<?php
// tests/update/test-backup.php — run: php8 tests/update/test-backup.php
define('PHPWCMS_INCLUDE_CHECK', true);

$tmp = sys_get_temp_dir() . '/phpwcms-update-test-' . getmypid();
@mkdir($tmp, 0775, true);

// stub DB layer (query-aware, ASSOC-shaped rows like the real _dbQuery)
function _dbQuery($q, $type = '') {
    if (str_starts_with($q, 'SHOW TABLES')) {
        return [['Tables_in_db' => 'phpwcms_user']];
    }
    if (str_starts_with($q, 'SHOW CREATE TABLE')) {
        return [['Table' => 'phpwcms_user', 'Create Table' => 'CREATE TABLE `phpwcms_user` (`id` int(11) NOT NULL)']];
    }
    if (str_starts_with($q, 'SELECT * FROM')) {
        return [['id' => 1, 'name' => 'admin']];
    }
    return [];
}
define('DB_PREPEND', '');
function _dbCount($t) { return 1; }
// (real _dbQuery returns data rows; db-dump test uses a stubbed query-result shape)
// For the unit test we exercise file backup + change report only:
define('PHPWCMS_ROOT', $tmp . '/orig');
define('LF', "\n"); // normally defined by include/inc_lib/default.inc.php

require_once __DIR__ . '/../../include/inc_lib/update/update.backup.php';

// create a fake docroot file
@mkdir($tmp . '/orig/inc', 0775, true);
file_put_contents($tmp . '/orig/inc/a.php', 'OLD');
$ok = phpwcms_update_backup_files(['inc/a.php', 'inc/missing.php'], $tmp . '/backup');
assert($ok === false, 'missing file must fail the whole backup');
$ok = phpwcms_update_backup_files(['inc/missing.php'], $tmp . '/backup2');
assert($ok === false, 'missing file must fail backup');
assert(!is_dir($tmp . '/backup2/inc'), 'failed backup must not leave trusted mirror');
$ok = phpwcms_update_backup_files(['inc/a.php'], $tmp . '/backup');
assert($ok === true, 'existing file backs up');
assert(file_get_contents($tmp . '/backup/inc/a.php') === 'OLD');

// added file must exist for its sha256 to be computed
@mkdir($tmp . '/orig/new', 0775, true);
file_put_contents($tmp . '/orig/new/file.php', 'NEW');
phpwcms_update_change_report($tmp . '/backup', ['new/file.php'], ['inc/a.php'], ['old/gone.php']);
$report = file_get_contents($tmp . '/backup/changed-files.txt');
assert(strpos($report, 'ADDED') !== false && strpos($report, 'inc/a.php') !== false);

// db-dump coverage (stubbed _dbQuery returns ASSOC-shaped rows)
$dump = $tmp . '/dump.sql.gz';
$ok = phpwcms_update_db_dump($dump);
assert($ok === true, 'db dump succeeds');
$sql = gzdecode((string)file_get_contents($dump));
assert(strpos($sql, 'DROP TABLE IF EXISTS `phpwcms_user`') !== false, 'dump has DROP TABLE');
assert(strpos($sql, 'CREATE TABLE `phpwcms_user`') !== false, 'dump has CREATE TABLE');
assert(strpos($sql, 'INSERT INTO `phpwcms_user`') !== false, 'dump has INSERT INTO');

echo 'OK' . PHP_EOL;
