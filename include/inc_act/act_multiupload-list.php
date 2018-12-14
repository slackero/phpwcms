<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

session_start();

if(empty($_SESSION["wcs_user_id"])) {

	die('{"success":false}');

}

$cmsgo = array();
require '../../include/config/conf.inc.php';
require '../inc_lib/default.inc.php';
require_once CMSGO_ROOT.'/include/inc_lib/helper.session.php';

if(!validate_csrf_get_token('csrftoken')) {
	die('{"success":false}');
}

require CMSGO_ROOT.'/include/inc_lib/general.inc.php';

// check against user's language
if(!empty($_SESSION["wcs_user_lang"]) && preg_match('/[a-z]{2}/i', $_SESSION["wcs_user_lang"])) {
    $BE['LANG'] = $_SESSION["wcs_user_lang"];
}

require_once CMSGO_ROOT.'/include/inc_lang/backend/en/lang.inc.php'; //load default language EN

if(!empty($_SESSION["wcs_user_lang_custom"])) {
    //use custom lang if available -> was set in edit.php
    $BL['merge_lang_array'][0]      = $BL['be_admin_optgroup_label'];
    $BL['merge_lang_array'][1]      = $BL['be_cnt_field'];
    include CMSGO_ROOT.'/include/inc_lang/backend/'. $BE['LANG'] .'/lang.inc.php';
    // Adding specific language files
    include CMSGO_ROOT.'/include/inc_lang/backend/'. $BE['LANG'] .'/lang.pp.inc.php';
    $BL['be_admin_optgroup_label']  = array_merge($BL['merge_lang_array'][0], $BL['be_admin_optgroup_label']);
    $BL['be_cnt_field']             = array_merge($BL['merge_lang_array'][1], $BL['be_cnt_field']);
    unset($BL['merge_lang_array']);
}
?>

<div class="table-responsive">
	<table class="table table-sm">
		<tr bgcolor="#D9DEE3">
			<th><?php echo $BL['be_ftptakeover_mark'] ?></th>
			<th><?php echo $BL['be_ftptakeover_available'] ?></th>
			<th><?php echo $BL['be_ftptakeover_size'] ?></th>
		</tr>
	<?php
					//Browse FTP Open Directory
					$handle = @opendir(CMSGO_ROOT.$cmsgo["ftp_path"]);
					$fx = 0;
					$fxsg = 0;
							while($file = @readdir($handle)) {
									if(!is_dir($file) && $file !== "." && $file !== ".." && substr($file, 0, 1) !== '.' && $fxs = filesize(CMSGO_ROOT.$cmsgo["ftp_path"].$file)) {

											// test if the file should be deleted
											$file_base64 = base64_encode($file);

											if(isset($deleteFiles[$file_base64]) && @unlink(CMSGO_ROOT.$cmsgo["ftp_path"].$file)) {
															continue;
													}

											$fxb = ($fx % 2) ? ' bgColor="#F9FAFB"' : '';
											$fxsg += $fxs;
											$fxe = extimg(which_ext($file));
											 // there is a big problem with special chars on Mac OS X and seems Windows too
											$filename = (CMSGO_CHARSET != 'utf-8' && cmsgo_seems_utf8($file)) ? str_replace('?', '', utf8_decode($file)) : $file;
											$filename = html($filename);
	?>
						<tr<?php echo $fxb ?>>
							<td align="center" width="30"><input name="ftp_mark[<?php echo $fx ?>]" type="checkbox" id="ftp_mark_<?php echo $fx ?>" value="1" class="ftp_mark" /></td>
							<td><?php echo $filename ?></td>
							<td>
									<?php echo fsizelong($fxs) ?>
									<input name="ftp_file[<?php echo $fx ?>]" type="hidden" value="<?php echo $file_base64 ?>" />
									<input name="ftp_filename[<?php echo $fx ?>]" type="hidden" value="<?php echo $filename ?>" />
							</td>
	<?php               $fx++;
									}
							}
					@closedir($handle);

					if(!$fx) {
	?>
						<tr>
							<td colspan="2" class="dir">&nbsp;<?php echo $BL['be_ftptakeover_nofile'] ?></td>
							<td></td>
					</tr>
	<?php
					} else {
	?>

						<tr bgcolor="#EAEDF0">
							<td align="center" width="30"><input name="toggle" type="checkbox" id="toggle" value="1" title="<?php echo $BL['be_ftptakeover_all'] ?>" /></td>
							<td><button id="delete-selected-files" style="display:none;" class="btn btn-sm btn-blue my-1"><?php echo $BL['be_delete_selected_files'] ?></button></td>
							<td><?php echo fsizelong($fxsg) ?>&nbsp;</td>
					</tr>
	<?php
					}
	?>
	</table>
</div>

