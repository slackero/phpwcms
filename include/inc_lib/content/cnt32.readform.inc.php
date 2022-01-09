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


// Content Type Tabs
$content["tabs_template"]	= clean_slweg($_POST['template']);
$content["tabs"]			= array();
$content['search']			= '';
$content['html']			= array();
$content['tabwysiwygoff']	= empty($_POST['tabwysiwygoff']) ? 0 : 1;

$tab_fieldgroup_fields = null;
$tab_fieldgroup_field_render = array('html', 'markdown');
if(empty($_POST['tab_fieldgroup'])) {
	$content['tab_fieldgroup'] = '';
} else {
	$content['tab_fieldgroup'] = clean_slweg($_POST['tab_fieldgroup']);
	if($content['tab_fieldgroup'] && isset($template_default['settings']['tabs_custom_fields'][ $content['tab_fieldgroup'] ]['fields'])) {
		$tab_fieldgroup_fields =& $template_default['settings']['tabs_custom_fields'][ $content['tab_fieldgroup'] ]['fields'];
	}
}

// get all tabs
if(isset($_POST['tabtitle']) && is_array($_POST['tabtitle']) && count($_POST['tabtitle'])) {

	$x = 0;

	foreach($_POST['tabtitle'] as $key => $value) {

		$content["tabs"][$x]['tabtitle'] = clean_slweg($value);
		if($content["tabs"][$x]['tabtitle'] == '') {
			$content["tabs"][$x]['tabtitle'] = $BL['be_tab_name'].' #'.($x+1);
		}
		$content["tabs"][$x]['tabheadline'] = empty($_POST['tabheadline'][$key]) ? '' : clean_slweg($_POST['tabheadline'][$key]);
		$content["tabs"][$x]['tabtext']		= empty($_POST['tabtext'][$key]) ? '' : slweg($_POST['tabtext'][$key]);
		$content["tabs"][$x]['tablink']		= empty($_POST['tablink'][$key]) ? '' : clean_slweg($_POST['tablink'][$key]);

		$content["tabs"][$x]['custom_fields'] = array();

		// first read all defined custom field values
		if(!empty($tab_fieldgroup_fields)) {
			foreach($tab_fieldgroup_fields as $custom_field => $custom_field_definition) {
				$custom_field_value = isset($_POST['customfield'][$key][$custom_field]) ? $_POST['customfield'][$key][$custom_field] : null;

				$_POST['customfield'][$key][$custom_field] = null;
				unset($_POST['customfield'][$key][$custom_field]);

				if(isset($tab_fieldgroup_fields[$custom_field]['render']) && in_array($tab_fieldgroup_fields[$custom_field]['render'], $tab_fieldgroup_field_render)) {

				    $content["tabs"][$x]['custom_fields'][$custom_field] = slweg($custom_field_value);

				} elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'int') {

				    $content["tabs"][$x]['custom_fields'][$custom_field] = intval($custom_field_value);

				} elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'float') {

				    $content["tabs"][$x]['custom_fields'][$custom_field] = floatval($custom_field_value);

				} elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'bool') {

				    $content["tabs"][$x]['custom_fields'][$custom_field] = empty($custom_field_value) ? 0 : 1;

				} elseif($tab_fieldgroup_fields[$custom_field]['type'] === 'file') {

                    $content["tabs"][$x]['custom_fields'][$custom_field] = array('id' => '', 'name' => '', 'description' => '');

                    if(!empty($custom_field_value['id']) && ($custom_field_value['id'] = intval($custom_field_value['id']))) {
                       $content["tabs"][$x]['custom_fields'][$custom_field]['id'] = $custom_field_value['id'];
                    }
                    if(!empty($custom_field_value['name']) && $content["tabs"][$x]['custom_fields'][$custom_field]['id']) {
                        $content["tabs"][$x]['custom_fields'][$custom_field]['name'] = clean_slweg($custom_field_value['name']);
                    }
                    if(!empty($custom_field_value['description']) && $content["tabs"][$x]['custom_fields'][$custom_field]['id']) {
                        $content["tabs"][$x]['custom_fields'][$custom_field]['description'] = clean_slweg($custom_field_value['description']);
                    }

                } else {

				    $content["tabs"][$x]['custom_fields'][$custom_field] = clean_slweg($custom_field_value);

				}
			}
		}

		// parse all non-defined custom fields (maybe left over from old definitions)
		if(!empty($_POST['customfield'][$key]) && count($_POST['customfield'][$key])) {
			foreach($_POST['customfield'][$key] as $custom_field => $custom_field_value) {
				if($custom_field_value === null) {
					continue;
				}
				$content["tabs"][$x]['custom_fields'][$custom_field] = slweg($custom_field_value); // keep the value as is
			}
		}

		$content['search'] .= strip_tags(
			trim(
				$content["tabs"][$x]['tabtitle'] . ' ' . $content["tabs"][$x]['tabheadline'] . ' ' .
				$content["tabs"][$x]['tabtext'] . ' ' . implode(' ', $content["tabs"][$x]['custom_fields'])
			)
		) . ' ';

		$content['html'][] = '<dt>'.html_specialchars($content["tabs"][$x]['tabtitle']).'</dt>';
		$content['html'][] = '<dd>';
		if($content["tabs"][$x]['tabheadline']) {
			$content['html'][] = '<h3>'.html_specialchars($content["tabs"][$x]['tabheadline']).'</h3>';
		}
		if(!$content['tabwysiwygoff'] && strpos($content["tabs"][$x]['tabtext'], '<') === false) {
			$content["tabs"][$x]['tabtext'] = plaintext_htmlencode($content["tabs"][$x]['tabtext']);
			$content['html'][] = ''.$content["tabs"][$x]['tabtext'];
		}
		$content['html'][] = '</dd>';

		$x++;

	}
}

$content['search'] = trim($content['search']);

$content['html'] = count($content['html']) ? '<dl>' . implode(LF, $content['html']) . '</dl>' : '';

$content['tabs']['tabwysiwygoff'] = $content['tabwysiwygoff'];
$content['tabs']['tab_fieldgroup'] = $content['tab_fieldgroup'];
