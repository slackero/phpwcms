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
 * HTTP GET via curl if available, else PHP streams. HTTPS enforced.
 * Returns string body or false.
 */
function phpwcms_update_http_get(string $url): string|false
{
    if (str_starts_with($url, 'http://')) {
        return false; // never plain http
    }
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
            CURLOPT_USERAGENT => 'phpwcms-selfupdate/' . PHPWCMS_VERSION,
        ]);
        $body = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $errno = curl_errno($ch);
        curl_close($ch);
        if ($errno || $code !== 200 || !is_string($body)) {
            return false;
        }
        return $body;
    }
    if (!ini_get('allow_url_fopen')) {
        return false;
    }
    $context = stream_context_create(['http' => ['timeout' => 30, 'user_agent' => 'phpwcms-selfupdate/' . PHPWCMS_VERSION, 'max_redirects' => 3]]);
    $body = @file_get_contents($url, false, $context);
    return $body === false ? false : $body;
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
        if (!empty($asset['browser_download_url']) && str_ends_with((string)$asset['name'], '.zip')) {
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
 */
function phpwcms_update_download_asset(string $zipUrl, string $targetPath): bool
{
    $host = (string)parse_url($zipUrl, PHP_URL_HOST);
    if (!in_array($host, ['github.com', 'objects.githubusercontent.com', 'api.github.com'], true)) {
        return false;
    }
    $body = phpwcms_update_http_get($zipUrl);
    if ($body === false) {
        return false;
    }
    return (bool)@file_put_contents($targetPath, $body);
}
