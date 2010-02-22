<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

function struct_list ($struct_id, $dbcon, $counter=0) {
	//Create Listing for Site Structure
	$struct_id = intval($struct_id);
	$counter++;
	$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_struct=".$struct_id." ORDER BY acat_sort;";
	if($result = mysql_query($sql, $dbcon)) {
		while($row = mysql_fetch_array($result)) {
			echo "<tr>\n<td nowrap=\"nowrap\"><img src=\"img/leer.gif\" width=\"15\" height=\"11\"></td>\n";
			echo "<td class=\"dir\">".html_specialchars($row["acat_name"])."</td>\n";
			echo "<td><img src=\"img/button/add_11x11.gif\" width=\"11\" height=\"11\">";
			echo "<img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\">";
			echo "<img src=\"img/button/sort_0_1.gif\" width=\"11\" height=\"11\">";
			echo "<img src=\"img/button/sort_1_1.gif\" width=\"11\" height=\"11\">";
			echo "<img src=\"img/button/sort_2_1.gif\" width=\"11\" height=\"11\">";
			echo "<img src=\"img/button/sort_3_1.gif\" width=\"11\" height=\"11\">";
			echo "<img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\">";
			echo "</td>\n</tr>\n";
			struct_list ($row["acat_name"], $dbcon, $counter);
		}
		//mysql_free_result($result);
	}
}

?>