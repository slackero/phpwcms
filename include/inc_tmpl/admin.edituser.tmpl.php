<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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
            $set_user_2fa   = !empty($result[0]["usr_2fa_enabled"]);
            $set_user_var   = @unserialize($result[0]["usr_vars"], ['allowed_classes' => false]);
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
                    $set_user_var = @unserialize($result[0]["usr_vars"], ['allowed_classes' => false]);
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
                $bcrypt_pass = password_hash(makeCharsetConversion($new_password, PHPWCMS_CHARSET, 'utf-8'), PASSWORD_DEFAULT);
                $sql .= "usr_pass='".aporeplace($bcrypt_pass)."', ";
            }
            if(!empty($_POST['form_reset_2fa'])) {
                $sql .= "usr_2fa_enabled=0, usr_2fa_secret='', ";
                if(isset($set_user_var['2fa_backup_codes'])) {
                    unset($set_user_var['2fa_backup_codes']);
                }
            }
            $sql .= "usr_email='".aporeplace($new_email)."', ".
                    "usr_admin='".$set_user_admin."', ".
                    "usr_aktiv='".$set_user_aktiv."', ".
                    "usr_name='".aporeplace($new_name)."', ";
            if(isset($set_user_var['allowed_cp']) || !empty($_POST['form_reset_2fa'])) {
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

    ?><form action="phpwcms.php?do=admin&amp;s=2&amp;u=<?php echo $new_user_id ?>" method="post" name="edituser">

  <h1 class="text-center text-sm-start"><?php echo $BL['be_subnav_admin_users'] ?></h1>
  <div class="card mb-4">
  <div class="card-header"><h2><i class="fa fa-user" aria-hidden="true"></i> <?php echo $BL['be_admin_usr_etitle'] ?></h2></div>
  <div class="card-body">

    <?php
    if(!empty($user_err)) {
    ?>
    <div class="alert alert-danger"><?php echo $BL['be_admin_usr_err'] ?>: <?php echo nl2br(chop($user_err)) ?></div>
    <?php
    } //Ende Fehler New User
    ?>

     <div class="form-group row g-2 align-items-center">
      <label for="form_newloginname" class="col-sm-2 col-form-label text-end"><?php echo $BL["login_username"] ?></label>
      <div class="col">
        <input type="text" class="form-control form-control-sm col-sm-5" name="form_newloginname" id="form_newloginname" value="<?php echo html($new_login); ?>" autocomplete="off" required="required" />
      </div>
    </div>

    <div class="form-group row g-2 align-items-center">
      <label for="form_newpassword" class="col-sm-2 col-form-label text-end"><?php echo $BL["login_userpass"] ?></label>
      <div class="col">
        <input type="password" class="form-control form-control-sm col-sm-5" name="form_newpassword" id="form_newpassword" value="<?php echo html($new_password); ?>" maxlength="200" autocomplete="new-password">
        <span class="text-blue small" onclick="this.innerText=(togglePasswordVisibility('form_newpassword') === 'hide' ? '<?php echo $BL['be_password_hide']; ?>' : '<?php echo $BL['be_password_show']; ?>');" style="cursor:pointer">
           <?php echo $BL['be_password_show']; ?>
        </span>
      </div>
    </div>

    <div class="form-group row g-2 align-items-center">
      <label for="form_newemail" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_profile_label_email'] ?></label>
      <div class="col">
        <input type="email" class="form-control form-control-sm col-sm-5" name="form_newemail" id="form_newemail" value="<?php echo html($new_email); ?>" maxlength="250" autocomplete="off" required="required" />
      </div>
    </div>

    <div class="form-group row g-2 align-items-center">
      <label for="form_newrealname" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_usr_realname'] ?></label>
      <div class="col">
        <input type="text" class="form-control form-control-sm col-sm-5" name="form_newrealname" id="form_newrealname" value="<?php echo html($new_name); ?>" maxlength="200" autocomplete="off" required="required" />
      </div>
    </div>

<hr />

  <div class="row g-2 align-items-center">
    <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_usr_issection']  ?></label>
    <div class="col">
    <div class="form-check form-check-inline">
      <input class="form-check-input" name="form_feuser" type="radio" id="form_feuser0" value="0"<?php is_checked($set_user_fe, 0); ?> />
      <label class="form-check-label" for="form_feuser0"><?php echo $BL['be_admin_usr_ifsection0'] ?></label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" name="form_feuser" type="radio" id="form_feuser1" value="1"<?php is_checked($set_user_fe, 1); ?> />
      <label class="form-check-label" for="form_feuser1"><?php echo $BL['be_admin_usr_ifsection1'] ?></label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" name="form_feuser" type="radio" id="form_feuser2" value="2"<?php is_checked($set_user_fe, 2); ?> />
      <label class="form-check-label" for="form_feuser2"><?php echo $BL['be_admin_usr_ifsection2'] ?></label>
    </div>
    </div>
  </div>

  <div class="row g-2 align-items-center">
    <label for="form_active" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_usr_setactive'] ?></label>
    <div class="col">
    <div class="form-check form-check-inline">
      <input class="form-check-input" name="form_active" type="checkbox" id="form_active" value="1"<?php is_checked($set_user_aktiv, 1); ?> />
      <label class="form-check-label" for="form_active"><?php echo  $BL['be_admin_usr_iflogin'] ?></label>
    </div>
    </div>
  </div>

  <div class="row g-2 align-items-center">
    <label for="form_admin" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_usr_isadmin'] ?></label>
    <div class="col">
    <div class="form-check form-check-inline">
      <input class="form-check-input" name="form_admin" type="checkbox" id="form_admin" value="1"<?php is_checked($set_user_admin, 1); ?> />
      <label class="form-check-label" for="form_admin"><strong><?php echo  $BL['be_admin_usr_ifadmin'] ?>!</strong></label>
    </div>
    </div>
  </div>

  <div class="row g-2 align-items-center">
    <label for="verification_email" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_usr_verify'] ?></label>
    <div class="col">
    <div class="form-check form-check-inline">
      <input class="form-check-input" name="verification_email" type="checkbox" id="verification_email" value="1"<?php is_checked($send_verification, 1); ?> />
      <label class="form-check-label" for="verification_email"><?php echo $BL['be_admin_usr_sendemail'] ?></label>
    </div>
    </div>
  </div>

  <?php if(!empty($set_user_2fa)): ?>
  <div class="row g-2 align-items-center mt-2">
    <label for="form_reset_2fa" class="col-sm-2 col-form-label text-end text-danger"><i class="fa fa-shield-alt"></i> 2FA</label>
    <div class="col">
      <div class="form-check form-check-inline">
        <input class="form-check-input" name="form_reset_2fa" type="checkbox" id="form_reset_2fa" value="1" />
        <label class="form-check-label text-danger fw-bold" for="form_reset_2fa">
          <?php echo $BL['be_admin_usr_2fa_reset'] ?? 'Reset / Disable 2FA'; ?>
        </label>
      </div>
      <small class="form-text text-muted d-inline-block ms-2">(<?php echo $BL['be_admin_usr_2fa_active'] ?? '2FA is active for this account.'; ?>)</small>
    </div>
  </div>
  <?php endif; ?>

  <hr />

  <ul class="nav nav-tabs">
    <li class="nav-item"><a data-bs-toggle="tab" href="#select_cp" class="nav-link active"><?php echo $BL['be_structform_select_cp'] ?></a></li>
    <?php
    if (isset($new_user_id)) {
      echo '<li class="nav-item"><a data-bs-toggle="tab" href="#admin_groups" class="nav-link">'.$BL['be_subnav_admin_groups'].'</a></li>';
      echo '<li class="nav-item"><a data-bs-toggle="tab" href="#log" class="nav-link">'.$BL['usr_online'].'</a></li>';
    }
    ?>
  </ul>

      <div class="tab-content my-3">
        <div id="select_cp" class="tab-pane in active checkbox-list" role="tabpanel">
             <div class="row g-2">
						<?php
						$has_allowed_cp = isset($set_allowed_cp) ? count($set_allowed_cp) : 0;
						foreach($wcs_content_type as $key => $value):
								// count used CPs so it is easier to decide if needed or not
								$used_count = _dbCount('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_articlecontent WHERE acontent_trash=0 AND acontent_type='._dbEscape($key));
						 ?>
          <div class="col-sm-6 col-md-4 mb-2">
            <div class="form-check">
							<input class="form-check-input" type="checkbox" id="allowed_cp_<?php echo $key ?>" name="allowed_cp[<?php echo $key ?>]" value="<?php echo $key ?>"<?php if(!$has_allowed_cp || isset($set_allowed_cp[$key])): ?> checked="checked"<?php endif; ?> />
							<label class="form-check-label" for="allowed_cp_<?php echo $key ?>"><?php echo html($value).' ('.$used_count.')' ?></label>
            </div>
          </div>

        <?php endforeach; ?>
                  <input type="hidden" name="cp_total" value="<?php echo count($wcs_content_type) ?>" />
                </div>
        </div>
        <?php
        if (isset($new_user_id)) {
          echo '<div id="admin_groups" class="tab-pane fade" role="tabpanel"><div class="row">';
          //group access
          $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_usergroup WHERE group_trash=0 AND group_active = 1 ORDER BY group_name";
          $result = _dbQuery($sql);
          if(isset($result[0]['group_id'])) {
            foreach ($result AS $row) {
              $group["member"]    = empty($row["group_member"]) ? array() : explode(',', $row["group_member"]);
              $url = (in_array($_SESSION["wcs_user_id"], $group["member"])) ? 'phpwcms.php?do=admin&amp;p=1&amp;u='.$row["group_id"] : '#';
              echo '<div class="col-sm-6 col-md-4">';
              if (in_array($new_user_id, $group["member"])) {
                echo ' <a href="'.$url.'" class="badge badge-success mt-2"> ';
              } else {
                echo ' <a href="'.$url.'" class="badge badge-danger mt-2"> ';
              }
              if ($row["group_syskey"] != '') {
                echo $groupnames[$row["group_syskey"]];
              } else {
                echo $row["group_name"];
              }
              echo '</a></div>';
            }
          }
          echo '</div></div>';

          //tab with user log details
          echo '<div id="log" class="tab-pane fade" role="tabpanel"><div class="row">';
          $sql = 'SELECT * FROM '.DB_PREPEND."phpwcms_userlog WHERE logged_user like '".$new_login."' ORDER BY logged_start DESC LIMIT 0,100";
          $result = _dbQuery($sql);
          if(isset($result[0]['logged_start'])) {
            foreach ($result AS $row) {
            echo '<div class="col-sm-6 col-md-4">';
              echo @date($BL['be_fprivedit_dateformat'], $row['logged_start'])."<br />" . LF;
              echo '</div>';
            }

          }
          echo '</div></div>';
        }
        ?>

      </div>
    </div>
  </div>

  <div class="form-group align-items-center mt-4 mb-0">
    <button name="Submit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa fa-rotate"></i> <?php echo $BL['be_admin_usr_ebutton'] ?></button>
    <a href="phpwcms.php?do=admin&amp;p=6" class="btn btn-sm btn-danger ms-3"><i class="fa fa-times"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
  </div>

  <input name="form_aktion" type="hidden" value="edit_account" />
  <input name="form_uid" type="hidden" value="<?php echo html($new_user_id) ?>" />
</form>

<?php
    } else {
        echo "<script type=\"text/JavaScript\"> timer=setTimeout(\"self.location.href='phpwcms.php'+'?".CSRF_GET_TOKEN."&do=admin'\", 0); </script>";
    }
}
?>
