<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------



// RSS feed
$content["rssfeed"]['rssurl'] 		= clean_slweg($_POST["crss_url"]);
$content["rssfeed"]["template"]		= clean_slweg($_POST["crss_template"]);
$content["rssfeed"]["item"]			= (intval($_POST["crss_item"])) ? intval($_POST["crss_item"]) : '';
$content['rssfeed']["cut1st"]		= isset($_POST["crss_cut1st"]) ? 1 : 0;
$content['rssfeed']["cacheoff"]		= isset($_POST["crss_cacheoff"]) ? 1 : 0;
$content['rssfeed']["timeout"]		= strval(intval($_POST['crss_timeout']));

$content['rssfeed']["content_type"]	= '';

if( isset($_POST['crss_contenttype']) && in_array($_POST['crss_contenttype'], $cmsgo['charsets']) ) {
	$content['rssfeed']["content_type"]	= $_POST['crss_contenttype'];
}
