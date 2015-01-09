<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$order_process  = str_replace('{CURRENCY_SYMBOL}', html($_shopPref['shop_pref_currency']), $order_process);
$order_process  = str_replace('{WEIGHT_UNIT}', html($_shopPref['shop_pref_unit_weight']), $order_process);

$subtotal['float_net']		= $subtotal['net'];
$subtotal['float_gross']	= $subtotal['gross'];

$subtotal['net']	= number_format($subtotal['net'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['vat']	= number_format($subtotal['vat'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['gross']	= number_format($subtotal['gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);

$order_process  = str_replace('{SUBTOTAL_NET}', $subtotal['net'], $order_process);
$order_process  = str_replace('{SUBTOTAL_VAT}', $subtotal['vat'], $order_process);
$order_process  = str_replace('{SUBTOTAL_GROSS}', $subtotal['gross'], $order_process);

$subtotal['float_shipping_net']		= $subtotal['shipping_net'];
$subtotal['float_shipping_gross']	= $subtotal['shipping_gross'];

$subtotal['shipping_net']	= number_format($subtotal['shipping_net'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['shipping_vat']	= number_format($subtotal['shipping_vat'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['shipping_gross']	= number_format($subtotal['shipping_gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);

$order_process  = str_replace('{SHIPPING_NET}', $subtotal['shipping_net'], $order_process);
$order_process  = str_replace('{SHIPPING_VAT}', $subtotal['shipping_vat'], $order_process);
$order_process  = str_replace('{SHIPPING_GROSS}', $subtotal['shipping_gross'], $order_process);

$subtotal['float_weight']	= $subtotal['weight'];

$subtotal['weight']	= number_format($subtotal['weight'], $_tmpl['config']['weight_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$order_process 		= str_replace('{SUBTOTAL_WEIGHT}', $subtotal['weight'], $order_process);


// calculate discount
if(empty($_shopPref['shop_pref_discount']['discount']) || empty($_shopPref['shop_pref_discount']['percent'])) {

	$subtotal['float_discount_net']		= 0;
	$subtotal['float_discount_vat']		= 0;
	$subtotal['float_discount_gross']	= 0;

} else {

	$subtotal['float_discount_net']		= round($subtotal['float_net'] * $_shopPref['shop_pref_discount']['percent'] / 100, 2);
	$subtotal['float_discount_gross']	= round($subtotal['float_gross'] * $_shopPref['shop_pref_discount']['percent'] / 100, 2);
	$subtotal['float_discount_vat']		= $subtotal['float_discount_gross'] - $subtotal['float_discount_net'];

}
$subtotal['discount_percent'] 		= number_format(round($_shopPref['shop_pref_discount']['percent'],1), $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_discount_net']		= number_format($subtotal['float_discount_net'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_discount_vat']		= number_format($subtotal['float_discount_vat'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_discount_gross']	= number_format($subtotal['float_discount_gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);

//$order_process  = str_replace('{DISCOUNT}', $subtotal['discount_percent'], $order_process);
$order_process  = str_replace('{DISCOUNT_NET}', $subtotal['total_discount_net'], $order_process);
$order_process  = str_replace('{DISCOUNT_VAT}', $subtotal['total_discount_vat'], $order_process);
$order_process  = str_replace('{DISCOUNT_GROSS}', $subtotal['total_discount_gross'], $order_process);


// calculate low oder surcharge
$_shopPref['shop_pref_loworder']['under'] = floatval($_shopPref['shop_pref_loworder']['under']);
if(empty($_shopPref['shop_pref_loworder']['loworder']) || empty($_shopPref['shop_pref_loworder']['charge']) || $subtotal['float_net'] > $_shopPref['shop_pref_loworder']['under']) {

	$subtotal['float_loworder_net']		= 0;
	$subtotal['float_loworder_vat']		= 0;
	$subtotal['float_loworder_gross']	= 0;

} else {

	$subtotal['float_loworder_net']		= $_shopPref['shop_pref_loworder']['charge'];
	$subtotal['float_loworder_gross']	= round( $subtotal['float_loworder_net'] * ( 1 + ($_shopPref['shop_pref_loworder']['vat'] / 100) ), 2 );
	$subtotal['float_loworder_vat']		= $subtotal['float_loworder_gross'] - $subtotal['float_loworder_net'];

}
$subtotal['total_loworder_net']		= number_format($subtotal['float_loworder_net'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_loworder_vat']		= number_format($subtotal['float_loworder_vat'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_loworder_gross']	= number_format($subtotal['float_loworder_gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);

$order_process  = str_replace('{LOWORDER_NET}', $subtotal['total_loworder_net'], $order_process);
$order_process  = str_replace('{LOWORDER_VAT}', $subtotal['total_loworder_vat'], $order_process);
$order_process  = str_replace('{LOWORDER_GROSS}', $subtotal['total_loworder_gross'], $order_process);


// now sum everything
$subtotal['float_total_net']	= $subtotal['float_net'] + $subtotal['float_shipping_net'] + $subtotal['float_loworder_net'] - $subtotal['float_discount_net'];
$subtotal['float_total_gross']	= $subtotal['float_gross'] + $subtotal['float_shipping_gross'] + $subtotal['float_loworder_gross'] - $subtotal['float_discount_gross'];
$subtotal['float_total_vat']	= $subtotal['float_total_gross'] - $subtotal['float_total_net'];

$subtotal['total_net']		= number_format($subtotal['float_total_net'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_vat']		= number_format($subtotal['float_total_vat'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_gross']	= number_format($subtotal['float_total_gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);

$order_process  = str_replace('{TOTAL_NET}', $subtotal['total_net'], $order_process);
$order_process  = str_replace('{TOTAL_VAT}', $subtotal['total_vat'], $order_process);
$order_process  = str_replace('{TOTAL_GROSS}', $subtotal['total_gross'], $order_process);


$order_process = render_cnt_template($order_process, 'LOWORDER', $subtotal['float_loworder_net'] != 0 ? 1 : '');
$order_process = render_cnt_template($order_process, 'DISCOUNT', $subtotal['float_discount_net'] != 0 ? $subtotal['discount_percent'] : '');

// Is Shipping?
$order_process = render_cnt_template($order_process, 'SHIPPING', $subtotal['float_shipping_net'] > 0 ? 1 : '');

?>