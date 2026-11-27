<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// Bulk alias recalculation. Admin only, CSRF validated, preview by default.

$phpwcms = array('SESSION_START' => true);
global $BL;

require_once __DIR__ . '/../config/conf.inc.php';
require_once __DIR__ . '/../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';

$user_lang = !empty($_SESSION['wcs_user_lang']) ? strtolower(substr($_SESSION['wcs_user_lang'], 0, 2)) : 'en';
if (!preg_match('/^[a-z]{2}$/', $user_lang)) {
    $user_lang = 'en';
}
require_once PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php';
$cust_lang = PHPWCMS_ROOT.'/include/inc_lang/backend/'.$user_lang.'/lang.inc.php';
if (is_file($cust_lang)) {
    include $cust_lang;
}

checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

$ref = empty($_SESSION['REFERER_URL']) ? PHPWCMS_URL.'phpwcms.php?'.get_token_get_string() : $_SESSION['REFERER_URL'];

// Bulk URL rewrites are an administrator task only, and only via the form.
if (empty($_SESSION['wcs_user_admin']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['recalc_mode'])) {
    headerRedirect($ref);
}

$mode    = ($_POST['recalc_mode'] ?? '') === 'title' ? 'title' : 'structure';
$keep_redirects = !empty($_POST['recalc_redirect']);

$post_token = strtolower((string)($_POST['recalc_token'] ?? ''));
if (!preg_match('/^[a-f0-9]{32}$/', $post_token)) {
    $post_token = '';
}

$result_key = 'alias_recalc_result_' . (int)$_SESSION['wcs_user_id'];
$pending    = $_SESSION[$result_key] ?? null;

if (!empty($_POST['recalc_cancel'])) {
    unset($_SESSION[$result_key]);
    headerRedirect($ref);
}

$result = phpwcms_alias_recalc($mode);
$error  = '';

$confirmed = phpwcms_alias_pending_valid($pending, $post_token);

// Build the pending confirmation payload; the token binds the plan to the form.
$queue_confirmation = static function (array $plan) use ($mode, $keep_redirects) {
    $plan['row_count'] = count($plan['rows']);
    $plan['signature'] = phpwcms_alias_plan_signature($plan['rows']);
    if ($plan['row_count'] > 200) {
        $plan['rows'] = array_slice($plan['rows'], 0, 200);
    }
    $plan['mode']      = $mode;
    $plan['redirects'] = $keep_redirects;
    $plan['pending']   = true;
    $plan['auto_open'] = true;
    $plan['token']     = bin2hex(random_bytes(16));
    $plan['time']      = time();
    $plan['error']     = '';

    return $plan;
};

// Server-side confirmation step: the first request only stores the plan.
if (!$confirmed && !empty($result['rows'])) {
    $_SESSION[$result_key] = $queue_confirmation($result);

    headerRedirect($ref);
}

// The plan changed after the preview: confirm the refreshed one instead.
if (
    $confirmed
    && !empty($pending['signature'])
    && $pending['signature'] !== phpwcms_alias_plan_signature($result['rows'])
) {
    $_SESSION[$result_key] = $queue_confirmation($result);

    headerRedirect($ref);
}

if ($confirmed && !empty($result['rows']) && !_dbQuery(
    'LOCK TABLES '.DB_PREPEND.'article WRITE, '.DB_PREPEND.'articlecat WRITE, '.DB_PREPEND.'content READ',
    'SET'
)) {
    // Never rewrite without the lock; the values may be stale otherwise.
    $error = $BL['be_error_while_save'] ?? 'Storing data failed.';
    $result['rows'] = [];
}

$redirect_error = false;
$applied        = false;

if ($confirmed && $error === '' && !empty($result['rows'])) {
    // Verify that none of the changed rows moved since the calculation.
    $conflict = false;
    $checks   = ['category' => [], 'article' => []];
    foreach ($result['rows'] as $row) {
        $checks[$row['type']][(int)$row['id']] = $row['old'];
    }
    foreach ($checks as $type => $values) {
        $table    = $type === 'category' ? 'articlecat' : 'article';
        $idcol    = $type === 'category' ? 'acat_id' : 'article_id';
        $aliascol = $type === 'category' ? 'acat_alias' : 'article_alias';
        foreach (array_chunk($values, 500, true) as $chunk) {
            $ids  = implode(',', array_map('intval', array_keys($chunk)));
            $rows = _dbGet($table, $idcol.','.$aliascol, $idcol.' IN ('.$ids.')');
            if (!is_array($rows)) {
                $conflict = true;
                break 2;
            }
            foreach ($rows as $current) {
                if (($chunk[(int)$current[$idcol]] ?? null) !== (string)$current[$aliascol]) {
                    $conflict = true;
                    break 2;
                }
            }
        }
    }

    // Every target alias must be free outside the changed rows.
    if (!$conflict) {
        $changed_ids = ['articlecat' => [], 'article' => []];
        foreach ($result['rows'] as $row) {
            $changed_ids[$row['type'] === 'category' ? 'articlecat' : 'article'][] = (int)$row['id'];
        }
        foreach (array_chunk(array_unique(array_column($result['rows'], 'new')), 500) as $chunk) {
            $list = implode(',', array_map('_dbEscape', $chunk));
            $where = [
                'articlecat' => 'acat_alias IN ('.$list.')',
                'article'    => 'article_alias IN ('.$list.')',
                'content'    => 'cnt_alias IN ('.$list.')',
            ];
            if (!empty($changed_ids['articlecat'])) {
                $where['articlecat'] .= ' AND acat_id NOT IN ('.implode(',', $changed_ids['articlecat']).')';
            }
            if (!empty($changed_ids['article'])) {
                $where['article'] .= ' AND article_id NOT IN ('.implode(',', $changed_ids['article']).')';
            }
            foreach (['articlecat' => 'acat_id', 'article' => 'article_id', 'content' => 'cnt_id'] as $table => $idcol) {
                if (_dbQuery('SELECT COUNT('.$idcol.') FROM '.DB_PREPEND.$table.' WHERE '.$where[$table], 'COUNT') > 0) {
                    $conflict = true;
                    break 2;
                }
            }
        }
    }

    if ($conflict) {
        $error = $BL['be_error_while_save'] ?? 'Storing data failed.';
        $result['rows'] = [];
    } else {
        foreach ($result['rows'] as $row) {
            if ($row['type'] === 'category') {
                _dbQuery(
                    "UPDATE ".DB_PREPEND."articlecat SET acat_alias="._dbEscape($row['new'])." WHERE acat_id=".(int)$row['id'],
                    'UPDATE'
                );
            } else {
                _dbQuery(
                    "UPDATE ".DB_PREPEND."article SET article_alias="._dbEscape($row['new'])." WHERE article_id=".(int)$row['id'],
                    'UPDATE'
                );
            }
        }

        $applied = true;
    }
    _dbQuery('UNLOCK TABLES', 'SET');

    if ($applied && $keep_redirects) {
        // Keep old URLs alive: 301 from every previous alias to its new one.
        if (_dbQuery('LOCK TABLES '.DB_PREPEND.'redirect WRITE', 'SET')) {
            $entries = phpwcms_alias_redirect_entries($result['rows']);
            $existing = [];
            foreach (array_chunk(array_column($entries, 'alias'), 500) as $chunk) {
                $found = _dbGet('redirect', 'alias', 'alias IN ('.implode(',', array_map('_dbEscape', $chunk)).')');
                if ($found === false) {
                    $redirect_error = true; // never risk duplicates on a failed lookup
                    break;
                }
                foreach ($found as $row) {
                    $existing[$row['alias']] = true;
                }
            }
            foreach ($redirect_error ? [] : $entries as $entry) {
                if (isset($existing[$entry['alias']])) {
                    continue; // never overwrite an existing redirect entry
                }
                $inserted = _dbInsert('redirect', $entry);
                if (!$inserted) {
                    $redirect_error = true;
                }
            }
            _dbQuery('UNLOCK TABLES', 'SET');
        } else {
            $redirect_error = true;
        }
    }

    if ($applied) {
        set_status_message(
            $BL['be_successfully_saved'] . ' ' . sprintf($BL['be_alias_recalc_done'], count($result['rows'])),
            'success'
        );
    }
    if ($error === '') {
        update_cache();
    }
}

// Keep the session payload small: only the summary and a capped sample.
$stored              = $result;
$stored['row_count'] = count($result['rows']);
if ($stored['row_count'] > 200) {
    $stored['rows'] = array_slice($stored['rows'], 0, 200);
}
$stored['error']          = $error;
$stored['redirect_error'] = $redirect_error;
$stored['mode']           = $mode;
$stored['redirects']      = $keep_redirects;
if ($confirmed && empty($result['rows']) && $error === '') {
    // Confirmed apply found nothing to do: tell the admin instead of silence.
    set_status_message($BL['be_alias_recalc_none'], 'info');
}
if (!$confirmed || $error !== '' || $redirect_error) {
    // First request with nothing to change, and every partial/blocked run, opens the modal.
    $stored['auto_open'] = true;
}

$_SESSION[$result_key] = $stored;

headerRedirect($ref);
