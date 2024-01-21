<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
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

$CNT_TMP .= headline($crow['acontent_title'], $crow['acontent_subtitle'], $template_default['article']);
$content['page_file'] = @unserialize($crow['acontent_form'], ['allowed_classes' => false]);
if(!empty($content['page_file']['source'])) {
	$CNT_TMP .= empty($content['page_file']['pfile']) ? '' : include_url($content['page_file']['pfile']);
} elseif(!empty($content['page_file']['pfile'])) {
    if (!empty($cmsgo['enable_inline_php'])) {
        $content['page_file']['pfile'] = include_ext_php($content['page_file']['pfile'], 1);
    } elseif (is_file(CMSGO_ROOT .'/' . $content['page_file']['pfile'])) {
        $content['page_file']['pfile'] = file_get_contents(CMSGO_ROOT .'/' . $content['page_file']['pfile']);
    } else {
        $content['page_file']['pfile'] = '';
    }
    $content['page_file']['pfile'] = trim($content['page_file']['pfile']);
    if ($content['page_file']['pfile']) {
        if (preg_match('/.*?<body[^>]*?>(.*?)<\/body>.*?/si', $content['page_file']['pfile'], $content['page_file']['match'])) {
            $CNT_TMP .= $content['page_file']['match'][1];
        } elseif (preg_match('/<[a-z][\s\S]*>/i', $content['page_file']['pfile'])) {
            $CNT_TMP .= $content['page_file']['pfile'];
        } else {
            $CNT_TMP .= html($content['page_file']['pfile']);
        }
    }
}
$CNT_TMP .= $crow['attr_class_id_close'];
unset($content['page_file']);
