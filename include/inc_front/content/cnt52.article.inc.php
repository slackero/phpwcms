<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
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
