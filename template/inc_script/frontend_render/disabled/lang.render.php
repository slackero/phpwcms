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

/**
 * Set i18n default language
 */
$phpwcms['i18_lang'] = $phpwcms['default_lang'];

/**
 * By default we have no language switch,
 * because no multiple languages defined
 */
$content_search = [
    'switch_lang'  => '{SWITCH_LANG}',
    'current_lang' => '{LANG}',
];
$content_replace = [
    'switch_lang'  => '',
    'current_lang' => $phpwcms['i18_lang'],
];
$content_search_regexp  = [];
$content_replace_regexp = [];

/**
 * Try to catch current alias and match it against opposite language
 */

if (is_array($phpwcms['allowed_lang']) && count($phpwcms['allowed_lang']) > 1) {

    $content['auto_lang'] = [
        'current'       => $phpwcms['default_lang'],
        'topcount'      => $content['struct'][$content['cat_id']]['acat_topcount'],
        'struct_alias'  => $content['struct'][$content['cat_id']]['acat_alias'],
        'struct_depth'  => $LEVEL_KEY[$content['cat_id']],
        'preg_or'       => implode('|', $phpwcms['allowed_lang']),
        'match_left'    => [],
        'match_right'   => [],
        'is_left'       => false,
        'is_right'      => false,
        'opposite'      => [],
        'root_id'       => $phpwcms['lang_id'], // array_flip($phpwcms['id_lang']), yet done in init
        'lang_native'   => [
            'de' => ['DE', 'Deutsch'],
            'en' => ['EN', 'English'],
            'fr' => ['FR', 'Français'],
            'it' => ['IT', 'Italiano'],
            'es' => ['ES', 'Español'],
            'ru' => ['RU', 'Ру́сский'],
            'zh' => ['中文', '中文'],
        ],
    ];


    // regexp against allowed languages and any alias combination
    // like "de_alias" or "alias_de" or "de/alias"

    // by default, PHPWCMS_ALIAS contains alias of current article if in
    // article detailed mode or alias of current structure level

    // if current alias seems not to have an opposite language counter part
    // we will first test against current structure level and then against
    // parental structure level and so on

    // generally current alias is defined in constant PHPWCMS_ALIAS
    // first check if article detail mode is active

    // no article alias defined, use current constant
    if(empty($row['article_alias'])) {

        $content['auto_lang']['alias'] = PHPWCMS_ALIAS;

    // article alias different from current constant but the
    // topcount value of structure level is set to display article
    // so the current constant alias has preference
    } elseif($content['auto_lang']['topcount'] == -1 && $row['article_alias'] != PHPWCMS_ALIAS) {

        $content['auto_lang']['alias'] = PHPWCMS_ALIAS;

    // OK, fallback, use the article alias
    } else {

        $content['auto_lang']['alias'] = $row['article_alias'];

    }

    // loop as long it matches or we arrived the root level
    while(!match_alias_language()) {

        // root level, has no parent, so break
        if($content['auto_lang']['struct_depth'] < 0) {
            break;
        }

        if($content['auto_lang']['alias'] == $content['auto_lang']['struct_alias']) {
            $content['auto_lang']['struct_depth']--;
            if($content['auto_lang']['struct_depth'] < 0) {
                break;
            }
        }

        $content['auto_lang']['alias'] = $content['struct'][ $LEVEL_ID[ $content['auto_lang']['struct_depth'] ] ]['acat_alias'];

        $content['auto_lang']['struct_depth']--;

    }

    // build all possible opposite language related aliases
    // hide non-matching language sections
    foreach($phpwcms['allowed_lang'] as $lang) {

        $lang_active_class = '';
        $lang_switch_title = '@@' . $content['auto_lang']['lang_native'][$lang][1] . '@@';

        if( $content['auto_lang']['is_left'] ) {

            if($content['auto_lang']['match_left'][1] == $lang) {
                $lang_alias         = $content['auto_lang']['alias'];
                $lang_active_class  = ' lang-active';
            } else {
                $lang_alias         = search_opposite_alias( $lang . $content['auto_lang']['match_left'][2] );
            }

        } elseif($content['auto_lang']['is_right']) {

            if($content['auto_lang']['match_right'][2] == $lang) {
                $lang_alias         = $content['auto_lang']['alias'];
                $lang_active_class  = ' lang-active';
            } else {
                $lang_alias         = search_opposite_alias( $content['auto_lang']['match_right'][1] . $lang );
            }

        } else {

            $lang_alias = '';

        }

        // make the language link
        $content['auto_lang']['opposite'][$lang] = '';
        if($lang_alias == '') {
            if ($lang === PHPWCMS_ALIAS) {
                $lang_alias = PHPWCMS_ALIAS;
                $lang_active_class = ' lang-active';
            } else {
                $lang_root_id = $content['auto_lang']['root_id'][$lang];
                $content['auto_lang']['opposite'][$lang] .= !empty($content['struct'][$lang_root_id]['acat_alias']) ? $content['struct'][$lang_root_id]['acat_alias'] : 'id=' . $lang_root_id;
            }
        } else {
            $content['auto_lang']['opposite'][$lang] .= $lang_alias;
        }

        if(PHPWCMS_REWRITE) {
            $content['auto_lang']['opposite'][$lang] = '<a href="'.$content['auto_lang']['opposite'][$lang].PHPWCMS_REWRITE_EXT;
        } else {
            $content['auto_lang']['opposite'][$lang] = '<a href="index.php?'.$content['auto_lang']['opposite'][$lang];
        }

        $content['auto_lang']['opposite'][$lang] .= '" class="lang-switch lang-' . $lang . $lang_active_class . '" title="' . $lang_switch_title . '">';
        $content['auto_lang']['opposite'][$lang] .= $content['auto_lang']['lang_native'][$lang][0] . '</a>';

        $content_search_regexp[$lang]   = '/\['.$lang.'\](.*?)\[\/'.$lang.'\]/is';
        $content_replace_regexp[$lang]  = $lang_active_class ? '$1' : '';

    }

    // take the language switch menu
    $content_replace['switch_lang'] = implode(LF, $content['auto_lang']['opposite']);

    // build the main menu (Bootstrap styled)
    //$content_replace['nav_main'] = buildCascadingMenu('B,'.$phpwcms['nav_entry_id'].', , active|nav navbar-nav navbar-right, active'); //nav-justified

}


// Search and Replace
$content['pagetitle'] = str_replace($content_search, $content_replace, $content['pagetitle']);
$content["pagetitle"] = strip_tags(preg_replace($content_search_regexp, $content_replace_regexp, $content["pagetitle"]));

$content['all'] = str_replace($content_search, $content_replace, $content['all']);
$content['all'] = preg_replace($content_search_regexp, $content_replace_regexp, $content['all']);
