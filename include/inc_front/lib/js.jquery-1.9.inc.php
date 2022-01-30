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

define('PHPWCMS_JSLIB', 'jquery-1.9');

/**
 * Init jQuery 1.9.x Library
 */
function initJSLib() {
	if(empty($GLOBALS['block']['custom_htmlhead']['jquery.js'])) {
		if(PHPWCMS_USE_CDN) {
			$GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(PHPWCMS_HTTP_SCHEMA.'://code.jquery.com/jquery-1.9.1.min.js');
		} else {
			$GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-1.9.1.min.js');
		}
	}
	return TRUE;
}
