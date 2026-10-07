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


// Shop module handle content part POST values

$content['shop'] = array();

$content['shop']['shop_action']		= clean_slweg($_POST['shop_action'] ?? '');
$content['shop']['shop_category']	= abs(intval($_POST['shop_category'] ?? 0));
$content['shop']['shop_template']	= sanitize_template_name(clean_slweg($_POST['shop_template'] ?? ''));
$content['shop']['shop_config']		= slweg($_POST['shop_config'] ?? '');

$content['shop']['options']			= array();

// Action-specific options
// categories & category
$content['shop']['options']['cat_all'] = clean_slweg($_POST['shop_opt_cat_all'] ?? '');
$content['shop']['options']['cat_all_pos'] = clean_slweg($_POST['shop_opt_cat_all_pos'] ?? '');
if (!in_array($content['shop']['options']['cat_all_pos'], array('', 'bottom', 'top', 'none'), true)) {
	$content['shop']['options']['cat_all_pos'] = '';
}
$content['shop']['options']['cat_count_products'] = clean_slweg($_POST['shop_opt_cat_count_products'] ?? '');
if (!in_array($content['shop']['options']['cat_count_products'], array('', '0', '1'), true)) {
	$content['shop']['options']['cat_count_products'] = '';
}
$content['shop']['options']['cat_list_products'] = clean_slweg($_POST['shop_opt_cat_list_products'] ?? '');
if (!in_array($content['shop']['options']['cat_list_products'], array('', '0', '1'), true)) {
	$content['shop']['options']['cat_list_products'] = '';
}

// productlist
$content['shop']['options']['cat_list_sort_by'] = clean_slweg($_POST['shop_opt_cat_list_sort_by'] ?? '');
$allowed_sorts = array(
	'',
	'shopprod_name1 ASC',
	'shopprod_name1 DESC',
	'shopprod_ordernumber ASC',
	'shopprod_ordernumber DESC',
	'shopprod_created DESC',
	'shopprod_created ASC',
	'shopprod_track_view DESC',
);
if (!in_array($content['shop']['options']['cat_list_sort_by'], $allowed_sorts, true)) {
	$content['shop']['options']['cat_list_sort_by'] = '';
}
$content['shop']['options']['image_list_width'] = empty($_POST['shop_opt_image_list_width']) ? '' : abs(intval($_POST['shop_opt_image_list_width']));
$content['shop']['options']['image_list_height'] = empty($_POST['shop_opt_image_list_height']) ? '' : abs(intval($_POST['shop_opt_image_list_height']));
$content['shop']['options']['image_list_crop'] = clean_slweg($_POST['shop_opt_image_list_crop'] ?? '');
if (!in_array($content['shop']['options']['image_list_crop'], array('', '0', '1'), true)) {
	$content['shop']['options']['image_list_crop'] = '';
}
$content['shop']['options']['image_list_lightbox'] = clean_slweg($_POST['shop_opt_image_list_lightbox'] ?? '');
if (!in_array($content['shop']['options']['image_list_lightbox'], array('', '0', '1'), true)) {
	$content['shop']['options']['image_list_lightbox'] = '';
}

// order
$content['shop']['options']['order_number_style'] = clean_slweg($_POST['shop_opt_order_number_style'] ?? '');

// smallcart
$content['shop']['options']['cart_url'] = clean_slweg($_POST['shop_opt_cart_url'] ?? '');

// Filter out empty options so they don't needlessly blow up storage or override defaults
$content['shop']['options'] = array_filter($content['shop']['options'], static function ($val) {
	return $val !== '';
});

if (!in_array($content['shop']['shop_action'], array('categories', 'category', 'productlist', 'order', 'smallcart'), true)) {
	$content['shop']['shop_action'] = 'categories';
}

// the picker only offers .html shop templates
if ($content['shop']['shop_template'] !== '' && !preg_match('/\.html?$/i', $content['shop']['shop_template'])) {
	$content['shop']['shop_template'] = '';
}
