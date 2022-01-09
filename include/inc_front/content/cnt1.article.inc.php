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

//image with text

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/imagetext.tmpl')) {

    $crow["acontent_template"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/imagetext.tmpl') );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/imagetext/'.$crow["acontent_template"])) {

    $crow["acontent_template"] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/imagetext/'.$crow["acontent_template"]) );

} else {

    $crow["acontent_template"] = '[IMAGETEXT]<div class="image-with-text">{IMAGETEXT}</div>[/IMAGETEXT]';

}

$crow["settings"] = get_tmpl_section('IMAGETEXT_SETTINGS', $crow["acontent_template"]);
$crow["settings"] = parse_ini_str($crow["settings"], false);

$crow["acontent_template"] = replace_tmpl_section('IMAGETEXT_SETTINGS', $crow["acontent_template"]);
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_ID', html($crow['acontent_attr_id']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TITLE', html($crow['acontent_title']));
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html($crow['acontent_subtitle']));

$crow['is_imagetext'] = strpos($crow["acontent_template"], '{IMAGETEXT}') !== false;
$crow['has_image'] = false;

// 0   :1       :2   :3        :4    :5     :6      :7       :8
// dbid:filename:hash:extension:width:height:caption:position:zoom
$image = $crow["acontent_image"] ? explode(":", $crow["acontent_image"]) : false;

$crow["default_settings"] = array(
    'class_top_left'            => $template_default['classes']['imgtxt-top-left'],
    'class_top_center'          => $template_default['classes']['imgtxt-top-center'],
    'class_top_right'           => $template_default['classes']['imgtxt-top-right'],
    'class_bottom_left'         => $template_default['classes']['imgtxt-bottom-left'],
    'class_bottom_center'       => $template_default['classes']['imgtxt-bottom-center'],
    'class_bottom_right'        => $template_default['classes']['imgtxt-bottom-right'],
    'class_float_left'          => $template_default['classes']['imgtxt-left'],
    'class_float_right'         => $template_default['classes']['imgtxt-right'],
    'class_column_left'         => $template_default['classes']['imgtxt-column-left'],
    'class_column_right'        => $template_default['classes']['imgtxt-column-right'],
    'class_column_left_image'   => $template_default['classes']['imgtxt-column-left-image'],
    'class_column_right_image'  => $template_default['classes']['imgtxt-column-right-image'],
    'class_column_left_text'    => $template_default['classes']['imgtxt-column-left-text'],
    'class_column_right_text'   => $template_default['classes']['imgtxt-column-right-text'],
    'width'                     => 0,
    'height'                    => 0,
    'zoom'                      => 0
);

if($image) {

    $cnt_image = @unserialize($crow["acontent_form"]);
    $crow["default_settings"]['lightbox']   = empty($cnt_image['cimage_lightbox']) ? 0 : 1;
    $crow["default_settings"]['nocaption']  = empty($cnt_image['cimage_nocaption']) ? 0 : 1;
    $crow["default_settings"]['crop']       = empty($cnt_image['cimage_crop']) ? 0 : 1;
    $crow["default_settings"]['width']      = empty($image[4]) ? 0 : intval($image[4]);
    $crow["default_settings"]['height']     = empty($image[5]) ? 0 : intval($image[5]);
    $crow["default_settings"]['zoom']       = empty($image[8]) ? 0 : 1;

}

$crow["settings"] = array_merge($crow["default_settings"], $crow["settings"]);

$image_text = '';

if($image) {

    $crow['has_image'] = true;

    if($crow["settings"]['lightbox']) {
        initSlimbox();
        $crow["settings"]['zoom'] = 1;
    }

    if($crow['is_imagetext']) {

        // load special functions
        require_once(PHPWCMS_ROOT.'/include/inc_front/img.func.inc.php');

        $GLOBALS['cnt_image_lightbox']  = $cnt_image_lightbox = $crow["settings"]['lightbox'];
        $image['nocaption']             = $crow["settings"]['nocaption'];
        $image['crop']                  = $crow["settings"]['crop'];
        $image[4]                       = $crow["settings"]['width'];
        $image[5]                       = $crow["settings"]['height'];
        $image[8]                       = $crow["settings"]['zoom'];

        switch($image[7]) {
            case 0: // top left
                $image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_top_left']);
                $image_text .= LF . $crow["acontent_text"];
                break;
            case 1: // top center
                $image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_top_center']);
                $image_text .= LF . $crow["acontent_text"];
                break;
            case 2: // top right
                $image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_top_right']);
                $image_text .= LF . $crow["acontent_text"];
                break;
            case 3: // bottom left
                $image_text .= $crow["acontent_text"] . LF;
                $image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_bottom_left']);
                break;
            case 4: // bottom center
                $image_text .= $crow["acontent_text"] . LF;
                $image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_bottom_center']);
                break;
            case 5: // bottom right
                $image_text .= $crow["acontent_text"] . LF;
                $image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_bottom_right']);
                break;
            case 6: // float left
                $image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_float_left']);
                $image_text .= LF . $crow["acontent_text"];
                break;
            case 7: // float right
                $image_text .= imagediv($phpwcms, $image, $crow["settings"]['class_float_right']);
                $image_text .= LF . $crow["acontent_text"];
                break;
            case 8: // column left
                $iconimg = imagediv($phpwcms, $image, $crow["settings"]['class_column_left_image']);
                if(trim($iconimg.$crow["acontent_text"])) {
                    $image_text .= '<div class="'.$crow["settings"]['class_column_left'].'">'.LF;
                    $image_text .= '    ' . $iconimg . LF;
                    $image_text .= '    <div class="'.$crow["settings"]['class_column_left_text'].'">'.$crow["acontent_text"].'</div>' . LF;
                    $image_text .= '</div>';
                }
                break;
            case 9: // column right
                $iconimg = imagediv($phpwcms, $image, $crow["settings"]['class_column_right_image']);
                if(trim($iconimg.$crow["acontent_text"])) {
                    $image_text .= '<div class="'.$crow["settings"]['class_column_right'].'">' . LF;
                    $image_text .= '    <div class="'.$crow["settings"]['class_column_right_text'].'">'.$crow["acontent_text"].'</div>' . LF;
                    $image_text .= '    ' . $iconimg . LF;
                    $image_text .= '</div>';
                }
                break;
        }

        unset($cnt_image);
        $GLOBALS['cnt_image_lightbox'] = $cnt_image_lightbox = 0;

    }

} else {

    $image_text .= $crow["acontent_text"];
    $image = array(
        0,
        '',
        '',
        '',
        0,
        0,
        '',
        0,
        0
    );

}

if ($crow['is_imagetext']) {
    $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'IMAGETEXT', $image_text);
}

$crow['imagetext_class'] = '';
$crow['position_type'] = array(
    'top_left'      => 0,
    'top_center'    => 0,
    'top_right'     => 0,
    'bottom_left'   => 0,
    'bottom_center' => 0,
    'bottom_right'  => 0,
    'float_left'    => 0,
    'float_right'   => 0,
    'column_left'   => 0,
    'column_right'  => 0
);

switch($image[7]) {
    case 1: // top center
        $crow['imagetext_class'] = $crow["settings"]['class_top_center'];
        $crow['position_type']['top_center'] = 1;
        break;
    case 2: // top right
        $crow['imagetext_class'] = $crow["settings"]['class_top_right'];
        $crow['position_type']['top_right'] = 1;
        break;
    case 3: // bottom left
        $crow['imagetext_class'] = $crow["settings"]['class_bottom_left'];
        $crow['position_type']['bottom_left'] = 1;
        break;
    case 4: // bottom center
        $crow['imagetext_class'] = $crow["settings"]['class_bottom_center'];
        $crow['position_type']['bottom_center'] = 1;
        break;
    case 5: // bottom right
        $crow['imagetext_class'] = $crow["settings"]['class_bottom_right'];
        $crow['position_type']['bottom_right'] = 1;
        break;
    case 6: // float left
        $crow['imagetext_class'] = $crow["settings"]['class_float_left'];
        $crow['position_type']['float_left'] = 1;
        break;
    case 7: // float right
        $crow['imagetext_class'] = $crow["settings"]['class_float_right'];
        $crow['position_type']['float_right'] = 1;
        break;
    case 8: // column left
        $crow['imagetext_class'] = $crow["settings"]['class_column_left'];
        $crow['position_type']['column_left'] = 1;
        break;
    case 9: // column right
        $crow['imagetext_class'] = $crow["settings"]['class_column_right'];
        $crow['position_type']['column_right'] = 1;
        break;
    case 0: // top left
    default:
        $crow['imagetext_class'] = $crow["settings"]['class_top_left'];
        $crow['position_type']['top_left'] = 1;
        break;
}

$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TOP_LEFT', $crow['position_type']['top_left'] ? ' ' : '');
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TOP_CENTER', $crow['position_type']['top_center'] ? ' ' : '');
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TOP_RIGHT', $crow['position_type']['top_right'] ? ' ' : '');
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'BOTTOM_LEFT', $crow['position_type']['bottom_left'] ? ' ' : '');
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'BOTTOM_CENTER', $crow['position_type']['bottom_center'] ? ' ' : '');
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'BOTTOM_RIGHT', $crow['position_type']['bottom_right'] ? ' ' : '');
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'FLOAT_RIGHT', $crow['position_type']['float_right'] ? ' ' : '');
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'FLOAT_LEFT', $crow['position_type']['float_left'] ? ' ' : '');
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'COLUMN_LEFT', $crow['position_type']['column_left'] ? ' ' : '');
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'COLUMN_RIGHT', $crow['position_type']['column_right'] ? ' ' : '');
$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ZOOM', $crow["settings"]['zoom'] ? ' ' : '');

if(!$crow['has_image']) {

    $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'IMAGE', '');
    $crow["acontent_template"] = str_replace('{IMAGE_ID}', 'empty-image-'.$crow["acontent_id"], $crow["acontent_template"]);
    $crow["acontent_template"] = str_replace('{IMAGE_HASH}', '', $crow["acontent_template"]);
    $crow["acontent_template"] = str_replace('{IMAGE_NAME}', 'empty', $crow["acontent_template"]);
    $crow["acontent_template"] = str_replace('{IMAGE_EXT}', '', $crow["acontent_template"]);
    $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'CAPTION', '');
    $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ALT', '');
    $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'IMAGE_TITLE', '');
    $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'COPYRIGHT', '');

} else {

    $crow["acontent_template"] = str_replace('{IMAGE_ID}', $image[0], $crow["acontent_template"]);
    $crow["acontent_template"] = str_replace('{IMAGE_HASH}', $image[2], $crow["acontent_template"]);
    $crow["acontent_template"] = str_replace('{IMAGE_EXT}', $image[3], $crow["acontent_template"]);
    $crow["acontent_template"] = str_replace('{IMAGE_NAME}', html($image[1]), $crow["acontent_template"]);

    $crow['image_tag']  = '<img src="' . PHPWCMS_RESIZE_IMAGE . '/' . $crow["settings"]['width'].'x'.$crow["settings"]['height'].'x'.$crow["settings"]['crop'] . '/';
    $crow['image_tag'] .= $image[2].'.'.$image[3].'/'.rawurlencode($image[1]).'" alt="';

    $caption = getImageCaption(array('caption' => base64_decode($image[6]), 'file' => $image[0]));
    $caption[1] = html(empty($caption[1]) ? $image[1] : $caption[1]);
    $crow['image_tag'] .= $caption[1];

    if($crow["settings"]['nocaption']) {
        $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'CAPTION', '');
        $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ALT', '');
        $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'IMAGE_TITLE', '');
        $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'COPYRIGHT', '');
    } else {
        $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'CAPTION', html($caption[0]));
        $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ALT', $caption[1]);
        if(empty($caption[3])) {
            $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'IMAGE_TITLE', '');
        } else {
            $caption[3] = html($caption[3]);
            $crow['image_tag'] .= '" title="'.$caption[3];
            $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'IMAGE_TITLE', $caption[3]);
        }
        $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'COPYRIGHT', empty($caption[4]) ? '' : html($caption[4]));
    }

    $crow['image_tag'] .= '"' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;

    $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'IMAGE', $crow['image_tag']);
}

$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'CLASS', $crow['imagetext_class']);
$crow["acontent_template"] = str_replace('{IMAGE_WIDTH}', $crow["settings"]['width'], $crow["acontent_template"]);
$crow["acontent_template"] = str_replace('{IMAGE_HEIGHT}', $crow["settings"]['width'], $crow["acontent_template"]);
$crow["acontent_template"] = str_replace('{ID}', $crow["acontent_id"], $crow["acontent_template"]);

$CNT_TMP .= render_cnt_template($crow["acontent_template"], 'TEXT', $crow["acontent_text"]);

unset($image);
