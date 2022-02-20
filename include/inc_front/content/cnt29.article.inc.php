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

// 2009-07-22 Thumbnail max width and max height replacement tag implemented
//            Thanks to Gerd Müller for proposal and code sample

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//images (gallery)
$image = @unserialize($crow["acontent_form"]);
$crow['acontent_template_listmode'] = empty($crow['acontent_template_listmode']) ? false : true;

// get template
if($crow['acontent_template_listmode'] && empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/list.images.tmpl')) {

    $image['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/list.images.tmpl') );

} elseif(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/images.tmpl')) {

    $image['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/images.tmpl') );

} elseif($crow['acontent_template_listmode'] && is_file(PHPWCMS_TEMPLATE.'inc_cntpart/images/list.'.$crow["acontent_template"])) {

    $image['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/images/list.'.$crow["acontent_template"]) );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/images/'.$crow["acontent_template"])) {

    $image['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/images/'.$crow["acontent_template"]) );

} else {

    $image['template'] = '';

}

if($image['template']) {

    $image['tmpl_settings'] = parse_ini_str( get_tmpl_section('IMAGE_SETTINGS', $image['template']), false);

    if(is_array($image['tmpl_settings']) && count($image['tmpl_settings'])) {
        $image = array_merge($image, $image['tmpl_settings']);
    }

    $image['tmpl_header']           = get_tmpl_section('IMAGES_HEADER', $image['template']);
    $image['tmpl_footer']           = get_tmpl_section('IMAGES_FOOTER', $image['template']);
    $image['tmpl_entry']            = get_tmpl_section('IMAGES_ENTRY', $image['template']);
    $image['tmpl_entry_space']      = get_tmpl_section('IMAGES_ENTRY_SPACER', $image['template']);
    $image['tmpl_row_space']        = get_tmpl_section('IMAGES_ROW_SPACER', $image['template']);

    $image['tmpl_thumb_width_max']  = 0;
    $image['tmpl_thumb_height_max'] = 0;
    $image['tmpl_images']           = array();

    $image['template']              = $image['tmpl_header'];
    $image['tmpl_data']             = array();

    if(empty($image['center_image'])) {
        $image['center_image'] = 0;
    }

    $image['center_image_class'] = '';

    switch($image['center_image']) {

        case 1:
            // center horizontal/vertical
            if(!$image['width'] && !$image['height']) {
                $image['center_image'] = 0;
            } elseif(!$image['width']) {
                $image['center_image'] = 3;
                $image['center_image_class'] = empty($image['image_class_center_vertical']) ? $template_default['classes']['image-center-vertical'] : $image['image_class_center_vertical'];
            } elseif(!$image['height']) {
                $image['center_image'] = 2;
                $image['center_image_class'] = empty($image['image_class_center_horizontal']) ? $template_default['classes']['image-center-horizontal'] : $image['image_class_center_horizontal'];
            } else {
                $image['center_image_class'] = empty($image['image_class_center_center']) ? $template_default['classes']['image-center-center'] : $image['image_class_center_center'];
            }
            break;

        case 2:
            // center horizontal
            if(!$image['width']) {
                $image['center_image'] = 0;
            } else {
                $image['center_image_class'] = empty($image['image_class_center_horizontal']) ? $template_default['classes']['image-center-horizontal'] : $image['image_class_center_horizontal'];
            }
            break;

        case 3:
            // center vertical
            if(!$image['height']) {
                $image['center_image'] = 0;
            } else {
                $image['center_image_class'] = empty($image['image_class_center_vertical']) ? $template_default['classes']['image-center-vertical'] : $image['image_class_center_vertical'];
            }
            break;

        default:
            $image['center_image'] = 0;
    }

    if(empty($image['thumb_class'])) {
        $image['thumb_class'] = '';
    }
    $image['thumb_class'] = trim($template_default['classes']['image-thumb'] . ' ' . $image['thumb_class']);

    if($image['center_image_class']) {
        $image['thumb_class'] = trim($image['thumb_class'].' '.$image['center_image_class']);
    }

    $total = 0;

    if(is_array($image['images']) && ($image['count'] = count($image['images']))) {

        // Start lightbox
        if(empty($image['lightbox'])) {
            $image['lightbox'] = false;
        } else {
            initSlimbox();
            $image['lightbox'] = generic_string(5);
        }

        $image['crop'] = empty($image['crop']) ? 0 : 1;

        $x      = 0;
        $col    = 0;

        // Randomize?
        if(!empty($image['random']) && $image['count'] > 1) {
            shuffle($image['images']);
        }
        // Limit images?
        $image['limit'] = empty($image['limit']) ? 0 : intval($image['limit']);
        $image['hide_limited'] = isset($image['hide_limited']) ? intval($image['hide_limited']) : 0;
        if($image['limit'] && $image['count'] > $image['limit']) {
            if(!$image['hide_limited']) {
                $image['count'] = $image['limit'];
                $image['images'] = array_slice($image['images'], 0, $image['limit']);
            }
        }

        foreach($image['images'] as $key => $value) {

            $col++;
            $total++;

            // put spacer content between images
            if($col > 1) {

                $image['tmpl_images'][$x] .= $image['tmpl_entry_space'];

            } else {

                $image['tmpl_images'][$x]  = '';

            }

            $this_image_name = $image['images'][$key][2] . '.' . $image['images'][$key][3];

            $thumb_image = get_cached_image(array(
                "target_ext"    =>  $image['images'][$key][3],
                "image_name"    =>  $image['images'][$key][2] . '.' . $image['images'][$key][3],
                "max_width"     =>  $image['width'],
                "max_height"    =>  $image['height'],
                "thumb_name"    =>  md5($image['images'][$key][2].$image['width'].$image['height'].$phpwcms["sharpen_level"].$image['crop'].$phpwcms['colorspace']),
                'crop_image'    =>  $image['crop']
            ));

            if($image['zoom']) {

                $zoominfo = get_cached_image(array(
                    "target_ext"    =>  $image['images'][$key][3],
                    "image_name"    =>  $image['images'][$key][2] . '.' . $image['images'][$key][3],
                    "max_width"     =>  $phpwcms["img_prev_width"],
                    "max_height"    =>  $phpwcms["img_prev_height"],
                    "thumb_name"    =>  md5($image['images'][$key][2].$phpwcms["img_prev_width"].$phpwcms["img_prev_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                ));
            }

            if(strpos($image['tmpl_entry'], '[LANDSCAPE') !== false) {

                $img_landscape = false;

                if(!empty($thumb_image['svg'])) {

                    $img_landscape = $image['height'] > $image['width'] ? '' : 'L';

                } elseif(is_file(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$image['images'][$key][2] . '.' . $image['images'][$key][3])) {

                    $img_landscape = @getimagesize(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$image['images'][$key][2] . '.' . $image['images'][$key][3]);
                    $img_landscape = $img_landscape ? ($img_landscape[1] > $img_landscape[0] ? '' : 'L') : false;

                }
            } else {

                $img_landscape = null;

            }

            // now try to build caption and if neccessary add alt to image or set external link for image
            $caption = getImageCaption(array('caption' => $image['images'][$key][6], 'file' => $image['images'][$key][0]));
            // set caption and ALT Image Text for imagelist
            $caption[0] = html($caption[0]);

            if(empty($caption[3])) {
                $capt_title = '';
                $caption[3] = '';
            } else {
                $caption[3] = html($caption[3]);
                $capt_title = $caption[3];
            }
            $caption[1] = html(empty($caption[1]) ? $image['images'][$key][1] : $caption[1]);
            if($caption[4]) {
                $caption[4] = html($caption[4]);
            }

            $img_thumb_name     = $thumb_image[0];
            $img_thumb_rel      = $thumb_image['src'];
            $img_thumb_abs      = PHPWCMS_URL.$thumb_image['src'];
            $img_thumb_width    = $thumb_image[1];
            $img_thumb_height   = $thumb_image[2];
            $img_thumb_link     = '';
            $img_thumb_ext      = which_ext($thumb_image[0]);

            $list_img_temp  = '<img src="'.$thumb_image['src'].'" data-image-ext="'.$img_thumb_ext.'" ';
            $list_img_temp .= 'data-image-id="'.$image['images'][$key][0].'" data-image-hash="'.$image['images'][$key][2].'" ';

            if($image['center_image']) {

                $img_margin_left    = 0;
                $img_margin_right   = 0;
                $img_margin_top     = 0;
                $img_margin_bottom  = 0;

                // center hor/vert
                if($image['center_image'] == 1 || $image['center_image'] == 2) {
                    $img_margin_left    = ceil( ($image['width'] - $thumb_image[1]) / 2 );
                    $img_margin_right   = $image['width'] - $thumb_image[1] - $img_margin_left;
                }
                if($image['center_image'] == 1 || $image['center_image'] == 3) {
                    $img_margin_top     = ceil( ($image['height'] - $thumb_image[2]) / 2 );
                    $img_margin_bottom  = $image['height'] - $thumb_image[2] - $img_margin_top;
                }

                $list_img_style     = 'style="margin:'.$img_margin_top.'px '.$img_margin_right.'px '.$img_margin_bottom.'px '.$img_margin_left.'px;" ';
                $list_ahref_style   = '';
                $list_img_temp     .= $list_img_style;

            } else {
                $list_img_style     = '';
                $list_ahref_style   = '';
            }
            $list_img_temp .= $thumb_image[3].' alt="'.$caption[1].'"';
            if($caption[3]) {
                $list_img_temp .= ' title="'.$caption[3].'"';
            }
            $list_img_temp .= ' class="' . $image['thumb_class'] . '"' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;
            $img_a          = '';

            if($image['zoom'] && isset($zoominfo) && $zoominfo != false) {
                // if click enlarge the image
                $open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo['src'], $zoominfo[3], $image['images'][$key][1]);
                if($caption[2][0]) {
                    $open_link = $caption[2][0];
                    $return_false = '';
                } else {
                    $open_link = $open_popup_link;
                    $return_false = 'return false;';
                }

                if(!$image['lightbox'] || $caption[2][0]) {
                    $img_thumb_link  = '<a href="'.$open_link."\" onclick=\"checkClickZoom();clickZoom('".$open_popup_link."','previewpic','width=";
                    $img_thumb_link .= $zoominfo[1].",height=".$zoominfo[2]."');".$return_false.'"'.$caption[2][1];
                    $img_thumb_link .= $list_ahref_style.' class="'.$template_default['classes']['image-zoom'].'">';
                } else {
                    // Gallery image
                    $img_thumb_link  = '<a href="'.$zoominfo['src'].'" rel="lightbox['.$image['lightbox'].']"'.get_attr_data_gallery($image['lightbox'], ' ', ' ');
                    if($caption[0]) {
                        $img_thumb_link .= 'title="'.parseLightboxCaption($caption[0]).'" ';
                    } elseif(strpos($image['tmpl_entry'], '{IMGNAME}')) {
                        $img_thumb_link .= 'title="'.parseLightboxCaption( $image['images'][$key][1] ).'" ';
                    }
                    $img_thumb_link .= $list_ahref_style.'class="'.$template_default['classes']['image-lightbox'].'">';
                }

                $img_a .= $img_thumb_link.$list_img_temp.'</a>';

                $img_zoom_name      = $zoominfo[0];
                $img_zoom_rel       = $zoominfo['src'];
                $img_zoom_abs       = PHPWCMS_URL.$zoominfo['src'];
                $img_zoom_width     = $zoominfo[1];
                $img_zoom_height    = $zoominfo[2];

            } else {
                // if not click enlarge
                if($caption[2][0]) {
                    $img_thumb_link = '<a href="'.$caption[2][0].'" '.$list_ahref_style.$caption[2][1].' class="'.$template_default['classes']['image-link'].'">';
                    $img_a .= $img_thumb_link.$list_img_temp.'</a>';
                } else {
                    $img_a .= $list_img_temp;
                }

                $img_zoom_name      = '';
                $img_zoom_rel       = '';
                $img_zoom_abs       = '';
                $img_zoom_width     = 0;
                $img_zoom_height    = 0;
            }

            $img_a = str_replace('{IMAGE}', $img_a, $image['tmpl_entry']);
            $img_a = str_replace('{IMGID}', $key, $img_a);
            $img_a = str_replace('{IMAGE_ID}', $image['images'][$key][0], $img_a);
            $img_a = str_replace('{IMAGE_HASH}', $image['images'][$key][2], $img_a);
            $img_a = str_replace('{IMGNAME}', html_specialchars($image['images'][$key][1]), $img_a);
            $img_a = str_replace('{ENTRY_ID}', $total-1, $img_a);
            $img_a = str_replace('{ENTRY_NUM}', $total, $img_a);

            // replace thumbnail and zoom image information
            $img_a = str_replace('{THUMB_NAME}',    $img_thumb_name, $img_a);
            $img_a = str_replace('{THUMB_REL}',     $img_thumb_rel, $img_a);
            $img_a = str_replace('{THUMB_ABS}',     $img_thumb_abs, $img_a);
            $img_a = str_replace('{THUMB_WIDTH}',   $img_thumb_width, $img_a);
            $img_a = str_replace('{THUMB_HEIGHT}',  $img_thumb_height, $img_a);

            $image['tmpl_thumb_width_max']  = max($image['tmpl_thumb_width_max'], $img_thumb_width);
            $image['tmpl_thumb_height_max'] = max($image['tmpl_thumb_height_max'], $img_thumb_height);

            $img_a = str_replace('{IMAGE_NAME}',    $img_zoom_name, $img_a);
            $img_a = str_replace('{IMAGE_REL}',     $img_zoom_rel, $img_a);
            $img_a = str_replace('{IMAGE_ABS}',     $img_zoom_abs, $img_a);
            $img_a = str_replace('{IMAGE_WIDTH}',   $img_zoom_width, $img_a);
            $img_a = str_replace('{IMAGE_HEIGHT}',  $img_zoom_height, $img_a);

            $img_a = str_replace('{IMAGE_EXT}', $img_thumb_ext, $img_a);

            $img_a = render_cnt_template($img_a, 'HIDDEN', ($image['limit'] && $image['hide_limited'] && $total > $image['limit']  ? ' style="display:hidden"' : '') );
            $img_a = render_cnt_template($img_a, 'ZOOM', ($img_zoom_name ? '<!-- Zoomed -->' : '') );
            $img_a = render_cnt_template($img_a, 'COPYRIGHT', $caption[4] );
            $img_a = render_cnt_template($img_a, 'FIRST', ($col > 1 ? '' : $col) );
            $img_a = render_cnt_template($img_a, 'ROW', ($x+1) );

            if($image['nocaption']) {
                $img_a = render_cnt_template($img_a, 'CAPTION_ELSE', '');
                $img_a = render_cnt_template($img_a, 'CAPTION', '');
            } else {
                $img_a = render_cnt_template($img_a, 'CAPTION', $caption[0]);
            }
            $img_a = render_cnt_template($img_a, 'TITLE', $caption[3]);
            $img_a = render_cnt_template($img_a, 'ALT', $caption[1]);
            $img_a = render_cnt_template($img_a, 'LINK', $img_thumb_link);
            $img_a = render_cnt_template($img_a, 'URL', $caption[2][0]);
            $img_a = render_cnt_template($img_a, 'URL_TARGET', $caption[2][1]);

            if($img_landscape !== null) {
                if($img_landscape === false) {
                    $img_a = replace_cnt_template($img_a, 'LANDSCAPE', '');
                    $img_a = replace_cnt_template($img_a, 'LANDSCAPE_ELSE', '');
                } else {
                    $img_a = render_cnt_template($img_a, 'LANDSCAPE', $img_landscape);
                }
            }

            // check if this is the last image in row
            if($image['col'] == $col || $image['count'] == $total) {

                $img_a = render_cnt_template($img_a, 'LAST', $col);

                $xx = $x;

                $x++;
                $col = 0;

            } else {

                $img_a = render_cnt_template($img_a, 'LAST', '');
                $xx = $x;

            }

            // Get the entry data
            $image['tmpl_data'][] = get_tmpl_section('ENTRY_DATA', $img_a);
            $img_a = replace_tmpl_section('ENTRY_DATA', $img_a, '');

            $image['tmpl_images'][$xx] .= $img_a;
        }

        $image['template'] .= implode($image['tmpl_row_space'], $image['tmpl_images']);
    }

    $image['template'] .= $image['tmpl_footer'];
    $image['tmpl_data'] = implode('', $image['tmpl_data']);

    // now do main replacements
    $image['template'] = render_cnt_template($image['template'], 'DATA', $image['tmpl_data']);
    $image['template'] = str_replace('{ID}', $crow['acontent_id'], $image['template']);
    $image['template'] = str_replace('{SPACE}', $image['space'], $image['template']);
    $image['template'] = str_replace('{THUMB_WIDTH_MAX}', $image['tmpl_thumb_width_max'], $image['template']);
    $image['template'] = str_replace('{THUMB_HEIGHT_MAX}', $image['tmpl_thumb_height_max'], $image['template']);
    $image['template'] = str_replace('{THUMB_COLUMNS}', $image['col'], $image['template']);
    $image['template'] = str_replace('{IMAGE_COUNT}', $total, $image['template']);

    $image['template'] = render_cnt_template($image['template'], 'IMAGE_CLASS_CENTER', $image['center_image_class']);
    $image['template'] = render_cnt_template($image['template'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
    $image['template'] = render_cnt_template($image['template'], 'ATTR_ID', html($crow['acontent_attr_id']));
    $image['template'] = render_cnt_template($image['template'], 'TITLE', html_specialchars($crow['acontent_title']));
    $image['template'] = render_cnt_template($image['template'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
    $image['template'] = render_cnt_template($image['template'], 'TEXT', $crow['acontent_text']);

    $CNT_TMP .= $image['template'];

}

unset($image);
