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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Glossary module handle content part POST values

$content['glossary'] = array();
$content['glossary']['glossary_template']	= clean_slweg($_POST['glossary_template']);
$content['glossary']['glossary_filter']		= clean_slweg($_POST['glossary_filter']);
$content['glossary']['glossary_maxwords']	= intval($_POST['glossary_maxwords']);
if(empty($content['glossary']['glossary_maxwords'])) {
	$content['glossary']['glossary_maxwords'] = '';
}

$content['glossary']['glossary_tag']		= strtolower(clean_slweg($_POST['glossary_tag']));
$content['glossary']['glossary_noentry']	= slweg($_POST['glossary_noentry']);

?>