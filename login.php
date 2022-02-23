<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

$phpwcms    = array('SESSION_START' => true);
$BL         = array();

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
        <h4 style="">
            <strong>Your configuration is placed at the wrong position.</strong>
        </h4>
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

logdir_exists();

$_SESSION['REFERER_URL'] = PHPWCMS_URL.get_login_file();

// make compatibility check
if(phpwcms_revision_check_temp($phpwcms["revision"]) !== true) {
    if (!PHPWCMS_DB_VERSION_57PLUS) {
        _dbQuery('SET storage_engine=MYISAM', 'SET');
    }
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

//define language and check if language file is available
if(isset($_COOKIE['phpwcmsBELang'])) {
    $temp_lang = strtoupper(substr(trim($_COOKIE['phpwcmsBELang']), 0, 2));
    if (isset($BL[$temp_lang])) {
        $_SESSION["wcs_user_lang"] = strtolower($temp_lang);
    } else {
        setcookie('phpwcmsBELang', '', time() - 3600, '/', getCookieDomain(), PHPWCMS_SSL, true);
    }
}
if(isset($_POST['form_lang'])) {
    $temp_lang = strtolower(substr(clean_slweg($_POST['form_lang']), 0, 2));
    $_SESSION["wcs_user_lang"] = $temp_lang;
    set_language_cookie($temp_lang);
}
if(empty($_SESSION["wcs_user_lang"])) {
    $_SESSION["wcs_user_lang"] = strtolower( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 ) : $phpwcms["default_lang"] );
} else {
    $_SESSION["wcs_user_lang"] = strtolower( substr($_SESSION["wcs_user_lang"], 0, 2 ) );
}
if(isset($BL[strtoupper($_SESSION["wcs_user_lang"])]) && is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$_SESSION["wcs_user_lang"].'/lang.inc.php')) {
    $_SESSION["wcs_user_lang_custom"] = 1;
} else {
    $_SESSION["wcs_user_lang"] = 'en'; //by ono
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
$phpwcms["wysiwyg_editor"] = empty($phpwcms["wysiwyg_editor"]) ? 0 : 1;
$_SESSION["WYSIWYG_EDITOR"] = $phpwcms["wysiwyg_editor"];

destroyBackendSessionData();

$json_check = isset($_POST['json']) ? intval($_POST['json']) : 0;

if(isset($_POST['form_aktion']) && $_POST['form_aktion'] == 'login' && $json_check === 1) {

    $login_passed       = 0;
    $wysiwyg_template   = '';
    $wcs_user           = slweg($_POST['form_loginname']);
    $wcs_pass           = slweg($_POST['md5pass']);

    $sql_query  = "SELECT * FROM " . DB_PREPEND . "phpwcms_user WHERE usr_login=" . _dbEscape($wcs_user) . " AND ";
    $sql_query .= "usr_pass=" . _dbEscape($wcs_pass) . " AND usr_aktiv=1 AND (usr_fe=1 OR usr_fe=2)";

    if(!$csrf_error) {

        $result = _dbQuery($sql_query);

        if(isset($result[0]['usr_id'])) {

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
                $_SESSION["wcs_user_lang"]  = $result[0]["usr_lang"];
                set_language_cookie($result[0]["usr_lang"]);
            } elseif (!empty($_SESSION["wcs_user_lang"])) {
                set_language_cookie($_SESSION["wcs_user_lang"]);
            } else {
                set_language_cookie();
            }

            $_SESSION["structure"] = @unserialize($result[0]["usr_var_structure"]);
            $_SESSION["klapp"]     = @unserialize($result[0]["usr_var_privatefile"]);
            $_SESSION["pklapp"]    = @unserialize($result[0]["usr_var_publicfile"]);
            $result[0]["usr_vars"] = @unserialize($result[0]["usr_vars"]);

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

            // Fallback to CKeditor?
            $_SESSION["WYSIWYG_EDITOR"] = empty($result[0]["usr_wysiwyg"]) ? false : true;
            $_SESSION["wcs_user_cp"]    = isset($result[0]["usr_vars"]['selected_cp']) && is_array($result[0]["usr_vars"]['selected_cp']) ? $result[0]["usr_vars"]['selected_cp'] : array();
            $_SESSION["wcs_allowed_cp"] = isset($result[0]["usr_vars"]['allowed_cp']) && is_array($result[0]["usr_vars"]['allowed_cp']) ? $result[0]["usr_vars"]['allowed_cp'] : array();

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

            $login_passed = 1;
        }
    }

    if($login_passed) {

        // Store login information in DB
        if(!($check = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_userlog WHERE logged_user="._dbEscape($wcs_user)." AND logged_in=1", 'COUNT'))) {
            // User not yet logged in, create new
            $sql  = "INSERT INTO ".DB_PREPEND."phpwcms_userlog (logged_user, logged_username, logged_start, logged_change, logged_in, logged_ip) VALUES (";
            $sql .= _dbEscape($wcs_user).", "._dbEscape($_SESSION["wcs_user_name"]).", ".time().", ".time().", 1, "._dbEscape(PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP()).")";
            _dbQuery($sql, 'INSERT');
        }

        $_SESSION['PHPWCMS_ROOT'] = PHPWCMS_ROOT;
        set_status_message('Welcome '.$wcs_user.'!');

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

    } else {

        $err = 1;

    }

} elseif(isset($_POST['form_loginname']) && $json_check !== 2) {

    $err = 1;

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
<html lang="<?php echo $_SESSION["wcs_user_lang"]; ?>">
<head>
    <meta charset="<?php echo PHPWCMS_CHARSET ?>">
    <title><?php echo $BL['be_page_title'] . ' - ' . PHPWCMS_HOST ?></title>
    <meta name="robots" content="noindex, nofollow">
    <link href="include/inc_css/login.min.css" rel="stylesheet" type="text/css">
<?php if((isset($_SESSION["wcs_user_lang"]) && ($_SESSION["wcs_user_lang"] == 'ar' || $_SESSION["wcs_user_lang"] == 'he')) || ($phpwcms['default_lang'] == 'ar' || $phpwcms['default_lang'] == 'he')): ?>
    <style>* {direction: rtl;}</style>
<?php endif; ?>
    <style>
        .alert-img {
            display: inline-block;
            width: 30px;
            height: 30px;
            float: left;
            position: relative;
        }
        .alert-offset {
            margin-left: 40px;
            margin-top: 8px;
            margin-bottom: 5px;
        }
    </style>
    <script src="include/inc_js/jquery/jquery.min.js"></script>
    <script src="include/inc_js/phpwcms.min.js"></script>
    <script src="include/inc_js/md5.js"></script>
</head>
<body>
<div style="margin:0 auto;width:500px;padding-top:50px;">

    <h2>
        <a href="index.php" target="_top" class="d-inline-block" style="padding:0 18px 12px 15px;">
            <img src="img/backend/phpwcms-logo.png" srcset="img/backend/phpwcms-logo.svg" alt="phpwcms" width="202" height="56" />
        </a>
    </h2>

    <div style="border-radius:15px;background:#fff;padding:15px;box-shadow:2px 2px 10px rgba(0,0,0,.25);">

        <h1><?php echo $BL["login_text"]; ?></h1>

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
            <div class="alert alert-danger" style="font-weight:bold;padding:0 0 15px 0;font-size:12px;text-align:center"><?php echo $BL['be_login_jsinfo']; ?></div>
        </div>
        <p style="padding: 0 3px 5px 3px;">
            <strong><a href="http://www.phpwcms.org" target="_blank" style="text-decoration:none;">phpwcms</a></strong>
            Copyright &copy; 2002&#8212;<?php echo date('Y'); ?>
            Oliver Georgi. Extensions are copyright of their respective owners.
            Visit <a href="https://www.phpwcms.org" target="_blank">phpwcms.org</a> for
            details. phpwcms is free software released under <a href="http://www.fsf.org/licensing/licenses/gpl.html" target="_blank">GPL</a>
            and comes WITHOUT ANY WARRANTY. Obstructing the appearance of this notice is prohibited  by law.
        </p>
    </div>
</div>
<?php

// get whole login form and keep in buffer
ob_start();

?>
<form action="<?php echo PHPWCMS_URL.get_login_file() ?>" method="post" id="login_formular" onsubmit="return login(this);"<?php if(empty($phpwcms['login_autocomplete'])): ?> autocomplete="off"<?php endif; ?>>
<input type="hidden" name="json" id="json" value="0" />
<input type="hidden" name="customlang" id="customlang" value="<?php if(!empty($_POST['customlang'])): ?>1<?php endif; ?>" />
<input type="hidden" name="md5pass" id="md5pass" value="" autocomplete="off" />
<input type="hidden" name="ref_url" value="<?php echo html_specialchars($ref_url); ?>" />
<input type="hidden" name="logintoken" value="<?php echo LOGIN_TOKEN; ?>" />
<input name="form_aktion" type="hidden" id="form_aktion" value="login" />
<?php

    if(file_exists(PHPWCMS_ROOT.'/setup')) {
        echo '<div class="alert alert-danger">';
        echo '<svg focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="alert-img"><path fill="currentColor" d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z" class=""></path></svg>';
        echo '<div class="alert-offset">' . $BL["setup_dir_exists"];
        echo '</div></div>';
    }

    if(isset($_POST['json']) && $_POST['json'] == 2) {
        $err = 0;
    }

    if(file_exists(PHPWCMS_ROOT.'/phpwcms_code_snippets')) {
        echo '<div class="alert alert-danger">'.$BL["phpwcms_code_snippets_dir_exists"].'</div>';
    }

    if(($phpwcms['image_library'] === 'gd' || $phpwcms['image_library'] === 'gd2') && (!extension_loaded('gd') || !function_exists('gd_info'))) {
        echo '<div class="alert alert-danger" style="font-weight:normal;">'.$BL['gd_not_loaded'].'</div>';
    }

    echo '<div class="alert alert-danger"';
    if(!$err) {
        echo ' style="display:none;"';
    }
    echo ' id="jserr">';
    echo $BL["login_error"];
    echo '</div>';

?>
    <table border="0" cellpadding="0" cellspacing="0" summary="Login Form" style="margin:15px 0 20px 10px">
        <tr>
            <td align="right" nowrap="nowrap" class="v10 nowrap"><?php echo $BL["login_username"] ?>:&nbsp;</td>
            <td class="v10"><input name="form_loginname" type="text" id="form_loginname" class="width250" size="30" maxlength="30" value="<?php echo html_specialchars($wcs_user); ?>" required="required" /></td>
        </tr>
        <tr>
            <td align="right" nowrap="nowrap" class="v10 nowrap"><?php echo $BL["login_userpass"] ?>:&nbsp;</td>
            <td class="v10"><input name="form_password" type="password" id="form_password" class="width250" size="30" maxlength="40" required="required"<?php if(empty($phpwcms['login_autocomplete'])): ?> autocomplete="new-password"<?php endif; ?> /></td>
        </tr>
        <tr>
            <td align="right" nowrap="nowrap" class="v10 nowrap"><?php echo $BL["login_lang"] ?>:&nbsp;</td>
            <td class="v10">
                <select name="form_lang" id="form_lang" onchange="getObjectById('json').value='2';login(this.form);">
<?php

// check available languages installed and build language selector menu
$lang_dirs = opendir(PHPWCMS_ROOT.'/include/inc_lang/backend');
$lang_options = array();
while($lang_code = readdir($lang_dirs)) {
    if( substr($lang_code, 0, 1) !== '.' && is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$lang_code."/lang.inc.php")) {
        $_lang_code = strtoupper($lang_code);
        $lang_options[$_lang_code]  = '<option value="'.$lang_code.'"';
        $lang_options[$_lang_code] .= ($lang_code == $_SESSION["wcs_user_lang"]) ? ' selected="selected"' : '';
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
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input name="submit_form" type="submit" value="<?php echo $BL["login_button"] ?>" class="button" /></td>
        </tr>
    </table>
    </form>
<?php

$formAll = str_replace( array("'", "\r", "\n", '<'), array("\'", '', " ", "<'+'"), ob_get_clean() );

?>
<script>
    getObjectById('loginFormArea').innerHTML = '<?php echo $formAll ?>';
    getObjectById('form_loginname').focus();
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