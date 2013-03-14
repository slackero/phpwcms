<?php

/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2013, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
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
		
			if(empty($this->search_target_url)) {
				$this->search_target_url = rel_url(array(), array('newsdetail'));
			} else {	
				$this->search_target_url = html_specialchars($this->search_target_url);
			}
			
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
				
				$cat_sql[] = 'pcat.cat_name' . $news_compare . "'" . aporeplace($value) . "'";
				
			}
			
			$sql       .= "LEFT JOIN ".DB_PREPEND."phpwcms_categories pcat ON (pcat.cat_type='news' AND pcat.cat_pid=pc.cnt_id) ";
			
			$sql_where .= 'AND (' . implode($news_andor, $cat_sql) . ') ';
			
			$sql_group  = 'GROUP BY pc.cnt_id ';
			
		}
		
		// language selection
		if(count($this->search_language)) {
		
			$sql_where .= "AND pc.cnt_lang IN ('". str_replace('#', "','", aporeplace( implode('#', $this->search_language) ) ) . "') ";
		
		}
		
		$sql .= $sql_where;
		$sql .= $sql_group;
		
		$sql  = trim($sql);

		$data = _dbQuery($sql);
		
		
		foreach($data as $value) {
		
			$s_result = array();
						
			$s_text  = $value['cnt_text'] . ', ' . $value['cnt_teasertext'] . ', ' . $value['cnt_place'] . ', ';
			$s_text .= $value['cnt_subtitle'] . ', ' . $value['cnt_editor'] . ', ' . $value['cnt_title'];
			
			$value['cnt_object'] = @unserialize($value['cnt_object']);
			
			if(isset($value['cnt_object']['cnt_category'])) {
			
				$s_text .= ' ' . $value['cnt_object']['cnt_category'];
				$s_text .= ' ' . $value['cnt_object']['cnt_image']['caption'];
				$s_text .= ' ' . $value['cnt_object']['cnt_files']['caption'];
			
			}
			$s_text  = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $s_text); // strip all <script> Tags
			$s_text  = str_replace( array('~', '|', ':', 'http', '//', '_blank', '&nbsp;') , ' ', $s_text );
			$s_text  = clean_replacement_tags($s_text, '');
			$s_text  = remove_unsecure_rptags($s_text);
			$s_text  = cleanUpSpecialHtmlEntities($s_text);
			
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
				
				$s_title  = html_specialchars($value['cnt_title']);

				$this->search_results[$id]["id"]	= $value['cnt_id'];
				$this->search_results[$id]["cid"]	= 0;
				$this->search_results[$id]["rank"]	= $s_count;
				$this->search_results[$id]["title"]	= $this->search_highlight ? highlightSearchResult($s_title, $this->search_highlight_words) : $s_title;
				$this->search_results[$id]["date"]	= $value['cnt_ts_livedate'];
				$this->search_results[$id]["user"]	= html_specialchars($value['cnt_editor']);
				
				
				$value['detail_link']	= date('Ymd', $value['cnt_ts_livedate']) . '-' . $value['cnt_id'] . '_' ; //$crow['acontent_aid']
				$value['detail_link']  .= empty($value['cnt_alias']) ? $value['cnt_id'] : urlencode( $value['cnt_alias'] );
				
				$this->search_results[$id]['query']	= $this->search_target_url.'&amp;newsdetail='.$value['detail_link'];
				
				$s_text   = trim(trim(str_replace(', ,', ',', $s_text)), ' ,');
				$s_text   = getCleanSubString($s_text, $this->search_wordlimit, $this->ellipse_sign, 'word');
				$s_text   = html_specialchars($s_text);
				
				if($this->search_highlight) {
					$s_text = highlightSearchResult($s_text, $this->search_highlight_words);
				}
				$this->search_results[$id]["text"]	= $s_text;
				
				$this->search_result_entry++;
			
			}	
		}
	}
}


function clean_search_text($string='') {

	$string = clean_replacement_tags($string);
	$string = remove_unsecure_rptags($string);
	$string = str_replace('&nbsp;', ' ', $string);
	$string = preg_replace('/\s+/i', ' ', $string);
	$string = cleanUpSpecialHtmlEntities($string);

	return $string;
}


?>