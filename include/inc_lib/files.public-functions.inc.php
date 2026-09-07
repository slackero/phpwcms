<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

//Function for listing private files
function list_public($pid, $counter, $zieldatei, $userID, $wcs_user_thumb, $phpwcms) {

    $pid = intval($pid);
    $userID = intval($userID);

    //Folder listing for public files
    $sql = "SELECT f_id, f_name FROM ".DB_PREPEND."file WHERE ".
           "f_pid=".$pid." AND ".
           "f_public=1 AND f_aktiv=1 AND ".
           "f_uid=".$userID." AND ".
           "f_kid=0 AND f_trash=0 ORDER BY f_sort, f_name";

    $result = _dbQuery($sql);
    if(!isset($result[0]['f_id'])) {
        return $counter;
    }

    foreach($result as $row) {

        if(!has_public_files($row['f_id'], $userID)) {
            continue;
        }

        $dirname = html($row["f_name"]);

        //Determine toggle status
        $klapp_status = empty($_SESSION["pklapp"][$row["f_id"]]) ? 1 : 0;

        //Check if dependent files/folders exist
        $count_sql = "SELECT COUNT(f_id) FROM ".DB_PREPEND."file WHERE ".
                     "f_pid=".$row["f_id"]." AND f_uid=".$userID." AND ".
                     "f_public=1 AND f_aktiv=1 AND f_trash=0";

        if(($count_wert = _dbQuery($count_sql, 'COUNT'))) {
            $count  = '<a class="filein" href="'.$zieldatei."&amp;klapp=".$row["f_id"];
            $count .= '%7C'.$klapp_status.'">'.on_off($klapp_status, $dirname, 0, $counter);
        } else {
            $count  = on_off($klapp_status, $dirname, 0, $counter);
        }

        //Build row
        echo "<tr bgcolor=\"#EBF2F4\">\n"; //Open table row
        echo "<td>".$count; //Open cell
        echo '<i class="fa-solid fa-folder fa-fw me-1" aria-hidden="true"></i>'; //Column 1 icon
        echo "<strong>".$dirname; //Column 1 name
        if($count_wert) {
            echo "</strong></a></td>\n"; //Close cell 1
        } else {
            echo "</strong></td>\n";
        }
        //Column 2 - action buttons etc.
        echo "<td></td>\n";
        echo "</tr>\n"; //Close table row

        //Continue if sub-structure exists
        if(!$klapp_status && $count_wert) {
            list_public($row["f_id"], $counter+1, $zieldatei, $userID, $wcs_user_thumb, $phpwcms);

            //Listing of files contained in the directory
            $file_sql  = "SELECT * FROM ".DB_PREPEND."file WHERE f_pid=".$row["f_id"]." AND f_uid=".$userID;
            $file_sql .= " AND f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 ORDER BY f_sort, f_name";

            $file_result = _dbQuery($file_sql);

            if(isset($file_result[0]['f_id'])) {
                $file_durchlauf = 0;
                $bg_toggle = false;
                foreach($file_result as $file_row) {
                    $filename = html($file_row["f_name"]);
                    $bg_toggle = !$bg_toggle;
                    $row_class = $bg_toggle ? ' class="file-row-even"' : ' class="file-row-odd"';
                    if(!$file_durchlauf) {
                        echo '<tr><td colspan="2" class="p-0"><table class="table-borderless w-100">'."\n";
                    } else {

                    }
                    echo '<tr'.$row_class.'>'."\n";
                    echo '<td width="30">';
                    echo '<i class="fa-solid fa-fw fa-'.ext_icon($file_row["f_ext"]).' fslist-'.($counter+1).'" data-bs-toggle="tooltip" data-bs-html="true" title="ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'"></i>';
                    echo '</td>'."\n";
                    echo "<td>";
                    echo "<a href=\"fileinfo.php?public&amp;fid=".$file_row["f_id"];
                    echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
                    echo $filename."</a>";
                    echo "</td>\n<td></td>\n<td width=\"15\" align=\"right\">";
                    echo "<a href=\"include/inc_act/act_download.php?pl=1&dl=".$file_row["f_id"];
                    echo "\" target=\"_blank\" title=\"".$GLOBALS['BL']['be_fprivfunc_dlfile'].": ".$filename."\">";
                    echo '<i class="fa-solid fa-download me-1 text-muted" aria-hidden="true"></i></a>'; //target='_blank'
                    echo "</td>\n";
                    echo "</tr>\n";

                    if(!empty($_SESSION["wcs_user_thumb"])) {
                        if(empty($file_row["f_svg"])) {
                            $thumb_image = get_cached_image(array(
                                "target_ext"    =>  $file_row["f_ext"],
                                "image_name"    =>  $file_row["f_hash"] . '.' . $file_row["f_ext"],
                                "thumb_name"    =>  md5($file_row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                            ));
                            if($thumb_image != false) {
                                echo '<tr'.$row_class.'>'."\n";
                                echo '<td></td>'."\n";
                                echo '<td colspan="3" class="pt-0 pb-2"><a href="fileinfo.php?public&amp;fid=';
                                echo $file_row["f_id"].'" target="_blank" onclick="flevPopupLink(this.href,\'filedetail\',\'scrollbars=';
                                echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                                echo '<img src="' . $thumb_image['src'] .'" border="0" style="max-height:'.$phpwcms["img_list_height"].'px;max-width:100%;width:auto;height:auto;" '.$thumb_image[3];
                                echo ' onmouseover="Tip(\'ID: '.$file_row["f_id"].'\');" onmouseout="UnTip()" alt=""';
                                echo '></a></td>'."\n";
                                echo "</tr>\n";
                            }
                        } else {
                            echo '<tr'.$row_class.'>'."\n";
                            echo '<td></td>'."\n";
                            echo '<td colspan="3" class="pt-0 pb-2"><a href="fileinfo.php?public&amp;fid=';
                            echo $file_row["f_id"].'" target="_blank" onclick="flevPopupLink(this.href,\'filedetail\',\'scrollbars=';
                            echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                            echo '<img src="'.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$file_row['f_hash'].'.'.$file_row["f_ext"].'" style="max-height:'.$phpwcms["img_list_height"].'px;max-width:100%;width:auto;height:auto;"';
                            echo ' onmouseover="Tip(\'ID: '.$file_row["f_id"].'\');" onmouseout="UnTip()" alt=""';
                            echo '></a></td>'."\n";
                            echo "</tr>\n";
                        }
                    }

                    $file_durchlauf++;
                }
                if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
                    echo "</table>\n";
                }
            } //Ende Liste Dateien
        }
        // Increment counter
        $_SESSION["list_zaehler"]++;
    }

    return $counter;
}

function true_false($wert) {
    // Toggle boolean value: 1=>0 and 0=>1
    return (intval($wert)) ? 0 : 1;
}

function on_off($wert, $string, $art=1, $counter=0) {
    // Generate status icon for expand/collapse
    // If art = 1 return character (+/-), otherwise icon
    if($wert) {
        return ($art == 1) ? '+' : '<i class="fa-solid fa-caret-right fa-fw slist-'.$counter.'" aria-hidden="true" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_opendir'].': '.$string.'"></i>';
    } else {
        return ($art == 1) ? '-' : '<i class="fa-solid fa-caret-down fa-fw slist-'.$counter.'" aria-hidden="true" data-bs-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_closedir'].': '.$string.'"></i>';
    }
}

function list_public_root($wert) {
    // Check if public root files should be displayed for user
    return ($wert) ? 1 : 0;
}

function has_public_files($folder_id, $userID) {
    $folder_id = intval($folder_id);
    $userID = intval($userID);

    // Check if there is any file directly in this folder
    $file_sql = 'SELECT COUNT(f_id) FROM '.DB_PREPEND.'file WHERE f_pid='.$folder_id.' AND f_uid='.$userID.' AND f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0';
    if(_dbQuery($file_sql, 'COUNT') > 0) {
        return true;
    }

    // Check if there are subfolders, and if any of them contain public files
    $sub_sql = 'SELECT f_id FROM '.DB_PREPEND.'file WHERE f_pid='.$folder_id.' AND f_uid='.$userID.' AND f_public=1 AND f_aktiv=1 AND f_kid=0 AND f_trash=0';
    $subfolders = _dbQuery($sub_sql);
    if(isset($subfolders[0]['f_id'])) {
        foreach($subfolders as $sub) {
            if(has_public_files($sub['f_id'], $userID)) {
                return true;
            }
        }
    }

    return false;
}
