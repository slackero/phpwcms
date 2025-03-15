<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

?>
<h1><?php echo $BL['be_nav_profile'] ?></h1>

<div class="card">
  <div class="card-header"><h2><i class="fa fa-user" aria-hidden="true"></i> <?php echo $BL['be_profile_account_title'] ?></h2></div>
  <div class="card-body">
    <p><?php echo $BL['be_profile_account_text'] ?></p>

    <?php
    if(!empty($err)) {
    ?>
    <div class="alert alert-danger"><?php echo $BL['be_profile_label_err'] ?>: <?php echo nl2br(chop($err)) ?></div>
    <?php
    }
    ?>

  <form action="cmsgo.php?do=profile" method="post" name="formprofiledetail" id="formprofiledetail" autocomplete="off">
    <div class="form-group row align-items-center">
      <label for="be_profile_label_username" class="col-sm-2 col-form-label text-right"><?php echo $BL["be_profile_label_username"]  ?></label>
      <div class="col">
        <input type="text" class="form-control form-control-sm col-sm-5" name="form_loginname" id="form_loginname" value="<?php echo html($_SESSION["wcs_user"]); ?>" autocomplete="off">
      </div>
    </div>

    <div class="form-group row align-items-center">
      <label for="be_profile_label_newpass" class="col-sm-2 col-form-label text-right"><?php echo $BL["be_profile_label_newpass"]  ?></label>
      <div class="col">
        <input type="password" class="form-control form-control-sm col-sm-5" name="form_password" id="form_password" value="" autocomplete="new-password">
      </div>
    </div>

    <div class="form-group row align-items-center">
      <label for="be_profile_label_repeatpass" class="col-sm-2 col-form-label text-right"><?php echo $BL["be_profile_label_repeatpass"]  ?></label>
      <div class="col">
        <input type="password" class="form-control form-control-sm col-sm-5" name="form_password2" id="form_password2" value="" autocomplete="new-password">
      </div>
    </div>

    <div class="form-group row align-items-center">
      <label for="be_profile_label_email" class="col-sm-2 col-form-label text-right"><?php echo $BL["be_profile_label_email"]  ?></label>
      <div class="col">
        <input type="text" class="form-control form-control-sm col-sm-5" name="form_useremail" id="form_useremail" value="<?php echo html($_SESSION["wcs_user_email"]); ?>" autocomplete="off">
      </div>
    </div>

    <div class="form-group row align-items-center">
      <label for="be_profile_label_lang" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_lang'] ?></label>
      <div class="col-sm-3">
        <select name="form_lang" id="form_lang" class="custom-select form-control form-control-sm">
          <?php
            // check available languages installed and build language selector menu
            include_once CMSGO_ROOT."/include/inc_lang/code.lang.inc.php";
            $lang_dirs = opendir(CMSGO_ROOT."/include/inc_lang/backend");
            while($lang_codes = readdir( $lang_dirs )) {
                    if( substr($lang_codes, 0, 1) !== '.' && file_exists(CMSGO_ROOT."/include/inc_lang/backend/".$lang_codes."/lang.inc.php")) {
                            echo '<option value="'.$lang_codes.'"';
                            if($lang_codes == $_SESSION["wcs_user_lang"]) {
                                    echo ' selected="selected"';
                            }
                            echo '>';
                            echo (isset($BL[strtoupper($lang_codes)])) ? $BL[strtoupper($lang_codes)] : strtoupper($lang_codes);
                            echo "</option>\n";
                    }
            }
            closedir( $lang_dirs );
            $wysiwygTemplates['editor'] = empty($_SESSION["WYSIWYG_EDITOR"]) ? 0 : 1;
            ?>
        </select>
      </div>
    </div>

    <div class="row align-items-center">
      <label for="be_WYSIWYG" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_WYSIWYG'] ?></label>
      <div class="col">
        <div class="form-check form-check-inline">
					<input class="form-check-input" name="form_wysiwyg" type="checkbox" id="form_active" value="1"<?php if(!empty($_SESSION["WYSIWYG_EDITOR"])): ?> checked="checked"<?php endif; ?> />
					<label class="form-check-label" for="form_active"><?php echo $BL['be_on']; ?> (CKEditor 4.x)</label>
					<input type="hidden" name="form_wysiwyg_toolbar" value="" />
        </div>
      </div>
    </div>

    <hr />

    <div class="row">
      <label for="be_structform_select_cp" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_structform_select_cp'] ?></label>
      <div class="col-sm-10">
        <div class="form-check form-check-inline">
          <div class="row">
            <?php
                    $has_selected_cp    = isset($_SESSION["wcs_user_cp"]) ? count($_SESSION["wcs_user_cp"]) : 0;
                    $has_allowed_cp     = isset($_SESSION["wcs_allowed_cp"]) ? count($_SESSION["wcs_allowed_cp"]) : 0;

                            foreach($wcs_content_type as $key => $value):
                            if($has_allowed_cp && !isset($_SESSION["wcs_allowed_cp"][$key])):
            ?>
            <div class="col-sm-6 col-md-4">
                  <input type="checkbox" disabled="disabled" class="form-check-input" />
                  <label class="form-check-label">
                    <?php echo html($value) ?>
                  </label>
            </div>
            <?php
                    continue;
            endif;
            ?>
            <div class="col-sm-6 col-md-4">
                <input type="checkbox" class="form-check-input" name="profile_account_cp[<?php echo $key ?>]" value="<?php echo $key ?>"<?php if(!$has_selected_cp || isset($_SESSION["wcs_user_cp"][$key])): ?> checked="checked"<?php endif; ?> />
                <label class="form-check-label">
                  <?php echo html($value) ?>
                </label>
            </div>
            <?php   endforeach; ?>
            <input type="hidden" name="profile_cp_total" value="<?php echo count($wcs_content_type) ?>" />
          </div>
        </div>
      </div>
    </div>

    <div class="form-group mt-3 mb-0 text-right">
       <input type="submit" name="Submit" value="<?php echo $BL['be_profile_account_button'] ?>" class="btn btn-sm btn-blue">
    </div>

    <input name="form_aktion" type="hidden" id="form_aktion" value="update_account">
  </form>
  </div>
</div>
