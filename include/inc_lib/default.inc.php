<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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
if (!defined('PHPWCMS_INCLUDE_CHECK')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


@ini_set( 'arg_separator.output' , '&amp;' );

// i18n charsets that might be accessible - in general used in MySQL
// but a few more as listed here http://www.w3.org/International/O-charset-list.html
$phpwcms['charsets'] = array(
	'iso-2022-kr',
	'iso-2022-jp',
    'iso-8859-1',
    'iso-8859-2',
    'iso-8859-3',
    'iso-8859-4',
    'iso-8859-5',
    'iso-8859-6',
    'iso-8859-7',
    'iso-8859-8',
	'iso-8859-8-i',
    'iso-8859-9',
    'iso-8859-10',
    'iso-8859-11',
    'iso-8859-12',
    'iso-8859-13',
    'iso-8859-14',
    'iso-8859-15',
	'iso-10646-ucs-2',
	'windows-874',
    'windows-1250',
    'windows-1251',
    'windows-1252',
	'windows-1253',
	'windows-1254',
	'windows-1255',
    'windows-1256',
    'windows-1257',
	'windows-1258',
    'koi8-r',
    'big5',
    'gb2312',
	'us-ascii',
    'utf-16',
    'utf-8',
    'utf-7',
    'x-user-defined',
	'euc-cn',
    'euc-jp',
	'euc-kr',
	'euc-tw',
    'ks_c_5601-1987',
    'tis-620',
    'shift_jis'
);

define ('PHPWCMS_CHARSET', 	empty($phpwcms["charset"]) ? 'utf-8' : strtolower($phpwcms["charset"]));

/* seems to be problematic at the moment - so always use text/html
if (!empty($phpwcms['header_XML']) && $_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1' && isset($_SERVER['HTTP_ACCEPT']) && preg_match('|application/xhtml\+xml(?!\s*;\s*q=0)|', $_SERVER['HTTP_ACCEPT'])) {
	header('Content-Type: application/xhtml+xml; charset='.PHPWCMS_CHARSET);
	header('Vary: Negotiate, Accept');
	$_use_content_type = 'application/xhtml+xml';
}
*/
if(defined('CUSTOM_CONTENT_TYPE')) {

	header(CUSTOM_CONTENT_TYPE);
	
} else {

	header('Content-Type: text/html; charset='.PHPWCMS_CHARSET);
	$_use_content_type = 'text/html';

}


// define the real path of the phpwcms installation
// important to script that must know the real path to files or something else

$phpwcms['DOC_ROOT'] = rtrim( str_replace("\\", '/', $phpwcms['DOC_ROOT']), '/' );
if( empty($phpwcms["root"]) ) {
	$phpwcms["root"]			 = '';
	$phpwcms["host_root"]		 = '';
} else {
	$phpwcms["root"]			 = trim( $phpwcms["root"], '/' );
	$phpwcms["host_root"]		 = '/'.$phpwcms["root"];
	$phpwcms['DOC_ROOT']		.= 	'/' . $phpwcms["root"];
	$phpwcms["root"]			.= 	'/';
}

define ("PHPWCMS_ROOT", 			$phpwcms['DOC_ROOT']);
define ('PHPWCMS_FILES', 			$phpwcms["file_path"] . '/');
define ('PHPWCMS_BASEPATH',			'/' . $phpwcms["root"]);
define ('On',						true);
define ('Off',						false);
define ('PHPWCMS_USER_KEY',			md5(getRemoteIP().$phpwcms['DOC_ROOT'].$phpwcms["db_pass"]));
define ('PHPWCMS_REWRITE_EXT',		'phtml');

// Mime-Type definitions
require_once(PHPWCMS_ROOT.'/include/inc_lib/mimetype.inc.php');

phpwcms_getUserAgent();
define('BROWSER_NAME',				$phpwcms['USER_AGENT']['agent']);
define('BROWSER_NUMBER',			$phpwcms['USER_AGENT']['version']);
define('BROWSER_OS',				$phpwcms['USER_AGENT']['platform']);

$phpwcms["file_path"]    		= 	'/'.$phpwcms["file_path"].'/' ;  // "/phpwcms_filestorage/"

define ('TEMPLATE_PATH', 			$phpwcms["templates"].'/');
$phpwcms["templates"]    		= 	'/'.$phpwcms["templates"].'/' ;  // "/phpwcms_template/"
$phpwcms["content_path"] 		= 	$phpwcms["content_path"].'/'  ;  // "content/"
define ('CONTENT_PATH',				$phpwcms["content_path"]);
$phpwcms["cimage_path"]  		= 	$phpwcms["cimage_path"].'/'   ;  // "images/"
$phpwcms["ftp_path"]     		= 	'/'.$phpwcms["ftp_path"].'/'  ;  // "/phpwcms_ftp/"

define ('PHPWCMS_TEMPLATE', 		PHPWCMS_ROOT.$phpwcms["templates"]);
define ('PHPWCMS_URL', 				$phpwcms["site"].$phpwcms["root"]);

$phpwcms['parse_url']			=	parse_url(PHPWCMS_URL);
define ('PHPWCMS_HOST',				$phpwcms['parse_url']['host'].$phpwcms["host_root"]);
define ('PHPWCMS_IMAGES', 			$phpwcms["content_path"].$phpwcms["cimage_path"]);
define ('PHPWCMS_TEMP', 			PHPWCMS_ROOT.'/'.$phpwcms["content_path"].'tmp/');
define ('PHPWCMS_CONTENT',			PHPWCMS_ROOT.'/'.$phpwcms["content_path"]);
define ('PHPWCMS_THUMB',			PHPWCMS_CONTENT.$phpwcms["cimage_path"]);
define ('PHPWCMS_RSS', 				PHPWCMS_CONTENT.'rss');
define ('LF', 						"\n"); 	//global new line Feed
define ('FEUSER_REGKEY',			empty($phpwcms['feuser_regkey']) ? 'FEUSER' : $phpwcms['feuser_regkey']);

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

$phpwcms['default_lang']	= strtolower($phpwcms['default_lang']);
$phpwcms['DOCTYPE_LANG']	= empty($phpwcms['DOCTYPE_LANG']) ? $phpwcms['default_lang'] : strtolower(trim($phpwcms['DOCTYPE_LANG']));

if(empty($phpwcms['js_lib'])) {
	$phpwcms['js_lib']		= array(
		'mootools-1.2'	=> 'MooTools 1.2',
		'mootools-1.1'	=> 'MooTools 1.1',
		'jquery'		=> 'jQuery 1.3',
		'jquery-1.4'	=> 'jQuery 1.4'
	);
}

if(empty($phpwcms['mode_XHTML'])) {
	
	$phpwcms['mode_XHTML'] = 0;

	define('PHPWCMS_DOCTYPE', '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'.LF.'<html{DOCTYPE_LANG}>'.LF.'<head>'.LF);
	define('SCRIPT_CDATA_START', '  <!-- ');
	define('SCRIPT_CDATA_END'  , '  // -->');
	define('HTML_TAG_CLOSE'  , '>');
	define('XHTML_MODE', false);
	define('PHPWCMS_DOCTYPE_LANG', ' lang="{DOCTYPE_LANG}"');
	
} elseif($phpwcms['mode_XHTML'] == 2) {

	define('PHPWCMS_DOCTYPE', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'.LF.'<html xmlns="http://www.w3.org/1999/xhtml"{DOCTYPE_LANG}>'.LF.'<head>'.LF);
	define('SCRIPT_CDATA_START', '  /* <![CDATA[ */');
	define('SCRIPT_CDATA_END'  , '  /* ]]> */');
	define('HTML_TAG_CLOSE'  , ' />');
	define('XHTML_MODE', true);
	define('PHPWCMS_DOCTYPE_LANG', ' xml:lang="{DOCTYPE_LANG}" lang="{DOCTYPE_LANG}"');
	
} elseif($phpwcms['mode_XHTML'] == 3) {

	define('PHPWCMS_DOCTYPE', '<!DOCTYPE html>'.LF.'<html{DOCTYPE_LANG}>'.LF.'<head>'.LF);
	define('SCRIPT_CDATA_START', '');
	define('SCRIPT_CDATA_END'  , '');
	define('HTML_TAG_CLOSE'  , ' />');
	define('XHTML_MODE', true);
	define('PHPWCMS_DOCTYPE_LANG', ' xml:lang="{DOCTYPE_LANG}"');
	
} else {
	
	$phpwcms['mode_XHTML'] = 1;

	define('PHPWCMS_DOCTYPE', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.LF.'<html xmlns="http://www.w3.org/1999/xhtml"{DOCTYPE_LANG}>'.LF.'<head>'.LF);
	define('SCRIPT_CDATA_START', '  <!-- ');
	define('SCRIPT_CDATA_END'  , '  // -->');
	define('HTML_TAG_CLOSE'  , ' />');
	define('XHTML_MODE', true);
	define('PHPWCMS_DOCTYPE_LANG', ' xml:lang="{DOCTYPE_LANG}" lang="{DOCTYPE_LANG}"');

}

$phpwcms["release"]			= '1.4.7';
$phpwcms["release_date"]	= '2010/11/23';
$phpwcms["revision"]		= '405';

// -------------------------------------------------------------

function removeSessionName($str='') {
	// is used to remove all &hashID=...
	// not useful when when storing in cache
	// because it stores unneccessary session IDs too
	$sessName = session_name();
	if($sessName) {
		$str = preg_replace('/[&|\?]{0,1}'.$sessName.'=[a-zA-Z0-9]{1,}/', '', $str);
	}
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
		  
	if( get_magic_quotes_gpc() ) {
		foreach($GLOBALS['_getVar'] as $key => $value) {
			$GLOBALS['_getVar'][$key] = stripslashes($value);
		}
	}
	
	if($return == 'getQuery') {
		return returnGlobalGET_QueryString('htmlentities');
	}
}

// build phpwcms specific relative url
function rel_url($add=array(), $remove=array(), $id_alias='', $format='htmlentities', $glue='&', $bind='=') {
	
	return 'index.php' . returnGlobalGET_QueryString($format, $add, $remove, $id_alias, $glue, $bind);

}
// build phpwcms specific absolute url
function abs_url($add=array(), $remove=array(), $id_alias='', $format='htmlentities', $glue='&', $bind='=') {
	
	return PHPWCMS_URL . 'index.php' . returnGlobalGET_QueryString($format, $add, $remove, $id_alias, $glue, $bind);

}

// build a URL query string based on current values
function returnGlobalGET_QueryString($format='', $add=array(), $remove=array(), $id_alias='', $glue='&', $bind='=') {

	$queryString	= array();
	$_getVarTemp	= $GLOBALS['_getVar'];
	
	// replace first value with $id_alias
	if($id_alias !== '') {

		$id_alias		= explode($bind, $id_alias, 2);
		$id_alias[0]	= trim($id_alias[0]);

		if($id_alias[0] !== '') {
			$id_alias[1] = isset($id_alias[1]) ? trim($id_alias[1]) : '';
			array_shift($_getVarTemp);
			$_getVarTemp = array($id_alias[0] => $id_alias[1]) + $_getVarTemp;
		}
	}

	foreach($remove as $value) {
		unset($_getVarTemp[$value]);
	}

	$pairs = count($add) ? array_merge($_getVarTemp, $add) : $_getVarTemp;

	switch($format) {
	
		case 'htmlentities':	$glue	= html_entities($glue);
								$funct	= 'getQueryString_htmlentities';
								break;
								
		case 'urlencode':		$funct	= 'getQueryString_urlencode';
								break;
								
		case 'rawurlencode':	$funct	= 'getQueryString_rawurlencode';
								break;
								
		default:				$funct	= 'getQueryString_default';

	}
	
	foreach($pairs as $key => $value) {
	
		$queryString[] = $funct($key, $value, $bind);

	}

	return count($queryString) ? '?'.implode($glue, $queryString) : '';
}

function getQueryString_htmlentities($key='', $value='', $bind='=') {
	if($value !== '') {
		return html_entities(urlencode($key).$bind.str_replace('%2C', ',', urlencode($value)));
	}
	return html_entities(urlencode($key));
}

function getQueryString_urlencode($key='', $value='', $bind='=') {
	if($value !== '') {
		return urlencode($key).$bind.urlencode($value);
	}
	return urlencode($key);
}

function getQueryString_rawurlencode($key='', $value='', $bind='=') {
	if($value !== '') {
		return rawurlencode($key).$bind.rawurlencode($value);
	}
	return rawurlencode($key);
}

function getQueryString_default($key='', $value='', $bind='=') {
	if($value !== '') {
		return $key.$bind.$value;
	}
	return $key;
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
	if(defined('REMOTE_IP')) {
		return REMOTE_IP;
	}
	$IP = 'unknown';
	if (!empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown')) {
		$IP = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown')) {
		$IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$IP = $_SERVER['REMOTE_ADDR'];
	}
	define('REMOTE_IP', $IP);
	return $IP;
}

// Get user agent informations, based on concepts of OpenAds 2.0
// Copyright (c) 2000-2007 by the OpenAds developers
function phpwcms_getUserAgent($USER_AGENT='') {
	
	if(isset($GLOBALS['phpwcms']['USER_AGENT'])) {
		return $GLOBALS['phpwcms']['USER_AGENT'];
	}
	
	$USER_AGENT = empty($USER_AGENT) && isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $USER_AGENT;

	if(empty($USER_AGENT)) {
		$GLOBALS['phpwcms']['USER_AGENT'] = array(
			'agent' => 'Other',
			'version' => 0,
			'platform' => 'Other',
			'mobile' => 0,
			'bot' => 0
		);
		return $GLOBALS['phpwcms']['USER_AGENT'];
	}

	if(preg_match('#MSIE ([0-9].[0-9]{1,2})(.*Opera ([0-9].[0-9]{1,2}))?#', $USER_AGENT, $log_version)) {
		if(isset($log_version[3])) {
			$ver = $log_version[3];
			$agent = 'Opera';
		} else {
			$ver = $log_version[1];
			$agent = 'IE';
		}
	} elseif(preg_match('#Mozilla.*Firefox\/([0-9].[0-9]{1,2})#', $USER_AGENT, $log_version)) {
		$ver = $log_version[1];
		$agent = 'Firefox';
	} elseif(preg_match('#Mozilla.*Chrome\/([0-9].[0-9]{1,2})#', $USER_AGENT, $log_version)) {
		$ver = $log_version[1];
		$agent = 'Chrome';
 	} elseif(strstr($USER_AGENT, 'Safari') && preg_match('#Safari/([0-9]{1,4})#', $USER_AGENT, $log_version)) {
		$ver = $log_version[1];
		$agent = 'Safari';
	} elseif(preg_match('#Mozilla/([0-9].[0-9]{1,2})#', $USER_AGENT, $log_version)) {
		$ver = $log_version[1];
		$agent = 'Mozilla';
	} elseif(preg_match('#Opera.* Version\/([0-9]{1,2}.[0-9]{1,2})#', $USER_AGENT, $log_version)) {
		$ver = $log_version[1];
		$agent = 'Opera';
	} elseif(preg_match('#Opera[/ ]([0-9].[0-9]{1,2})#', $USER_AGENT, $log_version)) {
		$ver = $log_version[1];
		$agent = 'Opera';
	} elseif(strstr($USER_AGENT, 'Konqueror') && preg_match('#Konqueror/([0-9])#', $USER_AGENT, $log_version)) {
		$ver = $log_version[1];
		$agent = 'Konqueror';
	} else {
		$ver = 0;
		$agent = 'Other';
	}
	
	$mobile = 0;
	$bot = 0;
	
	if(strpos($USER_AGENT, 'Win') !== false) {
		$platform = 'Win';
	} elseif(strpos($USER_AGENT, 'iPhone') !== false || strpos($USER_AGENT, 'iPod') !== false || strpos($USER_AGENT, 'iPad') !== false) {
		$platform = 'iOS';
		$mobile = 1;
	} elseif(strpos($USER_AGENT, 'Mac') !== false) {
		$platform = 'Mac';
	} elseif(strpos($USER_AGENT, 'Android') !== false) {
		$platform = 'Android';
		$mobile = 1;
	} elseif(strpos($USER_AGENT, 'Linux') !== false) {
		$platform = 'Linux';
	} elseif(strpos($USER_AGENT, 'Unix') !== false) {
		$platform = 'Unix';
	} else {
		$platform = 'Other';
		
		if($USER_AGENT) {
		
			if(empty($GLOBALS['phpwcms']["BOTS"]) || !is_array($GLOBALS['phpwcms']["BOTS"])) {
				$GLOBALS['phpwcms']["BOTS"] = array('googlebot', 'msnbot', 'bingbot', 'ia_archiver', 'altavista', 'slurp', 'yahoo', 'jeeves', 'teoma', 'lycos', 'crawler');
			}
			
			if(preg_match('/('.implode('|', $GLOBALS['phpwcms']["BOTS"]).')/i', $USER_AGENT, $match_bot)) {
				$agent = $match_bot[1];
				$bot = 1;
			}
		
		}
	}
		
	$GLOBALS['phpwcms']['USER_AGENT'] = array(
		'agent' => $agent,
		'version' => $ver,
		'platform' => $platform,
		'mobile' => $mobile,
		'bot' => $bot
	);
	
	return $GLOBALS['phpwcms']['USER_AGENT'];
}

/**
 * Return current UNIX timestamp
 * Wrapper function that might be enhanced for regional time and so on
 **/
function now($format=NULL) {
	return is_string($format) ? date($format) : time();
}

/**
 * Log to db
 *
 * Default log types: DEBUG|INFO|ERROR|INFO or use specific module name
 */
function log_message($type='UNDEFINED', $message='', $userid=0) {

	$log = array(
			'log_created'		=> date('Y-m-d H:i:s', now()),
			'log_type'			=> 'UNDEFINED',
			'log_ip'			=> getRemoteIP(),
			'log_user_agent'	=> '',
			'log_user_id'		=> 0,
			'log_user_name'		=> '',
			'log_referrer_id'	=> 0,
			'log_referrer_url'	=> '',
			'log_data1'			=> '',
			'log_data2'			=> '',
			'log_data3'			=> '',
			'log_msg'			=> ''
		);

	if(is_array($type)) {
		foreach($type as $key => $value) {
			if(isset($log[$key])) {
				$log[$key] = $value;
			}
		}
	} else {
		$log['log_type']	= trim($type);
		$log['log_user_id']	= intval($userid);
		$log['log_msg']		= trim($message);
	}
	
	$log['log_type'] = strtoupper($log['log_type']);
	
	if($log['log_user_agent'] == '') {
		$log['log_user_agent'] = empty($_SERVER['HTTP_USER_AGENT']) ? implode( ', ', phpwcms_getUserAgent() ) : $_SERVER['HTTP_USER_AGENT'];
	}
	if(empty($log['log_referrer_url']) && isset($_SERVER['HTTP_REFERER'])) {
		$log['log_referrer_url'] = $_SERVER['HTTP_REFERER'];
	}
	
	_dbInsert( 'phpwcms_log', $log, 'DELAYED' );

}


function init_frontend_edit() {
	// define VISIBLE_MODE
	// 0 = frontend (all) mode
	// 1 = article user mode
	// 2 = admin user mode
	if(empty($_SESSION["wcs_user_id"])) {
		define('VISIBLE_MODE', 0);
	} else {
		define('VISIBLE_MODE', $_SESSION['wcs_user_admin'] === 1 ? 2 : 1);
	}
	define ('FE_EDIT_LINK', VISIBLE_MODE == 0 || empty($GLOBALS['phpwcms']['frontend_edit']) ? false : true);
}

/**
 * Wrapper for htmlentities() to handle charset better inside of phpwcms
 **/
function html_entities($string='', $quote_mode=ENT_QUOTES, $charset=PHPWCMS_CHARSET) {
	return @htmlentities($string, $quote_mode, $charset);
}

function getMicrotime() {
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (float)$sec);
}

function getMicrotimeDiff($start=0) {
	return (getMicrotime() - $start);
}

/**
 * Return login.php
 */
function get_login_file() {
	if(defined('PHPWCMS_LOGIN_PHP')) {
		return PHPWCMS_LOGIN_PHP;
	}
	global $phpwcms;
	$login = empty($GLOBALS['phpwcms']['login.php']) ? 'login.php' : $GLOBALS['phpwcms']['login.php'];
	if(is_file(PHPWCMS_ROOT.'/'.$login)) {
		define('PHPWCMS_LOGIN_PHP', $login);
		return PHPWCMS_LOGIN_PHP;
	}
	if(is_file(PHPWCMS_ROOT.'/login.php')) {
		define('PHPWCMS_LOGIN_PHP', 'login.php');
		return PHPWCMS_LOGIN_PHP;
	}
	die('Login.php cannot be found. We stop here!');
}

/**
 * Encrypt string
 */
function encrypt($plaintext, $key='8936AeYcenBDLyMzN', $cypher='blowfish', $mode='cfb') {
	$td = mcrypt_module_open($cypher, '', $mode, '');
	$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$crypttext = mcrypt_generic($td, $plaintext);
	mcrypt_generic_deinit($td);
	return $iv.$crypttext;
}

/**
 * Decrypt string
 */
function decrypt($crypttext, $key='8936AeYcenBDLyMzN', $cypher='blowfish', $mode='cfb') {
	$plaintext = '';
	$td = mcrypt_module_open($cypher, '', $mode, '');
	$ivsize = mcrypt_enc_get_iv_size($td);
	$iv = substr($crypttext, 0, $ivsize);
	$crypttext = substr($crypttext, $ivsize);
	if ($iv) {
		mcrypt_generic_init($td, $key, $iv);
		$plaintext = mdecrypt_generic($td, $crypttext);
	}
	return $plaintext;
}

/**
 * Get current user visual mode
 */
function get_user_vmode() {
	switch(VISIBLE_MODE) {
		case 1:		return 'editor';	break;
		case 2:		return 'admin';		break;
		default:	return 'all';
	};
}

function get_user_rc($g='', $pu=501289, $pr=506734, $e=array('SAAAAA','PT96y0w','5k4kWtC','8RAoSD4','Jp6RmA','6LfyU74','OVQRK5f','kbHQ6qx','YdgUgX-','H808le')) {
	$c = ''; foreach(str_split(strval($$g)) as $a) $c.=$e[intval($a)]; return $c;
}


?>