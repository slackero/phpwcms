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

$content['custom_html']     = isset($_POST['custom_html']) ? slweg($_POST['custom_html']) : '';
$content['custom_template'] = isset($_POST['template']) ? clean_slweg($_POST['template']) : '';
$content['custom_form']     = array(
    'custom_elements' => array(),
    'fieldgroup'      => $content['module'] ?? ''
);

$tab_fieldgroup_fields = null;

// Check if dynamic custom content part definition exists for this module
if (!empty($content['module']) && function_exists('get_custom_contentpart_by_key')) {
    $active_cpt = get_custom_contentpart_by_key($content['module']);
    if ($active_cpt && !empty($active_cpt['fields'])) {
        $tab_fieldgroup_fields = $active_cpt['fields'];
    }
}

// Fallback to template/settings fieldgroup if not found via module
if ($tab_fieldgroup_fields === null) {
    if (empty($_POST['tab_fieldgroup'])) {
        $content['tab_fieldgroup'] = '';
    } else {
        $content['tab_fieldgroup'] = clean_slweg($_POST['tab_fieldgroup']);
        if ($content['tab_fieldgroup'] && isset($template_default['settings']['customctp_custom_fields'][$content['tab_fieldgroup']]['fields'])) {
            $tab_fieldgroup_fields = $template_default['settings']['customctp_custom_fields'][$content['tab_fieldgroup']]['fields'];
        }
    }
    $content['custom_form']['fieldgroup'] = $content['tab_fieldgroup'];
}

// Process submitted custom fields
if (isset($_POST['customfield']) && is_array($_POST['customfield']) && count($_POST['customfield'])) {
    $x = 0;
    foreach ($_POST['customfield'] as $key => $values) {
        $custom_entry = array(
            'sort'          => $x,
            'custom_fields' => array()
        );

        if (!empty($tab_fieldgroup_fields) && is_array($tab_fieldgroup_fields)) {
            foreach ($tab_fieldgroup_fields as $f_key => $f_def) {
                $raw_val = isset($values[$f_key]) ? $values[$f_key] : null;
                $custom_entry['custom_fields'][$f_key] = custom_field_sanitize_value($f_def, $raw_val);
                unset($values[$f_key]);
            }
        }

        // Keep any remaining/custom fields
        if (is_array($values)) {
            foreach ($values as $f_key => $f_val) {
                if ($f_val !== null) {
                    $custom_entry['custom_fields'][$f_key] = slweg((string)$f_val);
                }
            }
        }

        $content['custom_form']['custom_elements'][] = $custom_entry;
        $x++;
    }
}
