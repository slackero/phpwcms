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

//list($id, $wert) = explode(".", $_GET["do"]);
//$do		= intval($do);
//$id		= intval($id);

//Wechseln des Status AKTIV für Datei/Ordner
if(isset($_GET["aktiv"])) {
	list($id, $wert) = explode("|", $_GET["aktiv"]);
	$id		= intval($id);
	$wert	= intval($wert);
	if($wert != 1 && $wert != 0) $wert = 0;
	$sql =  "UPDATE ".DB_PREPEND."phpwcms_file SET ".
			"f_aktiv=".$wert.", f_changed='".time()."' ".
			"WHERE f_id=".$id." AND f_uid=".intval($_SESSION["wcs_user_id"]);
	$result = mysql_query($sql, $db) or die ("error while changing ACTIVE status");
}

if(isset($_GET["public"])) {
	list($id, $wert) = explode("|", $_GET["public"]);
	$id		= intval($id);
	$wert	= intval($wert);
	if($wert != 1 && $wert != 0) $wert = 0;
	$sql =  "UPDATE ".DB_PREPEND."phpwcms_file SET ".
			"f_public=".$wert.", f_changed='".time()."' ".
			"WHERE f_id=".$id." AND f_uid=".intval($_SESSION["wcs_user_id"]);
	$result = mysql_query($sql, $db) or die ("error while changing PUBLIC status");
}

if(isset($_GET["delete"])) {
	list($id, $wert) = explode("|", $_GET["delete"]);
	$id		= intval($id);
	$wert	= intval($wert);
	if($wert == 9) {
		$sql =  "UPDATE ".DB_PREPEND."phpwcms_file SET ".
				"f_trash=9, f_changed='".time()."' ".
				"WHERE f_id=".$id." AND f_uid=".intval($_SESSION["wcs_user_id"]);
		$result = mysql_query($sql, $db) or die ("error while deleted directory");
	}
}

if(isset($_GET["trash"])) {
	list($id, $wert) = explode("|", $_GET["trash"]);
	$id		= intval($id);
	$wert	= intval($wert);
	if($wert == 1 || $wert == 9 || $wert == 0) {
		$sql  = "UPDATE ".DB_PREPEND."phpwcms_file SET f_pid=0, ".
				"f_trash=".$wert.", f_changed='".time()."' WHERE ";
		$sql .= ($id) ? "f_id=".$id." AND " : "f_trash=1 AND ";
		$sql .= "f_kid=1 AND f_uid=".intval($_SESSION["wcs_user_id"]); 
		$result = mysql_query($sql, $db) or die ("error while moving file to trash");
	}
}

if(isset($_GET["paste"])) {
	list($file_id, $dir_id) = explode("|", $_GET["paste"]);
	$file_id	= intval($file_id);
	$dir_id		= intval($dir_id);
	$sql =  "UPDATE ".DB_PREPEND."phpwcms_file SET f_pid=".$dir_id.", ".
			"f_changed='".time()."' WHERE f_id=".$file_id." AND f_kid=1 AND f_uid=".intval($_SESSION["wcs_user_id"]);
	$result = mysql_query($sql, $db) or die ("error while moving file to other directory");
}

if(isset($_GET["thumbnail"])) {
	$_SESSION["wcs_user_thumb"] = intval($_GET["thumbnail"]);
}

if($_SESSION["wcs_user_admin"] == 1) { //Wenn Benutzer Admin-Rechte hat
	
	//move deleted files into final deletion directory
	if(isset($_GET['movedeletedfiles']) && intval($_GET['movedeletedfiles']) == $_SESSION["wcs_user_id"]) {
		
		$sql =  "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_trash=9 AND f_kid=1";
		if($result = mysql_query($sql, $db) or die ("error while retrieving list of deleted files")) {
		
			//default file storage folder
			$default_path = PHPWCMS_ROOT.$phpwcms["file_path"];
			$tempimg_path = PHPWCMS_ROOT.'/'.PHPWCMS_IMAGES;
			
			if(!is_dir($default_path.'can_be_deleted')) {
				@mkdir ($default_path.'can_be_deleted', 0777);
			}
		
			while($row = mysql_fetch_assoc($result)) {
			
				$delstatus = false;
				// name of the file that should be moved
				$filename = ($row['f_ext']) ? $row['f_hash'].'.'.$row['f_ext'] : $row['f_hash'];
				if(is_file($default_path.$filename)) {
				
					if(@rename($default_path.$filename, $default_path.'can_be_deleted/'.$filename)) {
						$delstatus = true;
					}
				
				} else {
					$delstatus = true;
				}
				
				if($delstatus) {
				
					$sql_f  = "UPDATE ".DB_PREPEND."phpwcms_file SET f_trash=8 WHERE f_id=".$row['f_id']." AND f_kid=1";
					@mysql_query($sql_f, $db);
					
					$temp_img = _dbQuery('SELECT * FROM '.DB_PREPEND."phpwcms_imgcache WHERE imgcache_hash='".aporeplace($row['f_hash'])."'");
					$g = 0;
					foreach($temp_img as $value) {
						if(is_file($tempimg_path.$value['imgcache_imgname'])) {
							if(@rename($tempimg_path.$value['imgcache_imgname'], $default_path.'can_be_deleted/'.$value['imgcache_imgname'])) {
								$g++;
							}
						}
					}
					if($g) {
						@_dbQuery('DELETE FROM '.DB_PREPEND."phpwcms_imgcache WHERE imgcache_hash='".aporeplace($row['f_hash'])."'", 'DELETE');
					}
				
				}
			
			
			}
			mysql_free_result($result);
		
		}
	
	}
}

headerRedirect($ref);

?>