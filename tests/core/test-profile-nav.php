<?php
// tests/core/test-profile-nav.php — run: php8 tests/core/test-profile-nav.php
// Every logged-in backend user owns their account and must reach the profile
// page, independent of any usergroup membership.

$root = dirname(__DIR__, 2);
$failures = 0;

function check(bool $ok, string $message): void
{
    global $failures;
    echo ($ok ? 'OK   ' : 'FAIL ') . $message . "\n";
    if (!$ok) {
        $failures++;
    }
}

$backend = (string)file_get_contents($root . '/phpwcms.php');

check(
    !str_contains($backend, "\$grouparray['profile']"),
    'the profile entry is not gated by a usergroup'
);
check(
    str_contains($backend, "href=\"phpwcms.php?do=profile\""),
    'the backend navigation offers the profile page'
);
check(
    str_contains($backend, "case 'profile': //profile"),
    'the profile action stays reachable without a permission check'
);

exit($failures === 0 ? 0 : 1);
