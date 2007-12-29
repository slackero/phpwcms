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

$newsletter										= array();

// should show newsletter form
$newsletter["newsletter_id"] 					= intval($_GET["s"]);
$newsletter["newsletter_subject"] 				= '';
$newsletter["newsletter_date"] 					= time();
if(!isset($newsletter["newsletter_vars"])) {
	$newsletter["newsletter_vars"] 				= array();
}
$newsletter["newsletter_vars"]['from_name'] 	= '';
$newsletter["newsletter_vars"]['from_email'] 	= '';
$newsletter["newsletter_vars"]['replyto'] 		= '';
$newsletter["newsletter_vars"]['html'] 			= '';
$newsletter["newsletter_vars"]['text'] 			= '';
$newsletter["newsletter_active"]				= 0;

if(!empty($_GET["del"]) && intval($_GET["del"]) == $newsletter["newsletter_id"]) {

	//delete newsletter now
	$sql  = "UPDATE ".DB_PREPEND."phpwcms_newsletter SET newsletter_trashed=9 ";
	$sql .= "WHERE newsletter_id=".intval($_GET["del"])." LIMIT 1;";
	mysql_query($sql, $db) or die("error while deleting newsletter");
	headerRedirect(PHPWCMS_URL."phpwcms.php?do=messages&p=3");
}

if(isset($_POST["newsletter_id"])) {
	// read the create or edit subscription form data
	$newsletter["newsletter_id"]					= intval($_POST["newsletter_id"]);
	$newsletter["newsletter_subject"]				= clean_slweg($_POST["newsletter_subject"]);
	if(!$newsletter["newsletter_subject"]) 	$newsletter['error']['subject'] = 1;
	
	$newsletter['newsletter_vars']['from_name']		= clean_slweg($_POST["newsletter_fromname"]);
	$newsletter['newsletter_vars']['from_email']	= clean_slweg($_POST["newsletter_fromemail"]);
	if(!is_valid_email($newsletter['newsletter_vars']['from_email'])) {
		$newsletter['error']['from_email'] 			= 1;
	}
	$newsletter['newsletter_vars']['replyto']		= clean_slweg($_POST["newsletter_replyto"]);
	if(!is_valid_email($newsletter['newsletter_vars']['replyto'])) {
		$newsletter['error']['replyto'] 			= 1;
	}
	
	$newsletter['newsletter_vars']['html']			= slweg($_POST["newsletter_html"]);
	$newsletter['newsletter_vars']['text']			= clean_slweg($_POST["newsletter_text"]);
	$newsletter["newsletter_vars"]['template']		= clean_slweg($_POST["newsletter_template"]);
	
	$newsletter['newsletter_active']				= empty($_POST['newsletter_active']) ? 0 : 1;
	
	if(!empty($_POST['newsletter_subscription']) && count($_POST['newsletter_subscription'])) {
		foreach($_POST['newsletter_subscription'] as $value) {
			$value = intval($value);
			if($value) {
				$newsletter['newsletter_vars']['subscription'][$value] = intval($value);
			} else {
				unset($newsletter['newsletter_vars']['subscription']);
				$newsletter['newsletter_vars']['subscription'][0] = 0;
				break;
			}
		}
	} else {
		$newsletter['newsletter_vars']['subscription'][0] = 0;
	}
	
	$sql  = "newsletter_subject='".aporeplace($newsletter["newsletter_subject"])."', ";
	$sql .= "newsletter_vars='".aporeplace(serialize($newsletter['newsletter_vars']))."' ";
	
	if($newsletter["newsletter_id"]) {
		$sql  = "UPDATE ".DB_PREPEND."phpwcms_newsletter SET ".$sql;
		$sql .= "WHERE newsletter_id=".$newsletter["newsletter_id"]." LIMIT 1";	
	} else {
		$sql  = "INSERT INTO ".DB_PREPEND."phpwcms_newsletter SET newsletter_created=NOW(), ".$sql;
	}
	
	if(!isset($newsletter['error'])) {
	
		// update or insert data entry
		mysql_query($sql, $db) or die("error while updating or inserting newsletter datas");
		if(!$newsletter["newsletter_id"]) $newsletter["newsletter_id"] = mysql_insert_id($db);
	
		// check recipients and subscriptions for building newsletter sending queue
		if($newsletter['newsletter_active']) {
	
			@set_time_limit(0);
	
			if($recipients = _dbQuery('SELECT * FROM '.DB_PREPEND.'phpwcms_address WHERE address_verified=1')) {
		
				$queue = array();
			
				foreach($recipients as $value) {
				
					// check which subscription and compare with recipient
				
					// check against "all"
					if(empty($value['address_subscription'])) {
					
						$queue[] = '(NOW(), NOW(), 0, '.$newsletter["newsletter_id"].', '.$value['address_id'].')';
					
					} else {
					
						$value['address_subscription']		= unserialize($value['address_subscription']);
					
						// run all
						foreach($value['address_subscription'] as $subscr) {
						
							$subscr = intval($subscr);
							if(in_array($subscr, $newsletter['newsletter_vars']['subscription'])) {
						
								$queue[] = '(NOW(), NOW(), 0, '.$newsletter["newsletter_id"].', '.$value['address_id'].')';
						
								break;
							}
						
						}
						
					}
					
					unset($recipients);
										
				}
				
				// create entries in the sending queue
				
				/* queue_status:
				   [0] = unsent
				   [1] = sent
				   [2] = error
				   [3] = reset, will never be sent
				*/
				// first reset all unsent queue entries
				
				$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_newsletterqueue SET ';
				$sql .= 'queue_changed=NOW(), queue_status=3 ';
				$sql .= 'WHERE queue_pid='.$newsletter["newsletter_id"].' AND queue_status=0';
				_dbQuery($sql, 'UPDATE');

				// now insert queue entries into db
				$queue = array_chunk($queue, 2);
				foreach($queue as $value) {
				
					$sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_newsletterqueue ';
					$sql .= '(queue_created, queue_changed, queue_status, queue_pid, queue_rid) VALUES ';
					$sql .= implode(', ', $value);
					
					_dbQuery($sql, 'INSERT');
				
				}
				
			}

		} else {
		
			// if unmarked -> first remove all unset recipients from queue for same newsletter
			$sql  = 'DELETE FROM '.DB_PREPEND.'phpwcms_newsletterqueue ';
			$sql .= 'WHERE queue_pid='.$newsletter["newsletter_id"].' AND queue_status=0';
			_dbQuery($sql, 'DELETE');
		
		}
	
		// update active status
		$sql  = "UPDATE ".DB_PREPEND.'phpwcms_newsletter SET ';
		$sql .= 'newsletter_active='.$newsletter['newsletter_active'].' ';
		$sql .= "WHERE newsletter_id=".$newsletter["newsletter_id"];
		@_dbQuery($sql, 'UPDATE');

		if(isset($_POST['close'])) {
			headerRedirect(PHPWCMS_URL.'phpwcms.php?do=messages&p=3');
		} else {
			headerRedirect(PHPWCMS_URL.'phpwcms.php?do=messages&p=3&s='.$newsletter["newsletter_id"].'&edit=1');
		}
	}
}

if($newsletter["newsletter_id"] && !isset($_POST["newsletter_id"])) {
// read the given subscription datas from db
	$sql  = "SELECT *, UNIX_TIMESTAMP(newsletter_changed) AS newsletter_date FROM ";
	$sql .= DB_PREPEND."phpwcms_newsletter WHERE newsletter_id=".$newsletter["newsletter_id"]." LIMIT 1;";
	if($result = mysql_query($sql, $db)) {
		if($row = mysql_fetch_assoc($result)) {
			$newsletter = $row;
			$newsletter['newsletter_vars'] = unserialize($newsletter['newsletter_vars']);
		}
		mysql_free_result($result);
	}
}

if($newsletter["newsletter_id"] && ($newsletter["newsletter_vars"]['html'] || $newsletter["newsletter_vars"]['text']) && !isset($newsletter['error'])) {
	$show_nl_send = 1;
} else {
	$show_nl_send = 0;
}

?>