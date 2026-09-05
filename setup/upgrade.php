<?php
/**
 * phpwcms — standalone CLI & browser upgrade script
 *
 * Can be uploaded to /setup/upgrade.php or root upgrade.php alongside an existing phpwcms installation.
 * Runs independently on older phpwcms versions using mysqli, pure-PHP DB dump, and ZipArchive.
 *
 * Usage:
 *   CLI:     php setup/upgrade.php   (or php upgrade.php)
 *   Browser: https://your-domain.com/setup/upgrade.php
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 **/

declare(strict_types=1);

@ini_set('display_errors', '1');
@error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
@set_time_limit(0);
@ini_set('memory_limit', '512M');
if (function_exists('ignore_user_abort')) {
    @ignore_user_abort(true);
}

const UPGRADE_VERSION = '2.0.0';
const UPGRADE_REPO    = 'slackero/phpwcms';
const MIN_PHP_VERSION = '8.2.0';

function phpwcms_logo_svg(int $height = 30): string
{
    return '<svg xmlns="http://www.w3.org/2000/svg" height="' . $height . '" viewBox="0 0 415 115" class="align-middle" role="img" aria-label="phpwcms">'
        . '<g fill="#fff"><path d="M19.952 28.371c4.919.08 8.79 1.984 11.613 5.726 2.822 3.726 4.258 8.806 4.322 15.242-.064 6.516-1.5 11.645-4.322 '
        . '15.37-2.823 3.726-6.694 5.63-11.613 5.694-4.775-.032-8.436-1.774-10.968-5.258-2.532-3.468-3.807-8.5-3.823-15.08 0-7.275 1.226-12.71 '
        . '3.678-16.307 2.451-3.597 6.145-5.387 11.113-5.387M5.162 98.774V65.968c2.015 3.258 4.209 5.613 6.612 7.064 2.387 1.436 5.29 2.13 8.694 '
        .' 2.113 6.403-.113 11.467-2.451 15.226-7.048 3.758-4.597 5.677-10.807 5.758-18.662-.097-7.838-2-14.08-5.742-18.71-3.742-4.612-8.79-6.983-'
        . '15.145-7.08-3.404-.032-6.29.645-8.662 2.049-2.387 1.419-4.629 3.758-6.742 7.016v-7.63H0v73.694h5.161zM86.5 73.694V44.387c.016-4.322-.'
        . '161-7.564-.532-9.693-.371-2.13-1.097-3.855-2.162-5.178-1.37-1.87-3.274-3.306-5.71-4.339-2.45-1.016-5.257-1.516-8.45-1.532-3.646-.016-'
        . '6.791.645-9.436 1.984-2.645 1.355-4.904 3.436-6.807 6.258V0h-5.145v73.694h5.145V45.935c.032-5.564 1.42-9.87 4.145-12.919 2.726-3.048 '
        . '6.581-4.597 11.581-4.645 2.29.016 4.323.387 6.097 1.113 1.758.726 3.145 1.774 4.145 3.129.855 1.048 1.403 2.403 1.645 4.08.242 1.662.355 '
        . '4.468.323 8.404v28.597zm29.952-45.323c4.919.08 8.79 1.984 11.613 5.726 2.822 3.726 4.258 8.806 4.306 15.242-.048 6.516-1.484 11.645-4.306 '
        . '15.37-2.823 3.726-6.694 5.63-11.613 5.694-4.775-.032-8.436-1.774-10.968-5.258-2.532-3.468-3.807-8.5-3.823-15.08 0-7.275 1.21-12.71 '
        . '3.662-16.307 2.467-3.597 6.16-5.387 11.129-5.387m-14.79 70.403V65.968c2.015 3.258 4.209 5.613 6.596 7.064 2.403 1.436 5.29 2.13 8.71 '
        . '2.113 6.387-.113 11.467-2.451 15.226-7.048 3.741-4.597 5.677-10.807 5.758-18.662-.097-7.838-2-14.08-5.742-18.71-3.742-4.612-8.79-6.'
        . '983-15.145-7.08-3.404-.032-6.29.645-8.678 2.049-2.37 1.419-4.613 3.758-6.726 7.016v-7.63H96.5v73.694h5.161zM199.532 73.694l15.726-52.'
        . '323h-12.726l-9.403 34.37-9.71-34.37h-11.177l-9.71 34.37-9.21-34.37h-13.016l15.29 52.323h12.21l9.613-34.162 9.92 34.162zm67.548-39.'
        . '129c-2.338-5.113-5.435-8.968-9.322-11.565-3.87-2.597-8.452-3.887-13.726-3.903-7.758.161-14.048 2.838-18.87 8.016-4.84 5.193-7.34 '
        . '11.952-7.485 20.258.146 8.37 2.597 15.177 7.34 20.403 4.741 5.226 10.902 7.92 18.5 8.08 5.483.033 10.144-1.257 13.967-3.87 '
        . '3.822-2.613 7.048-6.726 9.71-12.323l-10.759-5.774c-1.483 3.5-3.241 6.08-5.274 7.742-2.032 1.661-4.403 2.484-7.129 '
        . '2.484-3.935-.08-7.08-1.661-9.42-4.726-2.354-3.08-3.547-7.177-3.612-12.322.065-5.13 1.242-9.226 3.548-12.259 2.29-3.048 '
        . '5.387-4.596 9.275-4.677 2.725-.016 5.048.758 6.983 2.306 1.92 1.565 3.517 3.952 4.807 7.194l11.468-5.064zm4.146-13.'
        . '194v52.323h12.92V48c.015-5.242 1.031-9.274 3.064-12.08 2.016-2.791 4.935-4.21 8.725-4.243 1.468.017 2.775.323 3.936.952a6.685 '
        . '6.685 0 0 1 2.694 2.661c.403.678.725 1.758.919 3.275.21 1.532.322 3.5.322 5.919v29.21h12.484V48c.033-5.242 1.049-9.274 '
        . '3.081-12.08 2.016-2.791 4.92-4.21 8.726-4.243 1.468.017 2.774.323 3.92.952a6.587 6.587 0 0 1 2.693 2.661c.419.678.725 '
        . '1.758.935 3.275.21 1.532.307 3.5.307 5.919v29.21h12.5V36.839c-.017-5.533-1.549-9.758-4.597-12.645-3.049-2.904-7.468-4.355-'
        . '13.274-4.372-3.549-.016-6.646.597-9.275 1.84-2.629 1.241-4.919 3.177-6.87 5.79-1.79-2.742-3.759-4.694-5.92-5.855-2.161-'
        . '1.145-4.951-1.71-8.339-1.678-3.451 0-6.516.549-9.193 1.678-2.678 1.129-5.097 2.87-7.258 5.242V21.37h-12.5zm126.242 '
        . '7.516c-3.162-3.387-6.565-5.855-10.178-7.435-3.629-1.581-7.758-2.371-12.37-2.355-6.485.113-11.775 1.758-15.872 '
        . '4.935-4.096 3.194-6.21 7.29-6.338 12.29-.016 4.226 1.484 7.678 4.484 10.34 2.983 2.677 7.564 4.596 13.693 5.773l7.355 '
        . '1.452c2.645.484 4.468 1.065 5.484 1.742s1.484 1.645 1.451 2.903c-.048 2.033-1.032 3.662-2.935 4.855-1.903 1.21-4.484 '
        . '1.823-7.726 1.855-2.92.016-5.532-.484-7.839-1.516-2.306-1.016-4.564-2.694-6.742-4.984l-9.403 7.42c2.887 3.112 '
        . '6.452 5.5 10.71 7.177 4.258 1.661 9 2.5 14.21 2.516 7-.097 12.677-1.774 17.032-5.065 4.355-3.274 6.597-7.564 '
        . '6.742-12.887.016-4.064-1.242-7.338-3.758-9.822-2.516-2.5-6.387-4.275-11.645-5.355l-8.581-1.758c-3.694-.726-6.21-'
        . '1.516-7.548-2.371-1.323-.855-1.952-2.065-1.871-3.613.032-1.871.854-3.323 2.483-4.339 1.63-1.016 3.904-1.532 '
        . '6.823-1.548 3.258 0 6 .661 8.226 1.984 2.242 1.338 4.048 3.403 5.435 6.177l8.678-8.37z"/></g></svg>';
}

function upgrade_css(): string
{
    return <<<'CSS'
    :root {
        --font-sans: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        --font-mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        --color-primary: #0d6efd;
        --color-primary-hover: #0b5ed7;
        --color-secondary: #6c757d;
        --color-secondary-hover: #5c636a;
        --color-success: #198754;
        --color-danger: #dc3545;
        --color-warning: #ffc107;
        --color-light: #f8f9fa;
        --color-border: #dee2e6;
    }
    *, *::before, *::after {
        box-sizing: border-box;
    }
    body {
        background-color: #f4f6f8;
        font-family: var(--font-sans);
        color: #1e293b;
        line-height: 1.5;
        margin: 0;
        padding: 0;
    }
    a {
        color: var(--color-primary);
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
    .upgrade-card {
        max-width: 840px;
        margin: 40px auto;
        border-radius: 8px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        background: #fff;
        overflow: hidden;
    }
    .upgrade-card-sm {
        max-width: 600px;
    }
    .upgrade-header {
        background-color: #4B6F92;
        color: #fff;
        padding: 16px 24px;
    }
    .upgrade-header.danger {
        background-color: var(--color-danger);
    }
    .upgrade-body {
        padding: 28px;
    }
    h4, h5 {
        margin-top: 0;
        font-weight: 600;
    }
    p {
        margin-top: 0;
        margin-bottom: 1rem;
    }
    .log-terminal {
        background: #1e1e1e;
        color: #e0e0e0;
        font-family: var(--font-mono);
        font-size: 13px;
        border-radius: 6px;
        padding: 15px;
        max-height: 280px;
        overflow-y: auto;
        margin-top: 20px;
        line-height: 1.6;
    }
    code {
        font-family: var(--font-mono);
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9em;
        color: #0f172a;
    }
    .form-label {
        display: inline-block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.95rem;
    }
    .form-control {
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 1rem;
        font-family: inherit;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        border: 1px solid var(--color-border);
        border-radius: 6px;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .form-control:focus {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .form-check {
        display: block;
        min-height: 1.5rem;
        padding-left: 1.6em;
        margin-bottom: 0.5rem;
        position: relative;
    }
    .form-check-input {
        float: left;
        margin-left: -1.6em;
        width: 1.15em;
        height: 1.15em;
        margin-top: 0.3em;
        vertical-align: top;
        background-color: #fff;
        border: 1px solid #adb5bd;
        border-radius: 0.25em;
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }
    .form-check-label {
        cursor: pointer;
    }
    .btn {
        display: inline-block;
        font-family: inherit;
        font-weight: 500;
        line-height: 1.5;
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.45rem 1rem;
        font-size: 0.95rem;
        border-radius: 6px;
        transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out;
    }
    .btn-primary {
        color: #fff;
        background-color: var(--color-primary);
        border-color: var(--color-primary);
    }
    .btn-primary:hover {
        color: #fff;
        background-color: var(--color-primary-hover);
        border-color: var(--color-primary-hover);
        text-decoration: none;
    }
    .btn-success {
        color: #fff;
        background-color: var(--color-success);
        border-color: var(--color-success);
    }
    .btn-success:hover {
        color: #fff;
        background-color: #157347;
        border-color: #146c43;
        text-decoration: none;
    }
    .btn-outline-light {
        color: #f8f9fa;
        border-color: rgba(255, 255, 255, 0.75);
        background-color: transparent;
    }
    .btn-outline-light:hover {
        color: #000;
        background-color: #f8f9fa;
        border-color: #f8f9fa;
        text-decoration: none;
    }
    .btn-outline-secondary {
        color: #5c636a;
        border-color: #6c757d;
        background-color: transparent;
    }
    .btn-outline-secondary:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
        text-decoration: none;
    }
    .btn-link {
        font-weight: 400;
        color: var(--color-primary);
        background-color: transparent;
        border: none;
        padding: 0;
    }
    .btn-link:hover {
        text-decoration: underline;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.825rem;
        border-radius: 4px;
    }
    .btn-lg {
        padding: 0.65rem 1.4rem;
        font-size: 1.05rem;
        border-radius: 6px;
    }
    .btn:disabled, .btn[disabled] {
        opacity: 0.55;
        cursor: not-allowed;
        pointer-events: none;
    }
    .alert {
        position: relative;
        padding: 0.85rem 1.15rem;
        margin-bottom: 1.25rem;
        border: 1px solid transparent;
        border-radius: 6px;
        font-size: 0.95rem;
    }
    .alert-danger {
        color: #842029;
        background-color: #f8d7da;
        border-color: #f5c2c7;
    }
    .alert-warning {
        color: #664d03;
        background-color: #fff3cd;
        border-color: #ffecb5;
    }
    .badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        border-radius: 0.375rem;
    }
    .text-bg-warning {
        color: #000;
        background-color: var(--color-warning);
    }
    .bg-success {
        background-color: var(--color-success);
        color: #fff;
    }
    .bg-light {
        background-color: var(--color-light);
    }
    .border {
        border: 1px solid var(--color-border);
    }
    .border-start {
        border-left: 1px solid var(--color-border);
    }
    .rounded {
        border-radius: 6px;
    }
    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border-radius: 6px;
        overflow: hidden;
    }
    .card-header {
        padding: 0.75rem 1.15rem;
        margin-bottom: 0;
        border-bottom: 1px solid var(--color-border);
    }
    .card-body {
        padding: 1.15rem;
    }
    .d-flex { display: flex; }
    .d-inline { display: inline; }
    .d-inline-block { display: inline-block; }
    .align-items-center { align-items: center; }
    .justify-content-between { justify-content: space-between; }
    .text-end { text-align: right; }
    .text-muted { color: #64748b; }
    .text-primary { color: var(--color-primary); }
    .text-danger { color: var(--color-danger); }
    .text-white { color: #fff; }
    .text-decoration-none { text-decoration: none; }
    .font-weight-bold, .fw-bold { font-weight: 700; }
    .small { font-size: 0.85em; }
    .fs-6 { font-size: 0.9rem; }
    .m-0 { margin: 0; }
    .mb-0 { margin-bottom: 0; }
    .mb-2 { margin-bottom: 0.5rem; }
    .mb-3 { margin-bottom: 1rem; }
    .mb-4 { margin-bottom: 1.5rem; }
    .me-2 { margin-right: 0.5rem; }
    .me-3 { margin-right: 1rem; }
    .ms-2 { margin-left: 0.5rem; }
    .ms-4 { margin-left: 1.5rem; }
    .mt-2 { margin-top: 0.5rem; }
    .mt-3 { margin-top: 1rem; }
    .mt-4 { margin-top: 1.5rem; }
    .p-3 { padding: 1rem; }
    .ps-3 { padding-left: 1rem; }
    .px-0 { padding-left: 0; padding-right: 0; }
    .px-4 { padding-left: 1.5rem; padding-right: 1.5rem; }
    .py-0 { padding-top: 0; padding-bottom: 0; }
    .w-100 { width: 100%; }
CSS;
}

if (version_compare(PHP_VERSION, MIN_PHP_VERSION, '<')) {
    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, "\033[0;31mError: phpwcms requires PHP " . MIN_PHP_VERSION . ' or higher. Your current PHP version is ' . PHP_VERSION . ".\033[0m\n");
    } else {
        http_response_code(500);
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
        echo '<title>PHP Version Error</title><style>' . upgrade_css() . '</style></head><body>';
        echo '<div class="upgrade-card upgrade-card-sm"><div class="upgrade-header danger d-flex align-items-center justify-content-between">';
        echo '<div class="d-flex align-items-center">';
        echo '<span class="me-3">' . phpwcms_logo_svg(28) . '</span>';
        echo '<h4 class="m-0 text-white font-weight-bold">PHP Version Incompatible</h4>';
        echo '</div></div>';
        echo '<div class="upgrade-body"><p>phpwcms requires <strong>PHP ' . htmlspecialchars(MIN_PHP_VERSION) . '</strong> or higher.</p>';
        echo '<p>Your server is currently running PHP <code>' . htmlspecialchars(PHP_VERSION) . '</code>.</p>';
        echo '<p class="mb-0">Please upgrade your PHP environment before running the updater.</p></div></div></body></html>';
    }
    exit(1);
}

// Check archive support (ZipArchive, shell zip/unzip, or shell tar)
$hasZipArchive = class_exists('ZipArchive');
$hasShellZip   = false;
$hasShellUnzip = false;
$hasShellTar   = false;
if (function_exists('shell_exec')) {
    $hasShellZip   = !empty(trim((string)@shell_exec('which zip 2>/dev/null')));
    $hasShellUnzip = !empty(trim((string)@shell_exec('which unzip 2>/dev/null')));
    $hasShellTar   = !empty(trim((string)@shell_exec('which tar 2>/dev/null')));
}

$canExtract = $hasZipArchive || $hasShellUnzip || $hasShellTar;
$canArchive = $hasZipArchive || $hasShellZip || $hasShellTar;

if (!$canExtract || !$canArchive) {
    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, "\033[0;31mError: phpwcms upgrade requires archive support (PHP ZipArchive extension, or system zip/unzip or tar).\033[0m\n");
        fwrite(STDERR, "No compatible archive extraction or creation tools were found. Process stopped.\n");
    } else {
        http_response_code(500);
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
        echo '<title>Archive Support Required</title><style>' . upgrade_css() . '</style></head><body>';
        echo '<div class="upgrade-card upgrade-card-sm"><div class="upgrade-header danger d-flex align-items-center justify-content-between">';
        echo '<div class="d-flex align-items-center"><span class="me-3">' . phpwcms_logo_svg(28) . '</span><h4 class="m-0 text-white font-weight-bold">Archive Support Required</h4></div></div>';
        echo '<div class="upgrade-body"><p>The upgrade process requires <strong>archive support</strong> to create file backups and extract the new release.</p>';
        echo '<p>Neither the PHP <code>ZipArchive</code> extension nor system <code>zip</code> / <code>unzip</code> / <code>tar</code> commands are available on this server.</p>';
        echo '<p class="mb-0">Please enable the PHP <code>zip</code> extension in your PHP configuration before running the updater.</p></div></div></body></html>';
    }
    exit(1);
}

// Determine document root and setup directory
$scriptDir = __DIR__;
if (basename($scriptDir) === 'setup') {
    $docRoot = dirname($scriptDir);
} else {
    $docRoot = realpath($scriptDir) ?: $scriptDir;
}

// Locate config file
$configFile = null;
$configCandidates = [
    $docRoot . '/include/config/conf.inc.php',
    $docRoot . '/config/phpwcms/conf.inc.php', // legacy v1.x path
];

foreach ($configCandidates as $candidate) {
    if (is_file($candidate)) {
        $configFile = $candidate;
        break;
    }
}

$isCli = PHP_SAPI === 'cli';

// Start session for browser mode
if (!$isCli) {
    if (session_status() === PHP_SESSION_NONE) {
        @ini_set('session.cookie_httponly', '1');
        @ini_set('session.cookie_samesite', 'Strict');
        session_start();
    }
    if (empty($_SESSION['upgrade_csrf_token'])) {
        $_SESSION['upgrade_csrf_token'] = bin2hex(random_bytes(24));
    }
}

// -------------------------------------------------------------------------
// Helper functions (CLI & Utilities)
// -------------------------------------------------------------------------

function cli_print(string $text, string $color = ''): void
{
    $colors = [
        'red'    => "\033[0;31m",
        'green'  => "\033[0;32m",
        'yellow' => "\033[1;33m",
        'blue'   => "\033[0;34m",
        'bold'   => "\033[1m",
        'reset'  => "\033[0m",
    ];
    if ($color && isset($colors[$color])) {
        echo $colors[$color] . $text . $colors['reset'];
    } else {
        echo $text;
    }
}

function cli_prompt(string $question, string $default = ''): string
{
    $defHint = $default !== '' ? ' [' . $default . ']' : '';
    cli_print($question . $defHint . ': ', 'bold');
    $input = trim((string)fgets(STDIN));
    return $input !== '' ? $input : $default;
}

function cli_prompt_password(string $question): string
{
    cli_print($question . ': ', 'bold');
    if (PHP_OS_FAMILY === 'Windows') {
        return trim((string)fgets(STDIN));
    }
    $style = shell_exec('stty -g');
    shell_exec('stty -echo');
    $pwd = trim((string)fgets(STDIN));
    shell_exec('stty ' . $style);
    echo "\n";
    return $pwd;
}

function cli_confirm(string $question, bool $default = true): bool
{
    $opts = $default ? '[Y/n]' : '[y/N]';
    cli_print($question . ' ' . $opts . ': ', 'yellow');
    $input = strtolower(trim((string)fgets(STDIN)));
    if ($input === '') {
        return $default;
    }
    return in_array($input, ['y', 'yes', '1'], true);
}

function normalize_version_tag(string $tag): string
{
    $version = str_starts_with($tag, 'v') ? substr($tag, 1) : $tag;
    return preg_match('/^\d+\.\d+\.\d+$/', $version) ? $version : '';
}

function get_installed_phpwcms_version(string $docRoot): string
{
    $revFile = $docRoot . '/include/inc_lib/revision/revision.php';
    $pattern = '/(?:const|define\s*\(\s*[\'\"])\s*PHPWCMS_VERSION[\'\"]?\s*[=,]\s*[\'\"]([^\'\"]+)[\'\"]/i';
    if (is_file($revFile)) {
        $content = (string)@file_get_contents($revFile);
        if (preg_match($pattern, $content, $m)) {
            return trim($m[1]);
        }
    }

    $setupFunc = $docRoot . '/setup/inc/setup.func.inc.php';
    if (is_file($setupFunc)) {
        $content = (string)@file_get_contents($setupFunc);
        if (preg_match($pattern, $content, $m)) {
            return trim($m[1]);
        }
    }

    return 'unknown';
}

// -------------------------------------------------------------------------
// GitHub Release Fetcher
// -------------------------------------------------------------------------

function fetch_latest_release(string $repo = UPGRADE_REPO): array|false
{
    $url = 'https://api.github.com/repos/' . $repo . '/releases/latest';
    $body = false;

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 25,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_PROTOCOLS      => CURLPROTO_HTTPS,
            CURLOPT_USERAGENT      => 'phpwcms-standalone-upgrade/' . UPGRADE_VERSION,
        ]);
        $body = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if ($code !== 200 || !is_string($body)) {
            $body = false;
        }
    }

    if ($body === false && ini_get('allow_url_fopen')) {
        $context = stream_context_create([
            'http' => [
                'timeout'    => 25,
                'user_agent' => 'phpwcms-standalone-upgrade/' . UPGRADE_VERSION,
            ],
            'ssl' => [
                'verify_peer' => true,
            ],
        ]);
        $body = @file_get_contents($url, false, $context);
    }

    if ($body === false) {
        return false;
    }

    $json = json_decode($body, true);
    if (!is_array($json) || empty($json['tag_name'])) {
        return false;
    }

    $tag = (string)$json['tag_name'];
    $version = normalize_version_tag($tag);
    if ($version === '') {
        return false;
    }

    $zipUrl = '';
    if (!empty($json['assets']) && is_array($json['assets'])) {
        foreach ($json['assets'] as $asset) {
            $name = (string)($asset['name'] ?? '');
            if (str_ends_with($name, '.zip')) {
                $zipUrl = (string)($asset['browser_download_url'] ?? '');
                break;
            }
        }
    }
    if ($zipUrl === '' && !empty($json['zipball_url'])) {
        $zipUrl = (string)$json['zipball_url'];
    }

    return [
        'tag'         => $tag,
        'version'     => $version,
        'name'        => (string)($json['name'] ?? $tag),
        'body'        => (string)($json['body'] ?? ''),
        'zip_url'     => $zipUrl,
        'published'   => (string)($json['published_at'] ?? ''),
    ];
}

function download_file(string $url, string $destPath): bool
{
    $dir = dirname($destPath);
    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        return false;
    }

    $fp = @fopen($destPath, 'wb');
    if ($fp === false) {
        return false;
    }

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_FILE           => $fp,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 6,
            CURLOPT_TIMEOUT        => 300,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_USERAGENT      => 'phpwcms-standalone-upgrade/' . UPGRADE_VERSION,
        ]);
        $success = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        fclose($fp);
        return ($success !== false && $code >= 200 && $code < 300);
    }

    if (ini_get('allow_url_fopen')) {
        $context = stream_context_create([
            'http' => [
                'timeout'         => 300,
                'user_agent'      => 'phpwcms-standalone-upgrade/' . UPGRADE_VERSION,
                'follow_location' => 1,
                'max_redirects'   => 6,
            ],
            'ssl' => [
                'verify_peer' => true,
            ],
        ]);
        $src = @fopen($url, 'rb', false, $context);
        if ($src === false) {
            fclose($fp);
            return false;
        }
        while (!feof($src)) {
            $buffer = fread($src, 65536);
            if ($buffer === false) {
                break;
            }
            fwrite($fp, $buffer);
        }
        fclose($src);
        fclose($fp);
        return (filesize($destPath) > 0);
    }

    fclose($fp);
    return false;
}

// -------------------------------------------------------------------------
// Database Connection & Authentication (mysqli)
// -------------------------------------------------------------------------

function get_mysqli_connection(array $conf): mysqli
{
    $host   = $conf['db_host'] ?? 'localhost';
    $user   = $conf['db_user'] ?? '';
    $pass   = $conf['db_pass'] ?? '';
    $name   = $conf['db_table'] ?? '';
    $port   = !empty($conf['db_port']) ? (int)$conf['db_port'] : 3306;
    $socket = !empty($conf['db_socket']) ? (string)$conf['db_socket'] : null;

    $mysqli = mysqli_init();
    $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
    $connected = @$mysqli->real_connect($host, $user, $pass, $name, $port, $socket);

    if (!$connected || $mysqli->connect_errno) {
        throw new RuntimeException('Database connection failed: ' . $mysqli->connect_error . ' (Error ' . $mysqli->connect_errno . ')');
    }

    $charset = !empty($conf['db_charset']) ? $conf['db_charset'] : 'utf8mb4';
    @$mysqli->set_charset($charset);

    return $mysqli;
}

function base32_decode_totp(string $b32): string|false
{
    $lut = array_flip(str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'));
    $b32 = strtoupper(rtrim($b32, "=\r\n\t "));
    $buffer = 0;
    $bitsLeft = 0;
    $out = '';
    for ($i = 0, $len = strlen($b32); $i < $len; $i++) {
        $c = $b32[$i];
        if (!isset($lut[$c])) {
            return false;
        }
        $buffer = ($buffer << 5) | $lut[$c];
        $bitsLeft += 5;
        if ($bitsLeft >= 8) {
            $bitsLeft -= 8;
            $out .= chr(($buffer >> $bitsLeft) & 0xFF);
            $buffer &= ((1 << $bitsLeft) - 1);
        }
    }
    return $out;
}

function verify_totp_code(string $secret, string $code, int $discrepancy = 1): bool
{
    $code = trim($code);
    if (strlen($code) !== 6 || !ctype_digit($code)) {
        return false;
    }
    $key = base32_decode_totp($secret);
    if ($key === false) {
        return false;
    }

    $timeSlice = (int)floor(time() / 30);
    for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
        $binTime = pack('N*', 0) . pack('N*', $timeSlice + $i);
        $hash = hash_hmac('sha1', $binTime, $key, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $truncated = unpack('N', substr($hash, $offset, 4))[1] & 0x7FFFFFFF;
        $calcCode = str_pad((string)($truncated % 1000000), 6, '0', STR_PAD_LEFT);
        if (hash_equals($calcCode, $code)) {
            return true;
        }
    }
    return false;
}

function verify_and_consume_2fa_code(mysqli $mysqli, string $prepend, array $user, string $code): bool
{
    $code = trim($code);
    if ($code === '') {
        return false;
    }

    // 1. Verify 6-digit TOTP
    if (!empty($user['usr_2fa_secret']) && strlen($code) === 6 && ctype_digit($code) && verify_totp_code((string)$user['usr_2fa_secret'], $code)) {
        return true;
    }

    // 2. Verify single-use backup recovery codes
    $userVars = !empty($user['usr_vars']) ? @unserialize($user['usr_vars'], ['allowed_classes' => false]) : [];
    if (is_array($userVars) && !empty($userVars['2fa_backup_codes']) && is_array($userVars['2fa_backup_codes'])) {
        $cleanCode = str_replace('-', '', strtoupper($code));
        foreach ($userVars['2fa_backup_codes'] as $index => $hash) {
            if (password_verify($cleanCode, $hash)) {
                unset($userVars['2fa_backup_codes'][$index]);
                $userVars['2fa_backup_codes'] = array_values($userVars['2fa_backup_codes']);

                $sql = 'UPDATE `' . $mysqli->real_escape_string($prepend) . 'phpwcms_user` SET usr_vars = ? WHERE usr_id = ?';
                $stmt = $mysqli->prepare($sql);
                if ($stmt) {
                    $newVars = serialize($userVars);
                    $uid = (int)$user['usr_id'];
                    $stmt->bind_param('si', $newVars, $uid);
                    $stmt->execute();
                    $stmt->close();
                }
                return true;
            }
        }
    }

    return false;
}

function verify_admin_credentials(mysqli $mysqli, string $prepend, string $username, string $password): array|false
{
    // Sleep to prevent timing attacks / brute force
    usleep(250000);

    $sql = 'SELECT * FROM `' . $mysqli->real_escape_string($prepend) . 'phpwcms_user` WHERE usr_login = ? AND usr_admin = 1 AND usr_aktiv = 1 LIMIT 1';
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res ? $res->fetch_assoc() : null;
    $stmt->close();

    if (!$user) {
        return false;
    }

    $user['usr_2fa_enabled'] = !empty($user['usr_2fa_enabled']);
    $user['usr_2fa_secret']  = (string)($user['usr_2fa_secret'] ?? '');
    $user['usr_vars']        = (string)($user['usr_vars'] ?? '');

    $hash = $user['usr_pass'];
    $valid = false;

    if (str_starts_with($hash, '$')) {
        if (password_verify($password, $hash)) {
            $valid = true;
        } elseif (password_verify(md5($password), $hash)) {
            $valid = true;
        }
    } elseif ($hash === md5($password) || $hash === md5(mb_convert_encoding($password, 'UTF-8', 'ISO-8859-1'))) {
        $valid = true;
    }

    return $valid ? $user : false;
}

// -------------------------------------------------------------------------
// Backup Engine (DB dump & selective files via mysqli & ZipArchive)
// -------------------------------------------------------------------------

function create_db_dump(mysqli $mysqli, string $prepend, string $destPath): bool
{
    $dir = dirname($destPath);
    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        return false;
    }

    $res = $mysqli->query('SHOW TABLES');
    if (!$res) {
        return false;
    }

    $tables = [];
    while ($row = $res->fetch_row()) {
        $table = (string)$row[0];
        if ($prepend === '' || str_starts_with($table, $prepend)) {
            $tables[] = $table;
        }
    }
    $res->free();

    $useGz = str_ends_with($destPath, '.gz') && function_exists('gzopen');
    if ($useGz) {
        $fp = @gzopen($destPath, 'wb9');
        $write = static function (string $text) use ($fp): void {
            gzwrite($fp, $text);
        };
        $close = static function () use ($fp): void {
            gzclose($fp);
        };
    } else {
        $fp = @fopen($destPath, 'wb');
        $write = static function (string $text) use ($fp): void {
            fwrite($fp, $text);
        };
        $close = static function () use ($fp): void {
            fclose($fp);
        };
    }

    if ($fp === false) {
        return false;
    }

    $write("-- phpwcms database backup\n");
    $write('-- Generated: ' . date('Y-m-d H:i:s') . "\n");
    $write('-- Database: ' . $mysqli->character_set_name() . "\n\n");
    $write("SET FOREIGN_KEY_CHECKS=0;\n\n");

    foreach ($tables as $tbl) {
        $cres = $mysqli->query('SHOW CREATE TABLE `' . $mysqli->real_escape_string($tbl) . '`');
        if (!$cres) {
            continue;
        }
        $crow = $cres->fetch_assoc();
        $cres->free();

        $createSql = $crow['Create Table'] ?? '';
        if ($createSql === '') {
            continue;
        }

        $write("DROP TABLE IF EXISTS `$tbl`;\n");
        $write($createSql . ";\n\n");

        $dres = $mysqli->query('SELECT * FROM `' . $mysqli->real_escape_string($tbl) . '`');
        if ($dres) {
            while ($drow = $dres->fetch_assoc()) {
                $vals = [];
                foreach ($drow as $val) {
                    if ($val === null) {
                        $vals[] = 'NULL';
                    } else {
                        $vals[] = "'" . $mysqli->real_escape_string((string)$val) . "'";
                    }
                }
                $write('INSERT INTO `' . $tbl . '` VALUES (' . implode(',', $vals) . ");\n");
            }
            $dres->free();
        }
        $write("\n");
    }

    $write("SET FOREIGN_KEY_CHECKS=1;\n");
    $close();

    return is_file($destPath) && filesize($destPath) > 0;
}

function create_files_backup(string $docRoot, array $conf, string $zipPath, bool $includeArchive, ?callable $progress = null): bool
{
    $dir = dirname($zipPath);
    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        return false;
    }

    if (class_exists('ZipArchive')) {
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        $filePathRel    = trim($conf['file_path'] ?? 'filearchive', '/');
        $contentPathRel = trim($conf['content_path'] ?? 'content', '/');
        $cimagePathRel  = trim($conf['cimage_path'] ?? 'images', '/');

        // Build exclusion rules
        $exclusions = [
            '.git',
            $contentPathRel . '/' . $cimagePathRel, // content/images (resized/cached thumbs)
            $contentPathRel . '/tmp',               // content/tmp
            $contentPathRel . '/cache',             // content/cache
            $contentPathRel . '/rss',               // content/rss
            $contentPathRel . '/backup',            // content/backup (where backups live!)
            'setup/backup',
        ];

        if (!$includeArchive && $filePathRel !== '') {
            $exclusions[] = $filePathRel;
        }

        $totalFiles = 0;
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($docRoot, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }
            $abs = $file->getPathname();
            $rel = ltrim(substr($abs, strlen($docRoot)), '/\\');
            $relNormalized = str_replace('\\', '/', $rel);

            // Check exclusions
            $skip = false;
            foreach ($exclusions as $exc) {
                if ($relNormalized === $exc || str_starts_with($relNormalized, $exc . '/')) {
                    $skip = true;
                    break;
                }
            }

            // Never backup the zip being created
            if ($abs === realpath($zipPath) || (str_ends_with($relNormalized, '.zip') && str_contains($relNormalized, 'backup'))) {
                $skip = true;
            }

            if ($skip) {
                continue;
            }

            $zip->addFile($abs, $relNormalized);
            $totalFiles++;

            if ($progress && ($totalFiles % 250 === 0)) {
                $progress($totalFiles);
            }
        }

        $zip->close();
        return is_file($zipPath) && filesize($zipPath) > 0;
    }

    // Shell zip fallback if available
    if (function_exists('shell_exec')) {
        $realZip = realpath($zipPath) ?: $zipPath;
        $escapedZip = escapeshellarg($realZip);
        $escapedDocRoot = escapeshellarg($docRoot);
        $hasZip = !empty(trim((string)@shell_exec('which zip 2>/dev/null')));
        if ($hasZip) {
            $cmd = "cd $escapedDocRoot && zip -q -r $escapedZip . -x '.git*' 'content/images/*' 'content/tmp/*' 'content/cache/*' 'content/rss/*' 'content/backup/*'";
            if (!$includeArchive && !empty($conf['file_path'])) {
                $cmd .= ' ' . escapeshellarg(trim($conf['file_path'], '/') . '/*');
            }
            @shell_exec($cmd);
            if (is_file($zipPath) && filesize($zipPath) > 0) {
                return true;
            }
        }

        // Shell tar fallback
        $hasTar = !empty(trim((string)@shell_exec('which tar 2>/dev/null')));
        if ($hasTar) {
            $tarFile = preg_replace('/\.zip$/i', '.tar.gz', $zipPath);
            $escapedTar = escapeshellarg($tarFile);
            $cmd = "cd $escapedDocRoot && tar --exclude='.git' --exclude='content/images' --exclude='content/tmp' --exclude='content/cache' --exclude='content/rss' --exclude='content/backup'";
            if (!$includeArchive && !empty($conf['file_path'])) {
                $cmd .= ' --exclude=' . escapeshellarg(trim($conf['file_path'], '/'));
            }
            $cmd .= " -czf $escapedTar .";
            @shell_exec($cmd);
            if (is_file($tarFile) && filesize($tarFile) > 0) {
                // Return true so caller knows backup succeeded
                return true;
            }
        }
    }

    return false;
}

// -------------------------------------------------------------------------
// Upgrade Extraction & Apply
// -------------------------------------------------------------------------

function apply_release_zip(string $zipFile, string $docRoot, array $conf, ?callable $logger = null): array
{
    $log = static function (string $msg) use ($logger) {
        if ($logger) {
            $logger($msg);
        }
    };

    $tempStage = $docRoot . '/content/tmp/upgrade_stage_' . time();
    if (!is_dir($tempStage) && !mkdir($tempStage, 0775, true) && !is_dir($tempStage)) {
        throw new RuntimeException('Cannot create staging directory: ' . $tempStage);
    }

    $log('Extracting release package to staging area...');
    if (class_exists('ZipArchive')) {
        $zip = new ZipArchive();
        if ($zip->open($zipFile) !== true) {
            throw new RuntimeException('Cannot open release zip archive: ' . $zipFile);
        }
        $zip->extractTo($tempStage);
        $zip->close();
    } elseif (function_exists('shell_exec')) {
        $realZip = realpath($zipFile) ?: $zipFile;
        $escapedZip = escapeshellarg($realZip);
        $escapedStage = escapeshellarg($tempStage);
        $hasUnzip = !empty(trim((string)@shell_exec('which unzip 2>/dev/null')));
        if ($hasUnzip) {
            @shell_exec("unzip -q $escapedZip -d $escapedStage");
        } else {
            @shell_exec("tar -xf $escapedZip -C $escapedStage 2>/dev/null");
        }
    } else {
        throw new RuntimeException('No Zip or tar extraction capability found.');
    }

    // Check if zip had a top-level root folder (e.g. phpwcms-2.0.0 or slackero-phpwcms-xxxx)
    $stageRoot = $tempStage;
    if (!is_dir($stageRoot . '/include') && !is_file($stageRoot . '/index.php')) {
        $items = scandir($tempStage);
        if ($items !== false) {
            foreach ($items as $item) {
                if ($item === '.' || $item === '..' || str_starts_with($item, '.')) {
                    continue;
                }
                $sub = $tempStage . '/' . $item;
                if (is_dir($sub) && (is_dir($sub . '/include') || is_file($sub . '/index.php'))) {
                    $stageRoot = $sub;
                    break;
                }
            }
        }
    }

    $filePathRel    = trim($conf['file_path'] ?? 'filearchive', '/');
    $contentPathRel = trim($conf['content_path'] ?? 'content', '/');
    $ftpPathRel     = trim($conf['ftp_path'] ?? 'upload', '/');

    $skipPaths = [
        'include/config/',
        'setup/backup/',
        '.htaccess',
        'robots.txt',
    ];
    if ($filePathRel !== '') {
        $skipPaths[] = $filePathRel . '/';
    }
    if ($contentPathRel !== '') {
        $skipPaths[] = $contentPathRel . '/';
    }
    if ($ftpPathRel !== '') {
        $skipPaths[] = $ftpPathRel . '/';
    }

    $log('Copying upgraded files to document root...');
    $copied = 0;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($stageRoot, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $itemPath = $item->getPathname();
        $rel = ltrim(substr($itemPath, strlen($stageRoot)), '/\\');
        $relNormalized = str_replace('\\', '/', $rel);

        // Check skip rules
        $skip = false;
        foreach ($skipPaths as $p) {
            if ($relNormalized === $p || str_starts_with($relNormalized, $p)) {
                $skip = true;
                break;
            }
        }
        if ($skip) {
            continue;
        }

        $dest = $docRoot . '/' . $relNormalized;
        if ($item->isDir()) {
            if (!is_dir($dest) && !mkdir($dest, 0775, true) && !is_dir($dest)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $dest));
            }
        } else {
            $destDir = dirname($dest);
            if (!is_dir($destDir) && !mkdir($destDir, 0775, true) && !is_dir($destDir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $destDir));
            }
            if (@copy($itemPath, $dest)) {
                $copied++;
            }
        }
    }

    // Clean up staging dir
    $rmdirRecursive = static function ($dir) use (&$rmdirRecursive) {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) as $f) {
            if ($f === '.' || $f === '..') {
                continue;
            }
            $p = $dir . '/' . $f;
            is_dir($p) ? $rmdirRecursive($p) : @unlink($p);
        }
        @rmdir($dir);
    };
    $rmdirRecursive($tempStage);

    $log("Overwrote $copied files cleanly.");
    return ['copied' => $copied];
}

// -------------------------------------------------------------------------
// Trigger DB Revisions / Migrations
// -------------------------------------------------------------------------

function execute_post_upgrade_revisions(string $docRoot, ?callable $logger = null): array
{
    $log = static function (string $msg) use ($logger) {
        if ($logger) {
            $logger($msg);
        }
    };

    $revisionFile = $docRoot . '/include/inc_lib/revision/revision.php';
    if (!is_file($revisionFile)) {
        $log('No revision.php found; skipping DB migrations.');
        return ['success' => true, 'version' => 'unknown'];
    }

    require_once $revisionFile;
    $targetRevision = defined('PHPWCMS_REVISION') ? (int)PHPWCMS_REVISION : 0;
    $targetVersion  = defined('PHPWCMS_VERSION') ? PHPWCMS_VERSION : 'unknown';

    $backendFunc = $docRoot . '/include/inc_lib/backend.functions.inc.php';
    if (is_file($backendFunc)) {
        require_once $backendFunc;
        if (function_exists('phpwcms_revision_check')) {
            $log("Applying database schema migrations up to revision r$targetRevision...");
            $ok = phpwcms_revision_check($targetRevision);
            if ($ok !== false) {
                $log('Database schema revisions updated successfully.');
            } else {
                $errMsg = $GLOBALS['phpwcms']['revision_error'] ?? 'Unknown revision failure';
                $log("Database revision warning: $errMsg");
            }
        }
    }

    return ['success' => true, 'version' => $targetVersion, 'revision' => $targetRevision];
}

// -------------------------------------------------------------------------
// Load existing configuration
// -------------------------------------------------------------------------

if ($configFile === null) {
    if ($isCli) {
        cli_print("Error: Could not locate phpwcms config file in $docRoot/include/config/conf.inc.php or legacy config path.\n", 'red');
        exit(1);
    }

    http_response_code(500);
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>Configuration Error</title><style>' . upgrade_css() . '</style></head><body>';
    echo '<div class="upgrade-card upgrade-card-sm"><div class="upgrade-header danger d-flex align-items-center justify-content-between">';
    echo '<div class="d-flex align-items-center"><span class="me-3">' . phpwcms_logo_svg(28) . '</span><h4 class="m-0 text-white font-weight-bold">Configuration Error</h4></div></div>';
    echo '<div class="upgrade-body"><p>Could not locate the phpwcms configuration file.</p>';
    echo '<p class="mb-0">Expected location: <code>include/config/conf.inc.php</code></p></div></div></body></html>';
    exit(1);
}

$phpwcms = [];
require $configFile;

if (empty($phpwcms['db_table']) || empty($phpwcms['db_user'])) {
    $err = 'Invalid configuration in ' . $configFile;
    if ($isCli) {
        cli_print("Error: $err\n", 'red');
        exit(1);
    }

    http_response_code(500);
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>Configuration Error</title><style>' . upgrade_css() . '</style></head><body>';
    echo '<div class="upgrade-card upgrade-card-sm"><div class="upgrade-header danger d-flex align-items-center justify-content-between">';
    echo '<div class="d-flex align-items-center"><span class="me-3">' . phpwcms_logo_svg(28) . '</span><h4 class="m-0 text-white font-weight-bold">Configuration Error</h4></div></div>';
    echo '<div class="upgrade-body"><p class="mb-0">' . htmlspecialchars($err) . '</p></div></div></body></html>';
    exit(1);
}

$dbPrepend = $phpwcms['db_prepend'] ?? '';
$installedVersion = get_installed_phpwcms_version($docRoot);

// Handle direct backup downloads in browser mode
if (!$isCli && isset($_GET['download']) && !empty($_SESSION['upgrade_authenticated'])) {
    $dl = basename($_GET['download']);
    $backupDir = $docRoot . '/content/backup';
    $file = $backupDir . '/' . $dl;
    if (is_file($file) && (str_ends_with($dl, '.sql.gz') || str_ends_with($dl, '.sql') || str_ends_with($dl, '.zip') || str_ends_with($dl, '.tar.gz'))) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $dl . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit();
    }
    http_response_code(404);
    die('Backup file not found.');
}

// =========================================================================
// CLI MODE EXECUTION
// =========================================================================

if ($isCli) {
    cli_print("\n=========================================================\n", 'blue');
    cli_print(" phpwcms Standalone CLI Updater\n", 'bold');
    cli_print("=========================================================\n\n", 'blue');

    try {
        $mysqli = get_mysqli_connection($phpwcms);
    } catch (Exception $e) {
        cli_print('Database Error: ' . $e->getMessage() . "\n", 'red');
        exit(1);
    }

    cli_print("Detected phpwcms root: $docRoot\n", 'green');
    cli_print("Database connected: {$phpwcms['db_table']} (prefix: '$dbPrepend')\n\n");

    // 1. Admin Authentication
    cli_print("Authentication required (admin credentials of this installation):\n");
    $authenticatedUser = null;
    $attempts = 0;

    while ($attempts < 4) {
        $attempts++;
        $user = cli_prompt('Admin Username');
        $pass = cli_prompt_password('Admin Password');

        $check = verify_admin_credentials($mysqli, $dbPrepend, $user, $pass);
        if ($check !== false) {
            if (!empty($check['usr_2fa_enabled'])) {
                cli_print("Two-Factor Authentication is active for this account.\n", 'yellow');
                $tfaAttempts = 0;
                $tfaOk = false;
                while ($tfaAttempts < 3) {
                    $tfaAttempts++;
                    $twoFactorCode = cli_prompt('2FA Code / Backup Code');
                    if (verify_and_consume_2fa_code($mysqli, $dbPrepend, $check, $twoFactorCode)) {
                        $tfaOk = true;
                        break;
                    }
                    cli_print("Invalid 2FA code or backup code.\n", 'red');
                }
                if (!$tfaOk) {
                    cli_print("2FA verification failed.\n\n", 'red');
                    continue;
                }
            }
            $authenticatedUser = $check;
            cli_print('Welcome, ' . ($check['usr_name'] ?: $check['usr_login']) . "!\n\n", 'green');
            break;
        }

        cli_print("Authentication failed. Invalid username or password.\n", 'red');
    }

    if (!$authenticatedUser) {
        cli_print("Too many failed login attempts. Aborting.\n", 'red');
        exit(1);
    }

    // 2. Fetch Release Info
    cli_print('Checking for latest release from GitHub (' . UPGRADE_REPO . ")...\n");
    $release = fetch_latest_release();
    if ($release === false) {
        cli_print("Failed to fetch latest release metadata from GitHub.\n", 'red');
        exit(1);
    }

    cli_print('Current installed version: ', 'bold');
    cli_print("$installedVersion\n");
    cli_print('Target Release available:  ', 'bold');
    cli_print("v{$release['version']} ({$release['tag']})\n", 'green');
    if (!empty($release['published'])) {
        cli_print('Published at: ' . substr($release['published'], 0, 10) . "\n");
    }
    cli_print("\n");

    if (($installedVersion !== 'unknown') && version_compare($installedVersion, $release['version'], '>=')) {
        cli_print("Error: The target release (v{$release['version']}) is not newer than the currently installed version ($installedVersion).\n", 'red');
        cli_print("Downgrades or overwriting newer versions is not allowed.\n\n");
        exit(1);
    }

    // 3. Backup Confirmation & Options
    cli_print("---------------------------------------------------------\n", 'blue');
    cli_print(" Safety & Backup Confirmation\n", 'bold');
    cli_print("---------------------------------------------------------\n", 'blue');

    $hasExternalBackup = cli_confirm('Do you have a fresh, external backup of your database and files?', false);
    if (!$hasExternalBackup) {
        cli_print("Notice: Proceeding without an external backup is risky.\n", 'yellow');
    }

    $runBackup = cli_confirm('Create full local backup before upgrading? (Recommended)', true);
    $backupFiles = [];

    if ($runBackup) {
        $backupTimestamp = date('Y-m-d-His');
        $backupDir = $docRoot . '/content/backup/' . $backupTimestamp;
        if (!mkdir($backupDir, 0775, true) && !is_dir($backupDir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $backupDir));
        }

        // A. Database Dump
        $hasZlib = function_exists('gzopen');
        $sqlExt = $hasZlib ? '.sql.gz' : '.sql';
        $sqlBackupFile = $backupDir . '/database' . $sqlExt;
        if (!$hasZlib) {
            cli_print("Notice: ext-zlib not found; creating uncompressed SQL dump ($sqlExt)...\n", 'yellow');
        }
        cli_print("Dumping database tables to $sqlBackupFile...\n");
        if (create_db_dump($mysqli, $dbPrepend, $sqlBackupFile)) {
            $size = round(filesize($sqlBackupFile) / 1024, 1);
            cli_print("Database backup completed ($size KB).\n", 'green');
            $backupFiles['db'] = $sqlBackupFile;
        } else {
            cli_print("Database dump failed!\n", 'red');
            if (!cli_confirm('Continue upgrade anyway without database backup?', false)) {
                exit(1);
            }
        }

        // B. Files Backup Options
        $realFileArchive = $phpwcms['file_path'] ?? 'filearchive';
        cli_print("Detected file archive path: '$realFileArchive'\n");
        $includeArchive = cli_confirm("Include '$realFileArchive' (user uploads/media) in zip backup?", false);

        $zipBackupFile = $backupDir . '/files.zip';
        cli_print("Archiving files (excluding rebuildable cache/temporary directories)...\n");

        try {
            $ok = create_files_backup($docRoot, $phpwcms, $zipBackupFile, $includeArchive, static function ($count) {
                cli_print("\rArchived $count files...", 'yellow');
            });
            echo "\n";
            $actualBackupFile = is_file($zipBackupFile) ? $zipBackupFile : $backupDir . '/files.tar.gz';
            if ($ok && is_file($actualBackupFile)) {
                $mb = round(filesize($actualBackupFile) / (1024 * 1024), 2);
                cli_print("File backup completed ($mb MB) at $actualBackupFile\n", 'green');
                $backupFiles['files'] = $actualBackupFile;
            } else {
                cli_print("File backup failed!\n", 'red');
            }
        } catch (Exception $e) {
            cli_print('File backup error: ' . $e->getMessage() . "\n", 'red');
        }

        cli_print("\nLocal backup stored at: $backupDir\n", 'bold');
        if (!cli_confirm('Proceed with upgrading files now?', true)) {
            cli_print("Upgrade cancelled by user. Backups remain intact.\n");
            exit(0);
        }
    } elseif (!cli_confirm('Are you SURE you want to skip backup and overwrite installation files?', false)) {
        cli_print("Upgrade cancelled.\n");
        exit(0);
    }

    // 4. Download & Apply Release
    cli_print("\n---------------------------------------------------------\n", 'blue');
    cli_print(" Upgrade Execution\n", 'bold');
    cli_print("---------------------------------------------------------\n", 'blue');

    $tempZip = $docRoot . '/content/tmp/phpwcms_' . $release['tag'] . '_' . time() . '.zip';
    if (!is_dir(dirname($tempZip)) && !mkdir($concurrentDirectory = dirname($tempZip), 0775, true) && !is_dir($concurrentDirectory)) {
        throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
    }

    cli_print("Downloading release package: {$release['zip_url']}...\n");
    if (!download_file($release['zip_url'], $tempZip)) {
        cli_print("Download failed! Check network connectivity.\n", 'red');
        exit(1);
    }
    cli_print('Downloaded (' . round(filesize($tempZip) / (1024 * 1024), 2) . " MB).\n", 'green');

    cli_print("Applying update...\n");
    try {
        $res = apply_release_zip($tempZip, $docRoot, $phpwcms, static function ($msg) {
            cli_print("  * $msg\n");
        });
        @unlink($tempZip);
    } catch (Exception $e) {
        cli_print('Error applying update: ' . $e->getMessage() . "\n", 'red');
        exit(1);
    }

    // 5. DB Migration
    execute_post_upgrade_revisions($docRoot, static function ($msg) {
        cli_print("  * $msg\n");
    });

    cli_print("\n=========================================================\n", 'green');
    cli_print(" Upgrade to phpwcms {$release['tag']} completed successfully!\n", 'bold');
    cli_print("=========================================================\n\n", 'green');

    $removeScript = cli_confirm('Do you want to delete this upgrade script for security?', true);
    if ($removeScript) {
        @unlink(__FILE__);
        cli_print("Upgrade script removed.\n", 'green');
    }

    exit(0);
}

// =========================================================================
// BROWSER MODE EXECUTION
// =========================================================================

$action = $_POST['action'] ?? '';
$error  = '';
$notice = '';
$mysqli = null;

try {
    $mysqli = get_mysqli_connection($phpwcms);
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Brute-force throttling for browser sessions
if (empty($_SESSION['upgrade_failed_attempts'])) {
    $_SESSION['upgrade_failed_attempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['upgrade_csrf_token'], $csrf)) {
        $error = 'Invalid security token (CSRF). Please reload the page.';
    } elseif ($action === 'login') {
        if ($_SESSION['upgrade_failed_attempts'] >= 5) {
            $error = 'Too many failed login attempts. Please wait a few minutes.';
        } elseif (!$mysqli instanceof mysqli) {
            $error = 'Database connection is not available: ' . ($error ?: 'Unknown error');
        } else {
            $user = trim($_POST['username'] ?? '');
            $pass = trim($_POST['password'] ?? '');
            $chk = verify_admin_credentials($mysqli, $dbPrepend, $user, $pass);
            if ($chk !== false) {
                if (!empty($chk['usr_2fa_enabled'])) {
                    $_SESSION['upgrade_2fa_pending'] = $chk;
                } else {
                    $_SESSION['upgrade_authenticated'] = true;
                    $_SESSION['upgrade_user'] = $chk['usr_name'] ?: $chk['usr_login'];
                    $_SESSION['upgrade_failed_attempts'] = 0;
                    unset($_SESSION['upgrade_2fa_pending']);
                }
            } else {
                $_SESSION['upgrade_failed_attempts']++;
                $error = 'Invalid credentials. Only active administrator accounts can perform upgrades.';
            }
        }
    } elseif ($action === 'verify_2fa') {
        if (empty($_SESSION['upgrade_2fa_pending'])) {
            $error = '2FA session expired. Please log in again.';
        } elseif (!$mysqli instanceof mysqli) {
            $error = 'Database connection is not available.';
        } else {
            $code = trim($_POST['two_factor_code'] ?? '');
            $pendingUser = $_SESSION['upgrade_2fa_pending'];
            if (verify_and_consume_2fa_code($mysqli, $dbPrepend, $pendingUser, $code)) {
                $_SESSION['upgrade_authenticated'] = true;
                $_SESSION['upgrade_user'] = $pendingUser['usr_name'] ?: $pendingUser['usr_login'];
                $_SESSION['upgrade_failed_attempts'] = 0;
                unset($_SESSION['upgrade_2fa_pending']);
            } else {
                $_SESSION['upgrade_failed_attempts']++;
                $error = 'Invalid 2FA code or backup code. Please try again.';
            }
        }
    } elseif ($action === 'cancel_2fa') {
        unset($_SESSION['upgrade_2fa_pending']);
    } elseif ($action === 'logout') {
        unset($_SESSION['upgrade_authenticated'], $_SESSION['upgrade_user'], $_SESSION['upgrade_2fa_pending']);
    }
}

$isAuthenticated = !empty($_SESSION['upgrade_authenticated']);
$releaseInfo = null;
$isUpgradeAllowed = true;
$versionError = '';
if ($isAuthenticated) {
    $releaseInfo = fetch_latest_release();
    if ($releaseInfo !== false && $installedVersion !== 'unknown' && version_compare($installedVersion, $releaseInfo['version'], '>=')) {
        $isUpgradeAllowed = false;
        $versionError = 'The target release (v' . $releaseInfo['version'] . ') is not newer than the currently installed version (' . $installedVersion . '). Downgrades or reinstalling equal/older versions is not permitted.';
    }
}

$realFileArchive = $phpwcms['file_path'] ?? 'filearchive';
$realContentPath = $phpwcms['content_path'] ?? 'content';

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>phpwcms Standalone Upgrade</title>
    <style><?= upgrade_css() ?></style>
</head>
<body>

<div class="upgrade-card">
    <div class="upgrade-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="https://www.phpwcms.org" target="_blank" rel="noopener" class="d-inline-block me-3">
                <?= phpwcms_logo_svg(32) ?>
            </a>
            <span class="badge text-bg-warning mb-2">Standalone Updater</span>
        </div>
        <?php if ($isAuthenticated): ?>
            <div class="text-end small">
                Logged in as <strong><?= htmlspecialchars($_SESSION['upgrade_user'] ?? 'Admin') ?></strong>
                <form method="post" class="d-inline ms-2">
                    <input type="hidden" name="action" value="logout">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['upgrade_csrf_token']) ?>">
                    <button type="submit" class="btn btn-sm btn-outline-light py-0">Logout</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <div class="upgrade-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!$isAuthenticated): ?>
            <?php if (!empty($_SESSION['upgrade_2fa_pending'])): ?>
                <h5 class="mb-3">Two-Factor Authentication</h5>
                <p class="text-muted">Two-Factor Authentication is active for account <strong><?= htmlspecialchars($_SESSION['upgrade_2fa_pending']['usr_name'] ?: $_SESSION['upgrade_2fa_pending']['usr_login']) ?></strong>. Enter your 6-digit authenticator code or a backup recovery code to complete login.</p>

                <form method="post">
                    <input type="hidden" name="action" value="verify_2fa">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['upgrade_csrf_token']) ?>">
                    <div class="mb-3">
                        <label class="form-label" for="two-factor-code">Authentication Code / Backup Code</label>
                        <input type="text" name="two_factor_code" class="form-control" id="two-factor-code" required autofocus autocomplete="one-time-code" placeholder="6-digit code or backup code">
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">Verify Code & Proceed</button>
                    </div>
                </form>
                <form method="post" class="mt-3">
                    <input type="hidden" name="action" value="cancel_2fa">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['upgrade_csrf_token']) ?>">
                    <button type="submit" class="btn btn-sm btn-link text-decoration-none px-0 text-muted">&larr; Back to login</button>
                </form>
            <?php else: ?>
                <h5 class="mb-3">Administrator Authentication Required</h5>
                <p class="text-muted">Enter the administrator username and password for this phpwcms installation to continue.</p>

                <form method="post">
                    <input type="hidden" name="action" value="login">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['upgrade_csrf_token']) ?>">
                    <div class="mb-3">
                        <label class="form-label" for="admin-username">Admin Username</label>
                        <input type="text" name="username" class="form-control" id="admin-username" required autofocus autocomplete="username">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="admin-password">Admin Password</label>
                        <input type="password" name="password" class="form-control" id="admin-password" required autocomplete="current-password">
                    </div>
                    <button type="submit" class="btn btn-primary">Login & Proceed</button>
                </form>
            <?php endif; ?>

        <?php else: ?>

            <?php if ($releaseInfo === false): ?>
                <div class="alert alert-warning">
                    Could not query GitHub for release information. Please verify server internet connectivity.
                </div>
            <?php else: ?>
                <div class="d-flex justify-content-between align-items-center p-3 mb-4 rounded bg-light border">
                    <div>
                        <div class="text-muted small">Target Release Available</div>
                        <h4 class="mb-0 text-primary">v<?= htmlspecialchars($releaseInfo['version']) ?> (<?= htmlspecialchars($releaseInfo['tag']) ?>)</h4>
                        <div class="text-muted small">
                            Installed version: <strong><?= htmlspecialchars($installedVersion) ?></strong>
                            &bull; Published: <?= htmlspecialchars(substr($releaseInfo['published'], 0, 10)) ?>
                        </div>
                    </div>
                    <div>
                        <?php if ($isUpgradeAllowed): ?>
                            <span class="badge bg-success fs-6">Ready to upgrade</span>
                        <?php else: ?>
                            <span class="badge text-bg-warning fs-6">Already Up to Date</span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!$isUpgradeAllowed): ?>
                    <div class="alert alert-warning">
                        <strong>Upgrade Not Permitted:</strong> <?= htmlspecialchars($versionError) ?>
                    </div>
                    <p class="text-muted">
                        Your installation is already at or newer than the latest available release. Downgrades or reinstalling older versions is disallowed to protect your installation and database schema integrity.
                    </p>
                    <div class="mt-3">
                        <a href="../login.php" class="btn btn-primary">Return to Backend</a>
                    </div>
                <?php elseif (isset($_POST['execute_upgrade'])): ?>
                    <?php if (empty($_POST['has_external_backup'])): ?>
                        <div class="alert alert-danger">
                            Please confirm that you have verified an external backup before proceeding.
                        </div>
                    <?php else: ?>
                    <h5>Upgrade In Progress...</h5>
                    <div class="log-terminal" id="logBox">
                        <?php
                        ob_implicit_flush(true);
                        $logWeb = static function ($msg) {
                            echo htmlspecialchars($msg) . "<br>\n";
                            if (ob_get_level() > 0) {
                                ob_flush();
                            }
                            flush();
                        };

                        $doBackup = !empty($_POST['create_backup']);
                        $includeArchive = !empty($_POST['include_filearchive']);
                        $backupDownloadLinks = [];

                        $backupFailed = false;
                        if ($doBackup) {
                            $backupTimestamp = date('Y-m-d-His');
                            $backupDir = $docRoot . '/content/backup';
                            if (!mkdir($backupDir, 0775, true) && !is_dir($backupDir)) {
                                throw new RuntimeException(sprintf('Directory "%s" was not created', $backupDir));
                            }

                            $hasZlib = function_exists('gzopen');
                            $sqlExt = $hasZlib ? '.sql.gz' : '.sql';
                            $sqlFile = "database-$backupTimestamp$sqlExt";

                            $logWeb('Creating database backup' . ($hasZlib ? ' (compressed)...' : ' (uncompressed, ext-zlib missing)...'));
                            if (create_db_dump($mysqli, $dbPrepend, $backupDir . '/' . $sqlFile)) {
                                $logWeb("✓ Database backup created: $sqlFile");
                                $backupDownloadLinks['db'] = $sqlFile;
                            } else {
                                $logWeb('✗ Database backup failed! Aborting upgrade to protect installation.');
                                $backupFailed = true;
                            }

                            if (!$backupFailed) {
                                $logWeb('Creating file backup (excluding cached/temporary images and feeds)...');
                                $zipFile = "files-$backupTimestamp.zip";
                                try {
                                    if (create_files_backup($docRoot, $phpwcms, $backupDir . '/' . $zipFile, $includeArchive)) {
                                        $actualFile = is_file($backupDir . '/' . $zipFile) ? $zipFile : "files-$backupTimestamp.tar.gz";
                                        $logWeb("✓ File backup created: $actualFile");
                                        $backupDownloadLinks['files'] = $actualFile;
                                    } else {
                                        $logWeb('✗ File backup creation failed! Aborting upgrade to protect installation.');
                                        $backupFailed = true;
                                    }
                                } catch (Exception $e) {
                                    $logWeb('✗ ' . $e->getMessage());
                                    $backupFailed = true;
                                }
                            }
                        }

                        if ($backupFailed) {
                            $logWeb('');
                            $logWeb('★ Upgrade aborted because backup creation failed. No installation files were modified.');
                        } else {
                            $tempZip = $docRoot . '/content/tmp/release_' . time() . '.zip';
                            $logWeb('Downloading release zip...');
                            if (!download_file($releaseInfo['zip_url'], $tempZip)) {
                                $logWeb('✗ Failed to download release package from GitHub.');
                            } else {
                                $logWeb('✓ Downloaded release package.');
                                $logWeb('Extracting package and updating files...');
                                try {
                                    $res = apply_release_zip($tempZip, $docRoot, $phpwcms, $logWeb);
                                    $updatedFiles = $res['copied'] ?? 0;
                                    $logWeb("✓ Successfully updated $updatedFiles files.");
                                    @unlink($tempZip);

                                    $logWeb('Applying database revisions...');
                                    execute_post_upgrade_revisions($docRoot, $logWeb);
                                    $logWeb('✓ Database revisions completed.');

                                    $logWeb('');
                                    $logWeb('★ Upgrade successfully finished! You may now return to the phpwcms backend.');
                                } catch (Exception $e) {
                                    $logWeb('✗ Update failed: ' . $e->getMessage());
                                }
                            }
                        }
                        ?>
                    </div>

                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <div>
                            <?php if (!empty($backupDownloadLinks['db'])): ?>
                                <a href="?download=<?= urlencode($backupDownloadLinks['db']) ?>" class="btn btn-outline-secondary btn-sm me-2">
                                    Download DB Backup (<?= htmlspecialchars(pathinfo($backupDownloadLinks['db'], PATHINFO_EXTENSION) === 'gz' ? '.sql.gz' : '.sql') ?>)
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($backupDownloadLinks['files'])): ?>
                                <a href="?download=<?= urlencode($backupDownloadLinks['files']) ?>" class="btn btn-outline-secondary btn-sm">
                                    Download Files Backup (<?= htmlspecialchars(str_ends_with($backupDownloadLinks['files'], '.tar.gz') ? '.tar.gz' : '.zip') ?>)
                                </a>
                            <?php endif; ?>
                        </div>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="action" value="logout">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['upgrade_csrf_token']) ?>">
                            <a href="../login.php" class="btn btn-success">Go to Backend Login</a>
                        </form>
                    </div>
                    <?php endif; ?>

                <?php else: ?>

                    <form method="post">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['upgrade_csrf_token']) ?>">
                        <input type="hidden" name="execute_upgrade" value="1">

                        <div class="card mb-3 border">
                            <div class="card-header bg-light">
                                <strong>1. Safety Checklist</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="checkExternalBackup" name="has_external_backup" value="1">
                                    <label class="form-check-label text-danger fw-bold" for="checkExternalBackup">
                                        I have verified that I have an external or server backup.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4 border">
                            <div class="card-header bg-light">
                                <strong>2. Automated Local Backup Options</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="checkAutoBackup" name="create_backup" value="1" checked>
                                    <label class="form-check-label" for="checkAutoBackup">
                                        <strong>Create full automated backup first</strong> (recommended)<br>
                                        <span class="text-muted small">Creates a database dump (<?= function_exists('gzopen') ? '<code>.sql.gz</code>' : '<code>.sql</code> (uncompressed; ext-zlib unavailable)' ?>) and codebase archive (<?= class_exists('ZipArchive') ? '<code>.zip</code>' : '<code>.tar.gz</code> / <code>.zip</code>' ?>) before overwriting any files.</span>
                                    </label>
                                </div>

                                <div class="ms-4 border-start ps-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkFileArchive" name="include_filearchive" value="1">
                                        <label class="form-check-label" for="checkFileArchive">
                                            Include file archive folder (<code>/<?= htmlspecialchars($realFileArchive) ?></code>)<br>
                                            <span class="text-muted small">Check this only if you want uploaded media in the backup zip. Uncheck to keep backup fast and compact.</span>
                                        </label>
                                    </div>
                                    <div class="text-muted small mt-2">
                                        * Rebuildable cache files, temporary assets, and cached thumbnails in <code>/<?= htmlspecialchars($realContentPath) ?></code>
                                        are automatically excluded from the backup to preserve disk space.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Protected configuration and upload files will never be overwritten.
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg px-4" id="btnStartUpgrade" disabled onclick="return confirm('Ready to start the upgrade?');">
                                Start Upgrade Now
                            </button>
                        </div>
                    </form>

                    <script>
                        (function() {
                            const chk = document.getElementById('checkExternalBackup');
                            const btn = document.getElementById('btnStartUpgrade');
                            if (chk && btn) {
                                chk.addEventListener('change', function() {
                                    btn.disabled = !this.checked;
                                });
                            }
                        })();
                    </script>

                <?php endif; ?>

            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>

</body>
</html>
