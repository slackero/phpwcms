<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Module/Plug-in Shop & Products - include in frontend search

// include search specific functions and class
require_once($cmsgo['modules'][$key]['path'].'inc/frontend.search.inc.php');

// initialize shop module search class
$s_module = new ModuleShopSearch();

// set current search result counter
$s_module->search_result_entry		= $s_run;
$s_module->search_words				= $s_search_words;
$s_module->search_word_count		= $s_search_words_count;
$s_module->search_highlight			= $content['search']['highlight_result'];
$s_module->search_highlight_words	= $content['highlight'];
$s_module->search_wordlimit			= $content['search']['wordlimit'];
$s_module->ellipse_sign				= $template_default['ellipse_sign'];
$s_module->image_render				= $crow['template']['image_render'];

$s_module->search();

// add module search results
$s_list += $s_module->search_results;

// get back final search result counter
$s_run = $s_module->search_result_entry;
