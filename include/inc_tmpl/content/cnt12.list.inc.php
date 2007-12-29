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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Newsletter Subscription

$cinfo[1] = html_specialchars(cut_string($row["acontent_title"],'&#8230;', 55));
							 $cinfo[2] = html_specialchars(cut_string($row["acontent_subtitle"],'&#8230;', 55));
							 $cnewsletter = unserialize($row["acontent_newsletter"]);
							 $cinfo[3] = str_replace("\n", " ", cut_string($cnewsletter["text"],'&#8230;', 150));	
							 $cinfo["result"] = "";
				
							 foreach($cinfo as $value) {
								 if($value) $cinfo["result"] .= $value."\n";
							 }
							 $cinfo["result"] = str_replace("\n", " / ", chop($cinfo["result"]));
							 if($cinfo["result"]) { //Zeige Inhaltinfo
								 echo "<tr><td>&nbsp;</td><td class=\"v10\">";
								 echo "<a href=\"phpwcms.php?do=articles&p=2&s=1&aktion=2&id=".$article["article_id"]."&acid=".$row["acontent_id"]."\">";
								 echo $cinfo["result"]."</a></td><td>&nbsp;</td></tr>";
							 }

?>