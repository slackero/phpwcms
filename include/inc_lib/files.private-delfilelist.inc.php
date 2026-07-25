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

// list trashed files
$file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_uid=".$_SESSION["wcs_user_id"]." AND f_kid=1 AND f_trash=1 ORDER BY f_name";
$file_result = _dbQuery($file_sql);

if(isset($file_result[0]['f_id'])) {

    $file_durchlauf = 0;
    $bg_toggle = false;

    foreach($file_result as $file_row) {

        $filename = html($file_row["f_name"]);
        $bg_toggle = !$bg_toggle;
        $row_class = $bg_toggle ? ' class="file-row-even"' : ' class="file-row-odd"';

        echo '<tr'.$row_class.">\n";
        echo '<td width="30">';
             echo '<i class="fa fa-fw fa-'.ext_icon($file_row["f_ext"]).'" data-toggle="tooltip" data-html="true" title="ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'&lt;br&gt;Name: '.html($file_row["f_name"]).'"></i>';
        echo "</td>\n<td>";
        echo '<a href="fileinfo.php?fid='.$file_row["f_id"];
        echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
        echo $filename."</a></td>\n";

        echo "<td class=\"text-right text-nowrap\">";
        $restore_msg = str_replace('{VAL}', $filename, $BL['be_ftrash_restore']);
        $delete_msg  = str_replace('{VAL}', $filename, $BL['be_ftrash_delete']);

        echo '<a class="btn btn-sm btn-blue mr-1" href="include/inc_act/act_file.php?trash='.$file_row["f_id"].'|0'.
             '" data-toggle="tooltip" title="'.$BL['be_ftrash_undo'].': '.$filename.'" data-confirm-warning="'.html_specialchars($restore_msg).'">'.
             '<i class="fa fa-arrow-up fa-fw"></i></a>';

        echo '<a class="btn btn-sm btn-danger" href="include/inc_act/act_file.php?trash='.$file_row["f_id"].'|9'.
             '" data-toggle="tooltip" title="'.$BL['be_ftrash_delfinal'].': '.$filename.'" data-confirm-danger="'.html_specialchars($delete_msg).'">'.
             '<i class="far fa-trash-alt fa-fw"></i></a>';

        echo "</td>\n";
        echo "</tr>\n";

        $file_durchlauf++;
    }

    if($file_durchlauf) {
        echo "</table>\n";
        echo "</div>\n";
    }

}
