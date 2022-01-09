<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


//file list

if(empty($content["file_descr"])) $content["file_descr"] = '';
$content['file']['direct_download'] = empty($content['file']['direct_download']) ? 0 : 1;

?>

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
	<td><select name="cfile_template" id="cfile_template">

<?php

echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for recipes
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/filelist');
if(is_array($tmpllist) && count($tmpllist)) {
	foreach($tmpllist as $val) {
		if(isset($content['file_template']) && $val == $content['file_template']) {
			$selected_val = ' selected="selected"';
		} else {
			$selected_val = '';
		}
		$val = htmlspecialchars($val);
		echo '	<option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
	}
}

if(is_file(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.'.htaccess') && ($content['file']['direct_download_deny'] = file_get_contents(PHPWCMS_ROOT.'/'.PHPWCMS_FILES.'.htaccess'))) {
    $content['file']['direct_download_deny'] = strtolower($content['file']['direct_download_deny']);
    if(strpos($content['file']['direct_download_deny'], 'deny') !== false) {
        $content['file']['direct_download_deny'] = true;
    } else {
        $content['file']['direct_download_deny'] = false;
    }
} else {
    $content['file']['direct_download_deny'] = false;
}


?>
		</select></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_download'] ?>:&nbsp;</td>
    <td><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
            <td bgcolor="#E7E8EB"><input name="cfile_direct" id="cfile_direct" type="checkbox" value="1" <?php
                is_checked(1, $content['file']['direct_download']);
                if($content['file']['direct_download_deny'] && !$content['file']['direct_download']) {
                    echo ' disabled="disabled"';
                }
            ?> /></td>
            <td class="v10" bgcolor="#E7E8EB"><label for="cfile_direct">&nbsp;<?php echo $BL['be_cnt_download_direct'] ?></label>&nbsp;&nbsp;</td>
            <td bgcolor="#E7E8EB"><img src="img/leer.gif" alt="" width="1" height="22"></td>
            <?php
                if($content['file']['direct_download_deny']) {
                    echo '<td class="v10 error">&nbsp;';
                    printf($BL['be_filedownload_direct_blocked'], PHPWCMS_ROOT.'/'.PHPWCMS_FILES.'.htaccess');
                    echo '</td>';
                }
            ?>
        </tr>
    </table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>

<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_files'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
	<tr>
	    <td valign="top"><select name="cfile_list[]" size="8" multiple class="width300" id="cfile_list">
<?php

        if(isset($content["file_list"]) && is_array($content["file_list"]) && count($content["file_list"])) {
    		$fx  = 0;
    		$fxa = "";
    		$fxb = array();
    		foreach($content["file_list"] as $key => $value) {
    			if($fx) $fxa .= " OR ";
    			$fxa .= "f_id=".intval($value);
    			$fxb[$key]["fid"] = intval($value);
    			$fx++;
    		}
    		if($fx) {
    			$file_sql = "SELECT f_id, f_name FROM ".DB_PREPEND."phpwcms_file WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND (".$fxa.")";
    			$file_result = _dbQuery($file_sql);
    			if(isset($file_result[0]['f_id'])) {
    				foreach($file_result as $file_row) {
    					foreach($fxb as $key => $value) {
    						if($fxb[$key]["fid"] == $file_row['f_id']) {
    							$fxb[$key]["fname"] = html($file_row['f_name']);
    						}
    					}
    				}
    			}
    			foreach($fxb as $key => $value) {
    				if(!empty($fxb[$key]["fname"])) {
    					echo "<option value=\"".$fxb[$key]["fid"]."\">".$fxb[$key]["fname"]."</option>\n";
    				}
    			}
    			unset($fxb, $content["file_list"]);
    		}
	    }

?>
	    </select></td>
	    <td valign="top"><img src="img/leer.gif" alt="" width="5" height="1"></td>                                           <!-- browser_file.php //-->
	    <td valign="top"><a href="javascript:;" title="<?php echo $BL['be_cnt_openfilebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=4&amp;target=nolist')"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0"></a><br />
	        <img src="img/leer.gif" alt="" width="1" height="4"><br />
            <a href="javascript:;" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cfile_list);"><img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0"></a><a href="javascript:;" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cfile_list);"><img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0"></a><br />
            <img src="img/leer.gif" alt="" width="1" height="4"><br />
            <a href="javascript:;" onclick="removeSelectedOptions(document.articlecontent.cfile_list);" title="<?php echo $BL['be_cnt_delfile'] ?>"><img src="img/button/del_image_button1.gif" alt="" width="20" height="15" border="0"></a></td>
    </tr>
  </table></td>
  </tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
  <td valign="top">
  	<textarea name="cfile_descr" cols="40" rows="5" class="width440 autosize" id="cfile_descr"><?php

	if(!empty($content["file_descr"]) && (substr($content["file_descr"], 0, 1) === "\r" || substr($content["file_descr"], 0, 1) === "\n")) {
		echo ' ';
	}
	echo html($content["file_descr"]);

  ?></textarea>
  	<span class="caption width440">
		<?php echo $BL['be_cnt_description']; ?>
		|
		<?php echo $BL['be_fprivedit_filename']; ?>
		|
		<?php echo $BL['be_caption_file_title']; ?>
		|
		<?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
		|
		<?php echo $BL['be_caption_file_imagesize']; ?>
		|
		<?php echo $BL['be_copyright']; ?>&nbsp;&crarr;&nbsp;&hellip;
	</span>
  </td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr><td colspan="2" align="center"><?php

$wysiwyg_editor = array(
	'value'		=> isset($content["html"]) ? $content["html"] : '',
	'field'		=> 'chtml',
	'height'	=> '250px',
	'width'		=> '100%',
	'rows'		=> '15',
	'editor'	=> $_SESSION["WYSIWYG_EDITOR"],
	'lang'		=> 'en'
);

include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';


?></td></tr>
