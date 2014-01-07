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

// Just a simple example how to set the Open Graph meta tags
if(empty($content['images']['article']['image'])) {
	
	set_meta('og:image', 'http://example.com/default-image.jpg', 'property');
	
} else {
	
	// Based on default article detail / zoom image
	set_meta('og:image', PHPWCMS_URL . $content['images']['article'][ isset($content['images']['article']) ? 'zoom' : 'image' ]['src'], 'property');
	
	// This can be set dynamically and allow always the same size
	//set_meta('og:image', PHPWCMS_URL . 'img/cmsimage.php/1000x1000x0/'.$content['images']['article']['hash'].'.'.$content['images']['article']['ext'], 'property');
	
}



?>