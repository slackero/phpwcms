<?php
/**
 * phpwcms — self-update GitHub API client
 **/

if (!defined('PHPWCMS_INCLUDE_CHECK')) {
    die('You are not allowed to access this file directly.');
}

const PHPWCMS_UPDATE_REPO = 'slackero/phpwcms';

// Standalone fallback: PHPWCMS_VERSION is normally defined by revision.php,
// which is not loaded when this client is used on its own.
if (!defined('PHPWCMS_VERSION')) {
    define('PHPWCMS_VERSION', '2.0.0-dev');
}

/**
 * Normalize a GitHub release tag to a plain version string.
 * Pre-release/draft tags (containing '-' or anything non x.y.z) return ''.
 */
function phpwcms_update_normalize_tag(string $tag): string
{
    $version = str_starts_with($tag, 'v') ? substr($tag, 1) : $tag;
    return preg_match('/^\d+\.\d+\.\d+$/', $version) ? $version : '';
}

/**
 * Single HTTP GET without redirect following.
 * Returns [body, statusCode, location] or false on transport error.
 */
function phpwcms_update_http_get_once(string $url): array|false
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
            CURLOPT_USERAGENT => 'phpwcms-selfupdate/' . PHPWCMS_VERSION,
        ]);
        $body = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $errno = curl_errno($ch);
        $location = (string)curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        curl_close($ch);
        if ($errno || !is_string($body)) {
            return false;
        }
        return [$body, $code, $location];
    }
    if (!ini_get('allow_url_fopen')) {
        return false;
    }
    $context = stream_context_create(['http' => [
        'timeout' => 30,
        'user_agent' => 'phpwcms-selfupdate/' . PHPWCMS_VERSION,
        'follow_location' => 0,
        'max_redirects' => 0,
    ]]);
    $body = @file_get_contents($url, false, $context);
    if ($body === false) {
        return false;
    }
    $code = 0;
    $location = '';
    foreach ($http_response_header as $header) {
        if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $header, $m)) {
            $code = (int)$m[1];
        }
        if (stripos($header, 'Location:') === 0) {
            $location = trim(substr($header, 9));
        }
    }
    return [$body, $code, $location];
}

/**
 * Whether a host is a trusted GitHub download host: github.com, any *.github.com
 * subdomain (api.github.com, codeload.github.com, ...) or objects.githubusercontent.com.
 */
function phpwcms_update_is_allowed_host(string $host): bool
{
    if (in_array($host, ['github.com', 'objects.githubusercontent.com'], true)) {
        return true;
    }
    return str_ends_with($host, '.github.com');
}

/**
 * HTTP GET via curl if available, else PHP streams. HTTPS enforced.
 * Redirects are walked manually so each hop's host can be re-validated.
 * When $restrictHosts is true, every hop must be a trusted GitHub host.
 * Returns string body or false.
 */
function phpwcms_update_http_get(string $url, bool $restrictHosts = false): string|false
{
    $redirects = 0;
    while (true) {
        if (str_starts_with($url, 'http://')) {
            return false; // never plain http
        }
        $host = (string)parse_url($url, PHP_URL_HOST);
        if ($restrictHosts && !phpwcms_update_is_allowed_host($host)) {
            return false;
        }
        $result = phpwcms_update_http_get_once($url);
        if ($result === false) {
            return false;
        }
        [$body, $code, $location] = $result;
        if (in_array($code, [301, 302, 303, 307, 308], true) && $location !== '') {
            if (++$redirects > 3) {
                return false;
            }
            if (preg_match('#^https?://#i', $location)) {
                $url = $location;
            } else {
                $url = (string)parse_url($url, PHP_URL_SCHEME) . '://' . (string)parse_url($url, PHP_URL_HOST) . $location;
            }
            continue;
        }
        if ($code !== 200) {
            return false;
        }
        return $body;
    }
}

/**
 * Fetch the latest release from GitHub. Returns release info array or false.
 */
function phpwcms_update_fetch_latest_release(): array|false
{
    $body = phpwcms_update_http_get('https://api.github.com/repos/' . PHPWCMS_UPDATE_REPO . '/releases/latest');
    if ($body === false) {
        return false;
    }
    $data = json_decode($body, true);
    if (!is_array($data) || empty($data['tag_name']) || empty($data['assets']) || !empty($data['draft']) || !empty($data['prerelease'])) {
        return false;
    }
    $version = phpwcms_update_normalize_tag((string)$data['tag_name']);
    if ($version === '') {
        return false;
    }
    $zipUrl = '';
    foreach ($data['assets'] as $asset) {
        if (!empty($asset['browser_download_url']) && str_ends_with(strtolower((string)$asset['name']), '.zip')) {
            $zipUrl = (string)$asset['browser_download_url'];
            break;
        }
    }
    if ($zipUrl === '') {
        return false;
    }
    return [
        'tag' => (string)$data['tag_name'],
        'version' => $version,
        'name' => (string)($data['name'] ?? ''),
        'notes' => (string)($data['body'] ?? ''),
        'zip' => $zipUrl,
        'date' => (string)($data['published_at'] ?? ''),
    ];
}

/**
 * Download the release zip asset to $targetPath. Returns bool.
 * Redirects are followed only to allowlisted HTTPS hosts.
 */
function phpwcms_update_download_asset(string $zipUrl, string $targetPath): bool
{
    if (!phpwcms_update_is_allowed_host((string)parse_url($zipUrl, PHP_URL_HOST))) {
        return false;
    }
    $body = phpwcms_update_http_get($zipUrl, true);
    if ($body === false) {
        return false;
    }
    return (bool)@file_put_contents($targetPath, $body);
}
