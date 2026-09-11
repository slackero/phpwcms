<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

$custom_data = array();
if (!empty($crow['acontent_form'])) {
    $decoded = json_decode($crow['acontent_form'], true);
    $custom_data = is_array($decoded) ? $decoded : @unserialize($crow['acontent_form'], ['allowed_classes' => false]);
}
if (!is_array($custom_data)) {
    $custom_data = array();
}

$cpt_module = $crow['acontent_module'] ?? '';
$active_cpt = (!empty($cpt_module) && function_exists('get_custom_contentpart_by_key')) ? get_custom_contentpart_by_key($cpt_module) : null;

$cpt_fields = $active_cpt['fields'] ?? array();
$cpt_mode   = $active_cpt['cpt_mode'] ?? 'repeater';

// Fallback to legacy fieldgroup if needed
if (empty($cpt_fields) && !empty($custom_data['fieldgroup']) && !empty($template_default['settings']['customctp_custom_fields'][$custom_data['fieldgroup']]['fields'])) {
    $cpt_fields = $template_default['settings']['customctp_custom_fields'][$custom_data['fieldgroup']]['fields'];
}

// Load frontend template file
$tmpl_file = $crow['acontent_template'] ?? '';
$template_content = '';

if (!empty($tmpl_file) && file_exists(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $tmpl_file)) {
    $template_content = file_get_contents(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $tmpl_file);
} elseif (!empty($cpt_module) && file_exists(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $cpt_module . '/default.tmpl')) {
    $template_content = file_get_contents(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $cpt_module . '/default.tmpl');
} elseif (!empty($cpt_module) && file_exists(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $cpt_module . '/default.html')) {
    $template_content = file_get_contents(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $cpt_module . '/default.html');
} elseif (!empty($cpt_module) && file_exists(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $cpt_module . '.tmpl')) {
    $template_content = file_get_contents(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $cpt_module . '.tmpl');
} elseif (!empty($cpt_module) && file_exists(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $cpt_module . '.html')) {
    $template_content = file_get_contents(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $cpt_module . '.html');
} elseif (file_exists(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/default.tmpl')) {
    $template_content = file_get_contents(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/default.tmpl');
} elseif (file_exists(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/default.html')) {
    $template_content = file_get_contents(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/default.html');
}

$elements = $custom_data['custom_elements'] ?? array();

if (!empty($template_content) && is_array($elements) && count($elements)) {

    $tmpl_header = get_tmpl_section('CUSTOM_HEADER', $template_content);
    $tmpl_entry  = get_tmpl_section('CUSTOM_ENTRY', $template_content);
    $tmpl_footer = get_tmpl_section('CUSTOM_FOOTER', $template_content);
    $tmpl_spacer = get_tmpl_section('CUSTOM_ENTRY_SPACER', $template_content);

    // If template does not use section markers, treat entire template as entry template
    if (empty($tmpl_header) && empty($tmpl_entry) && empty($tmpl_footer)) {
        $tmpl_entry = $template_content;
    }

    $entries_rendered = array();
    $tmpl_data = array();
    $idx = 1;
    $total_count = count($elements);

    foreach ($elements as $elem) {
        $item_fields = $elem['custom_fields'] ?? array();
        $entry_html = $tmpl_entry;

        // Replace custom fields
        foreach ($cpt_fields as $f_key => $f_def) {
            $f_val = $item_fields[$f_key] ?? ($f_def['default'] ?? '');
            $entry_html = custom_field_replace_tags($entry_html, $f_key, $f_def, $f_val, 'CUSTCTP_');
        }

        // Also replace any dynamic keys not explicitly defined
        foreach ($item_fields as $f_key => $f_val) {
            if (!isset($cpt_fields[$f_key])) {
                $entry_html = custom_field_replace_tags($entry_html, $f_key, ['type' => 'str'], $f_val, 'CUSTCTP_');
            }
        }

        // Loop meta tags
        $entry_html = str_replace(
            array('{ITEM_INDEX}', '{ITEM_NUM}', '{ITEM_TOTAL}'),
            array($idx - 1, $idx, $total_count),
            $entry_html
        );

        // [ITEM_FIRST] = first element of the whole set
        $entry_html = render_cnt_template($entry_html, 'ITEM_FIRST', ($idx === 1 ? 1 : ''));
        // [ITEM_LAST] = last element of the whole set
        $entry_html = render_cnt_template($entry_html, 'ITEM_LAST', ($idx === $total_count ? 1 : ''));
        // [ITEM_FIRST_ELSE] = all but the first element
        $entry_html = render_cnt_template($entry_html, 'ITEM_FIRST_ELSE', ($idx === 1 ? '' : 1));
        // [ITEM_LAST_ELSE] = all but the last element
        $entry_html = render_cnt_template($entry_html, 'ITEM_LAST_ELSE', ($idx === $total_count ? '' : 1));

        // Get the entry data, collect it for [DATA] in header/footer
        $tmpl_data[] = get_tmpl_section('ENTRY_DATA', $entry_html);
        $entry_html = replace_tmpl_section('ENTRY_DATA', $entry_html, '');

        $entries_rendered[] = $entry_html;
        $idx++;
    }

    $joined_entries = implode($tmpl_spacer ?: LF, $entries_rendered);
    $output = $tmpl_header . $joined_entries . $tmpl_footer;
    $output = render_cnt_template($output, 'DATA', implode('', $tmpl_data));

    // Standard Content Part Replacement Tags
    $output = render_cnt_template($output, 'ATTR_CLASS', html($crow['acontent_attr_class'] ?? ''));
    $output = render_cnt_template($output, 'ATTR_ID', html($crow['acontent_attr_id'] ?? ''));
    $output = render_cnt_template($output, 'TITLE', html($crow['acontent_title'] ?? ''));
    $output = render_cnt_template($output, 'SUBTITLE', html($crow['acontent_subtitle'] ?? ''));
    $output = render_cnt_template($output, 'ANCHOR', html($crow['acontent_anchor'] ?? ''));
    $output = render_cnt_template($output, 'ID', (string)($crow['acontent_id'] ?? ''));
    $output = render_cnt_template($output, 'BEFORE', $crow['acontent_before'] ?? '');
    $output = render_cnt_template($output, 'AFTER', $crow['acontent_after'] ?? '');
    $output = render_cnt_template($output, 'HTML', $crow['acontent_html'] ?? '');
    $output = render_cnt_template($output, 'TEXT', $crow['acontent_text'] ?? '');
    $output = render_cnt_template($output, 'CPT_KEY', html($cpt_module));
    $output = render_cnt_template($output, 'MODULE', html($cpt_module));
    $output = render_cnt_template($output, 'CPT_TITLE', html($active_cpt['cpt_title'] ?? ''));
    $output = render_cnt_template($output, 'CPT_DESC', html($active_cpt['cpt_desc'] ?? ''));

    $CNT_TMP .= $output;
}
