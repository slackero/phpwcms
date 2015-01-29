<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
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



//bullet list table
$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$bullet  = explode("\n", chop($crow["acontent_text"]));
if(sizeof($bullet)) {
	$CNT_TMP .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	foreach($bullet as $value) {
		if($value) $CNT_TMP .= '<tr valign="top"><td nowrap="nowrap">'.$template_default["article"]["bullet_sign"]."</td><td>".$value."</td></tr>\n";
	}
	$CNT_TMP .= "</table>\n";
}


									
?>