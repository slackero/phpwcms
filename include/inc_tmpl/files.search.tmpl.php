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

// keep existing search data - outdated but seems to be enough
if(!isset($_POST["file_search"]) && isset($_SESSION['file_search_query'])) {
    $_POST = $_SESSION['file_search_query'];
}

//Search
if(isset($_POST["file_search"])) {

    $_POST["file_search"] = clean_slweg($_POST["file_search"]);

    $_SESSION['file_search_query'] = array(
        "file_search" => $_POST["file_search"],
        "file_andor" => $_POST["file_andor"],
        "file_which" => $_POST["file_which"]
    );

    $search_string  = explode(" ", $_POST["file_search"]);
    if(count($search_string)) {
        foreach($search_string as $key => $value) {
            if(($value = trim($value))) {
                $search["key"][$key] = $value;
            }
        }
        unset($search_string);
        if(isset($search["key"]) && sizeof($search["key"])) {
        //check for AND or OR
            $search["andor"] = (intval($_POST["file_andor"])) ? 1 : 0;
            $search["which"] = intval($_POST["file_which"]);
            switch($search["which"]) {
                case 0: $search["which"]="f_uid=".$_SESSION["wcs_user_id"]; break;
                case 1: $search["which"]="f_public=1"; break;
                default: $search["which"]="(f_public=1 OR f_uid=".$_SESSION["wcs_user_id"].")"; break;
            }

            $file_key = get_list_of_file_keywords(); //Auslesen der File Schlüsselwörter

            //Aufbau des eigentlichen Suchstrings
            $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_aktiv=1 AND f_trash=0 AND f_kid=1 AND ".$search["which"]; //ob public oder private order keine Angabe
            $result = _dbQuery($sql);
            if(isset($result[0]['f_id'])) {
                foreach($result as $row) {
                    $search["string"]  = $row["f_name"]." ".$row["f_shortinfo"]." ".$row["f_longinfo"];
                    $search["string"]  = str_replace(array("\r\n", "\n"), " ", $search["string"]);
                    $search["string"] .= add_keywords_to_search ($file_key, $row["f_keywords"]); //fügt freie Keywords zum Suchstring hinzu

                    foreach($search["key"] as $value) {
                        if(preg_match('/' .preg_quote($value, '/'). '/i', $search["string"])) {
                            if($search["andor"]) {
                                if(!isset($search["result"][$row["f_id"]])) {
                                    $search["result"][$row["f_id"]] = 1;
                                } else {
                                    $search["result"][$row["f_id"]]++;
                                }
                            } else {
                                $search["result"][$row["f_id"]] = 1; //OR clause
                            }
                        }
                    }
                }
                if(isset($search["result"]) && sizeof($search["result"]) && $search["andor"]) {
                    //Prüfen, ob die AND bedingung erfüllt ist
                    //gilt nur, wenn Anzahl Suchworte = Anzahl Funde im String
                    $search["count_key"] = sizeof($search["key"]);
                    foreach($search["result"] as $key => $value) {
                        if($search["count_key"] != $value) {
                            unset($search["result"][$key]);
                        }
                    }
                }
            }
        } else {
            $search["error"][1] = $BL['be_fsearch_err1'];
        }
    } else {
        $search["error"][1] = $BL['be_fsearch_err1'];
    }
}
?>

<div class="card mt-4">
  <div class="card-header"><h2><i class="fa fa-search" aria-hidden="true"></i> <?php echo $BL['be_fsearch_title'] ?></h2></div>
  <div class="card-body">
    <div class="alert alert-info"><?php echo $BL['be_fsearch_infotext'] ?></div>
    <?php if(isset($search["error"])) { //fehler suche anfang ?>
         <div class="alert alert-danger"><?php
            $zz=0;
            foreach($search["error"] as $value) {
                if($zz) echo "<br />";
                echo html($value);
                $zz++;
            }
    ?></div>
    <?php   } //fehler suche ende   ?>
    <form action="phpwcms.php?do=files&amp;f=3" method="post" enctype="multipart/form-data" name="searchfile" id="searchfile" class="form-inline mb-2">

      <label class="form-label mr-2" for="file_search"><?php echo $BL['be_fsearch_searchlabel'] ?></label>
      <input name="file_search" type="search" id="file_search" class="form-control form-control-sm mr-2 my-2 my-sm-0" value="<?php
                    if(!empty($_SESSION['file_search_query']['file_search'])) {
                        echo html($_SESSION['file_search_query']['file_search']);
                    }
                ?>" maxlength="250" />
      <script type="text/javascript"> document.searchfile.file_search.focus(); </script>

      <select name="file_andor" id="file_andor" class="custom-select form-control form-control-sm mr-2 my-2 my-sm-0">
        <?php

        $s1 = $_POST['file_andor'] ?? 1;
        $s2 = $_POST['file_which'] ?? 2;

        ?>
          <option value="1" <?php is_selected("1", $s1) ?>><?php echo $BL['be_fsearch_and'] ?></option>
          <option value="0" <?php is_selected("0", $s1) ?>><?php echo $BL['be_fsearch_or'] ?></option>
        </select>
        <select name="file_which" id="file_which" class="custom-select form-control form-control-sm mr-2 my-2 my-sm-0">
          <option value="2" <?php is_selected("2", $s2) ?>><?php echo $BL['be_fsearch_all'] ?></option>
          <option value="0" <?php is_selected("0", $s2) ?>><?php echo $BL['be_fsearch_personal'] ?></option>
          <option value="1" <?php is_selected("1", $s2) ?>><?php echo $BL['be_fsearch_public'] ?></option>
      </select>
      <button name="submit" type="submit" id="submit" class="btn btn-sm btn-blue"><?php echo $BL['be_fsearch_startsearch'] ?></button>
    </form>

<?php

if(isset($search["result"])) {
    //Beginn Tabelle für Dateilisting
    echo "<div class=\"table-responsive\">\n";
    echo "<table class=\"table table-sm table-borderless border-top mb-0\">\n";

    $sl=0;
    $search["filelist"] = " ";

    foreach($search["result"] as $key => $value) {
        if($sl) $search["filelist"] .=" OR ";
        $search["filelist"] .= "f_id=".intval($key);
        $sl++;
    }

    //Listing der gefundenen Dateien
    $file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE (" . (trim($search["filelist"]) ? $search["filelist"] : 0) . ") AND f_kid=1 AND f_trash=0 ORDER BY f_name";
    $file_result = _dbQuery($file_sql);
    if(isset($file_result[0]['f_id'])) {
        $file_durchlauf = 0;
        //new delete button
        if (empty($_SESSION["wcs_user_admin"])) {
            $result = _dbGet('phpwcms_usergroup', '*', 'group_active != 9', '', 'group_id');
            if (isset($result[0])) {
                foreach ($result as $grouplist) {
                    $grouparray[$grouplist['group_syskey']] = convertStringToArray($grouplist['group_member']);
                }
            }
            $has_filedelete_permission = !empty($grouparray['filedelete']) && in_array($_SESSION['wcs_user_id'], $grouparray['filedelete']);
        } else {
            $has_filedelete_permission = true;
        }
        $bg_toggle = false;
        foreach($file_result as $file_row) {
            $filename = html($file_row["f_name"]);
            $bg_toggle = !$bg_toggle;
            $row_class = $bg_toggle ? ' class="file-row-even"' : ' class="file-row-odd"';

            echo '<tr'.$row_class.'>';
            echo '<td width="30">';
            echo '<i class="fa fa-fw fa-'.ext_icon($file_row["f_ext"]).'" data-toggle="tooltip" data-html="true" title="ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'&lt;br&gt;Name: '.html($file_row["f_name"]).'"></i>';
            echo "</td>";
            echo "<td>";
            if(empty($_SESSION["wcs_user_admin"]) && $file_row["f_uid"] != $_SESSION["wcs_user_id"]) {
                echo "<a href=\"fileinfo.php?public&amp;fid=".$file_row["f_id"];
                echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
                $file_row['edit'] = '';
            } else {
                $file_row['edit'] = '<a href="phpwcms.php?do=files&amp;f=0&amp;editfile='.$file_row["f_id"].'" data-toggle="tooltip" title="'.$BL['be_fprivfunc_editfile'].": ".$filename.'">';
                echo $file_row['edit'];

            }
            echo $filename."</a>";
            echo "</td><td></td><td class=\"text-right text-nowrap\">";

            if($file_row['edit']) {
                echo $file_row['edit'];
                echo '<i class="btn btn-sm btn-blue fa fa-pencil-alt mr-1"></i></a>';
            }

            echo '<a href="include/inc_act/act_download.php?pl=1&dl='.$file_row["f_id"].'" data-toggle="tooltip" title="'.$BL['be_fprivfunc_dlfile'].': '.$filename.'" target="_blank">';
            echo '<i class="btn btn-sm btn-blue mr-1 fa fa-download" aria-hidden="true"></i></a>';

            if ($has_filedelete_permission || $file_row['f_uid'] == intval($_SESSION['wcs_user_id'])) {
                //if user is owner then delete button is active
                echo '<a href="include/inc_act/act_file.php?trash='.$file_row["f_id"].'%7C'.'1'.'" ';
                echo 'data-toggle="tooltip" title="'.$GLOBALS['BL']['be_fprivfunc_movetrash'].': '.$filename."\" onclick=\"alert('";
                echo $GLOBALS['BL']['be_fprivfunc_jsmovetrash1']."\\n[".$filename."]\\n".$GLOBALS['BL']['be_fprivfunc_jsmovetrash2'];
                echo "');\">", '<i class="btn btn-sm btn-blue mr-1 fa fa-trash-alt" aria-hidden="true"></i></a>';
            } else {
                echo '<i class="btn btn-sm btn-blue mr-1 fa fa-trash-alt disabled" aria-hidden="true" style="pointer-events: none; opacity: 0.5;"></i>';
            }
            echo "</td>";
            echo "</tr>";

            if($_SESSION["wcs_user_thumb"]) {
                $thumb_image = get_cached_image(array(
                    "target_ext" => $file_row["f_ext"],
                    "image_name" => $file_row["f_hash"] . '.' . $file_row["f_ext"],
                    "thumb_name" => md5($file_row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                ));

                if($thumb_image != false) {
                    echo '<tr'.$row_class.'>'."\n";
                    echo '<td></td>'."\n";
                    echo '<td colspan="3" class="pt-0 pb-2">';
                    if($file_row['edit']) {
                        echo $file_row['edit'];
                    } else {
                        echo "<a href=\"fileinfo.php?public&amp;fid=";
                        echo $file_row["f_id"]."\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=";
                        echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                    }
                    echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3]."></a></td>\n</tr>\n";
                }
            }
            $file_durchlauf++;
        }
        if($file_durchlauf) { //Abschluss der Filelisten-Tabelle

        } else {
            echo "<tr><td colspan=\"2\">";
            echo "<div class=\"alert alert-danger mt-3\">";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;".$BL['be_fsearch_nonfound'];
            echo "</div></td></tr>\n";
        }
    } //Ende Liste Dateien

    echo "</table>\n"; //Ende Tabelle
    echo "</div>\n";

} elseif(isset($search["string"])) { //kein gültiges Suchergebnis
    echo "<div class=\"alert alert-danger mt-3\">";
    echo $BL['be_fsearch_nonfound'];
    echo "</div>";
} else {
    echo $BL['be_fsearch_fillin'];
}

?>
  </div>
</div>
