<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

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
	global $cmsgo;
	if(!empty($cmsgo['i18_lang'])) {
		return $cmsgo['i18_lang'];
	} elseif(empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$cmsgo['i18_lang'] = $cmsgo['default_lang'];
		return $cmsgo['i18_lang'];
	}
	$complex = isset($cmsgo['i18n_complex']) ? $cmsgo['i18n_complex'] : $complex;
	if($complex) {
		$lang = explode(';', trim($_SERVER['HTTP_ACCEPT_LANGUAGE']), 2);
		$lang = explode(',', $lang[0], 2);
		$cmsgo['i18_lang'] = trim($lang[0]);
	} else {
		$cmsgo['i18_lang'] = substr(trim($_SERVER['HTTP_ACCEPT_LANGUAGE']), 0, 2);
	}
	if($cmsgo['i18_lang'] === '*') { // Any language
    	$cmsgo['i18_lang'] = $cmsgo['default_lang'];
    	return $cmsgo['i18_lang'];
	}
	$cmsgo['i18_lang'] = preg_replace('/[^a-z\-_]/', '', strtolower($cmsgo['i18_lang']));
	if(empty($cmsgo['i18_lang'])) {
		$cmsgo['i18_lang'] = $cmsgo['default_lang'];
	}
	return $cmsgo['i18_lang'];
}
// get the template file name
function i18n_get_filename() {
	return CMSGO_TEMPLATE . 'template_lang/' . i18n_get_language(true) . '.php';
}
function i18n_get_file_open_text() {
	$text  = '<?php' . LF;
	$text .= '// cmsgo template language file "' . i18n_get_language(true) . '" (' . now('Y-m-d H:i:s') . ')' . LF;
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
			include $f;
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
			include $f;
		} elseif($handle = fopen($f, 'ab')) {
			fwrite($handle, i18n_get_file_open_text() );
			fclose($handle);
		}
	}
	return preg_replace_callback('/@@(.+?)@@/', 'i18n_substitute_text_token', $tpl_output);
}
