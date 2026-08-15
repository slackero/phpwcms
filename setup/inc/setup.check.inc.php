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
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}

if(!empty($step)) {

    if ($step == 1 && $do) {

        if(!empty($_POST['user_account'])) {

            // fine continue with step 2
            session_write_close();
            if (!empty($_SERVER['HTTP_HOST']) && !empty($_SERVER['REQUEST_URI'])) {
                header('Location: http' . (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/setup.php?step=2');
            } else {
                header('Location: setup.php?step=2');
            }
            exit();
        }

        //superuser settings
        if(isset($_POST['admin_name'])) {

            $phpwcms['admin_name'] = empty($_POST['admin_name']) ? $phpwcms['admin_name'] : slweg($_POST['admin_name']);
            $phpwcms['admin_user'] = empty($_POST['admin_user']) ? $phpwcms['admin_user'] : slweg($_POST['admin_user']);

            if ($_POST['admin_pass'] !== $_POST['admin_passrepeat'] || empty($phpwcms['admin_pass'])) {
                $admin_err_pass = 1;
            } elseif (!empty($_POST['admin_pass'])) {
                $phpwcms['admin_pass'] = password_hash(slweg($_POST['admin_pass']), PASSWORD_DEFAULT);
            }

            $phpwcms['admin_email'] = clean_slweg($_POST['admin_email']);

            if (empty($admin_err_pass) && empty($_SESSION['admin_save'])) {
                write_conf_file($phpwcms);
                $_SESSION['admin_save'] = 1;
            }

        }

        // main settings

        $phpwcms['db_host'] = slweg($_POST['db_host']);
        $phpwcms['db_port'] = empty($_POST['db_port']) || !intval($_POST['db_port']) ? 3306 : intval($_POST['db_port']);
        $phpwcms['db_user'] = slweg($_POST['db_user']);
        $phpwcms['db_pass'] = slweg($_POST['db_pass']);
        $phpwcms['db_table'] = slweg($_POST['db_table']);
        $phpwcms['db_prepend'] = slweg($_POST['db_prepend']);
        $phpwcms['db_pers'] = empty($_POST['db_pers']) ? 0 : 1;

        $phpwcms['charset'] = 'utf-8'; // Fixed
        $phpwcms['db_charset'] = 'utf8mb4';
        if (!empty($_POST['charset'])) {
            $phpwcms['default_lang'] = substr($_POST['charset'], 0, 2);
            $_collation_warning = false;
        } elseif (empty($phpwcms['default_lang'])) {
            $phpwcms['default_lang'] = 'en';
        }
        if (!empty($_POST['collation']) && preg_match('/^utf8mb4[a-zA-Z0-9_]*$/i', $_POST['collation'])) {
            $phpwcms['db_collation'] = clean_slweg($_POST['collation']);
        } elseif (empty($phpwcms['db_collation'])) {
            $phpwcms['db_collation'] = 'utf8mb4_unicode_ci';
        }
        $db_sql = empty($_POST['db_sql']) ? 0 : 1;

        write_conf_file($phpwcms);
        $err = 0;

        $prepend = $phpwcms['db_prepend'];

        if(isset($_POST['dbsavesubmit'])) {

            // make db connect
            $db_host = $phpwcms['db_host'];
            if(!empty($phpwcms['db_pers']) && !str_starts_with($db_host, 'p:')) {
                $db_host = 'p:'.$db_host;
            }

            $db_error_message = '';
            $db_missing = false;
            $db_created_notice = false;

            try {
                $db = mysqli_connect(
                    $db_host,
                    $phpwcms['db_user'],
                    $phpwcms['db_pass'],
                    $phpwcms['db_table'],
                    $phpwcms['db_port']
                );
            } catch (Throwable $e) {
                $db = false;
                $db_error_message = $e->getMessage();
            }

            if (!$db) {
                // Test if server connection without database succeeds (credentials valid)
                try {
                    $server_db = mysqli_connect(
                        $db_host,
                        $phpwcms['db_user'],
                        $phpwcms['db_pass'],
                        null,
                        $phpwcms['db_port']
                    );
                } catch (Throwable $e) {
                    $server_db = false;
                }

                if ($server_db) {
                    $db_missing = true;

                    // Create database if requested or auto-create checkbox was submitted
                    if (!empty($_POST['create_database'])) {
                        $clean_db_name = preg_replace('/[^a-zA-Z0-9_\-]/', '', $phpwcms['db_table']);
                        $create_sql = 'CREATE DATABASE IF NOT EXISTS `' . $clean_db_name . '` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
                        if (mysqli_query($server_db, $create_sql) && mysqli_select_db($server_db, $phpwcms['db_table'])) {
                            $db = $server_db;
                            $db_error_message = '';
                            $db_missing = false;
                            $db_created_notice = true;
                        } else {
                            $db_error_message = 'Failed to create database: ' . mysqli_error($server_db);
                        }
                    }
                } elseif (empty($db_error_message)) {
                    $db_error_message = mysqli_connect_error();
                }
            }

            if($db) {

                if($result = mysqli_query($db, 'SELECT VERSION()')) {

                    if($row = mysqli_fetch_row($result)) {

                        $phpwcms['db_version'] = $row[0];
                        write_conf_file($phpwcms);

                    }

                    mysqli_free_result($result);

                    if($result = mysqli_query($db, "SHOW TABLES LIKE '". ($phpwcms['db_prepend'] ? mysqli_real_escape_string($db, $phpwcms['db_prepend']) . '_' : '') . "phpwcms_user'")) {

                        if (!empty($result->num_rows)) {
                            $_db_prepend_error = true;
                        }
                        mysqli_free_result($result);

                    }

                } else {

                    $err = 1;
                    $_SESSION['admin_save'] = 0;

                }

            } else {

                $err = 1;
                $_SESSION['admin_save'] = 0;

            }

            // enable additional db settings like collation and charset
            if(empty($err)) {

                $db_additional = true;

                if(isset($_collation_warning) && $_collation_warning === false) {

                    $db_init = true;

                    if(isset($_POST['db_sql_hidden'])) {

                        if(empty($db_sql)) {

                            $_SESSION['admin_set']  = true;
                            $db_no_create           = true;

                        } else {

                            // now read and display sql queries

                            $_db_prepend = $phpwcms['db_prepend'] ? mysqli_real_escape_string($db, $phpwcms['db_prepend']) . '_' : '';

                            $sql_data = read_textfile($DOCROOT . '/setup/default_sql/phpwcms_init.sql');
                            $sql_data = $sql_data . read_textfile($DOCROOT . '/setup/default_sql/phpwcms_inserts.sql');
                            $sql_data = preg_replace("/(#|--).*.\n/", '', $sql_data );
                            $sql_data = preg_replace('/ `phpwcms/', ' `'.$_db_prepend.'phpwcms', $sql_data );
                            $sql_data = preg_replace('/CREATE\s+TABLE\s+(?!IF\s+NOT\s+EXISTS)/i', 'CREATE TABLE IF NOT EXISTS ', $sql_data);
                            $sql_data = str_replace("\r", '', $sql_data);
                            $sql_data = str_replace("\n\n", "\n", $sql_data);
                            $sql_data = trim($sql_data);

                            // if True create initial database
                            if(!empty($_POST['db_sql']) || isset($_POST['db_create'])) {

                                $db_create_err = [];

                                //mysqli_query($db, 'SET storage_engine=MYISAM');
                                try {
                                    mysqli_query($db, 'SET SQL_MODE=NO_ENGINE_SUBSTITUTION');
                                } catch (Throwable $e) {}

                                try {
                                    mysqli_query($db, 'SET innodb_default_row_format=DYNAMIC');
                                    $set_dynamic = true;
                                } catch (Throwable $e) {
                                    $set_dynamic = false;
                                }

                                if (!$set_dynamic) {
                                    try {
                                        mysqli_query($db, 'SET GLOBAL innodb_default_row_format=DYNAMIC');
                                    } catch (Throwable $e) {
                                        // we just go on
                                    }
                                }

                                $value  = "SET NAMES '". mysqli_real_escape_string($db, $phpwcms['db_charset'])."'";
                                $value .= empty($phpwcms['db_collation']) ? '' : " COLLATE '".mysqli_real_escape_string($db, $phpwcms['db_collation'])."'";
                                try {
                                    mysqli_query($db, $value);
                                } catch (Throwable $e) {}

                                $db_create_sql = explode(';', $sql_data);
                                foreach($db_create_sql as $key => $value) {

                                    $value = trim($value);

                                    if(empty($value)) {
                                        unset($db_create_sql[$key]);
                                        continue;
                                    }

                                    if(!str_starts_with(strtoupper($value), 'INSERT')) {
                                        $value .= ' DEFAULT';
                                        $value .= ' CHARACTER SET '.$phpwcms['db_charset'];
                                        $value .= ' COLLATE '.$phpwcms['db_collation'];
                                    } else {
                                        $value = preg_replace('/^INSERT\s+INTO\s+/i', 'INSERT IGNORE INTO ', $value);
                                    }

                                    // send sql query safely with exception handling
                                    try {
                                        if(!mysqli_query($db, $value)) {
                                            $db_create_err[] = $value . ' [Error: ' . mysqli_error($db) . ']';
                                            unset($db_create_sql[$key]);
                                        }
                                    } catch (Throwable $e) {
                                        // Check if error is benign duplicate entry (code 1062)
                                        if ($e->getCode() !== 1062 && !str_contains($e->getMessage(), 'Duplicate entry')) {
                                            $db_create_err[] = $value . ' [Error: ' . $e->getMessage() . ']';
                                            unset($db_create_sql[$key]);
                                        }
                                    }
                                }

                                if (empty($db_create_err)) {
                                    $_SESSION['admin_set'] = true;
                                }

                            }
                        }
                    }
                }
            }
        }
    }

    if($step == 2 && $do) {

        $phpwcms['site'] = clean_slweg($_POST['site']);

        $phpwcms['SMTP_FROM_EMAIL'] = clean_slweg($_POST['smtp_from_email']);
        if(!$phpwcms['SMTP_FROM_EMAIL']) {
            $phpwcms['SMTP_FROM_EMAIL'] = $phpwcms['admin_email'];
        }
        $phpwcms['SMTP_FROM_NAME'] = clean_slweg($_POST['smtp_from_name']);
        if(!$phpwcms['SMTP_FROM_NAME']) {
            $phpwcms['SMTP_FROM_NAME'] = 'webmaster';
        }
        $phpwcms['SMTP_HOST'] = clean_slweg($_POST['smtp_host']);
        if(!$phpwcms['SMTP_HOST']) {
            $phpwcms['SMTP_HOST'] = 'localhost';
        }
        $phpwcms['SMTP_PORT'] = intval($_POST['smtp_port']);
        if(!$phpwcms['SMTP_PORT']) {
            $phpwcms['SMTP_PORT'] = 25;
        }
        $phpwcms['SMTP_MAILER'] = clean_slweg($_POST['smtp_mailer']);
        if(!$phpwcms['SMTP_MAILER']) {
            $phpwcms['SMTP_MAILER'] = 'mail';
        }
        $phpwcms['SMTP_AUTH'] = empty($_POST['smtp_auth']) ? 0 : 1;
        $phpwcms['SMTP_USER'] = slweg($_POST['smtp_user']);
        $phpwcms['SMTP_PASS'] = slweg($_POST['smtp_pass']);
        $phpwcms['SMTP_SECURE'] = clean_slweg($_POST['smtp_secure']);

        write_conf_file($phpwcms);

        if(!empty($_POST['admin_create'])) {
            try {
                $db = mysqli_connect(
                    $phpwcms['db_host'],
                    $phpwcms['db_user'],
                    $phpwcms['db_pass'],
                    $phpwcms['db_table'],
                    $phpwcms['db_port']
                );
            } catch (Exception $e) {
                $db = false;
            }
            if(!$db || mysqli_connect_error()) {
                $err = 1;
            } else {
                mysqli_query($db, 'SET SQL_MODE=NO_AUTO_VALUE_ON_ZERO,NO_ENGINE_SUBSTITUTION');
                mysqli_query($db, "SET NAMES '" . mysqli_real_escape_string($db, $phpwcms['db_charset']) . "'");
                $_db_prepend = $phpwcms['db_prepend'] ? mysqli_real_escape_string($db, $phpwcms['db_prepend']) . '_' : '';
                $sql =  'INSERT INTO ' . $_db_prepend . 'phpwcms_user (usr_login, usr_pass, usr_email, '.
                        "usr_admin, usr_aktiv, usr_name, usr_fe, usr_wysiwyg ) VALUES ('".
                        mysqli_real_escape_string($db, $phpwcms['admin_user'])."', '".
                        mysqli_real_escape_string($db, $phpwcms['admin_pass'])."', '".
                        mysqli_real_escape_string($db, $phpwcms['admin_email'])."', 1, 1, '".
                        mysqli_real_escape_string($db, $phpwcms['SMTP_FROM_NAME'])."', 2, 2)";

                mysqli_query($db, $sql) or $err = 1;
            }
        }

        if(!$err) {
            header('Location: setup.php?step=3');
            exit();
        }
    }

    if($step == 3 && $do) {

        $phpwcms['DOC_ROOT']       = clean_slweg($_POST['doc_root']);
        $phpwcms['root']           = clean_slweg($_POST['root']);
        $phpwcms['file_path']      = clean_slweg($_POST['file_path']);
        $phpwcms['templates']      = clean_slweg($_POST['templates']);
        $phpwcms['ftp_path']       = clean_slweg($_POST['ftp_path']);

        $phpwcms['file_path']      = ($phpwcms['file_path']) ?: 'phpwcms_filestorage';
        $phpwcms['templates']      = ($phpwcms['templates']) ?: 'phpwcms_template';
        $phpwcms['content_path']   = ($phpwcms['content_path']) ?: 'content';
        $phpwcms['cimage_path']    = ($phpwcms['cimage_path']) ?: 'images';
        $phpwcms['ftp_path']       = ($phpwcms['ftp_path']) ?: 'phpwcms_ftp';

        write_conf_file($phpwcms);
        header('Location: setup.php?step=4');
        exit();
    }

    if($step == 4 && $do) {
        $phpwcms['file_maxsize']     = intval($_POST['file_maxsize']);
        $phpwcms['content_width']    = intval($_POST['content_width']);
        $phpwcms['img_list_width']   = intval($_POST['img_list_width']);
        $phpwcms['img_list_height']  = intval($_POST['img_list_height']);
        $phpwcms['img_prev_width']   = intval($_POST['img_prev_width']);
        $phpwcms['img_prev_height']  = intval($_POST['img_prev_height']);
        $phpwcms['max_time']         = intval($_POST['max_time']);
        $phpwcms['file_maxsize']     = ($phpwcms['file_maxsize']) ?: 2097152;
        $phpwcms['content_width']    = ($phpwcms['content_width']) ?: 538;
        $phpwcms['img_list_width']   = ($phpwcms['img_list_width']) ?: 100;
        $phpwcms['img_list_height']  = ($phpwcms['img_list_height']) ?: 75;
        $phpwcms['img_prev_width']   = ($phpwcms['img_prev_width']) ?: 538;
        $phpwcms['img_prev_height']  = ($phpwcms['img_prev_height']) ?: 400;
        $phpwcms['max_time']         = ($phpwcms['max_time']) ?: 1800;

        if (isset($_POST['image_library'])) {
            $phpwcms['image_library'] = trim($_POST['image_library']);
        }
        if (isset($_POST['library_path'])) {
            $phpwcms['library_path'] = trim($_POST['library_path']);
        }
        if (isset($_POST['sharpen_level'])) {
            $phpwcms['sharpen_level'] = intval($_POST['sharpen_level']);
        }
        if (isset($_POST['jpg_quality'])) {
            $phpwcms['jpg_quality'] = max(10, min(100, intval($_POST['jpg_quality'])));
        }
        if (isset($_POST['webp_quality'])) {
            $phpwcms['webp_quality'] = max(10, min(100, intval($_POST['webp_quality'])));
        }

        write_conf_file($phpwcms);
        header('Location: setup.php?step=5');
        exit();
    }

}
