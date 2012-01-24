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



// Content Type E-Card
$content['ecard']['images']		= array();
$content["ecard"]["list"] 		= isset($_POST["cimage_list"]) ? $_POST["cimage_list"] : array();

$content["ecard"]["width"]		= (intval($_POST["cecard_width"]))  ? intval($_POST["cecard_width"])  : '';
$content["ecard"]["height"]		= (intval($_POST["cecard_height"])) ? intval($_POST["cecard_height"]) : '';
$temp_width						= $content["ecard"]["width"];
$temp_height					= $content["ecard"]["height"];

$content["ecard"]["space"]		= intval($_POST["cecard_space"]);
$content["ecard"]["col"]		= intval($_POST["cecard_col"]);
$content["ecard"]["pos"]		= intval($_POST["cecard_pos"]);
$content["ecard"]["caption"]	= clean_slweg($_POST["cecard_caption"]);

$content["ecard"]["subject"]	= clean_slweg($_POST["cecard_subject"]);
$content["ecard"]["form"]		= slweg($_POST["cecard_form"]);
$content["ecard"]["mail"]		= slweg($_POST["cecard_mail"]);
$content["ecard"]["send"]		= slweg($_POST["cecard_send"]);
$content["ecard"]["zoom"]		= isset($_POST["cecard_zoom"]) ? 1 : 0;
$content["ecard"]["selector"]	= isset($_POST["cecard_selector"]) ? intval($_POST["cecard_selector"]) : 0;
$content["ecard"]["onover"]		= slweg($_POST["cecard_onover"]);
$content["ecard"]["onclick"]	= slweg($_POST["cecard_onclick"]);
$content["ecard"]["onout"]		= slweg($_POST["cecard_onout"]);

$imgx = 0;

$content["ecard"]["image_cctext"] 	= explode("\n", $content["ecard"]["caption"]);

// remove form tag from form template
$content["ecard"]["form"] = preg_replace("'<form[^>]*?>(.*?)</form>'si", '$1', $content["ecard"]["form"]);
				
if(is_array($content["ecard"]["list"]) && count($content["ecard"]["list"])) {

	$img_sql 	= "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE (";
	$img_sort 	= array();
	
	foreach($content["ecard"]["list"] as $key => $value) {
		if ($imgx) $img_sql .= " OR ";
		$img_sql .= "f_id=" . intval($value);
		$imgx++;
	}
	if(!$imgx) {
		$img_sql .= "0";
	}
	$img_sql .= ");";
					
	// check for image information and get alle infos from file
	if ($img_result = mysql_query($img_sql, $db) or die("error while getting content image only info")) {
	
		// Gegenrechnen von Breite zu Anzahl Spalten und Bildabstand
		$temp_count_img = mysql_num_rows($img_result);
		if($content["ecard"]["col"] > $temp_count_img) $content["ecard"]["col"] = $temp_count_img;
		$temp_img_maxwidth = $phpwcms["content_width"] - (($content["ecard"]["col"] - 1) * $content["ecard"]["space"]);
		$temp_img_maxwidth = intval($temp_img_maxwidth / $content["ecard"]["col"]);
						
		if (($content["ecard"]["width"] > $temp_img_maxwidth) || ($content["ecard"]["width"] == "")) {
			$content["ecard"]["width"] = $temp_img_maxwidth;
			$temp_width = $content["ecard"]["width"];
		}
		
		$imgx = 0;
		$current_img_key = 0;
						
		while ($img_row = mysql_fetch_assoc($img_result)) {
		
			// set correct sorting
			foreach($content["ecard"]["list"] as $key => $value) {
				if($value == $img_row['f_id']) {
					$current_img_key = $key;
					unset($content["ecard"]["list"][$key]);
					break;
				}
			}
			$content['ecard']['images'][$current_img_key][0]	= $img_row['f_id'];
			$content['ecard']['images'][$current_img_key][1]	= $img_row['f_name'];
			$content['ecard']['images'][$current_img_key][2]	= $img_row['f_hash'];
			$content['ecard']['images'][$current_img_key][3]	= $img_row['f_ext'];
			$content['ecard']['images'][$current_img_key][4]	= $temp_width;
			$content['ecard']['images'][$current_img_key][5]	= $temp_height;
			$content['ecard']['images'][$current_img_key][6]	= isset($content["ecard"]["image_cctext"][$current_img_key]) ? trim($content["ecard"]["image_cctext"][$current_img_key]) : '';
			
			$imgx++;
							
		}
		mysql_free_result($img_result);
		ksort($content['ecard']['images']);
	}
}

unset($content["ecard"]["list"]);


?>