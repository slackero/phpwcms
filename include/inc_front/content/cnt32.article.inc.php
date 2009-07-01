<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.

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


// tabs

$tabs			= array();
$tabs['tabs']	= @unserialize($crow["acontent_form"]);

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/tabs.tmpl')) {

	$tabs['template']	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/tabs.tmpl');
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/tabs/'.$crow["acontent_template"])) {

	$tabs['template']	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/tabs/'.$crow["acontent_template"]);

} else {

	$tabs['template']	= '';

}

if($tabs['template']) {

	$tabs['entries']	= array();

	$tabs['tmpl_entry']	= get_tmpl_section('TABS_ENTRY', $tabs['template']);
	$tabs['template']	= get_tmpl_section('TABS', $tabs['template']);
	
	foreach($tabs['tabs'] as $key => $entry) {
	
		$tabs['entries'][$key] = render_cnt_template($tabs['tmpl_entry'], 'TABTITLE', html_specialchars($entry['tabtitle']));
		$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABCONTENT', trim($entry['tabheadline'].$entry['tabtext']) == '' ? '' : LF);
		$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABHEADLINE', html_specialchars($entry['tabheadline']));
		$tabs['entries'][$key] = render_cnt_template($tabs['entries'][$key], 'TABTEXT', $entry['tabtext']);
	
	}

	$tabs['template']	= render_cnt_template($tabs['template'], 'TITLE', html_specialchars($crow['acontent_title']));
	$tabs['template']	= render_cnt_template($tabs['template'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
	$tabs['template']	= render_cnt_template($tabs['template'], 'TABS_ENTRIES', count($tabs['entries']) ? implode('', $tabs['entries']) : '' );


	$CNT_TMP .= str_replace('{ID}', $crow['acontent_id'], $tabs['template']);

} else {
	
	$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);	
	$CNT_TMP .= LF . $crow["acontent_html"];
	
}

unset($tabs);

?>