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

//images (gallery)
$image = @unserialize($crow["acontent_form"]);

if(is_array($image) && ($image_count = count($image))) {

    // load special functions
    require_once(PHPWCMS_ROOT.'/include/inc_front/img.func.inc.php');

    // read template
    if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/imagetable.tmpl')) {

        $crow["acontent_template"]  = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/imagetable.tmpl') );

    } elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/imagetable/'.$crow["acontent_template"])) {

        $crow["acontent_template"]  = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/imagetable/'.$crow["acontent_template"]) );

    } else {

        $crow["acontent_template"]  = '[IMAGETABLE]{IMAGETABLE}[/IMAGETABLE]';

    }

    $crow["settings"]          = get_tmpl_section('IMAGETABLE_SETTINGS', $crow["acontent_template"]);
    $crow["settings"]          = parse_ini_str($crow["settings"], false);
    $crow["acontent_template"] = replace_tmpl_section('IMAGETABLE_SETTINGS', $crow["acontent_template"]);
    $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_CLASS', html($crow['acontent_attr_class']));
    $crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'ATTR_ID', html($crow['acontent_attr_id']));
    $crow["acontent_template"] = str_replace('{IMAGE_COUNT}', $image_count, $crow["acontent_template"]);

    if(strpos($crow["acontent_template"], 'TITLE')) {
        $crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'TITLE', html($crow['acontent_title']));
        $crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html($crow['acontent_subtitle']));
        $crow['render_titles']      = false;
    } else {
        $crow['render_titles']      = true;
    }

    $image['class_top_left']        = $template_default['classes']['imgtable-top-left'];
    $image['class_top_center']      = $template_default['classes']['imgtable-top-center'];
    $image['class_top_right']       = $template_default['classes']['imgtable-top-right'];
    $image['class_bottom_left']     = $template_default['classes']['imgtable-bottom-left'];
    $image['class_bottom_center']   = $template_default['classes']['imgtable-bottom-center'];
    $image['class_bottom_right']    = $template_default['classes']['imgtable-bottom-right'];
    $image['class_float_left']      = $template_default['classes']['imgtable-left'];
    $image['class_float_right']     = $template_default['classes']['imgtable-right'];

    $image['class_image_thumb']     = $template_default['classes']['image-thumb'];
    $image['class_image_wrapper']   = $template_default['classes']['image-wrapper'];
    $image['class_image_link']      = $template_default['classes']['image-link'];
    $image['class_image_zoom']      = $template_default['classes']['image-zoom'];
    $image['class_image_lightbox']  = $template_default['classes']['image-lightbox'];

    $image = array_merge($image, $crow["settings"]);

    $image['usetable'] = empty($image['usetable']) ? 0 : 1;

    if(empty($image['lightbox'])) {

        $GLOBALS['cnt_image_lightbox'] = 0;
        $cnt_image_lightbox = 0;

    } else {

        $GLOBALS['cnt_image_lightbox'] = 1;
        $cnt_image_lightbox = 1;
        initSlimbox();
        $image['zoom'] = 1;

    }

    $imagetable = '';

    switch($image['pos']) {
        //oben mitte
        case 1: $image['class'] = $image['class_top_center'];
                $imagetable .= imagelisttable($image, "0:0:0:0", "center");
                if($crow['render_titles']) {
                    $imagetable .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
                }
                break;
        //oben rechts
        case 2: if($image['usetable']) {
                    $imagetable .= '<div class="'.$template_default['classes']['img-list-right'].'">'.LF;
                    $imagetable .= imagelisttable($image, "0:0:0:0", "");
                    $imagetable .= '</div>'.LF;
                } else {
                    $image['class'] = $image['class_top_right'];
                    $imagetable .= imagelisttable($image, "0:0:0:0", "");
                }
                if($crow['render_titles']) {
                    $imagetable .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
                }
                break;
        //unten links
        case 3: if($crow['render_titles']) {
                    $imagetable .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
                }
                $image['class'] = $image['class_bottom_left'];
                $imagetable .= imagelisttable($image, "0:0:0:0", "");
                break;
        //unten mitte
        case 4: if($crow['render_titles']) {
                    $CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
                }
                $image['class'] = $image['class_bottom_center'];
                $imagetable .= imagelisttable($image, "0:0:0:0", "center");
                break;
        //unten rechts
        case 5: if($crow['render_titles']) {
                    $imagetable .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
                }
                if($image['usetable']) {
                    $imagetable .= '<div class="'.$template_default['classes']['img-list-right'].'">'.LF;
                    $imagetable .= imagelisttable($image, "0:0:0:0", "");
                    $imagetable .= '</div>'.LF;
                } else {
                    $image['class'] = $image['class_bottom_right'];
                    $imagetable .= imagelisttable($image, "0:0:0:0", "");
                }
                break;
        //im Text links
        case 6: if($crow['render_titles']) {
                    $imagetable .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
                }
                $image['class'] = $image['class_float_left'];
                $imagetable .= imagelisttable($image, "0:0:0:0", "left");
                break;
        //im Text rechts
        case 7: if($crow['render_titles']) {
                    $imagetable .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
                }
                $image['class'] = $image['class_float_right'];
                $imagetable .= imagelisttable($image, "0:0:0:0", "right");
                break;
        //oben links
        default:
                $image['class'] = $image['class_top_left'];
                $imagetable .= imagelisttable($image, "0:0:0:0", "");
                if($crow['render_titles']) {
                    $imagetable .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
                }
    }

    $GLOBALS['cnt_image_lightbox'] = 0;
    $cnt_image_lightbox = 0;

    $CNT_TMP .= LF . trim(str_replace('{ID}', $crow["acontent_id"], render_cnt_template($crow["acontent_template"], 'IMAGETABLE', $imagetable ))) . LF;

    unset($imagetable);

} else {
    $CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
}
unset($image);
