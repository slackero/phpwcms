<?php
/**
 * PHPStan Bootstrap File for cmsGO!
 * Defines necessary constants and loads configuration/default libraries safely.
 */

use staabm\PHPStanDba\QueryReflection\MysqliQueryReflector;
use staabm\PHPStanDba\QueryReflection\QueryReflection;
use staabm\PHPStanDba\QueryReflection\RuntimeConfiguration;

// Initialize key global arrays expected by cmsGO!
global $cmsgo, $content, $BL, $template_default, $indexpage;
$cmsgo = [];
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

// Mock essential keys in $cmsgo before loading files to prevent warnings
$cmsgo['USER_AGENT'] = [
    'agent' => 'cli',
    'version' => '1.0',
    'platform' => 'cli',
    'mobile' => false,
    'bot' => false,
];
$cmsgo['parse_url'] = [
    'host' => 'localhost',
    'scheme' => 'http',
    'port' => 80,
];
$cmsgo['host_root'] = '/';


// Load configuration
if (file_exists($projectRoot . '/include/config/conf.inc.php')) {
    require_once $projectRoot . '/include/config/conf.inc.php';
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
    require_once $projectRoot . '/include/inc_lang/backend/en/lang.pp.inc.php';
    require_once $projectRoot . '/include/inc_lang/image/image.en.php';
    ob_end_clean();
}

// Configure PHPStan DBA (Database Analysis) dynamic connection using conf.inc.php with fallbacks
if (class_exists(QueryReflection::class)) {
    // 1. Read parameters from conf.inc.php ($cmsgo array) with environment and hardcoded fallbacks
    $host = getenv('DBA_HOST') ?: ($cmsgo['db_host'] ?? '127.0.0.1');
    $user = getenv('DBA_USER') ?: ($cmsgo['db_user'] ?? 'cmsgo');
    $pass = getenv('DBA_PASS') ?: ($cmsgo['db_pass'] ?? 'cmsgo!');
    $dbname = getenv('DBA_DBNAME') ?: ($cmsgo['db_table'] ?? 'cmsgo_v2');

    // 2. Establish a connection for query reflection analysis
    $mysqli = @new \mysqli($host, $user, $pass, $dbname);
    if (!$mysqli->connect_error) {
        $config = new RuntimeConfiguration();
        $reflector = new MysqliQueryReflector($mysqli);
        QueryReflection::setupReflector($reflector, $config);
    }
}
