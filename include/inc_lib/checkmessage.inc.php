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

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//Check is actual user has new messages waiting
$sql = "SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_message WHERE msg_uid=".intval($_SESSION["wcs_user_id"])." AND msg_read=0";
if(($check = _dbQuery($sql, 'COUNT'))) {

    $wcsnav["navspace1"]  = '<a href="phpwcms.php?do=messages" title="'.$check.' new messages waiting!">'.
    $wcsnav["navspace1"] .= '<img src="img/symbole/new_mail.gif" border="0" alt="" /></a>';
    $new_mail_waiting = 1;

}
