<?php

if(!empty($step)) {

    if ($step == 1 && $do) {

        if(!empty($_POST['user_account'])) {

            // fine continue with step 2
            session_write_close();
            if(!empty($_SERVER['HTTP_HOST']) && !empty($_SERVER['REQUEST_URI'])) {
                header('Location: http'.($_SERVER['HTTPS'] ? 's' : '').'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']).'/setup.php?step=2');
            } else {
                header("Location: setup.php?step=2");
            }
            exit();
        }

        //superuser settings
        if(isset($_POST['admin_name'])) {

            $cmsgo['admin_name']      = empty($_POST['admin_name']) ? $cmsgo['admin_name'] : slweg($_POST['admin_name']);
            $cmsgo['admin_user']      = empty($_POST['admin_user']) ? $cmsgo['admin_user'] : slweg($_POST['admin_user']);

            if($_POST["admin_pass"] !== $_POST["admin_passrepeat"] || empty($cmsgo["admin_pass"])) {
                $admin_err_pass         = 1;
            } elseif(!empty($_POST["admin_pass"])) {
                $cmsgo["admin_pass"]  = md5(slweg($_POST["admin_pass"]));
            }

            $cmsgo["admin_email"]     = clean_slweg($_POST["admin_email"]);

            if(empty($admin_err_pass) && empty($_SESSION['admin_save'])) {
                write_conf_file($cmsgo);
                $_SESSION['admin_save'] = 1;
            }

        }

        // main settings

        $cmsgo["db_host"]    = slweg($_POST["db_host"]);
        $cmsgo["db_user"]    = slweg($_POST["db_user"]);
        $cmsgo["db_pass"]    = slweg($_POST["db_pass"]);
        $cmsgo["db_table"]   = slweg($_POST["db_table"]);
        $cmsgo["db_prepend"] = slweg($_POST["db_prepend"]);
        $cmsgo["db_pers"]    = empty($_POST["db_pers"]) ? 0 : 1;

        $cmsgo["charset"]         = 'utf-8'; // Fixed
        $cmsgo['db_charset']      = 'utf8';
        if (!empty($_POST["charset"])) {
            $cmsgo['default_lang'] = substr($_POST["charset"], 0, 2);
            $_collation_warning = false;
        } elseif (empty($cmsgo['default_lang'])) {
            $cmsgo['default_lang'] = 'en';
        }
        $cmsgo['db_collation']    = 'utf8_general_ci';
        $db_sql = empty($_POST["db_sql"]) ? 0 : 1;

        write_conf_file($cmsgo);
        $err = 0;

        $prepend = $cmsgo["db_prepend"];

        if(isset($_POST["dbsavesubmit"])) {

            // make db connect
            $db_host = $cmsgo["db_host"];
            if(!empty($cmsgo["db_pers"]) && substr($db_host, 0, 2) !== 'p:') {
                $db_host = 'p:'.$db_host;
            }
            $db = mysqli_connect($db_host, $cmsgo["db_user"], $cmsgo["db_pass"], $cmsgo["db_table"]);

            if($db) {

                if($result = mysqli_query($db, "SELECT VERSION()")) {

                    if($row = mysqli_fetch_row($result)) {

                        $cmsgo["db_version"] = $row[0];
                        write_conf_file($cmsgo);

                    }

                    mysqli_free_result($result);

                    if($result = mysqli_query($db, 'SELECT * FROM '. ($cmsgo["db_prepend"] ? $cmsgo["db_prepend"].'_' : '').'cmsgo_user')) {

                        $_db_prepend_error = true;
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

                            $_db_prepend = ($cmsgo["db_prepend"] ? $cmsgo["db_prepend"].'_' : '');

                            $sql_data = read_textfile($DOCROOT . '/setup/default_sql/cmsgo_init.sql');
                            $sql_data = $sql_data . read_textfile($DOCROOT . '/setup/default_sql/cmsgo_inserts.sql');
                            $sql_data = preg_replace("/(#|--).*.\n/", '', $sql_data );
                            $sql_data = preg_replace('/ `cmsgo/', ' `'.$_db_prepend.'cmsgo', $sql_data );
                            $sql_data = str_replace("\r", '', $sql_data);
                            $sql_data = str_replace("\n\n", "\n", $sql_data);
                            $sql_data = trim($sql_data);

                            // if True create initial database
                            if(isset($_POST['db_create'])) {

                                $db_create_err = array();

                                mysqli_query($db, 'SET storage_engine=MYISAM');
                                mysqli_query($db, "SET SQL_MODE=NO_AUTO_VALUE_ON_ZERO,NO_ENGINE_SUBSTITUTION");

                                $value  = "SET NAMES '". mysqli_real_escape_string($db, $cmsgo['db_charset'])."'";
                                $value .= empty($cmsgo['db_collation']) ? '' : " COLLATE '".mysqli_real_escape_string($db, $cmsgo['db_collation'])."'";
                                mysqli_query($db, $value);

                                $db_create_sql = explode(';', $sql_data);
                                foreach($db_create_sql as $key => $value) {

                                    $value = trim($value);

                                    if(empty($value)) {
                                        unset($db_create_sql[$key]);
                                        continue;
                                    }

                                    if(strpos(strtoupper($value), 'INSERT') !== 0) {
                                        $value .= ' DEFAULT';
                                        $value .= ' CHARACTER SET '.$cmsgo['db_charset'];
                                        $value .= ' COLLATE '.$cmsgo['db_collation'];
                                    }

                                    // send sql query
                                    if(!mysqli_query($db, $value)) {
                                        $db_create_err[] = $value;
                                        unset($db_create_sql[$key]);
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }
    }

    if($step == 2 && $do) {

        $cmsgo["site"] = clean_slweg($_POST["site"]);

        $cmsgo['SMTP_FROM_EMAIL'] = clean_slweg($_POST["smtp_from_email"]);
        if(!$cmsgo['SMTP_FROM_EMAIL']) {
            $cmsgo['SMTP_FROM_EMAIL'] = $cmsgo["admin_email"];
        }
        $cmsgo['SMTP_FROM_NAME'] = clean_slweg($_POST["smtp_from_name"]);
        if(!$cmsgo['SMTP_FROM_NAME']) {
            $cmsgo['SMTP_FROM_NAME'] = 'webmaster';
        }
        $cmsgo['SMTP_HOST'] = clean_slweg($_POST["smtp_host"]);
        if(!$cmsgo['SMTP_HOST']) {
            $cmsgo['SMTP_HOST'] = 'localhost';
        }
        $cmsgo['SMTP_PORT'] = intval($_POST["smtp_port"]);
        if(!$cmsgo['SMTP_PORT']) {
            $cmsgo['SMTP_PORT'] = 25;
        }
        $cmsgo['SMTP_MAILER'] = clean_slweg($_POST["smtp_mailer"]);
        if(!$cmsgo['SMTP_MAILER']) {
            $cmsgo['SMTP_MAILER'] = 'mail';
        }
        $cmsgo['SMTP_AUTH'] = empty($_POST["smtp_auth"]) ? 0 : 1;
        $cmsgo['SMTP_USER'] = slweg($_POST["smtp_user"]);
        $cmsgo['SMTP_PASS'] = slweg($_POST["smtp_pass"]);
        $cmsgo['SMTP_SECURE'] = clean_slweg($_POST["smtp_secure"]);

        write_conf_file($cmsgo);

        if(!empty($_POST["admin_create"])) {
            $db = mysqli_connect($cmsgo["db_host"], $cmsgo["db_user"], $cmsgo["db_pass"], $cmsgo["db_table"]);
            if(mysqli_connect_error()) {
                $err = 1;
            } else {
                mysqli_query($db, "SET SQL_MODE=NO_AUTO_VALUE_ON_ZERO,NO_ENGINE_SUBSTITUTION");
                mysqli_query($db, "SET NAMES '".mysqli_real_escape_string($db, $cmsgo["charset"])."'");
                $cmsgo["db_prepend"] = ($cmsgo["db_prepend"]) ? $cmsgo["db_prepend"]."_" : "";
                $sql =  "INSERT INTO ".$cmsgo["db_prepend"]."cmsgo_user (usr_login, usr_pass, usr_email, ".
                        "usr_admin, usr_aktiv, usr_name, usr_fe, usr_wysiwyg ) VALUES ('".
                        mysqli_real_escape_string($db, $cmsgo["admin_user"])."', '".
                        mysqli_real_escape_string($db, md5($cmsgo["admin_pass"]))."', '".
                        mysqli_real_escape_string($db, $cmsgo["admin_email"])."', 1, 1, '".
                        mysqli_real_escape_string($db, $cmsgo['SMTP_FROM_NAME'])."', 2, 2)";

                mysqli_query($db, $sql) or $err = 1;
            }
        }

        if(!$err) {
            header("Location: setup.php?step=3");
            exit();
        }
    }

    if($step == 3 && $do) {

        $cmsgo['DOC_ROOT']       = clean_slweg($_POST["doc_root"]);
        $cmsgo["root"]           = clean_slweg($_POST["root"]);
        $cmsgo["file_path"]      = clean_slweg($_POST["file_path"]);
        $cmsgo["templates"]      = clean_slweg($_POST["templates"]);
        $cmsgo["ftp_path"]       = clean_slweg($_POST["ftp_path"]);

        $cmsgo["file_path"]      = ($cmsgo["file_path"]) ? $cmsgo["file_path"] : "cmsgo_filestorage";
        $cmsgo["templates"]      = ($cmsgo["templates"]) ? $cmsgo["templates"] : "cmsgo_template";
        $cmsgo["content_path"]   = ($cmsgo["content_path"]) ? $cmsgo["content_path"] : "content";
        $cmsgo["cimage_path"]    = ($cmsgo["cimage_path"]) ? $cmsgo["cimage_path"] : "images";
        $cmsgo["ftp_path"]       = ($cmsgo["ftp_path"]) ? $cmsgo["ftp_path"] : "cmsgo_ftp";

        write_conf_file($cmsgo);
        header("Location: setup.php?step=4");
        exit();
    }

    if($step == 4 && $do) {
        $cmsgo["file_maxsize"]     = intval($_POST["file_maxsize"]);
        $cmsgo["content_width"]    = intval($_POST["content_width"]);
        $cmsgo["img_list_width"]   = intval($_POST["img_list_width"]);
        $cmsgo["img_list_height"]  = intval($_POST["img_list_height"]);
        $cmsgo["img_prev_width"]   = intval($_POST["img_prev_width"]);
        $cmsgo["img_prev_height"]  = intval($_POST["img_prev_height"]);
        $cmsgo["max_time"]         = intval($_POST["max_time"]);
        $cmsgo["file_maxsize"]     = ($cmsgo["file_maxsize"]) ? $cmsgo["file_maxsize"] : 2097152;
        $cmsgo["content_width"]    = ($cmsgo["content_width"]) ? $cmsgo["content_width"] : 538;
        $cmsgo["img_list_width"]   = ($cmsgo["img_list_width"]) ? $cmsgo["img_list_width"] : 100;
        $cmsgo["img_list_height"]  = ($cmsgo["img_list_height"]) ? $cmsgo["img_list_height"] : 75;
        $cmsgo["img_prev_width"]   = ($cmsgo["img_prev_width"]) ? $cmsgo["img_prev_width"] : 538;
        $cmsgo["img_prev_height"]  = ($cmsgo["img_prev_height"]) ? $cmsgo["img_prev_height"] : 400;
        $cmsgo["max_time"]         = ($cmsgo["max_time"]) ? $cmsgo["max_time"] : 1800;

        write_conf_file($cmsgo);
        header("Location: setup.php?step=5");
        exit();
    }

}
