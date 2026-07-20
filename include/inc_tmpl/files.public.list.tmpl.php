<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/


// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//Default for listing public files
$vor = 0;

if(!isset($_SESSION["pklapp"]) || (isset($_GET["all"]) && $_GET["all"] == "close")) {
    $_SESSION["pklapp"] = array();
}

if(isset($_GET["pklapp"])) {

    list($pklapp_id, $pklapp_value) = explode("|", $_GET["pklapp"]);

    if(intval($pklapp_value)) {
        $_SESSION["pklapp"][$pklapp_id] = 1;
    } else {
        unset($_SESSION["pklapp"][$pklapp_id]);
    }

    foreach($_SESSION["pklapp"] as $pklapp_id => $pklapp_value) {
        if(!$pklapp_value) {
            unset($_SESSION["pklapp"][$pklapp_id]);
        }
    }

    _dbQuery("UPDATE ".DB_PREPEND."phpwcms_user SET usr_var_publicfile="._dbEscape(serialize($_SESSION["pklapp"]))." WHERE usr_id=".$_SESSION["wcs_user_id"], 'UPDATE');
}

if(isset($_GET["klapp"])) {

    list($klapp_id, $klapp_value) = explode("|", $_GET["klapp"]);
    $klapp_id = intval($klapp_id);

    if(intval($klapp_value)) {
        $_SESSION["pklapp"][$klapp_id] = 1;
    } else {
        unset($_SESSION["pklapp"][$klapp_id]);
    }

    foreach($_SESSION["pklapp"] as $pklapp_id => $pklapp_value) {
        if(!$pklapp_value) {
            unset($_SESSION["pklapp"][$pklapp_id]);
        }
    }

    _dbQuery("UPDATE ".DB_PREPEND."phpwcms_user SET usr_var_publicfile="._dbEscape(serialize($_SESSION["pklapp"]))." WHERE usr_id=".$_SESSION["wcs_user_id"], 'UPDATE');
}

$_SESSION["list_zaehler"] = 0; // set counter

//Feststellen, ob überhaupt Dateien/Ordner des Users vorhanden sind
$count_user_files = _dbQuery("SELECT COUNT(f_id) FROM ".DB_PREPEND."phpwcms_file WHERE f_public=1 AND f_aktiv=1 AND f_trash=0", 'COUNT');

if($count_user_files) { //Wenn überhaupt Public-Dateien vorhanden, dann Listing
    //Beginn Tabelle für Public Dateilisting
    echo "<table class=\"table table-sm\">\n";


    //Prüfen, für welche User überhaupt Public Files vorhanden sind
    $sql = "SELECT DISTINCT ".DB_PREPEND."phpwcms_file.f_uid, ".DB_PREPEND."phpwcms_user.usr_login, ".DB_PREPEND."phpwcms_user.usr_name ".
           "FROM ".DB_PREPEND."phpwcms_file INNER JOIN ".DB_PREPEND."phpwcms_user ON ".DB_PREPEND."phpwcms_file.f_uid=".DB_PREPEND."phpwcms_user.usr_id ".
           "WHERE ".DB_PREPEND."phpwcms_file.f_public=1 AND ".DB_PREPEND."phpwcms_file.f_aktiv=1 AND ".DB_PREPEND."phpwcms_file.f_trash=0 ".
           "ORDER BY ".DB_PREPEND."phpwcms_user.usr_name, ".DB_PREPEND."phpwcms_user.usr_login";
    $result = _dbQuery($sql);

    $user_counter=0;
    $counter=0;
    if(isset($result[0]['f_uid'])) {
        foreach($result as $row) {
            //Prüfen
            $pklapp_status = empty($_SESSION["pklapp"][ "u".$row["f_uid"] ]) ? 1 : 0;
            $root_user_id = intval($row["f_uid"]);
            $user_naming = html($row["usr_name"]." (".$row["usr_login"].")");
            $count = "<a href=\"phpwcms.php?do=files&amp;f=1&amp;pklapp=u".$row["f_uid"].
                     "|".$pklapp_status."\">".on_off($pklapp_status, "\n".$BL['be_fpublic_user'].": ".$user_naming, 0, $counter)."</a>";

            //Aufbau der Zeile mit den Benutzerinfos
            if($user_counter) {

            }
            echo "<tr bgcolor=\"#D8E4E9\">\n"; //Einleitung Tabellenzeile
            echo "<td width=\"488\" class=\"msglist\">"; //Einleiten der Tabellenzelle
            echo $count."<i class=\"fa fa-user\"></i>";
            echo "<strong>".$user_naming."</strong></td>\n"; //Schließen Zelle 1. Spalte
            echo "<td width=\"50\" align=\"right\" class=\"msglist\">"; //Zelle 2. Spalte - vorgesehen für Buttons/Tasten Edit etc.
            echo "</td>\n";
            echo "</tr>\n"; //Abschluss Tabellenzeile

            if(!$pklapp_status) {
                list_public(0, 0, "phpwcms.php?do=files&amp;f=1", $row["f_uid"], $_SESSION["wcs_user_thumb"], $phpwcms);

                //Root files anzeigen
                $file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=0 AND f_uid=".$root_user_id.
                            " AND f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 ORDER BY f_name";
                $file_result = _dbQuery($file_sql);
                if(isset($file_result[0]['f_id'])) {
                    $file_durchlauf = 0;
                    $bg_toggle = false;
                    foreach($file_result as $file_row) {
                        $filename = html($file_row["f_name"]);
                        $bg_toggle = !$bg_toggle;
                        $row_class = $bg_toggle ? ' class="file-row-even"' : ' class="file-row-odd"';
                        if(!$file_durchlauf) { //Aufbau der Zeile zum Einfließen der Filelisten-Tabelle
                            echo '<tr><td colspan="2" class="p-0"><table class="table-borderless w-100">'."\n";
                        }
                        echo '<tr'.$row_class.'>'."\n";
                        echo '<td width="30">';
                        echo '<span class="admin-slist" data-toggle="tooltip" data-html="true" title="ID: '.$file_row["f_id"].' <br>Sort: '.$file_row["f_sort"].'"><i class="fa fa-'.extimg($file_row["f_ext"]).'"></i></span>';
                        echo "</td>\n";
                        echo "<td>";
                        echo "<a href=\"fileinfo.php?public&amp;fid=".$file_row["f_id"];
                        echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
                        echo $filename."</a>";
                        echo "</td>\n";
                        echo '<td class="text-right text-nowrap px-0">';
                        echo "<a href=\"include/inc_act/act_download.php?pl=1&dl=".$file_row["f_id"];
                        echo "\" target=\"_blank\" title=\"".$BL['be_fprivfunc_dlfile'].": ".$filename."\">";
                        echo '<i class="btn btn-sm btn-blue mr-1 fa fa-download" aria-hidden="true"></i></a>';
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
                                    echo '<td colspan="2" class="pt-0 pb-2"><a href="fileinfo.php?public&amp;fid=';
                                    echo $file_row["f_id"].'" target="_blank" onclick="flevPopupLink(this.href,\'filedetail\',\'scrollbars=';
                                    echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                                    echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3]."></a></td>\n</tr>\n";
                                }
                            } else {
                                echo '<tr'.$row_class.'>'."\n";
                                echo '<td></td>'."\n";
                                echo '<td colspan="2" class="pt-0 pb-2"><a href="fileinfo.php?public&amp;fid=';
                                echo $file_row["f_id"].'" target="_blank" onclick="flevPopupLink(this.href,\'filedetail\',\'scrollbars=';
                                echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                                echo '<img src="'.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$file_row["f_hash"].'.'.$file_row["f_ext"].'" style="max-width:'.$phpwcms["img_list_width"].'px;height:auto;">';
                                echo "</a></td>\n</tr>\n";
                            }
                        }

                        $file_durchlauf++;
                    }
                    if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
                        echo "</table>\n";
                    }
                } //Ende Liste Dateien

                //Ende Anzeige root files public
            }
            $user_counter++;
            $counter++;
        }
    }
    echo "</table>\n"; //Ende Tabelle
} else { //Wenn keinerlei Datensatz innerhalb Files durchlaufen wurde, dann
    echo $BL['be_fpublic_nofiles']."&nbsp;&nbsp;";
  echo "[<a href=\"phpwcms.php?do=files&amp;f=0&amp;mkdir=0\">".$BL['be_fpriv_button']."</a>]";

}
