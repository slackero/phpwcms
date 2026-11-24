<?php
// tests/core/test-url-origin.php — run: php8 tests/core/test-url-origin.php
// Extracts the host helpers from default.inc.php so they can be tested
// without bootstrapping the full frontend. The extraction assumes each
// helper ends with a closing brace at column 0.
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

$source = file_get_contents(__DIR__ . '/../../include/inc_lib/default.inc.php');
$names = ['ip_in_cidr', 'ip_in_cidr_list', 'phpwcms_validate_url_host', 'get_url_origin', 'phpwcms_site_url_is_trusted', 'phpwcms_mail_site_url'];
foreach ($names as $name) {
    extract_fn($source, $name);
}

function run(array $server, array $trusted, bool $forwarded = true, array $trusted_hosts = []): string
{
    $_SERVER = $server + ['SERVER_PROTOCOL' => 'HTTP/1.1', 'SERVER_PORT' => 80];
    $GLOBALS['phpwcms'] = ['trusted_proxies' => $trusted, 'trusted_hosts' => $trusted_hosts];
    return get_url_origin($forwarded);
}

$cases = [
    'plain host'                    => [run(['HTTP_HOST' => 'example.test'], []), 'http://example.test'],
    'forwarded, no allowlist'       => [run(['HTTP_HOST' => 'example.test', 'HTTP_X_FORWARDED_HOST' => 'evil.test'], []), 'http://example.test'],
    'forwarded, untrusted peer'     => [run(['REMOTE_ADDR' => '203.0.113.9', 'HTTP_HOST' => 'example.test', 'HTTP_X_FORWARDED_HOST' => 'evil.test'], ['10.0.0.0/8']), 'http://example.test'],
    'forwarded, trusted peer'       => [run(['REMOTE_ADDR' => '10.0.0.5', 'HTTP_HOST' => 'example.test', 'HTTP_X_FORWARDED_HOST' => 'cdn.test'], ['10.0.0.0/8']), 'http://cdn.test'],
    'forwarded chain, trusted peer' => [run(['REMOTE_ADDR' => '10.0.0.5', 'HTTP_X_FORWARDED_HOST' => 'cdn.test, inner.test'], ['10.0.0.0/8']), 'http://cdn.test'],
    'bad host chars fall back'      => [run(['HTTP_HOST' => 'evil"<script>', 'SERVER_NAME' => 'fallback.test'], []), 'http://fallback.test'],
    'no host at all'                => [run(['SERVER_NAME' => 'fallback.test'], []), 'http://fallback.test'],
    'host with port'                => [run(['HTTP_HOST' => 'example.test:8080'], []), 'http://example.test:8080'],
    'ipv6 host'                     => [run(['HTTP_HOST' => '[::1]:8080'], []), 'http://[::1]:8080'],
    'trailing dot fqdn'             => [run(['HTTP_HOST' => 'example.test.'], []), 'http://example.test'],
    'underscore hostname'           => [run(['HTTP_HOST' => 'dev_site.test:8080'], []), 'http://dev_site.test:8080'],
    'forwarded, trusted, bad host'  => [run(['REMOTE_ADDR' => '10.0.0.5', 'HTTP_HOST' => 'example.test', 'HTTP_X_FORWARDED_HOST' => 'evil test/'], ['10.0.0.0/8']), 'http://example.test'],
    'allowlist blocks host header'  => [run(['HTTP_HOST' => 'evil.test'], [], true, ['example.test', 'www.example.test']), 'http://example.test'],
    'allowlist matches host'        => [run(['HTTP_HOST' => 'www.example.test'], [], true, ['example.test', 'www.example.test']), 'http://www.example.test'],
    'allowlist keeps request port'  => [run(['HTTP_HOST' => 'example.test:8080'], [], true, ['example.test']), 'http://example.test:8080'],
    'allowlist entry port wins'     => [run(['HTTP_HOST' => 'example.test:8080'], [], true, ['example.test:9090']), 'http://example.test:9090'],
    'allowlist beats forwarded'     => [run(['REMOTE_ADDR' => '10.0.0.5', 'HTTP_X_FORWARDED_HOST' => 'evil.test'], ['10.0.0.0/8'], true, ['example.test']), 'http://example.test'],
    'empty allowlist unchanged'     => [run(['HTTP_HOST' => 'example.test'], [], true, []), 'http://example.test'],
    'allowlist skips invalid entry' => [run(['HTTP_HOST' => 'example.test'], [], true, ['bad host', 'example.test']), 'http://example.test'],
    'underscore kept'               => [run(['HTTP_HOST' => 'a_b.test'], []), 'http://a_b.test'],
    'port max accepted'             => [run(['HTTP_HOST' => 'example.test:65535'], []), 'http://example.test:65535'],
    'ipv6 port max accepted'        => [run(['HTTP_HOST' => '[::1]:65535'], []), 'http://[::1]:65535'],
    'label leading hyphen'          => [run(['HTTP_HOST' => 'a.-b.test', 'SERVER_NAME' => 'fallback.test'], []), 'http://fallback.test'],
    'label trailing hyphen'         => [run(['HTTP_HOST' => 'a.b-.test', 'SERVER_NAME' => 'fallback.test'], []), 'http://fallback.test'],
    'empty label'                   => [run(['HTTP_HOST' => 'a..test', 'SERVER_NAME' => 'fallback.test'], []), 'http://fallback.test'],
    'label too long'                => [run(['HTTP_HOST' => str_repeat('a', 64) . '.test', 'SERVER_NAME' => 'fallback.test'], []), 'http://fallback.test'],
    'port out of range'             => [run(['HTTP_HOST' => 'example.test:99999', 'SERVER_NAME' => 'fallback.test'], []), 'http://fallback.test'],
];

// setup-only helpers, extracted from the same file. The IDN conversion needs
// the shipped Composer autoloader, otherwise the helper returns its input.
if (is_file(__DIR__ . '/../../include/vendor/autoload.php')) {
    require_once __DIR__ . '/../../include/vendor/autoload.php';
}
$setup_source = file_get_contents(__DIR__ . '/../../setup/inc/setup.func.inc.php');
foreach (['setup_site_url_to_idn', 'setup_resolve_site_url', 'setup_site_url_is_valid'] as $name) {
    extract_fn($setup_source, $name);
}

$site_cases = [
    'entered url kept'          => [setup_resolve_site_url('https://example.test', ''), 'https://example.test/'],
    'entered url slash'         => [setup_resolve_site_url('https://example.test/', ''), 'https://example.test/'],
    'uppercase scheme lowered'  => [setup_resolve_site_url('HTTPS://example.test', ''), 'https://example.test/'],
    'empty uses detected'       => [setup_resolve_site_url('', 'http://detected.test:8080'), 'http://detected.test:8080/'],
    'empty, no host detected'   => [setup_resolve_site_url('', 'http://'), ''],
    'empty, none given'         => [setup_resolve_site_url('', ''), ''],
    'valid https'               => [setup_site_url_is_valid('https://example.test/'), true],
    'valid http path'           => [setup_site_url_is_valid('http://example.test/cms/'), true],
    'invalid bare host'         => [setup_site_url_is_valid('example.test'), false],
    'invalid scheme'            => [setup_site_url_is_valid('ftp://example.test'), false],
    'invalid empty'             => [setup_site_url_is_valid(''), false],
    'invalid credentials'       => [setup_site_url_is_valid('https://user:pass@example.test/'), false],
    'invalid query'             => [setup_site_url_is_valid('https://example.test/?a=1'), false],
    'invalid fragment'          => [setup_site_url_is_valid('https://example.test/#x'), false],
] + (class_exists('\Algo26\IdnaConvert\ToIdn') ? [
    'idn host converted'        => [setup_site_url_to_idn('https://münchen.test'), 'https://xn--mnchen-3ya.test'],
    'idn host kept with port'   => [setup_site_url_to_idn('https://münchen.test:8080/cms'), 'https://xn--mnchen-3ya.test:8080/cms'],
    'ascii host untouched'      => [setup_site_url_to_idn('https://example.test'), 'https://example.test'],
    'invalid idn host returned' => [setup_site_url_to_idn('https://münchen.test.'), 'https://münchen.test.'],
] : []);

$failed = 0;
foreach ($site_cases as $label => [$actual, $expected]) {
    if ($actual !== $expected) {
        $failed++;
        echo "FAIL $label: expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n";
    } else {
        echo "ok   $label\n";
    }
}

// the setup copy of the validator must stay identical to the frontend one
// compare behaviour, not formatting: ignore comments and whitespace
$normalize = static function ($code) {
    return preg_replace('/\s+/', ' ', preg_replace('#//[^\n]*#', '', $code));
};
if ($normalize(extract_fn_source($source, 'phpwcms_validate_url_host'))
    !== $normalize(extract_fn_source($setup_source, 'phpwcms_validate_url_host'))
) {
    fwrite(STDERR, "phpwcms_validate_url_host differs between default.inc.php and setup.func.inc.php\n");
    exit(1);
}
echo "ok   setup validator in sync\n";

function trusted(array $phpwcms): bool
{
    $GLOBALS['phpwcms'] = $phpwcms;
    return phpwcms_site_url_is_trusted();
}

$trust_cases = [
    'configured site trusted'    => [trusted(['site' => 'https://example.test', 'site_derived' => false]), true],
    'derived site, no allowlist' => [trusted(['site' => 'https://evil.test', 'site_derived' => true, 'trusted_hosts' => []]), false],
    'derived site, allowlisted'  => [trusted(['site' => 'https://example.test', 'site_derived' => true, 'trusted_hosts' => ['example.test']]), true],
    'derived site, mismatch'     => [trusted(['site' => 'https://evil.test', 'site_derived' => true, 'trusted_hosts' => ['example.test']]), false],
    'derived site, bad entries'  => [trusted(['site' => 'https://evil.test', 'site_derived' => true, 'trusted_hosts' => ['bad host']]), false],
    'empty site untrusted'       => [trusted(['site' => '', 'site_derived' => true, 'trusted_hosts' => ['example.test']]), false],
    'missing flag is untrusted'  => [trusted(['site' => 'https://example.test']), false],
    'configured site, empty str' => [trusted(['site' => 'http://', 'site_derived' => false]), true],
];

define('PHPWCMS_URL', 'https://example.test/');

function mail_url(array $phpwcms): string
{
    trusted($phpwcms);
    return phpwcms_mail_site_url();
}

$mail_cases = [
    'mail url trusted site'     => [mail_url(['site' => 'https://example.test', 'site_derived' => false]), PHPWCMS_URL],
    'mail url derived, no list' => [mail_url(['site' => 'https://evil.test', 'site_derived' => true, 'trusted_hosts' => []]), ''],
    'mail url derived, listed'  => [mail_url(['site' => 'https://example.test', 'site_derived' => true, 'trusted_hosts' => ['example.test']]), PHPWCMS_URL],
];

$failed = 0;
foreach ($mail_cases as $label => [$actual, $expected]) {
    if ($actual !== $expected) {
        $failed++;
        echo "FAIL $label: expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n";
    } else {
        echo "ok   $label\n";
    }
}
foreach ($trust_cases as $label => [$actual, $expected]) {
    if ($actual !== $expected) {
        $failed++;
        echo "FAIL $label: expected " . var_export($expected, true) . ", got " . var_export($actual, true) . "\n";
    } else {
        echo "ok   $label\n";
    }
}

foreach ($cases as $label => [$actual, $expected]) {
    if ($actual !== $expected) {
        $failed++;
        echo "FAIL $label: expected $expected, got $actual\n";
    } else {
        echo "ok   $label\n";
    }
}
exit($failed ? 1 : 0);
