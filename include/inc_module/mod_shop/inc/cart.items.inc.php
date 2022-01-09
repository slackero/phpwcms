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

 /**
  * Many thanks to WR who has brought in the idea and core development of optional prices
  * and article numbers for article options on 2012-06-29
  */

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

    $row_shopprod_price = (float) $row['shopprod_price']; //initial prize
    $row_shopprod_numbr = $row['shopprod_ordernumber']; //initial odernr

    foreach($_SESSION[CART_KEY]['products'][$prod_id] as $item_key1 => $value_opt1) {

        $opt1_id = $item_key1;

        foreach($_SESSION[CART_KEY]['products'][$prod_id][$opt1_id] as $item_key2 => $value_opt2) {

            $opt2_id = $item_key2;

            //order options 1
            $opt1_txt = '';
            $opt1_numbr = '';
            $value_opt1_float = 0;
            if($row['shopprod_size'] && ($_cart_opt_1 = explode(LF, $row['shopprod_size']))) {
                foreach($_cart_opt_1 as $key => $value){
                    if($key && $_SESSION[CART_KEY]['options1'][$prod_id][$opt1_id][$opt2_id] == $key){
                        $value = get_shop_option_value($value);
                        if ($value['type'] === '=') { // use the option price as full price
                            $row_shopprod_price  = $value[1];
                        } else {
                            $value_opt1_float = $value[1];
                        }
                        $opt1_txt = $value[0] . $value['option'];
                        $opt1_numbr = $value[2];
                    }
                }
            }

            //order options 2
            $opt2_txt = '';
            $opt2_numbr = '';
            $value_opt2_float = 0;
            if($row['shopprod_color'] && ($_cart_opt_2 = explode(LF, $row['shopprod_color']))) {
                foreach ($_cart_opt_2 as $key => $value){
                    if($key && $_SESSION[CART_KEY]['options2'][$prod_id][$opt1_id][$opt2_id] == $key){
                        $value = get_shop_option_value($value);
                        if ($value['type'] === '=') { // use the option price as full price
                            $row_shopprod_price  = $value[1];
                        } else {
                            $value_opt2_float = $value[1];
                        }
                        $opt2_txt = $value[0] . $value['option'];
                        $opt2_numbr = $value[2];
                    }
                }
            }

            //add option's prize to normal prize
            $row['shopprod_price'] = $row_shopprod_price + $value_opt1_float + $value_opt2_float;

            //add option to article order number
            $row['shopprod_ordernumber'] = $row_shopprod_numbr . $opt1_numbr . $opt2_numbr;

            $total[$prod_id]['quantity'] = $_SESSION[CART_KEY]['products'][$prod_id][$opt1_id][$opt2_id];

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
            $cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_UNIT', html($row['shopprod_unit']));

            $cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_OPT1', $opt1_txt);
            $cart_items[$x] = render_cnt_template($cart_items[$x], 'PRODUCT_OPT2', $opt2_txt);

            if($cart_mode === 'cart') {
                $cart_items[$x] = str_replace('{COUNT}', '<input type="text" name="shop_prod_amount['.$prod_id.']['.$opt1_id.']['.$opt2_id.']" value="' . $total[$prod_id]['quantity'] . '" size="3" />', $cart_items[$x]);
            } else {
                $cart_items[$x] = str_replace('{COUNT}', $total[$prod_id]['quantity'], $cart_items[$x]);
            }

            $x++;
        }
    }
}

// set shipping fees
$subtotal['shipping_net']				= 0;
$subtotal['shipping_vat']				= 0;
$subtotal['shipping_gross'] 			= 0;
$subtotal['shipping_calc']				= false;
$subtotal['shipping_calc_type']			= _getConfig( 'shop_pref_shipping_calc', '_shopPref' );
$subtotal['shipping_distance']			= false; // not set yet
$subtotal['shipping_distance_details']	= array(
    'city' => '',
    'postcode' => '',
    'country' => '',
    'country_code' => '',
    'foreign' => false,
    'label' => ''
);

// Catch distance first based on base and delivery address
if($subtotal['shipping_calc_type'] === 2) {

    if(isset($_SESSION[CART_KEY]['distance']) && $_SESSION[CART_KEY]['distance'] === false && !empty($_SESSION[CART_KEY]['delivery_address'])) {

        $subtotal['shop_zone_base']	= _getConfig( 'shop_pref_zone_base', '_shopPref' );

        if($subtotal['shop_zone_base']) {
            // http://maps.googleapis.com/maps/api/directions/json?origin=Dessau&destination=Berlin&sensor=false
            if(PHPWCMS_CHARSET !== 'utf-8') {
                $_base_address = mb_convert_encoding($subtotal['shop_zone_base'], 'utf-8', PHPWCMS_CHARSET);
                $_delivery_address = mb_convert_encoding($_SESSION[CART_KEY]['delivery_address'], 'utf-8', PHPWCMS_CHARSET);
            } else {
                $_base_address = $subtotal['shop_zone_base'];
                $_delivery_address = $_SESSION[CART_KEY]['delivery_address'];
            }

            $_query = sprintf(
                'http://maps.googleapis.com/maps/api/directions/json?origin=%s&destination=%s&sensor=false',
                rawurlencode($_base_address),
                rawurlencode($_delivery_address)
            );
            if($_response = @file_get_contents($_query)) {

                $_response = json_decode($_response);

                if(isset($_response->status) && $_response->status == 'OK' && isset($_response->routes[0]->legs[0]->distance->value)) {
                    // Result should be distance in meters (m)
                    $subtotal['shipping_distance'] = $_response->routes[0]->legs[0]->distance->value;
                    $_SESSION[CART_KEY]['distance'] = $subtotal['shipping_distance'];

                    // Try to get Geocoding informations
                    // http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false
                    // http://maps.googleapis.com/maps/api/geocode/json?latlng=%s,%s&sensor=false
                    $_query = sprintf('http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&language=%s', rawurlencode($_delivery_address), $phpwcms['default_lang']);
                    if($_response = @file_get_contents($_query)) {

                        $_response = json_decode($_response);

                        if(isset($_response->status) && $_response->status == 'OK' && isset($_response->results[0]->address_components)) {
                            foreach($_response->results[0]->address_components as $_component) {
                                if(isset($_component->types[0])) {
                                    if($_component->types[0] === 'locality') {
                                        $_SESSION[CART_KEY]['distance_details']['city'] = PHPWCMS_CHARSET == 'utf-8' ? $_component->long_name : mb_convert_encoding($_component->long_name, PHPWCMS_CHARSET, 'utf-8');
                                    } elseif($_component->types[0] === 'country') {
                                        $_SESSION[CART_KEY]['distance_details']['country'] = PHPWCMS_CHARSET == 'utf-8' ? $_component->long_name : mb_convert_encoding($_component->long_name, PHPWCMS_CHARSET, 'utf-8');
                                        $_SESSION[CART_KEY]['distance_details']['country_code'] = strtolower($_component->short_name);
                                    } elseif($_component->types[0] === 'postal_code') {
                                        $_SESSION[CART_KEY]['distance_details']['postcode'] = PHPWCMS_CHARSET == 'utf-8' ? $_component->long_name : mb_convert_encoding($_component->long_name, PHPWCMS_CHARSET, 'utf-8');
                                    }
                                }
                            }

                            // Reset foreign
                            $_SESSION[CART_KEY]['distance_details']['foreign'] = false;
                            $subtotal['shipping_distance_details'] = array_merge($subtotal['shipping_distance_details'], $_SESSION[CART_KEY]['distance_details']);

                            // test against base country
                            $_query = sprintf('http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false&language=%s', rawurlencode($_base_address), $phpwcms['default_lang']);
                            if($subtotal['shipping_distance_details']['country_code'] && $_response = @file_get_contents($_query)) {
                                $_response = json_decode($_response);
                                if(isset($_response->status) && $_response->status == 'OK' && isset($_response->results[0]->address_components)) {
                                    foreach($_response->results[0]->address_components as $_component) {
                                        // Test agains delivery country code
                                        if(isset($_component->types[0]) && $_component->types[0] === 'country' && strtolower($_component->short_name) !== $subtotal['shipping_distance_details']['country_code']) {
                                            $subtotal['shipping_distance_details']['foreign'] = true;
                                            $_SESSION[CART_KEY]['distance_details']['foreign'] = true;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

    } elseif(isset($_SESSION[CART_KEY]['distance']) && is_intval($_SESSION[CART_KEY]['distance'])) {

        $subtotal['shipping_distance'] = $_SESSION[CART_KEY]['distance'];
        $subtotal['shipping_distance_details'] = array_merge($subtotal['shipping_distance_details'], $_SESSION[CART_KEY]['distance_details']);

    }

}

foreach( _getConfig( 'shop_pref_shipping', '_shopPref' ) as $item_key => $row ) {

    // calculate shipping costs based on weight
    if($subtotal['shipping_calc_type'] === 0) {

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

    // calculate shipping costs based on distance
    } elseif($subtotal['shipping_calc_type'] === 2) {

        // do nothing as long shipping fee = 0
        if( $row['zone_net'] == 0 ) {
            continue;
        }

        $subtotal['shipping_distance_details']['label'] = '';

        if($subtotal['shipping_distance'] !== false) {

            // km given and compare against
            if( $subtotal['shipping_distance'] / 1000 <= $row['zone'] ) {

                $subtotal['shipping_calc'] = true;

            }

            if( $subtotal['shipping_calc'] ) {

                $subtotal['shipping_net']	= $row['zone_net'];
                $subtotal['shipping_gross']	= $subtotal['shipping_net'] * ( 1 + ($row['zone_vat'] / 100) );
                $subtotal['shipping_vat']	= $subtotal['shipping_gross'] - $subtotal['shipping_net'];

                $subtotal['shipping_distance_details']['label'] = $row['zone_label'] ? $row['zone_label'] : $row['zone'];

                break;
            }

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
