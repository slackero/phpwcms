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

// session_name('hashID');
session_start();
$phpwcms = array();
require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
checkLogin();
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

if($_SESSION["wcs_user_admin"] == 1) { //Wenn Benutzer Admin-Rechte hat

	//Löschen eines Benutzers
	if(isset($_GET["del"])) {
		$ui = explode(":", clean_slweg($_GET["del"]));
		$user_id = intval($ui[0]);
		$user_email = '';
		if(isset($ui[1])) {
			$user_email = $ui[1];
		}
		if($user_id <> $_SESSION["wcs_user_id"]) {
			$sql =	"UPDATE ".DB_PREPEND."phpwcms_user SET ".
					"usr_login='".generic_string(10)."', ".
					"usr_pass='".md5(generic_string(10))."', ".
					"usr_email='', ".
					"usr_admin=0, ".
					"usr_aktiv=9 ".
					"WHERE usr_id=".$user_id." AND ".
					"usr_email='".aporeplace($user_email)."';";
			if($result = mysql_query($sql, $db)) {
				if(!MailVal($user_email,2)) {
					@mail($user_email, "your account", "YOUR PHPWCMS ACCOUNT WAS DELETED\n \ncontact the admin if you have any question.\n\nSee you at ".$phpwcms["site"], "From: ".$phpwcms["admin_email"]."\nReply-To: ".$phpwcms["admin_email"]."\n");
				}
			}
		}
	}
	
	if(isset($_GET["aktiv"])) {
		$ui = explode(":", clean_slweg($_GET["aktiv"]));
		$user_id = intval($ui[0]);
		$user_aktiv = !empty($ui[1]) ? 1 : 0;
		if($user_id <> $_SESSION["wcs_user_id"]) {
			$sql =	"UPDATE ".DB_PREPEND."phpwcms_user SET usr_aktiv=".$user_aktiv." WHERE usr_id=".$user_id.";";
			mysql_query($sql, $db) or die ("error");
		}
	}
	
} //Ende Abarbeiten Aktion

headerRedirect(PHPWCMS_URL.'phpwcms.php?do=admin');

?>