<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//Listing eventuell im Verzeichnis enthaltener Dateien
$file_sql  = "SELECT * FROM ".DB_PREPEND."cmsgo_file WHERE f_pid=0 AND f_uid=".intval($root_user_id);
$file_sql .= " AND f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 ORDER BY f_name";

$file_result = _dbQuery($file_sql);

if(isset($file_result[0]['f_id'])) {
    $file_durchlauf = 0;

    foreach($file_result as $file_row) {

        $filename = html($file_row["f_name"]);
        if(!$file_durchlauf) {
            echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table width=\"100%\" class=\"\">\n";
        } else {
            echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"5\"></td></tr>\n";
        }
        echo "<tr>\n";
        echo "<td width=\"55\">aaaaa</td>\n";
        echo "<td width=\"13\">";
        echo '<i class="btn btn-sm btn-blue fa fa-'.ext_icon($file_row["f_ext"])."</td>\n";
        echo "<td width=\"455\">";
        echo "<a href=\"fileinfo.php?fid=".$file_row["f_id"];
        echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
        echo $filename."</a>";

        echo "</td>\n";

        echo "<td width=\"15\" align=\"right\">";
        echo "<a href=\"include/inc_act/act_download.php?dl=".$file_row["f_id"];
        echo '" target="_blank" data-toggle="tooltip" title="'.$BL['be_fprivfunc_dlfile'].': '.$filename.'\' target="_blank">';
        echo '<i class="btn btn-sm btn-blue mr-1 fa fa-download" aria-hidden="true"></i></a>';
        echo "</td>\n";
        //Ende Aufbau
        echo "</tr>\n";

        if(!empty($_SESSION["wcs_user_thumb"])) {

            $thumb_image = get_cached_image(array(
                "target_ext"    =>  $file_row["f_ext"],
                "image_name"    =>  $file_row["f_hash"] . '.' . $file_row["f_ext"],
                "thumb_name"    =>  md5($file_row["f_hash"].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
            ));

            if($thumb_image != false) {
                echo "<tr>\n";
                echo "<td width=\"55\"></td>\n";
                echo "<td width=\"13\"></td>\n<td width=\"";
                echo "505\"><a href=\"fileinfo.php?fid=";
                echo $file_row["f_id"]."\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=";
                echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                echo '<img src="' . $thumb_image['src'] .'" alt="" '.$thumb_image[3]."></a></td>\n";
                echo "<td width=\"15\"></td>\n</tr>\n";
            }

        }

        $file_durchlauf++;
    }
    if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
        echo "</table>\n";
    }
} //Ende Liste Dateien
