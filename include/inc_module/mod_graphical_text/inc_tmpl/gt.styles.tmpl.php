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
	<tr><td colspan="3" class="title"><?php echo $BL['be_gt_styles_title']; ?></td></tr>
	<tr><td colspan="3" class="title"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr><td colspan="3" ><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
	</tr>
	<tr><td colspan="3" ><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr>
		<td><strong><?php echo $BL['be_gt_styles_name'] ?></strong></td>
		<td><strong><?php echo $BL['be_gt_styles_preview'] ?></strong></td>
		<td>&nbsp;</td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr><td colspan="3"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
	</tr>
	<tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<?php
	
		//$gtarray = gt2array($db);
	
		$query = "SELECT * FROM ".DB_PREPEND."phpwcms_fonts_styles ORDER BY style_name";
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
			
			$style_id = $row["style_id"];
			$style_name = $row["style_name"];
			$style_preview = get_gt_by_style(array(1 => $style_name, 2 => $style_name)); //$gtarray, 
			
			echo "<tr $bgcolor>\n";
			echo "  <td valign=\"top\">$style_name</td>\n";
			echo "  <td>$style_preview</td>\n";
			echo "  <td align=\"right\" valign=\"top\">\n";
			echo "<a href=\"phpwcms.php?do=modules&amp;p=2&amp;s=styles&amp;t=update&amp;style_id=$style_id\" title=\"".$BL['be_gt_style_edit']."\"><img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\" border=\"0\" alt=\"\" /></a>";
			echo "<a href=\"phpwcms.php?do=modules&amp;p=2&amp;s=styles&amp;t=delete&amp;style_id=$style_id\" title=\"".$BL['be_gt_style_delete']."\" onclick=\"return confirm('".$BL['be_gt_style_delete_confirm']."');\"><img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\" border=\"0\" alt=\"\" /></a>\n";
			echo "  </td>\n";
			echo "</tr>\n";
		}
	?>
	<tr><td colspan="3"><img src="img/leer.gif" width="1" height="5" alt="" /></td></tr>
	<tr><td colspan="3"><img src="img/lines/l538_70.gif" width="538" height="1" alt="" /></td></tr>
	<tr><td colspan="3"><img src="img/leer.gif" width="1" height="5" alt="" /></td></tr>
	<tr><td class="subnavinactive" colspan="4"><img src="img/leer.gif" width="3" height="12" alt="" /><img src="img/button/add_11x11.gif" width="11" height="11" border="0" alt="" />&nbsp;<a href="phpwcms.php?do=modules&amp;p=2&amp;s=styles&amp;t=add"><?php echo $BL['be_gt_style_add']; ?></a></td></tr>
</table>
