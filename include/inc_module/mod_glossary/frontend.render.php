<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

// Module/Plug-in Glossary frontend_render script
// use it as when it is located under "template/inc_script/frontend_render"
// most times it is used to make global replacements

// $content['all'] = str_replace('{MY_TAG}', 'My Replacement', $content['all'];

if(false && !isset($content['glossary'])) {


	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_glossary WHERE ';
	$sql .= 'glossary_status=1 AND glossary_highlight=1 AND glossary_keyword != ""';
	
	$glossary['keywords'] = @_dbQuery($sql);
	
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

}

?>