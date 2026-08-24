<?php
// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

// Set allowed languages
// Set $phpwcms['allowed_lang'] in conf.inc.php: $phpwcms['allowed_lang'] = ['en', 'de', 'fr', 'es'];
$language_default         = !empty($phpwcms['default_lang']) ? strtolower($phpwcms['default_lang']) : 'en';
$language_current         = $language_default;
$language_cookie_duration = 86400 * 365; // 1 year

$cookie_options = [
    'expires'  => time() + $language_cookie_duration,
    'path'     => '/',
    'domain'   => getCookieDomain(),
    'secure'   => PHPWCMS_SSL,
    'httponly' => true,
    'samesite' => !empty($phpwcms['session.cookie_samesite']) ? $phpwcms['session.cookie_samesite'] : 'Lax',
];

if (isset($_GET['lang'])) {
    $language_current = strtolower(substr(clean_slweg($_GET['lang']), 0, 2));
    $_SESSION['phpwcmsFrontendLanguage'] = $language_current;
    setcookie('phpwcmsFrontendLanguage', $language_current, $cookie_options);
} elseif (isset($_SESSION['phpwcmsFrontendLanguage'])) {
    $language_current = $_SESSION['phpwcmsFrontendLanguage'];
} elseif (!empty($_COOKIE['phpwcmsFrontendLanguage'])) {
    $language_current = clean_slweg($_COOKIE['phpwcmsFrontendLanguage']);
}

$allowed_lang = !empty($phpwcms['allowed_lang']) && is_array($phpwcms['allowed_lang']) ? $phpwcms['allowed_lang'] : [$language_default];

if (!in_array($language_current, $allowed_lang, true)) {
    $language_current = $language_default;
    $_SESSION['phpwcmsFrontendLanguage'] = $language_current;
    setcookie('phpwcmsFrontendLanguage', $language_current, $cookie_options);
}

// Init language replacements
$language_regexp = ['search' => [], 'replace' => []];

// Set all language replacements now
foreach ($allowed_lang as $lang) {
    $language_regexp['search'][$lang]  = '/\[' . preg_quote($lang, '/') . '\](.*?)\[\/' . preg_quote($lang, '/') . '\]/is';
    $language_regexp['replace'][$lang] = ($lang === $language_current) ? '$1' : '';
}

$content['all']       = preg_replace($language_regexp['search'], $language_regexp['replace'], $content['all']);
$content['pagetitle'] = preg_replace($language_regexp['search'], $language_regexp['replace'], $content['pagetitle']);

