<?php
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// used to get a calendar

if (strpos($content["all"], '{CALENDAR') !== false) {

    include_once PHPWCMS_ROOT . '/include/inc_ext/php_calendar.php';
    include_once PHPWCMS_ROOT . '/include/inc_front/calendar.func.inc.php';

    $_baseCalVal = initializeCalendar(PHPWCMS_TEMPLATE . 'calendar/calendar.ini');

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
            ),)
        ),
        $content['all']
    );

}
