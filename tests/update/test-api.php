<?php
// tests/update/test-api.php — run: php8 tests/update/test-api.php
define('PHPWCMS_INCLUDE_CHECK', true);
require_once __DIR__ . '/../../include/inc_lib/update/update.api.php';

assert(phpwcms_update_normalize_tag('v2.0.1') === '2.0.1', 'tag normalize');
assert(phpwcms_update_normalize_tag('2.0.1') === '2.0.1', 'bare version passes through');
assert(phpwcms_update_normalize_tag('v2.0.1-beta') === '', 'pre-release tags rejected');

$release = phpwcms_update_fetch_latest_release(); // live network call — may be false offline
if ($release !== false) {
    assert(strpos($release['zip'], 'https://') === 0, 'zip url must be https');
    assert(strpos($release['tag'], 'v') === 0, 'tag format');
    echo 'Live check OK: latest tag ' . $release['tag'] . PHP_EOL;
} else {
    echo 'Network unavailable — offline assertions only' . PHP_EOL;
}
echo 'OK' . PHP_EOL;
