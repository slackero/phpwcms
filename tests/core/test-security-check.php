<?php
// tests/core/test-security-check.php — run: php8 tests/core/test-security-check.php
// The backend security check must find missing protectors and private storage,
// and repair the safe subset without deleting anything.

$base = sys_get_temp_dir() . '/phpwcms-sec-' . bin2hex(random_bytes(4));
$root = $base . '/docroot';
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
function by_id(array $checks, string $id): array
{
    foreach ($checks as $row) {
        if ($row['id'] === $id) {
            return $row;
        }
    }

    return ['id' => $id, 'label' => '', 'status' => 'missing', 'detail' => '', 'fixable' => false];
}

require __DIR__ . '/../../include/inc_lib/security-check.inc.php';

// --- broken installation
@mkdir($root . '/content/tmp', 0775, true);
@mkdir($root . '/content/cache', 0775, true);
@mkdir($root . '/content/form', 0775, true);
@mkdir($root . '/include/config', 0775, true);
@mkdir($root . '/upload', 0775, true);
@mkdir($root . '/tests', 0775, true);
@mkdir($root . '/filearchive', 0775, true);
@mkdir($root . '/setup', 0775, true);
file_put_contents($root . '/content/tmp/leftover.txt', 'x');
file_put_contents($root . '/.htaccess', "RedirectMatch 404 /content/(tmp|cache|log|backup|form)/\n");

$checks = phpwcms_security_check($root, $storage);
check(by_id($checks, 'storage_root')['status'] === 'fail', 'missing storage root is a failure');
check(by_id($checks, 'storage_dir:tmp')['status'] === 'fail' && by_id($checks, 'storage_dir:tmp')['fixable'] === true, 'missing private tmp is fixable');
check(by_id($checks, 'legacy_dir:tmp')['status'] === 'fail' && by_id($checks, 'legacy_dir:tmp')['fixable'] === true, 'legacy tmp inside the docroot is a fixable failure');
check(by_id($checks, 'legacy_dir:cache')['status'] === 'fail', 'empty legacy cache without a deny file is a failure');
check(by_id($checks, 'protector:content/form/.htaccess')['status'] === 'fail', 'missing form protector is a failure');
check(by_id($checks, 'protector:upload/.htaccess')['status'] === 'fail', 'missing upload protector is a failure');
check(by_id($checks, 'protector:filearchive/.htaccess')['status'] === 'warn', 'filearchive without a deny file is a warning (opt-in)');
check(by_id($checks, 'root_rule')['status'] === 'ok', 'root deny rule is detected');
check(by_id($checks, 'form_token_key')['status'] === 'warn', 'empty form_token_key is a warning');
check(by_id($checks, 'setup_dir')['status'] === 'warn', 'present setup directory is a warning');

$missing_text_keys = array();
foreach ($checks as $row) {
    foreach (array('label_key', 'detail_key') as $kind) {
        $key = (string)($row[$kind] ?? '');
        if ($key === '' || !isset(PHPWCMS_SECURITY_TEXT[$key])) {
            $missing_text_keys[] = $row['id'] . ':' . $kind;
        }
    }
}
check($missing_text_keys === array(), 'every row has a translatable label and detail key');

// --- repair the safe subset
$ids = [];
foreach ($checks as $row) {
    if ($row['fixable']) {
        $ids[] = $row['id'];
    }
}
$fixed = phpwcms_security_fix($root, $storage, $ids);
check(!in_array(false, $fixed, true), 'every fixable check repaired');

check(is_dir($storage . '/tmp') && is_writable($storage . '/tmp'), 'private tmp created');
check(is_dir($storage . '/form'), 'private form created');
check(phpwcms_security_check_denied($root . '/content/tmp/.htaccess'), 'legacy tmp got a deny file');
check(is_file($root . '/content/tmp/leftover.txt'), 'repair never deletes user entries');
check(phpwcms_security_check_denied($root . '/upload/.htaccess'), 'upload protector written');
check(phpwcms_security_check_denied($root . '/content/form/.htaccess'), 'form protector written');
check(phpwcms_security_check_denied($root . '/filearchive/.htaccess'), 'filearchive protector written');

// --- forged ids must not write outside the installation
$bad = phpwcms_security_fix($root, $storage, array('protector:../escape/.htaccess', 'storage_dir:../escape', 'legacy_dir:../escape'));
check(!in_array(true, $bad, true), 'unknown or traversing ids are rejected');
check(!is_file($base . '/escape/.htaccess'), 'fixer writes nothing outside the document root');
check(!is_dir($base . '/escape'), 'fixer creates no directory outside the storage root');

// --- rerun: nothing fixable is left, remaining failures are non-fixable
$checks = phpwcms_security_check($root, $storage);
$remaining_fixable = 0;
foreach ($checks as $row) {
    if ($row['fixable']) {
        $remaining_fixable++;
    }
}
check($remaining_fixable === 0, 'no fixable finding survives the repair');
check(by_id($checks, 'legacy_dir:tmp')['status'] === 'warn' && by_id($checks, 'legacy_dir:tmp')['detail'] !== '', 'leftover entries behind a deny file are a warning after the repair');
check(by_id($checks, 'protector:filearchive/.htaccess')['status'] === 'ok', 'filearchive now reports disabled downloads');

rrmdir($base);
exit($failures === 0 ? 0 : 1);
