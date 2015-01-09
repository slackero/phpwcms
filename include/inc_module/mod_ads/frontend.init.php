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

// Module/Plug-in Ads/Banner Management

// use it as when it is located under "template/inc_script/frontend_render"
// most times it is used to register custom function
// or make a very early redirection...

if(isset($_GET['adclickval'])) {

	// OK ADS CLICK set 
	include_once(dirname($value).'/inc/ads.fe_init.inc.php');

}


?>