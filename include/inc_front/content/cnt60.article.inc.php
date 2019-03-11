<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2019, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//custom contentpart


$custom  = @unserialize($crow["acontent_form"]);

$crow['acontent_template_listmode'] = empty($crow['acontent_template_listmode']) ? false : true;
if(empty($custom['fieldgroup'])) {
    $custom['fieldgroup'] = '';
}

// get template
if($crow['acontent_template_listmode'] && empty($crow["acontent_template"]) && is_file(CMSGO_TEMPLATE.'inc_default/list.custom.tmpl')) {

    $custom['template'] = render_device( @file_get_contents(CMSGO_TEMPLATE.'inc_default/list.custom.tmpl') );

} elseif(empty($crow["acontent_template"]) && is_file(CMSGO_TEMPLATE.'inc_default/custom.tmpl')) {

    $custom['template'] = render_device( @file_get_contents(CMSGO_TEMPLATE.'inc_default/custom.tmpl') );

} elseif($crow['acontent_template_listmode'] && is_file(CMSGO_TEMPLATE.'inc_cntpart/custom/list.'.$crow["acontent_template"])) {

    $custom['template'] = render_device( @file_get_contents(CMSGO_TEMPLATE.'inc_cntpart/custom/list.'.$crow["acontent_template"]) );

} elseif(is_file(CMSGO_TEMPLATE.'inc_cntpart/custom/'.$crow["acontent_template"])) {

    $custom['template'] = render_device( @file_get_contents(CMSGO_TEMPLATE.'inc_cntpart/custom/'.$crow["acontent_template"]) );

} else {

    $custom['template'] = '';

}

// define default infotext renderer
$custom['text_render'] = 'plaintext';

if($custom['template']) {

    $custom['tmpl_settings'] = parse_ini_str( get_tmpl_section('CUSTOM_SETTINGS', $custom['template']), false);

    if(is_array($custom['tmpl_settings']) && count($custom['tmpl_settings'])) {
        $custom = array_merge($custom, $custom['tmpl_settings']);

        if($custom['text_render'] === 'markdown' && !isset($cmsgo['parsedown_class'])) {
            require_once(CMSGO_ROOT.'/include/inc_ext/parsedown/Parsedown.php');
            require_once(CMSGO_ROOT.'/include/inc_ext/parsedown-extra/ParsedownExtra.php');
            $cmsgo['parsedown_class'] = new ParsedownExtra();
        } elseif($custom['text_render'] === 'textile' && !isset($cmsgo['textile_class'])) {
            require_once(CMSGO_ROOT.'/include/inc_ext/classTextile.php');
            $cmsgo['textile_class'] = new Textile();
        }
    }

    $custom['tmpl_header']           = get_tmpl_section('CUSTOM_HEADER', $custom['template']);
    $custom['tmpl_footer']           = get_tmpl_section('CUSTOM_FOOTER', $custom['template']);
    $custom['tmpl_entry']            = get_tmpl_section('CUSTOM_ENTRY', $custom['template']);
    $custom['tmpl_entry_space']      = get_tmpl_section('CUSTOM_ENTRY_SPACER', $custom['template']);
    $custom['tmpl_row_space']        = get_tmpl_section('CUSTOM_ROW_SPACER', $custom['template']);

    $custom['tmpl_thumb_width_max']  = 0;
    $custom['tmpl_thumb_height_max'] = 0;
    $custom['tmpl_images']           = array();
    $custom['cnt_id']                = $crow['acontent_id'];

    $custom['template']              = $custom['tmpl_header'];
    $custom['tmpl_data']             = array();

    if($custom['fieldgroup'] === '' || empty($template_default['settings']['customctp_custom_fields'][ $custom['fieldgroup'] ]['fields'])) {
        $custom['custom_tab_fields'] = array();
    } else {
        $custom['custom_tab_fields'] = array_keys($template_default['settings']['customctp_custom_fields'][ $custom['fieldgroup'] ]['fields']);
        $custom['field_render'] = array('html', 'markdown', 'plain');
        $custom['fieldgroup'] =& $template_default['settings']['customctp_custom_fields'][ $custom['fieldgroup'] ]['fields'];
    }

print_r($custom);
    if(is_array($custom['fieldgroup']) && ($custom['count'] = count($custom['fieldgroup']))) {

        $x      = 0;
        $col    = 0;
        $total  = 0;
        $hasimage = false;

        foreach ($custom['fieldgroup'] as $key => $customfiled) {
          if ($custom['fieldgroup'][$key]['type'] === 'image') {
            $hasimage = true;
          }
        }

        if ($hasimage) {
          if(empty($custom['lightbox'])) {
            $custom['lightbox'] = false;
          } else {
              initSlimbox();
              $custom['lightbox'] = generic_string(5);
          }

          if(empty($custom['thumb_class'])) {
              $custom['thumb_class'] = '';
          }
          $custom['thumb_class'] = trim($template_default['classes']['image-thumb'] . ' ' . $custom['thumb_class']);

          switch($custom['center']) {

              case 1:     // center hor/vert
                  if(!$custom['width'] && !$custom['height']) {
                      $custom['center'] = 0;
                  } elseif(!$image['width']) {
                      $custom['center'] = 3;
                  } elseif(!$image['height']) {
                      $custom['center'] = 2;
                  }
                  break;

              case 2:     // center hor
                  if(!$custom['width']) {
                      $custom['center'] = 0;
                  }
                  break;

              case 3:     // center vert
                  if(!$custom['height']) {
                      $custom['center'] = 0;
                  }
                  break;

              default:
                  $custom['center'] = 0;

          }
        }

        foreach($custom['custom_elements'] as $values) {
            $custom_a = $custom['tmpl_entry'];
            if($custom['custom_elements']) {
                foreach($custom['custom_tab_fields'] as $custom_field_key) {
                    $custom_field_value = isset($values['custom_fields'][$custom_field_key]) ? $values['custom_fields'][$custom_field_key] : '';
                    $custom_field_replacer = 'CUSTCTP_'.strtoupper($custom_field_key);
                    //echo $custom_field_key.':'.$custom_field_value .LF;

                    if($custom_field_value === '') {
                        $custom_a = render_cnt_template($custom_a, $custom_field_replacer, '');
                        continue;
                    }

                    if($custom['fieldgroup'][$custom_field_key]['type'] === 'bool') {

                        $custom_a = render_cnt_template($custom_a, $custom_field_replacer, empty($custom_field_value) ? '' : ' ');

                    } elseif($custom['fieldgroup'][$custom_field_key]['type'] === 'option' || $custom['fieldgroup'][$custom_field_key]['type'] === 'select') {

                        if(isset($custom['fieldgroup'][$custom_field_key]['values'][$custom_field_value])) {

                            // render custom option globally first
                            $custom_a = render_cnt_template($custom_a, $custom_field_replacer, html($custom_field_value));

                            // render option specific replacers
                            if(strpos($custom_a, $custom_field_replacer.'_') !== false) {
                                foreach($custom['fieldgroup'][$custom_field_key]['values'] as $option_key => $option_label) {
                                    if($custom_field_value === $option_key) {
                                        $custom_a = render_cnt_template($custom_a, $custom_field_replacer.'_'.strtoupper($option_key), html($option_key));
                                    } else {
                                        $custom_a = render_cnt_template($custom_a, $custom_field_replacer.'_'.strtoupper($option_key), '');
                                    }
                                }
                            }
                        }

                    } elseif($custom['fieldgroup'][$custom_field_key]['type'] === 'int' || $custom['fieldgroup'][$custom_field_key]['type'] === 'float') {

                        $custom_a = render_cnt_template($custom_a, $custom_field_replacer, $custom_field_value);

                    } elseif($custom['fieldgroup'][$custom_field_key]['type'] === 'file') {

                        $news['files_result'] = '';

                        if(!empty($custom_field_value['id'])) {

                            $IS_NEWS_CP = true;

                            $value['cnt_object']['cnt_files'] = array(
                                'id' => array(0 => $custom_field_value['id']),
                                'caption' => array(0 => $custom_field_value['description']),
                            );
                            $value['files_direct_download'] = empty($custom['fieldgroup'][$custom_field_key]['direct']) ? 0 : 1;
                            $value['files_template'] = empty($custom['fieldgroup'][$custom_field_key]['template']) ? '' : $custom['fieldgroup'][$custom_field_key]['template'];

                            // include content part files renderer
                            include CMSGO_ROOT.'/include/inc_front/content/cnt7.article.inc.php';

                            unset($IS_NEWS_CP);

                        }

                        $custom_a = render_cnt_template($custom_a, $custom_field_replacer, $news['files_result']);

                    } elseif($custom['fieldgroup'][$custom_field_key]['type'] === 'image') {
                        //print_r($custom_field_value);
                        $thumb_image = get_cached_image(array(
                          "target_ext"    =>  $custom_field_value['f_ext'],
                          "image_name"    =>  $custom_field_value['f_hash'] . '.' . $custom_field_value['f_ext'],
                          "max_width"     =>  $custom['width'],
                          "max_height"    =>  $custom['height'],
                          "thumb_name"    =>  md5($custom_field_value['f_hash'].$custom['width'].$custom['height'].$cmsgo["sharpen_level"].$custom['crop_zoom'].$cmsgo['colorspace']),
                          'crop_image'    =>  $custom['crop_zoom']
                        ));

                        if(!$custom_field_value['f_hash'] || !$thumb_image) {
                            $custom_a = render_cnt_template($custom_a, $custom_field_replacer, '');
                            continue;
                        }

                        if($custom['zoom']) {

                          $zoom_image = get_cached_image(array(
                            "target_ext"    =>  $custom_field_value['f_ext'],
                            "image_name"    =>  $custom_field_value['f_hash'] . '.' . $custom_field_value['f_ext'],
                            "max_width"     =>  $custom['width_zoom'],
                            "max_height"    =>  $custom['height_zoom'],
                            "thumb_name"    =>  md5($custom_field_value['f_hash'].$custom['width_zoom'].$custom['height_zoom'].$cmsgo["sharpen_level"].$custom['crop_zoom'].$cmsgo['colorspace']),
                            'crop_image'    =>  $custom['crop_zoom']
                          ));
                        }

                        $thumb_img  = '<img src="' . $thumb_image['src'] .'" data-image-ext="'.$custom_field_value['f_ext'].'" ';
                        $thumb_img .= 'data-image-id="'.$custom_field_value['id'].'" data-image-hash="'.$custom_field_value['f_hash'].'" '.$thumb_image[3];

                        if($custom['center']) {

                          $img_margin_left    = 0;
                          $img_margin_right   = 0;
                          $img_margin_top     = 0;
                          $img_margin_bottom  = 0;

                          // center hor/vert
                          if($custom['center'] == 1 || $custom['center'] == 2) {
                              $img_margin_left    = ceil( ($custom['width'] - $thumb_image[1]) / 2 );
                              $img_margin_right   = $custom['width'] - $thumb_image[1] - $img_margin_left;
                          }
                          if($custom['center'] == 1 || $custom['center'] == 3) {
                              $img_margin_top     = ceil( ($custom['height'] - $thumb_image[2]) / 2 );
                              $img_margin_bottom  = $custom['height'] - $thumb_image[2] - $img_margin_top;
                          }

                          $list_img_style     = 'style="margin:'.$img_margin_top.'px '.$img_margin_right.'px '.$img_margin_bottom.'px '.$img_margin_left.'px;" ';
                          $list_ahref_style   = '';
                          $thumb_img     .= $list_img_style;

                        } else {
                            $list_img_style     = '';
                            $list_ahref_style   = '';
                        }


                        $thumb_img .= ' alt="'.$custom_field_value['alt'].'"';
                        if($custom_field_value['title']) {
                            $thumb_img .= ' title="'.$custom_field_value['title'].'"';
                        }
                        $thumb_img .= ' class="'.$custom['thumb_class'].'"'.HTML_TAG_CLOSE;

                        $img_a          = '';
                        $lightbox_capt  = '';

                        if($custom['zoom'] && isset($zoom_image) && $zoom_image != false) {
                            // if click enlarge the image
                            $open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoom_image['src'].'?'.$zoom_image[3]);
                            $open_link = $open_popup_link;
                            $return_false = 'return false;';

                            if($custom['lightbox'] && $custom_field_value['title']) {
                                $lightbox_capt = 'title="'.$custom_field_value['title'].'" ';
                            }

                            // lightbox
                            $img_thumb_link  = '<a href="'.$zoom_image['src'].'" rel="lightbox['.$custom['lightbox'].']"'.get_attr_data_gallery($custom['lightbox'], ' ', ' ');
                            $img_thumb_link .= $lightbox_capt;
                            $img_thumb_link .= $list_ahref_style.' class="'.$template_default['classes']['image-lightbox'].'">';

                            $img_a .= $img_thumb_link;
                            $img_a .= $thumb_img.'</a>';

                            $img_zoom_id        = $custom_field_value['id'];
                            $img_zoom_hash      = $custom_field_value['f_hash'];
                            $img_zoom_ext       = $custom_field_value['f_ext'];
                            $img_zoom_name      = $zoom_image[0];
                            $img_zoom_rel       = $zoom_image['src'];
                            $img_zoom_abs       = CMSGO_URL.$zoom_image['src'];
                            $img_zoom_width     = $zoom_image[1];
                            $img_zoom_height    = $zoom_image[2];

                        } else {
                            $img_a .= $thumb_img;
                        }

                        $custom_a = render_cnt_template($custom_a, $custom_field_replacer, $img_a);

                    } elseif(isset($custom['fieldgroup'][$custom_field_key]['render']) && in_array($custom['fieldgroup'][$custom_field_key]['render'], $custom['field_render'])) {

                        if($custom['fieldgroup'][$custom_field_key]['render'] === 'markdown') {
                            if(!isset($cmsgo['parsedown_class'])) {
                                require_once(CMSGO_ROOT.'/include/inc_ext/parsedown/Parsedown.php');
                                require_once(CMSGO_ROOT.'/include/inc_ext/parsedown-extra/ParsedownExtra.php');
                                $cmsgo['parsedown_class'] = new ParsedownExtra();
                            }
                            $custom_a = render_cnt_template($custom_a, $custom_field_replacer, $cmsgo['parsedown_class']->text($custom_field_value));
                        } elseif($custom['fieldgroup'][$custom_field_key]['render'] === 'plain') {
                            $custom_a = render_cnt_template($custom_a, $custom_field_replacer, plaintext_htmlencode($custom_field_value));
                        } else {
                            $custom_a = render_cnt_template($custom_a, $custom_field_replacer, $custom_field_value);
                        }

                    } else {

                        $custom_a = render_cnt_template($custom_a, $custom_field_replacer, nl2br(html($custom_field_value)));

                    }
                    $custom_a = render_cnt_template($custom_a, 'CUSTCTP_SORT', $values['sort']);
                }
            }

            // check if this is the last image in row
            if($custom['col'] == $col || $custom['count'] == $total) {

                $custom_a = render_cnt_template($custom_a, 'LAST', $col);

                $xx = $x;
                $x++;
                $col = 0;

            } else {

                $custom_a = render_cnt_template($custom_a, 'LAST', '');

                $xx = $x;

            }

            // Get the entry data
            $custom['tmpl_data'][] = get_tmpl_section('ENTRY_DATA', $custom_a);
            $custom_a = replace_tmpl_section('ENTRY_DATA', $custom_a, '');

            $custom['tmpl_images'][$xx] .= $custom_a;

        }

        $custom['template'] .= implode($custom['tmpl_row_space'], $custom['tmpl_images']);

    }

    $custom['template'] .= $custom['tmpl_footer'];
    $custom['tmpl_data'] = implode('', $custom['tmpl_data']);

    // now do main replacements
    $custom['template'] = render_cnt_template($custom['template'], 'DATA', $custom['tmpl_data']);
    $custom['template'] = str_replace('{ID}', $custom['cnt_id'], $custom['template']);
    $custom['template'] = str_replace('{SPACE}', $custom['space'], $custom['template']);
    $custom['template'] = str_replace('{THUMB_WIDTH_MAX}', $custom['tmpl_thumb_width_max'], $custom['template']);
    $custom['template'] = str_replace('{THUMB_HEIGHT_MAX}', $custom['tmpl_thumb_height_max'], $custom['template']);
    $custom['template'] = str_replace('{THUMB_COLUMNS}', $custom['col'], $custom['template']);

    $custom['template'] = render_cnt_template($custom['template'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
    $custom['template'] = render_cnt_template($custom['template'], 'ATTR_ID', html($crow['acontent_attr_id']));
    $custom['template'] = render_cnt_template($custom['template'], 'TITLE', html($crow['acontent_title']));
    $custom['template'] = render_cnt_template($custom['template'], 'SUBTITLE', html($crow['acontent_subtitle']));
    $custom['template'] = render_cnt_template($custom['template'], 'TEXT', $crow['acontent_html']);
    $custom['template'] = render_cnt_template($custom['template'], 'EFFECT_1', (empty($custom['fx1']) ? '' : '<!-- FX 1 -->') );
    $custom['template'] = render_cnt_template($custom['template'], 'EFFECT_2', (empty($custom['fx2']) ? '' :  '<!-- FX 2 -->') );
    $custom['template'] = render_cnt_template($custom['template'], 'EFFECT_3', (empty($custom['fx3']) ? '' :  '<!-- FX 3 -->') );

    $CNT_TMP .= $custom['template'];

}

unset($custom);
