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


if ($action == 'edit') {

    if (isset($_POST['save'])) {

        $plugin['data']['shop_pref_currency'] = clean_slweg($_POST['pref_currency']);
        $plugin['data']['shop_pref_unit_weight'] = clean_slweg($_POST['pref_unit_weight']);

        $plugin['data']['shop_pref_terms'] = slweg($_POST['pref_terms']);
        $plugin['data']['shop_pref_terms_format'] = empty($_POST['pref_terms_format']) ? 0 : 1;

        $plugin['data']['shop_pref_api_key'] = slweg($_POST['pref_api_key']);
        $plugin['data']['shop_pref_api_access'] = empty($_POST['pref_api_access']) ? 0 : 1;

        $plugin['data']['shop_pref_felang'] = empty($_POST['pref_felang']) ? 0 : 1;
        $plugin['data']['shop_pref_autosubtract_off'] = empty($_POST['pref_autosubtract_off']) ? 0 : 1;

        $plugin['data']['shop_pref_id_shop'] = slweg($_POST['pref_shop_id']);
        $plugin['data']['shop_pref_id_cart'] = slweg($_POST['pref_cart_id']);

        $plugin['data']['shop_pref_vat'] = clean_slweg($_POST['pref_vat']);
        $plugin['data']['shop_pref_vat'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_vat']);
        $plugin['data']['shop_pref_vat'] = str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_vat']);
        $plugin['data']['shop_pref_vat'] = explode(LF, $plugin['data']['shop_pref_vat']);
        $plugin['data']['shop_pref_vat'] = array_map('roundAll', $plugin['data']['shop_pref_vat']);
        natsort($plugin['data']['shop_pref_vat']);
        $plugin['data']['shop_pref_vat'] = array_unique($plugin['data']['shop_pref_vat']);

        $plugin['data']['shop_pref_email_to'] = convertStringToArray(sanitize_multiple_emails(clean_slweg($_POST['pref_email_to'])), ';');
        $plugin['data']['shop_pref_email_from'] = clean_slweg($_POST['pref_email_from']);
        $plugin['data']['shop_pref_email_paypal'] = clean_slweg($_POST['pref_email_paypal']);

        $plugin['data']['shop_pref_shipping_calc'] = empty($_POST['pref_shipping_calc']) ? 0 : abs(intval($_POST['pref_shipping_calc']));
        if ($plugin['data']['shop_pref_shipping_calc'] > 2) {
            $plugin['data']['shop_pref_shipping_calc'] = 2;
        }

        $plugin['data']['shop_pref_shipping_selfpickup'] = empty($_POST['pref_shipping_selfpickup']) ? 0 : 1;

        $plugin['data']['shop_pref_zone_base'] = clean_slweg($_POST['pref_zone_base']);

        // check if multiple emails
        foreach ($plugin['data']['shop_pref_email_to'] as $key => $value) {
            if (!is_valid_email($value)) {
                unset($plugin['data']['shop_pref_email_to'][$key]);
            }
        }
        $plugin['data']['shop_pref_email_to'] = strtolower(implode(';', $plugin['data']['shop_pref_email_to']));

        if (!is_valid_email($plugin['data']['shop_pref_email_from'])) {
            $plugin['data']['shop_pref_email_from'] = '';
        }
        if (!is_valid_email($plugin['data']['shop_pref_email_paypal'])) {
            $plugin['data']['shop_pref_email_paypal'] = '';
        }

        if ($plugin['data']['shop_pref_api_access'] && $plugin['data']['shop_pref_api_key'] === '') {
            $plugin['data']['shop_pref_api_key'] = preg_replace('/[^a-zA-Z0-9]/', '', shortHash(PHPWCMS_URL . $phpwcms['db_pass']));
        }

        for ($x = 0; $x <= 4; $x++) {

            // Weight based
            $plugin['data']['shop_pref_shipping'][$x]['weight'] = clean_slweg($_POST['pref_shipping_weight'][$x]);
            $plugin['data']['shop_pref_shipping'][$x]['net'] = clean_slweg($_POST['pref_shipping_net'][$x]);
            $plugin['data']['shop_pref_shipping'][$x]['vat'] = clean_slweg($_POST['pref_shipping_vat'][$x]);

            $plugin['data']['shop_pref_shipping'][$x]['weight'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_shipping'][$x]['weight']);
            $plugin['data']['shop_pref_shipping'][$x]['weight'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['weight']), 3);

            $plugin['data']['shop_pref_shipping'][$x]['net'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_shipping'][$x]['net']);
            $plugin['data']['shop_pref_shipping'][$x]['net'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['net']), 3);

            $plugin['data']['shop_pref_shipping'][$x]['vat'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_shipping'][$x]['vat']);
            $plugin['data']['shop_pref_shipping'][$x]['vat'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['vat']), 2);

            // Price based
            $plugin['data']['shop_pref_shipping'][$x]['price'] = clean_slweg($_POST['pref_shipping_price'][$x]);
            $plugin['data']['shop_pref_shipping'][$x]['price_net'] = clean_slweg($_POST['pref_shipping_price_net'][$x]);
            $plugin['data']['shop_pref_shipping'][$x]['price_vat'] = clean_slweg($_POST['pref_shipping_price_vat'][$x]);

            $plugin['data']['shop_pref_shipping'][$x]['price'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_shipping'][$x]['price']);
            $plugin['data']['shop_pref_shipping'][$x]['price'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['price']), 3);

            $plugin['data']['shop_pref_shipping'][$x]['price_net'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_shipping'][$x]['price_net']);
            $plugin['data']['shop_pref_shipping'][$x]['price_net'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['price_net']), 3);

            $plugin['data']['shop_pref_shipping'][$x]['price_vat'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_shipping'][$x]['price_vat']);
            $plugin['data']['shop_pref_shipping'][$x]['price_vat'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['price_vat']), 2);

            // Zone based
            $plugin['data']['shop_pref_shipping'][$x]['zone'] = intval($_POST['pref_shipping_zone'][$x]);
            $plugin['data']['shop_pref_shipping'][$x]['zone_net'] = clean_slweg($_POST['pref_shipping_zone_net'][$x]);
            $plugin['data']['shop_pref_shipping'][$x]['zone_vat'] = clean_slweg($_POST['pref_shipping_zone_vat'][$x]);
            $plugin['data']['shop_pref_shipping'][$x]['zone_label'] = clean_slweg($_POST['pref_shipping_zone_label'][$x]);

            $plugin['data']['shop_pref_shipping'][$x]['zone'] = empty($plugin['data']['shop_pref_shipping'][$x]['zone']) ? '' : intval($plugin['data']['shop_pref_shipping'][$x]['zone']);

            $plugin['data']['shop_pref_shipping'][$x]['zone_net'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_shipping'][$x]['zone_net']);
            $plugin['data']['shop_pref_shipping'][$x]['zone_net'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['zone_net']), 3);

            $plugin['data']['shop_pref_shipping'][$x]['zone_vat'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_shipping'][$x]['zone_vat']);
            $plugin['data']['shop_pref_shipping'][$x]['zone_vat'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['zone_vat']), 2);
        }

        $plugin['data']['shop_pref_payment'] = array(
            'paypal' => empty($_POST['pref_payment_paypal']) ? 0 : 1,
            'prepay' => empty($_POST['pref_payment_prepay']) ? 0 : 1,
            'pod' => empty($_POST['pref_payment_pod']) ? 0 : 1,
            'onbill' => empty($_POST['pref_payment_onbill']) ? 0 : 1,
            'ccard' => empty($_POST['pref_payment_ccard']) ? 0 : 1,
            'cash' => empty($_POST['pref_payment_cash']) ? 0 : 1,
            'accepted_ccard' => (is_array($_POST['pref_supported_ccard']) ? $_POST['pref_supported_ccard'] : array())
        );

        // Discount Setting
        $plugin['data']['shop_pref_discount'] = array(
            'discount' => empty($_POST['pref_discount']) ? 0 : 1,
            'percent' => clean_slweg($_POST['pref_discount_percent']),
            'amount' => clean_slweg($_POST['pref_discount_amount']),
            'freeshipping' => empty($_POST['pref_discount_freeshipping']) ? 0 : 1,

            'discount_1' => empty($_POST['pref_discount_1']) ? 0 : 1,
            'percent_1' => clean_slweg($_POST['pref_discount_percent_1']),
            'amount_1' => clean_slweg($_POST['pref_discount_amount_1']),
            'freeshipping_1' => empty($_POST['pref_discount_freeshipping_1']) ? 0 : 1,

            'discount_2' => empty($_POST['pref_discount_2']) ? 0 : 1,
            'percent_2' => clean_slweg($_POST['pref_discount_percent_2']),
            'amount_2' => clean_slweg($_POST['pref_discount_amount_2']),
            'freeshipping_2' => empty($_POST['pref_discount_freeshipping_2']) ? 0 : 1,

            'freeshipping_pickup' => empty($_POST['pref_freeshipping_pickup']) ? 0 : 1
        );
        $plugin['data']['shop_pref_discount']['percent'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_discount']['percent']);
        $plugin['data']['shop_pref_discount']['percent'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_discount']['percent']), 2);
        $plugin['data']['shop_pref_discount']['amount'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_discount']['amount']);
        $plugin['data']['shop_pref_discount']['amount'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_discount']['amount']), 2);

        $plugin['data']['shop_pref_discount']['percent_1'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_discount']['percent_1']);
        $plugin['data']['shop_pref_discount']['percent_1'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_discount']['percent_1']), 2);
        $plugin['data']['shop_pref_discount']['amount_1'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_discount']['amount_1']);
        $plugin['data']['shop_pref_discount']['amount_1'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_discount']['amount_1']), 2);

        $plugin['data']['shop_pref_discount']['percent_2'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_discount']['percent_2']);
        $plugin['data']['shop_pref_discount']['percent_2'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_discount']['percent_2']), 2);
        $plugin['data']['shop_pref_discount']['amount_2'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_discount']['amount_2']);
        $plugin['data']['shop_pref_discount']['amount_2'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_discount']['amount_2']), 2);

        // Low Order
        $plugin['data']['shop_pref_loworder'] = array(
            'loworder' => empty($_POST['pref_loworder']) ? 0 : 1,
            'under' => clean_slweg($_POST['pref_loworder_under']),
            'charge' => clean_slweg($_POST['pref_loworder_charge']),
            'vat' => clean_slweg($_POST['pref_loworder_vat'])
        );
        $plugin['data']['shop_pref_loworder']['under'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_loworder']['under']);
        $plugin['data']['shop_pref_loworder']['under'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_loworder']['under']), 2);
        $plugin['data']['shop_pref_loworder']['charge'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_loworder']['charge']);
        $plugin['data']['shop_pref_loworder']['charge'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_loworder']['charge']), 2);
        $plugin['data']['shop_pref_loworder']['vat'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_loworder']['vat']);
        $plugin['data']['shop_pref_loworder']['vat'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_loworder']['vat']), 2);

        if (empty($plugin['error'])) {

            _setConfig('shop_pref_currency', $plugin['data']['shop_pref_currency'], 'module_shop');
            _setConfig('shop_pref_unit_weight', $plugin['data']['shop_pref_unit_weight'], 'module_shop');
            _setConfig('shop_pref_vat', $plugin['data']['shop_pref_vat'], 'module_shop');
            _setConfig('shop_pref_email_to', $plugin['data']['shop_pref_email_to'], 'module_shop');
            _setConfig('shop_pref_email_from', $plugin['data']['shop_pref_email_from'], 'module_shop');
            _setConfig('shop_pref_email_paypal', $plugin['data']['shop_pref_email_paypal'], 'module_shop');
            _setConfig('shop_pref_shipping_calc', $plugin['data']['shop_pref_shipping_calc'], 'module_shop');
            _setConfig('shop_pref_shipping_selfpickup', $plugin['data']['shop_pref_shipping_selfpickup'], 'module_shop');
            _setConfig('shop_pref_shipping', $plugin['data']['shop_pref_shipping'], 'module_shop');
            _setConfig('shop_pref_payment', $plugin['data']['shop_pref_payment'], 'module_shop');
            _setConfig('shop_pref_terms', $plugin['data']['shop_pref_terms'], 'module_shop');
            _setConfig('shop_pref_terms_format', $plugin['data']['shop_pref_terms_format'], 'module_shop');
            _setConfig('shop_pref_id_shop', $plugin['data']['shop_pref_id_shop'], 'module_shop');
            _setConfig('shop_pref_id_cart', $plugin['data']['shop_pref_id_cart'], 'module_shop');
            _setConfig('shop_pref_discount', $plugin['data']['shop_pref_discount'], 'module_shop');
            _setConfig('shop_pref_loworder', $plugin['data']['shop_pref_loworder'], 'module_shop');
            _setConfig('shop_pref_felang', $plugin['data']['shop_pref_felang'], 'module_shop');
            _setConfig('shop_pref_zone_base', $plugin['data']['shop_pref_zone_base'], 'module_shop');
            _setConfig('shop_pref_api_access', $plugin['data']['shop_pref_api_access'], 'module_shop');
            _setConfig('shop_pref_api_key', $plugin['data']['shop_pref_api_key'], 'module_shop');
            _setConfig('shop_pref_autosubtract_off', $plugin['data']['shop_pref_autosubtract_off'], 'module_shop');

            // save and back to listing mode
            headerRedirect(shop_url(get_token_get_string() . '&controller=pref', ''));
        }
    }

    $_checkPref_shipping_default = array(
        'weight' => '',
        'net' => 0,
        'vat' => 0,
        'price' => '',
        'price_net' => 0,
        'price_vat' => 0,
        'zone' => '',
        'zone_net' => 0,
        'zone_vat' => 0,
        'zone_label' => ''
    );
    $_checkPref = array(
        'shop_pref_currency' => '',
        'shop_pref_unit_weight' => 'kg',
        'shop_pref_vat' => array('0.00', '7.00', '19.00'),
        'shop_pref_email_to' => '',
        'shop_pref_email_from' => '',
        'shop_pref_email_paypal' => '',
        'shop_pref_id_shop' => 0,
        'shop_pref_id_cart' => 0,
        'shop_pref_felang' => 0,
        'shop_pref_shipping_calc' => 0,
        'shop_pref_shipping_selfpickup' => 0,
        'shop_pref_shipping' => array(
            0 => $_checkPref_shipping_default,
            1 => $_checkPref_shipping_default,
            2 => $_checkPref_shipping_default,
            3 => $_checkPref_shipping_default,
            4 => $_checkPref_shipping_default
        ),
        'shop_pref_zone_base' => '',
        'shop_pref_payment' => array(
            'paypal' => 1,
            'prepay' => 1,
            'pod' => 1,
            'onbill' => 1,
            'ccard' => 1,
            'cash' => 1,
            'accepted_ccard' => array('americanexpress', 'mastercard', 'visa')
        ),
        'shop_pref_terms' => '',
        'shop_pref_terms_format' => 0,
        'shop_pref_discount' => array(
            'discount' => 0, 'percent' => 0, 'amount' => 0, 'freeshipping' => 0,
            'discount_1' => 0, 'percent_1' => 0, 'amount_1' => 0, 'freeshipping_1' => 0,
            'discount_2' => 0, 'percent_2' => 0, 'amount_2' => 0, 'freeshipping_2' => 0,
            'freeshipping_pickup' => 1
        ),
        'shop_pref_loworder' => array('loworder' => 0, 'under' => 0, 'charge' => 0, 'vat' => 0),
        'shop_pref_api_access' => 0,
        'shop_pref_api_key' => '',
        'shop_pref_autosubtract_off' => 0
    );

    // retrieve all settings
    foreach ($_checkPref as $key => $value) {
        if (false === ($plugin['data'][$key] = _getConfig($key))) {
            $plugin['data'][$key] = $value;
            _setConfig($key, $plugin['data'][$key], 'module_shop');
        }
    }
}
