<?php
// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

/**
 * Compare against current domain and redirect to correct if necessary - based on 1st level
 * and also check for browser language and try to do correct redirect based on this
 *
 * Example structure:
 *   [-] Webroot (ID 0)
 *    |--- EN (level 1, structure ID 1)
 *    |--- DE (level 1, structure ID 2)
 *    |--- ES (level 1, structure ID 3)
 */

$_DOMAIN_REDIRECT = [
    'domain1.com' => ['ID' => 1, 'LANG' => 'EN', 'HOME_URL' => 'https://www.domain1.com/?en'], // 1st entry is default
    'domain2.com' => ['ID' => 2, 'LANG' => 'DE', 'HOME_URL' => 'https://www.domain2.com/?de'],
    'domain3.com' => ['ID' => 3, 'LANG' => 'ES', 'HOME_URL' => 'https://www.domain3.com/?es'],
];

// Try browser based language detection when opening root level
$_DOMAIN_DETECT_BROWSER_LANG = true;

$_DOMAIN_URI = !empty($_SERVER['SERVER_NAME']) ? strtolower($_SERVER['SERVER_NAME']) : '';

if (isset($LEVEL_ID[1])) {

    $_DOMAIN_STATUS = true;

    foreach ($_DOMAIN_REDIRECT as $key => $value) {
        if ($LEVEL_ID[1] === $value['ID'] && strpos($_DOMAIN_URI, strtolower($key)) !== false) {
            $_DOMAIN_STATUS = false;
            break;
        } elseif ($LEVEL_ID[1] === $value['ID'] && strpos($_DOMAIN_URI, strtolower($key)) === false) {
            headerRedirect($value['HOME_URL'], 301);
        }
    }

    if ($_DOMAIN_STATUS) {
        reset($_DOMAIN_REDIRECT);
        $value = current($_DOMAIN_REDIRECT);
        headerRedirect($value['HOME_URL'], 301);
    }

} elseif ($_DOMAIN_DETECT_BROWSER_LANG && (int)$content['cat_id'] === 0) {

    $current_lang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])
        ? strtoupper(substr(preg_replace('/(;q=\d+\.?\d*)/i', '', $_SERVER['HTTP_ACCEPT_LANGUAGE']), 0, 2))
        : '';

    foreach ($_DOMAIN_REDIRECT as $key => $value) {
        if ($value['LANG'] === $current_lang) {
            headerRedirect($value['HOME_URL'], 301);
        }
    }

    reset($_DOMAIN_REDIRECT);
    $value = current($_DOMAIN_REDIRECT);
    headerRedirect($value['HOME_URL'], 301);

}

