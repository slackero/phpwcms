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
if (!defined('PHPWCMS_INCLUDE_CHECK')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

/**
 * Render @@Text@@ based on browser language and store in related language file
 * which allows easy translation at later time and when needed. The Text between
 * @@Default@@ will be taken as default text if no translation exists.
 *
 * Based on work and ideas of
 *   Dr.-Ing. Tobias Schittkowski (http://www.schittkowski.de/index.php?q=node/20)
 *   André Rabold (http://smarty.incutio.com/?page=SmartyMultilanguageSupport)
 **/

// language which is selected by user (defined in the browser settings)
function i18n_get_language($complex=false) {
	global $phpwcms;
	if(!empty($phpwcms['i18_lang'])) {
		return $phpwcms['i18_lang'];
	} elseif(empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$phpwcms['i18_lang'] = $phpwcms['default_lang'];
		return $phpwcms['i18_lang'];
	}
	$complex = isset($phpwcms['i18n_complex']) ? $phpwcms['i18n_complex'] : $complex;
	if($complex) {
		$lang = explode(';', trim($_SERVER['HTTP_ACCEPT_LANGUAGE']), 2);
		$lang = explode(',', $lang[0], 2);
		$phpwcms['i18_lang'] = preg_replace('/[^a-z0-9\-_\.]/', '', strtolower(trim($lang[0])));
	} else {
		$phpwcms['i18_lang'] = strtolower(substr( trim($_SERVER['HTTP_ACCEPT_LANGUAGE']) , 0, 2));
	}
	if(empty($phpwcms['i18_lang'])) {
		$phpwcms['i18_lang'] = $phpwcms['default_lang'];
	}
	return $phpwcms['i18_lang'];
}
// get the template file name
function i18n_get_filename() {
	return PHPWCMS_TEMPLATE . 'template_lang/' . i18n_get_language(true) . '.php';
}
function i18n_get_file_open_text() {
	$text  = '<?php ' . LF;
	$text .= '// phpwcms template language file "' . i18n_get_language(true) . '" (' . now('Y-m-d H:i:s') . ')' . LF;
	$text .= '// ATTENTION! Never add the closing PHP tag "? >" at the end of this file!' . LF . LF;
	return $text;
}
// substitutes a single token
function i18n_substitute_text_token($token) {
	global $i18n_tokens;	
	$a = trim($token[1]);
	if(isset($i18n_tokens[$a])) {
		return $i18n_tokens[$a];
	} else {		
		$f = i18n_get_filename();
		if(is_readable($f)) {
			include($f);
		} elseif($handle = fopen($f, 'ab')) {
			fwrite($handle, i18n_get_file_open_text() );
			fclose($handle);
		} else {
			return $a;
		}
		if(isset($i18n_tokens[$a])) {
			return $i18n_tokens[$a];
		}
		$i18n_tokens[$a] = $a;
		$as = str_replace("'", "\\'", $a);
		$s = '$i18n_tokens' . "['" . $as . "']" . " = '" . $as . "'; // NEW " . now('Y-m-d H:i:s') . LF;
		if($handle = fopen($f, 'ab')) {
			fwrite($handle, $s);
			fclose($handle);
		}
	}
	return $a;
}
// all contents starting and ending with @@ are replaced
function i18n_substitute_text($tpl_output) {
	global $i18n_tokens;	    
	$f = i18n_get_filename();
	if(!isset($i18n_tokens)) {
		if(is_readable($f)) {
			include($f);
		} elseif($handle = fopen($f, 'ab')) {
			fwrite($handle, i18n_get_file_open_text() );
			fclose($handle);
		}
	}
	return preg_replace_callback('/@@(.+?)@@/', 'i18n_substitute_text_token', $tpl_output);    
}
///////////////////////////////////////////////////////////////////////////////////////////////


?>