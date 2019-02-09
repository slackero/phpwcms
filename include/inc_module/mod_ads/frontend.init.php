<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2019, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Module/Plug-in Ads/Banner Management

// use it as when it is located under "template/inc_script/frontend_render"
// most times it is used to register custom function
// or make a very early redirection...

if(isset($_GET['adclickval'])) {

	// OK ADS CLICK set
	include_once dirname($value.'/inc/ads.fe_init.inc.php');

}
