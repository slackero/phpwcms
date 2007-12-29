<?php if (!defined('BB2_CORE')) die('I said no cheating!');

// Look up address on various blackhole lists.
// These cannot be used for GET requests under any circumstances!
function bb2_blackhole($package) {
	// Only conservative lists
	$bb2_blackhole_lists = array(
		"sbl-xbl.spamhaus.org",	// All around nasties
//		"dnsbl.sorbs.net",	// Old useless data.
//		"list.dsbl.org",	// Old useless data.
//		"dnsbl.ioerror.us",	// Bad Behavior Blackhole
	);
	
	// Things that shouldn't be blocked, from aggregate lists
	$bb2_blackhole_exceptions = array(
		"sbl-xbl.spamhaus.org" => array("127.0.0.4"),	// CBL is problematic
		"dnsbl.sorbs.net" => array("127.0.0.10",),	// Dynamic IPs only
		"list.dsbl.org" => array(),
		"dnsbl.ioerror.us" => array(),
	);

	// Check the blackhole lists
	$ip = $package['ip'];
	$find = implode('.', array_reverse(explode('.', $ip)));
	foreach ($bb2_blackhole_lists as $dnsbl) {
		$result = gethostbynamel($find . "." . $dnsbl . ".");
		if (!empty($result)) {
			// Got a match and it isn't on the exception list
			$result = @array_diff($result, $bb2_blackhole_exceptions[$dnsbl]);
			if (!empty($result)) {
				return '136673cd';
			}
		}
	}
	return false;
}
?>