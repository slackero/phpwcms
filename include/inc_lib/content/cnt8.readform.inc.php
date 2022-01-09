<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
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



// Content Type Link Articles

$content['alink']['alink_template']		= clean_slweg($_POST["calink_template"]);
$content['alink']['alink_allowedtags']	= slweg($_POST["calink_allowedtags"]);
$content['alink']['alink_id']			= (isset($_POST["calink"]) && is_array($_POST["calink"])) ? $_POST["calink"] : array();
$content['alink']['alink_level']		= (isset($_POST["calink_level"]) && is_array($_POST["calink_level"])) ? $_POST["calink_level"] : array();

// article select type
$content['alink']['alink_type'] = abs(intval($_POST['calink_type']));
if($content['alink']['alink_type'] > 27) {
	$content['alink']['alink_type'] = 0;
}

// summary wordlimit
$content['alink']['alink_wordlimit']	= intval($_POST['calink_wordlimit']);
$content['alink']['alink_hidesummary']	= empty($_POST['calink_hidesummary']) ? 0 : 1;

// handle teaser for columns
$content['alink']['alink_columns']		= empty($_POST['calink_columns']) ? 0 : intval($_POST['calink_columns']);

// link against structure level link for single articles
$content['alink']['alink_categoryalias'] = empty($_POST['calink_categoryalias']) ? 0 : 1;

// max auto article
$content['alink']['alink_max']			= intval($_POST['calink_max']);

// image settings
$content['alink']['alink_width']	= intval($_POST['calink_width']);
$content['alink']['alink_height']	= intval($_POST['calink_height']);
$content['alink']['alink_zoom']		= empty($_POST['calink_zoom']) ? 0 : 1;
$content['alink']['alink_unique']	= empty($_POST['calink_unique']) ? 0 : 1;
$content['alink']['alink_crop']		= empty($_POST['calink_crop']) ? 0 : 1;
$content['alink']['alink_prio']		= empty($_POST['calink_prio']) ? 0 : 1;
$content['alink']['alink_andor']	= (empty($_POST['calink_andor']) || !in_array($_POST['calink_andor'], array('OR', 'AND', 'NOT', 'NOR'))) ? 'OR' : $_POST['calink_andor'];
$content['alink']['alink_category'] = convertStringToArray( clean_slweg($_POST['calink_category']) );

if(empty($content['alink']['alink_width'])) $content['alink']['alink_width'] = '';
if(empty($content['alink']['alink_height'])) $content['alink']['alink_height'] = '';
if(empty($content['alink']['alink_wordlimit'])) $content['alink']['alink_wordlimit'] = '';
if(empty($content['alink']['alink_max'])) $content['alink']['alink_max'] = '';


foreach($content['alink']['alink_id'] as $key => $value) {
	$value = intval($value);
	if($value) {
		$content['alink']['alink_id'][$key] = $value;
	} else {
		unset($content['alink']['alink_id'][$key]);
	}
}
