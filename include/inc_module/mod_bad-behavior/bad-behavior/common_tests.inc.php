<?php if (!defined('BB2_CORE')) die('I said no cheating!');

// Enforce adherence to protocol version claimed by user-agent.

function bb2_protocol($settings, $package)
{
	// Is it claiming to be HTTP/1.0?  Then it shouldn't do HTTP/1.1 things
	// Always run this test; we should never see Expect:
	if (array_key_exists('Expect', $package['headers_mixed']) && stripos($package['headers_mixed']['Expect'], "100-continue") !== FALSE) {
		return "a0105122";
	}

	// Is it claiming to be HTTP/1.1?  Then it shouldn't do HTTP/1.0 things
	// Blocks some common corporate proxy servers in strict mode
	if ($settings['strict'] && !strcmp($package['server_protocol'], "HTTP/1.1")) {
		if (array_key_exists('Pragma', $package['headers_mixed']) && strpos($package['headers_mixed']['Pragma'], "no-cache") !== FALSE && !array_key_exists('Cache-Control', $package['headers_mixed'])) {
			return "41feed15";
		}
	}
	return false;
}

function bb2_misc_headers($settings, $package)
{
	$ua = $package['headers_mixed']['User-Agent'];

	if (!strcmp($package['request_method'], "POST") && empty($ua)) {
		return "f9f2b8b9";
	}

	// Broken spambots send URLs with various invalid characters
	// Some broken browsers send the #vector in the referer field :(
	// if (strpos($package['request_uri'], "#") !== FALSE || strpos($package['headers_mixed']['Referer'], "#") !== FALSE) {
	if (strpos($package['request_uri'], "#") !== FALSE) {
		return "dfd9b1ad";
	}

	// Range: field exists and begins with 0
	// Real user-agents do not start ranges at 0
	// NOTE: this blocks the whois.sc bot. No big loss.
	// Exceptions: MT (not fixable); LJ (refuses to fix; may be
	// blocked again in the future)
	if (array_key_exists('Range', $package['headers_mixed']) && strpos($package['headers_mixed']['Range'], "=0-") !== FALSE) {
		if (strncmp($ua, "MovableType", 11) && strncmp($ua, "URI::Fetch", 10)) {
			return "7ad04a8a";
		}
	}

	// Content-Range is a response header, not a request header
	if (array_key_exists('Content-Range', $package['headers_mixed'])) {
		return '7d12528e';
	}

	// Lowercase via is used by open proxies/referrer spammers
	// Exceptions: Clearswift uses lowercase via (refuses to fix;
	// may be blocked again in the future)
	if (array_key_exists('via', $package['headers']) &&
		!strstr($package['headers']['via'],'Clearswift Web Policy Engine')) {
		return "9c9e4979";
	}

	// pinappleproxy is used by referrer spammers
	if (array_key_exists('Via', $package['headers_mixed'])) {
		if (stripos($package['headers_mixed']['Via'], "pinappleproxy") !== FALSE || stripos($package['headers_mixed']['Via'], "PCNETSERVER") !== FALSE || stripos($package['headers_mixed']['Via'], "Invisiware") !== FALSE) {
			return "939a6fbb";
		}
	}

	// TE: if present must have Connection: TE
	// RFC 2616 14.39
	// Blocks Microsoft ISA Server 2004 in strict mode. Contact Microsoft
	// to obtain a hotfix.
	if ($settings['strict'] && array_key_exists('Te', $package['headers_mixed'])) {
		if (!preg_match('/\bTE\b/', $package['headers_mixed']['Connection'])) {
			return "582ec5e4";
		}
	}

	if (array_key_exists('Connection', $package['headers_mixed'])) {
		// Connection: keep-alive and close are mutually exclusive
		if (preg_match('/\bKeep-Alive\b/i', $package['headers_mixed']['Connection']) && preg_match('/\bClose\b/i', $package['headers_mixed']['Connection'])) {
			return "a52f0448";
		}
		// Close shouldn't appear twice
		if (preg_match('/\bclose,\s?close\b/i', $package['headers_mixed']['Connection'])) {
			return "a52f0448";
		}
		// Keey-Alive shouldn't appear twice either
		if (preg_match('/\bkeep-alive,\s?keep-alive\b/i', $package['headers_mixed']['Connection'])) {
			return "a52f0448";
		}
	}
	

	// Headers which are not seen from normal user agents; only malicious bots
	if (array_key_exists('X-Aaaaaaaaaaaa', $package['headers_mixed']) || array_key_exists('X-Aaaaaaaaaa', $package['headers_mixed'])) {
		return "b9cc1d86";
	}
	if (array_key_exists('Proxy-Connection', $package['headers_mixed'])) {
		return "b7830251";
	}

	if (array_key_exists('Referer', $package['headers_mixed'])) {
		// Referer, if it exists, must not be blank
		if (empty($package['headers_mixed'])) {
			return "69920ee5";
		}

		// Referer, if it exists, must contain a :
		// While a relative URL is technically valid in Referer, all known
		// legit user-agents send an absolute URL
		if (strpos($package['headers_mixed']['Referer'], ":") === FALSE) {
			return "45b35e30";
		}
	}
	
	return false;
}

?>