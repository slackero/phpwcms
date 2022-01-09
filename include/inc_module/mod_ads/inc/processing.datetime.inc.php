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
$plugin['data']['adcampaign_time_start']		= explode(':', $plugin['data']['adcampaign_time_start']);
// hour
$plugin['data']['adcampaign_time_start'][0]	= intval($plugin['data']['adcampaign_time_start'][0]);
if($plugin['data']['adcampaign_time_start'][0] > 23) {
	$plugin['data']['adcampaign_time_start'][0] = 0;
}
// minutes
$plugin['data']['adcampaign_time_start'][1]	= empty($plugin['data']['adcampaign_time_start'][1]) ? 0 : intval($plugin['data']['adcampaign_time_start'][1]);
	if($plugin['data']['adcampaign_time_start'][1] > 59) {
	$plugin['data']['adcampaign_time_start'][1] = 0;
}
// start time
$plugin['data']['adcampaign_time_start'] 		= sprintf('%02d:%02d', $plugin['data']['adcampaign_time_start'][0], $plugin['data']['adcampaign_time_start'][1]);

// date
$plugin['data']['adcampaign_date_start']		= explode($BLM['date_delimiter'], $plugin['data']['adcampaign_date_start']);
// day
$plugin['data']['adcampaign_date_start'][0]	= intval($plugin['data']['adcampaign_date_start'][0]);
if(empty($plugin['data']['adcampaign_date_start'][0]) || $plugin['data']['adcampaign_date_start'][0] > 31) {
	$plugin['data']['adcampaign_date_start'][0] = gmdate('d');
}
// month
$plugin['data']['adcampaign_date_start'][1]	= empty($plugin['data']['adcampaign_date_start'][1]) ? 0 : intval($plugin['data']['adcampaign_date_start'][1]);
if(empty($plugin['data']['adcampaign_date_start'][1]) || $plugin['data']['adcampaign_date_start'][1] > 12) {
	$plugin['data']['adcampaign_date_start'][1] = gmdate('m');
}
// year
$plugin['data']['adcampaign_date_start'][2]	= empty($plugin['data']['adcampaign_date_start'][2]) ? 0 : intval($plugin['data']['adcampaign_date_start'][2]);
if(empty($plugin['data']['adcampaign_date_start'][2])) {
	$plugin['data']['adcampaign_date_start'][2] = gmdate('Y');
}



$plugin['data']['adcampaign_time_end']		= explode(':', $plugin['data']['adcampaign_time_end']);
// hour
$plugin['data']['adcampaign_time_end'][0]	= intval($plugin['data']['adcampaign_time_end'][0]);
if($plugin['data']['adcampaign_time_end'][0] > 23) {
	$plugin['data']['adcampaign_time_end'][0] = 0;
}
// minutes
$plugin['data']['adcampaign_time_end'][1]	= empty($plugin['data']['adcampaign_time_end'][1]) ? 0 : intval($plugin['data']['adcampaign_time_end'][1]);
	if($plugin['data']['adcampaign_time_end'][1] > 59) {
	$plugin['data']['adcampaign_time_end'][1] = 0;
}
// start time
$plugin['data']['adcampaign_time_end'] 		= sprintf('%02d:%02d', $plugin['data']['adcampaign_time_end'][0], $plugin['data']['adcampaign_time_end'][1]);

// date
$plugin['data']['adcampaign_date_end']		= explode($BLM['date_delimiter'], $plugin['data']['adcampaign_date_end']);
// day
$plugin['data']['adcampaign_date_end'][0]	= intval($plugin['data']['adcampaign_date_end'][0]);
if(empty($plugin['data']['adcampaign_date_end'][0]) || $plugin['data']['adcampaign_date_end'][0] > 31) {
	$plugin['data']['adcampaign_date_end'][0] = gmdate('d');
}
// month
$plugin['data']['adcampaign_date_end'][1]	= empty($plugin['data']['adcampaign_date_end'][1]) ? 0 : intval($plugin['data']['adcampaign_date_end'][1]);
if(empty($plugin['data']['adcampaign_date_end'][1]) || $plugin['data']['adcampaign_date_end'][1] > 12) {
	$plugin['data']['adcampaign_date_end'][1] = gmdate('m');
}
// year
$plugin['data']['adcampaign_date_end'][2]	= empty($plugin['data']['adcampaign_date_end'][2]) ? 0 : intval($plugin['data']['adcampaign_date_end'][2]);
if(empty($plugin['data']['adcampaign_date_end'][2])) {
	$plugin['data']['adcampaign_date_end'][2] = gmdate('Y');
}


// build start / date
$plugin['data']['adcampaign_date_start'][0]	= sprintf('%02d', $plugin['data']['adcampaign_date_start'][0]);
$plugin['data']['adcampaign_date_start'][1]	= sprintf('%02d', $plugin['data']['adcampaign_date_start'][1]);
if($plugin['data']['adcampaign_date_start'][2] < 100) {
	$plugin['data']['adcampaign_date_start'][2]	= sprintf('%02d', $plugin['data']['adcampaign_date_start'][2]);
}

$plugin['data']['adcampaign_datestart']  = $plugin['data']['adcampaign_date_start'][2].'-'.$plugin['data']['adcampaign_date_start'][1].'-';
$plugin['data']['adcampaign_datestart'] .= $plugin['data']['adcampaign_date_start'][0].' '.$plugin['data']['adcampaign_time_start'].':00';


$plugin['data']['adcampaign_date_end'][0]	= sprintf('%02d', $plugin['data']['adcampaign_date_end'][0]);
$plugin['data']['adcampaign_date_end'][1]	= sprintf('%02d', $plugin['data']['adcampaign_date_end'][1]);
if($plugin['data']['adcampaign_date_end'][2] < 100) {
	$plugin['data']['adcampaign_date_end'][2]	= sprintf('%02d', $plugin['data']['adcampaign_date_end'][2]);
}

$plugin['data']['adcampaign_dateend']  = $plugin['data']['adcampaign_date_end'][2].'-'.$plugin['data']['adcampaign_date_end'][1].'-';
$plugin['data']['adcampaign_dateend'] .= $plugin['data']['adcampaign_date_end'][0].' '.$plugin['data']['adcampaign_time_end'].':00';

// compare start against end
$plugin['data']['start_timestamp'] = strtotime($plugin['data']['adcampaign_datestart']);
if(strtotime($plugin['data']['adcampaign_dateend']) < $plugin['data']['start_timestamp']) {
	$plugin['data']['adcampaign_dateend']		= $plugin['data']['start_timestamp'] + (7*24*60*60);
	$plugin['data']['adcampaign_time_end']		= date('H:i', $plugin['data']['adcampaign_dateend']);
	$plugin['data']['adcampaign_date_end'][0]	= date('d', $plugin['data']['adcampaign_dateend']);
	$plugin['data']['adcampaign_date_end'][1]	= date('m', $plugin['data']['adcampaign_dateend']);
	$plugin['data']['adcampaign_date_end'][2]	= date('Y', $plugin['data']['adcampaign_dateend']);
	$plugin['data']['adcampaign_dateend']		= date('Y-m-d H:i', $plugin['data']['adcampaign_dateend']);
}

$plugin['data']['adcampaign_date_start']	= implode($BLM['date_delimiter'], $plugin['data']['adcampaign_date_start']);
$plugin['data']['adcampaign_date_end']		= implode($BLM['date_delimiter'], $plugin['data']['adcampaign_date_end']);
