<?php if (!defined('BB2_CORE')) die('I said no cheating!');

function bb2_whitelist($package)
{
	// DANGER! DANGER! DANGER! DANGER! DANGER! DANGER! DANGER! DANGER!

	// Inappropriate whitelisting WILL expose you to spam, or cause Bad
	// Behavior to stop functioning entirely!  DO NOT WHITELIST unless you
	// are 100% CERTAIN that you should.

	// IP address ranges use the CIDR format.

	// Includes four examples of whitelisting by IP address and netblock.
	$bb2_whitelist_ip_ranges = array(
		"10.0.0.0/8",
		"172.16.0.0/12",
		"192.168.0.0/16",
//		"127.0.0.1",
	);

	// DANGER! DANGER! DANGER! DANGER! DANGER! DANGER! DANGER! DANGER!

	// Inappropriate whitelisting WILL expose you to spam, or cause Bad
	// Behavior to stop functioning entirely!  DO NOT WHITELIST unless you
	// are 100% CERTAIN that you should.

	// You should not whitelist search engines by user agent. Use the IP
	// netblock for the search engine instead. See http://whois.arin.net/
	// to locate the netblocks for an IP.

	// User agents are matched by exact match only.

	// Includes one example of whitelisting by user agent.
	// All are commented out.
	$bb2_whitelist_user_agents = array(
	//	"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) It's me, let me in",
	);

	// DANGER! DANGER! DANGER! DANGER! DANGER! DANGER! DANGER! DANGER!

	// Do not edit below this line

	if (!empty($bb2_whitelist_ip_ranges)) {
		foreach ($bb2_whitelist_ip_ranges as $range) {
			if (match_cidr($package['ip'], $range)) return true;
		}
	}
	if (!empty($bb2_whitelist_user_agents)) {
		foreach ($bb2_whitelist_user_agents as $user_agent) {
			if (!strcmp($package['headers_mixed']['User-Agent'], $user_agent)) return true;
		}
	}
	return false;
}

?>