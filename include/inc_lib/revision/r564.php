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
 * Revision 564:
 * - Repeat the r563 quarantine with the widened shared extension list, so
 *   multi-extension names such as notes.php.5 and browser-executed formats
 *   are caught on installations that already ran r563.
 * - Quarantine names have every dot replaced by an underscore, so mod_mime
 *   cannot match an embedded extension inside <name>.quarantine, and stay
 *   below NAME_MAX.
 * - The revision folder gets a random suffix, so two runs in the same second
 *   cannot write into the same folder.
 * - index.html is skipped, so the folder protector is never quarantined.
 * - Files are moved, not deleted, and the run is reported through the
 *   revision return message.
 * - Best effort: an unreadable or unwritable folder never fails the revision,
 *   it is reported instead.
 *
 * @return bool
 */
function phpwcms_revision_r564() {

    // Honour a renamed content folder. During the standalone upgrade the
    // default.inc.php constants are not loaded, so fall back to 'content/'.
    $content_path = 'content/';

    if (defined('CONTENT_PATH')) {
        $content_path = CONTENT_PATH;
    } elseif (!empty($GLOBALS['phpwcms']['content_path'])) {
        $content_path = $GLOBALS['phpwcms']['content_path'];
    }

    $content_path = rtrim($content_path, '/') . '/';

    $form_dir = PHPWCMS_ROOT . '/' . $content_path . 'form';

    if (!is_dir($form_dir)) {
        return true;
    }

    $entries = @scandir($form_dir);

    if (!is_array($entries)) {
        return phpwcms_revision_r564_report(array(), array('content/form is not readable'));
    }

    // Shared with form_upload_extension_disallowed() in general.inc.php. The
    // standalone upgrade does not load general.inc.php, so the list lives in a
    // file both can require.
    $extensions_file = PHPWCMS_ROOT . '/include/inc_lib/upload-extensions.inc.php';

    if (!is_file($extensions_file)) {
        $GLOBALS['phpwcms']['revision_return'] = 'Revision r564 cannot run: include/inc_lib/upload-extensions.inc.php is missing. Restore the release files and run the revision again.';

        return false;
    }

    $disallowed_ext = (require $extensions_file)['disallowed'];

    $quarantine_dir = PHPWCMS_ROOT . '/' . $content_path . 'tmp/.form-upload-quarantine/' . date('Ymd-His') . '-' . bin2hex(random_bytes(4));
    $quarantined = array();
    $failed = array();

    foreach ($entries as $entry) {

        if ($entry === '' || $entry[0] === '.' || strtolower($entry) === 'index.html') {
            continue;
        }

        $source = $form_dir . '/' . $entry;

        if (!is_file($source) || is_link($source)) {
            continue;
        }

        // Check every dot separated extension segment: mod_mime can map an
        // extension that is not the last one, so notes.php.5 is as suspect as
        // notes.php.
        $is_disallowed = false;
        $segments = explode('.', strtolower($entry));
        array_shift($segments);

        foreach ($segments as $segment) {
            if (in_array($segment, $disallowed_ext, true)) {
                $is_disallowed = true;
                break;
            }
        }

        if (!$is_disallowed) {
            continue;
        }

        if (!is_dir($quarantine_dir) && !@mkdir($quarantine_dir, 0755, true) && !is_dir($quarantine_dir)) {
            $failed[] = $entry . ' (quarantine folder not writable)';
            continue;
        }

        phpwcms_revision_r564_protect($quarantine_dir);

        // Neutralize dots so mod_mime cannot match an embedded extension such
        // as .php. inside .quarantine, and stay below NAME_MAX (255 bytes).
        $base_name = str_replace('.', '_', $entry);

        if (strlen($base_name) > 200) {
            $base_name = function_exists('mb_strcut') ? mb_strcut($base_name, 0, 200, 'UTF-8') : substr($base_name, 0, 200);
        }

        $target = $quarantine_dir . '/' . $base_name . '.quarantine';
        $counter = 1;

        while (file_exists($target)) {
            $counter++;
            $target = $quarantine_dir . '/' . $base_name . '.' . $counter . '.quarantine';
        }

        if (@rename($source, $target)) {
            $quarantined[] = $entry;
        } elseif (@copy($source, $target)) {
            // content/form and content/tmp can be separate mounts
            if (@unlink($source)) {
                $quarantined[] = $entry;
            } else {
                @unlink($target);
                $failed[] = $entry;
            }
        } else {
            $failed[] = $entry;
        }
    }

    return phpwcms_revision_r564_report($quarantined, $failed, $quarantine_dir);
}

/**
 * Deny web access inside a quarantine folder.
 *
 * The renamed files no longer carry an executable extension, this is the
 * second layer in case a server maps .quarantine.
 *
 * @param string $dir
 */
function phpwcms_revision_r564_protect($dir) {

    $htaccess = $dir . '/.htaccess';

    if (!is_file($htaccess)) {
        @file_put_contents(
            $htaccess,
            "<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n"
            . "<IfModule !mod_authz_core.c>\n    Order allow,deny\n    Deny from all\n</IfModule>\n"
        );
    }

    if (!is_file($dir . '/index.html')) {
        @file_put_contents($dir . '/index.html', '');
    }
}

/**
 * Record the quarantine result in the revision return message and the log.
 *
 * @param array  $quarantined
 * @param array  $failed
 * @param string $quarantine_dir
 *
 * @return bool
 */
function phpwcms_revision_r564_report($quarantined, $failed, $quarantine_dir = '') {

    $messages = array();

    if (count($quarantined)) {
        $summary = 'Quarantined ' . count($quarantined) . ' upload(s) with an executable extension from content/form: '
            . implode(', ', array_slice($quarantined, 0, 20))
            . (count($quarantined) > 20 ? ', ...' : '')
            . '. The files were moved to ' . $quarantine_dir . ' and can be restored after review.';
        $messages[] = $summary;
        phpwcms_revision_r564_log('INFO', $summary);
    }

    if (count($failed)) {
        $summary = 'Could not quarantine ' . count($failed) . ' upload(s) in content/form: '
            . implode(', ', array_slice($failed, 0, 20))
            . '. Remove or move these files manually. The revision does not retry by itself; '
            . 'resolve the cause and remove the r564 revision marker to run it again.';
        $messages[] = $summary;
        phpwcms_revision_r564_log('WARNING', $summary);
        trigger_error('phpwcms r564: ' . $summary, E_USER_WARNING);
    }

    if (count($messages)) {
        $GLOBALS['phpwcms']['revision_return'] = implode("\n", $messages);
    }

    return true;
}

/**
 * Append a line to the quarantine log inside PHPWCMS_TEMP.
 *
 * @param string $level
 * @param string $message
 */
function phpwcms_revision_r564_log($level, $message) {

    if (!defined('PHPWCMS_TEMP')) {
        return;
    }

    @file_put_contents(
        PHPWCMS_TEMP . 'form-upload-quarantine.log',
        date('Y-m-d H:i:s') . ' [' . $level . '] ' . $message . "\n",
        FILE_APPEND
    );
}
