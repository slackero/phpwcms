<?php
/**
 * phpwcms
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

// Retrieve the list of entries from online form

// First some settings

// Replacement tag
$_form_entries['RT'] = '{FORM_RESULT_LISTING}';

// Insert the ID of the form content part here
$_form_entries['FORM_ID'] = 270;

// Which form fields should be listed - comma separated
$_form_entries['FIELDS'] = 'firstname,name,email';

// Template settings
$_form_entries['HEADER'] = '<table class="table listing" summary="form entries">' . LF .
    '    <thead>' . LF .
    '        <tr>' . LF .
    '            <th>@@First name@@</th>' . LF .
    '            <th>@@Last name@@</th>' . LF .
    '            <th>@@Email@@</th>' . LF .
    '        </tr>' . LF .
    '    </thead>' . LF .
    '    <tbody>' . LF;

$_form_entries['FOOTER'] = '    </tbody>' . LF . '</table>';

$_form_entries['SPACER'] = '';

$_form_entries['ENTRY']  = '        <tr>' . LF .
    '            <td>{firstname}</td>' . LF .
    '            <td>{name}</td>' . LF .
    '            <td>{email}</td>' . LF .
    '        </tr>' . LF;

////////// Do not change below //////////////////////////////

if (strpos($content['all'], $_form_entries['RT']) !== false) {

    $_form_entries['RESULT']  = [];
    $_form_entries['ALL']     = _dbQuery('SELECT * FROM ' . DB_PREPEND . 'phpwcms_formresult WHERE formresult_pid=' . (int)$_form_entries['FORM_ID']);
    $_form_entries['FIELDS']  = convertStringToArray($_form_entries['FIELDS']);
    $_form_entries['SELECT']  = [];
    $_form_entries['ENTRIES'] = [];

    foreach ($_form_entries['FIELDS'] as $_form_entries_value) {
        $_form_entries['SELECT'][$_form_entries_value] = $_form_entries_value;
    }

    $_fc = 0;
    if (is_array($_form_entries['ALL'])) {
        foreach ($_form_entries['ALL'] as $_form_entries_value) {
            $_form_entries['ENTRIES'][$_fc] = $_form_entries['ENTRY'];
            $_unserialized = @unserialize($_form_entries_value['formresult_content'], ['allowed_classes' => false]);
            if (is_array($_unserialized)) {
                foreach ($_unserialized as $_form_entries_key => $_form_entries_value1) {
                    if (isset($_form_entries['SELECT'][$_form_entries_key])) {
                        $_form_entries['ENTRIES'][$_fc] = str_replace('{' . $_form_entries_key . '}', html_specialchars((string)$_form_entries_value1), $_form_entries['ENTRIES'][$_fc]);
                    }
                }
            }
            $_fc++;
        }
    }

    if (count($_form_entries['ENTRIES'])) {
        $_form_entries['ENTRIES'] = implode($_form_entries['SPACER'], $_form_entries['ENTRIES']);
        $content['all'] = str_replace($_form_entries['RT'], $_form_entries['HEADER'] . $_form_entries['ENTRIES'] . $_form_entries['FOOTER'], $content['all']);
    } else {
        $content['all'] = str_replace($_form_entries['RT'], '', $content['all']);
    }

}

