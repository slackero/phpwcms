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
$ref = $_SESSION['REFERER_URL'];


require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
checkLogin();
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

if($_SESSION["wcs_user_admin"] == 1) { //Wenn Benutzer Admin-Rechte hat

	//diverse Aktionen	
	$do = explode(",", $_GET["do"]);
	
	switch(intval($do[0])) {
	
		case 1: //Aktiv/Inaktiv File Category
				$do[1] = intval($do[1]); //cat ID
				$do[2] = intval($do[2]); //active value
				if($do[1]) {
					$sql =  "UPDATE ".DB_PREPEND."phpwcms_filecat SET fcat_aktiv=".$do[2]." WHERE fcat_id=".$do[1].";";
					mysql_query($sql, $db) or die("error while changing file category status");			
				}
				break;
		
		case 2: //Aktiv/Inaktiv File Key
				$do[1] = intval($do[1]); //key ID
				$do[2] = intval($do[2]); //active value
				if($do[1]) {
					$sql =  "UPDATE ".DB_PREPEND."phpwcms_filekey SET fkey_aktiv=".$do[2]." WHERE fkey_id=".$do[1].";";
					mysql_query($sql, $db) or die("error while changing file key status");			
				}
				break;		

		case 8: //Löschen der File Category
				$do[1] = intval($do[1]); //delete ID
				if($do[1]) {
					$sql =  "UPDATE ".DB_PREPEND."phpwcms_filecat SET fcat_deleted=9 WHERE fcat_id=".$do[1].";";
					mysql_query($sql, $db) or die("error while deleting file category");			
				}
				break;
	
		case 9: //Löschen des File Keys
				$do[1] = intval($do[1]); //delete ID
				$do[2] = intval($do[2]); //cat ID
				if($do[1] && $do[2]) {
					$sql =  "UPDATE ".DB_PREPEND."phpwcms_filekey SET fkey_deleted=9 WHERE fkey_id=".$do[1]." AND fkey_cid=".$do[2].";";
					mysql_query($sql, $db) or die("error while deleting file key");			
				}
				break;
	
	}
	
} //Ende Abarbeiten Aktion

headerRedirect($ref);

?>