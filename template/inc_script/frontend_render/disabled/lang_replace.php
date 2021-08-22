<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// set allowed languages
// set $phpwcms['allowed_lang'] in conf.inc.php
// $phpwcms['allowed_lang']      = array('en', 'de', 'fr', 'es');
$language_default           = 'en';
$language_current           = $language_default;
$language_cookie_duration   = 60*60*24*365; // 1 year

if(isset($_GET['lang'])) {
    $language_current = strtolower( substr($_GET['lang'], 0, 2) );
    $_SESSION['phpwcmsFrontendLanguage'] = $language_current;
    setcookie('phpwcmsFrontendLanguage', $language_current, time()+$language_cookie_duration, '/', getCookieDomain(), PHPWCMS_SSL, true);
} elseif(isset($_SESSION['phpwcmsFrontendLanguage'])) {
    $language_current   = $_SESSION['phpwcmsFrontendLanguage'];
} elseif(isset($_COOKIE['phpwcmsFrontendLanguage'])) {
    $language_current   = $_COOKIE['phpwcmsFrontendLanguage'];
}
if(!in_array($language_current, $phpwcms['allowed_lang'])) {
    $language_current   = $language_default;
    $_SESSION['phpwcmsFrontendLanguage'] = $language_current;
    setcookie('phpwcmsFrontendLanguage', $language_current, time()+$language_cookie_duration, '/', getCookieDomain(), PHPWCMS_SSL, true);
}

// init language replacements
$language_regexp        = array( 'search' => array(), 'replace' => array() );

// set all language replacements now
foreach($phpwcms['allowed_lang'] as $lang) {

    $language_regexp['search'][$lang]   = '/\['.$lang.'\](.*?)\[\/'.$lang.'\]/is';
    $language_regexp['replace'][$lang]  = $lang == $language_current ? '$1' : '';

}

$content['all']         = preg_replace($language_regexp['search'], $language_regexp['replace'], $content['all']);
$content["pagetitle"]   = preg_replace($language_regexp['search'], $language_regexp['replace'], $content["pagetitle"]);
