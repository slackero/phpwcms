<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// Content Type Bid
$content["bid"]["start_date"]			= clean_slweg($_POST["cbid_startdate"]);
$content["bid"]["end_date"]				= clean_slweg($_POST["cbid_enddate"]);

$content["bid"]["emailfrom"]			= clean_slweg($_POST["cbid_emailfrom"]);
if(!is_valid_email($content["bid"]["emailfrom"])) {
	$content["bid"]["emailfrom"] = $phpwcms['SMTP_FROM_EMAIL'];
}
$content["bid"]["emailfromname"]		= clean_slweg($_POST["cbid_emailfromname"]);
if(!$content["bid"]["emailfromname"]) $content["bid"]["emailfromname"] = $phpwcms['SMTP_FROM_NAME'];

if($content["bid"]["start_date"]) {
	$content["bid"]["start_date"] = strtotime($content["bid"]["start_date"]);
	if($content["bid"]["start_date"] == -1) $content["bid"]["start_date"] = '';
}
if($content["bid"]["end_date"]) {
	$content["bid"]["end_date"] = strtotime($content["bid"]["end_date"]);
	if($content["bid"]["end_date"] == -1) $content["bid"]["end_date"] = '';
}

$content["bid"]["before"]				= slweg($_POST["cbid_before"]);
$content["bid"]["after"] 				= slweg($_POST["cbid_after"]);
$content["bid"]["text"] 				= slweg($_POST["cbid_text"]);

$content["bid"]["form"] 				= slweg($_POST["cbid_form"]);
$content["bid"]["form"] 				= preg_replace("'<form[^>]*?>(.*?)</form>'si", '$1', $content["bid"]["form"]);


$content["bid"]["sent"] 				= slweg($_POST["cbid_sent"]);
$content["bid"]["verified"] 			= slweg($_POST["cbid_verified"]);
$content["bid"]["notverified"] 			= slweg($_POST["cbid_notverified"]);
$content["bid"]["emailmsg"] 			= slweg($_POST["cbid_emailmsg"]);

$content["bid"]["startbid"] 			= floatval($_POST["cbid_startbid"]);
$content["bid"]["nextbidadd"] 			= floatval($_POST["cbid_nextbidadd"]);

$content["bid"]["image_name"]			= clean_slweg($_POST["cimage_name"]);
$content["bid"]["image_id"]				= intval($_POST["cimage_id"]);

$content["bid"]["image_width"]			= intval($_POST["cimage_width"]);
$content["bid"]["image_height"]			= intval($_POST["cimage_height"]);
$content["bid"]["image_zoom"]			= isset($_POST["cimage_zoom"]) ? intval($_POST["cimage_zoom"]) : 0;

if(!$content["bid"]["image_width"])		$content["bid"]["image_width"] = '';
if(!$content["bid"]["image_height"])	$content["bid"]["image_height"] = '';

//prepare bid image
if($content["bid"]["image_width"] > $phpwcms["content_width"] || $content["bid"]["image_width"] == '') {
	$content["bid"]["image_width"] = $phpwcms["content_width"];
}

/*
if ($content["bid"]["image_id"] || $content["bid"]["image_name"]) {

	// check for image information and get alle infos from file
	$img_sql  = "SELECT f_thumb_preview, f_thumb_list FROM ";
	$img_sql .= DB_PREPEND . "phpwcms_file WHERE f_id=" . $content["bid"]["image_id"];
	$img_sql .= " AND f_name='" . aporeplace($content["bid"]["image_name"]) . "' LIMIT 1;";

	if ($img_result = mysql_query($img_sql, $db) or die("error while getting content image info")) {

		if ($img_row = mysql_fetch_row($img_result)) {
                    
			$content["bid"]["image_prev"] = $img_row[0];
			$content["bid"]["image_list"] = $img_row[1];
			$content["bid"]["image_prev_info"] = getimagesize(PHPWCMS_ROOT.'/'.$phpwcms["file_tmp"].$phpwcms["dir_preview"].$content["bid"]["image_prev"]);
			$content["bid"]["image_prev_make"] = 0; 
			
			// check real image sizes
			if(	$content["bid"]["image_width"] >= $content["bid"]["image_prev_info"][0] && 
				$content["bid"]["image_height"] > $content["bid"]["image_prev_info"][1])
			{
				//only make a copy of the image
				copy(	PHPWCMS_ROOT."/".$phpwcms["file_tmp"].$phpwcms["dir_preview"].$content["bid"]["image_prev"], 
						PHPWCMS_ROOT."/".$phpwcms["content_path"].$phpwcms["cimage_path"].cut_ext($content["bid"]["image_prev"])."-"
						.$content["bid"]["image_prev_info"][0]."x".$content["bid"]["image_prev_info"][1]
						.".".which_ext($content["bid"]["image_prev"]));
				
				//set that that preview make done
				$content["bid"]["image_prev_make"] = 1;
			} 
			
			if($content["bid"]["image_width"] > $content["bid"]["image_prev_info"][0]) {
				$content["bid"]["image_width"] = $content["bid"]["image_prev_info"][0];
			}
			
			if($content["bid"]["image_height"] > $content["bid"]["image_prev_info"][1]) {
				$content["bid"]["image_height"] = $content["bid"]["image_prev_info"][1];
			}

			$content["bid"]["image_add"] = "-".$content["bid"]["image_width"]."x".$content["bid"]["image_height"];
					
			$content["bid"]["image_cname"]  = cut_ext($content["bid"]["image_prev"]).$content["bid"]["image_add"].".";
			$content["bid"]["image_cname"] .= which_ext($content["bid"]["image_prev"]);
                
		}
		mysql_free_result($img_result);
	
	}
	
}
*/
		
// now check if new resized image should be created
if(isset($content["bid"]["image_prev_info"]) && count($content["bid"]["image_prev_info"]) && !$content["bid"]["image_prev_make"] && 
	!file_exists(PHPWCMS_ROOT."/".$phpwcms["content_path"].$phpwcms["cimage_path"].$content["bid"]["image_cname"])) {
	
	include_once "include/inc_lib/imagick.convert.inc.php";
	$old_abort = ignore_user_abort(true);
	$create_cimage = imagick_converting($content["bid"]["image_prev"], $content["bid"]["image_add"], PHPWCMS_ROOT."/".$phpwcms["file_tmp"].$phpwcms["dir_preview"], PHPWCMS_ROOT."/".$phpwcms["content_path"].$phpwcms["cimage_path"], $content["bid"]["image_width"], $content["bid"]["image_height"]);
	ignore_user_abort($old_abort);
	if ($create_cimage["error"]) $content["error"][60] = "Error while creating image preview";

}




?>