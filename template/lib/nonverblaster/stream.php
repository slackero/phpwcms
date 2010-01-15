<?php

$phpwcms = array();

$path = rtrim(str_replace('\\', '/', dirname(dirname(dirname(realpath(dirname(__FILE__)))))), '/');

require_once ($path . '/config/phpwcms/conf.inc.php');
require_once ($path . '/include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT . '/include/inc_lib/general.inc.php');

$file = isset($_GET['file']) ? clean_slweg($_GET['file'], 40) : '';
$file = PHPWCMS_ROOT.'/'.PHPWCMS_FILES. basename($file);

if(is_file($file)) {
	
	ob_start();

	if(function_exists('mime_content_type') && empty($_GET['type'])) {
		$mime = @mime_content_type($file);
	} elseif(!empty($_GET['type'])) {
		$mime = clean_slweg($_GET['type'], 30);
	} else {
		$mime = '';
	}
	
	if(!$mime) {
		$ext = which_ext($file);
		switch($ext) {
			case 'mp3':		$mime='audio/x-mpeg';					break;
			case 'flv':		$mime='video/x-flv';					break;
			case 'mp4':
			case 'f4p':
			case 'f4v':		$mime='video/mp4';						break;
			case 'm4v':		$mime='video/x-m4v';					break;
			case '3gp':
			case '3gpp':	$mime='video/3gpp';						break;
			case 'f4a':
			case 'f4b':		$mime='audio/mp4';						break;
			case 'aif':
			case 'aiff':	$mime='audio/x-aiff';					break;
			case 'aac':		$mime='audio/x-aiff';					break;
			case 'jpeg':
			case 'jpg':		$mime='image/jpeg';						break;
			case 'png':		$mime='image/png';						break;
			case 'gif':		$mime='image/gif';						break;
			case 'swf':		$mime='application/x-shockwave-flash';	break;
		}
	}
	
	@ob_clean();
	
	if($mime) {
		header('Content-Type: ' . $mime);
	}
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($file));

	@readfile($file);

}

exit();

?>