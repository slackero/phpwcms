<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

/**
 * Revision 561:
 * - Add the file usage tracking columns to phpwcms_file:
 *     f_used       - 1 = verified as referenced in content at some point
 *     f_pre_install- 1 = file already existed when this feature was deployed
 *   (both 0 = fresh upload, never seen in use)
 * - Add the index used by the unused-files lookup
 * - Backfill f_pre_install for every file that already exists, so a
 *   pre-existing file is not mistaken for a fresh, unused upload
 *
 * @return bool
 */
function phpwcms_revision_r561() {

    $status  = true;
    $columns_added = false;

    $new_columns = array(
        'f_used'        => "TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 = referenced in content at some point'",
        'f_pre_install' => "TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 = existed before file usage tracking was deployed'",
    );

    foreach ($new_columns as $column => $definition) {
        if (!_dbColumnExists('file', $column)) {
            $alter = 'ALTER TABLE `' . DB_PREPEND . 'file` ADD `' . $column . '` ' . $definition;
            if (!_dbQuery($alter, 'ALTER')) {
                $status = false;
            } else {
                $columns_added = true;
            }
        }
    }

    // index used by the unused-files lookup
    if ($status) {
        $index_exists = _dbQuery(
            'SHOW INDEX FROM `' . DB_PREPEND . "file` WHERE Key_name='f_used' AND Column_name='f_used'"
        );
        if (empty($index_exists[0]['Key_name'])) {
            $alter = 'ALTER TABLE `' . DB_PREPEND . "file` ADD KEY `f_used` (`f_used`)";
            if (!_dbQuery($alter, 'ALTER')) {
                $status = false;
            }
        }
    }

    // Backfill only when the column was just created. On a fresh installation
    // the setup SQL already ships both columns, and files uploaded before the
    // first login are new - they must not be marked as pre-existing.
    if ($status && $columns_added) {
        if (!_dbQuery(
            'UPDATE ' . DB_PREPEND . 'file SET f_pre_install=1 WHERE f_pre_install=0 AND f_kid=1 AND f_trash=0',
            'UPDATE'
        )) {
            $status = false;
        }
    }

    return $status;
}
