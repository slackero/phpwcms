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



// Content Type Link List
$content["text"] = str_replace('  ', ' ', clean_slweg($_POST["ctext"]));
$clinklist = explode(LF, $content["text"]);
if(is_array($clinklist) && count($clinklist)) {
	$clink = array();
	foreach($clinklist as $key => $value) {
		if (trim($value)) {
			$clink_it = explode("|", trim($value));
			$clink[$key]["name"] = $clink_it[0];
			$clink[$key]["link"] = isset($clink_it[1]) ? $clink_it[1] : '';
			if (isEmpty($clink[$key]["link"])) {
				$clink[$key]["link"] = trim($clink[$key]["name"]);
				$clink[$key]["name"] = "";
			} else {
				$clink[$key]["name"] = trim($clink[$key]["name"]);
				$clink[$key]["link"] = trim($clink[$key]["link"]);
			} 
		} 
	} 
	if(is_array($clink) && count($clink)) {
		unset($clinklist);
		foreach($clink as $key => $value) {
			$clink_it = explode(" ", $clink[$key]["link"]);
			$clink[$key]["link"] = $clink_it[0];
			$clink[$key]["target"] = isset($clink_it[1]) ? $clink_it[1] : '';
			$clinklist[$key] = trim($clink[$key]["name"] . "|" . $clink[$key]["link"] . " " . $clink[$key]["target"]);
		} 
		unset($clink);
	} 
	$content["text"] = implode(LF, $clinklist);
}
$content["template"] = clean_slweg($_POST['template']);

?>