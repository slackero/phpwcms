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

//Listing eventuell im Verzeichnis enthaltener Dateien
$file_sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=0 AND f_uid=".intval($root_user_id);
$file_sql .= " AND f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 ORDER BY f_name";

$file_result = _dbQuery($file_sql);

if(isset($file_result[0]['f_id'])) {
    $file_durchlauf = 0;

    foreach($file_result as $file_row) {

        $filename = html($file_row["f_name"]);
        if(!$file_durchlauf) {
            echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table width=\"538\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
        } else {
            echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"5\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
        }
        echo "<tr>\n";
        echo "<td width=\"55\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"55\" border=\"0\"></td>\n";
        echo "<td width=\"13\" class=\"msglist\">";
        echo "<img src=\"img/icons/small_".extimg($file_row["f_ext"])."\" border=\"0\"></td>\n";
        echo "<td width=\"455\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"5\">";
        echo "<a href=\"fileinfo.php?fid=".$file_row["f_id"];
        echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
        echo $filename."</a>";

        echo "</td>\n";

        echo "<td width=\"15\" align=\"right\" class=\"msglist\">";
        echo "<a href=\"include/inc_act/act_download.php?dl=".$file_row["f_id"];
        echo "\" target=\"_blank\" title=\"".$BL['be_fprivfunc_dlfile'].": ".$filename."\" target=\"_blank\">";
        echo "<img src=\"img/button/download_disc.gif\" border=\"0\"></a>";
        echo "<img src=\"img/leer.gif\" width=\"2\" height=\"1\">"; //Spacer
        echo "</td>\n";
        //Ende Aufbau
        echo "</tr>\n";

        if(!empty($_SESSION["wcs_user_thumb"])) {

            $thumb_image = get_cached_image(array(
                "target_ext"    =>  $file_row["f_ext"],
                "image_name"    =>  $file_row["f_hash"] . '.' . $file_row["f_ext"],
                "thumb_name"    =>  md5($file_row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
            ));

            if($thumb_image != false) {
                echo "<tr>\n";
                echo "<td width=\"55\"><img src=\"img/leer.gif\" height=\"1\" width=\"55\" border=\"0\"></td>\n";
                echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n<td width=\"";
                echo "505\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\"><a href=\"fileinfo.php?fid=";
                echo $file_row["f_id"]."\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=";
                echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                echo '<img src="' . $thumb_image['src'] .'" alt="" '.$thumb_image[3]."></a></td>\n";
                echo "<td width=\"15\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" alt=\"\"></td>\n</tr>\n";
                echo "<tr><td colspan=\"4\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" border=\"0\"></td>\n</tr>\n";
            }

        }

        $file_durchlauf++;
    }
    if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
        echo "</table>\n";
        echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
    }
} //Ende Liste Dateien
