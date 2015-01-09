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



//link & email

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
list($link["link"], $link["target"]) = explode(" ", $crow["acontent_redirect"]);
$CNT_TMP .= $template_default["article"]["link_email_before"];
$CNT_TMP .= "<a href=\"".$link["link"]."\"".(($link["target"])?" target=\"".$link["target"]."\"":"").">";
$CNT_TMP .= html_specialchars(trim(str_replace("mailto:", "", $link["link"])))."</a>";
$CNT_TMP .= $template_default["article"]["link_email_after"];
									
?>