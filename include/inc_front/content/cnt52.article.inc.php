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



//PHP variables

if($crow["acontent_text"]) {
	$crow["acontent_text"] = parse_ini_str($crow["acontent_text"]);
	if(is_array($crow["acontent_text"]) && count($crow["acontent_text"])) {
		$CUSTOM = array_merge( $CUSTOM, $crow["acontent_text"]);
	}
}

?>