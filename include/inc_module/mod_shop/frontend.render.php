<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// Module/Plug-in Shop & Products

$_shop_load_cat  		= strpos($content['all'], '{SHOP_CATEGOR');
$_shop_load_list 		= strpos($content['all'], '{SHOP_PRODUCTLIST}');
$_shop_load_cart_small	= strpos($content['all'], '{CART_SMALL}');
$_shop_load_order		= strpos($content['all'], '{SHOP_ORDER_PROCESS}');


// set CART session value
if(!isset($_SESSION['shopping_cart'])) {
	$_SESSION['shopping_cart'] = array();
}


if( $_shop_load_cat !== false || $_shop_load_list !== false || $_shop_load_order !== false || $_shop_load_cart_small !== false) {

	// load template
	$_tmpl = array( 'config' => array(), 'source' => '' );
	$_tmpl['source'] = @file_get_contents($phpwcms['modules']['shop']['path'].'template/default/default.html');
	if($_tmpl['source'])
	{
		$_tmpl['config'] = parse_ini_str(get_tmpl_section('CONFIG', $_tmpl['source']), false);
		
		$_tmpl['config']['cat_list_products']		= empty($_tmpl['config']['cat_list_products']) ? false : boolval($_tmpl['config']['cat_list_products']);
		$_tmpl['config']['image_list_lightbox']		= empty($_tmpl['config']['image_list_lightbox']) ? false : boolval($_tmpl['config']['image_list_lightbox']);
		$_tmpl['config']['image_detail_lightbox']	= empty($_tmpl['config']['image_detail_lightbox']) ? false : boolval($_tmpl['config']['image_detail_lightbox']);
		$_tmpl['config']['image_detail_crop']		= empty($_tmpl['config']['image_detail_crop']) ? false : boolval($_tmpl['config']['image_detail_crop']);
		$_tmpl['config']['image_list_crop']			= empty($_tmpl['config']['image_list_crop']) ? false : boolval($_tmpl['config']['image_list_crop']);
		
		// handle custom fields
		$_tmpl['config']['shop_field'] = array();
		$custom_field_number = 1;
		while( !empty( $_tmpl['config']['shop_field_' . $custom_field_number] ) ) {
		
			$custom_field_type = explode('_', trim($_tmpl['config']['shop_field_' . $custom_field_number]) );
			if($custom_field_type[0] === 'STRING' || $custom_field_type[0] === 'TEXTAREA') {
				$_tmpl['config']['shop_field'][ $custom_field_number ]['type'] = $custom_field_type[0];
				if(isset($custom_field_type[1]) && $custom_field_type[1] == 'REQ') {
					$_tmpl['config']['shop_field'][ $custom_field_number ]['required'] = true;
					if(empty($custom_field_type[2])) {
						$_tmpl['config']['shop_field'][ $custom_field_number ]['label'] = 'Custom '.$custom_field_number;
					} else {
						$_tmpl['config']['shop_field'][ $custom_field_number ]['label'] = trim($custom_field_type[2]);
					}
				} elseif(empty($custom_field_type[1])) {
					$_tmpl['config']['shop_field'][ $custom_field_number ]['required'] = false;
					$_tmpl['config']['shop_field'][ $custom_field_number ]['label'] = 'Custom '.$custom_field_number;
				} else {
					$_tmpl['config']['shop_field'][ $custom_field_number ]['required'] = false;
					$_tmpl['config']['shop_field'][ $custom_field_number ]['label'] = trim($custom_field_type[1]);
				}
			}
			$custom_field_number++;
		}
	
		if($_shop_load_list) {
			$_tmpl['list_header']	= get_tmpl_section('LIST_HEADER',	$_tmpl['source']);
			$_tmpl['list_entry']	= get_tmpl_section('LIST_ENTRY',	$_tmpl['source']);
			$_tmpl['list_space']	= get_tmpl_section('LIST_SPACE',	$_tmpl['source']);
			$_tmpl['list_none']		= get_tmpl_section('LIST_NONE',		$_tmpl['source']);
			$_tmpl['list_footer']	= get_tmpl_section('LIST_FOOTER',	$_tmpl['source']);
			$_tmpl['detail']		= get_tmpl_section('DETAIL',		$_tmpl['source']);
			$_tmpl['image_space']	= get_tmpl_section('IMAGE_SPACE',	$_tmpl['source']);
		}
		
		if($_shop_load_cart_small) {	
			$_tmpl['cart_small']	= get_tmpl_section('CART_SMALL',	$_tmpl['source']);
		}
		
		if($_shop_load_order) {	
			$_tmpl['cart_header']	= get_tmpl_section('CART_HEADER',			$_tmpl['source']);
			$_tmpl['cart_entry']	= get_tmpl_section('CART_ENTRY',			$_tmpl['source']);
			$_tmpl['cart_space']	= get_tmpl_section('CART_SPACE',			$_tmpl['source']);
			$_tmpl['cart_footer']	= get_tmpl_section('CART_FOOTER',			$_tmpl['source']);
			$_tmpl['cart_none']		= get_tmpl_section('CART_NONE',				$_tmpl['source']);
			$_tmpl['inv_address']	= get_tmpl_section('ORDER_INV_ADDRESS',		$_tmpl['source']);
			$_tmpl['order_terms']	= get_tmpl_section('ORDER_TERMS',			$_tmpl['source']);
			$_tmpl['term_entry']	= get_tmpl_section('ORDER_TERMS_ITEM',		$_tmpl['source']);
			$_tmpl['term_space']	= get_tmpl_section('ORDER_TERMS_ITEMSPACE',	$_tmpl['source']);
			$_tmpl['mail_customer']	= get_tmpl_section('MAIL_CUSTOMER',			$_tmpl['source']);
			$_tmpl['mail_neworder']	= get_tmpl_section('MAIL_NEWORDER',			$_tmpl['source']);
			$_tmpl['order_success']	= get_tmpl_section('ORDER_DONE',			$_tmpl['source']);
			$_tmpl['order_failed']	= get_tmpl_section('ORDER_NOT_DONE',		$_tmpl['source']);
			$_tmpl['mail_item']		= get_tmpl_section('MAIL_ITEM',				$_tmpl['source']);
		}
	}
	
	// merge config settings like translations and so on	
	$_tmpl['config'] = array_merge(	array(
							'cat_all'				=> '@@All products@@',
							'cat_list_products'		=> false,
							'price_decimals'		=> 2,
							'vat_decimals'			=> 0,
							'weight_decimals'		=> 0,
							'dec_point'				=> ".",
							'thousands_sep'			=> ",",
							'image_list_width'		=> 200,
							'image_list_height'		=> 200,
							'image_detail_width'	=> 200,
							'image_detail_height'	=> 200,
							'image_zoom_width'		=> 750,
							'image_zoom_height'		=> 500,
							'image_list_lightbox'	=> false,
							'image_detail_lightbox'	=> true,
							'image_detail_crop'		=> false,
							'image_list_crop'		=> false,
							'mail_customer_subject'	=> "[#{ORDER}] Your order at MyShop",
							'mail_neworder_subject'	=> "[#{ORDER}] New order",
							'label_payby_prepay'	=> "@@Cash with order@@",
							'label_payby_pod'		=> "@@Cash on delivery@@",
							'label_payby_onbill'	=> "@@On account@@",
							'order_number_style'	=> 'RANDOM',
							'cat_list_sort_by'		=> 'shopprod_name1 ASC'
						),	$_tmpl['config'] );
	
	// set preferences
	$_shopPref = array();
	foreach( array( 'shop_pref_currency', 'shop_pref_unit_weight', 'shop_pref_vat', 'shop_pref_email_to', 
					'shop_pref_email_from', 'shop_pref_email_paypal', 'shop_pref_shipping', 
					'shop_pref_payment' ) as $value ) {
		_getConfig( $value, '_shopPref' );
	}

	$_tmpl['config']['shop_url'] = _getConfig( 'shop_pref_id_shop', '_shopPref' );
	$_tmpl['config']['cart_url'] = _getConfig( 'shop_pref_id_cart', '_shopPref' );
	
	if(!is_numeric($_tmpl['config']['shop_url']) && is_string($_tmpl['config']['shop_url'])) {
		$_tmpl['config']['shop_url']	= trim($_tmpl['config']['shop_url']);
	} elseif(is_numeric($_tmpl['config']['shop_url']) && intval($_tmpl['config']['shop_url'])) {
		$_tmpl['config']['shop_url']	= 'aid='.intval($_tmpl['config']['shop_url']);
	} else {
		$_tmpl['config']['shop_url']	= $aktion[1] ? 'aid='.$aktion[1] : 'id='.$aktion[0];
	}
	
	if(!is_numeric($_tmpl['config']['cart_url']) && is_string($_tmpl['config']['cart_url'])) {
		$_tmpl['config']['cart_url']	= trim($_tmpl['config']['cart_url']);
	} elseif(is_numeric($_tmpl['config']['cart_url']) && intval($_tmpl['config']['cart_url'])) {
		$_tmpl['config']['cart_url']	= 'aid='.intval($_tmpl['config']['cart_url']);
	} else {
		$_tmpl['config']['cart_url']	= $aktion[1] ? 'aid='.$aktion[1] : 'id='.$aktion[0];
	}
	
	$_tmpl['config']['shop_url'] = 'index.php?' . $_tmpl['config']['shop_url'];
	$_tmpl['config']['cart_url'] = 'index.php?' . $_tmpl['config']['cart_url'];
	
	
	// OK get cart post data
	if( isset($_POST['shop_action']) ) {
	
		switch($_POST['shop_action']) {
		
			case 'add':		$shop_prod_id		= intval($_POST['shop_prod_id']);
							$shop_prod_amount	= abs( intval($_POST['shop_prod_amount']) );
							if(empty($shop_prod_id) || empty($shop_prod_amount)) break; // leave
							
							// add product to shopping 
							if(isset($_SESSION['shopping_cart']['products'][$shop_prod_id])) {
								$_SESSION['shopping_cart']['products'][$shop_prod_id] += $shop_prod_amount;
							} else {
								$_SESSION['shopping_cart']['products'][$shop_prod_id]  = $shop_prod_amount;
							}
							
							break;
		
		}
	
	} elseif( isset($_POST['shop_prod_amount']) && is_array($_POST['shop_prod_amount']) ) {
	
		foreach($_POST['shop_prod_amount'] as $prod_id => $prod_qty) {
		
			$prod_id  = intval($prod_id);
			$prod_qty = abs( intval($prod_qty) );
			if(isset($_SESSION['shopping_cart']['products'][$prod_id])) {
				if($prod_qty) {
					$_SESSION['shopping_cart']['products'][$prod_id] = $prod_qty;
				} else {
					unset($_SESSION['shopping_cart']['products'][$prod_id]);
				}
			}		
		}
	
	} elseif( isset($_POST['shop_order_step1']) ) {
	
		// handle invoice address -> checkout
		
		$_SESSION['shopping_cart']['step1'] = array(
	
			'INV_FIRSTNAME'	=> isset($_POST['shop_inv_firstname']) ? clean_slweg($_POST['shop_inv_firstname']) : '',
			'INV_NAME'		=> isset($_POST['shop_inv_name']) ? clean_slweg($_POST['shop_inv_name']) : '',
			'INV_ADDRESS'	=> isset($_POST['shop_inv_address']) ? clean_slweg($_POST['shop_inv_address']) : '',
			'INV_ZIP'		=> isset($_POST['shop_inv_zip']) ? clean_slweg($_POST['shop_inv_zip']) : '',
			'INV_CITY'		=> isset($_POST['shop_inv_city']) ? clean_slweg($_POST['shop_inv_city']) : '',
			'INV_REGION'	=> isset($_POST['shop_inv_region']) ? clean_slweg($_POST['shop_inv_region']) : '',
			'INV_COUNTRY'	=> isset($_POST['shop_inv_country']) ? clean_slweg($_POST['shop_inv_country']) : '',
			'EMAIL'			=> isset($_POST['shop_email']) ? clean_slweg($_POST['shop_email']) : '',
			'PHONE'			=> isset($_POST['shop_phone']) ? clean_slweg($_POST['shop_phone']) : ''
					
					);
		
		// retrieve all custom field POST data
		foreach($_tmpl['config']['shop_field'] as $key => $row) {
			
			$_SESSION['shopping_cart']['step1']['shop_field_'.$key] = empty($_POST['shop_field_'.$key]) ? '' : clean_slweg($_POST['shop_field_'.$key]);
			if($row['required'] && $_SESSION['shopping_cart']['step1']['shop_field_'.$key] === '') {
				$ERROR['inv_address']['shop_field_'.$key] = $row['required'] . ' must be filled';
			}		
		}
		
		$payment_options = get_payment_options();
		if(!empty($_POST['shopping_payment']) && isset($payment_options[$_POST['shopping_payment']])) {
			$_SESSION['shopping_cart']['payby'] = $_POST['shopping_payment'];
		} else {
			$ERROR['inv_address']['payment'] = true;
		}
		
		if(empty($_SESSION['shopping_cart']['step1']['INV_FIRSTNAME'])) {
			$ERROR['inv_address']['INV_FIRSTNAME'] = '@@First name must be filled@@';
		}
		if(empty($_SESSION['shopping_cart']['step1']['INV_NAME'])) {
			$ERROR['inv_address']['INV_NAME'] = '@@Name must be filled@@';
		}
		if(empty($_SESSION['shopping_cart']['step1']['INV_ADDRESS'])) {
			$ERROR['inv_address']['INV_ADDRESS'] = '@@Address must be filled@@';
		}
		if(empty($_SESSION['shopping_cart']['step1']['INV_ZIP'])) {
			$ERROR['inv_address']['INV_ZIP'] = '@@ZIP must be filled@@';
		}
		if(empty($_SESSION['shopping_cart']['step1']['INV_CITY'])) {
			$ERROR['inv_address']['INV_CITY'] = '@@City must be filled@@';
		}
		if(empty($_SESSION['shopping_cart']['step1']['EMAIL']) || !is_valid_email($_SESSION['shopping_cart']['step1']['EMAIL'])) {
			$ERROR['inv_address']['EMAIL'] = '@@Email must be filled or is invalid@@';
		}
		if(empty($_SESSION['shopping_cart']['step1']['PHONE'])) {
			$ERROR['inv_address']['PHONE'] = '@@Phone must be filled@@';
		}
		if(isset($ERROR['inv_address']) && count($ERROR['inv_address'])) {
			$_SESSION['shopping_cart']['error']['step1'] = true;
		} elseif(isset($_SESSION['shopping_cart']['error']['step1'])) {
			unset($_SESSION['shopping_cart']['error']['step1']);
		}

	
	
	} elseif( isset($_POST['shop_order_submit']) ) {
	
		if(empty($_POST['shop_terms_agree'])) {
			$_SESSION['shopping_cart']['error']['step2'] = true;
		} elseif(isset($_SESSION['shopping_cart']['error']['step2'])) {
			unset($_SESSION['shopping_cart']['error']['step2']);
		}
		
	} elseif( isset($_SESSION['shopping_cart']['error']['step2']) && !isset($_POST['shop_order_submit'])) {
	
		unset($_SESSION['shopping_cart']['error']['step2']);
	
	}

}


// first we take categories
if( $_shop_load_cat !== false ) {

	preg_match('/\{SHOP_CATEGORY:(\d+)\}/', $content['all'], $catmatch);
	if(!empty($catmatch[1])) {
		$shop_limited_cat = true;
		$shop_limited_catid = intval($catmatch[1]);
		if(empty($GLOBALS['_getVar']['shop_cat'])) {
			$GLOBALS['_getVar']['shop_cat'] = $shop_limited_catid;
		}
	} else {
		$shop_limited_cat = false;
	}
	

	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_categories WHERE ';
	$sql .= "cat_type='module_shop' AND cat_status=1 AND cat_pid=0 ";
	if($shop_limited_cat) {
		$sql .= 'AND cat_id = ' . $shop_limited_catid . ' ';
	}
	$sql .= 'ORDER BY cat_name ASC';
	$data = _dbQuery($sql);
	
	$shop_cat = array();
	
	$shop_cat_selected	= isset($GLOBALS['_getVar']['shop_cat']) ? $GLOBALS['_getVar']['shop_cat'] : 'all';
	if(strpos($shop_cat_selected, '_')) {
		$shop_cat_selected = explode('_', $shop_cat_selected, 2);
		if(isset($shop_cat_selected[1])) {
			$shop_subcat_selected	= intval($shop_cat_selected[1]);
		}
		$shop_cat_selected = intval($shop_cat_selected[0]);
		if(!$shop_cat_selected) {
			$shop_cat_selected		= 'all';
			$shop_subcat_selected	= 0;
		}
	} else {
		$shop_subcat_selected = 0;
	}
	
	
	$shop_detail_id		= isset($GLOBALS['_getVar']['shop_detail']) ? intval($GLOBALS['_getVar']['shop_detail']) : 0;
	unset($GLOBALS['_getVar']['shop_cat'], $GLOBALS['_getVar']['shop_detail']);

	$shop_cat_link  = $_tmpl['config']['shop_url'];
	
	if($shop_detail_id) {
		$GLOBALS['_getVar']['shop_detail'] = $shop_detail_id;
	}
	
	if(is_array($data) && count($data)) {

		$x = 0;
	
		foreach($data as $row) {
		
			if($shop_limited_cat && $row['cat_id'] != $shop_limited_catid) {
				continue;
			}
			
			$shop_cat_prods = '';
			$shop_cat[$x]   = '<li';
			if($row['cat_id'] == $shop_cat_selected) {
				$shop_cat[$x] .= ' class="active"';
				
				// now try to retrieve sub categories for active category
				$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_categories WHERE ';
				$sql .= "cat_type='module_shop' AND cat_status=1 AND cat_pid=" . $shop_cat_selected ;
				$sql .= ' ORDER BY cat_name ASC';
				$sdata = _dbQuery($sql);
				
				$subcat_count = count($sdata);
				
				$selected_product_cat = $subcat_count && $shop_subcat_selected ? $shop_subcat_selected : $shop_cat_selected;
				
				if($subcat_count) {
					
					$shop_subcat = array();
					$z = 0;
					foreach($sdata as $srow) {
					
						$shop_subcat[$z]   = '<li';
						if($srow['cat_id'] == $shop_subcat_selected) {
							$shop_subcat[$z] .= ' class="active"';	
						}
						$shop_subcat[$z]  .= '>';
						$shop_subcat[$z] .= '<a href="' . $shop_cat_link . '&amp;shop_cat=' . $srow['cat_pid'] . '_' . $srow['cat_id'] . '">';
						$shop_subcat[$z] .= html_specialchars($srow['cat_name']);
						$shop_subcat[$z] .= '</a>';
						if($srow['cat_id'] == $shop_subcat_selected && $_tmpl['config']['cat_list_products']) {
							$shop_subcat[$z] .= get_category_products($srow['cat_id'], $shop_detail_id, $shop_cat_selected, $shop_subcat_selected, $shop_cat_link);
						}
						$shop_subcat[$z] .= '</li>';
						
						$z++;
					}
					
					if(count($shop_subcat)) {
						$shop_cat_prods = LF . '		<ul>' . LF.'			' . implode(LF.'			', $shop_subcat) . LF .'		</ul>' . LF.'	';
					}
				
				}
				
				if($_tmpl['config']['cat_list_products']) {
					 $shop_cat_prods .= get_category_products($shop_cat_selected, $shop_detail_id, $shop_cat_selected, $shop_subcat_selected, $shop_cat_link);
				}
				
			}
			$shop_cat[$x] .= '>';
			$shop_cat[$x] .= '<a href="' . $shop_cat_link . '&amp;shop_cat=' . $row['cat_id'] . '">';
			$shop_cat[$x] .= html_specialchars($row['cat_name']);
			$shop_cat[$x] .= '</a>' . $shop_cat_prods;
			$shop_cat[$x] .= '</li>';
		
			$x++;
		}	
	
	}
	
	if( count($shop_cat) ) {
	
		if( ! $shop_limited_cat ) {
			$shop_cat[$x]  = '<li';
			if($shop_cat_selected == 'all') {
				$shop_cat[$x] .= ' class="active"';
			}
			$shop_cat[$x] .= '>';
			$shop_cat[$x] .= '<a href="' . $shop_cat_link . '&amp;shop_cat=all">';
			$shop_cat[$x] .= html_specialchars($_tmpl['config']['cat_all']);
			$shop_cat[$x] .= '</a>';
			$shop_cat[$x] .= '</li>';
		}
		$shop_cat = '<ul class="shop_cat">' . LF.'	' . implode(LF.'	', $shop_cat) . LF . '</ul>';
		
	
	} else {
		
		$shop_cat = '';
		
	}
	
	$content['all'] = str_replace('{SHOP_CATEGORIES}', $shop_cat, $content['all']);
	$content['all'] = preg_replace('/\{SHOP_CATEGORY:\d+\}/', $shop_cat, $content["all"]);
	
	if($shop_cat_selected) {
		$GLOBALS['_getVar']['shop_cat'] = $shop_cat_selected;
		if($shop_subcat_selected) {
			$GLOBALS['_getVar']['shop_cat'] .= '_' . $shop_subcat_selected;
		}
	}

}


// Ok lets search for product listing
if( $_shop_load_list !== false ) {

	// check selected category
	$shop_cat_selected	= isset($GLOBALS['_getVar']['shop_cat']) ? $GLOBALS['_getVar']['shop_cat'] : 0;
	if(strpos($shop_cat_selected, '_')) {
		$shop_cat_selected = explode('_', $shop_cat_selected, 2);
		if(isset($shop_cat_selected[1])) {
			$shop_subcat_selected	= intval($shop_cat_selected[1]);
		}
		$shop_cat_selected = intval($shop_cat_selected[0]);
		if(!$shop_cat_selected) {
			//$shop_cat_selected		= 'all';
			$shop_subcat_selected	= 0;
		}
	} else {
		$shop_cat_selected		= intval($shop_cat_selected);
		$shop_subcat_selected	= 0;
	}
	$selected_product_cat = $shop_subcat_selected ? $shop_subcat_selected : $shop_cat_selected;
	
	$shop_detail_id		= isset($GLOBALS['_getVar']['shop_detail']) ? intval($GLOBALS['_getVar']['shop_detail']) : 0;
	
	$shop_cat_name = get_shop_category_name($shop_cat_selected);

	if(empty($shop_cat_name)) {
		$shop_cat_name		= $_tmpl['config']['cat_all'];
		$shop_cat_selected	= 0;
	}
	
	$sql  = "SELECT * FROM ".DB_PREPEND.'phpwcms_shop_products WHERE ';
	$sql .= "shopprod_status=1";

	if($selected_product_cat && !$shop_detail_id) {

		$sql .= ' AND (';
		$sql .= "shopprod_category = '" . $selected_product_cat . "' OR ";
		$sql .= "shopprod_category LIKE '%," . $selected_product_cat . ",%' OR ";
		$sql .= "shopprod_category LIKE '" . $selected_product_cat . ",%' OR ";
		$sql .= "shopprod_category LIKE '%," . $selected_product_cat . "'";
		$sql .= ')';
	
	} elseif($shop_detail_id) {

		$sql .= ' AND shopprod_id=' . $shop_detail_id;
	
	} else {
		
		$sql .= ' AND shopprod_listall=1';
		
	}
	
	$_tmpl['config']['cat_list_sort_by'] = trim($_tmpl['config']['cat_list_sort_by']);
	if($_tmpl['config']['cat_list_sort_by'] !== '') {
		$sql .= ' ORDER BY '.aporeplace($_tmpl['config']['cat_list_sort_by']);
	}
	
	$data = _dbQuery($sql);
	
	if( count($shop_cat) ) {
	
		$x = 0;
		$entry = array();

		$shop_prod_detail = rel_url(array(), array('shop_detail'));
		
		$_tmpl['config']['init_lightbox'] = false;

		foreach($data as $row) {
		
			$_price['vat'] = $row['shopprod_vat'];
			if($row['shopprod_netgross'] == 1) {
				// price given is GROSS price, including VAT
				$_price['net']		= $row['shopprod_price'] / (1 + $_price['vat'] / 100);
				$_price['gross']	= $row['shopprod_price'];
			} else {
				// price given is NET price, excluding VAT
				$_price['net']		= $row['shopprod_price'];
				$_price['gross']	= $row['shopprod_price'] * (1 + $_price['vat'] / 100);
			}
			
			$_price['vat']		= number_format($_price['vat'],   $_tmpl['config']['vat_decimals'],   $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
			$_price['net']		= number_format($_price['net'],   $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
			$_price['gross']	= number_format($_price['gross'], $_tmpl['config']['price_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']);
			$_price['weight']	= $row['shopprod_weight'] > 0 ? number_format($row['shopprod_weight'], $_tmpl['config']['weight_decimals'], $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']) : '';
			
			$row['shopprod_var'] = @unserialize($row['shopprod_var']);
			
			// select template based on listing or detail view
			$entry[$x] = $shop_detail_id ? $_tmpl['detail'] : $_tmpl['list_entry'];
			
			$_cart = preg_match("/\[CART_ADD\](.*?)\[\/CART_ADD\]/is", $entry[$x], $g) ? $g[1] : '';
			
			$_cart_add  = '<form action="' . $shop_prod_detail . '" method="post">';
			$_cart_add .= '<input type="hidden" name="shop_prod_id" value="' . $row['shopprod_id'] . '" />';
			$_cart_add .= '<input type="hidden" name="shop_action" value="add" />';
			$_cart_add .= '<input type="hidden" name="shop_prod_amount" value="1" />';
			if(strpos($_cart, 'input ')) {
				// user has set input button
				$_cart_add .= $_cart;
			} else {
				$_cart_add .= '<input type="submit" name="shop_cart_add" value="' . html_specialchars($_cart) . '" class="cart_add_button" />';
			}
			$_cart_add .= '</form>';

			$entry[$x] = preg_replace('/\[CART_ADD\](.*?)\[\/CART_ADD\]/is', $_cart_add , $entry[$x]);
			
			// product name
			$entry[$x] = str_replace('{CURRENCY_SYMBOL}', html_entities($_shopPref['shop_pref_currency']), $entry[$x]);
			$entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_TITLE', html_specialchars($row['shopprod_name1']));
			$entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_ADD', html_specialchars($row['shopprod_name2']));
			$entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_SHORT', $row['shopprod_description0']);
			$entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_LONG', $row['shopprod_description1']);
			$entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_WEIGHT', $_price['weight']);
			$entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_NET_PRICE', $_price['net']);
			$entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_GROSS_PRICE', $_price['gross']);
			$entry[$x] = render_cnt_template($entry[$x], 'PRODUCT_VAT', $_price['vat']);
			$entry[$x] = render_cnt_template($entry[$x], 'ORDER_NUM', html_specialchars($row['shopprod_ordernumber']));
			$entry[$x] = render_cnt_template($entry[$x], 'MODEL', html_specialchars($row['shopprod_model']));
			$entry[$x] = render_cnt_template($entry[$x], 'VIEWED', number_format($row['shopprod_track_view'], 0, $_tmpl['config']['dec_point'], $_tmpl['config']['thousands_sep']));
			
			if($shop_detail_id) {
				
				$_tmpl['config']['mode']		= 'detail';
				$_tmpl['config']['lightbox_id']	= '[product_'.$x.'_'.$shop_detail_id.']';
				
				// product detail
				$entry[$x] = str_replace('{PRODUCT_DETAIL_LINK}', $shop_prod_detail, $entry[$x]);
				
				$_prod_list_img = array();
				
				if(count($row['shopprod_var']['images'])) {

					foreach($row['shopprod_var']['images'] as $img_key => $img_vars) {
						if($img_vars = shop_image_tag($row['shopprod_var']['images'][$img_key])) {
							$_prod_list_img[] = $img_vars;
						}
					}
				}
				$_prod_list_img = implode($_tmpl['image_space'], $_prod_list_img);
				
				
				// Update product view count
				// ToDo: Maybe use cookie or session to avoid tracking in case showed once
				$sql = 'UPDATE LOW_PRIORITY '.DB_PREPEND.'phpwcms_shop_products SET shopprod_track_view=shopprod_track_view+1 WHERE shopprod_id='.$shop_detail_id;
				_dbQuery($sql, 'UPDATE');
				
			} else {
				
				$_tmpl['config']['mode']		= 'list';
				$_tmpl['config']['lightbox_id']	= '';
			
				if(count($row['shopprod_var']['images'])) {
					$_prod_list_img = shop_image_tag($row['shopprod_var']['images'][0]);
				} else {
					$_prod_list_img = '';
				}
				
				// product listing
				$entry[$x] = str_replace('{PRODUCT_DETAIL_LINK}', $shop_prod_detail.'&amp;shop_detail='.$row['shopprod_id'], $entry[$x]);
				
			}
			
			if(!$_tmpl['config']['init_lightbox'] && $_tmpl['config']['image_'.$_tmpl['config']['mode'].'_lightbox'] && $_prod_list_img) {
				$_tmpl['config']['init_lightbox'] = true;
			}
			
			$entry[$x] = render_cnt_template($entry[$x], 'IMAGE', $_prod_list_img);

			$x++;
		}
		
		// initialize Lightbox effect
		if($_tmpl['config']['init_lightbox']) {
			initializeLightbox();
		}
		
		$entries = implode($_tmpl['list_space'], $entry);

	} else {
	
		$entries = $_tmpl['list_none'];
	
	}
	

	$entries = $_tmpl['list_header'] . LF . $entries . LF . $_tmpl['list_footer'];
	
	$entries = str_replace('{CATEGORY}', html_specialchars($shop_cat_name), $entries);

	$content['all'] = str_replace('{SHOP_PRODUCTLIST}', $entries, $content['all']);
	
}

if( $_shop_load_order ) {

	$cart_data = get_cart_data();
	
	if(empty($cart_data)) {
		
		// cart is empty
		$order_process = $_tmpl['cart_none'];

	} elseif(isset($_POST['shop_cart_checkout']) || isset($ERROR['inv_address']) || isset($_SESSION['shopping_cart']['error']['step1']) || isset($_POST['shop_edit_address'])) {
	
		// order Step 1 -> get address
	
		$_step1 = array(
					'INV_FIRSTNAME' => '',
					'INV_NAME' => '',
					'INV_ADDRESS' => '',
					'INV_ZIP' => '',
					'INV_CITY' => '',
					'INV_REGION' => '',
					'INV_COUNTRY' => '',
					'EMAIL' => '',
					'PHONE' => ''
						);
						
		// handle custom fields
		foreach($_tmpl['config']['shop_field'] as $item_key => $row) {
			$_step1['shop_field_'.$item_key] = '';
		}
	
		if(isset($_SESSION['shopping_cart']['step1'])) {
			$_step1 = array_merge($_step1, $_SESSION['shopping_cart']['step1']);
		}

		// checkout step 1 -> insert invoice address
		$order_process = $_tmpl['inv_address'];
		
		foreach($_step1 as $item_key => $row) {
			$field_error   = empty($ERROR['inv_address'][$item_key]) ? '' : $ERROR['inv_address'][$item_key];
			
			$order_process = render_cnt_template($order_process, $item_key, html_specialchars($row));
			$order_process = render_cnt_template($order_process, 'ERROR_'.$item_key, $field_error);
		}
		
		$payment_options = get_payment_options();

		if(count($payment_options)) {
		
			$payment_fields = array();
			$payment_selected = isset($_SESSION['shopping_cart']['payby']) && isset($payment_options[ $_SESSION['shopping_cart']['payby'] ]) ? $_SESSION['shopping_cart']['payby'] : '';
			foreach($payment_options as $item_key => $row) {
				
				$payment_fields[$item_key]  = '<div><label>';
				$payment_fields[$item_key] .= '<input type="radio" name="shopping_payment" id="shopping_payment_'.$item_key.'" ';
				$payment_fields[$item_key] .= 'value="'.$item_key.'" ';
				if($payment_selected == $item_key) {
					$payment_fields[$item_key] .= ' checked="checked"';
				}
				$payment_fields[$item_key] .= ' />';
				$payment_fields[$item_key] .= '<span>' . html_specialchars($_tmpl['config']['label_payby_'.$item_key]) . '</span>';
				$payment_fields[$item_key] .= '</label></div>';
			}
			$order_process = render_cnt_template($order_process, 'PAYMENT', implode(LF, $payment_fields));
		} else {
			$order_process = render_cnt_template($order_process, 'PAYMENT', '');
		}
		
		// some errr handling
		$order_process = render_cnt_template($order_process, 'ERROR_PAYMENT', isset($ERROR['inv_address']['payment']) ? ' ' : '');
		$order_process = render_cnt_template($order_process, 'IF_ERROR', isset($ERROR['inv_address']) ? ' ' : '');
		
		$order_process = '<form action="' .$_tmpl['config']['cart_url']. '" method="post">' . LF . trim($order_process) . LF . '</form>';


	} elseif( isset($_POST['shop_order_step1']) || isset($ERROR['terms']) || isset($_SESSION['shopping_cart']['error']['step2']) ) {
	
		// Order step 2 -> Proof and [X] terms of business
		$order_process = $_tmpl['order_terms'];
		
		$order_process = str_replace('{SHOP_LINK}', $_tmpl['config']['shop_url'], $order_process);
		$order_process = str_replace('{CART_LINK}', $_tmpl['config']['cart_url'], $order_process);
		
		foreach($_SESSION['shopping_cart']['step1'] as $item_key => $row) {
			$order_process = render_cnt_template($order_process, $item_key, nl2br(html_specialchars($row)));
		}
		
		$order_process = render_cnt_template($order_process, 'IF_ERROR', isset($_SESSION['shopping_cart']['error']['step2']) ? ' ' : '');
		
		if(isset($_SESSION['shopping_cart']['payby'])) {
			$order_process = render_cnt_template($order_process, 'PAYMENT', html_specialchars($_tmpl['config']['label_payby_'.$_SESSION['shopping_cart']['payby']]));
		} else {
			$order_process = render_cnt_template($order_process, 'PAYMENT', '');
		}
		
		$cart_mode = 'terms';
		include($phpwcms['modules']['shop']['path'].'inc/cart.items.inc.php');
		$order_process = str_replace('{ITEMS}', implode($_tmpl['term_space'], $cart_items), $order_process);
		
		$terms_text		= _getConfig( 'shop_pref_terms', '_shopPref' );
		$terms_format	= _getConfig( 'shop_pref_terms_format', '_shopPref' );
		$order_process = str_replace('{TERMS}', $terms_format ? $terms_text : nl2br(html_specialchars($terms_text)), $order_process);
		

		include($phpwcms['modules']['shop']['path'].'inc/cart.parse.inc.php');
		
		// Is Shipping?
		//$order_process  = preg_replace('/\[SHIPPING\](.*?)\[\/SHIPPING\]/is', '' , $order_process);
		$order_process = render_cnt_template($order_process, 'SHIPPING', $subtotal['float_shipping_net'] > 0 ? 1 : '');
		

	} elseif( isset($_POST['shop_order_submit']) && !isset($_SESSION['shopping_cart']['error']['step2']) ) {

		// OK agreed - now send order
		
		if($_tmpl['config']['order_number_style'] == 'RANDOM') {
			$order_num = generic_string(8, 2);
		} else {
			// count all current orders
			$order_num = _dbCount('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_shop_orders') + 1;
			if(strpos($_tmpl['config']['order_number_style'], '%') !== FALSE) {
				$order_num = sprintf($_tmpl['config']['order_number_style'], $order_num);
			}
		}
		
		// prepare customer mail
		$order_process = $_tmpl['mail_customer'];

		foreach($_SESSION['shopping_cart']['step1'] as $item_key => $row) {
			$order_process = render_cnt_template($order_process, $item_key, html_specialchars($row));
		}
		
		$cart_mode = 'mail1';
		include($phpwcms['modules']['shop']['path'].'inc/cart.items.inc.php');
		$order_process = str_replace('{ITEMS}', implode(LF.LF, $cart_items), $order_process);

		include($phpwcms['modules']['shop']['path'].'inc/cart.parse.inc.php');

		$order_process = str_replace('{ORDER}', $order_num, $order_process);
		$order_process = render_cnt_date($order_process, time());
		
		$mail_customer = @html_entity_decode($order_process);
		
		// prepare new order mail
		$order_process = $_tmpl['mail_neworder'];
		
		foreach($_SESSION['shopping_cart']['step1'] as $item_key => $row) {
			$order_process = render_cnt_template($order_process, $item_key, html_specialchars($row));
		}
		
		$cart_mode = 'mail1';
		include($phpwcms['modules']['shop']['path'].'inc/cart.items.inc.php');
		$order_process = str_replace('{ITEMS}', implode(LF.LF, $cart_items), $order_process);

		include($phpwcms['modules']['shop']['path'].'inc/cart.parse.inc.php');

		$order_process = str_replace('{ORDER}', $order_num, $order_process);
		$order_process = render_cnt_date($order_process, time());
		
		$mail_neworder = @html_entity_decode($order_process);
		
		if(!empty($_SESSION['shopping_cart']['payby'])) {
			$payment = $_SESSION['shopping_cart']['payby'];
			$mail_customer = render_cnt_template($mail_customer, 'PAYBY_'.strtoupper($payment), $_tmpl['config']['label_payby_'.$payment]);
			$mail_neworder = render_cnt_template($mail_neworder, 'PAYMENT', $_tmpl['config']['label_payby_'.$payment]);
		} else {
			$mail_customer = render_cnt_template($mail_customer, 'PAYBY_'.strtoupper($payment), 'n.a.');
			$mail_neworder = render_cnt_template($mail_neworder, 'PAYMENT', 'n.a.');
			$payment = 'n.a.';
		}
		
		$payment_options = get_payment_options();
		foreach($payment_options  as $item_key => $row) {
			$mail_customer = render_cnt_template($mail_customer, 'PAYBY_'.strtoupper($item_key), '');
		}

		// store order in database		
		$order_data = array(
			'order_number'		=> $order_num,
			'order_date'		=> gmdate('Y-m-d H:i'),
			'order_name'		=> $_SESSION['shopping_cart']['step1']['INV_NAME'],
			'order_firstname'	=> $_SESSION['shopping_cart']['step1']['INV_FIRSTNAME'],
			'order_email'		=> $_SESSION['shopping_cart']['step1']['EMAIL'],
			'order_net'			=> $subtotal['float_total_net'],
			'order_gross'		=> $subtotal['float_total_gross'],
			'order_payment'		=> $payment,
			'order_data'		=> @serialize( array(
												'cart' => $cart_data, 
												'address' => $_SESSION['shopping_cart']['step1'], 
												'mail_customer' => $mail_customer,
												'mail_self' => $mail_neworder,
												'subtotal' => array(
														'subtotal_net' => $subtotal['float_net'],
														'subtotal_gross' => $subtotal['float_gross']
																	),
												'shipping' => array(
														'shipping_net' => $subtotal['float_shipping_net'],
														'shipping_gross' => $subtotal['float_shipping_gross']
																	),
												'weight' => $subtotal['float_weight']
												) ),
			'order_status'		=> 'NEW-ORDER'		
		);
		
		// receive order db ID
		$order_data = _dbInsert('phpwcms_shop_orders', $order_data);
		
		// send mail to customer
		$email_from = _getConfig( 'shop_pref_email_from', '_shopPref' );
		if(!is_valid_email($email_from)) $email_from = $phpwcms['SMTP_FROM_EMAIL'];

		$order_mail_customer = array(
			'recipient'	=> $_SESSION['shopping_cart']['step1']['EMAIL'],
			'toName'	=> $_SESSION['shopping_cart']['step1']['INV_FIRSTNAME'] . ' ' . $_SESSION['shopping_cart']['step1']['INV_NAME'],
			'subject'	=> str_replace('{ORDER}', $order_num, $_tmpl['config']['mail_customer_subject']),
			'text'		=> $mail_customer,
			'from'		=> $email_from,
			'sender'	=> $email_from
		);
		
		$order_data_mail_customer = sendEmail($order_mail_customer);
		
		// send mail to shop
		$send_order_to = convertStringToArray( _getConfig( 'shop_pref_email_to', '_shopPref' ), ';' );
		if(empty($send_order_to[0]) || !is_valid_email($send_order_to[0])) {
			$email_to = $phpwcms['SMTP_FROM_EMAIL'];
		} else {
			$email_to = $send_order_to[0];
			unset($send_order_to[0]);
		}
		
		$order_mail_self = array(
			'from'		=> $_SESSION['shopping_cart']['step1']['EMAIL'],
			'fromName'	=> $_SESSION['shopping_cart']['step1']['INV_FIRSTNAME'] . ' ' . $_SESSION['shopping_cart']['step1']['INV_NAME'],
			'subject'	=> str_replace('{ORDER}', $order_num, $_tmpl['config']['mail_neworder_subject']),
			'text'		=> $mail_neworder,
			'recipient'	=> $email_to,
			'sender'	=> $_SESSION['shopping_cart']['step1']['EMAIL']
		);
		
		$order_data_mail_self = sendEmail($order_mail_self);
		
		// are there additional recipients for orders?
		if(count($send_order_to)) {
			foreach($send_order_to as $value) {
				$order_mail_self['recipient'] = $value;
				@sendEmail($order_mail_self);
			}
		}
		
	
		// success
		if(!empty($order_data['INSERT_ID']) || !empty($order_data_mail_customer[0])) {
	
			$order_process = $_tmpl['order_success'];
			
			foreach($_SESSION['shopping_cart']['step1'] as $item_key => $row) {
				$order_process = render_cnt_template($order_process, $item_key, html_specialchars($row));
			}
			unset($_SESSION['shopping_cart']);

		// NO success
		} else {

			$order_process = $_tmpl['order_failed'];
			
			$order_process = str_replace('{SUBJECT}', rawurlencode($_tmpl['config']['mail_neworder_subject']), $order_process);
			$order_process = str_replace('{MSG}', rawurlencode('---- FALLBACK MESSAGE ---' . LF . LF . $mail_customer), $order_process);
			
			foreach($_SESSION['shopping_cart']['step1'] as $item_key => $row) {
				$order_process = render_cnt_template($order_process, $item_key, html_specialchars($row));
			}

		}
		
		$order_process = str_replace('{ORDER}', $order_num, $order_process);

		
	} else {
	
		// show cart
		
		$cart_mode = 'cart';
		include($phpwcms['modules']['shop']['path'].'inc/cart.items.inc.php');
		
		$order_process  = $_tmpl['cart_header'];
		$order_process .= implode($_tmpl['cart_space'], $cart_items);
		$order_process .= $_tmpl['cart_footer'];
		
		include($phpwcms['modules']['shop']['path'].'inc/cart.parse.inc.php');
		
		// Update Cart Button
		$_cart_button = preg_match("/\[UPDATE\](.*?)\[\/UPDATE\]/is", $order_process, $g) ? $g[1] : '';
		if(strpos($_cart_button, 'input ') === false) {
			$_cart_button = '<input type="submit" name="shop_cart_update" value="' . html_specialchars($_cart_button) . '" class="cart_update_button" />';
		}
		$order_process  = preg_replace('/\[UPDATE\](.*?)\[\/UPDATE\]/is', $_cart_button , $order_process);
		
		// Checkout Button
		$_cart_button = preg_match("/\[CHECKOUT\](.*?)\[\/CHECKOUT\]/is", $order_process, $g) ? $g[1] : '';
		if(strpos($_cart_button, 'input ') === false) {
			$_cart_button = '<input type="submit" name="shop_cart_checkout" value="' . html_specialchars($_cart_button) . '" class="cart_checkout_button" />';
		}
		$order_process  = preg_replace('/\[CHECKOUT\](.*?)\[\/CHECKOUT\]/is', $_cart_button , $order_process);
		
		// Is Shipping?
		//$order_process  = preg_replace('/\[SHIPPING\](.*?)\[\/SHIPPING\]/is', '' , $order_process);
		$order_process = render_cnt_template($order_process, 'SHIPPING', $subtotal['float_shipping_net'] > 0 ? 1 : '');
		
		$order_process  = '<form action="' .$_tmpl['config']['cart_url']. '" method="post">' . LF . trim($order_process) . LF . '</form>';
		
	}

	$order_process = str_replace('{SHOP_LINK}', $_tmpl['config']['shop_url'], $order_process);
	
	$content['all'] = str_replace('{SHOP_ORDER_PROCESS}', $order_process, $content['all']);
}

// small cart
if($_shop_load_cart_small) {

	$_cart_count = 0;

	if(isset($_SESSION['shopping_cart']['products']) && is_array($_SESSION['shopping_cart']['products']) && count($_SESSION['shopping_cart']['products'])) {
		foreach($_SESSION['shopping_cart']['products'] as $cartval) {
			$_cart_count += $cartval;
		}
	}	

	if(!$_cart_count) {
		$_cart_count = '';
	}

	if(strpos($_tmpl['cart_small'], '{CART_LINK}')) {
	
		$shop_cat_selected	= isset($GLOBALS['_getVar']['shop_cat']) ? $GLOBALS['_getVar']['shop_cat'] : 0;
		$shop_detail_id		= isset($GLOBALS['_getVar']['shop_detail']) ? intval($GLOBALS['_getVar']['shop_detail']) : 0;
		unset($GLOBALS['_getVar']['shop_cat'], $GLOBALS['_getVar']['shop_detail']);
		$_tmpl['cart_small'] = str_replace('{CART_LINK}', $_tmpl['config']['cart_url'], $_tmpl['cart_small']);
		if($shop_cat_selected) $GLOBALS['_getVar']['shop_cat'] = $shop_cat_selected;
		if($shop_detail_id) $GLOBALS['_getVar']['shop_detail'] = $shop_detail_id;
		
	}
	
	$_tmpl['cart_small'] = render_cnt_template($_tmpl['cart_small'], 'COUNT', $_cart_count);
	$content['all'] = str_replace('{CART_SMALL}', $_tmpl['cart_small'], $content['all']);
}



function get_cart_data() {

	// retrieve all cart data
	if(empty($_SESSION['shopping_cart']['products']) || ! is_array($_SESSION['shopping_cart']['products']) ||	! count($_SESSION['shopping_cart']['products'])	) 
	{
		return array();
	}
	
	$in = array();
	foreach($_SESSION['shopping_cart']['products'] as $key => $value) {
		$key = intval($key);
		$in[$key] = $key;
	}

	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_shop_products WHERE shopprod_status=1 AND ';
	$sql .= 'shopprod_id IN (' . implode(',', $in) . ')';
	$data = _dbQuery($sql);
	
	if(isset($data[0])) {
	
		foreach($data as $key => $value) {
	
			$data[$key]['shopprod_quantity'] = $_SESSION['shopping_cart']['products'][ $value['shopprod_id'] ];
	
		}
	
	}

	return $data;	
}



function shop_image_tag($img) {
	
	//['config'][], $_tmpl['config']['']
	$config =& $GLOBALS['_tmpl']['config'];
	
	// set image values
	$width		= $config['image_'.$config['mode'].'_width'];
	$height		= $config['image_'.$config['mode'].'_height'];
	$crop		= $config['image_'.$config['mode'].'_crop'];

	$thumb_image = get_cached_image(
			array(	"target_ext"	=>	$img['f_ext'],
					"image_name"	=>	$img['f_hash'] . '.' . $img['f_ext'],
					"max_width"		=>	$width,
					"max_height"	=>	$height,
					"thumb_name"	=>	md5($img['f_hash'].$width.$height.$GLOBALS['phpwcms']["sharpen_level"].$crop),
					'crop_image'	=>	$crop
				  )
			);
	
	if($thumb_image) {
		
		// now try to build caption and if neccessary add alt to image or set external link for image
		$caption	= getImageCaption($img['caption']);
		// set caption and ALT Image Text for imagelist
		$capt_cur	= html_specialchars($caption[0]);
		$caption[3] = empty($caption[3]) ? '' : ' title="'.html_specialchars($caption[3]).'"'; //title
		$caption[1] = html_specialchars(empty($caption[1]) ? $img['f_name'] : $caption[1]);
		
		$list_img_temp  = '<img src="'.PHPWCMS_IMAGES.$thumb_image[0].'" ';
		$list_img_temp .= $thumb_image[3].' alt="'.$caption[1].'"'.$caption[3].' border="0" />';
		
		// use lightbox effect
		if($config['image_'.$config['mode'].'_lightbox']) {
		
			$a  = '<a href="img/cmsimage.php/';
			$a .= $config['image_zoom_width'] . 'x' . $config['image_zoom_height'] . '/';
			$a .= $img['f_hash'] . '.' . $img['f_ext'] . '" ';
			$a .= 'target="_blank" rel="lightbox'.$config['lightbox_id'].'"' . $caption[3] .'>';
			
			$list_img_temp = $a . $list_img_temp . '</a>';
		}
		
		return $list_img_temp;

	}
	
	return '';
}

function get_shop_category_name($id) {
	if(empty($id)) return '';
	$sql  = 'SELECT cat_name FROM '.DB_PREPEND.'phpwcms_categories WHERE ';
	$sql .= "cat_type='module_shop' AND cat_status=1 AND cat_id=" . intval($id) . ' LIMIT 1';
	$data = _dbQuery($sql);
	if(is_array($data)) {
		foreach($data as $row) {
			return $row['cat_name'];
		}
	}
	return '';
}

function get_payment_options() {

	$payment_prefs = _getConfig( 'shop_pref_payment', '_shopPref' );
	$supported = array('prepay' => 0, 'pod' => 0, 'onbill' => 0);
	$available = array();
	foreach($supported as $key => $value) {
		if($payment_prefs[$key]) $available[$key] = $payment_prefs[$key];
	}
	return $available;
}


function get_category_products($selected_product_cat, $shop_detail_id, $shop_cat_selected, $shop_subcat_selected, $shop_cat_link) {
	
	$shop_cat_prods = '';
	
	$sql  = "SELECT * FROM ".DB_PREPEND.'phpwcms_shop_products WHERE ';
	$sql .= "shopprod_status=1";
	$sql .= ' AND (';
	$sql .= "shopprod_category = '" . $selected_product_cat . "' OR ";
	$sql .= "shopprod_category LIKE '%," . $selected_product_cat . ",%' OR ";
	$sql .= "shopprod_category LIKE '" . $selected_product_cat . ",%' OR ";
	$sql .= "shopprod_category LIKE '%," . $selected_product_cat . "'";
	$sql .= ')';
	$pdata = _dbQuery($sql);
	
	if(is_array($pdata) && count($pdata)) {
	
		$z = 0;
		$shop_cat_prods = array();
		foreach($pdata as $prow) {
			
			$shop_cat_prods[$z] = '<li';
			if($prow['shopprod_id'] == $shop_detail_id) {
				$shop_cat_prods[$z] .= ' class="active"';
			}
			$shop_cat_prods[$z] .= '>';
			$shop_cat_prods[$z] .= '<a href="' . $shop_cat_link . '&amp;shop_cat=' . $shop_cat_selected;
			if($shop_subcat_selected) {
				$shop_cat_prods[$z] .= '_' . $shop_subcat_selected;
			}
			$shop_cat_prods[$z] .= '&amp;shop_detail=' .$prow['shopprod_id']. '">';
			$shop_cat_prods[$z] .= html_specialchars($prow['shopprod_name1']);
			$shop_cat_prods[$z] .= '</a>';
			$shop_cat_prods[$z] .= '</li>';
			$z++;
		}

		if(count($shop_cat_prods)) {
			$shop_cat_prods = LF . '		<ul class="products">' . LF.'			' . implode(LF.'			', $shop_cat_prods) . LF .'		</ul>' . LF.'	';
		}
	
	}
	
	return $shop_cat_prods;

}


?>