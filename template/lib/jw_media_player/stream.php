<?php

$phpwcms = array();

$path = dirname(dirname(realpath(dirname(__FILE__))));

require_once ($path . '/config/phpwcms/conf.inc.php');
require_once ($path . '/include/inc_lib/default.inc.php');

// this example assumes your FLV files are in the "upload" directory of your website:
$file = trim($_GET['file']);

if(strlen($file) < 40) {

	$file = PHPWCMS_ROOT.'/'.PHPWCMS_FILES. basename($file);

	if(is_file($file)) {
	
		$mime = substr(trim($_GET['type']), 0, 30);
		//$pos = (isset($_GET["pos"]))  ? intval($_GET["pos"]) : 0;
		
		if($mime) {
			header('Content-Type: ' . $mime);
		}
		header('Content-Length: ' . filesize($file));
		
		/*
		if($pos > 0) {
			print("FLV");
			print(pack('C',1));
			print(pack('C',1));
			print(pack('N',9));
			print(pack('N',9));
		}
		
		$fh = fopen($file,"rb");
		fseek($fh, $pos);
		fpassthru($fh);
		fclose($fh);
		
		*/
	
		@readfile($file);

	}
} else {

	echo '';
	
}


?>