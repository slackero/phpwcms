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

// neccessary frontend functions for search
function get_SearchForStructureID($search_at) {

	$k = array();

	if(is_array($search_at) && count($search_at)) {

		if(in_array(0, $search_at)) {
			return $k;
		}

		$m = search_buildMenuPathStructure();
		$m = explode(LF, $m);
		$m = array_unique($m);
		$m = array_diff($m, array(''));
		$k = $m;
		$d = implode(LF, $m);
		$c = 0;
		foreach($m as $key => $value) {
			preg_match_all('/'.trim($value).'/', $d, $match);
			if(count($match[0]) > 1) {
				unset($k[$key]);
			}
			$c++;
		}

		$_search_at_this = '';

		foreach($search_at as $_search_start_ID) {

			foreach($k as $_search_path) {

				$_search_path = '  '.$_search_path.'  ';
				$_this_pos = strpos($_search_path, ' '.$_search_start_ID.' ');

				if($_this_pos) {
					$_search_at_this .= ' '.trim(substr($_search_path, $_this_pos));
				}

			}
		}

		if($_search_at_this) {

			$k = explode(' ', trim($_search_at_this));
			$k = array_unique($k);

		} else {
			$k = array();
		}

	}

	return $k;
}

function search_buildMenuPathStructure($start_id=0, $counter=0, $pre = '') {

	global $content;

	$li	= '';

	foreach($content['struct'] as $key => $value) {

		if($key && $content['struct'][$key]['acat_struct'] == $start_id && empty($content['struct'][$key]['acat_nosearch'])) {

			$g   = $pre.$key.' ' ;
			$li .= $g;
			$li .= search_buildMenuPathStructure($key, $counter+1, LF.$g);

		}
	}

	return $li . LF;
}

class search_News {

	var $search_words			= '';
	var $search_word_count		= 0;
	var $search_result_entry	= 0;
	var $search_results			= array();
	var $search_highlight		= false;
	var $search_highlight_words	= false;
	var $search_wordlimit		= 0;
	var $search_target_url		= '';
	var $now					= 0;
	var $search_category		= array();
	var $search_language		= array();
	var $search_andor			= 'OR';
	var $ellipse_sign			= '&#8230;';
	var $image_render			= false;
	var $search_filename		= true;
	var $search_username		= true;
	var $search_caption			= true;
	var $search_keyword			= true;

	function search() {

		$this->now = now();

		if(empty($this->search_words)) {
			return NULL;
		}

		$cnt_ts_livedate = 'IF(UNIX_TIMESTAMP(pc.cnt_livedate) > 0, UNIX_TIMESTAMP(pc.cnt_livedate), pc.cnt_created)';
		$cnt_ts_killdate = 'IF(UNIX_TIMESTAMP(pc.cnt_killdate) > 0, UNIX_TIMESTAMP(pc.cnt_killdate), pc.cnt_created + 31536000)';

		$sql        = 'SELECT pc.*, ';
		$sql       .= $cnt_ts_livedate . ' AS cnt_ts_livedate, ';
		$sql       .= $cnt_ts_killdate . ' AS cnt_ts_killdate ';
		$sql       .= 'FROM '.DB_PREPEND.'phpwcms_content pc ';

		$sql_where  = 'WHERE ';
		$sql_where .= 'pc.cnt_status=1 AND ';
		$sql_where .= "pc.cnt_module='news' AND ";
		$sql_where .= $cnt_ts_livedate . ' < ' . $this->now . ' AND ';
		$sql_where .= '(' . $cnt_ts_killdate . ' > ' . $this->now . ' OR cnt_archive_status = 1) ';

		$sql_group  = '';

		// choose by category
		if(count($this->search_category)) {

			$cat_sql = array();

			// and/or/not mode
			switch($this->search_andor) {

				case 'AND': $news_andor		= ' AND ';
							$news_compare	= '=';
							break;

				case 'NOT':	$news_andor		= ' AND ';
							$news_compare	= '!=';
							break;

				default:	//OR
							$news_andor		= ' OR ';
							$news_compare	= '=';
			}

			foreach($this->search_category as $value) {

				$cat_sql[] = 'pcat.cat_name' . $news_compare . _dbEscape($value);

			}

			$sql       .= "LEFT JOIN ".DB_PREPEND."phpwcms_categories pcat ON (pcat.cat_type='news' AND pcat.cat_pid=pc.cnt_id) ";
			$sql_where .= 'AND (' . implode($news_andor, $cat_sql) . ') ';
			$sql_group  = 'GROUP BY pc.cnt_id ';
		}

		// language selection
		if(count($this->search_language)) {
			$sql_where .= "AND pc.cnt_lang IN ('". str_replace('#', "','", _dbEscape( implode('#', $this->search_language), false ) ) . "') ";
		}

		$sql .= $sql_where;
		$sql .= $sql_group;
		$sql  = trim($sql);

		$data = _dbQuery($sql);

		$search_target_url_test = strtolower(substr($this->search_target_url, 0, 4));

		if($search_target_url_test !== 'http' && $search_target_url_test !== '{sit') {
			// expected alias here or aid=123 or id=123
			if($this->search_highlight) {
				$this->search_target_url = rel_url(array('newsdetail' => '___NEWSDETAIL__', 'highlight' => '___HIGHLIGHT__'), array('searchstart', 'searchwords'), $this->search_target_url);
			} else {
				$this->search_target_url = rel_url(array('newsdetail' => '___NEWSDETAIL__'), array('highlight', 'searchstart', 'searchwords'), $this->search_target_url);
			}
			$search_replace_newsdetail = true;
		} else {
			$search_replace_newsdetail = strpos($this->search_target_url, '___NEWSDETAIL__') !== false;
			$this->search_target_url = html_specialchars($this->search_target_url);
		}

		if($this->search_highlight_words && is_array($this->search_highlight_words)) {
			$s_highlight_words = rawurlencode(implode(' ', $this->search_highlight_words));
		} else {
			$s_highlight_words = '';
		}

		foreach($data as $value) {

			$s_result = array();

			$s_text  = $value['cnt_text'] . ', ' . $value['cnt_teasertext'] . ', ' . $value['cnt_place'] . ', ';
			$s_text .= $value['cnt_subtitle'] . ', '.$value['cnt_title'];
			if($this->search_username) {
				$s_text .= ', '.$value['cnt_editor'];
			}

			$value['cnt_object'] = @unserialize($value['cnt_object']);

			if(!empty($value['cnt_object']['cnt_searchoff'])) {
				continue;
			}

			if(isset($value['cnt_object']['cnt_category'])) {
				if($this->search_keyword) {
					$s_text .= ' ' . $value['cnt_object']['cnt_category'];
				}
				if($this->search_caption) {
					$s_text .= ' ' . $value['cnt_object']['cnt_image']['caption'];
					$s_text .= ' ' . $value['cnt_object']['cnt_files']['caption'];
				}
			}
			$s_text  = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $s_text); // strip all <script> Tags
			$s_text  = str_replace( array('~', '|', ':', 'http', '//', '_blank', '&nbsp;') , ' ', $s_text );
			$s_text  = clean_search_text($s_text);

			preg_match_all('/'.$this->search_words.'/is', $s_text, $s_result );

			$s_count = count($s_result[0]); //set search_result to 0

			if($s_count && SEARCH_TYPE_AND) {
				$s_and_or = array();
				foreach($s_result[0] as $svalue) {
					$s_and_or[strtolower($svalue)] = 1;
				}
				$s_and_or = count($s_and_or);

				if($s_and_or != $this->search_word_count) {
					$s_count = 0;
				}
			}

			if($s_count) {

				$id = $this->search_result_entry;

				$this->search_results[$id]["id"]	= $value['cnt_id'];
				$this->search_results[$id]["cid"]	= 0;
				$this->search_results[$id]["rank"]	= $s_count;
				if($this->search_highlight) {
					$this->search_results[$id]["title"]		= highlightSearchResult(html($value['cnt_title']), $this->search_highlight_words);
					$this->search_results[$id]["subtitle"]	= highlightSearchResult(html($value['cnt_subtitle']), $this->search_highlight_words);
				} else {
					$this->search_results[$id]["title"]		= html($value['cnt_title']);
					$this->search_results[$id]["subtitle"]	= html($value['cnt_subtitle']);
				}
				$this->search_results[$id]["date"]	= $value['cnt_ts_livedate'];
				$this->search_results[$id]["user"]	= html($value['cnt_editor']);

				$value['detail_link']	= date('Ymd', $value['cnt_ts_livedate']) . '-' . $value['cnt_id'] . '_' ; //$crow['acontent_aid']
				$value['detail_link']  .= empty($value['cnt_alias']) ? $value['cnt_id'] : urlencode( $value['cnt_alias'] );

				if(strpos($this->search_target_url, '___NEWSDETAIL__') !== false) {
					$this->search_results[$id]['link'] = str_replace(array('___NEWSDETAIL__', '___HIGHLIGHT__'), array($value['detail_link'], $s_highlight_words), $this->search_target_url);
				} else {
					$this->search_results[$id]['link'] = $this->search_target_url.'&amp;newsdetail='.$value['detail_link'];
					if($this->search_highlight) {
						$this->search_results[$id]['link'] .= '&amp;highlight='.$s_highlight_words;
					}
				}

				$s_text = trim(trim(str_replace(', ,', ',', $s_text)), ' ,');
				$s_text = html(getCleanSubString($s_text, $this->search_wordlimit, $this->ellipse_sign, 'word'), false);

				if($this->search_highlight) {
					$s_text = highlightSearchResult($s_text, $this->search_highlight_words);
				}
				$this->search_results[$id]["text"]	= $s_text;
				$this->search_results[$id]["image"]	= false;

				if($this->image_render && !empty($value['cnt_object']['cnt_image']['id'])) {
					$value['cnt_object']['cnt_image'] = _dbGet(
						'phpwcms_file',
						'f_id AS `id`, f_hash AS `hash`, f_ext AS `ext`, f_name AS `name`',
						'f_id='._dbEscape($value['cnt_object']['cnt_image']['id']).' AND f_trash=0 AND f_aktiv=1 AND f_public=1'
					);
					if(isset($value['cnt_object']['cnt_image'][0]['id'])) {
						$this->search_results[$id]["image"] = $value['cnt_object']['cnt_image'][0];
					}
				}

				$this->search_result_entry++;
			}
		}
	}
}

function clean_search_text($string='') {

	$string = strip_tags($string);
	$string = strip_bbcode($string);
	$string = clean_replacement_tags($string);
	$string = remove_unsecure_rptags($string);
	$string = str_replace('&nbsp;', ' ', $string);
	$string = preg_replace('/\s+/', ' ', $string);
	$string = cleanUpSpecialHtmlEntities($string);

	return $string;
}
