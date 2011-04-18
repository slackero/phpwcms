<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2011 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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

if($err) echo "<p class=\"error\"><b>Check your admin user name and password!</b></p>";

?>
<h1>7. Path settings </h1>
<form action="setup.php?step=2" method="post">
  <table border="0" cellpadding="0" cellspacing="0" summary="">
          <tr>
            <td align="right" class="v10">site basis:&nbsp;</td>
            <td><input name="site" type="text" class="f11b" id="site" value="<?php echo html_specialchars($phpwcms["site"]) ?>" size="30" style="width:200px"></td>
            <td class="chatlist"><em>&nbsp;default: http://www.mysite.com/</em></td>
          </tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td class="chatlist">&nbsp;</td>
            <td class="chatlist"><em>&nbsp;do NOT add any subdir here like</em><br><em>&nbsp;http://mysite.com/wcms/</em></td>
          </tr>
		   <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="6"></td></tr>

          <tr>
            <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="25"></td>
          </tr>
          <tr>
            <td colspan="3">Settings for handling email sending in phpwcms</td>
          </tr>
		  <tr><td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="15"></td></tr>
		  <tr>
            <td align="right" class="v10">from/reply-to&nbsp;email:&nbsp;</td>
            <td><input name="smtp_from_email" type="text" class="f11b" id="smtp_from_email" value="<?php echo ($phpwcms['SMTP_FROM_EMAIL']) ? html_specialchars($phpwcms['SMTP_FROM_EMAIL']) : html_specialchars($phpwcms["admin_email"]) ?>" size="30" style="width:200px"></td>
            <td class="chatlist"><em>&nbsp;default: <?php echo html_specialchars($phpwcms["admin_email"]) ?></em></td>
    </tr>
		  <tr>
		    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td>
    </tr>
		  <tr>
            <td align="right" class="v10">from/reply-to&nbsp;name:&nbsp;</td>
            <td><input name="smtp_from_name" type="text" class="f11b" id="smtp_from_name" style="width:200px" value="<?php echo ($phpwcms['SMTP_FROM_NAME']) ? html_specialchars($phpwcms['SMTP_FROM_NAME']) : 'webmaster' ?>" size="30"></td>
            <td class="chatlist"><em>&nbsp;default: webmaster</em></td>
    </tr>
			  <tr>
		    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td>
    </tr>
		  <tr>
            <td align="right" class="v10">SMTP&nbsp;server:&nbsp;</td>
            <td><input name="smtp_host" type="text" class="f11b" id="smtp_host" value="<?php echo ($phpwcms['SMTP_HOST']) ? html_specialchars($phpwcms['SMTP_HOST']) : 'localhost' ?>" size="30" style="width:200px"></td>
            <td class="chatlist"><em>&nbsp;default: localhost </em></td>
    </tr>
		  <tr>
		    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td>
    </tr>
		  <tr>
            <td align="right" class="v10">SMTP&nbsp;port :&nbsp;</td>
            <td><input name="smtp_port" type="text" class="f11b" id="smtp_port" style="width:200px" value="<?php echo ($phpwcms['SMTP_PORT']) ? intval($phpwcms['SMTP_PORT']) : '25'; ?>" size="30"></td>
            <td class="chatlist"><em>&nbsp;default: 25</em></td>
    </tr>
			  <tr>
		    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td>
    </tr>
		  <tr>
            <td align="right" class="v10">mail&nbsp;method :&nbsp;</td>
            <td><select name="smtp_mailer" id="smtp_mailer" class="f11b">
              <option value="mail" <?php if(strtolower($phpwcms['SMTP_MAILER']) == 'mail') echo 'selected="selected"'; ?>>PHP mail()</option>
              <option value="smtp" <?php if(strtolower($phpwcms['SMTP_MAILER']) == 'smtp') echo 'selected="selected"'; ?>>SMTP</option>
              <option value="sendmail" <?php if(strtolower($phpwcms['SMTP_MAILER']) == 'sendmail') echo 'selected="selected"'; ?>>UNIX sendmail</option>
            </select></td>
            <td class="chatlist"><em>&nbsp;default: mail (smtp, sendmail) </em></td>
    </tr>
		  <tr>
		    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td>
    </tr>
		  <tr>
            <td align="right" class="v10">use&nbsp;SMTP_AUTH :&nbsp;</td>
            <td><input name="smtp_auth" type="checkbox" id="smtp_auth" value="1" <?php if(intval($phpwcms['SMTP_AUTH']) == 1) echo 'checked="checked"'; ?> /></td>
            <td class="chatlist"><em>&nbsp;default: ON </em></td>
    </tr>
			  <tr>
		    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td>
    </tr>
		  <tr>
            <td align="right" class="v10">SMTP login:&nbsp;</td>
            <td><input name="smtp_user" type="text" class="f11b" id="smtp_user" value="<?php echo html_specialchars($phpwcms['SMTP_USER']) ?>" size="30" style="width:200px"></td>
            <td class="chatlist">&nbsp;</td>
    </tr>
		  <tr>
		    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="2"></td>
    </tr>
		  <tr>
            <td align="right" class="v10">SMTP&nbsp;password:&nbsp;</td>
            <td><input name="smtp_pass" type="text" class="f11b" id="smtp_pass" style="width:200px" value="<?php echo html_specialchars($phpwcms['SMTP_PASS']) ?>" size="30"></td>
            <td class="chatlist">&nbsp;</td>
    </tr>
		  <tr>
		    <td colspan="3"><img src="../img/leer.gif" alt="" width="1" height="15"></td>
    </tr>
          <tr>
            <td align="right" class="v10">&nbsp;</td>
            <td colspan="2"><input name="Submit" type="submit" class="button10" value="send site data"></td>
          </tr>
</table>
<input name="do" type="hidden" value="1"></form>
