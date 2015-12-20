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

$tabs['tab_fieldgroup']	= empty($tabs['tabs']['tab_fieldgroup']) ? '' : $tabs['tabs']['tab_fieldgroup'];
unset($tabs['tabs']['tab_fieldgroup']);

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/tabs.tmpl')) {

	$tabs['template']	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/tabs.tmpl') );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/tabs/'.$crow["acontent_template"])) {

	$tabs['template']	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/tabs/'.$crow["acontent_template"]) );

} else {

	$tabs['template']	= '';

}

if($tabs['template']) {

	$tabs['entries']		= array();

	$tabs['tmpl_entry']		= get_tmpl_section('TABS_ENTRY', $tabs['template']);
	$tabs['template']		= get_tmpl_section('TABS', $tabs['template']);

	if($tabs['tab_fieldgroup'] === '' || empty($template_default['settings']['tabs_custom_fields'][ $tabs['tab_fieldgroup'] ]['fields'])) {
		$tabs['custom_tab_fields'] = array();
	} else {
		$tabs['custom_tab_fields'] = array_keys($template_default['settings']['tabs_custom_fields'][ $tabs['tab_fieldgroup'] ]['fields']);
		$tabs['field_render'] = array('html', 'markdown', 'plain');
		$tabs['fieldgroup'] =& $template_default['settings']['tabs_custom_fields'][ $tabs['tab_fieldgroup'] ];
	}

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

		if($tabs['custom_tab_fields']) {
			foreach($tabs['custom_tab_fields'] as $custom_field_key) {
				$custom_field_value = isset($entry['custom_fields'][$custom_field_key]) ? $entry['custom_fields'][$custom_field_key] : '';
				$custom_field_key = 'TAB_'.strtoupper($custom_field_key);

				if($custom_field_value === '') {
					$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_key, '');
					continue;
				}

				if(isset($tabs['fieldgroup'][$custom_field_key]['render']) && in_array($tabs['fieldgroup'][$custom_field_key]['render'], $tabs['field_render'])) {
					if($tabs['fieldgroup'][$custom_field_key]['render'] === 'markdown') {
						if(!isset($phpwcms['parsedown_class'])) {
							require_once(PHPWCMS_ROOT.'/include/inc_ext/parsedown/Parsedown.php');
							require_once(PHPWCMS_ROOT.'/include/inc_ext/parsedown-extra/ParsedownExtra.php');
							$phpwcms['parsedown_class'] = new ParsedownExtra();
						}
						$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_key, $phpwcms['parsedown_class']->text($custom_field_value));
					} elseif($tabs['fieldgroup'][$custom_field_key]['render'] === 'plain') {
						$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_key, plaintext_htmlencode($custom_field_value));
					} else {
						render_cnt_template($tabs['entries'][$key], $custom_field_key, $custom_field_value);
					}
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
