<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/


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