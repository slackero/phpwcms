<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

// set fields

$plugin['fields'] = array(

	'detail_login'		=> 'STRING',

	'detail_title'		=> 'STRING',
	'detail_firstname'	=> 'STRING',
	'detail_lastname'	=> 'STRING',
	'detail_company'	=> 'STRING',
	'detail_street'		=> 'STRING',
	'detail_add'		=> 'STRING',
	'detail_city'		=> 'STRING',
	'detail_zip'		=> 'STRING',
	'detail_region'		=> 'STRING',
	'detail_country'	=> 'STRING',
	'detail_fon'		=> 'STRING',
	'detail_fax'		=> 'STRING',
	'detail_mobile'		=> 'STRING',
	'detail_signature'	=> 'STRING',
	'detail_website'	=> 'STRING',
	'detail_email'		=> 'STRING',
	'detail_public'		=> 'CHECK',
	'detail_aktiv'		=> 'CHECK'
	
	);



if(isset($_GET['edit'])) {
	$plugin['id']		= intval($_GET['edit']);
} else {
	$plugin['id']		= 0;
}


// process post form
if(isset($_POST['detail_firstname'])) {

	$plugin['data'] = array(
	
			'detail_id'		=> intval($_POST['detail_id'])

								);
								
	foreach($plugin['fields'] as $key => $value) {
	
		switch($value) {
		
			case 'STRING':	$plugin['data'][$key] = clean_slweg($_POST[$key]);
							break;
							
			case 'CHECK':	$plugin['data'][$key] = empty($_POST[$key]) ? 0 : 1;
							break;
		
		}
	
	}
	
					
	
	if(!isset($plugin['error'])) {
	
		if($plugin['data']['detail_id']) {
		
			// UPDATE
			$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_userdetail SET ';
			
			$sql_fields = array();
			
			foreach($plugin['fields'] as $key => $value) {
				
				$sql_fields[] = $key."='".aporeplace($plugin['data'][$key])."'";

			}
			
			//$sql_fields[] = "detail_password='".aporeplace($plugin['data']['detail_password'])."'";
			$sql_fields[] = "detail_login='".aporeplace($plugin['data']['detail_login'])."'";
			
			$sql .= implode(', ', $sql_fields);
			
			$sql .= "WHERE detail_id=".$plugin['data']['detail_id'];
			
			if(@_dbQuery($sql, 'UPDATE')) {
			
				if($plugin['data']['detail_aktiv']) {
				
					sendActivationEmail( array(
							
						'detail_login'		=> $plugin['data']['detail_login'],
						'detail_email'		=> $plugin['data']['detail_email'],
						'detail_title'		=> $plugin['data']['detail_title'],
						'detail_firstname'	=> $plugin['data']['detail_firstname'],
						'detail_lastname'	=> $plugin['data']['detail_lastname']
					) );
				}
							
			
				if(isset($_POST['save'])) {
					
					headerRedirect(decode_entities(MODULE_HREF));
					
				}
			
			} else {
			
				$plugin['error']['update'] = 'MySQL error: '.mysql_error();
			
			}
			
		
		} else {
		
			// INSERT
			$sql_fields = $plugin['fields'];
			$sql_fields['detail_password'] = md5($sql_fields['detail_email']);
			$sql_fields['detail_login'] = '';			
			$sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_userdetail (';
			foreach($sql_fields as $key => $value) {
				
				$sql_fields[$key] = $key;

			}
			$sql .= implode(', ', $sql_fields);
			$sql .= ') VALUES (';
			foreach($sql_fields as $key => $value) {
				
				$sql_fields[$key] = "'".aporeplace($plugin['data'][$key])."'";

			}
			$sql .= implode(', ', $sql_fields);
			
			$sql .= ')';
			
			if(@_dbQuery($sql, 'INSERT')) {
			
				if(isset($_POST['save'])) {
					
					headerRedirect(decode_entities(MODULE_HREF));
					
				}
			
			} else {
			
				$plugin['error']['update'] = 'MySQL error: '.mysql_error();
			
			}
		
		
		}
	}

}

// try to read entry from database
if($plugin['id'] && !isset($plugin['error'])) {

	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_userdetail WHERE detail_id='.$plugin['id'].' AND detail_pid=0';
	$plugin['data'] = _dbQuery($sql);
	$plugin['data'] = $plugin['data'][0];
	
}

// default values
if(empty($plugin['data'])) {

	$plugin['data'] = array(
	
		'detail_id'		=> 0
					
								);
	foreach($plugin['fields'] as $key => $value) {
	
		switch($value) {
		
			case 'STRING':	$plugin['data'][$key] = '';
							break;
							
			case 'CHECK':	$plugin['data'][$key] = 0;
							break;
		
		}
	
	}

}


?>