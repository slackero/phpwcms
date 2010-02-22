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


$chatlist = empty($_GET['l']) ? 0 : intval($_GET['l']); //Prüfen, wieviele Chatmeldungen angezeigt werden sollen
if(!$chatlist) $chatlist = 15; //Standardanzahl Chatmitteilungen

require_once(PHPWCMS_ROOT."/include/inc_lib/autolink.inc.php");

?><form action="include/inc_act/act_addchat.php" method="post" name="sendchatmessage" target="_top" onSubmit="window.document.cookie='chatstring=';"><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
		 <tr>
            <td colspan="2" class="title"><table width="490" border="0" cellpadding="0" cellspacing="0" summary="">
                <tr valign="bottom">
                  <td class="title"><?php echo $BL['be_chat_title'] ?></td>
                  <td align="right" class="chatlist"><?php echo $BL['be_chat_lines'] ?>:&nbsp;<a href="javascript:set_chatlist('5');">5</a>|<a href="javascript:set_chatlist('10');">10</a>|<a href="javascript:set_chatlist('15');">15</a>|<a href="javascript:set_chatlist('25');">25</a>|<a href="javascript:set_chatlist('50');">50</a>|<a href="javascript:set_chatlist('100');">100</a>|<a href="javascript:set_chatlist('99999');">ALL</a><input name="chatlist" type="hidden" value="<?php echo $chatlist ?>"></td>
                </tr>
              </table></td>
          </tr>
		  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
          <tr>
            <td width="490"><input name="chatmsg" type="text" id="chatmsg" style="font-family: Verdana, Arial, sans-serif; width:490px; font-size: 11px" size="40" maxlength="250" onKeyDown="timer=restart_reload(timer);"></td>
            <td width="48">&nbsp;<input name="Submit" type="image" id="Submit" src="img/button/send_chat_message.gif" width="36" height="15" border="0"></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
          <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
		  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
      </table></form>
      <table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
	  <?php
	  //Chatlisting
	  $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_chat WHERE chat_cat=0 ORDER BY chat_tstamp DESC LIMIT ".$chatlist.";";
	  $result = mysql_query($sql, $db);
	  while($row = mysql_fetch_array($result)) {
	  	if($row['chat_uid'] == $_SESSION['wcs_user_id']) {
			$chatclass=" class='chat'";
		} else {
			$chatclass="";
		}
	  	echo "<tr valign='top'>\n<td align='right'".$chatclass.">".$row["chat_name"].":&nbsp;</td>\n";
		echo "<td width='90%'".$chatclass.">".auto_link(html_specialchars($row["chat_text"]))."</td>\n</tr>\n";
		} //Chatlisting Ende
		
	  ?>
	  	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
		<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
		<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
      </table>