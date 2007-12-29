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
require_once ('config/phpwcms/conf.inc.php');
require_once ('include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');

$IS_A_BOT = false;
$BOTSLIST = (isset($phpwcms["BOTS"]) && is_array($phpwcms["BOTS"])) ? $phpwcms["BOTS"] : array('googlebot', 'msnbot', 'ia_archiver', 'altavista', 'slurp', 'yahoo', 'jeeves', 'teoma', 'lycos', 'crawler');
foreach($BOTSLIST as $value) {
	if(stristr($_SERVER['HTTP_USER_AGENT'], $value)) {
		$IS_A_BOT = true;
		break;
	}
}

// start session - neccessary if frontend users are available
// but neccessary also to check if a bot is visiting the site
// -> if so then do not initialize session for larger search engines
if(!$IS_A_BOT && !empty($phpwcms['SESSION_FEinit'])) {
	_initSession();
}
// define VISIBLE_MODE
// 0 = frontend (all) mode
// 1 = article user mode
// 2 = admin user mode
if(empty($_SESSION["wcs_user_id"])) {
	define('VISIBLE_MODE', 0);
} else {
	define('VISIBLE_MODE', $_SESSION['wcs_user_admin'] != 1 ? 1 : 2);
}

// some initial actions
cleanupPOSTandGET();
define('FE_CURRENT_URL', PHPWCMS_URL . 'index.php' . buildGlobalGET('getQuery'));

//script caching to allow header redirect
//if($phpwcms["compress_page"] && isset($_SESSION['session_is_set'])) {
//	ob_start("ob_gzhandler"); //with old style GZ Compression
//} else {
//	$_SESSION['session_is_set'] = true;
ob_start(); //without Compression (or use browsers default)
//}

require_once (PHPWCMS_ROOT.'/config/phpwcms/conf.template_default.inc.php');
require_once (PHPWCMS_ROOT.'/config/phpwcms/conf.indexpage.inc.php');

require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_front/cnt.lang.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/modules.check.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/article.contenttype.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/imagick.convert.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_front/front.func.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_front/ext.func.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_front/content.func.inc.php');

if(!empty($phpwcms['Bad_Behavior'])) {
	require(PHPWCMS_ROOT.'/include/inc_module/mod_bad-behavior/bad-behavior-phpwcms.php');
}

$phpwcms["templates"] = TEMPLATE_PATH;

$content['page_start']  = PHPWCMS_DOCTYPE;
$content['page_start'] .= '<!--
	phpwcms | free open source content management system
	created by Oliver Georgi (oliver at phpwcms dot de) and licensed under GNU/GPL.
	phpwcms is copyright 2003-2007 of Oliver Georgi. Extensions are copyright of
	their respective owners. Visit project page for details: http://www.phpwcms.org/'.LF.'//-->'.LF;
$content['page_start'] .= '<title>'.html_specialchars($content["pagetitle"]).'</title>'.LF;
$content['page_start'] .= '  <meta http-equiv="content-type" content="'.$_use_content_type.'; charset='.PHPWCMS_CHARSET.'"'.HTML_TAG_CLOSE.LF;
$content['page_start'] .= '  <meta http-equiv="content-style-type" content="text/css"'.HTML_TAG_CLOSE.LF;
/*
if(defined('PHPWCMS_WRAPPED_DISPLAY')) {
	$content['page_start'] .= '  <base href="'.PHPWCMS_URL.'"'.HTML_TAG_CLOSE.LF;
}
*/
$content['page_start'] .= '  <script src="'.TEMPLATE_PATH.'inc_js/frontend.js" type="text/javascript"></script>'.LF;
$content['page_start'] .= get_body_attributes($pagelayout);

// now add all CSS files here
$content['css_import'] = '';
if(isset($block['css']) && is_array($block['css'])) {

	foreach($block['css'] as $value) {
		$content['css_import'] .= '    @import url("'.TEMPLATE_PATH.'inc_css/'.$value.'");'.LF;
	}
		
	if($content['css_import']) {
		$content['page_start'] .= '  <style type="text/css">'.LF.SCRIPT_CDATA_START.LF;
		$content['page_start'] .= $content['css_import'];
		$content['page_start'] .= SCRIPT_CDATA_END.LF.'  </style>'.LF;
	}

}

$content['page_start'] .= $block["htmlhead"];
if(!empty($phpwcms['IE_htc_hover']) || !empty($phpwcms['IE_htc_png'])) {
	$content['page_start'] .= '  <!--[if lt IE 7]>'.LF;
	$content['page_start'] .= '  <style type="text/css">'.LF;
	if(!empty($phpwcms['IE_htc_hover'])) {
		$content['page_start'] .= '    body { behavior: url("'.TEMPLATE_PATH.'inc_css/specific/csshover2.htc"); }'.LF;
	}
	if(!empty($phpwcms['IE_htc_png'])) {
		$content['page_start'] .= '    img { behavior: url("'.TEMPLATE_PATH.'inc_css/specific/';
		$content['page_start'] .= $phpwcms['IE_htc_png']==1 ? 'pngbehavior' : 'iepngfix';
		$content['page_start'] .= '.htc"); }'.LF;
	}
	$content['page_start'] .= '  </style>'.LF;
	$content['page_start'] .= '  <![endif]-->'.LF;
}

$content['page_start'] .= '</head>'.LF;

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


$content['page_end'] = '';
if(VISIBLE_MODE == 1 || VISIBLE_MODE == 2) {
	$content['page_end']  = '<div id="VisualModeIndicator">';
	$content['page_end'] .= VISIBLE_MODE == 1 ? 'user: ' : 'admin: ';
	$content['page_end'] .= html_specialchars($_SESSION['wcs_user']);
	$content['page_end'] .= ' <a href="phpwcms.php?do=articles';
	if($aktion[1]) {
		$content['page_end'] .= '&amp;p=2&amp;s=1&amp;id='.$aktion[1];
	}
	$content['page_end'] .= '" target="_blank" title="edit article">';
	$content['page_end'] .= '<img src="img/symbols/fe_page_edit.gif" width="16" height="16" alt="edit article" border="0" style="vertical-align:middle"'.HTML_TAG_CLOSE;
	$content['page_end'] .= "</a></div>";
}

//  this regex's call the function
if($phpwcms["rewrite_url"]) {
	$content["all"] = preg_replace("/( href=\"index.php?)(([a-zA-Z0-9@,\.\+&\-_=\*#\/%\?])*)(\")/e", "url_search('$2')", $content["all"]);
	$content["all"] = preg_replace("/(onclick=\"location.href='index.php?)(([a-zA-Z0-9@,\.\+&\-_=\*#\/%\?])*)(\')/e", "js_url_search('$2')", $content["all"]);
	/*
	$allowed_chars_in_url = "[".implode("]|[",array("@",",","\.","+","&","-","_","=","*","#","\/","%","?"))."]";
	$content["all"] = preg_replace("/( href=\"index.php?)(([a-z]|[A-Z]|[0-9]|".$allowed_chars_in_url.")*)(\")/e","url_search('\\2')",$content["all"]);
	$content["all"] = preg_replace("/(onclick=\"location.href='index.php?)(([a-z]|[A-Z]|[0-9]|".$allowed_chars_in_url.")*)(\')/e","js_url_search('\\2')",$content["all"]);
	*/
}

// return rendered content
echo $content['page_start'];
echo $content["all"];
echo $content['page_end'];

// real page ending
echo LF.'</body>'.LF.'</html>';

// phpwcms Default header settings
//$gmdate_timestamp = time(); // + date('Z');
if($phpwcms['cache_timeout']) {
	header('Expires: '.gmdate('D, d M Y H:i:s', time() + $phpwcms['cache_timeout']) .' GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', empty($row['article_date']) ? time() : $row['article_date']) .' GMT');
	header('Cache-Control: public, max-age='.$phpwcms['cache_timeout']);
	header('Pragma: public');
}

// write phpwcms release information in a custom HTTP header
header('X-phpwcms-Release: ' . $phpwcms["release"] . ' ('.$phpwcms["release_date"].')');

// retrieve complete processing time
list($usec, $sec) = explode(' ', microtime());
header('X-phpwcms-Page-Processed-In: ' . number_format(1000*($usec + $sec - $phpwcms_rendering_start), 3) .' ms');

// print PDF
if($aktion[2] === 1 && defined('PRINT_PDF') && PRINT_PDF) {
	require_once (PHPWCMS_ROOT.'/include/inc_front/pdf.inc.php');
}

// send buffer to browser
ob_end_flush();

?>