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
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

/**
 * Embedded master public key for Ed25519 license verification (hex-encoded)
 */
if (!defined('PHPWCMS_WHITELABEL_PUBKEY')) {
    define('PHPWCMS_WHITELABEL_PUBKEY', 'f1a161b32dc0b778911c0eba897a320b0ad4b776695aa3f9e773e18753d3d76a');
}

/**
 * Base64 URL Decode
 */
function whitelabel_base64_url_decode(string $data): string {
    $remainder = strlen($data) % 4;
    if ($remainder) {
        $padlen = 4 - $remainder;
        $data .= str_repeat('=', $padlen);
    }

    return (string)base64_decode(strtr($data, '-_', '+/'));
}

/**
 * Base64 URL Encode
 */
function whitelabel_base64_url_encode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

/**
 * Check if the host matches the license domain wildcard pattern
 */
function whitelabel_domain_match(string $host, string $pattern): bool {
    $host = strtolower(trim(explode(':', $host)[0]));
    $pattern = strtolower(trim(explode(':', $pattern)[0]));

    if ($pattern === '*' || $pattern === '' || $pattern === $host) {
        return true;
    }

    if (str_starts_with($pattern, '*.')) {
        $base = substr($pattern, 2);
        return $host === $base || str_ends_with($host, '.' . $base);
    }

    return false;
}

/**
 * Verify white label license and set up configuration
 */
function phpwcms_whitelabel_init(array &$phpwcms): bool {
    $phpwcms['whitelabel'] = [
        'is_active'       => false,
        'licensee'        => '',
        'domain'          => '',
        'brand_name'      => 'phpwcms',
        'logo_light'      => '',
        'logo_dark'       => '',
        'logo_email'      => '',
        'custom_css'      => '',
        'support_url'     => '',
        'url'             => '',
        'copyright'       => '',
        'license_expires' => 0
    ];

    if (empty($phpwcms['whitelabel_key']) || !is_string($phpwcms['whitelabel_key'])) {
        if (!defined('PHPWCMS_WHITELABEL')) {
            define('PHPWCMS_WHITELABEL', false);
        }

        return false;
    }

    $parts = explode('.', trim($phpwcms['whitelabel_key']));
    if (count($parts) !== 2) {
        if (!defined('PHPWCMS_WHITELABEL')) {
            define('PHPWCMS_WHITELABEL', false);
        }

        return false;
    }

    if (!function_exists('sodium_crypto_sign_verify_detached') || !function_exists('sodium_hex2bin')) {
        if (!defined('PHPWCMS_WHITELABEL')) {
            define('PHPWCMS_WHITELABEL', false);
        }

        return false;
    }

    $payload_json = whitelabel_base64_url_decode($parts[0]);
    $signature    = whitelabel_base64_url_decode($parts[1]);

    $sig_bytes = defined('SODIUM_CRYPTO_SIGN_BYTES') ? SODIUM_CRYPTO_SIGN_BYTES : 64;
    if (!$payload_json || strlen($signature) !== $sig_bytes) {
        if (!defined('PHPWCMS_WHITELABEL')) {
            define('PHPWCMS_WHITELABEL', false);
        }

        return false;
    }

    $pubkey = sodium_hex2bin(PHPWCMS_WHITELABEL_PUBKEY);
    if (!sodium_crypto_sign_verify_detached($signature, $payload_json, $pubkey)) {
        if (!defined('PHPWCMS_WHITELABEL')) {
            define('PHPWCMS_WHITELABEL', false);
        }

        return false;
    }

    $payload = json_decode($payload_json, true);
    if (!is_array($payload)) {
        if (!defined('PHPWCMS_WHITELABEL')) {
            define('PHPWCMS_WHITELABEL', false);
        }

        return false;
    }

    // Check expiration if set
    if (!empty($payload['valid_until']) && (int)$payload['valid_until'] > 0 && time() > (int)$payload['valid_until']) {
        if (!defined('PHPWCMS_WHITELABEL')) {
            define('PHPWCMS_WHITELABEL', false);
        }

        return false;
    }

    // Check domain match against current host
    $current_host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? '');
    if (!empty($payload['domain']) && !whitelabel_domain_match($current_host, $payload['domain'])) {
        if (!defined('PHPWCMS_WHITELABEL')) {
            define('PHPWCMS_WHITELABEL', false);
        }

        return false;
    }

    // Determine custom product/brand name
    $brand_name = 'phpwcms';
    if (!empty($phpwcms['brand_name'])) {
        $brand_name = trim($phpwcms['brand_name']);
    } elseif (!empty($payload['brand_name']) && $payload['brand_name'] !== '*') {
        $brand_name = trim($payload['brand_name']);
    }

    // Determine custom website / support URL
    $brand_url = '';
    if (!empty($phpwcms['brand_url'])) {
        $brand_url = trim($phpwcms['brand_url']);
    } elseif (!empty($phpwcms['brand_support_url'])) {
        $brand_url = trim($phpwcms['brand_support_url']);
    } elseif (!empty($payload['url'])) {
        $brand_url = trim($payload['url']);
    } elseif (!empty($payload['support_url'])) {
        $brand_url = trim($payload['support_url']);
    }

    // Determine custom copyright
    $brand_copyright = '';
    if (!empty($phpwcms['brand_copyright'])) {
        $brand_copyright = trim($phpwcms['brand_copyright']);
    } elseif (!empty($payload['copyright'])) {
        $brand_copyright = trim($payload['copyright']);
    } elseif (!empty($payload['licensee'])) {
        $brand_copyright = trim($payload['licensee']);
    }

    $phpwcms['whitelabel'] = [
        'is_active'       => true,
        'licensee'        => $payload['licensee'] ?? '',
        'domain'          => $payload['domain'] ?? '',
        'brand_name'      => $brand_name,
        'logo_light'      => $phpwcms['brand_logo_light'] ?? '',
        'logo_dark'       => $phpwcms['brand_logo_dark'] ?? '',
        'logo_email'      => $phpwcms['brand_logo_email'] ?? '',
        'custom_css'      => $phpwcms['brand_custom_css'] ?? '',
        'support_url'     => $phpwcms['brand_support_url'] ?? ($payload['support_url'] ?? ''),
        'url'             => $brand_url,
        'copyright'       => $brand_copyright,
        'license_expires' => $payload['valid_until'] ?? 0
    ];

    $phpwcms['disable_generator'] = true;

    if (!defined('PHPWCMS_WHITELABEL')) {
        define('PHPWCMS_WHITELABEL', true);
    }

    return true;
}

/**
 * Check if white label mode is active
 */
function is_whitelabel(): bool {
    return defined('PHPWCMS_WHITELABEL') && PHPWCMS_WHITELABEL;
}

/**
 * Get current brand name
 */
function get_brand_name(): string {
    global $phpwcms;
    if (is_whitelabel() && !empty($phpwcms['whitelabel']['brand_name'])) {
        return (string)$phpwcms['whitelabel']['brand_name'];
    }

    return 'phpwcms';
}

/**
 * Get brand website / support URL
 */
function get_brand_url(): string {
    global $phpwcms;
    if (is_whitelabel()) {
        if (!empty($phpwcms['whitelabel']['url'])) {
            return (string)$phpwcms['whitelabel']['url'];
        }
        if (!empty($phpwcms['whitelabel']['support_url'])) {
            return (string)$phpwcms['whitelabel']['support_url'];
        }
        return '';
    }

    return 'https://www.phpwcms.org/';
}

/**
 * Get brand copyright string
 */
function get_brand_copyright(): string {
    global $phpwcms;
    if (is_whitelabel()) {
        $copyright = !empty($phpwcms['whitelabel']['copyright']) ? (string)$phpwcms['whitelabel']['copyright'] : '';
        if ($copyright !== '') {
            if (stripos($copyright, 'copyright') !== false || str_contains($copyright, '&copy;') || str_contains($copyright, '©')) {
                return $copyright;
            }
            return 'Copyright &copy; ' . date('Y') . ' ' . $copyright . (str_ends_with($copyright, '.') ? '' : '.');
        }
        $brand_name = get_brand_name();
        if ($brand_name !== '') {
            return 'Copyright &copy; ' . date('Y') . ' ' . $brand_name . '.';
        }
        return '';
    }

    return 'Copyright &copy; 2002-' . date('Y') . ' Oliver Georgi.';
}

/**
 * Get brand footer HTML markup
 */
function get_brand_footer(): string {
    $brand_name = html(get_brand_name());
    $brand_url  = get_brand_url();
    $copyright  = get_brand_copyright();

    if ($brand_url !== '') {
        $product_html = '<strong><a href="' . html_specialchars($brand_url) . '" target="_blank" style="text-decoration:none;">' . $brand_name . '</a></strong>';
    } elseif ($brand_name !== '') {
        $product_html = '<strong>' . $brand_name . '</strong>';
    } else {
        $product_html = '';
    }

    $parts = [];
    if ($product_html !== '') {
        $parts[] = $product_html;
    }
    if ($copyright !== '') {
        $parts[] = $copyright;
    }

    return implode(' | ', $parts);
}

/**
 * Get brand logo HTML markup supporting dark/light mode
 */
function get_brand_logo(string $class = '', string $link = 'index.php'): string {
    global $phpwcms;

    $brand_name = html(get_brand_name());
    $title = $brand_name . ' Content Management System';
    $class_attr = $class ? ' class="' . html_specialchars($class) . '"' : '';

    if (is_whitelabel()) {
        $logo_light = !empty($phpwcms['whitelabel']['logo_light']) ? $phpwcms['whitelabel']['logo_light'] : '';
        $logo_dark  = !empty($phpwcms['whitelabel']['logo_dark'])  ? $phpwcms['whitelabel']['logo_dark']  : $logo_light;

        if ($logo_light && $logo_dark && $logo_light !== $logo_dark) {
            $html  = '<a href="' . html_specialchars($link) . '"' . $class_attr . '>';
            $html .= '<img class="border-0 brand-logo-light d-theme-light-inline" src="' . html_specialchars($logo_light) . '" alt="' . $brand_name . '" title="' . $title . '" />';
            $html .= '<img class="border-0 brand-logo-dark d-theme-dark-inline" src="' . html_specialchars($logo_dark) . '" alt="' . $brand_name . '" title="' . $title . '" />';
            $html .= '</a>';
            return $html;
        }

        if ($logo_light) {
            return '<a href="' . html_specialchars($link) . '"' . $class_attr . '><img class="border-0 brand-logo" src="' . html_specialchars($logo_light) . '" alt="' . $brand_name . '" title="' . $title . '" /></a>';
        }
    }

    // Default phpwcms logo
    return '<a href="' . html_specialchars($link) . '"' . $class_attr . '><img class="border-0" src="img/phpwcms-logo.svg" alt="phpwcms Content Management System" title="phpwcms Content Management System" /></a>';
}

/**
 * Get brand bitmap email logo URL or data URI
 */
function get_brand_email_logo(): string {
    global $phpwcms;

    // Check explicit whitelabel email logo first
    if (is_whitelabel()) {
        if (!empty($phpwcms['whitelabel']['logo_email'])) {
            return (string)$phpwcms['whitelabel']['logo_email'];
        }
        if (!empty($phpwcms['whitelabel']['logo_light']) && (preg_match('#\.(png|jpe?g|gif|webp)$#i', $phpwcms['whitelabel']['logo_light']) || preg_match('#^data:image/(png|jpe?g|gif|webp);base64,#i', $phpwcms['whitelabel']['logo_light']))) {
            return (string)$phpwcms['whitelabel']['logo_light'];
        }
        return '';
    }

    return '';
}

/**
 * Get brand custom CSS link tag if configured
 */
function get_brand_custom_css(): string {
    global $phpwcms;
    $lf = defined('LF') ? LF : "\n";
    if (is_whitelabel() && !empty($phpwcms['whitelabel']['custom_css'])) {
        $css_file = $phpwcms['whitelabel']['custom_css'];
        return '<link href="' . html_specialchars($css_file) . '" rel="stylesheet" type="text/css">' . $lf;
    }

    return '';
}

/**
 * Filter language strings or arbitrary text to replace 'phpwcms' with brand name
 */
function apply_brand_replacements(mixed $data): mixed {
    if (!is_whitelabel()) {
        return $data;
    }

    $brand_name = get_brand_name();
    if ($brand_name === 'phpwcms') {
        return $data;
    }

    if (is_string($data)) {
        return str_replace(['phpwcms', 'phpWCMS', 'PHPWCMS'], [$brand_name, $brand_name, strtoupper($brand_name)], $data);
    }

    if (is_array($data)) {
        foreach ($data as $key => $val) {
            $data[$key] = apply_brand_replacements($val);
        }
    }

    return $data;
}
