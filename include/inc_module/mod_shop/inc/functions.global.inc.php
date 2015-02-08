<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

function get_shop_option_value($option) {

	// first explode: Option|+/-0.00|-ADD
	// [0] string: description
	// [1] float: price to add
	// [2] string: to add to prod#
	$option = explode('|', $option);

	$option[0] = trim($option[0]);
	$option['option'] = ''; // used to return the formatted optional price component
	$option_sign = '';

	if(isset($option[1])) {
		$option[1] = trim($option[1]);
		if($option[1]) {
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
			} elseif($option[1] == 0) {
				$option_sign = '';
				$option_enable = $config['null'];
			} else {
				$option_sign = '+';
			}
			if($option_enable) {
				$option['option'] .= trim($config['prefix'], '|');
				$option['option'] .= $option_sign;
				$option['option'] .= number_format($option[1], 2, $config['dec_point'], $config['thousands_sep']);
				$option['option'] .= trim($config['suffix'], '|');
			}
		}
	} else {
		$option[1] = '';
	}
	if(isset($option[2])) {
		$option[2] = trim($option[2]);
	}

	return $option;
}


?>