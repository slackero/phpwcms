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





// THE FOLLOWIN FUNCTION IS RELEASED UNDER THE FOLLOWING OPEN SOUCE LICENCE
// http://keithdevens.com/software/license

function returnDayNameArray() {
	// taken from PHP Calendar (version 2.3), written by Keith Devens
	// http://keithdevens.com/software/php_calendar
	$day_names = array();
	for($n=0, $t=3*86400; $n<7; $n++, $t+=86400) {
		$day_names[$n] = ucfirst(gmstrftime('%A',$t));
	}
	return $day_names;
}




?>