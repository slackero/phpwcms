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

function shop_url($get='', $type='htmlentities') {
	$base = MODULE_HREF;
	if(is_array($get) && count($get)) {
		$get = implode('&', $get);
	} elseif(empty($get)) {
		$get = '';
	}
	if($get) {
		$get = '&'.$get;
	}
	if(empty($type) || $type != 'htmlentities') {
		$base = str_replace('&amp;', '&', MODULE_HREF);
	} else {
		$get = html_entities($get);
	}
	return $base.$get;
}

function roundAll($a) {
	return round(floatval($a), 2);
}

function order_status($is='', $status='') {
	if(strpos(strtoupper($status), strtoupper($is)) !== FALSE) {
		return ' checked="checked"';
	}
	return '';
}

function get_shop_option_value_config() {
	return array(
		'dec_point' => isset($GLOBALS['BLM']['dec_point']) ? $GLOBALS['BLM']['dec_point'] : '.',
		'thousands_sep' => isset($GLOBALS['BLM']['thousands_sep']) ? $GLOBALS['BLM']['thousands_sep'] : ',',
		'null' => 1,
		'prefix' => ', ',
		'suffix' => '' // _getConfig( 'shop_pref_currency' )
	);
}
