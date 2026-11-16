<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
// PHPWCMS_SETUP is set by the setup wizard, which loads this file before
// PHPWCMS_ROOT exists
if (!defined('PHPWCMS_ROOT') && !defined('PHPWCMS_SETUP')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

/**
 * Resolve an installation-owned config file.
 *
 * These files belong to the installation and are never part of a release or
 * an update package. Only their dist.* templates are shipped and tracked:
 *   conf.indexpage.inc.php
 *   conf.template_default.inc.php
 *   feeds.ini.php
 *   sitemap.custom.ini
 *   sitemap.custom.php
 *   iptc.inc.php
 *
 * The loader is used for the required files (conf.indexpage.inc.php,
 * conf.template_default.inc.php, feeds.ini.php): a missing installation file
 * falls back to its dist template. Optional files (sitemap.custom.*,
 * iptc.inc.php) are checked by their callers with is_file() and never use
 * the dist template.
 *
 * When both files are missing the installation path is returned, so the
 * include failure names the file that is expected to exist.
 *
 * @param string $file File name inside include/config/
 * @return string Absolute path to the installation file or its dist template
 */
function phpwcms_user_config_path($file) {

    // request-scoped: config files are resolved once per request and are not
    // created in the middle of one
    static $cache = array();

    if (isset($cache[$file])) {
        return $cache[$file];
    }

    $dir  = PHPWCMS_ROOT . '/include/config/';
    $path = $dir . $file;

    if (is_file($path)) {
        return $cache[$file] = $path;
    }

    $dist = $dir . 'dist.' . $file;

    if (is_file($dist)) {
        return $cache[$file] = $dist;
    }

    // a broken installation: name the expected file, the include will fail
    static $reported = array();
    if (!isset($reported[$path])) {
        $reported[$path] = true;
        trigger_error('Missing phpwcms config file ' . $path, E_USER_WARNING);
    }

    return $cache[$file] = $path;

}

/**
 * Seed the installation-owned config files from their dist templates.
 *
 * include/config/{conf.indexpage.inc.php,conf.template_default.inc.php,
 * feeds.ini.php,sitemap.custom.ini,sitemap.custom.php} belong to the
 * installation: they are neither shipped in a release nor replaced by an
 * update. A fresh install creates editable copies from the dist.* templates,
 * an existing install keeps its own files untouched.
 *
 * @param string $root phpwcms document root
 * @return array{created: array<int, string>, failed: array<int, string>}
 */
function write_user_config_files($root) {

    $result = array('created' => array(), 'failed' => array());
    // Keep in sync with .gitignore and the excludes in build/make-release.sh:
    // a file seeded here but still shipped in a release would be overwritten
    // by the next update, a gitignored file missing here stays absent forever.
    $files = array(
        'conf.indexpage.inc.php'        => 'dist.conf.indexpage.inc.php',
        'conf.template_default.inc.php' => 'dist.conf.template_default.inc.php',
        'feeds.ini.php'                 => 'dist.feeds.ini.php',
        'sitemap.custom.ini'            => 'dist.sitemap.custom.ini',
        'sitemap.custom.php'            => 'dist.sitemap.custom.php',
    );

    foreach ($files as $file => $template) {
        $target = $root . '/include/config/' . $file;
        $source = $root . '/include/config/' . $template;

        if (is_file($target)) {
            continue;
        }

        if (!is_file($source) || !@copy($source, $target)) {
            $result['failed'][] = $file;
            continue;
        }

        $result['created'][] = $file;
    }

    return $result;
}
