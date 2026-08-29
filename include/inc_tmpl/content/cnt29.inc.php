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
        'pos'           => 0,
        'width'         => $template_default['imagegallery_default_width'],
        'height'        => $template_default['imagegallery_default_height'],
        'col'           => $template_default['imagegallery_default_column'],
        'space'         => $template_default['imagegallery_default_space'],
        'zoom'          => 0,
        'caption'       => '',
        'lightbox'      => 0,
        'nocaption'     => 0,
        'crop'          => 0,
        'limit'         => 0,
        'random'        => 0,
        'fieldgroup'    => '',
        'custom'        => array()
    );
}
if(empty($content['image_list']['center_image'])) {
    $content['image_list']['center_image'] = 0;
}

$img_count = isset($content["image_list"]['images']) && is_array($content["image_list"]['images']) ? count($content["image_list"]['images']) : 0;

$cnt_fieldgroup_templates = array();
$cnt_onchange_templates = array();
if(isset($template_default['settings']['imgdiv_custom_fields']) && is_array($template_default['settings']['imgdiv_custom_fields']) && count($template_default['settings']['imgdiv_custom_fields'])) {
    $cnt_fieldgroups = $template_default['settings']['imgdiv_custom_fields'];
    foreach($template_default['settings']['imgdiv_custom_fields'] as $key => $cnt_fieldgroup) {
        if(empty($cnt_fieldgroup['template'])) {
            continue;
        }
        $cnt_fieldgroup_templates[ $cnt_fieldgroup['template'] ] = $key;
        $cnt_onchange_templates[] = $cnt_fieldgroup['template'];
    }
} else {
    $cnt_fieldgroups = array();
}

$cnt_fieldgroups_active = isset($cnt_fieldgroup_templates['default']) ? $cnt_fieldgroup_templates['default'] : '';

?>
<div class="form-group align-items-center row g-2">
  <label for="template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="form-select form-select-sm">
        <option value=""><?php echo $BL['be_admin_tmpl_default']; ?></option>
<?php
    // templates for frontend login
    $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/images');
    if(is_array($tmpllist) && count($tmpllist)) {
        foreach($tmpllist as $val) {
            // do not show listmode templates
            if(substr($val, 0, 5) == 'list.') {
                continue;
            }

            if(isset($content['image_template']) && $val === $content['image_template']) {
                $selected_val = ' selected="selected"';
                if(isset($cnt_fieldgroup_templates[$val])) {
                    $cnt_fieldgroups_active = $cnt_fieldgroup_templates[$val];
                    $content['image_list']['fieldgroup'] = $cnt_fieldgroups_active;
                } else {
                    // Reset
                    $cnt_fieldgroups_active = '';
                }
            } else {
                $selected_val = '';
            }

            $val = html($val);
            echo '<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>';
        }
    }
?>
    </select>
  </div>
</div>

<div class="form-group align-items-center row g-2">
  <label for="cimage_center" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_image_align'] ?></label>
  <div class="col-sm-4">
    <select name="cimage_center" id="cimage_center" class="form-select form-select-sm">
        <option value="0"<?php is_selected(0, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagenocenter'] ?></option>
        <option value="1"<?php is_selected(1, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagecenter'] ?></option>
        <option value="2"<?php is_selected(2, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagecenterh'] ?></option>
        <option value="3"<?php is_selected(3, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagecenterv'] ?></option>
    </select>
  </div>
</div>

<div class="form-group align-items-center row g-2">
    <label for="template" class="col-sm-2 col-form-label text-end">
        <?php echo $BL['be_ftptakeover_size'] ?>
    </label>
    <div class="col-sm-auto">
        <div class="input-group input-group-sm">
            <span class="input-group-text"><?php echo $BL['be_cnt_maxw'] ?></span>
            <input name="cimage_width" type="text" class="form-control form-control-sm width50" id="cimage_width" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo empty($content['image_list']['width']) ? $template_default['imagegallery_default_width'] : $content['image_list']['width']; ?>" />
            <span class="input-group-text">px</span>
        </div>
    </div>
    <div class="col-sm-auto">
        <div class="input-group input-group-sm">
            <span class="input-group-text"><?php echo $BL['be_cnt_maxh'] ?></span>
            <input name="cimage_height" type="text" class="form-control form-control-sm width50" id="cimage_height" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo empty($content['image_list']['height']) ? $template_default['imagegallery_default_height'] : $content['image_list']['height']; ?>" />
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
		<input name="cimage_limit" type="text" class="form-control form-control-sm" id="cimage_limit" maxlength="3" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['limit']) ? '' : $content['image_list']['limit']; ?>" />
	</div>
	<div class="col">
		<div class="form-check form-check-inline">
			<input class="form-check-input" name="cimage_random" type="checkbox" id="cimage_random" value="1" <?php is_checked(1, empty($content['image_list']['random']) ? 0 : 1); ?> />
			<label class="form-check-label" for="cimage_random"><?php echo $BL['random_image'] ?></label>
		</div>
	</div>
</div>

<div class="form-group align-items-center row g-2">
    <label for="cimage_col" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_column'] ?></label>
    <div class="col-sm-auto">
		<select class="form-select form-select-sm" name="cimage_col" id="cimage_col">
		<?php
		// list select menu for max image columns
		for($max_image_col = 1; $max_image_col <= 25; $max_image_col++) {
            echo '<option value="'.$max_image_col.'" ';
            is_selected($max_image_col, $content['image_list']['col']);
            echo '>'.$max_image_col.'</option>';
		}
		?>
		</select>
	</div>
	<div class="col-sm-auto">
        <div class="input-group input-group-sm">
			<span class="input-group-text"><?php echo $BL['be_cnt_imagespace'] ?></span>
            <input name="cimage_space" type="text" class="form-control form-control-sm" id="cimage_space" maxlength="3" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['space']) ? $template_default['imagegallery_default_space'] : $content['image_list']['space']; ?>" />
			<span class="input-group-text">px</span>
		</div>
    </div>
</div>

<script type="text/javascript">
    function setCimageCenterInactive() {
        const widthVal = parseInt($('#cimage_width').val(), 10);
        const heightVal = parseInt($('#cimage_height').val(), 10);
        const isInvalid = isNaN(widthVal) || isNaN(heightVal) || widthVal <= 0 || heightVal <= 0;
        
        if (isNaN(widthVal)) $('#cimage_width').val('');
        if (isNaN(heightVal)) $('#cimage_height').val('');
        
        $('#cimage_center, #cimage_crop').prop('disabled', isInvalid);
    }
    setCimageCenterInactive();
</script>

<div class="form-group align-items-center row g-2">
    <label for="cimage_zoom" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_behavior'] ?></label>
    <div class="col">
        <div class="form-check form-check-inline">
			<input name="cimage_zoom" class="form-check-input" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, $content['image_list']['zoom']); ?> />
			<label class="form-check-label"><?php echo $BL['be_cnt_enlarge'] ?></label>
        </div>
        <div class="form-check form-check-inline">
			<input name="cimage_lightbox" class="form-check-input" type="checkbox" id="cimage_lightbox" value="1" <?php is_checked(1, $content['image_list']['lightbox']); ?> onchange="if(this.checked){document.getElementById('cimage_zoom').checked=true;}" />
			<label class="form-check-label"><?php echo $BL['be_cnt_lightbox'] ?></label>
        </div>
        <div class="form-check form-check-inline">
			<input name="cimage_nocaption" class="form-check-input" type="checkbox" id="cimage_nocaption" value="1" <?php is_checked(1, $content['image_list']['nocaption']); ?> />
			<label class="form-check-label"><?php echo $BL['be_cnt_imglist_nocaption'] ?></label>
        </div>
    </div>
</div>

<div class="form-group row g-2">
    <label for="cimage_list" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_image'] ?></label>
    <div class="col">
        <div class="row g-2">
            <div class="col">
                <select name="cimage_list[]" size="<?php echo $img_count+6 ?>" multiple="multiple" class="form-select form-control form-control-sm" id="cimage_list">
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
            "thumb_name"    =>  md5($content['image_list']['images'][$key][2].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
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
            $img_thumbs .= '<img class="mt-2 me-2 img-fluid" src="' . $thumb_image['src'] .'" '.$thumb_image[3].' alt="'.$img_name.'" data-bs-toggle="tooltip" title="'.$img_name.'" />';
            $caption_box[] = html($content['image_list']['images'][$key][6]);
            $imgx++;
        }
    }
}

?>
                </select>
            </div>
            <div class="col-sm-auto">
                <span data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>">
                    <button type="button" class="modalButton btn btn-sm btn-blue mb-1" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="filebrowser.php?opt=1&amp;target=nolist">
                        <i class="fa fa-folder-open fa-fw" aria-hidden="true"></i>
                    </button>
                </span><br>
                <button type="button" class="btn btn-sm btn-secondary mb-1" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cimage_list)">
                    <i class="fa fa-angle-up fa-fw" aria-hidden="true"></i>
                </button><br>
                <button type="button" class="btn btn-sm btn-secondary mb-1" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cimage_list)">
                    <i class="fa fa-angle-down fa-fw" aria-hidden="true"></i>
                </button><br>
                <button type="button" class="btn btn-sm btn-danger mb-1" onclick="removeSelectedOptions(document.articlecontent.cimage_list)" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>">
                    <i class="far fa-trash-alt fa-fw" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <?php if($img_thumbs): ?><div class="row"><div class="col mt-1"><?php echo $img_thumbs; ?></div></div><?php endif; ?>
   </div>
</div>

<div class="form-group row g-2">
    <label for="cimage_caption" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_caption'] ?></label>
    <div class="col">
        <textarea name="cimage_caption" cols="40" rows="3" class="form-control form-control-sm" id="cimage_caption"><?php echo implode(' '.LF, $caption_box) ?></textarea>
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
// Custom Fields

if($cnt_fieldgroups_active && isset($template_default['settings']['imgdiv_custom_fields'][$cnt_fieldgroups_active])) {
    $cnt_fieldgroup =& $template_default['settings']['imgdiv_custom_fields'][$cnt_fieldgroups_active];
} else {
    $cnt_fieldgroup = null;
}
if($cnt_fieldgroup !== null && isset($cnt_fieldgroup['fields']) && is_array($cnt_fieldgroup['fields']) && count($cnt_fieldgroup['fields'])) {
    $custom_cnt_fields = array_keys($cnt_fieldgroup['fields']);
} else {
    $custom_cnt_fields = array();
    $cnt_fieldgroup = null;
}

$content['custom_field_items'] = $custom_cnt_fields;
$custom_cnt_fields_hidden = array();
$custom_cnt_field_types = array('str', 'textarea', 'option', 'select', 'int', 'float', 'bool', 'file');

if(isset($content['custom_fields']) && is_array($content['custom_fields']) && count($content['custom_fields'])) {
    if(count($custom_cnt_fields)) {
        $content['custom_field_items'] = array_unique( array_merge($custom_cnt_fields, array_keys($content['custom_fields'])) );
    } else {
        $content['custom_field_items'] = array_keys($content['custom_fields']);
    }
} else {
    $content['custom_field_items'] = $custom_cnt_fields;
}

if($content['custom_field_items']):

    foreach($content['custom_field_items'] as $custom_field_key => $custom_field):

        // send fields not defined as hidden values, should ensure not loosing values
        if(!isset($cnt_fieldgroup['fields'][$custom_field]) && isset($content['custom_fields'][$custom_field])) {
            // do not store if the value is an empty string
            if($content['custom_fields'][$custom_field] !== '') {
                $custom_cnt_fields_hidden[] = '<input type="hidden" name="customfield['.$custom_field.']" value="'.html($content['custom_fields'][$custom_field]).'" />';
            }
            continue;
        }

        // support only type "str" or "textarea" at the moment
        if(empty($cnt_fieldgroup['fields'][$custom_field]['type']) || !in_array($cnt_fieldgroup['fields'][$custom_field]['type'], $custom_cnt_field_types)) {
            $cnt_fieldgroup['fields'][$custom_field]['type'] = 'str';
        }

        $custom_field_placeholder = isset($cnt_fieldgroup['fields'][$custom_field]['placeholder']) && $cnt_fieldgroup['fields'][$custom_field]['placeholder'] !== '' ? ' placeholder="'.html($cnt_fieldgroup['fields'][$custom_field]['placeholder']).'"' : '';
        $is_wysiwyg = $cnt_fieldgroup['fields'][$custom_field]['type'] === 'textarea' && !empty($cnt_fieldgroup['fields'][$custom_field]['render']) && $cnt_fieldgroup['fields'][$custom_field]['render'] === 'wysiwyg' ? true : false;
        $custom_field_class = empty($cnt_fieldgroup['fields'][$custom_field]['class']) ? '' : ' ' . $cnt_fieldgroup['fields'][$custom_field]['class'];
?>
    <div class="form-group row g-2<?php if($cnt_fieldgroup['fields'][$custom_field]['type'] !== 'file' && (empty($cnt_fieldgroup['fields'][$custom_field]['rows']) || $cnt_fieldgroup['fields'][$custom_field]['rows'] < 2)): ?> align-items-center<?php endif; ?><?= $custom_field_class; ?>">
        <label class="col-sm-2 col-form-label text-end"><?php
            if($cnt_fieldgroup['fields'][$custom_field]['type'] !== 'bool') {
                if(isset($cnt_fieldgroup['fields'][$custom_field]['legend'])) {
                    echo html($cnt_fieldgroup['fields'][$custom_field]['legend']);
                } else {
                    echo $BL['be_custom_textfield'].' #'.($custom_field_key+1);
                }
            }
        ?></label>
        <div class="col"><?php

            if($cnt_fieldgroup['fields'][$custom_field]['type'] === 'str'): ?>
                <input type="text" name="customfield[<?php echo $custom_field; ?>]" value="<?php
                if(isset($content['custom_fields'][$custom_field])) {
                    echo html($content['custom_fields'][$custom_field]);
                }
                ?>"<?php if(!empty($cnt_fieldgroup['fields'][$custom_field]['maxlength'])): ?>
                    maxlength="<?php echo $cnt_fieldgroup['fields'][$custom_field]['maxlength']; ?>"
                <?php endif; ?> class="form-control form-control-sm"<?php echo $custom_field_placeholder; ?> /><?php

            elseif($cnt_fieldgroup['fields'][$custom_field]['type'] === 'int' || $cnt_fieldgroup['fields'][$custom_field]['type'] === 'float'): ?>
                <input type="number" name="customfield[<?php echo $custom_field; ?>]" value="<?php
                echo isset($content['custom_fields'][$custom_field]) ? $content['custom_fields'][$custom_field] : 0;
                ?>" class="form-control form-control-sm w-50" <?php echo $custom_field_placeholder;
                if(!empty($cnt_fieldgroup['fields'][$custom_field]['min'])): ?> min="<?php echo $cnt_fieldgroup['fields'][$custom_field]['min']; ?>" <?php endif;
                if(!empty($cnt_fieldgroup['fields'][$custom_field]['max'])): ?> max="<?php echo $cnt_fieldgroup['fields'][$custom_field]['max']; ?>" <?php endif;
                if(!empty($cnt_fieldgroup['fields'][$custom_field]['step'])): ?> step="<?php
                    if($cnt_fieldgroup['fields'][$custom_field]['type'] === 'int') {
                        $cnt_fieldgroup['fields'][$custom_field]['step'] = ceil($cnt_fieldgroup['fields'][$custom_field]['step']);
                    } else {
                        $cnt_fieldgroup['fields'][$custom_field]['step'] = floatval($cnt_fieldgroup['fields'][$custom_field]['step']);
                        $cnt_fieldgroup['fields'][$custom_field]['step'] = rtrim(number_format($cnt_fieldgroup['fields'][$custom_field]['step'], (int) (14 - log10($cnt_fieldgroup['fields'][$custom_field]['step']))), '0');
                    }
                    echo $cnt_fieldgroup['fields'][$custom_field]['step']; ?>"<?php
                endif; ?> /><?php

            elseif($cnt_fieldgroup['fields'][$custom_field]['type'] === 'textarea'):

                if($is_wysiwyg) {
                    $wysiwyg_editor = array(
                        'value' => isset($content['custom_fields'][$custom_field]) ? $content['custom_fields'][$custom_field] : '',
                        'field' => 'customfield[' . $custom_field . ']',
                        'height' => empty($cnt_fieldgroup['fields'][$custom_field]['height']) ? '150px' : $cnt_fieldgroup['fields'][$custom_field]['height'],
                        'width' => '100%',
                        'rows' => empty($cnt_fieldgroup['fields'][$custom_field]['rows']) ? '5' : $cnt_fieldgroup['fields'][$custom_field]['rows'],
                        'editor' => $_SESSION["WYSIWYG_EDITOR"],
                        'lang' => 'en'
                    );
                    include PHPWCMS_ROOT . '/include/inc_lib/wysiwyg.editor.inc.php';

                } else {
                    ?><textarea
                    name="customfield[<?php echo $custom_field; ?>]"
                    class="form-control form-control-sm"<?php echo $custom_field_placeholder; ?>
                    rows="<?php echo empty($cnt_fieldgroup['fields'][$custom_field]['rows']) ? '3' : $cnt_fieldgroup['fields'][$custom_field]['rows']; ?>"><?php
                    if (isset($content['custom_fields'][$custom_field])) {
                        echo html($content['custom_fields'][$custom_field]);
                    }
                    ?></textarea><?php
                }

            elseif($cnt_fieldgroup['fields'][$custom_field]['type'] === 'option' && !empty($cnt_fieldgroup['fields'][$custom_field]['values'])):

                foreach($cnt_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="customfield[<?php echo $custom_field; ?>]" value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
                        if(isset($content['custom_fields'][$custom_field]) && $content['custom_fields'][$custom_field] === $option_key):
                            ?> checked="checked"<?php
                        elseif(empty($content['custom_fields'][$custom_field]) && !empty($cnt_fieldgroup['fields'][$custom_field]['default']) && $cnt_fieldgroup['fields'][$custom_field]['default'] === $option_key):
                            ?> checked="checked"<?php endif; ?> />
                        <label class="form-check-label me-3"><?php echo html($option_label); ?></label>
                    </div><?php
                endforeach;

            elseif($cnt_fieldgroup['fields'][$custom_field]['type'] === 'select' && !empty($cnt_fieldgroup['fields'][$custom_field]['values'])): ?>
                <select name="customfield[<?php echo $custom_field; ?>]" class="form-select form-control form-control-sm"><?php
                foreach($cnt_fieldgroup['fields'][$custom_field]['values'] as $option_key => $option_label): ?>
                    <option value="<?php echo ($option_key === 'empty' ? '' : $option_key); ?>"<?php
                        if(isset($content['custom_fields'][$custom_field]) && $content['custom_fields'][$custom_field] === $option_key): ?> selected="selected"<?php
                        elseif(empty($content['custom_fields'][$custom_field]) && !empty($cnt_fieldgroup['fields'][$custom_field]['default']) && $cnt_fieldgroup['fields'][$custom_field]['default'] === $option_key): ?>
                            selected="selected"<?php endif; ?>><?php echo html($option_label); ?></option><?php
                endforeach;
                ?></select><?php

            elseif($cnt_fieldgroup['fields'][$custom_field]['type'] === 'bool'): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="customfield[<?php echo $custom_field; ?>]"
                           name="customfield[<?php echo $custom_field; ?>]" value="1"<?php
                        if((!empty($content['custom_fields'][$custom_field])) || (!isset($content['custom_fields'][$custom_field]) && !empty($cnt_fieldgroup['fields'][$custom_field]['default']))): ?>
                           checked="checked"<?php
                        endif; ?>
                    />
                    <label class="form-check-label" for="customfield[<?php echo $custom_field; ?>]"><?php echo html($cnt_fieldgroup['fields'][$custom_field]['legend']); ?></label>
                </div><?php

            elseif($cnt_fieldgroup['fields'][$custom_field]['type'] === 'file'): ?>

                <div class="input-group mb-2">
                    
                        <button class="modalButton btn btn-sm btn-blue folder-open" type="button"
                                data-bs-toggle="modal" data-bs-target="#browserModal"
                                data-src="filebrowser.php?opt=19&field=<?php echo $custom_field; ?>&allowed=<?php echo $cnt_fieldgroup['fields'][$custom_field]['filetypes']; ?>">
                        </button>
                    
                    <input name="customfield[<?php echo $custom_field; ?>][id]" type="hidden" id="customfield_<?php echo $custom_field; ?>_id" value="<?php
                        if(isset($content['custom_fields'][$custom_field]['id'])) {
                            echo $content['custom_fields'][$custom_field]['id'];
                        } ?>"
                    />
                    <input name="customfield[<?php echo $custom_field; ?>][name]" type="text" id="customfield_<?php echo $custom_field; ?>_name"
                           class="form-control form-control-sm" size="40" onfocus="this.blur()" value="<?php
                        if(isset($content['custom_fields'][$custom_field]['name'])) {
                            echo html($content['custom_fields'][$custom_field]['name']);
                        } ?>"<?php
                        if(!empty($cnt_fieldgroup['fields'][$custom_field]['filetypes'])) {
                            echo ' placeholder="' . $BL['be_allowed_filetypes'] . ': '. html(str_replace(',', ', ', $cnt_fieldgroup['fields'][$custom_field]['filetypes'])) . '"';
                        } ?>
                    />
                    
                        <a class="btn btn-sm btn-danger trash" href="#" type="button"
                           data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_delmedia'] ?>"
                           onclick="document.getElementById('customfield_<?php
                            echo $custom_field; ?>_name').value='';document.getElementById('customfield_<?php
                            echo $custom_field; ?>_id').value='';document.getElementById('customfield_<?php
                            echo $custom_field; ?>_description').value='';this.blur();return false;"
                        ></a>
                    
                </div>
                <textarea name="customfield[<?php echo $custom_field; ?>][description]"
                          cols="40"
                          rows="1"
                          class="form-control form-control-sm mb-2"
                          id="customfield_<?php echo $custom_field; ?>_description"><?php
                    if(isset($content['custom_fields'][$custom_field]['description'])) {
                        echo html($content['custom_fields'][$custom_field]['description']);
                    } ?></textarea>
                <span class="small">
                    <?php echo $BL['be_cnt_description']; ?> |
                    <?php echo $BL['be_fprivedit_filename']; ?> |
                    <?php echo $BL['be_caption_file_title']; ?> |
                    <?php echo $BL['be_cnt_target']; ?> |
                    <?php echo $BL['be_caption_file_imagesize']; ?> |
                    <?php echo $BL['be_copyright']; ?>
                </span><?php

            endif; ?>
        </div>
    </div><?php

    if(!empty($cnt_fieldgroup['fields'][$custom_field]['hr'])):?><hr><?php endif;

    endforeach;
endif; ?>
    <input type="hidden" name="cnt_fieldgroup" value="<?php echo $cnt_fieldgroups_active; ?>" />
<?php
if(count($custom_cnt_fields_hidden)) {
    echo implode('', $custom_cnt_fields_hidden);
}
if(count($cnt_fieldgroups)): ?><script>
    function toggleTabsTemplate(e) {
        if(confirm('<?php echo correct_charset($BL['be_tab_template_toggle_warning'], true); ?>')) {
            e.form.submit();
            return true;
        }
        return false;
    }

    function setIdName(field, file_id, file_name) {
        if(file_id == null || file_name == null || field == null) {
            return null;
        }
        $('#customfield_'+field+'_name').val(file_name);
        $('#customfield_'+field+'_id').val(file_id);
        $('#browserModal').modal('hide');
    }
</script><?php

endif;

$wysiwyg_editor = array(
    'value'     => isset($content["text"]) ? $content["text"] : '',
    'field'     => 'ctext',
    'height'    => '250px',
    'width'     => '100%',
    'rows'      => '15',
    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    'lang'      => 'en'
);

include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

if(count($cnt_onchange_templates)): ?>
<script>
    $(function(){
        var custom_field_templates = <?php echo json_encode($cnt_onchange_templates); ?>;
        var selected_template = $('#template').val();

        if(selected_template === '') {
            selected_template = 'default';
        }

        $('#template').on('change', function() {
            var new_template = this.value;
            if(confirm('<?php echo correct_charset($BL['be_imagediv_template_toggle_warning'], true); ?>')) {
                if(new_template !== selected_template && $.inArray(new_template, custom_field_templates) !== -1) {
                    $('#submit-button').trigger('click');
                }
            } else if(selected_template === 'default') {
                $('#template').val('');
            } else {
                $('#template').val(selected_template);
            }
        });
    });
</script>
<?php endif;
