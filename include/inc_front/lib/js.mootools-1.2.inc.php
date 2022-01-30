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

define('PHPWCMS_JSLIB', 'mootools-1.2');

/**
 * Init Mootools 1.2.x Library
 */
function initJSLib() {
	if(empty($GLOBALS['block']['custom_htmlhead']['mootools.js'])) {
		if(PHPWCMS_USE_CDN) {
            $GLOBALS['block']['custom_htmlhead']['mootools.js'] = getJavaScriptSourceLink(PHPWCMS_HTTP_SCHEMA.'://ajax.googleapis.com/ajax/libs/mootools/1.2.6/mootools-yui-compressed.js');
        } else {
			$GLOBALS['block']['custom_htmlhead']['mootools.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/mootools/mootools-1.2-core-yc.js');
		}
	}
	return true;
}

/**
 * Initialize Slimbox CSS and JavaScript for MooTools 1.2
 */
function initSlimbox() {
	initJSLib();
	$GLOBALS['block']['custom_htmlhead']['lightbox.css'] = '  <link href="'.TEMPLATE_PATH.'lib/slimbox/slimbox.css" rel="stylesheet" type="text/css" media="screen" />';
	$GLOBALS['block']['custom_htmlhead']['slimbox.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/slimbox/slimbox.mootools-1.2.js');
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
 *
 * @param   string  $js
 * @param   false   $return
 * @param   string  $prefix
 *
 * @return string
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

		$GLOBALS['block']['custom_htmlhead'][] = $_js;
	}
}

/**
 * Create JavaScript UnLoad Section
 *
 * @param   string  $js
 * @param   false   $return
 * @param   string  $prefix
 *
 * @return string
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

		$GLOBALS['block']['custom_htmlhead'][] = $_js;
	}
}

/**
 * Simple MooTools Plugin Loader
 *
 * @param   string  $plugin
 * @param   false   $more
 *
 * @return bool
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
			$GLOBALS['block']['custom_htmlhead'][$plugin] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/mootools/plugin-1.2/'.$plugin);
		}
	} else {
		// Load MooTools More Plugins - simple Wrapper 'Fx/Fx.Slide,Fx/Fx.Scroll...'
		// it does not check dependends
		if(!is_array($plugin)) {
			$plugin = convertStringToArray($plugin);
		}
		if(count($plugin)) {
			initJSLib();
			// add mootools more core
			array_unshift($plugin, 'Core/More');
			foreach($plugin as $more) {
				if(empty($GLOBALS['block']['custom_htmlhead'][$more]) && is_file(PHPWCMS_TEMPLATE.'lib/mootools/more/'.$more.'.js')) {
					$GLOBALS['block']['custom_htmlhead'][$more] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/mootools/more/'.$more.'.js');
				}
			}
		}
	}
	return true;
}
