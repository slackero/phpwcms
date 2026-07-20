<?php
/**
 * phpwcms jQuery FancyBox replacement for Lightbox (SlimBox)
 * version: 29 Nov 2016
 * @requires jQuery v1.6 or later
 *
 * Examples at http://fancyapps.com/fancybox/
 * License: http://www.fancyapps.com/fancybox/#license
 * Includes: TouchSwipe, http://labs.rampinteractive.co.uk/touchSwipe/
 *
 * Copyright 2012–2017 Oliver Georgi
 **/

if(substr($block['jslib'], 0, 6) === 'jquery' && version_compare(substr($block['jslib'], 7), '1.6', '>=')) {

	$phpwcms['fancybox_swipe_support'] = true;

	// Remove Slimbox support
	unset(
		$block['custom_htmlhead']['lightbox.css'],
		$block['custom_htmlhead']['slimbox.js']
	);

	set_css_link('lib/fancybox/jquery.fancybox.css');

	initJSPlugin('mousewheel.min');

	if($phpwcms['fancybox_swipe_support']) {
		$block['custom_htmlhead']['touchswipe.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/touchswipe/jquery.touchSwipe.min.js');
		$phpwcms['fancybox_swipe_js'] = 'fancybox.initSwipeOn';
	} else {
		$phpwcms['fancybox_swipe_js'] = 'fancybox.initSwipeOff';
	}

	$block['custom_htmlhead']['fancybox.js'] = getJavaScriptSourceLink(TEMPLATE_PATH.'lib/fancybox/jquery.fancybox.pack.js');

	initJSPlugin($phpwcms['fancybox_swipe_js']);
}
