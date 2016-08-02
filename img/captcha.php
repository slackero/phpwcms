<?php

$phpwcms = array();
require_once '../include/config/conf.inc.php';
require_once '../include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_ext/SPAF_FormValidator.class.php';

$spaf_obj = new SPAF_FormValidator();

// custom settings for phpwcms
$spaf_obj->setLibDir(PHPWCMS_TEMPLATE.'inc_captcha/');
$spaf_obj->work_dir	= PHPWCMS_ROOT.'/content/tmp/';
$spaf_obj->tag_ttl	= 5;
$spaf_char_num		= empty($_GET['length']) ? false : intval($_GET['length']);

if($spaf_char_num) {
	$spaf_obj->char_num	= $spaf_char_num > 15 ? 15 : $spaf_char_num;
}

$spaf_obj->streamImage();
