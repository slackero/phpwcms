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

require_once(PHPWCMS_ROOT.'/include/inc_front/lib/js.jquery.default.php');

define('PHPWCMS_JSLIB', 'jquery-2.1-migrate');

/**
 * Init jQuery 2.1.x + jQuery Migrate Library
 */
function initJSLib() {
    if(empty($GLOBALS['block']['custom_htmlhead']['jquery.js'])) {
        if(PHPWCMS_USE_CDN) {
            // use jQuery CDN
            if(IE8_CC) {
                $GLOBALS['block']['custom_htmlhead']['jquery-1.11.min.js'] = '  <!--[if lt IE 9]>' . getJavaScriptSourceLink(PHPWCMS_HTTP_SCHEMA.'://code.jquery.com/jquery-1.11.3.min.js', '') . '<![endif]-->';
                $GLOBALS['block']['custom_htmlhead']['jquery.js'] = '  <!--[if gte IE 9]><!-->' . getJavaScriptSourceLink(PHPWCMS_HTTP_SCHEMA.'://code.jquery.com/jquery-2.1.4.min.js', '') . '<!--<![endif]-->';
            } else {
                $GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(PHPWCMS_HTTP_SCHEMA.'://code.jquery.com/jquery-2.1.4.min.js');
            }
            $GLOBALS['block']['custom_htmlhead']['jquery-migrate.js'] = getJavaScriptSourceLink(PHPWCMS_HTTP_SCHEMA.'://code.jquery.com/jquery-migrate-1.2.1.min.js');
        } elseif(IE8_CC) {
            $GLOBALS['block']['custom_htmlhead']['jquery-1.11.min.js'] = '  <!--[if lt IE 9]>' . getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-1.11.3.min.js', '') . '<![endif]-->';
            $GLOBALS['block']['custom_htmlhead']['jquery.js'] = '  <!--[if gte IE 9]><!-->' . getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-2.1.4.min.js', '') . '<!--<![endif]-->';
            $GLOBALS['block']['custom_htmlhead']['jquery-migrate.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-migrate-1.2.1.min.js');
        } else {
            $GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-2.1.4.min.js');
            $GLOBALS['block']['custom_htmlhead']['jquery-migrate.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-migrate-1.2.1.min.js');
        }
    }
    return true;
}
