<?php
// tests/core/test-random-image-dir.php — run: php8 tests/core/test-random-image-dir.php
// img/random_image.php used to join the unauthenticated `imgdir` value to
// PHPWCMS_ROOT and strip `../` once, so `....//` left a parent-directory
// escape. The shared resolver now realpaths the value and requires it to stay
// inside the root.

$root = dirname(__DIR__, 2);
$lib = file_get_contents($root . '/include/inc_lib/general.inc.php');

define('PHPWCMS_ROOT', $root);

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

extract_fn($lib, 'path_is_within');
extract_fn($lib, 'phpwcms_image_dir_path');

$fixture = $root . '/tests/.tmp-random-image-' . getmypid();
$outside = dirname($fixture) . '/.tmp-random-image-outside-' . getmypid();

function cleanup_fixture(string $fixture, string $outside): void
{
    @unlink($fixture . '/escape');
    @rmdir($fixture . '/inside/sub');
    @rmdir($fixture . '/inside');
    @rmdir($fixture);
    @rmdir($outside);
}

@mkdir($fixture . '/inside/sub', 0777, true);
@mkdir($outside, 0777, true);
register_shutdown_function('cleanup_fixture', $fixture, $outside);

check(phpwcms_image_dir_path('inside', $fixture) === $fixture . '/inside/', 'contained directory resolves with trailing slash');
check(phpwcms_image_dir_path('inside/sub', $fixture) === $fixture . '/inside/sub/', 'nested directory resolves');
check(phpwcms_image_dir_path('/inside', $fixture) === $fixture . '/inside/', 'leading slash is treated as relative to the root');
check(phpwcms_image_dir_path('inside/../inside', $fixture) === $fixture . '/inside/', 'internal parent segment stays inside the root');

check(phpwcms_image_dir_path('....//.tmp-random-image-outside-' . getmypid(), $fixture) === '', '....// escape is rejected');
check(phpwcms_image_dir_path('..', $fixture) === '', 'plain parent directory is rejected when outside the root');
check(phpwcms_image_dir_path('../.tmp-random-image-outside-' . getmypid(), $fixture) === '', 'parent escape is rejected');
check(phpwcms_image_dir_path('/etc', $fixture) === '', 'absolute path cannot leave the root');
check(phpwcms_image_dir_path('missing', $fixture) === '', 'missing directory is rejected');
check(phpwcms_image_dir_path('', $fixture) === '', 'empty value is rejected');
check(phpwcms_image_dir_path(array('inside'), $fixture) === '', 'non-string value is rejected');

if (@symlink($outside, $fixture . '/escape') !== false) {
    check(phpwcms_image_dir_path('escape', $fixture) === '', 'symlink pointing outside the root is rejected');
}

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
