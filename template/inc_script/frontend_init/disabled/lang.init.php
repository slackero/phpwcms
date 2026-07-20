<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// Define some general language specifics

/**
 * language setting based on site tree
 * $phpwcms['allowed_lang'] = array('en', 'de', 'fr', 'es');
 * - DE
 * - EN
 * - FR
 * - ES
 * ...
 */
$phpwcms['id_lang'] = array(
 // ID    LANG
     1 => 'de',
     2 => 'en',
     3 => 'fr',
     4 => 'es'
);
$phpwcms['lang_id'] = array_flip($phpwcms['id_lang']);

// The default menu entry ID (0 = root)
$phpwcms['nav_entry_id'] = 0;

// Redirect to default language entry based on browser
if(!isset($LEVEL_ID[1])) {

    // try to link user to correct language
    $phpwcms['DOCTYPE_LANG'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)) : $phpwcms['default_lang'];
    if(!isset($phpwcms['lang_id'][$phpwcms['DOCTYPE_LANG']])) {
        $phpwcms['DOCTYPE_LANG'] = $phpwcms['default_lang'];
    }

    // Get current alias
    $pagelink = empty($content['struct'][ $phpwcms['lang_id'][$phpwcms['DOCTYPE_LANG']] ]['acat_alias']) ? 'id='.$phpwcms['lang_id'][$phpwcms['DOCTYPE_LANG']] : $content['struct'][ $phpwcms['lang_id'][$phpwcms['DOCTYPE_LANG']] ]['acat_alias'];
    $pagelink = $phpwcms["rewrite_url"] ? $pagelink . PHPWCMS_REWRITE_EXT : 'index.php?' . $pagelink;

    // Redirect
    headerRedirect(PHPWCMS_URL . $pagelink, 301);

} elseif(isset($phpwcms['id_lang'][ $LEVEL_ID[1] ])) {

    $phpwcms['DOCTYPE_LANG'] = $phpwcms['default_lang'] = $phpwcms['id_lang'][ $LEVEL_ID[1] ];

    // Take the current LEVEL 1 as nav entry
    $phpwcms['nav_entry_id'] = $LEVEL_ID[1];

} else {

    $phpwcms['DOCTYPE_LANG'] = $phpwcms['default_lang'];

}

// switch default date setting
if($phpwcms['default_lang'] != 'de') {
    $template_default['date']['language']       = strtoupper($phpwcms['default_lang']);
    $template_default['date']['short']          = 'y/m/d';
    $template_default['date']['article']        = 'Y/m/d';
    $template_default['news']['date_language']  = $template_default['date']['language'];
    $template_default['news']['date_format']    = $template_default['date']['article'];
}

/**
 * Try to get the base alias: "de_alias" => "_alias" or "alias_de" => "alias_"
 */
function match_alias_language() {

    global $content;

    $lang_match_spacer = PHPWCMS_ALIAS_WSLASH ? '[_\/]' : '_';

    // check if the short language code matches left side first
    preg_match(
        '/^(' . $content['auto_lang']['preg_or'] . ')(' . $lang_match_spacer . '.+)$/i',
        $content['auto_lang']['alias'],
        $content['auto_lang']['match_left']
    );

    if(isset($content['auto_lang']['match_left'][1])) {

        $content['auto_lang']['is_left'] = true;

        return TRUE;

    }

    // check if the short language code matches right side
    preg_match(
        '/^(.+' . $lang_match_spacer . ')(' . $content['auto_lang']['preg_or'] . ')$/i',
        $content['auto_lang']['alias'],
        $content['auto_lang']['match_right']
    );

    if(isset($content['auto_lang']['match_right'][1])) {

        $content['auto_lang']['is_right'] = true;

        return TRUE;

    }

    return false;

}

/**
 * Search structe or article for the opposite alias
 */
function search_opposite_alias($alias='') {

    $alias = _dbEscape($alias); // escape only once

    // test structure against alias first, is more common and
    // maybe we will often see parental level fallback
    $where  = 'acat_trash=0 AND ';
    $where .= VISIBLE_MODE != 2 ? '' : 'acat_aktiv=1 AND acat_public=1 AND '; // handle admin/editor mode
    $where .= 'acat_alias LIKE '.$alias; // use LIKE because will match also against uper/lower case, even it is a bit slower

    $result = _dbGet('phpwcms_articlecat', 'acat_alias', $where, '', '', 1);

    if(isset($result[0]['acat_alias'])) {
        return $result[0]['acat_alias'];
    }

    // test against article ID
    $where  = 'article_deleted=0 AND article_begin < NOW() AND ';
    switch(VISIBLE_MODE) {
        case 0: $where .= 'article_public=1 AND article_aktiv=1 AND ';
                break;
        case 1: $where .= 'article_uid='._dbEscape($_SESSION["wcs_user_id"]).' AND ';
                break;
    }
    $where .= 'article_alias LIKE '.$alias; // use LIKE because will match also against uper/lower case, even it is a bit slower

    $result = _dbGet('phpwcms_article', 'article_alias', $where, '', '', 1);

    if(isset($result[0]['article_alias'])) {
        return $result[0]['article_alias'];
    }

    return '';
}

/**
 * Search the opposite language based on ID
 */
function get_opposite_language() {




}
