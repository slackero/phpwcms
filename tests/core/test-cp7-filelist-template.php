<?php
// tests/core/test-cp7-filelist-template.php — run: php7.4 tests/core/test-cp7-filelist-template.php
// Content Part 7 concatenated the stored `acontent_template` selector below
// template/inc_cntpart/filelist and read it with file_get_contents(). A
// `[download=1 template=../../...]` tag in an article body therefore disclosed
// any file readable by the web process. The selector is now resolved through
// phpwcms_template_file_path() and must stay inside the filelist directory.

$root = dirname(__DIR__, 2);
$lib = file_get_contents($root . '/include/inc_lib/general.inc.php');

define('PHPWCMS_ROOT', $root);

$failures = 0;
require_once __DIR__ . '/lib/source-fixture.php';

extract_fn($lib, 'path_is_within');
extract_fn($lib, 'phpwcms_template_file_path');

$fixture = $root . '/tests/.tmp-cp7-filelist-' . getmypid();
$outside = $root . '/tests/.tmp-cp7-filelist-outside-' . getmypid();

function cleanup_fixture(string $fixture, string $outside): void
{
    @unlink($fixture . '/list.tmpl');
    @unlink($fixture . '/default/default.tmpl');
    @rmdir($fixture . '/default');
    @unlink($outside . '/outside.tmpl');
    @rmdir($fixture);
    @rmdir($outside);
}

@mkdir($fixture . '/default', 0777, true);
@mkdir($outside, 0777, true);
file_put_contents($fixture . '/list.tmpl', 'TEMPLATE');
file_put_contents($fixture . '/default/default.tmpl', 'NESTED');
file_put_contents($outside . '/outside.tmpl', 'OUTSIDE');
register_shutdown_function('cleanup_fixture', $fixture, $outside);

check(
    phpwcms_template_file_path($fixture, 'list.tmpl') === $fixture . '/list.tmpl',
    'valid filelist template resolves'
);
check(
    phpwcms_template_file_path($fixture, 'default/default.tmpl') === $fixture . '/default/default.tmpl',
    'nested template inside the directory resolves'
);
check(
    phpwcms_template_file_path($fixture, '../../../include/config/conf.template_default.inc.php') === '',
    'parent traversal is rejected'
);
check(
    phpwcms_template_file_path($fixture, '../.tmp-cp7-filelist-outside-' . getmypid() . '/outside.tmpl') === '',
    'sibling escape is rejected'
);
check(phpwcms_template_file_path($fixture, $outside . '/outside.tmpl') === '', 'absolute path is rejected');
check(phpwcms_template_file_path($fixture, '/etc/passwd') === '', 'root absolute path is rejected');
check(phpwcms_template_file_path($fixture, "list.tmpl\0.php") === '', 'NUL byte is rejected');
check(phpwcms_template_file_path($fixture, 'missing.tmpl') === '', 'missing template is rejected');

$renderer = file_get_contents($root . '/include/inc_front/content/cnt7.article.inc.php');
check(
    strpos($renderer, "phpwcms_template_file_path(PHPWCMS_TEMPLATE . 'inc_cntpart/filelist'") !== false,
    'renderer resolves the stored template through the containment helper'
);
check(
    strpos($renderer, 'is_file(PHPWCMS_TEMPLATE . \'inc_cntpart/filelist/\' . $crow["acontent_template"])') === false,
    'renderer no longer reads the raw selector path'
);

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
