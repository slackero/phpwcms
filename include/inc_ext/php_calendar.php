<?php
/**
 * PHP Calendar (version 2.3), written by Keith Devens
 * http://keithdevens.com/software/php_calendar
 * see example at http://keithdevens.com/weblog
 * License: http://keithdevens.com/software/license
 *
 * enhanced by Oliver Georgi for phpwcms
 * - 2020-04-15: refactored, solve deprecated functions
 **/

function generate_calendar($param = array()) {
    if (!defined('THIS_YEAR')) {
        define('THIS_YEAR', date('Y'));
    }
    if (!defined('THIS_MONTH')) {
        define('THIS_MONTH', date('n'));
    }
    if (!defined('THIS_DAY')) {
        define('THIS_DAY', date('j'));
    }

    $year            = empty($param['year']) ? THIS_YEAR : $param['year'];
    $month           = empty($param['month']) ? THIS_MONTH : $param['month'];
    $day_name_length = empty($param['day_name_length']) ? 3 : $param['day_name_length'];
    $month_href      = empty($param['month_href']) ? NULL : $param['month_href'];
    $first_day       = empty($param['first_day']) ? 0 : $param['first_day'];
    $weekNr          = empty($param['weekNr']) ? TRUE : $param['weekNr'];
    $weekNrTitle     = empty($param['weekNrTitle']) ? 'Wno' : $param['weekNrTitle'];
    $styleAdd        = empty($param['styleAdd']) ? '' : $param['styleAdd'];
    $pn              = isset($param['pn']) && is_array($param['pn']) ? $param['pn'] : array();
    $days            = isset($param['days']) && is_array($param['days']) ? $param['days'] : array();
    $locale          = empty($param['locale']) ? FALSE : $param['locale'];

    // set correct locale
    if ($locale) {
        $_oldLocale = setlocale(LC_TIME, NULL); //save current locale
        setlocale(LC_TIME, $locale);
    }

    $first_of_month = gmmktime(0, 0, 0, $month, 1, $year);
    //remember that mktime will automatically correct if invalid dates are entered
    // for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
    // this provides a built in "rounding" feature to generate_calendar()

    $day_names = array(); //generate all the day names according to the current locale
    for ($n = 0, $t = (3 + $first_day) * 86400; $n < 7; $n++, $t += 86400) {//January 4, 1970 was a Sunday
        $day_names[$n] = ucfirst(gmstrftime('%A', $t)); //%A means full textual day name
    }

    @list($month, $year, $month_name, $weekday) = explode(',', gmstrftime('%m,%Y,%B,%w', $first_of_month));
    $weekday = ($weekday + 7 - $first_day) % 7; //adjust for $first_day
    $title   = htmlentities(ucfirst($month_name)) . '&nbsp;' . $year;  //note that some locales don't capitalize month and day names
    $YYYYmm  = $year . $month;

    //Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03

    //previous and next links, if applicable
    $p = '';
    $n = '';
    $pnCount = count($pn);
    if ($pnCount) {
        reset($pn);
        $p = key($pn);
        if ($p) {
            $pl = current($pn);
            $p = '<span class="calendarPrev' . $styleAdd . '">' . ($pl ? '<a href="' . htmlspecialchars($pl) . '">' . $p . '</a>' : $p) . '</span>&nbsp;';
        }
        if ($pnCount > 1) {
            next($pn);
            $n = key($pn);
            if ($n) {
                $nl = current($pn);
                $n = '&nbsp;<span class="calendarNext' . $styleAdd . '">' . ($nl ? '<a href="' . htmlspecialchars($nl) . '">' . $n . '</a>' : $n) . '</span>';
            }
        }
    }

    $calendar = '<table class="calendar' . $styleAdd . '" summary="Calendar">';
    $calendar .= '<tr>';
    $calendar .= '<td colspan="' . ($weekNr ? '8' : '7') . '" align="center" class="calendarMonth' . $styleAdd . '"><strong>';
    $calendar .= $p;
    $calendar .= $month_href ? '<a href="' . htmlspecialchars($month_href) . '">' . $title . '</a>' : $title;
    $calendar .= $n;
    $calendar .= '</strong></td></tr><tr>';

    if ($weekNr) {
        $calendar  .= '<td class="calendarWeekNoTitle' . $styleAdd . '">' . $weekNrTitle . '</td>';
        $weekStart = date('W', $first_of_month);
    }

    //if the day names should be shown ($day_name_length > 0)
    if ($day_name_length) {
        //if day_name_length is >3, the full name of the day will be printed
        foreach ($day_names as $d) {
            $calendar .= '<td class="calendarDayName' . $styleAdd . '">' . htmlentities($day_name_length < 4 ? substr($d, 0, $day_name_length) : $d) . '</td>';
        }
        $calendar .= '</tr><tr>';
    }

    if ($weekday > 0) {
        if ($weekNr) {
            $calendar .= '<td class="calendarWeek' . $styleAdd . '">' . $weekStart . '</td>';
            $weekStart++;
        }
        $calendar .= '<td colspan="' . $weekday . '">&nbsp;</td>'; //initial 'empty' days
    }
    for ($day = 1, $days_in_month = gmdate('t', $first_of_month); $day <= $days_in_month; $day++, $weekday++) {
        if ($weekday == 7) {
            $weekday = 0; //start a new week
            $calendar .= '</tr><tr>';
        } elseif (!$weekday && $weekNr) {
            $calendar .= '<td class="calendarWeek' . $styleAdd . '">' . $weekStart . '</td>';
            $weekStart++;
        }

        $thisSelected = intval($year) == THIS_YEAR && intval($month) == THIS_MONTH && $day == THIS_DAY;

        $checkDay = $YYYYmm . substr('0' . $day, -2);
        if (isset($days[$checkDay]) && is_array($days[$checkDay])) {
            @list($link, $classes, $content) = $days[$checkDay];
            @list($link, $target) = explode(' ', trim($link));
            $target = $target ? ' target="' . $target . '"' : '';
            if (is_null($content)) {
                $content = $day;
            }
            if ($thisSelected) {
                $content = '<span class="calendarSelectedDay' . $styleAdd . '">' . $content . '</span>';
            }
            $calendar .= '<td';
            if ($classes) {
                $calendar .= ' class="' . htmlspecialchars($classes) . '"';
            }
            $calendar .= '>';
            $calendar .= $link ? '<a href="' . htmlspecialchars($link) . '"' . $target . '>' . $content . '</a>' : $content;
            $calendar .= '</td>';
        }
        else {
            $calendar .= $thisSelected ? '<td class="calendarSelectedDay' . $styleAdd . '">' : '<td>';
            $calendar .= $day . '</td>';
        }
    }
    if ($weekday != 7) {
        $calendar .= '<td colspan="' . (7 - $weekday) . '">&nbsp;</td>'; //remaining "empty" days
    }

    if ($locale) {
        setlocale(LC_TIME, $_oldLocale); //switch current locale back to old value
    }

    return $calendar . '</tr></table>';
}

function tzdelta($iTime = 0) {
    if (!$iTime) {
        $iTime = time();
    }
    $ar = localtime($iTime);
    $ar[5] += 1900;
    $ar[4]++;
    $iTztime = gmmktime($ar[2], $ar[1], $ar[0], $ar[4], $ar[3], $ar[5], $ar[8]);
    return ($iTztime - $iTime);
}
