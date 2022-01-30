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

//link & email

$crow['attr_class_id'] = array();
if($crow['acontent_attr_class']) {
    $crow['attr_class_id'][] = 'class="'.html($crow['acontent_attr_class']).'"';
}
if($crow['acontent_attr_id']) {
    $crow['attr_class_id'][] = 'id="'.html($crow['acontent_attr_id']).'"';
}

if(($crow['attr_class_id'] = implode(' ', $crow['attr_class_id']))) {
    $CNT_TMP .= '<div '.$crow['attr_class_id'].'>';
    $crow['attr_class_id_close'] = '</div>';
} else {
    $crow['attr_class_id_close'] = '';
}

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
list($link["link"], $link["target"]) = explode(" ", $crow["acontent_redirect"]);
$CNT_TMP .= $template_default["article"]["link_email_before"];
$CNT_TMP .= "<a href=\"".$link["link"]."\"".(($link["target"])?" target=\"".$link["target"]."\"":"").">";
$CNT_TMP .= html(trim(str_replace("mailto:", "", $link["link"])))."</a>";
$CNT_TMP .= $template_default["article"]["link_email_after"];
$CNT_TMP .= $crow['attr_class_id_close'];