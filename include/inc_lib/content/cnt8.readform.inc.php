<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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



// Content Type Link Articles

$content['alink']['alink_template']		= clean_slweg($_POST["calink_template"]);
$content['alink']['alink_allowedtags']	= slweg($_POST["calink_allowedtags"]);
$content['alink']['alink_id']			= (isset($_POST["calink"]) && is_array($_POST["calink"])) ? $_POST["calink"] : array();
$content['alink']['alink_level']		= (isset($_POST["calink_level"]) && is_array($_POST["calink_level"])) ? $_POST["calink_level"] : array();

// article select type
$content['alink']['alink_type']			= intval($_POST['calink_type']);
if($content['alink']['alink_type'] > 21) $content['alink']['alink_type'] = 0;

// summary wordlimit
$content['alink']['alink_wordlimit']	= intval($_POST['calink_wordlimit']);

// max auto article
$content['alink']['alink_max']			= intval($_POST['calink_max']);

// image settings
$content['alink']['alink_width']		= intval($_POST['calink_width']);
$content['alink']['alink_height']		= intval($_POST['calink_height']);
$content['alink']['alink_zoom']			= empty($_POST['calink_zoom']) ? 0 : 1;
$content['alink']['alink_unique']		= empty($_POST['calink_unique']) ? 0 : 1;
$content['alink']['alink_crop']			= empty($_POST['calink_crop']) ? 0 : 1;
$content['alink']['alink_prio']			= empty($_POST['calink_prio']) ? 0 : 1;

if(empty($content['alink']['alink_width'])) $content['alink']['alink_width'] = '';
if(empty($content['alink']['alink_height'])) $content['alink']['alink_height'] = '';
if(empty($content['alink']['alink_wordlimit'])) $content['alink']['alink_wordlimit'] = '';
if(empty($content['alink']['alink_max'])) $content['alink']['alink_max'] = '';


foreach($content['alink']['alink_id'] as $key => $value) {
	$value = intval($value);
	if($value) {
		$content['alink']['alink_id'][$key] = $value;
	} else {
		unset($content['alink']['alink_id'][$key]);
	}
}

?>