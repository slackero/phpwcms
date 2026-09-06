<?php
// tests/update/test-upgrade-script.php — run: /Applications/MAMP/bin/php/php8.2.32/bin/php tests/update/test-upgrade-script.php

define('UPGRADE_SCRIPT_TEST_MODE', true);
require_once __DIR__ . '/../../setup/upgrade.php';

echo 'Testing setup/upgrade.php...' . PHP_EOL;

// 1. Host allowlist checks
assert(upgrade_is_allowed_host('github.com') === true, 'github.com allowed');
assert(upgrade_is_allowed_host('objects.githubusercontent.com') === true, 'objects.githubusercontent.com allowed');
assert(upgrade_is_allowed_host('api.github.com') === true, 'api.github.com allowed');
assert(upgrade_is_allowed_host('codeload.github.com') === true, 'codeload.github.com allowed');
assert(upgrade_is_allowed_host('evil.com') === false, 'evil.com rejected');
assert(upgrade_is_allowed_host('github.com.evil.com') === false, 'fake github domain rejected');
assert(upgrade_is_allowed_host('localhost') === false, 'localhost rejected');

// 2. HTTPS enforcement
assert(upgrade_resolve_download_url('http://github.com/test.zip') === false, 'plain http rejected');

// 3. Manifest parsing and path validation
$manifestContent = <<<MANIFEST
# Comment line
e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855  index.php
a591a6d40bf420404a011733cfb7b190d62c65bf0bcda32b57b277d9ad9f146e  ./include/inc_lib/test.php
c2b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855  ../outside.php
d2b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855  /etc/passwd
f2b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855  win\\path.php
MANIFEST;

$parsed = parse_update_manifest($manifestContent);
assert(count($parsed) === 2, 'manifest parsed valid lines only');
assert(isset($parsed['index.php']), 'index.php parsed');
assert(isset($parsed['include/inc_lib/test.php']), 'include/inc_lib/test.php parsed and ./ stripped');
assert(!isset($parsed['../outside.php']), 'traversal rejected');
assert(!isset($parsed['/etc/passwd']), 'absolute path rejected');
assert(!isset($parsed['win\\path.php']), 'backslash rejected');

// 4. Test apply_release_zip with verified manifest vs unverified legacy package
$tmpBase = sys_get_temp_dir() . '/phpwcms_test_' . uniqid();
$docRoot = $tmpBase . '/docroot';
@mkdir($docRoot . '/include/config', 0775, true);
@mkdir($docRoot . '/content/tmp', 0775, true);
file_put_contents($docRoot . '/include/config/conf.inc.php', '<?php $phpwcms = [];');
file_put_contents($docRoot . '/existing.php', 'original');

$conf = [
    'file_path'    => 'filearchive',
    'content_path' => 'content',
    'ftp_path'     => 'upload',
];

// Helper to create a zip file
function create_test_zip(string $zipPath, array $files): void
{
    $zip = new ZipArchive();
    $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    foreach ($files as $name => $content) {
        $zip->addFromString($name, $content);
    }
    $zip->close();
}

// Test A: Package with valid .update-manifest and matching checksums
$fileA = "<?php echo 'new version';\n";
$hashA = hash('sha256', $fileA);
$revFile = "<?php const PHPWCMS_VERSION = '2.0.0';\n";
$hashRev = hash('sha256', $revFile);
$manifestStr = "$hashA  index.php\n$hashRev  include/inc_lib/revision/revision.php\n";

$validZip = $tmpBase . '/valid.zip';
create_test_zip($validZip, [
    '.update-manifest' => $manifestStr,
    'index.php'        => $fileA,
    'include/inc_lib/revision/revision.php' => $revFile,
]);

$res = apply_release_zip($validZip, $docRoot, $conf, null, false, '2.0.0');
assert($res['manifest_verified'] === true, 'manifest verified');
assert($res['copied'] >= 2, 'files copied');
assert(file_get_contents($docRoot . '/index.php') === $fileA, 'index.php updated');
assert(is_file($docRoot . '/.update-manifest'), '.update-manifest copied to docroot');

// Test B: Package with tampered file (checksum mismatch)
$badZip = $tmpBase . '/tampered.zip';
create_test_zip($badZip, [
    '.update-manifest' => $manifestStr,
    'index.php'        => "<?php echo 'tampered content';\n",
    'include/inc_lib/revision/revision.php' => $revFile,
]);

$tamperCaught = false;
try {
    apply_release_zip($badZip, $docRoot, $conf, null, false, '2.0.0');
} catch (RuntimeException $e) {
    if (str_contains($e->getMessage(), 'SHA-256 checksum mismatch')) {
        $tamperCaught = true;
    }
}
assert($tamperCaught === true, 'tampered file caught by SHA-256 check');

// Test C: Package with version mismatch
$mismatchZip = $tmpBase . '/mismatch.zip';
$revOther = "<?php const PHPWCMS_VERSION = '2.0.1';\n";
$hashRevOther = hash('sha256', $revOther);
create_test_zip($mismatchZip, [
    '.update-manifest' => "$hashRevOther  include/inc_lib/revision/revision.php\n",
    'include/inc_lib/revision/revision.php' => $revOther,
]);

$versionMismatchCaught = false;
try {
    apply_release_zip($mismatchZip, $docRoot, $conf, null, false, '2.0.0');
} catch (RuntimeException $e) {
    if (str_contains($e->getMessage(), 'Version mismatch')) {
        $versionMismatchCaught = true;
    }
}
assert($versionMismatchCaught === true, 'version mismatch caught');

// Test D: Legacy package without .update-manifest (< 2.0.0) — should FAIL when allowUnverified is false
$legacyZip = $tmpBase . '/legacy.zip';
create_test_zip($legacyZip, [
    'legacy_file.php' => "<?php echo 'legacy';\n",
]);

$unverifiedBlocked = false;
try {
    apply_release_zip($legacyZip, $docRoot, $conf, null, false, '1.9.36');
} catch (RuntimeException $e) {
    if (str_contains($e->getMessage(), 'does not contain an update manifest')) {
        $unverifiedBlocked = true;
    }
}
assert($unverifiedBlocked === true, 'unverified package blocked by default');

// Test E: Legacy package without .update-manifest (< 2.0.0) — should SUCCEED when allowUnverified is true
$resLegacy = apply_release_zip($legacyZip, $docRoot, $conf, null, true, '1.9.36');
assert($resLegacy['manifest_verified'] === false, 'legacy package not verified');
assert(is_file($docRoot . '/legacy_file.php'), 'legacy file copied successfully with override');

// Test F: Revision detection and < r401 blocking guard
assert(defined('MIN_INSTALLED_REVISION') && MIN_INSTALLED_REVISION === 401, 'MIN_INSTALLED_REVISION is 401');

$revTestBase = $tmpBase . '/rev_test';
@mkdir($revTestBase . '/include/inc_lib/revision', 0775, true);
@mkdir($revTestBase . '/setup/inc', 0775, true);

// F1: Modern revision in include/inc_lib/revision/revision.php
file_put_contents($revTestBase . '/include/inc_lib/revision/revision.php', "<?php const PHPWCMS_REVISION = 549;\n");
assert(get_installed_phpwcms_revision($revTestBase) === 549, 'detected revision 549 from revision.php');
assert(get_installed_phpwcms_revision($revTestBase) >= MIN_INSTALLED_REVISION, 'revision 549 >= 401 allowed');

// F2: Setup-based revision
@unlink($revTestBase . '/include/inc_lib/revision/revision.php');
file_put_contents($revTestBase . '/setup/inc/setup.func.inc.php', "<?php define('PHPWCMS_REVISION', 401);\n");
assert(get_installed_phpwcms_revision($revTestBase) === 401, 'detected revision 401 from setup.func.inc.php');
assert(get_installed_phpwcms_revision($revTestBase) >= MIN_INSTALLED_REVISION, 'revision 401 >= 401 allowed');

// F3: Pre-r401 revision (e.g. r400)
file_put_contents($revTestBase . '/setup/inc/setup.func.inc.php', "<?php define('PHPWCMS_REVISION', 400);\n");
assert(get_installed_phpwcms_revision($revTestBase) === 400, 'detected revision 400');
assert(get_installed_phpwcms_revision($revTestBase) < MIN_INSTALLED_REVISION, 'revision 400 < 401 rejected');

// F4: Legacy installation with no revision tracking at all (r0)
@unlink($revTestBase . '/setup/inc/setup.func.inc.php');
assert(get_installed_phpwcms_revision($revTestBase) === 0, 'missing revision returns 0');
assert(get_installed_phpwcms_revision($revTestBase) < MIN_INSTALLED_REVISION, 'pre-r401 (0) < 401 rejected');

// Test G: Same version vs downgrade comparison logic
assert(version_compare('2.0.0', '2.0.0', '==') === true, 'same version identified');
assert(version_compare('2.0.1', '2.0.0', '>') === true, 'downgrade identified');
assert(version_compare('1.9.34', '2.0.0', '<') === true, 'upgrade identified');

// Cleanup
$rmdirRec = static function ($dir) use (&$rmdirRec) {
    if (!is_dir($dir)) return;
    foreach (scandir($dir) as $f) {
        if ($f === '.' || $f === '..') continue;
        $p = $dir . '/' . $f;
        is_dir($p) ? $rmdirRec($p) : @unlink($p);
    }
    @rmdir($dir);
};
$rmdirRec($tmpBase);

echo 'All setup/upgrade.php tests PASSED!' . PHP_EOL;
