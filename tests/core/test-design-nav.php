<?php
// tests/core/test-design-nav.php — run: php8 tests/core/test-design-nav.php
// Theme and design pages live in their own sidebar section above Admin.

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

$backend = (string)file_get_contents($root . '/phpwcms.php');

check(str_contains($backend, '$design_subnav'), 'the design section builds its own submenu');
check(
    str_contains($backend, '$can_design')
        && preg_match('/\$can_design[\s\S]{0,400}\$design_subnav = \'\'/', $backend) === 1,
    'the design section is gated by design permissions, not only adm'
);
check(str_contains($backend, "\$BL['be_nav_design']"), 'the design section has a label');
check(substr_count($backend, 'phpwcms.php?do=admin&amp;p=8') === 1, 'page layout appears once (design section)');
check(substr_count($backend, 'phpwcms.php?do=admin&amp;p=11') === 1, 'templates appear once (design section)');
check(substr_count($backend, 'phpwcms.php?do=admin&amp;p=17') === 1, 'template translations appear once (design section)');
check(substr_count($backend, 'phpwcms.php?do=admin&amp;p=16') === 1, 'custom content parts appear once (design section)');

$langFiles = glob($root . '/include/inc_lang/backend/*/lang.inc.php');
$hits = 0;
foreach ($langFiles as $langFile) {
    if (str_contains((string)file_get_contents($langFile), 'be_nav_design')) {
        $hits++;
    }
}
check(count($langFiles) > 0 && $hits === count($langFiles), 'the design label exists in every backend language');

exit($failures === 0 ? 0 : 1);
