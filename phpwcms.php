<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// set page processiong start time
list($usec, $sec) = explode(' ', microtime());
$phpwcms_rendering_start = $usec + $sec;

//define used var names
$body_onload                = '';
$forward_to_message_center  = false;
$wcsnav                     = array();
$indexpage                  = array();
$phpwcms                    = array('SESSION_START' => true);
$BL                         = array();
$BE                         = array('HTML' => '', 'BODY_OPEN' => array(), 'BODY_CLOSE' => array(), 'HEADER' => array(), 'LANG' => 'en');
$PHPWCMS_ROOT               = dirname(__FILE__);

require_once $PHPWCMS_ROOT.'/include/config/conf.inc.php';
require_once $PHPWCMS_ROOT.'/include/inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';

// check against user's language
if(!empty($_SESSION["wcs_user_lang"]) && preg_match('/[a-z]{2}/i', $_SESSION["wcs_user_lang"])) {
    $BE['LANG'] = $_SESSION["wcs_user_lang"];
}

checkLogin();
validate_csrf_tokens();
define('CSRF_GET_TOKEN', get_token_get_string());

require_once PHPWCMS_ROOT.'/include/inc_lib/backend.functions.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/default.backend.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php'; //load default language EN
include_once PHPWCMS_ROOT."/include/inc_lang/code.lang.inc.php";

$BL['modules'] = array();

if(!empty($_SESSION["wcs_user_lang_custom"])) {
    //use custom lang if available -> was set in login.php
    $BL['merge_lang_array'][0]      = $BL['be_admin_optgroup_label'];
    $BL['merge_lang_array'][1]      = $BL['be_cnt_field'];
    include PHPWCMS_ROOT.'/include/inc_lang/backend/'. $BE['LANG'] .'/lang.inc.php';
    $BL['be_admin_optgroup_label']  = array_merge($BL['merge_lang_array'][0], $BL['be_admin_optgroup_label']);
    $BL['be_cnt_field']             = array_merge($BL['merge_lang_array'][1], $BL['be_cnt_field']);
    unset($BL['merge_lang_array']);
}

require_once PHPWCMS_ROOT.'/include/inc_lib/navi_text.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/checkmessage.inc.php';
require_once PHPWCMS_ROOT.'/include/config/conf.template_default.inc.php';
require_once PHPWCMS_ROOT.'/include/config/conf.indexpage.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/imagick.convert.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/constants/timestamp.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.iptc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/classes/class.convertibletimestamp.php';

// check modules
require_once PHPWCMS_ROOT.'/include/inc_lib/modules.check.inc.php';

// load array with actual content types
include PHPWCMS_ROOT.'/include/inc_lib/article.contenttype.inc.php';

$BL['be_admin_struct_index'] = html_specialchars($indexpage['acat_name']);

$subnav                             = ''; //Sub Navigation
$p                                  = isset($_GET["p"])  ? intval($_GET["p"]) : 0; //which page should be opened
$do                                 = isset($_GET["do"]) ? $_GET["do"] : 'default'; //which backend section and which $do action
$module                             = isset($_GET['module'])  ? clean_slweg($_GET['module']) : ''; //which module
$phpwcms['be_parse_lang_process']   = false; // limit parsing for BBCode/BraceCode languages only to some sections

switch ($do) {

    case "articles":    //articles
        include PHPWCMS_ROOT.'/include/inc_lib/admin.functions.inc.php';
        $wcsnav["articles"] = "<strong class=\"navtexta\">".$wcsnav["articles"]."</strong>";
        include PHPWCMS_ROOT.'/include/inc_lib/article.functions.inc.php'; //load article funtions
        $subnav .= subnavtext($BL['be_subnav_article_center'], "phpwcms.php?do=articles", $p, "", 0);
        $subnav .= subnavtext($BL['be_subnav_article_new'], "phpwcms.php?do=articles&amp;p=1&amp;struct=0", $p, "1", 0);
        $subnav .= '<tr><td colspan="2"><img src="img/leer.gif" height="5" width="1" alt="" /></td></tr>'."\n";
        $subnav .= subnavtext($BL['be_news'], "phpwcms.php?do=articles&amp;p=3", $p, "3", 0);
        break;

    case "files":       //files
        $wcsnav["files"] = "<strong class=\"navtexta\">".$wcsnav["files"]."</strong>";
        $subnav .= subnavtext($BL['be_subnav_file_center'], "phpwcms.php?do=files", $p, "", 0);

        // based on pwmod by pagewerkstatt.ch 12/2012
        $subnav .= subnavtext($BL['be_subnav_file_actions'], "phpwcms.php?do=files&amp;p=4", $p, "4", 0);

        $subnav .= subnavtext($BL['be_file_multiple_upload'], "phpwcms.php?do=files&amp;p=8", $p, "8", 0);
        break;

    case "modules":     //modules
        $wcsnav["modules"] = "<strong class=\"navtexta\">".$wcsnav["modules"]."</strong>";

        foreach($phpwcms['modules'] as $value) {

            if($value['type'] == 2) {
                continue;
            }

            $subnav .= subnavtext($BL['modules'][ $value['name'] ]['backend_menu'], 'phpwcms.php?do=modules&amp;module='.$value['name'], $module, $value['name'], 0);

        }
        break;

    case "messages":    //messages
        $wcsnav["messages"] = "<strong class=\"navtexta\">".$wcsnav["messages"]."</strong>";
        if(isset($_SESSION["wcs_user_admin"]) && $_SESSION["wcs_user_admin"] == 1) {
            $subnav .= subnavtext($BL['be_subnav_msg_newslettersend'], "phpwcms.php?do=messages&amp;p=3", $p, "3", 0);
            $subnav .= subnavtext($BL['be_subnav_msg_subscribers'], "phpwcms.php?do=messages&amp;p=4", $p, "4", 0);
            $subnav .= subnavtext($BL['be_subnav_msg_newsletter'], "phpwcms.php?do=messages&amp;p=2", $p, "2", 0);

            if(!empty($phpwcms['enable_messages'])) {
                $subnav .= '<tr><td colspan="2"><img src="img/leer.gif" height="5" width="1" alt="" /></td></tr>'."\n";
            }
        }
        if(!empty($phpwcms['enable_messages'])) {
            $subnav .= subnavtext($BL['be_subnav_msg_center'], "phpwcms.php?do=messages", $p, "", 0);
            $subnav .= subnavtext($BL['be_subnav_msg_new'], "phpwcms.php?do=messages&amp;p=1", $p, "1", 0);
        }
        break;

    case "discuss":     //discuss
        $wcsnav["discuss"] = "<strong class=\"navtexta\">".$wcsnav["discuss"]."</strong>";
        break;

    case "chat":        //chat
        $wcsnav["chat"] = "<strong class=\"navtexta\">".$wcsnav["chat"]."</strong>";
        $subnav .= subnavtext($BL['be_subnav_chat_main'], "phpwcms.php?do=chat", $p, "", 0);
        $subnav .= subnavtext($BL['be_subnav_chat_internal'], "phpwcms.php?do=chat&amp;p=1", $p, "1", 0);
        break;

    case "profile":     //profile
        $wcsnav["profile"] = "<strong class=\"navtexta\">".$wcsnav["profile"]."</strong>";
        if(!empty($_POST["form_aktion"])) {
            switch($_POST["form_aktion"]) { //Aktualisieren der wcs account & profile Daten
                case "update_account":  include PHPWCMS_ROOT.'/include/inc_lib/profile.updateaccount.inc.php';
                                        break;
                case "update_detail":   include PHPWCMS_ROOT.'/include/inc_lib/profile.update.inc.php';
                                        break;
                case "create_detail":   include PHPWCMS_ROOT.'/include/inc_lib/profile.create.inc.php';
                                        break;
            }
        }
        $subnav .= subnavtext($BL['be_subnav_profile_login'], "phpwcms.php?do=profile", $p, "", 0);
        $subnav .= subnavtext($BL['be_subnav_profile_personal'], "phpwcms.php?do=profile&amp;p=1", $p, "1", 0);
        break;

    case "logout":      //Logout
        logout_user();
        break;

    case "admin":       //Admin
        if(!empty($_SESSION["wcs_user_admin"])) {
            include PHPWCMS_ROOT.'/include/inc_lib/admin.functions.inc.php';
            $subnav .= subnavtext($BL['be_subnav_admin_sitestructure'], "phpwcms.php?do=admin&amp;p=6", $p, "6", 0);
            $subnav .= '<tr><td colspan="2"><img src="img/leer.gif" height="5" width="1" alt="" /></td></tr>'."\n";
            $subnav .= subnavtext($BL['be_subnav_admin_pagelayout'], "phpwcms.php?do=admin&amp;p=8", $p, "8", 0);
            $subnav .= subnavtext($BL['be_subnav_admin_templates'], "phpwcms.php?do=admin&amp;p=11", $p, "11", 0);
            if(!empty($phpwcms['enable_deprecated'])) {
                $subnav .= subnavtext($BL['be_subnav_admin_css'], "phpwcms.php?do=admin&amp;p=10", $p, "10", 0);
            }
            $subnav .= '<tr><td colspan="2"><img src="img/leer.gif" height="5" width="1" alt="" /></td></tr>'."\n";
            $subnav .= subnavtext($BL['be_subnav_admin_users'], "phpwcms.php?do=admin", $p, "", 0);
            if(!empty($phpwcms['usergroup_support'])) {
                $subnav .= subnavtext($BL['be_subnav_admin_groups'], "phpwcms.php?do=admin&amp;p=1", $p, "1", 0);
            }
            $subnav .= '<tr><td colspan="2"><img src="img/leer.gif" height="5" width="1" alt="" /></td></tr>'."\n";
            //$subnav .= subnavtext($BL['be_admin_keywords'], "phpwcms.php?do=admin&amp;p=5", $p, "5", 0);
            $subnav .= subnavtext($BL['be_subnav_admin_filecat'], "phpwcms.php?do=admin&amp;p=7", $p, "7", 0);
            if(!empty($phpwcms['enable_deprecated'])) {
                $subnav .= subnavtext($BL['be_subnav_admin_starttext'], "phpwcms.php?do=admin&amp;p=12", $p, "12", 0);
            }
            $subnav .= subnavtext($BL['be_alias'], 'phpwcms.php?do=admin&amp;p=13', $p, "13", 0);
            $subnav .= subnavtext($BL['be_link'] . ' &amp; ' . $BL['be_redirect'], 'phpwcms.php?do=admin&amp;p=14', $p, "14", 0);

            $subnav .= '<tr><td colspan="2"><img src="img/leer.gif" height="5" width="1" alt="" /></td></tr>'."\n";
            $subnav .= subnavtext($BL['be_flush_image_cache'], '#', 1, 0, 0, 'onclick="return flush_image_cache(this,\'include/inc_act/ajax_connector.php?action=flush_image_cache&value=1\');" ');
            $subnav .= subnavtext($BL['be_cnt_move_deleted'], 'include/inc_act/act_file.php?movedeletedfiles='. $_SESSION["wcs_user_id"], 1, 0, 0, 'onclick="return confirm(\''.$BL['be_cnt_move_deleted_msg'].'\');" ');
            $subnav .= '<tr><td colspan="2"><img src="img/leer.gif" height="5" width="1" alt="" /></td></tr>'."\n";
            $subnav .= subnavtextext('phpinfo()', 'include/inc_act/act_phpinfo.php', '_blank', 0);
        }
        break;

    case 'home':
        $do = 'default';
        break;

}

//Subnav Wrap Text Tabelle
if($subnav) {
    $subnav  = '<table border="0" cellpadding="0" cellspacing="0" summary="">'.LF.$subnav;
    $subnav .= "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"15\" alt=\"\" /></td></tr>\n</table>";
}

//Wenn der User kein Admin ist, anderenfalls
if(empty($_SESSION["wcs_user_admin"])) {
    unset($wcsnav["admin"]);
} elseif($do  == "admin") {
    $wcsnav["admin"] = '<strong class="navtexta">'.$wcsnav["admin"].'</strong>';
}

//script chaching to allow header redirect
ob_start(); //without Compression

// set correct content type for backend
header('Content-Type: text/html; charset='.PHPWCMS_CHARSET);

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="<?php echo $BE['LANG']; ?>">
<head><?php printf(PHPWCMS_HEADER_COMMENT, ''); ?>
    <title><?php echo $BL['be_page_title'].' - '.PHPWCMS_HOST ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo PHPWCMS_CHARSET ?>">
    <link href="include/inc_css/phpwcms.min.css" rel="stylesheet" type="text/css">
    <meta name="robots" content="noindex, nofollow">
<?php

$BE['HEADER']['alias_slash_var'] = ' <script type="text/javascript"> var aliasAllowSlashes=' . (PHPWCMS_ALIAS_WSLASH ? 'true' : 'false') . ', aliasUtf8=' . (PHPWCMS_ALIAS_UTF8 ? 'true' : 'false') . '; </script>';

initMootools();

if($do == "messages" && $p == 1) {

    include PHPWCMS_ROOT.'/include/inc_lib/message.sendjs.inc.php';

} elseif($do == "articles") {

    if($p == 2 && isset($_GET["aktion"]) && intval($_GET["aktion"]) == 2) {
        initJsOptionSelect();
    }
    if(($p == 1) || ($p == 2 && isset($_GET["aktion"]) && intval($_GET["aktion"]) == 1)) {
        initJsCalendar();
    }

} elseif($do == 'admin' && ($p == 6 || $p == 11)) {

    // struct editor
    initJsOptionSelect();

}

if($BE['LANG'] == 'ar') {
    $BE['HEADER'][] = '<style type="text/css">' . LF . '<!--' . LF . '* {direction: rtl;}' . LF . '// -->' . LF . '</style>';
}

?>
<!-- phpwcms HEADER -->
</head>
<body<?php echo $body_onload ?>><!-- phpwcms BODY_OPEN -->
<table width="830" border="0" align="center" cellpadding="0" cellspacing="0" summary="main layout structure">
    <tr>
        <td colspan="6">
        <div style="position:relative;margin:15px 15px 7px 15px;">
            <a href="phpwcms.php?do=home" target="_top" class="d-inline-block"><img src="img/backend/phpwcms-logo.png" srcset="img/backend/phpwcms-logo.svg" alt="phpwcms v<?php echo html_specialchars(PHPWCMS_VERSION); ?>" width="112" height="31" class="img-fluid" /></a>
            <a href="<?php echo PHPWCMS_URL ?>" class="v10" style="position:absolute;right:0;bottom:5px;color:#FFFFFF" target="_blank"><?php echo PHPWCMS_HOST ?></a>
        </div>
        </td>
    </tr>
    <tr>
        <td valign="top" class="backend-menu-left"><img src="img/backend/backend_r3_c1.png" alt="" width="15" height="40"></td>
        <td colspan="4" valign="top" class="backend-menu navtext"><?php

            // create backend main navigation
            echo '<a href="phpwcms.php?do=home">' . ($do == 'default' ? '<strong>HOME</strong>' : 'HOME') . '</a>';
            echo implode('', $wcsnav);

            ?><a href="phpwcms.php?do=logout" target="_top" class="backend-menu-logout"><?php echo $BL['be_nav_logout'] ?></a>
        </td>
        <td valign="top" class="backend-menu-right"><img src="img/backend/backend_r3_c7.png" alt="" width="15" height="40"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td width="15" bgcolor="#FFFFFF" style="background: url(img/backend/preinfo2_r7_c2.gif) repeat-y;"><img src="img/leer.gif" alt="" width="15" height="1"></td>
        <td width="175" valign="top" bgcolor="#FFFFFF"><?php

        echo $subnav . LF;

        ?>
        <form action="phpwcms.php?do=home" method="POST" class="backend-search">

            <h1 class="title" style="margin:0 0 3px 0;"><?php echo $BL['be_ctype_search'] ?></h1>
            <input type="search" name="backend_search_input" value="<?php

                if(isset($_POST['backend_search_input'])) {
                    $_SESSION['phpwcms_backend_search'] = clean_slweg($_POST['backend_search_input']);
                }

                if(!empty($_SESSION['phpwcms_backend_search'])) {
                    echo html_specialchars($_SESSION['phpwcms_backend_search']);
                }
            ?>" class="backend-search-input v11" /><input type="image" src="img/famfamfam/magnifier.png" class="backend-search-button" />

        </form>


        <h1 class="title" style="margin:1em 0 3px 0;"><?php echo $BL['usr_online'] ?></h1>

        <?php echo online_users('<br />', '<span class="subnavinactive">|</span>'); ?>

        </td>
      <td width="10" bgcolor="#FFFFFF"><img src="img/leer.gif" alt="" width="10" height="1"></td>
      <td width="15" bgcolor="#FFFFFF" style="background:url(img/backend/dividerA.gif) repeat-y;"><img src="img/leer.gif" alt="" width="15" height="200"></td>
      <td width="540" valign="top" bgcolor="#FFFFFF" class="v11b width540" id="be_main_content">{STATUS_MESSAGE}{BE_PARSE_LANG}<!--BE_MAIN_CONTENT_START//-->
<?php

    switch ($do) {

        case "profile": //Profile
            if ($p === 1) {
                include PHPWCMS_ROOT . '/include/inc_tmpl/profile.data.tmpl.php';
            } else {
                include PHPWCMS_ROOT . '/include/inc_tmpl/profile.account.tmpl.php';
            }
            break;

        case "files":   // File manager
            if ($p === 8) { //FTP File upload
                include PHPWCMS_ROOT . '/include/inc_tmpl/files.ftptakeover.tmpl.php';
                // based on pwmod by pagewerkstatt.ch 12/2012
            } elseif ($p === 4) {
                    include PHPWCMS_ROOT . '/include/inc_tmpl/files.actions.tmpl.php';
            } else {
                include PHPWCMS_ROOT . '/include/inc_tmpl/files.reiter.tmpl.php'; //Files Navigation/Reiter
                switch ($files_folder) {
                    case 0: //Listing der Privaten Dateien
                        include PHPWCMS_ROOT . '/include/inc_lib/files.private-functions.inc.php'; //Listing-Funktionen einfügen

                        if (isset($_GET["mkdir"]) || (isset($_POST["dir_aktion"]) && intval($_POST["dir_aktion"]) == 1)) {
                            include PHPWCMS_ROOT . '/include/inc_tmpl/files.private.newdir.tmpl.php';
                        } elseif (isset($_GET["editdir"]) || (isset($_POST["dir_aktion"]) && intval($_POST["dir_aktion"]) == 2)) {
                            include PHPWCMS_ROOT . '/include/inc_tmpl/files.private.editdir.tmpl.php';
                        } elseif (isset($_GET["upload"]) || (isset($_POST["file_aktion"]) && intval($_POST["file_aktion"]) == 1)) {
                            include PHPWCMS_ROOT . '/include/inc_tmpl/files.private.upload.tmpl.php';
                        } elseif (isset($_GET["editfile"]) || (isset($_POST["file_aktion"]) && intval($_POST["file_aktion"]) == 2)) {
                            include PHPWCMS_ROOT . '/include/inc_tmpl/files.private.editfile.tmpl.php';
                        } else {
                            include PHPWCMS_ROOT . '/include/inc_lib/files.private.additions.inc.php'; //Zusätzliche Private Funktionen
                        }
                        break;

                    case 1: //Funktionen zum Listen von Public Files
                        include PHPWCMS_ROOT . '/include/inc_lib/files.public-functions.inc.php'; //Public Listing-Funktionen einfügen
                        include PHPWCMS_ROOT . '/include/inc_tmpl/files.public.list.tmpl.php'; //Elemetares für Public Listing
                        break;

                    case 2: //Dateien im Papierkorb
                        include PHPWCMS_ROOT . '/include/inc_tmpl/files.private.trash.tmpl.php';
                        break;

                    case 3: //Dateisuche
                        include PHPWCMS_ROOT . '/include/inc_tmpl/files.search.tmpl.php';
                        break;
                }
                include PHPWCMS_ROOT . '/include/inc_tmpl/files.abschluss.tmpl.php'; //Abschließende Tabellenzeile = dicke Linie
            }
            break;

        case "chat":    //Chat
            if ($p === 1) {
                include PHPWCMS_ROOT . '/include/inc_tmpl/chat.list.tmpl.php';  //Chat/Listing
            } else {
                include PHPWCMS_ROOT . '/include/inc_tmpl/chat.main.tmpl.php';  //Chat Startseite
            }
            break;

        case "messages":    //Messages
            switch ($p) {
                case 0:
                    include PHPWCMS_ROOT . '/include/inc_tmpl/message.center.tmpl.php';
                    break; //Messages Overview
                case 1:
                    include PHPWCMS_ROOT . '/include/inc_tmpl/message.send.tmpl.php';
                    break;    //New Message
                case 2: //Newsletter subscription
                    if ($_SESSION["wcs_user_admin"] == 1) {
                        include PHPWCMS_ROOT . '/include/inc_tmpl/message.subscription.tmpl.php';
                    }
                    break;
                case 3: //Newsletter
                    if ($_SESSION["wcs_user_admin"] == 1) {
                        include PHPWCMS_ROOT . '/include/inc_tmpl/newsletter.list.tmpl.php';
                    }
                    break;
                case 4: //Newsletter subscribers
                    if ($_SESSION["wcs_user_admin"] == 1) {
                        include PHPWCMS_ROOT . '/include/inc_tmpl/message.subscribers.tmpl.php';
                    }
                    break;
            }
            break;

        case "modules": //Modules
            // if a module is selected
            if (isset($phpwcms['modules'][$module])) {
                include $phpwcms['modules'][$module]['path'] . 'backend.default.php';
            }
            break;

        case "admin":   //Administration
            if ($_SESSION["wcs_user_admin"] == 1) {
                switch ($p) {
                    case 0: //User Administration
                        switch (!empty($_GET['s']) ? intval($_GET["s"]) : 0) {
                            case 1:
                                include PHPWCMS_ROOT . '/include/inc_tmpl/admin.newuser.tmpl.php';
                                break; //New User
                            case 2:
                                include PHPWCMS_ROOT . '/include/inc_tmpl/admin.edituser.tmpl.php';
                                break; //Edit User
                        }
                        include PHPWCMS_ROOT . '/include/inc_tmpl/admin.listuser.tmpl.php';
                        break;

                    case 1: //Users and Groups
                        //enym new group management tool
                        include PHPWCMS_ROOT . '/include/inc_tmpl/admin.groups.tmpl.php';
                        break;

                    case 2: //Settings
                        include PHPWCMS_ROOT . '/include/inc_tmpl/admin.settings.tmpl.php';
                        break;

                    case 5: //Keywords
                        include PHPWCMS_ROOT . '/include/inc_tmpl/admin.keyword.tmpl.php';
                        break;

                    case 6: //article structure
                            include PHPWCMS_ROOT . '/include/inc_lib/admin.structure.inc.php';
                        if (isset($_GET["struct"])) {
                            //include PHPWCMS_ROOT.'/include/inc_lib/article.contenttype.inc.php'; //loading array with actual content types
                            include PHPWCMS_ROOT . '/include/inc_tmpl/admin.structform.tmpl.php';
                        } else {
                            include PHPWCMS_ROOT . '/include/inc_tmpl/admin.structlist.tmpl.php';
                            $phpwcms['be_parse_lang_process'] = true;
                        }
                        break;

                    case 7: //File Categories
                        include PHPWCMS_ROOT . '/include/inc_tmpl/admin.filecat.tmpl.php';
                        break;

                    case 8: //Page Layout
                        include PHPWCMS_ROOT . '/include/inc_tmpl/admin.pagelayout.tmpl.php';
                        break;

                    case 10: //Frontend CSS
                        if (!empty($phpwcms['enable_deprecated'])) {
                            include PHPWCMS_ROOT . '/include/inc_tmpl/admin.frontendcss.tmpl.php';
                        }
                        break;

                    case 11: //Templates
                        include PHPWCMS_ROOT . '/include/inc_tmpl/admin.templates.tmpl.php';
                        break;

                    case 12: //Default backend starup HTML
                        if (!empty($phpwcms['enable_deprecated'])) {
                            include PHPWCMS_ROOT . '/include/inc_tmpl/admin.startup.tmpl.php';
                        }
                        break;

                    //Default backend sitemap HTML
                    case 13:
                        include PHPWCMS_ROOT . '/include/inc_tmpl/admin.aliaslist.tmpl.php';
                        break;

                    //Default backend sitemap HTML
                    case 14:
                        include PHPWCMS_ROOT . '/include/inc_tmpl/admin.redirect.tmpl.php';
                        break;
                }
            }
            break;

        // articles
        case "articles":
            $_SESSION['image_browser_article'] = 0; //set how image file browser should work
            switch ($p) {
                    // List articles
                case 0:
                    include PHPWCMS_ROOT . '/include/inc_tmpl/article.structlist.tmpl.php';
                    $phpwcms['be_parse_lang_process'] = true;
                    break;

                // Edit/create article
                case 1:
                case 2:
                    include PHPWCMS_ROOT . '/include/inc_lib/article.editcontent.inc.php';
                    break;

                // News
                case 3:
                    include PHPWCMS_ROOT . '/include/inc_lib/news.inc.php';
                    include PHPWCMS_ROOT . '/include/inc_tmpl/news.tmpl.php';
                    break;
            }
            break;

        // about phpwcms
        case "about":
            include PHPWCMS_ROOT . '/include/inc_tmpl/about.tmpl.php';
            break;

        // start
        default:
            include PHPWCMS_ROOT . '/include/inc_tmpl/be_start.tmpl.php';
            include PHPWCMS_TEMPLATE . 'inc_default/startup.php';
            echo phpwcmsversionCheck();
            $phpwcms['be_parse_lang_process'] = true;
    }

?>

    <!--BE_MAIN_CONTENT_END//--></td>
      <td width="15" bgcolor="#FFFFFF" style="background:url(img/backend/preinfo2_r7_c7.gif) repeat-y right;"><img src="img/leer.gif" alt="" width="15" height="1"></td>
    </tr>
    <tr>
      <td><img src="img/backend/backend_a_r1_c1.gif" alt="" width="15" height="15" border="0"></td>
      <td colspan="4" valign="bottom" bgcolor="#FFFFFF" class="navtext"><img src="img/backend/backend_r6_c2.jpg" alt="" width="740" height="15" border="0"></td>
      <td valign="bottom" class="navtext"><img src="img/backend/backend_a_r1_c7.gif" alt="" width="15" height="15" border="0"></td>
  </tr>
    <tr>
      <td width="15"><img src="img/leer.gif" alt="" width="14" height="17"></td>
      <td colspan="5" valign="bottom" class="navtext darkblue" style="padding: 8px 0 15px 0;">
            <a href="http://www.phpwcms.org" title="phpwcms <?php echo PHPWCMS_VERSION; ?>" target="_blank">phpwcms</a>
            &copy; 2002&#8212;<?php echo date('Y'); ?>
            <a href="mailto:og@phpwcms.org?subject=phpwcms+<?php echo rawurlencode(PHPWCMS_VERSION); ?>">Oliver Georgi</a>.
            <a href="phpwcms.php?do=about" title="<?php echo $BL['be_aboutlink_title'] ?>"><?php echo $BL['be_licensed_under_GPL']; ?></a>
            <?php echo $BL['be_extensions_copyright']; ?>
        </td>
  </tr>
</table>
<?php

//Set Focus for chat insert filed
set_chat_focus($do, $p);

//If new message was sent -> automatic forwarding to message center
forward_to($forward_to_message_center, PHPWCMS_URL."phpwcms.php?do=messages", 2500);


?>
<!-- phpwcms BODY_CLOSE -->
</body>
</html>
<?php

// retrieve complete processing time
list($usec, $sec) = explode(' ', microtime());
header('X-phpwcms-Page-Processed-In: ' . number_format(1000*($usec + $sec - $phpwcms_rendering_start), 3) .' ms');

$BE['HTML'] = ob_get_clean();

// Load ToolTip JS only when necessary
if(strpos($BE['HTML'], 'Tip(')) {
    $BE['BODY_CLOSE']['wz_tooltip.js'] = getJavaScriptSourceLink('include/inc_js/wz_tooltip.js', '');
}

//  parse for backend languages
backend_language_parser();

//  replace special backend sections -> good for additional code like custom JavaScript, CSS and so on
//  <!-- phpwcms BODY_CLOSE -->
//  <!-- phpwcms BODY_OPEN -->
//  <!-- phpwcms HEADER -->

// special body onload JavaScript
if($body_onload) {
    $BE['HTML'] = str_replace('<body>', '<body '.$body_onload.'>', $BE['HTML']);
}

$BE['HEADER']['textarea.autosize.js'] = getJavaScriptSourceLink('include/inc_js/autosize.min.js');
$BE['HEADER']['phpwcms.js'] = getJavaScriptSourceLink('include/inc_js/phpwcms.min.js');

// html head section
$BE['HTML'] = str_replace('<!-- phpwcms HEADER -->', implode(LF, $BE['HEADER']), $BE['HTML']);

// body open area
$BE['HTML'] = str_replace('<!-- phpwcms BODY_OPEN -->', implode(LF, $BE['BODY_OPEN']), $BE['HTML']);

// body close area
$BE['HTML'] = str_replace('<!-- phpwcms BODY_CLOSE -->', implode(LF, $BE['BODY_CLOSE']), $BE['HTML']);

// Show global system status message
$BE['HTML'] = str_replace('{STATUS_MESSAGE}', show_status_message(true), $BE['HTML']);

// return all
echo tokenize_urls( tokenize_forms($BE['HTML']) );
