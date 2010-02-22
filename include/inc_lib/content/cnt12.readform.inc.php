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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Content Type Newsletter Subscription
$content["newsletter"]["text"] 					= html_specialchars(clean_slweg($_POST["cnewsletter_text"]));
$content["newsletter"]["label_email"]			= html_specialchars(clean_slweg($_POST["cnewsletter_label_email"]));
$content["newsletter"]["label_name"]			= html_specialchars(clean_slweg($_POST["cnewsletter_label_name"]));
$content["newsletter"]["label_subscriptions"]	= html_specialchars(clean_slweg($_POST["cnewsletter_label_subscriptions"]));
$content["newsletter"]["all_subscriptions"]		= html_specialchars(clean_slweg($_POST["cnewsletter_all_subscriptions"]));
$content["newsletter"]["button_text"]			= html_specialchars(clean_slweg($_POST["cnewsletter_button_text"]));
$content["newsletter"]["success_text"]			= html_specialchars(clean_slweg($_POST["cnewsletter_success_text"]));
$content["newsletter"]["reg_text"]				= html_specialchars(clean_slweg($_POST["cnewsletter_reg_text"]));
$content["newsletter"]["logoff_text"]			= html_specialchars(clean_slweg($_POST["cnewsletter_logoff_text"]));
$content["newsletter"]["change_text"]			= html_specialchars(clean_slweg($_POST["cnewsletter_change_text"]));
$content["newsletter"]["url1"]					= clean_slweg($_POST["cnewsletter_url1"]);
$content["newsletter"]["url2"]					= clean_slweg($_POST["cnewsletter_url2"]);

$content['subscription_temp']					= convertStringToArray($_POST['cnewsletter_subscription_left']);
$content["newsletter"]["subscription"]			= array();
foreach($content['subscription_temp'] as $subscr_value) {
	$subscr_value = intval($subscr_value);
	$content["newsletter"]["subscription"][$subscr_value] = $subscr_value;
}

$content["newsletter"]["pos"]					= intval($_POST["cnewsletter_pos"]);


?>