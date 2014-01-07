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

// Module/Plug-in Feed to Article import default settings
// Init used to import feed to articles

if(!empty($_getVar['feedimport'])) {
	
	// catch the Feed to be imported to article
	$feedimport_source = $_getVar['feedimport'];
	$feedimport_where  = "cnt_status=1 AND cnt_module='feedimport' AND ";
	
	if(strlen($feedimport_source) > 32 && substr($feedimport_source, 32) == '-now') {
		$feedimport_source = substr($feedimport_source, 0, 32);
	} else {
		$feedimport_where .= 'IF(cnt_prio=0, 1, '.now().'-cnt_sort>cnt_prio) AND ';
	}

	$feedimport_where .= 'MD5(CONCAT(cnt_id,cnt_text))='._dbEscape($feedimport_source);
	
	
	unset($_getVar['feedimport']);
	
	$feedimport_result = _dbGet('phpwcms_content', 'cnt_id,cnt_name,cnt_text,cnt_object', $feedimport_where);
	
	if(isset($feedimport_result[0]['cnt_id'])) {
		
		$feedimport_result = $feedimport_result[0];
		
		$feedimport_result['cnt_object'] = @unserialize($feedimport_result['cnt_object']);
		
	}
	
	if(isset($feedimport_result['cnt_object']['structure_level_id'])) {
		
		// retrieve Feed now
		// Load SimplePie
		require_once(PHPWCMS_ROOT.'/include/inc_ext/simplepie.inc.php');

		$rss_obj = new SimplePie();
	
		// Feed URL
		$rss_obj->set_feed_url( $feedimport_result['cnt_text'] );
	
		// Output Encoding Charset
		$rss_obj->set_output_encoding( PHPWCMS_CHARSET );
	
		// Disable Feed cache
		$rss_obj->enable_cache( false );
	
		// Remove surrounding DIV
		$rss_obj->remove_div( true );

	
		// Init Feed
		$rss_obj->init();
	
		if( $rss_obj->data ) {
			
			$feedimport_result['status'] = array(
				'Feed Importer Status - ' . date('Y-m-d, H:i:s') . LF . '===========================================',
				$feedimport_result['cnt_name'] . LF . $feedimport_result['cnt_text'] . LF
			);
		
			// need some additional functions
			include_once(PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php');
			
			foreach($rss_obj->get_items() as $rssvalue) {
				
				$article_unique_hash	= md5( $feedimport_result['cnt_text'] . $rssvalue->get_title() . $rssvalue->get_date('U') );
				
				// check against crossreference table
				$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_crossreference c ';
				$sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_article a ';
				$sql .= 'ON c.cref_rid=a.article_id ';
				$sql .= "WHERE c.cref_type='feed_to_article_import' AND c.cref_str="._dbEscape('feedimport_'.$article_unique_hash).' AND ';
				$sql .= 'a.article_deleted=0 LIMIT 1';
				
				if(_dbQuery($sql, 'COUNT') > 0) {
					continue;
				}
				
				
				$article_title			= html_entity_decode($rssvalue->get_title(), ENT_COMPAT, PHPWCMS_CHARSET);
				$article_alias			= proof_alias(0, $article_title, 'ARTICLE');
				$article_begin			= $rssvalue->get_date('U');
				$article_end			= now()+(3600*24*365*10);
				$article_summary		= $rssvalue->get_description();
				$article_content		= $rssvalue->get_content();
				$article_description	= preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($article_summary), ENT_COMPAT, PHPWCMS_CHARSET));
				list($article_description) = explode("\n", wordwrap($article_description, 250), 2);
				list($article_description) = explode("-- ", $article_description, 2);
				$article_description	= preg_replace('/(.*?\.).+?$/', '$1', $article_description);
				$article_author			= $rssvalue->get_author();
				if($article_author) {
					$article_author		= html_entity_decode($article_author->get_name());
				} else {
					$article_author		= $feedimport_result['cnt_object']['author_name'];
				}
				
				if($feedimport_result['cnt_object']['source_link_add'] && $rssvalue->get_permalink()) {
					$article_content .= '<p><a href="'.$rssvalue->get_permalink().'" class="feed-permalink">' . (empty($feedimport_result['cnt_object']['source_link_text']) ? '@@Source@@' : $feedimport_result['cnt_object']['source_link_text']) . '</a></p>';
				}
				
				// define article data
				$data = array(
					
					'article_created'		=> now(),
					"article_cid"			=> $feedimport_result['cnt_object']['structure_level_id'],
					"article_uid"			=> $feedimport_result['cnt_object']['author_id'],
					"article_title"			=> $article_title,
					"article_alias"			=> $article_alias,
					"article_keyword"		=> '',
					"article_public"		=> 1,
					"article_aktiv"			=> $feedimport_result['cnt_object']['activate_after_import'],
					"article_begin"			=> date('Y-m-d H:i:s', $article_begin),
					"article_end"			=> date('Y-m-d 23:59:59', $article_end),
					"article_subtitle"		=> '',
					"article_summary"		=> $article_content,
					"article_redirect"		=> '',
					"article_sort"			=> $article_begin,
					"article_username"		=> $article_author,
					"article_notitle"		=> 0,
					"article_hidesummary"	=> 0,
					"article_image"			=> array(
						'tmpllist'			=> $feedimport_result['cnt_object']['article_template_list'],
						'tmplfull'			=> $feedimport_result['cnt_object']['article_template_detail'],
						'name'				=> '',
						'id'				=> '',
						'caption'			=> '',
						"hash"				=> '',
						'list_usesummary'	=> 0,
						'list_name'			=> '',
						'list_id'			=> 0,
						'list_width'		=> '',
						'list_height'		=> '',
						'list_zoom'			=> 0,
						'list_caption'		=> '',
						"list_hash"			=> '',
						'zoom'				=> 0
					),
					"article_cache"			=> '',
					"article_nosearch"		=> 0,
					"article_nositemap"		=> 0,
					"article_aliasid"		=> 0,
					"article_headerdata"	=> 0,
					"article_morelink"		=> 1,
					"article_pagetitle"		=> '',
					"article_paginate"		=> 0,
					"article_priorize"		=> 0,
					"article_norss"			=> 0,
					"article_archive_status"=> 1,
					"article_menutitle"		=> '',
					'article_description'	=> $article_description,
					'article_serialized'	=> ''

				);
	
				
				$data['article_image'] = serialize($data['article_image']);
				
				$result = _dbInsert('phpwcms_article', $data);
				
				if(isset($result['INSERT_ID'])) {
					
					$feedimport_result['status'][] = date('Y-m-d, H:i:s', $article_begin) . LF . $article_title . LF . $rssvalue->get_permalink() . LF . PHPWCMS_URL . 'phpwcms.php?do=articles&p=2&s=1&id='.$result['INSERT_ID'];
					
					$data = array(
						'cref_type'	=> 'feed_to_article_import',
						'cref_rid'	=> $result['INSERT_ID'],
						'cref_str'	=> 'feedimport_'.$article_unique_hash
					);
					
					_dbInsert('phpwcms_crossreference', $data);
				
				}

			}
			
			
			// check if status email should be sent
			if(!empty($feedimport_result['cnt_object']['import_status_email']) && is_valid_email($feedimport_result['cnt_object']['import_status_email'])) {
				
				$feedimport_result['status'] = implode(LF.LF, $feedimport_result['status']);
				
				sendEmail(array(
					'recipient'	=> $feedimport_result['cnt_object']['import_status_email'],
					'subject'	=> 'Import Status: ' . $feedimport_result['cnt_name'],
					'isHTML'	=> 0,
					'text'		=> $feedimport_result['status'],
					'fromName'	=> 'Feed Importer'
				));
				
			}
			
		}
		
			
	}
	
	// we quit here
	exit();
	
}


?>