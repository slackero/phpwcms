<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

if(isset($_GET["open"])) {
    list($open_id, $open_value) = explode(":", $_GET["open"]);
    $_SESSION["fcatlist"][intval($open_id)] = intval($open_value);
}
?>

<h1><?php echo $BL['be_admin_fcat_title'] ?></h1>
<div class="card">
  <div class="card-header"><h2><i class="fa fa-list"></i> <?php echo $BL['be_cnt_title_overview'] ?></h2></div>
  <div class="card-body">

  <?php
  if(isset($_GET["fcatid"])) {
  ?>

  <?php
    $fcat["id"] = intval($_GET["fcatid"]);
    if($fcat["id"]) {
        $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_filecat WHERE fcat_id=".$fcat["id"]." LIMIT 1";
        $result = _dbQuery($sql);
        if(isset($result[0]['fcat_id'])) {
            $fcat["name"]   = $result[0]["fcat_name"];
            $fcat["active"] = $result[0]["fcat_aktiv"];
            $fcat["needed"] = $result[0]["fcat_needed"];
            $fcat["sort"]   = $result[0]["fcat_sort"];
        }
        $sendbutton = $BL['be_admin_fcat_button1'];
    } else {
        $sendbutton = $BL['be_admin_fcat_button2'];
    }

    if(isset($_POST["fcat_aktion"]) && intval($_POST["fcat_aktion"])) { //Formular zum Bearbeiten der Dateikategorie-Namen

        $fcat["name"]   = clean_slweg($_POST["fcat_name"], 250);
        $fcat["id"]     = intval($_POST["fcat_id"]);
        $fcat["active"] = empty($_POST["fcat_active"]) ? 0 : 1;
        $fcat["needed"] = empty($_POST["fcat_needed"]) ? 0 : 1;
        $fcat["sort"]   = empty($_POST["fcat_sort"]) ? 0 : intval($_POST["fcat_sort"]);

        if(empty($fcat["name"])) {
            $fcat["error"] = 1;
        } else {
            if(empty($fcat["id"])) {
                $query_mode = 'INSERT';
                $sql  = "INSERT INTO ".DB_PREPEND."cmsgo_filecat (fcat_name, fcat_aktiv, fcat_needed, fcat_sort) VALUES ('";
                $sql .= aporeplace($fcat["name"])."', ".$fcat["active"].", ".$fcat["needed"].", ".$fcat["sort"].")";
            } else {
                $query_mode = 'UPDATE';
                $sql  = "UPDATE ".DB_PREPEND."cmsgo_filecat SET fcat_name='".aporeplace($fcat["name"]);
                $sql .= "', fcat_aktiv=".$fcat["active"].", fcat_needed=".$fcat["needed"].", fcat_sort=".$fcat["sort"]." WHERE fcat_id=".$fcat["id"];
            }
            $result = _dbQuery($sql, $query_mode);

            if(isset($result['AFFECTED_ROWS'])) {

                if($query_mode === 'INSERT' && !empty($result['INSERT_ID'])) {
                    $fcat["id"] = $result['INSERT_ID'];
                }

                headerRedirect(CMSGO_URL.'cmsgo.php?'.get_token_get_string('csrftoken').'&do=admin&p=7');
            }
        }

    }

  ?>
  <form action="cmsgo.php?do=admin&amp;p=7&amp;fcatid=<?php echo $fcat["id"] ?>" method="post" name="filecategory" id="filecategory">

  <table border="0" cellpadding="0" cellspacing="0" summary="">
    <?php if(!empty($fcat["error"])) { ?>
    <tr>
      <td style="color:#FF3300;"><?php echo $BL['be_admin_usr_err'] ?>:&nbsp;</td>
      <td class="error"><strong><?php echo $BL['be_admin_fcat_err'] ?></strong></td>
    </tr>

    <?php } ?>
    <tr>
      <td><?php echo $BL['be_admin_fcat_name'] ?>:&nbsp;</td>
      <td><input name="fcat_name" type="text" id="fcat_name" class="form-control" value="<?php echo  empty($fcat["name"]) ? '' : html($fcat["name"]) ?>" size="40" maxlength="250" /></td>
    </tr>

    <tr>
      <td><?php echo $BL['be_cnt_sorting'] ?>:&nbsp;</td>
      <td><input name="fcat_sort" type="text" id="fcat_sort" class="form-control" value="<?php echo empty($fcat["sort"]) ? 0 : $fcat["sort"] ?>" size="10" maxlength="8" /></td>
    </tr>

    <tr>
      <td><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
      <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#D9DEE3" summary="">
        <tr>
          <td><input name="fcat_active" type="checkbox" id="fcat_active" value="1"<?php is_checked(1, empty($fcat["active"]) ? 0 : $fcat["active"]); ?> /></td>
          <td><label for="fcat_active"><?php echo $BL['be_ftptakeover_active'] ?></label>&nbsp;&nbsp;</td>
          <td><input name="fcat_needed" type="checkbox" id="fcat_needed" value="1"<?php is_checked(1, empty($fcat["needed"]) ? 0 : $fcat["needed"]); ?> /></td>
          <td><label for="fcat_needed"><?php echo $BL['be_admin_fcat_needed'] ?></label>&nbsp;&nbsp;</td>
        </tr>
      </table></td>
      </tr>

    <tr>
      <td></td>
      <td>
      <input name="Submit" type="submit" class="btn btn-blue btn-sm" value="<?php echo $sendbutton ?>" />
      &nbsp;&nbsp;
      <input name="donotsubmit" type="button" class="btn btn-blue btn-sm" value="<?php echo $BL['be_admin_fcat_exit'] ?>" onclick="location.href='cmsgo.php?do=admin&amp;p=7';" /></td>
      </tr>
  </table>
    <input name="fcat_id" type="hidden" id="fcat_id" value="<?php echo intval($fcat["id"]) ?>" />
    <input name="fcat_aktion" type="hidden" id="fcat_aktion" value="1" />
</form>
<?php
  } //Ende Anzeige Category Name Formular

  if(isset($_GET["fkeyid"])) { //Keyname
  ?>

  <?php
    $fkey["id"] = intval($_GET["fkeyid"]);
    $fkey["cid"] = intval($_GET["cid"]);
    if($fkey["id"]) {
        $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_filekey WHERE fkey_id=".$fkey["id"]." LIMIT 1";
        $result = _dbQuery($sql);
        if(isset($result[0]['fkey_id'])) {
            $fkey["name"]   = $result[0]["fkey_name"];
            $fkey["active"] = $result[0]["fkey_aktiv"];
            $fkey["cid"]    = $result[0]["fkey_cid"];
            $fkey["sort"]   = $result[0]["fkey_sort"];
        }
        $sendbutton = $BL['be_admin_fcat_button1'];
    } else {
        $sendbutton = $BL['be_admin_fcat_button2'];
    }

    if(!empty($_POST["fkey_aktion"])) { //Formular zum Bearbeiten der Dateischl�ssel-Namen

        $fkey["name"]   = clean_slweg($_POST["fkey_name"], 250);
        $fkey["id"]     = intval($_POST["fkey_id"]);
        $fkey["active"] = intval($_POST["fkey_active"]);
        $fkey["cid"]    = intval($_POST["fkey_cid"]);
        $fkey["sort"]   = empty($_POST["fkey_sort"]) ? 0 : intval($_POST["fkey_sort"]);

        if(empty($fkey["name"])) {

            $fkey["error"] = 1;

        } else {

            if(empty($fkey["id"])) {
                $query_mode = 'INSERT';
                $sql  = "INSERT INTO ".DB_PREPEND."cmsgo_filekey (fkey_name, fkey_aktiv, fkey_cid, fkey_sort) VALUES ('";
                $sql .= aporeplace($fkey["name"])."', ".$fkey["active"].", ".$fkey["cid"].", ".$fkey["sort"].")";
            } else {
                $query_mode = 'UPDATE';
                $sql  = "UPDATE ".DB_PREPEND."cmsgo_filekey SET fkey_name='".aporeplace($fkey["name"]);
                $sql .= "', fkey_aktiv=".$fkey["active"].", fkey_cid=".$fkey["cid"].", fkey_sort=".$fkey["sort"]." WHERE fkey_id=".$fkey["id"];
            }
            $result = _dbQuery($sql, $query_mode);

            if(isset($result['AFFECTED_ROWS'])) {
                if($query_mode === 'INSERT' && !empty($result['INSERT_ID'])) {
                    $fkey["id"] = $result['INSERT_ID'];
                }
                headerRedirect(CMSGO_URL.'cmsgo.php?'.get_token_get_string('csrftoken').'&do=admin&p=7');
            }
        }
    }
  ?>
  <form action="cmsgo.php?do=admin&amp;p=7&amp;fkeyid=<?php echo $fkey["id"]."&cid=".$fkey["cid"] ?>" method="post" name="filekey" id="filekey">
  <table border="0" cellpadding="0" cellspacing="0" summary="">
  <tr>
      <td><?php echo $BL['be_admin_fcat_fcat'] ?>:&nbsp;</td>
      <td><select name="fkey_cid" id="fkey_cid" class="custom-select form-control">
      <?php
        $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_filecat WHERE fcat_deleted=0 ORDER BY fcat_name";
        $result = _dbQuery($sql);
        if(isset($result[0]['fcat_id'])) {
            foreach($result as $row) {
                echo "<option value=\"".$row["fcat_id"]."\"".
                     (($row["fcat_id"]==$fkey["cid"])?" selected":"").
                     ">".html($row["fcat_name"])."</option>\n";
            }
        }
      ?>
        </select></td>
    </tr>

    <?php if(!empty($fkey["error"])) { ?>
    <tr>
      <td><span style="color:#FF3300"><?php echo $BL['be_admin_usr_err'] ?>:</span>&nbsp;</td>
      <td class="error"><strong><?php echo $BL['be_admin_fcat_err1']  ?></strong></td>
    </tr>

    <?php } ?>
    <tr>
      <td><?php echo $BL['be_admin_fcat_fkeyname'] ?>:&nbsp;</td>
      <td><input name="fkey_name" type="text" id="fkey_name" class="form-control" value="<?php echo html(empty($fkey["name"]) ? '' : $fkey["name"]) ?>" size="40" maxlength="250" /></td>
    </tr>

    <tr>
      <td><?php echo $BL['be_cnt_sorting'] ?>:&nbsp;</td>
      <td><input name="fkey_sort" type="text" id="fkey_sort" class="form-control" value="<?php echo empty($fkey["sort"]) ? 0 : $fkey["sort"] ?>" size="10" maxlength="8" /></td>
    </tr>

    <tr>
      <td><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
      <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#D9DEE3" summary="">
        <tr>
          <td><input name="fkey_active" type="checkbox" id="fkey_active" value="1"<?php is_checked(1, empty($fkey["active"]) ? 0 : $fkey["active"]); ?> /></td>
          <td><label for="fkey_active"><?php echo $BL['be_ftptakeover_active'] ?></label>&nbsp;&nbsp;</td>
        </tr>
      </table></td>
      </tr>

    <tr>
      <td>
      </td>
      <td>
      <input name="Submit" type="submit" class="btn btn-blue btn-sm" value="<?php echo $sendbutton ?>" />
      &nbsp;&nbsp;
      <input name="donotsubmit" type="button" class="btn btn-blue btn-sm" value="<?php echo $BL['be_admin_fcat_exit'] ?>" onclick="location.href='cmsgo.php?do=admin&amp;p=7';" /></td>
    </tr>
    </table>
      <input name="fkey_id" type="hidden" id="fkey_id" value="<?php echo intval($fkey["id"]) ?>" />
      <input name="fkey_aktion" type="hidden" id="fkey_aktion" value="1" />
  </form>
<?php
  } //Ende Anzeige Key Name Formular
  ?>

  <?php
    $sql = "SELECT * FROM ".DB_PREPEND."cmsgo_filecat WHERE fcat_deleted=0 ORDER BY fcat_sort, fcat_name";
    $result = _dbQuery($sql);
    if(isset($result[0]['fcat_id'])) {
        foreach($result as $row) {

            echo "<tr onmouseover=\"this.bgColor='#D2EED9';\" onMouseOut=\"this.bgColor='#FFFFFF';\">\n";
            echo "<td width=\"483\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n";

            $child_count = get_filecat_childcount($row["fcat_id"]);

            echo "<td>";
            echo ($child_count) ? "<a href=\"cmsgo.php?do=admin&p=7&open=".$row["fcat_id"].":".(empty($_SESSION["fcatlist"][$row["fcat_id"]])?1:0)."\">" : "";
            echo "<img src=\"img/symbole/plus_".(($child_count) ? (empty($_SESSION["fcatlist"][$row["fcat_id"]]) ? "open" : "close") : "empty");
            echo ".gif\" width=\"15\" height=\"15\" border=\"0\">".(($child_count) ? "</a>" : "")."</td>\n";
            echo "<td><strong".(($row["fcat_needed"])?" style=\"color:#FF3300\"":"").">".html($row["fcat_name"])."</strong> [".$row["fcat_sort"]."]</td>\n";
            echo "</tr>\n</table></td>".LF;

            echo '<td width="66" class="nowrap">';

            echo "<a href=\"cmsgo.php?do=admin&p=7&fkeyid=0&cid=".$row["fcat_id"]."\" title=\"".$BL['be_admin_fcat_addkey']."\">";
            echo "<img src=\"img/button/add_22x11.gif\" width=\"22\" height=\"11\" border=\"0\"></a>";

            echo "<a href=\"cmsgo.php?do=admin&p=7&fcatid=".$row["fcat_id"]."\" title =\"".$BL['be_admin_fcat_editcat']."\">";
            echo "<img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\" border=\"0\"></a>";

            echo "<a href=\"include/inc_act/act_filecat.php?do=1,".$row["fcat_id"].",".(($row["fcat_aktiv"])?0:1)."\" title =\"".$BL['be_fprivfunc_cactivefile']."\">";
            echo "<img src=\"img/button/active_11x11_".$row["fcat_aktiv"].".gif\" width=\"11\" height=\"11\" border=\"0\"></a>";

            echo "<a href=\"include/inc_act/act_filecat.php?do=8,".$row["fcat_id"]."\" title =\"".$BL['be_admin_fcat_delcat']."\" ";
            echo "onclick=\"return confirm('".$BL['be_admin_fcat_delcatmsg']."\\n[".html($row["fcat_name"])."] ');\">";
            echo "<img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\" border=\"0\"></a>";

            echo "</td>\n</tr>\n";


            if(isset($_SESSION["fcatlist"]) && isset($_SESSION["fcatlist"][$row["fcat_id"]]) && $_SESSION["fcatlist"][$row["fcat_id"]]) { //List key names for this categroy
                $ksql = "SELECT * FROM ".DB_PREPEND."cmsgo_filekey WHERE fkey_cid=".$row["fcat_id"]." AND fkey_deleted=0 ORDER BY fkey_sort, fkey_name";
                $kresult = _dbQuery($ksql);
                if(isset($kresult[0]['fcat_id'])) {
                    foreach($kresult as $krow) {
                        echo "<tr onMouseOver=\"this.bgColor='#CCFF00';\" onMouseOut=\"this.bgColor='#FFFFFF';\">\n";
                        echo "<td><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n";
                        echo "<td><img src=\"img/symbole/key_1.gif\" width=\"11\" height=\"15\"></td>\n";
                        echo "<td></td>\n";
                        echo "<td>".html($krow["fkey_name"])." [".$krow["fkey_sort"]."]</td>\n";
                        echo "</tr>\n</table></td>\n";
                        echo "<td><a href=\"cmsgo.php?do=admin&p=7&fkeyid=".$krow["fkey_id"]."&cid=".$row["fcat_id"]."\" title =\"".$BL['be_admin_fcat_editkey']."\">";
                        echo "<img src=\"img/button/edit_22x11.gif\" width=\"22\" height=\"11\" border=\"0\"></a>";
                        echo "<a href=\"include/inc_act/act_filecat.php?do=2,".$krow["fkey_id"].",".(($krow["fkey_aktiv"])?0:1)."\" title =\"".$BL['be_fprivfunc_cactivefile']."\">";
                        echo "<img src=\"img/button/active_11x11_".$krow["fkey_aktiv"].".gif\" width=\"11\" height=\"11\" border=\"0\"></a>";

                        echo "<a href=\"include/inc_act/act_filecat.php?do=9,".$krow["fkey_id"].",".($krow["fkey_cid"])."\" title =\"".$BL['be_admin_fcat_delkey']."\" ";
                        echo "onclick=\"return confirm('".$BL['be_admin_fcat_delmsg']."\\n[".html($krow["fkey_name"])."] ');\">";
                        echo "<img src=\"img/button/del_11x11.gif\" width=\"11\" height=\"11\" border=\"0\"></a>";
                        echo "</td>\n</tr>\n";
                    }
                }
            } //Ende List Keynames
        }
    }
?>

<form action="cmsgo.php?do=admin&amp;p=7&amp;fcatid=0" method="post">
                <input type="submit" value="<?php echo $BL['be_admin_fcat_addcat'] ?>" class="btn btn-blue btn-sm" title="<?php echo $BL['be_admin_fcat_addcat'] ?>" />
</form>
</div>
</div>
</div>
