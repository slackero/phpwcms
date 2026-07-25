<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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
    echo "<div class=\"table-responsive\">\n";
    echo "<table class=\"table table-sm\">\n";
    include_once PHPWCMS_ROOT."/include/inc_lib/files.private-delfilelist.inc.php";
    //echo "</table>\n";

    echo '<div class="text-center text-sm-left"><a class="btn btn-sm btn-blue mt-3" href="include/inc_act/act_file.php?trash=0|9"' .
         ' title="' . html_specialchars($BL['be_ftrash_delall']) . '" data-confirm-danger="' . html_specialchars($BL['be_ftrash_delall']) . '">' .
         $BL['be_ftrash_delallfiles'] . '</a></div>';
} else { //Wenn keinerlei Datensatz innerhalb Files durchlaufen wurde, dann
  echo $BL['be_ftrash_nofiles']."&nbsp;&nbsp;[<a href=\"phpwcms.php?do=files&amp;f=0\">".$BL['be_ftrash_show']."</a>]";
}
