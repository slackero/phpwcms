<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

if(!defined('MAGPIE_OUTPUT_ENCODING')) {
	define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
}

// include RSS parser
require_once(MAGPIE_DIR.'rss_fetch.inc.php');

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$rssfeed = unserialize($crow["acontent_form"]);
$rssfeed['template'] = @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/rssfeed/'.$rssfeed['template']);
if(!$rssfeed['template']) {

	$rssfeed['template'] = '
	<!--ITEM_START//-->
	<p style="margin:0;padding:0"><a href="{LINK}" target="_blank">{TITLE}</a></p>
	<!--ITEM_END//-->

	<!--DIVIDER_START//-->
	<div style="margin:0;padding:0;border-top:1px dotted #B2B2B2;height:1px;"><img src="img/leer.gif" width="1" height="1" alt=""></div>
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

if(!$rssfeed["timeout"]) {
	// set to default value = 3600 seconds = 1 hour
	$rssfeed["timeout"] = 3600;
}
if($rssfeed["cacheoff"]) {
	// check if cache enabled or not
	$rssfeed["timeout"] = 0;
}

$rss_obj = @fetch_rss($rssfeed['rssurl'], $rssfeed["timeout"]);

$rss_obj->encoding 				= strtoupper($rss_obj->encoding);
$rss_obj->source_encoding 		= empty($rss_obj->source_encoding) ? '' : strtoupper($rss_obj->source_encoding);

$rssfeed['do_charset_conversion'] 	= false;

if($rss_obj->encoding != $rss_obj->source_encoding && $rss_obj->source_encoding) {
	$rssfeed['do_charset_conversion'] = true;
}

$c = 0;

$rss['template_ITEM']		= get_tmpl_section('ITEM',		$rssfeed['template']);
$rss['template_DIVIDER']	= get_tmpl_section('DIVIDER',	$rssfeed['template']);
$rss['template_FEEDINFO']	= get_tmpl_section('FEEDINFO',	$rssfeed['template']);
$rss['template_RSSFEED']	= get_tmpl_section('RSSFEED',	$rssfeed['template']);

if(strtoupper($rss_obj->feed_type) == 'ATOM') {
	$feedDescrKeyName	= 'description';
	$itemDescrKeyName	= 'summary';
	$itemDateKeyName	= 'date_timestamp';
} else {
	$feedDescrKeyName 	= 'description';
	$itemDescrKeyName 	= 'description';
	$itemDateKeyName	= 'date_timestamp';
}

// check RSS image
if(isset($rss_obj->image['url'])) {
	$rss['temp']['feedinfo']	= '';
	$rss['temp']['linkto'] 		= '';
	if(isset($rss_obj->image['link'])) {
		$rss['temp']['feedinfo']	.= '<a href="'.$rss_obj->image['link'].'" target="_blank">';
		$rss['temp']['linkto']		 = '</a>';
	}
	if($rssfeed['do_charset_conversion']) {
		$rss_obj->image['title'] = makeCharsetConversion($rss_obj->image['title'], $rss_obj->encoding, $rss_obj->source_encoding, 1);
	}
	$rss['temp']['feedinfo'] .= '<img src="'.$rss_obj->image['url'].'" border="0" alt="'.html_specialchars($rss_obj->image['title']).'" />';
	$rss['temp']['feedinfo'] .= $rss['temp']['linkto'];
	$rss['template_FEEDINFO'] = str_replace('{IMAGE}', $rss['temp']['feedinfo'], $rss['template_FEEDINFO']);
} else {
	$rss['template_FEEDINFO'] = str_replace('{IMAGE}', '', $rss['template_FEEDINFO']);
}
if($rssfeed['do_charset_conversion']) {
	$rss_obj->channel['title'] 			= makeCharsetConversion($rss_obj->channel['title'], $rss_obj->encoding, $rss_obj->source_encoding, 1);
	$rss_obj->channel['description']	= makeCharsetConversion($rss_obj->channel[$feedDescrKeyName], $rss_obj->encoding, $rss_obj->source_encoding, 1);
}

$rss['template_FEEDINFO'] = str_replace('{TITLE}', html_specialchars($rss_obj->channel['title']), $rss['template_FEEDINFO']);
$rss['template_FEEDINFO'] = str_replace('{DESCRIPTION}', html_specialchars($rss_obj->channel[$feedDescrKeyName]), $rss['template_FEEDINFO']);


if($rssfeed["item"] && count($rss_obj->items) > $rssfeed["item"]) {
	// cut to max items
	$rss_obj->items = array_slice($rss_obj->items, 0, $rssfeed["item"]);
}


$rss['temp']['items'] = '';
if(is_array($rss_obj->items) && count($rss_obj->items)) {
	foreach($rss_obj->items as $rssvalue) {
		// divider
		if($c) {
			$rss['temp']['items'] .= $rss['template_DIVIDER'];
		}
		if($rssfeed['do_charset_conversion']) {
			$rssvalue['title'] 				= makeCharsetConversion($rssvalue['title'], $rss_obj->encoding, $rss_obj->source_encoding, 1);
			$rssvalue[$itemDescrKeyName]	= isset($rssvalue[$itemDescrKeyName]) ? makeCharsetConversion($rssvalue[$itemDescrKeyName], $rss_obj->encoding, $rss_obj->source_encoding, 1) : '';
		}
		// general item info
		$rss['temp']['item'] = str_replace('{LINK}',  empty($rssvalue['link']) ? '' : $rssvalue['link'],  $rss['template_ITEM']);
		$rss['temp']['item'] = str_replace('{TITLE}', html_specialchars($rssvalue['title']), $rss['temp']['item']);
		$rss['temp']['item'] = str_replace('{DESCRIPTION}', empty($rssvalue[$itemDescrKeyName]) ? '' : $rssvalue[$itemDescrKeyName], $rss['temp']['item']);
		
		// item date
		$rss['temp']['date'] = empty($rssvalue[$itemDateKeyName]) ? time() : $rssvalue[$itemDateKeyName];
		$rss['temp']['item'] = preg_replace('/{DATE:(.*)}/ie', "date('$1',\$rss['temp']['date'])", $rss['temp']['item']);
		
		// add to items list
		$rss['temp']['items'] .= $rss['temp']['item'];
		$c++;
	}
}

// whole rss feed
$rss['template_RSSFEED'] = str_replace('{DIVIDER}', $rss['template_DIVIDER'], $rss['template_RSSFEED']);
$rss['template_RSSFEED'] = str_replace('{ITEMS}', $rss['temp']['items'], $rss['template_RSSFEED']);
$rss['template_RSSFEED'] = str_replace('{FEEDINFO}', $rss['template_FEEDINFO'], $rss['template_RSSFEED']);

$CNT_TMP .= $rss['template_RSSFEED'];

unset($rss, $rssfeed);

?>