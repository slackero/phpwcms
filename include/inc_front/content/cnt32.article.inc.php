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


// tabs
$tabs = array();
$tabs['tabs'] = @unserialize($crow["acontent_form"]);
unset($tabs['tabs']['tabwysiwygoff']);

$tabs['tab_fieldgroup'] = empty($tabs['tabs']['tab_fieldgroup']) ? '' : $tabs['tabs']['tab_fieldgroup'];
unset($tabs['tabs']['tab_fieldgroup']);

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/tabs.tmpl')) {

    $tabs['template']   = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/tabs.tmpl') );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/tabs/'.$crow["acontent_template"])) {

    $tabs['template']   = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/tabs/'.$crow["acontent_template"]) );

} else {

    $tabs['template']   = '';

}

if($tabs['template']) {

    $tabs['template'] = render_cnt_template($tabs['template'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
    $tabs['template'] = render_cnt_template($tabs['template'], 'ATTR_ID', html($crow['acontent_attr_id']));
    $tabs['template'] = render_cnt_template($tabs['template'], 'TITLE', html_specialchars($crow['acontent_title']));
    $tabs['template'] = render_cnt_template($tabs['template'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));

    $tabs['entries'] = array();

    $tabs['tmpl_entry'] = get_tmpl_section('TABS_ENTRY', $tabs['template']);
    $tabs['template'] = get_tmpl_section('TABS', $tabs['template']);

    if($tabs['tab_fieldgroup'] === '' || empty($template_default['settings']['tabs_custom_fields'][ $tabs['tab_fieldgroup'] ]['fields'])) {
        $tabs['custom_tab_fields'] = array();
    } else {
        $tabs['custom_tab_fields'] = array_keys($template_default['settings']['tabs_custom_fields'][ $tabs['tab_fieldgroup'] ]['fields']);
        $tabs['field_render'] = array('html', 'markdown', 'plain');
        $tabs['fieldgroup'] =& $template_default['settings']['tabs_custom_fields'][ $tabs['tab_fieldgroup'] ]['fields'];
    }

    foreach($tabs['tabs'] as $key => $entry) {

        $tabs['entries'][$key] = str_replace('{TABID}', ($key+1), $tabs['tmpl_entry']);
        $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABTITLE', $entry['tabtitle'] === '-' ? '' : html_specialchars($entry['tabtitle']));
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
                $custom_field_replacer = 'TAB_'.strtoupper($custom_field_key);

                if($custom_field_value === '') {
                    $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_replacer, '');
                    continue;
                }

                if($tabs['fieldgroup'][$custom_field_key]['type'] === 'bool') {

                    $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_replacer, empty($custom_field_value) ? '' : ' ');

                } elseif($tabs['fieldgroup'][$custom_field_key]['type'] === 'option' || $tabs['fieldgroup'][$custom_field_key]['type'] === 'select') {

                    if(isset($tabs['fieldgroup'][$custom_field_key]['values'][$custom_field_value])) {

                        // render custom option globally first
                        $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_replacer, html($custom_field_value));

                        // render option specific replacers
                        if(strpos($tabs['entries'][$key], $custom_field_replacer.'_') !== false) {
                            foreach($tabs['fieldgroup'][$custom_field_key]['values'] as $option_key => $option_label) {
                                if($custom_field_value === $option_key) {
                                    $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_replacer.'_'.strtoupper($option_key), html($option_key));
                                } else {
                                    $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_replacer.'_'.strtoupper($option_key), '');
                                }
                            }
                        }
                    }

                } elseif($tabs['fieldgroup'][$custom_field_key]['type'] === 'int' || $tabs['fieldgroup'][$custom_field_key]['type'] === 'float') {

                    $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_replacer, $custom_field_value);

                } elseif($tabs['fieldgroup'][$custom_field_key]['type'] === 'file') {

                    $news['files_result'] = '';

                    if(!empty($custom_field_value['id'])) {

                        $IS_NEWS_CP = true;

                        if (!is_array($value)) {
                            $value = array();
                        } elseif (!isset($value['cnt_object']) || !is_array($value['cnt_object'])) {
                            $value['cnt_object'] = array();
                        }

                        $value['cnt_object']['cnt_files'] = array(
                            'id' => array(0 => $custom_field_value['id']),
                            'caption' => array(0 => $custom_field_value['description']),
                        );
                        $value['files_direct_download'] = empty($tabs['fieldgroup'][$custom_field_key]['direct']) ? 0 : 1;
                        $value['files_template'] = empty($tabs['fieldgroup'][$custom_field_key]['template']) ? '' : $tabs['fieldgroup'][$custom_field_key]['template'];

                        // include content part files renderer
                        include PHPWCMS_ROOT.'/include/inc_front/content/cnt7.article.inc.php';

                        unset($IS_NEWS_CP);

                    }

                    $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_replacer, $news['files_result']);

                } elseif(isset($tabs['fieldgroup'][$custom_field_key]['render']) && in_array($tabs['fieldgroup'][$custom_field_key]['render'], $tabs['field_render'])) {

                    if($tabs['fieldgroup'][$custom_field_key]['render'] === 'markdown') {
                        init_markdown();
                        $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_replacer, $phpwcms['parsedown_class']->text($custom_field_value));
                    } elseif($tabs['fieldgroup'][$custom_field_key]['render'] === 'plain') {
                        $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_replacer, plaintext_htmlencode($custom_field_value));
                    } else {
                        $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_replacer, $custom_field_value);
                    }

                } else {

                    $tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], $custom_field_replacer, nl2br(html($custom_field_value)));

                }
            }
        }

    }

    $tabs['entries_count'] = count($tabs['entries']);
    $tabs['template'] = render_cnt_template($tabs['template'], 'TABS_ENTRIES', $tabs['entries_count'] ? implode('', $tabs['entries']) : '');
    $tabs['template'] = str_replace('{TAB_COUNT}', $tabs['entries_count'], $tabs['template']);

} else {

    $CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
    $CNT_TMP .= LF . $crow["acontent_html"];

}

$CNT_TMP .= str_replace('{ID}', $crow['acontent_id'], $tabs['template']);

unset($tabs);
