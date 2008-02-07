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


/*
 * module florist user
 * ===================
 *
 *
 */
 
// first check if neccessary db exists
if(isset($phpwcms['modules'][$module]['path'])) {

	// module default stuff
	
	// put translation back to have easier access to it - use it as relation
	$BLM = & $BL['modules'][$module];
	define('MODULE_HREF', 'phpwcms.php?do=modules&amp;module='.$module);
	$glossary = array();


	if(isset($_GET['edit'])) {
	
		// handle posts and read data
		include_once($phpwcms['modules'][$module]['path'].'inc/processing.inc.php');
	
		// edit form
		include_once($phpwcms['modules'][$module]['path'].'backend.editform.php');
		
	} elseif(isset($_GET['verify'])) {
	
		// active/inactive
		$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_userdetail SET ';
		$sql .= "detail_aktiv=".(intval($_GET['verify']) ? 1 : 0)." ";
		$sql .= "WHERE detail_id=".intval($_GET['editid']);
		@_dbQuery($sql, 'UPDATE');
		
		// send activation email
		$udata = _dbQuery('SELECT * FROM '.DB_PREPEND.'phpwcms_userdetail WHERE detail_id='.intval($_GET['editid']).' LIMIT 1');
		if(isset($udata[0]) && intval($_GET['verify'])) {
		
			sendActivationEmail($udata[0]);		
	
		}
		
		headerRedirect(decode_entities(MODULE_HREF));
	
	} elseif(isset($_GET['delete'])) {
	
		// delete
		$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_userdetail SET ';
		$sql .= "detail_aktiv=9 WHERE detail_id=".intval($_GET['delete']);
		@_dbQuery($sql, 'UPDATE');		
		headerRedirect(decode_entities(MODULE_HREF));
	
	} else {
	
		// listing
		include_once($phpwcms['modules'][$module]['path'].'backend.listing.php');
		
	}
	
}


function sendActivationEmail($udata) {
	$fe_text  = 'Hallo '.trim($udata['detail_title'] . ' ' . trim( $udata['detail_firstname'].' '.$udata['detail_lastname']) ) . LF . LF;
	$fe_text .= 'Ihr Zugang zum Azlan HP ProCurve Partnerbereich wurde soeben freigeschaltet.' . LF . LF;
	$fe_text .= 'Besuchen Sie gleich unsere Homepage und loggen Sie sich ein:' . LF;
	$fe_text .= PHPWCMS_URL . LF . LF;
	$fe_text .= 'Ihr Login lautet: ' . $udata['detail_login'] . LF . LF . LF;
	$fe_text .= 'Mit besten Grьяen' . LF;
	$fe_text .= 'Azlan' . LF;
	
	sendEmail(array(
			'recipient'		=> strtolower($udata['detail_email']),
			'toName'		=> trim($udata['detail_firstname'].' '.$udata['detail_lastname']),
			'subject'		=> 'Freischaltung der Partner Registrierung Azlan HP ProCurve',
			'text'			=> $fe_text,
			'from'			=> 'partnerregistrierung@azlan.de',
			'fromName'		=> 'Azlan HP ProCurve',
			'sender'		=> 'partnerregistrierung@azlan.de' ));
}

?>