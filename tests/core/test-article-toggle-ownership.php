<?php
// tests/core/test-article-toggle-ownership.php — run: php8 tests/core/test-article-toggle-ownership.php
// act_articlecontent.php case 3 changed article_aktiv by id only, so a non-admin
// could publish or hide an article owned by another user. The update has to
// carry the same owner-or-admin clause as the other cases.

$root = dirname(__DIR__, 2);
$source = file_get_contents($root . '/include/inc_act/act_articlecontent.php');

$failures = 0;

function check(bool $ok, string $message): void
{
    global $failures;
    if ($ok) {
        echo "OK   $message\n";
        return;
    }
    echo "FAIL $message\n";
    $failures++;
}

if (!preg_match('/case 3:.*?break;/s', $source, $match)) {
    echo "FAIL cannot locate case 3\n";
    exit(1);
}

$case3 = $match[0];

check(strpos($case3, 'article_aktiv=') !== false, 'case 3 still toggles article_aktiv');
check(strpos($case3, 'article_uid=') !== false, 'case 3 restricts by article owner');
check(strpos($case3, 'wcs_user_admin') !== false, 'case 3 exempts administrators');
check(strpos($case3, 'article_id=') !== false, 'case 3 still targets the requested article');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
