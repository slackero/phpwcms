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

// Content Type Multimedia

$content["media"] = @unserialize($row["acontent_form"]);
$content["media_type"] = $content['media']["media_type"];
$content["media_player"] = $content['media']["media_player"];
$content["media_src"] = $content['media']["media_src"];
$content["media_auto"] = $content['media']["media_auto"];
$content["media_transparent"] = $content['media']["media_transparent"];
$content["media_control"] = $content['media']["media_control"];
$content["media_pos"] = $content['media']["media_pos"];
$content["media_width"] = $content['media']["media_width"];
$content["media_height"] = $content['media']["media_height"];
$content["media_id"] = $content['media']["media_id"];
$content["media_name"] = $content['media']["media_name"];
$content["media_extern"] = $content['media']["media_extern"];
$content["image_name"] = empty($content['media']["image_name"]) ? '' : $content['media']["image_name"];
$content["image_id"] = empty($content['media']["image_id"]) ? '' : $content['media']["image_id"];
$content["image_caption"] = empty($content['media']["image_caption"]) ? '' : $content['media']["image_caption"];
$content["template"] = $row["acontent_template"];
