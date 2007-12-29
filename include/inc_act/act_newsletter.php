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

/*
if(empty($phpwcms)) $phpwcms = array();
require_once ('../../config/phpwcms/conf.inc.php');
require_once ('../inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');


$type	= '';
$email	= 'n.a.';
$target = '';

/ *
if(!empty($_GET['i'])) {
	
	$verify		= explode(':', base64_decode(clean_slweg($_GET["i"])));
	$email		= empty($verify[2]) ? '?' : $verify[2];
	$email		= @htmlentities(strip_tags($email), ENT_QUOTES, PHPWCMS_CHARSET);

	if(isset($verify[1]) && ($verify[0] == 'V' || $verify[0] == 'D')) {
	
		$hash = clean_slweg($verify[1]);
		$data = _dbQuery('SELECT * FROM '.DB_PREPEND."phpwcms_address WHERE address_key='".aporeplace($hash)."' LIMIT 1");

		switch($verify[0]) {
	
			case "V":	$sql  = "UPDATE ".DB_PREPEND."phpwcms_address SET ".
								"address_verified=1 ".
								"WHERE address_key='".aporeplace($hash)."' LIMIT 1;";
						
						$type = 'subscribe';

						break;
	
			case "D":	$sql  = "DELETE FROM ".DB_PREPEND."phpwcms_address ".
								"WHERE address_key='".aporeplace($hash)."' LIMIT 1;";
						
						$type = 'unsubscribe'; 
						
						break;

		}
		@mysql_query($sql, $db);

	}
	

} else
* /
if(!empty($_GET['s']) || !empty($_GET['u'])) {
	
	if(isset($_GET['s'])) {
	
		$hash = clean_slweg($_GET['s']);
		$type = 'subscribe';
	
	} else {
	
		$hash = clean_slweg($_GET['u']);
		$type = 'unsubscribe';
		
	}

	$data = _dbQuery('SELECT * FROM '.DB_PREPEND."phpwcms_address WHERE address_key='".aporeplace($hash)."' LIMIT 1");

	if(isset($data[0])) {
	
		$email = $data[0]['address_email'];
		switch($type) {
		
			case 'subscribe':		$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_address ';
									$sql .= 'SET address_verified=1 ';
									$sql .= "WHERE address_key='".aporeplace($hash)."'";
									if(empty($data[0]['address_verified'])) {
										$result = _dbQuery($sql, 'UPDATE');
									}
									if(!empty($data[0]['address_url1'])) {
										$target = $data[0]['address_url1'];
									}
									
									break;
									
			
			case 'unsubscribe':		$sql  = 'DELETE FROM '.DB_PREPEND.'phpwcms_address ';
									$sql .= "WHERE address_key='".aporeplace($hash)."'";
									$result = _dbQuery($sql, 'DELETE');
									
									if(!empty($data[0]['address_url2'])) {
										$target = $data[0]['address_url2'];
									}
									
									break;
									
		}
		
	
	}
	
	//redirect
	if($target) {
		headerRedirect($target);
	}
	
}


switch($type) {

	case 'subscribe':	
						if(!($page = file_get_contents(PHPWCMS_TEMPLATE.'inc_default/subscribe.tmpl'))) {
						
							$page = "The email address <strong>{EMAIL}</strong> was verified.";
						
						}
						break;


	case 'unsubscribe':
						if(!($page = file_get_contents(PHPWCMS_TEMPLATE.'inc_default/unsubscribe.tmpl'))) {
							
							$page = "All Subscriptions for <strong>{EMAIL}</strong> canceled.";
						
						}
						break;
						
	default:
	
						headerRedirect(PHPWCMS_URL);

}


// some replacements
$page = replaceGlobalRT($page);
$page = str_replace('{EMAIL}', $email, $page);

// send non caching page header
headerAvoidPageCaching();
echo $page;

*/
?>