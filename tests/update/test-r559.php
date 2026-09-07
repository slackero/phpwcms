<?php
// tests/update/test-r559.php — standalone, run: php8 tests/update/test-r559.php
define('PHPWCMS_INCLUDE_CHECK', true);
require_once __DIR__ . '/../../include/inc_lib/revision/r559.php';

function _dbTableExists($t) { return in_array($t, ['update_log', 'phpwcms_update_log'], true); }
function _dbColumnExists($t, $c) { return in_array($t, ['update_log', 'phpwcms_update_log'], true); }
function _dbQuery($q, $type = '') { echo 'QUERY: ' . $q . PHP_EOL; return in_array($type, ['CREATE', 'ALTER'], true) ? true : []; }
function _dbCount($q) { return 0; }
function _dbInsert($t, $d) { return ['INSERT_ID' => 1]; }
function _dbGetCreateCharsetCollation() { return 'DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'; }
define('DB_PREPEND', '');

$result = phpwcms_revision_r559();
assert($result === true, 'r559 must return true');
echo 'OK' . PHP_EOL;
