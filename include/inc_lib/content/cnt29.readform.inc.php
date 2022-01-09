<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
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

		$img_all = _dbQuery('SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE f_id IN ('.implode(',', $content["image_list"]).')');

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
