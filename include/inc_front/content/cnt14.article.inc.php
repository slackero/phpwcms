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



// WYSIWYG

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/wysiwyg.tmpl')) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/wysiwyg.tmpl') );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/wysiwyg/'.$crow["acontent_template"])) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/wysiwyg/'.$crow["acontent_template"]) );

} else {

	$crow["acontent_template"]	= '[TITLE]<h3>{TITLE}</h3>'.LF.'[/TITLE][SUBTITLE]<h4>{SUBTITLE}</h4>'.LF.'[/SUBTITLE][TEXT]{TEXT}[/TEXT]';

}

$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_ID', html($crow['acontent_attr_id']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TITLE', html($crow['acontent_title']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html($crow['acontent_subtitle']));

$crow['custom_fields'] = empty($crow["acontent_form"]) ? null : @unserialize($crow["acontent_form"]);

if(is_array($crow['custom_fields']) && !empty($crow["custom_fields"]['cnt_fields'])) {

    $crow['cnt_fieldgroup'] = empty($crow["custom_fields"]['cnt_fieldgroup']) ? '' : $crow["custom_fields"]['cnt_fieldgroup'];
    $crow['custom_fields'] = $crow["custom_fields"]['cnt_fields'];

    if($crow['cnt_fieldgroup'] === '' || empty($template_default['settings']['wysiwyg_custom_fields'][ $crow['cnt_fieldgroup'] ]['fields'])) {
		$crow['custom_cnt_fields'] = array();
	} else {
		$crow['custom_cnt_fields'] = array_keys($template_default['settings']['wysiwyg_custom_fields'][ $crow['cnt_fieldgroup'] ]['fields']);
		$crow['field_render'] = array('html', 'markdown', 'plain', 'wysiwyg');
		$crow['fieldgroup'] =& $template_default['settings']['wysiwyg_custom_fields'][ $crow['cnt_fieldgroup'] ]['fields'];
	}

    if($crow['custom_cnt_fields'] && isset($crow['fieldgroup'])) {
        foreach($crow['custom_cnt_fields'] as $custom_field_key) {
			$custom_field_value = isset($crow['custom_fields'][$custom_field_key]) ? $crow['custom_fields'][$custom_field_key] : '';
			$custom_field_replacer = 'WYSIWYG_'.strtoupper($custom_field_key);

			if($custom_field_value === '') {
				$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], $custom_field_replacer, '');
				continue;
			}

			if($crow['fieldgroup'][$custom_field_key]['type'] === 'bool') {

				$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], $custom_field_replacer, empty($custom_field_value) ? '' : ' ');

			} elseif($crow['fieldgroup'][$custom_field_key]['type'] === 'option' || $crow['fieldgroup'][$custom_field_key]['type'] === 'select') {

				if(isset($crow['fieldgroup'][$custom_field_key]['values'][$custom_field_value])) {

					// render custom option globally first
					$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], $custom_field_replacer, html($custom_field_value));

					// render option specific replacers
					if(strpos($crow["acontent_template"], $custom_field_replacer.'_') !== false) {
						foreach($crow['fieldgroup'][$custom_field_key]['values'] as $option_key => $option_label) {
							if($custom_field_value === $option_key) {
								$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], $custom_field_replacer.'_'.strtoupper($option_key), html($option_key));
							} else {
								$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], $custom_field_replacer.'_'.strtoupper($option_key), '');
							}
						}
					}
				}

			} elseif($crow['fieldgroup'][$custom_field_key]['type'] === 'int' || $crow['fieldgroup'][$custom_field_key]['type'] === 'float') {

				$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], $custom_field_replacer, $custom_field_value);

			} elseif(isset($crow['fieldgroup'][$custom_field_key]['render']) && in_array($crow['fieldgroup'][$custom_field_key]['render'], $crow['field_render'])) {

				if($crow['fieldgroup'][$custom_field_key]['render'] === 'markdown') {
					init_markdown();
					$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], $custom_field_replacer, $phpwcms['parsedown_class']->text($custom_field_value));
				} elseif($crow['fieldgroup'][$custom_field_key]['render'] === 'plain') {
					$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], $custom_field_replacer, plaintext_htmlencode($custom_field_value));
				} else {
					$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], $custom_field_replacer, $custom_field_value);
				}

			} else {

				$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], $custom_field_replacer, nl2br(html($custom_field_value)));

			}
		}
    }
}

$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TEXT', $crow['acontent_html']);
$crow["acontent_template"] = str_replace('{ID}', $crow['acontent_id'], $crow["acontent_template"]);

$CNT_TMP .= $crow["acontent_template"];
