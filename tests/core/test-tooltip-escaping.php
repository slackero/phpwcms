<?php
// tests/core/test-tooltip-escaping.php — run: php8 tests/core/test-tooltip-escaping.php
// Private file tooltips render their title as HTML (sanitize: false). The
// copyright and name were escaped once, so &lt;img onerror=...&gt; reached the
// DOM attribute decoded and bootstrap inserted it as markup. User data must be
// double encoded.

$root = dirname(__DIR__, 2);
$default = file_get_contents($root . '/include/inc_lib/default.inc.php');
$filelist = file_get_contents($root . '/include/inc_lib/files.private-filelist.inc.php');
$functions = file_get_contents($root . '/include/inc_lib/files.private-functions.inc.php');

define('PHPWCMS_ROOT', $root);
define('PHPWCMS_CHARSET', 'utf-8');

require_once $root . '/include/vendor/autoload.php';

// default.inc.php imports voku\helper\UTF8, the eval below has no import scope
if (!class_exists('UTF8', false)) {
    class_alias('voku\helper\UTF8', 'UTF8');
}

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

extract_fn($default, 'html');

$payload = '&lt;img src=x onerror=alert(1)&gt;';

$single = html_entity_decode(html($payload, false), ENT_QUOTES | ENT_HTML5, PHPWCMS_CHARSET);
check(str_contains($single, '<img'), 'single encoding still produces markup after the attribute decode (the reported bug)');

$double = html_entity_decode(html($payload, true), ENT_QUOTES | ENT_HTML5, PHPWCMS_CHARSET);
check(!str_contains($double, '<'), 'double encoding leaves no markup after the attribute decode');
check($double === $payload, 'double encoded entity survives as visible text');

$plain = html_entity_decode(html('Photo (c) Jane & John', true), ENT_QUOTES | ENT_HTML5, PHPWCMS_CHARSET);
check($plain === 'Photo (c) Jane & John', 'ordinary text stays readable');

check(
    str_contains($filelist, 'html($file_row["f_copyright"], true)') &&
    str_contains($filelist, 'html($file_row["f_name"], true)'),
    'private file list double encodes name and copyright in the tooltip'
);
check(
    str_contains($functions, 'html($file_row["f_copyright"], true)') &&
    str_contains($functions, 'html($file_row["f_name"], true)'),
    'file center list double encodes name and copyright in the tooltip'
);

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
