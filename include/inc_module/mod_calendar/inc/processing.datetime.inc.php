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

// now check for correct start and end date/time
$plugin['data']['calendar_start_time'] = explode(':', $plugin['data']['calendar_start_time']);
// hour
$plugin['data']['calendar_start_time'][0]	= intval($plugin['data']['calendar_start_time'][0]);
if($plugin['data']['calendar_start_time'][0] > 23) {
	$plugin['data']['calendar_start_time'][0] = 0;
}
// minutes
$plugin['data']['calendar_start_time'][1]	= empty($plugin['data']['calendar_start_time'][1]) ? 0 : intval($plugin['data']['calendar_start_time'][1]);
	if($plugin['data']['calendar_start_time'][1] > 59) {
	$plugin['data']['calendar_start_time'][1] = 0;
}
// start time
$plugin['data']['calendar_start_time'] = sprintf('%02d:%02d', $plugin['data']['calendar_start_time'][0], $plugin['data']['calendar_start_time'][1]);

// date
$plugin['data']['calendar_start_date'] = explode($BLM['date_delimiter'], $plugin['data']['calendar_start_date']);
// day
$plugin['data']['calendar_start_date'][0] = intval($plugin['data']['calendar_start_date'][0]);
if(empty($plugin['data']['calendar_start_date'][0]) || $plugin['data']['calendar_start_date'][0] > 31) {
	$plugin['data']['calendar_start_date'][0] = gmdate('d');
}
// month
$plugin['data']['calendar_start_date'][1]	= empty($plugin['data']['calendar_start_date'][1]) ? 0 : intval($plugin['data']['calendar_start_date'][1]);
if(empty($plugin['data']['calendar_start_date'][1]) || $plugin['data']['calendar_start_date'][1] > 12) {
	$plugin['data']['calendar_start_date'][1] = gmdate('m');
}
// year
$plugin['data']['calendar_start_date'][2] = empty($plugin['data']['calendar_start_date'][2]) ? 0 : intval($plugin['data']['calendar_start_date'][2]);
if(empty($plugin['data']['calendar_start_date'][2])) {
	$plugin['data']['calendar_start_date'][2] = gmdate('Y');
}


$plugin['data']['calendar_end_time'] = explode(':', $plugin['data']['calendar_end_time']);
// hour
$plugin['data']['calendar_end_time'][0]	= intval($plugin['data']['calendar_end_time'][0]);
if($plugin['data']['calendar_end_time'][0] > 23) {
	$plugin['data']['calendar_end_time'][0] = 0;
}
// minutes
$plugin['data']['calendar_end_time'][1]	= empty($plugin['data']['calendar_end_time'][1]) ? 0 : intval($plugin['data']['calendar_end_time'][1]);
	if($plugin['data']['calendar_end_time'][1] > 59) {
	$plugin['data']['calendar_end_time'][1] = 0;
}
// end time
$plugin['data']['calendar_end_time'] = sprintf('%02d:%02d', $plugin['data']['calendar_end_time'][0], $plugin['data']['calendar_end_time'][1]);

// date
$plugin['data']['calendar_end_date'] = explode($BLM['date_delimiter'], $plugin['data']['calendar_end_date']);
// day
$plugin['data']['calendar_end_date'][0]	= intval($plugin['data']['calendar_end_date'][0]);
if(empty($plugin['data']['calendar_end_date'][0]) || $plugin['data']['calendar_end_date'][0] > 31) {
	$plugin['data']['calendar_end_date'][0] = gmdate('d');
}
// month
$plugin['data']['calendar_end_date'][1]	= empty($plugin['data']['calendar_end_date'][1]) ? 0 : intval($plugin['data']['calendar_end_date'][1]);
if(empty($plugin['data']['calendar_end_date'][1]) || $plugin['data']['calendar_end_date'][1] > 12) {
	$plugin['data']['calendar_end_date'][1] = gmdate('m');
}
// year
$plugin['data']['calendar_end_date'][2]	= empty($plugin['data']['calendar_end_date'][2]) ? 0 : intval($plugin['data']['calendar_end_date'][2]);
if(empty($plugin['data']['calendar_end_date'][2])) {
	$plugin['data']['calendar_end_date'][0] = gmdate('Y');
}

// range dates

// date
$plugin['data']['calendar_range_start'] = explode($BLM['date_delimiter'], $plugin['data']['calendar_rangestart']);
// day
$plugin['data']['calendar_range_start'][0]	= intval($plugin['data']['calendar_range_start'][0]);
if(empty($plugin['data']['calendar_range_start'][0]) || $plugin['data']['calendar_range_start'][0] > 31) {
	$plugin['data']['calendar_range_start'][0] = gmdate('d');
}
// month
$plugin['data']['calendar_range_start'][1]	= empty($plugin['data']['calendar_range_start'][1]) ? 0 : intval($plugin['data']['calendar_range_start'][1]);
if(empty($plugin['data']['calendar_range_start'][1]) || $plugin['data']['calendar_range_start'][1] > 12) {
	$plugin['data']['calendar_range_start'][1] = gmdate('m');
}
// year
$plugin['data']['calendar_range_start'][2]	= empty($plugin['data']['calendar_range_start'][2]) ? 0 : intval($plugin['data']['calendar_range_start'][2]);
if(empty($plugin['data']['calendar_range_start'][2])) {
	$plugin['data']['calendar_range_start'][2] = gmdate('Y');
}

// date
$plugin['data']['calendar_range_end'] = explode($BLM['date_delimiter'], $plugin['data']['calendar_rangeend']);
// day
$plugin['data']['calendar_range_end'][0] = intval($plugin['data']['calendar_range_end'][0]);
if(empty($plugin['data']['calendar_range_end'][0]) || $plugin['data']['calendar_range_end'][0] > 31) {
	$plugin['data']['calendar_range_end'][0] = gmdate('d');
}
// month
$plugin['data']['calendar_range_end'][1] = empty($plugin['data']['calendar_range_end'][1]) ? 0 : intval($plugin['data']['calendar_range_end'][1]);
if(empty($plugin['data']['calendar_range_end'][1]) || $plugin['data']['calendar_range_end'][1] > 12) {
	$plugin['data']['calendar_range_end'][1] = gmdate('m');
}
// year
$plugin['data']['calendar_range_end'][2]	= empty($plugin['data']['calendar_range_end'][2]) ? 0 : intval($plugin['data']['calendar_range_end'][2]);
if(empty($plugin['data']['calendar_range_end'][2])) {
	$plugin['data']['calendar_range_end'][2] = gmdate('Y');
}

$plugin['data']['calendar_range_start'][0]	= sprintf('%02d', $plugin['data']['calendar_range_start'][0]);
$plugin['data']['calendar_range_start'][1]	= sprintf('%02d', $plugin['data']['calendar_range_start'][1]);
if($plugin['data']['calendar_range_start'][2] < 100) {
	$plugin['data']['calendar_range_start'][2]	= sprintf('%02d', $plugin['data']['calendar_range_start'][2]);
}
$plugin['data']['calendar_range_end'][0] = sprintf('%02d', $plugin['data']['calendar_range_end'][0]);
$plugin['data']['calendar_range_end'][1] = sprintf('%02d', $plugin['data']['calendar_range_end'][1]);
if($plugin['data']['calendar_range_end'][2] < 100) {
	$plugin['data']['calendar_range_end'][2]	= sprintf('%02d', $plugin['data']['calendar_range_end'][2]);
}

$plugin['data']['calendar_rangestart'] = implode($BLM['date_delimiter'], $plugin['data']['calendar_range_start']);
$plugin['data']['calendar_rangeend'] = implode($BLM['date_delimiter'], $plugin['data']['calendar_range_end']);

$plugin['data']['calendar_range_start']	= $plugin['data']['calendar_range_start'][2].'-'.$plugin['data']['calendar_range_start'][1].'-'.$plugin['data']['calendar_range_start'][0];
$plugin['data']['calendar_range_end'] = $plugin['data']['calendar_range_end'][2].'-'.$plugin['data']['calendar_range_end'][1].'-'.$plugin['data']['calendar_range_end'][0];

$plugin['data']['start_timestamp'] = phpwcms_strtotime($plugin['data']['calendar_range_start'], NULL, 0);
if(phpwcms_strtotime($plugin['data']['calendar_range_end'], NULL, 0) < $plugin['data']['start_timestamp']) {
	$plugin['data']['calendar_rangeend'] = $plugin['data']['start_timestamp'] + (60*60*24*7);
	$plugin['data']['calendar_range_end'] = date('Y-m-d', $plugin['data']['calendar_rangeend']);
	$plugin['data']['calendar_rangeend'] = date('d'.$BLM['date_delimiter'].'m'.$BLM['date_delimiter'].'Y', $plugin['data']['calendar_rangeend']);
}


// build start / date
$plugin['data']['calendar_start_date'][0] = sprintf('%02d', $plugin['data']['calendar_start_date'][0]);
$plugin['data']['calendar_start_date'][1] = sprintf('%02d', $plugin['data']['calendar_start_date'][1]);
if($plugin['data']['calendar_start_date'][2] < 100) {
	$plugin['data']['calendar_start_date'][2] = sprintf('%02d', $plugin['data']['calendar_start_date'][2]);
}

$plugin['data']['calendar_start']  = $plugin['data']['calendar_start_date'][2].'-'.$plugin['data']['calendar_start_date'][1].'-';
$plugin['data']['calendar_start'] .= $plugin['data']['calendar_start_date'][0].' '.$plugin['data']['calendar_start_time'].':00';

$plugin['data']['calendar_end_date'][0]	= sprintf('%02d', $plugin['data']['calendar_end_date'][0]);
$plugin['data']['calendar_end_date'][1]	= sprintf('%02d', $plugin['data']['calendar_end_date'][1]);
if($plugin['data']['calendar_end_date'][2] < 100) {
	$plugin['data']['calendar_end_date'][2]	= sprintf('%02d', $plugin['data']['calendar_end_date'][2]);
}

$plugin['data']['calendar_end']  = $plugin['data']['calendar_end_date'][2].'-'.$plugin['data']['calendar_end_date'][1].'-';
$plugin['data']['calendar_end'] .= $plugin['data']['calendar_end_date'][0].' '.$plugin['data']['calendar_end_time'].':00';

// compare start against end
$plugin['data']['start_timestamp'] = phpwcms_strtotime($plugin['data']['calendar_start'], NULL, 0);
if(phpwcms_strtotime($plugin['data']['calendar_end'], NULL, 0) < $plugin['data']['start_timestamp']) {
	$plugin['data']['calendar_end']			= $plugin['data']['start_timestamp'] + (60*30);
	$plugin['data']['calendar_end_time']	= date('H:i', $plugin['data']['calendar_end']);
	$plugin['data']['calendar_end_date'][0]	= date('d', $plugin['data']['calendar_end']);
	$plugin['data']['calendar_end_date'][1]	= date('m', $plugin['data']['calendar_end']);
	$plugin['data']['calendar_end_date'][2]	= date('Y', $plugin['data']['calendar_end']);
	$plugin['data']['calendar_end']			= date('Y-m-d H:i', $plugin['data']['calendar_end']);
}

// define new selected month based on given start date
$_SESSION['calendardate'] = $plugin['data']['calendar_start_date'][1].'-'.$plugin['data']['calendar_start_date'][2];

$plugin['data']['calendar_start_date'] = implode($BLM['date_delimiter'], $plugin['data']['calendar_start_date']);
$plugin['data']['calendar_end_date'] = implode($BLM['date_delimiter'], $plugin['data']['calendar_end_date']);
