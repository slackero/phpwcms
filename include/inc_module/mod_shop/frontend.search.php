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


// Module/Plug-in Shop & Products - include in frontend search

// include search specific functions and class
require_once($phpwcms['modules'][$key]['path'].'inc/frontend.search.inc.php');

// initialize shop module search class
$s_module = new ModuleShopSearch();

// set current search result counter
$s_module->search_result_entry		= $s_run;
$s_module->search_words				= $content["search_word"];
$s_module->search_highlight			= $content['search']['highlight_result'];
$s_module->search_highlight_words	= $content['highlight'];
$s_module->search_wordlimit			= $content['search']['wordlimit'];
$s_module->ellipse_sign				= $template_default['ellipse_sign'];

$s_module->search();

// add module search results
$s_list += $s_module->search_results;

// get back final search result counter
$s_run = $s_module->search_result_entry;

?>