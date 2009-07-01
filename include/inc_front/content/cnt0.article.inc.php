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



//default Plain Text

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/plaintext.tmpl')) {

	$crow["acontent_template"]	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/plaintext.tmpl');
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/plaintext/'.$crow["acontent_template"])) {

	$crow["acontent_template"]	= @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/plaintext/'.$crow["acontent_template"]);

} else {

	$crow["acontent_template"]	= '[TITLE]<h4>{TITLE}</h4>'.LF.'[/TITLE][SUBTITLE]<h5>{SUBTITLE}</h5>'.LF.'[/SUBTITLE][TEXT]<p>{TEXT}</p>[/TEXT]';

}

$crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'TITLE', html_specialchars($crow['acontent_title']));
$crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
$crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'TEXT', plaintext_htmlencode($crow['acontent_text']));

$CNT_TMP .= LF.trim($crow["acontent_template"]).LF;
									
?>