<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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



// Content Type 89: Poll			jens

$content["poll_list"] 		= isset($_POST["cimage_list"]) ? $_POST["cimage_list"] : array();

$content["img_width"]		= intval($_POST["cpoll_width"]) ? intval($_POST["cpoll_width"]) : "";
$temp_width					= $content["img_width"];

$content["img_height"] 		= intval($_POST["cpoll_height"]) ? intval($_POST["cpoll_height"]) : "";
$temp_height 				= $content["img_height"];

$content["poll_caption"] 	= clean_slweg($_POST["cpoll_caption"], 65000);
$content["poll_zoom"] 		= isset($_POST["cpoll_zoom"]) ? 1 : 0;
$content["poll_cctext"] 	= explode("\n", $content["poll_caption"]);

$content["poll_buttontext"] 	= clean_slweg($_POST["cpoll_buttontext"]);
$content["poll_buttonstyle"] 	= clean_slweg($_POST["cpoll_buttonstyle"]);
/* not really neccessary, if empty default submit text is used
if(empty($content["poll_buttontext"])) {
	$content["poll_buttontext"] = "";
}
*/

$content['tmp_images']		= array();
$imgx = 0;

if (is_array($content["poll_list"]) && sizeof($content["poll_list"])) 
{
	$img_sql = "SELECT * FROM " . DB_PREPEND . "phpwcms_file WHERE (";
	$img_sort = array();

	foreach($content["poll_list"] as $key => $value) 
	{
		if ($imgx) $img_sql .= " OR ";
		$img_sql .= "f_id=" . intval($value);
		$imgx++; 
	}
	if(!$imgx) 
	{
		$img_sql .= "0";
	}
	$img_sql .= ")";

	if ($img_result = mysql_query($img_sql, $db) or die("error while getting content image only info")) 
	{
	
		$temp_count_img = $imgx;
		
		$temp_img_maxwidth = $phpwcms["content_width"];

		if (($content["img_width"] > $temp_img_maxwidth) || ($content["img_width"] == "")) 
		{
			$content["img_width"] = $temp_img_maxwidth;
			$temp_width = $content["img_width"];
		}
		
		$imgx = 0;
		
		$temp_img_row = array();
		while ($img_row = mysql_fetch_assoc($img_result)) 
		{
			$temp_img_row[$img_row['f_id']] = $img_row;
		}
		mysql_free_result($img_result);
		
		foreach($content["poll_list"] as $key => $value) 
		{
			if(isset($temp_img_row[$value])) 
			{
				$content['tmp_images'][$key][0]	= $temp_img_row[$value]['f_id'];
				$content['tmp_images'][$key][1]	= $temp_img_row[$value]['f_name'];
				$content['tmp_images'][$key][2]	= $temp_img_row[$value]['f_hash'];
				$content['tmp_images'][$key][3]	= $temp_img_row[$value]['f_ext'];
				$content['tmp_images'][$key][4]	= $temp_width;
				$content['tmp_images'][$key][5]	= $temp_height;
			}
		}		
	}
}


$content['poll_list'] 				= array();
$content['poll_list']['images']	= $content['tmp_images'];
$content['poll_list']['width']		= $temp_width;
$content['poll_list']['height']	= $temp_height;
$content['poll_list']['zoom']		= $content["poll_zoom"];

$content['poll_form']['choice']	= $content['poll_cctext'];
$content['poll_form']['count'] = array();
$content['poll_form']['ip'] = array();
foreach($content['poll_cctext'] as $key => $value)
{
	$content['poll_form']['count'][$key] = 0; // initialize count to zero
}

$content['poll_text']['poll_buttontext']	= $content['poll_buttontext'];
$content['poll_text']['poll_buttonstyle']	= $content['poll_buttonstyle'];


?>