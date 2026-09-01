<?php
// tests/update/test-engine.php — run: php8 tests/update/test-engine.php
// Tests path-skip logic + version compare without network/filesystem mutation.
define('PHPWCMS_INCLUDE_CHECK', true);
define('PHPWCMS_ROOT', sys_get_temp_dir() . '/phpwcms-eng-' . getmypid());
define('PHPWCMS_VERSION', '2.0.0');
@mkdir(PHPWCMS_ROOT, 0775, true);

require_once __DIR__ . '/../../include/inc_lib/update/update.php';

// 1. skip logic
assert(phpwcms_update::isSkipped('include/config/config.inc.php') === true, 'config must be skipped');
assert(phpwcms_update::isSkipped('content/images/x.jpg') === true, 'content must be skipped');
assert(phpwcms_update::isSkipped('.htaccess') === true);
assert(phpwcms_update::isSkipped('robots.txt') === true);
assert(phpwcms_update::isSkipped('include/inc_lib/revision/revision.php') === false);
assert(phpwcms_update::isSkipped('template/inc_default/startup.php') === false, 'template is applied');

// 2. version compare
assert(phpwcms_update::isNewer('2.0.1') === true);
assert(phpwcms_update::isNewer('2.0.0') === false);
assert(phpwcms_update::isNewer('1.9.9') === false);

echo 'OK' . PHP_EOL;
