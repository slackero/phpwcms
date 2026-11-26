<?php
// tests/core/test-tooltip-escaping.php — run: php8 tests/core/test-tooltip-escaping.php
// The private file tooltips build a single-quoted inline JavaScript string in
// an HTML attribute. HTML-encoding alone decodes the entity before JavaScript
// runs, so an apostrophe in the copyright broke out of the string.

$root = dirname(__DIR__, 2);
$lib = file_get_contents($root . '/include/inc_lib/general.inc.php');
$default = file_get_contents($root . '/include/inc_lib/default.inc.php');
$filelist = file_get_contents($root . '/include/inc_lib/files.private-filelist.inc.php');
$functions = file_get_contents($root . '/include/inc_lib/files.private-functions.inc.php');
$admin = file_get_contents($root . '/include/inc_lib/admin.functions.inc.php');

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
extract_fn($lib, 'js_string');
extract_fn($lib, 'js_inline_string');
extract_fn($lib, 'js_tooltip_string');

$payload = "x');alert(1);//";
$escaped = js_inline_string($payload);
$decoded = html_entity_decode($escaped, ENT_QUOTES | ENT_HTML5, PHPWCMS_CHARSET);

check(strpos($decoded, "\\'") !== false, 'quote is backslash escaped after the HTML attribute decode');
check(strpos($decoded, "x');") === false, 'raw quote cannot close the JavaScript string');

$entity = '&#39;);alert(1);//';
$decoded_entity = html_entity_decode(js_inline_string($entity), ENT_QUOTES | ENT_HTML5, PHPWCMS_CHARSET);
check(strpos($decoded_entity, "'") === false, 'literal entity cannot become a JavaScript quote');
check(strpos($decoded_entity, '&#39;') !== false, 'literal entity stays visible text');

$backslash = js_inline_string("a\\'b");
$decoded_backslash = html_entity_decode($backslash, ENT_QUOTES | ENT_HTML5, PHPWCMS_CHARSET);
check(strpos($decoded_backslash, "\\\\\\'") !== false, 'backslash is escaped before the quote');

$tip = html_entity_decode(js_tooltip_string('<img src=x onerror=alert(1)>'), ENT_QUOTES, PHPWCMS_CHARSET);
check(strpos($tip, '&lt;img') !== false, 'Tip() receives the markup as text, not as an element');
check(strpos($tip, '<img') === false, 'Tip() cannot execute markup from a value');

$tooltip_comment = js_inline_string(nl2br(html("a\nb")));
check(strpos($tooltip_comment, '\\x3Cbr') !== false && strpos($tooltip_comment, '\\n') !== false, 'tooltip comment keeps its line break after escaping');

check(js_inline_string('<b>Name</b> & Co') === '\\x3Cb\\x3EName\\x3C/b\\x3E &amp; Co', 'markup and ampersand stay HTML encoded');

check(
    strpos($filelist, 'js_tooltip_string($file_row["f_copyright"])') !== false
    && strpos($filelist, 'js_tooltip_string($file_row["f_name"])') !== false,
    'private file list escapes the inline tooltip values'
);
check(
    strpos($functions, 'js_tooltip_string($file_row["f_copyright"])') !== false
    && strpos($functions, 'js_tooltip_string($file_row["f_name"])') !== false,
    'file center list escapes the inline tooltip values'
);

// --- script blocks -------------------------------------------------------

check(js_string("</script><img src=x onerror=alert(1)>") === '\\x3C/script\\x3E\\x3Cimg src=x onerror=alert(1)\\x3E', 'a script end tag is neutralised for script blocks');
check(js_string("a'b\"c\\d") === "a\\'b\\\"c\\\\d", 'quotes and backslash are JavaScript escaped');
check(js_string("line\nbreak") === 'line\\nbreak', 'line break becomes a JavaScript escape');
check(js_string("\xE2\x80\xA8\xE2\x80\xA9") === '\\u2028\\u2029', 'U+2028 and U+2029 are escaped');

// --- article list sinks --------------------------------------------------

check(strpos($admin, 'js_singlequote(') === false, 'no inline handler still uses the entity-based js_singlequote');
check(strpos($admin, "js_tooltip_string(\$article_content['acontent_title'])") !== false, 'content-part title is escaped for the HTML tooltip');
check(strpos($admin, "js_tooltip_string(\$article_content['acontent_subtitle'])") !== false, 'content-part subtitle is escaped for the HTML tooltip');
check(strpos($admin, 'js_inline_string(nl2br(html($article_content["acontent_comment"])))') !== false, 'content-part comment keeps line breaks and renders as text');
check(strpos($admin, 'js_inline_string($article[$akey]["article_title"])') !== false, 'article delete confirm is JavaScript escaped');
check(strpos($admin, 'js_inline_string($an_raw)') !== false, 'structure delete confirm is JavaScript escaped');

// --- repository-wide guard ----------------------------------------------

$offenders = array();
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/include', FilesystemIterator::SKIP_DOTS));
foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php' || $file->getPathname() === $root . '/include/inc_lib/general.inc.php') {
        continue;
    }
    $source = file_get_contents($file->getPathname());
    if (strpos($source, 'js_singlequote(') !== false) {
        $offenders[] = substr($file->getPathname(), strlen($root) + 1);
    }
}
check($offenders === array(), 'no output path uses js_singlequote(): ' . implode(', ', $offenders));

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
