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


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Content Type Guestbook
$content["guestbook"] = array();
$content["guestbook"]["listing"] 			= intval($_POST["cguestbook_listing"]);
$content["guestbook"]["listcount"] 			= intval($_POST["cguestbook_listcount"]);
if(!$content["guestbook"]["listcount"] || !$content["guestbook"]["listing"]) {
	$content["guestbook"]["listcount"]		= '';
	$content["guestbook"]["listing"]		= 0;
} else {
	$content["guestbook"]["listing"]		= 1;
}
if($content["guestbook"]["listing"] == 0) {
	$content["guestbook"]["listcount"]		= '';
	$content["guestbook"]["listing"]		= 0;
}
$content["guestbook"]["sorting"] 			= intval($_POST["cguestbook_sorting"]) ? 1 : 0;
$content["guestbook"]["image_upload"] 		= intval($_POST["cguestbook_imgupload"]) ? 1 : 0;
$content["guestbook"]["template"]			= clean_slweg($_POST["cguestbook_template"]);
$content["guestbook"]["banned"]				= trim(clean_slweg($_POST["cguestbook_banned"]));
$content["guestbook"]["banned"]				= preg_replace('/\s{1,}/is', ' ', $content["guestbook"]["banned"]);
$content["guestbook"]["aliasID"] 			= intval($_POST["cguestbook_aliasID"]);
if(!$content["guestbook"]["aliasID"]) $content["guestbook"]["aliasID"] = '';
$content["guestbook"]["time"] 				= intval($_POST["cguestbook_time"]);
$content["guestbook"]["cookie"] 			= empty($_POST["cguestbook_cookie"]) ? 0 : 1;
$content["guestbook"]["captcha"] 			= empty($_POST["cguestbook_captcha"]) ? 0 : 1;

$content["guestbook"]["gb_login_show"] 		= empty($_POST["cguestbook_login_show"]) ? 0 : 1;
$content["guestbook"]["gb_login_post"] 		= empty($_POST["cguestbook_login_post"]) ? 0 : 1;
$content["guestbook"]["gb_urlcheck"] 		= empty($_POST["cguestbook_urlcheck"]) ? 0 : 1;

$content["guestbook"]["notify"] 			= empty($_POST["cguestbook_notify"]) ? 0 : 1;
$content["guestbook"]["notify_email"] 		= clean_slweg($_POST["cguestbook_notify_email"]);
if(empty($content["guestbook"]["notify_email"])) {
	$content["guestbook"]["notify"] = 0;
} else{
	$content["guestbook"]["notify_email"] = convertStringToArray(str_replace(',', ';', $content["guestbook"]["notify_email"]), ';');
	foreach($content["guestbook"]["notify_email"] as $key => $item) {
		if(!is_valid_email($item)) {
			unset($content["guestbook"]["notify_email"][$key]);
		}
	}
	$content["guestbook"]["notify_email"] = implode(';', $content["guestbook"]["notify_email"]);
	if($content["guestbook"]["notify_email"] == '') {
		$content["guestbook"]["notify"] = 0;
	}
}
$content["guestbook"]["captcha_maxchar"]	= intval($_POST['cguestbook_captchamaxchar']);
if(!$content["guestbook"]["captcha_maxchar"]) {
	$content["guestbook"]["captcha_maxchar"] = 5;
} elseif($content["guestbook"]["captcha_maxchar"] > 15) {
	$content["guestbook"]["captcha_maxchar"] = 15;
}

$content["guestbook"]["max_image_filesize"]	= return_bytes_shorten(clean_slweg($_POST["cguestbook_maximgsize"]));
if(!(return_bytes($content["guestbook"]["max_image_filesize"]))) {
	$content["guestbook"]["max_image_filesize"] = return_bytes_shorten($phpwcms['file_maxsize']);
}
