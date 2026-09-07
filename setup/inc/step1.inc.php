<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

if (!defined('PHPWCMS_SETUP')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}

?>
<h2 class="h4 text-primary fw-normal mb-3">3. Database</h2>

<?php if (!empty($db_missing)): ?>
    <div class="alert alert-info mb-4 border">
        <h5 class="alert-heading fw-bold mb-2"><i class="fa fa-database"></i> Database "<?php echo html_specialchars($phpwcms['db_table']) ?>" does not exist</h5>
        <p class="mb-2">Your database server credentials are valid, but the database <code><?php echo html_specialchars($phpwcms['db_table']) ?></code> has not been created yet.</p>
        <div class="form-check mt-2">
            <input type="checkbox" name="create_database" class="form-check-input" id="create_database" value="1" checked="checked" />
            <label class="form-check-label fw-bold text-dark" for="create_database">Create database "<?php echo html_specialchars($phpwcms['db_table']) ?>" now (UTF-8 / utf8mb4)</label>
        </div>
    </div>
    <?php $_SESSION['admin_set'] = false; ?>
<?php elseif (isset($_POST['dbsavesubmit']) && $err): ?>
    <div class="alert alert-danger mb-4">
        <div><i class="fa fa-exclamation-triangle"></i> Please check your database connection settings below.</div>
        <?php if (!empty($db_error_message)): ?>
            <div class="mt-2 small font-monospace fw-bold bg-white p-2 border rounded text-danger"><?php echo html_specialchars($db_error_message) ?></div>
        <?php endif; ?>
    </div>
    <?php $_SESSION['admin_set'] = false; ?>
<?php endif; ?>

<?php if (!empty($db_created_notice)): ?>
    <div class="alert alert-success mb-4"><i class="fa fa-check-circle"></i> Database "<strong><?php echo html_specialchars($phpwcms['db_table']) ?></strong>" created successfully.</div>
<?php endif; ?>

<?php
$current_db_host = !empty($phpwcms['db_host']) && $phpwcms['db_host'] !== 'localhost' ? $phpwcms['db_host'] : detect_mysql_host($phpwcms['db_host'] ?? 'localhost');
$phpwcms['db_host'] = $current_db_host;
$detected_db_port = detect_mysql_port($current_db_host, $phpwcms['db_port'] ?? null);
$display_db_port = (!empty($phpwcms['db_port']) && (int)$phpwcms['db_port'] !== 3306) ? (int)$phpwcms['db_port'] : ($detected_db_port !== 3306 ? $detected_db_port : '');
?>
<form action="setup.php?step=1" method="post" autocomplete="off">

    <div class="card mb-4 border">
        <div class="card-header bg-light fw-bold">Database Server Connection</div>
        <div class="card-body">
            <div class="form-group row">
                <label for="db_host" class="col-sm-3 col-form-label fw-bold">Host &amp; Port</label>
                <div class="col-sm-6 d-flex">
                    <input name="db_host" type="text" class="form-control me-2" id="db_host" value="<?php echo html_specialchars($phpwcms['db_host']) ?>" placeholder="localhost" />
                    <input name="db_port" type="text" class="form-control" id="db_port" style="max-width: 90px;" value="<?php echo html_specialchars($display_db_port) ?>" placeholder="3306" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center"><?php echo ($detected_db_port !== 3306) ? 'Auto-detected port: ' . $detected_db_port : 'Default: localhost / 3306' ?></div>
            </div>

            <div class="form-group row">
                <label for="db_user" class="col-sm-3 col-form-label fw-bold">DB Username</label>
                <div class="col-sm-6">
                    <input name="db_user" type="text" class="form-control" id="db_user" value="<?php echo html_specialchars($phpwcms['db_user']) ?>" placeholder="database user" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Database user name</div>
            </div>

            <div class="form-group row">
                <label for="db_pass" class="col-sm-3 col-form-label fw-bold">DB Password</label>
                <div class="col-sm-6">
                    <div class="form-password">
                        <input name="db_pass" type="password" class="form-control" id="db_pass" value="<?php echo html_specialchars($phpwcms['db_pass']) ?>" placeholder="database password" autocomplete="new-password" />
                        <button type="button" class="form-password-action" data-coreui-toggle="password" aria-pressed="false" aria-label="Toggle password visibility">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Database password</div>
            </div>

            <div class="form-group row">
                <label for="db_table" class="col-sm-3 col-form-label fw-bold">Database Name</label>
                <div class="col-sm-6">
                    <input name="db_table" type="text" class="form-control" id="db_table" value="<?php echo html_specialchars($phpwcms['db_table']) ?>" placeholder="database name" maxlength="255" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Will be created if missing</div>
            </div>

            <div class="form-group row">
                <label for="db_prepend" class="col-sm-3 col-form-label fw-bold">Table Prefix</label>
                <div class="col-sm-6">
                    <input name="db_prepend" type="text" class="form-control" id="db_prepend" value="<?php echo html_specialchars($phpwcms['db_prepend']) ?>" placeholder="optional" maxlength="10" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Table prefix (e.g. <code>my_</code>)</div>
            </div>

            <div class="form-group row mb-0">
                <label for="db_pers" class="col-sm-3 col-form-label fw-bold">Persistent Connection</label>
                <div class="col-sm-6">
                    <div class="form-check pt-2">
                        <input name="db_pers" type="checkbox" class="form-check-input" id="db_pers" value="1" <?php if (!empty($phpwcms['db_pers'])) echo 'checked="checked"' ?> />
                        <label class="form-check-label" for="db_pers">Enable persistent connection</label>
                    </div>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: Enabled (1)</div>
            </div>

        </div>
    </div>

<?php if (!empty($db_additional)): ?>
    <?php
    $db_collations = get_db_collations($db);
    $server_default_collation = get_server_default_collation($db);

    $common_defaults = array(
        'utf8mb4_uca1400_ai_ci',
        'utf8mb4_0900_ai_ci',
        'utf8mb4_unicode_520_ci',
        'utf8mb4_unicode_ci',
        'utf8mb4_general_ci'
    );

    $chosen_default = '';
    if (!empty($server_default_collation) && in_array($server_default_collation, $db_collations, true)) {
        $chosen_default = $server_default_collation;
    } else {
        foreach ($common_defaults as $cd) {
            if (in_array($cd, $db_collations, true)) {
                $chosen_default = $cd;
                break;
            }
        }
    }
    if (empty($chosen_default) && !empty($db_collations)) {
        $chosen_default = reset($db_collations);
    }

    $selected_collation = !empty($_POST['collation'])
        ? $_POST['collation']
        : (!empty($phpwcms['db_collation']) && in_array($phpwcms['db_collation'], $db_collations, true) && $phpwcms['db_collation'] !== 'utf8mb4_general_ci'
            ? $phpwcms['db_collation']
            : $chosen_default);

    if (!empty($selected_collation) && !in_array($selected_collation, $db_collations, true)) {
        array_unshift($db_collations, $selected_collation);
    }
    $browser_langs = detect_browser_languages();
    $clean_langs = get_clean_languages();
    $valid_browser_langs = array_values(array_intersect($browser_langs, array_keys($clean_langs)));

    if (!empty($phpwcms['allowed_lang']) && is_array($phpwcms['allowed_lang'])) {
        $selected_allowed = $phpwcms['allowed_lang'];
    } elseif (!empty($valid_browser_langs)) {
        $selected_allowed = $valid_browser_langs;
    } else {
        $selected_allowed = ['en'];
    }

    if (!empty($phpwcms['default_lang'])) {
        $selected_default = $phpwcms['default_lang'];
    } elseif (!empty($valid_browser_langs)) {
        $selected_default = $valid_browser_langs[0];
    } else {
        $selected_default = 'en';
    }
    ?>
    <div class="card mb-4 border">
        <div class="card-header bg-light fw-bold">Language &amp; Charset Settings (MySQL v<?php echo html_specialchars($row[0]) ?>)</div>
        <div class="card-body">
            <div class="form-group row">
                <label for="default_lang" class="col-sm-3 col-form-label fw-bold">Default Language</label>
                <div class="col-sm-6">
                    <select name="default_lang" class="form-select" id="default_lang" onchange="var cb = document.getElementById('lang_' + this.value); if (cb) cb.checked = true;">
                        <?php echo render_default_language_options($selected_default); ?>
                    </select>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Primary frontend language</div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label fw-bold">Allowed Languages</label>
                <div class="col-sm-6">
                    <div class="border rounded p-3 bg-white" style="max-height: 200px; overflow-y: auto;">
                        <?php echo render_language_checkboxes($selected_allowed); ?>
                    </div>
                    <div class="form-text text-muted small mt-1">Check all languages supported on your frontend. Allowed languages can also be configured directly in <code>conf.inc.php</code> at any time.</div>
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-start pt-2">
                    <?php if (!empty($valid_browser_langs)): ?>
                        <div>Detected browser language<?php echo count($valid_browser_langs) > 1 ? 's' : '' ?>: <code><?php echo html_specialchars(implode(', ', $valid_browser_langs)) ?></code></div>
                    <?php endif; ?>
                    <div class="text-muted small mt-1">Can be modified directly in <code>include/config/conf.inc.php</code>.</div>
                </div>
            </div>

            <div class="form-group row mb-0">
                <label for="collation" class="col-sm-3 col-form-label fw-bold">Collation</label>
                <div class="col-sm-6">
                    <select name="collation" class="form-select" id="collation">
                    <?php
                    foreach ($db_collations as $col) {
                        echo '<option value="' . html_specialchars($col) . '"';
                        if ($selected_collation === $col) {
                            echo ' selected="selected"';
                        }
                        echo '>' . html_specialchars($col) . '</option>';
                    }
                    ?>
                    </select>
                    <small class="form-text text-muted mt-2">All new installations strictly use <strong>UTF-8</strong> (Unicode) with <strong>utf8mb4</strong> database character set.</small>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($db_init)): ?>
    <div class="card mb-4 border">
        <div class="card-header bg-light fw-bold">Database Schema Initialization</div>
        <div class="card-body">
            <?php
            if (empty($db_no_create) && !empty($_db_prepend_error) && isset($_POST['db_sql_hidden'])) {
                echo '<div class="alert alert-warning mb-3">phpwcms tables already exist in chosen database. Consider changing the table prefix.</div>';
                $_SESSION['admin_set'] = false;
            }

            if (isset($db_create_err) && count($db_create_err)) {
                echo '<div class="alert alert-danger mb-3">Errors while creating initial tables. Please resolve manually:<pre class="bg-dark text-light p-2 mt-2 rounded">' . html_specialchars(implode(";\n\n", $db_create_err) . ';') . '</pre></div>';
                $_SESSION['admin_set']  = false;
                $sql_data               = false;
                $db_sql                 = false;

                $_brand_prefix = !empty($phpwcms['brand_table_prefix']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $phpwcms['brand_table_prefix']) : 'phpwcms';
                if ($_brand_prefix === '') {
                    $_brand_prefix = 'phpwcms';
                }
                $_db_prepend = ($phpwcms['db_prepend'] ? mysqli_real_escape_string($db, $phpwcms['db_prepend']) . '_' : '') . $_brand_prefix . '_';
                $check = _dbQuery("SHOW TABLES LIKE '" . $_db_prepend . "%'");

                if ($check && count($check)) {
                    $sql_data   = false;
                    $db_sql     = false;
                    $db_fine    = true;
                    $_SESSION['admin_set'] = true;
                    echo '<div class="alert alert-success mb-0"><i class="fa fa-check-circle"></i> Initial phpwcms database tables created successfully.<input type="hidden" name="db_sql_hidden" value="1" /></div>';
                } else {
                    $_SESSION['admin_set']  = false;
                    $sql_data               = false;
                    $db_sql                 = false;
                    echo '<div class="alert alert-danger mb-0">No phpwcms database tables found. Please check setup!<input type="hidden" name="db_sql_hidden" value="1" /></div>';
                }
            }

            if (empty($db_fine)) {
                $is_checked_sql = !empty($db_sql) || (!isset($_POST['db_sql_hidden']) && empty($_db_prepend_error));
                ?>
                <div class="form-check">
                    <input name="db_sql" type="checkbox" class="form-check-input" id="db_sql" value="1" <?php if ($is_checked_sql) echo 'checked="checked"' ?> />
                    <label class="form-check-label fw-bold" for="db_sql">Create initial phpwcms database tables</label>
                    <input type="hidden" name="db_sql_hidden" value="1" />
                </div>
                <?php
            }
            ?>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['admin_set'])): ?>
    <div class="card mb-4 border">
        <div class="card-header bg-light fw-bold">Superuser Administrator Settings</div>
        <div class="card-body">
            <?php if (empty($_SESSION['admin_save'])): ?>
                <div class="form-group row">
                    <label for="admin_name" class="col-sm-3 col-form-label fw-bold">Admin Full Name</label>
                    <div class="col-sm-6">
                        <input name="admin_name" type="text" id="admin_name" class="form-control" value="<?php echo empty($phpwcms['admin_name']) ? "Webmaster" : html_specialchars($phpwcms['admin_name']) ?>" />
                    </div>
                </div>

                <div class="form-group row">
                    <label for="admin_user" class="col-sm-3 col-form-label fw-bold">Admin Username</label>
                    <div class="col-sm-6">
                        <input name="admin_user" type="text" id="admin_user" class="form-control" value="<?php echo empty($phpwcms['admin_user']) ? "webmaster" : html_specialchars($phpwcms['admin_user']) ?>" autocomplete="username" />
                    </div>
                </div>

                <?php if (!empty($admin_err_pass)): ?>
                    <div class="alert alert-danger">Invalid password! Passwords are case sensitive, empty password not allowed.</div>
                <?php endif; ?>

                <div class="form-group row">
                    <label for="admin_pass" class="col-sm-3 col-form-label fw-bold">Password</label>
                    <div class="col-sm-6">
                        <div class="form-password">
                            <input name="admin_pass" type="password" id="admin_pass" class="form-control" autocomplete="new-password" />
                            <button type="button" class="form-password-action" data-coreui-toggle="password" aria-pressed="false" aria-label="Toggle password visibility">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="admin_passrepeat" class="col-sm-3 col-form-label fw-bold">Repeat Password</label>
                    <div class="col-sm-6">
                        <div class="form-password">
                            <input name="admin_passrepeat" type="password" id="admin_passrepeat" class="form-control" autocomplete="new-password" />
                            <button type="button" class="form-password-action" data-coreui-toggle="password" aria-pressed="false" aria-label="Toggle password visibility">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <label for="admin_email" class="col-sm-3 col-form-label fw-bold">Admin Email</label>
                    <div class="col-sm-6">
                        <input name="admin_email" type="email" id="admin_email" class="form-control" value="<?php echo html_specialchars($phpwcms['admin_email']) ?>" />
                    </div>
                </div>
            <?php else: ?>
                <?php
                $_brand_prefix = !empty($phpwcms['brand_table_prefix']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $phpwcms['brand_table_prefix']) : 'phpwcms';
                if ($_brand_prefix === '') {
                    $_brand_prefix = 'phpwcms';
                }
                $_db_prepend = ($phpwcms['db_prepend'] ? mysqli_real_escape_string($db, $phpwcms['db_prepend']) . '_' : '') . $_brand_prefix . '_';
                $user_check = _dbQuery('SELECT * FROM ' . $_db_prepend . "user WHERE usr_login='" . mysqli_real_escape_string($db, $phpwcms['admin_user']) . "'");

                if ($user_check !== false && count($user_check)) {
                    $sql  = "UPDATE " . $_db_prepend . "user SET ";
                    $sql .= "usr_login      = '" . mysqli_real_escape_string($db, $phpwcms['admin_user']) . "', ";
                    $sql .= "usr_pass       = '" . mysqli_real_escape_string($db, $phpwcms['admin_pass']) . "', ";
                    $sql .= "usr_email      = '" . mysqli_real_escape_string($db, $phpwcms['admin_email']) . "', ";
                    $sql .= "usr_admin      = 1, usr_aktiv = 1, ";
                    $sql .= "usr_name       = '" . mysqli_real_escape_string($db, $phpwcms['admin_name']) . "', ";
                    $sql .= "usr_lang       = '" . mysqli_real_escape_string($db, $phpwcms['default_lang']) . "', ";
                    $sql .= "usr_wysiwyg    = 2, usr_fe = 2 ";
                    $sql .= "WHERE usr_login='" . mysqli_real_escape_string($db, $phpwcms['admin_user']) . "' LIMIT 1";
                    $update_user = _dbQuery($sql, 'UPDATE');
                } elseif ($user_check !== false) {
                    $sql  = "INSERT INTO " . $_db_prepend . "user (";
                    $sql .= "usr_login, usr_pass, usr_email, usr_admin, usr_aktiv, usr_name, usr_var_structure, usr_var_publicfile, usr_var_privatefile, usr_lang, usr_wysiwyg, usr_fe, usr_2fa_enabled, usr_2fa_secret, usr_vars";
                    $sql .= ") VALUES (";
                    $sql .= "'" . mysqli_real_escape_string($db, $phpwcms['admin_user']) . "', '" . mysqli_real_escape_string($db, $phpwcms['admin_pass']) . "', '" . mysqli_real_escape_string($db, $phpwcms['admin_email']) . "', 1, 1, ";
                    $sql .= "'" . mysqli_real_escape_string($db, $phpwcms['admin_name']) . "', '', '', '', '" . mysqli_real_escape_string($db, $phpwcms['default_lang']) . "', 2, 2, 0, '', '')";
                    $create_user = _dbQuery($sql, 'INSERT');
                } else {
                    $user_check = false;
                }

                if (!empty($create_user) || !empty($update_user)) {
                    echo '<div class="alert alert-success mb-0"><i class="fa fa-check-circle"></i> Account for administrator <strong>' . html_specialchars($phpwcms['admin_user']) . '</strong> saved.<input type="hidden" name="user_account" value="1" /></div>';
                } elseif ($user_check === false) {
                    echo '<div class="alert alert-danger mb-0"><i class="fa fa-exclamation-triangle"></i> Database error: Administrator account could not be saved.</div>';
                    $_SESSION['admin_save'] = false;
                }
                ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
        <a href="setup.php?step=0" class="btn btn-secondary">&larr; Previous Step</a>
        <button name="dbsavesubmit" type="submit" class="btn btn-primary">Save &amp; Continue &rarr;</button>
    </div>
    <input name="do" type="hidden" value="1" />
</form>
