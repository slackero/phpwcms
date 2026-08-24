<?php
// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

if (strpos($content['all'], '[LOGGED_IN') !== false) {
    $content['all'] = render_cnt_template($content['all'], 'LOGGED_IN', _getFeUserLoginStatus() ? '<!-- //-->' : '');
}

