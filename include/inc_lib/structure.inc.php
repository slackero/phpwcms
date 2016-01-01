<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2016, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

function struct_list ($struct_id, $dbcon, $counter=0) {
	//Create Listing for Site Structure
	$struct_id = intval($struct_id);
	$counter++;
	$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_struct=".$struct_id." ORDER BY acat_sort;";
	if($result = mysql_query($sql, $dbcon)) {
		while($row = mysql_fetch_array($result)) {
			echo "<tr>\n<td nowrap=\"nowrap\"><img src=\"img/leer.gif\" width=\"15\" height=\"11\"></td>\n";
			echo "<td class=\"dir\">".html($row["acat_name"])."</td>\n";
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