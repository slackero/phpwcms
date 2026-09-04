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

$template_lang_dir = PHPWCMS_TEMPLATE . 'template_lang/';

// Collect available languages
$allowed_langs = [];
if (!empty($phpwcms['allowed_lang']) && is_array($phpwcms['allowed_lang'])) {
    foreach ($phpwcms['allowed_lang'] as $al) {
        $clean_al = sanitize_language_code($al);
        if ($clean_al !== '') {
            $allowed_langs[$clean_al] = $clean_al;
        }
    }
}
$default_lang = !empty($phpwcms['default_lang']) ? sanitize_language_code($phpwcms['default_lang']) : 'en';
if (empty($default_lang)) {
    $default_lang = 'en';
}
$allowed_langs[$default_lang] = $default_lang;

// Scan existing files in template_lang/
if (is_dir($template_lang_dir)) {
    $files = scandir($template_lang_dir);
    if ($files) {
        foreach ($files as $f) {
            if (substr($f, -4) === '.php') {
                $code = substr($f, 0, -4);
                $code = sanitize_language_code($code);
                if ($code !== '') {
                    $allowed_langs[$code] = $code;
                }
            }
        }
    }
}
ksort($allowed_langs);

// Active language
$current_lang = isset($_GET['lang']) ? sanitize_language_code($_GET['lang']) : $default_lang;
if (!isset($allowed_langs[$current_lang])) {
    $current_lang = $default_lang;
}

$action_msg = '';
$action_error = '';

// Handle Actions
// 1. Delete token
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['token'])) {
    if (validate_csrf_get_token()) {
        $del_token = rawurldecode((string)$_GET['token']);
        $all_tokens = template_lang_load_file($current_lang);
        if (array_key_exists($del_token, $all_tokens)) {
            unset($all_tokens[$del_token]);
            if (template_lang_save_file($current_lang, $all_tokens)) {
                $action_msg = $BL['be_admin_template_lang_deleted'] ?? 'Translation token deleted successfully.';
            } else {
                $action_error = 'Error saving language file.';
            }
        }
    }
}

// 2. Add single token
if (!empty($_POST['add_token'])) {
    $new_token_key = slweg(trim((string)($_POST['new_token_key'] ?? '')));
    $new_token_val = slweg(trim((string)($_POST['new_token_val'] ?? '')));
    if ($new_token_key !== '') {
        $all_tokens = template_lang_load_file($current_lang);
        $all_tokens[$new_token_key] = ($new_token_val !== '') ? $new_token_val : $new_token_key;
        if (template_lang_save_file($current_lang, $all_tokens)) {
            $action_msg = $BL['be_admin_template_lang_token_added'] ?? 'New translation token added successfully.';
        } else {
            $action_error = 'Error saving language file.';
        }
    }
}

// 3. Save modified tokens list
if (!empty($_POST['save_translations']) && isset($_POST['tokens']) && is_array($_POST['tokens'])) {
    $existing_tokens = template_lang_load_file($current_lang);
    foreach ($_POST['tokens'] as $t_key_b64 => $t_val) {
        $orig_key = base64_decode($t_key_b64);
        if ($orig_key !== false && array_key_exists($orig_key, $existing_tokens)) {
            $existing_tokens[$orig_key] = slweg((string)$t_val);
        }
    }
    if (template_lang_save_file($current_lang, $existing_tokens)) {
        $action_msg = $BL['be_admin_template_lang_saved'] ?? 'Translations saved successfully.';
    } else {
        $action_error = 'Error saving language file.';
    }
}

// Read current tokens for active language
$active_tokens = template_lang_load_file($current_lang);

// Read default lang tokens if inspecting another language to help find missing keys
$default_tokens = ($current_lang !== $default_lang) ? template_lang_load_file($default_lang) : [];

// Merge all keys from active and default language
$all_token_keys = array_unique(array_merge(array_keys($active_tokens), array_keys($default_tokens)));
natcasesort($all_token_keys);

// Filtering & Search
$search_query = trim((string)($_GET['q'] ?? ''));
$status_filter = trim((string)($_GET['status'] ?? 'all')); // 'all', 'missing', 'translated'
$per_page = isset($_GET['per_page']) ? max(10, min(250, (int)$_GET['per_page'])) : 25;
$current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$filtered_keys = [];
foreach ($all_token_keys as $key) {
    $val = isset($active_tokens[$key]) ? $active_tokens[$key] : '';
    $is_missing = ($val === '' || (!empty($default_tokens[$key]) && $val === $default_tokens[$key] && $current_lang !== $default_lang));

    if ($status_filter === 'missing' && !$is_missing) {
        continue;
    }
    if ($status_filter === 'translated' && $is_missing) {
        continue;
    }

    if ($search_query !== '') {
        $match_key = (stripos($key, $search_query) !== false);
        $match_val = (stripos($val, $search_query) !== false);
        $match_def = (isset($default_tokens[$key]) && stripos($default_tokens[$key], $search_query) !== false);
        if (!$match_key && !$match_val && !$match_def) {
            continue;
        }
    }

    $filtered_keys[] = $key;
}

$total_items = count($filtered_keys);
$total_pages = max(1, (int)ceil($total_items / $per_page));
if ($current_page > $total_pages) {
    $current_page = $total_pages;
}
$offset = ($current_page - 1) * $per_page;
$page_keys = array_slice($filtered_keys, $offset, $per_page);

$base_url = 'phpwcms.php?' . get_token_get_string() . '&amp;do=admin&amp;p=17&amp;lang=' . urlencode($current_lang);
if ($search_query !== '') {
    $base_url .= '&amp;q=' . urlencode($search_query);
}
if ($status_filter !== 'all') {
    $base_url .= '&amp;status=' . urlencode($status_filter);
}
if ($per_page !== 25) {
    $base_url .= '&amp;per_page=' . $per_page;
}

?>
<div class="row align-items-center mb-3">
    <div class="col col-sm-auto text-center text-sm-start">
        <h1 class="mb-0"><?php echo html($BL['be_admin_template_lang'] ?? 'Template Translations'); ?></h1>
    </div>
    <div class="col-12 col-sm text-center text-sm-end mt-2 mt-sm-0">
        <span class="badge text-bg-secondary py-1 px-2"><?php echo html($total_items . ' ' . ($BL['be_admin_template_lang_items'] ?? 'Items')); ?></span>
    </div>
</div>

<p class="text-muted"><?php echo $BL['be_admin_template_lang_desc'] ?? 'Manage automatic <code>@@Text@@</code> frontend translations stored under <code>template/template_lang</code>.'; ?></p>

<?php if (!empty($action_msg)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo html($action_msg); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (!empty($action_error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo html($action_error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Filter & Toolbar Card -->
<div class="card mb-3">
    <div class="card-body py-2">
        <?php $csrf_get = get_token_get_array(); ?>
        <form method="get" action="phpwcms.php" class="d-flex flex-wrap align-items-center" data-csrf="off">
            <input type="hidden" name="<?php echo html($csrf_get['name']); ?>" value="<?php echo html($csrf_get['value']); ?>" />
            <input type="hidden" name="do" value="admin" />
            <input type="hidden" name="p" value="17" />

            <!-- Language selector -->
            <label class="me-2 fw-bold" for="sel_lang"><i class="fa-solid fa-globe me-1"></i> <?php echo html($BL['login_lang'] ?? 'Language'); ?>:</label>
            <select name="lang" id="sel_lang" class="form-select form-select-sm w-auto me-3 mb-2 mb-md-0" onchange="this.form.submit();">
                <?php foreach ($allowed_langs as $code): ?>
                    <?php
                        $code_upper = strtoupper($code);
                        $lang_name = $BL[$code_upper] ?? '';
                        $opt_label = $code_upper . ($lang_name !== '' ? ' - ' . $lang_name : '');
                        if ($code === $default_lang) {
                            $opt_label .= ' (' . ($BL['be_admin_tmpl_default'] ?? 'Default') . ')';
                        }
                    ?>
                    <option value="<?php echo html($code); ?>" <?php is_selected($code, $current_lang); ?>>
                        <?php echo html($opt_label); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Status filter -->
            <label class="me-2 fw-bold" for="sel_status"><?php echo html($BL['be_admin_template_lang_filter'] ?? 'Filter'); ?>:</label>
            <select name="status" id="sel_status" class="form-select form-select-sm w-auto me-3 mb-2 mb-md-0" onchange="this.form.submit();">
                <option value="all" <?php is_selected($status_filter, 'all'); ?>><?php echo html($BL['be_admin_template_lang_all'] ?? 'All'); ?></option>
                <option value="missing" <?php is_selected($status_filter, 'missing'); ?>><?php echo html($BL['be_admin_template_lang_missing'] ?? 'Missing translation'); ?></option>
                <option value="translated" <?php is_selected($status_filter, 'translated'); ?>><?php echo html($BL['be_admin_template_lang_translated'] ?? 'Translated'); ?></option>
            </select>

            <!-- Search input -->
            <div class="input-group input-group-sm me-3 mb-2 mb-md-0 flex-grow-1" style="max-width: 320px;">
                <input type="text" name="q" class="form-control" placeholder="<?php echo html($BL['be_admin_template_lang_search'] ?? 'Search tokens or translations…'); ?>" value="<?php echo html($search_query); ?>" />
                <button class="btn btn-secondary" type="submit"><i class="fa-solid fa-search"></i></button>
                <?php if ($search_query !== '' || $status_filter !== 'all'): ?>
                    <a href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=17&amp;lang=<?php echo urlencode($current_lang); ?>" class="btn btn-outline-secondary" title="<?php echo html($BL['be_cnt_delete'] ?? 'Reset'); ?>"><i class="fa-solid fa-times"></i></a>
                <?php endif; ?>
            </div>

            <!-- Per page selector -->
            <label class="me-2 text-muted" for="sel_per_page"><?php echo html($BL['be_subnav_msg_subscribers'] ?? 'Per page'); ?>:</label>
            <select name="per_page" id="sel_per_page" class="form-select form-select-sm w-auto me-2 mb-2 mb-md-0" onchange="this.form.submit();">
                <option value="25" <?php is_selected($per_page, 25); ?>>25</option>
                <option value="50" <?php is_selected($per_page, 50); ?>>50</option>
                <option value="100" <?php is_selected($per_page, 100); ?>>100</option>
                <option value="250" <?php is_selected($per_page, 250); ?>>250</option>
            </select>
        </form>
    </div>
</div>

<?php
$current_lang_upper = strtoupper($current_lang);
$current_lang_name = $BL[$current_lang_upper] ?? '';
$default_lang_upper = strtoupper($default_lang);
$default_lang_name = $BL[$default_lang_upper] ?? '';
$current_flag_img = get_language_flag_img($current_lang, 'me-1');
$default_flag_img = get_language_flag_img($default_lang, 'me-1');
?>

<!-- Main Table Card -->
<form id="form_translations" method="post" action="phpwcms.php?do=admin&amp;p=17&amp;lang=<?php echo urlencode($current_lang); ?>&amp;page=<?php echo $current_page; ?>&amp;status=<?php echo urlencode($status_filter); ?>&amp;q=<?php echo urlencode($search_query); ?>&amp;per_page=<?php echo $per_page; ?>">
<input type="hidden" name="save_translations" value="1" />
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0">
            <i class="fa-solid fa-language"></i>
            <code>template/template_lang/<?php echo html($current_lang); ?>.php</code>
            <span class="badge text-bg-info ms-2 fw-normal badge-align badge-align-t2"><?php echo $current_flag_img; ?><?php echo html($current_lang_upper . ($current_lang_name !== '' ? ' - ' . $current_lang_name : '')); ?></span>
        </h2>
        <?php if (!empty($page_keys)): ?>
            <button type="submit" name="btn_save_top" value="1" class="btn btn-sm btn-blue">
                <i class="fa-solid fa-check me-1"></i> <?php echo html($BL['be_save_btn'] ?? 'Save'); ?>
            </button>
        <?php endif; ?>
    </div>

    <div class="card-body p-0">
        <?php if (empty($page_keys)): ?>
            <div class="p-4 text-center text-muted">
                <i class="fa-solid fa-info-circle fa-2x mb-2"></i>
                <p class="mb-0"><?php echo html($BL['be_admin_template_lang_empty'] ?? 'No translation tokens found.'); ?></p>
            </div>
        <?php else: ?>
            <table class="table table-striped table-sm table-hover table-valign-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40%;"><?php echo html($BL['be_admin_template_lang_token'] ?? 'Token / Default Text'); ?></th>
                        <th style="width: 52%;"><?php echo html($BL['be_admin_template_lang_trans'] ?? 'Translation'); ?> (<?php echo $current_flag_img; ?><?php echo html($current_lang_upper . ($current_lang_name !== '' ? ' - ' . $current_lang_name : '')); ?>)</th>
                        <th class="text-end" style="width: 8%;"><?php echo html($BL['be_article_action'] ?? 'Action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($page_keys as $t_key): ?>
                        <?php
                            $val = isset($active_tokens[$t_key]) ? $active_tokens[$t_key] : '';
                            $b64_key = base64_encode($t_key);
                            $is_missing = ($val === '' || (!empty($default_tokens[$t_key]) && $val === $default_tokens[$t_key] && $current_lang !== $default_lang));
                        ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-break">
                                    <code>@@<?php echo html($t_key); ?>@@</code>
                                </div>
                                <?php if ($current_lang !== $default_lang && isset($default_tokens[$t_key])): ?>
                                    <div class="small text-muted text-break mt-1">
                                        <span class="badge text-bg-light border me-1 badge-align"><?php echo $default_flag_img; ?><?php echo html($default_lang_upper . ($default_lang_name !== '' ? ' - ' . $default_lang_name : '')); ?></span> <?php echo html($default_tokens[$t_key]); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="text"
                                       name="tokens[<?php echo $b64_key; ?>]"
                                       class="form-control form-control-sm <?php echo $is_missing ? 'border-warning' : ''; ?>"
                                       value="<?php echo html($val); ?>"
                                       placeholder="<?php echo html($t_key); ?>" />
                            </td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-danger btn-sm"
                                   role="button"
                                   data-bs-toggle="tooltip"
                                   href="phpwcms.php?<?php echo get_token_get_string(); ?>&amp;do=admin&amp;p=17&amp;lang=<?php echo urlencode($current_lang); ?>&amp;action=delete&amp;token=<?php echo rawurlencode($t_key); ?>&amp;page=<?php echo $current_page; ?>&amp;status=<?php echo urlencode($status_filter); ?>&amp;q=<?php echo urlencode($search_query); ?>"
                                   title="<?php echo html($BL['be_cnt_delete'] ?? 'Delete'); ?>"
                                   data-confirm-danger="<?php echo html(($BL['be_cnt_delete'] ?? 'Delete') . ":\n[@@" . $t_key . '@@] ?'); ?>">
                                    <i class="fa-regular fa-trash-alt" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Card Footer with Pagination & Save Button -->
    <div class="card-footer d-flex flex-wrap justify-content-between align-items-center">
        <div>
            <?php if (!empty($page_keys)): ?>
                <button type="submit" name="btn_save_bottom" value="1" class="btn btn-sm btn-blue me-2">
                    <i class="fa-solid fa-check me-1"></i> <?php echo html($BL['be_save_btn'] ?? 'Save'); ?>
                </button>
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav aria-label="Pagination">
                <ul class="pagination pagination-sm mb-0">
                    <!-- Previous page -->
                    <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo $base_url . '&amp;page=' . ($current_page - 1); ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <?php
                    $start_p = max(1, $current_page - 3);
                    $end_p   = min($total_pages, $current_page + 3);

                    if ($start_p > 1) {
                        echo '<li class="page-item"><a class="page-link" href="' . $base_url . '&amp;page=1">1</a></li>';
                        if ($start_p > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                        }
                    }

                    for ($i = $start_p; $i <= $end_p; $i++) {
                        $active_cls = ($i === $current_page) ? ' active' : '';
                        echo '<li class="page-item' . $active_cls . '"><a class="page-link" href="' . $base_url . '&amp;page=' . $i . '">' . $i . '</a></li>';
                    }

                    if ($end_p < $total_pages) {
                        if ($end_p < $total_pages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="' . $base_url . '&amp;page=' . $total_pages . '">' . $total_pages . '</a></li>';
                    }
                    ?>

                    <!-- Next page -->
                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo $base_url . '&amp;page=' . ($current_page + 1); ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>
</form>

<!-- Add New Token Card -->
<div class="card mb-4">
    <div class="card-header">
        <h2 class="mb-0"><i class="fa-solid fa-plus-circle"></i> <?php echo html($BL['be_admin_template_lang_add'] ?? 'Add Token'); ?></h2>
    </div>
    <div class="card-body">
        <form method="post" action="phpwcms.php?do=admin&amp;p=17&amp;lang=<?php echo urlencode($current_lang); ?>" class="row g-2 align-items-end">
            <div class="col-12 col-md-5 mb-2 mb-md-0">
                <label for="new_token_key" class="fw-bold"><?php echo html($BL['be_admin_template_lang_token'] ?? 'Token / Default Text'); ?>:</label>
                <input type="text" name="new_token_key" id="new_token_key" class="form-control form-control-sm" placeholder="e.g. Read more" required />
            </div>
            <div class="col-12 col-md-5 mb-2 mb-md-0">
                <label for="new_token_val" class="fw-bold"><?php echo html($BL['be_admin_template_lang_trans'] ?? 'Translation'); ?> (<?php echo $current_flag_img; ?><?php echo html($current_lang_upper . ($current_lang_name !== '' ? ' - ' . $current_lang_name : '')); ?>):</label>
                <input type="text" name="new_token_val" id="new_token_val" class="form-control form-control-sm" placeholder="e.g. Mehr erfahren" />
            </div>
            <div class="col-12 col-md-2">
                <button type="submit" name="add_token" value="1" class="btn btn-sm btn-success w-100">
                    <i class="fa-solid fa-plus me-1"></i> <?php echo html($BL['be_admin_template_lang_add'] ?? 'Add Token'); ?>
                </button>
            </div>
        </form>
    </div>
</div>
