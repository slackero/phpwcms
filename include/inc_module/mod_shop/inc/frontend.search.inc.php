<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// Module/Plug-in Shop & Products search class
class ModuleShopSearch {

	var $search_words			= array();
	var $search_word_count		= 0;
	var $search_result_entry	= 0;
	var $search_results			= array();
	var $search_highlight		= false;
	var $search_highlight_words	= false;
	var $search_wordlimit		= 0;
	var $ellipse_sign			= '&#8230;';

	function search() {
	
		if(!$this->search_word_count) {
			return NULL;
		}
		
		$shop_url			= _getConfig( 'shop_pref_id_shop', '_shopPref' );
		$shop_lang_support	= _getConfig( 'shop_pref_felang' ) ? true : false;
		
		if(!is_numeric($shop_url) && is_string($shop_url)) {
			$shop_url	= trim($shop_url);
		} elseif(is_numeric($shop_url) && intval($shop_url)) {
			$shop_url	= 'aid='.intval($shop_url);
		} else {
			$shop_url	= $GLOBALS['aktion'][1] ? 'aid='.$GLOBALS['aktion'][1] : 'id='.$GLOBALS['aktion'][0];
		}
	
		$sql  = 'SELECT shopprod_id, shopprod_category, shopprod_ordernumber, ';
		$sql .= 'shopprod_name1, ';
		$sql .= 'UNIX_TIMESTAMP(shopprod_changedate) AS shopprod_date, ';
		$sql .= 'CONCAT(';
		$sql .= "	shopprod_description0,' ',";
		$sql .= "	shopprod_description1,' ',";
		$sql .= "	shopprod_description2,' ',";
		$sql .= "	shopprod_description3,' ',";
		$sql .= "	shopprod_color,' ',";
		$sql .= "	shopprod_size,' ',";
		$sql .= "	shopprod_ordernumber,' ',";
		$sql .= "	shopprod_model,' ',";
		$sql .= "	shopprod_name1,' ',";
		$sql .= "	shopprod_name2,' '";
		$sql .= ') AS shopprod_search ';
		$sql .= 'FROM '.DB_PREPEND.'phpwcms_shop_products WHERE shopprod_status=1';
		if($shop_lang_support && !empty($GLOBALS['phpwcms']['default_lang'])) {
			$sql .= " AND (shopprod_lang='' OR shopprod_lang="._dbEscape($GLOBALS['phpwcms']['default_lang']).')';
		}
		$data = _dbQuery($sql);
		
		foreach($data as $value) {
		
			$s_result	= array();
		
			$s_text		= $value['shopprod_search'];
			$s_text		= str_replace( array('~', '|', ':', 'http', '//', '_blank', '&nbsp;') , ' ', $s_text );
			$s_text		= clean_replacement_tags($s_text, '');
			$s_text		= remove_unsecure_rptags($s_text);
			$s_text		= cleanUpSpecialHtmlEntities($s_text);
			
			preg_match_all('/'.$this->search_words.'/is', $s_text, $s_result );

			$s_count	= count($s_result[0]);
			
			if($s_count && SEARCH_TYPE_AND) {
				$s_and_or = array();
				foreach($s_result[0] as $svalue) {
					$s_and_or[strtolower($svalue)] = 1;
				}
				$s_and_or = count($s_and_or);
				
				if($s_and_or != $this->search_word_count) {
					$s_count = 0;
				}
			}
			
			if($s_count) {

				$id = $this->search_result_entry;
				
				$s_title  = $value['shopprod_ordernumber'] ? trim($value['shopprod_ordernumber']).': ' : '';
				$s_title .= $value['shopprod_name1'];
				$s_title  = html_specialchars($s_title);

				$s_text   = trim($s_text);
				$s_text   = getCleanSubString($s_text, $this->search_wordlimit, $this->ellipse_sign, 'word');
				$s_text   = html_specialchars($s_text);
				
				$this->search_results[$id]["id"]	= $value['shopprod_id'];
				$this->search_results[$id]["cid"]	= 0;
				$this->search_results[$id]["rank"]	= $s_count;
				$this->search_results[$id]["title"]	= $this->search_highlight ? highlightSearchResult($s_title, $this->search_highlight_words) : $s_title;
				$this->search_results[$id]["date"]	= $value['shopprod_date'];
				$this->search_results[$id]["user"]	= '';
				$this->search_results[$id]['query']	= $shop_url.'&amp;shop_cat='.$value['shopprod_category'].'&amp;shop_detail='.$value['shopprod_id'];
				
				if($this->search_highlight) {
					$s_text = highlightSearchResult($s_text, $this->search_highlight_words);
				}
				$this->search_results[$id]["text"]	= $s_text;
				
				$this->search_result_entry++;
			}
		}
	}

}


?>