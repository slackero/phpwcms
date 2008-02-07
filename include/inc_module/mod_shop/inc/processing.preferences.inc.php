<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


if($action == 'edit') {

	if( isset($_POST['save']) ) {
	
		$plugin['data']['shop_pref_currency']			= clean_slweg($_POST['pref_currency']);
		$plugin['data']['shop_pref_unit_weight']		= clean_slweg($_POST['pref_unit_weight']);
		
		$plugin['data']['shop_pref_terms']				= slweg($_POST['pref_terms']);
		$plugin['data']['shop_pref_terms_format']		= empty($_POST['pref_terms_format']) ? 0 : 1;
		
		$plugin['data']['shop_pref_id_shop']			= slweg($_POST['pref_shop_id']);
		$plugin['data']['shop_pref_id_cart']			= slweg($_POST['pref_cart_id']);
		
		$plugin['data']['shop_pref_vat']				= clean_slweg($_POST['pref_vat']);
		$plugin['data']['shop_pref_vat']				= str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_vat']);
		$plugin['data']['shop_pref_vat']				= str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_vat']);
		$plugin['data']['shop_pref_vat']				= explode(LF, $plugin['data']['shop_pref_vat']);
		$plugin['data']['shop_pref_vat']				= array_map('roundAll', $plugin['data']['shop_pref_vat']);
		natsort($plugin['data']['shop_pref_vat']);
		$plugin['data']['shop_pref_vat']				= array_unique($plugin['data']['shop_pref_vat']);
		
		$plugin['data']['shop_pref_email_to']			= convertStringToArray( sanitize_multiple_emails( clean_slweg($_POST['pref_email_to']) ), ';');
		$plugin['data']['shop_pref_email_from']			= clean_slweg($_POST['pref_email_from']);
		$plugin['data']['shop_pref_email_paypal']		= clean_slweg($_POST['pref_email_paypal']);
		
		// check if multiple emails
		foreach( $plugin['data']['shop_pref_email_to'] as $key => $value ) {
			if(! is_valid_email($value) ) {
				unset( $plugin['data']['shop_pref_email_to'][$key] );
			}
		}
		$plugin['data']['shop_pref_email_to']			= strtolower( implode(';', $plugin['data']['shop_pref_email_to'] ) );
		
		if(! is_valid_email($plugin['data']['shop_pref_email_from']) )		$plugin['data']['shop_pref_email_from']		= '';
		if(! is_valid_email($plugin['data']['shop_pref_email_paypal']) )	$plugin['data']['shop_pref_email_paypal']	= '';
		
		for( $x = 0; $x <= 4; $x++ ) {
		
			$plugin['data']['shop_pref_shipping'][$x]['weight']	= clean_slweg($_POST['pref_shipping_weight'][$x]);
			$plugin['data']['shop_pref_shipping'][$x]['net']	= clean_slweg($_POST['pref_shipping_net'][$x]);
			$plugin['data']['shop_pref_shipping'][$x]['vat']	= clean_slweg($_POST['pref_shipping_vat'][$x]);
			
			$plugin['data']['shop_pref_shipping'][$x]['weight']	= str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_shipping'][$x]['weight']);
			$plugin['data']['shop_pref_shipping'][$x]['weight']	= round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['weight']), 3);
			
			$plugin['data']['shop_pref_shipping'][$x]['net']	= str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_shipping'][$x]['net']);
			$plugin['data']['shop_pref_shipping'][$x]['net']	= round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['net']), 2);
			
			$plugin['data']['shop_pref_shipping'][$x]['vat']	= str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_shipping'][$x]['vat']);
			$plugin['data']['shop_pref_shipping'][$x]['vat']	= round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['vat']), 2);
		
		}
		
		
		$plugin['data']['shop_pref_payment']			= array(
													'paypal'			=> empty($_POST['pref_payment_paypal']) ? 0 : 1,
													'prepay'			=> empty($_POST['pref_payment_prepay']) ? 0 : 1,
													'pod'				=> empty($_POST['pref_payment_pod']) ? 0 : 1,
													'onbill'			=> empty($_POST['pref_payment_onbill']) ? 0 : 1,
													'ccard'				=> empty($_POST['pref_payment_ccard']) ? 0 : 1,
													'accepted_ccard'	=> ( is_array($_POST['pref_supported_ccard']) ? $_POST['pref_supported_ccard'] : array() )
																);
		
		
		
		if( empty($plugin['error'] )) {

			_setConfig('shop_pref_currency', 		$plugin['data']['shop_pref_currency'], 		'module_shop');
			_setConfig('shop_pref_unit_weight', 	$plugin['data']['shop_pref_unit_weight'], 	'module_shop');
			_setConfig('shop_pref_vat', 			$plugin['data']['shop_pref_vat'], 			'module_shop');
			_setConfig('shop_pref_email_to', 		$plugin['data']['shop_pref_email_to'], 		'module_shop');
			_setConfig('shop_pref_email_from', 		$plugin['data']['shop_pref_email_from'], 	'module_shop');
			_setConfig('shop_pref_email_paypal', 	$plugin['data']['shop_pref_email_paypal'], 	'module_shop');
			_setConfig('shop_pref_shipping', 		$plugin['data']['shop_pref_shipping'], 		'module_shop');
			_setConfig('shop_pref_payment', 		$plugin['data']['shop_pref_payment'], 		'module_shop');
			_setConfig('shop_pref_terms', 			$plugin['data']['shop_pref_terms'], 		'module_shop');
			_setConfig('shop_pref_terms_format',	$plugin['data']['shop_pref_terms_format'],	'module_shop');
			_setConfig('shop_pref_id_shop',			$plugin['data']['shop_pref_id_shop'],		'module_shop');
			_setConfig('shop_pref_id_cart',			$plugin['data']['shop_pref_id_cart'],		'module_shop');
		
			// save and back to listing mode
			headerRedirect( shop_url('controller=pref', '') );
			
		}

	}
	
	$_checkPref = array(
	
		'shop_pref_currency'		=>	'€',
		'shop_pref_unit_weight'		=>	'kg',
		'shop_pref_vat'				=>	array( '0.00', '7.00', '19.00' ),
		'shop_pref_email_to'		=>	'',
		'shop_pref_email_from'		=>	'',
		'shop_pref_email_paypal'	=>	'',
		'shop_pref_id_shop'			=>	0,
		'shop_pref_id_cart'			=>	0,
		'shop_pref_shipping'		=>	array(	0 => array('weight'=>'50', 'net'=>0, 'vat'=>0), 
												1 => array('weight'=>'', 'net'=>0, 'vat'=>0), 
												2 => array('weight'=>'', 'net'=>0, 'vat'=>0),
												3 => array('weight'=>'', 'net'=>0, 'vat'=>0),
												4 => array('weight'=>'', 'net'=>0, 'vat'=>0)
											 ),
		'shop_pref_payment'			=>	array(	'paypal' => 1, 
												'prepay'=> 1, 
												'pod' => 1, 
												'onbill' => 1, 
												'ccard' => 1, 
												'accepted_ccard' => array('americanexpress', 'mastercard', 'visa')
											 ),
		'shop_pref_terms'			=>	'',
		'shop_pref_terms_format'	=>  0
	
				);

	// retrieve all settings
	foreach( $_checkPref as $key => $value ) {
		if( false === ( $plugin['data'][ $key ] = _getConfig( $key ) ) ) {
			$plugin['data'][ $key ] = $value;
			_setConfig( $key , $plugin['data'][ $key ], 'module_shop');
		}
	}

}


?>