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

//calendar specific functions
//PHPWCMS_TEMPLATE.'calendar/calendar.ini'

function initializeCalendar($ini='') {
	$_baseCalVal = getCurrentCalendarDate();
	$_calDates = ($ini && file_exists($ini)) ? parse_ini_file($ini, true) : array();
	$_baseCalVal['days'] = array();
	if(isset($_calDates[ $_baseCalVal['current_month'] ]) && is_array($_calDates[ $_baseCalVal['current_month'] ])) {
		foreach($_calDates[ $_baseCalVal['current_month'] ] as $_calDay => $calLink) {
			$_baseCalVal['days'][ strval($_baseCalVal['current_month'].substr('0'.$_calDay, -2)) ] = array($calLink, NULL, NULL);
		}
	}
	return $_baseCalVal;
}


function buildCalendarLink($date_value='') {
	global $_getVar;
	$_oldCalValue = '';
	if(!empty($_getVar['calendardate'])) $_oldCalValue = $_getVar['calendardate']; //save old value
	if(!empty($date_value)) $_getVar['calendardate'] = $date_value; //set new value
	$link = rel_url(array(), array(), '', 'urlencode'); //build Link
	if($_oldCalValue) $_getVar['calendardate'] = $_oldCalValue; //restore old value
	return $link;
}


function buildCalendarGETValue($value=array(), $spacer='-') {
	if(!is_array($value) || !count($value)) {
		$value = array('year'=>date('Y'), 'month'=>date('n'), 'day'=>date('j'));
	}
	return $value['year'].$spacer.$value['month'].$spacer.$value['day'];
}


function getCurrentCalendarDate() {

	global $_getVar;

	$_date = array();

	$_date['year']	= date('Y');
	$_date['month']	= date('n');
	$_date['day']	= date('j');

	if(!empty($_getVar['calendardate'])) {
		$d = explode('-', $_getVar['calendardate']);
		if(!empty($d[0]) && intval($d[0]))	$_date['year']	= intval($d[0]);
		if(!empty($d[1]) && intval($d[1]))	$_date['month']	= intval($d[1]);
		if(!empty($d[2]) && intval($d[2]))	$_date['day']	= intval($d[2]);
		if (($int_time = phpwcms_strtotime($_date['year'].'/'.$_date['month'].'/'.$_date['day'])) === false) {
   			$_date['year']	= date('Y');
			$_date['month']	= date('n');
			$_date['day']	= date('j');
		} else {
   			$_date['year']	= date('Y', $int_time);
			$_date['month']	= date('n', $int_time);
			$_date['day']	= date('j', $int_time);
		}
	}

	define('THIS_YEAR',		$_date['year']);
	define('THIS_MONTH',	$_date['month']);
	define('THIS_DAY',		$_date['day']);


	$_date['next_month']['month']	= ($_date['month'] == 12)				? 1						: $_date['month'] + 1;
	$_date['prev_month']['month']	= ($_date['month'] == 1)				? 12					: $_date['month'] - 1;
	$_date['next_month']['year']	= ($_date['next_month']['month'] == 1)	? $_date['year'] + 1	: $_date['year'];
	$_date['prev_month']['year']	= ($_date['prev_month']['month'] == 12)	? $_date['year'] - 1	: $_date['year'];

	$_date['next_year']['month']	= $_date['month'];
	$_date['prev_year']['month']	= $_date['month'];
	$_date['next_year']['year']		= $_date['year'] + 1;
	$_date['prev_year']['year']		= $_date['year'] - 1;

	$_date['next_month']['day']		= $_date['day'];
	$_date['prev_month']['day']		= $_date['day'];
	$_date['next_year']['day']		= $_date['day'];
	$_date['prev_year']['day']		= $_date['day'];

	$_date['next_link']	= buildCalendarLink( buildCalendarGETValue($_date['next_month']) );
	$_date['prev_link']	= buildCalendarLink( buildCalendarGETValue($_date['prev_month']) );

	$_date['current_month'] = THIS_YEAR.substr('0'.THIS_MONTH, -2);

	return $_date;

}
