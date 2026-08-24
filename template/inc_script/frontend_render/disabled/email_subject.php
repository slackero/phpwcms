<?php
// ----------------------------------------------------------------
// Obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

// Used to replace value of hidden form field by page title
// Use [%GLOBAL_FORM_SUBJECT%] as value for hidden form field in form content part

$content['all'] = str_replace(
    '[%GLOBAL_FORM_SUBJECT%]',
    trim('web form: ' . html_specialchars($content['article_title'])),
    $content['all']
);

