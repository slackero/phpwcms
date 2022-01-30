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


if($action == 'delete') {

	$plugin['data']['order_id']		= intval($_GET['delete']);

	$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_shop_orders SET ';
	$sql .= "order_status = 'CLOSED' ";
	$sql .= "WHERE order_id = " . $plugin['data']['order_id'];

	_dbQuery($sql, 'UPDATE');

	headerRedirect( shop_url(get_token_get_string().'&controller=order', '') );

} elseif($action == 'show') {

	if(isset($_POST['order_status'])) {

		$plugin['order_status'] = array();

		if(!empty($_POST['status_payment'])) {
			$plugin['order_status'][] = 'PAYED';
		}
		if(!empty($_POST['status_send'])) {
			$plugin['order_status'][] = 'SENT';
		}
		if(!empty($_POST['status_back'])) {
			$plugin['order_status'][] = 'RETURN';
		}
		if(!empty($_POST['status_done'])) {
			$plugin['order_status'][] = 'COMPLETED';
		}

		$plugin['order_status'] = implode('-', $plugin['order_status']);
		if($plugin['order_status'] == '') {
			$plugin['order_status'] = 'NEW-ORDER';
		}
		$sql  = 'UPDATE '.DB_PREPEND."phpwcms_shop_orders SET order_status='".aporeplace($plugin['order_status'])."' ";
		$sql .= "WHERE order_id=" . intval($_POST['order_status']);

		if( _dbQuery($sql, 'UPDATE') ) {
			set_status_message($BLM['shopprod_status_msg'], 'success');
		}
	}

	$sql  = 'SELECT *, UNIX_TIMESTAMP(order_date) AS order_date_unix FROM '.DB_PREPEND.'phpwcms_shop_orders ';
	$sql .= "WHERE order_id = " . intval($_GET['show']);

	$plugin['data'] = _dbQuery($sql);

	if(isset($plugin['data'][0])) {

		$plugin['data'] = $plugin['data'][0];
		$plugin['data']['order_data'] = @unserialize($plugin['data']['order_data']);

	} else {

		headerRedirect( shop_url(get_token_get_string().'&controller=order', '') );

	}

	$BLM['shopprod_payby_INVOICE'] = $BLM['shopprod_payby_onbill'];

}
