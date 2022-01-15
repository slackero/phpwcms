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

// Content Type Images
$content["image_template"]	= $row["acontent_template"];
$content["image_list"]		= unserialize($row["acontent_form"]);

if(!isset($content["image_list"]['lightbox'])) {
	$content["image_list"]['lightbox'] = 0;
}
if(!isset($content["image_list"]['nocaption'])) {
	$content["image_list"]['nocaption'] = 0;
}
if(!isset($content['image_list']['crop'])) {
	$content['image_list']['crop'] = 0;
}
if(!isset($content['image_list']['custom'])) {
    $content['image_list']['custom'] = array();
}

$content["text"] = $row["acontent_text"];
$content['custom_fields'] = $content['image_list']['custom'];
