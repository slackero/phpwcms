<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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



//link list
// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/linklist.tmpl')) {

	$crow["acontent_template"]	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/linklist.tmpl');
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/linklist/'.$crow["acontent_template"])) {

	$crow["acontent_template"]	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/linklist/'.$crow["acontent_template"]);

} else {

	$crow["acontent_template"]	= '<!--LINKLIST_START//-->
[TITLE]<h4>{TITLE}</h4>
[/TITLE][SUBTITLE]<h5>{SUBTITLE}</h5>
[/SUBTITLE][LINKLIST]
<ul class="linklist">
{LINKLIST}
</ul>
[/LINKLIST]<!--LINKLIST_END//-->
<!--LINKLIST_ENTRY_START//-->[LINK]	<li><a href="{LINK}"{TARGET}>{LINKNAME}</a></li>[/LINK]<!--LINKLIST_ENTRY_END//-->
<!--LINKLIST_SPACE_START//-->
<!--LINKLIST_SPACE_END//-->';

}

$content['linklist']		= get_tmpl_section('LINKLIST', $crow["acontent_template"]);
$content['linklist_entry']	= get_tmpl_section('LINKLIST_ENTRY', $crow["acontent_template"]);
$content['linklist_space']	= get_tmpl_section('LINKLIST_SPACE', $crow["acontent_template"]);

$content['linklist'] = str_replace('{ID}', $crow['acontent_id'], $content['linklist']);
$content['linklist'] = render_cnt_template($content['linklist'], 'TITLE', html_specialchars($crow['acontent_title']));
$content['linklist'] = render_cnt_template($content['linklist'], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));

$link  = explode(LF, $crow["acontent_text"]);

if(count($link)) {
	$tmp = array();
	foreach($link as $key => $value) {

		list($link["name"], $link["link"])   = explode("|", $value);

		$link["link"]	= explode(' ', $link["link"]);
		$link["target"]	= empty($link["link"][1]) ? '' : trim($link["link"][1]);
		$link["link"]	= trim($link["link"][0]);
		
		$tmp[$key] = render_cnt_template($content['linklist_entry'], 'LINK', html_specialchars($link["link"]));
		$tmp[$key] = str_replace('{TARGET}', $link["target"] ? ' target="'.$link["target"].'"' : '', $tmp[$key]);
		$tmp[$key] = str_replace('{LINKNAME}', html_specialchars( $link["name"] ? $link["name"] : $link["link"] ), $tmp[$key]);

	}
	
	$content['linklist'] = render_cnt_template($content['linklist'], 'LINKLIST', implode($content['linklist_space'], $tmp));
	
}

$CNT_TMP .= $content['linklist'];
									
?>