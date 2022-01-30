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

$content["text"] = $row["acontent_text"];
