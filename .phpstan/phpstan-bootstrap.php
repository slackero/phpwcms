<?php
/**
 * PHPStan Bootstrap File for phpwcms
 * Defines necessary constants and loads configuration/default libraries safely.
 */

// Initialize key global arrays expected by phpwcms
global $phpwcms, $content, $BL, $template_default, $indexpage;
$phpwcms = [];
$content = [];
$BL = [];
$template_default = [];
$indexpage = [];

// Navigates up from .phpstan/ to the project root directory
$projectRoot = dirname(__FILE__, 2);

// Mock $_SERVER environment variables to avoid undefined index notices and ensure safe paths
$_SERVER['DOCUMENT_ROOT'] = $projectRoot;
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['SERVER_PORT'] = '80';
$_SERVER['SCRIPT_FILENAME'] = 'dbdown.php';

// Mock essential keys in $phpwcms before loading files to prevent warnings
$phpwcms['USER_AGENT'] = [
    'agent' => 'cli',
    'version' => '1.0',
    'platform' => 'cli',
    'mobile' => false,
    'bot' => false,
    'webp' => false,
];
$phpwcms['parse_url'] = [
    'host' => 'localhost',
    'scheme' => 'http',
    'port' => 80,
];
$phpwcms['host_root'] = '/';

// Load configuration with fallback to dist config
if (file_exists($projectRoot . '/include/config/conf.inc.php')) {
    require_once $projectRoot . '/include/config/conf.inc.php';
} elseif (file_exists($projectRoot . '/include/config/dist.conf.inc.php')) {
    require_once $projectRoot . '/include/config/dist.conf.inc.php';
}

// Load default settings and constant definitions under output buffering to swallow headers
if (file_exists($projectRoot . '/include/inc_lib/default.inc.php')) {
    ob_start();
    require_once $projectRoot . '/include/inc_lib/default.inc.php';
    require_once $projectRoot . '/include/inc_lib/helper.session.php';
    require_once $projectRoot . '/include/inc_lib/dbcon.inc.php';
    require_once $projectRoot . '/include/config/conf.template_default.inc.php';
    require_once $projectRoot . '/include/config/conf.indexpage.inc.php';
    require_once $projectRoot . '/include/inc_lib/general.inc.php';
    require_once $projectRoot . '/include/inc_lib/backend.functions.inc.php';
    require_once $projectRoot . '/include/inc_lang/code.lang.inc.php';
    require_once $projectRoot . '/include/inc_lang/backend/en/lang.inc.php';
    require_once $projectRoot . '/include/inc_lang/backend/en/lang.ext.inc.php';
    ob_end_clean();
}

// Configure PHPStan DBA (Database Analysis) dynamic connection using conf.inc.php with fallbacks
if (class_exists('staabm\PHPStanDba\QueryReflection\QueryReflection')) {
    $host = getenv('DBA_HOST') ?: ($phpwcms['db_host'] ?? '127.0.0.1');
    $user = getenv('DBA_USER') ?: ($phpwcms['db_user'] ?? 'root');
    $pass = getenv('DBA_PASS') ?: ($phpwcms['db_pass'] ?? '');
    $dbname = getenv('DBA_DBNAME') ?: ($phpwcms['db_table'] ?? '');

    try {
        $mysqli = @new \mysqli($host, $user, $pass, $dbname);
        if (!$mysqli->connect_error) {
            $config = new \staabm\PHPStanDba\QueryReflection\RuntimeConfiguration();
            $reflector = new \staabm\PHPStanDba\QueryReflection\MysqliQueryReflector($mysqli);
            \staabm\PHPStanDba\QueryReflection\QueryReflection::setupReflector($reflector, $config);
        }
    } catch (\Throwable $e) {
        // Suppress connection exceptions during analysis
    }
}
