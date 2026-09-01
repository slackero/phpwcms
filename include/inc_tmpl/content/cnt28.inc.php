<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// frontend login

if (!isset($content['felogin']['felogin_cookie_expire'])) {
    $content['felogin']['felogin_cookie_expire'] = 2592000;
}
if (empty($content['felogin']['felogin_date_format'])) {
    $content['felogin']['felogin_date_format'] = '%m/%d/%y';
}
if (empty($content['felogin']['felogin_locale'])) {
    $content['felogin']['felogin_locale'] = '';
}
if (!isset($content['felogin']['felogin_validate_backenduser'])) {
    $content['felogin']['felogin_validate_backenduser'] = 1;
}
if (!isset($content['felogin']['felogin_validate_userdetail'])) {
    $content['felogin']['felogin_validate_userdetail'] = 1;
}
if (!isset($content['felogin']['felogin_profile_registration'])) {
    $content['felogin']['felogin_profile_registration'] = 1;
}
if (!isset($content['felogin']['felogin_profile_manage'])) {
    $content['felogin']['felogin_profile_manage'] = 1;
}
if (!isset($content['felogin']['felogin_reminder_subject'])) {
    $content['felogin']['felogin_reminder_subject'] = '';
}
if (!isset($content['felogin']['felogin_reminder_body'])) {
    $content['felogin']['felogin_reminder_body'] = '';
}
if (!isset($content['felogin']['felogin_profile_manage_redirect'])) {
    $content['felogin']['felogin_profile_manage_redirect'] = '';
}
if (!isset($content['felogin']['felogin_accept_email_login'])) {
    $content['felogin']['felogin_accept_email_login'] = 0;
}

?>
<div class="form-group align-items-center row g-2">
    <label for="template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template']; ?></label>
    <div class="col-sm-4">
        <select name="template" id="template" class="form-select form-select-sm">
            <?php
            // templates for frontend login
            $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE . 'inc_cntpart/felogin');
            if (is_array($tmpllist) && count($tmpllist)) {
                foreach ($tmpllist as $val) {
                    $selected_val = (isset($content['felogin_template']) && $val == $content['felogin_template']) ? ' selected="selected"' : '';
                    $val = html($val);
                    echo '<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>';
                }
            }
            ?>
        </select>
    </div>
</div>

<div class="form-group row g-2">
    <label class="col-sm-2 col-form-label text-end" for="cookie_expire"><?php echo $BL['be_cookie_runtime'] ?></label>
    <div class="col-sm-auto">
        <input name="cookie_expire" type="text" class="form-control form-control-sm" id="cookie_expire" maxlength="10" onkeyup="if(!parseInt(this.value,10))this.value='0';" value="<?php echo $content['felogin']['felogin_cookie_expire']; ?>"/>
    </div>
    <div class="col col-form-label">
        <?php echo $BL['be_cnt_guestbook_seconds'] ?>
    </div>
</div>

<div class="form-group align-items-center row g-2">
    <label class="col-sm-2 col-form-label text-end" for="date_format"><?php echo $BL['be_date_format'] ?></label>
    <div class="col-sm-auto">
        <input name="date_format" type="text" class="form-control form-control-sm" id="date_format" value="<?php echo $content['felogin']['felogin_date_format']; ?>"/>
    </div>
    <div class="col col-form-label">
        <a href="http://www.php.net/strftime" target="_blank"><i class="fa-solid fa-info-circle text-blue" data-bs-toggle="tooltip" title="PHP strftime"></i></a>
    </div>
</div>

<div class="form-group align-items-center row g-2">
    <label class="col-sm-2 col-form-label text-end" for="locale"><?php echo $BL['be_locale'] ?></label>
    <div class="col-sm-auto">
        <input name="locale" type="text" class="form-control form-control-sm" id="locale" value="<?php echo $content['felogin']['felogin_locale']; ?>"/>
    </div>
    <div class="col-sm-auto col-form-label">
        <a href="http://www.php.net/setlocale" target="_blank"><i class="fa-solid fa-info-circle text-blue" data-bs-toggle="tooltip" title="PHP setlocale"></i></a>
    </div>
    <div class="col-sm-auto col-form-label">
        (de, de_DE)
    </div>
</div>

<div class="form-group row g-2">
    <label class="col-sm-2 col-form-label text-end pt-0" for="validate_userdetail"><?php echo $BL['be_check_login_against'] ?></label>
    <div class="col-sm-auto">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="validate_userdetail" id="validate_userdetail" value="1"<?php echo is_checked(1, $content['felogin']['felogin_validate_userdetail']); ?> />
            <label class="form-check-label" for="validate_userdetail"><?php echo $BL['be_userprofile_db'] ?></label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="validate_backenduser" id="validate_backenduser" value="1"<?php echo is_checked(1, $content['felogin']['felogin_validate_backenduser']); ?> />
            <label class="form-check-label" for="validate_backenduser"><?php echo $BL['be_backenduser_db'] ?></label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="accept_email_login" id="accept_email_login" value="1"<?php echo is_checked(1, $content['felogin']['felogin_accept_email_login']); ?> />
            <label class="form-check-label" for="accept_email_login"><?php echo $BL['be_check_login_allow_email'] ?></label>
        </div>
    </div>
</div>

<div class="form-group row g-2">
    <label class="col-sm-2 col-form-label text-end pt-0" for="profile_registration"><?php echo $BL['be_check_feuser_profile'] ?></label>
    <div class="col-sm-auto">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="profile_registration" id="profile_registration" value="1"<?php echo is_checked(1, $content['felogin']['felogin_profile_registration']); ?> />
            <label class="form-check-label" for="profile_registration"><?php echo $BL['be_check_feuser_registration'] ?></label>
        </div>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="profile_manage" id="profile_manage" value="1"<?php echo is_checked(1, $content['felogin']['felogin_profile_manage']); ?> />
            <label class="form-check-label" for="profile_manage"><?php echo $BL['be_check_feuser_manage'] ?></label>
        </div>
    </div>
</div>

<div class="form-group row g-2">
    <label class="col-sm-2 col-form-label text-end" for="profile_manage_redirect"><?php echo $BL['be_alias'], ', aid=ID', ', id=ID' ?></label>
    <div class="col-sm-auto">
        <div class="input-group input-group-sm">
            <button class="modalButton btn btn-secondary sitemap-open" type="button" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="articlebrowser.php?opt=6&amp;field=profile_manage_redirect" title="<?php echo $BL['be_cnt_openarticlebrowser'] ?>"><i class="fa-solid fa-sitemap fa-fw" aria-hidden="true"></i></button>
            <input type="text" name="profile_manage_redirect" id="profile_manage_redirect" value="<?php echo html($content['felogin']['felogin_profile_manage_redirect']); ?>" class="form-control" data-bs-toggle="tooltip" title="<?php echo $BL['be_read_more_link'] ?>"/>
        </div>
    </div>
</div>
