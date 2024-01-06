<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2024, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// creates feeds
// rss.php removed
// now redirect to "real" feeds generator script
require_once 'include/config/conf.inc.php';
require_once 'include/inc_lib/default.inc.php';
header('Location: '.CMSGO_URL.'feeds.php');
exit();
