<?php
/**
 * phpwcms setup and upgrade session bootstrap.
 *
 * The setup has no database yet, so the installation directory identifies its
 * session. Cookies ignore the port and the default PHPSESSID is shared by all
 * phpwcms installations on a host, so a distinct name, a scoped path and the
 * usual cookie flags are required here too.
 **/

// Deliberately not PHPWCMS_INCLUDE_CHECK: that constant is defined by the
// generated configuration, which only loads after this bootstrap.
if (!defined('PHPWCMS_SETUP_SESSION')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}

/**
 * Normalize the installation root passed by a setup entrypoint.
 *
 * Kept as a test seam for the path normalization. The caller knows whether it
 * runs from `<root>/setup` or from the document root, so this helper must not
 * guess by directory name.
 *
 * @param string $dir Installation root
 *
 * @return string Root without a trailing slash
 */
function setup_session_root($dir) {
    return rtrim(str_replace('\\', '/', (string)$dir), '/');
}

/**
 * Cookie path of the installation that hosts the setup.
 *
 * Scripts normally live in `<base>/setup/`; the standalone upgrader may also
 * be placed directly in the document root. A directory named `setup` cannot
 * be told apart from the setup subdirectory, so an installation served below
 * a URL base literally called `/setup/` gets the broader `/` cookie path.
 *
 * @param string $scriptName Value of $_SERVER['SCRIPT_NAME']
 *
 * @return string Directory path with a trailing slash, never a relative path
 */
function setup_session_cookie_path($scriptName) {
    $scriptName = str_replace('\\', '/', (string)$scriptName);
    if ($scriptName === '' || $scriptName[0] !== '/') {
        return '/';
    }

    $dir = dirname($scriptName);
    if (basename($dir) === 'setup') {
        $dir = dirname($dir);
    }
    if ($dir === '' || $dir === '.' || $dir === '/') {
        return '/';
    }

    return rtrim($dir, '/') . '/';
}

/**
 * Whether the setup request reached us over HTTPS.
 *
 * Only the direct connection counts. A forwarded proto header would let a
 * plain-HTTP client force the Secure flag and drop its own session cookie.
 *
 * @return bool
 */
function setup_request_is_https() {
    return !empty($_SERVER['HTTPS']) && in_array(strtolower((string)$_SERVER['HTTPS']), ['on', '1', 'true'], true);
}

/**
 * Start the setup session with an installation specific cookie.
 *
 * The legacy PHPSESSID cookie is deliberately not expired here: older setups
 * used path `/`, so deleting it could remove the session of an unrelated
 * application on the same host. The backend expires only its own scoped one.
 *
 * @param string $root Installation root, passed by the setup entrypoint
 *
 * @return void
 */
function setup_start_session($root) {
    $root = setup_session_root($root);
    if ($root === '') {
        // Never fall back to a name shared by every installation. The setup
        // directory separates installations in one document root; the
        // document root separates installations that share one setup/.
        $root = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/')
            . '|' . rtrim(str_replace('\\', '/', (string)($_SERVER['DOCUMENT_ROOT'] ?? '')), '/');
        error_log('phpwcms setup: empty installation root passed to the session bootstrap; using setup directory and document root');
    }
    $session_name = 'PHPSESSID' . substr(md5($root), 0, 10);

    if (session_status() !== PHP_SESSION_NONE) {
        // The name, path and cookie flags cannot change after session_start();
        // warn once, mitigate fixation, and leave the started session alone.
        if (session_name() !== $session_name && empty($_SESSION['phpwcms_setup_session_name_warned'])) {
            $_SESSION['phpwcms_setup_session_name_warned'] = true;
            error_log('phpwcms: session was started before phpwcms initialized it (cookie name "' . session_name() . '"); other installations on this host may share the session. Set session.auto_start=0 and make sure no library starts the session earlier');
        }
        if (empty($_SESSION['phpwcmsSetupSessionInit']) && !headers_sent() && session_regenerate_id(true)) {
            $_SESSION['phpwcmsSetupSessionInit'] = true;
        }

        return;
    }

    @ini_set('session.cookie_httponly', '1');
    // Lax keeps the wizard working behind a cross-site redirect; setup forms
    // are same-site submissions, so nothing is lost compared to Strict.
    @ini_set('session.cookie_samesite', 'Lax');
    @ini_set('session.cookie_path', setup_session_cookie_path($_SERVER['SCRIPT_NAME'] ?? ''));
    if (setup_request_is_https()) {
        @ini_set('session.cookie_secure', '1');
    }

    session_name($session_name);
    session_start();
    if (empty($_SESSION['phpwcmsSetupSessionInit']) && session_regenerate_id(true)) {
        // Prevent session fixation: the upgrader stores its CSRF token here.
        $_SESSION['phpwcmsSetupSessionInit'] = true;
    }
}
