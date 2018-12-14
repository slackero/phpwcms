<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
  die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

//Wenn neues Verzeichnis angelegt werden soll

$dir_aktiv    = $cmsgo['set_file_active'];
$dir_public   = $cmsgo['set_file_active'];
$dir_newname  = '';
$dir_longinfo = '';
$dir_gallery  = 0;
$dir_pid    = empty($_GET["mkdir"]) ? 0 : intval($_GET["mkdir"]);
$dir_sort   = 0;

//Auswerten des Formulars
if(isset($_POST["dir_aktion"]) && intval($_POST["dir_aktion"]) == 1) {
  $dir_pid    = intval($_POST["dir_pid"]);
  $dir_aktiv    = empty($_POST["dir_aktiv"]) ? 0 : 1;
  $dir_public   = empty($_POST["dir_public"]) ? 0 : 1;
  $dir_newname  = clean_slweg($_POST["dir_newname"]);
  $dir_longinfo = clean_slweg($_POST["dir_longinfo"]);
  $dir_gallery  = empty($_POST["dir_gallery"]) ? 0 : intval($_POST["dir_gallery"]);
  $dir_sort   = intval($_POST["dir_sort"]);

  switch($dir_gallery) {

    case 2:
    case 3: break;

    default: $dir_gallery = 0;

  }

  if(str_empty($dir_newname)) $dir_error = 1;
  //Eintragen des neuen verzeichnisnamens
  if(!isset($dir_error)) {
    $sql =  "INSERT INTO ".DB_PREPEND."cmsgo_file (f_pid, f_uid, f_name, f_aktiv, f_public, ".
        "f_created, f_kid, f_longinfo, f_gallerystatus, f_sort) VALUES (".
        $dir_pid.", ".
        $_SESSION["wcs_user_id"].", '".
        aporeplace($dir_newname)."', ".
        $dir_aktiv.", ".
        $dir_public.", '".
        time()."', 0, '".aporeplace($dir_longinfo)."', ".$dir_gallery.", ".
        $dir_sort.")";
        $result = _dbQuery($sql, 'INSERT');
    if(!empty($result['INSERT_ID'])) {
      headerRedirect(CMSGO_URL.'cmsgo.php?'.get_token_get_string('csrftoken').'&do=files&f=0');
    }
  }
}
//Ende Auswerten Formular

//Wenn ID angegeben, dann -> oder aber Root Verzeichnis
if($dir_pid) {
  $sql  = "SELECT f.f_id, f.f_name, f.f_uid, u.usr_login FROM ".DB_PREPEND."cmsgo_file f ";
  $sql .= "LEFT JOIN ".DB_PREPEND."cmsgo_user u ON u.usr_id=f.f_uid WHERE f.f_id=".$dir_pid;
  if(empty($_SESSION["wcs_user_admin"])) {
    $sql .= " AND f.f_uid=".$_SESSION["wcs_user_id"];
  }
  $sql .= " AND f.f_trash=0 AND f.f_kid=0 LIMIT 1";
  $result = _dbQuery($sql);
  if(isset($result[0]['f_id'])) {
    $dir_parent_name  = html($result[0]['f_name']);
    $dir_pid      = intval($result[0]['f_id']);
    if($_SESSION["wcs_user_id"] != $result[0]['f_uid']) {
      $dir_parent_name .= ' (' . html($result[0]['usr_login']) . ')';
    }
  } else {
    $dir_parent_name = $BL['be_fpriv_rootdir'];
    $dir_pid     = 0;
  }
} else {
  $dir_parent_name = $BL['be_fpriv_rootdir'];
  $dir_pid     = 0;
}

?>

<h2><?php echo $BL['be_fpriv_title'] ?></h2>

<form action="cmsgo.php?do=files&amp;f=0" method="post" name="createnewdir" id="createnewdir">


  <div class="form-group align-items-center form-row">
    <label for="dir_aktiv" class="col-sm-2 col-form-label text-right"></label>
    <div class="col-sm-auto">
      <strong><?php echo $BL['be_fpriv_inside'] ?> <?php echo $dir_parent_name ?></strong>
    </div>
  </div>

  <?php if(isset($dir_error)) { ?>
  <strong style="color:#cc0000;"><?php echo $BL['be_fpriv_error'] ?></strong>
  <?php } ?>

  <div class="form-group align-items-center form-row">
    <label for="dir_aktiv" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_fpriv_name'] ?></label>
    <div class="col-sm-4">
      <input name="dir_newname" type="text" class="form-control form-control-sm" id="dir_newname" value="<?php echo html($dir_newname) ?>"" maxlength="250" />
    </div>
  </div>

  <div class="form-group form-row">
    <label for="dir_aktiv" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_longinfo'] ?></label>
    <div class="col-sm-4">
      <textarea name="dir_longinfo" cols="40" rows="4" class="form-control form-control-sm" id="dir_longinfo"><?php echo html($dir_longinfo) ?></textarea>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="dir_aktiv" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_gallery'] ?></label>
    <div class="col-sm-4">
    <select name="dir_gallery" id="dir_gallery" class="custom-select form-control form-control-sm">
      <option value="0"<?php is_selected(0, $dir_gallery) ?>>-</option>
      <option value="2"<?php is_selected(2, $dir_gallery) ?>><?php echo $BL['be_gallery_root'] ?></option>
      <option value="3"<?php is_selected(3, $dir_gallery) ?>><?php echo $BL['be_gallery_directory'] ?></option>
    </select>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="dir_aktiv" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_sorting'] ?></label>
    <div class="col-sm-auto">
      <input name="dir_sort" type="text" id="dir_sort" class="form-control form-control-sm" maxlength="10" value="<?php echo intval($dir_sort) ?>" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="dir_aktiv" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_fpriv_status'] ?></label>
    <div class="col-sm-auto">
    	<div class="form-check form-check-inline">
				<input class="form-check-input" name="dir_aktiv" type="checkbox" id="dir_aktiv" value="1"<?php is_checked("1", $dir_aktiv) ?> />
				<label class="form-check-label" for="dir_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label>
      </div>
      <div class="form-check form-check-inline">
				<input class="form-check-input" name="dir_public" type="checkbox" id="dir_public" value="1"<?php is_checked("1", $dir_public) ?> />
				<label class="form-check-label" for="dir_public"><?php echo $BL['be_ftptakeover_public'] ?></label>
      </div>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
      <input name="Submit" type="submit" class="btn btn-blue btn-sm" value="<?php echo $BL['be_fpriv_button'] ?>" />
      <input type="button" class="btn btn-blue btn-sm" value="<?php echo $BL['be_func_struct_close'] ?>" onclick="document.location.href='cmsgo.php?do=files&amp;f=0'" />
    </div>
  </div>

  <input name="dir_pid" type="hidden" id="dir_pid" value="<?php echo $dir_pid ?>" />
  <input name="dir_aktion" type="hidden" id="dir_aktion" value="1" />
</form>
