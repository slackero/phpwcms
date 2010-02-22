<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// Content Type List
$content["text"] = html_specialchars(slweg($_POST["ctext"], 65500));

// check if minimum of 1 delimeter '~' available
if(substr($content['text'], 0, 1) != '~') $content['text'] = '~'.$content['text'];
if($content['text'] == '~') $content['text'] = '';

$content['bulletlist']["list_type"] = intval($_POST['clist_type']);
switch($content['bulletlist']["list_type"]) {
	case 0:
	case 1:
	case 2: 	break;
	default: 	$content['bulletlist']["list_type"] = 0;
}


?>