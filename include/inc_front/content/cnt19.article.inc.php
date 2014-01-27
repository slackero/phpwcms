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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------



//sitemap

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
$sitemap = unserialize($crow["acontent_form"]);
$sitemap['startid'] = intval($sitemap['startid']);

if($sitemap['articleimg']) {
	$sitemap['article_style'] = ' style="list-style-image: url('.$sitemap['articleimg'].');"';
} elseif(empty($sitemap['article_style'])) {
	$sitemap['article_style'] = '';
}
if($sitemap['catimg']) {
	$sitemap['cat_style'] = ' style="list-style-image:url('.$sitemap['catimg'].');"';
} elseif(empty($sitemap['cat_style'])) {
	$sitemap['cat_style'] = '';
}

$CNT_TMP .= $sitemap['before'];

if($content['struct'][ $sitemap['startid'] ]['acat_nositemap']) {

	$sitemap['c'] = '';
	if($sitemap['catclass']) {
		$sitemap['c'] .= ' class="'.$sitemap['catclass'];
		if($sitemap['classcount']) {
			$sitemap['c'] .= '0';
		}
		$sitemap['c'] .= '"';
	}

	if(empty($sitemap["without_parent"])) {
		$CNT_TMP .= "<ul".$sitemap['c']."><li".$sitemap['cat_style'].">";
		$CNT_TMP .= '<a href="index.php?';
		if($content['struct'][ $sitemap['startid'] ]['acat_alias']) {
			$CNT_TMP .= $content['struct'][ $sitemap['startid'] ]['acat_alias'];
		} else {
			$CNT_TMP .= 'id='.$sitemap['startid'];
		}
		$CNT_TMP .= '">'.html_specialchars($content['struct'][ $sitemap['startid'] ]['acat_name']).'</a>';
	}
	if($sitemap["display"]) {
		$CNT_TMP .= build_sitemap_articlelist($sitemap['startid'], 0, $sitemap);
	}
	$CNT_TMP .= build_sitemap($sitemap['startid'], 0, $sitemap);
	if(empty($sitemap["without_parent"])) {
		$CNT_TMP .= "</li>\n</ul>";
	}
}
$CNT_TMP .= $sitemap['after'];

unset($sitemap);

?>