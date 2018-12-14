<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//code

// read template
if(empty($crow["acontent_template"]) && is_file(CMSGO_TEMPLATE.'inc_default/code.tmpl')) {

	$crow["acontent_template"] = render_device( @file_get_contents(CMSGO_TEMPLATE.'inc_default/code.tmpl') );

} elseif(is_file(CMSGO_TEMPLATE.'inc_cntpart/code/'.$crow["acontent_template"])) {

	$crow["acontent_template"] = render_device( @file_get_contents(CMSGO_TEMPLATE.'inc_cntpart/code/'.$crow["acontent_template"]) );

} else {

	$crow["acontent_template"] = '[TITLE]<h3>{TITLE}</h3>[/TITLE][SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE][CODE]<code>{CODE}</code>[/CODE]';

}

if($crow["acontent_text"]) {
    $crow["acontent_text"] = str_replace(array(' ', "\t", '[', ']', '{', '}'), array('&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;', '&#x5B;', '&#x5D;', '&#x7B;', '&#x7D;'), html($crow["acontent_text"]));

    if(strpos($crow["acontent_template"], '<pre') === false) {
        $crow["acontent_text"] = nl2br($crow["acontent_text"]);
    }
}

$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_ID', html($crow['acontent_attr_id']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TITLE', html($crow['acontent_title']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html($crow['acontent_subtitle']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'CODE', $crow["acontent_text"]);
$crow["acontent_template"] = str_replace('{ID}', $crow['acontent_id'], $crow["acontent_template"]);

$CNT_TMP .= $crow["acontent_template"];

unset($crow["acontent_template"], $crow["acontent_text"]);
