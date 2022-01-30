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

$base_path = dirname(__FILE__);

if(is_file($base_path.'/setup.conf.inc.php')) {

	require_once($base_path.'/inc/setup.func.inc.php');
	require_once($base_path.'/setup.conf.inc.php');


	if(empty($NO_ACCESS)) {

		header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Datum in der Vergangenheit
		header('Content-type: text/plain');
		header('Content-Disposition: attachment; filename="conf.inc.php"');
		$filesize = @filesize($base_path.'/setup.conf.inc.php');
		if($filesize) {
			header('Content-length: '.$filesize);
			$temp = read_textfile($base_path.'/setup.conf.inc.php');
			write_textfile($base_path.'/setup.conf.inc.php', str_replace('?>', "\$NO_ACCESS = true;\n\n?>", $temp));
		} else {
			$temp = 'Sorry there was a problem downloading "conf.inc.php". Check manually!';
		}

		echo $temp;

	} else {

		header('Location: '.$phpwcms['site'].$phpwcms['root']);

	}
}

exit();
