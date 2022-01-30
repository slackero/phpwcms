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
function phpwcms_revision_r549() {

    $status = true;

    // do former revision check – fallback to r548
    if(phpwcms_revision_check_temp('548') !== true) {
        $status = phpwcms_revision_check('548');
    }

    $result = _dbQuery("SHOW COLUMNS FROM `".DB_PREPEND."phpwcms_articlecat` WHERE Field='acat_title'");

    if(!isset($result[0]['Field'])) {

        $alter = _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` ADD `acat_title` VARCHAR(2000) NOT NULL DEFAULT '' AFTER `acat_name`", 'ALTER');

        if(!$alter) {
            $status = false;
        } else {
            _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` CHANGE `acat_alias` `acat_alias` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecat` CHANGE `acat_pagetitle` `acat_pagetitle` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_article` CHANGE `article_alias` `article_alias` VARCHAR(1000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_article` CHANGE `article_pagetitle` `article_pagetitle` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_article` CHANGE `article_menutitle` `article_menutitle` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecontent` CHANGE `acontent_paginate_title` `acontent_paginate_title` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
            _dbQuery("ALTER TABLE `".DB_PREPEND."phpwcms_articlecontent` CHANGE `acontent_tab` `acontent_tab` VARCHAR(2000) NOT NULL DEFAULT ''", 'ALTER');
        }
    }

    return $status;
}
