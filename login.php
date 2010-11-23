<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// session_name('hashID');
session_start();

$phpwcms	= array();
$BL			= array();

require_once ('./config/phpwcms/conf.inc.php');
require_once ('./include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lang/code.lang.inc.php');

$_SESSION['REFERER_URL'] = PHPWCMS_URL.get_login_file();

// make compatibility check
if(phpwcms_revision_check_temp($phpwcms["revision"]) !== true) {
	$revision_status = phpwcms_revision_check($phpwcms["revision"]);
}

// define vars
$err 		= 0;
$wcs_user 	= '';

// where user should be redirected too after login
if(!empty($_POST['ref_url'])) {
	$ref_url = xss_clean($_POST['ref_url']);
} elseif(!empty($_GET['ref'])) {
	$ref_url = xss_clean(rawurldecode($_GET['ref']));
} else {
	$ref_url = '';
}


// reset all inactive users
$sql  = "UPDATE ".DB_PREPEND."phpwcms_userlog SET ";
$sql .= "logged_in = 0, logged_change = '".time()."' ";
$sql .= "WHERE logged_in = 1 AND ( ".time()." - logged_change ) > ".intval($phpwcms["max_time"]);
mysql_query($sql, $db);


//load default language EN
require_once (PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php');

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
	$_SESSION["wcs_user_lang"] 			= 'en'; //by ono
	$_SESSION["wcs_user_lang_custom"] 	= 0;
}
if(!empty($_SESSION["wcs_user_lang_custom"])) { 
	//use custom lang if available -> was set in login.php
	$BL['merge_lang_array'][0] = $BL['be_admin_optgroup_label'];
	$BL['merge_lang_array'][1] = $BL['be_cnt_field'];	
	include_once (PHPWCMS_ROOT.'/include/inc_lang/backend/'.$_SESSION["wcs_user_lang"].'/lang.inc.php');
	$BL['be_admin_optgroup_label'] = array_merge($BL['merge_lang_array'][0], $BL['be_admin_optgroup_label']);
	$BL['be_cnt_field'] = array_merge($BL['merge_lang_array'][1], $BL['be_cnt_field']);
}

//WYSIWYG EDITOR:
//0 = no wysiwyg editor (default)
//1 = CKEditor
//2 = FCKeditor
$phpwcms["wysiwyg_editor"]		= abs(intval($phpwcms["wysiwyg_editor"]));
if($phpwcms["wysiwyg_editor"] > 2) {
	$phpwcms["wysiwyg_editor"] = 1;
}
$_SESSION["WYSIWYG_EDITOR"]		= $phpwcms["wysiwyg_editor"];
$wysiwyg_template				= '';

if($phpwcms["wysiwyg_editor"]) {
					
	if(!empty($phpwcms['wysiwyg_template']['FCKeditor'])) {
		$wysiwyg_template = convertStringToArray($phpwcms['wysiwyg_template']['FCKeditor']);
	} elseif(!empty($phpwcms['wysiwyg_template']['CKEditor'])) {
		$wysiwyg_template = convertStringToArray($phpwcms['wysiwyg_template']['CKEditor']);
	}
	
	if(empty($wysiwyg_template) || count($wysiwyg_template) == 0) {
		$wysiwyg_template = array('Basic');
	}

}

if(isset($_POST['form_aktion']) && $_POST['form_aktion'] == 'login' && isset($_POST['json']) && $_POST['json'] == '1') {

	$login_passed = 0;
	$wcs_user = slweg($_POST['form_loginname']);
	$wcs_pass = slweg($_POST['md5pass']);
	
	$sql_query =	"SELECT * FROM ".DB_PREPEND."phpwcms_user WHERE usr_login='".
					aporeplace($wcs_user)."' AND usr_pass='".
					aporeplace($wcs_pass)."' AND usr_aktiv=1 AND (usr_fe=1 OR usr_fe=2)";

	if($result = mysql_query($sql_query)) {
		if($row = mysql_fetch_assoc($result)) {
			$_SESSION["wcs_user"]			= $wcs_user;
			$_SESSION["wcs_user_name"] 		= ($row["usr_name"]) ? $row["usr_name"] : $wcs_user;
			$_SESSION["wcs_user_id"]		= $row["usr_id"];
			$_SESSION["wcs_user_aktiv"]		= $row["usr_aktiv"];
			$_SESSION["wcs_user_rechte"]	= $row["usr_rechte"];
			$_SESSION["wcs_user_email"]		= $row["usr_email"];
			$_SESSION["wcs_user_avatar"]	= $row["usr_avatar"];
			$_SESSION["wcs_user_logtime"]	= time();
			$_SESSION["wcs_user_admin"]		= intval($row["usr_admin"]);
			$_SESSION["wcs_user_thumb"]		= 1;
			if($row["usr_lang"]) {
				$_SESSION["wcs_user_lang"]	= $row["usr_lang"];
			}
			
			set_language_cookie();
						
			$_SESSION["structure"]			= @unserialize($row["usr_var_structure"]);
			$_SESSION["klapp"]				= @unserialize($row["usr_var_privatefile"]);
			$_SESSION["pklapp"]				= @unserialize($row["usr_var_publicfile"]);
			$row["usr_vars"]				= @unserialize($row["usr_vars"]);
			$_SESSION["WYSIWYG_TEMPLATE"]	= empty($row["usr_vars"]['template']) || !in_array($row["usr_vars"]['template'], $wysiwyg_template) ? $wysiwyg_template[0] : $row["usr_vars"]['template'];
			
			$row["usr_wysiwyg"]				= abs(intval($row["usr_wysiwyg"]));
			// Fallback to FCKeditor?
			$_SESSION["WYSIWYG_EDITOR"]		= $row["usr_wysiwyg"] > 2 ? 2 : $row["usr_wysiwyg"];
			
			$login_passed = 1;
		}
		mysql_free_result($result);
	}
	
	if($login_passed) {
		//Schreiben der Login-Daten in Datenbank
		$check = mysql_query(	"SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_userlog WHERE logged_user='".
								aporeplace($wcs_user)."' AND logged_in=1", $db );
		if($row = mysql_fetch_row($check)) {
			if(!$row[0]) {
				//Wenn kein User geführt wird, dann neu anlegen
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
			headerRedirect($ref_url.'&'.session_name().'='.session_id());
		} else {
			headerRedirect(PHPWCMS_URL."phpwcms.php?". session_name().'='.session_id());
		}

	} else {
		$err = 1;
	}

} elseif(isset($_POST['json']) && intval($_POST['json']) != 1) {

	$err = 1;

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title><?php echo $BL['be_page_title'] . ' - ' . PHPWCMS_HOST ?></title>
	<meta http-equiv="content-type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>" />
	<meta name="robots" content="noindex, nofollow" />
	<link href="include/inc_css/login.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="include/inc_js/phpwcms.js"></script>
	<script type="text/javascript" src="include/inc_js/md5.js"></script>
<?php

if((isset($_SESSION["wcs_user_lang"]) && $_SESSION["wcs_user_lang"] == 'ar') || strtolower($phpwcms['default_lang']) == 'ar') {
	echo '	<style type="text/css">' . LF . '<!--' . LF . '* {direction: rtl;}' . LF . '// -->' . LF . '</style>';
}

?>
</head>

<body>
<table width="504" border="0" align="center" cellpadding="0" cellspacing="0" summary="Login Screen">
  <tr>
    <td colspan="3"><img src="img/leer.gif" alt="" width="1" height="12" /></td>
  </tr>
  <tr>
    <td colspan="3"><a href="index.php" target="_top"><img src="img/backend/preinfo2.jpg" alt="phpwcms" width="122" height="31" border="0" hspace="18" /></a></td>
  </tr>
  <tr>
    <td colspan="3"><img src="img/leer.gif" alt="" width="1" height="7" /></td>
  </tr>
  <tr>
    <td colspan="3"><a href="index.php" target="_top"><img src="img/backend/preinfo2_r4_c2.jpg" alt="phpwcms" width="504" height="154" border="0" /></a></td>
  </tr>
  <tr>
    <td colspan="3"><img src="img/leer.gif" alt="" width="1" height="11" /></td>
  </tr>
  <tr>
    <td width="15" style="width:15px;"><img src="img/backend/preinfo2_r6_c2.gif" alt="" width="15" height="15" border="0" /></td>
    <td width="474" bgcolor="#FFFFFF" style="width:474px;"><img src="img/backend/preinfo2_r6_c3.gif" alt="" width="474" height="15" border="0" /></td>
    <td width="15" style="width:15px;"><img src="img/backend/preinfo2_r6_c7.gif" alt="" width="15" height="15" border="0" /></td>
  </tr>
  <tr>
    <td style="background-image:url(img/backend/preinfo2_r7_c2.gif);background-repeat:repeat-y;" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF" style="padding-left:3px;padding-right:3px;" id="loginFormArea">
		<div class="error" style="font-weight:bold;padding:0 0 15px 0;font-size:12px;text-align:center"><?php
	
			echo $BL['be_login_jsinfo'];
	
		?></div></td>
    <td style="background-image:url(img/backend/preinfo2_r7_c7.gif);background-repeat:repeat-y;background-position:right;" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td style="background-image:url(img/backend/preinfo2_r7_c2.gif);background-repeat:repeat-y;" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF" style="padding: 0 3px 5px 3px;">
		<strong><a href="http://www.phpwcms.de" target="_blank" style="text-decoration:none;">phpwcms</a></strong> 
		Copyright &copy; 2003&#8212;<?php echo date('Y'); ?>
        Oliver Georgi. Extensions are copyright of their respective owners.
        Visit <a href="http://www.phpwcms.de" target="_blank">http://www.phpwcms.de</a> for
        details. phpwcms is free software released under <a href="http://www.fsf.org/licensing/licenses/gpl.html" target="_blank">GPL</a> 
		and comes WITHOUT ANY WARRANTY. Obstructing the appearance of this notice is prohibited  by law. 
    </td>
    <td style="background-image:url(img/backend/preinfo2_r7_c7.gif);background-repeat:repeat-y;background-position:right;" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td><img src="img/backend/preinfo2_r9_c2.gif" alt="" width="15" height="15" border="0" /></td>
    <td bgcolor="#FFFFFF"><img src="img/backend/preinfo2_r9_c3.gif" alt="" width="474" height="15" border="0" /></td>
    <td><img src="img/backend/preinfo2_r9_c7.gif" alt="" width="15" height="15" border="0" /></td>
  </tr>
</table>
<?php

// get whole login form and keep in buffer
ob_start();

?>
<form action="<?php echo PHPWCMS_URL.get_login_file() ?>" method="post" name="login_formular" id="login_formular" style="margin:0;padding:0;" onsubmit="return login(this);" autocomplete="off">
<input type="hidden" name="json" id="json" value="0" />
<input type="hidden" name="md5pass" id="md5pass" value="" autocomplete="off" />
<input type="hidden" name="ref_url" value="<?php echo html_entities($ref_url) ?>" />
<input name="form_aktion" type="hidden" id="form_aktion" value="login" />
<?php 
	  
	echo '<h1>'.$BL["login_text"].'</h1>';
	
	if(file_exists(PHPWCMS_ROOT.'/setup')) {
		echo '<div class="error" style="margin-top:10px;">'.$BL["setup_dir_exists"].'</div>';
	}
	if(file_exists(PHPWCMS_ROOT.'/phpwcms_code_snippets')) {
		echo '<div class="error" style="margin-top:10px;">'.$BL["phpwcms_code_snippets_dir_exists"].'</div>';
	}
	
	if(isset($_POST['json']) && $_POST['json'] == 2) $err = 0;
	
	if($err) {
		echo '<div class="error" style="margin-top:10px;font-weight:bold;">'.$BL["login_error"].'</div>';
	}
	
	echo '<div class="error" style="margin-top:10px;font-weight:bold;display:none;" id="jserr">'.$BL["login_error"].'</div>';	
	
	?>	

	<table border="0" cellpadding="0" cellspacing="0" summary="Login Form" style="margin:15px 0 20px 10px">
        <tr>
          <td align="right" nowrap="nowrap" class="v10"><?php echo $BL["login_username"] ?>:&nbsp;</td>
          <td class="v10"><input name="form_loginname" type="text" id="form_loginname" style="width:250px;" size="30" maxlength="30" value="<?php echo html_specialchars($wcs_user); ?>" /></td>
          </tr>
        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
        <tr>
          <td align="right" nowrap="nowrap" class="v10"><?php echo $BL["login_userpass"] ?>:&nbsp;</td>
          <td class="v10"><input name="form_password" type="password" id="form_password" style="width:250px;" size="30" maxlength="40" /></td>
          </tr>
        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
        <tr>
          <td align="right" nowrap="nowrap" class="v10"><?php echo $BL["login_lang"] ?>:&nbsp;</td>
          <td class="v10"><select name="form_lang" id="form_lang" style="width:250px;" onchange="getObjectById('json').value='2';login(this.form);">
            <?php
// check available languages installed and build language selector menu
$lang_dirs = opendir(PHPWCMS_ROOT.'/include/inc_lang/backend');
$lang_code = array();
while($lang_codes = readdir( $lang_dirs )) {
	if( $lang_codes != "." && $lang_codes != ".." && is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$lang_codes."/lang.inc.php")) {
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
        <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
        <tr>
          <td>&nbsp;</td>
          <td><input name="submit_form" type="submit" value="<?php echo $BL["login_button"] ?>" /></td>
          </tr>
    </table>
    </form>
<?php

$formAll = ob_get_contents();
ob_end_clean();

$formAll = str_replace( "'", "\'", trim($formAll) );
$formAll = str_replace( "\r", '', $formAll );
$formAll = str_replace( "\n", "';\nlf += '", $formAll );
$formAll = str_replace( '<', "<'+'", $formAll );

?>
<script type="text/javascript">
var lf  = '<?php echo $formAll ?>';
getObjectById('loginFormArea').innerHTML = lf;
getObjectById('form_loginname').focus();
</script>
</body>
</html>