<?php

// list and search help for recipes

if(!(strpos($content["all"], '{RECIPES:') === false)) {

// define neccessary functions only when RT is in use

	function doRecipes($matches)
	{

		global $_getVar;

		// split into array by ","
		$recipe = convertStringToArray($matches[1]);
		// get additional values for each option
		foreach($recipe as $key => $value) {
			$recipe[ $key ] = explode('|', $value);
			$optindex = $recipe[ $key ][0];
			$option[ $optindex ] = array();
			unset($recipe[ $key ][0]);
			if(count($recipe[ $key ])) {
				$c = 0;
				foreach($recipe[ $key ] as $optval) {
					$option[ $optindex ][ $c ] = $optval;
					$c++;
				}
			}
		}

		$recipe = '';

		if(isset($option['LOCALE']) && !empty($option['LOCALE'][0])) {
			$_oldLocale = setlocale(LC_ALL, null);
			setlocale(LC_ALL, $option['LOCALE'][0]);
		}

		if(empty($_getVar['recipesearch'])) { // && isset($option['LISTCAT'])

			//$recipe .= listRecipeCategories($option);
			$recipe .= showRecipeSeach();

		} else {

			$recipe .= listRecipes();
		}

		if(isset($_oldLocale)) {
			setlocale(LC_ALL, $_oldLocale);
		}

		return $recipe;

	}

	function showRecipeSeach()
	{

		global $_getVar;

		$search = file_get_contents(PHPWCMS_TEMPLATE . 'inc_cntpart/recipe/search/search.html');

		return ($search ? $search : '');

	}

	function listRecipeCategories($option)
	{
		$cat = _dbQuery('SELECT acontent_text FROM ' . DB_PREPEND . 'phpwcms_articlecontent WHERE acontent_type=26 AND acontent_trash=0');
		$cat_all = '';
		if($cat) {
			foreach($cat as $temp) {
				if($temp['acontent_text']) {
					if($cat_all) {
						$cat_all .= ', ';
					}
					$cat_all .= $temp['acontent_text'];
				}
			}
			$cat_all = convertStringToArray($cat_all);
			sort($cat_all, SORT_LOCALE_STRING);
		} else {
			$cat_all = array();
		}

		$cat = '';

		unset($GLOBALS['_getVar']['recipecat']);

		foreach($cat_all as $temp) {

			$cat .= '	<li><a href="' . rel_url(['recipecat' => $temp]) . '" ';
			$temp = html_specialchars($temp);
			$cat .= 'title="' . $temp . '">' . $temp . '</a></li>' . LF;

		}

		if($cat) {

			$cat = LF . '<ul>' . LF . $cat . '</ul>' . LF;

		}

		if(isset($option['LISTCAT'][0])) {
			$cat = $option['LISTCAT'][0] . $cat;
		}
		if(isset($option['LISTCAT'][1])) {
			$cat .= $option['LISTCAT'][1];
		}

		return $cat;

	}

	function listRecipes($alt = null)
	{

		global $_getVar;
		$order_by = array();

		$sql = "SELECT * FROM " . DB_PREPEND . "phpwcms_articlecontent ";
		$sql .= "INNER JOIN " . DB_PREPEND . "phpwcms_article ON ";
		$sql .= DB_PREPEND . "phpwcms_article.article_id = " . DB_PREPEND . "phpwcms_articlecontent.acontent_aid ";
		$sql .= "WHERE acontent_type=26 AND acontent_visible=1 AND ";
		$sql .= "acontent_livedate < NOW() AND (acontent_killdate='0000-00-00 00:00:00' OR acontent_killdate > NOW()) ";
		$sql .= "AND acontent_trash=0 AND ";

		if(!empty($_getVar['recipecat'])) {
			$sql .= "acontent_text LIKE '%" . aporeplace($_getVar['recipecat']) . "%' AND ";
		}

		if(!empty($_getVar['recipecal']) && intval($_getVar['recipecal'])) {

			$_getVar['recipecal'] = intval($_getVar['recipecal']);

			switch($_getVar['recipecal']) {
				//till 400 kcal
				case 1:
					$sql .= "( SUBSTRING(acontent_alink, 3) / 4.1868) <= 400 AND ";
					$order_by[] = 'acontent_alink';
					break;

				//400 till 600 kcal
				case 2:
					$sql .= "( SUBSTRING(acontent_alink, 3) / 4.1868) > 400 AND ";
					$sql .= "( SUBSTRING(acontent_alink, 3) / 4.1868) <= 600 AND ";
					$order_by[] = 'acontent_alink';
					break;

				//above 600 kcal
				case 3:
					$sql .= "( SUBSTRING(acontent_alink, 3) / 4.1868) > 600 AND ";
					$order_by[] = 'acontent_alink';
					break;

			}

		}

		if(!empty($_getVar['recipetime']) && intval($_getVar['recipetime'])) {

			$_getVar['recipetime'] = intval($_getVar['recipetime']);

			switch($_getVar['recipetime']) {
				//till 20 Min.
				case 1:
					$sql .= "( SUBSTRING(acontent_media, 3) * 1) <= 20 AND ";
					$order_by[] = 'acontent_media';
					break;

				//20 till 40 Min.
				case 2:
					$sql .= "( SUBSTRING(acontent_media, 3) * 1) > 20 AND ";
					$sql .= "( SUBSTRING(acontent_media, 3) * 1) <= 40 AND ";
					$order_by[] = 'acontent_media';
					break;

				//above 40 Min.
				case 3:
					$sql .= "( SUBSTRING(acontent_media, 3) * 1) > 40 AND ";
					$order_by[] = 'acontent_media';
					break;

			}

		}

		if(!empty($_getVar['recipetext'])) {

			$text = optimizeForSearch(rawurldecode($_getVar['recipetext']));
			$text = str_replace(['UPDATE', 'INSERT', 'SELECT', 'FROM', 'DROP', 'CREATE', "'"], '', $text);
			$text = convertStringToArray($text, ' ');
			$t = array();
			$sql .= '( ';
			foreach($text as $value) {

				$t[] = '( CONCAT(acontent_newsletter, ' . DB_PREPEND . "phpwcms_article.article_title, acontent_title) LIKE '%" . aporeplace($value) . "%' )";

			}
			$sql .= implode(' AND ', $t) . ' ) AND ';

		}

		$order_by[] = 'article_title';

		$sql .= DB_PREPEND . "phpwcms_article.article_deleted=0 ";
		if(!PREVIEW_MODE) {
			$sql .= 'AND ';
			$sql .= DB_PREPEND . "phpwcms_article.article_begin < NOW() AND ";
			$sql .= '(' . DB_PREPEND . "phpwcms_article.article_end > NOW() OR " .DB_PREPEND . "phpwcms_article.article_end = '0000-00-00 00:00:00')";
		}
		$sql .= 'ORDER BY ' . implode(', ', $order_by);

		$result = _dbQuery($sql);
		$result_listing = '';

		if(is_array($result) && count($result)) {

			$articles = array();

			foreach($result as $value) {

				$value['article_image'] = unserialize($value['article_image']);
				$articles[ $value['article_id'] ] = $value;

			}
			$result_listing = list_articles_summary($articles);

		}

		return $result_listing;

	}

	$content["all"] = preg_replace_callback('/\{RECIPES:(.*?)\}/s', 'doRecipes', $content["all"]);
}
