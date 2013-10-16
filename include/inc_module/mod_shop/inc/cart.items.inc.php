<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2013, Oliver Georgi
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

$x = 0;
$cart_items = array();
$total		= array();
$subtotal	= array('net' => 0, 'vat' => 0, 'gross' => 0, 'weight' => 0);


foreach($cart_data as $item_key => $row) {
	
	$prod_id = $row['shopprod_id'];
	
	$total[$prod_id]['quantity']	= $_SESSION[CART_KEY]['products'][$prod_id];
	$total[$prod_id]['vat']			= $row['shopprod_vat'];
	if($row['shopprod_netgross'] == 1) {
		// price given is GROSS price, including VAT
		$total[$prod_id]['net']		= $row['shopprod_price'] / (1 + $row['shopprod_vat'] / 100);
		$total[$prod_id]['gross']	= $row['shopprod_price'];
	} else {
		// price given is NET price, excluding VAT
		$total[$prod_id]['net']		= $row['shopprod_price'];
		$total[$prod_id]['gross']	= $row['shopprod_price'] * (1 + $row['shopprod_vat'] / 100);
	}
	
	$subtotal['net']	+= $total[$prod_id]['quantity'] * $total[$prod_id]['net'];
	$subtotal['vat']	+= $total[$prod_id]['quantity'] * ($total[$prod_id]['gross'] - $total[$prod_id]['net']);
	$subtotal['gross']	+= $total[$prod_id]['quantity'] * $total[$prod_id]['gross'];
	$subtotal['weight']	+= $total[$prod_id]['quantity'] * $row['shopprod_weight'];

	$_price['vat']		= number_format($total[$prod_id]['vat'],   $_tmpl['config']['vat_decimals'],   $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
	$_price['net']		= number_format($total[$prod_id]['net'],   $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
	$_price['gross']	= number_format($total[$prod_id]['gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
	$_price['weight']	= $row['shopprod_weight'] > 0 ? number_format($row['shopprod_weight'], $_tmpl['config']['weight_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']) : '';

	switch($cart_mode) {

		case 'cart':	$cart_items[$x]  = $_tmpl['cart_entry'];
						break;

		case 'terms':	$cart_items[$x]  = $_tmpl['term_entry'];
						break;

		case 'mail1':	$cart_items[$x]  = trim( $_tmpl['mail_item'] );
						if(empty($cart_items[$x])) {
							$cart_items[$x]  = 'Qty:   {COUNT}' . LF;
							$cart_items[$x] .= 'Ord#:  {ORDER_NUM}' . LF;
							$cart_items[$x] .= 'Item:  {PRODUCT_TITLE}' . LF;
							$cart_items[$x] .= 'Net:   {PRODUCT_NET_PRICE} {CURRENCY_SYMBOL}' . LF;
							$cart_items[$x] .= 'VAT:   {PRODUCT_VAT} %' . LF;
							$cart_items[$x] .= 'Gross: {PRODUCT_GROSS_PRICE} {CURRENCY_SYMBOL}';
						}
						break;
	}
	
	$cart_items[$x] = str_replace('{PRODUCT_DETAIL_LINK}', rel_url(array('shop_detail' => $prod_id), array('shop_cart'), $_tmpl['config']['shop_url']), $cart_items[$x]);
	$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_TITLE', html_specialchars($row['shopprod_name1']));
	$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_SHORT', $row['shopprod_description0']);
	$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_NET_PRICE', $_price['net']);
	$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_GROSS_PRICE', $_price['gross']);
	$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_WEIGHT', $_price['weight']);
	$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_VAT', $_price['vat']);
	$cart_items[$x] = render_cnt_template($cart_items[$x], 'ORDER_NUM', html_specialchars($row['shopprod_ordernumber']));
	$cart_items[$x] = render_cnt_template($cart_items[$x], 'MODEL', html_specialchars($row['shopprod_model']));
	
	switch($cart_mode) {
		case 'cart':
			$cart_items[$x] = str_replace('{COUNT}', '<input type="text" name="shop_prod_amount['.$prod_id.']" value="' . $total[$prod_id]['quantity'] . '" size="3" />', $cart_items[$x]);
			break;
		default:
			$cart_items[$x] = str_replace('{COUNT}', $total[$prod_id]['quantity'], $cart_items[$x]);
	}

	
	$x++;

}

// set shipping fees
$subtotal['shipping_net']	= 0;
$subtotal['shipping_vat']	= 0;
$subtotal['shipping_gross'] = 0;
$subtotal['shipping_calc']	= false;

foreach( _getConfig( 'shop_pref_shipping', '_shopPref' ) as $item_key => $row ) {
	
	// do nothing as long shipping fee = 0
	if( $row['net'] == 0 ) {
		continue;
	}
	
	// lower weight and current shipping fee lower then this
	if( $subtotal['weight'] <= $row['weight'] ) { /* && $subtotal['shipping_net'] <= $row['net'] ) {

		$subtotal['shipping_calc'] = true;

	} elseif( $subtotal['weight'] > $row['weight'] && $subtotal['shipping_net'] < $row['net'] ) { */
	
		$subtotal['shipping_calc'] = true;
		
	}
	
	if( $subtotal['shipping_calc'] ) {
	
		$subtotal['shipping_net']	= $row['net'];
		$subtotal['shipping_gross']	= $subtotal['shipping_net'] * ( 1 + ($row['vat'] / 100) );
		$subtotal['shipping_vat']	= $subtotal['shipping_gross'] - $subtotal['shipping_net'];
		
		break;
	}

}


?>