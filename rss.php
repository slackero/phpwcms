<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// creates feeds
// rss.php removed
// now redirect to "real" feeds generator script
require_once 'include/config/conf.inc.php';
require_once 'include/inc_lib/default.inc.php';
header('Location: '.PHPWCMS_URL.'feeds.php');
exit();
