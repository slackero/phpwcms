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


if($_SESSION["wcs_user"] != "guest") { //Prüfung für Gastzugang

    $err = '';

    $user_var = array(
        'template'  => '', //$_SESSION["WYSIWYG_TEMPLATE"]
        'selected_cp' => $_SESSION["wcs_user_cp"],
        'allowed_cp' => $_SESSION["wcs_allowed_cp"]
    );

    $new_username = slweg($_POST["form_loginname"]);
    if($new_username != $_SESSION["wcs_user"]) {
        $sql = "SELECT COUNT(usr_login) FROM ".DB_PREPEND."phpwcms_user WHERE usr_login="._dbEscape($new_username);
        if(($result = _dbQuery($sql, 'COUNT'))) {
            $err = str_replace('{VAL}', html($new_username), $BL['be_profile_account_err1'])."\n";
        }
    }
    if ($_POST["form_password"] === $_POST["form_password2"]) {
        if(strlen($_POST["form_password"]) > 0) {
        $new_password = slweg($_POST["form_password"]);
        if ( strlen($new_password) < 5) $err .= str_replace('{VAL}', (string)strlen($new_password), $BL['be_profile_account_err2'])."\n";
        }
    } else {
        $err .= $BL['be_profile_account_err3']."\n";
    }
    $new_email = slweg(trim($_POST["form_useremail"]));
    if ($new_email != $_SESSION["wcs_user_email"]) {
        if( !is_valid_email($new_email) ) {
            $err .= str_replace('{VAL}', html($new_email), $BL['be_profile_account_err4'])."\n";
        }
    }

    $lang_aliases = ['cz' => 'cs', 'se' => 'sv', 'vn' => 'vi', 'el' => 'gr'];
    $new_language = isset($_POST['form_lang']) ? strtolower(trim(slweg($_POST['form_lang']))) : strtolower($phpwcms['default_lang']);
    if (isset($lang_aliases[$new_language])) {
        $new_language = $lang_aliases[$new_language];
    }
    if (!preg_match('/^[a-z]{2}(?:-[a-z]{2})?$/', $new_language) || !is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$new_language.'/lang.inc.php')) {
        $default_lang = strtolower($phpwcms['default_lang']);
        if (isset($lang_aliases[$default_lang])) {
            $default_lang = $lang_aliases[$default_lang];
        }
        $new_language = is_file(PHPWCMS_ROOT.'/include/inc_lang/backend/'.$default_lang.'/lang.inc.php') ? $default_lang : 'en';
    }

    $new_wysiwyg = empty($_POST['form_wysiwyg']) ? 0 : intval($_POST['form_wysiwyg']);
    $new_theme = isset($_POST['form_theme']) && in_array($_POST['form_theme'], array('auto', 'light', 'dark'), true) ? $_POST['form_theme'] : 'auto';
    $user_var['template'] = empty($_POST['form_wysiwyg_template']) ? '' : clean_slweg($_POST['form_wysiwyg_template']);
    $user_var['theme'] = $new_theme;

    $user_var['selected_cp'] = array();
    if(isset($_POST['profile_cp_total'])) {

        $profile_cp_total           = intval($_POST['profile_cp_total']);
        $profile_account_cp_total   = isset($_POST['profile_account_cp']) && is_array($_POST['profile_account_cp']) ? count($_POST['profile_account_cp']) : 0;

        if($profile_account_cp_total && $profile_account_cp_total !== $profile_cp_total) {
            foreach ($_POST['profile_account_cp'] as $cp) {
                $cp = intval($cp);
                $user_var['selected_cp'][$cp] = $cp;
            }
        }
    }

    if(empty($err)) {

        $sql  = "UPDATE ".DB_PREPEND."phpwcms_user SET usr_login="._dbEscape($new_username).", ";

        if(!empty($new_password)) {
            $bcrypt_pass = password_hash(makeCharsetConversion($new_password, PHPWCMS_CHARSET, 'utf-8'), PASSWORD_DEFAULT);
            $sql .= "usr_pass="._dbEscape($bcrypt_pass).", ";
        }

        $sql .= "usr_email="._dbEscape($new_email);
        $sql .= ", usr_lang="._dbEscape($new_language);
        $sql .= ", usr_wysiwyg=".$new_wysiwyg;
        $sql .= " , usr_vars="._dbEscape(serialize($user_var));
        $sql .= " WHERE usr_id=".$_SESSION["wcs_user_id"];
        $sql .= " AND usr_login='".$_SESSION["wcs_user"]."' LIMIT 1";

        $result = _dbQuery($sql, 'UPDATE');

        if(isset($result['AFFECTED_ROWS'])) {
            //Wenn Aktualisierung erfolgreich war
            //neue Werte den Sessionvariablen zuweisen
            $_SESSION["wcs_user"]           = $new_username;
            $_SESSION["wcs_user_email"]     = $new_email;
            $_SESSION["wcs_user_lang"]      = $new_language;
            $_SESSION["WYSIWYG_EDITOR"]     = $new_wysiwyg;
            $_SESSION["wcs_user_theme"]     = $new_theme;
            $_SESSION["wcs_user_cp"]        = $user_var['selected_cp'];

            set_language_cookie($new_language);
            set_theme_cookie($new_theme);

            headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=profile');
        }
    }
} //Ende Prüfung Gastzugang

// -----------------------------------------------------------------------------
// Two-Factor Authentication Enable Handler
// -----------------------------------------------------------------------------
if ($_SESSION['wcs_user'] !== 'guest' && !empty($_POST['form_aktion']) && $_POST['form_aktion'] === 'enable_2fa') {

    $verify_code = slweg($_POST['verify_2fa_code'] ?? '');
    $pending_secret = $_SESSION['pending_2fa_secret'] ?? '';

    if (!empty($pending_secret) && strlen($verify_code) === 6 && ctype_digit($verify_code)) {

        if (PhpwcmsTwoFactor::verifyCode($pending_secret, $verify_code)) {

            // Generate backup codes
            $plain_backup_codes = PhpwcmsTwoFactor::generateBackupCodes(8);
            $hashed_backup_codes = PhpwcmsTwoFactor::hashBackupCodes($plain_backup_codes);

            // Fetch user vars to merge backup codes
            $u_sql = 'SELECT usr_vars FROM ' . DB_PREPEND . 'phpwcms_user WHERE usr_id = ' . (int)$_SESSION['wcs_user_id'] . ' LIMIT 1';
            $u_res = _dbQuery($u_sql);
            $u_vars = isset($u_res[0]['usr_vars']) ? @unserialize($u_res[0]['usr_vars'], ['allowed_classes' => false]) : [];
            if (!is_array($u_vars)) {
                $u_vars = [];
            }
            $u_vars['2fa_backup_codes'] = $hashed_backup_codes;

            // Save to database
            $update_sql = 'UPDATE ' . DB_PREPEND . 'phpwcms_user SET usr_2fa_enabled = 1, usr_2fa_secret = ' . _dbEscape($pending_secret) . ', usr_vars = ' . _dbEscape(serialize($u_vars)) . ' WHERE usr_id = ' . (int)$_SESSION['wcs_user_id'];
            _dbQuery($update_sql, 'UPDATE');

            unset($_SESSION['pending_2fa_secret']);
            $_SESSION['new_2fa_backup_codes'] = $plain_backup_codes;

            set_status_message($BL['be_profile_2fa_enabled_success'] ?? 'Two-Factor Authentication has been successfully enabled!');
            headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=profile');

        } else {
            $tfa_err = $BL['be_profile_2fa_err_invalid_code'] ?? 'The 6-digit authentication code is invalid. Please try again.';
        }

    } else {
        $tfa_err = $BL['be_profile_2fa_err_invalid_code'] ?? 'The 6-digit authentication code is invalid. Please try again.';
    }

}

// -----------------------------------------------------------------------------
// Two-Factor Authentication Disable Handler
// -----------------------------------------------------------------------------
if ($_SESSION['wcs_user'] !== 'guest' && !empty($_POST['form_aktion']) && $_POST['form_aktion'] === 'disable_2fa') {

    $pass_check = slweg($_POST['disable_2fa_password'] ?? '');

    if ($pass_check !== '') {

        $u_sql = 'SELECT usr_pass, usr_vars FROM ' . DB_PREPEND . 'phpwcms_user WHERE usr_id = ' . (int)$_SESSION['wcs_user_id'] . ' LIMIT 1';
        $u_res = _dbQuery($u_sql);

        if (isset($u_res[0]['usr_pass'])) {

            $valid_pass = false;
            $db_pass = $u_res[0]['usr_pass'];

            if (str_starts_with($db_pass, '$')) {
                if (password_verify($pass_check, $db_pass)) {
                    $valid_pass = true;
                } else {
                    $md5_pass = md5(makeCharsetConversion($pass_check, PHPWCMS_CHARSET, 'utf-8'));
                    if (password_verify($md5_pass, $db_pass)) {
                        $valid_pass = true;
                    }
                }
            } else {
                $md5_pass = md5(makeCharsetConversion($pass_check, PHPWCMS_CHARSET, 'utf-8'));
                if ($md5_pass === $db_pass) {
                    $valid_pass = true;
                }
            }

            if ($valid_pass) {

                $u_vars = isset($u_res[0]['usr_vars']) ? @unserialize($u_res[0]['usr_vars'], ['allowed_classes' => false]) : [];
                if (is_array($u_vars)) {
                    unset($u_vars['2fa_backup_codes']);
                } else {
                    $u_vars = [];
                }

                $update_sql = 'UPDATE ' . DB_PREPEND . 'phpwcms_user SET usr_2fa_enabled = 0, usr_2fa_secret = \'\', usr_vars = ' . _dbEscape(serialize($u_vars)) . ' WHERE usr_id = ' . (int)$_SESSION['wcs_user_id'];
                _dbQuery($update_sql, 'UPDATE');

                set_status_message($BL['be_profile_2fa_disabled_success'] ?? 'Two-Factor Authentication has been disabled.');
                headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=profile');

            } else {
                $tfa_err = $BL['be_profile_2fa_err_password'] ?? 'Current password required to change 2FA settings.';
            }

        }

    } else {
        $tfa_err = $BL['be_profile_2fa_err_password'] ?? 'Current password required to change 2FA settings.';
    }

}
