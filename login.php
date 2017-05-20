<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2017, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

session_start();

$phpwcms    = array();
$BL         = array();
$basepath   = str_replace('\\', '/', dirname(__FILE__));

// Check if config is still at the old position
if(!is_file($basepath.'/include/config/conf.inc.php') && is_file($basepath.'/config/phpwcms/conf.inc.php')) {

    if(!@rename($basepath.'/config/phpwcms', $basepath.'/include/config')):
?>
    <html><body>
        <h4 style="color:#cc3300">
            <strong>Your configuration is placed at the wrong position.</strong>
        </h4>
        <p>
            Beginning with <strong>phpwcms v1.7.8</strong> base config files were moved from
            directory <code>config/phpwcms</code> to directory <code>include/config</code>. The fallback
            to do it automatically has failed. Please do it manually before you continue.
        </p>
    </body></html>
<?php
        die();

    endif;
}

require_once $basepath.'/include/config/conf.inc.php';
require_once $basepath.'/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lang/code.lang.inc.php';

$_SESSION['REFERER_URL'] = PHPWCMS_URL.get_login_file();

// make compatibility check
if(phpwcms_revision_check_temp($phpwcms["revision"]) !== true) {
    _dbQuery('SET storage_engine=MYISAM', 'SET');
    $revision_status = phpwcms_revision_check($phpwcms["revision"]);
}

// define vars
$err        = 0;
$wcs_user   = '';

// where user should be redirected too after login
if(!empty($_POST['ref_url'])) {
    $ref_url = xss_clean($_POST['ref_url']);
} elseif(!empty($_GET['ref'])) {
    $ref_url = xss_clean(rawurldecode($_GET['ref']));
} else {
    $ref_url = '';
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && count($_POST) && $_POST['logintoken'] !== get_token_get_value('csrftoken')) {
    $csrf_error = true;
} else {
    $csrf_error = false;
}

define('LOGIN_TOKEN', generate_get_token('csrftoken'));

// reset all inactive users
$sql  = "UPDATE ".DB_PREPEND."phpwcms_userlog SET ";
$sql .= "logged_in = 0, logged_change = '".time()."' ";
$sql .= "WHERE logged_in = 1 AND ( ".time()." - logged_change ) > ".intval($phpwcms["max_time"]);
mysql_query($sql, $db);

//load default language EN
require_once PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php';

//define language and check if language file is available
if(isset($_COOKIE['phpwcmsBELang'])) {
    $temp_lang = strtoupper( substr( trim( $_COOKIE['phpwcmsBELang'] ), 0, 2 ) );
    if( isset( $BL[ $temp_lang ] ) ) {
        $_SESSION["wcs_user_lang"] = strtolower($temp_lang);
    } else {
        setcookie('phpwcmsBELang', '', time()-3600 );
    }
}
if(isset($_POST['form_lang'])) {
    $_SESSION["wcs_user_lang"] = strtolower(substr(clean_slweg($_POST['form_lang']), 0, 2));
    set_language_cookie();
}
if(empty($_SESSION["wcs_user_lang"])) {
    $_SESSION["wcs_user_lang"] = strtolower( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 ) : $phpwcms["default_lang"] );
} else {
    $_SESSION["wcs_user_lang"] = strtolower( substr($_SESSION["wcs_user_lang"], 0, 2 ) );
}
if(isset($BL[strtoupper($_SESSION["wcs_user_lang"])]) && is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$_SESSION["wcs_user_lang"].'/lang.inc.php')) {
    $_SESSION["wcs_user_lang_custom"] = 1;
} else {
    $_SESSION["wcs_user_lang"]          = 'en'; //by ono
    $_SESSION["wcs_user_lang_custom"]   = 0;
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
$phpwcms["wysiwyg_editor"]  = empty($phpwcms["wysiwyg_editor"]) ? 0 : 1;
$_SESSION["WYSIWYG_EDITOR"] = $phpwcms["wysiwyg_editor"];

destroyBackendSessionData();

if(isset($_POST['form_aktion']) && $_POST['form_aktion'] == 'login' && isset($_POST['json']) && $_POST['json'] == '1') {

    $login_passed       = 0;
    $wysiwyg_template   = '';
    $wcs_user           = slweg($_POST['form_loginname']);
    $wcs_pass           = slweg($_POST['md5pass']);

    $sql_query =    "SELECT * FROM ".DB_PREPEND."phpwcms_user WHERE usr_login='".
                    aporeplace($wcs_user)."' AND usr_pass='".
                    aporeplace($wcs_pass)."' AND usr_aktiv=1 AND (usr_fe=1 OR usr_fe=2)";

    if(!$csrf_error && $result = mysql_query($sql_query)) {
        if($row = mysql_fetch_assoc($result)) {
            $_SESSION["wcs_user"]           = $wcs_user;
            $_SESSION["wcs_user_name"]      = ($row["usr_name"]) ? $row["usr_name"] : $wcs_user;
            $_SESSION["wcs_user_id"]        = $row["usr_id"];
            $_SESSION["wcs_user_aktiv"]     = $row["usr_aktiv"];
            $_SESSION["wcs_user_rechte"]    = $row["usr_rechte"];
            $_SESSION["wcs_user_email"]     = $row["usr_email"];
            $_SESSION["wcs_user_avatar"]    = $row["usr_avatar"];
            $_SESSION["wcs_user_logtime"]   = time();
            $_SESSION["wcs_user_admin"]     = intval($row["usr_admin"]);
            $_SESSION["wcs_user_thumb"]     = 1;
            if($row["usr_lang"]) {
                $_SESSION["wcs_user_lang"]  = $row["usr_lang"];
            }

            set_language_cookie();

            $_SESSION["structure"]          = @unserialize($row["usr_var_structure"]);
            $_SESSION["klapp"]              = @unserialize($row["usr_var_privatefile"]);
            $_SESSION["pklapp"]             = @unserialize($row["usr_var_publicfile"]);
            $row["usr_vars"]                = @unserialize($row["usr_vars"]);

            // Fallback to CKeditor?
            $_SESSION["WYSIWYG_EDITOR"]     = empty($row["usr_wysiwyg"]) ? false : true;
            $_SESSION["wcs_user_cp"]        = isset($row["usr_vars"]['selected_cp']) && is_array($row["usr_vars"]['selected_cp']) ? $row["usr_vars"]['selected_cp'] : array();
            $_SESSION["wcs_allowed_cp"]     = isset($row["usr_vars"]['allowed_cp']) && is_array($row["usr_vars"]['allowed_cp']) ? $row["usr_vars"]['allowed_cp'] : array();

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
        mysql_free_result($result);
    }

    if($login_passed) {
        // Store login information in DB
        $check = mysql_query(   "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_userlog WHERE logged_user='".
                                aporeplace($wcs_user)."' AND logged_in=1", $db );
        if($row = mysql_fetch_row($check)) {
            if(!$row[0]) {
                // User not yet logged in, create new
                mysql_query("INSERT INTO ".DB_PREPEND."phpwcms_userlog ".
                            "(logged_user, logged_username, logged_start, logged_change, ".
                            "logged_in, logged_ip) VALUES ('".
                            aporeplace($wcs_user)."', '".aporeplace($_SESSION["wcs_user_name"])."', ".time().", ".
                            time().", 1, '".aporeplace(getRemoteIP())."')", $db );
            }
        }
        mysql_free_result($check);
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

        headerRedirect($backend_redirect . get_token_get_string('csrftoken') . '&' . session_name().'='.session_id());

    } else {

        $err = 1;

    }

} elseif(isset($_POST['json']) && intval($_POST['json']) != 1) {

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
<html>
<head>
    <title><?php echo $BL['be_page_title'] . ' - ' . PHPWCMS_HOST ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>" />
    <meta name="robots" content="noindex, nofollow" />
    <link href="include/inc_css/login.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="include/inc_js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="include/inc_js/phpwcms.min.js"></script>
    <script type="text/javascript" src="include/inc_js/md5.js"></script>
<?php if((isset($_SESSION["wcs_user_lang"]) && ($_SESSION["wcs_user_lang"] == 'ar' || $_SESSION["wcs_user_lang"] == 'he')) || ($phpwcms['default_lang'] == 'ar' || $phpwcms['default_lang'] == 'he')): ?>
    <style type="text/css">* {direction: rtl;}</style>
<?php endif; ?>
</head>
<body>
<div style="margin:0 auto;width:500px;padding-top:50px;">

    <h2><a href="index.php" target="_top"><img src="img/backend/phpwcms-signet-be.png" alt="phpwcms" style="margin:0 18px 12px 18px;border:0;" /></a></h2>

    <div style="border-radius:15px;background:#fff;padding:15px;box-shadow:2px 2px 10px rgba(0,0,0,.25);">

        <h1><?php echo $BL["login_text"]; ?></h1>

<?php if(isset($_GET['reason'])): ?>
        <div class="alert <?php echo $reason_types[ (isset($_GET['type']) && isset($reason_types[$_GET['type']])) ? $_GET['type'] : 'default' ]; ?>">
            <?php if($_GET['reason'] === 'csrf-post-failed'): ?>
                <?php echo $BL['CSRF_POST_FAILED']; ?>
            <?php elseif($_GET['reason'] === 'csrf-post-invalid'): ?>
                <?php echo $BL['CSRF_POST_INVALID']; ?>
            <?php elseif($_GET['reason'] === 'csrf-get-failed'): ?>
                <?php echo $BL['CSRF_GET_FAILED']; ?>
            <?php elseif($_GET['reason'] === 'csrf-get-invalid'): ?>
                <?php echo $BL['CSRF_GET_INVALID']; ?>
            <?php endif; ?>
        </div>
<?php endif; ?>

        <div id="loginFormArea">
            <div class="alert alert-danger" style="font-weight:bold;padding:0 0 15px 0;font-size:12px;text-align:center"><?php echo $BL['be_login_jsinfo']; ?></div>
        </div>
        <p style="padding: 0 3px 5px 3px;">
            <strong><a href="http://www.phpwcms.org" target="_blank" style="text-decoration:none;">phpwcms</a></strong>
            Copyright &copy; 2002&#8212;<?php echo date('Y'); ?>
            Oliver Georgi. Extensions are copyright of their respective owners.
            Visit <a href="http://www.phpwcms.org" target="_blank">http://www.phpwcms.org</a> for
            details. phpwcms is free software released under <a href="http://www.fsf.org/licensing/licenses/gpl.html" target="_blank">GPL</a>
            and comes WITHOUT ANY WARRANTY. Obstructing the appearance of this notice is prohibited  by law.
        </p>
    </div>
</div>
<?php

// get whole login form and keep in buffer
ob_start();

?>
<form action="<?php echo PHPWCMS_URL.get_login_file() ?>" method="post" name="login_formular" id="login_formular" onsubmit="return login(this);" autocomplete="off">
<input type="hidden" name="json" id="json" value="0" />
<input type="hidden" name="md5pass" id="md5pass" value="" autocomplete="off" />
<input type="hidden" name="ref_url" value="<?php echo html_specialchars($ref_url); ?>" />
<input type="hidden" name="logintoken" value="<?php echo LOGIN_TOKEN; ?>" />
<input name="form_aktion" type="hidden" id="form_aktion" value="login" />
<?php

    if(file_exists(PHPWCMS_ROOT.'/setup')) {
        echo '<div class="alert alert-warning">'.$BL["setup_dir_exists"].'</div>';
    }
    if(file_exists(PHPWCMS_ROOT.'/phpwcms_code_snippets')) {
        echo '<div class="alert alert-danger">'.$BL["phpwcms_code_snippets_dir_exists"].'</div>';
    }

    if(isset($_POST['json']) && $_POST['json'] == 2) {
        $err = 0;
    }

    echo '<div class="alert alert-danger"'.($err ? '' : ' style="display:none;"') . ' id="jserr">'.$BL["login_error"].'</div>';

?>
    <table border="0" cellpadding="0" cellspacing="0" summary="Login Form" style="margin:15px 0 20px 10px">
        <tr>
          <td align="right" nowrap="nowrap" class="v10"><?php echo $BL["login_username"] ?>:&nbsp;</td>
          <td class="v10"><input name="form_loginname" type="text" id="form_loginname" class="width250" size="30" maxlength="30" value="<?php echo html_specialchars($wcs_user); ?>" required="required" /></td>
          </tr>
        <tr>
          <td align="right" nowrap="nowrap" class="v10"><?php echo $BL["login_userpass"] ?>:&nbsp;</td>
          <td class="v10"><input name="form_password" type="password" id="form_password" class="width250" size="30" maxlength="40" required="required" /></td>
          </tr>
        <tr>
          <td align="right" nowrap="nowrap" class="v10"><?php echo $BL["login_lang"] ?>:&nbsp;</td>
          <td class="v10"><select name="form_lang" id="form_lang" onchange="getObjectById('json').value='2';login(this.form);">
<?php

// check available languages installed and build language selector menu
$lang_dirs = opendir(PHPWCMS_ROOT.'/include/inc_lang/backend');
$lang_code = array();
while($lang_codes = readdir( $lang_dirs )) {
    if( substr($lang_codes, 0, 1) !== '.' && is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$lang_codes."/lang.inc.php")) {
        $lang_code[$lang_codes]  = '<option value="'.$lang_codes.'"';
        $lang_code[$lang_codes] .= ($lang_codes == $_SESSION["wcs_user_lang"]) ? ' selected="selected"' : '';
        $lang_code[$lang_codes] .= '>';
        $lang_code[$lang_codes] .= (isset($BL[strtoupper($lang_codes)])) ? $BL[strtoupper($lang_codes)] : strtoupper($lang_codes);
        $lang_code[$lang_codes] .= '</option>';
    }
}
closedir( $lang_dirs );
ksort($lang_code);

echo implode(LF, $lang_code);

?>
          </select></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input name="submit_form" type="submit" value="<?php echo $BL["login_button"] ?>" class="button" /></td>
          </tr>
    </table>
    </form>
<?php

$formAll = str_replace( array("'", "\r", "\n", '<'), array("\'", '', " ", "<'+'"), ob_get_clean() );

?><script type="text/javascript">
    getObjectById('loginFormArea').innerHTML = '<?php echo $formAll ?>';
    getObjectById('form_loginname').focus();
</script>
<?php if(!empty($phpwcms['browser_check']['be'])): ?>
<script type="text/javascript">
    $buoop = {<?php if(!empty($phpwcms['browser_check']['vs'])) { echo 'vs:'.$phpwcms['browser_check']['vs']; } ?>};
</script>
<script type="text/javascript" src="//browser-update.org/update.js"></script>
<?php endif; ?>
</body>
</html>