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

// List available files
$file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=0 ";
if(empty($_SESSION["wcs_user_admin"])) {
    $file_sql .= "AND f_uid=".$_SESSION["wcs_user_id"].' ';
}
$file_sql .= "AND f_kid=1 AND f_trash=0 ORDER BY f_sort, f_name";

$file_result = _dbQuery($file_sql);

if(isset($file_result[0]['f_id'])) {

    $file_durchlauf = 0;

    $zieldatei = "phpwcms.php?do=files&amp;f=0";

    foreach($file_result as $file_row) {
        $filename = html($file_row["f_name"]);

        $file_row['edit'] = '<a href="'.$zieldatei.'&amp;editfile='.$file_row["f_id"].'" title="'.$BL['be_fprivfunc_editfile'].": ".$filename.'">';

        if(!$file_durchlauf) {
            echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table width=\"538\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
        } else {
            echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"5\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
        }
        echo "<tr>\n";
        echo "<td width=\"19\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"19\" border=\"0\"></td>\n";
        echo "<td width=\"13\" class=\"msglist\">";
        echo "<img src=\"img/icons/small_".extimg($file_row["f_ext"])."\" border=\"0\" ";
        echo 'onmouseover="Tip(\'ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"];
        echo '&lt;br&gt;Name: '.html($file_row["f_name"]);
        if($file_row["f_copyright"]) {
            echo '&lt;br&gt;&copy;: '.html($file_row["f_copyright"]);
        }
        echo '\');" onmouseout="UnTip()" alt=""';
        echo " /></td>\n";
        echo "<td width=\"406\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"5\" />";
        echo $file_row['edit'] . $filename."</a></td>\n";
        //Aufbauen Buttonleiste für jeweilige Datei
        echo "<td width=\"100\" align=\"right\" class=\"msglist\">";
        //Button zum Downloaden der Datei
        echo "<a href=\"include/inc_act/act_download.php?dl=".$file_row["f_id"].
             "\" target=\"_blank\" title=\"".$BL['be_fprivfunc_dlfile'].": ".$filename."\">".
             "<img src=\"img/button/download_disc.gif\" border=\"0\"></a>";
        //Button zum Erzeugen eines Neuen Unterverzeichnisses
        if($cutID == $file_row["f_id"]) {
            echo "<img src=\"img/button/cut_13x13_1.gif\" border=\"0\" title=\"".$BL['be_fprivfunc_clipfile'].": ".$filename."\">";
        } else {
            echo "<a href=\"".$zieldatei."&cut=".$file_row["f_id"]."\" title=\"".$BL['be_fprivfunc_cutfile'].": ".$filename."\">";
            echo "<img src=\"img/button/cut_13x13_0.gif\" border=\"0\"></a>";
        }
        //Button zum Bearbeiten der Dateiinformationn
        echo $file_row['edit'] . "<img src=\"img/button/edit_22x13.gif\" border=\"0\"></a>";
        //Button zum Umschalten zwischen Aktiv/Inaktiv
        echo "<a href=\"include/inc_act/act_file.php?aktiv=".$file_row["f_id"].'%7C'.true_false($file_row["f_aktiv"]).
             "\" title=\"".$BL['be_fprivfunc_cactivefile'].": ".$filename."\">";
        echo "<img src=\"img/button/aktiv_12x13_".$file_row["f_aktiv"].".gif\" border=\"0\"></a>";
        //Button zum Umschalten zwischen Public/Non-Public
        echo "<a href=\"include/inc_act/act_file.php?public=".$file_row["f_id"].'%7C'.true_false($file_row["f_public"]).
             "\" title=\"".$BL['be_fprivfunc_cpublicfile'].": ".$filename."\">";
        echo "<img src=\"img/button/public_12x13_".$file_row["f_public"].".gif\" border=\"0\"></a>";
        echo "<img src=\"img/leer.gif\" width=\"5\" height=\"1\">"; //Spacer
        // button delete file
        if($file_row["f_uid"] === intval($_SESSION["wcs_user_id"]) || !empty($_SESSION["wcs_user_admin"])) {
            //if user is owner then delete button is active
            echo "<a href=\"include/inc_act/act_file.php?trash=".$file_row["f_id"].'%7C'."1".
             "\" title=\"".$BL['be_fprivfunc_movetrash'].": ".$filename."\" onclick=\"return confirm('".$BL['be_fprivfunc_jsmovetrash1'].
             "\\n[".$filename."]  \\n".$BL['be_fprivfunc_jsmovetrash2']."');\">".
             "<img src=\"img/button/trash_13x13_1.gif\" border=\"0\"></a>";
        } else {
            echo "<img src=\"img/button/trash_13x13_0.gif\" border=\"0\">";
        }
        echo "<img src=\"img/leer.gif\" width=\"2\" height=\"1\">"; //Spacer
        echo "</td>\n";
        // end
        echo "</tr>\n";

        if(!empty($_SESSION["wcs_user_thumb"])) {

            // now try to get existing thumbnails or if not exists
            // build new based on default thumbnail listing sizes

            if(empty($file_row["f_svg"])) {

                // build thumbnail image name
                $thumb_image = get_cached_image(array(
                    "target_ext"    =>  $file_row["f_ext"],
                    "image_name"    =>  $file_row["f_hash"] . '.' . $file_row["f_ext"],
                    "thumb_name"    =>  md5($file_row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                ));

                if($thumb_image != false) {

                    echo "<tr>\n";
                    echo "<td width=\"19\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n";
                    echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n<td width=\"";
                    echo "406\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\">";
                    echo $file_row['edit'];
                    echo '<img src="' . $thumb_image['src'] .'" border="0" '.$thumb_image[3].'></a></td>'."\n";
                    echo "<td width=\"100\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n</tr>\n";
                    echo "<tr><td colspan=\"4\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" border=\"0\"></td>\n</tr>\n";

                }

            } else {

                echo "<tr>\n";
                echo "<td width=\"19\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n";
                echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n<td width=\"";
                echo "406\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\">";
                echo $file_row['edit'];
                echo '<img src="'.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$file_row["f_hash"].'.'.$file_row["f_ext"].'" style="max-width:'.$phpwcms["img_list_width"].'px;height:auto;"></a></td>';
                echo "<td width=\"100\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n</tr>\n";
                echo "<tr><td colspan=\"4\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" border=\"0\"></td>\n</tr>\n";

            }

        }
        $file_durchlauf++;
    }
    if($file_durchlauf) { // close file list tables
        echo "</table>\n";
        echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n"; //Abstand vor
    }
} // end listing files
