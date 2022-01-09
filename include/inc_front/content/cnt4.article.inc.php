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



//bullet list
// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/bulletlist.tmpl')) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/bulletlist.tmpl') );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/bulletlist/'.$crow["acontent_template"])) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/bulletlist/'.$crow["acontent_template"]) );

} else {

	$crow["acontent_template"]  = '[TITLE]<h4>{TITLE}</h4>'.LF.'[/TITLE][SUBTITLE]<h5>{SUBTITLE}</h5>'.LF.'[/SUBTITLE]';
	$crow["acontent_template"] .= '[BULLETLIST]<ul class="bulletlist">{BULLETLIST}<!--BULLETLIST_ITEM_START//--><li>{BULLETLIST_ITEM}</li><!--BULLETLIST_ITEM_END//--></ul>[/BULLETLIST]';

}

$crow['bulletlist_item_template'] = get_tmpl_section('BULLETLIST_ITEM', $crow["acontent_template"]);
$crow["acontent_template"] = replace_tmpl_section('BULLETLIST_ITEM', $crow["acontent_template"]);
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_ID', html($crow['acontent_attr_id']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TITLE', html($crow['acontent_title']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html($crow['acontent_subtitle']));

$crow['bullets'] = convertStringToArray($crow["acontent_text"], LF, false);

if(count($crow['bullets'])) {

	$crow['bulletlist_items'] = array();

	foreach($crow['bullets'] as $item) {

		$crow['bulletlist_items'][] = str_replace('{BULLETLIST_ITEM}', html($item), $crow['bulletlist_item_template']);

	}

	$crow['bulletlist_items'] = implode(LF, $crow['bulletlist_items']);

} else {

	$crow['bulletlist_items'] = '';

}

$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'BULLETLIST', $crow['bulletlist_items']);
$crow["acontent_template"] = str_replace('{ID}', $crow['acontent_id'], $crow["acontent_template"]);

$CNT_TMP .= $crow["acontent_template"];
