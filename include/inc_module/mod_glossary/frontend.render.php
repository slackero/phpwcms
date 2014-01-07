<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// Module/Plug-in Glossary frontend_render script
// use it as when it is located under "template/inc_script/frontend_render"

if(!isset($content['glossary'])) {
	
	// used to store glossary keywords which has been found
	$content['glossary_cache'] = array();


	function replace_glossary_tag($matches) {
		
		global $content;
		
		$inner = trim($matches[2]);
		
		// search keyword in glossary table
		$keyword = trim($matches[1]);
		if($keyword !== '') {
			$keyword = html_entity_decode($keyword, ENT_QUOTES, PHPWCMS_CHARSET);
			
			// check against cache
			if(!isset($content['glossary_cache'][$keyword])) {
			
				$like = aporeplace($keyword);
			
				$where  = 'glossary_status=1 AND glossary_highlight=1 AND (';
				$where .= "glossary_keyword LIKE '".$like."' OR ";
				$where .= "glossary_keyword LIKE '".$like.",%' OR ";
				$where .= "glossary_keyword LIKE '%, ".$like.",%' OR ";
				$where .= "glossary_keyword LIKE '%, ".$like."'";
				$where .= ')';
			
				// retrieve only single keyword that matches best
				$entry  = _dbGet('phpwcms_glossary', 'glossary_title, glossary_keyword, glossary_text, COUNT(glossary_id) AS count_all', $where, 'glossary_id', 'count_all DESC', '1');
				
				if(isset($entry[0])) {
					
					// get keywords to store each in cache
					$keywords	= convertStringToArray($entry[0]['glossary_keyword']);
					$title		= empty($entry[0]['glossary_title']) ? $inner : html_specialchars($entry[0]['glossary_title']);
					$text		= trim(clean_slweg($entry[0]['glossary_text']));
				
					// store glossary item in cache
					foreach($keywords as $key) {
						$content['glossary_cache'][$key] = array( 'title' => $title, 'text' => $text );
					}
				
				}
			}
			
			// create ABBR
			if(isset($content['glossary_cache'][$keyword])) {
			
				$inner =	'<abbr class="glossary" title="'.$content['glossary_cache'][$keyword]['title'].
							' :: '.
							$content['glossary_cache'][$keyword]['text'].
							'">'.$inner.'</abbr>';
			}
			
		}
		
		return $inner;
		
	}
	
	// Search for glossary tag
	$content['all'] = preg_replace_callback('/\[glossary (.*?)\](.*?)\[\/glossary\]/i', 'replace_glossary_tag', $content['all']);
	
	


	/*
	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_glossary WHERE ';
	$sql .= 'glossary_status=1 AND glossary_highlight=1 AND glossary_keyword != ""';
	
	$glossary['keywords'] = _dbGet('phpwcms_glossary', );
	
	if($glossary['keywords'] !== false && is_array($glossary['keywords']) && count($glossary['keywords'])) {
	
		// OK - fine we have found all glossary words
		
		$glossary['tags'] = array();
		
		foreach($glossary['keywords'] as $value) {
			$value['glossary_tag'] = convertStringToArray(strtolower($value['glossary_tag']), ' ');
			foreach($value['glossary_tag'] as $value) {
				$glossary['tags'][$value] = $value;
			}
		}
		
		// now lets search for glossary content parts
		$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_articlecontent ac ';
		$sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_article ar ON ac.acontent_aid = ar.article_id ';
		$sql .= 'WHERE ar.article_public=1 AND ar.article_aktiv=1 AND ';
		$sql .= 'ar.article_deleted=0 AND ar.article_begin<NOW() AND ';
		$sql .= 'ar.article_end>NOW() AND ac.acontent_visible = 1 AND ac.acontent_module = "glossary"';
		
		$glossary['cp'] = @_dbQuery($sql);
		
		if(is_array($glossary['cp']) && count($glossary['cp'])) {
		
			foreach($glossary['cp'] as $key => $value) {
				
				if(!isset($content['struct'][ $value['article_cid'] ])) {
					unset($glossary['cp'][$key]);
					continue;
				}
				$glossary['cp'][$key]['acontent_form'] = unserialize($value['acontent_form']);
				$glossary['cp'][$key]['acontent_form']['acontent_id']  = $value['acontent_id'];
				$glossary['cp'][$key]['acontent_form']['acontent_aid'] = $value['acontent_aid'];
				$glossary['cp'][$key]['acontent_form']['article_cid'] = $value['article_cid'];
				
				$glossary['cp'][$key] = $glossary['cp'][$key]['acontent_form'];
		
			}
		
		}
	
	//dumpVar($glossary['tags']);
	//dumpVar($glossary['cp']);
	//dumpVar($glossary['keywords']);
		
	}
	
	*/

}

?>