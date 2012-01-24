<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
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


//Create new profile data if not existing
$sql =	"INSERT INTO ".DB_PREPEND."phpwcms_userdetail (".
		"detail_pid, detail_title, detail_firstname, detail_lastname, ".
		"detail_company, detail_street, detail_add, detail_city, ".
		"detail_zip, detail_region, detail_country, detail_fon, detail_fax, ".
		"detail_mobile, detail_signature, detail_prof, detail_notes, ".
		"detail_public, detail_newsletter) VALUES (".
		$_SESSION["wcs_user_id"].", '".
		getpostvar($_POST["form_title"])."', '".
		getpostvar($_POST["form_firstname"])."', '".
		getpostvar($_POST["form_lastname"])."', '".
		getpostvar($_POST["form_company"])."', '".
		getpostvar($_POST["form_street"])."', '".
		getpostvar($_POST["form_add"])."', '".
		getpostvar($_POST["form_city"])."', '".
		getpostvar($_POST["form_zip"])."', '".
		getpostvar($_POST["form_region"])."', '".
		getpostvar($_POST["form_country"])."', '".
		getpostvar($_POST["form_fon"])."', '".
		getpostvar($_POST["form_fax"])."', '".
		getpostvar($_POST["form_mobile"])."', '".
		getpostvar(mb_substr($_POST["form_signature"],0,250))."', '".
		getpostvar($_POST["form_prof"])."', '".
		getpostvar(mb_substr($_POST["form_notes"],0,3000))."', ".
		check_checkbox($_POST["form_public"]).", ".
		check_checkbox($_POST["form_newsletter"]).
		")";
if(mysql_query($sql, $db)) {
	$detail_updated = $BL['be_profile_create_success'];
} else {
	$detail_updated = $BL['be_profile_create_error'];
}
?>