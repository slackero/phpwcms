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


//newsletter subscription

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

$content["newsletter"] = unserialize($crow["acontent_newsletter"]);
$content["newsletter"]["email_address_error"] = 0;
$content["newsletter"]["success"] = 0;
$content["newsletter"]["email_subscription"] = array();
if(empty($content["newsletter"]["email_address"])) {
    $content["newsletter"]["email_address"] = '';
}
if(empty($content["newsletter"]["email_name"])) {
    $content["newsletter"]["email_name"] = '';
}
if(empty($content["newsletter"]["label_pos"])) {
    $content["newsletter"]["label_pos"] = 0;
}

if(!$content["newsletter"]["change_text"] || !$content["newsletter"]["reg_text"]) {
    $temp_mailtext  = 'Hi {NEWSLETTER_NAME},'."\n\n";
    $temp_mailtext .= 'You have subscribed to the newsletter at'." \n";
    $temp_mailtext .= $phpwcms["site"]." \n\n";
    $temp_mailtext .= 'Before you will receive any newsletter '."\n";
    $temp_mailtext .= 'you have to verify the email address '."\n\n";
    $temp_mailtext .= '   {NEWSLETTER_EMAIL} '."\n\n\n";
    $temp_mailtext .= 'To verify click the link '."\n";
    $temp_mailtext .= '{NEWSLETTER_VERIFY} '."\n\n";
    $temp_mailtext .= 'To delete your entry from our database '."\n";
    $temp_mailtext .= '{NEWSLETTER_DELETE}'."\n\n\n";
    $temp_mailtext .= 'Best Regards'."\n";
    $temp_mailtext .= $phpwcms['SMTP_FROM_NAME']."\n";
    $temp_mailtext .= $phpwcms["admin_email"]."\n\n";
    $temp_mailtext .= "--\nIP: {IP}, Date: {DATE:d-m-Y, H:i:s}\n";

    if(!$content["newsletter"]["change_text"]) {
        $content["newsletter"]["change_text"] = $temp_mailtext;
    }
    if(!$content["newsletter"]["reg_text"]) {
        $content["newsletter"]["reg_text"] = $temp_mailtext;
    }
}

if(empty($content["newsletter"]["recaptcha"])) {

    $content["newsletter"]["recaptcha"] = 0;

} else {

    require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.recaptcha.php';

    $recaptcha = new phpwcmsRecaptcha($content["newsletter"]["recaptcha_config"]['site_key'], $content["newsletter"]["recaptcha_config"]['secret_key']);

    if(empty($content["newsletter"]["recaptcha_config"]['lang'])) {
        $content["newsletter"]["recaptcha_config"]['lang'] = $phpwcms['default_lang'];
    }

}

$content["newsletter"]["newsletter_error"] = '';

if(isset($_POST["newsletter_send"]) && intval($_POST["newsletter_send"])) {
    unset($content["newsletter"]["email_subscription"]);

    $content["newsletter"]["email_address"]         = clean_slweg(remove_unsecure_rptags($_POST["newsletter_email"]), 250);
    $content["newsletter"]["email_name"]            = clean_slweg(remove_unsecure_rptags($_POST["newsletter_name"]), 250);
    $content["newsletter"]["email_subscription"]    = isset($_POST["email_subscription"]) && is_array($_POST["email_subscription"]) && count($_POST["email_subscription"]) ? $_POST["email_subscription"] : false;

    if($content["newsletter"]["email_subscription"]) {
        foreach($content["newsletter"]["email_subscription"] as $nlkey => $nlvalue) {
            $content["newsletter"]["email_subscription"][$nlkey] = intval($nlvalue);
        }
    } else {
        $content["newsletter"]["newsletter_error"] = '@@No subscription selected. Choose a list you wish to subscribe to.@@';
    }

    if(empty($content["newsletter"]["url1"])) {
        $content["newsletter"]["url1"] = '';
    }
    if(empty($content["newsletter"]["url2"])) {
        $content["newsletter"]["url2"] = '';
    }

    if(empty($content["newsletter"]["newsletter_error"]) && $content["newsletter"]["recaptcha"]) {

        if(empty($_POST['g-recaptcha-response'])) {

            $content["newsletter"]["newsletter_error"] = '@@recaptcha-error:missing-input@@';

        } else {

            $recaptcha_response = $recaptcha->verify_response($_POST['g-recaptcha-response']);

            if($recaptcha_response['success'] === false) {
                if(is_array($recaptcha_response['error-codes']) && count($recaptcha_response['error-codes'])) {
                    $content["newsletter"]["newsletter_error"] = '@@recaptcha-error:'.current($recaptcha_response['error-codes']).'@@';
                } else {
                    $content["newsletter"]["newsletter_error"] = '@@recaptcha-error:'.$recaptcha_response['error-codes'].'@@';
                }
            }

        }

    }

    if(empty($content["newsletter"]["newsletter_error"]) && is_valid_email($content["newsletter"]["email_address"]) && $content["newsletter"]["email_subscription"]) {
        //Success
        $content["newsletter"]["success"] = 1;

        $check_sql = "SELECT address_id,address_key FROM ".DB_PREPEND."phpwcms_address WHERE address_email="._dbEscape($content["newsletter"]["email_address"])." LIMIT 1";
        $check_result = _dbQuery($check_sql);

        if(isset($check_result[0]['address_id'])) {
            $content["newsletter"]["reffering_key"] = $check_result[0]["address_key"];
            $content["newsletter"]["reffering_id"]  = $check_result[0]["address_id"];
        } else {
            $content["newsletter"]["reffering_key"] = '';
        }

        if($content["newsletter"]["reffering_key"]) {

            //if email exists in newsletter address list update entry
            $e_sql = "UPDATE ".DB_PREPEND."phpwcms_address SET ".
                     "address_name="._dbEscape($content["newsletter"]["email_name"]).", ".
                     "address_verified=0, ".
                     "address_subscription="._dbEscape(serialize($content["newsletter"]["email_subscription"])).", ".
                     "address_url1="._dbEscape($content["newsletter"]["url1"]).", ".
                     "address_url2="._dbEscape($content["newsletter"]["url2"])." ".
                     "WHERE address_id="._dbEscape($content["newsletter"]["reffering_id"]);
            $content["newsletter"]["updated"] = 1;
            _dbQuery($e_sql, 'UPDATE');

        } else {

            $content["newsletter"]["reffering_key"] = preg_replace('/[^a-z0-9]/i', '', shortHash($content["newsletter"]["email_address"].time()) );
            //if email not exists in newsletter address list insert entry
            $e_sql = "INSERT INTO ".DB_PREPEND."phpwcms_address (".
                     "address_email, address_name, address_key, address_subscription, address_url1, address_url2) VALUES (".
                     _dbEscape($content["newsletter"]["email_address"]).", ".
                     _dbEscape($content["newsletter"]["email_name"]).", ".
                     _dbEscape($content["newsletter"]["reffering_key"]).", ".
                     _dbEscape(serialize($content["newsletter"]["email_subscription"])).", ".
                     _dbEscape($content["newsletter"]["url1"]).", ".
                     _dbEscape($content["newsletter"]["url2"]).")";
            $content["newsletter"]["updated"] = 0;
            _dbQuery($e_sql, 'INSERT');

        }

        $content["newsletter"]["verify_link"] = PHPWCMS_URL."verify.php?s=".rawurlencode($content["newsletter"]["reffering_key"]);
        $content["newsletter"]["delete_link"] = PHPWCMS_URL."verify.php?u=".rawurlencode($content["newsletter"]["reffering_key"]);
        $content["newsletter"]["mailtext"] = ($content["newsletter"]["updated"]) ? $content["newsletter"]["change_text"] : $content["newsletter"]["reg_text"];
        $content["newsletter"]["mailtext"] = str_replace("{NEWSLETTER_NAME}", $content["newsletter"]["email_name"], $content["newsletter"]["mailtext"]);
        $content["newsletter"]["mailtext"] = str_replace("{NEWSLETTER_EMAIL}", $content["newsletter"]["email_address"], $content["newsletter"]["mailtext"]);
        $content["newsletter"]["mailtext"] = str_replace("{NEWSLETTER_VERIFY}", $content["newsletter"]["verify_link"], $content["newsletter"]["mailtext"]);
        $content["newsletter"]["mailtext"] = str_replace("{NEWSLETTER_DELETE}", $content["newsletter"]["delete_link"], $content["newsletter"]["mailtext"]);
        $content["newsletter"]["mailtext"] = replaceGlobalRT($content["newsletter"]["mailtext"]);

        $content['newsletter']['subject']  = returnTagContent($content["newsletter"]["mailtext"], 'SUBJECT');
        if(empty($content['newsletter']['subject']['tag'])) {
            if(isset($content['newsletter']['subject']['new'])) {
                $content["newsletter"]["mailtext"] = $content['newsletter']['subject']['new'];
            }
            $content['newsletter']['subject'] = 'Newsletter verification for '.$phpwcms["site"];
        } else {
            $content["newsletter"]["mailtext"] = $content['newsletter']['subject']['new'];
            $content['newsletter']['subject'] = $content['newsletter']['subject']['tag'];
        }

        require_once PHPWCMS_ROOT.'/include/inc_ext/phpmailer/PHPMailerAutoload.php';

        // phpMailer Class
        $mail = new PHPMailer();
        $mail->Mailer           = $phpwcms['SMTP_MAILER'];
        $mail->Host             = $phpwcms['SMTP_HOST'];
        $mail->Port             = $phpwcms['SMTP_PORT'];
        if($phpwcms['SMTP_AUTH']) {
            $mail->SMTPAuth     = 1;
            $mail->Username     = $phpwcms['SMTP_USER'];
            $mail->Password     = $phpwcms['SMTP_PASS'];
        }
        if(!empty($phpwcms['SMTP_SECURE'])) {
            $mail->SMTPSecure   = $phpwcms['SMTP_SECURE'];
        }
        if(!empty($phpwcms['SMTP_AUTH_TYPE'])) {
            $mail->AuthType = $phpwcms['SMTP_AUTH_TYPE'];
            if($phpwcms['SMTP_AUTH_TYPE'] === 'NTLM') {
                if(!empty($phpwcms['SMTP_REALM'])) {
                    $mail->Realm = $phpwcms['SMTP_REALM'];
                }
                if(!empty($phpwcms['SMTP_WORKSTATION'])) {
                    $mail->Workstation = $phpwcms['SMTP_WORKSTATION'];
                }
            }
        }
        $mail->SMTPKeepAlive    = false;
        $mail->CharSet          = $phpwcms["charset"];
        $mail->isHTML(0);
        $mail->Subject          = $content['newsletter']['subject'];
        $mail->Body             = $content["newsletter"]["mailtext"];

        if(!$mail->setLanguage($phpwcms['default_lang'], PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/')) {
            $mail->setLanguage('en', PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/');
        }

        $mail->setFrom($phpwcms['SMTP_FROM_EMAIL'], $phpwcms['SMTP_FROM_NAME']);
        $mail->addReplyTo($phpwcms["admin_email"]);

        $mail->clearAddresses();
        $mail->addAddress($content["newsletter"]["email_address"]);

        if(!$mail->send()) {
            $template_default["article"]["newsletter_error"] = html($mail->ErrorInfo);
            $content["newsletter"]["success"] = 0;
            $content["newsletter"]["email_address_error"] = 1;
        }

        $mail->smtpClose();

    } else {
        //Error
        $content["newsletter"]["email_address_error"] = 1;
    }

    $content["newsletter"]["email_address"] = html($content["newsletter"]["email_address"]);
    $content["newsletter"]["email_name"] = html($content["newsletter"]["email_name"]);
}

if($content["newsletter"]["success"]) {

    $content["newsletter"]["success_text"] = str_replace("{NEWSLETTER_EMAIL}", $content["newsletter"]["email_address"], html($content["newsletter"]["success_text"]));

    $CNT_TMP .= div_class(
        $content["newsletter"]["success_text"] ? nl2br($content["newsletter"]["success_text"]) : sprintf("@@Email: %s successfully registered. You will receive a verification email within seconds.@@", $content["newsletter"]["email_address"]), $template_default["article"]["text_class"]
    );

} else {

    $label_pos = empty($content["newsletter"]["label_pos"]) ? '' : ' label-offset';

    $content["newsletter"]["form_id"] = 'subscribe-newsletter-form'.$crow['acontent_id'];

    $CNT_TMP .= $content["newsletter"]["text"] ? nl2br(div_class(html($content["newsletter"]["text"]), $template_default["article"]["text_class"])) : '';
    $CNT_TMP .= '<form action="'.FE_CURRENT_URL.'#'.$content["newsletter"]["form_id"].'" method="post" id="'.$content["newsletter"]["form_id"].'"';

    switch($content["newsletter"]["pos"]) {
        case 1:
            $content["newsletter"]["class"] = trim($template_default['classes']['newsletter-table'].' pull-left');
            break;

        case 2:
            $content["newsletter"]["class"] = trim($template_default['classes']['newsletter-table'].' center-block');
            break;

        case 3:
            $content["newsletter"]["class"] = trim($template_default['classes']['newsletter-table'].' pull-right');
            break;

        default:
            $content["newsletter"]["class"] = $template_default['classes']['newsletter-table'];
    }

    $CNT_TMP .= ' class="'.$content["newsletter"]["class"].$label_pos.'">' . LF;

    if($content["newsletter"]["newsletter_error"]) {
        $CNT_TMP .= '<p class="formError">'.$content["newsletter"]["newsletter_error"].'<p>' . LF;
    } elseif($content["newsletter"]["email_address_error"]) {
        $CNT_TMP .= '<p class="formError">'.$template_default["article"]["newsletter_error"].'<p>' . LF;
    }

    $CNT_TMP .= '<fieldset class="subscriber">';

    $CNT_TMP .= '<div class="form-group">';
    $CNT_TMP .= '<label class="formLabel">' . ($content["newsletter"]["label_email"] ? $content["newsletter"]["label_email"] : "@@email:@@") . '</label> ';
    $CNT_TMP .= '<input name="newsletter_email" type="email" class="'.$template_default['classes']['newsletter-input-email'].'" size="30" maxlength="250" ';
    $CNT_TMP .= 'value="'.$content["newsletter"]["email_address"].'" required="required" placeholder="@@newsletter email@@" /></div>';

    $CNT_TMP .= '<div class="form-group">';
    $CNT_TMP .= '<label class="formLabel">' . ($content["newsletter"]["label_name"] ? $content["newsletter"]["label_name"] : '@@name:@@') . '</label> ';
    $CNT_TMP .= '<input name="newsletter_name" type="text" class="'.$template_default['classes']['newsletter-input-name'].'" size="30" maxlength="250" ';
    $CNT_TMP .= 'value="'.$content["newsletter"]["email_name"].'" placeholder="@@newsletter name@@" /></div>';

    $CNT_TMP .= '</fieldset>' . LF;

    if(is_array($content["newsletter"]["subscription"]) && count($content["newsletter"]["subscription"])) {

        // retrieve all active newsletters
        $content["newsletter"]['temp'] = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_subscription WHERE subscription_active=1 ORDER BY subscription_name");
        foreach($content["newsletter"]['temp'] as $nlvalue) {

            if(isset($content["newsletter"]["subscription"][ $nlvalue['subscription_id'] ])) {
                $content["newsletter"]["subscription"][ $nlvalue['subscription_id'] ] = $nlvalue['subscription_name'];
            }

        }
        // check for "all" subscriptions setting
        if(isset($content["newsletter"]["subscription"][0])) {
            $content["newsletter"]["subscription"][0] = empty($content["newsletter"]["all_subscriptions"]) ? '@@subscribe to all@@' : $content["newsletter"]["all_subscriptions"];
        }

        $content["newsletter"]['c'] = 0;
        $content["newsletter"]['t'] = '';
        foreach($content["newsletter"]["subscription"] as $nlkey => $nlvalue) {

            if(is_numeric($nlvalue)) {
                continue;
            }

            $content["newsletter"]['t'] .= '<li class="'.$template_default['classes']['newsletter-checkbox-item'].'">';
            $content["newsletter"]['t'] .= '<div class="checkbox"><label for="email_subscription_'.$nlkey.'">';
            $content["newsletter"]['t'] .= '<input name="email_subscription['.$nlkey.']" type="checkbox" value="'.$nlkey.'"';
            if(isset($content["newsletter"]["email_subscription"][$nlkey])) {
                $content["newsletter"]['t'] .= ' checked="checked"';
            }
            $content["newsletter"]['t'] .= ' id="email_subscription_'.$nlkey.'" /> ';
            $content["newsletter"]['t'] .= html($nlvalue);
            $content["newsletter"]['t'] .= '</label></div></li>';

            $content["newsletter"]['c']++;

        }

        if($content["newsletter"]['c']) {

            $CNT_TMP .= '<fieldset class="subscriptions">' . LF;
            $CNT_TMP .= '<legend>' . (empty($content["newsletter"]["label_subscriptions"]) ? '@@subscribe&nbsp;to:@@' : html($content["newsletter"]["label_subscriptions"])) . '</legend>' . LF;
            $CNT_TMP .= '<ul class="'.$template_default['classes']['newsletter-table-subscription'].'">' . LF;
            $CNT_TMP .= $content["newsletter"]['t'];
            $CNT_TMP .= '</ul>' . LF;
            $CNT_TMP .= '</fieldset>' . LF;

        }

    }

    // reCAPTCHA v2
    if($content["newsletter"]["recaptcha"] === 1) {

        $block['custom_htmlhead']['recaptcha_api.js'] = '  ' . $recaptcha->get_api_src($content["newsletter"]["recaptcha_config"]['lang'], true);

        $CNT_TMP .= '<fieldset class="subscribe-recaptcha">' . LF;

        $CNT_TMP .= '<div class="g-recaptcha"';
        $CNT_TMP .= ' data-sitekey="'.$recaptcha->get_site_key().'"';
        $CNT_TMP .= ' data-theme="'.$content["newsletter"]["recaptcha_config"]['theme'].'"';
        $CNT_TMP .= ' data-type="'.$content["newsletter"]["recaptcha_config"]['type'].'"';
        $CNT_TMP .= ' data-size="'.$content["newsletter"]["recaptcha_config"]['size'].'"';
        $CNT_TMP .= '></div>';

        $CNT_TMP .= '</fieldset>' . LF;

    }

    $CNT_TMP .= '<fieldset class="subscribe-buttons">' . LF;

        // reCAPTCHA v2
    if($content["newsletter"]["recaptcha"] === 2) {

        $block['custom_htmlhead']['recaptcha_api.js'] = '  ' . $recaptcha->get_api_src($content["newsletter"]["recaptcha_config"]['lang'], true);
        $block['custom_htmlhead']['recaptchainv_submit'.$crow['acontent_id']] = '  '.$recaptcha->get_onsubmit_function($crow['acontent_id'], $content["newsletter"]["form_id"], true);

        $CNT_TMP .= '<button type="submit" class="'.trim('g-recaptcha '.$template_default['classes']['newsletter-submit-button']).'"';
        $CNT_TMP .= ' data-sitekey="'.$recaptcha->get_site_key().'"';
        $CNT_TMP .= ' data-badge="'.$content["newsletter"]["recaptcha_config"]['badge'].'"';
        $CNT_TMP .= ' data-type="'.$content["newsletter"]["recaptcha_config"]['type'].'"';
        $CNT_TMP .= ' data-size="'.$content["newsletter"]["recaptcha_config"]['size'].'"';
        $CNT_TMP .= ' data-callback="'.$recaptcha->get_callback().'"';
        $CNT_TMP .= '>';

    } else {
        $CNT_TMP .= '<button type="submit" class="'.$template_default['classes']['newsletter-submit-button'].'">';
    }

    $CNT_TMP .= $content["newsletter"]["button_text"] ? $content["newsletter"]["button_text"] : '@@Subscribe@@';
    $CNT_TMP .= '</button>' . LF;
    $CNT_TMP .= '<input name="newsletter_send" type="hidden" value="1" />';

    $CNT_TMP .= '</fieldset>' . LF;

    $CNT_TMP .= '</form>';
}

$CNT_TMP .= $crow['attr_class_id_close'];
