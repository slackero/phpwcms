<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// change value in table (aktive, public etc.)

$phpwcms = array('SESSION_START' => true);
require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

if(empty($_SESSION["wcs_user_id"]) || !validate_csrf_get_token()) {
    headerRedirect('', 401);
    die('Sorry, access forbidden');
}

$table = isset($_GET['table']) ? _dbEscape(clean_slweg($_GET['table']), false) : false;
$field = isset($_GET['field']) ? _dbEscape(clean_slweg($_GET['field']), false) : false;
$fieldid = isset($_GET['fieldid']) ? _dbEscape(clean_slweg($_GET['fieldid']), false) : false;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fields a backend list may toggle, keyed by table and column. The id column
// is fixed here and the permission mirrors the screen that renders the
// toggle, so a request cannot reach another row, column or table than the one
// it names. Owners are restricted through the article/file ownership rules.
function ajax_toggle_rules() {
    return [
        'article'        => ['article_aktiv'       => ['id' => 'article_id',       'owner' => 'article']],
        'articlecontent' => ['acontent_visible'    => ['id' => 'acontent_id',      'owner' => 'articlecontent']],
        'articlecat'     => ['acat_aktiv'          => ['id' => 'acat_id', 'any' => [['permission' => 'adm'], ['group' => 'artcent']]]],
        'file'           => [
            'f_aktiv'  => ['id' => 'f_id', 'owner' => 'file'],
            'f_public' => ['id' => 'f_id', 'owner' => 'file'],
        ],
        'content'        => ['cnt_status'          => ['id' => 'cnt_id', 'any' => [['group' => 'artcent'], ['group' => 'artnews']]]],
        'redirect'       => ['active'              => ['id' => 'rid',             'permission' => 'adm']],
        'subscription'   => ['subscription_active' => ['id' => 'subscription_id', 'admin' => true]],
        'address'        => ['address_verified'    => ['id' => 'address_id',      'admin' => true]],
        'usergroup'      => ['group_active'        => ['id' => 'group_id',        'permission' => 'admugroup']],
        'user'           => ['usr_aktiv'           => ['id' => 'usr_id',          'permission' => 'admuser']],
        'shop_products'  => ['shopprod_status'     => ['id' => 'shopprod_id',     'module' => 'shop']],
        'categories'     => ['cat_status'          => ['id' => 'cat_id',          'module' => 'shop']],
    ];
}

$toggle_rules = ajax_toggle_rules();

/**
 * Resolve the permission rule of a toggle.
 *
 * @param array $rule    Rule of the whitelisted table/field pair
 * @param int   $user_id Current backend user
 *
 * @return array{0: bool, 1: string} Allowed flag and extra SQL restriction
 */
function ajax_toggle_restriction($rule, $user_id) {
    $user_id = (int) $user_id;
    $allowed = true;
    $where   = '';

    if (!empty($rule['any'])) {
        // any of the listed rules grants the toggle, e.g. admin OR the
        // permission of the section that renders the list
        foreach ($rule['any'] as $sub_rule) {
            [$sub_allowed] = ajax_toggle_restriction($sub_rule, $user_id);
            if ($sub_allowed) {
                return [true, ''];
            }
        }
        return [false, ''];
    }

    if (!empty($rule['owner'])) {
        if ($rule['owner'] === 'article') {
            $where = article_owner_restriction($user_id, has_article_edit_all_permission());
        } elseif ($rule['owner'] === 'articlecontent') {
            $where = articlecontent_owner_restriction($user_id, has_article_edit_all_permission());
        } elseif ($rule['owner'] === 'file') {
            $allows_all = !empty($_SESSION['wcs_user_admin']) || is_user_in_group($user_id, 'filecent');
            if (!$allows_all) {
                $where = ' AND f_uid = ' . $user_id;
            }
        }
    } elseif (!empty($rule['permission'])) {
        $allowed = has_admin_permission($rule['permission']);
    } elseif (!empty($rule['group'])) {
        $allowed = is_user_in_group($user_id, $rule['group']);
    } elseif (!empty($rule['module'])) {
        $allowed = is_user_in_module_group($user_id, $rule['module']);
    } elseif (!empty($rule['admin'])) {
        $allowed = !empty($_SESSION['wcs_user_admin']);
    }

    return [$allowed, $where];
}

if($table && $field && $fieldid && $id) {

    $table = _dbNormalizeTable($table);

    if (!isset($toggle_rules[$table][$field]) || $toggle_rules[$table][$field]['id'] !== $fieldid) {
        headerRedirect('', 403);
        die('Sorry, access forbidden');
    }

    $rule = $toggle_rules[$table][$field];
    [$allowed, $where] = ajax_toggle_restriction($rule, (int) $_SESSION['wcs_user_id']);

    if (!$allowed) {
        headerRedirect('', 403);
        die('Sorry, access forbidden');
    }

    $sql = "UPDATE `" . DB_PREPEND . $table . "` SET `" . $field . "`= (CASE `" . $field . "` WHEN 1 THEN 0 ELSE 1 END) WHERE `" . $rule['id'] . "`=" . $id . $where;
    _dbQuery($sql, 'UPDATE');
}
