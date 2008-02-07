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



//link article

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

$content['alink'] = unserialize($crow["acontent_form"]);

if(!isset($content['alink']['alink_id'])) {

	$content['alink']['alink_id']	= explode(':', $crow['acontent_alink']);

}

if((is_array($content['alink']['alink_id']) && count($content['alink']['alink_id'])) || (!empty($content['alink']['alink_type']) && is_array($content['alink']['alink_level']) && count($content['alink']['alink_level']))) {

	if(!isset($content['UNIQUE_ALINK'])) {
		$content['UNIQUE_ALINK'] = array();
	}

	if(!empty($content['alink']['alink_template']) && is_file(PHPWCMS_TEMPLATE.'inc_cntpart/teaser/'.$content['alink']['alink_template'])) {

		$content['alink']['alink_template'] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/teaser/'.$content['alink']['alink_template']);

	} elseif(is_file(PHPWCMS_TEMPLATE.'inc_default/teaser.tmpl')) {
	
		$content['alink']['alink_template'] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/teaser.tmpl');

	} else {
	
		$content['alink']['alink_template']  = '<!--TEASER_HEAD_START//--><ul{LINK_ARTICLE_CLASS}>'.LF.'<!--TEASER_HEAD_END//-->';
		$content['alink']['alink_template'] .= '<!--TEASER_ENTRY_START//-->	<li><a href="{ARTICLELINK}">{TITLE}</a></li><!--TEASER_ENTRY_END//-->';
		$content['alink']['alink_template'] .= '<!--TEASER_ENTRY_SPACER_START//-->'.LF.'<!--TEASER_ENTRY_SPACER_END//-->';
		$content['alink']['alink_template'] .= '<!--TEASER_FOOTER_START//-->'.LF.'</ul><!--TEASER_FOOTER_END//-->';
			
	}

	$alink_sql  = "SELECT ar.* FROM ".DB_PREPEND."phpwcms_article ar ";
	$alink_sql .= "WHERE ar.article_public=1 AND ar.article_aktiv=1 AND ar.article_deleted=0 ";
	$alink_sql .= "AND ar.article_begin < NOW() AND ar.article_end > NOW() ";
	
	if(empty($content['alink']['alink_type'])) {
	
		if(!empty($content['alink']['alink_unique']) && count($content['UNIQUE_ALINK'])) {
		
			$content['alink']['alink_id'] = array_diff($content['alink']['alink_id'], $content['UNIQUE_ALINK']);
			$alink_sql .= count($content['alink']['alink_id']) ? 'AND ar.article_id IN ('.implode(',', $content['alink']['alink_id']) . ')' : ' AND 0 ';
		
		} else {
			$alink_sql .= 'AND ar.article_id IN ('.implode(',', $content['alink']['alink_id']) . ')';
		}
	
	} else {
	
		if(is_array($content['alink']['alink_level']) && count($content['alink']['alink_level'])) {
		
			$alink_sql .= 'AND ar.article_cid IN ('.implode(',', $content['alink']['alink_level']) . ')';
			if(!empty($content['alink']['alink_unique']) && count($content['UNIQUE_ALINK'])) {
				$alink_sql .= ' AND ar.article_id NOT IN ('.implode(',', $content['UNIQUE_ALINK']) . ')';
			}
		}
	
		switch($content['alink']['alink_type']) {
	
			case 1:	// create date, DESC
			
					$alink_sql .= " ORDER BY ar.article_created DESC";
					break;
					
			case 2:	// create date, ASC
					
					$alink_sql .= " ORDER BY ar.article_created ASC";
					break;
					
			case 3:	// change date, DESC
			
					$alink_sql .= " ORDER BY ar.article_tstamp DESC";
					break;
					
			case 4:	// change date, ASC
			
					$alink_sql .= " ORDER BY ar.article_tstamp ASC";
					break;
					
			case 5:	// live date, DESC
			
					$alink_sql .= " ORDER BY ar.article_begin DESC";
					break;
					
			case 6:	// live date, ASC
			
					$alink_sql .= " ORDER BY ar.article_begin ASC";
					break;
					
			case 7:	// kill date, DESC
			
					$alink_sql .= " ORDER BY ar.article_end DESC";
					break;
					
			case 8:	// kill date, ASC
			
					$alink_sql .= " ORDER BY ar.article_end ASC";
					break;
	
		}
		
		if(!empty($content['alink']['alink_max']) && intval($content['alink']['alink_max'])) {
			$alink_sql .= " LIMIT ".intval($content['alink']['alink_max']);
		}
	}
	
	$content['alink']['result']	= _dbQuery($alink_sql);
	
	if(count($content['alink']['result'])) {
			
		// before finding a faster solution...
		if(!empty($content['alink']['alink_type'])) {
			$content['alink']['alink_id'] = array();
			foreach($content['alink']['result'] as $value) {
				$content['alink']['alink_id'][] = $value['article_id'];
			}
		}
	
		$content['alink']['alink_template_head']	= get_tmpl_section('TEASER_HEAD', $content['alink']['alink_template']);
		$content['alink']['alink_template_footer']	= get_tmpl_section('TEASER_FOOTER', $content['alink']['alink_template']);
		$content['alink']['alink_template_entry']	= get_tmpl_section('TEASER_ENTRY', $content['alink']['alink_template']);
		$content['alink']['alink_template_space']	= get_tmpl_section('TEASER_SPACER', $content['alink']['alink_template']);
		
		$content['alink']['alink_template_head']	= str_replace('{LINK_ARTICLE_CLASS}', get_class_attrib($template_default["article"]["link_article_class"]), $content['alink']['alink_template_head']);
		
		$content['alink']['tr']		= array();	
		foreach($content['alink']['alink_id'] as $key => $value) {
		
			$content['UNIQUE_ALINK'][$value] = $value; //save UNIQUE Teaser ID
		
			foreach($content['alink']['result'] as $row) {
		
				if($value == $row['article_id'] && isset($content['struct'][ $row['article_cid'] ])) {
				
					// also check against structure				
					$content['alink']['tr'][$key]	= $content['alink']['alink_template_entry'];
					$content['alink']['tr'][$key]	= render_cnt_template($content['alink']['tr'][$key], 'TITLE', html_specialchars($row['article_title']));
					$content['alink']['tr'][$key]	= render_cnt_template($content['alink']['tr'][$key], 'SUBTITLE', html_specialchars($row['article_subtitle']));
					$content['alink']['tr'][$key]	= render_cnt_date($content['alink']['tr'][$key], $row['article_created'], strtotime($row['article_begin']), strtotime($row['article_end']));
					
					$row['article_image'] = unserialize( $row['article_image'] );
					
					// article list image
					if(strpos($content['alink']['tr'][$key], 'IMAGE') !== false) {
					
						$img_thumb_name		= '';
						$img_thumb_rel		= '';
						$img_thumb_abs		= '';
						$img_thumb_width	= 0;
						$img_thumb_height	= 0;
					
						$row['article_image'] = setArticleSummaryImageData( $row['article_image'] );
						
						// check if image available
						if(!empty($row['article_image']['list_id'])) {
						
							if(!empty($content['alink']['alink_width']))  $row['article_image']['list_width']  = $content['alink']['alink_width'];
							if(!empty($content['alink']['alink_height'])) $row['article_image']['list_height'] = $content['alink']['alink_height'];
						
							// build image/image link
							$content['alink']['poplink']			= '';
							$thumb_image 							= false;
							$thumb_img 								= '';
							$content['alink']['caption'] 			= getImageCaption($row['article_image']['list_caption']);
							$row['article_image']['list_caption']	= $content['alink']['caption'][0]; // caption text
							
							
							if(!empty($row['article_image']['list_hash'])) {
							
								$content['alink']['alink_crop'] = empty($content['alink']['alink_crop']) ? 0 : 1;

								$thumb_image = get_cached_image(
													array(	"target_ext"	=>	$row['article_image']['list_ext'],
															"image_name"	=>	$row['article_image']['list_hash'] . '.' . $row['article_image']['list_ext'],
															"max_width"		=>	$row['article_image']['list_width'],
															"max_height"	=>	$row['article_image']['list_height'],
															"thumb_name"	=>	md5(	$row['article_image']['list_hash'].
																						$row['article_image']['list_width'].
																						$row['article_image']['list_height'].
																						$GLOBALS['phpwcms']['sharpen_level'].
																						$content['alink']['alink_crop']
																					),
															'crop_image'	=>	$content['alink']['alink_crop']
													  ));

								if($thumb_image != false) {
				
									$content['alink']['caption'][3] = empty($content['alink']['caption'][3]) ? '' : ' title="'.html_specialchars($content['alink']['caption'][3]).'"';
									$content['alink']['caption'][1] = html_specialchars($content['alink']['caption'][1]);
				
									$thumb_img = '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3].' alt="'.$content['alink']['caption'][1].'"'.$content['alink']['caption'][3].' />';
				
									$img_thumb_name		= $thumb_image[0];
									$img_thumb_rel		= PHPWCMS_IMAGES.$thumb_image[0];
									$img_thumb_abs		= PHPWCMS_URL.PHPWCMS_IMAGES.$thumb_image[0];
									$img_thumb_width	= $thumb_image[1];
									$img_thumb_height	= $thumb_image[2];
								}
							}
							
							$content['alink']['tr'][$key]	= render_cnt_template($content['alink']['tr'][$key], 'IMAGE', $thumb_img);
						
						} else {
						
							$content['alink']['tr'][$key]	= render_cnt_template($content['alink']['tr'][$key], 'IMAGE', '');
						
						}
						
						// replace thumbnail and zoom image information
						$content['alink']['tr'][$key] = str_replace(
						
											array(	'{THUMB_NAME}', '{THUMB_REL}', '{THUMB_ABS}', '{THUMB_WIDTH}', '{THUMB_HEIGHT}' ),
									 		array(	$img_thumb_name, $img_thumb_rel, $img_thumb_abs, $img_thumb_width, $img_thumb_height ),
									 		$content['alink']['tr'][$key] 
										);
						
						// Image Caption
						$content['alink']['tr'][$key]	= render_cnt_template($content['alink']['tr'][$key], 'CAPTION', empty($row['article_image']['list_caption']) ? '' : html_specialchars($row['article_image']['list_caption']));
					
					}
					
					// article summary
					if(strpos($content['alink']['tr'][$key], 'SUMMARY') !== false) {
						
						if(empty($content['alink']['alink_wordlimit']) && !empty($row['article_image']['list_maxwords'])) $content['alink']['alink_wordlimit'] = $row['article_image']['list_maxwords'];
						$row['article_summary'] = empty($content['alink']['alink_allowedtags']) ? strip_tags($row['article_summary']) : strip_tags($row['article_summary'], $content['alink']['alink_allowedtags']);
						$content['alink']['tr'][$key]	= render_cnt_template($content['alink']['tr'][$key], 'SUMMARY', empty($content['alink']['alink_wordlimit']) ? $row['article_summary'] : getCleanSubString($row['article_summary'], $content['alink']['alink_wordlimit'], '&#8230;', 'word'));
					
					}
					
					$content['alink']['tr'][$key]	= str_replace('{ARTICLELINK}', 'index.php?aid='.$row['article_id'], $content['alink']['tr'][$key]);
					break;
					
				}
			
			}
		}
		
		if(count($content['alink']['tr'])) {
			$CNT_TMP .= LF.$content['alink']['alink_template_head'];
			$CNT_TMP .= implode($content['alink']['alink_template_space'], $content['alink']['tr']);
			$CNT_TMP .= $content['alink']['alink_template_footer'].LF;
		}
		
	}
}

									
?>