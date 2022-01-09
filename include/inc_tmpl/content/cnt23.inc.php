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

// CP Form

$field_counter = 0;
initMootools();
$BE['HEADER']['contentpart.js'] = getJavaScriptSourceLink('include/inc_js/contentpart.js');
$BE['HEADER']['custom_js'] = '<script type="text/javascript">
function initMathSpam() {
    document.getElementById("cform_field_value_0").value = "+ = ' .
    $BL['be_cnt_field']['summing'] .
    '\n- = ' .
    $BL['be_cnt_field']['subtract'] .
    '\n* = ' .
    $BL['be_cnt_field']['multiply'] .
    '\n: = ' .
    $BL['be_cnt_field']['divide'] .
    '\ncalc = ' .
    $BL['be_cnt_field']['calculation'] .
    '";
}
function setFieldValue(el) {
    if(el.options[el.selectedIndex].value === "mathspam") {
        initMathSpam();
    }
}
</script>';

$BL['be_cnt_field'] = array_merge(
    array(
        "text" => 'text (single-line)',
        "email" => 'email',
        "textarea" => 'text (multi-line)',
        "hidden" => 'hidden',
        "password" => 'password',
        "select" => 'select menu',
        "list" => 'list menu',
        "checkbox" => 'checkbox',
        "checkboxcopy" => 'checkbox (email copy on/off)',
        "radio" => 'radio button',
        "upload" => 'file',
        "submit" => 'send button',
        "reset" => 'reset button',
        "break" => 'break',
        "breaktext" => 'break text',
        "special" => 'text (spezial)',
        "captchaimg" => 'captcha image',
        "captcha" => 'captcha code',
        'newsletter' => 'newsletter',
        'selectemail' => 'select email menu',
        'country' => 'select country menu',
        'mathspam' => 'math spam protect',
        'summing' => 'summing',
        'subtract' => 'subtract',
        'divide' => 'divide',
        'multiply' => 'multiply',
        'calculation' => 'calculation:',
        'formtracking_off' => 'disable form tracking',
        'checktofrom' => 'email of recipient must be different from sender',
        'recaptcha' => 'reCAPTCHA',
        'recaptcha_signapikey' => 'Sign up for a reCAPTCHA API key',
        'recaptchainv' => 'Invisible reCAPTCHA',
    ),
    $BL['be_cnt_field']
);

if (empty($content['form']) || !is_array($content['form'])) {
    $content['form'] = array();
}

$content['form'] = array_merge(
    array(
        'subject' => '',
        'startup' => '',
        'startup_html' => 0,
        'targettype' => 'email',
        'class' => '',
        'label_wrap' => '|:',
        'error_class' => 'error',
        'cform_reqmark' => '*',
        'target' => '',
        "copyto" => '',
        "sendcopy" => 0,
        "onsuccess_redirect" => 0,
        "onsuccess" => '',
        "onerror_redirect" => 0,
        "onerror" => '',
        "template_format" => 0,
        "template" => '',
        "template_format_copy" => 0,
        "template_copy" => '',
        'template_equal' => 1,
        "customform" => '',
        'sender' => '',
        'sendertype' => 'email',
        'sendername' => '',
        'sendernametype' => 'custom',
        'cc' => '',
        'subjectselect' => '',
        'savedb' => 0,
        'saveprofile' => 0,
        'verifyemail' => '',
        'formtracking_off' => 0,
        'checktofrom' => 0,
        'function_to' => '',
        'function_cc' => '',
        'anchor_off' => 0,
        'anchor_name' => '',
        'ssl' => 0,
        'cform_function_validate' => '',
        'doubleoptin' => PHPWCMS_GDPR_MODE ? 1 : 0,
        'doubleoptin_targettype' => 0,
        'template_format_doubleoptin' => 0,
        'template_doubleoptin' => '',
        'onsuccess_doubleoptin' => '',
        'onerror_doubleoptin' => '',
        'onsuccess_redirect_doubleoptin' => 0,
        'onerror_redirect_doubleoptin' => 0
    ),
    $content['form']
);

$content['profile_fields'] = array(
    "title" => $BL['be_profile_label_title'],
    "firstname" => $BL['be_profile_label_firstname'],
    "lastname" => $BL['be_profile_label_name'],
    "company" => $BL['be_profile_label_company'],
    "street" => $BL['be_profile_label_street'],
    "add" => $BL['be_profile_label_add'],
    "city" => $BL['be_profile_label_city'],
    "zip" => $BL['be_profile_label_zip'],
    "region" => $BL['be_profile_label_state'],
    "country" => $BL['be_profile_label_country'],
    "fon" => $BL['be_profile_label_phone'],
    "fax" => $BL['be_profile_label_fax'],
    "mobile" => $BL['be_profile_label_cellphone'],
    "signature" => $BL['be_profile_label_signature'],
    'notes' => $BL['be_profile_label_notes'],
    "prof" => $BL['be_profile_label_profession'],
    "newsletter" => $BL['be_profile_label_newsletter'],
    "website" => $BL['be_profile_label_website'],
    'gender' => $BL['be_profile_label_gender'],
    'birthday' => $BL['be_profile_label_birthday'],
    "varchar1" => $BL['be_cnt_field']['text'] . ' 1',
    "varchar2" => $BL['be_cnt_field']['text'] . ' 2',
    "varchar3" => $BL['be_cnt_field']['text'] . ' 3',
    "varchar4" => $BL['be_cnt_field']['text'] . ' 4',
    "varchar5" => $BL['be_cnt_field']['text'] . ' 5',
    "text1" => $BL['be_cnt_field']['textarea'] . ' 1',
    "text2" => $BL['be_cnt_field']['textarea'] . ' 2',
    "text3" => $BL['be_cnt_field']['textarea'] . ' 3'
);

$content['profile_fields_varchar'] = array(
    "title" => $BL['be_profile_label_title'],
    "firstname" => $BL['be_profile_label_firstname'],
    "lastname" => $BL['be_profile_label_name'],
    "company" => $BL['be_profile_label_company'],
    "street" => $BL['be_profile_label_street'],
    "add" => $BL['be_profile_label_add'],
    "city" => $BL['be_profile_label_city'],
    "zip" => $BL['be_profile_label_zip'],
    "region" => $BL['be_profile_label_state'],
    "country" => $BL['be_profile_label_country'],
    "fon" => $BL['be_profile_label_phone'],
    "fax" => $BL['be_profile_label_fax'],
    "mobile" => $BL['be_profile_label_cellphone'],
    "email" => $BL['be_profile_label_email'],
    "password" => $BL['be_cnt_field']['password'],
    "signature" => $BL['be_profile_label_signature'],
    "prof" => $BL['be_profile_label_profession'],
    "website" => $BL['be_profile_label_website'],
    'gender' => $BL['be_profile_label_gender'],
    "varchar1" => $BL['be_cnt_field']['text'] . ' 1',
    "varchar2" => $BL['be_cnt_field']['text'] . ' 2',
    "varchar3" => $BL['be_cnt_field']['text'] . ' 3',
    "varchar4" => $BL['be_cnt_field']['text'] . ' 4',
    "varchar5" => $BL['be_cnt_field']['text'] . ' 5'
);
$content['profile_fields_longtext'] = array(
    'notes' => $BL['be_profile_label_notes'],
    "text1" => $BL['be_cnt_field']['textarea'] . ' 1',
    "text2" => $BL['be_cnt_field']['textarea'] . ' 2',
    "text3" => $BL['be_cnt_field']['textarea'] . ' 3'
);

$for_select = '';
$for_select_2 = '';

// always disable switching content part for form - too complex settings and better to safe the user for himself
initMootools();
$BE['BODY_CLOSE'][] = '<script type="text/javascript">document.getElementById("target_ctype").disabled = true;</script>';

?>
<tr>
    <td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="5"/><input type="hidden" name="target_ctype" value="23"/></td>
</tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_msg_subject'] ?>:&nbsp;</td>
    <td>
        <table summary="" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td><select name="cform_subjectselect" class="v11" style="width:200px">

                        <option value=""><?php echo $BL['be_msg_subject'] ?></option>
                        <?php

                        $cc_listing = '';
                        $recipient_option = '';
                        $sender_option = '';
                        $sendername_option = '';
                        $subject_option = '';
                        $recipient_option_doubleoptin = '';

                        if (isset($content['form']["fields"]) && is_array($content['form']["fields"]) && count($content['form']["fields"])) {
                            foreach ($content['form']["fields"] as $key => $value) {

                                $for_copy = false;
                                $for_sendername = false;
                                $for_email = false;
                                $for_placeholder = true;
                                $for_subject = false;
                                $for_newsletter = false;
                                $for_name = html($content['form']["fields"][$key]['name']);

                                switch ($content['form']["fields"][$key]['type']) {

                                    case 'text':
                                        $for_copy = true;
                                        $for_sendername = true;
                                        $for_subject = true;
                                        break;

                                    case 'email':
                                        $for_copy = true;
                                        $for_email = true;
                                        $for_sendername = true;
                                        break;

                                    case 'selectemail':
                                        $for_copy = true;
                                        $for_email = true;
                                        break;

                                    case 'hidden':
                                        $for_copy = true;
                                        $for_subject = true;
                                        break;

                                    case 'newsletter':
                                        $for_newsletter = true;
                                        break;

                                    case 'select':
                                    case 'list':
                                        $for_subject = true;
                                        break;
                                }

                                if ($for_subject) {
                                    $subject_option .= '<option value="formfield_' . $for_name . '"';
                                    $subject_option .= is_selected($content['form']['subjectselect'], 'formfield_' . $content['form']['fields'][$key]['name'], 0, 0);
                                    $subject_option .= '>' . $BL['be_cnt_guestbook_form'] . ': ' . $for_name . '</option>';
                                }

                                if ($for_copy) {
                                    $cc_listing .= '<option value="' . $for_name . '"';
                                    $cc_listing .= is_selected($content['form']["copyto"], $content['form']['fields'][$key]['name'], 0, 0);
                                    $cc_listing .= '>' . $for_name . '</option>';

                                    if ($for_email) {
                                        $recipient_option .= '<option value="emailfield_' . $for_name . '"';
                                        $recipient_option .= is_selected($content['form']['targettype'], 'emailfield_' . $content['form']['fields'][$key]['name'], 0, 0);
                                        $recipient_option .= '>' . $BL['be_cnt_guestbook_form'] . ': ' . $for_name . '</option>';

                                        $recipient_option_doubleoptin .= '  <option value="emailfield_'.$for_name.'"';
                                        $recipient_option_doubleoptin .= is_selected($content['form']['doubleoptin_targettype'], 'emailfield_'.$content['form']['fields'][$key]['name'], 0, 0);
                                        $recipient_option_doubleoptin .= '>'.$BL['be_cnt_guestbook_form'].': '.$for_name.'</option>';

                                        $sender_option .= '<option value="emailfield_' . $for_name . '"';
                                        $sender_option .= is_selected($content['form']['sendertype'], 'emailfield_' . $content['form']['fields'][$key]['name'], 0, 0);
                                        $sender_option .= '>' . $BL['be_cnt_guestbook_form'] . ': ' . $for_name . '</option>';
                                    }

                                    if ($for_sendername) {
                                        $sendername_option .= '<option value="formfield_' . $for_name . '"';
                                        $sendername_option .= is_selected($content['form']['sendernametype'], 'formfield_' . $content['form']['fields'][$key]['name'], 0, 0);
                                        $sendername_option .= '>' . $BL['be_cnt_guestbook_form'] . ': ' . $for_name . '</option>';
                                    }
                                }

                                // parallel building of the placeholder tag menu for the template
                                switch ($content['form']["fields"][$key]['type']) {
                                    case 'submit':
                                    case 'reset':
                                    case 'break':
                                    case 'breaktext':
                                        $for_placeholder = false;
                                        break;
                                }

                                $for_select_2 .= '<option value="';
                                $for_tempselect = '';
                                if ($for_placeholder) {

                                    $for_select .= '<option value="{' . $for_name . '}">';
                                    if (!empty($content['form']["fields"][$key]['label'])) {
                                        $for_select .= html($content['form']["fields"][$key]['label']) . ' ';
                                        $for_tempselect .= html($content['form']["fields"][$key]['label']) . ' ';
                                    }
                                    $for_select .= '{' . $for_name . "}</option>\n";

                                    $for_select_2 .= '{ERROR:' . $for_name . '}{LABEL:' . $for_name . '}';

                                }
                                $for_select_2 .= '{' . $for_name . '}">' . $for_tempselect . '{' . $for_name . "}</option>\n";

                            }
                        }

                        echo $subject_option;

                        ?>
                    </select></td>
                <td>&nbsp;</td>
                <td><input name="cform_subject" type="text" id="cform_subject" class="f11b" style="width:230px" value="<?php echo html($content['form']["subject"]) ?>" size="40"/></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"/></td>
</tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_recipient'] ?>:&nbsp;</td>
    <td>
        <table summary="" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td><select name="cform_targettype" class="v11" style="width:200px">
                    <?php
                        echo '<option value="email"' . is_selected('email', $content['form']['targettype'], 0, 0) . '>' . $BL['be_profile_label_email'] . '</option>';
                        echo $recipient_option;
                    ?>
                    </select></td>
                <td>&nbsp;</td>
                <td><input name="cform_target" type="text" id="cform_target" class="f11b" style="width:230px" value="<?php echo html($content['form']["target"]) ?>" size="40"/></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"/></td>
</tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_newsletter_fromemail'] ?>:&nbsp;</td>
    <td>
        <table summary="" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td><select name="cform_sendertype" class="v11 width200">
                    <?php
                        echo '<option value="email"' . is_selected('email', $content['form']['sendertype'], 0, 0) . '>' . $BL['be_profile_label_email'] . '</option>';
                        echo '<option value="system"' . is_selected('system', $content['form']['sendertype'], 0, 0) . '>' . $BL['be_cnt_sysadmin_system'] . ': ' . html($phpwcms['SMTP_FROM_EMAIL']) . '</option>';
                        echo $sender_option;
                    ?>
                    </select></td>
                <td>&nbsp;</td>
                <td><input name="cform_sender" type="text" id="cform_sender" class="f11b" style="width:230px" value="<?php echo html($content['form']['sender']) ?>" size="40"/></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"/></td>
</tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_newsletter_fromname'] ?>:&nbsp;</td>
    <td>
        <table summary="" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td><select name="cform_sendernametype" class="v11 width200">
                    <?php
                        echo '<option value="custom"' . is_selected('custom', $content['form']['sendernametype'], 0, 0) . '>' . $BL['be_cnt_ecardform_name'] . '</option>';
                        echo '<option value="system"' . is_selected('system', $content['form']['sendernametype'], 0, 0) . '>' . $BL['be_cnt_sysadmin_system'] . ': ' . html($phpwcms['SMTP_FROM_NAME']) . '</option>';
                        echo $sendername_option;
                    ?>
                    </select></td>
                <td>&nbsp;</td>
                <td><input name="cform_sendername" type="text" id="cform_sendername" class="f11b" style="width:230px" value="<?php echo html($content['form']['sendername']) ?>" size="40"/></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"/></td>
</tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_send_copy_to'] ?>:&nbsp;</td>
    <td>
        <table summary="" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td><input type="checkbox" name="cform_sendcopy" value="1" <?php echo is_checked('1', $content['form']["sendcopy"], 0, 0) ?> title="send copy to selected field"/>&nbsp;</td>
                <td><select name="cform_copyto" class="v11" style="width:180px;">
                        <?php echo $cc_listing; ?>
                    </select></td>
                <td>&nbsp;</td>
                <td><input name="cform_cc" type="text" id="cform_cc" class="f11b" style="width:230px" value="<?php echo html($content['form']['cc']) ?>" size="40"/></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"/></td>
</tr>
<tr>
    <td align="right">&nbsp;</td>
    <td>
        <table summary="" cellpadding="0" cellspacing="0" border="0" bgcolor="#E7E8EB">
            <tr>
                <td><input type="checkbox" name="cform_checktofrom" id="cform_checktofrom" value="1" <?php is_checked(1, $content['form']['checktofrom']) ?> /></td>
                <td class="v10"><label for="cform_checktofrom">&nbsp;<?php echo $BL['be_cnt_field']['checktofrom'] ?></label>&nbsp;
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"/></td>
</tr>
<tr>
    <td align="right">&nbsp;</td>
    <td>
        <table summary="" cellpadding="0" cellspacing="0" border="0" bgcolor="#E7E8EB">
            <tr>
                <td><input type="checkbox" name="cform_ssl" id="cform_ssl" value="1" <?php is_checked(1, $content['form']['ssl']) ?> /></td>
                <td class="v10"><label for="cform_ssl">&nbsp;<?php echo $BL['form_force_ssl'] ?></label>&nbsp;</td>
        </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"/></td>
</tr>
<tr>
    <td align="right" class="chatlist tdtop4"><?php echo $BL['be_cnt_database'] ?>:&nbsp;</td>
    <td><?php

        // check form entries
        if ($content["id"]) {

            $entries = _dbQuery('SELECT COUNT(*) FROM ' . DB_PREPEND . 'phpwcms_formresult WHERE formresult_pid=' . $content['id'], 'COUNT');

            // yepp - available - link to export script
            if ($entries > 0) {

                echo '<div style="float:right;padding-right:10px;">';
                echo "<button onclick=\"window.open('include/inc_act/act_export.php?" . CSRF_GET_TOKEN . "&amp;action=exportformresult&amp;fid=";
                echo $content['id'] . "', 'Zweitfenster');\" class=\"f11b\" style=\"padding: 2px 6px 2px 4px\">";
                echo '<img src="img/icons/small_icon_xls.gif" alt="Excel Sheet" style="position:relative;top:1px;" />&nbsp;';
                echo $BL['be_cnt_download'] . '<span style="font-weight:normal;">&nbsp;(' . $entries . ')</span></button></div>';

            }

        }
        ?>
        <table summary="" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td bgcolor="#E7E8EB"><input type="checkbox" name="cform_savedb" id="cform_savedb" value="1" <?php echo is_checked(1, $content['form']["savedb"], 0, 0) ?> /></td>
                <td class="v10" bgcolor="#E7E8EB"><label for="cform_savedb">&nbsp;<?php echo $BL['be_cnt_formsave_in_db'] ?></label>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"/></td>
            </tr>
            <tr>
                <td bgcolor="#E7E8EB"><input type="checkbox" name="cform_saveprofile" id="cform_saveprofile" value="1" <?php echo is_checked(1, $content['form']["saveprofile"], 0, 0) ?> onchange="this.form.submit();"/></td>
                <td class="v10" bgcolor="#E7E8EB"><label for="cform_saveprofile">&nbsp;<?php echo $BL['be_cnt_formsave_profile'] ?></label>&nbsp; </td>
            </tr>
            <tr>
                <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"/></td>
            </tr>
            <tr>
                <td bgcolor="#E7E8EB"><input type="checkbox" name="cform_tracking_off" id="cform_tracking_off" value="1" <?php echo is_checked(1, $content['form']["formtracking_off"], 0, 0) ?> /> </td>
                <td class="v10" bgcolor="#E7E8EB"><label for="cform_tracking_off">&nbsp;<?php echo $BL['be_cnt_field']['formtracking_off'] ?></label>&nbsp;</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"/></td>
</tr>
<tr>
    <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"/><?php echo $BL['be_admin_tmpl_default'] ?>:&nbsp;</td>
    <td>
        <table summary="" cellpadding="1" cellspacing="0" border="0">
            <tr bgcolor="#E7E8EB">
                <td><input type="radio" name="cform_startup_html" id="cform_startup_html0" value="0"<?php echo is_checked('0', $content['form']["startup_html"], 0, 0) ?> title="Text"/></td>
                <td class="v10"><label for="cform_startup_html0">Text&nbsp;</label>&nbsp;</td>
                <td><input type="radio" name="cform_startup_html" id="cform_startup_html1" value="1"<?php echo is_checked('1', $content['form']["startup_html"], 0, 0) ?> title="HTML"/></td>
                <td class="v10"><label for="cform_startup_html1">HTML&nbsp;</label>&nbsp;</td>
            </tr>
        </table>
        <textarea name="cform_startup" id="cform_startup" rows="5" class="width440 autosize"><?php echo html($content['form']["startup"]) ?></textarea></td>
</tr>
<tr>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"/></td>
</tr>
<tr>
    <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"/><?php echo $BL['be_cnt_onsuccess'] ?>:&nbsp;</td>
    <td>
        <table summary="" cellpadding="0" cellspacing="0" border="0">
            <tr bgcolor="#E7E8EB">
                <td><input type="radio" name="cform_onsuccess_redirect" id="cform_onsuccess_redirect0" value="0"<?php echo is_checked('0', $content['form']["onsuccess_redirect"], 0, 0) ?> title="redirect on success"/></td>
                <td class="v10"><label for="cform_onsuccess_redirect0">Text&nbsp;</label>&nbsp;</td>
                <td><input type="radio" name="cform_onsuccess_redirect" id="cform_onsuccess_redirect2" value="2"<?php echo is_checked('2', $content['form']["onsuccess_redirect"], 0, 0) ?> title="redirect on success"/></td>
                <td class="v10"><label for="cform_onsuccess_redirect2">HTML&nbsp;</label>&nbsp;</td>
                <?php
                if ($for_select != '') {
                    echo '<td style="padding:2px;"><select name="successInfo" id="successInfo" class="v10 width200" ';
                    echo 'onchange="insertAtCursorPos(document.articlecontent.cform_onsuccess, ';
                    echo 'document.articlecontent.successInfo.options[document.articlecontent.successInfo.selectedIndex].value);">';
                    echo $for_select;
                    echo '<option value="{REMOTE_IP}">{REMOTE_IP}</option>' . LF;
                    echo '</select></td>';
                    echo '<td style="padding-right:3px;"><img src="img/button/go04.gif" alt="" width="15" height="15" title="insert field placeholder" border="0" ';
                    echo 'onclick="insertAtCursorPos(document.articlecontent.cform_onsuccess, ';
                    echo 'document.articlecontent.successInfo.options[document.articlecontent.successInfo.selectedIndex].value);" style="margin:3px;" /></td>';
                }
                ?>
                <td bgcolor="#FFFFFF"><img src="img/leer.gif" alt="" width="4" height="23" /></td>
                <td><input type="radio" name="cform_onsuccess_redirect" id="cform_onsuccess_redirect1" value="1"<?php echo is_checked('1', $content['form']["onsuccess_redirect"], 0, 0) ?> title="redirect on success"/></td>
                <td class="v10"><label for="cform_onsuccess_redirect1">Redirect</label>&nbsp;&nbsp;</td>
            </tr>
        </table>
        <textarea name="cform_onsuccess" id="cform_onsuccess" rows="3" class="width440 autosize"><?php echo html($content['form']["onsuccess"]) ?></textarea>
    </td>
</tr>
<tr>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"/></td>
</tr>
<tr>
    <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"/><?php echo $BL['be_cnt_onerror'] ?>:&nbsp;</td>
    <td>
        <table summary="" cellpadding="1" cellspacing="0" border="0">
            <tr bgcolor="#E7E8EB">
                <td><input type="radio" name="cform_onerror_redirect" id="cform_onerror_redirect0" value="0"<?php echo is_checked('0', $content['form']["onerror_redirect"], 0, 0) ?> title="redirect on success"/></td>
                <td class="v10"><label for="cform_onerror_redirect0">Text&nbsp;</label>&nbsp;</td>
                <td><input type="radio" name="cform_onerror_redirect" id="cform_onerror_redirect2" value="2"<?php echo is_checked('2', $content['form']["onerror_redirect"], 0, 0) ?> title="redirect on success"/></td>
                <td class="v10"><label for="cform_onerror_redirect2">HTML&nbsp;</label>&nbsp;</td>
                <td bgcolor="#FFFFFF"><img src="img/leer.gif" alt="" width="4" height="23" /></td>
                <td><input type="radio" name="cform_onerror_redirect" id="cform_onerror_redirect1" value="1"<?php echo is_checked('1', $content['form']["onerror_redirect"], 0, 0) ?> title="redirect on success"/></td>
                <td class="v10"><label for="cform_onerror_redirect1">Redirect</label>&nbsp;&nbsp;
                </td>
            </tr>
        </table>
        <textarea name="cform_onerror" rows="3" class="width440 autosize"><?php echo html($content['form']["onerror"]) ?></textarea>
    </td>
</tr>

<tr>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"/></td>
</tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_optin'] ?>:&nbsp;</td>
     <td><table summary="" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td bgcolor="#E7E8EB"><input type="checkbox" onchange="toggleDoubleOptInOptions();" name="cform_doubleoptin" id="cform_doubleoptin" value="1" <?php echo is_checked(1, $content['form']['doubleoptin'], 0, 0) ?> /></td>
                <td class="v10" bgcolor="#E7E8EB"><label for="cform_doubleoptin">&nbsp;<?php echo $BL['be_cnt_doubleoptin'] ?></label>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
          </tr>
        </table>
    </td>
</tr>
<tr id="form_doubleoptin_1"<?php if(empty($content['form']["doubleoptin"])): ?> style="display:none;"<?php endif; ?>>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
</tr>
<tr id="form_doubleoptin_2"<?php if(empty($content['form']["doubleoptin"])): ?> style="display:none;"<?php endif; ?>>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_recipient'] ?>:&nbsp;</td>
    <td><select name="cform_targettype_doubleoptin" class="v11" style="width:200px">
    <?php
        echo '<option value="email"'. is_selected('email', $content['form']['doubleoptin_targettype'], 0, 0) .'>'.$BL['be_profile_label_email'].'</option>'.LF;
        echo $recipient_option_doubleoptin;
    ?>
    </select></td>
</tr>
<tr id="form_doubleoptin_3"<?php if(empty($content['form']["doubleoptin"])): ?> style="display:none;"<?php endif; ?>>
    <td class="chatlist tdtop10" colspan="1"><?php echo $BL['be_cnt_recipient'].' - '.$BL['be_admin_struct_template'] ?>:&nbsp;</td>
    <td>
        <table summary="" cellpadding="0" cellspacing="0" border="0">
            <tr id="optintemplate1">
                <td colspan="2" class="tdtop5"><table summary="" cellpadding="0" cellspacing="0" border="0" bgcolor="#E7E8EB">
                    <tr>
                        <td><input type="radio" name="cform_template_format_doubleoptin" id="cform_template_text_doubleoptin" value="0"<?php is_checked(0, $content['form']["template_format_doubleoptin"]) ?> onchange="this.form.submit();" /></td>
                        <td class="f10"><label for="cform_template_text_copy">TEXT&nbsp;</label></td>
                        <td><input type="radio" name="cform_template_format_doubleoptin" id="cform_template_html_doubleoptin" value="1"<?php is_checked(1, $content['form']["template_format_doubleoptin"]) ?> onchange="this.form.submit();" /></td>
                        <td class="f10"><label for="cform_template_html_doubleoptin">HTML&nbsp;</label></td>
                        <?php
                        if(!$content['form']["template_format_doubleoptin"] && $for_select !== '') {
                            echo '<td style="padding:2px;"><select name="phc_doubleoptin" id="phc_doubleoptin" class="v10 width200" ';
                            echo 'onchange="insertAtCursorPos(document.articlecontent.cform_template_doubleoptin, ';
                            echo 'document.articlecontent.phc_doubleoptin.options[document.articlecontent.phc_doubleoptin.selectedIndex].value);">';
                            echo $for_select;
                            echo '<option value="{FORM_URL}">{FORM_URL}</option>';
                            echo '<option value="{REMOTE_IP}">{REMOTE_IP}</option>';
                            echo '<option value="{DATE:y/m/d H:i:s}">{DATE:y/m/d H:i:s}</option>';
                            echo '</select></td>';
                            echo '<td><img src="img/button/go04.gif" width="15" height="15" title="insert field placeholder" border="0" ';
                            echo 'onclick="insertAtCursorPos(document.articlecontent.cform_template_doubleoptin, ';
                            echo 'document.articlecontent.phc_doubleoptin.options[document.articlecontent.phc_doubleoptin.selectedIndex].value);" style="margin:3px;" alt="" /></td>';
                        }
                        ?>
                        <td><img src="img/leer.gif" alt="" width="1" height="23" /></td>
                    </tr>
                </table></td>
            </tr>
            <tr id="optintemplate3">
                <td colspan="2" class="tdbottom3"><?php
                    if($content['form']["template_format_doubleoptin"]) {
                        $wysiwyg_editor = array(
                            'value'     => $content['form']["template_doubleoptin"],
                            'field'     => 'cform_template_doubleoptin',
                            'height'    => '200px',
                            'width'     => '100%',
                            'rows'      => '10',
                            'editor'    => $_SESSION["WYSIWYG_EDITOR"],
                            'lang'      => 'en'
                        );
                        include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';
                    } else {

                        echo '<textarea name="cform_template_doubleoptin" id="cform_template_doubleoptin" rows="5" class="code width440 autosize">';
                        echo html($content['form']["template_doubleoptin"]).'</textarea>';

                    }
            ?></td>
            </tr>
        </table>
  </td>
</tr>
<tr id="form_doubleoptin_4"<?php if(empty($content['form']["doubleoptin"])): ?> style="display:none;"<?php endif; ?>>
    <td align="right" class="chatlist tdtop5"><?php echo $BL['be_cnt_onsuccess'] ?>:&nbsp;</td>
    <td>
        <table summary="" cellpadding="0" cellspacing="0" border="0">
            <tr bgcolor="#E7E8EB">
                <td><input type="radio" name="cform_onsuccess_redirect_doubleoptin" id="cform_onsuccess_redirec_doubleoptint0" value="0"<?php echo is_checked('0', $content['form']["onsuccess_redirect"], 0, 0) ?> title="redirect on success" /></td>
                <td class="v10"><label for="cform_onsuccess_redirect0">Text&nbsp;</label>&nbsp;</td>
                <td><input type="radio" name="cform_onsuccess_redirect_doubleoptin" id="cform_onsuccess_redirect_doubleoptin2" value="2"<?php echo is_checked('2', $content['form']["onsuccess_redirect"], 0, 0) ?> title="redirect on success" /></td>
                <td class="v10"><label for="cform_onsuccess_redirect_doubleoptin2">HTML&nbsp;</label>&nbsp;</td>
                <?php
                if($for_select != '') {
                    echo '<td style="padding:2px;"><select name="successInfo_doubleoptin" id="successInfo_doubleoptin" class="v10 width200" ';
                    echo 'onchange="insertAtCursorPos(document.articlecontent.cform_onsuccess_doubleoptin, ';
                    echo 'document.articlecontent.successInfo.options[document.articlecontent.successInfo.selectedIndex].value);">';
                    echo $for_select;
                    echo '<option value="{REMOTE_IP}">{REMOTE_IP}</option>'.LF;
                    echo '</select></td>';
                    echo '<td style="padding-right:3px;"><img src="img/button/go04.gif" alt="" width="15" height="15" title="insert field placeholder" border="0" ';
                    echo 'onclick="insertAtCursorPos(document.articlecontent.cform_onsuccess_doubleoptin, ';
                    echo 'document.articlecontent.successInfo.options[document.articlecontent.successInfo.selectedIndex].value);" style="margin:3px;" /></td>';
                }
                ?>
                <td bgcolor="#FFFFFF"><img src="img/leer.gif" alt="" width="4" height="23" /></td>
                <td><input type="radio" name="cform_onsuccess_redirect" id="cform_onsuccess_redirect1" value="1"<?php echo is_checked('1', $content['form']["onsuccess_redirect_doubleoptin"], 0, 0) ?> title="redirect on success" /></td>
                <td class="v10"><label for="cform_onsuccess_redirect1">Redirect</label>&nbsp;&nbsp;</td>
            </tr>
        </table>
        <textarea name="cform_onsuccess_doubleoptin" id="cform_onsuccess_doubleoptin" rows="3" class="width440 autosize"><?php echo html($content['form']["onsuccess_doubleoptin"]) ?></textarea>
    </td>
</tr>
<tr id="form_doubleoptin_5"<?php if(empty($content['form']["doubleoptin"])): ?> style="display:none;"<?php endif; ?>>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
</tr>
<tr id="form_doubleoptin_6"<?php if(empty($content['form']["doubleoptin"])): ?> style="display:none;"<?php endif; ?>>
    <td align="right" class="chatlist tdtop5"><?php echo $BL['be_cnt_onerror'] ?>:&nbsp;</td>
    <td>
        <table summary="" cellpadding="1" cellspacing="0" border="0">
            <tr bgcolor="#E7E8EB">
                <td><input type="radio" name="cform_onerror_redirect_doubleoptin" id="cform_onerror_redirect_doubleoptin0" value="0"<?php echo is_checked('0', $content['form']["onerror_redirect_doubleoptin"], 0, 0) ?> title="redirect on success" /></td>
                <td class="v10"><label for="cform_onerror_redirect_doubleoptin0">Text&nbsp;</label>&nbsp;</td>
                <td><input type="radio" name="cform_onerror_redirect_doubleoptin" id="cform_onerror_redirect_doubleoptin2" value="2"<?php echo is_checked('2', $content['form']["onerror_redirect_doubleoptin"], 0, 0) ?> title="redirect on success" /></td>
                <td class="v10"><label for="cform_onerror_redirect_doubleoptin2">HTML&nbsp;</label>&nbsp;</td>
                <td bgcolor="#FFFFFF"><img src="img/leer.gif" alt="" width="4" height="23" /></td>
                <td><input type="radio" name="cform_onerror_redirect_doubleoptin" id="cform_onerror_redirect_doubleoptin1" value="1"<?php echo is_checked('1', $content['form']["onerror_redirect_doubleoptin"], 0, 0) ?> title="redirect on success" /></td>
                <td class="v10"><label for="cform_onerror_redirect_doubleoptin1">Redirect</label>&nbsp;&nbsp;</td>
            </tr>
        </table>
        <textarea name="cform_onerror_doubleoptin" rows="3" class="width440 autosize"><?php echo html($content['form']["onerror_doubleoptin"]) ?></textarea>
    </td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td colspan="2">
        <table summary="" cellpadding="0" cellspacing="1" border="0">
            <tr bgcolor="#DAE4ED">
                <td colspan="8"><img src="img/leer.gif" alt="" width="1" height="1"/></td>
            </tr>
            <tr bgcolor="#E7E8EB">
                <td colspan="2" class="chatlist" align="right"><?php echo $BL['be_cnt_reference_basis'] ?>:&nbsp;</td>
                <td colspan="6" style="padding:3px 0 3px 0">
                    <table summary="" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>&nbsp;</td>
                            <?php
                            if (!isset($content['form']["labelpos"])) {
                                $content['form']["labelpos"] = 3;
                                // 0 = default = in front of form field
                                // 1 = above form field
                                // 2 = Custom
                                // 3 = modern DIV based
                            }
                            ?>
                            <td><input type="radio" name="cform_labelpos" id="cform_labelpos3" value="3"<?php echo is_checked(3, $content['form']["labelpos"], 0, 1) ?> /></td>
                            <td><label for="cform_labelpos3"><img src="img/symbole/label_3.gif" width="72" height="22" alt=""/></label></td>
                            <td>&nbsp;&nbsp;</td>
                            <td><input type="radio" name="cform_labelpos" id="cform_labelpos0" value="0"<?php echo is_checked(0, $content['form']["labelpos"], 0, 1) ?> /></td>
                            <td><label for="cform_labelpos0"><img src="img/symbole/label_0.gif" width="70" height="22" alt=""/></label></td>
                            <td>&nbsp;&nbsp;</td>
                            <td><input type="radio" name="cform_labelpos" id="cform_labelpos1" value="1"<?php echo is_checked(1, $content['form']["labelpos"], 0, 1) ?> /></td>
                            <td><label for="cform_labelpos1"><img src="img/symbole/label_1.gif" width="60" height="22" alt=""/></label></td>
                            <td>&nbsp;&nbsp;</td>
                            <td><input type="radio" name="cform_labelpos" id="cform_labelpos2" value="2"<?php echo is_checked(2, $content['form']["labelpos"], 0, 1) ?> /></td>
                            <td><label for="cform_labelpos2"><img src="img/symbole/label_2.gif" width="60" height="22" alt=""/></label></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr bgcolor="#DAE4ED">
                <td colspan="8"><img src="img/leer.gif" alt="" width="1" height="1"/></td>
            </tr>
            <tr>
                <td colspan="8"><img src="img/leer.gif" alt="" width="1" height="3"/></td>
            </tr>
            <tr>
                <td colspan="2" class="chatlist" align="right"><?php echo $BL['be_cnt_form_class'] ?>:&nbsp;</td>
                <td><input type="text" name="cform_class" class="width120" value="<?php echo html($content['form']["class"]) ?>"/></td>
                <td class="chatlist" align="right">&nbsp;<?php echo $BL['be_cnt_label_wrap'] ?>:&nbsp;</td>
                <td colspan="4"><input type="text" name="cform_label_wrap" class="width120" value="<?php echo html($content['form']["label_wrap"]) ?>"/></td>
            </tr>
            <tr>
                <td colspan="2" class="chatlist" align="right"><?php echo $BL['be_cnt_req_mark'] ?>:&nbsp;</td>
                <td><input type="text" name="cform_reqmark" class="width120" value="<?php echo html($content['form']["cform_reqmark"]) ?>"/></td>
                <td class="chatlist" align="right">&nbsp;<?php echo $BL['be_cnt_error_class'] ?>:&nbsp;</td>
                <td colspan="4"><input type="text" name="cform_error_class" class="width120" value="<?php echo html($content['form']["error_class"]) ?>"/></td>
            </tr>
            <tr>
                <td colspan="2" class="chatlist" align="right"><?php echo $BL['be_cnt_function_validate'] ?>:&nbsp;</td>
                <td><input type="text" name="cform_function_validate" class="width120" value="<?php echo html($content['form']["cform_function_validate"]) ?>"/></td>
                <td class="chatlist nowrap" align="right" class="nowrap">
                    <?php echo $BL['be_article_cnt_anchor'] . ' &ndash; ' . $BL['be_cnt_target']; ?>:<input type="checkbox" name="cform_anchor_off" id="cform_anchor_off" value="0"<?php is_checked(0, $content['form']["anchor_off"]) ?> />
                </td>
                <td colspan="4"><input type="text" name="cform_anchor_name" class="width120" value="<?php echo html($content['form']["anchor_name"]) ?>" placeholder="jumpForm<?php echo empty($content["id"]) ? '' : $content["id"]; ?>"/></td>
            </tr>
            <tr>
                <td colspan="8"><img src="img/leer.gif" alt="" width="1" height="3"/></td>
            </tr>
            <tr bgcolor="#DAE4ED">
                <td colspan="8"><img src="img/leer.gif" alt="" width="1" height="1"/></td>
            </tr>
            <tr bgcolor="#E6ECF2">
                <td style="width:30px">&nbsp;</td>
                <td class="chatlist" style="padding: 1px">&nbsp;<?php echo $BL['be_cnt_type'] ?>:</td>
                <td class="chatlist" style="padding: 1px">&nbsp;<?php echo $BL['be_newsletter_name'] ?>:</td>
                <td class="chatlist" style="padding: 1px">&nbsp;<?php echo $BL['be_cnt_label'] ?>:</td>
                <td class="chatlist" title="size/columns" style="padding: 1px">&nbsp;S/C:</td>
                <td class="chatlist" title="maxlength/rows" style="padding: 1px">&nbsp;M/R:</td>
                <td align="center" style="padding: 1px"><img src="img/article/fill_in_here.gif" alt="<?php echo $BL['be_cnt_needed'] ?>" title="<?php echo $BL['be_cnt_needed'] ?>" /></td>
                <td align="center" style="padding: 1px"><img src="img/button/trash_13x13_1.gif" alt="<?php echo $BL['be_cnt_delete'] ?>" title="<?php echo $BL['be_cnt_delete'] ?>" /></td>
            </tr>
            <?php
            if (isset($content['form']["fields"]) && is_array($content['form']["fields"]) && count($content['form']["fields"])) {

                $field_counter = 1;
                $field_max = count($content['form']["fields"]);
                $field_js = array(
                    'showAll' => array(),
                    'hideAll' => array(),
                    'varcharFields' => array(),
                    'longtextFields' => array()
                );
                $field_type_count = array();

                foreach ($content['form']["fields"] as $key => $value) {

                    $field_bg = ($field_counter % 2) ? '' : ' bgcolor="#F3F5F8"';
                    $field_row4 = '';
                    $field_type = $content['form']["fields"][$key]['type'];

                    // generate javascript code part 1
                    $field_js['showAll'][$key] = ' showHide_CntFormfieldRow(\'formRow_' . $field_counter . '\', \'block\'';
                    $field_js['hideAll'][$key] = ' showHide_CntFormfieldRow(\'formRow_' . $field_counter . '\', \'none\'';

                    echo '<tr' . $field_bg . '>';
                    echo '<td align="center" id="formRow_' . $field_counter . '">';
                    echo '<a href="#" onclick="return showHide_CntFormfieldRow(\'formRow_' . $field_counter . '\', \'none\'';

                    if (!isset($field_type_count[$field_type])) {
                        $field_type_count[$field_type] = 0;
                    }

                    // some field specific checks and settings
                    switch ($field_type) {

                        case 'newsletter':      // default hide/show
                            echo ', 5';

                            $field_row4 = '<tr' . $field_bg . ' id="formRow_' . $field_counter . '_5">';
                            $field_row4 .= '<td colspan="2" class="chatlist" align="right" valign="top">&nbsp;<img src="img/leer.gif" width="1" height="15" alt="" />';
                            $field_row4 .= $BL['be_cnt_bid_verifyemail'] . ':&nbsp;</td>';
                            $field_row4 .= '<td colspan="6"><textarea name="cform_field_verifyemail" ';
                            $field_row4 .= 'id="cform_field_verifyemail" rows="5" class="code width340 autosize" wrap="off">';
                            $field_row4 .= html($content['form']['verifyemail']) . '</textarea></td>';
                            $field_row4 .= '</tr>';

                            $field_js['showAll'][$key] .= ', 5';
                            $field_js['hideAll'][$key] .= ', 5';

                            break;

                        case 'text':
                        case 'special':
                        case 'email':
                        case 'password':
                        case 'hidden':
                        case 'select':
                        case 'selectemail':
                        case 'country':
                        case 'radio':
                            // default hide/show
                            if ($content['form']["saveprofile"]) {
                                echo ', 5';

                                $field_row4 = '<tr' . $field_bg . ' id="formRow_' . $field_counter . '_5">';
                                $field_row4 .= '<td colspan="2" class="chatlist" align="right">' . $BL['be_cnt_store_in'] . ':&nbsp;</td>';
                                $field_row4 .= '<td colspan="6" id="cform_field_profile_' . $field_counter . '_td">';

                                if (!empty($content['form']["fields"][$key]['profile']) && isset($content['profile_fields_varchar'][$content['form']["fields"][$key]['profile']])) {

                                    $field_js['varcharFields'][$field_counter] = '<"+"option value=\"' . $content['form']["fields"][$key]['profile'] . '\" selected=\"selected\">';
                                    $field_js['varcharFields'][$field_counter] .= $content['profile_fields_varchar'][$content['form']["fields"][$key]['profile']] . '<"+"/option>';
                                    unset($content['profile_fields_varchar'][$content['form']["fields"][$key]['profile']]);

                                } else {

                                    $field_js['varcharFields'][$field_counter] = '';

                                }

                                $field_row4 .= '</td></tr>';

                                $field_js['showAll'][$key] .= ', 5';
                                $field_js['hideAll'][$key] .= ', 5';
                            }
                            break;

                        case 'textarea':
                        case 'checkbox':
                        case 'checkboxcopy':
                        case 'list':
                            // default hide/show
                            if ($content['form']["saveprofile"]) {
                                echo ', 5';

                                $field_row4 = '<tr' . $field_bg . ' id="formRow_' . $field_counter . '_5">';
                                $field_row4 .= '<td colspan="2" class="chatlist" align="right">' . $BL['be_cnt_store_in'] . ':&nbsp;</td>';
                                $field_row4 .= '<td colspan="6" id="cform_field_profile_' . $field_counter . '_td">';

                                if (!empty($content['form']["fields"][$key]['profile']) && isset($content['profile_fields_longtext'][$content['form']["fields"][$key]['profile']])) {

                                    $field_js['longtextFields'][$field_counter] = '<"+"option value=\"' . $content['form']["fields"][$key]['profile'] . '\" selected=\"selected\">';
                                    $field_js['longtextFields'][$field_counter] .= $content['profile_fields_longtext'][$content['form']["fields"][$key]['profile']] . '<"+"/option>';
                                    unset($content['profile_fields_longtext'][$content['form']["fields"][$key]['profile']]);

                                } else {

                                    $field_js['longtextFields'][$field_counter] = '';
                                }

                                $field_row4 .= '</td></tr>';

                                $field_js['showAll'][$key] .= ', 5';
                                $field_js['hideAll'][$key] .= ', 5';
                            }
                            break;

                        case 'mathspam':
                        case 'recaptcha':
                        case 'recaptchainv':
                            $_ini_values = $content['form']["fields"][$key]['value'];
                            $content['form']["fields"][$key]['value'] = '';

                            // Rewrite for reCAPTCHA v2 and invisible reCAPTCHA
                            if (isset($_ini_values['public_key'])) {
                                if (!isset($_ini_values['site_key'])) {
                                    $_ini_values['site_key'] = $_ini_values['public_key'];
                                }
                                unset($_ini_values['public_key']);
                            }
                            if (isset($_ini_values['private_key'])) {
                                if (!isset($_ini_values['secret_key'])) {
                                    $_ini_values['secret_key'] = $_ini_values['private_key'];
                                }
                                unset($_ini_values['private_key']);
                            }

                            foreach ($_ini_values as $item_key => $item) {
                                $content['form']["fields"][$key]['value'] .= $item_key . ' = ' . $item . LF;
                            }

                            $content['form']["fields"][$key]['value'] = trim($content['form']["fields"][$key]['value']);

                            unset($_ini_values);

                            break;

                    }

                    echo ')"><img src="img/button/arrow_opened.gif" alt="" border="0" /></a>';
                    echo '</td><td>';
                    echo '<select name="cform_field_type[' . $field_counter . ']" class="v10" style="width:140px">';
                    echo '<option value="text"' . is_selected('text', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['text'] . '</option>';
                    echo '<option value="textarea"' . is_selected('textarea', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['textarea'] . '</option>';
                    echo '<option value="special"' . is_selected('special', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['special'] . '</option>';
                    echo '<option value="hidden"' . is_selected('hidden', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['hidden'] . '</option>';
                    echo '<option value="password"' . is_selected('password', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['password'] . '</option>';
                    echo '<option value="email"' . is_selected('email', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['email'] . '</option>';
                    echo '<option value="selectemail"' . is_selected('selectemail', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['selectemail'] . '</option>';
                    echo '<option value="select"' . is_selected('select', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['select'] . '</option>';
                    echo '<option value="country"' . is_selected('country', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['country'] . '</option>';
                    echo '<option value="list"' . is_selected('list', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['list'] . '</option>';
                    echo '<option value="newsletter"' . is_selected('newsletter', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['newsletter'] . '</option>';
                    echo '<option value="checkbox"' . is_selected('checkbox', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['checkbox'] . '</option>';
                    echo '<option value="checkboxcopy"' . is_selected('checkboxcopy', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['checkboxcopy'] . '</option>';
                    echo '<option value="radio"' . is_selected('radio', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['radio'] . '</option>';
                    echo '<option value="upload"' . is_selected('upload', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['upload'] . '</option>';
                    echo '<option value="recaptcha"' . is_selected('recaptcha', $field_type, 0, 0) . ($field_type === 'recaptcha' ? '' : ' class="disable-recaptcha"') . '>' . $BL['be_cnt_field']['recaptcha'] . '</option>';
                    echo '<option value="recaptchainv"' . is_selected('recaptchainv', $field_type, 0, 0) . ($field_type === 'recaptchainv' ? '' : ' class="disable-recaptchainv"') . '>' . $BL['be_cnt_field']['recaptchainv'] . '</option>';
                    echo '<option value="captcha"' . is_selected('captcha', $field_type, 0, 0) . ($field_type === 'captcha' ? '' : ' class="disable-captcha"') . '>' . $BL['be_cnt_field']['captcha'] . '</option>';
                    echo '<option value="captchaimg"' . is_selected('captchaimg', $field_type, 0, 0) . ($field_type === 'captchaimg' ? '' : ' class="disable-captchaimg"') . '>' . $BL['be_cnt_field']['captchaimg'] . '</option>';
                    echo '<option value="mathspam"' . is_selected('mathspam', $field_type, 0, 0) . ($field_type === 'mathspam' ? '' : ' class="disable-mathspam"') . '>' . $BL['be_cnt_field']['mathspam'] . '</option>';
                    echo '<option value="submit"' . is_selected('submit', $field_type, 0, 0) . ($field_type === 'submit' ? '' : ' class="disable-submit"') . '>' . $BL['be_cnt_field']['submit'] . '</option>';
                    echo '<option value="reset"' . is_selected('reset', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['reset'] . '</option>';
                    echo '<option value="break"' . is_selected('break', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['break'] . '</option>';
                    echo '<option value="breaktext"' . is_selected('breaktext', $field_type, 0, 0) . '>' . $BL['be_cnt_field']['breaktext'] . '</option>';
                    echo '</select></td>';
                    echo '<td><input type="text" name="cform_field_name[' . $field_counter . ']" class="width120" value="';
                    echo html($content['form']["fields"][$key]['name']) . '"></td>';
                    echo '<td><input type="text" name="cform_field_label[' . $field_counter . ']" class="width120" value="';
                    echo html($content['form']["fields"][$key]['label']) . '"></td>';
                    echo '<td><input type="text" name="cform_field_size[' . $field_counter . ']" class="width40" value="';
                    echo html($content['form']["fields"][$key]['size']) . '"title="SIZE for Text/COLUMNS for Textarea"></td>';
                    echo '<td><input type="text" name="cform_field_max[' . $field_counter . ']" class="width40" value="';
                    echo html($content['form']["fields"][$key]['max']) . '" title="MAXLENGTH for Text/ROWS for Textarea and List OR B3/B4/B5 for Checkbox/Radio"></td>';
                    echo '<td><input type="checkbox" name="cform_field_required[' . $field_counter . ']"';
                    echo is_checked('1', $content['form']["fields"][$key]['required'], 0, 0) . ' value="1" title="' . $BL['be_cnt_mark_as_req'] . '"></td>';
                    echo '<td><input type="checkbox" name="cform_field_delete[' . $field_counter . ']" value="1" title="' . $BL['be_cnt_mark_as_del'] . '"></td>';
                    echo "</tr>";
                    echo '<tr' . $field_bg . ' id="formRow_' . $field_counter . '_1"><td>&nbsp;</td>';
                    echo '<td valign="top"><table summary="" cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td valign="top">';
                    echo '<img src="img/leer.gif" width="3" height="17" alt="" />';
                    if ($field_counter != 1) {
                        echo '<a href="#" onclick="document.articlecontent.cform_order_' . $field_counter . '.value=\'';
                        echo ($field_counter - 1) . '\';document.articlecontent.cform_order_' . ($field_counter - 1);
                        echo '.value=\'' . $field_counter . '\';document.articlecontent.submit();return false;">';
                        echo '<img src="img/button/sort_top_1.gif" border="0" alt="" width="11" height="11" />';
                        echo '</a>';
                    } else {
                        echo '<img src="img/button/sort_top_0.gif" border="0" alt="" width="11" height="11" />';
                    }
                    echo '<img src="img/leer.gif" width="1" height="1" alt="" />';
                    if ($field_max != $field_counter) {
                        echo '<a href="#" onclick="document.articlecontent.cform_order_' . $field_counter . '.value=\'';
                        echo ($field_counter + 1) . '\';document.articlecontent.cform_order_' . ($field_counter + 1);
                        echo '.value=\'' . $field_counter . '\';document.articlecontent.submit();return false;">';
                        echo '<img src="img/button/sort_bottom_1.gif" border="0" alt="" width="11" height="11" />';
                        echo '</a>';
                    } else {
                        echo '<img src="img/button/sort_bottom_0.gif" border="0" alt="" width="11" height="11" />';
                    }
                    echo '<input type="hidden" name="cform_order[' . $field_counter . ']" id="cform_order_' . $field_counter . '" value="' . $field_counter . '">';
                    echo '</td><td class="chatlist" align="right" valign="top"><a name="field_value_' . $field_counter . '"></a>';
                    echo '<img src="img/leer.gif" width="1" height="15" alt="" />' . $BL['be_cnt_value'] . ':&nbsp;';
                    echo "</td></tr>";
                    // Show "sign up for reCAPCHA API key"
                    if ($field_type === 'recaptcha' || $field_type === 'recaptchainv') {
                        echo '<tr><td colspan="2" class="chatlist" style="padding:5px 0 0 3px"><a href="https://www.google.com/recaptcha/admin" target="_blank" ';
                        echo 'style="white-space:normal;max-width:135px;display:inline-block;">' . $BL['be_cnt_field']['recaptcha_signapikey'] . '</a></td></tr>';
                    }
                    echo "</table></td>";

                    echo '<td colspan="4"><textarea name="cform_field_value[' . $field_counter . ']" ';
                    echo 'id="cform_field_value_' . $field_counter . '" rows="5" class="code width340 autosize">';
                    echo html($content['form']["fields"][$key]['value']) . '</textarea>';

                    echo '</td>';
                    echo '<td colspan="2" valign="bottom"></td>';
                    echo '</tr>';

                    echo '<tr' . $field_bg . ' id="formRow_' . $field_counter . '_2">';
                    echo '<td colspan="2" class="chatlist" align="right">&nbsp;' . $BL['be_newsletter_placeholder'] . ':&nbsp;</td>';
                    echo '<td colspan="6"><input type="text" name="cform_field_placeholder[' . $field_counter . ']" value="';
                    echo empty($content['form']["fields"][$key]['placeholder']) ? '' : html($content['form']["fields"][$key]['placeholder']);
                    echo '" class="width340"></td></tr>';

                    echo '<tr' . $field_bg . ' id="formRow_' . $field_counter . '_3">';
                    echo '<td colspan="2" class="chatlist" align="right">&nbsp;' . $BL['be_cnt_error_text'] . ':&nbsp;</td>';
                    echo '<td colspan="6"><input type="text" name="cform_field_error[' . $field_counter . ']" value="';
                    echo html($content['form']["fields"][$key]['error']) . '" class="width340"';
                    if ($field_type == 'upload') {
                        echo ' title="{MAXLENGTH}, {FILESIZE}, {FILENAME}, {FILEEXT}"';
                    }
                    echo '></td></tr>';
                    echo '<tr' . $field_bg . ' id="formRow_' . $field_counter . '_4">';
                    echo '<td colspan="2" class="chatlist" align="right">&nbsp;' . $BL['be_cnt_css_class'] . ':&nbsp;</td>';
                    echo '<td><input type="text" name="cform_field_class[' . $field_counter . ']" value="';
                    echo html($content['form']["fields"][$key]['class']) . '" class="width120"></td>';
                    echo '<td colspan="5"><table summary="" cellpadding="0" cellspacing="0" border="0"><tr>';
                    echo '<td class="chatlist" align="right">&nbsp;&nbsp;' . $BL['be_cnt_css_style'] . ':&nbsp;</td>';
                    echo '<td><input type="text" name="cform_field_style[' . $field_counter . ']" value="';
                    echo html($content['form']["fields"][$key]['style']) . '" class="width150"></td></tr></table></td>';
                    echo "</tr>";

                    // if field row 4
                    echo $field_row4;

                    echo '<tr bgcolor="#DAE4ED"><td colspan="8"><img src="img/leer.gif" width="1" height="1" alt="" /></td></tr>';

                    // generate javascript code part 2
                    $field_js['showAll'][$key] .= ');';
                    $field_js['hideAll'][$key] .= ');';

                    $field_counter++;
                    $field_type_count[$field_type]++;
                }

            }

            ?>
            <tr bgcolor="#E7E8EB">
                <td>&nbsp;</td>
                <td><select name="cform_field_type[0]" class="v10" style="width:140px" onchange="setFieldValue(this);">
                        <option value="text"><?php echo $BL['be_cnt_field']['text'] ?></option>
                        <option value="textarea"><?php echo $BL['be_cnt_field']['textarea'] ?></option>
                        <option value="special"><?php echo $BL['be_cnt_field']['special'] ?></option>
                        <option value="hidden"><?php echo $BL['be_cnt_field']['hidden'] ?></option>
                        <option value="password"><?php echo $BL['be_cnt_field']['password'] ?></option>
                        <option value="email"><?php echo $BL['be_cnt_field']['email'] ?></option>
                        <option value="selectemail"><?php echo $BL['be_cnt_field']['selectemail'] ?></option>
                        <option value="select"><?php echo $BL['be_cnt_field']['select'] ?></option>
                        <option value="country"><?php echo $BL['be_cnt_field']['country'] ?></option>
                        <option value="list"><?php echo $BL['be_cnt_field']['list'] ?></option>
                        <?php if (empty($for_newsletter)): ?>
                            <option value="newsletter"><?php echo $BL['be_cnt_field']['newsletter'] ?></option>
                        <?php endif; ?>
                        <option value="checkbox"><?php echo $BL['be_cnt_field']['checkbox'] ?></option>
                        <option value="checkboxcopy"><?php echo $BL['be_cnt_field']['checkboxcopy'] ?></option>
                        <option value="radio"><?php echo $BL['be_cnt_field']['radio'] ?></option>
                        <option value="upload"><?php echo $BL['be_cnt_field']['upload'] ?></option>
                        <?php if (!defined('RECAPTCHA_API_SERVER')): ?>
                            <?php if (empty($field_type_count['recaptcha'])): ?>
                                <option value="recaptcha"><?php echo $BL['be_cnt_field']['recaptcha'] ?></option>
                            <?php endif; ?>
                            <?php if (empty($field_type_count['recaptchainv'])): ?>
                                <option value="recaptchainv"><?php echo $BL['be_cnt_field']['recaptchainv'] ?></option>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (empty($field_type_count['captcha'])): ?>
                            <option value="captcha"><?php echo $BL['be_cnt_field']['captcha'] ?></option>
                        <?php endif; ?>
                        <?php if (empty($field_type_count['captchaimg'])): ?>
                            <option value="captchaimg"><?php echo $BL['be_cnt_field']['captchaimg'] ?></option>
                        <?php endif; ?>
                        <?php if (empty($field_type_count['mathspam'])): ?>
                            <option value="mathspam"><?php echo $BL['be_cnt_field']['mathspam'] ?></option>
                        <?php endif; ?>
                        <?php if (empty($field_type_count['submit'])): ?>
                            <option value="submit"><?php echo $BL['be_cnt_field']['submit'] ?></option>
                        <?php endif; ?>
                        <option value="reset"><?php echo $BL['be_cnt_field']['reset'] ?></option>
                        <option value="break"><?php echo $BL['be_cnt_field']['break'] ?></option>
                        <option value="breaktext"><?php echo $BL['be_cnt_field']['breaktext'] ?></option>
                    </select></td>
                <td><input type="text" name="cform_field_name[0]" class="width120"/></td>
                <td><input type="text" name="cform_field_label[0]" class="width120"/></td>
                <td><input type="text" name="cform_field_size[0]" class="width40" title="SIZE for Text/COLUMNS for Textarea"/></td>
                <td><input type="text" name="cform_field_max[0]" class="width40" title="MAXLENGTH for Text/ROWS for Textarea and List OR B3/B4/B5 for Checkbox/Radio"/></td>
                <td><input type="checkbox" name="cform_field_required[0]" value="1" title="mark as required field"/></td>
                <td>&nbsp;<input type="hidden" name="cform_order[0]" value="<?php echo $field_counter ?>"/></td>
            </tr>
            <tr bgcolor="#E7E8EB">
                <td colspan="2" class="chatlist" valign="top" align="right"><a name="field_value_0" id="field_value_0"></a>&nbsp;<img src="img/leer.gif" alt="" width="1" height="15"/><?php echo $BL['be_cnt_value'] ?>:&nbsp;</td>
                <td colspan="4"><textarea name="cform_field_value[0]" id="cform_field_value_0" rows="5" class="code width340 autosize"></textarea></td>
                <td colspan="2" valign="bottom"></td>
            </tr>
            <tr bgcolor="#E7E8EB">
                <td colspan="2" class="chatlist" align="right">&nbsp;<?php echo $BL['be_newsletter_placeholder'] ?>:&nbsp;</td>
                <td colspan="6"><input type="text" name="cform_field_placeholder[0]" class="width340"/></td>
            </tr>
            <tr bgcolor="#E7E8EB">
                <td colspan="2" class="chatlist" align="right">&nbsp;<?php echo $BL['be_cnt_error_text'] ?>:&nbsp;</td>
                <td colspan="6"><input type="text" name="cform_field_error[0]" class="width340"/></td>
            </tr>
            <tr bgcolor="#E7E8EB">
                <td colspan="2" class="chatlist" align="right">&nbsp;<?php echo $BL['be_cnt_css_class'] ?>:&nbsp;</td>
                <td><input type="text" name="cform_field_class[0]" class="width120" /></td>
                <td colspan="5">
                    <table summary="" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="chatlist" align="right">&nbsp;&nbsp;<?php echo $BL['be_cnt_css_style'] ?>:&nbsp;</td>
                            <td class="width120"><input type="text" name="cform_field_style[0]" class="width150"/></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr bgcolor="#DAE4ED">
                <td colspan="8"><img src="img/leer.gif" alt="" width="1" height="1"/></td>
            </tr>
            <tr>
                <td colspan="8"><img src="img/leer.gif" alt="" width="1" height="2"/></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td colspan="6"><input type="submit" value="<?php echo $BL['be_article_cnt_button1'] ?>" class="v09"/></td>
            </tr>

        </table><?php

        if (!empty($field_counter) && $field_counter > 1) {

            echo '<script type="text/javascript">' . LF;

            echo 'function hideAllFormFields() {' . LF;
            echo implode(LF, $field_js['hideAll']);
            echo LF . '}' . LF;

            echo 'function showAllFormFields() {' . LF;
            echo implode(LF, $field_js['showAll']);
            echo LF . '}' . LF . LF;

            echo 'hideAllFormFields();' . LF . LF;

            // set options lists
            if ($content['form']["saveprofile"]) {

                $field_js['options'] = '';
                foreach ($content['profile_fields_varchar'] as $fieldKey => $fieldValue) {
                    $field_js['options'] .= '<"+"option value=\"' . $fieldKey . '\">' . $fieldValue . '<"+"/option>';
                }

                foreach ($field_js['varcharFields'] as $tdID => $tdIDvalue) {

                    $field_value = 'document.getElementById("cform_field_profile_' . $tdID . '_td").innerHTML = "';
                    $field_value .= '<"+"select name=\"cform_field_profile[' . $tdID . ']\" id=\"cform_field_profile_' . $tdID . '\" class=\"v10\">';
                    $field_value .= '<"+"option value=\"\">-<"+"/option>';
                    $field_value .= $tdIDvalue;
                    $field_value .= $field_js['options'];
                    $field_value .= '<"+"/select>";' . LF;

                    echo $field_value;

                }

                $field_js['options'] = '';
                foreach ($content['profile_fields_longtext'] as $fieldKey => $fieldValue) {
                    $field_js['options'] .= '<"+"option value=\"' . $fieldKey . '\">' . $fieldValue . '<"+"/option>';
                }

                foreach ($field_js['longtextFields'] as $tdID => $tdIDvalue) {

                    $field_value = 'document.getElementById("cform_field_profile_' . $tdID . '_td").innerHTML = "';
                    $field_value .= '<"+"select name=\"cform_field_profile[' . $tdID . ']\" id=\"cform_field_profile_' . $tdID . '\" class=\"v10\">';
                    $field_value .= '<"+"option value=\"\">-<"+"/option>';
                    $field_value .= $tdIDvalue;
                    $field_value .= $field_js['options'];
                    $field_value .= '<"+"/select>";' . LF;

                    echo $field_value;

                }
            }

            echo ' var i; ';

            foreach (array('recaptcha', 'recaptchainv', 'captcha', 'captchaimg', 'mathspam', 'submit') as $field_type) {

                if (isset($field_type_count[$field_type])) {
                    echo ' var options_' . $field_type . ' = document.getElementsByClassName("disable-' . $field_type . '"); ';
                    echo ' for(i=0; i < options_' . $field_type . '.length; i++){options_' . $field_type . '[i].disabled=true;} ';
                }

            }

            echo LF . '</script>';
        }

        ?></td>
</tr>
<tr>
    <td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"/></td>
</tr>
<tr>
    <td class="chatlist" colspan="2"><?php echo $BL['be_cnt_recipient'] . ' - ' . $BL['be_admin_struct_template'] ?>:&nbsp;</td>
</tr>
<tr>
    <td colspan="2">
        <table summary="" cellpadding="0" cellspacing="0" border="0" bgcolor="#E7E8EB" style="margin-top:3px;">
            <tr>
                <td><input type="radio" name="cform_template_format" id="cform_template_text" value="0"<?php is_checked('0', $content['form']["template_format"]) ?> onchange="this.form.submit();"/></td>
                <td class="f10"><label for="cform_template_text">TEXT&nbsp;&nbsp;</label></td>
                <td><input type="radio" name="cform_template_format" id="cform_template_html" value="1"<?php is_checked('1', $content['form']["template_format"]) ?> onchange="this.form.submit();"/></td>
                <td class="f10"><label for="cform_template_html">HTML&nbsp;</label></td>
                <?php
                if (!$content['form']["template_format"] && $for_select != '') {
                    echo '<td style="padding:2px;"><select name="ph" id="ph" class="v10 width200" ';
                    echo 'onchange="insertAtCursorPos(document.articlecontent.cform_template, ';
                    echo 'document.articlecontent.ph.options[document.articlecontent.ph.selectedIndex].value);">';
                    echo $for_select;
                    echo '<option value="{FORM_URL}">{FORM_URL}</option>';
                    echo '<option value="{REMOTE_IP}">{REMOTE_IP}</option>';
                    echo '<option value="{DATE:y/m/d H:i:s}">{DATE:y/m/d H:i:s}</option>';
                    echo '</select></td>';
                    echo '<td><img src="img/button/go04.gif" width="15" height="15" title="insert field placeholder" border="0" ';
                    echo 'onclick="insertAtCursorPos(document.articlecontent.cform_template, ';
                    echo 'document.articlecontent.ph.options[document.articlecontent.ph.selectedIndex].value);" style="margin:3px;" alt="" /></td>';
                }
                ?>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"/></td>
</tr>
<tr>
    <td colspan="2" class="tdbottom3"><?php
        if ($content['form']["template_format"]) {
            $wysiwyg_editor = array(
                'value' => $content['form']["template"],
                'field' => 'cform_template',
                'height' => '200px',
                'width' => '100%',
                'rows' => '10',
                'editor' => $_SESSION["WYSIWYG_EDITOR"],
                'lang' => 'en'
            );
            include PHPWCMS_ROOT . '/include/inc_lib/wysiwyg.editor.inc.php';
        } else {

            echo '<textarea name="cform_template" id="cform_template" rows="5" class="code width540 autosize">';
            echo html($content['form']["template"]) . '</textarea>';

        }

        ?></td>
</tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['php_function'] ?>:&nbsp;</td>
    <td><input name="cform_function_to" type="text" id="cform_function_to" class="v11 width200" value="<?php echo html($content['form']['function_to']) ?>" size="40"/></td>
</tr>
<!-- copy mail template //-->
<tr>
    <td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"/></td>
</tr>
<tr>
    <td class="chatlist" colspan="2"><?php echo $BL['be_cnt_send_copy_to'].' - '.$BL['be_admin_struct_template'] ?>:&nbsp;</td>
</tr>
<tr>
    <td colspan="2" class="tdtop3">
        <table summary="" cellpadding="0" cellspacing="0" border="0" bgcolor="#E7E8EB">
            <tr>
                <td><input type="checkbox" name="cform_template_equal" id="cform_template_equal" value="1"<?php is_checked(1, $content['form']["template_equal"]) ?> onchange="showhidecopy();"/></td>
                <td class="v10"><label for="cform_template_equal">&nbsp;= <?php echo $BL['be_cnt_recipient'] . ' - ' . $BL['be_admin_struct_template'] ?>&nbsp;&nbsp;</label></td>
            </tr>
        </table>
        <script type="text/javascript">

            var showhidecopy = function () {
                var display_style = document.getElementById('cform_template_equal').checked ? 'none' : '';
                document.getElementById('copytemplate1').style.display = display_style;
                document.getElementById('copytemplate2').style.display = display_style;
                document.getElementById('copytemplate3').style.display = display_style;
            };

            document.addEventListener("DOMContentLoaded", showhidecopy);

        </script>
    </td>
</tr>
<tr id="copytemplate1">
    <td colspan="2" class="tdtop3">
        <table summary="" cellpadding="0" cellspacing="0" border="0" bgcolor="#E7E8EB">
            <tr>
                <td><input type="radio" name="cform_template_format_copy" id="cform_template_text_copy" value="0"<?php is_checked(0, $content['form']["template_format_copy"]) ?> onchange="this.form.submit();"/></td>
                <td class="f10"><label for="cform_template_text_copy">TEXT&nbsp;&nbsp;</label></td>
                <td><input type="radio" name="cform_template_format_copy" id="cform_template_html_copy" value="1"<?php is_checked(1, $content['form']["template_format_copy"]) ?> onchange="this.form.submit();"/></td>
                <td class="f10"><label for="cform_template_html_copy">HTML&nbsp;</label></td>
                <?php
                if (!$content['form']["template_format_copy"] && $for_select != '') {
                    echo '<td style="padding:2px;"><select name="phc" id="phc" class="v10 width200" ';
                    echo 'onchange="insertAtCursorPos(document.articlecontent.cform_template_copy, ';
                    echo 'document.articlecontent.phc.options[document.articlecontent.phc.selectedIndex].value);">';
                    echo $for_select;
                    echo '<option value="{FORM_URL}">{FORM_URL}</option>';
                    echo '<option value="{REMOTE_IP}">{REMOTE_IP}</option>';
                    echo '<option value="{DATE:y/m/d H:i:s}">{DATE:y/m/d H:i:s}</option>';
                    echo '</select></td>';
                    echo '<td><img src="img/button/go04.gif" width="15" height="15" title="insert field placeholder" border="0" ';
                    echo 'onclick="insertAtCursorPos(document.articlecontent.cform_template_copy, ';
                    echo 'document.articlecontent.phc.options[document.articlecontent.phc.selectedIndex].value);" style="margin:3px;" alt="" /></td>';
                }
                ?>
            </tr>
        </table>
    </td>
</tr>
<tr id="copytemplate2">
    <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"/></td>
</tr>
<tr id="copytemplate3">
    <td colspan="2" class="tdbottom3"><?php
        if ($content['form']["template_format_copy"]) {
            $wysiwyg_editor = array(
                'value' => $content['form']["template_copy"],
                'field' => 'cform_template_copy',
                'height' => '200px',
                'width' => '100%',
                'rows' => '10',
                'editor' => $_SESSION["WYSIWYG_EDITOR"],
                'lang' => 'en'
            );
            include PHPWCMS_ROOT . '/include/inc_lib/wysiwyg.editor.inc.php';
        } else {

            echo '<textarea name="cform_template_copy" id="cform_template_copy" rows="5" class="code width540 autosize">';
            echo html($content['form']["template_copy"]) . '</textarea>';

        }
    ?></td>
</tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['php_function'] ?>:&nbsp;</td>
    <td><input name="cform_function_cc" type="text" id="cform_function_cc" class="v11 width200" value="<?php echo html($content['form']['function_cc']) ?>" size="40"/></td>
</tr>
<tr>
    <td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"/></td>
</tr>
<tr>
    <td class="chatlist" colspan="2" style="padding-bottom:3px;"><a name="anchor_customform" id="anchor_customform"></a><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
</tr>
<?php

if ($for_select_2 != '') {
    echo '<tr><td colspan="2"><table summary="" cellpadding="0" cellspacing="0" border="0" bgcolor="#E7E8EB"><tr><td style="padding:2px;">';
    echo '<select name="ph1" id="ph1" class="v10 width200" ';
    echo 'onchange="insertAtCursorPos(document.articlecontent.cform_customform, ';
    echo 'document.articlecontent.ph1.options[document.articlecontent.ph1.selectedIndex].value);">';
    echo $for_select_2 . '</select></td>';
    echo '<td><img src="img/button/go04.gif" width="15" height="15" title="insert field placeholder" border="0" ';
    echo 'onclick="insertAtCursorPos(document.articlecontent.cform_customform, ';
    echo 'document.articlecontent.ph1.options[document.articlecontent.ph1.selectedIndex].value);" style="margin:3px;" alt="" />';
    echo '</td></tr></table></td></tr>';
}

?>
<tr>
    <td colspan="2" class="tdbottom3">
        <textarea name="cform_customform" id="cform_customform" rows="5" class="code width540 autosize"><?php echo html($content['form']["customform"]) ?></textarea>
        <script type="text/javascript">
            function toggleDoubleOptInOptions() {
                var display_style = document.getElementById('cform_doubleoptin').checked ? '' : 'none';
                document.getElementById('form_doubleoptin_1').style.display = display_style;
                document.getElementById('form_doubleoptin_2').style.display = display_style;
                document.getElementById('form_doubleoptin_3').style.display = display_style;
                document.getElementById('form_doubleoptin_4').style.display = display_style;
                document.getElementById('form_doubleoptin_5').style.display = display_style;
                document.getElementById('form_doubleoptin_6').style.display = display_style;
            };
        </script>
    </td>
</tr>