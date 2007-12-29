<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
<tr><td class="title"><?php echo $BL['be_chat_title'] ?></td></tr>
<tr><td><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr valign="top"><td width="538"><?php echo $BL['be_chat_info'] ?></td></tr>
<tr><td><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td><a href="phpwcms.php?do=chat&p=1"><img src="img/symbole/link_grau.gif" alt="" width="13" height="9" border="0"><strong><?php echo $BL['be_chat_start'] ?></strong></a></td></tr>
<tr><td><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
</table>