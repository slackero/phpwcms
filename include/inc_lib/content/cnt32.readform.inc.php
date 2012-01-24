<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
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