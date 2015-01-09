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


// Be more modern here — we start switch to jQuery and overwrite non-used MooTools with jQuery call
initJsAutocompleter();

// Upload new file
$file_aktiv				= $phpwcms['set_file_active'];
$file_public			= $phpwcms['set_file_active'];
$file_shortinfo			= '';
$file_longinfo			= '';
$file_pid				= empty($_GET["upload"]) ? 0 : intval($_GET["upload"]);
$file_copyright			= '';
$file_tags				= '';
$file_granted			= 0;
$file_gallerydownload	= 0;
$file_sort				= 0;

//Auswerten des Formulars
if(isset($_POST["file_aktion"]) && intval($_POST["file_aktion"]) == 1) {
	if(!ini_get('safe_mode') && function_exists('set_time_limit')) set_time_limit(0);
	$file_pid 				= intval($_POST["file_pid"]);
	$file_aktiv				= empty($_POST["file_aktiv"]) ? 0 : 1;
	$file_public 			= empty($_POST["file_public"]) ? 0 : 1;
	$file_shortinfo			= clean_slweg($_POST["file_shortinfo"]);
	$file_longinfo			= slweg(trim($_POST["file_longinfo"]));
	$file_copyright			= clean_slweg($_POST["file_copyright"]);
	$file_tags				= trim( clean_slweg($_POST["file_tags"]), ',' );
	$file_granted			= empty($_POST["file_granted"]) ? 0 : 1;
	$file_gallerydownload	= empty($_POST["file_gallerydownload"]) ? 0 : 1;
	$file_keys				= '';
	$file_sort 				= intval($_POST["file_sort"]);

	if(count($phpwcms['allowed_lang']) > 1) {

		$file_vars = array();

		foreach($phpwcms['allowed_lang'] as $lang) {
			$lang = strtolower($lang);

			if(isset($_POST['file_longinfo_'.$lang])) {
				$file_vars[$lang]['longinfo'] = slweg($_POST['file_longinfo_'.$lang]);
			}
			if(isset($_POST['file_copyright_'.$lang])) {
				$file_vars[$lang]['copyright'] = slweg($_POST['file_copyright_'.$lang]);
			}
		}
	}

	$file_keywords	= empty($_POST["file_keywords"]) ? array() : $_POST["file_keywords"];
	if(count($file_keywords)) {
		foreach($file_keywords as $key => $value) {
			unset($file_keywords[$key]);
			$key = intval($key);
			if($value != "0_1") {
				$file_keys .= (($file_keys) ? ":" : "").$key."_".intval($value);
				$file_keywords[$key] = intval($value);
			} else {
				$file_error["keywords"][$key] = 1;
			}
		}
	}

	//starts upload of file
	if(!is_uploaded_file($_FILES["file"]["tmp_name"])) {
		$file_error["file"] = $BL['be_fprivup_err1'];
	} else {
		if($_FILES["file"]["size"] > $phpwcms["file_maxsize"]) {
			$file_error["file"] = $BL['be_fprivup_err2']." ".number_format($phpwcms["file_maxsize"] / 1024, 2, ',', '.')." kB";
		}
	}

	//Create new file in database and give hashed
	if(!isset($file_error)) {
		$fileName	= sanitize_filename($_FILES["file"]["name"]);
		$fileExt	= check_image_extension($_FILES["file"]["tmp_name"], $fileName);
		$fileExt	= $fileExt === false ? which_ext($fileName) : $fileExt;
		$fileHash	= md5( $fileName . microtime() );
		$fileType	= is_mimetype_format($_FILES["file"]["type"]) ? $_FILES["file"]["type"] : get_mimetype_by_extension($fileExt);

		if(isset($file_vars)) {
			$fileVarsField = ',f_vars';
			$fileVarsValue = ','._dbEscape(serialize($file_vars));
		} else {
			$fileVarsField = '';
			$fileVarsValue = '';
		}

		$sql =  "INSERT INTO ".DB_PREPEND."phpwcms_file (".
				"f_pid, f_uid, f_kid, f_aktiv, f_public, f_name, f_created, f_size, f_type, f_ext, ".
				"f_shortinfo, f_longinfo, f_keywords, f_hash, f_copyright, f_tags, f_granted, f_gallerystatus, f_sort".$fileVarsField.") VALUES (".
				$file_pid.", ".intval($_SESSION["wcs_user_id"]).", 1, ".$file_aktiv.", ".$file_public.", '".
				$fileName."', '".time()."', '".intval($_FILES["file"]["size"])."', '".
				aporeplace($fileType)."', '".$fileExt."', '".aporeplace($file_shortinfo)."', '".
				aporeplace($file_longinfo)."', '".aporeplace($file_keys)."', '".aporeplace($fileHash)."', '".
				aporeplace($file_copyright)."', '".aporeplace($file_tags)."', ".$file_granted.", ".
				$file_gallerydownload.", ".$file_sort.$fileVarsValue.")";

		if($result = mysql_query($sql, $db) or die("error while insert file information")) {
			$new_fileId = mysql_insert_id($db); //Festlegen der aktuellen File-ID
			$wcs_newfilename = ($fileExt) ? $fileHash.'.'.$fileExt : $fileHash;

			// changed for using hashed file names
			$useruploadpath = PHPWCMS_ROOT.$phpwcms["file_path"];
			$usernewfile	= $useruploadpath.$wcs_newfilename;

			if ($dir = @opendir($useruploadpath)) {
				if(!@move_uploaded_file($_FILES["file"]["tmp_name"], $usernewfile)) {

					$file_error["upload"] = $BL['be_fprivup_err3'].' (1)';
				}
			} else {
				$oldumask = umask(0);
				if(@mkdir($useruploadpath, 0777)) {;
					if(!@move_uploaded_file($_FILES["file"]["tmp_name"], $usernewfile)) {
						$file_error["upload"] = $BL['be_fprivup_err3'].' (2)';
					}
				} else {
					$file_error["upload"] = $BL['be_fprivup_err4'];
				}
				umask($oldumask);
			}
			if(file_exists($usernewfile)) {
				@chmod($usernewfile, 0666);
			}
			if(!isset($file_error["upload"])) {

				// store tags
				_dbSaveCategories($file_tags, 'file', $new_fileId, ',');

				//after successful upload go back to clear post (form) var
				headerRedirect(PHPWCMS_URL."phpwcms.php?do=files&f=0&uploaded=1");
			} else {
				echo $file_error["upload"]."<br />";
				$file_error["upload"] = str_replace('{VAL}', $phpwcms["admin_email"], $BL['be_fprivup_err6']);
				mysql_query("DELETE FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$new_fileId." AND f_uid=".$_SESSION["wcs_user_id"].";", $db);
			}
		}
	}
	if(!ini_get('safe_mode') && function_exists('set_time_limit')) set_time_limit(30);
}
//Ende Auswerten Formular


?>
<form action="phpwcms.php?do=files&amp;f=0" method="post" enctype="multipart/form-data" name="uploadfile" id="uploadfile">
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#EBF2F4" summary="">
	<tr>
		<td rowspan="2" valign="top"><a href="phpwcms.php?do=files&amp;f=0"><img src="img/button/close_reiter.gif" alt="" width="45" height="12" border="0" /></a></td>
		<td><img src="img/leer.gif" alt="" width="1" height="6" /></td>
	</tr>
	<tr><td class="title"><?php echo $BL['be_fprivup_title'] ?></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
	<tr>
		<td align="right" class="v09"><?php echo $BL['be_ftptakeover_directory'] ?>:&nbsp;</td>
		<td class="v10"><select name="file_pid" id="file_pid" class="width400">
			<option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
			<?php dir_menu(0, $file_pid, $db, "+", $_SESSION["wcs_user_id"], "+") ?>
	</select></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1" /></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	<?php if(isset($file_error["upload"])) { ?>
	<tr>
	  <td><img src="img/leer.gif" alt="" width="1" height="1" /></td>
	  <td class="v10"><strong style="color:#FF3300"><?php echo $file_error["upload"] ?></strong></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
	</tr>
	<?php }

	if(isset($file_error["file"])) { ?>
	<tr>
	  <td><img src="img/leer.gif" alt="" width="1" height="1" /></td>
	  <td class="v10"><strong style="color:#FF3300"><?php echo $file_error["file"] ?></strong></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
	<?php } ?>
	<tr>
		<td align="right" class="v09"><?php echo $BL['be_fprivup_upload'] ?>:&nbsp;</td>
		<td><input name="file" type="file" id="file" size="40" /></td>
	</tr>


	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

<?php	if(count($phpwcms['allowed_lang']) > 1): ?>

	<tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1"></td></tr>

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
		<td valign="top tdbottom3"><textarea name="file_longinfo" cols="40" rows="4" class="width400" id="file_longinfo"><?php echo html($file_longinfo) ?></textarea></td>
	</tr>
	<tr class="tab-content finfo<?php echo $phpwcms['default_lang'] ?>">
		<td align="right" class="v09"><?php echo $BL['be_copyright'] ?>:&nbsp;</td>
		<td><input name="file_copyright" type="text" id="file_copyright" size="40" class="width400" maxlength="255" value="<?php echo html($file_copyright) ?>" /></td>
	</tr>


<?php	if(count($phpwcms['allowed_lang']) > 1):

			foreach($phpwcms['allowed_lang'] as $lang):

				$lang = strtolower($lang);

				if($lang == $phpwcms['default_lang']) {
					continue;
				}

				if(!isset($file_vars[$lang]['longinfo'])) {
					$file_vars[$lang]['longinfo'] = '';
				}
				if(!isset($file_vars[$lang]['copyright'])) {
					$file_vars[$lang]['copyright'] = '';
				}

?>

	<tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
		<td align="right" valign="top" class="v09 tdtop5"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_ftptakeover_longinfo'] ?> (<?php echo strtoupper($lang) ?>):&nbsp;</td>
		<td valign="top tdbottom3"><textarea name="file_longinfo_<?php echo $lang ?>" cols="40" rows="4" class="width400" id="file_longinfo_<?php echo $lang ?>"><?php echo html($file_vars[$lang]['longinfo']) ?></textarea></td>
	</tr>
	<tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
		<td align="right" class="v09"><?php echo $BL['be_copyright'] ?> (<?php echo strtoupper($lang) ?>):&nbsp;</td>
		<td><input name="file_copyright_<?php echo $lang ?>" type="text" id="file_copyright_<?php echo $lang ?>" size="40" class="width400" maxlength="255" value="<?php echo html($file_vars[$lang]['copyright']) ?>" /></td>
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
						$k .= "<option value=\"".$krow["fkey_id"]."\"";
						$k .= isset($file_keywords[$row["fcat_id"]]) && $file_keywords[$row["fcat_id"]] == $krow["fkey_id"] ? " selected" : "";
						$k .= ">".html($krow["fkey_name"])."</option>\n";
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
			<td><input name="file_shortinfo" type="text" class="width300" id="file_shortinfo" value="<?php echo html($file_shortinfo) ?>" size="40" maxlength="250" /></td>
		</tr>
		</table></td>
	</tr>

	<tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

	<tr bgcolor="#F5F8F9">
		<td align="right" class="v09">&nbsp;<?php echo $BL['be_tags'] ?>:&nbsp;</td>
		<td><input type="text" id="file_tags_autosuggest" /><input name="file_tags" type="hidden" id="file_tags" value="<?php echo html($file_tags) ?>" /></td>
	</tr>


	<tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	<tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1" /></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

	<tr>
		<td align="right" class="v09">&nbsp;<?php echo $BL['be_cnt_sorting'] ?>:&nbsp;</td>
		<td><input name="file_sort" type="text" id="file_sort" size="10" class="width50" maxlength="10" value="<?php echo intval($file_sort) ?>" /></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>


	<tr>
		<td align="right" class="v09 tdtop3"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<tr>
			<td><input name="file_aktiv" type="checkbox" id="file_aktiv" value="1"<?php is_checked("1", $file_aktiv) ?> /></td>
			<td class="v10"><strong><label for="file_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

			<td><input name="file_granted" type="checkbox" id="file_granted" value="1"<?php is_checked("1", $file_granted) ?>></td>
			<td class="v10"><label for="file_granted"><?php echo $BL['be_granted_download'] ?></label></td>
		</tr>
		<tr>
			<td><input name="file_public" type="checkbox" id="file_public" value="1"<?php is_checked("1", $file_public) ?> /></td>
			<td class="v10"><strong><label for="file_public"><?php echo $BL['be_ftptakeover_public'] ?></label></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

			<td><input name="file_gallerydownload" type="checkbox" id="file_gallerydownload" value="1"<?php is_checked(1, $file_gallerydownload) ?>></td>
			<td class="v10"><label for="file_gallerydownload"><?php echo $BL['be_gallerydownload'] ?></label></td>
		</tr>
		</table></td>
	</tr>

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

	<tr>
		<td valign="top"><input name="file_aktion" type="hidden" id="file_aktion" value="1" />
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php

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

		?>" /></td>
		<td>
			<input name="Submit" type="submit" class="button" value="<?php echo $BL['be_fprivup_button'] ?>" />
			<input type="button" class="button" value="<?php echo $BL['be_func_struct_close'] ?>" onclick="document.location.href='phpwcms.php?do=files&amp;f=0'" />
		</td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
	<tr><td colspan="2" bgcolor="#9BBECA"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
</table>
</form>
<script type="text/javascript">
<!--

$(function(){

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

	$('#uploadfile').submit(function(){
		$("#file_tags").val($('#as-values-keyword-autosuggest').val());
	});

<?php	if(count($phpwcms['allowed_lang']) > 1): ?>

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

<?php	endif; ?>

});

//-->
</script>