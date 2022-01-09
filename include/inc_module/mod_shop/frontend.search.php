<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.org>
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


// Module/Plug-in Shop & Products - include in frontend search

// include search specific functions and class
require_once($phpwcms['modules'][$key]['path'].'inc/frontend.search.inc.php');

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
