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

// CP Form

initAceEditor();

$BL['be_cnt_field'] = array_merge(
    array(
        "text" => 'text (single-line)',
        "email" => 'email',
        "tel" => 'telephone',
        "url" => 'url (web address)',
        "number" => 'number',
        "date" => 'date',
        "time" => 'time',
        "color" => 'color picker',
        "range" => 'range slider',
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
        "special" => 'text (special)',
        "captchaimg" => 'captcha image',
        "captcha" => 'captcha code',
        "newsletter" => 'newsletter',
        "selectemail" => 'select email menu',
        "country" => 'select country menu',
        "mathspam" => 'math spam protect',
        "summing" => 'summing',
        "subtract" => 'subtract',
        "divide" => 'divide',
        "multiply" => 'multiply',
        "calculation" => 'calculation:',
        "formtracking_off" => 'disable form tracking',
        "checktofrom" => 'email of recipient must be different from sender',
        "recaptcha" => 'reCAPTCHA',
        "recaptcha_signapikey" => 'Sign up for a reCAPTCHA API key',
        "recaptchainv" => 'Invisible reCAPTCHA'
    ),
    $BL['be_cnt_field']
);

$field_counter = 0;
$BE['BODY_CLOSE']['custom_js'] = '<script type="text/javascript">
function initMathSpam(idx) {
    const el = document.getElementById("cform_field_value_" + idx);
    if (el) {
        el.value = "+ = ' .
        js_singlequote($BL['be_cnt_field']['summing']) . '\n- = ' .
        js_singlequote($BL['be_cnt_field']['subtract']) . '\n* = ' .
        js_singlequote($BL['be_cnt_field']['multiply']) . '\n: = ' .
        js_singlequote($BL['be_cnt_field']['divide']) . '\ncalc = ' .
        js_singlequote($BL['be_cnt_field']['calculation']) . '";
    }
}

$(function() {
    $(document).on("change", "select[name^=\'cform_field_type\']", function() {
        if (this.value === "mathspam") {
            const m = this.name.match(/\[(\w+)\]/);
            if (m && m[1]) {
                initMathSpam(m[1]);
            }
        }
    });

    function reindexSortableFields() {
        let i = 1;
        $("#sortable-list li.sortme").each(function() {
            const orderInput = $(this).find("input[name^=\'cform_order\']");
            if (orderInput.length) {
                orderInput.val(i);
                i++;
            }
        });
    }

    var el = document.getElementById("sortable-list");
    if (el) {
        new Sortable(el, {
            handle: ".handle",
            animation: 150,
            ghostClass: "sortable-ghost",
            chosenClass: "sortable-chosen",
            dragClass: "sortable-drag",
            scroll: true,
            onEnd: function() {
                reindexSortableFields();
            }
        });
    }

    // Dynamic addition of new field cards
    var newFieldCounter = 1;
    $(document).on("click", "#btn-add-more-fields, .btn-add-field-trigger", function(e) {
        e.preventDefault();
        var template = document.getElementById("cform-new-field-template");
        if (!template) return;
        var clone = template.content ? template.content.cloneNode(true) : $(template.innerHTML)[0];
        var tempDiv = document.createElement("div");
        tempDiv.appendChild(clone);
        var html = tempDiv.innerHTML;
        var key = "new_" + newFieldCounter + "_" + Date.now();
        var orderVal = $("#sortable-list li.sortme").length + 1;
        html = html.replace(/__KEY__/g, key).replace(/__ORDER__/g, orderVal);
        newFieldCounter++;
        var $newLi = $(html);
        $("#sortable-list").append($newLi);
        reindexSortableFields();
        if (typeof $.fn.tooltip === "function") {
            $newLi.find("[data-bs-toggle=\'tooltip\']").tooltip();
        }
        $newLi.find("input[name^=\'cform_field_name\']").focus();
    });

    var confirmMsgTmpl = \'' . js_singlequote(html_entity_decode($BL['be_admin_custom_cpt_delete_field_confirm'] ?? 'Really delete field "%s"?', ENT_QUOTES | ENT_HTML5, 'ISO-8859-1')) . '\';
    var confirmMsgSimple = \'' . js_singlequote(html_entity_decode($BL['be_admin_custom_cpt_delete_field_confirm_simple'] ?? 'Really delete this field?', ENT_QUOTES | ENT_HTML5, 'ISO-8859-1')) . '\';

    function getFieldConfirmMsg($container) {
        var name = ($container.find("input[name^=\'cform_field_name\']").val() || "").trim();
        var label = ($container.find("input[name^=\'cform_field_label\']").val() || "").trim();
        var displayName = label ? (name ? label + " [" + name + "]" : label) : (name ? "[" + name + "]" : "");
        return displayName ? confirmMsgTmpl.replace("%s", displayName) : confirmMsgSimple;
    }

    $(document).on("click", ".btn-remove-new-field", function(e) {
        e.preventDefault();
        var $card = $(this).closest(".new-field-card");
        var msg = getFieldConfirmMsg($card);
        if (typeof bsConfirmDanger === "function") {
            bsConfirmDanger(msg, function() {
                $card.remove();
                reindexSortableFields();
            });
        } else if (confirm(msg)) {
            $card.remove();
            reindexSortableFields();
        }
    });

    $(document).on("click", ".btn-delete-form-field", function(e) {
        e.preventDefault();
        var $btn = $(this);
        var $li = $btn.closest("li.sortme");
        var $delInput = $li.find("input[name^=\'cform_field_delete\']");
        var isMarked = $li.hasClass("bg-danger-subtle") || $li.hasClass("border-danger");

        if (isMarked) {
            $li.removeClass("bg-danger-subtle border-danger opacity-50");
            $btn.removeClass("active");
            $delInput.prop("checked", false);
        } else {
            var msg = getFieldConfirmMsg($li);
            var doMark = function() {
                $li.addClass("bg-danger-subtle border-danger opacity-50");
                $btn.addClass("active");
                $delInput.prop("checked", true);
            };
            if (typeof bsConfirmDanger === "function") {
                bsConfirmDanger(msg, doMark);
            } else if (confirm(msg)) {
                doMark();
            }
        }
    });

    // Persist active form tab across submissions / refreshes
    var activeFormTab = sessionStorage.getItem("phpwcms_cform_active_tab");
    if (activeFormTab && $("#cform-tabs a[href=\'" + activeFormTab + "\']").length) {
        $("#cform-tabs a[href=\'" + activeFormTab + "\']").tab("show");
    }
    $("#cform-tabs a[data-bs-toggle=\'tab\']").on("shown.bs.tab", function(e) {
        sessionStorage.setItem("phpwcms_cform_active_tab", $(e.target).attr("href"));
        window.phpwcmsAceEditors?.forEach(function(editor) {
            editor.resize();
        });
    });
});
</script>';

if (empty($content['form']) || !is_array($content['form'])) {
    $content['form'] = array();
}

// CSPRNG-generated API key (16 hex chars) — never the mt_rand-based generic_string()
try {
    $content['direct_download_apikey'] = bin2hex(random_bytes(8));
} catch (Exception $e) {
    $content['direct_download_apikey'] = generic_string(16);
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
        'copyto' => '',
        'sendcopy' => 0,
        'onsuccess_redirect' => 0,
        'onsuccess' => '',
        'onerror_redirect' => 0,
        'onerror' => '',
        'template_format' => 0,
        'template' => '',
        'template_format_copy' => 0,
        'template_copy' => '',
        'template_equal' => 1,
        'customform' => '',
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
        'onerror_redirect_doubleoptin' => 0,
        'direct_download' => 0,
        'direct_download_apikey' => $content['direct_download_apikey'],
        'novalidate' => 0,
    ),
    $content['form']
);

$content['profile_fields'] = array(
    'title' => $BL['be_profile_label_title'],
    'firstname' => $BL['be_profile_label_firstname'],
    'lastname' => $BL['be_profile_label_name'],
    'company' => $BL['be_profile_label_company'],
    'street' => $BL['be_profile_label_street'],
    'add' => $BL['be_profile_label_add'],
    'city' => $BL['be_profile_label_city'],
    'zip' => $BL['be_profile_label_zip'],
    'region' => $BL['be_profile_label_state'],
    'country' => $BL['be_profile_label_country'],
    'fon' => $BL['be_profile_label_phone'],
    'fax' => $BL['be_profile_label_fax'],
    'mobile' => $BL['be_profile_label_cellphone'],
    'signature' => $BL['be_profile_label_signature'],
    'notes' => $BL['be_profile_label_notes'],
    'prof' => $BL['be_profile_label_profession'],
    'newsletter' => $BL['be_profile_label_newsletter'],
    'website' => $BL['be_profile_label_website'],
    'gender' => $BL['be_profile_label_gender'],
    'birthday' => $BL['be_profile_label_birthday'],
    'varchar1' => $BL['be_cnt_field']['text'].' 1',
    'varchar2' => $BL['be_cnt_field']['text'].' 2',
    'varchar3' => $BL['be_cnt_field']['text'].' 3',
    'varchar4' => $BL['be_cnt_field']['text'].' 4',
    'varchar5' => $BL['be_cnt_field']['text'].' 5',
    'text1' => $BL['be_cnt_field']['textarea'].' 1',
    'text2' => $BL['be_cnt_field']['textarea'].' 2',
    'text3' => $BL['be_cnt_field']['textarea'].' 3'
);

$content['profile_fields_varchar'] = array(
    'title' => $BL['be_profile_label_title'],
    'firstname' => $BL['be_profile_label_firstname'],
    'lastname' => $BL['be_profile_label_name'],
    'company' => $BL['be_profile_label_company'],
    'street' => $BL['be_profile_label_street'],
    'add' => $BL['be_profile_label_add'],
    'city' => $BL['be_profile_label_city'],
    'zip' => $BL['be_profile_label_zip'],
    'region' => $BL['be_profile_label_state'],
    'country' => $BL['be_profile_label_country'],
    'fon' => $BL['be_profile_label_phone'],
    'fax' => $BL['be_profile_label_fax'],
    'mobile' => $BL['be_profile_label_cellphone'],
    'email' => $BL['be_profile_label_email'],
    'password' => $BL['be_cnt_field']['password'],
    'signature' => $BL['be_profile_label_signature'],
    'prof' => $BL['be_profile_label_profession'],
    'website' => $BL['be_profile_label_website'],
    'gender' => $BL['be_profile_label_gender'],
    'varchar1' => $BL['be_cnt_field']['text'].' 1',
    'varchar2' => $BL['be_cnt_field']['text'].' 2',
    'varchar3' => $BL['be_cnt_field']['text'].' 3',
    'varchar4' => $BL['be_cnt_field']['text'].' 4',
    'varchar5' => $BL['be_cnt_field']['text'].' 5'
);
$content['profile_fields_longtext'] = array(
    'notes' => $BL['be_profile_label_notes'],
    'text1' => $BL['be_cnt_field']['textarea'].' 1',
    'text2' => $BL['be_cnt_field']['textarea'].' 2',
    'text3' => $BL['be_cnt_field']['textarea'].' 3'
);

$for_select = '';
$for_select_2 = '';

// always disable switching content part for form - too complex settings and better to safe the user for himself
$BE['BODY_CLOSE'][] = '<script type="text/javascript">const targetCtype = document.getElementById("target_ctype"); if (targetCtype) targetCtype.disabled = true;</script>';

$cc_listing         = '';
$recipient_option   = '';
$recipient_option_doubleoptin = '';
$sender_option      = '';
$sendername_option  = '';
$subject_option     = '';

if(isset($content['form']["fields"]) && is_array($content['form']["fields"]) && count($content['form']["fields"])) {
    foreach($content['form']["fields"] as $key => $value) {

        $for_copy           = false;
        $for_sendername     = false;
        $for_email          = false;
        $for_placeholder    = true;
        $for_subject        = false;
        $for_newsletter     = false;
        $for_name           = html($content['form']["fields"][$key]['name']);

        switch($content['form']["fields"][$key]['type']) {

            case 'text':
            case 'tel':
            case 'url':
            case 'number':
            case 'date':
            case 'time':
            case 'color':
            case 'range':
                $for_copy       = true;
                $for_sendername = true;
                $for_subject    = true;
                break;

            case 'email':
                $for_copy       = true;
                $for_email      = true;
                $for_sendername = true;
                break;

            case 'selectemail':
                $for_copy  = true;
                $for_email = true;
                break;

            case 'hidden':
                $for_copy    = true;
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

        if($for_subject) {

            $subject_option .= '    <option value="formfield_'.$for_name.'"';
            $subject_option .= is_selected($content['form']['subjectselect'], 'formfield_'.$content['form']['fields'][$key]['name'], 0, 0);
            $subject_option .= '>'.$BL['be_cnt_guestbook_form'].': '.$for_name.'</option>'.LF;

        }

        if($for_copy) {

            $cc_listing .= '    <option value="'.$for_name.'"';
            $cc_listing .= is_selected($content['form']["copyto"], $content['form']['fields'][$key]['name'], 0, 0);
            $cc_listing .= '>'.$for_name.'</option>'.LF;

            if($for_email) {

                $recipient_option .= '  <option value="emailfield_'.$for_name.'"';
                $recipient_option .= is_selected($content['form']['targettype'], 'emailfield_'.$content['form']['fields'][$key]['name'], 0, 0);
                $recipient_option .= '>'.$BL['be_cnt_guestbook_form'].': '.$for_name.'</option>'.LF;

                $recipient_option_doubleoptin .= '  <option value="emailfield_'.$for_name.'"';
                $recipient_option_doubleoptin .= is_selected($content['form']['doubleoptin_targettype'], 'emailfield_'.$content['form']['fields'][$key]['name'], 0, 0);
                $recipient_option_doubleoptin .= '>'.$BL['be_cnt_guestbook_form'].': '.$for_name.'</option>';

                $sender_option .= ' <option value="emailfield_'.$for_name.'"';
                $sender_option .= is_selected($content['form']['sendertype'], 'emailfield_'.$content['form']['fields'][$key]['name'], 0, 0);
                $sender_option .= '>'.$BL['be_cnt_guestbook_form'].': '.$for_name.'</option>'.LF;

            }

            if($for_sendername) {

                $sendername_option .= ' <option value="formfield_'.$for_name.'"';
                $sendername_option .= is_selected($content['form']['sendernametype'], 'formfield_'.$content['form']['fields'][$key]['name'], 0, 0);
                $sendername_option .= '>'.$BL['be_cnt_guestbook_form'].': '.$for_name.'</option>'.LF;

            }

        }

        // parallel building of the placeholder tag menu for the template
        switch($content['form']["fields"][$key]['type']) {
            case 'submit':
            case 'reset':
            case 'break':
            case 'breaktext':
                $for_placeholder = false;
                break;
        }

        $for_select_2   .= '<option value="';
        $for_tempselect  = '';
        if($for_placeholder) {
            $for_select   .= '<option value="{'.$for_name.'}">';
            if(!empty($content['form']["fields"][$key]['label'])) {
                $for_select     .= html($content['form']["fields"][$key]['label']).' ';
                $for_tempselect .= html($content['form']["fields"][$key]['label']).' ';
            }
            $for_select   .= '{'.$for_name."}</option>\n";
            $for_select_2 .= '{ERROR:'.$for_name.'}{LABEL:'.$for_name.'}';
        }
        $for_select_2 .= '{'.$for_name.'}">'.$for_tempselect.'{'.$for_name."}</option>\n";

    }
}

?>
<input type="hidden" name="target_ctype" value="23" />

<ul class="nav nav-tabs mb-3" id="cform-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="cform-tab-mail" data-bs-toggle="tab" href="#cform-pane-mail" role="tab" aria-controls="cform-pane-mail" aria-selected="true">
            <i class="fa-solid fa-envelope me-1"></i> <?php echo $BL['be_cnt_recipient']; ?> &amp; <?php echo $BL['be_subnav_msg_new']; ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="cform-tab-feedback" data-bs-toggle="tab" href="#cform-pane-feedback" role="tab" aria-controls="cform-pane-feedback" aria-selected="false">
            <i class="fa-solid fa-comment-alt me-1"></i> <?php echo $BL['be_cnt_texts']; ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="cform-tab-fields" data-bs-toggle="tab" href="#cform-pane-fields" role="tab" aria-controls="cform-pane-fields" aria-selected="false">
            <i class="fa-solid fa-list me-1"></i> <?php echo $BL['be_ctype_simpleform']; ?>
            <?php if (!empty($content['form']['fields'])): ?>
                <span class="badge rounded-pill bg-secondary ms-1"><?php echo count($content['form']['fields']); ?></span>
            <?php endif; ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="cform-tab-templates" data-bs-toggle="tab" href="#cform-pane-templates" role="tab" aria-controls="cform-pane-templates" aria-selected="false">
            <i class="fa-solid fa-file-code me-1"></i> <?php echo $BL['be_admin_struct_template']; ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="cform-tab-settings" data-bs-toggle="tab" href="#cform-pane-settings" role="tab" aria-controls="cform-pane-settings" aria-selected="false">
            <i class="fa-solid fa-poll-h me-1"></i> <?php echo $BL['be_cnt_result']; ?>
        </a>
    </li>
</ul>

<div class="tab-content" id="cform-tabContent">

  <!-- TAB 1: MAIL & RECIPIENT SETTINGS -->
  <div class="tab-pane fade show active" id="cform-pane-mail" role="tabpanel" aria-labelledby="cform-tab-mail">

    <div class="form-group align-items-center row g-2">
      <label for="cform_subjecttype" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_msg_subject'] ?></label>
      <div class="col-sm-3">
            <select name="cform_subjectselect" id="cform_subjecttype" class="form-select form-select-sm">
                <option value=""><?php echo $BL['be_msg_subject'] ?></option>
                <?php echo $subject_option; ?>
            </select>
      </div>
      <div class="col">
          <input name="cform_subject" type="text" id="cform_subject" class="form-control form-control-sm" value="<?php echo html($content['form']["subject"]) ?>" />
      </div>
    </div>

    <div class="form-group align-items-center row g-2">
      <label for="cform_recipienttype" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_recipient'] ?></label>
      <div class="col-sm-3">
            <select name="cform_targettype" id="cform_recipienttype" class="form-select form-select-sm">
        <?php
            echo '<option value="email"'. is_selected('email', $content['form']['targettype'],0,0) .'>'.$BL['be_profile_label_email'].'</option>'.LF;
            echo $recipient_option;
        ?>
        </select>
      </div>
      <div class="col">
            <input name="cform_target" type="text" id="cform_target" class="form-control form-control-sm" value="<?php echo html($content['form']["target"]) ?>" />
      </div>
    </div>

    <div class="form-group align-items-center row g-2">
      <label for="cform_sendertype" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_newsletter_fromemail'] ?></label>
      <div class="col-sm-3">
            <select name="cform_sendertype" id="cform_sendertype" class="form-select form-select-sm">
        <?php
            echo '<option value="email"'. is_selected('email', $content['form']['sendertype'],0,0) .'>'.$BL['be_profile_label_email'].'</option>'.LF;
            echo '<option value="system"'. is_selected('system', $content['form']['sendertype'],0,0) .'>'.$BL['be_cnt_sysadmin_system'].': '.html($phpwcms['SMTP_FROM_EMAIL']).'</option>'.LF;
            echo $sender_option;
        ?>
        </select>
      </div>
      <div class="col">
        <input name="cform_sender" type="text" id="cform_sender" class="form-control form-control-sm" value="<?php echo html($content['form']['sender']) ?>" />
      </div>
    </div>

    <div class="form-group align-items-center row g-2">
      <label for="cform_sendernametype" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_newsletter_fromname'] ?></label>
      <div class="col-sm-3">
            <select name="cform_sendernametype" id="cform_sendernametype" class="form-select form-select-sm">
        <?php
                echo '<option value="custom"'. is_selected('custom', $content['form']['sendernametype'],0,0) .'>'.$BL['be_cnt_ecardform_name'].'</option>'.LF;
                echo '<option value="system"'. is_selected('system', $content['form']['sendernametype'],0,0) .'>'.$BL['be_cnt_sysadmin_system'].': '.html($phpwcms['SMTP_FROM_NAME']).'</option>'.LF;
                echo $sendername_option;
        ?>
        </select>
      </div>
      <div class="col">
        <input name="cform_sendername" type="text" id="cform_sendername" class="form-control form-control-sm" value="<?php echo  html($content['form']['sendername']) ?>" />
      </div>
    </div>

    <div class="form-group align-items-center row g-2">
      <label for="cform_sendcopy" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_send_copy_to']?></label>
      <div class="col-sm-3">
        <div class="input-group input-group-sm">
          
            <div class="input-group-text">
              <input type="checkbox" name="cform_sendcopy" id="cform_sendcopy" title="send copy to selected field" value="1"<?php echo is_checked('1', $content['form']["sendcopy"], 0, 0) ?> />
            
          </div>
          <select name="cform_copyto" id="cform_copyto" class="form-select form-select-sm"><?php echo $cc_listing; ?></select>
        </div>
      </div>
      <div class="col">
        <input name="cform_cc" type="text" id="cform_cc" class="form-control form-control-sm" value="<?php echo html($content['form']['cc']) ?>" />
      </div>
    </div>

    <div class="form-group align-items-center row g-2">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="cform_checktofrom" id="cform_checktofrom" value="1" <?php is_checked(1, $content['form']['checktofrom']) ?> />
              <label class="form-check-label" for="cform_checktofrom">
                <?php echo $BL['be_cnt_field']['checktofrom'] ?>
              </label>
            </div>
      </div>
    </div>

  </div>

  <!-- TAB 2: FEEDBACK, SUCCESS & ERROR RESPONSES -->
  <div class="tab-pane fade" id="cform-pane-feedback" role="tabpanel" aria-labelledby="cform-tab-feedback">

    <!-- STARTUP (DEFAULT TEXT / HTML) -->
    <div class="form-group align-items-center row g-2">
      <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_tmpl_default'] ?></label>
      <div class="col">
      	<div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="cform_startup_html" id="cform_startup_html0" value="0" data-ace-target="#cform_startup" data-ace-mode="text"<?php echo is_checked('0', $content['form']["startup_html"], 0, 0) ?> />
            <label class="form-check-label" for="cform_startup_html0">Text</label>
        </div>
    	<div class="form-check form-check-inline">
    		<input class="form-check-input" type="radio" name="cform_startup_html" id="cform_startup_html1" value="1" data-ace-target="#cform_startup" data-ace-mode="html"<?php echo is_checked('1', $content['form']["startup_html"], 0, 0) ?> />
            <label class="form-check-label" for="cform_startup_html1">HTML</label>
        </div>
      </div>
    </div>

    <div class="form-group row g-2">
        <label class="col-sm-2 col-form-label" for="cform_startup"></label>
        <div class="col">
            <textarea name="cform_startup" id="cform_startup" rows="4" class="form-control form-control-sm code-editor" data-mode="<?php echo empty($content['form']['startup_html']) ? 'text' : 'html'; ?>"><?php echo html($content['form']["startup"]) ?></textarea>
      	</div>
    </div>

    <hr />

    <!-- ON SUCCESS -->
    <div class="form-group align-items-center row g-2">
      <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_onsuccess'] ?></label>
      <div class="col-sm-auto">
      	<div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="cform_onsuccess_redirect" id="cform_onsuccess_redirect0" value="0" data-ace-target="#cform_onsuccess" data-ace-mode="text"<?php echo is_checked('0', $content['form']["onsuccess_redirect"], 0, 0) ?> />
            <label class="form-check-label" for="cform_onsuccess_redirect0">Text</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="cform_onsuccess_redirect" id="cform_onsuccess_redirect2" value="2" data-ace-target="#cform_onsuccess" data-ace-mode="html"<?php echo is_checked('2', $content['form']["onsuccess_redirect"], 0, 0) ?> />
            <label class="form-check-label" for="cform_onsuccess_redirect2">HTML</label>
        </div>
        <div class="form-check form-check-inline">
        	<input class="form-check-input" type="radio" name="cform_onsuccess_redirect" id="cform_onsuccess_redirect1" value="1" data-ace-target="#cform_onsuccess" data-ace-mode="url"<?php echo is_checked('1', $content['form']["onsuccess_redirect"], 0, 0) ?> />
            <label class="form-check-label" for="cform_onsuccess_redirect1">Redirect</label>
      	</div>
      </div>

      <div class="col-sm-auto px-sm-2">
    		<?php
    		if($for_select != '') {
    			echo '<div class="input-group">';
    			echo '<select name="successInfo" id="successInfo" class="form-select form-select-sm" ';
    			echo 'onChange="insertAtCursorPos(document.articlecontent.cform_onsuccess, ';
    			echo 'document.articlecontent.successInfo.options[document.articlecontent.successInfo.selectedIndex].value);">';
                echo '<option value="">' . $BL['be_newsletter_placeholder'] . '</option>';
    			echo $for_select;
    			echo '<option value="{REMOTE_IP}">{REMOTE_IP}</option>'.LF;
    			echo '</select>';
    			echo '';
    			echo '<a class="btn btn-sm btn-blue insert px-3" onclick="insertAtCursorPos(document.articlecontent.cform_onsuccess, document.articlecontent.successInfo.options[document.articlecontent.successInfo.selectedIndex].value);"><i class="fa-solid fa-angle-right" aria-hidden="true"></i></a>';
    			echo '';
    			echo '</div>';
    		}
    		?>
    	</div>
    </div>

    <div class="form-group row g-2">
      <label class="col-sm-2 col-form-label"></label>
        <div class="col">
        	<textarea name="cform_onsuccess" id="cform_onsuccess" rows="4" class="form-control form-control-sm code-editor" data-mode="<?php echo ($content['form']['onsuccess_redirect'] == 2) ? 'html' : (($content['form']['onsuccess_redirect'] == 1) ? 'url' : 'text'); ?>"><?php echo html($content['form']["onsuccess"]) ?></textarea>
    	</div>
    </div>

    <hr />

    <!-- ON ERROR -->
    <div class="form-group align-items-center row g-2">
        <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_onerror'] ?></label>
        <div class="col">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="cform_onerror_redirect" id="cform_onerror_redirect0" value="0" data-ace-target="#cform_onerror" data-ace-mode="text"<?php echo is_checked('0', $content['form']["onerror_redirect"], 0, 0) ?> />
                <label class="form-check-label" for="cform_onerror_redirect0">Text</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="cform_onerror_redirect" id="cform_onerror_redirect2" value="2" data-ace-target="#cform_onerror" data-ace-mode="html"<?php echo is_checked('2', $content['form']["onerror_redirect"], 0, 0) ?> />
                <label class="form-check-label" for="cform_onerror_redirect2">HTML</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="cform_onerror_redirect" id="cform_onerror_redirect1" value="1" data-ace-target="#cform_onerror" data-ace-mode="url"<?php echo is_checked('1', $content['form']["onerror_redirect"], 0, 0) ?> />
                <label class="form-check-label" for="cform_onerror_redirect1">Redirect</label>
            </div>
        </div>
    </div>

    <div class="form-group row g-2">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col">
            <textarea name="cform_onerror" id="cform_onerror" rows="4" class="form-control form-control-sm code-editor" data-mode="<?php echo ($content['form']['onerror_redirect'] == 2) ? 'html' : (($content['form']['onerror_redirect'] == 1) ? 'url' : 'text'); ?>"><?php echo html($content['form']["onerror"]) ?></textarea>
        </div>
    </div>

  </div>

  <!-- TAB 3: FORM & FIELDS -->
  <div class="tab-pane fade" id="cform-pane-fields" role="tabpanel" aria-labelledby="cform-tab-fields">

    <div class="form-group align-items-center row g-2">
      <label for="cform_labelpos3" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_reference_basis'] ?></label>
      <div class="col">
          <?php
          if(!isset($content['form']["labelpos"])) {
              $content['form']["labelpos"] = 3;
              // 0 = default = in front of form field
              // 1 = above form field
              // 2 = Custom
              // 3 = modern DIV based
          }
          ?>
          <div class="form-check form-check-inline me-sm-4">
              <input class="form-check-input" type="radio" name="cform_labelpos" id="cform_labelpos3" value="3"<?php echo is_checked(3, $content['form']["labelpos"], 0, 1) ?> />
              <label class="form-check-label" for="cform_labelpos3"><img src="img/symbole/label_3.svg" width="72" height="22" alt=""/></label>
          </div>
          <div class="form-check form-check-inline me-sm-4">
              <input class="form-check-input" type="radio" name="cform_labelpos" id="cform_labelpos0" value="0"<?php echo is_checked(0, $content['form']["labelpos"], 0, 1) ?> />
              <label class="form-check-label" for="cform_labelpos0"><img src="img/symbole/label_0.svg" width="72" height="22" alt=""/></label>
          </div>
          <div class="form-check form-check-inline me-sm-4">
              <input class="form-check-input" type="radio" name="cform_labelpos" id="cform_labelpos1" value="1"<?php echo is_checked(1, $content['form']["labelpos"], 0, 1) ?> />
              <label class="form-check-label" for="cform_labelpos1"><img src="img/symbole/label_1.svg" width="72" height="22" alt=""/></label>
          </div>
          <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="cform_labelpos" id="cform_labelpos2" value="2"<?php echo is_checked(2, $content['form']["labelpos"], 0, 1) ?> />
              <label class="form-check-label" for="cform_labelpos2"><img src="img/symbole/label_2.svg" width="72" height="22" alt=""/></label>
          </div>
      </div>
    </div>

    <div class="form-group align-items-center row g-2">
      <label for="cform_class" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_form_class'] ?></label>
      <div class="col-sm-4">
        <input type="text" name="cform_class" id="cform_class" class="form-control form-control-sm" value="<?php echo html($content['form']["class"]) ?>" />
      </div>
      <label for="cform_label_wrap" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_label_wrap'] ?></label>
      <div class="col-sm-4">
        <input type="text" name="cform_label_wrap" id="cform_label_wrap" class="form-control form-control-sm" value="<?php echo html($content['form']["label_wrap"]) ?>" />
      </div>
    </div>

    <div class="form-group align-items-center row g-2">
        <label for="cform_reqmark" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_req_mark'] ?></label>
        <div class="col-sm-4">
            <input type="text" name="cform_reqmark" id="cform_reqmark" class="form-control form-control-sm" value="<?php echo html($content['form']["cform_reqmark"]) ?>" />
        </div>
        <label for="cform_error_class" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_error_class'] ?></label>
        <div class="col-sm-2">
            <input type="text" name="cform_error_class" id="cform_error_class" class="form-control form-control-sm" value="<?php echo html($content['form']["error_class"]) ?>" />
        </div>
        <div class="col-sm-2 ps-sm-5"><div class="form-check">
            <input type="checkbox" name="cform_novalidate" id="cform_novalidate" class="form-check-input" value="1"<?php is_checked(1, $content['form']["novalidate"]) ?> />
            <label for="cform_novalidate" class="form-check-label"><strong><?php echo $BL['be_cnt_novalidate']; ?></strong></label>
        </div></div>
    </div>

    <div class="form-group align-items-center row g-2">
      <label for="cform_function_validate" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_function_validate'] ?></label>
      <div class="col-sm-4">
        <input type="text" name="cform_function_validate" id="cform_function_validate" class="form-control form-control-sm" value="<?php echo html($content['form']["cform_function_validate"]) ?>" />
      </div>
      <label for="cform_anchor_name" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_article_cnt_anchor'].' &ndash; '.$BL['be_cnt_target']; ?></label>
      <div class="col-sm-4">
        <div class="input-group input-group-sm">
            
                <div class="input-group-text">
                    <input type="checkbox" name="cform_anchor_off" id="cform_anchor_off" value="0"<?php is_checked(0, $content['form']["anchor_off"]) ?> />
                
            </div>
            <input type="text" name="cform_anchor_name" id="cform_anchor_name" class="form-control form-control-sm" value="<?php echo html($content['form']["anchor_name"]) ?>" placeholder="jumpForm<?php echo empty($content["id"]) ? '' : $content["id"]; ?>" />
        </div>
      </div>
    </div>

    <div class="form-group align-items-center row g-2">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="cform_ssl" id="cform_ssl" value="1" <?php is_checked(1, $content['form']['ssl']) ?> />
            <label class="form-check-label" for="cform_ssl">
                <?php echo $BL['form_force_ssl'] ?>
            </label>
        </div>
      </div>
    </div>

    <hr />

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="mb-0 fw-bold text-secondary"><i class="fa-solid fa-list me-1"></i> <?php echo $BL['be_cnt_formfields']; ?></h5>
        <div class="btn-toolbar" role="toolbar">
            <button type="button" class="btn btn-sm btn-success me-2 btn-add-field-trigger">
                <i class="fa-solid fa-plus me-1"></i> <?php echo $BL['be_admin_custom_cpt_add_field']; ?>
            </button>
            <div class="btn-group btn-group-sm" role="group" aria-label="Field toggles">
                <button type="button" class="btn btn-light" onclick="showAllFormFields();" title="<?php echo $BL['be_cnt_expand_all']; ?>">
                    <i class="fa-solid fa-angle-double-down me-1"></i> <?php echo $BL['be_cnt_expand_all']; ?>
                </button>
                <button type="button" class="btn btn-light" onclick="hideAllFormFields();" title="<?php echo $BL['be_cnt_collapse_all']; ?>">
                    <i class="fa-solid fa-angle-double-up me-1"></i> <?php echo $BL['be_cnt_collapse_all']; ?>
                </button>
            </div>
        </div>
    </div>

    <table class="table w-100 mb-1" style="position: relative;">
    <tr class="bg-light">
        <th class="px-1 text-center" style="width: 30px"><i class="fa-solid fa-grip-vertical text-muted"></i></th>
        <th class="px-1 text-center" style="width: 30px"><i class="fa-solid fa-caret-down text-muted"></i></th>
        <th class="px-1" style="width: 25%;"><?php echo $BL['be_cnt_type'] ?></th>
        <th class="px-1" style="width: 25%;"><?php echo $BL['be_admin_tmpl_name'] ?></th>
        <th class="px-1" style="width: 15%;"><?php echo $BL['be_cnt_label'] ?></th>
        <th class="px-1" style="width: 15%;"><div data-bs-toggle="tooltip" data-placement="top" title="size/columns">S/C</div></th>
        <th class="px-1" style="width: auto;"><div data-bs-toggle="tooltip" data-placement="top" title="maxlength/rows">M/R</div></th>
        <th class="px-0 text-center" style="width: 30px;"><i class="fa-solid fa-exclamation text-danger" data-bs-toggle="tooltip" data-placement="top" title="<?php echo $BL['be_cnt_needed'] ?>" alt="<?php echo $BL['be_cnt_needed'] ?>"></i></th>
        <th class="px-0 text-center" style="width: 30px;"><i class="fa-solid fa-trash text-danger" data-bs-toggle="tooltip" data-placement="top" title="<?php echo $BL['be_cnt_delete'] ?>" alt="<?php echo $BL['be_cnt_delete'] ?>"></i></th>
    </tr>
    </table>

    <ul id="sortable-list" class="dropable-list ps-0">
    <?php
    if(isset($content['form']["fields"]) && is_array($content['form']["fields"]) && count($content['form']["fields"])) {

        $field_counter = 1;
        $field_max = count($content['form']["fields"]);
        $field_js = array(
            'showAll' => array(),
            'hideAll' => array(),
            'varcharFields' => array(),
            'longtextFields' => array()
        );
        $field_type_count = array();

        foreach($content['form']["fields"] as $key => $value) {

            $field_row4 = '';
            $field_type = $content['form']["fields"][$key]['type'];

            // generate javascript code part 1
            $field_js['showAll'][$key]  = ' showHide_CntFormfieldRow(\'formRow_'.$field_counter.'\', \'block\'';
            $field_js['hideAll'][$key]  = ' showHide_CntFormfieldRow(\'formRow_'.$field_counter.'\', \'none\'';

            echo '<li class="sortme card mb-2 p-2 shadow-sm" id="sortRow_'.$field_counter.'"><table class="table-borderless w-100"><tr>';
            echo '<td width="30" class="text-center"><em data-bs-toggle="tooltip" title="'.$BL['be_func_struct_sort_up'].' / '.$BL['be_func_struct_sort_down'].'" class="handle text-secondary cursor-grab"><i class="fa-solid fa-grip-vertical"></i></em></td>';

            if(!isset($field_type_count[$field_type])) {
                $field_type_count[$field_type] = 0;
            }

            // some field specific checks and settings
            switch($field_type) {

                case 'newsletter':      // default hide/show

                    $field_row4  = '<tr id="formRow_'.$field_counter.'_5">';
                    $field_row4 .= '<td colspan="3" class="text-end align-top pt-2">&nbsp;';
                    $field_row4 .= $BL['be_cnt_bid_verifyemail'].'&nbsp;</td>'.LF;
                    $field_row4 .= '<td class="pb-2" colspan="4"><textarea name="cform_field_verifyemail" ';
                    $field_row4 .= 'id="cform_field_verifyemail" rows="5" class="form-control form-control-sm" wrap="off">';
                    $field_row4 .= html($content['form']['verifyemail']).'</textarea></td>';
                    $field_row4 .= '</tr>';

                    $field_js['showAll'][$key] .= ', 5';
                    $field_js['hideAll'][$key] .= ', 5';

                    break;

                case 'text':
                case 'tel':
                case 'url':
                case 'number':
                case 'date':
                case 'time':
                case 'color':
                case 'range':
                case 'special':
                case 'email':
                case 'password':
                case 'hidden':
                case 'select':
                case 'selectemail':
                case 'country':
                case 'radio':
                    // default hide/show
                    if($content['form']["saveprofile"]) {

                        $field_row4  = '<tr id="formRow_'.$field_counter.'_5">';
                        $field_row4 .= '<td colspan="3" class="text-end pt-2">'.$BL['be_cnt_store_in'].':&nbsp;</td>';
                        $field_row4 .= '<td colspan="1" id="cform_field_profile_'.$field_counter.'_td">';

                        if(!empty($content['form']["fields"][$key]['profile']) && isset($content['profile_fields_varchar'][ $content['form']["fields"][$key]['profile'] ])) {

                            $field_js['varcharFields'][$field_counter]  = '<"+"option value=\"'.$content['form']["fields"][$key]['profile'].'\" selected=\"selected\">';
                            $field_js['varcharFields'][$field_counter] .= $content['profile_fields_varchar'][ $content['form']["fields"][$key]['profile'] ].'<"+"/option>';
                            unset($content['profile_fields_varchar'][ $content['form']["fields"][$key]['profile'] ]);

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
                    if($content['form']["saveprofile"]) {

                        $field_row4  = '<tr id="formRow_'.$field_counter.'_5">';
                        $field_row4 .= '<td colspan="2" class="text-end pt-2">'.$BL['be_cnt_store_in'].':&nbsp;</td>';
                        $field_row4 .= '<td colspan="6" id="cform_field_profile_'.$field_counter.'_td">';

                        if(!empty($content['form']["fields"][$key]['profile']) && isset($content['profile_fields_longtext'][ $content['form']["fields"][$key]['profile'] ])) {

                            $field_js['longtextFields'][$field_counter]  = '<"+"option value=\"'.$content['form']["fields"][$key]['profile'].'\" selected=\"selected\">';
                            $field_js['longtextFields'][$field_counter] .= $content['profile_fields_longtext'][ $content['form']["fields"][$key]['profile'] ].'<"+"/option>';
                            unset($content['profile_fields_longtext'][ $content['form']["fields"][$key]['profile'] ]);

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
                    if(isset($_ini_values['public_key'])) {
                        if(!isset($_ini_values['site_key'])) {
                            $_ini_values['site_key'] = $_ini_values['public_key'];
                        }
                        unset($_ini_values['public_key']);
                    }
                    if(isset($_ini_values['private_key'])) {
                        if(!isset($_ini_values['secret_key'])) {
                            $_ini_values['secret_key'] = $_ini_values['private_key'];
                        }
                        unset($_ini_values['private_key']);
                    }

                    unset($_ini_values);

                    break;

            }

            $extraParam = '';
            if ($field_type === 'newsletter' || ($content['form']['saveprofile'] && in_array($field_type, array('textarea', 'checkbox', 'checkboxcopy', 'list', 'text', 'tel', 'url', 'number', 'date', 'time', 'color', 'range', 'special', 'email', 'password', 'hidden', 'select', 'selectemail', 'country', 'radio'), true))) {
                $extraParam = ', 5';
            }

            echo '<td width="30" class="text-center" id="formRow_'.$field_counter.'">';
            echo '<a href="#" onclick="return showHide_CntFormfieldRow(\'formRow_'.$field_counter.'\', \'none\'' . $extraParam . ');"><i class="fa-solid fa-caret-down text-primary"></i></a>';
            echo '</td><td style="width: 25%;">';
            echo '<select name="cform_field_type['.$field_counter.']" class="form-select form-select-sm">';
            echo '<option value="text"'. is_selected('text', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['text'].'</option>';
            echo '<option value="email"'. is_selected('email', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['email'].'</option>';
            echo '<option value="tel"'. is_selected('tel', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['tel'].'</option>';
            echo '<option value="url"'. is_selected('url', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['url'].'</option>';
            echo '<option value="number"'. is_selected('number', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['number'].'</option>';
            echo '<option value="date"'. is_selected('date', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['date'].'</option>';
            echo '<option value="time"'. is_selected('time', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['time'].'</option>';
            echo '<option value="color"'. is_selected('color', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['color'].'</option>';
            echo '<option value="range"'. is_selected('range', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['range'].'</option>';
            echo '<option value="textarea"'. is_selected('textarea', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['textarea'].'</option>';
            echo '<option value="special"'. is_selected('special', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['special'].'</option>';
            echo '<option value="hidden"'. is_selected('hidden', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['hidden'].'</option>';
            echo '<option value="password"'. is_selected('password', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['password'].'</option>';
            echo '<option value="selectemail"'. is_selected('selectemail', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['selectemail'].'</option>';
            echo '<option value="select"'. is_selected('select', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['select'].'</option>';
            echo '<option value="country"'. is_selected('country', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['country'].'</option>';
            echo '<option value="list"'. is_selected('list', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['list'].'</option>';
            echo '<option value="newsletter"'. is_selected('newsletter', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['newsletter'].'</option>';
            echo '<option value="checkbox"'. is_selected('checkbox', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['checkbox'].'</option>';
            echo '<option value="checkboxcopy"'. is_selected('checkboxcopy', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['checkboxcopy'].'</option>';
            echo '<option value="radio"'. is_selected('radio', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['radio'].'</option>';
            echo '<option value="upload"'. is_selected('upload', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['upload'].'</option>';
            echo '<option value="recaptcha"'. is_selected('recaptcha', $field_type, 0, 0) . ($field_type === 'recaptcha' ? '' : ' class="disable-recaptcha"') . '>'.$BL['be_cnt_field']['recaptcha'].'</option>';
            echo '<option value="recaptchainv"'. is_selected('recaptchainv', $field_type, 0, 0) . ($field_type === 'recaptchainv' ? '' : ' class="disable-recaptchainv"') . '>'.$BL['be_cnt_field']['recaptchainv'].'</option>';
            echo '<option value="captcha"'. is_selected('captcha', $field_type, 0, 0) . ($field_type === 'captcha' ? '' : ' class="disable-captcha"') . '>'.$BL['be_cnt_field']['captcha'].'</option>';
            echo '<option value="captchaimg"'. is_selected('captchaimg', $field_type, 0, 0) . ($field_type === 'captchaimg' ? '' : ' class="disable-captchaimg"') . '>'.$BL['be_cnt_field']['captchaimg'].'</option>';
            echo '<option value="mathspam"'. is_selected('mathspam', $field_type, 0, 0) . ($field_type === 'mathspam' ? '' : ' class="disable-mathspam"') . '>'.$BL['be_cnt_field']['mathspam'].'</option>';
            echo '<option value="submit"'. is_selected('submit', $field_type, 0, 0) . ($field_type === 'submit' ? '' : ' class="disable-submit"') . '>'.$BL['be_cnt_field']['submit'].'</option>';
            echo '<option value="reset"'. is_selected('reset', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['reset'].'</option>';
            echo '<option value="break"'. is_selected('break', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['break'].'</option>';
            echo '<option value="breaktext"'. is_selected('breaktext', $field_type, 0, 0) .'>'.$BL['be_cnt_field']['breaktext'].'</option>';
            echo '</select></td>';

            echo '<td width="25%"><input type="text" name="cform_field_name['.$field_counter.']" class="form-control form-control-sm" value="';
            echo html($content['form']["fields"][$key]['name']).'"></td>';
            echo '<td width="15%"><input type="text" name="cform_field_label['.$field_counter.']" class="form-control form-control-sm" value="';
            echo html($content['form']["fields"][$key]['label']).'"></td>';
            echo '<td width="15%"><input type="text" name="cform_field_size['.$field_counter.']" class="form-control form-control-sm" value="';
            echo html($content['form']["fields"][$key]['size']).'" title="SIZE / COLUMNS / MIN"></td>';
            echo '<td width="auto"><input type="text" name="cform_field_max['.$field_counter.']" class="form-control form-control-sm" value="';
            echo html($content['form']["fields"][$key]['max']).'" title="MAXLENGTH / ROWS / MAX / STEP"></td>';
            echo '<td class="text-center" style="width: 30px;"><input type="checkbox" name="cform_field_required['.$field_counter.']"';
            echo is_checked('1', $content['form']["fields"][$key]['required'], 0, 0).' value="1" title="'.$BL['be_cnt_mark_as_req'].'"></td>';
            echo '<td class="text-center" style="width: 30px;">';
            echo '<button type="button" class="btn btn-sm btn-danger btn-delete-form-field" title="'.$BL['be_cnt_mark_as_del'].'"><i class="fa-solid fa-trash-alt"></i></button>';
            echo '<input type="checkbox" name="cform_field_delete['.$field_counter.']" id="cform_field_delete_'.$field_counter.'" value="1" class="d-none">';
            echo '</td>';
            echo "</tr>";

            echo '<tr id="formRow_'.$field_counter.'_1"><td>&nbsp;</td>';
            echo '<td class="align-top"><table class="table-borderless w-100"><tr><td class="align-top">';
            echo '<input type="hidden" name="cform_order['.$field_counter.']" id="cform_order_'.$field_counter.'" value="'.$field_counter.'">';
            echo '</td><td align="right" class="align-top"><a name="field_value_'.$field_counter.'"></a>';
            echo "</td></tr>";
            echo "</table></td>";
            echo '<td colspan="1" class="align-top py-2 text-end text-muted">&nbsp;'.$BL['be_cnt_value'].'&nbsp;</td>';
            echo '<td colspan="4" class="py-2"><textarea name="cform_field_value['.$field_counter.']" ';
            echo 'id="cform_field_value_'.$field_counter.'" rows="5" class="form-control form-control-sm font-monospace">';
            echo html($content['form']["fields"][$key]['value']).'</textarea>';

            // Show "sign up for reCAPCHA API key"
            if($field_type === 'recaptcha' || $field_type === 'recaptchainv') {
                echo '<a href="https://www.google.com/recaptcha/admin"
                    target="_blank"
                    class="d-inline-block text-primary fw-bold my-2"><i class="fa-solid fa-external-link-alt me-1"></i>'.$BL['be_cnt_field']['recaptcha_signapikey'].'</a>';
            }

            echo '</td>';
            echo '<td colspan="2" class="align-bottom"></td>';
            echo '</tr>';

            echo '<tr id="formRow_'.$field_counter.'_2">';
            echo '<td colspan="3" class="text-end pb-2 text-muted">&nbsp;'.$BL['be_newsletter_placeholder'].'&nbsp;</td>';
            echo '<td colspan="4" class="pb-2"><input type="text" name="cform_field_placeholder['.$field_counter.']" value="';
            echo empty($content['form']["fields"][$key]['placeholder']) ? '' : html($content['form']["fields"][$key]['placeholder']);
            echo '" class="form-control form-control-sm"></td></tr>';

            echo '<tr id="formRow_'.$field_counter.'_3">';
            echo '<td colspan="3" class="text-end pb-2 text-muted">&nbsp;'.$BL['be_cnt_error_text'].'&nbsp;</td>';
            echo '<td colspan="4" class="pb-2"><input type="text" name="cform_field_error['.$field_counter.']" value="';
            echo  html($content['form']["fields"][$key]['error']).'" class="form-control form-control-sm"';
            if($field_type == 'upload') {
                echo ' title="{MAXLENGTH}, {FILESIZE}, {FILENAME}, {FILEEXT}"';
            }
            echo '></td></tr>';

            echo '<tr id="formRow_'.$field_counter.'_4">';
            echo '<td colspan="3" class="text-end pb-2 text-muted">&nbsp;'.$BL['be_cnt_css_class'].'&nbsp;</td>';
            echo '<td class="pb-2"><input type="text" name="cform_field_class['.$field_counter.']" value="';
            echo  html($content['form']["fields"][$key]['class']).'" class="form-control form-control-sm"></td>';
            echo '<td colspan="3">
                 <table class="table-borderless w-100"><tr>
                 <td style="width: 80px;" class="text-end pb-2 text-muted">&nbsp;'.$BL['be_cnt_css_style'].':&nbsp;</td>
                 <td class="pb-2"><input type="text" name="cform_field_style['.$field_counter.']" value="';
            echo html($content['form']["fields"][$key]['style']).'" class="form-control form-control-sm"></td></tr></table></td>';

            echo "</tr>";

            // if field row 4
            echo $field_row4;

            echo '</table></li>';

            // generate javascript code part 2
            $field_js['showAll'][$key] .= ');';
            $field_js['hideAll'][$key] .= ');';

            $field_counter++;
            $field_type_count[$field_type]++;
        }
    }
    ?></ul>

    <div class="d-flex justify-content-between align-items-center my-3">
      <button type="button" class="btn btn-sm btn-success" id="btn-add-more-fields"><i class="fa-solid fa-plus me-1"></i> <?php echo $BL['be_admin_custom_cpt_add_field']; ?></button>
      <button type="submit" class="btn btn-blue btn-sm" value="1"><i class="fa-solid fa-rotate"></i> <?php echo $BL['be_article_cnt_button1'] ?></button>
    </div>

    <!-- Template for dynamic additional field cards -->
    <template id="cform-new-field-template">
      <li class="sortme card mb-2 p-2 shadow-sm new-field-card border-success" id="sortRow___KEY__">
        <table class="table-borderless w-100">
        <tr>
          <td width="30" class="text-center"><em data-bs-toggle="tooltip" title="<?php echo $BL['be_func_struct_sort_up'].' / '.$BL['be_func_struct_sort_down']; ?>" class="handle text-secondary cursor-grab"><i class="fa-solid fa-grip-vertical"></i></em></td>
          <td width="30" class="text-center" id="formRow___KEY__">
            <a href="#" onclick="return showHide_CntFormfieldRow('formRow___KEY__', 'none');"><i class="fa-solid fa-caret-down text-primary"></i></a>
          </td>
          <td width="25%" style="width: 25%;">
            <select name="cform_field_type[__KEY__]" id="cform_field_type___KEY__" class="form-select form-select-sm fw-bold">
              <option value="text"><?php echo $BL['be_cnt_field']['text'] ?></option>
              <option value="email"><?php echo $BL['be_cnt_field']['email'] ?></option>
              <option value="tel"><?php echo $BL['be_cnt_field']['tel'] ?></option>
              <option value="url"><?php echo $BL['be_cnt_field']['url'] ?></option>
              <option value="number"><?php echo $BL['be_cnt_field']['number'] ?></option>
              <option value="date"><?php echo $BL['be_cnt_field']['date'] ?></option>
              <option value="time"><?php echo $BL['be_cnt_field']['time'] ?></option>
              <option value="color"><?php echo $BL['be_cnt_field']['color'] ?></option>
              <option value="range"><?php echo $BL['be_cnt_field']['range'] ?></option>
              <option value="textarea"><?php echo $BL['be_cnt_field']['textarea'] ?></option>
              <option value="special"><?php echo $BL['be_cnt_field']['special'] ?></option>
              <option value="hidden"><?php echo $BL['be_cnt_field']['hidden'] ?></option>
              <option value="password"><?php echo $BL['be_cnt_field']['password'] ?></option>
              <option value="selectemail"><?php echo $BL['be_cnt_field']['selectemail'] ?></option>
              <option value="select"><?php echo $BL['be_cnt_field']['select'] ?></option>
              <option value="country"><?php echo $BL['be_cnt_field']['country'] ?></option>
              <option value="list"><?php echo $BL['be_cnt_field']['list'] ?></option>
              <?php   if(empty($for_newsletter)): ?>
              <option value="newsletter"><?php echo $BL['be_cnt_field']['newsletter'] ?></option>
              <?php   endif;  ?>
              <option value="checkbox"><?php echo $BL['be_cnt_field']['checkbox'] ?></option>
              <option value="checkboxcopy"><?php echo $BL['be_cnt_field']['checkboxcopy'] ?></option>
              <option value="radio"><?php echo $BL['be_cnt_field']['radio'] ?></option>
              <option value="upload"><?php echo $BL['be_cnt_field']['upload'] ?></option>
              <option value="reset"><?php echo $BL['be_cnt_field']['reset'] ?></option>
              <option value="break"><?php echo $BL['be_cnt_field']['break'] ?></option>
              <option value="breaktext"><?php echo $BL['be_cnt_field']['breaktext'] ?></option>
            </select>
          </td>
          <td width="25%"><input type="text" placeholder="<?php echo $BL['be_admin_tmpl_name'] ?>" name="cform_field_name[__KEY__]" class="form-control form-control-sm" /></td>
          <td width="15%"><input type="text" placeholder="<?php echo $BL['be_cnt_label'] ?>" name="cform_field_label[__KEY__]" class="form-control form-control-sm" /></td>
          <td width="15%"><input type="text" placeholder="S/C/Min" name="cform_field_size[__KEY__]" class="form-control form-control-sm" title="SIZE / COLUMNS / MIN" /></td>
          <td width="auto"><input type="text" placeholder="M/R/Max" name="cform_field_max[__KEY__]" class="form-control form-control-sm" title="MAXLENGTH / ROWS / MAX / STEP" /></td>
          <td class="text-center" style="width: 30px;"><input type="checkbox" name="cform_field_required[__KEY__]" value="1" title="<?php echo $BL['be_cnt_mark_as_req'] ?>" /></td>
          <td class="text-center" style="width: 30px;"><button type="button" class="btn btn-sm btn-danger btn-remove-new-field" title="<?php echo $BL['be_cnt_mark_as_del']; ?>"><i class="fa-solid fa-trash-alt"></i></button></td>
        </tr>
        <tr id="formRow___KEY___1">
          <td>&nbsp;</td>
          <td class="align-top">
            <input type="hidden" name="cform_order[__KEY__]" id="cform_order___KEY__" value="__ORDER__" class="cform-order-input" />
          </td>
          <td colspan="1" class="align-top py-2 text-end text-muted">&nbsp;<?php echo $BL['be_cnt_value'] ?>&nbsp;</td>
          <td colspan="4" class="pt-2"><textarea name="cform_field_value[__KEY__]" id="cform_field_value___KEY__" rows="4" class="form-control form-control-sm font-monospace"></textarea></td>
          <td colspan="2" class="align-bottom"></td>
        </tr>
        <tr id="formRow___KEY___2">
          <td colspan="3" class="text-end pb-2 text-muted">&nbsp;<?php echo $BL['be_newsletter_placeholder'] ?>&nbsp;</td>
          <td colspan="4" class="pb-2"><input type="text" name="cform_field_placeholder[__KEY__]" class="form-control form-control-sm" /></td>
          <td colspan="2"></td>
        </tr>
        <tr id="formRow___KEY___3">
          <td colspan="3" class="text-end pb-2 text-muted">&nbsp;<?php echo $BL['be_cnt_error_text'] ?>&nbsp;</td>
          <td colspan="4" class="pb-2"><input type="text" name="cform_field_error[__KEY__]" class="form-control form-control-sm" /></td>
          <td colspan="2"></td>
        </tr>
        <tr id="formRow___KEY___4">
          <td colspan="3" class="text-end pb-2 text-muted">&nbsp;<?php echo $BL['be_cnt_css_class'] ?>&nbsp;</td>
          <td class="pb-2"><input type="text" name="cform_field_class[__KEY__]" class="form-control form-control-sm" /></td>
          <td colspan="3">
            <table class="table-borderless w-100">
              <tr>
                <td style="width:80px;" class="text-end pb-2 text-muted">&nbsp;<?php echo $BL['be_cnt_css_style'] ?>:&nbsp;</td>
                <td class="pb-2"><input type="text" name="cform_field_style[__KEY__]" class="form-control form-control-sm" /></td>
              </tr>
            </table>
          </td>
          <td colspan="1"></td>
        </tr>
        </table>
      </li>
    </template>

  </div>

  <!-- TAB 4: TEMPLATES & SETTINGS -->
  <div class="tab-pane fade" id="cform-pane-templates" role="tabpanel" aria-labelledby="cform-tab-templates">

    <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-file-code me-1"></i> <?php echo $BL['be_admin_struct_template']; ?> (Custom HTML / Output)</h6>

    <a id="anchor_customform"></a>
    <div class="form-group align-items-center row g-2">
      <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template'] ?></label>
      <div class="col-sm-auto">
    		<?php
    		if($for_select_2 != '') {
    				echo '<div class="input-group">';
    				echo '<select name="ph1" id="ph1" class="form-select form-select-sm" ';
    				echo 'onChange="insertAtCursorPos(document.articlecontent.cform_customform, ';
    				echo 'document.articlecontent.ph1.options[document.articlecontent.ph1.selectedIndex].value);">';
                    echo '<option value="">' . $BL['be_newsletter_placeholder'] . '</option>';
    				echo $for_select_2.'</select>';

    				echo '';
    				echo '<a class="btn btn-sm btn-blue px-3 insert" onclick="insertAtCursorPos(document.articlecontent.cform_customform, ';
    				echo 'document.articlecontent.ph1.options[document.articlecontent.ph1.selectedIndex].value);"><i class="fa-solid fa-angle-right" aria-hidden="true"></i></a>';
    				echo '';
    				echo '</div>';
    		}
    		?>
      </div>
    </div>

    <div class="form-group row g-2">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col">
        <textarea name="cform_customform" id="cform_customform" rows="6" class="form-control form-control-sm code-editor" data-mode="html"><?php echo html($content['form']["customform"]) ?></textarea>
      </div>
    </div>

    <hr />

    <a name="anchor_template" id="anchor_template"></a>
    <div class="form-group align-items-center row g-2">
        <label for="cform_template_text" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_recipient'] . ' - ' . $BL['be_admin_struct_template'] ?></label>
        <div class="col-sm-auto">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="cform_template_format" id="cform_template_text" value="0"<?php is_checked('0', $content['form']["template_format"]) ?> onchange="sessionStorage.setItem('phpwcms_cform_active_tab', '#cform-pane-templates');this.form.submit();" />
                <label class="form-check-label" for="cform_template_text">Text</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="cform_template_format" id="cform_template_html" value="1"<?php is_checked('1', $content['form']["template_format"]) ?> onchange="sessionStorage.setItem('phpwcms_cform_active_tab', '#cform-pane-templates');this.form.submit();" />
                <label class="form-check-label" for="cform_template_html">HTML</label>
            </div>
        </div>

    	<div class="col-sm-auto px-sm-4">
    		<?php
    		if($for_select != '') {
    			echo '<div class="input-group">';
    			echo '<select name="ph" id="ph" class="form-select form-select-sm" ';
    			echo 'onChange="insertAtCursorPos(document.articlecontent.cform_template, ';
    			echo 'document.articlecontent.ph.options[document.articlecontent.ph.selectedIndex].value);">';
                echo '<option value="">' . $BL['be_newsletter_placeholder'] . '</option>';
    			echo $for_select;
    			echo '<option value="{FORM_URL}">{FORM_URL}</option>';
    			echo '<option value="{REMOTE_IP}">{REMOTE_IP}</option>';
    			echo '<option value="{DATE:y/m/d H:i:s}">{DATE:y/m/d H:i:s}</option>';
    			echo '</select>';

    			echo '<button type="button" class="btn btn-sm btn-blue insert px-3" onclick="insertAtCursorPos(document.articlecontent.cform_template, document.articlecontent.ph.options[document.articlecontent.ph.selectedIndex].value);"><i class="fa-solid fa-angle-right" aria-hidden="true"></i></button>';
    			echo '</div>';
    		}
    		?>
      </div>
    </div>

    <div class="form-group row g-2">
    	<label class="col-sm-2 col-form-label"></label>
    	<div class="col">
    		<?php
    		if($content['form']["template_format"]) {
    				$wysiwyg_editor = array(
    						'value'     => $content['form']["template"],
    						'field'     => 'cform_template',
    						'height'    => '350px',
    						'width'     => '100%',
    						'rows'      => '15',
    						'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    						'lang'      => 'en'
    				);
    				include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';
    		} else {
    				echo '<textarea name="cform_template" id="cform_template" rows="5" class="form-control form-control-sm code-editor" data-mode="html">';
    				echo html($content['form']["template"]).'</textarea>';
    		}
    		?>
    	</div>
    </div>

    <div class="form-group align-items-center row g-2 mt-4">
      <label for="cform_function_to" class="col-sm-2 col-form-label text-end"><?php echo $BL['php_function']?></label>
      <div class="col">
        <input name="cform_function_to" type="text" id="cform_function_to" class="form-control form-control-sm" value="<?php echo html($content['form']['function_to']) ?>" />
      </div>
    </div>

    <hr />

    <!-- copy mail template //-->
    <a name="anchor_template_copy" id="anchor_template_copy"></a>
    <div class="form-group align-items-center row g-2">
      <label for="cform_template_copy" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_send_copy_to'].' - '.$BL['be_admin_struct_template'] ?></label>
      <div class="col">
        <div class="form-check form-check-inline">
    			<input class="form-check-input" type="checkbox" name="cform_template_equal" id="cform_template_equal" value="1"<?php is_checked(1, $content['form']["template_equal"]) ?> onchange="showhidecopy();" />
          <label class="form-check-label">
    			&nbsp;= <?php echo $BL['be_cnt_recipient'].' - '.$BL['be_admin_struct_template'] ?></label>
        </div>
        <script type="text/javascript">
        var showhidecopy = function() {
            var display_style = document.getElementById('cform_template_equal').checked ? 'none' : '';
            var c1 = document.getElementById('copytemplate1');
            var c2 = document.getElementById('copytemplate2');
            var c3 = document.getElementById('copytemplate3');
            if (c1) c1.style.display = display_style;
            if (c2) c2.style.display = display_style;
            if (c3) c3.style.display = display_style;
        };
        document.addEventListener("DOMContentLoaded", showhidecopy);
        </script>
      </div>
    </div>

    <div class="form-group align-items-center row g-2">
        <label class="col-sm-2 col-form-label"></label>
        <div id="copytemplate1" class="col-sm-auto">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="cform_template_format_copy" id="cform_template_text_copy" value="0"<?php is_checked(0, $content['form']["template_format_copy"]) ?> onchange="sessionStorage.setItem('phpwcms_cform_active_tab', '#cform-pane-templates');this.form.submit();" />
                <label class="form-check-label" for="cform_template_text_copy">Text</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="cform_template_format_copy" id="cform_template_html_copy" value="1"<?php is_checked(1, $content['form']["template_format_copy"]) ?> onchange="sessionStorage.setItem('phpwcms_cform_active_tab', '#cform-pane-templates');this.form.submit();" />
                <label class="form-check-label pe-sm-4" for="cform_template_html_copy">HTML</label>
            </div>
      </div>

    	<div class="col-sm-auto">
    		<?php
    		if($for_select != '') {
    			echo '<div class="input-group">';
    			echo '<select name="phc" id="phc" class="form-select form-select-sm" ';
    			echo 'onchange="insertAtCursorPos(document.articlecontent.cform_template_copy, ';
    			echo 'document.articlecontent.phc.options[document.articlecontent.phc.selectedIndex].value);">';
                echo '<option value="">' . $BL['be_newsletter_placeholder'] . '</option>';
    			echo $for_select;
    			echo '<option value="{FORM_URL}">{FORM_URL}</option>';
    			echo '<option value="{REMOTE_IP}">{REMOTE_IP}</option>';
    			echo '<option value="{DATE:y/m/d H:i:s}">{DATE:y/m/d H:i:s}</option>';
    			echo '</select>';

    			echo '';
    			echo '<a class="btn btn-sm btn-blue insert px-3" onclick="insertAtCursorPos(document.articlecontent.cform_template_copy, ';
    			echo 'document.articlecontent.phc.options[document.articlecontent.phc.selectedIndex].value);"><i class="fa-solid fa-angle-right" aria-hidden="true"></i></a>';
    			echo '';
    			echo '</div>';
    		}
    		?>
    	</div>
    </div>

    <div id="copytemplate2"></div>

    <div class="form-group row g-2">
    	<label class="col-sm-2 col-form-label"></label>
    	<div id="copytemplate3" class="col">
    	<?php
    	if($content['form']["template_format_copy"]) {
    			$wysiwyg_editor = array(
    					'value'     => $content['form']["template_copy"],
    					'field'     => 'cform_template_copy',
    					'height'    => '350px',
    					'width'     => '100%',
    					'rows'      => '15',
    					'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    					'lang'      => 'en'
    			);
    			include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';
    	} else {

    			echo '<textarea name="cform_template_copy" id="cform_template_copy" rows="5" class="form-control form-control-sm code-editor" data-mode="html">';
    			echo html($content['form']["template_copy"]).'</textarea>';
    	}
    	?>
    	</div>
    </div>

    <div class="form-group align-items-center row g-2">
      <label for="cform_onsuccess" class="col-sm-2 col-form-label text-end"><?php echo $BL['php_function']?></label>
      <div class="col">
        <input name="cform_function_cc" type="text" id="cform_function_cc" class="form-control form-control-sm" value="<?php echo html($content['form']['function_cc']) ?>" />
      </div>
    </div>

  </div>

  <!-- TAB 5: RESULT & DATABASE -->
  <div class="tab-pane fade" id="cform-pane-settings" role="tabpanel" aria-labelledby="cform-tab-settings">

    <div class="form-group row g-2">
        <label for="cform_savedb" class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_cnt_result'] ?></label>
        <div class="col-sm-auto">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="cform_savedb" id="cform_savedb" value="1" <?php echo is_checked(1, $content['form']["savedb"], 0, 0) ?> />
                <label class="form-check-label" for="cform_savedb"><?php echo $BL['be_cnt_formsave_in_db'] ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="cform_saveprofile" id="cform_saveprofile" value="1" <?php echo is_checked(1, $content['form']["saveprofile"], 0, 0) ?> onchange="sessionStorage.setItem('phpwcms_cform_active_tab', '#cform-pane-settings');this.form.submit();" />
                <label class="form-check-label" for="cform_saveprofile"><?php echo $BL['be_cnt_formsave_profile'] ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="cform_tracking_off" id="cform_tracking_off" value="1" <?php echo is_checked(1, $content['form']["formtracking_off"], 0, 0) ?> />
                <label class="form-check-label" for="cform_tracking_off"><?php echo $BL['be_cnt_field']['formtracking_off'] ?></label>
            </div>
        </div>
        <div class="col ms-sm-5">
            <?php
            // check form entries
            $result_download_link = 'include/inc_act/act_export.php?' . CSRF_GET_TOKEN . '&amp;action=exportformresult&amp;fid=' . $content['id'];
            if($content["id"]):
                $entries = _dbQuery('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_formresult WHERE formresult_pid='.$content['id'], 'COUNT');
                if($entries > 0):
                    ?>
                    <button class="btn btn-success text-nowrap" onclick="window.open('<?php echo $result_download_link; ?>', '_new');" class="p-3">
                        <i class="fa-solid fa-file-excel text-light"></i>
                        <?php echo $BL['be_cnt_download']; ?>
                        <span class="badge rounded-pill bg-light"><?php echo $entries; ?></span>
                    </button>
                    <?php
                endif;
            endif;
            $result_download_link = PHPWCMS_URL . 'include/inc_act/act_export.php?action=exportformresult&amp;fid=' . $content['id'] . '&amp;apikey=' . html($content['form']['direct_download_apikey']);
            ?>
        </div>
    </div>

    <div class="form-group row g-2">
        <label for="cform_savedb" class="col-sm-2 col-form-label text-end pt-1"><?php echo $BL['be_cnt_form_direct_download_apikey'] ?></label>
        <div class="col-sm-auto">
            <input type="hidden" name="direct_download_apikey" id="direct_download_apikey" value="<?php echo html($content['form']['direct_download_apikey']) ?>" />
            <div class="input-group input-group-sm">
                <span class="input-group-text">
                    <input type="checkbox" class="form-check-input me-1" name="cform_direct_download" id="cform_direct_download" value="1" <?php echo is_checked(1, $content['form']["direct_download"], 0, 0) ?> />
                    <label class="form-check-label" for="cform_direct_download"><?php echo $BL['be_cnt_form_direct_download'] ?></label>
                </span>
                <span id="direct_download_apikey_display" class="form-control fw-bold text-primary"><?php echo html($content['form']['direct_download_apikey']) ?></span>
                <button class="btn btn-secondary" type="button" onclick="resetApiKey(this);">
                    <i class="fa-solid fa-sync" aria-hidden="true"></i>
                    <?php echo $BL['be_cnt_form_apikey_reset']; ?>
                </button>
                <button class="btn btn-blue" type="button" onclick="copyToClipboard('<?php echo $result_download_link; ?>');return false;" title="<?php echo $BL['copy_to_clipboard'] . ': ' . $result_download_link; ?>" id="copy_link_to_clipboard">
                    <i class="fa-solid fa-copy" aria-hidden="true"></i>
                    <?php echo $BL['be_copy_link']; ?>
                </button>
            </div>
            <script type="text/javascript">
            function resetApiKey(btn) {
                var bytes = new Uint8Array(8);
                (window.crypto || window.msCrypto).getRandomValues(bytes);
                var key = '';
                for (var i = 0; i < bytes.length; i++) {
                    key += ('0' + bytes[i].toString(16)).slice(-2);
                }
                document.getElementById('direct_download_apikey').value = key;
                document.getElementById('direct_download_apikey_display').textContent = key;
                var copyBtn = document.getElementById('copy_link_to_clipboard');
                var link = '<?php echo PHPWCMS_URL . 'include/inc_act/act_export.php?action=exportformresult&fid=' . $content['id'] . '&apikey='; ?>' + key;
                copyBtn.setAttribute('onclick', "copyToClipboard('" + link + "');return false;");
                copyBtn.setAttribute('title', '<?php echo $BL['copy_to_clipboard']; ?>: ' + link);
                btn.blur();
            }
            </script>
        </div>
    </div>

  </div>

</div>
<?php

if(!empty($field_counter) && $field_counter > 1) {

    echo '<script type="text/javascript">'.LF;

    echo 'function hideAllFormFields() {'.LF;
    echo implode(LF, $field_js['hideAll']);
    echo LF.'}'.LF;

    echo 'function showAllFormFields() {'.LF;
    echo implode(LF, $field_js['showAll']);
    echo LF.'}'.LF.LF;

    echo 'hideAllFormFields();'.LF.LF;

    // set options lists
    if($content['form']["saveprofile"]) {

        $field_js['options'] = '';
        foreach($content['profile_fields_varchar'] as $fieldKey => $fieldValue) {
            $field_js['options'] .= '<"+"option value=\"'.$fieldKey.'\">'.$fieldValue.'<"+"/option>';
        }

        foreach($field_js['varcharFields'] as $tdID => $tdIDvalue) {

            $field_value  = 'document.getElementById("cform_field_profile_'.$tdID.'_td").innerHTML = "';
            $field_value .= '<"+"select name=\"cform_field_profile['.$tdID.']\" id=\"cform_field_profile_'.$tdID.'\" class=\"form-control form-control-sm\">';
            $field_value .= '<"+"option value=\"\">-<"+"/option>';
            $field_value .= $tdIDvalue;
            $field_value .= $field_js['options'];
            $field_value .= '<"+"/select>";'.LF;

            echo $field_value;

        }

        $field_js['options'] = '';
        foreach($content['profile_fields_longtext'] as $fieldKey => $fieldValue) {
            $field_js['options'] .= '<"+"option value=\"'.$fieldKey.'\">'.$fieldValue.'<"+"/option>';
        }

        foreach($field_js['longtextFields'] as $tdID => $tdIDvalue) {

            $field_value  = 'document.getElementById("cform_field_profile_'.$tdID.'_td").innerHTML = "';
            $field_value .= '<"+"select name=\"cform_field_profile['.$tdID.']\" id=\"cform_field_profile_'.$tdID.'\" class=\"form-control form-control-sm\">';
            $field_value .= '<"+"option value=\"\">-<"+"/option>';
            $field_value .= $tdIDvalue;
            $field_value .= $field_js['options'];
            $field_value .= '<"+"/select>";'.LF;

            echo $field_value;

        }
    }

    echo ' var i; ';

    foreach(array('recaptcha', 'recaptchainv', 'captcha', 'captchaimg', 'mathspam', 'submit') as $field_type) {

        if(isset($field_type_count[$field_type])) {
            echo ' var options_'.$field_type.' = document.getElementsByClassName("disable-'.$field_type.'"); ';
            echo ' for(i=0; i < options_'.$field_type.'.length; i++){options_'.$field_type.'[i].disabled=true;} ';
        }

    }

    echo LF.'</script>';
}

?>
