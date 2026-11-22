<?php
// tests/core/test-server-deny.php — run: php8 tests/core/test-server-deny.php
// The web server configuration must deny private paths and only serve an
// explicit allowlist, independently of .htaccess support.

$root = dirname(__DIR__, 2);
$failures = 0;
function check(bool $ok, string $message): void
{
    global $failures;
    echo ($ok ? 'OK   ' : 'FAIL ') . $message . "\n";
    if (!$ok) {
        $failures++;
    }
}
function read_or_empty(string $path): string
{
    return is_file($path) ? (string)file_get_contents($path) : '';
}

$configs = [
    'nginx.conf' => read_or_empty($root . '/nginx.conf'),
    '.htaccess' => read_or_empty($root . '/.htaccess'),
    '_.htaccess' => read_or_empty($root . '/_.htaccess'),
    'web.config' => read_or_empty($root . '/web.config'),
];

$rules = [
    'private content storage' => 'content/(tmp|cache|log|backup',
    'internal include directories' => 'include/(config|vendor|inc_act|inc_front|inc_help|inc_lang|inc_lib|inc_module|inc_tmpl)',
    'include/inc_ext except ckeditor' => 'include/inc_ext',
    'upload directory' => 'upload',
    'tests directory' => 'tests',
    'hidden files' => '(?!well-known)',
];

foreach ($configs as $name => $source) {
    check($source !== '', "$name exists");
    foreach ($rules as $label => $needle) {
        check(str_contains($source, $needle), "$name blocks $label");
    }
}

check(str_contains($configs['nginx.conf'], 'sql|log|bak|dist'), 'nginx denies sensitive extensions');
check(str_contains($configs['.htaccess'], 'sql|log|bak|dist'), '.htaccess denies sensitive extensions');
check(str_contains($configs['web.config'], 'fileExtension=".sql"'), 'web.config denies sensitive extensions');
check(!preg_match('#location\s+~\s+\^/content/\(tmp#', $configs['nginx.conf']), 'nginx patterns stay unanchored');
check(str_contains($configs['web.config'], 'Allow root PHP entry points'), 'web.config allows root PHP entry scripts (includes formfile.php)');
check(str_contains($configs['web.config'], 'url="^[^/]+\\.php$"'), 'web.config root PHP rule matches root-level .php files');

$private_dirs = [
    'content/form',
    'content/cache',
    'include/config',
    'include/vendor',
    'upload',
    'filearchive',
    'tests',
];
foreach ($private_dirs as $dir) {
    $source = read_or_empty($root . '/' . $dir . '/.htaccess');
    check($source !== '', "$dir/.htaccess exists");
    check(stripos($source, 'deny') !== false, "$dir/.htaccess denies access");
}

check(!is_dir($root . '/content/less'), 'unused content/less is removed');

$compose = read_or_empty($root . '/docker-compose.yml');
check(str_contains($compose, 'phpwcms-php') || str_contains($compose, 'container_name: phpwcms-php'), 'docker-compose starts a php-fpm service');
check(str_contains($compose, 'nginx'), 'docker-compose starts an nginx service');
check(str_contains($compose, 'phpwcms-storage'), 'docker-compose mounts the private storage volume');

$fpm = read_or_empty($root . '/Dockerfile.fpm');
check(str_contains($fpm, 'php:8.4-fpm'), 'Dockerfile.fpm uses PHP 8.4');

$http_test = read_or_empty($root . '/tests/web/path-protection.sh');
check($http_test !== '', 'tests/web/path-protection.sh exists');
check(str_contains($http_test, 'formfile.php'), 'HTTP test probes the form controller');
check(str_contains($http_test, '8080') && str_contains($http_test, '8082'), 'HTTP test defaults to the v2 ports');

exit($failures === 0 ? 0 : 1);
