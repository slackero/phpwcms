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

$plugin['id'] = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

// process post form
if(isset($_POST['calendar_title'])) {

    $plugin['data'] = array(
        'calendar_id'           => intval($_POST['calendar_id']),
        'calendar_title'        => clean_slweg($_POST['calendar_title']),
        'calendar_created'      => date('Y-m-d H:i:s'),
        'calendar_changed'      => date('Y-m-d H:i:s'),
        'calendar_tag'          => clean_slweg($_POST['calendar_tag']),
        'calendar_lang'         => isset($_POST['calendar_lang']) ? preg_replace('/[^a-z\-]/', '', strtolower($_POST['calendar_lang'])) : '',
        'calendar_teaser'       => clean_slweg($_POST['calendar_teaser']),
        'calendar_text'         => slweg($_POST['calendar_text']),
        'calendar_object'       => array(),
        'calendar_status'       => empty($_POST['calendar_status']) ? 0 : 1,
        'calendar_start_date'   => clean_slweg($_POST['calendar_start_date']),
        'calendar_start_time'   => clean_slweg($_POST['calendar_start_time']),
        'calendar_end_date'     => clean_slweg($_POST['calendar_end_date']),
        'calendar_end_time'     => clean_slweg($_POST['calendar_end_time']),
        'calendar_allday'       => empty($_POST['calendar_allday']) ? 0 : 1,
        'calendar_range'        => intval($_POST['calendar_range']),
        'calendar_where'        => clean_slweg($_POST['calendar_where']),
        'calendar_refid'        => clean_slweg($_POST['calendar_refid']),
        'calendar_duplicate'    => empty($_POST['calendar_duplicate']) ? 0 : 1,
        'calendar_rangestart'   => clean_slweg($_POST['calendar_range_start']),
        'calendar_rangeend'     => clean_slweg($_POST['calendar_range_end']),
        'calendar_image'        => array(
            'id'        => intval($_POST['cnt_image_id']),
            'name'      => clean_slweg($_POST['cnt_image_name']),
            'zoom'      => empty($_POST['cnt_image_zoom']) ? 0 : 1,
            'lightbox'  => empty($_POST['cnt_image_lightbox']) ? 0 : 1,
            'caption'   => clean_slweg($_POST['cnt_image_caption']),
            'link'      =>clean_slweg($_POST['cnt_image_link'])
        )
    );

    if($plugin['data']['calendar_range'] > 16) {
        $plugin['data']['calendar_range'] = 0;
    }

    if(!$plugin['data']['calendar_range']) {
        $plugin['data']['calendar_rangestart']  = $plugin['data']['calendar_start_date'];
        $plugin['data']['calendar_rangeend']    = $plugin['data']['calendar_end_date'];
    }

    // clean up date/time
    include_once $phpwcms['modules'][$module]['path'].'inc/processing.datetime.inc.php';

    if(empty($plugin['data']['calendar_title'])) {
        $plugin['error']['calendar_title'] = 1;
    }

    if(!isset($glossary['error'])) {

        if($plugin['data']['calendar_duplicate']) {
            $plugin['data']['calendar_id'] = 0;
        }

        $plugin['data']['calendar_object']['image'] = $plugin['data']['calendar_image'];

        if($plugin['data']['calendar_id']) {

            // UPDATE
            $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_calendar SET ';

            $sql .= "calendar_created='".aporeplace($plugin['data']['calendar_created'])."', ";
            $sql .= "calendar_changed='".aporeplace($plugin['data']['calendar_changed'])."', ";
            $sql .= "calendar_status=".$plugin['data']['calendar_status'].", ";
            $sql .= "calendar_start='".aporeplace($plugin['data']['calendar_start'])."', ";
            $sql .= "calendar_end='".aporeplace($plugin['data']['calendar_end'])."', ";
            $sql .= "calendar_allday=".$plugin['data']['calendar_allday'].", ";
            $sql .= "calendar_range=".$plugin['data']['calendar_range'].", ";
            $sql .= "calendar_range_start='".aporeplace($plugin['data']['calendar_range_start'])."', ";
            $sql .= "calendar_range_end='".aporeplace($plugin['data']['calendar_range_end'])."', ";
            $sql .= "calendar_title='".aporeplace($plugin['data']['calendar_title'])."', ";
            $sql .= "calendar_where='".aporeplace($plugin['data']['calendar_where'])."', ";
            $sql .= "calendar_teaser='".aporeplace($plugin['data']['calendar_teaser'])."', ";
            $sql .= "calendar_text='".aporeplace($plugin['data']['calendar_text'])."', ";
            $sql .= "calendar_tag='".aporeplace($plugin['data']['calendar_tag'])."', ";
            $sql .= "calendar_object='".aporeplace(serialize($plugin['data']['calendar_object']))."', ";
            $sql .= "calendar_refid='".aporeplace($plugin['data']['calendar_refid'])."', ";
            $sql .= "calendar_lang='".aporeplace($plugin['data']['calendar_lang'])."' ";

            $sql .= "WHERE calendar_id=".$plugin['data']['calendar_id'];

            if(@_dbQuery($sql, 'UPDATE')) {

                _dbSaveCategories($plugin['data']['calendar_tag'], 'calendar', $plugin['data']['calendar_id'], ',');

                if(isset($_POST['save'])) {

                    headerRedirect(decode_entities(MODULE_HREF));

                }

            } else {

                $plugin['error']['update'] = _dbError();

            }

        } else {

            // INSERT
            $sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_calendar (';

            $sql .= 'calendar_created, calendar_changed, calendar_status, ';
            $sql .= 'calendar_start, calendar_end, calendar_allday, calendar_range, ';
            $sql .= 'calendar_range_start, calendar_range_end, calendar_title, ';
            $sql .= 'calendar_where, calendar_teaser, calendar_text, calendar_tag, ';
            $sql .= 'calendar_object, calendar_refid, calendar_lang) VALUES (';

            $sql .= "'".aporeplace($plugin['data']['calendar_created'])."', ";
            $sql .= "'".aporeplace($plugin['data']['calendar_changed'])."', ";
            $sql .= $plugin['data']['calendar_status'].", ";
            $sql .= "'".aporeplace($plugin['data']['calendar_start'])."', ";
            $sql .= "'".aporeplace($plugin['data']['calendar_end'])."', ";
            $sql .= $plugin['data']['calendar_allday'].", ";
            $sql .= $plugin['data']['calendar_range'].", ";
            $sql .= "'".aporeplace($plugin['data']['calendar_range_start'])."', ";
            $sql .= "'".aporeplace($plugin['data']['calendar_range_end'])."', ";
            $sql .= "'".aporeplace($plugin['data']['calendar_title'])."', ";
            $sql .= "'".aporeplace($plugin['data']['calendar_where'])."', ";
            $sql .= "'".aporeplace($plugin['data']['calendar_teaser'])."', ";
            $sql .= "'".aporeplace($plugin['data']['calendar_text'])."', ";
            $sql .= "'".aporeplace($plugin['data']['calendar_tag'])."', ";
            $sql .= "'".aporeplace(serialize($plugin['data']['calendar_object']))."', ";
            $sql .= "'".aporeplace($plugin['data']['calendar_refid'])."', ";
            $sql .= "'".aporeplace($plugin['data']['calendar_lang'])."'";

            $sql .= ')';

            if($sql = @_dbQuery($sql, 'INSERT')) {

                $plugin['data']['calendar_id'] = $sql['INSERT_ID'];

                _dbSaveCategories($plugin['data']['calendar_tag'], 'calendar', $plugin['data']['calendar_id'], ',');

                if(isset($_POST['save'])) {

                    headerRedirect(decode_entities(MODULE_HREF));

                } else {

                    headerRedirect(decode_entities(MODULE_HREF).'&edit='.$plugin['data']['calendar_id']);
                }

            } else {

                $plugin['error']['update'] = _dbError();

            }
        }
    }

}

// try to read entry from database
if($plugin['id'] && !isset($plugin['error'])) {

    $sql  = 'SELECT *, ';
    $sql .= "DATE_FORMAT(calendar_start, '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS calendar_start_date, ";
    $sql .= "DATE_FORMAT(calendar_end,   '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS calendar_end_date, ";
    $sql .= "DATE_FORMAT(calendar_start, '%H:%i') AS calendar_start_time, ";
    $sql .= "DATE_FORMAT(calendar_end,   '%H:%i') AS calendar_end_time, ";
    $sql .= "DATE_FORMAT(calendar_range_start, '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS calendar_rangestart, ";
    $sql .= "DATE_FORMAT(calendar_range_end, '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS calendar_rangeend ";
    $sql .= 'FROM '.DB_PREPEND.'phpwcms_calendar WHERE calendar_id='.$plugin['id'];
    $plugin['data'] = _dbQuery($sql);
    $plugin['data'] = $plugin['data'][0];

    $plugin['data']['calendar_object'] = @unserialize($plugin['data']['calendar_object']);

    if(is_array($plugin['data']['calendar_object'])) {
        if(isset($plugin['data']['calendar_object']['image'])) {
            $plugin['data']['calendar_image'] = $plugin['data']['calendar_object']['image'];
        }
    }

}

// default values
if(empty($plugin['data'])) {

    if(isset($_GET['defaultdate'])) {

        $plugin['default_date'] = explode('-', clean_slweg($_GET['defaultdate']));

        $plugin['default_date'][0]  = empty($plugin['default_date'][0]) ? gmdate('d') : $plugin['default_date'][0];
        $plugin['default_date'][1]  = empty($plugin['default_date'][1]) ? gmdate('m') : $plugin['default_date'][1];
        $plugin['default_date'][2]  = empty($plugin['default_date'][2]) ? gmdate('Y') : $plugin['default_date'][2];

        $plugin['default_date']     = gmmktime(0, 0, 0, $plugin['default_date'][1], $plugin['default_date'][0], $plugin['default_date'][2]);
        $plugin['default_date_end'] = $plugin['default_date']+3600;

        $plugin['default_date']     = gmdate('d'.$BLM['date_delimiter'].'m'.$BLM['date_delimiter'].'Y', $plugin['default_date']);
        $plugin['default_date_end'] = gmdate('d'.$BLM['date_delimiter'].'m'.$BLM['date_delimiter'].'Y', $plugin['default_date_end']);

    } else {

        $plugin['default_date']     = gmdate('d'.$BLM['date_delimiter'].'m'.$BLM['date_delimiter'].'Y');
        $plugin['default_date_end'] = gmdate('d'.$BLM['date_delimiter'].'m'.$BLM['date_delimiter'].'Y', time()+3600);

    }

    $plugin['data'] = array(
        'calendar_id'           => 0,
        'calendar_title'        => '',
        'calendar_created'      => '',
        'calendar_changed'      => gmdate('Y-m-d H:i:s'),
        'calendar_tag'          => '',
        'calendar_teaser'       => '',
        'calendar_text'         => '',
        'calendar_object'       => array(),
        'calendar_status'       => 0,
        'calendar_start_date'   => $plugin['default_date'],
        'calendar_end_date'     => $plugin['default_date_end'],
        'calendar_start_time'   => gmdate('H:00'),
        'calendar_end_time'     => gmdate('H:00', time()+3600),
        'calendar_allday'       => 0,
        'calendar_range'        => 0,
        'calendar_where'        => '',
        'calendar_refid'        => '',
        'calendar_duplicate'    => 0,
        'calendar_rangestart'   => '',
        'calendar_rangeend'     => '',
        'calendar_lang'         => ''
    );

}

if(!isset($plugin['data']['calendar_image'])) {
    $plugin['data']['calendar_image'] = array('id'=>0, 'name'=>'', 'zoom'=>0, 'lightbox'=>0, 'caption'=>'', 'link'=>'');
} else {
    $plugin['data']['calendar_image'] = array_merge(
        array('id'=>0, 'name'=>'', 'zoom'=>0, 'lightbox'=>0, 'caption'=>'', 'link'=>''),
        $plugin['data']['calendar_image']
    );
}
