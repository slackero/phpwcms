<?php if (!defined('BB2_CWD')) die("I said no cheating!");

// Bad Behavior entry point is bb2_start()
// If you're reading this, you are probably lost.
// Go read the bad-behavior-generic.php file.

define('BB2_COOKIE', 'bb2_screener_');

require_once(BB2_CORE . "/functions.inc.php");

// Kill 'em all!
function bb2_banned($settings, $package, $key, $previous_key=false)
{
	// Some spambots hit too hard. Slow them down a bit.
	sleep(2);

	require_once(BB2_CORE . "/banned.inc.php");
	bb2_display_denial($settings, $key, $previous_key);
	bb2_log_denial($settings, $package, $key, $previous_key);
	if (is_callable('bb2_banned_callback')) {
		bb2_banned_callback($settings, $package, $key);
	}
	// Penalize the spammers some more
	require_once(BB2_CORE . "/housekeeping.inc.php");
	bb2_housekeeping($settings, $package);
	die();
}

function bb2_approved($settings, $package)
{
	// Dirk wanted this
	if (is_callable('bb2_approved_callback')) {
		bb2_approved_callback($settings, $package);
	}

	// Decide what to log on approved requests.
	if (($settings['verbose'] && $settings['logging']) || empty($package['user_agent'])) {
		bb2_db_query(bb2_insert($settings, $package, "00000000"));
	}
}


// Let God sort 'em out!
function bb2_start($settings)
{
	// Gather up all the information we need, first of all.
	$headers = bb2_load_headers();
	// Postprocess the headers to mixed-case
	// TODO: get the world to stop using PHP as CGI
	$headers_mixed = array();
	foreach ($headers as $h => $v) {
		$headers_mixed[uc_all($h)] = $v;
	}

	// IPv6 - IPv4 compatibility mode hack
	$_SERVER['REMOTE_ADDR'] = preg_replace("/^::ffff:/", "", $_SERVER['REMOTE_ADDR']);

	// Reconstruct the HTTP entity, if present.
	$request_entity = array();
	if (!strcasecmp($_SERVER['REQUEST_METHOD'], "POST") || !strcasecmp($_SERVER['REQUEST_METHOD'], "PUT")) {
		foreach ($_POST as $h => $v) {
			$request_entity[$h] = $v;
		}
	}

	@$package = array('ip' => $_SERVER['REMOTE_ADDR'], 'headers' => $headers, 'headers_mixed' => $headers_mixed, 'request_method' => $_SERVER['REQUEST_METHOD'], 'request_uri' => $_SERVER['REQUEST_URI'], 'server_protocol' => $_SERVER['SERVER_PROTOCOL'], 'request_entity' => $request_entity, 'user_agent' => $_SERVER['HTTP_USER_AGENT'], 'is_browser' => false);

	$result = bb2_screen($settings, $package);
	if ($result && !defined('BB2_TEST')) bb2_banned($settings, $package, $result);
	return $result;
}

function bb2_screen($settings, $package)
{
	// Please proceed to the security checkpoint and have your
	// identification and boarding pass ready.

	// First check the whitelist
	require_once(BB2_CORE . "/whitelist.inc.php");
	if (!bb2_whitelist($package)) {
		// Now check the blacklist
		require_once(BB2_CORE . "/blacklist.inc.php");
		if ($r = bb2_blacklist($package)) return $r;

		// Check the http:BL
		require_once(BB2_CORE . "/blackhole.inc.php");
		if ($r = bb2_httpbl($settings, $package)) return $r;

		// Check for common stuff
		require_once(BB2_CORE . "/common_tests.inc.php");
		if ($r = bb2_protocol($settings, $package)) return $r;
		if ($r = bb2_cookies($settings, $package)) return $r;
		if ($r = bb2_misc_headers($settings, $package)) return $r;

		// Specific checks
		@$ua = $package['user_agent'];
		// MSIE checks
		if (stripos($ua, "; MSIE") !== FALSE) {
			$package['is_browser'] = true;
			if (stripos($ua, "Opera") !== FALSE) {
				require_once(BB2_CORE . "/opera.inc.php");
				if ($r = bb2_opera($package)) return $r;
			} else {
				require_once(BB2_CORE . "/msie.inc.php");
				if ($r = bb2_msie($package)) return $r;
			}
		} elseif (stripos($ua, "Konqueror") !== FALSE) {
			$package['is_browser'] = true;
			require_once(BB2_CORE . "/konqueror.inc.php");
			if ($r = bb2_konqueror($package)) return $r;
		} elseif (stripos($ua, "Opera") !== FALSE) {
			$package['is_browser'] = true;
			require_once(BB2_CORE . "/opera.inc.php");
			if ($r = bb2_opera($package)) return $r;
		} elseif (stripos($ua, "Safari") !== FALSE) {
			$package['is_browser'] = true;
			require_once(BB2_CORE . "/safari.inc.php");
			if ($r = bb2_safari($package)) return $r;
		} elseif (stripos($ua, "Lynx") !== FALSE) {
			$package['is_browser'] = true;
			require_once(BB2_CORE . "/lynx.inc.php");
			if ($r = bb2_lynx($package)) return $r;
		} elseif (stripos($ua, "MovableType") !== FALSE) {
			require_once(BB2_CORE . "/movabletype.inc.php");
			if ($r = bb2_movabletype($package)) return $r;
		} elseif (stripos($ua, "msnbot") !== FALSE || stripos($ua, "MS Search") !== FALSE) {
			require_once(BB2_CORE . "/msnbot.inc.php");
			if ($r = bb2_msnbot($package)) return $r;
		} elseif (stripos($ua, "Googlebot") !== FALSE || stripos($ua, "Mediapartners-Google") !== FALSE || stripos($ua, "Google Wireless") !== FALSE) {
			require_once(BB2_CORE . "/google.inc.php");
			if ($r = bb2_google($package)) return $r;
		} elseif (stripos($ua, "Mozilla") !== FALSE && stripos($ua, "Mozilla") == 0) {
			$package['is_browser'] = true;
			require_once(BB2_CORE . "/mozilla.inc.php");
			if ($r = bb2_mozilla($package)) return $r;
		}

		// More intensive screening applies to POST requests
		if (!strcasecmp('POST', $package['request_method'])) {
			require_once(BB2_CORE . "/post.inc.php");
			if ($r = bb2_post($settings, $package)) return $r;
		}
	}

	// Last chance screening.
	require_once(BB2_CORE . "/screener.inc.php");
	bb2_screener($settings, $package);

	// And that's about it.
	bb2_approved($settings, $package);
	return false;
}
?>
