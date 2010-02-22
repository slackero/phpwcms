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


//email form

if(!isset($content["mailhtml"])) {
	$content["mailhtml"] = 0;
}

?><tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_subject'] ?>:&nbsp;</td>
	<td><input name="cmailsubject" type="text" id="cmailsubject" class="f11b" style="width: 440px" value="<?php echo  isset($content["mailsubject"]) ?  html_specialchars($content["mailsubject"]) : '' ?>" size="40" maxlength="250"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_recipient'] ?>:&nbsp;</td>
	<td><input name="cmailrecipient" type="text" id="cmailrecipient" class="f11b" style="width: 440px" value="<?php echo  isset($content["mailrecipient"]) ? html_specialchars($content["mailrecipient"]) : '' ?>" size="40" maxlength="250"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_buttontext']  ?>:&nbsp;</td>
	<td><input name="cmailbutton" type="text" id="cmailbutton" class="f11b" style="width: 150px" value="<?php echo  isset($content["mailbutton"]) ? html_specialchars($content["mailbutton"]) : '' ?>" size="20" maxlength="35"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_sendas'] ?>:&nbsp;</td>
	<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr bgcolor="#E7E8EB">
		<td><input name="cmailhtml" type="radio" value="0" <?php is_checked(0, $content["mailhtml"]); ?>></td>
	    <td class="v10"><?php echo $BL['be_cnt_text'] ?>&nbsp;&nbsp;</td>
	    <td><input name="cmailhtml" type="radio" value="1" <?php is_checked(1, $content["mailhtml"]); ?>></td>
	    <td class="v10"><?php echo $BL['be_cnt_html'] ?>&nbsp;</td>
		<td><img src="img/leer.gif" alt="" width="6" height="15"></td>
		</tr>
		</table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
	<td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_formfields'] ?>:&nbsp;</td>
	<td valign="top"><textarea name="cmailform" rows="15" wrap="off" class="msgtext" id="cmailform" style="width: 440px"><?php
	
	if(isset($content["mailform"])) {	
		if(is_array($content["mailform"])) {
			foreach($content["mailform"] as $formkey => $valform) {
				echo html_specialchars($content["mailform"][$formkey]['field'])."\n";
			}
		} else {
			echo html_specialchars($content["mailform"]);
		}
	} else {
		echo '';
	}
	 
	 ?></textarea></td>
</tr>