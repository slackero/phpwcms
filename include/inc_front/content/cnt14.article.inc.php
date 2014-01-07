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



// WYSIWYG

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/wysiwyg.tmpl')) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/wysiwyg.tmpl') );
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/wysiwyg/'.$crow["acontent_template"])) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/wysiwyg/'.$crow["acontent_template"]) );

} else {

	$crow["acontent_template"]	= '[TITLE]<h3>{TITLE}</h3>'.LF.'[/TITLE][SUBTITLE]<h4>{SUBTITLE}</h4>'.LF.'[/SUBTITLE][TEXT]{TEXT}[/TEXT]';

}

$crow["acontent_template"]	 = render_cnt_template($crow["acontent_template"], 'TITLE', html_specialchars($crow['acontent_title']));
$crow["acontent_template"]	 = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));
$CNT_TMP					.= render_cnt_template($crow["acontent_template"], 'TEXT', $crow['acontent_html']);
									
?>