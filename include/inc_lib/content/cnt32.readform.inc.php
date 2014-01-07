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


// Content Type Tabs
$content["tabs_template"]	= clean_slweg($_POST['template']);
$content["tabs"]			= array();
$content['search']			= '';
$content['html']			= array();
$content['tabwysiwygoff']	= empty($_POST['tabwysiwygoff']) ? 0 : 1;

// get all tabs
if(isset($_POST['tabtitle']) && is_array($_POST['tabtitle']) && count($_POST['tabtitle'])) {

	$x = 0;

	foreach($_POST['tabtitle'] as $key => $value) {
		
		$content["tabs"][$x]['tabtitle'] = clean_slweg($value);
		if($content["tabs"][$x]['tabtitle'] == '') {
			$content["tabs"][$x]['tabtitle'] = $BL['be_tab_name'].' #'.($x+1);
		}
		$content["tabs"][$x]['tabheadline'] = empty($_POST['tabheadline'][$key]) ? '' : clean_slweg($_POST['tabheadline'][$key]);
		$content["tabs"][$x]['tabtext']		= empty($_POST['tabtext'][$key]) ? '' : slweg($_POST['tabtext'][$key]);
		
		$content['search'] .= strip_tags( trim( $content["tabs"][$x]['tabtitle'].' '.$content["tabs"][$x]['tabheadline'].' '.$content["tabs"][$x]['tabtext'] ) ).' ';
		
		
		$content['html'][] = '	<dt>'.html_specialchars($content["tabs"][$x]['tabtitle']).'</dt>';
		$content['html'][] = '	<dd>';
		if($content["tabs"][$x]['tabheadline']) {
			$content['html'][] = '		<h3>'.html_specialchars($content["tabs"][$x]['tabheadline']).'</h3>';
		}
		if(!$content['tabwysiwygoff'] && strpos($content["tabs"][$x]['tabtext'], '<') === false) {
			$content["tabs"][$x]['tabtext'] = plaintext_htmlencode($content["tabs"][$x]['tabtext']);
			$content['html'][] = '		'.$content["tabs"][$x]['tabtext'];
		}
		$content['html'][] = '	</dd>';

		$x++;

	}
}
$content['search']	= trim($content['search']);

if(count($content['html'])) {
	$content['html'] = '<dl>' . LF . implode(LF, $content['html']) . LF . '</dl>';
} else {
	$content['html'] = '';
}

$content['tabs']['tabwysiwygoff'] = $content['tabwysiwygoff'];

?>