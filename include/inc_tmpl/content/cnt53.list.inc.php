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


// Forum

$cinfo = array();
$cinfo[0] = html_specialchars(cut_string($row["acontent_title"],'&#8230;', 55));
$cinfo[1] = html_specialchars(cut_string($row["acontent_subtitle"],'&#8230;', 55));

$forum = unserialize($row["acontent_form"]);
$forum_selection = '';
$forum_selection = trim(implode(' OR forum_id=', $forum['selection']));

if($forum_selection != '') {
	$forum_selection = 'AND (forum_id='.$forum_selection.')';
	$sql_f = "SELECT * FROM ".DB_PREPEND."phpwcms_forum WHERE forum_entry=0 AND forum_deleted=0 ".$forum_selection." ORDER BY forum_title;";
	
	$forum_selection = '';
	if($result_f = mysql_query($sql_f, $db) or die("error while listing forums")) {
		while($row_f = mysql_fetch_assoc($result_f)) {
			$forum_selection .= '<li>'.html_specialchars($row_f["forum_title"])."</li>\n";
		}
		mysql_free_result($result_f);
		if($forum_selection != '') {
			$forum_selection = '<ul>'.$forum_selection.'</ul>';
		}
	}
}

$cinfo = str_replace("\n", " / ", trim(implode("\n", $cinfo)));
if($cinfo || $forum_selection) { //Zeige Inhaltinfo
	echo "<tr><td>&nbsp;</td><td class=\"v10\">";
	echo "<a href=\"phpwcms.php?do=articles&p=2&s=1&aktion=2&id=".$article["article_id"]."&acid=".$row["acontent_id"]."\">";
	echo $cinfo.$forum_selection."</a></td><td>&nbsp;</td></tr>";
}

unset($forum, $forum_selection);

?>