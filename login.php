<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = array('SESSION_START' => true);
$BL = array();

// Check if config is still at the old position
if(!is_file(__DIR__.'/include/config/conf.inc.php') && is_file(__DIR__.'/config/phpwcms/conf.inc.php')):
    if(!@rename(__DIR__.'/config/phpwcms', __DIR__.'/include/config')):

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <title>phpwcms configuration error</title>
    <style>
        body {
            background-color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 18px;
            color: #000;
        }
        h1 {
            font-size: 28px;
            color:#cc3300;
        }
    </style>
</head>
<body>
    <h1>
        <strong>Your configuration is placed at the wrong position.</strong>
    </h1>
    <p>
        Beginning with <strong>phpwcms v1.7.8</strong> base config files were moved from
        directory <code>config/phpwcms</code> to directory <code>include/config</code>. The fallback
        to do it automatically has failed. Please do it manually before you continue.
    </p>
</body>
</html>
<?php
        die();
    endif;
endif;

require_once __DIR__.'/include/config/conf.inc.php';
require_once __DIR__.'/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lang/code.lang.inc.php';

if (isset($_GET['reason']) && str_starts_with($_GET['reason'], 'csrf-')) {
    headerAvoidPageCaching();
}

logdir_exists();

$_SESSION['REFERER_URL'] = PHPWCMS_URL.get_login_file();

// make compatibility check
if(phpwcms_revision_check_temp($phpwcms["revision"]) !== true) {
    $revision_status = phpwcms_revision_check($phpwcms["revision"]);
}

// define vars
$err = 0;
$wcs_user = '';

// where user should be redirected too after login
if(isset($_POST['ref_url']) || isset($_GET['ref'])) {
    $ref_url = xss_clean(isset($_GET['ref']) ? rawurldecode($_GET['ref']) : $_POST['ref_url']);
    if (substr($ref_url, 0, strlen(PHPWCMS_URL)) !== PHPWCMS_URL) {
        $ref_url = '';
    }
} else {
    $ref_url = '';
}

$csrf_error = $_SERVER['REQUEST_METHOD'] === 'POST' && (empty($_POST['logintoken']) || $_POST['logintoken'] !== get_token_get_value());

define('LOGIN_TOKEN', generate_get_token());

// reset all inactive users
$sql  = "UPDATE " . DB_PREPEND . "phpwcms_userlog SET logged_in=0, logged_change='" . time() . "' ";
$sql .= "WHERE logged_in=1 AND (" . time() . "-logged_change) > ".intval($phpwcms["max_time"]);
_dbQuery($sql, 'UPDATE');

//load default language EN
require_once PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php';

$lang_aliases = ['cz' => 'cs', 'se' => 'sv', 'vn' => 'vi', 'el' => 'gr'];

//define language and check if language file is available
if(isset($_COOKIE['phpwcmsBELang'])) {
    $temp_lang = strtolower(trim($_COOKIE['phpwcmsBELang']));
    if (isset($lang_aliases[$temp_lang])) {
        $temp_lang = $lang_aliases[$temp_lang];
    }
    if (preg_match('/^[a-z]{2}(?:-[a-z]{2})?$/', $temp_lang) && is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$temp_lang.'/lang.inc.php')) {
        $_SESSION["wcs_user_lang"] = $temp_lang;
    } else {
        setcookie('phpwcmsBELang', '', time() - 3600, '/', getCookieDomain(), PHPWCMS_SSL, true);
    }
}
if(isset($_POST['form_lang'])) {
    $temp_lang = strtolower(trim(clean_slweg($_POST['form_lang'])));
    if (isset($lang_aliases[$temp_lang])) {
        $temp_lang = $lang_aliases[$temp_lang];
    }
    if (preg_match('/^[a-z]{2}(?:-[a-z]{2})?$/', $temp_lang) && is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$temp_lang.'/lang.inc.php')) {
        $_SESSION["wcs_user_lang"] = $temp_lang;
        set_language_cookie($temp_lang);
    }
}
if(empty($_SESSION["wcs_user_lang"])) {
    $detected_lang = '';
    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $accepted = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        foreach ($accepted as $al) {
            $al_parts = explode(';', trim($al));
            $al_code = strtolower(trim($al_parts[0]));
            if ($al_code === '') {
                continue;
            }
            if (isset($lang_aliases[$al_code])) {
                $al_code = $lang_aliases[$al_code];
            }
            if (is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$al_code.'/lang.inc.php')) {
                $detected_lang = $al_code;
                break;
            }
            $base_code = substr($al_code, 0, 2);
            if (isset($lang_aliases[$base_code])) {
                $base_code = $lang_aliases[$base_code];
            }
            if (is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$base_code.'/lang.inc.php')) {
                $detected_lang = $base_code;
                break;
            }
        }
    }
    if ($detected_lang === '') {
        $default_lang = strtolower($phpwcms['default_lang'] ?? 'en');
        if (isset($lang_aliases[$default_lang])) {
            $default_lang = $lang_aliases[$default_lang];
        }
        if (is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$default_lang.'/lang.inc.php')) {
            $detected_lang = $default_lang;
        } else {
            $detected_lang = 'en';
        }
    }
    $_SESSION["wcs_user_lang"] = $detected_lang;
}
if(is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$_SESSION["wcs_user_lang"].'/lang.inc.php')) {
    $_SESSION["wcs_user_lang_custom"] = 1;
} else {
    $_SESSION["wcs_user_lang"] = 'en';
    $_SESSION["wcs_user_lang_custom"] = 0;
}
if(!empty($_SESSION["wcs_user_lang_custom"])) {
    //use custom lang if available -> was set in login.php
    $BL['merge_lang_array'][0] = $BL['be_admin_optgroup_label'];
    $BL['merge_lang_array'][1] = $BL['be_cnt_field'];
    include_once PHPWCMS_ROOT.'/include/inc_lang/backend/'.$_SESSION["wcs_user_lang"].'/lang.inc.php';
    $BL['be_admin_optgroup_label'] = array_merge($BL['merge_lang_array'][0], $BL['be_admin_optgroup_label']);
    $BL['be_cnt_field'] = array_merge($BL['merge_lang_array'][1], $BL['be_cnt_field']);
}

//WYSIWYG EDITOR:
//0 = no wysiwyg editor (default)
//1 = CKEditor
//2 = TinyMCE
/** @phpstan-ignore-next-line */
$phpwcms["wysiwyg_editor"] = empty($phpwcms["wysiwyg_editor"]) ? 0 : intval($phpwcms["wysiwyg_editor"]);
$_SESSION["WYSIWYG_EDITOR"] = $phpwcms["wysiwyg_editor"];

destroyBackendSessionData();

$json_check = isset($_POST['json']) ? intval($_POST['json']) : 0;
$step_2fa   = false;
$backup_code_used = false;

// Handle Step 2: 2FA Verification
if (isset($_POST['form_aktion']) && $_POST['form_aktion'] === 'verify_2fa' && !empty($_SESSION['wcs_2fa_pending_uid'])) {

    $pending_uid = (int)$_SESSION['wcs_2fa_pending_uid'];
    $submitted_2fa_code = slweg($_POST['form_2fa_code'] ?? '');

    if (!$csrf_error && $submitted_2fa_code !== '') {

        $sql_query = 'SELECT * FROM ' . DB_PREPEND . 'phpwcms_user WHERE usr_id=' . $pending_uid . ' AND usr_aktiv=1 AND (usr_fe=1 OR usr_fe=2) LIMIT 1';
        $result = _dbQuery($sql_query);

        if (isset($result[0]['usr_id'])) {
            $user_vars = @unserialize($result[0]['usr_vars'], ['allowed_classes' => false]);
            if (!is_array($user_vars)) {
                $user_vars = [];
            }

            $tfa_valid = false;

            // 1. Check standard 6-digit TOTP
            if (!empty($result[0]['usr_2fa_secret']) && strlen($submitted_2fa_code) === 6 && ctype_digit($submitted_2fa_code)) {
                $tfa_valid = PhpwcmsTwoFactor::verifyCode($result[0]['usr_2fa_secret'], $submitted_2fa_code);
            }

            // 2. Check backup recovery codes
            if (!$tfa_valid && !empty($user_vars['2fa_backup_codes']) && is_array($user_vars['2fa_backup_codes'])) {
                if (PhpwcmsTwoFactor::verifyAndConsumeBackupCode($user_vars['2fa_backup_codes'], $submitted_2fa_code)) {
                    $tfa_valid = true;
                    $backup_code_used = true;
                    _dbUpdate('phpwcms_user', ['usr_vars' => serialize($user_vars)], 'WHERE usr_id=' . $pending_uid);
                }
            }

            if ($tfa_valid) {
                $wcs_user = $result[0]['usr_login'];
                unset($_SESSION['wcs_2fa_pending_uid'], $_SESSION['wcs_2fa_user_row'], $_SESSION['wcs_2fa_ref_url'], $_SESSION['wcs_2fa_customlang']);

                $_SESSION['wcs_user']           = $wcs_user;
                $_SESSION['wcs_user_name']      = empty($result[0]['usr_name']) ? $wcs_user : $result[0]['usr_name'];
                $_SESSION['wcs_user_id']        = $result[0]['usr_id'];
                $_SESSION['wcs_user_aktiv']     = $result[0]['usr_aktiv'];
                $_SESSION['wcs_user_rechte']    = $result[0]['usr_rechte'];
                $_SESSION['wcs_user_email']     = $result[0]['usr_email'];
                $_SESSION['wcs_user_avatar']    = $result[0]['usr_avatar'];
                $_SESSION['wcs_user_logtime']   = time();
                $_SESSION['wcs_user_admin']     = intval($result[0]['usr_admin']);
                $_SESSION['wcs_user_thumb']     = 1;

                if (empty($_POST['customlang']) && !empty($result[0]['usr_lang'])) {
                    $usr_lang = strtolower($result[0]['usr_lang']);
                    if (isset($lang_aliases[$usr_lang])) {
                        $usr_lang = $lang_aliases[$usr_lang];
                    }
                    $_SESSION['wcs_user_lang'] = $usr_lang;
                    set_language_cookie($usr_lang);
                } elseif (!empty($_SESSION['wcs_user_lang'])) {
                    set_language_cookie($_SESSION['wcs_user_lang']);
                } else {
                    set_language_cookie();
                }

                $_SESSION['structure'] = @unserialize($result[0]['usr_var_structure'], ['allowed_classes' => false]);
                $_SESSION['klapp']     = @unserialize($result[0]['usr_var_privatefile'], ['allowed_classes' => false]);
                $_SESSION['pklapp']    = @unserialize($result[0]['usr_var_publicfile'], ['allowed_classes' => false]);

                if (!is_array($_SESSION['structure'])) {
                    $_SESSION['structure'] = [];
                }
                if (!is_array($_SESSION['klapp'])) {
                    $_SESSION['klapp'] = [];
                }
                if (!is_array($_SESSION['pklapp'])) {
                    $_SESSION['pklapp'] = [];
                }

                $_SESSION['WYSIWYG_EDITOR'] = empty($result[0]['usr_wysiwyg']) ? $phpwcms['wysiwyg_editor'] : intval($result[0]['usr_wysiwyg']);
                $_SESSION['wcs_user_theme'] = isset($user_vars['theme']) && in_array($user_vars['theme'], ['auto', 'light', 'dark'], true) ? $user_vars['theme'] : (!empty($_COOKIE['phpwcmsBETheme']) && in_array($_COOKIE['phpwcmsBETheme'], ['auto', 'light', 'dark'], true) ? $_COOKIE['phpwcmsBETheme'] : 'auto');
                set_theme_cookie($_SESSION['wcs_user_theme']);
                $_SESSION['wcs_user_cp']    = isset($user_vars['selected_cp']) && is_array($user_vars['selected_cp']) ? $user_vars['selected_cp'] : [];
                $_SESSION['wcs_allowed_cp'] = isset($user_vars['allowed_cp']) && is_array($user_vars['allowed_cp']) ? $user_vars['allowed_cp'] : [];

                if (count($_SESSION['wcs_allowed_cp'])) {
                    if (count($_SESSION['wcs_user_cp'])) {
                        foreach ($_SESSION['wcs_user_cp'] as $key => $value) {
                            if (!isset($_SESSION['wcs_allowed_cp'][$key])) {
                                unset($_SESSION['wcs_user_cp'][$key]);
                            }
                        }
                    } else {
                        $_SESSION['wcs_user_cp'] = $_SESSION['wcs_allowed_cp'];
                    }
                }

                // Store login information in DB
                if (!($check = _dbQuery('SELECT COUNT(*) FROM ' . DB_PREPEND . 'phpwcms_userlog WHERE logged_user=' . _dbEscape($wcs_user) . ' AND logged_in=1', 'COUNT'))) {
                    $sql  = 'INSERT INTO ' . DB_PREPEND . 'phpwcms_userlog (logged_user, logged_username, logged_start, logged_change, logged_in, logged_ip) VALUES (';
                    $sql .= _dbEscape($wcs_user) . ', ' . _dbEscape($_SESSION['wcs_user_name']) . ', ' . time() . ', ' . time() . ', 1, ' . _dbEscape(PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP()) . ')';
                    _dbQuery($sql, 'INSERT');
                }

                $_SESSION['PHPWCMS_ROOT'] = PHPWCMS_ROOT;
                set_status_message($BL['login_welcome'] . ', ' . $wcs_user . '!' . ($backup_code_used ? ' (' . ($BL['login_2fa_backup_used'] ?? 'Backup code used') . ')' : ''));

                if ($ref_url) {
                    if (($token_position = strpos($ref_url, 'csrftoken')) !== false) {
                        $ref_url = substr_replace($ref_url, '', $token_position, 42);
                        $ref_url = str_replace('?&', '?', $ref_url);
                        $ref_url = str_replace('&&', '&', $ref_url);
                    }
                    $backend_redirect = $ref_url . '&';
                } else {
                    $backend_redirect = PHPWCMS_URL . 'phpwcms.php?';
                }

                $_SESSION['PHPWCMS_BROWSER_HASH'] = $phpwcms['USER_AGENT']['hash'];
                headerRedirect($backend_redirect . get_token_get_string() . '&' . session_name() . '=' . session_id());
            }
        }
    }

    $err = 1;
    $step_2fa = true;

} elseif (isset($_POST['form_aktion']) && $_POST['form_aktion'] == 'login' && $json_check === 1) {

    $login_passed       = 0;
    $wysiwyg_template   = '';
    $wcs_user           = slweg($_POST['form_loginname']);
    $wcs_pass           = slweg($_POST['md5pass']);
    $plain_pass         = isset($_POST['form_password']) ? slweg($_POST['form_password']) : '';

    $sql_query  = "SELECT * FROM " . DB_PREPEND . "phpwcms_user WHERE usr_login=" . _dbEscape($wcs_user) . " AND usr_aktiv=1 AND (usr_fe=1 OR usr_fe=2)";

    if(!$csrf_error) {

        $result = _dbQuery($sql_query);

        if(isset($result[0]['usr_id'])) {

            $db_pass = $result[0]['usr_pass'];
            if ($plain_pass !== '') {
                if (str_starts_with($db_pass, '$')) {
                    if (password_verify($plain_pass, $db_pass)) {
                        $login_passed = 1;
                    } else {
                        $md5_pass = md5(makeCharsetConversion($plain_pass, PHPWCMS_CHARSET, 'utf-8'));
                        if (password_verify($md5_pass, $db_pass)) {
                            $login_passed = 1;
                            $new_hash = password_hash($plain_pass, PASSWORD_DEFAULT);
                            _dbQuery('UPDATE ' . DB_PREPEND . 'phpwcms_user SET usr_pass=' . _dbEscape($new_hash) . ' WHERE usr_id=' . (int)$result[0]['usr_id'], 'UPDATE');
                        }
                    }
                } else {
                    $md5_pass = md5(makeCharsetConversion($plain_pass, PHPWCMS_CHARSET, 'utf-8'));
                    if ($md5_pass === $db_pass) {
                        $login_passed = 1;
                        $new_hash = password_hash($plain_pass, PASSWORD_DEFAULT);
                        _dbQuery('UPDATE ' . DB_PREPEND . 'phpwcms_user SET usr_pass=' . _dbEscape($new_hash) . ' WHERE usr_id=' . (int)$result[0]['usr_id'], 'UPDATE');
                    }
                }
            } else {
                if (str_starts_with($db_pass, '$')) {
                    if (password_verify($wcs_pass, $db_pass)) {
                        $login_passed = 1;
                    }
                } else {
                    if ($wcs_pass === $db_pass) {
                        $login_passed = 1;
                        $new_hash = password_hash($wcs_pass, PASSWORD_DEFAULT);
                        _dbQuery('UPDATE ' . DB_PREPEND . 'phpwcms_user SET usr_pass=' . _dbEscape($new_hash) . ' WHERE usr_id=' . (int)$result[0]['usr_id'], 'UPDATE');
                    }
                }
            }

            if ($login_passed) {

                // Check if Two-Factor Authentication is enabled for this account
                if (!empty($result[0]['usr_2fa_enabled']) && !empty($result[0]['usr_2fa_secret'])) {
                    $_SESSION['wcs_2fa_pending_uid'] = (int)$result[0]['usr_id'];
                    $_SESSION['wcs_2fa_user_row']    = $result[0];
                    $_SESSION['wcs_2fa_ref_url']     = $ref_url;
                    $_SESSION['wcs_2fa_customlang']  = !empty($_POST['customlang']) ? 1 : 0;
                    $step_2fa = true;
                } else {

                    $_SESSION["wcs_user"]           = $wcs_user;
                    $_SESSION["wcs_user_name"]      = empty($result[0]["usr_name"]) ? $wcs_user : $result[0]["usr_name"];
                    $_SESSION["wcs_user_id"]        = $result[0]["usr_id"];
                    $_SESSION["wcs_user_aktiv"]     = $result[0]["usr_aktiv"];
                    $_SESSION["wcs_user_rechte"]    = $result[0]["usr_rechte"];
                    $_SESSION["wcs_user_email"]     = $result[0]["usr_email"];
                    $_SESSION["wcs_user_avatar"]    = $result[0]["usr_avatar"];
                    $_SESSION["wcs_user_logtime"]   = time();
                    $_SESSION["wcs_user_admin"]     = intval($result[0]["usr_admin"]);
                    $_SESSION["wcs_user_thumb"]     = 1;
                    if(empty($_POST['customlang']) && !empty($result[0]["usr_lang"])) {
                        $usr_lang = strtolower($result[0]["usr_lang"]);
                        if (isset($lang_aliases[$usr_lang])) {
                            $usr_lang = $lang_aliases[$usr_lang];
                        }
                        $_SESSION["wcs_user_lang"]  = $usr_lang;
                        set_language_cookie($usr_lang);
                    } elseif (!empty($_SESSION["wcs_user_lang"])) {
                        set_language_cookie($_SESSION["wcs_user_lang"]);
                    } else {
                        set_language_cookie();
                    }

                    $_SESSION["structure"] = @unserialize($result[0]["usr_var_structure"], ['allowed_classes' => false]);
                    $_SESSION["klapp"]     = @unserialize($result[0]["usr_var_privatefile"], ['allowed_classes' => false]);
                    $_SESSION["pklapp"]    = @unserialize($result[0]["usr_var_publicfile"], ['allowed_classes' => false]);
                    $result[0]["usr_vars"] = @unserialize($result[0]["usr_vars"], ['allowed_classes' => false]);

                    if(!is_array($_SESSION["structure"])) {
                        $_SESSION["structure"] = array();
                    }
                    if(!is_array($_SESSION["klapp"])) {
                        $_SESSION["klapp"] = array();
                    }
                    if(!is_array($_SESSION["pklapp"])) {
                        $_SESSION["pklapp"] = array();
                    }
                    if(!is_array($result[0]["usr_vars"])) {
                        $result[0]["usr_vars"] = array();
                    }

                    // Fallback to configured global editor
                    $_SESSION['WYSIWYG_EDITOR'] = empty($result[0]['usr_wysiwyg']) ? $phpwcms['wysiwyg_editor'] : intval($result[0]['usr_wysiwyg']);
                    if (isset($_POST['form_theme']) && in_array($_POST['form_theme'], array('auto', 'light', 'dark'), true)) {
                        $_SESSION['wcs_user_theme'] = $_POST['form_theme'];
                        if (!isset($result[0]['usr_vars']['theme']) || $result[0]['usr_vars']['theme'] !== $_POST['form_theme']) {
                            $result[0]['usr_vars']['theme'] = $_POST['form_theme'];
                            _dbUpdate('phpwcms_user', array('usr_vars' => serialize($result[0]['usr_vars'])), 'WHERE usr_id=' . intval($result[0]['usr_id']));
                        }
                    } else {
                        $_SESSION['wcs_user_theme'] = isset($result[0]['usr_vars']['theme']) && in_array($result[0]['usr_vars']['theme'], array('auto', 'light', 'dark'), true) ? $result[0]['usr_vars']['theme'] : (!empty($_COOKIE['phpwcmsBETheme']) && in_array($_COOKIE['phpwcmsBETheme'], array('auto', 'light', 'dark'), true) ? $_COOKIE['phpwcmsBETheme'] : 'auto');
                    }
                    set_theme_cookie($_SESSION['wcs_user_theme']);
                    $_SESSION['wcs_user_cp']    = isset($result[0]['usr_vars']['selected_cp']) && is_array($result[0]['usr_vars']['selected_cp']) ? $result[0]['usr_vars']['selected_cp'] : array();
                    $_SESSION['wcs_allowed_cp'] = isset($result[0]['usr_vars']['allowed_cp']) && is_array($result[0]['usr_vars']['allowed_cp']) ? $result[0]['usr_vars']['allowed_cp'] : array();

                    // Test if there are CPs that use had choosen but no longer available for
                    if(count($_SESSION["wcs_allowed_cp"])) {
                        if(count($_SESSION["wcs_user_cp"])) {
                            // Remove selected CP if not allowed CP
                            foreach($_SESSION["wcs_user_cp"] as $key => $value) {
                                if(!isset($_SESSION["wcs_allowed_cp"][$key])) {
                                    unset($_SESSION["wcs_user_cp"][$key]);
                                }
                            }
                        } else {
                            $_SESSION["wcs_user_cp"] = $_SESSION["wcs_allowed_cp"];
                        }
                    }

                }

            }
        }
    }

    if($login_passed && !$step_2fa) {

        // Store login information in DB
        if(!($check = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_userlog WHERE logged_user="._dbEscape($wcs_user)." AND logged_in=1", 'COUNT'))) {
            // User not yet logged in, create new
            $sql  = "INSERT INTO ".DB_PREPEND."phpwcms_userlog (logged_user, logged_username, logged_start, logged_change, logged_in, logged_ip) VALUES (";
            $sql .= _dbEscape($wcs_user).", "._dbEscape($_SESSION["wcs_user_name"]).", ".time().", ".time().", 1, "._dbEscape(PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP()).")";
            _dbQuery($sql, 'INSERT');
        }

        $_SESSION['PHPWCMS_ROOT'] = PHPWCMS_ROOT;
        set_status_message($BL["login_welcome"].', '.$wcs_user.'!');

        if($ref_url) {

            if(($token_position = strpos($ref_url, 'csrftoken')) !== false) {
                $ref_url = substr_replace($ref_url, '', $token_position, 42);
                $ref_url = str_replace('?&', '?', $ref_url);
                $ref_url = str_replace('&&', '&', $ref_url);
            }

            $backend_redirect = $ref_url . '&';

        } else {

            $backend_redirect = PHPWCMS_URL.'phpwcms.php?';

        }

        $_SESSION['PHPWCMS_BROWSER_HASH'] = $phpwcms['USER_AGENT']['hash'];

        headerRedirect($backend_redirect . get_token_get_string() . '&' . session_name().'='.session_id());

    } elseif (!$step_2fa) {

        $err = 1;

    }

} elseif(isset($_POST['form_loginname']) && $json_check !== 2) {

    $err = 1;

}

$step_reset_request = false;
$step_reset_set     = false;
$reset_sent_success = false;
$reset_done_success = false;
$reset_error_msg    = '';
$reset_token_valid  = false;
$reset_user_row     = null;

// Handle Password Reset Request
if (isset($_GET['reset']) && $_GET['reset'] === '1' && empty($_POST['form_aktion'])) {
    $step_reset_request = true;
}

// Process Password Reset Request (send email with reset token)
if (isset($_POST['form_aktion']) && $_POST['form_aktion'] === 'send_reset_link') {
    $step_reset_request = true;
    $reset_account = slweg($_POST['form_reset_account'] ?? '');

    if (!$csrf_error && $reset_account !== '') {
        $sql = 'SELECT usr_id, usr_login, usr_email, usr_name, usr_vars FROM ' . DB_PREPEND . 'phpwcms_user WHERE (usr_login = ' . _dbEscape($reset_account) . ' OR LOWER(usr_email) = ' . _dbEscape(strtolower($reset_account)) . ') AND usr_aktiv = 1 AND (usr_fe = 1 OR usr_fe = 2) LIMIT 1';
        $user_res = _dbQuery($sql);

        if (!empty($user_res[0]['usr_id']) && !empty($user_res[0]['usr_email']) && is_valid_email($user_res[0]['usr_email'])) {
            $user = $user_res[0];
            $u_vars = @unserialize($user['usr_vars'], ['allowed_classes' => false]);
            if (!is_array($u_vars)) {
                $u_vars = [];
            }

            $raw_token = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $raw_token);
            $token_expires = time() + 3600; // 1 hour validity

            $u_vars['password_reset'] = [
                'token_hash' => $token_hash,
                'expires'    => $token_expires,
                'ip'         => PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP()
            ];

            _dbUpdate('phpwcms_user', ['usr_vars' => serialize($u_vars)], 'WHERE usr_id = ' . (int)$user['usr_id']);

            $reset_link = PHPWCMS_URL . get_login_file() . '?reset_token=' . rawurlencode($raw_token) . '&u=' . (int)$user['usr_id'];
            $email_body = str_replace(
                ['{NAME}', '{LOGIN}', '{SITE}', '{RESET_LINK}'],
                [empty($user['usr_name']) ? $user['usr_login'] : $user['usr_name'], $user['usr_login'], PHPWCMS_HOST, $reset_link],
                $BL['login_reset_email_body'] ?? ''
            );
            $email_subject = str_replace('{SITE}', PHPWCMS_HOST, $BL['login_reset_email_subject'] ?? 'Password reset request for {SITE}');

            sendEmail([
                'recipient'  => $user['usr_email'],
                'toName'     => $user['usr_name'],
                'subject'    => $email_subject,
                'isHTML'     => false,
                'text'       => $email_body,
                'from'       => $phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? '',
                'fromName'   => 'phpwcms'
            ]);
        }

        // Always show the same neutral success message to prevent user enumeration
        $reset_sent_success = true;
    } else {
        $err = 1;
    }
}

// Verify Reset Token and Load Reset Password View
if (!empty($_GET['reset_token']) && !empty($_GET['u'])) {
    $token_param = slweg($_GET['reset_token']);
    $user_id_param = (int)$_GET['u'];

    $sql = 'SELECT usr_id, usr_login, usr_name, usr_vars FROM ' . DB_PREPEND . 'phpwcms_user WHERE usr_id = ' . $user_id_param . ' AND usr_aktiv = 1 AND (usr_fe = 1 OR usr_fe = 2) LIMIT 1';
    $user_res = _dbQuery($sql);

    if (!empty($user_res[0]['usr_id'])) {
        $u_vars = @unserialize($user_res[0]['usr_vars'], ['allowed_classes' => false]);
        if (is_array($u_vars) && !empty($u_vars['password_reset'])) {
            $reset_info = $u_vars['password_reset'];
            if (!empty($reset_info['expires']) && $reset_info['expires'] >= time()) {
                if (hash_equals($reset_info['token_hash'], hash('sha256', $token_param))) {
                    $reset_token_valid = true;
                    $step_reset_set    = true;
                    $reset_user_row    = $user_res[0];
                }
            }
        }
    }

    if (!$reset_token_valid) {
        $step_reset_set  = true;
        $reset_error_msg = $BL['login_reset_invalid_token'] ?? 'This password reset link is invalid or has expired. Please request a new one.';
    }
}

// Process Set New Password
if (isset($_POST['form_aktion']) && $_POST['form_aktion'] === 'set_new_password') {
    $token_param = slweg($_POST['form_reset_token'] ?? '');
    $user_id_param = (int)($_POST['form_reset_uid'] ?? 0);
    $new_pw = slweg($_POST['form_new_password'] ?? '');
    $repeat_pw = slweg($_POST['form_repeat_password'] ?? '');

    $step_reset_set = true;

    if ($csrf_error) {
        $reset_error_msg = $BL['CSRF_POST_INVALID'] ?? 'Security token mismatch. Please try again.';
    } elseif ($new_pw === '') {
        $reset_error_msg = $BL['login_reset_password_empty'] ?? 'Password cannot be empty!';
    } elseif ($new_pw !== $repeat_pw) {
        $reset_error_msg = $BL['login_reset_password_mismatch'] ?? 'Passwords do not match!';
    } else {
        $sql = 'SELECT usr_id, usr_login, usr_name, usr_vars FROM ' . DB_PREPEND . 'phpwcms_user WHERE usr_id = ' . $user_id_param . ' AND usr_aktiv = 1 AND (usr_fe = 1 OR usr_fe = 2) LIMIT 1';
        $user_res = _dbQuery($sql);

        if (!empty($user_res[0]['usr_id'])) {
            $u_vars = @unserialize($user_res[0]['usr_vars'], ['allowed_classes' => false]);
            if (is_array($u_vars) && !empty($u_vars['password_reset'])) {
                $reset_info = $u_vars['password_reset'];
                if (!empty($reset_info['expires']) && $reset_info['expires'] >= time()) {
                    if (hash_equals($reset_info['token_hash'], hash('sha256', $token_param))) {
                        // Reset valid -> update password
                        unset($u_vars['password_reset']);
                        $hashed_password = password_hash($new_pw, PASSWORD_DEFAULT);

                        _dbUpdate('phpwcms_user', [
                            'usr_pass' => $hashed_password,
                            'usr_vars' => serialize($u_vars)
                        ], 'WHERE usr_id = ' . (int)$user_res[0]['usr_id']);

                        $reset_done_success = true;
                        $step_reset_set     = false;
                    }
                }
            }
        }

        if (!$reset_done_success) {
            $reset_error_msg = $BL['login_reset_invalid_token'] ?? 'This password reset link is invalid or has expired. Please request a new one.';
        }
    }
}

$reason_types = array(
    'default' => 'alert-default',
    'info' => 'alert-info',
    'error' => 'alert-error',
    'warning' => 'alert-warning',
    'success' => 'alert-success',
    'danger' => 'alert-danger'
);

?><!DOCTYPE html>
<html lang="<?php echo $_SESSION["wcs_user_lang"]; ?>" data-theme="<?php echo html(get_backend_theme()); ?>">
<head>
	<meta charset="<?php echo PHPWCMS_CHARSET ?>">
	<title><?php echo $BL['be_page_title'] . ' - ' . PHPWCMS_HOST ?></title>
	<meta name="robots" content="noindex, nofollow">
	<script>
	(function() {
		var storedTheme = localStorage.getItem('phpwcms_theme');
		var theme = storedTheme || '<?php echo html(get_backend_theme()); ?>' || 'auto';
		document.documentElement.setAttribute('data-theme', theme);
	})();
	</script>
	<link href="include/inc_css/backend.min.css" rel="stylesheet" type="text/css">
<?php if((isset($_SESSION["wcs_user_lang"]) && ($_SESSION["wcs_user_lang"] == 'ar' || $_SESSION["wcs_user_lang"] == 'he')) || ($phpwcms['default_lang'] == 'ar' || $phpwcms['default_lang'] == 'he')): ?>
    <style>* {direction: rtl;}</style>
<?php endif; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
	<script src="include/inc_js/jquery/jquery-3.7.1.min.js"></script>
	<script src="include/inc_js/bootstrap.bundle.min.js"></script>
	<?php echo getJavaScriptTranslations(); ?>
	<script src="include/inc_js/phpwcms.min.js"></script>
</head>
<body id="login">
    <div id="container">
        <header id="header" class="navbar navbar-expand navbar-static-top">
            <div class="container-fluid px-0 px-sm-3">
                <div id="header-logo" class="navbar-header d-flex align-items-center">
                    <a href="index.php" class="navbar-brand"><img class="border-0" src="img/phpwcms-logo.svg" alt="phpwcms Content Management System" title="phpwcms Content Management System" /></a>
                </div>
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item dropdown theme-switcher">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" id="themeDropdown" aria-expanded="false" title="<?php echo html($BL['be_theme']); ?>">
                            <i class="theme-icon-active fa fa-adjust fa-fw mr-1"></i> <span><?php echo html($BL['be_theme']); ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="themeDropdown">
                            <a class="dropdown-item d-flex align-items-center" href="#" data-set-theme="auto"><i class="fa fa-adjust fa-fw mr-2"></i> <?php echo html($BL['be_theme_auto']); ?> <i class="fa fa-check ml-auto theme-check d-none"></i></a>
                            <a class="dropdown-item d-flex align-items-center" href="#" data-set-theme="light"><i class="fa fa-sun fa-fw mr-2"></i> <?php echo html($BL['be_theme_light']); ?> <i class="fa fa-check ml-auto theme-check d-none"></i></a>
                            <a class="dropdown-item d-flex align-items-center" href="#" data-set-theme="dark"><i class="fa fa-moon fa-fw mr-2"></i> <?php echo html($BL['be_theme_dark']); ?> <i class="fa fa-check ml-auto theme-check d-none"></i></a>
                        </div>
                    </li>
                </ul>
            </div>
        </header>

        <div id="content">
            <div class="container-fluid">
                <div class="row justify-content-sm-center">
                    <div class="col-12 col-sm-9 col-md-6 col-lg-5 col-xl-4">
                        <div class="card mt-5">
                            <div class="card-header">
                                <h2 class="card-title"><strong><?php
                                    if ($step_2fa) {
                                        echo $BL['login_2fa_title'] ?? 'Two-Factor Authentication';
                                    } elseif ($step_reset_set) {
                                        echo $BL['login_reset_set_new_title'] ?? 'Set New Password';
                                    } elseif ($step_reset_request) {
                                        echo $BL['login_reset_title'] ?? 'Reset Password';
                                    } else {
                                        echo $BL["login_text"];
                                    }
                                ?></strong></h2>
                            </div>
<div class="card-body">
<?php if(isset($_GET['reason'])): ?>
        <div class="alert <?php echo $reason_types[ (isset($_GET['type']) && isset($reason_types[$_GET['type']])) ? $_GET['type'] : 'default' ]; ?>">
            <?php
                if($_GET['reason'] === 'csrf-post-failed') {
                    echo $BL['CSRF_POST_FAILED'];
                } elseif($_GET['reason'] === 'csrf-post-invalid') {
                    echo $BL['CSRF_POST_INVALID'];
                } elseif($_GET['reason'] === 'csrf-get-failed') {
                    echo $BL['CSRF_GET_FAILED'];
                } elseif($_GET['reason'] === 'csrf-get-invalid') {
                    echo $BL['CSRF_GET_INVALID'];
                }
            ?>
        </div>
<?php endif; ?>
    <div id="loginFormArea">
    	<div class="alert alert-danger" style="font-size:12px;text-align:center"><?php echo $BL['be_login_jsinfo']; ?></div>
	</div>
</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer id="footer" class="text-center mt-3">
            <strong><a href="https://www.phpwcms.org/" target="_blank" style="text-decoration:none;">phpwcms</a></strong>
            |
            Copyright &copy; 2002-<?php echo date('Y'); ?> Oliver Georgi.
        </footer>
    </div>
<?php

// get whole login form and keep in buffer
ob_start();

if ($step_2fa):
?>
<form action="<?php echo PHPWCMS_URL.get_login_file() ?>" method="post" id="login_2fa_form" autocomplete="off">
<input type="hidden" name="ref_url" value="<?php echo html_specialchars($ref_url); ?>" />
<input type="hidden" name="logintoken" value="<?php echo LOGIN_TOKEN; ?>" />
<input type="hidden" name="customlang" value="<?php if(!empty($_SESSION['wcs_2fa_customlang']) || !empty($_POST['customlang'])): ?>1<?php endif; ?>" />
<input name="form_aktion" type="hidden" id="form_aktion" value="verify_2fa" />

<p class="small text-muted mb-3"><?php echo $BL['login_2fa_desc'] ?? 'Two-Factor Authentication is active for this account. Enter the 6-digit code from your authenticator app or use a backup recovery code to complete login.'; ?></p>

<?php
    echo '<div class="alert alert-danger" role="alert"';
    if(!$err) {
        echo ' style="display:none;"';
    }
    echo ' id="jserr">';
    echo $BL["login_2fa_invalid"] ?? 'Invalid 2FA code or backup code. Please try again.';
    echo '</div>';
?>

<div class="form-group">
    <label class="sr-only" for="form_2fa_code"><?php echo $BL['login_2fa_code'] ?? 'Authentication Code'; ?></label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fa fa-shield-alt fa-fw"></i></span>
        </div>
        <input name="form_2fa_code" type="text" id="form_2fa_code" class="form-control" placeholder="<?php echo $BL['login_2fa_placeholder'] ?? '6-digit code or backup code'; ?>" autofocus="autofocus" required="required" maxlength="20" autocomplete="one-time-code" />
    </div>
</div>

<button name="submit_2fa" type="submit" class="btn btn-blue btn-block mt-4"><?php echo $BL['login_2fa_button'] ?? 'Verify Code'; ?> <i class="fa fa-arrow-right"></i></button>

<div class="text-center mt-3">
    <a href="<?php echo PHPWCMS_URL.get_login_file() ?>" class="small text-muted"><i class="fa fa-arrow-left mr-1"></i> <?php echo $BL['login_2fa_back'] ?? 'Back to Login'; ?></a>
</div>
</form>
<?php
elseif ($step_reset_set):
?>
<form action="<?php echo PHPWCMS_URL.get_login_file() ?>" method="post" id="reset_set_form" autocomplete="off">
<input type="hidden" name="logintoken" value="<?php echo LOGIN_TOKEN; ?>" />
<input type="hidden" name="form_reset_token" value="<?php echo html_specialchars($token_param ?? ''); ?>" />
<input type="hidden" name="form_reset_uid" value="<?php echo (int)($user_id_param ?? 0); ?>" />
<input name="form_aktion" type="hidden" id="form_aktion" value="set_new_password" />

<p class="small text-muted mb-3"><?php echo $BL['login_reset_set_new_desc'] ?? 'Please enter and confirm your new password.'; ?></p>

<?php if (!empty($reset_error_msg)): ?>
    <div class="alert alert-danger" role="alert"><?php echo $reset_error_msg; ?></div>
<?php endif; ?>

<?php if ($reset_token_valid): ?>
    <div class="form-group">
        <label class="sr-only" for="form_new_password"><?php echo $BL['login_reset_new_password'] ?? 'New password'; ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-lock fa-fw"></i></span>
            </div>
            <input name="form_new_password" type="password" id="form_new_password" class="form-control" placeholder="<?php echo $BL['login_reset_new_password'] ?? 'New password'; ?>" autofocus="autofocus" required="required" autocomplete="new-password" />
        </div>
    </div>

    <div class="form-group">
        <label class="sr-only" for="form_repeat_password"><?php echo $BL['login_reset_repeat_password'] ?? 'Repeat password'; ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-lock fa-fw"></i></span>
            </div>
            <input name="form_repeat_password" type="password" id="form_repeat_password" class="form-control" placeholder="<?php echo $BL['login_reset_repeat_password'] ?? 'Repeat password'; ?>" required="required" autocomplete="new-password" />
        </div>
    </div>

    <button name="submit_set_password" type="submit" class="btn btn-blue btn-block mt-4"><?php echo $BL['login_reset_set_new_title'] ?? 'Set New Password'; ?> <i class="fa fa-arrow-right"></i></button>
<?php endif; ?>

<div class="text-center mt-3">
    <a href="<?php echo PHPWCMS_URL.get_login_file() ?>" class="small text-muted"><i class="fa fa-arrow-left mr-1"></i> <?php echo $BL['login_reset_back'] ?? 'Back to Login'; ?></a>
</div>
</form>
<?php
elseif ($step_reset_request):
?>
<form action="<?php echo PHPWCMS_URL.get_login_file() ?>" method="post" id="reset_request_form" autocomplete="off">
<input type="hidden" name="logintoken" value="<?php echo LOGIN_TOKEN; ?>" />
<input name="form_aktion" type="hidden" id="form_aktion" value="send_reset_link" />

<p class="small text-muted mb-3"><?php echo $BL['login_reset_desc'] ?? 'Enter your username or email address. We will send you a secure link to reset your password.'; ?></p>

<?php if ($reset_sent_success): ?>
    <div class="alert alert-success" role="alert"><i class="fa fa-check-circle mr-1"></i> <?php echo $BL['login_reset_sent'] ?? 'If an active account with matching credentials exists, an email with instructions to reset your password has been sent.'; ?></div>
<?php else: ?>
    <div class="form-group">
        <label class="sr-only" for="form_reset_account"><?php echo $BL['login_username'] . ' / ' . ($BL['be_newsletter_email'] ?? 'Email'); ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-envelope fa-fw"></i></span>
            </div>
            <input name="form_reset_account" type="text" id="form_reset_account" class="form-control" placeholder="<?php echo $BL['login_username'] . ' / ' . ($BL['be_newsletter_email'] ?? 'Email'); ?>" autofocus="autofocus" required="required" />
        </div>
    </div>

    <button name="submit_reset" type="submit" class="btn btn-blue btn-block mt-4"><?php echo $BL['login_reset_button'] ?? 'Send Reset Link'; ?> <i class="fa fa-arrow-right"></i></button>
<?php endif; ?>

<div class="text-center mt-3">
    <a href="<?php echo PHPWCMS_URL.get_login_file() ?>" class="small text-muted"><i class="fa fa-arrow-left mr-1"></i> <?php echo $BL['login_reset_back'] ?? 'Back to Login'; ?></a>
</div>
</form>
<?php
else:
?>
<form action="<?php echo PHPWCMS_URL.get_login_file() ?>" method="post" id="login_formular" onsubmit="return login(this);"<?php if(empty($phpwcms['login_autocomplete'])): ?> autocomplete="off"<?php endif; ?>>
<input type="hidden" name="json" id="json" value="0" />
<input type="hidden" name="customlang" id="customlang" value="<?php if(!empty($_POST['customlang'])): ?>1<?php endif; ?>" />
<input type="hidden" name="md5pass" id="md5pass" value="" autocomplete="off" />
<input type="hidden" name="ref_url" value="<?php echo html_specialchars($ref_url); ?>" />
<input type="hidden" name="logintoken" value="<?php echo LOGIN_TOKEN; ?>" />
<input name="form_aktion" type="hidden" id="form_aktion" value="login" />
<?php

    if ($reset_done_success) {
        echo '<div class="alert alert-success" role="alert"><i class="fa fa-check-circle mr-1"></i> ' . ($BL['login_reset_success'] ?? 'Your password has been reset successfully. You can now log in.') . '</div>';
    }

    if(file_exists(PHPWCMS_ROOT.'/setup')) {
        echo '<div class="alert alert-danger">'.$BL["setup_dir_exists"].'</div>';
    }

    if(isset($_POST['json']) && $_POST['json'] == 2) {
        $err = 0;
    }

    if(file_exists(PHPWCMS_ROOT.'/phpwcms_code_snippets')) {
        echo '<div class="alert alert-danger" role="alert">'.$BL["phpwcms_code_snippets_dir_exists"].'</div>';
    }

    if(!empty($GLOBALS['phpwcms']['revision_error'])) {
        echo '<div class="alert alert-danger" role="alert">'.html_specialchars($GLOBALS['phpwcms']['revision_error']).'</div>';
    }

    if(($phpwcms['image_library'] === 'gd' || $phpwcms['image_library'] === 'gd2') && (!extension_loaded('gd') || !function_exists('gd_info'))) {
        echo '<div class="alert alert-danger" role="alert">'.$BL['gd_not_loaded'].'</div>';
    }

    echo '<div class="alert alert-danger" role="alert"';
    if(!$err) {
        echo ' style="display:none;"';
    }
    echo ' id="jserr">';
    echo $BL["login_error"];
    echo '</div>';

?>
<div class="form-group">
	<label class="sr-only" for="form_loginname"><?php echo $BL["login_username"] ?></label>
	<div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fa fa-user fa-fw"></i></span>
        </div>
		<input name="form_loginname" type="text" id="form_loginname" class="form-control" placeholder="<?php echo $BL["login_username"] ?>" value="<?php echo html_specialchars($wcs_user); ?>" required="required" />
	</div>
</div>

<div class="form-group">
	<label class="sr-only" for="form_password"><?php echo $BL["login_userpass"] ?></label>
    <div class="input-group">
	    <div class="input-group-prepend">
            <span class="input-group-text"><i class="fa fa-lock fa-fw"></i></span>
        </div>
        <input name="form_password" type="password" id="form_password" placeholder="<?php echo $BL["login_userpass"] ?>" class="form-control" required="required"<?php if(empty($phpwcms['login_autocomplete'])): ?> autocomplete="new-password"<?php endif; ?> />
	</div>
</div>

<div class="d-flex justify-content-end mt-2 mb-3">
    <a href="<?php echo PHPWCMS_URL.get_login_file() ?>?reset=1" class="small text-muted"><i class="fa fa-question-circle mr-1"></i> <?php echo $BL['login_forgot_password'] ?? 'Forgot password?'; ?></a>
</div>

<hr class="mt-2 mb-3" />
<div class="form-row">
    <div class="form-group col-6 mb-0">
        <label for="form_lang"><?php echo $BL['login_lang'] ?></label>
        <div class="input-group">
            <select class="custom-select form-control-sm m-0" name="form_lang" id="form_lang" onchange="document.getElementById('json').value='2';login(this.form);">
            <?php
            // check available languages installed and build language selector menu
            $lang_dirs = opendir(PHPWCMS_ROOT.'/include/inc_lang/backend');
            $lang_options = array();
            while($lang_code = readdir($lang_dirs)) {
                if( substr($lang_code, 0, 1) !== '.' && is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$lang_code.'/lang.inc.php')) {
                    $_lang_code = strtoupper($lang_code);
                    $lang_options[$_lang_code]  = '<option value="'.$lang_code.'"';
                    $lang_options[$_lang_code] .= ($lang_code == $_SESSION['wcs_user_lang']) ? ' selected="selected"' : '';
                    $lang_options[$_lang_code] .= '>';
                    $lang_options[$_lang_code] .= (isset($BL[$_lang_code])) ? $BL[$_lang_code] : $_lang_code;
                    $lang_options[$_lang_code] .= '</option>';
                }
            }
            closedir($lang_dirs);
            ksort($lang_options);
            echo implode('', $lang_options);

            ?>
            </select>
        </div>
    </div>
    <div class="form-group col-6 mb-0">
        <label for="form_theme"><?php echo $BL['be_theme'] ?></label>
        <div class="input-group">
            <select class="custom-select form-control-sm m-0" name="form_theme" id="form_theme">
                <option value="auto"<?php if(get_backend_theme() === 'auto'): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_theme_auto']; ?></option>
                <option value="light"<?php if(get_backend_theme() === 'light'): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_theme_light']; ?></option>
                <option value="dark"<?php if(get_backend_theme() === 'dark'): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_theme_dark']; ?></option>
            </select>
        </div>
    </div>
</div>
<button name="submit_form" type="submit" class="btn btn-blue btn-block mt-4"><?php echo $BL['login_button'] ?> <i class="fa fa-arrow-right"></i></button></form>
<?php
endif;

$formAll = str_replace( array("'", "\r", "\n", '<'), array("\'", '', " ", "<'+'"), ob_get_clean() );

?>
<script>
    document.getElementById('loginFormArea').innerHTML = '<?php echo $formAll ?>';
    if (document.getElementById('form_2fa_code')) {
        document.getElementById('form_2fa_code').focus();
    } else if (document.getElementById('form_reset_account')) {
        document.getElementById('form_reset_account').focus();
    } else if (document.getElementById('form_new_password')) {
        document.getElementById('form_new_password').focus();
    } else if (document.getElementById('form_loginname')) {
        document.getElementById('form_loginname').focus();
    }
    if (typeof initPhpwcmsTheme === 'function') {
        initPhpwcmsTheme();
    }
</script>
<?php if(!empty($phpwcms['browser_check']['be'])):
    $buoop = array('insecure' => isset($phpwcms['browser_check']['insecure']) ? boolval($phpwcms['browser_check']['insecure']) : true);
    if(!empty($phpwcms['browser_check']['vs'])) {
        $buoop['vs'] = $phpwcms['browser_check']['vs'];
    }
    if(!empty($phpwcms['browser_check']['required'])) {
        $buoop['required'] = '{' . trim($phpwcms['browser_check']['required'], '{}') . '}';
    }
?>
    var $buoop = <?php echo json_encode($buoop); ?>;
</script><script src="https://browser-update.org/update.min.js"><?php endif; ?></script>
</body>
</html>
