<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 416 Update Check
function phpwcms_revision_r416()
{
    $status = true;

    // Add column for default content part
    if (!_dbColumnExists('articlecat', 'acat_cpdefault')) {
        $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . "articlecat` ADD `acat_cpdefault` INT(10) UNSIGNED NOT NULL DEFAULT '0'", 'ALTER');
        if (!$result) {
            $status = false;
        }
    }

    return $status;
}
