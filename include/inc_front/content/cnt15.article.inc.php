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



//article menu

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

$alinkmenu 					= unserialize($crow["acontent_form"]);
$alinkmenu["catid"] 		= ($alinkmenu["cat"]) ? $alinkmenu["catid"] : $content["cat_id"];
$alinkmenu['headertext']	= empty($alinkmenu["headertext"]) ? 0 : 1;
$alinkmenu['ul']			= empty($alinkmenu["ul"]) ? 0 : $alinkmenu["ul"];
$alinkmenu['titlewrap'] 	= empty($alinkmenu["titlewrap"]) ? array('', '') : array('<'.$alinkmenu["titlewrap"].'>', '</'.$alinkmenu["titlewrap"].'>');
$alinkmenu['link'] 			= '';

$ao 						= get_order_sort($content['struct'][ $alinkmenu["catid"] ]['acat_order']);

$alink_sql  = "SELECT article_id, article_title, article_cid, article_summary FROM ";
$alink_sql .= DB_PREPEND."phpwcms_article WHERE article_public=1 AND ";
$alink_sql .= "article_aktiv=1 AND article_deleted=0 AND article_cid=";
$alink_sql .= intval($alinkmenu["catid"])." AND article_begin<NOW() ";
$alink_sql .= 'AND article_end>NOW() ';
if(!empty($alinkmenu['hideactive'])) {
	$alink_sql .= 'AND article_id != '. $aktion[1] . ' ';
}
$alink_sql .= 'ORDER BY ' . $ao[2] ;
			 
if($result = mysql_query($alink_sql, $db) or die("error while getting link article list: ".$alink_sql)) {

	while($row = mysql_fetch_row($result)) {
	
		$tempRowSpan 			= '';
		$row[3]					= preg_replace('/<br[^>]*?>$/i', '', $row[3]);
		
		if($alinkmenu['headertext'] && !empty($row[3])) {
		
			$alinkmenu['sum']	= $row[3];
			
			if(!empty($alinkmenu['maxchar'])) {
			
				$alinkmenu['sum'] 		= clean_replacement_tags($alinkmenu['sum']);
				$alinkmenu['sum'] 		= remove_unsecure_rptags($alinkmenu['sum']);
				$alinkmenu['sum'] 		= preg_replace('/\s/i', ' ', $alinkmenu['sum']);
				$alinkmenu['sum'] 		= preg_replace('/\s{2,}/i', ' ', $alinkmenu['sum']);
				$alinkmenu['sum'] 		= trim(decode_entities($alinkmenu['sum']));
				$alinkmenu['sum']		= wordwrap($alinkmenu['sum'], $alinkmenu['maxchar'], "\n");
				list($alinkmenu['sum'])	= explode("\n", $alinkmenu['sum']);
				$alinkmenu['sum']		= trim($alinkmenu['sum']);
				$alinkmenu['sum']		= html_specialchars($alinkmenu['sum']);
				
				if(!empty($alinkmenu['morelink'])) {
					
					$alinkmenu['sum']  .= '<a href="index.php?aid='.$row[0].'">';
					$alinkmenu['sum']  .= $alinkmenu['morelink'];
					$alinkmenu['sum']  .= '</a>';
					
				}
			
			}
				
		} else {
		
			$alinkmenu['sum']	= false;
		
		}
		
		switch($alinkmenu['ul']) {
		
			case 1:		// render as unordered list
						$alinkmenu['link'] .= '<li>'.$alinkmenu['titlewrap'][0];
						$alinkmenu['link'] .= '<a href="index.php?aid='.$row[0].'">';
						$alinkmenu['link'] .= html_specialchars($row[1]);
						$alinkmenu['link'] .= '</a>'.$alinkmenu['titlewrap'][1];
						
						if($alinkmenu['sum'] !== false) {
							$alinkmenu['link'] .= "\n".$alinkmenu['sum'];
						}
						
						$alinkmenu['link'] .= "</li>\n";
						break;
						
			case 2:		// render as div
						$alinkmenu['link'] .= '<div>'.$alinkmenu['titlewrap'][0];
						$alinkmenu['link'] .= '<a href="index.php?aid='.$row[0].'">';
						$alinkmenu['link'] .= html_specialchars($row[1]);
						$alinkmenu['link'] .= '</a>'.$alinkmenu['titlewrap'][1];
						
						if($alinkmenu['sum'] !== false) {
							$alinkmenu['link'] .= "\n".$alinkmenu['sum'];
						}
						
						$alinkmenu['link'] .= "</div>\n";
						break;
		
			default:	// render as table
		
						if($alinkmenu['sum'] !== false) {
							$tempRowSpan		= ' rowspan="2"';
							$alinkmenu['sum']	= "<tr>\n\t<td>" . $alinkmenu['sum'] . "</td>\n</tr>\n";
						}
					
						$alinkmenu['link'] .= "<tr>\n\t<td valign=\"top\"".$tempRowSpan." nowrap=\"nowrap\">".$template_default["article"]["link_article_sign"]."</td>\n\t";
						$alinkmenu['link'] .= '<td>'.$alinkmenu['titlewrap'][0].'<a href="index.php?aid='.$row[0].'" ';
						$alinkmenu['link'] .= get_class_attrib($template_default["article"]["link_article_class"]).">";
						$alinkmenu['link'] .= html_specialchars($row[1]).'</a>'.$alinkmenu['titlewrap'][1]."</td>\n</tr>\n";
						$alinkmenu['link'] .= $alinkmenu['sum'];
			
		}	
		
		
	}
	mysql_free_result($result);

}

if($alinkmenu['link']) {

	switch($alinkmenu['ul']) {
	
			case 1:		// render as unordered list
						$alinkmenu['link'] = "<ul>\n" . $alinkmenu['link'] . "</ul>\n";						
						break;
						
			case 2:		// render as div			
						break;
		
			default:	// render as table
						$alinkmenu['link'] = '<table border="0" cellspacing="0" cellpadding="0">'."\n" . $alinkmenu['link'] . "</table>\n";
			
	}

	// now check if class name is given
	// if so wrap article menu in div
	if(!empty($alinkmenu['class'])) {
	
		$alinkmenu['link'] = '<div class="' . html_specialchars($alinkmenu['class']) . "\">\n" . $alinkmenu['link'] . "</div>\n";
	
	}	
	$CNT_TMP .= $alinkmenu['link'];
	
}

unset($alinkmenu);
									
?>