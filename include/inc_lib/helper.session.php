<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}

// Cookie settings
if (!empty($phpwcms['SESSION_START'])) {
    _initSession();
}

/**
 * Set session var.
 *
 * @access public
 * @param string $key
 * @param mixed $data
 * @return null|true
 */
function set_session_var($key, $data) {

	if(isset($_SESSION) && is_string($key)) {

		$_SESSION[$key] = $data;

		return true;

	}

	return null;
}

/**
 * Get value of a session var.
 * Returns $fallback if session is not set.
 *
 * @access public
 * @param string $key
 * @param mixed $fallback (default: null)
 * @return mixed
 */
function get_session_var($key, $fallback=null) {

	if(isset($_SESSION[$key])) {

		return $_SESSION[$key];

	}

	return $fallback;
}

/**
 * Unset session var.
 *
 * @access public
 * @param string $key
 * @return null
 */
function unset_session_var($key) {

	if(isset($_SESSION[$key])) {

		$_SESSION[$key] = '';
		unset($_SESSION[$key]);

	}

	return null;
}

/**
 * Generates a unique token name.
 *
 * @access public
 * @param string $prefix (default: 'csrf')
 * @return string
 */
function generate_token_name($prefix='csrf') {

	return uniqid($prefix, true);

}

/**
 * Generate a more short token with shorter value
 */
/**
 * Generates a token with md5 based unique value.
 * The token is registered as session var too.
 * Generates a shorter token as recommend with GET parameters.
 *
 * @access public
 * @param string $get_token_name (default: 'csrftoken')
 * @return string
 */
function generate_get_token($get_token_name='csrftoken') {

	$token_name = '_gettoken_'.$get_token_name;
	$token_value = md5( generate_token_name($token_name) );

	set_session_var($token_name, $token_value);

	return $token_value;

}

/**
 * Get the token value.
 * Returns empty string if token is not registered in the session.
 *
 * @access public
 * @param string $get_token_name (default: 'csrftoken')
 * @return mixed
 */
function get_token_get_value($get_token_name='csrftoken') {

	$token_name = '_gettoken_'.$get_token_name;
	return get_session_var($token_name, '');

}

/**
 * Get the token and token value formatted as GET parameter 'name=value'.
 * Value part is empty string if token is not registered in the session 'name='.
 *
 * @access public
 * @param string $get_token_name (default: 'csrftoken')
 * @return string
 */
function get_token_get_string($get_token_name='csrftoken') {

	return $get_token_name.'='.get_token_get_value($get_token_name);

}

/**
 * Get the token and token value as array.
 * Value part is empty string if token is not registered in the session 'name='.
 *
 * @access public
 * @param string $get_token_name (default: 'csrftoken')
 * @return array
 */
function get_token_get_array($get_token_name='csrftoken') {

	return array(
		'name' => $get_token_name,
		'value' => get_token_get_value($get_token_name)
	);

}

/**
 * Generate a complex unique token and token value and store it in a session value.
 *
 * @access public
 * @param string $unique_name
 * @return string
 */
function generate_session_token($unique_name) {

	if(function_exists('hash_algos') && in_array('sha512', hash_algos())) {

		$token = hash('sha512', uniqid('', true));

	} else {

		$token = '';

		for($i=0; $i < 128; $i++) {
			$r = mt_rand(0, 35);
			$token .= chr( ($r < 26) ? (ord('a') + $r) : (ord('0') + $r - 26) );
		}
	}

	set_session_var($unique_name, $token);
	set_cached_token($unique_name);

	return $token;

}

/**
 * Store a session key to the unique token cache.
 *
 * @access public
 * @param string $unique_name
 * @return void
 */
function set_cached_token($unique_name) {

	if(!isset($_SESSION['cached_unique_tokens'])) {
		$_SESSION['cached_unique_tokens'] = array();
	}

	$_SESSION['cached_unique_tokens'][$unique_name] = time();

}

/**
 * Unset in unique token cache.
 *
 * @access public
 * @param string $unique_name
 * @return void
 */
function unset_cached_token($unique_name) {

	unset($_SESSION['cached_unique_tokens'][$unique_name]);

}

/**
 * Validate the token.
 *
 * @access public
 * @param string $unique_name
 * @param string $token_value
 * @return bool
 */
function validate_session_token($unique_name, $token_value) {

	$token = get_session_var($unique_name);

	if($token === false) {

		return false;

	} elseif($token === $token_value) {

		$result = true;

	} else {

		$result = false;

	}

	unset_session_var($unique_name);
	unset_cached_token($unique_name);

	return $result;

}

/**
 * Return a unique ID from integer or reverse it.
 * rand_uniqid(9007199254740989) = 'PpQXn7COf'
 * rand_uniqid('PpQXn7COf', true) = '9007199254740989'
 *
 * @access public
 * @author Enrico Pallazzo
 * @link http://php.net/manual/de/function.uniqid.php#96898
 * @param mixed $in
 * @param bool $to_num (default: false)
 * @param bool $pad_up (default: false)
 * @param mixed $passkey (default: null)
 * @return string
 */
function rand_uniqid($in, $to_num=false, $pad_up=false, $passkey=null) {

	$index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

	if($passkey !== null) {

		// Although this function's purpose is to just make the
		// ID short - and not so much secure,
		// you can optionally supply a password to make it harder
		// to calculate the corresponding numeric ID

		$i = array();
		$p = array();

		for($n = 0; $n < strlen($index); $n++) {
			$i[] = substr($index, $n, 1);
		}

		$passhash = hash('sha256', $passkey);
		$passhash = (strlen($passhash) < strlen($index)) ? hash('sha512', $passkey) : $passhash;

		for($n=0; $n < strlen($index); $n++) {
			$p[] = substr($passhash, $n, 1);
		}

		array_multisort($p, SORT_DESC, $i);
		$index = implode($i);
	}

	$base = strlen($index);

	if($to_num) {

		// Digital number <<-- alphabet letter code
		$in	 = strrev($in);
		$out = 0;
		$len = strlen($in) - 1;
		for($t = 0; $t <= $len; $t++) {
			$bcpow = bcpow($base, $len - $t);
			$out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
		}

		if(is_numeric($pad_up)) {
			$pad_up--;
			if($pad_up > 0) {
				$out -= pow($base, $pad_up);
			}
		}
		$out = sprintf('%F', $out);
		$out = substr($out, 0, strpos($out, '.'));

	} else {

		// Digital number -->> alphabet letter code
		if(is_numeric($pad_up)) {
			$pad_up--;
			if($pad_up > 0) {
				$in += pow($base, $pad_up);
			}
		}

		$out = '';
		for ($t = floor(log($in, $base)); $t >= 0; $t--) {
			$bcp = bcpow($base, $t);
			$a	 = floor($in / $bcp) % $base;
			$out = $out . substr($index, $a, 1);
			$in	 = $in - ($a * $bcp);
		}
		$out = strrev($out); // reverse
	}

	return $out;
}

/**
 * Search forms and add (CSRF) tokens.
 *
 * @access public
 * @param string $html
 * @return string
 */
function tokenize_forms($html) {

    return preg_replace_callback('/<form(.*?)>/s', 'get_tokenized_form', $html);

}

/**
 * Callback function to add the CSRF token input fields to a form.
 * Forms with attribute 'data-csrf="off"' are ignored.
 *
 * @access public
 * @param array $match
 * @param string $token_prefix (default: 'csrf_')
 * @return string
 */
function get_tokenized_form($match, $token_prefix='csrf_') {

	$form = $match[0];

	if(strpos($match[1], 'data-csrf="off"') === false) {

		$token_name = generate_token_name();
		$token_value = generate_session_token($token_name);

		$form .= '<input type="hidden" name="'.$token_prefix.'token_name" value="'.$token_name.'" />';
		$form .= '<input type="hidden" name="'.$token_prefix.'token_value" value="'.$token_value.'" />';

	}

	return $form;
}

/**
 * Add csrftoken GET parameter to backend links.
 *
 * @access public
 * @param string $html
 * @return string
 */
function tokenize_urls($html) {

	$get_token = get_token_get_string();

	if($get_token) {

		$search = array(
			'phpwcms.php?',
			'/act_structure.php?',
			'/act_articlecontent.php?',
			'/act_file.php?',
			'/act_download.php?',
			'/act_filecat.php?',
			'/act_usergroup.php?',
			'/act_user.php?',
			'/act_frontendsetup.php?',
			'/act_message.php?',
			'/act_cache.php?'
		);

		$replace = array(
			'phpwcms.php?'.$get_token.'&amp;',
			'/act_structure.php?'.$get_token.'&amp;',
			'/act_articlecontent.php?'.$get_token.'&amp;',
			'/act_file.php?'.$get_token.'&amp;',
			'/act_download.php?'.$get_token.'&amp;',
			'/act_filecat.php?'.$get_token.'&amp;',
			'/act_usergroup.php?'.$get_token.'&amp;',
			'/act_user.php?'.$get_token.'&amp;',
			'/act_frontendsetup.php?'.$get_token.'&amp;',
			'/act_message.php?'.$get_token.'&amp;',
			'/act_cache.php?'.$get_token.'&amp;'
		);

		$html = str_replace($search, $replace, $html);
	}

	return $html;
}

/**
 * Validate CSRF tokens, POST and GET.
 * User will get logged out in case error reporting does not stop script.
 *
 * @access public
 * @param string $token_prefix (default: 'csrf_')
 * @return void
 */
function validate_csrf_tokens($token_prefix='csrf_') {

	if($_SERVER['REQUEST_METHOD'] === 'POST' && count($_POST)) {

		if(empty($_POST[$token_prefix.'token_name']) || empty($_POST[$token_prefix.'token_value'])) {
			logout_user('csrf-post-invalid', 'danger');
		}

		if(!validate_session_token($_POST[$token_prefix.'token_name'], $_POST[$token_prefix.'token_value'])) {
			logout_user('csrf-post-failed', 'danger');
		}

	} else {

		validate_csrf_get_token();

	}

	// Purge cached tokens
	if($cached_tokens = get_session_var('cached_unique_tokens')) {

		$now = time();
		$timespan = 60 * 15; // 15 Minutes

		foreach($cached_tokens as $unique_name => $time) {

			if($time < ($now - $timespan)) {
				unset_session_var($unique_name);
				unset_cached_token($unique_name);
			}

		}

	}

}

/**
 * Validate CSRF token, GET only.
 * User will get logged out in case $logout=true and error reporting does not stop script.
 *
 * @access public
 * @param string $token_name (default: 'csrftoken')
 * @param bool $logout (default: true)
 * @return bool
 */
function validate_csrf_get_token($token_name='csrftoken', $logout=true) {

	if($_SERVER['REQUEST_METHOD'] === 'GET') {

		if(empty($_GET[$token_name])) {
			if($logout) {
				logout_user('csrf-get-invalid', 'danger');
			}
			return false;
		}

		if($_GET[$token_name] !== get_token_get_value($token_name)) {
			if($logout) {
				logout_user('csrf-get-failed', 'danger');
			}
			return false;
		}

	}

	return true;

}