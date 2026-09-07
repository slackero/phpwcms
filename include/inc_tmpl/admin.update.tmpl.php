<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}

require_once PHPWCMS_ROOT . '/include/inc_lib/update/update.php';

$update = new phpwcms_update((int)$_SESSION['wcs_user_id']);
$updateResult = null;
$rollbackResult = null;

// A new update must not run on a half-migrated schema — block it while DB
// revisions are pending or failed. History/rollback stay available as the
// recovery path.
$installedBaseVersion = preg_replace('/[-+].*$/', '', PHPWCMS_VERSION);
$versionUnsupported = version_compare($installedBaseVersion, phpwcms_update::MIN_SUPPORTED_VERSION, '<');
$revisionsPending = phpwcms_revision_check_temp(PHPWCMS_REVISION) !== true;

// POST actions: sysadmin + CSRF + POST only
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && !empty($_SESSION['wcs_user_admin'])
    && isset($_POST['csrftoken'])
    && hash_equals((string)get_token_get_value(), (string)$_POST['csrftoken'])) {

    if (isset($_POST['update_run']) && !empty($_POST['update_tag'])) {
        $tagVersion = phpwcms_update_normalize_tag((string)$_POST['update_tag']);
        if ($versionUnsupported || version_compare($tagVersion, phpwcms_update::MIN_SUPPORTED_VERSION, '<=')) {
            $updateResult = ['success' => false, 'error' => $BL['be_update_version_unsupported'] ?? 'Automatic update is only supported for phpwcms version > 2.0.0.'];
        } elseif ($revisionsPending) {
            $updateResult = ['success' => false, 'error' => $BL['be_update_revision_pending'] ?? 'Database migrations are pending or failed — run them (backend login) before updating.'];
        } else {
            $updateResult = $update->run((string)$_POST['update_tag']);
            unset($_SESSION['phpwcms_update_check']);
        }
    } elseif (isset($_POST['update_rollback']) && !empty($_POST['update_rollback'])) {
        $rollbackResult = $update->rollback((int)$_POST['update_rollback']);
        unset($_SESSION['phpwcms_update_check']);
    } elseif (isset($_POST['update_clear_maintenance'])) {
        $update->clearMaintenance();
    }
}

// Session cache for update check (invalidate on explicit check or post-action)
if (isset($_GET['check']) || !isset($_SESSION['phpwcms_update_check']) || !is_array($_SESSION['phpwcms_update_check'])) {
    $updateCheck = $update->check();
    $_SESSION['phpwcms_update_check'] = $updateCheck;
} else {
    $updateCheck = $_SESSION['phpwcms_update_check'];
}

$history = [];
if (_dbTableExists('update_log')) {
    $history = _dbQuery('SELECT * FROM `' . DB_PREPEND . 'update_log` ORDER BY update_id DESC');
    if (!is_array($history)) {
        $history = [];
    }
}

/**
 * Render release notes with limited markdown only.
 * Input is html()-escaped first, so any raw HTML is neutralized before
 * the small set of markdown conversions is applied.
 */
function be_update_markdown(string $text): string
{
    // inline code
    $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
    // bold
    $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text);
    // links [text](url) — only emit an anchor for http(s) URLs, otherwise text only
    $text = preg_replace_callback('/\[([^\]]+)\]\(([^)]+)\)/', function (array $m): string {
        $href = trim($m[2]);
        if (preg_match('/^https?:\/\//i', $href)) {
            return '<a href="' . $href . '" target="_blank" rel="noopener">' . $m[1] . '</a>';
        }
        return $m[1];
    }, $text);
    // lists: lines starting with "- " or "* "
    $lines = preg_split('/\r?\n/', $text);
    $inList = false;
    $out = '';
    foreach ($lines as $line) {
        if (preg_match('/^\s*[-*]\s+(.*)$/', $line, $m)) {
            if (!$inList) {
                $out .= '<ul>';
                $inList = true;
            }
            $out .= '<li>' . $m[1] . '</li>';
        } else {
            if ($inList) {
                $out .= '</ul>';
                $inList = false;
            }
            $out .= ($line === '' ? '<br>' : $line . '<br>');
        }
    }
    if ($inList) {
        $out .= '</ul>';
    }
    return $out;
}

$currentVersion = PHPWCMS_VERSION . ' / ' . PHPWCMS_RELEASE_DATE . ' (r' . PHPWCMS_REVISION . ')';
$maintenanceActive = phpwcms_update::maintenanceActive();
?>

<div class="container-fluid p-0">
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-rotate"></i> <?php echo html($BL['be_subnav_admin_update'] ?? 'System Update'); ?></h1>
        <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=18&amp;check=1" class="btn btn-sm btn-blue"><i class="fa-solid fa-magnifying-glass"></i> <?php echo html($BL['be_update_check'] ?? 'Check for update'); ?></a>
      </div>

      <?php if ($maintenanceActive): ?>
        <div class="alert alert-warning">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <?php echo html($BL['be_update_clear_maintenance'] ?? 'Clear maintenance mode'); ?>
          <form method="post" action="phpwcms.php?do=admin&amp;p=18" class="d-inline ms-2">
            <input type="hidden" name="csrftoken" value="<?php echo html(get_token_get_value()); ?>">
            <button type="submit" name="update_clear_maintenance" value="1" class="btn btn-sm btn-warning"><i class="fa-solid fa-power-off"></i> <?php echo html($BL['be_update_clear_maintenance'] ?? 'Clear maintenance mode'); ?></button>
          </form>
        </div>
      <?php endif; ?>

      <?php if (is_array($updateResult)): ?>
        <?php if (!empty($updateResult['success'])): ?>
          <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <?php
              $updatedTo = !empty($_POST['update_tag']) ? ltrim((string)$_POST['update_tag'], 'v') : ($updateCheck['version'] ?? '');
              echo html(sprintf($BL['be_update_success'] ?? 'Update to %s completed successfully.', $updatedTo));
            ?>
            <?php if (!empty($updateResult['backup'])): ?>
              <div class="small mt-1"><?php echo html($BL['be_update_backup_path'] ?? 'Backup stored at'); ?>: <code><?php echo html($updateResult['backup']); ?></code></div>
            <?php endif; ?>
            <?php if (!empty($updateResult['files'])): ?>
              <div class="small"><?php echo html($BL['be_update_changed_files'] ?? 'Changed files'); ?>: <?php echo (int)$updateResult['files']; ?></div>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <div class="alert alert-danger">
            <i class="fa-solid fa-circle-xmark"></i>
            <strong><?php echo html($BL['be_update_failed'] ?? 'Update failed'); ?>:</strong>
            <?php echo html($updateResult['error'] ?? ''); ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <?php if (is_array($rollbackResult)): ?>
        <?php if (!empty($rollbackResult['success'])): ?>
          <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <?php echo html($BL['be_update_rolled_back'] ?? 'Previous version restored.'); ?>
          </div>
        <?php else: ?>
          <div class="alert alert-warning">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <?php echo html($rollbackResult['error'] ?? ''); ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <div class="card mb-3">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6 class="text-muted text-uppercase small mb-1"><?php echo html($BL['be_update_current'] ?? 'Installed version'); ?></h6>
              <div class="fs-5 fw-semibold"><?php echo html($currentVersion); ?></div>
            </div>
            <div class="col-md-6">
              <h6 class="text-muted text-uppercase small mb-1"><?php echo html($BL['be_update_available'] ?? 'Available version'); ?></h6>
              <?php if ($updateCheck === false): ?>
                <div class="alert alert-warning py-2 mb-0"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo html($BL['be_update_fetch_error'] ?? 'Could not fetch update information from GitHub. The latest release may have no zip asset, or the API is unreachable.'); ?></div>
              <?php elseif (!empty($updateCheck['newer'])): ?>
                <div class="fs-5 fw-semibold text-success"><?php echo html($updateCheck['version']); ?></div>
                <div class="small text-muted"><?php echo html($updateCheck['tag']); ?> &middot; <?php echo html($updateCheck['date']); ?></div>
              <?php else: ?>
                <div class="text-success"><i class="fa-solid fa-circle-check"></i> <?php echo html($BL['be_update_uptodate'] ?? (get_brand_name() . ' is up to date.')); ?></div>
              <?php endif; ?>
            </div>
          </div>

          <?php if ($updateCheck !== false && !empty($updateCheck['newer'])): ?>
            <hr>
            <h6 class="text-muted text-uppercase small mb-2"><?php echo html(apply_brand_replacements($updateCheck['name'])); ?></h6>
            <div class="mb-3">
              <?php echo be_update_markdown(html(apply_brand_replacements($updateCheck['notes']))); ?>
            </div>
            <?php if ($versionUnsupported): ?>
              <div class="alert alert-danger py-2 mb-0"><i class="fa-solid fa-circle-xmark"></i> <?php echo html($BL['be_update_version_unsupported'] ?? 'Automatic update is only supported for phpwcms version > 2.0.0.'); ?></div>
            <?php elseif ($revisionsPending): ?>
              <div class="alert alert-warning py-2 mb-0"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo html($BL['be_update_revision_pending'] ?? 'Database migrations are pending or failed — run them (backend login) before updating.'); ?></div>
            <?php else: ?>
            <form method="post" action="phpwcms.php?do=admin&amp;p=18" onsubmit="return confirm('<?php echo js_singlequote($BL['be_update_confirm'] ?? 'The system creates a database backup, then replaces its own files. Continue?'); ?>');">
              <input type="hidden" name="csrftoken" value="<?php echo html(get_token_get_value()); ?>">
              <input type="hidden" name="update_tag" value="<?php echo html($updateCheck['tag']); ?>">
              <button type="submit" name="update_run" value="1" class="btn btn-blue"><i class="fa-solid fa-download"></i> <?php echo $BL['be_update_run'] ?? 'Backup &amp; update now'; ?></button>
            </form>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <i class="fa-solid fa-clock-rotate-left"></i> <?php echo html($BL['be_update_history'] ?? 'Update history'); ?>
        </div>
        <div class="card-body p-0">
          <?php if (empty($history)): ?>
            <div class="p-3 text-muted"><?php echo html($BL['be_update_no_history'] ?? 'No updates recorded yet.'); ?></div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th class="ps-3"><?php echo html($BL['be_cnt_date'] ?? 'Date'); ?></th>
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Files</th>
                    <th class="text-end pe-3"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($history as $row): ?>
                    <?php
                    $status = (string)($row['update_status'] ?? '');
                    $badge = 'secondary';
                    if ($status === 'success') {
                        $badge = 'success';
                    } elseif ($status === 'failed') {
                        $badge = 'danger';
                    } elseif ($status === 'running') {
                        $badge = 'warning';
                    } elseif ($status === 'rolled_back') {
                        $badge = 'secondary';
                    }
                    $backupDir = (string)($row['update_backup'] ?? '');
                    // Rollback is offered whenever a file backup exists, even for a
                    // stuck 'running' row (crash/OOM mid-run) — the files/ mirror is
                    // the source of truth, not the status column.
                    $canRollback = $backupDir !== ''
                        && is_dir($backupDir . 'files/');
                    ?>
                    <tr>
                      <td class="ps-3 text-nowrap"><?php echo html($row['update_tstamp'] ?? ''); ?></td>
                      <td class="text-nowrap"><?php echo html($row['update_from'] ?? ''); ?></td>
                      <td class="text-nowrap"><?php echo html($row['update_to'] ?? ''); ?></td>
                      <td><span class="badge text-bg-<?php echo $badge; ?>"><?php echo html($status); ?></span></td>
                      <td class="text-end pe-3"><?php echo (int)($row['update_files'] ?? 0); ?></td>
                      <td class="text-end pe-3">
                        <?php if ($canRollback): ?>
                          <form method="post" action="phpwcms.php?do=admin&amp;p=18" class="d-inline" onsubmit="return confirm('<?php echo js_singlequote($BL['be_update_rollback'] ?? 'Restore previous version'); ?>?');">
                            <input type="hidden" name="csrftoken" value="<?php echo html(get_token_get_value()); ?>">
                            <input type="hidden" name="update_rollback" value="<?php echo (int)$row['update_id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-rotate-left"></i> <?php echo html($BL['be_update_rollback'] ?? 'Restore previous version'); ?></button>
                          </form>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
