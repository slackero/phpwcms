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


// Content Type Map
$content["map"] = array();
$content["map"]["template"]	= clean_slweg($_POST["cmap_template"]);
$content["map"]["text"]		= clean_slweg($_POST["cmap_text"]);
$content["map"]['image']	= isset($_POST["cmap_image"]) ? clean_slweg($_POST["cmap_image"]) : '';


if(isset($_POST['cmap_location_x']) && $content['id']) {
//if(isset($_POST['cmap_location_x']) && isset($_POST['cmap_location_edited']) && intval($_POST['cmap_location_edited']) && $content['id']) {
	//if location should be updated or ceated
	// cmap_location_x, cmap_location_y, cmap_location_title, 
	// cmap_location_zip, cmap_location_city, cmap_location_entry
	$content["location"]			= array();
	$content["location"]['id']		= intval($_POST["cmap_location_id"]);
	$content["location"]['x']		= intval($_POST["cmap_location_x"]);
	$content["location"]['y']		= intval($_POST["cmap_location_y"]);
	$content["location"]['title']	= clean_slweg($_POST["cmap_location_title"]);
	$content["location"]['zip']		= clean_slweg($_POST["cmap_location_zip"]);
	$content["location"]['city']	= clean_slweg($_POST["cmap_location_city"]);
	$content["location"]['entry']	= slweg($_POST["cmap_location_entry"]);
	if(!$_SESSION["WYSIWYG_EDITOR"]) {
		$content["location"]['entry'] = nl2br($content["location"]['entry']);
	} else {
		$content["location"]['entry'] = str_replace("\r\n", '', $content["location"]['entry']);
		$content["location"]['entry'] = str_replace("\n", '', $content["location"]['entry']);
	}
	
	if(!$content["location"]['title']) {
		$content["error"][] = $BL['be_cmap_location_error_notitle'];
	} else {
	
		$content["location"]['sql']  = "map_cid='".$content['id']."', ";
		$content["location"]['sql'] .= "map_x='".$content["location"]['x']."', ";
		$content["location"]['sql'] .= "map_y='".$content["location"]['y']."', ";
		$content["location"]['sql'] .= "map_title='".aporeplace($content["location"]['title'])."', ";
		$content["location"]['sql'] .= "map_zip='".aporeplace($content["location"]['zip'])."', ";
		$content["location"]['sql'] .= "map_city='".aporeplace($content["location"]['city'])."', ";
		$content["location"]['sql'] .= "map_entry='".aporeplace($content["location"]['entry'])."'";
	
		// create UPDATE or INSERT query for location
		if(!$content["location"]['id']) {
			// INSERT
			$content["location"]['sql']  = "INSERT INTO ".DB_PREPEND."phpwcms_map SET ".$content["location"]['sql'];
		} else {
			// UPDATE
			$content["location"]['sql']  = "UPDATE ".DB_PREPEND."phpwcms_map SET ".$content["location"]['sql']." ";
			$content["location"]['sql'] .= "WHERE map_cid=".$content['id']." AND map_id=".$content["location"]['id']." LIMIT 1";
		}
		mysql_query($content["location"]['sql'], $db);

	}

}

?>