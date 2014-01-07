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



//default Plain Text

// read template
if(empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_default/plaintext.tmpl')) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_default/plaintext.tmpl') );
	
} elseif(is_file(PHPWCMS_TEMPLATE.'inc_cntpart/plaintext/'.$crow["acontent_template"])) {

	$crow["acontent_template"]	= render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/plaintext/'.$crow["acontent_template"]) );

} else {

	$crow["acontent_template"]	= '[TITLE]<h4>{TITLE}</h4>'.LF.'[/TITLE][SUBTITLE]<h5>{SUBTITLE}</h5>'.LF.'[/SUBTITLE][TEXT]<p>{TEXT}</p>[/TEXT]';

}

$crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'TITLE', html_specialchars($crow['acontent_title']));
$crow["acontent_template"]  = render_cnt_template($crow["acontent_template"], 'SUBTITLE', html_specialchars($crow['acontent_subtitle']));

$crow["acontent_form"]		= @unserialize($crow["acontent_form"]);
$crow["acontent_form"]		= isset($crow["acontent_form"]['ctext_format']) ? $crow["acontent_form"]['ctext_format'] : 'plain';

switch($crow["acontent_form"]) {
	
	case 'markdown':
		// Load MarkDown function and class
		require_once(PHPWCMS_ROOT.'/include/inc_ext/markdown.php');
		$crow['acontent_text'] = Markdown($crow['acontent_text']);
		break;
	
	case 'textile':
		// Load Textile function and class
		require_once(PHPWCMS_ROOT.'/include/inc_ext/classTextile.php');
		if(!isset($phpwcms['textile'])) {
			$phpwcms['textile'] = new Textile();
		}
		$crow['acontent_text'] = $phpwcms['textile']->TextileThis($crow['acontent_text']);
		break;
	
	case 'plain':
		$crow['acontent_text'] = plaintext_htmlencode($crow['acontent_text']);
		break;

}

$crow["acontent_template"] = render_cnt_template($crow["acontent_template"], 'TEXT', $crow['acontent_text']);


$CNT_TMP .= LF.trim($crow["acontent_template"]).LF;
									
?>