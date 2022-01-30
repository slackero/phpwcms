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

// RSS feed

if( !empty($crow["acontent_form"]) && is_string($crow["acontent_form"]) ) {
	$rssfeed = unserialize($crow["acontent_form"]);
} elseif( empty($rssfeed) || !is_array($rssfeed) ) {
	$rssfeed = array();
}

// Feed
if( isset($rssfeed['rssurl']) && !empty($rssfeed['rssurl']) ) {

	if( empty($rssfeed['template']) || !is_file(PHPWCMS_TEMPLATE.'inc_cntpart/rssfeed/'.$rssfeed['template']) ) {
		$rssfeed['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/rssfeed.tmpl') );
	} else {
		$rssfeed['template'] = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/rssfeed/'.$rssfeed['template']) );
	}
	if(!$rssfeed['template']) {

		$rssfeed['template'] = '<!--ITEM_START//--><li><a href="{LINK}" target="_blank">{TITLE}</a></li><!--ITEM_END//-->
<!--DIVIDER_START//--><hr /><!--DIVIDER_END//-->
<!--FEEDINFO_START//--><p>{IMAGE}</p><!--FEEDINFO_END//-->
<!--RSSFEED_START//-->[TITLE]<h3>{TITLE}</h3>[/TITLE][SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]<div class="rss-feed">{FEEDINFO}<ul>{ITEMS}</ul></div><!--RSSFEED_END//-->';

	}

	// Get Template
	$rss['template_ITEM']		= get_tmpl_section('ITEM',		$rssfeed['template']);
	$rss['template_DIVIDER']	= get_tmpl_section('DIVIDER',	$rssfeed['template']);
	$rss['template_FEEDINFO']	= get_tmpl_section('FEEDINFO',	$rssfeed['template']);
	$rss['template_RSSFEED']	= get_tmpl_section('RSSFEED',	$rssfeed['template']);

    require_once PHPWCMS_ROOT.'/include/inc_ext/idna_convert/idna_convert.class.php';

	// Load SimplePie
	require_once(PHPWCMS_ROOT.'/include/inc_ext/simplepie/SimplePie.compiled.php');

	$rss_obj = new SimplePie();

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
			$rss['temp_feedinfo'] .= '<img src="' . $rss_obj->get_image_url() . '" alt="' . $rss_obj->get_image_title() . '"' . PHPWCMS_LAZY_LOADING . HTML_TAG_CLOSE;
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
			$rss['items'][$c] = render_cnt_template($rss['items'][$c], 'DESCRIPTION', $rssvalue->get_description() );
			$rss['items'][$c] = render_cnt_template($rss['items'][$c], 'CONTENT', $rssvalue->get_content() );

			// author
			$rss['item_author'] = $rssvalue->get_author();
			$rss['items'][$c] = render_cnt_template($rss['items'][$c], 'AUTHOR', $rss['item_author'] ? $rss['item_author']->get_name() : '' );

			// item date
			$rss['items'][$c] = render_cnt_date($rss['items'][$c], $rssvalue->get_date('U') );

			// Thumbnail
			$rss['item_thumbnail'] = '';
			if($rss['enclosure'] = $rssvalue->get_enclosure()) {

				$rss['item_thumbnail'] = $rss['enclosure']->get_thumbnail();
				if(!$rss['item_thumbnail'] && $rss['enclosure']->get_link()) {
					$rss['item_thumbnail'] = $rss['enclosure']->get_link();
					if($rss['item_thumbnail'] && ($rss['item_thumbnail_ext'] = which_ext($rss['item_thumbnail']))) {
						if(!in_array($rss['item_thumbnail_ext'], array('jpg', 'jpeg', 'png', 'gif'))) {
							$rss['item_thumbnail'] = '';
						}
					} else {
						$rss['item_thumbnail'] = '';
					}
				}
			}
			$rss['items'][$c] = render_cnt_template($rss['items'][$c], 'IMAGE', $rss['item_thumbnail']);

			$c++;

			if($rssfeed["item"] && $rssfeed["item"] == $c) {

				break;

			}
		}

		// whole rss feed
		$rss['template_RSSFEED'] = render_cnt_template($rss['template_RSSFEED'], 'ATTR_CLASS', html($crow['acontent_attr_class']));
        $rss['template_RSSFEED'] = render_cnt_template($rss['template_RSSFEED'], 'ATTR_ID', html($crow['acontent_attr_id']));
		$rss['template_RSSFEED'] = render_cnt_template($rss['template_RSSFEED'], 'TITLE', html_specialchars($crow['acontent_title']) );
		$rss['template_RSSFEED'] = render_cnt_template($rss['template_RSSFEED'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']) );
		$rss['template_RSSFEED'] = render_cnt_template($rss['template_RSSFEED'], 'ITEMS', implode( LF , $rss['items'] ) );
		$rss['template_RSSFEED'] = str_replace('{FEEDINFO}', $rss['template_FEEDINFO'], $rss['template_RSSFEED']);
		$rss['template_RSSFEED'] = render_cnt_template($rss['template_RSSFEED'], 'LINK', $rss_obj->get_permalink() );
		$rss['template_RSSFEED'] = str_replace('{DIVIDER}', $rss['template_DIVIDER'], $rss['template_RSSFEED']);

		$CNT_TMP .= $rss['template_RSSFEED'];

	}

}
unset($rss, $rssfeed);
