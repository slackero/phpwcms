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

@ini_set( 'arg_separator.output' , '&amp;' );

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_INCLUDE_CHECK')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

define ('PHPWCMS_CHARSET', 	empty($phpwcms["charset"]) ? 'utf-8' : strtolower($phpwcms["charset"]));

/* seems to be problematic at the moment - so always use text/html
if (!empty($phpwcms['header_XML']) && $_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1' && isset($_SERVER['HTTP_ACCEPT']) && preg_match('|application/xhtml\+xml(?!\s*;\s*q=0)|', $_SERVER['HTTP_ACCEPT'])) {
	header('Content-Type: application/xhtml+xml; charset='.PHPWCMS_CHARSET);
	header('Vary: Negotiate, Accept');
	$_use_content_type = 'application/xhtml+xml';
} else {
*/
	header('Content-Type: text/html; charset='.PHPWCMS_CHARSET);
//	header('Vary: Negotiate, Accept');
	$_use_content_type = 'text/html';
//}
//header ('Content-Type: text/html; charset='.PHPWCMS_CHARSET);

// define the real path of the phpwcms installation
// important to script that must know the real path to files or something else

$phpwcms['DOC_ROOT'] = str_replace("\\", '/', $phpwcms['DOC_ROOT']);
if(substr($phpwcms['DOC_ROOT'], -1, 1) == '/') {
	$phpwcms['DOC_ROOT'] = substr($phpwcms['DOC_ROOT'], 0, -1);
}
if(!empty($phpwcms["root"])) {
	$phpwcms['DOC_ROOT']		.= 	'/'.$phpwcms["root"];
	$phpwcms["root"]			.= 	'/';
}

define ("PHPWCMS_ROOT", 			$phpwcms['DOC_ROOT']);
define ('PHPWCMS_FILES', 			$phpwcms["file_path"].'/');
define ('PHPWCMS_BASEPATH',			'/'.$phpwcms["root"]);
define ('On',						true);
define ('Off',						false);
define ('PHPWCMS_USER_KEY',			md5(getRemoteIP().$phpwcms['DOC_ROOT'].$phpwcms["db_pass"]));
define ('PHPWCMS_REWRITE_EXT',		'phtml');

$phpwcms['browser_detect']		=	phpwcms_getUserAgent();
define('BROWSER_NAME',				$phpwcms['browser_detect']['agent']);
define('BROWSER_NUMBER',			$phpwcms['browser_detect']['version']);
define('BROWSER_OS',				$phpwcms['browser_detect']['platform']);

$phpwcms["file_path"]    		= 	'/'.$phpwcms["file_path"].'/' ;  // "/phpwcms_filestorage/"

define ('TEMPLATE_PATH', 			$phpwcms["templates"].'/');
$phpwcms["templates"]    		= 	'/'.$phpwcms["templates"].'/' ;  // "/phpwcms_template/"
$phpwcms["content_path"] 		= 	$phpwcms["content_path"].'/'  ;  // "content/"
define ('CONTENT_PATH',				$phpwcms["content_path"]);
$phpwcms["cimage_path"]  		= 	$phpwcms["cimage_path"].'/'   ;  // "images/"
$phpwcms["ftp_path"]     		= 	'/'.$phpwcms["ftp_path"].'/'  ;  // "/phpwcms_ftp/"

define ('PHPWCMS_TEMPLATE', 		PHPWCMS_ROOT.$phpwcms["templates"]);
define ('PHPWCMS_URL', 				$phpwcms["site"].$phpwcms["root"]);
define ('PHPWCMS_IMAGES', 			$phpwcms["content_path"].'images/');
define ('PHPWCMS_TEMP', 			PHPWCMS_ROOT.'/'.$phpwcms["content_path"].'tmp/');
define ('PHPWCMS_CONTENT',			PHPWCMS_ROOT.'/'.$phpwcms["content_path"]);
define ('MAGPIE_DIR', 				PHPWCMS_ROOT.'/include/inc_ext/magpierss/');
define ('MAGPIE_CACHE_DIR', 		PHPWCMS_ROOT.'/content/rss');
define ('MAGPIE_OUTPUT_ENCODING', 	PHPWCMS_CHARSET);
define ('LF', 						"\n"); 	//global new line Feed

define ('MB_SAFE',					function_exists('mb_substr') ? true : false); //mbstring safe - better to do a check here

$phpwcms['modules']				 = array();
$phpwcms['modules_fe_render']	 = array();
$phpwcms['modules_fe_init']		 = array();

// check which function should be used to create thumbnail images
// and if ImageMagick check if enabled or 1 or located at give path
if($phpwcms["imagick_path"]) {
	$phpwcms["imagick_path"] = $phpwcms["imagick_path"].'/';
	$phpwcms["imagick_path"] = str_replace("\\", '/', $phpwcms["imagick_path"]);
	$phpwcms["imagick_path"] = str_replace('//', '/', $phpwcms["imagick_path"]);
}
define ("IMAGICK_PATH",	$phpwcms["imagick_path"]);
define ("IMAGICK_ON", intval($phpwcms["imagick"]));
define ("GD2_ON", intval($phpwcms["use_gd2"]));

if(empty($phpwcms['SMTP_MAILER'])) {
	$phpwcms['SMTP_MAILER'] = 'mail';
}
if(empty($phpwcms['SMTP_FROM_EMAIL'])) {
	$phpwcms['SMTP_FROM_EMAIL'] = $phpwcms["admin_email"];
}


if(empty($phpwcms['mode_XHTML'])) {

	define('PHPWCMS_DOCTYPE', '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'.LF.'<html>'.LF.'<head>'.LF);
	define('SCRIPT_CDATA_START', '  <!-- ');
	define('SCRIPT_CDATA_END'  , '  //-->');
	define('HTML_TAG_CLOSE'  , '>');
	define('XHTML_MODE', false);
	
} else {

	define('PHPWCMS_DOCTYPE', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.LF.'<html xmlns="http://www.w3.org/1999/xhtml">'.LF.'<head>'.LF);
//	define('SCRIPT_CDATA_START', '  /* <![CDATA[ */');
//	define('SCRIPT_CDATA_END'  , '  /* ]]> */');
	define('SCRIPT_CDATA_START', '  <!-- ');
	define('SCRIPT_CDATA_END'  , '  //-->');
	define('HTML_TAG_CLOSE'  , ' />');
	define('XHTML_MODE', true);
	
}

$phpwcms["release"] = '1.3.9';
$phpwcms["release_date"] = '2008/04/07';

// -------------------------------------------------------------

function removeSessionName($str='') {
	// is used to remove all &hashID=...
	// not useful when when storing in cache
	// because it stores unneccessary session IDs too
	$sessName = session_name();
	$str = preg_replace('/[&|\?]{0,1}'.$sessName.'=[a-zA-Z0-9]{1,}/', '', $str);
	return $str;
}


function buildGlobalGET($return = '') {
	// build internal array containing all GET values
	// and remove session from this array
	$GLOBALS['_getVar'] = array();
	
	$_queryVal		= empty($_SERVER['QUERY_STRING']) ? array() : explode('&', $_SERVER['QUERY_STRING']);
	$_queryCount	= count($_queryVal);
	$_getCount		= is_array($_GET) ? count($_GET) : 0;
	
	if($_getCount && $_getCount >= $_queryCount) {
		$GLOBALS['_getVar'] = $_GET;
	} elseif($_queryCount) {
		foreach($_queryVal as $value) {
			$key = explode('=', $value);
			$val = empty($key[1]) ? '' : $key[1];
			$key = $key[0];
			$GLOBALS['_getVar'][$key] = $val;
		}
	}
	
	unset(	$_GET[session_name()], 
			$GLOBALS['_getVar'][session_name()], 
			$GLOBALS['_getVar']['']
		  );
	
	if($return == 'getQuery') {
		return returnGlobalGET_QueryString('htmlentities');
	}
}

function returnGlobalGET_QueryString($format='', $add=array(), $remove=array()) {
	// build a URL query string
	$queryString = array();
	$glue = '&';
	$pairs = is_array($add) && count($add) ? array_merge($GLOBALS['_getVar'], $add) : $GLOBALS['_getVar'];
	if(count($remove)) {
		foreach($remove as $value) {
			unset($pairs[$value]);
		}
	}
	
	foreach($pairs as $key => $value) {
	
		switch($format) {
		
			case 'htmlentities':
				if($value != '') {
					$queryString[] = @htmlentities(urlencode($key).'='.str_replace('%2C', ',', urlencode($value)), ENT_QUOTES, PHPWCMS_CHARSET);
				} else {
					$queryString[] = @htmlentities(urlencode($key), ENT_QUOTES, PHPWCMS_CHARSET);
				}
				$glue = '&amp;';
				break;
				
			case 'urlencode':
				if($value != '') {
					$queryString[] = urlencode($key).'='.urlencode($value);
				} else {
					$queryString[] = urlencode($key);
				}
				break;
			
			case 'rawurlencode':
				if($value != '') {
					$queryString[] = rawurlencode($key).'='.rawurlencode($value);
				} else {
					$queryString[] = rawurlencode($key);
				}
				break;
			
			default:
				if($value != '') {
					$queryString[] = $key.'='.$value;
				} else {
					$queryString[] = $key;
				}
		}
	}
	return (count($queryString) ? '?'.implode($glue, $queryString) : '');
}

function cleanupPOSTandGET() {
	// remove possible unsecure PHP replacement tags in GET and POST vars
	if(isset($_POST) && count($_POST)) {
		foreach($_POST as $key => $value) {
			if(!is_array($_POST[$key])) {
				$_POST[$key] = remove_unsecure_rptags($value);
			}		
		}
	}
	if(isset($_GET) && count($_GET)) {
		foreach($_GET as $key => $value) {
			$_GET[$key] = remove_unsecure_rptags($value);
		}
	}
}

function remove_unsecure_rptags($check) {
	// this is for security reasons
	// where you can use input fields for
	// code injection
	
	//remove special replacement tags
	$check = preg_replace('/\{PHP:(.*?)\}/i', '$1', $check);
	$check = preg_replace('/\{PHPVAR:(.*?)\}/si', '$1', $check);
	$check = preg_replace('/\[PHP\](.*?)\[\/PHP\]/si', '$1', $check);
	$check = preg_replace('/\{URL:(.*?)\}/i', '$1', $check);
	$check = str_replace('[PHP]', '[ PHP ]', $check);
	$check = str_replace('[/PHP]', '[ /PHP ]', $check);
	$check = str_replace('{PHP:', '{ PHP :', $check);
	$check = str_replace('{PHPVAR:', '{ PHPVAR :', $check);
	$check = str_replace('{URL:', '{ URL :', $check);
	return $check;
}

function headerRedirect($target='', $type=0) {
	if(isset($_SESSION)) {
		session_write_close();
	}
	switch($type) {
		case 307:	header('HTTP/1.1 307 Temporary Redirect');		break;
		case 401:	header('HTTP/1.1 401 Authorization Required'); 	break;
		case 404:	header('HTTP/1.1 404 Not Found');				break;
		case 503:	header('HTTP/1.1 503 Service Unavailable'); 	break;
		case 301:	header('HTTP/1.1 301 Moved Permanently');		break;
	}
	if($target !== '') {
		header('Location: '.$target);
		exit();
	}
}

function _initSession() {
	if(!session_id()) session_start();
	if(empty($_SESSION['phpwcmsSessionInit']) && function_exists("session_regenerate_id")) {
		session_regenerate_id();
		$_SESSION['phpwcmsSessionInit'] = true;
	}
	return session_id();
}

function getRemoteIP() {
	$IP = 'unknown';
	if (!empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown')) {
		$IP = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown')) {
		$IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$IP = $_SERVER['REMOTE_ADDR'];
	}
	return $IP;
}

/************************************************************************/
/* Openads 2.0                                                          */
/* ===========                                                          */
/*                                                                      */
/* Copyright (c) 2000-2007 by the Openads developers                    */
/* For more information visit: http://www.openads.org                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

function phpwcms_getUserAgent() {

	if(!isset($_SERVER['HTTP_USER_AGENT'])) {
		return array(
			'agent' => 'Other',
			'version' => 0,
			'platform' => 'Other'
		);
	}

	if(preg_match('#MSIE ([0-9].[0-9]{1,2})(.*Opera ([0-9].[0-9]{1,2}))?#', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
		if(isset($log_version[3])) {
			$ver = $log_version[3];
			$agent = 'Opera';
		} else {
			$ver = $log_version[1];
			$agent = 'IE';
		}
	} elseif (preg_match('#Opera[/ ]([0-9].[0-9]{1,2})#', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
		$ver = $log_version[1];
		$agent = 'Opera';
	} elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Safari') && preg_match('#Safari/([0-9]{1,3})#', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
		$ver = $log_version[1];
		$agent = 'Safari';
	} elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Konqueror') && preg_match('#Konqueror/([0-9])#', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
		$ver = $log_version[1];
		$agent = 'Konqueror';
	} elseif (preg_match('#Mozilla/([0-9].[0-9]{1,2})#', $_SERVER['HTTP_USER_AGENT'], $log_version)) {
		$ver = $log_version[1];
		$agent = 'Mozilla';
	} else {
		$ver = 0;
		$agent = 'Other';
	}
	
	if (strstr($_SERVER['HTTP_USER_AGENT'], 'Win')) {
		$platform = 'Win';
	} elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Mac')) {
		$platform = 'Mac';
	} elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Linux')) {
		$platform = 'Linux';
	} elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Unix')) {
		$platform = 'Unix';
	} else {
		$platform = 'Other';
	}
	
	return array(
		'agent' => $agent,
		'version' => $ver,
		'platform' => $platform
	);
}

function now() {
	return time();
}

function log_message($logtype='', $message='', $userid='', $ip='') {

	$logtype = strtoupper($logtype);

	switch($logtype) {
	
		case 'DEBUG':
		case 'INFO':
		case 'ERROR':	break;
		default: 		$logtype = 'INFO';
	
	}
	
	$message = trim($message);
	if( empty($message) ) {
		$message = 'no specific log message';
	}
	if( empty( $ip ) ) {
		$ip = getRemoteIP();
	}
	
	@_dbInsert('phpwcms_log', array(
		
			'log_type'		=> $logtype,
			'log_message'	=> $message,
			'log_ip'		=> $ip,
			'log_userid'	=> $userid
		
		), 'DELAYED');

}

function init_frontend_edit() {
	// define VISIBLE_MODE
	// 0 = frontend (all) mode
	// 1 = article user mode
	// 2 = admin user mode
	if(empty($_SESSION["wcs_user_id"])) {
		define('VISIBLE_MODE', 0);
	} else {
		define('VISIBLE_MODE', $_SESSION['wcs_user_admin'] !== 1 ? 1 : 2);
	}
	define ('FE_EDIT_LINK', VISIBLE_MODE == 0 || empty($GLOBALS['phpwcms']['frontend_edit']) ? false : true);
}

?>