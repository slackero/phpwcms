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

// general wrapper for ajax based queries

session_start();
$phpwcms = array();
require('../../config/phpwcms/conf.inc.php');
require('../inc_lib/default.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');

if(empty($_SESSION["wcs_user"])) {
	die('Sorry, access forbidden');
	//require(PHPWCMS_ROOT.'/include/inc_lib/checklogin.inc.php');
}

$action		= isset($_POST['action']) ? $_POST['action'] : false;
$method		= isset($_POST['method']) ? $_POST['method'] : 'json';
$value		= isset($_POST['value']) ? $_POST['value'] : 'json';

// do charset conversions for value
if(PHPWCMS_CHARSET != 'utf-8') {

	if(function_exists('mb_convert_encoding')) {
	
		$value = @mb_convert_encoding( $value, PHPWCMS_CHARSET, 'utf-8' );
	
	} else {

		$value = utf8_decode($value);

	}

}

$data		= array();

switch($action) {

	case 'category':	$where  = "cat_status=1 AND cat_type NOT IN('module_shop') AND ";
						$where .= "cat_name LIKE '" . aporeplace( preg_replace('/[^\w\- ]/', '', $value) ) . "%'";
						$result = _dbGet('phpwcms_categories', 'cat_name', $where, 'cat_name', 'cat_name', 20);

						if(isset($result[0])) {

							foreach($result as $value) {
								$data[] = utf8_encode($value['cat_name']);
							}
						}
						break;
						
	case 'lang':		$data = is_array($phpwcms['allowed_lang']) && count($phpwcms['allowed_lang']) ? $phpwcms['allowed_lang'] : array($phpwcms['default_lang']);
						sort($data);
						break;

}

switch($method) {
	
	
	default:	header('Content-type: application/json');
				if(!function_exists('json_encode')) {
					
					require(PHPWCMS_ROOT.'/include/inc_ext/JSON/JSON.php');
					$json = new Services_JSON();

					echo $json->encode( $data );
				
				} else {

					echo json_encode( $data );
				
				}
				

}


?>