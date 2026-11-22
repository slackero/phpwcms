<?php
// tests/core/test-revision-r565.php — run: php8 tests/core/test-revision-r565.php
// r565 moves the private content directories to the storage root and leaves a
// deny file behind whenever something could not be moved.

$base = sys_get_temp_dir() . '/phpwcms-r565-' . bin2hex(random_bytes(4));
$root = $base . '/www';
$storage = $base . '/storage';

$failures = 0;
function check(bool $ok, string $message): void
{
    global $failures;
    echo ($ok ? 'OK   ' : 'FAIL ') . $message . "\n";
    if (!$ok) {
        $failures++;
    }
}

function rrmdir(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }
    @chmod($dir, 0700);
    foreach (array_diff(scandir($dir) ?: [], ['.', '..']) as $entry) {
        $path = $dir . '/' . $entry;
        is_dir($path) && !is_link($path) ? rrmdir($path) : @unlink($path);
    }
    @rmdir($dir);
}

foreach (['tmp', 'cache', 'log', 'backup', 'form'] as $dir) {
    @mkdir($root . '/content/' . $dir, 0775, true);
}
@mkdir($root . '/content/less', 0775, true);
@mkdir($storage, 0700, true);

file_put_contents($root . '/content/tmp/a.txt', 'A');
file_put_contents($root . '/content/tmp/index.html', 'protector');
file_put_contents($root . '/content/cache/b.txt', 'B');
file_put_contents($root . '/content/log/c.txt', 'C');
file_put_contents($root . '/content/backup/d.sql', 'D');
file_put_contents($root . '/content/form/e.pdf', 'E');
file_put_contents($root . '/content/less/index.html', 'less');
@mkdir($root . '/content/tmp/blocked', 0775, true);
file_put_contents($root . '/content/tmp/blocked/inside.txt', 'blocked');
// a regular file at the target blocks moving the directory of the same name
file_put_contents($storage . '/tmp-blocker', 'x');
@mkdir($storage . '/tmp', 0700, true);
file_put_contents($storage . '/tmp/blocked', 'blocker');

define('PHPWCMS_ROOT', $root);
define('PHPWCMS_STORAGE_ROOT', $storage . '/');

require __DIR__ . '/../../include/inc_lib/revision/r565.php';

check(phpwcms_revision_r565() === true, 'first run returns true');

check(is_file($storage . '/tmp/a.txt') && file_get_contents($storage . '/tmp/a.txt') === 'A', 'tmp file moved');
check(is_file($storage . '/cache/b.txt'), 'cache file moved');
check(is_file($storage . '/log/c.txt'), 'log file moved');
check(is_file($storage . '/backup/d.sql'), 'backup file moved');
check(is_file($storage . '/form/e.pdf'), 'form file moved');

check(!is_dir($root . '/content/cache'), 'emptied cache directory removed');
check(!is_dir($root . '/content/log'), 'emptied log directory removed');
check(!is_dir($root . '/content/backup'), 'emptied backup directory removed');
check(!is_dir($root . '/content/form'), 'emptied form directory removed');
check(!is_dir($root . '/content/less'), 'unused less directory removed');

check(is_dir($root . '/content/tmp'), 'directory with an unmoved entry stays');
check(is_file($root . '/content/tmp/blocked/inside.txt'), 'unmoved entry is preserved');
check(is_file($root . '/content/tmp/.htaccess'), 'leftover directory gets a deny file');

check(phpwcms_revision_r565() === true, 'second run stays successful');
check(is_file($storage . '/tmp/a.txt'), 'second run does not lose moved files');

// cross-device storage root: rename() fails, the move must copy then unlink,
// and a failed cleanup must never leave a duplicate copy behind
$r565_source = (string)file_get_contents(__DIR__ . '/../../include/inc_lib/revision/r565.php');
check(str_contains($r565_source, '@copy($source, $target)'), 'move falls back to copy+unlink across filesystems');

@mkdir($base . '/xfer-src', 0700, true);
file_put_contents($base . '/xfer-src/file.txt', 'XFER');
@mkdir($base . '/xfer-dst', 0700, true);
@chmod($base . '/xfer-src', 0555);
$moved = phpwcms_revision_r565_move($base . '/xfer-src/file.txt', $base . '/xfer-dst/file.txt');
@chmod($base . '/xfer-src', 0700);
check($moved === false, 'move reports failure when the source cannot be removed');
check(!file_exists($base . '/xfer-dst/file.txt'), 'failed move does not leave a duplicate copy');

$isRoot = function_exists('posix_geteuid') && posix_geteuid() === 0;
if (!$isRoot) {
    @chmod($storage, 0500);
    $GLOBALS['phpwcms']['revision_return'] = '';
    check(phpwcms_revision_r565() === false, 'unwritable storage root fails the revision');
    check($GLOBALS['phpwcms']['revision_return'] !== '', 'failure is reported in revision_return');
    @chmod($storage, 0700);
} else {
    echo "SKIP unwritable storage root (running as root)\n";
}

rrmdir($base);
exit($failures === 0 ? 0 : 1);
