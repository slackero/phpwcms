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

// neccessary frontend functions for search

function get_SearchForStructureID($search_at) {

	$k = array();

	if(is_array($search_at) && count($search_at)) {
	
		if(in_array(0, $search_at)) {
			return $k;
		}

		$m = search_buildMenuPathStructure();
		$m = explode(LF, $m);
		$m = array_unique($m);
		$m = array_diff($m, array(''));
		$k = $m;
		$d = implode(LF, $m);
		$c = 0;
		foreach($m as $key => $value) {
			preg_match_all('/'.trim($value).'/', $d, $match);
			if(count($match[0]) > 1) {
				unset($k[$key]);
			}
			$c++;
		}
		
		$_search_at_this = '';

		foreach($search_at as $_search_start_ID) {
	
			foreach($k as $_search_path) {
		
				$_search_path = '  '.$_search_path.'  ';
				$_this_pos = strpos($_search_path, ' '.$_search_start_ID.' ');
				
				if($_this_pos) {
					$_search_at_this .= ' '.trim(substr($_search_path, $_this_pos));
				}
				
			}
		}
	
		if($_search_at_this) {
	
			$k = explode(' ', trim($_search_at_this));
			$k = array_unique($k);
		
		} else {
			$k = array();
		}
		

	}
	
	return $k;
}



function search_buildMenuPathStructure($start_id=0, $counter=0, $pre = '') {

	global $content;

	$li	= '';
		
	foreach($content['struct'] as $key => $value) {
	
		if($key && $content['struct'][$key]['acat_struct'] == $start_id && empty($content['struct'][$key]['acat_nosearch'])) {

			$g   = $pre.$key.' ' ;
			$li .= $g;
			$li .= search_buildMenuPathStructure($key, $counter+1, LF.$g);
			
		}
	}

	return $li . LF;
}

?>