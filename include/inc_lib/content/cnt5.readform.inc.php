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

// Content Type Link List
$content["text"] = str_replace(array('	', '   ', '  '), ' ', clean_slweg($_POST["ctext"]));
$clinklist = explode(LF, $content["text"]);
if(is_array($clinklist) && count($clinklist)) {
	$clink = array();
	foreach($clinklist as $key => $value) {
		$value = trim($value);
		if($value !== '') {
			$value = explode("|", trim($value), 3);
			$value = array(
				'name' => trim($value[0]),
				'link' => isset($value[1]) ? trim($value[1]) : '',
				'title' => isset($value[2]) ? trim($value[2]) : ''
			);

			if($value["link"] === '') {
				$value["link"] = $value["name"];
				$value["name"] = '';
			}
			if($value["title"] === '') {
				unset($value["title"]);
			}
			$clink[] = implode(' | ', $value);
		}
	}

	unset($clinklist);
	$content["text"] = implode(LF, $clink);
}
$content["template"] = clean_slweg($_POST['template']);
