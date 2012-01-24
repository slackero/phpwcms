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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


$startup_default = read_textfile(PHPWCMS_TEMPLATE."inc_default/startup.php"); //reads the css template
$startup_default = ($startup_default) ? html_specialchars($startup_default) : "";
		
?><form action="include/inc_act/act_startuptext.php" method="post" name="startup" target="_self"><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
	
	<tr>
	  <td colspan="2" class="title"><?php echo $BL['be_admin_startup_title'] ?></td>
	</tr>
	<tr>
		<td width="35"><img src="img/leer.gif" alt="" width="35" height="4"></td>
		<td width="503"><img src="img/leer.gif" alt="" width="1" height="1"></td>
	</tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
	<tr><td colspan="2" class="chatlist"><?php echo $BL['be_admin_startup_text'] ?>:&nbsp;</td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr>
		<td colspan="2"><textarea name="startup_default" cols="35" rows="25" wrap="OFF" class="msgtext" id="startup_default" style="width:538px"><?php echo $startup_default; ?></textarea></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="Submit" type="submit" class="button10" value="<?php echo $BL['be_admin_startup_button'] ?>"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
	
</table></form>
