<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type Images

$content["image_list"] 		= isset($_POST["cimage_list"]) ? $_POST["cimage_list"] : array();
$content["image_pos"] 		= empty($_POST["cimage_pos"]) ? 0 : intval($_POST["cimage_pos"]);

$content["image_width"] 	= intval($_POST["cimage_width"]) ? intval($_POST["cimage_width"]) : '';
$temp_width 				= $content["image_width"];

$content["image_height"] 	= intval($_POST["cimage_height"]) ? intval($_POST["cimage_height"]) : '';
$temp_height 				= $content["image_height"];

$content["image_space"] 	= intval($_POST["cimage_space"]);
$content["image_col"] 		= intval($_POST["cimage_col"]);
$content["image_caption"] 	= clean_slweg($_POST["cimage_caption"], 0 , false);
$content["image_zoom"] 		= empty($_POST["cimage_zoom"]) ? 0 : 1;
$content["image_crop"] 		= empty($_POST["cimage_crop"]) ? 0 : 1;
$content["image_random"] 	= empty($_POST["cimage_random"]) ? 0 : 1;
$content["image_limit"] 	= intval($_POST["cimage_limit"]);
$content["image_cctext"] 	= explode(LF, $content["image_caption"]);

$content["image_template"]	= clean_slweg($_POST['template']);

$content["text"]			= slweg($_POST["ctext"]);

$content['tmp_images']		= array();

if(is_array($content["image_list"]) && count($content["image_list"])) {


	$content["image_list"] = array_map('intval', $content["image_list"]);
	$content["image_list"] = array_diff($content["image_list"], array(0,'',NULL,false));

	if(count($content["image_list"])) {

		$img_all = _dbQuery('SELECT * FROM '.DB_PREPEND.'cmsgo_file WHERE f_id IN ('.implode(',', $content["image_list"]).')');

		// take all values from db
		$temp_img_row = array();
		foreach($img_all as $value) {
			$temp_img_row[ $value['f_id'] ] = $value;
		}

		// now run though image result - but keep sorting
		foreach($content["image_list"] as $key => $value) {
			if(isset($temp_img_row[$value])) {

				$content['tmp_images'][$key][0]	= $temp_img_row[$value]['f_id'];
				$content['tmp_images'][$key][1]	= $temp_img_row[$value]['f_name'];
				$content['tmp_images'][$key][2]	= $temp_img_row[$value]['f_hash'];
				$content['tmp_images'][$key][3]	= $temp_img_row[$value]['f_ext'];
				$content['tmp_images'][$key][4]	= $temp_width;
				$content['tmp_images'][$key][5]	= $temp_height;
				$content['tmp_images'][$key][6]	= isset($content["image_cctext"][$key]) ? trim($content["image_cctext"][$key]) : '';

			}
		}



	}
}

// take values
$content['image_list'] 					= array();
$content['image_list']['images']		= $content['tmp_images'];
$content['image_list']['width']			= $temp_width;
$content['image_list']['height']		= $temp_height;
$content['image_list']['pos']			= $content["image_pos"];
$content['image_list']['col']			= $content["image_col"];
$content['image_list']['zoom']			= $content["image_zoom"];
$content['image_list']['crop']			= $content["image_crop"];
$content['image_list']['space']			= $content["image_space"];
$content['image_list']['lightbox']		= empty($_POST["cimage_lightbox"]) ? 0 : 1;
$content['image_list']['nocaption']		= empty($_POST["cimage_nocaption"]) ? 0 : 1;
$content['image_list']['limit']			= $content["image_limit"];
$content['image_list']['random']		= $content["image_random"];

$content['image_list']['center_image']	= empty($_POST["cimage_center"]) ? 0 : intval($_POST["cimage_center"]);
if($content['image_list']['center_image'] > 3) {
	$content['image_list']['center_image'] = 0;
} elseif($content['image_list']['center_image'] < 0) {
	$content['image_list']['center_image'] = 0;
}

// Custom Fields
$cnt_fieldgroup_fields = null;
$cnt_fieldgroup_field_render = array('html', 'markdown', 'wysiwyg');
if(empty($_POST['cnt_fieldgroup'])) {
    $content['cnt_fieldgroup'] = '';
} else {
    $content['cnt_fieldgroup'] = clean_slweg($_POST['cnt_fieldgroup']);
    if($content['cnt_fieldgroup'] && isset($template_default['settings']['imgdiv_custom_fields'][ $content['cnt_fieldgroup'] ]['fields'])) {
        $cnt_fieldgroup_fields =& $template_default['settings']['imgdiv_custom_fields'][ $content['cnt_fieldgroup'] ]['fields'];
    }
}

$content['custom_fields'] = array();

// first read all defined custom field values
if(!empty($cnt_fieldgroup_fields)) {
    foreach($cnt_fieldgroup_fields as $custom_field => $custom_field_definition) {
        $custom_field_value = isset($_POST['customfield'][$custom_field]) ? $_POST['customfield'][$custom_field] : null;
        $_POST['customfield'][$custom_field] = null;
        unset($_POST['customfield'][$custom_field]);

        if(isset($cnt_fieldgroup_fields[$custom_field]['render']) && in_array($cnt_fieldgroup_fields[$custom_field]['render'], $cnt_fieldgroup_field_render)) {
            $content['custom_fields'][$custom_field] = slweg($custom_field_value);
        } elseif($cnt_fieldgroup_fields[$custom_field]['type'] === 'int') {
            $content['custom_fields'][$custom_field] = intval($custom_field_value);
        } elseif($cnt_fieldgroup_fields[$custom_field]['type'] === 'float') {
            $content['custom_fields'][$custom_field] = floatval($custom_field_value);
        } elseif($cnt_fieldgroup_fields[$custom_field]['type'] === 'bool') {
            $content['custom_fields'][$custom_field] = empty($custom_field_value) ? 0 : 1;
        } elseif($cnt_fieldgroup_fields[$custom_field]['type'] === 'file') {

            $content['custom_fields'][$custom_field] = array('id' => '', 'name' => '', 'description' => '');

            if(!empty($custom_field_value['id']) && ($custom_field_value['id'] = intval($custom_field_value['id']))) {
                $content['custom_fields'][$custom_field]['id'] = $custom_field_value['id'];
            }
            if(!empty($custom_field_value['name']) && $content['custom_fields'][$custom_field]['id']) {
                $content['custom_fields'][$custom_field]['name'] = clean_slweg($custom_field_value['name']);
            }
            if(!empty($custom_field_value['description']) && $content['custom_fields'][$custom_field]['id']) {
                $content['custom_fields'][$custom_field]['description'] = clean_slweg($custom_field_value['description']);
            }

        } else {
            $content['custom_fields'][$custom_field] = clean_slweg($custom_field_value);
        }
    }
}

// parse all non-defined custom fields (maybe left over from old definitions)
if(!empty($_POST['customfield']) && count($_POST['customfield'])) {
    foreach($_POST['customfield'] as $custom_field => $custom_field_value) {
        if($custom_field_value === null) {
            continue;
        }
        $content['custom_fields'][$custom_field] = slweg($custom_field_value); // keep the value as is
    }
}

$content['image_list']['fieldgroup'] = $content['cnt_fieldgroup'];
$content['image_list']['custom'] = $content['custom_fields'];
