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

$x = 0;
$cart_items = array();
$total		= array();
$subtotal	= array('net' => 0, 'vat' => 0, 'gross' => 0, 'weight' => 0);


foreach($cart_data as $item_key => $row) {

	$prod_id = $row['shopprod_id'];

	//wr begin changed 29.06.12, optimized OG
	//get the value from textarea for options, prepare data, change the varaibles for rendering

	$row_shopprod_price = $row['shopprod_price']; //initial prize
	$row_shopprod_numbr = $row['shopprod_ordernumber']; //initial odernr

	foreach($_SESSION[CART_KEY]['products'][$prod_id] as $item_key1 => $value_opt1) {

		$opt1_id = $item_key1;

		foreach($_SESSION[CART_KEY]['products'][$prod_id][$opt1_id] as $item_key2 => $value_opt2) {

			$opt2_id = $item_key2;

			//order options 1
			$opt1_txt = "";
			$opt1_price = "";
			$opt1_numbr = "";
			$value_opt1_float = 0;
			$_cart_opt_1['data'] = explode(LF, $row['shopprod_size']);
			foreach($_cart_opt_1['data'] as $key => $value){

				//values - followin rows
				if($_SESSION[CART_KEY]['options1'][$prod_id][$opt1_id][$opt2_id] == $key && $key > 0){

					$_cart_opt_1['value'] = explode('|', trim($value));
					// following is default for the exploded $caption
					// [0] string: description
					// [1] float: price to add
					// [2] string:# to add to prod#

					$value_opt1_float = 0;

					if(isset($_cart_opt_1['value'][1])) {
						$value_opt1_float = preg_replace("/[^-0-9\.\,]/", '',$_cart_opt_1['value'][1]);
						$value_opt1_float = floatval(preg_replace("/\,/", ".", $value_opt1_float));
						$opt1_price = number_format($value_opt1_float, 2, $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
						if($value_opt1_float >= 0) {
							$opt1_price = "+".$opt1_price; //+ (wieder) hinzufügen
						}
					}
					$opt1_txt =  $_cart_opt_1['value'][0]." ".$opt1_price;
					if(isset($_cart_opt_1['value'][2]) ) {
						$opt1_numbr = $_cart_opt_1['value'][2];
					}
				}
			}

			//order options 2
			$opt2_txt = "";
			$opt2_price = "";
			$opt2_numbr = "";
			$value_opt2_float = 0;
			$_cart_opt_2['data'] = explode(LF, $row['shopprod_color']);
			foreach ($_cart_opt_2['data'] as $key => $value){
				//values - followin rows
				if ($_SESSION[CART_KEY]['options2'][$prod_id][$opt1_id][$opt2_id] == $key && $key > 0){

					$_cart_opt_2['value'] = explode('|', trim($value));
					// following is default for the exploded $caption
					// [0] string: description
					// [1] float: price to add
					// [2] string:# to add to prod#

					$value_opt2_float = 0;

					if(isset($_cart_opt_2['value'][1])) {
						$value_opt2_float = preg_replace("/[^-0-9\.\,]/", '', $_cart_opt_2['value'][1]);
						$value_opt2_float = floatval(preg_replace("/\,/", ".", $value_opt2_float));
						$opt2_price = number_format($value_opt2_float, 2, $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
						if($value_opt2_float >= 0) {
							$opt2_price = "+".$opt2_price; //+ (wieder) hinzufügen
						}
					}
					$opt2_txt =  $_cart_opt_2['value'][0]." ".$opt2_price;
					if(isset($_cart_opt_2['value'][2])) {
						$opt2_numbr = $_cart_opt_2['value'][2];
					}
				}
			}

			//add opt prize to normal prize
			$row['shopprod_price'] = $row_shopprod_price + $value_opt1_float + $value_opt2_float;

			//add # of opt to prod#
			$row['shopprod_ordernumber'] = $row_shopprod_numbr.$opt1_numbr.$opt2_numbr;

			$total[$prod_id]['quantity'] = $_SESSION[CART_KEY]['products'][$prod_id][$opt1_id][$opt2_id];

			//wr end changed 29.06.12

			$total[$prod_id]['vat']				= (float) $row['shopprod_vat'];
			$total[$prod_id]['vat_decimals']	= dec_num_count($total[$prod_id]['vat']);
			if($total[$prod_id]['vat_decimals'] < $_tmpl['config']['vat_decimals']) {
				$total[$prod_id]['vat_decimals'] = $_tmpl['config']['vat_decimals'];
			}
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

			$row['vat']		= number_format($total[$prod_id]['vat'],   $total[$prod_id]['vat_decimals'],   $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
			$row['net']		= number_format($total[$prod_id]['net'],   $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
			$row['gross']	= number_format($total[$prod_id]['gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
			$row['weight']	= $row['shopprod_weight'] > 0 ? number_format($row['shopprod_weight'], $_tmpl['config']['weight_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']) : '';

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
			$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_NET_PRICE', $row['net']);
			$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_GROSS_PRICE', $row['gross']);
			$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_WEIGHT', $row['weight']);
			$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_VAT', $row['vat']);
			$cart_items[$x] = render_cnt_template($cart_items[$x], 'ORDER_NUM', html_specialchars($row['shopprod_ordernumber']));
			$cart_items[$x] = render_cnt_template($cart_items[$x], 'MODEL', html_specialchars($row['shopprod_model']));

			//wr start changed 29.06.12
			$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_OPT1', $opt1_txt);
			$cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_OPT2', $opt2_txt);
			//wr end changed 29.06.12

			switch($cart_mode) {
				case 'cart':
					//wr start changed 29.06.12
					$cart_items[$x] = str_replace('{COUNT}', '<input type="text" name="shop_prod_amount['.$prod_id.']['.$opt1_id.']['.$opt2_id.']" value="' . $total[$prod_id]['quantity'] . '" size="3" />', $cart_items[$x]);
					//wr end changed 29.06.12
					break;

				default:
					$cart_items[$x] = str_replace('{COUNT}', $total[$prod_id]['quantity'], $cart_items[$x]);
			}

			$x++;

			//wr start changed 29.06.12
		}
	}
	//wr end changed 29.06.12
}

// set shipping fees
$subtotal['shipping_net']		= 0;
$subtotal['shipping_vat']		= 0;
$subtotal['shipping_gross'] 	= 0;
$subtotal['shipping_calc']		= false;
$subtotal['shipping_calc_type']	= _getConfig( 'shop_pref_shipping_calc', '_shopPref' );

foreach( _getConfig( 'shop_pref_shipping', '_shopPref' ) as $item_key => $row ) {

	// calculate shipping costs based on weight
	if(!$subtotal['shipping_calc_type']) {

		// do nothing as long shipping fee = 0
		if( $row['net'] == 0 ) {
			continue;
		}

		// lower weight and current shipping fee lower then this
		if( $subtotal['weight'] <= $row['weight'] ) {

			$subtotal['shipping_calc'] = true;

		}

		if( $subtotal['shipping_calc'] ) {

			$subtotal['shipping_net']	= $row['net'];
			$subtotal['shipping_gross']	= $subtotal['shipping_net'] * ( 1 + ($row['vat'] / 100) );
			$subtotal['shipping_vat']	= $subtotal['shipping_gross'] - $subtotal['shipping_net'];

			break;
		}

	// calculate shipping costs based on total price
	} else {

		// do nothing as long shipping fee = 0
		if( $row['price_net'] == 0 ) {
			continue;
		}

		// when total net price is lower shipping barrier
		if( $subtotal['net'] <= $row['price'] ) {

			$subtotal['shipping_calc'] = true;

		}

		if( $subtotal['shipping_calc'] ) {

			$subtotal['shipping_net']	= $row['price_net'];
			$subtotal['shipping_gross']	= $subtotal['shipping_net'] * ( 1 + ($row['price_vat'] / 100) );
			$subtotal['shipping_vat']	= $subtotal['shipping_gross'] - $subtotal['shipping_net'];

			break;
		}

	}

}


?>