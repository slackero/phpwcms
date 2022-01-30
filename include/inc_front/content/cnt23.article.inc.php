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

include_once PHPWCMS_ROOT.'/include/inc_front/content/cnt_functions/cnt23.func.inc.php';

// Form
$cnt_form = unserialize($crow["acontent_form"]);

if(empty($cnt_form['anchor_off'])) {
    $CNT_TMP .= '<a id="';
    if(empty($cnt_form['anchor_name'])) {
        $CNT_TMP .= 'jumpForm'.$crow["acontent_id"];
    } else {
        $CNT_TMP .= html($cnt_form['anchor_name']);
    }
    $CNT_TMP .= '"></a>';
}

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

if(!empty($cnt_form['ssl']) && !PHPWCMS_SSL) {
    headerRedirect($phpwcms['site_ssl_url'] . rel_url(), 301);
}

// save default form tracking status
$default_formtracking_value = $phpwcms['form_tracking'];
// check form related form tracking status
$phpwcms['form_tracking'] = empty($cnt_form['formtracking_off']) ? 1 : 0;

$form_error_text = '';
$doubleoptin_error = false;
$doubleoptin_values = null;

$form_cnt = $cnt_form['labelpos'] == 2 ? render_device( $cnt_form['customform'] ) : '';

// set sender email address
if(empty($cnt_form['sendertype']) || $cnt_form['sendertype'] == 'system') {
    $cnt_form['sender'] = $phpwcms['SMTP_FROM_EMAIL'];
} elseif($cnt_form['sendertype'] == 'email' && !is_valid_email($cnt_form['sender'])) {
    $cnt_form['sender'] = $phpwcms['SMTP_FROM_EMAIL'];
}

// basic sender name check
if(empty($cnt_form['sendernametype'])) {

    $cnt_form['sendername']     = '';
    $cnt_form['sendernametype'] = '';

} elseif($cnt_form['sendernametype'] == 'system') {

    $cnt_form['sendername'] = $phpwcms['SMTP_FROM_NAME'];

}

if(empty($cnt_form['sendername'])) {
    $cnt_form['sendername'] = '';
}
if(empty($cnt_form["error_class"])) {
    $cnt_form["error_class"] = 'error';
}

// set enctype mode false (no upload)
$cnt_form['is_enctype']         = false;

// use default behavior for send email copy to
$cnt_form['option_email_copy']  = NULL;

/*
 * Browse form fields
 */
if(isset($cnt_form["fields"]) && is_array($cnt_form["fields"]) && count($cnt_form["fields"])) {

    $form_counter = 0;
    $cnt_form['label_wrap'] = explode('|', $cnt_form['label_wrap']);
    $cnt_form['label_wrap'][0] = !empty($cnt_form['label_wrap'][0]) ? trim($cnt_form['label_wrap'][0]) : '';
    $cnt_form['label_wrap'][1] = !empty($cnt_form['label_wrap'][1]) ? trim($cnt_form['label_wrap'][1]) : '';
    $form_field_hidden = '';
    $POST_DO = false;

    $cnt_form['regx_pattern'] = array(
        'A-Z'           => '/^[A-Z]+$/',
        'a-Z'           => '/^[a-zA-Z]+$/',
        'a-z'           => '/^[a-z]+$/',
        '0-9'           => '/^[0-9]+$/',
        'PHONE'         => '/^[+]?([0-9]*[\.\s\-\(\)\/]|[0-9]+){3,24}$/',
        'INT'           => '/^[0-9\-\+]+$/',
        'WORD'          => '/^[\w]+$/',
        'LETTER+SPACE'  => '/^[a-z _\-\:]+$/i'
    );

    if(!empty($_POST['cpID'.$crow["acontent_id"]]) && intval($_POST['cpID'.$crow["acontent_id"]]) == $crow["acontent_id"]) {

        $POST_DO = true;
        $POST_val = array();
        $cache_nosave = true;

    } elseif(!empty($_GET['hash']) && !empty($cnt_form['doubleoptin'])) {

        $cache_nosave = true;
        $doubleoptin_values = _dbGet('phpwcms_formresult', 'formresult_content', 'formresult_content LIKE ' . _dbEscape($_GET['hash'], true, '%', '%'));

        if(!isset($doubleoptin_values[0]['formresult_content'])) {
            $doubleoptin_values = null;
            $doubleoptin_error = true;
        } else {
            $doubleoptin_values = $doubleoptin_values[0];
            $doubleoptin_values['formresult_content'] = unserialize($doubleoptin_values['formresult_content']);
            if(empty($doubleoptin_values['formresult_content']['hash']) || $doubleoptin_values['formresult_content']['hash'] !== $_GET['hash']) {
                $doubleoptin_values = null;
                $doubleoptin_error = true;
            }
        }
    }

    // make spam check
    if($phpwcms['form_tracking'] && $POST_DO && !checkFormTrackingValue()) {
        $POST_ERR['spamFormAlert'.time()] = '[span_class:spamFormAlert]Your IP '.(PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP()).' is not allowed to send form![/class]';
    }

    $_default_cnt_form = array(
        'size' => '',
        'max' => '',
        'class' => '',
        'style' => '',
        'placeholder' => '',
        'required' => '',
        'error' => ''
    );

    foreach($cnt_form["fields"] as $key => $value) {

        $value = array_merge($_default_cnt_form, $value);
        $cnt_form["fields"][$key] = $value;

        $form_field = '';
        $form_name = html_specialchars($cnt_form["fields"][$key]['name']);
        $POST_name = $cnt_form["fields"][$key]['name'];

        switch($cnt_form["fields"][$key]['type']) {

            case 'text':
                /*
                 * Text
                 */
                if($POST_DO && isset($_POST[$POST_name])) {
                    $POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
                    if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] == '') {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    } else {
                        $cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
                    }
                }
                //
                $form_field .= '<input type="text" name="'.$form_name.'" id="'.$form_name.'" ';
                $form_field .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'"';
                if($cnt_form["fields"][$key]['size']) {
                    $form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
                }
                if($cnt_form["fields"][$key]['max']) {
                    $form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
                }
                if($cnt_form["fields"][$key]['class']) {
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                }
                if($cnt_form["fields"][$key]['style']) {
                    $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                }
                if(!empty($cnt_form["fields"][$key]['placeholder'])) {
                    $form_field .= ' placeholder="'.html_specialchars($cnt_form["fields"][$key]['placeholder']).'"';
                }
                if($cnt_form["fields"][$key]['required']) {
                    $form_field .= ' required="required"';
                }
                $form_field .= ' />';
                break;

            case 'captcha':
                /*
                 * Captcha
                 */
                if($POST_DO && isset($_POST[$POST_name])) {
                    $POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
                    include_once PHPWCMS_ROOT.'/include/inc_ext/SPAF_FormValidator.class.php';
                    $spaf_obj = new SPAF_FormValidator();
                    if($spaf_obj->validRequest($POST_val[$POST_name])) {
                        $spaf_obj->destroy();
                    } else {
                        $POST_ERR[$key] = empty($cnt_form["fields"][$key]['error']) ? 'Captcha error' : $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    }
                    $cnt_form["fields"][$key]['value'] = '';
                }
                //
                $form_field .= '<input type="text" name="'.$form_name.'" id="'.$form_name.'" value=""';
                if($cnt_form["fields"][$key]['size']) {
                    $form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
                }
                if($cnt_form["fields"][$key]['max']) {
                    $form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
                }
                if($cnt_form["fields"][$key]['class']) {
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                }
                if($cnt_form["fields"][$key]['style']) {
                    $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                }
                if(!empty($cnt_form["fields"][$key]['placeholder'])) {
                    $form_field .= ' placeholder="'.html_specialchars($cnt_form["fields"][$key]['placeholder']).'"';
                }
                if($cnt_form["fields"][$key]['required']) {
                    $form_field .= ' required="required"';
                }
                $form_field .= ' />';
                break;

            case 'recaptcha':
                /*
                 * reCAPTCHA
                 */
                require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.recaptcha.php';

                $cnt_form['recaptcha'] = array(
                    'site_key' => empty($cnt_form["fields"][$key]['value']['site_key']) ? get_user_rc('pu') : $cnt_form["fields"][$key]['value']['site_key'],
                    'secret_key' => empty($cnt_form["fields"][$key]['value']['secret_key']) ? get_user_rc('pr') : $cnt_form["fields"][$key]['value']['secret_key'],
                    'lang' => empty($cnt_form["fields"][$key]['value']['lang']) ? $phpwcms['default_lang'] : $cnt_form["fields"][$key]['value']['lang'],
                    'theme' => empty($cnt_form["fields"][$key]['value']['theme']) ? 'light' : $cnt_form["fields"][$key]['value']['theme'],
                    'type' => empty($cnt_form["fields"][$key]['value']['type']) ? 'image' : $cnt_form["fields"][$key]['value']['type'],
                    'size' => empty($cnt_form["fields"][$key]['value']['size']) ? 'normal' : $cnt_form["fields"][$key]['value']['size'],
                    'error' => null
                );

                $recaptcha = new phpwcmsRecaptcha($cnt_form['recaptcha']['site_key'], $cnt_form['recaptcha']['secret_key']);

                if($POST_DO) {
                    /***
                     * CAUTION: spambots may bypass g-recaptcha by removing
                     * g-recaptcha-response from posted values prior to submitting
                     *
                     * Thanks to Thomas Mooshammer to report this
                     */
                    if(empty($_POST['g-recaptcha-response'])) {
                        $_POST['g-recaptcha-response'] = generic_string(10);
                    }

                    if(isset($_POST['g-recaptcha-response'])) {

                        $cnt_form['recaptcha']['response'] = $recaptcha->verify_response($_POST['g-recaptcha-response']);

                        if($cnt_form['recaptcha']['response']['success'] === false) {
                            if(is_array($cnt_form['recaptcha']['response']['error-codes']) && count($cnt_form['recaptcha']['response']['error-codes'])) {
                                $cnt_form['recaptcha']['error'] = '@@recaptcha-error:'.current($cnt_form['recaptcha']['response']['error-codes']).'@@';
                            } else {
                                $cnt_form['recaptcha']['error'] = '@@recaptcha-error:'.$cnt_form['recaptcha']['response']['error-codes'].'@@';
                            }
                            $POST_ERR[$key] = empty($cnt_form["fields"][$key]['error']) ? $cnt_form['recaptcha']['error'] : $cnt_form["fields"][$key]['error'];
                            $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                        }
                    }
                }

                $form_field  = '<div class="g-recaptcha"';
                $form_field .= ' data-sitekey="'.$recaptcha->get_site_key().'"';
                $form_field .= ' data-theme="'.$cnt_form['recaptcha']['theme'].'"';
                $form_field .= ' data-type="'.$cnt_form['recaptcha']['type'].'"';
                $form_field .= ' data-size="'.$cnt_form['recaptcha']['size'].'"';
                $form_field .= '></div>';

                $block['custom_htmlhead']['recaptcha_api.js'] = '  ' . $recaptcha->get_api_src($cnt_form['recaptcha']['lang'], true);

                if($cnt_form["fields"][$key]['class'] || $cnt_form["fields"][$key]['style']) {
                    $form_field = '<div class="'.$cnt_form["fields"][$key]['class'].'" style="'.$cnt_form["fields"][$key]['style'].'">' . $form_field . '</div>';
                }

                break;

            case 'recaptchainv':
                /*
                 * Invisible reCAPTCHA
                 */
                require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.recaptcha.php';

                $cnt_form['recaptcha'] = array(
                    'site_key' => empty($cnt_form["fields"][$key]['value']['site_key']) ? get_user_rc('pu') : $cnt_form["fields"][$key]['value']['site_key'],
                    'secret_key' => empty($cnt_form["fields"][$key]['value']['secret_key']) ? get_user_rc('pr') : $cnt_form["fields"][$key]['value']['secret_key'],
                    'lang' => empty($cnt_form["fields"][$key]['value']['lang']) ? $phpwcms['default_lang'] : $cnt_form["fields"][$key]['value']['lang'],
                    'badge' => empty($cnt_form["fields"][$key]['value']['badge']) ? 'bottomright' : $cnt_form["fields"][$key]['value']['badge'],
                    'type' => empty($cnt_form["fields"][$key]['value']['type']) ? 'image' : $cnt_form["fields"][$key]['value']['type'],
                    'size' => empty($cnt_form["fields"][$key]['value']['size']) ? '' : $cnt_form["fields"][$key]['value']['size'],
                    'error' => null
                );

                $recaptcha = new phpwcmsRecaptcha($cnt_form['recaptcha']['site_key'], $cnt_form['recaptcha']['secret_key']);

                if($POST_DO) {
                    /***
                     * CAUTION: spambots may bypass g-recaptcha by removing
                     * g-recaptcha-response from posted values prior to submitting
                     *
                     * Thanks to Thomas Mooshammer to report this
                     */
                    if(empty($_POST['g-recaptcha-response'])) {
                        $_POST['g-recaptcha-response'] = generic_string(10);
                    }

                    if(isset($_POST['g-recaptcha-response'])) {

                        $cnt_form['recaptcha']['response'] = $recaptcha->verify_response($_POST['g-recaptcha-response']);

                        if($cnt_form['recaptcha']['response']['success'] === false) {
                            if(is_array($cnt_form['recaptcha']['response']['error-codes']) && count($cnt_form['recaptcha']['response']['error-codes'])) {
                                $cnt_form['recaptcha']['error'] = '@@recaptcha-error:'.current($cnt_form['recaptcha']['response']['error-codes']).'@@';
                            } else {
                                $cnt_form['recaptcha']['error'] = '@@recaptcha-error:'.$cnt_form['recaptcha']['response']['error-codes'].'@@';
                            }
                            $POST_ERR[$key] = empty($cnt_form["fields"][$key]['error']) ? $cnt_form['recaptcha']['error'] : $cnt_form["fields"][$key]['error'];
                            $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                        }
                    }
                }

                $block['custom_htmlhead']['recaptcha_api.js'] = '  ' . $recaptcha->get_api_src($cnt_form['recaptcha']['lang'], true);
                $block['custom_htmlhead']['recaptchainv_submit'.$crow["acontent_id"]]  = '  '.$recaptcha->get_onsubmit_function($crow['acontent_id'], 'phpwcmsForm'.$crow["acontent_id"], true);

                $crow['recaptcha_submit_data']  = ' data-sitekey="'.$recaptcha->get_site_key().'"';
                $crow['recaptcha_submit_data'] .= ' data-badge="'.$cnt_form['recaptcha']['badge'].'"';
                $crow['recaptcha_submit_data'] .= ' data-type="'.$cnt_form['recaptcha']['type'].'"';
                $crow['recaptcha_submit_data'] .= ' data-size="'.$cnt_form['recaptcha']['size'].'"';
                $crow['recaptcha_submit_data'] .= ' data-callback="'.$recaptcha->get_callback().'"';

                                break;

            case 'special':
                /*
                 * Special
                 */
                $cnt_form['special_attribute'] = array(
                    'default'       => '',
                    'type'          => 'MIX',
                    'dateformat'    => 'm/d/Y',
                    'pattern'       => '/.*?/'
                );
                //
                if($cnt_form["fields"][$key]['value']) {
                    $cnt_form['special_value'] = str_replace( array('"', "'", "\r'"), '', $cnt_form["fields"][$key]['value'] );
                    $cnt_form['special_value'] = explode("\n", $cnt_form['special_value']);
                    $cnt_form["fields"][$key]['value'] = '';

                    if(is_array($cnt_form['special_value']) && count($cnt_form['special_value'])) {
                        foreach($cnt_form['special_value'] as $cnt_form['special_key'] => $cnt_form['special_val']) {
                            $temp_array = explode('=', $cnt_form['special_val']);
                            if($temp_array[0] === 'default') {
                                $cnt_form['special_attribute']['default'] = isset($temp_array[1]) ? $temp_array[1] : '';
                            } elseif($temp_array[0] === 'type') {
                                $cnt_form['special_attribute']['type'] = isset($temp_array[1]) ? $temp_array[1] : 'MIX';
                            } elseif($temp_array[0] === 'dateformat') {
                                $cnt_form['special_attribute']['dateformat'] = isset($temp_array[1]) ? $temp_array[1] : 'm/d/Y';
                            } elseif($temp_array[0] === 'pattern') {
                                $cnt_form['special_attribute']['pattern'] = isset($temp_array[1]) ? ('/' . trim($temp_array[1], '/') . '/') : '/.*?/'; //#%+~
                            }
                        }
                    }
                }

                $cnt_form["fields"][$key]['value'] = isset($cnt_form['special_attribute']['default']) ? $cnt_form['special_attribute']['default'] : '';

                if($POST_DO && isset($_POST[$POST_name])) {
                    $POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
                    if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] == '') {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    } else {
                        $cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
                        // try to check for special value
                        switch($cnt_form['special_attribute']['type']) {
                            case 'A-Z':
                            case 'a-Z':
                            case 'a-z':
                            case '0-9':
                            case 'WORD':
                            case 'LETTER+SPACE':
                            case 'PHONE':
                            case 'INT':
                                if($cnt_form["fields"][$key]['value'] !== '' && !preg_match($cnt_form['regx_pattern'][ $cnt_form['special_attribute']['type'] ], $cnt_form["fields"][$key]['value'])) {
                                    $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                                }
                                break;

                            case 'REGEX':
                                if($cnt_form["fields"][$key]['value'] !== '' && !preg_match($cnt_form['special_attribute']['pattern'], $cnt_form["fields"][$key]['value'])) {
                                    $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                                }
                                break;

                            case 'DEC':
                            case 'FLOAT':
                                if($cnt_form["fields"][$key]['value'] !== '' && !is_float_ex($cnt_form["fields"][$key]['value'])) {
                                    $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                                }
                                break;

                            case 'IDENT':
                                if(isset($cnt_form['special_attribute']['default']) &&
                                    decode_entities($cnt_form['special_attribute']['default']) != decode_entities($cnt_form["fields"][$key]['value'])) {
                                    $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                                }
                                break;

                            case 'DATE':
                                if($cnt_form["fields"][$key]['value'] !== '' && isset($cnt_form['special_attribute']['dateformat']) &&
                                    !is_date($cnt_form["fields"][$key]['value'], $cnt_form['special_attribute']['dateformat'])) {
                                    $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                                }
                                break;
                        }
                    }
                } elseif(isset($cnt_form['special_attribute']['default']) && isset($cnt_form['special_attribute']['type']) && $cnt_form['special_attribute']['type'] === 'DATE' && $cnt_form['special_attribute']['default'] === 'NOW') {
                    $cnt_form["fields"][$key]['value'] = date(isset($cnt_form['special_attribute']['dateformat']) ? $cnt_form['special_attribute']['dateformat'] : 'm/d/Y');
                }

                $form_field_type = 'text';
                $form_field_attributes = '';

                switch($cnt_form['special_attribute']['type']) {
                    case 'A-Z':
                    case 'a-Z':
                    case 'a-z':
                    case '0-9':
                    case 'WORD':
                    case 'LETTER+SPACE':
                        $form_field_attributes = ' pattern="' . trim($cnt_form['regx_pattern'][ $cnt_form['special_attribute']['type'] ], '/') . '"';
                        break;

                    case 'PHONE':
                        $form_field_type = 'phone';
                        break;

                    case 'INT':
                        $form_field_type = 'number';
                        $form_field_attributes = ' step="1" pattern="\d+"';
                        break;

                    case 'DEC':
                        $form_field_type = 'number';
                        $form_field_attributes = ' step=".01" pattern="^\d+(\.\d{1,2})?$"';
                        break;

                    case 'REGEX':
                        $form_field_attributes = ' pattern="'.trim($cnt_form['special_attribute']['pattern'], '/').'"';
                        break;
                }

                $form_field .= '<input type="' . $form_field_type . '" name="'.$form_name.'" id="'.$form_name.'" ';
                $form_field .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'"';
                if($cnt_form["fields"][$key]['size']) {
                    $form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
                }
                if($cnt_form["fields"][$key]['max']) {
                    $form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
                }
                if($cnt_form["fields"][$key]['class']) {
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                }
                if($cnt_form["fields"][$key]['style']) {
                    $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                }
                if(!empty($cnt_form["fields"][$key]['placeholder'])) {
                    $form_field .= ' placeholder="'.html_specialchars($cnt_form["fields"][$key]['placeholder']).'"';
                }
                if($cnt_form["fields"][$key]['required']) {
                    $form_field .= ' required="required"';
                }
                $form_field .= $form_field_attributes . ' />';
                break;

            case 'email':
                /*
                 * Email
                 */
                if($POST_DO && isset($_POST[$POST_name])) {
                    $POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
                    if(($cnt_form["fields"][$key]['required'] && !$POST_val[$POST_name]) || ($POST_val[$POST_name] && !is_valid_email($POST_val[$POST_name]))) {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    }
                    $cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
                }
                // check if message should be delivered to email address of this field
                if($POST_DO && ($cnt_form['targettype'] === 'emailfield_'.$POST_name) && empty($POST_ERR[$key]) && is_valid_email($cnt_form["fields"][$key]['value'])) {
                    if(empty($cnt_form['target'])) {
                        $cnt_form['target'] = $cnt_form["fields"][$key]['value'];
                    } else {
                        $cnt_form['target'] = $cnt_form["fields"][$key]['value'].';'.$cnt_form['target'];
                    }
                }

                //check if message should be delivered to email address of this field doubleoptin
                if($POST_DO && empty($POST_ERR[$key]) && !empty($cnt_form['doubleoptin_targettype']) && ($cnt_form['doubleoptin_targettype'] === 'emailfield_'.$POST_name) && is_valid_email($cnt_form["fields"][$key]['value'])) {
                    $cnt_form['doubleoptin_target'] = $cnt_form["fields"][$key]['value'];
                } else {
                    $cnt_form['doubleoptin_target'] = $cnt_form['target'];
                }

                // check if message should be sent by email address of this field
                if($POST_DO && ($cnt_form['sendertype'] === 'emailfield_'.$POST_name) && empty($POST_ERR[$key]) && is_valid_email($cnt_form["fields"][$key]['value'])) {
                    $cnt_form['sender'] = $cnt_form["fields"][$key]['value'];
                }

                $form_field .= '<input type="'.(HTML5_MODE ? 'email' : 'text').'" name="'.$form_name.'" id="'.$form_name.'" ';
                $form_field .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'"';
                if($cnt_form["fields"][$key]['size']) {
                    $form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
                }
                if($cnt_form["fields"][$key]['max']) {
                    $form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
                }
                if($cnt_form["fields"][$key]['class']) {
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                }
                if($cnt_form["fields"][$key]['style']) {
                    $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                }
                if(!empty($cnt_form["fields"][$key]['placeholder'])) {
                    $form_field .= ' placeholder="'.html_specialchars($cnt_form["fields"][$key]['placeholder']).'"';
                }
                if($cnt_form["fields"][$key]['required']) {
                    $form_field .= ' required="required"';
                }
                $form_field .= ' />';
                break;

            case 'textarea':
                /*
                 * Textarea
                 */
                if($POST_DO && isset($_POST[$POST_name])) {
                    $POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
                    if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] == '') {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    } else {
                        $cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
                    }
                }
                //
                $form_field .= '<textarea name="'.$form_name.'" id="'.$form_name.'"';
                if($cnt_form["fields"][$key]['size']) {
                    $form_field .= ' cols="'.$cnt_form["fields"][$key]['size'].'"';
                } else {
                    $form_field .= ' cols="20"';
                }
                if($cnt_form["fields"][$key]['max']) {
                    $form_field .= ' rows="'.$cnt_form["fields"][$key]['max'].'"';
                }
                if($cnt_form["fields"][$key]['class']) {
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                }
                if($cnt_form["fields"][$key]['style']) {
                    $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                }
                if(!empty($cnt_form["fields"][$key]['placeholder'])) {
                    $form_field .= ' placeholder="'.html_specialchars($cnt_form["fields"][$key]['placeholder']).'"';
                }
                if($cnt_form["fields"][$key]['required']) {
                    $form_field .= ' required="required"';
                }
                $form_field .= '>'.html_specialchars($cnt_form["fields"][$key]['value']).'</textarea>';
                break;

            case 'hidden':
                /*
                 * Hidden
                 */
                if($POST_DO && isset($_POST[$POST_name])) {
                    $POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
                    if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] == '') {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                    } else {
                        $cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
                    }
                }
                //
                $form_field_hidden .= '<input type="hidden" name="'.$form_name.'" id="'.$form_name.'" ';
                $form_field_hidden .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'" />';
                break;

            case 'password':
                /*
                 * Password
                 */
                if($POST_DO && isset($_POST[$POST_name])) {
                    $POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
                    if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] == '') {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    } else {
                        $cnt_form["fields"][$key]['value'] = $POST_val[$POST_name];
                    }
                }
                //
                $form_field .= '<input type="password" name="'.$form_name.'" id="'.$form_name.'" ';
                $form_field .= 'value="'.html_specialchars($cnt_form["fields"][$key]['value']).'"';
                if($cnt_form["fields"][$key]['size']) {
                    $form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
                }
                if($cnt_form["fields"][$key]['max']) {
                    $form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
                }
                if($cnt_form["fields"][$key]['class']) {
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                }
                if($cnt_form["fields"][$key]['style']) {
                    $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                }
                if(!empty($cnt_form["fields"][$key]['placeholder'])) {
                    $form_field .= ' placeholder="'.html_specialchars($cnt_form["fields"][$key]['placeholder']).'"';
                }
                if($cnt_form["fields"][$key]['required']) {
                    $form_field .= ' required="required"';
                }
                $form_field .= ' autocomplete="new-password" />';
                break;

            case 'country':
            case 'selectemail':
            case 'select':
                /*
                 * Select menu
                 */
                if($POST_DO && isset($_POST[$POST_name])) {
                    $POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
                    if($POST_val[$POST_name] != '' && $cnt_form["fields"][$key]['type'] == 'selectemail') { // decrypt
                        $POST_val[$POST_name] = phpwcms_decrypt(base64_decode($POST_val[$POST_name]));
                    }
                    if($cnt_form["fields"][$key]['required'] && $POST_val[$POST_name] == '') {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    } else {
                        $cnt_form["fields"][$key]['value'] = str_replace(' selected', '', $cnt_form["fields"][$key]['value']);
                    }
                }
                //
                if($cnt_form["fields"][$key]['type'] == 'selectemail' && $POST_DO && empty($POST_ERR[$key]) ) {

                    // check if message should be delivered to email address of this field
                    if( ($cnt_form['targettype'] == 'emailfield_'.$POST_name)  && is_valid_email($POST_val[$POST_name])) {
                        if(empty($cnt_form['target'])) {
                            $cnt_form['target'] = $POST_val[$POST_name];
                        } else {
                            $cnt_form['target'] = $POST_val[$POST_name].';'.$cnt_form['target'];
                        }
                    }
                    //
                    // check if message should be sent by email address of this field
                    if( ($cnt_form['sendertype'] == 'emailfield_'.$POST_name) && is_valid_email($POST_val[$POST_name])) {
                        $cnt_form['sender'] = $POST_val[$POST_name];
                    }
                }
                //

                $form_field .= '<select name="'.$form_name.'" id="'.$form_name.'"';
                if($cnt_form["fields"][$key]['class']) {
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                }
                if($cnt_form["fields"][$key]['style']) {
                    $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                }
                if($cnt_form["fields"][$key]['required']) {
                    $form_field .= ' required="required"';
                }
                $form_field .= '>' . LF;

                // build country select menu
                if($cnt_form["fields"][$key]['type'] == 'country') {

                    // check which language should be used and
                    // which country should be set as default
                    $form_value = parse_ini_str($cnt_form["fields"][$key]['value'], false);
                    if(isset($form_value['lang'])) {
                        $form_value['lang'] = preg_replace('/[^a-zA-Z]/', '', $form_value['lang']);
                    } else {
                        $form_value['lang'] = $phpwcms['default_lang'];
                    }
                    if(isset($form_value['default'])) {
                        $form_value['default'] = preg_replace('/[^a-zA-Z]/', '', $form_value['default']);
                    } else {
                        $form_value['default'] = '-';
                    }

                    $option_value = substr( empty($POST_val[$POST_name]) ? $form_value['default'] : $POST_val[$POST_name] , 0, 2);
                    if(!empty($form_value['first'])) {
                        $form_field  .= '<option value="">' . html_specialchars($form_value['first']) . '</option>' . LF;
                    }
                    $form_field  .= list_country($option_value, $form_value['lang']);


                // build value/option select menu
                } else {

                    $form_value = explode("\n", $cnt_form["fields"][$key]['value']);
                    $form_value = array_map('trim', $form_value);
                    $form_value = array_diff($form_value, array(''));
                    if(count($form_value)) {
                        $form_optgroup = false;
                        foreach($form_value as $option_value) {

                            // search for OPTGROUP
                            if( strpos(strtoupper($option_value), 'OPTGROUP') === 0 ) {
                                $option_value = explode(' ', $option_value, 2);
                                if(isset($option_value[1]) ) {
                                    $option_value = trim($option_value[1]);
                                    $form_field .= '<optgroup label="';
                                    $form_field .= $option_value == '' ? 'Please select:' : html_specialchars($option_value);
                                    $form_field .= '">'.LF;
                                    $form_optgroup = true;
                                }
                                continue;
                            } elseif(strpos(strtoupper($option_value), '/OPTGROUP') === 0) {
                                if($form_optgroup == true) {
                                    $form_field .= '</optgroup>'.LF;
                                    $form_optgroup = false;
                                }
                                continue;
                            }

                            // check if select item has specila value and name
                            $option_value = explode('-|-', $option_value, 2);
                            $option_label = $option_value[0];
                            $option_value = isset($option_value[1]) ? $option_value[1] : $option_label;

                            if(substr($option_label, -2) === ' -') {
                                $option_label = trim( substr($option_label, 0, strlen($option_label) -2) );
                            }
                            $option_label = str_replace(' selected', '', $option_label);

                            if(isset($POST_val[$POST_name]) && $POST_val[$POST_name] == $option_value) {
                                $option_value .= ' selected';
                            }

                            $option_value = html_specialchars($option_value);
                            if(substr($option_value, -2) === ' -') {
                                $form_field .= '<option value=""';
                                $option_value = trim( substr($option_value, 0, strlen($option_value) -2) );
                            } elseif(strtolower(substr($option_value, -9)) != ' selected') {
                                $form_field .= '<option value="'.($cnt_form["fields"][$key]['type'] == 'selectemail' && $option_value ? base64_encode(phpwcms_encrypt($option_value)) : $option_value).'"';
                            } else {
                                $option_value = str_replace(' selected', '', $option_value);
                                $form_field .= '<option value="'.($cnt_form["fields"][$key]['type'] == 'selectemail' && $option_value ? base64_encode(phpwcms_encrypt($option_value)) : $option_value).'" selected="selected"';
                            }
                            $form_field .= '>'.html_specialchars($option_label)."</option>\n";
                        }
                        if($form_optgroup == true) {
                            $form_field .= '</optgroup>'.LF;
                        }
                    }

                }
                $form_field .= '</select>';
                break;

            case 'list':
                /*
                 * Liste
                 */
                if($POST_DO && isset($_POST[$POST_name])) {
                    if(is_array($_POST[$POST_name])) {
                        $POST_val[$POST_name] = array_map('combined_POST_cleaning', $_POST[$POST_name]);
                        $POST_val[$POST_name] = array_diff($POST_val[$POST_name], array(''));
                        if(!count($POST_val[$POST_name])) {
                            $POST_val[$POST_name] = false;
                        }
                    } else {
                        $POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
                    }
                    if($cnt_form["fields"][$key]['required'] && ($POST_val[$POST_name] === false || $POST_val[$POST_name] == '')) {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    } else {
                        $cnt_form["fields"][$key]['value'] = str_replace(' selected', '', $cnt_form["fields"][$key]['value']);
                    }
                }
                //
                $form_field .= '<select id="'.$form_name.'"';
                if($cnt_form["fields"][$key]['size']) {
                    $form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
                }
                if($cnt_form["fields"][$key]['max']) {
                    $form_field .= ' multiple';
                    $form_field .= ' name="'.$form_name.'[]"';
                } else {
                    $form_field .= ' name="'.$form_name.'"';
                }
                if($cnt_form["fields"][$key]['class']) {
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                }
                if($cnt_form["fields"][$key]['style']) {
                    $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                }
                if($cnt_form["fields"][$key]['required']) {
                    $form_field .= ' required="required"';
                }
                $form_field .= '>'.LF;
                $form_value = explode("\n", $cnt_form["fields"][$key]['value']);
                $form_value = array_map('trim', $form_value);
                $form_value = array_diff($form_value, array(''));
                if(count($form_value)) {
                    foreach($form_value as $option_value) {

                        // search for OPTGROUP
                        if( strpos(strtoupper($option_value), 'OPTGROUP') === 0 ) {
                            $option_value = explode(' ', $option_value, 2);
                            if(isset($option_value[1]) ) {
                                $option_value = trim($option_value[1]);
                                $form_field .= '<optgroup label="';
                                $form_field .= $option_value == '' ? 'Please select:' : html_specialchars($option_value);
                                $form_field .= '">'.LF;
                                $form_optgroup = true;
                            }
                            continue;
                        } elseif(strpos(strtoupper($option_value), '/OPTGROUP') === 0) {
                            if($form_optgroup == true) {
                                $form_field .= '</optgroup>'.LF;
                                $form_optgroup = false;
                            }
                            continue;
                        }


                        // try to set given POST var as selected
                        if(isset($POST_val[$POST_name])) {
                            if(is_array($POST_val[$POST_name])) {
                                foreach($POST_val[$POST_name] as $postvar_value) {
                                    if($postvar_value == $option_value) {
                                        $option_value .= ' selected';
                                    }
                                }
                            } elseif ($POST_val[$POST_name] == $option_value) {
                                $option_value .= ' selected';
                            }
                        }

                        $option_value = html_specialchars($option_value);
                        if(substr($option_value, -2) === ' -') {
                            $form_field .= '<option value=""';
                            $option_value = trim( substr($option_value, 0, strlen($option_value) -2) );
                        } elseif(substr($option_value, -9) != ' selected') {
                            $form_field .= '<option value="'.$option_value.'"';
                        } else {
                            $option_value = str_replace(' selected', '', $option_value);
                            $form_field .= '<option value="'.$option_value.'" selected="selected"';
                        }
                        $form_field .= '>'.$option_value."</option>\n";
                    }
                    if($form_optgroup == true) {
                        $form_field .= '</optgroup>'.LF;
                    }
                }
                $form_field .= '</select>';
                break;

            case 'checkboxcopy':
            case 'checkbox':
                /*
                 * Checkbox and Checkbox (send email copy on/off)
                 */
                if($cnt_form["fields"][$key]['type'] == 'checkboxcopy') {
                    $checkbox_copy                  = true;
                    $cnt_form['option_email_copy']  = false;
                } else {
                    $checkbox_copy                  = false;
                }

                if($POST_DO && ($cnt_form["fields"][$key]['required'] || isset($_POST[$POST_name]) ) ) {
                    if(isset($_POST[$POST_name]) && is_array($_POST[$POST_name])) {
                        $POST_val[$POST_name] = array_map('combined_POST_cleaning', $_POST[$POST_name]);
                        $POST_val[$POST_name] = array_diff($POST_val[$POST_name], array(''));
                        if(!count($POST_val[$POST_name])) {
                            $POST_val[$POST_name] = '';
                        }
                    } elseif(isset($_POST[$POST_name])) {
                        $POST_val[$POST_name] = remove_unsecure_rptags(clean_slweg($_POST[$POST_name]));
                        if($checkbox_copy) {
                            $cnt_form['option_email_copy'] = true;
                        }
                    } else {
                        $POST_val[$POST_name] = '';
                    }
                    if($cnt_form["fields"][$key]['required'] && ($POST_val[$POST_name] === false || $POST_val[$POST_name] == '')) {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    } else {
                        $cnt_form["fields"][$key]['value'] = str_replace(' checked', '', $cnt_form["fields"][$key]['value']);
                    }
                }
                //
                $form_value = explode("\n", $cnt_form["fields"][$key]['value']);
                $form_value = array_map('trim', $form_value);
                $form_value = array_diff($form_value, array(''));
                $checkbox_style = $cnt_form["fields"][$key]['style'] ? ' style="'.$cnt_form["fields"][$key]['style'].'"' : '';
                if (count($form_value) > 1) {
                    $form_value_single = false;
                    $form_value_inline = $cnt_form["fields"][$key]['size'] ? false : true;
                } else {
                    $form_value_single = true;
                    $form_value_inline = false;
                }

                if (substr($cnt_form["fields"][$key]['max'], 0, 1) === 'B') {
                    $form_bs = intval(substr($cnt_form["fields"][$key]['max'], -1));
                    $form_field_prefix = '<div class="'.trim('form-check '.$cnt_form["fields"][$key]['class']);
                    if ($form_value_inline) {
                        $form_field_prefix .= ' form-check-inline';
                    }
                } else {
                    $form_bs = 0;
                    $form_field_prefix = '<div class="'.trim('form-checkbox '.$cnt_form["fields"][$key]['class']);
                }
                $form_field_prefix .= '">';
                $form_field .= $form_field_prefix;

                if($checkbox_copy || $form_value_single || !$form_value) {
                    // only 1 checkbox
                    $checkbox_value = is_array($form_value) ? implode('', $form_value) : $form_value;
                    $checkbox_value = trim($checkbox_value);
                    $checkbox_value = explode('-|-', $checkbox_value, 2);
                    $checkbox_label = $checkbox_value[0];
                    $checkbox_value = isset($checkbox_value[1]) ? $checkbox_value[1] : $checkbox_label;
                    $checkbox_label = str_replace(' checked', '', $checkbox_label);
                    if(isset($POST_val[$POST_name]) && $POST_val[$POST_name] == ($checkbox_value ? $checkbox_value : $form_name)) {
                        $checkbox_value .= ' checked';
                    }
                    $checkbox_value = $checkbox_value ? html_specialchars($checkbox_value) : $form_name;
                    if ($form_bs < 4) {
                        $form_field .= '<label for="' . $form_name . '"' . $checkbox_style . '>';
                    }
                    $form_field .= '<input type="checkbox" name="'.$form_name.'" id="'.$form_name.'" ';
                    if ($form_bs > 3) {
                        $form_field .= ' class="form-check-input" ';
                    }
                    if(substr($checkbox_value, -8) != ' checked') {
                        $form_field .= 'value="' . $checkbox_value . '" ';
                    } else {
                        $checkbox_value = str_replace(' checked', '', $checkbox_value);
                        $form_field .= 'value="' . $checkbox_value . '" checked="checked" ';
                    }
                    if($cnt_form["fields"][$key]['required']) {
                        $form_field .= 'required="required"';
                    }
                    $form_field .= HTML_TAG_CLOSE . ' ';
                    if ($form_bs > 3) {
                        $form_field .= '<label for="' . $form_name . '" class="form-check-label">';
                    }
                    $form_field .= $checkbox_label . '</label>';

                } else {
                    // list of checkboxes
                    $checkbox_counter = 0;
                    foreach($form_value as $checkbox_value) {
                        $checkbox_value = explode('-|-', $checkbox_value, 2);
                        $checkbox_label = $checkbox_value[0];
                        $checkbox_value = isset($checkbox_value[1]) ? $checkbox_value[1] : $checkbox_label;
                        $checkbox_label = str_replace(' checked', '', $checkbox_label);
                        if(isset($POST_val[$POST_name]) && is_array($POST_val[$POST_name])) {
                            foreach($POST_val[$POST_name] as $postvar_value) {
                                if($postvar_value == $checkbox_value) {
                                    $checkbox_value .= ' checked';
                                }
                            }
                        }
                        $checkbox_value = html_specialchars(trim($checkbox_value));

                        if ($checkbox_counter && ($form_bs > 3 || !$form_value_inline)) {
                            $form_field .= '</div>' . $form_field_prefix;
                        }

                        if ($form_bs < 4) {
                            $form_field .= '<label for="' . $form_name . $checkbox_counter . '"' . $checkbox_style;
                            if ($form_value_inline) {
                                $form_field .= ' class="checkbox-inline"';
                            }
                            $form_field .= '>';
                        }
                        $form_field .= '<input type="checkbox" name="' . $form_name .'[]" id="' . $form_name . $checkbox_counter.'" ';
                        if ($form_bs > 3) {
                            $form_field .= ' class="form-check-input" ';
                        }
                        if(substr($checkbox_value, -8) !== ' checked') {
                            $form_field .= 'value="' . $checkbox_value . '"';
                        } else {
                            $checkbox_value = str_replace(' checked', '', $checkbox_value);
                            $form_field .= 'value="' . $checkbox_value . '" checked="checked"';
                        }
                        $form_field .= HTML_TAG_CLOSE . ' ';
                        if ($form_bs > 3) {
                            $form_field .= '<label for="' . $form_name . $checkbox_counter . '" class="form-check-label">';
                        }
                        $form_field .= $checkbox_label .'</label>';
                        $checkbox_counter++;
                    }
                }
                $form_field .= '</div>';
                break;

            case 'radio':
                /*
                 * Radiobutton
                 */
                if($POST_DO && ( $cnt_form["fields"][$key]['required'] || isset($_POST[$POST_name]) ) ) {
                    $POST_val[$POST_name] = isset($_POST[$POST_name]) ? remove_unsecure_rptags(clean_slweg($_POST[$POST_name])) : false;
                    if($cnt_form["fields"][$key]['required'] && ($POST_val[$POST_name] === false || $POST_val[$POST_name] == '')) {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    } else {
                        $cnt_form["fields"][$key]['value'] = str_replace(' checked', '', $cnt_form["fields"][$key]['value']);
                    }
                }
                //
                $form_value = explode("\n", $cnt_form["fields"][$key]['value']);
                $form_value = array_map('trim', $form_value);
                $form_value = array_diff($form_value, array(''));
                $checkbox_style = $cnt_form["fields"][$key]['style'] ? ' style="'.$cnt_form["fields"][$key]['style'].'"' : '';
                if (count($form_value) > 1) {
                    $form_value_single = false;
                    $form_value_inline = $cnt_form["fields"][$key]['size'] ? false : true;
                } else {
                    $form_value_single = true;
                    $form_value_inline = false;
                }

                if (substr($cnt_form["fields"][$key]['max'], 0, 1) === 'B') {
                    $form_bs = intval(substr($cnt_form["fields"][$key]['max'], -1));
                    $form_field_prefix = '<div class="'.trim('form-check '.$cnt_form["fields"][$key]['class']);
                    if ($form_value_inline) {
                        $form_field_prefix .= ' form-check-inline';
                    }
                } else {
                    $form_bs = 0;
                    $form_field_prefix = '<div class="'.trim('form-radiobutton '.$cnt_form["fields"][$key]['class']);
                }
                $form_field_prefix .= '">';
                $form_field .= $form_field_prefix;

                if($form_value_single || !$form_value) {
                    // only 1 radio button
                    $checkbox_value = is_array($form_value) ? implode('', $form_value) : $form_value;
                    $checkbox_value = trim($checkbox_value);
                    $checkbox_value = explode('-|-', $checkbox_value, 2);
                    $checkbox_label = $checkbox_value[0];
                    $checkbox_value = isset($checkbox_value[1]) ? $checkbox_value[1] : $checkbox_label;
                    $checkbox_label = str_replace(' checked', '', $checkbox_label);

                    if(isset($POST_val[$POST_name]) && $POST_val[$POST_name] == ($checkbox_value ? $checkbox_value : $form_name)) {
                        $checkbox_value .= ' checked';
                    }
                    $checkbox_value = $checkbox_value ? html_specialchars($checkbox_value) : $form_name;
                    if ($form_bs < 4) {
                        $form_field .= '<label for="' . $form_name . '"' . $checkbox_style . '>';
                    }
                    $form_field .= '<input type="radio" name="'.$form_name.'" id="'.$form_name.'" ';
                    if ($form_bs > 3) {
                        $form_field .= ' class="form-check-input" ';
                    }
                    if(substr($checkbox_value, -8) != ' checked') {
                        $form_field .= 'value="' . $checkbox_value . '" ';
                    } else {
                        $checkbox_value = str_replace(' checked', '', $checkbox_value);
                        $form_field .= 'value="' . $checkbox_value . '" checked="checked" ';
                    }
                    if($cnt_form["fields"][$key]['required']) {
                        $form_field .= 'required="required"';
                    }
                    $form_field .= HTML_TAG_CLOSE . ' ';
                    if ($form_bs > 3) {
                        $form_field .= '<label for="' . $form_name . '" class="form-check-label">';
                    }
                    $form_field .= $checkbox_label .'</label>';

                } else {
                    // list of radio buttons
                    $checkbox_counter = 0;
                    foreach($form_value as $checkbox_value) {

                        $checkbox_value = explode('-|-', $checkbox_value, 2);
                        $checkbox_label = $checkbox_value[0];
                        $checkbox_value = isset($checkbox_value[1]) ? $checkbox_value[1] : $checkbox_label;
                        $checkbox_label = str_replace(' checked', '', $checkbox_label);
                        if(isset($POST_val[$POST_name]) && $POST_val[$POST_name] == $checkbox_value) {
                            $checkbox_value .= ' checked';
                        }
                        $checkbox_value = html_specialchars(trim($checkbox_value));

                        if ($checkbox_counter && ($form_bs > 3 || !$form_value_inline)) {
                            $form_field .= '</div>' . $form_field_prefix;
                        }

                        if ($form_bs < 4) {
                            $form_field .= '<label for="' . $form_name . $checkbox_counter . '"' . $checkbox_style;
                            if ($form_value_inline) {
                                $form_field .= ' class="radio-inline"';
                            }
                            $form_field .= '>';
                        }
                        $form_field .= '<input type="radio" name="'.$form_name.'" id="'.$form_name.$checkbox_counter.'" ';
                        if ($form_bs > 3) {
                            $form_field .= ' class="form-check-input" ';
                        }
                        if(substr($checkbox_value, -8) !== ' checked') {
                            $form_field .= 'value="' . $checkbox_value . '" ';
                        } else {
                            $checkbox_value = str_replace(' checked', '', $checkbox_value);
                            $form_field .= 'value="' . $checkbox_value . '" checked="checked" ';
                        }
                        if ($checkbox_counter === 0 && $cnt_form["fields"][$key]['required']) {
                            $form_field .= 'required="required"';
                        }
                        $form_field .= HTML_TAG_CLOSE . ' ';
                        if ($form_bs > 3) {
                            $form_field .= '<label for="' . $form_name . $checkbox_counter . '" class="form-check-label">';
                        }
                        $form_field .= $checkbox_label .'</label>';
                        $checkbox_counter++;
                    }
                }
                $form_field .= $checkbox_class;
                break;

            case 'upload':
                /*
                 * Upload
                 */
                if($cnt_form["fields"][$key]['value']) {
                    $cnt_form['upload_value'] = str_replace("\r'", '', $cnt_form["fields"][$key]['value']);
                    $cnt_form['upload_value'] = explode("\n", $cnt_form['upload_value']);
                    if(is_array($cnt_form['upload_value']) && count($cnt_form['upload_value'])) {
                        foreach($cnt_form['upload_value'] as $cnt_form['upload_key'] => $cnt_form['upload_val']) {
                            $temp_array = explode('=', $cnt_form['upload_val']);
                            $temp_array[0] = trim($temp_array[0]);
                            $temp_array[1] = isset($temp_array[1]) ? trim(trim(trim($temp_array[1]), "\"' ")) : '';
                            unset($cnt_form['upload_value'][$cnt_form['upload_key']]);
                            if(!empty($temp_array[0]) && !empty($temp_array[1])) {
                                $cnt_form['upload_value'][$temp_array[0]] = $temp_array[1];
                            }
                        }
                    }
                }
                if(empty($cnt_form['upload_value']['folder'])) {
                    $cnt_form['upload_value']['folder'] = 'content/form';
                } else {
                    $cnt_form['upload_value']['folder'] = trim($cnt_form['upload_value']['folder'], '/');
                }
                if(empty($cnt_form['upload_value']['attachment'])) {
                    $cnt_form['upload_value']['attachment'] = 0;
                }
                if(empty($cnt_form['upload_value']['exclude'])) {
                    $cnt_form['upload_value']['exclude'] = 'php,asp,php3,php4,php5,aspx,cfm,js,exe,com,bat,app,sh,jar,java';
                }

                if($POST_DO && isset($_FILES[$POST_name])) {
                    $POST_val[$POST_name]['folder'] = $cnt_form['upload_value']['folder'];
                    $POST_val[$POST_name]['attachment'] = $cnt_form['upload_value']['attachment'];
                    $POST_val[$POST_name]['name'] = '';
                    if(!empty($cnt_form['upload_value']['accept'])) {
                        $cnt_form['upload_value']['accept'] = convertStringToArray($cnt_form['upload_value']['accept'], ',');
                        if(count($cnt_form['upload_value']['accept'])) {
                            $cnt_form['upload_value']['accept'] = implode('|', $cnt_form['upload_value']['accept']);
                            $cnt_form['upload_value']['regexp'] = '/(\.'.$cnt_form['upload_value']['accept'].')$/';
                        } else {
                            $cnt_form['upload_value']['accept'] = '';
                        }
                    }
                    if(empty($cnt_form['upload_value']['accept'])) {
                        $cnt_form['upload_value']['exclude'] = convertStringToArray($cnt_form['upload_value']['exclude'], ',');
                        $cnt_form['upload_value']['exclude'] = implode('|', $cnt_form['upload_value']['exclude']);
                        $cnt_form['upload_value']['regexp'] = '/(\.'.$cnt_form['upload_value']['exclude'].')$/';
                    } else {
                        $cnt_form['upload_value']['exclude'] = '';
                    }
                    if($cnt_form["fields"][$key]['required'] && empty($_FILES[$POST_name]['name'])) {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                        $POST_ERR[$key] = str_replace('{MAXLENGTH}', '', $POST_ERR[$key]);
                        $POST_ERR[$key] = str_replace('{FILESIZE}', fsize(0, ' '), $POST_ERR[$key]);
                        $POST_ERR[$key] = str_replace('{FILENAME}', '"n.a."', $POST_ERR[$key]);
                        $POST_ERR[$key] = str_replace('{FILEEXT}', '"n.a."', $POST_ERR[$key]);
                    } elseif(!empty($_FILES[$POST_name]['name'])) {
                        $cnt_form['upload_value']['filename'] = time().'_'.sanitize_filename($_FILES[$POST_name]['name']);
                        if(
                            (!empty($cnt_form['upload_value']['maxlength']) && $_FILES[$POST_name]['size'] > intval($cnt_form['upload_value']['maxlength']))
                          ||
                            (!empty($cnt_form['upload_value']['exclude']) && preg_match($cnt_form['upload_value']['regexp'], strtolower($_FILES[$POST_name]['name'])))
                          ||
                            (!empty($cnt_form['upload_value']['accept']) && !preg_match($cnt_form['upload_value']['regexp'], strtolower($_FILES[$POST_name]['name'])))
                          ||
                            !@move_uploaded_file(
                                $_FILES[$POST_name]['tmp_name'],
                                PHPWCMS_ROOT.'/'.$cnt_form['upload_value']['folder'].'/'.$cnt_form['upload_value']['filename']
                            )
                        ) {

                           $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                           $POST_ERR[$key] = str_replace('{MAXLENGTH}', empty($cnt_form['upload_value']['maxlength']) ? '' : fsize($cnt_form['upload_value']['maxlength'], ' '), $POST_ERR[$key]);
                           $POST_ERR[$key] = str_replace('{FILESIZE}', fsize(empty($_FILES[$POST_name]['size']) ? 0 : $_FILES[$POST_name]['size'], ' '), $POST_ERR[$key]);
                           $POST_ERR[$key] = str_replace('{FILENAME}', empty($_FILES[$POST_name]['name']) || trim($_FILES[$POST_name]['name'])=='' ? '"n.a."' : $_FILES[$POST_name]['name'], $POST_ERR[$key]);
                           $POST_ERR[$key] = str_replace('{FILEEXT}', '.'.str_replace('|', ', .', str_replace(',', ', .', $cnt_form['upload_value']['exclude'])), $POST_ERR[$key]);

                        } else {

                            $POST_val[$POST_name]['name'] = $cnt_form['upload_value']['filename'];
                            @chmod(PHPWCMS_ROOT.'/'.$cnt_form['upload_value']['folder'].'/'.$cnt_form['upload_value']['filename'], 0644);

                        }
                    }
                    if(isset($POST_ERR[$key])) {
                        @unlink($_FILES[$POST_name]['tmp_name']);
                        @unlink(PHPWCMS_ROOT.'/'.$cnt_form['upload_value']['folder'].'/'.$cnt_form['upload_value']['filename']);
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    }
                }

                $form_field .= '<input type="file" name="'.$form_name.'" id="'.$form_name.'"';
                if(!empty($cnt_form['upload_value']['accept']) ) {
                    $form_field .= ' accept=".'.str_replace('|', ',.', $cnt_form['upload_value']['accept']).'"';
                }
                if($cnt_form["fields"][$key]['size']) {
                    $form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
                }
                if($cnt_form["fields"][$key]['max']) {
                    $form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
                    $form_field .= ' title="max. '.fsize($cnt_form["fields"][$key]['max'],' ',1).'"';
                } elseif (!empty($cnt_form['upload_value']['maxlength'])) {
                    $form_field .= ' maxlength="'.$cnt_form['upload_value']['maxlength'].'"';
                    $form_field .= ' title="max. '.fsize($cnt_form['upload_value']['maxlength'],' ',1).'"';
                }
                if($cnt_form["fields"][$key]['class']) {
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                }
                if($cnt_form["fields"][$key]['style']) {
                    $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                }
                $form_field .= ' />';
                unset($cnt_form['upload_value']);

                // enable enctype attribute
                $cnt_form['is_enctype'] = true;
                break;

            case 'submit':
                /*
                 * Submit
                 */
                $cnt_form["fields"][$key]['class'] = trim('phpwcms-recaptcha-class '.$cnt_form["fields"][$key]['class']);
                $cnt_form["fields"][$key]['recaptchainv'] = ' data-recaptchainv-submit';

                if(strpos(strtolower($cnt_form["fields"][$key]['value']), 'src=') === false) {
                    $form_field .= '<button type="submit" name="'.$form_name.'" id="'.$form_name.'" ';
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                    if($cnt_form["fields"][$key]['style']) {
                        $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                    }
                    $form_field .= $cnt_form["fields"][$key]['recaptchainv'].'>';
                    $form_field .= ($cnt_form["fields"][$key]['value'] != '') ? html($cnt_form["fields"][$key]['value']) : '@@Submit@@';
                    $form_field .= '</button>###RESET###';
                } else {
                    $form_field .= '<input type="image" name="'.$form_name.'" id="'.$form_name.'" ';
                    $form_field .= $cnt_form["fields"][$key]['value'];
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                    if($cnt_form["fields"][$key]['style']) {
                        $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                    }
                    $form_field .= $cnt_form["fields"][$key]['recaptchainv'].' />###RESET###';
                }
                break;

            case 'reset':
                /*
                 * Reset
                 */
                if(strpos(strtolower($cnt_form["fields"][$key]['value']), 'src=') === false) {
                    $form_field .= '<button type="reset" name="'.$form_name.'" id="'.$form_name.'" ';
                    if($cnt_form["fields"][$key]['class']) {
                        $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                    }
                    if($cnt_form["fields"][$key]['style']) {
                        $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                    }
                    $form_field .= '>';
                    $form_field .= ($cnt_form["fields"][$key]['value'] != '') ? html($cnt_form["fields"][$key]['value']) : '@@Reset@@';
                    $form_field .= '</button>';
                } else {
                    $form_field .= '<img name="'.$form_name.'" id="'.$form_name.'" ';
                    $form_field .= $cnt_form["fields"][$key]['value'];
                    if($cnt_form["fields"][$key]['class']) {
                        $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                    }
                    if($cnt_form["fields"][$key]['style']) {
                        $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                    }
                    $form_field .= ' onclick="document.phpwcmsForm'.$crow["acontent_id"].'.reset();" />';
                }
                break;

            case 'break':
                /*
                 * Break
                 */
                if($cnt_form["fields"][$key]['style'] || $cnt_form["fields"][$key]['class']) {
                    $form_field .= '<div id="'.$form_name.'"';
                    if($cnt_form["fields"][$key]['class']) {
                        $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                    }
                    if($cnt_form["fields"][$key]['style']) {
                        $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                    }
                    $form_field .= '>';
                    $form_field .= $cnt_form["fields"][$key]['value'];
                    $form_field .= '</div>';
                } else {
                    $form_field .= $cnt_form["fields"][$key]['value'];
                }
                break;

            case 'breaktext':
                /*
                 * Breaktext
                 */
                if($cnt_form["fields"][$key]['style'] || $cnt_form["fields"][$key]['class']) {
                    $form_field .= '<div id="'.$form_name.'"';
                    if($cnt_form["fields"][$key]['class']) {
                        $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                    }
                    if($cnt_form["fields"][$key]['style']) {
                        $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                    }
                    $form_field .= '>';
                    $form_field .= plaintext_htmlencode($cnt_form["fields"][$key]['value']);
                    $form_field .= '</div>';
                } else {
                    $form_field .= plaintext_htmlencode($cnt_form["fields"][$key]['value']);
                }
                break;

            case 'captchaimg':
                /*
                 * Captcha Images
                 */
                if(empty($cnt_form["fields"][$key]['value']) && ($cnt_form["fields"][$key]['style'] || $cnt_form["fields"][$key]['class'])) {
                    $form_field .= '<div id="'.$form_name.'"';
                    if($cnt_form["fields"][$key]['class']) {
                        $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                    }
                    if($cnt_form["fields"][$key]['style']) {
                        $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                    }
                    $form_field .= '>{CAPTCHA}</div>';
                } elseif(!empty($cnt_form["fields"][$key]['value'])) {
                    $form_field .= $cnt_form["fields"][$key]['value'];
                } else {
                    $form_field .= '{CAPTCHA}';
                }
                $form_field = str_replace('{CAPTCHA}', '<img src="img/captcha.php?regen=y&amp;'.time().'" alt="Captcha" />', $form_field);
                break;

            case 'mathspam':
                /*
                 * Math Spam Protect
                 */
                if($POST_DO) {

                    $POST_val[$POST_name] = isset($_POST[$POST_name]) && trim(is_numeric($_POST[$POST_name])) ? intval($_POST[$POST_name]) : -1;

                    $mathspam_result  = $POST_val[$POST_name] * 123345 * strlen($phpwcms['db_user']);
                    $mathspam_result  = md5( PHPWCMS_URL . md5($phpwcms['db_pass']) . $mathspam_result );

                    $mathspam_default = isset($_POST[$POST_name.'_result']) ? trim($_POST[$POST_name.'_result']) : '';

                    if($mathspam_result != $mathspam_default  || ($cnt_form["fields"][$key]['required'] && ($POST_val[$POST_name] === false || $POST_val[$POST_name] === ''))) {
                        $POST_ERR[$key] = empty($cnt_form["fields"][$key]['error']) ? 'Math spam protection error' : $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    }
                }

                $form_field .= '<input type="text" name="'.$form_name.'" id="'.$form_name.'" value=""';
                if($cnt_form["fields"][$key]['size']) {
                    $form_field .= ' size="'.$cnt_form["fields"][$key]['size'].'"';
                }
                if($cnt_form["fields"][$key]['max']) {
                    $form_field .= ' maxlength="'.$cnt_form["fields"][$key]['max'].'"';
                }
                if($cnt_form["fields"][$key]['class']) {
                    $form_field .= ' class="'.$cnt_form["fields"][$key]['class'].'"';
                }
                if($cnt_form["fields"][$key]['style']) {
                    $form_field .= ' style="'.$cnt_form["fields"][$key]['style'].'"';
                }
                if(!empty($cnt_form["fields"][$key]['placeholder'])) {
                    $form_field .= ' placeholder="'.html_specialchars($cnt_form["fields"][$key]['placeholder']).'"';
                }
                if($cnt_form["fields"][$key]['required']) {
                    $form_field .= ' required="required"';
                }
                $form_field .= ' />';

                // calculate the result and the question
                $mathspam_calculations   = array('+'=>'+', '-'=>'-', '*'=>'*', '/'=>':');
                $mathspam_operation      = array_rand($mathspam_calculations, 1);
                $mathspam_operator       = $mathspam_calculations[ $mathspam_operation ];
                $mathspam_number_1       = rand( $mathspam_operation === '/' ? 1 : 0 , 10);

                // fix divisions to avoid fractional results
                if($mathspam_operation === '/') {

                    switch($mathspam_number_1) {

                        case 1:
                            $mathspam_number_2 = 1;
                            break;

                        case 2:
                            $mathspam_number_2 = array_rand( array(1=>1, 2=>2), 1);
                            break;

                        case 3:
                            $mathspam_number_2 = array_rand( array(1=>1, 3=>3), 1);
                            break;

                        case 4:
                            $mathspam_number_2 = array_rand( array(1=>1, 2=>2, 4=>4), 1);
                            break;

                        case 5:
                            $mathspam_number_2 = array_rand( array(1=>1, 5=>5), 1);
                            break;

                        case 6:
                            $mathspam_number_2 = array_rand( array(1=>1, 2=>2, 3=>3, 6=>6), 1);
                            break;

                        case 7:
                            $mathspam_number_2 = array_rand( array(1=>1, 7=>7), 1);
                            break;

                        case 8:
                            $mathspam_number_2 = array_rand( array(1=>1, 2=>2, 4=>4, 8=>8), 1);
                            break;

                        case 9:
                            $mathspam_number_2 = array_rand( array(1=>1, 3=>3, 9=>9), 1);
                            break;

                        case 10:
                            $mathspam_number_2 = array_rand( array(1=>1, 2=>2, 5=>5, 10=>10), 1);
                            break;

                    }

                // avoid subtraction with results < 0
                } elseif($mathspam_operation === '-') {

                    $mathspam_number_2 = rand(0, $mathspam_number_1);

                } else {

                    $mathspam_number_2 = rand(0, 10);

                }

                $mathspam_question  = $cnt_form["fields"][$key]['value'][ $mathspam_operator ];
                $mathspam_question .= ' <span class="calc">' . $mathspam_number_1 . '&nbsp;';
                $mathspam_question .= html_specialchars( $mathspam_operator );
                $mathspam_question .= '&nbsp;' . $mathspam_number_2 . '</span>';

                switch($mathspam_operation) {

                    case '+': $mathspam_result = $mathspam_number_1 + $mathspam_number_2; break;
                    case '-': $mathspam_result = $mathspam_number_1 - $mathspam_number_2; break;
                    case '/': $mathspam_result = $mathspam_number_1 / $mathspam_number_2; break;
                    case '*': $mathspam_result = $mathspam_number_1 * $mathspam_number_2; break;

                }
                $mathspam_result = intval($mathspam_result) * 123345 * strlen($phpwcms['db_user']);
                $mathspam_result = md5( PHPWCMS_URL . md5($phpwcms['db_pass']) . $mathspam_result );

                // hidden field, contains the hashed result
                $form_field .= '<input type="hidden" name="'.$form_name.'_result" value="'.$mathspam_result.'" />';
                $form_field .= ' <span class="mathspam">';
                $form_field .= trim( $cnt_form["fields"][$key]['value']['calc'] . ' ' . trim( $mathspam_question ) );
                $form_field .= '</span>';
                break;

            case 'newsletter':
                /*
                 * Newsletter
                 */

                $form_newletter_setting                 = array();
                $form_newletter_setting['double_optin'] = 0;
                $form_value                             = array();

                if($POST_DO && ($cnt_form["fields"][$key]['required'] || isset($_POST[$POST_name]) ) ) {
                    if(isset($_POST[$POST_name]) && is_array($_POST[$POST_name])) {
                        $POST_val[$POST_name] = array_map('combined_POST_cleaning', $_POST[$POST_name]);
                        $POST_val[$POST_name] = array_diff($POST_val[$POST_name], array(''));
                        if(!count($POST_val[$POST_name])) {
                            $POST_val[$POST_name] = false;
                        }
                    } else {
                        $POST_val[$POST_name] = isset($_POST[$POST_name]) ? remove_unsecure_rptags(clean_slweg($_POST[$POST_name])) : false;
                    }
                    if($cnt_form["fields"][$key]['required'] && ($POST_val[$POST_name] === false || $POST_val[$POST_name] == '')) {
                        $POST_ERR[$key] = $cnt_form["fields"][$key]['error'];
                        $cnt_form["fields"][$key]['class'] = getFieldErrorClass($value['class'], $cnt_form["error_class"]);
                    } else {
                        $cnt_form["fields"][$key]['value'] = str_replace(' checked', '', $cnt_form["fields"][$key]['value']);
                    }

                    if(isset($POST_val[$POST_name])) {
                        $form_newletter_setting['selection'] = $POST_val[$POST_name];
                    } else {
                        $form_newletter_setting['selection'] = false;
                    }

                }
                // prepare default settings for newsletter field
                $form_value_default     = convertStringToArray($cnt_form["fields"][$key]['value'], "\n", 'UNIQUE', false);
                foreach($form_value_default as $form_value_nl) {

                    $form_value_nl      = explode('=', $form_value_nl, 2);
                    $form_value_nl[0]   = trim($form_value_nl[0]);
                    $form_value_nl[1]   = empty($form_value_nl[1]) ? '' : trim($form_value_nl[1]);

                    if(empty($form_value_nl[0]) || empty($form_value_nl[1])) {
                        continue;
                    }

                    switch($form_value_nl[0]) {

                        case 'all':
                            $form_value[0] = $form_value_nl[1];
                            break;

                        case 'email_field':
                            $form_newletter_setting['email_field'] = $form_value_nl[1];
                            break;

                        case 'name_field':
                            $form_newletter_setting['name_field'] = $form_value_nl[1];
                            break;

                        case 'sender_email':
                            $form_newletter_setting['sender_email'] = $form_value_nl[1];
                            break;

                        case 'sender_name':
                            $form_newletter_setting['sender_name'] = $form_value_nl[1];
                            break;

                        case 'url_subscribe':
                            $form_newletter_setting['url_subscribe'] = $form_value_nl[1];
                            break;

                        case 'url_unsubscribe':
                            $form_newletter_setting['url_unsubscribe'] = $form_value_nl[1];
                            break;

                        case 'subject':
                            $form_newletter_setting['subject'] = $form_value_nl[1];
                            break;

                        case 'double_optin':
                            $form_newletter_setting['double_optin'] = intval($form_value_nl[1]) ? 1 : 0;
                            break;

                        case 'optin_template':
                            $form_newletter_setting['optin_template'] = $form_value_nl[1];
                            break;

                        default:
                            if($form_value_nl[0] = intval($form_value_nl[0])) {
                                $query = _dbGet('phpwcms_subscription', '*', 'subscription_id='.$form_value_nl[0].' AND subscription_active=1');
                                if(isset($query[0])) {
                                    if($form_value_nl[1] === '') {
                                        $form_value_nl[1] = $query[0]['subscription_name'];
                                    }
                                    $form_value[$form_value_nl[0]] = $form_value_nl[1];
                                }
                            }
                    }
                }

                $form_newletter_setting['subscriptions'] = $form_value;

                if($cnt_form["fields"][$key]['class']) {
                    $form_field     .= '<div class="'.$cnt_form["fields"][$key]['class'].'">';
                    $checkbox_class  = '</div>';
                } else {
                    $checkbox_class  = '';
                }
                if($cnt_form["fields"][$key]['style']) {
                    $checkbox_style = ' style="'.$cnt_form["fields"][$key]['style'].'"';
                } else {
                    $checkbox_style = '';
                }
                // list of checkboxes
                $checkbox_counter = 0;
                $checkbox_spacer  = $cnt_form["fields"][$key]['size'] ? '<br />' : ' ';
                foreach($form_value as $checkbox_key => $checkbox_value) {

                    if(isset($POST_val[$POST_name]) && is_array($POST_val[$POST_name])) {
                        foreach($POST_val[$POST_name] as $postvar_value) {
                            if($postvar_value == $checkbox_key) {
                                $checkbox_key .= ' checked';
                            }
                        }
                    }

                    if($checkbox_counter) {
                        $form_field .= $checkbox_spacer;
                    }
                    $form_field .= '<label for="'.$form_name.$checkbox_counter.'"' . $checkbox_style . '>';
                    $form_field .= '<input type="checkbox" name="'.$form_name.'[]" id="'.$form_name.$checkbox_counter.'" ';
                    if(substr($checkbox_key, -8) != ' checked' && substr($checkbox_value, -8) != ' checked') {
                        $form_field .= 'value="' . $checkbox_key . '" />';
                    } else {
                        $checkbox_key   = str_replace(' checked', '', $checkbox_key);
                        $checkbox_value = str_replace(' checked', '', $checkbox_value);
                        $form_field    .= 'value="' . $checkbox_key . '" checked="checked" />';
                    }
                    $form_field .= $checkbox_value .'</label>';
                    $checkbox_counter++;
                }
                $form_field .= $checkbox_class;
                break;

        }

        // try to find correct sender name
        if($POST_DO && $cnt_form['sendernametype'] == 'formfield_'.$POST_name) {

            $cnt_form['sendername'] = cleanUpForEmailHeader($cnt_form["fields"][$key]['value']);

        }
        // try to build correct subject
        if($POST_DO && isset($cnt_form['subjectselect']) && $cnt_form['subjectselect'] == 'formfield_'.$POST_name) {

            $cnt_form['subject'] .= ' '.cleanUpForEmailHeader($POST_val[$POST_name]);
            $cnt_form['subject']  = trim($cnt_form['subject']);

        }

        // Build the form elements
        if($form_field && $cnt_form["fields"][$key]['type'] !== 'hidden') {

            if($cnt_form['labelpos'] == 2) {

                // custom form template
                $POST_name_quoted = preg_quote($POST_name, '/');

                if(isset($POST_ERR[$key])) {
                    // field error
                    $form_cnt = preg_replace('/\[IF_ERROR:'.$POST_name_quoted.'\](.*?)\[\/IF_ERROR\]/s', '$1', $form_cnt);
                    $form_cnt = preg_replace('/\[ELSE_ERROR:'.$POST_name_quoted.'\].*?\[\/ELSE_ERROR\]/s', '', $form_cnt);
                    $form_cnt = str_replace('{ERROR:'.$POST_name.'}', html_specialchars($POST_ERR[$key]), $form_cnt);
                } else {
                    // no field error
                    $form_cnt = preg_replace('/\[IF_ERROR:'.$POST_name_quoted.'\].*?\[\/IF_ERROR\]/s', '', $form_cnt);
                    $form_cnt = preg_replace('/\[ELSE_ERROR:'.$POST_name_quoted.'\](.*?)\[\/ELSE_ERROR\]/s', '$1', $form_cnt);
                    $form_cnt = str_replace('{ERROR:'.$POST_name.'}', '', $form_cnt);
                }

                $form_cnt = str_replace('{'.$POST_name.'}', $form_field, $form_cnt);
                $form_cnt = str_replace('{LABEL:'.$POST_name.'}', html_specialchars($cnt_form["fields"][$key]['label']), $form_cnt);

            } elseif($cnt_form["fields"][$key]['type'] === 'reset' && strpos($form_cnt, '###RESET###')) { // default table

                $form_cnt = str_replace('###RESET###', $form_field, $form_cnt);

            } else {

                if($cnt_form["fields"][$key]['required']) {
                    $cnt_form['labelReqMark']  = $cnt_form["cform_reqmark"];
                    $cnt_form['requiredClass'] = ' required';
                } else {
                    $cnt_form['labelReqMark']  = '';
                    $cnt_form['requiredClass'] = '';
                }

                $cnt_form['typeClass'] = 'form-type-'.$cnt_form["fields"][$key]['type'];

                if($cnt_form["fields"][$key]['class']) {
                    $cnt_form['typeClass'] .= ' ftc-'.$cnt_form["fields"][$key]['class'];
                }

                if($cnt_form['labelpos'] == 0) {

                    // label: field
                    if($cnt_form["fields"][$key]['type'] != 'break') {
                        $form_cnt .= '<tr class="'.$cnt_form['typeClass'].$cnt_form['requiredClass'].'">'.'<td class="form-label'.$cnt_form['requiredClass'].'">';
                        if($cnt_form["fields"][$key]['label'] != '') {
                            $form_cnt .= $cnt_form['label_wrap'][0];
                            $form_cnt .= html_specialchars($cnt_form["fields"][$key]['label']);
                            $form_cnt .= $cnt_form['labelReqMark'];
                            $form_cnt .= $cnt_form['label_wrap'][1];
                        } else {
                            $form_cnt .= '&nbsp;';
                        }
                        $form_cnt .= "</td>\n";
                        $form_cnt .= '<td class="form-field">'.$form_field."</td>\n</tr>\n";
                    } else {
                        // colspan for break
                        $form_cnt .= '<tr><td colspan="2">'.$form_field."</td></tr>\n";
                    }

                } elseif($cnt_form['labelpos'] == 3) {

                    // DIV based
                    $form_cnt .= '<div class="'.$cnt_form['typeClass'].' form-field'.$cnt_form['requiredClass'];
                    if($cnt_form["fields"][$key]['label'] !== '') {
                        $form_cnt .= '">' . LF . '  <label class="form-label'.$cnt_form['requiredClass'].'">';
                        $form_cnt .= $cnt_form['label_wrap'][0];
                        $form_cnt .= html_specialchars($cnt_form["fields"][$key]['label']);
                        $form_cnt .= $cnt_form['labelReqMark'];
                        $form_cnt .= $cnt_form['label_wrap'][1];
                        $form_cnt .= '</label>';
                    } else {
                        $form_cnt .= ' no-label">';
                    }
                    $form_cnt .= LF . ' ' . $form_field . LF . '</div>' . LF;

                } else {

                    // label:field
                    if($cnt_form["fields"][$key]['label'] !== '') {
                        $form_cnt .= '<tr class="'.$cnt_form['typeClass'].$cnt_form['requiredClass'].'"><td class="form-label'.$cnt_form['requiredClass'].'">'.$cnt_form['label_wrap'][0];
                        $form_cnt .= html_specialchars($cnt_form["fields"][$key]['label']);
                        $form_cnt .= $cnt_form['labelReqMark'];
                        $form_cnt .= $cnt_form['label_wrap'][1]."</td></tr>\n";
                    }
                    $form_cnt .= '<tr class="'.$cnt_form['typeClass'].$cnt_form['requiredClass'].'"><td class="form-field">'.$form_field."</td></tr>\n";

                }
            }
        }

        if($form_field_hidden && $cnt_form["fields"][$key]['type'] === 'hidden' && $cnt_form['labelpos'] == 2) {

            // custom form template
            $POST_name_quoted = preg_quote($POST_name, '/');

            if(isset($POST_ERR[$key])) {
                // field error
                $form_cnt = preg_replace('/\[IF_ERROR:'.$POST_name_quoted.'\](.*?)\[\/IF_ERROR\]/s', '$1', $form_cnt);
                $form_cnt = preg_replace('/\[ELSE_ERROR:'.$POST_name_quoted.'\].*?\[\/ELSE_ERROR\]/s', '', $form_cnt);
                $form_cnt = str_replace('{ERROR:'.$POST_name.'}', html_specialchars($POST_ERR[$key]), $form_cnt);
            } else {
                // no field error
                $form_cnt = preg_replace('/\[IF_ERROR:'.$POST_name_quoted.'\].*?\[\/IF_ERROR\]/s', '', $form_cnt);
                $form_cnt = preg_replace('/\[ELSE_ERROR:'.$POST_name_quoted.'\](.*?)\[\/ELSE_ERROR\]/s', '$1', $form_cnt);
                $form_cnt = str_replace('{ERROR:'.$POST_name.'}', '', $form_cnt);
            }

            $form_cnt = str_replace('{'.$POST_name.'}', $form_field, $form_cnt);
            $form_cnt = str_replace('{LABEL:'.$POST_name.'}', html_specialchars($cnt_form["fields"][$key]['label']), $form_cnt);
        }

        $form_counter++;
    }

    if(empty($crow['recaptcha_submit_data'])) {
        $form_cnt = str_replace(' data-recaptchainv-submit', '', $form_cnt);
        $form_cnt = str_replace('phpwcms-recaptcha-class ', '', $form_cnt);
    } else {
        $form_cnt = str_replace(' data-recaptchainv-submit', $crow['recaptcha_submit_data'], $form_cnt);
        $form_cnt = str_replace('phpwcms-recaptcha-class', ' g-recaptcha', $form_cnt);
    }

    // check against custom PHP function used to validate form
    if($POST_DO && !empty($cnt_form['cform_function_validate']) && is_string($cnt_form['cform_function_validate'])) {

        $cnt_form['validate'] = explode('[', trim($cnt_form['cform_function_validate'], ']'));
        $cnt_form_validate_function = trim($cnt_form['validate'][0]);

        if($cnt_form_validate_function && function_exists($cnt_form_validate_function)) {

            $cnt_form_validate_fields = NULL;

            if(isset($cnt_form['validate'][1])) {
                $cnt_form_validate_fields = trim($cnt_form['validate'][1]);
                if($cnt_form_validate_fields) {
                    $cnt_form_validate_fields = convertStringToArray($cnt_form_validate_fields);
                    if(empty($cnt_form_validate_fields) || !count($cnt_form_validate_fields)) {
                        $cnt_form_validate_fields = NULL;
                    }
                }
            }

            if($cnt_form_validate_function($POST_val, $cnt_form_validate_fields, $crow['acontent_id']) === FALSE) {
                $POST_ERR['VALIDATE_FUNCTION_ERROR'] = TRUE;
            }

        }

    }
}

if((!empty($POST_DO) && empty($POST_ERR)) || !empty($doubleoptin_values)) {

    $POST_attach = array();
    $POST_savedb = array();

    if(!empty($doubleoptin_values['formresult_content'])) {
        $POST_val = $doubleoptin_values['formresult_content'];
    }

    // now prepare form values for sending or storing
    if(isset($POST_val) && is_array($POST_val) && count($POST_val)) {

        // fallback solution for older forms which do not know
        // separate email template for "copy to" recipient
        if(!isset($cnt_form['template_equal'])) {
            $cnt_form['template_equal'] = 1;
        }

        foreach($POST_val as $POST_key => $POST_keyval) {

            $POST_valurl = '';

            if(isset($cnt_form["copyto"]) && $cnt_form["copyto"] == $POST_key) {
                $cnt_form["copyto"] = $POST_keyval;
            }

            if(is_array($POST_keyval) && !isset($POST_keyval['folder'])) {
                // check if this is an array - but no upload value
                $POST_keyval = implode(', ', $POST_keyval);

            } elseif(is_array($POST_keyval) && isset($POST_keyval['folder'])) {
                // check if this is an array - and is an upload value
                $POST_valurl = PHPWCMS_URL.$POST_keyval['folder'].'/'.rawurlencode($POST_keyval['name']);
                if(isset($POST_keyval['attachment']) && $POST_keyval['attachment']) {
                    $POST_attach[] = PHPWCMS_ROOT.'/'.$POST_keyval['folder'].'/'.$POST_keyval['name'];
                }
                if(!$cnt_form['template_format']) {
                    $POST_keyval = $POST_valurl;
                }
            }

            // prepare for storing in database
            if(!empty($cnt_form['savedb'])) {

                $POST_savedb[$POST_key] = empty($POST_valurl) ? $POST_keyval : $POST_valurl;

            }


            // first check copy to email template related things
            if( !$cnt_form['template_equal'] ) {

                if($cnt_form['template_format_copy'] == 1) { //HTML

                    if(is_string($POST_keyval)) {
                        $POST_keyval_copy = html_specialchars($POST_keyval);
                    } elseif(is_array($POST_keyval) && isset($POST_keyval['folder'])) {
                        $POST_keyval_copy = '<a href="'.$POST_valurl.'" target="_blank">'.html_specialchars($POST_keyval['name']).'</a>';
                    }

                } else {

                    $POST_keyval_copy = $POST_keyval;

                }

                // replace tags in email form
                $cnt_form['template_copy'] = str_replace('{'. $POST_key . '}', $POST_keyval_copy, $cnt_form['template_copy']);

            }

            if($cnt_form['template_format']) { //HTML

                if(is_string($POST_keyval)) {
                    $POST_keyval = html_specialchars($POST_keyval);
                } elseif(is_array($POST_keyval) && isset($POST_keyval['folder'])) {
                    $POST_keyval = '<a href="'.$POST_valurl.'" target="_blank">'.html_specialchars($POST_keyval['name']).'</a>';
                }

                $cnt_form['is_html_entity'] = true;

            } else {

                // remember the HTML entity status
                $cnt_form['is_html_entity'] = false;

            }

            // replace tags in email form
            $cnt_form['template'] = str_replace('{'. $POST_key . '}', $POST_keyval, $cnt_form['template']);

            // replace tags in the success form
            if($cnt_form["onsuccess_redirect"] === 1) {

                $cnt_form["onsuccess"] = str_replace('{'. $POST_key . '}', rawurlencode($POST_keyval), $cnt_form["onsuccess"]);

            } else {

                $cnt_form["onsuccess"] = str_replace('{'. $POST_key . '}', (!$cnt_form['is_html_entity'] && $cnt_form["onsuccess_redirect"] === 2 ? html_specialchars($POST_keyval) : $POST_keyval), $cnt_form["onsuccess"]);

            }

        }

        $phpwcms['callback'] = now();

        $cnt_form["onsuccess"]  = str_replace('{REMOTE_IP}', PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP(), $cnt_form["onsuccess"]);

        if(strpos($cnt_form["onsuccess"], 'EMAIL_COPY') !== false) {
            if($cnt_form["onsuccess_redirect"] === 1) {
                $cnt_form["onsuccess"] = render_cnt_template($cnt_form["onsuccess"], 'EMAIL_COPY', empty($cnt_form['sendcopy']) || $cnt_form['option_email_copy'] === false ? '' : rawurlencode($cnt_form["copyto"]));
            } else {
                $cnt_form["onsuccess"] = render_cnt_template($cnt_form["onsuccess"], 'EMAIL_COPY', empty($cnt_form['sendcopy']) || $cnt_form['option_email_copy'] === false ? '' : html_specialchars($cnt_form["copyto"]));
            }
        }

        $cnt_form['onsuccess'] = preg_replace('/\{(.*?)\}/', '', $cnt_form['onsuccess']);

        $cnt_form['fe_current_url'] = abs_url(array(), array(), '', 'rawurlencode');

        $GLOBALS['phpwcms']['callback'] = now();
        $cnt_form['template'] = str_replace('{FORM_URL}', $cnt_form['fe_current_url'], $cnt_form['template']);
        $cnt_form['template'] = str_replace('{REMOTE_IP}', PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP(), $cnt_form['template']);
        $cnt_form['template'] = preg_replace_callback('/\{DATE:(.*?)\}/', 'date_callback', $cnt_form['template']);

        if( !$cnt_form['template_equal'] ) {

            $cnt_form['template_copy'] = str_replace('{FORM_URL}', $cnt_form['fe_current_url'], $cnt_form['template_copy']);
            $cnt_form['template_copy'] = str_replace('{REMOTE_IP}', PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP(), $cnt_form['template_copy']);
            $cnt_form['template_copy'] = preg_replace_callback('/\{DATE:(.*?)\}/', 'date_callback', $cnt_form['template_copy']);
            $cnt_form['template_copy'] = preg_replace('/\{(.*?)\}/', '', $cnt_form['template_copy']);

        }

        if(!empty($cnt_form['doubleoptin'])) {
            $POST_savedb['hash'] = preg_replace('/[^a-z0-9]/i', '', shortHash($cnt_form['doubleoptin_target'].time() ) );
            $cnt_form['template_doubleoptin'] = str_replace('{FORM_URL}', abs_url(array('hash' => $POST_savedb['hash']), array(), '', 'rawurlencode'), $cnt_form['template_doubleoptin']);
            $cnt_form['template_doubleoptin'] = str_replace('{REMOTE_IP}', PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP(), $cnt_form['template_doubleoptin']);
            $cnt_form['template_doubleoptin'] = preg_replace_callback('/\{DATE:(.*?)\}/', 'date_callback', $cnt_form['template_doubleoptin']);
            $cnt_form['template_doubleoptin'] = preg_replace('/\{(.*?)\}/', '', $cnt_form['template_doubleoptin']);
            $cnt_form['template_doubleoptin'] = preg_replace('/\{(.*?)\}/', '', $cnt_form['template_doubleoptin']);
        }

        $cnt_form['template']   = preg_replace('/\{(.*?)\}/', '', $cnt_form['template']);

        // check if "copy to" email template is equal recipient
        // email template and set it the same
        if($cnt_form['template_equal'] == 1) {

            $cnt_form['template_format_copy']   = $cnt_form['template_format'];
            $cnt_form['template_copy']          = $cnt_form['template'];

        }

        if(!empty($doubleoptin_values)) {
            $POST_savedb = array();
        }
    }

    // get email addresses of recipients and senders
    $cnt_form["target"] = convertStringToArray($cnt_form["target"], ';');
    if(empty($cnt_form["subject"])) {
        $cnt_form["alt_subj"] = str_replace('http://', '', $phpwcms['site']);
        $cnt_form["alt_subj"] = substr($cnt_form["alt_subj"], 0, trim($phpwcms['site'], '/'));
        $cnt_form["subject"]  = 'Webform: '.$cnt_form["alt_subj"];
    }

    // check for BCC Addresses
    $cnt_form['cc'] = empty($cnt_form['cc']) ? array() : convertStringToArray($cnt_form['cc'], ';');

    // first try to send copy message
    if(!empty($cnt_form['sendcopy']) && !empty($cnt_form["copyto"]) && is_valid_email($cnt_form["copyto"])) {

        // check if user has avoided receiving email copy
        if($cnt_form['option_email_copy'] !== false) {
            $cnt_form['cc'][] = $cnt_form["copyto"];
        }

        $cnt_form['fromEmail'] = $cnt_form["copyto"];
    }

    // check for unique recipients (target) and sender (fromEmail)
    if(!empty($cnt_form['checktofrom']) && !empty($cnt_form['fromEmail'])) {
        foreach($cnt_form["target"] as $value) {
            if(strtolower($cnt_form['fromEmail']) == strtolower($value)) {
                $POST_ERR[] = '@@Sender&#8217;s email must be different from recipient&#8217;s email@@';
                break;
            }
        }
    }
}

// do $POST_ERR test again to handle possible duplicates
// in case 'checktofrom' = 1
if((!empty($POST_DO) && empty($POST_ERR)) || (!empty($doubleoptin_values) && !$doubleoptin_error)) {

    // check if there are form values which should be saved in db
    if(count($POST_savedb)) {
        $POST_savedb_sql  = 'INSERT INTO '.DB_PREPEND.'phpwcms_formresult ';
        $POST_savedb_sql .= '(formresult_pid, formresult_ip, formresult_content) VALUES (';
        $POST_savedb_sql .= $crow['acontent_id'].", "._dbEscape(PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP()).", ";
        $POST_savedb_sql .= _dbEscape(serialize($POST_savedb)) . ")";
        $POST_savedb_sql  = _dbQuery($POST_savedb_sql, 'INSERT');
    }

    if(!empty($cnt_form["doubleoptin"]) && !empty($cnt_form['doubleoptin_target']) && $POST_DO) {

        if(!empty($cnt_form["onsuccess"])) {
            $CNT_TMP .= '<p class="error form-copy-to">'.$cnt_form["onsuccess"].'</p>';
        }

        if (is_valid_email($cnt_form['doubleoptin_target'])) {
            // send mail, include phpmailer class
            require_once PHPWCMS_ROOT.'/include/inc_ext/phpmailer/PHPMailerAutoload.php';

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
            $mail->CharSet          = $phpwcms["charset"];

            $mail->isHTML($cnt_form['template_format_doubleoptin']);
            $mail->Subject          = $cnt_form["subject"];
            $mail->Body             = $cnt_form['template_doubleoptin'];
            if(!$mail->setLanguage($phpwcms['default_lang'], PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/')) {
                $mail->setLanguage('en', PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/');
            }

            $mail->setFrom($cnt_form['sender'], $cnt_form['sendername']);
            $mail->addReplyTo($cnt_form['sender']);

            $cnt_form["copytoError"] = array();

            $mail->addAddress($cnt_form['doubleoptin_target']);

            if(!$mail->send()) {
                $cnt_form["copytoError"][] = html_specialchars($cnt_form['doubleoptin_target'] . ' ('.$mail->ErrorInfo.')');
            }

            $mail->clearAddresses();

            if(count($cnt_form["copytoError"])) {
                $cnt_form["copytoError"] = implode('<br />', $cnt_form["copytoError"]);
            } else {
                unset($cnt_form["copytoError"]);
            }

            unset($mail);
        }

    } else {

        // send mail, include phpmailer class
        require_once PHPWCMS_ROOT.'/include/inc_ext/phpmailer/PHPMailerAutoload.php';

        // now run all CC -> but sent as full email to each CC recipient
        if(count($cnt_form['cc'])) {

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
            $mail->CharSet          = $phpwcms["charset"];

            if(isset($cnt_form['function_cc']) && function_exists($cnt_form['function_cc'])) {
                @$cnt_form['function_cc']($POST_savedb, $cnt_form, $mail);
            }

            $mail->isHTML($cnt_form['template_format_copy']);
            $mail->Subject          = $cnt_form["subject"];
            $mail->Body             = $cnt_form['template_copy'];
            if(!$mail->setLanguage($phpwcms['default_lang'], PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/')) {
                $mail->setLanguage('en', PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/');
            }

            $mail->setFrom($cnt_form['sender'], $cnt_form['sendername']);
            $mail->addReplyTo($cnt_form['sender']);

            $cnt_form["copytoError"] = array();

            foreach($cnt_form['cc'] as $cc_email) {

                $mail->addAddress($cc_email);

                if(!$mail->send()) {
                    $cnt_form["copytoError"][] = html_specialchars($cc_email.' ('.$mail->ErrorInfo.')');
                }

                $mail->clearAddresses();

            }

            if(count($cnt_form["copytoError"])) {
                $cnt_form["copytoError"] = implode('<br />', $cnt_form["copytoError"]);
            } else {
                unset($cnt_form["copytoError"]);
            }

            unset($mail);
        }

        // now send original message
        $mail = new PHPMailer();
        $mail->Mailer           = $phpwcms['SMTP_MAILER'];
        $mail->Host             = $phpwcms['SMTP_HOST'];
        $mail->Port             = $phpwcms['SMTP_PORT'];
        if($phpwcms['SMTP_AUTH']) {
            $mail->SMTPAuth     = 1;
            $mail->Username     = $phpwcms['SMTP_USER'];
            $mail->Password     = $phpwcms['SMTP_PASS'];
        }
        $mail->CharSet          = $phpwcms["charset"];

        if(isset($cnt_form['function_to']) && function_exists($cnt_form['function_to'])) {
            @$cnt_form['function_to']($POST_savedb, $cnt_form, $mail);
        }

        $mail->isHTML($cnt_form['template_format']);
        $mail->Subject          = $cnt_form["subject"];
        $mail->Body             = $cnt_form['template'];

        if(!$mail->setLanguage($phpwcms['default_lang'], PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/')) {
            $mail->setLanguage('en', PHPWCMS_ROOT.'/include/inc_ext/phpmailer/language/');
        }
        if(empty($cnt_form["fromEmail"])) {
            $cnt_form["fromEmail"] = $phpwcms['SMTP_FROM_EMAIL'];
        }

        $mail->setFrom($cnt_form['sender'], $cnt_form['sendername']);
        $mail->addReplyTo($cnt_form['sender']);

        if(!empty($cnt_form["target"]) && is_array($cnt_form["target"]) && count($cnt_form["target"])) {

            foreach($cnt_form["target"] as $e_value) {
                $mail->addAddress(trim($e_value));
            }

        } else {
            // use default email address
            $mail->addAddress($phpwcms['SMTP_FROM_EMAIL']);
        }

        if(count($POST_attach)) {
            foreach($POST_attach as $attach_file) {
                $mail->addAttachment($attach_file);
            }
        }

        if(!$mail->send()) {
            $CNT_TMP .= '<p>'.html_specialchars($mail->ErrorInfo).'</p>';
        } else {

            // check if user should be registered for newsletter
            if(isset($form_newletter_setting['selection']) && count($form_newletter_setting['selection'])) {

                // first check if neccessary form field is valid email
                if(isset($POST_val[ $form_newletter_setting['email_field'] ]) && is_valid_email($POST_val[ $form_newletter_setting['email_field'] ])) {

                    // ok now I know we can store email as newsletter recipient
                    $form_newletter_setting['email_field'] = $POST_val[ $form_newletter_setting['email_field'] ];

                    // now try to find fields to build recipient's name, if empty name is same as email
                    if(!empty($form_newletter_setting['name_field'])) {

                        // split by "+"
                        $form_newletter_setting['name_field_tmp'] = explode('+', $form_newletter_setting['name_field']);
                        $form_newletter_setting['name_field'] = '';
                        foreach($form_newletter_setting['name_field_tmp'] as $form_value_nl) {

                            // empty - continue
                            if(empty($form_value_nl)) {
                                continue;
                            }

                            // now check if field name exists and build corresponding name value
                            if(empty($POST_val[ trim($form_value_nl) ])) {
                                $form_newletter_setting['name_field'] .= $form_value_nl;
                            } else {
                                $form_value_nl = trim($form_value_nl);
                                $form_newletter_setting['name_field'] .= $POST_val[ $form_value_nl ];
                            }

                        }
                        $form_newletter_setting['name_field'] = trim($form_newletter_setting['name_field']);

                    }

                    if(empty($form_newletter_setting['name_field'])) {
                        $form_newletter_setting['name_field'] = $form_newletter_setting['email_field'];
                    }

                    $form_newletter_setting['hash'] = preg_replace('/[^a-z0-9]/i', '', shortHash( $form_newletter_setting['email_field'].time() ) );

                    // create SQL query to populate recipient into recipients db
                    $form_newletter_setting['sql']  = 'INSERT INTO '.DB_PREPEND.'phpwcms_address ';
                    $form_newletter_setting['sql'] .= '(address_key, address_email, address_name, address_verified, ';
                    $form_newletter_setting['sql'] .= 'address_subscription, address_url1, address_url2) VALUES (';
                    $form_newletter_setting['sql'] .= _dbEscape($form_newletter_setting['hash']).", ";
                    $form_newletter_setting['sql'] .= _dbEscape($form_newletter_setting['email_field']).", ";
                    $form_newletter_setting['sql'] .= _dbEscape($form_newletter_setting['name_field']).", ";
                    $form_newletter_setting['sql'] .= (empty($form_newletter_setting['double_optin']) ? 1 : 0) .", ";
                    $form_newletter_setting['sql'] .= _dbEscape(serialize($form_newletter_setting['selection'])).", ";
                    $form_newletter_setting['sql'] .= _dbEscape(empty($form_newletter_setting['url_subscribe']) ? '' : $form_newletter_setting['url_subscribe']).", ";
                    $form_newletter_setting['sql'] .= _dbEscape(empty($form_newletter_setting['url_unsubscribe']) ? '' : $form_newletter_setting['url_unsubscribe']);
                    $form_newletter_setting['sql'] .= ')';

                    // save recipient in db and send verify message in case of double opt-in
                    $form_newletter_setting['query_result'] = @_dbQuery($form_newletter_setting['sql'], 'INSERT');

                    // now send opt-in email
                    if(!empty($form_newletter_setting['double_optin'])) {

                        if(empty($cnt_form['verifyemail'])) {
                            if(empty($form_newletter_setting['optin_template']) || !is_file(PHPWCMS_TEMPLATE.'inc_cntpart/newsletter/email/'.trim($form_newletter_setting['optin_template']))) {
                                $cnt_form['verifyemail'] = file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/newsletter/email/default.opt-in.txt');
                            } else {
                                $cnt_form['verifyemail'] = file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/newsletter/email/'.trim($form_newletter_setting['optin_template']));
                                if(trim($cnt_form['verifyemail']) === '') {
                                    $cnt_form['verifyemail'] = file_get_contents(PHPWCMS_TEMPLATE.'inc_cntpart/newsletter/email/default.opt-in.txt');
                                }
                            }
                            if(trim($cnt_form['verifyemail']) === '') {
                                $cnt_form['verifyemail']  = 'Hi {NEWSLETTER_NAME},'.LF.LF.'Someone (presumably you) on {SITE}'.LF.'subscribed to these newsletters:'.LF;
                                $cnt_form['verifyemail'] .= '{SUBSCRIPTIONS}'.LF.LF.'The following email was requested for subscription'.LF.'{NEWSLETTER_EMAIL}'.LF.LF;
                                $cnt_form['verifyemail'] .= 'If you requested this subscription, visit the following URL'.LF.'{NEWSLETTER_VERIFY}'.LF.'to verify and activate it.'.LF.LF;
                                $cnt_form['verifyemail'] .= 'Ignore the message or visit the following URL'.LF.'{NEWSLETTER_DELETE}'.LF.'and nothing will happen.'.LF.LF.LF;
                                $cnt_form['verifyemail'] .= 'With best regards'.LF.'Webmaster'.LF.LF.'--'.LF.'{DATE:m/d/Y H:i:s}, IP: {IP}'.LF;
                            }
                        }

                        $form_newletter_setting['hash'] = rawurlencode($form_newletter_setting['hash']);

                        $form_newletter_setting['selection_text'] = array();
                        foreach($form_newletter_setting['selection'] as $form_value_nl) {
                            $form_newletter_setting['subscr_text'][] = '[X] '.$form_newletter_setting['subscriptions'][$form_value_nl];
                        }

                        if($form_newletter_setting['email_field'] == $form_newletter_setting['name_field']) $form_newletter_setting['name_field'] = '';

                        $cnt_form['verifyemail'] = str_replace('{NEWSLETTER_NAME}', $form_newletter_setting['name_field'], $cnt_form['verifyemail']);
                        $cnt_form['verifyemail'] = str_replace('{SUBSCRIPTIONS}', implode(LF, $form_newletter_setting['subscr_text']), $cnt_form['verifyemail']);
                        $cnt_form['verifyemail'] = str_replace('{NEWSLETTER_EMAIL}', $form_newletter_setting['email_field'], $cnt_form['verifyemail']);
                        $cnt_form['verifyemail'] = str_replace('{NEWSLETTER_VERIFY}', PHPWCMS_URL.'verify.php?s='.$form_newletter_setting['hash'], $cnt_form['verifyemail']);
                        $cnt_form['verifyemail'] = str_replace('{NEWSLETTER_DELETE}', PHPWCMS_URL.'verify.php?u='.$form_newletter_setting['hash'], $cnt_form['verifyemail']);
                        $cnt_form['verifyemail'] = str_replace(array('[br]', '[BR]'), LF, $cnt_form['verifyemail']);
                        $cnt_form['verifyemail'] = replaceGlobalRT($cnt_form['verifyemail']);

                        if(empty($form_newletter_setting['sender_email'])) $form_newletter_setting['sender_email'] = $cnt_form['sender'];
                        if(empty($form_newletter_setting['sender_name']))  $form_newletter_setting['sender_name']  = $cnt_form['sendername'];

                        // now send verification email
                        @sendEmail(array(
                           'recipient' => $form_newletter_setting['email_field'],
                            'toName'    => $form_newletter_setting['name_field'],
                            'subject'   => $form_newletter_setting['subject'],
                            'text'      => $cnt_form['verifyemail'],
                            'from'      => $form_newletter_setting['sender_email'],
                            'fromName'  => $form_newletter_setting['sender_name'],
                            'sender'    => $form_newletter_setting['sender_email']
                        ));
                    }

                }

            }

            if (!empty($cnt_form["doubleoptin"]) && !empty($doubleoptin_values)) {

                $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_formresult ';
                $sql .= 'SET formresult_content=' . _dbEscape(serialize($doubleoptin_values['formresult_content']));
                $sql .= ' WHERE formresult_id=' . intval($doubleoptin_values['formresult_id']);
                $result  = _dbQuery($sql, 'UPDATE');

                if($cnt_form["onsuccess_doubleoptin_redirect"] === 1) {
                    // redirect on success
                    headerRedirect(str_replace('{SITE}', PHPWCMS_URL, $cnt_form["onsuccess_doubleoptin"]));

                } elseif($cnt_form["onsuccess_doubleoptin"]) {
                    // success
                    $CNT_TMP .= '<div class="' . trim('form-success ' . $cnt_form["class"]) . '">';
                    $CNT_TMP .= !$cnt_form["onsuccess_doubleoptin_redirect"] ? plaintext_htmlencode($cnt_form["onsuccess_doubleoptin"]) : $cnt_form["onsuccess_doubleoptin"];
                    $CNT_TMP .= '</div>';
                }

            } elseif($cnt_form["onsuccess_redirect"] === 1) {
                // redirect on success
                headerRedirect(str_replace('{SITE}', PHPWCMS_URL, $cnt_form["onsuccess"]));

            } elseif($cnt_form["onsuccess"]) {
                // success
                $CNT_TMP .= '<div class="' . trim('form-success ' . $cnt_form["class"]) . '">' . LF;
                $CNT_TMP .= !$cnt_form["onsuccess_redirect"] ? plaintext_htmlencode($cnt_form["onsuccess"]) : $cnt_form["onsuccess"];
                $CNT_TMP .= '</div>';
            }
        }
    }
    if(!empty($cnt_form["copytoError"])) {
        $CNT_TMP .= '<p class="error form-copy-to">'.$cnt_form["copytoError"].'</p>';
    }

    unset($mail);

    $form_cnt = '';

} elseif($doubleoptin_error) {

    if($cnt_form["onerror_doubleoptin_redirect"] === 1) {
        // redirect on success
        headerRedirect(str_replace('{SITE}', PHPWCMS_URL, $cnt_form["onerror_doubleoptin"]));

    } elseif($cnt_form["onerror_doubleoptin"]) {
        // success
        $CNT_TMP .= '<div class="' . trim('form-error ' . $cnt_form["class"]) . '">';
        $CNT_TMP .= !$cnt_form["onerror_doubleoptin_redirect"] ? plaintext_htmlencode($cnt_form["onerror_doubleoptin"]) : $cnt_form["onerror_doubleoptin"];
        $CNT_TMP .= '</div>';
    }

    $form_cnt = '';

} elseif(isset($POST_ERR)) {
    // do on POST_ERROR

    if(isset($_FILES)) {
        foreach($_FILES as $file_key => $file_val) {
            @unlink($_FILES[$file_key]['tmp_name']);
        }
        if(isset($POST_val) && count($POST_val)) {
            foreach($POST_val as $file_key => $file_val) {
                if(isset($POST_val[$file_key]['name'])) {
                    @unlink(PHPWCMS_ROOT.'/'.$POST_val[$file_key]['folder'].$POST_val[$file_key]['name']);
                }
            }
        }
    }

    if($cnt_form["onerror_redirect"] === 1) {

        headerRedirect(str_replace('{SITE}', PHPWCMS_URL, $cnt_form["onerror"]));

    } else {

        if($cnt_form["onerror"]) {

            $form_error_text  = '<div class="form-error on-send">' . LF;
            $form_error_text .= $cnt_form["onerror_redirect"] === 0 ? plaintext_htmlencode($cnt_form["onerror"]) : $cnt_form["onerror"];
            $form_error_text .= LF . '</div>' . LF;

        }

        $POST_ERR = array_diff( $POST_ERR , array('', FALSE, TRUE) );
        $POST_ERR = array_map( 'html_specialchars', $POST_ERR );
        if($cnt_form['labelpos'] != 2 && count( $POST_ERR ) ) {

            if($cnt_form['labelpos'] == 3) {

                $form_error  = '<div class="' . trim('form-error ' . $cnt_form["error_class"]) . '">';
                $form_error .= '<p>' . implode('</p><p>', $POST_ERR) . '</p></div>';

            } else {

                $form_error = "<tr>";
                if($cnt_form['labelpos'] == 0) { // label: field
                    $form_error .= '<td class="form-label">'."&nbsp;</td>";
                }
                $form_error .= '<td'.(!empty($cnt_form["error_class"]) ? ' class="'.$cnt_form["error_class"].'"' : '').'>';
                $form_error .= implode("<br />", $POST_ERR);
                $form_error .= "</td></tr>";

            }

            $form_cnt = $form_error.$form_cnt;

            unset($form_error);
        }
    }

} elseif(!empty($cnt_form['startup'])) { // form was not send yet, display startup text

    $CNT_TMP .= empty($cnt_form['startup_html']) ? '<div class="form-intro">' . plaintext_htmlencode($cnt_form['startup']) . '</div>' : $cnt_form['startup'];

}

if($form_cnt) {
    $form_cnt = str_replace('###RESET###', '', $form_cnt);
    $cnt_form["class_close"] = '';
    if($cnt_form["class"]) {
        $CNT_TMP .= '<div class="'.$cnt_form["class"].'">';
        $cnt_form["class_close"] = '</div>';
        $cnt_form['class'] = ' class="form-'.$cnt_form["class"].'"';
    } else {
        $cnt_form['class'] = '';
    }
    $CNT_TMP .= $form_error_text;
    $CNT_TMP .= '<form id="phpwcmsForm'.$crow["acontent_id"].'"'.$cnt_form['class'].' action="'.rel_url();
    if(!empty($cnt_form['anchor_name'])) {
        $CNT_TMP .= '#'.html($cnt_form['anchor_name']);
    } elseif(empty($cnt_form['anchor_off'])) {
        $CNT_TMP .= '#jumpForm'.$crow["acontent_id"];
    }
    $CNT_TMP .= '" ';
    if($cnt_form['is_enctype']) {
        $CNT_TMP .= 'enctype="multipart/form-data" ';
    }
    $CNT_TMP .= 'method="post">';


    if($cnt_form['labelpos'] == 2) {

        if(isset($POST_ERR) && count($POST_ERR)) {
            $form_cnt = preg_replace('/\[IF_ERROR\](.*?)\[\/IF_ERROR\]/s', '$1', $form_cnt);
            $form_cnt = preg_replace('/\[ELSE_ERROR\].*?\[\/ELSE_ERROR\]/s', '', $form_cnt);
        } else {
            $form_cnt = preg_replace('/\[IF_ERROR\].*?\[\/IF_ERROR\]/s', '', $form_cnt);
            $form_cnt = preg_replace('/\[ELSE_ERROR\](.*?)\[\/ELSE_ERROR\]/s', '$1', $form_cnt);
        }
        $CNT_TMP .= $form_cnt;

    } elseif($cnt_form['labelpos'] == 3) {

        $CNT_TMP .= $form_cnt;

    } else {

        $CNT_TMP .= '<table cellspacing="0" cellpadding="0" border="0">';
        $CNT_TMP .= "\n".$form_cnt.'</table>';

    }

    $CNT_TMP .= '<div><input type="hidden" name="cpID'.$crow["acontent_id"].'" value="'.$crow["acontent_id"].'" />';
    $CNT_TMP .= $form_field_hidden;
    $CNT_TMP .= getFormTrackingValue(); //hidden form tracking field
    $CNT_TMP .= '</div></form>' . $cnt_form["class_close"];
}

$CNT_TMP .= $crow['attr_class_id_close'];

unset( $form, $form_cnt, $form_cnt_2, $form_field, $form_field_hidden, $form_counter, $form_error_text, $POST_ERR );

// reset form tracking status to default value
$phpwcms['form_tracking'] = $default_formtracking_value;
