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


$frontend_css = read_textfile(PHPWCMS_TEMPLATE."inc_css/frontend.css"); //reads the css template
$frontend_css = ($frontend_css) ? html_specialchars($frontend_css) : "";
		
?><form action="include/inc_act/act_frontendcss.php" method="post" name="css" target="_self"><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
	
	<tr>
	  <td colspan="2" class="title"><?php echo $BL['be_admin_css_title'] ?></td>
	</tr>
	<tr>
		<td width="35"><img src="img/leer.gif" alt="" width="35" height="4"></td>
		<td width="503"><img src="img/leer.gif" alt="" width="1" height="1"></td>
	</tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
	<tr>
		<td align="right" valign="top" class="chatlist"><a name="frontend_css"></a><img src="img/leer.gif" alt="" width="1" height="16"><?php echo $BL['be_admin_css_css'] ?>:&nbsp;
		<div style="text-align:right;padding:2px;padding-right:5px;padding-top:10px;">
		<a href="#frontend_css" onclick="contractField('frontend_css', 'V')"><img src="img/button/minus_11x11.gif" border="0" alt="-" width="11" height="11"></a><a href="#frontend_css" onclick="growField('frontend_css', 'V')"><img src="img/button/add_11x11.gif" border="0" alt="+" width="11" height="11"></a>
		</div>
		</td>
		<td><textarea name="frontend_css" cols="35" rows="25" wrap="OFF" class="msgtext" id="frontend_css" style="width:500px"><?php echo $frontend_css; ?></textarea></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="Submit" type="submit" class="button10" value="<?php echo $BL['be_admin_css_button'] ?>"></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
	
</table></form>
