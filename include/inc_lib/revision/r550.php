<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 548 Update Check
function cmsgo_revision_r550() {

    $status = true;

    // do former revision check – fallback to r548
    if(cmsgo_revision_check_temp('549') !== true) {
        $status = cmsgo_revision_check('549');
    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_shop_products` WHERE Field='shopprod_inventory'");

    if(!isset($result[0]['Field'])) {

        $alter = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_shop_products` ADD `shopprod_inventory` INT(11) NOT NULL DEFAULT '0'", 'ALTER');

        if(!$alter) {
            $status = false;
        }
    }

    return $status;
}
