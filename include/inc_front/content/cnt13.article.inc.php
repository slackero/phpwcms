<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//search form
$CNT_TMP				.= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$content["search"]		 = unserialize($crow["acontent_form"]);
$s_result_list			 = '';
$content["search_word"]	 = '';
$content['highlight']	 = array();
$s_list					 = array();
define('SEARCH_TYPE_AND', empty($content['search']['type']) || $content['search']['type'] == 'OR' ? FALSE : TRUE);

if(empty($content['search']["text_html"])) {
	$content['search']['text_html'] = 0;
}

$content['search']['search_filenames'] = empty($content['search']["no_filenames"]) ? true : false;	// search/list for file/imagenames
$content['search']['show_summary'] = empty($content['search']["hide_summary"]) ? true : false; // show search tester text

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

	$content['search']['highlight_result']	= empty($content["search"]['highlight_result']) ? false : true;
	$content['search']['wordlimit']			= isset($content["search"]['wordlimit']) && is_intval($content["search"]['wordlimit']) ? intval($content["search"]['wordlimit']) : 35;

	$content["search"]["result_per_page"]	= empty($content["search"]['result_per_page']) ? 15 : $content["search"]['result_per_page'];
	if($content["search"]["result_per_page"] == -1)  {
		$content["search"]["result_per_page"] = 100000;
	}

	if(!isset($content["search"]["show_always"]))	$content["search"]["show_always"] 	= 1;
	if(!isset($content["search"]["show_top"]))		$content["search"]["show_top"] 		= 1;
	if(!isset($content["search"]["show_bottom"]))	$content["search"]["show_bottom"] 	= 1;
	if(!isset($content["search"]["show_next"]))		$content["search"]["show_next"] 	= 1;
	if(!isset($content["search"]["show_prev"]))		$content["search"]["show_prev"] 	= 1;
	if(!isset($content["search"]["minchar"]))		$content["search"]["minchar"]		= 3;

	if(!isset($content["search"]["start_at"]) || !is_array($content["search"]["start_at"])) {
		$content["search"]["start_at"] = array(0);
	}

	// include neccessary frontend functions, but only once
	include_once(PHPWCMS_ROOT.'/include/inc_front/content/cnt_functions/cnt13.func.inc.php');
	$content["search"]["start_at"] = get_SearchForStructureID($content["search"]["start_at"]);

	$content['highlight'] = array();
	foreach($content["search_word"] as $key => $value) {
		//$_strlen_value = MB_SAFE ? mb_strlen($value) : strlen($value);
		$_strlen_value = strlen($value);
		if($_strlen_value >= $content["search"]["minchar"]) {
			$value = trim($value);
			$content["search_word"][$key] = preg_quote($value);
			$content["search_word"][$key] = str_replace("\\?", '.?', $content["search_word"][$key]);
			$content["search_word"][$key] = str_replace("\\*", '.*', $content["search_word"][$key]);
			$content['highlight'][] = $value;
		}
	}

	if(count($content['highlight'])) {

		$s_result_highlight = implode(' ', $content['highlight']);

		if(!empty($_POST["search_input_field"])) {
			// make a redirection to avoid message when using browser back
			$GLOBALS['_getVar']['searchstart'] = 1;
			$GLOBALS['_getVar']['searchwords'] = $s_result_highlight;
			headerRedirect(abs_url(array(), array(), '', 'rawurlencode'));
		}

		$s_result_highlight = rawurlencode($s_result_highlight);

		$sql  = "SELECT article_id, article_cid, article_title, article_username, article_subtitle, ";
		$sql .= "article_summary, article_keyword, UNIX_TIMESTAMP(article_tstamp) AS article_date ";
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

		$sql .= " AND ac.acat_nosearch != 1 AND ac.acat_aktiv=1 AND ac.acat_public=1 AND ";
		if(!FEUSER_LOGIN_STATUS) {
			$sql .= "ac.acat_regonly=0 AND ";
		}
		$sql .= "ar.article_public=1 AND ar.article_aktiv=1 AND ar.article_deleted=0 AND ar.article_nosearch!=1 AND ";
		// enhanced IF statement by kh 2008/12/03
		$sql .= "IF((ar.article_begin < NOW() AND ar.article_end > NOW()) OR (ar.article_archive_status=1 AND ac.acat_archive=1), 1, 0) ";
		$sql .= "GROUP BY ar.article_id";

		if($sresult = mysql_query($sql, $db)) {
			$s_search_words			= implode('|', $content["search_word"]);
			$s_search_words_count	= count($content["search_word"]);

			while($srow = mysql_fetch_assoc($sresult)) {

				// read article base info for search
				$s_id		= $srow["article_id"];
				$s_cid		= $srow["article_cid"];
				$s_title	= $srow["article_title"];
				$s_date		= $srow["article_date"];
				$s_user		= $srow["article_username"];
				$s_text		= $srow["article_subtitle"].' '.$srow["article_summary"];

				// read article content for search
				$csql  = "SELECT acontent_title, acontent_subtitle, acontent_text, acontent_html, acontent_files, acontent_type, acontent_form, acontent_image FROM ";
				$csql .= DB_PREPEND."phpwcms_articlecontent WHERE acontent_aid=".$s_id." ";
				$csql .= "AND acontent_visible=1 AND acontent_trash=0 AND ";
				if( !FEUSER_LOGIN_STATUS ) {
					$csql .= 'acontent_granted=0 AND ';
				}
				$csql .= "acontent_type IN (0, 1, 2, 4, 5, 6, 7, 11, 14, 26, 27, 29, 100, 31, 32)";


				if($scresult = mysql_query($csql, $db)) {
					while($scrow = mysql_fetch_row($scresult)) {

						// always title, subtitle
						$s_text .= ' '.$scrow[0].' '.$scrow[1];

						switch($scrow[5]) {

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
							case 14:	$s_text .= ' '.$scrow[3];
										break;

							// only TEXT
							case 0:
							case 4:
							case 5:
							case 11:
							case 32:
							case 100:	$s_text .= ' '.$scrow[2];
										break;

							case 7:		// file list, get files listed here
										$s_text .= ' '.$scrow[2];
										if($content['search']['search_filenames']) {
											$s_files = getFileInformation( explode(':', $scrow[4]) );
											if(is_array($s_files) && count($s_files)) {
												// retrieve file information
												foreach($s_files as $s_files_value) {
													$s_text .= ' '.$s_files_value['f_name'];
												}
											}
										}
										break;

							// optimize images for search
							case 1	:	$s_text .= ' '.$scrow[2];
										if($content['search']['search_filenames'] && $scrow[7]) {
											$scrow[7] = explode(":", $scrow[7]);
											$s_text .= ' '.$scrow[1];
										}
										break;

							case 29:	$s_text .= ' '.$scrow[2];
							case 2:		$scrow[6] = @unserialize($scrow[6]);
										if(isset($scrow[6]['images']) && is_array($scrow[6]['images']) && count($scrow[6]['images'])) {
											$s_imgname = '';
											foreach($scrow[6]['images'] as $s_imgtext) {

												$s_imgtext[6] = getImageCaption($s_imgtext[6], '', true);

												if($s_imgtext[6]['caption']) {
													$s_text .= ' '.$s_imgtext[6]['caption'];
												} elseif($s_imgtext[6]['title']) {
													$s_text .= ' '.$s_imgtext[6]['title'];
												} elseif($s_imgtext[6]['alt']) {
													$s_text .= ' '.$s_imgtext[6]['alt'];
												}

												if($content['search']['search_filenames']) {
													$s_imgname .= ' '.$s_imgtext[1];
												}
											}
											$s_text .= $s_imgname;
										}
										break;

							case 31:	$s_text .= ' '.$scrow[3];
										$scrow[6] = @unserialize($scrow[6]);
										if(isset($scrow[6]['images']) && is_array($scrow[6]['images']) && count($scrow[6]['images'])) {
											foreach($scrow[6]['images'] as $s_imgtext) {
												$s_text .= ' '.$s_imgtext['caption'];
												//$s_text .= ' '.$s_imgtext['url'];
												if($content['search']['search_filenames']) {
													$s_text .= ' '.$s_imgtext['thumb_name'];
													$s_text .= ' '.$s_imgtext['zoom_name'];
												}
											}
										}
										break;

							// search recipe
							case 26:	$s_text .= ' '.$scrow[2].' '.$scrow[3];
										$scrow[6] = @unserialize($scrow[6]);
										if(isset($scrow[6]['preparation'])) {
											$s_text .= ' '.$scrow[6]['preparation'].' '.$scrow[6]['ingredients'];
											$s_text .= ' '.$scrow[6]['calorificvalue'].' '.$scrow[6]['calorificvalue_add'];
										}
										break;

							// all other non defined CPs
							default:	$s_text .= ' '.$scrow[2].' '.$scrow[3];

						}
						$s_text = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $s_text); // strip all <script> Tags
						$s_text = str_replace(array('~', '|', ':', 'http', '//', '_blank'), ' ', $s_text );

					}
					mysql_free_result($scresult);
				}

				$s_result	= array();
				$s_text		= clean_search_text($s_text.' --##-'.$srow["article_keyword"].' '.$s_title.' '.$s_user.'-##--');

				preg_match_all('/'.$s_search_words.'/is', $s_text, $s_result ); //search string

				$s_text		= preg_replace("/(<\/?)(\w+)([^>]*>)/i", '', $s_text);
				$s_count	= count($s_result[0]);

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

					$s_list[$s_run]["id"]		= $s_id;
					$s_list[$s_run]["cid"]		= $s_cid;
					$s_list[$s_run]["rank"]		= $s_count;
					$s_list[$s_run]["title"]	= $content['search']['highlight_result'] ? highlightSearchResult($s_title, $content['highlight']) : $s_title;
					$s_list[$s_run]["date"]		= $s_date;
					$s_list[$s_run]["user"]		= $s_user;
					$s_list[$s_run]['query']	= 'aid='.$s_id;

					if($content['search']['show_summary'] && $content['search']['wordlimit'] > 0) {
						$s_list[$s_run]["text"]	= getCleanSubString($s_text, $content['search']['wordlimit'], $template_default['ellipse_sign'], 'word');
						$s_list[$s_run]["text"]	= html_specialchars($s_list[$s_run]["text"]);
						if($content['search']['highlight_result']) {
							$s_list[$s_run]["text"] = highlightSearchResult($s_list[$s_run]["text"], $content['highlight']);
						}
					} else {
						$s_list[$s_run]["text"]	= '';
					}

					$s_run++;
				}
			}
			mysql_free_result($sresult);

		}

		// at this point we inject search by module search results
		if(isset($content['search']['module']) && is_array($content['search']['module']) && count($content['search']['module'])) {
			foreach($content['search']['module'] as $key => $value) {
				if(isset($phpwcms['modules'][$key]) && is_file($phpwcms['modules'][$key]['path'].'frontend.search.php')) {

					// include module search
					include($phpwcms['modules'][$key]['path'].'frontend.search.php');

				}
			}
		}

		// news search
		if(!empty($content['search']['search_news'])) {

			// initialize search for news
			$s_news = new search_News();

			// set current search result counter
			$s_news->search_result_entry		= $s_run;
			$s_news->search_words				= $s_search_words;
			$s_news->search_word_count			= $s_search_words_count;
			$s_news->search_highlight			= $content['search']['highlight_result'];
			$s_news->search_highlight_words		= $content['highlight'];
			$s_news->search_wordlimit			= $content['search']['wordlimit'];
			$s_news->search_category			= $content['search']['news_category'];
			$s_news->search_language			= $content['search']['news_lang'];
			$s_news->search_andor				= $content['search']['news_andor'];
			$s_news->ellipse_sign				= $template_default['ellipse_sign'];
			$s_news->search_target_url			= $content['search']['news_url'];

			$s_news->search();

			// add news search results
			$s_list += $s_news->search_results;

			// get final search result counter
			$s_run = $s_news->search_result_entry;

			unset($s_news);
		}


		if($s_run) {
			$CNT_TMP .= $content['search']['text_html'] ? $content["search"]["text_result"] : nl2br(html_specialchars($content['search']['text_result']));

			// create search result listing
			// ranking
			foreach($s_list as $s_key => $svalue) {
				$s_rank[$s_key] = $s_list[$s_key]["rank"];
			}
			arsort($s_rank, SORT_NUMERIC);

			//check result listing
			$_search_results		= count($s_rank);
			$_search_max_pages		= 1;
			$_search_current_page	= 1;
			$_search_next_page		= 1;
			$_search_prev_page		= 1;
			if($_search_results > $content["search"]["result_per_page"]) {
				$_search_max_pages		= ceil($_search_results / $content["search"]["result_per_page"]);
				$_search_current_page	= empty($_GET['searchstart']) ? 1 : intval($_GET['searchstart']);
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

			$_search_pagination_counter	= 1;
			$_search_start_at			= ($_search_current_page-1) * $content["search"]["result_per_page"];
			$_search_end_at				= $content["search"]["result_per_page"] * $_search_current_page;

			foreach($s_rank as $s_key => $svalue) {

				if($_search_pagination_counter <= $_search_start_at) {
					$_search_pagination_counter++;
					continue;
				}

				$s_result_list .= '<div class="'.$template_default['classes']['search-result-item'].'">'.LF;
				$s_result_list .= '	<h3><a href="';

				if(strpos($s_list[$s_key]['query'], 'index.php') !== false || strpos($s_list[$s_key]['query'], 'http') === 0) {
					$s_result_list .= $s_list[$s_key]['query'];
				} else {
					$s_result_list .= 'index.php?'.$s_list[$s_key]['query'];
				}

				if($content['search']['highlight_result']) {
					$s_result_list .= '&amp;highlight='.$s_result_highlight;
				}
				$s_result_list .= ($content["search"]["newwin"]) ? '" target="_blank">' : '">';
				$s_result_list .= $s_list[$s_key]["title"].'</a></h3>'.LF;

				if($content['search']['show_summary'] && $s_list[$s_key]["text"]) {
					$s_result_list .= '	<p>'.$s_list[$s_key]["text"].'</p>'.LF;
				}
				$s_result_list .= '</div>'.LF;

				if($_search_pagination_counter == $_search_end_at) {
					break;
				} else {
					$_search_pagination_counter++;
				}

			}

			$_search_next_link = '';
			$_search_prev_link = '';
			$_search_linkblock = '';

			// create link to search page
			unset($GLOBALS['_getVar']['searchstart']);
			$GLOBALS['_getVar']['searchwords'] = $s_result_highlight;
			$_search_page_link = rel_url();

			if($_search_end_at > $_search_results) $_search_end_at = $_search_results;

			$_search_pages_of  = $content["search"]["label_pages"];
			$_search_pages_of  = str_replace('#####',	$_search_results, 		$_search_pages_of);
			$_search_pages_of  = str_replace('####',	$_search_end_at, 		$_search_pages_of);
			$_search_pages_of  = str_replace('###', 	$_search_start_at+1,	$_search_pages_of);
			$_search_pages_of  = str_replace('##', 		$_search_max_pages, 	$_search_pages_of);
			$_search_pages_of  = str_replace('#', 		$_search_current_page, 	$_search_pages_of);

			if($_search_next_page != $_search_current_page) {

				$_search_next_link = '<a href="'.$_search_page_link.'&amp;searchstart='. ($_search_current_page + 1 ).'">';

			}
			if($_search_prev_page != $_search_current_page) {

				$_search_prev_link = '<a href="'.$_search_page_link.'&amp;searchstart='. ($_search_current_page - 1 ).'">';

			}

			$GLOBALS['_search_next_link_t']	= '';
			$GLOBALS['_search_prev_link_t']	= '';
			$GLOBALS['_search_navi'] 		= '';

			$_search_pages_of = preg_replace_callback('/\{NEXT:(.*?)\}/', create_function('$matches', '$GLOBALS["_search_next_link_t"]=$matches[1]; return "{NEXT}";'), $_search_pages_of);
			$_search_pages_of = preg_replace_callback('/\{PREV:(.*?)\}/', create_function('$matches', '$GLOBALS["_search_prev_link_t"]=$matches[1]; return "{PREV}";'), $_search_pages_of);
			$_search_pages_of = preg_replace_callback('/\{NAVI:(.*?)\}/', create_function('$matches', '$GLOBALS["_search_navi"]=$matches[1]; return "{NAVI}";'), $_search_pages_of);

			if($_search_prev_link) {
				$_search_prev_link = $_search_prev_link.$GLOBALS['_search_prev_link_t'].'</a>';
			} elseif($content["search"]["show_prev"]) {
				$_search_prev_link = $GLOBALS['_search_prev_link_t'];
			}
			if($_search_next_link) {
				$_search_next_link = $_search_next_link.$GLOBALS['_search_next_link_t'].'</a>';
			} elseif($content["search"]["show_next"]) {
				$_search_next_link = $GLOBALS['_search_next_link_t'];
			}

			$_search_pages_of = str_replace('{NEXT}', $_search_next_link, $_search_pages_of);
			$_search_pages_of = str_replace('{PREV}', $_search_prev_link, $_search_pages_of);


			$GLOBALS['_search_navi'] 	= explode(',', $GLOBALS['_search_navi'], 2);
			$GLOBALS['_search_navi'][0] = trim($GLOBALS['_search_navi'][0]);
			$GLOBALS['_search_navi'][1]	= empty($GLOBALS['_search_navi'][1]) ? '' : explode('|', $GLOBALS['_search_navi'][1]);

			if($GLOBALS['_search_navi'][0] == '123') {

				$GLOBALS['_search_navi'][1][0] = empty($GLOBALS['_search_navi'][1][0]) ? ' ' : $GLOBALS['_search_navi'][1][0]; // spacer
				$GLOBALS['_search_navi'][1][1] = empty($GLOBALS['_search_navi'][1][1]) ? '' : $GLOBALS['_search_navi'][1][1]; // link prefix
				$GLOBALS['_search_navi'][1][2] = empty($GLOBALS['_search_navi'][1][2]) ? '' : $GLOBALS['_search_navi'][1][2]; // link suffix

				$_search_navi_x = array();
				for($_search_page_i = 1; $_search_page_i <= $_search_max_pages; $_search_page_i++) {

					$_search_navi_x[$_search_page_i]  = $GLOBALS['_search_navi'][1][1];
					if($_search_current_page == $_search_page_i) {
						$_search_navi_x[$_search_page_i] .= $_search_page_i;
					} else {
						$_search_navi_x[$_search_page_i] .= '<a href="'.$_search_page_link.'&amp;searchstart='. $_search_page_i .'">' . $_search_page_i . '</a>';
					}
					$_search_navi_x[$_search_page_i] .= $GLOBALS['_search_navi'][1][2];

				}
				$GLOBALS['_search_navi'] = implode($GLOBALS['_search_navi'][1][0], $_search_navi_x);

			} elseif($GLOBALS['_search_navi'][0] == '1-3') {

				$GLOBALS['_search_navi'][1][0] = empty($GLOBALS['_search_navi'][1][0]) ? ' ' : $GLOBALS['_search_navi'][1][0]; // spacer
				$GLOBALS['_search_navi'][1][1] = empty($GLOBALS['_search_navi'][1][1]) ? '' : $GLOBALS['_search_navi'][1][1]; // link prefix
				$GLOBALS['_search_navi'][1][2] = empty($GLOBALS['_search_navi'][1][2]) ? '' : $GLOBALS['_search_navi'][1][2]; // link suffix

				$_search_navi_x = array();
				for($_search_page_i = 1; $_search_page_i <= $_search_max_pages; $_search_page_i++) {

					$_search_navi_x[$_search_page_i]  = $GLOBALS['_search_navi'][1][1];
					$_search_page_i_start	= ($_search_page_i-1) * $content["search"]["result_per_page"];
					$_search_page_i_end		= $_search_page_i_start + $content["search"]["result_per_page"];
					if($_search_results < $_search_page_i_end) {
						$_search_page_i_end = $_search_results;
					}
					$_search_page_i_start++;
					if($_search_current_page == $_search_page_i) {
						$_search_navi_x[$_search_page_i] .= $_search_page_i_start.'-'.$_search_page_i_end;
					} else {
						$_search_navi_x[$_search_page_i] .= '<a href="'.$_search_page_link.'&amp;searchstart='. $_search_page_i .'">' . $_search_page_i_start.'-'.$_search_page_i_end . '</a>';
					}
					$_search_navi_x[$_search_page_i] .= $GLOBALS['_search_navi'][1][2];

				}
				$GLOBALS['_search_navi'] = implode($GLOBALS['_search_navi'][1][0], $_search_navi_x);


			} else {
				$GLOBALS['_search_navi'] = '';
			}
			$_search_pages_of = str_replace('{NAVI}', $GLOBALS['_search_navi'], $_search_pages_of);

			$_search_linkblock  = '<div class="'.$template_default['classes']['search-nextprev'].'">';
			$_search_linkblock .= $_search_pages_of;
			$_search_linkblock .= '</div>' . LF;

			if($s_result_list) {
				$s_result_listing  = '<div class="'. ($content["search"]["style_result"] ? $content["search"]["style_result"] : $template_default['classes']['search-result']) .'">';
				if($content["search"]["show_top"] && ($_search_max_pages > 1 || $content["search"]["show_always"])) {
					$s_result_listing .= $_search_linkblock;
				}
				$s_result_listing .= $s_result_list;
				if($content["search"]["show_bottom"] && ($_search_max_pages > 1 || $content["search"]["show_always"])) {
					$s_result_listing .= $_search_linkblock;
				}
				$s_result_listing .= '</div>';

				$s_result_list = $s_result_listing;
			}

		} else {

			$CNT_TMP .= $content['search']['text_html'] ? $content["search"]["text_noresult"] : nl2br(html_specialchars($content['search']['text_noresult']));
		}

	} else {

		$CNT_TMP .= $content['search']['text_html'] ? $content["search"]["text_noresult"] : nl2br(html_specialchars($content['search']['text_noresult']));

	}
} else {

	$CNT_TMP .= $content['search']['text_html'] ? $content["search"]["text_intro"] : nl2br(html_specialchars($content['search']['text_intro']));

}

if(count($content['highlight'])) {
	$content["search_word"] = html_specialchars(implode(' ', $content['highlight']));
} else {
	$content["search_word"] = '';
}

if(isset($content["search"]["result_per_page"])) {

	//build search form
	$CNT_TMP .= LF;
	$CNT_TMP .= '<div class="search_form"';
	switch($content["search"]["align"]) {
		case 1: $CNT_TMP .= ' align="right"'; break;
		case 2: $CNT_TMP .= ' align="center"'; break;
	}
	$CNT_TMP .= '>';

	unset($GLOBALS['_getVar']['searchwords'], $GLOBALS['_getVar']['searchstart']);

	$CNT_TMP .= '<form action="' . rel_url() . '" method="post">'.LF;
	$CNT_TMP .= '<table cellspacing="0" cellpadding="0" border="0" summary="Search">'.LF.'<tr>'.LF;
	if($content["search"]["label_input"]) {
		$CNT_TMP .= '<td class="formLabel">';
		$CNT_TMP .= $content["search"]["label_input"]."</td>\n<td>&nbsp;</td>\n";
	}
	$CNT_TMP .= '<td class="formSearch">';
	$CNT_TMP .= '<input name="search_input_field" id="search_input_field" type="text" size="20" maxlength="200" ';
	$CNT_TMP .= 'value="'.$content["search_word"].'"';
	if($content["search"]["style_input"]) {
		$CNT_TMP .= ' class="'.$content["search"]["style_input"].'"';
	}
	$CNT_TMP .= " /></td>\n<td>&nbsp;</td>\n<td>";
	$CNT_TMP .= '<input type="submit" name="submit" id="search_submit_button" value="';
	$CNT_TMP .= ($content["search"]["label_button"]) ? $content["search"]["label_button"] : 'Search';
	$CNT_TMP .= '"';
	if($content["search"]["style_button"]) {
		$CNT_TMP .= ' class="'.$content["search"]["style_button"].'"';
	}
	$CNT_TMP .= " /></td>\n";
	$CNT_TMP .= "</tr>\n</table>\n</form>\n</div>\n";
}

$CNT_TMP .= $s_result_list;


?>