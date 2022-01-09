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

// email form new
$content["form"]['subject']                 = clean_slweg($_POST["cform_subject"]);
$content["form"]['startup']                 = slweg($_POST["cform_startup"]);
$content["form"]['startup_html']            = intval($_POST["cform_startup_html"]) ? 1 : 0;
$content["form"]["class"]                   = slweg($_POST["cform_class"]);
$content["form"]["error_class"]             = slweg($_POST["cform_error_class"]);
$content["form"]["label_wrap"]              = slweg($_POST["cform_label_wrap"]);
$content["form"]["cform_reqmark"]           = slweg($_POST["cform_reqmark"]);
$content["form"]["cform_function_validate"] = clean_slweg($_POST["cform_function_validate"]);
$content["form"]["cc"]                      = convertStringToArray(str_replace(array(' ',','), ';', clean_slweg($_POST["cform_cc"])),';');
foreach($content["form"]["cc"] as $e_key => $e_value) {
    if(!is_valid_email($content["form"]["cc"][$e_key])) {
        unset($content["form"]["cc"][$e_key]);
    }
}
$content["form"]["cc"] = implode(';', $content["form"]["cc"]);

$content["form"]["targettype"]  = clean_slweg($_POST["cform_targettype"]);

$content["form"]["target"]      = clean_slweg($_POST["cform_target"]);
$content["form"]["target"]      = sanitize_multiple_emails($content["form"]["target"]);
$content["form"]["target"]      = strtolower($content["form"]["target"]);
$content["form"]["target"]      = explode(';', $content["form"]["target"]);
if(!empty($content["form"]["target"]) && is_array($content["form"]["target"]) && count($content["form"]["target"])) {
    foreach($content["form"]["target"] as $e_key => $e_value) {
        if(!is_valid_email($content["form"]["target"][$e_key])) {
            unset($content["form"]["target"][$e_key]);
        }
    }
    $content["form"]["target"] = implode(';', $content["form"]["target"]);
} else {
    $content["form"]["target"] = '';
}
if(empty($content["form"]["target"]) && $content["form"]["targettype"] == 'email') {
    $content["form"]["target"] = $phpwcms['SMTP_FROM_EMAIL'];
}

$content["form"]["subjectselect"]   = clean_slweg($_POST["cform_subjectselect"]);

$content["form"]["sendertype"]      = clean_slweg($_POST["cform_sendertype"]);
$content["form"]["sender"]          = clean_slweg($_POST["cform_sender"]);
$content["form"]["sender"]          = str_replace(' ', ';', $content["form"]["sender"]);
list($content["form"]["sender"])    = explode(';', $content["form"]["sender"]);
$content["form"]["sender"]          = trim($content["form"]["sender"]);
if(!is_valid_email($content["form"]["sender"])) {
    $content["form"]["sender"]      = '';
    if($content["form"]["sendertype"] == 'email') {
        $content["form"]["sendertype"] = 'system';
    }
} elseif($content["form"]["sendertype"] == 'system' && $content["form"]["sender"]) {
    $content["form"]["sendertype"] = 'email';
}

$content["form"]["sendernametype"]  = clean_slweg($_POST["cform_sendernametype"]);
$content["form"]["sendername"]      = clean_slweg($_POST["cform_sendername"]);
if($content["form"]["sendernametype"] == 'system' && $content["form"]["sendername"]) {
    $content["form"]["sendernametype"] = 'custom';
}

$content['form']['verifyemail']     = isset($_POST['cform_field_verifyemail']) ? clean_slweg($_POST['cform_field_verifyemail']) : '';
$content["form"]["labelpos"]        = intval($_POST["cform_labelpos"]);
$content['form']["sendcopy"]        = empty($_POST["cform_sendcopy"]) ? 0 : 1;
$content['form']["copyto"]          = isset($_POST["cform_copyto"]) ? clean_slweg($_POST["cform_copyto"]) : '';

//double opt-in
$content['form']["doubleoptin"] = empty($_POST["cform_doubleoptin"]) ? 0 : 1;
$content["form"]["doubleoptin_targettype"] = clean_slweg($_POST["cform_targettype_doubleoptin"]);

$content['form']["onsuccess_redirect_doubleoptin"] = empty($_POST["cform_onsuccess_redirect_doubleoptin"]) ? 0 : intval($_POST["cform_onsuccess_redirect_doubleoptin"]);
if($content['form']["onsuccess_redirect_doubleoptin"] !== 1 && $content['form']["onsuccess_redirect_doubleoptin"] !== 2) {
    $content['form']["onsuccess_redirect_doubleoptin"] = 0;
}

$content['form']["onerror_redirect_doubleoptin"]   = empty($_POST["cform_onerror_redirect_doubleoptin"]) ? 0 : intval($_POST["cform_onerror_redirect_doubleoptin"]);
if($content['form']["onerror_redirect_doubleoptin"] !== 1 && $content['form']["onerror_redirect_doubleoptin"] !== 2) {
    $content['form']["onerror_redirect_doubleoptin"] = 0;
}

$content['form']['onsuccess_doubleoptin'] = $content['form']["onsuccess_redirect_doubleoptin"] === 2 ? slweg($_POST["cform_onsuccess_doubleoptin"]) : clean_slweg($_POST["cform_onsuccess_doubleoptin"]);
$content['form']['onerror_doubleoptin']   = $content['form']["onerror_redirect_doubleoptin"]   === 2 ? slweg($_POST["cform_onerror_doubleoptin"])   : clean_slweg($_POST["cform_onerror_doubleoptin"]);

$content['form']["template_format_doubleoptin"] = intval($_POST["cform_template_format_doubleoptin"]) ? 1 : 0;
$content['form']["template_doubleoptin"]        = slweg($_POST["cform_template_doubleoptin"]);

// disable formtracking as recommend for "send a friend" forms
$content['form']['formtracking_off'] = empty($_POST["cform_tracking_off"]) ? 0 : 1;

// check if email of sender and recipient have to be different
$content['form']['checktofrom'] = empty($_POST['cform_checktofrom']) ? 0 : 1;

$content['form']["onsuccess_redirect"] = empty($_POST["cform_onsuccess_redirect"]) ? 0 : intval($_POST["cform_onsuccess_redirect"]);
switch($content['form']["onsuccess_redirect"]) {
    case 1:
    case 2:
        break;
    default:
        $content['form']["onsuccess_redirect"] = 0;
}
$content['form']["onerror_redirect"]   = empty($_POST["cform_onerror_redirect"]) ? 0 : intval($_POST["cform_onerror_redirect"]);
switch($content['form']["onerror_redirect"]) {
    case 1:
    case 2:
        break;
    default:
        $content['form']["onerror_redirect"] = 0;
}
$content['form']["onsuccess"] = $content['form']["onsuccess_redirect"] === 2 ? slweg($_POST["cform_onsuccess"]) : clean_slweg($_POST["cform_onsuccess"]);
$content['form']["onerror"]   = $content['form']["onerror_redirect"]   === 2 ? slweg($_POST["cform_onerror"])   : clean_slweg($_POST["cform_onerror"]);

$content['form']["template_format"] = intval($_POST["cform_template_format"]) ? 1 : 0;
$content['form']["template"]        = slweg($_POST["cform_template"]);

$content['form']["template_format_copy"]    = intval($_POST["cform_template_format_copy"]) ? 1 : 0;
$content['form']["template_copy"]           = slweg($_POST["cform_template_copy"]);

$content['form']["function_to"] = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST["cform_function_to"]);
$content['form']["function_cc"] = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST["cform_function_cc"]);
if(is_callable($content['form']["function_to"])) {
    $content['form']["function_to"] = '_Proof_'.$content['form']["function_to"];
}
if(is_callable($content['form']["function_cc"])) {
    $content['form']["function_cc"] = '_Proof_'.$content['form']["function_cc"];
}

$content['form']["template_equal"]  = empty($_POST["cform_template_equal"]) ? 0 : 1;
$content['form']["customform"]      = slweg($_POST["cform_customform"]);
$content['form']["savedb"]          = empty($_POST["cform_savedb"]) ? 0 : 1;
$content['form']["saveprofile"]     = empty($_POST["cform_saveprofile"]) ? 0 : 1;
$content['form']["anchor_off"]      = isset($_POST["cform_anchor_off"]) && !intval($_POST["cform_anchor_off"]) ? 0 : 1;
$content['form']["ssl"]             = empty($_POST["cform_ssl"]) ? 0 : 1;
$content['form']["anchor_name"]     = clean_slweg($_POST["cform_anchor_name"]);

//$field_counter = 0;
$content["form"]["fields"] = array();
/*
 * now retrieve all form entities and check based on type
 */
foreach($_POST['cform_field_type'] as $key => $value) {

    if(!isset($_POST['cform_field_delete'][$key])) {

        $value = clean_slweg($value);
        $field_counter = intval($_POST['cform_order'][$key]);
        $content["form"]["fields"][$field_counter]['type'] = $value;

        // field name cannot include spaces and also should not include any special chars
        $content['form']["fields"][$field_counter]['name'] = attribute_name_clean(clean_slweg($_POST['cform_field_name'][$key]));
        $content['form']["fields"][$field_counter]['label'] = clean_slweg($_POST['cform_field_label'][$key]);
        $content['form']["fields"][$field_counter]['required'] = isset($_POST['cform_field_required'][$key]) ? 1 : 0;
        $content['form']["fields"][$field_counter]['value'] = slweg($_POST['cform_field_value'][$key]);
        $content['form']["fields"][$field_counter]['error'] = clean_slweg($_POST['cform_field_error'][$key]);
        $content['form']["fields"][$field_counter]['style'] = clean_slweg($_POST['cform_field_style'][$key]);
        $content['form']["fields"][$field_counter]['class'] = clean_slweg($_POST['cform_field_class'][$key]);
        $content['form']["fields"][$field_counter]['placeholder'] = clean_slweg($_POST['cform_field_placeholder'][$key]);
        $content['form']["fields"][$field_counter]['profile'] = empty($_POST['cform_field_profile'][$key]) ? '' : clean_slweg($_POST['cform_field_profile'][$key]);

        switch($value) {

            case 'text':
                /*
                 * Text
                 */
                $content['form']["fields"][$field_counter]['value'] = str_replace("\r\n", ' ', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['value'] = str_replace("\r", ' ', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['value'] = str_replace("\n", ' ', $content['form']["fields"][$field_counter]['value']);

                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']   = intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
                break;

            case 'special':
                /*
                 * Special
                 */
                $content['form']["fields"][$field_counter]['value'] = slweg($_POST['cform_field_value'][$key]);
                $content['form']["fields"][$field_counter]['value'] = str_replace('"', '', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['value'] = str_replace("'", '', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['value'] = explode("\n", $content['form']["fields"][$field_counter]['value']);
                if(is_array($content['form']["fields"][$field_counter]['value']) && count($content['form']["fields"][$field_counter]['value'])) {
                    foreach($content['form']["fields"][$field_counter]['value'] as $_special) {
                        $_special = trim($_special);
                        $_special = explode('=', $_special);
                        if(isset($_special[0])) {
                            $_special[0] = strtolower(trim($_special[0]));
                            switch($_special[0]) {

                                case 'type':        if(!empty($_special[1])) {
                                                        $_special[1] = trim($_special[1]);
                                                        if($_special[1] !== 'a-Z' && $_special[1] !== 'a-z') {
                                                            $_special[1] = strtoupper($_special[1]);
                                                        }
                                                        switch($_special[1]) {
                                                            case 'MIX':
                                                            case 'INT':
                                                            case 'FLOAT':
                                                            case 'DEC':
                                                            case 'IDENT':
                                                            case 'STRING':
                                                            case 'DATE':
                                                            case 'A-Z':
                                                            case 'a-Z':
                                                            case 'a-z':
                                                            case '0-9':
                                                            case 'WORD':
                                                            case 'LETTER+SPACE':
                                                            case 'PHONE':
                                                            case 'REGEX':
                                                                $special_attribute['type'] = $_special[1];
                                                                break;
                                                        }
                                                    }
                                                    if(!isset($special_attribute['type'])) {
                                                        $special_attribute['type'] = 'MIX';
                                                    }
                                                    break;

                                case 'default':     $special_attribute['default'] = isset($_special[1]) ? trim($_special[1]) : '';
                                                    break;

                                case 'dateformat':  $special_attribute['dateformat'] = isset($_special[1]) ? trim($_special[1]) : 'm/d/Y';
                                                    break;

                                case 'pattern':     $special_attribute['pattern'] = isset($_special[1]) ? trim(trim($_special[1]), '/') : '/.*?/';
                                                    break;
                            }
                        }
                    }
                }
                $content['form']["fields"][$field_counter]['value'] = '';
                if(isset($special_attribute)) {
                    foreach($special_attribute as $_special_key => $_special) {
                        if($_special) {
                            $content['form']["fields"][$field_counter]['value'] .= $_special_key.'="'.$_special.'"'."\n";
                        }
                    }
                    $content['form']["fields"][$field_counter]['value'] = trim($content['form']["fields"][$field_counter]['value']);
                    unset($special_attribute, $_special, $_special_key);
                }
                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']   = intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
                break;

            case 'email':
                /*
                 * Email
                 */
                $content['form']["fields"][$field_counter]['value'] = str_replace("\r\n", ' ', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['value'] = str_replace("\r", ' ', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['value'] = str_replace("\n", ' ', $content['form']["fields"][$field_counter]['value']);

                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']   = intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
                break;

            case 'textarea':
                /*
                 * Textarea
                 */
                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']   = intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : 3;
                break;

            case 'hidden':
                /*
                 * Hidden
                 */
                $content['form']["fields"][$field_counter]['size']  = '';
                $content['form']["fields"][$field_counter]['max']   = '';
                $content['form']["fields"][$field_counter]['value'] = str_replace("\r\n", ' ', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['value'] = str_replace("\r", ' ', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['value'] = str_replace("\n", ' ', $content['form']["fields"][$field_counter]['value']);
                break;

            case 'password':
                /*
                 * Password
                 */
                $content['form']["fields"][$field_counter]['value'] = str_replace("\r\n", ' ', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['value'] = str_replace("\r", ' ', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['value'] = str_replace("\n", ' ', $content['form']["fields"][$field_counter]['value']);

                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']   = intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
                break;

            case 'country':
            case 'selectemail':
            case 'select':
                /*
                 * Select Menu
                 */
                $content['form']["fields"][$field_counter]['size']  = ''; //mutiple or not
                $content['form']["fields"][$field_counter]['max']   = '';
                break;

            case 'list':
                /*
                 * Liste
                 */
                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : 3;
                $content['form']["fields"][$field_counter]['max']   = intval($_POST['cform_field_max'][$key]) ? 1 : 0; //mutiple or not
                break;

            case 'newsletter':
                /*
                 * Newsletter
                 */
                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']   = '';
                $content['form']["fields"][$field_counter]['value'] = convertStringToArray($content['form']["fields"][$field_counter]['value'], "\n", 'UNIQUE', false);
                $newletter_array                                    = array();
                $newletter_array['double_optin']                    = 0;
                $newletter_array['subject']                         = 'Verify your newsletter subscription';

                foreach($content['form']["fields"][$field_counter]['value'] as $newsletter) {

                    $newsletter     = explode('=', $newsletter, 2);
                    $newsletter[0]  = trim($newsletter[0]);
                    $newsletter[1]  = empty($newsletter[1]) ? '' : trim($newsletter[1]);

                    if(empty($newsletter[0]) || empty($newsletter[1])) {

                        continue;

                    } else {

                        switch ($newsletter[0]) {

                            case 'all':
                                $newletter_array['all'] = $newsletter[1];
                                break;
                            case 'email_field':
                                $newletter_array['email_field'] = $newsletter[1];
                                break;
                            case 'name_field':
                                $newletter_array['name_field'] = $newsletter[1];
                                break;
                            case 'sender_email':
                                $newletter_array['sender_email'] = $newsletter[1];
                                break;
                            case 'sender_name':
                                $newletter_array['sender_name'] = $newsletter[1];
                                break;
                            case 'url_subscribe':
                                $newletter_array['url_subscribe'] = $newsletter[1];
                                break;
                            case 'url_unsubscribe':
                                $newletter_array['url_unsubscribe'] = $newsletter[1];
                                break;
                            case 'double_optin':
                                $newletter_array['double_optin'] = intval($newsletter[1]) ? 1 : 0;
                                break;
                            case 'optin_template':
                                $newletter_array['optin_template'] = $newsletter[1];
                                break;
                            case 'subject':
                                $newletter_array['subject'] = $newsletter[1];
                                break;

                            default:
                                if (intval($newsletter[0])) {
                                    $newsletter[0] = intval($newsletter[0]);
                                    $query = _dbGet('phpwcms_subscription', '*', 'subscription_id=' . $newsletter[0] . ' AND subscription_active=1');
                                    if (isset($query[0])) {
                                        if ($newsletter[1] == '') {
                                            $newsletter[1] = $query[0]['subscription_name'];
                                        }
                                        $newletter_array[$newsletter[0]] = $newsletter[1];
                                    }
                                }
                        }
                    }
                }

                $content['form']["fields"][$field_counter]['value'] = '';
                foreach($newletter_array as $newsletter_key => $newsletter_value) {
                    $content['form']["fields"][$field_counter]['value'] .= $newsletter_key.'='.$newsletter_value.LF;
                }
                $content['form']["fields"][$field_counter]['value'] = trim($content['form']["fields"][$field_counter]['value']);
                break;

            case 'checkboxcopy':
            case 'checkbox' :
                /*
                 * Checkbox
                 */
                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']   = strtoupper(clean_slweg($_POST['cform_field_max'][$key]));
                if (!in_array($content['form']["fields"][$field_counter]['max'], array('B3', 'B4', 'B5'))) {
                    $content['form']["fields"][$field_counter]['max'] = '';
                }
                break;

            case 'radio':
                /*
                 * Radiobutton
                 */
                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']   = strtoupper(clean_slweg($_POST['cform_field_max'][$key]));
                if (!in_array($content['form']["fields"][$field_counter]['max'], array('B3', 'B4', 'B5'))) {
                    $content['form']["fields"][$field_counter]['max'] = '';
                }
                break;

            case 'upload':
                /*
                 * Upload
                 */
                $content['form']["fields"][$field_counter]['value'] = slweg($_POST['cform_field_value'][$key]);
                $content['form']["fields"][$field_counter]['value'] = explode("\n", $content['form']["fields"][$field_counter]['value']);
                if(is_array($content['form']["fields"][$field_counter]['value']) && count($content['form']["fields"][$field_counter]['value'])) {
                    foreach($content['form']["fields"][$field_counter]['value'] as $upload) {
                        $upload = trim($upload);
                        $upload = explode('=', $upload, 2);
                        $upload[0] = strtolower(trim($upload[0]));
                        $upload[1] = isset($upload[1]) ? trim(trim(trim($upload[1]), "\"' ")) : '';
                        switch($upload[0]) {

                            case 'maxlength':
                                $upload_value['maxlength'] = isset($upload[1]) ? intval($upload[1]) : '';
                                break;

                            case 'folder':
                                $upload_value['folder'] = isset($upload[1]) ? trim($upload[1]) : 'content/form/';
                                $upload_value['folder'] = preg_replace('/\/{1,}$/', '', $upload_value['folder']);
                                $upload_value['folder'] = preg_replace('/^\//', '', $upload_value['folder']);
                                if(!is_dir(PHPWCMS_ROOT.'/'.$upload_value['folder']) || !is_writable(PHPWCMS_ROOT.'/'.$upload_value['folder'])) {
                                    $upload_value['folder'] = 'content/form/';
                                }
                                break;

                            case 'accept':
                                if(!empty($upload[1])) {
                                    $upload_value['accept'] = str_replace('.', '', str_replace(';', ',', strtolower($upload[1])));
                                    $upload_value['accept'] = convertStringToArray($upload_value['accept'], ',');
                                    $upload_value['accept'] = implode(',', $upload_value['accept']);
                                }
                                break;

                            case 'attachment':
                                $upload_value['attachment'] = isset($upload[1]) && intval($upload[1]) ? 1 : 0;
                                break;

                            case 'exclude':
                                if(empty($upload[1])) {
                                    $upload_value['exclude'] = 'php,asp,php3,php4,php5,aspx,cfm,js,exe,com,bat,app,sh,jar,java';
                                } else {
                                    $upload_value['exclude'] = str_replace(';', ',', strtolower($upload[1]));
                                    $upload_value['exclude'] = convertStringToArray($upload_value['exclude'], ',');
                                    $upload_value['exclude'] = implode(',', $upload_value['exclude']);
                                }
                                break;
                        }

                    }
                    if(!empty($upload_value['accept'])) {
                        unset($upload_value['exclude']);
                    }
                }
                $content['form']["fields"][$field_counter]['value'] = '';
                if(empty($upload_value['accept']) && empty($upload_value['exclude'])) {
                    $upload_value['exclude'] = 'php,asp,php3,php4,php5,aspx,cfm,js,exe,com,bat,app,sh,jar,java';
                }
                if(isset($upload_value)) {
                    foreach($upload_value as $upload_key => $upload) {
                        if($upload) {
                            $content['form']["fields"][$field_counter]['value'] .= $upload_key.'="'.$upload.'"'.LF;
                        }
                    }
                    $content['form']["fields"][$field_counter]['value'] = trim($content['form']["fields"][$field_counter]['value']);
                    unset($upload_value, $upload, $upload_key);
                }
                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']   = intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
                break;

            case 'submit':
                /*
                 * Submit
                 */
                $src_pos = strpos(strtolower($_POST['cform_field_value'][$key]), 'src=');
                if($src_pos === 0 || $src_pos) {
                    $content['form']["fields"][$field_counter]['value'] = slweg($_POST['cform_field_value'][$key]);
                }
                $content['form']["fields"][$field_counter]['value'] = str_replace(array("\r\n", "\r", "\n"), ' ', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['size']  = '';
                $content['form']["fields"][$field_counter]['max']   = '';
                break;

            case 'reset':
                /*
                 * Reset
                 */
                $src_pos = strpos(strtolower($_POST['cform_field_value'][$key]), 'src=');
                if($src_pos === 0 || $src_pos) {
                    $content['form']["fields"][$field_counter]['value'] = slweg($_POST['cform_field_value'][$key]);
                }
                $content['form']["fields"][$field_counter]['value'] = str_replace(array("\r\n", "\r", "\n"), ' ', $content['form']["fields"][$field_counter]['value']);
                $content['form']["fields"][$field_counter]['size']  = '';
                $content['form']["fields"][$field_counter]['max']   = '';
                break;

            case 'break':
                /*
                 * Break
                 */
                $content['form']["fields"][$field_counter]['size']  = '';
                $content['form']["fields"][$field_counter]['max']   = '';
                $content['form']["fields"][$field_counter]['value'] = slweg($_POST['cform_field_value'][$key]);
                break;

            case 'breaktext':
                /*
                 * Breaktext
                 */
                $content['form']["fields"][$field_counter]['size']  = '';
                $content['form']["fields"][$field_counter]['max']   = '';
                break;

            case 'captcha':
                /*
                 * Captcha Code Input Field
                 */
                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']   = intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
                $content['form']["fields"][$field_counter]['value'] = '';
                $content['form']["fields"][$field_counter]['required'] = 1;
                break;

            case 'captchaimg':
                /*
                 * Captcha Image
                 */
                $content['form']["fields"][$field_counter]['size']  = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']   = '';
                $content['form']["fields"][$field_counter]['value'] = slweg($_POST['cform_field_value'][$key]);
                break;

            case 'mathspam':
                /*
                 * Math Spam Protect
                 */
                $content['form']["fields"][$field_counter]['size']      = intval($_POST['cform_field_size'][$key]) ? intval($_POST['cform_field_size'][$key]) : '';
                $content['form']["fields"][$field_counter]['max']       = intval($_POST['cform_field_max'][$key]) ? intval($_POST['cform_field_max'][$key]) : '';
                $content['form']["fields"][$field_counter]['required']  = 1;
                $content['form']["fields"][$field_counter]['value']     = parse_ini_str( slweg($_POST['cform_field_value'][$key]), false );

                $mathspam = array(
                    '+'     => $BL['be_cnt_field']['summing'],
                    '-'     => $BL['be_cnt_field']['subtract'],
                    '*'     => $BL['be_cnt_field']['multiply'],
                    ':'     => $BL['be_cnt_field']['divide'],
                    'calc'  => $BL['be_cnt_field']['calculation']
                );

                if(isset($content['form']["fields"][$field_counter]['value']['+'])) {
                    $mathspam['+'] = $content['form']["fields"][$field_counter]['value']['+'];
                }
                if(isset($content['form']["fields"][$field_counter]['value']['-'])) {
                    $mathspam['-'] = $content['form']["fields"][$field_counter]['value']['-'];
                }
                if(isset($content['form']["fields"][$field_counter]['value']['*'])) {
                    $mathspam['*'] = $content['form']["fields"][$field_counter]['value']['*'];
                }
                if(isset($content['form']["fields"][$field_counter]['value'][':'])) {
                    $mathspam[':'] = $content['form']["fields"][$field_counter]['value'][':'];
                }
                if(isset($content['form']["fields"][$field_counter]['value']['calc'])) {
                    $mathspam['calc'] = $content['form']["fields"][$field_counter]['value']['calc'];
                }

                $content['form']["fields"][$field_counter]['value'] = $mathspam;
                unset($mathspam);

                break;

            case 'recaptcha':
                /*
                 * reCAPTCHA
                 */
                $content['form']["fields"][$field_counter]['name']      = 'recaptcha';
                $content['form']["fields"][$field_counter]['size']      = '';
                $content['form']["fields"][$field_counter]['max']       = '';
                $content['form']["fields"][$field_counter]['required']  = 1;
                $content['form']["fields"][$field_counter]['value']     = parse_ini_str( slweg($_POST['cform_field_value'][$key]), false );
                $content['form']['recaptcha'] = array(
                    'site_key' => '',
                    'secret_key' => '',
                    'lang' => $phpwcms['default_lang'],
                    'theme' => 'light',
                    'type' => 'image',
                    'size' => 'normal',
                );

                if(isset($content['form']["fields"][$field_counter]['value']['site_key'])) {
                    $content['form']['recaptcha']['site_key'] = trim($content['form']["fields"][$field_counter]['value']['site_key']);
                } elseif(isset($content['form']["fields"][$field_counter]['value']['public_key'])) {
                    $content['form']['recaptcha']['site_key'] = trim($content['form']["fields"][$field_counter]['value']['public_key']);
                }
                if(isset($content['form']["fields"][$field_counter]['value']['secret_key'])) {
                    $content['form']['recaptcha']['secret_key'] = trim($content['form']["fields"][$field_counter]['value']['secret_key']);
                } elseif(isset($content['form']["fields"][$field_counter]['value']['private_key'])) {
                    $content['form']['recaptcha']['secret_key'] = trim($content['form']["fields"][$field_counter]['value']['private_key']);
                }
                if(!empty($content['form']["fields"][$field_counter]['value']['lang'])) {
                    $content['form']['recaptcha']['lang'] = strtolower($content['form']["fields"][$field_counter]['value']['lang']);
                }
                if(isset($content['form']["fields"][$field_counter]['value']['theme']) && in_array($content['form']["fields"][$field_counter]['value']['theme'], array('light', 'dark'))) {
                    $content['form']['recaptcha']['theme'] = $content['form']["fields"][$field_counter]['value']['theme'];
                }
                if(isset($content['form']["fields"][$field_counter]['value']['type']) && in_array($content['form']["fields"][$field_counter]['value']['type'], array('image', 'audio'))) {
                    $content['form']['recaptcha']['type'] = $content['form']["fields"][$field_counter]['value']['type'];
                }
                if(isset($content['form']["fields"][$field_counter]['value']['size']) && in_array($content['form']["fields"][$field_counter]['value']['size'], array('compact', 'normal'))) {
                    $content['form']['recaptcha']['size'] = $content['form']["fields"][$field_counter]['value']['size'];
                }

                $content['form']["fields"][$field_counter]['value'] = $content['form']['recaptcha'];
                unset($content['form']['recaptcha']);

                break;

            case 'recaptchainv':
                /*
                 * Invisible reCAPTCHA
                 */
                $content['form']["fields"][$field_counter]['name']      = 'recaptchainv';
                $content['form']["fields"][$field_counter]['size']      = '';
                $content['form']["fields"][$field_counter]['max']       = '';
                $content['form']["fields"][$field_counter]['required']  = 1;
                $content['form']["fields"][$field_counter]['value']     = parse_ini_str( slweg($_POST['cform_field_value'][$key]), false );
                $content['form']['recaptchainv'] = array(
                    'site_key' => '',
                    'secret_key' => '',
                    'lang' => $phpwcms['default_lang'],
                    'badge' => 'bottomright',
                    'type' => 'image',
                    'size' => '',
                );

                if(isset($content['form']["fields"][$field_counter]['value']['site_key'])) {
                    $content['form']['recaptchainv']['site_key'] = trim($content['form']["fields"][$field_counter]['value']['site_key']);
                }
                if(isset($content['form']["fields"][$field_counter]['value']['secret_key'])) {
                    $content['form']['recaptchainv']['secret_key'] = trim($content['form']["fields"][$field_counter]['value']['secret_key']);
                }
                if(!empty($content['form']["fields"][$field_counter]['value']['lang'])) {
                    $content['form']['recaptchainv']['lang'] = strtolower($content['form']["fields"][$field_counter]['value']['lang']);
                }
                if(isset($content['form']["fields"][$field_counter]['value']['type']) && in_array($content['form']["fields"][$field_counter]['value']['type'], array('image', 'audio'))) {
                    $content['form']['recaptchainv']['type'] = $content['form']["fields"][$field_counter]['value']['type'];
                }
                if(isset($content['form']["fields"][$field_counter]['value']['size']) && ($content['form']["fields"][$field_counter]['value']['size'] === '' || $content['form']["fields"][$field_counter]['value']['size'] === 'invisible')) {
                    $content['form']['recaptchainv']['size'] = $content['form']["fields"][$field_counter]['value']['size'];
                }
                if(isset($content['form']["fields"][$field_counter]['value']['badge']) && in_array($content['form']["fields"][$field_counter]['value']['type'], array('bottomright', 'bottomleft', 'inline'))) {
                    $content['form']['recaptchainv']['size'] = $content['form']["fields"][$field_counter]['value']['badge'];
                }

                $content['form']["fields"][$field_counter]['value'] = $content['form']['recaptchainv'];
                unset($content['form']['recaptchainv']);

                break;
        }

        /*
         * Test if values are filled in
         */
        $all_fields_empty  = $content['form']["fields"][$field_counter]['name'];
        $all_fields_empty .= $content['form']["fields"][$field_counter]['label'];
        $all_fields_empty .= $content['form']["fields"][$field_counter]['value'];
        $all_fields_empty .= $content['form']["fields"][$field_counter]['error'];
        $all_fields_empty .= $content['form']["fields"][$field_counter]['style'];


        if(trim($all_fields_empty) == '') {
            unset($content['form']["fields"][$field_counter]);
        } else {

            if($content['form']["fields"][$field_counter]['name'] == '') {
                $content['form']["fields"][$field_counter]['name'] = attribute_name_clean($content["form"]["fields"][$field_counter]['type']);
            }
            if($content['form']["fields"][$field_counter]['name'] == 'reset' || $content['form']["fields"][$field_counter]['name'] == 'submit') {
                $content['form']["fields"][$field_counter]['name'] .= 'It';
            }

            $current_field_name = preg_replace('/(.*?)(\d+){1,}$/', '$1', $content['form']["fields"][$field_counter]['name']);

            if(!isset($field_name[$current_field_name])) {
                $field_name[$current_field_name] = 0;
            } else {
                $content['form']["fields"][$field_counter]['name'] = $current_field_name . $field_name[$current_field_name];
                $field_name[$current_field_name]++;
            }

        }

    }

}

// sort form fields
ksort($content["form"]["fields"], SORT_NUMERIC);
