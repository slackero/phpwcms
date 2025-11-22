<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

require_once(CMSGO_ROOT.'/include/inc_front/lib/js.jquery.default.php');

const CMSGO_JSLIB = 'jquery';

/**
 * Init jQuery 1.3.x Library
 */
function initJSLib() {
	if(empty($GLOBALS['block']['custom_htmlhead']['jquery.js'])) {
		if(CMSGO_USE_CDN) {
			$GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(CMSGO_HTTP_SCHEMA.'://code.jquery.com/jquery-1.3.2.min.js');
		} else {
			$GLOBALS['block']['custom_htmlhead']['jquery.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/jquery-1.3.2.min.js');
		}
	}
	return TRUE;
}
