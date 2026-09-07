<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 421 Update Check
function phpwcms_revision_r421()
{
    $status = true;

    // Add column for default content part
    if (_dbTableExists('categories') && !_dbColumnExists('categories', 'cat_sort')) {
        $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . "categories` ADD `cat_sort` INT(11) NOT NULL DEFAULT '0'", 'ALTER');
        if (!$result) {
            $status = false;
        }
        $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . 'categories` ADD INDEX (`cat_sort`)', 'ALTER');
        if (!$result) {
            $status = false;
        }
    }

    return $status;
}
