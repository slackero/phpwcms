<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2012, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

require_once(PHPWCMS_ROOT.'/include/inc_front/lib/js.jquery.default.php');

define('PHPWCMS_JSLIB', 'jquery-1.9');

/**
 * Init jQuery 1.9.x Library
 */
function initJSLib() {
	if(empty($GLOBALS['block']['custom_htmlhead']['jquery.js'])) {
		if(!USE_GOOGLE_AJAX_LIB) {
			$GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-1.9.min.js');
		} else {
			// at the moment not available on Google
			$GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink('//code.jquery.com/jquery.min.js'); //USE_GOOGLE_AJAX_LIB.'jquery/1.9/jquery.min.js');
		}
	}
	return TRUE;
}

?>