<?php
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------
?>
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
	<tr><td colspan="3" class="title"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr><td colspan="3" class="title"><?php echo $BL['be_gt_colors_title']; ?></td></tr>
	<tr><td colspan="3" class="title"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr><td colspan="3" ><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
	</tr>
	<tr><td colspan="3" ><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr>
		<td><strong><?php echo $BL['be_gt_colors_name'] ?></strong></td>
		<td><strong><?php echo $BL['be_gt_colors_info'] ?></strong></td>
		<td>&nbsp;</td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr><td colspan="3"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<?php
		$query = "SELECT * FROM ".DB_PREPEND."phpwcms_fonts_colors ORDER BY color_value";
		$result = mysql_query($query, $db);
		
		$rowcount = 0;
		
		while ($row = mysql_fetch_assoc($result))
		{
			++$rowcount;
			if (fmod($rowcount, 2) == 0)
			{
				$bgcolor = "bgcolor=\"#F5F9FA\"";
				$bgcolor2 = "bgcolor=\"#E5E9EA\"";
			}
			else
			{
				$bgcolor = "";
				$bgcolor2 = "bgcolor=\"#F8F8F8\"";
			}
			
			$color_id = $row["color_id"];
			$color_name = $row["color_name"];
			$color_value = $row["color_value"];
			
			echo "<tr $bgcolor style=\"height:21px;\">\n<td>";
			echo '<table cellspacing="0" cellpadding="0" border="0" summary=""><tr><td>';
			echo "  <img src=\"img/leer.gif\" width=\"15\" height=\"15\" border=\"1\" alt=\"\" style=\"border:1px 1px 1px 1px solid #000000; background-color:#$color_value;\" />";
			echo '</td><td>&nbsp;'.html_specialchars($color_name).'</td></tr></table>';
			echo "</td>\n  <td class=\"code\">#$color_value</td>\n";
			echo "  <td align=\"right\">\n";
			echo "<a href=\"phpwcms.php?do=modules&amp;p=2&amp;s=colors&amp;t=update&amp;color_id=$color_id\" title=\"".$BL['be_gt_color_edit']."\"><img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\" border=\"0\" alt=\"\" /></a>";
			echo "<a href=\"phpwcms.php?do=modules&amp;p=2&amp;s=colors&amp;t=delete&amp;color_id=$color_id\" title=\"".$BL['be_gt_color_delete']."\" onclick=\"return confirm('".$BL['be_gt_color_delete_confirm']."');\"><img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>\n";
			echo "  </td>\n";
			echo "</tr>\n";
		}
	?>
	<tr><td colspan="3"><img src="img/leer.gif" width="1" height="5" alt="" /></td></tr>
	<tr><td colspan="3"><img src="img/lines/l538_70.gif" width="538" height="1" alt="" /></td></tr>
	<tr><td colspan="3"><img src="img/leer.gif" width="1" height="5" alt="" /></td></tr>
	<tr><td class="subnavinactive" colspan="4"><img src="img/leer.gif" alt="" width="3" height="12" /><img src="img/button/add_11x11.gif" alt="" width="11" height="11" border="0" />&nbsp;<a href="phpwcms.php?do=modules&amp;p=2&amp;s=colors&amp;t=add"><?php echo $BL['be_gt_color_add']; ?></a></td>
	</tr>
</table>
