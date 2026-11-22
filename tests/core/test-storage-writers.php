<?php
// tests/core/test-storage-writers.php — run: php8 tests/core/test-storage-writers.php
// Every private writer must target the storage constants. Writing into
// content/ re-creates the directories the split removed and, for captcha,
// breaks captcha sessions after the migration.

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
function read_file(string $path): string
{
    return is_file($path) ? (string)file_get_contents($path) : '';
}

$captcha = read_file($root . '/img/captcha.php');
check(preg_match('/\$spaf_obj->work_dir\s*=\s*PHPWCMS_TEMP\b/', $captcha) === 1, 'captcha work_dir is PHPWCMS_TEMP');
check(!str_contains($captcha, "PHPWCMS_ROOT.'/content/tmp/"), 'captcha no longer writes content/tmp');

$front = read_file($root . '/include/inc_front/front.func.inc.php');
check(str_contains($front, "PHPWCMS_TEMP.\$cache_filename"), 'URL cache writes into PHPWCMS_TEMP');
check(!str_contains($front, "PHPWCMS_CONTENT.'tmp/'"), 'URL cache no longer writes content/tmp');

$sitemap = read_file($root . '/sitemap.php');
check(str_contains($sitemap, 'PHPWCMS_CACHE'), 'sitemap cache uses PHPWCMS_CACHE');
check(!str_contains($sitemap, 'PHPWCMS_CONTENT . \'/cache\''), 'sitemap cache no longer builds content/cache');
check(!str_contains($sitemap, 'mkdir($cache_dir, 0777'), 'sitemap cache is not world-writable');

$pdf = read_file($root . '/include/inc_front/pdf.inc.php');
check(str_contains($pdf, 'PHPWCMS_TEMP'), 'print-to-PDF stages in PHPWCMS_TEMP');
check(!str_contains($pdf, "PHPWCMS_CONTENT.'tmp/'"), 'print-to-PDF no longer writes content/tmp');
check(str_contains($pdf, '@unlink($_PDF_file)'), 'print-to-PDF removes the streamed PDF afterwards');
check(str_contains($pdf, 'filename="') && str_contains($pdf, 'filename*=UTF-8'), 'print-to-PDF sends an ASCII filename fallback');

$default = read_file($root . '/include/inc_lib/default.inc.php');
check(preg_match('/phpwcms_storage_ensure\(\s*PHPWCMS_STORAGE_ROOT\s*\.\s*\'tmp\/\'/', $default) === 1, 'bootstrap ensures the private tmp directory');

$upgrade = read_file($root . '/setup/upgrade.php');
$auth_pos = strpos($upgrade, '$isAuthenticated = !empty($_SESSION');
$post_pos = strpos($upgrade, "isset(\$_POST['storage_path'])");
check($auth_pos !== false, 'upgrade script computes the auth state');
check($post_pos === false || ($auth_pos !== false && $post_pos > $auth_pos), 'storage_path POST input is handled after authentication');

exit($failures === 0 ? 0 : 1);
