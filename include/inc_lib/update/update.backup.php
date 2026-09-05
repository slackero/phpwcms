<?php
/**
 * phpwcms — self-update backup helpers (DB dump, file backup, change report)
 **/

if (!defined('PHPWCMS_INCLUDE_CHECK')) {
    die('You are not allowed to access this file directly.');
}

/**
 * Dump all phpwcms tables (schema + data) as gzipped SQL to $gzPath.
 * Pure PHP — no exec/mysqldump. Returns bool.
 */
function phpwcms_update_db_dump(string $gzPath): bool
{
    $tables = _dbQuery('SHOW TABLES');
    if (!$tables) {
        return false;
    }
    $gz = @gzopen($gzPath, 'wb9');
    if ($gz === false) {
        return false;
    }
    $status = true;
    $write = static function (string $data) use ($gz, &$status): void {
        if (gzwrite($gz, $data) === false) {
            $status = false;
        }
    };
    $write('-- phpwcms DB backup ' . date('Y-m-d H:i:s') . LF);
    foreach ($tables as $row) {
        $table = (string)reset($row);
        if (!str_starts_with($table, DB_PREPEND . 'phpwcms_')) {
            continue;
        }
        $create = _dbQuery('SHOW CREATE TABLE `' . $table . '`');
        if (!$create || !array_key_exists('Create Table', $create[0])) {
            $status = false;
            break;
        }
        $write(LF . 'DROP TABLE IF EXISTS `' . $table . '`;' . LF . $create[0]['Create Table'] . ';' . LF);
        if (!empty($GLOBALS['db']) && ($GLOBALS['db'] instanceof mysqli)) {
            $result = mysqli_query($GLOBALS['db'], 'SELECT * FROM `' . $table . '`', MYSQLI_USE_RESULT);
            if ($result) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $values = [];
                    foreach ($data as $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = '\'' . mysqli_real_escape_string($GLOBALS['db'], (string)$value) . '\'';
                        }
                    }
                    $write('INSERT INTO `' . $table . '` VALUES (' . implode(',', $values) . ');' . LF);
                }
                mysqli_free_result($result);
            }
        } else {
            $rows = _dbQuery('SELECT * FROM `' . $table . '`');
            foreach ($rows as $data) {
                $values = [];
                foreach ($data as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = '\'' . addslashes((string)$value) . '\'';
                    }
                }
                $write('INSERT INTO `' . $table . '` VALUES (' . implode(',', $values) . ');' . LF);
            }
        }
    }
    gzclose($gz);
    return $status;
}

/**
 * Mirror docroot-relative files into $backupDir, preserving relative paths.
 * Any unreadable source file fails the whole backup (returns false).
 */
function phpwcms_update_backup_files(array $files, string $backupDir): bool
{
    $backupDir = rtrim($backupDir, '/') . '/';
    foreach ($files as $rel) {
        $source = PHPWCMS_ROOT . '/' . $rel;
        if (!is_file($source)) {
            return false;
        }
        $target = $backupDir . $rel;
        $dir = dirname($target);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            return false;
        }
        if (!@copy($source, $target)) {
            return false;
        }
    }
    return true;
}

/**
 * Write the per-run change report (added/modified/deleted) with sha256.
 * $backupDir is the run root (content/backup/<run>/); the report is written
 * there as changed-files.txt and old hashes are read from the files/ mirror.
 */
function phpwcms_update_change_report(string $backupDir, array $added, array $modified, array $deleted): void
{
    $backupDir = rtrim($backupDir, '/') . '/';
    $lines = ['# phpwcms update change report ' . date('Y-m-d H:i:s'), ''];
    $lines[] = '## ADDED';
    foreach ($added as $rel) {
        $target = PHPWCMS_ROOT . '/' . $rel;
        $hash = is_file($target) ? (hash_file('sha256', $target) ?: '-') : '-';
        $lines[] = $rel . '  sha256:' . $hash;
    }
    $lines[] = '## MODIFIED';
    foreach ($modified as $rel) {
        $oldFile = $backupDir . 'files/' . $rel;
        $newFile = PHPWCMS_ROOT . '/' . $rel;
        $old = is_file($oldFile) ? (hash_file('sha256', $oldFile) ?: '-') : '-';
        $new = is_file($newFile) ? (hash_file('sha256', $newFile) ?: '-') : '-';
        $lines[] = $rel . '  ' . $old . ' -> ' . $new;
    }
    $lines[] = '## DELETED';
    foreach ($deleted as $rel) {
        $lines[] = $rel;
    }
    @file_put_contents($backupDir . 'changed-files.txt', implode(LF, $lines) . LF);
}
