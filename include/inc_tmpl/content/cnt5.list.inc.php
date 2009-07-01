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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Link List

$cinfo[1] = cut_string($row["acontent_title"],'&#8230;', 55);
$cinfo[2] = cut_string($row["acontent_subtitle"],'&#8230;', 55);

$clink = explode(LF, $row["acontent_text"]);
$clink_liste = "";
if(count($clink)) {
 foreach($clink as $key => $value) {
	 list($clink_name, $clink_link)   = explode("|", $value);
	 $clink_link = explode(" ", $clink_link);
	 $clink_target = isset($clink_link[1]) ? $clink_link[1] : '';
	 $clink_link = $clink_link[0];
	 $clink_liste .= "<a href=\"".$clink_link."\" target=\"_blank\" ".
					 "title=\"Link: ".html_specialchars($clink_link.trim(' '.$clink_target))."\">".
					 "<img src=\"img/symbole/link_to_1.gif\" border=\"0\" alt=\"\" />";
	 if(isEmpty($clink_name)) {
		$clink_liste .= html_specialchars($clink_link)."</a>\n";
	 } else {
		$clink_liste .= html_specialchars($clink_name)."</a>\n";
	 }
 }
 unset($clink);
}
$cinfo["result"] = "";

foreach($cinfo as $value) {
 if($value) $cinfo["result"] .= $value."\n";
}
$cinfo["result"] = str_replace("\n", " / ", html_specialchars(chop($cinfo["result"])));
if($cinfo["result"] || $clink_liste) { //Zeige Inhaltinfo
 echo "<tr><td>&nbsp;</td><td class=\"v10\">";
 if($cinfo["result"]) {
	echo "<a href=\"phpwcms.php?do=articles&p=2&s=1&aktion=2&id=".$article["article_id"]."&acid=";
	echo $row["acontent_id"]."\">".$cinfo["result"]."</a><br />";
 }
 echo nl2br(chop($clink_liste))."</td><td>&nbsp;</td></tr>";
}


?>