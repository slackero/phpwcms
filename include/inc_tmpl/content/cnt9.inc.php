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


//multimedia

if(!isset($content["media_type"])) {
    $content["media_type"] = 0;
}
if(!isset($content["media_player"])) {
    $content["media_player"] = 0;
}
if(!isset($content["media_auto"])) {
    $content["media_auto"] = 0;
}
if(!isset($content["media_transparent"])) {
    $content["media_transparent"] = 0;
}
if(!isset($content["media_src"])) {
    $content["media_src"] = 0;
}
if(!isset($content["media_pos"])) {
     $content["media_pos"] = 0;
}
if(empty($content["image_name"])) {
    $content["image_name"] = '';
}
if(empty($content["image_id"])) {
    $content["image_id"] = '';
}
if(empty($content["image_caption"])) {
    $content["image_caption"] = '';
}

?>

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
    <td><select name="template" id="template">
<?php

    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/multimedia');
if(is_array($tmpllist) && count($tmpllist)) {
    foreach($tmpllist as $val) {
        $selected_val = (isset($content["template"]) && $val == $content["template"]) ? ' selected="selected"' : '';
        $val = html($val);
        echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
    }
}

?>
    </select></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
          <td align="right" class="chatlist"><?php echo $BL['be_cnt_mediatype']  ?>:&nbsp;</td>
          <td bgcolor="#E7E8EB"><input name="cmedia_type" type="radio" value="0" <?php is_checked(0, $content["media_type"]); ?> onchange="if(cmedia_player[3].checked) cmedia_player[0].click();" /></td>
          <td bgcolor="#E7E8EB" class="v10">Video</td>
          <td bgcolor="#E7E8EB"><input name="cmedia_type" type="radio" value="1" <?php is_checked(1, $content["media_type"]); ?> onchange="if(cmedia_player[3].checked) cmedia_player[0].click();" /></td>
          <td bgcolor="#E7E8EB" class="v10">Audio</td>
          <td bgcolor="#E7E8EB"><input name="cmedia_type" type="radio" value="2" <?php is_checked(2, $content["media_type"]); ?> onchange="cmedia_player[3].click();" /></td>
          <td bgcolor="#E7E8EB" class="v10">Flash</td>
          <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
          </tr>
        <tr>
          <td><img src="img/leer.gif" alt="" width="80" height="1" /></td>
          <td colspan="8"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
          </tr>
        <tr>
          <td align="right" class="chatlist">&nbsp;</td>
          <td bgcolor="#E7E8EB"><input name="cmedia_player" type="radio" value="0" <?php is_checked(0, $content["media_player"]); ?> onchange="if(cmedia_type[2].checked) cmedia_type[0].click();" /></td>
          <td bgcolor="#E7E8EB" class="v10">Quicktime&nbsp;</td>
          <td bgcolor="#E7E8EB"><input name="cmedia_player" type="radio" value="1" <?php is_checked(1, $content["media_player"]); ?> onchange="if(cmedia_type[2].checked) cmedia_type[0].click();" /></td>
          <td bgcolor="#E7E8EB" class="v10">RealPlayer&nbsp;</td>
          <td bgcolor="#E7E8EB"><input name="cmedia_player" type="radio" value="2" <?php is_checked(2, $content["media_player"]); ?> onchange="if(cmedia_type[2].checked) cmedia_type[0].click();" /></td>
          <td bgcolor="#E7E8EB" class="v10">MediaPlayer&nbsp;</td>
          <td bgcolor="#E7E8EB"><input name="cmedia_player" type="radio" value="3" <?php is_checked(3, $content["media_player"]); ?> onchange="cmedia_type[2].click();" /></td>
          <td bgcolor="#E7E8EB" class="v10">Flash&nbsp;Plugin&nbsp;&nbsp;</td>
          </tr>
       <tr><td colspan="9"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
        <tr>
          <td align="right" class="chatlist"><?php echo $BL['be_cnt_control']  ?>:&nbsp;</td>
          <td bgcolor="#E7E8EB"><input name="cmedia_control" type="checkbox" id="cmedia_control" value="1" <?php is_checked(1, $content["media_control"]); ?> /></td>
          <td colspan="3" bgcolor="#E7E8EB" class="v10"><?php echo $BL['be_cnt_showcontrol'] ?></td>
          <td bgcolor="#E7E8EB"><input name="cmedia_auto" type="checkbox" id="cmedia_auto" value="1" <?php is_checked(1, $content["media_auto"]); ?> /></td>
          <td bgcolor="#E7E8EB" class="v10"><?php echo $BL['be_cnt_autoplay'] ?></td>
          <td bgcolor="#E7E8EB"><input name="cmedia_transparent" type="checkbox" id="cmedia_transparent" value="1" <?php is_checked(1, $content["media_transparent"]); ?> /></td>
          <td bgcolor="#E7E8EB" class="v10"><?php echo $BL['be_cnt_transparent'] ?>&nbsp;&nbsp;</td>
          </tr>
        </table>
    </td>
    </tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
<tr>
  <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" summary="">
    <tr>
      <td align="right" class="chatlist"><?php echo $BL['be_cnt_source'] ?>:&nbsp;</td>
      <td bgcolor="#E7E8EB"><input name="cmedia_src" type="radio" value="0" <?php is_checked(0, $content["media_src"]); ?> /></td>
      <td bgcolor="#E7E8EB" class="v10"><?php echo $BL['be_cnt_internal'] ?>&nbsp;&nbsp;</td>
      <td><img src="img/leer.gif" alt="" width="6" height="1" /></td>
      <td><input name="cmedia_name" type="text" id="cmedia_name" class="f11b" style="width: 300px; color: #727889;" value="<?php echo  isset($content["media_name"]) ? html($content["media_name"]) : '' ?>" size="40" onfocus="this.blur()" /></td>

      <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="javascript:;" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=2&amp;target=nolist')"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
      <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="javascript:;" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="document.articlecontent.cmedia_name.value='';document.articlecontent.cmedia_id.value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
      <td><input name="cmedia_id" type="hidden" id="cmedia_id2" value="<?php echo  isset($content["media_id"]) ? $content["media_id"] : '' ?>" /></td>
    </tr>
    <tr>
      <td><img src="img/leer.gif" alt="" width="80" height="1" /></td>
      <td colspan="7"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
    </tr>
    <tr>
      <td align="right" class="chatlist">&nbsp;</td>
      <td bgcolor="#E7E8EB"><input name="cmedia_src" type="radio" value="1" <?php is_checked(1, $content["media_src"]); ?> /></td>
      <td bgcolor="#E7E8EB" class="v10"><?php echo $BL['be_cnt_external'] ?>&nbsp;&nbsp;</td>
      <td><img src="img/leer.gif" alt="" width="6" height="1" /></td>
      <td colspan="4"><input name="cmedia_extern" type="text" id="cmedia_extern" class="f11b" style="width: 300px; color: #727889;" value="<?php echo  isset($content["media_extern"]) ? html($content["media_extern"]) : '' ?>" size="40" /></td>
      </tr>
  </table></td>
  </tr>
  <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_cnt_position'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><select name="cimage_pos" id="cimage_pos" onchange="changeImagePosMenu();">
                <option value="0" <?php is_selected(0, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos0'] ?></option>
                <option value="1" <?php is_selected(1, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos1'] ?></option>
                <option value="2" <?php is_selected(2, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos2'] ?></option>
                <option value="3" <?php is_selected(3, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos3'] ?></option>
                <option value="4" <?php is_selected(4, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos4'] ?></option>
              </select></td>
                  <td><img src="img/leer.gif" alt="" width="3" height="1" /></td>
                  <td><img src="img/symbole/content_selected.gif" alt="" name="imgpos0" width="7" height="10" id="imgpos0" /></td>
                  <td><a href="javascript:;" onclick="changeImagePos(0);this.blur();return false;" title="<?php echo $BL['be_cnt_mediapos0i'] ?>"><img src="img/button/image_pos0.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos1" width="7" height="10" id="imgpos1" /></td>
                  <td><a href="javascript:;" onclick="changeImagePos(1);this.blur();return false;" title="<?php echo $BL['be_cnt_mediapos1i'] ?>"><img src="img/button/image_pos1.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos2" width="7" height="10" id="imgpos2" /></td>
                  <td><a href="javascript:;" onclick="changeImagePos(2);this.blur();return false;" title="<?php echo $BL['be_cnt_mediapos2i'] ?>"><img src="img/button/image_pos2.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos3" width="7" height="10" id="imgpos3" /></td>
                  <td><a href="javascript:;" onclick="changeImagePos(3);this.blur();return false;" title="<?php echo $BL['be_cnt_mediapos3i'] ?>"><img src="img/button/image_pos6.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos4" width="7" height="10" id="imgpos4" /></td>
                  <td><a href="javascript:;" onclick="changeImagePos(4);this.blur();return false;" title="<?php echo $BL['be_cnt_mediapos4i'] ?>"><img src="img/button/image_pos7.gif" alt="" width="15" height="15" border="0" /></a></td>
                </tr>
              </table></td>
              </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_width'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><input name="cmedia_width" type="text" class="f11b" id="cmedia_width" style="width: 50px;" size="3" maxlength="5" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo  isset($content["media_width"]) ? $content["media_width"] : '' ?>" /></td>
                  <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_admin_page_height'] ?>:&nbsp;</td>
                  <td><input name="cmedia_height" type="text" class="f11b" id="cmedia_height" style="width: 50px;" size="3" maxlength="5" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo  isset($content["media_height"]) ? $content["media_height"] : '' ?>" /></td>
                  <td class="chatlist">&nbsp;px</td>
                  <td><img src="img/leer.gif" alt="" width="30" height="1" /></td>
                  <td class="chatlist"><?php echo $BL['be_cnt_setsize'] ?>:&nbsp;</td>
                  <td><a href="javascript:;" title="<?php echo $BL['be_cnt_set1'] ?>" onclick="document.articlecontent.cmedia_width.value='160';document.articlecontent.cmedia_height.value='120';this.blur();return false;"><img src="img/button/video_160x120.gif" alt="" width="27" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" width="3" height="1" /></td>
                  <td><a href="javascript:;" title="<?php echo $BL['be_cnt_set2'] ?>" onclick="document.articlecontent.cmedia_width.value='240';document.articlecontent.cmedia_height.value='180';this.blur();return false;"><img src="img/button/video_240x180.gif" alt="" width="27" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" width="3" height="1" /></td>
                  <td><a href="javascript:;" title="<?php echo $BL['be_cnt_set3'] ?>" onclick="document.articlecontent.cmedia_width.value='320';document.articlecontent.cmedia_height.value='240';this.blur();return false;"><img src="img/button/video_320x240.gif" alt="" width="27" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" width="3" height="1" /></td>
                  <td><a href="javascript:;" title="<?php echo $BL['be_cnt_set4'] ?>" onclick="document.articlecontent.cmedia_width.value='480';document.articlecontent.cmedia_height.value='360';this.blur();return false;"><img src="img/button/video_480x360.gif" alt="" width="27" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" width="6" height="1" /></td>
                  <td><a href="javascript:;" title="<?php echo $BL['be_cnt_set5'] ?>" onclick="document.articlecontent.cmedia_width.value='';document.articlecontent.cmedia_height.value='';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
                </tr>
</table><script type="text/javascript">
    changeImagePos(<?php echo intval($content["media_pos"]) ?>);
</script></td></tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

<tr>
      <td align="right" class="chatlist"><?php echo $BL['alt_image'] ?>:&nbsp;</td>
      <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
          <td><input name="cimage_name" type="text" id="cimage_name" class="f11b" style="width: 300px; color: #727889;" value="<?php echo html($content["image_name"]) ?>" size="40" maxlength="250" onfocus="this.blur()" /></td>
          <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="javascript:;" title="<?php echo  $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=0&amp;target=nolist')"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
          <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="javascript:;" title="<?php echo  $BL['be_cnt_delimage'] ?>" onclick="document.articlecontent.cimage_name.value='';document.articlecontent.cimage_id.value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>
            <input name="cimage_id" type="hidden" value="<?php echo $content["image_id"] ?>" /></td>
        </tr>
      </table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
  <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['alt_text'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td valign="top"><textarea name="cimage_caption" cols="30" rows="4" class="width300 autosize" id="cimage_caption"><?php echo html($content["image_caption"]) ?></textarea></td>
        <td valign="top"><img src="img/leer.gif" alt="" width="10" height="1" /></td>
        <td valign="top"><?php if($content["image_id"]): ?><img src="<?php echo PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$content["image_id"] ?>" border="0" /><?php endif; ?>&nbsp;</td>
      </tr>
  </table></td>
</tr>
