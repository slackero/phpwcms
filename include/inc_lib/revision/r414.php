<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 414 Update Check
function phpwcms_revision_r414()
{
    $status = true;

    // Test against new shopping module fields
    if (_dbTableExists('shop_products')) {
        if (!_dbColumnExists('shop_products', 'shopprod_special_price')) {
            $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . 'shop_products` ADD `shopprod_special_price` TEXT NOT NULL', 'ALTER');
            if (!$result) {
                $status = false;
            }
        }

        if (!_dbColumnExists('shop_products', 'shopprod_track_view')) {
            $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . "shop_products` ADD `shopprod_track_view` INT(11) NOT NULL DEFAULT '0'", 'ALTER');
            if (!$result) {
                $status = false;
            }
            $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . 'shop_products` ADD INDEX (`shopprod_track_view`)', 'ALTER');
            if (!$result) {
                $status = false;
            }
        }

        if (!_dbColumnExists('shop_products', 'shopprod_lang')) {
            $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . "shop_products` ADD `shopprod_lang` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
            if (!$result) {
                $status = false;
            }
            $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . 'shop_products` ADD INDEX (`shopprod_lang`)', 'ALTER');
            if (!$result) {
                $status = false;
            }
        }
    }

    return $status;
}
