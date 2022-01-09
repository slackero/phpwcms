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
if(!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type Text with Image
$content["text"] = $row["acontent_text"];
$content["template"] = $row["acontent_template"];
$content["cimage"] = @unserialize($row["acontent_form"]);
if(!is_array($content["cimage"])) {
	$content["cimage"] = array();
}

// 0   :1       :2   :3        :4    :5     :6      :7       :8
// dbid:filename:hash:extension:width:height:caption:position:zoom
$content["image_info"] = explode(":", $row["acontent_image"]);

$content["image_id"] = $content["image_info"][0];
$content["image_name"] = isset($content["image_info"][1]) ? $content["image_info"][1] : '';

$content["image_hash"] = isset($content["image_info"][2]) ? $content["image_info"][2] : '';
$content["image_ext"] = isset($content["image_info"][3]) ? $content["image_info"][3] : '';

$content["image_width"] = isset($content["image_info"][4]) ? $content["image_info"][4] : '';
$content["image_height"] = isset($content["image_info"][5]) ? $content["image_info"][5] : '';

$content["image_caption"] = isset($content["image_info"][6]) ? base64_decode($content["image_info"][6]) : '';

$content["image_pos"] = isset($content["image_info"][7]) ? $content["image_info"][7] : 0;
$content["image_zoom"] = isset($content["image_info"][8]) ? $content["image_info"][8] : 0;

unset($content["image_info"]);
