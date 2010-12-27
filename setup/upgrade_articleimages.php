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


$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_type=1 and acontent_image != ''";
$result = mysql_query($sql, $db);
$total = mysql_num_rows($result);

echo 'TOTAL: '.$total." ENTRIES\n";
echo '================================================================='."\n\n";

$linenumber = 1;

while($row = mysql_fetch_assoc($result)) {
	
	$error = false;
	$image = explode(':', $row['acontent_image']);
	
	$fsql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_id='".intval($image[0])."' LIMIT 1";
	if($fresult = mysql_query($fsql, $db)) {
	
		if($frow = mysql_fetch_assoc($fresult)) {

			// dbid:filename:hash:extension:width:height:caption:position:zoom
			$newimage  = $frow['f_id'];
			$newimage .= ':';
			$newimage .= $frow['f_name'];
			$newimage .= ':';
			$newimage .= $frow['f_hash'];
			$newimage .= ':';
			$newimage .= $frow['f_ext'];
			$newimage .= ':';
			$newimage .= $image[3];
			$newimage .= ':';
			$newimage .= $image[4];
			$newimage .= ':';
			$newimage .= $image[7];
			$newimage .= ':';
			$newimage .= $image[5];
			$newimage .= ':';
			$newimage .= (isset($image[8]) && intval($image[8])) ? 1 : 0;
			
			// check if this is an updated content part
			if($image[2] != $frow['f_hash'] && $image[3] != $frow['f_ext']) {
				$usql  = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET ";
				$usql .= "acontent_image='".aporeplace($newimage)."' ";
				$usql .= "WHERE acontent_id=".$row['acontent_id']." LIMIT 1";
				mysql_query($usql, $db);
				echo 'Image '. sprintf('%05d: ', $linenumber) . html_specialchars($frow['f_name']) ."\n";
			}
	
		}
		mysql_free_result($fresult);
	
	}

	flush();
	$linenumber++;

}

if(empty($usql)) {
	echo 'None of the content parts &quot;image with text&quot; needs to be upgraded.';
}

echo '</pre></body></html>';

?>