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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Frontend JavaScript Wrapper

// set array for possible custom html head additions
$block['custom_htmlhead']	= array();

// set array to hold global onLoad and onDomReady events
$block['js_onunload']		= array();
$block['js_ondomready']		= array();
$block['js_inline']			= array();

// set default JS library
if(empty($block['jslib'])) {
	$block['jslib'] = key($phpwcms['js_lib']);
}

// set if Google Ajax Library should be used and if it should from which base URI
define('USE_GOOGLE_AJAX_LIB', !empty($block['googleapi']) || !isset($block['googleapi']) ? 'http://ajax.googleapis.com/ajax/libs/' : FALSE);

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
		if(!USE_GOOGLE_AJAX_LIB) {
			$GLOBALS['block']['custom_htmlhead']['swfobject.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/swfobject/swfobject.js');
		} else {
			$GLOBALS['block']['custom_htmlhead']['swfobject.js'] = getJavaScriptSourceLink(USE_GOOGLE_AJAX_LIB.'swfobject/2/swfobject.js');
		}
	}
	return TRUE;
}

function initFrontendJS() {
	$GLOBALS['block']['custom_htmlhead']['frontend.js'] = '  <script src="'.TEMPLATE_PATH.'inc_js/frontend.js" type="text/javascript"></script>';
}

function inlineJS($js='', $prefix='	') {
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
	
	$remote = substr($js, 0, 4) == 'http' ? true : false;

	if(!$remote && (strpos($js, ';') !== false || strpos($js, '//') !== false || strpos($js, '/*') !== false)) {
		
		$key = md5($js);
		
		// add the same section only once
		if(empty($GLOBALS['block']['custom_htmlhead'][$key])) {
					
			$GLOBALS['block']['custom_htmlhead'][$key]  = '  <script type="text/javascript">' . LF . SCRIPT_CDATA_START . LF . '	';
			$GLOBALS['block']['custom_htmlhead'][$key] .= $js;
			$GLOBALS['block']['custom_htmlhead'][$key] .= LF . SCRIPT_CDATA_END . LF . '  </script>';
			
		}
	
	} elseif($js == 'initJSLib') {
		
		initJSLib();
		
	} elseif($remote || which_ext($js) == 'js') { // decide if plugin or script
			
			// replace {TEMPLATE}
			$js		= str_replace('{TEMPLATE}', TEMPLATE_PATH, $js);
			$GLOBALS['block']['custom_htmlhead'][md5($js)] = getJavaScriptSourceLink(html_specialchars($js));
			
	} else {
		
		initJSLib();

		if(strtolower($js) != 'initlib') {
			initJSPlugin($js);		
		}
	}
	
	return '';
	
}


?>