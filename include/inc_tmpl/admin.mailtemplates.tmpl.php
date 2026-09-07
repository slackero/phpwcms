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
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------
if (function_exists('initAceEditor')) {
    initAceEditor();
}

// Determine available languages
$allowed_langs = [];
if (!empty($phpwcms['allowed_lang']) && is_array($phpwcms['allowed_lang'])) {
    foreach ($phpwcms['allowed_lang'] as $al) {
        $clean_al = sanitize_language_code($al);
        if ($clean_al !== '') {
            $allowed_langs[$clean_al] = $clean_al;
        }
    }
}
$default_lang = !empty($phpwcms['default_lang']) ? sanitize_language_code($phpwcms['default_lang']) : 'en';
if (empty($default_lang)) {
    $default_lang = 'en';
}
$allowed_langs[$default_lang] = $default_lang;
$allowed_langs['en'] = 'en';
$allowed_langs['de'] = 'de';

// Also check for language JSON files in include/inc_lang/email/
$email_lang_dir = PHPWCMS_ROOT . '/include/inc_lang/email';
if (is_dir($email_lang_dir)) {
    foreach (glob($email_lang_dir . '/*.json') as $json_path) {
        $json_code = sanitize_language_code(pathinfo($json_path, PATHINFO_FILENAME));
        if ($json_code !== '') {
            $allowed_langs[$json_code] = $json_code;
        }
    }
}
ksort($allowed_langs);

// Current selected language
$current_lang = isset($_GET['lang']) ? sanitize_language_code($_GET['lang']) : (strtolower($_SESSION['wcs_user_lang'] ?? $default_lang));
if (!isset($allowed_langs[$current_lang])) {
    $current_lang = $default_lang;
}

$action_msg = '';
$action_error = '';

$definitions = get_system_email_definitions();
$edit_key = isset($_GET['edit']) ? clean_slweg($_GET['edit']) : '';
if (!isset($definitions[$edit_key])) {
    $edit_key = '';
}

// -----------------------------------------------------------------------------
// POST Actions: Save, Reset to Default, Send Test
// -----------------------------------------------------------------------------
if (!empty($_POST['save_mail_template'])) {
    if (validate_csrf_get_token()) {
        $post_key     = clean_slweg($_POST['tpl_key'] ?? '');
        $post_lang    = sanitize_language_code($_POST['tpl_lang'] ?? $current_lang);
        $post_subject = clean_slweg($_POST['tpl_subject'] ?? '');
        $post_html    = slweg($_POST['tpl_content_html'] ?? '');
        $post_text    = slweg($_POST['tpl_content_text'] ?? '');
        $post_active  = empty($_POST['tpl_active']) ? 0 : 1;

        if (isset($definitions[$post_key]) && $post_lang !== '') {
            $data = [
                'tpl_key'          => $post_key,
                'tpl_lang'         => $post_lang,
                'tpl_subject'      => $post_subject,
                'tpl_content_html' => $post_html,
                'tpl_content_text' => $post_text,
                'tpl_active'       => $post_active
            ];

            $saved = _dbInsertOrUpdate('mailtemplates', $data);
            if ($saved !== false) {
                $action_msg = $BL['be_admin_mail_saved'] ?? 'Email template saved successfully.';
                if (!empty($_POST['save_and_close'])) {
                    headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=19&lang=' . urlencode($post_lang) . '&msg=saved');
                } else {
                    headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=19&lang=' . urlencode($post_lang) . '&edit=' . urlencode($post_key) . '&msg=saved');
                }
            } else {
                $action_error = $BL['be_admin_mail_err_save'] ?? 'Error saving template to database.';
            }
        }
    }
}

// Reset to Default Action
if (isset($_GET['reset']) && !empty($_GET['reset'])) {
    if (validate_csrf_get_token()) {
        $reset_key = clean_slweg($_GET['reset']);
        if (isset($definitions[$reset_key])) {
            $del_sql = 'DELETE FROM ' . DB_PREPEND . 'mailtemplates WHERE tpl_key = ' . _dbEscape($reset_key) . ' AND tpl_lang = ' . _dbEscape($current_lang);
            _dbQuery($del_sql, 'DELETE');
            headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=19&lang=' . urlencode($current_lang) . '&msg=reset');
        }
    }
}

// Toggle Active State Action
if (isset($_GET['toggle_active']) && !empty($_GET['toggle_active'])) {
    if (validate_csrf_get_token()) {
        $toggle_key = clean_slweg($_GET['toggle_active']);
        if (isset($definitions[$toggle_key])) {
            $tpl_info = get_system_email_template($toggle_key, $current_lang);
            if (!empty($tpl_info['is_custom'])) {
                $new_state = empty($tpl_info['active']) ? 1 : 0;
                $toggle_sql = 'UPDATE ' . DB_PREPEND . 'mailtemplates SET tpl_active = ' . $new_state . ' WHERE tpl_key = ' . _dbEscape($toggle_key) . ' AND tpl_lang = ' . _dbEscape($current_lang);
                _dbQuery($toggle_sql, 'UPDATE');
            }
            headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=19&lang=' . urlencode($current_lang));
        }
    }
}

// Send Test Email Action
if (!empty($_POST['send_test_mail'])) {
    if (validate_csrf_get_token()) {
        $test_recipient = clean_slweg($_POST['test_email'] ?? '');
        $post_key       = clean_slweg($_POST['tpl_key'] ?? '');
        $post_lang      = sanitize_language_code($_POST['tpl_lang'] ?? $current_lang);

        if (is_valid_email($test_recipient) && isset($definitions[$post_key])) {
            $dummy_vars = [
                '{NAME}'       => $_SESSION['wcs_user_name'] ?? $_SESSION['wcs_user'],
                '{LOGIN}'      => $_SESSION['wcs_user'],
                '{PASSWORD}'   => '•••••••• (Sample)',
                '{NEW_EMAIL}'  => 'new-user@example.com',
                '{OLD_EMAIL}'  => 'old-user@example.com',
                '{IP}'         => PHPWCMS_GDPR_MODE ? getAnonymizedIp() : getRemoteIP(),
                '{DATE}'       => date('Y-m-d H:i:s'),
                '{SITE}'       => PHPWCMS_HOST,
                '{SITE_URL}'   => PHPWCMS_URL,
                '{LOGIN_PAGE}' => PHPWCMS_URL . get_login_file(),
                '{RESET_LINK}' => PHPWCMS_URL . get_login_file() . '?reset_token=SAMPLE_TOKEN_PREVIEW&u=' . (int)$_SESSION['wcs_user_id'],
                '{EXPIRES}'    => '1 hour',
                '{ADMIN_EMAIL}'=> $phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? 'admin@' . PHPWCMS_HOST
            ];

            if (in_array($post_key, ['mail_layout', 'mail_header', 'mail_footer'], true)) {
                $sample_body = '<p>This is a preview of the email layout/component containing sample text.</p>'
                    . '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris.</p>'
                    . renderEmailButtonHTML(PHPWCMS_URL, 'Sample CTA Button')
                    . renderEmailSignatureHTML();
                $preview_title = $definitions[$post_key]['title'] ?? 'Sample Preview';
                $rendered = [
                    'subject' => '[TEST] ' . $preview_title,
                    'html'    => renderSystemEmailHTML('Sample Headline', $sample_body, 'Preview Preheader', $post_lang),
                    'text'    => "Sample Headline\n\nThis is a preview containing sample text.\n\n" . PHPWCMS_URL
                ];
            } else {
                $rendered = render_system_email($post_key, $dummy_vars, $post_lang);
            }

            $send_res = sendEmail([
                'recipient' => $test_recipient,
                'toName'    => $_SESSION['wcs_user_name'] ?? $_SESSION['wcs_user'],
                'subject'   => '[TEST] ' . $rendered['subject'],
                'isHTML'    => true,
                'html'      => $rendered['html'],
                'text'      => $rendered['text'],
                'from'      => $phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? '',
                'fromName'  => get_brand_name()
            ]);

            if ($send_res[0]) {
                $action_msg = str_replace('{EMAIL}', html($test_recipient), $BL['be_admin_mail_test_sent'] ?? 'Test email sent successfully to {EMAIL}.');
            } else {
                $action_error = ($BL['be_msg_err'] ?? 'Error while sending message') . ': ' . html($send_res[1]);
            }
        } else {
            $action_error = $BL['be_admin_usr_err4'] ?? 'Invalid email address.';
        }
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'saved') {
        $action_msg = $BL['be_admin_mail_saved'] ?? 'Email template saved successfully.';
    } elseif ($_GET['msg'] === 'reset') {
        $action_msg = $BL['be_admin_mail_reset_done'] ?? 'Template reset to system default.';
    }
}

// Fetch all database records for current language
$db_templates = get_mail_templates_from_db($current_lang);
$current_lang_upper = strtoupper($current_lang);
$current_lang_name = $BL[$current_lang_upper] ?? '';
$current_lang_display = $current_lang_upper . ($current_lang_name !== '' ? ' - ' . $current_lang_name : '');
$current_flag_img = get_language_flag_img($current_lang, 'me-1');
?>

<div class="row align-items-center mb-3">
    <div class="col col-sm-auto text-center text-sm-start">
        <h1 class="mb-0"><?php echo html($BL['be_admin_mail_templates'] ?? 'Email Templates'); ?></h1>
    </div>
    <div class="col-12 col-sm text-center text-sm-end mt-2 mt-sm-0">
        <?php if ($edit_key !== ''): ?>
            <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=19&amp;lang=<?php echo urlencode($current_lang); ?>" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> <?php echo html($BL['be_admin_mail_back_to_list'] ?? 'Back to Overview'); ?>
            </a>
        <?php endif; ?>
    </div>
</div>

<p class="text-muted"><?php echo $BL['be_admin_mail_templates_desc'] ?? 'Manage and customize transactional emails sent by the system (subject, content, and placeholders).'; ?></p>

<?php if (!empty($action_msg)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo html($action_msg); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($action_error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo html($action_error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Language Filter Bar -->
<div class="card mb-4">
    <div class="card-body py-2">
        <?php $csrf_get = get_token_get_array(); ?>
        <form method="get" action="phpwcms.php" class="d-flex align-items-center flex-wrap" data-csrf="off">
            <input type="hidden" name="<?php echo html($csrf_get['name']); ?>" value="<?php echo html($csrf_get['value']); ?>" />
            <input type="hidden" name="do" value="admin" />
            <input type="hidden" name="p" value="19" />
            <?php if ($edit_key !== ''): ?>
                <input type="hidden" name="edit" value="<?php echo html($edit_key); ?>" />
            <?php endif; ?>

            <label class="me-2 fw-bold" for="sel_lang"><i class="fa-solid fa-globe me-1"></i> <?php echo html($BL['login_lang'] ?? 'Language'); ?>:</label>
            <select name="lang" id="sel_lang" class="form-select form-select-sm w-auto me-3" onchange="this.form.submit();">
                <?php foreach ($allowed_langs as $code): ?>
                    <?php
                        $code_upper = strtoupper($code);
                        $lang_name = $BL[$code_upper] ?? '';
                        $opt_label = $code_upper . ($lang_name !== '' ? ' - ' . $lang_name : '');
                        if ($code === $default_lang) {
                            $opt_label .= ' (' . ($BL['be_admin_tmpl_default'] ?? 'Default') . ')';
                        }
                    ?>
                    <option value="<?php echo html($code); ?>" <?php is_selected($code, $current_lang); ?>>
                        <?php echo html($opt_label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<?php if ($edit_key === ''): ?>
    <!-- OVERVIEW LIST VIEW -->
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h2 class="mb-0">
                <i class="fa-solid fa-envelope-open-text me-2"></i><?php echo html($BL['be_admin_mail_templates'] ?? 'System Email Templates'); ?>
                <span class="badge text-bg-info ms-2 fw-normal badge-align badge-align-t2"><?php echo $current_flag_img; ?><?php echo html($current_lang_display); ?></span>
            </h2>
            <span class="badge text-bg-secondary"><?php echo count($definitions); ?> <?php echo html($BL['be_admin_template_lang_items'] ?? 'Items'); ?></span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 25%;"><?php echo html($BL['be_admin_mail_name'] ?? 'Template Name'); ?></th>
                        <th style="width: 40%;"><?php echo html($BL['be_admin_mail_subject'] ?? 'Subject'); ?></th>
                        <th style="width: 15%;"><?php echo html($BL['be_fpriv_status'] ?? 'Status'); ?></th>
                        <th style="width: 20%;" class="text-end"><?php echo html($BL['be_admin_custom_cpt_table_actions'] ?? 'Actions'); ?></th>
                    </tr>
                </thead>
                    <?php foreach ($definitions as $key => $def): ?>
                        <?php if ($key === 'mail_layout'): ?>
                </tbody>
                <thead class="table-light">
                    <tr>
                        <th style="width: 25%;"><?php echo html($BL['be_admin_mail_template'] ?? 'Email Template'); ?></th>
                        <th style="width: 40%;"></th>
                        <th style="width: 15%;"><?php echo html($BL['be_fpriv_status'] ?? 'Status'); ?></th>
                        <th style="width: 20%;" class="text-end"><?php echo html($BL['be_admin_custom_cpt_table_actions'] ?? 'Actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                        <?php endif; ?>
                        <?php
                            $tpl_data = get_system_email_template($key, $current_lang);
                            $is_custom = $tpl_data['is_custom'];
                            $is_active = !empty($tpl_data['active']);
                            $edit_url = 'phpwcms.php?' . get_token_get_string() . '&amp;do=admin&amp;p=19&amp;lang=' . urlencode($current_lang) . '&amp;edit=' . urlencode($key);
                            $toggle_url = 'phpwcms.php?' . get_token_get_string() . '&amp;do=admin&amp;p=19&amp;lang=' . urlencode($current_lang) . '&amp;toggle_active=' . urlencode($key);
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo $edit_url; ?>" class="fw-bold text-decoration-none">
                                    <?php echo html($def['title']); ?>
                                </a>
                                <div class="text-muted small font-monospace text-secondary">
                                    <code><?php echo html($key); ?></code>
                                </div>
                                <div class="text-muted small">
                                    <?php echo html($def['desc']); ?>
                                </div>
                            </td>
                            <td>
                                <?php if (in_array($key, ['mail_layout', 'mail_header', 'mail_footer'], true)): ?>
                                    <span class="text-muted fst-italic">&mdash;</span>
                                <?php else: ?>
                                    <div class="text-truncate" style="max-width: 380px;" title="<?php echo html($tpl_data['subject']); ?>">
                                        <?php echo html($tpl_data['subject']); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($is_custom): ?>
                                    <div class="d-inline-flex align-items-center">
                                        <span class="badge text-bg-info d-inline-flex align-items-center justify-content-center" style="height: 24px; font-size: 0.75rem;"><i class="fa-solid fa-sliders me-1"></i> <?php echo html($BL['be_admin_mail_customized'] ?? 'Customized'); ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="d-inline-flex align-items-center">
                                        <span class="badge text-bg-light border text-muted d-inline-flex align-items-center justify-content-center" style="height: 24px; font-size: 0.75rem;"><i class="fa-solid fa-code me-1"></i> <?php echo html($BL['be_admin_tmpl_default'] ?? 'System Default'); ?></span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-nowrap">
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php if ($is_custom): ?>
                                        <a href="<?php echo $toggle_url; ?>" class="btn btn-sm d-inline-flex align-items-center justify-content-center <?php echo $is_active ? 'btn-success' : 'btn-warning'; ?>" style="height: 28px; min-width: 32px;" data-bs-toggle="tooltip" title="<?php echo html($is_active ? ($BL['be_active'] ?? 'Active') : ($BL['be_admin_mail_inactive'] ?? 'Inactive')); ?>">
                                            <i class="fa-solid <?php echo $is_active ? 'fa-eye' : 'fa-eye-slash'; ?> fa-fw"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-success disabled" disabled aria-disabled="true" style="pointer-events: none; opacity: 0.5; height: 28px; min-width: 32px;" data-bs-toggle="tooltip" title="<?php echo html($BL['be_active'] ?? 'Active'); ?>">
                                            <i class="fa-solid fa-eye fa-fw"></i>
                                        </button>
                                    <?php endif; ?>
                                    <a href="<?php echo $edit_url; ?>" class="btn btn-blue btn-sm d-inline-flex align-items-center justify-content-center" style="height: 28px; min-width: 32px;" data-bs-toggle="tooltip" title="<?php echo html($BL['be_tt_edit'] ?? 'Edit'); ?>">
                                        <i class="fa-solid fa-pencil-alt fa-fw"></i>
                                    </a>
                                </div>
                                <?php if ($is_custom): ?>
                                    <?php
                                        $confirm_msg = str_replace('{NAME}', $def['title'], $BL['be_admin_mail_confirm_reset'] ?? 'Reset this template to the default system version?');
                                    ?>
                                    <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=19&amp;lang=<?php echo urlencode($current_lang); ?>&amp;reset=<?php echo urlencode($key); ?>"
                                       class="btn btn-danger btn-sm ms-1 confirm-link d-inline-flex align-items-center justify-content-center"
                                       style="height: 28px; min-width: 32px;"
                                       data-confirm-type="danger"
                                       data-confirm-action="<?php echo html($BL['modal_ok'] ?? 'OK'); ?>"
                                       data-confirm="<?php echo html($confirm_msg); ?>"
                                       data-bs-toggle="tooltip"
                                       title="<?php echo html($BL['be_admin_mail_reset_default'] ?? 'Reset to default'); ?>">
                                        <i class="fa-solid fa-rotate-left fa-fw"></i>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-danger btn-sm ms-1 disabled" disabled aria-disabled="true" style="pointer-events: none; opacity: 0.5; height: 28px; min-width: 32px;" data-bs-toggle="tooltip" title="<?php echo html($BL['be_admin_mail_reset_default'] ?? 'Reset to default'); ?>">
                                        <i class="fa-solid fa-rotate-left fa-fw"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>
    <!-- EDIT TEMPLATE VIEW -->
    <?php
        $curr_def = $definitions[$edit_key];
        $curr_tpl = get_system_email_template($edit_key, $current_lang);
    ?>
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h2 class="mb-0">
                <i class="fa-solid fa-pencil-alt me-2"></i><?php echo html($curr_def['title']); ?>
                <span class="badge text-bg-info ms-2 fw-normal badge-align badge-align-t2"><?php echo $current_flag_img; ?><?php echo html($current_lang_display); ?></span>
            </h2>
            <div class="d-inline-flex align-items-center gap-1">
                <?php if ($curr_tpl['is_custom']): ?>
                    <span class="badge text-bg-info d-inline-flex align-items-center justify-content-center" style="height: 28px; font-size: 0.8rem;"><i class="fa-solid fa-sliders me-1"></i> <?php echo html($BL['be_admin_mail_customized'] ?? 'Customized'); ?></span>
                    <?php if (!empty($curr_tpl['active'])): ?>
                        <span class="badge text-bg-success d-inline-flex align-items-center justify-content-center" style="height: 28px; font-size: 0.8rem;"><i class="fa-solid fa-eye me-1"></i> <?php echo html($BL['be_active'] ?? 'Active'); ?></span>
                    <?php else: ?>
                        <span class="badge text-bg-warning d-inline-flex align-items-center justify-content-center" style="height: 28px; font-size: 0.8rem;"><i class="fa-solid fa-eye-slash me-1"></i> <?php echo html($BL['be_admin_mail_inactive'] ?? 'Inactive'); ?></span>
                    <?php endif; ?>
                    <?php
                        $confirm_msg_edit = str_replace('{NAME}', $curr_def['title'], $BL['be_admin_mail_confirm_reset'] ?? 'Reset this template to the default system version?');
                    ?>
                    <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=19&amp;lang=<?php echo urlencode($current_lang); ?>&amp;reset=<?php echo urlencode($edit_key); ?>"
                       class="btn btn-danger btn-sm confirm-link d-inline-flex align-items-center justify-content-center ms-1"
                       style="height: 28px; font-size: 0.8rem;"
                       data-confirm-type="danger"
                       data-confirm-action="<?php echo html($BL['modal_ok'] ?? 'OK'); ?>"
                       data-confirm="<?php echo html($confirm_msg_edit); ?>">
                        <i class="fa-solid fa-rotate-left me-1"></i> <?php echo html($BL['be_admin_mail_reset_default'] ?? 'Reset to Default'); ?>
                    </a>
                <?php else: ?>
                    <span class="badge text-bg-light border text-muted d-inline-flex align-items-center justify-content-center" style="height: 28px; font-size: 0.8rem;"><i class="fa-solid fa-code me-1"></i> <?php echo html($BL['be_admin_tmpl_default'] ?? 'System Default'); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4"><?php echo html($curr_def['desc']); ?></p>

            <form action="phpwcms.php?do=admin&amp;p=19&amp;lang=<?php echo urlencode($current_lang); ?>&amp;edit=<?php echo urlencode($edit_key); ?>" method="post" id="mailTplForm">
                <input type="hidden" name="save_mail_template" value="1" />
                <input type="hidden" name="tpl_key" value="<?php echo html($edit_key); ?>" />
                <input type="hidden" name="tpl_lang" value="<?php echo html($current_lang); ?>" />

                <!-- Subject -->
                <?php if (in_array($edit_key, ['mail_layout', 'mail_header', 'mail_footer'], true)): ?>
                    <input type="hidden" name="tpl_subject" value="" />
                <?php else: ?>
                    <div class="form-group row g-2 mb-3">
                        <label for="tpl_subject" class="col-sm-2 col-form-label text-end fw-bold"><?php echo html($BL['be_admin_mail_subject'] ?? 'Subject'); ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-sm" name="tpl_subject" id="tpl_subject" value="<?php echo html($curr_tpl['subject']); ?>" required />
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Placeholders Info Box -->
                <div class="form-group row g-2 mb-3">
                    <label class="col-sm-2 col-form-label text-end fw-bold text-muted"><?php echo html($BL['be_admin_mail_placeholders'] ?? 'Placeholders'); ?></label>
                    <div class="col-sm-10">
                        <div class="card bg-light border-light p-2 small">
                            <div class="row g-2">
                                <?php foreach ($curr_def['placeholders'] as $ph => $ph_desc): ?>
                                    <div class="col-md-6">
                                        <a href="#" class="badge bg-secondary font-monospace text-decoration-none me-1" onclick="insertPlaceholder('<?php echo js_singlequote($ph); ?>'); return false;" title="<?php echo html($BL['be_admin_custom_cpt_copy'] ?? 'Insert'); ?>">
                                            <?php echo html($ph); ?>
                                        </a>
                                        <span class="text-muted"><?php echo html($ph_desc); ?></span>
                                    </div>
                                <?php endforeach; ?>
                                <?php if (!in_array($edit_key, ['mail_layout', 'mail_header', 'mail_footer'], true)): ?>
                                    <div class="col-md-6">
                                        <a href="#" class="badge bg-secondary font-monospace text-decoration-none me-1" onclick="insertPlaceholder('{BUTTON}'); return false;">
                                            {BUTTON}
                                        </a>
                                        <span class="text-muted"><?php echo html($BL['be_admin_mail_ph_button'] ?? 'Call-to-action button'); ?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#" class="badge bg-secondary font-monospace text-decoration-none me-1" onclick="insertPlaceholder('{SIGNATURE}'); return false;">
                                            {SIGNATURE}
                                        </a>
                                        <span class="text-muted"><?php echo html($BL['be_admin_mail_ph_signature'] ?? 'Closing signature block'); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- HTML Content Tab & Text Content Tab -->
                <div class="form-group row g-2 mb-4">
                    <label class="col-sm-2 col-form-label text-end fw-bold"><?php echo html($BL['be_cnt_texts'] ?? 'Content'); ?></label>
                    <div class="col-sm-10">
                        <ul class="nav nav-tabs" id="mailTplTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="html-tab" data-bs-toggle="tab" data-bs-target="#html-panel" type="button" role="tab" aria-controls="html-panel" aria-selected="true">
                                    <i class="fa-brands fa-html5 text-danger me-1"></i> HTML Content
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="text-tab" data-bs-toggle="tab" data-bs-target="#text-panel" type="button" role="tab" aria-controls="text-panel" aria-selected="false">
                                    <i class="fa-solid fa-align-left text-primary me-1"></i> Plain Text Content
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content border border-top-0 rounded-bottom p-3 bg-white" id="mailTplTabsContent">
                            <div class="tab-pane fade show active" id="html-panel" role="tabpanel" aria-labelledby="html-tab">
                                <div class="mb-2 text-muted small">
                                    <?php
                                        if ($edit_key === 'mail_layout') {
                                            echo $BL['be_admin_mail_layout_html_note'] ?? 'The master HTML document structure. Use <code>{CONTENT}</code> for the inner email message, <code>{HEADER}</code> and <code>{FOOTER}</code> for standard branding, and <code>{TITLE}</code> for the headline.';
                                        } elseif (in_array($edit_key, ['mail_header', 'mail_footer'], true)) {
                                            echo $BL['be_admin_mail_component_html_note'] ?? 'This sub-template is rendered into the master email layout at <code>{HEADER}</code> or <code>{FOOTER}</code>.';
                                        } else {
                                            echo $BL['be_admin_mail_html_note'] ?? 'The HTML markup is wrapped into the standard responsive email layout automatically (header logo, container, footer).';
                                        }
                                    ?>
                                </div>
                                <textarea name="tpl_content_html" rows="12" class="form-control form-control-sm autosize font-monospace code-editor" data-mode="html" id="tpl_content_html"><?php echo html($curr_tpl['content_html']); ?></textarea>
                            </div>
                            <div class="tab-pane fade" id="text-panel" role="tabpanel" aria-labelledby="text-tab">
                                <div class="mb-2 text-muted small">
                                    <?php echo $BL['be_admin_mail_text_note'] ?? 'Fallback text for clients that do not support HTML emails.'; ?>
                                </div>
                                <textarea name="tpl_content_text" rows="12" class="form-control form-control-sm autosize font-monospace" id="tpl_content_text"><?php echo html($curr_tpl['content_text']); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Toggle -->
                <div class="form-group row g-2 mb-4 align-items-center">
                    <label class="col-sm-2 col-form-label text-end fw-bold"><?php echo html($BL['be_active'] ?? $BL['be_ftptakeover_active'] ?? 'Active'); ?></label>
                    <div class="col-sm-10">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="tpl_active" name="tpl_active" value="1" <?php is_checked($curr_tpl['active'], 1); ?> />
                            <label class="form-check-label text-muted" for="tpl_active"><?php echo html($BL['be_admin_mail_active_desc'] ?? 'Use customized version when sending emails'); ?></label>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="form-group row g-2 mb-3">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" name="save_only" value="1" class="btn btn-sm btn-blue me-1">
                            <i class="fa-solid fa-rotate me-1"></i> <?php echo html($BL['be_save_btn'] ?? 'Save'); ?>
                        </button>
                        <button type="submit" name="save_and_close" value="1" class="btn btn-sm btn-blue me-2">
                            <i class="fa-solid fa-check me-1"></i> <?php echo html($BL['be_article_cnt_button3'] ?? 'Save & close'); ?>
                        </button>
                        <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=19&amp;lang=<?php echo urlencode($current_lang); ?>" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-times me-1"></i> <?php echo html($BL['be_admin_struct_close'] ?? 'Close'); ?>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Send Test Email Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title h6 mb-0"><i class="fa-solid fa-paper-plane me-2"></i><?php echo html($BL['be_admin_mail_test_title'] ?? 'Send Test Email'); ?></h3>
        </div>
        <div class="card-body">
            <p class="text-muted small mb-3"><?php echo html($BL['be_admin_mail_test_desc'] ?? 'Send a rendered preview of this email with sample data to verify design and formatting.'); ?></p>

            <form action="phpwcms.php?do=admin&amp;p=19&amp;lang=<?php echo urlencode($current_lang); ?>&amp;edit=<?php echo urlencode($edit_key); ?>" method="post" class="row g-2 align-items-center">
                <input type="hidden" name="send_test_mail" value="1" />
                <input type="hidden" name="tpl_key" value="<?php echo html($edit_key); ?>" />
                <input type="hidden" name="tpl_lang" value="<?php echo html($current_lang); ?>" />

                <div class="col-auto">
                    <label for="test_email" class="col-form-label col-form-label-sm fw-bold"><?php echo html($BL['be_profile_account_email'] ?? 'Recipient Email'); ?>:</label>
                </div>
                <div class="col-sm-4">
                    <input type="email" name="test_email" id="test_email" class="form-control form-control-sm" value="<?php echo html($_SESSION['wcs_user_email'] ?? ''); ?>" required />
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-blue">
                        <i class="fa-solid fa-paper-plane me-1"></i> <?php echo html($BL['be_admin_mail_send_test'] ?? 'Send Test'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function insertPlaceholder(placeholder) {
        var activeTab = document.querySelector('#mailTplTabs .nav-link.active');
        var targetId = (activeTab && activeTab.id === 'text-tab') ? 'tpl_content_text' : 'tpl_content_html';
        var el = document.getElementById(targetId);
        if (!el) return;

        if (typeof insertAtCursorPos === 'function') {
            insertAtCursorPos(el, placeholder);
        } else {
            var start = el.selectionStart || 0;
            var end = el.selectionEnd || 0;
            var text = el.value || '';
            el.value = text.substring(0, start) + placeholder + text.substring(end);
            el.selectionStart = el.selectionEnd = start + placeholder.length;
            el.focus();
        }
    }
    </script>
<?php endif; ?>
