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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// File List

$cinfo["result"]  = '';
if($row["acontent_title"]) {
	$cinfo["result"] .= getCleanSubString($row["acontent_title"], 55, '&#8230;');
}
if($cinfo["result"] && $row["acontent_subtitle"]) {
	$cinfo["result"] .= ' / ';
}
if($row["acontent_subtitle"]) {
	$cinfo["result"] .= getCleanSubString($row["acontent_subtitle"], 55, '&#8230;');
}
$cinfo["result"]  = html_specialchars($cinfo["result"]);
if($row["acontent_template"]) {
	if($cinfo["result"]) {
		$cinfo["result"] .= ' / ';
	}
	$cinfo["result"] .= $BL['be_admin_struct_template'].': <strong>'.html_specialchars($row["acontent_template"]).'</strong>';
}

if($cinfo["result"]) { //Zeige Inhaltinfo
	echo '<tr><td>&nbsp;</td><td class="v10">';
	echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id='.$article["article_id"]."&amp;acid=".$row["acontent_id"].'">';
	echo $cinfo["result"].'</a></td><td>&nbsp;</td></tr>';
}

?>