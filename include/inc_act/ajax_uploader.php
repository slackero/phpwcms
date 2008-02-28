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

// general wrapper for ajax based uploads

session_start();
$phpwcms = array();
require('../../config/phpwcms/conf.inc.php');
require('../inc_lib/default.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require(PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
$login = checkLogin('');

$log = @fopen(PHPWCMS_ROOT.$phpwcms["ftp_path"].'.multiple.upload.log', 'a');
$status = array();

if($login == false) {
	
	$error	= 'FAILED ';
	$msg	= ' - 401 Unauthorized';
	$status['name'] = '';
	$status['size'] = 0;

} else {

	$status = saveUploadedFile('Filedata', PHPWCMS_ROOT.$phpwcms["ftp_path"]);
	
}

if($log) {

	if(isset($status['status']) && $status['status'] == true) {
		$error	= 'SUCCESS';
		$msg	= '';
	} elseif(isset($status['error'])) {
		$error	= 'FAILED ';
		$msg	= ' - ' . $status['error'];
	} elseif($login) {
		$error	= 'FAILED ';
		$msg	= '';
	}

	fputs($log, date('Y-m-d H:i:s') . ' - ' . $error . ' - ' . getRemoteIP() . ': ' . $status['name'] . ' ('. $status['size'] .' byte)' . $msg . LF );
	fclose($log);	

}

if(isset($status['status']) && $status['status'] == true) {

	die('Upload Successfull');

} elseif(isset($status['error'])) {

	header('HTTP/1.1 405 '.$status['error']);
	die($status['error']);

} elseif($login) {

	header('HTTP/1.1 409 Conflict');
	die('General error');

} else {

	header('HTTP/1.1 401 Unauthorized');
	die('Sorry, Access forbidden!');

}


?>