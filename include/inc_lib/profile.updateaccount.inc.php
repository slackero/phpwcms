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



//Prüfen, ob der Benutzername bereits vergeben ist, aber nur, wenn sich der
//Benutzername geändert hat
if($_SESSION["wcs_user"] != "guest") { //Prüfung für Gastzugang
	
	$err = '';

	$new_username = slweg($_POST["form_loginname"]);
	if ($new_username != $_SESSION["wcs_user"]) {
		$sql = "SELECT COUNT(usr_login) FROM ".DB_PREPEND."phpwcms_user WHERE usr_login='".aporeplace($new_username)."';"; 
		if($result = mysql_query($sql, $db)) {
			if($row = mysql_fetch_row($result)) {
				if($row[0])	$err = str_replace('{VAL}', html_specialchars($new_username), $BL['be_profile_account_err1'])."\n";
			}
			mysql_free_result($result);
		}
	}
	if ($_POST["form_password"] == $_POST["form_password2"]) {
		if(strlen($_POST["form_password"]) > 0) {
		$new_password = slweg($_POST["form_password"]);
		if ( strlen($new_password) < 5) $err .= str_replace('{VAL}', strlen($new_password), $BL['be_profile_account_err2'])."\n";
		}
	} else {
		$err .= $BL['be_profile_account_err3']."\n";
	}
	$new_email = slweg(trim($_POST["form_useremail"]));
	if ($new_email != $_SESSION["wcs_user_email"]) {
		if( !is_valid_email($new_email) ) {
			$err .= str_replace('{VAL}', html_specialchars($new_email), $BL['be_profile_account_err4'])."\n";
		}
	}
	
	if($_POST["form_lang"]) {
		$new_language = slweg(trim($_POST["form_lang"]));
	} else {
		$new_language = $phpwcms["default_lang"];
	}
	
	$new_wysiwyg = empty($_POST['form_wysiwyg']) ? 0 : intval($_POST['form_wysiwyg']);
	$user_var['template'] = empty($_POST['form_wysiwyg_template']) ? '' : clean_slweg($_POST['form_wysiwyg_template']);
	
	
	//Jetzt die Daten aktualisieren
	
	if(empty($err)) {
	
		$sql  = "UPDATE ".DB_PREPEND."phpwcms_user SET ";
		$sql .= "   usr_login='".aporeplace($new_username)."', ";
		
		if(!empty($new_password)) {
			$sql .= "usr_pass='".aporeplace(md5(makeCharsetConversion($new_password, PHPWCMS_CHARSET, 'utf-8')))."', ";
		}
		
		$sql .= "usr_email='".aporeplace($new_email);
		$sql .= "', usr_lang='".aporeplace($new_language);
		$sql .= "', usr_wysiwyg=".$new_wysiwyg;
		$sql .= " , usr_vars='".aporeplace(serialize($user_var))."'";
		$sql .= " WHERE usr_id=".$_SESSION["wcs_user_id"];
		$sql .= " AND usr_login='".$_SESSION["wcs_user"]."' LIMIT 1";

		if(mysql_query($sql, $db)) {
			//Wenn Aktualisierung erfolgreich war
			//neue Werte den Sessionvariablen zuweisen
			$_SESSION["wcs_user"] 			= $new_username;
			$_SESSION["wcs_user_email"] 	= $new_email;
			$_SESSION["wcs_user_lang"] 		= $new_language;
			$_SESSION["WYSIWYG_EDITOR"]		= $new_wysiwyg;
			$_SESSION["WYSIWYG_TEMPLATE"]	= $user_var['template'];
			
			set_language_cookie();
			
			headerRedirect(PHPWCMS_URL."phpwcms.php?do=profile");
		}
	}
} //Ende Prüfung Gastzugang
?>