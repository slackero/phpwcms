<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 543 Update Check
function phpwcms_revision_r543()
{
    $status = true;

    if (!_dbColumnExists('file', 'f_title')) {
        $insert = _dbQuery('ALTER TABLE `' . DB_PREPEND . "file` ADD `f_title` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `f_sort`", 'ALTER');
        if (!$insert) {
            $status = false;
        }
    }

    if (!_dbColumnExists('file', 'f_alt')) {
        $insert = _dbQuery('ALTER TABLE `' . DB_PREPEND . "file` ADD `f_alt` VARCHAR(1000) NOT NULL DEFAULT '' AFTER `f_sort`", 'ALTER');
        if (!$insert) {
            $status = false;
        }
    }

    foreach (['f_keywords', 'f_shortinfo', 'f_copyright', 'f_tags'] as $field) {
        $col = _dbQuery("SHOW COLUMNS FROM `" . DB_PREPEND . "file` WHERE Field='" . $field . "'");
        if (isset($col[0]['Type']) && strtolower($col[0]['Type']) !== 'varchar(1000)') {
            if (!_dbQuery('ALTER TABLE `' . DB_PREPEND . 'file` CHANGE `' . $field . '` `' . $field . "` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER')) {
                $status = false;
            }
        }
    }

    return $status;
}
