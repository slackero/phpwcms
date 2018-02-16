<?php

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// set allowed languages
// set $cmsgo['allowed_lang'] in conf.inc.php
// $cmsgo['allowed_lang']      = array('en', 'de', 'fr', 'es');
$language_default           = 'en';
$language_current           = $language_default;
$language_cookie_duration   = 60*60*24*365; // 1 year

if(isset($_GET['lang'])) {
    $language_current = strtolower( substr($_GET['lang'], 0, 2) );
    $_SESSION['cmsgoFrontendLanguage'] = $language_current;
    setcookie('cmsgoFrontendLanguage', $language_current, time()+$language_cookie_duration, '/' );
} elseif(isset($_SESSION['cmsgoFrontendLanguage'])) {
    $language_current   = $_SESSION['cmsgoFrontendLanguage'];
} elseif(isset($_COOKIE['cmsgoFrontendLanguage'])) {
    $language_current   = $_COOKIE['cmsgoFrontendLanguage'];
}
if(!in_array($language_current, $cmsgo['allowed_lang'])) {
    $language_current   = $language_default;
    $_SESSION['cmsgoFrontendLanguage'] = $language_current;
    setcookie('cmsgoFrontendLanguage', $language_current, time()+$language_cookie_duration, '/' );
}

// init language replacements
$language_regexp        = array( 'search' => array(), 'replace' => array() );

// set all language replacements now
foreach($cmsgo['allowed_lang'] as $lang) {

    $language_regexp['search'][$lang]   = '/\['.$lang.'\](.*?)\[\/'.$lang.'\]/is';
    $language_regexp['replace'][$lang]  = $lang == $language_current ? '$1' : '';

}

$content['all']         = preg_replace($language_regexp['search'], $language_regexp['replace'], $content['all']);
$content["pagetitle"]   = preg_replace($language_regexp['search'], $language_regexp['replace'], $content["pagetitle"]);
