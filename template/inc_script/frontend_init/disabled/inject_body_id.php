<?php
// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

/**
 * Sample how to inject body ID - e.g. for specific CSS styling.
 * If you define $content['body_id'] = false, body tag injection is skipped.
 */

if (isset($LEVEL_ID[1])) {
    $content['body_id'] = $LEVEL_ID[1];
} else {
    $content['body_id'] = false;
}

