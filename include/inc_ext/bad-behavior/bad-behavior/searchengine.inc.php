<?php if (!defined('BB2_CORE')) die('I said no cheating!');

require_once(BB2_CORE . "/roundtripdns.inc.php");

// Analyze user agents claiming to be Googlebot

function bb2_google($package)
{
	if (match_cidr($package['ip'], array("66.249.64.0/19", "64.233.160.0/19", "72.14.192.0/18", "203.208.32.0/19", "74.125.0.0/16", "216.239.32.0/19")) === FALSE) {
		return "f1182195";
	}
#	Disabled due to http://bugs.php.net/bug.php?id=53092
#	if (!bb2_roundtripdns($package['ip'], "googlebot.com")) {
#		return "f1182195";
#	}
	return false;
}

// Analyze user agents claiming to be msnbot

function bb2_msnbot($package)
{
	if (match_cidr($package['ip'], array("207.46.0.0/16", "65.52.0.0/14", "207.68.128.0/18", "207.68.192.0/20", "64.4.0.0/18", "157.54.0.0/15", "157.60.0.0/16", "157.56.0.0/14")) === FALSE) {
		return "e4de0453";
	}
#	Disabled due to http://bugs.php.net/bug.php?id=53092
#	if (!bb2_roundtripdns($package['ip'], "msn.com")) {
#		return "e4de0453";
#	}
	return false;
}

// Analyze user agents claiming to be Yahoo!

function bb2_yahoo($package)
{
	if (match_cidr($package['ip'], array("202.160.176.0/20", "67.195.0.0/16", "203.209.252.0/24", "72.30.0.0/16", "98.136.0.0/14")) === FALSE) {
		return '71436a15';
	}
#	Disabled due to http://bugs.php.net/bug.php?id=53092
#	if (!bb2_roundtripdns($package['ip'], "crawl.yahoo.net")) {
#		return "71436a15";
#	}
	return false;
}
