<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type Text with Image
$content["text"]				= $row["acontent_text"];
$content["template"]			= $row["acontent_template"];
$content["cimage"]				= @unserialize($row["acontent_form"]);
if(!is_array($content["cimage"])) {
	$content["cimage"] = array();
}

// 0   :1       :2   :3        :4    :5     :6      :7       :8
// dbid:filename:hash:extension:width:height:caption:position:zoom
$content["image_info"]			= explode(":", $row["acontent_image"]);

$content["image_id"]			= $content["image_info"][0];
$content["image_name"]			= isset($content["image_info"][1]) ? $content["image_info"][1] : '';

$content["image_hash"]			= isset($content["image_info"][2]) ? $content["image_info"][2] : '';
$content["image_ext"]			= isset($content["image_info"][3]) ? $content["image_info"][3] : '';

$content["image_width"]			= isset($content["image_info"][4]) ? $content["image_info"][4] : '';
$content["image_height"]		= isset($content["image_info"][5]) ? $content["image_info"][5] : '';

$content["image_caption"]		= isset($content["image_info"][6]) ? base64_decode($content["image_info"][6]) : '';

$content["image_pos"]			= isset($content["image_info"][7]) ? $content["image_info"][7] : 0;
$content["image_zoom"]			= isset($content["image_info"][8]) ? $content["image_info"][8] : 0;
								 
unset($content["image_info"]);

?>