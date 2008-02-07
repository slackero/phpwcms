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


//Baut das Level Struktur Auswahlmenü
function struct_select_menu($dbcon, $counter=0, $struct_id=0, $selected_id=0) {

	$struct_id		= intval($struct_id);
	$selected_id	= intval($selected_id);
	$counter		= intval($counter) + 1;
					
	$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_trash=0 AND acat_struct=".$struct_id." ORDER BY acat_sort;";
	if($result = mysql_query($sql, $dbcon) or die ("error while building struct select menu (ID:".$struct_id)) {
		$sx=0;
		while($row = mysql_fetch_assoc($result)) {
			$struct[$sx] = $row;
			$sx++;
		}
		mysql_free_result($result);
	}
	if(isset($struct[0])) {
		foreach($struct as $key => $value) {
			echo "<option value=\"".$struct[$key]["acat_id"]."\"";
			echo ( ($selected_id==$struct[$key]["acat_id"]) ? " selected" : "" ).">";
			echo str_repeat("&#8212;", $counter).html_specialchars($struct[$key]["acat_name"]);
			echo "</option>\n";
			struct_select_menu($dbcon, $counter, $struct[$key]["acat_id"], $selected_id);
		}
	}
}

function change_articledate($article_id=0) {
	// update article date when content part was changed
	$article_id = intval($article_id);
	if($article_id) {
		$sql  = "UPDATE ".DB_PREPEND."phpwcms_article SET ";
		$sql .= "article_tstamp = NOW() WHERE article_id = '".$article_id."' LIMIT 1 ;";
		mysql_query($sql, $GLOBALS['db']);
	}
}

function struct_select_list($counter=0, $struct_id=0, & $selected_id) {

	global $db;

	$struct_id		= intval($struct_id);
	$counter		= intval($counter) + 1;
					
	$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_trash=0 AND acat_struct=".$struct_id." ORDER BY acat_sort;";
	if($result = mysql_query($sql, $db) or die ("error while building struct select menu (ID:".$struct_id)) {
		$sx=0;
		while($row = mysql_fetch_assoc($result)) {
			$struct[$sx] = $row;
			$sx++;
		}
		mysql_free_result($result);
	}
	if(isset($struct[0])) {
		foreach($struct as $key => $value) {
			echo '<option value="'.$struct[$key]["acat_id"].'"';
			if(in_array($struct[$key]["acat_id"], $selected_id)) {
				echo ' selected';
			}
			echo '>'.str_repeat("&#8212;", $counter).' '.html_specialchars($struct[$key]["acat_name"]);
			echo '</option>'.LF;
			struct_select_list($counter, $struct[$key]["acat_id"], $selected_id);
		}
	}
}

?>