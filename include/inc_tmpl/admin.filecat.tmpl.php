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

if(isset($_GET['open'])) {
    list($open_id, $open_value) = explode(':', $_GET['open']);
    $_SESSION['fcatlist'][intval($open_id)] = intval($open_value);
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
        $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_id=".$fcat["id"]." LIMIT 1";
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
                $sql  = "INSERT INTO ".DB_PREPEND."phpwcms_filecat (fcat_name, fcat_aktiv, fcat_needed, fcat_sort) VALUES ('";
                $sql .= aporeplace($fcat["name"])."', ".$fcat["active"].", ".$fcat["needed"].", ".$fcat["sort"].")";
            } else {
                $query_mode = 'UPDATE';
                $sql  = "UPDATE ".DB_PREPEND."phpwcms_filecat SET fcat_name='".aporeplace($fcat["name"]);
                $sql .= "', fcat_aktiv=".$fcat["active"].", fcat_needed=".$fcat["needed"].", fcat_sort=".$fcat["sort"]." WHERE fcat_id=".$fcat["id"];
            }
            $result = _dbQuery($sql, $query_mode);

            if(isset($result['AFFECTED_ROWS'])) {

                if($query_mode === 'INSERT' && !empty($result['INSERT_ID'])) {
                    $fcat["id"] = $result['INSERT_ID'];
                }

                headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=admin&p=7');
            }
        }

    }

  ?>
  <form action="phpwcms.php?do=admin&amp;p=7&amp;fcatid=<?php echo $fcat["id"] ?>" method="post" name="filecategory" id="filecategory" class="mb-4">
    <div class="card bg-light mb-4">
        <div class="card-body">
            <?php if(!empty($fcat["error"])) { ?>
                <div class="alert alert-danger mb-3">
                    <strong><?php echo $BL['be_admin_usr_err'] ?>:</strong> <?php echo $BL['be_admin_fcat_err'] ?>
                </div>
            <?php } ?>

            <div class="form-group row">
                <label for="fcat_name" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_admin_fcat_name'] ?>:</label>
                <div class="col-sm-6">
                    <input name="fcat_name" type="text" id="fcat_name" class="form-control form-control-sm" value="<?php echo empty($fcat["name"]) ? '' : html($fcat["name"]) ?>" maxlength="250" />
                </div>
            </div>

            <div class="form-group row">
                <label for="fcat_sort" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_cnt_sorting'] ?>:</label>
                <div class="col-sm-3">
                    <input name="fcat_sort" type="number" id="fcat_sort" class="form-control form-control-sm" value="<?php echo empty($fcat["sort"]) ? 0 : $fcat["sort"] ?>" />
                </div>
            </div>

            <div class="form-group row">
                <span class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BL['be_ftptakeover_status'] ?>:</span>
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="form-check me-4">
                        <input class="form-check-input" name="fcat_active" type="checkbox" id="fcat_active" value="1"<?php is_checked(1, empty($fcat["active"]) ? 0 : $fcat["active"]); ?> />
                        <label for="fcat_active" class="form-check-label"><?php echo $BL['be_ftptakeover_active'] ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="fcat_needed" type="checkbox" id="fcat_needed" value="1"<?php is_checked(1, empty($fcat["needed"]) ? 0 : $fcat["needed"]); ?> />
                        <label for="fcat_needed" class="form-check-label"><?php echo $BL['be_admin_fcat_needed'] ?></label>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-sm-9 offset-sm-3">
                    <button name="Submit" type="submit" class="btn btn-blue btn-sm fw-bold" value="1"><i class="fa fa-rotate me-1"></i><?php echo $sendbutton ?></button>
                    <a href="phpwcms.php?do=admin&amp;p=7" class="btn btn-danger btn-sm ms-3"><i class="fa fa-times me-1"></i><?php echo $BL['be_admin_fcat_exit'] ?></a>
                </div>
            </div>
        </div>
    </div>
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
        $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_id=".$fkey["id"]." LIMIT 1";
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

    if(!empty($_POST["fkey_aktion"])) { //Formular zum Bearbeiten der Dateischlssel-Namen

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
                $sql  = "INSERT INTO ".DB_PREPEND."phpwcms_filekey (fkey_name, fkey_aktiv, fkey_cid, fkey_sort) VALUES ('";
                $sql .= aporeplace($fkey["name"])."', ".$fkey["active"].", ".$fkey["cid"].", ".$fkey["sort"].")";
            } else {
                $query_mode = 'UPDATE';
                $sql  = "UPDATE ".DB_PREPEND."phpwcms_filekey SET fkey_name='".aporeplace($fkey["name"]);
                $sql .= "', fkey_aktiv=".$fkey["active"].", fkey_cid=".$fkey["cid"].", fkey_sort=".$fkey["sort"]." WHERE fkey_id=".$fkey["id"];
            }
            $result = _dbQuery($sql, $query_mode);

            if(isset($result['AFFECTED_ROWS'])) {
                if($query_mode === 'INSERT' && !empty($result['INSERT_ID'])) {
                    $fkey["id"] = $result['INSERT_ID'];
                }
                headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=admin&p=7');
            }
        }
    }
  ?>
  <form action="phpwcms.php?do=admin&amp;p=7&amp;fkeyid=<?php echo $fkey["id"]."&cid=".$fkey["cid"] ?>" method="post" name="filekey" id="filekey" class="mb-4">
    <div class="card bg-light mb-4">
        <div class="card-body">
            <?php if(!empty($fkey["error"])) { ?>
                <div class="alert alert-danger mb-3">
                    <strong><?php echo $BL['be_admin_usr_err'] ?>:</strong> <?php echo $BL['be_admin_fcat_err1'] ?>
                </div>
            <?php } ?>

            <div class="form-group row">
                <label for="fkey_cid" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_admin_fcat_fcat'] ?>:</label>
                <div class="col-sm-6">
                    <select name="fkey_cid" id="fkey_cid" class="form-select form-select-sm">
                    <?php
                    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_deleted=0 ORDER BY fcat_name";
                    $result = _dbQuery($sql);
                    if (isset($result[0]['fcat_id'])) {
                        foreach ($result as $row) {
                            echo "<option value=\"" . $row["fcat_id"] . "\"" .
                                (($row["fcat_id"] == $fkey["cid"]) ? " selected" : "") .
                                ">" . html($row["fcat_name"]) . "</option>\n";
                        }
                    }
                    ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="fkey_name" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_admin_fcat_fkeyname'] ?>:</label>
                <div class="col-sm-6">
                    <input name="fkey_name" type="text" id="fkey_name" class="form-control form-control-sm" value="<?php echo html(empty($fkey["name"]) ? '' : $fkey["name"]) ?>" maxlength="250" />
                </div>
            </div>

            <div class="form-group row">
                <label for="fkey_sort" class="col-sm-3 col-form-label text-sm-end"><?php echo $BL['be_cnt_sorting'] ?>:</label>
                <div class="col-sm-3">
                    <input name="fkey_sort" type="number" id="fkey_sort" class="form-control form-control-sm" value="<?php echo empty($fkey["sort"]) ? 0 : $fkey["sort"] ?>" />
                </div>
            </div>

            <div class="form-group row">
                <span class="col-sm-3 col-form-label text-sm-end fw-bold"><?php echo $BL['be_ftptakeover_status'] ?>:</span>
                <div class="col-sm-6 d-flex align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" name="fkey_active" type="checkbox" id="fkey_active" value="1"<?php is_checked(1, empty($fkey["active"]) ? 0 : $fkey["active"]); ?> />
                        <label for="fkey_active" class="form-check-label"><?php echo $BL['be_ftptakeover_active'] ?></label>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-sm-9 offset-sm-3">
                    <button name="Submit" type="submit" class="btn btn-blue btn-sm fw-bold" value="1"><i class="fa fa-rotate me-1"></i><?php echo $sendbutton ?></button>
                    <a href="phpwcms.php?do=admin&amp;p=7" class="btn btn-danger btn-sm ms-3"><i class="fa fa-times me-1"></i><?php echo $BL['be_admin_fcat_exit'] ?></a>
                </div>
            </div>
        </div>
    </div>
    <input name="fkey_id" type="hidden" id="fkey_id" value="<?php echo intval($fkey["id"]) ?>" />
    <input name="fkey_aktion" type="hidden" id="fkey_aktion" value="1" />
  </form>
<?php
  } //Ende Anzeige Key Name Formular

  $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_deleted=0 ORDER BY fcat_sort, fcat_name";
  $result = _dbQuery($sql);
  if(isset($result[0]['fcat_id'])) {
      echo '<div class="table-responsive mb-4">';
      echo '<table class="table table-sm table-hover table-valign-middle mb-0">';
      echo '<thead class="thead-light"><tr><th>Category / Key</th><th class="text-end">Actions</th></tr></thead><tbody>';

      foreach($result as $row) {

          $child_count = get_filecat_childcount($row["fcat_id"]);

          echo "<tr class=\"table-secondary fw-bold\">\n";
          echo "<td>";
          echo ($child_count) ? "<a href=\"phpwcms.php?do=admin&p=7&open=".$row["fcat_id"].":".(empty($_SESSION["fcatlist"][$row["fcat_id"]])?1:0)."\">" : "";
          echo "<i class=\"fa fa-fw fa-caret-".(($child_count) ? (empty($_SESSION["fcatlist"][$row["fcat_id"]]) ? "right" : "down") : "right")." text-muted me-1\"></i>".(($child_count) ? "</a>" : "");
          echo "<span".(($row["fcat_needed"])?" class=\"text-danger\"":"").">".html($row["fcat_name"])."</span> <span class=\"badge badge-light border ms-1\">".$row["fcat_sort"]."</span></td>\n";

          echo '<td class="text-end text-nowrap">';
          echo '<div class="btn-group btn-group-sm" role="group" aria-label="fcat-actions-'.$row["fcat_id"].'">';

          echo "<a href=\"phpwcms.php?do=admin&p=7&fkeyid=0&cid=".$row["fcat_id"]."\" class=\"btn btn-sm btn-blue\" title=\"".$BL['be_admin_fcat_addkey']."\">";
          echo "<i class=\"fa fa-plus\"></i></a>";

          echo "<a href=\"phpwcms.php?do=admin&p=7&fcatid=".$row["fcat_id"]."\" class=\"btn btn-sm btn-blue\" title=\"".$BL['be_admin_fcat_editcat']."\">";
          echo "<i class=\"fa fa-pencil-alt\"></i></a>";

          echo "<a href=\"include/inc_act/act_filecat.php?do=1,".$row["fcat_id"].",".(($row["fcat_aktiv"])?0:1)."\" class=\"btn btn-sm ".($row["fcat_aktiv"] ? 'btn-success' : 'btn-warning')."\" title=\"".$BL['be_fprivfunc_cactivefile']."\">";
          echo "<i class=\"fas ".($row["fcat_aktiv"] ? 'fa-eye' : 'fa-eye-slash')."\"></i></a>";
          echo '</div>';

          echo "<a href=\"include/inc_act/act_filecat.php?do=8,".$row["fcat_id"]."\" class=\"btn btn-sm btn-danger ms-1 confirm-link\" data-confirm=\"".$BL['be_admin_fcat_delcatmsg']." [".html($row["fcat_name"])."]\" title=\"".$BL['be_admin_fcat_delcat']."\">";
          echo "<i class=\"far fa-trash-alt\"></i></a>";

          echo "</td>\n</tr>\n";


          if(!empty($_SESSION["fcatlist"][$row["fcat_id"]])) { //List key names for this categroy
              $ksql = "SELECT * FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_cid=".$row['fcat_id']." AND fkey_deleted=0 ORDER BY fkey_sort, fkey_name";
              $kresult = _dbQuery($ksql);
              if(isset($kresult[0]['fkey_id'])) {
                  foreach($kresult as $krow) {
                      echo "<tr>\n";
                      echo "<td class=\"ps-4\"><i class=\"fa fa-key text-muted me-2\"></i>".html($krow['fkey_name'])." <span class=\"badge badge-light border ms-1\">".$krow['fkey_sort']."</span></td>\n";
                      echo "<td class=\"text-end text-nowrap\">";
                      echo '<div class="btn-group btn-group-sm" role="group" aria-label="fkey-actions-'.$krow['fkey_id'].'">';
                      echo "<a href=\"phpwcms.php?do=admin&p=7&fkeyid=".$krow['fkey_id']."&cid=".$row['fcat_id']."\" class=\"btn btn-sm btn-blue\" title=\"".$BL['be_admin_fcat_editkey']."\">";
                      echo "<i class=\"fa fa-pencil-alt\"></i></a>";
                      echo "<a href=\"include/inc_act/act_filecat.php?do=2,".$krow['fkey_id'].",".(($krow['fkey_aktiv'])?0:1)."\" class=\"btn btn-sm ".($krow['fkey_aktiv'] ? 'btn-success' : 'btn-warning')."\" title=\"".$BL['be_fprivfunc_cactivefile']."\">";
                      echo "<i class=\"fas ".($krow['fkey_aktiv'] ? 'fa-eye' : 'fa-eye-slash')."\"></i></a>";
                      echo '</div>';

                      echo "<a href=\"include/inc_act/act_filecat.php?do=9,".$krow['fkey_id'].",".($krow['fkey_cid'])."\" class=\"btn btn-sm btn-danger ms-1 confirm-link\" data-confirm=\"".$BL['be_admin_fcat_delmsg']." [".html($krow['fkey_name'])."]\" title=\"".$BL['be_admin_fcat_delkey']."\">";
                      echo "<i class=\"far fa-trash-alt\"></i></a>";
                      echo "</td>\n</tr>\n";
                  }
              }
          } //Ende List Keynames
      }

      echo '</tbody></table></div>';
  }
?>

    <a href="phpwcms.php?do=admin&amp;p=7&amp;fcatid=0" class="btn btn-blue btn-sm fw-bold" title="<?php echo $BL['be_admin_fcat_addcat'] ?>">
        <i class="fa fa-plus me-1"></i><?php echo $BL['be_admin_fcat_addcat'] ?>
    </a>
</div>
</div>
