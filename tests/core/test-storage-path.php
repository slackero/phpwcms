<?php
$source = file_get_contents(dirname(__DIR__, 2) . '/include/inc_lib/storage.inc.php');
$source = preg_replace('/^declare\(strict_types=1\);\s*/m', '', $source);
eval(str_replace('<?php', '', $source));
if (!function_exists('phpwcms_storage_path')) {
    fwrite(STDERR, "FAIL could not load storage.inc.php (extraction or syntax error)\n");
    exit(1);
}
$failures = 0;
function check(bool $ok, string $message): void { global $failures; echo ($ok ? 'OK   ' : 'FAIL ') . "$message\n"; if (!$ok) $failures++; }

check(str_contains($source, 'include/config/conf.inc.php') && !str_contains($source, 'inc.conf.php'), 'error message names conf.inc.php');

check(phpwcms_storage_path('../phpwcms-storage', '/srv/www/site') === '/srv/www/phpwcms-storage', 'relative sibling resolves');
check(phpwcms_storage_path('', '/srv/www/site') === '/srv/www/phpwcms-storage', 'empty uses default sibling');
check(phpwcms_storage_path('/data/private', '/srv/www/site') === '/data/private', 'absolute stays absolute');
check(phpwcms_storage_path('../phpwcms-storage/', '/srv/www/site') === '/srv/www/phpwcms-storage', 'trailing slash removed');
check(phpwcms_storage_path('../../outside', '/srv/www/site') === '/srv/outside', 'parent traversal normalised');

$threw = false;
try {
    phpwcms_storage_require('/nonexistent/phpwcms-storage');
} catch (RuntimeException $e) {
    $threw = true;
}
check($threw, 'missing storage root throws');

$ensureDir = sys_get_temp_dir() . '/phpwcms-storage-' . bin2hex(random_bytes(4)) . '/nested';
check(phpwcms_storage_ensure($ensureDir, 0700) === true, 'ensure creates missing directory');
check((fileperms($ensureDir) & 0777) === 0700, 'ensure uses 0700');
$blocker = tempnam(sys_get_temp_dir(), 'phpwcms-blocker-');
check(phpwcms_storage_ensure($blocker . '/nested', 0700) === false, 'ensure fails below a regular file');
@unlink($blocker);
@rmdir($ensureDir);
@rmdir(dirname($ensureDir));
exit($failures === 0 ? 0 : 1);
