<?php
// tests/core/test-random-image-public.php — run: php8 tests/core/test-random-image-public.php
// img/random_image.php resolved a directory below the private archive and
// served any image it found, including files marked f_public=0 or with a
// frontend grant. Archive directories are now filtered through the file
// records that the normal download path uses.

$root = dirname(__DIR__, 2);
$lib = file_get_contents($root . '/include/inc_lib/default.inc.php');

define('PHPWCMS_ROOT', $root);
define('PHPWCMS_STORAGE', $root . '/tests/.tmp-random-archive-' . getmypid() . '/');

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

extract_fn($lib, 'path_is_within');
extract_fn($lib, 'phpwcms_image_dir_is_archive');
extract_fn($lib, 'phpwcms_archive_files_matching_records');
extract_fn($lib, 'phpwcms_archive_visibility_sql');

$archive = PHPWCMS_STORAGE;
$outside = $root . '/tests/.tmp-random-public-' . getmypid();

function cleanup_fixture(string $archive, string $outside): void
{
    @rmdir($archive);
    @rmdir($outside);
}

@mkdir($archive, 0777, true);
@mkdir($outside, 0777, true);
register_shutdown_function('cleanup_fixture', $archive, $outside);

check(phpwcms_image_dir_is_archive($archive), 'archive directory is recognised');
check(!phpwcms_image_dir_is_archive($outside), 'directory outside the archive is not treated as private');
check(!phpwcms_image_dir_is_archive(''), 'empty path is not an archive directory');
check(!phpwcms_image_dir_is_archive('/does/not/exist'), 'missing path is not an archive directory');

$files = ['public.jpg', 'granted.png', 'private.webp', 'orphan.gif', 'PUBLIC.JPG'];

$records = [
    ['f_hash' => 'public', 'f_ext' => 'jpg'],
    ['f_hash' => 'granted', 'f_ext' => 'png'],
    ['f_hash' => 'private', 'f_ext' => 'webp'],
];

$public = phpwcms_archive_files_matching_records($files, $records);
check(
    $public === ['public.jpg', 'granted.png', 'private.webp', 'PUBLIC.JPG'],
    'only names with a matching record survive (case-insensitive); visibility flags are filtered in the query'
);
check(phpwcms_archive_files_matching_records($files, []) === [], 'no records means no archive files are served');
check(phpwcms_archive_files_matching_records([], $records) === [], 'empty file list stays empty');
check(
    phpwcms_archive_files_matching_records(['noext'], [['f_hash' => 'noext', 'f_ext' => '']]) === ['noext'],
    'record without extension matches the bare hash'
);

check(
    phpwcms_archive_visibility_sql(true) === 'f_aktiv=1 AND f_trash=0 AND f_public=1 AND f_granted=0',
    'anonymous visibility requires a public record without a frontend grant'
);
check(
    phpwcms_archive_visibility_sql(false) === 'f_aktiv=1 AND f_trash=0 AND f_public=1',
    'a logged-in caller keeps granted records'
);

$script = file_get_contents($root . '/img/random_image.php');
check(str_contains($script, 'phpwcms_image_dir_is_archive('), 'endpoint applies the archive boundary');
check(str_contains($script, 'phpwcms_archive_visibility_sql(!$feuser_logged_in)'), 'archive lookup uses the shared visibility clause and the frontend session state');
check(str_contains($script, 'helper.session.php'), 'endpoint reads an existing frontend session for granted images');
check(!str_contains($script, 'mt_srand'), 'endpoint does not reseed the PRNG from the request time');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
