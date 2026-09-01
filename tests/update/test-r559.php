<?php
// tests/update/test-r559.php — standalone, run: php8 tests/update/test-r559.php
define('PHPWCMS_INCLUDE_CHECK', true);
require_once __DIR__ . '/../../include/inc_lib/revision/r559.php';

function _dbTableExists($t) { return $t === 'phpwcms_update_log'; }
function _dbColumnExists($t, $c) { return $t === 'phpwcms_update_log'; }
function _dbQuery($q, $type = '') { echo 'QUERY: ' . $q . PHP_EOL; return true; }
define('DB_PREPEND', '');

$result = phpwcms_revision_r559();
assert($result === true, 'r559 must return true');
echo 'OK' . PHP_EOL;
