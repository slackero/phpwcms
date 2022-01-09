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



// Content Type Multimedia
$content["media_type"]			= isset($_POST["cmedia_type"]) ? intval($_POST["cmedia_type"]) : 0;
$content["media_player"]		= isset($_POST["cmedia_player"]) ? intval($_POST["cmedia_player"]) : 0;
$content["media_src"]			= isset($_POST["cmedia_src"]) ? intval($_POST["cmedia_src"]) : 0;
$content["media_auto"]			= isset($_POST["cmedia_auto"]) ? intval($_POST["cmedia_auto"]) : 0;
$content["media_transparent"]	= empty($_POST["cmedia_transparent"]) ? 0 : 1;
$content["media_control"] 		= empty($_POST["cmedia_control"]) ? 0 : 1;
$content["media_pos"] 			= intval($_POST["cimage_pos"]);
$content["media_width"] 		= intval($_POST["cmedia_width"]);
$content["media_width"] 		= ($content["media_width"]) ? $content["media_width"] : '';
$content["media_height"] 		= intval($_POST["cmedia_height"]);
$content["media_height"] 		= ($content["media_height"]) ? $content["media_height"] : '';
$content["media_id"] 			= intval($_POST["cmedia_id"]);
$content["media_name"] 			= clean_slweg($_POST["cmedia_name"]);
$content["media_extern"] 		= clean_slweg($_POST["cmedia_extern"]);
$content["image_name"]			= clean_slweg($_POST["cimage_name"]);
$content["image_id"]			= empty($_POST["cimage_id"]) ? '' : intval($_POST["cimage_id"]);
$content["image_caption"]		= clean_slweg($_POST["cimage_caption"]);


$content['media']	= array();

$content['media']["media_type"]			= $content["media_type"];
$content['media']["media_player"]		= $content["media_player"];
$content['media']["media_src"]			= $content["media_src"];
$content['media']["media_auto"]			= $content["media_auto"];
$content['media']["media_transparent"]	= $content["media_transparent"];
$content['media']["media_control"]		= $content["media_control"];
$content['media']["media_pos"]			= $content["media_pos"];
$content['media']["media_width"]		= $content["media_width"];
$content['media']["media_height"]		= $content["media_height"];
$content['media']["media_id"]			= $content["media_id"];
$content['media']["media_name"]			= $content["media_name"];
$content['media']["media_extern"]		= $content["media_extern"];

$content['media']["image_name"]			= $content["image_name"];
$content['media']["image_id"]			= $content["image_id"];
$content['media']["image_caption"]		= $content["image_caption"];


$content["template"] = clean_slweg($_POST['template']);
