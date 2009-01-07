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

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$rssfeed = unserialize($crow["acontent_form"]);
$rssfeed['template'] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/rssfeed/'.$rssfeed['template']);
if(!$rssfeed['template']) {

	$rssfeed['template'] = '
	<!--ITEM_START//-->
	<p><a href="{LINK}" target="_blank">{TITLE}</a></p>
	<!--ITEM_END//-->

	<!--DIVIDER_START//-->
	<div style="padding:0;margin:0;border-top:1px dotted #B2B2B2;height:1px;overflow:hidden;"><img src="img/leer.gif" width="1" height="1" alt="" /></div>
	<!--DIVIDER_END//-->

	<!--FEEDINFO_START//-->
	<p style="margin-left:3px;margin-bottom:8px;">{IMAGE}</p>
	<!--FEEDINFO_END//-->

	<!--RSSFEED_START//-->
	<div style="clear:both">
	{FEEDINFO}
	{ITEMS}
	</div>
	<!--RSSFEED_END//-->
	';

}

// Get Template
$rss['template_ITEM']		= get_tmpl_section('ITEM',		$rssfeed['template']);
$rss['template_DIVIDER']	= get_tmpl_section('DIVIDER',	$rssfeed['template']);
$rss['template_FEEDINFO']	= get_tmpl_section('FEEDINFO',	$rssfeed['template']);
$rss['template_RSSFEED']	= get_tmpl_section('RSSFEED',	$rssfeed['template']);



// Feed

// Load SimplePie
require_once(PHPWCMS_ROOT.'/include/inc_ext/SimplePie/simplepie.inc.php');
require_once(PHPWCMS_ROOT.'/include/inc_ext/SimplePie/idn/idna_convert.class.php');

$rss_obj = new SimplePie();

// Feed URL
$rss_obj->set_feed_url( $rssfeed['rssurl'] );

// Output Charset
$rss_obj->set_output_encoding( PHPWCMS_CHARSET );

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
	$rss_obj->set_cache_location ( MAGPIE_CACHE_DIR );
	
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


if( $rss_obj->data ) {

	// check RSS image
	if( $rss_obj->get_image_url() ) {
	
		$rss['temp_feedinfo']  = '<a href="'. ( $rss_obj->get_image_link() ? $rss_obj->get_image_link() : $rss_obj->get_permalink() ) .'" target="_blank">';
		$rss['temp_feedinfo'] .= '<img src="' . $rss_obj->get_image_url() . '" border="0" alt="' . $rss_obj->get_image_title() . '" />';
		$rss['temp_feedinfo'] .= '</a>';
		$rss['template_FEEDINFO'] = str_replace('{IMAGE}', $rss['temp_feedinfo'], $rss['template_FEEDINFO']);
	
	} else {
	
		$rss['template_FEEDINFO'] = str_replace('{IMAGE}', '', $rss['template_FEEDINFO']);
	
	}
	
	$rss['template_FEEDINFO'] = str_replace('{TITLE}', $rss_obj->get_title() , $rss['template_FEEDINFO']);
	$rss['template_FEEDINFO'] = str_replace('{DESCRIPTION}', $rss_obj->get_description() , $rss['template_FEEDINFO']);
	
	
	$c				= 0;
	$rss['items']	= array();
	
	foreach($rss_obj->get_items() as $rssvalue) {
	
		// general item info
		$rss['items'][$c] = str_replace('{LINK}',			$rssvalue->get_permalink()	, $rss['template_ITEM'] );
		$rss['items'][$c] = str_replace('{TITLE}',			$rssvalue->get_title()		, $rss['items'][$c] );
		$rss['items'][$c] = str_replace('{DESCRIPTION}', 	$rssvalue->get_content()	, $rss['items'][$c] );
		
		// item date
		$rss['items'][$c] = render_cnt_date($rss['items'][$c], $rssvalue->get_date('U') );
		
		$c++;
		
		if($rssfeed["item"] && $rssfeed["item"] == $c) {
		
			break;
		
		}
	}
	
	// whole rss feed
	$rss['template_RSSFEED'] = str_replace('{DIVIDER}', $rss['template_DIVIDER'], $rss['template_RSSFEED']);
	$rss['template_RSSFEED'] = str_replace('{ITEMS}', implode( $rss['template_DIVIDER'] , $rss['items'] ), $rss['template_RSSFEED']);
	$rss['template_RSSFEED'] = str_replace('{FEEDINFO}', $rss['template_FEEDINFO'], $rss['template_RSSFEED']);
	
	$CNT_TMP .= $rss['template_RSSFEED'];
	
}

unset($rss, $rssfeed);

?>