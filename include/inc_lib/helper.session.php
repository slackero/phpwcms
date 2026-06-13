<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

if (!defined('CMSGO_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}

header('Access-Control-Allow-Origin: ' . CMSGO_BASEURL);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');

// Cookie settings
if (!empty($cmsgo['SESSION_START'])) {
    _initSession();
}

/**
 * Set session var.
 *
 * @access public
 * @param string $key
 * @param mixed $data
 * @return null|true
 */
function set_session_var($key, $data)
{
    if (isset($_SESSION) && is_string($key)) {
        $_SESSION[$key] = $data;

        return true;
    }

    return null;
}

/**
 * Get value of a session var.
 * Returns $fallback if session is not set.
 *
 * @access public
 * @param string $key
 * @param mixed $fallback (default: null)
 * @return mixed
 */
function get_session_var($key, $fallback = null)
{
    return $_SESSION[$key] ?? $fallback;
}

/**
 * Unset session var.
 *
 * @access public
 * @param string $key
 * @return null
 */
function unset_session_var($key)
{

    if (isset($_SESSION[$key])) {

        $_SESSION[$key] = '';
        unset($_SESSION[$key]);

    }

    return null;
}

/**
 * Generates a unique token name.
 *
 * @access public
 * @param string $prefix (default: 'csrf')
 * @return string
 */
function generate_token_name($prefix = 'csrf')
{
    return $prefix . '_' . bin2hex(random_bytes(16));
}

/**
 * Generate a more short token with shorter value.
 * The token is registered as session var too.
 * Generates a shorter token as recommend with GET parameters.
 *
 * @access public
 * @param string $get_token_name (default: 'csrftoken')
 * @return string
 */
function generate_get_token($get_token_name = 'csrftoken')
{
    $token_name = '_gettoken_' . $get_token_name;
    $token_value = bin2hex(random_bytes(32));

    set_session_var($token_name, $token_value);

    return $token_value;
}

/**
 * Get the token value.
 * Returns empty string if token is not registered in the session.
 *
 * @access public
 * @param string $get_token_name (default: 'csrftoken')
 * @return mixed
 */
function get_token_get_value($get_token_name = 'csrftoken')
{
    $token_name = '_gettoken_' . $get_token_name;

    return get_session_var($token_name, '');
}

/**
 * Get the token and token value formatted as GET parameter 'name=value'.
 * Value part is empty string if token is not registered in the session 'name='.
 *
 * @access public
 * @param string $get_token_name (default: 'csrftoken')
 * @return string
 */
function get_token_get_string($get_token_name = 'csrftoken')
{
    return $get_token_name . '=' . get_token_get_value($get_token_name);
}

/**
 * Get the token and token value as array.
 * Value part is empty string if token is not registered in the session 'name='.
 *
 * @access public
 * @param string $get_token_name (default: 'csrftoken')
 * @return array
 */
function get_token_get_array($get_token_name = 'csrftoken')
{
    return [
        'name' => $get_token_name,
        'value' => get_token_get_value($get_token_name)
    ];
}

/**
 * Generate a complex unique token and token value and store it in a session value.
 *
 * @access public
 * @param string $unique_name
 * @return string
 */
function generate_session_token($unique_name)
{
    $token = bin2hex(random_bytes(32));

    set_session_var($unique_name, $token);
    set_cached_token($unique_name);

    return $token;
}

/**
 * Store a session key to the unique token cache.
 *
 * @access public
 * @param string $unique_name
 * @return void
 */
function set_cached_token($unique_name)
{
    if (!isset($_SESSION['cached_unique_tokens'])) {
        $_SESSION['cached_unique_tokens'] = [];
    }

    $_SESSION['cached_unique_tokens'][$unique_name] = time();
}

/**
 * Unset in unique token cache.
 *
 * @access public
 * @param string $unique_name
 * @return void
 */
function unset_cached_token($unique_name)
{
    unset($_SESSION['cached_unique_tokens'][$unique_name]);
}

/**
 * Validate the token.
 *
 * @access public
 * @param string $unique_name
 * @param string $token_value
 * @return bool
 */
function validate_session_token($unique_name, $token_value)
{
    $token = get_session_var($unique_name);

    if (empty($token)) {
        return false;
    }

    if ($unique_name !== 'csrf_form_token') {
        unset_session_var($unique_name);
        unset_cached_token($unique_name);
    }

    return $token === $token_value;
}

/**
 * Return a unique ID from integer or reverse it.
 * rand_uniqid(9007199254740989) = 'PpQXn7COf'
 * rand_uniqid('PpQXn7COf', true) = '9007199254740989'
 *
 * **Refactored** on 2026-06-13 with the following optimizations and improvements:
 *
 * 1. **Edge Case Safety**: Added checking for values `< 1.0` or non-finite
 *    logarithms (e.g. `log(0)`) to prevent division by zero, float warnings,
 *    or negative array indexing.
 * 2. **Simplified Permutation Logic**: Replaced the nested index copy/rebuilding
 *    loops and redundant secondary hashing (SHA-512) with a direct `str_split`
 *    on the SHA-256 hash output, preserving identical sorting key mapping
 *    for backward-compatible passkey values.
 * 3. **Index Access Optimization**: Substituted slow `substr($index, $pos, 1)`
 *    string extractions with direct character array offsets (`$index[$pos]`).
 * 4. **Improved Math Types**: Ensured values are cast appropriately to floats or
 *    integers to match the expected mathematical inputs for exponent operations.
 *
 * @access public
 * @param mixed $in
 * @param bool $to_num (default: false)
 * @param bool|int $pad_up (default: false)
 * @param mixed $passkey (default: null)
 *
 * @return string
 *
 * @link http://php.net/manual/de/function.uniqid.php#96898
 * @author Enrico Pallazzo
 */
function rand_uniqid($in, $to_num = false, $pad_up = false, $passkey = null): string
{
    $index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $base = 62;

    if ($passkey !== null) {
        $i = str_split($index);
        $passhash = hash('sha256', $passkey);
        $p = str_split(substr($passhash, 0, $base));
        array_multisort($p, SORT_DESC, $i);
        $index = implode('', $i);
    }

    if ($to_num) {
        // Digital number <<-- alphabet letter code
        $in = strrev((string)$in);
        $out = 0.0;
        $len = strlen($in);
        for ($t = 0; $t < $len; $t++) {
            $pos = strpos($index, $in[$t]);
            if ($pos === false) {
                continue;
            }
            $bcpow = bcpow((string)$base, (string)($len - 1 - $t));
            $out += $pos * (float)$bcpow;
        }

        if (is_numeric($pad_up)) {
            $pad_up = (int)$pad_up - 1;
            if ($pad_up > 0) {
                $out -= $base ** $pad_up;
            }
        }
        $out = sprintf('%F', $out);
        $dotPos = strpos($out, '.');
        return $dotPos !== false ? substr($out, 0, $dotPos) : $out;
    }

    // Digital number -->> alphabet letter code
    $in = (float)$in;
    if (is_numeric($pad_up)) {
        $pad_up = (int)$pad_up - 1;
        if ($pad_up > 0) {
            $in += $base ** $pad_up;
        }
    }

    if ($in < 1.0) {
        return $index[0];
    }

    $out = '';
    $logVal = log($in, $base);
    if (is_finite($logVal)) {
        for ($t = (int)floor($logVal); $t >= 0; $t--) {
            $bcp = (float)bcpow((string)$base, (string)$t);
            $a = floor($in / $bcp) % $base;
            $out .= $index[$a];
            $in -= ($a * $bcp);
        }
    }

    return strrev($out);
}

/**
 * Search forms and add (CSRF) tokens.
 *
 * @access public
 * @param string $html
 * @return string
 */
function tokenize_forms($html)
{
    return preg_replace_callback('/<form(.*?)>/s', 'get_tokenized_form', $html);
}

/**
 * Callback function to add the CSRF token input fields to a form.
 * Forms with attribute 'data-csrf="off"' are ignored.
 *
 * @access public
 * @param array $match
 * @param string $token_prefix (default: 'csrf_')
 * @return string
 */
function get_tokenized_form($match, $token_prefix = 'csrf_')
{
    $form = $match[0];

    if (!str_contains($match[1], 'data-csrf="off"')) {

        $token_name = 'csrf_form_token';
        $token_value = get_session_var($token_name);
        if (empty($token_value)) {
            $token_value = generate_session_token($token_name);
        }

        $form .= '<input type="hidden" name="' . $token_prefix . 'token_name" value="' . $token_name . '" />';
        $form .= '<input type="hidden" name="' . $token_prefix . 'token_value" value="' . $token_value . '" />';

    }

    return $form;
}

/**
 * Add csrftoken GET parameter to backend links.
 *
 * @access public
 * @param string $html
 * @return string
 */
function tokenize_urls($html)
{
    $get_token = get_token_get_string();

    if ($get_token) {

        $search = [
            'cmsgo.php?',
            '/act_structure.php?',
            '/act_articlecontent.php?',
            '/act_file.php?',
            '/act_download.php?',
            '/act_filecat.php?',
            '/act_usergroup.php?',
            '/act_user.php?',
            '/act_frontendsetup.php?',
            '/act_message.php?',
            'cmsgo.php"',
            'articlebrowser.php?opt',
            'filebrowser.php?opt',
            $get_token . '&amp;' . $get_token
        ];

        $replace = [
            'cmsgo.php?' . $get_token . '&amp;',
            '/act_structure.php?' . $get_token . '&amp;',
            '/act_articlecontent.php?' . $get_token . '&amp;',
            '/act_file.php?' . $get_token . '&amp;',
            '/act_download.php?' . $get_token . '&amp;',
            '/act_filecat.php?' . $get_token . '&amp;',
            '/act_usergroup.php?' . $get_token . '&amp;',
            '/act_user.php?' . $get_token . '&amp;',
            '/act_frontendsetup.php?' . $get_token . '&amp;',
            '/act_message.php?' . $get_token . '&amp;',
            'cmsgo.php?' . $get_token . '"',
            'articlebrowser.php?' . $get_token . '&opt',
            'filebrowser.php?' . $get_token . '&opt',
            $get_token
        ];

        $html = str_replace($search, $replace, $html);
    }

    return $html;
}

/**
 * Validate CSRF tokens, POST and GET.
 * User will get logged out in case error reporting does not stop script.
 *
 * @access public
 * @param string $token_prefix (default: 'csrf_')
 * @return void
 */
function validate_csrf_tokens($token_prefix = 'csrf_')
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && count($_POST)) {

        if (empty($_POST[$token_prefix . 'token_name']) || empty($_POST[$token_prefix . 'token_value'])) {
            handle_csrf_error('csrf-post-invalid');
        }

        if (!validate_session_token($_POST[$token_prefix . 'token_name'], $_POST[$token_prefix . 'token_value'])) {
            handle_csrf_error('csrf-post-failed');
        }

    } else {

        validate_csrf_get_token();

    }

    // Purge cached tokens
    if ($cached_tokens = get_session_var('cached_unique_tokens')) {

        $now = time();
        $timespan = 60 * 15; // 15 Minutes

        foreach ($cached_tokens as $unique_name => $time) {

            if ($time < ($now - $timespan)) {
                unset_session_var($unique_name);
                unset_cached_token($unique_name);
            }

        }

    }
}

/**
 * Validate CSRF token, GET only.
 * User will get logged out in case $logout=true and error reporting does not stop script.
 *
 * @access public
 * @param string $token_name (default: 'csrftoken')
 * @param bool $logout (default: true)
 * @return bool
 */
function validate_csrf_get_token($token_name = 'csrftoken', $logout = true)
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        if (empty($_GET[$token_name])) {
            if ($logout) {
                handle_csrf_error('csrf-get-invalid');
            }
            return false;
        }

        if ($_GET[$token_name] !== get_token_get_value($token_name)) {
            if ($logout) {
                handle_csrf_error('csrf-get-failed');
            }
            return false;
        }

    }

    return true;
}

/**
 * Renders a premium, glassmorphic security error screen when a CSRF validation fails.
 *
 * @access public
 * @param string $reason
 * @return never
 */
function handle_csrf_error($reason)
{
    global $BL;

    // Proactively load translation files if they aren't loaded yet (boot order issue)
    if (empty($BL)) {
        $user_lang = 'en';
        if (!empty($_SESSION['wcs_user_lang']) && preg_match('/[a-z]{2}/i', $_SESSION['wcs_user_lang'])) {
            $user_lang = strtolower($_SESSION['wcs_user_lang']);
        } elseif (!empty($_COOKIE['cmsgoBELang'])) {
            $user_lang = strtolower(substr(trim($_COOKIE['cmsgoBELang']), 0, 2));
        }

        $en_lang_file = CMSGO_ROOT . '/include/inc_lang/backend/en/lang.inc.php';
        if (is_file($en_lang_file)) {
            include_once $en_lang_file;
        }

        if ($user_lang !== 'en') {
            $cust_lang_file = CMSGO_ROOT . '/include/inc_lang/backend/' . $user_lang . '/lang.inc.php';
            if (is_file($cust_lang_file)) {
                include_once $cust_lang_file;
            }
        }
    }

    // Prevent caching
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }

    $reason_title = $BL['CSRF_ERROR_TITLE'] ?? 'Security Validation Failed';
    $reason_desc = 'The request could not be verified because the security token was invalid or expired. To protect your data, the operation was aborted.';

    if ($reason === 'csrf-post-invalid') {
        $reason_desc = $BL['CSRF_POST_INVALID'] ?? 'No <a href="https://en.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a> POST parameters found. Form submission aborted.';
    } elseif ($reason === 'csrf-post-failed') {
        $reason_desc = $BL['CSRF_POST_FAILED'] ?? 'Validating <a href="https://en.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a> POST parameters failed. Form submission aborted.';
    } elseif ($reason === 'csrf-get-invalid') {
        $reason_desc = $BL['CSRF_GET_INVALID'] ?? 'No <a href="https://en.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a> GET parameters found. Navigation aborted.';
    } elseif ($reason === 'csrf-get-failed') {
        $reason_desc = $BL['CSRF_GET_FAILED'] ?? 'Validating <a href="https://en.wikipedia.org/wiki/Cross-site_request_forgery" target="_blank" rel="noopener noreferrer">CSRF</a> GET parameters failed. Navigation aborted.';
    }

    $bootstrap_css = CMSGO_URL . 'include/inc_css/bootstrap.min.css';
    $login_css = CMSGO_URL . 'include/inc_css/login.min.css';
    $fontawesome_css = CMSGO_URL . 'include/inc_css/cmsgo-fontawesome.css';
    $special_css = CMSGO_URL . 'include/inc_css/cmsgospecial.min.css';
    $logo_svg = CMSGO_URL . 'img/logo.svg';

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo html_specialchars($reason_title); ?> - cmsGO!</title>
        <link href="<?php echo $bootstrap_css; ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo $login_css; ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo $fontawesome_css; ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo $special_css; ?>" rel="stylesheet" type="text/css">
    </head>
    <body id="login">
    <div id="container">
        <header id="header" class="navbar navbar-static-top">
            <div class="container-fluid">
                <div id="header-logo" class="navbar-header">
                    <a href="<?php echo CMSGO_URL . get_login_file(); ?>" class="navbar-brand"><img class="border-0" src="<?php echo $logo_svg; ?>" alt="cmsGO! Content Management System" title="cmsGO! Content Management System" /></a>
                </div>
                <a href="#" id="button-menu" class="d-md-none d-lg-none d-xl-none"><span class="fa fa-bars"></span></a>
            </div>
        </header>

        <div id="content">
            <div class="container-fluid">
                <div class="row justify-content-md-center">
                    <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                        <div class="card mt-5 shadow-sm">
                            <div class="card-header text-center pt-4 bg-danger text-white">
                                <div class="text-white mb-3">
                                    <i class="fa fa-exclamation-triangle fa-3x"></i>
                                </div>
                                <h2 class="card-title text-white mb-0"><strong><?php echo html_specialchars($reason_title); ?></strong></h2>
                            </div>
                            <div class="card-body text-center pb-4">
                                <p class="card-text text-muted mb-4 px-3" style="line-height: 1.6;"><?php echo $reason_desc; // contains HTML link, not escaped ?></p>
                                <div class="d-flex justify-content-center gap-2" style="gap: 12px;">
                                    <a href="javascript:history.back()" class="btn btn-blue"><?php echo html_specialchars($BL['CSRF_BTN_BACK'] ?? 'Go Back'); ?></a>
                                    <a href="<?php echo CMSGO_URL . get_login_file(); ?>" class="btn btn-light"><?php echo html_specialchars($BL['CSRF_BTN_LOGIN'] ?? 'Login'); ?></a>
                                </div>
 							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit();
}

/**
 * Check if a user belongs to a specific user group (by syskey).
 * Uses local static caching to avoid redundant database lookups.
 *
 * @access public
 * @param int $user_id The unique user ID.
 * @param string $group_syskey The system key identifying the user group.
 * @return bool True if the user is a member of the group, false otherwise.
 */
function is_user_in_group($user_id, $group_syskey)
{
    static $group_cache = [];

    $user_id = (int)$user_id;
    if (!$user_id || empty($group_syskey)) {
        return false;
    }

    if (!isset($group_cache[$group_syskey])) {
        $sql = 'SELECT group_member FROM ' . DB_PREPEND . 'cmsgo_usergroup WHERE group_syskey = ' . _dbEscape($group_syskey) . ' AND group_active = 1 AND group_trash = 0 LIMIT 1';
        $result = _dbQuery($sql);
        if (isset($result[0]['group_member']) && trim($result[0]['group_member']) !== '') {
            $members = explode(',', $result[0]['group_member']);
            $group_cache[$group_syskey] = array_map('intval', array_map('trim', $members));
        } else {
            $group_cache[$group_syskey] = [];
        }
    }

    return in_array($user_id, $group_cache[$group_syskey], true);
}

/**
 * Check if the current user has permission for a specific admin action.
 * Super-administrators bypass this check and are always authorized.
 *
 * @access public
 * @param string $permission The system key of the permission group (e.g. 'admuser', 'admugroup').
 * @return bool True if the current user has the permission, false otherwise.
 */
function has_admin_permission($permission)
{
    if (!empty($_SESSION['wcs_user_admin']) && (int)$_SESSION['wcs_user_admin'] === 1) {
        return true;
    }
    if (empty($_SESSION['wcs_user_id'])) {
        return false;
    }
    return is_user_in_group($_SESSION['wcs_user_id'], $permission);
}
