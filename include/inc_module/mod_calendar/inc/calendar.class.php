<?php

/**
 * phpwcms Calendar frontend render class
 */
class phpwcmsCalendar {

    var $mode                   = 'simple';
    var $dates                  = array();
    var $session                = false;
    var $place                  = '';
    var $where_tag              = '';
    var $where_place            = '';
    var $where_lang             = '';
    var $calendar_places        = array();
    var $limit_item             = 0;
    var $limit                  = '';
    var $gettype                = ''; // used to detect type section inside event title
    var $teaserwords            = 0; // cut teaser text after n words
    var $date_first             = NULL;
    var $date_last              = NULL;
    var $getbasis               = 'pcal_';
    var $selector_format        = 'DMY';
    var $select_format_day      = '%02s'; // %02s -> 05, %s -> 5
    var $select_format_month    = 'name'; // %02s -> 05, %s -> 5, 'name' like defined in $select_month_option
    var $select_format_year     = 4; // 4 = 2010, 2 = 10
    var $select_month_option    = array(
        1   => 'January',
        2   => 'February',
        3   => 'March',
        4   => 'April',
        5   => 'May',
        6   => 'June',
        7   => 'July',
        8   => 'August',
        9   => 'September',
        10  => 'October',
        11  => 'November',
        12  => 'December'
    );
    var $no_calendar_item_found = '@@No date found for current calendar search.@@ <a href="{CALENDAR_RESET}" title="@@Reset@@">@@Reset@@</a>';
    var $template               = '';
    var $expired                = '';
    var $expired_date           = 'END';
    var $expired_prefix         = '';
    var $expired_suffix         = '';
    var $range_date_type        = array(
        0   => '',
        1   => 'daily',
        2   => 'every weekday (Mon-Fri)',
        3   => 'every Mon., Wed. and Fri.',
        4   => 'every Tues. and Thurs.',
        5   => 'weekly',
        6   => 'monthly',
        7   => 'yearly',
        8   => 'every Monday',
        9   => 'every Tuesday',
        10  => 'every Wednesday',
        11  => 'every Thursday',
        12  => 'every Friday',
        13  => 'every Saturday',
        14  => 'every Sunday',
        15  => 'every Wednesday - Sunday',
        16  => 'every Weekend (Sat+Sun)'
    );

    /**
     * Initialize class
     */
    function __construct() {

        // current
        $this->current_date     = getdate();

        // set today 00:00:00 as start date
        $this->date_start       = mktime(0, 0, 0, $this->current_date['mon'], $this->current_date['mday'], $this->current_date['year']);

        // by default date_start + 1 year
        $this->date_end         = mktime(0, 0, 0, $this->current_date['mon'], $this->current_date['mday'], $this->current_date['year'] + 1) - 1;

        // set datetime
        $this->datetime_start   = date('Y-m-d H:i:s', $this->date_start);
        $this->datetime_end     = date('Y-m-d H:i:s', $this->date_end);

        $this->dbReset();
    }

    /**
     * Reset db base vars
     */
    function dbReset() {
        $this->where    = '';
        $this->select   = '*';
        $this->join_on  = '';
        $this->group_by = '';
        $this->order_by = 'calendar_start ASC';
        $this->limit    = 0;
    }

    function defaultTemplate() {
        $this->template  = '<div class="calendar-item-{ID}[TEXT] calendar-item-has-text[/TEXT]">'.LF;
        $this->template .= '    <p class="calendar-list-date">'.LF;
        $this->template .= '        [RANGEDATE]{RANGEDATE} | {STARTDATE:m/d}-{ENDDATE:m/d/Y}[/RANGEDATE][RANGEDATE_ELSE]{STARTDATE:m/d/Y}[/RANGEDATE_ELSE][ALLDAY_ELSE], {STARTDATE:H:i}[/ALLDAY_ELSE][TYPE],'.LF;
        $this->template .= '        <span class="calendar-list-type">{TYPE}</span>[/TYPE]'.LF;
        $this->template .= '    </p>[TITLE]'.LF;
        $this->template .= '    <h2>[URL]<a href="{URL}"{TARGET}>[/URL]{TITLE}[URL]</a>[/URL]</h2>[/TITLE][TEASER]'.LF;
        $this->template .= '    <div class="calendar-list-teaser">{TEASER}</div>[/TEASER][TEXT]'.LF;
        $this->template .= '    <div class="calendar-list-text">{TEXT}'.LF;
        $this->template .= '        [URL]<p class="calendar-list-more"><a href="{URL}"{TARGET}>@@more@@&#8230;</a></p>[/URL]'.LF;
        $this->template .= '    </div>[/TEXT][TEXT_ELSE][URL]'.LF;
        $this->template .= '    <p class="calendar-list-more"><a href="{URL}"{TARGET}>@@more@@&#8230;</a></p>[/URL][/TEXT_ELSE]'.LF;
        $this->template .= '</div>';
        $this->href      = '';
    }

    /**
     * search string for calendar tag and parse
     */
    function parse(& $string) {

        if(isset($_GET['pcal_reset']) || isset($_POST['pcal_reset'])) {
            unset($_SESSION['pcal']);
            headerRedirect( abs_url(array(), array('pcal_reset','pcal_start', 'pcal_end', 'pcal_place', 'pcal_limit'), '', 'rawurlencode' ) );
        }

        if( preg_match_all('/\{CALENDAR:(.*?)\}/s', $string, $matches) ) {

            if( isset($matches[1]) ) {

                foreach($matches[1] as $key => $value) {

                    $this->parse_match($value);
                    $result = $this->render();

                    // replace calendar by result
                    $string = str_replace($matches[0][$key], $result, $string);

                }

                $string = html_parser($string);

            }
        }

    }

    /**
     * Calendar reset Link - delete current Calendar Session or GET
     */
    function resetCalendarLink() {
        return rel_url(array('pcal_reset'=>1), array('pcal_start', 'pcal_end', 'pcal_place', 'pcal_limit'));
    }

    function get_calendar_range_type($calendar_range) {

        if(isset($this->range_date_type[$calendar_range])) {
            return $this->range_date_type[$calendar_range];
        }

        return '';

    }

    function render() {

        $items = array(
            'top' => array(),
            'default' => array(),
            'bottom' => array(),
            'hide' => array()
        );

        $now = now();

        foreach($this->dates as $key => $date) {

            $url            = '';
            $target         = '';
            $href           = $this->href ? $this->href . '&amp;show_date='.date('Y-m-d', $date['calendar_start_date']).'_'.$date['calendar_id'] : '';
            $itemgroup      = 'default';

            $date['calendar_range'] = intval($date['calendar_range']);

            if($date['calendar_range']) {
                $date['calendar_range_start_date']  = strtotime($date['calendar_range_start'].' '.date('H:i', $date['calendar_start_date']));
                $date['calendar_range_end_date']    = strtotime($date['calendar_range_end']);
                $expired_date = $this->expired === 'START' ? 'calendar_range_start_date' : 'calendar_range_end_date';
            } else {
                $expired_date = $this->expired === 'START' ? 'calendar_start_date' : 'calendar_end_date';
            }

            if($this->expired !== '' && $date[$expired_date] < $now) {
                if($this->expired === 'bottom' || $this->expired === 'top') {
                    $itemgroup = $this->expired;
                } elseif($this->expired === 'hide') {
                    unset($this->dates[$key]);
                    continue;
                }
            }

            if(!empty($date['calendar_refid'])) {

                $date['calendar_refid']         = get_redirect_link($date['calendar_refid'], ' ', '');
                $date['calendar_refid']['link'] = trim($date['calendar_refid']['link']);
                $date['calendar_refid']['link'] = trim($date['calendar_refid']['link'], '#');

                $target                         = $date['calendar_refid']['target'];

                if(is_intval($date['calendar_refid']['link'])) {
                    $url = rel_url(array(), array(), 'aid='.$date['calendar_refid']['link']); //'index.php?aid='.$date['calendar_refid']['link'];

                } elseif(strpos($date['calendar_refid']['link'], '://') || strpos($date['calendar_refid']['link'], '?') || strpos($date['calendar_refid']['link'], '.')) {
                    $url = $date['calendar_refid']['link'];

                } elseif(!empty($date['calendar_refid']['link'])) {
                    $url = rel_url(array(), array(), $date['calendar_refid']['link']);

                }
            }

            // Split title/type
            if($this->gettype !== '') {
                $date['calendar_title'] = explode($this->gettype, $date['calendar_title'], 2);
                $date['calendar_type']  = empty($date['calendar_title'][1]) ? '' : trim($date['calendar_title'][1]);
                $date['calendar_title'] = trim($date['calendar_title'][0]);
            } else {
                $date['calendar_type']  = '';
            }

            if($date['calendar_teaser']) {
                if($this->teaserwords) {
                    $date['calendar_teaser'] = getCleanSubString($date['calendar_teaser'], $this->teaserwords, $GLOBALS['template_default']['ellipse_sign'], 'word');
                }
                $date['calendar_teaser'] = plaintext_htmlencode($date['calendar_teaser']);
            }

            $items[$itemgroup][$key] = str_replace('{ID}', $date['calendar_id'], $this->template);
            $items[$itemgroup][$key] = render_cnt_template($items[$itemgroup][$key], 'HREF', $href);
            $items[$itemgroup][$key] = render_cnt_template($items[$itemgroup][$key], 'URL', $url);
            $items[$itemgroup][$key] = render_cnt_template($items[$itemgroup][$key], 'TARGET', $target);
            $items[$itemgroup][$key] = render_cnt_template($items[$itemgroup][$key], 'TITLE', html_specialchars($date['calendar_title']));
            $items[$itemgroup][$key] = render_cnt_template($items[$itemgroup][$key], 'TYPE', $date['calendar_type'] ? html_specialchars($date['calendar_type']) : '');
            $items[$itemgroup][$key] = render_cnt_template($items[$itemgroup][$key], 'TEASER', $date['calendar_teaser']);
            $items[$itemgroup][$key] = render_cnt_template($items[$itemgroup][$key], 'TEXT', $date['calendar_text']);
            $items[$itemgroup][$key] = render_cnt_template($items[$itemgroup][$key], 'PLACE', html_specialchars($date['calendar_where']));
            $items[$itemgroup][$key] = render_cnt_template($items[$itemgroup][$key], 'ALLDAY', $date['calendar_allday'] ? ' ' : '');
            // Detect if range date
            if($date['calendar_range']) {
                $items[$itemgroup][$key] = render_cnt_template($items[$itemgroup][$key], 'RANGEDATE', $this->get_calendar_range_type($date['calendar_range']));
                $items[$itemgroup][$key] = render_cnt_date($items[$itemgroup][$key], $date['calendar_range_start_date'], $date['calendar_range_start_date'], $date['calendar_range_end_date']);
            } else {
                $items[$itemgroup][$key] = render_cnt_template($items[$itemgroup][$key], 'RANGEDATE', '');
            }
            $items[$itemgroup][$key] = render_cnt_date($items[$itemgroup][$key], $date['calendar_start_date'], $date['calendar_start_date'], $date['calendar_end_date']);

        }

        if(!count($items['default']) && !count($items['top']) && !count($items['bottom'])) {
            return str_replace('{CALENDAR_RESET}', $this->resetCalendarLink(), $this->no_calendar_item_found);
        } elseif($this->expired && count($items[$this->expired])) {
            array_unshift($items[$this->expired], $this->expired_prefix);
            $items[$this->expired][] = $this->expired_suffix;
            if($this->expired === 'top') {
                $items = implode(LF, $items['top']) . LF . implode(LF, $items['default']);
            } else {
                $items = implode(LF, $items['default']) . LF . implode(LF, $items['bottom']);
            }
        } else {
            $items = implode(LF, $items['default']);
        }

        return $items;
    }

    /**
     * Parse matched replacement tag
     */
    function parse_match($match='') {

        $default        = array();
        $match          = trim($match);

        // set query defaults
        $this->dbReset();
        $this->defaultTemplate();

        if($match !== '' && strpos($match, '=') !== FALSE ) {

            // oh yes fix, in case LF was converted to <br /> by phpwcms
            $match = str_replace('<br />', LF, $match);

            // result is a normal array
            $match = parse_ini_str($match, false);

            $default['items']           = isset($match['items']) ? intval($match['items']) : $this->limit;
            $default['template']        = empty($match['template']) ? '' : trim($match['template']);
            $default['lang']            = empty($match['lang']) ? '' : trim($match['lang']);
            $default['tag']             = empty($match['tag']) ? '' : trim($match['tag']);
            $default['tagmode']         = empty($match['tagmode']) ? 'OR' : (trim(strtoupper($match['tagmode'])) === 'AND' ? 'AND' : 'OR');
            $default['href']            = empty($match['href']) ? '' : trim($match['href']);
            $default['place']           = empty($match['place']) ? '' : trim($match['place']);
            $default['gettype']         = empty($match['gettype']) ? '' : $match['gettype'];
            $default['teaserwords']     = empty($match['teaserwords']) ? 0 : intval($match['teaserwords']);
            if(!empty($match['expired'])) {
                $match['expired']       = strtolower(trim($match['expired']));
                $default['expired']     = in_array($match['expired'], array('hide', 'bottom', 'top')) ? $match['expired'] : '';
            } else {
                $default['expired']     = '';
            }
            if(!empty($match['expired_date'])) {
                $match['expired_date']   = strtoupper(trim($match['expired_date']));
                $default['expired_date'] = in_array($match['expired_date'], array('START', 'END')) ? $match['expired_date'] : 'END';
            } else {
                $default['expired_date'] = 'END';
            }
            $default['expired_prefix']  = empty($match['expired_prefix']) ? '' : trim($match['expired_prefix']);
            $default['expired_suffix']  = empty($match['expired_suffix']) ? '' : trim($match['expired_suffix']);

        } else {

            // base format
            // 2,main_page.tmpl,de en, href, tag1, tag2 tag2, tag3 : date_start, date_end, place
            // [item count,[template[,language(en de - separated by space)[, href, tags, tag, tag, tag]]]]
            $match = explode(',', $match, 5);

            $default['items']           = intval($match[0]);
            $default['lang']            = empty($match[1]) ? '' : $match[1];
            $default['template']        = empty($match[2]) ? '' : trim($match[2]) ;
            $default['href']            = empty($match[3]) ? '' : trim($match[3]);
            $default['tagmode']         = 'OR';
            $default['place']           = '';
            $default['gettype']         = '';
            $default['teaserwords']     = 0;
            $default['expired']         = '';
            $default['expired_date']    = 'END';
            $default['expired_prefix']  = '';
            $default['expired_suffix']  = '';

            if(empty($match[4])) {
                $default['tag']     = '';
            } else {
                // check for start/end date
                $match[4]           = explode(':', $match[4], 2);
                if(isset($match[4][1])) {
                    $match[4][1] = explode(',', $match[4][1], 3);
                    if(!empty($match[4][1][0])) {
                        $match['date_start'] = $match[4][1][0];
                    } else {
                        $match['date_start'] = 'TODAY';
                    }
                    if(!empty($match[4][1][1])) {
                        $match['date_end'] = $match[4][1][1];
                    } else {
                        $match['date_end'] = (365 * 24 * 60 * 60) - 1; // + 365 days - 1 second
                    }
                    if(!empty($match[4][1][2])) {
                        $default['place'] = trim($match[4][1][2]);
                    }
                }
            }

        }

        // check for limit
        if(isset($_POST[$this->getbasis.'limit'])) {
            $default['items'] = intval(clean_slweg($_POST[$this->getbasis.'limit']));
            $this->session = true;
        } elseif(isset($_GET[$this->getbasis.'limit'])) {
            $default['items'] = intval(clean_slweg($_GET[$this->getbasis.'limit']));
            $this->session = true;
        } elseif(!empty($_SESSION['pcal']['limit'])) {
            $default['items'] = $_SESSION['pcal']['limit'];
        }

        // check for place to search
        if(isset($_POST[$this->getbasis.'place'])) {
            $default['place'] = clean_slweg($_POST[$this->getbasis.'place']);
            $this->session = true;
        } elseif(isset($_GET[$this->getbasis.'place'])) {
            $default['place'] = clean_slweg($_GET[$this->getbasis.'place']);
            $this->session = true;
        } elseif(!empty($_SESSION['pcal']['place'])) {
            $default['place'] = $_SESSION['pcal']['place'];
        }

        // custom start date
        if(isset($_POST[$this->getbasis.'start'])) {
            if(empty($_POST[$this->getbasis.'start'])) {
                $match['date_start'] = $_POST[$this->getbasis.'start_year'] . '-' . $_POST[$this->getbasis.'start_month'] . '-' . $_POST[$this->getbasis.'start_day'] . ' 00:00:00';
            } else {
                $match['date_start'] = $_POST[$this->getbasis.'start'];
            }
            $match['date_start'] = clean_slweg($match['date_start']);
            $this->session = true;
        } elseif(isset($_GET[$this->getbasis.'start'])) {
            if(empty($_GET[$this->getbasis.'start'])) {
                $match['date_start'] = $_GET[$this->getbasis.'start_year'] . '-' . $_GET[$this->getbasis.'start_month'] . '-' . $_GET[$this->getbasis.'start_day'] . ' 00:00:00';
            } else {
                $match['date_start'] = $_GET[$this->getbasis.'start'];
            }
            $match['date_start'] = clean_slweg($match['date_start']);
            $this->session = true;
        } elseif(!empty($_SESSION['pcal']['date_start'])) {
            $match['date_start'] = $_SESSION['pcal']['date_start'];
        }

        // custom end date
        if(isset($_POST[$this->getbasis.'end'])) {
            if(empty($_POST[$this->getbasis.'end'])) {
                $match['date_end'] = $_POST[$this->getbasis.'end_year'] . '-' . $_POST[$this->getbasis.'end_month'] . '-' . $_POST[$this->getbasis.'end_day'] . ' 23:59:59';
            } else {
                $match['date_end'] = $_POST[$this->getbasis.'end'];
            }
            $match['date_end'] = clean_slweg($match['date_end']);
            $this->session = true;
        } elseif(isset($_GET[$this->getbasis.'end'])) {
            if(empty($_GET[$this->getbasis.'end'])) {
                $match['date_end'] = $_GET[$this->getbasis.'end_year'] . '-' . $_GET[$this->getbasis.'end_month'] . '-' . $_GET[$this->getbasis.'end_day'] . ' 23:59:59';
            } else {
                $match['date_end'] = $_GET[$this->getbasis.'end'];
            }
            $this->session = true;
        } elseif(!empty($_SESSION['pcal']['date_end'])) {
            $match['date_end'] = $_SESSION['pcal']['date_end'];
        }

        // set custom defined start/end date
        if(!empty($match['date_start'])) {
            $match['date_start'] = trim($match['date_start']);
            if(strtoupper($match['date_start']) == 'TODAY') {
                $this->date_start = mktime(0, 0, 0, $this->current_date['mon'], $this->current_date['mday'], $this->current_date['year']);
            } elseif(strtoupper($match['date_start']) == 'WEEKSTART') {
                $this->date_start = strtotime( ( intval(date('w', $this->current_date[0]))===1 ? 'Today' : 'last Monday') . ' 00:00:00' );
            } elseif(strtoupper($match['date_start']) == 'MONTHSTART') {
                $this->date_start = mktime(0, 0, 0, $this->current_date['mon'], 1, $this->current_date['year']);
            } elseif(strtoupper($match['date_start']) == 'YEARSTART') {
                $this->date_start = mktime(0, 0, 0, 1, 1, $current_date['year']);
            } else {
                $match['date_start'] = phpwcms_strtotime($match['date_start']);
                if($match['date_start']) {
                    $this->date_start = $match['date_start'];
                }
            }
        }
        if(!empty($match['date_end'])) {
            $match['date_end'] = strtoupper(trim($match['date_end']));
            if(is_intval($match['date_end'])) {
                $this->date_end = ceil((int)$this->date_start + ((int)$match['date_end'] * 24 * 3600));

                // Get Seconds of this day and match against 23:59:59
                $today_hours    = date('G', $this->date_end) * 3600;
                $today_minutes  = intval(date('i', $this->date_end)) * 60;
                $today_seconds  = intval(date('s', $this->date_end));
                $total_seconds  = $today_hours + $today_minutes + $today_seconds;
                $this->date_end += (24*3600) - $total_seconds - 1;

            } elseif($match['date_end'] == 'TODAY') {
                $this->date_end = mktime(23, 59, 59, $this->current_date['mon'], $this->current_date['mday'], $this->current_date['year']);
            } elseif($match['date_end'] == 'WEEKEND') {
                $this->date_end = strtotime('next Sunday 23:59:59');
            } elseif(preg_match('/(\d+)\s{0,1}(DAY|DAYS|WEEK|WEEKS|MONTH|MONTHS)/', $match['date_end'], $add)) {
                $this->date_end = strtotime('+'.$add[1].' '.$add[2].' 23:59:59', $this->date_start);
            } elseif(strtoupper($match['date_end']) == 'MONTHEND') {
                $this->date_end = mktime(23, 59, 59, $this->current_date['mon'], intval(date('t', $this->current_date[0])), $this->current_date['year']);
            } elseif(strtoupper($match['date_end']) == 'YEAREND') {
                $this->date_end = mktime(23, 59, 59, 12, 31, $current_date['year']);
            } else {
                if(strlen($match['date_end']) < 12 && preg_match('/[0-9\-]/', $match['date_end']) && strpos($match['date_end'], ':') === false) {
                    $match['date_end'] .= ' 23:59:59';
                }
                $match['date_end'] = phpwcms_strtotime($match['date_end']);
                if($match['date_end']) {
                    $this->date_end = $match['date_end'];
                }
            }
        }
        if($this->date_end <= $this->date_start) {
            $this->date_end = mktime(0, 0, 0, $this->current_date['mon'], $this->current_date['mday'], $this->current_date['year'] + 1) - 1;
        }

        $this->limit            = $default['items'];
        $this->limit_item       = $default['items'];
        $this->href             = $default['href'];
        $this->gettype          = $default['gettype'];
        $this->teaserwords      = $default['teaserwords'];
        $this->expired          = $default['expired'];
        $this->expired_date     = $default['expired_date'];
        $this->expired_prefix   = $default['expired_prefix'];
        $this->expired_suffix   = $default['expired_suffix'];

        if($default['template'] !== '') {
            $default['template'] = preg_replace('/[\/\\:]/', '', $default['template']);
            if(is_file(PHPWCMS_TEMPLATE.'calendar/'.$default['template'])) {
                $default['template'] = file_get_contents(PHPWCMS_TEMPLATE.'calendar/'.$default['template']);
                if($default['template']) {
                    $default['template'] = str_replace('{STARTDATE', '{LIVEDATE', $default['template']);
                    $default['template'] = str_replace('{ENDDATE', '{KILLDATE', $default['template']);
                    $this->template = $default['template'];
                }
            }
        }

        $where = array();

        if($default['lang'] !== '') {

            $default['lang']    = str_replace(',', ' ', preg_replace('/[^a-z\-]/', '', strtolower($default['lang'])));
            $default['lang']    = array_intersect( convertStringToArray( $default['lang'], ' '), $GLOBALS['phpwcms']['allowed_lang']);

            if(count($default['lang'])) {
                $this->where_lang   = "calendar_lang IN ('" . implode("','", $default['lang']) . "')";
                $where[]            = $this->where_lang;
            }
        }

        if($default['place'] !== '') {

            $places         = convertStringToArray( strtolower( $default['place'] ), ',');
            $place_items    = array();

            foreach($places as $place) {
                $place_items[] = 'calendar_where LIKE '._dbEscape('%'.$place.'%');
            }

            if(count($place_items)) {
                $this->where_place  = '(' . implode(' OR ', $place_items) . ')';
                $where[]            = $this->where_place;
            }
        }

        if($default['tag'] !== '') {

            $default['tag'] = convertStringToArray( strtolower( $default['tag'] ), ',');

            if(count($default['tag'])) {

                $tag_where = array();

                foreach($default['tag'] as $tag) {

                    $tag_where[]        = "cat_name='".aporeplace($tag)."'";

                }

                if(count($tag_where)) {

                    $this->where_tag    = '(' . implode(' '.$default['tagmode'] . ' ', $tag_where) . ')';
                    $where[]            = $this->where_tag;

                    $this->join_on  = 'LEFT JOIN '.DB_PREPEND.'phpwcms_categories ON cat_pid=calendar_id';
                    $this->group_by = 'calendar_id';
                }

            }
        }

        $this->where            = implode(' AND ', $where);
        $this->datetime_start   = date('Y-m-d H:i:s', $this->date_start);
        $this->datetime_end     = date('Y-m-d H:i:s', $this->date_end);
        $this->place            = $default['place'];

        $this->getDate();

        if($this->session && session_id()) {

            $this->session = array(
                'date_start'    => $this->datetime_start,
                'date_end'      => $this->datetime_end,
                'place'         => $default['place'],
                'limit'         => $default['items']
            );

            $_SESSION['pcal'] = isset($_SESSION['pcal']) ? array_merge($_SESSION['pcal'], $this->session) : $this->session;

        }

        return $default;

    }


    function getDate($s=NULL, $e=NULL, $o='', $l='', $g='', $w='') {

        // 1 daily
        // 2 Every weekday (Mon-Fri)
        // 3 Every Mon., Wed. and Fri.
        // 4 Every Tues. and Thurs.
        // 5 Weekly
        // 6 Monthly
        // 7 yearly

        if(!$o && is_string($this->order_by) && trim($this->order_by) != '') {
            $this->order_by = ' ORDER BY '.$this->order_by;
        } else {
            $this->order_by = $o ? ' ORDER BY '.$o : '';
        }
        if(!$l && is_int($this->limit) && $this->limit > 0) {
            $this->limit = ' LIMIT '.$this->limit;
        } else {
            $l = intval($l);
            $this->limit = $l ? ' LIMIT '.$l : '';
        }
        if(!$g && is_string($this->group_by) && trim($this->group_by) != $g) {
            $this->group_by = ' GROUP BY '.$this->group_by;
        } else {
            $this->group_by = $g ? ' GROUP BY '.$g : '';
        }

        $sql  = 'SELECT '. $this->select .', ';
        $sql .= "UNIX_TIMESTAMP(calendar_start) AS calendar_start_date, ";
        $sql .= "UNIX_TIMESTAMP(calendar_end) AS calendar_end_date ";
        $sql .= ' FROM '.DB_PREPEND.'phpwcms_calendar pc ';
        $sql .= $this->join_on;
        $sql .= ' WHERE ';
        $sql .= 'calendar_status = 1 ';
        if($s === NULL && $this->datetime_start) {
            $sql .= "AND calendar_range_end >= '".aporeplace( $this->datetime_start )."' ";
        } elseif($s) {
            $sql .= "AND calendar_range_end >= '".aporeplace( $s )."' ";
        }
        if($e === NULL && $this->datetime_end) {
            $sql .= "AND calendar_range_start <= '".aporeplace( $this->datetime_end )."' ";
        } elseif($e) {
            $sql .= "AND calendar_range_start <= '".aporeplace( $e )."' ";
        }
        if(empty($w) && !empty($this->where)) {
            $sql .= 'AND '.$this->where;
        } elseif(!empty($w)) {
            $sql .= 'AND '.$w;
        }
        $sql .= $this->group_by;
        $sql .= $this->order_by;
        $sql .= $this->limit;

        $this->dates = _dbQuery($sql);
        if( !$this->dates ) {
            $this->dates = array();
        }
    }

    /**
     * Return the where without place segement
     */
    function getNonLocationWhere() {

        $where  = $this->where_lang;
        if($where && $this->where_tag) {
            $where .= ' AND '.$this->where_tag;
        } elseif($this->where_tag) {
            $where = $this->where_tag;
        }

        return trim($where);

    }

    /**
     * Get the first date in the calender based on current where
     */
    function getFirstCalendarDate() {

        $this->getDate( '' , '', 'calendar_start ASC', 1, '', $this->getNonLocationWhere() );

        $this->date_first = isset($this->dates[0]['calendar_start_date']) ? $this->dates[0]['calendar_start_date'] : NULL;

        return $this->date_first;
    }

    /**
     * Get the last date in the calender based on current where
     */
    function getLastCalendarDate() {

        $this->getDate( '' , '', 'calendar_end DESC', 1, '', $this->getNonLocationWhere() );

        $this->date_last = isset($this->dates[0]['calendar_end_date']) ? $this->dates[0]['calendar_end_date'] : NULL;

        return $this->date_last;
    }

    /**
     * Get all places available for this calendar based on current where
     */
    function getCalendarPlaces() {

        $this->getDate( '' , '', 'calendar_where ASC', 0, 'calendar_where', $this->getNonLocationWhere() );

        $this->calendar_places = array();

        if(isset($this->dates[0])) {

            foreach($this->dates as $place) {

                $this->calendar_places[] = $place['calendar_where'];

            }

        }

        return $this->calendar_places;

    }

    function getDateSelect($name='', $year_min_max=NULL, $day=NULL, $month=NULL, $year=NULL) {

        $name = $this->getbasis.trim($name, '_');
        $bind = empty($name) ? '' : '_';

        $_day  = '<select name="'.$name.$bind.'day" id="'.$name.$bind.'day" class="cal-day">' . LF;
        for($x=1; $x<=31; $x++) {
            $_day .= '  <option value="'.$x.'"';
            if($x==$day) {
                $_day .= ' selected="selected"';
            }
            $_day .= '>';
            $_day .= sprintf($this->select_format_day, $x);
            $_day .= '</option>' . LF;
        }
        $_day .= '</select>';

        $_month = '<select name="'.$name.$bind.'month" id="'.$name.$bind.'month" class="cal-month">';
        for($x=1; $x<=12; $x++) {
            $_month .= '    <option value="'.$x.'"';
            if($x==$month) {
                $_month .= ' selected="selected"';
            }
            $_month .= '>';
            $_month .= $this->select_format_month == 'name' ? $this->select_month_option[$x] : sprintf($this->select_format_month, $x);
            $_month .= '</option>' . LF;
        }
        $_month .= '</select>';

        $_year = '<select name="'.$name.$bind.'year" id="'.$name.$bind.'year" class="cal-year">';
        $year_min = intval( empty($year_min_max['min']) ? date('Y', strtotime('-50 years')) : $year_min_max['min'] );
        $year_max = intval( empty($year_min_max['max']) ? $year_min+100 : $year_min_max['max'] );
        for($x=$year_min; $x<=$year_max; $x++) {
            $_year .= ' <option value="'.$x.'"';
            if($x==$year) {
                $_year .= ' selected="selected"';
            }
            $_year .= '>';
            $_year .= sprintf($this->select_format_year == 4 ? '%4s' : '%02s', $x);
            $_year .= '</option>' . LF;
        }
        $_year .= '</select>';

        $select = str_replace(array('D','M','Y'), array('[%%D%%]','[%%M%%]','[%%Y%%]'), $this->selector_format);
        $select = str_replace('[%%D%%]', $_day, $select);
        $select = str_replace('[%%M%%]', $_month, $select);
        $select = str_replace('[%%Y%%]', $_year, $select);

        if($name) {
            $select .= '<input type="hidden" name="'.$name.'" id="'.$name.'" value="" />';
        }

        return $select;
    }

}
