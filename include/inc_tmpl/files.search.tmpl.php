<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
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
            $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_file WHERE f_aktiv=1 AND f_trash=0 AND f_kid=1 AND ".$search["which"]; //ob public oder private order keine Angabe
            $result = _dbQuery($sql);
            if(isset($result[0]['f_id'])) {
                foreach($result as $row) {
                    $search["string"]  = $row["f_name"]." ".$row["f_shortinfo"]." ".$row["f_longinfo"];
                    $search["string"]  = str_replace(array("\r\n", "\n"), " ", $search["string"]);
                    $search["string"] .= add_keywords_to_search ($file_key, $row["f_keywords"]); //fügt freie Keywords zum Suchstring hinzu

                    foreach($search["key"] as $value) {
                        if(preg_match("/".preg_quote($value,"/")."/i", $search["string"])) {
                            if($search["andor"]) {
                                if(!isset($search["result"][$row["f_id"]])) {; //AND clause
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
                        if($search["count_key"] != $value) unset($search["result"][$key]);
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
    <form action="cmsgo.php?do=files&amp;f=3" method="post" enctype="multipart/form-data" name="searchfile" id="searchfile" class="form-inline mb-2">

      <label class="form-label mr-2" for="file_search"><?php echo $BL['be_fsearch_searchlabel'] ?></label>
      <input name="file_search" type="search" id="file_search" class="form-control form-control-sm mr-2 my-2 my-sm-0" value="<?php
                    if(!empty($_SESSION['file_search_query']["file_search"])) {
                        echo html($_SESSION['file_search_query']["file_search"]);
                    }
                ?>" maxlength="250" />
      <script type="text/javascript"> document.searchfile.file_search.focus(); </script>

      <select name="file_andor" id="file_andor" class="custom-select form-control form-control-sm mr-2 my-2 my-sm-0">
        <?php

        $s1 = isset($_POST["file_andor"]) ? $_POST["file_andor"] : 1;
        $s2 = isset($_POST["file_which"]) ? $_POST["file_which"] : 2;

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
    echo "<table class=\"table table-sm mt-3 mb-0\">\n";

    $sl=0;
    $search["filelist"] = " ";

    foreach($search["result"] as $key => $value) {
        if($sl) $search["filelist"] .=" OR ";
        $search["filelist"] .= "f_id=".intval($key);
        $sl++;
    }

    //Listing der gefundenen Dateien
    $file_sql = "SELECT * FROM ".DB_PREPEND."cmsgo_file WHERE (" . (trim($search["filelist"]) ? $search["filelist"] : 0) . ") AND f_kid=1 AND f_trash=0 ORDER BY f_name";
    $file_result = _dbQuery($file_sql);
    if(isset($file_result[0]['f_id'])) {
        $file_durchlauf = 0;
        foreach($file_result as $file_row) {
            $filename = html($file_row["f_name"]);
            echo "<tr>\n";
            echo "<td width=\"13\">";
             echo '<i class="fa fa-lg fa-fw fa-'.ext_icon($file_row["f_ext"]).'" data-toggle="tooltip" data-html="true" title="ID: '.$file_row["f_id"].'&lt;br&gt;Sort: '.$file_row["f_sort"].'&lt;br&gt;Name: '.html($file_row["f_name"]).'"></i>';
            echo "</td>\n";
            echo "<td>";
            if(empty($_SESSION["wcs_user_admin"]) && $file_row["f_uid"] != $_SESSION["wcs_user_id"]) {
                echo "<a href=\"fileinfo.php?public&amp;fid=".$file_row["f_id"];
                echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";
                $file_row['edit'] = '';
            } else {
                $file_row['edit'] = '<a href="cmsgo.php?do=files&amp;f=0&amp;editfile='.$file_row["f_id"].'" data-toggle="tooltip" title="'.$BL['be_fprivfunc_editfile'].": ".$filename.'">';
                echo $file_row['edit'];

            }
            echo $filename."</a>";
            echo "</td>\n<td>\n";
            if($_SESSION["wcs_user_thumb"]) {
                $thumb_image = get_cached_image(array(
                    "target_ext" => $file_row["f_ext"],
                    "image_name" => $file_row["f_hash"] . '.' . $file_row["f_ext"],
                    "thumb_name" => md5($file_row["f_hash"].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
                ));

                if($thumb_image != false) {
                    if($file_row['edit']) {
                        echo $file_row['edit'];
                    } else {
                        echo "<a href=\"fileinfo.php?public&amp;fid=";
                        echo $file_row["f_id"]."\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=";
                        echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                    }
                    echo '<img src="'.CMSGO_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3]."></a>";
                }
            }
            echo "</td>\n<td class=\"text-right text-nowrap\">";

            if($file_row['edit']) {
                echo $file_row['edit'];
                echo '<i class="btn btn-sm btn-blue fa fa-pencil mr-1"></i></a>';
            }

            echo '<a href="include/inc_act/act_download.php?pl=1&dl='.$file_row["f_id"].'" data-toggle="tooltip" title="'.$BL['be_fprivfunc_dlfile'].': '.$filename.'" target="_blank">';
            echo '<i class="btn btn-sm btn-blue mr-1 fa fa-download" aria-hidden="true"></i></a>';
            echo "</td>\n";
            //Ende Aufbau
            echo "</tr>\n";
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

} else {
    //kein gültiges Suchergebnis
    if(isset($search["string"])) {
        echo "<div class=\"alert alert-danger mt-3\">";
        echo $BL['be_fsearch_nonfound'];
        echo "</div>";
    } else {
        echo $BL['be_fsearch_fillin'];
    }
}

?>
  </div>
</div>
