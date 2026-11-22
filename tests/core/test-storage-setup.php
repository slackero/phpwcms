<?php
$root = dirname(__DIR__, 2);
$failures = 0;
function check(bool $ok, string $message): void { global $failures; echo ($ok ? 'OK   ' : 'FAIL ') . "$message\n"; if (!$ok) $failures++; }

$setupFunc = (string)file_get_contents($root . '/setup/inc/setup.func.inc.php');
$setupCheck = (string)file_get_contents($root . '/setup/inc/setup.check.inc.php');
$step4 = (string)file_get_contents($root . '/setup/inc/step4.inc.php');
$step6 = (string)file_get_contents($root . '/setup/inc/step6.inc.php');

check(str_contains($setupFunc, "\$phpwcms['storage_path']"), 'conf writer persists storage_path');
check(str_contains($setupFunc, "'../phpwcms-storage'"), 'conf writer defaults to ../phpwcms-storage');
check(str_contains($setupCheck, 'phpwcms_storage_path('), 'setup resolves the storage root');
check(str_contains($setupCheck, 'phpwcms_storage_ensure('), 'setup creates the storage tree');
check(str_contains($setupCheck, 'chgrp'), 'setup shares the private tree with the owner group');
check(str_contains($step4, 'name="storage_path"'), 'path step renders the storage_path input');
check(str_contains($step6, 'Private Storage'), 'installer reports the private storage directory');

exit($failures === 0 ? 0 : 1);
