<?php if (!defined('BB2_CORE')) die('I said no cheating!');

// Analyze requests claiming to be from CloudFlare

require_once(BB2_CORE . "/roundtripdns.inc.php");

function bb2_cloudflare($package)
{
	if (!bb2_roundtripdns($package['cloudflare'], "cloudflare.com")) {
		return '70e45496';
	}
	return false;
}

?>