<?php
// tests/update/test-r562.php — standalone, run: php8 tests/update/test-r562.php
define('PHPWCMS_INCLUDE_CHECK', true);
define('DB_PREPEND', '');

$indexTemplate = 0;
$templates = [];
$updates = [];
$warnings = [];

function _dbQuery($query, $type = '')
{
    global $indexTemplate, $templates, $updates;

    if (str_starts_with($query, 'SELECT acat_id FROM ')) {
        return $indexTemplate === null ? [] : [['acat_id' => '0']];
    }
    if (str_starts_with($query, 'SELECT acat_template FROM ')) {
        return $indexTemplate === null ? [] : [['acat_template' => (string)$indexTemplate]];
    }
    if (str_starts_with($query, 'SELECT template_id FROM ')) {
        return array_key_exists($indexTemplate, $templates) ? [['template_id' => (string)$indexTemplate]] : [];
    }
    if (str_starts_with($query, 'UPDATE ')) {
        $updates[] = $query;
        return ['AFFECTED_ROWS' => 1];
    }
    return [];
}

require_once __DIR__ . '/../../include/inc_lib/revision/r562.php';

set_error_handler(static function ($errno, $errstr) use (&$warnings) {
    $warnings[] = $errstr;
    return true;
});

$fail = static function (string $message) {
    fwrite(STDERR, 'FAIL: ' . $message . PHP_EOL);
    exit(1);
};

// 1. valid template: untouched
$indexTemplate = 12;
$templates = [12 => true];
$updates = [];
$warnings = [];
phpwcms_revision_r562() === true || $fail('valid template must pass');
$updates === [] || $fail('valid template must not be rewritten');
$warnings === [] || $fail('valid template must not warn');

// 2. template 0 means "use the default template"
$indexTemplate = 0;
$updates = [];
phpwcms_revision_r562() === true || $fail('template 0 must pass');
$updates === [] || $fail('template 0 must not be rewritten');

// 3. unknown template: reset to 0 and report
$indexTemplate = 99;
$templates = [12 => true];
$updates = [];
$warnings = [];
$GLOBALS['phpwcms']['revision_return'] = '';
phpwcms_revision_r562() === true || $fail('unknown template must be repaired');
count($updates) === 1 && str_contains($updates[0], 'acat_template=0') || $fail('unknown template must be reset');
str_contains($GLOBALS['phpwcms']['revision_return'], '99') || $fail('repair must be reported');
count($warnings) === 1 || $fail('repair must warn once');

// 4. no index page row yet: seed path, nothing to verify
$indexTemplate = null;
$updates = [];
phpwcms_revision_r562_verify_indexpage_template() === true || $fail('missing row must pass the verification');

restore_error_handler();

echo 'OK' . PHP_EOL;
