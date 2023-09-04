<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

require_once(CMSGO_ROOT.'/include/inc_front/lib/js.jquery.default.php');

define('CMSGO_JSLIB', 'jquery-3.6-migrate-1');

/**
 * Init jQuery 3.7.x + jQuery Migrate 1.4.x & 3.4.x Library
 */
function initJSLib() {
    if(empty($GLOBALS['block']['custom_htmlhead']['jquery.js'])) {
        if(CMSGO_USE_CDN) {
            // use jQuery CDN
            if(IE8_CC) {
                $GLOBALS['block']['custom_htmlhead']['jquery-1.12.min.js'] = '  <!--[if lt IE 9]>' . getJavaScriptSourceLink(CMSGO_HTTP_SCHEMA.'://code.jquery.com/jquery-1.12.4.min.js', '') . '<![endif]-->';
                $GLOBALS['block']['custom_htmlhead']['jquery.js'] = '  <!--[if gte IE 9]><!-->' . getJavaScriptSourceLink(CMSGO_HTTP_SCHEMA.'://code.jquery.com/jquery-3.7.0.min.js', '') . '<!--<![endif]-->';
            } else {
                $GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(CMSGO_HTTP_SCHEMA.'://code.jquery.com/jquery-3.7.0.min.js');
            }
            $GLOBALS['block']['custom_htmlhead']['jquery-migrate.js'] = getJavaScriptSourceLink(CMSGO_HTTP_SCHEMA.'://code.jquery.com/jquery-migrate-1.4.1.min.js');
            $GLOBALS['block']['custom_htmlhead']['jquery-migrate-3.js'] = getJavaScriptSourceLink(CMSGO_HTTP_SCHEMA.'://code.jquery.com/jquery-migrate-3.4.0.min.js');
        } elseif(IE8_CC) {
            $GLOBALS['block']['custom_htmlhead']['jquery-1.12.min.js'] = '  <!--[if lt IE 9]>' . getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-1.12.4.min.js', '') . '<![endif]-->';
            $GLOBALS['block']['custom_htmlhead']['jquery.js'] = '  <!--[if gte IE 9]><!-->' . getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-3.7.0.min.js', '') . '<!--<![endif]-->';
            $GLOBALS['block']['custom_htmlhead']['jquery-migrate.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-migrate-1.4.1.min.js');
            $GLOBALS['block']['custom_htmlhead']['jquery-migrate-3.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-migrate-3.4.0.min.js');
        } else {
            $GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-3.7.0.min.js');
            $GLOBALS['block']['custom_htmlhead']['jquery-migrate.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-migrate-1.4.1.min.js');
            $GLOBALS['block']['custom_htmlhead']['jquery-migrate-3.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-migrate-3.4.0.min.js');
        }
    }
    return true;
}
