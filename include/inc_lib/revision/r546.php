<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 546 Update Check
function cmsgo_revision_r546() {

    $status = true;

    // do former revision check – fallback to r545
    if(cmsgo_revision_check_temp('545') !== true) {
        $status = cmsgo_revision_check('545');
    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_file` WHERE Field='f_image_height'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_file` ADD `f_image_height` VARCHAR(20) NOT NULL DEFAULT '' AFTER `f_ext`", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_file` WHERE Field='f_image_width'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_file` ADD `f_image_width` VARCHAR(20) NOT NULL DEFAULT '' AFTER `f_ext`", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_file` WHERE Field='f_is_variation'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_file` ADD `f_is_variation` INT(11) NOT NULL DEFAULT '0' AFTER `f_kid`, ADD INDEX (`f_is_variation`)", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    return $status;
}
