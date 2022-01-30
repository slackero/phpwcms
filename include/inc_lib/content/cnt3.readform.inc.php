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

// Content Type redirect
$content["link"]		= clean_slweg($_POST["clink"]);
$content["target"]		= slweg($_POST["ctarget"]);
$content["redirect"]	= $content["link"] . " " . $content["target"];
$content["template"]	= clean_slweg($_POST['template']);
