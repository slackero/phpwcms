<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.

This script is part of PHPWCMS. The PHPWCMS web content management system is
free software; you can redistribute it and/or modify it under the terms of
the GNU General Public License as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version.

The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
A copy is found in the textfile GPL.txt and important notices to the license
from the author is found in LICENSE.txt distributed with these scripts.

This script is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// News

$news	= @unserialize($crow["acontent_form"]);

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/news.tmpl')) {

	$news['template']	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/news.tmpl');
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/news/'.$crow["acontent_template"])) {

	$news['template']	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/news/'.$crow["acontent_template"]);

} else {

	$news['template']	= '';

}


// build SQL query first
$news['sql_where']	= array();
$news['now']		= now();
$news['list_mode']	= true;

$news['cnt_ts_livedate'] = 'IF(UNIX_TIMESTAMP(pc.cnt_livedate) > 0, UNIX_TIMESTAMP(pc.cnt_livedate), pc.cnt_created)';
$news['cnt_ts_killdate'] = 'IF(UNIX_TIMESTAMP(pc.cnt_killdate) > 0, UNIX_TIMESTAMP(pc.cnt_killdate), pc.cnt_created + 31536000)';

$sql  = 'SELECT pc.*, ';
$sql .= $news['cnt_ts_livedate'] . ' AS cnt_ts_livedate, ';
$sql .= $news['cnt_ts_killdate'] . ' AS cnt_ts_killdate ';
$sql .= 'FROM '.DB_PREPEND.'phpwcms_content pc ';

$news['sql_group_by']	= '';
$news['sql_where'][]	= 'pc.cnt_status=1';
$news['sql_where'][]	= "AND pc.cnt_module='news'";

// check if detail mode is active
// and select the related news item

if(isset($_getVar['newsdetail'])) {

	$news['select_detail'] = trim( preg_replace('/^\d+\/\d+\/\d+\//', '', xss_clean($_getVar['newsdetail']) ) );
	
	if(is_numeric($news['select_detail'])) {
		$news['sql_where'][]	= "AND pc.cnt_id=" . intval($news['select_detail']);
	} else {
		$news['sql_where'][]	= "AND pc.cnt_alias='" . aporeplace($news['select_detail']) . "'";
	}
	
	$news['list_mode']	= false;

}

// archived 
switch($news['news_archive']) {

	case 0:	// include archived
			$news['sql_where'][] = 'AND ' . $news['cnt_ts_livedate'] . ' < ' . $news['now'];
			$news['sql_where'][] = 'AND (' . $news['cnt_ts_killdate'] . ' > ' . $news['now'] . ' OR cnt_archive_status = 1)';
			break;
			
	case 1:	// exclude archived
			$news['sql_where'][] = 'AND ' . $news['cnt_ts_livedate'] . ' < ' . $news['now'];
			$news['sql_where'][] = 'AND ' . $news['cnt_ts_killdate'] . ' > ' . $news['now'];
			break;
			
	case 2:	// archived only
			$news['sql_where'][] = 'AND ' . $news['cnt_ts_killdate'] . ' > ' . $news['now'];
			$news['sql_where'][] = 'AND cnt_archive_status = 1';
			break;
			
	case 3:	// all items
			$news['sql_where'][] = 'AND ' . $news['cnt_ts_livedate'] . ' < ' . $news['now'];
			break;

}


// choose by category
if(count($news['news_category'])) {
	
	$news['news_category_sql'] = array();

	// and/or/not mode
	switch($news['news_andor']) {
	
		case 'AND': $news['news_andor']		= ' AND ';
					$news['news_compare']	= '=';
					break;
					
		case 'NOT':	$news['news_andor']		= ' AND ';
					$news['news_compare']	= '!=';
					break;
					
		default:	//OR
					$news['news_andor']		= ' OR ';
					$news['news_compare']	= '=';
	}
	
	foreach($news['news_category'] as $value) {
		
		$news['news_category_sql'][] = 'pcat.cat_name' . $news['news_compare'] . "'" . aporeplace($value) . "'";
		
	}
	
	$sql .= "LEFT JOIN ".DB_PREPEND."phpwcms_categories pcat ON (pcat.cat_type='news' AND pcat.cat_pid=pc.cnt_id) ";
	$news['sql_where'][] = 'AND (' . implode($news['news_andor'], $news['news_category_sql']) . ')';
	
	$news['sql_group_by'] = 'GROUP BY pc.cnt_id ';
	
}

// language selection
if(count($news['news_lang'])) {

	$news['sql_where'][] = "AND pc.cnt_lang IN ('". str_replace('#', "','", aporeplace( implode('#', $news['news_lang']) ) ) . "')";

}

$sql .= 'WHERE ' . implode(' ', $news['sql_where']) . ' ';

// group by
$sql .= $news['sql_group_by'];

// order by - only necessary in list mode
if($news['list_mode']) {
	
	$sql .= 'ORDER BY ';
	
	// add prio sorting value
	if( !empty($news['news_prio']) ) {
		$sql .= 'pc.cnt_prio DESC, ';
	}

	switch($news['news_sort']) {
	
		case 1:		// create date, DESC
					$sql .= 'pc.cnt_created DESC';
					break;
		
		case 2:		// create date, ASC
					$sql .= 'pc.cnt_created ASC';
					break;
		
		case 3:		// change date, DESC
					$sql .= 'pc.cnt_changed DESC';
					break;
		
		case 4:		// change date, ASC
					$sql .= 'pc.cnt_changed ASC';
					break;
		
		case 6:		// live date, ASC
					$sql .= 'cnt_ts_livedate ASC';
					break;
		
		case 7:		// kill date, DESC
					$sql .= 'cnt_ts_killdate DESC';
					break;
		
		case 8:		// kill date, ASC
					$sql .= 'cnt_ts_killdate ASC';
					break;
		
		default:	// live date, DESC
					$sql .= 'cnt_ts_livedate DESC';
					break;
	
	}

	if(!empty($news['news_skip'])) {
	
		$sql .= ' LIMIT '.intval($news['news_skip']).', ';
		$sql .= !empty($news['news_limit']) ? intval($news['news_limit']) : 9999;

	} elseif(!empty($news['news_limit'])) {

		$sql .= ' LIMIT ';
		if(!empty($news['news_skip'])) {
			$sql .= intval($news['news_skip']).', ';
		}
		$sql .= intval($news['news_limit']);

	}
}

// get db query result
$news['result'] = _dbQuery($sql);

// now render
if($news['template']) {

	$news['entries']			= array();

	// check if news is in list mode
	if($news['list_mode']) {
		$news['tmpl_news']			= get_tmpl_section('NEWS_LIST', $news['template']);
		$news['tmpl_entry']			= get_tmpl_section('NEWS_LIST_ENTRY', $news['template']);
		$news['tmpl_entry_space']	= get_tmpl_section('NEWS_LIST_ENTRY_SPACE', $news['template']);
		$news['tmpl_row_space']		= get_tmpl_section('NEWS_LIST_ROW_SPACE', $news['template']);
	
	// or not in list mode
	} else {
	
		$news['tmpl_news']			= '[NEWS_ENTRIES]{NEWS_ENTRIES}[/NEWS_ENTRIES]';
		$news['tmpl_entry']			= get_tmpl_section('NEWS_DETAIL', $news['template']);
		$news['tmpl_entry_space']	= '';
		$news['tmpl_row_space']		= '';
	
	}

	// get template based config and merge with defaults
	$news['config']	= array_merge(	array(	'news_per_row'			=> 1,
											'news_teaser_text'		=> 'p',
											'files_template_list'	=> 'default',
											'files_template_detail'	=> 'default',
											'files_direct_download'	=> 0 
										  ),
									parse_ini_str( get_tmpl_section('NEWS_SETTINGS', $news['template']), false )
								  );

	$news['config']['news_per_row']		= abs(intval($news['config']['news_per_row']));
	$news['config']['news_teaser_text']	= strtolower(trim($news['config']['news_teaser_text'])) == 'br' ? 'br_htmlencode' : 'plaintext_htmlencode';


	// start parsing news entries	
	$news['row_count']		= 1;
	$news['total_count']	= 1;
	$news['entry_count']	= count($news['result']);
	
	$news['base_href']		= 'index.php' . returnGlobalGET_QueryString('htmlentities', array(), array('newsdetail'));
	
	foreach($news['result'] as $key => $value) {
	
		$value['cnt_object']	= @unserialize($value['cnt_object']);
	
		$news['entries'][$key]	= $news['tmpl_entry'];
		
		$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'NEWS_TITLE', html_specialchars($value['cnt_title']));
		$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'NEWS_TOPIC', html_specialchars($value['cnt_name']));
		$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'NEWS_SUBTITLE', html_specialchars($value['cnt_subtitle']));
		$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'NEWS_TEASER', $news['config']['news_teaser_text']($value['cnt_teasertext']));
		$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'NEWS_TEXT', $value['cnt_text']);
		$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'AUTHOR', html_specialchars($value['cnt_editor']));
		$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'PLACE', html_specialchars($value['cnt_place']));
		$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'PRIO', empty($value['cnt_prio']) ? '' : $value['cnt_prio'] );
		
		// news detail link (read)
		if($news['list_mode']) {
		
			if(empty($value['cnt_object']['cnt_readmore'])) {
				$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'NEWS_DETAIL_LINK', '');
			} else {
				$value['detail_link']	= urlencode( date('Y/m/d/', $value['cnt_ts_livedate']) . (empty($value['cnt_alias']) ? $value['cnt_id'] : $value['cnt_alias']) );
				$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'NEWS_DETAIL_LINK', $news['base_href'] . '&amp;newsdetail=' . $value['detail_link']);
			}
			
		// news list link (back)
		} else {
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'NEWS_LIST_LINK', $news['base_href']);
		}
		
		// Image
		if(empty($value['cnt_object']['cnt_image']['id'])) {
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'IMAGE', '');
			$news['entries'][$key]	= str_replace('{IMAGE_ID}', '', $news['entries'][$key]);
		} else {
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'IMAGE', html_specialchars($value['cnt_object']['cnt_image']['name']));
			$news['entries'][$key]	= str_replace('{IMAGE_ID}', $value['cnt_object']['cnt_image']['id'], $news['entries'][$key]);
		}
		
		// Zoom Image
		if(empty($value['cnt_object']['cnt_image']['zoom'])) {
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'ZOOM', '' );
		} else {
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'ZOOM', 'zoom' );
		}
		// Lightbox
		if(empty($value['cnt_object']['cnt_image']['lightbox'])) {
			$news['entries'][$key]	= str_replace('{LIGHTBOX}', '', $news['entries'][$key]);
		} else {
			initializeLightbox();
			$news['entries'][$key]	= str_replace('{LIGHTBOX}', ' rel="lightbox"', $news['entries'][$key]);
		}
		// Caption
		if(empty($value['cnt_object']['cnt_image']['caption'])) {
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'CAPTION', '' );
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'LIGHTBOX_CAPTION', '' );
		} else {
			$value['cnt_caption']	= getImageCaption($value['cnt_object']['cnt_image']['caption'], '');
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'CAPTION', html_specialchars($value['cnt_caption']['caption_text']) );
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'LIGHTBOX_CAPTION', parseLightboxCaption($value['cnt_caption']['caption_text']) );
		}
		
		// Image URL
		if(empty($value['cnt_object']['cnt_image']['link'])) {
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'IMAGE_URL', '');
			$news['entries'][$key]	= str_replace('{IMAGE_URL_TARGET}', '', $news['entries'][$key]);
		} else {
			$value['image_url']		= get_redirect_link($value['cnt_object']['cnt_image']['link'], ' ', '');
			$news['entries'][$key]	= str_replace('{IMAGE_URL_TARGET}', $value['image_url']['target'], $news['entries'][$key]);
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'IMAGE_URL', html_specialchars($value['image_url']['link']) );
		}
		// Check for Zoom
		$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'ZOOM', empty($value['cnt_object']['cnt_image']['zoom']) ? '' : 'zoom' );
		
		// news entry URL
		$value['news_url']		= $value['cnt_object']['cnt_link'] == '' ? array('link'=>'', 'target'=>'') : get_redirect_link($value['cnt_object']['cnt_link'], ' ', '');
		$news['entries'][$key]	= str_replace('{URL_TARGET}', $value['news_url']['target'], $news['entries'][$key]);
		if(is_numeric($value['news_url']['link']) && intval($value['news_url']['link'])) {
			$value['news_url']['link'] = 'index.php?aid='.intval($value['news_url']['link']);
		}
		$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'URL', html_specialchars($value['news_url']['link']) );
		$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'URL_TEXT', html_specialchars($value['cnt_object']['cnt_linktext']) );
		
		// Dates
		$news['entries'][$key]	= render_cnt_date($news['entries'][$key], $value['cnt_changed'], $value['cnt_ts_livedate'], $value['cnt_ts_killdate']);
		
		$news['files_result']	= '';
		
		// Files
		if(isset($value['cnt_object']['cnt_files']['id']) && is_array($value['cnt_object']['cnt_files']['id']) && count($value['cnt_object']['cnt_files']['id'])) {
		
			$IS_NEWS_CP = true;
		
			$value['files_direct_download'] = intval($news['config']['files_direct_download']) ? 1 : 0;
			
			// set correct template for files based on list or detail mode
			if($news['list_mode']) {
				$value['files_template']	= $news['config']['files_template_list'] == 'default' ? '' : $news['config']['files_template_list'];
			} else {
				$value['files_template']	= $news['config']['files_template_detail'] == 'default' ? '' : $news['config']['files_template_detail'];
			}
			
			// include content part files renderer
			include(PHPWCMS_ROOT.'/include/inc_front/content/cnt7.article.inc.php');
			
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'FILES', $news['files_result'] );
			
			unset($IS_NEWS_CP);
		
		} else {
			$news['entries'][$key]	= render_cnt_template($news['entries'][$key], 'FILES', '' );
		}
		
		
		$news['entries'][$key]	= $news['entries'][$key];
		
		// row and entry spacer
		if($news['list_mode']) {

			if( $news['row_count'] == $news['config']['news_per_row'] || $news['config']['news_per_row'] == 0 ) {
	
				if($news['total_count'] < $news['entry_count']) {
					$news['entries']['row'.$key] = $news['tmpl_row_space'];
				}
				$news['row_count']	= 1;
			
			} else {
	
				if($news['total_count'] < $news['entry_count']) {
					$news['entries']['entry'.$key] = $news['tmpl_entry_space'];
				}
				$news['row_count']++;
			
			}
			
			$news['total_count']++;
		}
	
	}
	
	$news['tmpl_news']	= render_cnt_template($news['tmpl_news'], 'NEWS_ENTRIES', implode('', $news['entries']) );
	$news['tmpl_news']	= render_cnt_template($news['tmpl_news'], 'TITLE', html_specialchars($crow['acontent_title']));
	$news['tmpl_news']	= render_cnt_template($news['tmpl_news'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
	
	$CNT_TMP .= $news['tmpl_news'];

}


?>