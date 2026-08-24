<?php
/**
 * phpwcms
 *
 * Anti-Spam Email Protection frontend render script
 *
 * Obfuscates email addresses and "mailto:" links in content
 * to protect against automatic harvesting by scrapers and spambots.
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

$content['all'] = replaceEmailAddress($content['all']);

/**
 * Replaces mailto: links and inline email addresses with spambot-protected markup.
 *
 * @param string $str
 * @return string
 */
function replaceEmailAddress($str) {

    if (!is_string($str) || strpos($str, '@') === false) {
        return $str;
    }

    // Inject lightweight mailtoLink function directly into HTML head
    $GLOBALS['block']['custom_htmlhead']['mailtoLink.js'] = '  <script' . SCRIPT_ATTRIBUTE_TYPE . '>function mailtoLink(u,d){if(u&&d){window.location.href="mailto:"+u+"@"+d;return true;}return false;}</script>';

    preg_match_all('/<input[^>]+>/iU', $str, $input_tags);
    preg_match_all('/<textarea[^>]+>.*<\/textarea>/isU', $str, $textarea_tags);
    preg_match_all('/<option[^>]+>/iU', $str, $option_tags);

    foreach ($input_tags[0] as $key => $value) {
        $str = str_replace($value, '[%---I' . $key . '---%]', $str);
    }
    foreach ($textarea_tags[0] as $key => $value) {
        $str = str_replace($value, '[%---T' . $key . '---%]', $str);
    }
    foreach ($option_tags[0] as $key => $value) {
        $str = str_replace($value, '[%---O' . $key . '---%]', $str);
    }

    // Modern Unicode-safe email regex pattern
    $regex = '([a-zA-Z0-9_\.\-]+)(@)([a-zA-Z0-9_\-]+\.[a-zA-Z0-9\-\._\-]+[a-zA-Z0-9]\??[a-zA-Z0-9=&]*)';

    $src = '/href=[\'"]*mailto:' . $regex . '[\'"]*([^>]*>)/i';
    $tar = 'href="#" onclick="mailtoLink(\'$1\',\'$3\');return false;" title="@@Email:@@ $1 @@at@@ $3"$4';

    $str = preg_replace($src, $tar, $str);
    $str = preg_replace_callback('/' . $regex . '/i', 'rewriteEmailText', $str);

    foreach ($input_tags[0] as $key => $value) {
        $str = str_replace('[%---I' . $key . '---%]', $value, $str);
    }
    foreach ($textarea_tags[0] as $key => $value) {
        $str = str_replace('[%---T' . $key . '---%]', $value, $str);
    }
    foreach ($option_tags[0] as $key => $value) {
        $str = str_replace('[%---O' . $key . '---%]', $value, $str);
    }

    return $str;
}

/**
 * Obfuscates email text components with HTML character entities.
 *
 * @param array $part
 * @return string
 */
function rewriteEmailText($part) {
    $user   = str_replace('.', '&#46;', html_specialchars($part[1]));
    $at     = '&#64;';
    $domain = str_replace('.', '&#46;', html_specialchars($part[3]));

    return $user . $at . $domain;
}

