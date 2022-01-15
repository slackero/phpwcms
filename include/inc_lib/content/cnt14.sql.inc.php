<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
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
