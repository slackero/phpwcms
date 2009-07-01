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


if(isset($_GET["u"]) && intval($_GET["u"])) {
	if(empty($_POST["form_aktion"]) || $_POST["form_aktion"] != "edit_account") {
		$new_user_id = intval($_GET["u"]);
		$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_user WHERE usr_id=".$new_user_id." AND usr_aktiv<>9;";
		if($result = mysql_query($sql, $db) or die("error")) {
			if($row = mysql_fetch_array($result)) {
				$new_login = $row["usr_login"];
				$new_email = $row["usr_email"];
				$new_name = $row["usr_name"];
				$set_user_aktiv = $row["usr_aktiv"];
				$set_user_admin = $row["usr_admin"];
				$set_user_fe	= $row["usr_fe"];
				$send_verification = 0;			
				$new_password = '';	
			}
		}
	}
	if(isset($_POST["form_aktion"]) && $_POST["form_aktion"] == "edit_account") {
		//Create Account Daten verarbeiten
		$new_user_id = intval($_POST["form_uid"]);
		$new_login = clean_slweg($_POST["form_newloginname"]);
		$new_password = clean_slweg($_POST["form_newpassword"]);
		$new_email = clean_slweg($_POST["form_newemail"]);
		$new_name = clean_slweg($_POST["form_newrealname"]);
		$set_user_aktiv = isset($_POST["form_active"]) ? 1 : 0;
		$set_user_admin = isset($_POST["form_admin"]) ? 1 : 0;
		$set_user_fe = isset($_POST["form_feuser"]) ? intval($_POST["form_feuser"]) : 0;
		if($set_user_admin) {
			$set_user_fe = 2;
		}
		$send_verification = isset($_POST["verification_email"]) ? 1 : 0;
		if(isEmpty($new_login)) {
			$user_err = $BL['be_admin_usr_err2']."\n";
		} else {
			$sql = "SELECT usr_id, COUNT(*) AS anzahl FROM ".DB_PREPEND."phpwcms_user WHERE usr_login='".aporeplace($new_login)."' GROUP BY usr_id;";
			if($result = mysql_query($sql, $db)) {
				if($check_anzahl = mysql_fetch_array($result)) {
					if($check_anzahl["usr_id"] != $new_user_id && $check_anzahl["anzahl"]) $user_err .= $BL['be_admin_usr_err1']."\n";
				}
			}
		}
		if(MailVal($new_email, 3) && $send_verification) $user_err .= $BL['be_admin_usr_err4']."\n";
		if(empty($user_err)) { //Insert new User
			$upd_password = ($new_password) ? "usr_pass='".aporeplace(md5($new_password))."', " : "";
			//$upd_password = ($new_password) ? "usr_pass=PASSWORD('".aporeplace($new_password)."'), " : "";
			$sql =	"UPDATE ".DB_PREPEND."phpwcms_user SET ".
					"usr_login='".aporeplace($new_login)."', ".$upd_password.
					"usr_email='".aporeplace($new_email)."', ".
					"usr_admin='".$set_user_admin."', ".
					"usr_aktiv='".$set_user_aktiv."', ".
					"usr_name='".aporeplace($new_name)."', ".
					"usr_wysiwyg='".$GLOBALS['phpwcms']['wysiwyg_editor']."', ".
					"usr_fe='".$set_user_fe."' ".
					"WHERE usr_id=".$new_user_id.";";								
			if($result = mysql_query($sql, $db) or die("error")) {
				$user_ok = 1;
				$new_user_id = NULL;
				if($send_verification) {
					$emailbody = str_replace('{LOGIN}',    $new_login, $BL['be_admin_usr_emailbody']);
					$emailbody = str_replace('{PASSWORD}', (($new_password) ? $new_password : $BL['be_admin_usr_passnochange']), $emailbody);
					$emailbody = str_replace('{SITE}',     PHPWCMS_URL, $emailbody);

					sendEmail(	array(
						'recipient'	=> $new_email,
						'toName'	=> $new_name,
						'subject'	=> $BL['be_admin_usr_mailsubject'],
						'isHTML'	=> 0,
						'text'		=> $emailbody,
						'from'		=> $phpwcms["admin_email"],
						'sender'	=> $phpwcms["admin_email"]
				        ));
				}
			}					
		}				
	}
			
	if(empty($user_ok)) {
		
?><form action="phpwcms.php?do=admin&amp;s=2&amp;u=<?php echo $new_user_id ?>" method="post" name="edituser"><table border="0" cellpadding="0" cellspacing="0" summary="">
		
          <tr><td colspan="2" class="title"><?php echo $BL['be_admin_usr_etitle'] ?></td></tr>
          <tr> 
            <td><img src="img/leer.gif" alt="" width="105" height="1"></td>
            <td><img src="img/leer.gif" alt="" width="1" height="7"></td>
          </tr>
		  <?php
		  if(!empty($user_err)) {
		  ?>
          <tr valign="top">
            <td align="right"><strong style="color:#FF3300"><?php echo $BL['be_admin_usr_err'] ?>:</strong>&nbsp;</td>
            <td><strong style="color:#FF3300"><?php echo nl2br(chop($user_err)) ?></strong></td>
          </tr>
          <tr valign="top"><td colspan="2" align="right" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="7"></td></tr>
		  <?php
		  } //Ende Fehler New User
		  ?>
          <tr> 
            <td align="right" class="chatlist"><?php echo $BL["login_username"]  ?>:&nbsp;</td>
            <td><input name="form_newloginname" type="text" id="form_newloginname" style="font-family: Verdana, Arial, Helvetica, sans-serif; width:250px; font-size: 11px; font-weight: bold;" value="<?php echo $new_login ?>" size="30" maxlength="30"></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr> 
            <td align="right" class="chatlist"><?php echo $BL["login_userpass"] ?>:&nbsp;</td>
            <td><input name="form_newpassword" type="text" id="form_newpassword" style="font-family: Verdana, Arial, Helvetica, sans-serif; width:250px; font-size: 11px; font-weight: bold;" value="<?php echo $new_password ?>" size="30" maxlength="20"></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr> 
            <td align="right" class="chatlist"><?php echo $BL['be_profile_label_email'] ?>:&nbsp;</td>
            <td><input name="form_newemail" type="text" id="form_newemail" style="font-family: Verdana, Arial, Helvetica, sans-serif; width:250px; font-size: 11px; font-weight: bold;" value="<?php echo $new_email ?>" size="30" maxlength="150"></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr> 
            <td align="right" class="chatlist"><?php echo $BL['be_admin_usr_realname'] ?>:&nbsp;</td>
            <td><input name="form_newrealname" type="text" id="form_newrealname" style="font-family: Verdana, Arial, Helvetica, sans-serif; width:250px; font-size: 11px; font-weight: bold;" value="<?php echo $new_name ?>" size="30" maxlength="80"></td>
          </tr>

		  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
          <tr> 
            <td align="right" class="chatlist"><?php echo $BL['be_admin_usr_issection']  ?>:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
                <tr> 
                  <td><input name="form_feuser" type="radio" id="form_feuser0" value="0"<?php is_checked($set_user_fe, 0); ?>></td>
                  <td><label for="form_feuser0"><?php echo $BL['be_admin_usr_ifsection0'] ?></label>&nbsp;&nbsp;</td>
				  <td><input name="form_feuser" type="radio" id="form_feuser1" value="1"<?php is_checked($set_user_fe, 1); ?>></td>
                  <td><label for="form_feuser1"><?php echo $BL['be_admin_usr_ifsection1'] ?></label>&nbsp;&nbsp;</td>
				  <td><input name="form_feuser" type="radio" id="form_feuser2" value="2"<?php is_checked($set_user_fe, 2); ?>></td>
                  <td><label for="form_feuser2"><?php echo $BL['be_admin_usr_ifsection2'] ?></label></td>
				  <td><img src="img/leer.gif" alt="" width="4" height="21"></td>
                </tr>
              </table></td>
          </tr>	

          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
          <tr> 
            <td align="right" class="chatlist"><?php echo $BL['be_admin_usr_setactive'] ?>:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr> 
                  <td><input name="form_active" type="checkbox" id="form_active" value="1"<?php is_checked($set_user_aktiv, 1); ?>></td>
                  <td><label for="form_active"><?php echo $BL['be_admin_usr_iflogin'] ?></label></td>
                </tr>
              </table></td>
          </tr>
  
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr> 
            <td align="right" class="chatlist" style="color:#FF0000"><?php echo $BL['be_admin_usr_isadmin'] ?>:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr> 
                  <td><input name="form_admin" type="checkbox" id="form_admin" value="1"<?php is_checked(1, $set_user_admin); ?>></td>
                  <td><label for="form_admin"><?php echo $BL['be_admin_usr_ifadmin'] ?> <strong style="color:#FF0000">!!!</strong></label></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td align="right" class="chatlist"><?php echo $BL['be_admin_usr_verify'] ?>:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
                <tr bgcolor="#E7E8EB"> 
                  <td><input name="verification_email" type="checkbox" id="verification_email" value="1"<?php is_checked(1, $send_verification); ?>></td>
				  <td><label for="verification_email"><?php echo $BL['be_admin_usr_sendemail'] ?></label></td>
                  <td><img src="img/leer.gif" alt="" width="4" height="21"></td>
                </tr>
                <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
              </table></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"><input name="form_aktion" type="hidden" value="edit_account"><input name="form_uid" type="hidden" value="<?php echo $new_user_id ?>"></td></tr>
          <tr> 
            <td>&nbsp;</td>
            <td><input name="Submit" type="submit" class="button10" value="<?php echo $BL['be_admin_usr_ebutton'] ?>"></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
       
      </table></form><img src="img/lines/l538_70.gif" alt="" width="538" height="1"><br /><img src="img/leer.gif" alt="" width="1" height="5"><?php
 	} else {
		echo "<script language=\"JavaScript\" type=\"text/JavaScript\">\n<!--\n";
		echo "timer=setTimeout(\"self.location.href='phpwcms.php?do=admin'\", 0);\n";
		echo "//-->\n</script>\n";
	}
}	
//Dialog New User bis hierher
?>