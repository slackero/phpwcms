<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
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


// Get INI values
if(is_file(PHPWCMS_TEMPLATE.'inc_script/felogin/felogin.ini.php')) {

	$FELOGIN = parse_ini_file(PHPWCMS_TEMPLATE.'inc_script/felogin/felogin.ini.php', TRUE);
	
	if(isset($FELOGIN['FELOGIN_LEVEL_DEPTH']) && isset($FELOGIN['FELOGIN_LEVEL_ID'])) {
		
		define('FELOGIN_LEVEL_DEPTH',		 intval($FELOGIN['FELOGIN_LEVEL_DEPTH']));
		define('FELOGIN_LEVEL_ID',			 intval($FELOGIN['FELOGIN_LEVEL_ID']));
		
		define('FELOGIN_CHILD_LEVEL', 		 FELOGIN_LEVEL_DEPTH + 1);
		define('FELOGIN_CHILD_SUBLEVEL', 	 FELOGIN_LEVEL_DEPTH + 2);
		
		define('FELOGIN_LOGOUT_LINK',		 empty($FELOGIN['FELOGIN_LOGOUT_LINK']) ? 0 : trim($FELOGIN['FELOGIN_LOGOUT_LINK']) );
		define('FELOGIN_LOGOUT_GET_VALUE',	 empty($FELOGIN['FELOGIN_LOGOUT_GET_VALUE']) ? 'yes' : trim($FELOGIN['FELOGIN_LOGOUT_GET_VALUE']) );
		
		define('FELOGIN_LOGOUT_LINK_PREFIX', empty($FELOGIN['FELOGIN_LOGOUT_LINK_PREFIX']) ? '' : cleandblsquote($FELOGIN['FELOGIN_LOGOUT_LINK_PREFIX']) );
		define('FELOGIN_LOGOUT_LINK_SUFFIX', empty($FELOGIN['FELOGIN_LOGOUT_LINK_SUFFIX']) ? '' : cleandblsquote($FELOGIN['FELOGIN_LOGOUT_LINK_SUFFIX']) );
		
		define('FELOGIN_ERROR_PREFIX',		 empty($FELOGIN['FELOGIN_ERROR_PREFIX']) ? '<p class="error">' : cleandblsquote($FELOGIN['FELOGIN_ERROR_PREFIX']) );
		define('FELOGIN_ERROR_SUFFIX',		 empty($FELOGIN['FELOGIN_ERROR_SUFFIX']) ? '</p>' : cleandblsquote($FELOGIN['FELOGIN_ERROR_SUFFIX']) );
		
		define('FELOGIN_ERROR_EMPTY_USER',	 empty($FELOGIN['FELOGIN_ERROR_EMPTY_USER']) ? 'Insert your username' : $FELOGIN['FELOGIN_ERROR_EMPTY_USER']);
		define('FELOGIN_ERROR_UNKNOWN_USER', empty($FELOGIN['FELOGIN_ERROR_UNKNOWN_USER']) ? 'Please proof, the user is unknow' : $FELOGIN['FELOGIN_ERROR_UNKNOWN_USER']);
		define('FELOGIN_ERROR_EMPTY_PASS',	 empty($FELOGIN['FELOGIN_ERROR_EMPTY_PASS']) ? 'Insert your password' : $FELOGIN['FELOGIN_ERROR_EMPTY_PASS']);
		define('FELOGIN_ERROR_WRONG_PASS',	 empty($FELOGIN['FELOGIN_ERROR_WRONG_PASS']) ? 'Wrong password' : $FELOGIN['FELOGIN_ERROR_WRONG_PASS']);
	
	}

}

// check if we are in right section
if(	defined('FELOGIN_LEVEL_DEPTH') && isset($LEVEL_ID[FELOGIN_LEVEL_DEPTH]) && $LEVEL_ID[FELOGIN_LEVEL_DEPTH] == FELOGIN_LEVEL_ID) {

	if(isset($_GET['logout']) && $_GET['logout'] == FELOGIN_LOGOUT_GET_VALUE) {
		unset($_SESSION['FELOGIN_IS_LOGGED'], $_SESSION['FELOGIN_USER_NAME']);
		headerRedirect( PHPWCMS_URL . 'index.php' . (isset($LEVEL_ID[FELOGIN_CHILD_LEVEL]) ? '?id='.$LEVEL_ID[FELOGIN_CHILD_LEVEL] : ''), 401);
	}

	$FELOGIN_ERROR = array();
	
	if(isset($LEVEL_ID[FELOGIN_CHILD_LEVEL]) && isset( $FELOGIN[ $LEVEL_ID[FELOGIN_CHILD_LEVEL] ] ) ) {
	
		$FELOGIN = $FELOGIN[ $LEVEL_ID[FELOGIN_CHILD_LEVEL] ];
				
		// OK, user is trying to login
		if(isset($_POST['feSubmit'])) {
		
			$FELOGIN_USER_NAME = empty($_POST['feLogin']) ? '' : slweg($_POST['feLogin']);
			$FELOGIN_USER_PASS = empty($_POST['fePassword']) ? '' : slweg($_POST['fePassword']);
			
			if(empty($FELOGIN_USER_NAME)) {
				
				$FELOGIN_ERROR[] = FELOGIN_ERROR_EMPTY_USER;
				unset($_SESSION['FELOGIN_IS_LOGGED'], $_SESSION['FELOGIN_USER_NAME']);
				
			} elseif( !isset($FELOGIN[ $FELOGIN_USER_NAME ]) ) {
				
				$FELOGIN_ERROR[] = FELOGIN_ERROR_UNKNOWN_USER;
				unset($_SESSION['FELOGIN_IS_LOGGED'], $_SESSION['FELOGIN_USER_NAME']);
			
			} else {
			
				$_SESSION['FELOGIN_USER_NAME'] = $FELOGIN_USER_NAME;
				
				if(empty($FELOGIN_USER_PASS)) {
				
					$FELOGIN_ERROR[] = FELOGIN_ERROR_EMPTY_PASS;
					unset($_SESSION['FELOGIN_IS_LOGGED']);
				
				} elseif($FELOGIN[ $FELOGIN_USER_NAME ] !== $FELOGIN_USER_PASS) {
				
					$FELOGIN_ERROR[] = FELOGIN_ERROR_WRONG_PASS;
					unset($_SESSION['FELOGIN_IS_LOGGED']);
				
				} else {
				
					$_SESSION['FELOGIN_IS_LOGGED'] = $LEVEL_ID[FELOGIN_CHILD_LEVEL];
				
				}

			}
		
		}		
		
		
		// check if the user is logged in
		if( empty($_SESSION['FELOGIN_IS_LOGGED']) || $_SESSION['FELOGIN_IS_LOGGED'] != $LEVEL_ID[FELOGIN_CHILD_LEVEL] ) {
		
			// if user has opened deeper level, deny permission
			if(isset($LEVEL_ID[FELOGIN_CHILD_SUBLEVEL])) {
			
				headerRedirect( PHPWCMS_URL . 'index.php?id=' . $LEVEL_ID[FELOGIN_CHILD_LEVEL], 401);
			
			}
		
			foreach( $content['struct'] as $key => $value ) {
			
				if( $content['struct'][$key]['acat_struct'] == $LEVEL_ID[FELOGIN_CHILD_LEVEL] ) {
					$content['struct'][$key]['acat_regonly'] = 1;
				}
			
			}
			
			define('FELOGIN_IS_LOGGED', false);
			
		} else {
		
			define('FELOGIN_IS_LOGGED', true);
			
			if(FELOGIN_LOGOUT_LINK) {
				$content['struct'][100000] = array(
		
					'acat_id' => 100000,
					'acat_name' => FELOGIN_LOGOUT_LINK,
					'acat_info' => '',
					'acat_struct' => FELOGIN_LEVEL_ID,
					'acat_sort' => 10000,
					'acat_hidden' => 0,
					'acat_regonly' => 0,
					'acat_ssl' => 0,
					'acat_template' => 1,
					'acat_alias' => '',
					'acat_topcount' => -1,
					'acat_maxlist' => 0,
					'acat_redirect' => 'index.php?id='.FELOGIN_LEVEL_ID.'&logout='.FELOGIN_LOGOUT_GET_VALUE,
					'acat_order' => 0,
					'acat_timeout' => '',
					'acat_nosearch' => '',
					'acat_nositemap' => 1,
					'acat_permit' => array(),				
					'acat_pagetitle' => '',
					'acat_paginate' => 0,
					'acat_overwrite' => ''
				);
			
			}
		
		}

	}

}

function cleandblsquote($string) {
	return trim( str_replace("''", '"', $string) );
}

?>