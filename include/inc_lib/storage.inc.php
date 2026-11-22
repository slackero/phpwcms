<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

declare(strict_types=1);

/**
 * Resolve the configured private storage path to an absolute path without a
 * trailing slash. Relative values resolve against the document root. The path
 * is normalised segment by segment, so it is usable before it exists.
 */
function phpwcms_storage_path(string $configured, string $root): string
{
    $configured = trim(str_replace('\\', '/', $configured));
    if ($configured === '') {
        $configured = '../phpwcms-storage';
    }

    $root = phpwcms_storage_normalize($root);
    $is_absolute = str_starts_with($configured, '/')
        || preg_match('#^[A-Za-z]:#', $configured) === 1;

    $path = $is_absolute ? $configured : $root . '/' . $configured;

    return phpwcms_storage_normalize($path);
}

/**
 * Normalise a path: collapse `.` and `..` segments, drop duplicate and
 * trailing slashes, keep a Windows drive prefix.
 */
function phpwcms_storage_normalize(string $path): string
{
    $path = str_replace('\\', '/', $path);
    $prefix = '';
    if (preg_match('#^([A-Za-z]:)(/|$)#', $path, $match) === 1) {
        $prefix = $match[1];
        $path = substr($path, 2);
    }

    $absolute = str_starts_with($path, '/');
    $segments = [];
    foreach (explode('/', $path) as $segment) {
        if ($segment === '' || $segment === '.') {
            continue;
        }
        if ($segment === '..') {
            if ($segments !== [] && end($segments) !== '..') {
                array_pop($segments);
            } elseif (!$absolute) {
                $segments[] = '..';
            }
            continue;
        }
        $segments[] = $segment;
    }

    $normalized = ($absolute ? '/' : '') . implode('/', $segments);
    if ($normalized === '') {
        $normalized = $absolute ? '/' : '.';
    }

    return $prefix . $normalized;
}

/**
 * Create a private directory tree when missing. Returns true only when the
 * directory exists and is writable afterwards.
 */
function phpwcms_storage_ensure(string $dir, int $mode = 0700): bool
{
    if (!is_dir($dir)) {
        if (!@mkdir($dir, $mode, true) && !is_dir($dir)) {
            return false;
        }
        @chmod($dir, $mode);
    }

    return is_dir($dir) && is_writable($dir);
}

/**
 * Require a usable private storage root, or throw with the path and the
 * permission it needs. Never lets a writer fall back into the document root.
 */
function phpwcms_storage_require(?string $root = null): void
{
    if ($root === null) {
        if (!defined('PHPWCMS_STORAGE_ROOT')) {
            throw new RuntimeException(
                'Private storage root is not configured. Set $phpwcms[\'storage_path\'] in include/config/conf.inc.php.'
            );
        }
        $root = (string)PHPWCMS_STORAGE_ROOT;
    }

    $root = rtrim(str_replace('\\', '/', $root), '/');

    if (!is_dir($root)) {
        throw new RuntimeException(sprintf(
            'Private storage root "%s" does not exist. Create it with mode 0700 or set $phpwcms[\'storage_path\'].',
            $root
        ));
    }

    if (!is_writable($root)) {
        throw new RuntimeException(sprintf(
            'Private storage root "%s" is not writable. Grant the PHP user write access with mode 0700 or set $phpwcms[\'storage_path\'].',
            $root
        ));
    }
}
