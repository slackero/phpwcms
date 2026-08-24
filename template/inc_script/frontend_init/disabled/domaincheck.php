<?php
// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

// Compare against current domain and redirect to correct if necessary

// Check active domain
if (isset($LEVEL_ID[1]) && $LEVEL_ID[1] === 1 && strpos(PHPWCMS_URL, 'mydomain1.com') === false) {

    headerRedirect('https://www.mydomain1.com/' . rel_url([], [], '', 'urlencode'));

} elseif (isset($LEVEL_ID[1]) && $LEVEL_ID[1] === 2 && strpos(PHPWCMS_URL, 'mydomain2.com') === false) {

    headerRedirect('https://www.mydomain2.com/' . rel_url([], [], '', 'urlencode'));

}

