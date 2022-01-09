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
function list_public($pid, $vor, $zieldatei, $userID, $phpwcms) {

    $pid = intval($pid);
    $userID = intval($userID);

    //Folder Listing für Public files
    $sql = "SELECT f_id, f_name FROM ".DB_PREPEND."phpwcms_file WHERE ".
           "f_pid=".$pid." AND ".
           "f_public=1 AND f_aktiv=1 AND ".
           "f_uid=".$userID." AND ".
           "f_kid=0 AND f_trash=0 ORDER BY f_sort, f_name";

    $result = _dbQuery($sql);
    if(!isset($result[0]['f_id'])) {
        return $vor;
    }

    foreach($result as $row) {

        $dirname = html($row["f_name"]);

        //Ermitteln des Aufklappwertes
        $klapp_status = empty($_SESSION["pklapp"][$row["f_id"]]) ? 1 : 0;

        //Ermitteln, ob überhaupt abhängige Dateien/Ordner existieren
        $count_sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE ".
                     "f_pid=".$row["f_id"]." AND f_uid=".$userID." AND ".
                     "f_public=1 AND f_aktiv=1 AND f_trash=0";

        if(($count_wert = _dbQuery($count_sql, 'COUNT'))) {
            $count  = "<img src=\"img/leer.gif\" width=\"2\" height=\"1\"><a href=\"".$zieldatei."&amp;pklapp=".$row["f_id"];
            $count .= "|".$klapp_status."\">".on_off($klapp_status, $dirname, 0)."</a>";
        } else {
            $count  = "<img src=\"img/leer.gif\" width=\"13\" height=\"1\">";
        }

        //Aufbau der Zeile
        echo "<tr bgcolor=\"#EBF2F4\"><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n"; //Abstand vor
        echo "<tr bgcolor=\"#EBF2F4\">\n"; //Einleitung Tabellenzeile
        echo "<td width=\"488\" class='msglist'>"; //Einleiten der Tabellenzelle
        echo $count."<img src=\"img/leer.gif\" height=\"1\" width=\"".($vor+6)."\" border=\"0\"><img src=\"img/icons/folder_zu.gif\" border=\"0\">"; //Zellinhalt 1. Spalte
        echo "<img src=\"img/leer.gif\" height=\"1\" width=\"5\"><strong>".$dirname; //Zellinhalt 1. Spalte Fortsetzung
        echo "</strong></td>\n"; //Schließen Zelle 1. Spalte
        //Zelle 2. Spalte - vorgesehen für Buttons/Tasten Edit etc.
        echo "<td width=\"50\" align=\"right\" class=\"msglist\">";
        echo "<img src=\"img/leer.gif\" width=\"50\" height=\"1\">"; //Spacer
        echo "</td>\n";
        echo "</tr>\n"; //Abschluss Tabellenzeile

        //Aufbau trennende Tabellen-Zeile
        echo "<tr bgcolor=\"#EBF2F4\"><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n"; //Abstand nach
        echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n"; //Trennlinie<img src='img/lines/line-lightgrey-dotted-538.gif'>

        //Weiter, wenn Unterstruktur
        if(!$klapp_status && $count_wert) {
            list_public($row["f_id"], $vor+18, $zieldatei, $userID, $phpwcms);

            //Listing eventuell im Verzeichnis enthaltener Dateien
            $file_sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=".$row["f_id"]." AND f_uid=".$userID;
            $file_sql .= " AND f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 ORDER BY f_sort, f_name";

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
                    echo "<td width=\"".($vor+37)."\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"".($vor+37)."\" border=\"0\"></td>\n";
                    echo "<td width=\"13\" class=\"msglist\">";
                    echo "<img src=\"img/icons/small_".extimg($file_row["f_ext"])."\" border=\"0\"";

                    echo ' onmouseover="Tip(\'ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'\');" onmouseout="UnTip()" alt=""';

                    echo "></td>\n";
                    echo "<td width=\"".(473-$vor)."\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"5\">"; //438-$vor
                    echo "<a href=\"fileinfo.php?public&amp;fid=".$file_row["f_id"];
                    echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
                    echo $filename."</a>";
                    echo "</td>\n";
                    echo "<td width=\"15\" align=\"right\" class=\"msglist\">";
                    echo "<a href=\"include/inc_act/act_download.php?pl=1&dl=".$file_row["f_id"];
                    echo "\" target=\"_blank\" title=\"".$GLOBALS['BL']['be_fprivfunc_dlfile'].": ".$filename."\">";
                    echo "<img src=\"img/button/download_disc.gif\" border=0></a>"; //target='_blank'
                    echo "<img src=\"img/leer.gif\" width=\"2\" height=\"1\">"; //Spacer
                    echo "</td>\n";
                    //Ende Aufbau
                    echo "</tr>\n";

                    if(!empty($_SESSION["wcs_user_thumb"])) {

                        if(empty($file_row["f_svg"])) {
                            $thumb_image = get_cached_image(array(
                                "target_ext"    =>  $file_row["f_ext"],
                                "image_name"    =>  $file_row["f_hash"] . '.' . $file_row["f_ext"],
                                "thumb_name"    =>  md5($file_row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                            ));

                            if($thumb_image != false) {

                                echo '<tr>';
                                echo '<td width="'.($vor+37).'"><img src="img/leer.gif" height="1" width="'.($vor+37).'" border="0"></td>';
                                echo '<td width="13"><img src="img/leer.gif" height="1" width="1" border="0"></td><td width="';
                                echo (473-$vor).'"><img src="img/leer.gif" height="1" width="6"><a href="fileinfo.php?public&amp;fid=';
                                echo $file_row["f_id"].'" target="_blank" onclick="flevPopupLink(this.href,\'filedetail\',\'scrollbars=';
                                echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                                echo '<img src="' . $thumb_image['src'] .'" border="0" '.$thumb_image[3];
                                echo ' onmouseover="Tip(\'ID: '.$file_row["f_id"].'\');" onmouseout="UnTip()" alt=""';
                                echo '></a></td>';
                                echo '<td width="15\"><img src="img/leer.gif" height="1" width="1" border="0"></td></tr>';
                                echo '<tr><td colspan="4"><img src="img/leer.gif" height="2" width="1" border="0"></td></tr>';

                            }

                        } else {

                            echo '<tr>';
                            echo '<td width="'.($vor+37).'"><img src="img/leer.gif" height="1" width="'.($vor+37).'" border="0"></td>';
                            echo '<td width="13"><img src="img/leer.gif" height="1" width="1" border="0"></td><td width="';
                            echo (473-$vor).'"><img src="img/leer.gif" height="1" width="6"><a href="fileinfo.php?public&amp;fid=';
                            echo $file_row["f_id"].'" target="_blank" onclick="flevPopupLink(this.href,\'filedetail\',\'scrollbars=';
                            echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                            echo '<img src="'.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$file_row['f_hash'].'.'.$file_row["f_ext"].'" style="max-width:'.$phpwcms["img_list_width"].'px;height:auto;"';
                            echo ' onmouseover="Tip(\'ID: '.$file_row["f_id"].'\');" onmouseout="UnTip()" alt=""';
                            echo '></a></td>';
                            echo '<td width="15\"><img src="img/leer.gif" height="1" width="1" border="0"></td></tr>';
                            echo '<tr><td colspan="4"><img src="img/leer.gif" height="2" width="1" border="0"></td></tr>';

                        }

                    }

                    $file_durchlauf++;
                }
                if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
                    echo "</table>\n";
                    echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n";
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
        return ($art == 1) ? "+" : "<img src=\"img/symbols/klapp_zu.gif\" border=\"0\" title=\"".$GLOBALS['BL']['be_fprivfunc_opendir']." ".$string."\">";
    } else {
        return ($art == 1) ? "-" : "<img src=\"img/symbols/klapp_auf.gif\" border=\"0\" title=\"".$GLOBALS['BL']['be_fprivfunc_closedir']." ".$string."\">";
    }
}

function list_public_root($wert) {
    //Checken ob public root files für user gezeigt werden sollen
    return ($wert) ? 1 : 0;
}
