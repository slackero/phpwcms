<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type List
$content["text"] = html_specialchars(slweg($_POST["ctext"], 65500));

// check if minimum of 1 delimeter '~' available
if(substr($content['text'], 0, 1) != '~') $content['text'] = '~'.$content['text'];
if($content['text'] == '~') $content['text'] = '';

$content['bulletlist']["list_type"] = intval($_POST['clist_type']);
switch($content['bulletlist']["list_type"]) {
	case 0:
	case 1:
	case 2: 	break;
	default: 	$content['bulletlist']["list_type"] = 0;
}
