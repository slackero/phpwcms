<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

require_once(PHPWCMS_ROOT.'/include/inc_front/lib/js.jquery.default.php');

const PHPWCMS_JSLIB = 'jquery-2.1';

/**
 * Init jQuery 2.1.x Library
 */
function initJSLib() {
    if(empty($GLOBALS['block']['custom_htmlhead']['jquery.js'])) {
        if(PHPWCMS_USE_CDN) {
            // use jQuery CDN
            
                $GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(PHPWCMS_HTTP_SCHEMA.'://code.jquery.com/jquery-2.1.4.min.js');
            }
        } else
            $GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-2.1.4.min.js');
        }
    }
    return true;
}
