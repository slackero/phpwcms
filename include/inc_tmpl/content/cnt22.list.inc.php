<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// Plain Text

$cinfo[1] = html_specialchars(cut_string($row["acontent_title"],'&#8230;', 55));
$cinfo[2] = html_specialchars(cut_string($row["acontent_subtitle"],'&#8230;', 55));
$cinfo["result"] = "";
foreach($cinfo as $value) {
	if($value) $cinfo["result"] .= $value."\n";
}
$cinfo["result"] = str_replace("\n", " / ", trim($cinfo["result"]));


echo "<tr><td>&nbsp;</td><td class=\"v10\">";
if($cinfo["result"]) { //Zeige Inhaltinfo
	echo "<a href=\"phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=".$article["article_id"]."&amp;acid=";
	echo $row["acontent_id"]."\">".$cinfo["result"].'</a>';
}
$rssfeed = unserialize($row["acontent_form"]);
if($rssfeed['rssurl']) {
	echo ' <a href="'.html_specialchars($rssfeed['rssurl']).'" target="_blank">';
	echo '<img src="img/symbole/xml.gif" width="36" height="14" border="0" style="vertical-align:middle;"></a>';
}
echo "</td><td>&nbsp;</td></tr>";



?>