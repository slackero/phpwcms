<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 545 Update Check
function cmsgo_revision_r545() {

    $status = true;

    // do former revision check – fallback to r544
    if(cmsgo_revision_check_temp('544') !== true) {
        $status = cmsgo_revision_check('544');
    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_articlecontent` WHERE Field='acontent_attr_class'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecontent` ADD `acontent_attr_class` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_articlecontent` WHERE Field='acontent_attr_id'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecontent` ADD `acontent_attr_id` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_articlecontent` WHERE Field='acontent_setting'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecontent` ADD `acontent_setting` MEDIUMTEXT", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_articlecontent` WHERE Field='acontent_type_setting'");

    if(!isset($result[0]['Field'])) {

        $insert = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecontent` ADD `acontent_type_setting` VARCHAR(20) NOT NULL DEFAULT ''", 'ALTER');

        if(!$insert) {
            $status = false;
        }

    }

    return $status;
}
