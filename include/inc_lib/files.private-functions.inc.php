<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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
function list_private($pid, $counter, $zieldatei, $userID, $cutID, $phpwcms) {
    $cutID = intval($cutID);
    $pid = intval($pid);
    $sql  = "SELECT * FROM ".DB_PREPEND."file f ";
    $sql .= "LEFT JOIN ".DB_PREPEND."user u ON u.usr_id=f.f_uid ";
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

        // Determine toggle status
        $klapp_status = empty($_SESSION["klapp"][$row["f_id"]]) ? 1 : 0;

        // Check if dependent files/folders exist
        $count_sql  = "SELECT COUNT(f_id) FROM ".DB_PREPEND."file WHERE ";
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

        // Build row
        echo '<tr bgcolor="#F4F5F4">'.LF; // Open table row
        echo '<td>'.$count; // Open cell


        // Gallery status
        switch($row["f_gallerystatus"]) {

            case 2:
                // gallery root dir
                echo '<i class="fa-solid fa-folder fa-fw me-1" aria-hidden="true" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_gallery_root'].'"></i>';
                break;

            case 3:
                // gallery subdir
                echo '<i class="fa-solid fa-folder fa-fw me-1 text-warning" aria-hidden="true" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_gallery_directory'].'"></i>';
                break;

            default:
                echo '<i class="fa-solid fa-folder fa-fw me-1" aria-hidden="true"></i>';
        }

        echo "<strong>".$dirname; // Column 1 name
        echo "</strong></a></td>\n"; // Close column 1
        // Column 2 (action buttons)
        echo '<td class="text-end text-nowrap">';

        echo '<div class="btn-group btn-group-sm" role="group" aria-label="group'.$row["f_id"].'">';
        // Edit directory button
        echo '<a class="btn btn-sm btn-blue" role="button" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_edit'].": ".$dirname.'" href="'.$zieldatei."&amp;editdir=".$row["f_id"].'"><i class="fa-solid fa-pencil-alt fa-fw"></i></a>';
        echo '<div class="btn-group btn-group-sm" role="group">';
        echo '<a class="btn btn-sm btn-blue darken dropdown-toggle" role="button" href="#" id="dropdownFcontentLink'.$row["f_id"].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$GLOBALS['BL']['be_func_struct_more_action'].'</a>';
        echo '<div class="dropdown-menu" aria-labelledby="dropdownFcontentLink'.$row["f_id"].'">';
        echo '<h6 class="dropdown-header">'.$dirname.'</h6>';

        // Upload file to directory button
        echo '<a class="dropdown-item" href="'.$zieldatei.'&amp;upload='.$row["f_id"].'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_upload'].': '.$dirname.'">';
        echo '<i class="ms-1 fa-solid fa-fw fa-upload" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_upload'].'</a>';
        if(!$cutID) { // Create new subdirectory button
            echo '<a class="dropdown-item" role="button" href="'.$zieldatei.'&amp;mkdir='.$row["f_id"].'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_makenew'].': '.$dirname.'">';
            echo '<i class="ms-1 fa-solid fa-fw fa-plus" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_makenew'].'</a>';
        } else {  // Paste clipboard file into directory button
            echo '<a class="dropdown-item" role="button" href="include/inc_act/act_file.php?paste='.$cutID.'%7C'.$row["f_id"].
                 '" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_paste'].': '.$dirname.'">';
            echo '<i class="ms-1 fa-solid fa-fw fa-clipboard" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_paste'].'</a>';
        }
        // Delete directory button if empty
        if(!$count_wert) {
            echo '<a class="dropdown-item" href="include/inc_act/act_file.php?delete='.$row["f_id"].'%7C'.'9'.
                 '" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_deldir'].': '.$dirname.'" data-confirm-danger="'.html_specialchars($GLOBALS['BL']['be_fprivfunc_jsdeldir'] . " \n[".$dirname."]? ") . '">';
            echo '<i class="ms-1 disabled fa-regular fa-fw fa-trash-alt" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_deldir'].'</a>';
        } else {
            echo '<div class="dropdown-item disabled text-muted"><i class="ms-1 disabled fa-regular fa-fw fa-trash-alt text-muted" aria-hidden="true" data-bs-toggle="tooltip" title="';
            echo str_replace('{VAL}', $dirname, $GLOBALS['BL']['be_fprivfunc_notempty']).'"></i> '.$GLOBALS['BL']['be_fprivfunc_notempty_short'].'</div>';
        }
        echo '</div></div>';

        //Button zum Umschalten zwischen Aktiv/Inaktiv
        echo '<button id="abtnfileaktiv'.$row["f_id"].'" class="btn fa-solid fa-fw btn-sm visible '.($row["f_aktiv"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$row["f_id"].'" data-type="fileaktiv" data-table="file" data-field="f_aktiv" data-fieldid="f_id" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cactive'].': '.$dirname.'"><i class="fa-solid '.($row["f_aktiv"]==0 ? "fa-eye-slash" : "fa-eye").' fa-fw" aria-hidden="true"></i></button>';
        //Button zum Umschalten zwischen Public/Non-Public
        echo '<button id="abtnfilepublic'.$row["f_id"].'" class="btn fa-solid fa-fw btn-sm public '.($row["f_public"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$row["f_id"].'" data-type="filepublic" data-table="file" data-field="f_public" data-fieldid="f_id" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cpublic'].': '.$dirname.'"><i class="fa-solid '.($row["f_public"]==0 ? "fa-lock" : "fa-lock-open").' fa-fw" aria-hidden="true"></i></button>';
        echo '</div>';
        echo '</td>'.LF;
        echo '</tr>'.LF; //Abschluss Tabellenzeile

        //Weiter, wenn Unterstruktur
        if(!$klapp_status && $count_wert) {
            list_private($row["f_id"], $counter+1, $zieldatei, $userID, $cutID, $phpwcms);

            //Listing eventuell im Verzeichnis enthaltener Dateien
            $file_sql = "SELECT * FROM ".DB_PREPEND."file WHERE f_pid=".$row["f_id"];
            if(empty($_SESSION["wcs_user_admin"])) {
                $file_sql .= " AND f_uid=".$userID;
            }
            $file_sql .= " AND f_kid=1 AND f_trash=0 ORDER BY f_sort, f_name";

            $file_result = _dbQuery($file_sql);

            if(isset($file_result[0]['f_id'])) {

                $file_durchlauf = 0;
                $bg_toggle = false;

                foreach($file_result as $file_row) {
                    $filename = html($file_row["f_name"]);
                    $bg_toggle = !$bg_toggle;
                    $row_class = $bg_toggle ? ' class="file-row-even"' : ' class="file-row-odd"';

                    $file_row["edit"] = '<a href="'.$zieldatei."&amp;editfile=".$file_row["f_id"].'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_editfile'].": ".$filename.'">';

                    if(!$file_durchlauf) { // Open embedded table for file list
                        echo '<tr bgcolor="#FFFFFF"><td colspan="2" class="p-0"><table class="table-sm table-borderless w-100">'."\n";
                        echo "<!-- start file list: private-functions //-->\n";
                    } else {

                    }

                    echo '<tr'.$row_class.">\n";
                    echo "<td width=30>";
                    echo '<i class="fa-solid fa-fw fa-'.ext_icon($file_row["f_ext"]).' fslist-'.($counter+1).'" data-bs-toggle="tooltip" data-bs-html="true" title="ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'&lt;br&gt;Name: '.html($file_row["f_name"]);
                    if($file_row["f_copyright"]) {
                        echo '&lt;br&gt;&copy;: '.html($file_row["f_copyright"]);
                    }
                    echo '"></i>'.LF;
                    echo '</td>'.LF;
                    echo '<td>'.LF;
                    echo $file_row['edit'] . $filename."</a></td>\n";
                    //echo "<tr><td></td>\n<td colspan=\"2\">";



                    // Build button bar for file
                    echo '<td class="text-end text-nowrap px-0">'.LF;
                    echo '<div class="btn-group btn-group-sm" role="group">'.LF;

                    // Edit file info button
                    echo '<a class="btn btn-sm btn-blue" role="button" title="'.$GLOBALS['BL']['be_fprivfunc_editfile'].": ".$filename.'" data-bs-toggle="tooltip" href="'.$zieldatei.'&amp;editfile='.$file_row["f_id"].'"><i class="fa-solid fa-pencil-alt fa-fw"></i></a>';

                    echo '<div class="btn-group btn-group-sm" role="group">';
                    echo '<a class="btn btn-sm btn-blue darken dropdown-toggle" role="button" href="#" id="dropdownFcontentLink'.$file_row["f_id"].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$GLOBALS['BL']['be_func_struct_more_action'].'</a>';
                    echo '<div class="dropdown-menu" aria-labelledby="dropdownFcontentLink'.$file_row["f_id"].'">';
                    echo '<h6 class="dropdown-header">'.$filename.'</h6>';

                    // Download file button
                    echo '<a class="dropdown-item" href="include/inc_act/act_download.php?dl='.$file_row["f_id"].
                         '"  target="_blank" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_dlfile'].': '.$filename.'">'.
                         '<i class="ms-1 fa-solid fa-fw fa-download" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_dlfile'].'</a>'; //target='_blank'
                    // Cut / clipboard file button
                    if($cutID == $file_row["f_id"]) {
                        echo '<a class="dropdown-item" href="#"><i class="fa-solid fa-fw fa-cut ms-1" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_cutfile'].'</a>';
                    } else {
                        echo '<a class="dropdown-item" href="'.$zieldatei.'&amp;cut='.$file_row["f_id"].'" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cutfile'].': '.$filename.'">';
                        echo '<i class="ms-1 fa-solid fa-fw fa-cut" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_cutfile'].'</a>';
                    }
                    // Delete / move to trash button
                    if ($file_row["f_uid"] == intval($_SESSION["wcs_user_id"])) {
                        //if user is owner then delete button is active
                        $confirm_msg = $GLOBALS['BL']['be_fprivfunc_jsmovetrash1'] . "\n[" . $filename . "]\n" . $GLOBALS['BL']['be_fprivfunc_jsmovetrash2'];
                        echo '<a class="dropdown-item" href="include/inc_act/act_file.php?trash=' . $file_row["f_id"] . '%7C' . '1' .
                             '" data-bs-toggle="tooltip" title="' . $GLOBALS['BL']['be_fprivfunc_movetrash'] . ': ' . $filename . '" data-confirm-danger="' . html_specialchars($confirm_msg) . '">' .
                             '<i class="ms-1 fa-regular fa-fw fa-trash-alt" aria-hidden="true"></i> ' . $GLOBALS['BL']['be_fprivfunc_movetrash'] . '</a>';
                    } else {
                        echo '<div class="dropdown-item disabled text-muted"><i class="fa-regular fa-fw fa-trash-alt text-muted ms-1" aria-hidden="true"></i> '.$GLOBALS['BL']['be_fprivfunc_notrash'].'</div>';
                    }
                    echo '</div></div>'; // Close dropdown-menu & inner btn-group

                    // Toggle active/inactive button
                    echo '<button id="abtnfileaktiv'.$file_row["f_id"].'" class="btn fa-solid fa-fw btn-sm visible '.($file_row["f_aktiv"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$file_row["f_id"].'" data-type="fileaktiv" data-table="file" data-field="f_aktiv" data-fieldid="f_id" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cactivefile'].': '.$filename.'"><i class="fa-solid '.($file_row["f_aktiv"]==0 ? "fa-eye-slash" : "fa-eye").' fa-fw" aria-hidden="true"></i></button>';
                    // Toggle public/private button
                    echo '<button id="abtnfilepublic'.$file_row["f_id"].'" class="btn fa-solid fa-fw btn-sm public '.($file_row["f_public"]==0 ? "btn-warning" : "btn-success").'" data-id="'.$file_row["f_id"].'" data-type="filepublic" data-table="file" data-field="f_public" data-fieldid="f_id" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_cpublicfile'].': '.$filename.'"><i class="fa-solid '.($file_row["f_public"]==0 ? "fa-lock" : "fa-lock-open").' fa-fw" aria-hidden="true"></i></button>';
                    echo '</div>'; // Close outer btn-group

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
                                echo '<tr'.$row_class.">\n";
                                echo '<td></td>'."\n".'<td colspan="2" class="pt-0 pb-2">';
                                echo $file_row['edit'];
                                echo '<img src="' . $thumb_image['src'] .'" border="0" style="max-height:'.$phpwcms["img_list_height"].'px;max-width:100%;width:auto;height:auto;" '.$thumb_image[3].'></a></td>'."\n";
                                echo "\n</tr>\n";
                            }

                        } else {
                            echo '<tr'.$row_class.">\n";
                            echo '<td></td>'."\n".'<td colspan="2" class="pt-0 pb-2">';
                            echo $file_row['edit'];
                            echo '<img src="'.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$file_row["f_hash"].'.'.$file_row["f_ext"].'" style="max-height:'.$phpwcms["img_list_height"].'px;max-width:100%;width:auto;height:auto;"></a></td>';
                            echo "\n</tr>\n";
                        }

                    }
                    $file_durchlauf++;
                }
                if($file_durchlauf) { // Close file list table
                    echo "</table>\n<!-- end file list: private-functions //-->\n";
                }
            } // End file list
        }

        // Increment counter
        $_SESSION["list_zaehler"]++;
    }

    return $vor;
}

function true_false($wert) {
    // Toggle boolean value: 1=>0 and 0=>1
    return (intval($wert)) ? 0 : 1;
}

function on_off($wert, $string, $art=1, $counter=0) {
    // Generate status icon for expand/collapse
    // If art = 1 return character (+/-), otherwise icon
    if($wert) {
        return ($art == 1) ? "+" : '<i class="fa-solid fa-caret-right fa-fw slist-'.$counter.'" aria-hidden="true" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_opendir'].': '.$string.'"></i>';
    } else {
        return ($art == 1) ? "-" : '<i class="fa-solid fa-caret-down fa-fw slist-'.$counter.'" aria-hidden="true" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_closedir'].': '.$string.'"></i>';
    }
}
