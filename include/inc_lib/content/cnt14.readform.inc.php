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

// Content Type WYSIWYG HTML
$content["html"]		= slweg($_POST["chtml"]);
$content["template"]	= clean_slweg($_POST['template']);

$cnt_fieldgroup_fields = null;
$cnt_fieldgroup_field_render = array('html', 'markdown', 'wysiwyg');
if(empty($_POST['cnt_fieldgroup'])) {
	$content['cnt_fieldgroup'] = '';
} else {
	$content['cnt_fieldgroup'] = clean_slweg($_POST['cnt_fieldgroup']);
	if($content['cnt_fieldgroup'] && isset($template_default['settings']['wysiwyg_custom_fields'][ $content['cnt_fieldgroup'] ]['fields'])) {
		$cnt_fieldgroup_fields =& $template_default['settings']['wysiwyg_custom_fields'][ $content['cnt_fieldgroup'] ]['fields'];
	}
}

$content['custom_fields'] = array();

// first read all defined custom field values
if(!empty($cnt_fieldgroup_fields)) {
	foreach($cnt_fieldgroup_fields as $custom_field => $custom_field_definition) {
		$custom_field_value = isset($_POST['customfield'][$custom_field]) ? $_POST['customfield'][$custom_field] : null;
		$_POST['customfield'][$custom_field] = null;
		unset($_POST['customfield'][$custom_field]);

		if(isset($cnt_fieldgroup_fields[$custom_field]['render']) && in_array($cnt_fieldgroup_fields[$custom_field]['render'], $cnt_fieldgroup_field_render)) {
			$content['custom_fields'][$custom_field] = slweg($custom_field_value);
		} elseif($cnt_fieldgroup_fields[$custom_field]['type'] === 'int') {
			$content['custom_fields'][$custom_field] = intval($custom_field_value);
		} elseif($cnt_fieldgroup_fields[$custom_field]['type'] === 'float') {
			$content['custom_fields'][$custom_field] = floatval($custom_field_value);
		} elseif($cnt_fieldgroup_fields[$custom_field]['type'] === 'bool') {
			$content['custom_fields'][$custom_field] = empty($custom_field_value) ? 0 : 1;
		} else {
			$content['custom_fields'][$custom_field] = clean_slweg($custom_field_value);
		}
	}
}

// parse all non-defined custom fields (maybe left over from old definitions)
if(!empty($_POST['customfield']) && count($_POST['customfield'])) {
	foreach($_POST['customfield'] as $custom_field => $custom_field_value) {
		if($custom_field_value === null) {
			continue;
		}
		$content['custom_fields'][$custom_field] = slweg($custom_field_value); // keep the value as is
	}
}

$content['search'] = trim(strip_tags(implode(' ', $content['custom_fields'])));
