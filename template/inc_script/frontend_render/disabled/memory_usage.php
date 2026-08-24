<?php
// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

if (function_exists('memory_get_usage')) {
    $content['all'] .= '<div class="phpwcms-memory-usage">Memory: ' . round(memory_get_usage() / 1024 / 1024, 2) . ' MB</div>';
}

