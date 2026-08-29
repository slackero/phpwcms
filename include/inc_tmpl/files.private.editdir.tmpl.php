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

$dir_error = 0;

//Auswerten des Formulars
if(isset($_POST["dir_aktion"]) && intval($_POST["dir_aktion"]) == 2) {
    $dir_id         = abs(intval($_POST["dir_id"]));
    $dir_aktiv      = empty($_POST["dir_aktiv"]) ? 0 : 1;
    $dir_public     = empty($_POST["dir_public"]) ? 0 : 1;
    $dir_newname    = clean_slweg($_POST["dir_newname"]);
    $dir_longinfo   = clean_slweg($_POST["dir_longinfo"]);
    $dir_gallery    = empty($_POST["dir_gallery"]) ? 0 : intval($_POST["dir_gallery"]);
    $dir_sort       = intval($_POST["dir_sort"]);
    $dir_pid        = abs(intval($_POST['dir_pid']));

    switch($dir_gallery) {

        case 2:
        case 3: break;

        default: $dir_gallery = 0;

    }

    if($dir_id == $dir_pid) {
        $dir_error += 2;
    }
    if(empty($dir_newname)) {
        $dir_error += 1;
    }

    //Eintragen der aktualisierten Verzeichnisinfos
    if(empty($dir_error)) {
        $sql =  "UPDATE ".DB_PREPEND."phpwcms_file SET ".
                'f_pid='.$dir_pid.', '.
                "f_name='".aporeplace($dir_newname)."', ".
                "f_aktiv=".$dir_aktiv.", ".
                "f_public=".$dir_public.", ".
                "f_longinfo='".aporeplace($dir_longinfo)."', ".
                "f_created='".time()."', ".
                "f_gallerystatus=".$dir_gallery.", ".
                'f_sort='.$dir_sort.' '.
                "WHERE f_kid=0 AND f_id=".$dir_id;
                if(empty($_SESSION["wcs_user_admin"])) {
                    $sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
                }
        _dbQuery($sql, 'UPDATE');
        //if($result = _dbQuery($sql, 'UPDATE')) {
            //headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=files&f=0');
        //}
    }

} else {

    //Editieren des Verzeichnisses
    $dir_id = empty($_GET["editdir"]) ? 0 : intval($_GET["editdir"]);

}
//Ende Auswerten Formular

//Wenn ID angegeben, dann -> oder aber Root Verzeichnis
if($dir_id) {

    $sql = "SELECT f_id, f_name, f_aktiv, f_public, f_longinfo, f_gallerystatus, f_sort, f_pid FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$dir_id;
    if(empty($_SESSION["wcs_user_admin"])) {
        $sql .= " AND f_uid=".$_SESSION["wcs_user_id"];
    }
    $sql .= " AND f_trash=0 AND f_kid=0 LIMIT 1";
    $result = _dbQuery($sql);
    if(isset($result[0]['f_id'])) {

        $dir_oldname = html($result[0]['f_name']);
        $dir_id      = intval($result[0]['f_id']);

        if(empty($_POST["dir_aktion"]) || (isset($_POST["dir_aktion"]) && intval($_POST["dir_aktion"]) != 2)) {
            $dir_newname    = $dir_oldname;
            $dir_aktiv      = $result[0]['f_aktiv'];
            $dir_public     = $result[0]['f_public'];
            $dir_longinfo   = $result[0]['f_longinfo'];
            $dir_gallery    = $result[0]['f_gallerystatus'];
            $dir_sort       = $result[0]['f_sort'];
            $dir_pid        = $result[0]['f_pid'];
        }

        $ja = 1;
    }
}

if(!empty($ja)) {

?>

<div class="card">
	<div class="card-header"><h1><?php echo $BL['be_fpriv_edittitle'] ?></h1></div>
		<div class="card-body">
			<form action="phpwcms.php?do=files&amp;f=0" method="post" name="editdir" id="editdir">
				<div class="form-group align-items-center row g-2">
						<label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_fpriv_name'] ?></label>
						<div class="col-sm-4">
							<strong><?php echo $dir_oldname ?></strong>
					</div>
				</div>

					<?php if($dir_error > 1) { ?>
				<strong style="color:#FF3300;"><?php echo $BL['be_fpriv_errordir'] ?></strong>
					<?php } ?>

				<div class="form-group align-items-center row g-2">
					<label for="dir_pid" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ftptakeover_directory'] ?></label>
					<div class="col-sm-4">
							<select name="dir_pid" id="dir_pid" class="form-select form-select-sm">
									<option value="0"<?php if($dir_pid == 0) echo " selected"; ?>><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
									<?php dir_menu(0, $dir_pid, "+", $_SESSION["wcs_user_id"], "+"); ?>
							</select>
					</div>
				</div>

					<?php if($dir_error === 1 || $dir_error === 3) { ?>

				<strong style="color:#FF3300;"><?php echo $BL['be_fpriv_error'] ?></strong></td>

					<?php } ?>

				<div class="form-group align-items-center row g-2">
					<label for="dir_newname" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_fpriv_newname'] ?></label>
					<div class="col-sm-4">
							<input name="dir_newname" type="text" class="form-control form-control-sm" id="dir_newname" value="<?php echo html($dir_newname) ?>" maxlength="250" />
					</div>
				</div>

				<div class="form-group row g-2">
					<label for="dir_longinfo" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ftptakeover_longinfo'] ?></label>
					<div class="col-sm-4">
							<textarea name="dir_longinfo" cols="40" rows="4" class="form-control form-control-sm" id="dir_longinfo"><?php echo html($dir_longinfo) ?></textarea>
					</div>
				</div>

				<div class="form-group align-items-center row g-2">
						<label for="dir_gallery" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_gallery'] ?></label>
						<div class="col-sm-4">
							<select name="dir_gallery" id="dir_gallery" class="form-select form-select-sm">
									<option value="0"<?php is_selected(0, $dir_gallery) ?>>-</option>
									<option value="2"<?php is_selected(2, $dir_gallery) ?>><?php echo $BL['be_gallery_root'] ?></option>
									<option value="3"<?php is_selected(3, $dir_gallery) ?>><?php echo $BL['be_gallery_directory'] ?></option>
							</select>
					</div>
				</div>

				<div class="form-group align-items-center row g-2">
					<label for="dir_sort" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_sorting'] ?></label>
					<div class="col-sm-4">
							<input name="dir_sort" type="text" id="dir_sort" size="10" class="form-control form-control-sm" maxlength="10" value="<?php echo intval($dir_sort) ?>" />
					</div>
				</div>

				<div class="form-group align-items-center row g-2">
					<label for="dir_aktiv" class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_fpriv_status'] ?></label>
						<div class="col-sm-auto">
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input class="form-check-input" name="dir_aktiv" type="checkbox" id="dir_aktiv" value="1"<?php is_checked("1", $dir_aktiv) ?> />
								<?php echo $BL['be_ftptakeover_active'] ?></label>
							</div>
						</div>
						<div class="col-sm-auto">
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input class="form-check-input" name="dir_public" type="checkbox" id="dir_public" value="1"<?php is_checked("1", $dir_public) ?> />
								<?php echo $BL['be_ftptakeover_public'] ?></label>
							</div>
						</div>
				</div>

				<div class="form-group row mt-4 mb-0">
					<div class="col-sm-2"></div>
					<div class="col-sm-10">
							<button name="Submit" type="submit" class="btn btn-blue btn-sm" value="1"><i class="fa fa-rotate"></i> <?php echo $BL['be_fpriv_updatebutton'] ?></button>
							<a class="btn btn-danger btn-sm ms-3" href="phpwcms.php?do=files&amp;f=0"><i class="fa fa-times"></i> <?php echo $BL['be_func_struct_close'] ?></a>
					</div>
				</div>

				<input name="dir_id" type="hidden" id="dir_id" value="<?php echo $dir_id ?>" />
				<input name="dir_aktion" type="hidden" id="dir_aktion" value="2" />
			</form>
		</div>
	</div>
</div>
<?php
}
?>
