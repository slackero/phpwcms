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
 * Revision 565: move the private content directories outside the document root.
 *
 * Moves tmp, cache, log, backup and form into the configured storage root and
 * removes content/less. A directory that still holds an entry which could not
 * be moved keeps a deny file, so a stale server mapping returns nothing.
 *
 * Idempotent: a second run finds the directories gone and does nothing.
 * Returns false only when the storage root cannot be created or is not
 * writable; individual move failures are collected in the revision report.
 *
 * @return bool
 */
function phpwcms_revision_r565(): bool
{
    $moved = array();
    $failed = array();

    $storage_root = phpwcms_revision_r565_storage_root($failed);
    if ($storage_root === '') {
        $GLOBALS['phpwcms']['revision_return'] = 'Revision r565 cannot run: ' . implode(' ', $failed);

        return false;
    }

    if (!phpwcms_revision_r565_ensure($storage_root, 0700) || !is_writable($storage_root)) {
        $GLOBALS['phpwcms']['revision_return'] = 'Revision r565 cannot move the private directories: storage root "' . $storage_root . '" does not exist or is not writable. Create it with mode 0700 or set $phpwcms[\'storage_path\'].';

        return false;
    }

    $content_path = 'content';
    if (defined('CONTENT_PATH')) {
        $content_path = trim((string)CONTENT_PATH, '/');
    } elseif (!empty($GLOBALS['phpwcms']['content_path'])) {
        $content_path = trim((string)$GLOBALS['phpwcms']['content_path'], '/');
    }
    $content_dir = rtrim((string)PHPWCMS_ROOT, '/') . '/' . $content_path;

    foreach (array('tmp', 'cache', 'log', 'backup', 'form') as $name) {
        $old_dir = $content_dir . '/' . $name;
        if (!is_dir($old_dir)) {
            continue;
        }
        $new_dir = $storage_root . '/' . $name;
        phpwcms_revision_r565_ensure($new_dir, 0700);

        $entries = @scandir($old_dir);
        if (!is_array($entries)) {
            $failed[] = $name . ' is not readable';
            continue;
        }

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..' || $entry === '.htaccess' || $entry === 'index.html') {
                continue;
            }
            if (phpwcms_revision_r565_move($old_dir . '/' . $entry, $new_dir . '/' . $entry)) {
                $moved[] = $name . '/' . $entry;
            } else {
                $failed[] = $name . '/' . $entry;
            }
        }

        $remaining = @scandir($old_dir);
        $remaining = is_array($remaining) ? array_values(array_diff($remaining, array('.', '..', '.htaccess', 'index.html'))) : array('?');

        if ($remaining === array()) {
            @unlink($old_dir . '/index.html');
            @unlink($old_dir . '/.htaccess');
            @rmdir($old_dir);
            continue;
        }

        // never leave a stale, partially moved directory readable
        @file_put_contents($old_dir . '/.htaccess', "Require all denied\nDeny from all\n");
        $failed[] = $name . '/ (' . count($remaining) . ' entr' . (count($remaining) === 1 ? 'y' : 'ies') . ' left)';
    }

    // content/less is unused; only its protector is tracked
    $less_dir = $content_dir . '/less';
    if (is_dir($less_dir)) {
        $less_entries = @scandir($less_dir);
        $less_entries = is_array($less_entries) ? array_values(array_diff($less_entries, array('.', '..', 'index.html'))) : array('?');
        if ($less_entries === array()) {
            @unlink($less_dir . '/index.html');
            @rmdir($less_dir);
        }
    }

    $message = 'Moved ' . count($moved) . ' private entr' . (count($moved) === 1 ? 'y' : 'ies') . ' to ' . $storage_root . '.';
    if ($failed !== array()) {
        $message .= ' Not moved: ' . implode(', ', $failed) . '. Those directories keep a deny file; move the remaining entries manually.';
    }
    $GLOBALS['phpwcms']['revision_return'] = $message;

    return true;
}

/**
 * Resolve the storage root for a revision that may run before default.inc.php.
 *
 * @param array $errors
 *
 * @return string Storage root without trailing slash, empty when unresolvable
 */
function phpwcms_revision_r565_storage_root(array &$errors): string
{
    if (defined('PHPWCMS_STORAGE_ROOT')) {
        return rtrim((string)PHPWCMS_STORAGE_ROOT, '/');
    }

    if (!function_exists('phpwcms_storage_path')) {
        $storage_file = dirname(__DIR__) . '/storage.inc.php';
        if (!is_file($storage_file)) {
            $errors[] = 'include/inc_lib/storage.inc.php is missing.';

            return '';
        }
        require_once $storage_file;
    }

    return rtrim(phpwcms_storage_path((string)($GLOBALS['phpwcms']['storage_path'] ?? ''), (string)PHPWCMS_ROOT), '/');
}

/**
 * Create a private directory when missing.
 */
function phpwcms_revision_r565_ensure(string $dir, int $mode = 0700): bool
{
    if (function_exists('phpwcms_storage_ensure')) {
        return phpwcms_storage_ensure($dir, $mode);
    }
    if (!is_dir($dir)) {
        if (!@mkdir($dir, $mode, true) && !is_dir($dir)) {
            return false;
        }
        @chmod($dir, $mode);
    }

    return is_dir($dir) && is_writable($dir);
}

/**
 * Move a file or directory tree without overwriting existing private data.
 */
function phpwcms_revision_r565_move(string $source, string $target): bool
{
    if (is_dir($source) && !is_link($source)) {
        if (file_exists($target) && !is_dir($target)) {
            return false;
        }
        if (!is_dir($target) && !@mkdir($target, 0700, true) && !is_dir($target)) {
            return false;
        }

        $ok = true;
        $entries = @scandir($source);
        if (!is_array($entries)) {
            return false;
        }
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            if (!phpwcms_revision_r565_move($source . '/' . $entry, $target . '/' . $entry)) {
                $ok = false;
            }
        }

        $remaining = @scandir($source);
        if (is_array($remaining) && array_diff($remaining, array('.', '..')) === array()) {
            @rmdir($source);
        }

        return $ok;
    }

    if (file_exists($target)) {
        return false;
    }
    if (!@rename($source, $target)) {
        return false;
    }
    @chmod($target, 0600);

    return true;
}
