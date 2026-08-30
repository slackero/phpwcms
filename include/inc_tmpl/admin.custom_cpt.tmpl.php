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
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

ensure_custom_cpt_table();

$action_msg = '';
$action_error = '';

// Handle Delete
if (isset($_GET['delete']) && (int)$_GET['delete'] > 0) {
    if (function_exists('validate_csrf_get_token') ? validate_csrf_get_token() : true) {
        delete_custom_contentpart((int)$_GET['delete']);
        headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=16');
    }
}

// Handle Toggle Active
if (isset($_GET['toggle']) && (int)$_GET['toggle'] > 0) {
    if (function_exists('validate_csrf_get_token') ? validate_csrf_get_token() : true) {
        $cpt_to_toggle = get_custom_contentpart_by_key((int)$_GET['toggle']);
        if ($cpt_to_toggle) {
            $new_status = empty($cpt_to_toggle['cpt_active']) ? 1 : 0;
            _dbUpdate('phpwcms_custom_cpt', ['cpt_active' => $new_status], 'cpt_id = ' . (int)$cpt_to_toggle['cpt_id']);
        }
        headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=16');
    }
}

// Handle Export JSON
if (isset($_GET['export']) && (int)$_GET['export'] > 0) {
    $cpt_export = get_custom_contentpart_by_key((int)$_GET['export']);
    if ($cpt_export) {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="custom_cpt_' . $cpt_export['cpt_key'] . '.json"');
        $cpt_export_utf8 = function_exists('custom_cpt_to_utf8') ? custom_cpt_to_utf8($cpt_export) : $cpt_export;
        echo json_encode($cpt_export_utf8, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Handle Save Form
if (!empty($_POST['save_custom_cpt'])) {
    $cpt_id    = isset($_POST['cpt_id']) ? (int)$_POST['cpt_id'] : 0;
    $cpt_title = clean_slweg($_POST['cpt_title'] ?? '');
    $cpt_key   = preg_replace('/[^-a-z0-9_]/i', '', strtolower(trim($_POST['cpt_key'] ?? '')));
    $cpt_mode  = in_array($_POST['cpt_mode'] ?? '', ['single', 'repeater']) ? $_POST['cpt_mode'] : 'repeater';
    $cpt_icon  = clean_slweg($_POST['cpt_icon'] ?? 'fa-cube');
    $cpt_desc  = slweg($_POST['cpt_desc'] ?? '');
    $cpt_active = empty($_POST['cpt_active']) ? 0 : 1;

    // Process fields JSON or POST array
    $fields = [];
    $reserved_key_found = '';
    if (!empty($_POST['field_key']) && is_array($_POST['field_key'])) {
        foreach ($_POST['field_key'] as $idx => $f_key) {
            $f_key = preg_replace('/[^-a-z0-9_]/i', '', strtolower(trim($f_key)));
            if (empty($f_key)) {
                continue;
            }

            if (is_custom_cpt_reserved_field_key($f_key)) {
                $reserved_key_found = $f_key;
                break;
            }

            $f_type   = clean_slweg($_POST['field_type'][$idx] ?? 'str');
            $f_legend = clean_slweg($_POST['field_legend'][$idx] ?? $f_key);
            $f_render = clean_slweg($_POST['field_render'][$idx] ?? '');
            $f_def    = clean_slweg($_POST['field_default'][$idx] ?? '');
            $f_ph     = clean_slweg($_POST['field_placeholder'][$idx] ?? '');
            $f_hr     = empty($_POST['field_hr'][$idx]) ? 0 : 1;

            $field_data = [
                'legend'      => $f_legend,
                'type'        => $f_type,
                'render'      => $f_render,
                'placeholder' => $f_ph,
                'default'     => $f_def,
                'hr'          => $f_hr
            ];

            // Parse values if select or option
            if (in_array($f_type, ['select', 'option']) && !empty($_POST['field_values'][$idx])) {
                $lines = explode(LF, str_replace("\r", '', $_POST['field_values'][$idx]));
                $vals = [];
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '') continue;
                    if (strpos($line, ':') !== false) {
                        list($vk, $vl) = explode(':', $line, 2);
                    } elseif (strpos($line, '=') !== false) {
                        list($vk, $vl) = explode('=', $line, 2);
                    } else {
                        $vk = $vl = $line;
                    }
                    $vals[trim($vk)] = trim($vl);
                }
                $field_data['values'] = $vals;
            }

            $fields[$f_key] = $field_data;
        }
    }

    if (empty($cpt_title)) {
        $action_error = $BL['be_admin_custom_cpt_err_title'] ?? 'Please provide a title for the custom content part.';
    } elseif (!empty($reserved_key_found)) {
        $action_error = sprintf($BL['be_admin_custom_cpt_err_reserved_field_key'] ?? 'The field key "%s" is a reserved standard tag name and cannot be used.', html(strtoupper($reserved_key_found)));
    } elseif (!empty($cpt_key) && custom_cpt_key_exists($cpt_key, $cpt_id)) {
        $action_error = sprintf($BL['be_admin_custom_cpt_err_duplicate_key'] ?? 'The identifier key "%s" is already in use by another Custom Content Part.', html($cpt_key));
    } else {
        $saved_id = save_custom_contentpart([
            'cpt_id'     => $cpt_id,
            'cpt_title'  => $cpt_title,
            'cpt_key'    => $cpt_key,
            'cpt_mode'   => $cpt_mode,
            'cpt_icon'   => $cpt_icon,
            'cpt_desc'   => $cpt_desc,
            'cpt_active' => $cpt_active,
            'fields'     => $fields
        ]);

        if ($saved_id) {
            $saved_cpt = get_custom_contentpart_by_key($saved_id);
            $real_key  = $saved_cpt['cpt_key'] ?? $cpt_key;

            // Automatically create / overwrite starter template file if requested
            if (!empty($_POST['create_template_file']) && !empty($real_key)) {
                $target_dir  = PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $real_key;
                $target_file = $target_dir . '/default.tmpl';
                $scaffold    = custom_field_generate_template_scaffold($saved_cpt);

                if (!is_dir($target_dir)) {
                    @mkdir($target_dir, 0777, true);
                }
                if (is_dir($target_dir)) {
                    write_textfile($target_file, $scaffold);
                }
            }

            set_status_message($BL['be_successfully_saved'] ?? 'Saved successfully.', 'success');

            if (!empty($_POST['save_only'])) {
                headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=16&edit=' . $saved_id);
            } else {
                headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=16');
            }
        } else {
            $action_error = $BL['be_admin_custom_cpt_err_save'] ?? 'An error occurred while saving the custom content part.';
        }
    }
}

// Handle Import JSON
if (!empty($_POST['import_custom_cpt'])) {
    $import_json = trim($_POST['import_json'] ?? '');
    if (!empty($_FILES['import_file']['tmp_name']) && is_uploaded_file($_FILES['import_file']['tmp_name'])) {
        $import_json = file_get_contents($_FILES['import_file']['tmp_name']);
    }

    if (!empty($import_json)) {
        $data = json_decode($import_json, true);
        if (is_array($data)) {
            $data = function_exists('custom_cpt_from_utf8') ? custom_cpt_from_utf8($data) : $data;
            if (!empty($data['cpt_title'])) {
                unset($data['cpt_id']); // create as new
                $saved_id = save_custom_contentpart($data);
                if ($saved_id) {
                    headerRedirect(PHPWCMS_URL . 'phpwcms.php?' . get_token_get_string() . '&do=admin&p=16');
                }
            }
        }
    }
    $action_error = $BL['be_admin_custom_cpt_err_import'] ?? 'Invalid JSON data provided for import.';
}

$edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : (isset($_GET['new']) ? -1 : 0);
$edit_cpt = ($edit_id > 0) ? get_custom_contentpart_by_key($edit_id) : null;
$all_cpts = get_custom_contentparts(false);
$sendbutton = ($edit_id > 0) ? ($BL['be_article_cnt_button1'] ?? 'Update') : ($BL['be_article_cnt_button2'] ?? 'Create');

$other_keys = array();
if (is_array($all_cpts)) {
    foreach ($all_cpts as $k => $c) {
        if (!isset($edit_cpt['cpt_key']) || strtolower($k) !== strtolower($edit_cpt['cpt_key'])) {
            $other_keys[] = strtolower($k);
        }
    }
}

$cpt_tpl_dir  = !empty($edit_cpt['cpt_key']) ? PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $edit_cpt['cpt_key'] : '';
$cpt_tpl_file = !empty($cpt_tpl_dir) ? $cpt_tpl_dir . '/default.tmpl' : '';
$cpt_tpl_root = !empty($edit_cpt['cpt_key']) ? PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $edit_cpt['cpt_key'] . '.tmpl' : '';
$tpl_exists   = (!empty($cpt_tpl_file) && file_exists($cpt_tpl_file)) || (!empty($cpt_tpl_root) && file_exists($cpt_tpl_root));
$existing_tpl_path = (!empty($cpt_tpl_file) && file_exists($cpt_tpl_file)) ? 'template/inc_cntpart/custom/' . $edit_cpt['cpt_key'] . '/default.tmpl' : ((!empty($cpt_tpl_root) && file_exists($cpt_tpl_root)) ? 'template/inc_cntpart/custom/' . $edit_cpt['cpt_key'] . '.tmpl' : '');
$usage_count = !empty($edit_cpt['cpt_key']) && function_exists('get_custom_cpt_usage_count') ? get_custom_cpt_usage_count($edit_cpt['cpt_key']) : 0;

?>

<div class="container-fluid p-0">
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0 text-gray-800"><i class="fa fa-cubes"></i> <?php echo html($BL['be_admin_custom_cpt'] ?? 'Custom Content Parts'); ?></h1>
        <div>
          <?php if ($edit_id !== 0): ?>
            <button type="submit" form="cptForm" name="save_only" value="1" class="btn btn-sm btn-blue"><i class="fa fa-rotate"></i> <?php echo html($sendbutton); ?></button>
            <button type="submit" form="cptForm" name="save_and_close" value="1" class="btn btn-sm btn-blue ms-1"><i class="fa fa-check"></i> <?php echo html($BL['be_article_cnt_button3'] ?? 'Save & close'); ?></button>
            <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=16" class="btn btn-sm btn-danger ms-3"><i class="fa fa-times"></i> <?php echo html($BL['be_newsletter_button_cancel'] ?? 'Cancel'); ?></a>
          <?php else: ?>
            <button type="button" class="btn btn-sm btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#importModal"><i class="fa fa-upload"></i> <?php echo html($BL['be_admin_custom_cpt_import'] ?? 'Import JSON'); ?></button>
            <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=16&amp;new=1" class="btn btn-sm btn-blue"><i class="fa fa-plus"></i> <?php echo html($BL['be_admin_custom_cpt_add'] ?? 'New Custom Content Part'); ?></a>
          <?php endif; ?>
        </div>
      </div>

      <?php if (!empty($action_error)): ?>
        <div class="alert alert-danger"><?php echo html($action_error); ?></div>
      <?php endif; ?>

      <?php if ($edit_id !== 0): // SHOW EDIT / CREATE FORM ?>

        <form action="phpwcms.php?do=admin&amp;p=16" method="post" id="cptForm">
          <input type="hidden" name="save_custom_cpt" value="1">
          <input type="hidden" name="cpt_id" value="<?php echo $edit_cpt['cpt_id'] ?? 0; ?>">

          <div class="card mb-4">
            <div class="card-header fw-bold">
              <?php echo ($edit_id > 0) ? html($BL['be_admin_custom_cpt_edit'] ?? 'Edit Custom Content Part') : html($BL['be_admin_custom_cpt_new'] ?? 'Create Custom Content Part'); ?>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 form-group">
                  <label for="cpt_title"><strong><?php echo html($BL['be_admin_custom_cpt_title'] ?? 'Title / Display Name'); ?> *</strong></label>
                  <input type="text" name="cpt_title" id="cpt_title" class="form-control" required value="<?php echo html($edit_cpt['cpt_title'] ?? ''); ?>" placeholder="e.g. Hero Banner">
                </div>
                <div class="col-md-4 form-group">
                  <label for="cpt_key">
                    <strong><?php echo html($BL['be_admin_custom_cpt_key'] ?? 'Identifier Key / Alias'); ?></strong>
                    <?php if ($usage_count > 0): ?>
                      <span class="badge text-bg-warning ms-1" data-bs-toggle="tooltip" title="<?php echo html(sprintf($BL['be_admin_custom_cpt_key_in_use'] ?? 'Key is locked because %d content part(s) are using it.', $usage_count)); ?>">
                        <i class="fa fa-lock"></i> <?php echo $usage_count; ?>
                      </span>
                    <?php endif; ?>
                  </label>
                  <input type="text" name="cpt_key" id="cpt_key" class="form-control font-monospace" value="<?php echo html($edit_cpt['cpt_key'] ?? ''); ?>" placeholder="e.g. hero_banner" pattern="[-a-zA-Z0-9_]*"<?php echo ($usage_count > 0) ? ' readonly' : ''; ?>>
                  <div class="invalid-feedback" id="cptKeyFeedback">
                    <?php echo html($BL['be_admin_custom_cpt_err_duplicate_key_simple'] ?? 'This identifier key is already in use by another Custom Content Part.'); ?>
                  </div>
                </div>
                <div class="col-md-2 form-group">
                  <label for="cpt_mode"><strong><?php echo html($BL['be_admin_custom_cpt_mode'] ?? 'Mode'); ?></strong></label>
                  <select name="cpt_mode" id="cpt_mode" class="form-select">
                    <option value="repeater"<?php echo (($edit_cpt['cpt_mode'] ?? '') === 'repeater') ? ' selected' : ''; ?>><?php echo html($BL['be_admin_custom_cpt_repeater'] ?? 'Repeater (List)'); ?></option>
                    <option value="single"<?php echo (($edit_cpt['cpt_mode'] ?? '') === 'single') ? ' selected' : ''; ?>><?php echo html($BL['be_admin_custom_cpt_single'] ?? 'Single Item'); ?></option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-8 form-group">
                  <label for="cpt_desc"><?php echo html($BL['be_admin_custom_cpt_desc'] ?? 'Description / Instructions for Editors'); ?></label>
                  <input type="text" name="cpt_desc" id="cpt_desc" class="form-control" value="<?php echo html($edit_cpt['cpt_desc'] ?? ''); ?>" placeholder="Short guidance for editors">
                </div>
                <div class="col-md-2 form-group">
                  <label for="cpt_icon"><?php echo html($BL['be_admin_custom_cpt_icon'] ?? 'Icon (FontAwesome)'); ?></label>
                  <input type="text" name="cpt_icon" id="cpt_icon" class="form-control" value="<?php echo html($edit_cpt['cpt_icon'] ?? 'fa-cube'); ?>">
                </div>
                <div class="col-md-2 form-group d-flex align-items-end pb-2">
                  <div class="form-check">
                    <input type="checkbox" name="cpt_active" id="cpt_active" value="1" class="form-check-input"<?php echo (!isset($edit_cpt['cpt_active']) || !empty($edit_cpt['cpt_active'])) ? ' checked' : ''; ?>>
                    <label class="form-check-label" for="cpt_active"><strong><?php echo html($BL['be_admin_struct_active'] ?? 'Active'); ?></strong></label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Field Builder Card -->
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span class="fw-bold"><i class="fa fa-list"></i> <?php echo html($BL['be_admin_custom_cpt_fields'] ?? 'Field Definitions'); ?></span>
              <button type="button" class="btn btn-sm btn-blue" onclick="addFieldRow();"><i class="fa fa-plus"></i> <?php echo html($BL['be_admin_custom_cpt_add_field'] ?? 'Add Field'); ?></button>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped table-hover table-valign-middle mb-0" id="fieldsTable">
                  <thead class="table-light">
                    <tr>
                      <th style="width: 25px;"></th>
                      <th style="width: 20%;"><?php echo html($BL['be_admin_custom_cpt_field_key'] ?? 'Key (Tag)'); ?></th>
                      <th style="width: 25%;"><?php echo html($BL['be_admin_custom_cpt_field_label'] ?? 'Label / Title'); ?></th>
                      <th style="width: 18%;"><?php echo html($BL['be_admin_custom_cpt_field_type'] ?? 'Type'); ?></th>
                      <th style="width: 25%;"><?php echo html($BL['be_admin_custom_cpt_field_config'] ?? 'Options / Config'); ?></th>
                      <th style="width: 50px;" class="text-center"><?php echo html($BL['be_admin_custom_cpt_actions'] ?? 'Action'); ?></th>
                    </tr>
                  </thead>
                  <tbody id="fieldsContainer">
                    <!-- Dynamic field rows inserted via JS -->
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer">
              <button type="button" class="btn btn-sm btn-blue" onclick="addFieldRow();"><i class="fa fa-plus"></i> <?php echo html($BL['be_admin_custom_cpt_add_field'] ?? 'Add Field'); ?></button>
            </div>
          </div>

          <!-- Template File Options & Actions Card -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="form-check">
                <input type="checkbox" name="create_template_file" id="create_template_file" value="1" class="form-check-input"<?php echo ($edit_id < 0 || !$tpl_exists) ? ' checked' : ''; ?>>
                <label class="form-check-label" for="create_template_file">
                  <?php echo $tpl_exists ? html($BL['be_admin_custom_cpt_overwrite_template'] ?? 'Overwrite template file:') : html($BL['be_admin_custom_cpt_create_template_help'] ?? 'Automatically create starter template file:'); ?>
                  <code>template/inc_cntpart/custom/<strong class="tpl-key-preview" id="tplKeyPreview"><?php echo html(!empty($edit_cpt['cpt_key']) ? $edit_cpt['cpt_key'] : '{KEY}'); ?></strong>/default.tmpl</code>
                </label>
              </div>
            </div>
            <div class="card-footer">
              <button name="save_only" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa fa-rotate"></i> <?php echo html($sendbutton); ?></button>
              <button name="save_and_close" type="submit" class="btn btn-sm btn-blue ms-1" value="1"><i class="fa fa-check"></i> <?php echo html($BL['be_article_cnt_button3'] ?? 'Save & close'); ?></button>
              <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=16" class="btn btn-sm btn-danger ms-3"><i class="fa fa-times"></i> <?php echo html($BL['be_newsletter_button_cancel'] ?? 'Cancel'); ?></a>
            </div>
          </div>

          <!-- Template Scaffold Helper -->
          <?php if ($edit_id !== 0): ?>
            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center fw-bold">
                <span><i class="fa fa-code"></i> <?php echo html($BL['be_admin_custom_cpt_scaffold'] ?? 'Starter Template Boilerplate'); ?></span>
                <div>
                  <?php if ($tpl_exists): ?>
                    <span class="badge text-bg-success me-2"><i class="fa fa-check"></i> <?php echo html($existing_tpl_path); ?></span>
                  <?php endif; ?>
                  <button type="button" class="btn btn-sm btn-secondary" onclick="copyTemplateScaffold(this);" title="<?php echo html($BL['be_admin_custom_cpt_copy'] ?? 'Copy to Clipboard'); ?>">
                    <i class="far fa-copy"></i> <?php echo html($BL['be_admin_custom_cpt_copy'] ?? 'Copy to Clipboard'); ?>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <p class="small text-muted mb-2">
                  <?php if ($tpl_exists): ?>
                    <?php echo html($BL['be_admin_custom_cpt_tpl_exists_notice'] ?? 'Template file already exists on disk:'); ?> <code><?php echo html($existing_tpl_path); ?></code>
                  <?php else: ?>
                    <?php echo html($BL['be_admin_custom_cpt_scaffold_help'] ?? 'Kopieren und als Vorlagendatei speichern unter:'); ?> <code>template/inc_cntpart/custom/<strong class="tpl-key-preview"><?php echo html(!empty($edit_cpt['cpt_key']) ? $edit_cpt['cpt_key'] : '{KEY}'); ?></strong>/default.tmpl</code>
                  <?php endif; ?>
                </p>
                <textarea id="templateScaffoldTextarea" class="form-control form-control-sm font-monospace" rows="12" style="font-family: SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; font-size: 12px; line-height: 1.45;" readonly onclick="this.select();"><?php echo html(custom_field_generate_template_scaffold($edit_cpt)); ?></textarea>
              </div>
            </div>
          <?php endif; ?>

        </form>

        <script>
        function copyTemplateScaffold(btn) {
            const textarea = document.getElementById('templateScaffoldTextarea');
            if (!textarea) return;
            textarea.select();
            const textToCopy = textarea.value;
            const successText = '<i class="fa fa-check text-success"></i> <?php echo html($BL['be_admin_custom_cpt_copied'] ?? 'Copied!'); ?>';
            const origHtml = btn.innerHTML;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(() => {
                    btn.innerHTML = successText;
                    setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
                }).catch(() => {
                    document.execCommand('copy');
                    btn.innerHTML = successText;
                    setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
                });
            } else {
                document.execCommand('copy');
                btn.innerHTML = successText;
                setTimeout(() => { btn.innerHTML = origHtml; }, 2000);
            }
        }
        const initialFields = <?php echo json_encode(function_exists('custom_cpt_to_utf8') ? custom_cpt_to_utf8($edit_cpt['fields'] ?? new stdClass()) : ($edit_cpt['fields'] ?? new stdClass()), JSON_UNESCAPED_UNICODE); ?>;

        function renderFieldRow(key, def, idx) {
            const types = [
                { val: 'str', label: 'Text (Single line)' },
                { val: 'textarea', label: 'Textarea' },
                { val: 'wysiwyg', label: 'WYSIWYG Editor' },
                { val: 'select', label: 'Dropdown (Select)' },
                { val: 'option', label: 'Radio (Option)' },
                { val: 'bool', label: 'Checkbox / Toggle' },
                { val: 'int', label: 'Integer Number' },
                { val: 'float', label: 'Decimal / Float' },
                { val: 'color', label: 'Color Picker' },
                { val: 'date', label: 'Date' },
                { val: 'file', label: 'File Download' },
                { val: 'image', label: 'Image Picker' },
                { val: 'url', label: 'URL / Link' }
            ];

            let typeOptions = '';
            types.forEach(t => {
                const sel = (def.type === t.val || (t.val === 'wysiwyg' && def.render === 'wysiwyg')) ? ' selected' : '';
                typeOptions += `<option value="${t.val}"${sel}>${t.label}</option>`;
            });

            let valuesText = '';
            if (def.values && typeof def.values === 'object') {
                for (let vk in def.values) {
                    valuesText += `${vk}:${def.values[vk]}\n`;
                }
            }

            return `
            <tr class="field-row">
              <td class="align-middle text-muted text-center drag-handle" style="cursor: grab; width: 30px; user-select: none;" title="<?php echo html($BL['be_admin_custom_cpt_drag_reorder'] ?? 'Drag to reorder'); ?>"><i class="fa fa-grip-vertical text-black-50"></i></td>
              <td>
                <input type="text" name="field_key[]" class="form-control form-control-sm font-monospace" value="${key || ''}" placeholder="key_name" required pattern="[-a-zA-Z0-9_]+" oninput="updateFieldTagPreview(this); validateFieldRowKey(this);">
                <div class="invalid-feedback field-key-feedback" style="display: none; font-size: 11px;"></div>
                <code class="small text-muted font-monospace mt-1 d-inline-block">{<span class="field-tag-preview">${(key ? key.toUpperCase().replace(/[^A-Z0-9_-]/g, '') : 'KEY')}</span>}</code>
              </td>
              <td>
                <input type="text" name="field_legend[]" class="form-control form-control-sm" value="${def.legend || def.label || ''}" placeholder="Label / Field Legend" required>
                <input type="text" name="field_placeholder[]" class="form-control form-control-sm mt-1" value="${def.placeholder || ''}" placeholder="Placeholder...">
              </td>
              <td>
                <select name="field_type[]" class="form-select form-select-sm" onchange="toggleFieldConfig(this);">
                  ${typeOptions}
                </select>
                <div class="form-check mt-2">
                  <input type="checkbox" name="field_hr[]" value="1" class="form-check-input" id="hr_${idx}" ${def.hr ? 'checked' : ''}>
                  <label class="form-check-label" for="hr_${idx}"><?php echo html($BL['be_admin_custom_cpt_divider'] ?? 'Divider'); ?> <code>&lt;hr&gt;</code></label>
                </div>
              </td>
              <td>
                <div class="field-opts-container">
                  <textarea name="field_values[]" rows="2" class="form-control form-control-sm values-area ${(['select', 'option'].includes(def.type)) ? '' : 'd-none'}" placeholder="<?php echo html($BL['be_admin_custom_cpt_opt_placeholder'] ?? 'key:Label (one per line)'); ?>">${valuesText}</textarea>
                  <input type="text" name="field_default[]" class="form-control form-control-sm" value="${def.default || ''}" placeholder="<?php echo html($BL['be_admin_custom_cpt_default_val'] ?? 'Default value...'); ?>">
                </div>
              </td>
              <td class="align-middle text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeFieldRow(this);" title="<?php echo html($BL['be_tt_delete'] ?? 'Delete'); ?>"><i class="far fa-trash-alt fa-fw"></i></button>
              </td>
            </tr>`;
        }

        function removeFieldRow(btn) {
            const row = btn.closest('tr.field-row');
            if (!row) return;
            const fKeyInput = row.querySelector('input[name="field_key[]"]');
            const fLegendInput = row.querySelector('input[name="field_legend[]"]');

            const key = fKeyInput ? fKeyInput.value.trim() : '';
            const legend = fLegendInput ? fLegendInput.value.trim() : '';
            const fieldName = legend ? (key ? `${legend} [${key}]` : legend) : (key ? `[${key}]` : '');

            let confirmMsg = '<?php echo js_singlequote($BL['be_admin_custom_cpt_delete_field_confirm'] ?? 'Really delete field "%s"?'); ?>';
            if (fieldName) {
                confirmMsg = confirmMsg.replace('%s', fieldName);
            } else {
                confirmMsg = '<?php echo js_singlequote($BL['be_admin_custom_cpt_delete_field_confirm_simple'] ?? 'Really delete this field?'); ?>';
            }

            if (typeof bsConfirmDanger === 'function') {
                bsConfirmDanger(confirmMsg, function() {
                    row.remove();
                    generateTemplateScaffoldJS();
                });
            } else if (confirm(confirmMsg)) {
                row.remove();
                generateTemplateScaffoldJS();
            }
        }

        function updateFieldTagPreview(input) {
            const td = input.closest('td');
            if (!td) return;
            const tagPreview = td.querySelector('.field-tag-preview');
            if (tagPreview) {
                const val = input.value.trim().toUpperCase().replace(/[^A-Z0-9_-]/g, '');
                tagPreview.textContent = val || 'KEY';
            }
        }

        function toggleFieldConfig(select) {
            const row = select.closest('tr');
            const valuesArea = row.querySelector('.values-area');
            if (['select', 'option'].includes(select.value)) {
                valuesArea.classList.remove('d-none');
            } else {
                valuesArea.classList.add('d-none');
            }
            generateTemplateScaffoldJS();
        }

        let fieldCounter = 0;
        function addFieldRow(key = '', def = {}) {
            const container = document.getElementById('fieldsContainer');
            container.insertAdjacentHTML('beforeend', renderFieldRow(key, def, fieldCounter++));
            const lastRow = container.lastElementChild;
            if (lastRow) {
                const lastKeyInput = lastRow.querySelector('input[name="field_key[]"]');
                if (lastKeyInput) {
                    validateFieldRowKey(lastKeyInput);
                }
            }
            generateTemplateScaffoldJS();
        }

        const existingCptKeys = <?php echo json_encode($other_keys); ?>;
        const reservedFieldKeys = <?php echo json_encode(get_custom_cpt_reserved_field_keys()); ?>;
        const reservedFieldErrorMsg = '<?php echo js_singlequote($BL['be_admin_custom_cpt_err_reserved_field_key_simple'] ?? 'Reserved standard tag name (e.g. TITLE, SUBTITLE, TEXT, etc.).'); ?>';
        const keyInput = document.getElementById('cpt_key');
        const titleInput = document.getElementById('cpt_title');
        const modeSelect = document.getElementById('cpt_mode');
        const keyFeedback = document.getElementById('cptKeyFeedback');
        const duplicateErrorMsg = '<?php echo js_singlequote($BL['be_admin_custom_cpt_err_duplicate_key_simple'] ?? 'This identifier key is already in use by another Custom Content Part.'); ?>';

        function validateFieldRowKey(input) {
            if (!input) return true;
            const val = input.value.trim().toLowerCase().replace(/[^a-z0-9_-]/g, '');
            const td = input.closest('td');
            const feedback = td ? td.querySelector('.field-key-feedback') : null;

            if (val && reservedFieldKeys.includes(val)) {
                input.classList.add('is-invalid');
                input.setCustomValidity(reservedFieldErrorMsg);
                if (feedback) {
                    feedback.textContent = reservedFieldErrorMsg;
                    feedback.style.display = 'block';
                }
                return false;
            } else {
                input.classList.remove('is-invalid');
                input.setCustomValidity('');
                if (feedback) {
                    feedback.style.display = 'none';
                }
                return true;
            }
        }

        function validateKeyUnique() {
            if (!keyInput) return true;
            let keyVal = keyInput.value.trim().toLowerCase().replace(/[^a-z0-9_-]/g, '');
            if (!keyVal && titleInput) {
                keyVal = titleInput.value.trim().toLowerCase().replace(/[^a-z0-9_-]/g, '');
            }

            if (keyVal && existingCptKeys.includes(keyVal)) {
                keyInput.classList.add('is-invalid');
                keyInput.setCustomValidity(duplicateErrorMsg);
                if (keyFeedback) {
                    keyFeedback.style.display = 'block';
                }
                return false;
            } else {
                keyInput.classList.remove('is-invalid');
                keyInput.setCustomValidity('');
                if (keyFeedback) {
                    keyFeedback.style.display = 'none';
                }
                return true;
            }
        }

        function generateTemplateScaffoldJS() {
            const title = (titleInput && titleInput.value.trim()) ? titleInput.value.trim() : 'Custom Content Part';
            let key = (keyInput && keyInput.value.trim()) ? keyInput.value.trim().toLowerCase().replace(/[^a-z0-9_-]/g, '') : '';
            if (!key && titleInput) {
                key = titleInput.value.trim().toLowerCase().replace(/[^a-z0-9_-]/g, '');
            }
            key = key || 'block';

            const mode = modeSelect ? modeSelect.value : 'repeater';

            let code = `<!-- ${title} -->\n`;

            if (mode === 'repeater') {
                code += `<!--CUSTOM_HEADER_START//-->\n`;
                code += `<div class="custom-cpt custom-${key}[ATTR_CLASS] {ATTR_CLASS}[/ATTR_CLASS]"[ATTR_ID] id="{ATTR_ID}"[/ATTR_ID]>\n`;
                code += `  [TITLE]<h3>{TITLE}</h3>[/TITLE]\n`;
                code += `  [SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]\n`;
                code += `  <div class="row">\n`;
                code += `<!--CUSTOM_HEADER_END//-->\n\n`;
                code += `<!--CUSTOM_ENTRY_START//-->\n`;
                code += `    <div class="col-md-4 mb-4 custom-cpt-item">\n`;
            } else {
                code += `<div class="custom-cpt custom-${key}[ATTR_CLASS] {ATTR_CLASS}[/ATTR_CLASS]"[ATTR_ID] id="{ATTR_ID}"[/ATTR_ID]>\n`;
                code += `  [TITLE]<h3>{TITLE}</h3>[/TITLE]\n`;
                code += `  [SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]\n`;
            }

            const rows = document.querySelectorAll('#fieldsContainer .field-row');
            rows.forEach(row => {
                const fKeyInput = row.querySelector('input[name="field_key[]"]');
                const fLegendInput = row.querySelector('input[name="field_legend[]"]');
                const fTypeSelect = row.querySelector('select[name="field_type[]"]');

                const rawKey = fKeyInput ? fKeyInput.value.trim().toLowerCase().replace(/[^a-z0-9_-]/g, '') : '';
                if (!rawKey) return;

                const tag = rawKey.toUpperCase();
                const legend = (fLegendInput && fLegendInput.value.trim()) ? fLegendInput.value.trim() : rawKey;
                const type = fTypeSelect ? fTypeSelect.value : 'str';

                if (type === 'bool') {
                    code += `      [${tag}]<div class="badge text-bg-success">${legend}</div>[/${tag}]\n`;
                } else if (type === 'image') {
                    code += `      [${tag}]<img src="{${tag}}" alt="" class="img-fluid mb-2">[/${tag}]\n`;
                } else if (type === 'url') {
                    code += `      [${tag}]<a href="{${tag}}" class="btn btn-primary">${legend}</a>[/${tag}]\n`;
                } else if (type === 'textarea') {
                    code += `      [${tag}]<div class="${rawKey}-content">{${tag}}</div>[/${tag}]\n`;
                } else {
                    code += `      [${tag}]<div class="${rawKey}"><strong>${legend}:</strong> {${tag}}</div>[/${tag}]\n`;
                }
            });

            if (mode === 'repeater') {
                code += `    </div>\n`;
                code += `<!--CUSTOM_ENTRY_END//-->\n\n`;
                code += `<!--CUSTOM_FOOTER_START//-->\n`;
                code += `  </div>\n`;
                code += `</div>\n`;
                code += `<!--CUSTOM_FOOTER_END//-->\n`;
            } else {
                code += `</div>\n`;
            }

            const scaffoldTextarea = document.getElementById('templateScaffoldTextarea');
            if (scaffoldTextarea) {
                scaffoldTextarea.value = code;
            }
        }

        function updateKeyPreview() {
            let keyVal = keyInput ? keyInput.value.trim().toLowerCase().replace(/[^a-z0-9_-]/g, '') : '';
            if (!keyVal && titleInput) {
                keyVal = titleInput.value.trim().toLowerCase().replace(/[^a-z0-9_-]/g, '');
            }
            document.querySelectorAll('.tpl-key-preview').forEach(el => {
                el.textContent = keyVal || '{KEY}';
            });
            validateKeyUnique();
            generateTemplateScaffoldJS();
        }

        if (keyInput) {
            keyInput.addEventListener('input', updateKeyPreview);
        }
        if (titleInput) {
            titleInput.addEventListener('input', updateKeyPreview);
        }
        if (modeSelect) {
            modeSelect.addEventListener('change', generateTemplateScaffoldJS);
        }

        const fieldsContainer = document.getElementById('fieldsContainer');
        if (fieldsContainer) {
            fieldsContainer.addEventListener('input', generateTemplateScaffoldJS);
            fieldsContainer.addEventListener('change', generateTemplateScaffoldJS);
        }

        function initRowDragAndDrop() {
            const container = document.getElementById('fieldsContainer');
            if (!container) return;

            let draggedRow = null;

            container.addEventListener('mousedown', function(e) {
                const handle = e.target.closest('.drag-handle');
                const row = e.target.closest('tr.field-row');
                if (handle && row) {
                    row.setAttribute('draggable', 'true');
                } else if (row) {
                    row.removeAttribute('draggable');
                }
            });

            container.addEventListener('mouseup', function() {
                if (!draggedRow) {
                    const activeRows = container.querySelectorAll('tr.field-row[draggable="true"]');
                    activeRows.forEach(function(r) { r.removeAttribute('draggable'); });
                }
            });

            container.addEventListener('dragstart', function(e) {
                const row = e.target.closest('tr.field-row');
                if (!row || !row.hasAttribute('draggable')) {
                    e.preventDefault();
                    return;
                }
                draggedRow = row;
                draggedRow.classList.add('table-primary');
                draggedRow.style.opacity = '0.4';
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', '');
            });

            container.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                if (!draggedRow) return;

                const targetRow = e.target.closest('tr.field-row');
                if (targetRow && targetRow !== draggedRow && targetRow.parentNode === container) {
                    const rect = targetRow.getBoundingClientRect();
                    const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;
                    container.insertBefore(draggedRow, next ? targetRow.nextSibling : targetRow);
                }
            });

            container.addEventListener('dragend', function() {
                if (draggedRow) {
                    draggedRow.classList.remove('table-primary');
                    draggedRow.style.opacity = '';
                    draggedRow.removeAttribute('draggable');
                    draggedRow = null;
                    generateTemplateScaffoldJS();
                }
                const activeRows = container.querySelectorAll('tr.field-row[draggable="true"]');
                activeRows.forEach(function(r) { r.removeAttribute('draggable'); });
            });
        }

        const cptForm = document.getElementById('cptForm');
        if (cptForm) {
            cptForm.addEventListener('submit', function(e) {
                let valid = validateKeyUnique();
                const fieldKeyInputs = document.querySelectorAll('#fieldsContainer input[name="field_key[]"]');
                fieldKeyInputs.forEach(function(input) {
                    if (!validateFieldRowKey(input)) {
                        valid = false;
                    }
                });
                if (!valid) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateKeyPreview();
            validateKeyUnique();
            if (Object.keys(initialFields).length > 0) {
                for (let k in initialFields) {
                    addFieldRow(k, initialFields[k]);
                }
            } else {
                addFieldRow('headline', { legend: 'Headline', type: 'str' });
                addFieldRow('description', { legend: 'Description', type: 'textarea' });
            }
            initRowDragAndDrop();
            generateTemplateScaffoldJS();
        });
        </script>

      <?php else: // SHOW LISTING OF CUSTOM CPTS ?>

        <div class="card shadow-sm mb-4">
          <div class="card-header fw-bold">
            <?php echo html($BL['be_admin_custom_cpt_registered'] ?? 'Configured Custom Content Parts'); ?>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover table-striped table-valign-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 40px;" class="text-center"><?php echo html($BL['be_admin_custom_cpt_status'] ?? 'Status'); ?></th>
                    <th><?php echo html($BL['be_admin_custom_cpt_title'] ?? 'Title'); ?></th>
                    <th><?php echo html($BL['be_admin_custom_cpt_key'] ?? 'Identifier Key'); ?></th>
                    <th><?php echo html($BL['be_admin_custom_cpt_mode'] ?? 'Mode'); ?></th>
                    <th><?php echo html($BL['be_admin_custom_cpt_fields'] ?? 'Fields'); ?></th>
                    <th class="text-end text-nowrap" style="width: 140px;"><?php echo html($BL['be_admin_custom_cpt_actions'] ?? 'Actions'); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($all_cpts) && is_array($all_cpts)): ?>
                    <?php foreach ($all_cpts as $cpt): ?>
                      <tr>
                        <td class="text-center">
                          <?php if (strpos((string)$cpt['cpt_id'], 'preset_') === 0): ?>
                            <span class="badge text-bg-info" title="JSON File Preset"><?php echo html($BL['be_admin_custom_cpt_preset'] ?? 'Preset'); ?></span>
                          <?php elseif (strpos((string)$cpt['cpt_id'], 'legacy_') === 0): ?>
                            <span class="badge text-bg-secondary" title="Legacy Config Array"><?php echo html($BL['be_admin_custom_cpt_legacy'] ?? 'Legacy'); ?></span>
                          <?php else: ?>
                            <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=16&amp;toggle=<?php echo $cpt['cpt_id']; ?>" class="btn btn-sm <?php echo !empty($cpt['cpt_active']) ? 'btn-success' : 'btn-warning'; ?>" data-bs-toggle="tooltip" title="<?php echo html($BL['be_tooltip_visibility'] ?? 'Activate/Deactivate'); ?>">
                              <i class="fa fa-<?php echo !empty($cpt['cpt_active']) ? 'check' : 'times'; ?>"></i>
                            </a>
                          <?php endif; ?>
                        </td>
                        <td class="fw-bold">
                          <i class="fa <?php echo html($cpt['cpt_icon'] ?? 'fa-cube'); ?> text-primary me-1"></i>
                          <?php echo html($cpt['cpt_title']); ?>
                          <?php if (!empty($cpt['cpt_desc'])): ?>
                            <div class="small text-muted fw-normal"><?php echo html($cpt['cpt_desc']); ?></div>
                          <?php endif; ?>
                        </td>
                        <td class="font-monospace text-muted"><?php echo html($cpt['cpt_key']); ?></td>
                        <td>
                          <span class="badge text-bg-light border"><?php echo html($cpt['cpt_mode'] ?? 'repeater'); ?></span>
                        </td>
                        <td>
                          <span class="badge rounded-pill text-bg-secondary"><?php echo count($cpt['fields'] ?? []); ?></span>
                        </td>
                        <td class="text-end text-nowrap">
                          <?php if (is_numeric($cpt['cpt_id'])): ?>
                            <div class="btn-group btn-group-sm" role="group" aria-label="cpt-actions-<?php echo $cpt['cpt_id']; ?>">
                              <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=16&amp;edit=<?php echo $cpt['cpt_id']; ?>" class="btn btn-blue btn-sm" role="button" data-bs-toggle="tooltip" title="<?php echo html($BL['be_tt_edit'] ?? 'Edit'); ?>"><i class="fa fa-pencil-alt"></i></a>
                              <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=16&amp;export=<?php echo $cpt['cpt_id']; ?>" class="btn btn-blue btn-sm" role="button" data-bs-toggle="tooltip" title="<?php echo html($BL['be_admin_custom_cpt_export'] ?? 'Export JSON'); ?>"><i class="fa fa-download"></i></a>
                            </div>
                            <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=16&amp;delete=<?php echo $cpt['cpt_id']; ?>" class="btn btn-danger btn-sm ms-1" role="button" data-bs-toggle="tooltip" title="<?php echo html($BL['be_tt_delete'] ?? 'Delete') . ': ' . html($cpt['cpt_title']); ?>" data-confirm-danger="<?php echo html(($BL['be_admin_custom_cpt_delete_confirm'] ?? 'Delete this custom content part definition?') . "\n[" . $cpt['cpt_title'] . ']'); ?>"><i class="far fa-trash-alt fa-fw"></i></a>
                          <?php else: ?>
                            <span class="small text-muted"><?php echo html($BL['be_admin_custom_cpt_readonly_file'] ?? 'Read-only (File)'); ?></span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fa fa-info-circle"></i> <?php echo html($BL['be_admin_custom_cpt_no_entries'] ?? 'No custom content parts defined yet.'); ?> <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=16&amp;new=1"><?php echo html($BL['be_admin_custom_cpt_create_one'] ?? 'Create one now'); ?></a>.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      <?php endif; ?>

    </div>
  </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="phpwcms.php?do=admin&amp;p=16" method="post" enctype="multipart/form-data">
        <input type="hidden" name="import_custom_cpt" value="1">
        <div class="modal-header">
          <h5 class="modal-title" id="importModalLabel"><i class="fa fa-upload"></i> <?php echo html($BL['be_admin_custom_cpt_modal_import'] ?? 'Import Custom Content Part'); ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="import_file"><strong><?php echo html($BL['be_admin_custom_cpt_upload_json'] ?? 'Upload JSON File'); ?></strong></label>
            <input type="file" name="import_file" id="import_file" class="form-control-file" accept=".json">
          </div>
          <div class="form-group">
            <label for="import_json"><strong><?php echo html($BL['be_admin_custom_cpt_paste_json'] ?? 'Or Paste JSON Content'); ?></strong></label>
            <textarea name="import_json" id="import_json" class="form-control font-monospace" rows="6" placeholder="{...}"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><?php echo html($BL['be_admin_fcat_exit'] ?? 'Cancel'); ?></button>
          <button type="submit" class="btn btn-sm btn-blue"><?php echo html($BL['be_admin_custom_cpt_import'] ?? 'Import'); ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
