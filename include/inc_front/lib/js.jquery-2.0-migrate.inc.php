<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2013, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

require_once(PHPWCMS_ROOT.'/include/inc_front/lib/js.jquery.default.php');

define('PHPWCMS_JSLIB', 'jquery-2.0-migrate');

/**
 * Init jQuery 2.0.x + jQuery Migrate Library
 */
function initJSLib() {
	if(empty($GLOBALS['block']['custom_htmlhead']['jquery.js'])) {
		if(!USE_GOOGLE_AJAX_LIB) {
			$GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-2.0.0.min.js');
			$GLOBALS['block']['custom_htmlhead']['jquery-migrate.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-migrate.min.js');
		} else {
			$GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(USE_GOOGLE_AJAX_LIB.'jquery/2.0.0/jquery.min.js');
			$GLOBALS['block']['custom_htmlhead']['jquery-migrate.js'] = getJavaScriptSourceLink('http://code.jquery.com/jquery-migrate-1.1.1.min.js'); // not available at Google
		}
	}
	return TRUE;
}

?>