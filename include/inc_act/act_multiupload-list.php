<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

$phpwcms = array('SESSION_START' => true);
require_once '../config/conf.inc.php';
require_once '../inc_lib/default.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/helper.session.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/dbcon.inc.php';
require_once PHPWCMS_ROOT.'/include/inc_lib/general.inc.php';

if(empty($_SESSION["wcs_user_id"]) || !validate_csrf_get_token()) {
	die('{"success":false}');
}

// check against user's language
if(!empty($_SESSION["wcs_user_lang"]) && preg_match('/[a-z]{2}/i', $_SESSION["wcs_user_lang"])) {
    $BE['LANG'] = $_SESSION["wcs_user_lang"];
}

require_once PHPWCMS_ROOT.'/include/inc_lang/backend/en/lang.inc.php'; //load default language EN

if(!empty($_SESSION["wcs_user_lang_custom"])) {
    //use custom lang if available -> was set in login.php
    $BL['merge_lang_array'][0]      = $BL['be_admin_optgroup_label'];
    $BL['merge_lang_array'][1]      = $BL['be_cnt_field'];
    include PHPWCMS_ROOT.'/include/inc_lang/backend/'. $BE['LANG'] .'/lang.inc.php';
    // Adding specific language files
    include PHPWCMS_ROOT.'/include/inc_lang/backend/'. $BE['LANG'] .'/lang.pp.inc.php';
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
					$handle = @opendir(PHPWCMS_ROOT.$phpwcms["ftp_path"]);
					$fx = 0;
					$fxsg = 0;
							while($file = @readdir($handle)) {
                                if(!is_dir($file) && $file !== "." && $file !== ".." && substr($file, 0, 1) !== '.' && $fxs = filesize(PHPWCMS_ROOT.$phpwcms["ftp_path"].$file)) {

                                    // test if the file should be deleted
                                    $file_base64 = base64_encode($file);

                                    if(isset($deleteFiles[$file_base64]) && @unlink(PHPWCMS_ROOT.$phpwcms["ftp_path"].$file)) {
                                        continue;
                                    }

                                    $fxb = ($fx % 2) ? ' bgColor="#F9FAFB"' : '';
                                    $fxsg += $fxs;
                                    $fxe = extimg(which_ext($file));
                                    $filename = html(makeCharsetConversion($file, 'utf-8', PHPWCMS_CHARSET));
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

