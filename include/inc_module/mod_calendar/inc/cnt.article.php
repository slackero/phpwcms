<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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
	$content['glossary']['detail_head']		= get_tmpl_section('GLOSSARY_DETAIL_HEAD',		$content['glossary']['glossary_template']);
	$content['glossary']['detail_footer']	= get_tmpl_section('GLOSSARY_DETAIL_FOOTER',	$content['glossary']['glossary_template']);
	$content['glossary']['detail_entry']	= get_tmpl_section('GLOSSARY_DETAIL_ENTRY',		$content['glossary']['glossary_template']);
	
	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_glossary WHERE glossary_status=1 ';
	$sql .= 'AND glossary_id='.$GLOBALS['_getVar']['glossaryid'];
	$sql .= $content['glossary']['where'];
	$content['glossary']['entry'] = _dbQuery($sql);
	if(empty($content['glossary']['entry'][0])) {
	
		$content['glossary']['entry']['glossary_title']	= '';
		$content['glossary']['entry']['glossary_text']	= $content['glossary']['glossary_noentry'];
	
	} else {
	
		$content['glossary']['entry'] = $content['glossary']['entry'][0];
	
	}
		
	unset($GLOBALS['_getVar']['glossaryid']);
	$content['glossary']['base_link'] = 'index.php'.returnGlobalGET_QueryString('htmlentities');

	$content['glossary']['detail_head']		= str_replace('{BACKLINK}', $content['glossary']['base_link'], $content['glossary']['detail_head']);
	$content['glossary']['detail_footer']	= str_replace('{BACKLINK}', $content['glossary']['base_link'], $content['glossary']['detail_footer']);
	$content['glossary']['detail_entry']	= get_tmpl_section('GLOSSARY_DETAIL_ENTRY',		$content['glossary']['glossary_template']);
	$content['glossary']['detail_entry']	= render_cnt_template($content['glossary']['detail_entry'], 'TEXT', $content['glossary']['entry']['glossary_text']);
	$content['glossary']['detail_entry']	= render_cnt_template($content['glossary']['detail_entry'], 'TITLE', html_specialchars($content['glossary']['entry']['glossary_title']));

	// fine we will display given glossary ID
	$CNT_TMP .= $content['glossary']['detail_head'];
	$CNT_TMP .= $content['glossary']['detail_entry'];
	$CNT_TMP .= $content['glossary']['detail_footer'];

} else {

	// get list entries template sections
	$content['glossary']['list_head']		= get_tmpl_section('GLOSSARY_LIST_HEAD',		$content['glossary']['glossary_template']);
	$content['glossary']['list_footer']		= get_tmpl_section('GLOSSARY_LIST_FOOTER',		$content['glossary']['glossary_template']);
	$content['glossary']['list_entry']		= get_tmpl_section('GLOSSARY_LIST_ENTRY',		$content['glossary']['glossary_template']);
	$content['glossary']['list_spacer']		= get_tmpl_section('GLOSSARY_LIST_SPACER',		$content['glossary']['glossary_template']);

	// OK we build filter
	$content['glossary']['glossary_alphabet']		= '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$content['glossary']['glossary_filter']			= convertStringToArray(strtoupper($content['glossary']['glossary_filter']), ' ');
	$content['glossary']['glossary_filter_active']	= empty($GLOBALS['_getVar']['glossary']) ? '' : strtoupper(clean_slweg($GLOBALS['_getVar']['glossary']));
	
	if(in_array($content['glossary']['glossary_filter_active'], $content['glossary']['glossary_filter'])) {
			
			// build SQL query
			if(strpos($content['glossary']['glossary_filter_active'], '-')) {
				$content['glossary']['filter']		= explode('-', $content['glossary']['glossary_filter_active']);
				$content['glossary']['filter'][0]	= substr($content['glossary']['filter'][0], 0, 1);
				$content['glossary']['filter'][1]	= empty($content['glossary']['filter'][1]) ? '?' : substr($content['glossary']['filter'][1], 0, 1);
				// is there start and end
				if(strpos($content['glossary']['glossary_alphabet'], $content['glossary']['filter'][0]) !== false && strpos($content['glossary']['glossary_alphabet'], $content['glossary']['filter'][1]) !== false) {
					
					$content['glossary']['glossary_alphabet']	= preg_split('//', $content['glossary']['glossary_alphabet'], -1, PREG_SPLIT_NO_EMPTY);
					$content['glossary']['filters']				= array();
					$content['glossary']['filter_run']			= false;
					foreach($content['glossary']['glossary_alphabet'] as $content['glossary']['char']) {
						
						// OK start here
						if($content['glossary']['char'] == $content['glossary']['filter'][0]) {
							$content['glossary']['filter_run']	= true;
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
				
			} else {
			
				//$content['glossary']['where'] = " AND TRIM(CONCAT(glossary_tag, glossary_title)) LIKE '".aporeplace($content['glossary']['glossary_filter_active'])."%'";
				if($content['glossary']['glossary_filter_active'] != '*' && strlen($content['glossary']['glossary_filter_active']) == 1) {
					$content['glossary']['where'] = " AND glossary_title LIKE '".aporeplace($content['glossary']['glossary_filter_active'])."%'";
				}
			}
	}

	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_glossary WHERE glossary_status=1'.$content['glossary']['where'].' ORDER BY glossary_title';
	
	$content['glossary']['entries'] = _dbQuery($sql);
	
	unset($GLOBALS['_getVar']['glossary']);
	$content['glossary']['base_link'] = 'index.php'.returnGlobalGET_QueryString('htmlentities');
	if(strpos($content['glossary']['base_link'], '?') === false) {
		$content['glossary']['base_link'] .= '?';
	} else {
		$content['glossary']['base_link'] .= '&amp;';
	}

	$_filter_link	= array();
	$_filter_c		= 0;
	foreach($content['glossary']['glossary_filter'] as $content['glossary']['filter_value']) {
		$_filter_entities = html_specialchars($content['glossary']['filter_value']);
		$_filter_link[$_filter_c] = '<a href="'.$content['glossary']['base_link'].'glossary='.$_filter_entities.'"';
		// yes - this is the active part
		if($content['glossary']['filter_value'] == $content['glossary']['glossary_filter_active']) {
			$_filter_link[$_filter_c] .= ' class="active"';
		}
		$_filter_link[$_filter_c] .= ' title="'.$_filter_entities.'">';
		$_filter_link[$_filter_c] .= $_filter_entities.'</a>';
		$_filter_c++;
	}

	$_filter_link = implode(' ', $_filter_link);
	$content['glossary']['list_head']	= render_cnt_template($content['glossary']['list_head'], 'FILTER', $_filter_link);
	$content['glossary']['list_footer']	= render_cnt_template($content['glossary']['list_head'], 'FILTER', $_filter_link);
	
	$CNT_TMP .= $content['glossary']['list_head'];
	
	if(!count($content['glossary']['entries'])) {
	
		$content['glossary']['entries'][0]['glossary_title']	= '';
		$content['glossary']['entries'][0]['glossary_text']		= $content['glossary']['glossary_noentry'];
	
		$_no_entry = true;
	
	} else {
	
		$_no_entry = false;
	
	}

	foreach($content['glossary']['entries'] as $_entry_key => $_entry_value) {

		$content['glossary']['entries'][$_entry_key] = str_replace('{LINK}', $_no_entry ? '#' : $content['glossary']['base_link'].'glossaryid='.$_entry_value['glossary_id'], $content['glossary']['list_entry']);
		$content['glossary']['entries'][$_entry_key] = render_cnt_template($content['glossary']['entries'][$_entry_key], 'TITLE', html_specialchars($_entry_value['glossary_title']));
		
		if(!empty($content['glossary']['glossary_maxwords']) && !$_no_entry) {
			$_entry_value['glossary_text'] = getCleanSubString(strip_tags($_entry_value['glossary_text']), $content['glossary']['glossary_maxwords'], $template_default['ellipse_sign'], 'word');
		}
		$content['glossary']['entries'][$_entry_key] = render_cnt_template($content['glossary']['entries'][$_entry_key], 'TEXT', $_entry_value['glossary_text']);
		
	}
	
	$CNT_TMP .= implode($content['glossary']['list_spacer'] ,$content['glossary']['entries']);
	$CNT_TMP .= $content['glossary']['list_footer'];

}


?>