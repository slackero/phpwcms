<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//FAQ

?>

<div class="form-group form-row">
	<label for="faq_template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
	<div class="col-sm-4">
		<select name="faq_template" id="faq_template" class="custom-select form-control form-control-sm">
			<?php
			$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE . 'inc_cntpart/faq');
			if (is_array($tmpllist) && count($tmpllist)) {
				foreach ($tmpllist as $val) {
					if (isset($content['faq']['faq_template']) && $val == $content['faq']['faq_template']) {
						$selected_val = ' selected="selected"';
					} else {
						$selected_val = '';
					}
					$val = html($val);
					echo '			<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
				}
			}
			?>
		</select>
	</div>
</div>

<hr />

<div class="form-group form-row">
	<label for="faq_question" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_question']; ?></label>
	<div class="col-sm-10">
		<textarea name="faq_question" rows="4" class="form-control form-control-sm field-sizing-content field-sizing-content-4" id="faq_question"><?php echo empty($content['faq_question']) ? '' : $content['faq_question']; ?></textarea>
	</div>
</div>

<div class="form-group form-row">
	<label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_answer']; ?></label>
	<div class="col-sm-10">
		<?php
		$wysiwyg_editor = array(
			'value'     => isset($content['faq_answer']) ? $content['faq_answer'] : '',
			'field'     => 'faq_answer',
			'height'    => '300px',
			'width'     => '100%',
			'rows'      => '15',
			'editor'    => $_SESSION['WYSIWYG_EDITOR'],
			'lang'      => 'en'
		);
		include PHPWCMS_ROOT . '/include/inc_lib/wysiwyg.editor.inc.php';
		?>
	</div>
</div>

<hr />

<div class="form-group form-row align-items-center">
	<label for="cimage_name" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_image']; ?></label>
	<div class="col-sm-4">
		<div class="input-group">
			<span class="input-group-prepend">
				<button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=0&amp;target=nolist"></button>
			</span>
			<input name="cimage_name" type="text" id="cimage_name" class="form-control form-control-sm" value="<?php echo isset($content['image_name']) ? html($content['image_name']) : ''; ?>" maxlength="250" onfocus="this.blur()" />
			<span class="input-group-append">
				<a href="#" id="cimage_delete_button" class="btn btn-sm btn-danger trash<?php echo empty($content['image_id']) ? ' disabled' : '' ?>" style="<?php echo empty($content['image_id']) ? 'opacity: 0.5; pointer-events: none;' : '' ?>" type="button" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage']; ?>" onclick="if ($(this).hasClass('disabled')) return false; bsConfirmDanger('<?php echo js_singlequote($BL['be_image_delete_js']); ?>' + (document.articlecontent.cimage_name.value ? '\n[' + document.articlecontent.cimage_name.value + ']' : ''), function() { document.articlecontent.cimage_name.value='';document.articlecontent.cimage_id.value='0'; if (typeof onImageSelected === 'function') onImageSelected('_', '0', ''); }, '<?php echo js_singlequote($BL['be_yes']); ?>', '<?php echo js_singlequote($BL['be_no']); ?>'); this.blur();return false;"></a>
			</span>
		</div>
		<input name="cimage_id" type="hidden" value="<?php echo isset($content['image_id']) ? $content['image_id'] : ''; ?>" />
	</div>

	<label for="cimage_width" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_size'] ?></label>
	<div class="col-sm-auto my-2 my-sm-0">
		<div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
			</div>
			<input name="cimage_width" type="text" class="form-control form-control-sm" id="cimage_width" style="width: 50px;" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo isset($content['image_width']) ? $content['image_width'] : '' ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
	</div>
	<div class="col-sm-auto my-2 my-sm-0 ml-sm-3">
		<div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_cnt_maxh'] ?></span>
			</div>
			<input name="cimage_height" type="text" class="form-control form-control-sm" id="cimage_height" style="width: 50px;" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo isset($content['image_height']) ? $content['image_height'] : '' ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
	</div>
	<div class="col-sm-auto my-2 my-sm-0 ml-sm-3 d-flex align-items-center">
		<div class="custom-control custom-checkbox custom-control-inline">
			<input name="cimage_zoom" type="checkbox" id="cimage_zoom" value="1" class="custom-control-input" <?php is_checked(1, isset($content['image_zoom']) ? $content['image_zoom'] : 0); ?> />
			<label class="custom-control-label" for="cimage_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>
		</div>
	</div>
</div>

<div class="form-group form-row">
	<label for="cimage_caption" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_caption']; ?></label>
	<div class="col-sm-10">
		<div class="d-flex align-items-start">
			<textarea name="cimage_caption" rows="4" class="form-control form-control-sm mr-3 field-sizing-content field-sizing-content-4" id="cimage_caption"><?php echo isset($content['image_caption']) ? html($content['image_caption']) : ''; ?></textarea>
			<div id="cimage_preview_container">
				<?php
				if (isset($content['image_hash'])) {
					$thumb_image = get_cached_image(array(
						'target_ext'    =>  $content['image_ext'],
						'image_name'    =>  $content['image_hash'] . '.' . $content['image_ext'],
						'thumb_name'    =>  md5($content['image_hash'].$phpwcms['img_list_width'].$phpwcms['img_list_height'].$phpwcms['sharpen_level'].$phpwcms['colorspace'])
					));

					if ($thumb_image != false) {
						echo '				<img src="' . $thumb_image['src'] . '" alt="" ' . $thumb_image[3] . ' class="img-thumbnail" />';
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
