<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// try

if(isset($_GET['edit'])) {
	$glossary['id']		= intval($_GET['edit']);
} else {
	$glossary['id']		= 0;
}


// process post form
if(isset($_POST['glossary_title'])) {

	$glossary['data'] = array(
	
				'glossary_id'			=> intval($_POST['glossary_id']),
				'glossary_title'		=> clean_slweg($_POST['glossary_title']),
				'glossary_created'		=> date('Y-m-d H:i:s'),
				'glossary_changed'		=> date('Y-m-d H:i:s'),
				'glossary_tag'			=> clean_slweg($_POST['glossary_tag']),
				'glossary_keyword'		=> clean_slweg($_POST['glossary_keyword']),
				'glossary_text'			=> slweg($_POST['glossary_text']),
				'glossary_object'		=> array(),
				'glossary_status'		=> empty($_POST['glossary_status']) ? 0 : 1,
				'glossary_highlight'	=> empty($_POST['glossary_highlight']) ? 0 : 1
	
								);

	if(empty($glossary['data']['glossary_title'])) {
	
		$glossary['error']['glossary_title'] = 1;
	
	}
	
	if(empty($glossary['data']['glossary_keyword'])) {
	
		$glossary['error']['glossary_keyword'] = 1;
		
	} else {
	
		$sql  = 'SELECT COUNT(*) FROM '.DB_PREPEND."phpwcms_glossary ";
		$sql .= "WHERE glossary_keyword LIKE '".aporeplace($glossary['data']['glossary_keyword']);
		$sql .= "' AND glossary_id <> ".$glossary['data']['glossary_id'];
	
		if(_dbQuery($sql, 'COUNT')) {
		
			$glossary['error']['glossary_keyword'] = 1;
		
		}
	
	}
	
	
	if(!isset($glossary['error'])) {
	
		if($glossary['data']['glossary_id']) {
		
			// UPDATE
			$sql  = 'UPDATE '.DB_PREPEND.'phpwcms_glossary SET ';
			
			$sql .= "glossary_title='".aporeplace($glossary['data']['glossary_title'])."', ";
			$sql .= "glossary_tag='".aporeplace($glossary['data']['glossary_tag'])."', ";
			$sql .= "glossary_keyword='".aporeplace($glossary['data']['glossary_keyword'])."', ";
			$sql .= "glossary_text='".aporeplace($glossary['data']['glossary_text'])."', ";
			$sql .= "glossary_object='".aporeplace(serialize($glossary['data']['glossary_object']))."', ";
			$sql .= "glossary_changed='".aporeplace($glossary['data']['glossary_changed'])."', ";
			$sql .= "glossary_status=".$glossary['data']['glossary_status'].", ";
			$sql .= "glossary_highlight=".$glossary['data']['glossary_highlight']." ";
			
			$sql .= "WHERE glossary_id=".$glossary['data']['glossary_id'];
			
			if(@_dbQuery($sql, 'UPDATE')) {
			
				if(isset($_POST['save'])) {
					
					headerRedirect(decode_entities(GLOSSARY_HREF));
					
				}
			
			} else {
			
				$glossary['error']['update'] = mysql_error();
			
			}
			
		
		} else {
		
			// INSERT
			$sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_glossary (';
			$sql .= 'glossary_created, glossary_changed, glossary_title, glossary_tag, ';
			$sql .= 'glossary_keyword, glossary_text, glossary_highlight, glossary_object, glossary_status';		
			$sql .= ') VALUES (';
			$sql .= "'".aporeplace($glossary['data']['glossary_created'])."', ";
			$sql .= "'".aporeplace($glossary['data']['glossary_changed'])."', ";
			$sql .= "'".aporeplace($glossary['data']['glossary_title'])."', ";
			$sql .= "'".aporeplace($glossary['data']['glossary_tag'])."', ";
			$sql .= "'".aporeplace($glossary['data']['glossary_keyword'])."', ";
			$sql .= "'".aporeplace($glossary['data']['glossary_text'])."', ";
			$sql .= aporeplace($glossary['data']['glossary_highlight']).', ';
			$sql .= "'".aporeplace(serialize($glossary['data']['glossary_object']))."', ";
			$sql .= aporeplace($glossary['data']['glossary_status']);
			$sql .= ')';
			
			if($result = @_dbQuery($sql, 'INSERT')) {
			
				if(isset($_POST['save'])) {
					
					headerRedirect(decode_entities(GLOSSARY_HREF));
					
				}
				
				if(!empty($result['INSERT_ID'])) {
					$glossary['id'] = $result['INSERT_ID'];
				}
			
			} else {
			
				$glossary['error']['update'] = mysql_error();
			
			}
		
		
		}
	}

}

// try to read entry from database
if($glossary['id'] && !isset($glossary['error'])) {

	$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_glossary WHERE glossary_id='.$glossary['id'];
	$glossary['data'] = _dbQuery($sql);
	$glossary['data'] = $glossary['data'][0];
}

// default values
if(empty($glossary['data'])) {

	$glossary['data'] = array(
	
				'glossary_id'			=> 0,
				'glossary_title'		=> '',
				'glossary_created'		=> '',
				'glossary_changed'		=> date('Y-m-d H:i:s'),
				'glossary_tag'			=> '',
				'glossary_keyword'		=> '',
				'glossary_text'			=> '',
				'glossary_object'		=> array(),
				'glossary_status'		=> 0,
				'glossary_highlight'	=> 0
	
								);

}



?>