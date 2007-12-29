<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


	$action = clean_slweg($_GET["t"]);
	$sendbutton = ($action == "add") ? $BL['be_gt_button_add'] : $BL['be_gt_button_update'];
	$color_id = empty($_GET["color_id"]) ? 0 : intval($_GET["color_id"]);
	$color_name = empty($_POST["color_name"]) ? '' : clean_slweg($_POST["color_name"]);
	$color_info = empty($_POST["color_info"]) ? '' : strtoupper(clean_slweg($_POST["color_info"]));
	
	if (isset($_GET["doit"]) && intval($_GET["doit"]) == 1)
	{
		if (!empty($color_name) && !empty($color_info))
		{		
			if (preg_match('/^[0-9a-fA-F]{6}$/', $color_info) == 1)
			{
				if ($action == "add") {
					$query  = "INSERT INTO ".DB_PREPEND."phpwcms_fonts_colors SET color_name='";
					$query .= aporeplace($color_name)."', color_value='".aporeplace($color_info)."'";
				} else {
					$query  = "UPDATE ".DB_PREPEND."phpwcms_fonts_colors SET color_name='";
					$query .= aporeplace($color_name)."', color_value='".aporeplace($color_info)."' WHERE color_id = ".$color_id." LIMIT 1";
				}
			
				mysql_query($query, $db) or die ("Graphical Text - Error in query:$query");
				
				header("Location:phpwcms.php?do=modules&p=2&s=colors");
			}
			else
				echo "<span style=\"color:#FF0000\">".$BL['be_gt_only_six_numbers']."</span>";
		}
		else 
			echo "<span style=\"color:#FF0000\">".$BL['be_gt_edit_empty_fields']."</span>";
	}

	
	if (!empty($color_id))
	{
		$query = "SELECT * FROM ".DB_PREPEND."phpwcms_fonts_colors WHERE color_id = ".$color_id;
		$result = mysql_query($query, $db);
		
		while ($row = mysql_fetch_assoc($result))
		{
			$color_name = $row["color_name"];
			$color_info = $row["color_info"];
		}
	}
?>

<form action="phpwcms.php?do=modules&amp;p=2&amp;s=colors&amp;t=<?php echo $action; ?>&amp;color_id=<?php echo $color_id;?>&amp;doit=1" method="post">
	<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
		<tr><td colspan="2" class="title"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		</tr>
		<tr><td colspan="2" class="title"><?php echo $BL['be_gt_color_edit_title']; ?></td></tr>
		<tr><td colspan="2" class="title"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		</tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_color_name'] ?>:&nbsp;</td>
			<td><input name="color_name" type="text" id="font_name" class="f11b" style="width: 350px" value="<?php echo html_specialchars($color_name); ?>" size="50" maxlength="16" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_color_info'] ?>:&nbsp;</td>
			<td>#
			<input name="color_info" type="text" id="font_shortname" class="f11b" style="width: 100px" value="<?php echo $color_info; ?>" size="10" maxlength="6" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td class="chatlist"></td>
			<td><input name="Submit" type="submit" class="button10" value="<?php echo $sendbutton ?>" />
			&nbsp;&nbsp;
			<input name="donotsubmit" type="button" class="button10" value="<?php echo $BL['be_gt_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=modules&amp;p=2&amp;s=colors'" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
		</tr>
		<tr><td colspan="2"><?php subnavback($BL['be_gt_color_back'], "phpwcms.php?do=modules&amp;p=2&amp;s=colors", 6) ?></td></tr>
	</table>
</form>