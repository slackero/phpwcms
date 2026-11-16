<?php
// tests/update/test-config-files.php — standalone, run: php8 tests/update/test-config-files.php

$root = rtrim(sys_get_temp_dir(), '/') . '/phpwcms-config-files-' . getmypid();
$fail = static function (string $message) use ($root) {
    fwrite(STDERR, 'FAIL: ' . $message . PHP_EOL);
    if (is_dir($root . '/include/config')) {
        array_map('unlink', glob($root . '/include/config/*') ?: []);
        @rmdir($root . '/include/config');
        @rmdir($root . '/include');
        @rmdir($root);
    }
    exit(1);
};

mkdir($root . '/include/config', 0775, true);

define('PHPWCMS_ROOT', $root);
require_once __DIR__ . '/../../include/inc_lib/user.config.inc.php';

$config = $root . '/include/config/';

// 1. no installation file -> dist template
file_put_contents($config . 'dist.conf.indexpage.inc.php', "<?php // dist\n");
$path = phpwcms_user_config_path('conf.indexpage.inc.php');
$path === $config . 'dist.conf.indexpage.inc.php' || $fail('dist fallback: ' . $path);

// 2. installation file wins over the dist template
file_put_contents($config . 'feeds.ini.php', "; user\n");
file_put_contents($config . 'dist.feeds.ini.php', "; dist\n");
$path = phpwcms_user_config_path('feeds.ini.php');
$path === $config . 'feeds.ini.php' || $fail('user file precedence: ' . $path);

// 3. neither file -> installation path plus one warning
$warnings = [];
set_error_handler(static function ($errstr) use (&$warnings) {
    $warnings[] = $errstr;
    return true;
});
$path = phpwcms_user_config_path('sitemap.custom.ini');
phpwcms_user_config_path('sitemap.custom.ini');
restore_error_handler();
$path === $config . 'sitemap.custom.ini' || $fail('missing file path: ' . $path);
count($warnings) === 1 || $fail('missing file warning not reported once: ' . count($warnings));

// 4. seeding: only missing files, existing user files stay untouched
file_put_contents($config . 'dist.conf.template_default.inc.php', "<?php \$template_default = [];\n");
file_put_contents($config . 'dist.conf.indexpage.inc.php', "<?php // dist\n");
file_put_contents($config . 'sitemap.custom.php', "<?php // user owned\n");
$result = write_user_config_files($root);
sort($result['created']);
$result['created'] === ['conf.indexpage.inc.php', 'conf.template_default.inc.php'] || $fail('created: ' . json_encode($result['created']));
$result['failed'] === ['sitemap.custom.ini'] || $fail('failed: ' . json_encode($result['failed']));
file_get_contents($config . 'sitemap.custom.php') === "<?php // user owned\n" || $fail('user file overwritten');

// 5. second run is a no-op
$result = write_user_config_files($root);
$result['created'] === [] || $fail('second run created files: ' . json_encode($result['created']));

array_map('unlink', glob($config . '*') ?: []);
@rmdir($root . '/include/config');
@rmdir($root . '/include');
@rmdir($root);

echo 'OK' . PHP_EOL;
