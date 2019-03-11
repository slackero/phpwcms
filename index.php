<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2019, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// set page processiong start time
list($usec, $sec) = explode(' ', microtime());
$cmsgo_rendering_start = $usec + $sec;

// define some general vars
$content            = array();
$cmsgo            = array();
$BL                 = array();
$template_default   = array();
$indexpage          = array();

// load general configuration
$basepath           = str_replace('\\', '/', dirname(__FILE__));
if(!is_file($basepath.'/include/config/conf.inc.php')) {
    if(is_file($basepath.'/setup/index.php')) {
        header('Location: setup/index.php');
        exit();
    }
    die('Error: Config file missing. Check your setup!');
}

require_once $basepath.'/include/config/conf.inc.php';
require_once $basepath.'/include/inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';

// Get user Agent BOT check
$IS_A_BOT = $cmsgo['USER_AGENT']['bot'];

// start session - neccessary if frontend users are available
// but neccessary also to check if a bot is visiting the site
// -> if so then do not initialize session for larger search engines
if(!$IS_A_BOT && (!empty($cmsgo['SESSION_FEinit']) || isset($_GET['cmsgo-preview']))) {
    _initSession();
}

// some initial actions
cleanupPOSTandGET();
buildGlobalGET();
define('FE_CURRENT_URL', abs_url(array(),array('cmsgo_output_action')) );

// init some special rights and also frontend edit
init_frontend_edit();

// buffer everything
ob_start();

$content['page_end'] = '';

require_once CMSGO_ROOT.'/include/config/conf.template_default.inc.php';
require_once CMSGO_ROOT.'/include/config/conf.indexpage.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/general.inc.php';
require_once CMSGO_ROOT.'/include/inc_front/cnt.lang.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/modules.check.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/article.contenttype.inc.php';
require CMSGO_ROOT.'/include/inc_lib/imagick.convert.inc.php';
require CMSGO_ROOT.'/include/inc_front/front.func.inc.php';
require CMSGO_ROOT.'/include/inc_front/ext.func.inc.php';
require CMSGO_ROOT.'/include/inc_front/content.func.inc.php';


// SEO logging
if(!empty($cmsgo['enable_seolog']) && !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME']) === false) {
    $cmsgo['seo_referrer_data'] = seReferrer( $_SERVER['HTTP_REFERER'] );
    if( is_array( $cmsgo['seo_referrer_data'] ) ) {
        $cmsgo['seo_referrer_data']['hash'] = md5(strtolower($cmsgo['seo_referrer_data']['domain'].$cmsgo['seo_referrer_data']['query']));
        @_dbInsert('cmsgo_log_seo', $cmsgo['seo_referrer_data'], 'DELAYED');
    }
}

$cmsgo["templates"]    = TEMPLATE_PATH;
$content['page_start']   = sprintf(
    CMSGO_DOCTYPE,
    $cmsgo['htmlhead_inject_prefix'],
    str_replace( '{DOCTYPE_LANG}', $cmsgo['DOCTYPE_LANG'], CMSGO_DOCTYPE_LANG ) . ' id="'.str_replace(array('.','/'), '-', CMSGO_HOST).'"',
    empty($content['htmltag_inject']) ? '' : ' '.$content['htmltag_inject'],
    $cmsgo['htmlhead_inject_suffix'],
    sprintf(CMSGO_HEADER_COMMENT, empty($cmsgo['header_comment']) ? '' : LF . ' ' . trim($cmsgo['header_comment']) . LF),
    $cmsgo['htmlhead_inject']
);

// Compatibility Mode
if(!empty($cmsgo['X-UA-Compatible'])) {
    $content['page_start']  .= '  <meta http-equiv="X-UA-Compatible" content="' . $cmsgo['X-UA-Compatible'] . '"'.HTML_TAG_CLOSE.LF;
}

// HTML5 does not like content-style-type
if($cmsgo['mode_XHTML'] != 3) {
    $content['page_start']  .= '  <meta http-equiv="content-type" content="' . $_use_content_type . '; charset='.CMSGO_CHARSET.'"'.HTML_TAG_CLOSE.LF;
    $content['page_start']  .= '  <meta http-equiv="content-style-type" content="text/css"'.HTML_TAG_CLOSE.LF;
} else {
    $content['page_start']  .= '  <meta charset="' . CMSGO_CHARSET . '"'.HTML_TAG_CLOSE.LF;
}

// Viewport setting
if(!empty($cmsgo['viewport'])) {
    $content['page_start']  .= '  <meta name="viewport" content="' . $cmsgo['viewport'] . '"'.HTML_TAG_CLOSE.LF;
}

// Base Href
if(!empty($cmsgo['base_href'])) {

    if($cmsgo['base_href'] === true) {
        $content['page_start'] .= '  <base href="'.CMSGO_URL.'"'.HTML_TAG_CLOSE . LF;
    } else {
        $content['page_start'] .= '  <base href="'.$cmsgo['base_href'].'"'.HTML_TAG_CLOSE . LF;
        $cmsgo['base_href']   = true;
    }

} else {

    $cmsgo['base_href'] = false;

}

$content['page_start']  .= '  <title>'.html_specialchars($content["pagetitle"]).'</title>'.LF;

// Deprecated custom page CSS
$content['page_start']  .= get_body_attributes($pagelayout);

// Add all CSS files here
if(count($block['css'])) {
    foreach($block['css'] as $value) {
        $content['page_start'] .= '  <link rel="stylesheet" type="text/css" href="'.TEMPLATE_PATH.'inc_css/' . str_replace(' ', '%20', $value) . '"'.HTML_TAG_CLOSE.LF;
    }
}

$content['page_start'] .= $block["htmlhead"];

if(!empty($cmsgo['IE7-js']) && $cmsgo['USER_AGENT']['agent'] == 'IE' && version_compare($cmsgo['USER_AGENT']['version'], '9.0', '<')) {
    $content['page_start'] .= '  <!--[if lt IE 9]><script type="text/javascript" src="'.TEMPLATE_PATH.'lib/ie7-js/IE9.js"></script><![endif]-->'.LF;
}

$content['page_start'] .= '</head>'.LF;

if(!$cmsgo['base_href'] && $cmsgo['rewrite_url'] && strpos($content['page_start'], '<base href') === false) {
    $content['page_start'] = str_replace('<title>', '<base href="'.CMSGO_URL.'"'.HTML_TAG_CLOSE . LF . '  <title>', $content['page_start']);
}

// inject body tag in case of class or id attribute
$content['page_start'] .= '<body';
if(!empty($template_default['body']['id'])) {
    $content['page_start'] .= ' id="'.$template_default['body']['id'].$content['body_id'].'"';
}
if(!empty($template_default['body']['class'])) {
    $content['page_start'] .= ' class="'.$template_default['body']['class'].$content['body_id'].'"';
}
$content['page_start'] .= '>'.LF;

//  this regex's inits rewrite
if(CMSGO_REWRITE) {
    $content["all"] = preg_replace_callback('/( href| action)(="index.php\?)([a-zA-Z0-9@,\.\+\-_\*#\/%=&;]+?)"/', 'url_search', $content["all"]);
    $content["all"] = preg_replace_callback('/onclick="location.href=\'index.php\?([a-zA-Z0-9@,\.\+\-_\*#\/%=&;]+?)\'/', 'js_url_search', $content["all"]);
    if(CMSGO_REWRITE_EXT && strpos($content["all"], CMSGO_REWRITE_EXT.'&amp;')) {
        $content['all'] = str_replace(CMSGO_REWRITE_EXT.'&amp;', CMSGO_REWRITE_EXT.'?', $content["all"]);
    };

    $content['all'] = str_replace('img/cmsimage.php', 'im', $content['all']);
    $content['page_start'] = str_replace('img/cmsimage.php', 'im', $content['page_start']);
}

$content['all'] = str_replace('{CMSGO_RESIZE_IMAGE}', CMSGO_RESIZE_IMAGE, $content['all']);

// real page ending
if(count($block['bodyjs'])) {
    $content['page_end'] .= implode(LF, $block['bodyjs']);
}
if(!empty($cmsgo['browser_check']['fe'])) {
    $content['page_end'] .= '<script'.SCRIPT_ATTRIBUTE_TYPE.'> var $buoop = {';
    if(!empty($cmsgo['browser_check']['vs'])) {
        $content['page_end'] .= 'vs:' . $cmsgo['browser_check']['vs'];
    }
    $content['page_end'] .= '}; </script><script'.SCRIPT_ATTRIBUTE_TYPE.' src="//browser-update.org/update.js"></script>';
}
$content['page_end'] .= LF.'</body>'.LF.'</html>';

if(!empty($cmsgo['render_clean_html'])) {
    $content['all'] = preg_replace('/<!--.+?-->/s', '', $content['all']);
}

// return rendered content
echo $content['page_start'];
echo $content["all"];
echo $content['page_end'];

// cmsgo Default header settings
if($cmsgo['cache_timeout']) {
    header('Expires: '.gmdate('D, d M Y H:i:s', time() + $cmsgo['cache_timeout']) .' GMT');
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', empty($row['article_date']) ? time() : $row['article_date']) .' GMT');
    header('Cache-Control: public, max-age='.$cmsgo['cache_timeout']);
    header('Pragma: public');
}

// write cmsgo release information in a custom HTTP header
header('X-cmsgo-Release: ' . CMSGO_VERSION);

// retrieve complete processing time
list($usec, $sec) = explode(' ', microtime());
header('X-cmsgo-Page-Processed-In: ' . number_format(1000*($usec + $sec - $cmsgo_rendering_start), 3) .' ms');

// print PDF
if($aktion[2] === 1 && defined('PRINT_PDF') && PRINT_PDF) {

    require_once CMSGO_ROOT.'/include/inc_front/pdf.inc.php';

// handle output action and section
} elseif($cmsgo['output_action']) {

    if(empty($cmsgo['output_function_filter']) || !is_array($cmsgo['output_function_filter'])) {
        $cmsgo['output_function_filter'] = array('trim', 'strip_tags');
    }

    $cmsgo['output_function'] = array_intersect($cmsgo['output_function_filter'], $cmsgo['output_function']);

    $content = ob_get_clean();

    $sections = '';

    foreach($cmsgo['output_section'] as $section) {

        $section = get_tmpl_section($section, $content);

        foreach($cmsgo['output_function'] as $function) {
            $section = $function($section);
        }

        $sections .= $section;
    }

    // Return sections content ONLY
    echo $sections;

    exit();
}

// send buffer to browser
ob_end_flush();
