<?php
// tests/core/test-form-upload-quarantine.php — run: php8 tests/core/test-form-upload-quarantine.php
// Revision r564 moves uploads with an executable extension out of the web
// served content/form folder, keeps regular uploads and the shipped dot
// files, does not recurse, and never removes anything.

$source = file_get_contents(dirname(__DIR__, 2) . '/include/inc_lib/revision/r564.php');
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

function remove_tree(string $path): void
{
    if (!is_dir($path)) {
        @unlink($path);
        return;
    }
    foreach (array_diff(scandir($path), ['.', '..']) as $entry) {
        remove_tree($path . '/' . $entry);
    }
    @rmdir($path);
}

foreach (['phpwcms_revision_r564', 'phpwcms_revision_r564_protect', 'phpwcms_revision_r564_report', 'phpwcms_revision_r564_log'] as $name) {
    extract_fn($source, $name);
}

$root = sys_get_temp_dir() . '/phpwcms-r564-' . bin2hex(random_bytes(4));
define('PHPWCMS_ROOT', $root);
define('PHPWCMS_TEMP', $root . '/content/tmp/');
define('CONTENT_PATH', 'content/');

mkdir($root . '/content/form/sub', 0777, true);
mkdir($root . '/content/tmp', 0777, true);
mkdir($root . '/include/inc_lib', 0777, true);
check(
    copy(dirname(__DIR__, 2) . '/include/inc_lib/upload-extensions.inc.php', $root . '/include/inc_lib/upload-extensions.inc.php'),
    'shared extension list copied into the test root'
);

$dangerous = ['marker.php', 'x.PHTML', 'notes.php.5', str_repeat('a', 250) . '.php', str_repeat('ä', 120) . '.php'];
$regular = ['photo.jpg', 'php.jpg', 'README', 'index.html', '.htaccess', 'sub/marker.php'];

foreach (array_merge($dangerous, $regular) as $file) {
    file_put_contents($root . '/content/form/' . $file, 'x');
}

check(phpwcms_revision_r564() === true, 'revision reports success');
check(
    strpos((string)($GLOBALS['phpwcms']['revision_return'] ?? ''), 'Quarantined ' . count($dangerous)) !== false,
    'revision return message names the count'
);

foreach ($dangerous as $file) {
    check(!file_exists($root . '/content/form/' . $file), $file . ' left content/form');
}

$quarantined_files = glob($root . '/content/tmp/.form-upload-quarantine/*/*.quarantine');
check(
    count($quarantined_files) === count($dangerous),
    'every dangerous upload was quarantined'
);
foreach ($quarantined_files as $qfile) {
    $qname = basename($qfile);
    check(
        preg_match('/\.php\./i', $qname) === 0 && strlen($qname) <= 255,
        $qname . ' neutralizes embedded extensions and stays within NAME_MAX'
    );
}

foreach ($regular as $file) {
    check(file_exists($root . '/content/form/' . $file), $file . ' stays in content/form');
}

$quarantine_dirs = glob($root . '/content/tmp/.form-upload-quarantine/*', GLOB_ONLYDIR);
check(!empty($quarantine_dirs), 'quarantine folder was created');

if (!empty($quarantine_dirs)) {
    check(is_file($quarantine_dirs[0] . '/.htaccess'), 'quarantine folder denies web access');
    check(is_file($quarantine_dirs[0] . '/index.html'), 'quarantine folder has an index.html');
}

unset($GLOBALS['phpwcms']['revision_return']);
check(phpwcms_revision_r564() === true, 'second run reports success');
check(empty($GLOBALS['phpwcms']['revision_return']), 'second run reports nothing');
check(
    count(glob($root . '/content/tmp/.form-upload-quarantine/*/*.quarantine')) === count($dangerous),
    'second run quarantines nothing'
);

remove_tree($root);

exit($failures === 0 ? 0 : 1);
