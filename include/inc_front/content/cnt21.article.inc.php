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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type external Pages
$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$content['page_file'] = @unserialize($crow["acontent_form"]);
if($content["page_file"]['source']) {
	$CNT_TMP .= include_url($content['page_file']['pfile']);
} else {
	$content['page_file']['pfile'] = include_ext_php($content['page_file']['pfile'], 1);
	if(preg_match('/.*?<body[^>]*?>(.*?)<\/body>.*?/si', $content['page_file']['pfile'], $content['page_file']['match'])) {
		$CNT_TMP .= $content['page_file']['match'][1];
	} else {
		$CNT_TMP .= $content['page_file']['pfile'];
	}
}
unset($content['page_file']);

?>