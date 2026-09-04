<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

/**
 * Revision 559:
 * - Create table phpwcms_update_log for the self-update history
 * - Create table phpwcms_mailtemplates for backend email templates
 * - Add admmailtpl permission to phpwcms_usergroup
 *
 * @return bool
 */
function phpwcms_revision_r559() {

    $status = true;

    if (!_dbTableExists('phpwcms_update_log')) {
        $create = 'CREATE TABLE IF NOT EXISTS `' . DB_PREPEND . "phpwcms_update_log` (
            `update_id` INT NOT NULL AUTO_INCREMENT,
            `update_from` VARCHAR(32) NOT NULL DEFAULT '',
            `update_to` VARCHAR(32) NOT NULL DEFAULT '',
            `update_tag` VARCHAR(64) NOT NULL DEFAULT '',
            `update_status` ENUM('running','success','failed','rolled_back') NOT NULL DEFAULT 'running',
            `update_error` TEXT NULL,
            `update_backup` VARCHAR(255) NOT NULL DEFAULT '',
            `update_files` INT NOT NULL DEFAULT 0,
            `update_tstamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `update_user` INT NOT NULL DEFAULT 0,
            PRIMARY KEY (`update_id`)
        ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        if (!_dbQuery($create, 'CREATE')) {
            $status = false;
        }
    } else {
        foreach (['update_from' => "VARCHAR(32) NOT NULL DEFAULT ''", 'update_to' => "VARCHAR(32) NOT NULL DEFAULT ''", 'update_tag' => "VARCHAR(64) NOT NULL DEFAULT ''", 'update_status' => "ENUM('running','success','failed','rolled_back') NOT NULL DEFAULT 'running'", 'update_error' => 'TEXT NULL', 'update_backup' => "VARCHAR(255) NOT NULL DEFAULT ''", 'update_files' => 'INT NOT NULL DEFAULT 0', 'update_tstamp' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP', 'update_user' => 'INT NOT NULL DEFAULT 0'] as $column => $definition) {
            if (!_dbColumnExists('phpwcms_update_log', $column)) {
                if (!_dbQuery('ALTER TABLE `' . DB_PREPEND . 'phpwcms_update_log` ADD `' . $column . '` ' . $definition, 'ALTER')) {
                    $status = false;
                }
            }
        }
    }

    // Ensure phpwcms_mailtemplates table exists
    if (!_dbTableExists('phpwcms_mailtemplates')) {
        $charset_collate = _dbGetCreateCharsetCollation();
        $create_mail = 'CREATE TABLE IF NOT EXISTS `' . DB_PREPEND . 'phpwcms_mailtemplates` (
            `tpl_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `tpl_key` varchar(64) NOT NULL DEFAULT "",
            `tpl_lang` varchar(10) NOT NULL DEFAULT "en",
            `tpl_subject` varchar(255) NOT NULL DEFAULT "",
            `tpl_content_html` mediumtext NOT NULL,
            `tpl_content_text` mediumtext NOT NULL,
            `tpl_active` tinyint(1) NOT NULL DEFAULT 1,
            `tpl_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `tpl_changed` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`tpl_id`),
            UNIQUE KEY `key_lang` (`tpl_key`, `tpl_lang`),
            KEY `tpl_key` (`tpl_key`),
            KEY `tpl_lang` (`tpl_lang`)
        ) ENGINE=InnoDB ' . $charset_collate;
        if (!_dbQuery($create_mail, 'CREATE')) {
            $status = false;
        }
    }

    // Add admmailtpl permission to phpwcms_usergroup if not exists
    $count = _dbCount('SELECT COUNT(*) FROM `' . DB_PREPEND . 'phpwcms_usergroup` WHERE `group_syskey`="admmailtpl" AND `group_trash`=0');
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
            'group_syskey' => 'admmailtpl'
        ]);

        if (empty($result['INSERT_ID'])) {
            $status = false;
        }
    }

    return $status;
}
