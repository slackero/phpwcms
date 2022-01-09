<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// set page processiong start time
list($usec, $sec) = explode(' ', microtime());
$phpwcms_rendering_start = $usec + $sec;

// define some general vars
$content            = array();
$phpwcms            = array();
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
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';

// Get user Agent BOT check
$IS_A_BOT = $phpwcms['USER_AGENT']['bot'];

// start session - neccessary if frontend users are available
// but neccessary also to check if a bot is visiting the site
// -> if so then do not initialize session for larger search engines
if(!$IS_A_BOT && (!empty($phpwcms['SESSION_FEinit']) || isset($_GET['phpwcms-preview']))) {
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

require_once PHPWCMS_ROOT.'/include/config/conf.template_default.inc.php';
require_once PHPWCMS_ROOT.'/include/config/conf.indexpage.inc.php';
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
        $phpwcms['seo_referrer_data']['hash'] = md5(strtolower($phpwcms['seo_referrer_data']['domain'].$phpwcms['seo_referrer_data']['query']));
        @_dbInsert('phpwcms_log_seo', $phpwcms['seo_referrer_data'], 'DELAYED');
    }
}

$phpwcms["templates"]    = TEMPLATE_PATH;
$content['page_start']   = sprintf(
    PHPWCMS_DOCTYPE,
    $phpwcms['htmlhead_inject_prefix'],
    str_replace( '{DOCTYPE_LANG}', $phpwcms['DOCTYPE_LANG'], PHPWCMS_DOCTYPE_LANG ) . ' id="'.str_replace(array('.','/'), '-', PHPWCMS_HOST).'"',
    empty($content['htmltag_inject']) ? '' : ' '.$content['htmltag_inject'],
    $phpwcms['htmlhead_inject_suffix'],
    sprintf(PHPWCMS_HEADER_COMMENT, empty($phpwcms['header_comment']) ? '' : LF . ' ' . trim($phpwcms['header_comment']) . LF),
    $phpwcms['htmlhead_inject']
);

// Compatibility Mode
if(!empty($phpwcms['X-UA-Compatible'])) {
    $content['page_start']  .= '  <meta http-equiv="X-UA-Compatible" content="' . $phpwcms['X-UA-Compatible'] . '"'.HTML_TAG_CLOSE.LF;
}

// HTML5 does not like content-style-type
if($phpwcms['mode_XHTML'] != 3) {
    $content['page_start']  .= '  <meta http-equiv="content-type" content="' . $_use_content_type . '; charset='.PHPWCMS_CHARSET.'"'.HTML_TAG_CLOSE.LF;
    $content['page_start']  .= '  <meta http-equiv="content-style-type" content="text/css"'.HTML_TAG_CLOSE.LF;
} else {
    $content['page_start']  .= '  <meta charset="' . PHPWCMS_CHARSET . '"'.HTML_TAG_CLOSE.LF;
}

// Viewport setting
if(!empty($phpwcms['viewport'])) {
    $content['page_start']  .= '  <meta name="viewport" content="' . $phpwcms['viewport'] . '"'.HTML_TAG_CLOSE.LF;
}

// Base Href
if(!empty($phpwcms['base_href'])) {

    if($phpwcms['base_href'] === true) {
        $content['page_start'] .= '  <base href="'.PHPWCMS_URL.'"'.HTML_TAG_CLOSE . LF;
    } else {
        $content['page_start'] .= '  <base href="'.$phpwcms['base_href'].'"'.HTML_TAG_CLOSE . LF;
        $phpwcms['base_href']   = true;
    }

} else {

    $phpwcms['base_href'] = false;

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

if(!empty($phpwcms['IE7-js']) && $phpwcms['USER_AGENT']['agent'] == 'IE' && version_compare($phpwcms['USER_AGENT']['version'], '9.0', '<')) {
    $content['page_start'] .= '  <!--[if lt IE 9]><script type="text/javascript" src="'.TEMPLATE_PATH.'lib/ie7-js/IE9.js"></script><![endif]-->'.LF;
}

$content['page_start'] .= '</head>'.LF;

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
if(PHPWCMS_REWRITE) {
    $content["all"] = preg_replace_callback('/( href| action)(="index.php\?)([a-zA-Z0-9@,\.\+\-_\*#\/%=&;]+?)"/', 'url_search', $content["all"]);
    $content["all"] = preg_replace_callback('/onclick="location.href=\'index.php\?([a-zA-Z0-9@,\.\+\-_\*#\/%=&;]+?)\'/', 'js_url_search', $content["all"]);
    if(PHPWCMS_REWRITE_EXT && strpos($content["all"], PHPWCMS_REWRITE_EXT.'&amp;')) {
        $content['all'] = str_replace(PHPWCMS_REWRITE_EXT.'&amp;', PHPWCMS_REWRITE_EXT.'?', $content["all"]);
    }

    $content['all'] = str_replace('img/cmsimage.php', 'im', $content['all']);
    $content['page_start'] = str_replace('img/cmsimage.php', 'im', $content['page_start']);
}

$content['all'] = str_replace('{PHPWCMS_RESIZE_IMAGE}', PHPWCMS_RESIZE_IMAGE, $content['all']);

// real page ending
if(count($block['bodyjs'])) {
    $content['page_end'] .= implode(LF, $block['bodyjs']);
}
if(!empty($phpwcms['browser_check']['fe'])) {
    $buoop = array('insecure' => isset($phpwcms['browser_check']['insecure']) ? boolval($phpwcms['browser_check']['insecure']) : true);
    if(!empty($phpwcms['browser_check']['vs'])) {
        $buoop['vs'] = $phpwcms['browser_check']['vs'];
    }
    if(!empty($phpwcms['browser_check']['required'])) {
        $buoop['required'] = '{' . trim($phpwcms['browser_check']['required'], '{}') . '}';
    }
    $content['page_end'] .= '<script'.SCRIPT_ATTRIBUTE_TYPE.'>var $buoop = ' . json_encode($buoop) . '; </script>';
    $content['page_end'] .= '<script'.SCRIPT_ATTRIBUTE_TYPE.' src="https://browser-update.org/update.min.js"></script>';
}
$content['page_end'] .= LF.'</body>'.LF.'</html>';

if(!empty($phpwcms['render_clean_html'])) {
    $content['all'] = preg_replace('/<!--.+?-->/s', '', $content['all']);
}

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
if(empty($phpwcms['disable_generator'])) {
    header('X-phpwcms-Release: ' . PHPWCMS_VERSION);
}

// retrieve complete processing time
if(empty($phpwcms['disable_processed_in'])) {
    list($usec, $sec) = explode(' ', microtime());
    header('X-phpwcms-Page-Processed-In: ' . number_format(1000 * ($usec + $sec - $phpwcms_rendering_start), 3) . ' ms');
}

// print PDF
if($aktion[2] === 1 && defined('PRINT_PDF') && PRINT_PDF) {

    require_once PHPWCMS_ROOT.'/include/inc_front/pdf.inc.php';

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

    // Return sections content ONLY
    echo $sections;

    exit();
}

// send buffer to browser
ob_end_flush();
