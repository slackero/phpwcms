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
function r568_column_type($table, $field)
{
    $result = _dbQuery(
        'SHOW COLUMNS FROM `' . DB_PREPEND . 'phpwcms_' . $table . '` WHERE Field=' . _dbEscape($field)
    );

    return isset($result[0]['Type']) ? strtolower((string)$result[0]['Type']) : '';
}

/**
 * Load the aliases sharing a prefix. Article aliases are shared by article,
 * articlecat and content.
 *
 * @return array<string,bool>|null null when the query failed
 */
function r568_alias_bucket($prefix)
{
    $like = _dbEscape(addcslashes($prefix, '%_'), true, '', '%');

    $rows = _dbQuery(
        'SELECT article_alias AS a FROM `' . DB_PREPEND . 'phpwcms_article` WHERE article_alias LIKE ' . $like
        . ' UNION ALL SELECT acat_alias AS a FROM `' . DB_PREPEND . 'phpwcms_articlecat` WHERE acat_alias LIKE ' . $like
        . ' UNION ALL SELECT cnt_alias AS a FROM `' . DB_PREPEND . 'phpwcms_content` WHERE cnt_alias LIKE ' . $like
    );

    if (!is_array($rows)) {
        return null;
    }

    $set = array();
    foreach ($rows as $row) {
        if (!empty($row['a'])) {
            $set[mb_strtolower($row['a'], PHPWCMS_CHARSET)] = true;
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
function r568_unique_alias($alias, $maxlen)
{
    $alias = (string)$alias;
    if (mb_strlen($alias, PHPWCMS_CHARSET) <= $maxlen) {
        return $alias;
    }

    $bucket = r568_alias_bucket(
        mb_substr($alias, 0, max(1, $maxlen - 7), PHPWCMS_CHARSET)
    );
    if ($bucket === null) {
        return false;
    }

    for ($counter = 0; $counter <= 100000; $counter++) {
        $suffix    = $counter ? '-' . $counter : '';
        $base      = max(0, $maxlen - mb_strlen($suffix, PHPWCMS_CHARSET));
        $candidate = mb_substr($alias, 0, $base, PHPWCMS_CHARSET) . $suffix;

        if (!isset($bucket[mb_strtolower($candidate, PHPWCMS_CHARSET)])) {
            return $candidate;
        }
    }

    return false;
}

/**
 * Append the old -> new alias mapping to the migration log. Best effort: the
 * log must not block the migration.
 */
function r568_log_alias($table, $id, $old, $new)
{
    $line = date('Y-m-d H:i:s') . "\t" . $table . "\t" . (int)$id . "\t" . $old . "\t" . $new . "\n";

    $targets = array();
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
 * Make sure the calendar_refid index is a prefix index no longer than
 * $max_prefix, so neither a later utf8mb4 conversion nor a column shrink can
 * exceed the key length limit.
 */
function r568_calendar_index_prefix($max_prefix = 768)
{
    $type = r568_column_type('calendar', 'calendar_refid');
    if (!preg_match('/^varchar\((\d+)\)$/', $type, $match)) {
        return false;
    }

    $prefix = min($max_prefix, (int)$match[1]);
    if ($prefix < 1) {
        return false;
    }

    $index = _dbQuery("SHOW INDEX FROM `" . DB_PREPEND . "phpwcms_calendar` WHERE Key_name='calendar_refid'");
    if (!is_array($index)) {
        return false;
    }

    if (count($index) === 0) {
        return (bool)_dbQuery(
            'ALTER TABLE `' . DB_PREPEND . 'phpwcms_calendar` ADD KEY `calendar_refid` (`calendar_refid`(' . $prefix . '))',
            'ALTER'
        );
    }

    foreach ($index as $row) {
        if (!empty($row['Sub_part']) && (int)$row['Sub_part'] <= $prefix) {
            return true;
        }
    }

    return (bool)_dbQuery(
        'ALTER TABLE `' . DB_PREPEND . 'phpwcms_calendar`'
        . ' DROP KEY `calendar_refid`, ADD KEY `calendar_refid` (`calendar_refid`(' . $prefix . '))',
        'ALTER'
    );
}

/**
 * Prepare the index for shrinking calendar_refid to $new_length. A full index
 * survives the shrink, only a prefix longer than the new column has to go.
 */
function r568_calendar_index_trim($new_length)
{
    $index = _dbQuery("SHOW INDEX FROM `" . DB_PREPEND . "phpwcms_calendar` WHERE Key_name='calendar_refid'");
    if (!is_array($index)) {
        return false;
    }

    foreach ($index as $row) {
        if (!empty($row['Sub_part']) && (int)$row['Sub_part'] > $new_length) {
            return r568_calendar_index_prefix($new_length);
        }
    }

    return true;
}


// Revision 567 Update Check
function phpwcms_revision_r568()
{
    $status = true;
    $maxlen = 500;

    $tables = array(
        'article'    => array('id' => 'article_id', 'field' => 'article_alias'),
        'articlecat' => array('id' => 'acat_id', 'field' => 'acat_alias'),
        'content'    => array('id' => 'cnt_id', 'field' => 'cnt_alias'),
    );

    // 1. Truncate overlong aliases without creating duplicates
    foreach ($tables as $table => $meta) {
        $rows = _dbQuery(
            'SELECT `' . $meta['id'] . '` AS id, `' . $meta['field'] . '` AS alias'
            . ' FROM `' . DB_PREPEND . 'phpwcms_' . $table . '`'
            . ' WHERE CHAR_LENGTH(`' . $meta['field'] . '`) > ' . $maxlen
        );

        if (!is_array($rows)) {
            if (_dbErrorNum()) {
                $status = false;
            }
            continue;
        }

        foreach ($rows as $row) {
            $candidate = r568_unique_alias($row['alias'], $maxlen);

            if ($candidate === false) {
                $status = false;
                continue;
            }

            r568_log_alias($table, $row['id'], $row['alias'], $candidate);

            // Compare the old value too: a concurrently saved alias must not
            // be overwritten by the migration
            $updated = _dbQuery(
                'UPDATE `' . DB_PREPEND . 'phpwcms_' . $table . '` SET `' . $meta['field'] . '` = ' . _dbEscape($candidate)
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
        $GLOBALS['phpwcms']['revision_return'] = trim(
            (string)($GLOBALS['phpwcms']['revision_return'] ?? '')
            . "\nAlias migration incomplete: the revision will retry."
        );

        return false;
    }

    // 2. Normalise the alias columns to VARCHAR(500): shrink the wide ones
    //    once the data fits, widen the 255 columns
    foreach (array(array('article', 'article_alias'), array('articlecat', 'acat_alias')) as $meta) {
        $type = r568_column_type($meta[0], $meta[1]);
        if ($type === '') {
            $GLOBALS['phpwcms']['revision_return'] = 'Could not read the type of ' . $meta[0] . '.' . $meta[1];
            $status = false;
            continue;
        }
        if (preg_match('/^varchar\((\d+)\)$/', $type, $match) && (int)$match[1] !== 500) {
            if (!_dbQuery(
                'ALTER TABLE `' . DB_PREPEND . 'phpwcms_' . $meta[0] . '`'
                . ' CHANGE `' . $meta[1] . '` `' . $meta[1] . '` VARCHAR(500) NOT NULL DEFAULT \'\'',
                'ALTER'
            )) {
                $status = false;
            }
        }
    }

    // 3. calendar_refid: shrink to 500 when all values fit, otherwise keep the
    //    column and rebuild the index as a prefix so utf8mb4 stays possible
    $refid_type = r568_column_type('calendar', 'calendar_refid');
    if ($refid_type === '') {
        $status = false;
    } elseif (preg_match('/^varchar\((\d+)\)$/', $refid_type, $match) && (int)$match[1] > 500) {
        $max = _dbQuery(
            'SELECT MAX(CHAR_LENGTH(`calendar_refid`)) AS maxlen FROM `' . DB_PREPEND . 'phpwcms_calendar`'
        );
        $max_length = isset($max[0]['maxlen']) ? (int)$max[0]['maxlen'] : 0;

        if ($max_length <= 500) {
            if (!r568_calendar_index_trim(500)) {
                $status = false;
            } elseif (!_dbQuery(
                'ALTER TABLE `' . DB_PREPEND . 'phpwcms_calendar`'
                . ' CHANGE `calendar_refid` `calendar_refid` VARCHAR(500) NOT NULL DEFAULT \'\'',
                'ALTER'
            )) {
                $status = false;
            }
        } elseif (!r568_calendar_index_prefix()) {
            $status = false;
        }
    }

    return $status;
}
