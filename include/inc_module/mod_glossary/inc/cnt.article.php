<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Glossary module content part frontend article rendering

// if you ant to access module vars check that var
// $phpwcms['modules'][$crow["acontent_module"]]

$content['glossary'] = @unserialize($crow["acontent_form"]);

// check for template and load default in case of error
if(empty($content['glossary']['glossary_template'])) {

    // load default template
    $content['glossary']['glossary_template'] = file_get_contents($phpwcms['modules'][$crow["acontent_module"]]['path'].'template/default/default.tmpl');

} elseif(file_exists($phpwcms['modules'][$crow["acontent_module"]]['path'].'template/'.$content['glossary']['glossary_template'])) {

    // load custom template
    $content['glossary']['glossary_template'] = file_get_contents($phpwcms['modules'][$crow["acontent_module"]]['path'].'template/'.$content['glossary']['glossary_template']);

} else {

    // again load default template
    $content['glossary']['glossary_template'] = file_get_contents($phpwcms['modules'][$crow["acontent_module"]]['path'].'template/default/default.tmpl');

}

$content['glossary']['where'] = '';

if(!empty($content['glossary']['glossary_tag'])) {
    $content['glossary']['glossary_tag'] = convertStringToArray($content['glossary']['glossary_tag'], ' ');
    foreach($content['glossary']['glossary_tag'] as $_filter_c => $content['glossary']['char']) {
        $content['glossary']['glossary_tag'][$_filter_c] = "glossary_tag LIKE '%".aporeplace($content['glossary']['char'])."%'";
    }
    if(count($content['glossary']['glossary_tag'])) {
        $content['glossary']['where'] .= ' AND ('.implode(' OR ', $content['glossary']['glossary_tag']).')';
    }
}


// and now lets check where we are - listing mode or detail view
if(!empty($GLOBALS['_getVar']['glossaryid'])) {

    $GLOBALS['_getVar']['glossaryid'] = intval($GLOBALS['_getVar']['glossaryid']);

    // get detail entry template sections
    $content['glossary']['detail_head']     = get_tmpl_section('GLOSSARY_DETAIL_HEAD',      $content['glossary']['glossary_template']);
    $content['glossary']['detail_footer']   = get_tmpl_section('GLOSSARY_DETAIL_FOOTER',    $content['glossary']['glossary_template']);
    $content['glossary']['detail_entry']    = get_tmpl_section('GLOSSARY_DETAIL_ENTRY',     $content['glossary']['glossary_template']);

    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_glossary WHERE glossary_status=1 ';
    $sql .= 'AND glossary_id='.$GLOBALS['_getVar']['glossaryid'];
    $sql .= $content['glossary']['where'];
    $content['glossary']['entry'] = _dbQuery($sql);
    if(empty($content['glossary']['entry'][0])) {

        $content['glossary']['entry']['glossary_title'] = '';
        $content['glossary']['entry']['glossary_text']  = $content['glossary']['glossary_noentry'];
        $content['glossary']['entry']['glossary_id']    = 'empty-glossary-id';

    } else {

        $content['glossary']['entry'] = $content['glossary']['entry'][0];

    }

    unset($GLOBALS['_getVar']['glossaryid']);
    unset($GLOBALS['_getVar']['glossarytitle']);

    $content['glossary']['detail_entry']    = get_tmpl_section('GLOSSARY_DETAIL_ENTRY',     $content['glossary']['glossary_template']);
    $content['glossary']['detail_entry']    = render_cnt_template($content['glossary']['detail_entry'], 'TEXT', $content['glossary']['entry']['glossary_text']);
    $content['glossary']['detail_entry']    = render_cnt_template($content['glossary']['detail_entry'], 'TITLE', html_specialchars($content['glossary']['entry']['glossary_title']));

    $content['glossary']['item'] = $content['glossary']['detail_head'] . $content['glossary']['detail_entry'] . $content['glossary']['detail_footer'];
    $content['glossary']['item'] = str_replace('{GLOSSARY_ID}', $content['glossary']['entry']['glossary_id'], $content['glossary']['item']);
    $content['glossary']['item'] = str_replace('{BACKLINK}', rel_url(), $content['glossary']['item']);

    // fine we will display given glossary ID
    $CNT_TMP .= $content['glossary']['item'];

} else {

    // get list entries template sections
    $content['glossary']['list_head']       = get_tmpl_section('GLOSSARY_LIST_HEAD',        $content['glossary']['glossary_template']);
    $content['glossary']['list_footer']     = get_tmpl_section('GLOSSARY_LIST_FOOTER',      $content['glossary']['glossary_template']);
    $content['glossary']['list_entry']      = get_tmpl_section('GLOSSARY_LIST_ENTRY',       $content['glossary']['glossary_template']);
    $content['glossary']['list_spacer']     = get_tmpl_section('GLOSSARY_LIST_SPACER',      $content['glossary']['glossary_template']);

    // OK we build filter
    $content['glossary']['glossary_alphabet']       = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $content['glossary']['glossary_filter']         = convertStringToArray(strtoupper($content['glossary']['glossary_filter']), ' ');
    $content['glossary']['glossary_filter_active']  = empty($GLOBALS['_getVar']['glossary']) ? '' : strtoupper(clean_slweg($GLOBALS['_getVar']['glossary']));

    if(in_array($content['glossary']['glossary_filter_active'], $content['glossary']['glossary_filter'])) {

        // build SQL query
        if(strpos($content['glossary']['glossary_filter_active'], '-')) {
            $content['glossary']['filter']      = explode('-', $content['glossary']['glossary_filter_active']);
            $content['glossary']['filter'][0]   = substr($content['glossary']['filter'][0], 0, 1);
            $content['glossary']['filter'][1]   = empty($content['glossary']['filter'][1]) ? '?' : substr($content['glossary']['filter'][1], 0, 1);
            // is there start and end
            if(strpos($content['glossary']['glossary_alphabet'], $content['glossary']['filter'][0]) !== false && strpos($content['glossary']['glossary_alphabet'], $content['glossary']['filter'][1]) !== false) {

                $content['glossary']['glossary_alphabet']   = preg_split('//', $content['glossary']['glossary_alphabet'], -1, PREG_SPLIT_NO_EMPTY);
                $content['glossary']['filters']             = array();
                $content['glossary']['filter_run']          = false;
                foreach($content['glossary']['glossary_alphabet'] as $content['glossary']['char']) {

                    // OK start here
                    if($content['glossary']['char'] == $content['glossary']['filter'][0]) {
                        $content['glossary']['filter_run']  = true;
                    }
                    if($content['glossary']['filter_run']) {
                        //$content['glossary']['filters'][] = "TRIM(CONCAT(glossary_tag, glossary_title)) LIKE '".aporeplace($content['glossary']['char'])."%'";
                        $content['glossary']['filters'][] = "glossary_title LIKE '".aporeplace($content['glossary']['char'])."%'";
                    }
                    if($content['glossary']['char'] == $content['glossary']['filter'][1]) {
                        break;
                    }

                }

                if(count($content['glossary']['filters'])) {

                    $content['glossary']['where'] = ' AND ('.implode(' OR ', $content['glossary']['filters']).')';

                }

            }

        } elseif($content['glossary']['glossary_filter_active'] !== '*' && strlen($content['glossary']['glossary_filter_active']) == 1) {
            $content['glossary']['where'] = " AND glossary_title LIKE '".aporeplace($content['glossary']['glossary_filter_active'])."%'";
        }
    }

    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_glossary WHERE glossary_status=1'.$content['glossary']['where'].' ORDER BY glossary_title';

    $content['glossary']['entries'] = _dbQuery($sql);

    $_filter_link   = array();
    $_filter_c      = 0;
    foreach($content['glossary']['glossary_filter'] as $content['glossary']['filter_value']) {
        $_filter_link[$_filter_c] = '<a href="'.rel_url(array('glossary' => $content['glossary']['filter_value'])).'"';
        // yes - this is the active part
        if($content['glossary']['filter_value'] == $content['glossary']['glossary_filter_active']) {
            $_filter_link[$_filter_c] .= ' class="active"';
        }
        $_filter_entities = html($content['glossary']['filter_value']);
        $_filter_link[$_filter_c] .= ' title="'.$_filter_entities.'">';
        $_filter_link[$_filter_c] .= $_filter_entities.'</a>';
        $_filter_c++;
    }

    $_filter_link = implode(' ', $_filter_link);

    $CNT_TMP .= render_cnt_template($content['glossary']['list_head'], 'FILTER', $_filter_link);

    if(!count($content['glossary']['entries'])) {

        $content['glossary']['entries'][0]['glossary_title']    = '';
        $content['glossary']['entries'][0]['glossary_text']     = $content['glossary']['glossary_noentry'];
        $content['glossary']['entries'][0]['glossary_id']       = 0;

        $_no_entry = true;

    } else {

        $_no_entry = false;

    }

    foreach($content['glossary']['entries'] as $_entry_key => $_entry_value) {

        $content['glossary']['entries'][$_entry_key] = str_replace('{GLOSSARY_ID}', $_entry_value['glossary_id'], $content['glossary']['list_entry']);
        $content['glossary']['entries'][$_entry_key] = str_replace('{LINK}', $_no_entry ? '#' : rel_url(array('glossaryid' => $_entry_value['glossary_id'], 'glossarytitle' => $_entry_value['glossary_title'])), $content['glossary']['entries'][$_entry_key]);
        $content['glossary']['entries'][$_entry_key] = render_cnt_template($content['glossary']['entries'][$_entry_key], 'TITLE', html_specialchars($_entry_value['glossary_title']));

        if(!empty($content['glossary']['glossary_maxwords']) && !$_no_entry) {
            $_entry_value['glossary_text'] = getCleanSubString(strip_tags($_entry_value['glossary_text']), $content['glossary']['glossary_maxwords'], $template_default['ellipse_sign'], 'word');
        }
        $content['glossary']['entries'][$_entry_key] = render_cnt_template($content['glossary']['entries'][$_entry_key], 'TEXT', $_entry_value['glossary_text']);

    }

    $CNT_TMP .= implode($content['glossary']['list_spacer'] ,$content['glossary']['entries']);
    $CNT_TMP .= render_cnt_template($content['glossary']['list_footer'], 'FILTER', $_filter_link);

    unset($GLOBALS['_getVar']['glossary']);

}

// render content part title/subtitle
$CNT_TMP = render_cnt_template($CNT_TMP, 'CP_TITLE', html_specialchars($crow['acontent_title']));
$CNT_TMP = render_cnt_template($CNT_TMP, 'CP_SUBTITLE', html_specialchars($crow['acontent_subtitle']));
