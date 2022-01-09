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

if(isset($_GET["u"]) && intval($_GET["u"])) {

    if(empty($_POST["form_aktion"]) || $_POST["form_aktion"] != "edit_account") {
        $new_user_id = intval($_GET["u"]);
        $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_user WHERE usr_id=".$new_user_id." AND usr_aktiv<>9";
        $result = _dbQuery($sql);
        if(isset($result[0]['usr_id'])) {
            $new_login = $result[0]["usr_login"];
            $new_email = $result[0]["usr_email"];
            $new_name = $result[0]["usr_name"];
            $set_user_aktiv = $result[0]["usr_aktiv"];
            $set_user_admin = $result[0]["usr_admin"];
            $set_user_fe    = $result[0]["usr_fe"];
            $set_user_var   = @unserialize($result[0]["usr_vars"]);
            $send_verification = 0;
            $new_password = '';
        }
    }

    $set_allowed_cp = isset($set_user_var['allowed_cp']) && is_array($set_user_var['allowed_cp']) ? $set_user_var['allowed_cp'] : array();

    if(isset($_POST["form_aktion"]) && $_POST["form_aktion"] == "edit_account") {

        // Handle account data

        $new_user_id = intval($_POST["form_uid"]);
        $new_login = slweg($_POST["form_newloginname"]);
        $new_password = slweg($_POST["form_newpassword"]);
        $new_email = clean_slweg($_POST["form_newemail"]);
        $new_name = clean_slweg($_POST["form_newrealname"]);
        $set_user_aktiv = isset($_POST["form_active"]) ? 1 : 0;
        $set_user_admin = isset($_POST["form_admin"]) ? 1 : 0;
        $set_user_fe = isset($_POST["form_feuser"]) ? intval($_POST["form_feuser"]) : 0;
        $set_allowed_cp = isset($_POST["allowed_cp"]) && is_array($_POST["allowed_cp"]) && count($_POST["allowed_cp"]) ? $_POST["allowed_cp"] : array();
        $set_allowed_cp_total = count($set_allowed_cp);
        $cp_total = empty($_POST["cp_total"]) ? 0 : intval($_POST["cp_total"]);
        if(!$set_allowed_cp_total || $cp_total == $set_allowed_cp_total) {
            $set_allowed_cp = array();
        }
        if($set_user_admin) {
            $set_user_fe = 2;
        }
        $send_verification = isset($_POST["verification_email"]) ? 1 : 0;
        $user_err = '';
        if(empty($new_login)) {
            $user_err = $BL['be_admin_usr_err2']."\n";
        } else {
            $sql = "SELECT usr_id, usr_vars, COUNT(*) AS anzahl FROM ".DB_PREPEND."phpwcms_user WHERE usr_login='".aporeplace($new_login)."' GROUP BY usr_id";
            $result = _dbQuery($sql);
            if(isset($result[0]['anzahl'])) {

                if($result[0]["usr_id"] != $new_user_id && $result[0]["anzahl"]) {
                    $user_err .= $BL['be_admin_usr_err1']."\n";
                }

                if(empty($user_err)) {
                    $set_user_var = @unserialize($result[0]["usr_vars"]);
                    if(!is_array($set_user_var)) {
                        $set_user_var = array();
                    }
                    $set_user_var['allowed_cp'] = $set_allowed_cp;
                }

            }
        }

        if(!is_valid_email($new_email)) {
            $user_err .= $BL['be_admin_usr_err4']."\n";
        }
        if(empty($user_err)) { //Insert new User

            $sql =  "UPDATE ".DB_PREPEND."phpwcms_user SET usr_login='".aporeplace($new_login)."', ";
            if($new_password) {
                $sql .= "usr_pass='".aporeplace(md5(makeCharsetConversion($new_password, PHPWCMS_CHARSET, 'utf-8')))."', ";
            }
            $sql .= "usr_email='".aporeplace($new_email)."', ".
                    "usr_admin='".$set_user_admin."', ".
                    "usr_aktiv='".$set_user_aktiv."', ".
                    "usr_name='".aporeplace($new_name)."', ";
            if(isset($set_user_var['allowed_cp'])) {
                $sql .= "usr_vars="._dbEscape(serialize($set_user_var)).", ";
            }
            $sql .= "usr_fe='".$set_user_fe."' WHERE usr_id=".$new_user_id;
            $result = _dbQuery($sql, 'UPDATE');
            if(isset($result['AFFECTED_ROWS'])) {
                $user_ok = 1;
                $new_user_id = NULL;
                if($send_verification) {
                    $emailbody = str_replace('{LOGIN}',         $new_login, $BL['be_admin_usr_emailbody']);
                    $emailbody = str_replace('{PASSWORD}',      (($new_password) ? $new_password : $BL['be_admin_usr_passnochange']), $emailbody);
                    $emailbody = str_replace('{SITE}',          PHPWCMS_URL, $emailbody);
                    $emailbody = str_replace('{LOGIN_PAGE}',    PHPWCMS_URL.get_login_file(), $emailbody);

                    sendEmail(array(
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

?><form action="phpwcms.php?do=admin&amp;s=2&amp;u=<?php echo $new_user_id ?>" method="post" name="edituser" autocomplete="off"><table border="0" cellpadding="0" cellspacing="0" summary="">

          <tr><td colspan="2" class="title"><?php echo $BL['be_admin_usr_etitle'] ?></td></tr>
          <tr>
            <td><img src="img/leer.gif" alt="" width="105" height="1"></td>
            <td><img src="img/leer.gif" alt="" width="1" height="7"></td>
          </tr>
          <?php
          if(!empty($user_err)) {
          ?>
          <tr valign="top">
            <td align="right" class="error"><strong><?php echo $BL['be_admin_usr_err'] ?>:</strong>&nbsp;</td>
            <td class="error"><strong><?php echo nl2br(chop($user_err)) ?></strong></td>
          </tr>
          <tr valign="top"><td colspan="2" align="right" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="7"></td></tr>
          <?php
          } //Ende Fehler New User
          ?>
          <tr>
            <td align="right" class="chatlist"><?php echo $BL["login_username"]  ?>:&nbsp;</td>
            <td><input name="form_newloginname" type="text" id="form_newloginname" class="width250" value="<?php echo html($new_login); ?>" size="30" maxlength="200" autocomplete="off" required="required" /></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr>
            <td align="right" class="chatlist"><?php echo $BL["login_userpass"] ?>:&nbsp;</td>
            <td>
                <input name="form_newpassword" type="password" id="form_newpassword" class="width250" value="<?php echo html($new_password); ?>" size="30" maxlength="200" autocomplete="new-password" />
                <span onclick="this.innerText = (togglePasswordVisibility('form_newpassword') === 'hide') ? '<?php echo $BL['be_password_hide']; ?>' : '<?php echo $BL['be_password_show']; ?>';" style="cursor:pointer">
                    <?php echo $BL['be_password_show']; ?>
                </span>
            </td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr>
            <td align="right" class="chatlist"><?php echo $BL['be_profile_label_email'] ?>:&nbsp;</td>
            <td><input name="form_newemail" type="email" id="form_newemail" class="width250" value="<?php echo html($new_email); ?>" size="30" maxlength="250" autocomplete="off" required="required" /></td>
          </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr>
            <td align="right" class="chatlist"><?php echo $BL['be_admin_usr_realname'] ?>:&nbsp;</td>
            <td><input name="form_newrealname" type="text" id="form_newrealname" class="width250" value="<?php echo html($new_name); ?>" size="30" maxlength="200" autocomplete="off" required="required" /></td>
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
                  <td><label for="form_active"><?php echo $BL['be_admin_usr_iflogin'] ?></label></td>
                </tr>
              </table></td>
          </tr>

          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
          <tr>
            <td align="right" class="chatlist" style="color:#FF0000"><?php echo $BL['be_admin_usr_isadmin'] ?>:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><input name="form_admin" type="checkbox" id="form_admin" value="1"<?php is_checked(1, $set_user_admin); ?> /></td>
                  <td><label for="form_admin"><?php echo $BL['be_admin_usr_ifadmin'] ?> <strong class="error">!!!</strong></label></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td align="right" class="chatlist"><?php echo $BL['be_admin_usr_verify'] ?>:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
                <tr bgcolor="#E7E8EB">
                  <td><input name="verification_email" type="checkbox" id="verification_email" value="1"<?php is_checked(1, $send_verification); ?> /></td>
                  <td><label for="verification_email"><?php echo $BL['be_admin_usr_sendemail'] ?></label></td>
                  <td><img src="img/leer.gif" alt="" width="4" height="21"></td>
                </tr>
                <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
              </table></td>
          </tr>

            <tr>
                <td align="right" class="chatlist tdtop6 nowrap"><?php echo $BL['be_structform_select_cp'] ?>:&nbsp;</td>
                <td class="checkbox-list v11">
          <?php
                $has_allowed_cp = isset($set_allowed_cp) ? count($set_allowed_cp) : 0;

                foreach($wcs_content_type as $key => $value):

                    // count used CPs so it is easier to decide if needed or not
                    $used_count = _dbCount('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_articlecontent WHERE acontent_trash=0 AND acontent_type='._dbEscape($key));
          ?>

                    <label>
                        <input type="checkbox" name="allowed_cp[<?php echo $key ?>]" value="<?php echo $key ?>"<?php if(!$has_allowed_cp || isset($set_allowed_cp[$key])): ?> checked="checked"<?php endif; ?> />
                        <?php echo html($value).' ('.$used_count.')' ?>
                    </label>

          <?php endforeach; ?>
                    <input type="hidden" name="cp_total" value="<?php echo count($wcs_content_type) ?>" />
                </td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="tdbottom10 tdtop6">
                <input name="Submit" type="submit" class="button" value="<?php echo $BL['be_admin_usr_ebutton'] ?>" />
                <input name="form_aktion" type="hidden" value="edit_account" />
                <input name="form_uid" type="hidden" value="<?php echo html($new_user_id) ?>" />
            </td>
          </tr>
      </table>


      </form>

      <img src="img/lines/l538_70.gif" alt="" width="538" height="1"><br />
      <img src="img/leer.gif" alt="" width="1" height="5">
<?php

    } else {

        echo "<script type=\"text/JavaScript\"> timer=setTimeout(\"self.location.href='phpwcms.php'+'?".CSRF_GET_TOKEN."&do=admin'\", 0); </script>";

    }
}
