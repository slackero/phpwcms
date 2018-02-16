<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

require_once(CMSGO_ROOT.'/include/inc_front/lib/js.jquery.default.php');

define('CMSGO_JSLIB', 'jquery-2.1');

/**
 * Init jQuery 2.1.x Library
 */
function initJSLib() {
    if(empty($GLOBALS['block']['custom_htmlhead']['jquery.js'])) {
        if(CMSGO_USE_CDN) {
            // use jQuery CDN
            if(IE8_CC) {
                $GLOBALS['block']['custom_htmlhead']['jquery-1.11.min.js'] = '  <!--[if lt IE 9]>' . getJavaScriptSourceLink(CMSGO_HTTP_SCHEMA.'://code.jquery.com/jquery-1.11.3.min.js', '') . '<![endif]-->';
                $GLOBALS['block']['custom_htmlhead']['jquery.js'] = '  <!--[if gte IE 9]><!-->' . getJavaScriptSourceLink(CMSGO_HTTP_SCHEMA.'://code.jquery.com/jquery-2.1.4.min.js', '') . '<!--<![endif]-->';
            } else {
                $GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(CMSGO_HTTP_SCHEMA.'://code.jquery.com/jquery-2.1.4.min.js');
            }
        } elseif(IE8_CC) {
            $GLOBALS['block']['custom_htmlhead']['jquery-1.11.min.js'] = '  <!--[if lt IE 9]>' . getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-1.11.3.min.js', '') . '<![endif]-->';
            $GLOBALS['block']['custom_htmlhead']['jquery.js'] = '  <!--[if gte IE 9]><!-->' . getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-2.1.4.min.js', '') . '<!--<![endif]-->';
        } else {
            $GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-2.1.4.min.js');
        }
    }
    return true;
}
