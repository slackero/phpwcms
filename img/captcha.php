<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2025, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

$phpwcms = array();
$base_dir = dirname(__DIR__);
require_once $base_dir . '/include/config/conf.inc.php';
require_once $base_dir . '/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_ext/SPAF_FormValidator.class.php';

$spaf_obj = new SPAF_FormValidator();

// custom settings for phpwcms
$spaf_obj->setLibDir(PHPWCMS_TEMPLATE.'inc_captcha/');
$spaf_obj->work_dir	= PHPWCMS_ROOT.'/content/tmp/';
$spaf_obj->tag_ttl	= 5;
$spaf_char_num		= empty($_GET['length']) ? false : intval($_GET['length']);

if($spaf_char_num) {
	$spaf_obj->char_num	= min($spaf_char_num, 15);
}

$spaf_obj->streamImage();
