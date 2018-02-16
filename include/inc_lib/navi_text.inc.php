<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 **/

// Main Backend Nav Definition

$wcsnav = array();

$wcsnav["articles"]   = '<a href="cmsgo.php?do=articles">'.$BL['be_nav_articles'].'</a>';
$wcsnav["files"]      = '<a href="cmsgo.php?do=files">'.$BL['be_nav_files'].'</a>';
$wcsnav["modules"]    = '<a href="cmsgo.php?do=modules">'.$BL['be_nav_modules'].'</a>';
$wcsnav["messages"]   = '<a href="cmsgo.php?do=messages&amp;p=4">'.$BL['be_nav_messages'].'</a>';

if(!empty($cmsgo['enable_chat'])) {
    $wcsnav["chat"]   = '<a href="cmsgo.php?do=chat">'.$BL['be_nav_chat'].'</a>';
}

$wcsnav["profile"]    = '<a href="cmsgo.php?do=profile">'.$BL['be_nav_profile'].'</a>';
$wcsnav["admin"]      = '<a href="cmsgo.php?do=articles&amp;p=0">'.$BL['be_nav_admin'].'</a>';
$wcsnav["navspace1"]  = '';

// add usergroup names
$groupnames = array(
    'artcent' => $BL['be_nav_articles'].' - '.$BL['be_subnav_article_center'],
    'artnew' => $BL['be_nav_articles'].' - '.$BL['be_subnav_article_new'],
    'artstruc' => $BL['be_nav_articles'].' - '.$BL['be_subnav_admin_sitestructure'],
    'artnews' => $BL['be_nav_articles'].' - '.$BL['be_news'],
    'file' => $BL['be_nav_files'],
    'filecent' => $BL['be_nav_files'].' - '.$BL['be_subnav_file_center'],
    'fileaction' => $BL['be_nav_files'].' - '.$BL['be_subnav_file_actions'],
    'fileupload' => $BL['be_nav_files'].' - '.$BL['be_file_multiple_upload'],
    'module' => $BL['be_nav_modules'],
    'nl' => $BL['be_nav_messages'],
    'nllist' => $BL['be_nav_messages'].' - '.$BL['be_subnav_msg_newslettersend'],
    'nlrecip' => $BL['be_nav_messages'].' - '.$BL['be_subnav_msg_subscribers'],
    'nlabo' => $BL['be_nav_messages'].' - '.$BL['be_subnav_msg_newsletter'],
    'adm' => $BL['be_nav_admin'],
    'admlayout' => $BL['be_nav_admin'].' - '.$BL['be_subnav_admin_pagelayout'],
    'admtempl' => $BL['be_nav_admin'].' - '.$BL['be_subnav_admin_templates'],
    'admuser' => $BL['be_nav_admin'].' - '.$BL['be_subnav_admin_users'],
    'admugroup' => $BL['be_nav_admin'].' - '.$BL['be_subnav_admin_groups'],
    'admfc' => $BL['be_nav_admin'].' - '.$BL['be_subnav_admin_filecat'],
    'admalias' => $BL['be_nav_admin'].' - '.$BL['be_alias'],
    'admialias' => $BL['be_nav_admin'].' - '.$BL['be_imagealias'],
    'admctptemp' => $BL['be_nav_admin'].' - '.$BL['be_ctptemp'],
    'admlink' => $BL['be_nav_admin'].' - '.$BL['be_link'],
    'profile' => $BL['be_nav_profile'],
);
