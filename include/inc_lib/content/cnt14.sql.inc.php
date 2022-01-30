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


// Content Type WYSIWYG HTML
$SQL .= "acontent_html = "._dbEscape($content["html"]).", ";
$SQL .= "acontent_template = "._dbEscape($content["template"]).", ";
if(!empty($content['custom_fields']) && is_array($content['custom_fields'])) {
    $content['acontent_form'] = array(
        'cnt_fieldgroup' => $content['cnt_fieldgroup'],
        'cnt_fields' => $content['custom_fields']
    );
    $SQL .= "acontent_form = "._dbEscape(serialize($content['acontent_form']))." ";
    unset($content['acontent_form']);
} else {
    $SQL .= "acontent_form = "._dbEscape('')." ";
}
