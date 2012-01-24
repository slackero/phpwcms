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


$body_onload = ' onload="opt.init(document.forms[0])"';

$BE['HEADER']['optionselect.js']	 = getJavaScriptSourceLink('include/inc_js/optionselect.js');
$BE['HEADER']['message']			 = JS_START;
$BE['HEADER']['message']			.= 'var opt = new OptionTransfer("msg_send_to","msg_send_list");'.LF;
$BE['HEADER']['message']			.= 'opt.setAutoSort(true);'.LF;
$BE['HEADER']['message']			.= 'opt.setDelimiter(":");'.LF;
$BE['HEADER']['message']			.= 'opt.saveNewLeftOptions("msg_send_receiver");';
$BE['HEADER']['message']			.= JS_END;

?>