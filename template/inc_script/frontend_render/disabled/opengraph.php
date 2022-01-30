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

// An example how to set the Open Graph meta tags
// For more visit:
// - http://ogp.me/
// - https://developers.facebook.com/docs/opengraph/using-objects#selfhosted
// - https://developers.facebook.com/docs/opengraph/howtos/maximizing-distribution-media-content#tags
if($content['opengraph']['support']) {

    if(empty($phpwcms['opengraph_imagesize'])) {
        $phpwcms['opengraph_imagesize'] = '1200x630x1';
    }

	set_meta('og:type', $content['opengraph']['type'], 'property');

	// maybe clean your title by sanitize_replacement_tags()
	set_meta('og:title', $content['opengraph']['title'], 'property');

	if(empty($content['opengraph']['url'])) {
		set_meta('og:url', abs_url(array(), array('phpwcms_output_action', 'print', 'phpwcms-preview', 'unsubscribe', 'subscribe')), 'property');
	} else {
		set_meta('og:url', $content['opengraph']['url'], 'property');
	}

	if(!empty($content['opengraph']['description'])) {
		// maybe clean description by sanitize_replacement_tags()
		set_meta('og:description', $content['opengraph']['description'], 'property');
	}

	// Open Graph images
	$content['opengraph']['has_image'] = false;
	if(isset($content['images']['shop']) && count($content['images']['shop'])) {
		foreach($content['images']['shop'] as $og_img) {
			$content['opengraph']['has_image'] = true;
			set_meta('og:image', PHPWCMS_URL.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms['opengraph_imagesize'].'/'.$og_img['hash'].'.'.$og_img['ext'], 'property', false, true);
		}
	}
	if(isset($content['images']['news']) && count($content['images']['news'])) {
		foreach($content['images']['news'] as $og_img) {
			$content['opengraph']['has_image'] = true;
			set_meta('og:image', PHPWCMS_URL.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms['opengraph_imagesize'].'/'.$og_img['id'].'.'.$og_img['ext'], 'property', false, true);
		}
	}
	if(isset($content['images']['article']['image'])) {
		$content['opengraph']['has_image'] = true;

		// Based on default article detail / zoom image
		//set_meta('og:image', PHPWCMS_URL . $content['images']['article'][ isset($content['images']['article']) ? 'zoom' : 'image' ]['src'], 'property');

		// This can be set dynamically ad allow always the same size
		set_meta('og:image', PHPWCMS_URL.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms['opengraph_imagesize'].'/'.$content['images']['article']['hash'].'.'.$content['images']['article']['ext'], 'property');
	}
	if(!$content['opengraph']['has_image']) {
		// Default Open Graph image
		set_meta('og:image', PHPWCMS_URL.TEMPLATE_PATH.'img/opengraph-default.png', 'property');
	}

	// Disable the built-in Open Graph rendering
	$content['opengraph']['support'] = false;
}
