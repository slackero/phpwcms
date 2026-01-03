<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
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
			$keyword = html_entity_decode($keyword, ENT_QUOTES, CMSGO_CHARSET);

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
				$entry  = _dbGet('cmsgo_glossary', 'glossary_title, glossary_keyword, glossary_text, COUNT(glossary_id) AS count_all', $where, 'glossary_id', 'count_all DESC', '1');

				if(isset($entry[0])) {

					// get keywords to store each in cache
					$keywords	= convertStringToArray($entry[0]['glossary_keyword']);
					$title		= empty($entry[0]['glossary_title']) ? $inner : html($entry[0]['glossary_title']);
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
}
