<?php
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


	$action			= empty($_GET["t"]) ? '' : clean_slweg($_GET["t"]);
	$sendbutton		= ($action == "add") ? $BL['be_gt_button_add'] : $BL['be_gt_button_update'];
	$font_filename	= clean_slweg($_GET["font_filename"]);
	$font_id		= empty($_GET["font_id"]) ? 0 : intval($_GET["font_id"]);
	$font_name		= '';
	$font_shortname	= '';
	
	if (isset($_GET["doit"]) && intval($_GET["doit"]) === 1)
	{
		$font_name = clean_slweg($_POST["font_name"]);
		$font_shortname = clean_slweg($_POST["font_shortname"]);
		$font_filename = clean_slweg($_GET["font_filename"]);
		
		if (!empty($font_shortname) && !empty($font_name))
		{		
			if ($action == "add") {
				$query  = "INSERT INTO ".DB_PREPEND."phpwcms_fonts SET font_name='";
				$query .= aporeplace($font_name)."', font_shortname='";
				$query .= aporeplace($font_shortname)."', font_filename='";
				$query .= aporeplace($font_filename)."'";
			} else {
				$query = "UPDATE ".DB_PREPEND."phpwcms_fonts SET font_name='";
				$query .= aporeplace($font_name)."', font_shortname='";
				$query .= aporeplace($font_shortname)."', font_filename='";
				$query .= aporeplace($font_filename)."' WHERE font_id = ".$font_id;
			}
		
			mysql_query($query, $db) or die ("Graphical Text - Error in query:$query");
			
			header('Location:'.PHPWCMS_URL.'phpwcms.php?do=modules&p=2&s=fonts');
		}
		else 
		{
			echo "<span style=\"color:#FF0000\">".$BL['be_gt_edit_empty_fields']."</span>";
		}
	}

	
	if ($font_id)
	{
		$query = "SELECT * FROM ".DB_PREPEND."phpwcms_fonts WHERE font_id = ".intval($font_id);
		$result = mysql_query($query, $db);
		
		while ($row = mysql_fetch_assoc($result))
		{
			$font_name = $row["font_name"];
			$font_shortname = $row["font_shortname"];
		}
	}
?>

<form action="phpwcms.php?do=modules&amp;p=2&amp;s=fonts&amp;t=<?php echo $action; ?>&amp;font_id=<?php echo $font_id;?>&amp;font_filename=<?php echo rawurlencode($font_filename); ?>&amp;&amp;doit=1" method="post">
	<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
		<tr><td colspan="2" class="title"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		</tr>
		<tr><td colspan="2" class="title"><?php echo $BL['be_gt_font_edit_title']; ?></td></tr>
		<tr><td colspan="2" class="title"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		</tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_font_filename'] ?>:&nbsp;</td>
			<td><?php echo html_specialchars($font_filename) ?></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_font_name'] ?>:&nbsp;</td>
			<td><input name="font_name" type="text" id="font_name" class="f11b" style="width: 350px" value="<?php echo html_specialchars($font_name) ?>" size="50" maxlength="50" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_font_shortname'] ?>:&nbsp;</td>
			<td><input name="font_shortname" type="text" id="font_shortname" class="f11b" style="width: 350px" value="<?php echo html_specialchars($font_shortname) ?>" size="50" maxlength="16" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td class="chatlist"></td>
			<td><input name="Submit" type="submit" class="button10" value="<?php echo $sendbutton ?>" />
			&nbsp;&nbsp;
			<input name="donotsubmit" type="button" class="button10" value="<?php echo $BL['be_gt_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=modules&amp;p=2&amp;s=fonts'" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
		</tr>
		<tr><td colspan="2"><?php subnavback($BL['be_gt_font_back'], "phpwcms.php?do=modules&amp;p=2&amp;s=fonts", 6) ?></td></tr>
	</table>
</form>