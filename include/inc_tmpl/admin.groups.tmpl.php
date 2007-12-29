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


//user group


?>
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
<tr><td colspan="3" class="title"><?php echo $BL['be_subnav_admin_groups'] ?></td></tr>
<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<?php

$row_count = 0;
$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_usergroup WHERE group_trash=0 ORDER BY group_member;";
if($result = mysql_query($sql, $db) or die("error while listing user groups")) {
	
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<tr".( ($row_count % 2) ? " bgcolor=\"#F3F5F8\"" : "" ).">\n<td width=\"28\">"; //#F9FAFB
		echo '<img src="img/symbole/template_list_icon.gif" width="28" height="18"></td>'."\n";
		echo '<td width="470" class="dir"><a href="phpwcms.php?do=admin&amp;p=1&amp;s='.$row["group_id"];
		echo '"><strong>'.html_specialchars($row["group_name"])."</strong>";
		echo "</a></td>\n".'<td width="60" align="right">';
		echo '<a href="phpwcms.php?do=admin&amp;p=1&amp;s='.$row["group_id"];
		echo '"><img src="img/button/edit_22x11.gif" width="22" height="11" border="0"></a>';
		echo '<img src="img/leer.gif" width="2" height="1">';
		echo '<a href="phpwcms.php?do=admin&amp;p=1&amp;s='.$row["group_id"].'&amp;c=1'; // c=1 -> do copy
		echo '" title="copy group"><img src="img/button/copy_11x11_0.gif" width="11" height="11" border="0"></a>';
		echo '<img src="img/leer.gif" width="2" height="1">';
		echo '<a href="include/inc_act/act_usergroup.php?do=2|'.$row["group_id"].'" ';
		echo 'title="delete user group: '.html_specialchars($row["group_name"]).'">';
		echo '<img src="img/button/del_11x11.gif" width="11" height="11" border="0"></a>';
		echo '<img src="img/leer.gif" width="2" height="1">'."</td>\n</tr>\n";
		$row_count++;
	}
	mysql_free_result($result);
}

if(!$row_count) {
	echo '<tr><td colspan="3">'.$BL['be_admin_group_nogroup'];
	echo ': <a href="phpwcms.php?do=admin&amp;p=1&amp;s=0">'.$BL['be_admin_group_add'];
	echo '</a></td></tr>';
}

?>
<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
<tr><td colspan="3"><form action="phpwcms.php?do=admin&amp;p=1&amp;s=0" method="post"><input type="submit" value="<?php echo $BL['be_admin_group_add'] ?>" class="button10" title="<?php echo $BL['be_admin_group_add'] ?>"></form></td></tr>
</table>