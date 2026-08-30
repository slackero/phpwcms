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

// files
$files_folder = (isset($_GET["f"])) ? intval($_GET["f"]) : 0; //Ermitteln, welcher Unterreiter angezeigt wird

// if cut/paste is active
$add_paste_icon = '<a class="btn btn-blue btn-sm" href="phpwcms.php?do=files&amp;f=0&amp;mkdir=0" data-bs-toggle="tooltip" title="'.$BL['be_ftab_createnew'].
          '"><i class="fa fa-fw fa-plus"></i></a>';
if(isset($_GET["cut"])) {
  $cutID = intval($_GET["cut"]);
  $add_paste_icon = '<a class="btn btn-danger btn-sm" data-bs-toggle="tooltip" href="include/inc_act/act_file.php?paste='.$cutID.'|0" title="'.$BL['be_ftab_paste'].
            '"><i class="fa fa-fw fa-arrow-down"></i></a>';
} else { $cutID=0; }

$change_thumbnail_icon = '<a class="btn btn-blue btn-sm" data-bs-toggle="tooltip" href="include/inc_act/act_file.php?thumbnail=';
if($_SESSION["wcs_user_thumb"]) {
  $change_thumbnail_icon .= '0" title="'.$BL['be_ftab_disablethumb'].'">';
  $change_thumbnail_icon .= '<i class="fa fa-fw fa-bars"></i></a>';
} else {
  $change_thumbnail_icon .= '1" title="'.$BL['be_ftab_enablethumb'].'">';
  $change_thumbnail_icon .= '<i class="fa fa-fw fa-image"></i></a>';
}

?>
<h1 class="text-center text-sm-start"><?php echo $BL['be_nav_files'] ?></h1>
<div class="card">
  <div class="card-header"><h2><?php echo $BL['be_subnav_file_center'] ?></h2></div>
  <div class="card-body">

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center tabs-flex-container mb-3 pb-2 pb-lg-0">
  <ul class="nav nav-tabs order-2 order-lg-1 mb-2 mb-lg-0 w-100 w-lg-auto align-self-lg-end">
    <li class="nav-item">
      <a class="nav-link<?php echo ($files_folder == 0 ? ' active' : '');?>" href="phpwcms.php?do=files&amp;f=0"><?php echo $BL['be_ftab_private'] ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link<?php echo ($files_folder == 1 ? ' active' : '');?>" href="phpwcms.php?do=files&amp;f=1"><?php echo $BL['be_ftab_public'] ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link<?php echo ($files_folder == 3 ? ' active' : '');?>" href="phpwcms.php?do=files&amp;f=3"><?php echo $BL['be_ftab_search'] ?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link<?php echo ($files_folder == 2 ? ' active' : '');?>" href="phpwcms.php?do=files&amp;f=2"><?php echo $BL['be_ftab_trash'] ?></a>
    </li>
  </ul>
  <div class="pb-1 align-self-end align-self-lg-center order-1 order-lg-2 text-nowrap">
    <?php if($files_folder == 0) { ?>
      <a class="btn btn-blue btn-sm" href="phpwcms.php?do=files&amp;f=0&amp;upload=0" data-bs-toggle="tooltip" title="<?php echo $BL['be_ftab_upload'] ?>">
        <i class="fa fa-fw fa-upload"></i>
      </a>
      <?php echo $add_paste_icon ?>
      <a class="btn btn-blue btn-sm" data-bs-toggle="modal" data-bs-target="#help" style="cursor: pointer;">
        <i class="fa fa-fw fa-info"></i>
      </a>
      <a class="btn btn-blue btn-sm" href="phpwcms.php?do=files&amp;f=0&amp;all=open" data-bs-toggle="tooltip" title="<?php echo $BL['be_ftab_open'] ?>">
        <i class="fa fa-fw fa-folder-open"></i>
      </a>
      <a class="btn btn-blue btn-sm" href="phpwcms.php?do=files&amp;f=0&amp;all=close" data-bs-toggle="tooltip" title="<?php echo $BL['be_ftab_close'] ?>">
        <i class="fa fa-fw fa-folder"></i>
      </a>
      <?php echo $change_thumbnail_icon ?>
    <?php } elseif($files_folder == 1) { ?>
      <a class="btn btn-blue btn-sm" data-bs-toggle="modal" data-bs-target="#help" style="cursor: pointer;">
        <i class="fa fa-fw fa-info"></i>
      </a>
      <a class="btn btn-blue btn-sm" href="phpwcms.php?do=files&amp;f=0&amp;all=close" data-bs-toggle="tooltip" title="<?php echo $BL['be_ftab_close'] ?>">
        <i class="fa fa-fw fa-folder"></i>
      </a>
      <?php echo $change_thumbnail_icon ?>
    <?php } elseif($files_folder == 2) { ?>
      <a class="btn btn-blue btn-sm" data-bs-toggle="modal" data-bs-target="#help" style="cursor: pointer;">
        <i class="fa fa-fw fa-info"></i>
      </a>
    <?php } elseif($files_folder == 3) { ?>
      <a class="btn btn-blue btn-sm" data-bs-toggle="modal" data-bs-target="#help" style="cursor: pointer;">
        <i class="fa fa-fw fa-info"></i>
      </a>
      <?php echo $change_thumbnail_icon ?>
    <?php } ?>
  </div>
</div>

	<div class="modal fade" id="help" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 id="exampleModalLabel">Dateizentrale Hilfe</h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<ul class="list-group">
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm me-2"><i class="fa fa-fw fa-plus"></i></button>
							<?php echo $BL['be_ftabhelp_add'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm me-2"><i class="fa fa-fw fa-upload"></i></button>
							<?php echo $BL['be_ftabhelp_upload'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm me-2"><i class="fa fa-fw fa-bars"></i></button>
							<?php echo $BL['be_ftabhelp_disablethumb'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm me-2"><i class="fa fa-fw fa-image"></i></button>
							<?php echo $BL['be_ftabhelp_enablethumb'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm me-2"><i class="fa fa-fw fa-pencil-alt"></i></button>
							<?php echo $BL['be_ftabhelp_edit'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-link text-dark btn-sm me-2"><i class="fa fa-fw fa-cut"></i></button>
							<?php echo $BL['be_ftabhelp_cut'] ?>
						</li>
						<!--<li class="list-group-item p-1">
							<button class="btn btn-danger btn-sm me-2"><i class="fa fa-fw fa-cut"></i></button>
							<?php echo $BL['be_ftabhelp_cutmark'] ?>
						</li>-->
						<li class="list-group-item p-1">
							<button class="btn btn-danger btn-sm me-2"><i class="fa fa-fw fa-arrow-down"></i></button>
							<?php echo $BL['be_ftabhelp_paste'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-link text-dark btn-sm me-2"><i class="fa fa-fw fa-download"></i></button>
							<?php echo $BL['be_ftabhelp_download'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-link text-dark btn-sm me-2"><i class="far fa-fw fa-trash-alt"></i></button>
							<?php echo $BL['be_ftabhelp_delete'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-link text-dark btn-sm me-2" disabled><i class="far fa-fw fa-trash-alt"></i></button>
							<?php echo $BL['be_ftabhelp_cantdelete'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm me-2"><i class="fa fa-fw fa-arrow-up"></i></button>
							<?php echo $BL['be_ftabhelp_restore'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm me-2" href="#"><i class="fa fa-fw fa-folder"></i></button>
							<?php echo $BL['be_ftabhelp_closefolder'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm me-2" href="#"><i class="fa fa-fw fa-folder-open"></i></button>
							<?php echo $BL['be_ftabhelp_openfolder'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-success btn-sm me-2" href="#"><i class="fa fa-fw fa-eye"></i></button>
							<?php echo $BL['be_ftabhelp_active'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-danger btn-sm me-2" href="#"><i class="fa fa-fw fa-eye-slash"></i></button>
							<?php echo $BL['be_ftabhelp_inactive'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-success btn-sm" href="#"><i class="fa fa-fw fa-unlock"></i></button>
							<?php echo $BL['be_ftabhelp_public'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-danger btn-sm me-2" href="#"><i class="fa fa-fw fa-lock"></i></button>
							<?php echo $BL['be_ftabhelp_private'] ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
