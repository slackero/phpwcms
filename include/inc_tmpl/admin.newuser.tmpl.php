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
    } elseif(($check_anzahl = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'user WHERE usr_aktiv != 9 AND usr_login='._dbEscape($new_login), 'COUNT'))) {
        $user_err .= $BL['be_admin_usr_err1'].LF;
    }
    if(empty($new_password)) {
        $user_err .= $BL['be_admin_usr_err3'].LF;
    }
    if(!is_valid_email($new_email) && $send_verification) {
        $user_err .= $BL['be_admin_usr_err4'].LF;
    }
    if(empty($user_err)) { //Insert new User
        $bcrypt_pass = password_hash(makeCharsetConversion($new_password, PHPWCMS_CHARSET, 'utf-8'), PASSWORD_DEFAULT);
        $sql =  "INSERT INTO ".DB_PREPEND."user (usr_login, usr_pass, usr_email, ".
                "usr_admin, usr_aktiv, usr_name, usr_wysiwyg, usr_fe ) VALUES ('".
                aporeplace($new_login)."', '".
                aporeplace($bcrypt_pass)."', '".
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
                $rendered_mail = render_system_email('new_user', [
                    '{NAME}'       => $new_name ?: $new_login,
                    '{LOGIN}'      => $new_login,
                    '{PASSWORD}'   => $new_password,
                    '{SITE}'       => PHPWCMS_HOST,
                    '{SITE_URL}'   => PHPWCMS_URL,
                    '{LOGIN_PAGE}' => PHPWCMS_URL . get_login_file()
                ]);

                sendEmail([
                    'recipient' => $new_email,
                    'toName'    => $new_name,
                    'subject'   => $rendered_mail['subject'],
                    'isHTML'    => true,
                    'html'      => $rendered_mail['html'],
                    'text'      => $rendered_mail['text'],
                    'from'      => $phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? '',
                    'fromName'  => get_brand_name(),
                    'sender'    => $phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? ''
                ]);
            }
        }
    }
}

if(empty($user_ok)) {

?><form action="phpwcms.php?do=admin&amp;s=1" method="post" name="edituser" autocomplete="off" <?php echo PHPWCMS_PASS_IGNORE; ?>>

		<h1><?php echo $BL['be_subnav_admin_users'] ?></h1>
		<div class="card mb-4">
		<div class="card-header"><h2><i class="fa-solid fa-user-plus" aria-hidden="true"></i> <?php echo $BL['be_admin_usr_title'] ?></h2></div>
		<div class="card-body">

				<?php
				if(!empty($user_err)) {
				?>
				<div class="alert alert-danger"><?php echo $BL['be_admin_usr_err'] ?>: <?php echo nl2br(chop($user_err)) ?></div>
				<?php
				} //Ende Fehler New User
				?>

				<div class="form-group row g-2 align-items-center">
					<label for="form_newloginname" class="col-sm-2 col-form-label text-end"><?php echo $BL["login_username"]  ?></label>
					<div class="col">
						<input type="text" class="form-control form-control-sm col-sm-5" name="form_newloginname" id="form_newloginname" value="<?php echo html($new_login); ?>" maxlength="200" autocomplete="username" required="required" <?php echo PHPWCMS_PASS_IGNORE; ?> />
					</div>
				</div>

				<div class="form-group row g-2 align-items-center">
					<label for="form_newpassword" class="col-sm-2 col-form-label text-end"><?php echo $BL["login_userpass"] ?></label>
					<div class="col-sm-5">
						<div class="form-password">
							<input type="password" class="form-control form-control-sm" name="form_newpassword" id="form_newpassword" value="<?php echo html($new_password); ?>" maxlength="200" autocomplete="new-password" <?php echo PHPWCMS_PASS_IGNORE; ?> />
							<button type="button" class="form-password-action" data-coreui-toggle="password" aria-pressed="false" aria-label="<?php echo html($BL['be_password_show']); ?>">
								<i class="fa-regular fa-eye"></i>
							</button>
						</div>
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
				<label class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_admin_usr_issection']  ?></label>
				<div class="col">
					<div class="btn-group btn-group-sm flex-wrap" role="group" aria-label="form_feuser">
						<input class="btn-check" name="form_feuser" type="radio" id="form_feuser0" value="0" autocomplete="off"<?php is_checked($set_user_fe, 0); ?> />
						<label class="btn btn-outline-blue" for="form_feuser0"><?php echo $BL['be_admin_usr_ifsection0'] ?></label>

						<input class="btn-check" name="form_feuser" type="radio" id="form_feuser1" value="1" autocomplete="off"<?php is_checked($set_user_fe, 1); ?> />
						<label class="btn btn-outline-blue" for="form_feuser1"><?php echo $BL['be_admin_usr_ifsection1'] ?></label>

						<input class="btn-check" name="form_feuser" type="radio" id="form_feuser2" value="2" autocomplete="off"<?php is_checked($set_user_fe, 2); ?> />
						<label class="btn btn-outline-blue" for="form_feuser2"><?php echo $BL['be_admin_usr_ifsection2'] ?></label>
					</div>
				</div>
			</div>

			<div class="row g-2 align-items-center">
				<label for="form_active" class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_admin_usr_setactive'] ?></label>
				<div class="col">
					<div class="form-check form-switch">
						<input class="form-check-input" name="form_active" type="checkbox" role="switch" id="form_active" value="1"<?php is_checked($set_user_aktiv, 1); ?> />
						<label class="form-check-label" for="form_active"><?php echo $BL['be_admin_usr_iflogin'] ?></label>
					</div>
				</div>
			</div>

			<div class="row g-2 align-items-center">
				<label for="form_admin" class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_admin_usr_isadmin'] ?></label>
				<div class="col">
					<div class="form-check form-switch">
						<input class="form-check-input" name="form_admin" type="checkbox" role="switch" id="form_admin" value="1"<?php is_checked($set_user_admin, 1); ?> />
						<label class="form-check-label" for="form_admin"><strong><?php echo $BL['be_admin_usr_ifadmin'] ?>!</strong></label>
					</div>
				</div>
			</div>

			<div class="row g-2 align-items-center">
				<label for="verification_email" class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_admin_usr_verify'] ?></label>
				<div class="col">
					<div class="form-check form-switch">
						<input class="form-check-input" name="verification_email" type="checkbox" role="switch" id="verification_email" value="1"<?php is_checked($send_verification, 1); ?> />
						<label class="form-check-label" for="verification_email"><?php echo $BL['be_admin_usr_sendemail'] ?></label>
					</div>
				</div>
			</div>

    </div>
  </div>

  <div class="form-group align-items-center mt-4 mb-0">
    <input name="form_aktion" type="hidden" value="create_account" />
    <button name="Submit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa-solid fa-plus"></i> <?php echo $BL['be_admin_usr_button'] ?></button>
    <a href="phpwcms.php?do=admin" class="btn btn-sm btn-danger ms-3"><i class="fa-solid fa-times"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
  </div>
</form>

<?php
} else {
    forward_to(true, 'phpwcms.php?' . CSRF_GET_TOKEN . '&do=admin', 0);
}
?>
