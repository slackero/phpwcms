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


function shop_url($get='', $type='htmlentities') {
	$base = MODULE_HREF;
	if(is_array($get) && count($get)) {
		$get = implode('&', $get);
	} elseif(empty($get)) {
		$get = '';
	}
	if($get) $get = '&'.$get;
	if(empty($type) || $type != 'htmlentities') {
		$base = str_replace('&amp;', '&', MODULE_HREF);
	} else {
		$get = html_entities($get);
	}
	return $base.$get;
}

function roundAll($a) {
	$a = floatval($a);
	return round($a, 2);
}

function order_status($is='', $status='') {
	$is		= strtoupper($is);
	$status	= strtoupper($status);
	if(strpos($status, $is) !== FALSE) {
		return ' checked="checked"';
	}
	return '';
}

?>