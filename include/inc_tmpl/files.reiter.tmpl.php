<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2020, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
  die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// files
$files_folder = (isset($_GET["f"])) ? intval($_GET["f"]) : 0; //Ermitteln, welcher Unterreiter angezeigt wird

// if cut/paste is active
$add_paste_icon = '<a class="btn btn-blue btn-sm" href="cmsgo.php?do=files&amp;f=0&amp;mkdir=0" data-toggle="tooltip" title="'.$BL['be_ftab_createnew'].
          '"><i class="fa fa-fw fa-plus"></i></a>';
if(isset($_GET["cut"])) {
  $cutID = intval($_GET["cut"]);
  $add_paste_icon = '<a class="btn btn-danger btn-sm" data-toggle="tooltip" href="include/inc_act/act_file.php?paste='.$cutID.'|0" title="'.$BL['be_ftab_paste'].
            '"><i class="fa fa-fw fa-arrow-down"></i></a>';
} else { $cutID=0; }

$change_thumbnail_icon = '<a class="btn btn-blue btn-sm" data-toggle="tooltip" href="include/inc_act/act_file.php?thumbnail=';
if($_SESSION["wcs_user_thumb"]) {
  $change_thumbnail_icon .= '0" title="'.$BL['be_ftab_disablethumb'].'">';
  $change_thumbnail_icon .= '<i class="fa fa-fw fa-bars"></i></a>';
} else {
  $change_thumbnail_icon .= '1" title="'.$BL['be_ftab_enablethumb'].'">';
  $change_thumbnail_icon .= '<i class="fa fa-fw fa-image"></i></a>';
}

?>
<h1 class="text-center text-sm-left"><?php echo $BL['be_nav_files'] ?></h1>
<div class="card">
  <div class="card-header"><h2><?php echo $BL['be_subnav_file_center'] ?></h2></div>
  <div class="card-body">

<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link<?php echo ($files_folder == 0 ? ' active' : '');?>" href="cmsgo.php?do=files&amp;f=0"><?php echo $BL['be_ftab_private'] ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link<?php echo ($files_folder == 1 ? ' active' : '');?>" href="cmsgo.php?do=files&amp;f=1"><?php echo $BL['be_ftab_public'] ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link<?php echo ($files_folder == 3 ? ' active' : '');?>" href="cmsgo.php?do=files&amp;f=3"><?php echo $BL['be_ftab_search'] ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link<?php echo ($files_folder == 2 ? ' active' : '');?>" href="cmsgo.php?do=files&amp;f=2"><?php echo $BL['be_ftab_trash'] ?></a>
  </li>
</ul>


	<table class="table table-sm bg-grey mt-3" border="0" cellpadding="2" cellspacing="0" summary="">
		<tr>
			<?php if($files_folder == 0) { ?>
			<td class="tableheader text-right">
				<a class="btn btn-blue btn-sm" href="cmsgo.php?do=files&amp;f=0&amp;upload=0" data-toggle="tooltip" title="<?php echo $BL['be_ftab_upload'] ?>">
					<i class="fa fa-fw fa-upload"></i>
				</a>
			<?php echo $add_paste_icon ?>
				<a class="btn btn-blue btn-sm" data-toggle="modal" data-target="#help" style="cursor: pointer;">
					<i class="fa fa-fw fa-info"></i>
				</a>
				<a class="btn btn-blue btn-sm" href="cmsgo.php?do=files&amp;f=0&amp;all=open" data-toggle="tooltip" title="<?php echo $BL['be_ftab_open'] ?>">
					<i class="fa fa-fw fa-folder-open"></i>
				</a>
				<a class="btn btn-blue btn-sm" href="cmsgo.php?do=files&amp;f=0&amp;all=close" data-toggle="tooltip" title="<?php echo $BL['be_ftab_close'] ?>">
					<i class="fa fa-fw fa-folder"></i>
				</a>
				<?php echo $change_thumbnail_icon ?>
			</td>
			<?php } elseif($files_folder == 1) { ?>
			<td class="tableheader text-right">
				<a class="btn btn-blue btn-sm" data-toggle="modal" data-target="#help" style="cursor: pointer;">
					<i class="fa fa-fw fa-info"></i>
				</a>
				<a class="btn btn-blue btn-sm" href="cmsgo.php?do=files&amp;f=0&amp;all=close" data-toggle="tooltip" title="<?php echo $BL['be_ftab_close'] ?>">
					<i class="fa fa-fw fa-folder"></i>
				</a>
			<?php echo $change_thumbnail_icon ?></td>
			<?php } elseif($files_folder == 2) { ?>
			<td class="tableheader text-right">
				<a class="btn btn-blue btn-sm" data-toggle="modal" data-target="#help" style="cursor: pointer;">
					<i class="fa fa-fw fa-info"></i>
				</a>
			</td>
			<?php } elseif($files_folder == 3) { ?>
			<td class="tableheader text-right">
				<a class="btn btn-blue btn-sm" data-toggle="modal" data-target="#help" style="cursor: pointer;">
					<i class="fa fa-fw fa-info"></i>
				</a>
				<?php echo $change_thumbnail_icon ?></td>
			<?php } else { ?>
			<td  class="chatlist">&nbsp;</td>
			<?php } ?>
		</tr>
	</table>

	<div class="modal fade" id="help" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 id="exampleModalLabel">Dateizentrale Hilfe</h2>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<ul class="list-group">
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm mr-2" href="#"><i class="fa fa-fw fa-plus"></i></button>
							<?php echo $BL['be_ftabhelp_add'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm mr-2" href="#"><i class="fa fa-fw fa-upload"></i></button>
							<?php echo $BL['be_ftabhelp_upload'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm mr-2" href="#"><i class="fa fa-fw fa-bars"></i></button>
							<?php echo $BL['be_ftabhelp_disablethumb'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm mr-2" href="#"><i class="fa fa-fw fa-image"></i></button>
							<?php echo $BL['be_ftabhelp_enablethumb'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm mr-2" href="#"><i class="fa fa-fw fa-penci-alt"></i></button>
							<?php echo $BL['be_ftabhelp_edit'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-link text-dark btn-sm mr-2" href="#"><i class="fa fa-fw fa-cut"></i></button>
							<?php echo $BL['be_ftabhelp_cut'] ?>
						</li>
						<!--<li class="list-group-item p-1">
							<button class="btn btn-danger btn-sm mr-2" href="#"><i class="fa fa-fw fa-cut"></i></button>
							<?php echo $BL['be_ftabhelp_cutmark'] ?>
						</li>-->
						<li class="list-group-item p-1">
							<button class="btn btn-danger btn-sm mr-2" href="#"><i class="fa fa-fw fa-arrow-down"></i></button>
							<?php echo $BL['be_ftabhelp_paste'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-link text-dark btn-sm mr-2" href="#"><i class="fa fa-fw fa-download"></i></button>
							<?php echo $BL['be_ftabhelp_download'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-link text-dark btn-sm mr-2" href="#"><i class="far fa-fw fa-trash-alt"></i></button>
							<?php echo $BL['be_ftabhelp_delete'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-link text-dark btn-sm mr-2" disabled href="#"><i class="far fa-fw fa-trash-alt"></i></button>
							<?php echo $BL['be_ftabhelp_cantdelete'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm mr-2" href="#"><i class="fa fa-fw fa-arrow-up"></i></button>
							<?php echo $BL['be_ftabhelp_restore'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm mr-2" href="#"><i class="fa fa-fw fa-folder"></i></button>
							<?php echo $BL['be_ftabhelp_closefolder'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-blue btn-sm mr-2" href="#"><i class="fa fa-fw fa-folder-open"></i></button>
							<?php echo $BL['be_ftabhelp_openfolder'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-success btn-sm mr-2" href="#"><i class="fa fa-fw fa-eye"></i></button>
							<?php echo $BL['be_ftabhelp_active'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-danger btn-sm mr-2" href="#"><i class="fa fa-fw fa-eye-slash"></i></button>
							<?php echo $BL['be_ftabhelp_inactive'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-success btn-sm" href="#"><i class="fa fa-fw fa-unlock"></i></button>
							<?php echo $BL['be_ftabhelp_public'] ?>
						</li>
						<li class="list-group-item p-1">
							<button class="btn btn-danger btn-sm mr-2" href="#"><i class="fa fa-fw fa-lock"></i></button>
							<?php echo $BL['be_ftabhelp_private'] ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
