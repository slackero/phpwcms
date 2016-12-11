<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2015, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

/**
 * Set i18n default language
 */
$phpwcms['i18_lang'] = $phpwcms['default_lang'];

/**
 * By default we have no language switch,
 * because no multiple languages defined
 */
$content_search = array(
	'nav_main' => '{NAV_MAIN}',
	'switch_lang' => '{SWITCH_LANG}',
	'switch_lang_ul' => '{SWITCH_LANG_UL}',
	'current_lang' => '{LANG}'
);
$content_replace = array(
	'nav_main' => '',
	'switch_lang' => '',
	'switch_lang_ul' => '',
	'current_lang' => ''
);
$content_search_regexp = array();
$content_replace_regexp = array();

/**
 * Try to catch current alias and match it against opposite language
 *
 */

if(is_array($phpwcms['allowed_lang']) && count($phpwcms['allowed_lang']) > 1) {

	$content['auto_lang'] = array(
		'current'		=> $phpwcms['default_lang'],
		'topcount'		=> $content['struct'][ $content['cat_id'] ]['acat_topcount'],
		'struct_alias'	=> $content['struct'][ $content['cat_id'] ]['acat_alias'],
		'struct_depth'	=> $LEVEL_KEY[ $content['cat_id'] ],
		'preg_or'		=> implode('|', $phpwcms['allowed_lang']),
		'match_left'	=> array(),
		'match_right'	=> array(),
		'is_left'		=> false,
		'is_right'		=> false,
		'opposite'		=> array(),
		'root_id'		=> $phpwcms['lang_id'],
		'lang_native'	=> array(
			'it' => array('IT', 'ITALIANO'),
			'de' => array('DE', 'DEUTSCH')
		)
	);
	

	
	$content_replace['current_lang'] = $content['struct'][ $GLOBALS['LEVEL_ID'][1] ]['acat_name'];
	
	// build all possible opposite language related aliases
	// hide non-matching language sections
	foreach($phpwcms['allowed_lang'] as $lang) {

		$lang_active_class = '';
		$lang_switch_title = '' . $content['auto_lang']['lang_native'][$lang][1] . '';

		if($content_replace['current_lang'] == $lang) {
			$lang_alias			= '';
			$lang_active_class	= ' selected';
		} elseif ($content['auto_lang']['current'] == $lang) {
			if (intval($row['article_lang_id']) > 0) {
				$lang_alias	= search_alias($row['article_lang_type'], 'id', $row['article_lang_id'] );
			} else {
				$lang_alias = $lang;
			}
		} else {
			$lang_alias	= search_alias($row['article_lang_type'], 'lang_id', $row['article_id'] );
		}
	
		// make the language link
		$content['auto_lang']['opposite'][$lang]  = '';
		if($lang_alias == '') {
			$lang_root_id = $content['auto_lang']['root_id'][$lang];
			$content['auto_lang']['opposite'][$lang] .= !empty( $content['struct'][ $lang_root_id ]['acat_alias'] ) ? $content['struct'][ $lang_root_id ]['acat_alias'] : 'id='.$lang_root_id;
		} else {
			$content['auto_lang']['opposite'][$lang] .= $lang_alias;
		}

		if(PHPWCMS_REWRITE) {
			$content['auto_lang']['textlink'][$lang] = '<a href="'.$content['auto_lang']['opposite'][$lang].PHPWCMS_REWRITE_EXT;
			$content['auto_lang']['opposite'][$lang] = '<option value="/'.$content['auto_lang']['opposite'][$lang].PHPWCMS_REWRITE_EXT;
		} else {
			$content['auto_lang']['textlink'][$lang] = '<a href="/index.php?'.$content['auto_lang']['opposite'][$lang];
			$content['auto_lang']['opposite'][$lang] = '<option value="index.php?'.$content['auto_lang']['opposite'][$lang];
		}

		$content['auto_lang']['opposite'][$lang] .= '" ' . $lang_active_class . '>';
		$content['auto_lang']['opposite'][$lang] .= $content['auto_lang']['lang_native'][$lang][0] . '</option>';
		$content['auto_lang']['textlink'][$lang] .= '">' . $lang_switch_title .'</a>';

		if($content_replace['current_lang'] == $lang) {
			$content['auto_lang']['textlink'][$lang] = $lang_switch_title;
		}

		$content_search_regexp[$lang]	= '/\['.$lang.'\](.*?)\[\/'.$lang.'\]/is';
		$content_replace_regexp[$lang]	= $lang_active_class ? '$1' : '';

	}
	
	// take the language switch menu
	$content_replace['switch_lang'] = implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $content['auto_lang']['textlink']);
	$content_replace['switch_lang_ul'] = '<div class="form-group"><label class="select"><select class="form-control" id="languageselector">' . implode(LF, $content['auto_lang']['opposite']) . '</select><i></i></label></div>';
	
}

$content['all'] = str_replace($content_search, $content_replace, $content['all']);

?>