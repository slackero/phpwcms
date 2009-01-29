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

// RSS feed

//$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

if( !empty($crow["acontent_form"]) && is_string($crow["acontent_form"]) ) {
	$rssfeed = unserialize($crow["acontent_form"]);
} elseif( empty($rssfeed) || !is_array($rssfeed) ) {
	$rssfeed = array();
}

// Feed
if( isset($rssfeed['rssurl']) && !empty($rssfeed['rssurl']) ) {
	
	if( empty($rssfeed['template']) || !is_file(PHPWCMS_TEMPLATE.'inc_cntpart/rssfeed/'.$rssfeed['template']) ) {
		$rssfeed['template'] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/rssfeed.tmpl');
	} else {
		$rssfeed['template'] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/rssfeed/'.$rssfeed['template']);
	}
	if(!$rssfeed['template']) {

		$rssfeed['template'] = '<!--ITEM_START//--><p><a href="{LINK}" target="_blank">{TITLE}</a></p><!--ITEM_END//-->
<!--DIVIDER_START//--><div style="padding:0;margin:0;border-top:1px dotted #B2B2B2;height:1px;overflow:hidden;"><img src="img/leer.gif" width="1" height="1" alt="" /></div><!--DIVIDER_END//-->
<!--FEEDINFO_START//--><p style="margin-left:3px;margin-bottom:8px;">{IMAGE}</p><!--FEEDINFO_END//-->
<!--RSSFEED_START//-->[TITLE]<h3>{TITLE}</h3>[/TITLE][SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]<div>{FEEDINFO}{ITEMS}</div><!--RSSFEED_END//-->';
	
	}
	
	// Get Template
	$rss['template_ITEM']		= get_tmpl_section('ITEM',		$rssfeed['template']);
	$rss['template_DIVIDER']	= get_tmpl_section('DIVIDER',	$rssfeed['template']);
	$rss['template_FEEDINFO']	= get_tmpl_section('FEEDINFO',	$rssfeed['template']);
	$rss['template_RSSFEED']	= get_tmpl_section('RSSFEED',	$rssfeed['template']);

	
	// Load SimplePie
	require_once(PHPWCMS_ROOT.'/include/inc_ext/SimplePie/simplepie.inc.php');
	//require_once(PHPWCMS_ROOT.'/include/inc_ext/SimplePie/idn/idna_convert.class.php');
	
	$rss_obj = new SimplePie();
	
	//$CNT_TMP .= dumpVar($rssfeed['rssurl'], 2);
	
	// Feed URL
	$rss_obj->set_feed_url( $rssfeed['rssurl'] );
	
	// Output Encoding Charset
	$rss_obj->set_output_encoding( PHPWCMS_CHARSET );
	
	// Input Encoding Charset
	if(!empty($rssfeed['content_type'])) {
		$rss_obj->set_input_encoding( $rssfeed['content_type'] );
	}
	
	
	// Feed Cache Timeout
	if(!$rssfeed["timeout"]) {
		// set to default value = 3600 seconds = 1 hour
		$rssfeed["timeout"] = 3600;
	}
	if($rssfeed["cacheoff"]) {
		// check if cache enabled or not
		$rssfeed["timeout"] = 0;
	}
	
	if($rssfeed["timeout"]) {
	
		$rss_obj->enable_cache( true );
		$rss_obj->set_cache_duration ( $rssfeed["timeout"] );
		$rss_obj->set_cache_location ( PHPWCMS_RSS );
		
	} else {
		
		$rss_obj->enable_cache( false );
	
	}
	
	//$rss_obj->enable_cache( false );
	
	// Remove surrounding DIV
	$rss_obj->remove_div( true );
	
	// Strip all HTML Tags
	$rss_obj->strip_htmltags( true );
	
	// Limit items
	if($rssfeed["item"]) {
		$rss_obj->set_item_limit( $rssfeed["item"] );
	}
	
	// Init Feed
	$rss_obj->init();
	
	if($rss_obj->error()) {
	
		$CNT_TMP .= $rss_obj->error();
	
	}
	
	
	if( $rss_obj->data ) {
	
		// check RSS image
		if( $rss_obj->get_image_url() ) {
		
			$rss['temp_feedinfo']  = '<a href="'. ( $rss_obj->get_image_link() ? $rss_obj->get_image_link() : $rss_obj->get_permalink() ) .'" target="_blank">';
			$rss['temp_feedinfo'] .= '<img src="' . $rss_obj->get_image_url() . '" border="0" alt="' . $rss_obj->get_image_title() . '" />';
			$rss['temp_feedinfo'] .= '</a>';

			$rss['template_FEEDINFO'] = render_cnt_template($rss['template_FEEDINFO'], 'IMAGE', $rss['temp_feedinfo']);
		
		} else {
			
			$rss['template_FEEDINFO'] = render_cnt_template($rss['template_FEEDINFO'], 'IMAGE', '');
		
		}
		
		$rss['template_FEEDINFO'] = render_cnt_template($rss['template_FEEDINFO'], 'TITLE', $rss_obj->get_title());
		$rss['template_FEEDINFO'] = render_cnt_template($rss['template_FEEDINFO'], 'DESCRIPTION', $rss_obj->get_description());
		
		
		$c				= 0;
		$rss['items']	= array();
		
		foreach($rss_obj->get_items() as $rssvalue) {
		
			// general item info
			$rss['items'][$c] = render_cnt_template($rss['template_ITEM'], 'LINK', $rssvalue->get_permalink() );
			$rss['items'][$c] = render_cnt_template($rss['items'][$c], 'TITLE', $rssvalue->get_title() );
			$rss['items'][$c] = render_cnt_template($rss['items'][$c], 'DESCRIPTION', $rssvalue->get_content() );
			
			// item date
			$rss['items'][$c] = render_cnt_date($rss['items'][$c], $rssvalue->get_date('U') );
			
			$c++;
			
			if($rssfeed["item"] && $rssfeed["item"] == $c) {
			
				break;
			
			}
		}
		
		// whole rss feed
		$rss['template_RSSFEED'] = render_cnt_template($rss['template_RSSFEED'], 'TITLE', html_entities($crow['acontent_title']) );
		$rss['template_RSSFEED'] = render_cnt_template($rss['template_RSSFEED'], 'SUBTITLE', html_entities($crow['acontent_subtitle']) );
		$rss['template_RSSFEED'] = str_replace('{DIVIDER}', $rss['template_DIVIDER'], $rss['template_RSSFEED']);
		$rss['template_RSSFEED'] = str_replace('{ITEMS}', implode( $rss['template_DIVIDER'] , $rss['items'] ), $rss['template_RSSFEED']);
		$rss['template_RSSFEED'] = str_replace('{FEEDINFO}', $rss['template_FEEDINFO'], $rss['template_RSSFEED']);
		$rss['template_RSSFEED'] = render_cnt_template($rss['template_RSSFEED'], 'LINK', $rss_obj->get_permalink() );
		
		$CNT_TMP .= $rss['template_RSSFEED'];
		
	}

}
unset($rss, $rssfeed);

?>