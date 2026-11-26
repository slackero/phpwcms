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

// --- article list and structure list -------------------------------------

$admin = file_get_contents($root . '/include/inc_lib/admin.functions.inc.php');
check(substr_count($admin, 'html($info, true)') === 3, 'all three HTML tooltips double encode their markup');
check(str_contains($admin, 'html($article_content[\'acontent_title\'])') || str_contains($admin, 'html($article_content["acontent_title"])'), 'content-part title is encoded inside the tooltip');
check(str_contains($admin, 'nl2br(html($article_content["acontent_comment"]))'), 'content-part comment is encoded inside the tooltip');
check(!str_contains($admin, 'js_singlequote('), 'article list no longer uses the entity-based js_singlequote');
check(preg_match('/data-confirm="[^"]*html\(/', $admin) === 1, 'confirm messages are HTML encoded for the attribute');

// --- confirm and tooltip handling in JavaScript --------------------------

$js = file_get_contents($root . '/include/inc_js/phpwcms.js');
check(str_contains($js, 'function formatConfirmMessage('), 'confirm messages are escaped at render time');
check(str_contains($js, ".html(formatConfirmMessage(message))"), 'confirm modal uses the escaped message');
check(!str_contains($js, 'sanitize: false'), 'tooltip and popover sanitizing is enabled');
check(str_contains($js, 'sanitize: true'), 'tooltip and popover sanitizing is enabled');
check(str_contains($js, 'allowList: tooltipMarkupAllowList'), 'tooltips use the backend allowList');
check(str_contains($js, 'inlineMarkupAllowList'), 'allowList is defined');
check(str_contains($js, 'delete tooltipMarkupAllowList.a'), 'tooltips cannot render links');
check(str_contains($js, 'popoverMarkupAllowList'), 'popovers keep the link allowList');
check(!str_contains($js, 'replace(/\\\\n/g'), 'confirm messages only convert real newlines');


// --- escaping helpers ----------------------------------------------------

$lib = file_get_contents($root . '/include/inc_lib/general.inc.php');
extract_fn($lib, 'js_string');
extract_fn($lib, 'js_inline_string');
extract_fn($lib, 'js_tooltip_string');

check(js_string("</script><img src=x onerror=alert(1)>") === '\\x3C/script\\x3E\\x3Cimg src=x onerror=alert(1)\\x3E', 'script end tag is neutralised');
check(js_string("a'b\"c\\d") === "a\\'b\\\"c\\\\d", 'quotes and backslash are JavaScript escaped');

$payload = "x');alert(1);//";
$decoded = html_entity_decode(js_inline_string($payload), ENT_QUOTES | ENT_HTML5, PHPWCMS_CHARSET);
check(str_contains($decoded, "\\'"), 'attribute handler quote is backslash escaped after decode');
check(!str_contains($decoded, "x');"), 'attribute handler cannot be closed');

// --- remaining tooltip and structure escaping ---------------------------

check(strpos($admin, '$an = html($an);') !== false, 'listmode_edits escapes the category name it receives');
$structlist = file_get_contents($root . '/include/inc_tmpl/article.structlist.tmpl.php');
check(strpos($structlist, 'html($info, true)') !== false, 'the index tooltip in the article list double encodes its markup');
check(strpos($structlist, '. html($an) .') !== false, 'the index name is escaped before it is printed');
$adminstruct = file_get_contents($root . '/include/inc_tmpl/admin.structlist.tmpl.php');
check(strpos($adminstruct, 'js_tooltip_string($indexpage["acat_alias"])') !== false, 'the admin index tooltip escapes the alias for Tip()');

$tip = html_entity_decode(js_tooltip_string('<img src=x onerror=alert(1)>'), ENT_QUOTES, PHPWCMS_CHARSET);
check(strpos($tip, '&lt;img') !== false, 'Tip() receives markup as text');
check(strpos($tip, '<img') === false, 'Tip() cannot execute markup from a value');
check(strpos($js, "img: ['src'") === false, 'tooltips no longer load images from their content');

// --- repository-wide guard ----------------------------------------------

$offenders = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/include', FilesystemIterator::SKIP_DOTS));
foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php' || $file->getPathname() === $root . '/include/inc_lib/general.inc.php') {
        continue;
    }
    if (str_contains(file_get_contents($file->getPathname()), 'js_singlequote(')) {
        $offenders[] = substr($file->getPathname(), strlen($root) + 1);
    }
}
check($offenders === [], 'no output path uses js_singlequote(): ' . implode(', ', $offenders));

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
