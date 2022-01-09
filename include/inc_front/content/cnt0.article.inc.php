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



//default Plain Text

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/plaintext.tmpl')) {

    $crow["acontent_template"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/plaintext.tmpl') );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/plaintext/'.$crow["acontent_template"])) {

    $crow["acontent_template"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/plaintext/'.$crow["acontent_template"]) );

} else {

    $crow["acontent_template"] = '[TITLE]<h4>{TITLE}</h4>'.LF.'[/TITLE][SUBTITLE]<h5>{SUBTITLE}</h5>'.LF.'[/SUBTITLE][TEXT]<p>{TEXT}</p>[/TEXT]';

}

$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_ID', html($crow['acontent_attr_id']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TITLE', html_specialchars($crow['acontent_title']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));

$crow["acontent_form"] = @unserialize($crow["acontent_form"]);
$crow["acontent_form"] = isset($crow["acontent_form"]['ctext_format']) ? $crow["acontent_form"]['ctext_format'] : 'plain';

switch($crow["acontent_form"]) {

    case 'markdown':
        init_markdown();
        $crow['acontent_text'] = $phpwcms['parsedown_class']->text($crow['acontent_text']);
        break;

    case 'textile':
        init_textile();
        $crow['acontent_text'] = $phpwcms['textile_class']->textileThis($crow['acontent_text']);
        break;

    case 'plain':
        $crow['acontent_text'] = plaintext_htmlencode($crow['acontent_text']);
        break;

}

$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TEXT', $crow['acontent_text']);
$crow["acontent_template"] = str_replace('{ID}', $crow['acontent_id'], $crow["acontent_template"]);

$CNT_TMP .= LF.trim($crow["acontent_template"]).LF;
