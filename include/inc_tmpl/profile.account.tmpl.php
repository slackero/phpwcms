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
