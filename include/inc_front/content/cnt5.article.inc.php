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



//link list
// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/linklist.tmpl')) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/linklist.tmpl') );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/linklist/'.$crow["acontent_template"])) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/linklist/'.$crow["acontent_template"]) );

} else {

	$crow["acontent_template"]	= '<!--LINKLIST_START//-->
[TITLE]<h4>{TITLE}</h4>
[/TITLE][SUBTITLE]<h5>{SUBTITLE}</h5>
[/SUBTITLE][LINKLIST]
<ul class="linklist">
{LINKLIST}
</ul>
[/LINKLIST]<!--LINKLIST_END//-->
<!--LINKLIST_ENTRY_START//-->[LINK]	<li><a href="{LINK}"{TARGET}>{LINKNAME}</a></li>[/LINK]<!--LINKLIST_ENTRY_END//-->
<!--LINKLIST_SPACE_START//-->
<!--LINKLIST_SPACE_END//-->';

}

$content['linklist']		= get_tmpl_section('LINKLIST', $crow["acontent_template"]);
$content['linklist_entry']	= get_tmpl_section('LINKLIST_ENTRY', $crow["acontent_template"]);
$content['linklist_space']	= get_tmpl_section('LINKLIST_SPACE', $crow["acontent_template"]);

$content['linklist'] = str_replace('{ID}', $crow['acontent_id'], $content['linklist']);
$content['linklist'] = render_cnt_template($content['linklist'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$content['linklist'] = render_cnt_template($content['linklist'], 'ATTR_ID', html($crow['acontent_attr_id']));
$content['linklist'] = render_cnt_template($content['linklist'], 'TITLE', html($crow['acontent_title']));
$content['linklist'] = render_cnt_template($content['linklist'], 'SUBTITLE', html($crow['acontent_subtitle']));

$link  = explode(LF, $crow["acontent_text"]);

if(count($link)) {

	$tmp = array();

	foreach($link as $key => $value) {

		$link = explode("|", $value);
		$link["name"] = trim($link[0]);
		if(empty($link[1])) {
			$link["link"] = '#';
			$link["target"] = '';
		} else {
			$link["link"] = explode(' ', trim($link[1]) );
			$link["target"] = empty($link["link"][1]) ? '' : ' target="' . trim($link["link"][1]) .'"';
			$link["link"] = trim($link["link"][0]);
		}
		$link['title'] = empty($link[2]) ? '' : ' title="' . html_specialchars(trim($link[2])) . '"';

		if($link["name"] === '') {
			$link["name"] = $link["link"];
		}

		$tmp[$key] = render_cnt_template($content['linklist_entry'], 'LINK', html_specialchars($link["link"]));
		$tmp[$key] = str_replace('{TARGET}', $link["target"], $tmp[$key]);
		$tmp[$key] = str_replace('{LINKNAME}', html_specialchars($link["name"]), $tmp[$key]);
		$tmp[$key] = str_replace('{LINKTITLE}', $link["title"], $tmp[$key]);

	}

	$content['linklist'] = render_cnt_template($content['linklist'], 'LINKLIST', implode($content['linklist_space'], $tmp));

}

$CNT_TMP .= $content['linklist'];
