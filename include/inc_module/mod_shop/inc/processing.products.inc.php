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


    $plugin['data']['shopprod_id']	= intval($_GET['edit']);

    if(isset($_POST['shopprod_id'])) {

        // check if form should be closed only -> and back to listing mode
        if( isset($_POST['close']) ) {
            headerRedirect( shop_url(get_token_get_string().'&controller=prod', '') );
        }

        $plugin['data']['shopprod_changedate']		= time();

        $plugin['data']['shopprod_name1']			= clean_slweg($_POST['shopprod_name1']);
        $plugin['data']['shopprod_name2']			= clean_slweg($_POST['shopprod_name2']);

        $plugin['data']['shopprod_ordernumber']		= clean_slweg($_POST['shopprod_ordernumber']);
        $plugin['data']['shopprod_model']			= clean_slweg($_POST['shopprod_model']);

        $plugin['data']['shopprod_price']			= clean_slweg($_POST['shopprod_price']);
        $plugin['data']['shopprod_vat']				= abs(floatval($_POST['shopprod_vat']));
        $plugin['data']['shopprod_weight']			= clean_slweg($_POST['shopprod_weight']);
        $plugin['data']['shopprod_unit']			= clean_slweg($_POST['shopprod_unit']);
        $plugin['data']['shopprod_inventory']		= intval($_POST['shopprod_inventory']);

        $plugin['data']['shopprod_size']			= clean_slweg($_POST['shopprod_size']);
        $plugin['data']['shopprod_color']			= clean_slweg($_POST['shopprod_color']);

        $plugin['data']['shopprod_size']			= explode(LF, $plugin['data']['shopprod_size']);
        if(count($plugin['data']['shopprod_size']) > 3) {
            $_temp = array_shift($plugin['data']['shopprod_size']) . LF;
            natsort($plugin['data']['shopprod_size']);
        } else {
            $_temp = '';
        }
        $plugin['data']['shopprod_size']			= $_temp . implode(LF, $plugin['data']['shopprod_size']);

        $plugin['data']['shopprod_color']			= explode(LF, $plugin['data']['shopprod_color']);
        if(count($plugin['data']['shopprod_color']) > 3) {
            $_temp = array_shift($plugin['data']['shopprod_color']) . LF;
            natsort($plugin['data']['shopprod_color']);
        } else {
            $_temp = '';
        }
        $plugin['data']['shopprod_color']			= $_temp . implode(LF, $plugin['data']['shopprod_color']);


        $plugin['data']['shopprod_netgross']		= empty($_POST['shopprod_netgross']) ? 0 : 1; //0 = net, 1 = gross

        $plugin['data']['shopprod_description0']	= slweg($_POST['shopprod_description0']);
        $plugin['data']['shopprod_description1']	= slweg($_POST['shopprod_description1']);
        $plugin['data']['shopprod_description2']	= clean_slweg($_POST['shopprod_description2']);
        $plugin['data']['shopprod_description3']	= clean_slweg($_POST['shopprod_description3']);

        $plugin['data']['shopprod_url']				= clean_slweg($_POST['shopprod_url']);

        // Shop product language
        $plugin['data']['shopprod_lang']			= empty($_POST['shopprod_lang']) ? '' : strtolower(clean_slweg($_POST['shopprod_lang']));

        $plugin['data']['shopprod_status']			= empty($_POST['shopprod_status']) ? 0 : 1;
        $plugin['data']['shopprod_listall']			= empty($_POST['shopprod_listall']) ? 0 : 1;
        $plugin['data']['shopprod_overwrite_meta']	= empty($_POST['shopprod_overwrite_meta']) ? 0 : 1;
        $plugin['data']['shopprod_opengraph']		= empty($_POST['shopprod_opengraph']) ? 0 : 1;

        $plugin['data']['shopprod_category']		= isset($_POST['shopprod_category']) && is_array($_POST['shopprod_category']) ? $_POST['shopprod_category'] : array();

        if(!$plugin['data']['shopprod_name1']) {
            $plugin['error']['shopprod_name1'] = 'No name';
        }
        if(!$plugin['data']['shopprod_ordernumber']) {
            $plugin['error']['shopprod_ordernumber'] = 'No order number';
        } else {
            $sql  = 'SELECT COUNT(shopprod_id) FROM '.DB_PREPEND.'phpwcms_shop_products WHERE ';
            if($plugin['data']['shopprod_id']) $sql .= 'shopprod_id != '.$plugin['data']['shopprod_id'].' AND ';
            $sql .= "shopprod_ordernumber LIKE '" . aporeplace($plugin['data']['shopprod_ordernumber']) . "'";
            //if($plugin['data']['shopprod_lang']) {
                $sql .= " AND shopprod_lang='" . aporeplace($plugin['data']['shopprod_lang']) . "'";
            //}
            if(_dbCount($sql)) $plugin['error']['shopprod_ordernumber'] = 'Unique order number necessary';
        }

        $plugin['data']['shopprod_price']			= str_replace($BLM['thousands_sep'], '', $plugin['data']['shopprod_price']);
        $plugin['data']['shopprod_price']			= str_replace($BLM['dec_point'], '.', $plugin['data']['shopprod_price']);
        $plugin['data']['shopprod_price']			= floatval($plugin['data']['shopprod_price']);
        if(abs($plugin['data']['shopprod_price']) > 10000000000) {
            $plugin['error']['shopprod_price'] = 'Check price';
        }

        $plugin['data']['shopprod_weight']			= str_replace($BLM['thousands_sep'], '', $plugin['data']['shopprod_weight']);
        $plugin['data']['shopprod_weight']			= str_replace($BLM['dec_point'], '.', $plugin['data']['shopprod_weight']);
        $plugin['data']['shopprod_weight']			= floatval($plugin['data']['shopprod_weight']);

        $plugin['data']['shopprod_tag']				= strtolower( preg_replace('/[^0-9a-z, \-_]/i', '', phpwcms_remove_accents($_POST['shopprod_tag']) ) );
        $plugin['data']['shopprod_tag']				= implode(', ', convertStringToArray($plugin['data']['shopprod_tag']));


        // Images
        $plugin['data']['shopprod_caption']			= clean_slweg($_POST["shopprod_caption"], 0 , false);
        $plugin['data']['shopprod_caption'] 		= explode(LF, $plugin['data']['shopprod_caption']);

        $plugin['data']['shopprod_images']			= isset($_POST['shopprod_images']) && is_array($_POST['shopprod_images']) ? $_POST['shopprod_images'] : array();

        if(is_array($plugin['data']['shopprod_images']) && count($plugin['data']['shopprod_images'])) {

            $plugin['data']['shopprod_images'] = array_map('intval', $plugin['data']['shopprod_images']);
            $plugin['data']['shopprod_images'] = array_diff($plugin['data']['shopprod_images'], array(0,'',NULL,false));

            if(count($plugin['data']['shopprod_images'])) {

                $img_all = _dbQuery('SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE f_id IN ('.implode(',', $plugin['data']['shopprod_images']).')');

                // take all values from db
                $temp_img_row = array();
                foreach($img_all as $value) {
                    $temp_img_row[ $value['f_id'] ] = $value;
                }

                $img_all = array();

                // now run though image result - but keep sorting
                foreach($plugin['data']['shopprod_images'] as $key => $value) {
                    if(isset($temp_img_row[$value])) {

                        $img_all[$key]['f_id']		= $temp_img_row[$value]['f_id'];
                        $img_all[$key]['f_name']	= $temp_img_row[$value]['f_name'];
                        $img_all[$key]['f_hash']	= $temp_img_row[$value]['f_hash'];
                        $img_all[$key]['f_ext']		= $temp_img_row[$value]['f_ext'];
                        $img_all[$key]['caption']	= isset($plugin['data']['shopprod_caption'][$key]) ? trim($plugin['data']['shopprod_caption'][$key]) : '';

                    }
                }

                $plugin['data']['shopprod_caption']	= array();
                $plugin['data']['shopprod_images']	= $img_all;
                unset($img_all);

            }
        }

        // Attachments
        $plugin['data']['shopprod_filecaption']		= clean_slweg($_POST["shopprod_filecaption"], 0 , false);
        $plugin['data']['shopprod_filecaption'] 	= explode(LF, $plugin['data']['shopprod_filecaption']);

        $plugin['data']['shopprod_files']			= isset($_POST['shopprod_files']) && is_array($_POST['shopprod_files']) ? $_POST['shopprod_files'] : array();

        if(is_array($plugin['data']['shopprod_files']) && count($plugin['data']['shopprod_files'])) {

            $plugin['data']['shopprod_files'] = array_map('intval', $plugin['data']['shopprod_files']);
            $plugin['data']['shopprod_files'] = array_diff($plugin['data']['shopprod_files'], array(0,'',NULL,false));

            if(count($plugin['data']['shopprod_files'])) {

                $img_all = _dbQuery('SELECT * FROM '.DB_PREPEND.'phpwcms_file WHERE f_id IN ('.implode(',', $plugin['data']['shopprod_files']).')');

                // take all values from db
                $temp_img_row = array();
                foreach($img_all as $value) {
                    $temp_img_row[ $value['f_id'] ] = $value;
                }

                $img_all = array();

                // now run though image result - but keep sorting
                foreach($plugin['data']['shopprod_files'] as $key => $value) {
                    if(isset($temp_img_row[$value])) {

                        $img_all[$key]['f_id']		= $temp_img_row[$value]['f_id'];
                        $img_all[$key]['f_name']	= $temp_img_row[$value]['f_name'];
                        $img_all[$key]['f_hash']	= $temp_img_row[$value]['f_hash'];
                        $img_all[$key]['f_ext']		= $temp_img_row[$value]['f_ext'];
                        $img_all[$key]['caption']	= isset($plugin['data']['shopprod_filecaption'][$key]) ? trim($plugin['data']['shopprod_filecaption'][$key]) : '';

                    }
                }

                $plugin['data']['shopprod_filecaption']	= array();
                $plugin['data']['shopprod_files']		= $img_all;
                unset($img_all);

            }
        }

        // Duplicate it?
        $plugin['data']['shopprod_duplicate'] = empty($_POST['shopprod_duplicate']) ? 0 : 1;

        $plugin['data']['shopprod_on_request'] = empty($_POST['shopprod_on_request']) ? 0 : 1;;
        $plugin['data']['shopprod_on_request_url'] = clean_slweg($_POST['shopprod_on_request_url']);

        if(empty($plugin['error'] )) {

            // Update
            if( $plugin['data']['shopprod_id'] && $plugin['data']['shopprod_duplicate'] == 0 ) {

                $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_shop_products SET ';

                $sql .= "shopprod_changedate = '".aporeplace( date('Y-m-d H:i:s', $plugin['data']['shopprod_changedate']) )."', ";
                $sql .= "shopprod_status = ".$plugin['data']['shopprod_status'].", ";

                $sql .= "shopprod_ordernumber = '".aporeplace($plugin['data']['shopprod_ordernumber'])."', ";
                $sql .= "shopprod_model = '".aporeplace($plugin['data']['shopprod_model'])."', ";

                $sql .= "shopprod_tag = '".aporeplace($plugin['data']['shopprod_tag'])."', ";

                $sql .= "shopprod_vat = '".aporeplace($plugin['data']['shopprod_vat'])."', ";
                $sql .= "shopprod_netgross = '".aporeplace($plugin['data']['shopprod_netgross'])."', ";
                $sql .= "shopprod_price = '".aporeplace($plugin['data']['shopprod_price'])."', ";

                $sql .= "shopprod_name1 = '".aporeplace($plugin['data']['shopprod_name1'])."', ";
                $sql .= "shopprod_name2 = '".aporeplace($plugin['data']['shopprod_name2'])."', ";

                $sql .= "shopprod_description0 = '".aporeplace($plugin['data']['shopprod_description0'])."', ";
                $sql .= "shopprod_description1 = '".aporeplace($plugin['data']['shopprod_description1'])."', ";
                $sql .= "shopprod_description2 = '".aporeplace($plugin['data']['shopprod_description2'])."', ";
                $sql .= "shopprod_description3 = '".aporeplace($plugin['data']['shopprod_description3'])."', ";

                $sql .= "shopprod_var = '".aporeplace(	serialize( array(
                    'images' => $plugin['data']['shopprod_images'],
                    'url' => $plugin['data']['shopprod_url'],
                    'files' => $plugin['data']['shopprod_files'],
                    'request' => $plugin['data']['shopprod_on_request'],
                    'request_url' => $plugin['data']['shopprod_on_request_url']
                ) ) ) . "', ";

                $sql .= "shopprod_category = '".aporeplace( implode(',', $plugin['data']['shopprod_category']) )."', ";

                $sql .= "shopprod_weight = '".aporeplace($plugin['data']['shopprod_weight'])."', ";
                $sql .= "shopprod_size = '".aporeplace($plugin['data']['shopprod_size'])."', ";
                $sql .= "shopprod_color = '".aporeplace($plugin['data']['shopprod_color'])."', ";
                $sql .= "shopprod_listall = '".aporeplace($plugin['data']['shopprod_listall'])."', ";
                $sql .= "shopprod_lang = '".aporeplace($plugin['data']['shopprod_lang'])."', ";
                $sql .= "shopprod_overwrite_meta = ".$plugin['data']['shopprod_overwrite_meta'].", ";
                $sql .= "shopprod_opengraph = ".$plugin['data']['shopprod_opengraph'].", ";
                $sql .= "shopprod_unit = "._dbEscape($plugin['data']['shopprod_unit']).", ";
                $sql .= "shopprod_inventory = "._dbEscape($plugin['data']['shopprod_inventory'])." ";

                $sql .= "WHERE shopprod_id = " . $plugin['data']['shopprod_id'];

                _dbQuery($sql, 'UPDATE');

            // INSERT
            } else {

                $sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_shop_products (';
                $sql .= 'shopprod_createdate, shopprod_changedate, shopprod_status, shopprod_ordernumber, shopprod_model, ';
                $sql .= 'shopprod_name1, shopprod_name2, shopprod_tag, shopprod_vat, shopprod_netgross, shopprod_price, ';
                $sql .= 'shopprod_maxrebate, shopprod_description0, shopprod_description1, shopprod_description2, ';
                $sql .= 'shopprod_description3, shopprod_var, shopprod_category, shopprod_weight, shopprod_size, shopprod_color, ';
                $sql .= 'shopprod_listall, shopprod_lang, shopprod_overwrite_meta, shopprod_opengraph, shopprod_unit,';
                $sql .= 'shopprod_inventory) VALUES (';
                $sql .= "'".aporeplace( date('Y-m-d H:i:s', $plugin['data']['shopprod_changedate']) )."', ";
                $sql .= "'".aporeplace( date('Y-m-d H:i:s', $plugin['data']['shopprod_changedate']) )."', ";
                $sql .= $plugin['data']['shopprod_status'].", ";

                $sql .= "'".aporeplace($plugin['data']['shopprod_ordernumber'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_model'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_name1'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_name2'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_tag'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_vat'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_netgross'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_price'])."', ";
                $sql .= "'".aporeplace('0')."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_description0'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_description1'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_description2'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_description3'])."', ";

                $sql .= "'".aporeplace(	serialize( array(
                    'images' => $plugin['data']['shopprod_images'],
                    'url' => $plugin['data']['shopprod_url'],
                    'files' => $plugin['data']['shopprod_files'],
                    'request' => $plugin['data']['shopprod_on_request'],
                    'request_url' => $plugin['data']['shopprod_on_request_url']
                ) ) )."', ";

                $sql .= "'".aporeplace( implode(',', $plugin['data']['shopprod_category']) ) ."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_weight'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_size'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_color'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_listall'])."', ";
                $sql .= "'".aporeplace($plugin['data']['shopprod_lang'])."', ";
                $sql .= $plugin['data']['shopprod_overwrite_meta'].', ';
                $sql .= $plugin['data']['shopprod_opengraph'].', ';
                $sql .= _dbEscape($plugin['data']['shopprod_unit']).', ';
                $sql .= _dbEscape($plugin['data']['shopprod_inventory']);
                $sql .= ')';

                $result = _dbQuery($sql, 'INSERT');

                if( !empty($result['INSERT_ID']) ) {
                    $plugin['data']['shopprod_id']	= $result['INSERT_ID'];
                }

            }

            // save and back to listing mode
            if( isset($_POST['save']) ) {
                headerRedirect( shop_url(get_token_get_string().'&controller=prod', '') );
            } else {
                headerRedirect( shop_url(get_token_get_string().'&controller=prod&edit='.$plugin['data']['shopprod_id'], '') );
            }

        }


    } elseif( $plugin['data']['shopprod_id'] == 0 ) {

        $plugin['data']['shopprod_id']				= 0;
        $plugin['data']['shopprod_changedate']		= time();
        $plugin['data']['shopprod_name1']			= '';
        $plugin['data']['shopprod_name2']			= '';
        $plugin['data']['shopprod_ordernumber']		= '';
        $plugin['data']['shopprod_model']			= '';
        $plugin['data']['shopprod_description0']	= '';
        $plugin['data']['shopprod_description1']	= '';
        $plugin['data']['shopprod_description2']	= '';
        $plugin['data']['shopprod_description3']	= '';
        $plugin['data']['shopprod_status']			= 1;
        $plugin['data']['shopprod_price']			= 0;
        $plugin['data']['shopprod_netgross']		= 0;
        $plugin['data']['shopprod_vat']				= 0;
        $plugin['data']['shopprod_tag']				= '';
        $plugin['data']['shopprod_category']		= array();
        $plugin['data']['shopprod_var']				= array();
        $plugin['data']['shopprod_images']			= array();
        $plugin['data']['shopprod_caption']			= array();
        $plugin['data']['shopprod_files']			= array();
        $plugin['data']['shopprod_filecaption']		= array();
        $plugin['data']['shopprod_weight']			= 0;
        $plugin['data']['shopprod_size']			= '';
        $plugin['data']['shopprod_color']			= '';
        $plugin['data']['shopprod_url']				= '';
        $plugin['data']['shopprod_listall']			= 0;
        $plugin['data']['shopprod_lang']			= '';
        $plugin['data']['shopprod_overwrite_meta']	= 1;
        $plugin['data']['shopprod_opengraph']		= empty($phpwcms['set_sociallink']['shop']) ? 0 : 1;
        $plugin['data']['shopprod_unit']			= '';
        $plugin['data']['shopprod_inventory']		= 0;
        $plugin['data']['shopprod_on_request']		= 0;
        $plugin['data']['shopprod_on_request_url']	= '';

    } else {

        $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_shop_products WHERE ';
        $sql .= "shopprod_id = " . $plugin['data']['shopprod_id'] . ' LIMIT 1';

        $plugin['data'] = _dbQuery($sql);

        if( isset($plugin['data'][0]) ) {
            $plugin['data'] = $plugin['data'][0];

            $plugin['data']['shopprod_changedate']	= strtotime($plugin['data']['shopprod_changedate']);
            $plugin['data']['shopprod_category']	= convertStringToArray($plugin['data']['shopprod_category']);

            $plugin['data']['shopprod_var']			= @unserialize($plugin['data']['shopprod_var']);
            if(isset($plugin['data']['shopprod_var']['images']) && is_array($plugin['data']['shopprod_var']['images'])) {
                $plugin['data']['shopprod_images']	= $plugin['data']['shopprod_var']['images'];
            } else {
                $plugin['data']['shopprod_images']	= array();
            }
            if(isset($plugin['data']['shopprod_var']['files']) && is_array($plugin['data']['shopprod_var']['files'])) {
                $plugin['data']['shopprod_files']	= $plugin['data']['shopprod_var']['files'];
            } else {
                $plugin['data']['shopprod_files']	= array();
            }
            $plugin['data']['shopprod_caption']		= array();
            $plugin['data']['shopprod_filecaption']	= array();
            $plugin['data']['shopprod_url']			= isset($plugin['data']['shopprod_var']['url']) ? $plugin['data']['shopprod_var']['url'] : '';
            $plugin['data']['shopprod_unit']		= isset($plugin['data']['shopprod_unit']) ? $plugin['data']['shopprod_unit'] : '';
            $plugin['data']['shopprod_opengraph']	= empty($plugin['data']['shopprod_opengraph']) ? 0 : 1;
            $plugin['data']['shopprod_overwrite_meta']	= empty($plugin['data']['shopprod_overwrite_meta']) ? 0 : 1;

            $plugin['data']['shopprod_on_request']	= empty($plugin['data']['shopprod_var']['request']) ? 0 : 1;
            if (empty($plugin['data']['shopprod_var']['request_url'])) {
                $plugin['data']['shopprod_on_request_url'] = '';
            } else {
                $plugin['data']['shopprod_on_request_url'] = $plugin['data']['shopprod_var']['request_url'];
            }

        } else {
            headerRedirect( shop_url(get_token_get_string().'&controller=prod', '') );
        }

    }

    $sql  = 'SELECT C1.cat_id, C1.cat_name, C1.cat_pid, C1.cat_status, ';
    $sql .= "IFNULL(CONCAT(C2.cat_name, '>', C1.cat_name), C1.cat_name) AS category ";
    $sql .= 'FROM '.DB_PREPEND.'phpwcms_categories C1 ';
    $sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_categories C2 ';
    $sql .= 'ON C1.cat_pid=C2.cat_id ';
    $sql .= "WHERE C1.cat_type='module_shop' AND C1.cat_status!=9 ";
    $sql .= 'ORDER BY category';
    $plugin['data']['categories'] = _dbQuery($sql);

} elseif($action == 'status') {

    list($plugin['data']['shopprod_id'], $plugin['data']['shopprod_status']) = explode( '-', $_GET['status'] );

    $plugin['data']['shopprod_id']		= intval($plugin['data']['shopprod_id']);
    $plugin['data']['shopprod_status']	= empty($plugin['data']['shopprod_status']) ? 1 : 0;

    $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_shop_products SET ';
    $sql .= "shopprod_status = ".$plugin['data']['shopprod_status']." ";
    $sql .= "WHERE shopprod_id = " . $plugin['data']['shopprod_id'];

    _dbQuery($sql, 'UPDATE');

    headerRedirect( shop_url(get_token_get_string().'&controller=prod', '') );

} elseif($action == 'delete') {

    $plugin['data']['shopprod_id']		= intval($_GET['delete']);

    $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_shop_products SET ';
    $sql .= "shopprod_status = 9 ";
    $sql .= "WHERE shopprod_id = " . $plugin['data']['shopprod_id'];

    _dbQuery($sql, 'UPDATE');

    headerRedirect( shop_url(get_token_get_string().'&controller=prod', '') );

}
