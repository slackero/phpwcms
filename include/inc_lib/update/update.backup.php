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
    gzwrite($gz, '-- phpwcms DB backup ' . date('Y-m-d H:i:s') . LF);
    $status = true;
    foreach ($tables as $row) {
        $table = (string)reset($row);
        if (!str_contains($table, DB_PREPEND . 'phpwcms_')) {
            continue;
        }
        $create = _dbQuery('SHOW CREATE TABLE `' . $table . '`');
        if (!$create) {
            $status = false;
            break;
        }
        gzwrite($gz, LF . 'DROP TABLE IF EXISTS `' . $table . '`;' . LF . (string)reset($create[0]) . ';' . LF);
        $rows = _dbQuery('SELECT * FROM `' . $table . '`');
        foreach ($rows as $data) {
            $values = [];
            foreach ($data as $value) {
                $values[] = $value === null ? 'NULL' : '\'' . addslashes((string)$value) . '\'';
            }
            gzwrite($gz, 'INSERT INTO `' . $table . '` VALUES (' . implode(',', $values) . ');' . LF);
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
    foreach ($files as $rel) {
        $source = PHPWCMS_ROOT . '/' . $rel;
        if (!is_file($source)) {
            return false;
        }
        $target = $backupDir . '/' . $rel;
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
 */
function phpwcms_update_change_report(string $backupDir, array $added, array $modified, array $deleted): void
{
    $lines = ['# phpwcms update change report ' . date('Y-m-d H:i:s'), ''];
    $lines[] = '## ADDED';
    foreach ($added as $rel) {
        $lines[] = $rel . '  sha256:' . hash_file('sha256', PHPWCMS_ROOT . '/' . $rel);
    }
    $lines[] = LF . '## MODIFIED';
    foreach ($modified as $rel) {
        $old = hash_file('sha256', $backupDir . '/' . $rel) ?: '-';
        $new = hash_file('sha256', PHPWCMS_ROOT . '/' . $rel) ?: '-';
        $lines[] = $rel . '  ' . $old . ' -> ' . $new;
    }
    $lines[] = LF . '## DELETED';
    foreach ($deleted as $rel) {
        $lines[] = $rel;
    }
    @file_put_contents($backupDir . '/changed-files.txt', implode(LF, $lines) . LF);
}
