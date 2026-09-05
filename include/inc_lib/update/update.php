<?php
/**
 * phpwcms — self-update engine
 **/

if (!defined('PHPWCMS_INCLUDE_CHECK')) {
    die('You are not allowed to access this file directly.');
}

require_once __DIR__ . '/update.api.php';
require_once __DIR__ . '/update.backup.php';

class phpwcms_update
{
    public const SKIP_PATHS = ['include/config/', 'filearchive/', 'content/', 'upload/'];
    public const SKIP_FILES = ['.htaccess', 'robots.txt', '.update-manifest'];
    public const MAINTENANCE_FLAG = 'update/maintenance.flag';
    public const STALE_FLAG_SECONDS = 900; // 15 min
    public const MIN_FREE_DISK_BYTES = 10 * 1024 * 1024; // rough preflight floor

    private string $logFile;
    private string $workDir;
    private int $userId;
    private int $updateId = 0;
    private string $backupDir = '';
    private string $zipPath = '';

    /**
     * @param int    $userId  id of the admin triggering the update
     * @param string $zipPath optional local release zip. When non-empty, run()
     *                        skips the GitHub fetch/download and applies this zip
     *                        directly (used by the e2e harness / offline installs).
     *                        Default behavior is unchanged.
     */
    public function __construct(int $userId, string $zipPath = '')
    {
        $this->userId = $userId;
        $this->zipPath = $zipPath;
        $this->workDir = PHPWCMS_TEMP . 'update/';
        $this->logFile = $this->workDir . 'update.log';
        if (!is_dir($this->workDir)) {
            @mkdir($this->workDir, 0775, true);
        }
    }

    public static function isSkipped(string $rel): bool
    {
        if (in_array($rel, self::SKIP_FILES, true)) {
            return true;
        }
        foreach (self::SKIP_PATHS as $path) {
            if (str_starts_with($rel, $path)) {
                return true;
            }
        }
        return false;
    }

    public static function isNewer(string $version): bool
    {
        return version_compare($version, PHPWCMS_VERSION, '>');
    }

    public function check(): array|false
    {
        $release = phpwcms_update_fetch_latest_release();
        if ($release === false) {
            return false;
        }
        $release['newer'] = self::isNewer($release['version']);
        return $release;
    }

    /** Full update run. Returns result array. */
    public function run(string $expectedTag): array
    {
        $result = ['success' => false, 'error' => '', 'files' => 0, 'backup' => '', 'log' => []];
        $stageDir = '';
        $lock = $this->lock();                       // flock on workDir/update.lock
        if (!$lock) {
            return array_merge($result, ['error' => 'Update already running (lock held).']);
        }
        try {
            // P0 preflight: ZipArchive, backup dir writable + .htaccess, disk space
            if (!class_exists('ZipArchive')) {
                throw new RuntimeException('PHP zip extension missing.');
            }
            $this->initBackupDir();                  // content/backup/<run>/ + .htaccess + files/ subdir; throws on failure
            $free = @disk_free_space($this->backupDir);
            if ($free !== false && $free < self::MIN_FREE_DISK_BYTES) {
                throw new RuntimeException('Not enough free disk space for update.');
            }
            // P1 DB backup FIRST — throws on failure, aborts before download
            if (!phpwcms_update_db_dump($this->backupDir . 'database.sql.gz')) {
                throw new RuntimeException('DB backup failed — update aborted.');
            }
            $this->log('DB backup written to ' . $this->backupDir . 'database.sql.gz');
            // P2 check release, tag must match $expectedTag (TOCTOU guard), must be newer
            if ($this->zipPath !== '') {
                // Local-zip seam: derive a synthetic release from the zip's staged revision.php.
                $release = $this->releaseFromZip($this->zipPath);
                if ($release === false) {
                    throw new RuntimeException('Could not read release from local zip.');
                }
                $zipPath = $this->zipPath;
            } else {
                $release = $this->check();
                if ($release === false) {
                    throw new RuntimeException('Could not fetch release info.');
                }
                $zipPath = $this->workDir . 'phpwcms-' . $release['tag'] . '.zip';
                if (!phpwcms_update_download_asset($release['zip'], $zipPath)) {
                    throw new RuntimeException('Download failed.');
                }
            }
            if ($release['tag'] !== $expectedTag) {
                throw new RuntimeException('Release tag changed during update.');
            }
            if (!$release['newer']) {
                throw new RuntimeException('Installed version is not older than release.');
            }
            $this->ensureUpdateLogTable();                // table may not exist yet on pre-r559 installs (r559 runs later, at P9)
            $this->insertLogRow($release);           // insert phpwcms_update_log row status running
            // P4 verify: open zip, read .update-manifest + staged revision.php version == $release['version']
            $manifest = $this->verifyZip($zipPath, $release);   // throws; returns [file => sha256]
            // P5 extract to workDir/stage/ with sha256 check per file
            $stageDir = $this->extract($zipPath, $manifest);    // throws; returns workDir/stage/
            // P6 compute plan: added/modified/deleted vs old manifest (docroot .update-manifest, may be absent)
            $plan = $this->buildPlan($manifest, $stageDir);     // ['added'=>[], 'modified'=>[], 'deleted'=>[]]
            // P7 file backup of modified+deleted (before any overwrite)
            $backupTargets = array_merge($plan['modified'], $plan['deleted']);
            if (!phpwcms_update_backup_files($backupTargets, $this->backupDir . 'files/')) {
                throw new RuntimeException('File backup failed — update aborted.');
            }
            // P8 maintenance ON, apply, maintenance OFF
            $this->setMaintenance(true);
            try {
                $this->apply($plan, $stageDir, $manifest);      // copy added+modified, delete deletions, write new manifest
                $this->setMaintenance(false);
            } catch (Throwable $e) {
                // Keep maintenance flag active on apply failure so broken state is not exposed
                throw $e;
            }
            phpwcms_update_change_report($this->backupDir, $plan['added'], $plan['modified'], $plan['deleted']);
            // P9 inline revision run + success row
            $revisionOk = phpwcms_revision_check($this->targetRevision());
            $revError = $revisionOk ? '' : ($GLOBALS['phpwcms']['revision_error'] ?? 'Database revision migration failed');
            $this->finishLogRow($revisionOk ? 'success' : 'failed', count($plan['added']) + count($plan['modified']) + count($plan['deleted']), $revError);
            return array_merge($result, [
                'success' => $revisionOk,
                'error' => $revisionOk ? '' : 'Files updated but DB revision failed — check revision_error log.',
                'files' => count($plan['added']) + count($plan['modified']) + count($plan['deleted']),
                'backup' => $this->backupDir,
                'log' => $this->logTail(),
            ]);
        } catch (Throwable $e) {
            $this->log('FAILED: ' . $e->getMessage());
            if ($this->updateId) {
                $this->finishLogRow('failed', 0, $e->getMessage());
            }
            return array_merge($result, ['error' => $e->getMessage(), 'log' => $this->logTail()]);
        } finally {
            if ($stageDir !== '') {
                $this->rrmdir($stageDir);
            }
            $this->unlock($lock);
        }
    }

    /** Restore files from the backup of a given update run. */
    public function rollback(int $updateId): array
    {
        $result = ['success' => false, 'error' => ''];
        $lock = $this->lock();                       // flock on workDir/update.lock
        if (!$lock) {
            return array_merge($result, ['error' => 'Update already running (lock held).']);
        }
        try {
            $row = _dbQuery('SELECT * FROM ' . DB_PREPEND . 'phpwcms_update_log WHERE update_id = ' . (int)$updateId);
            if (!$row || !isset($row[0])) {
                return array_merge($result, ['error' => 'Update run not found.']);
            }
            $row = $row[0];
            $backupDir = (string)($row['update_backup'] ?? '');
            if ($backupDir === '' || !is_dir($backupDir . 'files/')) {
                return array_merge($result, ['error' => 'No file backup available for this run.']);
            }
            $mirror = $backupDir . 'files/';
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($mirror, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($iterator as $item) {
                if (!$item->isFile()) {
                    continue;
                }
                $rel = substr($item->getPathname(), strlen($mirror));
                $target = PHPWCMS_ROOT . '/' . $rel;
                $dir = dirname($target);
                if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
                    return array_merge($result, ['error' => 'Could not create restore dir ' . $dir]);
                }
                if (!@copy($item->getPathname(), $target)) {
                    return array_merge($result, ['error' => 'Failed restoring ' . $rel]);
                }
            }
            $currentStatus = (string)($row['update_status'] ?? '');
            if ($currentStatus !== 'rolled_back') {
                $res = _dbQuery(
                    'UPDATE ' . DB_PREPEND . 'phpwcms_update_log SET update_status = \'rolled_back\' WHERE update_id = ' . (int)$updateId,
                    'UPDATE'
                );
                if (empty($res['AFFECTED_ROWS'])) {
                    return array_merge($result, ['error' => 'Could not mark run as rolled back.']);
                }
            }
            // Files are back to the old version; drop the new manifest so the next
            // update treats the install as manifest-less (no spurious deletions).
            @unlink(PHPWCMS_ROOT . '/.update-manifest');
            return array_merge($result, ['success' => true]);
        } finally {
            $this->unlock($lock);
        }
    }

    public function clearMaintenance(): void
    {
        @unlink(PHPWCMS_TEMP . self::MAINTENANCE_FLAG);
    }

    public static function maintenanceActive(): bool
    {
        $flag = PHPWCMS_TEMP . self::MAINTENANCE_FLAG;
        if (!is_file($flag)) {
            return false;
        }
        if (time() - (int)filemtime($flag) > self::STALE_FLAG_SECONDS) {
            return false; // stale — frontend serves normally
        }
        return true;
    }

    // --- private helpers ---

    private function lock()
    {
        $lockFile = $this->workDir . 'update.lock';
        $handle = @fopen($lockFile, 'c');
        if ($handle === false) {
            return false;
        }
        if (!flock($handle, LOCK_EX | LOCK_NB)) {
            fclose($handle);
            return false;
        }
        return $handle;
    }

    private function unlock($lock): void
    {
        if (is_resource($lock)) {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    private function log(string $message): void
    {
        @file_put_contents($this->logFile, date('Y-m-d H:i:s') . ' ' . $message . LF, FILE_APPEND);
    }

    /** Last ~40 lines of the run log, newest last. Empty array if no log yet. */
    private function logTail(): array
    {
        if (!is_file($this->logFile)) {
            return [];
        }
        $lines = file($this->logFile, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            return [];
        }
        return array_slice($lines, -40);
    }

    private function initBackupDir(): void
    {
        $this->backupDir = PHPWCMS_ROOT . '/content/backup/' . date('Y-m-d-His') . '/';
        if (!is_dir($this->backupDir) && !@mkdir($this->backupDir, 0775, true) && !is_dir($this->backupDir)) {
            throw new RuntimeException('Could not create backup dir ' . $this->backupDir);
        }
        if (!is_writable($this->backupDir)) {
            throw new RuntimeException('Backup dir not writable: ' . $this->backupDir);
        }
        $htaccess = $this->backupDir . '.htaccess';
        if (!is_file($htaccess)) {
            @file_put_contents($htaccess, "Require all denied\nDeny from all\n");
        }
        if (!is_file($htaccess) || !is_writable($htaccess)) {
            throw new RuntimeException('Could not secure backup dir with .htaccess.');
        }
        $filesDir = $this->backupDir . 'files/';
        if (!is_dir($filesDir) && !@mkdir($filesDir, 0775, true) && !is_dir($filesDir)) {
            throw new RuntimeException('Could not create backup files dir.');
        }
    }

    /**
     * Make sure phpwcms_update_log exists before the first log INSERT.
     * On installs older than r559 the table is only created by the revision
     * chain (P9) — which runs after insertLogRow() — so bootstrap it here
     * with the same DDL r559 uses, or the updater deadlocks on itself.
     */
    private function ensureUpdateLogTable(): void
    {
        if (_dbTableExists('phpwcms_update_log')) {
            return;
        }
        $create = 'CREATE TABLE IF NOT EXISTS `' . DB_PREPEND . "phpwcms_update_log` (
            `update_id` INT NOT NULL AUTO_INCREMENT,
            `update_from` VARCHAR(32) NOT NULL DEFAULT '',
            `update_to` VARCHAR(32) NOT NULL DEFAULT '',
            `update_tag` VARCHAR(64) NOT NULL DEFAULT '',
            `update_status` ENUM('running','success','failed','rolled_back') NOT NULL DEFAULT 'running',
            `update_error` TEXT NULL,
            `update_backup` VARCHAR(255) NOT NULL DEFAULT '',
            `update_files` INT NOT NULL DEFAULT 0,
            `update_tstamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `update_user` INT NOT NULL DEFAULT 0,
            PRIMARY KEY (`update_id`)
        ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        if (!_dbQuery($create, 'CREATE')) {
            throw new RuntimeException('Could not create update log table.');
        }
    }

    private function insertLogRow(array $release): void
    {
        $table = DB_PREPEND . 'phpwcms_update_log';
        $sql = 'INSERT INTO ' . $table . ' (update_from, update_to, update_tag, update_status, update_backup, update_files, update_user) VALUES ('
            . _dbEscape(PHPWCMS_VERSION) . ', '
            . _dbEscape($release['version']) . ', '
            . _dbEscape($release['tag']) . ', '
            . '\'running\', '
            . _dbEscape($this->backupDir) . ', '
            . '0, '
            . (int)$this->userId . ')';
        $res = _dbQuery($sql, 'INSERT');
        $this->updateId = (int)($res['INSERT_ID'] ?? 0);
        if ($this->updateId <= 0) {
            throw new RuntimeException('Could not create update log row.');
        }
    }

    private function finishLogRow(string $status, int $files, string $error = ''): void
    {
        $table = DB_PREPEND . 'phpwcms_update_log';
        $sql = 'UPDATE ' . $table . ' SET update_status = ' . _dbEscape($status) . ', update_files = ' . (int)$files
            . ', update_error = ' . ($error === '' ? 'NULL' : _dbEscape($error))
            . ' WHERE update_id = ' . (int)$this->updateId;
        $res = _dbQuery($sql, 'UPDATE');
        if (empty($res['AFFECTED_ROWS'])) {
            $this->log('Log row ' . $this->updateId . ' not updated to ' . $status);
        }
    }

    /**
     * Build a synthetic release array from a local release zip (seam for
     * offline installs / the e2e harness). Version is read from the zip's
     * staged revision.php. Returns false if the zip is unreadable.
     */
    private function releaseFromZip(string $zipPath): array|false
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return false;
        }
        $revisionSource = $zip->getFromName('include/inc_lib/revision/revision.php');
        $zip->close();
        if ($revisionSource === false || !preg_match('/(?:const|define\s*\(\s*[\'"])\s*PHPWCMS_VERSION[\'"]?\s*[=,]\s*[\'"]([^\'"]+)[\'"]/i', $revisionSource, $m)) {
            return false;
        }
        $version = $m[1];
        return [
            'tag' => 'v' . $version,
            'version' => $version,
            'name' => 'Local release',
            'notes' => '',
            'zip' => $zipPath,
            'date' => '',
            'newer' => self::isNewer($version),
        ];
    }

    private function verifyZip(string $zipPath, array $release): array
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new RuntimeException('Could not open release zip.');
        }
        $manifestContent = $zip->getFromName('.update-manifest');
        if ($manifestContent === false) {
            $zip->close();
            throw new RuntimeException('Release zip missing .update-manifest.');
        }
        $manifest = self::parseManifest($manifestContent);
        if ($manifest === []) {
            $zip->close();
            throw new RuntimeException('Release manifest is empty.');
        }
        $revisionSource = $zip->getFromName('include/inc_lib/revision/revision.php');
        $zip->close();
        if ($revisionSource === false || !preg_match('/(?:const|define\s*\(\s*[\'"])\s*PHPWCMS_VERSION[\'"]?\s*[=,]\s*[\'"]([^\'"]+)[\'"]/i', $revisionSource, $m)) {
            throw new RuntimeException('Release zip missing revision version.');
        }
        if ($m[1] !== $release['version']) {
            throw new RuntimeException('Release version mismatch: zip has ' . $m[1] . ', release says ' . $release['version'] . '.');
        }
        return $manifest;
    }

    private function extract(string $zipPath, array $manifest): string
    {
        $stageDir = $this->workDir . 'stage/';
        if (is_dir($stageDir)) {
            $this->rrmdir($stageDir);
        }
        if (!@mkdir($stageDir, 0775, true) && !is_dir($stageDir)) {
            throw new RuntimeException('Could not create staging dir.');
        }
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new RuntimeException('Could not open release zip for extraction.');
        }
        $zip->extractTo($stageDir);
        $zip->close();
        foreach ($manifest as $rel => $hash) {
            $staged = $stageDir . $rel;
            if (!is_file($staged)) {
                throw new RuntimeException('Staged file missing: ' . $rel);
            }
            if (hash_file('sha256', $staged) !== $hash) {
                throw new RuntimeException('Checksum mismatch for ' . $rel);
            }
        }
        return $stageDir;
    }

    private function buildPlan(array $manifest, string $stageDir): array
    {
        $plan = ['added' => [], 'modified' => [], 'deleted' => []];
        $oldManifest = [];
        $oldManifestPath = PHPWCMS_ROOT . '/.update-manifest';
        if (is_file($oldManifestPath)) {
            $oldManifest = self::parseManifest((string)file_get_contents($oldManifestPath));
        }
        foreach ($manifest as $rel => $hash) {
            if (self::isSkipped($rel)) {
                continue;
            }
            $target = PHPWCMS_ROOT . '/' . $rel;
            if (!is_file($target)) {
                $plan['added'][] = $rel;
            } elseif (hash_file('sha256', $target) !== $hash) {
                $plan['modified'][] = $rel;
            }
        }
        foreach ($oldManifest as $rel => $hash) {
            if (!array_key_exists($rel, $manifest) && !self::isSkipped($rel)) {
                $plan['deleted'][] = $rel;
            }
        }
        return $plan;
    }

    private function apply(array $plan, string $stageDir, array $manifest): void
    {
        foreach (array_merge($plan['added'], $plan['modified']) as $rel) {
            $target = PHPWCMS_ROOT . '/' . $rel;
            $dir = dirname($target);
            if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
                throw new RuntimeException('Failed writing ' . $rel);
            }
            if (!@copy($stageDir . $rel, $target)) {
                throw new RuntimeException('Failed writing ' . $rel);
            }
        }
        foreach ($plan['deleted'] as $rel) {
            $target = PHPWCMS_ROOT . '/' . $rel;
            if (is_file($target)) {
                @unlink($target);
            }
        }
        $lines = [];
        foreach ($manifest as $rel => $hash) {
            $lines[] = $hash . '  ' . $rel;
        }
        @file_put_contents(PHPWCMS_ROOT . '/.update-manifest', implode(LF, $lines) . LF);
    }

    private function setMaintenance(bool $on): void
    {
        $flag = PHPWCMS_TEMP . self::MAINTENANCE_FLAG;
        if ($on) {
            @touch($flag);
        } else {
            @unlink($flag);
        }
    }

    private function rrmdir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            if ($item->isDir() && !$item->isLink()) {
                @rmdir($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }
        @rmdir($dir);
    }

    private static function parseManifest(string $content): array
    {
        $manifest = [];
        foreach (preg_split('/\r?\n/', $content) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            $parts = preg_split('/\s+/', $line, 2);
            if (count($parts) === 2 && $parts[1] !== '') {
                $rel = $parts[1];
                // zip-slip defense: reject traversal ('..' segments), absolute
                // paths (leading '/') and backslash separators so a hostile
                // manifest cannot escape the docroot via extract/apply/backup.
                if (str_starts_with($rel, '/')
                    || str_contains($rel, '\\')
                    || in_array('..', explode('/', $rel), true)) {
                    continue;
                }
                if (str_starts_with($rel, './')) {
                    $rel = substr($rel, 2);
                }
                $manifest[$rel] = $parts[0];
            }
        }
        return $manifest;
    }

    /**
     * Target DB revision for the inline revision run after apply. Derived from
     * the NEWLY applied revision.php (the bootstrap-time PHPWCMS_REVISION
     * constant still holds the pre-update value). Falls back to the constant
     * only if the file is missing or the pattern does not match.
     */
    private function targetRevision(): int
    {
        $revisionFile = PHPWCMS_ROOT . '/include/inc_lib/revision/revision.php';
        $source = is_file($revisionFile) ? (string)@file_get_contents($revisionFile) : '';
        if ($source !== '' && preg_match('/(?:const|define\s*\(\s*[\'"])\s*PHPWCMS_REVISION[\'"]?\s*[=,]\s*[\'"]?(\d+)[\'"]?/i', $source, $m)) {
            return (int)$m[1];
        }
        return (int)PHPWCMS_REVISION;
    }
}
