<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------
?>

<h1 class="text-center text-sm-start"><?php echo $BL['be_alias'] ?></h1>
<?php
if (!empty($_SESSION['wcs_user_admin'])) {

    $recalc_key = 'alias_recalc_result_' . (int)$_SESSION['wcs_user_id'];
    $recalc         = $_SESSION[$recalc_key] ?? null;
    $recalc_expired = false;
    if (!empty($recalc) && !empty($recalc['pending']) && time() - (int)($recalc['time'] ?? 0) > 900) {
        unset($_SESSION[$recalc_key]); // stale confirmation
        $recalc         = null;
        $recalc_expired = true;
    }
    if (!empty($recalc) && empty($recalc['pending'])) {
        unset($_SESSION[$recalc_key]);
    } elseif (!empty($recalc)) {
        // Auto-open only once, directly after the plan was queued.
        $_SESSION[$recalc_key]['auto_open'] = false;
    }
    ?>
<div class="card mb-4">
  <div class="card-header"><h2><?php echo html($BL['be_alias_recalc']); ?></h2></div>
  <div class="card-body">
    <p class="text-muted"><?php echo html($BL['be_alias_recalc_desc']); ?></p>
<?php if (!empty($recalc_expired)) { ?>
    <p class="text-warning"><?php echo html($BL['be_alias_recalc_expired']); ?></p>
<?php } ?>
    <form action="include/inc_act/act_aliasrecalc.php" method="post" class="row g-2">
      <div class="col-12">
        <select name="recalc_mode" id="recalc_mode" class="form-select form-select-sm w-auto" aria-label="<?php echo html($BL['be_alias']); ?>">
          <option value="structure"><?php echo html($BL['be_alias_recalc_structure']); ?></option>
          <option value="title"><?php echo html($BL['be_alias_recalc_titles']); ?></option>
        </select>
      </div>
      <div class="col-12">
        <div class="form-check mb-0">
          <input type="checkbox" name="recalc_redirect" id="recalc_redirect" value="1" class="form-check-input" checked="checked" />
          <label for="recalc_redirect" class="form-check-label"><?php echo html($BL['be_alias_recalc_redirect']); ?></label>
        </div>
      </div>
      <div class="col-12">
        <?php // Disabled only for the blocked response; a reload retries with fresh data. ?>
        <button type="submit" id="recalc_submit" class="btn btn-sm btn-blue"<?php echo !empty($recalc['error']) ? ' disabled="disabled"' : ''; ?>><i class="fa-solid fa-rotate"></i> <?php echo html($BL['be_alias_recalc_run']); ?></button>
      </div>
    </form>
  </div>
</div>
<?php if (!empty($recalc)) { ?>
<div class="modal fade" id="aliasRecalcModal" tabindex="-1" aria-labelledby="aliasRecalcModalLabel" aria-hidden="true"<?php echo !empty($recalc['auto_open']) ? ' data-auto-open="1"' : ''; ?>>
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 95vw;">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title fs-5" id="aliasRecalcModalLabel"><?php echo html($BL['be_alias_recalc']); ?><?php if (!empty($recalc['row_count'])) { ?> (<?php echo (int)$recalc['row_count']; ?>)<?php } ?></h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
<?php if (empty($recalc['pending'])) { ?>
        <p class="mb-2"><?php echo html(sprintf($BL['be_alias_recalc_done'], (int)($recalc['row_count'] ?? count($recalc['rows'])))); ?></p>
<?php } ?>
<?php if (!empty($recalc['error'])) { ?>
        <p class="mb-0 text-danger"><?php echo html($recalc['error']); ?></p>
<?php } elseif (empty($recalc['rows'])) { ?>
        <p class="mb-0 text-muted"><?php echo html($BL['be_alias_recalc_none']); ?></p>
<?php } else { ?>
        <div class="table-responsive" style="max-height: 60vh;">
          <table class="table table-sm table-hover table-valign-middle mb-0">
            <thead>
              <tr>
                <th><?php echo html($BL['be_alias_recalc_type']); ?></th>
                <th><?php echo html($BL['be_alias_recalc_name']); ?></th>
                <th><?php echo html($BL['be_alias']); ?></th>
                <th><?php echo html($BL['be_alias_recalc_new']); ?></th>
              </tr>
            </thead>
            <tbody>
<?php foreach ($recalc['rows'] as $row) { ?>
              <tr>
                <td><?php echo html($row['type'] === 'category' ? $BL['be_alias_recalc_category'] : $BL['be_alias_recalc_article']); ?></td>
                <td><?php echo html($row['name']); ?></td>
                <td><code><?php echo html($row['old'] === '' ? '-' : $row['old']); ?></code></td>
                <td><code><?php echo html($row['new'] === '' ? '-' : $row['new']); ?></code></td>
              </tr>
<?php } ?>
            </tbody>
          </table>
        </div>
<?php } ?>
      </div>
<?php if (!empty($recalc['pending']) && empty($recalc['error'])) { ?>
      <div class="modal-footer flex-column align-items-start justify-content-start text-start">
        <form action="include/inc_act/act_aliasrecalc.php" method="post" class="d-flex flex-column align-items-start gap-2">
          <p class="mb-0 text-danger fw-bold"><?php echo html($BL['be_alias_recalc_confirm']); ?></p>
          <div class="form-check mb-0">
            <input type="checkbox" name="recalc_redirect" id="recalc_redirect_modal" value="1" class="form-check-input"<?php echo !empty($recalc['redirects']) ? ' checked="checked"' : ''; ?> />
            <label for="recalc_redirect_modal" class="form-check-label"><?php echo html($BL['be_alias_recalc_redirect']); ?></label>
          </div>
          <input type="hidden" name="recalc_mode" value="<?php echo html($recalc['mode']); ?>" />
          <input type="hidden" name="recalc_token" value="<?php echo html($recalc['token'] ?? ''); ?>" />
          <div class="d-flex gap-2">
            <button type="submit" name="recalc_cancel" value="1" class="btn btn-sm btn-secondary"><?php echo html($BL['modal_cancel'] ?? 'Cancel'); ?></button>
            <button type="submit" class="btn btn-sm btn-danger" data-confirm="<?php echo html($BL['be_alias_recalc_confirm'], true); ?>" data-confirm-type="danger" data-confirm-action="<?php echo html($BL['modal_ok'] ?? 'OK', true); ?>"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo html($BL['be_alias_recalc_run']); ?></button>
          </div>
        </form>
      </div>
<?php } elseif (!empty($recalc['redirect_error'])) { ?>
      <div class="modal-footer">
        <p class="mb-0 text-warning"><?php echo html($BL['be_alias_recalc_redirect'] . ': ' . $BL['be_error_while_save']); ?></p>
      </div>
<?php } ?>
    </div>
  </div>
</div>
<?php } ?>
<?php } ?>
<div class="card mb-2">
  <div class="card-header"><h2><?php echo $BL['be_article_urlalias'] ?> <?php echo $BL['be_ftptakeover_active'] ?></h2></div>
  <div class="card-body">

<?php
// now retrieve all structur items
$sql  = "SELECT *, DATE_FORMAT(acat_tstamp, '%Y-%m-%d') AS acat_timestamp ";
$sql .= "FROM ".DB_PREPEND."articlecat WHERE ";
$sql .= "acat_public=1 AND acat_aktiv=1 AND acat_trash=0 ";
$sql .= "ORDER BY acat_alias";

$result = _dbQuery($sql);
if(isset($result[0]['acat_id'])) {
  $x = 0;
  echo '<form action="" method="post" name="editstructur">';
   echo '<table class="table table-sm table-hover table-valign-middle mb-0">';

  foreach($result as $data) {

    // now add article URL
    echo '<tr>';
    echo '<td>';
    echo '<span class="badge '.(empty($data["acat_alias"]) ? "bg-danger" : "bg-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_alias'].'">A</span>';
    echo '<span class="badge '.(empty($data["acat_pagetitle"]) ? "bg-danger" : "bg-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_pagetitle'].'">T</span>';

    $sql = "SELECT * FROM ".DB_PREPEND."template WHERE template_trash=0 AND template_id = " . $data["acat_template"];
    $content['current_template'] = _dbGet('template', '*', 'template_trash=0 AND template_id='._dbEscape($data["acat_template"]), '', '', 1);
    echo '<span class="ms-2">' . $content['current_template'][0]['template_name'] . ' | ' . '</span>';
    echo '<a href="phpwcms.php?do=articles&p=6&struct=0&cat='.$data["acat_id"].'">'.(empty($data["acat_alias"]) ? 'no alias' : html_specialchars($data["acat_alias"]) ).'</a>';
	  echo '</td >';

		echo '<td class="text-end">';
    echo '<a href="phpwcms.php?do=articles&p=6&struct=0&cat='.$data["acat_id"].'" class="btn btn-sm btn-blue float-end" title="'.$BL['be_func_struct_sedit'].'" data-bs-toggle="tooltip"><i class="fa-solid fa-pencil-alt"></i></a>';
    echo "</td>" . LF;
    echo '</tr>';
    $x++;
  }
	echo '</table>';
  echo '</form>';
}
?>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header"><h2><?php echo $BL['be_acat_urlalias'] ?> <?php echo $BL['be_ftptakeover_active'] ?></h2></div>
  <div class="card-body">
  <?php
// now retrieve all articles
$sql  = "SELECT *, DATE_FORMAT(article_tstamp, '%Y-%m-%d') AS article_timestamp ";
$sql .= "FROM ".DB_PREPEND."article WHERE ";
$sql .= "article_public=1 AND article_aktiv=1 AND article_deleted=0 ";
$sql .= "ORDER BY article_alias";

$result = _dbQuery($sql);
if(isset($result[0]['article_id'])) {
  $x = 0;
  echo '<form action="" method="post" name="editartikel">';
  echo '<table class="table table-sm table-hover table-valign-middle mb-0">';
  foreach($result as $data) {

    // now add article URL
    echo '<tr>';
    echo '<td>';
    echo '<span class="badge '.(empty($data["article_alias"]) ? "bg-danger" : "bg-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_acat_alias'].'">A</span>';
    echo '<span class="badge '.(empty($data["article_description"]) ? "bg-danger" : "bg-success").' me-1" data-bs-toggle="tooltip" title="'.$BL['be_article_description'].'">D</span>';
    echo '<a class="ms-2" href="phpwcms.php?do=articles&p=2&s=1&id='.$data["article_id"].'">'.(empty($data["article_alias"]) ? 'no alias' : html_specialchars($data["article_alias"]) ).'</a>';
    echo '</td >';

		echo '<td class="text-end">';
    echo '<a href="phpwcms.php?do=articles&p=2&s=1&id='.$data["article_id"].'" class="btn btn-sm btn-blue" title="'.$BL['be_func_struct_edit'].'" data-bs-toggle="tooltip"><i class="fa-solid fa-pencil-alt"></i></a>';
    echo "</td>" . LF;
    echo '</tr>';
    $x++;
  }
  echo '</table>';
  echo '</form>';
}
?>

  </div>
</div>

