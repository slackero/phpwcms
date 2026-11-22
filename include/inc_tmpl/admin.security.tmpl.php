<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 **/

// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}

require_once PHPWCMS_ROOT . '/include/inc_lib/security-check.inc.php';

$security_storage_root = defined('PHPWCMS_STORAGE_ROOT') ? (string)PHPWCMS_STORAGE_ROOT : '';
$security_notice = '';

if (!empty($_POST['fix_security']) && validate_csrf_tokens()) {
    $security_ids = array_map('strval', (array)($_POST['fix_ids'] ?? array()));
    $security_result = phpwcms_security_fix(PHPWCMS_ROOT, $security_storage_root, $security_ids);
    $security_fixed = count(array_filter($security_result));
    headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=20&secfixed=' . $security_fixed);
}

$security_get_token = (string)($_GET['csrftoken'] ?? '');
if (isset($_GET['secfixed']) && $security_get_token !== '' && hash_equals((string)get_token_get_value(), $security_get_token)) {
    $security_notice = (int)$_GET['secfixed'] . ' ' . ($BL['be_security_fixed'] ?? 'finding(s) repaired.');
}

$security_checks = phpwcms_security_check(PHPWCMS_ROOT, $security_storage_root);

$security_text = static function (array $row, string $kind) use ($BL): string {
    $key = (string)($row[$kind . '_key'] ?? '');
    if ($key === '' || !isset($BL[$key])) {
        return html($row[$kind] ?? '');
    }
    $args = array_map('html', (array)($row[$kind . '_args'] ?? array()));

    return vsprintf(html($BL[$key]), $args);
};
$security_fixable = array();
foreach ($security_checks as $security_row) {
    if (!empty($security_row['fixable'])) {
        $security_fixable[] = $security_row['id'];
    }
}

$security_badge = array(
    'ok' => 'text-bg-success',
    'warn' => 'text-bg-warning',
    'fail' => 'text-bg-danger',
);
$security_status_label = array(
    'ok' => $BL['be_security_ok'] ?? 'OK',
    'warn' => $BL['be_security_warn'] ?? 'Warning',
    'fail' => $BL['be_security_fail'] ?? 'Problem',
);
?>

<div class="row align-items-center mb-3">
  <div class="col">
    <h1 class="h4 text-primary fw-normal m-0"><?php echo html($BL['be_security_check'] ?? 'Security Check'); ?></h1>
  </div>
</div>

<?php
// the sentence stays translatable; the template owns the markup for the paths
$security_intro = sprintf(
    html($BL['be_security_intro'] ?? 'Verifies the private storage tree, the legacy content directories and the per-directory deny files. The updater skips %1$s, %2$s and %3$s, so an update can leave these files missing or stale.'),
    '<code>content/</code>',
    '<code>upload/</code>',
    '<code>filearchive/</code>'
);
?>
<p class="text-muted"><?php echo $security_intro; ?></p>

<?php if ($security_notice !== ''): ?>
  <div class="alert alert-success"><i class="fa-solid fa-check-circle"></i> <?php echo html($security_notice); ?></div>
<?php endif; ?>

<div class="card mb-4 border">
  <div class="card-header bg-light fw-bold"><?php echo html($BL['be_security_results'] ?? 'Findings'); ?></div>
  <div class="list-group list-group-flush">
    <?php foreach ($security_checks as $security_row): ?>
      <div class="list-group-item d-flex justify-content-between align-items-start gap-3 py-2">
        <div>
          <strong><?php echo $security_text($security_row, 'label'); ?></strong>
          <div class="text-muted small"><?php echo $security_text($security_row, 'detail'); ?></div>
        </div>
        <span class="badge <?php echo $security_badge[$security_row['status']] ?? 'text-bg-secondary'; ?> rounded-pill">
          <?php echo html($security_status_label[$security_row['status']] ?? $security_row['status']); ?>
        </span>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php if ($security_fixable !== array()): ?>
  <form method="post" action="phpwcms.php?do=admin&amp;p=20">
    <?php foreach ($security_fixable as $security_id): ?>
      <input type="hidden" name="fix_ids[]" value="<?php echo html($security_id); ?>">
    <?php endforeach; ?>
    <button type="submit" name="fix_security" value="1" class="btn btn-primary">
      <i class="fa-solid fa-wrench"></i> <?php echo html($BL['be_security_repair'] ?? 'Repair safe findings'); ?>
    </button>
    <div class="form-text">
      <?php echo html($BL['be_security_repair_hint'] ?? 'Creates missing private directories and deny files. Never deletes or moves user data; findings that need manual work stay listed.'); ?>
    </div>
  </form>
<?php endif; ?>
