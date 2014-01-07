<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Flash Media Player

initMootools('1.2'); // We use MooTools here

if( ! $content["id"] ) {
	include(PHPWCMS_ROOT.'/include/inc_lib/content/cnt25.takeval.inc.php');
}

if(empty($fmp_data['fmp_set_skin'])) {
	$fmp_data['fmp_set_skin'] = 'default';
}
if(!isset($fmp_data['fmp_set_skin_html5'])) {
	$fmp_data['fmp_set_skin_html5'] = '';
}
if(!isset($fmp_data['fmp_int_ext_h264'])) {
	// H.264
	$fmp_data['fmp_int_ext_h264']		= 0;
	$fmp_data['fmp_internal_id_h264']	= 0;
	$fmp_data['fmp_internal_name_h264']	= '';
	$fmp_data['fmp_external_file_h264']	= '';
		
	// WebM
	$fmp_data['fmp_int_ext_webm']		= 0;
	$fmp_data['fmp_internal_id_webm']	= 0;
	$fmp_data['fmp_internal_name_webm']	= '';
	$fmp_data['fmp_external_file_webm']	= '';
		
	// Ogg
	$fmp_data['fmp_int_ext_ogg']		= 0;
	$fmp_data['fmp_internal_id_ogg']	= 0;
	$fmp_data['fmp_internal_name_ogg']	= '';
	$fmp_data['fmp_external_file_ogg']	= '';	
}

$fmp_data['fmp_player'] = empty($fmp_data['fmp_player']) ? 0 : 1;


?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="" class="width440">
		<tr>
			<td><select name="fmp_template" id="fmp_template" class="width150">
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
		
			<td><select name="fmp_width_height" id="fmp_width_height" onchange="setPlayerSize(this)">
				
				<option><?php echo $BL['be_flashplayer_selectsize'] ?></option>
				<option value="200x178">200 x 178 px</option>
				<option value="320x240">320 x 240 px</option>
				<option value="380x313">380 x 313 px</option>
				<option value="425x350">425 x 350 px</option>
				<option value="450x338">450 x 338 px</option>
				<option value="500x403">500 x 403 px</option>
				<option value="640x480">640 x 264 px</option>
				<option value="640x480">640 x 480 px</option>

				<option value="720x480">HD 720 x 480 px</option>
				<option value="852x480">HD 852 x 480 px</option>
				<option value="1280x720">HD 1.280 x 720 px</option>
				<option value="1920x1080">HD 1.920 x 1.080 px</option>

			</select></td>
			
			<td>&nbsp;&nbsp;</td>			
		
			<td><input name="fmp_width" type="text" class="width30" id="fmp_width" size="4" maxlength="4" value="<?php echo $fmp_data['fmp_width']  ?>" /></td>
			<td class="chatlist">&nbsp;x&nbsp;</td>

			<td><input name="fmp_height" type="text" class="width30" id="fmp_height" size="4" maxlength="4" value="<?php echo $fmp_data['fmp_height']  ?>" /></td>
			<td class="chatlist">&nbsp;px</td>
		
		</tr>
	</table></td>
		
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist" valign="top"><strong><?php echo $BL['be_html5_media'] ?></strong>:&nbsp;</td>

	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
	
		<!-- HTML5 Media H.264 -->
		<tr>
			<td colspan="3" class="chatlist tdbottom3" align="right"><?php echo $BL['be_media_format'] ?>&nbsp;&nbsp;</td>
			<td colspan="3" class="v10 tdbottom3 greyed">&nbsp;<?php echo $BL['be_html5_h264'] ?> &#8212; <i>mp4, m4v, mov, m4p, m4a</i></td>
		</tr>
		<tr>
			<td bgcolor="#E7E8EB"><input name="fmp_int_ext_h264" id="fmp_int_ext0_h264" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext_h264']); ?> /></td>
			<td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext0_h264"><?php echo $BL['be_cnt_internal'] ?>&nbsp;</label></td>
			<td>&nbsp;<input name="fmp_internal_id_h264" type="hidden" id="fmp_internal_id_h264" value="<?php echo $fmp_data['fmp_internal_id_h264'] ?>" /></td>
			<td><input name="fmp_internal_name_h264" type="text" id="fmp_internal_name_h264" class="width300 greyed" value="<?php echo html_specialchars($fmp_data['fmp_internal_name_h264']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=12');" /></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=12');return false;"><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name_h264').value='';getObjectById('fmp_internal_id_h264').value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" border="0" /></a></td>
		</tr>
		<tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
		<tr>
			<td bgcolor="#E7E8EB"><input name="fmp_int_ext_h264" id="fmp_int_ext1_h264" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext_h264']); ?> /></td>
			<td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext1_h264"><?php echo $BL['be_cnt_external'] ?>&nbsp;</label></td>
			<td>&nbsp;</td>
			<td colspan="3"><input name="fmp_external_file_h264" type="text" id="fmp_external_file_h264" class="width300" value="<?php echo html_specialchars($fmp_data['fmp_external_file_h264']) ?>" size="40" /></td>
		</tr>
		
		<tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
		
		<!-- HTML5 Media WebM -->
		<tr>
			<td colspan="3" class="chatlist tdbottom3" align="right"><?php echo $BL['be_media_format'] ?>&nbsp;&nbsp;</td>
			<td colspan="3" class="v10 tdbottom3 greyed">&nbsp;<?php echo $BL['be_html5_webm'] ?> &#8212; <i>webm</i></td>
		</tr>
		<tr>
			<td bgcolor="#E7E8EB"><input name="fmp_int_ext_webm" id="fmp_int_ext0_webm" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext_webm']); ?> /></td>
			<td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext0_webm"><?php echo $BL['be_cnt_internal'] ?>&nbsp;</label></td>
			<td>&nbsp;<input name="fmp_internal_id_webm" type="hidden" id="fmp_internal_id_webm" value="<?php echo $fmp_data['fmp_internal_id_webm'] ?>" /></td>
			<td><input name="fmp_internal_name_webm" type="text" id="fmp_internal_name_webm" class="width300 greyed" value="<?php echo html_specialchars($fmp_data['fmp_internal_name_webm']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=13');" /></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=13');return false;"><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name_webm').value='';getObjectById('fmp_internal_id_webm').value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" border="0" /></a></td>
		</tr>
		<tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
		<tr>
			<td bgcolor="#E7E8EB"><input name="fmp_int_ext_webm" id="fmp_int_ext1_webm" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext_webm']); ?> /></td>
			<td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext1_webm"><?php echo $BL['be_cnt_external'] ?>&nbsp;</label></td>
			<td>&nbsp;</td>
			<td colspan="3"><input name="fmp_external_file_webm" type="text" id="fmp_external_file_webm" class="width300" value="<?php echo html_specialchars($fmp_data['fmp_external_file_webm']) ?>" size="40" /></td>
		</tr>
		
		<tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	
		<!-- HTML5 Media Ogg -->
		<tr>
			<td colspan="3" class="chatlist tdbottom3" align="right"><?php echo $BL['be_media_format'] ?>&nbsp;&nbsp;</td>
			<td colspan="3" class="v10 tdbottom3 greyed">&nbsp;<?php echo $BL['be_html5_ogg'] ?> &#8212; <i>.ogg, .ogv, .oga, .ogx</i></td>
		</tr>
		<tr>
			<td bgcolor="#E7E8EB"><input name="fmp_int_ext_ogg" id="fmp_int_ext0_ogg" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext_ogg']); ?> /></td>
			<td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext0_ogg"><?php echo $BL['be_cnt_internal'] ?>&nbsp;</label></td>
			<td>&nbsp;<input name="fmp_internal_id_ogg" type="hidden" id="fmp_internal_id_ogg" value="<?php echo $fmp_data['fmp_internal_id_ogg'] ?>" /></td>
			<td><input name="fmp_internal_name_ogg" type="text" id="fmp_internal_name_ogg" class="width300 greyed" value="<?php echo html_specialchars($fmp_data['fmp_internal_name_ogg']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=14');" /></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=14');return false;"><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name_ogg').value='';getObjectById('fmp_internal_id_ogg').value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" border="0" /></a></td>
		</tr>
		<tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
		<tr>
			<td bgcolor="#E7E8EB"><input name="fmp_int_ext_ogg" id="fmp_int_ext1_ogg" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext_ogg']); ?> /></td>
			<td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext1_ogg"><?php echo $BL['be_cnt_external'] ?>&nbsp;</label></td>
			<td>&nbsp;</td>
			<td colspan="3"><input name="fmp_external_file_ogg" type="text" id="fmp_external_file_ogg" class="width300" value="<?php echo html_specialchars($fmp_data['fmp_external_file_ogg']) ?>" size="40" /></td>
		</tr>	
		
	</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="12" /></td></tr>
<tr><td colspan="2" class="rowspacer0x7" bgcolor="#e7e8eb"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist" bgcolor="#e7e8eb">&nbsp;</td>
	<td bgcolor="#e7e8eb"><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><input type="radio" name="fmp_player" id="fmp_player_nvb" value="1"<?php is_checked(1, $fmp_data['fmp_player']) ?> onclick="setPlayer(1);" /></td>
			<td class="tdtop3 v12" nowrap="nowrap"><label for="fmp_player_nvb">&nbsp;<strong>NonverBlaster:hover</strong></label></td>
			
			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>			
		
			<td><input type="radio" name="fmp_player" id="fmp_player_jw" value="0"<?php is_checked(0, $fmp_data['fmp_player']) ?> onclick="setPlayer(0);" /></td>
			<td class="tdtop3 v12" nowrap="nowrap"><label for="fmp_player_jw">&nbsp;<strong>JW Player&#8482;</strong></label></td>
			
			<td width="50%">&nbsp;</td>
		</tr>
		
		<tr class="jw-player">
			<td colspan="6"><p style="padding:7px;background-color:#C2EB9A;margin:7px 10px 0 5px">
		<?php if(empty($phpwcms['JW_FLV_License'])) {	?>
			
				<a href="http://www.jeroenwijering.com/?item=JW_Media_Player" target="_blank"><strong>JW Player&#8482;</strong></a>
				is licensed under a	non-commercial 
				<a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" target="_blank">Creative Commons License</a>.
				For commercial use you have to 
				<a href="http://www.longtailvideo.com/players/order/" target="_blank">order a special license</a>.

		<?php } else { ?>

				<strong>JW Player&#8482;</strong> License: <strong><?php echo html_specialchars($phpwcms['JW_FLV_License']) ?></strong> 
				[<a href="http://www.longtailvideo.com/players/jw-flv-player/" target="_blank">more</a>]
			
		<?php } ?></p>
			</td>
		</tr>
		
	</table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x0" bgcolor="#e7e8eb"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="7" /></td></tr>

<tr>
	<td align="right" class="chatlist" valign="top"><strong><?php echo $BL['be_flash_media'] ?></strong>:&nbsp;</td>

	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
	
		<!-- Flash Media Fallback -->
		<tr>
			<td colspan="3" class="chatlist tdbottom3" align="right"><?php echo $BL['be_media_format'] ?>&nbsp;&nbsp;</td>
			<td colspan="3" class="v10 tdbottom3 greyed">&nbsp;Flash &#8212; <i>mp4, mp3, flv, mov, swf, f4v, m4v, jpg, png</i></td>
		</tr>
		<tr>
			<td bgcolor="#E7E8EB"><input name="fmp_int_ext" id="fmp_int_ext0" type="radio" value="0" <?php is_checked(0, $fmp_data['fmp_int_ext']); ?> /></td>
			<td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext0"><?php echo $BL['be_cnt_internal'] ?>&nbsp;</label></td>
			<td>&nbsp;<input name="fmp_internal_id" type="hidden" id="fmp_internal_id" value="<?php echo $fmp_data['fmp_internal_id'] ?>" /></td>
			<td><input name="fmp_internal_name" type="text" id="fmp_internal_name" class="width300 greyed" value="<?php echo html_specialchars($fmp_data['fmp_internal_name']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=6');" /></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=6');return false;"><img src="img/button/open_image_button.gif" alt="" border="0" hspace="3" /></a></td>
			<td><a href="#" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="getObjectById('fmp_internal_name').value='';getObjectById('fmp_internal_id').value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" border="0" /></a></td>
		</tr>
		<tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
		<tr>
			<td bgcolor="#E7E8EB"><input name="fmp_int_ext" id="fmp_int_ext1" type="radio" value="1" <?php is_checked(1, $fmp_data['fmp_int_ext']); ?> /></td>
			<td bgcolor="#E7E8EB" class="v10"><label for="fmp_int_ext1"><?php echo $BL['be_cnt_external'] ?>&nbsp;</label></td>
			<td>&nbsp;</td>
			<td colspan="3"><input name="fmp_external_file" type="text" id="fmp_external_file" class="width300" value="<?php echo html_specialchars($fmp_data['fmp_external_file']) ?>" size="40" /></td>
		</tr>
		
	</table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist tdtop3"><?php echo $BL['be_flashplayer_caption'] ?>:&nbsp;</td>
	<td><textarea name="fmp_caption" cols="40" rows="2" class="width440" id="fmp_caption"><?php echo html_specialchars($fmp_data['fmp_caption']) ?></textarea></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>

<tr>
	<td align="right" class="chatlist tdtop3"><?php echo $BL['be_admin_page_link'] ?>:&nbsp;</td>
	<td><input name="fmp_link" type="text" id="fmp_link" class="width440" value="<?php echo html_specialchars($fmp_data['fmp_link']) ?>" size="40" /></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>


<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_flashplayer_thumbnail'] ?>:&nbsp;</td>

	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
	
		<tr>
			<td><input name="fmp_img_name" type="text" id="fmp_img_name" class="width300 greyed" value="<?php echo html_specialchars($fmp_data['fmp_img_name']) ?>" size="40" onfocus="this.blur()" onclick="openFileBrowser('filebrowser.php?opt=7');" /></td>
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
		<tr class="jw-player">
			<td class="chatlist" align="right"><label for="fmp_set_overstretch">Display:&nbsp;</label></td>
			<td><select name="fmp_set_overstretch" id="fmp_set_overstretch">
			
			<option value="uniform"<?php is_selected('uniform', $fmp_data['fmp_set_overstretch']) ?>><?php echo $BL['be_admin_tmpl_default'] ?></option>
			<option value="exactfit"<?php is_selected('exactfit', $fmp_data['fmp_set_overstretch']) ?>>Stretch disproportionally</option>
			<option value="none"<?php is_selected('none', $fmp_data['fmp_set_overstretch']) ?>>Show in original dimensions</option>
			<option value="fill"<?php is_selected('fill', $fmp_data['fmp_set_overstretch']) ?>>Stretch proportionally</option>

			</select></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_skin"><?php echo $BL['be_skin'].' '.$BL['be_html5_media'] ?>:&nbsp;</label></td>
			<td><select name="fmp_set_skin_html5">
			<option value="default"<?php is_selected('', $fmp_data['fmp_set_skin_html5']) ?>><?php echo $BL['be_admin_tmpl_default'] ?></option>
<?php
			// skins for HTML5 Media Player
			$skins = returnFileListAsArray(PHPWCMS_TEMPLATE.'lib/video-js/skins', 'css');
			if(is_array($skins) && count($skins)):
				foreach($skins as $skin):
					$skin = cut_ext($skin['filename']);
?>
			<option value="<?php 
				echo html_specialchars($skin) 
			?>"<?php is_selected($skin, $fmp_data['fmp_set_skin_html5']) ?>><?php 
				echo html_specialchars(ucwords(str_replace('_', ' ', $skin)))
			?></option>
<?php			
				endforeach;
			endif;
?>
			</select></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_showcontrols">Controlbar:&nbsp;</label></td>
			<td><select name="fmp_set_showcontrols" id="fmp_set_showcontrols">
			
			<option value="bottom"<?php is_selected('bottom', $fmp_data['fmp_set_showcontrols']) ?>><?php echo $BL['be_admin_tmpl_default'] ?></option>
			<option value="none"<?php is_selected('none', $fmp_data['fmp_set_showcontrols']) ?>><?php echo $BL['be_admin_struct_hide1'] ?></option>
			<option value="over"<?php is_selected('over', $fmp_data['fmp_set_showcontrols']) ?>><?php echo $BL['over'] ?></option>

			</select>

			<input type="hidden" name="fmp_set_largecontrols" id="fmp_set_largecontrols" value="0" />
			<input type="hidden" name="fmp_set_showdigits" id="fmp_set_showdigits" value="0" />
			<input type="hidden" name="fmp_set_showeq" id="fmp_set_showeq" value="0" />
			<input type="hidden" name="fmp_set_showvolume" id="fmp_set_showvolume" value="0" />
			<input type="hidden" name="fmp_set_showdownload" id="fmp_set_showdownload" value="0" />
			
			</td>
		</tr>

		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_bgcolor"><?php echo $BL['be_background_color'].' '.$BL['be_flash_media'] ?> (HEX):&nbsp;</label></td>
			<td><input name="fmp_set_bgcolor" type="text" id="fmp_set_bgcolor" class="width75" value="<?php echo html_specialchars($fmp_data['fmp_set_bgcolor']) ?>" size="40" maxlength="7" /></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_color"><?php echo $BL['be_foreground_color'].' '.$BL['be_flash_media'] ?> (HEX):&nbsp;</label></td>
			<td><input name="fmp_set_color" type="text" id="fmp_set_color" class="width75" value="<?php echo html_specialchars($fmp_data['fmp_set_color']) ?>" size="40" maxlength="7" /></td>
		</tr>
		
		<tr class="jw-player">
			<td class="chatlist" align="right"><label for="fmp_set_hcolor"><?php echo $BL['be_highlight_color'].' '.$BL['be_flash_media'] ?> (HEX):&nbsp;</label></td>
			<td><input name="fmp_set_hcolor" type="text" id="fmp_set_hcolor" class="width75" value="<?php echo html_specialchars($fmp_data['fmp_set_hcolor']) ?>" size="40" maxlength="7" /></td>
		</tr>
		
		<tr>
			<td class="chatlist" align="right"><label for="fmp_set_logo"><?php echo $BL['be_media_watermark'].' '.$BL['be_flash_media'].' ('.$BL['be_cnt_pages_cust'].')' ?>:&nbsp;</label></td>
			<td><input name="fmp_set_logo" type="text" id="fmp_set_logo" class="width200" value="<?php echo html_specialchars($fmp_data['fmp_set_logo']) ?>" size="40" /></td>
		</tr>
		
		<tr class="jw-player">
			<td class="chatlist" align="right"><label for="fmp_set_skin"><?php echo $BL['be_skin'] ?> JW Player&#8482;:&nbsp;</label></td>
			<td><select name="fmp_set_skin">
			<option value="default"<?php is_selected('default', $fmp_data['fmp_set_skin']) ?>><?php echo $BL['be_admin_tmpl_default'] ?></option>
<?php
			// skins for Flash Media Player
			$skins = returnFileListAsArray(PHPWCMS_TEMPLATE.'lib/jw_media_player/skins', 'swf,zip');
			if(is_array($skins) && count($skins)):
				foreach($skins as $skin):
?>
			<option value="<?php echo $skin['filename'] ?>"<?php is_selected($skin['filename'], $fmp_data['fmp_set_skin']) ?>><?php echo html_specialchars(ucwords(str_replace('_', ' ', cut_ext($skin['filename'])))) ?></option>
<?php			
				endforeach;
			endif;
?>
			</select></td>
		</tr>
		
	</table></td>
</tr>

<tr>
	<td colspan="2" class="rowspacer7x0"><script type="text/javascript">
	var selected_player = <?php echo $fmp_data['fmp_player'] ?>;
	var tr_jw_player	= null;
	
	function setIdName(file_id, file_name, file_type) {
		if(file_id == null) file_id=0;
		if(file_name == null) file_name='';
		if(file_type == 6 || file_type == null) {
			$('fmp_internal_id').value = file_id;
			$('fmp_internal_name').value = file_name;
		} else if(file_type == 12) { // H.264
			$('fmp_internal_id_h264').value = file_id;
			$('fmp_internal_name_h264').value = file_name;
		} else if(file_type == 13) { // WebM
			$('fmp_internal_id_webm').value = file_id;
			$('fmp_internal_name_webm').value = file_name;
		} else if(file_type == 14) { // Ogg
			$('fmp_internal_id_ogg').value = file_id;
			$('fmp_internal_name_ogg').value = file_name;
		}
	}
	function setImgIdName(file_id, file_name) {
		if(file_id == null) file_id=0;
		if(file_name == null) file_name='';
		$('fmp_img_id').value = file_id;
		$('fmp_img_name').value = file_name;
	}
	function setPlayerSize(sval) {
		var indx = sval.selectedIndex;
		if(indx > 0) {
			var val = sval.options[indx].value.split('x');
			$('fmp_width').value = parseInt(val[0]);
			$('fmp_height').value = parseInt(val[1]);
		}
		sval.options[0].selected = true;
		sval.blur();
	}
	function setPlayer(val) {
		selected_player = val ? 1 : 0;
		switchPlayer();
	}
	var switchPlayer = function() {
		if(tr_jw_player === null) {
			tr_jw_player = $('articlecontent').getElements('tr.jw-player');
		}		
		if(selected_player === 0) {
			tr_jw_player.each(function(el){el.setStyle('display', '')});
		} else {
			tr_jw_player.each(function(el){el.setStyle('display', 'none')});
		}
	}		
	window.addEvent('domready', switchPlayer);
</script></td>
</tr>