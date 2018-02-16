<?php

/**
 * Overwrite or extend keywords
 */

if(empty($content['all_keywords'])) {
    $content['all_keywords'] = 'set, my, default, keywords';
} else {
    $content['all_keywords'] .= ', add, my, default, keywords';
}

/**
 * Whenever you like or need set custom page description
 */
set_meta('description', 'This is my description for just a test');

/**
 * No problem to set more or different meta tags when needed
 */
set_meta('robots', 'index,follow');

/**
 * It is also easy to set http-equiv meta tags
 */
set_meta('Content-Language', 'en', TRUE);
