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

	$allowed_languages = is_array($phpwcms['allowed_lang'] ?? null) ? $phpwcms['allowed_lang'] : [];
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
		$complex = $phpwcms['i18n_complex'] ?? $complex;
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
		$ssl_active    = defined('PHPWCMS_SSL') && (bool)constant('PHPWCMS_SSL');
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
	$a = trim($token[1] ?? $token);
	if($a === '') {
		return '';
	}
	if(isset($i18n_tokens[$a])) {
		return $i18n_tokens[$a];
	}

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

    return $a;
}
// all contents starting and ending with @@ are replaced
function i18n_substitute_text($tpl_output) {
	if(!str_contains($tpl_output, '@@')) {
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

function sanitize_language_code($code) {
	$code = strtolower(trim((string)$code));
	if(preg_match('/^[a-z]{2}(?:-[a-z0-9]{2,})?$/', $code)) {
		return $code;
	}

	return '';
}

function get_language_flag_img($code, $extra_class = '') {
	$code = sanitize_language_code($code);
	if(empty($code)) {
		return '';
	}

	static $lang_flag_map = [
		'en'    => 'gb',
		'de'    => 'de',
		'de-at' => 'at',
		'de-ch' => 'ch',
		'da'    => 'dk',
		'el'    => 'gr',
		'es'    => 'es',
		'et'    => 'ee',
		'eu'    => 'es-pv',
		'fa'    => 'ir',
		'fi'    => 'fi',
		'fr'    => 'fr',
		'gl'    => 'es-ga',
		'he'    => 'il',
		'hi'    => 'in',
		'hr'    => 'hr',
		'hu'    => 'hu',
		'hy'    => 'am',
		'id'    => 'id',
		'is'    => 'is',
		'it'    => 'it',
		'ja'    => 'jp',
		'ka'    => 'ge',
		'kk'    => 'kz',
		'ko'    => 'kr',
		'lt'    => 'lt',
		'lv'    => 'lv',
		'mk'    => 'mk',
		'mn'    => 'mn',
		'ms'    => 'my',
		'nb'    => 'no',
		'nl'    => 'nl',
		'nn'    => 'no',
		'no'    => 'no',
		'pl'    => 'pl',
		'pt'    => 'pt',
		'pt-br' => 'br',
		'ro'    => 'ro',
		'ru'    => 'ru',
		'sk'    => 'sk',
		'sl'    => 'si',
		'sq'    => 'al',
		'sr'    => 'rs',
		'sv'    => 'se',
		'th'    => 'th',
		'tr'    => 'tr',
		'uk'    => 'ua',
		'ur'    => 'pk',
		'vi'    => 'vn',
		'zh'    => 'cn',
		'zh-cn' => 'cn',
		'zh-tw' => 'tw'
	];

	$flag = $lang_flag_map[$code] ?? '';
	if($flag === '' && str_contains($code, '-')) {
		$parts = explode('-', $code);
		$flag = end($parts);
	}
	if($flag === '') {
		$flag = $code;
	}

	$svg_path = PHPWCMS_ROOT . '/img/flags/4x3/' . $flag . '.svg';
	if(is_file($svg_path)) {
		$cls = 'flag-img badge-align' . ($extra_class !== '' ? ' ' . $extra_class : '');
		return '<img src="img/flags/4x3/' . html($flag) . '.svg" alt="' . html(strtoupper($code)) . '" class="' . $cls . '" style="width: 1.15em; height: auto; border-radius: 2px; box-shadow: 0 0 1px rgba(0,0,0,0.4);" />';
	}

	return '';
}

function template_lang_load_file($lang_code) {
	$lang_code = sanitize_language_code($lang_code);
	if(empty($lang_code)) {
		return [];
	}
	$file = PHPWCMS_TEMPLATE . 'template_lang/' . $lang_code . '.php';
	if(!is_file($file) || !is_readable($file)) {
		return [];
	}
	include $file;
	return isset($i18n_tokens) && is_array($i18n_tokens) ? $i18n_tokens : [];
}

function template_lang_save_file($lang_code, array $tokens) {
	$lang_code = sanitize_language_code($lang_code);
	if(empty($lang_code)) {
		return false;
	}
	$dir = PHPWCMS_TEMPLATE . 'template_lang/';
	if(!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
        throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
    }
	$file = $dir . $lang_code . '.php';

	$content  = '<?php' . LF;
	$content .= '// phpwcms template language file "' . $lang_code . '" (' . now('Y-m-d H:i:s') . ')' . LF;
	$content .= '// ATTENTION! Never add the closing PHP tag "? >" at the end of this file!' . LF . LF;

	ksort($tokens, SORT_NATURAL | SORT_FLAG_CASE);

	foreach($tokens as $token_key => $token_val) {
		$content .= '$i18n_tokens[' . var_export((string)$token_key, true) . '] = ' . var_export((string)$token_val, true) . ';' . LF;
	}

	$tmp_file = $file . '.tmp.' . uniqid('', true);
	if(@file_put_contents($tmp_file, $content) !== false) {
		if(@rename($tmp_file, $file)) {
			@chmod($file, 0666);
			return true;
		}
		@unlink($tmp_file);
	}

	return false;
}
