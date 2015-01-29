<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
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


//images special

initMootools();

// some predefinitions
if(empty($template_default['imagegallery_default_column'])) {
	$template_default['imagegallery_default_column'] = 1;
} else {
	$template_default['imagegallery_default_column'] = intval($template_default['imagegallery_default_column']);
	if(empty($template_default['imagegallery_default_column'])) {
		$template_default['imagegallery_default_column'] = 1;
	}
}
$template_default['imagegallery_default_width']	 = isset($template_default['imagegallery_default_width']) ? $template_default['imagegallery_default_width'] : '' ;
$template_default['imagegallery_default_height'] = isset($template_default['imagegallery_default_height']) ? $template_default['imagegallery_default_height'] : '' ;
$template_default['imagegallery_default_space']	 = isset($template_default['imagegallery_default_space']) ? $template_default['imagegallery_default_space'] : '' ;


$content['image_default'] = array(

			'pos'			=> 0,
			'width'			=> $template_default['imagegallery_default_width'],
			'height'		=> $template_default['imagegallery_default_height'],
			'width_zoom'	=> $phpwcms['img_prev_width'],
			'height_zoom'	=> $phpwcms['img_prev_height'],
			'col'			=> $template_default['imagegallery_default_column'],
			'space'			=> $template_default['imagegallery_default_space'],
			'zoom'			=> 0,
			'caption'		=> '',
			'lightbox'		=> 0,
			'nocaption'		=> 0,
			'center'		=> 0,
			'crop'			=> 0,
			'crop_zoom'		=> 0,
			'fx1'			=> 0,
			'fx2'			=> 0,
			'fx3'			=> 0,
			'freetext'		=> '',
			'images'		=> array()

					);

$content['image_special'] = isset($content['image_special']) ? array_merge($content['image_default'], $content['image_special']) : $content['image_default'];

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><select name="template" id="template" class="width150">
<?php

	echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

	$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/imagespecial');
	if(is_array($tmpllist) && count($tmpllist)) {
		foreach($tmpllist as $val) {
			// do not show listmode templates
			if(substr($val, 0, 5) == 'list.') {
				continue;
			}
			$selected_val = (isset($content["image_template"]) && $val == $content["image_template"]) ? ' selected="selected"' : '';
			$val = html($val);
			echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
		}
	}

?>
			</select></td>

		<td class="chatlist">&nbsp;&nbsp;&nbsp;<?php echo $BL['be_image_align'] ?>:&nbsp;</td>

		 <td>
			<select name="cimage_center" id="cimage_center" class="v11 width150">

				<option value="0"<?php is_selected(0, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagenocenter'] ?></option>
				<option value="1"<?php is_selected(1, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagecenter'] ?></option>
				<option value="2"<?php is_selected(2, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagecenterh'] ?></option>
				<option value="3"<?php is_selected(3, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagecenterv'] ?></option>

			</select>
		</td>

		</tr>

	</table></td>

</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_flashplayer_thumbnail'] ?>:&nbsp;</td>
	<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>

			<td><input name="cimage_width" type="text" class="f11b" id="cimage_width" style="width: 50px;" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo $content['image_special']['width']; ?>" /></td>
			<td class="chatlist">&nbsp;x&nbsp;</td>

			<td><input name="cimage_height" type="text" class="f11b" id="cimage_height" style="width: 50px;" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo $content['image_special']['height']; ?>" /></td>
			<td class="chatlist">&nbsp;<?php echo $BL['be_image_WxHpx'] ?>&nbsp;&nbsp;&nbsp;</td>

			<td><input type="checkbox" name="cimage_crop" id="cimage_crop" value="1" <?php is_checked(1, $content['image_special']['crop']); ?> /></td>
			<td class="v10"><label for="cimage_crop" class="checkbox"><?php echo $BL['be_image_crop'] ?></label></td>

		</tr>
	</table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

<tr>
	<td align="right" class="chatlist">&nbsp;</td>
	<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td class="chatlist"><?php echo $BL['be_cnt_column'] ?>:&nbsp;</td>
			<td><select name="cimage_col" id="cimage_col">
<?php

// list select menu for max image columns
for($max_image_col = 1; $max_image_col <= 25; $max_image_col++) {

	echo '<option value="'.$max_image_col.'" ';
	is_selected($max_image_col, $content['image_special']['col']);
	echo '>'.$max_image_col.'</option>'.LF;

}

?>
			  </select></td>
			  <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_imagespace'] ?>:&nbsp;</td>
			  <td><input name="cimage_space" type="text" class="f11b" id="cimage_space" style="width: 50px;" size="2" maxlength="3" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['image_special']['space']; ?>" /></td>
			  <td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;</td>

			</tr>
		</table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_reference_zoom'] ?>:&nbsp;</td>
	<td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>

			<td><input name="cimage_width_zoom" type="text" class="f11b" id="cimage_width_zoom" style="width: 50px;" size="4" maxlength="4" value="<?php echo $content['image_special']['width_zoom']; ?>" /></td>
			<td class="chatlist">&nbsp;x&nbsp;</td>

			<td><input name="cimage_height_zoom" type="text" class="f11b" id="cimage_height_zoom" style="width: 50px;" size="4" maxlength="4" value="<?php echo $content['image_special']['height_zoom']; ?>" /></td>
			<td class="chatlist">&nbsp;<?php echo $BL['be_image_WxHpx'] ?>&nbsp;&nbsp;&nbsp;</td>

			<td><input type="checkbox" name="cimage_crop_zoom" id="cimage_crop_zoom" value="1" <?php is_checked(1, $content['image_special']['crop_zoom']); ?> /></td>
			<td class="v10"><label for="cimage_crop_zoom" class="checkbox"><?php echo $BL['be_image_cropit'] ?></label></td>

		</tr>
	</table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td align="right" class="chatlist tdtop3"><?php echo $BL['be_cnt_behavior'] ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td><input name="cimage_zoom" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, $content['image_special']['zoom']); ?> /></td>
				<td class="v10"><label for="cimage_zoom" class="checkbox"><?php echo $BL['be_cnt_enlarge'] ?></label></td>

				<td>&nbsp;</td>
				<td><input name="cimage_lightbox" type="checkbox" id="cimage_lightbox" value="1" <?php is_checked(1, $content['image_special']['lightbox']); ?> onchange="if(this.checked){getObjectById('cimage_zoom').checked=true;}" /></td>
				<td class="v10"><label for="cimage_lightbox" class="checkbox"><?php echo $BL['be_cnt_lightbox'] ?></label></td>

				<td>&nbsp;</td>
				<td><input name="cimage_nocaption" type="checkbox" id="cimage_nocaption" value="1" <?php is_checked(1, $content['image_special']['nocaption']); ?> /></td>
				<td class="v10"><label for="cimage_nocaption" class="checkbox"><?php echo $BL['be_cnt_imglist_nocaption'] ?></label></td>

			</tr>

			<tr>
				<td><input name="cimage_fx1" type="checkbox" id="cimage_fx1" value="1" <?php is_checked(1, $content['image_special']['fx1']); ?> /></td>
				<td class="v10"><label for="cimage_fx1" class="checkbox"><?php echo $BL['be_fx_1'] ?></label></td>

				<td>&nbsp;</td>
				<td><input name="cimage_fx2" type="checkbox" id="cimage_fx2" value="1" <?php is_checked(1, $content['image_special']['fx2']); ?> onchange="if(this.checked){getObjectById('cimage_zoom').checked=true;}" /></td>
				<td class="v10"><label for="cimage_fx2" class="checkbox"><?php echo $BL['be_fx_2'] ?></label></td>

				<td>&nbsp;</td>
				<td><input name="cimage_fx3" type="checkbox" id="cimage_fx3" value="1" <?php is_checked(1, $content['image_special']['fx3']); ?> /></td>
				<td class="v10"><label for="cimage_fx3" class="checkbox"><?php echo $BL['be_fx_3'] ?></label></td>

			</tr>

		</table>
	</td>
</tr>


<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
	<td class="chatlist tdtop5" align="right"><?php echo $BL['be_ctype_images'] ?>:&nbsp;</td>
	<td class="tdbottom4">

	<button onclick="return addNewImage('top');">
		<span class="btn_image_add"><?php echo $BL['be_article_cnt_add'] ?></span>
	</button>

	</td>

</tr>

<tr>
	<td>&nbsp;</td>
	<td>

	<ul id="images">

<?php

	// Sort/Up Down Title
	$sort_up_down = $BL['be_func_struct_sort_up'] . ' / '. $BL['be_func_struct_sort_down'];

	// loop available image entries
	foreach($content['image_special']['images'] as $key => $value) {

?>

		<li id="image_<?php echo $key ?>">
		<table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
		<td class="chatlist right">
			<input name="cimage_id_thumb[<?php echo $key ?>]" id="cimage_id_thumb_<?php echo $key ?>" type="hidden" value="<?php echo $value['thumb_id'] ?>" />
			<input name="cimage_sort[<?php echo $key ?>]" id="cimage_sort_<?php echo $key ?>" type="hidden" value="<?php echo $value['sort'] ?>" />
			<?php echo $BL['be_flashplayer_thumbnail'] ?>:&nbsp;</td>
		<td><input name="cimage_name_thumb[<?php echo $key ?>]" type="text" id="cimage_name_thumb_<?php echo $key ?>" class="f11b imagename" value="<?php echo html($value['thumb_name']) ?>" size="30" onfocus="this.blur();" /></td>
		<td><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser('thumb_<?php echo $key ?>');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
		<td><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData('thumb_<?php echo $key ?>', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
	</tr>

	<tr><td colspan="3" class="spacerrow"></td></tr>

	<tr>
		<td class="chatlist right">
			<input name="cimage_id_zoom[<?php echo $key ?>]" id="cimage_id_zoom_<?php echo $key ?>" type="hidden" value="<?php echo $value['zoom_id'] ?>" />
			<?php echo $BL['be_image_zoom'] ?>:&nbsp;</td>
		<td><input name="cimage_name_zoom[<?php echo $key ?>]" type="text" id="cimage_name_zoom_<?php echo $key ?>" class="f11b imagename" value="<?php echo html($value['zoom_name']) ?>" size="30" onfocus="this.blur();" /></td>
		<td><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser('zoom_<?php echo $key ?>');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
		<td><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData('zoom_<?php echo $key ?>', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
	</tr>

	<tr>
		<td class="spacerrow"></td>
		<td id="img_preview_<?php echo $key ?>" colspan="3" class="backend_preview_img"></td>
	</tr>

	<tr>
		<td class="chatlist right tdtop3"><?php echo $BL['be_cnt_caption'] ?>:&nbsp;</td>
		<td colspan="3">
			<textarea name="cimage_caption[<?php echo $key ?>]" id="cimage_caption_<?php echo $key ?>" class="width300" cols="30" rows="2"><?php echo html($value['caption']) ?></textarea>
			<span class="caption width440">
				<?php echo $BL['be_cnt_caption']; ?>
				|
				<?php echo $BL['be_caption_alt']; ?>
				|
				<?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
				|
				<?php echo $BL['be_caption_title']; ?>
				|
				<?php echo $BL['be_copyright']; ?>
			</span>
		</td>
	</tr>

	<tr><td colspan="3" class="spacerrow"></td></tr>

	<tr>
		<td class="chatlist right tdtop3"><?php echo $BL['be_cnt_infotext'] ?>:&nbsp;</td>
		<td colspan="3"><textarea name="cimage_freetext[<?php echo $key ?>]" id="cimage_freetext_<?php echo $key ?>" class="w300" cols="30" rows="2"><?php echo html(empty($value['freetext']) ? '' : $value['freetext']) ?></textarea></td>
	</tr>

	<tr><td colspan="3" class="spacerrow"></td></tr>

	<tr>
		<td class="chatlist right"><?php echo $BL['be_profile_label_website'] ?>:&nbsp;</td>
		<td><input type="text" name="cimage_url[<?php echo $key ?>]" id="cimage_url_<?php echo $key ?>" class="v11 w300" size="30" value="<?php echo html($value['url']) ?>" /></td>
		<td><em title="<?php echo $sort_up_down; ?>" class="handle">&nbsp;</em></td>
		<td><a href="#" onclick="return deleteImgElement('image_<?php echo $key ?>');"><img src="img/famfamfam/image_delete.gif" alt="" border="" /></a></td>
	</tr>


		</table>
		</li>

<?php

	}
	// close image entry looping

?>

	</ul>

	</td>
</tr>

<?php
	// second button to add images at bottom of list
	if (count($content['image_special']['images'])){
?>
<tr>
	<td class="chatlist tdtop5" align="right"><?php echo $BL['be_ctype_images'] ?>:&nbsp;</td>
	<td class="tdbottom4">

	<button onclick="return addNewImage('bottom');">
		<span class="btn_image_add"><?php echo $BL['be_article_cnt_add'] ?></span>
	</button>

	</td>

</tr>
<?php
	}
?>
<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr><td colspan="2" align="center"><?php

$wysiwyg_editor = array(
	'value'		=> isset($content["image_html"]) ? $content["image_html"] : '',
	'field'		=> 'image_html',
	'height'	=> '300px',
	'width'		=> '536px',
	'rows'		=> '15',
	'editor'	=> $_SESSION["WYSIWYG_EDITOR"],
	'lang'		=> 'en'
);

include(PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php');


?></td></tr>

<tr>
	<td colspan="2" class="rowspacer7x7">
	<script type="text/javascript">
	<!--

	var site_url	= '<?php echo PHPWCMS_URL; ?>';
	var max_img_w	= <?php echo $phpwcms['img_list_width']; ?>;
	var max_img_h	= <?php echo $phpwcms['img_list_height']; ?>;
	var image_entry	= new Array();

	function setCimageCenterInactive() {
		var cih = $('cimage_width');
		var ciw = $('cimage_height');
		var cic = $('cimage_center');
		var ccp = $('cimage_crop');
		var dis = false;
		if(!parseInt(cih.value,10)) {
			cih.value = '';
			dis = true;
		}
		if(!parseInt(ciw.value,10)) {
			ciw.value = '';
			dis = true;
		}
		if(dis) {
			cic.disabled = true;
			ccp.disabled = true;
		} else {
			cic.disabled = false;
			ccp.disabled = false;
		}
	}

	function openImageFileBrowser(image_number) {
		openFileBrowser('filebrowser.php?opt=8&target=nolist&entry_id='+image_number);
		return false;
	}
	function setImgIdName(image_number, file_id, file_name) {
		if(file_id == null || file_name == null) return null;
		$('cimage_id_'+image_number).value = file_id;
		$('cimage_name_'+image_number).value = file_name;
		image_number = image_number.split('_');
		if(image_number[1]) {
			updatePreviewImage(image_number[1]);
		}
	}
	function deleteImageData(image_number, e) {
		$('cimage_name_'+image_number).value='';
		$('cimage_id_'+image_number).value='0';
		e.blur();
		image_number = image_number.split('_');
		if(image_number[1]) {
			updatePreviewImage(image_number[1]);
		}
		return false;
	}

	function updatePreviewImage(image_number) {
		var preview = '';
		if($('cimage_id_thumb_'+image_number)) {
			var image_file_id = $('cimage_id_thumb_'+image_number).value;
			preview += getBackendImgSrc( image_file_id );
		}
		if($('cimage_id_zoom_'+image_number)) {
			var image_file_id = $('cimage_id_zoom_'+image_number).value;
			preview += getBackendImgSrc( image_file_id );
		}
		$('img_preview_'+image_number).setHTML(preview);
	}

	function getBackendImgSrc(image_file_id) {
		var image_file_id = parseInt(image_file_id,10);
		if(image_file_id) {
			return '<'+'img src="'+site_url+'img/cmsimage.php/'+max_img_w+'x'+max_img_h+'/'+image_file_id+'" border="0" alt="" /'+'> ';
		}
		return '';
	}

	function updatePreviewImageAll() {
		var all_images = $('images').getElements('li[id^=image_]');
		if(all_images.length > 0) {
			all_images.each(function(e) {
				image_number = e.id.split('_');
				if(image_number[1])	{
					updatePreviewImage(image_number[1]);
					image_entry[ image_number[1] ] = $('cimage_sort_'+image_number[1]).value;
				}
			});
		}
	}

	function addNewImage(where) {

		updatePreviewImageAll();

		var entry_number = image_entry.length;

		var new_entry = '';

		new_entry += '<'+'table border="0" cellpadding="0" cellspacing="0" summary=""'+'>';
		new_entry += '<'+'tr>';
		new_entry += '<'+'td class="chatlist right">';
		new_entry += '<'+'input name="cimage_id_thumb['+entry_number+']" id="cimage_id_thumb_'+entry_number+'" type="hidden" value="" /'+'>';
		new_entry += '<'+'input name="cimage_sort['+entry_number+']" id="cimage_sort_'+entry_number+'" type="hidden" value="'+entry_number+'" /'+'>';
		new_entry += '<?php echo $BL['be_flashplayer_thumbnail'] ?>:&nbsp;<'+'/td'+'>';
		new_entry += '<'+'td><input name="cimage_name_thumb['+entry_number+']" type="text" id="cimage_name_thumb_'+entry_number+'" class="f11b imagename" value="" size="30" onfocus="this.blur();" /><'+'/td>';
		new_entry += '<'+'td><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser(\'thumb_'+entry_number+'\');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /'+'><'+'/a><'+'/td>';
		new_entry += '<'+'td><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData(\'thumb_'+entry_number+'\', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /><'+'/a><'+'/td>';
		new_entry += '<'+'/tr>';
		new_entry += '<'+'tr><td colspan="3" class="spacerrow"><'+'/td><'+'/tr>';
		new_entry += '<'+'tr>';
		new_entry += '<'+'td class="chatlist right">';
		new_entry += '<'+'input name="cimage_id_zoom['+entry_number+']" id="cimage_id_zoom_'+entry_number+'" type="hidden" value="" /'+'>';
		new_entry += '<?php echo $BL['be_image_zoom'] ?>:&nbsp;<'+'/td>';
		new_entry += '<'+'td><input name="cimage_name_zoom['+entry_number+']" type="text" id="cimage_name_zoom_'+entry_number+'" class="f11b imagename" value="" size="30" onfocus="this.blur();" /><'+'/td>';
		new_entry += '<'+'td><a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="return openImageFileBrowser(\'zoom_'+entry_number+'\');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /><'+'/a><'+'/td>';
		new_entry += '<'+'td><a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData(\'zoom_'+entry_number+'\', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /><'+'/a><'+'/td>';
		new_entry += '<'+'/tr>';
		new_entry += '<'+'tr>';
		new_entry += '<'+'td class="spacerrow"><'+'/td>';
		new_entry += '<'+'td id="img_preview_'+entry_number+'" colspan="3" class="backend_preview_img"><'+'/td>';
		new_entry += '<'+'/tr>';
		new_entry += '<'+'tr>';
		new_entry += '<'+'td class="chatlist right tdtop3"><?php echo $BL['be_cnt_caption'] ?>:&nbsp;<'+'/td>';
		new_entry += '<'+'td colspan="3"><textarea name="cimage_caption['+entry_number+']" id="cimage_caption_'+entry_number+'" class="width300" cols="30" rows="2"><'+'/textarea>';
		new_entry += '<span class="caption width300"><?php echo $BL['be_cnt_caption']; ?> | <?php echo $BL['be_caption_alt']; ?> | <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em> | <?php echo $BL['be_caption_title']; ?> | <?php echo $BL['be_copyright']; ?></span>';
		new_entry += '<'+'/td>';
		new_entry += '<'+'/tr>';
		new_entry += '<'+'tr><td colspan="3" class="spacerrow"><'+'/td><'+'/tr>';
		new_entry += '<'+'tr>';
		new_entry += '<'+'td class="chatlist right tdtop3"><?php echo $BL['be_cnt_infotext'] ?>:&nbsp;<'+'/td>';
		new_entry += '<'+'td colspan="3"><textarea name="cimage_freetext['+entry_number+']" id="cimage_freetext_'+entry_number+'" class="w300" cols="30" rows="2"><'+'/textarea><'+'/td>';
		new_entry += '<'+'/tr>';
		new_entry += '<'+'tr><td colspan="3" class="spacerrow"><'+'/td><'+'/tr>';
		new_entry += '<'+'tr>';
		new_entry += '<'+'td class="chatlist right"><?php echo $BL['be_profile_label_website'] ?>:&nbsp;<'+'/td>';
		new_entry += '<'+'td><input type="text" name="cimage_url['+entry_number+']" id="cimage_url_'+entry_number+'" class="v11 w300" size="30" value="" /><'+'/td>';
		new_entry += '<'+'td>&nbsp;<'+'/td>';
		new_entry += '<'+'td><a href="#" onclick="return deleteImgElement(\'image_'+entry_number+'\');"><img src="img/famfamfam/image_delete.gif" alt="" border="" /'+'><'+'/a></'+'td>';
		new_entry += '<'+'/tr>';
		new_entry += '<'+'/table>';

		var new_element = new Element('li', {'id': 'image_'+entry_number, 'class': 'nomove'}).inject($('images'),where);
		new_element.innerHTML = new_entry;
		window.location.hash='image_'+entry_number;
		return false;
	};

	function deleteImgElement(e) {
		if(confirm('<?php echo $BL['be_image_delete_js'] ?>')) {
			$(e).remove();
		}
		return false;
	}

	window.addEvent('domready', function() {

		setCimageCenterInactive();
		updatePreviewImageAll();

		new Sortables($('images'), {
			handles: 'em.handle'
		});

	});

	//-->
	</script>
	</td>
</tr>
