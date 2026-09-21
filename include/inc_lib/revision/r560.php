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
 * Revision 560:
 * - Ensure content/form/.htaccess exists and contains hardened rules blocking executable scripts and PHP execution
 *
 * @return bool
 */
function phpwcms_revision_r560() {

    $status = true;

    $htaccess_file = PHPWCMS_ROOT . '/content/form/.htaccess';
    $htaccess_content = '# Deny access to all PHP variants, CGI, scripts, executables, and sensitive files' . PHP_EOL
        . '<FilesMatch "(?i)\.(php[0-9]?|phtml|pht|phar|phps|inc|cgi|pl|py|sh|bash|exe|com|bat|cmd|msi|bin|dll|vbs|wsf|scr|htaccess|htpasswd|ini|env|conf|bak|sql)$">' . PHP_EOL
        . '    <IfModule mod_authz_core.c>' . PHP_EOL
        . '        Require all denied' . PHP_EOL
        . '    </IfModule>' . PHP_EOL
        . '    <IfModule !mod_authz_core.c>' . PHP_EOL
        . '        Order allow,deny' . PHP_EOL
        . '        Deny from all' . PHP_EOL
        . '    </IfModule>' . PHP_EOL
        . '</FilesMatch>' . PHP_EOL . PHP_EOL
        . '# Disable PHP execution engines' . PHP_EOL
        . '<IfModule mod_php.c>' . PHP_EOL
        . '    php_flag engine off' . PHP_EOL
        . '</IfModule>' . PHP_EOL
        . '<IfModule mod_php7.c>' . PHP_EOL
        . '    php_flag engine off' . PHP_EOL
        . '</IfModule>' . PHP_EOL
        . '<IfModule mod_php8.c>' . PHP_EOL
        . '    php_flag engine off' . PHP_EOL
        . '</IfModule>' . PHP_EOL . PHP_EOL
        . '# Prevent MIME type sniffing / execution in browsers' . PHP_EOL
        . '<IfModule mod_headers.c>' . PHP_EOL
        . '    Header set X-Content-Type-Options "nosniff"' . PHP_EOL
        . '</IfModule>' . PHP_EOL;

    if (is_dir(PHPWCMS_ROOT . '/content/form')) {
        if (@file_put_contents($htaccess_file, $htaccess_content) === false) {
            $status = false;
        }
    }

    return $status;
}
