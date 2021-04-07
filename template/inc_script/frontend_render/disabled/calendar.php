<?php
// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// used to get a calendar

if (strpos($content["all"], '{CALENDAR') !== false) {

    include_once CMSGO_ROOT . '/include/inc_ext/php_calendar.php';
    include_once CMSGO_ROOT . '/include/inc_front/calendar.func.inc.php';

    $_baseCalVal = initializeCalendar(CMSGO_TEMPLATE . 'calendar/calendar.ini');

    $content['all'] = str_replace(
        '{CALENDAR}',
        generate_calendar(array(
            'locale' => 'de_DE',
            'day_name_length' => 2,
            'weekNrTitle' => 'KW',
            'days' => $_baseCalVal['days'],
            'pn' => array(
                '&laquo;' => $_baseCalVal['prev_link'],
                '&raquo;' => $_baseCalVal['next_link'],
            )
        )),
        $content['all']
    );

}
