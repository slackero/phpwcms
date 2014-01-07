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


// tabs

$tabs			= array();
$tabs['tabs']	= @unserialize($crow["acontent_form"]);
unset($tabs['tabs']['tabwysiwygoff']);

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/tabs.tmpl')) {

	$tabs['template']	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/tabs.tmpl') );
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/tabs/'.$crow["acontent_template"])) {

	$tabs['template']	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/tabs/'.$crow["acontent_template"]) );

} else {

	$tabs['template']	= '';

}

if($tabs['template']) {

	$tabs['entries']	= array();

	$tabs['tmpl_entry']	= get_tmpl_section('TABS_ENTRY', $tabs['template']);
	$tabs['template']	= get_tmpl_section('TABS', $tabs['template']);
	
	foreach($tabs['tabs'] as $key => $entry) {
	
		$tabs['entries'][$key] = render_cnt_template($tabs['tmpl_entry'], 'TABTITLE', $entry['tabtitle'] == '-' ? '' : html_specialchars($entry['tabtitle']));
		$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABCONTENT', trim($entry['tabheadline'].$entry['tabtext']) == '' ? '' : LF);
		$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABHEADLINE', html_specialchars($entry['tabheadline']));
		$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABTEXT', $entry['tabtext']);
	
	}

	$tabs['template']	= render_cnt_template($tabs['template'], 'TITLE', html_specialchars($crow['acontent_title']));
	$tabs['template']	= render_cnt_template($tabs['template'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
	$tabs['template']	= render_cnt_template($tabs['template'], 'TABS_ENTRIES', count($tabs['entries']) ? implode('', $tabs['entries']) : '' );


	$CNT_TMP .= str_replace('{ID}', $crow['acontent_id'], $tabs['template']);

} else {
	
	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);	
	$CNT_TMP .= LF . $crow["acontent_html"];
	
}

unset($tabs);

?>