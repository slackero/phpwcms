<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//images <div>

$caption_box    = array();
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

if(!isset($content['image_list']['col'])) {
    
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
            'limit'     => 0,
            'random'    => 0
            
    );
    
}
if(empty($content['image_list']['center_image'])) {
    $content['image_list']['center_image'] = 0;
}

$img_count = isset($content["image_list"]['images']) && is_array($content["image_list"]['images']) ? count($content["image_list"]['images']) : 0;

?>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="custom-select form-control form-control-sm">
<?php
    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;
    // templates for frontend login
    $tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/images');
    if(is_array($tmpllist) && count($tmpllist)) {
        foreach($tmpllist as $val) {
            // do not show listmode templates
            if(substr($val, 0, 5) == 'list.') {
                continue;
            }
            $selected_val = (isset($content["image_template"]) && $val == $content["image_template"]) ? ' selected="selected"' : '';
            $val = html($val);
            echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
        }
    }
?>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cimage_center" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_image_align'] ?></label>
  <div class="col-sm-4">
    <select name="cimage_center" id="cimage_center" class="custom-select form-control form-control-sm">
        <option value="0"<?php is_selected(0, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagenocenter'] ?></option>
        <option value="1"<?php is_selected(1, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagecenter'] ?></option>
        <option value="2"<?php is_selected(2, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagecenterh'] ?></option>
        <option value="3"<?php is_selected(3, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagecenterv'] ?></option>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_size'] ?></label>

  <div class="col-sm-auto my-2 my-sm-0">
    <div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
			</div>
    	<input name="cimage_width" type="text" class="form-control form-control-sm" id="cimage_width" style="width: 50px;" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo empty($content['image_list']['width']) ? $template_default['imagegallery_default_width'] : $content['image_list']['width']; ?>" />
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
    	<input name="cimage_height" type="text" class="form-control form-control-sm" id="cimage_height" style="width: 50px;" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo empty($content['image_list']['height']) ? $template_default['imagegallery_default_height'] : $content['image_list']['height']; ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
  </div>  
  <div class="col-sm-auto my-2 my-sm-0 ml-sm-3">
    <div class="form-check form-check-inline">
			<input class="form-check-input" type="checkbox" name="cimage_crop" id="cimage_crop" value="1" <?php is_checked(1, $content['image_list']['crop']); ?> />
			<label class="form-check-label" for="cimage_crop"><?php echo $BL['be_image_crop'] ?></label>
		</div>
	</div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cimage_limit" class="col-sm-2 col-form-label text-right"><?php echo $BL['limit_image_from_list'] ?></label>
  <div class="col-sm-auto">
		<input name="cimage_limit" type="text" class="form-control form-control-sm" id="cimage_limit" maxlength="3" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['limit']) ? '' : $content['image_list']['limit']; ?>" />
	</div>
	<div class="col">
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="cimage_random" type="checkbox" id="cimage_random" value="1" <?php is_checked(1, empty($content['image_list']['random']) ? 0 : 1); ?> />
			<label class="form-check-label" for="cimage_random"><?php echo $BL['random_image'] ?></label>
		</div>
	</div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cimage_col" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_column'] ?></label>
  <div class="col-sm-auto">
		<select class="custom-select form-control form-control-sm" name="cimage_col" id="cimage_col">
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
	<div class="col-sm-auto my-2 my-sm-0 ml-sm-3">
    <div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_cnt_imagespace'] ?></span>
			</div>
      <input name="cimage_space" type="text" class="form-control form-control-sm" id="cimage_space" maxlength="3" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['space']) ? $template_default['imagegallery_default_space'] : $content['image_list']['space']; ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
  </div> 
</div>

<script type="text/javascript">
    function setCimageCenterInactive() {
        var cih = getObjectById('cimage_width');
        var ciw = getObjectById('cimage_height');
        var cic = getObjectById('cimage_center');
        var ccp = getObjectById('cimage_crop');
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
    setCimageCenterInactive();
</script>

<div class="form-group align-items-center form-row">
  <label for="cimage_zoom" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_behavior'] ?></label>
  <div class="col">
    <div class="form-check form-check-inline">
			<input name="cimage_zoom" class="form-check-input" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, $content['image_list']['zoom']); ?> />
			<label class="form-check-label"><?php echo $BL['be_cnt_enlarge'] ?></label>
    </div>
    <div class="form-check form-check-inline">
			<input name="cimage_lightbox" class="form-check-input" type="checkbox" id="cimage_lightbox" value="1" <?php is_checked(1, $content['image_list']['lightbox']); ?> onchange="if(this.checked){getObjectById('cimage_zoom').checked=true;}" />
			<label class="form-check-label"><?php echo $BL['be_cnt_lightbox'] ?></label>
    </div>
    <div class="form-check form-check-inline">
			<input name="cimage_nocaption" class="form-check-input" type="checkbox" id="cimage_nocaption" value="1" <?php is_checked(1, $content['image_list']['nocaption']); ?> />
			<label class="form-check-label"><?php echo $BL['be_cnt_imglist_nocaption'] ?></label>
    </div>
  </div>
</div>

<div class="form-group form-row">
  <label for="cimage_list" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_image'] ?></label>
  <div class="col">
    <div class="form-row">
      <div class="col">
        <select name="cimage_list[]" size="<?php echo $img_count+5 ?>" multiple="multiple" class="custom-select form-control form-control-sm" id="cimage_list">
<?php
if($img_count) {

    // browse images and list available
    // will be visible only when aceessible
    foreach($content['image_list']['images'] as $key => $value) {

        // 0   :1       :2   :3        :4    :5     :6      :7       :8
        // dbid:filename:hash:extension:width:height:caption:position:zoom
        $thumb_image = get_cached_image(array(
            "target_ext"    =>  $content['image_list']['images'][$key][3],
            "image_name"    =>  $content['image_list']['images'][$key][2] . '.' . $content['image_list']['images'][$key][3],
            "thumb_name"    =>  md5($content['image_list']['images'][$key][2].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
        ));

        if($thumb_image != false) {
          // image found
          echo '<option value="' . $content['image_list']['images'][$key][0] . '">';
          $img_name = html($content['image_list']['images'][$key][1]);
          echo $img_name . '</option>'.LF;
          if($imgx == 4) {
              $img_thumbs .= '';
              $imgx = 0;
          }
          if($imgx) {
              $img_thumbs .= '';
          }
          $img_thumbs .= '<img class="mt-2 mr-2 img-fluid" src="' . $thumb_image['src'] .'" '.$thumb_image[3].' alt="'.$img_name.'" data-toggle="tooltip" title="'.$img_name.'" />';
          $caption_box[] = html($content['image_list']['images'][$key][6]);
          $imgx++;
        }
    }
}

?>
        </select>
      </div>
      <div class="col-sm-auto">
        <span data-toggle="tooltip" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>"><button class="modalButton btn btn-sm btn-blue mb-1" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=1&amp;target=nolist"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i></button></span><br>
        <button class="btn btn-sm btn-secondary mb-1" data-toggle="tooltip" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cimage_list);return false;"><i class="fa fa-angle-up fa-fw" aria-hidden="true"></i></button><br>
        <button class="btn btn-sm btn-secondary mb-1" data-toggle="tooltip" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cimage_list);return false;"><i class="fa fa-angle-down fa-fw" aria-hidden="true"></i></button><br>
        <button class="btn btn-sm btn-danger mb-1" onclick="removeSelectedOptions(document.articlecontent.cimage_list);return false;" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>"><i class="fa fa-trash fa-fw" aria-hidden="true"></i></button>
      </div>
    </div>
    <div class="row">
      <div class="col mt-1">
<?php
    if($img_thumbs) {
        echo $img_thumbs;
    }
?>  
      </div>
    </div>
  </div>
</div>

<div class="form-group form-row">
  <label for="cimage_caption" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_caption'] ?></label>
  <div class="col">
    <textarea name="cimage_caption" cols="40" rows="3" wrap="off" class="form-control form-control-sm" id="cimage_caption"><?php echo implode(' '.LF, $caption_box) ?></textarea>
    <div class="my-2">
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

<?php

$wysiwyg_editor = array(
    'value'     => isset($content["text"]) ? $content["text"] : '',
    'field'     => 'ctext',
    'height'    => '250px',
    'width'     => '100%',
    'rows'      => '15',
    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    'lang'      => 'en'
);

include CMSGO_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

?>
