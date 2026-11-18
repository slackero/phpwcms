<?php
// tests/core/test-form-upload-name.php — run: php8 tests/core/test-form-upload-name.php
// The public Form upload must validate the extension of the name it actually
// stores. which_ext('marker.php.') is empty while sanitize_filename() trims
// the trailing dot and stores a .php file, so checking the raw name lets a
// dangerous extension through.

$general = file_get_contents(__DIR__ . '/../../include/inc_lib/general.inc.php');
$cnt23 = file_get_contents(__DIR__ . '/../../include/inc_front/content/cnt23.article.inc.php');
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

foreach (['which_ext', 'sanitize_filename', 'form_upload_filename', 'form_upload_extension_disallowed'] as $name) {
    extract_fn($general, $name);
}

check(form_upload_extension_disallowed(form_upload_filename('marker.php.')) === true, 'marker.php. is denied after cleaning');
check(form_upload_extension_disallowed(form_upload_filename('x.PHP.')) === true, 'x.PHP. is denied after cleaning');
check(form_upload_extension_disallowed(form_upload_filename('x.phtml')) === true, 'x.phtml is denied');
check(form_upload_extension_disallowed(form_upload_filename('photo.jpg.')) === false, 'photo.jpg. stays allowed');
check(form_upload_extension_disallowed(form_upload_filename('README')) === false, 'extensionless name stays allowed');

check(preg_match_all('/^[0-9]+_[0-9a-f]{8}_marker\.php$/m', form_upload_filename('marker.php.')) === 1, 'stored name carries a random component');

check(
    preg_match('/\$cnt_form\[[\'"]upload_value[\'"]]\[[\'"]filename[\'"]\]\s*=\s*form_upload_filename\(/', $cnt23) === 1,
    'Form upload builds its stored name with form_upload_filename()'
);
check(
    preg_match('/\$is_disallowed\s*=\s*form_upload_extension_disallowed\(\$cnt_form\[[\'"]upload_value[\'"]]\[[\'"]filename[\'"]\]\)/', $cnt23) === 1,
    'extension check reads the stored filename'
);

$regexp_checks = preg_match_all(
    '/preg_match\(\$cnt_form\[[\'"]upload_value[\'"]\]\[[\'"]regexp[\'"]\], strtolower\(([^)]*)\)\)/',
    $cnt23,
    $matches
);
check($regexp_checks === 2, 'both accept/exclude regexps are checked (found ' . (int)$regexp_checks . ')');

if (!empty($matches[1])) {
    foreach ($matches[1] as $argument) {
        $argument = trim($argument);
        check(
            preg_match('/^\$cnt_form\[[\'"]upload_value[\'"]]\[[\'"]filename[\'"]\]$/', $argument) === 1,
            'accept/exclude regexp uses the stored filename, not "' . $argument . '"'
        );
    }
}

exit($failures === 0 ? 0 : 1);
