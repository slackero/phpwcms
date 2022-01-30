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
        if ( strlen($new_password) < 5) $err .= str_replace('{VAL}', strlen($new_password), $BL['be_profile_account_err2'])."\n";
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

    $new_language = isset($_POST["form_lang"]) ? slweg(trim($_POST["form_lang"])) : $phpwcms["default_lang"];

    $new_wysiwyg = empty($_POST['form_wysiwyg']) ? 0 : intval($_POST['form_wysiwyg']);
    $user_var['template'] = empty($_POST['form_wysiwyg_template']) ? '' : clean_slweg($_POST['form_wysiwyg_template']);

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
            $sql .= "usr_pass="._dbEscape(md5(makeCharsetConversion($new_password, PHPWCMS_CHARSET, 'utf-8'))).", ";
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
            $_SESSION["wcs_user_cp"]        = $user_var['selected_cp'];

            set_language_cookie($new_language);

            headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=profile');
        }
    }
} //Ende Prüfung Gastzugang
