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

// try

if(isset($_GET['edit'])) {
    $plugin['id'] = intval($_GET['edit']);
} else {
    $plugin['id'] = 0;
}

// process post form
if(isset($_POST['adcampaign_title'])) {

    $plugin['data'] = array(

        'adcampaign_id'         => intval($_POST['adcampaign_id']),
        'adcampaign_title'      => clean_slweg($_POST['adcampaign_title']),
        'adcampaign_created'    => date('Y-m-d H:i:s'),
        'adcampaign_changed'    => date('Y-m-d H:i:s'),
        'adcampaign_comment'    => clean_slweg($_POST['adcampaign_comment']),
        'adcampaign_data'       => array(

            'max_views'         => intval($_POST['adcampaign_max_views']),
            'max_click'         => intval($_POST['adcampaign_max_click']),
            'width'             => intval($_POST['adcampaign_width']),
            'height'            => intval($_POST['adcampaign_height']),
            'unique'            => empty($_POST['adcampaign_unique']) ? 0 : 1,
            'url'               => clean_slweg($_POST['adcampaign_url']),
            'target'            => clean_slweg($_POST['adcampaign_target'])

        ),
        'adcampaign_status'     => empty($_POST['adcampaign_status']) ? 0 : 1,
        'adcampaign_date_start' => clean_slweg($_POST['adcampaign_date_start']),
        'adcampaign_date_end'   => clean_slweg($_POST['adcampaign_date_end']),
        'adcampaign_time_start' => clean_slweg($_POST['adcampaign_time_start']),
        'adcampaign_time_end'   => clean_slweg($_POST['adcampaign_time_end']),
        'adcampaign_format'     => intval($_POST['adcampaign_format'])
    );


    if(empty($plugin['data']['adcampaign_title'])) {

        $plugin['error']['adcampaign_title'] = 1;

    }

}

// try to read entry from database
if($plugin['id'] && !isset($plugin['error'])) {

    $sql  = 'SELECT *,';
    $sql .= "DATE_FORMAT(adcampaign_datestart, '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS adcampaign_date_start, ";
    $sql .= "DATE_FORMAT(adcampaign_dateend,   '%d".$BLM['date_delimiter']."%m".$BLM['date_delimiter']."%Y') AS adcampaign_date_end, ";
    $sql .= "DATE_FORMAT(adcampaign_datestart, '%H:%i') AS adcampaign_time_start, ";
    $sql .= "DATE_FORMAT(adcampaign_dateend,   '%H:%i') AS adcampaign_time_end ";
    $sql .= 'FROM '.DB_PREPEND.'phpwcms_ads_campaign WHERE adcampaign_id='.$plugin['id'];
    $plugin['data'] = _dbQuery($sql);
    $plugin['data'] = $plugin['data'][0];
    $plugin['data']['adcampaign_data'] = @unserialize($plugin['data']['adcampaign_data']);
    if(!is_array($plugin['data']['adcampaign_data'])) {
        $plugin['data']['adcampaign_data'] = array(

            'max_views'         => 0,
            'max_click'         => 0,
            'unique'            => 0,
            'width'             => '',
            'height'            => '',
            'url'               => '',
            'target'            => ''

        );
    }

}

// default values
if(empty($plugin['data'])) {

    $plugin['data'] = array(

        'adcampaign_id'         => 0,
        'adcampaign_title'      => '',
        'adcampaign_created'    => '',
        'adcampaign_changed'    => date('Y-m-d H:i:s'),
        'adcampaign_comment'    => '',
        'adcampaign_data'       => array(

            'max_views'         => 0,
            'max_click'         => 0,
            'unique'            => 0,
            'width'             => '',
            'height'            => '',
            'url'               => '',
            'target'            => ''

        ),
        'adcampaign_status'     => 0,
        'adcampaign_date_start' => '',
        'adcampaign_date_end'   => '',
        'adcampaign_time_start' => '00:00',
        'adcampaign_time_end'   => '23:59',
        'adcampaign_format'     => 0

    );

}
