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

// content 89: poll         jens

$caption_box = '';
$img_thumbs = '';
$imgx = 0;

if(!isset($content['poll_list'])) {
    $content['poll_list'] = array(
        'width' => '',
        'height' => '',
        'zoom' => 0,
    );
}
if(empty($content['poll_form'])) {
    $content['poll_form'] = array();
}
if(!isset($content['poll_text']['poll_buttonstyle'])) {
    $content['poll_text']['poll_buttonstyle'] = '';
}
if(!isset($content['poll_text']['poll_buttontext'])) {
    $content['poll_text']['poll_buttontext'] = '';
}
if(!empty($content['poll_form']['choice']) && is_array($content['poll_form']['choice']) && count($content['poll_form']['choice'])) {
    foreach($content['poll_form']['choice'] as $key => $value) {
        $caption_box .= html($content['poll_form']['choice'][$key])."\n";
    }
} else {
    $content['poll_form']['choice'] = array();
}

?>

<div class="form-group form-row">
	<label for="cpoll_buttonstyle" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_attribute_class']; ?></label>
	<div class="col-sm-4">
		<input name="cpoll_buttonstyle" type="text" class="form-control form-control-sm" id="cpoll_buttonstyle" value="<?php echo html($content['poll_text']['poll_buttonstyle']) ?>" />
	</div>
</div>

<div class="form-group form-row">
	<label for="cpoll_buttontext" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_buttontext']; ?></label>
	<div class="col-sm-4">
		<input name="cpoll_buttontext" type="text" class="form-control form-control-sm" id="cpoll_buttontext" value="<?php echo html($content['poll_text']['poll_buttontext']) ?>" />
	</div>
</div>

<div class="form-group form-row">
	<label for="cpoll_caption" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_poll_choices']; ?></label>
	<div class="col-sm-10">
		<textarea name="cpoll_caption" rows="8" class="form-control form-control-sm field-sizing-content field-sizing-content-8" id="cpoll_caption"><?php echo $caption_box; ?></textarea>
	</div>
</div>

<hr />

<div class="form-group form-row">
	<label for="cimage_list" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_image']; ?></label>
	<div class="col">
		<select name="cimage_list[]" size="<?php echo is_array($content['poll_select']) && count($content['poll_select']) ? count($content['poll_select'])+5 : 5 ?>" multiple="multiple" class="custom-select form-control form-control-sm" id="cimage_list">
			<?php
			if(isset($content['poll_list']['images']) && is_array($content['poll_list']['images']) && count($content['poll_list']['images'])) {
				foreach($content['poll_list']['images'] as $key => $value) {
					$thumb_image = get_cached_image(array(
						'target_ext' => $content['poll_list']['images'][$key][3],
						'image_name' => $content['poll_list']['images'][$key][2] . '.' . $content['poll_list']['images'][$key][3],
						'thumb_name' => md5($content['poll_list']['images'][$key][2].$phpwcms['img_list_width'].$phpwcms['img_list_height'].$phpwcms['sharpen_level'].$phpwcms['colorspace'])
					));

					if($thumb_image != false) {
						echo '					<option value="' . $content['poll_list']['images'][$key][0] . '">';
						$img_name = html($content['poll_list']['images'][$key][1]);
						echo $img_name . "</option>\n";

						if($imgx == 4) {
							$img_thumbs .= '<br /><img src="img/leer.gif" alt="" width="1" height="2"><br />';
							$imgx = 0;
						}
						if($imgx) {
							$img_thumbs .= '<img src="img/leer.gif" alt="" width="2" height="1" />';
						}
						$img_thumbs .= '<img src="'.$thumb_image['src'].'" '.$thumb_image[3].' alt="'.$img_name.'" title="'.$img_name.'" />';

						$imgx++;
					}
				}
			}
			?>
		</select>
	</div>
	<div class="col-sm-auto">
		<button type="button" class="modalButton btn btn-sm btn-blue mb-1 d-block" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=1&amp;target=nolist"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i></button>
		<button type="button" class="btn btn-sm btn-secondary mb-1 d-inline-block" data-toggle="tooltip" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cimage_list);"><i class="fa fa-angle-up fa-fw" aria-hidden="true"></i></button>
		<button type="button" class="btn btn-sm btn-secondary mb-1 d-inline-block" data-toggle="tooltip" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cimage_list);"><i class="fa fa-angle-down fa-fw" aria-hidden="true"></i></button>
		<button type="button" class="btn btn-sm btn-danger d-block" onclick="removeSelectedOptions(document.articlecontent.cimage_list);" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>"><i class="far fa-trash-alt fa-fw" aria-hidden="true"></i></button>
	</div>
</div>

<?php if($img_thumbs): ?>
	<div class="form-group form-row">
		<div class="col-sm-10 offset-sm-2">
			<?php echo $img_thumbs; ?>
		</div>
	</div>
<?php endif; ?>

<div class="form-group form-row align-items-center">
	<label for="cpoll_width" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_size'] ?></label>
	<div class="col-sm-auto my-2 my-sm-0">
		<div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
			</div>
			<input name="cpoll_width" type="text" class="form-control form-control-sm" id="cpoll_width" style="width: 50px;" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['poll_list']['width'] ?>" />
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
			<input name="cpoll_height" type="text" class="form-control form-control-sm" id="cpoll_height" style="width: 50px;" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['poll_list']['height'] ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
	</div>
	<div class="col-sm-auto my-2 my-sm-0 ml-sm-3 d-flex align-items-center">
		<div class="custom-control custom-checkbox custom-control-inline">
			<input name="cpoll_zoom" type="checkbox" id="cpoll_zoom" value="1" class="custom-control-input" <?php is_checked(1, $content['poll_list']['zoom']); ?> />
			<label class="custom-control-label" for="cpoll_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>
		</div>
	</div>
</div>
