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

// Reference

if(!isset($content['reference'])) {

    $content['reference']["text"] = '';
    $content["reference"]['tmpl'] = '';
    $content['reference']["select"] = array();
    $content['reference']["list"] = array();
    $content['reference']["caption"] = '';
    $content['reference']["zoom"] = 0;
    $content['reference']["width"] = '';
    $content['reference']["height"] = '';
    $content['reference']["border"] = '';
    $content['reference']["pos"] = 0;
    $content['reference']["blockwidth"] = '';
    $content['reference']["blockheight"] = '';
    $content['reference']["space"] = '';
    $content['reference']["listborder"] = '';
    $content["reference"]["basis"] = 0;

}

$imgx=0;
$img_thumbs = '';

?>

<div class="form-group row g-2">
	<label for="creference_tmpl" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template'] ?></label>
	<div class="col-sm-4">
		<select name="creference_tmpl" id="creference_tmpl" class="form-select form-select-sm">
			<?php
			echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;
			$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/reference');
			if(is_array($tmpllist) && count($tmpllist)) {
				foreach($tmpllist as $val) {
					$val = htmlspecialchars($val);
					echo '<option value="' . $val . '"' . ($val == $content["reference"]['tmpl'] ? ' selected="selected"' : '' ).'>' . $val . "</option>\n";
				}
			}
			?>
		</select>
	</div>
</div>

<hr />

<div class="form-group row g-2">
	<label for="creference_text" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_plaintext'] ?></label>
	<div class="col-sm-10">
		<textarea name="creference_text" rows="15" class="form-control form-control-sm field-sizing-content field-sizing-content-15" id="creference_text"><?php echo $content['reference']["text"] ?></textarea>
	</div>
</div>

<hr />

<div class="form-group row g-2">
	<label for="cimage_list" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_image']; ?></label>
	<div class="col">
		<select name="cimage_list[]" size="<?php echo isset($content['reference']["select"]) && count($content['reference']["select"]) ? count($content['reference']["select"]) + 6 : 6; ?>" multiple="multiple" class="form-select form-select-sm" id="cimage_list">
			<?php
			if(is_array($content['reference']["list"]) && count($content['reference']["list"])) {
				foreach($content['reference']["list"] as $key => $value) {
					$thumb_image = get_cached_image(array(
						"target_ext"    =>  $content['reference']["list"][$key][3],
						"image_name"    =>  $content['reference']["list"][$key][2] . '.' . $content['reference']["list"][$key][3],
						"thumb_name"    =>  md5($content['reference']["list"][$key][2].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
					));

					if($thumb_image != false) {
						echo "<option value=\"".$content['reference']["list"][$key][0]."\">";
						$img_name = html($content['reference']["list"][$key][1]);
						echo $img_name."</option>\n";

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
			<div class="col-sm-auto btn-col">
		<button type="button" class="modalButton btn btn-sm btn-blue" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="filebrowser.php?opt=3&amp;target=nolist"><i class="fa-solid fa-folder-open fa-fw" aria-hidden="true"></i></button>
		<button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cimage_list);"><i class="fa-solid fa-angle-up fa-fw" aria-hidden="true"></i></button>
		<button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cimage_list);"><i class="fa-solid fa-angle-down fa-fw" aria-hidden="true"></i></button>
		<button type="button" class="btn btn-sm btn-danger" onclick="removeSelectedOptions(document.articlecontent.cimage_list);" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>"><i class="fa-regular fa-trash-alt fa-fw" aria-hidden="true"></i></button>
	</div>
</div>

<?php if($img_thumbs): ?>
	<div class="form-group row g-2">
		<div class="col-sm-10 offset-sm-2">
			<?php echo $img_thumbs; ?>
		</div>
	</div>
<?php endif; ?>

<div class="form-group row g-2">
	<label for="creference_caption" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_caption'] ?></label>
	<div class="col-sm-10">
		<textarea name="creference_caption" rows="5" class="form-control form-control-sm field-sizing-content field-sizing-content-5" id="creference_caption"><?php echo html($content['reference']["caption"]) ?></textarea>
	</div>
</div>

<div class="form-group row g-2 align-items-center">
	<label for="creference_zoom" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_reference_zoom'] ?></label>
	<div class="col-sm-10">
		<div class="form-check form-check-inline">
			<input name="creference_zoom" type="checkbox" id="creference_zoom" value="1" class="form-check-input" <?php is_checked(1, $content['reference']["zoom"]); ?> />
			<label class="form-check-label" for="creference_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>
		</div>
	</div>
</div>

<hr />

<div class="form-group row g-2 align-items-center">
	<label class="col-sm-2 col-form-label text-end text-muted fw-bold"><?php echo $BL['be_cnt_reference_largetext']; ?></label>
	<div class="col-sm-10"></div>
</div>

<div class="form-group row g-2 align-items-center">
	<label for="creference_width" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ftptakeover_size'] ?></label>
	<div class="col-sm-auto">
		<div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
			
			<input name="creference_width" type="text" class="form-control form-control-sm" id="creference_width" style="width: 50px;" maxlength="5" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['reference']["width"] ?>" />
			
				<span class="input-group-text">px</span>
			
		</div>
	</div>
	<div class="col-sm-auto">
		<div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_maxh'] ?></span>
			
			<input name="creference_height" type="text" class="form-control form-control-sm" id="creference_height" style="width: 50px;" maxlength="5" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['reference']["height"] ?>" />
			
				<span class="input-group-text">px</span>
			
		</div>
	</div>
	<div class="col-sm-auto">
		<div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_reference_border'] ?></span>
			
			<input name="creference_border" type="text" class="form-control form-control-sm" id="creference_border" style="width: 50px;" maxlength="3" onkeyup="if(!parseInt(this.value,10)) this.value='0';" value="<?php echo $content['reference']["border"] ?>" />
			
				<span class="input-group-text">px</span>
			
		</div>
	</div>
</div>

<hr />

<div class="form-group row g-2 align-items-center">
	<label class="col-sm-2 col-form-label text-end text-muted fw-bold"><?php echo $BL['be_cnt_reference_aligntext'] ?></label>
	<div class="col-sm-10"></div>
</div>

<div class="form-group row g-2 align-items-center">
	<label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_reference_basis'] ?></label>
	<div class="col-sm-10">
		<div class="d-flex flex-wrap align-items-center gap-2">
			<div class="form-check form-check-inline me-3">
				<input name="creference_basis" id="creference_basis_0" type="radio" value="0" class="form-check-input" <?php is_checked(0, $content["reference"]["basis"]); ?> />
				<label class="form-check-label" for="creference_basis_0"><?php echo $BL['be_cnt_reference_horizontal'] ?></label>
			</div>
			<div class="form-check form-check-inline me-4">
				<input name="creference_basis" id="creference_basis_1" type="radio" value="1" class="form-check-input" <?php is_checked(1, $content["reference"]["basis"]); ?> />
				<label class="form-check-label" for="creference_basis_1"><?php echo $BL['be_cnt_reference_vertical'] ?></label>
			</div>
			
			<select name="creference_pos" id="creference_pos" class="form-select form-select-sm">
				<option value="0" <?php is_selected(0, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_default'] ?></option>
				<option value="1" <?php is_selected(1, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_left'] . ', ' . $BL['be_admin_page_top'] ?></option>
				<option value="2" <?php is_selected(2, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_left'] . ', ' . $BL['be_cnt_reference_middle'] ?></option>
				<option value="3" <?php is_selected(3, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_left'] . ', ' . $BL['be_admin_page_bottom'] ?></option>
				<option value="4" <?php is_selected(4, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_center'] . ', ' . $BL['be_admin_page_top'] ?></option>
				<option value="5" <?php is_selected(5, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_center'] . ', ' . $BL['be_cnt_reference_middle'] ?></option>
				<option value="6" <?php is_selected(6, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_center'] . ', ' . $BL['be_admin_page_bottom'] ?></option>
				<option value="7" <?php is_selected(7, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_right'] . ', ' . $BL['be_admin_page_top'] ?></option>
				<option value="8" <?php is_selected(8, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_right'] . ', ' . $BL['be_cnt_reference_middle'] ?></option>
				<option value="9" <?php is_selected(9, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_right'] . ', ' . $BL['be_admin_page_bottom'] ?></option>
			</select>
		</div>
	</div>
</div>

<div class="form-group row g-2 align-items-center">
	<label for="creference_blockwidth" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_reference_block'] ?></label>
	<div class="col-sm-auto">
		<div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
			
			<input name="creference_blockwidth" type="text" class="form-control form-control-sm" id="creference_blockwidth" style="width: 50px;" maxlength="5" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['reference']["blockwidth"] ?>" />
			
				<span class="input-group-text">px</span>
			
		</div>
	</div>
	<div class="col-sm-auto">
		<div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_maxh'] ?></span>
			
			<input name="creference_blockheight" type="text" class="form-control form-control-sm" id="creference_blockheight" style="width: 50px;" maxlength="5" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['reference']["blockheight"] ?>" />
			
				<span class="input-group-text">px</span>
			
		</div>
	</div>
	<div class="col-sm-auto">
		<div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_imagespace'] ?></span>
			
			<input name="creference_space" type="text" class="form-control form-control-sm" id="creference_space" style="width: 50px;" maxlength="2" onkeyup="if(!parseInt(this.value,10)) this.value='0';" value="<?php echo $content['reference']["space"] ?>" />
			
				<span class="input-group-text">px</span>
			
		</div>
	</div>
	<div class="col-sm-auto">
		<div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_reference_border'] ?></span>
			
			<input name="creference_listborder" type="text" class="form-control form-control-sm" id="creference_listborder" style="width: 50px;" maxlength="3" onkeyup="if(!parseInt(this.value,10)) this.value='0';" value="<?php echo $content['reference']["listborder"] ?>" />
			
				<span class="input-group-text">px</span>
			
		</div>
	</div>
</div>
