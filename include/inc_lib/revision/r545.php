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


// Revision 545 Update Check
function phpwcms_revision_r545() {

    $status = true;

    // do former revision check – fallback to r544
    if(phpwcms_revision_check_temp('544') !== true) {
        $status = phpwcms_revision_check('544');
    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecontent` WHERE Field='acontent_attr_class'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecontent` ADD `acontent_attr_class` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecontent` WHERE Field='acontent_attr_id'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecontent` ADD `acontent_attr_id` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecontent` WHERE Field='acontent_setting'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecontent` ADD `acontent_setting` MEDIUMTEXT", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecontent` WHERE Field='acontent_type_setting'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecontent` ADD `acontent_type_setting` VARCHAR(20) NOT NULL DEFAULT ''", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    return $status;
}
