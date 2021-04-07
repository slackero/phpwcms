<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

//Funktionen zum Listen der privaten Dateien
function list_public($pid, $counter, $zieldatei, $userID, $cmsgo) {

    $pid = intval($pid);
    $userID = intval($userID);

    //Folder Listing für Public files
    $sql = "SELECT f_id, f_name FROM ".DB_PREPEND."cmsgo_file WHERE ".
           "f_pid=".$pid." AND ".
           "f_public=1 AND f_aktiv=1 AND ".
           "f_uid=".$userID." AND ".
           "f_kid=0 AND f_trash=0 ORDER BY f_sort, f_name";

    $result = _dbQuery($sql);
    if(!isset($result[0]['f_id'])) {
        return $counter;
    }

    foreach($result as $row) {

        $dirname = html($row["f_name"]);

        //Ermitteln des Aufklappwertes
        $klapp_status = empty($_SESSION["pklapp"][$row["f_id"]]) ? 1 : 0;

        //Ermitteln, ob überhaupt abhängige Dateien/Ordner existieren
        $count_sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."cmsgo_file WHERE ".
                     "f_pid=".$row["f_id"]." AND f_uid=".$userID." AND ".
                     "f_public=1 AND f_aktiv=1 AND f_trash=0";

        if(($count_wert = _dbQuery($count_sql, 'COUNT'))) {
            $count  = '<a href="'.$zieldatei."&amp;klapp=".$row["f_id"];
            $count .= '%7C'.$klapp_status.'">'.on_off($klapp_status, $dirname, 0, $counter)."</a>";
        } else {
            $count  = on_off($klapp_status, $dirname, 0, $counter);
        }

        //Aufbau der Zeile
        echo "<tr bgcolor=\"#EBF2F4\">\n"; //Einleitung Tabellenzeile
        echo "<td>"; //Einleiten der Tabellenzelle
        echo $count.'<i class="fa fa-folder fa-lg fa-fw" aria-hidden="true"></i>'; //Zellinhalt 1. Spalte
        echo "<strong>".$dirname; //Zellinhalt 1. Spalte Fortsetzung
        echo "</strong></td>\n"; //Schließen Zelle 1. Spalte
        //Zelle 2. Spalte - vorgesehen für Buttons/Tasten Edit etc.
        echo "<td></td>\n";
        echo "</tr>\n"; //Abschluss Tabellenzeile

        //Weiter, wenn Unterstruktur
        if(!$klapp_status && $count_wert) {
            list_public($row["f_id"], $counter+1, $zieldatei, $userID, $cmsgo);

            //Listing eventuell im Verzeichnis enthaltener Dateien
            $file_sql  = "SELECT * FROM ".DB_PREPEND."cmsgo_file WHERE f_pid=".$row["f_id"]." AND f_uid=".$userID;
            $file_sql .= " AND f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 ORDER BY f_sort, f_name";

            $file_result = _dbQuery($file_sql);

            if(isset($file_result[0]['f_id'])) {
                $file_durchlauf = 0;
                foreach($file_result as $file_row) {
                    $filename = html($file_row["f_name"]);
                    if(!$file_durchlauf) {
                        echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table width=\"100%\" class=\"table-no-border\" cellpadding=\"0\" cellspacing=\"0\">\n";
                    } else {

                    }
                    echo "<tr>\n";
                    echo "<td>";
                    echo '<i class="btn btn-sm btn-blue fa fa-'.ext_icon($file_row["f_ext"]).' slist-'.($counter+1).'" data-toggle="tooltip" data-html="true" title="ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'"></i>';
                    echo "</td>\n";
                    echo "<td>";
                    echo "<a href=\"fileinfo.php?public&amp;fid=".$file_row["f_id"];
                    echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
                    echo $filename."</a>";
                    echo "</td>\n<td>";

                    if(!empty($_SESSION["wcs_user_thumb"])) {
                        if(empty($file_row["f_svg"])) {
                            $thumb_image = get_cached_image(array(
                                "target_ext"    =>  $file_row["f_ext"],
                                "image_name"    =>  $file_row["f_hash"] . '.' . $file_row["f_ext"],
                                "thumb_name"    =>  md5($file_row["f_hash"].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
                            ));
                            if($thumb_image != false) {
                                echo '<a href="fileinfo.php?public&amp;fid=';
                                echo $file_row["f_id"].'" target="_blank" onclick="flevPopupLink(this.href,\'filedetail\',\'scrollbars=';
                                echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                                echo '<img src="' . $thumb_image['src'] .'" border="0" '.$thumb_image[3];
                                echo ' onmouseover="Tip(\'ID: '.$file_row["f_id"].'\');" onmouseout="UnTip()" alt=""';
                                echo '></a>';
                            }
                        } else {
                            echo '<a href="fileinfo.php?public&amp;fid=';
                            echo $file_row["f_id"].'" target="_blank" onclick="flevPopupLink(this.href,\'filedetail\',\'scrollbars=';
                            echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                            echo '<img src="'.CMSGO_RESIZE_IMAGE.'/'.$cmsgo["img_list_width"].'x'.$cmsgo["img_list_height"].'/'.$file_row['f_hash'].'.'.$file_row["f_ext"].'" style="max-width:'.$cmsgo["img_list_width"].'px;height:auto;"';
                            echo ' onmouseover="Tip(\'ID: '.$file_row["f_id"].'\');" onmouseout="UnTip()" alt=""';
                            echo '></a>';
                        }
                    }

                    echo "</td>\n<td width=\"15\" align=\"right\">";
                    echo "<a href=\"include/inc_act/act_download.php?pl=1&dl=".$file_row["f_id"];
                    echo "\" target=\"_blank\" title=\"".$GLOBALS['BL']['be_fprivfunc_dlfile'].": ".$filename."\">";
                    echo '<i class="btn btn-sm btn-blue mr-1 fa fa-download" aria-hidden="true"></i></a>'; //target='_blank'
                    echo "</td>\n";
                    //Ende Aufbau
                    echo "</tr>\n";

                    $file_durchlauf++;
                }
                if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
                    echo "</table>\n";
                }
            } //Ende Liste Dateien
        }
        //Zaehler mitführen
        $_SESSION["list_zaehler"]++;
    }

    return $counter;
}

function true_false($wert) {
    //Wechselt den Wahr/Falsch wert zum Gegenteil: 1=>0 und 0=>1
    return (intval($wert)) ? 0 : 1;
}

function on_off($wert, $string, $art = 1, $counter) {
    //Erzeugt das Status-Zeichen für Klapp-Auf/Zu
    //Wenn Art = 1 dann als Zeichen, ansonsten als Bild
    if($wert) {
        return ($art == 1) ? "+" : '<i class="fa fa-lg fa-caret-right fa-fw slist-'.$counter.'" aria-hidden="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_opendir'].': '.$string.'"></i>';
    } else {
        return ($art == 1) ? "-" : '<i class="fa fa-lg fa-caret-down fa-fw slist-'.$counter.'" aria-hidden="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_closedir'].': '.$string.'"></i>';
    }
}

function list_public_root($wert) {
    //Checken ob public root files für user gezeigt werden sollen
    return ($wert) ? 1 : 0;
}
