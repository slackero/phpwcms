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
    $plugin['id']       = intval($_GET['edit']);
} else {
    $plugin['id']       = 0;
}


// process post form
if(isset($_POST['adplace_title'])) {

    $plugin['data'] = array(

                'adplace_id'            => intval($_POST['adplace_id']),
                'adplace_title'         => clean_slweg($_POST['adplace_title']),
                'adplace_created'       => date('Y-m-d H:i:s'),
                'adplace_changed'       => date('Y-m-d H:i:s'),
                'adplace_status'        => empty($_POST['adplace_status']) ? 0 : 1,
                'adplace_format'        => intval($_POST['adplace_format']),
                'adplace_width'         => intval($_POST['adplace_width']),
                'adplace_height'        => intval($_POST['adplace_height']),
                'adplace_prefix'        => slweg($_POST['adplace_prefix']),
                'adplace_suffix'        => slweg($_POST['adplace_suffix'])

                            );


    if(empty($plugin['data']['adplace_title'])) {

        $plugin['error']['adplace_title'] = 1;

    }

    if(empty($plugin['data']['adplace_format'])) {

        $plugin['error']['adplace_format'] = 1;

    }

    if(!isset($plugin['error'])) {

        if($plugin['data']['adplace_id']) {

            // UPDATE
            $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_ads_place SET ';

            $sql .= "adplace_changed='".    aporeplace($plugin['data']['adplace_changed'])  ."', ";
            $sql .= "adplace_status=".      $plugin['data']['adplace_status']               .", ";
            $sql .= "adplace_title='".      aporeplace($plugin['data']['adplace_title'])    ."', ";
            $sql .= "adplace_format=".      $plugin['data']['adplace_format']               .", ";
            $sql .= "adplace_width=".       $plugin['data']['adplace_width']                .", ";
            $sql .= "adplace_height=".      $plugin['data']['adplace_height']               .", ";
            $sql .= "adplace_prefix='".     aporeplace($plugin['data']['adplace_prefix'])   ."', ";
            $sql .= "adplace_suffix='".     aporeplace($plugin['data']['adplace_suffix'])   ."' ";

            $sql .= "WHERE adplace_id=".$plugin['data']['adplace_id'];

            if(@_dbQuery($sql, 'UPDATE')) {

                if(isset($_POST['save'])) {

                    headerRedirect(decode_entities(MODULE_HREF).'&listadplace=1');

                }

            } else {

                $plugin['error']['update'] = _dbError();

            }


        } else {

            // INSERT
            $sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_ads_place (';
            $sql .= 'adplace_created, adplace_changed, adplace_status, adplace_title, ';
            $sql .= 'adplace_format, adplace_width, adplace_height, adplace_prefix, adplace_suffix';
            $sql .= ') VALUES (';
            $sql .= "'".aporeplace($plugin['data']['adplace_created'])  ."', ";
            $sql .= "'".aporeplace($plugin['data']['adplace_changed'])  ."', ";
            $sql .=     $plugin['data']['adplace_status']               .", ";
            $sql .= "'".aporeplace($plugin['data']['adplace_title'])    ."', ";
            $sql .=     $plugin['data']['adplace_format']               .", ";
            $sql .=     $plugin['data']['adplace_width']                .", ";
            $sql .=     $plugin['data']['adplace_height']               .", ";
            $sql .= "'".aporeplace($plugin['data']['adplace_prefix'])   ."', ";
            $sql .= "'".aporeplace($plugin['data']['adplace_suffix'])   ."'";
            $sql .= ')';

            if(@_dbQuery($sql, 'INSERT')) {

                if(isset($_POST['save'])) {

                    headerRedirect(decode_entities(MODULE_HREF).'&listadplace=1');

                }

            } else {

                $plugin['error']['update'] = _dbError();

            }


        }
    }

}

// try to read entry from database
if($plugin['id'] && !isset($plugin['error'])) {

    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_ads_place WHERE adplace_id='.$plugin['id'];
    $plugin['data'] = _dbQuery($sql);
    $plugin['data'] = $plugin['data'][0];

}

// default values
if(empty($plugin['data'])) {

    $plugin['data'] = array(

                'adplace_id'            => 0,
                'adplace_title'         => '',
                'adplace_created'       => '',
                'adplace_changed'       => date('Y-m-d H:i:s'),
                'adplace_status'        => 0,
                'adplace_format'        => 0,
                'adplace_width'         => 0,
                'adplace_height'        => 0,
                'adplace_prefix'        => '',
                'adplace_suffix'        => ''

                            );

}
