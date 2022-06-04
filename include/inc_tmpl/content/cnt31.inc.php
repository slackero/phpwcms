<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//images special

// some predefinitions
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

$content['image_default'] = array(
    'pos'           => 0,
    'width'         => $template_default['imagegallery_default_width'],
    'height'        => $template_default['imagegallery_default_height'],
    'width_zoom'    => $cmsgo['img_prev_width'],
    'height_zoom'   => $cmsgo['img_prev_height'],
    'col'           => $template_default['imagegallery_default_column'],
    'space'         => $template_default['imagegallery_default_space'],
    'zoom'          => 0,
    'caption'       => '',
    'lightbox'      => 0,
    'nocaption'     => 0,
    'center'        => 0,
    'crop'          => 0,
    'crop_zoom'     => 0,
    'fx1'           => 0,
    'fx2'           => 0,
    'fx3'           => 0,
    'freetext'      => '',
    'images'        => array()
);

$content['image_special'] = isset($content['image_special']) ? array_merge($content['image_default'], $content['image_special']) : $content['image_default'];

$tab_fieldgroup_templates = array();

if(isset($template_default['settings']['imagespecial_custom_fields']) && is_array($template_default['settings']['imagespecial_custom_fields']) && count($template_default['settings']['imagespecial_custom_fields'])) {
    $tab_fieldgroups = $template_default['settings']['imagespecial_custom_fields'];
    foreach($template_default['settings']['imagespecial_custom_fields'] as $key => $tab_fieldgroup) {
        $tab_fieldgroup_templates[ $tab_fieldgroup['template'] ] = $key;
    }
} else {
    $tab_fieldgroups = array();
}

?>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="custom-select form-control form-control-sm">
<?php

    $tab_fieldgroups_active = isset($tab_fieldgroup_templates['default']) ? $tab_fieldgroup_templates['default'] : '';

    echo '<option value=""'.(empty($content["image_template"]) ? ' selected="selected"' : '').'>'.$BL['be_admin_tmpl_default'].'</option>'.LF;

    $tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/imagespecial');

    if(is_array($tmpllist) && count($tmpllist)) {
        foreach($tmpllist as $val) {
            // do not show listmode templates
            if(substr($val, 0, 5) == 'list.') {
                continue;
            }

            if(isset($content["image_template"]) && $val == $content["image_template"]){
                $selected_val = ' selected="selected"';
                if(isset($tab_fieldgroup_templates[$val])) {
                    $tab_fieldgroups_active = $tab_fieldgroup_templates[$val];
                } else {
                    // Reset
                    $tab_fieldgroups_active = '';
                }
            } else {
                $selected_val = '';
            }

            $val = html($val);
            echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
        }
    }

?>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_image_align']; ?></label>
  <div class="col-sm-4">
     <select name="cimage_center" id="cimage_center" class="custom-select form-control form-control-sm">
        <option value="0"<?php is_selected(0, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagenocenter'] ?></option>
        <option value="1"<?php is_selected(1, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagecenter'] ?></option>
        <option value="2"<?php is_selected(2, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagecenterh'] ?></option>
        <option value="3"<?php is_selected(3, $content['image_special']['center']); ?>><?php echo $BL['be_cnt_imagecenterv'] ?></option>
    </select>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="be_flashplayer_thumbnail" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_flashplayer_thumbnail'] ?></label>
  <div class="col-sm-auto my-2 my-sm-0">
    <div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_admin_page_width'] ?></span>
			</div>
			<input name="cimage_width" type="text" class="form-control form-control-sm text-right" id="cimage_width" style="width: 50px;" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo $content['image_special']['width']; ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
  </div>
  <div class="col-sm-auto my-2 my-sm-0 ml-sm-3">
    <div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_admin_page_height'] ?></span>
			</div>
			<input name="cimage_height" type="text" class="form-control form-control-sm text-right" id="cimage_height" style="width: 50px;" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo $content['image_special']['height']; ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
  </div>
  <div class="col-sm-auto my-2 my-sm-0 ml-sm-3">
    <div class="form-check form-check-inline">
			<input class="form-check-input" type="checkbox" name="cimage_crop" id="cimage_crop" value="1" <?php is_checked(1, $content['image_special']['crop']); ?> />
			<label class="form-check-label" for="cimage_crop"><?php echo $BL['be_image_crop'] ?></label>
		</div>
	</div>
</div>

<div class="form-group align-items-center form-row">
  <label for="be_cnt_reference_zoom" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_reference_zoom'] ?></label>
  <div class="col-sm-auto my-2 my-sm-0">
    <div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_admin_page_width'] ?></span>
			</div>
			<input name="cimage_width_zoom" type="text" class="form-control form-control-sm text-right" id="cimage_width_zoom" style="width: 50px;" size="4" maxlength="4" value="<?php echo $content['image_special']['width_zoom']; ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
  </div>
  <div class="col-sm-auto my-2 my-sm-0 ml-sm-3">
    <div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_admin_page_height'] ?></span>
			</div>
			<input name="cimage_height_zoom" type="text" class="form-control form-control-sm text-right" id="cimage_height_zoom" style="width: 50px;" size="4" maxlength="4" value="<?php echo $content['image_special']['height_zoom']; ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
  </div>
  <div class="col-sm-auto my-2 my-sm-0 ml-sm-3">
    <div class="form-check form-check-inline">
    	<input class="form-check-input" type="checkbox" name="cimage_crop_zoom" id="cimage_crop_zoom" value="1" <?php is_checked(1, $content['image_special']['crop']); ?> />
			<label class="form-check-label" for="be_image_cropit"><?php echo $BL['be_image_cropit'] ?></label>
		</div>
	</div>
</div>

<div class="form-group form-row align-items-center">
	<label for="be_cnt_column" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_column'] ?></label>
	<div class="col-sm-auto">
	<select class="custom-select form-control form-control-sm" name="cimage_col" id="cimage_col">
			<?php
			// list select menu for max image columns
			for($max_image_col = 1; $max_image_col <= 25; $max_image_col++) {
					echo '<option value="'.$max_image_col.'" ';
					is_selected($max_image_col, $content['image_special']['col']);
					echo '>'.$max_image_col.'</option>'.LF;
			}
			?>
	</select>
	</div>
	<div class="col-sm-auto my-2 my-sm-0 ml-sm-3">
    <div class="input-group input-group-sm">
			<div class="input-group-prepend">
				<span class="input-group-text"><?php echo $BL['be_cnt_imagespace'] ?></span>
			</div>
				<input class="form-control form-control-sm text-right" name="cimage_space" id="cimage_space" value="1" type="text" style="width: 50px;" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['image_special']['space']; ?>" />
			<div class="input-group-append">
				<span class="input-group-text">px</span>
			</div>
		</div>
  </div>
</div>

<div class="form-group align-items-center form-row">
	<label for="be_cnt_behavior" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_behavior'] ?></label>
	<div class="form-check form-check-inline col-sm-auto">
		<input class="form-check-input" id="cimage_zoom" name="cimage_zoom" type="checkbox" value="1"<?php is_checked(1, $content['image_special']['zoom']); ?> />
		<label class="form-check-label" for="cimage_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>
	</div>
	<div class="form-check form-check-inline col-sm-auto">
		<input class="form-check-input" id="cimage_lightbox" name="cimage_lightbox" type="checkbox" value="1"<?php is_checked(1, $content['image_special']['lightbox']); ?> onchange="if(this.checked){getObjectById('cimage_zoom').checked=true;}" />
		<label class="form-check-label" for="cimage_lightbox"><?php echo $BL['be_cnt_lightbox'] ?></label>
	</div>
	<div class="form-check form-check-inline col-sm-auto">
		<input class="form-check-input" id="cimage_nocaption" name="cimage_nocaption" type="checkbox" value="1"<?php is_checked(1, $content['image_special']['nocaption']); ?> />
		<label class="form-check-label" for="cimage_nocaption"><?php echo $BL['be_cnt_imglist_nocaption'] ?></label>
	</div>
</div>

<div class="form-group align-items-center form-row">
	<label class="col-sm-2 col-form-label text-right"></label>
	<div class="form-check form-check-inline col-sm-auto">
		<input class="form-check-input" id="cimage_fx1" name="cimage_fx1" type="checkbox" value="1"<?php is_checked(1, $content['image_special']['fx1']); ?> />
		<label class="form-check-label" for="cimage_fx1"><?php echo $BL['be_fx_1'] ?></label>
	</div>
	<div class="form-check form-check-inline col-sm-auto">
		<input class="form-check-input" id="cimage_fx2" name="cimage_fx2" type="checkbox" value="1"<?php is_checked(1, $content['image_special']['fx2']); ?> />
		<label class="form-check-label" for="cimage_fx2"><?php echo $BL['be_fx_2'] ?></label>
	</div>
	<div class="form-check form-check-inline col-sm-auto">
		<input class="form-check-input" id="cimage_fx3" name="cimage_fx3" type="checkbox" value="1"<?php is_checked(1, $content['image_special']['fx3']); ?> />
		<label class="form-check-label" for="cimage_fx3"><?php echo $BL['be_fx_3'] ?></label>
	</div>
</div>

<hr />

<div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right"></label>
    <div class="col">
        <button class="btn btn-blue btn-sm" onclick="return addNewImage('top');">
           <i class="fa fa-plus"></i> <?php echo $BL['be_ctype_images'] ?> <?php echo $BL['be_article_cnt_add'] ?>
        </button>
    </div>
</div>

<ul id="images" role="tablist" class="dropable-list p-0">

<?php

    // Sort/Up Down Title
    $sort_up_down = $BL['be_func_struct_sort_up'] . ' / '. $BL['be_func_struct_sort_down'];

    if($tab_fieldgroups_active && isset($template_default['settings']['imagespecial_custom_fields'][$tab_fieldgroups_active])) {
        $tab_fieldgroup =& $template_default['settings']['imagespecial_custom_fields'][$tab_fieldgroups_active];
    } else {
        $tab_fieldgroup = null;
    }
    if($tab_fieldgroup !== null && isset($tab_fieldgroup['fields']) && is_array($tab_fieldgroup['fields']) && count($tab_fieldgroup['fields'])) {
        $custom_tab_fields = array_keys($tab_fieldgroup['fields']);
    } else {
        $custom_tab_fields = array();
        $tab_fieldgroup = null;
    }

    $value['custom_field_items'] = $custom_tab_fields;
    $custom_tab_fields_hidden = array();
    $custom_tab_field_types = array('str', 'textarea', 'option', 'select', 'int', 'float', 'bool', 'file', 'image');

    // loop available image entries
    foreach($content['image_special']['images'] as $key => $value) {

        if(isset($value['custom_fields']) && is_array($value['custom_fields']) && count($value['custom_fields'])) {

            if(count($custom_tab_fields)) {
                $value['custom_field_items'] = array_unique( array_merge($custom_tab_fields, array_keys($value['custom_fields'])) );
            } else {
                $value['custom_field_items'] = array_keys($value['custom_fields']);
            }

        } else {
            $value['custom_field_items'] = $custom_tab_fields;
        }

?>


  <li id="image_<?php echo $key ?>" class="card my-3 p-0 sortme">

    <div class="card-header p-2 border-1" role="tab" id="heading_<?php echo $key ?>">
        <div class="row align-items-center">
            <div class="col-sm-auto text-right pr-0">
                <em data-toggle="tooltip" title="<?php echo $sort_up_down; ?>" class="handle text-success">
                    <span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-sort fa-stack-1x fa-inverse"></i></span>
                </em>
            </div>
            <div class="col-auto">
              <div id="img_previewsmall_<?php echo $key ?>" class="backend_previewsmall_img"></div>
            </div>
            <div class="col">
                <h2># <?php echo $key ?> - <?php echo html($value['caption']) ?></h2>
            </div>
            <div class="col-sm-auto text-right">
                <a class="btn btn-sm btn-blue" data-toggle="collapse" href="#collapse_<?php echo $key ?>">
                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </a>
                <a class="btn btn-sm btn-danger" href="#" onclick="return deleteImgElement('image_<?php echo $key ?>');">
                    <i class="far fa-trash-alt"></i>
                </a>
            </div>
        </div>
    </div>

    <div id="collapse_<?php echo $key ?>" class="collapse <?php echo (0 !== $key) ?: 'show'; ?>" data-parent="#images">
      <div class="card-body ">
        <div class="row mb-3">
          <div class="col-sm-6">

						<div class="form-group align-items-center">
							<input name="cimage_id_thumb[<?php echo $key ?>]" id="cimage_id_thumb_<?php echo $key ?>" type="hidden" value="<?php echo $value['thumb_id'] ?>" />
							<input name="cimage_sort[<?php echo $key ?>]" id="cimage_sort_<?php echo $key ?>" type="hidden" value="<?php echo $value['sort'] ?>" />
							<label><?php echo $BL['be_flashplayer_thumbnail'] ?></label>
							<div class="input-group">
								<span class="input-group-prepend">
									<button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=8&target=nolist&entry_id=thumb_<?php echo $key ?>" ></button>
								</span>
								<input name="cimage_name_thumb[<?php echo $key ?>]" type="text" id="cimage_name_thumb_<?php echo $key ?>" class="form-control form-control-sm" value="<?php echo html($value['thumb_name']) ?>" maxlength="250" onfocus="this.blur()" />
								<span class="input-group-append">
									<a href="#" class="btn btn-sm btn-danger trash" type="button" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData('thumb_<?php echo $key ?>', this);"></a>
								</span>
							</div>
						</div>

						<div class="form-group align-items-center">
							<input name="cimage_id_zoom[<?php echo $key ?>]" id="cimage_id_zoom_<?php echo $key ?>" type="hidden" value="<?php echo $value['zoom_id'] ?>" />
							<input name="cimage_sort[<?php echo $key ?>]" id="cimage_sort_<?php echo $key ?>" type="hidden" value="<?php echo $value['sort'] ?>" />
							<label><?php echo $BL['be_image_zoom'] ?></label>
							<div class="input-group">
								<span class="input-group-prepend">
									<button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=8&target=nolist&entry_id=zoom_<?php echo $key ?>" ></button>
								</span>
								<input name="cimage_name_zoom[<?php echo $key ?>]" type="text" id="cimage_name_zoom_<?php echo $key ?>" class="form-control form-control-sm" value="<?php echo html($value['zoom_name']) ?>" maxlength="250" onfocus="this.blur()" />
								<span class="input-group-append">
									<a href="#" class="btn btn-sm btn-danger trash" type="button" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData('zoom_<?php echo $key ?>', this);"></a>
								</span>
							</div>
						</div>

            <div id="img_preview_<?php echo $key ?>" class="backend_preview_img"></div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label><?php echo $BL['be_cnt_caption'] ?></label>
              <textarea name="cimage_caption[<?php echo $key ?>]" id="cimage_caption_<?php echo $key ?>" class="form-control form-control-sm" cols="30" rows="2"><?php echo html($value['caption']) ?></textarea>
              <span class="small">
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
            </div>

            <div class="form-group">
              <label><?php echo $BL['be_cnt_infotext'] ?></label>
              <textarea name="cimage_freetext[<?php echo $key ?>]" id="cimage_freetext_<?php echo $key ?>" class="form-control form-control-sm" cols="30" rows="2"><?php echo html(empty($value['freetext']) ? '' : $value['freetext']) ?></textarea>
            </div>

            <div class="form-group mb-0">
              <label><?php echo $BL['be_profile_label_website'] ?></label>
              <input type="text" name="cimage_url[<?php echo $key ?>]" id="cimage_url_<?php echo $key ?>" class="form-control form-control-sm" size="30" value="<?php echo html($value['url']) ?>" />
            </div>

          </div>
        </div>


<?php
if($value['custom_field_items']):
    foreach($value['custom_field_items'] as $custom_field_key => $custom_field):
        // send fields not defined as hidden values, should ensure not loosing values
        if(isset($value['custom_fields'][$custom_field])) {
            if(!isset($tab_fieldgroup['fields'][$custom_field])) {
                // do not store if the value is an empty string
                if(is_array($value['custom_fields'][$custom_field]) && $value['custom_fields'][$custom_field]) {
                    $custom_tab_fields_hidden[] = '<input type="hidden" name="customfield['.$key.']['.$custom_field.']" value="'.html(serialize($value['custom_fields'][$custom_field])).'" />';
                } elseif($value['custom_fields'][$custom_field] !== '') {
                    $custom_tab_fields_hidden[] = '<input type="hidden" name="customfield['.$key.']['.$custom_field.']" value="'.html($value['custom_fields'][$custom_field]).'" />';
                }
                continue;
            } elseif(is_string($value['custom_fields'][$custom_field]) && substr($value['custom_fields'][$custom_field], 0, 2) === 'a:') {
                $_unserialze = @unserialize($value['custom_fields'][$custom_field]);
                if ($_unserialze === false) {
                    continue;
                }
                $value['custom_fields'][$custom_field] = $_unserialze;
            }
        }

        $custom_field_placeholder = isset($tab_fieldgroup['fields'][$custom_field]['placeholder']) && $tab_fieldgroup['fields'][$custom_field]['placeholder'] !== '' ? ' placeholder="'.html($tab_fieldgroup['fields'][$custom_field]['placeholder']).'"' : '';
        $is_wysiwyg = $tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea' && !empty($tab_fieldgroup['fields'][$custom_field]['render']) && $tab_fieldgroup['fields'][$custom_field]['render'] === 'wysiwyg' ? true : false;
?>

      <div class="form-group align-items-center form-row">
        <label class="col-sm-2 col-form-label text-right align-self-start"><?php
            if($tab_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
                if(isset($tab_fieldgroup['fields'][$custom_field]['legend'])) {
                    echo html($tab_fieldgroup['fields'][$custom_field]['legend']);
                } else {
                    echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
                }
            }
        ?>&nbsp;</label>
        <div class="col">
<?php
        // support only type "str" or "textarea" at the moment
        if(empty($tab_fieldgroup['fields'][$custom_field]['type']) || !in_array($tab_fieldgroup['fields'][$custom_field]['type'], $custom_tab_field_types)) {
            $tab_fieldgroup['fields'][$custom_field]['type'] = 'str';
        }

        if($tab_fieldgroup['fields'][$custom_field]['type'] === 'str'): ?>
                <input type="text" class="form-control form-control-sm" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php
                if(isset($value['custom_fields'][$custom_field])) { echo html($value['custom_fields'][$custom_field]); }
                ?>"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['maxlength'])): ?> maxlength="<?php echo $tab_fieldgroup['fields'][$custom_field]['maxlength']; ?>"<?php endif; ?>
                class="form-control form-control-sm"<?php echo $custom_field_placeholder; ?> />

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'int' || $tab_fieldgroup['fields'][$custom_field]['type'] === 'float'): ?>
                <input type="number" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php
                echo isset($value['custom_fields'][$custom_field]) ? $value['custom_fields'][$custom_field] : 0;
                ?>" class="form-control form-control-sm" <?php echo $custom_field_placeholder; ?>
                <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['min'])): ?> min="<?php echo $tab_fieldgroup['fields'][$custom_field]['min']; ?>" <?php endif; ?>
                <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['max'])): ?> max="<?php echo $tab_fieldgroup['fields'][$custom_field]['max']; ?>" <?php endif; ?>
                <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['step'])): ?> step="<?php
                    if($tab_fieldgroup['fields'][$custom_field]['type'] === 'int') {
                        $tab_fieldgroup['fields'][$custom_field]['step'] = ceil($tab_fieldgroup['fields'][$custom_field]['step']);
                    } else {
                        $tab_fieldgroup['fields'][$custom_field]['step'] = floatval($tab_fieldgroup['fields'][$custom_field]['step']);
                        $tab_fieldgroup['fields'][$custom_field]['step'] = rtrim(number_format($tab_fieldgroup['fields'][$custom_field]['step'], 14 - log10($tab_fieldgroup['fields'][$custom_field]['step'])), '0');
                    }
                    echo $tab_fieldgroup['fields'][$custom_field]['step'];
                ?>"
                <?php endif; ?>
                />

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea'):

            if ($is_wysiwyg):
                $wysiwyg_editor = array(
                    'value' => isset($value['custom_fields'][$custom_field]) ? $value['custom_fields'][$custom_field] : '',
                    'field' => 'customfield[' . $key . '][' . $custom_field . ']',
                    'height' => empty($tab_fieldgroup['fields'][$custom_field]['height']) ? '150px' : $tab_fieldgroup['fields'][$custom_field]['height'],
                    'width' => '100%',
                    'rows' => empty($tab_fieldgroup['fields'][$custom_field]['rows']) ? '5' : $tab_fieldgroup['fields'][$custom_field]['rows'],
                    'editor' => $_SESSION["WYSIWYG_EDITOR"],
                    'lang' => 'en',
                    'config' => 'tabs'
                );

                include CMSGO_ROOT . '/include/inc_lib/wysiwyg.editor.inc.php';
            else: ?>
                <textarea name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" class="form-control form-control-sm"<?php echo $custom_field_placeholder; ?> rows="<?php
                    echo empty($tab_fieldgroup['fields'][$custom_field]['rows']) ? '3' : $tab_fieldgroup['fields'][$custom_field]['rows'];
                ?>"><?php if(isset($value['custom_fields'][$custom_field])) { echo html($value['custom_fields'][$custom_field]); } ?></textarea><?php
            endif;

        elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'option' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])):
            foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
                <div class="form-check form-check-inline pt-1 col-sm-auto">
									<input type="radio" class="form-check-input" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
											if(isset($value['custom_fields'][$custom_field]) && $value['custom_fields'][$custom_field] === $option_key):
									?> checked="checked"<?php
											elseif(empty($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key):
									?> checked="checked"<?php endif; ?> />
									<label class="form-check-label"><?php echo html($option_label); ?></label>
                </div>
<?php       endforeach; ?>

            <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])): ?>
            <select class="custom-select form-control form-control-sm" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]">
<?php       foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
                <option value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
                    if(isset($value['custom_fields'][$custom_field]) && $value['custom_fields'][$custom_field] === $option_key):
                ?> selected="selected"<?php
                    elseif(empty($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key):
                ?> selected="selected"<?php endif; ?>><?php echo html($option_label); ?></option>
<?php       endforeach; ?>
            </select>

            <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'bool'): ?>
            <label class="checkbox tab-option-checkbox">
                <input type="checkbox" name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>]" value="1"<?php
                    if((!empty($value['custom_fields'][$custom_field])) || (!isset($value['custom_fields'][$custom_field]) && !empty($tab_fieldgroup['fields'][$custom_field]['default']))):
                ?> checked="checked"<?php endif; ?> /> <?php echo html($tab_fieldgroup['fields'][$custom_field]['legend']); ?>
            </label>

            <?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'file'): ?>

            <div class="input-group mb-3">

              <span class="input-group-prepend">
                <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=19&field=<?php echo $custom_field.'_'.$key; ?>&allowed=<?php echo $tab_fieldgroup['fields'][$custom_field]['filetypes']; ?>" ></button>
              </span>

              <input
                  name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>][id]"
                  type="hidden"
                  id="customfield_<?php echo $custom_field.'_'.$key; ?>_id"
                  value="<?php
                  if(isset($value['custom_fields'][$custom_field]['id'])) {
                      echo $value['custom_fields'][$custom_field]['id'];
                  }
                  ?>"
              />
              <input
                  name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>][name]"
                  type="text"
                  id="customfield_<?php echo $custom_field.'_'.$key; ?>_name"
                  class="form-control form-control-sm"
                  value="<?php
                  if(isset($value['custom_fields'][$custom_field]['name'])) {
                      echo html($value['custom_fields'][$custom_field]['name']);
                  }
                  ?>"
                  size="40"
                  onfocus="this.blur()"
              />
                <span class="input-group-append ">
                    <a class="btn btn-sm btn-danger trash"
                        href="#"
                        type="button"
                        data-toggle="tooltip" title="<?php echo $BL['be_cnt_delmedia'] ?>"
                        onclick="getObjectById('customfield_<?php
                            echo $custom_field.'_'.$key; ?>_name').value='';getObjectById('customfield_<?php
                            echo $custom_field.'_'.$key; ?>_id').value='';getObjectById('customfield_<?php
                            echo $custom_field.'_'.$key; ?>_description').value='';this.blur();return false;"
                        ></a>
                </span>
            </div>

            <textarea
                    name="customfield[<?php echo $key; ?>][<?php echo $custom_field; ?>][description]"
                    cols="40"
                    rows="2"
                    class="form-control form-control-sm mb-2"
                    id="customfield_<?php echo $custom_field.'_'.$key; ?>_description"><?php
                    if(isset($value['custom_fields'][$custom_field]['description'])) {
                            echo html($value['custom_fields'][$custom_field]['description']);
                    }
                    ?></textarea>
            <span class="small">
                    <?php echo $BL['be_cnt_description']; ?>
                    |
                    <?php echo $BL['be_fprivedit_filename']; ?>
                    |
                    <?php echo $BL['be_caption_file_title']; ?>
                    |
                    <?php echo $BL['be_cnt_target']; ?>
                    |
                    <?php echo $BL['be_caption_file_imagesize']; ?>
                    |
                    <?php echo $BL['be_copyright']; ?>
            </span>

          <?php elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'image'): ?>
					<?php echo $custom_field; ?>
                      <input name="cimage_id_thumb<?php echo $custom_field; ?>[<?php echo $key ?>]" id="cimage_id_thumb_<?php echo $custom_field; ?>_<?php echo $key ?>" type="hidden" value="<?php
                          if(isset($value['custom_fields'][$custom_field])) { echo html($value['custom_fields'][$custom_field]); }
                          ?>" />
                      <input name="cimage_sort[<?php echo $key ?>]" id="cimage_sort_<?php echo $key ?>" type="hidden" value="<?php echo $value['sort'] ?>" />
                      <input name="cimage_name_thumb<?php echo $custom_field; ?>[<?php echo $key ?>]" type="text" id="cimage_name_thumb_<?php echo $custom_field; ?>_<?php echo $key ?>" class="form-control form-control-sm" value="<?php echo html($value['thumb_name']) ?>" size="30" onfocus="this.blur();" />
                  <img src="img/button/open_image_button.gif" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" alt="<?php echo $BL['be_cnt_openimagebrowser'] ?>" width="20" height="15" border="0" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=8&target=nolist&entry_id=thumb_<?php echo $custom_field; ?>_<?php echo $key ?>" class="modalButton" />
                  <a href="#" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData('thumb_<?php echo $custom_field; ?>_<?php echo $key ?>', this);"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>

          <?php endif; ?>
        </div>
      </div>
<?php
    endforeach;
endif;
?>
    </div>
      </div>
  </li>

<?php
}
// close image entry looping
?>
</ul>

<?php
// second button to add images at bottom of list
if (count($content['image_special']['images'])) {
?>
<div class="form-group align-items-center form-row mb-3">
    <label class="col-sm-2 col-form-label text-right"></label>
    <div class="col">
        <button class="btn btn-blue btn-sm" onclick="return addNewImage('bottom');">
           <i class="fa fa-plus"></i> <?php echo $BL['be_ctype_images'] ?> <?php echo $BL['be_article_cnt_add'] ?>
        </button>
    </div>
</div>
<?php
}

$wysiwyg_editor = array(
    'value'     => isset($content["image_html"]) ? $content["image_html"] : '',
    'field'     => 'image_html',
    'height'    => '300px',
    'width'     => '100%',
    'rows'      => '15',
    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    'lang'      => 'en'
);

include CMSGO_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

?>

<input type="hidden" name="tab_fieldgroup" value="<?php echo $tab_fieldgroups_active; ?>" />
<?php
    if(count($custom_tab_fields_hidden)) {
        echo implode('', $custom_tab_fields_hidden);
    }
?>
<script type="text/javascript">

var site_url    = '<?php echo CMSGO_URL; ?>';
var max_img_w   = <?php echo $cmsgo['img_list_width']; ?>;
var max_img_h   = <?php echo $cmsgo['img_list_height']; ?>;
var image_entry = [];

function setCimageCenterInactive() {
    var cih = $('#cimage_width');
    var ciw = $('#cimage_height');
    var cic = $('#cimage_center');
    var ccp = $('#cimage_crop');
    var dis = false;
    if(!parseInt(cih.value, 10)) {
        cih.value = '';
        dis = true;
    }
    if(!parseInt(ciw.value, 10)) {
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
    $('#cimage_id_'+image_number).val(file_id);
    $('#cimage_name_'+image_number).val(file_name);
    image_number = image_number.split('_');
    if(image_number[1]) {
        updatePreviewImage(image_number[1]);
    }
}

function setIdName(field, file_id, file_name) {
    if(file_id == null || file_name == null || field == null) {
        return null;
    }
    $('#customfield_'+field+'_name').val(file_name);
    $('#customfield_'+field+'_id').val(file_id);
    $('#browserModal').modal('hide');
}

function deleteImageData(image_number, e) {
    $('#cimage_name_'+image_number).val('');
    $('#cimage_id_'+image_number).val('0');
    e.blur();
    image_number = image_number.split('_');
    if(image_number[1]) {
        updatePreviewImage(image_number[1]);
    }
    return false;
}

function updatePreviewImage(image_number) {

    var preview = '';
    var cimage_id_thumb = $('#cimage_id_thumb_'+image_number).attr('value');
    var cimage_id_zoom = $('#cimage_id_zoom_'+image_number).attr('value');
    if(cimage_id_thumb) {
            preview += getBackendImgSrc( cimage_id_thumb.value );
    }
    if(cimage_id_zoom) {
        preview += getBackendImgSrc( cimage_id_zoom );
    }
    $('#img_preview_'+image_number).html(preview);
    $('#img_previewsmall_'+image_number).html(preview);
}

function getBackendImgSrc(image_file_id) {
    var image_file_id = parseInt(image_file_id, 10);
    if(image_file_id) {
        return '<'+'img src="'+site_url+'img/cmsimage.php/'+max_img_w+'x'+max_img_h+'/'+image_file_id+'" border="0" alt="" /'+'> ';
    }
    return '';
}

function updatePreviewImageAll() {

    $('li', $('ul#images')).each(function() {
      var image_number = $(this).attr('id').split('_');
      if(image_number[1]) {
          updatePreviewImage(image_number[1]);
          image_entry[ image_number[1] ] = $('cimage_sort_'+image_number[1]).value;
      }
    });
}

function addNewImage(where) {

    updatePreviewImageAll();

    var entry_number = image_entry.length;
    var new_entry = '';

    new_entry += '<'+'div class="card-header p-2 border-1" role="tab" id="heading_'+entry_number+'">';
    new_entry += '<'+'div class="row align-items-center">';
    new_entry += '<'+'div class="col-sm-auto text-right pr-0"><em data-toggle="tooltip" title="<?php echo $sort_up_down; ?>" class="handle text-success"><span class="fa-stack"><i class="fa fa-circle fa-stack-2x"><'+'/i><i class="fa fa-sort fa-stack-1x fa-inverse"><'+'/i><'+'/span><'+'/em><'+'/div>';
    new_entry += '<'+'div class="col"><h2># '+entry_number+'<'+'/h2><'+'/div>';
    new_entry += '<'+'div class="col-sm-auto text-right">';
    new_entry += '<'+'a class="btn btn-sm btn-blue mr-1" data-toggle="collapse" href="#collapse_'+entry_number+'" aria-expanded="true" aria-controls="collapse_'+entry_number+'">';
    new_entry += '<'+'i class="fa fa-ellipsis-h" aria-hidden="true"><'+'/i>';
    new_entry += '<'+'/a>';
    new_entry += '<'+'a class="btn btn-sm btn-danger" role="button" aria-disabled="true" href="#" onclick="return deleteImgElement(\'image_'+entry_number+'\'"><i class="far fa-trash-alt"><'+'/i><'+'/a><'+'/div>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'div id="collapse_'+entry_number+'" class="collapse show" role="tabpanel" aria-labelledby="heading_'+entry_number+'" data-parent="#images">';
    new_entry += '<'+'div class="card-body">';
    new_entry += '<'+'div class="row mb-3">';
    new_entry += '<'+'div class="col-sm-6">';
    new_entry += '<'+'div class="form-group align-items-center">';
    new_entry += '<'+'input name="cimage_id_thumb['+entry_number+']" id="cimage_id_thumb_'+entry_number+'" type="hidden" value="" '+'/>';
    new_entry += '<'+'input name="cimage_sort['+entry_number+']" id="cimage_sort_'+entry_number+'" type="hidden" value="" '+'/>';
    new_entry += '<'+'label><?php echo $BL['be_flashplayer_thumbnail'] ?><'+'/label>';
    new_entry += '<div class="input-group">';
    new_entry += '<'+'span class="input-group-prepend chatlist">';
    new_entry += '<'+'button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=8&target=nolist&entry_id=thumb_'+entry_number+'" ><'+'/button>';
    new_entry += '<'+'/span>';
    new_entry += '<'+'input name="cimage_name_thumb['+entry_number+']" type="text" id="cimage_name_thumb_'+entry_number+'" class="form-control form-control-sm" value="" maxlength="250" onfocus="this.blur()" '+'/>';
    new_entry += '<'+'span class="input-group-append chatlist">';
    new_entry += '<'+'a href="#" class="btn btn-sm btn-danger trash" type="button" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData(\'thumb_'+entry_number+'\', this);"></a>';
    new_entry += '<'+'/span>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'div class="form-group align-items-center">';
    new_entry += '<'+'input name="cimage_id_zoom['+entry_number+']" id="cimage_id_zoom_'+entry_number+'" type="hidden" value="" '+'/>';
    new_entry += '<'+'input name="cimage_sort['+entry_number+']" id="cimage_sort_'+entry_number+'" type="hidden" value="" '+'/>';
    new_entry += '<'+'label><?php echo $BL['be_image_zoom'] ?><'+'/label>';
    new_entry += '<'+'div class="input-group">';
    new_entry += '<'+'span class="input-group-prepend chatlist">';
    new_entry += '<'+'button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=8&target=nolist&entry_id=zoom_'+entry_number+'" ></button>';
    new_entry += '<'+'/span>';
    new_entry += '<'+'input name="cimage_name_zoom['+entry_number+']" type="text" id="cimage_name_zoom_'+entry_number+'" class="form-control form-control-sm" value="" maxlength="250" onfocus="this.blur()" '+'/>';
    new_entry += '<'+'span class="input-group-append chatlist">';
    new_entry += '<'+'a href="#" class="btn btn-sm btn-danger trash" type="button" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="return deleteImageData(\'zoom_'+entry_number+'\', this);"></a>';
    new_entry += '<'+'/span>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'div id="img_preview_'+entry_number+'" class="backend_preview_img"><'+'/div>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'div class="col-sm-6">';
    new_entry += '<'+'div class="form-group">';
    new_entry += '<'+'label><?php echo $BL['be_cnt_caption'] ?><'+'/label>';
    new_entry += '<'+'textarea name="cimage_caption['+entry_number+']" id="cimage_caption_'+entry_number+'" class="form-control form-control-sm" cols="30" rows="2"><'+'/textarea>';
    new_entry += '<span class="small"><?php echo $BL['be_cnt_caption']; ?> | <?php echo $BL['be_caption_alt']; ?> | <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em> | <?php echo $BL['be_caption_title']; ?> | <?php echo $BL['be_copyright']; ?><'+'/span>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'div class="form-group">';
    new_entry += '<'+'label><?php echo $BL['be_cnt_infotext'] ?><'+'/label>';
    new_entry += '<'+'textarea name="cimage_freetext['+entry_number+']" id="cimage_freetext_'+entry_number+'" class="form-control form-control-sm" cols="30" rows="2"><'+'/textarea>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'div class="form-group mb-0">';
    new_entry += '<'+'label><?php echo $BL['be_profile_label_website'] ?><'+'/label>';
    new_entry += '<'+'input type="text" name="cimage_url['+entry_number+']" id="cimage_url_<?php echo $key ?>" class="form-control form-control-sm"  value="" '+'/>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'/div>';
    new_entry += '<'+'/div>';

<?php
    if(!empty($value['custom_field_items'])):
        foreach($value['custom_field_items'] as $custom_field_key => $custom_field):

            // send fields not defined as hidden values, should ensure not loosing values
            if(!isset($tab_fieldgroup['fields'][$custom_field]) && isset($value['custom_fields'][$custom_field])) {
                continue;
            }

            $custom_field_placeholder = isset($tab_fieldgroup['fields'][$custom_field]['placeholder']) && $tab_fieldgroup['fields'][$custom_field]['placeholder'] !== '' ? ' placeholder="'.html($tab_fieldgroup['fields'][$custom_field]['placeholder']).'"' : '';

            // support only type "str" or "textarea" at the moment
            if(empty($tab_fieldgroup['fields'][$custom_field]['type']) || !in_array($tab_fieldgroup['fields'][$custom_field]['type'], $custom_tab_field_types)) {
                $tab_fieldgroup['fields'][$custom_field]['type'] = 'str';
            }

?>
    new_entry += '<div class="form-group align-items-center form-row">';
    new_entry += '<label class="col-sm-2 col-form-label text-right align-self-start"><?php
            if($tab_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
                if(isset($tab_fieldgroup['fields'][$custom_field]['legend'])) {
                    echo html($tab_fieldgroup['fields'][$custom_field]['legend']);
                } else {
                    echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
                }
            }
    ?><'+'/label>';
    new_entry += '<div class="col">';
<?php   if($tab_fieldgroup['fields'][$custom_field]['type'] === 'str'): ?>
    new_entry += '<input type="text" name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]" value=""<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['maxlength'])): ?> maxlength="<?php echo $tab_fieldgroup['fields'][$custom_field]['maxlength']; ?>"<?php endif; ?> class="form-control form-control-sm"<?php echo $custom_field_placeholder; ?> '+'/>';

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'textarea'): ?>
    new_entry += '<textarea name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]" class="form-control form-control-sm" rows="<?php echo empty($tab_fieldgroup['fields'][$custom_field]['rows']) ? '3' : $tab_fieldgroup['fields'][$custom_field]['rows']; ?>"<?php echo $custom_field_placeholder; ?>><'+'/textarea>';

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'option' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])):
    foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
    new_entry += '<div class="form-check form-check-inline col-sm-auto"><input class="form-check-input" type="radio" name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]" value="<?php echo $option_key; ?>"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key): ?> checked="checked"<?php endif; ?>'+'/> <label class="form-check-label"><?php echo html($option_label); ?><'+'/label></div> ';
<?php   endforeach;

    elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'int' || $tab_fieldgroup['fields'][$custom_field]['type'] === 'float'): ?>
    new_entry += '<input type="number" name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]" value="0" class="form-control form-control-sm"<?php echo $custom_field_placeholder; ?>';
    <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['min'])): ?>new_entry += ' min="<?php echo $tab_fieldgroup['fields'][$custom_field]['min']; ?>"';<?php endif; ?>
    <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['max'])): ?>new_entry += ' max="<?php echo $tab_fieldgroup['fields'][$custom_field]['max']; ?>"';<?php endif; ?>
    <?php if(!empty($tab_fieldgroup['fields'][$custom_field]['step'])): ?>new_entry += ' step="<?php echo $tab_fieldgroup['fields'][$custom_field]['step']; ?>"';<?php endif; ?>
    new_entry += ' />';

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($tab_fieldgroup['fields'][$custom_field]['values'])): ?>
    new_entry += '<select class="custom-select form-control form-control-sm" name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]">';
    <?php       foreach($tab_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
    new_entry += '<option value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['default']) && $tab_fieldgroup['fields'][$custom_field]['default'] === $option_key): ?> selected="selected"<?php endif; ?>><?php echo html($option_label); ?><'+'/option>';
    <?php       endforeach; ?>
    new_entry += '</select>';

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'bool'): ?>
		new_entry += '<div class="form-check form-check-inline pt-1 col-sm-auto">';
    new_entry += '<input class="form-check-input" type="checkbox" name="customfield[' + entry_number + '][<?php echo $custom_field; ?>]" value="1"<?php if(!empty($tab_fieldgroup['fields'][$custom_field]['default'])): ?> checked="checked"<?php endif; ?>'+'/> ';
    new_entry += '<label class="form-check-label"><?php echo html($tab_fieldgroup['fields'][$custom_field]['legend']); ?></label>';
    new_entry += '</div>'

<?php   elseif($tab_fieldgroup['fields'][$custom_field]['type'] === 'file'): ?>

    new_entry += '  <div class="input-group mb-3">';
    new_entry += '    <span class="input-group-prepend">';
    new_entry += '      <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=19&field=<?php echo $custom_field; ?>_' + entry_number + '&allowed=<?php echo $tab_fieldgroup['fields'][$custom_field]['filetypes']; ?>"  /></button>';
    new_entry += '    </span>';
    new_entry += '          <input';
    new_entry += '              name="customfield[' + entry_number + '][<?php echo $custom_field; ?>][id]"';
    new_entry += '              type="hidden"';
    new_entry += '              id="customfield_<?php echo $custom_field; ?>_' + entry_number + '_id"';
    new_entry += '              value="<?php if(isset($value['custom_fields'][$custom_field]['id'])) { echo $value['custom_fields'][$custom_field]['id']; } ?>"';
    new_entry += '          />';
    new_entry += '          <input';
    new_entry += '              name="customfield[' + entry_number + '][<?php echo $custom_field; ?>][name]"';
    new_entry += '              type="text"';
    new_entry += '              id="customfield_<?php echo $custom_field; ?>_' + entry_number + '_name"';
    new_entry += '              class="form-control form-control-sm"';
    new_entry += '              value="<?php if(isset($value['custom_fields'][$custom_field]['name'])) { echo html($value['custom_fields'][$custom_field]['name']); } ?>"';
    new_entry += '              size="40"';
    new_entry += '              onfocus="this.blur()"';
    new_entry += '          />';
    new_entry += '      <span class="input-group-append">';
    new_entry += '          <a class="btn btn-sm btn-danger trash"';
    new_entry += '              href="#" type="button"';
    new_entry += '              data-toggle="tooltip" title="<?php echo $BL['be_cnt_delmedia'] ?>"';
    new_entry += '              onclick="getObjectById(\'customfield_<?php
                                echo $custom_field; ?>_' + entry_number + '_name\').value=\'\';getObjectById(\'customfield_<?php
                                echo $custom_field; ?>_' + entry_number + '_id\').value=\'\';getObjectById(\'customfield_<?php
                                echo $custom_field; ?>_' + entry_number + '_description\').value=\'\';this.blur();return false;"';
    new_entry += '          ></a>';
    new_entry += '      </span>';
    new_entry += '  </div>';
    new_entry += '  <textarea name="customfield[' + entry_number + '][<?php echo $custom_field; ?>][description]" cols="40" rows="2"';
    new_entry += '      class="form-control form-control-sm" id="customfield_<?php echo $custom_field; ?>_' + entry_number + '_description"></textarea>';
    new_entry += '  <div class="small mt-3">';
    new_entry += '      <?php echo $BL['be_cnt_description']; ?> |';
    new_entry += '      <?php echo $BL['be_fprivedit_filename']; ?> |';
    new_entry += '      <?php echo $BL['be_caption_file_title']; ?> |';
    new_entry += '      <?php echo $BL['be_cnt_target']; ?> |';
    new_entry += '      <?php echo $BL['be_caption_file_imagesize']; ?> |';
    new_entry += '      <?php echo $BL['be_copyright']; ?>';
    new_entry += '  </div>';

<?php   endif; ?>
    new_entry += '</div></div>';
<?php
        endforeach;
    endif;
?>
    new_entry += '<'+'/div>';
    new_entry += '<'+'/div>'; //end card-body

    var $li = $("<li>", {id: 'image_'+entry_number, "class": "card my-3 p-0 sortme nomove"});
    if (where === 'top') {
        $("#images").prepend($li);
    } else {
        $("#images").append($li);
    }
    $('#image_'+entry_number).html(new_entry);
    window.location.hash='image_'+entry_number;

    $('button.modalButton').on('click', function() {
        $("#browserModal iframe").attr({'src': $(this).data('src'), 'height': '100%', 'width': '100%'});
    });
    return false;
}

function deleteImgElement(id) {
    if(confirm('<?php echo $BL['be_image_delete_js'] ?>')) {
        $("#" + id).remove();
    }
    return false;
}

$(function(){

    setCimageCenterInactive();
    updatePreviewImageAll();

    $("#images").sortable({
        group: 'no-drop',
        handle: 'em.handle',
        onDrag: function ($item, container, _super, event) {
            $(".collapse").collapse('hide');
        },
        onDrop: function ($item, container, _super, event) {
            $item.removeClass(container.group.options.draggedClass).removeAttr("style");
            $("body").removeClass(container.group.options.bodyClass);
        }
    });

});

</script>
