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



// RSS feed
$content["rssfeed"]['rssurl'] 		= clean_slweg($_POST["crss_url"]);
$content["rssfeed"]["template"]		= clean_slweg($_POST["crss_template"]);
$content["rssfeed"]["item"]			= (intval($_POST["crss_item"])) ? intval($_POST["crss_item"]) : '';
$content['rssfeed']["cut1st"]		= isset($_POST["crss_cut1st"]) ? 1 : 0;
$content['rssfeed']["cacheoff"]		= isset($_POST["crss_cacheoff"]) ? 1 : 0;
$content['rssfeed']["timeout"]		= strval(intval($_POST['crss_timeout']));

$content['rssfeed']["content_type"]	= '';

if( isset($_POST['crss_contenttype']) && in_array($_POST['crss_contenttype'], $phpwcms['charsets']) ) {
	$content['rssfeed']["content_type"]	= $_POST['crss_contenttype'];
}

?>