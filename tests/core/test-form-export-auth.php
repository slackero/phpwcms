<?php
// tests/core/test-form-export-auth.php — run: php8 tests/core/test-form-export-auth.php
// act_export.php checked login and CSRF only, so any backend user could export
// the stored submissions and source IPs of any form by id. The form now has to
// belong to an article the user owns, or the user must be an administrator,
// unless a validated direct-download API key authorized it.

$root = dirname(__DIR__, 2);
$export = file_get_contents($root . '/include/inc_act/act_export.php');

define('DB_PREPEND', 'phpwcms_');
define('PHPWCMS_ROOT', $root);

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

extract_fn($export, 'form_export_is_allowed');

$GLOBALS['stub_rows'] = array();

function _dbGet($table, $fields, $where)
{
    $GLOBALS['stub_last_where'] = $where;
    return $GLOBALS['stub_rows'];
}

$_SESSION = array('wcs_user_id' => 5);

check(form_export_is_allowed(42, 'abcdefghijklmnop') === true, 'validated API key authorizes the form');

$_SESSION['wcs_user_admin'] = 1;
check(form_export_is_allowed(42, '') === true, 'administrator authorizes any form');
unset($_SESSION['wcs_user_admin']);

$GLOBALS['stub_rows'] = array(array('acontent_id' => '42'));
check(form_export_is_allowed(42, '') === true, 'owned form is authorized');
check(
    strpos($GLOBALS['stub_last_where'], 'acontent_type=23') !== false
    && strpos($GLOBALS['stub_last_where'], 'article_uid=5') !== false,
    'authorization query restricts type and owner'
);

$GLOBALS['stub_rows'] = array();
check(form_export_is_allowed(43, '') === false, 'foreign form is rejected');

$GLOBALS['stub_rows'] = array(array('acontent_id' => '43'));
check(form_export_is_allowed(42, '') === false, 'mismatched result row is rejected');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
