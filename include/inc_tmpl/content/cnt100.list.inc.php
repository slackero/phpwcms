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


// List

$cinfo[1] = html_specialchars(cut_string($row["acontent_title"],'&#8230;', 55));
$cinfo[2] = html_specialchars(cut_string($row["acontent_subtitle"],'&#8230;', 55));
$cinfo_list_type = unserialize($row["acontent_form"]);
switch($cinfo_list_type['list_type']) {
	case 1:		$cinfo_list_type = '&lt;ol&gt;'; break;
	case 2:		$cinfo_list_type = '&lt;dl&gt;'; break;
	default:	$cinfo_list_type = '&lt;ul&gt;';
}
$cinfo[3] = str_replace("\n", " ", '<strong>'.$cinfo_list_type.'</strong> / '.cut_string($row["acontent_text"],'&#8230;', 150));	
$cinfo["result"] = "";

foreach($cinfo as $value) {
 if($value) $cinfo["result"] .= $value."\n";
}
$cinfo["result"] = str_replace("\n", " / ", chop($cinfo["result"]));
if($cinfo["result"]) { //Zeige Inhaltinfo
 echo "<tr><td>&nbsp;</td><td class=\"v10\">";
 echo "<a href=\"phpwcms.php?do=articles&p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=".$row["acontent_id"]."\">";
 echo $cinfo["result"]."</a></td><td>&nbsp;</td></tr>";
}
unset($cinfo_list_type);

?>