<?php
// tests/core/test-session-name.php — run: php tests/core/test-session-name.php
// Every installation on one host shared the default PHPSESSID cookie, and
// cookies ignore the port, so two backends replaced each other's session.
// The cookie name is derived from the installation identity now.

$root = dirname(__DIR__, 2);
$lib = file_get_contents($root . '/include/inc_lib/default.inc.php');

define('PHPWCMS_ROOT', $root);

$failures = 0;

function check(bool $ok, string $message): void
{
    global $failures;
    if ($ok) {
        echo "OK   $message\n";
        return;
    }
    echo "FAIL $message\n";
    $failures++;
}

function extract_fn(string $source, string $name): void
{
    if (!preg_match('/^function ' . preg_quote($name, '/') . '\b.*?^}/ms', $source, $match)) {
        echo "FAIL cannot extract $name\n";
        exit(1);
    }

    eval($match[0]);
}

extract_fn($lib, 'phpwcms_session_name');
extract_fn($lib, 'phpwcms_init_session_name');

$one = phpwcms_session_name('', 'site-one|');
$two = phpwcms_session_name('', 'site-two|');

// Must run before any output, otherwise session_name() cannot be changed.
$applied = null;
$applied_constant = null;
if (session_status() === PHP_SESSION_NONE) {
    phpwcms_init_session_name('', 'site-one|');
    $applied = session_name();
    $applied_constant = PHPWCMS_SESSION_NAME;
}

check($one === phpwcms_session_name('', 'site-one|'), 'same installation keeps a stable name');
check($one !== $two, 'two installations on one host get different names');
check($one !== 'PHPSESSID' && $two !== 'PHPSESSID', 'the shared default name is not used');
check(preg_match('/^[A-Za-z0-9]+$/', $one) === 1, 'name is a valid cookie token');
check(phpwcms_session_name('mysite', 'site-one|') === 'mysite', 'configured name wins');
check(phpwcms_session_name('phpwcms-session', 'site-one|') === 'phpwcms-session', 'hyphen and underscore survive sanitizing');
check(phpwcms_session_name('my site;path=/', 'site-one|') === 'mysitepath', 'configured name is reduced to cookie-safe characters');
check(phpwcms_session_name('', '') !== 'PHPSESSID', 'empty seed still yields a distinct name');

check(str_contains($lib, 'phpwcms_init_session_name($phpwcms[\'session_name\']'), 'session name is applied while the config is loaded');
check(str_contains($lib, 'PHPWCMS_SESSION_NAME'), 'name is exposed as a constant');

$setupLib = file_get_contents($root . '/setup/inc/session.inc.php');
foreach (['setup/index.php', 'setup/setup.php', 'setup/upgrade.php'] as $setupFile) {
    $source = file_get_contents($root . '/' . $setupFile);
    $call = $setupFile === 'setup/upgrade.php'
        ? 'setup_start_session($docRoot)'
        : 'setup_start_session(dirname(__DIR__))';
    check(str_contains($source, $call), $setupFile . ' passes the resolved installation root');
    check(str_contains($source, "define('PHPWCMS_SETUP_SESSION'"), $setupFile . ' marks the bootstrap as included');
    check(!str_contains($source, 'session_start('), $setupFile . ' does not start a session itself');
}

$upgradeSource = file_get_contents($root . '/setup/upgrade.php');
$docRootPos = strpos($upgradeSource, '$docRoot = realpath($docRootCandidate)');
$startPos = strpos($upgradeSource, 'setup_start_session($docRoot)');
check(
    $docRootPos !== false && $startPos !== false && $docRootPos < $startPos,
    'upgrade resolves one canonical docroot before starting the session'
);
$definePos = strpos($upgradeSource, "define('PHPWCMS_SETUP_SESSION', true);");
$upgradeStartPos = strpos($upgradeSource, 'setup_start_session($docRoot)');
check(
    $definePos !== false && $upgradeStartPos !== false && $definePos < $upgradeStartPos,
    'upgrade defines the bootstrap constant before the browser session starts'
);
check(str_contains($setupLib, 'empty installation root'), 'empty root falls back to a unique installation identity');
check(str_contains($setupLib, 'PHPWCMS_SETUP_SESSION'), 'session include refuses direct access');
check(
    str_contains($setupLib, 'session was started before phpwcms initialized it'),
    'setup uses the shared session-name warning text'
);
$sessionTmp = sys_get_temp_dir() . '/phpwcms-setup-sess-' . getmypid();
@mkdir($sessionTmp, 0700, true);
register_shutdown_function(static function () use ($sessionTmp): void {
    foreach (glob($sessionTmp . '/*') ?: [] as $file) {
        @unlink($file);
    }
    @rmdir($sessionTmp);
});

$probe = <<<'PHP'
define('PHPWCMS_SETUP_SESSION', true);
require $argv[1];
$_SERVER['SCRIPT_NAME'] = '/foo/setup/index.php';
$_SERVER['HTTPS'] = $argv[2] ?? '';
setup_start_session('/srv/site');
echo json_encode(array('name' => session_name(), 'params' => session_get_cookie_params()));
PHP;

$runProbe = static function (string $https) use ($probe, $root, $sessionTmp) {
    if (!function_exists('shell_exec') || PHP_BINARY === '') {
        return false;
    }
    $cmd = escapeshellarg(PHP_BINARY)
        . ' -d session.save_path=' . escapeshellarg($sessionTmp)
        . ' -d session.use_cookies=1 -r ' . escapeshellarg($probe)
        . ' -- ' . escapeshellarg($root . '/setup/inc/session.inc.php')
        . ' ' . escapeshellarg($https)
        . ' 2>&1';
    $out = trim((string)shell_exec($cmd));
    $decoded = $out !== '' ? json_decode($out, true) : null;

    return is_array($decoded) ? $decoded : ['__error' => $out === '' ? 'no output' : $out];
};

$probeError = static function ($probeResult): string {
    if ($probeResult === false) {
        return 'shell_exec or PHP_BINARY unavailable';
    }
    if (is_array($probeResult) && isset($probeResult['__error'])) {
        return $probeResult['__error'];
    }

    return '';
};

$httpsProbe = $runProbe('on');
if ($probeError($httpsProbe) !== '') {
    echo 'SKIP setup session probe: ' . $probeError($httpsProbe) . "\n";
} else {
    check(
        $httpsProbe['name'] === 'PHPSESSID' . substr(md5('/srv/site'), 0, 10),
        'setup probe uses the installation cookie name'
    );
    check(!empty($httpsProbe['params']['httponly']), 'setup session sets HttpOnly');
    check(($httpsProbe['params']['samesite'] ?? '') === 'Lax', 'setup session sets SameSite=Lax');
    check(($httpsProbe['params']['path'] ?? '') === '/foo/', 'setup session scopes the cookie path');
    check(!empty($httpsProbe['params']['secure']), 'setup session uses Secure on HTTPS');
}

$httpProbe = $runProbe('off');
if ($probeError($httpsProbe) === '' && $probeError($httpProbe) !== '') {
    echo 'SKIP setup HTTP cookie probe: ' . $probeError($httpProbe) . "\n";
} elseif ($probeError($httpProbe) === '') {
    check(empty($httpProbe['params']['secure']), 'setup session omits Secure on HTTP');
}

extract_fn($setupLib, 'setup_session_root');
extract_fn($setupLib, 'setup_session_cookie_path');
extract_fn($setupLib, 'setup_request_is_https');

check(setup_session_root('/var/www/a/') === '/var/www/a', 'trailing slash is trimmed');
check(setup_session_root('/var/www/a/setup') === '/var/www/a/setup', 'root is taken as passed, not guessed');
check(setup_session_root('/var/www/setup') === '/var/www/setup', 'a document root named setup keeps its identity');
check(
    substr(md5(setup_session_root('/srv/a')), 0, 10) !== substr(md5(setup_session_root('/srv/b')), 0, 10),
    'two installation roots derive different names'
);

check(setup_session_cookie_path('/setup/index.php') === '/', 'root installation keeps the cookie path at /');
check(setup_session_cookie_path('/foo/setup/index.php') === '/foo/', 'subdirectory installation scopes the cookie path');
check(setup_session_cookie_path('/foo/upgrade.php') === '/foo/', 'upgrader in a subdirectory docroot keeps that scope');
check(setup_session_cookie_path('/upgrade.php') === '/', 'upgrader in the document root keeps /');
check(setup_session_cookie_path('') === '/', 'missing SCRIPT_NAME falls back to /');
check(setup_session_cookie_path('relative.php') === '/', 'a relative SCRIPT_NAME cannot produce ./-');

$savedServer = $_SERVER;
$_SERVER['HTTPS'] = 'off';
$_SERVER['SERVER_PORT'] = '80';
unset($_SERVER['HTTP_X_FORWARDED_PROTO']);
check(setup_request_is_https() === false, 'plain HTTP is not secure');
$_SERVER['HTTPS'] = '0';
check(setup_request_is_https() === false, 'HTTPS=0 is not treated as secure');
$_SERVER['SERVER_PORT'] = '443';
check(setup_request_is_https() === false, 'port 443 without HTTPS is not treated as secure');
$_SERVER['SERVER_PORT'] = '80';
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
check(setup_request_is_https() === false, 'a forwarded proto header is not trusted');
$_SERVER['HTTPS'] = 'on';
check(setup_request_is_https() === true, 'direct HTTPS is detected');
$_SERVER = $savedServer;
check(
    str_contains(file_get_contents($root . '/setup/inc/setup.func.inc.php'), "\$phpwcms['session_name'] = '';"),
    'generated config contains the session_name option'
);

check($applied_constant === $one, 'applied constant matches the derived name');
check($applied === $one, 'session_name() is switched to the installation cookie');

$init = strpos($lib, 'function _initSession()');
$session_name = strpos($lib, 'session_name(PHPWCMS_SESSION_NAME);', $init);
$session_start = strpos($lib, 'session_start();', $init);
check($session_name !== false && $session_start !== false && $session_name < $session_start, '_initSession sets the name before session_start()');

if ($failures) {
    echo "\n$failures check(s) failed\n";
    exit(1);
}

echo "\nall checks passed\n";
