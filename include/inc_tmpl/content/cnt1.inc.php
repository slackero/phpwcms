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


//image with text

$template_default['article']['image_default_width'] = isset($template_default['article']['image_default_width']) ? $template_default['article']['image_default_width'] : '' ;
$template_default['article']['image_default_height']= isset($template_default['article']['image_default_height']) ? $template_default['article']['image_default_height'] : '' ;

if(empty($content['cimage']['cimage_crop'])) {
    $content['cimage']['cimage_crop'] = 0;
}

$image_caption = isset($content["image_caption"]) ? explode('|', html_specialchars($content["image_caption"])) : '';
if(isset($image_caption[2])) {
    $image_caption_link = explode(' ',$image_caption[2]);
}

?>

<div class="form-group align-items-center row g-2">
  <label for="template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="form-select form-select-sm">
<?php

    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/imagetext');
if(is_array($tmpllist) && count($tmpllist)) {
    foreach($tmpllist as $val) {
        $selected_val = (isset($content["template"]) && $val == $content["template"]) ? ' selected="selected"' : '';
        $val = html($val);
        echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
    }
}

?>
    </select>
  </div>
</div>

<div class="form-group row g-2">
  <label for="ctext" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_htmltext']; ?></label>
  <div class="col"><?php

$wysiwyg_editor = array(
    'value'     => isset($content["text"]) ? $content["text"] : '',
    'field'     => 'ctext',
    'height'    => '650px',
    'width'     => '100%',
    'rows'      => '15',
    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    'lang'      => 'en'
);

include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

?>
  </div>
</div>

<hr />

<div class="form-group align-items-center row g-2">
  <label for="cimage_name" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_image']; ?></label>
  <div class="col-sm-4">
    <div class="input-group">
      
      <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="filebrowser.php?opt=0&amp;target=nolist" title="<?php echo $BL['be_cnt_openimagebrowser']; ?>"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i></button>
      <input name="cimage_name" type="text" id="cimage_name" class="form-control form-control-sm" value="<?php echo isset($content['image_name']) ? html($content['image_name']) : ''; ?>" maxlength="250" onfocus="this.blur()" />
      <a href="#" id="cimage_delete_button" class="btn btn-sm btn-danger trash<?php echo empty($content['image_id']) ? ' disabled' : '' ?>" style="<?php echo empty($content['image_id']) ? 'opacity: 0.5; pointer-events: none;' : '' ?>" type="button" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage']; ?>" onclick="if ($(this).hasClass('disabled')) return false; bsConfirmDanger('<?php echo js_singlequote($BL['be_image_delete_js']); ?>' + (document.articlecontent.cimage_name.value ? '\n[' + document.articlecontent.cimage_name.value + ']' : ''), function() { document.articlecontent.cimage_name.value='';document.articlecontent.cimage_id.value='0'; if (typeof onImageSelected === 'function') onImageSelected('_', '0', ''); }, '<?php echo js_singlequote($BL['be_yes']); ?>', '<?php echo js_singlequote($BL['be_no']); ?>'); this.blur();return false;"><i class="fa fa-trash fa-fw" aria-hidden="true"></i></a>
      
    </div>
    <input name="cimage_id" type="hidden" value="<?php echo isset($content["image_id"]) ? $content["image_id"] : '' ?>" />
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="cimage_pos" class="col-sm-2 col-form-label text-end"><?php echo  $BL['be_cnt_position'] ?></label>
  <div class="col-sm-auto">
    <select name="cimage_pos" id="cimage_pos" class="form-select form-select-sm">
      <option value="0" <?php
      if(!isset($content["image_pos"])) $content["image_pos"] = 0;
      is_selected(0, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos0'] ?></option>
      <option value="1" <?php is_selected(1, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos1'] ?></option>
      <option value="2" <?php is_selected(2, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos2'] ?></option>
      <option value="3" <?php is_selected(3, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos3'] ?></option>
      <option value="4" <?php is_selected(4, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos4'] ?></option>
      <option value="5" <?php is_selected(5, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos5'] ?></option>
      <option value="6" <?php is_selected(6, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos6'] ?></option>
      <option value="7" <?php is_selected(7, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos7'] ?></option>
      <option value="8" <?php is_selected(8, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos8'] ?></option>
      <option value="9" <?php is_selected(9, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos9'] ?></option>
    </select>
  </div>
  <div class="col-sm-auto mt-2 mt-sm-0">
    <div id="imgpos0" class="btn <?php echo ($content["image_pos"]==0 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos0.svg" alt="" width="16" height="16" border="0"data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos0i'] ?>"></div>
    <div id="imgpos1" class="btn <?php echo ($content["image_pos"]==1 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos1.svg" alt="" width="16" height="16" border="0"data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos1i'] ?>"></div>
    <div id="imgpos2" class="btn <?php echo ($content["image_pos"]==2 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos2.svg" alt="" width="16" height="16" border="0"data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos2i'] ?>"></div>
    <div id="imgpos3" class="btn <?php echo ($content["image_pos"]==3 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos3.svg" alt="" width="16" height="16" border="0"data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos3i'] ?>"></div>
    <div id="imgpos4" class="btn <?php echo ($content["image_pos"]==4 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos4.svg" alt="" width="16" height="16" border="0"data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos4i'] ?>"></div>
    <div id="imgpos5" class="btn <?php echo ($content["image_pos"]==5 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos5.svg" alt="" width="16" height="16" border="0"data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos5i'] ?>"></div>
    <div id="imgpos6" class="btn <?php echo ($content["image_pos"]==6 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos6.svg" alt="" width="16" height="16" border="0"data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos6i'] ?>"></div>
    <div id="imgpos7" class="btn <?php echo ($content["image_pos"]==7 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos7.svg" alt="" width="16" height="16" border="0"data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos7i'] ?>"></div>
    <div id="imgpos8" class="btn <?php echo ($content["image_pos"]==8 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos8.svg" alt="" width="16" height="16" border="0"data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos8i'] ?>"></div>
    <div id="imgpos9" class="btn <?php echo ($content["image_pos"]==9 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos9.svg" alt="" width="16" height="16" border="0"data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos9i'] ?>"></div>
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="cimage_width" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ftptakeover_size'] ?></label>

  <div class="col-sm-auto">
    <div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
			
			<input name="cimage_width" type="text" class="form-control form-control-sm" id="cimage_width" style="width: 50px;" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content["image_width"]) ? $template_default['article']['image_default_width'] : $content["image_width"] ?>" />
			
				<span class="input-group-text">px</span>
			
		</div>
  </div>

  <div class="col-sm-auto">
    <div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_maxh'] ?></span>
			
			<input name="cimage_height" type="text" class="form-control form-control-sm" id="cimage_height" style="width: 50px;" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content["image_height"]) ? $template_default['article']['image_default_height'] : $content["image_height"] ?>" />			
				<span class="input-group-text">px</span>
			
		</div>
  </div>

  <div class="col-sm-auto">
    <div class="form-check form-check-inline">
			<input class="form-check-input" type="checkbox" name="cimage_crop" id="cimage_crop" value="1" <?php is_checked(1, $content['cimage']['cimage_crop']); ?> />
			<label class="form-check-label" for="cimage_crop"><?php echo $BL['be_image_crop'] ?></label>
		</div>
	</div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="cimage_zoom" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_behavior'] ?></label>
  <div class="col">
  <div class="form-check form-check-inline">
		<input name="cimage_zoom" class="form-check-input" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, empty($content["image_zoom"]) ? 0 : 1); ?> />
		<label class="form-check-label" for="cimage_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>
	</div>
  <div class="form-check form-check-inline">
		<input name="cimage_lightbox" class="form-check-input" type="checkbox" id="cimage_lightbox" value="1" <?php is_checked(1, empty($content['cimage']['cimage_lightbox']) ? 0 : 1); ?> onchange="if(this.checked){document.getElementById('cimage_zoom').checked=true;}" />
		<label class="form-check-label" for="cimage_lightbox"><?php echo $BL['be_cnt_lightbox'] ?></label>
	</div>
  <div class="form-check form-check-inline">
		<input name="cimage_nocaption" class="form-check-input" type="checkbox" id="cimage_nocaption" value="1" <?php is_checked(1, empty($content['cimage']['cimage_nocaption']) ? 0 : 1); ?> />
		<label class="form-check-label" for="cimage_nocaption"><?php echo $BL['be_cnt_imglist_nocaption'] ?></label>
	</div>
  </div>
</div>

<hr />

<legend><?php echo $BL['be_cnt_caption'] ?></legend>
	<div class="form-group row g-2">
    <label for="cimage_caption_title" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_profile_label_title'] ?></label>
    <div class="col">
      <textarea name="cimage_caption_title" cols="30" rows="2" class="form-control form-control-sm" id="cimage_caption_title" ><?php echo  isset($image_caption[0]) ? html_specialchars($image_caption[0]) : '' ?></textarea>
    </div>
		<div id="cimage_preview_container" class="col-sm-auto">
			<?php
				if(isset($content['image_hash'])) {
						$thumb_image = get_cached_image(array(
										'target_ext'    =>  $content['image_ext'],
										'image_name'    =>  $content['image_hash'] . '.' . $content['image_ext'],
										'thumb_name'    =>  md5($content['image_hash'].$phpwcms['img_list_width'].$phpwcms['img_list_height'].$phpwcms['sharpen_level'].$phpwcms['colorspace'])
						));

						if($thumb_image != false) {
								echo '<img class="p-1" src="' . $thumb_image['src'] .'" alt="" '.$thumb_image[3].'>';
						}
				} else {
						echo '&nbsp;';
				}
				?>
		</div>
  </div>

	<div class="form-group row g-2">
    <label for="cimage_caption_alt" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_description'] ?></label>
    <div class="col">
      <textarea name="cimage_caption_alt" cols="30" rows="2" class="form-control form-control-sm" id="cimage_caption_alt" ><?php echo isset($image_caption[1]) ? html_specialchars($image_caption[1]) : '' ?></textarea>
    </div>
  </div>

	<div class="form-group align-items-center row g-2">
    <label for="cimage_caption_url" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_page_link'] ?></label>
    <div class="col">
      <input name="cimage_caption_url" type="text" id="cimage_caption_url" class="form-control form-control-sm" value="<?php echo  isset($image_caption_link[0]) ? html_specialchars($image_caption_link[0]) : '' ?>" maxlength="250" />
    </div>
  </div>

	<div class="form-group align-items-center row g-2">
    <label for="cimage_caption_target" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_target'] ?></label>
    <div class="col">
			<select name="cimage_caption_target" id="cimage_caption_target" class="form-select form-select-sm">
				<option value="" <?php
				if(!isset($image_caption_link[1])) $image_caption_link[1] = '';
				is_selected('', $image_caption_link[1]) ?>></option>
				<option value="_blank" <?php is_selected('_blank', $image_caption_link[1]) ?>><?php echo $BL['be_cnt_target1'] ?> (_blank)</option>
				<option value="_top" <?php is_selected('_top', $image_caption_link[1]) ?>><?php echo $BL['be_cnt_target2'] ?> (_top)</option>
				<option value="_self" <?php is_selected('_self', $image_caption_link[1]) ?>><?php echo $BL['be_cnt_target3'] ?> (_self)</option>
				<option value="_parent" <?php is_selected('_parent', $image_caption_link[1]) ?>><?php echo $BL['be_cnt_target4'] ?> (_parent)</option>
			</select>
    </div>
  </div>




