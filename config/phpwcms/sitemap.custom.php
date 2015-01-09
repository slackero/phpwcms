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

function phpwcms_getCustomSitemap(&$struct) {
	
	// Check sitemap.php to see how $struct is used there
	global $phpwcms;
	
	// Avoid rendering Base sitemap link (default URL)
	//$phpwcms['sitemap_set_base'] = false;
	
	// Avoid default sitemap rendering, otherwise used in addition
	//$phpwcms['sitemap_set_default'] = false;
	
	$url = array(
	//	array('url' => 'http://www.webverbund.de', 'date' => '')
	);
	
	// Do everything here needed to build your custom sitemap links 
	// ...
	
	return $url;
}

?>