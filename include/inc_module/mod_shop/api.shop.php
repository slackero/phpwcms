<?php
/**
 * Shop API for phpwcms Shop module
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ToDo: Enhance Actions (setstatus)

$phpwcms = array();
$path = dirname(dirname(dirname(dirname(__FILE__))));
$module_path = dirname(__FILE__);
if(is_file($path.'/include/config/conf.inc.php')) {
    require $path.'/include/config/conf.inc.php';
} else {
    require $path.'/config/phpwcms/conf.inc.php';
}
require $path.'/include/inc_lib/default.inc.php';
require PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';
require $module_path.'/inc/functions.global.inc.php';

function get_shop_option_value_config() {
    return array(
        'dec_point' => '.',
        'thousands_sep' => ',',
        'null' => 1,
        'prefix' => ', ',
        'suffix' => ''
    );
}

// define a charset helper here
if(PHPWCMS_CHARSET !== 'utf-8') {
    function _convert_charset($string) {
        return mb_convert_encoding($string, 'utf-8', PHPWCMS_CHARSET);
    }
} else {
    function _convert_charset($string) {
        return $string;
    }
}

// Get API Key and check if API access is enabled
$shop_api_access    = _getConfig('shop_pref_api_access');
$shop_api_key       = _getConfig('shop_pref_api_key');
$shop_api_action    = null;
$shop_api_data  = array(
    'status'        => 'ok',
    'code'          => '',
    'message'       => '',
    'request_mode'  => 'get',
    'result'        => array()
);

if(!empty($_GET['api']) && $_GET['api'] === clean_slweg($shop_api_key)) {
    if(isset($_GET['action'])) {
        $shop_api_action = strtolower(clean_slweg($_GET['action']));
    }
} elseif(!empty($_POST['api']) && $_POST['api'] === clean_slweg($shop_api_key)) {
    $shop_api_data['request_mode'] = 'post';
    if(isset($_POST['action'])) {
        $shop_api_action = strtolower(clean_slweg($_POST['action']));
    }
} else {
    $shop_api_data['status'] = 'error';
    $shop_api_data['code'] = 'api-key-missing';
    $shop_api_data['message'] = 'Set or see the API key setting in the shop preferences.';
}

/**
 * Possible Actions
 * ================
 *
 * getorders
 * Will return all orders with status new (at the moment only)
 *
 * setstatus
 * Set order status of a specific order
 *
 */
if($shop_api_access) {

    if($shop_api_action === 'getorders') {

        $data = _dbGet('phpwcms_shop_orders', '*', "order_status='NEW-ORDER'", '', 'order_date ASC');

        foreach($data as $row) {

            $row['order_data'] = @unserialize($row['order_data']);

            // fallback for additional fields

            $row['order_data']['address'] = array_merge(
                array('INV_SALUTATION' => '', 'INV_TITLE' => '', 'INV_COMPANY' => '', 'INV_ADDRESS2' => ''),
                $row['order_data']['address']
            );

            $row_data = array(

                'id'            => md5($row["order_id"].$row['order_number']),
                'number'        => _convert_charset($row['order_number']),
                'date'          => $row['order_date'],
                'lang'          => empty($row['order_data']['lang']) ? '' : _convert_charset($row['order_data']['lang']),
                'email'         => _convert_charset($row['order_email']),
                'salutation'    => _convert_charset($row['order_data']['address']['INV_SALUTATION']),
                'title'         => _convert_charset($row['order_data']['address']['INV_TITLE']),
                'firstname'     => _convert_charset($row['order_firstname']),
                'name'          => _convert_charset($row['order_name']),
                'company'       => _convert_charset($row['order_data']['address']['INV_COMPANY']),
                'address'       => _convert_charset($row['order_data']['address']['INV_ADDRESS']),
                'address2'      => _convert_charset($row['order_data']['address']['INV_ADDRESS2']),
                'postcode'      => _convert_charset($row['order_data']['address']['INV_ZIP']),
                'city'          => _convert_charset($row['order_data']['address']['INV_CITY']),
                'region'        => _convert_charset($row['order_data']['address']['INV_REGION']),
                'country'       => _convert_charset($row['order_data']['address']['INV_COUNTRY']),
                'phone'         => _convert_charset($row['order_data']['address']['PHONE']),
                'custom_fields' => null,
                'payment'       => _convert_charset(strtolower($row['order_payment'])),
                'total'         => array(
                    'total_net'     => floatval($row['order_net']),
                    'total_vat'     => floatval($row['order_gross']) - floatval($row['order_net']),
                    'total_gross'   => floatval($row['order_gross'])
                ),
                'subtotal'      => isset($row['order_data']['subtotal']) ? $row['order_data']['subtotal'] : null,
                'shipping'      => isset($row['order_data']['shipping']) ? $row['order_data']['shipping'] : null,
                'discount'      => isset($row['order_data']['discount']) ? $row['order_data']['discount'] : null,
                'loworder'      => isset($row['order_data']['loworder']) ? $row['order_data']['loworder'] : null,
                'weight'        => isset($row['order_data']['weight']) ? $row['order_data']['weight'] : null,
                'distance'      => isset($row['order_data']['distance']) ? $row['order_data']['distance'] : null,
                'positions'     => array()
            );

            if($row_data['shipping']) {
                $row_data['shipping']['shipping_vat'] = $row_data['shipping']['shipping_gross'] - $row_data['shipping']['shipping_net'];
            }
            if(isset($row_data['distance']['label'])) {
                foreach($row_data['distance'] as $key => $value) {
                    $row_data['distance'][$key] = _convert_charset($row_data['distance'][$key]);
                }
                $row_data['shipping']['shipping_zone'] = $row_data['distance']['label'];
            }
            if($row_data['subtotal']) {
                $row_data['subtotal']['subtotal_vat'] = $row_data['subtotal']['subtotal_gross'] - $row_data['subtotal']['subtotal_net'];
            }
            if($row_data['discount']) {
                $row_data['discount']['discount_vat'] = $row_data['discount']['discount_gross'] - $row_data['discount']['discount_net'];
            }
            if($row_data['loworder']) {
                $row_data['loworder']['loworder_vat'] = $row_data['loworder']['loworder_gross'] - $row_data['loworder']['loworder_net'];
            }

            // unset default address fields to get custom fields
            unset(
                $row['order_data']['address']['INV_SALUTATION'],
                $row['order_data']['address']['INV_TITLE'],
                $row['order_data']['address']['INV_COMPANY'],
                $row['order_data']['address']['INV_FIRSTNAME'],
                $row['order_data']['address']['INV_NAME'],
                $row['order_data']['address']['INV_ADDRESS'],
                $row['order_data']['address']['INV_ADDRESS2'],
                $row['order_data']['address']['INV_ZIP'],
                $row['order_data']['address']['INV_CITY'],
                $row['order_data']['address']['INV_REGION'],
                $row['order_data']['address']['INV_COUNTRY'],
                $row['order_data']['address']['EMAIL'],
                $row['order_data']['address']['PHONE']
            );

            // get custom fields
            if(count($row['order_data']['address'])) {
                $row_data['custom_fields'] = array();
                foreach($row['order_data']['address'] as $field => $value) {
                    $row_data['custom_fields'][$field] = is_string($value) ? _convert_charset($value) : $value;
                }
            }

            // Loop order items
            foreach($row['order_data']['cart'] as $item) {

                $position = array(
                    'id' => '',
                    'ordernum' => '',
                    'ordernum_basis' => '',
                    'ordernum_opt1' => '',
                    'ordernum_opt2' => '',
                    'title' => _convert_charset($item['shopprod_name1']),
                    'quantity' => 0,
                    'option1' => '',
                    'option2' => '',
                    'weight' => $item['shopprod_weight'],
                    'vat' => $item['shopprod_vat'],
                    'item_net' => 0,
                    'item_vat' => 0,
                    'item_gross' => 0,
                    'rebate' => $item['shopprod_maxrebate'],
                    'pos_net' => 0,
                    'pos_vat' => 0,
                    'pos_gross' => 0
                );

                $vat_factor = 1 + ($position['vat'] / 100);

                if($item['shopprod_size'] && ($_cart_opt_1 = explode(LF, $item['shopprod_size']))) {
                    foreach($_cart_opt_1 as $key => $value){
                        //title
                        if(!$key) {
                            unset($_cart_opt_1[$key]);
                            continue;
                        }
                        $_cart_opt_1[$key] = get_shop_option_value($value);
                    }
                } else {
                    $_cart_opt_1 = null;
                }
                if($item['shopprod_color'] && ($_cart_opt_2 = explode(LF, $item['shopprod_color']))) {
                    foreach($_cart_opt_2 as $key => $value){
                        //title
                        if(!$key) {
                            unset($_cart_opt_2[$key]);
                            continue;
                        }
                        $_cart_opt_2[$key] = get_shop_option_value($value);
                    }
                } else {
                    $_cart_opt_2 = null;
                }

                //loop all opt_1
                if(!isset($item['shopprod_quantity'])) {
                    $item['shopprod_quantity'] = 1;
                }
                if(!is_array($item['shopprod_quantity'])) {
                    $item['shopprod_quantity'] = array(array($item['shopprod_quantity']));
                }

                if($item['shopprod_quantity']) {
                    foreach($item['shopprod_quantity'] as $key => $idval) {

                        foreach($idval as $k => $v) {

                            $position['quantity'] = $v;
                            $value_opt1_float = 0;
                            $value_opt2_float = 0;

                            //opt_1
                            if(isset($_cart_opt_1[$key][1])) {
                                if ($_cart_opt_1[$key]['type'] === '=') {
                                    $item['shopprod_price'] = $_cart_opt_1[$key][1];
                                } else {
                                    $value_opt1_float = $_cart_opt_1[$key][1];
                                }
                                $position['option1'] = _convert_charset($_cart_opt_1[$key]['option']);
                                $position['ordernum_opt1'] = $_cart_opt_1[$key][2];
                            }

                            //opt_2
                            if(isset($_cart_opt_2[$key][1])) {
                                if ($_cart_opt_2[$key]['type'] === '=') {
                                    $item['shopprod_price'] = $_cart_opt_2[$key][1];
                                } else {
                                    $value_opt2_float = $_cart_opt_2[$key][1];
                                }
                                $position['option2'] = _convert_charset($_cart_opt_2[$key]['option']);
                                $position['ordernum_opt2'] = $_cart_opt_2[$key][2];
                            }

                            $pluginshopprod_price = $item['shopprod_price'] + $value_opt1_float + $value_opt2_float;

                            if($item['shopprod_netgross'] == 1) {
                                $position['item_net']   = $pluginshopprod_price / $vat_factor;
                                $position['item_gross'] = $pluginshopprod_price;
                            } else {
                                $position['item_net']   = $pluginshopprod_price;
                                $position['item_gross'] = $pluginshopprod_price * $vat_factor;
                            }
                            $position['item_vat']   = $position['item_gross'] - $position['item_net'];
                            $position['pos_net']    = $v * $position['item_net'];
                            $position['pos_gross']  = $v * $position['item_gross'];
                            $position['pos_vat']    = $position['pos_gross'] - $position['pos_net'];

                            $position['ordernum'] = $item['shopprod_ordernumber'].$position['ordernum_opt1'].$position['ordernum_opt2'];
                            $position['ordernum_basis'] = $item['shopprod_ordernumber'];

                            $position['id'] = md5($item["shopprod_id"].$item['shopprod_ordernumber']);

                            // add item
                            $row_data['positions'][] = $position;

                        }
                    }
                }

            }

            // Add order data
            $shop_api_data['result'][] = $row_data;

        }

    // change order status
    } elseif($shop_api_action === 'setstatus') {


    // No valid
    } else {

        $shop_api_data['status'] = 'error';
        $shop_api_data['code'] = 'api-action-invalid';
        $shop_api_data['message'] = 'The API action \''.($shop_api_action === null ? 'NULL' : $shop_api_action).'\' is not valid. Allowed actions: getOrders, setStatus.';

    }

// API access disabled
} else {

    $shop_api_data['status'] = 'error';
    $shop_api_data['code'] = 'api-access-disabled';
    $shop_api_data['message'] = 'To be able to access the shop API it needs to be enabled in the shop preferences.';

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($shop_api_data);
