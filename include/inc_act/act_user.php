<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = ['SESSION_START' => true];

require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lib/general.inc.php';
checkLogin();
validate_csrf_tokens();
require_once PHPWCMS_ROOT . '/include/inc_lib/backend.functions.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lang/code.lang.inc.php';
require_once PHPWCMS_ROOT . '/include/inc_lang/backend/en/lang.inc.php';
if (!empty($_SESSION['wcs_user_lang_custom']) && is_file(PHPWCMS_ROOT . '/include/inc_lang/backend/' . $_SESSION['wcs_user_lang'] . '/lang.inc.php')) {
    require_once PHPWCMS_ROOT . '/include/inc_lang/backend/' . $_SESSION['wcs_user_lang'] . '/lang.inc.php';
}

if (has_admin_permission('admuser')) {
    // Delete user account
    if (isset($_GET['del'])) {
        $ui = explode(':', clean_slweg($_GET['del']));
        $user_id = (int)$ui[0];
        $user_email = empty($ui[1]) ? '' : $ui[1];
        if ($user_id && $user_id !== (int)$_SESSION['wcs_user_id'] && is_valid_email($user_email)) {
            $result = _dbQuery('UPDATE ' . DB_PREPEND . 'phpwcms_user SET usr_aktiv=9 WHERE usr_id=' . $user_id . ' AND usr_email=' . _dbEscape($user_email), 'UPDATE');
            if (!empty($result['AFFECTED_ROWS'])) {
                $host = parse_url($phpwcms['site'], PHP_URL_HOST);
                $subject = str_replace('{SITE}', (string)$host, $BL['email_deactivated_subject'] ?? 'Your account on {SITE} was deactivated');
                $text = $BL['email_deactivated_greeting'] . "\n\n"
                    . str_replace('{SITE}', (string)$host, $BL['email_deactivated_body']) . "\n\n"
                    . $BL['email_deactivated_contact'] . "\n\n"
                    . $phpwcms['site'];
                // plain text part must not contain HTML entities
                $text = html_entity_decode($text, ENT_QUOTES, PHPWCMS_CHARSET);
                $email_html = renderSystemEmailHTML(
                    $subject,
                    '<p>' . html($BL['email_deactivated_greeting']) . '</p>'
                    . '<p>' . html(str_replace('{SITE}', (string)$host, $BL['email_deactivated_body'])) . '</p>'
                    . '<p>' . html($BL['email_deactivated_contact']) . '</p>',
                    html(str_replace('{SITE}', (string)$host, $BL['email_deactivated_body']))
                );

                sendEmail([
                    'recipient' => $user_email,
                    'subject'   => $subject,
                    'isHTML'    => true,
                    'html'      => $email_html,
                    'text'      => $text,
                    'from'      => $phpwcms['admin_email'],
                    'sender'    => $phpwcms['admin_email']
                ]);
            }
        }
    }

    if (isset($_GET['aktiv'])) {
        $ui = explode(':', clean_slweg($_GET['aktiv']));
        $user_id = (int)$ui[0];
        $user_aktiv = empty($ui[1]) ? 0 : 1;
        if ($user_id && $user_id !== (int)$_SESSION['wcs_user_id']) {
            _dbQuery($sql = 'UPDATE ' . DB_PREPEND . 'phpwcms_user SET usr_aktiv=' . $user_aktiv . ' WHERE usr_aktiv != 9 AND usr_id=' . $user_id, 'UPDATE');
        }
    }
}

headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin');
