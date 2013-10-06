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


/**
 * Initialize Slimbox CSS and JavaScript for jQuery
 */
function initSlimbox() {
	initJSLib();
	$GLOBALS['block']['custom_htmlhead']['lightbox.css'] = '  <link href="'.TEMPLATE_PATH.'lib/slimbox/slimbox.css" rel="stylesheet" type="text/css" media="screen" />';
	$GLOBALS['block']['custom_htmlhead']['slimbox.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/slimbox/slimbox.jquery.js');
}

/**
 * Initialize Frontend Edit DomReady JavaScript
 */
function init_frontend_edit_js() {

	initJSPlugin('switchClass');
	// based on work and idea by markus@localhorst (http://forum.phpwcms.org/viewtopic.php?f=8&t=19551)
	$GLOBALS['block']['js_ondomready'][] = '		$("a.fe-link").toggle();$("#fe-link").click(function(){$(this).switchClass("enabled","disabled");$("a.fe-link").toggle();});';

}

/**
 * Create JavaScript Domready Section
 */
function jsOnDomReady($js='', $return=false, $prefix='  ') {

	if($js) {

		initJSLib();

		$_js  = $prefix . '<script'.SCRIPT_ATTRIBUTE_TYPE.'>'.LF.SCRIPT_CDATA_START.LF;
		$_js .= '	jQuery(document).ready(function() {' . LF . $js . LF . '	});';
		$_js .= LF.SCRIPT_CDATA_END.LF.$prefix.'</script>';

		if($return) {
			return $_js;
		}

		$GLOBALS['block']['custom_htmlhead'][] = $_js;
	}
}

/**
 * Create JavaScript UnLoad Section
 */
function jsOnUnLoad($js='', $return=false, $prefix='  ') {

	if($js) {

		initJSLib();

		$_js  = $prefix . '<script'.SCRIPT_ATTRIBUTE_TYPE.'>'.LF.SCRIPT_CDATA_START.LF;
		$_js .= '	jQuery(window).unload(function() {' . LF . $js . LF . '	});';
		$_js .= LF.SCRIPT_CDATA_END.LF.$prefix.'</script>';

		if($return) {
			return $_js;
		}

		$GLOBALS['block']['custom_htmlhead'][] = $_js;
	}
}

/**
 * Simple jQuery Plugin Loader
 */
function initJSPlugin($plugin='') {
	$plugin = 'jquery.'.$plugin.'.js';
	if(empty($GLOBALS['block']['custom_htmlhead'][$plugin])) {
		initJSLib();
		$GLOBALS['block']['custom_htmlhead'][$plugin] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/jquery/plugin/'.$plugin);
	}
	return TRUE;
}

?>