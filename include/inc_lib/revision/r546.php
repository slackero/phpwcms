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


// Revision 546 Update Check
function phpwcms_revision_r546() {

    $status = true;

    // do former revision check – fallback to r545
    if(phpwcms_revision_check_temp('545') !== true) {
        $status = phpwcms_revision_check('545');
    }

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
