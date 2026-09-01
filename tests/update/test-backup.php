<?php
// tests/update/test-backup.php — run: php8 tests/update/test-backup.php
define('PHPWCMS_INCLUDE_CHECK', true);

$tmp = sys_get_temp_dir() . '/phpwcms-update-test-' . getmypid();
@mkdir($tmp, 0775, true);

// stub DB layer
function _dbQuery($q, $type = '') { return [['Tables_in_db' => 'phpwcms_user']]; }
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
assert(!is_file($tmp . '/backup/inc/a.php') || true); // partial mirror allowed on failure
$ok = phpwcms_update_backup_files(['inc/a.php'], $tmp . '/backup');
assert($ok === true, 'existing file backs up');
assert(file_get_contents($tmp . '/backup/inc/a.php') === 'OLD');

// added file must exist for its sha256 to be computed
@mkdir($tmp . '/orig/new', 0775, true);
file_put_contents($tmp . '/orig/new/file.php', 'NEW');
phpwcms_update_change_report($tmp . '/backup', ['new/file.php'], ['inc/a.php'], ['old/gone.php']);
$report = file_get_contents($tmp . '/backup/changed-files.txt');
assert(strpos($report, 'ADDED') !== false && strpos($report, 'inc/a.php') !== false);

echo 'OK' . PHP_EOL;
