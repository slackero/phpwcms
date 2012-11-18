<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2012, Oliver Georgi
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



//code

/*
$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);
if($crow["acontent_text"]) {
	$crow["acontent_text"] = str_replace(" ", "&nbsp;", html_specialchars($crow["acontent_text"]));
	$CNT_TMP .= nl2br(div_class($crow["acontent_text"], $template_default["article"]["code_class"]));
}
*/

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/code.tmpl')) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/code.tmpl') );
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/code/'.$crow["acontent_template"])) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/code/'.$crow["acontent_template"]) );

} else {

	$crow["acontent_template"]	= '[TITLE]<h3>{TITLE}</h3>[/TITLE][SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE][CODE]<pre>{CODE}</pre>[/CODE]';

}


$crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'TITLE', html_entities($crow['acontent_title']));
$crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html_entities($crow['acontent_subtitle']));
$crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'CODE', nl2br( str_replace(' ', '&nbsp;', html_entities($crow["acontent_text"]) ) ) );

$CNT_TMP .= LF.$crow["acontent_template"].LF;


?>