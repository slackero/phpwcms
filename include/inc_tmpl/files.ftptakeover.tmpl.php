<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// initialize Mootools for autocomplete
initMootoolsAutocompleter();


?>
<form action="include/inc_act/act_ftptakeover.php" method="post" name="ftptakeover" id="ftptakeover">
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
		<tr><td colspan="6" class="title"><?php echo $BL['be_ftptakeover_title'] ?></td></tr>
		<tr><td colspan="6" class="title"><img src="img/leer.gif" alt="" width="1" height="4" /></td>
		</tr>
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
					
					if($file != "." && $file != "..") {
						if(!is_dir($file) && $fxs = filesize(PHPWCMS_ROOT.$phpwcms["ftp_path"].$file)) {
							$fxb = ($fx % 2) ? " bgColor=\"#F9FAFB\"" : "";
							//$fxs = filesize(PHPWCMS_ROOT.$phpwcms["ftp_path"].$file);
							$fxsg += $fxs;
							$fxe = extimg(which_ext($file));
		  ?>
          <tr<?php echo $fxb?>>
            <td align="center"><input name="ftp_mark[<?php echo $fx ?>]" type="checkbox" id="ftp_mark_<?php echo $fx ?>" value="1" class="ftp_mark" /></td>
            <td bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="17" /></td>
            <td align="center"><img src="img/icons/small_<?php echo $fxe ?>" alt="" width="13" height="11" /></td>
            <td class="v10"><?php echo html_specialchars($file) ?></td>
			
            <td bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
            <td align="right" class="v10">
				<?php echo fsizelong($fxs) ?>&nbsp;
				<input name="ftp_file[<?php echo $fx ?>]" type="hidden" value="<?php echo base64_encode($file) ?>" />
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
		  
          <tr bgcolor="#F9FAFB">
            <td align="center" class="subnavactive"><input name="toggle" type="checkbox" id="toggle" value="1" title="<?php echo $BL['be_ftptakeover_all'] ?>" /></td>
            <td bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="17" /></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
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
        ?><table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
          <tr bgcolor="#D9DEE3"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
          </tr>
            <tr bgcolor="#D9DEE3">
              <td width="67" align="right" class="v09"><?php echo $BL['be_ftptakeover_directory'] ?>:&nbsp;</td>
              <td width="471" class="v10"><select name="file_dir" id="file_dir" class="v11 width400">
                  <option value="0"><?php echo $BL['be_ftptakeover_rootdir'] ?></option>
                  <?php dir_menu(0, 0, $db, "+", $_SESSION["wcs_user_id"], "+") ?>
                 </select></td>
            </tr>
            <tr bgcolor="#D9DEE3"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
          </tr>
 			<tr bgcolor="#D9DEE3"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="538" height="1" /></td>
 			</tr>
            <tr bgcolor="#F9FAFB"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
            </tr>
            <?php 
	
	//Auswahlliste vordefinierte Keywörter
	$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_filecat WHERE fcat_deleted=0 ORDER BY fcat_name;";
	if($result = mysql_query($sql, $db) or die("error while browsing file categories for selecting keywords")) {
		$k = "";
		while($row = mysql_fetch_array($result)) {
			if(get_filecat_childcount ($row["fcat_id"], $db)) {
			
				$ke = '';
				if(!empty($file_error["keywords"][$row["fcat_id"]])) {
					$ke = "<img src=\"img/symbole/error.gif\" width=\"8\" height=\"9\" alt=\"\" />&nbsp;";
				}
				$k .= "<tr>\n<td class=\"f10b\">".$ke.html_specialchars($row["fcat_name"]).":&nbsp;</td>\n";
				$k .= "<td><select name=\"file_keywords[".$row["fcat_id"]."]\" class=\"v11\">\n";
				$k .= "<option value=\"".(($row["fcat_needed"])?"0_".$row["fcat_needed"]."\">".$BL['be_ftptakeover_needed']:'0">'.$BL['be_ftptakeover_optional'])."</option>\n";
				
				$ksql = "SELECT * FROM ".DB_PREPEND."phpwcms_filekey WHERE fkey_deleted=0 AND fkey_cid=".$row["fcat_id"]." ORDER BY fkey_name;";
				if($kresult = mysql_query($ksql, $db) or die("error while listing file keywords")) {
					while($krow = mysql_fetch_array($kresult)) {
						$k .= "<option value=\"".$krow["fkey_id"]."\"";
						if(isset($file_keywords[$row["fcat_id"]]) && $file_keywords[$row["fcat_id"]] == $krow["fkey_id"]) {
							$k .= " selected";
						}
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
	<tr bgcolor="#F9FAFB">
              <td align="right" valign="top" class="v09"><img src="img/leer.gif" alt="" width="1" height="13" /><?php echo $BL['be_ftptakeover_keywords'] ?>:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                  <?php if($k) echo $k; ?>
                  <tr>
                    <td class="f10b"><?php echo $BL['be_ftptakeover_additional'] ?>:&nbsp;</td>
                    <td><input name="file_shortinfo" type="text" class="v11 width300" id="file_shortinfo" size="40" maxlength="250" /></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr bgcolor="#F9FAFB"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
            </tr>
             <tr bgcolor="#D9DEE3"><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="538" height="1" /></td>
            </tr>
            <tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
            </tr>
            <tr>
              <td align="right" valign="top" class="v09"><img src="img/leer.gif" alt="" width="1" height="13" /><?php echo $BL['be_ftptakeover_longinfo'] ?>:&nbsp;</td>
              <td valign="top"><textarea name="file_longinfo" cols="40" rows="10" class="v11 width400" id="file_longinfo"></textarea></td>
            </tr>
			
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
	

	<tr>
		<td align="right" class="v09"><?php echo $BL['be_copyright'] ?>:&nbsp;</td>
		<td><input name="file_copyright" type="text" id="file_copyright" size="40" class="v11 width400" maxlength="255" value="" /></td>
	</tr>	
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	
	<tr>
		<td align="right" class="v09">&nbsp;<?php echo $BL['be_tags'] ?>:&nbsp;</td>
		<td><input name="file_tags" type="text" id="file_tags" size="40" class="v11 width400" maxlength="255" value="" /></td>
	</tr>
	
	

			
            <tr><td colspan="2" valign="top"><img src="img/leer.gif" alt="" width="1" height="9" /></td>
            </tr>
            <tr>
              <td align="right" class="v09"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
              <td><table border="0" cellpadding="1" cellspacing="0" bgcolor="#E6EAED" summary="">
                  <tr>
                    <td><input name="file_aktiv" type="checkbox" id="file_aktiv" value="1" /></td>
                    <td class="v10"><strong><label for="file_aktiv"><?php echo $BL['be_ftptakeover_active'] ?></label></strong>&nbsp;&nbsp;</td>
                    <td><input name="file_public" type="checkbox" id="file_public" value="1" /></td>
                    <td class="v10"><strong><label for="file_public"><?php echo $BL['be_ftptakeover_public'] ?></label></strong>&nbsp;&nbsp;</td>
					<td bgcolor="#FFFFFF"><img src="img/leer.gif" width="5" height="1" alt="" /></td>
					<td><input name="file_replace" type="checkbox" id="file_replace" value="1" /></td>
                    <td class="v10"><strong><label for="file_replace"><?php echo $BL['be_file_replace'] ?></label></strong></td>
					
                    <td class="v10"><img src="img/leer.gif" alt="" width="10" height="19" /></td>
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
<?php } ?>
</form>
<script type="text/javascript">
<!--

window.addEvent('domready', function(){
									 
	/* Autocompleter for keywords (=tags) */
	var searchKeyword = $('file_tags');
	var indicator = new Element('span', {'class': 'autocompleter-loading', 'styles': {'display': 'none'}}).setHTML('').injectAfter(searchKeyword);
	var completer = new Autocompleter.Ajax.Json(searchKeyword, 'include/inc_act/ajax_connector.php', {
		multi: true,
		maxChoices: 30,
		autotrim: true,
		minLength: 0,
		allowDupes: false,
		postData: {action: 'category', method: 'json'},
		onRequest: function(el) {
			indicator.setStyle('display', '');
		},
		onComplete: function(el) {
			indicator.setStyle('display', 'none');
		}
	});
	
	$('toggle').addEvent('click', function() {
	
		var toggle_var = this.checked ? 1 : 0;
	
		$$('input.ftp_mark').each( function(el) {
		
			el.checked = toggle_var ? true : false;
		
		});
	
	});
	

});


//-->
</script>
