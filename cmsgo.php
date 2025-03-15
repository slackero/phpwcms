<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// set page processiong start time
list($usec, $sec) = explode(' ', microtime());
$cmsgo_rendering_start = $usec + $sec;

//define used var names
$body_onload                = '';
$forward_to_message_center  = false;
$wcsnav                     = array();
$indexpage                  = array();
$cmsgo                      = array('SESSION_START' => true);
$BL                         = array();
$BE                         = array('HTML' => '', 'BODY_OPEN' => array(), 'BODY_CLOSE' => array(), 'HEADER' => array(), 'LANG' => 'en');
$CMSGO_ROOT                 = dirname(__FILE__);

require_once $CMSGO_ROOT.'/include/config/conf.inc.php';
require_once $CMSGO_ROOT.'/include/inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';
require_once CMSGO_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/general.inc.php';

// check against user's language
if(!empty($_SESSION["wcs_user_lang"]) && preg_match('/[a-z]{2}/i', $_SESSION["wcs_user_lang"])) {
    $BE['LANG'] = $_SESSION["wcs_user_lang"];
}

checkLogin();
validate_csrf_tokens();
define('CSRF_GET_TOKEN', get_token_get_string());

require_once CMSGO_ROOT.'/include/inc_lib/backend.functions.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/default.backend.inc.php';
require_once CMSGO_ROOT.'/include/inc_lang/backend/en/lang.inc.php'; //load default language EN
require_once CMSGO_ROOT.'/include/inc_lang/backend/en/lang.pp.inc.php';
include_once CMSGO_ROOT."/include/inc_lang/code.lang.inc.php";

$BL['modules'] = array();

if(!empty($_SESSION["wcs_user_lang_custom"])) {
    //use custom lang if available -> was set in edit.php
    $BL['merge_lang_array'][0]      = $BL['be_admin_optgroup_label'];
    $BL['merge_lang_array'][1]      = $BL['be_cnt_field'];
    include CMSGO_ROOT.'/include/inc_lang/backend/'. $BE['LANG'] .'/lang.inc.php';
    include CMSGO_ROOT.'/include/inc_lang/backend/'. $BE['LANG'] .'/lang.pp.inc.php';
    $BL['be_admin_optgroup_label']  = array_merge($BL['merge_lang_array'][0], $BL['be_admin_optgroup_label']);
    $BL['be_cnt_field']             = array_merge($BL['merge_lang_array'][1], $BL['be_cnt_field']);
    unset($BL['merge_lang_array']);
}

require_once CMSGO_ROOT.'/include/inc_lib/navi_text.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/checkmessage.inc.php';
require_once CMSGO_ROOT.'/include/config/conf.template_default.inc.php';
require_once CMSGO_ROOT.'/include/config/conf.indexpage.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/imagick.convert.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/constants/timestamp.php';
require_once CMSGO_ROOT.'/include/inc_lib/classes/class.iptc.php';
require_once CMSGO_ROOT.'/include/inc_lib/classes/class.convertibletimestamp.php';

// check modules
require_once CMSGO_ROOT.'/include/inc_lib/modules.check.inc.php';

// load array with actual content types
include CMSGO_ROOT.'/include/inc_lib/article.contenttype.inc.php';

$BL['be_admin_struct_index'] = html_specialchars($indexpage['acat_name']);

$subnav                           = ''; //Sub Navigation
$p                                = isset($_GET["p"])  ? intval($_GET["p"]) : 0; //which page should be opened
$do                               = isset($_GET["do"]) ? $_GET["do"] : 'default'; //which backend section and which $do action
$module                           = isset($_GET['module'])  ? clean_slweg($_GET['module']) : ''; //which module
$cmsgo['be_parse_lang_process']   = false; // limit parsing for BBCode/BraceCode languages only to some sections
$modulearray                      = [];

$result = _dbGet('cmsgo_usergroup', '*', 'group_active != 9', '', 'group_id');
if (isset($result[0])) {
    foreach ($result as $grouplist) {
        $grouparray[$grouplist["group_syskey"]] = convertStringToArray($grouplist["group_member"]);
        if ($grouplist["group_modkey"] !== '') {
            if ($grouplist["group_trash"] == '0' && $grouplist["group_active"] == '1') {
                $modulearray[$grouplist["group_modkey"]] = convertStringToArray($grouplist["group_member"]);
            } else {
                $modulearray[$grouplist["group_modkey"]] = array();
            }
        }
    }
}

switch ($do) {

    case "articles":    //articles
        include CMSGO_ROOT.'/include/inc_lib/admin.functions.inc.php';
        include CMSGO_ROOT.'/include/inc_lib/article.functions.inc.php'; //load article funtions
        break;

    case "files":       //files
        break;

    case "modules":        //modules
        break;

    case "profile":        //profile
        if (!empty($_POST["form_aktion"])) {
            switch ($_POST["form_aktion"]) { //Aktualisieren der wcs account & profile Daten
                case "update_account":    include CMSGO_ROOT.'/include/inc_lib/profile.updateaccount.inc.php';
                break;
            }
        }
        break;

    case "logout":      //Logout
        logout_user();
        break;

    case "admin":       //Admin
        if(!empty($_SESSION["wcs_user_admin"])) {
            include CMSGO_ROOT.'/include/inc_lib/admin.functions.inc.php';
        }
        break;

}

//script chaching to allow header redirect
ob_start(); //without Compression

// set correct content type for backend
header('Content-Type: text/html; charset=' . CMSGO_CHARSET);

?><!DOCTYPE HTML>
<html lang="<?php echo $BE['LANG']; ?>">
<head><?php printf(CMSGO_HEADER_COMMENT, ''); ?>
    <title><?php echo $BL['be_page_title'] . ' - ' . CMSGO_HOST ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CMSGO_CHARSET ?>">
    <link href="include/inc_css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="include/inc_css/flag-icon.min.css" rel="stylesheet">
    <link href="include/inc_css/cmsgo-fontawesome.css" rel="stylesheet" type="text/css">
    <link href="include/inc_css/cmsgospecial.min.css" rel="stylesheet" type="text/css">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="content-security-policy" content="default-src *; img-src 'self' data:; style-src 'self' data: 'unsafe-inline'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; script-src-elem 'self' 'unsafe-inline'; connect-src 'self' 'unsafe-inline'">
    <script>var CSRF_GET_TOKEN = '<?php echo CSRF_GET_TOKEN; ?>';</script>
<?php

$BE['HEADER']['alias_slash_var'] = ' <script type="text/javascript"> var aliasAllowSlashes=' . (CMSGO_ALIAS_WSLASH ? 'true' : 'false') . ', aliasUtf8=' . (CMSGO_ALIAS_UTF8 ? 'true' : 'false') . '; </script>';
$BE['HEADER']['cmsgo.js'] = getJavaScriptSourceLink('include/inc_js/cmsgo.js');
$BE['HEADER']['textarea.autosize.js'] = getJavaScriptSourceLink('include/inc_js/autosize.min.js');

if ($do == "messages" && $p == 1) {
    include CMSGO_ROOT.'/include/inc_lib/message.sendjs.inc.php';
} elseif ($do == "articles") {
    if ($p == 2 && isset($_GET["aktion"]) && intval($_GET["aktion"]) == 2) {
        initJsOptionSelect();
    }
    if ($p == 6) {
        initJsOptionSelect();
    }
} elseif ($do == 'admin' && ($p == 6 || $p == 11)) {
    // struct editor
    initJsOptionSelect();
}

if($BE['LANG'] == 'ar') {
    $BE['HEADER'][] = '<style type="text/css">' . LF . '<!--' . LF . '* {direction: rtl;}' . LF . '// -->' . LF . '</style>';
}

?>
<!-- cmsGO! HEADER -->
</head>
<body<?php echo $body_onload ?>><!-- cmsGO! BODY_OPEN -->
<div id="container">
  <header id="header" class="navbar navbar-static-top">
    <div class="container-fluid px-0 px-sm-3">
      <div id="header-logo" class="navbar-header"><a href="cmsgo.php?<?php echo get_token_get_string(); ?>" class="navbar-brand"><img class="border-0" src="img/logo.svg" alt="cmsGO! Content Management System" title="cmsGO! Content Management System"></a></div>
      <a href="#" id="button-menu" class="d-md-none d-lg-none d-xl-none"><span class="fa fa-bars"></span></a>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="<?php echo CMSGO_URL ?>" target="_blank"><i class="menu-image far fa-eye fa-fw"></i> <span class="d-none d-sm-inline-block"><?php echo $BL['be_func_struct_preview'] ?></span></a></li>
        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-search fa-fw"></i> <span class="d-none d-sm-inline-block"><?php echo $BL['be_fsearch_startsearch'] ?></span></a>
            <form class="dropdown-menu dropdown-menu-right backend-search" action="cmsgo.php?<?php echo get_token_get_string(); ?>" method="POST">
                <div class="input-group">
                    <input type="search" name="backend_search_input" placeholder="<?php echo $BL['be_ctype_search'] ?>" value="<?php
                    if (isset($_POST['backend_search_input'])) {
                        $_SESSION['cmsgo_backend_search'] = clean_slweg($_POST['backend_search_input']);
                    }
                    if (!empty($_SESSION['cmsgo_backend_search'])) {
                        echo html_specialchars($_SESSION['cmsgo_backend_search']);
                    }
                    ?>" class="form-control" aria-describedby="basic-search" />
                    <div class="input-group-append" id="basic-search">
                        <button class="btn btn-blue">
                            <i class="fa fa-search fw"></i>
                        </button>
                    </div>
                </div>
            </form>
        </li>
        <?php if (in_array($_SESSION["wcs_user_id"], $grouparray["profile"])) {
          $active = ($do == 'profile') ? ' class="active"' : '';
          echo '<li><a href="cmsgo.php?do=profile"'.$active.'><i class="menu-image far fa-user fa-fw"></i> <span class="d-none d-sm-inline-block">  '.$BL['be_nav_profile'].'</span></a></li>';
      } ?>
        <li><a href="cmsgo.php?do=logout" target="_top"><i class="menu-image fa fa-sign-out-alt fa-fw"></i> <span class="d-none d-sm-inline-block"><?php echo $BL['be_nav_logout'] ?></span></a></li>
      </ul>
    </div>
  </header>

  <nav id="column-left">
    <div class="sidebar">
      <div class="bootstrap-vertical-nav">
        <div id="collapseMenu">
          <ul id="side-menu" class="nav flex-column">
            <?php
            // create backend main navigation

            echo '<li class="nav-item';
            if ($do == 'default') {
                echo ' active';
            }
            echo '"><a href="cmsgo.php?' . get_token_get_string() . '"><i class="menu-image fa fa-tachometer-alt fa-fw"></i> Dashboard</a></li>';

            $active = ($do == 'articles' || ($do == 'admin' && $p == 6)) ? ' active' : '';
            //only access if admin or permission set
            if (!empty($_SESSION["wcs_user_admin"]) || in_array($_SESSION["wcs_user_id"], $grouparray["artcent"]) || in_array($_SESSION["wcs_user_id"], $grouparray["artnews"])) {
                echo '<li class="nav-item'.$active.'"><a href="#"><i class="menu-image fa fa-copy fa-fw"></i> '.$BL['be_nav_articles'].' <span class="glyphicon arrow"></span></a> ';
                $subnav = '';
                if (in_array($_SESSION["wcs_user_id"], $grouparray["artcent"])) {
                    $subnav .= subnavtext($BL['be_subnav_article_center'], "cmsgo.php?do=articles", $p, "", 0);
                    $subnav .= subnavtext($BL['be_subnav_article_new'], "cmsgo.php?do=articles&amp;p=1&amp;struct=0", $p, "1", 0);
                }
                if (in_array($_SESSION["wcs_user_id"], $grouparray["artnews"])) {
                    $subnav .= subnavtext($BL['be_news'], "cmsgo.php?do=articles&amp;p=3", $p, "3", 0);
                }
                echo '<ul class="submenu">'.$subnav."</ul></li>";
            }

            $active = $do == 'files' ? ' active' : '';
            //only access if admin or permission set
            if (!empty($_SESSION["wcs_user_admin"]) || in_array($_SESSION["wcs_user_id"], $grouparray["filecent"])) {
                echo '<li class="nav-item'.$active.'"><a href="#"><i class="menu-image fa fa-folder-open fa-fw"></i> '.$BL['be_nav_files'].' <span class="glyphicon arrow"></span></a> ';

                if (in_array($_SESSION["wcs_user_id"], $grouparray["filecent"])) {
                    $subnav = subnavtext($BL['be_subnav_file_center'], "cmsgo.php?do=files", $p, "", 0);
                }
                if (in_array($_SESSION["wcs_user_id"], $grouparray["fileaction"])) {
                    $subnav .= subnavtext($BL['be_subnav_file_actions'], "cmsgo.php?do=files&amp;p=4", $p, "4", 0);
                }
                if (in_array($_SESSION["wcs_user_id"], $grouparray["fileupload"])) {
                    $subnav .= subnavtext($BL['be_file_multiple_upload'], "cmsgo.php?do=files&amp;p=8", $p, "8", 0);
                }
                echo '<ul class="submenu">'.$subnav."</ul></li>";
            }

            if (!empty($cmsgo['enable_backend_module']) && in_array($_SESSION["wcs_user_id"], $grouparray["module"])) {
                $active = ($do == 'modules') ? ' active' : '';
                echo '<li class="nav-item'.$active.'"><a href="#"><i class="menu-image fa fa-puzzle-piece fa-fw"></i> '.$BL['be_nav_modules'].'  <span class="glyphicon arrow"></span></a>';
                $subnav = '';
                foreach ($cmsgo['modules'] as $value) {
                    if (isset($modulearray[$value['name']]) && in_array($_SESSION["wcs_user_id"], $modulearray[$value['name']])) {
                        $subnav .= subnavtext($BL['modules'][ $value['name'] ]['backend_menu'], 'cmsgo.php?do=modules&amp;module='.$value['name'], $module, $value['name'], 0);
                    }
                }
                echo '<ul class="submenu">'.LF.$subnav."\n</ul></li>";
            }

            //newsletter
            if (!empty($cmsgo['enable_backend_newsletter']) && in_array($_SESSION["wcs_user_id"], $grouparray["nl"])) {
                $active = $do == 'messages' ? ' active' : '';
                echo '<li class="nav-item'.$active.'"><a href="#"><i class="menu-image fa fa-envelope fa-fw"></i> '.$BL['be_nav_messages'].' <span class="glyphicon arrow"></span></a> ';
                $subnav = '';
                if (in_array($_SESSION['wcs_user_id'], $grouparray['nlabo'])) {
                    $subnav .= subnavtext($BL['be_subnav_msg_newsletter'], 'cmsgo.php?do=messages&amp;p=2', $p, '2', 0);
                }
                if (in_array($_SESSION['wcs_user_id'], $grouparray['nllist'])) {
                    $subnav .= subnavtext($BL['be_subnav_msg_newslettersend'], 'cmsgo.php?do=messages&amp;p=3', $p, '3', 0);
                }
                if (in_array($_SESSION['wcs_user_id'], $grouparray['nlrecip'])) {
                    $subnav .= subnavtext($BL['be_subnav_msg_subscribers'], 'cmsgo.php?do=messages&amp;p=4', $p, '4', 0);
                }
                echo '<ul class="submenu">'.LF.$subnav."\n</ul></li>";
            }

            if (in_array($_SESSION['wcs_user_id'], $grouparray['adm'])) {
                $active = ($do == 'admin' && $p != 6) ? ' active' : '';
                echo '<li class="nav-item'.$active.'"><a href="#"><i class="menu-image fa fa-cog fa-fw"></i> '.$BL['be_nav_admin'].' <span class="glyphicon arrow"></span></a>';
                $subnav = '';
                if (in_array($_SESSION['wcs_user_id'], $grouparray['admlayout'])) {
                    $subnav .= subnavtext($BL['be_subnav_admin_pagelayout'], 'cmsgo.php?do=admin&amp;p=8', $p, '8', 0);
                }
                if (in_array($_SESSION['wcs_user_id'], $grouparray['admtempl'])) {
                    $subnav .= subnavtext($BL['be_subnav_admin_templates'], 'cmsgo.php?do=admin&amp;p=11', $p, '11', 0);
                }
                if (in_array($_SESSION['wcs_user_id'], $grouparray['admuser'])) {
                    $subnav .= subnavtext($BL['be_subnav_admin_users'], 'cmsgo.php?do=admin', $p, '', 0);
                }
                if (in_array($_SESSION['wcs_user_id'], $grouparray['admugroup'])) {
                    $subnav .= subnavtext($BL['be_subnav_admin_groups'], 'cmsgo.php?do=admin&amp;p=1', $p, '1', 0);
                }
                if (in_array($_SESSION['wcs_user_id'], $grouparray['admialias'])) {
                    $subnav .= subnavtext($BL['be_imagealias'], 'cmsgo.php?do=admin&amp;p=12', $p, '12', 0);
                }
                if (in_array($_SESSION['wcs_user_id'], $grouparray['admfilecat'])) {
                    $subnav .= subnavtext($BL['be_subnav_admin_filecat'], 'cmsgo.php?do=admin&amp;p=7', $p, '7', 0);
                }
                if (in_array($_SESSION['wcs_user_id'], $grouparray['admalias'])) {
                    $subnav .= subnavtext($BL['be_alias'], 'cmsgo.php?do=admin&amp;p=13', $p, '13', 0);
                }
                if (in_array($_SESSION['wcs_user_id'], $grouparray['admlink'])) {
                    $subnav .= subnavtext($BL['be_link'] . ' &amp; ' . $BL['be_redirect'], 'cmsgo.php?do=admin&amp;p=14', $p, "14", 0);
                }

                $subnav .= subnavtext($BL['be_flush_image_cache'], '#', 1, 0, 0, 'onclick="return flush_image_cache(this,\'include/inc_act/ajax_connector.php?' . get_token_get_string() . '&action=flush_image_cache&value=1\');" ');
                $subnav .= subnavtext($BL['be_cnt_move_deleted'], 'include/inc_act/act_file.php?' . get_token_get_string() . '&movedeletedfiles='. $_SESSION['wcs_user_id'], 1, 0, 0, 'onclick="return confirm(\''.$BL['be_cnt_move_deleted_msg'].'\');" ');

                $subnav .= subnavtextext('phpinfo()', 'include/inc_act/act_phpinfo.php?' . get_token_get_string(), '_blank', 0);
                echo '<ul class="submenu">'.LF.$subnav."\n</ul></li>";
            }
          ?>
          </ul>

          <div class="searchbar">
            <div id="jslib" class="text-center text-white small"></div>
          </div>

        </div>
      </div>
    </div>
  </nav>


  <div id="content">
  {STATUS_MESSAGE}<!--BE_MAIN_CONTENT_START//-->
<?php

    switch ($do) {

        case "profile":    //Profile
              include CMSGO_ROOT.'/include/inc_tmpl/profile.account.tmpl.php';
              break;

        case "files":    // File manager
              if ($p === 8) { //FTP File upload

                include CMSGO_ROOT.'/include/inc_tmpl/files.ftptakeover.tmpl.php';

              } elseif ($p === 4) {
                  include CMSGO_ROOT.'/include/inc_tmpl/files.actions.tmpl.php';
              } else {
                  include CMSGO_ROOT.'/include/inc_tmpl/files.reiter.tmpl.php'; //Files Navigation/Reiter
                  switch ($files_folder) {
                      case 0:    //Listing der Privaten Dateien
                              if (isset($_GET["mkdir"]) || (isset($_POST["dir_aktion"]) && intval($_POST["dir_aktion"]) == 1)) {
                                  include CMSGO_ROOT.'/include/inc_tmpl/files.private.newdir.tmpl.php';
                              }
                              if (isset($_GET["editdir"]) || (isset($_POST["dir_aktion"]) && intval($_POST["dir_aktion"]) == 2)) {
                                  include CMSGO_ROOT.'/include/inc_tmpl/files.private.editdir.tmpl.php';
                              }
                              if (isset($_GET["upload"]) || (isset($_POST["file_aktion"]) && intval($_POST["file_aktion"]) == 1)) {
                                  include CMSGO_ROOT.'/include/inc_tmpl/files.private.upload.tmpl.php';
                              }
                              if (isset($_GET["editfile"]) || (isset($_POST["file_aktion"]) && intval($_POST["file_aktion"]) == 2)) {
                                  include CMSGO_ROOT.'/include/inc_tmpl/files.private.editfile.tmpl.php';
                              }
                              if (!isset($_GET["upload"]) && !isset($_GET["editfile"]) && !isset($_GET["editdir"]) && !isset($_GET["mkdir"])) {
                                  include CMSGO_ROOT.'/include/inc_lib/files.private-functions.inc.php'; //Add listing function
                                include CMSGO_ROOT.'/include/inc_lib/files.private.additions.inc.php'; //additional privat functions
                              }
                              break;

                      case 1: //Funktionen zum Listen von Public Files
                              include CMSGO_ROOT.'/include/inc_lib/files.public-functions.inc.php'; //Public Listing-Funktionen einfügen
                              include CMSGO_ROOT.'/include/inc_tmpl/files.public.list.tmpl.php'; //Elemetares für Public Listing
                              break;

                      case 2:    //Dateien im Papierkorb
                              include CMSGO_ROOT.'/include/inc_tmpl/files.private.trash.tmpl.php';
                              break;

                      case 3:    //Dateisuche
                              include CMSGO_ROOT.'/include/inc_tmpl/files.search.tmpl.php';
                              break;
                  }
                  include CMSGO_ROOT.'/include/inc_tmpl/files.abschluss.tmpl.php'; //Abschließende Tabellenzeile = dicke Linie
              }
              break;

      case "messages":    //Messages
            if (empty($cmsgo['enable_backend_newsletter'])) {
                $do = 'default';
                $p = 0;
                include CMSGO_ROOT.'/include/inc_tmpl/be_start.tmpl.php';
                include CMSGO_TEMPLATE.'inc_default/startup.php';
                // echo cmsgoversionCheck();
                $cmsgo['be_parse_lang_process'] = true;
            } else {
                switch ($p) {
                    //case 0: include CMSGO_ROOT.'/include/inc_tmpl/message.center.tmpl.php'; break; //Messages Overview
                    //case 1: include CMSGO_ROOT.'/include/inc_tmpl/message.send.tmpl.php';   break;    //New Message
                    case 2: //Newsletter subscription
                        if ($_SESSION["wcs_user_admin"] == 1) {
                            include CMSGO_ROOT . '/include/inc_tmpl/message.subscription.tmpl.php';
                        }
                        break;
                    case 3: //Newsletter
                        if ($_SESSION["wcs_user_admin"] == 1) {
                            include CMSGO_ROOT . '/include/inc_tmpl/newsletter.list.tmpl.php';
                        }
                        break;
                    case 4: //Newsletter subscribers
                        if ($_SESSION["wcs_user_admin"] == 1) {
                            include CMSGO_ROOT . '/include/inc_tmpl/message.subscribers.tmpl.php';
                        }
                        break;
                }
            }
            break;

      case "modules":    //Modules
            // if a module is selected
            if (isset($cmsgo['modules'][$module])) {
                include $cmsgo['modules'][$module]['path'].'backend.default.php';
            }
            break;

      case 'admin':    //Administration
        if ($_SESSION["wcs_user_admin"] == 1) {
            switch ($p) {
            case 0: //User Administration
              switch (!empty($_GET['s']) ? intval($_GET["s"]) : 0) {
                  case 1: include CMSGO_ROOT.'/include/inc_tmpl/admin.newuser.tmpl.php';  break; //New User
                  case 2: include CMSGO_ROOT.'/include/inc_tmpl/admin.edituser.tmpl.php'; break; //Edit User
              }
              include CMSGO_ROOT.'/include/inc_tmpl/admin.listuser.tmpl.php';
              break;

            case 1: //Users and Groups
              include CMSGO_ROOT.'/include/inc_tmpl/admin.groups.tmpl.php';
              break;

            case 7: //File Categories
              include CMSGO_ROOT.'/include/inc_tmpl/admin.filecat.tmpl.php';
              break;

            case 8: //Page Layout
              include CMSGO_ROOT.'/include/inc_tmpl/admin.pagelayout.tmpl.php';
              break;

            case 11: //Templates
              include CMSGO_ROOT.'/include/inc_tmpl/admin.templates.tmpl.php';
              break;

            case 12: //Manage image alias
              include CMSGO_ROOT.'/include/inc_tmpl/admin.imagealiaslist.tmpl.php';
              break;

            case 13: //Manage alias structure und pages
              include CMSGO_ROOT.'/include/inc_tmpl/admin.aliaslist.tmpl.php';
              break;

            case 14: //Manage redirect entries
              include CMSGO_ROOT.'/include/inc_tmpl/admin.redirect.tmpl.php';
              break;

          }
        }
        break;

        // articles
      case "articles":
        $_SESSION['image_browser_article'] = 0; //set how image file browser should work
        switch ($p) {

            case 0: // List articles
                include CMSGO_ROOT.'/include/inc_tmpl/article.structlist.tmpl.php';
                $cmsgo['be_parse_lang_process'] = true;
                break;

            // Edit/create article
            case 1:
            case 2:
                include CMSGO_ROOT.'/include/inc_lib/contentpart.functions.php';
                include CMSGO_ROOT.'/include/inc_lib/article.editcontent.inc.php';

                break;

            case 3: // News
                include CMSGO_ROOT.'/include/inc_lib/news.inc.php';
                include CMSGO_ROOT.'/include/inc_tmpl/news.tmpl.php';
                break;

            case 6: // Artikel structur
              include CMSGO_ROOT.'/include/inc_lib/admin.structure.inc.php';
              if (isset($_GET["struct"])) {
                  include CMSGO_ROOT.'/include/inc_tmpl/admin.structform.tmpl.php';
              }
              break;
        }
        break;

      // about cmsgo
      case "about":
          include CMSGO_ROOT.'/include/inc_tmpl/about.tmpl.php';
          break;

      // start
      default:
          include CMSGO_ROOT.'/include/inc_tmpl/be_start.tmpl.php';
          include CMSGO_TEMPLATE.'inc_default/startup.php';
          // echo cmsgoversionCheck();
          $cmsgo['be_parse_lang_process'] = true;

    }
?>

    <!--BE_MAIN_CONTENT_END//-->
  </div>
</div>

<?php

//Set Focus for chat insert filed
set_chat_focus($do, $p);

//If new message was sent -> automatic forwarding to message center
forward_to($forward_to_message_center, CMSGO_URL."cmsgo.php?do=messages", 2500);

$GLOBALS['BE']['HEADER']['jquery.js'] = getJavaScriptSourceLink('include/inc_js/jquery/jquery-3.7.1.min.js');
$GLOBALS['BE']['HEADER']['jquery-sortable.js'] = getJavaScriptSourceLink('include/inc_js/jquery/jquery-sortable.min.js');
$BE['BODY_CLOSE']['bootstrap.min.js'] = getJavaScriptSourceLink('include/inc_js/bootstrap.bundle.min.js');
$BE['BODY_CLOSE']['cmsgo-addons.js'] = getJavaScriptSourceLink('include/inc_js/cmsgo-addons.js');

?>
<!-- cmsGO! BODY_CLOSE -->
<div id="browserModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body d-flex align-items-stretch modal-body-iframe">
        <iframe src="" id="infobrowser" class="iframe flex-grow-1" name="infobrowser" seamless="seamless" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-blue" data-dismiss="modal"><?php echo $BL['be_func_struct_close'] ?></button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
<?php
$BE['BODY_CLOSE']['bootstrap.min.js'] = getJavaScriptSourceLink('include/inc_js/bootstrap.bundle.min.js');

// retrieve complete processing time
list($usec, $sec) = explode(' ', microtime());
header('X-cmsgo-Page-Processed-In: ' . number_format(1000*($usec + $sec - $cmsgo_rendering_start), 3) .' ms');

$BE['HTML'] = ob_get_clean();

//    replace special backend sections -> good for additional code like custom JavaScript, CSS and so on
//    <!-- cmsgo BODY_CLOSE -->
//    <!-- cmsgo BODY_OPEN -->
//    <!-- cmsgo HEADER -->

// special body onload JavaScript
if ($body_onload) {
    $BE['HTML'] = str_replace('<body>', '<body '.$body_onload.'>', $BE['HTML']);
}

//$BE['HEADER'][] = '';

// html head section
$BE['HTML'] = str_replace('<!-- cmsGO! HEADER -->', implode(LF, $BE['HEADER']), $BE['HTML']);

// body open area
$BE['HTML'] = str_replace('<!-- cmsGO! BODY_OPEN -->', implode(LF, $BE['BODY_OPEN']), $BE['HTML']);

// body close area
$BE['HTML'] = str_replace('<!-- cmsGO! BODY_CLOSE -->', implode(LF, $BE['BODY_CLOSE']), $BE['HTML']);

// Show global system status message
$BE['HTML'] = str_replace('{STATUS_MESSAGE}', show_status_message(true), $BE['HTML']);

// return all
echo tokenize_urls( tokenize_forms($BE['HTML']) );
