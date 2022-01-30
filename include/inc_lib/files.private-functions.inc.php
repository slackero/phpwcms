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

//Funktionen zum Listen der privaten Dateien
/**
 * @param $pid
 * @param $vor
 * @param $zieldatei
 * @param $userID
 * @param $cutID
 * @param $phpwcms
 *
 * @return mixed
 */
function list_private($pid, $vor, $zieldatei, $userID, $cutID, $phpwcms) {
    $cutID = intval($cutID);
    $pid = intval($pid);
    $sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_file f ";
    $sql .= "LEFT JOIN ".DB_PREPEND."phpwcms_user u ON u.usr_id=f.f_uid ";
    $sql .= "WHERE f.f_pid=".intval($pid)." AND ";
    if(empty($_SESSION["wcs_user_admin"])) {
        $sql .= "f.f_uid=".intval($userID)." AND ";
    }
    $sql .= "f.f_kid=0 AND f.f_trash=0 ORDER BY f_sort, f_name";
    $result = _dbQuery($sql);

    if(!isset($result[0]['f_id'])) {
        return $vor;
    }

    foreach($result as $row) {

        $dirname = html($row["f_name"]);

        if($_SESSION["wcs_user_id"] != $row["f_uid"]) {
            $dirname .= ' (' . html($row["usr_login"]) . ')';
        }

        //Ermitteln des Aufklappwertes
        $klapp_status = empty($_SESSION["klapp"][$row["f_id"]]) ? 1 : 0;

        //Ermitteln, ob überhaupt abhängige Dateien/Ordner existieren
        $count_sql  = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE ";
        $count_sql .= "f_pid=".$row["f_id"]." AND ";
        if(empty($_SESSION["wcs_user_admin"])) {
            $count_sql .= "f_uid=".intval($userID)." AND ";
        }
        $count_sql .= "f_trash=0";

        if(($count_wert = _dbQuery($count_sql, 'COUNT'))) {
            $count  = '<img src="img/leer.gif" width="2" height="1"><a href="'.$zieldatei."&amp;klapp=".$row["f_id"];
            $count .= '%7C'.$klapp_status.'">'.on_off($klapp_status, $dirname, 0)."</a>";
        } else {
            $count  = '<img src="img/leer.gif" width="13" height="1">';
        }

        //Aufbau der Zeile
        echo '<tr bgcolor="#EBF2F4"><td colspan="2"><img src="img/leer.gif" height="1" width="1" alt="" /></td></tr>'."\n"; //Abstand vor
        echo "<tr bgcolor=\"#EBF2F4\">\n"; //Einleitung Tabellenzeile
        echo "<td width=\"438\" class=\"msglist\">"; //Einleiten der Tabellenzelle
        echo $count."<img src=\"img/leer.gif\" height=\"1\" width=\"".($vor+6)."\" border=\"0\">";

        // Gallery status
        switch($row["f_gallerystatus"]) {

            case 2:
                // gallery root dir
                echo '<img src="img/icons/folder_galleryroot.gif" border="0" alt="'.$GLOBALS['BL']['be_gallery_root'].'" title="'.$GLOBALS['BL']['be_gallery_root'].'" />';
                break;

            case 3:
                // gallery subdir
                echo '<img src="img/icons/folder_gallerysub.gif" border="0" alt="'.$GLOBALS['BL']['be_gallery_directory'].'" title="'.$GLOBALS['BL']['be_gallery_directory'].'" />';
                break;

            default:
                echo "<img src=\"img/icons/folder_zu.gif\" border=\"0\" alt=\"\" />";
        }

        echo "<img src=\"img/leer.gif\" height=\"1\" width=\"5\"><strong>".$dirname; //Zellinhalt 1. Spalte Fortsetzung
        echo "</strong></td>\n"; //Schließen Zelle 1. Spalte
        //Zelle 2. Spalte - vorgesehen für Buttons/Tasten Edit etc.
        echo "<td width=\"100\" align=\"right\" class=\"msglist\">";
        //Button zum Uploaden einer Datei in dieses Verzeichnisses
        echo "<a href=\"".$zieldatei."&amp;upload=".$row["f_id"]."\" title=\"".$GLOBALS['BL']['be_fprivfunc_upload'].": ".$dirname."\">";
        echo "<img src=\"img/button/upload_13x13.gif\" border=\"0\" alt=\"\" /></a>";
        if(!$cutID) { //Button zum Erzeugen eines Neuen Unterverzeichnisses
            echo "<a href=\"".$zieldatei."&amp;mkdir=".$row["f_id"]."\" title=\"".$GLOBALS['BL']['be_fprivfunc_makenew'].": ".$dirname."\">";
            echo "<img src=\"img/button/add_13x13.gif\" border=\"0\" alt=\"\" /></a>";
        } else {  //Button zum Einfügen der Clipboard-Datei in das Verzeichnis
            echo "<a href=\"include/inc_act/act_file.php?paste=".$cutID.'%7C'.$row["f_id"].
                 "\" title=\"".$GLOBALS['BL']['be_fprivfunc_paste'].": ".$dirname."\">";
            echo "<img src=\"img/button/paste_13x13.gif\" border=\"0\" alt=\"\" /></a>";
        }
        //Button zum Bearbeiten des Verzeichnisses
        echo "<a href=\"".$zieldatei."&amp;editdir=".$row["f_id"]."\" title=\"".$GLOBALS['BL']['be_fprivfunc_edit'].": ".$dirname."\">";
        echo "<img src=\"img/button/edit_22x13.gif\" border=\"0\" alt=\"\" /></a>";
        //Button zum Umschalten zwischen Aktiv/Inaktiv
        echo "<a href=\"include/inc_act/act_file.php?aktiv=".$row["f_id"].'%7C'.true_false($row["f_aktiv"]).
             "\" title=\"".$GLOBALS['BL']['be_fprivfunc_cactive'].": ".$dirname."\">";
        echo "<img src=\"img/button/aktiv_12x13_".$row["f_aktiv"].".gif\" border=\"0\" alt=\"\" /></a>";
        //Button zum Umschalten zwischen Public/Non-Public
        echo "<a href=\"include/inc_act/act_file.php?public=".$row["f_id"].'%7C'.true_false($row["f_public"]).
             "\" title=\"".$GLOBALS['BL']['be_fprivfunc_cpublic'].": ".$dirname."\">";
        echo "<img src=\"img/button/public_12x13_".$row["f_public"].".gif\" border=\"0\" alt=\"\" /></a>";
        echo "<img src=\"img/leer.gif\" width=\"5\" height=\"1\">"; //Spacer
        //Button zum Löschen des Verzeichnisses, wenn leer
        if(!$count_wert) {
            echo "<a href=\"include/inc_act/act_file.php?delete=".$row["f_id"].'%7C'."9".
                 "\" title=\"".$GLOBALS['BL']['be_fprivfunc_deldir'].": ".$dirname."\" onclick=\"return confirm('".
                 $GLOBALS['BL']['be_fprivfunc_jsdeldir'] ." \\n[".$dirname."]? ');\">";
            echo "<img src=\"img/button/trash_13x13_1.gif\" border=\"0\" alt=\"\" /></a>";
        } else {
            echo "<img src=\"img/button/trash_13x13_0.gif\" title=\"";
            echo str_replace('{VAL}', $dirname, $GLOBALS['BL']['be_fprivfunc_notempty']).'" border="0" alt="" />';
        }
        echo "<img src=\"img/leer.gif\" width=\"2\" height=\"1\" border=\"0\" alt=\"\" />"; //Spacer
        echo "</td>\n";
        echo "</tr>\n"; //Abschluss Tabellenzeile

        //Aufbau trennende Tabellen-Zeile
        echo "<tr bgcolor=\"#EBF2F4\"><td colspan=\"2\"><img src=\"img/leer.gif\" border=\"0\" alt=\"\" /></td></tr>\n"; //Abstand nach
        echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" border=\"0\" alt=\"\" /></td></tr>\n"; //Trennlinie<img src='img/lines/line-lightgrey-dotted-538.gif'>

        //Weiter, wenn Unterstruktur
        if(!$klapp_status && $count_wert) { //$vor."<img src='img/leer.gif' height=1 width=18 border=0>"
            list_private($row["f_id"], $vor+18, $zieldatei, $userID, $cutID, $phpwcms);

            //Listing eventuell im Verzeichnis enthaltener Dateien
            $file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=".$row["f_id"];
            if(empty($_SESSION["wcs_user_admin"])) {
                $file_sql .= " AND f_uid=".$userID;
            }
            $file_sql .= " AND f_kid=1 AND f_trash=0 ORDER BY f_sort, f_name";

            $file_result = _dbQuery($file_sql);

            if(isset($file_result[0]['f_id'])) {

                $file_durchlauf = 0;

                foreach($file_result as $file_row) {
                    $filename = html($file_row["f_name"]);

                    $file_row["edit"] = '<a href="'.$zieldatei."&amp;editfile=".$file_row["f_id"].'" title="'.$GLOBALS['BL']['be_fprivfunc_editfile'].": ".$filename.'">';

                    if(!$file_durchlauf) { //Aufbau der Zeile zum Einfließen der Filelisten-Tavbelle
                        echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table width=\"538\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
                        echo "<!-- start file list: private-functions //-->\n";
                    } else {
                        echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"5\"><img src=\"img/leer.gif\" border=\"0\" alt=\"\" /></td></tr>\n";
                    }

                    echo "<tr>\n";
                    echo "<td width=\"".($vor+37)."\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"".($vor+37)."\" border=\"0\" alt=\"\" /></td>\n";
                    echo "<td width=\"13\" class=\"msglist\">";
                    echo "<img src=\"img/icons/small_".extimg($file_row["f_ext"])."\" border=\"0\" ";
                    echo 'onmouseover="Tip(\'ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"];
                    echo '&lt;br&gt;Name: '.html($file_row["f_name"]);
                    if($file_row["f_copyright"]) {
                        echo '&lt;br&gt;&copy;: '.html($file_row["f_copyright"]);
                    }
                    echo '\');" onmouseout="UnTip()" alt=""';
                    echo " /></td>\n";
                    echo "<td width=\"".(388-$vor)."\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"5\" border=\"0\" alt=\"\" />";
                    echo $file_row["edit"] . $filename . "</a></td>\n";
                    //Aufbauen Buttonleiste für jeweilige Datei
                    echo "<td width=\"100\" align=\"right\" class=\"msglist\">";
                    //Button zum Downloaden der Datei
                    echo "<a href=\"include/inc_act/act_download.php?dl=".$file_row["f_id"].
                         "\"  target=\"_blank\" title=\"".$GLOBALS['BL']['be_fprivfunc_dlfile'].": ".$filename."\">".
                         "<img src=\"img/button/download_disc.gif\" border=\"0\" alt=\"\" /></a>"; //target='_blank'
                    //Button zum Erzeugen eines Neuen Unterverzeichnisses
                    if($cutID == $file_row["f_id"]) {
                        echo "<img src=\"img/button/cut_13x13_1.gif\" border=\"0\" title=\"".$GLOBALS['BL']['be_fprivfunc_clipfile'].": ".$filename."\" alt=\"\" />";
                    } else {
                        echo "<a href=\"".$zieldatei."&amp;cut=".$file_row["f_id"]."\" title=\"".$GLOBALS['BL']['be_fprivfunc_cutfile'].": ".$filename."\">";
                        echo "<img src=\"img/button/cut_13x13_0.gif\" border=\"0\" alt=\"\" /></a>";
                    }
                    //Button zum Bearbeiten der Dateiinformationn
                    echo $file_row["edit"];
                    echo "<img src=\"img/button/edit_22x13.gif\" border=\"0\" alt=\"\" /></a>";
                    //Button zum Umschalten zwischen Aktiv/Inaktiv
                    echo "<a href=\"include/inc_act/act_file.php?aktiv=".$file_row["f_id"].'%7C'.true_false($file_row["f_aktiv"]).
                         "\" title=\"".$GLOBALS['BL']['be_fprivfunc_cactivefile'].": ".$filename."\">";
                    echo "<img src=\"img/button/aktiv_12x13_".$file_row["f_aktiv"].".gif\" border=\"0\" alt=\"\" /></a>";
                    //Button zum Umschalten zwischen Public/Non-Public
                    echo "<a href=\"include/inc_act/act_file.php?public=".$file_row["f_id"].'%7C'.true_false($file_row["f_public"]).
                         "\" title=\"".$GLOBALS['BL']['be_fprivfunc_cpublicfile'].": ".$filename."\">";
                    echo "<img src=\"img/button/public_12x13_".$file_row["f_public"].".gif\" border=\"0\" alt=\"\" /></a>";
                    echo "<img src=\"img/leer.gif\" width=\"5\" height=\"1\">"; //Spacer
                    //Button zum Löschen der Datei
                    if ($file_row["f_uid"] == intval($_SESSION["wcs_user_id"])) {
                        //if user is owner then delete button is active
                        echo "<a href=\"include/inc_act/act_file.php?trash=".$file_row["f_id"].'%7C'."1".
                         "\" title=\"".$GLOBALS['BL']['be_fprivfunc_movetrash'].": ".$filename."\" onclick=\"return confirm('".
                         $GLOBALS['BL']['be_fprivfunc_jsmovetrash1']."\\n[".$filename."]\\n".$GLOBALS['BL']['be_fprivfunc_jsmovetrash2'].
                         "');\">".
                         "<img src=\"img/button/trash_13x13_1.gif\" border=\"0\" alt=\"\" /></a>";
                    } else {
                        echo "<img src=\"img/button/trash_13x13_0.gif\" border=\"0\">";
                    }
                    echo "<img src=\"img/leer.gif\" width=\"2\" height=\"1\" border=\"0\" alt=\"\" />"; //Spacer
                    echo "</td>\n";
                    //Ende Aufbau
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
                                echo "<td width=\"".($vor+37)."\"><img src=\"img/leer.gif\" height=\"1\" width=\"".($vor+37)."\" border=\"0\" alt=\"\" /></td>\n";
                                echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\" alt=\"\" /></td>\n<td width=\"";
                                echo (388-$vor)."\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\" border=\"0\" alt=\"\" />";
                                echo $file_row["edit"];
                                echo '<img src="' . $thumb_image['src'] .'" alt="" '.$thumb_image[3].' ';
                                echo 'onmouseover="Tip(\'ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"];
                                echo '&lt;br&gt;Name: '.html($file_row["f_name"]);
                                if($file_row["f_copyright"]) {
                                    echo '&lt;br&gt;&copy;: '.html($file_row["f_copyright"]);
                                }
                                echo '\');" onmouseout="UnTip()" alt=""';
                                echo " /></a></td>\n";
                                echo "<td width=\"100\"><img src=\"img/leer.gif\" border=\"0\" alt=\"\" /></td>\n</tr>\n";
                                echo "<tr><td colspan=\"4\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" border=\"0\" alt=\"\" /></td>\n</tr>\n";

                            }

                        } else {

                            echo "<tr>\n";
                            echo "<td width=\"".($vor+37)."\"><img src=\"img/leer.gif\" height=\"1\" width=\"".($vor+37)."\" border=\"0\" alt=\"\" /></td>\n";
                            echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\" alt=\"\" /></td>\n<td width=\"";
                            echo (388-$vor)."\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\" border=\"0\" alt=\"\" />";
                            echo $file_row["edit"];
                            echo '<img src="' . PHPWCMS_RESIZE_IMAGE . '/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$file_row["f_hash"].'.'.$file_row["f_ext"].'" style="max-width:'.$phpwcms["img_list_width"].'px;height:auto;" ';
                            echo 'onmouseover="Tip(\'ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"];
                            echo '&lt;br&gt;Name: '.html($file_row["f_name"]);
                            if($file_row["f_copyright"]) {
                                echo '&lt;br&gt;&copy;: '.html($file_row["f_copyright"]);
                            }
                            echo '\');" onmouseout="UnTip()" alt=""';
                            echo " /></a></td>\n";
                            echo "<td width=\"100\"><img src=\"img/leer.gif\" border=\"0\" alt=\"\" /></td>\n</tr>\n";
                            echo "<tr><td colspan=\"4\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" border=\"0\" alt=\"\" /></td>\n</tr>\n";

                        }

                    }

                    $file_durchlauf++;
                }
                if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
                    echo "</table>\n<!-- end file list: private-functions //-->\n";
                    echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" border=\"0\" alt=\"\" /></td></tr>\n";
                }
            } //Ende Liste Dateien
        }

        //Zaehler mitführen
        $_SESSION["list_zaehler"]++;
    }

    return $vor;
}

function true_false($wert) {
    //Wechselt den Wahr/Falsch wert zum Gegenteil: 1=>0 und 0=>1
    return (intval($wert)) ? 0 : 1;
}

function on_off($wert, $string, $art = 1) {
    //Erzeugt das Status-Zeichen für Klapp-Auf/Zu
    //Wenn Art = 1 dann als Zeichen, ansonsten als Bild
    if($wert) {
        return ($art == 1) ? "+" : "<img src=\"img/symbols/klapp_zu.gif\" title=\"".$GLOBALS['BL']['be_fprivfunc_opendir'].": ".$string."\" border=\"0\" alt=\"\" />";
    } else {
        return ($art == 1) ? "-" : "<img src=\"img/symbols/klapp_auf.gif\" title=\"".$GLOBALS['BL']['be_fprivfunc_closedir'].": ".$string."\" border=\"0\" alt=\"\" />";
    }
}
