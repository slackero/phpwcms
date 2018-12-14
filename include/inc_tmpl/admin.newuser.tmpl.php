<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
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
    //Create Account Daten verarbeiten
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
    } elseif(($check_anzahl = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'cmsgo_user WHERE usr_aktiv != 9 AND usr_login='._dbEscape($new_login), 'COUNT'))) {
        $user_err .= $BL['be_admin_usr_err1'].LF;
    }
    if(empty($new_password)) {
        $user_err .= $BL['be_admin_usr_err3'].LF;
    }
    if(!is_valid_email($new_email) && $send_verification) {
        $user_err .= $BL['be_admin_usr_err4'].LF;
    }
    if(empty($user_err)) { //Insert new User
        $sql =  "INSERT INTO ".DB_PREPEND."cmsgo_user (usr_login, usr_pass, usr_email, ".
                "usr_admin, usr_aktiv, usr_name, usr_wysiwyg, usr_fe ) VALUES ('".
                aporeplace($new_login)."', '".
                aporeplace(md5(makeCharsetConversion($new_password, CMSGO_CHARSET, 'utf-8')))."', '".
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
                $emailbody = str_replace('{SITE}', CMSGO_URL, $emailbody);
                $emailbody = str_replace('{LOGIN_PAGE}', CMSGO_URL.get_login_file(), $emailbody);

                sendEmail(  array(
                    'recipient' => $new_email,
                    'toName'    => $new_name,
                    'subject'   => $BL['be_admin_usr_mailsubject'],
                    'isHTML'    => 0,
                    'text'      => $emailbody,
                    'from'      => $cmsgo["admin_email"],
                    'sender'    => $cmsgo["admin_email"]
                ));
            }
        }
    }
}

if(empty($user_ok)) {

?><form action="cmsgo.php?do=admin&amp;s=1" method="post" name="edituser">

		<h1><?php echo $BL['be_subnav_admin_users'] ?></h1>
		<div class="card mb-4">
		<div class="card-header"><h2><i class="fa fa-user-plus" aria-hidden="true"></i> <?php echo $BL['be_admin_usr_title'] ?></h2></div>
		<div class="card-body">

				<?php
				if(!empty($user_err)) {
				?>
				<div class="alert alert-danger"><?php echo $BL['be_admin_usr_err'] ?>: <?php echo nl2br(chop($user_err)) ?></div>
				<?php
				} //Ende Fehler New User
				?>

				<div class="form-group form-row align-items-center">
					<label for="form_newloginname" class="col-sm-2 col-form-label text-right"><?php echo $BL["login_username"]  ?></label>
					<div class="col">
						<input type="text" class="form-control form-control-sm col-sm-5" name="form_newloginname" id="form_newloginname" value="<?php echo html($new_login); ?>" maxlength="200" autocomplete="off" required="required" />
					</div>
				</div>

				<div class="form-group form-row">
					<label for="form_newpassword" class="col-sm-2 col-form-label text-right"><?php echo $BL["login_userpass"] ?></label>
					<div class="col">
						<input type="password" class="form-control form-control-sm col-sm-5" name="form_newpassword" id="form_newpassword" value="<?php echo html($new_password); ?>" maxlength="200" autocomplete="new-password" />
						<span class="text-blue small" onclick="this.innerText = (togglePasswordVisibility('form_newpassword') === 'hide') ? '<?php echo $BL['be_password_hide']; ?>' : '<?php echo $BL['be_password_show']; ?>';" style="cursor:pointer">
							<?php echo $BL['be_password_show']; ?>
						</span>
					</div>
				</div>

				<div class="form-group form-row align-items-center">
					<label for="form_newemail" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_email'] ?></label>
					<div class="col">
						<input type="email" class="form-control form-control-sm col-sm-5" name="form_newemail" id="form_newemail" value="<?php echo html($new_email); ?>" maxlength="250" autocomplete="off" required="required" />
					</div>
				</div>

				<div class="form-group form-row align-items-center">
					<label for="form_newrealname" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_usr_realname'] ?></label>
					<div class="col">
						<input type="text" class="form-control form-control-sm col-sm-5" name="form_newrealname" id="form_newrealname" value="<?php echo html($new_name); ?>" maxlength="200" autocomplete="off" required="required" />
					</div>
				</div>

		<hr />

			<div class="form-row align-items-center">
				<label for="be_admin_usr_realname" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_admin_usr_issection']  ?></label>
				<div class="col">
				<div class="form-check form-check-inline">
					<input class="form-check-input" name="form_feuser" type="radio" id="form_feuser0" value="0"<?php is_checked($set_user_fe, 0); ?> /> 
					<label class="form-check-label" for="form_feuser"><?php echo $BL['be_admin_usr_ifsection0'] ?></label>
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

			<div class="form-row align-items-center">
				<label for="be_admin_usr_setactive" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_admin_usr_setactive'] ?></label>
				<div class="col">
				<div class="form-check form-check-inline">
					<input class="form-check-input" name="form_active" type="checkbox" id="form_active" value="1"<?php is_checked($set_user_aktiv, 1); ?> /> 
					<label class="form-check-label" for="form_active"><?php echo $BL['be_admin_usr_iflogin'] ?></label>
				</div>
				</div>  
			</div>

			<div class="form-row align-items-center">
				<label for="be_admin_usr_isadmin" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_admin_usr_isadmin'] ?></label>
				<div class="col">
				<div class="form-check form-check-inline">
					<input class="form-check-input" name="form_admin" type="checkbox" id="form_admin" value="1"<?php is_checked($set_user_admin, 1); ?> /> 
					<label class="form-check-label" for="form_admin"><strong><?php echo $BL['be_admin_usr_ifadmin'] ?>!</strong></label>
				</div>
				</div>  
			</div>
	
			<div class="form-row align-items-center">
				<label for="be_admin_usr_verify" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_admin_usr_verify'] ?></label>
				<div class="col">
				<div class="form-check form-check-inline">
					<input class="form-check-input" name="verification_email" type="checkbox" id="verification_email" value="1"<?php is_checked($send_verification, 1); ?> /> 
					<label class="form-check-label" for="form_active"><?php echo $BL['be_admin_usr_sendemail'] ?></label>
				</div>
				</div>  
			</div>
	
			<div class="text-right">
				<input name="form_aktion" type="hidden" value="create_account" />
				<input name="Submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_admin_usr_button'] ?>" />
			</div>
     
    </div>
  </div>
</form>

<?php
} else {
    echo "<script type=\"text/javascript\"> timer=setTimeout(\"self.location.href='cmsgo.php'+'?".CSRF_GET_TOKEN."&do=admin'\", 0); </script>";
}
?>