<?php

if(strpos($content['all'], '{PHPWCMS-ADDRESS-LOCATOR}')) {

	function get_splitted_email_jslink($email) {

		$email = str_replace('.', "'+'.'+'", $email);
		$email = str_replace('@', "','", $email);
		$email = "'".$email."'";

		return ' onclick="return jsm('.$email.');"';
	}

	define('PHPWCMS_ADDRESS_KEY', 'PHPWCMS-ADDRESS');
	define('PHPWCMS_CONTACTS_KEY', 'PHPWCMS-CONTACT');
	include_once($phpwcms['modules']['phpwcms_addresses']['path'].'inc/conf.inc.php');
	include_once($phpwcms['modules']['phpwcms_addresses']['path'].'inc/search.addresses.php');

}
