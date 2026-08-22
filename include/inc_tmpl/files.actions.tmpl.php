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

$file_action = array(
    'file_dir' => empty($_POST['file_dir']) ? 0 : intval($_POST['file_dir']),
    'mark' => empty($_POST['ftp_mark']) || !is_array($_POST['ftp_mark']) ? array() : $_POST['ftp_mark'],
    'newdir' => empty($_POST['file_newdir']) ? 0 : intval($_POST['file_newdir'])
);

//Get post variables
if(isset($_POST['file_action'])) {
    $file_action['action']  = intval($_POST['file_action']);
    $file_action_msg        = $BL['file_actions_msg_error'];
} else {
    $file_action['action']  = 0;
    $file_action_msg        = '';
}

if($file_action['action'] === 1 && $file_action["mark"]) {
    if(is_array($file_action["mark"]) && count($file_action["mark"])) {
        foreach($file_action["mark"] as $key => $value) {
            $key = intval($key);
            if($key) {
                $sql = 'UPDATE '.DB_PREPEND.'phpwcms_file SET f_trash=1 WHERE f_id='.$key;
                @_dbQuery($sql, 'UPDATE');
            } else {
                unset($key);
            }
        }
        $file_action_msg = $BL['file_actions_msg_delete'];
    }
} elseif($file_action['action'] === 2 && $file_action["mark"]) {
    $newdir = intval($file_action["newdir"]);
    if(is_array($file_action["mark"]) && count($file_action["mark"])) {
        foreach($file_action["mark"] as $key => $value) {
            $key = intval($key);
            if($key) {
                $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_file SET f_pid='.$newdir.' WHERE f_id='.$key;
                @_dbQuery($sql, 'UPDATE');
            } else {
                unset($key);
            }
        }
        $file_action_msg = $BL['file_actions_msg_move'];
    }
} elseif($file_action['action'] === 3 && $file_action["mark"]) {
    $file_action["aktiv"]   = empty($_POST["file_aktiv"]) ? 0 : 1;
    $file_action["public"]  = empty($_POST["file_public"]) ? 0 : 1;
    if(is_array($file_action["mark"]) && count($file_action["mark"])) {
        foreach($file_action["mark"] as $key => $value) {
            $key = intval($key);
            if($key) {
                $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_file SET ';
                $sql .= "f_aktiv= " . $file_action["aktiv"] . ", ";
                $sql .= "f_public= " . $file_action["public"] . " ";
                $sql .= "WHERE f_id='".$key."'";
                @_dbQuery($sql, 'UPDATE');
            } else {
                unset($key);
            }
        }
        $file_action_msg = $BL['file_actions_msg_status'];
    }
} elseif($file_action['action'] === 4 && $file_action["mark"]) {
    $file_action["file_user"]   = intval($_POST["file_user"]);
    if(is_array($file_action["mark"]) && count($file_action["mark"]) && $file_action["file_user"] > 0) {
        foreach($file_action["mark"] as $key => $value) {
            $key = intval($key);
            if($key) {
                $sql  = 'UPDATE '.DB_PREPEND.'phpwcms_file SET ';
                $sql .= "f_pid=0, ";
                $sql .= "f_uid= " . $file_action["file_user"] . " ";
                $sql .= "WHERE f_id='".$key."'";
                @_dbQuery($sql, 'UPDATE');
            } else {
                unset($key);
            }
        }
        $file_action_msg = $BL['file_actions_msg_user'];
    }
}


?>
<script type=text/javascript>
function showAction() {
    divid = document.filetakeover.file_action.value;
    if (divid === '0') {
        document.getElementById("div_button").style.display='none';
        document.getElementById("div_status").style.display='none';
        document.getElementById("div_folder").style.display='none';
        document.getElementById("div_user").style.display='none';
    } else if (divid === '1') {
        document.getElementById("div_button").style.display='block';
        document.getElementById("div_status").style.display='none';
        document.getElementById("div_folder").style.display='none';
        document.getElementById("div_user").style.display='none';
    } else if (divid === '2') {
        document.getElementById("div_button").style.display='block';
        document.getElementById("div_status").style.display='none';
        document.getElementById("div_folder").style.display='block';
        document.getElementById("div_user").style.display='none';
    } else if (divid === '3') {
        document.getElementById("div_button").style.display='block';
        document.getElementById("div_status").style.display='block';
        document.getElementById("div_folder").style.display='none';
        document.getElementById("div_user").style.display='none';
    } else if (divid === '4') {
        document.getElementById("div_button").style.display='block';
        document.getElementById("div_status").style.display='none';
        document.getElementById("div_folder").style.display='none';
        document.getElementById("div_user").style.display='block';
    }
}
</script>

<h1 class="text-center text-sm-left"><?php echo $BL['be_nav_files'] ?></h1>

<div class="card">
  <div class="card-header"><h2><?php echo $BL['be_subnav_file_actions'] ?></h2></div>
  <div class="card-body">
    <?php if($file_action_msg) { echo '<div class="alert alert-success">'.$file_action_msg.'</div>'; } ?>

    <div class="card">
      <div class="card-body">
        <form action="phpwcms.php?do=files&amp;p=4" method="post" name="folderform" id="folderform">
            <legend><?php echo $BL['file_actions_step1'] ?></legend>
            <select name="file_dir" id="file_dir" class="custom-select form-control form-control-sm col-sm-4" onchange="submit();">
                <option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
                <?php //get folders for user
                    dir_menu(0, $file_action["file_dir"], "+", $_SESSION["wcs_user_id"], "+");
                ?>
            </select>
        </form>
      </div>
    </div>

    <form action="phpwcms.php?do=files&amp;p=4" method="post" name="filetakeover" id="filetakeover" class="mt-3">
    <input name="file_dir" type="hidden" value="<?php echo $file_action["file_dir"] ?>" />
    <div class="card">
      <div class="card-body">
        <legend><?php echo $BL['file_actions_step2'] ?></legend>
        <div class="table-responsive">
        <table class="table table-sm table-valign-middle">
          <tr bgcolor="#e3e3e3">
              <th width="35"><?php echo $BL['be_ftptakeover_mark'] ?></th>
              <th><?php echo $BL['be_ftptakeover_available'] ?></th>
              <th class="text-right"><?php echo $BL['be_ftptakeover_status'] ?>&nbsp;&nbsp;</th>
          </tr>
        <?php
        //Browse files in selected folder
        $fx = 0;
        $file_sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_pid=" . $file_action["file_dir"] .
                    " AND f_trash=0 AND f_kid = 1 AND f_uid = " . $_SESSION["wcs_user_id"] . " ORDER BY f_name";
        $file_result = _dbQuery($file_sql);
        if(isset($file_result[0]['f_id'])) {

            foreach($file_result as $file_row) {
                $fxb = ($fx % 2) ? " bgColor=\"#F9FAFB\"" : "";
                $filename = PHPWCMS_CHARSET !== 'utf-8' && phpwcms_seems_utf8($file_row["f_name"]) ? makeCharsetConversion($file_row["f_name"], 'utf-8', PHPWCMS_CHARSET) : $file_row["f_name"];
                $filename = html($filename);
        ?>
          <tr<?php echo $fxb ?>>
            <td align="center"><input name="ftp_mark[<?php echo $file_row["f_id"] ?>]" type="checkbox" id="ftp_mark_<?php echo $file_row["f_id"] ?>" value="1" class="ftp_mark" /></td>
            <td><i class="fa fa-file-image mr-2"></i> <?php echo $filename ?></td>
            <td class="text-right text-nowrap">
                <?php
                //Icons Public/Non-Public
                echo '<div class="btn fa btn-sm visible '.($file_row["f_aktiv"]==0 ? "btn-danger" : "btn-success").' mr-1 disabled"></div>';
                echo '<div class="btn fa btn-sm public '.($file_row["f_public"]==0 ? "btn-danger" : "btn-success").' disabled"></div>';
                 ?>&nbsp;
                <input name="ftp_fileid[<?php echo $fx ?>]" type="hidden" value="<?php echo $file_row["f_id"] ?>" />
            </td>
          </tr>
        <?php
                $fx++;
            }
        }
        if(!$fx) {
        ?>
          <tr>
            <td colspan="3">&nbsp;<?php echo $BL['file_actions_no'] ?></td>
          </tr>
        <?php } else { ?>
          <tr bgcolor="#e3e3e3">
            <td class="subnavactive text-center"><input name="toggle" type="checkbox" id="toggle" value="1" title="<?php echo $BL['be_ftptakeover_all'] ?>" /></td>
            <td colspan="2"><?php echo $BL['be_ftptakeover_all'] ?></td>
          </tr>
        <?php } ?>
        </table>
        </div>
      </div>
    </div>

    <?php
    //if files available
    if($fx) {
    ?>

    <div class="card mt-3">
      <div class="card-body">
        <legend><?php echo $BL['file_actions_step3'] ?></legend>

        <div id="div_folder" style="display: none;">
          <div class="form-group form-row align-items-center">
          	<div class="col-12 mb-3"><?php echo $BL['file_actions_bemfolder']; ?></div>
						<label for="file_newdir" class="col-form-label text-right"><?php echo $BL['be_ftptakeover_directory'] ?></label>
						<div class="col-sm-auto">
							<select name="file_newdir" id="file_newdir" class="custom-select form-control form-control-sm">
								<option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
								<?php dir_menu(0, 0, "+", $_SESSION["wcs_user_id"], "+"); ?>
							</select>
						</div>
					</div>
        </div>

        <div id="div_status" style="display: none;">
          <div class="form-group form-row align-items-center">
						<label class="col-form-label text-right"><?php echo $BL['be_ftptakeover_status'] ?></label>
						<div class="col-sm-auto">
							<div class="form-check form-check-inline">
								<input class="form-check-input" name="file_aktiv" type="checkbox" id="file_aktiv" value="1" />
								<label class="form-check-label" for="file_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" name="file_public" type="checkbox" id="file_public" value="1" />
								<label class="form-check-label" for="file_public"><?php echo $BL['be_ftptakeover_public'] ?></label>
							</div>
						</div>
					</div>
        </div>

        <div id="div_user" style="display: none;">
        	<div class="form-group form-row align-items-center">
          	<div class="col-12 mb-3"><?php echo $BL['file_actions_bemuser']; ?></div>
						<label for="file_user" class="col-form-label text-right"><?php echo $BL["login_username"] ?></label>
						<div class="col">
							<select name="file_user" id="file_user" class="custom-select form-control form-control-sm col-sm-4">
              <?php
                $sql = "SELECT usr_id, usr_name FROM ".DB_PREPEND."phpwcms_user WHERE usr_aktiv=1 AND usr_id !=".intval($_SESSION["wcs_user_id"])." ORDER BY usr_name";
                $result = _dbQuery($sql);
                if(isset($result[0]['usr_id'])) {
                  foreach($result as $row) {
                    echo "<option value='".$row['usr_id']."'>".html($row['usr_name'])."</option>\n";
                  }
                }
              ?>
              </select>
						</div>
					</div>
        </div>

				<div class="form-group align-items-center form-row mt-3">
					<div class="col-sm-4">
						<select name="file_action" id="file_action" class="custom-select form-control form-control-sm" onChange="showAction()">
							<option value="0">- <?php echo $BL['file_actions_pdl_empty'] ?> -</option>
							<option value="1"><?php echo $BL['file_actions_pdl_delete'] ?></option>
							<option value="2"><?php echo $BL['file_actions_pdl_move'] ?></option>
							<option value="3"><?php echo $BL['file_actions_pdl_status'] ?></option>
							<option value="4"><?php echo $BL['file_actions_pdl_user'] ?></option>
						</select>
					</div>
					<div class="col-sm-auto">
						<div id="div_button" style="display: none;"><button name="Submit" type="submit" class="btn btn-blue btn-sm ml-2" value="1"><i class="fa fa-cogs mr-1"></i> <?php echo $BL['file_actions_button'] ?></button></div>
          </div>
        </div>

    <?php } ?>
      </div>
    </div>
    </form>
  </div>
</div>
<?php if($fx) { ?>
<script type="text/javascript">

$('#toggle').change(function () {
  $('input:checkbox').prop('checked', this.checked);
});
</script>
<?php } ?>
