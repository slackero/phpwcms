<?php

/**
 * phpwcms Calendar frontend render class
 */
class phpwcmsCalendar {

	var $mode		= 'simple';
	var $dates		= array();
	
	/**
	 * Initialize class
	 */
	function phpwcmsCalendar() {
	
		// current
		$this->current_date	= getdate();
		
		// set today 00:00:00 as start date
		$this->date_start	= mktime(0, 0, 0, $this->current_date['mon'], $this->current_date['mday'], $this->current_date['year']);
		
		// by default date_start + 1 year
		$this->date_end		= mktime(0, 0, 0, $this->current_date['mon'], $this->current_date['mday'], $this->current_date['year'] + 1) - 1;
		
		$this->dbReset();
	}
	
	/**
	 * Reset db base vars
	 */
	function dbReset() {
		$this->where	= '';
		$this->select	= '*';
		$this->join_on	= '';
		$this->group_by	= '';
		$this->order_by	= 'calendar_start ASC';
		$this->limit	= 0;
	}
	
	function defaultTemplate() {
		$this->template = '';
		$this->href		= '';
	}

	/**
	 * search string for calendar tag and parse
	 */
	function parse(& $string) {
		
		if( preg_match_all('/\{CALENDAR:(.*?)\}/s', $string, $matches) ) {
		
			if( isset($matches[1]) ) {
			
				foreach($matches[1] as $key => $value) {
			
					$this->parse_match($value);
					$result = $this->render();
					
					// replace calendar by result
					$string = str_replace($matches[0][$key], $result, $string);
			
				}
				
				$string = html_parser($string);
			
			}
		}
	
	}
	
	function render() {

		$items		= array();
		
		foreach($this->dates as $key => $date) {
			
			$url	= '';
			$target	= '';
			$href	= $this->href ? $this->href . '&amp;show_date='.date('Y-m-d', $date['calendar_start_date']).'_'.$date['calendar_id'] : '';
	
			if(!empty($date['calendar_refid'])) {
						
				$date['calendar_refid']			= get_redirect_link($date['calendar_refid'], ' ', '');
				$date['calendar_refid']['link']	= trim($date['calendar_refid']['link']);
				$date['calendar_refid']['link']	= trim($date['calendar_refid']['link'], '#');
				
				$target							= $date['calendar_refid']['target'];
				
				if(is_intval($date['calendar_refid']['link'])) {
					$url = rel_url(array(), array(), 'aid='.$date['calendar_refid']['link']); //'index.php?aid='.$date['calendar_refid']['link'];
					
				} elseif(strpos($date['calendar_refid']['link'], '://') || strpos($date['calendar_refid']['link'], '?') || strpos($date['calendar_refid']['link'], '.')) {
					$url = $date['calendar_refid']['link'];
					
				} elseif(!empty($date['calendar_refid']['link'])) {
					$url = rel_url(array(), array(), $date['calendar_refid']['link']);
				
				}
			}
			
			$items[$key] = $this->template;
			$items[$key] = render_cnt_template($items[$key], 'HREF', $href);
			$items[$key] = render_cnt_template($items[$key], 'URL', $url);
			$items[$key] = render_cnt_template($items[$key], 'TARGET', $target);
			$items[$key] = render_cnt_template($items[$key], 'TITLE', html_specialchars($date['calendar_title']));
			$items[$key] = render_cnt_template($items[$key], 'TEXT', plaintext_htmlencode($date['calendar_teaser']));
			$items[$key] = render_cnt_template($items[$key], 'PLACE', html_specialchars($date['calendar_where']));
			$items[$key] = render_cnt_date($items[$key], $date['calendar_start_date'], $date['calendar_start_date'], $date['calendar_end_date']);
			
		}
		
		return implode(LF, $items);
		
	}
	
	/**
	 * Parse matched replacement tag
	 */
	function parse_match($match='') {
	
		$default		= array();
		$match			= trim($match);
		
		// set query defaults
		$this->dbReset();
		$this->defaultTemplate();

		if($match !== '' && strpos($match, '=') !== FALSE ) {
		
			// oh yes fix, in case LF was converted to <br /> by phpwcms
			$match = str_replace('<br />', LF, $match);
			
			// result is a normal array
			$match = parse_ini_str($match, false);
			
			$default['items']		= isset($match['items']) ? intval($match['items']) : 0;
			$default['template']	= empty($match['template']) ? '' : trim($match['template']);
			$default['lang']		= empty($match['lang']) ? '' : trim($match['lang']);
			$default['tag']			= empty($match['tag']) ? '' : trim($match['tag']);
			$default['tagmode']		= empty($match['tagmode']) ? 'OR' : ( trim($match['tagmode']) == 'AND' ? 'AND' : 'OR' );
			$default['href']		= empty($match['href']) ? '' : trim($match['href']);
		
		} else {
			
			// base format
			// 2,main_page.tmpl,de en, href, tag1, tag2 tag2, tag3 : date_start, date_end
			// [item count,[template[,language(en de - separated by space)[, href, tags, tag, tag, tag]]]]
			$match = explode(',', $match, 5);
			
			$default['items']		= intval($match[0]);
			$default['lang']		= empty($match[1]) ? '' : $match[1];
			$default['template']	= empty($match[2]) ? '' : trim($match[2]) ;
			$default['href']		= empty($match[3]) ? '' : trim($match[3]);
			$default['tagmode']		= 'OR';
			
			if(empty($match[4])) {
				$default['tag']		= '';
			} else {
				// check for start/end date
				$match[4]			= explode(':', $match[4], 2);
				if(isset($match[4][1])) {
					$match[4][1] = explode(',', $match[4][1], 2);
					if(!empty($match[4][1][0])) {
						$match['date_start'] = $match[4][1][0];
					} else {
						$match['date_start'] = 'TODAY';
					}
					if(!empty($match[4][1][1])) {
						$match['date_end'] = $match[4][1][1];
					} else {
						$match['date_end'] = 364 * 24 * 60 * 60; // + 364 days
					}
				}
			}			
		
		}
		
		// set custom defined start/end date
		if(!empty($match['date_start'])) {
			$match['date_start'] = trim($match['date_start']);
			if(strtoupper($match['date_start']) == 'TODAY') {
				$this->date_start =  mktime(0, 0, 0, $this->current_date['mon'], $this->current_date['mday'], $this->current_date['year']);
			} else {
				$match['date_start'] = phpwcms_strtotime($match['date_start']);
				if($match['date_start']) {
					$this->date_start = $match['date_start'];
				}
			}
		}
		if(!empty($match['date_end'])) {
			$match['date_end'] = trim($match['date_end']);
			if(is_intval($match['date_end'])) {
				$this->date_end = $this->date_start + ($match['date_end'] * 24 * 60 * 60);
			} elseif(strtoupper($match['date_end']) == 'TODAY') {
				$this->date_end =  mktime(23, 59, 59, $this->current_date['mon'], $this->current_date['mday'], $this->current_date['year']);
			} else {
				$match['date_end'] = phpwcms_strtotime($match['date_end']);
				if($match['date_end']) {
					$this->date_end = $match['date_end'];
				}
			}
		}
		if($this->date_end <= $this->date_start) {
			$this->date_end = mktime(0, 0, 0, $this->current_date['mon'], $this->current_date['mday'], $this->current_date['year'] + 1) - 1;
		}
		
		//dumpVar($this->date_start . ' - ' . $this->date_end . ' / ' . date('d.m.Y, H:i:s', $this->date_start) . ' - ' . date('d.m.Y, H:i:s', $this->date_end));
		
		$this->limit = $default['items'];
		$this->href  = $default['href'];
		
		if($default['template'] !== '') {
			$default['template'] = preg_replace('/[\/\\:]/', '', $default['template']);
			if(is_file(PHPWCMS_TEMPLATE.'calendar/'.$default['template'])) {
				$this->template = file_get_contents(PHPWCMS_TEMPLATE.'calendar/'.$default['template']);
				$this->template = str_replace('{STARTDATE', '{LIVEDATE', $this->template);
				$this->template = str_replace('{ENDDATE', '{KILLDATE', $this->template);
			} else {
				$default['template'] = '';
			}
		}

		$where = array();
		
		if($default['lang'] !== '') {

			$default['lang']	= str_replace(',', ' ', preg_replace('/[^a-z\-]/', '', strtolower($default['lang'])));
			$default['lang']	= array_intersect( convertStringToArray( $default['lang'], ' '), $GLOBALS['phpwcms']['allowed_lang']);

			if(count($default['lang'])) {
				$where[]			= "calendar_lang IN ('" . implode("','", $default['lang']) . "')";
			}
		}
		
		if($default['tag'] !== '') {

			$default['tag'] = convertStringToArray( strtolower( $default['tag'] ), ',');

			if(count($default['tag'])) {
				
				$tag_where = array();
	
				foreach($default['tag'] as $tag) {
					
					$tag_where[]		= "cat_name='".aporeplace($tag)."'";
				
				}
				
				if(count($tag_where)) {
				
					$where[]		= '(' . implode(' '.$default['tagmode'] . ' ', $tag_where) . ')';
					
					$this->join_on	= 'LEFT JOIN '.DB_PREPEND.'phpwcms_categories ON cat_pid=calendar_id';
					$this->group_by	= 'calendar_id';
				}
			
			};
		}
	
		$this->where = implode(' AND ', $where);
		
		$this->getDate();
		
		return $default;
	
	}
	

	function getDate() {
	
		// 1 daily
		// 2 Every weekday (Mon-Fri)
		// 3 Every Mon., Wed. and Fri.
		// 4 Every Tues. and Thurs.
		// 5 Weekly
		// 6 Monthly
		// 7 yearly
	
		if(is_string($this->order_by) && trim($this->order_by) != '') {
			$this->order_by = '  ORDER BY '.$this->order_by;
		} else {
			$this->order_by = '';
		}
		if(is_int($this->limit) && $this->limit > 0) {
			$this->limit = ' LIMIT '.$this->limit;
		} else {
			$this->limit = '';
		}
		if(is_string($this->group_by) && trim($this->group_by) != '') {
			$this->group_by = '  GROUP BY '.$this->group_by;
		} else {
			$this->group_by = '';
		}
		
	
		$sql  = 'SELECT '. $this->select .', ';
		$sql .= "UNIX_TIMESTAMP(calendar_start) AS calendar_start_date, ";
		$sql .= "UNIX_TIMESTAMP(calendar_end) AS calendar_end_date ";
		$sql .= ' FROM '.DB_PREPEND.'phpwcms_calendar pc ';
		$sql .= $this->join_on;
		$sql .= ' WHERE ';
		$sql .= 'calendar_status = 1 AND ';
		$sql .= "calendar_start >= '".aporeplace( date('Y-m-d H:i:s', $this->date_start) )."' AND ";
		$sql .= "calendar_start <= '".aporeplace( date('Y-m-d H:i:s', $this->date_end) )."'";
		if(!empty($this->where)) {
			$sql .= ' AND ' . $this->where;
		}
		$sql .= $this->group_by;
		$sql .= $this->order_by;
		$sql .= $this->limit;
		
		$this->dates = _dbQuery($sql);
		if( !$this->dates ) {
			$this->dates = array();
		}
	}

}


?>