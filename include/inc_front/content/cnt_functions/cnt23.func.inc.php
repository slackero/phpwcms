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

// necessary form functions

// Get Field specific class enhanced by error class
function getFieldErrorClass($field_class, $error_class) {
	return trim($field_class.' '.$error_class);
}


function block_comment_spam(&$POST_val, $field_names=NULL) {
	
	global $cnt_form;

	if(is_array($field_names)) {
		$spam_identified = false;
		foreach($field_names as $field_name) {
			if(!$spam_identified && !empty($POST_val[$field_name]) && empty($GLOBALS['POST_ERR'][$field_name])) {	
				// check against Spam BB Code
				if(preg_match('/\[link=|\[url=|SEO /i', $POST_val[$field_name]) || count(explode('http:/', strtolower($POST_val[$field_name]))) > 2 || linksleeve_comment_check($POST_val[$field_name]) == 'SPAM') {			
					// spam trying
					$GLOBALS['POST_ERR'][$field_name] = empty($cnt_form["fields"][$field_name]['error']) ? '@@Content looks like Spam.@@' : $cnt_form["fields"][$field_name]['error'];
					// we need to block only once
					$spam_identified = true;
				}
			}
		}		
	}
}

function linksleeve_comment_check($content) {

	$data	= 'content='.$content;
	$buf	= ''; 
	
	$fp		 = fsockopen("www.linksleeve.org", 80, $errno, $errstr, 30);
	
	if(!$fp) {
		return NULL;
	}
	
	$header  = "POST /pslv.php HTTP/1.0\r\n";
	$header .= "Host: www.linksleeve.org\r\n";
	$header .= "Content-type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-length: " . strlen($data) . "\r\n";
	$header .= "User-agent: Mozilla/4.0 (compatible: MSIE 7.0; Windows NT 6.0)\r\n";
	$header .= "Connection: close\r\n\r\n";
	$header .= $data;
	
	fputs($fp, $header, strlen($header));
	
	while (!feof($fp)) {
		$buf .= fgets($fp, 128);
	}
	
	fclose($fp);
	
	return !stristr($buf,"-slv-1-/slv-") ? 'SPAM' : 'HAM';
}


?>