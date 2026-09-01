<?php
// tests/update/test-e2e.php — run: php8 tests/update/test-e2e.php
// End-to-end self-update harness. Stubs the DB layer + revision check and drives
// phpwcms_update::run() against locally built release zips via the optional
// $zipPath constructor seam. No network. Cleans up its temp dirs on exit.
//
// Scenarios:
//   1. fresh install (no old manifest) -> apply, no deletions, manifest written
//   2. second run with a zip removing one file -> deletion executed + backed up
//   3. template/ file locally modified -> overwritten, old version backed up
//   4. DB dump failure (read-only backup dir) -> aborts before download, no log row
//   5. maintenance-flag safety net (fresh vs stale)

define('PHPWCMS_INCLUDE_CHECK', true);

$base = sys_get_temp_dir() . '/phpwcms-e2e-' . getmypid();
define('PHPWCMS_ROOT', $base . '/docroot');
define('PHPWCMS_TEMP', $base . '/tmp/');
define('PHPWCMS_VERSION', '2.0.0');
define('PHPWCMS_REVISION', '559');
define('DB_PREPEND', '');
define('LF', "\n");

// --- DB layer stubs (query-aware, ASSOC-shaped like the real _dbQuery) ---
$GLOBALS['logInserts'] = 0;
$GLOBALS['forceDbDumpFail'] = false;

function _dbQuery($q, $type = '')
{
    if ($GLOBALS['forceDbDumpFail'] && str_starts_with($q, 'SHOW TABLES')) {
        return false;
    }
    if (str_starts_with($q, 'SHOW TABLES')) {
        return [['Tables_in_db' => 'phpwcms_user']];
    }
    if (str_starts_with($q, 'SHOW CREATE TABLE')) {
        return [['Table' => 'phpwcms_user', 'Create Table' => 'CREATE TABLE `phpwcms_user` (`id` int(11) NOT NULL)']];
    }
    if (str_starts_with($q, 'SELECT * FROM')) {
        return [['id' => 1, 'name' => 'admin']];
    }
    if (str_starts_with($q, 'INSERT INTO')) {
        $GLOBALS['logInserts']++;
        return ['INSERT_ID' => 1];
    }
    if (str_starts_with($q, 'UPDATE')) {
        return ['AFFECTED_ROWS' => 1];
    }
    return [];
}
function _dbEscape($v) { return "'" . addslashes((string)$v) . "'"; }
function _dbCount($t) { return 1; }
function _dbColumnExists($t, $c) { return true; }
function _dbTableExists($t) { return true; }
// phpwcms_revision_check lives in backend.functions.inc.php (not loaded here).
function phpwcms_revision_check($rev) { return true; }

require_once __DIR__ . '/../../include/inc_lib/update/update.php';

// --- helpers ---

function rrmdir(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($items as $item) {
        if ($item->isDir() && !$item->isLink()) {
            @rmdir($item->getPathname());
        } else {
            @unlink($item->getPathname());
        }
    }
    @rmdir($dir);
}

/** Wipe the docroot and lay down a minimal fresh install. */
function resetDocroot(): void
{
    rrmdir(PHPWCMS_ROOT);
    @mkdir(PHPWCMS_ROOT . '/include/inc_lib/revision', 0775, true);
    @mkdir(PHPWCMS_ROOT . '/template/inc_default', 0775, true);
    file_put_contents(PHPWCMS_ROOT . '/phpwcms.php', 'PHPWCMS-OLD');
    file_put_contents(PHPWCMS_ROOT . '/include/inc_lib/revision/revision.php', "<?php\nconst PHPWCMS_VERSION = '2.0.0';\n");
    file_put_contents(PHPWCMS_ROOT . '/template/inc_default/startup.php', 'TPL-OLD');
}

/**
 * Build a docroot-rooted release zip: given files + a staged revision.php with
 * $version, plus a sha256 .update-manifest (same layout the engine expects).
 */
function buildReleaseZip(string $zipPath, array $files, string $version): void
{
    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new RuntimeException('cannot create zip ' . $zipPath);
    }
    $manifest = [];
    foreach ($files as $rel => $content) {
        $zip->addFromString($rel, $content);
        $manifest[$rel] = hash('sha256', $content);
    }
    $rev = "<?php\nconst PHPWCMS_VERSION = '" . $version . "';\n";
    $zip->addFromString('include/inc_lib/revision/revision.php', $rev);
    $manifest['include/inc_lib/revision/revision.php'] = hash('sha256', $rev);
    $lines = [];
    foreach ($manifest as $rel => $h) {
        $lines[] = $h . '  ' . $rel;
    }
    $zip->addFromString('.update-manifest', implode("\n", $lines) . "\n");
    $zip->close();
}

/** Newest timestamped backup dir under content/backup/, trailing slash. */
function newestBackupDir(): string
{
    $dirs = glob(PHPWCMS_ROOT . '/content/backup/*', GLOB_ONLYDIR);
    if (!$dirs) {
        return '';
    }
    usort($dirs, fn($a, $b) => filemtime($b) <=> filemtime($a));
    return $dirs[0] . '/';
}

/** Extract a named '## SECTION' body from a change report. */
function reportSection(string $report, string $name): string
{
    if (preg_match('/## ' . $name . '\s*\n(.*?)(?=\n## |$)/s', $report, $m)) {
        return trim($m[1]);
    }
    return '';
}

// --- Scenario 1: fresh install ---
resetDocroot();
$zip1 = $base . '/rel-1.zip';
buildReleaseZip($zip1, [
    'phpwcms.php' => 'PHPWCMS-NEW',
    'template/inc_default/startup.php' => 'TPL-RELEASE',
    'include/inc_lib/newfile.php' => 'NEWFILE',
], '9.9.9');

$u = new phpwcms_update(1, $zip1);
$res = $u->run('v9.9.9');
assert($res['success'] === true, 'S1: fresh install succeeds: ' . ($res['error'] ?? ''));
assert(file_get_contents(PHPWCMS_ROOT . '/phpwcms.php') === 'PHPWCMS-NEW', 'S1: phpwcms.php updated');
assert(strpos(file_get_contents(PHPWCMS_ROOT . '/include/inc_lib/revision/revision.php'), '9.9.9') !== false, 'S1: revision bumped');
assert(is_file(PHPWCMS_ROOT . '/include/inc_lib/newfile.php'), 'S1: added file present');
assert(is_file(PHPWCMS_ROOT . '/.update-manifest'), 'S1: new manifest written');
$backup1 = newestBackupDir();
assert($backup1 !== '', 'S1: backup dir created');
assert(is_file($backup1 . 'database.sql.gz'), 'S1: db dump in backup');
assert(is_file($backup1 . 'files/phpwcms.php'), 'S1: modified file mirrored in backup');
assert(is_file($backup1 . 'files/template/inc_default/startup.php'), 'S1: template mirrored in backup');
$report1 = file_get_contents($backup1 . 'changed-files.txt');
assert(strpos($report1, '## ADDED') !== false, 'S1: report has ADDED');
assert(strpos($report1, '## MODIFIED') !== false, 'S1: report has MODIFIED');
assert(reportSection($report1, 'DELETED') === '', 'S1: no deletions on fresh install');

// --- Scenario 2: second run, one file removed + one modified ---
$zip2 = $base . '/rel-2.zip';
buildReleaseZip($zip2, [
    'phpwcms.php' => 'PHPWCMS-NEW2',
    'template/inc_default/startup.php' => 'TPL-RELEASE',
], '9.9.9'); // include/inc_lib/newfile.php dropped

$u = new phpwcms_update(1, $zip2);
$res = $u->run('v9.9.9');
assert($res['success'] === true, 'S2: second run succeeds: ' . ($res['error'] ?? ''));
assert(!file_exists(PHPWCMS_ROOT . '/include/inc_lib/newfile.php'), 'S2: removed file deleted from docroot');
assert(file_get_contents(PHPWCMS_ROOT . '/phpwcms.php') === 'PHPWCMS-NEW2', 'S2: modified file applied');
$backup2 = newestBackupDir();
assert(is_file($backup2 . 'files/include/inc_lib/newfile.php'), 'S2: deleted file backed up');
$report2 = file_get_contents($backup2 . 'changed-files.txt');
assert(strpos(reportSection($report2, 'DELETED'), 'include/inc_lib/newfile.php') !== false, 'S2: report lists deletion');

// --- Scenario 3: template/ file locally modified ---
file_put_contents(PHPWCMS_ROOT . '/template/inc_default/startup.php', 'TPL-LOCAL');
$u = new phpwcms_update(1, $zip2); // zip2 still ships TPL-RELEASE
$res = $u->run('v9.9.9');
assert($res['success'] === true, 'S3: run succeeds: ' . ($res['error'] ?? ''));
assert(file_get_contents(PHPWCMS_ROOT . '/template/inc_default/startup.php') === 'TPL-RELEASE', 'S3: local template overwritten by release');
$backup3 = newestBackupDir();
assert(file_get_contents($backup3 . 'files/template/inc_default/startup.php') === 'TPL-LOCAL', 'S3: old local version backed up');
$report3 = file_get_contents($backup3 . 'changed-files.txt');
assert(strpos(reportSection($report3, 'MODIFIED'), 'template/inc_default/startup.php') !== false, 'S3: report lists MODIFIED template');

// --- Scenario 4: DB dump failure aborts before download, no log row ---
resetDocroot();
$zip4 = $base . '/rel-4.zip';
buildReleaseZip($zip4, ['phpwcms.php' => 'PHPWCMS-NEW'], '9.9.9');
@mkdir(PHPWCMS_ROOT . '/content/backup', 0775, true);
@chmod(PHPWCMS_ROOT . '/content/backup', 0555);
if (is_writable(PHPWCMS_ROOT . '/content/backup')) {
    // chmod ineffective (e.g. running as root) — force DB dump failure instead.
    $GLOBALS['forceDbDumpFail'] = true;
}
$GLOBALS['logInserts'] = 0;
$u = new phpwcms_update(1, $zip4);
$res = $u->run('v9.9.9');
assert($res['success'] === false, 'S4: run must fail');
assert(strpos($res['error'], 'backup') !== false, 'S4: error mentions backup: ' . $res['error']);
assert($GLOBALS['logInserts'] === 0, 'S4: no log row inserted before abort');
@chmod(PHPWCMS_ROOT . '/content/backup', 0755);
$GLOBALS['forceDbDumpFail'] = false;

// --- Scenario 5: maintenance-flag safety net ---
$flag = PHPWCMS_TEMP . 'update/maintenance.flag';
@mkdir(dirname($flag), 0775, true);
@touch($flag);
clearstatcache();
$active = phpwcms_update::maintenanceActive();
assert($active === true, 'S5: fresh flag active');
@touch($flag, time() - 16 * 60);
clearstatcache();
$active = phpwcms_update::maintenanceActive();
assert($active === false, 'S5: stale flag inactive');
@unlink($flag);

// --- cleanup ---
rrmdir($base);

echo 'OK' . PHP_EOL;
