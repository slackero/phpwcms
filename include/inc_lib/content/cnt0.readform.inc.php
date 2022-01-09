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
