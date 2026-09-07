<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// Revision 549 Update Check
function phpwcms_revision_r549()
{
    $status = true;

    if (!_dbColumnExists('articlecat', 'acat_title')) {
        $alter = _dbQuery('ALTER TABLE `' . DB_PREPEND . "articlecat` ADD `acat_title` VARCHAR(2000) NOT NULL DEFAULT '' AFTER `acat_name`", 'ALTER');
        if (!$alter) {
            $status = false;
        }
    }

    $expansions = [
        ['articlecat', 'acat_alias', 'VARCHAR(1000)'],
        ['articlecat', 'acat_pagetitle', 'VARCHAR(2000)'],
        ['article', 'article_alias', 'VARCHAR(1000)'],
        ['article', 'article_pagetitle', 'VARCHAR(2000)'],
        ['article', 'article_menutitle', 'VARCHAR(2000)'],
        ['articlecontent', 'acontent_paginate_title', 'VARCHAR(2000)'],
        ['articlecontent', 'acontent_tab', 'VARCHAR(2000)'],
    ];

    foreach ($expansions as $exp) {
        [$table, $field, $target_type] = $exp;
        if (_dbColumnExists($table, $field)) {
            $col = _dbQuery('SHOW COLUMNS FROM `' . DB_PREPEND . $table . '` WHERE Field=' . _dbEscape($field));
            if (isset($col[0]['Type']) && strtolower($col[0]['Type']) !== strtolower($target_type)) {
                if (!_dbQuery('ALTER TABLE `' . DB_PREPEND . $table . '` CHANGE `' . $field . '` `' . $field . '` ' . $target_type . " NOT NULL DEFAULT ''", 'ALTER')) {
                    $status = false;
                }
            }
        }
    }

    return $status;
}
