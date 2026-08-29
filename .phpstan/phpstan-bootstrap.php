<?php
/**
 * PHPStan Bootstrap File for phpwcms
 * Defines necessary constants and loads configuration/default libraries safely.
 */

use staabm\PHPStanDba\QueryReflection\MysqliQueryReflector;
use staabm\PHPStanDba\QueryReflection\QueryReflection;
use staabm\PHPStanDba\QueryReflection\RuntimeConfiguration;

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


// Load configuration — prefer the real config, fall back to the shipped
// distribution config so analysis works before conf.inc.php has been created.
if (file_exists($projectRoot . '/include/config/conf.inc.php')) {
    require_once $projectRoot . '/include/config/conf.inc.php';
} elseif (file_exists($projectRoot . '/include/config/dist.conf.inc.php')) {
    require_once $projectRoot . '/include/config/dist.conf.inc.php';
}

// Guarantee the direct-access guard constant is set so phpwcms library files
// do not bail out with "You Cannot Access This Script Directly" during analysis.
if (!defined('PHPWCMS_INCLUDE_CHECK')) {
    define('PHPWCMS_INCLUDE_CHECK', true);
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
    require_once $projectRoot . '/include/inc_lang/image/image.en.php';
    ob_end_clean();
}

// Configure PHPStan DBA (Database Analysis) dynamic connection using conf.inc.php with fallbacks
if (class_exists(QueryReflection::class)) {
    // 1. Read parameters from conf.inc.php ($phpwcms array) with environment and hardcoded fallbacks
    $host = getenv('DBA_HOST') ?: ($phpwcms['db_host'] ?? '127.0.0.1');
    $user = getenv('DBA_USER') ?: ($phpwcms['db_user'] ?? 'phpwcms');
    $pass = getenv('DBA_PASS') ?: ($phpwcms['db_pass'] ?? 'phpwcms!');
    $dbname = getenv('DBA_DBNAME') ?: ($phpwcms['db_table'] ?? 'phpwcms_v2');

    // 2. Establish a connection for query reflection analysis
    try {
        $mysqli = @new \mysqli($host, $user, $pass, $dbname);
        if (!$mysqli->connect_error) {
            $config = new RuntimeConfiguration();
            $reflector = new MysqliQueryReflector($mysqli);
            QueryReflection::setupReflector($reflector, $config);
        }
    } catch (\Throwable $e) {
        // Suppress connection exceptions during analysis
    }
}
