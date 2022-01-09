<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

//Create Listing for Site Structure
function struct_list($struct_id, $counter=0) {

	$counter++;

	$result = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_articlecat WHERE acat_struct=".intval($struct_id)." ORDER BY acat_sort");

	if(isset($result[0]['acat_id'])) {

		foreach($result as $row) {

			echo "<tr><td class=\"nowrap\"><img src=\"img/leer.gif\" width=\"15\" height=\"11\"></td>";
			echo "<td class=\"dir\">".html($row["acat_name"])."</td>";
			echo "<td><img src=\"img/button/add_22x11.gif\" width=\"22\" height=\"11\">";
			echo "<img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\">";
			echo "<img src=\"img/button/sort_0_1.gif\" width=\"11\" height=\"11\">";
			echo "<img src=\"img/button/sort_1_1.gif\" width=\"11\" height=\"11\">";
			echo "<img src=\"img/button/sort_2_1.gif\" width=\"11\" height=\"11\">";
			echo "<img src=\"img/button/sort_3_1.gif\" width=\"11\" height=\"11\">";
			echo "<img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\">";
			echo "</td></tr>";

			struct_list($row["acat_name"], $counter);

		}
	}
}
