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
    'internal include directories' => 'include/(config',
    'inc_front directory' => '|inc_front',
    'inc_help directory' => '|inc_help',
    'inc_lang directory' => '|inc_lang',
    'inc_lib directory' => '|inc_lib',
    'inc_tmpl except the cnt51 popup' => 'inc_tmpl/(?!content/cnt51\.open\.php$)',
    'inc_module stays filtered around the shop API' => 'api\.shop\.php',
    'vendor except TinyMCE' => 'include/vendor/(?!tinymce/|mklkj/tinymce-i18n/)',
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
check(str_contains($configs['.htaccess'], 'include/build'), '.htaccess blocks build scripts');
check(str_contains($configs['_.htaccess'], 'include/build'), '_.htaccess blocks build scripts');
check(str_contains($configs['nginx.conf'], 'include/build'), 'nginx blocks build scripts');
preg_match('#<rule name="Block private paths".*?</rule>#s', $configs['web.config'], $web_private_rule);
check(str_contains($web_private_rule[0] ?? '', '|build'), 'web.config blocks build scripts');

// include/inc_act holds browser-invoked action endpoints and must stay reachable.
check(!preg_match('#^RedirectMatch\s+404\s+.*inc_act#m', $configs['.htaccess']), '.htaccess does not block include/inc_act');
check(!preg_match('#^RedirectMatch\s+404\s+.*inc_act#m', $configs['_.htaccess']), '_.htaccess does not block include/inc_act');
check(!preg_match('#location\s+~[^{]*inc_act[^{]*\{#', $configs['nginx.conf']), 'nginx does not block include/inc_act');
preg_match('#<rule name="Block private paths".*?</rule>#s', $configs['web.config'], $private_rule);
check(!str_contains($private_rule[0] ?? '', 'inc_act'), 'web.config does not block include/inc_act');
check(str_contains($configs['web.config'], '|inc_act)'), 'web.config allows include/inc_act');

// PHP execution rules must not blanket-deny include/.
check(
    !preg_match('#location\s+~\s+/\(content\|include\|#', $configs['nginx.conf']),
    'nginx PHP rule no longer blocks all of include/'
);
preg_match('#<rule name="Block PHP in private directories".*?</rule>#s', $configs['web.config'], $php_rule);
check($php_rule !== [], 'web.config PHP deny rule exists');
check(
    !preg_match('#\^include/\.\*#', $php_rule[0] ?? ''),
    'web.config PHP rule no longer blocks all of include/'
);

// PHP must not execute from a module template directory either.
check(
    str_contains($configs['.htaccess'], '/include/inc_module/(?!.*/api\.shop\.php$).*\.php$'),
    '.htaccess denies PHP in include/inc_module'
);
check(
    str_contains($configs['nginx.conf'], '/include/inc_module/(?!.*/api\.shop\.php$).*\.php$'),
    'nginx denies PHP in include/inc_module'
);
check(str_contains($configs['web.config'], 'Allow root PHP entry points'), 'web.config allows root PHP entry scripts (includes formfile.php)');
check(str_contains($configs['web.config'], 'url="^[^/]+\\.php$"'), 'web.config root PHP rule matches root-level .php files');

$private_dirs = [
    'content/form',
    'content/cache',
    'include/config',
    'upload',
    'filearchive',
    'tests',
];
foreach ($private_dirs as $dir) {
    $source = read_or_empty($root . '/' . $dir . '/.htaccess');
    check($source !== '', "$dir/.htaccess exists");
    check(stripos($source, 'deny') !== false, "$dir/.htaccess denies access");
}

// A per-directory Require/If file inside vendor is not used: <If> needs
// AllowOverride All and answers 500 on stricter hosts. The root rules carry
// the vendor deny, with the TinyMCE exception.
$vendor_htaccess = read_or_empty($root . '/include/vendor/.htaccess');
check(!str_contains($vendor_htaccess, '<If '), 'include/vendor/.htaccess has no AllowOverride-dependent <If>');

$editor = read_or_empty($root . '/include/inc_lib/wysiwyg.editor.inc.php');
check(
    str_contains($editor, 'include/vendor/tinymce/tinymce/tinymce.min.js'),
    'TinyMCE editor script loads from the allowed vendor path'
);
check(
    str_contains($editor, 'include/vendor/mklkj/tinymce-i18n/langs/'),
    'TinyMCE language files load from the allowed vendor path'
);

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
