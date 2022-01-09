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


// Content Type Search Form
$content["template"] = clean_slweg($_POST['template']);

$content["search"]["result_per_page"] = empty($_POST["csearch_result_per_page"]) ? '' : intval($_POST["csearch_result_per_page"]);
$content["search"]["wordlimit"] = isset($_POST["csearch_wordlimit"]) ? trim($_POST["csearch_wordlimit"]) : '';
$content["search"]["wordlimit"] = is_intval($content["search"]["wordlimit"]) ? intval($content["search"]["wordlimit"]) : '';
$content["search"]["newwin"] = isset($_POST["csearch_newwin"]) ? 1 : 0;
$content["search"]["highlight_result"] = isset($_POST["csearch_highlight"]) ? 1 : 0;
$content["search"]["label_input"] = html_specialchars(clean_slweg($_POST["csearch_label_input"]));
$content["search"]["style_input"] = html_specialchars(clean_slweg($_POST["csearch_style_input"]));
$content["search"]["label_button"] = html_specialchars(clean_slweg($_POST["csearch_label_button"]));
$content["search"]["style_button"] = html_specialchars(clean_slweg($_POST["csearch_style_button"]));
$content["search"]["label_result"] = slweg($_POST["csearch_label_result"]);
$content["search"]["style_result"] = html_specialchars(clean_slweg($_POST["csearch_style_result"]));
$content["search"]["align"] = isset($_POST["csearch_align"]) ? intval($_POST["csearch_align"]) : 0;
$content["search"]["text_intro"] = slweg($_POST["csearch_text_intro"], 65500);
$content["search"]["text_result"] = slweg($_POST["csearch_text_result"], 65500);
$content["search"]["text_noresult"] = slweg($_POST["csearch_text_noresult"], 65500);
$content["search"]["template"] = isset($_POST["csearch_template"]) ? slweg($_POST["csearch_template"]) : '';
$content['search']["text_html"] = empty($_POST['csearch_text_html']) ? 0 : (intval($_POST['csearch_text_html']) ? 1 : 0);
$content["search"]["label_pages"] = slweg($_POST['csearch_label_pages']);
$content["search"]["minchar"] = intval($_POST['csearch_minchar']);
if(!$content["search"]["minchar"]) {
	$content["search"]["minchar"] = 3;
}

$content["search"]["start_at"] = isset($_POST['csearch_start_at']) && is_array($_POST['csearch_start_at']) ? $_POST['csearch_start_at'] : array();

$content["search"]["show_always"] 	= empty($_POST['csearch_show_always'])	? 0 : 1;
$content["search"]["show_top"] 		= empty($_POST['csearch_show_top'])		? 0 : 1;
$content["search"]["show_bottom"] 	= empty($_POST['csearch_show_bottom'])	? 0 : 1;
$content["search"]["show_next"] 	= empty($_POST['csearch_show_next'])	? 0 : 1;
$content["search"]["show_prev"] 	= empty($_POST['csearch_show_prev'])	? 0 : 1;

$content["search"]["module"]		= array();
if(isset($_POST['csearch_module']) && is_array($_POST['csearch_module']) && count($_POST['csearch_module'])) {
	foreach($_POST['csearch_module'] as $key => $value) {
		$value = strtolower(trim($key));
		if($value) {
			$content["search"]["module"][$value] = true;
		}
	}
}

$content['search']["search_news"]	= empty($_POST['csearch_news']) ? 0 : 1;
$content['search']["news_lang"]		= empty($_POST['csearch_news_lang']) ? array() : convertStringToArray( strtolower(clean_slweg($_POST['csearch_news_lang'])) );
$content['search']["news_category"]	= empty($_POST['csearch_news_category']) ? array() : convertStringToArray( strtolower(clean_slweg($_POST['csearch_news_category'])) );
$content['search']["news_andor"]	= clean_slweg($_POST['csearch_news_andor']);
$content['search']["news_url"]		= clean_slweg($_POST['csearch_news_url']);

$content['search']["no_filenames"]	= empty($_POST['csearch_nofilenames']) ? 0 : 1;
$content['search']["no_username"]	= empty($_POST['csearch_nousername']) ? 0 : 1;
$content['search']["no_caption"]	= empty($_POST['csearch_nocaption']) ? 0 : 1;
$content['search']["no_keyword"]	= empty($_POST['csearch_nokeyword']) ? 0 : 1;
$content['search']["hide_summary"]	= empty($_POST['csearch_hidesummary']) ? 0 : 1;
$content['search']["type"]			= empty($_POST['csearch_type']) || strtoupper($_POST['csearch_type']) == 'OR' ? 'OR' : 'AND';
