<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 567 Update Check
function phpwcms_revision_r567()
{
    // Permission group for delegated main editors. Members may write every
    // article, not only the articles they own.
    $existing = _dbQuery(
        'SELECT `group_id` FROM `' . DB_PREPEND . 'usergroup` WHERE `group_syskey` = ' . _dbEscape('artall') . ' LIMIT 1'
    );

    if (!empty($existing[0]['group_id'])) {
        return true;
    }

    // Seed the group with the current administrators, matching the fresh
    // installation data in setup/default_sql/phpwcms_inserts.sql.
    $adminusers = _dbQuery('SELECT `usr_id` FROM `' . DB_PREPEND . 'user` WHERE `usr_admin` = 1 AND `usr_aktiv` != 9');
    $adminids = [];
    if (!empty($adminusers)) {
        foreach ($adminusers as $admin) {
            $adminids[] = (int)$admin['usr_id'];
        }
    }

    return (bool)_dbInsert('usergroup', [
        'group_name'      => 'SYSGROUP',
        'group_member'    => implode(',', $adminids),
        'group_value'     => '',
        'group_timestamp' => date('Y-m-d H:i:s'),
        'group_trash'     => 0,
        'group_active'    => 1,
        'group_syskey'    => 'artall',
    ]);
}
