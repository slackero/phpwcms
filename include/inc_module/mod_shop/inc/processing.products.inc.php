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


if($action == 'edit') {


	$plugin['data']['shopprod_id']	= intval($_GET['edit']);

	if(isset($_POST['shopprod_id']) ) {
	
		// check if form should be closed only -> and back to listing mode
		if( isset($_POST['close']) ) {
			headerRedirect( shop_url('controller=prod', '') );
		}
	
		$plugin['data']['shopprod_changedate']		= time();
		
		$plugin['data']['shopprod_name1']			= clean_slweg($_POST['shopprod_name1']);
		$plugin['data']['shopprod_name2']			= clean_slweg($_POST['shopprod_name2']);
		
		$plugin['data']['shopprod_ordernumber']		= clean_slweg($_POST['shopprod_ordernumber']);
		$plugin['data']['shopprod_model']			= clean_slweg($_POST['shopprod_model']);
		
		$plugin['data']['shopprod_price']			= clean_slweg($_POST['shopprod_price']);
		$plugin['data']['shopprod_vat']				= abs(floatval($_POST['shopprod_vat']));
		$plugin['data']['shopprod_weight']			= clean_slweg($_POST['shopprod_weight']);
		
		$plugin['data']['shopprod_size']			= clean_slweg($_POST['shopprod_size']);
		$plugin['data']['shopprod_color']			= clean_slweg($_POST['shopprod_color']);
		
		$plugin['data']['shopprod_size']			= explode(LF, $plugin['data']['shopprod_size']);
		natsort($plugin['data']['shopprod_size']);
		$plugin['data']['shopprod_size']			= implode(LF, $plugin['data']['shopprod_size']);
		
		$plugin['data']['shopprod_color']			= explode(LF, $plugin['data']['shopprod_color']);
		natsort($plugin['data']['shopprod_color']);
		$plugin['data']['shopprod_color']			= implode(LF, $plugin['data']['shopprod_color']);
		
				
		$plugin['data']['shopprod_netgross']		= empty($_POST['shopprod_netgross']) ? 0 : 1; //0 = net, 1 = gross
		
		$plugin['data']['shopprod_description0']	= slweg($_POST['shopprod_description0']);
		$plugin['data']['shopprod_description1']	= slweg($_POST['shopprod_description1']);
		$plugin['data']['shopprod_description2']	= clean_slweg($_POST['shopprod_description2']);
		$plugin['data']['shopprod_description3']	= clean_slweg($_POST['shopprod_description3']);
		
		$plugin['data']['shopprod_url']				= clean_slweg($_POST['shopprod_url']);
		
		$plugin['data']['shopprod_status']			= empty($_POST['shopprod_status']) ? 0 : 1;
		$plugin['data']['shopprod_listall']			= empty($_POST['shopprod_listall']) ? 0 : 1;
		
		$plugin['data']['shopprod_category']		= isset($_POST['shopprod_category']) && is_array($_POST['shopprod_category']) ? $_POST['shopprod_category'] : array();
		
		if(!$plugin['data']['shopprod_name1']) {
			$plugin['error']['shopprod_name1'] = 'No name';
		}
		if(!$plugin['data']['shopprod_ordernumber']) {
			$plugin['error']['shopprod_ordernumber'] = 'No order number';
		} else {
			$sql  = 'SELECT COUNT(shopprod_id) FROM '.DB_PREPEND.'phpwcms_shop_products WHERE ';
			if($plugin['data']['shopprod_id']) $sql .= 'shopprod_id != '.$plugin['data']['shopprod_id'].' AND ';
			$sql .= "shopprod_ordernumber LIKE '" . aporeplace($plugin['data']['shopprod_ordernumber']) . "'";
			if(_dbCount($sql)) $plugin['error']['shopprod_ordernumber'] = 'Unique order number necessary';
		}
		
		$plugin['data']['shopprod_price']			= str_replace($BLM['thousands_sep'], '', $plugin['data']['shopprod_price']);
		$plugin['data']['shopprod_price']			= str_replace($BLM['dec_point'], '.', $plugin['data']['shopprod_price']);
		$plugin['data']['shopprod_price']			= floatval($plugin['data']['shopprod_price']);
		if(abs($plugin['data']['shopprod_price']) > 10000000000) {
			$plugin['error']['shopprod_price'] = 'Check price';
		}
		
		$plugin['data']['shopprod_weight']			= str_replace($BLM['thousands_sep'], '', $plugin['data']['shopprod_weight']);
		$plugin['data']['shopprod_weight']			= str_replace($BLM['dec_point'], '.', $plugin['data']['shopprod_weight']);
		$plugin['data']['shopprod_weight']			= floatval($plugin['data']['shopprod_weight']);
		
		$plugin['data']['shopprod_tag']				= strtolower( preg_replace('/[^0-9a-z, \-_]/i', '', remove_accents($_POST['shopprod_tag']) ) );
		$plugin['data']['shopprod_tag']				= implode(', ', convertStringToArray($plugin['data']['shopprod_tag']));

		
		$plugin['data']['shopprod_caption']			= clean_slweg($_POST["shopprod_caption"], 0 , false);
		$plugin['data']['shopprod_caption'] 		= explode(LF, $plugin['data']['shopprod_caption']);

		$plugin['data']['shopprod_images']			= isset($_POST['shopprod_images']) && is_array($_POST['shopprod_images']) ? $_POST['shopprod_images'] : array();
		
		
		if(is_array($plugin['data']['shopprod_images']) && count($plugin['data']['shopprod_images'])) {
		
		
			$plugin['data']['shopprod_images'] = array_map('intval', $plugin['data']['shopprod_images']);
			$plugin['data']['shopprod_images'] = array_diff($plugin['data']['shopprod_images'], array(0,'',NULL,false));
		
			if(count($plugin['data']['shopprod_images'])) {

				$img_all = _dbQuery('SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE f_id IN ('.implode(',', $plugin['data']['shopprod_images']).')');
				
				// take all values from db
				$temp_img_row = array();
				foreach($img_all as $value) {
					$temp_img_row[ $value['f_id'] ] = $value;
				}
				
				$img_all = array();
				
				// now run though image result - but keep sorting
				foreach($plugin['data']['shopprod_images'] as $key => $value) {
					if(isset($temp_img_row[$value])) {
					
						$img_all[$key]['f_id']		= $temp_img_row[$value]['f_id'];
						$img_all[$key]['f_name']	= $temp_img_row[$value]['f_name'];
						$img_all[$key]['f_hash']	= $temp_img_row[$value]['f_hash'];
						$img_all[$key]['f_ext']		= $temp_img_row[$value]['f_ext'];
						$img_all[$key]['caption']	= isset($plugin['data']['shopprod_caption'][$key]) ? trim($plugin['data']['shopprod_caption'][$key]) : '';
					
					}
				}
				
				$plugin['data']['shopprod_caption']	= array();
				$plugin['data']['shopprod_images']	= $img_all;
				unset($img_all);
				
			}
		}
	
		
				
		if(empty($plugin['error'] )) {
		
			// Update
			if( $plugin['data']['shopprod_id'] ) {
			
				$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_shop_products SET ';
				
				$sql .= "shopprod_changedate = '".aporeplace( date('Y-m-d H:i:s', $plugin['data']['shopprod_changedate']) )."', ";
				$sql .= "shopprod_status = ".$plugin['data']['shopprod_status'].", ";
				
				$sql .= "shopprod_ordernumber = '".aporeplace($plugin['data']['shopprod_ordernumber'])."', ";				
				$sql .= "shopprod_model = '".aporeplace($plugin['data']['shopprod_model'])."', ";
				
				$sql .= "shopprod_tag = '".aporeplace($plugin['data']['shopprod_tag'])."', ";				
				
				$sql .= "shopprod_vat = '".aporeplace($plugin['data']['shopprod_vat'])."', ";
				$sql .= "shopprod_netgross = '".aporeplace($plugin['data']['shopprod_netgross'])."', ";				
				$sql .= "shopprod_price = '".aporeplace($plugin['data']['shopprod_price'])."', ";
				
				$sql .= "shopprod_name1 = '".aporeplace($plugin['data']['shopprod_name1'])."', ";				
				$sql .= "shopprod_name2 = '".aporeplace($plugin['data']['shopprod_name2'])."', ";
				
				$sql .= "shopprod_description0 = '".aporeplace($plugin['data']['shopprod_description0'])."', ";
				$sql .= "shopprod_description1 = '".aporeplace($plugin['data']['shopprod_description1'])."', ";
				$sql .= "shopprod_description2 = '".aporeplace($plugin['data']['shopprod_description2'])."', ";
				$sql .= "shopprod_description3 = '".aporeplace($plugin['data']['shopprod_description3'])."', ";
				
				$sql .= "shopprod_var = '".aporeplace(	serialize( array(
												'images'	=> $plugin['data']['shopprod_images'],
												'url'		=> $plugin['data']['shopprod_url']
														) )	)."', ";
				
				$sql .= "shopprod_category = '".aporeplace( implode(',', $plugin['data']['shopprod_category']) )."', ";
				
				$sql .= "shopprod_weight = '".aporeplace($plugin['data']['shopprod_weight'])."', ";
				$sql .= "shopprod_size = '".aporeplace($plugin['data']['shopprod_size'])."', ";
				$sql .= "shopprod_color = '".aporeplace($plugin['data']['shopprod_color'])."', ";
				$sql .= "shopprod_listall = '".aporeplace($plugin['data']['shopprod_listall'])."' ";
				
				$sql .= "WHERE shopprod_id = " . $plugin['data']['shopprod_id'];
				
				_dbQuery($sql, 'UPDATE');
			
			// INSERT
			} else {

				$sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_shop_products (';
				$sql .= 'shopprod_createdate, shopprod_changedate, shopprod_status, shopprod_ordernumber, shopprod_model, ';
				$sql .= 'shopprod_name1, shopprod_name2, shopprod_tag, shopprod_vat, shopprod_netgross, shopprod_price, ';
				$sql .= 'shopprod_maxrebate, shopprod_description0, shopprod_description1, shopprod_description2, ';
				$sql .= 'shopprod_description3, shopprod_var, shopprod_category, shopprod_weight, shopprod_size, shopprod_color, ';
				$sql .= 'shopprod_listall) VALUES (';
				$sql .= "'".aporeplace( date('Y-m-d H:i:s', $plugin['data']['shopprod_changedate']) )."', ";			
				$sql .= "'".aporeplace( date('Y-m-d H:i:s', $plugin['data']['shopprod_changedate']) )."', ";
				$sql .= $plugin['data']['shopprod_status'].", ";
				
				$sql .= "'".aporeplace($plugin['data']['shopprod_ordernumber'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_model'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_name1'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_name2'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_tag'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_vat'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_netgross'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_price'])."', ";
				$sql .= "'".aporeplace('0')."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_description0'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_description1'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_description2'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_description3'])."', ";
							
				$sql .= "'".aporeplace(	serialize( array(
												'images'	=> $plugin['data']['shopprod_images'],
												'url'		=> $plugin['data']['shopprod_url']
												) )	)."', "; //VAR
				
				$sql .= "'".aporeplace( implode(',', $plugin['data']['shopprod_category']) ) ."', ";
				
				$sql .= "'".aporeplace($plugin['data']['shopprod_weight'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_size'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_color'])."', ";
				$sql .= "'".aporeplace($plugin['data']['shopprod_listall'])."' ";
				
				$sql .= ')';
			
				$result = _dbQuery($sql, 'INSERT');
				
				if( !empty($result['INSERT_ID']) ) {
					$plugin['data']['shopprod_id']	= $result['INSERT_ID'];
				}
			
			}
		
			// save and back to listing mode
			if( isset($_POST['save']) ) {
				headerRedirect( shop_url('controller=prod', '') );
			} else {
				headerRedirect( shop_url( array('controller=prod', 'edit='.$plugin['data']['shopprod_id']), '') );
			}
			
		}


	} elseif( $plugin['data']['shopprod_id'] == 0 ) {
	
		$plugin['data']['shopprod_id']				= 0;
		$plugin['data']['shopprod_changedate']		= time();
		$plugin['data']['shopprod_name1']			= '';
		$plugin['data']['shopprod_name2']			= '';
		$plugin['data']['shopprod_ordernumber']		= '';
		$plugin['data']['shopprod_model']			= '';
		$plugin['data']['shopprod_description0']	= '';
		$plugin['data']['shopprod_description1']	= '';
		$plugin['data']['shopprod_description2']	= '';
		$plugin['data']['shopprod_description3']	= '';
		$plugin['data']['shopprod_status']			= 1;
		$plugin['data']['shopprod_price']			= 0;
		$plugin['data']['shopprod_netgross']		= 0;
		$plugin['data']['shopprod_vat']				= 0;
		$plugin['data']['shopprod_tag']				= '';
		$plugin['data']['shopprod_category']		= array();
		$plugin['data']['shopprod_var']				= array();
		$plugin['data']['shopprod_images']			= array();
		$plugin['data']['shopprod_caption']			= array();
		$plugin['data']['shopprod_weight']			= 0;
		$plugin['data']['shopprod_size']			= '';
		$plugin['data']['shopprod_color']			= '';
		$plugin['data']['shopprod_url']				= '';
		$plugin['data']['shopprod_listall']			= 0;
	
	} else {

		$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_shop_products WHERE ';
		$sql .= "shopprod_id = " . $plugin['data']['shopprod_id'] . ' LIMIT 1';

		$plugin['data'] = _dbQuery($sql);
		
		if( isset($plugin['data'][0]) ) {
			$plugin['data'] = $plugin['data'][0];

			$plugin['data']['shopprod_changedate']	= strtotime($plugin['data']['shopprod_changedate']);
			$plugin['data']['shopprod_category']	= convertStringToArray($plugin['data']['shopprod_category']);
			
			$plugin['data']['shopprod_var']			= @unserialize($plugin['data']['shopprod_var']);
			if(isset($plugin['data']['shopprod_var']['images']) && is_array($plugin['data']['shopprod_var']['images'])) {
				$plugin['data']['shopprod_images']	= $plugin['data']['shopprod_var']['images'];
			} else {
				$plugin['data']['shopprod_images']	= array();
			}
			$plugin['data']['shopprod_caption']		= array();
			$plugin['data']['shopprod_url']			= isset($plugin['data']['shopprod_var']['url']) ? $plugin['data']['shopprod_var']['url'] : '';
			
		} else {
			headerRedirect( shop_url('controller=prod', '') );
		}

	}
	
	$sql  = 'SELECT C1.cat_id, C1.cat_name, C1.cat_pid, C1.cat_status, ';
	$sql .= "IFNULL(CONCAT(C2.cat_name, '>', C1.cat_name), C1.cat_name) AS category ";
	$sql .= 'FROM '.DB_PREPEND.'phpwcms_categories C1 ';
	$sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_categories C2 ';
	$sql .= 'ON C1.cat_pid=C2.cat_id ';
	$sql .= "WHERE C1.cat_type='module_shop' AND C1.cat_status!=9 ";
	$sql .= 'ORDER BY category';
	$plugin['data']['categories'] = _dbQuery($sql);	

} elseif($action == 'status') {

	list($plugin['data']['shopprod_id'], $plugin['data']['shopprod_status']) = explode( '-', $_GET['status'] );
	
	$plugin['data']['shopprod_id']		= intval($plugin['data']['shopprod_id']);
	$plugin['data']['shopprod_status']	= empty($plugin['data']['shopprod_status']) ? 1 : 0;

	$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_shop_products SET ';
	$sql .= "shopprod_status = ".$plugin['data']['shopprod_status']." ";
	$sql .= "WHERE shopprod_id = " . $plugin['data']['shopprod_id'];
	
	_dbQuery($sql, 'UPDATE');

	headerRedirect( shop_url('controller=prod', '') );

} elseif($action == 'delete') {

	$plugin['data']['shopprod_id']		= intval($_GET['delete']);

	$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_shop_products SET ';
	$sql .= "shopprod_status = 9 ";
	$sql .= "WHERE shopprod_id = " . $plugin['data']['shopprod_id'];
	
	_dbQuery($sql, 'UPDATE');

	headerRedirect( shop_url('controller=prod', '') );

}


?>