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
    //Adding specific language files
    include PHPWCMS_ROOT.'/include/inc_lang/backend/'. $_SESSION["wcs_user_lang"] .'/lang.pp.inc.php';
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

if(isset($_POST['form_aktion']) && $_POST['form_aktion'] == 'login' && $json_check === 1) {

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
            $_SESSION["WYSIWYG_EDITOR"] = empty($result[0]["usr_wysiwyg"]) ? $phpwcms["wysiwyg_editor"] : intval($result[0]["usr_wysiwyg"]);
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

            }
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
	<link href="include/inc_css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="include/inc_css/login.min.css" rel="stylesheet" type="text/css">
	<link href="include/inc_css/phpwcms-fontawesome.min.css" rel="stylesheet" type="text/css">
	<link href="include/inc_css/phpwcmsspecial.min.css" rel="stylesheet" type="text/css">
<?php if((isset($_SESSION["wcs_user_lang"]) && ($_SESSION["wcs_user_lang"] == 'ar' || $_SESSION["wcs_user_lang"] == 'he')) || ($phpwcms['default_lang'] == 'ar' || $phpwcms['default_lang'] == 'he')): ?>
    <style>* {direction: rtl;}</style>
<?php endif; ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
	<script src="include/inc_js/jquery/jquery.min.js"></script>
	<script src="include/inc_js/bootstrap.bundle.min.js"></script>
	<?php echo getJavaScriptTranslations(); ?>
	<script src="include/inc_js/phpwcms.min.js"></script>
	<script src="include/inc_js/md5.js"></script>
</head>
<body id="login">
    <div id="container">
        <header id="header" class="navbar navbar-static-top">
            <div class="container-fluid">
                <div id="header-logo" class="navbar-header">
                    <a href="index.php" class="navbar-brand"><img class="border-0" src="img/logo.svg" alt="phpwcms Content Management System" title="phpwcms Content Management System" /></a>
                </div>
                <a href="#" id="button-menu" class="d-md-none d-lg-none d-xl-none"><span class="fa fa-bars"></span></a> </div>
        </header>

        <div id="content">
            <div class="container-fluid">
                <div class="row justify-content-md-center">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card mt-5">
                            <div class="card-header">
                                <h2 class="card-title"><strong><?php echo $BL["login_text"]; ?></strong></h2>
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
        echo '<div class="alert alert-danger">'.$BL["setup_dir_exists"].'</div>';
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
<hr class="mt-4 mb-3" />
<div class="form-group">
	<label for="form_lang"><?php echo $BL["login_lang"] ?></label>
    <div class="input-group">
        <select class="custom-select form-control-sm m-0" name="form_lang" id="form_lang" onchange="document.getElementById('json').value='2';login(this.form);">
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
    </div>
</div>
<button name="submit_form" type="submit" class="btn btn-blue btn-block mt-4"><?php echo $BL["login_button"] ?> <i class="fa fa-arrow-right"></i></button></form>
<?php

$formAll = str_replace( array("'", "\r", "\n", '<'), array("\'", '', " ", "<'+'"), ob_get_clean() );

?>
<script>
    document.getElementById('loginFormArea').innerHTML = '<?php echo $formAll ?>';
    document.getElementById('form_loginname').focus();
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
