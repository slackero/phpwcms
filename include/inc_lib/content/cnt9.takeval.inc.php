<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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



// Content Type Multimedia
/*
$content["media"] 			= explode(":", $row["acontent_media"]);
$content["media_type"]		= isset($content["media"][0]) ? intval($content["media"][0]) : 0;
$content["media_player"]	= isset($content["media"][1]) ? intval($content["media"][1]) : 0;
$content["media_src"]		= isset($content["media"][5]) ? intval($content["media"][5]) : 0;
$content["media_pos"]		= isset($content["media"][2]) ? intval($content["media"][2]) : 0;
$content["media_control"]	= isset($content["media"][7]) ? intval($content["media"][7]) : 0;
$content["media_auto"]		= isset($content["media"][8]) ? intval($content["media"][8]) : 0;
$content["media_transparent"] = isset($content["media"][9]) ? intval($content["media"][9]) : 0;
$content["media_width"]		= isset($content["media"][3]) ? $content["media"][3] : '';
$content["media_height"]	= isset($content["media"][4]) ? $content["media"][4] : '';
if($content["media_src"]) {
	$content["media_id"]	= "";
	$content["media_name"]	= "";
	$content["media_extern"]= isset($content["media"][6]) ? base64_decode($content["media"][6]) : '';
} else {
	$content['temp_media'] = explode(":", !empty($content["media"][6]) ? base64_decode($content["media"][6]) : ':');
	$content["media_id"] = $content['temp_media'][0];
	$content["media_name"] = isset($content['temp_media'][1]) ? $content['temp_media'][1] : '';
 	$content["media_extern"] = "";
}
*/

$content["media"]	= @unserialize($row["acontent_form"]);

$content["media_type"]			= $content['media']["media_type"];
$content["media_player"]		= $content['media']["media_player"];
$content["media_src"]			= $content['media']["media_src"];
$content["media_auto"]			= $content['media']["media_auto"];
$content["media_transparent"]	= $content['media']["media_transparent"];
$content["media_control"]		= $content['media']["media_control"];
$content["media_pos"]			= $content['media']["media_pos"];
$content["media_width"]			= $content['media']["media_width"];
$content["media_height"]		= $content['media']["media_height"];
$content["media_id"]			= $content['media']["media_id"];
$content["media_name"]			= $content['media']["media_name"];
$content["media_extern"]		= $content['media']["media_extern"];


?>