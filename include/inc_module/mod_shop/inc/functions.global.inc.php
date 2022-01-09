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

if(!function_exists('dec_num_count')) {
    function dec_num_count($value) {
        if((int)$value == $value) {
            return 0;
        } elseif(!is_numeric($value)) {
            return false;
        }
        return strlen($value) - strrpos($value, '.') - 1;
    }
}

// In case shop module is used in an older release
if(!function_exists('phpwcms_boolval')) {
    function phpwcms_boolval($BOOL, $STRICT=false) {
        return version_compare(PHP_VERSION, '5.5.0', '>=') ? boolval($BOOL) : boolval($BOOL, $STRICT);
    }
}

function get_shop_option_value($option) {

    // first explode: Option|+/-0.00|-ADD
    // [0] string: description
    // [1] float: price to add
    // [2] string: to add to prod#
    $option = explode('|', $option);

    $option[0] = trim($option[0]);
    $option['option'] = ''; // used to return the formatted optional price component

    /**
     * in case there is a price option,
     * '' default price
     *  + add to the default price
     *  - subtract from the default price
     *  = use this as the product price
     */
    $option['type'] = '';

    if(isset($option[1])) {
        if(($option[1] = trim($option[1]))) {
            $config = get_shop_option_value_config();
            $option_sign = substr($option[1], 0, 1);
            if(strpos($option[1], ',')) {
                $option[1] = str_replace(',', '.', str_replace('.', '', $option[1]));
            }
            $option[1] = floatval(preg_replace('/[^0-9\.]/', '', $option[1]));
            $option_enable = true;
            if($option_sign === '-' && $option[1] > 0) {
                $option[1] = $option[1] * -1;
                $option_sign = '';
                $option['type'] = '-';
            } elseif($option[1] == 0) {
                $option_sign = '';
                $option_enable = $config['null'];
            } elseif($option_sign === '=') {
                $option['type'] = '=';
            } else {
                $option_sign = '+';
                $option['type'] = '+';
            }
            if($option_enable && !$config['hide']) {
                $option['option'] .= trim($config['prefix'], '|');
                if ($option_sign && $option_sign !== '=') {
                    $option['option'] .= $option_sign;
                }
                $option['option'] .= number_format($option[1], 2, $config['dec_point'], $config['thousands_sep']);
                $option['option'] .= trim($config['suffix'], '|');
            }
        }
    } else {
        $option[1] = 0;
    }
    $option[2] = isset($option[2]) ? trim($option[2]) : '';

    return $option;
}
