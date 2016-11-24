<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$content['all'] .= dumpVar($LEVEL_ID, 2);
$content['all'] .= dumpVar($LEVEL_KEY, 2);
$content['all'] .= dumpVar($LEVEL_STRUCT, 2);
$content['all'] .= dumpVar($content['struct'], 2);
