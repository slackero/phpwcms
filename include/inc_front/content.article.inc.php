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


$sql  =	"SELECT *, UNIX_TIMESTAMP(article_tstamp) AS article_date ";
$sql .=	"FROM ".DB_PREPEND."phpwcms_article ar LEFT JOIN ".DB_PREPEND."phpwcms_articlecat ac ON ";
$sql .=	"ar.article_cid = ac.acat_id WHERE ";
$sql .= "ar.article_id=".$aktion[1]." AND ";
// VISIBLE_MODE: 0 = frontend (all) mode, 1 = article user mode, 2 = admin user mode
switch(VISIBLE_MODE) {
	case 0: $sql .= "ar.article_public=1 AND ";
			$sql .= "ar.article_aktiv=1 AND ";
			break;
	case 1: $sql .= "ar.article_uid=".$_SESSION["wcs_user_id"]." AND ";
			break;
	//case 2: admin mode no additional neccessary
}
$sql .= "ar.article_deleted=0 AND ar.article_begin<NOW() ";
$sql .= "AND ar.article_end>NOW() LIMIT 1";

if($result = mysql_query($sql, $db) or die("error while reading article datas")) {
	if($row = mysql_fetch_assoc($result)) {
		//Da max. 1 Datensatz -> sofort Datenbankverbindung kappen
		mysql_free_result($result);
		
		// now try to retrieve alias article information
		if($row["article_aliasid"]) {
			$alias_sql  = "SELECT *, UNIX_TIMESTAMP(article_tstamp) AS article_date ";
			$alias_sql .= "FROM ".DB_PREPEND."phpwcms_article ";
			$alias_sql .= "WHERE article_deleted=0 AND article_id=".intval($row["article_aliasid"]);
			if(!$row["article_headerdata"]) {
				switch(VISIBLE_MODE) {
					case 0: $alias_sql .= " AND article_public=1 AND article_aktiv=1";
								break;
					case 1: $alias_sql .= " AND article_uid=".$_SESSION["wcs_user_id"];
								break;
				}
				$alias_sql .= " AND article_begin < NOW() AND article_end > NOW()";
			}
			$alias_sql .= " AND article_deleted=0 LIMIT 1";
			if($alias_result = mysql_query($alias_sql, $db)) {
				if($alias_row = mysql_fetch_assoc($alias_result)) {
					$row["article_id"] = $alias_row["article_id"];
					// use alias article header data
					if(!$row["article_headerdata"]) {
						$row["article_title"]		= $alias_row["article_title"];
						$row["article_subtitle"]	= $alias_row["article_subtitle"];
						$row["article_keyword"]		= $alias_row["article_keyword"];
						$row["article_summary"]		= $alias_row["article_summary"];
						$row["article_redirect"]	= $alias_row["article_redirect"];
						$row["article_date"]		= $alias_row["article_date"];
						$row["article_image"]		= $alias_row["article_image"];
						$row["article_pagetitle"]	= $alias_row["article_pagetitle"];
					}
				}
				mysql_free_result($alias_result);
			}
		}

		//Kategoriebezeichner
		$article['cat'] = $content['struct'][$row["article_cid"]]['acat_name'];

		//redirection definition
		if($row["article_redirect"]) {

			$row["article_redirect"]		= str_replace('{SITE}', PHPWCMS_URL, $row["article_redirect"]);
			$content["redirect"]			= explode(' ', $row["article_redirect"]);
			$content["redirect"]["link"]	= $content["redirect"][0];
			$content["redirect"]["target"]	= isset($content["redirect"][1]) ? $content["redirect"][1] : '';
			$content["redirect"]["timeout"]	= isset($content["redirect"][2]) ? intval($content["redirect"][2]) : 0;

			//check how to redirect - new window or self window
			if(	!$content["redirect"]["target"]	|| $content["redirect"]["target"] == "_self" ||	$content["redirect"]["target"] == "_top" ||	$content["redirect"]["target"] == "_parent") {
				// direct redirection in the same window
				headerRedirect($content["redirect"]["link"], 301);
			} else {
				// redirection by using a special <meta><javascript> html head part
				$content["redirect"]["code"]  = LF . '  <noscript>' . LF;
				$content["redirect"]["code"] .= '	<meta http-equiv="refresh" content="'.$content["redirect"]["timeout"].';URL=';
				$content["redirect"]["code"] .= $content["redirect"]["link"];
				$content["redirect"]["code"] .= '" />'.LF.'  </noscript>' . LF;
				$content["redirect"]["code"] .= '  <script type="text/javascript">' . LF . '  <!--' . LF;
				$content["redirect"]["code"] .= '	var redirectWin;' . LF;
				if($content["redirect"]["timeout"]) {
					$content["redirect"]["code"] .= '	window.setTimeout(\'window.open("'.$content["redirect"]["link"].'", redirectWin)\', ';
					$content["redirect"]["code"] .= $content["redirect"]["timeout"] * 1000;
					$content["redirect"]["code"] .= ');';
				} else {
					$content["redirect"]["code"] .= '	window.open("'.$content["redirect"]["link"].'", redirectWin);';
				}
				$content["redirect"]["code"] .= LF . '  //-->' . LF . '  </script>' . LF;
			}
		}

		//set cache timeout for this article
		if($row['article_cache'] != '') {
			$phpwcms['cache_timeout'] = $row['article_cache'];
		}
		//get value for article search (on/off)
		if($row['article_nosearch'] != '') {
			$cache_searchable = '1';
		}

		//check if article has custom pagetitle
		if(!empty($row["article_pagetitle"])) {
		
			$content["pagetitle"] = $row["article_pagetitle"];
		
		} else {
			
			$content["pagetitle"] = setPageTitle($content["pagetitle"], $article['cat'], $row["article_title"]);
		
		}
		
		$content['all_keywords'] = $row['article_keyword'];

		$content["main"] .= '<a name="jump'.$row["article_id"].'"></a>';

		// only copy the catname to a special var for multiple for use in any block
		$content["cat"]				= html_specialchars($article["cat"]);
		$content["cat_id"]			= $aktion[0] = $row["article_cid"]; //set category ID to actual category value
		$content["article_id"] 		= $row["article_id"];
		$content["summary"]			= '';
		$content['article_title']	= $row["article_title"];
		$content['article_summary']	= $row["article_summary"];
		
		$content["article_date"]	= $row["article_date"]; // article date
		$content["article_created"]	= $row["article_created"]; // article created

		//retrieve image info
		$row["article_image"] = unserialize($row["article_image"]);
		//$caption	= explode('|', $row["article_image"]["caption"]);
		$caption = getImageCaption($row["article_image"]["caption"]);
		$row["article_image"]["caption"] = $caption[0]; //$caption[0]
				
		//build image/image link
		$thumb_image = false;
		$thumb_img = '';
		$popup_img = '';
		
		$img_thumb_name		= '';
		$img_thumb_rel		= '';
		$img_thumb_abs		= '';
		$img_thumb_width	= 0;
		$img_thumb_height	= 0;
		
		$img_zoom_name		= '';
		$img_zoom_rel		= '';
		$img_zoom_abs		= '';
		$img_zoom_width		= 0;
		$img_zoom_height	= 0;
		
		if(!empty($row["article_image"]["hash"])) {

			$thumb_image = get_cached_image(
			array(	"target_ext"	=>	$row["article_image"]['ext'],
					"image_name"	=>	$row["article_image"]['hash'] . '.' . $row["article_image"]['ext'],
					"max_width"		=>	$row["article_image"]['width'],
					"max_height"	=>	$row["article_image"]['height'],
					"thumb_name"	=>	md5($row["article_image"]['hash'].$row["article_image"]['width'].$row["article_image"]['height'].$GLOBALS['phpwcms']["sharpen_level"])
			));

			if($thumb_image != false) {
			
				$thumb_img  = '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3];
				$thumb_img .= ' alt="'.html_specialchars($caption[1]).'" title="'.html_specialchars($caption[3]).'" />';
				
				$img_thumb_name		= $thumb_image[0];
				$img_thumb_rel		= PHPWCMS_IMAGES.$thumb_image[0];
				$img_thumb_abs		= PHPWCMS_URL.PHPWCMS_IMAGES.$thumb_image[0];
				$img_thumb_width	= $thumb_image[1];
				$img_thumb_height	= $thumb_image[2];

				if($row["article_image"]["zoom"]) {

					$zoominfo = get_cached_image(
					array(	"target_ext"	=>	$row["article_image"]['ext'],
					"image_name"	=>	$row["article_image"]['hash'] . '.' . $row["article_image"]['ext'],
					"max_width"		=>	$GLOBALS['phpwcms']["img_prev_width"],
					"max_height"	=>	$GLOBALS['phpwcms']["img_prev_height"],
					"thumb_name"	=>	md5($row["article_image"]['hash'].$GLOBALS['phpwcms']["img_prev_width"].$GLOBALS['phpwcms']["img_prev_height"].$GLOBALS['phpwcms']["sharpen_level"])
					));

					if($zoominfo != false) {
					
						$img_zoom_name		= $zoominfo[0];
						$img_zoom_rel		= PHPWCMS_IMAGES.$zoominfo[0];
						$img_zoom_abs		= PHPWCMS_URL.PHPWCMS_IMAGES.$zoominfo[0];
						$img_zoom_width		= $zoominfo[1];
						$img_zoom_height	= $zoominfo[2];

						$popup_img = 'image_zoom.php?'.getClickZoomImageParameter($zoominfo[0].'?'.$zoominfo[3]);
					
						if(!empty($caption[2][0])) {
							$open_link = $caption[2][0];
							$return_false = '';
						} else {
							$open_link = $popup_img;
							$return_false = 'return false;';
						}

						if(empty($row["article_image"]["lightbox"])) {
							$thumb_href  = '<a href="'.$popup_img.'" onclick="window.open(\''.$open_link;
							$thumb_href .= "','previewpic','width=".$zoominfo[1].",height=".$zoominfo[2]."');".$return_false;
							$thumb_href .= '"';
							if(!empty($caption[2][1])) {
								$thumb_href .= $caption[2][1];
							}
							$thumb_href .= '>';
						} else {
						
							//lightbox
							initializeLightbox();
							
							$thumb_href  = '<a href="'.PHPWCMS_IMAGES . $zoominfo[0].'"';
							if($row["article_image"]["caption"]) {
								$thumb_href .= ' title="'.parseLightboxCaption($row["article_image"]["caption"]).'"';
							}
							$thumb_href .= ' rel="lightbox" target="_blank">';
						}
						
						$thumb_img = $thumb_href.$thumb_img.'</a>';
						$popup_img = $thumb_img;
						
					}
				} else {
						
					if($caption[2][0]) {
						$thumb_img = '<a href="'.$caption[2][0].'"'.$caption[2][1].'>'.$thumb_img.'</a>';
					}
				}
			}
		}
		

		// make some elementary checks regarding content part pagination
		$_CpPaginate = false;
		
		if($row['article_paginate'] && $aktion[2] != 1) { // no pagination in print mode
			
			// use an IF because acontent_paginate_page=1 is the same as acontent_paginate_page=0
			$sql_cnt  = "SELECT DISTINCT IF(acontent_paginate_page=1, 0, acontent_paginate_page) AS acontent_paginate_page ";
			$sql_cnt .= "FROM ".DB_PREPEND."phpwcms_articlecontent WHERE ";
			$sql_cnt .= "acontent_aid=".$row["article_id"]." AND acontent_visible=1 AND acontent_trash=0 ";
			$sql_cnt .= "AND acontent_block IN ('', 'CONTENT') ORDER BY acontent_paginate_page DESC";
			$sql_cnt  = _dbQuery($sql_cnt);
			
			if(($paginate_count = count($sql_cnt)) > 1) {
			
				$content['CpPages']		= array();
				$_CpPaginate			= true;
					
				foreach($sql_cnt as $crow) {
					$content['CpPages'][ $crow['acontent_paginate_page'] ] = $paginate_count; // set page numbers
					$paginate_count--;
				}
	
				$content['CpPages']		= array_reverse($content['CpPages'], true);
				
				// check if given cp paginate page is valid, and reset to page 1 (=0)
				// same happens for 1 because this will always be used like it is 0
				if(!isset($content['CpPages'][ $content['aId_CpPage'] ])) {
					$content['aId_CpPage'] = 0;
				}
				
			} else {
			
				$content['aId_CpPage'] = 0;
			
			}
			
		}		

		// check for custom full article summary template
		if(!empty($row["article_image"]['tmplfull']) && $row["article_image"]['tmplfull']!='default' && is_file(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/article/'.$row["article_image"]['tmplfull'])) {

			// try to read the template files

			if($_CpPaginate && $content['aId_CpPage'] > 1 && is_file(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/article/paginate/'.$row["article_image"]['tmplfull'])) { // check for default cp paginate template
				$row["article_image"]['tmplfull'] = file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/article/paginate/'.$row["article_image"]['tmplfull']);
			} else {
				$row["article_image"]['tmplfull'] = file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/articlesummary/article/'.$row["article_image"]['tmplfull']);
			}
		
		} elseif(is_file(PHPWCMS_TEMPLATE.'inc_default/article_summary.tmpl')) {
		
			// load default template
		
			if($_CpPaginate && $content['aId_CpPage'] > 1 && is_file(PHPWCMS_TEMPLATE.'inc_default/article_summary_paginate.tmpl')) { // check for default cp paginate template
				$row["article_image"]['tmplfull'] = file_get_contents(PHPWCMS_TEMPLATE.'inc_default/article_summary_paginate.tmpl');
			} else {
				$row["article_image"]['tmplfull'] = file_get_contents(PHPWCMS_TEMPLATE.'inc_default/article_summary.tmpl');
			}
		
		} else {
		
			// template fallback
			if($_CpPaginate && $content['aId_CpPage'] > 1) {
				$row["article_image"]['tmplfull']  = '[TITLE]<h1>{TITLE}</h1>[/TITLE]'.LF.'<!--CP_PAGINATE_START//-->'.LF;
				$row["article_image"]['tmplfull'] .= '<div class="cpPagination">'.LF;
				$row["article_image"]['tmplfull'] .= '	[CP_PAGINATE_PREV]<a href="{CP_PAGINATE_PREV}" class="cpPaginationPrev">Previous</a>[/CP_PAGINATE_PREV]'.LF;
				$row["article_image"]['tmplfull'] .= '	[CP_PAGINATE]{CP_PAGINATE}[/CP_PAGINATE]'.LF;
				$row["article_image"]['tmplfull'] .= '	[CP_PAGINATE_NEXT]<a href="{CP_PAGINATE_NEXT}" class="cpPaginationNext">Previous</a>[/CP_PAGINATE_NEXT]'.LF;
				$row["article_image"]['tmplfull'] .= '</div><!--CP_PAGINATE_END//-->';
			} else {
				$row["article_image"]['tmplfull']  = '[TITLE]<h1>{TITLE}</h1>'.LF.'[/TITLE][SUB]<h3>{SUB}</h3>'.LF.'[/SUB]';
				$row["article_image"]['tmplfull'] .= '[SUMMARY][IMAGE]<span style="float:left;margin:2px 10px 5px 0;">{IMAGE}';
				$row["article_image"]['tmplfull'] .= '[CAPTION]<br />'.LF.'{CAPTION}[/CAPTION]</span>'.LF.'[/IMAGE]{SUMMARY}</div>'.LF.'[/SUMMARY]';
			}

		}
		
		//rendering
		if($row["article_image"]['tmplfull']) {
		
			// replace thumbnail and zoom image information
			$row["article_image"]['tmplfull'] = str_replace( 
								array(	'{THUMB_NAME}', '{THUMB_REL}', '{THUMB_ABS}', '{THUMB_WIDTH}', '{THUMB_HEIGHT}',
										'{IMAGE_NAME}', '{IMAGE_REL}', '{IMAGE_ABS}', '{IMAGE_WIDTH}', '{IMAGE_HEIGHT}' ),
								array(	$img_thumb_name, $img_thumb_rel, $img_thumb_abs, $img_thumb_width, $img_thumb_height,
										$img_zoom_name, $img_zoom_rel, $img_zoom_abs, $img_zoom_width, $img_zoom_height ),
								$row["article_image"]['tmplfull'] );
			
			// check if TITLE should be hidden
			if(!$row["article_notitle"]) {
				$row["article_image"]['tmplfull'] = render_cnt_template($row["article_image"]['tmplfull'], 'TITLE', html_specialchars($row["article_title"]));
			} else {
				$row["article_image"]['tmplfull'] = replace_cnt_template($row["article_image"]['tmplfull'], 'TITLE', '');
			}
			$row["article_image"]['tmplfull'] = render_cnt_template($row["article_image"]['tmplfull'], 'SUB', html_specialchars($row["article_subtitle"]));
			$row["article_image"]['tmplfull'] = render_cnt_template($row["article_image"]['tmplfull'], 'EDITOR', html_specialchars($row["article_username"]));
			
			// when "hide summary" is enabled replace everything between [SUMMARY][/SUMMARY]
			if(!$row["article_hidesummary"]) {
				$row["article_image"]['tmplfull'] = render_cnt_template($row["article_image"]['tmplfull'], 'SUMMARY', $row["article_summary"]);
			} else {
				$row["article_image"]['tmplfull'] = replace_cnt_template($row["article_image"]['tmplfull'], 'SUMMARY', '');
			}
			
			$row["article_image"]['tmplfull'] = render_cnt_template($row["article_image"]['tmplfull'], 'IMAGE', $thumb_img);
			$row["article_image"]['tmplfull'] = render_cnt_template($row["article_image"]['tmplfull'], 'CAPTION', nl2br(html_specialchars($row["article_image"]["caption"])));
			$row["article_image"]['tmplfull'] = render_cnt_date($row["article_image"]['tmplfull'], $content["article_date"], strtotime($row['article_begin']), strtotime($row['article_end']));
			$row["article_image"]['tmplfull'] = render_cnt_template($row["article_image"]['tmplfull'], 'ZOOMIMAGE', $popup_img);
			
			$content["summary"] .= $row["article_image"]['tmplfull'];
			$row["article_image"]['tmplfull'] = 1;
		
		} else {
		
			$row["article_image"]['tmplfull'] = 0;
		
		}

		if($content["summary"]) {
		
			$content["main"] .= $content["summary"];
			$content["main"] .= $template_default["article"]["head_after"];
		
		}

		// render content parts
		$sql_cnt  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_aid=".$row["article_id"]." ";
		$sql_cnt .=	"AND acontent_visible=1 AND acontent_trash=0 ORDER BY acontent_sorting, acontent_id";
		$cresult  = _dbQuery($sql_cnt);
		
		foreach($cresult as $crow) {
		
			// check for article content part pagination
			if($_CpPaginate && ($crow['acontent_block'] == 'CONTENT' || $crow['acontent_block'] == '')) {
	
				// now check which content part should be rendered...
				
				// first - cp page 0 OR 1 = 1st page and the same
				if(($content['aId_CpPage'] == 0 || $content['aId_CpPage'] == 1) && ($crow['acontent_paginate_page'] == 0 || $crow['acontent_paginate_page'] == 1)) {
										
					// then compare if selected page is same as paginate page
				} elseif($content['aId_CpPage'] == $crow['acontent_paginate_page']) {
										
					// hm, do not render current content part
				} else {
				
					continue;
				}
	
			}
		
			// if type of content part not enabled available 
			if(!isset($wcs_content_type[ $crow["acontent_type"] ]) ||  ($crow["acontent_type"] == 30 && !isset($phpwcms['modules'][$crow["acontent_module"]]))) {
				continue;
			}
		
			// do everything neccessary for alias content part
			if($crow["acontent_type"] == 24) {
				$crow = getContentPartAlias($crow);
			}
		
			// every article content  will be rendered into temp var 
			$CNT_TMP  = '';
			
			// each content part will get an anchor
			if($crow["acontent_anchor"]) {
				$CNT_TMP .= '<a name="cpid'.$crow["acontent_id"].'" id="cpid'.$crow["acontent_id"].'" class="cpidClass"></a>';
			}

			// Space before
			if($crow["acontent_before"]) {
				if(!empty($template_default["article"]["div_spacer"])) {
					$CNT_TMP .= '<div style="margin:'.$crow["acontent_before"].'px 0 0 0;padding:0;" class="spaceBeforeCP"></div>';
				} else {
					$CNT_TMP .= '<br class="spaceBeforeCP" />'.spacer(1,$crow["acontent_before"]);
				}
			}
			
			// include content part code section
			if($crow["acontent_type"] != 30) {
			
				@include(PHPWCMS_ROOT."/include/inc_front/content/cnt".$crow["acontent_type"].".article.inc.php");
			
			} elseif($crow["acontent_type"] == 30 && is_file($phpwcms['modules'][$crow["acontent_module"]]['path'].'inc/cnt.article.php')) {
			
				// now try to include module content part code
				include($phpwcms['modules'][$crow["acontent_module"]]['path'].'inc/cnt.article.php');
			
			}

			//check if top link should be shown
			$CNT_TMP .= getContentPartTopLink($crow["acontent_top"]);

			// Space after
			if($crow["acontent_after"]) {
				if(!empty($template_default["article"]["div_spacer"])) {
					$CNT_TMP .= '<div style="margin:0 0 '.$crow["acontent_after"].'px 0;padding:0;" class="spaceAfterCP"></div>';
				} else {
					$CNT_TMP .= '<br class="spaceAfterCP" />'.spacer(1,$crow["acontent_after"]);
				}
			}
			
			//Maybe content part ID should b used inside templates or for something different
			$CNT_TMP = str_replace( array('[%CPID%]', '{CPID}'), $crow["acontent_id"], $CNT_TMP );
			
			//check if PHP replacent tags are allowed for content
			if(empty($phpwcms["allow_cntPHP_rt"])) {
				$CNT_TMP = remove_unsecure_rptags($CNT_TMP);
			}
			
			// now add rendered content part to right frontend content 
			// var given by block -> $content['CB'][$crow['acontent_block']]
			if($crow['acontent_block'] == 'CONTENT' || $crow['acontent_block'] == '') {
				// default content block
				$content["main"] .= $CNT_TMP;
			} else {
				// check if content block var is set
				if(!isset($content['CB'][$crow['acontent_block']])) {
					$content['CB'][$crow['acontent_block']] = '';
				}
				$content['CB'][$crow['acontent_block']] .= $CNT_TMP;
			}
			
			
		}
	}
}

if(empty($template_default["article"]["div_spacer"])) {
	$content["main"] = str_replace("</table>\n<br />", "</table>\n", $content["main"]);
	$content["main"] = str_replace("</table><br />", "</table>", $content["main"]);
	$content["main"] = str_replace("</div><br />", "</div>", $content["main"]);
}

?>