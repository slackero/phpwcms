#!/usr/bin/env php
<?php
/**
 * phpwcms OAuth2 Refresh Token Generator CLI
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 */

if (php_sapi_name() !== 'cli') {
    die('This script can only be run from the command line.' . PHP_EOL);
}

$autoload_file = dirname(__DIR__) . '/include/vendor/autoload.php';
if (!file_exists($autoload_file)) {
    fwrite(STDERR, 'Error: Autoloader not found. Run composer install first.' . PHP_EOL);
    exit(1);
}
require_once $autoload_file;

use Greew\OAuth2\Client\Provider\Azure;
use League\OAuth2\Client\Provider\Google;

function prompt(string $message, string $default = ''): string {
    $prompt_text = $message . ($default !== '' ? ' [' . $default . ']' : '') . ': ';
    fwrite(STDOUT, $prompt_text);
    $input = trim((string)fgets(STDIN));
    return $input !== '' ? $input : $default;
}

echo PHP_EOL;
echo '=========================================' . PHP_EOL;
echo ' phpwcms OAuth2 Refresh Token Generator  ' . PHP_EOL;
echo '=========================================' . PHP_EOL . PHP_EOL;

// Parse CLI options
$options = getopt('h', ['help', 'provider:', 'client-id:', 'client-secret:', 'tenant-id:', 'port:']);

if (isset($options['h']) || isset($options['help'])) {
    echo <<<HELP
phpwcms OAuth2 Refresh Token Generator
======================================
Usage:
  bin/get-oauth-token.php [options]

Options:
  --provider=<name>         Provider name: "google" or "azure" (interactive if omitted)
  --client-id=<id>          OAuth2 Client ID
  --client-secret=<secret>  OAuth2 Client Secret
  --tenant-id=<id>          Microsoft Tenant ID (for Azure, default: "common")
  --port=<port>             Local callback port (default: 8080)
  -h, --help                Show this help message

Examples:
  bin/get-oauth-token.php
  bin/get-oauth-token.php --provider=google --client-id=xxx --client-secret=yyy
  bin/get-oauth-token.php --provider=azure --client-id=xxx --client-secret=yyy --tenant-id=common

HELP;
    exit(0);
}

$provider_name = strtolower($options['provider'] ?? '');
while (!in_array($provider_name, ['google', 'azure', 'microsoft'], true)) {
    echo 'Select OAuth2 Provider:' . PHP_EOL;
    echo '  [1] Google (Gmail / Google Workspace)' . PHP_EOL;
    echo '  [2] Microsoft Azure / Office 365' . PHP_EOL;
    $choice = prompt('Choice', '1');
    if ($choice === '1' || strtolower($choice) === 'google') {
        $provider_name = 'google';
    } elseif ($choice === '2' || in_array(strtolower($choice), ['azure', 'microsoft'], true)) {
        $provider_name = 'azure';
    }
}

$client_id = $options['client-id'] ?? '';
while ($client_id === '') {
    $client_id = prompt('Enter Client ID');
}

$client_secret = $options['client-secret'] ?? '';
while ($client_secret === '') {
    $client_secret = prompt('Enter Client Secret');
}

$tenant_id = '';
if ($provider_name === 'azure' || $provider_name === 'microsoft') {
    $tenant_id = $options['tenant-id'] ?? '';
    while ($tenant_id === '') {
        $tenant_id = prompt('Enter Tenant ID (Directory ID, or common/organizations)', 'common');
    }
}

$port = (int)($options['port'] ?? 8080);
if ($port <= 0 || $port > 65535) {
    $port = 8080;
}

$redirect_uri = 'http://localhost:' . $port;

// Initialize OAuth provider
$auth_options = [];
if ($provider_name === 'google') {
    $provider = new Google([
        'clientId'     => $client_id,
        'clientSecret' => $client_secret,
        'redirectUri'  => $redirect_uri,
        'accessType'   => 'offline',
    ]);
    $auth_options = [
        'scope'       => ['https://mail.google.com/'],
        'access_type' => 'offline',
        'prompt'      => 'consent',
    ];
} else {
    $provider = new Azure([
        'clientId'     => $client_id,
        'clientSecret' => $client_secret,
        'redirectUri'  => $redirect_uri,
        'tenantId'     => $tenant_id,
    ]);
    $auth_options = [
        'scope'  => [
            'https://outlook.office.com/SMTP.Send',
            'offline_access',
        ],
        'prompt' => 'consent',
    ];
}

$auth_url = $provider->getAuthorizationUrl($auth_options);
$expected_state = $provider->getState();

echo PHP_EOL;
echo '--------------------------------------------------------------------------------' . PHP_EOL;
echo 'Open the following URL in your browser to authorize access:' . PHP_EOL;
echo PHP_EOL . $auth_url . PHP_EOL . PHP_EOL;
echo '--------------------------------------------------------------------------------' . PHP_EOL;
echo 'Redirect URI expected: ' . $redirect_uri . PHP_EOL;
echo 'Waiting for callback on ' . $redirect_uri . ' ...' . PHP_EOL;
echo '(You can also paste the authorization code or full redirected URL below)' . PHP_EOL;
echo '--------------------------------------------------------------------------------' . PHP_EOL . PHP_EOL;

// Start local HTTP server to capture redirect
$socket = @stream_socket_server('tcp://127.0.0.1:' . $port, $errno, $errstr);
$auth_code = null;

if ($socket) {
    stream_set_blocking($socket, false);
    stream_set_blocking(STDIN, false);

    $start_time = time();
    $timeout = 180; // 3 minutes timeout

    while (time() - $start_time < $timeout) {
        $read = [$socket, STDIN];
        $write = null;
        $except = null;

        $num_changed = @stream_select($read, $write, $except, 1);
        if ($num_changed === false || $num_changed === 0) {
            continue;
        }

        // Check if connection received on local server
        if (in_array($socket, $read, true)) {
            $conn = @stream_socket_accept($socket, 2);
            if ($conn) {
                $request = fread($conn, 4096);
                if (preg_match('#GET\s+/\?([^ ]+)#', $request, $matches)) {
                    parse_str($matches[1], $query_params);
                    $state = $query_params['state'] ?? '';
                    $code = $query_params['code'] ?? '';

                    if ($code !== '') {
                        if ($expected_state && $state !== $expected_state) {
                            $response = "HTTP/1.1 400 Bad Request\r\nContent-Type: text/html\r\nConnection: close\r\n\r\n" .
                                '<html><body><h2>Error: State mismatch (possible CSRF).</h2></body></html>';
                            fwrite($conn, $response);
                            fclose($conn);
                            continue;
                        }

                        $auth_code = $code;
                        $response = "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\nConnection: close\r\n\r\n" .
                            '<!DOCTYPE html><html><head><meta charset="utf-8"><title>phpwcms OAuth</title></head>' .
                            '<body style="font-family:sans-serif;text-align:center;padding:50px;">' .
                            '<h2>Authentication Successful!</h2>' .
                            '<p>You can close this window and return to the terminal.</p>' .
                            '</body></html>';
                        fwrite($conn, $response);
                        fclose($conn);
                        break;
                    }
                }
                fclose($conn);
            }
        }

        // Check manual STDIN input
        if (in_array(STDIN, $read, true)) {
            $input = trim((string)fgets(STDIN));
            if ($input !== '') {
                if (strpos($input, 'code=') !== false) {
                    $parts = parse_url($input);
                    if (!empty($parts['query'])) {
                        parse_str($parts['query'], $query_params);
                        $auth_code = $query_params['code'] ?? '';
                    }
                } else {
                    $auth_code = $input;
                }
                break;
            }
        }
    }
    fclose($socket);
} else {
    // Socket could not be opened (e.g. port already in use), fall back to manual input
    echo 'Notice: Could not listen on port ' . $port . ' (' . $errstr . ').' . PHP_EOL;
    stream_set_blocking(STDIN, true);
    $input = prompt('Paste the full redirected URL or code parameter here');
    if (strpos($input, 'code=') !== false) {
        $parts = parse_url($input);
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query_params);
            $auth_code = $query_params['code'] ?? '';
        }
    } else {
        $auth_code = $input;
    }
}

if (!$auth_code) {
    fwrite(STDERR, PHP_EOL . 'Error: No authorization code received (timed out or cancelled).' . PHP_EOL);
    exit(1);
}

echo PHP_EOL . 'Authorization code received. Requesting refresh token...' . PHP_EOL;

try {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $auth_code,
    ]);
} catch (\Throwable $e) {
    fwrite(STDERR, PHP_EOL . 'Error requesting access token: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

$refresh_token = $token->getRefreshToken();

if (!$refresh_token) {
    fwrite(STDERR, PHP_EOL . 'Warning: No refresh token returned by provider.' . PHP_EOL);
    fwrite(STDERR, 'For Google: revoke application access in your Google Account security settings and re-run.' . PHP_EOL);
    exit(1);
}

echo PHP_EOL;
echo '================================================================================' . PHP_EOL;
echo ' SUCCESS! Your OAuth2 Refresh Token:' . PHP_EOL;
echo '================================================================================' . PHP_EOL;
echo PHP_EOL . $refresh_token . PHP_EOL . PHP_EOL;
echo '--------------------------------------------------------------------------------' . PHP_EOL;
echo 'Add the following to your include/config/conf.inc.php:' . PHP_EOL;
echo '--------------------------------------------------------------------------------' . PHP_EOL;
echo "\$phpwcms['SMTP_AUTH_TYPE']       = 'XOAUTH2';" . PHP_EOL;
echo "\$phpwcms['SMTP_XOAUTH_PROVIDER'] = '" . ($provider_name === 'google' ? 'Google' : 'Azure') . "';" . PHP_EOL;
echo "\$phpwcms['SMTP_CLIENT_ID']       = '" . addslashes($client_id) . "';" . PHP_EOL;
echo "\$phpwcms['SMTP_CLIENT_SECRET']   = '" . addslashes($client_secret) . "';" . PHP_EOL;
if ($tenant_id !== '') {
    echo "\$phpwcms['SMTP_TENANT_ID']       = '" . addslashes($tenant_id) . "';" . PHP_EOL;
}
echo "\$phpwcms['SMTP_REFRESH_TOKEN']   = '" . addslashes($refresh_token) . "';" . PHP_EOL;
echo '================================================================================' . PHP_EOL . PHP_EOL;
