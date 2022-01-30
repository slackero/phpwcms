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
$content["text"] 			= $row["acontent_text"];
$content["template"]		= $row["acontent_template"];
$content["ctext_format"]	= @unserialize($row["acontent_form"]);
$content["ctext_format"]	= isset($content["ctext_format"]['ctext_format']) ? $content["ctext_format"]['ctext_format'] : 'plain';

switch($content["ctext_format"]) {
	case 'plain':
	case 'markdown':
	case 'textile':	break;
	default: $content["ctext_format"] = 'plain';
}
