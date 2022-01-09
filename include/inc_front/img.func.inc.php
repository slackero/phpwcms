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

// image rendering functions
// moved away from front

function imagetable($phpwcms, & $image, $rand="0:0:0:0", $align=0) {
    // creates the image tags if text w/image
    // 0   :1       :2   :3        :4    :5     :6      :7       :8
    // dbid:filename:hash:extension:width:height:caption:position:zoom

    $cnt_image_lightbox = empty($GLOBALS['cnt_image_lightbox']) ? 0 : 1;
    $crop = empty($image['crop']) ? 0 : 1;

    if(!isset($image[8]) && isset($image['zoom'])) {
        $image[8] = $image['zoom'];
    } elseif(isset($image['zoom'])) {
        $image[8] = $image['zoom'];
    }

    $thumb_image = get_cached_image(array(
        "target_ext"    =>  $image[3],
        "image_name"    =>  $image[2] . '.' . $image[3],
        "max_width"     =>  $image[4],
        "max_height"    =>  $image[5],
        "thumb_name"    =>  md5($image[2].$image[4].$image[5].$phpwcms["sharpen_level"].$crop.$phpwcms['colorspace']),
        'crop_image'    =>  $crop
    ));

    if($thumb_image && $image[8]) {

        $zoominfo = get_cached_image(array(
            "target_ext"    =>  $image[3],
            "image_name"    =>  $image[2] . '.' . $image[3],
            "max_width"     =>  $phpwcms["img_prev_width"],
            "max_height"    =>  $phpwcms["img_prev_height"],
            "thumb_name"    =>  md5($image[2].$phpwcms["img_prev_width"].$phpwcms["img_prev_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
        ));

        if($zoominfo == false) {
            $image[8] = 0;
        }

    }

    $table = '';

    if($thumb_image != false) {

        // read content image info
        $table_class    = empty($GLOBALS["template_default"]["article"]["image_table_class"]) ? '' : ' class="'.$GLOBALS["template_default"]["article"]["image_table_class"].'"';
        $table_bgcolor  = empty($GLOBALS["template_default"]["article"]["image_table_bgcolor"]) ? '' : ' bgcolor="'.$GLOBALS["template_default"]["article"]["image_table_bgcolor"].'"';
        $image_align    = empty($GLOBALS["template_default"]["article"]["image_align"]) ? '' : ' align="'.$GLOBALS["template_default"]["article"]["image_align"].'"';
        $image_valign   = empty($GLOBALS["template_default"]["article"]["image_valign"]) ? '' : ' valign="'.$GLOBALS["template_default"]["article"]["image_valign"].'"';
        $image_border   = ' border="'.intval($GLOBALS["template_default"]["article"]["image_border"]).'"';
        $image_imgclass = empty($GLOBALS["template_default"]["article"]["image_imgclass"]) ? '' : ' class="'.$GLOBALS["template_default"]["article"]["image_imgclass"].'"';
        $image_class    = empty($GLOBALS["template_default"]["article"]["image_class"]) ? '' : ' class="'.$GLOBALS["template_default"]["article"]["image_class"].'"';
        $image_bgcolor  = empty($GLOBALS["template_default"]["article"]["image_bgcolor"]) ? '' : ' bgcolor="'.$GLOBALS["template_default"]["article"]["image_bgcolor"].'"';
        $caption_class  = empty($GLOBALS["template_default"]["article"]["image_caption_class"]) ? '' : ' class="'.$GLOBALS["template_default"]["article"]["image_caption_class"].'"';
        $caption_bgcolor= empty($GLOBALS["template_default"]["article"]["image_caption_bgcolor"]) ? '' : ' bgcolor="'.$GLOBALS["template_default"]["article"]["image_caption_bgcolor"].'"';
        $caption_valign = empty($GLOBALS["template_default"]["article"]["image_caption_valign"]) ? '' : ' valign="'.$GLOBALS["template_default"]["article"]["image_caption_valign"].'"';
        $caption_align  = empty($GLOBALS["template_default"]["article"]["image_caption_align"]) ? '' : ' align="'.$GLOBALS["template_default"]["article"]["image_caption_align"].'"';
        $capt_before    = $GLOBALS["template_default"]["article"]["image_caption_before"];
        $capt_after     = $GLOBALS["template_default"]["article"]["image_caption_after"];

        // image caption
        $caption = getImageCaption(array('caption' => base64_decode($image[6]), 'file' => $image[0]));
        $caption[0] = html($caption[0]);
        $caption[3] = empty($caption[3]) ? '' : ' title="'.html($caption[3]).'"'; //title
        $caption[1] = html(empty($caption[1]) ? $image[1] : $caption[1]);

        // image source
        $img  = '<img src="'.$thumb_image['src'].'" '.$thumb_image[3].$image_border.$image_imgclass.' alt="'.$caption[1].'" ';
        $img .= 'data-image-id="'.$image[0].'" data-image-hash="'.$image[2].'"'.$caption[3].' />';

        $tablewidth = $thumb_image[1];

        // spaces around image table
        $rand = explode(":", $rand);
        if(is_array($rand) && count($rand)) {
            foreach($rand as $key => $value) {
                $rand[$key] = intval($value);
            }
        } else {
            $rand = array(0,0,0,0);
        }
        if($rand[2] && $rand[3]) {
            $colspan = ' colspan="3"';
        } elseif($rand[2] || $rand[3]) {
            $colspan = ' colspan="2"';
        } else {
                $colspan = '';
        }
        $tablewidth += $rand[2] + $rand[3];

        $table .= '<table width="'.$tablewidth.'" border="0" cellspacing="0" cellpadding="0" ';
        $table .= ($align) ? 'align="'.$align.'"' : '';
        $table .= $table_bgcolor.$table_class.">\n";
        $table .= ($rand[0]) ? '<tr><td'.$colspan.'>'.spacer(1,$rand[0])."</td></tr>\n" : '';
        $table .= '<tr>';
        $table .= ($rand[2]) ? '<td>'.spacer($rand[2],1).'</td>' : '';
        if($image[8]) {

            $open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo['src'], $zoominfo[3], $image[1]);
            $table .= '<td'.$image_align.$image_valign.$image_bgcolor.$image_class.">";
            if($caption[2][0]) {
                $open_link = $caption[2][0];
                $return_false = '';
            } else {
                $open_link = $open_popup_link;
                $return_false = 'return false;';
            }

            if(!$cnt_image_lightbox || $caption[2][0]) {

                $table .= "<a href=\"".$open_link."\" onclick=\"checkClickZoom();clickZoom('".$open_popup_link."','previewpic','width=";
                $table .= $zoominfo[1].",height=".$zoominfo[2]."');".$return_false."\"".$caption[2][1].' class="'.$GLOBALS['template_default']['classes']['image-zoom'].'">';

            } else {

                $table .= '<a href="'.$zoominfo['src'].'" rel="lightbox"'.get_attr_data_gallery('', ' ', '');
                if($caption[0]) {
                    $table .= ' title="'.parseLightboxCaption($caption[0]).'"';
                }
                $table .= ' class="'.$GLOBALS['template_default']['classes']['image-lightbox'].'">';

            }
            $table .= $img.'</a></td>';
        } else {
            $table .= '<td'.$image_align.$image_valign.$image_bgcolor.$image_class.">";
            if($caption[2][0]) {
                $table .= '<a href="'.$caption[2][0].'"'.$caption[2][1].' class="'.$GLOBALS['template_default']['classes']['image-link'].'">'.$img.'</a>';
            } else {
                $table .= $img;
            }
            $table .= '</td>';
        }
        $table .= ($rand[3]) ? "<td>".spacer($rand[3],1)."</td>" : "";
        $table .= "</tr>\n";
        if($caption[0] && empty($image['nocaption'])) {
            $table .= "<tr>";
            $table .= ($rand[2]) ? "<td>".spacer($rand[2],1)."</td>" : "";
            $table .= '<td'.$caption_valign.$caption_align.$caption_bgcolor.$caption_class.'>'.$capt_before.$caption[0];
            if($caption[4] !== '') {
                $table .= ' <span class="'.$GLOBALS['template_default']['classes']['copyright'].'">'.html_specialchars($caption[4]).'</span>';
            }
            $table .= $capt_after."</td>";
            $table .= ($rand[3]) ? "<td>".spacer($rand[3],1)."</td>" : "";
            $table .= "</tr>\n";
        }
        $table .= ($rand[1]) ? "<tr><td".$colspan.">".spacer(1,$rand[1])."</td></tr>\n" : "";
        $table .= "</table>";

    }

    return $table;
}

function imagediv($phpwcms, $image, $classname='') {
    // creates the image tags if text w/image
    // 0   :1       :2   :3        :4    :5     :6      :7       :8
    // dbid:filename:hash:extension:width:height:caption:position:zoom

    $cnt_image_lightbox = empty($GLOBALS['cnt_image_lightbox']) ? 0 : 1;
    $crop = empty($image['crop']) ? 0 : 1;

    $thumb_image = get_cached_image(array(
        "target_ext"    =>  $image[3],
        "image_name"    =>  $image[2] . '.' . $image[3],
        "max_width"     =>  $image[4],
        "max_height"    =>  $image[5],
        "thumb_name"    =>  md5($image[2].$image[4].$image[5].$phpwcms["sharpen_level"].$crop.$phpwcms['colorspace']),
        'crop_image'    =>  $crop,
        'img_filename'  =>  $image[1]
    ));

    if($image[8]) {

        $zoominfo = get_cached_image(array(
            "target_ext"    =>  $image[3],
            "image_name"    =>  $image[2] . '.' . $image[3],
            "max_width"     =>  $phpwcms["img_prev_width"],
            "max_height"    =>  $phpwcms["img_prev_height"],
            "thumb_name"    =>  md5($image[2].$phpwcms["img_prev_width"].$phpwcms["img_prev_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace']),
            'img_filename'  =>  $image[1]
        ));

        if($zoominfo == false) {
            $image[8] = 0;
        }

    }

    $image_block = '';

    if($thumb_image != false) {

        // read content image info
        $image_border   = ' border="'.intval($GLOBALS["template_default"]["article"]["image_border"]).'"';
        $image_imgclass = empty($GLOBALS["template_default"]["article"]["image_imgclass"]) ? '' : ' class="'.$GLOBALS["template_default"]["article"]["image_imgclass"].'"';
        $image_class    = empty($GLOBALS["template_default"]["article"]["image_class"]) ? $GLOBALS['template_default']['classes']['image-thumb'] : $GLOBALS["template_default"]["article"]["image_class"];

        // image caption
        $caption    = getImageCaption(base64_decode($image[6]));
        $caption[0] = html($caption[0]);
        $caption[3] = empty($caption[3]) ? '' : ' title="'.html($caption[3]).'"'; //title
        $caption[1] = html(empty($caption[1]) ? $image[1] : $caption[1]);

        if(empty($classname)) {
            $classname = $GLOBALS['template_default']['classes']['image-wrapper'];
        }

        // image source
        $img  = '<img src="';
        if ($crop && !empty($thumb_image['src'])) {
            $img .= $thumb_image['src'] . '"';
            $thumb_image[3] = '';
        } else {
            $img .= PHPWCMS_IMAGES . $thumb_image[0] . '"';
        }
        $img .= ' '.$thumb_image[3] . ' data-image-id="'.$image[0].'" data-image-hash="'.$image[2].'"';
        $img .= $image_border.$image_imgclass.' alt="'.$caption[1].'"'.$caption[3].' />';

        $image_block .= '<div class="'.$classname.'">';

        if($image[8]) {

            $open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo[0], $zoominfo[3], $image[1]);
            $image_block .= '<div class="'.$image_class.'">';
            if($caption[2][0]) {
                $open_link = $caption[2][0];
                $return_false = '';
            } else {
                $open_link = $open_popup_link;
                $return_false = 'return false;';
            }

            if(!$cnt_image_lightbox || $caption[2][0]) {

                $image_block .= '<a href="'.$open_link."\" onclick=\"checkClickZoom();clickZoom('".$open_popup_link."','previewpic','width=";
                $image_block .= $zoominfo[1].",height=".$zoominfo[2]."');".$return_false."\"".$caption[2][1].' class="'.$GLOBALS['template_default']['classes']['image-zoom'].'">';

            } else {

                $image_block .= '<a href="'.PHPWCMS_IMAGES.$zoominfo[0].'" rel="lightbox"'.get_attr_data_gallery('', ' ', '');
                if($caption[0]) {
                    $image_block .= ' title="'.parseLightboxCaption($caption[0]).'"';
                }
                $image_block .= ' class="'.$GLOBALS['template_default']['classes']['image-lightbox'].'">';

            }
            $image_block .= $img.'</a></div>';

        } else {

            $image_block .= '<div class="'.$image_class.'">';
            $image_block .= $caption[2][0] ? '<a href="'.$caption[2][0].'"'.$caption[2][1].' class="'.$GLOBALS['template_default']['classes']['image-link'].'">'.$img.'</a>' : $img;
            $image_block .= '</div>';

        }

        if($caption[0] && empty($image['nocaption'])) {

            $caption_class  = empty($GLOBALS["template_default"]["article"]["image_caption_class"]) ? 'caption' : $GLOBALS["template_default"]["article"]["image_caption_class"];

            $image_block .= '<p style="width:'.$thumb_image[1].'px" class="'.$caption_class.'">'.$GLOBALS["template_default"]["article"]["image_caption_before"].$caption[0];

            if($caption[4] !== '') {
                $image_block .= ' <span class="'.$GLOBALS['template_default']['classes']['copyright'].'">'.html_specialchars($caption[4]).'</span>';
            }

            $image_block .= $GLOBALS["template_default"]["article"]["image_caption_after"]."</p>";

        }

        $image_block .= "</div>";

    }

    return $image_block;
}

function imagelisttable($imagelist, $rand="0:0:0:0", $align=0, $type=0) {
    // build imagelist or ecard chooser table
    // image: type = 0
    // ecard: type = 1
    $template_type  = $type ? 'ecard' : 'imagelist';
    $usetable       = !isset($imagelist['usetable']) || $imagelist['usetable'];

    if(empty($GLOBALS['cnt_image_lightbox'])) {
        $lightbox   = 0;
    } else {
        $lightbox   = generic_string(5);
    }

    $caption_on     = empty($imagelist['nocaption']);
    $crop           = empty($imagelist['crop']) ? 0 : 1;
    $image_border   = ' border="' . (empty($GLOBALS["template_default"]["article"][$template_type."_border"]) ? '0' : $GLOBALS["template_default"]["article"][$template_type."_border"]) . '"';

    if($usetable) {
        $table_class = $GLOBALS["template_default"]["article"][$template_type."_table_class"];
        if(empty($align)) {
            $align = '';
        } else {
            $table_class .= ' '.$GLOBALS['template_default']['classes']['image-list-table'].$align;
            $align = ' align="'.$align.'"';
        }
        $table_class    = ' class="'.trim($table_class).'"';
        $table_bgcolor  = empty($GLOBALS["template_default"]["article"][$template_type."_table_bgcolor"]) ? '' : ' bgcolor="'.$GLOBALS["template_default"]["article"][$template_type."_table_bgcolor"].'"';
        $image_align    = empty($GLOBALS["template_default"]["article"][$template_type."_align"]) ? '' : ' align="'.$GLOBALS["template_default"]["article"][$template_type."_align"].'"';
        $image_valign   = empty($GLOBALS["template_default"]["article"][$template_type."_valign"]) ? '' : ' valign="'.$GLOBALS["template_default"]["article"][$template_type."_valign"].'"';
        $image_class    = empty($GLOBALS["template_default"]["article"][$template_type."_class"]) ? '' : ' class="'.$GLOBALS["template_default"]["article"][$template_type."_class"].'"';
        $image_bgcolor  = empty($GLOBALS["template_default"]["article"][$template_type."_bgcolor"]) ? '' : ' bgcolor="'.$GLOBALS["template_default"]["article"][$template_type."_bgcolor"].'"';
        $caption_class  = empty($GLOBALS["template_default"]["article"][$template_type."_caption_class"]) ? '' : ' class="'.$GLOBALS["template_default"]["article"][$template_type."_caption_class"].'"';
        $caption_bgcolor= empty($GLOBALS["template_default"]["article"][$template_type."_caption_bgcolor"]) ? '' : ' bgcolor="'.$GLOBALS["template_default"]["article"][$template_type."_caption_bgcolor"].'"';
        $caption_valign = empty($GLOBALS["template_default"]["article"][$template_type."_caption_valign"]) ? '' : ' valign="'.$GLOBALS["template_default"]["article"][$template_type."_caption_valign"].'"';
        $caption_align  = empty($GLOBALS["template_default"]["article"][$template_type."_caption_align"]) ? '' : ' align="'.$GLOBALS["template_default"]["article"][$template_type."_caption_align"].'"';
        $image_imgclass = empty($GLOBALS["template_default"]["article"][$template_type."_imgclass"]) ? '' : ' class="'.$GLOBALS["template_default"]["article"][$template_type."_imgclass"].'"';
        $capt_before    = $GLOBALS["template_default"]["article"][$template_type."_caption_before"];
        $capt_after     = $GLOBALS["template_default"]["article"][$template_type."_caption_after"];
    } else {
        $image_imgclass = ' class="'.$imagelist['class_image_thumb'].'"';
    }


    $rand = explode(":", $rand);
    if(count($rand)) {
        foreach($rand as $key => $value) {
            $rand[$key] = intval($value);
        }
    } else {
        $rand = array(0,0,0,0);
    }
    $col_rand = ($rand[2] && $rand[3]) ? 2 : (($rand[2] || $rand[3]) ? 1 : 0 );

    if(($count_images = count($imagelist['images']))) {

        // randomize image
        if(!empty($imagelist['random'])) {
            shuffle($imagelist['images']);
        }

        if(empty($imagelist['limit'])) {
            $imagelist['limit'] = 0;
        }

        //Tabelle starten
        $table = LF;

        if($usetable) {
            $table .= ' <table border="0" cellspacing="0" cellpadding="0"'.$align.$table_bgcolor.$table_class.' summary="">'.LF;
        } else {
            $table .= ' <div class="';
            if(empty($imagelist['class'])) {
                $table .= 'image-table';
            } else {
                $table .= $imagelist['class'];
            }
            $table .= '">'.LF;
        }

        $x=0;
        $y=0;
        $z=0;
        foreach($imagelist['images'] as $key => $value) {

            if(isset($imagelist['width'])) {
                $imagelist['images'][$key][4] = $imagelist['width'];
                $imagelist['images'][$key][5] = $imagelist['height'];
            }

            $y++;
            if($usetable && $z && $x==1) {
                if($col_space) {
                    $table .= LF.'<tr>'.LF.'    <td';
                    $table .= (($col_total>1)?" colspan=\"".$col_total."\"":"");
                    if(!empty($GLOBALS["template_default"]['article']['imagelist_spacerrow_class'])) {
                        $table .= ' class="'.$GLOBALS["template_default"]['article']['imagelist_spacerrow_class'].'">';
                        $table .= spacer(1,1).'</td>'.LF.'</tr>'.LF;
                    } else {
                        $table .= '>'.spacer(1,$col_space).'</td>'.LF.'</tr>'.LF;
                    }
                }
            }

            if($usetable && !$x) {
                //Some default values
                $col_space = $imagelist['space'];   //Space between images
                $col_count = $imagelist['col'];     //columns
                $col_total = $col_count + (($col_space)?($col_count-1):(0)) + $col_rand;
                //Wenn oberer Rand definiert
                if($rand[0]) {
                    $table .= '<tr>'.LF.'   <td'.(($col_total>1)?' colspan="'.$col_total.'"':'').'>'.spacer(1,$rand[0]).'</td>'.LF.'</tr>'.LF;
                }
                $x=1;
            }
            if($usetable && $x==1) {

                // if left border
                $table_tmp   = ($rand[2]) ? '   <td width="'.$rand[2].'">'.spacer($rand[2],1).'</td>'.LF : '';

                //Neue Tabellenzeile
                $capt_tmp    = '';
                $capt_row    = '<tr>'.LF.$table_tmp;

                if($caption_on) {
                    $table  .= $capt_row;
                } else {
                    $table  .= '<tr>'.LF;
                }

            }
            //Aktuelle Bildspalte ausgeben
            if($usetable) {
                $table .= ' <td'.$image_align.$image_valign.$image_bgcolor.$image_class.'>';
            } else {

                if(!$x) {
                    $x = 1;
                }

                $table .= '     <div class="'.$imagelist['class_image_wrapper'];
                if($x === 1) {
                    $table .= ' first';
                }
                $table .= ' row-'.($z+1);
                $table .= '">' . LF . '         ';
            }

            $thumb_image = get_cached_image(array(
                "target_ext"    =>  $imagelist['images'][$key][3],
                "image_name"    =>  $imagelist['images'][$key][2] . '.' . $imagelist['images'][$key][3],
                "max_width"     =>  $imagelist['images'][$key][4],
                "max_height"    =>  $imagelist['images'][$key][5],
                "thumb_name"    =>  md5($imagelist['images'][$key][2].$imagelist['images'][$key][4].$imagelist['images'][$key][5].$GLOBALS['phpwcms']["sharpen_level"].$crop.$GLOBALS['phpwcms']['colorspace']),
                'crop_image'    =>  $crop
            ));

            if($thumb_image && $imagelist['zoom']) {

                $zoominfo = get_cached_image(array(
                    "target_ext"    =>  $imagelist['images'][$key][3],
                    "image_name"    =>  $imagelist['images'][$key][2] . '.' . $imagelist['images'][$key][3],
                    "max_width"     =>  $GLOBALS['phpwcms']["img_prev_width"],
                    "max_height"    =>  $GLOBALS['phpwcms']["img_prev_height"],
                    "thumb_name"    =>  md5($imagelist['images'][$key][2].$GLOBALS['phpwcms']["img_prev_width"].$GLOBALS['phpwcms']["img_prev_height"].$GLOBALS['phpwcms']["sharpen_level"].$GLOBALS['phpwcms']['colorspace'])
                ));
            }

            // now try to build caption and if neccessary add alt to image or set external link for image
            $caption = getImageCaption($imagelist['images'][$key][6]);
            // set caption and ALT Image Text for imagelist
            $capt_cur   = !$type ? html($caption[0]) : $caption[0];
            $caption[3] = empty($caption[3]) ? '' : ' title="'.html($caption[3]).'"'; //title
            $caption[1] = empty($caption[1]) ? html($imagelist['images'][$key][1]) : html_specialchars($caption[1]);

            $list_img_temp  = '<img src="'.$thumb_image['src'].'" '.$thumb_image[3].$image_border.$image_imgclass;
            $list_img_temp .= ' data-image-id="'.$imagelist['images'][$key][0].'" data-image-hash="'.$imagelist['images'][$key][2].'"';
            $list_img_temp .= ' data-image-ext="'.$imagelist['images'][$key][3].'"';
            $list_img_temp .= ' alt="'.$caption[1].'"'.$caption[3].' />';

            if($imagelist['zoom'] && isset($zoominfo) && $zoominfo != false) {
                // if click enlarge the image
                $open_popup_link = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo['src'], $zoominfo[3], $imagelist['images'][$key][1]);
                if($caption[2][0]) {
                    $open_link = $caption[2][0];
                    $return_false = '';
                } else {
                    $open_link = $open_popup_link;
                    $return_false = 'return false;';
                }

                if(!$lightbox || $caption[2][0]) {

                    $table .= "<a href=\"".$open_link."\" onclick=\"checkClickZoom();clickZoom('".$open_popup_link."','previewpic','width=";
                    $table .= $zoominfo[1].",height=".$zoominfo[2]."');".$return_false.'"'.$caption[2][1].' class="'.$imagelist['class_image_zoom'].'">';

                } else {

                    // lightbox
                    $table .= '<a href="'.$zoominfo['src'].'" rel="lightbox['.$lightbox.']"'.get_attr_data_gallery($lightbox, ' ', '');
                    if($capt_cur) {
                        $table .= ' title="'.parseLightboxCaption($capt_cur).'"';
                    }
                    $table .= ' class="'.$imagelist['class_image_lightbox'].'">';

                }

                $table .= $list_img_temp."</a>";
            } elseif($caption[2][0]) { // if not click enlarge
                $table .= '<a href="'.$caption[2][0].'"'.$caption[2][1].' class="'.$imagelist['class_image_link'].'">'.$list_img_temp.'</a>';
            } else {
                $table .= $list_img_temp;
            }

            if($usetable) {
                $table .= '</td>'.LF;
                if($caption_on && $capt_cur) {
                    $capt_tmp .= $capt_cur;
                    $capt_cur = '<span style="width:'.$thumb_image[1].'px">' . $capt_cur . '</span>';
                } else {
                    $capt_cur = '&nbsp;';
                }
                $capt_row .= '  <td'.$caption_valign.$caption_align.$caption_bgcolor.$caption_class.'>'.$capt_before.$capt_cur.$capt_after.'</td>'.LF;
            } else {

                if($caption_on && $capt_cur) {

                    $caption_class = empty($GLOBALS["template_default"]["article"]["image_caption_class"]) ? 'caption' : $GLOBALS["template_default"]["article"]["image_caption_class"];

                    $table .= LF . '            <p style="width:'.$thumb_image[1].'px" class="'.$caption_class.'">'.$GLOBALS["template_default"]["article"]["image_caption_before"];
                    $table .= $capt_cur;

                    if($caption[4] !== '') {
                        $table .= ' <span class="'.$GLOBALS['template_default']['classes']['copyright'].'">'.html_specialchars($caption[4]).'</span>';
                    }

                    $table .= $GLOBALS["template_default"]["article"]["image_caption_after"]."</p>" ;

                }

                $table .=  LF . '       </div>'.LF;
            }


            //Gegenchecken wieviele Tabellenspalten als Rest bleiben und ergänzen
            if($usetable) {
                if($y == $count_images && $col_count > 1) { //wenn eigentlich alle Bilder durchlaufen sind
                    if ($col_space && $x<$col_count) {
                        $xct = '    <td>'.spacer($col_space,1).'</td>'.LF;
                        $table      .= $xct;
                        $capt_row   .= $xct;
                    }
                    $rest_image = (ceil($count_images / $col_count) * $col_count) - $count_images;
                    for ($i=1; $i <= $rest_image; $i++) {
                        $table      .= '    <td>&nbsp;</td>';
                        $capt_row   .= '    <td>&nbsp;</td>';
                        if($i < $rest_image) {
                            if($col_space) {
                                $xct = '    <td width="'.$col_space.'">'.spacer($col_space,1).'</td>'.LF;
                                $table      .= $xct;
                                $capt_row   .= $xct;
                            }
                        }
                        $x++;
                    }
                }

                if($x==$col_count) {    //Wenn maximale Anzahl Bildspalten erreicht
                    $xct = ($rand[3]) ? '<td width="'.$rand[3].'">'.spacer($rand[3],1).'</td>'.LF : '';
                    $table      .= $xct;
                    $capt_row   .= $xct;
                    $table      .= "</tr>".LF;
                    $capt_row   .= "</tr>".LF;
                    if($capt_tmp) {
                        if($caption_on) {
                            $table  .= $capt_row;
                        }
                        $capt_row = '';
                        $capt_tmp = '';
                    }
                    $x=1; $z++;
                } else {
                    $xct         = ($col_space) ? ' <td width="'.$col_space.'">'.spacer($col_space,1).'</td>'.LF : '';
                    $table      .= $xct;
                    $capt_row   .= $xct;
                    $x++;
                }

            } elseif($x==$imagelist['col']) {
                $x = 0;
                $z++;
            } else {
                $x++;
            }

            // end if max image count
            if($imagelist['limit'] == $y) {
                break;
            }
        }

        if($usetable) {
            if($rand[1]) {
                $table .= '<tr>'.LF.'   <td'.(($col_total>1)?" colspan=\"".$col_total."\"":"").">".spacer(1,$rand[1]).'</td>'.LF.'</tr>'.LF;
            }
            $table .= ' </table>'.LF;
        } else {
            $table .= ' </div>'.LF;
        }
    }
    return $table;
}
