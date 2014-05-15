<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2014, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// If there are files marked to be deleted
$deleteFiles = array();

if(isset($_POST['ftp_mark']) && is_array($_POST['ftp_mark']) && count($_POST['ftp_mark'])) {

	foreach($_POST['ftp_mark'] as $key => $value) {

		$deleteFiles[$_POST['ftp_file'][$key]] = $_POST['ftp_filename'][$key];

	}

}

?>
<h1 class="title"><?php echo $BL['be_file_multiple_upload'] ?></h1>
<div class="uploader">
	<div class="uploader-button" id="upload-file-select"></div>
</div>

<h1 class="title"><?php echo $BL['be_files_select_available'] ?></h1>

<form action="include/inc_act/act_ftptakeover.php" method="post" name="ftptakeover" id="ftptakeover">
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
		<tr bgcolor="#92A1AF"><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
        </tr>
          <tr bgcolor="#D9DEE3">
            <td width="35" align="center" class="v09"><?php echo $BL['be_ftptakeover_mark'] ?></td>
            <td width="1" bgcolor="#F2F3F5"><img src="img/leer.gif" alt="" width="1" height="14" /></td>
            <td width="21"><img src="img/leer.gif" alt="" width="21" height="1" /></td>
            <td width="400" class="v09"><?php echo $BL['be_ftptakeover_available'] ?></td>
            <td width="1" bgcolor="#F2F3F5"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
            <td width="80" align="right" class="v09"><?php echo $BL['be_ftptakeover_size'] ?>&nbsp;&nbsp;</td>
          </tr>
		  <tr bgcolor="#92A1AF"><td colspan="6" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
	    </tr>
		  <?php
				//Browse FTP Open Directory
				$handle = @opendir(PHPWCMS_ROOT.$phpwcms["ftp_path"]);
				$fx = 0;
				$fxsg = 0;
				while ($file = @readdir ($handle))
				{
					if($file != "." && $file != ".." && $file{0} != '.') {
						if(!is_dir($file) && $fxs = filesize(PHPWCMS_ROOT.$phpwcms["ftp_path"].$file)) {

							// test if the file should be deleted
							$file_base64 = base64_encode($file);
							if(isset($deleteFiles[$file_base64])) {

								if(@unlink(PHPWCMS_ROOT.$phpwcms["ftp_path"].$file)) {
									continue;
								}

							}

							$fxb = ($fx % 2) ? " bgColor=\"#F9FAFB\"" : "";
							$fxsg += $fxs;
							$fxe = extimg(which_ext($file));
							 // there is a big problem with special chars on Mac OS X and seems Windows too
							$filename = (PHPWCMS_CHARSET != 'utf-8' && phpwcms_seems_utf8($file)) ? str_replace('?', '', utf8_decode($file)) : $file;
							$filename = html($filename);
		  ?>
          <tr<?php echo $fxb ?>>
            <td align="center"><input name="ftp_mark[<?php echo $fx ?>]" type="checkbox" id="ftp_mark_<?php echo $fx ?>" value="1" class="ftp_mark" /></td>
            <td bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="17" /></td>
            <td align="center"><img src="img/icons/small_<?php echo $fxe ?>" alt="" width="13" height="11" /></td>
            <td class="v10"><?php echo $filename ?></td>

            <td bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
            <td align="right" class="v10">
				<?php echo fsizelong($fxs) ?>&nbsp;
				<input name="ftp_file[<?php echo $fx ?>]" type="hidden" value="<?php echo $file_base64 ?>" />
				<input name="ftp_filename[<?php echo $fx ?>]" type="hidden" value="<?php echo $filename ?>" />
			</td>
          </tr>
			<?php				$fx++;
						}
					}
				}
				@closedir($handle);

				if(!$fx) {
			?>
          <tr>
            <td colspan="5" class="dir">&nbsp;<?php echo $BL['be_ftptakeover_nofile'] ?></td>
            <td><img src="img/leer.gif" alt="" width="1" height="17" /></td>
        </tr>
			<?php
				} else {
			?>
          <tr bgcolor="#92A1AF"><td colspan="6" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
        </tr>

          <tr bgcolor="#EAEDF0">
            <td align="center" class="subnavactive"><input name="toggle" type="checkbox" id="toggle" value="1" title="<?php echo $BL['be_ftptakeover_all'] ?>" /></td>
            <td bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="17" /></td>
            <td>&nbsp;</td>
            <td class="v10">
				<?php echo $BL['be_ftptakeover_all'] ?>
				<button id="delete-selected-files" style="display:none;margin-left:3em;" class="v10"><?php echo $BL['be_delete_selected_files'] ?></button>
			</td>
            <td bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
            <td align="right" class="v10"><?php echo fsizelong($fxsg) ?>&nbsp;</td>
          </tr>
		  <tr bgcolor="#92A1AF"><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
	    </tr>
		  <?php
		  	}
		  ?>
          <tr bgcolor="#D9DEE3">
            <td><img src="img/leer.gif" alt="" width="35" height="1" /></td>
            <td><img src="img/leer.gif" alt="" width="1" height="1" /></td>
            <td><img src="img/leer.gif" alt="" width="21" height="1" /></td>
            <td><img src="img/leer.gif" alt="" width="400" height="1" /></td>
            <td><img src="img/leer.gif" alt="" width="1" height="1" /></td>
            <td><img src="img/leer.gif" alt="" width="80" height="1" /></td>
        </tr>
	    </table><?php
			//Nur Zeigen wenn Dateien vorhanden
			if($fx) {

       ?>


	   <table width="538" border="0" cellpadding="0" cellspacing="0" summary="" style="background:#EBF2F4">
          <tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
            <tr>
              <td width="67" align="right" class="v09"><?php echo $BL['be_ftptakeover_directory'] ?>:&nbsp;</td>
              <td width="471" class="v10"><select name="file_dir" id="file_dir" class="v11 width400">
                  <option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
                  <?php dir_menu(0, 0, $db, "+", $_SESSION["wcs_user_id"], "+") ?>
                 </select></td>
            </tr>



 <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
 <tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1" /></td></tr>

<?php	if(count($phpwcms['allowed_lang']) > 1): ?>

	<tr>
		<td>&nbsp;</td>
		<td class="incell-tabs">

			<a href="#" rel="<?php echo $phpwcms['default_lang'] ?>" title="<?php echo get_language_name($phpwcms['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>" class="active">
				<img src="img/famfamfam/lang/<?php echo $phpwcms['default_lang'] ?>.png" /> <?php echo $BL['be_admin_tmpl_default'] ?>
			</a>

			<?php foreach($phpwcms['allowed_lang'] as $lang):

				$lang = strtolower($lang);

				if($lang == $phpwcms['default_lang']) {
					continue;
				}

			?>

			<a href="#" rel="<?php echo $lang ?>" title="<?php echo get_language_name($lang) ?>">
				<img src="img/famfamfam/lang/<?php echo $lang ?>.png" /> <?php echo strtoupper($lang) ?>
			</a>

			<?php	endforeach; ?>

		</td>
	</tr>

<?php	endif; ?>

	<tr class="tab-content finfo<?php echo $phpwcms['default_lang'] ?>">
		<td align="right" valign="top" class="v09 tdtop5"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_ftptakeover_longinfo'] ?>:&nbsp;</td>
		<td valign="top tdbottom3"><textarea name="file_longinfo" cols="40" rows="4" class="v12 width400" id="file_longinfo"></textarea></td>
	</tr>
	<tr class="tab-content finfo<?php echo $phpwcms['default_lang'] ?>">
		<td align="right" class="v09"><?php echo $BL['be_copyright'] ?>:&nbsp;</td>
		<td><input name="file_copyright" type="text" id="file_copyright" size="40" class="width400" maxlength="255" value="" /></td>
	</tr>


<?php	if(count($phpwcms['allowed_lang']) > 1):

			foreach($phpwcms['allowed_lang'] as $lang):

				$lang = strtolower($lang);

				if($lang == $phpwcms['default_lang']) {
					continue;
				}

?>

	<tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
		<td align="right" valign="top" class="v09 tdtop5"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_ftptakeover_longinfo'] ?> (<?php echo strtoupper($lang) ?>):&nbsp;</td>
		<td valign="top tdbottom3"><textarea name="file_longinfo_<?php echo $lang ?>" cols="40" rows="4" class="v12 width400" id="file_longinfo_<?php echo $lang ?>"></textarea></td>
	</tr>
	<tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
		<td align="right" class="v09"><?php echo $BL['be_copyright'] ?> (<?php echo strtoupper($lang) ?>):&nbsp;</td>
		<td><input name="file_copyright_<?php echo $lang ?>" type="text" id="file_copyright_<?php echo $lang ?>" size="40" class="width400" maxlength="255" value="" /></td>
	</tr>


<?php		endforeach;
		endif;
?>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1" /></td></tr>
	<tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	<?php

	//Auswahlliste vordefinierte Keywörter
	$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_deleted=0 ORDER BY fcat_sort, fcat_name";
	if($result = mysql_query($sql, $db) or die("error while browsing file categories for selecting keywords")) {
		$k = "";
		while($row = mysql_fetch_array($result)) {
			if(get_filecat_childcount ($row["fcat_id"], $db)) {

				$ke = empty($file_error["keywords"][$row["fcat_id"]])? '' : "<img src=\"img/symbole/error.gif\" width=\"8\" height=\"9\">&nbsp;";
				$k .= "<tr>\n<td class=\"f10b\">".$ke.html($row["fcat_name"]).":&nbsp;</td>\n";
				$k .= "<td><select name=\"file_keywords[".$row["fcat_id"]."]\" class=\"width300\">\n";
				$k .= "<option value=\"".(($row["fcat_needed"])?"0_".$row["fcat_needed"]."\">".$BL['be_ftptakeover_needed']:'0">'.$BL['be_ftptakeover_optional'])."</option>\n";

				$ksql = "SELECT * FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_deleted=0 AND fkey_cid=".$row["fcat_id"]." ORDER BY fkey_name";
				if($kresult = mysql_query($ksql, $db) or die("error while listing file keywords")) {
					while($krow = mysql_fetch_array($kresult)) {
						$k .= "<option value=\"".$krow["fkey_id"]."\">".html($krow["fkey_name"])."</option>\n";
					}
					mysql_free_result($kresult);
				}

				$k .= "</select></td>\n</tr>\n";
				$k .= "<tr>\n<td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"2\"></td>\n</tr>\n";

			}
		}
		mysql_free_result($result);
	}
	//Ende vordefinierte Keywörter

	?>
	<tr bgcolor="#F5F8F9">
		<td align="right" valign="top" class="v09 tdtop5"><?php echo $BL['be_ftptakeover_keywords'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<?php if($k) echo $k; ?>
		<tr>
			<td class="f10b"><?php echo $BL['be_ftptakeover_additional'] ?>:&nbsp;</td>
			<td><input name="file_shortinfo" type="text" class="width300" id="file_shortinfo" value="" size="40" maxlength="250" /></td>
		</tr>
		</table></td>
	</tr>

	<tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

	<tr bgcolor="#F5F8F9">
		<td align="right" class="v09">&nbsp;<?php echo $BL['be_tags'] ?>:&nbsp;</td>
		<td><input type="text" id="file_tags_autosuggest" /><input name="file_tags" type="hidden" id="file_tags" value="" /></td>
	</tr>


	<tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1" /></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

            <tr>
              <td align="right" class="v09"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
              <td><table border="0" cellpadding="1" cellspacing="0" bgcolor="#E6EAED" summary="">
                  <tr>
                    <td><input name="file_aktiv" type="checkbox" id="file_aktiv" value="1"<?php is_checked($phpwcms['set_file_active'], 1) ?> /></td>
                    <td class="v10"><strong><label for="file_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label></strong>&nbsp;&nbsp;</td>
                    <td><input name="file_public" type="checkbox" id="file_public" value="1"<?php is_checked($phpwcms['set_file_active'], 1) ?> /></td>
                    <td class="v10"><strong><label for="file_public"><?php echo $BL['be_ftptakeover_public'] ?></label></strong>&nbsp;&nbsp;</td>
					<td><input name="file_replace" type="checkbox" id="file_replace" value="1" /></td>
                    <td class="v10"><strong><label for="file_replace"><?php echo $BL['be_file_replace'] ?></label></strong>&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr><td colspan="2" align="right" class="v09"><img src="img/leer.gif" alt="" width="1" height="12" /></td>
            </tr>
            <tr>
              <td width="67" valign="top"><input name="file_aktion" type="hidden" id="file_aktion" value="1" /></td>
              <td><input name="Submit" type="submit" class="button10" value="<?php echo $BL['be_ftptakeover_button'] ?>" /></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td>
            </tr>
          <tr bgcolor="#92A1AF"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
          </tr>
</table>
<?php }

initJsAutocompleter();
$GLOBALS['BE']['HEADER']['fileuploader.css']	= '	<link href="include/inc_js/uploader/fileuploader.css" rel="stylesheet" type="text/css" />';
$GLOBALS['BE']['HEADER']['fileuploader.js']		= getJavaScriptSourceLink('include/inc_js/uploader/fileuploader.min.js');

?>
</form>
<script type="text/javascript">
<!--
$(function() {


	var uploadButton = $('#upload-file-select');

	if(uploadButton) {

		var uploadFileCount = 0;

		// File Uploading
		var uploader = new qq.FileUploader({
			element: uploadButton[0],
			action: '<?php echo PHPWCMS_URL ?>include/inc_act/act_upload.php',
			multiple: true,
			autoUpload: true,
			debug: true,
			uploadButtonText: '<?php echo $BL['be_fileuploader_uploadButtonText'] ?>',
			cancelButtonText: '<?php echo $BL['be_newsletter_button_cancel'] ?>',
			failUploadText: '<?php echo $BL['be_error_while_save'] ?>',
			dragText: '<?php echo $BL['be_fileuploader_dragText'] ?>',
			sizeLimit: <?php

				if(ini_get('post_max_size')) {
					$post_max_size = return_bytes(ini_get('post_max_size'));
					if($post_max_size < $phpwcms['file_maxsize']) {
						$phpwcms['file_maxsize'] = $post_max_size;
					}
				} else {
					$post_max_size = $phpwcms['file_maxsize'];
				}
				if(ini_get('upload_max_filesize')) {
					$upload_max_filesize = return_bytes(ini_get('upload_max_filesize'));
					if($upload_max_filesize < $phpwcms['file_maxsize']) {
						$phpwcms['file_maxsize'] = $upload_max_filesize;
					}
				} else {
					$upload_max_filesize = $phpwcms['file_maxsize'];
				}

				echo min($post_max_size, $upload_max_filesize, $phpwcms['file_maxsize']);

			?>,
			messages: {
				typeError: "<?php echo $BL['be_fileuploader_typeError'] ?>",
				sizeError: "<?php echo $BL['be_fileuploader_sizeError'] ?>",
				minSizeError: "<?php echo $BL['be_fileuploader_minSizeError'] ?>",
				emptyError: "<?php echo $BL['be_fileuploader_emptyError'] ?>",
				noFilesError: "<?php echo $BL['be_fileuploader_noFilesError'] ?>",
				onLeave: "<?php echo $BL['be_fileuploader_onLeave'] ?>"
			},
			disableDefaultDropzone: false,
			onSubmit: function(id, fileName) {
				uploadFileCount++;
			},
			onCancel: function(id, fileName) {
				uploadFileCount--;
			},
			onComplete: function(id, fileName, responseJSON) {
				if(responseJSON.success) {
					uploadFileCount--;
					if(uploadFileCount == 0) {
						document.location.reload(true);
					}
				}
			}
		});

	}

<?php if($fx): ?>

	var ftpTakeOverForm = $('#ftptakeover');
	var deleteFiles = $('#delete-selected-files');
	var fileMarker = $('input.ftp_mark');

	$('#toggle').click(function() {

		var toggle_var	= $(this).is(':checked');
		var isChecked	= false;

		fileMarker.each(function() {
			var $_this = $(this);
			if($_this.is(':checked')) {
				$_this.attr('checked', false);
			} else {
				$_this.attr('checked', true);
				isChecked = true;
			}
		});

		if(isChecked) {
			deleteFiles.show();
		} else {
			deleteFiles.hide();
		}

	});

	fileMarker.click(function() {

		var isChecked = false;

		fileMarker.each(function() {
			if($(this).is(':checked')) {
				isChecked = true;
			}
		});

		if(isChecked) {
			deleteFiles.show();
		} else {
			deleteFiles.hide();
		}

	});

	deleteFiles.click(function(evt) {
		evt.preventDefault();
		if(confirm('<?php echo str_replace("'", "\\'", html_entity_decode($BL['be_delete_selected_files_confirm'], ENT_QUOTES, PHPWCMS_CHARSET)) ?>')) {
			ftpTakeOverForm.attr('action', 'phpwcms.php?do=files&p=8').submit();
		}
	});


	$("#file_tags_autosuggest").autoSuggest('<?php echo PHPWCMS_URL ?>include/inc_act/ajax_connector.php', {
		selectedItemProp: "cat_name",
		selectedValuesProp: 'cat_name',
		searchObjProps: "cat_name",
		queryParam: 'value',
		extraParams: '&method=json&action=category',
		startText: '',
		preFill: $("#file_tags").val(),
		neverSubmit: true,
		asHtmlID: 'keyword-autosuggest'
	});

	ftpTakeOverForm.submit(function(evt) {

		if($('input.ftp_mark:checked').length) {
			$("#file_tags").val($('#as-values-keyword-autosuggest').val());
		} else {
			evt.preventDefault();
		}

	});

<?php
		if(count($phpwcms['allowed_lang']) > 1): ?>

	var tab_content = $('tr.tab-content');
	var tabs		= $('td.incell-tabs a');

	tabs.click(function(event) {
		event.preventDefault();

		var $_this = $(this);

		if($_this.hasClass('active')) {
			return false;
		}

		tab_content.hide();
		tabs.removeClass('active');

		$_this.addClass('active');
		$('tr.finfo'+$_this.attr('rel')).show();

	});

<?php
		endif;
	endif;
?>

});

//-->
</script>