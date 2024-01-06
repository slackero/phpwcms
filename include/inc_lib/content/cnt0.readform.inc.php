<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Content Type Text
$content["text"] 			= isset($_POST["ctext"]) ? slweg($_POST["ctext"], 0, false) : '';
$content["template"]		= clean_slweg($_POST['template']);
$content["ctext_format"]	= clean_slweg($_POST['ctext_format']);

switch($content["ctext_format"]) {
	case 'plain':
	case 'markdown':
	case 'textile':	break;
	default: $content["ctext_format"] = 'plain';
}
