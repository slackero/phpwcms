<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$base_path = __DIR__;

if (is_file($base_path . '/setup.conf.inc.php')) {

	require_once $base_path . '/inc/setup.func.inc.php';
	require_once $base_path . '/setup.conf.inc.php';

	if (empty($NO_ACCESS)) {

		header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Content-type: text/plain');
		header('Content-Disposition: attachment; filename="conf.inc.php"');
		$filesize = @filesize($base_path . '/setup.conf.inc.php');
		if ($filesize) {
			header('Content-length: ' . $filesize);
			$temp = read_textfile($base_path . '/setup.conf.inc.php');
			// Lock the setup against re-downloading the configuration file.
			// write_conf_file() no longer emits a closing tag, so the lock
			// line is appended to the file instead of injected before one.
			if ($temp && !str_contains($temp, '$NO_ACCESS')) {
				write_textfile($base_path . '/setup.conf.inc.php', $temp . "\n\$NO_ACCESS = true;\n");
			}
		} else {
			$temp = 'Sorry there was a problem downloading "conf.inc.php". Check manually!';
		}

		echo $temp;

	} else {

		header('Location: ' . $phpwcms['site'] . $phpwcms['root']);

	}
}

exit();
