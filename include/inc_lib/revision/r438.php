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


// Revision 438 Update Check
function phpwcms_revision_r438() {
		
	$status = true;
	
	// do former revision check – fallback to r427
	if(phpwcms_revision_check_temp('427') !== true) {
		$status = phpwcms_revision_check('427');
	}
	
	// Fix possible problem
	_dbQuery('UPDATE '.DB_PREPEND."phpwcms_article SET article_subtitle = '' WHERE article_subtitle = '0'", 'UPDATE');
	_dbQuery('UPDATE '.DB_PREPEND."phpwcms_article SET article_menutitle = '' WHERE article_menutitle = '0'", 'UPDATE');
	_dbQuery('UPDATE '.DB_PREPEND."phpwcms_article SET article_description = '' WHERE article_description = '0'", 'UPDATE');
	
	return $status;
}

?>