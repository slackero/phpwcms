<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 533 Update Check
function phpwcms_revision_r533()
{
    $status = true;

    if (_dbTableExists('shop_products')) {
        if (!_dbColumnExists('shop_products', 'shopprod_overwrite_meta')) {
            $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . "shop_products` ADD `shopprod_overwrite_meta` INT(1) NOT NULL DEFAULT '1'", 'ALTER');
            if (!$result) {
                $status = false;
            }
        }
    }

    return $status;
}
