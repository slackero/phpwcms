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

if (!in_array($content['shop']['shop_action'], array('categories', 'category', 'productlist', 'order', 'smallcart'), true)) {
	$content['shop']['shop_action'] = 'categories';
}

// the picker only offers .html shop templates
if ($content['shop']['shop_template'] !== '' && !preg_match('/\.html?$/i', $content['shop']['shop_template'])) {
	$content['shop']['shop_template'] = '';
}
