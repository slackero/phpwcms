<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 548 Update Check
function phpwcms_revision_r550() {

    $status = true;


    if(_dbTableExists('phpwcms_shop_products')) {

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_shop_products` WHERE Field='shopprod_inventory'");

    if(!isset($result[0]['Field'])) {

        $alter = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_shop_products` ADD `shopprod_inventory` INT(11) NOT NULL DEFAULT '0'", 'ALTER');

        if(!$alter) {
            $status = false;
        }
    }

    }

    return $status;
}
