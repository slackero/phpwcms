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
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Shop module content part frontend article rendering

$content['shop'] = @unserialize($crow['acontent_form'], ['allowed_classes' => false]);

if (!is_array($content['shop'])) {
	$content['shop'] = array();
}

$content['shop']['shop_action']		= $content['shop']['shop_action'] ?? 'categories';
$content['shop']['shop_category']	= intval($content['shop']['shop_category'] ?? 0);
$content['shop']['shop_template']	= $content['shop']['shop_template'] ?? '';
$content['shop']['shop_config']		= $content['shop']['shop_config'] ?? '';
$content['shop']['options']			= (isset($content['shop']['options']) && is_array($content['shop']['options'])) ? $content['shop']['options'] : array();

// per content part template override; the first shop content part on the page wins
// because the shop engine loads a single template for all of its output
if (empty($GLOBALS['phpwcms']['shop_cp_template']) && $content['shop']['shop_template'] !== '') {
	$GLOBALS['phpwcms']['shop_cp_template'] = basename($content['shop']['shop_template']);
}

// per content part action-specific options override
if (empty($GLOBALS['phpwcms']['shop_cp_options']) && count($content['shop']['options'])) {
	$GLOBALS['phpwcms']['shop_cp_options'] = $content['shop']['options'];
}

// per content part template CONFIG overrides, applied over the template's own section
if (empty($GLOBALS['phpwcms']['shop_cp_config']) && trim($content['shop']['shop_config']) !== '') {
	$GLOBALS['phpwcms']['shop_cp_config'] = $content['shop']['shop_config'];
}

// The shop frontend engine runs at the final render stage and replaces these
// tags in $content['all'], so the content part only has to emit the matching one.
switch ($content['shop']['shop_action']) {

	case 'category':
		$content['shop']['replacement_tag'] = $content['shop']['shop_category'] ? '{SHOP_CATEGORY:' . $content['shop']['shop_category'] . '}' : '{SHOP_CATEGORIES}';
		break;

	case 'productlist':
		$content['shop']['replacement_tag'] = '{SHOP_PRODUCTLIST}';
		break;

	case 'order':
		$content['shop']['replacement_tag'] = '{SHOP_ORDER_PROCESS}';
		break;

	case 'smallcart':
		$content['shop']['replacement_tag'] = '{CART_SMALL}';
		break;

	case 'categories':
	default:
		$content['shop']['replacement_tag'] = '{SHOP_CATEGORIES}';

}

$CNT_TMP .= $content['shop']['replacement_tag'];

// render content part title/subtitle
$CNT_TMP = render_cnt_template($CNT_TMP, 'CP_TITLE', html_specialchars($crow['acontent_title']));
$CNT_TMP = render_cnt_template($CNT_TMP, 'CP_SUBTITLE', html_specialchars($crow['acontent_subtitle']));
