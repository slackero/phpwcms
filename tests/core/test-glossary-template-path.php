<?php
// tests/core/test-glossary-template-path.php — run: php tests/core/test-glossary-template-path.php
// The Glossary renderer concatenated the stored glossary_template value below
// the module template directory and read it with file_get_contents(). A stored
// `../../../../index.php` therefore disclosed deployment files. Template names
// are now resolved and required to stay inside the template directory.

$root = dirname(__DIR__, 2);
$lib = file_get_contents($root . '/include/inc_lib/general.inc.php');

define('PHPWCMS_ROOT', $root);

$failures = 0;

function check($ok, $message)
{
    global $failures;
    if ($ok) {
        echo "OK   $message\n";
        return;
    }
    echo "FAIL $message\n";
    $failures++;
}

function extract_fn($source, $name)
{
    if (!preg_match('/^function ' . preg_quote($name, '/') . '\b.*?^}/ms', $source, $match)) {
        echo "FAIL cannot extract $name\n";
        exit(1);
    }

    eval($match[0]);
}

extract_fn($lib, 'path_is_within');
extract_fn($lib, 'phpwcms_template_file_path');

$fixture = $root . '/tests/.tmp-glossary-template-' . getmypid();
$outside = $root . '/tests/.tmp-glossary-outside-' . getmypid();

function cleanup_fixture($fixture, $outside)
{
    @unlink($fixture . '/escape.tmpl');
    @unlink($fixture . '/default/default.tmpl');
    @rmdir($fixture . '/default');
    @unlink($outside . '/outside.tmpl');
    @rmdir($fixture);
    @rmdir($outside);
}

@mkdir($fixture . '/default', 0777, true);
@mkdir($outside, 0777, true);
file_put_contents($fixture . '/default/default.tmpl', 'TEMPLATE');
file_put_contents($outside . '/outside.tmpl', 'OUTSIDE');
register_shutdown_function('cleanup_fixture', $fixture, $outside);

check(
    phpwcms_template_file_path($fixture, 'default/default.tmpl') === $fixture . '/default/default.tmpl',
    'nested template inside the directory resolves'
);
check(phpwcms_template_file_path($fixture, '../../../../index.php') === '', 'parent traversal is rejected');
check(phpwcms_template_file_path($fixture, '../.tmp-glossary-outside-' . getmypid() . '/outside.tmpl') === '', 'sibling escape is rejected');
check(phpwcms_template_file_path($fixture, $outside . '/outside.tmpl') === '', 'absolute path is rejected');
check(phpwcms_template_file_path($fixture, 'php://filter/resource=/etc/passwd') === '', 'stream wrapper is rejected');
check(phpwcms_template_file_path($fixture, '') === '', 'empty name is rejected');
check(phpwcms_template_file_path($fixture, 'missing.tmpl') === '', 'missing template is rejected');

if (@symlink($outside . '/outside.tmpl', $fixture . '/escape.tmpl') !== false) {
    check(phpwcms_template_file_path($fixture, 'escape.tmpl') === '', 'symlink leaving the directory is rejected');
}

$renderer = file_get_contents($root . '/include/inc_module/mod_glossary/inc/cnt.article.php');
check(strpos($renderer, 'phpwcms_template_file_path(') !== false, 'renderer resolves the stored template through the containment helper');
check(strpos($renderer, "'template/'.\$content['glossary']['glossary_template']") === false, 'renderer no longer concatenates the raw template name');

$post = file_get_contents($root . '/include/inc_module/mod_glossary/inc/cnt.post.php');
check(strpos($post, 'phpwcms_template_file_path(') !== false, 'editor keeps only a template that resolves inside the template directory');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
