<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2009 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

//used to convert old style file uploads

require_once ('../config/phpwcms/conf.inc.php');
require_once ('../include/inc_lib/default.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php');
require_once (PHPWCMS_ROOT.'/include/inc_lib/general.inc.php');

echo '<html><body><pre>';

echo 'CONVERT PAGELAYOUT' . LF;
echo '=================================================================' . LF.LF;

$pagelayout = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_pagelayout WHERE pagelayout_var NOT LIKE '%:{%'");

$c = 0;

foreach($pagelayout as $var) {
	$sql  = "UPDATE ".DB_PREPEND."phpwcms_pagelayout SET ";
	$sql .=	"pagelayout_var='".aporeplace(base64_decode($var['pagelayout_var']))."' ";
	$sql .= "WHERE pagelayout_id = ".$var['pagelayout_id'];
	$upgrade = _dbQuery($sql, 'UPDATE');

	echo html_specialchars($var['pagelayout_name']).': ';
	echo $upgrade['AFFECTED_ROWS'] ? $upgrade['AFFECTED_ROWS'] : html_specialchars($sql);
	echo LF;

	$c++;
}

if(!$c) echo 'No pagelayout for conversation found!';


echo '</pre></body></html>';

?>