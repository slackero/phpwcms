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
 * Registry of standard system email templates with default values and available placeholders
 *
 * @return array
 */
function get_system_email_definitions() {
    global $BL, $phpwcms;

    $definitions = [
        'password_reset' => [
            'key'         => 'password_reset',
            'title'       => $BL['be_admin_mail_title_password_reset'] ?? 'Password Reset Request',
            'desc'        => $BL['be_admin_mail_desc_password_reset'] ?? 'Sent to users requesting a password reset link on the login page.',
            'placeholders' => [
                '{NAME}'       => $BL['be_admin_mail_ph_name'] ?? 'Recipient display name or username',
                '{LOGIN}'      => $BL['be_admin_mail_ph_login'] ?? 'Username / login',
                '{SITE}'       => $BL['be_admin_mail_ph_site'] ?? 'Website host name (e.g. example.com)',
                '{SITE_URL}'   => $BL['be_admin_mail_ph_site_url'] ?? 'Full website URL',
                '{RESET_LINK}' => $BL['be_admin_mail_ph_reset_link'] ?? 'Password reset URL with secure token',
                '{EXPIRES}'    => $BL['be_admin_mail_ph_expires'] ?? 'Expiration time (e.g. 1 hour)'
            ],
            'defaults'    => [
                'en' => [
                    'subject'      => 'Password reset request for {SITE}',
                    'content_html' => '<p>Hello {NAME},</p>' . LF
                        . '<p>A password reset request has been initiated for your account <strong>{LOGIN}</strong> on {SITE}.</p>' . LF
                        . '{BUTTON}' . LF
                        . '<p style="color:#6c757d;font-size:13px;">If you did not request a password reset, please ignore this email. The link will expire in 1 hour.</p>' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'Hello {NAME},' . LF . LF
                        . 'A password reset request has been initiated for your account {LOGIN} on {SITE}.' . LF . LF
                        . 'Reset your password here:' . LF
                        . '{RESET_LINK}' . LF . LF
                        . 'If you did not request a password reset, please ignore this email.' . LF . LF
                        . 'phpwcms admin'
                ],
                'de' => [
                    'subject'      => 'Passwort zur&uuml;cksetzen f&uuml;r {SITE}',
                    'content_html' => '<p>Hallo {NAME},</p>' . LF
                        . '<p>F&uuml;r Ihr Benutzerkonto <strong>{LOGIN}</strong> auf {SITE} wurde das Zur&uuml;cksetzen des Passworts angefordert.</p>' . LF
                        . '{BUTTON}' . LF
                        . '<p style="color:#6c757d;font-size:13px;">Falls Sie diese Anfrage nicht gestellt haben, k&ouml;nnen Sie diese E-Mail ignorieren. Der Link ist 1 Stunde g&uuml;ltig.</p>' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'Hallo {NAME},' . LF . LF
                        . 'F&uuml;r Ihr Benutzerkonto {LOGIN} auf {SITE} wurde das Zur&uuml;cksetzen des Passworts angefordert.' . LF . LF
                        . 'Klicken Sie auf den folgenden Link, um ein neues Passwort festzulegen:' . LF
                        . '{RESET_LINK}' . LF . LF
                        . 'Falls Sie diese Anfrage nicht gestellt haben, k&ouml;nnen Sie diese E-Mail ignorieren.' . LF . LF
                        . 'phpwcms Admin'
                ]
            ]
        ],

        'new_user' => [
            'key'         => 'new_user',
            'title'       => $BL['be_admin_mail_title_new_user'] ?? 'New User Account Welcome',
            'desc'        => $BL['be_admin_mail_desc_new_user'] ?? 'Sent when a new backend user account is created by an administrator.',
            'placeholders' => [
                '{NAME}'       => $BL['be_admin_mail_ph_name'] ?? 'Recipient display name or username',
                '{LOGIN}'      => $BL['be_admin_mail_ph_login'] ?? 'Username / login',
                '{PASSWORD}'   => $BL['be_admin_mail_ph_password'] ?? 'Initial assigned password',
                '{SITE}'       => $BL['be_admin_mail_ph_site'] ?? 'Website host name',
                '{SITE_URL}'   => $BL['be_admin_mail_ph_site_url'] ?? 'Full website URL',
                '{LOGIN_PAGE}' => $BL['be_admin_mail_ph_login_page'] ?? 'Direct URL to login page'
            ],
            'defaults'    => [
                'en' => [
                    'subject'      => 'Welcome to {SITE} backend',
                    'content_html' => '<p>Welcome to {SITE}!</p>' . LF
                        . '<p>An account has been created for you with the following credentials:</p>' . LF
                        . '{CREDENTIALS_TABLE}' . LF
                        . '<p>You can sign in using the button below:</p>' . LF
                        . '{BUTTON}' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'WELCOME TO THE PHPWCMS BACKEND' . LF . LF
                        . '    username: {LOGIN}' . LF
                        . '    password: {PASSWORD}' . LF . LF
                        . 'You can login here: {LOGIN_PAGE}' . LF . LF
                        . 'phpwcms admin'
                ],
                'de' => [
                    'subject'      => 'Willkommen im Backend von {SITE}',
                    'content_html' => '<p>Willkommen bei {SITE}!</p>' . LF
                        . '<p>F&uuml;r Sie wurde ein Benutzerkonto mit folgenden Zugangsdaten angelegt:</p>' . LF
                        . '{CREDENTIALS_TABLE}' . LF
                        . '<p>&Uuml;ber den folgenden Button k&ouml;nnen Sie sich anmelden:</p>' . LF
                        . '{BUTTON}' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'WILLKOMMEN IM PHPWCMS BACKEND' . LF . LF
                        . '    Benutzername: {LOGIN}' . LF
                        . '    Passwort:     {PASSWORD}' . LF . LF
                        . 'Hier anmelden: {LOGIN_PAGE}' . LF . LF
                        . 'phpwcms Admin'
                ]
            ]
        ],

        'edit_user' => [
            'key'         => 'edit_user',
            'title'       => $BL['be_admin_mail_title_edit_user'] ?? 'User Account Updated',
            'desc'        => $BL['be_admin_mail_desc_edit_user'] ?? 'Sent when an administrator updates a user account and checks the send email option.',
            'placeholders' => [
                '{NAME}'       => $BL['be_admin_mail_ph_name'] ?? 'Recipient display name or username',
                '{LOGIN}'      => $BL['be_admin_mail_ph_login'] ?? 'Username / login',
                '{PASSWORD}'   => $BL['be_admin_mail_ph_password'] ?? 'New password or unchanged notice',
                '{SITE}'       => $BL['be_admin_mail_ph_site'] ?? 'Website host name',
                '{SITE_URL}'   => $BL['be_admin_mail_ph_site_url'] ?? 'Full website URL',
                '{LOGIN_PAGE}' => $BL['be_admin_mail_ph_login_page'] ?? 'Direct URL to login page'
            ],
            'defaults'    => [
                'en' => [
                    'subject'      => '{SITE} - account data changed',
                    'content_html' => '<p>Hello {NAME},</p>' . LF
                        . '<p>Your user account information on {SITE} has been updated:</p>' . LF
                        . '{CREDENTIALS_TABLE}' . LF
                        . '<p>You can sign in with your updated credentials:</p>' . LF
                        . '{BUTTON}' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'PHPWCMS USER ACCOUNT INFORMATION CHANGED' . LF . LF
                        . '    username: {LOGIN}' . LF
                        . '    password: {PASSWORD}' . LF . LF
                        . 'You can login here: {LOGIN_PAGE}' . LF . LF
                        . 'phpwcms admin'
                ],
                'de' => [
                    'subject'      => '{SITE} - Benutzerdaten ge&auml;ndert',
                    'content_html' => '<p>Hallo {NAME},</p>' . LF
                        . '<p>Ihre Benutzerdaten auf {SITE} wurden aktualisiert:</p>' . LF
                        . '{CREDENTIALS_TABLE}' . LF
                        . '<p>&Uuml;ber den folgenden Button k&ouml;nnen Sie sich anmelden:</p>' . LF
                        . '{BUTTON}' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'PHPWCMS BENUTZERDATEN GE&Auml;NDERT' . LF . LF
                        . '    Benutzername: {LOGIN}' . LF
                        . '    Passwort:     {PASSWORD}' . LF . LF
                        . 'Hier anmelden: {LOGIN_PAGE}' . LF . LF
                        . 'phpwcms Admin'
                ]
            ]
        ],

        'account_deactivated' => [
            'key'         => 'account_deactivated',
            'title'       => $BL['be_admin_mail_title_account_deactivated'] ?? 'Account Deactivation Notification',
            'desc'        => $BL['be_admin_mail_desc_account_deactivated'] ?? 'Sent when an administrator deactivates a user account.',
            'placeholders' => [
                '{NAME}'       => $BL['be_admin_mail_ph_name'] ?? 'Recipient display name or username',
                '{LOGIN}'      => $BL['be_admin_mail_ph_login'] ?? 'Username / login',
                '{SITE}'       => $BL['be_admin_mail_ph_site'] ?? 'Website host name',
                '{SITE_URL}'   => $BL['be_admin_mail_ph_site_url'] ?? 'Full website URL',
                '{ADMIN_EMAIL}'=> $BL['be_admin_mail_ph_admin_email'] ?? 'Administrator contact email'
            ],
            'defaults'    => [
                'en' => [
                    'subject'      => 'Your account on {SITE} was deactivated',
                    'content_html' => '<p>Hello {NAME},</p>' . LF
                        . '<p>Your account on {SITE} has been deactivated by an administrator.</p>' . LF
                        . '<p>If you have any questions or believe this is an error, please contact the site administrator.</p>' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'Hello {NAME},' . LF . LF
                        . 'Your account on {SITE} has been deactivated.' . LF . LF
                        . 'Please contact the administrator for more information.' . LF . LF
                        . '{SITE_URL}'
                ],
                'de' => [
                    'subject'      => 'Ihr Benutzerkonto auf {SITE} wurde deaktiviert',
                    'content_html' => '<p>Hallo {NAME},</p>' . LF
                        . '<p>Ihr Benutzerkonto auf {SITE} wurde durch einen Administrator deaktiviert.</p>' . LF
                        . '<p>Bei Fragen wenden Sie sich bitte an den Administrator.</p>' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'Hallo {NAME},' . LF . LF
                        . 'Ihr Benutzerkonto auf {SITE} wurde deaktiviert.' . LF . LF
                        . 'Bitte wenden Sie sich an den Administrator f&uuml;r weitere Informationen.' . LF . LF
                        . '{SITE_URL}'
                ]
            ]
        ],

        'password_changed' => [
            'key'         => 'password_changed',
            'title'       => $BL['be_admin_mail_title_password_changed'] ?? 'Password Changed Confirmation',
            'desc'        => $BL['be_admin_mail_desc_password_changed'] ?? 'Sent to the user when their account password has been successfully changed or reset.',
            'placeholders' => [
                '{NAME}'       => $BL['be_admin_mail_ph_name'] ?? 'Recipient display name or username',
                '{LOGIN}'      => $BL['be_admin_mail_ph_login'] ?? 'Username / login',
                '{SITE}'       => $BL['be_admin_mail_ph_site'] ?? 'Website host name',
                '{SITE_URL}'   => $BL['be_admin_mail_ph_site_url'] ?? 'Full website URL',
                '{LOGIN_PAGE}' => $BL['be_admin_mail_ph_login_page'] ?? 'Direct URL to login page',
                '{ADMIN_EMAIL}'=> $BL['be_admin_mail_ph_admin_email'] ?? 'Administrator contact email'
            ],
            'defaults'    => [
                'en' => [
                    'subject'      => 'Password changed for your account on {SITE}',
                    'content_html' => '<p>Hello {NAME},</p>' . LF
                        . '<p>The password for your account <strong>{LOGIN}</strong> on {SITE} was successfully changed.</p>' . LF
                        . '<p>If you made this change, no further action is required.</p>' . LF
                        . '<p style="color:#b02a37;font-size:13px;">If you did not make this change, please contact the site administrator immediately.</p>' . LF
                        . '{BUTTON}' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'Hello {NAME},' . LF . LF
                        . 'The password for your account {LOGIN} on {SITE} was successfully changed.' . LF . LF
                        . 'If you made this change, no further action is required.' . LF . LF
                        . 'If you did not make this change, please contact the site administrator immediately.' . LF . LF
                        . 'Login: {LOGIN_PAGE}' . LF . LF
                        . 'phpwcms admin'
                ],
                'de' => [
                    'subject'      => 'Passwort f&uuml;r Ihr Benutzerkonto auf {SITE} ge&auml;ndert',
                    'content_html' => '<p>Hallo {NAME},</p>' . LF
                        . '<p>Das Passwort f&uuml;r Ihr Benutzerkonto <strong>{LOGIN}</strong> auf {SITE} wurde erfolgreich ge&auml;ndert.</p>' . LF
                        . '<p>Wenn Sie diese &Auml;nderung selbst vorgenommen haben, ist keine weitere Aktion erforderlich.</p>' . LF
                        . '<p style="color:#b02a37;font-size:13px;">Falls Sie diese &Auml;nderung nicht veranlasst haben, wenden Sie sich bitte umgehend an den Administrator.</p>' . LF
                        . '{BUTTON}' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'Hallo {NAME},' . LF . LF
                        . 'Das Passwort f&uuml;r Ihr Benutzerkonto {LOGIN} auf {SITE} wurde erfolgreich ge&auml;ndert.' . LF . LF
                        . 'Wenn Sie diese &Auml;nderung selbst vorgenommen haben, ist keine weitere Aktion erforderlich.' . LF . LF
                        . 'Falls Sie diese &Auml;nderung nicht veranlasst haben, wenden Sie sich bitte umgehend an den Administrator.' . LF . LF
                        . 'Anmelden: {LOGIN_PAGE}' . LF . LF
                        . 'phpwcms Admin'
                ]
            ]
        ],

        'user_email_changed' => [
            'key'         => 'user_email_changed',
            'title'       => $BL['be_admin_mail_title_user_email_changed'] ?? 'Email Address Changed Notification',
            'desc'        => $BL['be_admin_mail_desc_user_email_changed'] ?? 'Sent to the previous email address when a user or administrator changes their account email.',
            'placeholders' => [
                '{NAME}'       => $BL['be_admin_mail_ph_name'] ?? 'Recipient display name or username',
                '{LOGIN}'      => $BL['be_admin_mail_ph_login'] ?? 'Username / login',
                '{NEW_EMAIL}'  => $BL['be_admin_mail_ph_new_email'] ?? 'New email address',
                '{OLD_EMAIL}'  => $BL['be_admin_mail_ph_old_email'] ?? 'Previous email address',
                '{SITE}'       => $BL['be_admin_mail_ph_site'] ?? 'Website host name',
                '{SITE_URL}'   => $BL['be_admin_mail_ph_site_url'] ?? 'Full website URL',
                '{LOGIN_PAGE}' => $BL['be_admin_mail_ph_login_page'] ?? 'Direct URL to login page',
                '{ADMIN_EMAIL}'=> $BL['be_admin_mail_ph_admin_email'] ?? 'Administrator contact email'
            ],
            'defaults'    => [
                'en' => [
                    'subject'      => 'Email address changed for your account on {SITE}',
                    'content_html' => '<p>Hello {NAME},</p>' . LF
                        . '<p>The email address associated with your account <strong>{LOGIN}</strong> on {SITE} has been changed to <strong>{NEW_EMAIL}</strong>.</p>' . LF
                        . '<p>If you requested this change, no further action is required.</p>' . LF
                        . '<p style="color:#b02a37;font-size:13px;">If you did not authorize this change, please contact the site administrator immediately.</p>' . LF
                        . '{BUTTON}' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'Hello {NAME},' . LF . LF
                        . 'The email address associated with your account {LOGIN} on {SITE} has been changed to {NEW_EMAIL}.' . LF . LF
                        . 'If you requested this change, no further action is required.' . LF . LF
                        . 'If you did not authorize this change, please contact the site administrator immediately.' . LF . LF
                        . 'Login: {LOGIN_PAGE}' . LF . LF
                        . 'phpwcms admin'
                ],
                'de' => [
                    'subject'      => 'E-Mail-Adresse f&uuml;r Ihr Benutzerkonto auf {SITE} ge&auml;ndert',
                    'content_html' => '<p>Hallo {NAME},</p>' . LF
                        . '<p>Die E-Mail-Adresse f&uuml;r Ihr Benutzerkonto <strong>{LOGIN}</strong> auf {SITE} wurde in <strong>{NEW_EMAIL}</strong> ge&auml;ndert.</p>' . LF
                        . '<p>Wenn Sie diese &Auml;nderung veranlasst haben, ist keine weitere Aktion erforderlich.</p>' . LF
                        . '<p style="color:#b02a37;font-size:13px;">Falls Sie diese &Auml;nderung nicht autorisiert haben, wenden Sie sich bitte umgehend an den Administrator.</p>' . LF
                        . '{BUTTON}' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'Hallo {NAME},' . LF . LF
                        . 'Die E-Mail-Adresse f&uuml;r Ihr Benutzerkonto {LOGIN} auf {SITE} wurde in {NEW_EMAIL} ge&auml;ndert.' . LF . LF
                        . 'Wenn Sie diese &Auml;nderung veranlasst haben, ist keine weitere Aktion erforderlich.' . LF . LF
                        . 'Falls Sie diese &Auml;nderung nicht autorisiert haben, wenden Sie sich bitte umgehend an den Administrator.' . LF . LF
                        . 'Anmelden: {LOGIN_PAGE}' . LF . LF
                        . 'phpwcms Admin'
                ]
            ]
        ],

        'account_locked' => [
            'key'         => 'account_locked',
            'title'       => $BL['be_admin_mail_title_account_locked'] ?? 'Account Security Alert (Suspicious Activity)',
            'desc'        => $BL['be_admin_mail_desc_account_locked'] ?? 'Sent when multiple repeated failed login attempts are detected for an account.',
            'placeholders' => [
                '{NAME}'       => $BL['be_admin_mail_ph_name'] ?? 'Recipient display name or username',
                '{LOGIN}'      => $BL['be_admin_mail_ph_login'] ?? 'Username / login',
                '{IP}'         => $BL['be_admin_mail_ph_ip'] ?? 'Client IP address of the attempt',
                '{DATE}'       => $BL['be_admin_mail_ph_date'] ?? 'Date and time of the attempt',
                '{SITE}'       => $BL['be_admin_mail_ph_site'] ?? 'Website host name',
                '{SITE_URL}'   => $BL['be_admin_mail_ph_site_url'] ?? 'Full website URL',
                '{RESET_LINK}' => $BL['be_admin_mail_ph_reset_link'] ?? 'Password reset URL with secure token',
                '{ADMIN_EMAIL}'=> $BL['be_admin_mail_ph_admin_email'] ?? 'Administrator contact email'
            ],
            'defaults'    => [
                'en' => [
                    'subject'      => 'Security Alert: Failed login attempts on {SITE}',
                    'content_html' => '<p>Hello {NAME},</p>' . LF
                        . '<p>Multiple consecutive failed login attempts were detected for your account <strong>{LOGIN}</strong> on {SITE}.</p>' . LF
                        . '<p><strong>IP Address:</strong> {IP}<br><strong>Time:</strong> {DATE}</p>' . LF
                        . '<p>If this was not you, someone may be trying to access your account. We strongly recommend resetting your password immediately.</p>' . LF
                        . '{BUTTON}' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'Hello {NAME},' . LF . LF
                        . 'Multiple consecutive failed login attempts were detected for your account {LOGIN} on {SITE}.' . LF . LF
                        . 'IP Address: {IP}' . LF
                        . 'Time:       {DATE}' . LF . LF
                        . 'If this was not you, someone may be trying to access your account. We recommend resetting your password immediately:' . LF
                        . '{LOGIN_PAGE}' . LF . LF
                        . 'phpwcms admin'
                ],
                'de' => [
                    'subject'      => 'Sicherheitshinweis: Fehlgeschlagene Anmeldeversuche auf {SITE}',
                    'content_html' => '<p>Hallo {NAME},</p>' . LF
                        . '<p>F&uuml;r Ihr Benutzerkonto <strong>{LOGIN}</strong> auf {SITE} wurden mehrere aufeinanderfolgende fehlgeschlagene Anmeldeversuche festgestellt.</p>' . LF
                        . '<p><strong>IP-Adresse:</strong> {IP}<br><strong>Zeitpunkt:</strong> {DATE}</p>' . LF
                        . '<p>Falls diese Versuche nicht von Ihnen stammen, empfehlen wir dringend, Ihr Passwort zur Sicherheit umgehend zur&uuml;ckzusetzen.</p>' . LF
                        . '{BUTTON}' . LF
                        . '{SIGNATURE}',
                    'content_text' => 'Hallo {NAME},' . LF . LF
                        . 'F&uuml;r Ihr Benutzerkonto {LOGIN} auf {SITE} wurden mehrere fehlgeschlagene Anmeldeversuche festgestellt.' . LF . LF
                        . 'IP-Adresse: {IP}' . LF
                        . 'Zeitpunkt:  {DATE}' . LF . LF
                        . 'Falls diese Versuche nicht von Ihnen stammen, &auml;ndern Sie bitte umgehend Ihr Passwort:' . LF
                        . '{LOGIN_PAGE}' . LF . LF
                        . 'phpwcms Admin'
                ]
            ]
        ]
    ];

    $charset = defined('PHPWCMS_CHARSET') ? PHPWCMS_CHARSET : 'UTF-8';
    $decode = static function ($val) use (&$decode, $charset) {
        if (is_array($val)) {
            return array_map($decode, $val);
        }
        if (is_string($val) && strpos($val, '&') !== false) {
            return html_entity_decode($val, ENT_QUOTES, $charset);
        }
        return $val;
    };

    return $decode($definitions);
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
 * Get a specific email template by key and language, falling back to default language or code defaults
 *
 * @param string $key Template identifier (e.g. 'password_reset')
 * @param string|null $lang Language code (default: current backend lang or 'en')
 * @return array
 */
function get_system_email_template(string $key, ?string $lang = null): array {
    global $phpwcms;

    $defs = get_system_email_definitions();
    if (!isset($defs[$key])) {
        return [
            'key'          => $key,
            'lang'         => $lang ?? 'en',
            'subject'      => '',
            'content_html' => '',
            'content_text' => '',
            'active'       => 1,
            'is_custom'    => false
        ];
    }

    $def = $defs[$key];
    $target_lang = !empty($lang) ? strtolower(trim($lang)) : (strtolower($_SESSION['wcs_user_lang'] ?? $phpwcms['default_lang'] ?? 'en'));

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

    // Fallback to definition defaults (target language, then 'en')
    $lang_fallback = isset($def['defaults'][$target_lang]) ? $target_lang : 'en';
    $defaults = $def['defaults'][$lang_fallback] ?? ($def['defaults']['en'] ?? []);

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
        $defs = get_system_email_definitions();
        $def = $defs[$key] ?? [];
        $target_lang = !empty($lang) ? strtolower(trim($lang)) : (strtolower($_SESSION['wcs_user_lang'] ?? $phpwcms['default_lang'] ?? 'en'));
        $lang_fallback = isset($def['defaults'][$target_lang]) ? $target_lang : 'en';
        $defaults = $def['defaults'][$lang_fallback] ?? ($def['defaults']['en'] ?? []);
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
    $full_html = renderSystemEmailHTML($subject, $content_html, $subject);

    return [
        'subject' => $subject,
        'html'    => $full_html,
        'text'    => $content_text
    ];
}
