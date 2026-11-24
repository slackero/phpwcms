<?php
// tests/core/test-cmsimage-grant.php — run: php tests/core/test-cmsimage-grant.php
// img/cmsimage.php served image records anonymously when only f_public matched,
// ignoring f_granted. The shared _getFileInfo() lookup requires f_granted=0 for
// requests without a logged-in frontend user.

$root = dirname(__DIR__, 2);
$cmsimage = file_get_contents($root . '/img/cmsimage.php');

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

function extract_fn(string $source, string $name): void
{
    if (!preg_match('/^function ' . preg_quote($name, '/') . '\b.*?^}/ms', $source, $match)) {
        echo "FAIL cannot extract $name\n";
        exit(1);
    }

    eval($match[0]);
}

extract_fn($cmsimage, 'cmsimage_feuser_logged_in');
extract_fn($cmsimage, 'cmsimage_file_public_clause');

session_start();

$_GET = array();
$_POST = array();
$_SESSION = array();

check(cmsimage_file_public_clause() === 'f_public=1 AND f_granted=0', 'anonymous request requires f_granted=0');

$_SESSION['wcs_user_id'] = 5;
check(
    cmsimage_file_public_clause() === '(f_public=1 OR f_uid=5) AND f_granted=0',
    'backend user without frontend login still requires f_granted=0'
);

$session_key = session_id();
$_SESSION[$session_key] = 77;
check(cmsimage_feuser_logged_in() === true, 'session key marks a logged-in frontend user');
check(cmsimage_file_public_clause() === '(f_public=1 OR f_uid=5)', 'logged-in frontend user may read granted files');

$_GET[$session_key] = 1;
check(cmsimage_feuser_logged_in() === false, 'GET parameter named like the session key is not trusted');
unset($_GET[$session_key]);

$_POST[$session_key] = 1;
check(cmsimage_feuser_logged_in() === false, 'POST parameter named like the session key is not trusted');
unset($_POST[$session_key]);

unset($_SESSION[$session_key]);
check(cmsimage_feuser_logged_in() === false, 'missing session entry means anonymous');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
