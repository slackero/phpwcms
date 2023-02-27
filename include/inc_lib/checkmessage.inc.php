<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//Check is actual user has new messages waiting
$sql = "SELECT COUNT(*) FROM ".DB_PREPEND."cmsgo_message WHERE msg_uid=".intval($_SESSION["wcs_user_id"])." AND msg_read=0";
if(($check = _dbQuery($sql, 'COUNT'))) {

    $wcsnav["navspace1"]  = '<a href="cmsgo.php?do=messages" title="'.$check.' new messages waiting!">'.
    $wcsnav["navspace1"] .= '<img src="img/symbole/new_mail.gif" border="0" alt="" /></a>';
    $new_mail_waiting = 1;

}
