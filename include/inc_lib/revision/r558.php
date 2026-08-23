<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// Revision 558 Update Check
function phpwcms_revision_r558() {

    $status = true;

    // 1. Ensure phpwcms_custom_cpt table exists
    if (!_dbTableExists('phpwcms_custom_cpt')) {
        $charset_collate = _dbGetCreateCharsetCollation();
        $sql = 'CREATE TABLE IF NOT EXISTS `' . DB_PREPEND . 'phpwcms_custom_cpt` (
            `cpt_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `cpt_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `cpt_changed` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `cpt_active` tinyint(1) NOT NULL DEFAULT 1,
            `cpt_key` varchar(50) NOT NULL DEFAULT "",
            `cpt_title` varchar(255) NOT NULL DEFAULT "",
            `cpt_desc` text NOT NULL,
            `cpt_mode` varchar(20) NOT NULL DEFAULT "repeater",
            `cpt_icon` varchar(50) NOT NULL DEFAULT "fa-cube",
            `cpt_template` varchar(255) NOT NULL DEFAULT "",
            `cpt_schema` longtext NOT NULL,
            PRIMARY KEY (`cpt_id`),
            UNIQUE KEY `cpt_key` (`cpt_key`)
        ) ENGINE=InnoDB ' . $charset_collate;
        _dbQuery($sql, 'CREATE');
    }

    // 2. Add admcustomcpt permission to phpwcms_usergroup if not exists
    $count = _dbCount('SELECT COUNT(*) FROM ' . DB_PREPEND . 'phpwcms_usergroup WHERE group_syskey="admcustomcpt" AND group_trash=0');
    if ($count === 0) {
        $adminusers = _dbQuery('SELECT `usr_id` FROM `' . DB_PREPEND . 'phpwcms_user` WHERE `usr_admin` = 1');
        $adminids = [];
        if (!empty($adminusers)) {
            foreach ($adminusers as $admins) {
                $adminids[] = $admins['usr_id'];
            }
        }
        $admin_members = implode(',', $adminids) ?: '1';

        $result = _dbInsert('phpwcms_usergroup', [
            'group_name'   => 'SYSGROUP',
            'group_member' => $admin_members,
            'group_value'  => '',
            'group_trash'  => 0,
            'group_active' => 1,
            'group_modkey' => '',
            'group_syskey' => 'admcustomcpt'
        ]);

        if (empty($result['INSERT_ID'])) {
            $status = false;
        }
    }

    // 3. Ensure 2FA columns exist in phpwcms_user
    if (!_dbColumnExists('phpwcms_user', 'usr_2fa_enabled')) {
        _dbQuery("ALTER TABLE `" . DB_PREPEND . "phpwcms_user` ADD `usr_2fa_enabled` tinyint(1) NOT NULL DEFAULT 0 AFTER `usr_fe`", 'ALTER');
    }
    if (!_dbColumnExists('phpwcms_user', 'usr_2fa_secret')) {
        _dbQuery("ALTER TABLE `" . DB_PREPEND . "phpwcms_user` ADD `usr_2fa_secret` varchar(64) NOT NULL DEFAULT '' AFTER `usr_2fa_enabled`", 'ALTER');
    }

    return $status;
}
