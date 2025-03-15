<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/


// Revision 548 Update Check
function cmsgo_revision_r549() {

    $status = true;

    // do former revision check – fallback to r548
    if(cmsgo_revision_check_temp('548') !== true) {
        $status = cmsgo_revision_check('548');
    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."cmsgo_articlecat` WHERE Field='acat_title'");

    if(!isset($result[0]['Field'])) {

        $alter = _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecat` ADD `acat_title` VARCHAR(2000) NOT NULL DEFAULT '' AFTER `acat_name`", 'ALTER');

        if(!$alter) {
            $status = false;
        } else {
            _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecat` CHANGE `acat_alias` `acat_alias` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecat` CHANGE `acat_pagetitle` `acat_pagetitle` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_article` CHANGE `article_alias` `article_alias` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_article` CHANGE `article_pagetitle` `article_pagetitle` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_article` CHANGE `article_menutitle` `article_menutitle` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecontent` CHANGE `acontent_paginate_title` `acontent_paginate_title` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."cmsgo_articlecontent` CHANGE `acontent_tab` `acontent_tab` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
        }
    }

    return $status;
}
