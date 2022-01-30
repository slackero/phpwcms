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

// News
$news = @unserialize($crow["acontent_form"]);

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/news.tmpl')) {
    $news['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/news.tmpl') );
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/news/'.$crow["acontent_template"])) {
    $news['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/news/'.$crow["acontent_template"]) );
} else {
    $news['template'] = '';
}

// build SQL query first
$news['sql_where'] = array();
$news['now'] = now();
$news['list_mode'] = true;
$news['listing_page'] = array();

$news['cnt_ts_livedate'] = 'IF(UNIX_TIMESTAMP(pc.cnt_livedate) > 0, UNIX_TIMESTAMP(pc.cnt_livedate), pc.cnt_created)';
$news['cnt_ts_killdate'] = 'IF(UNIX_TIMESTAMP(pc.cnt_killdate) > 0, UNIX_TIMESTAMP(pc.cnt_killdate), ' . ( $news['now'] + 3600 ) . ')'; // set end date to current time + 1 hour
$news['cnt_ts_sortdate'] = 'IF(pc.cnt_sort=0, IF(UNIX_TIMESTAMP(pc.cnt_livedate) > 0, UNIX_TIMESTAMP(pc.cnt_livedate), pc.cnt_created), pc.cnt_sort)';

// define the general SELECT query part
$news['sql_query']  = 'SELECT pc.*, ';
$news['sql_query'] .= $news['cnt_ts_livedate'] . ' AS cnt_ts_livedate, ';
$news['sql_query'] .= $news['cnt_ts_killdate'] . ' AS cnt_ts_killdate, ';
$news['sql_query'] .= $news['cnt_ts_sortdate'] . ' AS cnt_ts_sortdate ';

// define the COUNT all query part
$news['sql_count'] = 'SELECT COUNT(pc.cnt_id) ';
$news['sql_joined_count'] = 'SELECT pc.cnt_id ';

$sql = 'FROM '.DB_PREPEND.'phpwcms_content pc ';

$news['sql_group_by'] = '';
$news['sql_where'][] = 'pc.cnt_status=1';
$news['sql_where'][] = "AND pc.cnt_module='news'";

// check if detail mode is active and select the related news item
if(isset($_getVar['newsdetail'])) {

    $news['match'] = array();

    preg_match('/^\d{8}\-(\d+)_(.*?)$/', clean_slweg($_getVar['newsdetail']), $news['match']);

    if(isset($news['match'][2])) {
        $news['match'] = trim($news['match'][2]);
        $news['sql_where'][] = is_numeric($news['match']) ? 'AND pc.cnt_id='.intval($news['match']) : 'AND pc.cnt_alias='._dbEscape($news['match']);
        $news['list_mode'] = false;

        // disable canonical <link> tag
        $content['set_canonical'] = false;
    }
}

// filters necessary only when in news list mode
if($news['list_mode']) {

    // archived
    switch($news['news_archive']) {

        case 0: // include archived
                $news['sql_where'][] = 'AND ' . $news['cnt_ts_livedate'] . ' < ' . $news['now'];
                $news['sql_where'][] = 'AND (' . $news['cnt_ts_killdate'] . ' > ' . $news['now'] . ' OR cnt_archive_status = 1)';
                break;

        case 1: // exclude archived
                $news['sql_where'][] = 'AND ' . $news['cnt_ts_livedate'] . ' < ' . $news['now'];
                $news['sql_where'][] = 'AND ' . $news['cnt_ts_killdate'] . ' > ' . $news['now'];
                break;

        case 2: // archived only
                $news['sql_where'][] = 'AND ' . $news['cnt_ts_killdate'] . ' < ' . $news['now'];
                $news['sql_where'][] = 'AND cnt_archive_status = 1';
                break;

        case 3: // all items
                $news['sql_where'][] = 'AND ' . $news['cnt_ts_livedate'] . ' < ' . $news['now'];
                break;

    }

    // choose by category
    if(count($news['news_category'])) {

        $news['news_joined_sql']    = true;
        $news['news_category_sql']  = array();

        foreach($news['news_category'] as $value) {
            $news['news_category_sql'][] = 'pcat.cat_name LIKE ' . _dbEscape($value);
        }

        // use sub query instead of JOIN to compare against AND / OR / NOT
        if($news['news_andor'] !== 'NOT') {

            $news['sql_where_cat']  = '(';
            $news['sql_where_cat'] .=   'SELECT COUNT(pcat.cat_pid) ';
            $news['sql_where_cat'] .=   'FROM '.DB_PREPEND.'phpwcms_categories pcat WHERE ';
            $news['sql_where_cat'] .=   "pcat.cat_type='news' AND pcat.cat_pid=pc.cnt_id AND (";
            $news['sql_where_cat'] .=   implode(' OR ', $news['news_category_sql']);
            $news['sql_where_cat'] .=   ') GROUP BY pcat.cat_pid';
            $news['sql_where_cat'] .= ')';

            if($news['news_andor'] == 'AND') {
                // count must be identical
                $news['sql_where'][] = 'AND ' . $news['sql_where_cat'] . ' = ' . count($news['news_category_sql']);
            } else {
                //OR - only single matching category needed
                $news['sql_where'][] = 'AND ' . $news['sql_where_cat'] . ' > 0';
            }

        } else {

            // no category is allowed
            $news['sql_where_cat']  = 'SELECT pcat.cat_pid ';
            $news['sql_where_cat'] .= 'FROM '.DB_PREPEND.'phpwcms_categories pcat WHERE ';
            $news['sql_where_cat'] .= "pcat.cat_type='news' AND (";
            $news['sql_where_cat'] .= implode(' OR ', $news['news_category_sql']);
            $news['sql_where_cat'] .= ') GROUP BY pcat.cat_pid';

            // catch all cat_id having not allowed category
            $news['not_allowed'] = _dbQuery($news['sql_where_cat']);

            if(isset($news['not_allowed'][0])) {

                $news['not_allowed_id'] = array();

                foreach($news['not_allowed'] as $cat_key => $cat_pid) {
                    $news['not_allowed_id'][] = $cat_pid['cat_pid'];
                }

                $news['sql_where'][] = 'AND pc.cnt_id NOT IN (' . implode(',', $news['not_allowed_id']) . ')';
            }
        }

        $news['sql_group_by'] = 'GROUP BY pc.cnt_id ';

    } else {

        // for joined SQL the COUNT() query is used in different way
        $news['news_joined_sql'] = false;
    }

    // language selection
    if(count($news['news_lang'])) {
        $news['sql_where'][] = "AND pc.cnt_lang IN ('". str_replace('#', "','", _dbEscape( implode('#', $news['news_lang']), false ) ) . "')";
    }
}

$sql .= 'WHERE ' . implode(' ', $news['sql_where']) . ' ';
$sql .= $news['sql_group_by']; // group by

// order by - only necessary in list mode
if($news['list_mode']) {

    $news['news_skip'] = intval($news['news_skip']);
    $news['news_limit'] = intval($news['news_limit']);

    if($news['news_skip']) {
        $news['sql_limit']  = ' LIMIT '.$news['news_skip'].', ';
        $news['sql_limit'] .= $news['news_limit'] ? $news['news_limit'] : 99999999;
    } elseif($news['news_limit']) {
        $news['sql_limit']  = ' LIMIT ';
        $news['sql_limit'] .= $news['news_skip'] ? $news['news_skip'] : 0;
        $news['sql_limit'] .= ', ' . $news['news_limit'];
    } else {
        $news['sql_limit'] = '';
    }

    // set defaults
    $news['current_page'] = 1;
    $news['total_pages'] = 1;
    $news['page_next'] = '';
    $news['page_prev'] = '';

    // pagination - no LIMIT, no ORDER BY
    if($news['news_paginate'] == 1) {

        // count all news based on current query
        if($news['news_joined_sql']) {

            $news['count_all'] = count( _dbQuery($news['sql_joined_count'] . $sql . $news['sql_limit']) );

        } else {

            $news['count_all'] = _dbCount($news['sql_count'] . $sql);

            // handle skipped items
            if($news['news_skip']) {
                $news['count_all'] = $news['count_all'] - $news['news_skip'];
                if($news['count_all'] < 0) {
                    $news['count_all'] = 0;
                }
            }

            // check if less news should be used than news in db
            if($news['news_limit'] && $news['news_limit'] < $news['count_all']) {
                $news['count_all'] = $news['news_limit'];
            }
        }

        // test and set page
        if(empty($_getVar['newspage'])) {
            $news['current_page'] = 1;
        } else {
            $news['current_page'] = intval($_getVar['newspage']);
            if($news['current_page'] == 0) {
                $news['current_page'] = 1;
            }
        }
        $news['total_pages'] = ceil( $news['count_all'] / $news['news_paginate_count'] );

        if($news['current_page'] > $news['total_pages']) {
            $news['current_page'] = $news['total_pages'];
        }

        if($news['current_page'] > 1 && $news['total_pages'] > 1) {
            if($news['current_page'] == 2) {
                $news['page_prev'] = rel_url( array(), array('newspage') );
            } else {
                $news['page_prev'] = rel_url( array( 'newspage' => $news['current_page']-1 ) );
            }

            // set pagination page info for detail link too
            $news['listing_page'] = array( 'newspage' => $news['current_page'] );
        }

        if($news['total_pages'] > 1 && $news['current_page'] < $news['total_pages']) {
            $news['page_next'] = rel_url( array( 'newspage' => $news['current_page']+1 ) );
        }

        // set LIMIT
        $news['sql_limit']  = ' LIMIT ';
        $news['sql_limit'] .= (($news['current_page'] - 1) *  $news['news_paginate_count']) + $news['news_skip'];
        $news['sql_limit'] .= ', ' . $news['news_paginate_count'];

    }

    $sql .= 'ORDER BY ';

    // add prio sorting value
    if( !empty($news['news_prio']) ) {
        $sql .= 'pc.cnt_prio DESC, ';
    }

    switch($news['news_sort']) {

        case 1:     // create date, DESC
            $sql .= 'pc.cnt_created DESC';
            break;

        case 2:     // create date, ASC
            $sql .= 'pc.cnt_created ASC';
            break;

        case 3:     // change date, DESC
            $sql .= 'pc.cnt_changed DESC';
            break;

        case 4:     // change date, ASC
            $sql .= 'pc.cnt_changed ASC';
            break;

        case 5:     // live date, DESC
            $sql .= 'cnt_ts_livedate DESC';
            break;

        case 6:     // live date, ASC
            $sql .= 'cnt_ts_livedate ASC';
            break;

        case 7:     // kill date, DESC
            $sql .= 'cnt_ts_killdate DESC';
            break;

        case 8:     // kill date, ASC
            $sql .= 'cnt_ts_killdate ASC';
            break;

        case 10:    // sort date, ASC
            $sql .= 'cnt_ts_sortdate ASC';
            break;

        case 17:    // title, DESC
            $sql .= 'pc.cnt_title DESC';
            break;

        case 18:    // title, ASC
            $sql .= 'pc.cnt_title ASC';
            break;

        case 11:    // editor, DESC
            $sql .= 'pc.cnt_editor DESC, cnt_ts_sortdate DESC';
            break;

        case 12:    // editor, ASC
            $sql .= 'pc.cnt_editor ASC, cnt_ts_sortdate ASC';
            break;

        case 15:    // place, DESC
            $sql .= 'pc.cnt_place DESC, cnt_ts_sortdate DESC';
            break;

        case 16:    // place, ASC
            $sql .= 'pc.cnt_place ASC, cnt_ts_sortdate ASC';
            break;

        case 13:    // title alt, DESC
            $sql .= 'pc.cnt_name DESC';
            break;

        case 14:    // title alt, ASC
            $sql .= 'pc.cnt_name ASC';
            break;

        case 9:
        default:    // sort date, DESC
            $sql .= 'cnt_ts_sortdate DESC';

    }

    $sql .= $news['sql_limit'];
}

// get db query result
$news['result'] = _dbQuery($news['sql_query'] . $sql);

// now render
if($news['template']) {

    $news['entries'] = array();

    if($news['list_mode']) { // check if news is in list mode

        $news['tmpl_news'] = get_tmpl_section('NEWS_LIST', $news['template']);
        $news['tmpl_entry'] = get_tmpl_section('NEWS_LIST_ENTRY', $news['template']);
        $news['tmpl_entry_space'] = get_tmpl_section('NEWS_LIST_ENTRY_SPACE', $news['template']);
        $news['tmpl_row_space'] = get_tmpl_section('NEWS_LIST_ROW_SPACE', $news['template']);

    } else { // or not in list mode

        $news['tmpl_news'] = '[NEWS_ENTRIES]{NEWS_ENTRIES}[/NEWS_ENTRIES]';
        $news['tmpl_entry'] = get_tmpl_section('NEWS_DETAIL', $news['template']);
        $news['tmpl_entry_space'] = '';
        $news['tmpl_row_space'] = '';

    }

    $news['tmpl_gallery_item'] = '';

    // get template based config and merge with defaults
    $news['config'] = array_merge(
        array(
            'news_per_row'              => 1,
            'news_teaser_limit_chars'   => 0,
            'news_teaser_limit_words'   => 0,
            'news_teaser_limit_ellipse' => $GLOBALS['template_default']['ellipse_sign'],
            'files_template_list'       => 'default',
            'files_template_detail'     => 'default',
            'files_direct_download'     => 0,
            'gallery_allowed_ext'       => 'jpg,jpeg,png,svg',
            'gallery_filecenter_info'   => 1
        ),
        parse_ini_str(get_tmpl_section('NEWS_SETTINGS', $news['template']), false)
    );

    $news['config']['news_per_row']             = abs(intval($news['config']['news_per_row']));
    $news['config']['news_teaser_limit_chars']  = intval($news['config']['news_teaser_limit_chars']);
    $news['config']['news_teaser_limit_words']  = intval($news['config']['news_teaser_limit_words']);
    $news['config']['check_lang']               = count($phpwcms['allowed_lang']) > 1;
    $news['config']['gallery_allowed_ext']      = convertStringToArray(strtolower($news['config']['gallery_allowed_ext']));
    if(count($news['config']['gallery_allowed_ext'])) {
        foreach($news['config']['gallery_allowed_ext'] as $ikey => $ivalue) {
            $news['config']['gallery_allowed_ext'][$ikey] = _dbEscape($ivalue);
        }
        $news['config']['gallery_allowed_ext'] = implode(',', $news['config']['gallery_allowed_ext']);
    } else {
        $news['config']['gallery_allowed_ext'] = '';
    }

    // start parsing news entries
    $news['row_count'] = 1;
    $news['total_count'] = 1;
    $news['entry_count'] = is_array($news['result']) ? count($news['result']) : 0;

    // set new target if necessary
    if(empty($news['news_detail_link'])) {
        $news['base_href'] = rel_url($news['listing_page'], array('newsdetail'));
    } else {
        if(is_intval($news['news_detail_link'])) {
            $news['news_detail_link'] = 'aid='.$news['news_detail_link'];
        }
        $news['base_href'] = rel_url($news['listing_page'], array('newsdetail'), $news['news_detail_link']);
    }

    if($news['entry_count']) {

        foreach($news['result'] as $key => $value) {

            $value['cnt_object'] = @unserialize($value['cnt_object']);
            $news_item_id = $value['cnt_id'];

            $news['entries'][$key] = getFrontendEditLink('news', $value['cnt_id']);

            if(empty($value['cnt_object']['cnt_files']['gallery'])) {
                $news['tmpl_gallery_item'] = '';
                $news['entries'][$key] .= $news['tmpl_entry'];
            } else {
                if(empty($news['tmpl_gallery_item'])) {
                    $news['tmpl_gallery_item'] = get_tmpl_section('GALLERY_ITEM', $news['tmpl_entry']);
                }
                $news['entries'][$key] .= replace_tmpl_section('GALLERY_ITEM', $news['tmpl_entry']);
            }

            if($value['cnt_teasertext']) {
                $value['cnt_description'] = $value['cnt_teasertext'];
                if($news['config']['news_teaser_limit_chars']) {
                    $value['cnt_teasertext'] = getCleanSubString($value['cnt_teasertext'], $news['config']['news_teaser_limit_chars'], $news['config']['news_teaser_limit_ellipse'], 'char');
                } elseif($news['config']['news_teaser_limit_words']) {
                    $value['cnt_teasertext'] = getCleanSubString($value['cnt_teasertext'], $news['config']['news_teaser_limit_words'], $news['config']['news_teaser_limit_ellipse'], 'word');
                }

                if(empty($value['cnt_object']['cnt_textformat']) || $value['cnt_object']['cnt_textformat'] === 'plain') {
                    $value['cnt_teasertext'] = plaintext_htmlencode($value['cnt_teasertext']);
                    $value['cnt_description'] = plaintext_htmlencode($value['cnt_description']);
                } elseif($value['cnt_object']['cnt_textformat'] === 'br') {
                    $value['cnt_teasertext'] = br_htmlencode($value['cnt_teasertext']);
                    $value['cnt_description'] = br_htmlencode($value['cnt_description']);
                } elseif($value['cnt_object']['cnt_textformat'] === 'markdown') {
                    init_markdown();
                    $value['cnt_teasertext'] = $phpwcms['parsedown_class']->text($value['cnt_teasertext']);
                    $value['cnt_description'] = $phpwcms['parsedown_class']->text($value['cnt_description']);
                } elseif($value['cnt_object']['cnt_textformat'] === 'textile') {
                    init_textile();
                    $value['cnt_teasertext'] = $phpwcms['textile_class']->textileThis($value['cnt_teasertext']);
                    $value['cnt_description'] = $phpwcms['textile_class']->textileThis($value['cnt_description']);
                } else {
                    $value['cnt_teasertext'] = html($value['cnt_teasertext']);
                    $value['cnt_description'] = html($value['cnt_description']);
                }

            } else {
                $value['cnt_opengraph_teasertext'] = '';
                $value['cnt_description'] = '';
            }

            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'NEWS_TITLE', html_specialchars($value['cnt_title']));
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'NEWS_TOPIC', html_specialchars($value['cnt_name']));
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'NEWS_SUBTITLE', html_specialchars($value['cnt_subtitle']));
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'NEWS_TEASER', $value['cnt_teasertext']);
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'NEWS_TEXT', $value['cnt_text']);
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'AUTHOR', html_specialchars($value['cnt_editor']));
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'PLACE', html_specialchars($value['cnt_place']));
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'PRIO', empty($value['cnt_prio']) ? '' : $value['cnt_prio'] );
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'FIRST', $news['row_count'] === 1 ? ' ' : '');
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'LAST', $news['row_count'] === $news['entry_count'] ? ' ' : '');
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'NEWS_TAGS', html_specialchars($value['cnt_object']['cnt_category']));

            // news detail link (read)
            if($news['list_mode']) {

                if(empty($value['cnt_object']['cnt_readmore'])) {
                    $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'NEWS_DETAIL_LINK', '');
                } else {
                    $value['detail_link']   = date('Ymd', $value['cnt_ts_livedate']) . '-' . $crow['acontent_aid'] . '_' ;
                    $value['detail_link']  .= empty($value['cnt_alias']) ? $value['cnt_id'] : urlencode( $value['cnt_alias'] );
                    $news['entries'][$key]  = render_cnt_template($news['entries'][$key], 'NEWS_DETAIL_LINK', $news['base_href'] . (strpos($news['base_href'], '?') !== false ? '&amp;' : '?') . 'newsdetail=' . $value['detail_link']);
                }

            // news list link (back)
            } else {

                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'NEWS_LIST_LINK', $news['base_href']);

                $content['opengraph']['type'] = 'article';
                $content['opengraph']['title'] = $value['cnt_title'];
                if(!$value['cnt_opengraph']) {
                    $content['opengraph']['support'] = false;
                }

                if($value['cnt_description']) {
                    $content['opengraph']['description'] = preg_replace('/\s+/', ' ', strip_tags($value['cnt_description']));
                    set_meta('description', $content['opengraph']['description']);
                }
                if($value['cnt_title']) {
                    $content["pagetitle"] = $content["pagetitle"] ? $value['cnt_title'] . $GLOBALS['pagelayout']['layout_title_spacer'] . $content["pagetitle"] : $value['cnt_title'];
                }
            }

            // Image
            if(empty($value['cnt_object']['cnt_image']['id'])) {
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'IMAGE', '');
                $value['cnt_object']['cnt_image']['id'] = '';
                $value['cnt_object']['cnt_image']['ext'] = '';
            } else {
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'IMAGE', html_specialchars($value['cnt_object']['cnt_image']['name']));
                $value['cnt_object']['cnt_image']['ext'] = which_ext($value['cnt_object']['cnt_image']['name']);

                if(!isset($content['images']['news'])) {
                    $content['images']['news'] = array();
                }

                $content['images']['news'][ $value['cnt_object']['cnt_image']['id'] ] = array(
                    'name'  => $value['cnt_object']['cnt_image']['name'],
                    'id'    => $value['cnt_object']['cnt_image']['id'],
                    'ext'   => $value['cnt_object']['cnt_image']['ext'],
                    'list'  => $news['list_mode']
                );

                if($value['cnt_object']['cnt_image']['ext']) {
                    $value['cnt_object']['cnt_image']['ext'] = '.' . $value['cnt_object']['cnt_image']['ext'];
                }

                if(preg_match_all('/{IMAGE_(HASH|WIDTH|HEIGHT)}/', $news['entries'][$key], $matches)) {

                    $_this_id = $value['cnt_object']['cnt_image']['id'];

                    $content['images']['news'][ $_this_id ]['details'] = getFileDetails($value['cnt_object']['cnt_image']['id']);

                    if($content['images']['news'][ $_this_id ]['details'] !== null) {

                        if(count($matches[0]) > 1 || $matches[1][0] !== 'HASH') {
                            $newsimage_file = PHPWCMS_STORAGE . $content['images']['news'][ $_this_id ]['details']['f_hash'];
                            if($content['images']['news'][ $_this_id ]['details']['f_ext']) {
                                $newsimage_file .= '.' . $content['images']['news'][ $_this_id ]['details']['f_ext'];
                            }

                            if(($newsimage_is_file = is_file($newsimage_file)) && ($content['images']['news'][ $_this_id ]['details']['f_svg'] || $content['images']['news'][ $_this_id ]['details']['f_image_width'])) {

                                $news['entries'][$key] = str_replace('{IMAGE_WIDTH}', $content['images']['news'][ $_this_id ]['details']['f_image_width'], $news['entries'][$key]);
                                $news['entries'][$key] = str_replace('{IMAGE_HEIGHT}', $content['images']['news'][ $_this_id ]['details']['f_image_height'], $news['entries'][$key]);

                            } elseif($newsimage_is_file && ($newsimage_file_detail = @getimagesize($newsimage_file))) {

                                $news['entries'][$key] = str_replace('{IMAGE_WIDTH}', $newsimage_file_detail[0], $news['entries'][$key]);
                                $news['entries'][$key] = str_replace('{IMAGE_HEIGHT}', $newsimage_file_detail[1], $news['entries'][$key]);

                            } else {

                                $news['entries'][$key] = str_replace('{IMAGE_WIDTH}', '0', $news['entries'][$key]);
                                $news['entries'][$key] = str_replace('{IMAGE_HEIGHT}', '0', $news['entries'][$key]);

                            }
                        }

                        $news['entries'][$key] = str_replace('{IMAGE_HASH}', $content['images']['news'][ $_this_id ]['details']['f_hash'], $news['entries'][$key]);

                    } else {

                        $news['entries'][$key] = str_replace('{IMAGE_HASH}', '', $news['entries'][$key]);
                        $news['entries'][$key] = str_replace('{IMAGE_WIDTH}', '0', $news['entries'][$key]);
                        $news['entries'][$key] = str_replace('{IMAGE_HEIGHT}', '0', $news['entries'][$key]);

                    }

                }
            }

            $news['entries'][$key] = str_replace('{IMAGE_ID}', $value['cnt_object']['cnt_image']['id'], $news['entries'][$key]);
            $news['entries'][$key] = str_replace('{IMAGE_EXT}', $value['cnt_object']['cnt_image']['ext'], $news['entries'][$key]);

            // Zoom Image
            if(empty($value['cnt_object']['cnt_image']['zoom'])) {
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'ZOOM', '' );
            } else {
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'ZOOM', 'zoom' );
            }
            // Lightbox
            if(empty($value['cnt_object']['cnt_image']['lightbox'])) {
                $news['entries'][$key] = str_replace('{LIGHTBOX}', '', $news['entries'][$key]);
            } else {
                initSlimbox();
                $news['entries'][$key] = str_replace('{LIGHTBOX}', ' rel="lightbox"'.get_attr_data_gallery('', ' ', ''), $news['entries'][$key]);
            }
            // Caption
            if(empty($value['cnt_object']['cnt_image']['caption'])) {
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'CAPTION', '');
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'COPYRIGHT', '');
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'ALT', '');
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'TITLE', '');
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'CAPTION_LINK', '');
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'CAPTION_TARGET', '');
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'LIGHTBOX_CAPTION', '');
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'LIGHTBOX_COPYRIGHT', '');
            } else {
                $value['cnt_caption']  = getImageCaption(array('caption' => $value['cnt_object']['cnt_image']['caption'], 'file' => $value['cnt_object']['cnt_image']['id']), '');
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'CAPTION', html($value['cnt_caption']['caption_text']) );
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'COPYRIGHT', html($value['cnt_caption']['caption_copyright']) );
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'ALT', html($value['cnt_caption']['caption_alt']) );
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'TITLE', html($value['cnt_caption']['caption_title']) );
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'CAPTION_LINK', html($value['cnt_caption']['caption_link']));
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'CAPTION_TARGET', $value['cnt_caption']['caption_target']);
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'LIGHTBOX_CAPTION', parseLightboxCaption($value['cnt_caption']['caption_text']) );
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'LIGHTBOX_COPYRIGHT', parseLightboxCaption($value['cnt_caption']['caption_copyright']) );
            }

            // Image URL
            if(empty($value['cnt_object']['cnt_image']['link'])) {
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'IMAGE_URL', '');
                $news['entries'][$key] = str_replace('{IMAGE_URL_TARGET}', '', $news['entries'][$key]);
            } else {
                $value['image_url']    = get_redirect_link($value['cnt_object']['cnt_image']['link'], ' ', '');
                $news['entries'][$key] = str_replace('{IMAGE_URL_TARGET}', $value['image_url']['target'], $news['entries'][$key]);
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'IMAGE_URL', html_specialchars($value['image_url']['link']) );
            }
            // Check for Zoom
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'ZOOM', empty($value['cnt_object']['cnt_image']['zoom']) ? '' : 'zoom' );

            // news entry URL
            $value['news_url']     = $value['cnt_object']['cnt_link'] == '' ? array('link'=>'', 'target'=>'') : get_redirect_link($value['cnt_object']['cnt_link'], ' ', '');
            $news['entries'][$key] = str_replace('{URL_TARGET}', $value['news_url']['target'], $news['entries'][$key]);
            if(is_numeric($value['news_url']['link']) && intval($value['news_url']['link'])) {
                $value['news_url']['link'] = rel_url(array(), array(), 'aid='.intval($value['news_url']['link']));
            }
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'URL', html($value['news_url']['link']) );
            $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'URL_TEXT', html($value['cnt_object']['cnt_linktext']) );

            // Dates
            $news['entries'][$key] = render_cnt_date($news['entries'][$key], $value['cnt_changed'], $value['cnt_ts_livedate'], $value['cnt_ts_killdate']);
            $news['entries'][$key] = render_date($news['entries'][$key], $value['cnt_ts_sortdate'], 'SORTDATE');

            $news['files_result'] = '';

            // Files
            if(isset($value['cnt_object']['cnt_files']['id']) && is_array($value['cnt_object']['cnt_files']['id']) && count($value['cnt_object']['cnt_files']['id'])) {

                // should image files used for gallery
                if(!empty($value['cnt_object']['cnt_files']['gallery']) && strpos($news['entries'][$key], '/GALLERY')) {

                    if(!$news['config']['gallery_allowed_ext']) {

                        $value['cnt_object']['cnt_files']['gallery'] = false;

                    // Get Image files
                    } else {

                        $value['cnt_object']['cnt_files']['where']  = 'f_id IN (' . implode(',', $value['cnt_object']['cnt_files']['id']) . ') AND ';
                        $value['cnt_object']['cnt_files']['where'] .= 'f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND ';
                        $value['cnt_object']['cnt_files']['where'] .= 'f_ext IN(' . $news['config']['gallery_allowed_ext'] . ')';

                        $value['cnt_object']['cnt_files']['images'] = _dbGet(
                            'phpwcms_file',
                            'f_id,f_hash,f_name,f_ext,f_longinfo,f_copyright,f_vars,f_svg,f_image_width,f_image_height',
                            $value['cnt_object']['cnt_files']['where']
                        );

                        if(!isset($value['cnt_object']['cnt_files']['images'][0])) {

                            $value['cnt_object']['cnt_files']['gallery'] = false;

                        // create gallery
                        } else {

                            $value['gallery_id'] = array();

                            // just to have relation between file ID and query result
                            foreach($value['cnt_object']['cnt_files']['images'] as $ikey => $ivalue) {
                                $value['gallery_id'][ $ivalue['f_id'] ] = $ikey;
                            }

                            // Need to parse file list caption too
                            if(!$news['config']['gallery_filecenter_info']) {

                                // check if info for the file is available
                                // [0] = normal file description like before
                                // [1] = name the file (it's not the file name)
                                // [2] = title
                                // [3] = target (where to open a new file -> default is _blank even if empty
                                // [4] = if it is an image try to show a thumbnail instead of the file icon -> here thumbnail WIDTHxHEIGHT
                                // [5] = copyright information

                                $value['gallery_captions'] = explode("\n", $value['cnt_object']['cnt_files']['caption']);

                                if(count($value['gallery_captions'])) {

                                    foreach($value['gallery_captions'] as $ikey => $ivalue) {

                                        $ivalue = trim($ivalue);

                                        if($ivalue) {

                                            $ivalue = explode('|', $ivalue);
                                            $value['gallery_captions'][$ikey] = array(
                                                'caption' => trim($ivalue[0]),
                                                'copyright' => empty($ivalue[5]) ? '' : trim($ivalue[5])
                                            );

                                        } else {
                                            unset($value['gallery_captions'][$ikey]);
                                        }
                                    }
                                }
                            }

                            $value['cnt_object']['cnt_files']['gallery'] = '';

                            // now render and test which file should still be available for download
                            foreach($value['cnt_object']['cnt_files']['id'] as $ikey => $ivalue) {

                                if(isset($value['gallery_id'][ $ivalue ])) {

                                    // not downloadable
                                    if(empty($value['cnt_object']['cnt_files']['gallery_download'])) {
                                        unset($value['cnt_object']['cnt_files']['id'][$ikey]);
                                    }

                                    // render gallery item
                                    $ivalue = $value['cnt_object']['cnt_files']['images'][ $value['gallery_id'][ $ivalue ] ];

                                    // check for caption and copyright
                                    if($news['config']['gallery_filecenter_info'] && !isset($value['gallery_captions'][$ikey])) {

                                        if($news['config']['check_lang'] && !empty($ivalue['f_vars'])) {

                                            $ivalue['f_vars'] = @unserialize($ivalue['f_vars']);

                                            if(!empty($ivalue['f_vars'][$phpwcms['DOCTYPE_LANG']]['longinfo'])) {
                                                $ivalue['f_longinfo'] = $ivalue['f_vars'][$phpwcms['DOCTYPE_LANG']]['longinfo'];
                                            }
                                            if(!empty($ivalue['f_vars'][$phpwcms['DOCTYPE_LANG']]['copyright'])) {
                                                $ivalue['f_copyright'] = $ivalue['f_vars'][$phpwcms['DOCTYPE_LANG']]['copyright'];
                                            }
                                        }

                                        $value['gallery_captions'][$ikey] = array(
                                            'caption' => isset($ivalue['f_longinfo']) ? $ivalue['f_longinfo'] : '',
                                            'copyright' => isset($ivalue['f_copyright']) ? $ivalue['f_copyright'] : ''
                                        );
                                    }

                                    $ivalue['tmpl'] = str_replace('{IMAGE_HASH}', $ivalue['f_hash'], $news['tmpl_gallery_item']);
                                    $ivalue['tmpl'] = str_replace('{IMAGE_EXT}', $ivalue['f_ext'], $ivalue['tmpl']);
                                    $ivalue['tmpl'] = str_replace('{IMAGE_ID}', $ivalue['f_id'], $ivalue['tmpl']);
                                    $ivalue['tmpl'] = str_replace('{IMAGE_NAME}', $ivalue['f_name'], $ivalue['tmpl']);

                                    $ivalue['tmpl'] = render_cnt_template($ivalue['tmpl'], 'CAPTION', empty($value['gallery_captions'][$ikey]['caption']) ? '' : html($value['gallery_captions'][$ikey]['caption']));
                                    $ivalue['tmpl'] = render_cnt_template($ivalue['tmpl'], 'COPYRIGHT', empty($value['gallery_captions'][$ikey]['copyright']) ? '' : html($value['gallery_captions'][$ikey]['copyright']));
                                    $ivalue['tmpl'] = render_cnt_template($ivalue['tmpl'], 'FIRST', $value['cnt_object']['cnt_files']['gallery'] === '' ? ' ' : '');

                                    if(preg_match('/{IMAGE_(WIDTH|HEIGHT)}/', $ivalue['tmpl'])) {

                                        $ivalue['file'] = PHPWCMS_STORAGE . $ivalue['f_hash'];
                                        if($ivalue['f_ext']) {
                                            $ivalue['file'] .= '.' . $ivalue['f_ext'];
                                        }
                                        if(($ivalue['is_file'] = is_file($ivalue['file'])) && ($ivalue['f_svg'] || $ivalue['f_image_width'])) {
                                            $ivalue['tmpl'] = str_replace('{IMAGE_WIDTH}', $ivalue['f_image_width'], $ivalue['tmpl']);
                                            $ivalue['tmpl'] = str_replace('{IMAGE_HEIGHT}', $ivalue['f_image_height'], $ivalue['tmpl']);
                                        } elseif($ivalue['is_file'] && ($ivalue['imageinfo'] = @getimagesize($ivalue['file']))) {
                                            $ivalue['tmpl'] = str_replace('{IMAGE_WIDTH}', $ivalue['imageinfo'][0], $ivalue['tmpl']);
                                            $ivalue['tmpl'] = str_replace('{IMAGE_HEIGHT}', $ivalue['imageinfo'][1], $ivalue['tmpl']);
                                        } else {
                                            $ivalue['tmpl'] = str_replace('{IMAGE_WIDTH}', '0', $ivalue['tmpl']);
                                            $ivalue['tmpl'] = str_replace('{IMAGE_HEIGHT}', '0', $ivalue['tmpl']);
                                        }

                                    }

                                    $value['cnt_object']['cnt_files']['gallery'] .= $ivalue['tmpl'];

                                }
                            }

                            // cleanup some memory
                            unset(
                                $value['cnt_object']['cnt_files']['images'],
                                $value['gallery_id']
                            );
                        }
                    }
                }

                // No Gallery
                if(empty($value['cnt_object']['cnt_files']['gallery'])) {
                    $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'GALLERY', '' );
                } else {
                    $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'GALLERY', $value['cnt_object']['cnt_files']['gallery'] );
                }

                if(count($value['cnt_object']['cnt_files']['id'])) {

                    $IS_NEWS_CP = true;

                    $value['files_direct_download'] = intval($news['config']['files_direct_download']) ? 1 : 0;

                    // set correct template for files based on list or detail mode
                    if($news['list_mode']) {
                        $value['files_template'] = $news['config']['files_template_list'] == 'default' ? '' : $news['config']['files_template_list'];
                    } else {
                        $value['files_template'] = $news['config']['files_template_detail'] == 'default' ? '' : $news['config']['files_template_detail'];
                    }

                    // Preserve current content part values, might be overwritten by files CP
                    $_crow = $crow;

                    // include content part files renderer
                    include PHPWCMS_ROOT.'/include/inc_front/content/cnt7.article.inc.php';

                    $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'FILES', $news['files_result'] );

                    // Restore content part values
                    $crow = $_crow;
                    unset($IS_NEWS_CP, $_crow);

                } else {

                    $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'FILES', '' );

                }

            } else {
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'FILES', '' );
                $news['entries'][$key] = render_cnt_template($news['entries'][$key], 'GALLERY', '' );
            }

            // row and entry spacer
            if($news['list_mode']) {

                if( $news['row_count'] == $news['config']['news_per_row'] || $news['config']['news_per_row'] == 0 ) {

                    if($news['total_count'] < $news['entry_count']) {
                        $news['entries']['row'.$key] = $news['tmpl_row_space'];
                    }
                    $news['row_count']  = 1;

                } else {

                    if($news['total_count'] < $news['entry_count']) {
                        $news['entries']['entry'.$key] = $news['tmpl_entry_space'];
                    }
                    $news['row_count']++;

                }

                $news['total_count']++;
            }

            $news['entries'][$key] = str_replace('{ID}', $news_item_id, $news['entries'][$key]);

        }
    }

    $news['tmpl_news'] = render_cnt_template($news['tmpl_news'], 'NEWS_ENTRIES', implode('', $news['entries']) );
    $news['tmpl_news'] = render_cnt_template($news['tmpl_news'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
    $news['tmpl_news'] = render_cnt_template($news['tmpl_news'], 'ATTR_ID', html($crow['acontent_attr_id']));
    $news['tmpl_news'] = render_cnt_template($news['tmpl_news'], 'TITLE', html_specialchars($crow['acontent_title']));
    $news['tmpl_news'] = render_cnt_template($news['tmpl_news'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));

    // render news pagination
    if($news['list_mode']) {
        if($news['news_paginate'] == 1) {
            $news['tmpl_news'] = render_cnt_template($news['tmpl_news'], 'PAGINATE', true);
            $news['tmpl_news'] = render_cnt_template($news['tmpl_news'], 'PAGE_PREV', $news['page_prev']);
            $news['tmpl_news'] = render_cnt_template($news['tmpl_news'], 'PAGE_NEXT', $news['page_next']);
            $news['tmpl_news'] = str_replace('{PAGE_CURRENT}', $news['current_page'], $news['tmpl_news']);
            $news['tmpl_news'] = str_replace('{PAGE_TOTAL}', $news['total_pages'], $news['tmpl_news']);
        } else {
            $news['tmpl_news'] = render_cnt_template($news['tmpl_news'], 'PAGINATE', '');
        }
    } else {
        $news['tmpl_news'] = replace_cnt_template($news['tmpl_news'], 'PAGINATE', '');
        $news['tmpl_news'] = replace_cnt_template($news['tmpl_news'], 'PAGINATE_ELSE', '');
    }

    $CNT_TMP .= $news['tmpl_news'];

}
