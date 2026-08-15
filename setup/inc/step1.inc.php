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
<h2 class="h4 text-primary font-weight-normal mb-3">2. Database Server Connection &amp; Schema</h2>

<?php if (isset($_POST["dbsavesubmit"]) && $err): ?>
    <div class="alert alert-danger mb-4">
        <i class="fa fa-exclamation-triangle"></i> Please check your database connection settings below.
    </div>
    <?php $_SESSION['admin_set'] = false; ?>
<?php endif; ?>

<form action="setup.php?step=1" method="post" autocomplete="off">

    <div class="card mb-4 border">
        <div class="card-header bg-light font-weight-bold">Database Server Connection</div>
        <div class="card-body">
            <div class="form-group row">
                <label for="db_host" class="col-sm-3 col-form-label font-weight-bold">Host &amp; Port</label>
                <div class="col-sm-6 d-flex">
                    <input name="db_host" type="text" class="form-control mr-2" id="db_host" value="<?php echo html_specialchars($phpwcms["db_host"]) ?>" placeholder="localhost" />
                    <input name="db_port" type="text" class="form-control" id="db_port" style="max-width: 90px;" value="<?php echo $phpwcms["db_port"] ?>" placeholder="3306" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Default: localhost / 3306</div>
            </div>

            <div class="form-group row">
                <label for="db_user" class="col-sm-3 col-form-label font-weight-bold">DB Username</label>
                <div class="col-sm-6">
                    <input name="db_user" type="text" class="form-control" id="db_user" value="<?php echo html_specialchars($phpwcms["db_user"]) ?>" placeholder="database user" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Database user name</div>
            </div>

            <div class="form-group row">
                <label for="db_pass" class="col-sm-3 col-form-label font-weight-bold">DB Password</label>
                <div class="col-sm-6">
                    <input name="db_pass" type="password" class="form-control" id="db_pass" value="<?php echo html_specialchars($phpwcms["db_pass"]) ?>" placeholder="database password" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Database password</div>
            </div>

            <div class="form-group row">
                <label for="db_table" class="col-sm-3 col-form-label font-weight-bold">Database Name</label>
                <div class="col-sm-6">
                    <input name="db_table" type="text" class="form-control" id="db_table" value="<?php echo html_specialchars($phpwcms["db_table"]) ?>" placeholder="database name" maxlength="255" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Must exist before setup</div>
            </div>

            <div class="form-group row">
                <label for="db_prepend" class="col-sm-3 col-form-label font-weight-bold">Table Prefix</label>
                <div class="col-sm-6">
                    <input name="db_prepend" type="text" class="form-control" id="db_prepend" value="<?php echo html_specialchars($prepend) ?>" maxlength="10" />
                </div>
                <div class="col-sm-3 form-text text-muted small align-self-center">Appends prefix_ to tables</div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-sm-9 offset-sm-3">
                    <div class="custom-control custom-checkbox">
                        <input name="db_pers" type="checkbox" class="custom-control-input" id="db_pers" value="1" <?php echo ($phpwcms["db_pers"]) ? "checked" : ""; ?> />
                        <label class="custom-control-label" for="db_pers">Use persistent database connection</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php if (!empty($db_additional)): ?>
    <?php
    $db_collations = array();
    $server_default_collation = '';
    if (!empty($db) && ($col_res = mysqli_query($db, "SHOW COLLATION LIKE 'utf8mb4%'"))) {
        while ($col_row = mysqli_fetch_assoc($col_res)) {
            if (!empty($col_row['Collation'])) {
                $db_collations[] = $col_row['Collation'];
                if (isset($col_row['Default']) && strtoupper($col_row['Default']) === 'YES') {
                    $server_default_collation = $col_row['Collation'];
                }
            }
        }
        mysqli_free_result($col_res);
    }
    if (empty($db_collations)) {
        $db_collations = array(
            'utf8mb4_0900_ai_ci',
            'utf8mb4_unicode_520_ci',
            'utf8mb4_unicode_ci',
            'utf8mb4_general_ci',
            'utf8mb4_bin'
        );
    }

    $common_defaults = array(
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
    ?>
    <div class="card mb-4 border">
        <div class="card-header bg-light font-weight-bold">Language &amp; Charset Settings (MySQL v<?php echo html_specialchars($row[0]) ?>)</div>
        <div class="card-body">
            <div class="form-group row">
                <label for="charset" class="col-sm-3 col-form-label font-weight-bold">Default Language</label>
                <div class="col-sm-6">
                    <select name="charset" class="custom-select" id="charset">
                    <?php
                    foreach ($available_languages as $key => $value) {
                        list(, $_lang_charset)  = explode('-', $value[1], 2);
                        list(, $_lang_en)       = explode('|', $value[0]);

                        echo '<option value="' . $key . '"';
                        if ($key === strtolower(str_replace('-', '', $phpwcms['default_lang']) . '-' . $phpwcms['charset'])) {
                            echo ' selected="selected"';
                        }
                        echo '>';
                        echo empty($value[3]) ? '' : $value[3] . ' - ';
                        echo ucfirst($_lang_en);
                        echo '</option>';
                    }
                    ?>
                    </select>
                </div>
            </div>

            <div class="form-group row mb-0">
                <label for="collation" class="col-sm-3 col-form-label font-weight-bold">Collation</label>
                <div class="col-sm-6">
                    <select name="collation" class="custom-select" id="collation">
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
        <div class="card-header bg-light font-weight-bold">3. Database Schema Initialization</div>
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

            } elseif (isset($db_create_err) || !empty($db_no_create)) {
                $_db_prepend = $phpwcms["db_prepend"] ? mysqli_real_escape_string($db, $phpwcms["db_prepend"]) . '_' : '';
                $check = _dbQuery("SHOW TABLES LIKE '" . $_db_prepend . "phpwcms_%'");

                if ($check && count($check)) {
                    $sql_data   = false;
                    $db_sql     = false;
                    $db_fine    = true;
                    echo '<div class="alert alert-success mb-0"><i class="fa fa-check-circle"></i> Initial phpwcms database tables created successfully.<input type="hidden" name="db_sql_hidden" value="1" /></div>';
                } else {
                    $_SESSION['admin_set']  = false;
                    $sql_data               = false;
                    $db_sql                 = false;
                    echo '<div class="alert alert-danger mb-0">No phpwcms database tables found. Please check setup!<input type="hidden" name="db_sql_hidden" value="1" /></div>';
                }
            }

            if (empty($db_fine)) {
                ?>
                <div class="custom-control custom-checkbox">
                    <input name="db_sql" type="checkbox" class="custom-control-input" id="db_sql" value="1" <?php if (!empty($db_sql)) echo 'checked="checked"' ?> />
                    <label class="custom-control-label font-weight-bold" for="db_sql">Create initial phpwcms database tables</label>
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
        <div class="card-header bg-light font-weight-bold">4. Superuser Administrator Settings</div>
        <div class="card-body">
            <?php if (empty($_SESSION['admin_save'])): ?>
                <div class="form-group row">
                    <label for="admin_name" class="col-sm-3 col-form-label font-weight-bold">Admin Full Name</label>
                    <div class="col-sm-6">
                        <input name="admin_name" type="text" id="admin_name" class="form-control" value="<?php echo empty($phpwcms["admin_name"]) ? "Webmaster" : html_specialchars($phpwcms["admin_name"]) ?>" />
                    </div>
                </div>

                <div class="form-group row">
                    <label for="admin_user" class="col-sm-3 col-form-label font-weight-bold">Admin Username</label>
                    <div class="col-sm-6">
                        <input name="admin_user" type="text" id="admin_user" class="form-control" value="<?php echo empty($phpwcms["admin_user"]) ? "webmaster" : html_specialchars($phpwcms["admin_user"]) ?>" />
                    </div>
                </div>

                <?php if (!empty($admin_err_pass)): ?>
                    <div class="alert alert-danger">Invalid password! Passwords are case sensitive, empty password not allowed.</div>
                <?php endif; ?>

                <div class="form-group row">
                    <label for="admin_pass" class="col-sm-3 col-form-label font-weight-bold">Password</label>
                    <div class="col-sm-6">
                        <input name="admin_pass" type="password" id="admin_pass" class="form-control" autocomplete="new-password" />
                    </div>
                </div>

                <div class="form-group row">
                    <label for="admin_passrepeat" class="col-sm-3 col-form-label font-weight-bold">Repeat Password</label>
                    <div class="col-sm-6">
                        <input name="admin_passrepeat" type="password" id="admin_passrepeat" class="form-control" autocomplete="new-password" />
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <label for="admin_email" class="col-sm-3 col-form-label font-weight-bold">Admin Email</label>
                    <div class="col-sm-6">
                        <input name="admin_email" type="email" id="admin_email" class="form-control" value="<?php echo html_specialchars($phpwcms["admin_email"]) ?>" />
                    </div>
                </div>
            <?php else: ?>
                <?php
                $_db_prepend = $phpwcms["db_prepend"] ? mysqli_real_escape_string($db, $phpwcms["db_prepend"]) . '_' : '';
                $user_check = _dbQuery('SELECT * FROM ' . $_db_prepend . "phpwcms_user WHERE usr_login='" . mysqli_real_escape_string($db, $phpwcms['admin_user']) . "'");

                if ($user_check !== false && count($user_check)) {
                    $sql  = "UPDATE " . $_db_prepend . "phpwcms_user SET ";
                    $sql .= "usr_login      = '" . mysqli_real_escape_string($db, $phpwcms['admin_user']) . "', ";
                    $sql .= "usr_pass       = '" . mysqli_real_escape_string($db, $phpwcms["admin_pass"]) . "', ";
                    $sql .= "usr_email      = '" . mysqli_real_escape_string($db, $phpwcms["admin_email"]) . "', ";
                    $sql .= "usr_admin      = 1, usr_aktiv = 1, ";
                    $sql .= "usr_name       = '" . mysqli_real_escape_string($db, $phpwcms['admin_name']) . "', ";
                    $sql .= "usr_lang       = '" . mysqli_real_escape_string($db, $phpwcms['default_lang']) . "', ";
                    $sql .= "usr_wysiwyg    = 2, usr_fe = 2 ";
                    $sql .= "WHERE usr_login='" . mysqli_real_escape_string($db, $phpwcms['admin_user']) . "' LIMIT 1";
                    $update_user = _dbQuery($sql, 'UPDATE');
                } elseif ($user_check !== false) {
                    $sql  = "INSERT INTO " . $_db_prepend . "phpwcms_user (";
                    $sql .= "usr_login, usr_pass, usr_email, usr_admin, usr_aktiv, usr_name, usr_var_structure, usr_var_publicfile, usr_var_privatefile, usr_lang, usr_wysiwyg, usr_fe, usr_vars";
                    $sql .= ") VALUES (";
                    $sql .= "'" . mysqli_real_escape_string($db, $phpwcms['admin_user']) . "', '" . mysqli_real_escape_string($db, $phpwcms["admin_pass"]) . "', '" . mysqli_real_escape_string($db, $phpwcms["admin_email"]) . "', 1, 1, ";
                    $sql .= "'" . mysqli_real_escape_string($db, $phpwcms['admin_name']) . "', '', '', '', '" . mysqli_real_escape_string($db, $phpwcms['default_lang']) . "', 2, 2, '')";
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
