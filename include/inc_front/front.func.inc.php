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

//specific functions for the frontend

function spacer($width=1, $height=1) {
    //creates a placeholder image (transparent gif)
    return '<span style="display:inline-block;width:'.intval($width).'px;height:'.intval($height).'px" class="'.$GLOBALS['template_default']['classes']['spaceholder'].'"></span>';
}

function headline($head, $subhead, $layout) {
    $c = '';
    if($head) {
        $c .= $layout["content_head_before"];
        $c .= html_specialchars($head);
        $c .= $layout["content_head_after"];
    }
    if($subhead) {
        $c .= $layout["content_subhead_before"];
        $c .= html_specialchars($subhead);
        $c .= $layout["content_subhead_after"];
    }
    return $c;
}

//defines multimedia plugin specific values for width or height
function plugin_size($mediatype, $player, $width, $height) {

    switch($mediatype) {
        case 0: //Video
                switch($player) {
                    case 0: //Quicktime
                            $width = ($width) ? $width : "";
                            $height = ($height) ? $height+16 : "";
                            break;

                    case 1: //RealPlayer
                            $width = ($width) ? $width : "";
                            $width = ($height) ? $height+36 : "";
                            break;

                    case 2: //MediaPlayer
                            $width = ($width) ? $width : "";
                            $width = ($height) ? $height : "";
                            break;

                    case 3: //Flash
                            $width = ($width) ? $width : "";
                            $width = ($height) ? $height : "";
                            break;
                }
                break;

        case 1: //Audio
                break;

        case 2: //Flash
                break;

    }
}

function must_filled($c) {
    //spaceholder for form fields that have to be filled
    //with some content or has to be marked or like that
    return intval($c) ? ('<img src="img/article/fill_in_here.gif" alt=""' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE) : '';
}

//to add all relevant attributes that contains values to a string maybe a html tag
function add_attribute($attribute, $val, $space='', $allow_empty=false, $html_encode=false) {
    if(!$allow_empty && str_empty($val)) {
        return '';
    }
    if($html_encode) {
        $val = html($val);
    }
    $attribute = $attribute.'="'.$val.'"';
    if($space) {
        $attribute = $space.$attribute;
    }
    return $attribute;
}

//to add all relevant attributes that contains values to a string maybe a html tag
function add_style_attribute($attribute='', $val='') {
    return $attribute.': '.$val.';';
}

function html_attribute($attribute='', $val='', $space='', $allow_empty=false) {
    //to return only 1 well formatted attributes and values
    return add_attribute($attribute, $val, $space, $allow_empty, true);
}

//to return only 1 well formatted attributes and values
function html_height_attribute($val=0) {
    return ' style="height:'.intval($val).'px;" ';
}

function get_body_attributes($values) {
    //return a standard list of standard html body attributes
    //based on the pagelayout definitions
    $body_class = '';
    if(is_array($values)) {
        $link_class = '';
        if(empty($values["layout_noborder"])) {
            $body_class .= add_style_attribute('margin', '0');
            $body_class .= add_style_attribute('padding-top', empty($values["layout_border_top"]) ? '0' : intval($values["layout_border_top"]).'px');
            $body_class .= add_style_attribute('padding-bottom', empty($values["layout_border_bottom"]) ? '0' : intval($values["layout_border_bottom"]).'px');
            $body_class .= add_style_attribute('padding-left', empty($values["layout_border_left"]) ? '0' : intval($values["layout_border_left"]).'px');
            $body_class .= add_style_attribute('padding-right', empty($values["layout_border_right"]) ? '0' : intval($values["layout_border_right"]).'px');
        }
        if(!empty($values["layout_bgcolor"])) {
            $body_class .= add_style_attribute('background-color', $values["layout_bgcolor"]);
        }
        if(!empty($values["layout_bgimage"])) {
            $body_class .= add_style_attribute('background-image', 'url('.$values["layout_bgimage"].')');
        }
        if(!empty($values["layout_textcolor"])) {
            $body_class .= add_style_attribute('color', $values["layout_textcolor"]);
        }
        if($body_class) {
            $body_class  = '    body {'.$body_class.'}'.LF;
        }
        if(!empty($values["layout_linkcolor"])) {
            $link_class .= '    a, a:link, a:active, a:visited, a:hover {color:'.$values["layout_linkcolor"].';}'.LF;
        }
        if(!empty($values["layout_vcolor"])) {
            $link_class .= '    a:visited {color:'.$values["layout_vcolor"].';}'.LF;
        }
        if(!empty($values["layout_acolor"])) {
            $link_class .= '    a:active {color:'.$values["layout_acolor"].';}'.LF;
        }
        if(!empty($body_class) || !empty($link_class)) {
            $body_class  = '  <style type="text/css">'.LF.$body_class.$link_class.'  </style>'.LF;
        }
    }
    return $body_class;
}

function align_base_layout($value) {
    //to get the alignment of the base layout table
    if($value === 1) {
        return ' align="center"';
    } elseif($value === 2) {
        return ' align="right"';
    }
    return '';
}

function get_colspan($value) {
    //returns colspan value back to table row
    if (empty($value["layout_type"])) {
        $value["layout_type"] = 0;
    }
    if ($value["layout_type"] === 1 || $value["layout_type"] === 2) {
        $col = 2;
    } elseif ($value["layout_type"] === 3) {
        $col = 0;
    } else {
        $col = 3;
    }
    if(!empty($value["layout_leftspace_width"])) {
        $col++;
    }
    if(!empty($value["layout_rightspace_width"])) {
        $col++;
    }
    return $col ? ' colspan="'.$col.'"' : '';
}

function colspan_table_row($val, $block, $colspan="", $rowcontent="&nbsp;") {
    //creates a new table row for header or footer or such rows
    return ($rowcontent) ? "<tr>\n<td".$colspan.td_attributes($val, $block, 0).">".$rowcontent."</td>\n</tr>\n" : '';
}

function get_table_block($val, $content="", $leftblock="", $rightblock="") {
    //creates the string with all relevant main block data
    //$val = $pagelayout array values
    $mainblock  = "<tr>\n"; //start row

    //if 3column or 2column (with left block)
    if($val["layout_type"]==0 || $val["layout_type"]==1) {
        $mainblock .= "<td".td_attributes($val, "left").">".$leftblock."</td>\n";
    }

    //if there is a spacer column between left and main block
    if($val["layout_leftspace_width"]) {
        $mainblock .= "<td".td_attributes($val, "leftspace").">";
        $mainblock .= spacer($val["layout_leftspace_width"]);
        $mainblock .= "</td>\n";
    }

    $mainblock .= "<td".td_attributes($val, "content").">".$content."</td>\n";

    //if there is a spacer column between main block and right column
    if($val["layout_rightspace_width"]) {
        $mainblock .= "<td".td_attributes($val, "rightspace").">";
        $mainblock .= spacer($val["layout_rightspace_width"]);
        $mainblock .= "</td>\n";
    }

    //if 3column or 2column (with right block)
    if($val["layout_type"]==0 || $val["layout_type"]==2) {
        $mainblock .= "<td".td_attributes($val, "right").">".$rightblock."</td>\n";
    }

    $mainblock .= "</tr>\n"; //end row
    return $mainblock;
}

function td_attributes($val, $block, $top=1) {
    //creates a string with all relevant cell attributes like nackground color/image, class
    $td_attrib = $top ? add_attribute('valign', 'top', ' ') : '';
    if(!empty($val["layout_".$block."_height"])) {
        $td_attrib .= html_height_attribute($val["layout_".$block."_height"]);
    }
    if(!empty($val["layout_".$block."_width"])) {
        $td_attrib .= add_attribute('width', $val["layout_".$block."_width"], ' ');
    }
    if(!empty($val["layout_".$block."_bgcolor"])) {
        $td_attrib .= add_attribute('bgcolor', $val["layout_".$block."_bgcolor"], ' ');
    }
    if(!empty($val["layout_".$block."_bgimage"])) {
        $td_attrib .= add_attribute('style', 'background-image:url('.$val["layout_".$block."_bgimage"].')', ' ');
    }
    if(!empty($val["layout_".$block."_class"])) {
        $td_attrib .= add_attribute('class', $val["layout_".$block."_class"], ' ');
    }
    return $td_attrib;
}

function table_attributes($val, $var_part, $top=1, $tr=false) {
    //creates a string with all relevant cell attributes like background color/image, class
    //P.S. it is nearly the same as td_attributes - but it was boring to rewrite code ;-)
    $td_attrib = $top ? add_attribute('valign', 'top', ' ') : '';
    if(!$tr) {
        $td_attrib .= add_attribute('border', (empty($val[$var_part."_border"])) ? '0' : $val[$var_part."_border"], ' ');
        $td_attrib .= add_attribute('cellspacing', (empty($val[$var_part."_cspace"])) ? '0' : $val[$var_part."_cspace"], ' ');
        $td_attrib .= add_attribute('cellpadding', (empty($val[$var_part."_cpad"])) ? '0' : $val[$var_part."_cpad"], ' ');
    }

    if(!empty($val[$var_part."_height"])) {
        $td_attrib .= html_height_attribute($val[$var_part."_height"]);
    }
    if(!empty($val[$var_part."_width"])) {
        $td_attrib .= add_attribute('width', $val[$var_part."_width"], ' ');
    }
    if(!empty($val[$var_part."_bgcolor"])) {
        $td_attrib .= add_attribute('bgcolor', $val[$var_part."_bgcolor"], ' ');
    }
    if(!empty($val[$var_part."_bgimage"])) {
        $td_attrib .= add_attribute('background', $val[$var_part."_bgimage"], ' ');
    }
    if(!empty($val[$var_part."_class"])) {
        $td_attrib .= add_attribute('class', $val[$var_part."_class"], ' ');
    }

    return $td_attrib;
}

function get_breadcrumb($start_id, $struct_array, $key="acat_name") {
    //returns the breadcrumb path starting with given start_id
    $hash = 'breadcrumbdata_'.md5($start_id.$key);

    if(isset($GLOBALS['content'][$hash])) {
        return $GLOBALS['content'][$hash];
    }

    $data = array();
    while ($start_id && isset($struct_array[$start_id])) {
        $data[$start_id] = $struct_array[$start_id][$key];
        $start_id        = $struct_array[$start_id]["acat_struct"];
    }
    if(!empty($struct_array[$start_id][$key])) {
        $data[$start_id] = $struct_array[$start_id][$key];
    }
    return ($GLOBALS['content'][$hash] = array_reverse($data, 1));
}

// wrapper for breadcrumb frontend render
function breadcrumb_wrapper($match) {

    if(empty($match[2])) {
        $cat_only = false;
    } else {
        $cat_only = intval(trim($match[2], ',')) ? true : false;
    }

    return breadcrumb(
        $GLOBALS['content']["cat_id"],
        $GLOBALS['content']["struct"],
        empty($match[1]) ? 0 : $match[1],
        $GLOBALS['template_default']["breadcrumb_spacer"],
        $cat_only
    );
}

function breadcrumb($start_id, $struct_array, $end_id=0, $spacer=' &gt; ', $cat_only=false) {
    //builds the breadcrumb menu based on given values
    //$link_to = the page on which the breadcrum part links
    //$root_name = name of the breadcrumb part if empty/false/0 $start_id
    //$spacer = how should breadcrumb parts be divided
    $start_id   = intval($start_id);
    $end_id     = intval($end_id);
    $act_id     = $start_id; //store actual ID for later comparing
    $breadcrumb = array();
    $data       = array();
    $hash       = 'breadcrumb_'.md5($start_id.$end_id.$spacer);

    if(isset($GLOBALS['content'][$hash])) {
        return $GLOBALS['content'][$hash];
    }

    while ($start_id) { //get the breadcrumb path starting with given start_id
        if($end_id && $start_id === $end_id) {
            break;
        }
        $data[$start_id] = $struct_array[$start_id]["acat_name"];
        $start_id        = $struct_array[$start_id]["acat_struct"];
    }
    $data[$start_id]    = $struct_array[$start_id]["acat_name"];
    $data               = array_reverse($data, 1);

    // decide how to handle when in article detail or list mode
    $with_article       = $cat_only ? false : (!$GLOBALS['content']['list_mode'] && $GLOBALS['content']["article_list_count"] > 1);

    if(count($data)) {

        $breadcrumb_active_prefix = empty($GLOBALS['template_default']['breadcrumb_active_prefix']) ? '' : $GLOBALS['template_default']['breadcrumb_active_prefix'];
        $breadcrumb_active_suffix = empty($GLOBALS['template_default']['breadcrumb_active_suffix']) ? '' : $GLOBALS['template_default']['breadcrumb_active_suffix'];
        $breadcrumb_nolink_prefix = empty($GLOBALS['template_default']['breadcrumb_nolink_prefix']) ? '' : $GLOBALS['template_default']['breadcrumb_nolink_prefix'];
        $breadcrumb_nolink_suffix = empty($GLOBALS['template_default']['breadcrumb_nolink_suffix']) ? '' : $GLOBALS['template_default']['breadcrumb_nolink_suffix'];

        foreach($data as $key => $value) {

            $hidden = $struct_array[$key]["acat_hidden"];
            $link = 1;
            $nolink_prefix = '';
            $nolink_suffix = '';

            if($struct_array[$key]["acat_breadcrumb"]) {
                if($hidden && ($struct_array[$key]["acat_breadcrumb"] === 1 || $struct_array[$key]["acat_breadcrumb"] === 3)) {
                    $hidden = 0;
                }
                if($struct_array[$key]["acat_breadcrumb"] === 2 || $struct_array[$key]["acat_breadcrumb"] === 3) {
                    $link = 0;
                    $nolink_prefix = $breadcrumb_nolink_prefix;
                    $nolink_suffix = $breadcrumb_nolink_suffix;
                }
            }

            if(!$hidden) { // check if the structure should be unvisible when active

                $breadcrumb[$key] = '';

                if ($act_id != $key) {

                    if($link) {
                        if(!$struct_array[$key]["acat_redirect"]) {
                            $breadcrumb[$key] .= '<a href="' . rel_url(null, true, $struct_array[$key]["acat_alias"] ? html($struct_array[$key]["acat_alias"]) : 'id='.$key) . '">';
                        } else {
                            $redirect = get_redirect_link($struct_array[$key]["acat_redirect"], ' ', '');
                            $breadcrumb[$key] .= '<a href="'.$redirect['link'].'"'.$redirect['target'].'>';
                        }
                    }

                    $breadcrumb[$key] .= $nolink_prefix . html($data[$key]) . $nolink_suffix;

                } elseif($with_article) {

                    if($link) {
                        if(!$struct_array[$key]["acat_redirect"]) {
                            $breadcrumb[$key] .= '<a href="' . rel_url(null, true, $struct_array[$key]["acat_alias"] ? html($struct_array[$key]["acat_alias"]) : 'id='.$key) . '">';
                        } else {
                            $redirect = get_redirect_link($struct_array[$key]["acat_redirect"], ' ', '');
                            $breadcrumb[$key] .= '<a href="'.$redirect['link'].'"'.$redirect['target'].'>';
                        }
                    }

                    $breadcrumb[$key] .= $nolink_prefix . html($data[$key]) . $nolink_suffix;

                } else {

                    if($link) {
                        if(!$struct_array[$key]["acat_redirect"]) {
                            $breadcrumb[$key] .= '<a href="' . rel_url(null, true, $struct_array[$key]["acat_alias"] ? html($struct_array[$key]["acat_alias"]) : 'id='.$key);
                            $breadcrumb[$key] .= '" class="'.$GLOBALS['template_default']['classes']['breadcrumb-active'].'">';
                        } else {
                            $redirect = get_redirect_link($struct_array[$key]["acat_redirect"], ' ', '');
                            $breadcrumb[$key] .= '<a href="'.$redirect['link'].'"'.$redirect['target'].' class="'.$GLOBALS['template_default']['classes']['breadcrumb-active'].'">';
                        }
                    }

                    $breadcrumb[$key] .= $breadcrumb_active_prefix;
                    $breadcrumb[$key] .= $nolink_prefix . html($data[$key]) . $nolink_suffix;
                    $breadcrumb[$key] .= $breadcrumb_active_suffix;

                }

                if($link) {
                    $breadcrumb[$key] .= '</a>';
                }

            }
        }
    }

    // add current article information
    if($with_article && isset($GLOBALS['content']["article_menutitle"])) {

        $breadcrumb['article']  = '<a href="' . rel_url(null, true, defined('PHPWCMS_ALIAS') ? html(PHPWCMS_ALIAS) : 'aid='.$GLOBALS['content']["article_id"]);
        $breadcrumb['article'] .= '" class="'.$GLOBALS['template_default']['classes']['breadcrumb-active'].'">';

        if(!empty($GLOBALS['template_default']['breadcrumb_active_prefix'])) {
            $breadcrumb['article'] .= $GLOBALS['template_default']['breadcrumb_active_prefix'];
        }

        $breadcrumb['article'] .= html($GLOBALS['content']["article_menutitle"]);

        if(!empty($GLOBALS['template_default']['breadcrumb_active_suffix'])) {
            $breadcrumb['article'] .= $GLOBALS['template_default']['breadcrumb_active_suffix'];
        }

        $breadcrumb['article'] .= '</a>';
    }

    return ($GLOBALS['content'][$hash] = implode($spacer, $breadcrumb));
}

function get_redirect_link($link='#', $pre='', $after=' ', $self=null) {
    // returns the link var and target var if available
    $link           = explode(' ', $link);
    $l['link']      = empty($link[0]) ? '#' : $link[0];
    $l['target']    = empty($link[1]) ? ''  : $pre.'target="'.$link[1].'"'.$after;
    return $l;
}

function get_struct_data($root_name='', $root_info='') {
    //returns the complete active and public struct data as array
    //so it is reusable by many menu functions -> lower db access

    // first check pre-rendered structure for current user mode
    $sysvalue_key   = 'structure_array_vmode_'.get_user_vmode();
    $data           = _getConfig($sysvalue_key);

    if(is_array($data)) {
        return $data;
    }

    global $indexpage;
    $data = array();

    $data[0] = array(
        "acat_id"           => 0,
        "acat_name"         => $indexpage['acat_name'],
        "acat_info"         => $indexpage['acat_info'],
        "acat_struct"       => 0,
        "acat_sort"         => 0,
        "acat_hidden"       => intval($indexpage['acat_hidden']),
        "acat_regonly"      => intval($indexpage['acat_regonly']),
        "acat_ssl"          => intval($indexpage['acat_ssl']),
        "acat_template"     => intval($indexpage['acat_template']),
        "acat_alias"        => $indexpage['acat_alias'],
        "acat_topcount"     => intval($indexpage['acat_topcount']),
        "acat_maxlist"      => intval($indexpage['acat_maxlist']),
        "acat_redirect"     => $indexpage['acat_redirect'],
        "acat_order"        => intval($indexpage['acat_order']),
        "acat_timeout"      => $indexpage['acat_timeout'],
        "acat_nosearch"     => $indexpage['acat_nosearch'],
        "acat_nositemap"    => $indexpage['acat_nositemap'],
        "acat_permit"       => !empty($indexpage['acat_permit']) && is_array($indexpage['acat_permit']) ? $indexpage['acat_permit'] : array(),
        "acat_pagetitle"    => empty($indexpage['acat_pagetitle']) ? '' : $indexpage['acat_pagetitle'],
        "acat_paginate"     => empty($indexpage['acat_paginate']) ? 0 : 1,
        "acat_overwrite"    => empty($indexpage['acat_overwrite']) ? '' : $indexpage['acat_overwrite'],
        "acat_archive"      => empty($indexpage['acat_archive']) ? 0 : 1,
        "acat_class"        => empty($indexpage['acat_class']) ? '' : $indexpage['acat_class'],
        "acat_keywords"     => empty($indexpage['acat_keywords']) ? '' : $indexpage['acat_keywords'],
        "acat_disable301"   => empty($indexpage['acat_disable301']) ? 0 : 1,
        "acat_opengraph"    => isset($indexpage['acat_opengraph']) ? intval($indexpage['acat_opengraph']) : 1,
        "acat_canonical"    => empty($indexpage['acat_canonical']) ? '' : $indexpage['acat_canonical'],
        "acat_breadcrumb"   => empty($indexpage['acat_breadcrumb']) ? 0 : intval($indexpage['acat_breadcrumb']),
        "acat_onepage"      => empty($indexpage['acat_onepage']) ? 0 : 1,
    );
    $sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecat WHERE ";
    // VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
    if(VISIBLE_MODE != 2) {
        // for 0 AND 1
        $sql .= "acat_aktiv=1 AND ";
    }
    $sql .= "acat_trash=0 ORDER BY acat_struct, acat_sort";

    $result = _dbQuery($sql);

    if(isset($result[0]['acat_id'])) {
        foreach($result as $row) {
            $data[$row["acat_id"]] = array(
                "acat_id"           => $row["acat_id"],
                "acat_name"         => $row["acat_name"],
                "acat_info"         => $row["acat_info"],
                "acat_struct"       => $row["acat_struct"],
                "acat_sort"         => $row["acat_sort"],
                "acat_hidden"       => intval($row["acat_hidden"]),
                "acat_regonly"      => $row["acat_regonly"],
                "acat_ssl"          => $row["acat_ssl"],
                "acat_template"     => $row["acat_template"],
                "acat_alias"        => $row["acat_alias"],
                "acat_topcount"     => $row["acat_topcount"],
                "acat_maxlist"      => $row["acat_maxlist"],
                "acat_redirect"     => $row["acat_redirect"],
                "acat_order"        => $row["acat_order"],
                "acat_timeout"      => $row["acat_cache"],
                "acat_nosearch"     => $row["acat_nosearch"],
                "acat_nositemap"    => $row["acat_nositemap"],
                "acat_permit"       => empty($row["acat_permit"]) ? array() : explode(',', $row["acat_permit"]),
                "acat_pagetitle"    => $row["acat_pagetitle"],
                "acat_paginate"     => $row["acat_paginate"],
                "acat_overwrite"    => $row["acat_overwrite"],
                "acat_archive"      => $row["acat_archive"],
                "acat_class"        => $row["acat_class"],
                "acat_keywords"     => $row["acat_keywords"],
                "acat_disable301"   => $row["acat_disable301"],
                "acat_opengraph"    => $row["acat_opengraph"],
                "acat_canonical"    => $row["acat_canonical"],
                "acat_breadcrumb"   => intval($row["acat_breadcrumb"]),
                "acat_onepage"      => $row["acat_onepage"],
                "acat_lang"         => $row["acat_lang"],
                "acat_lang_type"    => $row["acat_lang_type"],
                "acat_lang_id"      => intval($row["acat_lang_id"])
            );
        }
    }

    // set max_allowed_package to bigger value
    _dbSetVar('max_allowed_packet', 16*1024*1024, '<');

    // store pre-rendered serialized array in database
    _setConfig($sysvalue_key, $data, 'frontend_render', 1);

    return $data;
}

function get_actcat_articles_data($act_cat_id) {
    //returns the complete active and public article data as array (basic infos only)
    //so it is reusable by many functions -> lower db access

    global $content;

    $data               = array();
    if(isset($content['struct'][ $act_cat_id ])) {
        $ao = get_order_sort($content['struct'][ $act_cat_id ]['acat_order']);
    } else {
        return $data;
    }
    $as                 = $content['struct'][ $act_cat_id ];
    $as['acat_maxlist'] = intval($as['acat_maxlist']);

    $sql  = "SELECT *, UNIX_TIMESTAMP(article_tstamp) AS article_date, ";
    $sql .= "UNIX_TIMESTAMP(article_begin) AS article_livedate, ";
    $sql .= "UNIX_TIMESTAMP(article_end) AS article_killdate ";
    $sql .= "FROM ".DB_PREPEND."phpwcms_article ";
    $sql .= "WHERE article_cid=".$act_cat_id;
    // VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
    switch(VISIBLE_MODE) {
        case 0: $sql .= " AND article_aktiv=1";
                break;
        case 1: $sql .= " AND (article_aktiv=1 OR article_uid=".$_SESSION["wcs_user_id"].')';
                break;
        //case 2: admin mode no additional neccessary
    }
    $sql .= ' AND article_deleted=0';
    if(!PREVIEW_MODE) {
        $sql .= ' AND article_begin < NOW() ';
        if($content['struct'][ $act_cat_id ]['acat_archive'] == 0) {
            $sql .= " AND (article_end='0000-00-00 00:00:00' OR article_end > NOW())";
        } else {
            $sql .= " AND IF(article_archive_status=1, 1, (article_end='0000-00-00 00:00:00' OR article_end > NOW()))";
        }
    }
    $sql .= ' ORDER BY '.$ao[2];

    if(empty($as['acat_paginate']) && $as['acat_maxlist'] && $as['acat_topcount'] >= $as['acat_maxlist']) {
        $sql .= ' LIMIT '.$as['acat_maxlist'];
    }

    $result = _dbQuery($sql);

    if(isset($result[0]['article_id'])) {
        foreach($result as $row) {
            $aid = $row["article_id"];
            $data[$aid] = array(
                "article_id"            => $row["article_id"],
                "article_cid"           => $row["article_cid"],
                "article_title"         => $row["article_title"],
                "article_subtitle"      => $row["article_subtitle"],
                "article_menutitle"     => $row["article_menutitle"],
                "article_keyword"       => $row["article_keyword"],
                "article_summary"       => $row["article_summary"],
                "article_redirect"      => $row["article_redirect"],
                "article_date"          => $row["article_date"],
                "article_username"      => $row["article_username"],
                "article_sort"          => $row["article_sort"],
                "article_notitle"       => $row["article_notitle"],
                "article_created"       => $row["article_created"],
                "article_image"         => @unserialize($row["article_image"]),
                "article_timeout"       => $row["article_cache"],
                "article_nosearch"      => $row["article_nosearch"],
                "article_nositemap"     => $row["article_nositemap"],
                "article_aliasid"       => $row["article_aliasid"],
                "article_headerdata"    => $row["article_headerdata"],
                "article_morelink"      => $row["article_morelink"],
                "article_begin"         => $row["article_begin"],
                "article_end"           => $row["article_end"],
                "article_alias"         => $row["article_alias"],
                'article_livedate'      => $row["article_livedate"],
                'article_killdate'      => $row["article_killdate"],
                'article_uid'           => $row["article_uid"],
                'article_description'   => $row["article_description"],
                'article_meta'          => $row["article_meta"]
            );
            // now check for article alias ID
            if($row["article_aliasid"]) {
                $alias_sql  = "SELECT *, UNIX_TIMESTAMP(article_tstamp) AS article_date, ";
                $alias_sql .= "UNIX_TIMESTAMP(article_begin) AS article_livedate, ";
                $alias_sql .= "UNIX_TIMESTAMP(article_end) AS article_killdate ";
                $alias_sql .= "FROM ".DB_PREPEND."phpwcms_article ";
                $alias_sql .= "WHERE article_deleted=0 AND article_id=".intval($row["article_aliasid"]);
                if(!$row["article_headerdata"]) {
                    switch(VISIBLE_MODE) {
                        case 0: $alias_sql .= " AND article_aktiv=1";
                                break;
                        case 1: $alias_sql .= " AND (article_aktiv=1 OR article_uid=".$_SESSION["wcs_user_id"].')';
                                break;
                    }
                    if(!PREVIEW_MODE) {
                        $alias_sql .= " AND article_begin < NOW() AND article_end > NOW()";
                    }
                }
                $alias_sql .= " AND article_deleted=0 LIMIT 1";

                $alias_result = _dbQuery($alias_sql);

                if(isset($alias_result[0]['article_id'])) {

                    $data[$aid]["article_id"] = $alias_result[0]["article_id"];
                    $data[$aid]["article_alias"] = $alias_result[0]["article_alias"];
                    $data[$aid]["article_uid"] = $alias_result[0]["article_uid"];

                    // use alias article header data
                    if(!$row["article_headerdata"]) {
                        $data[$aid]["article_title"]        = $alias_result[0]["article_title"];
                        $data[$aid]["article_subtitle"]     = $alias_result[0]["article_subtitle"];
                        $data[$aid]["article_keyword"]      = $alias_result[0]["article_keyword"];
                        $data[$aid]["article_summary"]      = $alias_result[0]["article_summary"];
                        $data[$aid]["article_redirect"]     = $alias_result[0]["article_redirect"];
                        $data[$aid]["article_date"]         = $alias_result[0]["article_date"];
                        $data[$aid]["article_image"]        = @unserialize($alias_result[0]["article_image"]);
                        $data[$aid]["article_begin"]        = $alias_result[0]["article_begin"];
                        $data[$aid]["article_end"]          = $alias_result[0]["article_end"];
                        $data[$aid]['article_livedate']     = $alias_result[0]["article_livedate"];
                        $data[$aid]['article_killdate']     = $alias_result[0]["article_killdate"];
                        $data[$aid]['article_menutitle']    = $alias_result[0]["article_menutitle"];
                        $data[$aid]['article_description']  = $alias_result[0]["article_description"];
                        $data[$aid]['article_meta']         = $alias_result[0]["article_meta"];
                    }
                }
            }

            if($data[$aid]['article_meta']) {
                $data[$aid]['article_meta'] = json_decode($data[$aid]['article_meta'], true);
            }
            if(is_array($data[$aid]['article_meta'])) {
                $data[$aid]['article_meta'] = array_merge(get_default_article_meta(), $data[$aid]['article_meta']);
            } else {
                $data[$aid]['article_meta'] = get_default_article_meta();
            }
        }
    }

    return $data;
}

function setArticleSummaryImageData($img) {
    // used to set correct list image values based on given data

    //first check if list_image data is set - will not for all old articles
    if(!isset($img['list_usesummary'])) {
        $img['list_usesummary'] = 1;
    }

    if($img['list_usesummary'] && !empty($img['hash'])) {
        $img['list_name'] = $img['name'];
        $img['list_hash'] = $img['hash'];
        $img['list_ext'] = $img['ext'];
        $img['list_id'] = $img['id'];
        $img['list_caption'] = $img['caption'];
        $img['list_zoom'] = empty($img['list_zoom']) ? $img['zoom'] : $img['list_zoom'];
        $img['list_width'] = empty($img['list_width']) ? $img['width'] : $img['list_width'];
        $img['list_height'] = empty($img['list_height']) ? $img['height'] : $img['list_height'];
        $img['list_caption_suppress'] = empty($img['list_caption_suppress']) ? (empty($img['caption_suppress']) ? 0 : 1) : $img['list_caption_suppress'];
    }

    return $img;
}

function get_real_imgsrc($img='') {
    // strips real src attribute from image tag
    if($img) {
        $img = preg_replace('/.*src=["|\'](.*?)["|\'].*/i', "$1", $img);
    }
    return $img;
}

function add_linkid($img='', $linkid='') {
    //used to add the link ID for js over functions
    $img = preg_replace('/( \/>|>)$/', $linkid."$1", $img);
    return $img;
}

function build_levels($struct, $level, $temp_tree, $act_cat_id, $nav_table_struct, $count, $div, $link_to) {

    // this returns the level structure based on given arrays
    // it is special for browsing from root levels

    $nav_table_struct["linkimage_over_js"]  = $nav_table_struct['array_struct'][$count]["linkimage_over_js"];
    $nav_table_struct["linkimage_norm"]     = $nav_table_struct['array_struct'][$count]["linkimage_norm"];
    $nav_table_struct["linkimage_over"]     = $nav_table_struct['array_struct'][$count]["linkimage_over"];
    $nav_table_struct["linkimage_active"]   = $nav_table_struct['array_struct'][$count]["linkimage_active"];
    $nav_table_struct["row_norm_bgcolor"]   = $nav_table_struct['array_struct'][$count]["row_norm_bgcolor"];
    $nav_table_struct["row_norm_class"]     = $nav_table_struct['array_struct'][$count]["row_norm_class"];
    $nav_table_struct["row_over_bgcolor"]   = $nav_table_struct['array_struct'][$count]["row_over_bgcolor"];
    $nav_table_struct["row_active_bgcolor"] = $nav_table_struct['array_struct'][$count]["row_active_bgcolor"];
    $nav_table_struct["row_active_class"]   = $nav_table_struct['array_struct'][$count]["row_active_class"];
    $nav_table_struct["space_celltop"]      = $nav_table_struct['array_struct'][$count]["space_celltop"];
    $nav_table_struct["space_cellbottom"]   = $nav_table_struct['array_struct'][$count]["space_cellbottom"];
    $nav_table_struct["cell_height"]        = $nav_table_struct['array_struct'][$count]["cell_height"];
    $nav_table_struct["cell_class"]         = $nav_table_struct['array_struct'][$count]["cell_class"];
    $nav_table_struct["cell_active_height"] = $nav_table_struct['array_struct'][$count]["cell_active_height"];
    $nav_table_struct["cell_active_class"]  = $nav_table_struct['array_struct'][$count]["cell_active_class"];
    $nav_table_struct["link_before"]        = $nav_table_struct['array_struct'][$count]["link_before"];
    $nav_table_struct["link_after"]         = $nav_table_struct['array_struct'][$count]["link_after"];
    $nav_table_struct["link_active_before"] = $nav_table_struct['array_struct'][$count]["link_active_before"];
    $nav_table_struct["link_active_after"]  = $nav_table_struct['array_struct'][$count]["link_active_after"];

    $temp_menu      = '';
    $js             = '';
    $depth          = count($temp_tree)-$div;
    $current_level  = $count;
    $count++;
    $depth2         = $depth-$count+2;
    $right_cell     = '';
    $left_cell      = '';
    $cell_top       = '';
    $cell_bottom    = '';
    $space_right    = '';
    $space_cell     = '';
    $space_row      = '';
    $cell_height    = $nav_table_struct["cell_height"] ? $nav_table_struct["cell_height"] : 1;

    if($nav_table_struct["space_right"]) {
        $right_cell  = "<td width=\"".$nav_table_struct["space_left"]."\">";
        $right_cell .= spacer($nav_table_struct["space_right"], $cell_height)."</td>\n";
        $space_right = "<td>".spacer(1, 1)."</td>";
    }
    if($nav_table_struct["space_left"]) {
        $colspan    = ($count > 1) ? " colspan=\"".$count."\"" : "";
        $left_cell  = "<td width=\"".$nav_table_struct["space_left"]."\"".$colspan.">";
        $left_cell .= spacer($nav_table_struct["space_left"], $cell_height)."</td>\n";
        $space_cell = "<td".$colspan.">".spacer(1, 1)."</td><td>".spacer(1, 1)."</td>";
    } elseif($count > 1) {
        $colspan    = ($count > 2) ? " colspan=\"".($count-1)."\"" : "";
        $left_cell  = "<td ".$colspan.">".spacer(1, 1)."</td>\n";
        $space_cell = "<td".$colspan.">".spacer(1, 1)."</td><td>".spacer(1, 1)."</td>";
    }
    if($nav_table_struct["space_celltop"]) $cell_top = spacer(1, $nav_table_struct["space_celltop"])."<br />";
    if($nav_table_struct["space_cellbottom"]) $cell_bottom = "<br />".spacer(1, $nav_table_struct["space_cellbottom"]);

    $colspan    = ($depth2 > 1) ? ' colspan="'.($depth2).'"' : '';

    foreach($struct as $key => $value) {

        if( _getStructureLevelDisplayStatus($key, $level) ) {

            $link_image_id  = "linkid".generic_string(6);
            $link_name_id   = ' name="'.$link_image_id.'" id="'.$link_image_id.'"';

            if(!$struct[$key]["acat_redirect"]) {
                $link = rel_url(array(), array('newsdetail'), $struct[$key]["acat_alias"] ? $struct[$key]["acat_alias"] : 'id='.$key);
                $redirect['target'] = '';
            } else {
                $redirect = get_redirect_link($struct[$key]["acat_redirect"], ' ', '');
                $link = $redirect['link'];
            }

            $js     = ' style="cursor:pointer;cursor:hand;"'; //display:block;
            $js_act = $js;
            if($nav_table_struct["js_over_effects"]) {

                if($redirect['target'] != ' target="_blank"') {
                    $js .= " onclick=\"location.href='".js_singlequote($link)."';return false;\"";
                } else {
                    $js .= " onclick=\"window.open('".js_singlequote($link)."', 'phpwcmnewwin');return false;\"";
                }

                $js_act = $js;
                $js .= ' onmouseover="';
                if($nav_table_struct["linkimage_over_js"]) {
                    $js .= "MM_swapImage('".$link_image_id."','','".$nav_table_struct["linkimage_over_js"]."',1);";
                }
                if($nav_table_struct["row_over_bgcolor"]) $js .= "this.bgColor='".$nav_table_struct["row_over_bgcolor"]."';";
                $js .= '" onmouseout="';
                if($nav_table_struct["linkimage_over_js"]) $js .= "MM_swapImgRestore();";
                if($nav_table_struct["row_norm_bgcolor"]) $js .= "this.bgColor='".$nav_table_struct["row_norm_bgcolor"]."';";
                $js .= '"';
            } else {
                $js = '';
            }

            // add structure level based classes
            if(!empty($struct[$key]["acat_class"])) {
                $nav_table_struct_temp = $nav_table_struct;
                $nav_table_struct["row_norm_class"]     = trim($nav_table_struct["row_norm_class"].' '.$struct[$key]["acat_class"]);
                $nav_table_struct["row_active_class"]   = trim($nav_table_struct["row_active_class"].' '.$struct[$key]["acat_class"]);
                $nav_table_struct["row_space_class"]    = 'row_space '.$struct[$key]["acat_class"];
            } else {
                $nav_table_struct_temp = NULL;
            }

            //spacer row
            if($nav_table_struct["row_space"]) {
                $space_row  = "<tr".table_attributes($nav_table_struct, "row_space", 0, true).">\n".$space_cell;
                $space_row .= "<td".$colspan.">".spacer(1, $nav_table_struct["row_space"])."</td>";
                $space_row .= $space_right."\n</tr>\n";
                $temp_menu .= $space_row;
            }

            if(!empty($temp_tree[$key])) {
                //if($act_cat_id == $key) {
                //check if inside active tree structure
                if($act_cat_id == $key || (!empty($nav_table_struct["all_nodes_active"]) && isset($GLOBALS['LEVEL_KEY'][$key]))) {
                    $temp_menu .= "<tr".table_attributes($nav_table_struct, "row_active", 0, true).$js_act.">\n".$left_cell;
                    $temp_menu .= "<td valign=\"top\">".str_replace('#', $link_name_id, $nav_table_struct["linkimage_active"])."</td>\n";
                    $temp_menu .= "<td".table_attributes($nav_table_struct, "cell_active", 1, true).$colspan.">".$cell_top;
                    $temp_menu .= '<a href="'.$link.'"'.$redirect['target'].'>';
                    $temp_menu .= $nav_table_struct["link_active_before"];
                    $temp_menu .= html_specialchars($struct[$key]["acat_name"]);
                    $temp_menu .= $nav_table_struct["link_active_after"].'</a>';
                } else {
                    $temp_menu .= "<tr".table_attributes($nav_table_struct, "row_norm", 0, true).$js.">\n".$left_cell;
                    $temp_menu .= "<td valign=\"top\">".str_replace('#', $link_name_id, $nav_table_struct["linkimage_norm"])."</td>\n";
                    $temp_menu .= "<td".table_attributes($nav_table_struct, "cell", 1, true).$colspan.">".$cell_top;
                    $temp_menu .= '<a href="'.$link.'"'.$redirect['target'].'>';
                    $temp_menu .= $nav_table_struct["link_before"];
                    $temp_menu .= html_specialchars($struct[$key]["acat_name"]);
                    $temp_menu .= $nav_table_struct["link_after"].'</a>';
                }

                $temp_menu .= $cell_bottom."</td>\n".$right_cell."</tr>\n";
                $temp_menu .= build_levels ($struct, $key, $temp_tree, $act_cat_id, $nav_table_struct, $count, $div, $link_to);
            } else {
                $temp_menu .= "<tr".table_attributes($nav_table_struct, "row_norm", 0, true).$js.">\n".$left_cell;
                $temp_menu .= "<td valign=\"top\">".str_replace('#', $link_name_id, $nav_table_struct["linkimage_norm"])."</td>\n";
                $temp_menu .= "<td".table_attributes($nav_table_struct, "cell", 1, true).$colspan.">".$cell_top;
                $temp_menu .= '<a href="'.$link.'"'.$redirect['target'].'>';
                $temp_menu .= $nav_table_struct["link_before"];
                $temp_menu .= html_specialchars($struct[$key]["acat_name"]);
                $temp_menu .= $nav_table_struct["link_after"].'</a>';
                $temp_menu .= $cell_bottom."</td>\n".$right_cell."</tr>\n";
            }

            // reset table structure attributes
            if($nav_table_struct_temp !== NULL) {
                $nav_table_struct = $nav_table_struct_temp;
            }
        }
    }

    if($nav_table_struct["row_space"] && $count == 1) {
        $temp_menu .= $space_row;
    }

    return $temp_menu;
}

function list_articles_summary($alt=NULL, $topcount=99999, $template='') {
    // returns an article listing only with headline and summary text
    // and with an listing of all other available articles of this category

    global $content;
    global $template_default;
    global $_getVar;

    // alternative way to send article listings
    if(is_array($alt)) {
        // first save default value of $content["articles"]
        $_old_articles          = $content["articles"];
        $content["articles"]    = $alt;
        $temp_topcount          = intval($topcount);
        if($temp_topcount == 0) {
            $temp_topcount      = $content['struct'][ $content['cat_id'] ]['acat_topcount'];
        }
        $template               = trim($template);
    } else {
        $temp_topcount = $content['struct'][ $content['cat_id'] ]['acat_topcount'];
    }

    $max_articles = count($content["articles"]);

    if(empty($template_default['article_paginate_show'])) {
        $paginate_show = array('bottom'=>1);
    } else {
        $paginate_show = array();
        foreach((explode(' ', $template_default['article_paginate_show'])) as $value) {
            if($value == 'top') {
                $paginate_show['top'] = 1;
            } elseif($value == 'bottom') {
                $paginate_show['bottom'] = 1;
            } elseif(strpos($value, 'rt') !== false) {
                $paginate_show['rt'] = str_replace('rt', '', $value);
            }
        }
        if(!count($paginate_show)) {
            $paginate_show = array('bottom'=>1);
        }
    }

    if($content['struct'][ $content['cat_id'] ]['acat_paginate'] && $content['struct'][ $content['cat_id'] ]['acat_maxlist'] && $max_articles > $content['struct'][ $content['cat_id'] ]['acat_maxlist']) {

        $paginate       = true;
        $paginate_navi  = empty($template_default['article_paginate_navi']) ? '<div class="'.$template_default['classes']['article-list-paginate'].'">{PREV:&laquo;} {NEXT:&raquo;}</div>' : $template_default['article_paginate_navi'];
        $max_pages      = ceil($max_articles / $content['struct'][ $content['cat_id'] ]['acat_maxlist']);

        // always do full top article listing because of paginating
        $temp_topcount  = $max_articles+1;

        if(isset($_getVar['listpage'])) {
            $page_current = intval($_getVar['listpage']);
            if($page_current < 1) {
                $page_current = 1;
            } elseif($page_current > $max_pages) {
                $page_current = $max_pages;
            }
        } else {
            $page_current = 1;
        }

        $page_next = $page_current;
        $page_prev = $page_current;
        if($page_current < $max_pages) {
            $page_next = $page_current + 1;
        }
        if($page_current > 1) {
            $page_prev = $page_current - 1;
        }

        // setting pagination navi

        $page_article_max = $content['struct'][ $content['cat_id'] ]['acat_maxlist'] * $page_current;
        $page_article_at  = $content['struct'][ $content['cat_id'] ]['acat_maxlist'] * ($page_current - 1);
        $page_article_at  = $page_article_at + 1;
        if($page_article_max > $max_articles) $page_article_max = $max_articles;

        $paginate_navi = str_replace('#####',   $max_articles,          $paginate_navi);
        $paginate_navi = str_replace('####',    $page_article_max,      $paginate_navi);
        $paginate_navi = str_replace('###',     $page_article_at,       $paginate_navi);
        $paginate_navi = str_replace('##',      $max_pages,             $paginate_navi);
        $paginate_navi = str_replace('#',       $page_current,          $paginate_navi);

        $GLOBALS['paginate_temp'] = array('next' => '', 'prev' => '', 'navi' => '');

        $paginate_navi = preg_replace_callback('/\{NEXT:(.*?)\}/', 'get_PaginateNext', $paginate_navi);
        $paginate_navi = preg_replace_callback('/\{PREV:(.*?)\}/', 'get_PaginatePrevious', $paginate_navi);
        $paginate_navi = preg_replace_callback('/\{NAVI:(.*?)\}/', 'get_PaginateNavigate', $paginate_navi);

        // next page link
        $page_next_link = '<a';
        if($GLOBALS['paginate_temp']['next'] && $page_current < $max_pages) {
            $_getVar['listpage'] = $page_next;
            $page_next_link .= ' href="' . rel_url( array('listpage'=>$page_next) ) . '"';
            if ($template_default['classes']['cp-paginate-link']) {
                $page_next_link .= ' class="' . $template_default['classes']['cp-paginate-link'] . '"';
            }
        } else {
            if ($template_default['attributes']['cp-paginate']['href-disabled']) {
                $page_next_link .= ' href="' . $template_default['attributes']['cp-paginate']['href-disabled'] . '" data-disabled="true" tabindex="-1" aria-disabled="true"';
            }
            if ($template_default['classes']['cp-paginate-link-disabled']) {
                $page_next_link .= ' class="' . $template_default['classes']['cp-paginate-link-disabled'] . '"';
            }
        }
        $page_next_link .= '>' . $GLOBALS['paginate_temp']['next'] . '</a>';

        // previous page link
        $page_prev_link = '<a';
        if($GLOBALS['paginate_temp']['prev'] && $page_current > 1) {
            $_getVar['listpage'] = $page_prev;
            $page_prev_link .= ' href="' . rel_url( array('listpage'=>$page_prev) ) . '"';
            if ($template_default['classes']['cp-paginate-link']) {
                $page_prev_link .= ' class="' . $template_default['classes']['cp-paginate-link'] . '"';
            }
        } else {
            if ($template_default['attributes']['cp-paginate']['href-disabled']) {
                $page_prev_link .= ' href="' . $template_default['attributes']['cp-paginate']['href-disabled'] . '" data-disabled="true" tabindex="-1" aria-disabled="true"';
            }
            if ($template_default['classes']['cp-paginate-link-disabled']) {
                $page_prev_link .= ' class="' . $template_default['classes']['cp-paginate-link-disabled'] . '"';
            }
        }
        $page_prev_link .= '>' . $GLOBALS['paginate_temp']['prev'] . '</a>';

        // set listpage value to current page

        $paginate_navi = str_replace('{NEXT}', $page_next_link, $paginate_navi);
        $paginate_navi = str_replace('{PREV}', $page_prev_link, $paginate_navi);

        // temporary unset GET listpage setting
        unset($_getVar['listpage']);

        if($GLOBALS['paginate_temp']['navi']) {

            $navi = explode(',', $GLOBALS['paginate_temp']['navi'], 2);
            $navi[0] = trim($navi[0]);

            $navi[1]        = empty($navi[1])    ? array(0 => ' ') : explode('|', $navi[1]);
            $navi['spacer'] = empty($navi[1][0]) ? ' ' : $navi[1][0]; //spacer
            $navi['prefix'] = empty($navi[1][1]) ? ''  : $navi[1][1]; //prefix
            $navi['suffix'] = empty($navi[1][2]) ? ''  : $navi[1][2]; //suffix

            $navi['navi']   = $navi['prefix'];
            $navi['link_class'] = $template_default['classes']['search-paginate-link'] ? ' class="' . $template_default['classes']['search-paginate-link'] .'"' : '';
            $navi['link_active_class'] = $template_default['classes']['search-paginate-link-active'] ? ' class="' . $template_default['classes']['search-paginate-link-active'] .'"' : '';

            if($navi[0] == '123') {

                for($i = 1; $i <= $max_pages; $i++) {

                    if($i > 1) {
                        $navi['navi'] .= $navi['spacer'];
                    }

                    $navi['navi'] .= '<a href="' . rel_url( array('listpage' => $i) ) . '"';
                    $navi['navi'] .= ($i === $page_current) ? $navi['link_active_class'] : $navi['link_class'];
                    $navi['navi'] .= '>' . $i . '</a>';

                }

            } elseif($navi[0] == '1-3') {

                for($i = 0; $i < $max_pages; $i++) {

                    $i_start    = $i * $content['struct'][ $content['cat_id'] ]['acat_maxlist'] + 1;
                    $i_end      = $i_start - 1 + $content['struct'][ $content['cat_id'] ]['acat_maxlist'];
                    if($i_end > $max_articles) {
                        $i_end = $max_articles;
                    }

                    if($i > 0) {
                        $navi['navi'] .= $navi['spacer'];
                    }
                    $i_entry    = $i_start.'&ndash;'.$i_end;
                    $i_page     = $i+1;
                    $navi['navi'] .= '<a href="' . rel_url( array('listpage' => $i_page) ) . '"';
                    $navi['navi'] .= ($i_page === $page_current) ? $navi['link_active_class'] : $navi['link_class'];
                    $navi['navi'] .= '>' . $i_entry . '</a>';

                }

            }

            $navi['navi'] .= $navi['suffix'];

            // replace navi
            $paginate_navi = str_replace('{NAVI}', $navi['navi'], $paginate_navi);

        }

        // reset GET listpage setting
        $_getVar['listpage'] = $page_current;

        unset($GLOBALS['paginate_temp']);

    } else {
        $paginate       = false;
        $paginate_navi  = '';
    }


    $tmpllist       = array(); //temporary array for storing templates to minimize load
    $temp_counter   = 0;
    $space_counter  = 0;

    $listing        = $template_default["space_top"]; //start with space at top

    if(isset($paginate_show['top'])) {
        $listing       .= str_replace('{POS}', 'top', $paginate_navi);
    }

    foreach($content["articles"] as $article) {

        if($paginate && $content['struct'][ $content['cat_id'] ]['acat_maxlist']) {
            // get page number based on current article counter
            $page_article = ceil( ($temp_counter + 1) / $content['struct'][ $content['cat_id'] ]['acat_maxlist']);
            if($page_article > $page_current) {
                //stop listing
                break;
            } elseif($page_article != $page_current) {
                //no listing - goto next article
                $temp_counter++;
                continue;
            }
        }

        $link_data      = get_article_morelink($article);
        $article_link   = $link_data[0];

        //add available keywords to page wide keyword field
        $content['all_keywords'] .= $article["article_keyword"].',';

        if($temp_counter < $temp_topcount) {
            // as long as the counter is lower than the default "top_count" value
            // show the complete article summary listing

            $article["article_image"] = setArticleSummaryImageData($article["article_image"]);

            if($template) {
                $article["article_image"]['tmpllist'] = $template;
            }

            // build image/image link
            $article["article_image"]["poplink"]    = '';
            $thumb_image                            = false;
            $thumb_img                              = '';

            $img_thumb_name     = '';
            $img_thumb_rel      = '';
            $img_thumb_abs      = '';
            $img_thumb_width    = 0;
            $img_thumb_height   = 0;
            $img_thumb_ext      = 'jpg';

            $img_zoom_name      = '';
            $img_zoom_rel       = '';
            $img_zoom_abs       = '';
            $img_zoom_width     = 0;
            $img_zoom_height    = 0;

            if(empty($article["article_image"]["list_caption"])) {
                $article["article_image"]["list_caption"] = '';
            }
            if(empty($article["article_image"]["list_id"])) {
                $article["article_image"]["list_id"] = 0;
            }
            $caption = getImageCaption(array('caption' => $article["article_image"]["list_caption"], 'file' => $article["article_image"]["list_id"]));

            $article["article_image"]["list_caption"]   = $caption[0]; // caption text
            $article["article_image"]["copyright"]      = $caption[4]; // copyright information
            $article["article_image"]["list_alt"]       = $caption[1]; // alt text
            $article["article_image"]["list_title"]     = $caption[3]; // title text

            if(!empty($article["article_image"]["list_hash"])) {

                $thumb_image = get_cached_image(array(
                    "target_ext"    =>  $article["article_image"]['list_ext'],
                    "image_name"    =>  $article["article_image"]['list_hash'] . '.' . $article["article_image"]['list_ext'],
                    "max_width"     =>  $article["article_image"]['list_width'],
                    "max_height"    =>  $article["article_image"]['list_height'],
                    "thumb_name"    =>  md5($article["article_image"]['list_hash'].$article["article_image"]['list_width'].$article["article_image"]['list_height'].$GLOBALS['phpwcms']["sharpen_level"].$GLOBALS['phpwcms']['colorspace'])
                ));

                if($thumb_image != false) {

                    $img_thumb_name     = $thumb_image[0];
                    $img_thumb_rel      = $thumb_image['src'];
                    $img_thumb_abs      = PHPWCMS_URL.$thumb_image['src'];
                    $img_thumb_width    = $thumb_image[1];
                    $img_thumb_height   = $thumb_image[2];
                    $img_thumb_ext      = $article["article_image"]['list_ext'];

                    $caption[3] = empty($caption[3]) ? '' : ' title="'.html_specialchars($caption[3]).'"';
                    $caption[1] = html_specialchars($caption[1]);

                    $thumb_img = '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" '.$thumb_image[3].' alt="'.$caption[1].'"'.$caption[3].' class="'.$GLOBALS['template_default']['classes']['image-article-list'].'"'.PHPWCMS_LAZY_LOADING.HTML_TAG_CLOSE;

                    if($article["article_image"]["list_zoom"]) {

                        $zoominfo = get_cached_image(array(
                            "target_ext"    =>  $article["article_image"]['list_ext'],
                            "image_name"    =>  $article["article_image"]['list_hash'] . '.' . $article["article_image"]['list_ext'],
                            "max_width"     =>  $GLOBALS['phpwcms']["img_prev_width"],
                            "max_height"    =>  $GLOBALS['phpwcms']["img_prev_height"],
                            "thumb_name"    =>  md5($article["article_image"]['list_hash'].$GLOBALS['phpwcms']["img_prev_width"].$GLOBALS['phpwcms']["img_prev_height"].$GLOBALS['phpwcms']["sharpen_level"].$GLOBALS['phpwcms']['colorspace'])
                        ));

                        if($zoominfo != false) {

                            $img_zoom_name      = $zoominfo[0];
                            $img_zoom_rel       = $zoominfo['src'];
                            $img_zoom_abs       = PHPWCMS_URL.$zoominfo['src'];
                            $img_zoom_width     = $zoominfo[1];
                            $img_zoom_height    = $zoominfo[2];

                            $article["article_image"]["poplink"] = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo['src'], $zoominfo[3], $article["article_image"]['list_name']);

                            if(!empty($caption[2][0])) {
                                $open_link = $caption[2][0];
                                $return_false = '';
                            } else {
                                $open_link = $article["article_image"]["poplink"];
                                $return_false = 'return false;';
                            }

                            if(empty($article["article_image"]["list_lightbox"]) && !empty($caption[2][0])) {

                                initFrontendJS();

                                $article["article_image"]["poplink"]  = '<a href="'.$article["article_image"]["poplink"].'" ';
                                $article["article_image"]["poplink"] .= 'onclick="checkClickZoom();clickZoom(\''.$open_link;
                                $article["article_image"]["poplink"] .= "','previewpic','width=".$zoominfo[1];
                                $article["article_image"]["poplink"] .= ",height=".$zoominfo[2]."');".$return_false;
                                $article["article_image"]["poplink"] .= '"'.$caption[2][1].' class="'.$GLOBALS['template_default']['classes']['image-zoom'].'">';
                            } else {
                                // lightbox
                                initSlimbox();

                                $article["article_image"]["poplink"]  = '<a href="'.$zoominfo['src'].'" rel="lightbox" '.get_attr_data_gallery('', '', ' ');
                                if($article["article_image"]["list_caption"]) {
                                    $article["article_image"]["poplink"] .= 'title="'.parseLightboxCaption($article["article_image"]["list_caption"]).'" ';
                                }
                                $article["article_image"]["poplink"] .= 'class="'.$GLOBALS['template_default']['classes']['image-lightbox'].'">';
                            }

                            $article["article_image"]["poplink"] .= $thumb_img.'</a>';
                        }
                    }

                    unset($caption);
                }
            } else {

                $article["article_image"]["list_id"]    = 0;
                $article["article_image"]["list_hash"]  = '';

            }

            // set default template
            if(empty($article["article_image"]['tmpllist']) || $article["article_image"]['tmpllist'] == 'default') {

                $article["article_image"]['tmpllist'] = 'default';

                if(empty($tmpllist['default'])) {

                    $tmpllist['default'] = file_get_contents(PHPWCMS_TEMPLATE.'inc_default/article_summary_list.tmpl');

                }

            }

            // try to read the template files
            // 1. try to check if template was read
            if(!isset($tmpllist[ $article["article_image"]['tmpllist'] ])) {
                $tmpllist[ $article["article_image"]['tmpllist'] ] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/list/'.$article["article_image"]['tmpllist']);
            }
            if($tmpllist[ $article["article_image"]['tmpllist'] ]) {

                // set frontend edit link
                if(FE_EDIT_LINK && ($_SESSION["wcs_user_admin"] || $_SESSION["wcs_user_id"] == $article["article_uid"])) {
                    $tmpl = getFrontendEditLink('summary', $article['article_id']);
                } else {
                    $tmpl = '';
                }

                //rendering
                $tmpl .= $tmpllist[ $article["article_image"]['tmpllist'] ];

                $tmpl = render_cnt_template($tmpl, 'TITLE', empty($article['article_notitle']) ? html_specialchars($article["article_title"]) : '' );
                $tmpl = render_cnt_template($tmpl, 'SUB', html_specialchars($article["article_subtitle"]));
                $tmpl = str_replace('{CATEGORYID}', $article['article_cid'], $tmpl);

                // replace thumbnail and zoom image information
                $tmpl = str_replace(
                    array(
                        '{THUMB_NAME}',
                        '{THUMB_REL}',
                        '{THUMB_ABS}',
                        '{THUMB_WIDTH}',
                        '{THUMB_HEIGHT}',
                        '{IMAGE_NAME}',
                        '{IMAGE_REL}',
                        '{IMAGE_ABS}',
                        '{IMAGE_WIDTH}',
                        '{IMAGE_HEIGHT}',
                        '{IMAGE_ID}',
                        '{IMAGE_HASH}',
                        '{IMAGE_EXT}'
                    ),
                    array(
                        $img_thumb_name,
                        $img_thumb_rel,
                        $img_thumb_abs,
                        $img_thumb_width,
                        $img_thumb_height,
                        $img_zoom_name,
                        $img_zoom_rel,
                        $img_zoom_abs,
                        $img_zoom_width,
                        $img_zoom_height,
                        $article["article_image"]["list_id"],
                        $article["article_image"]["list_hash"],
                        $img_thumb_ext
                    ),
                    $tmpl
                );

                if( preg_match('/\{SUMMARY:(\d+)\}/', $tmpl, $matches) ) {
                    if(empty($article['article_image']['list_maxwords'])) {
                        $article['article_image']['list_maxwords'] = intval($matches[1]);
                    }
                    $tmpl = preg_replace('/\{SUMMARY:\d+\}/', '{SUMMARY}', $tmpl);
                }

                if(strpos($article["article_summary"], '-//-')) {
                    $article["article_summary"] = explode('-//-', $article["article_summary"]);
                    $article['article_image']['list_maxwords_temp'] = count(preg_split("/[\s,]+/", $article["article_summary"][0], -1, PREG_SPLIT_NO_EMPTY));
                    if(empty($article['article_image']['list_maxwords']) || $article['article_image']['list_maxwords_temp'] < $article['article_image']['list_maxwords']) {
                        $article['article_image']['list_maxwords'] = $article['article_image']['list_maxwords_temp'];
                        $article["article_summary"] = trim($article["article_summary"][0]);
                    } else {
                        $article["article_summary"] = implode(' ', $article["article_summary"]);
                    }
                }
                $tmpl = render_cnt_template(
                    $tmpl,
                    'SUMMARY',
                    empty($article['article_image']['list_maxwords']) ? $article["article_summary"] : getCleanSubString(
                        $article["article_summary"],
                        abs($article['article_image']['list_maxwords']),
                        $template_default['ellipse_sign'], $article['article_image']['list_maxwords'] < 0 ? 'char' : 'word', true
                    )
                );

                // Render SYSTEM
                if(strpos($tmpl, '[SYSTEM]') !== false) {
                    // Search for all system related content parts
                    $sql_cnt  = 'SELECT * FROM ' . DB_PREPEND . 'phpwcms_articlecontent WHERE acontent_aid=' . $article["article_id"] . ' ';
                    $sql_cnt .= "AND acontent_livedate < NOW() AND (acontent_killdate='0000-00-00 00:00:00' OR acontent_killdate > NOW()) ";
                    $sql_cnt .= "AND acontent_visible=1 AND acontent_trash=0 AND acontent_block='SYSTEM' AND acontent_tid IN (1, 3) "; // 1 = article list, 3 = article detail OR list
                    $sql_cnt .= 'AND acontent_granted' . (FEUSER_LOGIN_STATUS ? '!=2' : '=0') . ' ';
                    $sql_cnt .= "ORDER BY acontent_sorting, acontent_id";
                    $tmpl = render_cnt_template($tmpl, 'SYSTEM', showSelectedContent('CPC', $sql_cnt, true));
                } else {
                    $tmpl = render_cnt_template($tmpl, 'SYSTEM', '');
                }

                if (!empty($article['article_meta'])) {
                    if (is_string($article['article_meta'])) {
                        $article['article_meta'] = json_decode($article['article_meta'], true);
                    }
                    $article['article_meta'] = is_array($article['article_meta']) ? array_merge(get_default_article_meta(), $article['article_meta']) : get_default_article_meta();
                } else {
                    $article['article_meta'] = get_default_article_meta();
                }

                $article['article_meta']['class'] = trim(get_css_keywords($article['article_keyword']) . ' ' . $article['article_meta']['class']);

                // article class based on keyword *CSS-classname*
                $tmpl = render_cnt_template($tmpl, 'CLASS', $article['article_meta']['class']);
                $tmpl = render_cnt_template($tmpl, 'IMAGE', $thumb_img);
                $tmpl = render_cnt_template($tmpl, 'ZOOMIMAGE', $article["article_image"]["poplink"]);
                $tmpl = render_cnt_template($tmpl, 'CAPTION_SUPPRESS', empty($article["article_image"]["list_caption_suppress"]) ? '' : ' ');
                $tmpl = render_cnt_template($tmpl, 'CAPTION', nl2br(html_specialchars($article["article_image"]["list_caption"])));
                $tmpl = render_cnt_template($tmpl, 'ALT', html_specialchars($article["article_image"]["list_alt"]));
                $tmpl = render_cnt_template($tmpl, 'IMAGE_TITLE', html_specialchars($article["article_image"]["list_title"]));
                $tmpl = render_cnt_template($tmpl, 'COPYRIGHT', html_specialchars($article["article_image"]["copyright"]));
                $tmpl = render_cnt_template($tmpl, 'ARTICLELINK', $article["article_morelink"] ? $article_link : '');
                $tmpl = render_cnt_template($tmpl, 'EDITOR', html_specialchars($article["article_username"]));
                $tmpl = render_cnt_template($tmpl, 'ARTICLEID', $article["article_id"]);
                $tmpl = render_cnt_template($tmpl, 'MORE', $article["article_morelink"] ? $template_default["top_readmore_link"] : '');
                $tmpl = render_cnt_template($tmpl, 'TARGET', ($article["article_morelink"] && $link_data[1]) ? ' target="'.$link_data[1].'"' : '');
                $tmpl = render_cnt_template($tmpl, 'BEFORE', '<!--before-->');
                $tmpl = render_cnt_template($tmpl, 'AFTER', '<!--after-->');
                $tmpl = render_cnt_date($tmpl, $article["article_date"], $article["article_livedate"], $article["article_killdate"] );
                $tmpl = render_cnt_template($tmpl, 'SPACE', $space_counter ? '<!--space-->' : '');

                $listing .= $tmpl;

                $article["article_image"]['tmpllist'] = 1;
            } else {
                $article["article_image"]['tmpllist'] = 0;
            }

        } else {
            // if "top_count" value is equal or larger
            // show only the article headline listing
            if($temp_counter && $temp_counter == $temp_topcount) {
                $listing .= $template_default["space_aftertop_text"];
            } elseif ($temp_counter) {
                $listing .= $template_default["space_between_list"];
            }

            $listing .= $template_default["list_headline_before"];

            // set frontend edit link
            if(FE_EDIT_LINK && ($_SESSION["wcs_user_admin"] || $_SESSION["wcs_user_id"] == $article["article_uid"])) {
                $listing .= getFrontendEditLink('article', $article['article_id']);
                $listing .= getFrontendEditLink('summary', $article['article_id']);
            }

            $listing .= '<a href="'.$article_link.'" class="'.$GLOBALS['template_default']['classes']['link-article-listing'].'">';
            $listing .= $template_default["list_startimage"];
            $listing .= html_specialchars($article["article_title"]);
            $listing .= '</a>'.$template_default["list_headline_after"];

        }
        $temp_counter++;
        $space_counter++;
    }

    if(isset($paginate_show['bottom'])) {
        $listing .= str_replace('{POS}', 'bottom', $paginate_navi);
    }
    if(!empty($paginate_show['rt'])) {
        $content['globalRT'][ $paginate_show['rt'] ] = $paginate_navi;
    }

    // restore original articles
    if(isset($_old_articles)) {
        $content["articles"] = $_old_articles;
    }

    $listing .= $template_default["space_bottom"]; //ends with space at bottom
    return $listing;
}

function get_html_part($value, $class="", $link="", $span_or_div=1) {
    // returns a content part for html output like
    // <span class="xxx">html</span>
    if($value) {
        $html_tag  = ($span_or_div) ? 'span' : 'div';
        $html_part = ($link) ? '<a href="'.$link.'">'.html_specialchars($value).'</a>' : html_specialchars($value);
        if($class) {
            $html_part = '<'.$html_tag.' class="'.$class.'">'.$html_part;
        } else {
            $html_part = '<'.$html_tag.'>'.$html_part;
        }
        return $html_part.'</'.$html_tag.'>';
    } else {
        return '';
    }
}

function span_class($value, $class) {
    return !empty($class) ? '<span class="'.$class.'">'.$value.'</span>' : $value;
}

function div_class($value, $class, $tag='div') {
    return !empty($class) ? '<'.$tag.' class="'.$class.'">'.$value.'</'.$tag.'>' : $value;
}

function get_class_attrib($class) {
    return !empty($class) ? ' class="'.$class.'"' : '';
}

function html_parser($string) {
    // parse the $string and replace all possible
    // values with $replace

    if(trim($string) == '') {
        return $string;
    }

    $string = render_bbcode_basics($string, '');

    $search     = array();
    $replace    = array();

    // page TOP link
    if(strpos($string, '[TOP]') !== false) {
        $search[0]  = '/\[TOP\](.*?)\[\/TOP\]/s';
        $replace[0] = '<a href="'.rel_url().'#top" class="'.$GLOBALS['template_default']['classes']['link-top'].'">$1</a>';
    }

    // external Link (string)
    $search[1]      = '/\[EXT (.*?)\](.*?)\[\/EXT\]/s';
    $replace[1]     = '<a href="$1" target="_blank" class="'.$GLOBALS['template_default']['classes']['link-external'].'">$2</a>';

    // internal Link (string)
    $search[2]      = '/\[INT (.*?)\](.*?)\[\/INT\]/s';
    $replace[2]     = '<a href="$1" class="'.$GLOBALS['template_default']['classes']['link-internal'].'">$2</a>';

    // insert non db image right
    $search[3]      = '/\{SPACER:(\d+)x(\d+)\}/';
    $replace[3]     = '<span class="'.$GLOBALS['template_default']['classes']['spaceholder'].'" style="width:$1px;height:$2px;display:inline-block;"></span>';

    // RSS feed link
    $search[4]      = '/\[RSS (.*?)\](.*?)\[\/RSS\]/s';
    $replace[4]     = '<a href="feeds.php?feed=$1" target="_blank" class="'.$GLOBALS['template_default']['classes']['link-rss'].'">$2</a>';

    // back Link (string)
    $search[5]      = '/\[BACK\](.*?)\[\/BACK\]/i';
    $replace[5]     = '<a href="#" target="_top" title="go back" onclick="history.back();return false;" class="'.$GLOBALS['template_default']['classes']['link-back'].'">$1</a>';

    // span or div style
    $search[6]      = '/\[(span|div)_style:(.*?)\](.*?)\[\/style\]/s';
    $replace[6]     = '<$1 style="$2">$3</$1>';

    // span or div class
    $search[7]      = '/\[(span|div)_class:(.*?)\](.*?)\[\/class\]/s';
    $replace[7]     = '<$1 class="$2">$3</$1>';

    $search[8]      = '/\[acronym (.*?)\](.*?)\[\/acronym\]/is';
    $replace[8]     = '<abbr title="$1">$2</abbr>';

    // this parses an E-Mail Link without subject (by Florian, 21-11-2003)
    $search[9]      = '/\[E{0,1}MAIL (.*?)\](.*?)\[\/E{0,1}MAIL\]/is';
    $replace[9]     = '<a href="mailto:$1" class="'.$GLOBALS['template_default']['classes']['link-email'].'">$2</a>';

    $search[10]     = '/\[E{0,1}MAIL\](.*?)\[\/E{0,1}MAIL\]/is';
    $replace[10]    = '<a href="mailto:$1" class="'.$GLOBALS['template_default']['classes']['link-email'].'">$1</a>';

    // this tags out a Mailaddress with an predifined subject (by Florian, 21-11-2003)
    $search[11]     = '/\[MAILSUB (.*?) (.*?)\](.*?)\[\/MAILSUB\]/is';
    $replace[11]    = '<a href="mailto:$1?subject=$2" class="'.$GLOBALS['template_default']['classes']['link-email'].'">$3</a>';

    $search[12]     = '/\[blockquote (.*?)\](.*?)\[\/blockquote\]/is';
    $replace[12]    = '<blockquote cite="$1">$2</blockquote>';

    // create "make bookmark" javascript code
    $search[13]     = '/\[BOOKMARK\s{0,}(.*)\](.*?)\[\/BOOKMARK\]/is';
    $replace[13]    = '<a href="#" onclick="return BookMark_Page(\'$1\');" title="$1" class="'.$GLOBALS['template_default']['classes']['link-bookmark'].'">$2</a>';

    // ABBreviation
    $search[14]     = '/\[abbr (.*?)\](.*?)\[\/abbr\]/is';
    $replace[14]    = '<abbr title="$1">$2</abbr>';

    $search[15]     = '/\[dfn (.*?)\](.*?)\[\/dfn\]/is';
    $replace[15]    = '<dfn title="$1">$2</dfn>';

    $string = preg_replace($search, $replace, $string);
    $string = parse_cnt_urlencode($string);

    // internal Link to article ID or alias
    $string = preg_replace_callback('/\[ID (.*?)\](.*?)\[\/ID\]/s', 'html_parse_idlink', $string);

    // anchor or page link
    $string = preg_replace_callback('/\{[aA]:(.+?)\}/', 'get_link_anchor', $string);

    // images
    $string = preg_replace_callback('/\[img=(\d+)(.*?){0,1}\](.*?)\[\/img\]/i', 'parse_images', $string);
    $string = preg_replace_callback('/\[img=(\d+)(.*?){0,1}\]/i', 'parse_images', $string);

    // downloads
    $string = preg_replace_callback('/\[download=([0-9, ]+?)( template=.*?){0,1}\/\]/i', 'parse_downloads', $string);
    $string = preg_replace_callback('/\[download=([0-9, ]+?)( template=.*?){0,1}\](.*?)\[\/download\]/is', 'parse_downloads', $string);

    $string = str_replace(
        array(
            '&#92;&#039;',
            '&amp;quot;',
            '-//-',
            ' class=""',
            '<br>',
            '{SITE}',
            '{TEMPLATE}'
        ),
        array(
            '&#039;',
            '&quot;',
            ' ',
            '',
            '<br />',
            PHPWCMS_URL,
            TEMPLATE_PATH
        ),
        $string
    );
    return $string;
}

function html_parse_idlink($matches) {
    $matches[1] = explode(' ', $matches[1], 2);
    $target = empty($matches[1][1]) ? '' : ' target="' . $matches[1][1] . '"';
    $matches[1] = $matches[1][0];
    if(strpos($matches[1], '#') !== false) {
        list($matches[1], $anchor) = explode('#', $matches[1], 2);
        if($anchor) {
            $anchor = '#'.$anchor;
        }
    } else {
        $anchor = '';
    }
    if(is_intval($matches[1], false)) {
        $matches[1] = 'aid='.$matches[1];
    }
    $replace = '<a href="' . rel_url(array(), array(), $matches[1]) . $anchor . '"';
    if(!empty($GLOBALS['template_default']['classes']['link-internal'])) {
        $replace .= ' class="'.$GLOBALS['template_default']['classes']['link-internal'].'"';
    }
    $replace .= $target . '>' . $matches[2] . '</a>';
    return $replace;
}

function include_ext_php($inc_file, $t=0) {
    // includes an external PHP script file and returns
    // the result as string from buffered include content
    if(is_array($inc_file) && !empty($inc_file[1])) {
        $inc_file = $inc_file[1];
    }

    //check if this is a local file
    if(!$t && preg_match('~^(?:ht)tps?://~i', $inc_file, $match) && is_file($inc_file)) {

        $this_path = rtrim(str_replace("\\", '/', dirname(realpath($inc_file))), '/');
        $root_path = rtrim(str_replace("\\", '/', realpath(PHPWCMS_ROOT)), '/');

        if(strpos($this_path, $root_path) === 0) {
            $t = 1;
        }

    } elseif(!$t && !empty($GLOBALS['phpwcms']['allow_remote_URL'])) {
        //if remote URL is allowed in conf.inc.php
        $t = 1;
    }

    if(!$t) {
        return '';
    } elseif(!empty($match[0]) && substr(strtolower($match[0]), 0, 4) === 'http') {
        if(ini_get('allow_url_fopen')) {
            $result = file_get_contents($inc_file);
            if($result !== false) {
                return $result;
            }
        }
        if(!ini_get('allow_url_include')) {
            return '<!-- @@allow_url_include is disabled in your PHP.ini@@ - @@remote website cannot be loaded@@ -->';
        }
    }

    ob_start();
    @include $inc_file;
    return ob_get_clean();
}

// callback wrapper functions
function international_date_format_callback($matches) {
    $matches[1] = trim($matches[1]);
    if($matches[1] && strpos($matches[1], ' set=') !== false) {
        $set = explode(' set=', $matches[1]);
        $matches[1] = trim($set[0]);
        if(!empty($set[1])) {
            $set[1] = trim($set[1]);
            $GLOBALS['phpwcms']['callback'] = is_intval($set[1]) ? intval($set[1]) : phpwcms_strtotime($set[1], NULL, now());
        }
    }
    return international_date_format($matches[2], $matches[1], $GLOBALS['phpwcms']['callback']);
}
function date_callback($matches) {
    $matches[1] = trim($matches[1]);
    if($matches[1] && strpos($matches[1], ' set=') !== false) {
        $set = explode(' set=', $matches[1]);
        $matches[1] = trim($set[0]);
        if(!empty($set[1])) {
            $set[1] = trim($set[1]);
            $GLOBALS['phpwcms']['callback'] = is_intval($set[1]) ? intval($set[1]) : phpwcms_strtotime($set[1], NULL, now());
        }
    }
    if($GLOBALS['phpwcms']['DOCTYPE_LANG'] !== 'en' && preg_match('/[MFDl]/', $matches[1])) {
        return international_date_format($GLOBALS['phpwcms']['default_lang'], $matches[1], $GLOBALS['phpwcms']['callback']);
    }
    return date($matches[1], $GLOBALS['phpwcms']['callback']);
}

function international_date_format($language='', $format="Y/m/d", $date_now=0) {
    // formats the given date
    // for the specific language
    // use the normal date format options

    if(!$format) {
        $format = "Y/m/d";
    }
    if(!intval($date_now)) {
        $date_now = now();
    }

    $language = strtolower(trim($language));

    if($language === 'en') {

        return date($format, $date_now);

    } elseif(empty($language) || $language=='@@') {

        if(empty($GLOBALS['phpwcms']['DOCTYPE_LANG'])) {

            return date($format, $date_now);

        }
        $language = $GLOBALS['phpwcms']['DOCTYPE_LANG'];

    }

    $lang_include = PHPWCMS_ROOT.'/include/inc_lang/date/'.substr($language, 0, 2).'.date.lang.php';
    if(is_file($lang_include)) {

        include $lang_include;
        $date_format_function = array (
            "a" => 1, "A" => 1, "B" => 1, "d" => 1, "g" => 1, "G" => 1,
            "h" => 1, "H" => 1, "i" => 1, "I" => 1, "j" => 1, "m" => 1,
            "n" => 1, "s" => 1, "t" => 1, "T" => 1, "U" => 1, "w" => 1,
            "Y" => 1, "y" => 1, "z" => 1, "Z" => 1,
            "D" => 0, "F" => 0, "l" => 0, "M" => 0, "S" => 0
        );

        $str_length = strlen($format);
        $date = '';
        for($i = 0; $i < $str_length; $i++) {
            $date_format[$i] = substr($format, $i, 1);
        }
        foreach($date_format as $key => $value) {
            if(isset($date_format_function[$value])) {
                if($date_format_function[$value]) {
                    $date .= date($value, $date_now);
                } else{
                    switch($value) {
                        case "D":   $date .= $weekday_short[ intval(date("w", $date_now)) ]; break; //short weekday name
                        case "l":   $date .= $weekday_long[ intval(date("w", $date_now)) ]; break; //long weekday name
                        case "F":   $date .= $month_long[ intval(date("n", $date_now)) ]; break; //long month name
                        case "M":   $date .= $month_short[ intval(date("n", $date_now)) ]; break; //long month name
                        case "S":   $date .= ""; break;
                    }
                }
            } else {
                $date .= $value;
            }
        }

        return $date;
    }

    return date($format, $date_now);
}

function return_struct_level($struct, $struct_id) {
    // walk through the given struct level and returns an array with available levels
    $level_entry = array();
    if(is_array($struct)) {
        foreach($struct as $key => $value) {
            if( _getStructureLevelDisplayStatus($key, $struct_id) ) {
                $level_entry[$key] = $value;
            }
        }
    }
    return $level_entry;
}

function get_active_categories($struct, $act_struct_id) {
    // returns an array with all active categories/structure levels

    $which_cat_array = array();
    $which_category = $act_struct_id;
    while($which_category) {
        $which_cat_array[$which_category] = 1;
        $which_category = $GLOBALS['content']["struct"][$which_category]["acat_struct"];
    }
    return $which_cat_array;
}

// REWRITE - PATCHED FOR 04/04 // jan212
function url_search($query) {
    $query_string   = '';
    $anchor         = '';
    if ( substr($query[3], 0, 3) == 'id=') {
        $file = str_replace(',', '.', substr($query[3], 3));
    } else {
        $file = str_replace(array(',', 'aid='), array('.', 'aid'.rawurlencode('=')), $query[3]);
    }
    if(strpos($file, '#') !== false) {
        preg_match('/^(.*?)(#.*?){0,1}$/', $file, $match);
        $file = $match[1];
        if(isset($match[2])) {
            $anchor = $match[2];
        }
    }
    if(strpos($file, '&') !== false) {
        preg_match('/^(.*?)(&.+?=.*?){0,1}$/', $file, $match);
        $file = $match[1];
        if(isset($match[2])) {
            $query_string = $match[2];
            if(substr($query_string, 0, 5) == '&amp;') {
                $query_string = substr($query_string, 5);
            }
            $query_string = '?' . $query_string;
        }
    }
    return $query[1].'="'.$file.PHPWCMS_REWRITE_EXT.$query_string.$anchor.'"';
}

function js_url_search($query) {
    $query_string   = '';
    $anchor         = '';
    if ( substr($query[1], 0, 3) == 'id=') {
        $file = str_replace(',', '.', substr($query[1], 3));
    } else {
        $file = str_replace(array(',', 'aid='), array('.', 'aid'.rawurlencode('=')), $query[1]);
    }
    if(strpos($file, '#') !== false) {
        preg_match('/^(.*?)(#.*?){0,1}$/', $file, $match);
        $file = $match[1];
        if(isset($match[2])) {
            $anchor = $match[2];
        }
    }
    if(strpos($file, '&') !== false) {
        preg_match('/^(.*?)(&.+?=.*?){0,1}$/', $file, $match);
        $file = $match[1];
        if(isset($match[2])) {
            $query_string = $match[2];
            if(substr($query_string, 0, 5) == '&amp;') {
                $query_string = substr($query_string, 5);
            }
            $query_string = '?' . $query_string;
        }
    }
    return "onclick=\"location.href='".$file.PHPWCMS_REWRITE_EXT.$query_string.$anchor."'";
}

function get_related_articles_callback($matches) {
    return get_related_articles((isset($matches[2]) ? $matches[2] : $GLOBALS['related_keywords']), $GLOBALS['aktion'][1], $GLOBALS['template_default']["related"], $matches[1]);
}

function get_related_articles($keywords, $current_article_id, $template_default, $max_cnt_links=0, $dbcon=null) {
    // find keyword for current article used for RELATED replacementtag
    // prepared and inspired by Magnar Stav Johanssen

    $keyword_links = '';
    $max_cnt_links = intval($max_cnt_links);

    // replace unwanted chars and convert to wanted
    $keywords = str_replace(
        array("ALLKEYWORDS",";", "'", ' ', ',,'),
        array($GLOBALS['content']['all_keywords'].',', ",", '', ',', ','),
        strtoupper($keywords)
    );

    // choose comma separated keywords
    $keywordarray = convertStringToArray($keywords);

    // check for empty keywords or keywords smaller than 3 chars
    if(is_array($keywordarray) && count($keywordarray)) {
        foreach($keywordarray as $key => $value) {

            if(substr($keywordarray[$key], 0, 1) == '-') {
                $doNotUse = substr($keywordarray[$key], 1);
                foreach($keywordarray as $key2 => $value2) {
                    if($doNotUse == $value2) {
                        unset($keywordarray[$key2]);
                        unset($keywordarray[$key]);
                    }
                }
            }

            if(isset($keywordarray[$key]) && (strlen($keywordarray[$key]) < 3 || empty($keywordarray[$key]))) {
                unset($keywordarray[$key]);
            }
        }
    }

    if(is_array($keywordarray) && count($keywordarray)) {
        $where = "";
        foreach($keywordarray as $value) {
                //build where keyword = blabla
                $where .= ($where) ? " OR " : "";
                //replace every "'" to "''" for security reasons with _dbEscape()
                $where .= "article_keyword LIKE "._dbEscape($value, true, '%', '%');
        }
        $limit = ($max_cnt_links) ? " LIMIT ".$max_cnt_links : "";
        $sql  = "SELECT article_id, article_title, article_cid, article_subtitle, article_summary, article_alias, article_redirect, article_morelink ";
        $sql .= "FROM ".DB_PREPEND."phpwcms_article WHERE article_deleted=0 AND ";
        $sql .= "article_id<>".intval($current_article_id)." AND ";
        // VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
        switch(VISIBLE_MODE) {
            case 0: $sql .= "article_aktiv=1 AND ";
                    break;
            case 1: $sql .= "(article_aktiv=1 OR article_uid=".$_SESSION["wcs_user_id"].") AND ";
                    break;
            //case 2: admin mode no additional neccessary
        }
        if(!PREVIEW_MODE) {
            $sql .= "article_begin < NOW() AND (article_end='0000-00-00 00:00:00' OR article_end > NOW()) AND ";
        }
        $sql .= '('.$where.') ';

        if(empty($template_default['sort_by'])) $template_default['sort_by'] = '';

        switch($template_default['sort_by']) {

            case 'title_asc':
                        $sql .= "ORDER BY article_title";
                        break;

            case 'title_desc':
                        $sql .= "ORDER BY article_title DESC";
                        break;

            case 'ldate_asc':
                        $sql .= "ORDER BY article_begin";
                        break;

            case 'ldate_desc':
                        $sql .= "ORDER BY article_begin DESC";
                        break;

            case 'kdate_asc':
                        $sql .= "ORDER BY article_end";
                        break;

            case 'kdate_desc':
                        $sql .= "ORDER BY article_end DESC";
                        break;

            case 'cdate_asc':
                        $sql .= "ORDER BY article_created";
                        break;

            case 'cdate_desc':
                        $sql .= "ORDER BY article_created DESC";
                        break;

            default:
                        $sql .= "ORDER BY article_tstamp DESC";
        }

        $sql .= $limit;

        // related things
        $target = $template_default["link_target"] ? ' target="'.$template_default["link_target"].'"' : '';
        $result = _dbQuery($sql);
        if(isset($result[0])) {
            foreach($result as $row) {

                if(empty($row['article_redirect'])) {
                    if(empty($row['article_morelink'])) {
                        continue;
                    }
                    $article_link = rel_url(array(), array('newsdetail'), setGetArticleAid($row)).'"'.$target;
                } else {
                    $redirect = get_redirect_link($row['article_redirect'], ' ', '');
                    $article_link = $redirect['link'].'"'.$redirect['target'];
                }

                if($template_default["link_length"] && strlen($row['article_title']) > $template_default["link_length"]) {
                    $article_title = substr($row['article_title'], 0, $template_default["link_length"]).$template_default["cut_title_add"];
                } else {
                    $article_title = $row['article_title'];
                }
                $keyword_links .= $template_default["link_before"];
                $keyword_links .= $template_default["link_symbol"];
                $keyword_links .= '<a href="' . $article_link . '>';
                $keyword_links .= html_specialchars($article_title);
                $keyword_links .= '</a>' . $template_default["link_after"];
            }
        }
    }

    //enclose whole
    return empty($keyword_links) ? '' : $template_default["before"].$keyword_links.$template_default["after"];
}

function get_new_articles_callback($matches) {
    if(!isset($matches[2])) {
        $matches[2] = '';
    }
    return get_new_articles($GLOBALS['template_default']['news'], $matches[1], $matches[2]);
}

function get_new_articles($template_default, $max_cnt_links=0, $cat='', $dbcon=null) {
    // find all new articles

    $max_cnt_links = intval($max_cnt_links);
    $limit = empty($max_cnt_links) ?  '' : ' LIMIT '.$max_cnt_links;
    $cat = trim($cat);
    $cat = is_intval($cat, false) ? 'article_cid='.intval($cat).' AND ' : '';

    $sql = 'SELECT article_id, article_title, article_cid, article_alias, article_redirect, article_morelink, ';

    switch( (empty($template_default["sort_by"]) ? '' : strtolower($template_default["sort_by"])) ) {

        case 'cdate':   //use real creation date
                        $sql .= "article_created AS article_date ";
                        $sorting = 'article_created';
                        break;

        case 'ldate':   //use live/start date
                        $sql .= "UNIX_TIMESTAMP(article_begin) AS article_date ";
                        $sorting = 'article_begin';
                        break;

        case 'kdate':   //use kill/end date
                        $sql .= "UNIX_TIMESTAMP(article_end) AS article_date ";
                        $sorting = 'article_end';
                        break;

        default:        $sql .= "UNIX_TIMESTAMP(article_tstamp) AS article_date ";
                        $sorting = 'article_tstamp';
    }

    $sql .= "FROM ".DB_PREPEND."phpwcms_article WHERE ".$cat;
    // VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
    switch(VISIBLE_MODE) {
        case 0: $sql .= "article_aktiv=1 AND ";
                break;
        case 1: $sql .= "(article_aktiv=1 OR article_uid=".$_SESSION["wcs_user_id"].") AND ";
                break;
        //case 2: admin mode no additional neccessary
    }
    $sql .= 'article_deleted=0 ';
    if(!PREVIEW_MODE) {
        $sql .= "AND article_begin < NOW() AND (article_end='0000-00-00 00:00:00' OR article_end > NOW()) ";
    }
    $sql .= "ORDER BY ".$sorting." DESC".$limit;

    // new articles list
    $new_links = "";
    $target = ($template_default["link_target"]) ? ' target="'.$template_default["link_target"].'"' : '';

    $result = _dbQuery($sql);
    $count  = 0;

    foreach($result as $row) {

            if(empty($row['article_redirect'])) {
                if(empty($row['article_morelink'])) {
                    continue;
                }
                $article_link = rel_url(array(), array('newsdetail'), setGetArticleAid($row)).'"'.$target;
            } else {
                $redirect = get_redirect_link($row['article_redirect'], ' ', '');
                $article_link = $redirect['link'].'"'.$redirect['target'];
            }

            $count++;
            if($template_default["link_length"] && strlen($row['article_title']) > $template_default["link_length"]) {
                $article_title = substr($row['article_title'], 0, $template_default["link_length"]).$template_default["cut_title_add"];
            } else {
                $article_title = $row['article_title'];
            }
            $article_title = html_specialchars($article_title);
            if(trim($template_default["date_format"])) {
                $article_title =    $template_default["date_before"] .
                                    html_specialchars(international_date_format(
                                    $template_default["date_language"],
                                    $template_default["date_format"],
                                    $row['article_date'])) .
                                    $template_default["date_after"] .
                                    $article_title;
            }
            $new_links .= $template_default["link_before"];
            $new_links .= $template_default["link_symbol"];
            $new_links .= '<a href="' . $article_link . '>' . $article_title . '</a>';
            $new_links .= $template_default["link_after"];
    }

    //enclose whole
    if($new_links) $new_links = $template_default["before"].$new_links.$template_default["after"];

    return $new_links;
}

function get_article_idlink($article_id=0, $link_text="", $dbcon=null) {
    // returns the internal article link to given article ID/category
    $article_id     = intval($article_id);
    $article_cid    = 0;
    $link_text      = decode_entities($link_text);
    $link_text      = html_specialchars($link_text);
    $article_title  = $link_text;

    if($article_id) {
        $sql =  "SELECT article_id, article_title, article_cid, article_alias ".
                "FROM ".DB_PREPEND."phpwcms_article WHERE article_id=".$article_id." AND ".
                "article_aktiv=1 AND article_deleted=0 ";
        if(!PREVIEW_MODE) {
            $sql .= "AND article_begin < NOW() AND (article_end='0000-00-00 00:00:00' OR article_end > NOW()) ";
        }
        $sql .= "LIMIT 1";
        $data = _dbQuery($sql);

        if(isset($data[0])) {
            return '<a href="'.rel_url(array(), array('newsdetail'), setGetArticleAid($data[0])).'" title="'.$article_title.'">'.$link_text.'</a>';
        }
    }
    return '<a href="'.rel_url(array(), array('newsdetail'), 'aid='.$article_id).'" title="'.$article_title.'">'.$link_text.'</a>';
}

function get_keyword_link($keywords) {
    // returns a link or linklist for special article keywords
    // used for replacement tag {KEYWORD:Charlie}

    $keywords       = explode(',', is_array($keywords) && isset($keywords[1]) ? $keywords[1] : $keywords);
    $where          = '';
    $keyword_list   = '';
    $link           = '';

    if(count($keywords)) {
        foreach($keywords as $value) {
            $value = trim($value);
            if($value) {
                if($where) {
                    $where .= " AND ";
                    $keyword_list .= ", ";
                }
                $where .= "article_keyword LIKE "._dbEscape($value, true, '%*', '*%');
                $keyword_list .= html_specialchars($value);
            }
        }
    } else {
        $keyword_list = $keywords;
    }

    if($where) {

        $sql  = "SELECT article_id, article_cid, article_title, article_alias FROM ".DB_PREPEND."phpwcms_article WHERE ";
        // VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
        switch(VISIBLE_MODE) {
            case 0: $sql .= "article_aktiv=1 AND ";
                    break;
            case 1: $sql .= "(article_aktiv=1 OR article_uid=".$_SESSION["wcs_user_id"].") AND ";
                    break;
            //case 2: admin mode no additional neccessary
        }
        $sql .= 'article_deleted=0 AND ';
        if(!PREVIEW_MODE) {
            $sql .= "article_begin < NOW() AND (article_end='0000-00-00 00:00:00' OR article_end > NOW()) ";
        }
        $sql .= "AND (".$where.")";

        $result = _dbQuery($sql);

        if(isset($result[0])) {
            if(count($result) === 1) {
                // if only 1 article found
                $link .= '<a href="'.rel_url(array(), array(), setGetArticleAid($result[0])).'" title="'.html_specialchars($article_list[0]['article_title']).'">'.$keyword_list.'</a>';
            } else {
                // if more than one article found
                foreach($result as $key => $value) {
                    if($link) {
                        $link .= '|';
                    }
                    $link .= '<a href="'.rel_url(array(), array(), setGetArticleAid($value)).'" title="'.html_specialchars($value['article_title']).'">'.($key+1).'</a>';
                }
                $link = $keyword_list.' ['.$link.']';
            }
        }
    }
    if(!$link) {
        $link = $keyword_list;
    }
    $link  = $GLOBALS['template_default']["article"]["keyword_before"] . $link;
    $link .= $GLOBALS['template_default']["article"]["keyword_after"];
    return $link;
}

function clean_replacement_tags($text = '', $allowed_tags='<a><b><i><strong>') {
    // strip out special replacement tags
    $text = render_PHPcode($text);
    $text = str_replace('<td>', '<td> ', $text);
    $text = strip_tags($text, $allowed_tags);
    $text = str_replace('|', ' ', $text);

    $search = array(
                '/\{.*?\}/si',
                '/\[ID.*?\/ID\]/si',
                '/(\s+)/i',
                '/\[img=(\d+)(.*?){0,1}\](.*?)\[\/img\]/i',
                '/\[img=(\d+)(.*?){0,1}\]/i',
                '/\[download=(.*?)\/\]/i',
                '/\[download=(.*?)\](.*?)\[\/download\]/is'
                    );

    $replace = array(
                '',
                '',
                ' ',
                '$3',
                '',
                '',
                '$2'
                    );

    $text = preg_replace($search, $replace, $text);

    return trim($text);
}

function get_search_action($matches) {
    // return the search form action
    $sql  = "SELECT article_cid, article_alias FROM ".DB_PREPEND."phpwcms_article WHERE ";
    $sql .= "article_aktiv=1 AND article_deleted=0 ";
    if(!PREVIEW_MODE) {
        $sql .= "AND article_begin < NOW() AND (article_end='0000-00-00 00:00:00' OR article_end > NOW()) ";
    }
    $sql .= "AND article_id=".intval($matches[1])." LIMIT 1";

    $result = _dbQuery($sql);

    if(isset($result[0])) {
        return rel_url(array(), array(), setGetArticleAid($result[0]));
    }

    return '';
}

function get_index_link_up($linktext) {
    // return the link to parent category of current category
    $cat_id = $GLOBALS['content']['cat_id'];
    $linktext = trim(is_array($linktext) && isset($linktext[1]) ? $linktext[1] : $linktext);
    $link = '';
    if(!$linktext) $linktext = 'UP';
    if($cat_id && !$GLOBALS['content']['struct'][$cat_id]['acat_hidden']) {
        $upid = $GLOBALS['content']['struct'][$cat_id]['acat_struct'];
        $link = '<a href="'.rel_url(array(), array('newsdetail'), empty($GLOBALS['content']['struct'][$upid]['acat_alias']) ? 'id='.$upid : $GLOBALS['content']['struct'][$upid]['acat_alias']) .'">';
    }
    return ($link) ? $link.$linktext.'</a>' : $linktext;
}

function get_index_link_next($linktext, $cat_down=0) {

    global $content;

    // return the link to next article in current ctageory
    $a_id = isset($content['article_id']) ? $content['article_id'] : $GLOBALS['aktion'][1];
    if(is_array($linktext) && isset($linktext[2])) {
        $cat_down = intval($linktext[2]);
    }
    $linktext = trim(is_array($linktext) && isset($linktext[1]) ? $linktext[1] : $linktext);
    if($linktext === '') {
        $linktext = 'NEXT';
    }
    $link = '';

    if(count($content['articles']) > 1) {

        $c = 0; //temp counter
        foreach($content['articles'] as $key => $value) {
            if($c || !$a_id) {
                $link = '<a href="'.rel_url(array(), array(), empty($content['articles'][$key]['article_alias']) ? 'aid='.$key : $content['articles'][$key]['article_alias']).'">';
                break;
            }
            if($key == $a_id) $c++;
        }
    }

    if($cat_down && !$link) {
        // go cat down or to next cat above

        if($content['cat_id']) {
            foreach($content['struct'] as $key => $value) {
                if($content['struct'][$key]['acat_struct'] == $content['cat_id']) {
                    $link = '<a href="'.rel_url(array(), array(), empty($content['struct'][$key]['acat_alias']) ? 'id='.$key : $content['struct'][$key]['acat_alias']).'">';
                    break;
                }
            }
        } else {
            $c = 0;
            foreach($content['struct'] as $key => $value) {
                if($c) {
                    $link = '<a href="'.rel_url(array(), array(), empty($content['struct'][$key]['acat_alias']) ? 'id='.$key : $content['struct'][$key]['acat_alias']).'">';
                    break;
                }
                $c++;
            }
        }

        if(!$link && $content['cat_id']) {
            $c=0;
            $temp_key = array();
            foreach($content['struct'] as $key => $value) {
                if($content['struct'][$key]['acat_struct'] == $content['struct'][ $content['cat_id'] ]['acat_struct']) {
                    $temp_key[] = $key;
                }
            }
            $count_temp = count($temp_key);
            if($count_temp) {
                $c=0;
                foreach($temp_key as $value) {
                    if($value == $content['cat_id'] && $c+1 < $count_temp) {
                        $key = $temp_key[$c+1];
                        $link = '<a href="'.rel_url(array(), array(), empty($content['struct'][$key]['acat_alias']) ? 'id='.$key : $content['struct'][$key]['acat_alias']).'">';
                        break;
                    }
                    $c++;
                }
                if($c == $count_temp && !$link) {
                    // back reverese to higher next structure level
                    $current_id = $content['cat_id'];

                    while($c=1) {
                        $parent_id = $content['struct'][ $current_id ]['acat_struct'];
                        $parent_struct_id = $content['struct'][ $parent_id ]['acat_struct'];

                        $c=0;
                        foreach($content['struct'] as $key => $value) {
                            if($content['struct'][$key]['acat_struct'] == $parent_struct_id) {
                                if($c) {
                                    $link = '<a href="'.rel_url(array(), array(), empty($content['struct'][$key]['acat_alias']) ? 'id='.$key : $content['struct'][$key]['acat_alias']).'">';
                                    break;
                                }
                                if($key == $parent_id) $c=1;
                            }
                        }

                        if(!$parent_struct_id) {
                            if(!$parent_id) $link = '';
                            break;
                        } else {
                            $current_id = $parent_id;
                        }
                    }
                }
            }
        }
    }

    return ($link) ? $link.$linktext.'</a>' : $linktext;
}

function get_index_link_prev($linktext, $cat_up=0) {
    global $content;
    // return the link to next article in current ctageory
    $a_id = isset($content['article_id']) ? $content['article_id'] : $GLOBALS['aktion'][1];
    if(is_array($linktext) && isset($linktext[2])) {
        $cat_up = intval($linktext[2]);
    }
    $linktext = trim(is_array($linktext) && isset($linktext[1]) ? $linktext[1] : $linktext);
    if($linktext === '') {
        $linktext = 'PREV';
    }
    $link = '';
    $c = 0; //temp counter
    if(count($content['articles']) > 1 && $a_id) {

        foreach($content['articles'] as $key => $value) {
            if($key == $a_id && $c) {
                $link = '<a href="'.rel_url(array(), array(), empty($content['articles'][$prev_art_id]['article_alias']) ? 'aid='.$prev_art_id : $content['articles'][$prev_art_id]['article_alias']).'">';
                break;
            }
            $c++;
            $prev_cat_id = $content['articles'][$key]['article_cid'];
            $prev_art_id = $key;
        }
    }
    if($cat_up && $a_id && $c && !$link) {
        $link = '<a href="'.rel_url(array(), array(), empty($content['struct'][$content['cat_id']]['acat_alias']) ? 'id='.$content['cat_id'] : $content['struct'][$content['cat_id']]['acat_alias']).'">';
    }

    if($cat_up && !$link) {
        // go cat down or to next cat above
        $temp_key = array();
        foreach($content['struct'] as $key => $value) {
            if($content['struct'][$key]['acat_struct'] == $content['struct'][ $content['cat_id'] ]['acat_struct']) {
                $temp_key[] = $key;
            }
        }
        if(count($temp_key) && $content['cat_id']) {
            $c = 0;
            foreach($temp_key as $value) {
                if($value == $content['cat_id']) {
                    $prev_cat_id = (!$c) ? $content['struct'][$value]['acat_struct'] : $temp_key[$c-1];
                    $link = '<a href="'.rel_url(array(), array(), empty($content['struct'][$prev_cat_id]['acat_alias']) ? 'id='.$prev_cat_id : $content['struct'][$prev_cat_id]['acat_alias']).'">';
                    break;
                }
                $c++;
            }
        }
    }

    return ($link) ? $link.$linktext.'</a>' : $linktext;
}

function include_int_php($string) {
    // return the PHP var value
    $s = html_despecialchars($string[1]);
    if((strpos($s,'$GLOBALS') || strpos($s,'$_'))===false) {
        $s = preg_replace('/^\$(.*?)\[(.*?)/si', '$GLOBALS["$1"][$2', $s);
        if(substr($s,strlen($s)-1) != ']') {
            $s = str_replace('$', '', $s);
            $s = '$GLOBALS["'.$s.'"]';
        }
    }
    $s = str_replace('$phpwcms', '$notavailable', $s);
    $s = str_replace(array('["phpwcms"]', "['phpwcms']"), '["notavailable"]', $s);

    $s = trim($s);

    if(!$s) {
        return '';
    }

    // for security reasons several php functions are forbidden
    if(preg_match('/(passthru|system|exec|popen|eval|assert|include|require|file_get|fread|ini_set|function|unpack|gzuncompress|gzinflate|\]\(|gzdecode)/i', $s)) {
        echo '<!-- forbidden -->';
        return '<!-- forbidden -->';
    }

    ob_start();
    eval('echo '.$s.';');
    return ob_get_clean();
}

function include_int_phpcode($string) {
    // return the PHP code
    $s = html_despecialchars($string[1]);
    $s = str_replace(array('<br>', '<br />'), "\n", $s);

    $s = trim($s);

    if(!$s) {
        return '';
    }

    // for security reasons several php functions are forbidden
    if(preg_match('/(passthru|system|exec|popen|eval|assert|include|require|file_get|fread|ini_set|function|unpack|gzuncompress|gzinflate|\]\(|gzdecode)/i', $s)) {
        return '<!-- forbidden -->';
    }

    ob_start();
    eval($s.";");
    return ob_get_clean();
}

function build_sitemap($start=0, $counter=0, $sitemap=array()) {
    // create sitemap

    $s = '';
    $c = '';
    $counter++;

    if(empty($sitemap['catclass'])) {
        $c = '';
    } else {
        $c = ' class="'.$sitemap['catclass'];
        if($sitemap['classcount']) {
            $c .= $counter;
        }
        $c .= '"';
    }

    foreach($GLOBALS['content']['struct'] as $key => $value) {

        if( $GLOBALS['content']['struct'][$key]['acat_nositemap'] && _getStructureLevelDisplayStatus($key, $start) ) {

            $s .= '<li'.$sitemap['cat_style'].'>';

            if(!$GLOBALS['content']['struct'][$key]["acat_redirect"]) {
                $s .= '<a href="'.rel_url(array(), array(), empty($GLOBALS['content']['struct'][$key]['acat_alias']) ? 'id='.$key : $GLOBALS['content']['struct'][$key]['acat_alias']).'"';
            } else {
                $redirect = get_redirect_link($GLOBALS['content']['struct'][$key]["acat_redirect"], ' ', '');
                $s .= '<a href="'.$redirect['link'].'"'.$redirect['target'];
            }

            $s .= '>';
            $s .= html_specialchars($GLOBALS['content']['struct'][$key]['acat_name']);
            $s .= '</a>';
            if($sitemap["display"]) {
                $s .= build_sitemap_articlelist($key, $counter, $sitemap);
            }
            $s .= build_sitemap($key, $counter, $sitemap);
            $s .= '</li>' . LF;
        }
    }

    if($s) {
        $s = LF . '<ul'.$c.'>' . LF . $s . '</ul>';
    }

    return $s;
}

function build_sitemap_articlelist($cat, $counter=0, $sitemap=array()) {
    // create list of articles for given category

    $ao = get_order_sort($GLOBALS['content']['struct'][ $cat ]['acat_order']);

    $sql  = "SELECT article_id, article_title, article_alias FROM ".DB_PREPEND."phpwcms_article ";
    $sql .= "WHERE article_cid=".intval($cat)." AND article_nositemap=1 AND ";
    // VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
    switch(VISIBLE_MODE) {
        case 0: $sql .= "article_aktiv=1 AND ";
                break;
        case 1: $sql .= "(article_aktiv=1 OR article_uid=".$_SESSION["wcs_user_id"].") AND ";
                break;
        //case 2: admin mode no additional neccessary
    }
    $sql .= 'article_deleted=0 ';
    if(!PREVIEW_MODE) {
        $sql .= 'AND article_begin < NOW() ';
        $sql .= "AND (article_end='0000-00-00 00:00:00' OR article_end > NOW()) ";
    }
    $sql .= "ORDER BY ".$ao[2];

    $s = '';

    $result = _dbQuery($sql);

    if(isset($result[0]['article_id']) && count($result) > 1) {

        $c = '';

        if($sitemap['articleclass']) {
            $c .= ' class="'.$sitemap['articleclass'];
            if($sitemap['classcount']) {
                $c .= $counter;
            }
            $c .= '"';
        }

        foreach($result as $row) {

            $s .= '<li'.$sitemap['article_style'].'>';
            $s .= '<a href="'.rel_url(array(), array(), empty($row['article_alias']) ? 'aid='.$row['article_id'] : $row['article_alias']).'">';
            $s .= html($row['article_title']);
            $s .= '</a></li>' . LF;

        }

        $s = LF . '<ul'.$c.'>' . LF . $s . '</ul>';

    }

    return $s;
}

// render content part by replacing placeholder tags by value
function render_cnt_template($text='', $tag='', $value='', $value_else='') {
    $value = strval($value);
    if($value !== '') {
        $text = preg_replace('/\['.$tag.'\](.*?)\[\/'.$tag.'\]/is', '$1', $text);
        $text = preg_replace('/\['.$tag.'_ELSE\].*?\[\/'.$tag.'_ELSE\]/is', '', $text);
    } else {
        $text = preg_replace('/\['.$tag.'_ELSE\](.*?)\[\/'.$tag.'_ELSE\]/is', '$1', $text);
        $text = preg_replace('/\['.$tag.'\].*?\[\/'.$tag.'\]/is', '', $text);
        $text = str_replace('{'.$tag.'_ELSE}', $value_else, $text);
    }
    $text = str_replace('{'.$tag.'}', $value, $text); // Keep it compatible with older templates
    return $text;
}

function replace_cnt_template($text='', $tag='', $value='') {
    // replace tag by value
    return preg_replace('/\['.$tag.'\].*?\[\/'.$tag.'\]/is', strval($value), $text);
}

function parse_cnt_urlencode($value) {
    // replace tag by value
    return preg_replace_callback('/\[URLENCODE\](.*?)\[\/URLENCODE\]/is', 'render_urlencode', $value);
}

function render_urlencode($match) {
    if(is_array($match) && isset($match[1])) {
        $match = $match[1];
    }
    return rawurlencode(decode_entities($match));
}
// render date by replacing placeholder tags by value
function render_cnt_date($text='', $date=0, $livedate=null, $killdate=null) {
    if(!($date = intval($date))) {
        $date = now();
    }
    $text = render_date($text, $date, 'DATE');
    $text = render_date($text, now(), 'NOW');
    if(($livedate = intval($livedate))) {
        $text = render_cnt_template($text, 'LIVEDATE', ' ');
        $text = render_date($text, $livedate, 'LIVEDATE');
    } else {
        $text = render_cnt_template($text, 'LIVEDATE', '');
    }
    if(($killdate = intval($killdate))) {
        $text = render_cnt_template($text, 'KILLDATE', ' ');
        $text = render_date($text, $killdate, 'KILLDATE');
    } else {
        $text = render_cnt_template($text, 'KILLDATE', '');
    }
    return $text;
}
// render date by replacing placeholder tags by value
function render_date($text='', $date=0, $rt='DATE') {
    $rt = preg_quote($rt, '/');
    $GLOBALS['phpwcms']['callback'] = $date;
    $text = preg_replace_callback('/\{'.$rt.':(.*?) lang=(..)\}/', 'international_date_format_callback', $text);
    $text = preg_replace_callback('/\{'.$rt.':(.*?)\}/', 'date_callback', $text);
    return $text;
}

function render_keywords($text='', $keywords=null, $separator=',') {

    if($keywords === null || $text == '' || strpos($text, '{KEYWORDS') === false) {
        return $text;
    }
    if(is_string($keywords)) {
        $keywords = convertStringToArray($keywords, $separator);
    } elseif(!is_array($keywords)) {
        $keywords = array();
    }

    $prefix = '';
    $suffix = '';
    $spacer = ' ';

    preg_match_all('/\{KEYWORDS(.*?)\}/', $text, $matches);

    //dumpVar($matches);

    return $text;
}

function returnTagContent($string='', $tag='', $findall=false, $tagOpen='[', $tagClose=']') {
    // used to exclude a special string sequence from string
    // enclosed by [tag][/tag] or also <tag></tag>
    $data               = array();
    $data['original']   = $string;
    $tag_open           = preg_quote($tagOpen.$tag.$tagClose, '/');
    $tag_close          = preg_quote($tagOpen.'/'.$tag.$tagClose, '/');
    $data['new']        = trim(preg_replace('/'.$tag_open.'(.*?)'.$tag_close.'/is', '', $string));
    if($findall) {
        preg_match_all('/'.$tag_open.'(.*?)'.$tag_close.'/is', $string, $matches);
    } else {
        preg_match('/'.$tag_open.'(.*?)'.$tag_close.'/is', $string, $matches);
    }
    $data['tag']        = isset($matches[1]) ? $matches[1] : '';
    return $data;
}

function include_url($url, $ignore_ssl=false) {
    // include given URL but only take content between <body></body>

    global $include_urlparts;

    if( is_string($url) ) {
        $url = array( 1 => $url );
    } elseif( ! isset($url[1]) ) {
        return '';
    }

    $k              = '';
    $url            = trim($url[1]);
    $url            = explode(' ', $url);
    $cache          = isset($url[1]) ? intval(str_replace('CACHE=', '', strtoupper($url[1]))) : 0;
    $url            = $url[0];
    $cache_status   = 'MISSING';

    if($url && $cache) {

        $cache_filename = md5($url).'-url'; // set cache file name
        $cache_file     = PHPWCMS_CONTENT.'tmp/'.$cache_filename;   // set caching file
        $cache_status   = check_cache($cache_file, $cache); // ceck existence

        if($cache_status === 'VALID') {  // read cache

            $k  = read_textfile($cache_file);
            $k  = trim($k);

            if(empty($k)) {
                $cache_status = 'EXPIRED'; // check if cache content is available
            }

        }

    }

    if($cache_status !== 'VALID' && $url) {  // cache file is missing or outdated

        $include_urlparts = parse_url($url);
        if(!empty($include_urlparts['path'])) {
            $include_urlparts['path'] = dirname($include_urlparts['path']);
            $include_urlparts['path'] = str_replace('\\', '/', $include_urlparts['path']);
        }
        if($ignore_ssl && function_exists('stream_context_create')) {
            $context = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ),
            );
            $k = @file_get_contents($url, false, stream_context_create($context));
        } else {
            $k = @file_get_contents($url);
        }

        if($k) {
            // now check against charset
            if(preg_match('/charset=(.*?)"/i', $k, $match)) {
                $charset = $match[1];
                $charset = str_replace(array('"', "'", '/'), '', $charset);
                $charset = strtolower(trim($charset));
            } elseif(preg_match('/http-equiv="{0,1}Content-Type"{0,1}\s{1,}(content="{0,1}.*?"{0,1}.{0,3}>)/i', $k, $match)) {
                $charset = '';
                if(!empty($match[1])) {
                    $charset = strtolower($match[1]);
                    $charset = trim(str_replace(array('"', "'", '/', 'content=', ' ', '>'), '', $charset));
                }
            } else {
                $charset = false;
            }

            if(preg_match('/<body[^>]*?'.'>(.*)<\/body>/is', $k, $match)) {
                $k = $match[1];
            }

            $k = str_replace(array('<?', '?>', '<%', '%>'), array('&lt;?', '?&gt;', '&lt;&#37;', '&#37;&gt;'), $k);
            $k = preg_replace_callback('/(href|src|action)=[\'|"]{0,1}(.*?)[\'|"]{0,1}( .*?){0,1}>/i', 'make_absoluteURL', $k);
            $k = htmlfilter_sanitize( trim($k) , array(false, 'link', 'meta'), array(), array('img', 'br', 'hr', 'input'), true);

            if($charset != false) {
                $k = makeCharsetConversion($k, $charset, PHPWCMS_CHARSET, 1);
            }

            // now write or update cache file in case there is timeout or content
            if($cache && $k) {
                @write_textfile($cache_file, $k);
            }

        }
        $include_urlparts = '';

    }
    return $k;
}

function make_absoluteURL($matches) {
    // replaces all relative URLs in href=/src= to absolute paths based on called URI
    $parts = $GLOBALS['include_urlparts'];
    $path  = $matches[2];
    $k = '';
    if(preg_match('/^(http|mailto|ftp|https|skype|itms)/i', $path)) {
        $k = $path;
    } else {
        if(empty($parts['path'])) $parts['path'] = '';
        $k = $parts['host'].$parts['path'].'/'.$path;
        $k = str_replace('///', '/', $k);
        $k = str_replace('//', '/', $k);
        $k = $parts['scheme'].'://'.$k;
    }
    if(empty($matches[3])) $matches[3] = '';
    return $matches[1].'="'.$k.'"'.$matches[3].'>';

}

// combined PHP replace renderer
function render_PHPcode($string='') {
    if($string && strpos($string,'PHP') !== false) {

        if(empty($GLOBALS['phpwcms']['enable_inline_php'])) {

            $string = remove_unsecure_rptags($string);

        } else {

            // includes external PHP script and returns the content
            $string = preg_replace_callback('/\{PHP:(.*?)\}/', 'include_ext_php', $string);
            // do complete PHP code
            $string = preg_replace_callback("/\[PHP\](.*?)\[\/PHP\]/s", 'include_int_phpcode', $string);
            // includes external PHP script and returns the content
            $string = preg_replace_callback("/\{PHPVAR:(.*?)\}/s", 'include_int_php', $string);

        }

    }
    return $string;
}

function build_list($struct, $level, $temp_tree, $act_cat_id, $class='', $depth=0) {
    // this returns the level structure based on given arrays
    // it is special for browsing from root levels

    if($class != '') {
        $curClass = ' class="'.$class.$depth.'"';
        $curClassNext = ' class="'.$class.($depth+1).'"';
        $curClassActive = ' class="'.$class.'Active'.$depth.'"';
    } else {
        $curClass = '';
        $curClassNext = '';
        $curClassActive = ' class="listActive"';
    }

    $depth++;

    $temp_menu = "\n<ul".$curClass.">\n";
    foreach($struct as $key => $value) {

        if( _getStructureLevelDisplayStatus($key, $level) ) {

            if(!$struct[$key]["acat_redirect"]) {
                $link = rel_url(array(), array('newsdetail'), $struct[$key]["acat_alias"] ? $struct[$key]["acat_alias"] : 'id='.$key);
                $redirect['target'] = '';
            } else {
                $redirect = get_redirect_link($struct[$key]["acat_redirect"], ' ', '');
                $link = $redirect['link'];
            }

            if(!empty($temp_tree[$key])) {

                if($act_cat_id == $key) {
                    $temp_menu .= "\n<li".$curClassActive.">";
                } else {
                    $temp_menu .= "\n<li>";
                }

                $temp_menu .= '<a href="'.$link.'">'.html_specialchars($struct[$key]["acat_name"]).'</a>';

                $temp_menu .= build_list ($struct, $key, $temp_tree, $act_cat_id, $class, $depth);
                $temp_menu .= '</li>';

            } else {
                $temp_menu .= "\n<li>".'<a href="'.$link.'"'.$redirect['target'].'>';
                $temp_menu .= html_specialchars($struct[$key]["acat_name"])."</a></li>\n";
            }
        }
    }

    $temp_menu = trim($temp_menu);
    return $temp_menu != "<ul".$curClassNext.">" ? $temp_menu."\n</ul>" : '';
}

function combined_POST_cleaning($val) {
    $val = clean_slweg($val);
    $val = remove_unsecure_rptags($val);
    return $val;
}

function get_fe_userinfo($forum_userID) {
    // get frontend user information
    $forum_userID = intval($forum_userID);
    $got_the_info = false;

    if($forum_userID != 0 && (!isset($GLOBALS['FE_USER']) || !isset($GLOBALS['FE_USER'][$forum_userID]))) {
        //connect to user db and get information
        $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_user WHERE usr_id=".$forum_userID." LIMIT 1";
        $result = _dbQuery($sql);

        if(isset($result[0]['usr_id'])) {

            $GLOBALS['FE_USER'][$forum_userID] = array(
                'FE_ID'     => $forum_userID,
                'login'     => $result[0]['usr_login'],
                'pass'      => $result[0]['usr_pass'],
                'email'     => $result[0]['usr_email'],
                'admin'     => $result[0]['usr_admin'],
                'fe'        => $result[0]['usr_fe'],
                'aktiv'     => $result[0]['usr_aktiv'],
                'name'      => $result[0]['usr_name'],
                'lang'      => empty($result[0]['usr_lang']) ? $GLOBALS['phpwcms']['default_lang'] : $result[0]['usr_lang'],
                'wysiwyg'   => $result[0]['usr_wysiwyg']
            );

            $got_the_info = true;

        }

    } else {

        $got_the_info = true;

    }
    if(($forum_userID === 0 && !isset($GLOBALS['FE_USER'][$forum_userID])) || !$got_the_info) {

        $forum_userID = 0;

        $GLOBALS['FE_USER'][$forum_userID] = array(
            'FE_ID'     => $forum_userID,
            'login'     => 'guest',
            'pass'      => '',
            'email'     => 'noreply@localhost',
            'admin'     => 0,
            'fe'        => 0,
            'aktiv'     => 1,
            'name'      => 'Guest',
            'lang'      => $GLOBALS['phpwcms']['default_lang'],
            'wysiwyg'   => empty($GLOBALS['phpwcms']['wysiwyg_editor']) ? 0 : 1
        );
    }

}

function highlightSearchResult($string='', $search=null) {

    if(empty($string) || empty($search)) {
        return $string;
    }

    // string will be highlighted by $search - can be string or array
    if(!is_array($search)) {
        $search = array(strval($search));
    }

    if(isset($GLOBALS['phpwcms']['current_highlight_match'])) {

        $highlight_match = $GLOBALS['phpwcms']['current_highlight_match'];

    } else {

        $highlight_match = '';

        foreach($search as $value) {
            if($highlight_match !== '') {
                $highlight_match .= '|';
            }
            $highlight_match .= preg_quote($value, '/');
        }

        $highlight_match = str_replace("\\?", '.?', $highlight_match);
        $highlight_match = trim(str_replace("\\*", '.*', $highlight_match));

    }

    if(!preg_match('/<.+?>/', $string)) {
        $string = preg_replace('/('.$highlight_match.')/i', $GLOBALS['phpwcms']['search_highlight']['prefix'].'$1'.$GLOBALS['phpwcms']['search_highlight']['suffix'], $string);
    } else {
        $string = preg_replace('/(?<=>)([^<]+)?('.$highlight_match.')/i', '$1'.$GLOBALS['phpwcms']['search_highlight']['prefix'].'$2'.$GLOBALS['phpwcms']['search_highlight']['suffix'], $string);
    }

    return $string;
}
function pregReplaceHighlightWrapper($matches) {
    // just a wrapper for frontend sectional highlighting
    global $highlight_words;
    return highlightSearchResult($matches[1], $highlight_words);
}

function buildCascadingMenu($parameter='', $counter=0, $param='string') {

    /*
        @string $parameter:
            menu_type,
            start_id,
            max_level_depth,
            class_path|ul_class_level1|ul_class_level2|...,
            class_active_li|class_active_a,
            ul_id_name,
            wrap_ul_div(0 = off, 1 = <div>, 2 = <div id="">, 3 = <div class="navLevel-0">),
            wrap_link_text(<em>|</em>),
            articlemenu_start_level|articlemenu_list_image_size (WxHxCROP OR WxHxCROP)|_
            articlemenu_use_text (take text from: description:MAXLEN OR menutitle:MAXLEN OR teaser:MAXLEN OR teaser:HTML)|_
            articlemenu_position (inside|outside)|_
            <custom>[TEXT]{TEXT}[/TEXT][IMAGE]<img src="{IMAGE}" alt="{IMAGE_NAME}">[/IMAGE]</custom>
    */

    if($param === 'string') {

        $parameter      = explode(',', is_array($parameter) && isset($parameter[1]) ? $parameter[1] : $parameter);
        $menu_type      = empty($parameter[0]) ? '' : strtoupper(trim($parameter[0]));

        $unfold         = 'all';
        $ie_patch       = false; // unused at the moment
        $create_css     = false;
        $parent         = false; // do not show parent link
        $articlemenu    = false; // do not show category's article titles as menu entry
        $bootstrap      = false; // bootstrap dropdown style
        $onepage        = IS_ONEPAGE_TEMPLATE; // render menu links as id anchor <a href=#alias>
        $onepage_every  = false; // ToDo!
        $hide_first     = false;

        /**
         * P = Show parent level
         * B = Bootstrap compatible rendering
         * A = Articles as menu items
         * AH = Articles as menu items, hide first (avoid double link because of parent structure level)
         * F = Folded, unfold only active level
         * HCSS = Sample horizontal CSS based menu
         * VCSS = Sample vertical CSS based menu
         **/
        switch($menu_type) {

            case 'B':       $bootstrap      = true;
                            break;

            case 'BAH':     $hide_first     = true;
            case 'BA':      $bootstrap      = true;
            case 'A':       $articlemenu    = true;
                            break;

            case 'PBAH':    $hide_first     = true;
            case 'PBA':     $bootstrap      = true;
            case 'PA':      $articlemenu    = true;
            case 'P':       $parent         = true;
                            break;

            case 'PB':      $parent         = true;
                            $bootstrap      = true;
                            break;

                            // vertical, active path unfolded
            case 'FPAH':    $hide_first     = true;
            case 'FPA':     $articlemenu    = true;
            case 'FP':      $parent         = true;
            case 'F':       $unfold         = 'active_path';
                            break;

            case 'FAH':     $hide_first     = true;
            case 'FA':      $articlemenu    = true;
                            $unfold         = 'active_path';
                            break;

            case 'HCSSP':   $parent     = true;
            case 'HCSS':    $create_css = true;
                            break;

            case 'VCSSP':   $parent     = true;
            case 'VCSS':    $create_css = true;
                            break;
        }

        $start_id       = empty($parameter[1]) ? 0  : intval($parameter[1]);
        $max_depth      = empty($parameter[2]) ? 0  : intval($parameter[2]);
        $path_class     = empty($parameter[3]) ? '' : trim($parameter[3]);
        $active_class   = empty($parameter[4]) ? '' : trim($parameter[4]);
        $level_id_name  = empty($parameter[5]) ? '' : trim($parameter[5]);
        $wrap_ul_div    = empty($parameter[6]) ? 0  : intval($parameter[6]);
        $amenu_options  = array(
            'enable'        => false,
            'hide_first'    => $hide_first,
            'image'         => false,
            'text'          => false,
            'width'         => 0,
            'height'        => 0,
            'crop'          => 0,
            'textlength'    => 0,
            'position'      => 'outside',
            'template'      => '<span class="amenu-extended">[IMAGE]<img src="[%IMAGE%]" alt="[%IMAGE_NAME%]" />[/IMAGE][TEXT]<span class="p">[%TEXT%]</span>[/TEXT]</span>'
        );
        if($path_class) {
            $path_class = explode('|', $path_class);
            foreach($path_class as $key => $class_name) {
                $path_class[$key] = trim($class_name);
            }
        } else {
            $path_class = array(0 => '');
        }
        if($active_class) {
            $active_class       = explode('|', $active_class, 2);
            $active_class[0]    = trim($active_class[0]);
            $active_class[1]    = empty($active_class[1]) ? '' : trim($active_class[1]);
        } else {
            $active_class       = array(0 => '', 1 => '');
        }
        if($wrap_ul_div > 3) {
            $wrap_ul_div = 2;
        } elseif($wrap_ul_div < 0) {
            $wrap_ul_div = 0;
        }
        $wrap_link_text = empty($parameter[7]) ? array(0 => '', 1 => '') : explode('|', trim($parameter[7]), 2);
        if(empty($wrap_link_text[1])) {
            $wrap_link_text[1] = '';
        }
        if(empty($parameter[8])) {
            $amenu_level = 0;
        } else {
            $parameter[8]   = explode('|', $parameter[8]);
            $amenu_level    = intval($parameter[8][0]);
            if(!empty($parameter[8][1]) && ($parameter[8][1] = trim($parameter[8][1]))) { // articlemenu_list_image_size
                $parameter[8][1] = explode('x', $parameter[8][1]);
                $amenu_options['width']     = intval($parameter[8][1][0]); // width
                $amenu_options['height']    = empty($parameter[8][1][1]) ? 0 : intval($parameter[8][1][1]); // height
                $amenu_options['crop']      = empty($parameter[8][1][2]) ? 0 : 1; // crop
                $amenu_options['enable']    = true;
                $amenu_options['image']     = true;
            }
            if(!empty($parameter[8][2]) && ($parameter[8][2] = trim($parameter[8][2]))) { // articlemenu_use_text
                $parameter[8][2]    = explode(':', $parameter[8][2]);
                $parameter[8][2][0] = strtolower(trim($parameter[8][2][0]));
                if($parameter[8][2][0] == 'description' || $parameter[8][2][0] == 'menutitle' || $parameter[8][2][0] == 'teaser') { // default is description
                    $amenu_options['text'] = $parameter[8][2][0];
                    if(empty($parameter[8][2][1])) {
                        $amenu_options['textlength'] = 0;
                    } elseif($parameter[8][2][0] == 'teaser' && strtoupper($parameter[8][2][1]) == 'HTML') {
                        $amenu_options['textlength'] = 'HTML';
                    } else {
                        $amenu_options['textlength'] = intval($parameter[8][2][1]); // set max text length
                    }
                    $amenu_options['enable'] = true;
                }
            }
            if($amenu_options['enable'] && !empty($parameter[8][3]) && ($parameter[8][3] = trim($parameter[8][3])) && strtolower($parameter[8][3]) == 'inside') { // articlemenu_position
                $amenu_options['position'] = 'inside';
            }
            if($amenu_options['enable'] && !empty($parameter[8][4])) { // template
                $amenu_options['template'] = str_replace(array('[%', '%]'), array('{', '}'), $parameter[8][4]);
            }
        }

        $parameter = array(
             0 => $menu_type,
             1 => $start_id,
             2 => $max_depth,
             3 => $path_class,
             4 => $active_class,
             5 => $level_id_name,
             6 => $wrap_ul_div,
             7 => $wrap_link_text,
             8 => $unfold,
             9 => $ie_patch,
            10 => $create_css,
            11 => $amenu_level,
            12 => array(
                'articlemenu'   => $articlemenu,
                'level_id'      => $start_id
            ),
            13 => $bootstrap,
            14 => $onepage,
            15 => $onepage_every
        );

        if($articlemenu) {
            $parameter[12]['class_active']          = $active_class;
            $parameter[12]['wrap_title_prefix']     = $wrap_link_text[0];
            $parameter[12]['wrap_title_suffix']     = $wrap_link_text[1];
            $parameter[12]['item_prefix']           = "\t";
            $parameter[12]['item_suffix']           = '';
            $parameter[12]['sort']                  = 'level';
            $parameter[12]['item_tag']              = 'li';
            $parameter[12]['wrap_tag']              = '';
            $parameter[12]['attribute_wrap_tag']    = '';
            $parameter[12]['class_item_link']       = $GLOBALS['template_default']['classes']['navlist-link-class'];
            $parameter[12]['class_item_tag']        = $GLOBALS['template_default']['classes']['navlist-asub_no'];
            $parameter[12]['class_first_item_tag']  = $GLOBALS['template_default']['classes']['navlist-asub_first'];
            $parameter[12]['class_last_item_tag']   = $GLOBALS['template_default']['classes']['navlist-asub_last'];
            $parameter[12]['return_format']         = 'array';
            $parameter[12]['articlemenu_options']   = $amenu_options;
        }

    } else {

        $menu_type      = $parameter[0];
        $start_id       = $parameter[1];
        $max_depth      = $parameter[2];
        $path_class     = $parameter[3];
        $active_class   = $parameter[4];
        $level_id_name  = $parameter[5];
        $wrap_ul_div    = $parameter[6];
        $wrap_link_text = $parameter[7];
        $unfold         = $parameter[8];
        $ie_patch       = $parameter[9];
        $create_css     = $parameter[10];
        $amenu_level    = $parameter[11];
        $bootstrap      = $parameter[13];
        $onepage        = $parameter[14];
        $onepage_every  = $parameter[15];
        $parent         = false; // do not show parent link

    }

    $li             = '';
    $ali            = '';
    $ul             = '';
    $TAB            = str_repeat('  ', $counter);
    $_menu_type     = strtolower($menu_type);
    $max_depth      = $max_depth == 0 || $max_depth - 1 > $counter;
    $x              = 0;
    $items          = array();
    $last_item      = 0;

    foreach($GLOBALS['content']['struct'] as $key => $value) {

        if( _getStructureLevelDisplayStatus($key, $start_id) ) {
            $items[$key] = $key;
            $last_item++;
        }

    }

    foreach($items as $key) {

        $li_ul      = '';
        $li_class   = array();
        $bs_toggle  = false;

        if($max_depth && ($unfold == 'all' || ($unfold == 'active_path' && isset($GLOBALS['LEVEL_KEY'][$key]))) ) {
            $parameter[1]   = $key;
            $li_ul          = buildCascadingMenu($parameter, $counter+1, 'param_is_array');
        }

        $li .= $TAB.'   <li';

        if($level_id_name) {
            $li .= ' id="li_'.$level_id_name.'_'.$key.'"';
        }
        if($li_ul) {
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_ul'];
            if($bootstrap) {
                $li_class[] = $GLOBALS['template_default']['classes']['navlist-bs-dropdown'];
                $bs_toggle  = true;
            }
        } elseif(getHasSubStructureStatus($key)) {
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_no'];
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_ul_true'];
        } else {
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_no'];
        }

        $li_a_title = html_specialchars($GLOBALS['content']['struct'][$key]['acat_name']);
        $li_a_class = array(
            $GLOBALS['template_default']['classes']['navlist-link-class']
        );
        if($active_class[1] && $key == $GLOBALS['aktion'][0]) {
            $li_a_class[] = $active_class[1]; // set active link class
        }
        if($bs_toggle) {
            $li_a_class[]   = $GLOBALS['template_default']['classes']['navlist-bs-dropdown-toggle'];
            $bs_data_toggle = ' ' . $GLOBALS['template_default']['attributes']['navlist-bs-dropdown-data'];
            $bs_caret       = $GLOBALS['template_default']['attributes']['navlist-bs-dropdown-caret'];
        } else {
            $bs_data_toggle = '';
            $bs_caret       = '';
        }
        if($bootstrap && $GLOBALS['template_default']['classes']['navlist-bs-link']) {
            $li_a_class[] = $GLOBALS['template_default']['classes']['navlist-bs-link'];
        }
        $li_a_class = ' class="' . implode(' ', get_unique_array($li_a_class)) . '"';
        $li_a  = get_level_ahref($key, $li_a_class.' title="'.$li_a_title.'"'.$bs_data_toggle);
        $li_a .= $wrap_link_text[0] . $li_a_title . $bs_caret . $wrap_link_text[1];

        if($path_class[0] && isset($GLOBALS['LEVEL_KEY'][$key])) {
            $li_class[] = $path_class[0];
        }
        if($active_class[0] != '' && $key == $GLOBALS['aktion'][0]) {
            $li_class[] = $active_class[0];
        }
        if($x === 0) {
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_first'];
        }

        $x++;

        if($x === $last_item) {
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_last'];
        }
        $li_class[] = $GLOBALS['content']['struct'][$key]['acat_class'];
        $li .= ' class="' . implode(' ', get_unique_array($li_class)) .'"';
        $li .= '>' . $li_a . '</a>';
        $li .= $li_ul.'</li>'.LF;
    }

    // show article menu
    if($parameter[12]['articlemenu'] && $amenu_level <= $counter) {

        $parameter[12]['level_id']      = $start_id;
        $parameter[12]['item_prefix']   = $TAB.$TAB.$TAB;

        $ali = getArticleMenu($parameter[12]);

        if(count($ali) > 1) {
            $li .= implode(LF, $ali) . LF;
            $ali = $TAB;
        } else {
            $ali = '';
        }

    }

    // also check if $parent
    if($li || ($parent && isset($GLOBALS['content']['struct'][$start_id]))) {

        switch($wrap_ul_div) {
            case 1:     $ul = LF.$TAB.'<div>';
                        $close_wrap_ul = '</div>'.LF.$TAB;
                        break;
            case 2:     $ul = LF.$TAB.'<div id="ul_div_'.$start_id.'">';
                        $close_wrap_ul = '</div>'.LF.$TAB;
                        break;
            case 3:     $ul = LF.$TAB.'<div class="'.$GLOBALS['template_default']['classes']['navlist-navLevel'].$counter.'">';
                        $close_wrap_ul = '</div>'.LF.$TAB;
                        break;
            default:    $ul = '';
                        $close_wrap_ul = '';
        }
        $ul .= LF . $TAB . $ali . '<ul';
        if($level_id_name) {
            $ul .= ' id="'.$level_id_name.'_'.$start_id.'"';
        }

        $ul_class = empty($path_class[$counter+1]) ? '' : $path_class[$counter+1];
        if(isset($GLOBALS['LEVEL_KEY'][$start_id]) && $counter && isset($path_class[0])) {
            $ul_class .= ' ' . $path_class[0];
        }
        if($bootstrap && $counter) {
            $ul_class = 'dropdown-menu '.$ul_class;
        }
        $ul_class = trim($ul_class);
        if($ul_class) {
            $ul .= ' class="' . $ul_class . '"';
        }
        $ul .= '>'.LF;

        if($parent && isset($GLOBALS['content']['struct'][$start_id])) {
            $ul .= LF . $TAB.'   <li';
            if($level_id_name) {
                $ul .= ' id="li_'.$level_id_name.'_'.$start_id.'"';
            }
            $li_class = array(
                $GLOBALS['content']['struct'][$start_id]['acat_class'],
                $GLOBALS['template_default']['classes']['navlist-sub_parent']
            );
            if($active_class[0] != '' && $start_id == $GLOBALS['aktion'][0]) {
                $li_class[] = $active_class[0];
            }
            $ul .= ' class="' . implode(' ', get_unique_array($li_class)) . '">';

            $link_text  = html_specialchars($GLOBALS['content']['struct'][$start_id]['acat_name']);
            $link_class = array(
                $GLOBALS['template_default']['classes']['navlist-link-class']
            );
            if($active_class[1] && $start_id == $GLOBALS['aktion'][0]) {
                $link_class[] = $active_class[1]; // set active link class
            }
            if($bootstrap && $GLOBALS['template_default']['classes']['navlist-bs-link']) {
                $link_class[] = $GLOBALS['template_default']['classes']['navlist-bs-link'];
            }
            $link_class = ' class="' . implode(' ', get_unique_array($link_class)) . '"';
            $ul .= get_level_ahref($start_id, $link_class.' title="'.$link_text.'"');
            $ul .= $wrap_link_text[0] . $link_text . $wrap_link_text[1];
            $ul .= '</a></li>'.LF;
        }

        $ul .= $li;
        $ul .= $TAB . $ali . '</ul>' . LF . $TAB . $close_wrap_ul;

        if($create_css && empty($GLOBALS['block']['custom_htmlhead'][$menu_type][$counter])) {

            if($counter) {

                $tmp_css  = '    .'.$_menu_type.'_menu ul li:hover '.str_repeat('ul ', $counter) .'ul { display: none; }'.LF;
                $tmp_css .= '    .'.$_menu_type.'_menu ul '.str_repeat('ul ', $counter) .'li:hover ul { display: block; }';
                $GLOBALS['block']['custom_htmlhead'][$menu_type][$counter] = $tmp_css;

            } else {  //if($counter == 0) {

                $GLOBALS['block']['custom_htmlhead'][$menu_type][-9]  = LF.'  <style type="text/css">'.LF.SCRIPT_CDATA_START;
                $GLOBALS['block']['custom_htmlhead'][$menu_type][-8]  = '    @import url("'.TEMPLATE_PATH.'inc_css/specific/nav_list_ul_'.$_menu_type.'.css");';

                $GLOBALS['block']['custom_htmlhead'][$menu_type][-5]  = '    .'.$_menu_type.'_menu ul ul { display: none; }';
                $GLOBALS['block']['custom_htmlhead'][$menu_type][-4]  = '    .'.$_menu_type.'_menu ul li:hover ul { display: block; }';

                ksort($GLOBALS['block']['custom_htmlhead'][$menu_type]);
                $GLOBALS['block']['custom_htmlhead'][$menu_type][]   = SCRIPT_CDATA_END.LF.'  </style>';
                $GLOBALS['block']['custom_htmlhead'][$menu_type]   = implode(LF, $GLOBALS['block']['custom_htmlhead'][$menu_type]);

                $ul = '<div class="'.$_menu_type.'_menu">'.$ul.'</div>';

            }

        }

    }

    return $ul;
}

function get_level_ahref($key=0, $custom_link_add='') {
    $link = '<a href="';
    if($GLOBALS['content']['struct'][$key]["acat_redirect"]) {
        $redirect = get_redirect_link($GLOBALS['content']['struct'][$key]["acat_redirect"], ' ', '');
        $link .= html_specialchars($redirect['link']).'"'.$redirect['target'];
    } elseif(IS_ONEPAGE_TEMPLATE && $GLOBALS['content']['struct'][$key]['acat_onepage']) {
        $link .= '#';
        $link .= $GLOBALS['content']['struct'][$key]['acat_alias'] ? $GLOBALS['content']['struct'][$key]['acat_alias'] : 'opid'.$key;
        $link .= '"';
    } else {
        $link .= rel_url(array(), array('newsdetail', 'profile_manage', 'profile_reminder', 'profile_register'), $GLOBALS['content']['struct'][$key]['acat_alias'] ? $GLOBALS['content']['struct'][$key]['acat_alias'] : 'id='.$key);
        $link .= '"';
    }
    return $link.$custom_link_add.'>';
}

function getHasSubStructureStatus($level_id=0) {
    if( !isset($GLOBALS['content']['struct'][$level_id]) ) {
        return false;
    }
    foreach($GLOBALS['content']['struct'] as $key => $value) {
        if( _getStructureLevelDisplayStatus($key, $level_id) ) {
            return true;
        }
    }
    return false;
}

function getStructureChildData($level_id=0) {
    if( !isset($GLOBALS['content']['struct'][$level_id]) ) return array();
    $struct_data = array();
    foreach($GLOBALS['content']['struct'] as $key => $value) {
        if( _getStructureLevelDisplayStatus($key, $level_id) ) {
            $struct_data[$key] = $value;
        }
    }
    return $struct_data;
}

function getStructureChildEntryHref($childData) {

    $a = array(
        'link' => '',
        'target' => ''
    );
    if($childData["acat_redirect"]) {
        $redirect = get_redirect_link($childData["acat_redirect"], ' ', '');
        $a['link']   = $redirect['link'];
        $a['target'] = $redirect['target'];
    } else {
        $a['link'] = rel_url(array(), array(), $childData['acat_alias'] ? $childData['acat_alias'] : 'id='.$childData['acat_id']);
    }
    return $a;

}

function getImageCaption($caption, $array_index='NUM', $short=false) {

    // empty
    if(empty($caption)) {
        if($short) {
            return array(
                'caption' => '',
                'alt' => '',
                'title' => ''
            );
        }
        if($array_index === 'NUM') {
            return array(
                0 => '',
                1 => '',
                2 => array('', '', ''),
                3 => '',
                4 => '',
                'caption_text' => '',
                'caption_alt' => '',
                'caption_link' => '',
                'caption_target' => '',
                'caption_title' => '',
                'caption_copyright' => ''
            );
        }
        return array(
            0 => '',
            1 => '',
            2 => array('', '', ''),
            3 => '',
            4 => ''
        );
    } elseif(is_array($caption)) {
        $filedata = !empty($caption['file']) ? getFileDetails($caption['file']) : null;
        $caption = isset($caption['caption']) ? $caption['caption'] : '';
    } else {
        $filedata = null;
    }

    // splits given image caption and returns an array
    $caption = explode('|', $caption);

    // following is default for the exploded $caption
    // [0] caption text
    // [1] alt text for image
    // [2] link -> array(0 => link, 1 => target)
    // [3] title text -> if empty alt text will be used
    // [4] copyright information
    $caption[0] = trim($caption[0]);
    if(($caption[0] = trim($caption[0])) === '' && isset($filedata['f_longinfo'])) {
        $caption[0] = $filedata['f_longinfo'];
    }
    $caption[1] = isset($caption[1]) ? trim($caption[1]) : (isset($filedata['f_alt']) ? $filedata['f_alt'] : '');
    $caption[3] = isset($caption[3]) ? trim($caption[3]) : (isset($filedata['f_title']) ? $filedata['f_title'] : '');

    // cut here – just return caption and alt text
    if($short) {
        return array(
            'caption' => $caption[0],
            'alt' => $caption[1],
            'title' => $caption[3]
        );
    }

    $caption[2]    = isset($caption[2]) ? explode(' ', trim($caption[2])) : array(0 => '', 1 => '');
    $caption[2][0] = trim($caption[2][0]);
    $caption[2][2] = '';
    if(empty($caption[2][0]) || empty($caption[2][1])) {
        $caption[2][1] = '';
    } else {
        $caption[2][1] = trim($caption[2][1]);
        if($caption[2][1] !== '') {
            $caption[2][2] = $caption[2][1];
            $caption[2][1] = ' target="' . $caption[2][1] . '"';
        }
    }

    $caption[4] = isset($caption[4]) ? trim($caption[4]) : (isset($filedata['f_copyright']) ? $filedata['f_copyright'] : '');

    if($caption[4] === '') {
        $copyright  = returnTagContent($caption[3], 'copyright');
        $caption[3] = $copyright['new'];
        $caption[4] = $copyright['tag'];
    } else {
        $caption[3] = replace_cnt_template($caption[3], 'copyright', '');
    }

    if($array_index === 'NUM') {
        return $caption;
    } else {
        return $caption + array(
            'caption_text' => $caption[0],
            'caption_alt' => $caption[1],
            'caption_link' => $caption[2][0],
            'caption_target' => $caption[2][2],
            'caption_title' => $caption[3],
            'caption_copyright' => $caption[4]
        );
    }

}

function getFileDetails($file) {

    if(empty($file)) {
        return null;
    }

    $where = 'f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND ';

    if(is_intval($file)) {
        $where .= 'f_id='.intval($file);
    } elseif(strlen($file) === 32) {
        $where .= 'f_hash='._dbEscape($file);
    } else {
        return null;
    }

    $result = _dbGet('phpwcms_file', '*', $where, '', '', 1);
    if(isset($result[0]['f_id'])) {

        $result = $result[0];
        if(!empty($result['f_vars'])) {
            $result['f_vars'] = @unserialize($result['f_vars']);
            $lang = $GLOBALS['phpwcms']['default_lang'];

            if(count($GLOBALS['phpwcms']['allowed_lang']) > 1 && isset($result['f_vars'][ $lang ])) {

                $default = array(
                    'longinfo' => '',
                    'copyright' => '',
                    'title' => '',
                    'alt' => '',
                );

                $result['f_vars'][ $lang ] = array_merge($default, $result['f_vars'][ $lang ]);

                $result['f_longinfo'] = $result['f_vars'][ $lang ]['longinfo'];
                $result['f_copyright'] = $result['f_vars'][ $lang ]['copyright'];
                $result['f_title'] = $result['f_vars'][ $lang ]['title'];
                $result['f_alt'] = $result['f_vars'][ $lang ]['alt'];
            }
        }

        return $result;

    }

    return null;

}

function getClickZoomImageParameter($src='', $size='', $name='') {
    return 'show='.rawurlencode(base64_encode(serialize(array('src' => $src, 'attr' => $size, 'name' => $name))));
}

function getPageInfoGetValue($type='string') {
    // type can be
    // 'string' -> 'pageinfo=/...';
    // 'array' -> array('pageinfo'=>'/...')
    return ($type == 'string') ? 'pageinfo=' : array('pageinfo'=>'');
}

function _getFeUserLoginStatus() {
    $login_key = session_id();
    if(empty($login_key)) {
        return false;   // user is not logged in
    } elseif(empty($_SESSION[$login_key])) {
        return false;   // this is the false session and/or false user
    } elseif(isset($_GET[$login_key])) {
        return false;   // hm, somebody is trying to inject by GET in case register_globals ON
    } elseif(isset($_POST[$login_key])) {
        return false;   // hm, somebody is trying to inject by POST in case register_globals ON
    }
    headerAvoidPageCaching();
    return true;
}

function _checkFrontendUserLogin($user='', $pass='', $validate_db=array('userdetail'=>1, 'backenduser'=>1, 'email_login'=>0)) {
    if(empty($user) || empty($pass)) {
        return false;
    }
    // check against database
    if(!empty($validate_db['userdetail'])) {
        $sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_userdetail WHERE ';
        if(!empty($validate_db['email_login']) && is_valid_email($user)) {
            $sql .= '(';
            $sql .= 'detail_login=' . _dbEscape($user);
            $sql .= ' OR ';
            $sql .= 'LOWER(detail_email)=' . _dbEscape(strtolower($user));
            $sql .= ') AND ';
        } else {
            $sql .= '(detail_login=' . _dbEscape($user) . ') AND ';
        }
        $sql .= 'detail_password=' . _dbEscape($pass) . ' AND ';
        $sql .= 'detail_aktiv=1 LIMIT 1';
        $result = _dbQuery($sql);
    }
    // hm, seems no user found - OK test against cms users
    if(!empty($validate_db['backenduser']) && !isset($result[0])) {
        $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_user ';
        $sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_userdetail ON ';
        $sql .= 'usr_id = detail_pid WHERE ';
        if(!empty($validate_db['email_login']) && is_valid_email($user)) {
            $sql .= '(';
            $sql .= 'usr_login=' . _dbEscape($user);
            $sql .= ' OR ';
            $sql .= 'LOWER(usr_email)=' . _dbEscape(strtolower($user));
            $sql .= ') AND ';
        } else {
            $sql .= '(usr_login=' . _dbEscape($user) . ') AND ';
        }
        $sql .= 'usr_pass=' . _dbEscape($pass) . ' AND ';
        $sql .= 'usr_aktiv=1 AND usr_fe IN (0,2) LIMIT 1';
        $result = _dbQuery($sql);
    }
    return (isset($result[0]) && is_array($result)) ? $result[0] : false;
}

function _getFrontendUserBaseData($data) {
    // use vaid user data to set some base fe user data
    // like name, login, email
    $userdata = array('login'=>'', 'name'=>'', 'email'=>'', 'url'=>'', 'source'=>'', 'id'=>0);

    if(isset($data['usr_login'])) {
        $userdata['login'] = $data['usr_login'];
        $userdata['name'] = $data['usr_name'];
        $userdata['email'] = $data['usr_email'];
        $userdata['source'] = 'BACKEND';
        $userdata['id'] = $data['usr_id'];
        if (trim($data['detail_firstname'] . $data['detail_lastname'] . $data['detail_company'])) {
            $t = trim($data['detail_firstname'] . ' ' . $data['detail_lastname']);
            if (empty($t)) {
                $t = trim($data['detail_company']);
            }
            if ($t) {
                $userdata['name'] = $t;
            }
            $userdata['url'] = trim($data['detail_website']);
        }
    } elseif($data['detail_login']) {
        $t = trim($data['detail_firstname'] . ' ' . $data['detail_lastname']);
        if (empty($t)) {
            $t = $data['detail_company'];
        }
        $userdata['login'] = $data['detail_login'];
        $userdata['name'] = $t;
        $userdata['email'] = $data['detail_email'];
        $userdata['url'] = $data['detail_website'];
        $userdata['source'] = 'PROFILE';
        $userdata['id'] = $data['detail_id'];
    }
    return $userdata;
}

function _checkFrontendUserAutoLogin() {
    if(!_getFeUserLoginStatus() && session_id() && !empty($_COOKIE['phpwcmsFeLoginRemember']) && !isset($_POST['phpwcmsFeLoginRemember']) && !isset($_GET['phpwcmsFeLoginRemember'])) {
        $_loginData = explode('##-|-##', $_COOKIE['phpwcmsFeLoginRemember']);
        if(!empty($_loginData[0]) && !empty($_loginData[1])) {
            $_loginData['validate_db']['userdetail']    = empty($_loginData[2]) ? 0 : 1;
            $_loginData['validate_db']['backenduser']   = empty($_loginData[3]) ? 0 : 1;
            $_loginData['query_result'] = _checkFrontendUserLogin($_loginData[0], $_loginData[1], $_loginData['validate_db']);
            if($_loginData['query_result'] !== false && is_array($_loginData['query_result']) && count($_loginData['query_result'])) {
                if(isset($_loginData['query_result']['usr_login'])) {
                    $_SESSION[ session_id() ] = $_loginData['query_result']['usr_login'];
                } elseif($_loginData['query_result']['detail_login']) {
                    $_SESSION[ session_id() ] = $_loginData['query_result']['detail_login'];
                }
                $_SESSION[ session_id().'_userdata'] = _getFrontendUserBaseData($_loginData['query_result']);
            } else {
                unset($_COOKIE['phpwcmsFeLoginRemember']);
            }
        }
    }
    // logout
    if(session_id() && (isset($_POST['feLogout']) || isset($_GET['feLogout']))) {
        unset($_SESSION[ session_id() ]);
        setcookie('phpwcmsFeLoginRemember', '', time()-3600, '/',  getCookieDomain(), PHPWCMS_SSL, true);
    }
    define('FEUSER_LOGIN_STATUS', _getFeUserLoginStatus() );
}

function _getStructureLevelDisplayStatus($level_ID, $current_ID) {
    if($GLOBALS['content']['struct'][$level_ID]['acat_struct'] == $current_ID && $level_ID) {
        if($GLOBALS['content']['struct'][$level_ID]['acat_regonly'] && !FEUSER_LOGIN_STATUS) {
            return false;
        }
        if(empty($GLOBALS['content']['struct'][$level_ID]['acat_hidden'])) {
            return true;
        } elseif($GLOBALS['content']['struct'][$level_ID]["acat_hidden"] == 2 && isset($GLOBALS['LEVEL_KEY'][$level_ID])) {
            return true;
        }
        return false;
    }
    return false;
}

function setPageTitle($pagetitle, $cattitle, $articletitle, $title_order=NULL) {
    // get default pagetitle order value;
    if($title_order === NULL) {
        $title_order = empty($GLOBALS['pagelayout']['layout_title_order']) ? 0 : intval($GLOBALS['pagelayout']['layout_title_order']);
    }
    if(empty($GLOBALS['pagelayout']['layout_title_spacer'])) {
        $title_spacer = ' | ';
        $GLOBALS['pagelayout']['layout_title_spacer'] = $title_spacer;
    } else {
        $title_spacer = $GLOBALS['pagelayout']['layout_title_spacer'];
    }
    switch($title_order) {

        case 1:     $title = array($pagetitle, $articletitle, $cattitle); break;
        case 2:     $title = array($cattitle, $articletitle, $pagetitle); break;
        case 3:     $title = array($cattitle, $pagetitle, $articletitle); break;
        case 4:     $title = array($articletitle, $cattitle, $pagetitle); break;
        case 5:     $title = array($articletitle, $pagetitle, $cattitle); break;

        case 6:     $title = array($pagetitle, $cattitle);      break;
        case 7:     $title = array($pagetitle, $articletitle);  break;
        case 8:     $title = array($cattitle, $articletitle);   break;
        case 9:     $title = array($cattitle, $pagetitle);      break;
        case 10:    $title = array($articletitle, $cattitle);   break;
        case 11:    $title = array($articletitle, $pagetitle);  break;

        case 12:    $title = array($pagetitle);     break;
        case 13:    $title = array($cattitle);      break;
        case 14:    $title = array($articletitle);  break;

        default:    $title = array($pagetitle, $cattitle, $articletitle);

    }
    $title = array_diff($title, array('', NULL, false));
    $title = array_unique($title);
    $title = trim(implode($title_spacer, $title));
    return ($title === '') ? $pagetitle : $title;
}

function sanitize_replacement_tags( $string, $rt='', $bracket=array('{}', '[]') ) {
    if( $string === '' ) return '';
    if( is_string($bracket) ) {
        $bracket = array($bracket);
    }
    $tag = array();
    if($rt === '') {
        $tag[] = array('.*?', '.*?');
    } elseif( is_array($rt) ) {
        foreach($rt as $value) {
            $value = trim($value);
            if($value === '') continue;
            $tag[] = array($value . '.*?', $value);
        }
    } elseif( is_string($rt) ) {
        $rt = trim($rt);
        if($rt) {
            $tag[] = array($rt . '.*?', $rt);
        }
    }
    if( is_array($bracket) && count($bracket) && count($tag) ) {
        foreach($bracket as $value) {
            if(strlen($value) < 2) {
                continue;
            }
            $prefix = preg_quote(substr($value, 0, 1), '/');
            $suffix = preg_quote(substr($value, 1, 1), '/');
            foreach($tag as $row) {
                $string = preg_replace('/' . $prefix . $row[0] . $suffix . '(.*?)' . $prefix . '\/' . $row[1] . $suffix . '/si', '$1', $string);
            }
        }
    }
    return $string;
}

function parseLightboxCaption($caption='') {
    if(empty($caption)) return '';
    $caption = html_parser($caption);
    return html_specialchars($caption);
}

function get_article_morelink(& $article) {
    if($article['article_redirect']) {
        $link = explode(' ', $article['article_redirect']);
        if($link[0]) {
            $link[0] = str_replace('{SITE}', PHPWCMS_URL, $link[0]);
        }
        if(empty($link[1])) {
            $link[1] = '';
        }
    } else {
        $link[0] = rel_url(array(), array(), setGetArticleAid($article));
        $link[1] = '';
    }
    return $link;
}

function set_css_link($css='', $add_template_path=true) {
    $css_var = basename($css);
    $GLOBALS['block']['custom_htmlhead'][$css_var] = '  <link href="';
    if($add_template_path) {
        $GLOBALS['block']['custom_htmlhead'][$css_var] .= TEMPLATE_PATH;
    }
    $GLOBALS['block']['custom_htmlhead'][$css_var] .= $css.'" rel="stylesheet" type="text/css"'.HTML_TAG_CLOSE;
}

function getFrontendEditLink($type='', $id_1=0, $id_2=0, $uid=0) {

    // check if frontend edit link allowed
    if(!FE_EDIT_LINK) {
        return '';
    }

    // init JSLib
    initJSLib();

    // set specific frontend editing link
    set_css_link('inc_css/specific/frontend_edit.css');

    $link  = '';
    $href  = '';
    $title = '';

    switch($type) {

        case 'article':     $href  = 'do=articles&amp;p=2&amp;s=1&amp;id='.$id_1;
                            $title = '@@Backend: goto Article@@';
                            break;

        case 'summary':     $href = 'do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id='.$id_1;
                            $title = '@@Backend: edit Article Summary@@';
                            break;

        //enym new edit structure
        case 'structure':   if(isset($GLOBALS['content']['struct'][$id_1]['acat_struct'])) {
                                $href  = 'do=admin&amp;p=6&amp;struct=';
                                $href .= $id_1 ? $GLOBALS['content']['struct'][$id_1]['acat_struct'].'&amp;cat='.$id_1 : 'index';
                                $title = '@@Backend: edit Structure Level@@';
                            }
                            break;

        case 'CP':          if(!$_SESSION["wcs_user_admin"] && $id_1) {
                                $sql  = 'SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_article WHERE article_id='._dbEscape($id_1);
                                $sql .= ' AND article_uid='._dbEscape($_SESSION["wcs_user_id"]);
                                if(!_dbCount($sql)) {
                                    return '';
                                }
                            }
                            $href = 'do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id='.$id_1.'&amp;acid='.$id_2;
                            $title = '@@Backend: edit Content Part@@';
                            break;

        //enym new block to make news items editable
        case 'news':        $href  = 'do=articles&amp;p=3&amp;cntid='.$id_1.'&amp;action=edit';
                            $title = '@@Backend: edit News@@';
                            break;
        //end enym

        case 'module':      if(!$_SESSION["wcs_user_admin"] && $id_2) {
                                $sql  = 'SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_article WHERE article_id='._dbEscape($id_2);
                                $sql .= ' AND article_uid='._dbEscape($_SESSION["wcs_user_id"]);
                                if(!_dbCount($sql)) {
                                    return '';
                                }
                            }
                            $href = 'do=modules&amp;module='.$id_1;
                            $title = '@@Backend: goto Module@@';
                            break;
    }

    if($href) {

        $link  = '<a href="'.PHPWCMS_URL.'phpwcms.php?'.$href.'&'.FE_EDIT_LINK.'" target="backend" ';
        $link .= 'class="fe-link fe-'.$type.'" title="'.$title.'">';
        $link .= '<span>'.$title.'</span></a> ';

    }

    return $link;
}

function setGetArticleAid($data) {

    if(!empty($data['article_alias'])) {
        return $data['article_alias'];
    } elseif(isset($data['article_id'])) {
        return 'aid='.$data['article_id'];
    }
    return '';
}

/**
 * Return Link based on given structure level data
 *
 * @param mixed - structure level [integer] ID or Array(acat_redirect,acat_alias,acat_id,acat_name)
 * @param string - additional link attributes
 * @param string - link prefix
 * @param string - link suffix
 */
function getStructureLevelLink($acat, $attributes='', $prefix='', $suffix='') {

    if( !is_array($acat) ) {

        $id = is_int($acat) ? intval($acat) : -1;

        if($id >= 0 && isset($GLOBALS['content']['struct'][$id])) {

            $acat =& $GLOBALS['content']['struct'][$id];

        } else {

            $acat = trim($acat);

            if(empty($acat)) {
                return '';
            }

            return $prefix . html_specialchars( $acat ) . $suffix;

        }

    }

    if($acat['acat_redirect'] == false) {

        $target     = '';
        $link       = rel_url( array(), array('newsdetail'), empty($acat['acat_alias']) ? 'id='.$acat['acat_id'] : $acat['acat_alias'] );

    } else {

        $redirect   = get_redirect_link($acat['acat_redirect'], ' ', '');
        $target     = $redirect['target'];
        $link       = $redirect['link'];

    }

    return trim( '<a href="' . $link . '"' . $target . ' ' . $attributes ) . '>' . $prefix . html_specialchars( $acat['acat_name'] ) . $suffix . '</a>';

}

/**
 * Return menu or menu elements based on article menu title information
 * starting at given structure level ID, limited to single level (non-nested)
 *
 * @return mixed (array/string)
 * @param array
 **/
function getArticleMenu($data=array()) {

    global $content;
    global $aktion;

    $defaults = array(
        'level_id'              => 0,
        'class_active'          => array(0 => 'active', 1 => ''),
        'wrap_title_prefix'     => '',
        'wrap_title_suffix'     => '',
        'item_prefix'           => "\t",
        'item_suffix'           => '',
        'sort'                  => 'level',
        'item_tag'              => 'li',
        'wrap_tag'              => 'ul',
        'attribute_wrap_tag'    => '',
        'class_item_tag'        => '',
        'class_item_link'       => '',
        'class_first_item_tag'  => '',
        'class_last_item_tag'   => '',
        'return_format'         => 'string', // string or array
        'articlemenu_options'   => array(
            'enable'        => false,
            'hide_first'    => false,
            'image'         => false,
            'text'          => false,
            'width'         => 0,
            'height'        => 0,
            'crop'          => 0,
            'textlength'    => 0,
            'position'      => 'inside',
            'template'      => '<span class="amenu-extended">[IMAGE]<img src="{IMAGE}" alt="{IMAGE_NAME}" />[/IMAGE][TEXT]<span class="p">{TEXT}</span>[/TEXT]</span>'
        )
    );

    $data       = is_array($data) && count($data) ? array_merge($defaults, $data) : $defaults;
    $li         = array();
    $articles   = get_actcat_articles_data( $data['level_id'] );
    $key        = 0;
    $total      = count($articles) - 1;

    foreach($articles as $item) {

        if ($data['articlemenu_options']['hide_first']) {
            $data['articlemenu_options']['hide_first'] = false;
            continue;
        }

        $class      = array(
            $data['class_item_tag']
        );
        $class_a    = array(
            $data['class_item_link']
        );
        if($key === 0) {
            $class[] = $data['class_first_item_tag'];
        } elseif($key === $total) {
            $class[] = $data['class_last_item_tag'];
        }
        if($item['article_id'] == $aktion[1]) {
            if(!empty($data['class_active'][0])) {
                $class[] = $data['class_active'][0];
            }
            if(!empty($data['class_active'][1])) {
                $class_a[] = $data['class_active'][1]; // set active link class
            }
        }

        $item['outside']    = '';
        $item['inside']     = '';

        if($data['articlemenu_options']['enable']) {
            $item['img_src'] = '';
            $item['img_name'] = '';
            $item['amenu_text'] = '';
            if($data['articlemenu_options']['image'] && (!empty($item['article_image']['list_id']) || !empty($item['article_image']['id']))) {
                if(!empty($item['article_image']['list_usesummary']) && !empty($item['article_image']['id'])) {
                    $item['img_src'] = $item['article_image']['hash'].'.'.$item['article_image']['ext'];
                    $item['img_name'] = html($item['article_image']['name']);
                } elseif(!empty($item['article_image']['list_id'])) {
                    $item['img_src'] = $item['article_image']['list_hash'].'.'.$item['article_image']['list_ext'];
                    $item['img_name'] = html($item['article_image']['list_name']);
                }
                if($item['img_src']) {
                    $item['img_src'] = 'x'.$data['articlemenu_options']['height'].'x'.$data['articlemenu_options']['crop'].'/'.$item['img_src'];
                    $item['img_src'] = PHPWCMS_RESIZE_IMAGE . '/'.$data['articlemenu_options']['width'].$item['img_src'].'/'.rawurlencode($item['article_image']['name']);
                }
            }
            if($data['articlemenu_options']['text']) {
                switch($data['articlemenu_options']['text']) {
                    case 'description':
                        $item['amenu_text'] = html(getCleanSubString($item['article_description'], abs($data['articlemenu_options']['textlength']), $GLOBALS['template_default']['ellipse_sign'], $data['articlemenu_options']['textlength'] < 0 ? 'char' : 'word'));
                        break;
                    case 'menutitle':
                        $item['amenu_text'] = html(getCleanSubString($item['article_menutitle'], abs($data['articlemenu_options']['textlength']), $GLOBALS['template_default']['ellipse_sign'], $data['articlemenu_options']['textlength'] < 0 ? 'char' : 'word'));
                        break;
                    case 'teaser':
                        if($data['articlemenu_options']['textlength'] === 'HTML') {
                            $item['amenu_text'] = $item['article_summary'];
                        } else {
                            $item['amenu_text'] = trim(strip_tags($item['article_summary']));
                            $item['amenu_text'] = getCleanSubString($item['amenu_text'], abs($data['articlemenu_options']['textlength']), $GLOBALS['template_default']['ellipse_sign'], $data['articlemenu_options']['textlength'] < 0 ? 'char' : 'word');
                        }
                        break;
                }
            }
            $item[ $data['articlemenu_options']['position'] ] = $data['articlemenu_options']['template'];
            $item[ $data['articlemenu_options']['position'] ] = str_replace('{IMAGE_NAME}', $item['img_name'], $item[ $data['articlemenu_options']['position'] ]);
            $item[ $data['articlemenu_options']['position'] ] = render_cnt_template($item[ $data['articlemenu_options']['position'] ], 'IMAGE', $item['img_src']);
            $item[ $data['articlemenu_options']['position'] ] = render_cnt_template($item[ $data['articlemenu_options']['position'] ], 'TEXT', $item['amenu_text']);
        }

        if($item['article_redirect']) {
            $item['article_redirect'] = get_redirect_link($item['article_redirect'], ' ', '');
            $item['href'] = $item['article_redirect']['link'];
            $item['target'] = $item['article_redirect']['target'];
        }
        if(empty($item['href'])) {
            $item['href'] = rel_url(array(), array('newsdetail'), setGetArticleAid($item));
            $item['target'] = '';
        }

        $li[$key]  = $data['item_prefix'];
        $li[$key] .= '<'. $data['item_tag'] . ' class="' . implode(' ', get_unique_array($class)) . '">';
        $li[$key] .= '<a href="'.$item['href'].'" class="' . implode(' ', get_unique_array($class_a)) . '"' . $item['target'] . '>';
        $li[$key] .= $data['wrap_title_prefix'];
        $li[$key] .= html(getArticleMenuTitle($item));
        $li[$key] .= $data['wrap_title_suffix'];
        $li[$key] .= $item['inside'];
        $li[$key] .= '</a>';
        $li[$key] .= $item['outside'];
        $li[$key] .= '</'.$data['item_tag'].'>';
        $li[$key] .= $data['item_suffix'];

        $key++;

    }

    if($data['wrap_tag'] && count($li)) {

        array_unshift($li, '<'.trim($data['wrap_tag'].' '.trim($data['attribute_wrap_tag'])).'>');
        array_push($li, '</'.$data['wrap_tag'].'>');

    }

    return $data['return_format'] == 'string' ? implode(LF, $li) : $li;

}

/**
 * Return article menu title text
 *
 * @return string
 * @param array article data
 **/
function getArticleMenuTitle($data) {
    return empty($data['article_menutitle']) ? $data['article_title'] : $data['article_menutitle'];
}

/**
 * Add HTML Head meta tag
 */
function set_meta($name='', $content=null, $type=FALSE, $return=false, $allow_multiple=false) {
    if(empty($name) || !is_string($content)) {
        return NULL;
    }

    $meta  = '  <meta ';
    if($type === FALSE) {
        $meta .= 'name';
    } elseif($type == 'prop' || $type == 'property') {
        $meta .= 'property';
    } elseif($type == 'itemprop') {
        $meta .= 'itemprop';
    } elseif($type === TRUE || $type == 'http-equiv') {
        $meta .= 'http-equiv';
    } elseif($type) {
        $meta .= $type;
    } else {
        $meta .= 'name';
    }
    $meta .= '="' . $name . '" content="'.html_specialchars($content).'"'.HTML_TAG_CLOSE;

    if($return) {
        return $meta;
    }
    if($allow_multiple) {
        $name .= md5($content);
    }
    $GLOBALS['block']['custom_htmlhead']['meta.'.$name] = $meta;
}

/**
 * Add HTML Head link tag
 */
function set_link($attributes) {
    if(empty($attributes)) {
        return NULL;
    }
    if(is_array($attributes)) {
        if(!count($attributes)) {
            return NULL;
        }
        $hash = md5(implode('', $attributes));
        foreach($attributes as $key => $attribute) {
            $attributes[$key] = $key.'="' . html_specialchars($attribute) . '"';
        }
        $attributes = implode(' ', $attributes);
    } else {
        $hash = md5($attributes);
    }
    $GLOBALS['block']['custom_htmlhead']['link.'.$hash] = '  <link ' . $attributes . HTML_TAG_CLOSE;
}

/**
 * Search for replacement tag {LEVELX_ID} while X is integer like {LEVEL3_ID}
 * and return ID of the level or 0 if level is not set
 */
function replace_level_id($match) {
    $level = intval($match[1]);
    if(isset($GLOBALS['LEVEL_ID'][ $level ])) {
        return $GLOBALS['LEVEL_ID'][ $level ];
    }
    return 0;
}

/**
 * Render CSS related template code
 *
 * relative or absolute external css file
 * <!-- CSS: file.css -->
 *
 * put as inline CSS in separate <style> section in head
 * <!-- CSS: img {} -->
 */
function renderHeadCSS($css) {

    if(is_array($css) && isset($css[1])) {
        $css = $css[1];
    }

    $css = trim($css);

    if(empty($css)) {
        return '';
    }

    // test for .css as file extension
    $ext = which_ext($css);
    $key = md5($css);

    // if it is a css file register it as external css file
    if($ext == 'css') {

        $css = str_replace(' ', '%20', str_replace('{TEMPLATE}', TEMPLATE_PATH, html_specialchars($css)));

        if(strpos($css, '/') !== false) {
            $GLOBALS['block']['custom_htmlhead'][$key] = '  <link rel="stylesheet" type="text/css" href="' . $css . '"'.HTML_TAG_CLOSE;
        } else {
            $GLOBALS['block']['css'][$css] = $css;
        }

    // otherwise create a CSS <style> section
    } else {

        // add the same section only once
        if(isset($GLOBALS['block']['custom_htmlhead'][$key])) {
            return '';
        }

        $GLOBALS['block']['custom_htmlhead'][$key]  = '  <style type="text/css">' . LF . SCRIPT_CDATA_START . LF . '    ';
        $GLOBALS['block']['custom_htmlhead'][$key] .= str_replace('{TEMPLATE}', TEMPLATE_PATH, $css);
        $GLOBALS['block']['custom_htmlhead'][$key] .= LF . SCRIPT_CDATA_END . LF . '  </style>';

    }

    return '';

}

function parse_CKEDitor_resized_images() {

    $GLOBALS['content']['all'] = preg_replace_callback('/<img.*? src="(image_resized\.php\?format=.*?)".*? style="(.*?)px;"/', 'render_CKEDitor_resized_images', $GLOBALS['content']['all']);

}

function render_CKEDitor_resized_images($match) {

    $src = explode(PHPWCMS_FILES, $match[1]);
    if(empty($src[1])) {
        return $match[0];
    }

    $sizes  = explode(';', str_replace('px', '', $match[2]));
    $height = 0;
    $width  = 0;
    foreach($sizes as $size) {

        $size = trim($size);

        if(substr($size, 0, 6) == 'width:') {
            $width  = intval(trim(substr($size, 6)));
        } elseif(substr($size, 0, 7) == 'height:') {
            $height = intval(trim(substr($size, 7)));
        }

    }

    if(!$width || !$height) {
        return $match[0];
    }

    $src = PHPWCMS_RESIZE_IMAGE . '/' . $width . 'x' . $height . '/' . $src[1];
    if(strpos($match[0], 'alt="')) {
        preg_match('/alt="(.*?)"/', $match[0], $alt);
        $alt = empty($alt[1]) ? '' : ' alt="'.$alt[1].'"';
    } else {
        $alt = '';
    }

    return '<img src="'.$src.'"'.$alt.PHPWCMS_LAZY_LOADING;
}

function get_structurelevel_single_article_alias($article_cid=0) {

    if(!is_intval($article_cid)) {
        return '';
    }

    global $content;

    if(empty($content['struct'][ $article_cid ]['acat_articlecount'])) {
        $sql  = 'SELECT COUNT(article_id) FROM '.DB_PREPEND.'phpwcms_article ';
        $sql .= 'WHERE article_cid='.$article_cid.' AND article_aktiv=1 AND article_deleted=0';
        if(!PREVIEW_MODE) {
            $sql .= " AND article_begin < NOW() AND (article_end='0000-00-00 00:00:00' OR article_end > NOW())";
        }
        $content['struct'][ $article_cid ]['acat_articlecount'] = _dbCount($sql);
    }

    // reset article alias/ID
    if($content['struct'][ $article_cid ]['acat_articlecount'] === 1) {
        return empty($content['struct'][ $article_cid ]['acat_alias']) ? 'id='.$article_cid : $content['struct'][ $article_cid ]['acat_alias'];
    }

    return '';
}

/**
 * Parse and render text for device specific replacement tags
 *
 *
 * @param   string
 * @return  string
 */
function render_device($string) {

    if(empty($string)) {
        return '';
    }

    if(empty($GLOBALS['phpwcms']['render_device']) || (strpos($string, '<!--if:') === false && strpos($string, '<!--!if:') === false)) {
        return $string;
    }

    preg_match_all('/<!--!{0,1}if:(.+?)-->.*?<!--\/!{0,1}if-->/s', $string, $matches);

    if(!isset($matches[0][0])) {
        return $string;
    }

    // get agent information
    $user_agent = phpwcms_getUserAgent();

    // Test against
    $cache = array();

    foreach($matches[1] as $match) {

        $hash = md5($match);

        if(isset($cache[$hash])) {
            continue;
        }

        $cache[$hash]   = true;
        $validity       = array();
        $values         = explode(';', strtolower($match));

        // parameters (AND)
        foreach($values as $check) {

            // values (OR)
            $check  = explode(':', trim($check));
            $param  = trim($check[0]);
            $value  = isset($check[1]) ? explode(',', trim($check[1])) : array();

            // mobile
            if($param == 'mobile') {

                $validity[] = $user_agent['mobile'] ? 1 : 0;

            } elseif($param == 'desktop') {

                $validity[] = $user_agent['mobile'] ? 0 : 1;

            } elseif($param == 'platform') {

                // WinPhone, WinCE, Win, iOS, Mac, GoogleTV, Android,
                // BlackBerry, WebOS, Linux, Unix, Symbian, Other
                $validity[] = in_array(strtolower($user_agent['platform']), $value) ? 1 : 0;

            } elseif($param == 'device') {

                // Default, Other, Smartphone, Tablet, Desktop, TV
                $validity[] = in_array(strtolower($user_agent['device']), $value) ? 1 : 0;

            } elseif($param == 'browser') {

                // Other, Firefox, Chrome, Safari, IE, IEMobile, Opera, Mozilla
                $validity[] = in_array(strtolower($user_agent['agent']), $value) ? 1 : 0;

            } elseif($param == 'engine') {

                // Gecko, Other, WebKit, Opera, KHTML,
                $validity[] = in_array(strtolower($user_agent['engine']), $value) ? 1 : 0;

            } elseif($param == 'version') {

                // Only first value will be used for comparison
                // >Version, <Version, =Version, <=Version, >=Version
                if(preg_match('/^([<>=]+)(\d+)$/', current($value), $value)) {

                    $compare    = $value[1];
                    $value      = intval($value[2]);
                    if($compare == '=' && $user_agent['version'] == $value) {
                        $validity[] = 1;
                    } elseif($compare == '<' && $user_agent['version'] < $value) {
                        $validity[] = 1;
                    } elseif($compare == '>' && $user_agent['version'] > $value) {
                            $validity[] = 1;
                    } elseif($compare == '<=' && $user_agent['version'] <= $value) {
                        $validity[] = 1;
                    } elseif($compare == '>=' && $user_agent['version'] >= $value) {
                        $validity[] = 1;
                    } else {
                        $validity[] = 0;
                    }

                } else {
                    $validity[] = 0;
                }

            }

        }

        $match = preg_quote($match, '/');
        if(array_sum($validity) == count($values)) {
            // Valid – delete the !if and !if:default
            $string = preg_replace(
                array('/<!--if:'.$match.'-->(.*?)<!--\/if-->/s', '/<!--!if:'.$match.'-->.*?<!--\/!if-->/s', '/<!--!if:default-->.*?<!--\/!if-->/s'),
                array('$1', '', ''),
                $string
            );
        } else {
            // Invalid – keep the !if and !if:default
            $string = preg_replace(
                array('/<!--if:'.$match.'-->.*?<!--\/if-->/s', '/<!--!if:'.$match.'-->(.*?)<!--\/!if-->/s', '/<!--!if:default-->(.*?)<!--\/!if-->/s'),
                array('', '$1', '$1'),
                $string
            );
        }

    }

    return $string;
}

function get_PaginateNext($matches) {
    if(isset($GLOBALS['paginate_temp'])) {
        $GLOBALS['paginate_temp']['next'] = $matches[1];
    } else {
        $GLOBALS['_search_next_link_t'] = $matches[1];
    }
    return '{NEXT}';
}
function get_PaginatePrevious($matches) {
    if(isset($GLOBALS['paginate_temp'])) {
        $GLOBALS['paginate_temp']['prev'] = $matches[1];
    } else {
        $GLOBALS['_search_prev_link_t'] = $matches[1];
    }
    return '{PREV}';
}
function get_PaginateNavigate($matches) {
    if(isset($GLOBALS['paginate_temp'])) {
        $GLOBALS['paginate_temp']['navi'] = $matches[1];
    } else {
        $GLOBALS['_search_navi'] = $matches[1];
    }
    return '{NAVI}';
}

function get_link_anchor($matches) {
    $anchor = trim($matches[1]);
    $anchor = explode('#', $anchor);
    if(!empty($anchor[1])) {
        if($anchor[0] === '') {
            return '<a id="'.$anchor[1].'"'.(empty($GLOBALS['template_default']['classes']['link-anchor']) ? '' : ' class="'.$GLOBALS['template_default']['classes']['link-anchor'].'"').'></a>';
        }
        $anchor[0] = strtoupper($anchor[0]);
        if($anchor[0] === 'REL') {
            return rel_url().'#'.$anchor[1];
        } elseif($anchor[0] === 'ABS') {
            return abs_url().'#'.$anchor[1];
        }
    }
    return $matches[0];
}

function render_if_category($matches) {

    $cat_ids = convertStringToArray($matches[1]);

    if(!count($cat_ids)) {
        return '';
    }

    $current = intval($GLOBALS['content']['cat_id']);

    foreach($cat_ids as $id) {

        $id = intval($id);

        if($id === $current) {
            return str_replace('{IF_CAT_ID}', $id, $matches[2]);
        }

    }

    return '';
}

function render_if_not_category($matches) {

    $cat_ids = convertStringToArray($matches[1]);

    if(!count($cat_ids)) {
        return '';
    }

    $current = intval($GLOBALS['content']['cat_id']);
    $return = true;

    foreach($cat_ids as $id) {

        if(intval($id) === $current) {
            $return = false;
        }

    }

    return $return === true ? str_replace('{IF_NOTCAT_ID}', $current, $matches[2]) : '';
}

function get_css_keywords($text, $return_as_string=true) {

    if(empty($text) || !is_string($text) || strpos($text, '*CSS-') === false) {
        return $return_as_string ? '' : array();
    }

    preg_match_all('/\*CSS\-(.+?)\*/', $text, $css);
    if(isset($css[1]) && is_array($css[1])) {
        return $return_as_string ? implode(' ', $css[1]) : $css[1];
    }

    return $return_as_string ? '' : array();
}

function get_attr_data_gallery($group='', $prefix=' ', $suffix='') {

    $attr = ' data-'.$GLOBALS['template_default']['attributes']['data-gallery'].'="1"';
    if($group !== '') {
        $attr .= $prefix.'data-'.$GLOBALS['template_default']['attributes']['data-gallery'].'group="'.$group.'"'.$suffix;
    }
    return $attr;

}

/**
 * Init Parsedown or ParsedownExtra Class
 */
function init_markdown() {

    if(!isset($GLOBALS['phpwcms']['parsedown_class'])) {
        require_once(PHPWCMS_ROOT . '/include/inc_ext/parsedown/Parsedown.php');
        if (empty($GLOBALS['phpwcms']['markdown_extra'])) {
            $GLOBALS['phpwcms']['parsedown_class'] = new Parsedown();
        } else {
            require_once(PHPWCMS_ROOT . '/include/inc_ext/parsedown-extra/ParsedownExtra.php');
            $GLOBALS['phpwcms']['parsedown_class'] = new ParsedownExtra();
        }
    }

}

/**
 * Init Textile Class
 */
function init_textile() {

    if(!isset($GLOBALS['phpwcms']['textile_class'])) {
        require_once(PHPWCMS_ROOT . '/include/inc_ext/classTextile.php');
        $GLOBALS['phpwcms']['textile_class'] = new Textile();
    }

}