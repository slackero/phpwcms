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

			if(!empty($feedimport_result['cnt_object']['image_url_replace'])) {
				$feedimport_result['cnt_object']['image_url_replace'] = explode('>', $feedimport_result['cnt_object']['image_url_replace']);
				$feedimport_result['cnt_object']['image_url_replace'][0] = trim(trim($feedimport_result['cnt_object']['image_url_replace'][0]), '"');
				if(isset($feedimport_result['cnt_object']['image_url_replace'][1])) {
					$feedimport_result['cnt_object']['image_url_replace'][1] = trim(trim($feedimport_result['cnt_object']['image_url_replace'][1]), '"');
				}
			} else {
				$feedimport_result['cnt_object']['image_url_replace'] = array(
                    0   => '', // Search
                    1   => '' // Replace
				);
			}

			if(empty($feedimport_result['cnt_object']['image_folder_id'])) {
				$feedimport_result['cnt_object']['image_folder_id'] = 0;
			}

			// need some additional functions
			include_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';

			// set import sort counter
			$article_sort_counter = _dbGet('phpwcms_article', 'article_sort', 'article_cid='._dbEscape($feedimport_result['cnt_object']['structure_level_id']), '', 'article_sort DESC', 1);
			if(isset($article_sort_counter[0])) {
				$article_sort_counter = $article_sort_counter[0]['article_sort'] + 10;
			} else {
				$article_sort_counter = 100;
			}

			foreach($rss_obj->get_items() as $rssvalue) {

                $article_unique_hash    = md5( $feedimport_result['cnt_text'] . $rssvalue->get_title() . $rssvalue->get_date('U') );

				// check against crossreference table
				$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_crossreference c ';
				$sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_article a ';
				$sql .= 'ON c.cref_rid=a.article_id ';
				$sql .= "WHERE c.cref_type='feed_to_article_import' AND c.cref_str="._dbEscape('feedimport_'.$article_unique_hash).' AND ';
				$sql .= 'a.article_deleted=0 LIMIT 1';

				if(_dbQuery($sql, 'COUNT') > 0) {
					continue;
				}

                $article_title          = html_entity_decode($rssvalue->get_title(), ENT_COMPAT, PHPWCMS_CHARSET);
                $article_alias          = proof_alias(0, $article_title, 'ARTICLE');
                $article_begin          = $rssvalue->get_date('U');
                $article_end            = now()+(3600*24*365*10);
                $article_summary        = $rssvalue->get_description();
                $article_content        = $rssvalue->get_content();
                $article_description    = preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($article_summary), ENT_COMPAT, PHPWCMS_CHARSET));
				list($article_description) = explode("\n", wordwrap($article_description, 250), 2);
				list($article_description) = explode("-- ", $article_description, 2);
                $article_description    = preg_replace('/(.*?\.).+?$/', '$1', $article_description);
                $article_author         = $rssvalue->get_author();
				if($article_author) {
                    $article_author     = html_entity_decode($article_author->get_name(), ENT_COMPAT, PHPWCMS_CHARSET);
				} else {
                    $article_author     = $feedimport_result['cnt_object']['author_name'];
				}

				if($article_summary && $article_content && $article_summary == $article_content) {
					$article_content = '';
				}
				if($article_content && !$article_summary) {
					$article_summary = $article_content;
					$article_content = '';
				}
				if($article_summary && $article_summary == strip_tags($article_summary)) {
					$article_summary = plaintext_htmlencode($article_summary);
				} elseif($article_summary && strpos($article_summary, '<') !== false) {
					$article_summary = preg_replace(array(
						'/<.+?[^>]*>\s*<\/.+?>/',
						'/<.+?[^>]*><\/.+?>/',
						'/<.+?[^>]*>'.preg_quote($rssvalue->get_title(), '/').'<\/.+?>/',
						'/<.+?[^>]*>'.preg_quote(html_entities($article_title), '/').'<\/.+?>/'
					), '', $article_summary);
				}
				if($article_content && strpos($article_content, '<') !== false) {
					$article_content = preg_replace(array(
						'/<.+?[^>]*>\s*<\/.+?>/',
						'/<.+?[^>]*><\/.+?>/',
						'/<.+?[^>]*>'.preg_quote($rssvalue->get_title(), '/').'<\/.+?>/',
						'/<.+?[^>]*>'.preg_quote(html_entities($article_title), '/').'<\/.+?>/'
					), '', $article_content);
				}

				$article_categories = $rssvalue->get_categories();
				if(is_array($article_categories) && count($article_categories)) {
					foreach($article_categories as $key => $cat) {
						if($cat->term) {
							$article_categories[$key] = $cat->term;
						} else {
							unset($article_categories[$key]);
						}
					}
					$article_categories = implode(', ', $article_categories);
				}

				$feedimport_result['image'] = array(
                    'tmpllist' => $feedimport_result['cnt_object']['article_template_list'],
                    'tmplfull' => $feedimport_result['cnt_object']['article_template_detail'],
                    'name' => '',
                    'id' => '',
                    'caption' => '',
                    'caption_suppress' => 0,
                    'hash' => '',
                    'width' => '',
                    'ext' => '',
                    'list_usesummary' => 0,
                    'list_name' => '',
                    'list_id' => 0,
                    'list_width' => '',
                    'list_height' => '',
                    'list_zoom' => 0,
                    'list_caption' => '',
                    'list_caption_suppress' => 0,
                    "list_hash" => '',
                    'zoom' => 0
				);

				// Thumbnail
				$article_thumbnail = '';
				if($article_enclosure = $rssvalue->get_enclosure()) {

					$article_thumbnail = $article_enclosure->get_thumbnail();
					if(!$article_thumbnail && $article_enclosure->get_link()) {
						$article_thumbnail = $article_enclosure->get_link();
						if($article_thumbnail && ($article_thumbnail_ext = which_ext($article_thumbnail)) && in_array($article_thumbnail_ext, array('jpg', 'jpeg', 'png', 'gif'))) {

							if($feedimport_result['cnt_object']['image_url_replace'][0] !== '') {
								$article_thumbnail = str_replace($feedimport_result['cnt_object']['image_url_replace'][0], $feedimport_result['cnt_object']['image_url_replace'][1], $article_thumbnail);
							}

							$article_thumbnail_name = basename($article_thumbnail);
							$article_thumbnail_hash = md5($article_thumbnail_name . microtime());
							$article_thumbnail_store = PHPWCMS_STORAGE . $article_thumbnail_hash . '.' . $article_thumbnail_ext;

                            $oldmask    = umask(0);
                            $insert     = false;

							if($dir = @opendir(PHPWCMS_STORAGE) && copy($article_thumbnail, $article_thumbnail_store)) {

								if($article_thumbnail_size = filesize($article_thumbnail_store)) {

									// yeah, we have it
									$data = array(
                                        'f_pid'         => $feedimport_result['cnt_object']['image_folder_id'],
                                        'f_uid'         => $feedimport_result['cnt_object']['author_id'],
                                        'f_kid'         => 1,
                                        'f_aktiv'       => 1,
                                        'f_public'      => 1,
                                        'f_name'        => $article_thumbnail_name,
                                        'f_created'     => now(),
                                        'f_size'        => $article_thumbnail_size,
                                        'f_type'        => get_mimetype_by_extension($article_thumbnail_ext),
                                        'f_ext'         => $article_thumbnail_ext,
                                        'f_longinfo'    => $article_title,
                                        'f_hash'        => $article_thumbnail_hash,
                                        'f_copyright'   => '',
                                        'f_tags'        => $article_categories
									);

									if(PHPWCMS_CHARSET != 'utf-8') {
                                        $data['f_name']         = makeCharsetConversion($data['f_name'], 'utf-8', PHPWCMS_CHARSET);
                                        $data['f_longinfo']     = makeCharsetConversion($data['f_longinfo'], 'utf-8', PHPWCMS_CHARSET);
                                        $data['f_copyright']    = makeCharsetConversion($data['f_copyright'], 'utf-8', PHPWCMS_CHARSET);
                                        $data['f_tags']         = makeCharsetConversion($data['f_tags'], 'utf-8', PHPWCMS_CHARSET);
									}

									$insert = _dbInsert('phpwcms_file', $data);

									if(isset($insert['INSERT_ID'])) {
                                        $feedimport_result['image']['name']             = $article_thumbnail_name;
                                        $feedimport_result['image']['id']               = $insert['INSERT_ID'];
                                        $feedimport_result['image']['width']            = $phpwcms["content_width"];
                                        $feedimport_result['image']['height']           = '';
                                        $feedimport_result['image']['hash']             = $article_thumbnail_hash;
                                        $feedimport_result['image']['ext']              = $article_thumbnail_ext;
                                        $feedimport_result['image']['list_usesummary']  = 1;
									}
								}

								if(!$feedimport_result['image']['id'] && is_file($article_thumbnail_store)) {
									unlink($article_thumbnail_store);
								}
							}

							if(!empty($dir)) {
								@closedir($dir);
							}

						} else {
							$article_thumbnail = '';
						}
					}
				}

				if($feedimport_result['cnt_object']['source_link_add'] && $rssvalue->get_permalink()) {
					$article_content .= '<p><a href="'.$rssvalue->get_permalink().'" class="feed-permalink">' . (empty($feedimport_result['cnt_object']['source_link_text']) ? '@@Source@@' : $feedimport_result['cnt_object']['source_link_text']) . '</a></p>';
				}

				// define article data
				$data = array(

                    'article_created'       => now(),
                    "article_cid"           => $feedimport_result['cnt_object']['structure_level_id'],
                    "article_uid"           => $feedimport_result['cnt_object']['author_id'],
                    "article_title"         => $article_title,
                    "article_alias"         => $article_alias,
                    "article_keyword"       => $article_categories,
                    "article_aktiv"         => $feedimport_result['cnt_object']['activate_after_import'],
                    "article_begin"         => date('Y-m-d H:i:s', $article_begin),
                    "article_end"           => '0000-00-00 00:00:00',
                    "article_subtitle"      => '',
                    "article_summary"       => $article_summary,
                    "article_redirect"      => '',
                    "article_sort"          => $article_sort_counter,
                    "article_username"      => $article_author,
                    "article_notitle"       => 0,
                    "article_hidesummary"   => 0,
                    "article_image"         => '',
                    "article_cache"         => '',
                    "article_nosearch"      => 0,
                    "article_nositemap"     => 0,
                    "article_aliasid"       => 0,
                    "article_headerdata"    => 0,
                    "article_morelink"      => 1,
                    "article_pagetitle"     => '',
                    "article_paginate"      => 0,
                    "article_priorize"      => 0,
                    "article_norss"         => 0,
					"article_archive_status"=> 1,
                    "article_menutitle"     => '',
                    'article_description'   => $article_description,
                    'article_serialized'    => ''

				);

				$data['article_image'] = serialize($feedimport_result['image']);

				$result = _dbInsert('phpwcms_article', $data);

				if(isset($result['INSERT_ID'])) {

					// create new related content part with additional content
					if($article_content) {

						$cpdata = array(
                            'acontent_aid'              => $result['INSERT_ID'],
                            'acontent_uid'              => $feedimport_result['cnt_object']['author_id'],
                            'acontent_created'          => date('Y-m-d H:i:s', now()),
                            'acontent_tstamp'           => date('Y-m-d H:i:s', now()),
                            'acontent_title'            => '',
                            'acontent_subtitle'         => '',
                            'acontent_text'             => '',
                            'acontent_html'             => '',
                            'acontent_sorting'          => 100,
                            'acontent_visible'          => 1,
                            'acontent_before'           => '',
                            'acontent_after'            => '',
                            'acontent_top'              => 0,
                            'acontent_block'            => 'CONTENT',
                            'acontent_anchor'           => 0,
                            'acontent_module'           => '',
                            'acontent_comment'          => $article_title,
                            'acontent_paginate_page'    => 0,
                            'acontent_paginate_title'   => '',
                            'acontent_granted'          => 0,
                            'acontent_tab'              => '',
                            'acontent_image'            => '',
                            'acontent_files'            => '',
                            'acontent_redirect'         => '',
                            'acontent_alink'            => '',
                            'acontent_template'         => '',
                            'acontent_spacer'           => '',
                            'acontent_category'         => '',
                            'acontent_lang'             => '',
                            'acontent_form'             => '',
                            'acontent_media'            => '',
                            'acontent_newsletter'       => ''
						);

						// CP WYSIWYG HTML
						if(preg_match('/<[^<]+>/', $article_content) || preg_match('/&[A-Za-z]+|#x[\dA-Fa-f]+|#\d+;/', $article_content)) {
							$cpdata['acontent_type'] = 14;
							$cpdata['acontent_html'] = $article_content;
						} else {
							$cpdata['acontent_type'] = 0;
							$cpdata['acontent_text'] = $article_content;
						}

						// Inset CP Data
						$insert = _dbInsert('phpwcms_articlecontent', $cpdata);

						if(!isset($insert['INSERT_ID'])) {
							dumpVar(_dbError());
						}

					}

					$feedimport_result['status'][] = date('Y-m-d, H:i:s', $article_begin) . LF . $article_title . LF . $rssvalue->get_permalink() . LF . PHPWCMS_URL . 'phpwcms.php?do=articles&p=2&s=1&id='.$result['INSERT_ID'];

					$data = array(
                        'cref_type' => 'feed_to_article_import',
                        'cref_rid'  => $result['INSERT_ID'],
                        'cref_str'  => 'feedimport_'.$article_unique_hash
					);

					_dbInsert('phpwcms_crossreference', $data);

					$article_sort_counter = $article_sort_counter + 10;
				}
			}

			// check if status email should be sent
			if(!empty($feedimport_result['cnt_object']['import_status_email']) && is_valid_email($feedimport_result['cnt_object']['import_status_email'])) {

				$feedimport_result['status'] = implode(LF.LF, $feedimport_result['status']);

				sendEmail(array(
                    'recipient' => $feedimport_result['cnt_object']['import_status_email'],
                    'subject'   => 'Import Status: ' . $feedimport_result['cnt_name'],
                    'isHTML'    => 0,
                    'text'      => $feedimport_result['status'],
                    'fromName'  => 'Feed Importer'
				));
			}
		}
	}

	// we quit here
	exit();
}
