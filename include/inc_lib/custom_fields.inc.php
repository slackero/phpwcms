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

/**
 * Ensure the custom content parts database table exists
 */
function ensure_custom_cpt_table() {
    static $checked = null;
    if ($checked !== null) {
        return $checked;
    }

    if (!defined('DB_PREPEND') || !function_exists('_dbTableExists') || !function_exists('_dbQuery')) {
        return false;
    }

    if (!_dbTableExists('phpwcms_custom_cpt')) {
        $charset_collate = _dbGetCreateCharsetCollation();
        $sql = 'CREATE TABLE IF NOT EXISTS ' . DB_PREPEND . 'phpwcms_custom_cpt (
            cpt_id int(11) unsigned NOT NULL AUTO_INCREMENT,
            cpt_created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            cpt_changed datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            cpt_active tinyint(1) NOT NULL DEFAULT 1,
            cpt_key varchar(50) NOT NULL DEFAULT "",
            cpt_title varchar(255) NOT NULL DEFAULT "",
            cpt_desc text NOT NULL,
            cpt_mode varchar(20) NOT NULL DEFAULT "repeater",
            cpt_icon varchar(50) NOT NULL DEFAULT "fa-cube",
            cpt_template varchar(255) NOT NULL DEFAULT "",
            cpt_schema longtext NOT NULL,
            PRIMARY KEY (cpt_id),
            UNIQUE KEY cpt_key (cpt_key)
        ) ENGINE=InnoDB ' . $charset_collate;
        _dbQuery($sql, 'CREATE');
    }
    $checked = true;
    return $checked;
}

/**
 * Convert string or recursive data structure to UTF-8
 *
 * @param mixed $data
 * @return mixed
 */
function custom_cpt_to_utf8($data) {
    if (!defined('PHPWCMS_CHARSET') || PHPWCMS_CHARSET === 'utf-8' || $data === null) {
        return $data;
    }
    if (is_array($data)) {
        $clean = array();
        foreach ($data as $k => $v) {
            $k_utf8 = is_string($k) && function_exists('makeCharsetConversion') ? makeCharsetConversion($k, PHPWCMS_CHARSET, 'utf-8') : $k;
            $clean[$k_utf8] = custom_cpt_to_utf8($v);
        }
        return $clean;
    } elseif (is_string($data) && function_exists('makeCharsetConversion')) {
        return makeCharsetConversion($data, PHPWCMS_CHARSET, 'utf-8');
    }
    return $data;
}

/**
 * Convert string or recursive data structure from UTF-8 to PHPWCMS_CHARSET
 *
 * @param mixed $data
 * @return mixed
 */
function custom_cpt_from_utf8($data) {
    if (!defined('PHPWCMS_CHARSET') || PHPWCMS_CHARSET === 'utf-8' || $data === null) {
        return $data;
    }
    if (is_array($data)) {
        $clean = array();
        foreach ($data as $k => $v) {
            $k_loc = is_string($k) && function_exists('makeCharsetConversion') ? makeCharsetConversion($k, 'utf-8', PHPWCMS_CHARSET) : $k;
            $clean[$k_loc] = custom_cpt_from_utf8($v);
        }
        return $clean;
    } elseif (is_string($data) && function_exists('makeCharsetConversion')) {
        return makeCharsetConversion($data, 'utf-8', PHPWCMS_CHARSET);
    }
    return $data;
}

/**
 * Fetch all registered custom content parts
 *
 * @param bool $only_active
 * @return array
 */
function get_custom_contentparts($only_active = false) {
    global $template_default;

    $cpts = array();
    $all_db_keys = array();

    // Fetch from database
    if (ensure_custom_cpt_table() && function_exists('_dbGet')) {
        $rows = _dbGet('phpwcms_custom_cpt', '*', '', '', 'cpt_title ASC');

        if (is_array($rows) && count($rows)) {
            foreach ($rows as $row) {
                $all_db_keys[$row['cpt_key']] = true;
                if ($only_active && empty($row['cpt_active'])) {
                    continue;
                }
                $schema = array();
                if (!empty($row['cpt_schema'])) {
                    $decoded = json_decode($row['cpt_schema'], true);
                    if (is_array($decoded)) {
                        $schema = custom_cpt_from_utf8($decoded);
                    } else {
                        $schema = @unserialize($row['cpt_schema'], ['allowed_classes' => false]);
                    }
                }
                $row['fields'] = is_array($schema) ? $schema : array();
                $cpts[$row['cpt_key']] = $row;
            }
        }
    }

    // Check JSON file presets in template/config/custom_cpt/
    $preset_dir = PHPWCMS_TEMPLATE . 'config/custom_cpt';
    if (is_dir($preset_dir)) {
        $files = scandir($preset_dir);
        if (is_array($files)) {
            foreach ($files as $file) {
                if (substr($file, -5) === '.json') {
                    $key = substr($file, 0, -5);
                    if (!isset($all_db_keys[$key]) && !isset($cpts[$key])) {
                        $content = file_get_contents($preset_dir . '/' . $file);
                        $data = json_decode($content, true);
                        if (is_array($data) && !empty($data['cpt_title'])) {
                            $data = custom_cpt_from_utf8($data);
                            if (!$only_active || !empty($data['cpt_active'])) {
                                $data['cpt_key'] = $key;
                                $data['cpt_id'] = 'preset_' . $key;
                                $data['cpt_mode'] = $data['cpt_mode'] ?? 'repeater';
                                $data['fields'] = $data['fields'] ?? array();
                                $cpts[$key] = $data;
                            }
                        }
                    }
                }
            }
        }
    }

    // Fallback merge for legacy $template_default['settings']['customctp_custom_fields']
    if (!empty($template_default['settings']['customctp_custom_fields']) && is_array($template_default['settings']['customctp_custom_fields'])) {
        foreach ($template_default['settings']['customctp_custom_fields'] as $key => $fg) {
            if (!isset($all_db_keys[$key]) && !isset($cpts[$key])) {
                $cpt_title = $fg['legend'] ?? ucfirst($key);
                if (function_exists('i18n_substitute_text')) {
                    $cpt_title = i18n_substitute_text($cpt_title);
                }
                $cpts[$key] = array(
                    'cpt_id'       => 'legacy_' . $key,
                    'cpt_key'      => $key,
                    'cpt_title'    => $cpt_title,
                    'cpt_desc'     => 'Legacy config custom fieldgroup',
                    'cpt_mode'     => 'repeater',
                    'cpt_icon'     => 'fa-cube',
                    'cpt_template' => $fg['template'] ?? '',
                    'cpt_active'   => 1,
                    'fields'       => $fg['fields'] ?? array()
                );
            }
        }
    }

    return $cpts;
}

/**
 * Retrieve a specific custom content part by its key or ID
 *
 * @param string|int $key_or_id
 * @return array|null
 */
function get_custom_contentpart_by_key($key_or_id) {
    if (empty($key_or_id)) {
        return null;
    }

    $cpts = get_custom_contentparts(false);
    if (isset($cpts[$key_or_id])) {
        return $cpts[$key_or_id];
    }

    foreach ($cpts as $cpt) {
        if (isset($cpt['cpt_id']) && (string)$cpt['cpt_id'] === (string)$key_or_id) {
            return $cpt;
        }
    }

    return null;
}

/**
 * Get human-readable title of a custom content part
 *
 * @param string $key
 * @return string
 */
function get_custom_contentpart_title($key) {
    $cpt = get_custom_contentpart_by_key($key);
    return $cpt ? $cpt['cpt_title'] : ucfirst($key);
}

/**
 * Check if a custom content part key is already taken in the database
 *
 * @param string $key
 * @param int $exclude_id
 * @return bool
 */
function custom_cpt_key_exists($key, $exclude_id = 0) {
    if (empty($key) || !function_exists('_dbGet')) {
        return false;
    }
    $where = 'cpt_key = ' . _dbEscape($key);
    $exclude_id = (int)$exclude_id;
    if ($exclude_id > 0) {
        $where .= ' AND cpt_id != ' . $exclude_id;
    }
    $rows = _dbGet('phpwcms_custom_cpt', 'cpt_id', $where);
    return is_array($rows) && count($rows) > 0;
}

/**
 * Generate a unique custom content part key
 *
 * @param string $key
 * @param int $exclude_id
 * @return string
 */
function get_unique_custom_cpt_key($key, $exclude_id = 0) {
    $key = preg_replace('/[^-a-z0-9_]/i', '', strtolower(trim($key)));
    if (empty($key)) {
        $key = 'custom_cpt';
    }
    $base = $key;
    $i = 1;
    while (custom_cpt_key_exists($key, $exclude_id)) {
        $key = $base . '_' . $i;
        $i++;
    }
    return $key;
}

/**
 * Return list of reserved field keys / standard content part tags
 *
 * @return array
 */
function get_custom_cpt_reserved_field_keys() {
    return array(
        'title',
        'subtitle',
        'attr_class',
        'attr_id',
        'anchor',
        'id',
        'before',
        'after',
        'html',
        'text',
        'cpt_key',
        'module',
        'cpt_title',
        'cpt_desc',
        'item_index',
        'item_num',
        'item_total'
    );
}

/**
 * Check if a custom content part field key is a reserved standard tag
 *
 * @param string $key
 * @return bool
 */
function is_custom_cpt_reserved_field_key($key) {
    if (empty($key)) {
        return false;
    }
    return in_array(strtolower(trim($key)), get_custom_cpt_reserved_field_keys(), true);
}

/**
 * Count active content parts using a specific custom CPT key
 *
 * @param string $key
 * @return int
 */
function get_custom_cpt_usage_count($key) {
    if (empty($key) || !function_exists('_dbCount') || !defined('DB_PREPEND')) {
        return 0;
    }
    return (int)_dbCount('SELECT COUNT(*) FROM ' . DB_PREPEND . 'phpwcms_articlecontent WHERE acontent_type = 60 AND acontent_module = ' . _dbEscape($key) . ' AND acontent_trash = 0');
}

/**
 * Rename custom content part template files and subfolder on disk when key changes
 *
 * @param string $old_key
 * @param string $new_key
 * @return bool
 */
function rename_custom_cpt_templates($old_key, $new_key) {
    if (empty($old_key) || empty($new_key) || $old_key === $new_key || !defined('PHPWCMS_TEMPLATE')) {
        return false;
    }

    $base_dir = PHPWCMS_TEMPLATE . 'inc_cntpart/custom/';
    if (!is_dir($base_dir)) {
        return false;
    }

    $old_dir = $base_dir . $old_key;
    $new_dir = $base_dir . $new_key;

    // 1. Rename dedicated subfolder if exists
    if (is_dir($old_dir) && !file_exists($new_dir)) {
        @rename($old_dir, $new_dir);
    }

    // 2. Rename root prefix template files
    $files = scandir($base_dir);
    if (is_array($files)) {
        foreach ($files as $f) {
            if (is_file($base_dir . $f)) {
                if ($f === $old_key . '.tmpl' && !file_exists($base_dir . $new_key . '.tmpl')) {
                    @rename($base_dir . $f, $base_dir . $new_key . '.tmpl');
                } elseif ($f === $old_key . '.html' && !file_exists($base_dir . $new_key . '.html')) {
                    @rename($base_dir . $f, $base_dir . $new_key . '.html');
                } elseif (strpos($f, $old_key . '.') === 0) {
                    $rest = substr($f, strlen($old_key));
                    $target = $base_dir . $new_key . $rest;
                    if (!file_exists($target)) {
                        @rename($base_dir . $f, $target);
                    }
                } elseif (strpos($f, $old_key . '_') === 0) {
                    $rest = substr($f, strlen($old_key));
                    $target = $base_dir . $new_key . $rest;
                    if (!file_exists($target)) {
                        @rename($base_dir . $f, $target);
                    }
                }
            }
        }
    }

    return true;
}

/**
 * Save custom content part definition to DB
 *
 * @param array $data
 * @return int|bool
 */
function save_custom_contentpart($data) {
    ensure_custom_cpt_table();

    $id = isset($data['cpt_id']) ? (int)$data['cpt_id'] : 0;
    $key = preg_replace('/[^-a-z0-9_]/i', '', strtolower(trim($data['cpt_key'] ?? '')));
    if (empty($key)) {
        $key = get_unique_custom_cpt_key($data['cpt_title'] ?? 'custom', $id);
    } elseif (custom_cpt_key_exists($key, $id)) {
        return false;
    }

    $fields = $data['fields'] ?? array();
    if (is_string($fields)) {
        $fields = json_decode($fields, true);
    }
    if (!is_array($fields)) {
        $fields = array();
    }
    $clean_fields = array();
    foreach ($fields as $fk => $fv) {
        $fk = preg_replace('/[^-a-z0-9_]/i', '', strtolower(trim($fk)));
        if (!empty($fk) && !is_custom_cpt_reserved_field_key($fk)) {
            $clean_fields[$fk] = $fv;
        }
    }

    $db_data = array(
        'cpt_key'      => $key,
        'cpt_title'    => clean_slweg($data['cpt_title'] ?? ''),
        'cpt_desc'     => slweg($data['cpt_desc'] ?? ''),
        'cpt_mode'     => in_array($data['cpt_mode'] ?? '', array('single', 'repeater')) ? $data['cpt_mode'] : 'repeater',
        'cpt_icon'     => clean_slweg($data['cpt_icon'] ?? 'fa-cube'),
        'cpt_template' => clean_slweg($data['cpt_template'] ?? ''),
        'cpt_active'   => empty($data['cpt_active']) ? 0 : 1,
        'cpt_schema'   => json_encode(custom_cpt_to_utf8($clean_fields), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
    );

    if ($id > 0) {
        $old_row = function_exists('_dbGet') ? _dbGet('phpwcms_custom_cpt', 'cpt_key', 'cpt_id = ' . $id) : null;
        $old_key = !empty($old_row[0]['cpt_key']) ? $old_row[0]['cpt_key'] : '';

        _dbUpdate('phpwcms_custom_cpt', $db_data, 'cpt_id = ' . $id);

        if (!empty($old_key) && $old_key !== $key) {
            rename_custom_cpt_templates($old_key, $key);
        }

        return $id;
    } else {
        $insert = _dbInsert('phpwcms_custom_cpt', $db_data);
        return !empty($insert['INSERT_ID']) ? (int)$insert['INSERT_ID'] : false;
    }
}

/**
 * Delete custom content part definition
 *
 * @param int $id
 * @return bool
 */
function delete_custom_contentpart($id) {
    ensure_custom_cpt_table();
    $id = (int)$id;
    if ($id > 0) {
        return (bool)_dbQuery('DELETE FROM ' . DB_PREPEND . 'phpwcms_custom_cpt WHERE cpt_id = ' . $id, 'DELETE');
    }
    return false;
}

/**
 * Sanitize a raw field value based on field type definition
 *
 * @param array $field_def
 * @param mixed $raw_value
 * @return mixed
 */
function custom_field_sanitize_value($field_def, $raw_value) {
    $type = $field_def['type'] ?? 'str';
    $render = $field_def['render'] ?? '';

    if ($type === 'int') {
        return (int)$raw_value;
    } elseif ($type === 'float') {
        return (float)$raw_value;
    } elseif ($type === 'bool') {
        return empty($raw_value) ? 0 : 1;
    } elseif ($type === 'url') {
        return filter_var(trim((string)$raw_value), FILTER_SANITIZE_URL);
    } elseif ($type === 'color') {
        $val = trim((string)$raw_value);
        return preg_match('/^#([a-f0-9]{3}|[a-f0-9]{6})$/i', $val) ? $val : '';
    } elseif ($type === 'date') {
        return clean_slweg((string)$raw_value, 20);
    } elseif ($type === 'file') {
        return is_array($raw_value) ? $raw_value : clean_slweg((string)$raw_value);
    } elseif ($type === 'image') {
        return is_array($raw_value) ? $raw_value : clean_slweg((string)$raw_value);
    } elseif (in_array($render, array('html', 'markdown', 'wysiwyg'))) {
        return slweg((string)$raw_value);
    } else {
        $maxlength = !empty($field_def['maxlength']) ? (int)$field_def['maxlength'] : 0;
        return clean_slweg((string)$raw_value, $maxlength);
    }
}

/**
 * Render HTML input control for a single custom field in backend
 *
 * @param string $field_key
 * @param array $field_def
 * @param mixed $value
 * @param string $name_prefix
 * @param int|string|null $index
 * @return string
 */
function custom_field_render_input($field_key, $field_def, $value = null, $name_prefix = 'customfield', $index = null) {
    $type        = $field_def['type'] ?? 'str';
    $legend      = $field_def['legend'] ?? ($field_def['label'] ?? ucfirst($field_key));
    if (function_exists('i18n_substitute_text')) {
        $legend = i18n_substitute_text($legend);
    }
    $render      = $field_def['render'] ?? '';
    $placeholder = $field_def['placeholder'] ?? '';
    if ($placeholder !== '' && function_exists('i18n_substitute_text')) {
        $placeholder = i18n_substitute_text($placeholder);
    }
    $class       = $field_def['class'] ?? '';
    $hr          = !empty($field_def['hr']);
    $default     = $field_def['default'] ?? '';

    if ($value === null) {
        $value = $default;
    }

    if ($index !== null) {
        $input_name = $name_prefix . '[' . $index . '][' . $field_key . ']';
        $input_id   = 'cfield_' . $field_key . '_' . $index;
    } else {
        $input_name = $name_prefix . '[' . $field_key . ']';
        $input_id   = 'cfield_' . $field_key;
    }

    $out = '';
    if ($hr) {
        $out .= '<hr class="my-2">' . LF;
    }

    $out .= '<div class="form-group row align-items-center mb-2">' . LF;
    $out .= '  <label for="' . html($input_id) . '" class="col-sm-3 col-form-label text-sm-end">' . html($legend) . '</label>' . LF;
    $out .= '  <div class="col-sm-9">' . LF;

    switch ($type) {
        case 'textarea':
            $rows = !empty($field_def['rows']) ? (int)$field_def['rows'] : 3;
            $height = !empty($field_def['height']) ? ' style="height:' . html($field_def['height']) . ';"' : '';
            $wysiwyg_class = ($render === 'wysiwyg') ? ' wysiwyg-editor' : '';
            $code_class = '';
            $mode_attr = '';
            if (in_array($render, array('markdown', 'textile', 'html', 'code', 'css', 'javascript', 'js', 'json', 'sql', 'php'), true)) {
                $code_class = ' code-editor';
                $ace_mode = ($render === 'code') ? 'html' : (($render === 'js') ? 'javascript' : $render);
                $mode_attr = ' data-mode="' . html($ace_mode) . '"';
                if (function_exists('initAceEditor')) {
                    initAceEditor();
                }
            }
            $out .= '    <textarea name="' . html($input_name) . '" id="' . html($input_id) . '" rows="' . $rows . '" class="form-control form-control-sm' . $wysiwyg_class . $code_class . ' ' . html($class) . '"' . $mode_attr . ' placeholder="' . html($placeholder) . '"' . $height . '>' . html($value) . '</textarea>' . LF;
            break;

        case 'option':
        case 'select':
            $values = $field_def['values'] ?? array();
            $out .= '    <select name="' . html($input_name) . '" id="' . html($input_id) . '" class="form-select form-select-sm ' . html($class) . '">' . LF;
            if (is_array($values)) {
                foreach ($values as $val_k => $val_label) {
                    if (function_exists('i18n_substitute_text')) {
                        $val_label = i18n_substitute_text($val_label);
                    }
                    $selected = ((string)$value === (string)$val_k) ? ' selected="selected"' : '';
                    $out .= '      <option value="' . html($val_k) . '"' . $selected . '>' . html($val_label) . '</option>' . LF;
                }
            }
            $out .= '    </select>' . LF;
            break;

        case 'bool':
            $checked = !empty($value) ? ' checked="checked"' : '';
            $out .= '    <div class="form-check">' . LF;
            $out .= '      <input type="checkbox" name="' . html($input_name) . '" id="' . html($input_id) . '" value="1" class="form-check-input ' . html($class) . '"' . $checked . '>' . LF;
            $out .= '      <label class="form-check-label" for="' . html($input_id) . '">' . html($legend) . '</label>' . LF;
            $out .= '    </div>' . LF;
            break;

        case 'int':
        case 'float':
            $min  = isset($field_def['min']) ? ' min="' . (float)$field_def['min'] . '"' : '';
            $max  = isset($field_def['max']) ? ' max="' . (float)$field_def['max'] . '"' : '';
            $step = isset($field_def['step']) ? ' step="' . (float)$field_def['step'] . '"' : ($type === 'float' ? ' step="any"' : ' step="1"');
            $out .= '    <input type="number" name="' . html($input_name) . '" id="' . html($input_id) . '" value="' . html($value) . '" class="form-control form-control-sm ' . html($class) . '" placeholder="' . html($placeholder) . '"' . $min . $max . $step . '>' . LF;
            break;

        case 'color':
            $out .= '    <div class="input-group input-group-sm">' . LF;
            $out .= '      <input type="color" name="' . html($input_name) . '" id="' . html($input_id) . '" value="' . html($value ?: '#000000') . '" class="form-control form-control-sm form-control-color ' . html($class) . '" style="max-width: 60px;">' . LF;
            $out .= '      <input type="text" value="' . html($value) . '" class="form-control form-control-sm" placeholder="#ffffff" oninput="document.getElementById(\'' . html($input_id) . '\').value = this.value;">' . LF;
            $out .= '    </div>' . LF;
            break;

        case 'date':
            $out .= '    <input type="date" name="' . html($input_name) . '" id="' . html($input_id) . '" value="' . html($value) . '" class="form-control form-control-sm ' . html($class) . '">' . LF;
            break;

        case 'file':
        case 'image':
            $file_val = is_array($value) ? ($value['file_id'] ?? $value['image_id'] ?? '') : (string)$value;
            $opt = ($type === 'image') ? 1 : 4;
            $out .= '    <div class="input-group input-group-sm">' . LF;
            $out .= '      <input type="text" name="' . html($input_name) . '" id="' . html($input_id) . '" value="' . html($file_val) . '" class="form-control form-control-sm ' . html($class) . '" placeholder="' . html($placeholder ?: ($type === 'image' ? 'Image ID / Path' : 'File ID / Path')) . '">' . LF;
            $out .= '      ' . LF;
            $out .= '        <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="filebrowser.php?opt=' . $opt . '&amp;target=nolist"><i class="fa fa-folder-open"></i></button>' . LF;
            $out .= '      ' . LF;
            $out .= '    </div>' . LF;
            break;

        case 'str':
        case 'url':
        default:
            $maxlength = !empty($field_def['maxlength']) ? ' maxlength="' . (int)$field_def['maxlength'] . '"' : '';
            $input_type = ($type === 'url') ? 'url' : 'text';
            $out .= '    <input type="' . $input_type . '" name="' . html($input_name) . '" id="' . html($input_id) . '" value="' . html($value) . '" class="form-control form-control-sm ' . html($class) . '" placeholder="' . html($placeholder) . '"' . $maxlength . '>' . LF;
            break;
    }

    $out .= '  </div>' . LF;
    $out .= '</div>' . LF;

    return $out;
}

/**
 * Replace template tags for a single field in frontend rendering
 *
 * @param string $template
 * @param string $field_key
 * @param array $field_def
 * @param mixed $value
 * @param string $prefix
 * @return string
 */
function custom_field_replace_tags($template, $field_key, $field_def, $value, $prefix = 'CUSTCTP_') {
    $tag = strtoupper($field_key);
    $prefixed_tag = $prefix . $tag;

    $type   = $field_def['type'] ?? 'str';
    $render = $field_def['render'] ?? '';

    // Render value according to field settings
    $rendered_value = '';
    if ($type === 'bool') {
        $rendered_value = !empty($value) ? ' ' : '';
    } elseif ($type === 'option' || $type === 'select') {
        $rendered_value = (string)$value;
        if (!empty($field_def['values']) && is_array($field_def['values'])) {
            foreach ($field_def['values'] as $opt_k => $opt_label) {
                $opt_tag = $tag . '_' . strtoupper($opt_k);
                $opt_prefixed_tag = $prefixed_tag . '_' . strtoupper($opt_k);
                $opt_match = ((string)$value === (string)$opt_k);
                $template = render_cnt_template($template, $opt_tag, $opt_match ? html($opt_k) : '');
                $template = render_cnt_template($template, $opt_prefixed_tag, $opt_match ? html($opt_k) : '');
            }
        }
    } elseif ($render === 'markdown' && function_exists('parse_markdown')) {
        $rendered_value = parse_markdown((string)$value);
    } elseif ($render === 'wysiwyg' || $render === 'html') {
        $rendered_value = (string)$value;
    } elseif ($render === 'plain' && function_exists('plaintext_htmlencode')) {
        $rendered_value = plaintext_htmlencode((string)$value);
    } else {
        $rendered_value = html((string)$value);
    }

    // Replace {FIELD} and {PREFIX_FIELD}
    $template = str_replace(
        array('{' . $tag . '}', '{' . $prefixed_tag . '}'),
        $rendered_value,
        $template
    );

    // Conditional blocks: [FIELD]...[/FIELD] and [PREFIX_FIELD]...[/PREFIX_FIELD]
    $has_value = ($type === 'bool') ? !empty($value) : ($value !== '' && $value !== null);
    $template = render_cnt_template($template, $tag, $has_value ? $rendered_value : '');
    $template = render_cnt_template($template, $prefixed_tag, $has_value ? $rendered_value : '');

    return $template;
}

/**
 * Generate starter template code snippet with all placeholder tags
 *
 * @param array $cpt
 * @return string
 */
function custom_field_generate_template_scaffold($cpt) {
    $mode = $cpt['cpt_mode'] ?? 'repeater';
    $fields = $cpt['fields'] ?? array();

    $code = '<!-- ' . html($cpt['cpt_title'] ?? 'Custom Content Part') . ' -->' . LF;

    if ($mode === 'repeater') {
        $code .= '<!--CUSTOM_HEADER_START//-->' . LF;
        $code .= '<div class="custom-cpt custom-' . html($cpt['cpt_key'] ?? 'block') . '[ATTR_CLASS] {ATTR_CLASS}[/ATTR_CLASS]"[ATTR_ID] id="{ATTR_ID}"[/ATTR_ID]>' . LF;
        $code .= '  [TITLE]<h3>{TITLE}</h3>[/TITLE]' . LF;
        $code .= '  [SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]' . LF;
        $code .= '  <div class="row">' . LF;
        $code .= '<!--CUSTOM_HEADER_END//-->' . LF . LF;

        $code .= '<!--CUSTOM_ENTRY_START//-->' . LF;
        $code .= '    <div class="col-md-4 mb-4 custom-cpt-item">' . LF;
    } else {
        $code .= '<div class="custom-cpt custom-' . html($cpt['cpt_key'] ?? 'block') . '[ATTR_CLASS] {ATTR_CLASS}[/ATTR_CLASS]"[ATTR_ID] id="{ATTR_ID}"[/ATTR_ID]>' . LF;
        $code .= '  [TITLE]<h3>{TITLE}</h3>[/TITLE]' . LF;
        $code .= '  [SUBTITLE]<h4>{SUBTITLE}</h4>[/SUBTITLE]' . LF;
    }

    foreach ($fields as $key => $field) {
        $tag = strtoupper($key);
        $type = $field['type'] ?? 'str';
        $legend = $field['legend'] ?? ($field['label'] ?? $key);

        if ($type === 'bool') {
            $code .= '      [' . $tag . ']<div class="badge text-bg-success">' . html($legend) . '</div>[/' . $tag . ']' . LF;
        } elseif ($type === 'image') {
            $code .= '      [' . $tag . ']<img src="{' . $tag . '}" alt="" class="img-fluid mb-2">[/' . $tag . ']' . LF;
        } elseif ($type === 'url') {
            $code .= '      [' . $tag . ']<a href="{' . $tag . '}" class="btn btn-primary">' . html($legend) . '</a>[/' . $tag . ']' . LF;
        } elseif ($type === 'textarea') {
            $code .= '      [' . $tag . ']<div class="' . html($key) . '-content">{' . $tag . '}</div>[/' . $tag . ']' . LF;
        } else {
            $code .= '      [' . $tag . ']<div class="' . html($key) . '"><strong>' . html($legend) . ':</strong> {' . $tag . '}</div>[/' . $tag . ']' . LF;
        }
    }

    if ($mode === 'repeater') {
        $code .= '    </div>' . LF;
        $code .= '<!--CUSTOM_ENTRY_END//-->' . LF . LF;

        $code .= '<!--CUSTOM_FOOTER_START//-->' . LF;
        $code .= '  </div>' . LF;
        $code .= '</div>' . LF;
        $code .= '<!--CUSTOM_FOOTER_END//-->' . LF;
    } else {
        $code .= '</div>' . LF;
    }

    return $code;
}
