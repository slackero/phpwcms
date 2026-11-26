<?php
// tests/core/test-profile-update-sqli.php — run: php8 tests/core/test-profile-update-sqli.php
// The profile update reused $_SESSION['wcs_user'] in the UPDATE predicate.
// The session value is stored unescaped, so a login name such as
// `x' OR usr_admin=1#` reached SQL on the next request and rewrote an
// administrator row. The predicate now uses the session user id only.

$root = dirname(__DIR__, 2);
$handler = file_get_contents($root . '/include/inc_lib/profile.updateaccount.inc.php');

$failures = 0;
require_once __DIR__ . '/lib/source-fixture.php';

check(
    !preg_match('/usr_login=\W{0,4}\.?\s*\$_(SESSION|POST|GET|REQUEST)/', $handler),
    'no request value is concatenated into a usr_login predicate'
);
check(
    !str_contains($handler, '$_SESSION["wcs_user"]."\' LIMIT 1"'),
    'the raw session login is no longer part of the UPDATE predicate'
);
check(
    str_contains($handler, 'WHERE usr_id=".(int)$_SESSION["wcs_user_id"]'),
    'the UPDATE predicate identifies the row by the session user id only'
);
check(
    str_contains($handler, '" LIMIT 1"'),
    'the UPDATE keeps its LIMIT 1 guard'
);

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
