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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Frontend JavaScript Wrapper

// set array for possible custom html head additions
$block['custom_htmlhead']   = array();

// set array to hold global onLoad and onDomReady events
$block['js_onunload']       = array();
$block['js_ondomready']     = array();
$block['js_inline']         = array();

// set default JS library
if(empty($block['jslib'])) {
    $block['jslib'] = key($phpwcms['js_lib']);
}

// set if CDN can be used
define('PHPWCMS_USE_CDN', empty($block['googleapi']) ? FALSE : TRUE);

// include the related JavaScript Library wrapper
@include PHPWCMS_ROOT.'/include/inc_front/lib/js.'.$block['jslib'].'.inc.php';

// check if selected JavaScript should be loaded permanently
if(!empty($block['jslibload'])) {
    initJSLib();
}

// check if frontend.js should be loaded always - it is  more for historic reasons
if(!empty($block['frontendjs'])) {
    initFrontendJS();
}

/**
 * Deprecated function to initialize the Slimbox
 */
function initializeLightbox() {
    initSlimbox();
}

/**
 * Init SwfObject JavaScript Library
 */
function initSwfObject() {
    if(empty($GLOBALS['block']['custom_htmlhead']['swfobject.js'])) {
        $GLOBALS['block']['custom_htmlhead']['swfobject.js'] = getJavaScriptSourceLink(PHPWCMS_USE_CDN ? PHPWCMS_HTTP_SCHEMA.'://ajax.googleapis.com/ajax/libs/swfobject/2/swfobject.js' : TEMPLATE_PATH.'lib/swfobject/swfobject.js');
    }
    return TRUE;
}

function initFrontendJS() {
    $GLOBALS['block']['custom_htmlhead']['frontend.js'] = '  <script src="'.TEMPLATE_PATH.'inc_js/frontend.js"'.SCRIPT_ATTRIBUTE_TYPE.'></script>';
}

function inlineJS($js='', $prefix=' ') {
    if($js) {
        $GLOBALS['block']['js_inline'][] = $prefix.$js;
    }
}

/**
 * render <!-- JS: PluginName|my.js //Whatever text -->
 */
function renderHeadJS($js) {

    if(is_array($js) && isset($js[1])) {
        $js = $js[1];
    }

    $js = trim($js);

    if(empty($js)) {
        return '';
    }

    // detect remote if `http://example.com`, `https://example.com` or `//example.com`
    $remote = substr($js, 0, 4) === 'http' || substr($js, 0, 2) === '//';

    if(!$remote && (strpos($js, ';') !== false || strpos($js, '//') !== false || strpos($js, '/*') !== false)) {

        if(strtolower(substr($js, 0, 5)) === 'ready') {
            $jsready = true;
            $js = trim(substr($js, 5));
        } else {
            $jsready = false;
        }

        $key = md5($js);

        // add the same section only once
        if(!$jsready && empty($GLOBALS['block']['custom_htmlhead'][$key])) {

            $GLOBALS['block']['custom_htmlhead'][$key]  = '  <script'.SCRIPT_ATTRIBUTE_TYPE.'>' . LF . SCRIPT_CDATA_START . LF . '  ';
            $GLOBALS['block']['custom_htmlhead'][$key] .= $js;
            $GLOBALS['block']['custom_htmlhead'][$key] .= LF . SCRIPT_CDATA_END . LF . '  </script>';

        } elseif($jsready && empty($GLOBALS['block']['custom_htmlhead']['jquery_ready']['ready_'.$key])) {

            $GLOBALS['block']['custom_htmlhead']['jquery_ready']['ready_'.$key] = $js;

        }

    } elseif($js == 'initJSLib') {

        initJSLib();

    } elseif($remote || which_ext($js) == 'js') { // decide if plugin or script

            // replace {TEMPLATE}
            $js     = str_replace('{TEMPLATE}', TEMPLATE_PATH, $js);
            $GLOBALS['block']['custom_htmlhead'][md5($js)] = getJavaScriptSourceLink(html($js));

    } else {

        initJSLib();

        if(strtolower($js) != 'initlib') {
            initJSPlugin($js);
        }
    }

    return '';

}

/**
 * Init Video JS
 */
function initVideoJs() {
    if(empty($GLOBALS['phpwcms']['video-js'])) {
        if(IE8_CC) {
            $GLOBALS['block']['custom_htmlhead']['video-js.ie8shim'] = '  <!--[if lt IE 9]><script src="'.PHPWCMS_HTTP_SCHEMA.'://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script><![endif]-->';
        }
        $GLOBALS['phpwcms']['video-js'] = PHPWCMS_HTTP_SCHEMA.'://vjs.zencdn.net/7.19.0/';
    } else {
        $GLOBALS['phpwcms']['video-js'] = rtrim($GLOBALS['phpwcms']['video-js'], '/') . '/';
    }
    $GLOBALS['block']['custom_htmlhead']['video-js.css'] = '  <link rel="stylesheet" type="text/css" href="' . $GLOBALS['phpwcms']['video-js'] . 'video-js.min.css" />';
}
