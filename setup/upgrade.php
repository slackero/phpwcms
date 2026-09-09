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
const MIN_INSTALLED_REVISION = 401;

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

/**
 * Detect the install flavour of a doc root: 'cmsgo' when the installation
 * defines CMSGO_* constants (cmsGo! whitelabel fork with its own version
 * schema), otherwise 'phpwcms'.
 */
function get_install_flavour(string $docRoot): string
{
    $cmsgoPattern = '/(?:const|define\s*\(\s*[\'\"])\s*CMSGO_VERSION/i';
    foreach ([
        $docRoot . '/include/inc_lib/revision/revision.php',
        $docRoot . '/setup/inc/setup.func.inc.php',
        $docRoot . '/include/inc_lib/default.inc.php',
    ] as $candidate) {
        if (is_file($candidate) && preg_match($cmsgoPattern, (string)@file_get_contents($candidate))) {
            return 'cmsgo';
        }
    }
    return 'phpwcms';
}

function get_installed_phpwcms_version(string $docRoot): string
{
    // cmsGo! (whitelabel) installs use CMSGO_* constants in the same files
    $patterns = [
        '/(?:const|define\s*\(\s*[\'\"])\s*PHPWCMS_VERSION[\'\"]?\s*[=,]\s*[\'\"]([^\'\"]+)[\'\"]/i',
        '/(?:const|define\s*\(\s*[\'\"])\s*CMSGO_VERSION[\'\"]?\s*[=,]\s*[\'\"]([^\'\"]+)[\'\"]/i',
    ];

    $revFile = $docRoot . '/include/inc_lib/revision/revision.php';
    if (is_file($revFile)) {
        $content = (string)@file_get_contents($revFile);
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $m)) {
                return trim($m[1]);
            }
        }
    }

    $setupFunc = $docRoot . '/setup/inc/setup.func.inc.php';
    if (is_file($setupFunc)) {
        $content = (string)@file_get_contents($setupFunc);
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $m)) {
                return trim($m[1]);
            }
        }
    }

    return 'unknown';
}

function get_installed_phpwcms_revision(string $docRoot): int
{
    // cmsGo! (whitelabel) installs use CMSGO_* constants in the same files
    $patterns = [
        '/(?:const|define\s*\(\s*[\'\"])\s*PHPWCMS_REVISION[\'\"]?\s*[=,]\s*[\'\"]?(\d+)[\'\"]?/i',
        '/(?:const|define\s*\(\s*[\'\"])\s*CMSGO_REVISION[\'\"]?\s*[=,]\s*[\'\"]?(\d+)[\'\"]?/i',
    ];

    $revFile = $docRoot . '/include/inc_lib/revision/revision.php';
    if (is_file($revFile)) {
        $content = (string)@file_get_contents($revFile);
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $m)) {
                return (int)$m[1];
            }
        }
    }

    $setupFunc = $docRoot . '/setup/inc/setup.func.inc.php';
    if (is_file($setupFunc)) {
        $content = (string)@file_get_contents($setupFunc);
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $m)) {
                return (int)$m[1];
            }
        }
    }

    $defaultInc = $docRoot . '/include/inc_lib/default.inc.php';
    if (is_file($defaultInc)) {
        $content = (string)@file_get_contents($defaultInc);
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content, $m)) {
                return (int)$m[1];
            }
        }
    }

    return 0;
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

function upgrade_is_allowed_host(string $host): bool
{
    if (in_array($host, ['github.com', 'objects.githubusercontent.com'], true)) {
        return true;
    }
    return str_ends_with($host, '.github.com');
}

function upgrade_http_probe(string $url): array|false
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_NOBODY         => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT        => 25,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_PROTOCOLS      => CURLPROTO_HTTPS,
            CURLOPT_USERAGENT      => 'phpwcms-standalone-upgrade/' . UPGRADE_VERSION,
        ]);
        curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $errno = curl_errno($ch);
        $location = (string)curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        curl_close($ch);
        if ($errno) {
            return false;
        }
        return [$code, $location];
    }

    if (!ini_get('allow_url_fopen')) {
        return false;
    }

    $context = stream_context_create([
        'http' => [
            'method'          => 'HEAD',
            'timeout'         => 25,
            'user_agent'      => 'phpwcms-standalone-upgrade/' . UPGRADE_VERSION,
            'follow_location' => 0,
            'max_redirects'   => 0,
        ],
        'ssl' => [
            'verify_peer' => true,
        ],
    ]);
    $fp = @fopen($url, 'rb', false, $context);
    if ($fp === false) {
        return false;
    }
    $code = 0;
    $location = '';
    foreach ($http_response_header ?? [] as $header) {
        if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $header, $m)) {
            $code = (int)$m[1];
        }
        if (stripos($header, 'Location:') === 0) {
            $location = trim(substr($header, 9));
        }
    }
    fclose($fp);
    return [$code, $location];
}

function upgrade_resolve_download_url(string $url, bool $restrictHosts = true): string|false
{
    $redirects = 0;
    while (true) {
        if (!str_starts_with($url, 'https://')) {
            return false; // enforce HTTPS
        }
        $host = (string)parse_url($url, PHP_URL_HOST);
        if ($restrictHosts && !upgrade_is_allowed_host($host)) {
            return false;
        }
        $probe = upgrade_http_probe($url);
        if ($probe === false) {
            return false;
        }
        [$code, $location] = $probe;
        if ($location !== '' && in_array($code, [301, 302, 303, 307, 308], true)) {
            if (++$redirects > 5) {
                return false;
            }
            if (preg_match('#^https?://#i', $location)) {
                $url = $location;
            } else {
                $parsed_url = parse_url($url);
                if (!isset($parsed_url['scheme'], $parsed_url['host'])) {
                    return false;
                }
                $url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . (str_starts_with($location, '/') ? '' : '/') . $location;
            }
            continue;
        }
        if ($code !== 200) {
            return false;
        }
        return $url;
    }
}

function download_file(string $url, string $destPath): bool
{
    $finalUrl = upgrade_resolve_download_url($url, true);
    if ($finalUrl === false) {
        return false;
    }

    $dir = dirname($destPath);
    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        return false;
    }

    $fp = @fopen($destPath, 'wb');
    if ($fp === false) {
        return false;
    }

    if (function_exists('curl_init')) {
        $ch = curl_init($finalUrl);
        curl_setopt_array($ch, [
            CURLOPT_FILE           => $fp,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT        => 300,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_PROTOCOLS      => CURLPROTO_HTTPS,
            CURLOPT_USERAGENT      => 'phpwcms-standalone-upgrade/' . UPGRADE_VERSION,
        ]);
        $success = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $errno = curl_errno($ch);
        curl_close($ch);
        fclose($fp);

        if (!$success || $errno !== 0 || $code !== 200) {
            @unlink($destPath);
            return false;
        }
        return (filesize($destPath) > 0);
    }

    if (ini_get('allow_url_fopen')) {
        $context = stream_context_create([
            'http' => [
                'timeout'         => 300,
                'user_agent'      => 'phpwcms-standalone-upgrade/' . UPGRADE_VERSION,
                'follow_location' => 0,
                'max_redirects'   => 0,
            ],
            'ssl' => [
                'verify_peer' => true,
            ],
        ]);
        $src = @fopen($finalUrl, 'rb', false, $context);
        if ($src === false) {
            fclose($fp);
            @unlink($destPath);
            return false;
        }
        $code = 0;
        foreach ($http_response_header ?? [] as $header) {
            if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $header, $m)) {
                $code = (int)$m[1];
            }
        }
        if ($code !== 200) {
            fclose($src);
            fclose($fp);
            @unlink($destPath);
            return false;
        }

        $copied = stream_copy_to_stream($src, $fp);
        fclose($src);
        fclose($fp);

        if ($copied === false || filesize($destPath) <= 0) {
            @unlink($destPath);
            return false;
        }
        return true;
    }

    fclose($fp);
    @unlink($destPath);
    return false;
}

function parse_update_manifest(string $content): array
{
    $manifest = [];
    foreach (preg_split('/\r?\n/', $content) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        $parts = preg_split('/\s+/', $line, 2);
        if (count($parts) === 2 && $parts[1] !== '') {
            $rel = $parts[1];
            // Zip-slip defense: reject traversal ('..' segments), absolute
            // paths (leading '/') and backslash separators so a hostile
            // manifest cannot escape the docroot.
            if (str_starts_with($rel, '/')
                || str_contains($rel, '\\')
                || in_array('..', explode('/', $rel), true)) {
                continue;
            }
            if (str_starts_with($rel, './')) {
                $rel = substr($rel, 2);
            }
            $manifest[$rel] = strtolower($parts[0]);
        }
    }
    return $manifest;
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

function get_upgrade_user_table(mysqli $mysqli, string $prepend): string
{
    $p = $mysqli->real_escape_string($prepend);
    if ($p !== '' && !str_ends_with($p, '_')) {
        $p .= '_';
    }
    $candidate = $p . 'user';
    $res = $mysqli->query("SHOW TABLES LIKE '" . $candidate . "'");
    if ($res && $res->num_rows > 0) {
        $res->free();
        return $candidate;
    }
    if ($res) {
        $res->free();
    }
    $candidate2 = $p . 'phpwcms_user';
    $res2 = $mysqli->query("SHOW TABLES LIKE '" . $candidate2 . "'");
    if ($res2 && $res2->num_rows > 0) {
        $res2->free();
        return $candidate2;
    }
    if ($res2) {
        $res2->free();
    }

    // Last fallback: scan all *_user tables. A whitelabel installation may use a
    // brand prefix other than phpwcms (e.g. {db_prepend}cmsgo_user) while
    // conf.inc.php still carries the default. Pick the table whose prefix
    // matches the $prepend without the trailing default brand part.
    $dbPart = $p;
    if (str_ends_with($dbPart, 'phpwcms_')) {
        $dbPart = substr($dbPart, 0, -strlen('phpwcms_'));
    }
    $res3 = $mysqli->query('SHOW TABLES');
    if ($res3) {
        $fallback = '';
        while ($row = $res3->fetch_row()) {
            $tbl = (string)$row[0];
            if (!str_ends_with($tbl, '_user') || $tbl === $candidate || $tbl === $candidate2) {
                continue;
            }
            $tblPrefix = substr($tbl, 0, -strlen('_user') - 1) . '_';
            if ($tblPrefix === $p) {
                continue; // already handled by the first candidate
            }
            // Prefer a table sharing the non-brand prefix part
            if (str_starts_with($tblPrefix, $dbPart)) {
                $fallback = $tbl;
                break;
            }
            if ($fallback === '' && $dbPart === '') {
                $fallback = $tbl;
            }
        }
        $res3->free();
        if ($fallback !== '') {
            return $fallback;
        }
    }

    return $candidate;
}

/**
 * Detect whether the configured $dbPrepend matches the actual DB tables.
 *
 * Checks if {expected}_user exists. If not, scans all tables for any *_user
 * pattern to infer what prefix is actually in use.
 *
 * Returns an array:
 *   'expected'  => string   — the prepend from conf.inc.php (e.g. 'phpwcms_')
 *   'detected'  => string|null — the prepend inferred from DB tables, or null if undetermined
 *   'mismatch'  => bool     — true when expected != detected
 *   'all_tables'=> string[] — list of all table names in the DB
 */
function detect_actual_db_prepend(mysqli $mysqli, string $expectedPrepend): array
{
    $result = [
        'expected'   => $expectedPrepend,
        'detected'   => null,
        'mismatch'   => false,
        'all_tables' => [],
    ];

    // Fetch all table names
    $res = $mysqli->query('SHOW TABLES');
    if (!$res) {
        return $result;
    }
    while ($row = $res->fetch_row()) {
        $result['all_tables'][] = (string)$row[0];
    }
    $res->free();

    // Check if expected prefix matches any table
    $matchesExpected = false;
    foreach ($result['all_tables'] as $tbl) {
        if ($expectedPrepend === '' || str_starts_with($tbl, $expectedPrepend)) {
            $matchesExpected = true;
            break;
        }
    }

    if ($matchesExpected) {
        // Expected prefix is found — no mismatch
        $result['detected'] = $expectedPrepend;
        return $result;
    }

    // Expected prefix not found — try to detect the real prefix from *_user tables
    // A phpwcms _user table name ends with '_user'; the prefix is everything before that.
    // A shared database may contain *_user tables of other applications (e.g. wp_user),
    // so a candidate is only accepted when its prefix is corroborated by further
    // phpwcms tables of the same prefix (article, structur, content, ...).
    $candidatePrepends = [];
    foreach ($result['all_tables'] as $tbl) {
        if (str_ends_with($tbl, '_user')) {
            $candidatePrepends[] = substr($tbl, 0, -4); // everything up to and including the trailing '_'
        }
    }

    $knownCoreTables = ['article', 'articlecat', 'content', 'structur', 'file', 'userdetail'];
    $detectedPrepend = null;
    foreach ($candidatePrepends as $candidatePrepend) {
        $corroborating = 0;
        foreach ($result['all_tables'] as $tbl) {
            if ($tbl === $candidatePrepend . 'user') {
                continue;
            }
            if (str_starts_with($tbl, $candidatePrepend)
                && in_array(substr($tbl, strlen($candidatePrepend)), $knownCoreTables, true)) {
                $corroborating++;
            }
        }
        // Require at least two known phpwcms core tables under that prefix
        if ($corroborating >= 2) {
            $detectedPrepend = $candidatePrepend;
            break;
        }
    }

    $result['detected'] = $detectedPrepend;
    // A non-empty DB without the expected prefix is a mismatch even when the
    // actual prefix cannot be inferred (no *_user table found).
    $result['mismatch'] = ($detectedPrepend !== null && $detectedPrepend !== $expectedPrepend)
        || ($detectedPrepend === null && count($result['all_tables']) > 0);

    return $result;
}

/**
 * Derive the brand_table_prefix value from a full detected table prefix.
 *
 * Strips the trailing '_' and, when a separate db_prepend part exists,
 * removes that part so only the brand segment remains.
 */
function derive_brand_prefix_from_detected(string $detectedPrepend, string $dbPrependPart): string
{
    $brand = rtrim($detectedPrepend, '_');
    if ($dbPrependPart !== '' && str_starts_with($brand, rtrim($dbPrependPart, '_') . '_')) {
        $brand = substr($brand, strlen(rtrim($dbPrependPart, '_') . '_'));
    }
    return $brand;
}

/**
 * Persist $phpwcms['brand_table_prefix'] to conf.inc.php.
 *
 * Updates the existing brand_table_prefix line. Legacy configs (cmsgo and
 * older forks) have no such line — in that case the line is appended right
 * after the db_prepend line, or at the end of the file when that too is absent.
 *
 * Returns false when the file is not writable or unreadable.
 */
function persist_brand_table_prefix(string $configFile, string $brandPrefix, array $phpwcmsValues = []): bool
{
    if ($brandPrefix === '' || !is_writable($configFile)) {
        return false;
    }
    $conf = file_get_contents($configFile);
    if ($conf === false) {
        return false;
    }
    $conf = preg_replace(
        "/(\\\$phpwcms\['brand_table_prefix'\]\s*=\s*')[^']*(')/",
        '${1}' . $brandPrefix . '${2}',
        $conf,
        -1,
        $replacements
    );
    if ($replacements === 0) {
        // Line missing (legacy config) — insert it after db_prepend, falling
        // back to just before the closing PHP tag or at the end of the file.
        $conf = insert_missing_config_setting($conf, 'brand_table_prefix', $brandPrefix);
    }
    // Seed any remaining missing whitelabel lines so later saves cannot fail
    return file_put_contents($configFile, ensure_whitelabel_config_lines($conf, $phpwcmsValues), LOCK_EX) !== false;
}

/**
 * Insert a $phpwcms[$key] = 'value'; line into a conf.inc.php source string
 * when it does not exist yet. Legacy configs (cmsgo and older forks) lack
 * the whitelabel_key / brand_table_prefix lines — the value is appended
 * right after the db_prepend line, or at the end of the file when that
 * is absent too.
 *
 * Returns the (possibly modified) config source.
 */
function insert_missing_config_setting(string $conf, string $key, string $value): string
{
    $escaped = str_replace(['\\', "'"], ['\\\\', "\\'"], $value);
    if (preg_match("/^\\\$phpwcms\['" . preg_quote($key, '/') . "'\]\s*=\s*'[^']*';/m", $conf)) {
        // Line already exists — persist via preg_replace by the caller is expected
        // to have handled it; append nothing.
        return $conf;
    }
    $line = "\$phpwcms['{$key}'] = '{$escaped}';\n";
    if (preg_match("/^\\\$phpwcms\['db_prepend'\][^\n]*\n/m", $conf, $m, PREG_OFFSET_CAPTURE)) {
        $insertAt = $m[0][1] + strlen($m[0][0]);
        return substr($conf, 0, $insertAt) . $line . substr($conf, $insertAt);
    }
    $close = strrpos($conf, '?' . '>');
    if ($close !== false) {
        return substr($conf, 0, $close) . $line . "\n" . substr($conf, $close);
    }
    return rtrim($conf, "\n") . "\n" . $line;
}

/**
 * Ensure all whitelabel-related config lines exist in a conf.inc.php source
 * string, seeding them from the current in-memory values (or empty defaults)
 * when the lines are missing. Keeps legacy configs (cmsgo and older forks)
 * writable by the upgrade's whitelabel handling without failing later.
 *
 * Returns the (possibly modified) config source.
 */
function ensure_whitelabel_config_lines(string $conf, array $phpwcmsValues): string
{
    foreach (['whitelabel_key', 'brand_table_prefix'] as $key) {
        if (preg_match("/^\\\$phpwcms\['" . preg_quote($key, '/') . "'\]\s*=\s*'[^']*';/m", $conf)) {
            continue;
        }
        $conf = insert_missing_config_setting($conf, $key, (string)($phpwcmsValues[$key] ?? ''));
    }
    return $conf;
}

/**
 * Migrate a legacy cmsgo conf.inc.php to the phpwcms $phpwcms array format.
 *
 * Rewrites the file in place: $cmsgo[...] keys become $phpwcms[...] (names match 1:1),
 * expressions built on $_SERVER['SERVER_NAME'] (undefined in CLI) are reset to ''
 * so the new release auto-configures them, and $_SERVER['DOCUMENT_ROOT'] is
 * replaced by the concrete path. The original file is preserved next to it as
 * conf.inc.cmsgo-backup.php (keeps the .php suffix so the web server never
 * serves its source). Returns true if the file was rewritten.
 */
function migrate_cmsgo_config(string $configFile, string $docRoot): bool
{
    if (!is_writable($configFile)) {
        return false;
    }
    $conf = file_get_contents($configFile);
    if ($conf === false || !preg_match('/\$cmsgo\[/', $conf)) {
        return false;
    }

    // Keep the original cmsgo config around — keep the .php suffix so the
    // web server keeps executing it instead of serving the source (credentials)
    $backupFile = str_replace('conf.inc.php', 'conf.inc.cmsgo-backup.php', $configFile);
    if ($backupFile !== $configFile && !is_file($backupFile)) {
        // The original holds DB credentials — never rewrite the config
        // when the safety backup cannot be created.
        if (file_put_contents($backupFile, $conf, LOCK_EX) === false) {
            return false;
        }
    }

    $conf = str_replace('$cmsgo[', '$phpwcms[', $conf);
    $conf = preg_replace(
        "#'(https?)://'\s*\.\s*\\\$_SERVER\['SERVER_NAME'\]\s*\.\s*'/'#",
        "''",
        $conf
    );
    $conf = str_replace("\$_SERVER['DOCUMENT_ROOT']", var_export($docRoot, true), $conf);

    // Reset remaining broken host expressions to '' (auto-configure)
    $conf = preg_replace("#(\\\$phpwcms\['site(?:_ssl_url)?'\]\s*=\s*)'https?:///'#", "\${1}''", $conf);

    return file_put_contents($configFile, $conf, LOCK_EX) !== false;
}

/**
 * Validate a signed whitelabel license key (sodium Ed25519 detached signature).
 *
 * Expects "base64url(payload).base64url(signature)".
 * Returns the decoded payload array on success, false on any failure
 * (malformed key, bad signature, expired license, missing sodium extension).
 */
function setup_validate_whitelabel_key(string $key): array|false
{
    $key = trim($key);
    if ($key === '' || !function_exists('sodium_crypto_sign_verify_detached') || !function_exists('sodium_hex2bin')) {
        return false;
    }
    $parts = explode('.', $key);
    if (count($parts) !== 2) {
        return false;
    }
    $b64d = static function (string $d): string {
        $r = strlen($d) % 4;
        if ($r) {
            $d .= str_repeat('=', 4 - $r);
        }
        return (string)base64_decode(strtr($d, '-_', '+/'));
    };
    $pj = $b64d($parts[0]);
    $sg = $b64d($parts[1]);
    $sb = defined('SODIUM_CRYPTO_SIGN_BYTES') ? SODIUM_CRYPTO_SIGN_BYTES : 64;
    if (!$pj || strlen($sg) !== $sb) {
        return false;
    }
    $pk = sodium_hex2bin('f1a161b32dc0b778911c0eba897a320b0ad4b776695aa3f9e773e18753d3d76a');
    if (!sodium_crypto_sign_verify_detached($sg, $pj, $pk)) {
        return false;
    }
    $payload = json_decode($pj, true);
    if (!is_array($payload)) {
        return false;
    }
    if (!empty($payload['valid_until']) && (int)$payload['valid_until'] > 0 && time() > (int)$payload['valid_until']) {
        return false;
    }
    return $payload;
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

                $tableName = get_upgrade_user_table($mysqli, $prepend);
                $sql = 'UPDATE `' . $tableName . '` SET usr_vars = ? WHERE usr_id = ?';
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

    $tableName = get_upgrade_user_table($mysqli, $prepend);
    $sql = 'SELECT * FROM `' . $tableName . '` WHERE usr_login = ? AND usr_admin = 1 AND usr_aktiv = 1 LIMIT 1';
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

function apply_release_zip(
    string $zipFile,
    string $docRoot,
    array $conf,
    ?callable $logger = null,
    bool $allowUnverified = false,
    string $expectedVersion = ''
): array {
    $log = static function (string $msg) use ($logger) {
        if ($logger) {
            $logger($msg);
        }
    };

    $tempStage = $docRoot . '/content/tmp/upgrade_stage_' . time();
    if (!is_dir($tempStage) && !mkdir($tempStage, 0775, true) && !is_dir($tempStage)) {
        throw new RuntimeException('Cannot create staging directory: ' . $tempStage);
    }

    $cleanupStage = static function () use ($tempStage) {
        if (!is_dir($tempStage)) {
            return;
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($tempStage, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $item) {
            if ($item->isDir() && !$item->isLink()) {
                @rmdir($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }
        @rmdir($tempStage);
    };

    try {
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

        // Package validity / Manifest verification
        $manifestPath = $stageRoot . '/.update-manifest';
        $manifestVerified = false;

        if (is_file($manifestPath)) {
            $log('Verifying package integrity via .update-manifest...');
            $manifestContent = (string)@file_get_contents($manifestPath);
            $manifest = parse_update_manifest($manifestContent);
            if (empty($manifest)) {
                throw new RuntimeException('Package .update-manifest is empty or invalid.');
            }

            // Version check in revision.php if available
            $revisionFile = $stageRoot . '/include/inc_lib/revision/revision.php';
            if (is_file($revisionFile)) {
                $revContent = (string)@file_get_contents($revisionFile);
                if (preg_match('/(?:const|define\s*\(\s*[\'"])\s*PHPWCMS_VERSION[\'"]?\s*[=,]\s*[\'"]([^\'"]+)[\'"]/i', $revContent, $m)) {
                    $stagedVersion = trim($m[1]);
                    if ($expectedVersion !== '' && $stagedVersion !== $expectedVersion) {
                        throw new RuntimeException("Version mismatch: package has version $stagedVersion, expected $expectedVersion.");
                    }
                }
            }

            // Verify SHA-256 for all files in the manifest
            $verifiedCount = 0;
            foreach ($manifest as $rel => $expectedHash) {
                $stagedFile = $stageRoot . '/' . $rel;
                if (!is_file($stagedFile)) {
                    throw new RuntimeException('Package verification failed: missing file ' . $rel);
                }
                $actualHash = strtolower((string)hash_file('sha256', $stagedFile));
                if ($actualHash !== $expectedHash) {
                    throw new RuntimeException('Package verification failed: SHA-256 checksum mismatch for ' . $rel);
                }
                $verifiedCount++;
            }
            $log("✓ Verified SHA-256 checksums for $verifiedCount files against .update-manifest.");
            $manifestVerified = true;
        } else {
            if (!$allowUnverified) {
                throw new RuntimeException(
                    'Package verification failed: The release package does not contain an update manifest (.update-manifest) with SHA-256 checksums. ' .
                    'By default, unverified packages cannot be installed. If upgrading an older release (< 2.0.0), you must explicitly enable the legacy package override.'
                );
            }
            $log('⚠ NOTICE: Release package has no .update-manifest. SHA-256 checksum verification is bypassed via legacy override.');
        }

        $filePathRel    = trim($conf['file_path'] ?? 'filearchive', '/');
        $contentPathRel = trim($conf['content_path'] ?? 'content', '/');
        $ftpPathRel     = trim($conf['ftp_path'] ?? 'upload', '/');

        $skipPaths = [
            'include/config/',
            'setup/backup/',
            'setup/setup.conf.inc.php',
            '.htaccess',
            'robots.txt',
            '.update-manifest',
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

        // If package included a verified manifest, copy it to docroot for future updater runs
        if ($manifestVerified && is_file($manifestPath)) {
            @copy($manifestPath, $docRoot . '/.update-manifest');
        }

        $log("Overwrote $copied files cleanly.");
        return ['copied' => $copied, 'manifest_verified' => $manifestVerified];
    } finally {
        $cleanupStage();
    }
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

if (defined('UPGRADE_SCRIPT_TEST_MODE')) {
    return;
}

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

/** @var array<string, mixed> $phpwcms */
// Legacy cmsgo conf.inc.php files reference $_SERVER['SERVER_NAME'] / DOCUMENT_ROOT,
// which are undefined in CLI mode and would raise warnings.
$_prevErrorLevel = error_reporting();
error_reporting($_prevErrorLevel & ~(E_WARNING | E_DEPRECATED | E_NOTICE));
require $configFile;
error_reporting($_prevErrorLevel);

// Legacy cmsgo installations define a $cmsgo array instead of $phpwcms.
// Key names match phpwcms 1:1 (db_host, db_user, db_pass, db_table, db_prepend, ...),
// so map it the same way the DB table prefix detection already tolerates cmsgo tables.
if (empty($phpwcms) && !empty($cmsgo) && is_array($cmsgo)) {
    /** @var array<string, mixed> $cmsgo */
    $phpwcms = $cmsgo;

    // Values built from $_SERVER are broken/empty in CLI context —
    // reset them so the auto-configuration of the new release kicks in.
    if (isset($phpwcms['site']) && preg_match('#^https?:///$#', (string) $phpwcms['site'])) {
        $phpwcms['site'] = '';
    }
    if (isset($phpwcms['site_ssl_url']) && preg_match('#^https?:///$#', (string) $phpwcms['site_ssl_url'])) {
        $phpwcms['site_ssl_url'] = '';
    }
    if (empty($phpwcms['DOC_ROOT'])) {
        $phpwcms['DOC_ROOT'] = $docRoot;
    }

    // Persist the migration so the config is phpwcms-native from now on
    $cmsgoBackupFile = str_replace('conf.inc.php', 'conf.inc.cmsgo-backup.php', $configFile);
    if (migrate_cmsgo_config($configFile, $docRoot)) {
        $cmsgoMigrationNotice = 'Your legacy cmsGo! configuration file was migrated to the current '
            . 'phpwcms format automatically. The original file was preserved as '
            . '<code>' . htmlspecialchars(basename($cmsgoBackupFile)) . '</code> in the same directory.';
        if ($isCli) {
            cli_print("Legacy cmsgo config migrated to phpwcms format in $configFile\n", 'green');
            if (is_file($cmsgoBackupFile)) {
                cli_print("Original cmsgo config preserved as $cmsgoBackupFile\n", 'green');
            }
        }
    } else {
        $cmsgoMigrationNotice = 'Your legacy cmsGo! configuration file could not be updated automatically. '
            . 'The upgrade continues with migrated settings in memory, but please update '
            . '<code>include/config/conf.inc.php</code> manually: rename <code>$cmsgo</code> to '
            . '<code>$phpwcms</code> and replace <code>$_SERVER[\'SERVER_NAME\']</code> / '
            . '<code>$_SERVER[\'DOCUMENT_ROOT\']</code> with static values.';
        // Non-fatal: keep running with the in-memory mapping
        if ($isCli) {
            cli_print("Warning: Could not rewrite legacy cmsgo config $configFile - using mapped values in memory only.\n", 'yellow');
        }
    }
}

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

$_brand_prefix = !empty($phpwcms['brand_table_prefix']) ? preg_replace('/[^a-zA-Z0-9_]/', '', (string)$phpwcms['brand_table_prefix']) : 'phpwcms';
if ($_brand_prefix === '') {
    $_brand_prefix = 'phpwcms';
}
$dbPrepend = (!empty($phpwcms['db_prepend']) ? rtrim((string)$phpwcms['db_prepend'], '_') . '_' : '') . $_brand_prefix . '_';
$installedVersion = get_installed_phpwcms_version($docRoot);
$installedRevision = get_installed_phpwcms_revision($docRoot);
// cmsGo! installs use their own version schema — only the revision is
// comparable with the phpwcms release line, so version comparisons are
// bypassed for them (see the isSameVersion / isDowngrade handling).
$isCmsgoInstall = get_install_flavour($docRoot) === 'cmsgo';

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
        // If the user table was resolved via fallback (whitelabel prefix),
        // hint at the brand_table_prefix that likely needs to be configured.
        $cli_hint_table = get_upgrade_user_table($mysqli, $dbPrepend);
        if ($cli_hint_table !== $dbPrepend . 'user' && $cli_hint_table !== $dbPrepend . 'phpwcms_user') {
            $cli_hint_prefix = rtrim(substr($cli_hint_table, 0, -strlen('_user')), '_');
            cli_print("Note: user accounts were found in '$cli_hint_table' — this looks like a whitelabel installation.\n");
            cli_print("Set brand_table_prefix to '$cli_hint_prefix' in conf.inc.php and re-run.\n\n", 'yellow');
        }
    }

    if (!$authenticatedUser) {
        cli_print("Too many failed login attempts. Aborting.\n", 'red');
        exit(1);
    }

    // 2. Whitelabel License — asked BEFORE the DB table prefix verification,
    // so a whitelabel license (and its brand_table_prefix) can be entered
    // before the prefix mismatch check runs.
    cli_print("---------------------------------------------------------\n", 'blue');
    cli_print(" Whitelabel License\n", 'bold');
    cli_print("---------------------------------------------------------\n", 'blue');

    $cli_wl_sodium = function_exists('sodium_crypto_sign_verify_detached') && function_exists('sodium_hex2bin');

    if (!$cli_wl_sodium) {
        cli_print("Note: PHP sodium extension not available — whitelabel license management skipped.\n\n", 'yellow');
    } else {
        $cli_validate_wl_key = 'setup_validate_whitelabel_key';

        $cli_current_key    = trim($phpwcms['whitelabel_key'] ?? '');
        $cli_current_prefix = $phpwcms['brand_table_prefix'] ?? '';
        $cli_current_payload = $cli_current_key !== '' ? $cli_validate_wl_key($cli_current_key) : false;

        if ($cli_current_key === '') {
            cli_print("No whitelabel license configured (standard open-source mode).\n");
        } elseif ($cli_current_payload !== false) {
            cli_print("Current license: VALID", 'green');
            $cli_wl_info = [];
            if (!empty($cli_current_payload['licensee'])) {
                $cli_wl_info[] = 'Licensee: ' . $cli_current_payload['licensee'];
            }
            if (!empty($cli_current_payload['domain'])) {
                $cli_wl_info[] = 'Domain: ' . $cli_current_payload['domain'];
            }
            if (!empty($cli_current_payload['valid_until']) && (int)$cli_current_payload['valid_until'] > 0) {
                $cli_wl_info[] = 'Expires: ' . date('Y-m-d', (int)$cli_current_payload['valid_until']);
            }
            if (!empty($cli_current_payload['brand_table_prefix'])) {
                $cli_wl_info[] = 'Prefix: ' . $cli_current_payload['brand_table_prefix'];
            }
            if ($cli_wl_info) {
                cli_print(' — ' . implode(' | ', $cli_wl_info));
            }
            cli_print("\n");
            cli_print('Configured brand_table_prefix: ' . ($cli_current_prefix !== '' ? $cli_current_prefix : '(empty, defaults to phpwcms)') . "\n");
        } else {
            cli_print("Current license: INVALID or expired\n", 'red');
        }

        cli_print("\n");
        if (cli_confirm('Update whitelabel license settings?', false)) {
            $cli_new_key = '';
            $cli_new_payload = false;
            $cli_key_attempts = 0;
            while ($cli_key_attempts < 3) {
                $cli_key_attempts++;
                $cli_new_key = cli_prompt('License Key (leave blank to clear)');
                $cli_new_key = trim($cli_new_key);
                if ($cli_new_key === '') {
                    $cli_new_payload = false;
                    cli_print("License key cleared.\n", 'yellow');
                    break;
                }
                $cli_new_payload = $cli_validate_wl_key($cli_new_key);
                if ($cli_new_payload !== false) {
                    cli_print("License key valid.\n", 'green');
                    break;
                }
                cli_print("Invalid or expired license key. Please try again.\n", 'red');
            }

            if ($cli_key_attempts >= 3 && $cli_new_key !== '' && $cli_new_payload === false) {
                cli_print("Whitelabel license update skipped after too many failed attempts.\n\n", 'yellow');
            } else {
                $cli_new_prefix = null;
                if ($cli_new_payload !== false) {
                    // Empty prefix falls back to phpwcms. Suggest a non-default value
                    // from existing config or the license payload.
                    $cli_prefix_suggestion = '';
                    if ($cli_current_prefix !== '' && $cli_current_prefix !== 'phpwcms') {
                        $cli_prefix_suggestion = $cli_current_prefix;
                    } elseif (!empty($cli_new_payload['brand_table_prefix']) && $cli_new_payload['brand_table_prefix'] !== 'phpwcms') {
                        $cli_prefix_suggestion = $cli_new_payload['brand_table_prefix'];
                    }
                    $cli_prefix_input = cli_prompt('Brand Table Prefix (leave blank for default phpwcms' . ($cli_prefix_suggestion !== '' ? ", suggestion: $cli_prefix_suggestion" : '') . ')');
                    $cli_new_prefix = preg_replace('/[^a-zA-Z0-9_]/', '', trim($cli_prefix_input));
                    // 'phpwcms' is the default anyway — store empty instead
                    if ($cli_new_prefix === 'phpwcms') {
                        $cli_new_prefix = '';
                    }
                }

                // Write to conf.inc.php. Clearing the license key leaves the
                // configured brand_table_prefix untouched (no silent wipe).
                if ($configFile && is_writable($configFile)) {
                    $cli_conf = file_get_contents($configFile);
                    if ($cli_conf !== false) {
                        $cli_esc_key = str_replace(['\\', "'"], ['\\\\', "\\'"], $cli_new_key);
                        $cli_conf = preg_replace(
                            "/(\\\$phpwcms\['whitelabel_key'\]\s*=\s*')[^']*(')/",
                            '${1}' . $cli_esc_key . '${2}',
                            $cli_conf,
                            -1,
                            $cli_key_replacements
                        );
                        $cli_prefix_replacements = 0;
                        if ($cli_new_prefix !== null) {
                            $cli_conf = preg_replace(
                                "/(\\\$phpwcms\['brand_table_prefix'\]\s*=\s*')[^']*(')/",
                                '${1}' . $cli_new_prefix . '${2}',
                                $cli_conf,
                                -1,
                                $cli_prefix_replacements
                            );
                        }
                        // Legacy configs lack the whitelabel_key / brand_table_prefix
                        // lines — insert them instead of failing.
                        if ($cli_key_replacements === 0) {
                            $cli_conf = insert_missing_config_setting($cli_conf, 'whitelabel_key', $cli_new_key);
                        }
                        if ($cli_new_prefix !== null && $cli_prefix_replacements === 0) {
                            $cli_conf = insert_missing_config_setting($cli_conf, 'brand_table_prefix', $cli_new_prefix);
                        }
                        if (file_put_contents($configFile, $cli_conf, LOCK_EX) !== false) {
                            $phpwcms['whitelabel_key'] = $cli_new_key;
                            if ($cli_new_prefix !== null) {
                                $phpwcms['brand_table_prefix'] = $cli_new_prefix;
                            }
                            // Recompute $dbPrepend with updated values
                            $cli_active_prefix = ($phpwcms['brand_table_prefix'] ?? '') !== '' ? $phpwcms['brand_table_prefix'] : 'phpwcms';
                            $dbPrepend = (!empty($phpwcms['db_prepend']) ? rtrim((string)$phpwcms['db_prepend'], '_') . '_' : '') . $cli_active_prefix . '_';
                            cli_print("Whitelabel license settings saved to $configFile\n", 'green');
                            cli_print("Active brand_table_prefix: $cli_active_prefix\n\n");
                        } else {
                            cli_print("Error: Could not write to $configFile\n\n", 'red');
                        }
                    } else {
                        cli_print("Error: Could not read $configFile\n\n", 'red');
                    }
                } else {
                    cli_print("Error: $configFile is not writable. Settings not saved.\n\n", 'red');
                }
            }
        } else {
            cli_print("\n");
        }
    }

    // 3. DB Table Prefix Verification
    $cli_prepend_check = detect_actual_db_prepend($mysqli, $dbPrepend);
    if ($cli_prepend_check['mismatch']) {
        $cli_detected = $cli_prepend_check['detected'];
        cli_print("---------------------------------------------------------\n", 'red');
        cli_print(" DB Table Prefix Mismatch Detected\n", 'bold');
        cli_print("---------------------------------------------------------\n", 'red');
        cli_print("Expected prefix (from conf.inc.php): '$dbPrepend'\n");
        cli_print("Detected prefix (from DB tables):    '$cli_detected'\n\n", 'yellow');
        cli_print("The configured brand_table_prefix does not match the actual database tables.\n");
        cli_print("Proceeding would backup and migrate the wrong (or no) tables.\n\n");
        // Auto-preserve legacy/whitelabel prefixes (e.g. cmsgo from older versions).
        // Prefer the prefix from a valid whitelabel license (the license step above
        // lets the user enter it before this check); fall back to the detected tables.
        $cli_db_part = (!empty($phpwcms['db_prepend']) ? rtrim((string)$phpwcms['db_prepend'], '_') . '_' : '');
        $cli_brand = $cli_detected !== null ? derive_brand_prefix_from_detected($cli_detected, $cli_db_part) : '';
        $cli_wl_payload = (!empty($phpwcms['whitelabel_key']) && function_exists('setup_validate_whitelabel_key'))
            ? setup_validate_whitelabel_key((string)$phpwcms['whitelabel_key'])
            : false;
        if ($cli_wl_payload !== false && !empty($cli_wl_payload['brand_table_prefix'])) {
            $cli_wl_brand = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$cli_wl_payload['brand_table_prefix']);
            if ($cli_wl_brand !== '' && $cli_wl_brand !== 'phpwcms') {
                $cli_brand = $cli_wl_brand;
            }
        }
        if ($cli_brand !== '' && preg_match('/^[a-zA-Z0-9_]+$/', $cli_brand)) {
            if (cli_confirm("Preserve the detected prefix by setting brand_table_prefix to '$cli_brand' in conf.inc.php?", true)) {
                if (persist_brand_table_prefix($configFile, $cli_brand, $phpwcms)) {
                    $phpwcms['brand_table_prefix'] = $cli_brand;
                    $_brand_prefix = $cli_brand;
                    $dbPrepend = $cli_db_part . $cli_brand . '_';
                    $cli_prepend_check = detect_actual_db_prepend($mysqli, $dbPrepend);
                    cli_print("brand_table_prefix set to '$cli_brand'. Continuing.\n\n", 'green');
                } else {
                    $cli_persist_why = !is_writable($configFile)
                        ? 'the file is not writable'
                        : ($configFile && strpos((string) @file_get_contents($configFile), "\$phpwcms['brand_table_prefix']") === false
                            ? "the file does not contain a \$phpwcms['brand_table_prefix'] line (e.g. legacy cmsgo config that could not be migrated)"
                            : 'writing the file failed');
                    cli_print("Error: Could not update brand_table_prefix in $configFile — $cli_persist_why.\n\n", 'red');
                }
            }
        }
        if ($cli_prepend_check['mismatch']) {
            cli_print("To fix this, set brand_table_prefix in conf.inc.php to match\n");
            cli_print("the detected prefix ('" . rtrim((string)$cli_detected, '_') . "'), then re-run.\n\n");
            cli_print("If this is a whitelabel installation, ensure a valid whitelabel\n");
            cli_print("license with a matching brand_table_prefix is configured.\n\n");
            exit(1);
        }
    }

    // 4. Fetch Release Info
    cli_print('Checking for latest release from GitHub (' . UPGRADE_REPO . ")...\n");
    $release = fetch_latest_release();
    if ($release === false) {
        cli_print("Failed to fetch latest release metadata from GitHub.\n", 'red');
        exit(1);
    }

    cli_print('Current installed version: ', 'bold');
    cli_print($installedVersion . ($installedRevision > 0 ? " (r$installedRevision)\n" : "\n"));
    if ($isCmsgoInstall) {
        cli_print("Note: cmsGo! install detected — version schema differs from phpwcms; upgrade eligibility is decided by revision (r$installedRevision) only.\n\n", 'yellow');
    }
    cli_print('Target Release available:  ', 'bold');
    cli_print("v{$release['version']} ({$release['tag']})\n", 'green');
    if (!empty($release['published'])) {
        cli_print('Published at: ' . substr($release['published'], 0, 10) . "\n");
    }
    cli_print("\n");

    if ($installedRevision < MIN_INSTALLED_REVISION) {
        cli_print('Error: Automatic update is not supported for installed phpwcms < r' . MIN_INSTALLED_REVISION . ".\n", 'red');
        cli_print('Detected installed revision: ' . ($installedRevision > 0 ? 'r' . $installedRevision : 'unknown / pre-r401') . "\n");
        cli_print("The database revision tracking system was introduced in revision r401.\n");
        cli_print("Earlier versions cannot be migrated automatically and require a manual upgrade path.\n\n");
        exit(1);
    }

    $isSameVersion = (!$isCmsgoInstall && $installedVersion !== 'unknown') && version_compare($installedVersion, $release['version'], '==');
    $isDowngrade = (!$isCmsgoInstall && $installedVersion !== 'unknown') && version_compare($installedVersion, $release['version'], '>');

    if ($isDowngrade) {
        cli_print("Error: The target release (v{$release['version']}) is older than the currently installed version ($installedVersion).\n", 'red');
        cli_print("Downgrading to older versions is not allowed.\n\n");
        exit(1);
    }

    if ($isSameVersion) {
        cli_print("Notice: The target release (v{$release['version']}) matches the currently installed version ($installedVersion).\n", 'yellow');
        $forceCli = in_array('--reinstall', $argv ?? [], true) || in_array('--force', $argv ?? [], true);
        if (!$forceCli) {
            $confirmSame = cli_confirm('Do you want to reinstall and overwrite the same version again?', false);
            if (!$confirmSame) {
                cli_print("Upgrade cancelled by user. Overwrite of the same version was not confirmed.\n", 'yellow');
                exit(0);
            }
        } else {
            cli_print("Notice: Reinstall flag supplied; proceeding with same version overwrite.\n", 'yellow');
        }
        cli_print("\n");
    }

    $isLegacyRelease = version_compare($release['version'], '2.0.0', '<');
    $allowUnverifiedCli = in_array('--allow-unverified', $argv ?? [], true);

    if ($isLegacyRelease) {
        cli_print("---------------------------------------------------------\n", 'yellow');
        cli_print(" Legacy Release Notice (< 2.0.0) — No Package Manifest\n", 'bold');
        cli_print("---------------------------------------------------------\n", 'yellow');
        cli_print("The target release (v{$release['version']}) was released prior to phpwcms 2.0.0\n");
        cli_print("and does not contain an .update-manifest with cryptographic SHA-256 checksums.\n\n");
        cli_print("Consequences:\n", 'bold');
        cli_print("  * Package file authenticity and integrity cannot be cryptographically verified.\n");
        cli_print("  * Release files will be extracted and overwritten using legacy file copy logic.\n\n");

        if (!$allowUnverifiedCli) {
            cli_print("To proceed with upgrading to this legacy version, you must actively confirm\n");
            cli_print("the unverified package override.\n\n");
            $confirmOverride = cli_confirm('Do you understand the consequences and want to proceed with unverified upgrade?', false);
            if (!$confirmOverride) {
                cli_print("Upgrade cancelled by user. Package verification required.\n", 'red');
                exit(1);
            }
            $allowUnverifiedCli = true;
        } else {
            cli_print("Notice: --allow-unverified flag supplied; legacy package override active.\n", 'yellow');
        }
        cli_print("\n");
    }

    // 5. Backup Confirmation & Options
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

    // 6. Download & Apply Release
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
        $res = apply_release_zip(
            $tempZip,
            $docRoot,
            $phpwcms,
            static function ($msg) {
                cli_print("  * $msg\n");
            },
            $allowUnverifiedCli,
            $release['version']
        );
        @unlink($tempZip);
    } catch (Exception $e) {
        @unlink($tempZip);
        cli_print('Error applying update: ' . $e->getMessage() . "\n", 'red');
        exit(1);
    }

    // 7. DB Migration
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
// Preset by the config-require/migration block above in CLI and web mode;
// only initialize when it was not set yet.
if (!isset($cmsgoMigrationNotice)) {
    $cmsgoMigrationNotice = '';
}
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
                // If the user table was resolved via fallback (whitelabel prefix),
                // hint at the brand_table_prefix that likely needs to be configured.
                $wl_hint_table = get_upgrade_user_table($mysqli, $dbPrepend);
                if ($wl_hint_table !== $dbPrepend . 'user' && $wl_hint_table !== $dbPrepend . 'phpwcms_user') {
                    $wl_hint_prefix = rtrim(substr($wl_hint_table, 0, -strlen('_user')), '_');
                    $error .= ' Note: user accounts were found in "' . htmlspecialchars($wl_hint_table) . '" — this looks like a whitelabel installation. Set $phpwcms[\'brand_table_prefix\'] to \'' . htmlspecialchars($wl_hint_prefix) . '\' in conf.inc.php.';
                }
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
    } elseif ($action === 'save_whitelabel' && !empty($_SESSION['upgrade_authenticated'])) {
        // Validate and persist whitelabel_key + brand_table_prefix to conf.inc.php
        $wl_key_post    = trim($_POST['whitelabel_key'] ?? '');
        $wl_prefix_post = preg_replace('/[^a-zA-Z0-9_]/', '', trim($_POST['brand_table_prefix'] ?? ''));
        $wl_save_error  = '';

        // License validation (sodium-based)
        $wl_payload = $wl_key_post !== '' ? setup_validate_whitelabel_key($wl_key_post) : false;

        if ($wl_key_post !== '' && $wl_payload === false) {
            if (trim($phpwcms['whitelabel_key'] ?? '') !== '') {
                // Invalid new key — preserve the existing configured key instead of failing
                $wl_key_post = trim($phpwcms['whitelabel_key']);
                $wl_payload  = setup_validate_whitelabel_key($wl_key_post);
            } else {
                $wl_save_error = 'The license key is invalid, expired, or the signature could not be verified. No changes were saved.';
            }
        }

        if ($wl_save_error === '' && $configFile && is_writable($configFile)) {
            $wl_conf = file_get_contents($configFile);
            if ($wl_conf !== false) {
                $wl_esc_key = str_replace(['\\', "'"], ['\\\\', "\\'"], $wl_key_post);
                $wl_prefix_replacements = 0;

                // Patch whitelabel_key line — fail if the line is missing (unexpected conf.inc.php layout)
                $wl_conf = preg_replace(
                    "/(\\\$phpwcms\['whitelabel_key'\]\s*=\s*')[^']*(')/",
                    "\${1}" . $wl_esc_key . '${2}',
                    $wl_conf,
                    -1,
                    $wl_key_replacements
                );
                // Patch brand_table_prefix line only when a prefix change is part of
                // this save (license present). Clearing the key keeps the configured
                // prefix untouched so a custom prefix is never wiped silently.
                $wl_esc_prefix = null;
                if ($wl_payload !== false) {
                    // 'phpwcms' is the default anyway — store empty instead
                    $wl_esc_prefix = $wl_prefix_post === 'phpwcms' ? '' : $wl_prefix_post;
                    $wl_conf = preg_replace(
                        "/(\\\$phpwcms\['brand_table_prefix'\]\s*=\s*')[^']*(')/",
                        "\${1}" . $wl_esc_prefix . '${2}',
                        $wl_conf,
                        -1,
                        $wl_prefix_replacements
                    );
                }

                // Legacy configs lack the whitelabel_key / brand_table_prefix
                // lines — insert them instead of failing.
                if ($wl_key_replacements === 0) {
                    $wl_conf = insert_missing_config_setting($wl_conf, 'whitelabel_key', $wl_key_post);
                }
                if ($wl_esc_prefix !== null && $wl_prefix_replacements === 0) {
                    $wl_conf = insert_missing_config_setting($wl_conf, 'brand_table_prefix', $wl_esc_prefix);
                }

                if (file_put_contents($configFile, $wl_conf, LOCK_EX) !== false) {
                    // Reload $phpwcms from the updated file so the page reflects the new state
                    $phpwcms['whitelabel_key']     = $wl_key_post;
                    if ($wl_esc_prefix !== null) {
                        $phpwcms['brand_table_prefix'] = $wl_esc_prefix;
                    }
                    // Recompute $_brand_prefix and $dbPrepend so the same-request
                    // prefix check reflects the saved values without a reload
                    $_brand_prefix = ($phpwcms['brand_table_prefix'] ?? '') !== '' ? (string)$phpwcms['brand_table_prefix'] : 'phpwcms';
                    $dbPrepend = (!empty($phpwcms['db_prepend']) ? rtrim((string)$phpwcms['db_prepend'], '_') . '_' : '') . $_brand_prefix . '_';
                    $notice = 'Whitelabel license settings saved successfully.';
                } else {
                    $wl_save_error = 'Could not write to ' . htmlspecialchars($configFile) . '. Check file permissions.';
                }
            } else {
                $wl_save_error = 'Could not read configuration file.';
            }
        } elseif ($wl_save_error === '') {
            // Only report the writability problem when no earlier error
            // (invalid license key) already set a more specific message.
            $wl_save_error = 'Configuration file is not writable: ' . htmlspecialchars((string)$configFile);
        }

        if ($wl_save_error !== '') {
            $error = $wl_save_error;
        }
    }
}

$isAuthenticated = !empty($_SESSION['upgrade_authenticated']);
$releaseInfo = null;
$isUpgradeAllowed = true;
$versionError = '';
$isLegacyTarget = false;
$isRevisionTooOld = false;
$isSameVersion = false;
$isDowngrade = false;
$prepend_check = null;
if ($isAuthenticated) {
    if ($installedRevision < MIN_INSTALLED_REVISION) {
        $isUpgradeAllowed = false;
        $isRevisionTooOld = true;
        $versionError = 'Automatic update is not supported for installed phpwcms < r' . MIN_INSTALLED_REVISION . ' (detected revision: ' . ($installedRevision > 0 ? 'r' . $installedRevision : 'unknown / pre-r401') . '). Database revision tracking began at revision r401; earlier versions cannot be migrated automatically and require a manual upgrade.';
    }

    if ($mysqli instanceof mysqli) {
        $prepend_check = detect_actual_db_prepend($mysqli, $dbPrepend);
        if ($prepend_check['mismatch'] && $prepend_check['detected'] !== null) {
            // Auto-preserve legacy/whitelabel prefixes (e.g. cmsgo from older
            // versions): persist the detected brand prefix to conf.inc.php so
            // the upgrade targets the tables that are actually there.
            $web_db_part = (!empty($phpwcms['db_prepend']) ? rtrim((string)$phpwcms['db_prepend'], '_') . '_' : '');
            $web_brand = derive_brand_prefix_from_detected((string)$prepend_check['detected'], $web_db_part);
            $web_license_payload = trim((string)($phpwcms['whitelabel_key'] ?? '')) !== ''
                ? setup_validate_whitelabel_key((string)$phpwcms['whitelabel_key'])
                : false;
            if ($web_brand !== '' && preg_match('/^[a-zA-Z0-9_]+$/', $web_brand)
                && $web_license_payload === false
                && ($phpwcms['brand_table_prefix'] ?? '') !== $web_brand
                && persist_brand_table_prefix($configFile, $web_brand, $phpwcms)
            ) {
                $phpwcms['brand_table_prefix'] = $web_brand;
                $_brand_prefix = $web_brand;
                $dbPrepend = $web_db_part . $web_brand . '_';
                $prepend_check = detect_actual_db_prepend($mysqli, $dbPrepend);
                if (!$prepend_check['mismatch']) {
                    $notice = 'Detected table prefix "' . htmlspecialchars($dbPrepend) . '" — $phpwcms[\'brand_table_prefix\'] was set to \'' . htmlspecialchars($web_brand) . '\' in conf.inc.php to preserve the existing tables.';
                }
            }
        }
        if ($prepend_check['mismatch']) {
            $isUpgradeAllowed = false;
        }
    }

    $releaseInfo = fetch_latest_release();
    if ($releaseInfo !== false) {
        $isLegacyTarget = version_compare($releaseInfo['version'], '2.0.0', '<');
        if ($isUpgradeAllowed && !$isCmsgoInstall && $installedVersion !== 'unknown') {
            if (version_compare($installedVersion, $releaseInfo['version'], '>')) {
                $isUpgradeAllowed = false;
                $isDowngrade = true;
                $versionError = 'The target release (v' . $releaseInfo['version'] . ') is older than the currently installed version (' . $installedVersion . '). Downgrading to an older version is not permitted.';
            } elseif (version_compare($installedVersion, $releaseInfo['version'], '==')) {
                $isSameVersion = true;
            }
        }
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

            <?php
            // Evaluate current whitelabel state from conf.inc.php values
            $wl_current_key     = trim($phpwcms['whitelabel_key'] ?? '');
            $wl_current_prefix  = $phpwcms['brand_table_prefix'] ?? '';
            $wl_sodium_available = function_exists('sodium_crypto_sign_verify_detached') && function_exists('sodium_hex2bin');
            $wl_current_payload = $wl_current_key !== '' ? setup_validate_whitelabel_key($wl_current_key) : false;

            // Determine prefix display value: preserve real custom value only
            if ($wl_current_payload !== false) {
                $wl_prefix_display = ($wl_current_prefix !== '' && $wl_current_prefix !== 'phpwcms')
                    ? $wl_current_prefix
                    : ((!empty($wl_current_payload['brand_table_prefix']) && $wl_current_payload['brand_table_prefix'] !== 'phpwcms')
                        ? $wl_current_payload['brand_table_prefix']
                        : '');
            } else {
                $wl_prefix_display = '';
            }

            // Show whitelabel card only when the upgrade itself is permitted.
            // While "Upgrade Not Permitted" is shown (revision too old, downgrade,
            // prefix mismatch), the card stays hidden — it is always rendered
            // directly after the Target Release Available block.
            $showWhitelabelCard = $isUpgradeAllowed;
            ?>

            <?php if (!empty($cmsgoMigrationNotice)): ?>
                <div class="alert <?= strpos($cmsgoMigrationNotice, 'could not be updated') !== false ? 'alert-warning' : 'alert-success' ?> mb-4">
                    <?= $cmsgoMigrationNotice ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($notice)): ?>
                <div class="alert alert-success mb-4"><?= htmlspecialchars($notice) ?></div>
            <?php endif; ?>

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
                            Installed version: <strong><?= htmlspecialchars($installedVersion) ?></strong><?= $installedRevision > 0 ? ' <span class="badge text-bg-secondary">r' . $installedRevision . '</span>' : '' ?>
                            &bull; Published: <?= htmlspecialchars(substr($releaseInfo['published'], 0, 10)) ?>
                        </div>
                    </div>
                    <div>
                        <?php if ($isRevisionTooOld): ?>
                            <span class="badge text-bg-danger fs-6">Revision &lt; r401</span>
                        <?php elseif ($isDowngrade): ?>
                            <span class="badge text-bg-danger fs-6">Downgrade Blocked</span>
                        <?php elseif ($prepend_check !== null && $prepend_check['mismatch']): ?>
                            <span class="badge text-bg-danger fs-6">Prefix Mismatch</span>
                        <?php elseif ($isSameVersion): ?>
                            <span class="badge text-bg-warning fs-6">Same Version (Reinstall)</span>
                        <?php elseif ($isUpgradeAllowed): ?>
                            <span class="badge bg-success fs-6">Ready to upgrade</span>
                        <?php else: ?>
                            <span class="badge text-bg-warning fs-6">Already Up to Date</span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($showWhitelabelCard): ?>
                <div class="card mb-4 border">
                    <div class="card-header bg-light">
                        <strong>Whitelabel License</strong>
                        <span class="text-muted small fw-normal"> (optional)</span>
                    </div>
                    <div class="card-body">
                        <?php if (!$wl_sodium_available): ?>
                            <div class="alert alert-warning mb-0">
                                The PHP <code>sodium</code> extension is not available on this server.
                                Whitelabel license validation requires libsodium (PHP 7.2+, usually bundled).
                                Please enable the extension to use this feature.
                            </div>
                        <?php else: ?>
                            <?php if ($wl_current_key !== ''): ?>
                                <div class="mb-3 <?= $wl_current_payload !== false ? 'text-success' : 'text-danger' ?>">
                                    <?php if ($wl_current_payload !== false): ?>
                                        &#10003; License valid
                                        <?php
                                        $wl_info = [];
                                        if (!empty($wl_current_payload['licensee'])) {
                                            $wl_info[] = 'Licensee: <strong>' . htmlspecialchars($wl_current_payload['licensee']) . '</strong>';
                                        }
                                        if (!empty($wl_current_payload['domain'])) {
                                            $wl_info[] = 'Domain: <strong>' . htmlspecialchars($wl_current_payload['domain']) . '</strong>';
                                        }
                                        if (!empty($wl_current_payload['valid_until']) && (int)$wl_current_payload['valid_until'] > 0) {
                                            $wl_info[] = 'Expires: <strong>' . date('Y-m-d', (int)$wl_current_payload['valid_until']) . '</strong>';
                                        }
                                        if (!empty($wl_current_payload['brand_table_prefix'])) {
                                            $wl_info[] = 'Prefix: <strong>' . htmlspecialchars($wl_current_payload['brand_table_prefix']) . '</strong>';
                                        }
                                        if ($wl_info) {
                                            echo ' &mdash; ' . implode(' &bull; ', $wl_info);
                                        }
                                        ?>
                                    <?php else: ?>
                                        &#10007; Saved key is invalid or expired
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <form method="post">
                                <input type="hidden" name="action" value="save_whitelabel">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['upgrade_csrf_token']) ?>">

                                <div class="mb-3">
                                    <label class="form-label fw-bold" for="wl-key">License Key</label>
                                    <textarea name="whitelabel_key" id="wl-key" class="form-control" style="font-family:var(--font-mono);font-size:0.82em;" rows="3" placeholder="Paste your signed whitelabel license key here &hellip;"><?= htmlspecialchars($wl_current_key) ?></textarea>
                                    <div class="text-muted small mt-1">Leave empty to clear the license and revert to the standard open-source mode.</div>
                                </div>

                                <?php if ($wl_current_payload !== false): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold" for="wl-prefix">Brand Table Prefix</label>
                                    <input type="text" name="brand_table_prefix" id="wl-prefix" class="form-control" value="<?= htmlspecialchars($wl_prefix_display) ?>" placeholder="phpwcms" maxlength="64" pattern="[a-zA-Z0-9_]*" style="max-width:280px;">
                                    <div class="text-muted small mt-1">
                                        Replaces <code>phpwcms</code> in all DB table names. Only <code>a-z A-Z 0-9 _</code> allowed.
                                        <?php if (!empty($wl_current_payload['brand_table_prefix']) && $wl_current_payload['brand_table_prefix'] !== 'phpwcms'): ?>
                                            License suggests: <code><?= htmlspecialchars($wl_current_payload['brand_table_prefix']) ?></code>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <button type="submit" class="btn btn-primary btn-sm">Save License Settings</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($prepend_check !== null && $prepend_check['mismatch']): ?>
                <div class="card mb-4 border border-danger">
                    <div class="card-header bg-light text-danger fw-bold">
                        &#10007; DB Table Prefix Mismatch &mdash; Upgrade Blocked
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            The table prefix configured in <code>conf.inc.php</code> does not match the tables found in the database.
                            Proceeding would back up and migrate the wrong (or no) tables.
                        </p>
                        <table style="border-collapse:collapse;width:100%;max-width:500px;" class="mb-3">
                            <tr>
                                <td class="text-muted small" style="padding:3px 8px 3px 0;white-space:nowrap;">Expected prefix <small>(from conf.inc.php)</small></td>
                                <td><code><?= htmlspecialchars($prepend_check['expected']) ?></code></td>
                            </tr>
                            <tr>
                                <td class="text-muted small" style="padding:3px 8px 3px 0;white-space:nowrap;">Detected prefix <small>(from DB tables)</small></td>
                                <td><code class="text-danger"><?= htmlspecialchars((string)$prepend_check['detected']) ?></code></td>
                            </tr>
                        </table>
                        <p class="mb-1 fw-bold">To resolve:</p>
                        <ol class="mb-0 small ps-3">
                            <li>If this is a <strong>whitelabel installation</strong>: enter a valid whitelabel license key with a matching <code>brand_table_prefix</code> in the card above and save.</li>
                            <li>If this is a <strong>standard installation</strong> with a custom prefix: manually set <code>$phpwcms['brand_table_prefix']</code> in <code>conf.inc.php</code> to <code><?= htmlspecialchars(rtrim((string)$prepend_check['detected'], '_')) ?></code>.</li>
                        </ol>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!$isUpgradeAllowed): ?>
                    <div class="alert <?= $isRevisionTooOld ? 'alert-danger' : 'alert-warning' ?>">
                        <strong>Upgrade Not Permitted:</strong> <?= htmlspecialchars($versionError) ?>
                    </div>
                    <p class="text-muted">
                        <?php if ($isRevisionTooOld): ?>
                            Automated database migrations require the revision system introduced in phpwcms r401. To upgrade an older installation, please follow the manual upgrade instructions.
                        <?php else: ?>
                            Downgrading to an older version of phpwcms is disallowed to protect your installation and database schema integrity.
                        <?php endif; ?>
                    </p>
                    <div class="mt-3">
                        <a href="../login.php" class="btn btn-primary">Return to Backend</a>
                    </div>
                <?php elseif (isset($_POST['execute_upgrade'])): ?>
                    <?php if (empty($_POST['has_external_backup'])): ?>
                        <div class="alert alert-danger">
                            Please confirm that you have verified an external backup before proceeding.
                        </div>
                    <?php elseif ($isSameVersion && empty($_POST['confirm_same_version'])): ?>
                        <div class="alert alert-danger">
                            Please confirm that you want to overwrite the same version again before proceeding.
                        </div>
                    <?php elseif ($isLegacyTarget && empty($_POST['allow_unverified'])): ?>
                        <div class="alert alert-danger">
                            Please acknowledge and confirm the unverified package override before proceeding with a legacy release.
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
                                $allowUnverified = !empty($_POST['allow_unverified']);
                                try {
                                    $res = apply_release_zip($tempZip, $docRoot, $phpwcms, $logWeb, $allowUnverified, $releaseInfo['version']);
                                    $updatedFiles = $res['copied'] ?? 0;
                                    $logWeb("✓ Successfully updated $updatedFiles files.");
                                    @unlink($tempZip);

                                    $logWeb('Applying database revisions...');
                                    execute_post_upgrade_revisions($docRoot, $logWeb);
                                    $logWeb('✓ Database revisions completed.');

                                    $logWeb('');
                                    $logWeb('★ Upgrade successfully finished! You may now return to the phpwcms backend.');
                                } catch (Exception $e) {
                                    @unlink($tempZip);
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

                    <?php if ($isSameVersion): ?>
                        <div class="alert alert-info border-info mb-4">
                            <h5 class="alert-heading font-weight-bold mb-1">ℹ️ Same Version Reinstall Notice</h5>
                            <p class="mb-0">
                                The target release (<strong>v<?= htmlspecialchars($releaseInfo['version']) ?></strong>) matches your currently installed version. You can proceed to reinstall and overwrite files to refresh or repair your installation.
                            </p>
                        </div>
                    <?php endif; ?>

                    <?php if ($isLegacyTarget): ?>
                        <div class="alert alert-warning border-warning mb-4">
                            <h5 class="alert-heading font-weight-bold mb-1">⚠️ Legacy Release Package Notice (&lt; 2.0.0)</h5>
                            <p class="mb-2">
                                The target release (<strong>v<?= htmlspecialchars($releaseInfo['version']) ?></strong>) was released prior to phpwcms 2.0.0 and does not contain an update manifest (<code>.update-manifest</code>) with SHA-256 checksums.
                            </p>
                            <p class="mb-1 small font-weight-bold">Consequences &amp; Risks:</p>
                            <ul class="mb-2 small ps-3">
                                <li>Package file authenticity and integrity cannot be cryptographically verified against checksums.</li>
                                <li>Files will be extracted and applied directly using legacy file copying.</li>
                                <li>An external backup is strongly advised before proceeding.</li>
                                <li>You must actively acknowledge and check the <strong>"Allow unverified package override"</strong> option below to unlock the upgrade button.</li>
                            </ul>
                        </div>
                    <?php endif; ?>

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

                        <?php if ($isSameVersion): ?>
                            <div class="card mb-3 border border-warning">
                                <div class="card-header bg-light">
                                    <strong>Same Version Overwrite Confirmation</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkSameVersion" name="confirm_same_version" value="1">
                                        <label class="form-check-label text-danger fw-bold" for="checkSameVersion">
                                            Confirm overwrite of the same version (phpwcms v<?= htmlspecialchars($releaseInfo['version']) ?>)
                                        </label>
                                        <div class="text-muted small mt-1">
                                            Target release matches your currently installed version.
                                            Please check this box to confirm that you want to reinstall and overwrite the same version again.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="card mb-3 border">
                            <div class="card-header bg-light">
                                <strong>2. Automated Local Backup Options</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="checkAutoBackup" name="create_backup" value="1" checked>
                                    <label class="form-check-label" for="checkAutoBackup">
                                        <strong>Create full automated backup first</strong> (recommended)<br>
                                        <span class="text-muted small">
                                            Creates a database dump
                                            (<?= function_exists('gzopen') ? '<code>.sql.gz</code>' : '<code>.sql</code> (uncompressed; ext-zlib unavailable)' ?>)
                                            and codebase archive
                                            (<?= class_exists('ZipArchive') ? '<code>.zip</code>' : '<code>.tar.gz</code> / <code>.zip</code>' ?>)
                                            before overwriting any files.
                                        </span>
                                    </label>
                                </div>

                                <div class="ms-4 border-start ps-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkFileArchive" name="include_filearchive" value="1">
                                        <label class="form-check-label" for="checkFileArchive">
                                            Include file archive folder (<code>/<?= htmlspecialchars($realFileArchive) ?></code>)<br>
                                            <span class="text-muted small">
                                                Check this only if you want uploaded media in the backup zip.
                                                Uncheck to keep backup fast and compact.
                                            </span>
                                        </label>
                                    </div>
                                    <div class="text-muted small mt-2">
                                        * Rebuildable cache files, temporary assets, and cached thumbnails in
                                        <code>/<?= htmlspecialchars($realContentPath) ?></code>
                                        are automatically excluded from the backup to preserve disk space.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4 border <?= $isLegacyTarget ? 'border-warning' : '' ?>">
                            <div class="card-header bg-light">
                                <strong>3. Package Integrity &amp; Verification</strong>
                            </div>
                            <div class="card-body">
                                <?php if ($isLegacyTarget): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkAllowUnverified" name="allow_unverified" value="1">
                                        <label class="form-check-label text-danger fw-bold" for="checkAllowUnverified">
                                            Allow unverified package override (Required for phpwcms &lt; 2.0.0)
                                        </label>
                                        <div class="text-muted small mt-1">
                                            I acknowledge that this release package does not contain an
                                            <code>.update-manifest</code> with SHA-256 checksums.
                                            I understand the consequences and wish to proceed with
                                            unverified installation.
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small mb-2">
                                        ✓ <strong>Cryptographic manifest verification active:</strong>
                                        Release files will be verified against SHA-256 checksums in
                                        <code>.update-manifest</code> before being applied.
                                    </p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkAllowUnverified" name="allow_unverified" value="1">
                                        <label class="form-check-label text-danger fw-bold" for="checkAllowUnverified">
                                            Allow unverified package override if manifest is missing (advanced fallback)
                                        </label>
                                    </div>
                                <?php endif; ?>
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
                            const chkBackup = document.getElementById('checkExternalBackup');
                            const chkLegacy = document.getElementById('checkAllowUnverified');
                            const chkSame = document.getElementById('checkSameVersion');
                            const btn = document.getElementById('btnStartUpgrade');
                            const requireLegacy = <?= $isLegacyTarget ? 'true' : 'false' ?>;
                            const requireSame = <?= $isSameVersion ? 'true' : 'false' ?>;

                            function updateBtnState() {
                                const backupOk = chkBackup && chkBackup.checked;
                                const legacyOk = !requireLegacy || (chkLegacy && chkLegacy.checked);
                                const sameOk = !requireSame || (chkSame && chkSame.checked);
                                if (btn) {
                                    btn.disabled = !(backupOk && legacyOk && sameOk);
                                }
                            }
                            if (chkBackup) {
                                chkBackup.addEventListener('change', updateBtnState);
                            }
                            if (chkLegacy) {
                                chkLegacy.addEventListener('change', updateBtnState);
                            }
                            if (chkSame) {
                                chkSame.addEventListener('change', updateBtnState);
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
