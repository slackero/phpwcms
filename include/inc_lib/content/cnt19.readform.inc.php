<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Content Type Guestbook
$content["sitemap"]["before"]			= slweg($_POST["csitemap_before"]);
$content["sitemap"]["after"] 			= slweg($_POST["csitemap_after"]);
$content["sitemap"]["catimg"]			= clean_slweg($_POST["csitemap_catimg"]);
$content["sitemap"]["articleimg"]		= clean_slweg($_POST["csitemap_articleimg"]);
$content["sitemap"]["display"]			= empty($_POST["csitemap_display"]) ? 0 : 1;
$content["sitemap"]["catclass"]			= clean_slweg($_POST["csitemap_catclass"]);
$content["sitemap"]["articleclass"]		= clean_slweg($_POST["csitemap_articleclass"]);
$content["sitemap"]["classcount"]		= intval($_POST["csitemap_classcount"]);
$content["sitemap"]["startid"]			= intval($_POST["csitemap_startid"]);
$content["sitemap"]["without_parent"]	= empty($_POST["csitemap_without_parent"]) ? 0 : 1;
