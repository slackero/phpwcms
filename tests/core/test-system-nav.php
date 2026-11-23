<?php
// tests/core/test-system-nav.php — run: php8 tests/core/test-system-nav.php
// Superadmin-only maintenance pages get their own sidebar section.

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

check(str_contains($backend, '$system_subnav'), 'the system section builds its own submenu');
check(str_contains($backend, "\$BL['be_nav_system']"), 'the system section has a label');
check(substr_count($backend, 'phpwcms.php?do=admin&amp;p=18') === 1, 'system update appears once (system section)');
check(substr_count($backend, 'phpwcms.php?do=admin&amp;p=20') === 1, 'security check appears once (system section)');
check(
    preg_match('/\$system_subnav[\s\S]{0,1200}phpinfo\(\)/', $backend) === 1,
    'phpinfo appears in the system section'
);
check(
    preg_match('/if \(!empty\(\$_SESSION\[\'wcs_user_admin\'\]\)\) \{\s*\$system_subnav = \'\'/', $backend) === 1,
    'the system section is superadmin only'
);
check(
    preg_match('/case 15:[^\n]*\n\s*if \(!empty\(\$_SESSION\[\'wcs_user_admin\'\]\)\) \{/', $backend) === 1,
    'phpinfo is protected by a superadmin check'
);
check(
    preg_match('/\$system_subnav[\s\S]{0,2000}be_flush_image_cache/', $backend) === 1,
    'flush image cache lives in the system section'
);
check(
    preg_match('/\$system_subnav[\s\S]{0,2000}be_cnt_move_deleted/', $backend) === 1,
    'move deleted files lives in the system section'
);
$system_start = strpos($backend, '$system_subnav');
$system_block = $system_start === false ? '' : substr($backend, $system_start, 2500);
$flush_pos = strpos($system_block, 'be_flush_image_cache');
$update_pos = strpos($system_block, 'be_subnav_admin_update');
check(
    $flush_pos !== false && $update_pos !== false && $flush_pos < $update_pos,
    'cache and delete actions precede the version tools'
);

$securityTpl = (string)file_get_contents($root . '/include/inc_tmpl/admin.security.tmpl.php');
check(
    str_contains($securityTpl, '<h1 class="h3 mb-0 text-gray-800">')
        && str_contains($securityTpl, 'fa-shield-alt'),
    'the security check headline matches the system section'
);

$phpinfoTpl = (string)file_get_contents($root . '/include/inc_tmpl/admin.phpinfo.tmpl.php');
check(
    !str_contains($phpinfoTpl, '<h1>phpinfo()</h1>'),
    'the phpinfo page has no extra headline'
);
check(
    str_contains($phpinfoTpl, 'margin-top: -1.1rem;'),
    'the phpinfo wrapper is pulled up under the header'
);
check(
    str_contains($phpinfoTpl, 'color: var(--pw-link-color);')
        && !str_contains($phpinfoTpl, 'color: #000099;'),
    'phpinfo links use the backend link color'
);
check(
    preg_match('/\.phpinfo-wrapper h2 \{[^}]*margin-bottom: -\.5rem;[^}]*font-weight: bold;/s', $phpinfoTpl) === 1,
    'phpinfo section headings are bold and tightened'
);

$langFiles = glob($root . '/include/inc_lang/backend/*/lang.inc.php');
$hits = 0;
foreach ($langFiles as $langFile) {
    if (str_contains((string)file_get_contents($langFile), 'be_nav_system')) {
        $hits++;
    }
}
check(count($langFiles) > 0 && $hits === count($langFiles), 'the system label exists in every backend language');

exit($failures === 0 ? 0 : 1);
