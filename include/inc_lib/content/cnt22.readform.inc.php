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


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
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

if( isset($_POST['crss_contenttype']) && in_array($_POST['crss_contenttype'], $phpwcms['charsets']) ) {
	$content['rssfeed']["content_type"]	= $_POST['crss_contenttype'];
}
