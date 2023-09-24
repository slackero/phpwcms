<?php

/**
 * Example of a custom calendar rendering handling periods/dates
 * with custom class — based on phpwcms' calendar module
 *
 * @see https://forum.phpwcms.org/viewtopic.php?p=151715#p151715
 */

@require_once PHPWCMS_ROOT . '/include/inc_module/mod_calendar/inc/calendar.class.php';

// Include Calendar Class
if (class_exists('phpwcmsCalendar') && str_contains($content['all'], '{CALENDAR_')) {

    /**
     * Extend phpwcmsCalendar
     */
    class RangeCalendar extends phpwcmsCalendar
    {

        /**
         * @var string
         */
        public string $lang;

        /**
         * calendar period replacer
         *
         * @var string
         */
        public string $period_format = 'j @@F@@ Y';

        /**
         * @var string
         */
        public string $filter_year_min;

        /**
         * @var string
         */
        public string $filter_year_max;

        /**
         * @var int|false
         */
        public int|false $month_start_next;

        /**
         * @var int|false
         */
        public int|false $month_end_next;

        /**
         * @var int|false
         */
        public int|false $month_start_prev;

        /**
         * @var int
         */
        public int $month_next;

        /**
         * @var int
         */
        public int $month_prev;

        /**
         * @var array
         */
        public array $prevnext = [];

        /**
         * @var string
         */
        public string $search_action_target = '';
        /**
         * @var array
         */
        public array $search = [];

        /**
         * Construct
         */
        public function __construct()
        {
            parent::__construct();

            $this->lang = $GLOBALS['phpwcms']['default_lang'];

            if ($this->lang === 'de') {
                $this->set_period_format('j. @@F@@ Y');
            }
        }

        /**
         * @param $format
         * @return void
         */
        public function set_period_format($format): void
        {
            $this->period_format = $format;
        }

        /**
         * @return string
         */
        public function get_period_format(): string
        {
            return $this->period_format;
        }

    }

    // Initialize the new RangeCalendar, based on phpwcmsCalendar
    $RANGE_CAL = new RangeCalendar();

    //$template_default['body']['class'] = trim($template_default['body']['class'] . ' my-calendar');

    $MY_CAL = [
        'str_search' => [
            '{CALENDAR_SEARCH}' => '{CALENDAR_SEARCH}',
            '{CALENDAR_PREVNEXT}' => '{CALENDAR_PREVNEXT}',
            '{CALENDAR_PERIOD}' => '{CALENDAR_PERIOD}',
            '{CALENDAR_PLACE}' => '{CALENDAR_PLACE}',
        ],
        'str_replace' => [
            '{CALENDAR_SEARCH}' => '',
            '{CALENDAR_PREVNEXT}' => '',
            '{CALENDAR_PERIOD}' => '@@Aktuelle Termine@@',
            '{CALENDAR_PLACE}' => '',
        ],
    ];

    $RANGE_CAL->parse($content['all']);
    $RANGE_CAL->getFirstCalendarDate();
    $RANGE_CAL->getLastCalendarDate();
    //$RANGE_CAL->getCalendarPlaces();

    if ($RANGE_CAL->date_first !== NULL && $RANGE_CAL->date_last !== NULL) {

        //$MYCAL['str_replace']['{CALENDAR_PERIOD}'] = date($RANGE_CAL->get_period_format(), $RANGE_CAL->date_start) . ' &#8211; ' . date($RANGE_CAL->get_period_format(), $RANGE_CAL->date_end);
        $MY_CAL['str_replace']['{CALENDAR_PERIOD}'] = date('@@F@@ Y', $RANGE_CAL->date_start);
        //$content['pagetitle'] = str_replace('{CALENDAR_PERIOD}', $MYCAL['str_replace']['{CALENDAR_PERIOD}'], $content['pagetitle']);

        // The ID or alias of the target page in your instance of phpwcms
        $RANGE_CAL->search_action_target = 'my-calendar';

        // set calendar month names
        $RANGE_CAL->select_format_day = '%02s';
        $RANGE_CAL->select_format_month = '%02s';
        $RANGE_CAL->select_format_year = 4;
        $RANGE_CAL->selector_format = 'DMY';
        $RANGE_CAL->select_month_option = [
            1 => '@@January@@',
            2 => '@@February@@',
            3 => '@@March@@',
            4 => '@@April@@',
            5 => '@@May@@',
            6 => '@@June@@',
            7 => '@@July@@',
            8 => '@@August@@',
            9 => '@@September@@',
            10 => '@@October@@',
            11 => '@@November@@',
            12 => '@@December@@'
        ];

        $RANGE_CAL->filter_year_min = date('Y', $RANGE_CAL->date_first);
        $RANGE_CAL->filter_year_max = date('Y', $RANGE_CAL->date_last);

        if (str_contains($content['all'], '{CALENDAR_PREVNEXT}')) {

            $RANGE_CAL->month_start_next = strtotime('+1 month', $RANGE_CAL->date_start);
            $RANGE_CAL->month_end_next = strtotime('+1 month', $RANGE_CAL->date_start);
            $RANGE_CAL->month_start_prev = strtotime('-1 month', $RANGE_CAL->date_start);
            $RANGE_CAL->month_next = (int)date('n', $RANGE_CAL->month_start_next);
            $RANGE_CAL->month_prev = (int)date('n', $RANGE_CAL->month_start_prev);

            $RANGE_CAL->prevnext = [];

            $RANGE_CAL->prevnext[] = '<script>';
            $RANGE_CAL->prevnext[] = '		let calendarlist = true;';
            $RANGE_CAL->prevnext[] = '		let calendarlistmore = "@@more@@";';
            $RANGE_CAL->prevnext[] = '</script>';

            $RANGE_CAL->prevnext[] = '	<span class="calendar-prevnext-links">';
            $_prev_link = rel_url(
                [
                    'pcal_start' => date('Y-m-01', $RANGE_CAL->month_start_prev),
                    'pcal_end' => date('Y-m-t', $RANGE_CAL->month_start_prev),
                ],
                [
                    'pcal_reset',
                    'pcal_place',
                    'pcal_limit',
                ]
            );
            $RANGE_CAL->prevnext[] = '		<a href="' . $_prev_link . '" title="' . $RANGE_CAL->select_month_option[$RANGE_CAL->month_prev] . ' ' . date('Y', $RANGE_CAL->month_start_prev) . '">&lt;</a>';

            $_next_link = rel_url(
                [
                    'pcal_start' => date('Y-m-01', $RANGE_CAL->month_start_next),
                    'pcal_end' => date('Y-m-t', $RANGE_CAL->month_start_next),
                ],
                [
                    'pcal_reset',
                    'pcal_place',
                    'pcal_limit',
                ]
            );
            $RANGE_CAL->prevnext[] = '		<a href="' . $$_next_link . '" title="' . $RANGE_CAL->select_month_option[$RANGE_CAL->month_next] . ' ' . date('Y', $RANGE_CAL->month_start_next) . '">&gt;</a>';
            $RANGE_CAL->prevnext[] = '	</span>';

            // Today
            //$RANGE_CAL->prevnext[] = '	<div class="calendar-prevnext-links">';
            //$RANGE_CAL->prevnext[] = '		<a href="'.rel_url(['pcal_reset'=>1], ['pcal_start', 'pcal_end', 'pcal_place', 'pcal_limit']).'" title="'.date($RANGE_CAL->get_period_format()).'">@@Today@@</a>';
            //$RANGE_CAL->prevnext[] = '	</div>';

            $MY_CAL['str_replace']['{CALENDAR_PREVNEXT}'] = implode(LF, $RANGE_CAL->prevnext);

        }

        if (str_contains($content['all'], '{CALENDAR_SEARCH}')) {

            $RANGE_CAL->search = [];

            $RANGE_CAL->search[] = '<div class="span2 calendar-search">';

            $_search_action = rel_url(
                [],
                [
                    'pcal_start',
                    'pcal_end',
                    'pcal_place',
                    'pcal_limit',
                ],
                $RANGE_CAL->search_action_target
            );
            $RANGE_CAL->search[] = '<form action="' . $_search_action . '" method="post" id="calendar-search">';

            $RANGE_CAL->search[] = '	<p>';
            $RANGE_CAL->search[] = '		<em>@@from@@&nbsp;</em> ';
            $RANGE_CAL->search[] = $RANGE_CAL->getDateSelect(
                'start',
                [
                    'min' => $RANGE_CAL->filter_year_min,
                    'max' => $RANGE_CAL->filter_year_max,
                ],
                date('j', $RANGE_CAL->date_start),
                date('n', $RANGE_CAL->date_start),
                date('Y', $RANGE_CAL->date_start),
            );
            $RANGE_CAL->search[] = '	</p>';

            $RANGE_CAL->search[] = '	<p>';
            $RANGE_CAL->search[] = '		<em>@@to@@&nbsp;</em> ';
            $RANGE_CAL->search[] = $RANGE_CAL->getDateSelect(
                'end',
                [
                    'min' => $RANGE_CAL->filter_year_min,
                    'max' => $RANGE_CAL->filter_year_max
                ],
                date('j', $RANGE_CAL->date_end),
                date('n', $RANGE_CAL->date_end),
                date('Y', $RANGE_CAL->date_end),
            );
            $RANGE_CAL->search[] = '	</p>';

            /*
            $RANGE_CAL->search[] = '	<p>';
            $RANGE_CAL->search[] = '		<em>@@Ort@@</em> ';
            $RANGE_CAL->search[] = '		<select name="pcal_place" id="pcal_place" class="place">';
            $RANGE_CAL->search[] = '			<option value="">@@alle Orte@@</option>';
            foreach($MYCAL['city_data'] as $city) {
                $RANGE_CAL->search[] = '			<option value="'.html_specialchars($city['tag']).'"' . ($city['tag'] == $RANGE_CAL->place ? ' selected="selected"' : '') . '>'.html_specialchars($city['city_name']).'</option>';
            }
            $RANGE_CAL->search[] = '		</select>';
            $RANGE_CAL->search[] = '	</p>';
            */

            $RANGE_CAL->search[] = '	<p>';
            $RANGE_CAL->search[] = '		<em>@@max@@&nbsp</em> ';
            $RANGE_CAL->search[] = '		<select name="pcal_limit" id="pcal_limit">';
            $RANGE_CAL->search[] = '			<option value="0">@@All@@</option>';
            foreach ([5, 10, 25, 50, 75, 100, 150] as $x) {
                $RANGE_CAL->search[] = '			<option value="' . $x . '"' . ($x == $RANGE_CAL->limit_item ? ' selected="selected"' : '') . '>' . $x . '</option>';
            }
            $RANGE_CAL->search[] = '		</select>';
            $RANGE_CAL->search[] = '		<em class="text">@@Items@@</em> ';
            $RANGE_CAL->search[] = '	</p>';

            $RANGE_CAL->search[] = '	<p><input type="submit" value="@@Search an event@@" class="btn btn-success"></p>';

            $RANGE_CAL->search[] = '</form>';

            /*
            $RANGE_CAL->search[] = '	<script>';
            $RANGE_CAL->search[] = '	$(function() {';
            $RANGE_CAL->search[] = '		var start_day = $("#pcal_start_day");';
            $RANGE_CAL->search[] = '		var start_month	= $("#pcal_start_month");';
            $RANGE_CAL->search[] = '		var start_year = $("#pcal_start_year");';
            $RANGE_CAL->search[] = '		var end_day = $("#pcal_end_day");';
            $RANGE_CAL->search[] = '		var end_month = $("#pcal_end_month");';
            $RANGE_CAL->search[] = '		var end_year = $("#pcal_end_year");';
            $RANGE_CAL->search[] = '		var start = new Date(';
            $RANGE_CAL->search[] = '			    start_year.options[start_year.selectedIndex].value,';
            $RANGE_CAL->search[] = '			    start_month.options[start_month.selectedIndex].value-1,';
            $RANGE_CAL->search[] = '			    start_day.options[start_day.selectedIndex].value,';
            $RANGE_CAL->search[] = '			    0, 0, 0);';
            $RANGE_CAL->search[] = '		var end = new Date(';
            $RANGE_CAL->search[] = '			end_year.options[end_year.selectedIndex].value,';
            $RANGE_CAL->search[] = '			end_month.options[end_month.selectedIndex].value-1,';
            $RANGE_CAL->search[] = '			end_day.options[end_day.selectedIndex].value,';
            $RANGE_CAL->search[] = '			23, 59, 59);';
            $RANGE_CAL->search[] = '		if(start >= end) {';
            $RANGE_CAL->search[] = '			end = new Date(start);';
            $RANGE_CAL->search[] = '			end.setHours(23, 59, 59);';
            $RANGE_CAL->search[] = '			end.setDate(end.getDate()+1);';
            $RANGE_CAL->search[] = '		}';
            $RANGE_CAL->search[] = '		$("pcal_start").value = start.getFullYear() + "-" + (start.getMonth()+1) + "-" + start.getDate() +" 00:00:00";';
            $RANGE_CAL->search[] = '		$("pcal_end").value = end.getFullYear() + "-" + (end.getMonth()+1) + "-" + end.getDate() + " 23:59:59";';
            $RANGE_CAL->search[] = '		return true;';
            $RANGE_CAL->search[] = '	});';
            $RANGE_CAL->search[] = '	</script>';
            */

            $RANGE_CAL->search[] = '</div>';

            $MY_CAL['str_replace']['{CALENDAR_SEARCH}'] = implode(LF, $RANGE_CAL->search);

        }

    }

    /* Replace in the page title */
    $content['pagetitle'] = str_replace(
        $MY_CAL['str_search'],
        $MY_CAL['str_replace'],
        $content['pagetitle']
    );

    /* Replace in the content */
    $content['all'] = str_replace(
        $MY_CAL['str_search'],
        $MY_CAL['str_replace'],
        $content['all']
    );

}
