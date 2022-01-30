<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$new_login          = genlogname();
$new_password       = generic_string(8);
$new_email          = '';
$new_name           = '';
$set_user_aktiv     = 0;
$set_user_admin     = 0;
$set_user_fe        = 0;
$send_verification  = 1;
$user_err           = '';

if(isset($_POST["form_aktion"]) && $_POST["form_aktion"] === "create_account") {

    // Create Account
    $new_login          = trim(slweg($_POST["form_newloginname"]));
    $new_password       = slweg($_POST["form_newpassword"]);
    $new_email          = clean_slweg($_POST["form_newemail"]);
    $new_name           = clean_slweg($_POST["form_newrealname"]);
    $set_user_aktiv     = isset($_POST["form_active"]) ? 1 : 0;
    $set_user_admin     = isset($_POST["form_admin"]) ? 1 : 0;
    $set_user_fe        = isset($_POST["form_feuser"]) ? intval($_POST["form_feuser"]) : 0;
    if($set_user_admin) {
        $set_user_fe = 2;
    }
    $send_verification  = isset($_POST["verification_email"]) ? 1 : 0;
    if(empty($new_login)) {
        $user_err .= $BL['be_admin_usr_err2'].LF;
    } elseif(($check_anzahl = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_user WHERE usr_aktiv != 9 AND usr_login='._dbEscape($new_login), 'COUNT'))) {
        $user_err .= $BL['be_admin_usr_err1'].LF;
    }
    if(empty($new_password)) {
        $user_err .= $BL['be_admin_usr_err3'].LF;
    }
    if(!is_valid_email($new_email) && $send_verification) {
        $user_err .= $BL['be_admin_usr_err4'].LF;
    }
    if(empty($user_err)) { //Insert new User
        $sql =  "INSERT INTO ".DB_PREPEND."phpwcms_user (usr_login, usr_pass, usr_email, ".
                "usr_admin, usr_aktiv, usr_name, usr_wysiwyg, usr_fe ) VALUES ('".
                aporeplace($new_login)."', '".
                aporeplace(md5(makeCharsetConversion($new_password, PHPWCMS_CHARSET, 'utf-8')))."', '".
                aporeplace($new_email)."', '".
                $set_user_admin."', '".
                $set_user_aktiv."', '".
                aporeplace($new_name)."', 1, '".
                $set_user_fe."')";
        $result = _dbQuery($sql, 'INSERT');

        if(!empty($result['INSERT_ID'])) {
            $new_user_id = $result['INSERT_ID'];
            $user_ok = 1;
            if($send_verification) {
                $emailbody = str_replace('{LOGIN}', $new_login, $BL['be_admin_usr_mailbody']);
                $emailbody = str_replace('{PASSWORD}', $new_password, $emailbody);
                $emailbody = str_replace('{SITE}', PHPWCMS_URL, $emailbody);
                $emailbody = str_replace('{LOGIN_PAGE}', PHPWCMS_URL.get_login_file(), $emailbody);

                sendEmail(  array(
                    'recipient' => $new_email,
                    'toName'    => $new_name,
                    'subject'   => $BL['be_admin_usr_mailsubject'],
                    'isHTML'    => 0,
                    'text'      => $emailbody,
                    'from'      => $phpwcms["admin_email"],
                    'sender'    => $phpwcms["admin_email"]
                ));
            }
        }
    }
}

if(empty($user_ok)) {

?><form action="phpwcms.php?do=admin&amp;s=1" method="post" name="edituser" autocomplete="off"><table border="0" cellpadding="0" cellspacing="0" summary="">
          <tr>
            <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><img src="img/usricon/usr_add.gif" alt="" width="19" height="16"></td>
                  <td class="title">&nbsp;<?php echo $BL['be_admin_usr_title'] ?></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td><img src="img/leer.gif" alt="" width="105" height="1"></td>
            <td><img src="img/leer.gif" alt="" width="1" height="5"></td>
          </tr>
<?php
        if(!empty($user_err)):
?>
          <tr valign="top">
            <td align="right" class="error"><strong><?php echo $BL['be_admin_usr_err'] ?>:</strong>&nbsp;</td>
            <td class="error"><strong><?php echo nl2br(chop($user_err)) ?></strong></td>
          </tr>
          <tr valign="top"><td colspan="2" align="right" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="7"></td></tr>
<?php
        endif;
?>
          <tr>
            <td align="right" class="chatlist"><?php echo $BL["login_username"]  ?>:&nbsp;</td>
            <td><input name="form_newloginname" type="text" id="form_newloginname" value="<?php echo html($new_login); ?>" size="30" maxlength="200" autocomplete="off" class="width250" required="required" /></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr>
            <td align="right" class="chatlist"><?php echo $BL["login_userpass"] ?>:&nbsp;</td>
            <td class="nowrap">
                <input name="form_newpassword" type="password" id="form_newpassword" value="<?php echo html($new_password); ?>" size="30" maxlength="200" autocomplete="new-password" class="width250" required="required" />
                <span onclick="this.innerText = (togglePasswordVisibility('form_newpassword') === 'hide') ? '<?php echo $BL['be_password_hide']; ?>' : '<?php echo $BL['be_password_show']; ?>';" style="cursor:pointer">
                    <?php echo $BL['be_password_show']; ?>
                </span>
            </td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr>
            <td align="right" class="chatlist"><?php echo $BL['be_profile_label_email'] ?>:&nbsp;</td>
            <td><input name="form_newemail" type="email" id="form_newemail" value="<?php echo html($new_email); ?>" size="30" maxlength="250" autocomplete="off" class="width250" required="required" /></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr>
            <td align="right" class="chatlist"><?php echo $BL['be_admin_usr_realname'] ?>:&nbsp;</td>
            <td><input name="form_newrealname" type="text" id="form_newrealname" value="<?php echo html($new_name); ?>" size="30" maxlength="200" autocomplete="off" class="width250" required="required" /></td>
          </tr>

          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
          <tr>
            <td align="right" class="chatlist"><?php echo $BL['be_admin_usr_issection']  ?>:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
                <tr>
                  <td><input name="form_feuser" type="radio" id="form_feuser0" value="0"<?php is_checked($set_user_fe, 0); ?> /></td>
                  <td><label for="form_feuser0"><?php echo $BL['be_admin_usr_ifsection0'] ?></label>&nbsp;&nbsp;</td>
                  <td><input name="form_feuser" type="radio" id="form_feuser1" value="1"<?php is_checked($set_user_fe, 1); ?> /></td>
                  <td><label for="form_feuser1"><?php echo $BL['be_admin_usr_ifsection1'] ?></label>&nbsp;&nbsp;</td>
                  <td><input name="form_feuser" type="radio" id="form_feuser2" value="2"<?php is_checked($set_user_fe, 2); ?> /></td>
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
                  <td><input name="form_active" type="checkbox" id="form_active" value="1"<?php is_checked($set_user_aktiv, 1); ?> /></td>
                  <td><label for="form_active"><?php echo  $BL['be_admin_usr_iflogin'] ?></label></td>
                </tr>
              </table></td>
          </tr>

          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr>
            <td align="right" class="chatlist" style="color:#FF0000"><?php echo $BL['be_admin_usr_isadmin']  ?>:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><input name="form_admin" type="checkbox" id="form_admin" value="1"<?php is_checked($set_user_admin, 1); ?> /></td>
                  <td><label for="form_admin"><?php echo  $BL['be_admin_usr_ifadmin'] ?> <strong class="error">!!!</strong></label></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td align="right" class="chatlist"><?php echo $BL['be_admin_usr_verify'] ?>:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
                <tr bgcolor="#E7E8EB">
                  <td><input name="verification_email" type="checkbox" id="verification_email" value="1"<?php is_checked($send_verification, 1); ?>></td>
                  <td><label for="verification_email"><?php echo $BL['be_admin_usr_sendemail'] ?></label></td>
                  <td><img src="img/leer.gif" alt="" width="4" height="21"></td>
                </tr>
                <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
              </table></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"><input name="form_aktion" type="hidden" value="create_account" /></td></tr>
          <tr>
            <td>&nbsp;</td>
            <td><input name="Submit" type="submit" class="button" value="<?php echo $BL['be_admin_usr_button'] ?>" /></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
      </table></form><img src="img/lines/l538_70.gif" alt="" width="538" height="1"><br /><img src="img/leer.gif" alt="" width="1" height="5"><?php


} else {
    echo "<script type=\"text/javascript\"> timer=setTimeout(\"self.location.href='phpwcms.php'+'?".CSRF_GET_TOKEN."&do=admin'\", 0); </script>";
}
