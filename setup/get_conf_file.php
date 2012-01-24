<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

$base_path = dirname(__FILE__);

if(file_exists($base_path.'/setup.conf.inc.php')) {

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

?>