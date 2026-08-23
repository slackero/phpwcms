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

if (!empty($_SESSION['wcs_user_admin'])) {
    $profile_icon = 'fa-user-shield text-info';
} elseif (!empty($_SESSION['wcs_user_fe']) && (int)$_SESSION['wcs_user_fe'] === 2) {
    $profile_icon = 'fa-user-check text-success';
} elseif (isset($_SESSION['wcs_user_fe']) && (int)$_SESSION['wcs_user_fe'] === 0) {
    $profile_icon = 'fa-user text-warning';
} else {
    $profile_icon = 'fa-user-cog text-primary';
}
?>
<h1><?php echo $BL['be_nav_profile'] ?></h1>

<div class="card">
  <div class="card-header">
      <h2>
          <i class="fa <?php echo $profile_icon; ?> mr-1" aria-hidden="true"></i>
          <?php echo $BL['be_profile_account_title'] ?>
      </h2>
  </div>
  <div class="card-body">
    <p><?php echo $BL['be_profile_account_text'] ?></p>

    <?php
    if(!empty($err)) {
    ?>
    <div class="alert alert-danger"><?php echo $BL['be_profile_label_err'] ?>: <?php echo nl2br(chop($err)) ?></div>
    <?php
    }
    ?>

  <form action="phpwcms.php?do=profile" method="post" name="formprofiledetail" id="formprofiledetail" autocomplete="off">
    <div class="form-group row align-items-center">
      <label for="form_loginname" class="col-sm-2 col-form-label text-right"><?php echo $BL["be_profile_label_username"]  ?></label>
      <div class="col">
        <input type="text" class="form-control form-control-sm col-sm-5" name="form_loginname" id="form_loginname" value="<?php echo html($_SESSION["wcs_user"]); ?>" autocomplete="off">
      </div>
    </div>

    <div class="form-group row align-items-center">
      <label for="form_password" class="col-sm-2 col-form-label text-right"><?php echo $BL["be_profile_label_newpass"]  ?></label>
      <div class="col">
        <input type="password" class="form-control form-control-sm col-sm-5" name="form_password" id="form_password" value="" autocomplete="new-password">
      </div>
    </div>

    <div class="form-group row align-items-center">
      <label for="form_password2" class="col-sm-2 col-form-label text-right"><?php echo $BL["be_profile_label_repeatpass"]  ?></label>
      <div class="col">
        <input type="password" class="form-control form-control-sm col-sm-5" name="form_password2" id="form_password2" value="" autocomplete="new-password">
      </div>
    </div>

    <div class="form-group row align-items-center">
      <label for="form_useremail" class="col-sm-2 col-form-label text-right"><?php echo $BL["be_profile_label_email"]  ?></label>
      <div class="col">
        <input type="text" class="form-control form-control-sm col-sm-5" name="form_useremail" id="form_useremail" value="<?php echo html($_SESSION["wcs_user_email"]); ?>" autocomplete="off">
      </div>
    </div>

    <div class="form-group row align-items-center">
      <label for="form_lang" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_lang'] ?></label>
      <div class="col-sm-3">
        <select name="form_lang" id="form_lang" class="custom-select form-control form-control-sm">
          <?php
            // check available languages installed and build language selector menu
            include_once PHPWCMS_ROOT."/include/inc_lang/code.lang.inc.php";
            $lang_dirs = opendir(PHPWCMS_ROOT."/include/inc_lang/backend");
            $lang_options = array();
            while($lang_codes = readdir( $lang_dirs )) {
                if( substr($lang_codes, 0, 1) !== '.' && is_file(PHPWCMS_ROOT."/include/inc_lang/backend/".$lang_codes."/lang.inc.php")) {
                    $_lang_code = strtoupper($lang_codes);
                    $lang_options[$_lang_code]  = '<option value="'.$lang_codes.'"';
                    $lang_options[$_lang_code] .= ($lang_codes == $_SESSION["wcs_user_lang"]) ? ' selected="selected"' : '';
                    $lang_options[$_lang_code] .= '>';
                    $lang_options[$_lang_code] .= (isset($BL[$_lang_code])) ? $BL[$_lang_code] : $_lang_code;
                    $lang_options[$_lang_code] .= "</option>\n";
                }
            }
            closedir( $lang_dirs );
            ksort($lang_options);
            echo implode('', $lang_options);
            $wysiwygTemplates['editor'] = empty($_SESSION["WYSIWYG_EDITOR"]) ? 0 : 1;
            ?>
        </select>
      </div>
    </div>

    <div class="form-group row align-items-center">
      <label for="form_theme" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_theme'] ?></label>
      <div class="col-sm-3">
        <select name="form_theme" id="form_theme" class="custom-select form-control form-control-sm">
          <option value="auto"<?php if(empty($_SESSION["wcs_user_theme"]) || $_SESSION["wcs_user_theme"] === 'auto'): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_theme_auto']; ?></option>
          <option value="light"<?php if(!empty($_SESSION["wcs_user_theme"]) && $_SESSION["wcs_user_theme"] === 'light'): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_theme_light']; ?></option>
          <option value="dark"<?php if(!empty($_SESSION["wcs_user_theme"]) && $_SESSION["wcs_user_theme"] === 'dark'): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_theme_dark']; ?></option>
        </select>
      </div>
    </div>

    <div class="row align-items-center">
      <label for="be_WYSIWYG" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_WYSIWYG'] ?></label>
      <div class="col-sm-4">
        <select class="custom-select form-control form-control-sm" name="form_wysiwyg" id="be_WYSIWYG">
          <option value="0"<?php if(empty($_SESSION["WYSIWYG_EDITOR"])): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_inactive']; ?></option>
          <option value="1"<?php if(isset($_SESSION["WYSIWYG_EDITOR"]) && $_SESSION["WYSIWYG_EDITOR"] == 1): ?> selected="selected"<?php endif; ?>>CKEditor (<?php echo $BL['be_legacy']; ?>)</option>
          <option value="2"<?php if(isset($_SESSION["WYSIWYG_EDITOR"]) && $_SESSION["WYSIWYG_EDITOR"] == 2): ?> selected="selected"<?php endif; ?>>TinyMCE 8 (<?php echo $BL['be_default']; ?>)</option>
        </select>
        <input type="hidden" name="form_wysiwyg_toolbar" value="" />
      </div>
    </div>

    <hr />

    <div class="row">
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_structform_select_cp'] ?></label>
      <div class="col-sm-10">
          <div class="row">
            <?php
            $has_selected_cp = isset($_SESSION["wcs_user_cp"]) ? count($_SESSION["wcs_user_cp"]) : 0;
            $has_allowed_cp = isset($_SESSION["wcs_allowed_cp"]) ? count($_SESSION["wcs_allowed_cp"]) : 0;

            foreach ($wcs_content_type as $key => $value):
                if ($has_allowed_cp && !isset($_SESSION["wcs_allowed_cp"][$key])):
            ?>
            <div class="col-sm-6 col-md-4 mb-2">
                <div class="form-check">
                  <input type="checkbox" disabled="disabled" class="form-check-input" id="profile_account_cp_<?php echo $key ?>" />
                  <label class="form-check-label" for="profile_account_cp_<?php echo $key ?>">
                    <?php echo html($value) ?>
                  </label>
                </div>
            </div>
            <?php
                    continue;
            endif;
            ?>
            <div class="col-sm-6 col-md-4 mb-2">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="profile_account_cp_<?php echo $key ?>" name="profile_account_cp[<?php echo $key ?>]" value="<?php echo $key ?>"<?php if(!$has_selected_cp || isset($_SESSION["wcs_user_cp"][$key])): ?> checked="checked"<?php endif; ?> />
                  <label class="form-check-label" for="profile_account_cp_<?php echo $key ?>">
                    <?php echo html($value) ?>
                  </label>
                </div>
            </div>
            <?php   endforeach; ?>
            <input type="hidden" name="profile_cp_total" value="<?php echo count($wcs_content_type) ?>" />
          </div>
      </div>
    </div>

    <div class="form-group mt-4 mb-0 text-right">
       <button type="submit" name="Submit" class="btn btn-sm btn-blue" value="1"><i class="fa fa-save"></i> <?php echo $BL['be_profile_account_button'] ?></button>
    </div>

    <input name="form_aktion" type="hidden" id="form_aktion" value="update_account">
  </form>
  </div>
</div>

<?php
// Load current user 2FA state from DB
$u_2fa_sql = 'SELECT usr_2fa_enabled, usr_2fa_secret, usr_vars FROM ' . DB_PREPEND . 'phpwcms_user WHERE usr_id = ' . (int)$_SESSION['wcs_user_id'] . ' LIMIT 1';
$u_2fa_res = _dbQuery($u_2fa_sql);
$has_2fa_enabled = !empty($u_2fa_res[0]['usr_2fa_enabled']) && !empty($u_2fa_res[0]['usr_2fa_secret']);
$u_2fa_vars = isset($u_2fa_res[0]['usr_vars']) ? @unserialize($u_2fa_res[0]['usr_vars'], ['allowed_classes' => false]) : [];
if (!is_array($u_2fa_vars)) {
    $u_2fa_vars = [];
}
$stored_backup_codes = isset($u_2fa_vars['2fa_backup_codes']) && is_array($u_2fa_vars['2fa_backup_codes']) ? count($u_2fa_vars['2fa_backup_codes']) : 0;
?>

<div class="card mt-4">
  <div class="card-header">
    <h2><i class="fa fa-shield-alt" aria-hidden="true"></i> <?php echo $BL['be_profile_2fa_title'] ?? 'Two-Factor Authentication (2FA)'; ?></h2>
  </div>
  <div class="card-body">
    <p><?php echo $BL['be_profile_2fa_text'] ?? 'Protect your account by requiring an additional 6-digit code from an authenticator app during login.'; ?></p>

    <?php if (!empty($tfa_msg)): ?>
      <div class="alert alert-success"><i class="fa fa-check-circle mr-1"></i> <?php echo html($tfa_msg); ?></div>
    <?php endif; ?>
    <?php if (!empty($tfa_err)): ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-triangle mr-1"></i> <?php echo html($tfa_err); ?></div>
    <?php endif; ?>

    <?php if ($has_2fa_enabled): ?>

      <div class="d-flex align-items-center mb-4">
        <span class="badge badge-success px-3 py-2 mr-3" style="font-size: 0.95rem;">
          <i class="fa fa-check-circle mr-1"></i> <?php echo $BL['be_profile_2fa_enabled'] ?? 'Enabled'; ?>
        </span>
        <span class="text-muted small">
          <?php echo $stored_backup_codes > 0 ? sprintf($BL['be_profile_2fa_backup_count'] ?? '%d backup recovery codes available', $stored_backup_codes) : ($BL['be_profile_2fa_backup_none'] ?? 'No backup codes available'); ?>
        </span>
      </div>

      <?php if (!empty($_SESSION['new_2fa_backup_codes'])): ?>
        <div class="alert alert-warning border p-3 mb-4">
          <h5 class="alert-heading font-weight-bold mb-2"><i class="fa fa-key mr-1"></i> <?php echo $BL['be_profile_2fa_backup_title'] ?? 'Backup Recovery Codes'; ?></h5>
          <p class="small mb-3"><?php echo $BL['be_profile_2fa_backup_text'] ?? 'Save these single-use recovery codes in a safe place:'; ?></p>
          <div class="row bg-white p-3 border rounded text-monospace font-weight-bold mb-2">
            <?php foreach ($_SESSION['new_2fa_backup_codes'] as $bcode): ?>
              <div class="col-sm-6 col-md-3 py-1"><?php echo html($bcode); ?></div>
            <?php endforeach; ?>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('<?php echo implode('\n', $_SESSION['new_2fa_backup_codes']); ?>'); alert('Backup codes copied to clipboard!');"><i class="fa fa-copy mr-1"></i> Copy Codes</button>
        </div>
        <?php unset($_SESSION['new_2fa_backup_codes']); ?>
      <?php endif; ?>

      <form action="phpwcms.php?do=profile" method="post" class="mt-3">
        <input type="hidden" name="form_aktion" value="disable_2fa" />
        <div class="form-group row align-items-center">
          <label for="disable_2fa_pass" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_currpass'] ?? 'Current Password'; ?></label>
          <div class="col-sm-4">
            <input type="password" class="form-control form-control-sm" name="disable_2fa_password" id="disable_2fa_pass" placeholder="<?php echo $BL['be_profile_2fa_currpass_placeholder'] ?? 'Enter current password to disable'; ?>" required="required" autocomplete="current-password" />
          </div>
          <div class="col-sm-auto mt-2 mt-sm-0">
            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-power-off mr-1"></i> <?php echo $BL['be_profile_2fa_btn_disable'] ?? 'Disable 2FA'; ?></button>
          </div>
        </div>
      </form>

    <?php else: ?>

      <?php
      if (empty($_SESSION['pending_2fa_secret'])) {
          $_SESSION['pending_2fa_secret'] = PhpwcmsTwoFactor::generateSecret();
      }
      $setup_secret = $_SESSION['pending_2fa_secret'];
      $otpauth_url = PhpwcmsTwoFactor::getOtpAuthUrl($_SESSION['wcs_user'], $setup_secret, 'phpwcms');
      $qr_svg = PhpwcmsTwoFactor::getQrCodeSvg($otpauth_url, 180);
      ?>

      <div class="border rounded p-4 bg-light">
        <div class="row">
          <div class="col-md-auto text-center mb-3 mb-md-0">
            <div class="p-2 bg-white border rounded d-inline-block shadow-sm">
              <?php echo $qr_svg; ?>
            </div>
          </div>
          <div class="col-md">
            <h5 class="font-weight-bold mb-2">1. <?php echo $BL['be_profile_2fa_step1'] ?? 'Scan QR Code with Authenticator App'; ?></h5>
            <p class="small text-muted mb-2"><?php echo $BL['be_profile_2fa_step1_text'] ?? 'Scan this QR code with your authenticator app, or enter the secret key manually:'; ?></p>
            <p class="mb-3">
              <span class="badge badge-secondary p-2 text-monospace" style="font-size: 1rem; letter-spacing: 0.1em;"><?php echo chunk_split($setup_secret, 4, ' '); ?></span>
              <button type="button" class="btn btn-sm btn-light border ml-2" onclick="copyToClipboard('<?php echo $setup_secret; ?>'); alert('Secret key copied!');" title="Copy Secret"><i class="fa fa-copy"></i></button>
            </p>

            <hr />

            <h5 class="font-weight-bold mb-2">2. <?php echo $BL['be_profile_2fa_step2'] ?? 'Enter Verification Code'; ?></h5>
            <p class="small text-muted mb-3"><?php echo $BL['be_profile_2fa_step2_text'] ?? 'Enter the 6-digit verification code from your authenticator app to complete setup:'; ?></p>

            <form action="phpwcms.php?do=profile" method="post" class="form-inline" autocomplete="off">
              <input type="hidden" name="form_aktion" value="enable_2fa" />
              <div class="input-group input-group-sm mr-2 mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-key"></i></span>
                </div>
                <input type="text" name="verify_2fa_code" class="form-control" style="max-width: 140px;" placeholder="123456" maxlength="6" pattern="[0-9]{6}" required="required" autocomplete="one-time-code" />
              </div>
              <button type="submit" class="btn btn-sm btn-success mb-2"><i class="fa fa-shield-alt mr-1"></i> <?php echo $BL['be_profile_2fa_btn_confirm'] ?? 'Confirm & Enable 2FA'; ?></button>
            </form>
          </div>
        </div>
      </div>

    <?php endif; ?>

  </div>
</div>
