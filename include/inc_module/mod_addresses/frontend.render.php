<?php

if(strpos($content['all'], '{CMSGO-ADDRESS-LOCATOR}')) {

	function get_splitted_email_jslink($email) {

		$email = str_replace('.', "'+'.'+'", $email);
		$email = str_replace('@', "','", $email);
		$email = "'".$email."'";

		return ' onclick="return jsm('.$email.');"';
	}

	define('CMSGO_ADDRESS_KEY', 'CMSGO-ADDRESS');
	define('CMSGO_CONTACTS_KEY', 'CMSGO-CONTACT');
	include_once($cmsgo['modules']['cmsgo_addresses']['path'].'inc/conf.inc.php');
	include_once($cmsgo['modules']['cmsgo_addresses']['path'].'inc/search.addresses.php');

}
