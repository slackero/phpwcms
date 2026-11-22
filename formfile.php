<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// Serves a form upload stored outside the document root through a signed link.
// The token is authenticated by HMAC, so an edited or forged token cannot
// resolve, and the resolved path is checked to stay inside PHPWCMS_FORM.

$phpwcms = array('SESSION_START' => false);
$phpwcms_root = rtrim(str_replace('\\', '/', dirname(__FILE__)), '/');

// An uninstalled site has no private uploads; never leak a bootstrap fatal.
if (!is_file($phpwcms_root . '/include/config/conf.inc.php')) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'File not found.';
    exit;
}

require_once $phpwcms_root . '/include/config/conf.inc.php';
require_once $phpwcms_root . '/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/general.inc.php';

$file = phpwcms_form_upload_target($_GET['t'] ?? '');

if ($file === false) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'File not found.';
    exit;
}

$name = basename($file);
$ascii_name = preg_replace('/[^\x20-\x7E]/', '_', $name);
$ascii_name = str_replace(array('"', '\\', "\r", "\n"), '_', (string)$ascii_name);
if ($ascii_name === '') {
    $ascii_name = 'download';
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $ascii_name . '"; filename*=UTF-8\'\'' . rawurlencode($name));
header('Content-Length: ' . filesize($file));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');
readfile($file);
exit;
