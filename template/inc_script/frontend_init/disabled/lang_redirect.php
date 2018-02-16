<?php

// compare against current domain and redirect to correct if neccessary - based on 1st level
// and also check for browser language and try to do correct redirect based on this
/*
    [-] Webroot (ID 0)
     |
     |--- EN (level 1, structure ID 1)
     |
     |--- DE (level 1, structure ID 2)
     |
     |--- ES (level 1, structure ID 3)


*/

$_DOMAIN_REDIRECT = array(

    'domain1.com'   => array( 'ID' => 1, 'LANG' => 'EN', 'HOME_URL' => 'http://www.domain1.com/?en' ), //1st entry will be taken as default

    'domain2.com'   => array( 'ID' => 2, 'LANG' => 'DE', 'HOME_URL' => 'http://www.domain2.com/?de' ),

    'domain3.com'   => array( 'ID' => 3, 'LANG' => 'ES', 'HOME_URL' => 'http://www.domain3.com/?es' ),

    );

// try browser based language detection
// but only when user has opened the root level
$_DOMAIN_DETECT_BROWSER_LANG = true;


//////////////////////////////////////////////////////////////////////////////////

$_DOMAIN_URI = strtolower($_SERVER['SERVER_NAME']);

if(isset($LEVEL_ID[1])) {

    $_DOMAIN_STATUS = true;

    foreach( $_DOMAIN_REDIRECT as $key => $value ) {

        if($LEVEL_ID[1] == $value['ID'] && strpos($_DOMAIN_URI, strtolower($key)) !== false ) {

            $_DOMAIN_STATUS = false;
            break;

        } elseif($LEVEL_ID[1] == $value['ID'] && strpos($_DOMAIN_URI, strtolower($key)) === false ) {

            headerRedirect($value['HOME_URL'], 301);

        }
    }

    if($_DOMAIN_STATUS) {
        reset($_DOMAIN_REDIRECT);
        $value = current($_DOMAIN_REDIRECT);
        headerRedirect($value['HOME_URL'], 301);
    }


} elseif( $_DOMAIN_DETECT_BROWSER_LANG && $content['cat_id'] == 0 ) {

    $current_lang = strtoupper( substr( preg_replace('/(;q=\d+.\d+)/i', '', $_SERVER['HTTP_ACCEPT_LANGUAGE'] ), 0, 2 ) );

    foreach( $_DOMAIN_REDIRECT as $key => $value ) {

        if( $value['LANG'] == $current_lang ) {

            headerRedirect($value['HOME_URL'], 301);

        }

    }

    reset($_DOMAIN_REDIRECT);
    $value = current($_DOMAIN_REDIRECT);
    headerRedirect($value['HOME_URL'], 301);

}
