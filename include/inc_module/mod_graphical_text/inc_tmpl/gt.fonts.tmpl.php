<?php
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


	// Array mit allen bisher in der DB eingetragenen Fonts erstellen
	$query = "SELECT * FROM ".DB_PREPEND."phpwcms_fonts";
	$result = mysql_query($query, $db) or die ("Graphical Text MOD - Error in query: $query");
	
	$fontcount = 0;
	while ($row = mysql_fetch_assoc($result))
	{
		++$fontcount;
		$font_array[$fontcount]["font_id"] = $row["font_id"];
		$font_array[$fontcount]["font_name"] = $row["font_name"];
		$font_array[$fontcount]["font_shortname"] = $row["font_shortname"];
		$font_array[$fontcount]["font_filename"] = $row["font_filename"];
	}
?>
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
	<tr><td colspan="4" class="title"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr><td colspan="4" class="title"><?php echo $BL['be_gt_fonts_title']; ?></td></tr>
	<tr><td colspan="4" class="title"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr><td colspan="4" ><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
	</tr>
	<tr><td colspan="4" ><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr>
		<td><strong><?php echo $BL['be_gt_font_name'] ?></strong></td>
		<td><strong><?php echo $BL['be_gt_font_shortname'] ?></strong></td>
		<td><strong><?php echo $BL['be_gt_font_filename'] ?></strong></td>
		<td>&nbsp;</td>
	</tr>
	<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	<tr><td colspan="4"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
	</tr>
	<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
	</tr>
	
<?php
		$font_dir = PHPWCMS_ROOT."/include/inc_module/mod_graphical_text/inc_fonts/";
		
		// Directory-Handle geifen
		$handle = opendir($font_dir);
		
		$i = 0;
		
		while (false !== ($file = readdir($handle)))	// von php.net abgeguckt
		{
			$font_ext = which_ext($file);
			
			if(strpos($file, '.') === 0 || ($font_ext != 'ttf' && $font_ext != 'otf')) {
				continue;	
			}

			if (fmod($i, 2) == 0)
			{
				$bgcolor = "F5F9FA";
				$bgcolor2 = "E5E9EA";
			}
			else
			{
				$bgcolor = "FFFFFF";
				$bgcolor2 = "F8F8F8";
			}
			
			// Den aktuellen Wert mit dem Array vergleichen. Wenn der Wert im Array ist, dann ist 
			// der Font schon erfasst und darf normal angezeigt werden. Ansonsten wird er rot 
			// dargestellt (oder so).
			
			$found = false;
			
			if (!empty($font_array))
			{
				foreach ($font_array as $font)
				{
					if ($font["font_filename"] == $file)
					{
						$found = true;
						$font_id = $font["font_id"];
						$font_name = $font["font_name"];
						$font_shortname = $font["font_shortname"];
					}
				}
			}

			//if(0)
			//{
				$font_filename = $file;
			
				echo "<tr style=\"background: #$bgcolor;\">\n";
				if ($found == false)
				{
					echo "  <td valign=\"top\"><span style=\"color:#FF0000\">".$BL["be_gt_font_not_yet_added"]."</span></td>\n";
					echo "  <td valign=\"top\">&nbsp;</td>\n";
					echo "  <td valign=\"top\">".html_specialchars($file)."</td>\n";
					echo "  <td  valign=\"top\" align=\"right\"><a href=\"phpwcms.php?do=modules&amp;p=2&amp;s=fonts&amp;t=add&amp;font_filename=".rawurlencode($font_filename)."\" title=\"".$BL['be_gt_font_add']."\">";
					echo "<img src=\"include/inc_module/mod_graphical_text/img/button/add_22x11.gif\" width=\"22\" height=\"11\" border=\"0\" alt=\"\" /></a></td>\n";
				}
				else
				{
					echo "  <td valign=\"top\">".html_specialchars($font_name)."<br />";
					echo show_picture($file, $font_name, "1", "12", "#111111", "0", $bgcolor, "0", "0", "png") ."</td>\n";
					echo "  <td valign=\"top\">".html_specialchars($font_shortname)."</td>\n";
					echo "  <td valign=\"top\">".html_specialchars($file)."</td>\n";
					echo "  <td  valign=\"top\" align=\"right\"><a href=\"phpwcms.php?do=modules&amp;p=2&amp;s=fonts&amp;t=update&amp;font_filename=".rawurlencode($font_filename)."&amp;font_id=$font_id\" title=\"".$BL['be_gt_font_edit']."\"><img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\" border=\"0\" alt=\"\" /></a></td>\n";
				}
				echo "</tr>\n";
				echo "<tr bgcolor=\"#AAAAAA\"><td colspan=\"4\"><img src=\"img/leer.gif\" width=\"1\" height=\"1\" alt=\"\" /></td></tr>\n";
			//}
			++$i;
			$found = false;
			$font_id = "";
			$font_name = "";
			$font_shortname = "";
		}
?>	
	<tr><td colspan="4"><img src="img/leer.gif" width="1" height="5" alt="" /></td></tr>
	<tr><td colspan="4"><img src="img/lines/l538_70.gif" width="538" height="1" alt="" /></td></tr>
</table>