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

//used to convert old style file uploads

$phpwcms = array();

require_once ('../config/phpwcms/conf.inc.php');
require_once ('../include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');


echo '<html><body><pre>';


$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_hash='' AND f_kid=1";
$result = mysql_query($sql, $db);
$total = mysql_num_rows($result);

echo 'TOTAL: '.$total." ENTRIES\n";
echo '=================================================================

If last line number is  < '.$total.'  <a href="upgrade_filestorage.php">click here</a>'."\n\n";

$linenumber = 1;
$format     = '%0'.(strlen(strval($total))).'d: ';

while($row = mysql_fetch_assoc($result)) {
	
	$error = false;
	
	echo sprintf($format, $linenumber);

	$fileHash = md5( $row['f_name'] . microtime() );
	$fileOld  = PHPWCMS_ROOT.$phpwcms["file_path"].$row['f_uid'].'/'.$row['f_uid'].'_'.$row['f_id'];
	$fileNew  = PHPWCMS_ROOT.$phpwcms["file_path"].$fileHash;
	if($row['f_ext'] != '') {
		$fileOld .=  '.'.$row['f_ext'];
		$fileNew .=  '.'.$row['f_ext'];
	}
	if(file_exists($fileOld)) {
		if(@copy($fileOld, $fileNew)) {
			$update  = "UPDATE ".DB_PREPEND."phpwcms_file SET ";
			$update .= "f_thumb_list = '', ";
			$update .= "f_thumb_preview = '', ";
			$update .= "f_hash = '".$fileHash."' ";
			$update .= "WHERE f_id=".$row['f_id'];
			if(mysql_query($update, $db)) {
				unlink($fileOld);
			} else {
				$error = 1;
			}
		} else {
			$error = 2;
		}
	
		if($error) {
			echo 'ERROR('.$error.'): ' .$fileOld .' -> ' . $fileNew ."\n";
		} else {
			echo 'SUCCESS: ' .$fileOld .' -> ' . $fileNew ."\n";
		}
	
	} else {
	
		@mysql_query("DELETE FROM ".DB_PREPEND."phpwcms_file WHERE f_kid=1 AND f_id=".$row['f_id']." LIMIT 1", $db);
		echo 'DELETED: ' . $fileOld ."\n";
	
	}
	
	flush();
	$linenumber++;

}

echo '</pre></body></html>';

?>