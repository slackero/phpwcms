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
require_once 'config/phpwcms/conf.inc.php';
require_once 'include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';

// BOT check
$IS_A_BOT = false;

if( !empty($_SERVER['HTTP_USER_AGENT']) ) {

	if(empty($phpwcms["BOTS"]) || !is_array($phpwcms["BOTS"])) {
		$phpwcms["BOTS"] = array('googlebot', 'msnbot', 'ia_archiver', 'altavista', 'slurp', 'yahoo', 'jeeves', 'teoma', 'lycos', 'crawler');
	}
	
	$_HTTP_USER_AGENT = strtolower($_SERVER['HTTP_USER_AGENT']);

	foreach($phpwcms["BOTS"] as $value) {
		if(strpos($_HTTP_USER_AGENT, $value) !== FALSE) {
			$IS_A_BOT = true;
			break;
		}
	}
}

// start session - neccessary if frontend users are available
// but neccessary also to check if a bot is visiting the site
// -> if so then do not initialize session for larger search engines
if(!$IS_A_BOT && !empty($phpwcms['SESSION_FEinit'])) {
	_initSession();
}

// some initial actions
cleanupPOSTandGET();
define('FE_CURRENT_URL', PHPWCMS_URL . 'index.php' . buildGlobalGET('getQuery'));

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

if(!empty($phpwcms['Bad_Behavior'])) {
	require PHPWCMS_ROOT.'/include/inc_module/mod_bad-behavior/bad-behavior-phpwcms.php';
}

$phpwcms["templates"]    = TEMPLATE_PATH;

$phpwcms['DOCTYPE_LANG'] = str_replace( '{DOCTYPE_LANG}', $phpwcms['DOCTYPE_LANG'], PHPWCMS_DOCTYPE_LANG );

$content['page_start']   = str_replace( '{DOCTYPE_LANG}', $phpwcms['DOCTYPE_LANG'], PHPWCMS_DOCTYPE );
$content['page_start']  .= '<!--
	phpwcms | free open source content management system
	created by Oliver Georgi (oliver at phpwcms dot de) and licensed under GNU/GPL.
	phpwcms is copyright 2003-'.date('Y').' of Oliver Georgi. Extensions are copyright of
	their respective owners. Visit project page for details: http://www.phpwcms.org/'.LF.'//-->'.LF;
$content['page_start']  .= '<title>'.html_specialchars($content["pagetitle"]).'</title>'.LF;
$content['page_start']  .= '  <meta http-equiv="content-type" content="'.$_use_content_type.'; charset='.PHPWCMS_CHARSET.'"'.HTML_TAG_CLOSE.LF;
$content['page_start']  .= '  <meta http-equiv="content-style-type" content="text/css"'.HTML_TAG_CLOSE.LF;
$content['page_start']  .= '  <script src="'.TEMPLATE_PATH.'inc_js/frontend.js" type="text/javascript"></script>'.LF;
$content['page_start']  .= get_body_attributes($pagelayout);

// now add all CSS files here
if(count($block['css'])) {
	foreach($block['css'] as $value) {
		$content['page_start'] .= '  <link rel="stylesheet" type="text/css" href="'.TEMPLATE_PATH.'inc_css/';
		$content['page_start'] .= str_replace('--__--', '/', rawurlencode(str_replace('/', '--__--', $value)));
		$content['page_start'] .= '"'.HTML_TAG_CLOSE.LF;
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
		$content['page_start'] .= $phpwcms['IE_htc_png']==1 ? 'iepngfix' : 'pngbehavior';
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

//  this regex's inits rewrite
if($phpwcms["rewrite_url"]) {
	$content["all"] = preg_replace("/( href=\"index.php?)(([a-zA-Z0-9@,\.\+&\-_=\*#\/%\?])*)(\")/e", "url_search('$2')", $content["all"]);
	$content["all"] = preg_replace("/(onclick=\"location.href='index.php?)(([a-zA-Z0-9@,\.\+&\-_=\*#\/%\?])*)(\')/e", "js_url_search('$2')", $content["all"]);
}

// return rendered content
echo $content['page_start'];
echo $content["all"];
echo $content['page_end'];

if(FE_EDIT_LINK) {

	echo '<div id="fe-link" class="disabled"></div>
<script type="text/javascript">
<!--
	window.addEvent("domready", function(){
		var felink_status = 0;
		$$("a.fe-link").each(function(r) {
			r.setStyle("display", "none");
		});
		$("fe-link").addEvent("click", function() {
			if(felink_status == 1) {
				$$("a.fe-link").each(function(r) {
					r.setStyle("display", "none");
				});
				$("fe-link").removeClass("enabled");
				$("fe-link").addClass("disabled");
				felink_status = 0;
			} else {
				$$("a.fe-link").each(function(r) {
					r.setStyle("display", "");
				});
				$("fe-link").removeClass("disabled");
				$("fe-link").addClass("enabled");
				felink_status = 1;
			}
		});
	});
//-->
</script>';
}

// real page ending
echo LF.'</body>'.LF.'</html>';

// phpwcms Default header settings
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