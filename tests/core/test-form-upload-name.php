<?php
// tests/core/test-form-upload-name.php — run: php8 tests/core/test-form-upload-name.php
// The public Form upload must validate the extension of the name it actually
// stores. which_ext('marker.php.') is empty while sanitize_filename() trims
// the trailing dot and stores a .php file, so checking the raw name lets a
// dangerous extension through.

$general = file_get_contents(__DIR__ . '/../../include/inc_lib/general.inc.php');
$cnt23 = file_get_contents(__DIR__ . '/../../include/inc_front/content/cnt23.article.inc.php');
$failures = 0;

define('PHPWCMS_ROOT', dirname(__DIR__, 2));

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

foreach (['which_ext', 'sanitize_filename', 'form_upload_filename', 'form_upload_extension_lists', 'form_upload_executable_extensions', 'form_upload_disallowed_extensions', 'form_upload_extension_disallowed', 'upload_stored_name'] as $name) {
    extract_fn($general, $name);
}

check(form_upload_extension_disallowed(form_upload_filename('marker.php.')) === true, 'marker.php. is denied after cleaning');
check(form_upload_extension_disallowed(form_upload_filename('x.PHP.')) === true, 'x.PHP. is denied after cleaning');
check(form_upload_extension_disallowed(form_upload_filename('x.phtml')) === true, 'x.phtml is denied');
check(form_upload_extension_disallowed(form_upload_filename('notes.php.5')) === true, 'notes.php.5 is denied (multi-extension)');
check(form_upload_extension_disallowed(form_upload_filename('evil.shtml.foo')) === true, 'evil.shtml.foo is denied (multi-extension)');
check(form_upload_extension_disallowed(form_upload_filename('xss.svg')) === true, 'xss.svg is denied (browser-executed)');
check(form_upload_extension_disallowed(form_upload_filename('page.html')) === true, 'page.html is denied (browser-executed)');
check(form_upload_extension_disallowed(form_upload_filename('photo.jpg.')) === false, 'photo.jpg. stays allowed');
check(form_upload_extension_disallowed(form_upload_filename('php.jpg')) === false, 'php.jpg basename stays allowed');
check(form_upload_extension_disallowed(form_upload_filename('README')) === false, 'extensionless name stays allowed');

check(preg_match_all('/^[0-9]+_[0-9a-f]{8}_marker\.php$/m', form_upload_filename('marker.php.')) === 1, 'stored name carries a random component');

[, , $stored] = upload_stored_name('notes.php.5');
check($stored === 'notes_php.5', 'backend upload stores notes.php.5 as notes_php.5');
[, , $stored] = upload_stored_name('x.PHTML.jpg');
check($stored === 'x_PHTML.jpg', 'backend upload joins an embedded handler extension');
[, $ext, $stored] = upload_stored_name('photo.jpg.');
check($ext === 'jpg' && $stored === 'photo.jpg', 'backend upload drops a trailing dot before the extension check');
[, , $stored] = upload_stored_name('page.html');
check($stored === 'page.html', 'backend upload keeps plain web formats such as .html');
[, , $stored] = upload_stored_name('data.de.json');
check($stored === 'data.de.json', 'backend upload keeps a harmless embedded dot');
[, , $stored] = upload_stored_name('marker.php');
check($stored === 'marker.php', 'backend upload leaves the final extension to the allowlist');
[, $ext, $stored] = upload_stored_name('.htaccess');
check($ext === '' && $stored === 'htaccess', 'backend upload drops a leading dot and leaves the name extensionless');

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

$executable = form_upload_executable_extensions();
check(in_array('php', $executable, true) && in_array('cgi', $executable, true), 'server-executed extensions are hard-coded');
check(
    !in_array('html', $executable, true) && !in_array('svg', $executable, true) && !in_array('js', $executable, true),
    'hard-coded list leaves web formats to the allowlist'
);

foreach ([
    'saveUploadedFile' => $general,
    'private upload' => file_get_contents(__DIR__ . '/../../include/inc_tmpl/files.private.upload.tmpl.php'),
] as $path => $source) {
    check(
        preg_match('/in_array\(.*?,\s*form_upload_executable_extensions\(\),\s*true\)/', $source) === 1,
        $path . ' rejects server-executed extensions without consulting configuration'
    );
}

$shared = form_upload_disallowed_extensions();

check(array_diff($executable, $shared) === [], 'hard-coded extensions are part of the shared list');
check(
    preg_match('/\$cnt_form\[[\'"]upload_value[\'"]]\[[\'"]exclude[\'"]\]\s*=\s*implode\(\s*[\'"],[\'"]\s*,\s*form_upload_disallowed_extensions\(\)\s*\)/', $cnt23) === 1,
    'Form upload default exclude list is derived from the shared list'
);

$r564 = file_get_contents(__DIR__ . '/../../include/inc_lib/revision/r564.php');
check(
    preg_match('/\$disallowed_ext\s*=\s*\(require \$extensions_file\)\[\'disallowed\'\]/', $r564) === 1
    && preg_match('/if \(!is_file\(\$extensions_file\)\)/', $r564) === 1,
    'Revision r564 reads the shared extension list and guards a missing file'
);

$lists = require PHPWCMS_ROOT . '/include/inc_lib/upload-extensions.inc.php';
check($lists['disallowed'] === $shared, 'Shared extension file matches the helper list');
check($lists['executable'] === $executable, 'Shared executable list matches the helper list');

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
