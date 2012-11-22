<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2012, Oliver Georgi
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


//Wenn neue Datei hochgeladen werden soll
$file_id			= isset($_GET["editfile"]) ? intval($_GET["editfile"]) : 0;
$file_ext			= '';
$ja					= 0;
$file_thumb_small	= '';
				
//Auswerten des Formulars
if(isset($_POST["file_aktion"]) && intval($_POST["file_aktion"]) == 2) {
	$file_id				= intval($_POST["file_id"]);
	$file_pid 				= intval($_POST["file_pid"]);
	$file_aktiv				= empty($_POST["file_aktiv"]) ? 0 : 1;
	$file_public 			= empty($_POST["file_public"]) ? 0 : 1;
	$file_name				= clean_slweg($_POST["file_name"]);
	$file_ext				= clean_slweg($_POST["file_ext"]);
	$file_shortinfo			= clean_slweg($_POST["file_shortinfo"]);
	$file_longinfo			= slweg($_POST["file_longinfo"]);
	$file_copyright			= clean_slweg($_POST["file_copyright"]);
	$file_tags				= trim( clean_slweg($_POST["file_tags"]), ',' );
	$file_granted			= empty($_POST["file_granted"]) ? 0 : 1;
	$file_gallerydownload	= empty($_POST["file_gallerydownload"]) ? 0 : 1;
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

	
	$file_keywords			= $_POST["file_keywords"];
	if(count($file_keywords)) {
		$file_keys = "";
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
	
	//if(isEmpty($file_shortinfo)) $file_error["shortinfo"] = 1;
	if(empty($file_name)) {
		$file_error["name"] = 1;
	} else {
		//Wenn Dateiname keine Erweiterung hat, dann Extension anhängen
		if(trim(strtolower(FileExtension($file_name))) != trim($file_ext)) $file_name .= ".".$file_ext;
	}
	//Eintragen der aktualisierten Verzeichnisinfos
	if(!isset($file_error)) {
		$sql =  "UPDATE ".DB_PREPEND."phpwcms_file SET ".
				"f_name='".aporeplace($file_name)."', ".
				"f_pid=".$file_pid.", ".
				"f_aktiv=".$file_aktiv.", ".
				"f_public=".$file_public.", ".
				"f_shortinfo='".aporeplace($file_shortinfo)."', ".
				"f_longinfo='".aporeplace($file_longinfo)."', ".
				"f_keywords='".$file_keys."', ".
				"f_created='".time()."', ".
				"f_copyright='".aporeplace($file_copyright)."', ".
				"f_tags='".aporeplace($file_tags)."', ".
				"f_granted=".$file_granted.", ".
				"f_gallerystatus=".$file_gallerydownload.", ".
				(isset($file_vars) ? 'f_vars='._dbEscape(serialize($file_vars)).',' : '').
				"f_sort=".$file_sort." ".
				"WHERE f_kid=1 AND f_id=".$file_id;
				if(empty($_SESSION["wcs_user_admin"])) {
					$sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
				}
		if($result = mysql_query($sql, $db)) {
		
			// store tags
			_dbSaveCategories($file_tags, 'file', $file_id, ',');
		
			//headerRedirect(PHPWCMS_URL."phpwcms.php?do=files&f=0");
		} else {
			
			$file_error["save_failed"] = 1;
			
		}
	}	
}
//Ende Auswerten Formular

//Wenn ID angegeben, dann -> oder aber Root Verzeichnis
if($file_id) {
	$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE f_id=".$file_id;
	if(empty($_SESSION["wcs_user_admin"])) {
		$sql .= " AND f_uid=".intval($_SESSION["wcs_user_id"]);
	}
	$sql .= " AND f_trash=0 AND f_kid=1 LIMIT 1";
	if($result = mysql_query($sql, $db) or die("error while reading file information")) {
		if($row = mysql_fetch_array($result)) {
			$file_oldname	= html_specialchars($row["f_name"]);
			$file_created	= intval($row["f_created"]);
			$file_size		= intval($row["f_size"]);
			$file_id		= $row["f_id"];
			$file_ext		= $row["f_ext"];
			//$file_makethumb	= "thumb=".$row["f_id"]."&ext=".$row["f_ext"];
			if(empty($_POST["file_aktion"]) || intval($_POST["file_aktion"]) != 2) {
				$file_pid				= $row["f_pid"];
				$file_name				= $row["f_name"];
				$file_aktiv				= $row["f_aktiv"];
				$file_public			= $row["f_public"];
				$file_shortinfo 		= $row["f_shortinfo"];
				$file_longinfo			= $row["f_longinfo"];
				$file_keys				= $row["f_keywords"];
				$file_copyright			= $row["f_copyright"];
				$file_tags				= $row["f_tags"];
				$file_granted			= $row["f_granted"];
				$file_gallerydownload	= $row["f_gallerystatus"];
				$file_sort				= $row["f_sort"];
				$file_vars				= @unserialize($row['f_vars']);
				
				if($file_keys) {
					$file_keys_temp = explode(":", $file_keys);
					if(count($file_keys_temp)) {
						if(isset($file_keywords)) unset($file_keywords);
						foreach($file_keys_temp as $value) {
							list($k1, $k2) = explode("_", $value);
							$file_keywords[intval($k1)] = intval($k2);
						}
					}
				}
			}
			
			if(isset($row["f_hash"])) {
				$thumb_image = get_cached_image(
						array(	'max_width' => 420,
								'max_height' => 250,
								"target_ext"	=>	$row["f_ext"],
								"image_name"	=>	$row["f_hash"] . '.' . $row["f_ext"],
								"thumb_name"	=>	md5($row["f_hash"].'420250'.$phpwcms["sharpen_level"])
        					  )
						);

				if($thumb_image != false) {
					$file_thumb_small = '<img src="'.PHPWCMS_IMAGES . $thumb_image[0] .'" '.$thumb_image[3].' alt="" style="border: 1px solid #9BBECA;background:#F5F8F9;" />';
				}
			}
			
			$ja = 1;
		}
		mysql_free_result($result);
	}
}

				
if($ja) {
?>
<form action="phpwcms.php?do=files&f=0" method="post" name="editfileinfo" id="editfileinfo">
<table border="0" cellpadding="0" cellspacing="0" bgcolor='#EBF2F4' summary="">
	<tr>
		<td rowspan="2" valign="top"><a href="phpwcms.php?do=files&f=0"><img src="img/button/close_reiter.gif" alt="" width="45" height="12" border="0"></a></td>
		<td><img src="img/leer.gif" alt="" width="1" height="6"></td>
	</tr>
	<tr><td class="title"><?php echo $BL['be_fprivedit_title'] ?></td></tr>
	<tr>
	  <td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="5"></td>
	  </tr>
	<tr>
      <td align="right" class="v09">&nbsp;</td>
      <td class="v11"><?php
			
			if($file_thumb_small) {
				echo $file_thumb_small;
			} else {
				echo '<img src="img/icons/small_'.extimg($file_ext).'" border="0" alt="" style="position:relative;top:1px" /> ';
				echo html_specialchars($file_name);
			}
		
		
		?></td>
	  </tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<tr>
      <td><img src="img/leer.gif" alt="" width="1" height="1"></td>
      <td class="v09"><?php 
	  	echo $BL['be_fprivedit_created'].': '.date($BL['be_fprivedit_dateformat'], $file_created);
		echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$BL['be_fprivedit_size'].': '.fsizelong($file_size);
		echo '&nbsp;&nbsp;&nbsp;&nbsp;EXT: '.strtoupper($file_ext); 
	  ?></td>
	</tr>
	<tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
	<tr>
		<td align="right" class="v09"><?php echo $BL['be_ftptakeover_directory'] ?>:&nbsp;</td>
		<td class="v10"><select name="file_pid" id="file_pid" class="width400">
			<option value="0" <?php if($file_pid == 0) echo "selected"; ?>><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
			<?php dir_menu(0, $file_pid, $db, "+", $_SESSION["wcs_user_id"], "+") ?>
		</select></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
	<tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1"></td></tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
	<?php if(isset($file_error["name"])) { 
			$file_name = $file_oldname;
	?>
	<tr>
	  <td align="right" class="v09"><img src="img/leer.gif" alt="" width="1" height="1"></td>
	  <td class="v10"><strong style="color:#FF3300"><?php echo $BL['be_fprivedit_err1'] ?></strong></td>
	</tr>
	<tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
	<?php } ?>
	<tr>
      <td align="right" class="v09"><?php echo $BL['be_fprivedit_filename'] ?>:&nbsp;</td>
      <td><input name="file_name" type="text" class="width400 v12" id="file_name" value="<?php echo html_specialchars($file_name) ?>" size="40" maxlength="230"></td>
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
		<td valign="top tdbottom3"><textarea name="file_longinfo" cols="40" rows="4" class="v12 width400" id="file_longinfo"><?php echo html_specialchars($file_longinfo) ?></textarea></td>
	</tr>
	<tr class="tab-content finfo<?php echo $phpwcms['default_lang'] ?>">
		<td align="right" class="v09"><?php echo $BL['be_copyright'] ?>:&nbsp;</td>
		<td><input name="file_copyright" type="text" id="file_copyright" size="40" class="width400" maxlength="255" value="<?php echo html_specialchars($file_copyright) ?>" /></td>
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
		<td valign="top tdbottom3"><textarea name="file_longinfo_<?php echo $lang ?>" cols="40" rows="4" class="v12 width400" id="file_longinfo_<?php echo $lang ?>"><?php echo html_specialchars($file_vars[$lang]['longinfo']) ?></textarea></td>
	</tr>
	<tr class="tab-content finfo<?php echo $lang ?>" style="display:none">
		<td align="right" class="v09"><?php echo $BL['be_copyright'] ?> (<?php echo strtoupper($lang) ?>):&nbsp;</td>
		<td><input name="file_copyright_<?php echo $lang ?>" type="text" id="file_copyright_<?php echo $lang ?>" size="40" class="width400" maxlength="255" value="<?php echo html_specialchars($file_vars[$lang]['copyright']) ?>" /></td>
	</tr>


<?php		endforeach;
		endif;
?>
	
	<tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
	<tr><td colspan="2" valign="top"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1"></td></tr>
	<tr bgcolor="#F5F8F9"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

	<?php 
	
	//Auswahlliste vordefinierte Keywörter
	$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_deleted=0 ORDER BY fcat_sort, fcat_name";
	if($result = mysql_query($sql, $db) or die("error while browsing file categories for selecting keywords")) {
		$k = "";
		while($row = mysql_fetch_array($result)) {
			if(get_filecat_childcount ($row["fcat_id"], $db)) {
			
				$ke = isset($file_error["keywords"][$row["fcat_id"]]) ? '<img src="img/symbole/error.gif" width="8" height="9" alt="" />&nbsp;' : '';
				$k .= "<tr>\n<td class=\"f10b\">".$ke.html_specialchars($row["fcat_name"]).":&nbsp;</td>\n";
				$k .= "<td><select name=\"file_keywords[".$row["fcat_id"]."]\" class=\"width300\">\n";
				$k .= "<option value=\"".(($row["fcat_needed"])?"0_".$row["fcat_needed"]."\">".$BL['be_ftptakeover_needed']:'0">'.$BL['be_ftptakeover_optional'])."</option>\n";
				
				$ksql = "SELECT * FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_deleted=0 AND fkey_cid=".$row["fcat_id"]." ORDER BY fkey_name";
				if($kresult = mysql_query($ksql, $db) or die("error while listing file keywords")) {
					while($krow = mysql_fetch_array($kresult)) {
						$k .= "<option value=\"".$krow["fkey_id"]."\"";
						$k .= isset($file_keywords[$row["fcat_id"]]) && $file_keywords[$row["fcat_id"]] == $krow["fkey_id"] ? " selected" : "";
						$k .= ">".html_specialchars($krow["fkey_name"])."</option>\n";
					}
					mysql_free_result($kresult);
				}
				
				$k .= "</select></td>\n</tr>\n";
				$k .= "<tr>\n<td colspan=\"2\"><img src=\"img/leer.gif\" width=\"1\" height=\"2\"></td>\n</tr>\n";			
			
			}
		}
		mysql_free_result($result);
	}

	?>
	<tr bgcolor="#F5F8F9">
		<td align="right" valign="top" class="v09 tdtop5"><?php echo $BL['be_ftptakeover_keywords'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		<?php if($k) echo $k; ?>
		<tr>
			<td class="f10b"><?php echo $BL['be_ftptakeover_additional'] ?>:&nbsp;</td>
			<td><input name="file_shortinfo" type="text" class="width300" id="file_shortinfo" value="<?php echo html_specialchars($file_shortinfo) ?>" size="40" maxlength="250"></td>
		</tr>		
		</table></td>
	</tr>
	
	<tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	
	<tr bgcolor="#F5F8F9">
		<td align="right" class="v09">&nbsp;<?php echo $BL['be_tags'] ?>:&nbsp;</td>
		<td><input type="text" id="file_tags_autosuggest" /><input name="file_tags" type="hidden" id="file_tags" value="<?php echo html_specialchars($file_tags) ?>" /></td>
	</tr>
	
	<tr bgcolor="#F5F8F9"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
	<tr><td colspan="2"><img src="img/lines/line-bluelight.gif" alt="" width="538" height="1"></td></tr>
	
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
	

	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
	<tr>
		<td valign="top">
			<input name="file_id" type="hidden" id="file_id" value="<?php echo $file_id ?>" />
			<input name="file_aktion" type="hidden" id="file_aktion" value="2" />
			<input name="file_ext" type="hidden" id="file_ext" value="<?php echo strtolower($file_ext) ?>" />
		</td>
		<td>
			<input name="Submit" type="submit" class="button10" value="<?php echo $BL['be_fprivedit_button'] ?>" />
			<input type="button" class="button10" value="<?php echo $BL['be_func_struct_close'] ?>" onclick="document.location.href='phpwcms.php?do=files&amp;f=0'" />
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
	
	$('#editfileinfo').submit(function(){
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

<?php 
} 

?>