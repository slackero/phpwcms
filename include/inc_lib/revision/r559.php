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

    return $status;
}
