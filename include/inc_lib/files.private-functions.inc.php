<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

//Funktionen zum Listen der privaten Dateien
/**
 * @param $pid
 * @param $vor
 * @param $zieldatei
 * @param $userID
 * @param $cutID
 * @param $cmsgo
 *
 * @return mixed
 */
function list_private($pid, $counter, $zieldatei, $userID, $cutID, $cmsgo) {
    $cutID = intval($cutID);
    $pid = intval($pid);
    $sql  = "SELECT * FROM ".DB_PREPEND."cmsgo_file f ";
    $sql .= "LEFT JOIN ".DB_PREPEND."cmsgo_user u ON u.usr_id=f.f_uid ";
    $sql .= "WHERE f.f_pid=".intval($pid)." AND ";
    if(empty($_SESSION["wcs_user_admin"])) {
        $sql .= "f.f_uid=".intval($userID)." AND ";
    }
    $sql .= "f.f_kid=0 AND f.f_trash=0 ORDER BY f_sort, f_name";
    $result = _dbQuery($sql);
    $vor = '';

    if(!isset($result[0]['f_id'])) {
        return $counter;
    }

    foreach($result as $row) {

        $dirname = html($row["f_name"]);

        if($_SESSION["wcs_user_id"] != $row["f_uid"]) {
            $dirname .= ' (' . html($row["usr_login"]) . ')';
        }

        //Ermitteln des Aufklappwertes
        $klapp_status = empty($_SESSION["klapp"][$row["f_id"]]) ? 1 : 0;

        //Ermitteln, ob überhaupt abhängige Dateien/Ordner existieren
        $count_sql  = "SELECT COUNT(f_id) FROM ".DB_PREPEND."cmsgo_file WHERE ";
        $count_sql .= "f_pid=".$row["f_id"]." AND ";
        if(empty($_SESSION["wcs_user_admin"])) {
            $count_sql .= "f_uid=".intval($userID)." AND ";
        }
        $count_sql .= "f_trash=0";

        if(($count_wert = _dbQuery($count_sql, 'COUNT'))) {
            $count  = '<a class="filein" href="'.$zieldatei."&amp;klapp=".$row["f_id"];
            $count .= '%7C'.$klapp_status.'">'.on_off($klapp_status, $dirname, 0, $counter);
        } else {
            $count  = on_off($klapp_status, $dirname, 0, $counter);
        }

        //Aufbau der Zeile
        echo '<tr bgcolor="#F4F5F4">'.LF; //Einleitung Tabellenzeile
        echo '<td>'.$count; //Einleiten der Tabellenzelle


        // Gallery status
        switch($row["f_gallerystatus"]) {

            case 2:
                // gallery root dir
                echo '<i class="fa ffolder fa-folder fa-fw" aria-hidden="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_gallery_root'].'"></i>';
                break;

            case 3:
                // gallery subdir
                echo '<i class="fa ffolder fa-folder fa-fw text-warning" aria-hidden="true"data-toggle="tooltip" title="'.$GLOBALS['BL']['be_gallery_directory'].'"></i>';
                break;

            default:
                echo '<i class="fa ffolder fa-folder fa-fw" aria-hidden="true"></i>';
        }

        echo "<strong>".$dirname; //Zellinhalt 1. Spalte Fortsetzung
        echo "</strong></a></td>\n"; //Schließen Zelle 1. Spalte
        //Zelle 2. Spalte - vorgesehen für Buttons/Tasten Edit etc.
        echo '<td class="text-right" nowrap="nowrap">';

        echo '<div class="btn-group" role="group" aria-label="group'.$row["f_id"].'">';
        //Button zum Bearbeiten des Verzeichnisses
        echo '<a class="btn btn-xs btn-blue" role="button" aria-disabled="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_edit'].": ".$dirname.'" href="'.$zieldatei."&amp;editdir=".$row["f_id"].'"><i class="fa fa-pencil-alt fa-fw mt-1"></i></a>';

        echo '<div class="btn-group" role="group">';
        echo '<a class="btn btn-xs btn-blue darken dropdown-toggle" role="button" type="button" href="#" id="dropdownFcontentLink'.$row["f_id"].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$GLOBALS['BL']['be_func_struct_more_action'].'</a>';
        echo '<div class="dropdown-menu" aria-labelledby="dropdownFcontentLink'.$row["f_id"].'">';

        //Button zum Uploaden einer Datei in dieses Verzeichnisses
        echo '<a class="dropdown-item" href="'.$zieldatei.'&amp;upload='.$row["f_id"].'" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_upload'].': '.$dirname.'">';
        echo '<i class="ml-1 fa fa-fw fa-upload" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_upload'].': '.$dirname.'</a>';
        if(!$cutID) { //Button zum Erzeugen eines Neuen Unterverzeichnisses
            echo '<a class="dropdown-item" role="button" href="'.$zieldatei.'&amp;mkdir='.$row["f_id"].'" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_makenew'].': '.$dirname.'">';
            echo '<i class="ml-1 fa fa-fw fa-plus" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_makenew'].': '.$dirname.'</a>';
        } else {  //Button zum Einfügen der Clipboard-Datei in das Verzeichnis
            echo '<a class="dropdown-item" role="button" href="include/inc_act/act_file.php?paste='.$cutID.'%7C'.$row["f_id"].
                 '" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_paste'].': '.$dirname.'">';
            echo '<i class="ml-1 fa fa-fw fa-clipboard" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_paste'].': '.$dirname.'</a>';
        }
        //Button zum Löschen des Verzeichnisses, wenn leer
        if(!$count_wert) {
            echo '<a class="dropdown-item" href="include/inc_act/act_file.php?delete='.$row["f_id"].'%7C'.'9'.
                 '" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_deldir'].': '.$dirname."\" onclick=\"return confirm('".$GLOBALS['BL']['be_fprivfunc_jsdeldir'] ." \\n[".$dirname."]? ');\">";
            echo '<i class="ml-1 disabled far fa-fw fa-trash-alt" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_deldir'].': '.$dirname.'</a>';
        } else {
            echo '<div class="dropdown-item"><i class="ml-1 disabled far fa-fw fa-trash-alt text-muted" aria-hidden="true" data-toggle="tooltip" title="';
            echo str_replace('{VAL}', $dirname, $GLOBALS['BL']['be_fprivfunc_notempty']).'"></i> '.str_replace('{VAL}', $dirname, $GLOBALS['BL']['be_fprivfunc_notempty']).'</div>';
        }
        echo '</div></div></div>';

        //Button zum Umschalten zwischen Aktiv/Inaktiv
        echo '<button id="abtnfileaktiv'.$row["f_id"].'" class="btn fa fa-fw ml-1 btn-sm visible '.($row["f_aktiv"]==0 ? "btn-danger" : "btn-success").'" data-id="'.$row["f_id"].'" data-type="fileaktiv" data-table="file" data-field="f_aktiv" data-fieldid="f_id" aria-disabled="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cactive'].': '.$dirname.'"></button>';
        //Button zum Umschalten zwischen Public/Non-Public
        echo '<button id="abtnfilepublic'.$row["f_id"].'" class="btn fa fa-fw ml-1 btn-sm public '.($row["f_public"]==0 ? "btn-danger" : "btn-success").'" data-id="'.$row["f_id"].'" data-type="filepublic" data-table="file" data-field="f_public" data-fieldid="f_id" aria-disabled="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cpublic'].': '.$dirname.'"></button>';
        echo '</td>'.LF;
        echo '</tr>'.LF; //Abschluss Tabellenzeile

        //Weiter, wenn Unterstruktur
        if(!$klapp_status && $count_wert) {
            list_private($row["f_id"], $counter+1, $zieldatei, $userID, $cutID, $cmsgo);

            //Listing eventuell im Verzeichnis enthaltener Dateien
            $file_sql = "SELECT * FROM ".DB_PREPEND."cmsgo_file WHERE f_pid=".$row["f_id"];
            if(empty($_SESSION["wcs_user_admin"])) {
                $file_sql .= " AND f_uid=".$userID;
            }
            $file_sql .= " AND f_kid=1 AND f_trash=0 ORDER BY f_sort, f_name";

            $file_result = _dbQuery($file_sql);

            if(isset($file_result[0]['f_id'])) {

                $file_durchlauf = 0;

                foreach($file_result as $file_row) {
                    $filename = html($file_row["f_name"]);

                    $file_row["edit"] = '<a href="'.$zieldatei."&amp;editfile=".$file_row["f_id"].'" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_editfile'].": ".$filename.'">';

                    if(!$file_durchlauf) { //Aufbau der Zeile zum Einfließen der Filelisten-Tabelle
                        echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"2\"><table class=\"table-sm table-no-border\" width=\"100%\">\n";
                        echo "<!-- start file list: private-functions //-->\n";
                    } else {

                    }

                    echo "<tr>\n";
                    echo "<td width=30>";
                    echo '<i class="fa fa-fw fa-'.ext_icon($file_row["f_ext"]).' fslist-'.($counter+1).'" data-toggle="tooltip" data-html="true" title="ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'&lt;br&gt;Name: '.html($file_row["f_name"]);
                    if($file_row["f_copyright"]) {
                        echo '&lt;br&gt;&copy;: '.html($file_row["f_copyright"]);
                    }
                    echo '"></i>'.LF;
                    echo '</td>'.LF;
                    echo '<td>'.LF;
                    echo $file_row['edit'] . $filename."</a></td>\n";
                    //echo "<tr><td></td>\n<td colspan=\"2\">";



                    //Aufbauen Buttonleiste für jeweilige Datei
                    echo '<td class="text-right px-0" nowrap="nowrap">'.LF;
                    echo '<div class="btn-group" role="group">'.LF;

                    //Button zum Bearbeiten der Dateiinformationn
                    echo '<a class="btn btn-xs btn-blue" role="button" aria-disabled="true" title="'.$GLOBALS['BL']['be_fprivfunc_editfile'].": ".$filename.'" data-toggle="tooltip" href="'.$zieldatei.'&amp;editfile='.$file_row["f_id"].'"><i class="fa fa-pencil-alt fa-fw mt-1"></i></a>';

                    echo '<div class="btn-group" role="group">';
                    echo '<a class="btn btn-xs btn-blue darken dropdown-toggle" role="button" type="button" href="#" id="dropdownFcontentLink'.$file_row["f_id"].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$GLOBALS['BL']['be_func_struct_more_action'].'</a>';
                    echo '<div class="dropdown-menu" aria-labelledby="dropdownFcontentLink'.$file_row["f_id"].'">';

                    //Button zum Downloaden der Datei
                    echo '<a class="dropdown-item" href="include/inc_act/act_download.php?dl='.$file_row["f_id"].
                         '"  target="_blank" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_dlfile'].': '.$filename.'">'.
                         '<i class="ml-1 fa fa-fw fa-download" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_dlfile'].': '.$filename.'</a>'; //target='_blank'
                    //Button zum Erzeugen eines Neuen Unterverzeichnisses
                    if($cutID == $file_row["f_id"]) {
                        echo '<a class="dropdown-item" href="#"><i class="fa fa-fw fa-cut ml-1" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_cutfile'].': '.$filename.'</a>';
                    } else {
                        echo '<a class="dropdown-item" href="'.$zieldatei.'&amp;cut='.$file_row["f_id"].'" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cutfile'].': '.$filename.'">';
                        echo '<i class="ml-1 fa fa-fw fa-cut" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_cutfile'].': '.$filename.'</a>';
                    }
                    //Button zum Löschen der Datei
                    if ($file_row["f_uid"] == intval($_SESSION["wcs_user_id"])) {
                        //if user is owner then delete button is active
                        echo '<a class="dropdown-item" href="include/inc_act/act_file.php?trash='.$file_row["f_id"].'%7C'.'1'.
                         '" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_movetrash'].': '.$filename."\" onclick=\"return confirm('".
                         $GLOBALS['BL']['be_fprivfunc_jsmovetrash1']."\\n[".$filename."]\\n".$GLOBALS['BL']['be_fprivfunc_jsmovetrash2'].
                         "');\">".
                         '<i class="ml-1 far fa-fw fa-trash-alt" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_movetrash'].': '.$filename.'</a>';
                    } else {
                        echo '<div class="dropdown-item"><i class="ml-1 far fa-fw fa-trash-alt text-muted" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_notrash'].'</div>';
                    }
                    echo "</div></div></div>";

                    //Button zum Umschalten zwischen Aktiv/Inaktiv
                    echo '<button id="abtnfileaktiv'.$file_row["f_id"].'" class="btn fa fa-fw btn-sm ml-1 visible '.($file_row["f_aktiv"]==0 ? "btn-danger" : "btn-success").'" data-id="'.$file_row["f_id"].'" data-type="fileaktiv" data-table="file" data-field="f_aktiv" data-fieldid="f_id" aria-disabled="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cactivefile'].': '.$filename.'"></button>';
                    //Button zum Umschalten zwischen Public/Non-Public
                    echo '<button id="abtnfilepublic'.$file_row["f_id"].'" class="btn fa fa-fw btn-sm ml-1 public '.($file_row["f_public"]==0 ? "btn-danger" : "btn-success").'" data-id="'.$file_row["f_id"].'" data-type="filepublic" data-table="file" data-field="f_public" data-fieldid="f_id" aria-disabled="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cpublicfile'].': '.$filename.'"></button>';

                    echo "</td>\n";
                    echo "</tr>\n";


                    if(!empty($_SESSION["wcs_user_thumb"])) {

                        // now try to get existing thumbnails or if not exists
                        // build new based on default thumbnail listing sizes

                        if(empty($file_row["f_svg"])) {

                            // build thumbnail image name
                            $thumb_image = get_cached_image(array(
                                "target_ext"    =>  $file_row["f_ext"],
                                "image_name"    =>  $file_row["f_hash"] . '.' . $file_row["f_ext"],
                                "thumb_name"    =>  md5($file_row["f_hash"].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
                            ));

                            if($thumb_image != false) {
                                echo "<tr>\n";
                                echo "<td></td>\n<td colspan=\"2\">";
                                echo $file_row['edit'];
                                echo '<img src="' . $thumb_image['src'] .'" border="0" '.$thumb_image[3].'></a></td>'."\n";
                                echo "\n</tr>\n";
                            }

                        } else {
                            echo "<tr>\n";
                            echo "<td></td>\n<td colspan=\"2\">";
                            echo $file_row['edit'];
                            echo '<img src="'.CMSGO_RESIZE_IMAGE.'/'.$cmsgo["img_list_width"].'x'.$cmsgo["img_list_height"].'/'.$file_row["f_hash"].'.'.$file_row["f_ext"].'" style="max-width:'.$cmsgo["img_list_width"].'px;height:auto;"></a></td>';
                            echo "\n</tr>\n";
                        }

                    }
                    $file_durchlauf++;
                }
                if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
                    echo "</table>\n<!-- end file list: private-functions //-->\n";
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

function on_off($wert, $string, $art=1, $counter=0) {
    //Erzeugt das Status-Zeichen für Klapp-Auf/Zu
    //Wenn Art = 1 dann als Zeichen, ansonsten als Bild
    if($wert) {
        return ($art == 1) ? "+" : '<i class="fa fa-caret-right fa-fw slist-'.$counter.'" aria-hidden="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_opendir'].': '.$string.'"></i>';
    } else {
        return ($art == 1) ? "-" : '<i class="fa fa-caret-down fa-fw slist-'.$counter.'" aria-hidden="true" data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_closedir'].': '.$string.'"></i>';
    }
}
