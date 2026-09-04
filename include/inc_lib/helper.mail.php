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

/**
 * Get definition of all available system email placeholders and their localized descriptions
 *
 * @return array
 */
function get_system_email_placeholders(): array {
    global $BL;

    return [
        '{NAME}'        => $BL['be_admin_mail_ph_name'] ?? 'Recipient display name or username',
        '{LOGIN}'       => $BL['be_admin_mail_ph_login'] ?? 'Username / login',
        '{SITE}'        => $BL['be_admin_mail_ph_site'] ?? 'Website host name (e.g. example.com)',
        '{SITE_URL}'    => $BL['be_admin_mail_ph_site_url'] ?? 'Full website URL',
        '{LOGIN_PAGE}'  => $BL['be_admin_mail_ph_login_page'] ?? 'Direct URL to login page',
        '{ADMIN_EMAIL}' => $BL['be_admin_mail_ph_admin_email'] ?? 'Administrator contact email',
        '{RESET_LINK}'  => $BL['be_admin_mail_ph_reset_link'] ?? 'Password reset URL with secure token',
        '{EXPIRES}'     => $BL['be_admin_mail_ph_expires'] ?? 'Expiration time (e.g. 1 hour)',
        '{PASSWORD}'    => $BL['be_admin_mail_ph_password'] ?? 'Initial or updated password',
        '{NEW_EMAIL}'   => $BL['be_admin_mail_ph_new_email'] ?? 'New email address',
        '{OLD_EMAIL}'   => $BL['be_admin_mail_ph_old_email'] ?? 'Previous email address',
        '{IP}'          => $BL['be_admin_mail_ph_ip'] ?? 'Client IP address of the attempt',
        '{DATE}'        => $BL['be_admin_mail_ph_date'] ?? 'Date and time of the attempt',
        '{PREHEADER}'   => $BL['be_admin_mail_ph_preheader'] ?? 'Hidden preview text snippet',
        '{HEADER}'      => $BL['be_admin_mail_ph_header'] ?? 'Rendered email header (logo or site name)',
        '{TITLE}'       => $BL['be_admin_mail_ph_title'] ?? 'Email subject / headline',
        '{CONTENT}'     => $BL['be_admin_mail_ph_content'] ?? 'Body content of the specific email',
        '{FOOTER}'      => $BL['be_admin_mail_ph_footer'] ?? 'Rendered email footer / disclaimer',
    ];
}

/**
 * Load default email template contents from JSON language file(s)
 *
 * @param string|null $lang Target language code (default: 'en')
 * @return array
 */
function load_system_email_defaults(?string $lang = 'en'): array {
    static $cache = [];

    $clean_lang = !empty($lang) ? sanitize_language_code($lang) : 'en';
    if ($clean_lang === '') {
        $clean_lang = 'en';
    }

    if (isset($cache[$clean_lang])) {
        return $cache[$clean_lang];
    }

    $json_file = PHPWCMS_ROOT . '/include/inc_lang/email/' . $clean_lang . '.json';
    if (is_file($json_file)) {
        $content = file_get_contents($json_file);
        $data = json_decode($content, true);
        if (is_array($data)) {
            if (defined('PHPWCMS_CHARSET') && PHPWCMS_CHARSET !== 'utf-8') {
                array_walk_recursive($data, function (&$val) {
                    if (is_string($val)) {
                        $val = mb_convert_encoding($val, PHPWCMS_CHARSET, 'UTF-8');
                    }
                });
            }
            $cache[$clean_lang] = $data;
            return $cache[$clean_lang];
        }
    }

    // If target language file not found and target was not 'en', fall back to 'en'
    if ($clean_lang !== 'en') {
        $cache[$clean_lang] = load_system_email_defaults('en');
        return $cache[$clean_lang];
    }

    $cache[$clean_lang] = [];
    return $cache[$clean_lang];
}

/**
 * Registry of standard system email templates with definitions and placeholder lists
 *
 * @param string|null $merge_lang If specified, merges default templates for this language into 'defaults'
 * @return array
 */
function get_system_email_definitions(?string $merge_lang = null): array {
    global $BL;

    $all_placeholders = get_system_email_placeholders();

    $definitions = [
        'password_reset' => [
            'key' => 'password_reset',
            'title' => $BL['be_admin_mail_title_password_reset'] ?? 'Password Reset Request',
            'desc' => $BL['be_admin_mail_desc_password_reset'] ?? 'Sent to users requesting a password reset link on the login page.',
            'placeholder_keys' => [
                '{NAME}',
                '{LOGIN}',
                '{SITE}',
                '{SITE_URL}',
                '{RESET_LINK}',
                '{EXPIRES}'
            ]
        ],
        'new_user' => [
            'key' => 'new_user',
            'title' => $BL['be_admin_mail_title_new_user'] ?? 'New User Account Welcome',
            'desc' => $BL['be_admin_mail_desc_new_user'] ?? 'Sent when a new backend user account is created by an administrator.',
            'placeholder_keys' => [
                '{NAME}',
                '{LOGIN}',
                '{PASSWORD}',
                '{SITE}',
                '{SITE_URL}',
                '{LOGIN_PAGE}'
            ]
        ],
        'edit_user' => [
            'key' => 'edit_user',
            'title' => $BL['be_admin_mail_title_edit_user'] ?? 'User Account Updated',
            'desc' => $BL['be_admin_mail_desc_edit_user'] ?? 'Sent when an administrator updates a user account and checks the send email option.',
            'placeholder_keys' => [
                '{NAME}',
                '{LOGIN}',
                '{PASSWORD}',
                '{SITE}',
                '{SITE_URL}',
                '{LOGIN_PAGE}'
            ]
        ],
        'account_deactivated' => [
            'key' => 'account_deactivated',
            'title' => $BL['be_admin_mail_title_account_deactivated'] ?? 'Account Deactivation Notification',
            'desc' => $BL['be_admin_mail_desc_account_deactivated'] ?? 'Sent when an administrator deactivates a user account.',
            'placeholder_keys' => [
                '{NAME}',
                '{LOGIN}',
                '{SITE}',
                '{SITE_URL}',
                '{ADMIN_EMAIL}'
            ]
        ],
        'password_changed' => [
            'key' => 'password_changed',
            'title' => $BL['be_admin_mail_title_password_changed'] ?? 'Password Changed Confirmation',
            'desc' => $BL['be_admin_mail_desc_password_changed'] ?? 'Sent to the user when their account password has been successfully changed or reset.',
            'placeholder_keys' => [
                '{NAME}',
                '{LOGIN}',
                '{SITE}',
                '{SITE_URL}',
                '{LOGIN_PAGE}',
                '{ADMIN_EMAIL}'
            ]
        ],
        'user_email_changed' => [
            'key' => 'user_email_changed',
            'title' => $BL['be_admin_mail_title_user_email_changed'] ?? 'Email Address Changed Notification',
            'desc' => $BL['be_admin_mail_desc_user_email_changed'] ?? 'Sent to the previous email address when the account email address is changed.',
            'placeholder_keys' => [
                '{NAME}',
                '{LOGIN}',
                '{NEW_EMAIL}',
                '{OLD_EMAIL}',
                '{SITE}',
                '{SITE_URL}',
                '{LOGIN_PAGE}',
                '{ADMIN_EMAIL}'
            ]
        ],
        'account_locked' => [
            'key' => 'account_locked',
            'title' => $BL['be_admin_mail_title_account_locked'] ?? 'Account Security Alert (Suspicious Activity)',
            'desc' => $BL['be_admin_mail_desc_account_locked'] ?? 'Sent when multiple repeated failed login attempts are detected for an account.',
            'placeholder_keys' => [
                '{NAME}',
                '{LOGIN}',
                '{IP}',
                '{DATE}',
                '{SITE}',
                '{SITE_URL}',
                '{RESET_LINK}',
                '{ADMIN_EMAIL}'
            ]
        ],
        'mail_layout' => [
            'key' => 'mail_layout',
            'title' => $BL['be_admin_mail_title_mail_layout'] ?? 'Master Email Layout (HTML Shell)',
            'desc' => $BL['be_admin_mail_desc_mail_layout'] ?? 'The responsive HTML layout wrapping all transactional system emails (header, content container, and footer).',
            'placeholder_keys' => [
                '{PREHEADER}',
                '{HEADER}',
                '{TITLE}',
                '{CONTENT}',
                '{FOOTER}',
                '{SITE}',
                '{SITE_URL}',
                '{ADMIN_EMAIL}'
            ]
        ]
    ];

    // Build the localized placeholders map for each template
    foreach ($definitions as $key => $def) {
        $ph_map = [];
        foreach ($def['placeholder_keys'] as $ph_key) {
            if (isset($all_placeholders[$ph_key])) {
                $ph_map[$ph_key] = $all_placeholders[$ph_key];
            }
        }
        $definitions[$key]['placeholders'] = $ph_map;
    }

    // Merge language defaults if requested
    if (!empty($merge_lang)) {
        $lang_clean = sanitize_language_code($merge_lang);
        $lang_defs = load_system_email_defaults($lang_clean);
        $en_defs   = $lang_clean !== 'en' ? load_system_email_defaults('en') : $lang_defs;

        foreach ($definitions as $key => $def) {
            $definitions[$key]['defaults'][$lang_clean] = $lang_defs[$key] ?? ($en_defs[$key] ?? []);
        }
    }

    return $definitions;
}

/**
 * Get all customized email templates from the database
 *
 * @param string|null $lang Language code filter (optional)
 * @return array Keyed by [key][lang]
 */
function get_mail_templates_from_db(?string $lang = null): array {
    $where = '';
    if (!empty($lang)) {
        $where = 'tpl_lang = ' . _dbEscape($lang);
    }

    $rows = _dbGet('phpwcms_mailtemplates', '*', $where, '', 'tpl_key ASC, tpl_lang ASC');
    if (!is_array($rows)) {
        return [];
    }

    $result = [];
    foreach ($rows as $row) {
        $k = $row['tpl_key'];
        $l = $row['tpl_lang'];
        $result[$k][$l] = $row;
    }

    return $result;
}

/**
 * Get a specific email template by key and language, falling back to default language or JSON defaults
 *
 * @param string $key Template identifier (e.g. 'password_reset')
 * @param string|null $lang Language code (default: current backend lang or 'en')
 * @return array
 */
function get_system_email_template(string $key, ?string $lang = null): array {
    global $phpwcms;

    $target_lang = !empty($lang) ? sanitize_language_code($lang) : (sanitize_language_code($_SESSION['wcs_user_lang'] ?? $phpwcms['default_lang'] ?? 'en'));
    if ($target_lang === '') {
        $target_lang = 'en';
    }

    // Try loading custom template from DB
    $sql = 'SELECT * FROM `' . DB_PREPEND . 'phpwcms_mailtemplates` WHERE `tpl_key` = ' . _dbEscape($key) . ' AND `tpl_lang` = ' . _dbEscape($target_lang) . ' LIMIT 1';
    $res = _dbQuery($sql);
    if (!empty($res[0]['tpl_id'])) {
        return [
            'key'          => $key,
            'lang'         => $target_lang,
            'subject'      => $res[0]['tpl_subject'],
            'content_html' => $res[0]['tpl_content_html'],
            'content_text' => $res[0]['tpl_content_text'],
            'active'       => (int)$res[0]['tpl_active'],
            'is_custom'    => true,
            'tpl_id'       => (int)$res[0]['tpl_id']
        ];
    }

    // Load defaults from JSON (target language, then 'en')
    $lang_defs = load_system_email_defaults($target_lang);
    $defaults  = $lang_defs[$key] ?? [];
    if (empty($defaults) && $target_lang !== 'en') {
        $en_defs  = load_system_email_defaults('en');
        $defaults = $en_defs[$key] ?? [];
    }

    return [
        'key'          => $key,
        'lang'         => $target_lang,
        'subject'      => $defaults['subject'] ?? '',
        'content_html' => $defaults['content_html'] ?? '',
        'content_text' => $defaults['content_text'] ?? '',
        'active'       => 1,
        'is_custom'    => false,
        'tpl_id'       => 0
    ];
}

/**
 * Render a system email from template with variable replacement and standard HTML wrapper
 *
 * @param string $key Template identifier
 * @param array $data Variable substitutions (e.g. ['{NAME}' => 'John', '{RESET_LINK}' => '...'])
 * @param string|null $lang Language code
 * @return array ['subject' => string, 'html' => string, 'text' => string]
 */
function render_system_email(string $key, array $data = [], ?string $lang = null): array {
    global $phpwcms;

    $template = get_system_email_template($key, $lang);

    // If a custom template exists but is marked inactive, use default definition
    if (!empty($template['is_custom']) && empty($template['active'])) {
        $target_lang = !empty($lang) ? sanitize_language_code($lang) : (sanitize_language_code($_SESSION['wcs_user_lang'] ?? $phpwcms['default_lang'] ?? 'en'));
        if ($target_lang === '') {
            $target_lang = 'en';
        }
        $lang_defs = load_system_email_defaults($target_lang);
        $defaults  = $lang_defs[$key] ?? [];
        if (empty($defaults) && $target_lang !== 'en') {
            $en_defs  = load_system_email_defaults('en');
            $defaults = $en_defs[$key] ?? [];
        }
        $template['subject']      = $defaults['subject'] ?? '';
        $template['content_html'] = $defaults['content_html'] ?? '';
        $template['content_text'] = $defaults['content_text'] ?? '';
    }

    // Standard baseline placeholders
    $data['{SITE}']       = $data['{SITE}'] ?? PHPWCMS_HOST;
    $data['{SITE_URL}']   = $data['{SITE_URL}'] ?? PHPWCMS_URL;
    $data['{LOGIN_PAGE}'] = $data['{LOGIN_PAGE}'] ?? (PHPWCMS_URL . get_login_file());
    $data['{ADMIN_EMAIL}']= $data['{ADMIN_EMAIL}'] ?? ($phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? '');

    // Handle button placeholder if {BUTTON} is not set
    if (!isset($data['{BUTTON}'])) {
        if (!empty($data['{RESET_LINK}'])) {
            $data['{BUTTON}'] = renderEmailButtonHTML($data['{RESET_LINK}'], $GLOBALS['BL']['login_reset_title'] ?? 'Reset Password');
        } elseif (!empty($data['{LOGIN_PAGE}'])) {
            $data['{BUTTON}'] = renderEmailButtonHTML($data['{LOGIN_PAGE}'], $GLOBALS['BL']['login_button'] ?? 'Login');
        } else {
            $data['{BUTTON}'] = '';
        }
    }

    // Handle signature placeholder
    if (!isset($data['{SIGNATURE}'])) {
        $data['{SIGNATURE}'] = renderEmailSignatureHTML();
    }

    // Handle credentials table placeholder if {LOGIN} and {PASSWORD} are provided
    if (!isset($data['{CREDENTIALS_TABLE}']) && isset($data['{LOGIN}']) && isset($data['{PASSWORD}'])) {
        $data['{CREDENTIALS_TABLE}'] = renderEmailFieldTableHTML([
            $GLOBALS['BL']['login_username'] ?? 'Username' => $data['{LOGIN}'],
            $GLOBALS['BL']['login_userpass'] ?? 'Password' => $data['{PASSWORD}']
        ]);
    }

    // Replace placeholders in subject
    $subject = str_replace(array_keys($data), array_values($data), $template['subject']);
    $subject = cleanUpForEmailHeader(html_entity_decode(i18n_substitute_text($subject), ENT_QUOTES, 'UTF-8'));

    // Replace placeholders in HTML content
    $content_html = str_replace(array_keys($data), array_values($data), $template['content_html']);
    $content_html = i18n_substitute_text($content_html);

    // Replace placeholders in Plain Text content
    $content_text = str_replace(array_keys($data), array_values($data), $template['content_text']);
    $content_text = html_entity_decode(i18n_substitute_text($content_text), ENT_QUOTES, 'UTF-8');

    // Render inside standard HTML shell
    $full_html = renderSystemEmailHTML($subject, $content_html, $subject, $target_lang ?? $lang ?? null);

    return [
        'subject' => $subject,
        'html'    => $full_html,
        'text'    => $content_text
    ];
}
