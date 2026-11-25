<?php
// tests/core/test-tooltip-escaping.php — run: php tests/core/test-tooltip-escaping.php
// The private file tooltips build a single-quoted inline JavaScript string in
// an HTML attribute. HTML-encoding alone decodes the entity before JavaScript
// runs, so an apostrophe in the copyright broke out of the string.

$root = dirname(__DIR__, 2);
$lib = file_get_contents($root . '/include/inc_lib/general.inc.php');
$default = file_get_contents($root . '/include/inc_lib/default.inc.php');
$filelist = file_get_contents($root . '/include/inc_lib/files.private-filelist.inc.php');
$functions = file_get_contents($root . '/include/inc_lib/files.private-functions.inc.php');

define('PHPWCMS_ROOT', $root);
define('PHPWCMS_CHARSET', 'utf-8');

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
extract_fn($lib, 'js_inline_string');

$payload = "x');alert(1);//";
$escaped = js_inline_string($payload);
$decoded = html_entity_decode($escaped, ENT_QUOTES, PHPWCMS_CHARSET);

check(strpos($decoded, "\\'") !== false, 'quote is backslash escaped after the HTML attribute decode');
check(strpos($decoded, "x');") === false, 'raw quote cannot close the JavaScript string');

$entity = '&#39;);alert(1);//';
$decoded_entity = html_entity_decode(js_inline_string($entity), ENT_QUOTES, PHPWCMS_CHARSET);
check(strpos($decoded_entity, "'") === false, 'literal entity cannot become a JavaScript quote');
check(strpos($decoded_entity, '&#39;') !== false, 'literal entity stays visible text');

$backslash = js_inline_string("a\\'b");
$decoded_backslash = html_entity_decode($backslash, ENT_QUOTES, PHPWCMS_CHARSET);
check(strpos($decoded_backslash, "\\\\\\'") !== false, 'backslash is escaped before the quote');

check(js_inline_string('<b>Name</b> & Co') === '&lt;b&gt;Name&lt;/b&gt; &amp; Co', 'markup and ampersand stay HTML encoded');

check(
    strpos($filelist, 'js_inline_string($file_row["f_copyright"])') !== false
    && strpos($filelist, 'js_inline_string($file_row["f_name"])') !== false,
    'private file list escapes the inline tooltip values'
);
check(
    strpos($functions, 'js_inline_string($file_row["f_copyright"])') !== false
    && strpos($functions, 'js_inline_string($file_row["f_name"])') !== false,
    'file center list escapes the inline tooltip values'
);

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
