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
 * Revision 563:
 * - Quarantine uploads in content/form that carry an executable extension.
 *   Releases before the stored-name extension check accepted names such as
 *   marker.php. and wrote <time>_marker.php into the web served folder.
 * - Files are moved, not deleted. They are renamed with a .quarantine suffix
 *   into content/tmp/.form-upload-quarantine/<timestamp>/ and the run is
 *   reported through the revision return message, so an administrator can
 *   review and restore them.
 * - Only the top level of content/form is scanned. Dot files such as the
 *   shipped .htaccess and the index.html are kept.
 * - Best effort: an unreadable or unwritable folder never fails the revision,
 *   it is reported instead.
 *
 * @return bool
 */
function phpwcms_revision_r563() {

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
        return phpwcms_revision_r563_report(array(), array('content/form is not readable'));
    }

    // keep in sync with form_upload_extension_disallowed() in general.inc.php
    $disallowed_ext = array(
        'php', 'php2', 'php3', 'php4', 'php5', 'php6', 'php7', 'php8', 'php9',
        'phtml', 'pht', 'phar', 'phps', 'inc',
        'asp', 'aspx', 'cfm', 'cgi', 'pl', 'py', 'sh', 'bash',
        'exe', 'com', 'bat', 'cmd', 'msi', 'bin', 'dll', 'vbs', 'wsf', 'scr',
        'app', 'jar', 'java', 'js',
        'htaccess', 'htpasswd', 'ini', 'env', 'conf', 'bak', 'sql',
        'shtml', 'shtm'
    );

    $quarantine_dir = PHPWCMS_ROOT . '/' . $content_path . 'tmp/.form-upload-quarantine/' . date('Ymd-His');
    $quarantined = array();
    $failed = array();

    foreach ($entries as $entry) {

        if ($entry === '' || $entry[0] === '.') {
            continue;
        }

        $source = $form_dir . '/' . $entry;

        if (!is_file($source) || is_link($source)) {
            continue;
        }

        // Check every dot separated segment: mod_mime can map an extension
        // that is not the last one, so notes.php.5 is as suspect as notes.php.
        $is_disallowed = false;

        foreach (explode('.', strtolower($entry)) as $segment) {
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

        phpwcms_revision_r563_protect($quarantine_dir);

        // Keep the extension readable, but stay below NAME_MAX once the
        // .quarantine suffix is appended.
        $base_name = $entry;

        if (mb_strlen($base_name) > 200) {
            $extension = strrchr($base_name, '.');
            $extension = ($extension === false || $extension === $base_name) ? '' : $extension;
            $base_name = mb_substr($base_name, 0, 200 - mb_strlen($extension)) . $extension;
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

    return phpwcms_revision_r563_report($quarantined, $failed, $quarantine_dir);
}

/**
 * Deny web access inside a quarantine folder.
 *
 * The renamed files no longer carry an executable extension, this is the
 * second layer in case a server maps .quarantine.
 *
 * @param string $dir
 */
function phpwcms_revision_r563_protect($dir) {

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
function phpwcms_revision_r563_report($quarantined, $failed, $quarantine_dir = '') {

    $messages = array();

    if (count($quarantined)) {
        $summary = 'Quarantined ' . count($quarantined) . ' upload(s) with an executable extension from content/form: '
            . implode(', ', array_slice($quarantined, 0, 20))
            . (count($quarantined) > 20 ? ', ...' : '')
            . '. The files were moved to ' . $quarantine_dir . ' and can be restored after review.';
        $messages[] = $summary;
        phpwcms_revision_r563_log('INFO', $summary);
    }

    if (count($failed)) {
        $summary = 'Could not quarantine ' . count($failed) . ' upload(s) in content/form: '
            . implode(', ', array_slice($failed, 0, 20))
            . '. Remove or move these files manually. The revision does not retry by itself; '
            . 'resolve the cause and remove the r563 revision marker to run it again.';
        $messages[] = $summary;
        phpwcms_revision_r563_log('WARNING', $summary);
        trigger_error('phpwcms r563: ' . $summary, E_USER_WARNING);
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
function phpwcms_revision_r563_log($level, $message) {

    if (!defined('PHPWCMS_TEMP')) {
        return;
    }

    @file_put_contents(
        PHPWCMS_TEMP . 'form-upload-quarantine.log',
        date('Y-m-d H:i:s') . ' [' . $level . '] ' . $message . "\n",
        FILE_APPEND
    );
}
