<?php
// tests/core/test-layout-table.php — run: php8 tests/core/test-layout-table.php
// Guards the table based page layout (layout_render = 0). The start tag is built
// from three statements inside content.func.inc.php; a port accident once moved
// them into a trailing line comment, so the tag stayed unclosed ("<table <tr>").
// The case 0 block is extracted and executed here instead of bootstrapping the
// whole frontend. Extraction assumes each helper ends with a closing brace at
// column 0.

if (!defined('LF')) {
    define('LF', "\n");
}

function extract_fn_source(string $source, string $name): string
{
    if (!preg_match('/^function ' . preg_quote($name, '/') . '\b.*?^}/ms', $source, $m)) {
        fwrite(STDERR, "cannot extract $name\n");
        exit(1);
    }

    return $m[0];
}

function extract_fn(string $source, string $name): string
{
    $code = extract_fn_source($source, $name);
    eval($code);
    if (!function_exists($name)) {
        fwrite(STDERR, "extraction of $name produced no function\n");
        exit(1);
    }

    return $code;
}

$front_source = file_get_contents(__DIR__ . '/../../include/inc_front/front.func.inc.php');
$general_source = file_get_contents(__DIR__ . '/../../include/inc_lib/general.inc.php');
$content_source = file_get_contents(__DIR__ . '/../../include/inc_front/content.func.inc.php');

foreach (['add_attribute', 'html_height_attribute', 'td_attributes', 'align_base_layout'] as $name) {
    extract_fn($front_source, $name);
}
extract_fn($general_source, 'str_empty');

// the table layout start tag statements as they run in production
if (!preg_match('/case 0:.*?\/\/header/s', $content_source, $case)) {
    fwrite(STDERR, "cannot locate the layout_render = 0 block in content.func.inc.php\n");
    exit(1);
}
preg_match_all('/^\s*\$content\s*\[\s*[\'"]all[\'"]\s*\][^\n]*$/m', $case[0], $matches);
$statements = array_map('trim', $matches[0]);

$render = static function (array $pagelayout) use ($statements): string {
    $code = "return static function (\$pagelayout) {\n\$content = [];\n"
        . implode("\n", $statements)
        . "\nreturn \$content['all'];\n};";
    $builder = eval($code);

    return $builder($pagelayout);
};

$cases = [
    'align left' => [
        $render(['layout_align' => 0]),
        '<table border="0" cellspacing="0" cellpadding="0" summary="">' . LF,
    ],
    'align center' => [
        $render(['layout_align' => 1]),
        '<table border="0" cellspacing="0" cellpadding="0" align="center" summary="">' . LF,
    ],
    'align right' => [
        $render(['layout_align' => 2]),
        '<table border="0" cellspacing="0" cellpadding="0" align="right" summary="">' . LF,
    ],
    'layout attributes kept' => [
        $render(['layout_align' => 0, 'layout_all_bgcolor' => '#ffffff', 'layout_all_class' => 'wrapper']),
        '<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="wrapper" summary="">' . LF,
    ],
];

$failed = 0;

// all three statements must run; a statement swallowed by a comment leaves
// the start tag incomplete
if (count($statements) !== 3) {
    $failed++;
    echo 'FAIL start tag statements: expected 3, found ' . count($statements) . "\n";
} else {
    echo "ok   start tag statements\n";
}

foreach ($cases as $label => [$actual, $expected]) {
    if ($actual !== $expected) {
        $failed++;
        echo "FAIL $label: expected " . var_export($expected, true) . ', got ' . var_export($actual, true) . "\n";
    } else {
        echo "ok   $label\n";
    }
}

// the tag must never end inside the opening angle bracket
foreach ($cases as $label => [$actual]) {
    if (!preg_match('/^<table [^<>]*summary="">' . preg_quote(LF, '/') . '$/', $actual)) {
        $failed++;
        echo "FAIL $label: start tag is not closed\n";
    }
}

if ($failed > 0) {
    echo "$failed test(s) failed\n";
    exit(1);
}

echo "all tests passed\n";
