<?php
// tests/core/test-cnt21-url-scheme.php — run: php8 tests/core/test-cnt21-url-scheme.php
// Content part 21 accepted file:// (and other local wrappers) as a page URL and
// include_url() fetched it with file_get_contents(). Both now require HTTP(S).

$root = dirname(__DIR__, 2);
$readform = file_get_contents($root . '/include/inc_lib/content/cnt21.readform.inc.php');
$frontend = file_get_contents($root . '/include/inc_front/front.func.inc.php');

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

extract_fn($readform, 'cnt21_external_url_allowed');
extract_fn($frontend, 'include_url');

check(cnt21_external_url_allowed('file:///etc/passwd') === false, 'file:// URL is rejected');
check(cnt21_external_url_allowed('php://filter/resource=/etc/passwd') === false, 'php:// wrapper is rejected');
check(cnt21_external_url_allowed('/etc/passwd') === false, 'bare local path is rejected');
check(cnt21_external_url_allowed('content/pages/page.html') === false, 'relative path is rejected');
check(cnt21_external_url_allowed('') === false, 'empty value is rejected');
check(cnt21_external_url_allowed('http://example.com/page') === true, 'http URL is accepted');
check(cnt21_external_url_allowed('https://example.com/page?x=1') === true, 'https URL is accepted');

check(include_url('file:///etc/passwd') === '', 'include_url() refuses file://');
check(include_url('php://filter/resource=/etc/passwd') === '', 'include_url() refuses php://');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
