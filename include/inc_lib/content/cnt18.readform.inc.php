<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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
if(!is_valid_email($content["guestbook"]["notify_email"])) {
	$content["guestbook"]["notify"] = 0;
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

?>