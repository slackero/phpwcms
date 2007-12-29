<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


//Aktualisieren der Userliste bzgl. der eingeloggten Zeit, Notfalls deaktivieren
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


$sql  = "UPDATE ".DB_PREPEND."phpwcms_userlog SET ";
$sql .= "logged_in = 0, logged_change = '".time()."' ";
$sql .= "WHERE logged_in = 1 AND ( ".time()." - logged_change ) > ".intval($phpwcms["max_time"]);
mysql_query($sql, $db);

if(!empty($_SESSION["wcs_user"])) {
	$sql  = "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_userlog ";
	$sql .= "WHERE logged_user='".aporeplace($_SESSION["wcs_user"])."' AND ";
	$sql .= "logged_in=1";
	if(!empty($phpwcms['Login_IPcheck'])) {
		$sql .= " AND logged_ip='".aporeplace(getRemoteIP())."'";
	}
	if($check = mysql_query($sql, $db)) {
		if($row = mysql_fetch_row($check)) {
			if($row[0] == 0) {
				unset($_SESSION["wcs_user"]);
			} else {
				$sql  = "UPDATE ".DB_PREPEND."phpwcms_userlog SET ";
				$sql .= "logged_change=".time()." WHERE ";
				$sql .= "logged_user='".aporeplace($_SESSION["wcs_user"])."' AND logged_in=1";
				mysql_query($sql, $db);
			}
			mysql_free_result($check);
		}
	}
}
if(empty($_SESSION["wcs_user"])) {
	@session_destroy();
	$ref_url = '';
	if(!empty($_SERVER['QUERY_STRING'])) {
		$ref_url = '?ref='.rawurlencode(PHPWCMS_URL.'phpwcms.php?'.xss_clean($_SERVER['QUERY_STRING']));
	}
	headerRedirect(PHPWCMS_URL.'login.php'.$ref_url);
}
?>