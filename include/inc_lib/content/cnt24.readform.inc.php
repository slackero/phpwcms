<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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



// Content Type Text
$content["alias"] = array();
$content["alias"]['alias_ID'] 		= isset($_POST["calias"]) ? intval($_POST["calias"]) : '';
$content["alias"]['alias_block']	= empty($_POST["cablock"]) ? 0 : 1;
$content["alias"]['alias_spaces']	= empty($_POST["caspaces"]) ? 0 : 1;
$content["alias"]['alias_title']	= empty($_POST["catitle"]) ? 0 : 1;
$content["alias"]['alias_toplink']	= empty($_POST["catop"]) ? 0 : 1;	
if(empty($content["alias"]['alias_ID'])) {
	$content["alias"]['alias_ID'] = '';
} else {

	// check if alias ID has valid counter part
	$sql_cnt  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_id=".$content["alias"]['alias_ID']." AND acontent_trash=0";
	if($cresult = mysql_query($sql_cnt, $db)) {
		if(!mysql_fetch_row($cresult)) {
			$content["alias"]['alias_ID'] = '';
		}
		mysql_free_result($cresult);
	} else {
		$content["alias"]['alias_ID'] = '';
	}
}




?>