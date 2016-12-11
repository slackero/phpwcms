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
     1 => 'de',
     2 => 'fr',
     3 => 'it'
	 );
$phpwcms['lang_id'] = array_flip($phpwcms['id_lang']);

// The default menu entry ID (0 = root)
$phpwcms['nav_entry_id'] = 0;

// switch default date setting
if($phpwcms['default_lang'] != 'de') {
	$template_default['date']['language']		= strtoupper($phpwcms['default_lang']);
	$template_default['date']['short']			= 'y/m/d';
	$template_default['date']['article']		= 'Y/m/d';
	$template_default['news']['date_language']	= $template_default['date']['language'];
	$template_default['news']['date_format']	= $template_default['date']['article'];
}


/**
 * Search structe or article for the opposite alias
 */
function search_alias($type='article',$field='',$id='') {

	if ($type == 'category') {
		// test against category
		$where  = 'acat_trash=0 AND ';
		$where .= VISIBLE_MODE != 2 ? '' : 'acat_aktiv=1 AND acat_public=1 AND '; // handle admin/editor mode
		if ($field == 'id') {
			$where .= 'acat_id = '.$id;
		} else {
			$where .= 'acat_lang_id = '.$id;			
		}

		$result = _dbGet('phpwcms_articlecat', 'acat_alias', $where, '', '', 1);

		if(isset($result[0]['acat_alias'])) {
			return $result[0]['acat_alias'];
		}
	} else {
		// test against article
		$where  = 'article_deleted=0 AND article_begin < NOW() AND ';
		switch(VISIBLE_MODE) {
			case 0: $where .= 'article_public=1 AND article_aktiv=1 AND ';
					break;
			case 1: $where .= 'article_uid='._dbEscape($_SESSION["wcs_user_id"]).' AND ';
					break;
		}
		if ($field == 'id') {
			$where .= 'article_id = '.$id;
		} else {
			$where .= 'article_lang_id = '.$id;			
		}

		$result = _dbGet('phpwcms_article', 'article_alias', $where, '', '', 1);

		if(isset($result[0]['article_alias'])) {
			return $result[0]['article_alias'];
		}
	}
	
	return '';
}


?>