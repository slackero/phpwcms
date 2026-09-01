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

$active_cpt = null;
$cpt_mode   = 'repeater';
$cpt_fields = array();

if (!empty($content['module']) && function_exists('get_custom_contentpart_by_key')) {
    $active_cpt = get_custom_contentpart_by_key($content['module']);
    if ($active_cpt) {
        $cpt_mode   = $active_cpt['cpt_mode'] ?? 'repeater';
        $cpt_fields = $active_cpt['fields'] ?? array();
    }
} elseif (empty($content['module']) && function_exists('get_custom_contentparts')) {
    $all_cpts = get_custom_contentparts(true);
    if (!empty($all_cpts)) {
        $active_cpt = reset($all_cpts);
        $content['module'] = $active_cpt['cpt_key'] ?? '';
        $cpt_mode   = $active_cpt['cpt_mode'] ?? 'repeater';
        $cpt_fields = $active_cpt['fields'] ?? array();
    }
}

// Fallback to legacy $template_default['settings']['customctp_custom_fields']
$tab_fieldgroup_templates = array();
if (empty($cpt_fields) && isset($template_default['settings']['customctp_custom_fields']) && is_array($template_default['settings']['customctp_custom_fields'])) {
    foreach ($template_default['settings']['customctp_custom_fields'] as $key => $tab_fieldgroup) {
        $tab_fieldgroup_templates[$tab_fieldgroup['template'] ?? 'default'] = $key;
    }
}

if (!isset($content['custom_form']['custom_elements']) || !is_array($content['custom_form']['custom_elements'])) {
    $content['custom_form']['custom_elements'] = array();
}

// If in single mode and no elements yet, initialize element 0
if ($cpt_mode === 'single' && empty($content['custom_form']['custom_elements'])) {
    $content['custom_form']['custom_elements'][] = array('custom_fields' => array());
}

?>
<input type="hidden" name="ctype_module" value="<?php echo html($content['module']); ?>" />


<div class="form-group align-items-center row g-2 mb-3">
  <label for="template" class="col-sm-2 col-form-label text-sm-end"><?php echo html($BL['be_admin_struct_template']); ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="form-select form-select-sm">
      <option value=""<?php echo empty($content['custom_template']) ? ' selected="selected"' : ''; ?>><?php echo html($BL['be_admin_tmpl_default']); ?></option>
      <?php
      $cpt_key = $content['module'] ?? '';
      $specific_templates = array();
      $general_templates  = array();

      // 1. Check dedicated subfolder: template/inc_cntpart/custom/<cpt_key>/
      if (!empty($cpt_key) && is_dir(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $cpt_key)) {
          $subtmpllist = get_tmpl_files(PHPWCMS_TEMPLATE . 'inc_cntpart/custom/' . $cpt_key);
          if (is_array($subtmpllist) && count($subtmpllist)) {
              foreach ($subtmpllist as $val) {
                  if (substr($val, 0, 5) === 'list.') continue;
                  $specific_templates[] = $cpt_key . '/' . $val;
              }
          }
      }

      // 2. Check root folder: template/inc_cntpart/custom/
      $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE . 'inc_cntpart/custom');
      if (is_array($tmpllist) && count($tmpllist)) {
          foreach ($tmpllist as $val) {
              if (substr($val, 0, 5) === 'list.') continue;
              // Match files starting with cpt_key (e.g. hero_banner.tmpl, hero_banner_dark.tmpl)
              if (!empty($cpt_key) && (
                  $val === $cpt_key . '.tmpl' ||
                  $val === $cpt_key . '.html' ||
                  strpos($val, $cpt_key . '.') === 0 ||
                  strpos($val, $cpt_key . '_') === 0 ||
                  strpos($val, $cpt_key . '-') === 0
              )) {
                  $specific_templates[] = $val;
              } else {
                  $general_templates[] = $val;
              }
          }
      }

      // If specific templates exist for this CPT, list them first
      if (!empty($specific_templates)) {
          foreach ($specific_templates as $val) {
              $selected_val = (!empty($content['custom_template']) && $val === $content['custom_template']) ? ' selected="selected"' : '';
              echo '      <option value="' . html($val) . '"' . $selected_val . '>' . html($val) . '</option>' . LF;
          }
          if (!empty($general_templates)) {
              echo '      <optgroup label="' . html($BL['be_admin_optgroup_label'] ?? 'General') . '">' . LF;
              foreach ($general_templates as $val) {
                  $selected_val = (!empty($content['custom_template']) && $val === $content['custom_template']) ? ' selected="selected"' : '';
                  echo '        <option value="' . html($val) . '"' . $selected_val . '>' . html($val) . '</option>' . LF;
              }
              echo '      </optgroup>' . LF;
          }
      } else {
          foreach ($general_templates as $val) {
              $selected_val = (!empty($content['custom_template']) && $val === $content['custom_template']) ? ' selected="selected"' : '';
              echo '      <option value="' . html($val) . '"' . $selected_val . '>' . html($val) . '</option>' . LF;
          }
      }
      ?>
    </select>
  </div>
  <?php if ($active_cpt): ?>
    <div class="col-sm-6 text-muted small">
      <i class="fa-solid <?php echo html($active_cpt['cpt_icon'] ?? 'fa-cube'); ?> text-primary"></i> <strong><?php echo html($active_cpt['cpt_title']); ?></strong>
      (<?php echo html($cpt_mode === 'single' ? ($BL['be_admin_custom_cpt_single'] ?? 'Single Item') : ($BL['be_admin_custom_cpt_repeater'] ?? 'Repeater')); ?>)
      <?php if (!empty($active_cpt['cpt_desc'])): ?>
        &mdash; <?php echo html($active_cpt['cpt_desc']); ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>

<?php if ($cpt_mode === 'single'): // SINGLE ITEM MODE ?>

  <div class="card my-3 shadow-sm">
    <div class="card-header bg-light fw-bold">
      <i class="fa-solid fa-sliders-h"></i> <?php echo html($active_cpt['cpt_title'] ?? 'Fields'); ?>
    </div>
    <div class="card-body">
      <?php
      $item_fields = $content['custom_form']['custom_elements'][0]['custom_fields'] ?? array();
      if (!empty($cpt_fields)) {
          foreach ($cpt_fields as $f_key => $f_def) {
              $current_val = $item_fields[$f_key] ?? null;
              echo custom_field_render_input($f_key, $f_def, $current_val, 'customfield[0]');
          }
      } else {
          echo '<p class="text-muted">' . html($BL['be_cnt_custom_entry'] ?? 'No fields defined.') . '</p>';
      }
      ?>
    </div>
  </div>

<?php else: // REPEATER / MULTI-ITEM MODE ?>

  <div class="form-group align-items-center row g-2 mb-3">
    <label class="col-sm-2 col-form-label text-sm-end"><?php echo html($BL['be_cnt_custom_entry'] ?? 'Entries'); ?></label>
    <div class="col-sm-5">
      <button type="button" onclick="addNewCustomElement();" class="btn btn-blue btn-sm"><i class="fa-solid fa-plus"></i> <?php echo html($BL['be_article_cnt_add'] ?? 'Add Element'); ?></button>
    </div>
  </div>

  <ul id="custom_elements" class="dropable-list p-0 list-unstyled">
    <?php
    $element_count = count($content['custom_form']['custom_elements']);
    foreach ($content['custom_form']['custom_elements'] as $key => $element_data):
        $item_fields = $element_data['custom_fields'] ?? array();
    ?>
      <li id="custom_element_<?php echo $key; ?>" class="card my-3 p-0 sortme shadow-sm">
        <div class="card-header p-2 bg-light border-bottom" role="tab" id="heading_<?php echo $key; ?>">
          <div class="row align-items-center">
            <div class="col-auto pe-0">
              <span class="handle text-muted"><i class="fa-solid fa-grip-vertical"></i></span>
            </div>
            <div class="col">
              <h5 class="mb-0 fw-bold">#<?php echo ($key + 1); ?></h5>
            </div>
            <div class="col-auto">
              <a class="btn btn-sm btn-secondary me-1" data-bs-toggle="collapse" href="#collapse_<?php echo $key; ?>" aria-expanded="true" aria-controls="collapse_<?php echo $key; ?>">
                <i class="fa-solid fa-chevron-down"></i>
              </a>
              <button type="button" class="btn btn-sm btn-danger" onclick="deleteCustomElement('custom_element_<?php echo $key; ?>');">
                <i class="fa-regular fa-trash-alt fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <div id="collapse_<?php echo $key; ?>" class="collapse show p-3" role="tabpanel">
          <?php
          if (!empty($cpt_fields)) {
              foreach ($cpt_fields as $f_key => $f_def) {
                  $current_val = $item_fields[$f_key] ?? null;
                  echo custom_field_render_input($f_key, $f_def, $current_val, 'customfield[' . $key . ']');
              }
          } else {
              // Legacy fieldgroup fallback
              $tab_fieldgroup_key = $content['custom_form']['fieldgroup'] ?? ($tab_fieldgroup_templates['default'] ?? '');
              $legacy_fields = $template_default['settings']['customctp_custom_fields'][$tab_fieldgroup_key]['fields'] ?? array();
              foreach ($legacy_fields as $f_key => $f_def) {
                  $current_val = $item_fields[$f_key] ?? null;
                  echo custom_field_render_input($f_key, $f_def, $current_val, 'customfield[' . $key . ']');
              }
          }
          ?>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>

  <!-- Template for adding new repeater items dynamically -->
  <template id="custom_element_template">
    <li id="custom_element___INDEX__" class="card my-3 p-0 sortme shadow-sm">
      <div class="card-header p-2 bg-light border-bottom" role="tab">
        <div class="row align-items-center">
          <div class="col-auto pe-0">
            <span class="handle text-muted"><i class="fa-solid fa-grip-vertical"></i></span>
          </div>
          <div class="col">
            <h5 class="mb-0 fw-bold">#__NUM__</h5>
          </div>
          <div class="col-auto">
            <a class="btn btn-sm btn-secondary me-1" data-bs-toggle="collapse" href="#collapse___INDEX__" aria-expanded="true">
              <i class="fa-solid fa-chevron-down"></i>
            </a>
            <button type="button" class="btn btn-sm btn-danger" onclick="deleteCustomElement('custom_element___INDEX__');">
              <i class="fa-regular fa-trash-alt fa-fw"></i>
            </button>
          </div>
        </div>
      </div>
      <div id="collapse___INDEX__" class="collapse show p-3">
        <?php
        if (!empty($cpt_fields)) {
            foreach ($cpt_fields as $f_key => $f_def) {
                echo custom_field_render_input($f_key, $f_def, null, 'customfield[__INDEX__]');
            }
        }
        ?>
      </div>
    </li>
  </template>

  <script>
  let nextCustomIndex = <?php echo count($content['custom_form']['custom_elements']); ?>;

  function addNewCustomElement() {
      const template = document.getElementById('custom_element_template').innerHTML;
      const html = template
          .replace(/__INDEX__/g, nextCustomIndex)
          .replace(/__NUM__/g, nextCustomIndex + 1);

      document.getElementById('custom_elements').insertAdjacentHTML('beforeend', html);
      nextCustomIndex++;
  }

  function deleteCustomElement(elementId) {
      const el = document.getElementById(elementId);
      if (!el) return;
      const numEl = el.querySelector('h5');
      const entryNum = numEl ? numEl.textContent.trim() : '';

      let confirmMsg = '<?php echo js_singlequote($BL['be_admin_custom_cpt_delete_entry_confirm'] ?? 'Really remove entry %s?'); ?>';
      if (entryNum) {
          confirmMsg = confirmMsg.replace('%s', entryNum);
      } else {
          confirmMsg = '<?php echo js_singlequote($BL['be_admin_custom_cpt_delete_entry_confirm_simple'] ?? 'Really remove this entry?'); ?>';
      }

      if (typeof bsConfirmDanger === 'function') {
          bsConfirmDanger(confirmMsg, function() {
              el.remove();
          });
      } else if (confirm(confirmMsg)) {
          el.remove();
      }
  }
  </script>

<?php endif; ?>
