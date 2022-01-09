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

//Feststellen, ob überhaupt Dateien/Ordner im Papierkorb des Users vorhanden sind
$count_user_files = _dbQuery("SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE f_uid=".$_SESSION["wcs_user_id"]." AND f_trash=1", 'COUNT');

//Wenn überhaupt Papierkorb-Dateien für User vorhanden, dann Listing
if($count_user_files) {
    //Beginn Tabelle für Dateilisting
    echo "<table width=\"538\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"1\"></td></tr>\n";
    include_once PHPWCMS_ROOT."/include/inc_lib/files.private-delfilelist.inc.php";
    echo "</table>\n";
    ?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr><td colspan="2" bgcolor="#9BBECA"><img src="img/leer.gif" width="1" height="1" alt=""></td><tr>
    <tr><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt=""></td><tr>
    <tr>
        <td width="19"><img src="img/leer.gif" width="6" height="1" alt=""><img src="img/button/trash_13x13_1.gif" width="13" height="13" alt=""></td>
        <td width="519" class="msglist">&nbsp;<strong><?php

        echo "<a href=\"include/inc_act/act_file.php?trash=0|9".
             "\" title=\"".$BL['be_ftrash_delall']."\" onclick=\"return confirm('". str_replace("\n", "\\n", $BL['be_ftrash_delall'])."');\">".
             $BL['be_ftrash_delallfiles']."</a>";

        ?></strong></td>
    <tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td><tr>
    </table>
<?php

    //Ende Tabelle
} else { //Wenn keinerlei Datensatz innerhalb Files durchlaufen wurde, dann
    echo "<img src=\"img/leer.gif\" width=\"1\" height=\"6\"><br />".$BL['be_ftrash_nofiles']."&nbsp;&nbsp;";
	echo "[<a href=\"phpwcms.php?do=files&amp;f=0\">".$BL['be_ftrash_show']."</a>]";
    echo "<br /><img src=\"img/leer.gif\" width=\"1\" height=\"6\">";
}
