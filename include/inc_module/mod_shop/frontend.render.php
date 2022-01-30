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

// Module/Plug-in Shop & Products

$_shop_load_cat         = strpos($content['all'], '{SHOP_CATEGOR');
$_shop_load_list        = strpos($content['all'], '{SHOP_PRODUCTLIST}');
$_shop_load_cart_small  = strpos($content['all'], '{CART_SMALL}');
$_shop_load_order       = strpos($content['all'], '{SHOP_ORDER_PROCESS}');
$_shop_parsed           = false;

// set preferences
$_shopPref              = array();

if(_getConfig( 'shop_pref_felang' )) {
    define('SHOP_FELANG_SUPPORT', true);
    define('SHOP_FELANG_SQL', " AND (shopprod_lang='' OR shopprod_lang="._dbEscape($phpwcms['default_lang']).')');
    define('CART_KEY', 'shopping_cart_'.$phpwcms['default_lang']);
} else {
    define('SHOP_FELANG_SUPPORT', false);
    define('SHOP_FELANG_SQL', '');
    define('CART_KEY', 'shopping_cart');
}

// set CART session value
if(!isset($_SESSION[CART_KEY])) {
    $_SESSION[CART_KEY] = array();
}
// reset cart session error var to allow cart listing
if(isset($_getVar['shop_cart']) && $_getVar['shop_cart'] == 'show') {
    unset($_SESSION[CART_KEY]['error'], $_getVar['cart'], $_GET['cart']);
}

if( $_shop_load_cat !== false || $_shop_load_list !== false || $_shop_load_order !== false || $_shop_load_cart_small !== false) {

    $_shop_parsed = true;

    // load template
    $_tmpl = array(
        'config' => array(),
        'source' => '',
        'lang' => $phpwcms['default_lang']
    );

    // Check against language specific shop template
    if(is_file($phpwcms['modules']['shop']['path'].'template/'.$phpwcms['default_lang'].'.html')) {
        $_tmpl['source'] = @file_get_contents($phpwcms['modules']['shop']['path'].'template/'.$phpwcms['default_lang'].'.html');
    } else {
        $_tmpl['source'] = @file_get_contents($phpwcms['modules']['shop']['path'].'template/default.html');
    }

    if($_tmpl['source']) {

        if(PHPWCMS_CHARSET !== 'utf-8' && phpwcms_seems_utf8($_tmpl['source'])) {
            $_tmpl['source'] = mb_convert_encoding($_tmpl['source'], PHPWCMS_CHARSET, 'utf-8');
        }

        $_tmpl['config'] = parse_ini_str(get_tmpl_section('CONFIG', $_tmpl['source']), false);
        $_tmpl['config']['cat_list_products'] = empty($_tmpl['config']['cat_list_products']) ? false : phpwcms_boolval($_tmpl['config']['cat_list_products']);
        $_tmpl['config']['cat_count_products'] = empty($_tmpl['config']['cat_count_products']) ? false : phpwcms_boolval($_tmpl['config']['cat_count_products']);
        $_tmpl['config']['image_list_lightbox'] = empty($_tmpl['config']['image_list_lightbox']) ? false : phpwcms_boolval($_tmpl['config']['image_list_lightbox']);
        $_tmpl['config']['image_detail_lightbox'] = empty($_tmpl['config']['image_detail_lightbox']) ? false : phpwcms_boolval($_tmpl['config']['image_detail_lightbox']);
        $_tmpl['config']['image_detail_crop'] = empty($_tmpl['config']['image_detail_crop']) ? false : phpwcms_boolval($_tmpl['config']['image_detail_crop']);
        $_tmpl['config']['image_list_crop'] = empty($_tmpl['config']['image_list_crop']) ? false : phpwcms_boolval($_tmpl['config']['image_list_crop']);

        // Classes and other default values
        $_tmpl['config'] = array_merge(array(
            'class_form_product_cart_option' => 'product-cart-option',
            'class_shop_amount' => 'shop-amount',
            'class_cart_add_button' => 'cart-add-button',
            'class_product_option_1' => 'product-option-1',
            'class_product_option_2' => 'product-option-2',
            'class_form_cart' => 'cart-form',
            'class_request_button' => 'request-button',
            'class_request_form' => 'form-request-product'
        ), $_tmpl['config']);

        if(empty($_tmpl['config']['class_prefix_shop_mode'])) {
            $_tmpl['config']['class_prefix_shop_mode'] = 'shopmode-';
        }

        // handle custom fields
        $_tmpl['config']['shop_field'] = array();
        $custom_field_number = 1;
        while( !empty( $_tmpl['config']['shop_field_' . $custom_field_number] ) ) {

            $custom_field_type = explode('_', trim($_tmpl['config']['shop_field_' . $custom_field_number]) );
            if($custom_field_type[0] === 'STRING' || $custom_field_type[0] === 'TEXTAREA' || $custom_field_type[0] === 'CHECK') {
                $_tmpl['config']['shop_field'][ $custom_field_number ]['type'] = $custom_field_type[0];
                if(isset($custom_field_type[1]) && $custom_field_type[1] == 'REQ') {
                    $_tmpl['config']['shop_field'][ $custom_field_number ]['required'] = true;
                    if(empty($custom_field_type[2])) {
                        $_tmpl['config']['shop_field'][ $custom_field_number ]['label'] = 'Custom '.$custom_field_number;
                    } else {
                        $_tmpl['config']['shop_field'][ $custom_field_number ]['label'] = trim($custom_field_type[2]);
                    }
                } elseif(empty($custom_field_type[1])) {
                    $_tmpl['config']['shop_field'][ $custom_field_number ]['required'] = false;
                    $_tmpl['config']['shop_field'][ $custom_field_number ]['label'] = 'Custom '.$custom_field_number;
                } else {
                    $_tmpl['config']['shop_field'][ $custom_field_number ]['required'] = false;
                    $_tmpl['config']['shop_field'][ $custom_field_number ]['label'] = trim($custom_field_type[1]);
                }
                if($custom_field_type[0] === 'CHECK') {
                    if($_tmpl['config']['shop_field'][ $custom_field_number ]['required']) {
                        $_tmpl['config']['shop_field'][ $custom_field_number ]['value'] = empty($custom_field_type[3]) ? 1 : trim($custom_field_type[3]);
                    } else {
                        $_tmpl['config']['shop_field'][ $custom_field_number ]['value'] = empty($custom_field_type[2]) ? 1 : trim($custom_field_type[2]);
                    }
                }
            }
            $custom_field_number++;
        }

        if($_shop_load_list) {
            $_tmpl['list_header']   = get_tmpl_section('LIST_HEADER',   $_tmpl['source']);
            $_tmpl['list_entry']    = get_tmpl_section('LIST_ENTRY',    $_tmpl['source']);
            $_tmpl['list_space']    = get_tmpl_section('LIST_SPACE',    $_tmpl['source']);
            $_tmpl['list_none']     = get_tmpl_section('LIST_NONE',     $_tmpl['source']);
            $_tmpl['list_footer']   = get_tmpl_section('LIST_FOOTER',   $_tmpl['source']);
            $_tmpl['detail']        = get_tmpl_section('DETAIL',        $_tmpl['source']);
            $_tmpl['image_space']   = get_tmpl_section('IMAGE_SPACE',   $_tmpl['source']);
        }

        if($_shop_load_cart_small) {
            $_tmpl['cart_small']    = get_tmpl_section('CART_SMALL',    $_tmpl['source']);
        }

        if($_shop_load_order) {
            $_tmpl['cart_header']   = get_tmpl_section('CART_HEADER',           $_tmpl['source']);
            $_tmpl['cart_entry']    = get_tmpl_section('CART_ENTRY',            $_tmpl['source']);
            $_tmpl['cart_space']    = get_tmpl_section('CART_SPACE',            $_tmpl['source']);
            $_tmpl['cart_footer']   = get_tmpl_section('CART_FOOTER',           $_tmpl['source']);
            $_tmpl['cart_none']     = get_tmpl_section('CART_NONE',             $_tmpl['source']);
            $_tmpl['inv_address']   = get_tmpl_section('ORDER_INV_ADDRESS',     $_tmpl['source']);
            $_tmpl['order_terms']   = get_tmpl_section('ORDER_TERMS',           $_tmpl['source']);
            $_tmpl['term_entry']    = get_tmpl_section('ORDER_TERMS_ITEM',      $_tmpl['source']);
            $_tmpl['term_space']    = get_tmpl_section('ORDER_TERMS_ITEMSPACE', $_tmpl['source']);
            $_tmpl['mail_customer'] = get_tmpl_section('MAIL_CUSTOMER',         $_tmpl['source']);
            $_tmpl['mail_neworder'] = get_tmpl_section('MAIL_NEWORDER',         $_tmpl['source']);
            $_tmpl['order_success'] = get_tmpl_section('ORDER_DONE',            $_tmpl['source']);
            $_tmpl['order_failed']  = get_tmpl_section('ORDER_NOT_DONE',        $_tmpl['source']);
            $_tmpl['mail_item']     = get_tmpl_section('MAIL_ITEM',             $_tmpl['source']);
        }
    }

    // merge config settings like translations and so on
    $_tmpl['config'] = array_merge(array(
            'cat_all'                   => '@@All products@@',
            'cat_all_pos'               => 'bottom',
            'cat_list_products'         => false,
            'cat_subcat_spacer'         => ' / ',
            'cat_count_products'        => true,
            'cat_count_products_prefix' => ' (',
            'cat_count_products_suffix' => ')',
            'cat_class_menu'            => 'shop-categories',
            'cat_class_item'            => 'shop-category ',
            'cat_class_item_active'     => 'active',
            'cat_class_submenu'         => 'shop-sub-categories',
            'cat_class_subitem'         => 'shop-sub-category ',
            'cat_class_subitem_active'  => 'active',
            'cat_class_item_link'       => 'shop-cat-link',
            'cat_class_subitem_link'    => 'shop-subcat-link',
            'price_decimals'            => 2,
            'vat_decimals'              => 1,
            'weight_decimals'           => 1,
            'dec_point'                 => '.',
            'thousands_sep'             => ',',
            'price_option_null'         => false,
            'price_option_prefix'       => ', ',
            'price_option_suffix'       => ' {$}',
            'price_option_hide'         => false,
            'image_list_width'          => 200,
            'image_list_height'         => 200,
            'image_detail_width'        => 200,
            'image_detail_height'       => 200,
            'image_zoom_width'          => 750,
            'image_zoom_height'         => 500,
            'image_list_lightbox'       => false,
            'image_detail_lightbox'     => true,
            'image_detail_crop'         => false,
            'image_list_crop'           => false,
            'mail_customer_subject'     => "[#{ORDER}] Your order at MyShop",
            'mail_neworder_subject'     => "[#{ORDER}] New order",
            'label_payby_prepay'        => "@@Cash with order@@",
            'label_payby_pod'           => "@@Cash on delivery@@",
            'label_payby_onbill'        => "@@On account@@",
            'label_payby_cash'          => "@@Cash@@",
            'label_selfpickup'          => "@@Self pickup@@",
            'label_selfpickup_freeshipping' => "@@Self pickup, free shipping@@",
            'order_number_style'        => 'RANDOM',
            'cat_list_sort_by'          => 'shopprod_name1 ASC',
            'shop_css'                  => '',
            'shop_wrap'                 => '',
            'image_detail_more_width'   => 50,
            'image_detail_more_height'  => 50,
            'image_detail_more_crop'    => false,
            'image_detail_more_start'   => 1,
            'image_detail_more_lightbox'=> false,
            'image_class'               => 'img-fluid',
            'files_direct_download'     => false,
            'files_template'            => '', // default
            'on_request_trigger'        => -999,
            'pagetitle_productname'     => '%s',
            'pagetitle_model'           => ', Model: %s',
            'pagetitle_add'             => '%S- %s',
            'pagetitle_ordernumber'     => '%S(Order No. %s)',
            'pagetitle'                 => '%1$s%2$s%3$s%4$s',
            'product_option_1_prefix' => '<span class="option-1">',
            'product_option_1_suffix' => '</span>',
            'product_option_1_required' => 'required="required"',
            'product_option_2_prefix' => '<span class="option-2">',
            'product_option_2_suffix' => '</span>',
            'product_option_2_required' => 'required="required"',
            'amount_input_prefix' => '<span class="input-amount">',
            'amount_input_suffix' => '</span>',
            'amount_input_position' => 'after',
            'default_request_url' => ''
        ),
        $_tmpl['config']
    );

    foreach( array(
            'shop_pref_currency',
            'shop_pref_unit_weight',
            'shop_pref_vat',
            'shop_pref_email_to',
            'shop_pref_email_from',
            'shop_pref_email_paypal',
            'shop_pref_shipping',
            'shop_pref_shipping_calc',
            'shop_pref_shipping_selfpickup',
            'shop_pref_payment',
            'shop_pref_discount',
            'shop_pref_loworder'
        ) as $value ) {

        _getConfig( $value, '_shopPref' );
    }

    if(!isset($_tmpl['config']['shop_url'])) {
        $_tmpl['config']['shop_url'] = _getConfig( 'shop_pref_id_shop', '_shopPref' );
    }
    if(!isset($_tmpl['config']['cart_url'])) {
        $_tmpl['config']['cart_url'] = _getConfig( 'shop_pref_id_cart', '_shopPref' );
    }

    if(!is_intval($_tmpl['config']['shop_url']) && is_string($_tmpl['config']['shop_url'])) {
        $_tmpl['config']['shop_url'] = trim($_tmpl['config']['shop_url']);
    } elseif(is_intval($_tmpl['config']['shop_url']) && intval($_tmpl['config']['shop_url'])) {
        $_tmpl['config']['shop_url'] = 'aid='.intval($_tmpl['config']['shop_url']);
    } else {
        $_tmpl['config']['shop_url'] = $aktion[1] ? 'aid='.$aktion[1] : 'id='.$aktion[0];
    }

    if(!is_intval($_tmpl['config']['cart_url']) && is_string($_tmpl['config']['cart_url'])) {
        $_tmpl['config']['cart_url'] = trim($_tmpl['config']['cart_url']);
    } elseif(is_intval($_tmpl['config']['cart_url']) && intval($_tmpl['config']['cart_url'])) {
        $_tmpl['config']['cart_url'] = 'aid='.intval($_tmpl['config']['cart_url']);
    } else {
        $_tmpl['config']['cart_url'] = $aktion[1] ? 'aid='.$aktion[1] : 'id='.$aktion[0];
    }

    if($_tmpl['config']['shop_wrap']) {
        $_tmpl['config']['shop_wrap'] = explode('|', $_tmpl['config']['shop_wrap']);
        $_tmpl['config']['shop_wrap'] = array(
            'prefix' => trim($_tmpl['config']['shop_wrap'][0]) . LF,
            'suffix' => empty($_tmpl['config']['shop_wrap'][1]) ? '' : LF . trim($_tmpl['config']['shop_wrap'][1])
        );
    } else {
        $_tmpl['config']['shop_wrap'] = array('prefix'=>'', 'suffix'=>'');
    }

    $_tmpl['config']['price_decimals'] = (int) $_tmpl['config']['price_decimals'];
    $_tmpl['config']['vat_decimals'] = (int) $_tmpl['config']['vat_decimals'];
    $_tmpl['config']['weight_decimals'] = (int) $_tmpl['config']['weight_decimals'];

    if($_tmpl['config']['shop_css']) {
        renderHeadCSS($_tmpl['config']['shop_css']);
    }

    // OK get cart post data
    if( isset($_POST['shop_action']) && $_POST['shop_action'] == 'add') {

        $shop_prod_id       = abs(intval($_POST['shop_prod_id']));
        $shop_prod_amount   = abs(intval($_POST['shop_prod_amount']));
        $shop_prod_cartadd  = false;

        if(!empty($shop_prod_id) && !empty($shop_prod_amount)) {

            // wr begin changed 29.06.12
            // check for selections in $_POST
            // the session var is now prod id|opt1 id|opt2 id
            // addings with no options result in: prod id|0|0
            $opt_1 = isset($_POST['prod_opt1']) ? intval($_POST['prod_opt1']) : 0;
            $opt_2 = isset($_POST['prod_opt2']) ? intval($_POST['prod_opt2']) : 0;

            // Test against product options
            if(!isset($_POST['prod_opt1']) && !isset($_POST['prod_opt2'])) {

                $shop_prod_cartadd = true;

            } elseif(isset($_POST['prod_opt1']) && isset($_POST['prod_opt2']) && $opt_1 && $opt_2) {

                $shop_prod_cartadd = true;

            } elseif(isset($_POST['prod_opt1']) && !isset($_POST['prod_opt2']) && $opt_1) {

                $shop_prod_cartadd = true;

            } elseif(isset($_POST['prod_opt2']) && !isset($_POST['prod_opt1']) && $opt_2) {

                $shop_prod_cartadd = true;

            } else {

                $data = _dbGet('phpwcms_shop_products', 'shopprod_size,shopprod_color', 'shopprod_status=1 AND shopprod_id='.$shop_prod_id);

                if(isset($data[0]['shopprod_size'])) {
                    $data[0]['shopprod_size']   = trim($data[0]['shopprod_size']);
                    $data[0]['shopprod_color']  = trim($data[0]['shopprod_color']);

                    if($data[0]['shopprod_size'] === '' && $data[0]['shopprod_color'] === '') {
                        $shop_prod_cartadd = true;
                    }
                }
            }

            if($shop_prod_cartadd) {

                // add product to shopping
                if(isset($_SESSION[CART_KEY]['products'][$shop_prod_id][$opt_1][$opt_2])) {
                    $_SESSION[CART_KEY]['products'][$shop_prod_id][$opt_1][$opt_2] += $shop_prod_amount;
                } else {
                    $_SESSION[CART_KEY]['products'][$shop_prod_id][$opt_1][$opt_2] = $shop_prod_amount;
                }
                $_SESSION[CART_KEY]['options1'][$shop_prod_id][$opt_1][$opt_2] = $opt_1;
                $_SESSION[CART_KEY]['options2'][$shop_prod_id][$opt_1][$opt_2] = $opt_2;

                //this sessionvar holds the products for the small cart
                if(isset($_SESSION[CART_KEY]['total'][$shop_prod_id.$opt_1.$opt_2])) {
                    $_SESSION[CART_KEY]['total'][$shop_prod_id.$opt_1.$opt_2] += $shop_prod_amount;
                } else {
                    $_SESSION[CART_KEY]['total'][$shop_prod_id.$opt_1.$opt_2]  = $shop_prod_amount;
                }

                $_SESSION[CART_KEY]['amount'][$shop_prod_id] = $_SESSION[CART_KEY]['products'][$shop_prod_id][$opt_1][$opt_2];
            }

        }

    } elseif( isset($_POST['shop_prod_amount']) && is_array($_POST['shop_prod_amount']) ) {

        // wr begin changed 29.06.12
        // loop through options to get the amount
        foreach($_POST['shop_prod_amount'] as $prod_id => $value_opt1) {
            foreach($value_opt1 as $opt_1 => $value_opt2) {
                foreach($value_opt2 as $opt_2 => $prod_qty) {
                    $prod_id    = intval($prod_id);
                    $prod_qty   = isset($_POST['shop_cart_delete']) ? 0 : abs(intval($prod_qty));
                    $opt_1      = intval($opt_1);
                    $opt_2      = intval($opt_2);
                    if($prod_qty && isset($_SESSION[CART_KEY]['products'][$prod_id][$opt_1][$opt_2])) {
                        $_SESSION[CART_KEY]['products'][$prod_id][$opt_1][$opt_2] = $prod_qty;
                        $_SESSION[CART_KEY]['total'][$prod_id.$opt_1.$opt_2] = $prod_qty;
                    } else {
                        unset(
                            $_SESSION[CART_KEY]['products'][$prod_id][$opt_1][$opt_2],
                            $_SESSION[CART_KEY]['total'][$prod_id.$opt_1.$opt_2]
                        );
                    }
                }
            }
        }
        //wr end changed 29.06.12

    } elseif( isset($_POST['shop_order_step1']) ) {

        // reset shipping distance related values
        $_SESSION[CART_KEY]['delivery_address'] = '';
        $_SESSION[CART_KEY]['distance'] = false;

        if (empty($_shopPref['shop_pref_shipping_selfpickup'])) {
            $_SESSION[CART_KEY]['selfpickup'] = false;
        } else {
            $_SESSION[CART_KEY]['selfpickup'] = empty($_POST['shopping_selfpickup']) ? false : true;
        }

        // handle invoice address -> checkout

        $_SESSION[CART_KEY]['step1'] = array(
            'INV_SALUTATION'    => isset($_POST['shop_inv_salutation']) ? clean_slweg($_POST['shop_inv_salutation']) : '',
            'INV_TITLE'         => isset($_POST['shop_inv_title']) ? clean_slweg($_POST['shop_inv_title']) : '',
            'INV_COMPANY'       => isset($_POST['shop_inv_company']) ? clean_slweg($_POST['shop_inv_company']) : '',
            'INV_FIRSTNAME'     => isset($_POST['shop_inv_firstname']) ? clean_slweg($_POST['shop_inv_firstname']) : '',
            'INV_NAME'          => isset($_POST['shop_inv_name']) ? clean_slweg($_POST['shop_inv_name']) : '',
            'INV_ADDRESS'       => isset($_POST['shop_inv_address']) ? clean_slweg($_POST['shop_inv_address']) : '',
            'INV_ADDRESS2'      => isset($_POST['shop_inv_address2']) ? clean_slweg($_POST['shop_inv_address2']) : '',
            'INV_ZIP'           => isset($_POST['shop_inv_zip']) ? clean_slweg($_POST['shop_inv_zip']) : '',
            'INV_CITY'          => isset($_POST['shop_inv_city']) ? clean_slweg($_POST['shop_inv_city']) : '',
            'INV_REGION'        => isset($_POST['shop_inv_region']) ? clean_slweg($_POST['shop_inv_region']) : '',
            'INV_COUNTRY'       => isset($_POST['shop_inv_country']) ? clean_slweg($_POST['shop_inv_country']) : '',
            'EMAIL'             => isset($_POST['shop_email']) ? clean_slweg($_POST['shop_email']) : '',
            'PHONE'             => isset($_POST['shop_phone']) ? clean_slweg($_POST['shop_phone']) : ''
        );

        // retrieve all custom field POST data
        foreach($_tmpl['config']['shop_field'] as $key => $row) {

            $_SESSION[CART_KEY]['step1']['shop_field_'.$key] = empty($_POST['shop_field_'.$key]) ? '' : clean_slweg($_POST['shop_field_'.$key]);
            if($row['required'] && $_SESSION[CART_KEY]['step1']['shop_field_'.$key] === '') {
                $ERROR['inv_address']['shop_field_'.$key] = $row['required'] . ' must be filled';
            }
        }

        $payment_options = get_payment_options();
        if(!empty($_POST['shopping_payment']) && isset($payment_options[$_POST['shopping_payment']])) {
            $_SESSION[CART_KEY]['payby'] = $_POST['shopping_payment'];
        } else {
            $ERROR['inv_address']['payment'] = true;
        }

        if(empty($_SESSION[CART_KEY]['step1']['INV_FIRSTNAME'])) {
            $ERROR['inv_address']['INV_FIRSTNAME'] = '@@First name must be filled@@';
        }
        if(empty($_SESSION[CART_KEY]['step1']['INV_NAME'])) {
            $ERROR['inv_address']['INV_NAME'] = '@@Name must be filled@@';
        }
        if(empty($_SESSION[CART_KEY]['step1']['INV_ADDRESS'])) {
            $ERROR['inv_address']['INV_ADDRESS'] = '@@Address must be filled@@';
        }
        if(empty($_SESSION[CART_KEY]['step1']['INV_ZIP'])) {
            $ERROR['inv_address']['INV_ZIP'] = '@@ZIP must be filled@@';
        }
        if(empty($_SESSION[CART_KEY]['step1']['INV_CITY'])) {
            $ERROR['inv_address']['INV_CITY'] = '@@City must be filled@@';
        }
        if(empty($_SESSION[CART_KEY]['step1']['EMAIL']) || !is_valid_email($_SESSION[CART_KEY]['step1']['EMAIL'])) {
            $ERROR['inv_address']['EMAIL'] = '@@Email must be filled or is invalid@@';
        }
        if(empty($_SESSION[CART_KEY]['step1']['PHONE'])) {
            $ERROR['inv_address']['PHONE'] = '@@Phone must be filled@@';
        }
        if(isset($ERROR['inv_address']) && count($ERROR['inv_address'])) {
            $_SESSION[CART_KEY]['error']['step1'] = true;
        } elseif(isset($_SESSION[CART_KEY]['error']['step1'])) {
            unset($_SESSION[CART_KEY]['error']['step1']);
        }

        // set address for zone calculation (in case needed)
        if(!isset($_SESSION[CART_KEY]['error']['step1'])) {

            $_SESSION[CART_KEY]['delivery_address']  = $_SESSION[CART_KEY]['step1']['INV_CITY'].', ';
            $_SESSION[CART_KEY]['delivery_address'] .= $_SESSION[CART_KEY]['step1']['INV_ZIP'];
            if($_SESSION[CART_KEY]['step1']['INV_COUNTRY']) {
                $_SESSION[CART_KEY]['delivery_address'] .= ', ' . $_SESSION[CART_KEY]['step1']['INV_COUNTRY'];
            }
            $_SESSION[CART_KEY]['distance_details']  = array(
                'city' => $_SESSION[CART_KEY]['step1']['INV_CITY'],
                'postcode' => $_SESSION[CART_KEY]['step1']['INV_ZIP'],
                'country' => $_SESSION[CART_KEY]['step1']['INV_COUNTRY'],
                'country_code' => '',
                'foreign' => false
            );

        }

    } elseif( isset($_POST['shop_order_submit']) ) {

        if(empty($_POST['shop_terms_agree'])) {
            $_SESSION[CART_KEY]['error']['step2'] = true;
        } elseif(isset($_SESSION[CART_KEY]['error']['step2'])) {
            unset($_SESSION[CART_KEY]['error']['step2']);
        }

    } elseif( isset($_SESSION[CART_KEY]['error']['step2']) && !isset($_POST['shop_order_submit'])) {

        unset($_SESSION[CART_KEY]['error']['step2']);

    }
}

// first we take categories
if( $_shop_load_cat !== false ) {

    preg_match('/\{SHOP_CATEGORY:(\d+)\}/', $content['all'], $catmatch);
    if(!empty($catmatch[1])) {
        $shop_limited_cat = true;
        $shop_limited_catid = intval($catmatch[1]);
        if(empty($GLOBALS['_getVar']['shop_cat'])) {
            $GLOBALS['_getVar']['shop_cat'] = $shop_limited_catid;
        }
    } else {
        $shop_limited_cat = false;
    }

    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_categories WHERE ';
    $sql .= "cat_type='module_shop' AND cat_status=1 AND cat_pid=0 ";
    if($shop_limited_cat) {
        $sql .= 'AND cat_id = ' . $shop_limited_catid . ' ';
    }
    $sql .= 'ORDER BY cat_sort DESC, cat_name ASC';
    $data = _dbQuery($sql);

    $shop_cat = array();

    $shop_cat_selected = isset($GLOBALS['_getVar']['shop_cat']) ? $GLOBALS['_getVar']['shop_cat'] : 'all';
    if(strpos($shop_cat_selected, '_')) {
        $shop_cat_selected = explode('_', $shop_cat_selected, 2);
        if(isset($shop_cat_selected[1])) {
            $shop_subcat_selected   = intval($shop_cat_selected[1]);
        }
        $shop_cat_selected = intval($shop_cat_selected[0]);
        if(!$shop_cat_selected) {
            $shop_cat_selected      = 'all';
            $shop_subcat_selected   = 0;
        }
    } else {
        $shop_subcat_selected = 0;
    }

    $shop_detail_id = isset($GLOBALS['_getVar']['shop_detail']) ? intval($GLOBALS['_getVar']['shop_detail']) : 0;
    unset($GLOBALS['_getVar']['shop_cat'], $GLOBALS['_getVar']['shop_detail']);

    if($shop_detail_id) {
        $GLOBALS['_getVar']['shop_detail'] = $shop_detail_id;
    }

    if(is_array($data) && count($data)) {

        $x = 0;

        foreach($data as $row) {

            if($shop_limited_cat && $row['cat_id'] != $shop_limited_catid) {
                continue;
            }

            $shop_cat_prods = '';
            $shop_cat[$x] = '<li id="shopcat-'.$row['cat_id'].'"';
            $shop_cat_class = $_tmpl['config']['cat_class_item'];
            if($row['cat_id'] == $shop_cat_selected) {
                $shop_cat_class .= ' ' . $_tmpl['config']['cat_class_item_active'];

                // now try to retrieve sub categories for active category
                $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_categories WHERE ';
                $sql .= "cat_type='module_shop' AND cat_status=1 AND cat_pid=" . $shop_cat_selected ;
                $sql .= ' ORDER BY cat_sort DESC, cat_name ASC';
                $sdata = _dbQuery($sql);

                $subcat_count = count($sdata);

                $selected_product_cat = $subcat_count && $shop_subcat_selected ? $shop_subcat_selected : $shop_cat_selected;

                if($subcat_count) {

                    $shop_subcat = array();
                    $z = 0;
                    foreach($sdata as $srow) {

                        $shop_subcat[$z]   = '<li id="shopsubcat-'.$row['cat_id'].'" ';
                        $shop_subcat_class = $_tmpl['config']['cat_class_subitem'];
                        if($srow['cat_id'] == $shop_subcat_selected) {
                            $shop_subcat_class .= ' ' . $_tmpl['config']['cat_class_subitem_active'];
                        }
                        $shop_subcat[$z] .= 'class="' . trim($shop_subcat_class) . '">';
                        $shop_subcat[$z] .= '<a href="' . rel_url(array('shop_cat' => $srow['cat_pid'] . '_' . $srow['cat_id']), array('shop_detail', 'shop_cart'), $_tmpl['config']['shop_url']) . '" ';
                        $shop_subcat[$z] .= 'class="' .  $_tmpl['config']['cat_class_subitem_link'] . '">';
                        $shop_subcat[$z] .= '@@' . html($srow['cat_name']) . '@@';
                        if ($_tmpl['config']['cat_count_products']) {
                            $count_cat_products_sql  = "SELECT COUNT(*) FROM ".DB_PREPEND.'phpwcms_shop_products WHERE ';
                            $count_cat_products_sql .= "shopprod_status=1 AND (";
                            $count_cat_products_sql .= "shopprod_category = '" . $srow['cat_id'] . "' OR ";
                            $count_cat_products_sql .= "shopprod_category LIKE '%," . $srow['cat_id'] . ",%' OR ";
                            $count_cat_products_sql .= "shopprod_category LIKE '" . $srow['cat_id'] . ",%' OR ";
                            $count_cat_products_sql .= "shopprod_category LIKE '%," . $srow['cat_id'] . "')";
                            $count_cat_products_sql .= SHOP_FELANG_SQL; // FE language
                            $count_cat_products = _dbCount($count_cat_products_sql);

                            $shop_subcat[$z] .= ' ' . $_tmpl['config']['cat_count_products_prefix'];
                            $shop_subcat[$z] .= $count_cat_products;
                            $shop_subcat[$z] .= $_tmpl['config']['cat_count_products_suffix'];
                        }
                        $shop_subcat[$z] .= '</a>';
                        if($srow['cat_id'] == $shop_subcat_selected && $_tmpl['config']['cat_list_products']) {
                            $shop_subcat[$z] .= get_category_products($srow['cat_id'], $shop_detail_id, $shop_cat_selected, $shop_subcat_selected, $_tmpl['config']['shop_url']);
                        }
                        $shop_subcat[$z] .= '</li>';

                        $z++;
                    }

                    if(count($shop_subcat)) {
                        $shop_cat_prods = '<ul class="' . $_tmpl['config']['cat_class_submenu'] . '">' . implode('', $shop_subcat) . '</ul>';
                    }

                }

                if($_tmpl['config']['cat_list_products']) {
                     $shop_cat_prods .= get_category_products($shop_cat_selected, $shop_detail_id, $shop_cat_selected, $shop_subcat_selected, $_tmpl['config']['shop_url']);
                }

            }
            $shop_cat[$x] .= ' class="' . trim($shop_cat_class) . '">';
            $shop_cat[$x] .= '<a href="' . rel_url(array('shop_cat' => $row['cat_id']), array('shop_detail', 'shop_cart'), $_tmpl['config']['shop_url']) . '" ';
            $shop_cat[$x] .= 'class="' . $_tmpl['config']['cat_class_item_link'] . '">';
            $shop_cat[$x] .= '@@' . html($row['cat_name']) . '@@';
            if ($_tmpl['config']['cat_count_products']) {
                $count_cat_products_sql  = "SELECT COUNT(*) FROM ".DB_PREPEND.'phpwcms_shop_products WHERE ';
                $count_cat_products_sql .= "shopprod_status=1 AND (";
                $count_cat_products_sql .= "shopprod_category = '" . $row['cat_id'] . "' OR ";
                $count_cat_products_sql .= "shopprod_category LIKE '%," . $row['cat_id'] . ",%' OR ";
                $count_cat_products_sql .= "shopprod_category LIKE '" . $row['cat_id'] . ",%' OR ";
                $count_cat_products_sql .= "shopprod_category LIKE '%," . $row['cat_id'] . "')";
                $count_cat_products_sql .= SHOP_FELANG_SQL; // FE language
                $count_cat_products = _dbCount($count_cat_products_sql);

                $shop_cat[$x] .= ' ' . $_tmpl['config']['cat_count_products_prefix'];
                $shop_cat[$x] .= $count_cat_products;
                $shop_cat[$x] .= $_tmpl['config']['cat_count_products_suffix'];
            }
            $shop_cat[$x] .= '</a>' . $shop_cat_prods;
            $shop_cat[$x] .= '</li>';

            $x++;
        }
    }

    $shop_cat = count($shop_cat) ? implode('', $shop_cat) : '';
    $shop_cat_all = '';

    if(empty($_tmpl['config']['cat_all_pos'])) {
        // fallback for older templates
        $_tmpl['config']['cat_all_pos'] = 'bottom';
    } else {
        $_tmpl['config']['cat_all_pos'] = strtolower($_tmpl['config']['cat_all_pos']);
        if($_tmpl['config']['cat_all_pos'] != 'top' && $_tmpl['config']['cat_all_pos'] != 'bottom') {
            $_tmpl['config']['cat_all_pos'] = 'none';
        }
    }

    if( ! $shop_limited_cat && $_tmpl['config']['cat_all_pos'] != 'none') {
        $shop_cat_all .= '<li id="shopcat-all" ';
        $shop_cat_class = $_tmpl['config']['cat_class_item'];
        if($shop_cat_selected == 'all') {
            $shop_cat_class .= ' ' . $_tmpl['config']['cat_class_item_active'];
        }
        $shop_cat_all .= 'class="' . trim($shop_cat_class) . '">';
        $shop_cat_all .= '<a href="' . rel_url(array('shop_cat' => 'all'), array('shop_detail', 'shop_cart'), $_tmpl['config']['shop_url']) . '" ';
        $shop_cat_all .= 'class="' . $_tmpl['config']['cat_class_item_link'] . '">@@';
        $shop_cat_all .= html($_tmpl['config']['cat_all']);
        $shop_cat_all .= '@@</a>';
        $shop_cat_all .= '</li>';

        if($_tmpl['config']['cat_all_pos'] == 'top') {
            $shop_cat = $shop_cat_all . $shop_cat;
        } else {
            $shop_cat .= $shop_cat_all;
        }
    }

    if($shop_cat !== '') {
        $shop_cat = '<ul class="' . trim($template_default['classes']['shop-category-menu'] . ' ' . $_tmpl['config']['cat_class_menu']) . '">' . $shop_cat . '</ul>';
    }

    $content['all'] = str_replace('{SHOP_CATEGORIES}', $shop_cat, $content['all']);
    $content['all'] = preg_replace('/\{SHOP_CATEGORY:\d+\}/', $shop_cat, $content["all"]);

    if($shop_cat_selected) {
        $GLOBALS['_getVar']['shop_cat'] = $shop_cat_selected;
        if($shop_subcat_selected) {
            $GLOBALS['_getVar']['shop_cat'] .= '_' . $shop_subcat_selected;
        }
    }
}


// Ok lets search for product listing
if( $_shop_load_list !== false ) {

    // check selected category
    $shop_cat_selected = isset($GLOBALS['_getVar']['shop_cat']) ? $GLOBALS['_getVar']['shop_cat'] : 0;
    if(strpos($shop_cat_selected, '_')) {
        $shop_cat_selected = explode('_', $shop_cat_selected, 2);
        if(isset($shop_cat_selected[1])) {
            $shop_subcat_selected = intval($shop_cat_selected[1]);
        }
        $shop_cat_selected = intval($shop_cat_selected[0]);
        if(!$shop_cat_selected) {
            $shop_subcat_selected = 0;
        }
    } else {
        $shop_cat_selected      = intval($shop_cat_selected);
        $shop_subcat_selected   = 0;
    }
    $selected_product_cat = $shop_subcat_selected ? $shop_subcat_selected : $shop_cat_selected;
    $shop_cat_name = get_shop_category_name($shop_cat_selected, $shop_subcat_selected);
    if(isset($GLOBALS['_getVar']['shop_detail']) && ($shop_detail_id = intval($GLOBALS['_getVar']['shop_detail']))) {
        $_tmpl['config']['class_prefix_shop_mode'] .= 'detail';
    } else {
        $_tmpl['config']['class_prefix_shop_mode'] .= 'list';
        $shop_detail_id = 0;
    }

    if(empty($shop_cat_name)) {
        $shop_cat_name      = $_tmpl['config']['cat_all'];
        $shop_cat_selected  = 0;
    }

    $shop_pagetitle = '';

    $sql  = "SELECT * FROM ".DB_PREPEND.'phpwcms_shop_products WHERE ';
    $sql .= "shopprod_status=1";

    if($selected_product_cat && !$shop_detail_id) {

        $sql .= ' AND (';
        $sql .= "shopprod_category = '" . $selected_product_cat . "' OR ";
        $sql .= "shopprod_category LIKE '%," . $selected_product_cat . ",%' OR ";
        $sql .= "shopprod_category LIKE '" . $selected_product_cat . ",%' OR ";
        $sql .= "shopprod_category LIKE '%," . $selected_product_cat . "'";
        $sql .= ')';

    } elseif($shop_detail_id) {

        $sql .= ' AND shopprod_id=' . $shop_detail_id;

    } else {

        $sql .= ' AND shopprod_listall=1';

    }

    // FE language
    $sql .= SHOP_FELANG_SQL;

    $_tmpl['config']['cat_list_sort_by'] = trim($_tmpl['config']['cat_list_sort_by']);
    if($_tmpl['config']['cat_list_sort_by'] !== '') {
        $sql .= ' ORDER BY '.aporeplace($_tmpl['config']['cat_list_sort_by']);
    }

    $data = _dbQuery($sql);

    if( isset($data[0]) ) {

        $x = 0;
        $entry = array();

        $shop_prod_detail = rel_url(array(), array('shop_detail'));

        $_tmpl['config']['init_lightbox'] = false;

        foreach($data as $row) {

            $row['vat'] = (float) $row['shopprod_vat'];
            $row['vat_decimals'] = dec_num_count($row['vat']);
            if($row['vat_decimals'] < $_tmpl['config']['vat_decimals']) {
                $row['vat_decimals'] = $_tmpl['config']['vat_decimals'];
            }
            if($row['shopprod_netgross'] == 1) {
                // price given is GROSS price, including VAT
                $row['net']     = $row['shopprod_price'] / (1 + $row['vat'] / 100);
                $row['gross']   = $row['shopprod_price'];
            } else {
                // price given is NET price, excluding VAT
                $row['net']     = $row['shopprod_price'];
                $row['gross']   = $row['shopprod_price'] * (1 + $row['vat'] / 100);
            }

            $row['prices'] = array(
                'vat' => $row['vat'],
                'net' => $row['net'],
                'gross' => $row['gross'],
                'weight' => $row['shopprod_weight']
            );

            $row['vat']     = number_format($row['vat'],   $row['vat_decimals'],   $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
            $row['net']     = number_format($row['net'],   $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
            $row['gross']   = number_format($row['gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
            $row['weight']  = $row['shopprod_weight'] > 0 ? number_format($row['shopprod_weight'], $_tmpl['config']['weight_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']) : '';

            $row['shopprod_var'] = @unserialize($row['shopprod_var']);
            $row['shopprod_var']['request'] = !empty($row['shopprod_var']['request']);
            if (empty($row['shopprod_var']['request_url'])) {
                $row['shopprod_var']['request_url'] = trim($_tmpl['config']['default_request_url']);
            }

            // check custom product URL
            if(empty($row['shopprod_var']['url'])) {
                $row['prod_url'] = array('link'=>'', 'target'=>'');
            } else {
                $row['prod_url'] = get_redirect_link($row['shopprod_var']['url'], ' ', '');
                $row['prod_url']['link'] = html($row['prod_url']['link']);
            }

            // select template based on listing or detail view
            $entry[$x] = $shop_detail_id ? $_tmpl['detail'] : $_tmpl['list_entry'];

            // get the value from the textarea for options, prepare the data, write select drop down

            //order options 1
            $_cart_prod_opt1 = '';

            if($row['shopprod_size'] && $_cart_opt_1 = explode(LF, $row['shopprod_size'])) {
                foreach($_cart_opt_1 as $key => $value){
                    //title - first row in textarea - string
                    if($key === 0 && ($value = trim($value))) {
                        $_cart_prod_opt1 .= '<option value="">'.html($value).'</option>';
                        continue;
                    }

                    $value = get_shop_option_value($value);

                    $_cart_prod_opt1 .= '<option value="'.$key.'" data-type="' . $value['type'] . '" data-price="' . $value[1] . '">';
                    $_cart_prod_opt1 .= html($value[0]) . $value['option'];
                    $_cart_prod_opt1 .= '</option>';
                }
                $_cart_req_opt1 = empty($_tmpl['config']['product_option_1_required']) ? '' : ' ' . $_tmpl['config']['product_option_1_required'];
                $_cart_prod_opt1 = '<select name="prod_opt1" id="prod_opt1_'.$row['shopprod_id'].'" class="'.trim($_tmpl['config']['class_product_option_1'].' '.$_tmpl['config']['class_prefix_shop_mode']).'"' . $_cart_req_opt1 . '>' . $_cart_prod_opt1 . '</select>';
            }

            //order options 2
            $_cart_prod_opt2 = '';

            if($row['shopprod_color'] && $_cart_opt_2 = explode(LF, $row['shopprod_color'])) {
                foreach($_cart_opt_2 as $key => $value){
                    //title - first row in textarea - string
                    if($key === 0 && ($value = trim($value))) {
                        $_cart_prod_opt2 .= '<option value="">'.html($value).'</option>';
                        continue;
                    }

                    $value = get_shop_option_value($value);

                    $_cart_prod_opt2 .= '<option value="'.$key.'" data-type="' . $value['type'] . '" data-price="' . $value[1] . '">';
                    $_cart_prod_opt2 .= html($value[0]) . $value['option'];
                    $_cart_prod_opt2 .= '</option>';

                }
                $_cart_req_opt2 = empty($_tmpl['config']['product_option_2_required']) ? '' : ' ' . $_tmpl['config']['product_option_2_required'];
                $_cart_prod_opt2 = '<select name="prod_opt2" id="prod_opt2_'.$row['shopprod_id'].'" class="'.trim($_tmpl['config']['class_product_option_2'].' '.$_tmpl['config']['class_prefix_shop_mode']).'"' . $_cart_req_opt2 . '>' . $_cart_prod_opt2 . '</select>';
            }

            if($_tmpl['config']['on_request_trigger'] == $row['net']) {

                $_cart = '';
                $_cart_add = '';
                $_cart_on_request = true;

            } else {

                $_cart = preg_match("/\[CART_ADD\](.*?)\[\/CART_ADD\]/is", $entry[$x], $g) ? $g[1] : '';

                $_cart_add = '<form action="';
                $_cart_form_class = $_tmpl['config']['class_form_product_cart_option'].' '.$_tmpl['config']['class_prefix_shop_mode'];

                if ($row['shopprod_var']['request'] && $row['shopprod_var']['request_url']) {
                    $_cart_request_link = str_replace('&', '&amp;', $row['shopprod_var']['request_url']);
                    $_cart_request_link = str_replace('&amp;amp;', '&amp;', $_cart_request_link);
                    $_cart_request_link = str_replace('{PRODUCT}', urlencode($row['shopprod_name1']), $_cart_request_link);
                    $_cart_request_link = str_replace('{NUM}', urlencode($row['shopprod_ordernumber']), $_cart_request_link);

                    $_cart_add .= $_cart_request_link;
                    $_cart_form_class .= ' ' . $_tmpl['config']['class_request_form'];
                    $_cart_shop_action = 'request';
                    $_cart_button_class = $_tmpl['config']['class_request_button'];
                } else {
                    $_cart_add .= $shop_prod_detail;
                    $_cart_shop_action = 'add';
                    $_cart_request_link = '';
                    $_cart_button_class = $_tmpl['config']['class_cart_add_button'];
                }

                $_cart_add .= '" id="prod_form_'.$row['shopprod_id'].'" class="'.trim($_cart_form_class).'"';
                $_cart_add .= ' method="post" data-prices="' . html(json_encode($row['prices'])) . '">';
                $_cart_add .= '<input type="hidden" name="shop_prod_id" value="' . $row['shopprod_id'] . '" />';
                $_cart_add .= '<input type="hidden" name="shop_action" value="' . $_cart_shop_action . '" />';

                if ($_cart_request_link) {
                    $_cart_add .= '<input type="hidden" name="shop_product_title" value="' . html($row['shopprod_name1']) . '" />';
                    $_cart_add .= '<input type="hidden" name="shop_product_number" value="' . html($row['shopprod_ordernumber']) . '" />';
                }

                $_cart_manual_add = '';

                if(strpos($_cart, '<!-- SHOW-AMOUNT -->') !== false) {
                    // user has set amount manually
                    $_cart_manual_add .= $_tmpl['config']['amount_input_prefix'];
                    $_cart_manual_add .= '<input type="text" name="shop_prod_amount" id="shop_prod_amount_'.$row['shopprod_id'].'" class="';
                    $_cart_manual_add .= trim($_tmpl['config']['class_shop_amount'].' '.$_tmpl['config']['class_prefix_shop_mode']).'" value="1" size="2" />';
                    $_cart_manual_add .= $_tmpl['config']['amount_input_suffix'];
                    $_cart = str_replace('<!-- SHOW-AMOUNT -->', '', $_cart);
                } else {
                    $_cart_add .= '<input type="hidden" name="shop_prod_amount" value="1" />';
                }

                if($_tmpl['config']['amount_input_position'] === 'before') {
                    $_cart_add .= $_cart_manual_add;
                }

                if(strpos($_cart, '{PRODUCT_OPT1}') !== false) {
                    if ($_cart_prod_opt1) {
                        $_cart_add .= $_tmpl['config']['product_option_1_prefix'];
                        $_cart_add .= $_cart_prod_opt1;
                        $_cart_add .= $_tmpl['config']['product_option_1_suffix'];
                    }
                    $_cart = str_replace('{PRODUCT_OPT1}', '', $_cart);
                }

                if($_tmpl['config']['amount_input_position'] === 'between') {
                    $_cart_add .= $_cart_manual_add;
                }

                if(strpos($_cart, '{PRODUCT_OPT2}') !== false) {
                    if ($_cart_prod_opt2) {
                        $_cart_add .= $_tmpl['config']['product_option_2_prefix'];
                        $_cart_add .= $_cart_prod_opt2;
                        $_cart_add .= $_tmpl['config']['product_option_2_suffix'];
                    }
                    $_cart = str_replace('{PRODUCT_OPT2}', '', $_cart);
                }

                if($_tmpl['config']['amount_input_position'] === 'after') {
                    $_cart_add .= $_cart_manual_add;
                }

                if(strpos($_cart, 'input ') !== false) {
                    // user has set input button
                    $_cart_add .= $_cart;
                } else {
                    $_cart_add .= '<button type="submit" name="shop_cart_add" id="shop_cart_add_'.$row['shopprod_id'].'" class="'.trim($_cart_button_class.' '.$_tmpl['config']['class_prefix_shop_mode']).'">' . $_cart . '</button>';
                }

                $_cart_add .= '</form>';

                $_cart_on_request = false;
            }

            $entry[$x] = preg_replace('/\[CART_ADD\](.*?)\[\/CART_ADD\]/is', $_cart_add , $entry[$x]);

            // product name
            $entry[$x] = render_cnt_template($entry[$x], 'ON_REQUEST', $_cart_on_request);
            $entry[$x] = render_cnt_template($entry[$x], 'ON_REQUEST_LINK', $_cart_request_link);
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_TITLE', html($row['shopprod_name1']));
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_ADD', html($row['shopprod_name2']));
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_SHORT', $row['shopprod_description0']);
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_LONG', $row['shopprod_description1']);
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_WEIGHT', $row['weight']);
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_NET_PRICE', $row['net']);
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_GROSS_PRICE', $row['gross']);
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_VAT', $row['vat']);
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_URL', $row['prod_url']['link']);
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_UNIT', html($row['shopprod_unit']));
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_INVENTORY', html($row['shopprod_inventory']));
            $entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_INVENTORY_AVAILABLE', intval($row['shopprod_inventory']) > 0 ? ' ' : '');

            if(empty($_shopPref['shop_pref_discount']['discount']) || empty($_shopPref['shop_pref_discount']['percent'])) {
                $row['discount'] = '';
            } else {
                $row['discount'] = round($_shopPref['shop_pref_discount']['percent'], 2);
                if($row['discount'] - floor($row['discount']) == 0) {
                    $row['discount'] = number_format($row['discount'], 0, $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
                } else {
                    $row['discount'] = number_format($row['discount'], 1, $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
                }
            }
            $entry[$x] = render_cnt_template($entry[$x], 'DISCOUNT', $row['discount']);
            $entry[$x] = str_replace('{PRODUCT_URL_TARGET}', $row['prod_url']['target'], $entry[$x]);
            $entry[$x] = render_cnt_template($entry[$x], 'ORDER_NUM', html($row['shopprod_ordernumber']));
            $entry[$x] = render_cnt_template($entry[$x], 'MODEL', html($row['shopprod_model']));
            $entry[$x] = render_cnt_template($entry[$x], 'VIEWED', number_format($row['shopprod_track_view'], 0, $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']));

            if($shop_detail_id) {

                $_tmpl['config']['mode']        = 'detail';
                $_tmpl['config']['lightbox_id'] = '[product_'.$x.'_'.$shop_detail_id.']';

                if($row['shopprod_name2']) {
                    $row['shopprod_name2'] = sprintf(str_replace('%S', ' ', $_tmpl['config']['pagetitle_add']), $row['shopprod_name2']);
                }
                if($row['shopprod_model']) {
                    $row['shopprod_model'] = sprintf(str_replace('%S', ' ', $_tmpl['config']['pagetitle_model']), $row['shopprod_model']);
                }
                if($row['shopprod_ordernumber']) {
                    $row['shopprod_ordernumber'] = sprintf(str_replace('%S', ' ', $_tmpl['config']['pagetitle_ordernumber']), $row['shopprod_ordernumber']);
                }
                $shop_pagetitle = sprintf(
                    $_tmpl['config']['pagetitle'],
                    sprintf(str_replace('%S', ' ', $_tmpl['config']['pagetitle_productname']), $row['shopprod_name1']),
                    $row['shopprod_name2'],
                    $row['shopprod_model'],
                    $row['shopprod_ordernumber']
                );

                // product detail
                $entry[$x] = str_replace('{PRODUCT_DETAIL_LINK}', $shop_prod_detail, $entry[$x]);

                // Images
                $_prod_list_img = array();

                if(count($row['shopprod_var']['images'])) {

                    $row['shopprod_var']['img_count'] = 1;
                    $content['images']['shop'] = array();

                    foreach($row['shopprod_var']['images'] as $img_key => $img_vars) {
                        $img_vars['count'] = $row['shopprod_var']['img_count'];
                        if($_tmpl['config']['image_detail_more_start'] <= $row['shopprod_var']['img_count']) {
                            $_tmpl['config']['mode'] = 'detail_more';
                        }
                        if($img_vars = shop_image_tag($row['shopprod_var']['images'][$img_key], $img_vars['count'], $row['shopprod_name1'])) {
                            $_prod_list_img[] = $img_vars;
                            $row['shopprod_var']['img_count']++;
                        }
                        $content['images']['shop'][] = array(
                            'id'    => $row['shopprod_var']['images'][$img_key]['f_id'],
                            'name'  => $row['shopprod_var']['images'][$img_key]['f_name'],
                            'hash'  => $row['shopprod_var']['images'][$img_key]['f_hash'],
                            'ext'   => $row['shopprod_var']['images'][$img_key]['f_ext']
                        );
                    }
                }
                $_prod_list_img = implode($_tmpl['image_space'], $_prod_list_img);

                // Files
                $_prod_list_files = isset($row['shopprod_var']['files'][0]['f_id']) ? shop_files($row['shopprod_var']['files']) : '';

                if($row['shopprod_description0']) {
                    $row['meta_description'] = $row['shopprod_description0'];
                } elseif($row['shopprod_description1']) {
                    $row['meta_description'] = $row['shopprod_description1'];
                } else {
                    $row['meta_description'] = '';
                }

                if($row['meta_description']) {
                    $row['meta_description'] = trim( strip_tags( strip_bbcode($row['meta_description']) ) );
                    $row['meta_description'] = getCleanSubString($row['meta_description'], 75, '', 'word');
                    $row['meta_description_rendered'] = true;
                } else {
                    $row['meta_description_rendered'] = false;
                }

                if(!empty($row['shopprod_overwrite_meta'])) {
                    $content["pagetitle"] = setPageTitle($content["pagetitle"], $article['cat'], $shop_pagetitle);
                    if($row['meta_description_rendered']) {
                        set_meta('description', $row['meta_description']);
                    }
                }

                if($row['shopprod_opengraph']) {

                    $content['opengraph']['type'] = 'og:product';
                    $content['opengraph']['title'] = $shop_pagetitle;
                    $content['opengraph']['url'] = abs_url(array('shop_detail'=>$shop_detail_id), array('shop_cat', 'shop_cart', 'phpwcms_output_action', 'print', 'phpwcms-preview', 'unsubscribe', 'subscribe'));

                    if($row['meta_description_rendered']) {
                        $content['opengraph']['description'] = $row['meta_description'];
                    }

                } else {
                    $content['opengraph']['support'] = false;
                }

                // Update product view count
                // ToDo: Maybe use cookie or session to avoid tracking in case showed once
                $sql = 'UPDATE LOW_PRIORITY '.DB_PREPEND.'phpwcms_shop_products SET shopprod_track_view=shopprod_track_view+1 WHERE shopprod_id='.$shop_detail_id;
                _dbQuery($sql, 'UPDATE');

            } else {

                $_tmpl['config']['mode']        = 'list';
                $_tmpl['config']['lightbox_id'] = '';

                if(count($row['shopprod_var']['images'])) {
                    $_prod_list_img = shop_image_tag($row['shopprod_var']['images'][0], 0, $row['shopprod_name1']);
                } else {
                    $_prod_list_img = '';
                }

                // product listing
                $entry[$x] = str_replace('{PRODUCT_DETAIL_LINK}', $shop_prod_detail.'&amp;shop_detail='.$row['shopprod_id'], $entry[$x]);

                // no files in list mode
                $_prod_list_files = '';

            }

            if(!$_tmpl['config']['init_lightbox'] && $_tmpl['config']['image_'.$_tmpl['config']['mode'].'_lightbox'] && $_prod_list_img) {
                $_tmpl['config']['init_lightbox'] = true;
            }

            $entry[$x] = render_cnt_template($entry[$x], 'IMAGE', $_prod_list_img);

            // Render Files
            $entry[$x] = render_cnt_template($entry[$x], 'FILES', $_prod_list_files);

            $x++;
        }

        // initialize Lightbox effect
        if($_tmpl['config']['init_lightbox']) {
            initSlimbox();
        }

        $entries = implode($_tmpl['list_space'], $entry);

    } else {

        $entries = $_tmpl['list_none'];

    }

    if($shop_detail_id) {
        $entries = $_tmpl['config']['shop_wrap']['prefix'] . $entries . $_tmpl['config']['shop_wrap']['suffix'];
    } else {
        $entries = $_tmpl['config']['shop_wrap']['prefix'] . $_tmpl['list_header'] . LF . $entries . LF . $_tmpl['list_footer'] . $_tmpl['config']['shop_wrap']['suffix'];
    }

    $entries = str_replace('{CATEGORY}', html($shop_cat_name), $entries);
    $entries = render_cnt_template($entries, 'CART_LINK', is_cart_filled() ? rel_url(array('shop_cart' => 'show'), array('shop_detail'), $_tmpl['config']['cart_url']) : '');
    $entries = parse_cnt_urlencode($entries);

    $content['all'] = str_replace('{SHOP_PRODUCTLIST}', $entries, $content['all']);

    if(preg_match('/<!--\s{0,}RENDER_SHOP_PAGETITLE:(BEFORE|AFTER)\s{0,}-->/', $content['all'], $match)) {

        if(empty($GLOBALS['pagelayout']['layout_title_spacer'])) {
            $title_spacer = ' | ';
            $GLOBALS['pagelayout']['layout_title_spacer'] = $title_spacer;
        } else {
            $title_spacer = $GLOBALS['pagelayout']['layout_title_spacer'];
        }

        if($shop_pagetitle) {
            $shop_pagetitle .= $title_spacer;
        }

        $shop_pagetitle .= $shop_cat_name;

        if(empty($content['pagetitle'])) {
            $content['pagetitle'] = html($shop_pagetitle);
        } elseif($match[1] == 'BEFORE') {
            $content['pagetitle'] = html($shop_pagetitle . $title_spacer) . $content['pagetitle'];
        } else {
            $content['pagetitle'] .= html($title_spacer . $shop_pagetitle);
        }

        $content['all'] = str_replace($match[0], '', $content['all']);

    }
}

if( $_shop_load_order !== false ) {

    $cart_data = get_cart_data();

    if(empty($cart_data)) {

        // cart is empty
        $order_process = $_tmpl['cart_none'];

    } elseif(isset($_POST['shop_cart_checkout']) || isset($ERROR['inv_address']) || isset($_SESSION[CART_KEY]['error']['step1']) || isset($_POST['shop_edit_address'])) {

        // order Step 1 -> get address

        // checkout step 1 -> insert invoice address
        $order_process = $_tmpl['inv_address'];

        $_step1 = array(
            'INV_SALUTATION' => '',
            'INV_TITLE' => '',
            'INV_COMPANY' => '',
            'INV_FIRSTNAME' => '',
            'INV_NAME' => '',
            'INV_ADDRESS' => '',
            'INV_ADDRESS2' => '',
            'INV_ZIP' => '',
            'INV_CITY' => '',
            'INV_REGION' => '',
            'INV_COUNTRY' => '',
            'EMAIL' => '',
            'PHONE' => ''
        );

        // handle custom fields
        foreach($_tmpl['config']['shop_field'] as $item_key => $row) {
            if($row['type'] === 'CHECK') {
                $_step1['shop_field_'.$item_key] = $row['value'];
                if($_SESSION[CART_KEY]['step1']['shop_field_'.$item_key] && $_SESSION[CART_KEY]['step1']['shop_field_'.$item_key] == $row['value']) {
                    $order_process  = render_cnt_template($order_process, 'shop_field_'.$item_key, html($row['value']).'" checked="checked');
                } else {
                    $order_process  = render_cnt_template($order_process, 'shop_field_'.$item_key, html($row['value']));
                }
            } else {
                $_step1['shop_field_'.$item_key] = '';
            }
        }

        if(isset($_SESSION[CART_KEY]['step1'])) {
            $_step1 = array_merge($_step1, $_SESSION[CART_KEY]['step1']);
        }

        foreach($_step1 as $item_key => $row) {

            // Handle special fields first, have no error setting yet
            if($item_key === 'INV_SALUTATION' && strpos($order_process, '[INV_SALUTATION_') !== false) {
                // [INV_SALUTATION_SELECTED:value] => selected="selected"
                // [INV_SALUTATION_CHECKED:value] => checked="checked"
                $order_process = preg_replace_callback(
                    '/\[INV_SALUTATION_(CHECKED|SELECTED):(.+?)\]/',
                    function($match) use ($row) {
                        if($row == $match[2]) {
                            return $match[1] == 'CHECKED' ? ' checked="checked"' : ' selected="selected"';
                        }
                        return '';
                    },
                    $order_process
                );
                continue;
            } elseif($item_key === 'INV_COUNTRY' && strpos($order_process, '[COUNTRY_OPTIONS') !== false) {
                // [COUNTRY_OPTIONS:DE]Land w�hlen[/COUNTRY_OPTIONS]
                $order_process = preg_replace_callback(
                    '/\[COUNTRY_OPTIONS(:[A-Z]{2,2}){0,1}\](.*?)\[\/COUNTRY_OPTIONS\]/',
                    function($match) use ($row) {
                        if($row) {
                            $selected = $row;
                        } elseif($match[1] && ($match[1] = substr($match[1], 1))) {
                            $selected = $match[1];
                        } else {
                            $selected = '';
                        }
                        $options = '';
                        if($match[2]) {
                            $options .= '<option value="">' . $match[2] . '</option>';
                        }
                        $options .= list_country($selected);
                        return $options;
                    },
                    $order_process
                );
                continue;
            }

            $field_error = empty($ERROR['inv_address'][$item_key]) ? '' : $ERROR['inv_address'][$item_key];
            $row = html($row);
            $order_process  = render_cnt_template($order_process, $item_key, $row);
            $order_process  = render_cnt_template($order_process, 'ERROR_'.$item_key, $field_error);
        }

        if ($_shopPref['shop_pref_shipping_selfpickup']) {
            $selfpickup_field  = '<div class="shop-selfpickup"><label class="shop-selfpickup-label">';
            $selfpickup_field .= '<input type="checkbox" name="shopping_selfpickup" id="shopping_selfpickup" ';
            $selfpickup_field .= 'value="1" ';
            if(!empty($_SESSION[CART_KEY]['selfpickup'])) {
                $selfpickup_field .= ' checked="checked"';
            }
            $selfpickup_field .= ' class="shop-selfpickup-checkbox" />';
            $selfpickup_field .= '<span>';
            if (empty($_shopPref['shop_pref_discount']['freeshipping_pickup'])) {
                $selfpickup_field .= html($_tmpl['config']['label_selfpickup']);
            } else {
                $selfpickup_field .= html($_tmpl['config']['label_selfpickup_freeshipping']);
            }
            $selfpickup_field .= '</span>';
            $selfpickup_field .= '</label></div>';
            $order_process = render_cnt_template($order_process, 'SELFPICKUP', $selfpickup_field);
        } else {
            $order_process = render_cnt_template($order_process, 'SELFPICKUP', '');
        }

        $payment_options = get_payment_options();

        if(count($payment_options)) {

            $payment_fields = array();
            $payment_selected = isset($_SESSION[CART_KEY]['payby']) && isset($payment_options[ $_SESSION[CART_KEY]['payby'] ]) ? $_SESSION[CART_KEY]['payby'] : '';
            foreach($payment_options as $item_key => $row) {
                $payment_fields[$item_key]  = '<div class="shop-payment-option"><label class="shop-payment-option-label">';
                $payment_fields[$item_key] .= '<input type="radio" name="shopping_payment" id="shopping_payment_'.$item_key.'" ';
                $payment_fields[$item_key] .= 'value="'.$item_key.'" ';
                if($payment_selected == $item_key) {
                    $payment_fields[$item_key] .= ' checked="checked"';
                }
                $payment_fields[$item_key] .= ' class="shop-payment-option-radio" />';
                $payment_fields[$item_key] .= '<span>' . html($_tmpl['config']['label_payby_'.$item_key]) . '</span>';
                $payment_fields[$item_key] .= '</label></div>';
            }
            $order_process = render_cnt_template($order_process, 'PAYMENT', implode(LF, $payment_fields));
        } else {
            $order_process = render_cnt_template($order_process, 'PAYMENT', '');
        }

        // some error handling
        $order_process = render_cnt_template($order_process, 'ERROR_PAYMENT', isset($ERROR['inv_address']['payment']) ? ' ' : '');
        $order_process = render_cnt_template($order_process, 'IF_ERROR', isset($ERROR['inv_address']) ? ' ' : '');

        $order_process = '<form action="' . rel_url(array('shop_cart' => 'show'), array('shop_detail'), $_tmpl['config']['cart_url']) . '" class="'.$_tmpl['config']['class_form_cart'].'" id="'.$_tmpl['config']['class_form_id'].'" method="post">' . $order_process . '</form>';

    } elseif( isset($_POST['shop_order_step1']) || isset($ERROR['terms']) || isset($_SESSION[CART_KEY]['error']['step2']) ) {

        // Order step 2 -> Proof and [X] terms of business
        $order_process = $_tmpl['order_terms'];

        $order_process = str_replace('{SHOP_LINK}', rel_url(array(), array('shop_cat', 'shop_cart', 'shop_detail'), $_tmpl['config']['shop_url']), $order_process);
        $order_process = str_replace('{CART_LINK}', rel_url(array('shop_cart' => 'show'), array('shop_detail'), $_tmpl['config']['cart_url']), $order_process);

        foreach($_SESSION[CART_KEY]['step1'] as $item_key => $row) {
            $order_process = render_cnt_template($order_process, $item_key, nl2br(html($row)));
        }

        $order_process = render_cnt_template($order_process, 'IF_ERROR', isset($_SESSION[CART_KEY]['error']['step2']) ? ' ' : '');

        if(isset($_SESSION[CART_KEY]['payby'])) {
            $order_process = render_cnt_template($order_process, 'PAYMENT', html($_tmpl['config']['label_payby_'.$_SESSION[CART_KEY]['payby']]));
        } else {
            $order_process = render_cnt_template($order_process, 'PAYMENT', '');
        }

        $cart_mode = 'terms';
        include $phpwcms['modules']['shop']['path'].'inc/cart.items.inc.php';
        $order_process = str_replace('{ITEMS}', implode($_tmpl['term_space'], $cart_items), $order_process);

        $terms_text     = _getConfig( 'shop_pref_terms', '_shopPref' );
        $terms_format   = _getConfig( 'shop_pref_terms_format', '_shopPref' );
        $order_process = str_replace('{TERMS}', $terms_format ? $terms_text : nl2br(html($terms_text)), $order_process);

        include $phpwcms['modules']['shop']['path'].'inc/cart.parse.inc.php';
        include $phpwcms['modules']['shop']['path'].'inc/shipping.parse.inc.php';

    } elseif( isset($_POST['shop_order_submit']) && !isset($_SESSION[CART_KEY]['error']['step2']) ) {

        // OK agreed - now send order

        if($_tmpl['config']['order_number_style'] == 'RANDOM') {
            $order_num = generic_string(8, 2);
        } else {
            // count all current orders
            $order_num = _dbCount('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_shop_orders') + 1;
            if(strpos($_tmpl['config']['order_number_style'], '%') !== FALSE) {
                $order_num = sprintf($_tmpl['config']['order_number_style'], $order_num);
            }
        }

        // prepare customer mail
        $order_process = $_tmpl['mail_customer'];

        foreach($_SESSION[CART_KEY]['step1'] as $item_key => $row) {
            $order_process = render_cnt_template($order_process, $item_key, html($row));
        }

        $cart_mode = 'mail1';
        include $phpwcms['modules']['shop']['path'].'inc/cart.items.inc.php';
        $order_process = str_replace('{ITEMS}', implode(LF.LF, $cart_items), $order_process);

        include $phpwcms['modules']['shop']['path'].'inc/cart.parse.inc.php';

        $order_process = str_replace('{ORDER}', $order_num, $order_process);
        $order_process = render_cnt_date($order_process, time());

        $mail_customer = @html_entity_decode($order_process);

        // prepare new order mail
        $order_process = $_tmpl['mail_neworder'];

        foreach($_SESSION[CART_KEY]['step1'] as $item_key => $row) {
            $order_process = render_cnt_template($order_process, $item_key, html($row));
        }

        $cart_mode = 'mail1';
        include $phpwcms['modules']['shop']['path'].'inc/cart.items.inc.php';
        $order_process = str_replace('{ITEMS}', implode(LF.LF, $cart_items), $order_process);

        include $phpwcms['modules']['shop']['path'].'inc/cart.parse.inc.php';

        $order_process = str_replace('{ORDER}', $order_num, $order_process);
        $order_process = render_cnt_date($order_process, time());

        $mail_neworder = @html_entity_decode($order_process);

        if(empty($_SESSION[CART_KEY]['selfpickup'])) {
            $mail_customer = render_cnt_template($mail_customer, 'SELFPICKUP', '');
            $mail_neworder = render_cnt_template($mail_neworder, 'SELFPICKUP', '');
        } else {
            $mail_customer = render_cnt_template($mail_customer, 'SELFPICKUP', ' ');
            $mail_neworder = render_cnt_template($mail_neworder, 'SELFPICKUP', ' ');
        }

        if(!empty($_SESSION[CART_KEY]['payby'])) {
            $payment = $_SESSION[CART_KEY]['payby'];
            $mail_customer = render_cnt_template($mail_customer, 'PAYBY_'.strtoupper($payment), $_tmpl['config']['label_payby_'.$payment]);
            $mail_customer = render_cnt_template($mail_customer, 'PAYMENT', $_tmpl['config']['label_payby_'.$payment]);
            $mail_neworder = render_cnt_template($mail_neworder, 'PAYBY_'.strtoupper($payment), $_tmpl['config']['label_payby_'.$payment]);
            $mail_neworder = render_cnt_template($mail_neworder, 'PAYMENT', $_tmpl['config']['label_payby_'.$payment]);
        } else {
            $mail_customer = render_cnt_template($mail_customer, 'PAYBY_'.strtoupper($payment), 'n.a.');
            $mail_customer = render_cnt_template($mail_customer, 'PAYMENT', 'n.a.');
            $mail_neworder = render_cnt_template($mail_neworder, 'PAYBY_'.strtoupper($payment), 'n.a.');
            $mail_neworder = render_cnt_template($mail_neworder, 'PAYMENT', 'n.a.');
            $payment = 'n.a.';
        }

        if($subtotal['shipping_calc_type'] === 2) {
            $mail_customer = render_cnt_template($mail_customer, 'SHIPPING_DISTANCE', number_format($subtotal['shipping_distance'] / 1000, 1, $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']));
            $mail_neworder = render_cnt_template($mail_neworder, 'SHIPPING_DISTANCE', number_format($subtotal['shipping_distance'] / 1000, 1, $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']));
            $mail_customer = str_replace('{SHIPPING_DISTANCE_LABEL}', $subtotal['shipping_distance_details']['label'], $mail_customer);
            $mail_neworder = str_replace('{SHIPPING_DISTANCE_LABEL}', $subtotal['shipping_distance_details']['label'], $mail_neworder);
        } else {
            $mail_customer = render_cnt_template($mail_customer, 'SHIPPING_DISTANCE', '');
            $mail_neworder = render_cnt_template($mail_neworder, 'SHIPPING_DISTANCE', '');
        }

        $payment_options = get_payment_options();
        foreach($payment_options as $item_key => $row) {
            $mail_customer = render_cnt_template($mail_customer, 'PAYBY_'.strtoupper($item_key), '');
            $mail_neworder = render_cnt_template($mail_neworder, 'PAYBY_'.strtoupper($item_key), '');
        }

        $mail_customer = str_replace(array('{CURRENCY_SYMBOL}', '{$}'), $_shopPref['shop_pref_currency'], $mail_customer);
        $mail_neworder = str_replace(array('{CURRENCY_SYMBOL}', '{$}'), $_shopPref['shop_pref_currency'], $mail_neworder);

        // store order in database
        $order_data = array(
            'order_number'      => $order_num,
            'order_date'        => gmdate('Y-m-d H:i'),
            'order_name'        => $_SESSION[CART_KEY]['step1']['INV_NAME'],
            'order_firstname'   => $_SESSION[CART_KEY]['step1']['INV_FIRSTNAME'],
            'order_email'       => $_SESSION[CART_KEY]['step1']['EMAIL'],
            'order_net'         => $subtotal['float_total_net'],
            'order_gross'       => $subtotal['float_total_gross'],
            'order_payment'     => $payment,
            'order_data'        => @serialize( array(
                'cart' => $cart_data,
                'address' => $_SESSION[CART_KEY]['step1'],
                'mail_customer' => $mail_customer,
                'mail_self' => $mail_neworder,
                'subtotal' => array(
                    'subtotal_net' => $subtotal['float_net'],
                    'subtotal_gross' => $subtotal['float_gross']
                ),
                'shipping' => array(
                    'shipping_net' => $subtotal['float_shipping_net'],
                    'shipping_gross' => $subtotal['float_shipping_gross'],
                    'shipping_distance' => $subtotal['shipping_distance'] === false ? 0 : $subtotal['shipping_distance'],
                    'selfpickup' => empty($_SESSION[CART_KEY]['selfpickup']) ? 0 : 1
                ),
                'discount' => array(
                    'discount_net' => $subtotal['float_discount_net'],
                    'discount_gross' => $subtotal['float_discount_gross']
                ),
                'loworder' => array(
                    'loworder_net' => $subtotal['float_loworder_net'],
                    'loworder_gross' => $subtotal['float_loworder_gross']
                ),
                'weight' => $subtotal['float_weight'],
                'lang' => $phpwcms['default_lang'],
                'distance' => $subtotal['shipping_distance'] === false ? null : $subtotal['shipping_distance_details']
            ) ),
            'order_status'      => 'NEW-ORDER'
        );

        // receive order db ID
        $order_data = _dbInsert('phpwcms_shop_orders', $order_data);

        // send mail to customer
        $email_from = _getConfig( 'shop_pref_email_from', '_shopPref' );
        if(!is_valid_email($email_from)) {
            $email_from = $phpwcms['SMTP_FROM_EMAIL'];
        }

        $order_mail_customer = array(
            'recipient' => $_SESSION[CART_KEY]['step1']['EMAIL'],
            'toName'    => $_SESSION[CART_KEY]['step1']['INV_FIRSTNAME'] . ' ' . $_SESSION[CART_KEY]['step1']['INV_NAME'],
            'subject'   => str_replace('{ORDER}', $order_num, $_tmpl['config']['mail_customer_subject']),
            'text'      => $mail_customer,
            'from'      => $email_from,
            'sender'    => $email_from
        );

        $order_data_mail_customer = sendEmail($order_mail_customer);

        // send mail to shop
        $send_order_to = convertStringToArray( _getConfig( 'shop_pref_email_to', '_shopPref' ), ';' );
        if(empty($send_order_to[0]) || !is_valid_email($send_order_to[0])) {
            $email_to = $phpwcms['SMTP_FROM_EMAIL'];
        } else {
            $email_to = $send_order_to[0];
            unset($send_order_to[0]);
        }

        $order_mail_self = array(
            'from' => $email_from,
            'subject' => str_replace('{ORDER}', $order_num, $_tmpl['config']['mail_neworder_subject']),
            'text' => $mail_neworder,
            'recipient' => $email_to,
            'sender' => $_SESSION[CART_KEY]['step1']['EMAIL'],
            'senderName' => $_SESSION[CART_KEY]['step1']['INV_FIRSTNAME'] . ' ' . $_SESSION[CART_KEY]['step1']['INV_NAME']
        );

        $order_data_mail_self = sendEmail($order_mail_self);

        // are there additional recipients for orders?
        if(count($send_order_to)) {
            foreach($send_order_to as $value) {
                $order_mail_self['recipient'] = $value;
                @sendEmail($order_mail_self);
            }
        }

        // success
        if(!empty($order_data['INSERT_ID']) || !empty($order_data_mail_customer[0])) {

            $order_process = $_tmpl['order_success'];

            $shop_pref_autosubtract_off = _getConfig( 'shop_pref_autosubtract_off', '_shopPref' );

            if (empty($shop_pref_autosubtract_off)) {
                foreach($_SESSION[CART_KEY]['amount'] as $update_product_id => $subtract_amount) {
                    $subtract_query = 'UPDATE `' . DB_PREPEND . 'phpwcms_shop_products` SET ';
                    $subtract_query .= '`shopprod_inventory`=`shopprod_inventory`-' . intval($subtract_amount) . ' ';
                    $subtract_query .= 'WHERE `shopprod_id`=' . _dbEscape($update_product_id);
                    _dbQuery($subtract_query, 'UPDATE');
                }
            }

            foreach($_SESSION[CART_KEY]['step1'] as $item_key => $row) {
                $order_process = render_cnt_template($order_process, $item_key, html($row));
            }
            unset($_SESSION[CART_KEY]);

        // NO success
        } else {

            $order_process = $_tmpl['order_failed'];

            $order_process = str_replace('{SUBJECT}', rawurlencode($_tmpl['config']['mail_neworder_subject']), $order_process);
            $order_process = str_replace('{MSG}', rawurlencode('---- FALLBACK MESSAGE ---' . LF . LF . $mail_customer), $order_process);

            foreach($_SESSION[CART_KEY]['step1'] as $item_key => $row) {
                $order_process = render_cnt_template($order_process, $item_key, html($row));
            }

        }

        $order_process = str_replace('{ORDER}', $order_num, $order_process);

    // show cart
    } else {

        $cart_mode = 'cart';
        include $phpwcms['modules']['shop']['path'].'inc/cart.items.inc.php';

        $order_process  = $_tmpl['cart_header'];
        $order_process .= implode($_tmpl['cart_space'], $cart_items);
        $order_process .= $_tmpl['cart_footer'];

        include $phpwcms['modules']['shop']['path'].'inc/cart.parse.inc.php';

        // Update Cart Button
        $_cart_button = preg_match("/\[UPDATE\](.*?)\[\/UPDATE\]/s", $order_process, $g) ? $g[1] : '';
        if(strpos($_cart_button, 'input ') === false) {
            $_cart_button = '<input type="submit" name="shop_cart_update" value="' . html($_cart_button) . '" class="cart-update-button" />';
        }
        $order_process  = preg_replace('/\[UPDATE\](.*?)\[\/UPDATE\]/s', $_cart_button , $order_process);

        // Checkout Button
        $_cart_button = preg_match("/\[CHECKOUT\](.*?)\[\/CHECKOUT\]/s", $order_process, $g) ? $g[1] : '';
        if(strpos($_cart_button, 'input ') === false) {
            $_cart_button = '<input type="submit" name="shop_cart_checkout" value="' . html($_cart_button) . '" class="cart-checkout-button" />';
        }
        $order_process  = preg_replace('/\[CHECKOUT\](.*?)\[\/CHECKOUT\]/s', $_cart_button , $order_process);

        // Empty Cart Button
        $_cart_button = preg_match("/\[DELETE\](.*?)\[\/DELETE\]/s", $order_process, $g) ? $g[1] : '';
        if(strpos($_cart_button, 'input ') === false) {
            $_cart_button = '<input type="submit" name="shop_cart_delete" value="' . html($_cart_button) . '" class="cart-delete-button" />';
        }
        $order_process  = preg_replace('/\[DELETE\](.*?)\[\/DELETE\]/s', $_cart_button , $order_process);

        include $phpwcms['modules']['shop']['path'].'inc/shipping.parse.inc.php';

        $order_process = '<form action="' . rel_url(array('shop_cart' => 'show'), array('shop_detail'), $_tmpl['config']['cart_url']) . '" class="'.$_tmpl['config']['class_form_cart'].'" method="post">' . LF . trim($order_process) . LF . '</form>';

    }

    $order_process = str_replace('{SHOP_LINK}', rel_url(array(), array('shop_cart', 'shop_detail'), $_tmpl['config']['shop_url']), $order_process);

    $content['all'] = str_replace('{SHOP_ORDER_PROCESS}', $_tmpl['config']['shop_wrap']['prefix'] . $order_process . $_tmpl['config']['shop_wrap']['suffix'], $content['all']);
}

// small cart
if($_shop_load_cart_small !== false ) {

    if(empty($_SESSION[CART_KEY]['total']) || !is_array($_SESSION[CART_KEY]['total']) || ($_cart_count = array_sum($_SESSION[CART_KEY]['total'])) === 0) {
        $_cart_count = '';
    }

    if(strpos($_tmpl['cart_small'], '{CART_LINK}')) {

        $shop_cat_selected  = isset($GLOBALS['_getVar']['shop_cat']) ? $GLOBALS['_getVar']['shop_cat'] : 0;
        $shop_detail_id     = isset($GLOBALS['_getVar']['shop_detail']) ? intval($GLOBALS['_getVar']['shop_detail']) : 0;
        unset($GLOBALS['_getVar']['shop_cat'], $GLOBALS['_getVar']['shop_detail']);
        $_tmpl['cart_small'] = str_replace('{CART_LINK}', rel_url(array('shop_cart' => 'show'), array(), $_tmpl['config']['cart_url']), $_tmpl['cart_small']);
        if($shop_cat_selected) {
            $GLOBALS['_getVar']['shop_cat'] = $shop_cat_selected;
        }
        if($shop_detail_id) {
            $GLOBALS['_getVar']['shop_detail'] = $shop_detail_id;
        }
    }

    $_tmpl['cart_small'] = render_cnt_template($_tmpl['cart_small'], 'COUNT', $_cart_count);
    $content['all'] = str_replace('{CART_SMALL}', $_tmpl['cart_small'], $content['all']);
}

// global shop replacer, faster doing this only once
if($_shop_parsed) {
    $content['all'] = str_replace(array('{CURRENCY_SYMBOL}', '{$}'), html($_shopPref['shop_pref_currency']), $content['all']);
}
