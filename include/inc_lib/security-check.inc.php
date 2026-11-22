<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

/**
 * English source strings for every row. The backend template translates them
 * through $BL first and uses these only as fallback / for CLI output.
 */
const PHPWCMS_SECURITY_TEXT = array(
    'be_security_row_storage_root' => 'Private storage root',
    'be_security_row_storage_dir' => 'Private %s/',
    'be_security_row_legacy_dir' => 'Legacy %s/',
    'be_security_row_protector_form' => 'Form upload directory',
    'be_security_row_protector_cache' => 'Legacy cache directory',
    'be_security_row_protector_config' => 'Configuration directory',
    'be_security_row_protector_upload' => 'FTP upload directory',
    'be_security_row_protector_tests' => 'Test scripts',
    'be_security_row_protector_filearchive' => 'File archive',
    'be_security_row_root_rule' => 'Root server configuration',
    'be_security_row_form_token_key' => 'Form link secret',
    'be_security_row_setup_dir' => 'Setup directory',
    'be_security_detail_path' => '%s',
    'be_security_detail_missing' => 'Missing: %s',
    'be_security_detail_not_writable' => 'Not writable: %s',
    'be_security_detail_missing_or_unwritable' => 'Missing or not writable: %s',
    'be_security_detail_no_root' => 'No storage root is configured.',
    'be_security_detail_mode_open' => 'Mode %s, expected 0700.',
    'be_security_detail_removed' => 'Removed.',
    'be_security_detail_leftover_one' => '1 entry remains inside the document root.',
    'be_security_detail_leftover_many' => '%d entries remain inside the document root.',
    'be_security_detail_leftover_denied_one' => '1 entry remains behind a deny file; move it to the private storage.',
    'be_security_detail_leftover_denied_many' => '%d entries remain behind a deny file; move them to the private storage.',
    'be_security_detail_empty_denied' => 'Empty and denied; remove the directory.',
    'be_security_detail_empty_open' => 'Empty directory without a deny file.',
    'be_security_detail_no_deny_rule' => 'No deny rule in %s.',
    'be_security_detail_downloads_open' => 'Direct downloads are enabled (opt-in).',
    'be_security_detail_downloads_closed' => 'Direct downloads are disabled.',
    'be_security_detail_root_ok' => '%s denies the private paths.',
    'be_security_detail_root_missing' => 'No shipped server configuration with the private-path deny rules was found.',
    'be_security_detail_token_fallback' => 'form_token_key is empty; falls back to db_user + db_pass.',
    'be_security_detail_token_ok' => 'A dedicated form_token_key is configured.',
    'be_security_detail_setup_present' => 'setup/ is still present; remove or block it.',
);

/**
 * Security configuration check.
 *
 * Verifies the private storage tree, the legacy content directories and the
 * per-directory protector files. The updater skips content/, upload/ and
 * filearchive/, so an update can leave these files missing or stale; this check
 * is the backend-side detector and repair path for that.
 *
 * Each row carries a translation key plus its sprintf arguments, and the
 * rendered English strings for CLI output and as the template fallback.
 *
 * @return array<int, array{id:string,label:string,label_key:string,label_args:array,status:string,detail:string,detail_key:string,detail_args:array,fixable:bool}>
 */
function phpwcms_security_check(string $root, ?string $storageRoot = null): array
{
    $root = rtrim(str_replace('\\', '/', $root), '/');
    $storageRoot = rtrim(str_replace('\\', '/', (string)$storageRoot), '/');
    $contentPath = defined('CONTENT_PATH') ? trim((string)CONTENT_PATH, '/') : 'content';
    if ($contentPath === '') {
        $contentPath = 'content';
    }

    $checks = array();

    // --- private storage root
    if ($storageRoot === '') {
        $checks[] = phpwcms_security_check_row('storage_root', 'be_security_row_storage_root', array(), 'fail', 'be_security_detail_no_root', array(), false);
    } elseif (!is_dir($storageRoot)) {
        $checks[] = phpwcms_security_check_row('storage_root', 'be_security_row_storage_root', array(), 'fail', 'be_security_detail_missing', array($storageRoot), false);
    } elseif (!is_writable($storageRoot)) {
        $checks[] = phpwcms_security_check_row('storage_root', 'be_security_row_storage_root', array(), 'fail', 'be_security_detail_not_writable', array($storageRoot), false);
    } elseif (($storage_root_mode = @fileperms($storageRoot)) !== false && ($storage_root_mode & 0077) !== 0) {
        $checks[] = phpwcms_security_check_row('storage_root', 'be_security_row_storage_root', array(), 'warn', 'be_security_detail_mode_open', array(sprintf('%04o', $storage_root_mode & 0777)), false);
    } else {
        $checks[] = phpwcms_security_check_row('storage_root', 'be_security_row_storage_root', array(), 'ok', 'be_security_detail_path', array($storageRoot), false);
    }

    // --- private storage subdirectories
    foreach (array('tmp', 'cache', 'log', 'backup', 'form') as $name) {
        $id = 'storage_dir:' . $name;
        $dir = $storageRoot !== '' ? $storageRoot . '/' . $name : '';
        if ($dir !== '' && is_dir($dir) && is_writable($dir)) {
            $checks[] = phpwcms_security_check_row($id, 'be_security_row_storage_dir', array($name), 'ok', 'be_security_detail_path', array($dir), false);
        } elseif ($dir === '') {
            $checks[] = phpwcms_security_check_row($id, 'be_security_row_storage_dir', array($name), 'fail', 'be_security_detail_no_root', array(), true);
        } else {
            $checks[] = phpwcms_security_check_row($id, 'be_security_row_storage_dir', array($name), 'fail', 'be_security_detail_missing_or_unwritable', array($dir), true);
        }
    }

    // --- legacy document-root directories must be gone or denied
    foreach (array('tmp', 'cache', 'log', 'backup', 'form') as $name) {
        $id = 'legacy_dir:' . $name;
        $dir = $root . '/' . $contentPath . '/' . $name;
        $label_args = array($contentPath . '/' . $name);
        if (!is_dir($dir)) {
            $checks[] = phpwcms_security_check_row($id, 'be_security_row_legacy_dir', $label_args, 'ok', 'be_security_detail_removed', array(), false);
            continue;
        }
        $entries = array_values(array_diff(@scandir($dir) ?: array(), array('.', '..', '.htaccess', 'index.html')));
        $count = count($entries);
        $denied = phpwcms_security_check_denied($dir . '/.htaccess');
        if ($denied) {
            if ($count > 0) {
                $checks[] = phpwcms_security_check_row($id, 'be_security_row_legacy_dir', $label_args, 'warn', $count === 1 ? 'be_security_detail_leftover_denied_one' : 'be_security_detail_leftover_denied_many', $count === 1 ? array() : array($count), false);
            } else {
                $checks[] = phpwcms_security_check_row($id, 'be_security_row_legacy_dir', $label_args, 'warn', 'be_security_detail_empty_denied', array(), false);
            }
        } else {
            if ($count > 0) {
                $checks[] = phpwcms_security_check_row($id, 'be_security_row_legacy_dir', $label_args, 'fail', $count === 1 ? 'be_security_detail_leftover_one' : 'be_security_detail_leftover_many', $count === 1 ? array() : array($count), true);
            } else {
                $checks[] = phpwcms_security_check_row($id, 'be_security_row_legacy_dir', $label_args, 'fail', 'be_security_detail_empty_open', array(), true);
            }
        }
    }

    // --- per-directory protectors
    $protectors = array(
        $contentPath . '/form/.htaccess' => array('be_security_row_protector_form', 'Form upload directory'),
        $contentPath . '/cache/.htaccess' => array('be_security_row_protector_cache', 'Legacy cache directory'),
        'include/config/.htaccess' => array('be_security_row_protector_config', 'Configuration directory'),
        'upload/.htaccess' => array('be_security_row_protector_upload', 'FTP upload directory'),
        'tests/.htaccess' => array('be_security_row_protector_tests', 'Test scripts'),
    );
    foreach ($protectors as $relative => $meta) {
        $file = $root . '/' . $relative;
        $id = 'protector:' . $relative;
        if (phpwcms_security_check_denied($file)) {
            $checks[] = phpwcms_security_check_row($id, $meta[0], array(), 'ok', 'be_security_detail_path', array($relative), false);
        } elseif (is_file($file)) {
            $checks[] = phpwcms_security_check_row($id, $meta[0], array(), 'fail', 'be_security_detail_no_deny_rule', array($relative), true);
        } else {
            $checks[] = phpwcms_security_check_row($id, $meta[0], array(), 'fail', 'be_security_detail_missing', array($relative), true);
        }
    }

    // --- filearchive is an explicit opt-in for direct downloads
    $archive = $root . '/filearchive/.htaccess';
    if (!is_file($archive)) {
        $checks[] = phpwcms_security_check_row('protector:filearchive/.htaccess', 'be_security_row_protector_filearchive', array(), 'warn', 'be_security_detail_missing', array('filearchive/.htaccess'), true);
    } elseif (phpwcms_security_check_denied($archive)) {
        $checks[] = phpwcms_security_check_row('protector:filearchive/.htaccess', 'be_security_row_protector_filearchive', array(), 'ok', 'be_security_detail_downloads_closed', array(), false);
    } else {
        $checks[] = phpwcms_security_check_row('protector:filearchive/.htaccess', 'be_security_row_protector_filearchive', array(), 'warn', 'be_security_detail_downloads_open', array(), false);
    }

    // --- root server configuration templates
    $rootRule = false;
    foreach (array('.htaccess', '_.htaccess', 'nginx.conf', 'web.config') as $candidate) {
        $file = $root . '/' . $candidate;
        if (is_file($file) && str_contains((string)@file_get_contents($file), 'content/(tmp|cache|log|backup')) {
            $rootRule = true;
            $checks[] = phpwcms_security_check_row('root_rule', 'be_security_row_root_rule', array(), 'ok', 'be_security_detail_root_ok', array($candidate), false);
            break;
        }
    }
    if (!$rootRule) {
        $checks[] = phpwcms_security_check_row('root_rule', 'be_security_row_root_rule', array(), 'warn', 'be_security_detail_root_missing', array(), false);
    }

    // --- mailing secret
    $explicit = trim((string)($GLOBALS['phpwcms']['form_token_key'] ?? ''));
    if ($explicit === '') {
        $checks[] = phpwcms_security_check_row('form_token_key', 'be_security_row_form_token_key', array(), 'warn', 'be_security_detail_token_fallback', array(), false);
    } else {
        $checks[] = phpwcms_security_check_row('form_token_key', 'be_security_row_form_token_key', array(), 'ok', 'be_security_detail_token_ok', array(), false);
    }

    // --- installer directory
    if (is_dir($root . '/setup')) {
        $checks[] = phpwcms_security_check_row('setup_dir', 'be_security_row_setup_dir', array(), 'warn', 'be_security_detail_setup_present', array(), false);
    } else {
        $checks[] = phpwcms_security_check_row('setup_dir', 'be_security_row_setup_dir', array(), 'ok', 'be_security_detail_removed', array(), false);
    }

    return $checks;
}

/**
 * Apply the safe repairs from a check run: private directories are created,
 * missing or empty protector files are written. Nothing is deleted.
 *
 * @param string[] $ids Check ids to repair
 *
 * @return array<string, bool> id => repaired
 */
function phpwcms_security_fix(string $root, ?string $storageRoot, array $ids): array
{
    $root = rtrim(str_replace('\\', '/', $root), '/');
    $storageRoot = rtrim(str_replace('\\', '/', (string)$storageRoot), '/');
    $contentPath = defined('CONTENT_PATH') ? trim((string)CONTENT_PATH, '/') : 'content';
    if ($contentPath === '') {
        $contentPath = 'content';
    }

    // only ids this run reports as fixable may be repaired; the POST body is
    // user input and must never choose the path that is written
    $allowed = array();
    foreach (phpwcms_security_check($root, $storageRoot) as $row) {
        if (!empty($row['fixable'])) {
            $allowed[] = $row['id'];
        }
    }

    $done = array();

    foreach ($ids as $id) {
        $id = (string)$id;
        if (!in_array($id, $allowed, true) || str_contains($id, '..')) {
            $done[$id] = false;
            continue;
        }
        if (str_starts_with($id, 'storage_dir:')) {
            $name = substr($id, strlen('storage_dir:'));
            $done[$id] = $storageRoot !== '' && phpwcms_security_fix_dir($storageRoot . '/' . $name, 0700);
            continue;
        }
        if (str_starts_with($id, 'legacy_dir:')) {
            $name = substr($id, strlen('legacy_dir:'));
            $dir = $root . '/' . $contentPath . '/' . $name;
            $done[$id] = is_dir($dir) && phpwcms_security_fix_write($dir . '/.htaccess', phpwcms_security_fix_deny());
            continue;
        }
        if (str_starts_with($id, 'protector:')) {
            $relative = substr($id, strlen('protector:'));
            if (!is_file($root . '/' . $relative) || !phpwcms_security_check_denied($root . '/' . $relative)) {
                $body = $relative === $contentPath . '/form/.htaccess'
                    ? phpwcms_security_fix_form_protector()
                    : phpwcms_security_fix_deny();
                $done[$id] = phpwcms_security_fix_write($root . '/' . $relative, $body);
            } else {
                $done[$id] = true;
            }
            continue;
        }
        $done[$id] = false;
    }

    return $done;
}

function phpwcms_security_check_row(string $id, string $labelKey, array $labelArgs, string $status, string $detailKey, array $detailArgs, bool $fixable): array
{
    return array(
        'id' => $id,
        'label' => vsprintf(PHPWCMS_SECURITY_TEXT[$labelKey] ?? $labelKey, $labelArgs),
        'label_key' => $labelKey,
        'label_args' => $labelArgs,
        'status' => $status,
        'detail' => vsprintf(PHPWCMS_SECURITY_TEXT[$detailKey] ?? $detailKey, $detailArgs),
        'detail_key' => $detailKey,
        'detail_args' => $detailArgs,
        'fixable' => $fixable,
    );
}

function phpwcms_security_check_denied(string $file): bool
{
    if (!is_file($file)) {
        return false;
    }
    $source = (string)@file_get_contents($file);

    return stripos($source, 'Require all denied') !== false || stripos($source, 'Deny from all') !== false;
}

function phpwcms_security_fix_dir(string $dir, int $mode = 0700): bool
{
    if (!is_dir($dir) && !@mkdir($dir, $mode, true) && !is_dir($dir)) {
        return false;
    }
    @chmod($dir, $mode);

    return is_dir($dir) && is_writable($dir);
}

function phpwcms_security_fix_write(string $file, string $body): bool
{
    $dir = dirname($file);
    if (!is_dir($dir) && !@mkdir($dir, 0700, true) && !is_dir($dir)) {
        return false;
    }
    if (@file_put_contents($file, $body, LOCK_EX) === false) {
        return false;
    }
    @chmod($file, 0600);

    return true;
}

function phpwcms_security_fix_deny(): string
{
    return "# Private: must never be served\n"
        . "<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n"
        . "<IfModule !mod_authz_core.c>\n    Order allow,deny\n    Deny from all\n</IfModule>\n";
}

function phpwcms_security_fix_form_protector(): string
{
    return "# Private form storage: never execute uploads\n"
        . "<FilesMatch \"(?i)\\.(php[0-9]?|phtml|pht|phar|phps|inc|cgi|pl|py|sh|bash|exe|com|bat|cmd|msi|bin|dll|vbs|wsf|scr|htaccess|htpasswd|ini|env|conf|bak|sql)$\">\n"
        . "    <IfModule mod_authz_core.c>\n        Require all denied\n    </IfModule>\n"
        . "    <IfModule !mod_authz_core.c>\n        Order allow,deny\n        Deny from all\n    </IfModule>\n"
        . "</FilesMatch>\n"
        . "<IfModule mod_php8.c>\n    php_flag engine off\n</IfModule>\n"
        . "<IfModule mod_headers.c>\n    Header set X-Content-Type-Options \"nosniff\"\n</IfModule>\n";
}
