<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 550 Update Check
function phpwcms_revision_r550()
{
    $status = true;

    if (_dbTableExists('shop_products')) {
        if (!_dbColumnExists('shop_products', 'shopprod_inventory')) {
            $alter = _dbQuery('ALTER TABLE `' . DB_PREPEND . "shop_products` ADD `shopprod_inventory` INT(11) NOT NULL DEFAULT '0'", 'ALTER');
            if (!$alter) {
                $status = false;
            }
        }
    }

    return $status;
}
