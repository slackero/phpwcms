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

//PHP variables
if($crow["acontent_text"]) {
	$crow["acontent_text"] = parse_ini_str($crow["acontent_text"]);
	if(is_array($crow["acontent_text"]) && count($crow["acontent_text"])) {
		$GLOBALS['CUSTOM'] = array_merge( $GLOBALS['CUSTOM'], $crow["acontent_text"]);
	}
}
