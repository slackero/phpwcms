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


// Teaser (link article) content part

// Check if custom array is given or parse it based on the default behavior
if(!isset($content['alink']['inject'])) {

    $content['alink'] = @unserialize($crow["acontent_form"]);

    if(!isset($content['alink']['alink_id'])) {
        $content['alink']['alink_id'] = explode(':', $crow['acontent_alink']);
    }
}

if((is_array($content['alink']['alink_id']) && count($content['alink']['alink_id'])) || (!empty($content['alink']['alink_type']) && ((is_array($content['alink']['alink_level']) && count($content['alink']['alink_level'])) || (isset($content['alink']['alink_category']) && count($content['alink']['alink_category']))))) {

    if(!isset($content['UNIQUE_ALINK'])) {
        $content['UNIQUE_ALINK'] = array();
    }

    $crow['acontent_template_listmode'] = empty($crow['acontent_template_listmode']) ? false : true;

    if($crow['acontent_template_listmode'] && !empty($content['alink']['alink_template']) && is_file(PHPWCMS_TEMPLATE.'inc_cntpart/teaser/list.'.$content['alink']['alink_template'])) {

        $content['alink']['alink_template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/teaser/list.'.$content['alink']['alink_template']) );

    } elseif(!empty($content['alink']['alink_template']) && is_file(PHPWCMS_TEMPLATE.'inc_cntpart/teaser/'.$content['alink']['alink_template'])) {

        $content['alink']['alink_template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/teaser/'.$content['alink']['alink_template']) );

    } elseif($crow['acontent_template_listmode'] && is_file(PHPWCMS_TEMPLATE.'inc_default/list.teaser.tmpl')) {

        $content['alink']['alink_template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/list.teaser.tmpl') );

    } elseif(is_file(PHPWCMS_TEMPLATE.'inc_default/teaser.tmpl')) {

        $content['alink']['alink_template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/teaser.tmpl') );

    } else {

        $content['alink']['alink_template']  = '<!--TEASER_HEAD_START//--><ul{LINK_ARTICLE_CLASS}><!--TEASER_HEAD_END//-->';
        $content['alink']['alink_template'] .= '<!--TEASER_ENTRY_START//--><li><a href="{ARTICLELINK}">{TITLE}</a></li><!--TEASER_ENTRY_END//-->';
        $content['alink']['alink_template'] .= '<!--TEASER_ENTRY_SPACER_START//--><!--TEASER_ENTRY_SPACER_END//-->';
        $content['alink']['alink_template'] .= '<!--TEASER_ROW_SPACER_START//--><!--TEASER_ROW_SPACER_END//-->';
        $content['alink']['alink_template'] .= '<!--TEASER_FOOTER_START//--></ul><!--TEASER_FOOTER_END//-->';
        $content['alink']['alink_template'] .= '<!--TEASER_COLUMN_OVERWRITE_START//--><!--TEASER_COLUMN_OVERWRITE_END//-->';

    }

    $content['alink']['tags_group_by']          = '';
    $content['alink']['tags_where']             = '';
    $content['alink']['date_basis']             = 'article_date';
    $content['alink']['alink_categoryalias']    = empty($content['alink']['alink_categoryalias']) ? 0 : 1;
    $content['alink']['alink_category_count']   = empty($content['alink']['alink_category']) ? 0 : count($content['alink']['alink_category']);

    $alink_sql = 'SELECT ar.*, UNIX_TIMESTAMP(ar.article_tstamp) AS article_date ';

    // select by category
    if($content['alink']['alink_category_count']) {

        // pcat.cat_name is used to check against having keywords for OR, AND, NOT
        $alink_sql .= ', COUNT(*) AS matched_articles, pcat.cat_name FROM '.DB_PREPEND.'phpwcms_article ar ';

        $content['alink']['tags_sql'] = array();

        foreach($content['alink']['alink_category'] as $value) {

            $content['alink']['tags_sql'][] = _dbEscape($value);

        }

        // JOIN with tags/categories for articles
        $alink_sql .= "LEFT JOIN ".DB_PREPEND."phpwcms_categories pcat ON (pcat.cat_type='article' AND pcat.cat_pid=ar.article_id ";
        $alink_sql .= 'AND pcat.cat_name IN (' . implode(',', $content['alink']['tags_sql']) . '))';

        if($content['alink']['alink_andor'] === 'NOR') {

            $content['alink']['tags_where'] .= 'AND pcat.cat_name IS NULL ';

        } else {

            $content['alink']['tags_where'] .= 'AND pcat.cat_name IS NOT NULL ';

        }

        // group by article ID
        $content['alink']['tags_group_by'] = ' GROUP BY ar.article_id';

    } else {

        $alink_sql .= ', 1 AS matched_articles FROM '.DB_PREPEND.'phpwcms_article ar ';

    }

    $alink_sql .= 'WHERE ar.article_aktiv=1 AND ar.article_deleted=0 AND ar.article_noteaser=0 ';
    if(!PREVIEW_MODE) {
        $alink_sql .= "AND ar.article_begin < NOW() AND (ar.article_end='0000-00-00 00:00:00' OR ar.article_end > NOW()) ";
    }

    // add possible WHERE clauses when tags/categories are used
    $alink_sql .= $content['alink']['tags_where'];

    if(empty($content['alink']['alink_type'])) {

        if(!empty($content['alink']['alink_unique']) && count($content['UNIQUE_ALINK'])) {

            $content['alink']['alink_id'] = array_diff($content['alink']['alink_id'], $content['UNIQUE_ALINK']);
            $alink_sql .= count($content['alink']['alink_id']) ? 'AND ar.article_id IN ('.implode(',', $content['alink']['alink_id']) . ')' : ' AND 0 ';

        } else {
            $alink_sql .= 'AND ar.article_id IN ('.implode(',', $content['alink']['alink_id']) . ')';
        }

        // group by - when used with categories/tags
        $alink_sql .= $content['alink']['tags_group_by'];

    } else {

        if(is_array($content['alink']['alink_level']) && count($content['alink']['alink_level'])) {

            $alink_sql .= 'AND ar.article_cid IN ('.implode(',', $content['alink']['alink_level']) . ')';
            if(!empty($content['alink']['alink_unique']) && count($content['UNIQUE_ALINK'])) {
                $alink_sql .= ' AND ar.article_id NOT IN ('.implode(',', $content['UNIQUE_ALINK']) . ')';
            }
        }

        // group by - when used with categories/tags
        $alink_sql .= $content['alink']['tags_group_by'];

        // don't use SQL UNION
        $sql_union = '';

        // add prio sorting value
        if(empty($content['alink']['alink_prio'])) {
            $sql_prio       = '';
            $sql_union_prio = '';
        } else {
            $sql_prio       = 'ar.article_priorize DESC, ';
            $sql_union_prio = 'article_priorize DESC, ';
        }

        switch($content['alink']['alink_type']) {

            case 1:     // create date, DESC
                        $content['alink']['date_basis'] = 'article_created';
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_created DESC";
                        break;

            case 2:     // create date, ASC
                        $content['alink']['date_basis'] = 'article_created';
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_created ASC";
                        break;

            case 3:     // change date, DESC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_tstamp DESC";
                        break;

            case 4:     // change date, ASC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_tstamp ASC";
                        break;

            case 5:     // live date, DESC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_begin DESC";
                        break;

            case 6:     // live date, ASC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_begin ASC";
                        break;

            case 7:     // kill date, DESC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_end DESC";
                        break;

            case 8:     // kill date, ASC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_end ASC";
                        break;

            case 18:    // article title, DESC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_title DESC";
                        break;

            case 19:    // article title, ASC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_title ASC";
                        break;

            case 22:    // keywords/tags, DESC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_keyword DESC";
                        break;

            case 23:    // keywords/tags, ASC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_keyword ASC";
                        break;

            case 24:    // article sort, DESC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_sort DESC";
                        break;

            case 25:    // article sort, ASC
                        $alink_sql .= " ORDER BY ".$sql_prio."ar.article_sort ASC";
                        break;

            case 9:     // random
                        $alink_sql .= " ORDER BY RAND()";
                        break;

            case 10:    // random, create date, DESC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_created DESC";
                        break;

            case 11:    // random, create date, ASC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_created ASC";
                        break;

            case 12:    // random, change date, DESC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_tstamp DESC";
                        break;

            case 13:    // random, change date, ASC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_tstamp ASC";
                        break;

            case 14:    // random, live date, DESC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_begin DESC";
                        break;

            case 15:    // random, live date, ASC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_begin ASC";
                        break;

            case 16:    // random, kill date, DESC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_end DESC";
                        break;

            case 17:    // random, kill date, ASC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_end ASC";
                        break;

            case 20:    // random, article title, DESC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_title DESC";
                        break;

            case 21:    // random, article title, ASC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_title ASC";
                        break;

            case 26:    // random, article sort, DESC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_sort DESC";
                        break;

            case 27:    // random, article sort, ASC
                        $alink_sql .= " ORDER BY RAND()";
                        $sql_union .= " ORDER BY ".$sql_union_prio."article_sort ASC";
                        break;

        }

        if(!empty($content['alink']['alink_max']) && intval($content['alink']['alink_max'])) {
            $alink_sql .= " LIMIT ".intval($content['alink']['alink_max']);
        }

        if($sql_union != '') {

            $alink_sql = '('.$alink_sql.') UNION DISTINCT ('.$alink_sql.')'.$sql_union;
            if(!empty($content['alink']['alink_max']) && intval($content['alink']['alink_max'])) {
                $alink_sql .= " LIMIT ".intval($content['alink']['alink_max']);
            }

        }
    }

    $content['alink']['tr'] = array();

    $content['alink']['alink_template_head']    = get_tmpl_section('TEASER_HEAD', $content['alink']['alink_template']);
    $content['alink']['alink_template_footer']  = get_tmpl_section('TEASER_FOOTER', $content['alink']['alink_template']);
    $content['alink']['alink_template_entry']   = get_tmpl_section('TEASER_ENTRY', $content['alink']['alink_template']);
    $content['alink']['alink_template_space']   = get_tmpl_section('TEASER_ENTRY_SPACER', $content['alink']['alink_template']);
    $content['alink']['alink_template_row']     = get_tmpl_section('TEASER_ROW_SPACER', $content['alink']['alink_template']);
    $content['alink']['alink_template_column']  = trim( get_tmpl_section('TEASER_COLUMN_OVERWRITE', $content['alink']['alink_template']) );
    $content['alink']['alink_template_head']    = str_replace('{LINK_ARTICLE_CLASS}', get_class_attrib($template_default["article"]["link_article_class"]), $content['alink']['alink_template_head']);

    $content['alink']['result'] = _dbQuery($alink_sql);

    if(isset($content['alink']['result'][0])) {

        // lets handle columns and rows
        if($content['alink']['alink_template_column'] !== '') {
            $content['alink']['alink_columns']  = abs(intval($content['alink']['alink_template_column']));
        } elseif(empty($content['alink']['alink_columns'])) {
            $content['alink']['alink_columns']  = 0;
        }

        $content['alink']['column']     = 1;
        $content['alink']['row']        = 1;
        $content['alink']['row_space']  = false;

        if(!empty($content['alink']['alink_type'])) {

            $content['alink']['alink_id'] = array();

            if($content['alink']['alink_category_count'] && substr($content['alink']['alink_andor'], -2) !== 'OR') {

                foreach($content['alink']['result'] as $value) {

                    if($content['alink']['alink_andor'] === 'AND' && $content['alink']['alink_category_count'] !== (int)$value['matched_articles']) {
                        continue;
                    } elseif($content['alink']['alink_andor'] === 'NOT' && $content['alink']['alink_category_count'] === (int)$value['matched_articles']) {
                        continue;
                    }

                    $content['alink']['alink_id'][] = $value['article_id'];
                }

            } else {

                // Anay value is valid
                foreach($content['alink']['result'] as $value) {
                    $content['alink']['alink_id'][] = $value['article_id'];
                }
            }

        }

        // Max teaser items
        $content['alink']['max_items'] = count($content['alink']['alink_id']);

        foreach($content['alink']['alink_id'] as $key => $value) {

            $content['UNIQUE_ALINK'][$value] = $value; //save UNIQUE Teaser ID

            foreach($content['alink']['result'] as $row) {

                if($value == $row['article_id'] && isset($content['struct'][ $row['article_cid'] ])) {

                    // enable frontend edit link
                    if(FE_EDIT_LINK && ($_SESSION["wcs_user_admin"] || $_SESSION["wcs_user_id"] == $row["article_uid"])) {
                        $content['alink']['tr'][$key] = getFrontendEditLink('summary', $row["article_id"]);
                    } else {
                        $content['alink']['tr'][$key] = '';
                    }

                    // set columns/row class
                    if($content['alink']['alink_columns'] > 0) {

                        // check if the current teaser will be on a new row
                        if($content['alink']['row_space'] && $content['alink']['alink_template_row']) {
                            $content['alink']['tr'][$key] .= str_replace('{ROW}', $content['alink']['row'], $content['alink']['alink_template_row']);
                        }

                        $content['alink']['column_current'] = $content['alink']['column'];

                        // now make the tests
                        if($content['alink']['column'] % $content['alink']['alink_columns']) {
                            // New column
                            $content['alink']['column']++;
                            $content['alink']['row_space'] = false;
                            //$content['alink']['column_minus'] = 1;
                            $content['alink']['row_minus'] = 0;

                        } else {
                            // New row
                            $content['alink']['column'] = 1;
                            $content['alink']['row']++;
                            $content['alink']['row_space'] = true;
                            //$content['alink']['column_minus'] = 0;
                            $content['alink']['row_minus'] = 1;
                        }

                        if($content['alink']['column'] > 2 || ($content['alink']['row_space'] && $content['alink']['column'] === 1)) {
                            $content['alink']['tr'][$key] .= $content['alink']['alink_template_space'];
                        }

                    } else {

                        $content['alink']['row_space'] = false;

                        if($content['alink']['column'] > 1) {
                            $content['alink']['tr'][$key] .= $content['alink']['alink_template_space'];
                        }

                        $content['alink']['column_current'] = $content['alink']['column'];

                        $content['alink']['column']++;

                        //$content['alink']['column_minus'] = 1;
                        $content['alink']['row_minus'] = 0;

                    }

                    $content['alink']['tr'][$key]  .= $content['alink']['alink_template_entry'];

                    $content['alink']['tr'][$key]   = str_replace('{ARTICLEID}', $row['article_id'], $content['alink']['tr'][$key]);
                    $content['alink']['tr'][$key]   = str_replace('{CATEGORYID}', $row['article_cid'], $content['alink']['tr'][$key]);
                    $content['alink']['tr'][$key]   = str_replace('{COLUMN}', $content['alink']['column_current'], $content['alink']['tr'][$key]);
                    $content['alink']['tr'][$key]   = str_replace('{ROW}', $content['alink']['row']-$content['alink']['row_minus'], $content['alink']['tr'][$key]);

                    $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'MENUTITLE', html($row['article_menutitle']));
                    $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'TITLE', html($row['article_title']));
                    $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'SUBTITLE', html($row['article_subtitle']));
                    $content['alink']['tr'][$key]   = render_cnt_date($content['alink']['tr'][$key], $row[ $content['alink']['date_basis'] ], phpwcms_strtotime($row['article_begin']), phpwcms_strtotime($row['article_end']));
                    $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'PRIO', empty($row['article_priorize']) ? '' : $row['article_priorize']);
                    $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'ACTIVE', isset($content["article_id"]) && $content["article_id"] == $row['article_id'] ? 'active' : '');


                    $row['article_image'] = @unserialize($row['article_image']);
                    $row['article_image'] = setArticleSummaryImageData($row['article_image']);

                    if(!empty($row['article_image']['list_caption'])) {
                        $content['alink']['caption']            = getImageCaption(array('caption' => $row['article_image']['list_caption'], 'file' => $row['article_image']['list_id']));
                        $row['article_image']['list_caption']   = html($content['alink']['caption'][0]);
                        $content['alink']['caption'][3]         = html($content['alink']['caption'][3]);
                        $content['alink']['caption'][1]         = html($content['alink']['caption'][1]);
                    } else {
                        $row['article_image']['list_caption']   = '';
                        $content['alink']['caption']            = array('', '', array('', ''), '', '');
                    }

                    // article list image
                    if(strpos($content['alink']['tr'][$key], 'IMAGE') !== false) {

                        $img_thumb_name     = '';
                        $img_thumb_rel      = '';
                        $img_thumb_abs      = '';
                        $img_thumb_width    = 0;
                        $img_thumb_height   = 0;
                        $img_thumb_id       = empty($row['article_image']['list_id']) ? '' : $row['article_image']['list_id'];
                        $img_thumb_hash     = '';
                        $img_thumb_crop     = 0;
                        $img_thumb_ext      = 'jpg';
                        $row['article_image']['list_name'] = html($row['article_image']['list_name']);

                        // check if image available
                        if($img_thumb_id) {

                            if(!empty($content['alink']['alink_width'])) {
                                $row['article_image']['list_width']     = $content['alink']['alink_width'];
                                $img_thumb_width                        = $row['article_image']['list_width'];
                            }
                            if(!empty($content['alink']['alink_height'])) {
                                $row['article_image']['list_height']    = $content['alink']['alink_height'];
                                $img_thumb_height                       = $row['article_image']['list_height'];
                            }

                            // build image/image link
                            $content['alink']['poplink']            = '';
                            $thumb_image                            = false;
                            $thumb_img                              = '';
                            $img_thumb_hash                         = empty($row['article_image']['list_hash']) ? '' : $row['article_image']['list_hash'];
                            $img_thumb_crop                         = empty($content['alink']['alink_crop']) ? 0 : 1;

                            if(strpos($content['alink']['tr'][$key], 'cmsimage.php') !== false && $img_thumb_hash) {

                                $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'IMAGE', $img_thumb_hash);

                            } elseif($img_thumb_hash) {

                                $thumb_image = get_cached_image(array(
                                    "target_ext"    =>  $row['article_image']['list_ext'],
                                    "image_name"    =>  $row['article_image']['list_hash'] . '.' . $row['article_image']['list_ext'],
                                    "max_width"     =>  $row['article_image']['list_width'],
                                    "max_height"    =>  $row['article_image']['list_height'],
                                    "thumb_name"    =>  md5($row['article_image']['list_hash'].$row['article_image']['list_width'].$row['article_image']['list_height'].$phpwcms['sharpen_level'].$img_thumb_crop.$phpwcms['colorspace']),
                                    'crop_image'    =>  $img_thumb_crop
                                ));

                                if($thumb_image != false) {

                                    $thumb_img  = '<img src="' . $thumb_image['src'] .'" '.$thumb_image[3];
                                    $thumb_img .= ' data-image-id="'.$img_thumb_id.'" data-image-hash="'.$img_thumb_hash.'"';
                                    $thumb_img .= ' alt="'.($content['alink']['caption'][1] ? $content['alink']['caption'][1] : $row['article_image']['list_name']).'"';
                                    if($content['alink']['caption'][3]) {
                                        $thumb_img .= ' title="'.$content['alink']['caption'][3].'"';
                                    }
                                    $thumb_img .= ' />';

                                    $img_thumb_name     = $thumb_image[0];
                                    $img_thumb_rel      = $thumb_image['src'];
                                    $img_thumb_abs      = PHPWCMS_URL.$thumb_image['src'];
                                    $img_thumb_width    = $thumb_image[1];
                                    $img_thumb_height   = $thumb_image[2];
                                    $img_thumb_ext      = which_ext($thumb_image[0]);
                                }
                            }

                            $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'IMAGE', $thumb_img);

                        } else {

                            $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'IMAGE', '');

                        }

                        // replace thumbnail and zoom image information
                        $content['alink']['tr'][$key] = str_replace(
                            array(
                                '{THUMB_NAME}',
                                '{THUMB_REL}',
                                '{THUMB_ABS}',
                                '{THUMB_WIDTH}',
                                '{THUMB_HEIGHT}',
                                '{THUMB_ID}',
                                '{THUMB_HASH}',
                                '{THUMB_CROP}',
                                '{THUMB_EXT}',
                                '{IMAGE_NAME}'
                            ),
                            array(
                                $img_thumb_name,
                                $img_thumb_rel,
                                $img_thumb_abs,
                                $img_thumb_width,
                                $img_thumb_height,
                                $img_thumb_id,
                                $img_thumb_hash,
                                $img_thumb_crop,
                                $img_thumb_ext,
                                $row['article_image']['list_name']
                            ),
                            $content['alink']['tr'][$key]
                        );

                    }

                    // article detail image
                    if(strpos($content['alink']['tr'][$key], 'IMAGE_DETAIL') !== false && isset($row['article_image']['hash'])) {

                        $row['article_image']['name'] = html_specialchars($row['article_image']['name']);

                        if(!empty($content['alink']['alink_width'])) {
                            $row['article_image']['width'] = $content['alink']['alink_width'];
                        }
                        if(!empty($content['alink']['alink_height'])) {
                            $row['article_image']['height'] = $content['alink']['alink_height'];
                        }

                        // build image/image link
                        $row['article_image']['poplink']    = '';
                        $row['article_image']['detail']     = false;
                        $row['article_image']['img']        = '';
                        $row['article_image']['crop']       = empty($content['alink']['alink_crop']) ? 0 : 1;
                        if($row['article_image']['caption']) {
                            $row['article_image']['caption']    = getImageCaption($row['article_image']['caption']);
                            $row['article_image']['caption'][0] = html_specialchars($row['article_image']['caption'][0]);
                            $row['article_image']['caption'][3] = html_specialchars($row['article_image']['caption'][3]);
                            $row['article_image']['caption'][1] = html_specialchars($row['article_image']['caption'][1]);
                        } else {
                            $row['article_image']['caption'] = array(0 => '', 1 => '', 2 => array('', ''), 3 => '', 4 => '', 5 => '', 6 => '');
                        }

                        $row['article_image']['detail'] = get_cached_image(array(
                            "target_ext"    =>  $row['article_image']['ext'],
                            "image_name"    =>  $row['article_image']['hash'] . '.' . $row['article_image']['ext'],
                            "max_width"     =>  $row['article_image']['width'],
                            "max_height"    =>  $row['article_image']['height'],
                            "thumb_name"    =>  md5($row['article_image']['hash'].$row['article_image']['width'].$row['article_image']['height'].$phpwcms['sharpen_level'].$row['article_image']['crop'].$phpwcms['colorspace']),
                            'crop_image'    =>  $row['article_image']['crop']
                        ));

                        if($row['article_image']['detail'] != false) {

                            $row['article_image']['img']  = '<img src="' . $row['article_image']['detail']['src'] .'" '.$row['article_image']['detail'][3];
                            $row['article_image']['img'] .= ' data-detail-id="'.$row['article_image']['id'].'" data-detail-hash="'.$row['article_image']['hash'].'"';
                            $row['article_image']['img'] .= ' alt="'.($row['article_image']['caption'][1] ? $row['article_image']['caption'][1] : $row['article_image']['name']).'"';
                            if($row['article_image']['caption'][3]) {
                                $row['article_image']['img'] .= ' title="'.$row['article_image']['caption'][3].'"';
                            }
                            $row['article_image']['img'] .= ' />';

                        }

                        // replace thumbnail and zoom image information
                        $content['alink']['tr'][$key] = str_replace(
                            array(
                                '{IMAGE_DETAIL_ID}',
                                '{IMAGE_DETAIL_HASH}',
                                '{IMAGE_DETAIL_EXT}',
                                '{IMAGE_DETAIL_NAME}'
                            ),
                            array(
                                $row['article_image']['id'],
                                $row['article_image']['hash'],
                                $row['article_image']['ext'],
                                $row['article_image']['name']
                            ),
                            $content['alink']['tr'][$key]
                        );

                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'CAPTION_DETAIL_SUPPRESS', empty($row['article_image']['caption_suppress']) ? '' : ' ');
                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'IMAGE_DETAIL', $row['article_image']['img']);
                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'CAPTION_DETAIL', $row['article_image']['caption'][0]);
                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'IMAGE_TITLE', $row['article_image']['caption'][3]);
                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'ALT', $row['article_image']['caption'][1]);
                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'URL', $row['article_image']['caption'][2][0]);
                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'URL_TARGET', $row['article_image']['caption'][2][1]);
                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'COPYRIGHT', $row['article_image']['caption'][4]);

                    } else {

                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'IMAGE_DETAIL', '');

                    }

                    // article summary
                    if(strpos($content['alink']['tr'][$key], 'SUMMARY_RAW') !== false) {

                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'SUMMARY_RAW', empty($content['alink']['alink_hidesummary']) ? $row['article_summary'] : '');

                    }

                    if(strpos($content['alink']['tr'][$key], 'SUMMARY') !== false) {
                        if(empty($content['alink']['alink_hidesummary'])) {
                            if(empty($content['alink']['alink_wordlimit']) && !empty($row['article_image']['list_maxwords'])) {
                                $content['alink']['alink_wordlimit'] = $row['article_image']['list_maxwords'];
                            }
                            $row['article_summary'] = empty($content['alink']['alink_allowedtags']) ? strip_tags($row['article_summary']) : strip_tags($row['article_summary'], $content['alink']['alink_allowedtags']);
                            if(!empty($content['alink']['alink_wordlimit'])) {
                                $row['article_summary'] = getCleanSubString($row['article_summary'], abs($content['alink']['alink_wordlimit']), $template_default['ellipse_sign'], $content['alink']['alink_wordlimit'] < 0 ? 'char' : 'word');
                            }
                        } else {
                            $row['article_summary'] = '';
                        }
                        $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'SUMMARY', $row['article_summary']);
                    }

                    // $content['struct'][ $row['article_cid'] ]['acat_articlecount']
                    // count the articles per category and decide where to link on that basis
                    // store it if used once
                    if($content['alink']['alink_categoryalias'] && ($row['article_structalias'] = get_structurelevel_single_article_alias($row['article_cid']))) {

                        $row['article_alias'] = $row['article_structalias'];

                    }

                    // link to article detail
                    if($row['article_morelink']) {

                        if($row['article_redirect']) {
                            $row['article_redirect'] = get_redirect_link($row['article_redirect'], ' ', '');
                            $row['href'] = $row['article_redirect']['link'];
                            $row['target'] = $row['article_redirect']['target'];
                        }
                        if(empty($row['href'])) {
                            $row['href'] = rel_url(array(), array('newsdetail'), setGetArticleAid($row));
                            $row['target'] = '';
                        }

                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'ARTICLELINK', $row['href']);
                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'ARTICLELINK_TARGET', $row['target']);

                    } else {

                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'ARTICLELINK', '');
                        $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'ARTICLELINK_TARGET', '');

                    }

                    // article category
                    $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'CATEGORY', html_specialchars($content['struct'][ $row['article_cid'] ]['acat_name']));

                    // Image Caption, Alt, Title
                    $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'CAPTION_SUPPRESS', empty($row['article_image']['list_caption_suppress']) ? '' : ' ');
                    $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'CAPTION', $row['article_image']['list_caption']);
                    $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'CAPTION_ALT', $content['alink']['caption'][1]);
                    $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'CAPTION_URL', $content['alink']['caption'][2][0]);
                    $content['alink']['tr'][$key]   = render_cnt_template($content['alink']['tr'][$key], 'CAPTION_TITLE', $content['alink']['caption'][3]);

                    if (!empty($row['article_meta'])) {
                        if (is_string($row['article_meta'])) {
                            $row['article_meta'] = json_decode($row['article_meta'], true);
                        }
                        $row['article_meta'] = is_array($row['article_meta']) ? array_merge(get_default_article_meta(), $row['article_meta']) : get_default_article_meta();
                    } else {
                        $row['article_meta'] = get_default_article_meta();
                    }

                    $row['article_meta']['class'] = trim(get_css_keywords($row['article_keyword']) . ' ' . $row['article_meta']['class']);

                    // article class based on keyword *CSS-classname*
                    $content['alink']['tr'][$key] = render_cnt_template($content['alink']['tr'][$key], 'CLASS', $row['article_meta']['class']);

                    break;

                }
            }
        }
    }

    // combine all teaser items
    if(count($content['alink']['tr'])) {
        $content['alink']['tr']     = implode(LF, $content['alink']['tr']);
        $content['alink']['teaser'] = ' ';
    } else {
        $content['alink']['tr']     = '';
        $content['alink']['teaser'] = '';
    }

    // put all template and content into one
    $content['alink']['alink_template'] = LF . $content['alink']['alink_template_head'] . $content['alink']['tr'] . $content['alink']['alink_template_footer'] . LF;

    // render teaser elements - throw everything between [TEASER]...[/TEASER]
    $content['alink'] = render_cnt_template($content['alink']['alink_template'], 'TEASER', $content['alink']['teaser']);

    $content['alink'] = render_cnt_template($content['alink'], 'TITLE', html($crow['acontent_title']));
    $content['alink'] = render_cnt_template($content['alink'], 'SUBTITLE', html($crow['acontent_subtitle']));
    $content['alink'] = render_cnt_template($content['alink'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
    $content['alink'] = render_cnt_template($content['alink'], 'ATTR_ID', html($crow['acontent_attr_id']));
    $content['alink'] = str_replace('{ID}', $crow["acontent_id"], $content['alink']);

    $CNT_TMP .= $content['alink'];

}

// unset the CP related value
unset($content['alink']);
