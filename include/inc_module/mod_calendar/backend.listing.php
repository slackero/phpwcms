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

// include calendar functions
include_once $phpwcms['modules'][$module]['path'].'inc/functions.inc.php';

// OK lets switch language :)
// set correct locale
if(!empty($BLM['locale_string'])) {
    $_oldLocale = setlocale(LC_TIME, NULL); //save current locale
    setlocale(LC_TIME, $BLM['locale_string']);
}

$_entry['query']            = '';

// define some defaults
if(isset($_GET['calendardate'])) {

    $_SESSION['calendardate'] = substr(clean_slweg($_GET['calendardate']), 0, 7);

}
if(!empty($_SESSION['calendardate'])) {

    @list($plugin['current_month'], $plugin['current_year']) = explode('-', $_SESSION['calendardate']);

    $plugin['current_month']    = intval($plugin['current_month']);
    $plugin['current_year']     = intval($plugin['current_year']);

    if(empty($plugin['current_year'])) {
        $plugin['current_year']     = gmdate('Y');
    }
    if(empty($plugin['current_month'])) {
        $plugin['current_month']        = gmdate('n');
    }

} else {

    $plugin['current_year']     = gmdate('Y');
    $plugin['current_month']    = gmdate('n');

}

$plugin['first_of_month']   = gmmktime(0, 0, 0, $plugin['current_month'], 1, $plugin['current_year']);
$plugin['days_in_month']    = gmdate('t', $plugin['first_of_month']);
$plugin['week_start']       = date('W', $plugin['first_of_month']);
$plugin['first_day']        = 0;
$plugin['weekday']          = (intval(gmstrftime('%w', $plugin['first_of_month'])) + 7 - $plugin['first_day']) % 7; //adjust for $first_day
$plugin['this_date']        = html(ucfirst(gmstrftime('%B %Y', $plugin['first_of_month'])), false);

$plugin['location']         = decode_entities(MODULE_HREF);
$plugin['loc_this_month']   = $plugin['location'].'&calendardate='.date('m-Y');

$plugin['loc_next_month']   = $plugin['location'].'&calendardate=';
if($plugin['current_month'] == 12) {
    $plugin['loc_next_month'] .= '1-'.($plugin['current_year']+1);
} else {
    $plugin['loc_next_month'] .= ($plugin['current_month']+1).'-'.$plugin['current_year'];
}
$plugin['loc_prev_month']   = $plugin['location'].'&calendardate=';
if($plugin['current_month'] == 1) {
    $plugin['loc_prev_month'] .= '12-'.($plugin['current_year']-1);
} else {
    $plugin['loc_prev_month'] .= ($plugin['current_month']-1).'-'.$plugin['current_year'];
}
$plugin['week_add']         = intval(gmstrftime('%W', gmmktime(0, 0, 0, 1, 1, $plugin['current_year']))) ? 0 : 1;

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

    $_SESSION['list_active']    = empty($_POST['showactive']) ? 0 : 1;
    $_SESSION['list_inactive']  = empty($_POST['showinactive']) ? 0 : 1;

    $_SESSION['filter_calendar'] = clean_slweg($_POST['filter']);
    if(empty($_SESSION['filter_calendar'])) {
        unset($_SESSION['filter_calendar']);
    } else {
        $_SESSION['filter_calendar'] = convertStringToArray($_SESSION['filter_calendar'], ' ');
        $_POST['filter'] = $_SESSION['filter_calendar'];
    }

}


$_entry['list_active']      = isset($_SESSION['list_active'])   ? $_SESSION['list_active']      : 1;
$_entry['list_inactive']    = isset($_SESSION['list_inactive']) ? $_SESSION['list_inactive']    : 1;


// set correct status query
if($_entry['list_active'] != $_entry['list_inactive']) {

    if(!$_entry['list_active']) {
        $_entry['query'] .= 'calendar_status=0';
    }
    if(!$_entry['list_inactive']) {
        $_entry['query'] .= 'calendar_status=1';
    }

} else {
    $_entry['query'] .= 'calendar_status!=9';
}

if(isset($_SESSION['filter_calendar']) && is_array($_SESSION['filter_calendar']) && count($_SESSION['filter_calendar'])) {

    $_entry['filter_array'] = array();

    foreach($_SESSION['filter_calendar'] as $_entry['filter']) {
        //usr_name, usr_login, usr_email
        $_entry['filter_array'][] = "CONCAT(calendar_title, calendar_tag, calendar_text) LIKE '%".aporeplace($_entry['filter'])."%'";
    }
    if(count($_entry['filter_array'])) {

        $_SESSION['filter_calendar'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
        $_entry['query'] .= $_SESSION['filter_calendar'];

    }

} elseif(isset($_SESSION['filter_calendar']) && is_string($_SESSION['filter_calendar'])) {

    $_entry['query'] .= $_SESSION['filter_calendar'];

}



?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['listing_title'] ?></h1>

<!-- <form action="<?php echo MODULE_HREF ?>" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" /> -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="paginate" summary="">
    <tr>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>

            <!--
                <td><input type="checkbox" name="showactive" id="showactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_active'], 1) ?> /></td>
                <td><label for="showactive"><img src="img/button/aktiv_12x13_1.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>
                <td><input type="checkbox" name="showinactive" id="showinactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_inactive'], 1) ?> /></td>
                <td><label for="showinactive"><img src="img/button/aktiv_12x13_0.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>

                <td class="chatlist">|&nbsp;</td>

                <td><input type="search" name="filter" id="filter" size="10" value="<?php

                if(isset($_POST['filter']) && is_array($_POST['filter']) ) {
                    echo html(implode(' ', $_POST['filter']));
                }

                ?>" class="textinput" style="margin:0 2px 0 0;width:110px;text-align:left;" title="filter results by username, name or email" /></td>
                <td><input type="image" name="gofilter" src="img/famfamfam/action_go.gif" style="margin-right:3px;" /></td>

                <td class="chatlist">|&nbsp;</td>
            // -->
                <td class="calendarButton"><button onclick="location.href='<?php echo $plugin['loc_prev_month'] ?>';return false;">&lt;</button></td>
                <td class="calendarButton"><button onclick="location.href='<?php echo $plugin['loc_this_month'] ?>';return false;"><?php echo $BLM['today'] ?></button></td>
                <td class="calendarButton"><button onclick="location.href='<?php echo $plugin['loc_next_month'] ?>';return false;">&gt;</button></td>

            </tr>
        </table></td>

    <td class="chatlist" align="right">&nbsp;

    </td>

    </tr>
</table>
<!-- </form> -->

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="calendar" summary="">

<?php

// list current calendar here

if($plugin['current_month'] == 12) {
    $plugin['end_month']    = 1;
    $plugin['end_year']     = $plugin['current_year'] + 1;
} else {
    $plugin['end_month']    = $plugin['current_month'] + 1;
    $plugin['end_year']     = $plugin['current_year'];
}

$sql  = 'SELECT *, ';
$sql .= "DATE_FORMAT(calendar_start, '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS calendar_start_date, ";
$sql .= "DATE_FORMAT(calendar_end,   '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS calendar_end_date, ";
$sql .= "DATE_FORMAT(calendar_start, '%H:%i') AS calendar_start_time, ";
$sql .= "DATE_FORMAT(calendar_end,   '%H:%i') AS calendar_end_time, ";
$sql .= "DATE_FORMAT(calendar_start,   '%e') AS calendar_day ";
$sql .= ' FROM '.DB_PREPEND.'phpwcms_calendar WHERE ';
$sql .= 'calendar_status != 9 AND ';
$sql .= 'calendar_range = 0 AND ';
$sql .= "calendar_start >= '".aporeplace($plugin['current_year'].'-'.$plugin['current_month'].'-1 00:00:00')."' AND ";
$sql .= "calendar_start < '".aporeplace($plugin['end_year'].'-'.$plugin['end_month'].'-1 00:00:00')."' ORDER BY calendar_start ASC";
$plugin['dates'] = _dbQuery($sql);

// run through dates and put in right day, fist for all non-repeating dates
$_entry['dates'] = array();
if($plugin['dates']) {
    foreach($plugin['dates'] as $_entry['x']) {

        $_entry['day'] = intval($_entry['x']['calendar_day']);
        $_entry['dates'][ $_entry['day'] ][] = $_entry['x'];

    }
}

$sql  = 'SELECT *, ';
$sql .= "DATE_FORMAT(calendar_start, '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS calendar_start_date, ";
$sql .= "DATE_FORMAT(calendar_end,   '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS calendar_end_date, ";
$sql .= "DATE_FORMAT(calendar_range_start, '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS calendar_rangestart, ";
$sql .= "DATE_FORMAT(calendar_range_end,   '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS calendar_rangeend, ";
$sql .= "DATE_FORMAT(calendar_start, '%H:%i') AS calendar_start_time, ";
$sql .= "DATE_FORMAT(calendar_end,   '%H:%i') AS calendar_end_time, ";
$sql .= "DATE_FORMAT(calendar_start,   '%e') AS calendar_day ";
$sql .= ' FROM '.DB_PREPEND.'phpwcms_calendar WHERE ';
$sql .= 'calendar_status != 9 AND ';
$sql .= 'calendar_range > 0 AND ';
$sql .= "calendar_range_start < '".aporeplace($plugin['end_year'].'-'.$plugin['end_month'].'-01 00:00:00')."' AND ";
$sql .= "calendar_range_end > '".aporeplace($plugin['current_year'].'-'.$plugin['current_month'].'-1 00:00:00')."' ";
$sql .= 'ORDER BY calendar_range_start ASC';
$plugin['dates'] = _dbQuery($sql);

// run through dates and put in right day, fist for all non-repeating dates
if($plugin['dates']) {
    foreach($plugin['dates'] as $_entry['y']) {

        $_entry['day']                      = intval($_entry['y']['calendar_day']);
        $_entry['range_start_timestamp']    = strtotime($_entry['y']['calendar_range_start'].' 00:00:00');
        $_entry['range_end_timestamp']      = strtotime($_entry['y']['calendar_range_end'].' 23:59:59');
        $_entry['this_timestamp']           = strtotime($_entry['y']['calendar_start']);
        $_entry['date_weekday']             = intval(date('w', $_entry['this_timestamp']));
        $_entry['day_month']                = intval(date('j', $_entry['this_timestamp']));
        $_entry['day_year']                 = date('dm', $_entry['this_timestamp']);

        for($_entry['x'] = 1, $_entry['timestamp']=$plugin['first_of_month']; $_entry['x'] <= $plugin['days_in_month']; $_entry['x']++, $_entry['timestamp']+=86400) {

            if($_entry['timestamp'] >= $_entry['range_start_timestamp'] && $_entry['timestamp'] <= $_entry['range_end_timestamp']) {

                $_entry['weekday'] = intval(date('w', $_entry['timestamp']));
                //  1 daily
                //  2 Every weekday (Mon-Fri)
                //  3 Every Mon., Wed. and Fri.
                //  4 Every Tues. and Thurs.
                //  5 Weekly
                //  6 Monthly
                //  7 yearly
                //  8 Every Monday
                //  9 Every Tuesday
                // 10 Every Wednesday
                // 11 Every Thursday
                // 12 Every Friday
                // 13 Every Saturday
                // 14 Every Sunday
                // 15 Every Wednesday – Sunday
                // 16 Every Weekend

                $_entry['y']['calendar_range'] = intval($_entry['y']['calendar_range']);

                if( $_entry['y']['calendar_range'] === 1
                    ||
                    ($_entry['y']['calendar_range'] === 2 && $_entry['weekday'] !== 6 && $_entry['weekday'] !== 0)
                    ||
                    ($_entry['y']['calendar_range'] === 3 && ($_entry['weekday'] === 1 || $_entry['weekday'] === 3 || $_entry['weekday'] === 5))
                    ||
                    ($_entry['y']['calendar_range'] === 4 && ($_entry['weekday'] === 2 || $_entry['weekday'] === 4))
                    ||
                    ($_entry['y']['calendar_range'] === 5 && $_entry['weekday'] === $_entry['date_weekday'])
                    ||
                    ($_entry['y']['calendar_range'] === 6 && $_entry['x'] === $_entry['day_month'])
                    ||
                    ($_entry['y']['calendar_range'] === 7 && date('dm', $_entry['timestamp']) === $_entry['day_year'])
                    ||
                    ($_entry['y']['calendar_range'] === 8 && $_entry['weekday'] === 1)
                    ||
                    ($_entry['y']['calendar_range'] === 9 && $_entry['weekday'] === 2)
                    ||
                    ($_entry['y']['calendar_range'] === 10 && $_entry['weekday'] === 3)
                    ||
                    ($_entry['y']['calendar_range'] === 11 && $_entry['weekday'] === 4)
                    ||
                    ($_entry['y']['calendar_range'] === 12 && $_entry['weekday'] === 5)
                    ||
                    ($_entry['y']['calendar_range'] === 13 && $_entry['weekday'] === 6)
                    ||
                    ($_entry['y']['calendar_range'] === 14 && $_entry['weekday'] === 0)
                    ||
                    ($_entry['y']['calendar_range'] === 15 && (($_entry['weekday'] >= 3 && $_entry['weekday'] <= 6) || $_entry['weekday'] === 0))
                    ||
                    ($_entry['y']['calendar_range'] === 16 && ($_entry['weekday'] === 6 || $_entry['weekday'] === 0))
                ) {

                    $_entry['y']['calendar_start_date'] = date('d'.$BLM['date_delimiter'].'m'.$BLM['date_delimiter'].'Y', $_entry['timestamp']);
                    $_entry['y']['calendar_end_date']   = $_entry['y']['calendar_start_date'];
                    $_entry['dates'][ $_entry['x'] ][]  = $_entry['y'];

                }
            }
        }
    }
}


$plugin['day_names'] = returnDayNameArray();

// head row

echo '<tr>';
echo '<th class="calendarWeek">'.$BLM['weekNrTitle'].'</th>';
echo '<th><img src="img/famfamfam/calendar_view_month.gif" alt="" /></th>';
echo '<th width="95%" class="calendarMonth">';

echo $plugin['this_date'];

echo '</th><th>&nbsp;</th></tr>';

$_entry['rowspan']  = gmstrftime('%w', $plugin['first_of_month']);
$_entry['rowspan']  = 8 - ($_entry['rowspan']==0 ? 7 : $_entry['rowspan']);
$_entry['c']        = 0;

for($_entry['x'] = 1, $_entry['timestamp']=$plugin['first_of_month']; $_entry['x'] <= $plugin['days_in_month']; $_entry['x']++, $_entry['timestamp']+=86400) {

    $_entry['day_num'] = gmstrftime('%w', $_entry['timestamp']);
    $_entry['day_num'] = $_entry['day_num']==0 ? 7 : $_entry['day_num'];

    echo '<tr';
    if($_entry['x'] % 2) {
        echo ' class="calendarAltRow"';
    }
    echo '>'.LF;

    if($_entry['day_num'] == 1) {

        if($plugin['days_in_month'] - $_entry['x'] < 7) {
            $_entry['rowspan'] = (int)$plugin['days_in_month'] - $_entry['x'] + 1;
        } else {
            $_entry['rowspan'] = 7;
        }

    }

    if($_entry['rowspan']) {

        echo '<td ';
        echo ($_entry['rowspan'] > 1) ? 'rowspan="'.$_entry['rowspan'].'" ' : '';
        echo 'class="calendarWeek';
        echo ($_entry['c'] % 2) ? '' : ' calendarWeekAlt';
        echo '">';

        $_entry['wno'] = intval(gmstrftime('%W', $_entry['timestamp'])) + $plugin['week_add'];
        if($_entry['wno'] == 53) {
            $_entry['wno'] = 1;
        }

        echo $_entry['wno'];
        echo '</td>';

        $_entry['rowspan'] = 0;
        $_entry['c']++;

    }

    $_entry['class'] = ($_entry['day_num'] == 7 || $_entry['x'] == $plugin['days_in_month']) ? ' calendarSunday' : '';

    echo '<td class="calendarDay'.$_entry['class'].'"><span>'.$_entry['x'].'</span><br />'.html(gmstrftime('%a', $_entry['timestamp']), false).'</td>';
    echo '<td class="calendarData'.$_entry['class'].'">';

    // run available dates for current day
    if(isset($_entry['dates'][ $_entry['x'] ])) {

        foreach($_entry['dates'][ $_entry['x'] ] as $_entry['date']) {

            $_entry['link '] = $_entry['date']['calendar_title'].' (';
            if($_entry['date']['calendar_allday']) {
                $_entry['link '] .= $BLM['all_day'];
            } else {
                $_entry['link '] .= $_entry['date']['calendar_start_time'].'&#8211;';
                if($_entry['date']['calendar_start_date'] != $_entry['date']['calendar_end_date']) {
                    $_entry['link '] .= $_entry['date']['calendar_end_date'].',&nbsp;';
                }
                $_entry['link '] .= $_entry['date']['calendar_end_time'];
            }
            $_entry['link '] .= ')';

            if($_entry['date']['calendar_range']) {
                $_entry['link '] = $BLM['repeat_list'.$_entry['date']['calendar_range']].': '.$_entry['link '];
            }
            $_entry['link '] = html($_entry['link '], false);

            echo '<p><a href="'.MODULE_HREF.'&amp;edit='.$_entry['date']['calendar_id'].'"';
            if($_entry['date']['calendar_status'] == 0) echo ' class="off"';
            echo '>' . $_entry['link '] . '</a>';

            echo '<a href="'.MODULE_HREF.'&amp;delete='.$_entry['date']['calendar_id'].'" class="calendarDateDel"';
            echo ' title="'.$BLM['delete'].': '. $_entry['link '] .'"';
            echo ' onclick="return confirm(\''.$BLM['delete_entry'].' \n'.js_singlequote($_entry['date']['calendar_title']).'\');">';
            echo '<img src="img/button/del_9x9.gif" alt="" border="0" /></a>';

            /*
            echo '<img src="img/button/';
            if($_entry['date']['calendar_status'] == 0) echo 'in';
            echo 'aktiv_mini1.gif" alt="" border="0" />';
            */

            echo '</p>';

        }

    } else {

        echo '&nbsp;';

    }

    echo '</td>';

    echo '<td class="calendarButton'.$_entry['class'].'">';
    echo '<a href="'.MODULE_HREF.'&amp;edit=0&amp;defaultdate=';
    echo $_entry['x'].'-'.$plugin['current_month'].'-'.$plugin['current_year'].'" title="'.$BLM['add_event'].'">';
    echo '<img src="img/famfamfam/calendar_add.gif" alt="" border="0" />';
    echo '</a></td>';

    echo '</tr>'.LF;

}

// switch language back
if(!empty($BLM['locale_string'])) {
    setlocale(LC_TIME, $_oldLocale); //switch current locale back to old value
}

?>

</table>