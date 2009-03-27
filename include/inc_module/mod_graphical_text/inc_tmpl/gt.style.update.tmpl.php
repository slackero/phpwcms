<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

	$gtarray = gt2array($db);
	$action = empty($_GET["t"]) ? '' : clean_slweg($_GET["t"]);
	$sendbutton = ($action == "add") ? $BL['be_gt_button_add'] : $BL['be_gt_button_update'];
	$style_id = empty($_GET["style_id"]) ? 0 : intval($_GET["style_id"]);
	
	$fgtransparency			= 0;
	$bgtransparency 		= 0;
	$style_antialiasing 	= 0;
	$style_fgtransparency	= 0;
	$style_bgtransparency	= 0;
	$style_name				= '';
	$style_format			= 'png';
	$style_font				= 0;
	$style_fgcolor			= '';
	$style_bgcolor			= 0;
	$tr_visibility			= 'visible';
	$style_size				= '10';
	$style_rotation			= 'default';
	
	
	if (isset($_GET["doit"]) && intval($_GET["doit"]) == 1)
	{
		$style_name = clean_slweg($_POST["name"]);
		
		
		if (clean_slweg($_POST["format"]) != "jpg")
		{
			$fgtransparency = empty($_POST["fgtransparency"]) ? 0 : 1;
			$bgtransparency = empty($_POST["bgtransparency"]) ? 0 : 1;
		}
		
		$line_width= empty($_POST["linewidth"]) ? 0 : intval($_POST["linewidth"]);
		
		$style_info  = clean_slweg($_POST["font"]);
		$style_info .= ":";
		$style_info .= clean_slweg($_POST["antialiasing"]);
		$style_info .= ":";
		$style_info .= clean_slweg($_POST["size"]);
		$style_info .= ":";
		$style_info .= clean_slweg($_POST["fgcolor"]);
		$style_info .= ":";
		$style_info .= $fgtransparency;
		$style_info .= ":";
		$style_info .= clean_slweg($_POST["bgcolor"]);
		$style_info .= ":";
		$style_info .= $bgtransparency;
		$style_info .= ":";
		$style_info .= $line_width;
		$style_info .= ":";
		$style_info .= clean_slweg($_POST["format"]);
		
		//add new enhanced settings to font
		$style_info .= ":";
		$style_info .= intval($_POST["start_x"]);
		$style_info .= ":";
		$style_info .= intval($_POST["start_y"]);
		$style_info .= ":";
		$style_info .= intval($_POST["height"]);
		$style_info .= ":";
		$style_info .= clean_slweg($_POST["rotation"]);
		
		if ($action == "add") {
			$query  = "INSERT INTO ".DB_PREPEND."phpwcms_fonts_styles SET style_name = '";
			$query .= aporeplace($style_name)."', style_info = '".aporeplace($style_info)."'";
		} else {
			$query  = "UPDATE ".DB_PREPEND."phpwcms_fonts_styles SET style_name = '";
			$query .= aporeplace($style_name)."', style_info = '".aporeplace($style_info)."' WHERE style_id = ".$style_id;
		}
		
		$result = mysql_query($query, $db);
		
		header('Location:'.PHPWCMS_URL.'phpwcms.php?do=modules&p=2&s=styles');
	}

	
	if (!empty($style_id))
	{
		$style = $gtarray["styles_id"][$style_id];

		$style_name = $style["name"];
		$style_font = $style["font"];
		$style_antialiasing = $style["antialiasing"];
		$style_size = $style["size"];
		$style_fgcolor = $style["fgcolor"];
		$style_bgcolor = $style["bgcolor"];
		$style_format = $style["format"];
		$style_fgtransparency = $style["fgtransparency"];
		$style_bgtransparency = $style["bgtransparency"];
		$style_line_width = $style["line_width"];
		
		$style_x = $style["start_x"];
		$style_y = $style["start_y"];
		$style_height = $style["height"];
		$style_rotation = $style["rotation"];
		
		if ($style_format != "jpg") {
			$tr_visibility = "visible";
		} else {
			$tr_visibility = "hidden";
		}
	}
?>

<form action="phpwcms.php?do=modules&amp;p=2&amp;s=styles&amp;t=<?php echo $action; ?>&amp;style_id=<?php echo $style_id;?>&amp;doit=1" method="post" name="gt_styles" id="gt_styles">
	<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
		<tr><td colspan="2" class="title"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		</tr>
		<tr><td colspan="2" class="title"><?php echo $BL['be_gt_style_edit_title']; ?></td></tr>
		<tr><td colspan="2" class="title"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
		</tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_style_name'] ?>:&nbsp;</td>
			<td><input name="name" type="text" id="name" class="f11b" style="width: 350px" value="<?php echo html_specialchars($style_name); ?>" size="50" maxlength="16" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_output_format'] ?>:&nbsp;</td>
			<td>
				<select name="format" class="f11b" onchange="toggletransparency()">
					<?php
						$sel_png = "";
						$sel_gif = "";
						$sel_jpg = "";
						
						switch ($style_format)
						{
							case "png": $sel_png='selected="selected"'; break;
							case "jpg": $sel_jpg='selected="selected"'; break;
							case "gif": $sel_gif='selected="selected"'; break;
						}

						if (imagetypes() & IMG_PNG)
   							echo "<option value=\"png\" $sel_png >PNG</option>";
						if (ImageTypes() & IMG_JPG)
						{
   							echo "<option value=\"jpg\" $sel_jpg >JPG</option>";
						}
						if (imagetypes() & IMG_GIF)
   							echo "<option value=\"gif\" $sel_gif >GIF</option>";
					?>
				</select>
			</td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_style_font'] ?>:&nbsp;</td>
			<td>
				<select name="font" class="f11b">
					<option value="0" class="f11b">&nbsp;</option>
					<?php
						foreach($gtarray["fonts_id"] as $key)
						{
							if(intval($key["id"]) == intval($style_font)) {
								echo "<option value=\"".$key["id"]."\" class=\"f11b\" selected=\"selected\">".$key["name"]."</option>\n";
							} else {
								echo "<option value=\"".$key["id"]."\" class=\"f11b\">".$key["name"]."</option>\n";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap" valign="top"><?php echo $BL['be_gt_style_antialiasing']; ?>:&nbsp;</td>
			<td>
				<?php
				if (intval($style_antialiasing) != 0)
				{ ?>
					<input name="antialiasing" type="radio" id="antialiasing_1" value="1" checked="checked" /> <?php echo $BL['be_gt_style_antialiasing_yes']; ?>&nbsp;&nbsp;&nbsp;
					<input name="antialiasing" type="radio" id="antialiasing_0" value="0" /> <?php echo $BL['be_gt_style_antialiasing_no']; ?>
				<?php }
				else
				{ ?>
					<input name="antialiasing" type="radio" id="antialiasing_1" value="1" /> <?php echo $BL['be_gt_style_antialiasing_yes']; ?>&nbsp;&nbsp;&nbsp;
					<input name="antialiasing" type="radio" id="antialiasing_0" value="0" checked="checked" /> <?php echo $BL['be_gt_style_antialiasing_no']; ?>
				<?php } ?>
			</td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_style_size'] ?>:&nbsp;</td>
			<td><input name="size" type="text" id="size" class="f11b" style="width: 50px" value="<?php echo $style_size; ?>" size="10" maxlength="3" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_style_underline'] ?>:&nbsp;</td>
			<td class="chatlist"><input name="linewidth" type="text" id="linewidth" class="f11b" style="width: 50px" value="<?php echo @$style_line_width; ?>" size="10" maxlength="3" />
			 <?php echo $BL['be_gt_style_underline_desc'] ?></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap">X:&nbsp;</td>
			<td class="chatlist"><input name="start_x" type="text" id="start_x" class="f11b" style="width: 50px" value="<?php echo @$style_x; ?>" size="10" maxlength="3" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap">Y:&nbsp;</td>
			<td class="chatlist"><input name="start_y" type="text" id="start_y" class="f11b" style="width: 50px" value="<?php echo @$style_y; ?>" size="10" maxlength="3" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_style_height'] ?>:&nbsp;</td>
			<td class="chatlist"><input name="height" type="text" id="height" class="f11b" style="width: 50px" value="<?php echo @$style_height; ?>" size="10" maxlength="3" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_style_fgcolor']; ?>:&nbsp;</td>
			<td class="chatlist">
				<select name="fgcolor" class="f11b">
					<option value="0" class="f11b">&nbsp;</option>
					<?php
						foreach($gtarray["colors_id"] as $key)
						{
							if ($key["value"] > "EEEEEE") {
								$option_bgcolor = "#555555";
							} else {
								$option_bgcolor = "#FFFFFF";
							}
							
							if($key["id"] == $style_fgcolor) {
								echo "<option value=\"".$key["id"]."\"  class=\"f11b\" style=\"color:#".$key["value"].";background-color:$option_bgcolor\" selected=\"selected\">".$key["name"]."</option>\n";
							} else {
								echo "<option value=\"".$key["id"]."\"  class=\"f11b\" style=\"color:#".$key["value"].";background-color:$option_bgcolor\">".$key["name"]."</option>\n";
							}
						}
					?>
				</select> <span id="fgt" class="<?php echo $tr_visibility; ?>">
				<?php
					
					echo '<input type="checkbox" name="fgtransparency" id="fgtransparency" value="1"'.is_checked(1, $style_fgtransparency, 1, 0).' />';
					
					echo $BL['be_gt_style_transparency'];
				?></span>			</td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_style_bgcolor']; ?>:&nbsp;</td>
			<td class="chatlist">
				<select name="bgcolor" class="f11b">
					<option value="0" class="f11b">&nbsp;</option>
					<?php
						foreach($gtarray["colors_id"] as $key)
						{
							if ($key["value"] > "EEEEEE") {
								$option_bgcolor = "#555555";
							} else {
								$option_bgcolor = "#FFFFFF";
							}
								
							if(intval($key["id"]) == intval($style_bgcolor)) {
								echo "<option value=\"".$key["id"]."\"  class=\"f11b\" style=\"color:#".$key["value"].";background-color:$option_bgcolor\" selected=\"selected\">".$key["name"]."</option>\n";
							} else {
								echo "<option value=\"".$key["id"]."\"  class=\"f11b\" style=\"color:#".$key["value"].";background-color:$option_bgcolor\">".$key["name"]."</option>\n";
							}
						}
					?>
				</select> <span id="bgt" class="<?php echo $tr_visibility; ?>">
				<?php
					echo '<input type="checkbox" name="bgtransparency" id="bgtransparency" value="1"'.is_checked(1, $style_bgtransparency, 1, 0).' />';
					echo $BL['be_gt_style_transparency'];
				?></span>			</td>
		</tr>
		
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>
		<tr>
			<td align="right" class="chatlist" nowrap="nowrap"><?php echo $BL['be_gt_style_rotation']; ?>:&nbsp;</td>
			<td class="chatlist"><select name="rotation" class="f11b">
					
					<option value="default"<?php is_selected('default', $style_rotation) ?>><?php echo html_entities($BL['be_gt_style_rotation_default']); ?></option>
					<option value="cw"<?php is_selected('cw', $style_rotation) ?>><?php echo html_entities($BL['be_gt_style_rotation_cw']); ?></option>
					<option value="hcw"<?php is_selected('hcw', $style_rotation) ?>><?php echo html_entities($BL['be_gt_style_rotation_hcw']); ?></option>
					<option value="ccw"<?php is_selected('ccw', $style_rotation) ?>><?php echo html_entities($BL['be_gt_style_rotation_ccw']); ?></option>
					
				</select></td>
		</tr>
		
		
		<tr><td colspan="2"><img src="img/leer.gif" width="1" height="10" alt="" /></td></tr>
		<tr>
			<td class="chatlist"></td>
			<td><input name="Submit" type="submit" class="button10" value="<?php echo $sendbutton ?>" />
			&nbsp;&nbsp;
			<input name="donotsubmit" type="button" class="button10" value="<?php echo $BL['be_gt_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=modules&amp;p=2&amp;s=styles'" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
		</tr>
		<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
		</tr>
		<tr><td colspan="2"><?php subnavback($BL['be_gt_style_back'], "phpwcms.php?do=modules&amp;p=2&amp;s=styles", 6) ?></td></tr>
	</table>
</form>