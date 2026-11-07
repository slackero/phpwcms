<?php
/**
 * phpwcms charset and string helpers powered by voku/portable-utf8
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 */

use voku\helper\ASCII;
use voku\helper\UTF8;

/**
 * Checks to see if a string is valid UTF-8 encoded.
 *
 * @param mixed $str The string to be checked
 * @return bool True if $str fits a UTF-8 model, false otherwise.
 */
function phpwcms_seems_utf8($str) {
    if (!is_string($str) || $str === '') {
        return true;
    }

    return UTF8::is_utf8($str);
}

/**
 * Converts all accent / diacritic characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is returned as-is.
 * Supports Unicode Normalization and multilingual transliteration.
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced ASCII characters.
 */
function phpwcms_remove_accents($string) {
    if (!is_string($string) || $string === '' || !preg_match('/[\x80-\xff]/', $string)) {
        return (string)$string;
    }

    if (class_exists('Normalizer')) {
        $string = Normalizer::normalize($string, Normalizer::FORM_C);
    }

    if (!UTF8::is_utf8($string)) {
        $string = UTF8::to_utf8($string);
    }

    $lang = !empty($GLOBALS['phpwcms']['default_lang']) ? (string)$GLOBALS['phpwcms']['default_lang'] : (defined('PHPWCMS_USER_LANG') ? PHPWCMS_USER_LANG : 'en');

    return ASCII::to_ascii($string, $lang, false, false, true);
}

/**
 * Generate a clean URL slug using voku/portable-ascii and voku/portable-utf8
 *
 * @param string $string
 * @param string $separator
 * @param bool|null $allow_slash If null, defaults to PHPWCMS_ALIAS_WSLASH
 * @return string
 */
function phpwcms_slugify($string, $separator = '-', $allow_slash = null) {
    if (!is_string($string) || $string === '') {
        return '';
    }

    $allow_slash = $allow_slash ?? (defined('PHPWCMS_ALIAS_WSLASH') && PHPWCMS_ALIAS_WSLASH);
    $string = UTF8::cleanup($string);
    $lang = !empty($GLOBALS['phpwcms']['default_lang']) ? (string)$GLOBALS['phpwcms']['default_lang'] : (defined('PHPWCMS_USER_LANG') ? PHPWCMS_USER_LANG : 'en');

    if ($allow_slash && str_contains($string, '/')) {
        $parts = explode('/', $string);
        $slugs = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }
            if (defined('PHPWCMS_ALIAS_UTF8') && PHPWCMS_ALIAS_UTF8) {
                $slug = UTF8::strtolower(uri_sanitize($part, false));
            } else {
                $slug = ASCII::to_slugify($part, $separator, $lang, [], false, true, true);
            }
            if ($slug !== '') {
                $slugs[] = $slug;
            }
        }
        return implode('/', $slugs);
    }

    if (defined('PHPWCMS_ALIAS_UTF8') && PHPWCMS_ALIAS_UTF8) {
        return UTF8::strtolower(uri_sanitize($string, false));
    }

    return ASCII::to_slugify($string, $separator, $lang, [], false, true, true);
}
