<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

// Main Backend Nav Definition

$wcsnav = array(

	"articles" =>	'<a href="phpwcms.php?do=articles">'.$BL['be_nav_articles'].'</a>',
	"files" =>		'<a href="phpwcms.php?do=files">'.$BL['be_nav_files'].'</a>',
	"modules" =>	'<a href="phpwcms.php?do=modules">'.$BL['be_nav_modules'].'</a>',
	"messages" =>	'<a href="phpwcms.php?do=messages&amp;p=4">'.$BL['be_nav_messages'].'</a>',
	//"chat" =>		'<a href="phpwcms.php?do=chat">'.$BL['be_nav_chat'].'</a>',
	"profile" =>	'<a href="phpwcms.php?do=profile">'.$BL['be_nav_profile'].'</a>',
	"admin" =>		'<a href="phpwcms.php?do=admin&amp;p=6">'.$BL['be_nav_admin'].'</a>',
	"navspace1" =>	''

	);

?>