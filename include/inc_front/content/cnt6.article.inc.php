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



//HTML

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/html.tmpl')) {

    $crow["acontent_template"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/html.tmpl') );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/html/'.$crow["acontent_template"])) {

    $crow["acontent_template"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/html/'.$crow["acontent_template"]) );

} else {

    $crow["acontent_template"] = '[TITLE]<h3>{TITLE}</h3>'.LF.'[/TITLE][SUBTITLE]<h4>{SUBTITLE}</h4>'.LF.'[/SUBTITLE][HTML]{HTML}[/HTML]';

}

$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_ID', html($crow['acontent_attr_id']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TITLE', html($crow['acontent_title']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html($crow['acontent_subtitle']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'HTML', $crow['acontent_html']);
$crow["acontent_template"] = str_replace('{ID}', $crow['acontent_id'], $crow["acontent_template"]);

$CNT_TMP .= LF.$crow["acontent_template"].LF;
