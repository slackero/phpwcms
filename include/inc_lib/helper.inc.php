<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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

// language which is selected by user (defined in URL, session, cookie, or browser settings)
function i18n_get_language($complex=false) {
	global $phpwcms;

	if(!empty($phpwcms['i18_lang'])) {
		return $phpwcms['i18_lang'];
	}

	$allowed_languages = !empty($phpwcms['allowed_lang']) && is_array($phpwcms['allowed_lang']) ? $phpwcms['allowed_lang'] : [];
	$default_language   = !empty($phpwcms['default_lang']) ? $phpwcms['default_lang'] : 'en';
	$lang_key           = !empty($phpwcms['frontend_lang_key']) ? $phpwcms['frontend_lang_key'] : 'phpwcms_frontend_lang';

	$detected_lang = '';

	// 1. Check URL query parameter ?lang=xx
	if(!empty($_GET['lang'])) {
		$detected_lang = strtolower(substr(trim($_GET['lang']), 0, 5));
	}
	// 2. Check Route / Structure category language setting if enabled or defined
	elseif(!empty($GLOBALS['content']['cat_id']) && !empty($GLOBALS['content']['struct'][$GLOBALS['content']['cat_id']])) {
		$cat_id = $GLOBALS['content']['cat_id'];
		$struct = $GLOBALS['content']['struct'];
		// Check direct structure level language property if available
		if(!empty($phpwcms['use_content_lang']) && !empty($struct[$cat_id]['acat_lang'])) {
			$detected_lang = $struct[$cat_id]['acat_lang'];
		}
		// Check 1st-level structure category mapping array $phpwcms['id_lang']
		elseif(!empty($GLOBALS['LEVEL_ID'][1]) && !empty($phpwcms['id_lang'][$GLOBALS['LEVEL_ID'][1]])) {
			$detected_lang = $phpwcms['id_lang'][$GLOBALS['LEVEL_ID'][1]];
		}
	}

	// 3. Check Session if no route language detected
	if(empty($detected_lang) && !empty($_SESSION[$lang_key])) {
		$detected_lang = $_SESSION[$lang_key];
	}
	// 4. Check Cookie
	elseif(empty($detected_lang) && !empty($_COOKIE[$lang_key])) {
		$detected_lang = $_COOKIE[$lang_key];
	}
	// 5. Check HTTP ACCEPT_LANGUAGE header
	elseif(empty($detected_lang) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
		$complex = isset($phpwcms['i18n_complex']) ? $phpwcms['i18n_complex'] : $complex;
		if($complex) {
			$lang = explode(';', trim($_SERVER['HTTP_ACCEPT_LANGUAGE']), 2);
			$lang = explode(',', $lang[0], 2);
			$detected_lang = trim($lang[0]);
		} else {
			$detected_lang = substr(trim($_SERVER['HTTP_ACCEPT_LANGUAGE']), 0, 2);
		}
	}

	$detected_lang = preg_replace('/[^a-z\-_]/', '', strtolower($detected_lang));

	// Validate against allowed languages list if defined
	if(!empty($detected_lang) && !empty($allowed_languages)) {
		$short_lang = substr($detected_lang, 0, 2);
		if(in_array($detected_lang, $allowed_languages, true)) {
			// exact match (e.g. en-gb or en)
		} elseif(in_array($short_lang, $allowed_languages, true)) {
			$detected_lang = $short_lang;
		} else {
			$detected_lang = $default_language;
		}
	} elseif(empty($detected_lang)) {
		$detected_lang = $default_language;
	}

	// Persist language selection in session & cookie if URL parameter was specified
	if(!empty($_GET['lang'])) {
		$_SESSION[$lang_key] = $detected_lang;
		$cookie_domain = function_exists('getCookieDomain') ? getCookieDomain() : '';
		$ssl_active    = defined('PHPWCMS_SSL') ? PHPWCMS_SSL : false;
		@setcookie($lang_key, $detected_lang, time() + 31536000, '/', $cookie_domain, $ssl_active, true);
	}

	$phpwcms['i18_lang'] = $detected_lang;
	return $phpwcms['i18_lang'];
}
// get the template file name
function i18n_get_filename() {
	return PHPWCMS_TEMPLATE . 'template_lang/' . i18n_get_language(true) . '.php';
}
function i18n_get_file_open_text() {
	$text  = '<?php' . LF;
	$text .= '// phpwcms template language file "' . i18n_get_language(true) . '" (' . now('Y-m-d H:i:s') . ')' . LF;
	$text .= '// ATTENTION! Never add the closing PHP tag "? >" at the end of this file!' . LF . LF;
	return $text;
}
// substitutes a single token
function i18n_substitute_text_token($token) {
	global $i18n_tokens;
	$a = trim(isset($token[1]) ? $token[1] : $token);
	if($a === '') {
		return '';
	}
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
	if(strpos($tpl_output, '@@') === false) {
		return $tpl_output;
	}
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
