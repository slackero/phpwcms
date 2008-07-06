<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// Flash Media Player

if( ! $content["id"] ) {
	
	include(PHPWCMS_ROOT.'/include/inc_lib/content/cnt25.takeval.inc.php');
}


?>
<tr><td colspan="2" class="rowspacer0x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td colspan="2" style="padding:0 10px 0 10px;background-color:#C2EB9A;">
	<p><strong>Licensing JW Media Player</strong></p>
	<p>
	<a href="http://www.jeroenwijering.com/?item=JW_Media_Player" target="_blank"><strong>The player itself</strong></a> 
	(used as as plugin inside of <strong>phpwcms</strong>) is licensed under a 
	<a href="http://creativecommons.org/licenses/by-nc-sa/2.0/" target="_blank"><strong>Creative Commons License</strong></a>.
	<strong style="color:#cc3300">It allows you to use and modify the script for noncommercial purposes.</strong>
	For commercial use, the author Jeroen Wijering distribute licenses of the script for <strong>15 EUR</strong>. 
	For more info and instant ordering, please 
	<a href="http://www.jeroenwijering.com/?order=form" target="_blank"><strong>advance to his online order page</strong></a>!
	<strong>Please - respect copyrights!</strong>
	</p>
	</td>
</tr>

<tr><td colspan="2" class="rowspacer0x10"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<!-- Template selection -->
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="" class="width440">
		<tr>
			<td><select name="fmp_template" id="fmp_template" class="f11b width150">
<?php
	
	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for Flash Media Player
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/flashplayer');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		$selected_val = (isset($fmp_data['fmp_template']) && $val == $fmp_data['fmp_template']) ? ' selected="selected"' : '';
		$val = html_specialchars($val);
		echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
	}
}

?>				  
			</select></td>
		
			<td width="20%">&nbsp;</td>
		
			<td><select name="fmp_width_height" id="fmp_width_height" class="v11" onchange="setPlayerSize(this)">
				
				<option><?php echo $BL['be_flashplayer_selectsize'] ?></option>
				<option value="200x178">200 x 178 px</option>
				<option value="320x240">320 x 240 px</option>
				<option value="380x313">380 x 313 px</option>
				<option value="425x350">425 x 350 px</option>
				<option value="450x338">450 x 338 px</option>
				<option value="500x403">500 x 403 px</option>
				<option value="640x480">640 x 480 px</option>			

			</select></td>
			
			<td>&nbsp;&nbsp;</td>			
		
			<td><input name="fmp_width" type="text" class="f11b width30" id="fmp_width" size="4" maxlength="4" value="<?php echo $fmp_data['fmp_width']  ?>" /></td>
			<td class="chatlist">&nbsp;x&nbsp;</td>

			<td><input name="fmp_height" type="text" class="f11b width30" id="fmp_height" size="4" maxlength="4" value="<?php echo $fmp_data['fmp_height']  ?>" /></td>
			<td class="chatlist">&nbsp;px</td>
		
		</tr>
	</table></td>
		
</tr>


<tr><td colspan="2" class="rowspacer10x10"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>



<!-- internal/external selection -->
<tr>
	<td align="right" class="chatlist tdtop6">&nbsp;<br /><?php echo $BL['be_cnt_source'] ?>:&nbsp;</td>

	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td colspan="3">&nbsp;</td>
			<td colspan="3" class="v10 tdbottom3 greyed">&nbsp;<i>MP3, FLV, SWF, JPG, PNG, GIF</i></td>
		</tr>
	
		<tr>
			<td bgcolor="#E7E8EB"><input name="fmp_int_ext" id="fmp_int_ext0" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext']); ?> /></td>
			<td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext0"><?php echo $BL['be_cnt_internal'] ?>&nbsp;</label></td>
			<td>&nbsp;<input name="fmp_internal_id" type="hidden" id="fmp_internal_id" value="<?php echo $fmp_data['fmp_internal_id'] ?>" /></td>
			<td><input name="fmp_internal_name" type="text" id="fmp_internal_name" class="f11b width300 greyed" value="<?php echo html_specialchars($fmp_data['fmp_internal_name']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=6');" /></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=6');return false;"><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name').value='';getObjectById('fmp_internal_id').value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" border="0" /></a></td>
		</tr>
		<tr>
			<td colspan="6"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
		</tr>
		<tr>
			<td bgcolor="#E7E8EB"><input name="fmp_int_ext" id="fmp_int_ext1" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext']); ?> /></td>
			<td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext1"><?php echo $BL['be_cnt_external'] ?>&nbsp;</label></td>
			<td>&nbsp;</td>
			<td colspan="3"><input name="fmp_external_file" type="text" id="fmp_external_file" class="f11b width300" value="<?php echo html_specialchars($fmp_data['fmp_external_file']) ?>" size="40" /></td>
		</tr>
	</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

<tr>
	<td align="right" class="chatlist tdtop3"><?php echo $BL['be_flashplayer_caption'] ?>:&nbsp;</td>
	<td><textarea name="fmp_caption" cols="40" rows="2" wrap="off" class="v11 width440" id="fmp_caption"><?php echo html_specialchars($fmp_data['fmp_caption']) ?></textarea></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>

<tr>
	<td align="right" class="chatlist tdtop3"><?php echo $BL['be_admin_page_link'] ?>:&nbsp;</td>
	<td><input name="fmp_link" type="text" id="fmp_link" class="v11 width440" value="<?php echo html_specialchars($fmp_data['fmp_link']) ?>" size="40" /></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>


<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_flashplayer_thumbnail'] ?>:&nbsp;</td>

	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
	
		<tr>
			<td><input name="fmp_img_name" type="text" id="fmp_img_name" class="f11b width300 greyed" value="<?php echo html_specialchars($fmp_data['fmp_img_name']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=7');" /></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=7');return false;"><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_img_name').value='';getObjectById('fmp_img_id').value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" border="0" /></a>
			<input name="fmp_img_id" type="hidden" id="fmp_img_id" value="<?php echo $fmp_data['fmp_img_id'] ?>" />			
			</td>
		</tr>
		
	</table></td>
</tr>


<tr><td colspan="2" class="rowspacer10x10"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>


<tr>
	<td align="right" class="chatlist tdtop3"><?php echo $BL['be_settings'] ?>:&nbsp;</td>

	<td><table border="0" cellpadding="0" cellspacing="0" summary="" class="settings">
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_autostart">Autostart:&nbsp;</label></td>
			<td><input type="checkbox" name="fmp_set_autostart" id="fmp_set_autostart" value="1"<?php is_checked(1, $fmp_data['fmp_set_autostart']) ?> /></td>
		</tr>
		<!--
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_autohidecontrol">Autohide control bar:&nbsp;</label></td>
			<td><input type="checkbox" name="fmp_set_autohidecontrol" id="fmp_set_autohidecontrol" value="1"<?php is_checked(1, $fmp_data['fmp_set_autohidecontrol']) ?> /></td>
		</tr>
		//-->
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_overstretch">Display:&nbsp;</label></td>
			<td><select name="fmp_set_overstretch" id="fmp_set_overstretch" class="v11">
			
			<option value="default"<?php is_selected('default', $fmp_data['fmp_set_overstretch']) ?>>Default</option>
			<option value="fit"<?php is_selected('fit', $fmp_data['fmp_set_overstretch']) ?>>Stretch disproportionally</option>
			<option value="none"<?php is_selected('none', $fmp_data['fmp_set_overstretch']) ?>>Show in original dimensions</option>
			<option value="false"<?php is_selected('false', $fmp_data['fmp_set_overstretch']) ?>>Stretch to fit</option>
			<option value="true"<?php is_selected('true', $fmp_data['fmp_set_overstretch']) ?>>Stretch proportionally</option>

			</select></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_showcontrols">Show controls:&nbsp;</label></td>
			<td><input type="checkbox" name="fmp_set_showcontrols" id="fmp_set_showcontrols" value="1"<?php is_checked(1, $fmp_data['fmp_set_showcontrols']) ?> /></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_largecontrols">Large controls:&nbsp;</label></td>
			<td><input type="checkbox" name="fmp_set_largecontrols" id="fmp_set_largecontrols" value="1"<?php is_checked(1, $fmp_data['fmp_set_largecontrols']) ?> /></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_showdigits">Show digits:&nbsp;</label></td>
			<td><input type="checkbox" name="fmp_set_showdigits" id="fmp_set_showdigits" value="1"<?php is_checked(1, $fmp_data['fmp_set_showdigits']) ?> /></td>
		</tr>

		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_showeq">Show equalizer:&nbsp;</label></td>
			<td><input type="checkbox" name="fmp_set_showeq" id="fmp_set_showeq" value="1"<?php is_checked(1, $fmp_data['fmp_set_showeq']) ?> /></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_showvolume">Show volume:&nbsp;</label></td>
			<td><input type="checkbox" name="fmp_set_showvolume" id="fmp_set_showvolume" value="1"<?php is_checked(1, $fmp_data['fmp_set_showvolume']) ?> /></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_showdownload">Show download button:&nbsp;</label></td>
			<td><input type="checkbox" name="fmp_set_showdownload" id="fmp_set_showdownload" value="1"<?php is_checked(1, $fmp_data['fmp_set_showdownload']) ?> /></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_bgcolor">Background color (#FFFFFF):&nbsp;</label></td>
			<td><input name="fmp_set_bgcolor" type="text" id="fmp_set_bgcolor" class="v11 width75" value="<?php echo html_specialchars($fmp_data['fmp_set_bgcolor']) ?>" size="40" maxlength="7" /></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_color">Front color (#000000):&nbsp;</label></td>
			<td><input name="fmp_set_color" type="text" id="fmp_set_color" class="v11 width75" value="<?php echo html_specialchars($fmp_data['fmp_set_color']) ?>" size="40" maxlength="7" /></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_hcolor">Highlight color (#000000):&nbsp;</label></td>
			<td><input name="fmp_set_hcolor" type="text" id="fmp_set_hcolor" class="v11 width75" value="<?php echo html_specialchars($fmp_data['fmp_set_hcolor']) ?>" size="40" maxlength="7" /></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_logo">Watermark logo (path):&nbsp;</label></td>
			<td><input name="fmp_set_logo" type="text" id="fmp_set_logo" class="v11 width250" value="<?php echo html_specialchars($fmp_data['fmp_set_logo']) ?>" size="40" /></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_flashversion">Flash version (7 >= only):&nbsp;</label></td>
			<td><input name="fmp_set_flashversion" type="text" id="fmp_set_flashversion" class="v11 width75" value="<?php echo html_specialchars($fmp_data['fmp_set_flashversion']) ?>" size="40" maxlength="10" /></td>
		</tr>

		
	</table></td>
</tr>



<tr>
	<td colspan="2" class="rowspacer7x0"><script type="text/javascript">
		<!--
		function setIdName(file_id, file_name) {
			if(file_id == null) file_id=0;
			if(file_name == null) file_name='';
			getObjectById('fmp_internal_id').value = file_id;
			getObjectById('fmp_internal_name').value = file_name;
		}
		function setImgIdName(file_id, file_name) {
			if(file_id == null) file_id=0;
			if(file_name == null) file_name='';
			getObjectById('fmp_img_id').value = file_id;
			getObjectById('fmp_img_name').value = file_name;
		}
		function setPlayerSize(sval) {
			var indx = sval.selectedIndex;
			if(indx > 0) {
				var val = sval.options[indx].value.split('x');
				getObjectById('fmp_width').value = parseInt(val[0]);
				getObjectById('fmp_height').value = parseInt(val[1]);
			}
			sval.options[0].selected = true;
			sval.blur();
		}
		//-->
		</script></td>
</tr>