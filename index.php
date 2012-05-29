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

// set page processiong start time
list($usec, $sec) = explode(' ', microtime());
$phpwcms_rendering_start = $usec + $sec;

// define some general vars
$content 			= array();
$phpwcms 			= array();
$BL					= array();
$template_default	= array();
$indexpage			= array();

// load general configuration
$basepath			= str_replace('\\', '/', dirname(__FILE__));
if(!is_file($basepath.'/config/phpwcms/conf.inc.php')) {
	if(is_file($basepath.'/setup/index.php')) {
		header('Location: setup/index.php');
		exit();
	}
	die('Error: Config file missing. Check your setup!');
}
require_once $basepath.'/config/phpwcms/conf.inc.php';
require_once $basepath.'/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';

// Get user Agent BOT check
$IS_A_BOT = $phpwcms['USER_AGENT']['bot'];

// start session - neccessary if frontend users are available
// but neccessary also to check if a bot is visiting the site
// -> if so then do not initialize session for larger search engines
if(!$IS_A_BOT && !empty($phpwcms['SESSION_FEinit'])) {
	_initSession();
}

// some initial actions
cleanupPOSTandGET();
buildGlobalGET();
define('FE_CURRENT_URL', abs_url(array(),array('phpwcms_output_action')) );

// init some special rights and also frontend edit
init_frontend_edit();

// buffer everything
ob_start();

$content['page_end'] = '';

require_once PHPWCMS_ROOT.'/config/phpwcms/conf.template_default.inc.php';
require_once PHPWCMS_ROOT.'/config/phpwcms/conf.indexpage.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_front/cnt.lang.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/modules.check.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/article.contenttype.inc.php';
require PHPWCMS_ROOT.'/include/inc_lib/imagick.convert.inc.php';
require PHPWCMS_ROOT.'/include/inc_front/front.func.inc.php';
require PHPWCMS_ROOT.'/include/inc_front/ext.func.inc.php';
require PHPWCMS_ROOT.'/include/inc_front/content.func.inc.php';


// SEO logging
if(!empty($phpwcms['enable_seolog']) && !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME']) === false) {
	$phpwcms['seo_referrer_data'] = seReferrer( $_SERVER['HTTP_REFERER'] );
	if( is_array( $phpwcms['seo_referrer_data'] ) ) {
		@_dbInsert('phpwcms_log_seo', $phpwcms['seo_referrer_data'], 'DELAYED');
	}
}

$phpwcms["templates"]    = TEMPLATE_PATH;
$content['page_start']   = sprintf(PHPWCMS_DOCTYPE, str_replace( '{DOCTYPE_LANG}', $phpwcms['DOCTYPE_LANG'], PHPWCMS_DOCTYPE_LANG ) . ' id="'.str_replace(array('.','/'), '-', PHPWCMS_HOST).'"');
$content['page_start']  .= '<!--
	phpwcms | free open source content management system
	created by Oliver Georgi (oliver at phpwcms dot de) and licensed under GNU/GPL.
	phpwcms is copyright 2002-'.date('Y').' of Oliver Georgi. Extensions are copyright of
	their respective owners. Visit project page for details: http://www.phpwcms.org/'.LF.'-->'.LF;
$content['page_start']  .= '  <title>'.html_specialchars($content["pagetitle"]).'</title>'.LF;
$content['page_start']  .= '  <meta http-equiv="content-type" content="'.$_use_content_type.'; charset='.PHPWCMS_CHARSET.'"'.HTML_TAG_CLOSE.LF;

// HTML5 does not like content-style-type
if($phpwcms['mode_XHTML'] != 3) {
	$content['page_start']  .= '  <meta http-equiv="content-style-type" content="text/css"'.HTML_TAG_CLOSE.LF;
}

// Deprecated custom page CSS
$content['page_start']  .= get_body_attributes($pagelayout);

// Add all CSS files here
if(count($block['css'])) {
	foreach($block['css'] as $value) {
		$content['page_start'] .= '  <link rel="stylesheet" type="text/css" href="'.TEMPLATE_PATH.'inc_css/';
		$content['page_start'] .= str_replace(' ', '%20', $value);
		$content['page_start'] .= '"'.HTML_TAG_CLOSE.LF;
	}
}

$content['page_start'] .= $block["htmlhead"];

if($phpwcms['USER_AGENT']['agent'] == 'IE' && !empty($phpwcms['IE7-js']) && version_compare($phpwcms['USER_AGENT']['version'], '9.0', '<')) {
	$content['page_start'] .= '  <!--[if lt IE 9]><script type="text/javascript" src="'.TEMPLATE_PATH.'lib/ie7-js/IE9.js"></script><![endif]-->'.LF;
}

$content['page_start'] .= '</head>'.LF;

if($phpwcms['rewrite_url'] && strpos($content['page_start'], '<base href') === false) {
	$content['page_start'] = str_replace('</title>', '</title>'.LF.'  <base href="'.PHPWCMS_URL.'"'.HTML_TAG_CLOSE, $content['page_start']);
}

// inject body tag in case of class or id attribute
$body_inject = '<body';
if($content['body_id'] !== false) {
	if(!empty($template_default['body']['id'])) {
		$body_inject .= ' id="'.$template_default['body']['id'].$content['body_id'].'"';
	}
	if(!empty($template_default['body']['class'])) {
		$body_inject .= ' class="'.$template_default['body']['class'].$content['body_id'].'"';
	}
}
$content['page_start'] .= $body_inject.'>'.LF;

//  this regex's inits rewrite
if($phpwcms["rewrite_url"]) {
	$content["all"] = preg_replace_callback('/( href| action)(="index.php\?)([a-zA-Z0-9@,\.\+\-_\*#\/%=&;]+?)"/', 'url_search', $content["all"]);
	$content["all"] = preg_replace_callback('/onclick="location.href=\'index.php\?([a-zA-Z0-9@,\.\+\-_\*#\/%=&;]+?)\'/', 'js_url_search', $content["all"]);
}

// real page ending
$content['page_end'] .= LF.'</body>'.LF.'</html>';

// return rendered content
echo $content['page_start'];
echo $content["all"];
echo $content['page_end'];

// phpwcms Default header settings
if($phpwcms['cache_timeout']) {
	header('Expires: '.gmdate('D, d M Y H:i:s', time() + $phpwcms['cache_timeout']) .' GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', empty($row['article_date']) ? time() : $row['article_date']) .' GMT');
	header('Cache-Control: public, max-age='.$phpwcms['cache_timeout']);
	header('Pragma: public');
}

// write phpwcms release information in a custom HTTP header
header('X-phpwcms-Release: ' . $phpwcms["generator"]);

// retrieve complete processing time
list($usec, $sec) = explode(' ', microtime());
header('X-phpwcms-Page-Processed-In: ' . number_format(1000*($usec + $sec - $phpwcms_rendering_start), 3) .' ms');

// print PDF
if($aktion[2] === 1 && defined('PRINT_PDF') && PRINT_PDF) {
	require_once (PHPWCMS_ROOT.'/include/inc_front/pdf.inc.php');

// handle output action and section
} elseif($phpwcms['output_action']) {
	
	if(empty($phpwcms['output_function_filter']) || !is_array($phpwcms['output_function_filter'])) {
		$phpwcms['output_function_filter'] = array('trim', 'strip_tags');
	}
	
	$phpwcms['output_function'] = array_intersect($phpwcms['output_function_filter'], $phpwcms['output_function']);

	$content = ob_get_clean();

	$sections = '';

	foreach($phpwcms['output_section'] as $section) {
	
		$section = get_tmpl_section($section, $content);
		
		foreach($phpwcms['output_function'] as $function) {
	
			$section = $function($section);
	
		}
		
		$sections .= $section;
	}
	
	// return preg_replace('/<!--(.|\s)*?-->/', '', $buffer);
	echo trim($sections) == '' ? $content : $sections;
	
	exit();
}

// send buffer to browser
ob_end_flush();

?>