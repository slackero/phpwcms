<?php if (!defined('BB2_CORE')) die('I said no cheating!');

// Specialized screening for trackbacks
function bb2_trackback($package)
{
	// Web browsers don't send trackbacks
	if ($package['is_browser']) {
		return 'f0dcb3fd';
	}

	// Proxy servers don't send trackbacks either
	if (array_key_exists('Via', $package['headers_mixed']) || array_key_exists('Max-Forwards', $package['headers_mixed']) || array_key_exists('X-Forwarded-For', $package['headers_mixed']) || array_key_exists('Client-Ip', $package['headers_mixed'])) {
		return 'd60b87c7';
	}
	return false;
}

?>