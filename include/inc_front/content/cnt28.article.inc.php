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


// frontend login
$crow['attr_class_id'] = array();
if($crow['acontent_attr_class']) {
    $crow['attr_class_id'][] = 'class="'.html($crow['acontent_attr_class']).'"';
}
if($crow['acontent_attr_id']) {
    $crow['attr_class_id'][] = 'id="'.html($crow['acontent_attr_id']).'"';
}

if(($crow['attr_class_id'] = implode(' ', $crow['attr_class_id']))) {
    $CNT_TMP .= '<div '.$crow['attr_class_id'].'>';
    $crow['attr_class_id_close'] = '</div>';
} else {
    $crow['attr_class_id_close'] = '';
}

$CNT_TMP .= headline($crow["acontent_title"], $crow["acontent_subtitle"], $template_default["article"]);

if(!empty($crow["acontent_template"]) && is_file(PHPWCMS_TEMPLATE.'inc_cntpart/felogin/'.$crow["acontent_template"])) {

    $_loginData                     = @unserialize($crow["acontent_form"], ['allowed_classes' => false]);

    $_loginData['template']         = render_device( @file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/felogin/'.$crow["acontent_template"]) );

    $_loginData['form']             = get_tmpl_section('LOGIN_FORM', $_loginData['template']);
    $_loginData['logged_in']        = get_tmpl_section('LOGIN_IS', $_loginData['template']);

    $_loginData['register']         = get_tmpl_section('PROFILE_REGISTER', $_loginData['template']);
    $_loginData['manage']           = get_tmpl_section('PROFILE_MANAGE', $_loginData['template']);

    $_loginData['reminder']         = get_tmpl_section('REMINDER_FORM', $_loginData['template']);
    $_loginData['reminder_success'] = get_tmpl_section('REMINDER_SUCCESS', $_loginData['template']);
    $_loginData['reminder_email']   = get_tmpl_section('REMINDER_EMAIL', $_loginData['template']);
    $_loginData['reset']            = get_tmpl_section('REMINDER_RESET', $_loginData['template']);
    $_loginData['reset_success']    = get_tmpl_section('REMINDER_RESET_SUCCESS', $_loginData['template']);

    $_loginData['session_key']  = session_id();
    $_loginData['template']     = $_loginData['form'];
    $_loginData['error']        = false;
    $_loginData['login']        = '';
    $_loginData['password']     = '';
    $_loginData['remember']     = 0;
    $_loginData['remind_data']  = '';
    $_loginData['reset_error']  = '';
    $_loginData['reset_done']   = false;

    $_loginData['felogin_profile_registration'] = empty($_loginData['felogin_profile_registration']) ? 0 : 1;
    $_loginData['felogin_profile_manage']       = empty($_loginData['felogin_profile_manage']) ? 0 : 1;
    $_loginData['validate_db']['userdetail']    = empty($_loginData['felogin_validate_userdetail'])  ? 0 : 1;
    $_loginData['validate_db']['backenduser']   = empty($_loginData['felogin_validate_backenduser']) ? 0 : 1;
    $_loginData['validate_db']['email_login']   = empty($_loginData['felogin_accept_email_login']) ? 0 : 1;

    // handle Login
    if(isset($_POST['feLogin'])) {

        $_loginData['login']        = slweg($_POST['feLogin']);
        $_loginData['password']     = slweg($_POST['fePassword']);
        $_loginData['remember']     = empty($_POST['feRemember']) ? 0 : 1;
        $_loginData['query_result'] = _checkFrontendUserLogin($_loginData['login'], md5($_loginData['password']), $_loginData['validate_db']);

        // ok, and now check if we got valid login data
        if($_loginData['query_result'] !== false && is_array($_loginData['query_result']) && count($_loginData['query_result'])) {

            $_SESSION[ $_loginData['session_key'] ]             = $_loginData['login'];
            $_SESSION[ $_loginData['session_key'].'_userdata']  = _getFrontendUserBaseData($_loginData['query_result']);

            if($_loginData['remember'] && !empty($_loginData['felogin_cookie_expire'])) {

                setcookie(
                    'phpwcmsFeLoginRemember',
                    $_loginData['login'].'##-|-##'.md5($_loginData['password']).'##-|-##'.$_loginData['validate_db']['userdetail'].'##-|-##'.$_loginData['validate_db']['backenduser'],
                    time() + $_loginData['felogin_cookie_expire'],
                    '/',
                    getCookieDomain(),
                    PHPWCMS_SSL,
                    true
                );

            }

        } else {

            $_loginData['error'] = true;

        }

    } elseif(isset($_POST['feReminder'])) {

        $_loginData['remind_data'] = slweg($_POST['feReminder']);
        $_loginData['remind_login_known'] = empty($_POST['feReminderLoginKnown']) ? false : true;

        // Never reset a password or mail a reminder link while $phpwcms['site']
        // was derived from an unverified request host: an anonymous visitor
        // could otherwise poison the login URL sent to the account owner.
        $mail_site_url = phpwcms_mail_site_url();
        $reminder_allowed = $mail_site_url !== '';
        $reminder_throttled = $reminder_allowed && phpwcms_password_reset_rate_limited('reminder:' . getRemoteIP(), 60);

        if(!$reminder_allowed) {
            // tell the visitor that this is a configuration problem
            $_loginData['site_url_untrusted'] = true;
        }

        // check if valid email - send login
        if($reminder_allowed && $_loginData['remind_data'] && !$_loginData['remind_login_known'] && is_valid_email($_loginData['remind_data']) ) {

            if($_loginData['validate_db']['userdetail']) {
                $sql  = 'SELECT detail_id, detail_login AS LOGIN, detail_email AS EMAIL FROM '.DB_PREPEND."userdetail WHERE LOWER(detail_email)=";
                $sql .= _dbEscape(strtolower($_loginData['remind_data']))." LIMIT 1";
                $result = _dbQuery($sql);
            }

            // hm, seems no user found - OK test against cms users
            if($_loginData['validate_db']['backenduser'] && !isset($result[0])) {
                $sql  = 'SELECT usr_id, usr_login AS LOGIN, usr_email AS EMAIL FROM '.DB_PREPEND.'user WHERE ';
                $sql .= "LOWER(usr_email)="._dbEscape(strtolower($_loginData['remind_data']))." LIMIT 1";
                $result = _dbQuery($sql);
            }

            if(isset($result[0])) {
                if (is_valid_email($result[0]['LOGIN'])) {
                    $_loginData['remind_data'] = $result[0]['LOGIN'];
                } else {
                    $_loginData['remind_login'] = $result[0];
                }
            }
            }

        // otherwise check login and send password
        if($reminder_allowed && !$reminder_throttled && $_loginData['remind_data'] && empty($_loginData['remind_login'])) {

            if($_loginData['validate_db']['userdetail']) {
                $sql  = 'SELECT detail_id, detail_login AS LOGIN, detail_email AS EMAIL FROM '.DB_PREPEND."userdetail WHERE ";
                $sql .= 'detail_aktiv=1 AND (detail_login='._dbEscape($_loginData['remind_data']);
                if($_loginData['remind_login_known'] && is_valid_email($_loginData['remind_data'])) {
                    $sql .= ' OR LOWER(detail_email)='._dbEscape(strtolower($_loginData['remind_data']));
                }
                $sql .= ') LIMIT 1';
                $result = _dbQuery($sql);

                if(isset($result[0])) {
                    // A reminder must not change the password. Issue a single
                    // use token and let the owner choose a new password.
                    $reset_token = phpwcms_password_reset_token_create('userdetail', $result[0]['detail_id']);
                    if($reset_token !== false) {
                        $result[0]['RESET_URL'] = phpwcms_password_reset_url($reset_token, $mail_site_url);
                        $_loginData['remind_reset'] = $result[0];
                    }
                }
            }

            // hm, seems no user found - OK test against cms users
            if($_loginData['validate_db']['backenduser'] && !isset($result[0])) {
                $sql  = 'SELECT usr_id, usr_login AS LOGIN, usr_email AS EMAIL FROM '.DB_PREPEND.'user WHERE ';
                $sql .= 'usr_aktiv=1 AND (usr_login='._dbEscape($_loginData['remind_data']);
                if($_loginData['remind_login_known'] && is_valid_email($_loginData['remind_data'])) {
                    $sql .= ' OR LOWER(usr_email)='._dbEscape(strtolower($_loginData['remind_data']));
                }
                $sql .= ') LIMIT 1';
                $result = _dbQuery($sql);

                if(isset($result[0])) {
                    $reset_token = phpwcms_password_reset_token_create('user', $result[0]['usr_id']);
                    if($reset_token !== false) {
                        $result[0]['RESET_URL'] = phpwcms_password_reset_url($reset_token, $mail_site_url);
                        $_loginData['remind_reset'] = $result[0];
                    }
                }
            }
        }

        if($reminder_allowed && !$reminder_throttled && (isset($_loginData['remind_reset']) || isset($_loginData['remind_login']))) {

            $_loginData['reminder'] = $_loginData['reminder_success'];

            $_loginData['LOGIN_URL'] = rel_url(array(), array('profile_manage', 'profile_register', 'profile_reminder', 'feLogout') );

            $_loginData['reminder_email'] = str_replace('{LOGIN_URL}', $mail_site_url . $_loginData['LOGIN_URL'], $_loginData['reminder_email']);

            if(isset($_loginData['remind_reset'])) {

                $_loginData['reminder_email']   = str_replace('{LOGIN}', $_loginData['remind_reset']['LOGIN'], $_loginData['reminder_email']);
                $_loginData['reminder_email']   = str_replace('{RESET_URL}', $_loginData['remind_reset']['RESET_URL'], $_loginData['reminder_email']);
                // never mail a password; keep a custom template without a
                // {RESET_URL} placeholder from printing an empty value
                $_loginData['reminder_email']   = str_replace('{PASSWORD}', '', $_loginData['reminder_email']);
                $_loginData['reminder_to']      = $_loginData['remind_reset']['EMAIL'];

                $_loginData['reminder_email_body'] = returnTagContent( $_loginData['reminder_email'], 'PASSWORD_EMAIL' );
            } else {

                $_loginData['reminder_email']   = str_replace('{LOGIN}', $_loginData['remind_login']['LOGIN'], $_loginData['reminder_email']);
                $_loginData['reminder_to']      = $_loginData['remind_login']['EMAIL'];

                $_loginData['reminder_email_body'] = returnTagContent( $_loginData['reminder_email'], 'LOGIN_EMAIL' );
            }
            $_loginData['reminder_email_body'] = $_loginData['reminder_email_body']['tag'];

            $_loginData['reminder_email_subject'] =  returnTagContent( $_loginData['reminder_email'], 'SUBJECT' ) ;
            $_loginData['reminder_email_subject'] =  trim( $_loginData['reminder_email_subject']['tag'] );

            @sendEmail(array(
                'recipient' => $_loginData['reminder_to'],
                'subject' => $_loginData['reminder_email_subject'],
                'text' => $_loginData['reminder_email_body']
            ));

            // the log-once flag belongs to the blocked state only
            if(session_status() === PHP_SESSION_ACTIVE) {
                unset($_SESSION['phpwcms_fe_reminder_blocked']);
            }

        } else {

            $_loginData['error'] = true;

            // log the real reason once per frontend session so a bot cannot
            // flood the log table through the anonymous reminder form
            if(!$reminder_allowed && session_status() === PHP_SESSION_ACTIVE
                && empty($_SESSION['phpwcms_fe_reminder_blocked'])
            ) {
                $_SESSION['phpwcms_fe_reminder_blocked'] = true;
                log_message('FELOGIN', 'Password reminder blocked: site URL is not configured');
            }

        }

    } elseif(isset($_POST['feResetToken'])) {

        if(!headers_sent()) {
            header('Referrer-Policy: same-origin');
        }
        $_loginData['reset_token'] = is_string($_POST['feResetToken']) ? trim($_POST['feResetToken']) : '';
        $reset_password  = isset($_POST['feResetPassword']) ? (string)$_POST['feResetPassword'] : '';
        $reset_password2 = isset($_POST['feResetPassword2']) ? (string)$_POST['feResetPassword2'] : '';
        $reset_data = false;

        if(phpwcms_password_reset_rate_limited('reset:' . getRemoteIP(), 30)) {
            $_loginData['reset_error'] = 'Too many attempts. Please wait a moment.';
        } else {
            $reset_data = phpwcms_password_reset_token_lookup($_loginData['reset_token']);
            if($reset_data === false) {
                $_loginData['reset_error'] = 'The reset link is invalid or has expired.';
            } elseif(strlen($reset_password) < 8) {
                $_loginData['reset_error'] = 'The password must have at least 8 characters.';
            } elseif($reset_password !== $reset_password2) {
                $_loginData['reset_error'] = 'The passwords do not match.';
            } else {
                $reset_data = phpwcms_password_reset_token_consume($_loginData['reset_token']);
                if($reset_data === false) {
                    $_loginData['reset_error'] = 'The reset link is invalid or has expired.';
                } elseif($reset_data['scope'] === 'userdetail') {
                    $password_saved = _dbUpdate('userdetail', array('detail_password' => md5($reset_password)), 'WHERE detail_id=' . (int)$reset_data['id']);
                    if($password_saved) {
                        $_loginData['reset_done'] = true;
                    } else {
                        phpwcms_password_reset_token_restore($_loginData['reset_token'], $reset_data);
                        $_loginData['reset_error'] = 'The password could not be saved. Please try again.';
                    }
                } else {
                    $password_saved = _dbUpdate('user', array('usr_pass' => md5($reset_password)), 'WHERE usr_id=' . (int)$reset_data['id']);
                    if($password_saved) {
                        $_loginData['reset_done'] = true;
                    } else {
                        phpwcms_password_reset_token_restore($_loginData['reset_token'], $reset_data);
                        $_loginData['reset_error'] = 'The password could not be saved. Please try again.';
                    }
                }
            }
        }

    }

    // register profile default
    $_loginData['get_profile_register'] = 'create';
    $_loginData['get_profile_manage']   = 'edit';

    // A reset link or submission must render even for a logged-in visitor
    // (the link may be opened in a browser that still has a session).
    if(_getFeUserLoginStatus() && !isset($_POST['feResetToken']) && !(isset($_getVar['profile_reminder']) && isset($_getVar['token']))) {

        // proof if "former" redirect URL is known and redirect
        if(!empty($_SESSION['LOGIN_REDIRECT'])) {
            $linkto = $_SESSION['LOGIN_REDIRECT'];
            unset($_SESSION['LOGIN_REDIRECT']);
            headerRedirect($linkto);

        // user is logged in
        } elseif(isset($_POST['feLogin']) && empty($_POST['feNoRedirect'])) {
            headerRedirect(decode_entities(FE_CURRENT_URL));
        }

        // manage account
        if($_loginData['felogin_profile_manage'] && isset($_getVar['profile_manage'])) {

            $_loginData['get_profile_manage'] = strval($_getVar['profile_manage']);

            $_loginData['template']  = $_loginData['manage'];

        } else {

            $_loginData['template'] = $_loginData['logged_in'];
            $_loginData['template'] = str_replace('{LOGIN}', html_specialchars( $_SESSION[ $_loginData['session_key'] ] ), $_loginData['template']);

        }

    // check if user can register and if register form should be displayed
    } elseif($_loginData['felogin_profile_registration'] && isset($_getVar['profile_register'])) {

        $_loginData['get_profile_register'] = strval($_getVar['profile_register']);

        $_loginData['template'] = $_loginData['register'];

    } elseif(isset($_POST['feResetToken'])) {

        if(!empty($_loginData['reset_done'])) {
            $_loginData['template'] = trim($_loginData['reset_success']) !== ''
                ? $_loginData['reset_success']
                : '<p>@@Your password has been changed.@@</p>';
        } else {
            $_loginData['template'] = trim($_loginData['reset']) !== ''
                ? $_loginData['reset']
                : '<form action="{FORM_TARGET}" method="post"><input type="hidden" name="feResetToken" value="{RESET_TOKEN}" /><p><label>@@New password@@ <input type="password" name="feResetPassword" required="required" /></label></p><p><label>@@Repeat password@@ <input type="password" name="feResetPassword2" required="required" /></label></p><p><button type="submit">@@Reset password@@</button></p></form>';
            $has_reset_error_placeholder = str_contains($_loginData['template'], '{RESET_ERROR}');
            $_loginData['template'] = str_replace(
                array('{RESET_TOKEN}', '{RESET_ERROR}'),
                array(html($_loginData['reset_token']), html($_loginData['reset_error'])),
                $_loginData['template']
            );
            if($_loginData['reset_error'] !== '' && !$has_reset_error_placeholder) {
                $_loginData['template'] = '<p class="alert alert-danger">' . html($_loginData['reset_error']) . '</p>' . $_loginData['template'];
            }
        }

    } elseif(isset($_getVar['profile_reminder']) && isset($_getVar['token'])) {

        if(!headers_sent()) {
            header('Referrer-Policy: same-origin');
        }
        $_loginData['reset_token'] = is_string($_getVar['token']) ? trim($_getVar['token']) : '';
        if(phpwcms_password_reset_token_lookup($_loginData['reset_token']) === false) {
            $_loginData['template'] = '<p>@@The reset link is invalid or has expired.@@</p>';
        } else {
            $_loginData['template'] = trim($_loginData['reset']) !== ''
                ? $_loginData['reset']
                : '<form action="{FORM_TARGET}" method="post"><input type="hidden" name="feResetToken" value="{RESET_TOKEN}" /><p><label>@@New password@@ <input type="password" name="feResetPassword" required="required" /></label></p><p><label>@@Repeat password@@ <input type="password" name="feResetPassword2" required="required" /></label></p><p><button type="submit">@@Reset password@@</button></p></form>';
            $_loginData['template'] = str_replace(
                array('{RESET_TOKEN}', '{RESET_ERROR}'),
                array(html($_loginData['reset_token']), ''),
                $_loginData['template']
            );
        }

    } elseif(isset($_POST['feReminder']) || isset($_getVar['profile_reminder'])) {

        $_loginData['template'] = render_cnt_template($_loginData['reminder'], 'ERROR', ($_loginData['error'] ? 'login/email wrong' : '') );
        $_loginData['template'] = render_cnt_template($_loginData['template'], 'REMINDER', html_specialchars($_loginData['remind_data']) );

        // appended after render_cnt_template(), so a custom reminder template
        // that already prints its own [ERROR] block shows this notice as well
        if(!empty($_loginData['site_url_untrusted'])) {
            $_loginData['template'] .= '<div class="alert alert-danger" role="alert">@@Password reminder is unavailable until the site URL is configured or trusted.@@</div>';
        }

    } else {

        $_loginData['template'] = render_cnt_template($_loginData['template'], 'ERROR', ($_loginData['error'] ? 'login/pass wrong' : '') );
        $_loginData['template'] = render_cnt_template($_loginData['template'], 'LOGIN', html_specialchars($_loginData['login']));
        $_loginData['template'] = render_cnt_template($_loginData['template'], 'PASSWORD', '');
        $_loginData['template'] = render_cnt_template($_loginData['template'], 'REMEMBER', ($_loginData['remember'] ? ' checked="checked"' : '') );

    }

    // check register profile
    if($_loginData['felogin_profile_registration']) {
        // possible -> set link to form
        $_loginData['uri'] = rel_url( array('profile_register'=>$_loginData['get_profile_register']), array('profile_manage', 'profile_reminder', 'feLogout') );
        $_loginData['template'] = render_cnt_template($_loginData['template'], 'REGISTER_PROFILE', $_loginData['uri'] );
    } else {
        // not possible
        $_loginData['template'] = render_cnt_template($_loginData['template'], 'REGISTER_PROFILE', '' );
    }

    // check manage profile
    if($_loginData['felogin_profile_manage']) {

        if(isset($_GET['profile_manage'])) {
            $_loginData['template'] = render_cnt_template($_loginData['template'], 'MANAGE_PROFILE', '' );
        }

        // possible -> set link to form
        $_loginData['uri'] = rel_url(
            array('profile_manage'=>$_loginData['get_profile_manage']),
            array('profile_register', 'profile_reminder', 'feLogout'),
            empty($_loginData['felogin_profile_manage_redirect']) ? '' : $_loginData['felogin_profile_manage_redirect']
        );
        $_loginData['template'] = render_cnt_template($_loginData['template'], 'MANAGE_PROFILE', $_loginData['uri'] );

    } else {
        // not possible
        $_loginData['template'] = render_cnt_template($_loginData['template'], 'MANAGE_PROFILE', '' );
    }

    $_loginData['uri'] = rel_url( array('profile_reminder'=>'1'), array('profile_manage', 'profile_register', 'feLogout') );
    $_loginData['template'] = render_cnt_template($_loginData['template'], 'REMINDER_FORM', $_loginData['uri'] );

    $_loginData['uri'] = rel_url( array(), array('profile_manage', 'profile_register', 'profile_reminder', 'feLogout') );
    $CNT_TMP .=  str_replace(array('{FORM_TARGET}', '{LOGIN_URL}'), $_loginData['uri'], $_loginData['template']);

}

$CNT_TMP .= $crow['attr_class_id_close'];
