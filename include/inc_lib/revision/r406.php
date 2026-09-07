<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 406 Update Check
function phpwcms_revision_r406()
{
    $status = true;

    // upgrade sysvalue fields +KH:24.11.2011 if no field exists
    $result = _dbQuery("SHOW COLUMNS FROM `" . DB_PREPEND . "sysvalue` LIKE 'sysvalue_vartype'");

    // sysvalue_vartype
    if (empty($result)) {
        $result = _dbQuery("ALTER TABLE `" . DB_PREPEND . "sysvalue` ADD `sysvalue_vartype` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
        if (!$result) {
            $status = false;
        }
    } elseif (isset($result[0]['Type']) && $result[0]['Type'] === 'varchar(100)') {
        $result = _dbQuery("ALTER TABLE `" . DB_PREPEND . "sysvalue` CHANGE `sysvalue_vartype` `sysvalue_vartype` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
        if (!$result) {
            $status = false;
        }
    }

    // sysvalue_value
    if (!_dbColumnExists('sysvalue', 'sysvalue_value')) {
        $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . 'sysvalue` ADD `sysvalue_value` MEDIUMTEXT NOT NULL', 'ALTER');
        if (!$result) {
            $status = false;
        }
    } else {
        $col_val = _dbQuery("SHOW COLUMNS FROM `" . DB_PREPEND . "sysvalue` LIKE 'sysvalue_value'");
        if (isset($col_val[0]['Type']) && ($col_val[0]['Type'] === 'text' || $col_val[0]['Type'] === 'mediumblob')) {
            $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . 'sysvalue` CHANGE `sysvalue_value` `sysvalue_value` MEDIUMTEXT NOT NULL', 'ALTER');
            if (!$result) {
                $status = false;
            }
        }
    }

    // sysvalue_lastchange
    if (!_dbColumnExists('sysvalue', 'sysvalue_lastchange')) {
        $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . 'sysvalue` ADD `sysvalue_lastchange` INT(11) NOT NULL DEFAULT 0', 'ALTER');
        if (!$result) {
            $status = false;
        }
    }
    // sysvalue_group
    if (!_dbColumnExists('sysvalue', 'sysvalue_group')) {
        $result = _dbQuery("ALTER TABLE `" . DB_PREPEND . "sysvalue` ADD `sysvalue_group` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
        if (!$result) {
            $status = false;
        }
        $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . 'sysvalue` ADD INDEX (`sysvalue_group`)', 'ALTER');
        if (!$result) {
            $status = false;
        }
    }
    // sysvalue_status
    if (!_dbColumnExists('sysvalue', 'sysvalue_status')) {
        $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . 'sysvalue` ADD `sysvalue_status` INT(1) NOT NULL DEFAULT 0', 'ALTER');
        if (!$result) {
            $status = false;
        }
        $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . 'sysvalue` ADD INDEX (`sysvalue_status`)', 'ALTER');
        if (!$result) {
            $status = false;
        }
    }

    return $status;
}
