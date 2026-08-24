<?php
// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

/**
 * Overwrite or extend keywords
 */
if (empty($content['all_keywords'])) {
    $content['all_keywords'] = 'set, my, default, keywords';
} else {
    $content['all_keywords'] .= ', add, my, default, keywords';
}

/**
 * Custom page description
 */
set_meta('description', 'This is my description for just a test');

/**
 * Custom robots tag
 */
set_meta('robots', 'index,follow');

/**
 * HTTP-equiv meta tag
 */
set_meta('Content-Language', 'en', true);

