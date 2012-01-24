<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/


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
		
		$_js  = $prefix . '<script type="text/javascript">'.LF.SCRIPT_CDATA_START.LF;
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
		
		$_js  = $prefix . '<script type="text/javascript">'.LF.SCRIPT_CDATA_START.LF;
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