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
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//
// Content Type File List
//
$content["file_list"]				= isset($_POST["cfile_list"]) && is_array($_POST["cfile_list"]) ? $_POST["cfile_list"] : array();
$content["file_template"]			= clean_slweg($_POST['cfile_template']);
$content['file']['direct_download']	= empty($_POST['cfile_direct']) ? 0 : 1;
$content["html"]					= slweg($_POST["chtml"]);

/*
preg_match_all('/<\/p/is', $content["html"], $matches);
if(isset($matches[0]) && is_array($matches[0]) && count($matches[0]) == 1) {
	//$content["html"] = strip_selected_tags($content["html"], array('p'));
}
*/

//
// first get all file IDs
//
if (count($content["file_list"])) {
	foreach($content["file_list"] as $key => $value) {
		if (intval($value)) {
			$content["file_list"][$key] = intval($value);
		} else {
			unset($content["file_list"][$key]);
		}
	}
	if (count($content["file_list"])) {
		$content["file_id_list"] = implode(":", $content["file_list"]);
	} else {
		$content["file_id_list"] = '';
	}
}

$content["file_descr"] = explode("\n", slweg($_POST["cfile_descr"], 0, false));

//
// now check if there are more settings for each file - explode by |
//
// [0] = normal file description like before
// [1] = name the file (it's not the file name)
// [2] = title
// [3] = target (where to open a new file -> default is _blank even if empty
// [4] = if it is an image try to show a thumbnail instead of the file icon -> here thumbnail WIDTHxHEIGHT

if(count($content["file_descr"])) {

	foreach($content["file_descr"] as $key => $value) {

		$value = explode('|', $value, 5);

		$value[0] = trim($value[0], ' ');
		$value[1] = empty($value[1]) ? '' : trim($value[1]);
		$value[2] = empty($value[2]) ? '' : trim($value[2]);
		$value[3] = empty($value[3]) ? '' : trim($value[3]);
		$value[4] = empty($value[4]) ? '' : strtolower(trim($value[4]));

		$value[4] = explode('x', $value[4]);
		$value[4][0] = intval($value[4][0]);
		if(empty($value[4][0])) $value[4][0] = '';

		if(empty($value[4][1])) {
			$value[4][1] = '';
		} else {
			$value[4][1] = intval($value[4][1]);
			if(empty($value[4][1])) $value[4][1] = '';
		}
		$value[4][2] = empty($value[4][2]) ? '' : (intval($value[4][2]) ? 'x1' : '');
		$value[4] = ($value[4][0].$value[4][1]) == '' ? '' : $value[4][0].'x'.$value[4][1].$value[4][2];

		if(empty($value[4]))
		{
			unset($value[4]);
			if(empty($value[3]))
			{
				unset($value[3]);
				if(empty($value[2]))
				{
					unset($value[2]);
					if(empty($value[1]))
					{
						unset($value[1]);
					}
				}
			}
		}
		$content["file_descr"][$key] = implode('|', $value);
		$value = '';
	}

	$content["file_descr"] = implode("\n", $content["file_descr"]);


} else {

	$content["file_descr"] = '';

}
