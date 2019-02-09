<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2019, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// list trashed files
$file_sql = "SELECT * FROM ".DB_PREPEND."cmsgo_file WHERE f_uid=".$_SESSION["wcs_user_id"]." AND f_kid=1 AND f_trash=1 ORDER BY f_name";
$file_result = _dbQuery($file_sql);

if(isset($file_result[0]['f_id'])) {

    $file_durchlauf = 0;

    foreach($file_result as $file_row) {

        $filename = html($file_row["f_name"]);

        echo "<tr>\n";
        echo "<td width=\"13\">";
             echo '<i class="fa fa-lg fa-'.ext_icon($file_row["f_ext"]).'" data-toggle="tooltip" data-html="true" title="ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'&lt;br&gt;Name: '.html($file_row["f_name"]).'"></i>';
        echo "</td>\n<td>";
        echo '<a href="fileinfo.php?fid='.$file_row["f_id"];
        echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
        echo $filename."</a></td>\n";

        echo "<td class=\"text-right text-nowrap\">";
        echo '<a class="btn btn-sm btn-blue mr-1" href="include/inc_act/act_file.php?trash='.$file_row["f_id"].'|0'.
             '" data-toggle="tooltip" title="'.$BL['be_ftrash_undo'].': '.$filename."\" onclick=\"return confirm('".
             str_replace('{VAL}', $filename, $BL['be_ftrash_restore'])."');\">".
             '<i class="fa fa-arrow-alt-from-bottom fa-fw"></i></a>';

        echo '<a class="btn btn-sm btn-danger" href="include/inc_act/act_file.php?trash='.$file_row["f_id"].'|9'.
             '" data-toggle="tooltip" title="'.$BL['be_ftrash_delfinal'].': '.$filename."\" onclick=\"return confirm('".
             str_replace('{VAL}', $filename, $BL['be_ftrash_delete'])."');\">".
             '<i class="fa fa-trash fa-fw"></i></a>';
        echo "</td>\n";
        echo "</tr>\n";

        $file_durchlauf++;
    }

    if($file_durchlauf) {
        echo "</table>\n";
        echo "</div>\n";
    }

}
