<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//images

$caption_box    = '';
$img_thumbs     = '';
$imgx           = 0;

if(empty($template_default['imagegallery_default_column'])) {
    $template_default['imagegallery_default_column'] = 1;
} else {
    $template_default['imagegallery_default_column'] = intval($template_default['imagegallery_default_column']);
    if(empty($template_default['imagegallery_default_column'])) {
        $template_default['imagegallery_default_column'] = 1;
    }
}

$template_default['imagegallery_default_width']  = isset($template_default['imagegallery_default_width']) ? $template_default['imagegallery_default_width'] : '' ;
$template_default['imagegallery_default_height'] = isset($template_default['imagegallery_default_height']) ? $template_default['imagegallery_default_height'] : '' ;
$template_default['imagegallery_default_space']  = isset($template_default['imagegallery_default_space']) ? $template_default['imagegallery_default_space'] : '' ;

if(!isset($content['image_list'])) {

    $content['image_list'] = array(

            'pos'       => 0,
            'width'     => $template_default['imagegallery_default_width'],
            'height'    => $template_default['imagegallery_default_height'],
            'col'       => $template_default['imagegallery_default_column'],
            'space'     => $template_default['imagegallery_default_space'],
            'zoom'      => 0,
            'caption'   => '',
            'lightbox'  => 0,
            'nocaption' => 0,
            'crop'      => 0,
            'random'    => 0,
            'limit'     => 0

    );
}

if(!isset($content['image_list']['limit'])) {
    $content['image_list']['limit'] = 0;
}

?>

<div class="form-group align-items-center row g-2">
  <label for="template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="form-select form-select-sm">
<?php

    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/imagetable');
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
	<label for="cimage_list" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ctype_images']; ?></label>
		<div class="col">
        <select name="cimage_list[]" size="<?php echo isset($content["image_list"]) && count($content["image_list"]) ? count($content["image_list"])+6 : 6 ?>" multiple="multiple" class="form-select form-select-sm" id="cimage_list">
<?php
if(isset($content['image_list']['images']) && is_array($content['image_list']['images']) && count($content['image_list']['images'])) {

    // browse images and list available
    // will be visible only when accessible
    foreach($content['image_list']['images'] as $key => $value) {

        $caption_box .= html($content['image_list']['images'][$key][6])."\n";

        // 0   :1       :2   :3        :4    :5     :6      :7       :8
        // dbid:filename:hash:extension:width:height:caption:position:zoom
        $thumb_image = get_cached_image(array(
            "target_ext"    =>  $content['image_list']['images'][$key][3],
            "image_name"    =>  $content['image_list']['images'][$key][2] . '.' . $content['image_list']['images'][$key][3],
            "thumb_name"    =>  md5($content['image_list']['images'][$key][2].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
        ));

        if($thumb_image != false) {

            // image found
            echo '<option value="' . $content['image_list']['images'][$key][0] . '">';
            $img_name = html($content['image_list']['images'][$key][1]);
            echo $img_name . "</option>\n";


            if($imgx) {
                $img_thumbs .= '';
            }
            $img_thumbs .= '<img class="m-1" src="' . $thumb_image['src'] .'" '.$thumb_image[3].' alt="'.$img_name.'" data-bs-toggle="tooltip" title="'.$img_name.'">';

            $imgx++;
        }

    }

}

?>
    </select>
    </div>

            <div class="col-sm-auto btn-col">
        <button type="button" class="modalButton btn btn-sm btn-blue" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="filebrowser.php?opt=1&amp;target=nolist"><i class="fa-solid fa-folder-open fa-fw" aria-hidden="true"></i></button>
        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cimage_list);"><i class="fa-solid fa-angle-up fa-fw" aria-hidden="true"></i></button>
        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cimage_list);"><i class="fa-solid fa-angle-down fa-fw" aria-hidden="true"></i></button>
        <button type="button" class="btn btn-sm btn-danger" onclick="removeSelectedOptions(document.articlecontent.cimage_list);" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>"><i class="fa-regular fa-trash-alt fa-fw" aria-hidden="true"></i></button>
    </div>
</div>

<?php
if($img_thumbs) {
    echo '<div class="row">
    		<label for="template" class="col-sm-2 col-form-label text-end"></label>
    		<div class="col">
       			'.$img_thumbs.'
        	</div>
        </div>';
}

?>

<hr />

<div class="form-group align-items-center row g-2">
  <label for="cimage_pos" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_position'] ?></label>
  <div class="col">
    <input type="hidden" name="cimage_pos" id="cimage_pos" value="<?php echo $content['image_list']['pos'] ?>">
    <div class="input-group input-group-sm">
        <div id="imgpos0" class="btn <?php echo ($content['image_list']['pos']==0 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos0.svg" alt="" width="16" height="16" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos0i'] ?>"></div>
        <div id="imgpos1" class="btn <?php echo ($content['image_list']['pos']==1 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos1.svg" alt="" width="16" height="16" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos1i'] ?>"></div>
        <div id="imgpos2" class="btn <?php echo ($content['image_list']['pos']==2 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos2.svg" alt="" width="16" height="16" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos2i'] ?>"></div>
        <div id="imgpos3" class="btn <?php echo ($content['image_list']['pos']==3 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos3.svg" alt="" width="16" height="16" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos3i'] ?>"></div>
        <div id="imgpos4" class="btn <?php echo ($content['image_list']['pos']==4 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos4.svg" alt="" width="16" height="16" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos4i'] ?>"></div>
        <div id="imgpos5" class="btn <?php echo ($content['image_list']['pos']==5 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos5.svg" alt="" width="16" height="16" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos5i'] ?>"></div>
        <div id="imgpos6" class="btn <?php echo ($content['image_list']['pos']==6 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos6.svg" alt="" width="16" height="16" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos6i'] ?>"></div>
        <div id="imgpos7" class="btn <?php echo ($content['image_list']['pos']==7 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos7.svg" alt="" width="16" height="16" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_pos7i'] ?>"></div>
        <span class="input-group-text">
            <input type="checkbox" class="form-check-input me-1" name="cimage_usetable" id="cimage_usetable" value="1" <?php is_checked(1, empty($content['image_list']['usetable']) ? 0 : 1); ?> />
            <label for="cimage_usetable" class="form-check-label"><?php echo $BL['be_admin_page_table'] ?></label>
        </span>
    </div>
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="cimage_width" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ftptakeover_size'] ?></label>

  <div class="col-sm-auto">
    <div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
			
			<input name="cimage_width" type="text" class="form-control" id="cimage_width" style="width: 50px;" size="4" maxlength="4" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['width']) ? $template_default['imagegallery_default_width'] : $content['image_list']['width']; ?>">
			
				<span class="input-group-text">px</span>
			
		</div>
  </div>

  <div class="col-sm-auto">
    <div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_maxh'] ?></span>
			
			<input name="cimage_height" type="text" class="form-control" id="cimage_height" style="width: 50px;" size="4" maxlength="4" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['height']) ? $template_default['imagegallery_default_height'] : $content['image_list']['height']; ?>">
			
				<span class="input-group-text">px</span>
			
		</div>
  </div>

  <div class="col-sm-auto">
    <div class="form-check form-check-inline">
			<input class="form-check-input" type="checkbox" name="cimage_crop" id="cimage_crop" value="1" <?php is_checked(1, $content['image_list']['crop']); ?> />
			<label class="form-check-label" for="cimage_crop"><?php echo $BL['be_image_crop'] ?></label>
		</div>
	</div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="cimage_limit" class="col-sm-2 col-form-label text-end"><?php echo $BL['limit_image_from_list'] ?></label>
  <div class="col-sm-auto">
		<select name="cimage_limit" id="cimage_limit" class="form-select form-select-sm">
			<option value="0"<?php is_selected(0, $content['image_list']['limit']); ?>><?php echo $BL['be_ftptakeover_all'] ?></option>
			<?php
			// take max images from list
			if($imgx) {
					$max_limit = $imgx + 10;
			} else {
					$max_limit = 10;
			}
			for($max_image_limit = 1; $max_image_limit <= $max_limit; $max_image_limit++) {

					echo '<option value="'.$max_image_limit.'" ';
					is_selected($max_image_limit, $content['image_list']['limit']);
					echo '>'.$max_image_limit."</option>\n";
			}
			?>
		</select>
	</div>
	<div class="col">
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="cimage_random" type="checkbox" id="cimage_random" value="1" <?php is_checked(1, $content['image_list']['random']); ?> />
			<label class="form-check-label" for="cimage_random"><?php echo $BL['random_image'] ?></label>
		</div>
	</div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="cimage_col" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_column'] ?></label>
  <div class="col-sm-auto">
		<select name="cimage_col" id="cimage_col" class="form-select form-select-sm">
			<?php
			// list select menu for max image columns
			for($max_image_col = 1; $max_image_col <= 25; $max_image_col++) {
					echo '<option value="'.$max_image_col.'" ';
					is_selected($max_image_col, $content['image_list']['col']);
					echo '>'.$max_image_col."</option>\n";
			}
			?>
		</select>
	</div>
	<div class="col-sm-auto">
    <div class="input-group input-group-sm">
			
				<span class="input-group-text"><?php echo $BL['be_cnt_imagespace'] ?></span>
			
      <input name="cimage_space" type="text" class="form-control" id="cimage_space" maxlength="3" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['space']) ? $template_default['imagegallery_default_space'] : $content['image_list']['space']; ?>" />
			
				<span class="input-group-text">px</span>
			
		</div>
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="cimage_zoom" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_behavior'] ?></label>
  <div class="col">
  <div class="form-check form-check-inline">
		<input class="form-check-input" name="cimage_zoom" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, $content['image_list']['zoom']); ?>/>
		<label class="form-check-label" for="cimage_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>
	</div>
  <div class="form-check form-check-inline">
		<input name="cimage_lightbox" class="form-check-input" type="checkbox" id="cimage_lightbox" value="1" <?php is_checked(1, $content['image_list']['lightbox']); ?> onchange="if(this.checked){document.getElementById('cimage_zoom').checked=true;}" />
		<label class="form-check-label" for="cimage_lightbox"><?php echo $BL['be_cnt_lightbox'] ?></label>
	</div>
  <div class="form-check form-check-inline">
		<input class="form-check-input" name="cimage_nocaption" type="checkbox" id="cimage_nocaption" value="1" <?php is_checked(1, $content['image_list']['nocaption']); ?> />
		<label class="form-check-label" for="cimage_nocaption"><?php echo $BL['be_cnt_imglist_nocaption'] ?></label>
	</div>
  </div>
</div>

<div class="form-group row g-2">
  <label for="cimage_caption" class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_cnt_caption'] ?></label>
  <div class="col">
    <textarea name="cimage_caption" cols="40" rows="3" wrap="off" class="form-control form-control-sm" id="cimage_caption"><?php echo $caption_box; ?></textarea>
    <div class="pt-2">
        <?php echo $BL['be_cnt_caption']; ?>
        |
        <?php echo $BL['be_caption_alt']; ?>
        |
        <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
        |
        <?php echo $BL['be_caption_title']; ?>
        |
        <?php echo $BL['be_copyright']; ?>&nbsp;&crarr;&nbsp;&hellip;
    </div>
  </div>
</div>
