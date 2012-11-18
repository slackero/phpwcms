<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2012, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

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
		
		$plugin['data']['shop_pref_felang']				= empty($_POST['pref_felang']) ? 0 : 1;
		
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
			$plugin['data']['shop_pref_shipping'][$x]['net']	= round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_shipping'][$x]['net']), 3);
			
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
		
		// Discount Setting
		$plugin['data']['shop_pref_discount']			= array(
													'discount'			=> empty($_POST['pref_discount']) ? 0 : 1,
													'percent'			=> clean_slweg($_POST['pref_discount_percent'])
																);
		$plugin['data']['shop_pref_discount']['percent'] = str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_discount']['percent']);
		$plugin['data']['shop_pref_discount']['percent'] = round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_discount']['percent']), 2);
		
		
		
		// Low Order
		$plugin['data']['shop_pref_loworder']			= array(
													'loworder'			=> empty($_POST['pref_loworder']) ? 0 : 1,
													'under'				=> clean_slweg($_POST['pref_loworder_under']),
													'charge'			=> clean_slweg($_POST['pref_loworder_charge']),
													'vat'				=> clean_slweg($_POST['pref_loworder_vat'])
																);
		$plugin['data']['shop_pref_loworder']['under']	= str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_loworder']['under']);
		$plugin['data']['shop_pref_loworder']['under']	= round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_loworder']['under']), 2);
		$plugin['data']['shop_pref_loworder']['charge']	= str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_loworder']['charge']);
		$plugin['data']['shop_pref_loworder']['charge']	= round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_loworder']['charge']), 2);
		$plugin['data']['shop_pref_loworder']['vat']	= str_replace($BLM['thousands_sep'], '', $plugin['data']['shop_pref_loworder']['vat']);
		$plugin['data']['shop_pref_loworder']['vat']	= round(str_replace($BLM['dec_point'], '.', $plugin['data']['shop_pref_loworder']['vat']), 2);
		
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
			_setConfig('shop_pref_discount',		$plugin['data']['shop_pref_discount'],		'module_shop');
			_setConfig('shop_pref_loworder',		$plugin['data']['shop_pref_loworder'],		'module_shop');
			_setConfig('shop_pref_felang',			$plugin['data']['shop_pref_felang'],		'module_shop');		
			// save and back to listing mode
			headerRedirect( shop_url('controller=pref', '') );
			
		}

	}
	
	$_checkPref = array(
	
		'shop_pref_currency'		=>	'',
		'shop_pref_unit_weight'		=>	'kg',
		'shop_pref_vat'				=>	array( '0.00', '7.00', '19.00' ),
		'shop_pref_email_to'		=>	'',
		'shop_pref_email_from'		=>	'',
		'shop_pref_email_paypal'	=>	'',
		'shop_pref_id_shop'			=>	0,
		'shop_pref_id_cart'			=>	0,
		'shop_pref_felang'			=>	0,
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
		'shop_pref_terms_format'	=>  0,
		'shop_pref_discount'		=> array('discount' => 0, 'percent' => 0),
		'shop_pref_loworder'		=> array('loworder' => 0, 'under' => 0, 'charge' => 0, 'vat' => 0)
	
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