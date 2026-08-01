<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 546 Update Check
function phpwcms_revision_r546() {

    $status = true;


    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_file` WHERE Field='f_image_height'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_file` ADD `f_image_height` VARCHAR(20) NOT NULL DEFAULT '' AFTER `f_ext`", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_file` WHERE Field='f_image_width'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_file` ADD `f_image_width` VARCHAR(20) NOT NULL DEFAULT '' AFTER `f_ext`", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_file` WHERE Field='f_is_variation'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_file` ADD `f_is_variation` INT(11) NOT NULL DEFAULT '0' AFTER `f_kid`, ADD INDEX (`f_is_variation`)", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    return $status;
}
