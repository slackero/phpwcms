<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 * Shared upload extension blocklists. Loaded by form_upload_*() in
 * general.inc.php and by revision r563, which can run without general.inc.php
 * during a standalone upgrade.
 *
 * 'executable' lists extensions a web server executes or reads as
 * configuration and is never configurable. 'disallowed' adds the remaining
 * blocked types and is always a superset of 'executable'.
 *
 * @return array{executable: string[], disallowed: string[]}
 **/

$executable = [
    'php', 'php2', 'php3', 'php4', 'php5', 'php6', 'php7', 'php8', 'php9',
    'phtml', 'phtm', 'pht', 'phar', 'phps', 'phpt', 'inc',
    'cgi', 'fcgi', 'pcgi', 'pl', 'pm',
    'py', 'pyc', 'pyo', 'pyd', 'pyw', 'wsgi',
    'rb', 'rhtml', 'erb', 'ru',
    'sh', 'bash', 'zsh', 'csh', 'ksh', 'fish',
    'asp', 'aspx', 'ashx', 'asmx', 'ascx', 'asa', 'asax', 'cer', 'soap', 'rem', 'axd',
    'jsp', 'jspx', 'jspf', 'jsw', 'jsv',
    'cfm', 'cfc', 'cfml', 'cfr',
    'shtml', 'shtm', 'stm', 'ssi',
    'htaccess', 'htpasswd'
];

$additional = [
    'exe', 'com', 'bat', 'cmd', 'msi', 'msp', 'bin', 'dll',
    'vbs', 'vbe', 'wsf', 'wsh', 'wsc', 'sct', 'scr', 'hta', 'cpl', 'scf',
    'ps1', 'psm1', 'psd1', 'msh', 'msh1', 'msh2', 'mshxml', 'lnk', 'pif', 'reg',
    'app', 'jar', 'java', 'war', 'ear',
    'js', 'jse', 'mjs', 'cjs',
    'ini', 'env', 'conf', 'config', 'bak', 'sql',
    'html', 'htm', 'xhtml', 'xht', 'mhtml', 'mht',
    'svg', 'svgz', 'xml', 'xsl', 'xslt', 'swf'
];

return [
    'executable' => $executable,
    'disallowed' => array_merge($executable, $additional)
];
