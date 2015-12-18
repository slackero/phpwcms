<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
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
	$tabs['custom_tab_fields'] = empty($template_default['settings']['tabs_custom_fields']) ? array() : array_keys($template_default['settings']['tabs_custom_fields']);

	foreach($tabs['tabs'] as $key => $entry) {

		$tabs['entries'][$key] = render_cnt_template($tabs['tmpl_entry'], 'TABTITLE', $entry['tabtitle'] == '-' ? '' : html_specialchars($entry['tabtitle']));
		$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABCONTENT', trim($entry['tabheadline'].$entry['tabtext']) === '' ? '' : LF);
		$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABHEADLINE', html_specialchars($entry['tabheadline']));
		$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABTEXT', $entry['tabtext']);

		if(empty($entry['tablink'])) {
			$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABLINK', '');
		} else {
			$entry['tablink'] = get_redirect_link($entry['tablink'], ' ', '');
			$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABLINK', $entry['tablink']['link']);
			$tabs['entries'][$key] = str_replace('{TARGET}', $entry['tablink']['target'], $tabs['entries'][$key]);
		}

		if(!empty($entry['custom_fields']) && count($entry['custom_fields'])) {

			if(count($tabs['custom_tab_fields'])) {
				$tabs['custom_field_items'] = array_unique( array_merge($tabs['custom_tab_fields'], array_keys($entry['custom_fields'])) );
			} else {
				$tabs['custom_field_items'] = array_keys($entry['custom_fields']);
			}

		} else {

			$tabs['custom_field_items'] = $tabs['custom_tab_fields'];

		}

		if($tabs['custom_field_items']) {
			foreach($tabs['custom_field_items'] as $custom_field_key) {
				$custom_field_value = isset($entry['custom_fields'][$custom_field_key]) ? $entry['custom_fields'][$custom_field_key] : '';
				$custom_field_key = 'TAB_'.strtoupper($custom_field_key);
				if(substr($custom_field_key, -5) === '_HTML') {
					$custom_field_key = substr($custom_field_key, 0, -5);
					$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_key, $custom_field_value);
				} else {
					$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_key, html($custom_field_value));
				}
			}
		}

	}

	$tabs['template']	= render_cnt_template($tabs['template'], 'TITLE', html_specialchars($crow['acontent_title']));
	$tabs['template']	= render_cnt_template($tabs['template'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
	$tabs['template']	= render_cnt_template($tabs['template'], 'TABS_ENTRIES', count($tabs['entries']) ? implode('', $tabs['entries']) : '');


	$CNT_TMP .= str_replace('{ID}', $crow['acontent_id'], $tabs['template']);

} else {

	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
	$CNT_TMP .= LF . $crow["acontent_html"];

}

unset($tabs);

?>