<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
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


//check email 1st
$_POST['subscribe_email']							= strtolower(clean_slweg($_POST['subscribe_email']));
$_userInfo['error']['email'] 						= 1;

if(is_valid_email($_POST['subscribe_email'])) {

	$_userInfo['subscriber_data']['address_email']	= $_POST['subscribe_email'];
	$_userInfo['error']['email']					= 0;

} elseif($_POST['subscribe_email']) {

	$_userInfo['subscriber_data']['address_email']	= $_POST['subscribe_email'];

}

// user name
$_userInfo['subscriber_data']['address_name']		= clean_slweg($_POST['subscribe_name']);

// verification
$_userInfo['subscriber_data']['address_verified']	= empty($_POST['subscribe_active']) ? 0 : 1;

// now run through subscriptions
if(empty($_POST['subscribe_all']) && !empty($_POST['subscribe_to']) && is_array($_POST['subscribe_to']) && count($_POST['subscribe_to'])) {

	// check special subscriptions
	$_userInfo['subscriber_data']['address_subscription'] = array();
	foreach($_POST['subscribe_to'] as $subscriptions) {

		$subscription = intval($subscriptions);
		if($subscription) {
			$_userInfo['subscriber_data']['address_subscription'][$subscription] = $subscription;
		}

	}
	if(count($_userInfo['subscriber_data']['address_subscription'])) {
		$_userInfo['subscriber_data']['address_subscription'] = serialize($_userInfo['subscriber_data']['address_subscription']);
	} else {
		$_userInfo['subscriber_data']['address_subscription'] = '';
	}

} else {

	// means: all subscriptions
	$_userInfo['subscriber_data']['address_subscription'] = '';

}

// OK lets insert or update
if($_userInfo['error']['email'] == 0) {

	// check if update neccessary in case email still exists
	$sql  = "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_address ";
	$sql .= "WHERE address_email='".aporeplace($_userInfo['subscriber_data']['address_email'])."'";
	if($_userInfo['subscriber_data']['address_id']) {
		$sql .= " AND address_id != ".$_userInfo['subscriber_data']['address_id'];
	}
	$_userInfo['count'] = _dbQuery($sql, 'COUNT');

	if($_userInfo['subscriber_data']['address_id'] || $_userInfo['count']) {

		// update
		$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_address SET ';
		$sql .= "address_email			= '".aporeplace($_userInfo['subscriber_data']['address_email'])."', ";
		$sql .= "address_name			= '".aporeplace($_userInfo['subscriber_data']['address_name'])."', ";
		$sql .= "address_verified		= ".$_userInfo['subscriber_data']['address_verified'].", ";
		$sql .= "address_subscription	= '".aporeplace($_userInfo['subscriber_data']['address_subscription'])."' ";
		$sql .= 'WHERE ';
		if($_userInfo['count']) {
			// update based on email address
			$sql .= "address_email='".aporeplace($_userInfo['subscriber_data']['address_email'])."'";
		} else {
			// update based on email address
			$sql .= 'address_id='.$_userInfo['subscriber_data']['address_id'];
			$sql .= ' LIMIT 1';
		}

		_dbQuery($sql, 'UPDATE');

	} else {

		// insert
		$sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_address ';
		$sql .= '(address_key, address_email, address_name, address_verified, address_subscription) VALUES (';
		$sql .= "'".aporeplace( shortHash( $_userInfo['subscriber_data']['address_email'] . time() ) )."', ";
		$sql .= "'".aporeplace($_userInfo['subscriber_data']['address_email'])."', ";
		$sql .= "'".aporeplace($_userInfo['subscriber_data']['address_name'])."', ";
		$sql .= $_userInfo['subscriber_data']['address_verified'].", ";
		$sql .= "'".aporeplace($_userInfo['subscriber_data']['address_subscription'])."')";

		$_userInfo['result'] = _dbQuery($sql, 'INSERT');
		if(!empty($_userInfo['result']['INSERT_ID'])) {
			$_userInfo['subscriber_id'] 				= $_userInfo['result']['INSERT_ID'];
			$_userInfo['subscriber_data']['address_id']	= $_userInfo['result']['INSERT_ID'];
		}

	}

}

// in case data should be saved and closed then
if($_userInfo['error']['email'] == 0 && (!empty($_POST['save']) || !empty($_userInfo['count']))) {

	$_userInfo['subscriber_data'] = false;

}
