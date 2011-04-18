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


// Alias ID

echo "<tr><td>&nbsp;</td><td class=\"v10\">".$BL['be_alias_ID'].': ';
$content["alias"] = unserialize($row["acontent_form"]);
$content['alias_link'] = '';
if(empty($content["alias"]['alias_ID'])) {
	$content["alias"]['alias_ID'] = '';
} else {
	$content["alias"]['alias_ID'] = intval($content["alias"]['alias_ID']);
	$sql_cnt  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_id=".$content["alias"]['alias_ID']." AND acontent_trash=0";
	if($cntresult = mysql_query($sql_cnt, $db)) {
		if($cntrow = mysql_fetch_assoc($cntresult)) {
			//http://sommerschule.webverbund.info/
			$content['alias_link']  = ', ';
			$content['alias_link'] .= '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=';
			$content['alias_link'] .= $cntrow['acontent_aid'].'&amp;acid='.$content["alias"]['alias_ID'];
			$content['alias_link'] .= '" target="_blank">'.$BL['be_article_cnt_edit'].': ';
			$content['alias_link'] .= $wcs_content_type[$cntrow['acontent_type']].'</a>';
			$content["alias"]['alias_ID'] = '<strong>'.$content["alias"]['alias_ID'].'</strong>';
		} else {
			$content["alias"]['alias_ID'] = '';
		}
		mysql_free_result($cntresult);
	} else {
		$content["alias"]['alias_ID'] = '';
	}
}
echo $content["alias"]['alias_ID'].$content['alias_link'];
echo "</td><td>&nbsp;</td></tr>";

?>