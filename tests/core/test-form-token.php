<?php
// tests/core/test-form-token.php — run: php8 tests/core/test-form-token.php
// Form uploads live outside the document root; links mailed to a user must
// resolve only through a signed token and never through a path.

$root = dirname(__DIR__, 2);
$general = file_get_contents($root . '/include/inc_lib/general.inc.php');
$default = file_get_contents($root . '/include/inc_lib/default.inc.php');
$failures = 0;

function check(bool $ok, string $message): void
{
    global $failures;
    echo ($ok ? 'OK   ' : 'FAIL ') . $message . "\n";
    if (!$ok) {
        $failures++;
    }
}

function extract_fn(string $source, string $name): void
{
    if (!preg_match('/^function ' . preg_quote($name, '/') . '\b.*?^}/ms', $source, $match)) {
        fwrite(STDERR, "FAIL cannot extract $name\n");
        exit(1);
    }
    eval($match[0]);
}

foreach (['phpwcms_encrypt', 'phpwcms_decrypt'] as $name) {
    extract_fn($default, $name);
}
foreach (['phpwcms_form_upload_dir', 'phpwcms_form_upload_token', 'phpwcms_form_upload_url', 'phpwcms_form_upload_target'] as $name) {
    extract_fn($general, $name);
}

$form_root = sys_get_temp_dir() . '/phpwcms-form-' . bin2hex(random_bytes(4));
mkdir($form_root . '/invoices', 0700, true);
file_put_contents($form_root . '/invoices/1700000000_ab12cd34_invoice.pdf', 'pdf');
define('PHPWCMS_FORM', $form_root . '/');
define('PHPWCMS_FORM_TOKEN_KEY', 'test-form-token-key');
define('PHPWCMS_URL', 'https://example.test/');

$rel = 'invoices/1700000000_ab12cd34_invoice.pdf';
$token = phpwcms_form_upload_token($rel);

check($token !== '' && !str_contains($token, '=') && !str_contains($token, '+') && !str_contains($token, '/'), 'token is url-safe');
check(phpwcms_form_upload_target($token) === realpath($form_root . '/' . $rel), 'roundtrip resolves the stored file');
$url = phpwcms_form_upload_url($rel);
parse_str((string)parse_url($url, PHP_URL_QUERY), $query);
check(str_starts_with($url, 'https://example.test/formfile.php?t='), 'url uses the controller');
check(!empty($query['t']) && phpwcms_form_upload_target($query['t']) === realpath($form_root . '/' . $rel), 'url token resolves the stored file');

check(phpwcms_form_upload_target(phpwcms_form_upload_token('../config/conf.inc.php')) === false, 'traversal rejected');
check(phpwcms_form_upload_target(phpwcms_form_upload_token('/etc/passwd')) === false, 'absolute rejected');
check(phpwcms_form_upload_target(phpwcms_form_upload_token('invoices/missing.pdf')) === false, 'missing file rejected');
check(phpwcms_form_upload_target(substr($token, 0, -2) . 'xx') === false, 'edited token rejected');
check(phpwcms_form_upload_target('not-a-token') === false, 'garbage rejected');
check(phpwcms_form_upload_target('') === false, 'empty token rejected');

@unlink($form_root . '/invoices/1700000000_ab12cd34_invoice.pdf');
@rmdir($form_root . '/invoices');
@rmdir($form_root);

exit($failures === 0 ? 0 : 1);
