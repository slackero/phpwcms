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

// Content Type external Pages

$crow['attr_class_id'] = array();
if($crow['acontent_attr_class']) {
    $crow['attr_class_id'][] = 'class="'.html($crow['acontent_attr_class']).'"';
}
if($crow['acontent_attr_id']) {
    $crow['attr_class_id'][] = 'id="'.html($crow['acontent_attr_id']).'"';
}

if(($crow['attr_class_id'] = implode(' ', $crow['attr_class_id']))) {
    $CNT_TMP .= '<div '.$crow['attr_class_id'].'>';
    $crow['attr_class_id_close'] = '</div>';
} else {
    $crow['attr_class_id_close'] = '';
}

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$content['page_file'] = @unserialize($crow["acontent_form"]);
if($content["page_file"]['source']) {
	$CNT_TMP .= include_url($content['page_file']['pfile']);
} elseif(!empty($phpwcms['enable_inline_php'])) {
	$content['page_file']['pfile'] = include_ext_php($content['page_file']['pfile'], 1);
	if(preg_match('/.*?<body[^>]*?>(.*?)<\/body>.*?/si', $content['page_file']['pfile'], $content['page_file']['match'])) {
		$CNT_TMP .= $content['page_file']['match'][1];
	} else {
		$CNT_TMP .= $content['page_file']['pfile'];
	}
}
$CNT_TMP .= $crow['attr_class_id_close'];
unset($content['page_file']);
