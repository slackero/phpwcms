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



// Content Type Link/Email
$content["link"] = explode(" ", $row["acontent_redirect"]);
if(isset($content["link"][1])) {
	$content["target"] = $content["link"][1];
} else {
	$content["target"] = '';
}
$content["link"] 		= $content["link"][0];
$content["template"]	= $row["acontent_template"];
