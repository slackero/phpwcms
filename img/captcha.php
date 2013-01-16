<?php

$phpwcms = array();
require_once ('../config/phpwcms/conf.inc.php');
require_once ('../include/inc_lib/default.inc.php');

// include FormValidator class
include_once (PHPWCMS_ROOT.'/include/inc_ext/SPAF_FormValidator.class.php');

// start session
// NOTICE: we have removed session_start() from this script since as of
// version 1.01 FormValidator is able to start the session by itself 
// session_start();

// instantiate the object
$spaf_obj = new SPAF_FormValidator();

// custom settings for phpwcms
$spaf_obj->setLibDir(PHPWCMS_TEMPLATE.'inc_captcha/');
$spaf_obj->work_dir		= PHPWCMS_ROOT.'/content/tmp/';
$spaf_obj->tag_ttl		= 5;

$spaf_char_num = empty($_GET['length']) ? 0 : intval($_GET['length']);

if($spaf_char_num) {
	if($spaf_char_num > 15) $spaf_char_num = 15;
	$spaf_obj->char_num	= $spaf_char_num;
}

// stream image
$spaf_obj->streamImage();

?>