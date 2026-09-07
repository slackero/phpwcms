<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// Revision 555 Update Check
function phpwcms_revision_r555()
{
    $status = true;

    // Self-healing check for group_syskey and group_modkey columns
    if (!_dbColumnExists('usergroup', 'group_syskey')) {
        if (!_dbQuery('ALTER TABLE `' . DB_PREPEND . "usergroup` ADD `group_syskey` VARCHAR(255) NOT NULL DEFAULT '' AFTER `group_active`", 'ALTER')) {
            $status = false;
        }
    }
    if (!_dbColumnExists('usergroup', 'group_modkey')) {
        if (!_dbQuery('ALTER TABLE `' . DB_PREPEND . "usergroup` ADD `group_modkey` VARCHAR(20) NOT NULL DEFAULT '' AFTER `group_active`", 'ALTER')) {
            $status = false;
        }
    }

    $result = _dbCount('SELECT COUNT(*) FROM ' . DB_PREPEND . "usergroup WHERE group_syskey='filedelete' AND group_trash=0");

    if ($result > 0) {
        return $status;
    }

    $result = _dbInsert('usergroup', [
        'group_name' => 'File management - delete file',
        'group_member' => '1',
        'group_value' => '',
        'group_trash' => 0,
        'group_active' => 1,
        'group_modkey' => '',
        'group_syskey' => 'filedelete'
    ]);

    if (empty($result['INSERT_ID'])) {
        $status = false;
    }

    return $status;
}
