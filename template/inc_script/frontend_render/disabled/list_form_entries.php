<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2017, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// retrieve the list of entries from online form

// first some settings

// replacement tag
$_form_entries['RT'] = '{FORM_RESULT_LISTING}';

// insert the ID of the content part here
$_form_entries['FORM_ID'] = 1;

// which form fields should be listed - comma separated
$_form_entries['FIELDS'] = 'firstname,name,email';

$_form_entries['PAGINATE_GET'] = 'page';

$_form_entries['PAGINATE'] = 1; // or null if no pagination
// now some template settings

$_form_entries['PAGINATION'] = '<div class="pagination">Page %1$d of %2$d | <span class="page-nav">%3$s %4$s</span></div>';
$_form_entries['PAGINATION_NEXT'] = 'next&raquo;';
$_form_entries['PAGINATION_PREV'] = '&laquo;previous';

// Header - put in the header section here
$_form_entries['HEADER'] = '{PAGINATION}
<table cellpadding="0" cellspacing="0" border="0" class="listing" summary="form entries">
    <tr>
        <th>Vorname</th>
        <th>Name</th>
        <th>E-Mail</th>
    </tr>
';

// Footer - put in the footer section here
$_form_entries['FOOTER'] = '</table>{PAGINATION}';

// Spacer - his is placed between each line of entries
$_form_entries['SPACER'] = '<tr><td colspan="4">&nbsp;</td></tr>';

$_form_entries['ENTRY'] = '
    <tr>
        <td>{firstname}</td>
        <td>{name}</td>
        <td>{email}</td>
    </tr>
';

////////// Do not change below //////////////////////////////

if(strpos($content['all'], $_form_entries['RT']) !== false) {


    $_form_entries['RESULT']    = array();
    $_form_entries['FORM_ID']   = intval($_form_entries['FORM_ID']);

    if($_form_entries['PAGINATE'] !== null) {
        $_form_entries['COUNT'] = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND.'phpwcms_formresult WHERE formresult_pid='.$_form_entries['FORM_ID'], 'COUNT');

        if($_form_entries['COUNT'] > $_form_entries['PAGINATE']) {
            $_form_entries['PAGE_TOTAL'] = ceil($_form_entries['COUNT'] / $_form_entries['PAGINATE']);
            $_form_entries['PAGE'] = isset($_GET[$_form_entries['PAGINATE_GET']]) ? intval($_GET[$_form_entries['PAGINATE_GET']]) : 1;
            if($_form_entries['PAGE'] < 1) {
                $_form_entries['PAGE'] = 1;
            } elseif($_form_entries['PAGE'] > $_form_entries['PAGE_TOTAL']) {
                $_form_entries['PAGE'] = $_form_entries['PAGE_TOTAL'];
            }
            $_form_entries['LIMIT'] = ' LIMIT ' . (($_form_entries['PAGE'] - 1) * $_form_entries['PAGINATE']) . ',' . $_form_entries['PAGINATE'];

            if($_form_entries['PAGE'] < $_form_entries['PAGE_TOTAL']) {
                $_form_entries['PAGINATION_NEXT_LINK'] = '<a href="'.rel_url(array($_form_entries['PAGINATE_GET']=>($_form_entries['PAGE']+1))).'" class="page-next">'.$_form_entries['PAGINATION_NEXT'].'</a>';
            } else {
                $_form_entries['PAGINATION_NEXT_LINK'] = '<span class="page-next disabled">'.$_form_entries['PAGINATION_NEXT'].'</span>';
            }

            if($_form_entries['PAGE'] > 1) {
                $_form_entries['PAGINATION_PREV_LINK'] = '<a href="'.rel_url(array($_form_entries['PAGINATE_GET']=>($_form_entries['PAGE']-1))).'" class="page-prev">'.$_form_entries['PAGINATION_PREV'].'</a>';
            } else {
                $_form_entries['PAGINATION_PREV_LINK'] = '<span class="page-next disabled">'.$_form_entries['PAGINATION_PREV'].'</span>';
            }

            $_form_entries['PAGINATION'] = sprintf($_form_entries['PAGINATION'], $_form_entries['PAGE'], $_form_entries['PAGE_TOTAL'], $_form_entries['PAGINATION_PREV_LINK'], $_form_entries['PAGINATION_NEXT_LINK']);

        } else {
            $_form_entries['LIMIT'] = '';
            $_form_entries['PAGINATE'] = null;
            $_form_entries['PAGINATION'] = '';
        }

    } else {
        $_form_entries['LIMIT'] = '';
        $_form_entries['PAGINATE'] = null;
        $_form_entries['PAGINATION'] = '';
    }

    $_form_entries['ALL']       = _dbQuery("SELECT * FROM ".DB_PREPEND.'phpwcms_formresult WHERE formresult_pid='.$_form_entries['FORM_ID'].$_form_entries['LIMIT']);
    $_form_entries['FIELDS']    = convertStringToArray($_form_entries['FIELDS']);
    $_form_entries['SELECT']    = array();
    $_form_entries['ENTRIES']   = array();
    foreach($_form_entries['FIELDS'] as $_form_entries_value) {
        $_form_entries['SELECT'][$_form_entries_value] = $_form_entries_value;
    }

    $_fc = 0;
    foreach($_form_entries['ALL'] as $_form_entries_value) {

        $_form_entries['ENTRIES'][$_fc] = $_form_entries['ENTRY'];
        $_form_entries_value = @unserialize($_form_entries_value['formresult_content']);
        foreach($_form_entries_value as $_form_entries_key => $_form_entries_value1) {

            if(isset($_form_entries['SELECT'][$_form_entries_key])) {
                $_form_entries['ENTRIES'][$_fc] = str_replace('{'.$_form_entries_key.'}', html_specialchars($_form_entries_value1), $_form_entries['ENTRIES'][$_fc]);
            }

        }

        $_fc++;

    }

    if(count($_form_entries['ENTRIES'])) {

        $_form_entries['HEADER'] = str_replace('{PAGINATION}', $_form_entries['PAGINATION'], $_form_entries['HEADER']);
        $_form_entries['FOOTER'] = str_replace('{PAGINATION}', $_form_entries['PAGINATION'], $_form_entries['FOOTER']);

        $_form_entries['ENTRIES'] = implode($_form_entries['SPACER'], $_form_entries['ENTRIES']);
        $content['all'] = str_replace($_form_entries['RT'], $_form_entries['HEADER'].$_form_entries['ENTRIES'].$_form_entries['FOOTER'], $content['all']);

    } else {

        $content['all'] = str_replace($_form_entries['RT'], '', $content['all']);

    }

}
