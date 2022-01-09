<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.org>
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

// Is Shipping?
$order_process = render_cnt_template($order_process, 'SHIPPING_DISTANCE', $subtotal['shipping_calc_type'] === 2 ? ' ' : '');
if(strpos($order_process, '[SHIPPING_DISTANCE_ADDRESS]') !== false) {
	$order_process = render_cnt_template($order_process, 'SHIPPING_DISTANCE_ADDRESS', $subtotal['shipping_distance'] === false ? '' : ' ');

	// render distance address details
	if($subtotal['shipping_distance'] !== false) {

		if(preg_match_all('/\{SHIPPING_DISTANCE:(.+?)\}/', $order_process, $_details)) {

			foreach($_details[1] as $_detail) {

				$_ifdetail = strtolower($_detail);
				$_replace = '';

				if($_ifdetail === 'km') {
					$_replace = number_format($subtotal['shipping_distance'] / 1000, 1, $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
				} elseif($_ifdetail === 'm') {
					$_replace = number_format($subtotal['shipping_distance'], 0, $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
				} elseif($_ifdetail === 'mi') {
					$_replace = number_format($subtotal['shipping_distance'] * 0.000621371192, 1, $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
				} elseif($_ifdetail === 'city') {
					$_replace = html($subtotal['shipping_distance_details']['city']);
				} elseif($_ifdetail === 'postcode') {
					$_replace = html($subtotal['shipping_distance_details']['postcode']);
				} elseif($_ifdetail === 'country') {
					$_replace = html($subtotal['shipping_distance_details']['country']);
				} elseif($_ifdetail === 'label') {
					$_replace = html($subtotal['shipping_distance_details']['label']);
				} else {
					continue;
				}

				$order_process = render_cnt_template($order_process, 'SHIPPING_DISTANCE:'.$_detail, $_replace);

			}

		}

		$order_process = render_cnt_template($order_process, 'SHIPPING_DISTANCE:FOREIGN', empty($subtotal['shipping_distance_details']['foreign']) ? '' : ' ');

	}

} elseif(strpos($order_process, '[SHIPPING') !== false) {
	$order_process = render_cnt_template($order_process, 'SHIPPING', $subtotal['float_shipping_net'] > 0 ? 1 : '');
}
