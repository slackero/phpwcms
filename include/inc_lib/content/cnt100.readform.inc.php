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
