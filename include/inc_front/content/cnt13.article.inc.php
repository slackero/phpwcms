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

//search form
$content["search"]       = unserialize($crow["acontent_form"]);
$s_result_list           = array();
$content["search_word"]  = '';
$content['highlight']    = array();
$s_list                  = array();
define('SEARCH_TYPE_AND', empty($content['search']['type']) || $content['search']['type'] == 'OR' ? FALSE : TRUE);

if(empty($content['search']["text_html"])) {
    $content['search']['text_html'] = 0;
}

$content['search']['search_filename'] = empty($content['search']["no_filenames"]); // search/list for file/imagenames
$content['search']['search_username'] = empty($content['search']["no_username"]);
$content['search']['search_caption'] = empty($content['search']["no_caption"]);
$content['search']['search_keyword'] = empty($content['search']["no_keyword"]);
$content['search']['show_summary'] = empty($content['search']["hide_summary"]); // show search tester text

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/search.tmpl')) {
    $crow["acontent_template"]  = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/search.tmpl') );
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/search/'.$crow["acontent_template"])) {
    $crow["acontent_template"]  = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/search/'.$crow["acontent_template"]) );
} else {
    $crow["acontent_template"]  = '';
}

$crow['template'] = array(
    'header'        => get_tmpl_section('SEARCH_HEADER', $crow["acontent_template"]),
    'footer'        => get_tmpl_section('SEARCH_FOOTER', $crow["acontent_template"]),
    'item_space'    => get_tmpl_section('SEARCH_ITEM_SPACER', $crow["acontent_template"]),
    'item'          => get_tmpl_section('SEARCH_ITEM', $crow["acontent_template"]),
    'pagination'    => trim(get_tmpl_section('SEARCH_PAGINATE', $crow["acontent_template"])),
    'text'          => '',
    'form'          => '',
    'image_render'  => false
);

if(!empty($_POST["search_input_field"]) || !empty($_GET['searchwords'])) {

    $s_run = 0;
    // check search
    // remove unsecure replacement tags
    $content["search_word"] = empty($_POST["search_input_field"]) ? rawurldecode($_GET['searchwords']) : $_POST["search_input_field"];
    $content["search_word"] = clean_slweg($content["search_word"]);
    $content["search_word"] = clean_replacement_tags($content["search_word"]);
    $content["search_word"] = cleanUpSpecialHtmlEntities($content["search_word"]);

    // split all search words
    $content["search_word"] = explode(' ', $content["search_word"]);
    $content["search_word"] = array_unique($content["search_word"]);

    $content['search']['highlight_result']  = empty($content["search"]['highlight_result']) ? false : true;
    $content['search']['wordlimit']         = isset($content["search"]['wordlimit']) && is_intval($content["search"]['wordlimit']) ? abs(intval($content["search"]['wordlimit'])) : 35;

    $content["search"]["result_per_page"]   = empty($content["search"]['result_per_page']) ? 25 : $content["search"]['result_per_page'];
    if($content["search"]["result_per_page"] < 0)  {
        $content["search"]["result_per_page"] = $content["search"]["result_per_page"] === -1 ? 100000 : abs($content["search"]["result_per_page"]);
    }

    if(!isset($content["search"]["show_always"]))   $content["search"]["show_always"]   = 1;
    if(!isset($content["search"]["show_top"]))      $content["search"]["show_top"]      = 1;
    if(!isset($content["search"]["show_bottom"]))   $content["search"]["show_bottom"]   = 1;
    if(!isset($content["search"]["show_next"]))     $content["search"]["show_next"]     = 1;
    if(!isset($content["search"]["show_prev"]))     $content["search"]["show_prev"]     = 1;
    if(!isset($content["search"]["minchar"]))       $content["search"]["minchar"]       = 3;

    if(!isset($content["search"]["start_at"]) || !is_array($content["search"]["start_at"])) {
        $content["search"]["start_at"] = array(0);
    }

    // include neccessary frontend functions, but only once
    include_once PHPWCMS_ROOT.'/include/inc_front/content/cnt_functions/cnt13.func.inc.php';
    $content["search"]["start_at"] = get_SearchForStructureID($content["search"]["start_at"]);

    $content['highlight'] = array();
    foreach($content["search_word"] as $key => $value) {
        $_strlen_value = mb_strlen($value);
        if($_strlen_value >= $content["search"]["minchar"]) {
            $value = trim($value);
            $content["search_word"][$key] = preg_quote($value, '/');
            $content["search_word"][$key] = str_replace("\\?", '.?', $content["search_word"][$key]);
            $content["search_word"][$key] = str_replace("\\*", '.*', $content["search_word"][$key]);
            $content['highlight'][] = $value;
        }
    }

    if(count($content['highlight'])) {

        if(strpos($crow['template']['item'], '{IMAGE') !== false) {
            $crow['template']['image_render'] = true;
        }

        $s_result_highlight = implode(' ', $content['highlight']);

        if(!empty($_POST["search_input_field"])) {
            // make a redirection to avoid message when using browser back
            $GLOBALS['_getVar']['searchstart'] = 1;
            $GLOBALS['_getVar']['searchwords'] = $s_result_highlight;
            headerRedirect(abs_url(array(), array(), '', 'rawurlencode'));
        }

        $s_result_highlight = rawurlencode($s_result_highlight);

        $sql  = "SELECT article_id, article_cid, article_title, article_username, article_subtitle, ";
        $sql .= "article_summary, article_keyword, UNIX_TIMESTAMP(article_tstamp) AS article_date, ";
        $sql .= "article_image, article_alias, article_aliasid, article_headerdata ";
        $sql .= "FROM ".DB_PREPEND."phpwcms_article ar ";
        $sql .= "LEFT JOIN ".DB_PREPEND."phpwcms_articlecat ac ON ";
        $sql .= "(ar.article_cid = ac.acat_id OR ar.article_cid = 0)";
        $sql .= " WHERE ";

        // limit to special structure IDs if not all
        if(count($content["search"]["start_at"])) {

            $sql .= 'ar.article_cid IN ('.implode(',', $content["search"]["start_at"]).')';

        } else {

            $sql .= "IF(ar.article_cid = 0, " . (empty($GLOBALS['indexpage']['acat_nosearch']) ? 1 : 0) .", 1)";

        }

        $sql .= " AND ac.acat_nosearch != 1 AND ac.acat_aktiv=1 AND ";
        if(!FEUSER_LOGIN_STATUS) {
            $sql .= "ac.acat_regonly=0 AND ";
        }
        $sql .= "ar.article_aktiv=1 AND ar.article_deleted=0 AND ar.article_nosearch!=1 ";
        if(!PREVIEW_MODE) {
            // enhanced IF statement by kh 2008/12/03
			$sql .= "AND IF((ar.article_begin < NOW() AND (ar.article_end='0000-00-00 00:00:00' OR ar.article_end > NOW())) OR (ar.article_archive_status=1 AND ac.acat_archive=1), 1, 0) ";
        }
        $sql .= "GROUP BY ar.article_id";

        $sresult = _dbQuery($sql);

        if(isset($sresult[0]['article_id'])) {
            $s_search_words         = implode('|', $content["search_word"]);
            $s_search_words_count   = count($content["search_word"]);

            foreach($sresult as $srow) {

                // now try to retrieve alias article information
                if($srow["article_aliasid"]) {
                    $alias_sql  = 'SELECT article_id, article_title, article_subtitle, article_summary, article_keyword, ';
                    $alias_sql .= 'UNIX_TIMESTAMP(article_tstamp) AS article_date, article_image ';
                    $alias_sql .= "FROM ".DB_PREPEND."phpwcms_article ";
                    $alias_sql .= "WHERE article_deleted=0 AND article_id=".intval($srow["article_aliasid"]);
                    if(!$srow["article_headerdata"]) {
                        switch(VISIBLE_MODE) {
                            case 0: $alias_sql .= " AND article_aktiv=1"; break;
                            case 1: $alias_sql .= " AND (article_aktiv=1 OR article_uid=".$_SESSION["wcs_user_id"].')'; break;
                        }
                        if(!PREVIEW_MODE) {
							$alias_sql .= " AND article_begin < NOW() AND (article_end='0000-00-00 00:00:00' OR article_end > NOW())";
                        }
                    }
                    $alias_sql .= " LIMIT 1";

                    $alias_result = _dbQuery($alias_sql);

                    if(isset($alias_result[0]['article_id'])) {
                        $srow["article_id"] = $alias_result[0]["article_id"];
                        if(empty($srow["article_headerdata"])) {
                            $srow["article_title"]      = $alias_result[0]["article_title"];
                            $srow["article_subtitle"]   = $alias_result[0]["article_subtitle"];
                            $srow["article_keyword"]    = $alias_result[0]["article_keyword"];
                            $srow["article_summary"]    = $alias_result[0]["article_summary"];
                            $srow["article_date"]       = $alias_result[0]["article_date"];
                            $srow["article_image"]      = $alias_result[0]["article_image"];
                        }
                    }
                }

                // read article base info for search
                $s_id       = $srow["article_id"];
                $s_cid      = $srow["article_cid"];
                $s_title    = $srow["article_title"];
                $s_date     = $srow["article_date"];
                $s_user     = $srow["article_username"];
                $s_text     = $srow["article_subtitle"].' '.$srow["article_summary"];

                // read article content for search
                $csql  = "SELECT acontent_title, acontent_subtitle, acontent_text, acontent_html, acontent_files, acontent_type, acontent_form, acontent_image FROM ";
				$csql .= DB_PREPEND."phpwcms_articlecontent WHERE acontent_aid=".$s_id." ";
				$csql .= "AND acontent_visible=1 AND acontent_trash=0 AND ";
				$csql .= "acontent_livedate < NOW() AND (acontent_killdate='0000-00-00 00:00:00' OR acontent_killdate > NOW()) AND ";
                $csql .= 'acontent_granted' . (FEUSER_LOGIN_STATUS ? '!=2' : '=0') . ' AND ';
                $csql .= "acontent_type IN (0, 1, 2, 4, 5, 6, 7, 11, 14, 26, 27, 29, 100, 31, 32)";

                $scresult = _dbQuery($csql);

                if(isset($scresult[0]['acontent_title'])) {
                    foreach($scresult as $scrow) {

                        // always title, subtitle
                        $s_text .= ' '.$scrow['acontent_title'].' '.$scrow['acontent_subtitle'];

                        switch(intval($scrow['acontent_type'])) {

                            // just no additional search terms
                            case 3:
                            case 8:
                            case 9:
                            case 10:
                            case 12:
                            case 13:
                            case 15:
                            case 16:
                            case 18:
                            case 19:
                            case 20:
                            case 21:
                            case 22:
                            case 23:
                            case 24:
                            case 25:
                            case 28:
                            case 33:
                            case 50:
                            case 51:
                            case 52:
                            case 53:
                                        break;

                            // only HTML
                            case 6:
                            case 14:    $s_text .= ' '.$scrow['acontent_html'];
                                        break;

                            // only TEXT
                            case 0:
                            case 4:
                            case 5:
                            case 11:
                            case 32:
                            case 100:   $s_text .= ' '.$scrow['acontent_text'];
                                        break;

                            case 7:     // file list, get files listed here
                                        $s_text .= ' '.$scrow['acontent_text'];
                                        if($content['search']['search_filename']) {
                                            $s_files = getFileInformation( explode(':', $scrow['acontent_files']) );
                                            if(is_array($s_files) && count($s_files)) {
                                                // retrieve file information
                                                foreach($s_files as $s_files_value) {
                                                    $s_text .= ' '.$s_files_value['f_name'];
                                                }
                                            }
                                        }
                                        break;

                            // optimize images for search
                            case 1  :   $s_text .= ' '.$scrow['acontent_text'];
                                        if($content['search']['search_filename'] && $scrow['acontent_image']) {
                                            $scrow['acontent_image'] = explode(":", $scrow['acontent_image']);
                                            $s_text .= ' '.$scrow['acontent_image'][1];
                                        }
                                        break;

                            case 29:    $s_text .= ' '.$scrow['acontent_text'];
                            case 2:     if($content['search']['search_caption'] || $content['search']['search_filename']) {
                                            $scrow['acontent_form'] = @unserialize($scrow['acontent_form']);
                                            if(isset($scrow['acontent_form']['images']) && is_array($scrow['acontent_form']['images']) && count($scrow['acontent_form']['images'])) {
                                                $s_imgname = '';
                                                foreach($scrow['acontent_form']['images'] as $s_imgtext) {

                                                    $s_imgtext[6] = getImageCaption(array('caption' => $s_imgtext[6], 'file' => $s_imgtext[0]), '', true);

                                                    if($content['search']['search_caption']) {
                                                        if($s_imgtext[6]['caption']) {
                                                            $s_text .= ' '.$s_imgtext[6]['caption'];
                                                        } elseif($s_imgtext[6]['title']) {
                                                            $s_text .= ' '.$s_imgtext[6]['title'];
                                                        } elseif($s_imgtext[6]['alt']) {
                                                            $s_text .= ' '.$s_imgtext[6]['alt'];
                                                        }
                                                    }

                                                    if($content['search']['search_filename']) {
                                                        $s_imgname .= ' '.$s_imgtext[1];
                                                    }
                                                }
                                                $s_text .= $s_imgname;
                                            }
                                        }
                                        break;

                            case 31:    $s_text .= ' '.$scrow['acontent_html'];
                                        if($content['search']['search_caption'] || $content['search']['search_filename']) {
                                            $scrow['acontent_form'] = @unserialize($scrow['acontent_form']);
                                            if(isset($scrow['acontent_form']['images']) && is_array($scrow['acontent_form']['images']) && count($scrow['acontent_form']['images'])) {
                                                foreach($scrow['acontent_form']['images'] as $s_imgtext) {
                                                    if($content['search']['search_caption']) {
                                                        $s_text .= ' '.$s_imgtext['caption'];
                                                    }
                                                    if($content['search']['search_filename']) {
                                                        $s_text .= ' '.$s_imgtext['thumb_name'];
                                                        $s_text .= ' '.$s_imgtext['zoom_name'];
                                                    }
                                                }
                                            }
                                        }
                                        break;

                            // search recipe
                            case 26:    $s_text .= ' '.$scrow['acontent_text'].' '.$scrow['acontent_html'];
                                        $scrow['acontent_form'] = @unserialize($scrow['acontent_form']);
                                        if(isset($scrow['acontent_form']['preparation'])) {
                                            $s_text .= ' '.$scrow['acontent_form']['preparation'].' '.$scrow['acontent_form']['ingredients'];
                                            $s_text .= ' '.$scrow['acontent_form']['calorificvalue'].' '.$scrow['acontent_form']['calorificvalue_add'];
                                        }
                                        break;

                            // all other non defined CPs
                            default:    $s_text .= ' '.$scrow['acontent_text'].' '.$scrow['acontent_html'];

                        }
                    }
                }

                // Search for {SHOW_CONTENT}
                if(strpos($s_text, '{SHOW_CONTENT') !== false) {
                    $s_text = preg_replace_callback('/\{SHOW_CONTENT:(.*?)\}/', 'showSelectedContent', $s_text);
                }

                $s_text  = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $s_text);
                $s_text  = str_replace(array('~', '|', ':', 'http', '//', '_blank'), ' ', $s_text );
                $s_text .= ' --##-';
                if($content['search']['search_keyword']) {
                    $s_text .= $srow["article_keyword"].' ';
                }
                $s_text .= $s_title;
                if($content['search']['search_username']) {
                    $s_text .= ' '.$s_user;
                }
                $s_text .= '-##--';
                $s_text  = clean_search_text($s_text);

                $s_result = array();

                preg_match_all('/'.$s_search_words.'/is', $s_text, $s_result ); //search string

                $s_text     = preg_replace("/(<\/?)(\w+)([^>]*>)/i", '', $s_text);
                $s_count    = count($s_result[0]);

                if($s_count && SEARCH_TYPE_AND) {
                    $s_and_or = array();
                    foreach($s_result[0] as $svalue) {
                        $s_and_or[strtolower($svalue)] = 1;
                    }
                    $s_and_or = count($s_and_or);

                    if($s_and_or != $s_search_words_count) {
                        $s_count = 0;
                    }
                }

                if($s_count) {

                    $s_text = preg_replace('/--##-.*?-##--/', '', $s_text);

                    $s_list[$s_run]["id"]       = $s_id;
                    $s_list[$s_run]["cid"]      = $s_cid;
                    $s_list[$s_run]["rank"]     = $s_count;
                    $s_list[$s_run]["title"]    = html($s_title);
                    $s_list[$s_run]['subtitle'] = html($srow['article_subtitle']);
                    if($content['search']['highlight_result']) {
                        $s_list[$s_run]["title"]    = highlightSearchResult($s_list[$s_run]["title"], $content['highlight']);
                        $s_list[$s_run]['subtitle'] = highlightSearchResult($s_list[$s_run]['subtitle'], $content['highlight']);
                    }
                    $s_list[$s_run]["date"]     = $s_date;
                    $s_list[$s_run]["user"]     = $s_user;
                    $s_list[$s_run]['query']    = $srow['article_alias'] ? $srow['article_alias'] : 'aid='.$s_id;
                    $s_list[$s_run]['link']     = '';
                    $s_list[$s_run]["text"]     = '';
                    $s_list[$s_run]['image']    = false;
                    if($crow['template']['image_render'] && $srow["article_image"]) {
                        $srow["article_image"] = setArticleSummaryImageData(unserialize($srow["article_image"]));
                        if(!empty($srow["article_image"]['list_hash'])) {
                            $s_list[$s_run]['image'] = array(
                                'id'    => $srow["article_image"]['list_id'],
                                'hash'  => $srow["article_image"]['list_hash'],
                                'ext'   => $srow["article_image"]['list_ext'],
                                'name'  => $srow["article_image"]['list_name']
                            );
                        }
                    }

                    if($content['search']['show_summary'] && !empty($content['search']['wordlimit'])) {
                        $s_list[$s_run]["text"] = getCleanSubString($s_text, abs($content['search']['wordlimit']), $template_default['ellipse_sign'], $content['search']['wordlimit'] < 0 ? 'char' : 'word');
                        $s_list[$s_run]["text"] = html($s_list[$s_run]["text"], false);
                        if($content['search']['highlight_result']) {
                            $s_list[$s_run]["text"] = highlightSearchResult($s_list[$s_run]["text"], $content['highlight']);
                        }
                    }

                    $s_run++;
                }
            }
        }

        // at this point we inject search by module search results
        if(isset($content['search']['module']) && is_array($content['search']['module']) && count($content['search']['module'])) {
            foreach($content['search']['module'] as $key => $value) {
                if(isset($phpwcms['modules'][$key]) && is_file($phpwcms['modules'][$key]['path'].'frontend.search.php')) {

                    // include module search
                    include $phpwcms['modules'][$key]['path'].'frontend.search.php';

                }
            }
        }

        // news search
        if(!empty($content['search']['search_news']) && !empty($s_search_words) && isset($s_search_words_count)) {

            // initialize search for news
            $s_news = new search_News();

            // set current search result counter
            $s_news->search_result_entry        = $s_run;
            $s_news->search_words               = $s_search_words;
            $s_news->search_word_count          = $s_search_words_count;
            $s_news->search_highlight           = $content['search']['highlight_result'];
            $s_news->search_highlight_words     = $content['highlight'];
            $s_news->search_wordlimit           = $content['search']['wordlimit'];
            $s_news->search_category            = $content['search']['news_category'];
            $s_news->search_language            = $content['search']['news_lang'];
            $s_news->search_andor               = $content['search']['news_andor'];
            $s_news->ellipse_sign               = $template_default['ellipse_sign'];
            $s_news->search_target_url          = $content['search']['news_url'];
            $s_news->image_render               = $crow['template']['image_render'];
            $s_news->search_filename            = $content['search']['search_filename'];
            $s_news->search_username            = $content['search']['search_username'];
            $s_news->search_caption             = $content['search']['search_caption'];
            $s_news->search_keyword             = $content['search']['search_keyword'];

            $s_news->search();

            // add news search results
            $s_list += $s_news->search_results;

            // get final search result counter
            $s_run = $s_news->search_result_entry;

            unset($s_news);
        }

        if($s_run) {
            $crow['template']['text'] = $content['search']['text_html'] ? $content["search"]["text_result"] : plaintext_htmlencode($content['search']['text_result']);

            // create search result listing
            // ranking
            foreach($s_list as $s_key => $svalue) {
                $s_rank[$s_key] = $s_list[$s_key]["rank"];
            }
            arsort($s_rank, SORT_NUMERIC);

            //check result listing
            $_search_results        = count($s_rank);
            $_search_max_pages      = 1;
            $_search_current_page   = 1;
            $_search_next_page      = 1;
            $_search_prev_page      = 1;
            if($_search_results > $content["search"]["result_per_page"]) {
                $_search_max_pages      = ceil($_search_results / $content["search"]["result_per_page"]);
                $_search_current_page   = empty($_GET['searchstart']) ? 1 : intval($_GET['searchstart']);
                if($_search_current_page > $_search_max_pages) {
                    $_search_current_page = $_search_max_pages;
                } elseif($_search_current_page < 1) {
                    $_search_current_page = 1;
                }

                if($_search_current_page == 1) {
                    $_search_next_page = 2;
                    $_search_prev_page = 1;
                } elseif($_search_current_page == $_search_max_pages) {
                    $_search_next_page = $_search_current_page;
                    $_search_prev_page = $_search_current_page - 1;
                } else {
                    $_search_next_page = $_search_current_page + 1;
                    $_search_prev_page = $_search_current_page - 1;
                }
            }

            $_search_pagination_counter = 1;
            $_search_start_at           = ($_search_current_page-1) * $content["search"]["result_per_page"];
            $_search_end_at             = $content["search"]["result_per_page"] * $_search_current_page;
            $_search_link_basis         = rel_url(array(), array('searchstart', 'searchwords'), '___GOTO___');
            $_search_link_highlight     = rel_url(array('highlight' => '___HIGHLIGHT__'), array('searchstart', 'searchwords'), '___GOTO___');

            foreach($s_rank as $s_key => $svalue) {

                if($_search_pagination_counter <= $_search_start_at) {
                    $_search_pagination_counter++;
                    continue;
                }

                if(empty($s_list[$s_key]['link'])) {
                    if(strpos($s_list[$s_key]['query'], 'index.php') !== false || strpos($s_list[$s_key]['query'], 'http') === 0) {
                        $s_list[$s_key]['link'] = $s_list[$s_key]['query'];
                    } elseif($content['search']['highlight_result']) {
                        $s_list[$s_key]['link'] = str_replace(array('___GOTO___', '___HIGHLIGHT__'), array($s_list[$s_key]['query'], $s_result_highlight), $_search_link_highlight);
                    } else {
                        $s_list[$s_key]['link'] = str_replace('___GOTO___', $s_list[$s_key]['query'], $_search_link_basis);
                    }
                }

                $s_result_list[$s_key] = str_replace('{LINK}', $s_list[$s_key]['link'], $crow['template']['item']); // Replace link and set template
                $s_result_list[$s_key] = str_replace('{LINK_TARGET}', $content["search"]["newwin"] ? ' target="_blank"' : '', $s_result_list[$s_key]);
                $s_result_list[$s_key] = str_replace('{RANKING}', $s_list[$s_key]['rank'], $s_result_list[$s_key]);
                $s_result_list[$s_key] = render_cnt_template($s_result_list[$s_key], 'TITLE', $s_list[$s_key]["title"]);
                $s_result_list[$s_key] = render_cnt_template($s_result_list[$s_key], 'SUBTITLE', $s_list[$s_key]["subtitle"]);
                $s_result_list[$s_key] = render_cnt_template($s_result_list[$s_key], 'TEXT', $content['search']['show_summary'] ? $s_list[$s_key]["text"] : '');

                if($crow['template']['image_render'] && isset($s_list[$s_key]['image']['hash']) && is_file(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.$s_list[$s_key]['image']['hash'].'.'.$s_list[$s_key]['image']['ext'])) {
                    $s_result_list[$s_key] = str_replace('{IMAGE_HASH}', $s_list[$s_key]['image']['hash'], $s_result_list[$s_key]);
                    $s_result_list[$s_key] = str_replace('{IMAGE_ID}', $s_list[$s_key]['image']['id'], $s_result_list[$s_key]);
                    $s_result_list[$s_key] = str_replace('{IMAGE_NAME}', html($s_list[$s_key]['image']['name']), $s_result_list[$s_key]);
                    $s_result_list[$s_key] = str_replace('{IMAGE_EXT}', $s_list[$s_key]['image']['ext'], $s_result_list[$s_key]);
                    $s_result_list[$s_key] = render_cnt_template($s_result_list[$s_key], 'IMAGE', ' ');
                } else {
                    $s_result_list[$s_key] = render_cnt_template($s_result_list[$s_key], 'IMAGE', '');
                }

                if($_search_pagination_counter == $_search_end_at) {
                    break;
                } else {
                    $_search_pagination_counter++;
                }

            }

            if($_search_end_at > $_search_results) {
                $_search_end_at = $_search_results;
            }

            if($content["search"]["label_pages"]) {
                $crow['template']['pagination'] = $content["search"]["label_pages"];
                $crow['template']['paginate_custom'] = true;
            } elseif($crow['template']['pagination']) {
                $crow['template']['paginate_custom'] = false;
            } else {
                $crow['template']['pagination'] = '[PREV]{PREV:&laquo; Previous} | [/PREV]Page #/##[NEXT] | {NEXT:Next &raquo;}[/NEXT]';
                $crow['template']['paginate_custom'] = true;
            }

            $crow['template']['pagination'] = str_replace('#####', $_search_results, $crow['template']['pagination']);
            $crow['template']['pagination'] = str_replace('####', $_search_end_at, $crow['template']['pagination']);
            $crow['template']['pagination'] = str_replace('###', $_search_start_at+1, $crow['template']['pagination']);
            $crow['template']['pagination'] = str_replace('##', $_search_max_pages, $crow['template']['pagination']);
            $crow['template']['pagination'] = str_replace('#', $_search_current_page, $crow['template']['pagination']);

            $GLOBALS['_search_next_link_t'] = '';
            $GLOBALS['_search_prev_link_t'] = '';
            $GLOBALS['_search_navi']        = '';

            $crow['template']['pagination'] = preg_replace_callback('/\{NEXT:(.*?)\}/', 'get_PaginateNext', $crow['template']['pagination']);
            $crow['template']['pagination'] = preg_replace_callback('/\{PREV:(.*?)\}/', 'get_PaginatePrevious', $crow['template']['pagination']);
            $crow['template']['pagination'] = preg_replace_callback('/\{NAVI:(.*?)\}/', 'get_PaginateNavigate', $crow['template']['pagination']);

            // create link to search page
            unset($GLOBALS['_getVar']['searchstart']);
            $GLOBALS['_getVar']['searchwords'] = $s_result_highlight;
            $_search_page_link = rel_url(array('searchstart' => '___SEARCHSTART___'), array());

            $_search_next_link = '';
            $_search_prev_link = '';
            $_search_link_class = $template_default['classes']['search-paginate-link'] ? ' class="' . $template_default['classes']['search-paginate-link'] .'"' : '';
            $_search_link_active_class = $template_default['classes']['search-paginate-link-active'] ? ' class="' . $template_default['classes']['search-paginate-link-active'] .'"' : '';
            $_search_link_disabled_class = $template_default['classes']['search-paginate-link-disabled'] ? ' class="' . $template_default['classes']['search-paginate-link-disabled'] .'"' : '';

            if($_search_next_page !== $_search_current_page) {
                $_search_next_link .= '<a href="' . str_replace('___SEARCHSTART___', ($_search_current_page + 1), $_search_page_link) . '"';
                $_search_next_link .= $_search_link_class . '>' . $GLOBALS['_search_next_link_t'] . '</a>';
            } elseif($content["search"]["show_next"]) {
                $_search_next_link .= '<a ';
                if ($template_default['attributes']['cp-paginate']['href-disabled']) {
                    $_search_next_link .= 'href = "' . $template_default['attributes']['cp-paginate']['href-disabled'] . '" ';
                }
                $_search_next_link .= 'data-disabled="true" tabindex="-1" aria-disabled="true"' . $_search_link_disabled_class . '>';
                $_search_next_link .= $GLOBALS['_search_next_link_t'] . '</a>';
            }
            if($_search_prev_page !== $_search_current_page) {
                $_search_prev_link .= '<a href="' . str_replace('___SEARCHSTART___', ($_search_current_page - 1), $_search_page_link) . '"';
                $_search_prev_link .= $_search_link_class . '>' . $GLOBALS['_search_prev_link_t'] . '</a>';
            } elseif($content["search"]["show_prev"]) {
                $_search_prev_link .= '<a ';
                if ($template_default['attributes']['cp-paginate']['href-disabled']) {
                    $_search_prev_link .= 'href = "' . $template_default['attributes']['cp-paginate']['href-disabled'] . '" ';
                }
                $_search_prev_link .= 'data-disabled="true" tabindex="-1" aria-disabled="true"' . $_search_link_disabled_class . '>';
                $_search_prev_link .= $GLOBALS['_search_prev_link_t'] . '</a>';
            }
            $crow['template']['pagination'] = render_cnt_template($crow['template']['pagination'], 'NEXT', $_search_next_link);
            $crow['template']['pagination'] = render_cnt_template($crow['template']['pagination'], 'PREV', $_search_prev_link);

            $GLOBALS['_search_navi']    = explode(',', $GLOBALS['_search_navi'], 2);
            $GLOBALS['_search_navi'][0] = trim($GLOBALS['_search_navi'][0]);
            $GLOBALS['_search_navi'][1] = empty($GLOBALS['_search_navi'][1]) ? array(' ', '', '') : explode('|', $GLOBALS['_search_navi'][1]);

            if($GLOBALS['_search_navi'][0] === '123') {

                $GLOBALS['_search_navi'][1][0] = empty($GLOBALS['_search_navi'][1][0]) ? ' ' : $GLOBALS['_search_navi'][1][0]; // spacer
                $GLOBALS['_search_navi'][1][1] = empty($GLOBALS['_search_navi'][1][1]) ? '' : $GLOBALS['_search_navi'][1][1]; // link prefix
                $GLOBALS['_search_navi'][1][2] = empty($GLOBALS['_search_navi'][1][2]) ? '' : $GLOBALS['_search_navi'][1][2]; // link suffix

                $_search_navi_x = array();
                for($_search_page_i = 1; $_search_page_i <= $_search_max_pages; $_search_page_i++) {

                    $_search_navi_x[$_search_page_i]  = $GLOBALS['_search_navi'][1][1];
                    $_search_navi_x[$_search_page_i] .= '<a href="' . str_replace('___SEARCHSTART___', $_search_page_i, $_search_page_link) . '"';
                    $_search_navi_x[$_search_page_i] .= $_search_current_page === $_search_page_i ? $_search_link_active_class : $_search_link_class;
                    $_search_navi_x[$_search_page_i] .= '>' . $_search_page_i . '</a>';
                    $_search_navi_x[$_search_page_i] .= $GLOBALS['_search_navi'][1][2];

                }
                $GLOBALS['_search_navi'] = implode($GLOBALS['_search_navi'][1][0], $_search_navi_x);

            } elseif($GLOBALS['_search_navi'][0] == '1-3') {

                $GLOBALS['_search_navi'][1][0] = empty($GLOBALS['_search_navi'][1][0]) ? ' ' : $GLOBALS['_search_navi'][1][0]; // spacer
                $GLOBALS['_search_navi'][1][1] = empty($GLOBALS['_search_navi'][1][1]) ? '' : $GLOBALS['_search_navi'][1][1]; // link prefix
                $GLOBALS['_search_navi'][1][2] = empty($GLOBALS['_search_navi'][1][2]) ? '' : $GLOBALS['_search_navi'][1][2]; // link suffix

                $_search_navi_x = array();
                for($_search_page_i = 1; $_search_page_i <= $_search_max_pages; $_search_page_i++) {

                    $_search_navi_x[$_search_page_i] = $GLOBALS['_search_navi'][1][1];
                    $_search_page_i_start = ($_search_page_i-1) * $content["search"]["result_per_page"];
                    $_search_page_i_end = $_search_page_i_start + $content["search"]["result_per_page"];
                    if($_search_results < $_search_page_i_end) {
                        $_search_page_i_end = $_search_results;
                    }
                    $_search_page_i_start++;

                    $_search_navi_x[$_search_page_i] .= '<a href="' . str_replace('___SEARCHSTART___', $_search_page_i, $_search_page_link) . '"';
                    $_search_navi_x[$_search_page_i] .= $_search_current_page === $_search_page_i ? $_search_link_active_class : $_search_link_class;
                    $_search_navi_x[$_search_page_i] .= '>' . $_search_page_i_start . '&ndash;' . $_search_page_i_end . '</a>';
                    $_search_navi_x[$_search_page_i] .= $GLOBALS['_search_navi'][1][2];

                }
                $GLOBALS['_search_navi'] = implode($GLOBALS['_search_navi'][1][0], $_search_navi_x);

            } else {

                $GLOBALS['_search_navi'] = '';

            }

            $crow['template']['pagination'] = render_cnt_template($crow['template']['pagination'], 'NAVI', $GLOBALS['_search_navi']);
            if($crow['template']['paginate_custom']) {
                $crow['template']['pagination'] = '<div class="'.$template_default['classes']['search-nextprev'].'">' . $crow['template']['pagination'] . '</div>';
            }

        } else {

            $crow['template']['text'] = $content['search']['text_html'] ? $content["search"]["text_noresult"] : plaintext_htmlencode($content['search']['text_noresult']);

        }

    } else {

        $crow['template']['text'] = $content['search']['text_html'] ? $content["search"]["text_noresult"] : plaintext_htmlencode($content['search']['text_noresult']);

    }

} else {

    $crow['template']['text'] = $content['search']['text_html'] ? $content["search"]["text_intro"] : plaintext_htmlencode($content['search']['text_intro']);

}

$content["search_word"] = count($content['highlight']) ? html(implode(' ', $content['highlight'])) : '';

$crow['template']['result']  = $crow['template']['header'];
$crow['template']['result'] .= $crow['template']['footer'];

if(isset($content["search"]["result_per_page"])) {

    //build search form
    unset($GLOBALS['_getVar']['searchwords'], $GLOBALS['_getVar']['searchstart']);

    if(empty($content["search"]["label_button"])) {
        $content["search"]["label_button"] = '@@Search@@';
    }

    $crow['template']['form'] = ' ';

    if(strpos($crow['template']['result'], '{FORM}') !== false) {

        $crow['template']['form'] = '<div class="search_form"';
        if($content["search"]["align"] === 1) {
            $crow['template']['form'] .= ' style="text-align:right;"';
        } elseif($content["search"]["align"] === 2) {
            $crow['template']['form'] .= ' style="text-align:center;"';
        }
        $crow['template']['form'] .= '>';
        $crow['template']['form'] .= '<form action="' . rel_url() . '" method="post">'.LF;
        $crow['template']['form'] .= '<table cellspacing="0" cellpadding="0" border="0" summary="Search"><tr>';
        if($content["search"]["label_input"]) {
            $crow['template']['form'] .= '<td class="formLabel">';
            $crow['template']['form'] .= $content["search"]["label_input"]."</td>";
        }
        $crow['template']['form'] .= '<td class="formSearch">';
        $crow['template']['form'] .= '<input name="search_input_field" id="search_input_field" type="search" size="30" maxlength="200" ';
        $crow['template']['form'] .= 'value="'.$content["search_word"].'"';
        if($content["search"]["style_input"]) {
            $crow['template']['form'] .= ' class="'.$content["search"]["style_input"].'"';
        }
        $crow['template']['form'] .= " /></td><td>";
        $crow['template']['form'] .= '<input type="submit" name="submit" id="search_submit_button" value="';

        $crow['template']['form'] .= $content["search"]["label_button"];
        $crow['template']['form'] .= '"';
        if($content["search"]["style_button"]) {
            $crow['template']['form'] .= ' class="'.$content["search"]["style_button"].'"';
        }
        $crow['template']['form'] .= " /></td>";
        $crow['template']['form'] .= "</tr></table></form></div>";

    } else {

        $crow['template']['result'] = render_cnt_template($crow['template']['result'], 'SEARCH_INPUT_LABEL', $content["search"]["label_input"]);
        $crow['template']['result'] = str_replace(
            array(
                '{SEARCH_INPUT}',
                '{SEARCH_ACTION}',
                '{SEARCH_BUTTON}',
                '{SEARCH_VALUE}'
            ), array(
                'search_input_field',
                rel_url(),
                $content["search"]["label_button"],
                $content["search_word"]
            ),
            $crow['template']['result']
        );
    }
}

$crow['template']['result'] = render_cnt_template($crow['template']['result'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
$crow['template']['result'] = render_cnt_template($crow['template']['result'], 'ATTR_ID', html($crow['acontent_attr_id']));
$crow['template']['result'] = render_cnt_template($crow['template']['result'], 'TITLE', html($crow["acontent_title"]));
$crow['template']['result'] = render_cnt_template($crow['template']['result'], 'SUBTITLE', html($crow["acontent_subtitle"]));
$crow['template']['result'] = render_cnt_template($crow['template']['result'], 'TEXT', $crow['template']['text']);
$crow['template']['result'] = render_cnt_template($crow['template']['result'], 'FORM', $crow['template']['form']);

if(count($s_result_list)) {

    if($content["search"]["show_top"] && ($_search_max_pages > 1 || $content["search"]["show_always"])) {
        //$s_result_listing .= $crow['template']['pagination'];
        $crow['template']['result'] = render_cnt_template($crow['template']['result'], 'PAGINATE_TOP', $crow['template']['pagination']);
    } else {
        $crow['template']['result'] = render_cnt_template($crow['template']['result'], 'PAGINATE_TOP', '');
    }

    if($content["search"]["show_bottom"] && ($_search_max_pages > 1 || $content["search"]["show_always"])) {
        //$s_result_listing .= $crow['template']['pagination'];
        $crow['template']['result'] = render_cnt_template($crow['template']['result'], 'PAGINATE_BOTTOM', $crow['template']['pagination']);
    } else {
        $crow['template']['result'] = render_cnt_template($crow['template']['result'], 'PAGINATE_BOTTOM', '');
    }

    $crow['template']['result'] = render_cnt_template($crow['template']['result'], 'RESULTS', implode($crow['template']['item_space'], $s_result_list));

} else {

    $crow['template']['result'] = render_cnt_template($crow['template']['result'], 'RESULTS', '');
    $crow['template']['result'] = render_cnt_template($crow['template']['result'], 'PAGINATE_TOP', '');
    $crow['template']['result'] = render_cnt_template($crow['template']['result'], 'PAGINATE_BOTTOM', '');

}

$CNT_TMP .= $crow['template']['result'];

unset($crow['template']);
