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


//ecard
initAceEditor();

$imgx = 0;
$img_thumbs = '';
$caption_box = '';

if(!isset($content['ecard'])) {

    $content['ecard'] = array(

        'subject'   => '',
        'selector'  => 0,
        'onover'    => '',
        'onclick'   => '',
        'onout'     => '',
        'select'    => array(),
        'images'    => array(),
        'pos'       => 0,
        'width'     => '',
        'height'    => '',
        'form'      => false,
        'send'      => '',
        'mail'      => '',
        'col'       => 1,
        'space'     => '',
        'zoom'  => 0

    );

}

?>

<div class="form-group row g-2">
	<label for="cecard_subject" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_subject']; ?></label>
	<div class="col-sm-10">
		<input name="cecard_subject" type="text" id="cecard_subject" class="form-control form-control-sm" value="<?php echo html($content['ecard']['subject']); ?>" maxlength="250" />
	</div>
</div>

<hr />

<div class="form-group align-items-center row g-2">
	<label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_ecardform_selector']; ?></label>
	<div class="col-sm-10">
		<div class="form-check form-check-inline">
			<input name="cecard_selector" type="radio" id="cecard_selector_0" value="0" class="form-check-input" <?php is_checked(0, $content['ecard']['selector']); ?> />
			<label class="form-check-label" for="cecard_selector_0"><?php echo $BL['be_cnt_ecardform_radiobutton']; ?></label>
		</div>
		<div class="form-check form-check-inline">
			<input name="cecard_selector" type="radio" id="cecard_selector_1" value="1" class="form-check-input" <?php is_checked(1, $content['ecard']['selector']); ?> />
			<label class="form-check-label" for="cecard_selector_1"><?php echo $BL['be_cnt_ecardform_javascript']; ?></label>
		</div>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cecard_over" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_ecardform_over']; ?></label>
	<div class="col-sm-4">
		<input name="cecard_onover" type="text" id="cecard_over" class="form-control form-control-sm" value="<?php echo html($content['ecard']['onover']); ?>" />
	</div>
</div>

<div class="form-group row g-2">
	<label for="cecard_onclick" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_ecardform_click']; ?></label>
	<div class="col-sm-4">
		<input name="cecard_onclick" type="text" id="cecard_onclick" class="form-control form-control-sm" value="<?php echo html($content['ecard']['onclick']); ?>" />
	</div>
</div>

<div class="form-group row g-2">
	<label for="cecard_onout" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_ecardform_out']; ?></label>
	<div class="col-sm-4">
		<input name="cecard_onout" type="text" id="cecard_onout" class="form-control form-control-sm" value="<?php echo html($content['ecard']['onout']); ?>" />
	</div>
</div>

<hr />

<div class="form-group row g-2">
	<label for="cimage_list" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_image']; ?></label>
	<div class="col">
		<select name="cimage_list[]" size="<?php echo isset($content['ecard']['select']) && count($content['ecard']['select']) ? count($content['ecard']['select'])+6 : 6 ?>" multiple="multiple" class="form-select form-control form-control-sm" id="cimage_list">
			<?php
			if(is_array($content['ecard']['images']) && count($content['ecard']['images'])) {
				foreach($content['ecard']['images'] as $key => $value) {
					$caption_box .= html($content['ecard']['images'][$key][6])."\n";
					$thumb_image = get_cached_image(array(
						'target_ext'    =>  $content['ecard']['images'][$key][3],
						'image_name'    =>  $content['ecard']['images'][$key][2] . '.' . $content['ecard']['images'][$key][3],
						'thumb_name'    =>  md5($content['ecard']['images'][$key][2].$phpwcms['img_list_width'].$phpwcms['img_list_height'].$phpwcms['sharpen_level'].$phpwcms['colorspace'])
					));

					if($thumb_image != false) {
						echo '				<option value="' . $content['ecard']['images'][$key][0] . '">';
						$img_name = html($content['ecard']['images'][$key][1]);
						echo $img_name . "</option>\n";

						if($imgx == 4) {
							$img_thumbs .= '<br><img src="img/leer.gif" alt="" width="1" height="2"><br>';
							$imgx = 0;
						}
						if($imgx) {
							$img_thumbs .= '<img src="img/leer.gif" alt="" width="2" height="1">';
						}
						$img_thumbs .= '<img src="' . $thumb_image['src'] . '" ' . $thumb_image[3] . ' alt="' . $img_name . '" title="' . $img_name . '">';
						$imgx++;
					}
				}
			}
			?>
		</select>
	</div>
	<div class="col-sm-auto">
		<button type="button" class="modalButton btn btn-sm btn-blue mb-1 d-block" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="filebrowser.php?opt=3&amp;target=nolist"><i class="fa-solid fa-folder-open fa-fw" aria-hidden="true"></i></button>
		<button type="button" class="btn btn-sm btn-secondary mb-1 d-block" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cimage_list);"><i class="fa-solid fa-angle-up fa-fw" aria-hidden="true"></i></button>
		<button type="button" class="btn btn-sm btn-secondary mb-1 d-block" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cimage_list);"><i class="fa-solid fa-angle-down fa-fw" aria-hidden="true"></i></button>
		<button type="button" class="btn btn-sm btn-danger d-block" onclick="removeSelectedOptions(document.articlecontent.cimage_list);" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>"><i class="far fa-trash-alt fa-fw" aria-hidden="true"></i></button>
	</div>
</div>

<?php if(isset($img_thumbs) && $img_thumbs): ?>
	<div class="form-group row g-2">
		<div class="col-sm-10 offset-sm-2">
			<?php echo $img_thumbs; ?>
		</div>
	</div>
<?php endif; ?>

<div class="form-group row g-2 align-items-center">
	<label for="cecard_pos" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_alignment']; ?></label>
	<div class="col-sm-4">
		<select name="cecard_pos" id="cecard_pos" class="form-select form-select-sm">
			<option value="0" <?php is_selected(0, $content['ecard']['pos']) ?>><?php echo $BL['be_cnt_left'] . ' (' . $BL['be_cnt_default'] . ')' ?></option>
			<option value="1" <?php is_selected(1, $content['ecard']['pos']) ?>><?php echo $BL['be_cnt_center'] ?></option>
			<option value="2" <?php is_selected(2, $content['ecard']['pos']) ?>><?php echo $BL['be_cnt_right'] ?></option>
		</select>
	</div>
</div>

<div class="form-group row g-2 align-items-center">
	<label for="cecard_width" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ftptakeover_size'] ?></label>
	<div class="col-sm-auto">
		<div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
			
			<input name="cecard_width" type="text" class="form-control form-control-sm" id="cecard_width" style="width: 50px;" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['ecard']['width']; ?>" />
			
				<span class="input-group-text">px</span>
			
		</div>
	</div>
	<div class="col-sm-auto">
		<div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_maxh'] ?></span>
			
			<input name="cecard_height" type="text" class="form-control form-control-sm" id="cecard_height" style="width: 50px;" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['ecard']['height']; ?>" />
			
				<span class="input-group-text">px</span>
			
		</div>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cecard_col" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_column']; ?></label>
	<div class="col-sm-10">
		<div class="form-inline">
			<select name="cecard_col" id="cecard_col" class="form-select form-select-sm me-3" style="width: auto;">
				<?php
				for ($max_image_col = 1; $max_image_col <= 25; $max_image_col++) {
					echo '				<option value="' . $max_image_col . '" ';
					is_selected($max_image_col, $content['ecard']['col']);
					echo '>' . $max_image_col . "</option>\n";
				}
				?>
			</select>
			<span class="me-2"><?php echo $BL['be_cnt_imagespace']; ?>:</span>
			<input name="cecard_space" type="text" class="form-control form-control-sm me-2" id="cecard_space" style="width: 50px;" size="2" maxlength="2" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['ecard']['space']; ?>" />
			<span class="me-4">px</span>

			<div class="form-check form-check-inline ms-2">
				<input name="cecard_zoom" type="checkbox" id="cecard_zoom" value="1" class="form-check-input" <?php is_checked(1, $content['ecard']['zoom']); ?> />
				<label class="form-check-label" for="cecard_zoom"><?php echo $BL['be_cnt_enlarge']; ?></label>
			</div>
		</div>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cecard_caption" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_ecardtext']; ?></label>
	<div class="col-sm-10">
		<textarea name="cecard_caption" rows="3" class="form-control form-control-sm field-sizing-content field-sizing-content-3" id="cecard_caption"><?php echo isset($caption_box) ? $caption_box : '' ?></textarea>
	</div>
</div>

<hr />

<?php
if (!$content['ecard']['form']) {
    $content['ecard']['form']  = '<div class="text-center my-3">###ECARD_CHOOSER###</div>' . "\n";
    $content['ecard']['form'] .= '<!--FORM_ERROR_START-->' . "\n";
    $content['ecard']['form'] .= '<div class="alert alert-danger d-flex align-items-center" role="alert">' . "\n";
    $content['ecard']['form'] .= '  <i class="fa-solid fa-exclamation-triangle me-2"></i>' . "\n";
    $content['ecard']['form'] .= '  <div><strong>' . $BL['be_cnt_ecardform_err'] . '</strong></div>' . "\n";
    $content['ecard']['form'] .= '</div>' . "\n";
    $content['ecard']['form'] .= '<!--FORM_ERROR_END-->' . "\n";
    $content['ecard']['form'] .= '<div class="card mb-3">' . "\n";
    $content['ecard']['form'] .= '  <div class="card-body">' . "\n";
    $content['ecard']['form'] .= '    <div class="row">' . "\n";
    $content['ecard']['form'] .= '      <div class="col-md-6 mb-3">' . "\n";
    $content['ecard']['form'] .= '        <h6 class="card-title fw-bold text-success">' . $BL['be_cnt_ecardform_sender'] . '</h6>' . "\n";
    $content['ecard']['form'] .= '        <div class="form-group mb-2">' . "\n";
    $content['ecard']['form'] .= '          <label class="small">' . $BL['be_cnt_ecardform_name'] . ':</label>' . "\n";
    $content['ecard']['form'] .= '          <input name="###SENDER_NAME###" type="text" class="form-control form-control-sm" value="###SENDER_NAME###" />' . "\n";
    $content['ecard']['form'] .= '        </div>' . "\n";
    $content['ecard']['form'] .= '        <div class="form-group mb-0">' . "\n";
    $content['ecard']['form'] .= '          <label class="small">' . $BL['be_profile_label_email'] . ' <span class="text-danger">*</span>:</label>' . "\n";
    $content['ecard']['form'] .= '          <input name="###SENDER_EMAIL###" type="text" class="form-control form-control-sm" value="###SENDER_EMAIL###" />' . "\n";
    $content['ecard']['form'] .= '        </div>' . "\n";
    $content['ecard']['form'] .= '      </div>' . "\n";
    $content['ecard']['form'] .= '      <div class="col-md-6 mb-3">' . "\n";
    $content['ecard']['form'] .= '        <h6 class="card-title fw-bold text-success">' . $BL['be_cnt_ecardform_recipient'] . '</h6>' . "\n";
    $content['ecard']['form'] .= '        <div class="form-group mb-2">' . "\n";
    $content['ecard']['form'] .= '          <label class="small">' . $BL['be_cnt_ecardform_name'] . ':</label>' . "\n";
    $content['ecard']['form'] .= '          <input name="###RECIPIENT_NAME###" type="text" class="form-control form-control-sm" value="###RECIPIENT_NAME###" />' . "\n";
    $content['ecard']['form'] .= '        </div>' . "\n";
    $content['ecard']['form'] .= '        <div class="form-group mb-0">' . "\n";
    $content['ecard']['form'] .= '          <label class="small">' . $BL['be_profile_label_email'] . ' <span class="text-danger">*</span>:</label>' . "\n";
    $content['ecard']['form'] .= '          <input name="###RECIPIENT_EMAIL###" type="text" class="form-control form-control-sm" value="###RECIPIENT_EMAIL###" />' . "\n";
    $content['ecard']['form'] .= '        </div>' . "\n";
    $content['ecard']['form'] .= '      </div>' . "\n";
    $content['ecard']['form'] .= '    </div>' . "\n";
    $content['ecard']['form'] .= '    <div class="form-group mb-3">' . "\n";
    $content['ecard']['form'] .= '      <label class="fw-bold text-success">' . $BL['be_cnt_ecardform_msgtext'] . '</label>' . "\n";
    $content['ecard']['form'] .= '      <textarea name="###SENDER_MESSAGE###" rows="5" id="ecard_sender_msg" class="form-control form-control-sm">###SENDER_MESSAGE###</textarea>' . "\n";
    $content['ecard']['form'] .= '    </div>' . "\n";
    $content['ecard']['form'] .= '    <div class="text-center">' . "\n";
    $content['ecard']['form'] .= '      <input name="###BUTTON###" type="submit" value="' . $BL['be_cnt_ecardform_button'] . '" class="btn btn-sm btn-success" />' . "\n";
    $content['ecard']['form'] .= '    </div>' . "\n";
    $content['ecard']['form'] .= '  </div>' . "\n";
    $content['ecard']['form'] .= '</div>';
}

?>

<div class="form-group row g-2">
	<label for="cecard_form" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_ecardform']; ?></label>
	<div class="col-sm-10">
		<textarea name="cecard_form" rows="15" class="form-control form-control-sm field-sizing-content field-sizing-content-15 font-monospace code-editor" data-mode="html" id="cecard_form"><?php echo html($content['ecard']['form']); ?></textarea>
		<small class="form-text text-muted">
			HTML: ###ECARD_SUBJECT###, ###SENDER_NAME###, ###SENDER_EMAIL###, ###RECIPIENT_NAME###, ###RECIPIENT_EMAIL###, ###SENDER_MESSAGE###, ###ECARD_CHOOSER###, &lt;!--FORM_ERROR_START--&gt; &lt;!--FORM_ERROR_END--&gt;
		</small>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cecard_send" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_ecardsend']; ?></label>
	<div class="col-sm-10">
		<textarea name="cecard_send" rows="5" class="form-control form-control-sm field-sizing-content field-sizing-content-5 font-monospace code-editor" data-mode="html" id="cecard_send"><?php echo html($content['ecard']['send']); ?></textarea>
		<small class="form-text text-muted">
			HTML: ###ECARD_SUBJECT###, ###RECIPIENT_NAME###, ###RECIPIENT_EMAIL###, ###SENDER_MESSAGE###, ###ECARD_TITLE###, ###ECARD_IMAGE###
		</small>
	</div>
</div>

<div class="form-group row g-2">
	<label for="cecard_mail" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_ecardtmpl']; ?></label>
	<div class="col-sm-10">
		<textarea name="cecard_mail" rows="15" class="form-control form-control-sm field-sizing-content field-sizing-content-15 font-monospace code-editor" data-mode="html" id="cecard_mail"><?php echo html($content['ecard']['mail']); ?></textarea>
		<small class="form-text text-muted">
			HTML: ###ECARD_SUBJECT###, ###SENDER_NAME###, ###SENDER_EMAIL###, ###RECIPIENT_NAME###, ###RECIPIENT_EMAIL###, ###SENDER_MESSAGE###, ###ECARD_IMAGE###, ###ECARD_TITLE###
		</small>
	</div>
</div>
