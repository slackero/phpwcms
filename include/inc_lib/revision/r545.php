<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 545 Update Check
function phpwcms_revision_r545()
{
    $status = true;

    if (!_dbColumnExists('articlecontent', 'acontent_attr_class')) {
        $insert = _dbQuery('ALTER TABLE `' . DB_PREPEND . "articlecontent` ADD `acontent_attr_class` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
        if (!$insert) {
            $status = false;
        }
    }

    if (!_dbColumnExists('articlecontent', 'acontent_attr_id')) {
        $insert = _dbQuery('ALTER TABLE `' . DB_PREPEND . "articlecontent` ADD `acontent_attr_id` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
        if (!$insert) {
            $status = false;
        }
    }

    if (!_dbColumnExists('articlecontent', 'acontent_setting')) {
        $insert = _dbQuery('ALTER TABLE `' . DB_PREPEND . 'articlecontent` ADD `acontent_setting` MEDIUMTEXT', 'ALTER');
        if (!$insert) {
            $status = false;
        }
    }

    if (!_dbColumnExists('articlecontent', 'acontent_type_setting')) {
        $insert = _dbQuery('ALTER TABLE `' . DB_PREPEND . "articlecontent` ADD `acontent_type_setting` VARCHAR(20) NOT NULL DEFAULT ''", 'ALTER');
        if (!$insert) {
            $status = false;
        }
    }

    return $status;
}
