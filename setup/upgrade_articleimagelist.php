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

//used to convert old style file uploads

require_once ('../config/phpwcms/conf.inc.php');
require_once ('../include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

echo '<html><body><pre>';


$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_type=2 and acontent_image != ''";
$result = mysql_query($sql, $db);
$total = mysql_num_rows($result);

echo 'TOTAL: '.$total." ENTRIES\n";
echo '=================================================================

If last line number is  < '.$total.'  <a href="upgrade_articleimagelist.php">click here</a> (will run again)'."\n\n";

$linenumber = 1;

while($row = mysql_fetch_assoc($result)) {
	
	$error = false;
	$imglist = array();
	
	$image = explode("\n", $row['acontent_image']);
	
	if(is_array($image) && count($image)) {
	
		$g		= 0;
	
		foreach($image as $key => $value) {
		
			$ival  = explode(":", chop($value));

			$fsql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_id='".intval($ival[0])."' LIMIT 1";
			if($fresult = mysql_query($fsql, $db)) {

				if($frow = mysql_fetch_assoc($fresult)) {
				
					$imglist['images'][$g][0]	= $frow['f_id'];
					$imglist['images'][$g][1]	= $frow['f_name'];
					$imglist['images'][$g][2]	= $frow['f_hash'];
					$imglist['images'][$g][3]	= $frow['f_ext'];
					if(count($ival) > 9) {
						$imglist['images'][$g][4]	= $ival[3];
						$imglist['images'][$g][5]	= $ival[4];
						$imglist['images'][$g][6]	= trim(base64_decode($ival[9]));
					
						$imglist['width']		= $ival[3];
						$imglist['height']		= $ival[4];
						$imglist['pos']			= $ival[5];
						$imglist['space']		= $ival[6];
						$imglist['col']			= $ival[7];
						$imglist['zoom']		= $ival[10];
					} else {
						$imglist['images'][$g][4]	= '';
						$imglist['images'][$g][5]	= '';
						$imglist['images'][$g][6]	= '';
						
						$imglist['width']		= '';
						$imglist['height']		= '';
						$imglist['pos']			= 1;
						$imglist['space']		= 0;
						$imglist['col']			= 1;
						$imglist['zoom']		= 0;
					}

					$g++;
				}
				mysql_free_result($fresult);
			}
		}
		
		$usql  = "UPDATE ".DB_PREPEND."phpwcms_articlecontent SET ";
		$usql .= "acontent_image='', acontent_form='".((count($imglist)) ? aporeplace(serialize($imglist)) : '')."' ";
		$usql .= "WHERE acontent_id=".$row['acontent_id']." LIMIT 1";
		$done = _dbQuery($usql, 'UPDATE');
	
	}
	
	echo sprintf('%05d: ', $linenumber).' CP-ID: '.$row['acontent_id']."\n";
	flush();
	$linenumber++;

}

echo '</pre></body></html>';

?>