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
                        if(preg_match("/".preg_quote($value,"/")."/i", $search["string"])) {
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
<form action="phpwcms.php?do=files&amp;f=3" method="post" enctype="multipart/form-data" name="searchfile" id="searchfile">
<table width="538" border="0" cellpadding="0" cellspacing="0" bgcolor='#EBF2F4' summary="">
    <tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
    <tr>
    <td width="67" rowspan="3" align="right" valign="top"><img src="img/leer.gif" alt="" width="10" height="1" /><img src="img/symbole/lupe_suche.gif" alt="" width="23" height="21" /><img src="img/leer.gif" alt="" width="10" height="1" /></td>
    <td width="471" class="title"><?php echo $BL['be_fsearch_title'] ?></td>
    </tr>
    <tr><td valign="top"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
    <tr><td class="v09"><?php echo $BL['be_fsearch_infotext'] ?></td></tr>
    <tr>
      <td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
      </tr>
      <?php if(isset($search["error"])) { //fehler suche anfang ?>
        <tr>
            <td valign="top">&nbsp;</td>
            <td valign="top" class="error"><?php
                    $zz=0;
                    foreach($search["error"] as $value) {
                        if($zz) echo "<br />";
                        echo html($value);
                        $zz++;
                    }
      ?></td>
      </tr>
      <tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
      <?php   } //fehler suche ende   ?>
    <tr>
        <td align="right" class="v09"><?php echo $BL['be_fsearch_searchlabel'] ?>:&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
          <tr>
            <td>
                <input name="file_search" type="search" class="v11" id="file_search" style="font-weight:bold;width:260px;" value="<?php
                if(!empty($_SESSION['file_search_query']["file_search"])) {
                    echo html($_SESSION['file_search_query']["file_search"]);
                }
            ?>" size="40" maxlength="250" /><img src="img/leer.gif" alt="" width="2" height="1" /><script type="text/javascript"> document.searchfile.file_search.focus(); </script></td>
            <td><select name="file_andor" id="file_andor" class="v11">
            <?php

            $s1 = isset($_POST["file_andor"]) ? $_POST["file_andor"] : 1;
            $s2 = isset($_POST["file_which"]) ? $_POST["file_which"] : 2;

            ?>
              <option value="1" <?php is_selected("1", $s1) ?>><?php echo $BL['be_fsearch_and'] ?></option>
              <option value="0" <?php is_selected("0", $s1) ?>><?php echo $BL['be_fsearch_or'] ?></option>
              </select><select name="file_which" id="file_which" class="v11">
              <option value="2" <?php is_selected("2", $s2) ?>><?php echo $BL['be_fsearch_all'] ?></option>
              <option value="0" <?php is_selected("0", $s2) ?>><?php echo $BL['be_fsearch_personal'] ?></option>
              <option value="1" <?php is_selected("1", $s2) ?>><?php echo $BL['be_fsearch_public'] ?></option>
              </select><img src="img/leer.gif" alt="" width="3" height="1" /></td>
            <td><input name="submit" type="image" id="submit" src="img/button/go_search.gif" alt="<?php echo $BL['be_fsearch_startsearch'] ?>" width="22" height="14" /></td>
            </tr>
          </table></td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
    </tr>
    <tr><td colspan="2" bgcolor="#9BBECA"><img src="img/leer.gif" alt="" width="1" height="4" /></td>
    </tr>
</table>
</form>
<?php

if(isset($search["result"])) {
    //Beginn Tabelle für Dateilisting
    echo "<table width=\"538\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "<tr><td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"1\" /></td></tr>\n";

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
        foreach($file_result as $file_row) {
            $filename = html($file_row["f_name"]);
            if(!$file_durchlauf) { //Aufbau der Zeile zum Einflie�en der Filelisten-Tavbelle
                echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><table width=\"538\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
            } else {
                echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"5\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" /></td></tr>\n";
            }
            echo "<tr>\n";
            echo "<td width=\"6\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\" border=\"0\" /></td>\n";
            echo "<td width=\"13\" class=\"msglist\">";
            echo "<img src=\"img/icons/small_".extimg($file_row["f_ext"])."\" border=\"0\"></td>\n";
            echo "<td width=\"482\" class=\"msglist\"><img src=\"img/leer.gif\" height=\"1\" width=\"5\" />";

            if(empty($_SESSION["wcs_user_admin"]) && $file_row["f_uid"] != $_SESSION["wcs_user_id"]) {

                echo "<a href=\"fileinfo.php?public&amp;fid=".$file_row["f_id"];
                echo "\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=yes,resizable=yes,width=500,height=400',1);return document.MM_returnValue;\">";

                $file_row['edit'] = '';

            } else {

                $file_row['edit'] = '<a href="phpwcms.php?do=files&amp;f=0&amp;editfile='.$file_row["f_id"].'" title="'.$BL['be_fprivfunc_editfile'].": ".$filename.'">';
                echo $file_row['edit'];

            }

            echo $filename."</a>";

            echo "</td>\n";
            echo "<td width=\"37\" align=\"right\" class=\"msglist\">";

            if($file_row['edit']) {
                echo $file_row['edit'];
                echo "<img src=\"img/button/edit_22x13.gif\" border=\"0\"></a>";
            }

            echo "<a href=\"include/inc_act/act_download.php?pl=1&dl=".$file_row["f_id"];
            echo "\" target=\"_blank\" title=\"".$BL['be_fprivfunc_dlfile'].": ".$filename."\" target=\"_blank\">";
            echo "<img src=\"img/button/download_disc.gif\" border=\"0\" /></a>";
            echo "<img src=\"img/leer.gif\" width=\"2\" height=\"1\" />"; //Spacer
            echo "</td>\n";
            //Ende Aufbau
            echo "</tr>\n";

            if($_SESSION["wcs_user_thumb"]) {

                $thumb_image = get_cached_image(array(
                    "target_ext" => $file_row["f_ext"],
                    "image_name" => $file_row["f_hash"] . '.' . $file_row["f_ext"],
                    "thumb_name" => md5($file_row["f_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                ));

                if($thumb_image != false) {

                    echo "<tr>\n";
                    echo "<td width=\"6\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\" border=\"0\"></td>\n";
                    echo "<td width=\"13\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n<td width=\"";
                    echo "482\"><img src=\"img/leer.gif\" height=\"1\" width=\"6\">";
                    if($file_row['edit']) {
                        echo $file_row['edit'];
                    } else {
                        echo "<a href=\"fileinfo.php?public&amp;fid=";
                        echo $file_row["f_id"]."\" target=\"_blank\" onclick=\"flevPopupLink(this.href,'filedetail','scrollbars=";
                        echo "yes,resizable=yes,width=500,height=400',1); return document.MM_returnValue;\">";
                    }
                    echo '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" border="0" '.$thumb_image[3]."></a></td>\n";
                    echo "<td width=\"37\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\" border=\"0\"></td>\n</tr>\n";
                    echo "<tr><td colspan=\"4\"><img src=\"img/leer.gif\" height=\"2\" width=\"1\" border=\"0\"></td>\n</tr>\n";

                }

            }

            $file_durchlauf++;
        }
        if($file_durchlauf) { //Abschluss der Filelisten-Tabelle
            echo "</table>\n";
            echo "<tr bgcolor=\"#F5F8F9\"><td colspan=\"2\"><img src=\"img/leer.gif\" height=\"1\" width=\"1\"></td></tr>\n"; //Abstand vor
        } else {
            echo "<tr><td colspan=\"2\">";
            echo "<img src=\"img/leer.gif\" width=\"1\" height=\"6\"><br /><span class=\"error\" style=\"font-weight: bold;\">";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;".$BL['be_fsearch_nonfound'];
            echo "</span><br /><img src=\"img/leer.gif\" width=\"1\" height=\"10\"></td></tr>\n";
        }
    } //Ende Liste Dateien

    echo "</table>\n"; //Ende Tabelle

} elseif(isset($search["string"])) { //kein gültiges Suchergebnis
    echo "<img src=\"img/leer.gif\" width=\"1\" height=\"6\"><br /><span class=\"error\" style=\"font-weight: bold;\">";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;".$BL['be_fsearch_nonfound'];
    echo "</span><br /><img src=\"img/leer.gif\" width=\"1\" height=\"6\">";
} else {
    echo "<img src=\"img/leer.gif\" width=\"1\" height=\"6\"><br />";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;".$BL['be_fsearch_fillin'];
    echo "<br /><img src=\"img/leer.gif\" width=\"1\" height=\"6\">";
}