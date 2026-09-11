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
            $result = _dbQuery('UPDATE ' . DB_PREPEND . 'user SET usr_aktiv=9 WHERE usr_id=' . $user_id . ' AND usr_email=' . _dbEscape($user_email), 'UPDATE');
            if (!empty($result['AFFECTED_ROWS'])) {
                remove_user_from_groups($user_id);
                $rendered_mail = render_system_email('account_deactivated', [
                    '{NAME}'       => $user_email,
                    '{LOGIN}'      => $user_email,
                    '{SITE}'       => PHPWCMS_HOST,
                    '{SITE_URL}'   => PHPWCMS_URL,
                    '{ADMIN_EMAIL}'=> $phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? ''
                ]);

                sendEmail([
                    'recipient' => $user_email,
                    'subject'   => $rendered_mail['subject'],
                    'isHTML'    => true,
                    'html'      => $rendered_mail['html'],
                    'text'      => $rendered_mail['text'],
                    'from'      => $phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? '',
                    'fromName'  => get_brand_name(),
                    'sender'    => $phpwcms['admin_email'] ?? $phpwcms['SMTP_FROM_EMAIL'] ?? ''
                ]);
            }
        }
    }

    if (isset($_GET['aktiv'])) {
        $ui = explode(':', clean_slweg($_GET['aktiv']));
        $user_id = (int)$ui[0];
        $user_aktiv = empty($ui[1]) ? 0 : 1;
        if ($user_id && $user_id !== (int)$_SESSION['wcs_user_id']) {
            _dbQuery($sql = 'UPDATE ' . DB_PREPEND . 'user SET usr_aktiv=' . $user_aktiv . ' WHERE usr_aktiv != 9 AND usr_id=' . $user_id, 'UPDATE');
        }
    }
}

headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin');
