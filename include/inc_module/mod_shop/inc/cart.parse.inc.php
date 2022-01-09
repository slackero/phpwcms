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

$subtotal['float_net']		= $subtotal['net'];
$subtotal['float_gross']	= $subtotal['gross'];
$subtotal['float_weight']	= $subtotal['weight'];

// calculate discount
$_shopPref['shop_pref_discount_invalid'] = empty($_shopPref['shop_pref_discount']['discount']) && empty($_shopPref['shop_pref_discount']['discount_1']) && empty($_shopPref['shop_pref_discount']['discount_2']);
$_shopPref['shop_pref_discount_percent_invalid'] = empty($_shopPref['shop_pref_discount']['percent']) && empty($_shopPref['shop_pref_discount']['percent_1']) && empty($_shopPref['shop_pref_discount']['percent_2']);

$_shopPref['shop_discount_use'] = array(
    'percent' => 0,
    'freeshipping' => 0,
    'amount' => -1
);

if($_shopPref['shop_pref_discount_invalid'] || $_shopPref['shop_pref_discount_percent_invalid']) {

    $subtotal['float_discount_net']		= 0;
    $subtotal['float_discount_vat']		= 0;
    $subtotal['float_discount_gross']	= 0;

} else {

    $_shopPref['shop_discount_cur'] = array(
        0 => empty($_shopPref['shop_pref_discount']['discount']) ? -1 : (isset($_shopPref['shop_pref_discount']['amount']) && $_shopPref['shop_pref_discount']['amount'] > 0 ? $_shopPref['shop_pref_discount']['amount'] : 0),
        1 => empty($_shopPref['shop_pref_discount']['discount_1']) ? -1 : (isset($_shopPref['shop_pref_discount']['amount_1']) && $_shopPref['shop_pref_discount']['amount_1'] > 0 ? $_shopPref['shop_pref_discount']['amount_1'] : 0),
        2 => empty($_shopPref['shop_pref_discount']['discount_2']) ? -1 : (isset($_shopPref['shop_pref_discount']['amount_2']) && $_shopPref['shop_pref_discount']['amount_2'] > 0 ? $_shopPref['shop_pref_discount']['amount_2'] : 0)
    );

    // sort by highest discount amount
    asort($_shopPref['shop_discount_cur'], SORT_NUMERIC);

    // delete all
    foreach($_shopPref['shop_discount_cur'] as $_dk => $_dv) {
        if($_dv > -1) {
            if($_dv > $_shopPref['shop_discount_use']['amount'] && ($_dv == 0 || $subtotal['float_net'] >= $_dv)) {
                if($_dk === 0) {
                    $_shopPref['shop_discount_use'] = array(
                        'percent' => $_shopPref['shop_pref_discount']['percent'],
                        'freeshipping' => $_shopPref['shop_pref_discount']['freeshipping'],
                        'amount' => $_dv
                    );
                } elseif($_dk === 1) {
                    $_shopPref['shop_discount_use'] = array(
                        'percent' => $_shopPref['shop_pref_discount']['percent_1'],
                        'freeshipping' => $_shopPref['shop_pref_discount']['freeshipping_1'],
                        'amount' => $_dv
                    );
                } else {
                    $_shopPref['shop_discount_use'] = array(
                        'percent' => $_shopPref['shop_pref_discount']['percent_2'],
                        'freeshipping' => $_shopPref['shop_pref_discount']['freeshipping_2'],
                        'amount' => $_dv
                    );
                }
            }
        }
    }

    $subtotal['float_discount_net']		= round($subtotal['float_net'] * $_shopPref['shop_discount_use']['percent'] / 100, 2);
    $subtotal['float_discount_gross']	= round($subtotal['float_gross'] * $_shopPref['shop_discount_use']['percent'] / 100, 2);
    $subtotal['float_discount_vat']		= $subtotal['float_discount_gross'] - $subtotal['float_discount_net'];
    if(!empty($_shopPref['shop_discount_use']['freeshipping'])) {
        $subtotal['shipping_net']	= 0;
        $subtotal['shipping_gross']	= 0;
        $subtotal['shipping_vat']	= 0;
    }
}

if (!empty($_shopPref['shop_pref_discount']['freeshipping_pickup']) && !empty($_SESSION[CART_KEY]['selfpickup'])) {
    $subtotal['shipping_net']	= 0;
    $subtotal['shipping_gross']	= 0;
    $subtotal['shipping_vat']	= 0;
    $subtotal['selfpickup_free'] = 1;
} else {
    $subtotal['selfpickup_free'] = 0;
}

$subtotal['float_shipping_net']		= $subtotal['shipping_net'];
$subtotal['float_shipping_gross']	= $subtotal['shipping_gross'];

// calculate low or surcharge
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

// now sum everything
$subtotal['float_total_net']		= $subtotal['float_net'] + $subtotal['float_shipping_net'] + $subtotal['float_loworder_net'] - $subtotal['float_discount_net'];
$subtotal['float_total_gross']		= $subtotal['float_gross'] + $subtotal['float_shipping_gross'] + $subtotal['float_loworder_gross'] - $subtotal['float_discount_gross'];
$subtotal['float_total_vat']		= $subtotal['float_total_gross'] - $subtotal['float_total_net'];

// Number formatting
$subtotal['net']					= number_format($subtotal['net'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['vat']					= number_format($subtotal['vat'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['gross']					= number_format($subtotal['gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['shipping_net']			= number_format($subtotal['shipping_net'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['shipping_vat']			= number_format($subtotal['shipping_vat'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['shipping_gross']			= number_format($subtotal['shipping_gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['weight']					= number_format($subtotal['weight'], $_tmpl['config']['weight_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['discount_percent'] 		= number_format(round($_shopPref['shop_discount_use']['percent'], 1), $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_discount_net']		= number_format($subtotal['float_discount_net'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_discount_vat']		= number_format($subtotal['float_discount_vat'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_discount_gross']	= number_format($subtotal['float_discount_gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_loworder_net']		= number_format($subtotal['float_loworder_net'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_loworder_vat']		= number_format($subtotal['float_loworder_vat'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_loworder_gross']	= number_format($subtotal['float_loworder_gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_net']				= number_format($subtotal['float_total_net'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_vat']				= number_format($subtotal['float_total_vat'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
$subtotal['total_gross']			= number_format($subtotal['float_total_gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);

// Replace
$order_process = str_replace('{CURRENCY_SYMBOL}', html_specialchars($_shopPref['shop_pref_currency']), $order_process);
$order_process = str_replace('{WEIGHT_UNIT}', html_specialchars($_shopPref['shop_pref_unit_weight']), $order_process);
$order_process = str_replace('{SUBTOTAL_WEIGHT}', $subtotal['weight'], $order_process);
$order_process = str_replace('{SUBTOTAL_NET}', $subtotal['net'], $order_process);
$order_process = str_replace('{SUBTOTAL_VAT}', $subtotal['vat'], $order_process);
$order_process = str_replace('{SUBTOTAL_GROSS}', $subtotal['gross'], $order_process);
$order_process = str_replace('{SHIPPING_NET}', $subtotal['shipping_net'], $order_process);
$order_process = str_replace('{SHIPPING_VAT}', $subtotal['shipping_vat'], $order_process);
$order_process = str_replace('{SHIPPING_GROSS}', $subtotal['shipping_gross'], $order_process);
$order_process = str_replace('{DISCOUNT_NET}', $subtotal['total_discount_net'], $order_process);
$order_process = str_replace('{DISCOUNT_VAT}', $subtotal['total_discount_vat'], $order_process);
$order_process = str_replace('{DISCOUNT_GROSS}', $subtotal['total_discount_gross'], $order_process);
$order_process = str_replace('{LOWORDER_NET}', $subtotal['total_loworder_net'], $order_process);
$order_process = str_replace('{LOWORDER_VAT}', $subtotal['total_loworder_vat'], $order_process);
$order_process = str_replace('{LOWORDER_GROSS}', $subtotal['total_loworder_gross'], $order_process);
$order_process = str_replace('{TOTAL_NET}', $subtotal['total_net'], $order_process);
$order_process = str_replace('{TOTAL_VAT}', $subtotal['total_vat'], $order_process);
$order_process = str_replace('{TOTAL_GROSS}', $subtotal['total_gross'], $order_process);
$order_process = render_cnt_template($order_process, 'LOWORDER', $subtotal['float_loworder_net'] != 0 ? 1 : '');
$order_process = render_cnt_template($order_process, 'DISCOUNT', $subtotal['float_discount_net'] != 0 ? $subtotal['discount_percent'] : '');
$order_process = render_cnt_template($order_process, 'SHIPPING', $subtotal['float_shipping_net'] > 0 ? 1 : '');
if (empty($_SESSION[CART_KEY]['selfpickup'])) {
    $order_process = render_cnt_template($order_process, 'SELFPICKUP', '');
    $order_process = render_cnt_template($order_process, 'SELFPICKUP_FREE', '');
} else {
    $order_process = render_cnt_template($order_process, 'SELFPICKUP', ' ');
    $order_process = render_cnt_template($order_process, 'SELFPICKUP_FREE', $subtotal['selfpickup_free'] ? ' ' : '');
}
