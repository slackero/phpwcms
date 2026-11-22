<?php
define('PHPWCMS_FORM', '/srv/private/form/');
$general = file_get_contents(dirname(__DIR__, 2) . '/include/inc_lib/general.inc.php');
if (!preg_match('/^function phpwcms_form_upload_dir\b.*?^}/ms', $general, $m)) {
    fwrite(STDERR, "FAIL could not find phpwcms_form_upload_dir() in general.inc.php\n");
    exit(1);
}
eval($m[0]);
$failures = 0;
function check(bool $ok, string $message): void { global $failures; echo ($ok ? 'OK   ' : 'FAIL ') . "$message\n"; if (!$ok) $failures++; }

check(phpwcms_form_upload_dir('') === '/srv/private/form/', 'default is the form root');
check(phpwcms_form_upload_dir('invoices') === '/srv/private/form/invoices/', 'subdirectory allowed');
check(phpwcms_form_upload_dir('invoices/2026') === '/srv/private/form/invoices/2026/', 'nested subdirectory allowed');
check(phpwcms_form_upload_dir('invoices/') === '/srv/private/form/invoices/', 'trailing slash stripped');
check(phpwcms_form_upload_dir('/invoices/') === '/srv/private/form/', 'leading slash rejected');
check(phpwcms_form_upload_dir('../escape') === '/srv/private/form/', 'parent traversal rejected');
check(phpwcms_form_upload_dir('/etc') === '/srv/private/form/', 'absolute rejected');
check(phpwcms_form_upload_dir('C:\\temp') === '/srv/private/form/', 'drive path rejected');
check(phpwcms_form_upload_dir('a/../../b') === '/srv/private/form/', 'nested traversal rejected');
check(phpwcms_form_upload_dir('a/./b') === '/srv/private/form/', 'dot segment rejected');
exit($failures === 0 ? 0 : 1);
