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


// Content Type Text with Image

$content["image_info"] = '';
$content["text"] = slweg($_POST["ctext"]);

$content["image_id"] = intval($_POST["cimage_id"]);
$content["image_pos"] = intval($_POST["cimage_pos"]);
$content["image_caption"] = clean_slweg($_POST["cimage_caption"]);
$content["image_zoom"] = empty($_POST["cimage_zoom"]) ? 0 : 1;

$content['cimage'] = array();
$content['cimage']['cimage_lightbox']	= empty($_POST["cimage_lightbox"]) ? 0 : 1;
$content['cimage']['cimage_nocaption']	= empty($_POST["cimage_nocaption"]) ? 0 : 1;
$content['cimage']['cimage_crop']		= empty($_POST["cimage_crop"]) ? 0 : 1;

$content["image_width"] = (intval($_POST["cimage_width"])) ? intval($_POST["cimage_width"]) : "";
$content["image_height"] = (intval($_POST["cimage_height"])) ? intval($_POST["cimage_height"]): "";
$temp_img_maxwidth = ($content["image_pos"] == 6 || $content["image_pos"] == 7) ? intval($phpwcms["content_width"] / 1.75) : $phpwcms["content_width"];
if((!RESPONSIVE_MODE && $content["image_width"] > $temp_img_maxwidth) || ($content["image_width"] == "")) {
	$content["image_width"] = $temp_img_maxwidth;
}

// check for image information and get alle infos from file
$img_sql = "SELECT * FROM " . DB_PREPEND . "phpwcms_file WHERE f_id=" . $content["image_id"] . " LIMIT 1";
$img_result = _dbQuery($img_sql);
if(isset($img_result[0]['f_id'])) {

	// new structure of image information
	// dbid:filename:hash:extension:width:height:caption:position:zoom
	$content["image_info"]  = $img_result[0]['f_id'];
	$content["image_info"] .= ':';
	$content["image_info"] .= $img_result[0]['f_name'];
	$content["image_info"] .= ':';
	$content["image_info"] .= $img_result[0]['f_hash'];
	$content["image_info"] .= ':';
	$content["image_info"] .= $img_result[0]['f_ext'];
	$content["image_info"] .= ':';
	$content["image_info"] .= $content["image_width"];
	$content["image_info"] .= ':';
	$content["image_info"] .= $content["image_height"];
	$content["image_info"] .= ':';
	$content["image_info"] .= base64_encode($content["image_caption"]);
	$content["image_info"] .= ':';
	$content["image_info"] .= $content["image_pos"];
	$content["image_info"] .= ':';
	$content["image_info"] .= $content["image_zoom"];

}

$content["template"] = clean_slweg($_POST['template']);
