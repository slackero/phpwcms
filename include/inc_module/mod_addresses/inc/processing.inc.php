<?php
// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// set fields
$plugin['fields'] = array(
    'detail_varchar2'   => 'CHECK',  // Repräsentanz
    'detail_company'    => 'STRING', // Firma
    'detail_varchar4'   => 'STRING', // Standort
    'detail_title'      => 'STRING', // Anrede
    'detail_firstname'  => 'STRING', // Vorname
    'detail_lastname'   => 'STRING', // Name
    'detail_street'     => 'STRING', // Straße
    'detail_add'        => 'STRING', // Strasse2
    'detail_city'       => 'STRING', // Ort
    'detail_zip'        => 'STRING', // PLZ
    'detail_region'     => 'STRING',
    'detail_country'    => 'SELECT', // Land
    'detail_text2'      => 'MULTICHECK', // Zuständig auch für folgende Länder
    'detail_fon'        => 'STRING', // Tel.-Nr.
    'detail_fax'        => 'STRING', // Fax
    'detail_mobile'     => 'STRING',
    'detail_website'    => 'STRING', // Internet
    'detail_email'      => 'STRING', // Email
    'detail_varchar1'   => 'SELECT', // Language
    'detail_text3'      => 'FILES', // Files
    'detail_text1'      => 'TEXTAREA', // Anmerkungen
    'detail_varchar3'   => 'STRING', // Postleitzahl-Gebiet
    'detail_float1'     => 'FLOAT',  // Geokoordinate Latitude f
    'detail_float2'     => 'FLOAT',  // Geokoordinate Longitude Lambda
    'detail_int2'       => 'INT', // Sortierung
    'detail_public'     => 'CHECK',
    'detail_aktiv'      => 'CHECK'
);

$plugin['fields_detail_varchar1']   = array( 'DE'=>'Deutsch', 'EN'=>'Englisch' );
$plugin['fields_detail_country']    = 'getCountry';
$plugin['fields_detail_text2']      = 'getCountry';

$plugin['id'] = isset($_GET['edit']) ? intval($_GET['edit']) : 0;

// process post form
if(isset($_POST['detail_firstname'])) {

    $plugin['data'] = array(
        'detail_id' => intval($_POST['detail_id']),
        'detail_regkey' => MODULE_KEY
    );

    foreach($plugin['fields'] as $key => $value) {

        switch($value) {

            case 'TEXTAREA':
            case 'SELECT':
            case 'STRING':
                $plugin['data'][$key] = isset($_POST[$key]) ? clean_slweg($_POST[$key]) : '';
                break;

            case 'CHECK':
                $plugin['data'][$key] = empty($_POST[$key]) ? 0 : 1;
                break;

            case 'FLOAT':
                $plugin['data'][$key] = empty($_POST[$key]) ? 0 : floatval($_POST[$key]);
                break;

            case 'INT':
                $plugin['data'][$key] = empty($_POST[$key]) ? 0 : intval($_POST[$key]);
                break;

            case 'MULTICHECK':
            case 'MULTISELECT':
                $plugin['data'][$key] = isset($_POST[$key]) && is_array($_POST[$key]) && count($_POST[$key]) ? ' '.implode(' , ', $_POST[$key]).' ' : '';
                break;

            case 'FILES':
                $plugin['data'][$key] = isset($_POST[$key]) && is_array($_POST[$key]) && count($_POST[$key]) ? $_POST[$key] : array();
                $plugin_file_description = empty($_POST[$key.'_description']) ? array() : explode("\n", rtrim($_POST[$key.'_description']));
                if(count($plugin_file_description)) {
                    foreach($plugin_file_description as $fs_key => $fs_item) {
                        $plugin_file_description[$fs_key] = array_slice(explode('|', $fs_item), 0, 6);
                        if(count($plugin_file_description[$fs_key])) {
                            foreach($plugin_file_description[$fs_key] as $fd_key => $fd_value) {
                                $plugin_file_description[$fs_key][$fd_key] = trim($fd_value);
                            }
                        }
                    }
                }
                $plugin['data'][$key] = array(
                    'files' => $plugin['data'][$key],
                    'descriptions' => $plugin_file_description
                );
                break;
        }

    }

    if(empty($plugin['data']['detail_company']) && empty($plugin['data']['detail_lastname'])) {
        $plugin['error']['detail_company'] = $BLM['error_company'];
    }

    if(!empty($plugin['data']['detail_email']) && !is_valid_email($plugin['data']['detail_email'])) {
        $plugin['error']['detail_email'] = $BLM['error_email'];
    }

    if(!empty($plugin['data']['detail_varchar3'])) {
        $plugin['data']['detail_varchar3'] = ' ' . implode(' , ', convertStringToArray($plugin['data']['detail_varchar3']) ) . ' ';
    }

    if(!isset($plugin['error'])) {

        // Buffering files data
        $fs_item = $plugin['data']['detail_text3'];
        $plugin['data']['detail_text3'] = json_encode($plugin['data']['detail_text3']);

        if($plugin['data']['detail_id']) {

            // UPDATE
            $sql  = 'UPDATE '.DB_PREPEND.'cmsgo_userdetail SET ';

            $sql_fields = array();

            foreach($plugin['fields'] as $key => $value) {

                $sql_fields[] = $key."='".aporeplace($plugin['data'][$key])."'";

            }

            $sql .= implode(', ', $sql_fields);

            $sql .= "WHERE detail_regkey="._dbEscape(MODULE_KEY)." AND detail_id=".$plugin['data']['detail_id'];

            if(@_dbQuery($sql, 'UPDATE')) {

                if(isset($_POST['save'])) {

                    set_status_message($BLM['save_success'].(trim($plugin['data']['detail_company'].' / '.$plugin['data']['detail_lastname'], ' /')), 'success');
                    headerRedirect(MODULE_HREF_DECODE);

                }

            } else {

                $plugin['error']['update'] = 'MySQL error: '._dbError();

            }

        } else {

            // INSERT
            $sql_fields = $plugin['fields'];
            $sql_fields['detail_regkey'] = 'detail_regkey';
            $plugin['data']['detail_regkey'] = MODULE_KEY;

            $sql  = 'INSERT INTO '.DB_PREPEND.'cmsgo_userdetail (';
            foreach($sql_fields as $key => $value) {
                $sql_fields[$key] = $key;
            }
            $sql .= implode(', ', $sql_fields);
            $sql .= ') VALUES (';
            foreach($sql_fields as $key => $value) {
                $sql_fields[$key] = _dbEscape($plugin['data'][$key]);
            }
            $sql .= implode(', ', $sql_fields);
            $sql .= ')';

            if(@_dbQuery($sql, 'INSERT')) {
                if(isset($_POST['save'])) {
                    set_status_message($BLM['save_success'].$plugin['data']['detail_company'], 'success');
                    headerRedirect(MODULE_HREF_DECODE);
                }
            } else {
                $plugin['error']['update'] = 'MySQL error: '._dbError();
            }
        }

        // Restore files data from buffer
        $plugin['data']['detail_text3'] = $fs_item;
    }
}

// try to read entry from database
if($plugin['id'] && !isset($plugin['error'])) {

    $sql  = 'SELECT * FROM '.DB_PREPEND.'cmsgo_userdetail WHERE detail_id='.$plugin['id'].' AND detail_pid=0';
    $plugin['data'] = _dbQuery($sql);
    $plugin['data'] = isset($plugin['data'][0]) ? $plugin['data'][0] : false;

    // Json Decode
    if(!empty($plugin['data']['detail_text3'])) {
        $plugin['data']['detail_text3'] = @json_decode($plugin['data']['detail_text3'], true);
    }
    if(!is_array($plugin['data']['detail_text3'])) {
        $plugin['data']['detail_text3'] = array(
            'files' => array(),
            'descriptions' => array()
        );
    }
}

if(isset($plugin['data']['detail_varchar3']) && is_string($plugin['data']['detail_varchar3'])) {
    $plugin['data']['detail_varchar3'] = implode(', ', convertStringToArray($plugin['data']['detail_varchar3']));
}
if(isset($plugin['data']['detail_text2']) && is_string($plugin['data']['detail_text2'])) {
    $plugin['data']['detail_text2'] = convertStringToArray($plugin['data']['detail_text2']);
}

// default values
if(empty($plugin['data'])) {

    $plugin['data'] = array( 'detail_id' => 0 );

    foreach($plugin['fields'] as $key => $value) {

        switch($value) {

            case 'TEXTAREA':
            case 'SELECT':
            case 'STRING':
                $plugin['data'][$key] = '';
                break;

            case 'INT':
            case 'FLOAT':
            case 'CHECK':
                $plugin['data'][$key] = 0;
                break;

            case 'MULTICHECK':
            case 'MULTISELECT':
                $plugin['data'][$key] = array();
                break;

            case 'FILES':
                $plugin['data'][$key] = array(
                    'files' => array(),
                    'descriptions' => array()
                );
                break;
        }
    }

    // default country DE
    $plugin['data']['detail_country'] = 'DE';
}
