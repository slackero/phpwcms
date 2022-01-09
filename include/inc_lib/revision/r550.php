<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


// Revision 548 Update Check
function phpwcms_revision_r550() {

    $status = true;

    // do former revision check – fallback to r548
    if(phpwcms_revision_check_temp('549') !== true) {
        $status = phpwcms_revision_check('549');
    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_shop_products` WHERE Field='shopprod_inventory'");

    if(!isset($result[0]['Field'])) {

        $alter = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_shop_products` ADD `shopprod_inventory` INT(11) NOT NULL DEFAULT '0'", 'ALTER');

        if(!$alter) {
            $status = false;
        }
    }

    return $status;
}
