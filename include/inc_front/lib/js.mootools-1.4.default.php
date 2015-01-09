<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/


/**
 * Initialize Slimbox CSS and JavaScript for MooTools 1.3+
 */
function initSlimbox() {
	initJSLib();
	$GLOBALS['block']['custom_htmlhead']['lightbox.css'] = '  <link href="'.TEMPLATE_PATH.'lib/slimbox/slimbox.css" rel="stylesheet" type="text/css" media="screen" />';
	$GLOBALS['block']['custom_htmlhead']['slimbox.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/slimbox/slimbox.mootools-1.3.js');
}

/**
 * Initialize Frontend Edit DomReady JavaScript
 */
function init_frontend_edit_js() {

	initJSLib();
	$GLOBALS['block']['js_ondomready'][] = '		var felink_status = 0;
		$$("a.fe-link").each(function(r) { r.setStyle("display", "none"); });
		$("fe-link").addEvent("click", function() {
			if(felink_status == 1) {
				$$("a.fe-link").each(function(r) { r.setStyle("display", "none"); });
				$("fe-link").removeClass("enabled"); $("fe-link").addClass("disabled");	felink_status = 0;
			} else {
				$$("a.fe-link").each(function(r) { r.setStyle("display", ""); });
				$("fe-link").removeClass("disabled"); $("fe-link").addClass("enabled");	felink_status = 1;
			}
		});';

}

/**
 * Create JavaScript Domready Section
 */
function jsOnDomReady($js='', $return=false, $prefix='  ') {

	if($js) {

		initJSLib();

		$_js  = $prefix . '<script'.SCRIPT_ATTRIBUTE_TYPE.'>'.LF.SCRIPT_CDATA_START.LF;
		$_js .= '	window.addEvent("domready", function() {' . LF. $js . LF . '	});';
		$_js .= LF.SCRIPT_CDATA_END.LF.$prefix.'</script>';

		if($return) {
			return $_js;
		}

		$GLOBALS['block']['custom_htmlhead'][md5($js)] = $_js;
	}
}

/**
 * Create JavaScript UnLoad Section
 */
function jsOnUnLoad($js='', $return=false, $prefix='  ') {

	if($js) {

		initJSLib();

		$_js  = $prefix . '<script'.SCRIPT_ATTRIBUTE_TYPE.'>'.LF.SCRIPT_CDATA_START.LF;
		$_js .= '	window.addEvent("unload", function() {' . LF . $js . LF . '	});';
		$_js .= LF.SCRIPT_CDATA_END.LF.$prefix.'</script>';

		if($return) {
			return $_js;
		}

		$GLOBALS['block']['custom_htmlhead'][md5($js)] = $_js;
	}
}


/**
 * Simple MooTools Plugin Loader
 */
function initJSPlugin($plugin='', $more=false) {
	// enhance teplate JS parser for MooTools More
	// sample: <!-- JS: MORE:Fx/Fx.Elements,Fx/Fx.Accordion -->
	if(is_string($plugin) && $more === false && substr(strtoupper($plugin), 0, 5) == 'MORE:') {
		$plugin	= trim(substr($plugin, 5));
		$more	= true;
	}
	if($more === false) {
		$plugin = 'mootools.'.$plugin.'.js';
		if(empty($GLOBALS['block']['custom_htmlhead'][$plugin])) {
			initJSLib();
			$GLOBALS['block']['custom_htmlhead'][$plugin] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/mootools/plugin-1.4/'.$plugin);
		}
	} else {
		// Load MooTools More Plugins - simple Wrapper 'Fx/Fx.Slide,Fx/Fx.Scroll...'
		// it does not check dependents
		if(!is_array($plugin)) {
			$plugin = convertStringToArray($plugin);
		}
		if(count($plugin)) {
			initJSLib();
			// add mootools more core
			array_unshift($plugin, 'More/More');
			foreach($plugin as $more) {
				if(empty($GLOBALS['block']['custom_htmlhead'][$more]) && is_file(PHPWCMS_TEMPLATE.'lib/mootools/more-1.4/'.$more.'.js')) {
					$GLOBALS['block']['custom_htmlhead'][$more] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/mootools/more-1.4/'.$more.'.js');
				}
			}
		}
	}
	return TRUE;
}

?>