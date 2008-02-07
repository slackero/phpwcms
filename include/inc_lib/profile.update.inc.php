<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


//Updates the profile datas
$sql =	"UPDATE ".DB_PREPEND."phpwcms_userdetail SET ".
		"detail_title='".getpostvar($_POST["form_title"])."',".
		"detail_firstname='".getpostvar($_POST["form_firstname"])."',".
		"detail_lastname='".getpostvar($_POST["form_lastname"])."',".
		"detail_company='".getpostvar($_POST["form_company"])."',".
		"detail_street='".getpostvar($_POST["form_street"])."',".
		"detail_add='".getpostvar($_POST["form_add"])."',".
		"detail_city='".getpostvar($_POST["form_city"])."',".
		"detail_zip='".getpostvar($_POST["form_zip"])."',".
		"detail_region='".getpostvar($_POST["form_region"])."',".
		"detail_country='".getpostvar($_POST["form_country"])."',".
		"detail_fon='".getpostvar($_POST["form_fon"])."',".
		"detail_fax='".getpostvar($_POST["form_fax"])."',".
		"detail_mobile='".getpostvar($_POST["form_mobile"])."',".
		"detail_signature='".getpostvar(substr($_POST["form_signature"],0,250))."',".
		"detail_prof='".getpostvar($_POST["form_prof"])."',".
		"detail_notes='".getpostvar(substr($_POST["form_notes"],0,3000))."',".
		"detail_public=".(empty($_POST["form_public"]) ? 0 : 1).",".
		"detail_newsletter=".(empty($_POST["form_newsletter"]) ? 0 : 1)." WHERE ".
		"detail_pid=".$_SESSION["wcs_user_id"].";";
if(mysql_query($sql)) {
	$detail_updated = $BL['be_profile_update_success'];	
} else {
	$detail_updated = $BL['be_profile_update_error'];
}
?>