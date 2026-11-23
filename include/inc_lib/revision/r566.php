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
 * Return the column type of a table field, empty string when unknown.
 */
function r566_column_type($table, $field)
{
    $result = _dbQuery(
        'SHOW COLUMNS FROM `' . DB_PREPEND . $table . '` WHERE Field=' . _dbEscape($field)
    );

    return isset($result[0]['Type']) ? strtolower((string)$result[0]['Type']) : '';
}

/**
 * Load the aliases of a namespace sharing a prefix. Article aliases are shared
 * by article, articlecat and content; file aliases form their own namespace.
 *
 * @return array<string,bool>|null null when a query failed
 */
function r566_alias_bucket($namespace, $prefix)
{
    $like = _dbEscape($prefix, true, '', '%', true);

    if ($namespace === 'file') {
        $rows = _dbQuery(
            'SELECT f_alias AS a FROM `' . DB_PREPEND . 'file` WHERE f_alias LIKE ' . $like
        );
    } else {
        $rows = _dbQuery(
            'SELECT article_alias AS a FROM `' . DB_PREPEND . 'article` WHERE article_alias LIKE ' . $like
            . ' UNION ALL SELECT acat_alias AS a FROM `' . DB_PREPEND . 'articlecat` WHERE acat_alias LIKE ' . $like
            . ' UNION ALL SELECT cnt_alias AS a FROM `' . DB_PREPEND . 'content` WHERE cnt_alias LIKE ' . $like
        );
    }

    if (!is_array($rows)) {
        return null;
    }

    $set = [];
    foreach ($rows as $row) {
        if (!empty($row['a'])) {
            $set[\voku\helper\UTF8::strtolower($row['a'])] = true;
        }
    }

    return $set;
}

/**
 * Truncate an alias to $maxlen characters without creating a duplicate.
 * The -N suffix is appended before limiting, so it never gets cut.
 *
 * @return string|false
 */
function r566_unique_alias($alias, $namespace, $maxlen)
{
    $alias = (string)$alias;
    if (\voku\helper\UTF8::strlen($alias) <= $maxlen) {
        return $alias;
    }

    // All candidates keep at least the first $maxlen - 7 characters, which
    // covers the 7-char suffix of the 6-digit counter 100000; query only that
    // slice instead of the whole table.
    $bucket = r566_alias_bucket(
        $namespace,
        \voku\helper\UTF8::substr($alias, 0, max(1, $maxlen - 7))
    );
    if ($bucket === null) {
        return false;
    }

    // Prefer the plain truncated alias; only add a free -N suffix when it is
    // already taken
    for ($counter = 0; $counter <= 100000; $counter++) {
        $suffix    = $counter ? '-' . $counter : '';
        $base      = max(0, $maxlen - \voku\helper\UTF8::strlen($suffix));
        $candidate = \voku\helper\UTF8::substr($alias, 0, $base) . $suffix;

        if (isset($bucket[\voku\helper\UTF8::strtolower($candidate)])) {
            continue;
        }

        if (
            $namespace !== 'file'
            && defined('PHPWCMS_ROOT')
            && defined('PHPWCMS_REWRITE_EXT')
            && PHPWCMS_REWRITE_EXT !== ''
            && is_file(PHPWCMS_ROOT . '/' . $candidate . PHPWCMS_REWRITE_EXT)
        ) {
            continue;
        }

        return $candidate;
    }

    return false;
}

/**
 * Append the old -> new alias mapping to the migration log before the row is
 * updated, so rewritten URLs can be redirected later. When the following
 * UPDATE fails and the revision retries, the same mapping can appear twice;
 * treat the log as an append-only history, not a unique map.
 */
function r566_log_alias($table, $id, $old, $new)
{
    $line = date('Y-m-d H:i:s') . "\t" . $table . "\t" . (int)$id . "\t" . $old . "\t" . $new . "\n";

    $targets = [];
    if (defined('PHPWCMS_LOGDIR')) {
        $targets[] = PHPWCMS_LOGDIR . '/phpwcms_alias_migration.log';
    }
    if (defined('PHPWCMS_TEMP')) {
        $targets[] = PHPWCMS_TEMP . 'alias_migration.log';
    }

    foreach ($targets as $target) {
        if (@file_put_contents($target, $line, FILE_APPEND) !== false) {
            return true;
        }
    }

    return false;
}

/**
 * Attach a message to the revision result shown by the revision runner.
 */
function r566_revision_message($message)
{
    $GLOBALS['phpwcms']['revision_return'] = trim(
        (string)($GLOBALS['phpwcms']['revision_return'] ?? '') . "\n" . $message
    );
}

/**
 * Make sure the calendar_refid index is a prefix index no longer than
 * $max_prefix, so neither a later utf8mb4 conversion nor a column shrink can
 * exceed the key length limit.
 */
function r566_calendar_index_prefix($max_prefix = 768)
{
    $type = r566_column_type('calendar', 'calendar_refid');
    if (!preg_match('/^varchar\((\d+)\)$/', $type, $match)) {
        return false;
    }

    // 768 chars are the utf8mb4 limit (3072 bytes); never exceed the column
    // or the requested maximum (the new column length when shrinking)
    $prefix = min($max_prefix, (int)$match[1]);
    if ($prefix < 1) {
        return false;
    }

    $index = _dbQuery("SHOW INDEX FROM `" . DB_PREPEND . "calendar` WHERE Key_name='calendar_refid'");
    if (!is_array($index)) {
        return false;
    }

    if (count($index) === 0) {
        return (bool)_dbQuery(
            'ALTER TABLE `' . DB_PREPEND . 'calendar` ADD KEY `calendar_refid` (`calendar_refid`(' . $prefix . '))',
            'ALTER'
        );
    }

    foreach ($index as $row) {
        if (!empty($row['Sub_part']) && (int)$row['Sub_part'] <= $prefix) {
            return true;
        }
    }

    return (bool)_dbQuery(
        'ALTER TABLE `' . DB_PREPEND . 'calendar`'
        . ' DROP KEY `calendar_refid`, ADD KEY `calendar_refid` (`calendar_refid`(' . $prefix . '))',
        'ALTER'
    );
}

/**
 * Prepare the index for shrinking calendar_refid to $new_length. A full index
 * survives the shrink, only a prefix longer than the new column has to go.
 */
function r566_calendar_index_trim($new_length)
{
    $index = _dbQuery("SHOW INDEX FROM `" . DB_PREPEND . "calendar` WHERE Key_name='calendar_refid'");
    if (!is_array($index)) {
        return false;
    }

    foreach ($index as $row) {
        if (!empty($row['Sub_part']) && (int)$row['Sub_part'] > $new_length) {
            return r566_calendar_index_prefix($new_length);
        }
    }

    return true;
}


// Revision 566 Update Check
function phpwcms_revision_r566()
{
    $status = true;
    $maxlen = defined('PHPWCMS_ALIAS_MAXLEN') ? (int)PHPWCMS_ALIAS_MAXLEN : 170;
    $maxlen = max(50, min(500, $maxlen));

    $tables = [
        'article'    => ['id' => 'article_id', 'field' => 'article_alias', 'namespace' => 'shared'],
        'articlecat' => ['id' => 'acat_id', 'field' => 'acat_alias', 'namespace' => 'shared'],
        'content'    => ['id' => 'cnt_id', 'field' => 'cnt_alias', 'namespace' => 'shared'],
        'file'       => ['id' => 'f_id', 'field' => 'f_alias', 'namespace' => 'file'],
    ];

    // 1. Truncate overlong aliases without creating duplicates
    foreach ($tables as $table => $meta) {
        $rows = _dbQuery(
            'SELECT `' . $meta['id'] . '` AS id, `' . $meta['field'] . '` AS alias'
            . ' FROM `' . DB_PREPEND . $table . '`'
            . ' WHERE CHAR_LENGTH(`' . $meta['field'] . '`) > ' . $maxlen
        );

        if (!is_array($rows)) {
            if (_dbErrorNum()) {
                $status = false;
            }
            continue;
        }

        foreach ($rows as $row) {
            $candidate = r566_unique_alias($row['alias'], $meta['namespace'], $maxlen);

            if ($candidate === false) {
                $status = false;
                continue;
            }

            // Best effort: the mapping log is informational, a read-only log
            // directory must not block the alias migration
            r566_log_alias($table, $row['id'], $row['alias'], $candidate);

            // Compare the old value too: a concurrently saved alias must not
            // be overwritten by the migration
            $updated = _dbQuery(
                'UPDATE `' . DB_PREPEND . $table . '` SET `' . $meta['field'] . '` = ' . _dbEscape($candidate)
                . ' WHERE `' . $meta['id'] . '` = ' . (int)$row['id']
                . ' AND `' . $meta['field'] . '` = ' . _dbEscape($row['alias']),
                'UPDATE'
            );
            if (!is_array($updated) || (int)($updated['AFFECTED_ROWS'] ?? 0) !== 1) {
                $status = false;
            }
        }
    }

    if (!$status) {
        r566_revision_message(
            'Alias migration incomplete: already rewritten aliases stay in place, '
            . 'column changes were skipped and the revision will retry.'
        );
        return false;
    }

    // 2. Normalise alias columns to VARCHAR(500): shrink the wide ones once
    //    the data fits, widen the 255 columns so a raised alias_maxlength works
    foreach (
        [
            ['article', 'article_alias'],
            ['articlecat', 'acat_alias'],
            ['content', 'cnt_alias'],
            ['file', 'f_alias'],
        ] as $meta
    ) {
        $type = r566_column_type($meta[0], $meta[1]);
        if ($type === '') {
            r566_revision_message('Could not read the type of ' . $meta[0] . '.' . $meta[1]);
            $status = false;
            continue;
        }
        if (!preg_match('/^varchar\((\d+)\)$/', $type, $match)) {
            r566_revision_message('Unexpected type ' . $type . ' for ' . $meta[0] . '.' . $meta[1]);
            $status = false;
            continue;
        }
        if ((int)$match[1] !== 500) {
            if (!_dbQuery(
                'ALTER TABLE `' . DB_PREPEND . $meta[0] . '`'
                . ' CHANGE `' . $meta[1] . '` `' . $meta[1] . '` VARCHAR(500) NOT NULL DEFAULT \'\'',
                'ALTER'
            )) {
                $status = false;
            }
        }
    }

    // 3. calendar_refid: shrink to 500 when all values fit, otherwise keep the
    //    column and rebuild the index as a prefix so utf8mb4 stays possible
    $refid_type = r566_column_type('calendar', 'calendar_refid');
    if ($refid_type === '') {
        r566_revision_message('Could not read the type of calendar.calendar_refid');
        $status = false;
    } elseif (!preg_match('/^varchar\((\d+)\)$/', $refid_type, $match)) {
        r566_revision_message('Unexpected type ' . $refid_type . ' for calendar.calendar_refid');
        $status = false;
    } elseif ((int)$match[1] > 500) {
        $max = _dbQuery(
            'SELECT MAX(CHAR_LENGTH(`calendar_refid`)) AS maxlen FROM `' . DB_PREPEND . 'calendar`'
        );
        $max_length = isset($max[0]['maxlen']) ? (int)$max[0]['maxlen'] : 0;

        if ($max_length <= 500) {
            // A prefix index longer than the new column length would make the
            // shrink fail with 1089, so trim it first; a full index survives
            if (!r566_calendar_index_trim(500)) {
                $status = false;
            } elseif (!_dbQuery(
                'ALTER TABLE `' . DB_PREPEND . 'calendar`'
                . ' CHANGE `calendar_refid` `calendar_refid` VARCHAR(500) NOT NULL DEFAULT \'\'',
                'ALTER'
            )) {
                $status = false;
            }
        } elseif (!r566_calendar_index_prefix()) {
            $status = false;
        }
    }

    return $status;
}
