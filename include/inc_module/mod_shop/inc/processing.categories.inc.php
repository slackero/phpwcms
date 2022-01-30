<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.org>
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


if($action == 'edit') {


    $plugin['data']['cat_id']	= intval($_GET['edit']);

    if( isset($_POST['cat_id']) ) {

        // check if form should be closed only -> and back to listing mode
        if( isset($_POST['close']) ) {
            headerRedirect( shop_url(get_token_get_string().'&controller=cat', '') );
        }

        $plugin['data']['cat_changedate']	= time();
        $plugin['data']['cat_name']			= clean_slweg($_POST['cat_name']);
        $plugin['data']['cat_info']			= clean_slweg($_POST['cat_info']);
        $plugin['data']['cat_status']		= empty($_POST['cat_status']) ? 0 : 1;
        $plugin['data']['cat_pid']			= intval($_POST['cat_pid']);
        $plugin['data']['cat_sort']			= intval($_POST['cat_sort']);

        if(!$plugin['data']['cat_name']) {
            $plugin['error']['cat_name'] = 'No name';
        } else {
            $sql  = 'SELECT COUNT(cat_id) FROM '.DB_PREPEND.'phpwcms_categories WHERE ';
            $sql .= "cat_type='module_shop' AND cat_status != 9 AND cat_name LIKE '". aporeplace($plugin['data']['cat_name']) ."'";
            $sql .= $plugin['data']['cat_id'] ? ' AND cat_id != ' . $plugin['data']['cat_id'] : '';
            if( _dbQuery($sql, 'COUNT') ) {
                $plugin['error']['cat_name'] = 'Duplicate category name';
            }
        }

        if( empty($plugin['error'] )) {

            // Update
            if( $plugin['data']['cat_id'] ) {

                $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_categories SET ';
                $sql .= "cat_changedate = '".aporeplace( date('Y-m-d H:i:s', $plugin['data']['cat_changedate']) )."', ";
                $sql .= "cat_pid = ".$plugin['data']['cat_pid'].", ";
                $sql .= "cat_status = ".$plugin['data']['cat_status'].", ";
                $sql .= "cat_name = '".aporeplace($plugin['data']['cat_name'])."', ";
                $sql .= "cat_info = '".aporeplace($plugin['data']['cat_info'])."', ";
                $sql .= "cat_sort = ".$plugin['data']['cat_sort']." ";
                $sql .= "WHERE cat_type='module_shop' AND cat_id = " . $plugin['data']['cat_id'];

                _dbQuery($sql, 'UPDATE');

            // INSERT
            } else {

                $sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_categories (';
                $sql .= 'cat_type, cat_pid, cat_createdate, cat_changedate, cat_status, cat_name, cat_info, cat_sort';
                $sql .= ') VALUES (';
                $sql .= "'module_shop', ";
                $sql .= $plugin['data']['cat_pid'].', ';
                $sql .= "'".aporeplace( date('Y-m-d H:i:s', $plugin['data']['cat_changedate']) )."', ";
                $sql .= "'".aporeplace( date('Y-m-d H:i:s', $plugin['data']['cat_changedate']) )."', ";
                $sql .= $plugin['data']['cat_status'].", ";
                $sql .= "'".aporeplace($plugin['data']['cat_name'])."', ";
                $sql .= "'".aporeplace($plugin['data']['cat_info'])."',";
                $sql .= $plugin['data']['cat_sort'];
                $sql .= ')';

                $result = _dbQuery($sql, 'INSERT');

                if( !empty($result['INSERT_ID']) ) {
                    $plugin['data']['cat_id']	= $result['INSERT_ID'];
                }

            }

            // save and back to listing mode
            if( isset($_POST['save']) ) {
                headerRedirect( shop_url(get_token_get_string().'&controller=cat', '') );
            } else {
                headerRedirect( shop_url(get_token_get_string().'&controller=cat&edit='.$plugin['data']['cat_id'], '') );
            }

        }


    } elseif( $plugin['data']['cat_id'] == 0 ) {

        $plugin['data']['cat_id']			= 0;
        $plugin['data']['cat_pid']			= 0;
        $plugin['data']['cat_changedate']	= time();
        $plugin['data']['cat_name']			= '';
        $plugin['data']['cat_info']			= '';
        $plugin['data']['cat_status']		= 1;
        $plugin['data']['cat_sort']			= 0;

    } else {

        $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_categories WHERE ';
        $sql .= "cat_type='module_shop' AND cat_id = " . $plugin['data']['cat_id'] . ' LIMIT 1';

        $plugin['data'] = _dbQuery($sql);

        if( isset($plugin['data'][0]) ) {
            $plugin['data'] = $plugin['data'][0];

            $plugin['data']['cat_changedate'] = strtotime($plugin['data']['cat_changedate']);

        } else {
            headerRedirect( shop_url(get_token_get_string().'&controller=cat', '') );
        }

    }

} elseif($action == 'status') {

    list($plugin['data']['cat_id'], $plugin['data']['cat_status']) = explode( '-', $_GET['status'] );

    $plugin['data']['cat_id']		= intval($plugin['data']['cat_id']);
    $plugin['data']['cat_status']	= empty($plugin['data']['cat_status']) ? 1 : 0;

    $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_categories SET ';
    $sql .= "cat_status = ".$plugin['data']['cat_status']." ";
    $sql .= "WHERE cat_type='module_shop' AND cat_id = " . $plugin['data']['cat_id'];

    _dbQuery($sql, 'UPDATE');

    headerRedirect( shop_url(get_token_get_string().'&controller=cat', '') );

} elseif($action == 'delete') {

    $plugin['data']['cat_id']		= intval($_GET['delete']);

    $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_categories SET ';
    $sql .= "cat_status = 9 ";
    $sql .= "WHERE cat_type='module_shop' AND ";
    $sql .= "(cat_id = " . $plugin['data']['cat_id'] . " OR cat_pid = " . $plugin['data']['cat_id'] . ")";

    _dbQuery($sql, 'UPDATE');

    headerRedirect( shop_url(get_token_get_string().'&controller=cat', '') );

}
