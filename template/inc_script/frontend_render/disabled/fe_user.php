<?php

/**
 * FE User frontend render script
 * Use this to customize your frontend user registration form
 *
 * Frontend User Registration key can be found in constant FEUSER_REGKEY.
 * The default value of FEUSER_REGKEY is "FEUSER". It can be changed by
 * setting $phpwcms['feuser_regkey'] in conf.inc.php
 */

$fe_defaults = array(
    'field-open'    => '<p>',
    'field-close'   => '</p>',
    'label-class'   => 'label',
);


// first check what to do
if(_getFeUserLoginStatus() && strpos($content['all'], '{FE_USER_MANAGE}')) {

    $fe_action = '{FE_USER_MANAGE}';

    if( $_SESSION[ $_loginData['session_key'].'_userdata']['source'] == 'BACKEND' ) {
        $fe_action = false;
    }


} elseif(strpos($content['all'], '{FE_USER_REGISTER}')) {

    $fe_action = '{FE_USER_REGISTER}';

} else {

    $fe_action = false;

}


// fe user register
if($fe_action) {

    /**
     * Define fields to be used - fields are named like in phpwcms_userdetail
     * 'fieldname' => 'TYPE' (can be STRING, TEXT, CHECKBOX, RADIO, INT, FLOAT, TEL, COUNTRY, EMAIL)
     * or
     * 'fieldname' => array('type'=>'TYPE', 'value'=>default value, 'required'=>true|false)
     * use array for multiple selections
     * Use 'FIELDSET-X' => 'label', '/FIELDSET-X' => '' to enable fieldsets
     */
    $fe_fields = array(

        'FIELDSET-1'        => 'label-fieldset-1',

        'detail_login'      => 'STRING',
        'detail_password'   => 'STRING',

        '/FIELDSET-1'       => '',
        'FIELDSET-2'        => 'label-fieldset-2',

        'detail_title'      => 'STRING',
        'detail_salutation' => array( 'type'=>'RADIO', 'value'=>array('@@Mr@@', '@@Ms@@') ),
        'detail_firstname'  => 'STRING',
        'detail_lastname'   => 'STRING',
        'detail_company'    => 'STRING',
        'detail_street'     => 'STRING',
        'detail_add'        => 'STRING',
        'detail_city'       => 'STRING',
        'detail_zip'        => 'STRING',
        'detail_region'     => 'STRING',
        'detail_country'    => 'COUNTRY',
        'detail_fon'        => 'STRING',
        'detail_fax'        => 'STRING',
        'detail_mobile'     => 'STRING',
        'detail_signature'  => 'TEXT',
        'detail_prof'       => 'STRING',
        'detail_notes'      => 'TEXT',
        'detail_email'      => 'EMAIL',

        '/FIELDSET-2'       => '',
        'FIELDSET-3'        => 'label-fieldset-3',

        'detail_website'    => 'STRING',
        'detail_userimage'  => 'STRING',
        'detail_gender'     => 'STRING',
        'detail_birthday'   => 'STRING',

        '/FIELDSET-3'       => '',
        'FIELDSET-4'        => 'label-fieldset-4',

        'detail_varchar1'   => 'STRING',
        'detail_varchar2'   => 'STRING',
        'detail_varchar3'   => 'STRING',
        'detail_varchar4'   => 'STRING',
        'detail_varchar5'   => 'STRING',

        'detail_text1'      => 'TEXT',
        'detail_text2'      => 'TEXT',
        'detail_text3'      => 'TEXT',
        'detail_text4'      => 'TEXT',
        'detail_text5'      => 'TEXT',

        'detail_int1'       => 'INT',
        'detail_int2'       => 'INT',
        'detail_int3'       => 'INT',
        'detail_int4'       => 'INT',
        'detail_int5'       => 'INT',

        'detail_float1'     => 'FLOAT',
        'detail_float2'     => 'FLOAT',
        'detail_float3'     => 'FLOAT',
        'detail_float4'     => 'FLOAT',
        'detail_float5'     => 'FLOAT',

        '/FIELDSET-4'       => ''
                    );

    // init error array and error status set to false
    $fe_error = array('status' => false);

    // init fe data array
    $fe_data = array();

    foreach($fe_fields as $fe_field => $fe_field_value) {

        if( substr(ltrim($fe_field , '/'), 0, 8) === 'FIELDSET' ) {
            $fe_fields[ $fe_field ] = array('type' => 'FIELDSET', 'label' => $fe_field_value, 'value'=>substr($fe_field, 0, 1));
            continue;
        }

        $fe_error[ $fe_field ]  = '';

        if( is_array($fe_field_value) && isset($fe_field_value['type']) ) {
            $fe_field_type = $fe_field_value['type'];
            if(!isset($fe_field_value['value'])) {
                $fe_fields[ $fe_field ]['value'] = '';
            }
            $fe_fields[ $fe_field ]['required'] = empty( $fe_field_value['required'] ) ? false : true;
        } else {
            $fe_field_type = is_string($fe_field_value) ? $fe_field_value : 'STRING';
            $fe_fields[ $fe_field ] = array('type' => $fe_field_type, 'value' => '', 'required' => false);
        }

        if( $fe_field_type == 'INT' || $fe_field_type == 'FLOAT' ) {
            $fe_data[ $fe_field ] = 0;
        } else {
            $fe_data[ $fe_field ] = '';
        }

    }

    //dumpVar($fe_fields);

    if($content['cat_id'] == 0) {
        if($aktion[1]) {
            $_uri_alias = 'aid='.$aktion[1];
        } elseif($content['struct'][0]['acat_alias']) {
            $_uri_alias = $content['struct'][0]['acat_alias'];
        } else {
            $_uri_alias = 'id='.$content['cat_id'];
        }
    } else {
        $_uri_alias = '';
    }

    switch($fe_action) {

        case '{FE_USER_MANAGE}':    $_uri = rel_url( array('profile_manage'=>'edit'), array('profile_register', 'profile_reminder'), $_uri_alias );

                                    // at the moment it is only possible to edit user data of "real" FRONTEND users
                                    // all BACKEND users should login to backend and edit their data there
                                    $result = _dbGet(
                                            'phpwcms_userdetail', '*',
                                            "detail_filter='" . aporeplace(FEUSER_REGKEY) . "' AND detail_id=" . intval($_SESSION[ $_loginData['session_key'].'_userdata' ]['id']),
                                            '', '',  '1' );
                                    if(isset($result[0])) {
                                        $fe_data = $result[0];
                                        $fe_data['detail_password'] = '';
                                    }

                                    break;

        case '{FE_USER_REGISTER}':  $_uri = rel_url( array('profile_register'=>'create'), array('profile_manage', 'profile_reminder'), $_uri_alias );

                                    break;

    }


    if(isset($_POST['detail_login'])) {

        $udata['user_login']        = clean_slweg($_POST['user_login']);
        $udata['user_password']     = slweg($_POST['user_password']);
        $udata['user_password2']    = slweg($_POST['user_password2']);

        $udata['user_company']      = clean_slweg($_POST['user_company']);
        $udata['user_title']        = clean_slweg($_POST['user_title']);
        $udata['user_name']         = clean_slweg($_POST['user_name']);
        $udata['user_firstname']    = clean_slweg($_POST['user_firstname']);
        $udata['user_street']       = clean_slweg($_POST['user_street']);
        $udata['user_zip']          = clean_slweg($_POST['user_zip']);
        $udata['user_city']         = clean_slweg($_POST['user_city']);
        $udata['user_tel']          = preg_replace('/[^0-9\+\-\(\) ]/', '', clean_slweg($_POST['user_tel']) );
        $udata['user_email']        = clean_slweg($_POST['user_email']);


        if($fe_action == '{FE_USER_REGISTER}') {


            $sql  = 'SELECT COUNT(*) FROM '.DB_PREPEND."phpwcms_userdetail WHERE ";
            $sql .= "detail_login LIKE '" . aporeplace($udata['user_login'])."'";

            if( empty($udata['user_login']) ) {
                $uerror['user_login'] = '@@Login is required@@';
            } elseif( strlen($udata['user_login']) < 4 ) {
                $uerror['user_login'] = '@@Login is too short (more than 3 chars)@@';
            } elseif( _dbCount( $sql )  ) {
                $uerror['user_login'] = '@@Login not allowed@@';
            }

            if( empty($udata['user_password']) ) {
                $uerror['user_password'] = '@@Password is required@@';
            } elseif( strlen($udata['user_password']) < 4 ) {
                $uerror['user_password'] = '@@Password is too short (more than 3 chars)@@';
            } elseif( $udata['user_password'] !== $udata['user_password2'] ) {
                $uerror['user_password'] = '@@Password and repeat password are not equal@@';
            }



        } else {

            $udata['user_login'] = $_SESSION[ $_loginData['session_key'].'_userdata']['login'];

            if( !empty($udata['user_password']) && strlen($udata['user_password']) < 4 ) {
                $uerror['user_password'] = '@@Password is too short (more than 3 chars)@@';
            } elseif( $udata['user_password'] !== $udata['user_password2'] ) {
                $uerror['user_password'] = '@@Password and repeat password are not equal@@';
            }

        }

        $sql  = 'SELECT COUNT(*) FROM '.DB_PREPEND."phpwcms_userdetail WHERE ";
        $sql .= "detail_login != '" . aporeplace($udata['user_login']) . "' AND ";
        $sql .= "detail_email = '" . aporeplace(strtolower($udata['user_email']))."'";

        if( empty($udata['user_email']) ) {
            $uerror['user_email'] = 'E-Mail muss ausgef&uuml;llt werden';
        } elseif( !is_valid_email($udata['user_email']) ) {
            $uerror['user_email'] = 'E-Mail muss valide sein';
        } elseif( _dbCount( $sql )  ) {
            $uerror['user_email'] = 'E-Mail bereits registriert';
        }

        if( empty($udata['user_tel']) ) {
            $uerror['user_tel'] = '@@Phone is required@@';
        } elseif( preg_match('/[^0-9\+\-\(\) ]/', $udata['user_tel']) ) {
            $uerror['user_tel'] = '@@Only integers, spaces, parentheses, + or - are allowed@@';
        }

        if( empty($udata['user_name']) ) {
            $uerror['user_name'] = '@@Name is required@@';
        }
        if( empty($udata['user_firstname']) ) {
            $uerror['user_firstname'] = '@@First name is required@@';
        }
        if( empty($udata['user_street']) ) {
            $uerror['user_street'] = '@@Street is required@@';
        }
        if( empty($udata['user_zip']) || empty($udata['user_city']) ) {
            $uerror['user_zip'] = '@@Post code and city are required@@';
        }


    }

    $fe_reg = array();

    if($fe_action == '{FE_USER_REGISTER}') {

        $fe_reg[] = '<p>Register Text</p>';

    } else {

        $fe_reg[] = '<p>Edit Text</p>';

    }

    $fe_reg[] = '<form action="' .$_uri. '" method="post">';

    foreach($fe_fields as $fe_field) {

        switch($fe_field['type']) {

            case 'TEXT':
                break;

            case 'EMAIL':
                break;

            case 'INT':
                break;

            case 'FLOAT':
                break;

            case 'RADIO':
                break;

            case 'CHECKBOX':
                break;

            case 'FIELDSET':
                break;

            case 'STRING':
            default:

        }

    }

    /*
    $fe_reg[] = '<fieldset>';
    $fe_reg[] = '<legend> @@Login Data@@ </legend>';

    $fe_reg[] = is_fe_error('detail_login');
    $fe_reg[] = '<p>';
    $fe_reg[] = '<label class="labelpos" for="user_login">@@Login@@</label>';
    if($fe_action == '{FE_USER_REGISTER}') {
        $fe_reg[] = '<input type="text" name="user_login" id="user_login" value="' .html_specialchars($udata['user_login']). '" class="textfield" maxlength="200" size="30" />';
    } else {
        $fe_reg[] = '<strong>' .html_specialchars($udata['user_login']). '</strong>';
        $fe_reg[] = '<input type="hidden" name="user_login" value="' .html_specialchars($udata['user_login']). '" />';
    }
    $fe_reg[] = '</p>';
    */



    // Submit Button Line
    $fe_reg[] = '<p>';
    $fe_reg[] = '   <input type="submit" value="@@Submit@@" class="button" />';
    $fe_reg[] = '</p>';


    $fe_reg[] = '</form>';

    $fe_reg = implode(LF, $fe_reg);


    if(isset($_POST['user_login']) && $fe_action == '{FE_USER_REGISTER}') {
        if($uerror['status']) {

            $fe_reg = '<p class="error">Es sind Fehler bei der Verarbeitung des Formulars aufgetreten. Bitte pr&uuml;fen Sie Ihre Angaben.</p>' . LF . $fe_reg;

        } else {

            $profile_data = $udata;
            unset($profile_data['user_password'], $profile_data['user_password2']);

            $sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_userdetail (';
            $sql .= 'detail_title, detail_firstname, detail_lastname, detail_company, detail_street, detail_city, detail_zip, ';
            $sql .= 'detail_fon, detail_notes, detail_aktiv, detail_newsletter, detail_varchar1, detail_email, detail_login, detail_password) VALUES (';
            $sql .= "'" . aporeplace($udata['user_title']) . "', ";
            $sql .= "'" . aporeplace($udata['user_firstname']) . "', ";
            $sql .= "'" . aporeplace($udata['user_name']) . "', ";
            $sql .= "'" . aporeplace($udata['user_company']) . "', ";
            $sql .= "'" . aporeplace($udata['user_street']) . "', ";
            $sql .= "'" . aporeplace($udata['user_city']) . "', ";
            $sql .= "'" . aporeplace($udata['user_zip']) . "', ";
            $sql .= "'" . aporeplace($udata['user_tel']) . "', ";
            $sql .= "'" . aporeplace(serialize($profile_data)) . "', ";
            $sql .= "'0', ";
            $sql .= "'" . ( empty($udata['user_profile_7'][3]) ? '' : 1 ) . "', ";
            $sql .= "'fereg', ";
            $sql .= "'" . aporeplace(strtolower($udata['user_email'])) . "', ";
            $sql .= "'" . aporeplace($udata['user_login']) . "', ";
            $sql .= "'" . aporeplace(md5($udata['user_password'])) . "')";

            $queryResult = _dbQuery($sql, 'INSERT');
            if(!empty($queryResult['INSERT_ID'])) {
                $fe_reg  = '<p class="success">Vielen Dank '.html_specialchars($udata['user_firstname'].' '.$udata['user_name']).'! Ihre Registrierungsanfrage wurden erfolgreich &uuml;bertragen.</p>';
                $fe_reg .= '<p>Ihnen wird in wenigen Augenblicken eine Best�tigung an die E-Mail <b>'.html_specialchars($udata['user_email']).'</b> zugesendet.</p>';

                $fe_text  = 'Hallo '.trim($udata['user_title'] . ' ' . trim( $udata['user_firstname'].' '.$udata['user_name']) ) . LF . LF;
                $fe_text .= 'Ihre Registrierung haben wir erhalten.' . LF;
                $fe_text .= 'Wir pr�fen Ihre Daten und melden uns umgehend bei Ihnen.' . LF . LF;

                if(empty($udata['user_profile_7'][4])) {
                    $fe_text .= 'Sie m�chten keinen Zugriff auf unser Partnerbackend. ' .LF . 'Allerdings haben wir folgende Zugangsdaten f�r Sie hinterlegt:' . LF;
                } else {
                    $fe_text .= 'Sie m�chten Zugriff auf unser Partnerbackend. ' .LF . 'Folgende Zugangsdaten sind von Ihnen gesendet worden:' . LF;
                }
                $fe_text .= '  Login:    ' . $udata['user_login'] . LF;
                $fe_text .= '  Passwort: ' . $udata['user_password'] . LF . LF;
                $fe_text .= 'Ihr Passwort ist nicht reproduizierbar verschl�sselt in unserem System abgelegt worden.' . LF . LF . LF;
                $fe_text .= 'Mit besten Gr��en' . LF;
                $fe_text .= 'phpwcms, Oliver' . LF;

                $fe_text1  = 'Neue Benutzerregistrierung' . LF;
                $fe_text1 .= '--------------------------' . LF . LF;

                $fe_text1 .= 'Die Benutzerdaten k�nnen im Backend eingesehen werden.' . LF;

                if(!empty($udata['user_profile_7'][4])) {
                    $fe_text1 .= 'Der Benutzer w�nscht die Freischaltung f�r den Partnerbereich!' .LF;
                    $fe_text1 .= '  Login:    ' . $udata['user_login'] . LF;
                }

                $fe_text1 .= LF;
                $fe_text1 .= 'Benutzerangaben:' . LF;
                $fe_text1 .= '================' . LF . LF;

                $fe_text1 .= 'Firma:   ' . $udata['user_company'] . LF;
                $fe_text1 .= 'Anrede:  ' . $udata['user_title'] . LF;
                $fe_text1 .= 'Vorname: ' . $udata['user_firstname'] . LF;
                $fe_text1 .= 'Name:    ' . $udata['user_name'] . LF;
                $fe_text1 .= 'Stra�e:  ' . $udata['user_street'] . LF;
                $fe_text1 .= 'PLZ:     ' . $udata['user_zip'] . LF;
                $fe_text1 .= 'Ort:     ' . $udata['user_city'] . LF;
                $fe_text1 .= 'Telefon: ' . $udata['user_tel'] . LF;
                $fe_text1 .= 'E-Mail:  ' . $udata['user_email'] . LF;

                $fe_text1 .= LF . '-----------------------------------------------------------' . LF;
                if(!PHPWCMS_GDPR_MODE) {
                    $fe_text1 .= 'IP: ' . getRemoteIP();
                }

                $fe_csv_attach  = implode(';', array_keys($fe_csv) );
                $fe_csv_attach .= LF;
                $fe_csv_attach .= implode(';', $fe_csv );

                $fe_csv = array();

                $fe_csv['filename'] = date('Y-m-d_H-i-s') . '_' . preg_replace('/[^a-zA-Z0-9\-_]/', '', $udata['user_login']).'.csv';
                $fe_csv['mime']     = 'text/csv';
                $fe_csv['data']     = $fe_csv_attach;

                sendEmail(array(
                    'recipient'     => strtolower($udata['user_email']),
                    'toName'        => trim($udata['user_firstname'].' '.$udata['user_name']),
                    'subject'       => 'phpwcms Registration',
                    'text'          => $fe_text,
                    'from'          => 'og@phpwcms.org',
                    'fromName'      => 'phpwcms',
                    'sender'        => 'og@phpwcms.org' ));

                sendEmail(array(
                    'recipient'     => 'slackero+phpwcms-registration@gmail.com',
                    'subject'       => 'New registration',
                    'text'          => $fe_text1,
                    'from'          => strtolower($udata['user_email']),
                    'fromName'      => trim($udata['user_firstname'].' '.$udata['user_name']),
                    'sender'        => strtolower($udata['user_email']),
                    'stringAttach'  => array($fe_csv) ));


            } else {
                $fe_reg = '<p class="error">Beim Speichern Ihrer Daten ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut oder wenden Sie sich an den Webmaster.</p>' . LF . $fe_reg;
            }
        }
    }

    if(isset($_POST['user_login']) && $fe_action == '{FE_USER_MANAGE}') {

        if($uerror['status']) {

            $fe_reg = '<p class="error">Es sind Fehler bei der Verarbeitung des Formulars aufgetreten. Bitte pr&uuml;fen Sie Ihre Angaben.</p>' . LF . $fe_reg;

        } else {

            $profile_data = $udata;
            unset($profile_data['user_password'], $profile_data['user_password2']);

            $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_userdetail SET ';
            $sql .= "detail_title       = '".aporeplace($udata['user_title'])."', ";
            $sql .= "detail_firstname   = '".aporeplace($udata['user_firstname'])."', ";
            $sql .= "detail_lastname    = '".aporeplace($udata['user_name'])."', ";
            $sql .= "detail_company     = '".aporeplace($udata['user_company'])."', ";
            $sql .= "detail_street      = '".aporeplace($udata['user_street'])."', ";
            $sql .= "detail_city        = '".aporeplace($udata['user_city'])."', ";
            $sql .= "detail_zip         = '".aporeplace($udata['user_zip'])."', ";
            $sql .= "detail_fon         = '".aporeplace($udata['user_tel'])."', ";
            $sql .= "detail_notes       = '".aporeplace(serialize($profile_data))."', ";
            $sql .= "detail_newsletter  = '".( empty($udata['user_profile_7'][3]) ? '' : 1 )."', ";
            if($udata['user_password']) {
                $sql .= "detail_password    = '".aporeplace(md5($udata['user_password']))."', ";
            }
            $sql .= "detail_email       = '".aporeplace(strtolower($udata['user_email']))."' ";
            $sql .= 'WHERE detail_id=' . intval($_SESSION[ $_loginData['session_key'].'_userdata']['id']).' LIMIT 1';

            $queryResult = _dbQuery($sql, 'UPDATE');
            if(isset($queryResult['AFFECTED_ROWS'])) {

                $fe_reg = '<p>Ihre Profildaten wurden erfolgreich aktualisiert</p>' . LF . $fe_reg;

            } else {

                $fe_reg = '<p class="error">Leider konnten Ihre Anfgaben nicht in der Datenbank gesichert werden. Bitte pr&uuml;fen Sie Ihre Angaben oder wenden Sie sich an den Systemadministrator.</p>' . LF . $fe_reg;

            }

        }

    }

    $content['all'] = str_replace($fe_action, $fe_reg, $content['all']);

} else {

    $content['all'] = str_replace('{FE_USER_MANAGE}', '<p class="error">Diese Aktion ist leider nicht zul&auml;ssig</p>', $content['all']);

}


function is_fe_error($field='') {
    global $fe_error;
    if(!empty($fe_error[$field])) {
        $fe_error['status'] = true;
        return '<p class="error">' . $fe_error[$field] . '</p>';
    }
    return '';
}
