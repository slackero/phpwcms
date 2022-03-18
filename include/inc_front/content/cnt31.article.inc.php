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

$image  = @unserialize($crow["acontent_form"]);
$crow['acontent_template_listmode'] = empty($crow['acontent_template_listmode']) ? false : true;
if(empty($image['fieldgroup'])) {
    $image['fieldgroup'] = '';
}

// get template
if($crow['acontent_template_listmode'] && empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/list.imagespecial.tmpl')) {

    $image['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/list.imagespecial.tmpl') );

} elseif(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/imagespecial.tmpl')) {

    $image['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/imagespecial.tmpl') );

} elseif($crow['acontent_template_listmode'] && is_file(PHPWCMS_TEMPLATE.'inc_cntpart/imagespecial/list.'.$crow["acontent_template"])) {

    $image['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/imagespecial/list.'.$crow["acontent_template"]) );

} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/imagespecial/'.$crow["acontent_template"])) {

    $image['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/imagespecial/'.$crow["acontent_template"]) );

} else {

    $image['template'] = '';

}

// define default infotext renderer
$image['text_render'] = 'plaintext';

if($image['template']) {

    $image['tmpl_settings'] = parse_ini_str( get_tmpl_section('IMAGE_SETTINGS', $image['template']), false);

    if(is_array($image['tmpl_settings']) && count($image['tmpl_settings'])) {
        $image = array_merge($image, $image['tmpl_settings']);

        if($image['text_render'] === 'markdown') {
            init_markdown();
        } elseif($image['text_render'] === 'textile') {
            init_textile();
        }
    }

    $image['tmpl_header']           = get_tmpl_section('IMAGES_HEADER', $image['template']);
    $image['tmpl_footer']           = get_tmpl_section('IMAGES_FOOTER', $image['template']);
    $image['tmpl_entry']            = get_tmpl_section('IMAGES_ENTRY', $image['template']);
    $image['tmpl_entry_space']      = get_tmpl_section('IMAGES_ENTRY_SPACER', $image['template']);
    $image['tmpl_row_space']        = get_tmpl_section('IMAGES_ROW_SPACER', $image['template']);

    $image['tmpl_thumb_width_max']  = 0;
    $image['tmpl_thumb_height_max'] = 0;
    $image['tmpl_images']           = array();
    $image['cnt_id']                = $crow['acontent_id'];

    $image['template']              = $image['tmpl_header'];
    $image['tmpl_data']             = array();

    if($image['fieldgroup'] === '' || empty($template_default['settings']['imagespecial_custom_fields'][ $image['fieldgroup'] ]['fields'])) {
        $image['custom_tab_fields'] = array();
    } else {
        $image['custom_tab_fields'] = array_keys($template_default['settings']['imagespecial_custom_fields'][ $image['fieldgroup'] ]['fields']);
        $image['field_render'] = array('html', 'markdown', 'plain', 'wysiwyg');
        $image['fieldgroup'] =& $template_default['settings']['imagespecial_custom_fields'][ $image['fieldgroup'] ]['fields'];
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

        if(empty($image['thumb_class'])) {
            $image['thumb_class'] = '';
        }
        $image['thumb_class'] = trim($template_default['classes']['image-thumb'] . ' ' . $image['thumb_class']);

        switch($image['center']) {

            case 1:     // center hor/vert
                if(!$image['width'] && !$image['height']) {
                    $image['center'] = 0;
                } elseif(!$image['width']) {
                    $image['center'] = 3;
                } elseif(!$image['height']) {
                    $image['center'] = 2;
                }
                break;

            case 2:     // center hor
                if(!$image['width']) {
                    $image['center'] = 0;
                }
                break;

            case 3:     // center vert
                if(!$image['height']) {
                    $image['center'] = 0;
                }
                break;

            default:
                $image['center'] = 0;

        }

        $x      = 0;
        $col    = 0;

        foreach($image['images'] as $key => $value) {

            $thumb_image        = false;
            $zoominfo           = false;

            if(empty($value['thumb_hash'])) {
                continue;
            } else {
                $thumb_image = get_cached_image(array(
                    "target_ext" => $value['thumb_ext'],
                    "image_name" => $value['thumb_hash'] . '.' . $value['thumb_ext'],
                    "max_width" => $image['width'],
                    "max_height" => $image['height'],
                    "thumb_name" => md5($value['thumb_hash'].$image['width'].$image['height'].$phpwcms["sharpen_level"].$image['crop'].$phpwcms['colorspace']),
                    'crop_image' => $image['crop']
                ));
            }

            if(!$thumb_image) {
                continue;
            }

            $img_zoom_id        = '';
            $img_zoom_hash      = '';
            $img_zoom_ext       = '';
            $img_zoom_name      = '';
            $img_zoom_filename  = '';
            $img_zoom_rel       = '';
            $img_zoom_abs       = '';
            $img_zoom_width     = '';
            $img_zoom_height    = '';

            $col++;
            $total++;

            // put spacer content between images
            if($col > 1) {

                $image['tmpl_images'][$x] .= $image['tmpl_entry_space'];

            } else {

                $image['tmpl_images'][$x]  = '';

            }

            if($image['zoom']) {

                if($value['zoom_hash']) {
                    $zoominfo = get_cached_image(array(
                        "target_ext"    =>  $value['zoom_ext'],
                        "image_name"    =>  $value['zoom_hash'] . '.' . $value['zoom_ext'],
                        "max_width"     =>  $image['width_zoom'],
                        "max_height"    =>  $image['height_zoom'],
                        "thumb_name"    =>  md5($value['zoom_hash'].$image['width_zoom'].$image['height_zoom'].$phpwcms["sharpen_level"].$image['crop_zoom'].$phpwcms['colorspace']),
                        'crop_image'    =>  $image['crop_zoom']
                    ));
                } else {
                    $zoominfo = get_cached_image(array(
                        "target_ext"    =>  $value['thumb_ext'],
                        "image_name"    =>  $value['thumb_hash'] . '.' . $value['thumb_ext'],
                        "max_width"     =>  $image['width_zoom'],
                        "max_height"    =>  $image['height_zoom'],
                        "thumb_name"    =>  md5($value['thumb_hash'].$image['width_zoom'].$image['height_zoom'].$phpwcms["sharpen_level"].$image['crop_zoom'].$phpwcms['colorspace']),
                        'crop_image'    =>  $image['crop_zoom']
                    ));
                }
            }

            // set caption and ALT Image Text for imagelist
            $caption = getImageCaption(array('caption' => $value['caption'], 'file' => $value['thumb_id']));

            if($caption[0]) {
                $caption[0] = html($caption[0]);
            }

            // no ALT, no TITLE
            if(empty($caption[1])) {
                $capt_cur       = $caption[0];
                $caption[1]     = html($value['thumb_name']);
            } else {
                $caption[1]     = html($caption[1]);
                $capt_cur       = $caption[1];
            }
            if(empty($caption[2])) {
                $caption[2]     = explode(' ', $value['url']);
                $caption[2][1]  = empty($caption[2][1]) ? '' : ' target="'.$caption[2][1].'"';
            }

            $img_thumb_name     = $thumb_image[0];
            $img_thumb_rel      = $thumb_image['src'];
            $img_thumb_abs      = PHPWCMS_URL.$thumb_image['src'];
            $img_thumb_width    = $thumb_image[1];
            $img_thumb_height   = $thumb_image[2];
            $img_thumb_filename = $value['thumb_name'];
            $img_thumb_link     = '';

            $list_img_temp  = '<img src="'.$thumb_image['src'].'" data-image-ext="'.$value['thumb_ext'].'" ';
            $list_img_temp .= 'data-image-id="'.$value['thumb_id'].'" data-image-hash="'.$value['thumb_hash'].'" ';

            if($image['center']) {

                $img_margin_left    = 0;
                $img_margin_right   = 0;
                $img_margin_top     = 0;
                $img_margin_bottom  = 0;

                // center hor/vert
                if($image['center'] == 1 || $image['center'] == 2) {
                    $img_margin_left    = ceil( ($image['width'] - $thumb_image[1]) / 2 );
                    $img_margin_right   = $image['width'] - $thumb_image[1] - $img_margin_left;
                }
                if($image['center'] == 1 || $image['center'] == 3) {
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
                $caption[3] = html($caption[3]);
                $list_img_temp .= ' title="'.$caption[3].'"';
            }
            $list_img_temp .= ' class="' . $image['thumb_class'] . '"' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;
            $img_a          = '';
            $lightbox_capt  = '';

            if($image['zoom'] && isset($zoominfo) && $zoominfo != false) {
                // if click enlarge the image
                $open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo['src'], $zoominfo[3], $value['zoom_name']);
                if($caption[2][0]) {
                    $open_link = $caption[2][0];
                    $return_false = '';
                } else {
                    $open_link = $open_popup_link;
                    $return_false = 'return false;';
                }

                if($image['lightbox'] && $capt_cur) {
                    $lightbox_capt = 'title="'.parseLightboxCaption($capt_cur).'" ';
                }

                if(!$image['lightbox'] || $caption[2][0]) {
                    $img_thumb_link  = '<a href="'.$open_link."\" onclick=\"checkClickZoom();clickZoom('".$open_popup_link."','previewpic','width=";
                    $img_thumb_link .= $zoominfo[1].",height=".$zoominfo[2]."');".$return_false.'"'.$caption[2][1];
                    $img_thumb_link .= $list_ahref_style.' class="'.$template_default['classes']['image-zoom'].'">';
                } else {
                    // lightbox
                    $img_thumb_link  = '<a href="'.$zoominfo['src'].'" rel="lightbox['.$image['lightbox'].']"'.get_attr_data_gallery($image['lightbox'], ' ', ' ');
                    $img_thumb_link .= $lightbox_capt;
                    $img_thumb_link .= $list_ahref_style.' class="'.$template_default['classes']['image-lightbox'].'">';
                }
                $img_a .= $img_thumb_link.$list_img_temp.'</a>';

                $img_zoom_id        = $value['zoom_id'];
                $img_zoom_hash      = $value['zoom_hash'];
                $img_zoom_ext       = $value['zoom_ext'];
                $img_zoom_name      = $zoominfo[0];
                $img_zoom_rel       = $zoominfo['src'];
                $img_zoom_abs       = PHPWCMS_URL.$zoominfo['src'];
                $img_zoom_width     = $zoominfo[1];
                $img_zoom_height    = $zoominfo[2];
                $img_zoom_filename  = $value['zoom_name'];

            } elseif($caption[2][0]) { // if not click enlarge
                $img_thumb_link = '<a href="'.$caption[2][0].'" '.$list_ahref_style.$caption[2][1].' class="'.$template_default['classes']['image-link'].'">';
                $img_a .= $img_thumb_link.$list_img_temp.'</a>';
            } else {
                $img_a .= $list_img_temp;
            }

            $img_a = str_replace('{IMAGE}', $img_a, $image['tmpl_entry']);
            $img_a = str_replace('{IMGID}', $key, $img_a);
            $img_a = str_replace('{IMGNAME}', html($image['images'][$key]['thumb_name']), $img_a);
            $img_a = str_replace('{ENTRY_ID}', $total-1, $img_a);
            $img_a = str_replace('{ENTRY_NUM}', $total, $img_a);

            // replace thumbnail and zoom image information
            $img_a = str_replace('{THUMB_ID}',          $value['thumb_id'], $img_a);
            $img_a = str_replace('{THUMB_HASH}',        $value['thumb_hash'], $img_a);
            $img_a = str_replace('{THUMB_EXT}',         $value['thumb_ext'], $img_a);
            $img_a = str_replace('{THUMB_NAME}',        $img_thumb_name, $img_a);
            $img_a = str_replace('{THUMB_FILENAME}',    $img_thumb_filename, $img_a);
            $img_a = str_replace('{THUMB_REL}',         $img_thumb_rel, $img_a);
            $img_a = str_replace('{THUMB_ABS}',         $img_thumb_abs, $img_a);
            $img_a = str_replace('{THUMB_WIDTH}',       $img_thumb_width, $img_a);
            $img_a = str_replace('{THUMB_HEIGHT}',      $img_thumb_height, $img_a);

            $image['tmpl_thumb_width_max']  = max($image['tmpl_thumb_width_max'], $img_thumb_width);
            $image['tmpl_thumb_height_max'] = max($image['tmpl_thumb_height_max'], $img_thumb_height);

            $img_a = str_replace('{IMAGE_ID}',          $img_zoom_id, $img_a);
            $img_a = str_replace('{IMAGE_HASH}',        $img_zoom_hash, $img_a);
            $img_a = str_replace('{IMAGE_EXT}',         $img_zoom_ext, $img_a);
            $img_a = str_replace('{IMAGE_NAME}',        $img_zoom_name, $img_a);
            $img_a = str_replace('{IMAGE_FILENAME}',    $img_zoom_filename, $img_a);
            $img_a = str_replace('{IMAGE_REL}',         $img_zoom_rel, $img_a);
            $img_a = str_replace('{IMAGE_ABS}',         $img_zoom_abs, $img_a);
            $img_a = str_replace('{IMAGE_WIDTH}',       $img_zoom_width, $img_a);
            $img_a = str_replace('{IMAGE_HEIGHT}',      $img_zoom_height, $img_a);

            $img_a = render_cnt_template($img_a, 'IMAGE_URL', $caption[2][0]);
            $img_a = str_replace('{IMAGE_TARGET}',  $caption[2][1], $img_a);
            $img_a = str_replace('{LIGHTBOX}',  ($image['lightbox'] !== false ? ' rel="lightbox['.$image['lightbox'].']"'.get_attr_data_gallery($image['lightbox'], ' ', '') : '' ), $img_a);
            $img_a = str_replace('{LIGHTBOX_CAPTION}', $lightbox_capt, $img_a);

            $img_a = render_cnt_template($img_a, 'ZOOM', ($img_zoom_name ? '<!-- Zoomed -->' : '') );
            $img_a = render_cnt_template($img_a, 'COPYRIGHT', $caption[4] );
            $img_a = render_cnt_template($img_a, 'FIRST', ($col > 1 ? '' : $col) );
            $img_a = render_cnt_template($img_a, 'ROW', ($x+1) );

            // new freetext value
            $value['freetext'] = empty($value['freetext']) ? '' : trim($value['freetext']);

            switch($image['text_render']) {

                case 'markdown':
                    $value['freetext'] = $phpwcms['parsedown_class']->text($value['freetext']);
                    break;

                case 'textile':
                    $value['freetext'] = $phpwcms['textile_class']->textileThis($value['freetext']);
                    break;

                case 'html':
                    break;

                case 'text':
                    $value['freetext'] = html($value['freetext']);
                    break;

                default:
                    $value['freetext'] = plaintext_htmlencode($value['freetext'], 'html_entities');
                    break;

            }

            if(empty($value['url'])) {
                $value['url']           = '';
                $value['url_target']    = '';
            } else {
                $value['url']           = explode(' ', $value['url']);
                $value['url_target']    = empty($value['url'][1]) ? '' : trim($value['url'][1]);
                $value['url']           = trim($value['url'][0]);
            }

            $img_a = render_cnt_template($img_a, 'INFOTEXT', $value['freetext']);
            $img_a = render_cnt_template($img_a, 'URL', $value['url']);
            $img_a = render_cnt_template($img_a, 'URL_TARGET', $value['url_target']);

            if($image['nocaption']) {
                $img_a = render_cnt_template($img_a, 'CAPTION_ELSE', '');
                $img_a = render_cnt_template($img_a, 'CAPTION', '');
            } else {
                $img_a = render_cnt_template($img_a, 'CAPTION', $caption[0]);
            }
            $img_a = render_cnt_template($img_a, 'TITLE', $caption[3]);
            $img_a = render_cnt_template($img_a, 'ALT', $caption[1]);
            $img_a = render_cnt_template($img_a, 'LINK', $img_thumb_link);

            if($image['custom_tab_fields']) {
                foreach($image['custom_tab_fields'] as $custom_field_key) {
                    $custom_field_value = isset($value['custom_fields'][$custom_field_key]) ? $value['custom_fields'][$custom_field_key] : '';
                    $custom_field_replacer = 'IMGSPCL_'.strtoupper($custom_field_key);

                    if($custom_field_value === '') {
                        $img_a = render_cnt_template($img_a, $custom_field_replacer, '');
                        continue;
                    }

                    if($image['fieldgroup'][$custom_field_key]['type'] === 'bool') {

                        $img_a = render_cnt_template($img_a, $custom_field_replacer, empty($custom_field_value) ? '' : ' ');

                    } elseif($image['fieldgroup'][$custom_field_key]['type'] === 'option' || $image['fieldgroup'][$custom_field_key]['type'] === 'select') {

                        if(isset($image['fieldgroup'][$custom_field_key]['values'][$custom_field_value])) {

                            // render custom option globally first
                            $img_a = render_cnt_template($img_a, $custom_field_replacer, html($custom_field_value));

                            // render option specific replacers
                            if(strpos($img_a, $custom_field_replacer.'_') !== false) {
                                foreach($image['fieldgroup'][$custom_field_key]['values'] as $option_key => $option_label) {
                                    if($custom_field_value === $option_key) {
                                        $img_a = render_cnt_template($img_a, $custom_field_replacer.'_'.strtoupper($option_key), html($option_key));
                                    } else {
                                        $img_a = render_cnt_template($img_a, $custom_field_replacer.'_'.strtoupper($option_key), '');
                                    }
                                }
                            }
                        }

                    } elseif($image['fieldgroup'][$custom_field_key]['type'] === 'int' || $image['fieldgroup'][$custom_field_key]['type'] === 'float') {

                        $img_a = render_cnt_template($img_a, $custom_field_replacer, $custom_field_value);

                    } elseif($image['fieldgroup'][$custom_field_key]['type'] === 'file') {

                        $_preserve_acontent_html = $crow['acontent_html'];
                        $news['files_result'] = '';

                        if(!empty($custom_field_value['id'])) {

                            $IS_NEWS_CP = true;

                            if (!is_array($value)) {
                                $value = array();
                            } elseif (!isset($value['cnt_object']) || !is_array($value['cnt_object'])) {
                                $value['cnt_object'] = array();
                            }

                            $value['cnt_object']['cnt_files'] = array(
                                'id' => array(0 => $custom_field_value['id']),
                                'caption' => array(0 => $custom_field_value['description']),
                            );
                            $value['files_direct_download'] = empty($image['fieldgroup'][$custom_field_key]['direct']) ? 0 : 1;
                            $value['files_template'] = empty($image['fieldgroup'][$custom_field_key]['template']) ? '' : $image['fieldgroup'][$custom_field_key]['template'];

                            // include content part files renderer
                            include PHPWCMS_ROOT.'/include/inc_front/content/cnt7.article.inc.php';

                            unset($IS_NEWS_CP);

                        }

                        $img_a = render_cnt_template($img_a, $custom_field_replacer, $news['files_result']);
                        $crow['acontent_html'] = $_preserve_acontent_html;
                        unset($_preserve_acontent_html);

                    } elseif(isset($image['fieldgroup'][$custom_field_key]['render']) && in_array($image['fieldgroup'][$custom_field_key]['render'], $image['field_render'])) {

                        if($image['fieldgroup'][$custom_field_key]['render'] === 'markdown') {
                            init_markdown();
                            $img_a = render_cnt_template($img_a, $custom_field_replacer, $phpwcms['parsedown_class']->text($custom_field_value));
                        } elseif($image['fieldgroup'][$custom_field_key]['render'] === 'plain') {
                            $img_a = render_cnt_template($img_a, $custom_field_replacer, plaintext_htmlencode($custom_field_value));
                        } else {
                            $img_a = render_cnt_template($img_a, $custom_field_replacer, $custom_field_value);
                        }

                    } else {

                        $img_a = render_cnt_template($img_a, $custom_field_replacer, nl2br(html($custom_field_value)));

                    }
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
    $image['template'] = str_replace('{ID}', $image['cnt_id'], $image['template']);
    $image['template'] = str_replace('{SPACE}', $image['space'], $image['template']);
    $image['template'] = str_replace('{THUMB_WIDTH_MAX}', $image['tmpl_thumb_width_max'], $image['template']);
    $image['template'] = str_replace('{THUMB_HEIGHT_MAX}', $image['tmpl_thumb_height_max'], $image['template']);
    $image['template'] = str_replace('{THUMB_COLUMNS}', $image['col'], $image['template']);
    $image['template'] = str_replace('{IMAGE_COUNT}', $total, $image['template']);

    $image['template'] = render_cnt_template($image['template'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
    $image['template'] = render_cnt_template($image['template'], 'ATTR_ID', html($crow['acontent_attr_id']));
    $image['template'] = render_cnt_template($image['template'], 'TITLE', html($crow['acontent_title']));
    $image['template'] = render_cnt_template($image['template'], 'SUBTITLE', html($crow['acontent_subtitle']));
    $image['template'] = render_cnt_template($image['template'], 'TEXT', $crow['acontent_html']);
    $image['template'] = render_cnt_template($image['template'], 'EFFECT_1', (empty($image['fx1']) ? '' : '<!-- FX 1 -->') );
    $image['template'] = render_cnt_template($image['template'], 'EFFECT_2', (empty($image['fx2']) ? '' :  '<!-- FX 2 -->') );
    $image['template'] = render_cnt_template($image['template'], 'EFFECT_3', (empty($image['fx3']) ? '' :  '<!-- FX 3 -->') );

    $CNT_TMP .= $image['template'];

}

unset($image);
