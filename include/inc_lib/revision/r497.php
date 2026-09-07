<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 497 Update Check
function phpwcms_revision_r497()
{
    $status = true;

    // Check if seo log hash (for filter unique items) field exists
    if (!_dbColumnExists('log_seo', 'hash')) {
        $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . "log_seo` ADD `hash` CHAR(32) NOT NULL DEFAULT ''", 'ALTER');
        if ($result) {
            _dbQuery('UPDATE `' . DB_PREPEND . 'log_seo` SET `hash`=MD5(LOWER(CONCAT(domain,query)))', 'UPDATE');
            _dbQuery('ALTER TABLE `' . DB_PREPEND . 'log_seo` ADD INDEX (`hash`)', 'ALTER');
        } else {
            $status = false;
        }
    }

    // switch crossreference field type from INT to VARCHAR
    if (_dbTableExists('crossreference')) {
        $result = _dbQuery('SHOW COLUMNS FROM `' . DB_PREPEND . "crossreference` LIKE 'cref_type'");

        if (isset($result[0]['Type']) && substr(strtolower($result[0]['Type']), 0, 3) === 'int') {
            // Drop index first
            if (_dbIndexExists('crossreference', 'cref_type')) {
                _dbQuery('ALTER TABLE `' . DB_PREPEND . 'crossreference` DROP INDEX `cref_type`', 'ALTER');
            }
            $result = _dbQuery('ALTER TABLE `' . DB_PREPEND . "crossreference` CHANGE `cref_type` `cref_type` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER');
            if (!$result) {
                $status = false;
            }

            // Add new index
            if (!_dbIndexExists('crossreference', 'cref_type')) {
                _dbQuery('ALTER TABLE `' . DB_PREPEND . 'crossreference` ADD INDEX (`cref_type`)', 'ALTER');
            }
            if (!_dbIndexExists('crossreference', 'cref_int')) {
                _dbQuery('ALTER TABLE `' . DB_PREPEND . 'crossreference` ADD INDEX (`cref_int`)', 'ALTER');
            }
            if (!_dbIndexExists('crossreference', 'cref_rid')) {
                _dbQuery('ALTER TABLE `' . DB_PREPEND . 'crossreference` ADD INDEX (`cref_rid`)', 'ALTER');
            }
            if (!_dbIndexExists('crossreference', 'cref_str')) {
                _dbQuery('ALTER TABLE `' . DB_PREPEND . 'crossreference` ADD INDEX (`cref_str`)', 'ALTER');
            }

            if ($result) {
                // Update feedimport References
                _dbUpdate('crossreference', ['cref_type' => 'feed_to_article_import'], "cref_str LIKE 'feedimport_%'");
            }
        }
    }

    // add language to article category, article and content part
    if (!_dbColumnExists('articlecat', 'acat_lang')) {
        if (!_dbQuery('ALTER TABLE `' . DB_PREPEND . "articlecat` ADD `acat_lang` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER')) {
            $status = false;
        }
        _dbQuery('ALTER TABLE `' . DB_PREPEND . 'articlecat` ADD INDEX (`acat_lang`)', 'ALTER');
    }
    if (!_dbColumnExists('article', 'article_lang')) {
        if (!_dbQuery('ALTER TABLE `' . DB_PREPEND . "article` ADD `article_lang` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER')) {
            $status = false;
        }
        _dbQuery('ALTER TABLE `' . DB_PREPEND . 'article` ADD INDEX (`article_lang`)', 'ALTER');
    }
    if (!_dbColumnExists('articlecontent', 'acontent_lang')) {
        if (!_dbQuery('ALTER TABLE `' . DB_PREPEND . "articlecontent` ADD `acontent_lang` VARCHAR(255) NOT NULL DEFAULT ''", 'ALTER')) {
            $status = false;
        }
        _dbQuery('ALTER TABLE `' . DB_PREPEND . 'articlecontent` ADD INDEX (`acontent_lang`)', 'ALTER');
    }

    return $status;
}
