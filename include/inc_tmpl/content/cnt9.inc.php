<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
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

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="custom-select form-control form-control-sm">
<?php
echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;
// templates for frontend login
$tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/multimedia');
if(is_array($tmpllist) && count($tmpllist)) {
  foreach($tmpllist as $val) {
      $selected_val = (isset($content["template"]) && $val == $content["template"]) ? ' selected="selected"' : '';
      $val = html($val);
      echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
  }
}
?>
    </select>
  </div>
</div>

<div class="form-group form-row">
  <div class="col">
    <table class="table-no-border">
      <tr>
        <td align="right"><?php echo $BL['be_cnt_mediatype']  ?>:&nbsp;</td>
        <td bgcolor="#E7E8EB"><input name="cmedia_type" type="radio" value="0" <?php is_checked(0, $content["media_type"]); ?> onchange="if(cmedia_player[3].checked) cmedia_player[0].click();" /></td>
        <td bgcolor="#E7E8EB">Video</td>
        <td bgcolor="#E7E8EB"><input name="cmedia_type" type="radio" value="1" <?php is_checked(1, $content["media_type"]); ?> onchange="if(cmedia_player[3].checked) cmedia_player[0].click();" /></td>
        <td bgcolor="#E7E8EB">Audio</td>
        <td bgcolor="#E7E8EB"><input name="cmedia_type" type="radio" value="2" <?php is_checked(2, $content["media_type"]); ?> onchange="cmedia_player[3].click();" /></td>
        <td bgcolor="#E7E8EB">Flash</td>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td bgcolor="#E7E8EB"><input name="cmedia_player" type="radio" value="0" <?php is_checked(0, $content["media_player"]); ?> onchange="if(cmedia_type[2].checked) cmedia_type[0].click();" /></td>
        <td bgcolor="#E7E8EB">Quicktime&nbsp;</td>
        <td bgcolor="#E7E8EB"><input name="cmedia_player" type="radio" value="1" <?php is_checked(1, $content["media_player"]); ?> onchange="if(cmedia_type[2].checked) cmedia_type[0].click();" /></td>
        <td bgcolor="#E7E8EB">RealPlayer&nbsp;</td>
        <td bgcolor="#E7E8EB"><input name="cmedia_player" type="radio" value="2" <?php is_checked(2, $content["media_player"]); ?> onchange="if(cmedia_type[2].checked) cmedia_type[0].click();" /></td>
        <td bgcolor="#E7E8EB">MediaPlayer&nbsp;</td>
        <td bgcolor="#E7E8EB"><input name="cmedia_player" type="radio" value="3" <?php is_checked(3, $content["media_player"]); ?> onchange="cmedia_type[2].click();" /></td>
        <td bgcolor="#E7E8EB">Flash&nbsp;Plugin&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><?php echo $BL['be_cnt_control']  ?>:&nbsp;</td>
        <td bgcolor="#E7E8EB"><input name="cmedia_control" type="checkbox" id="cmedia_control" value="1" <?php is_checked(1, $content["media_control"]); ?> /></td>
        <td colspan="3" bgcolor="#E7E8EB"><?php echo $BL['be_cnt_showcontrol'] ?></td>
        <td bgcolor="#E7E8EB"><input name="cmedia_auto" type="checkbox" id="cmedia_auto" value="1" <?php is_checked(1, $content["media_auto"]); ?> /></td>
        <td bgcolor="#E7E8EB"><?php echo $BL['be_cnt_autoplay'] ?></td>
        <td bgcolor="#E7E8EB"><input name="cmedia_transparent" type="checkbox" id="cmedia_transparent" value="1" <?php is_checked(1, $content["media_transparent"]); ?> /></td>
        <td bgcolor="#E7E8EB"><?php echo $BL['be_cnt_transparent'] ?>&nbsp;&nbsp;</td>
      </tr>
    </table>
  </div>
</div>

<div class="form-group form-row">
  <div class="col">
    <table class="table-no-border">
      <tr>
        <td align="right"><?php echo $BL['be_cnt_source'] ?>:&nbsp;</td>
        <td bgcolor="#E7E8EB"><input name="cmedia_src" type="radio" value="0" <?php is_checked(0, $content["media_src"]); ?> /></td>
        <td bgcolor="#E7E8EB"><?php echo $BL['be_cnt_internal'] ?>&nbsp;&nbsp;</td>
        <td><input name="cmedia_name" type="text" id="cmedia_name" class="f11b" style="width: 300px; color: #727889;" value="<?php echo  isset($content["media_name"]) ? html($content["media_name"]) : '' ?>" size="40" onfocus="this.blur()" /></td>
        <td><img src="img/button/open_image_button.gif" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>" alt="<?php echo $BL['be_cnt_openmediabrowser'] ?>" width="20" height="15" border="0" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=2&amp;target=nolist" class="modalButton" /></td>
        <td><a href="javascript:;" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="document.articlecontent.cmedia_name.value='';document.articlecontent.cmedia_id.value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
        <td><input name="cmedia_id" type="hidden" id="cmedia_id2" value="<?php echo  isset($content["media_id"]) ? $content["media_id"] : '' ?>" /></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td bgcolor="#E7E8EB"><input name="cmedia_src" type="radio" value="1" <?php is_checked(1, $content["media_src"]); ?> /></td>
        <td bgcolor="#E7E8EB"><?php echo $BL['be_cnt_external'] ?>&nbsp;&nbsp;</td>
        <td colspan="4"><input name="cmedia_extern" type="text" id="cmedia_extern" class="f11b" style="width: 300px; color: #727889;" value="<?php echo  isset($content["media_extern"]) ? html($content["media_extern"]) : '' ?>" size="40" /></td>
        </tr>
    </table>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo  $BL['be_cnt_position'] ?></label>
  <div class="col-sm-auto">
    <select name="cimage_pos" id="cimage_pos" class="custom-select form-control form-control-sm">
      <option value="0" <?php is_selected(0, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos0'] ?></option>
      <option value="1" <?php is_selected(1, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos1'] ?></option>
      <option value="2" <?php is_selected(2, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos2'] ?></option>
      <option value="3" <?php is_selected(3, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos3'] ?></option>
      <option value="4" <?php is_selected(4, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos4'] ?></option>
    </select>
  </div>
  <div class="col-sm-auto">
    <div id="imgpos0" class="btn btn-sm <?php echo ($content["image_pos"]==0 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos0.gif" alt="" width="15" height="15" border="0"data-toggle="tooltip" title="<?php echo $BL['be_cnt_mediapos0i'] ?>"></div>
    <div id="imgpos1" class="btn btn-sm <?php echo ($content["image_pos"]==1 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos1.gif" alt="" width="15" height="15" border="0"data-toggle="tooltip" title="<?php echo $BL['be_cnt_mediapos1i'] ?>"></div>
    <div id="imgpos2" class="btn btn-sm <?php echo ($content["image_pos"]==2 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos2.gif" alt="" width="15" height="15" border="0"data-toggle="tooltip" title="<?php echo $BL['be_cnt_mediapos2i'] ?>"></div>
    <div id="imgpos3" class="btn btn-sm <?php echo ($content["image_pos"]==3 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos6.gif" alt="" width="15" height="15" border="0"data-toggle="tooltip" title="<?php echo $BL['be_cnt_mediapos3i'] ?>"></div>
    <div id="imgpos4" class="btn btn-sm <?php echo ($content["image_pos"]==4 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos7.gif" alt="" width="15" height="15" border="0"data-toggle="tooltip" title="<?php echo $BL['be_cnt_mediapos4i'] ?>"></div>
  </div>
</div>

<div class="form-group form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_width'] ?></label>
  <div class="col">
    <table class="table-no-border">
      <tr>
        <td><input name="cmedia_width" type="text" class="form-control" id="cmedia_width" style="width: 50px;" size="3" maxlength="5" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo  isset($content["media_width"]) ? $content["media_width"] : '' ?>" /></td>
        <td>&nbsp;&nbsp;<?php echo $BL['be_admin_page_height'] ?>:&nbsp;</td>
        <td><input name="cmedia_height" type="text" class="form-control" id="cmedia_height" style="width: 50px;" size="3" maxlength="5" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo  isset($content["media_height"]) ? $content["media_height"] : '' ?>" /></td>
        <td>&nbsp;px</td>
        <td><?php echo $BL['be_cnt_setsize'] ?>:&nbsp;</td>
        <td><a href="javascript:;" title="<?php echo $BL['be_cnt_set1'] ?>" onclick="document.articlecontent.cmedia_width.value='160';document.articlecontent.cmedia_height.value='120';this.blur();return false;"><img src="img/button/video_160x120.gif" alt="" width="27" height="15" border="0" /></a></td>
        <td><a href="javascript:;" title="<?php echo $BL['be_cnt_set2'] ?>" onclick="document.articlecontent.cmedia_width.value='240';document.articlecontent.cmedia_height.value='180';this.blur();return false;"><img src="img/button/video_240x180.gif" alt="" width="27" height="15" border="0" /></a></td>
        <td><a href="javascript:;" title="<?php echo $BL['be_cnt_set3'] ?>" onclick="document.articlecontent.cmedia_width.value='320';document.articlecontent.cmedia_height.value='240';this.blur();return false;"><img src="img/button/video_320x240.gif" alt="" width="27" height="15" border="0" /></a></td>
        <td><a href="javascript:;" title="<?php echo $BL['be_cnt_set4'] ?>" onclick="document.articlecontent.cmedia_width.value='480';document.articlecontent.cmedia_height.value='360';this.blur();return false;"><img src="img/button/video_480x360.gif" alt="" width="27" height="15" border="0" /></a></td>
        <td><a href="javascript:;" title="<?php echo $BL['be_cnt_set5'] ?>" onclick="document.articlecontent.cmedia_width.value='';document.articlecontent.cmedia_height.value='';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a></td>
      </tr>
    </table>
  </div>
</div>

<div class="form-group form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['alt_image'] ?></label>
  <div class="col">
    <table class="table-no-border">
      <tr>
        <td><input name="cimage_name" type="text" id="cimage_name" class="f11b" style="width: 300px; color: #727889;" value="<?php echo html($content["image_name"]) ?>" size="40" maxlength="250" onfocus="this.blur()" /></td>
        <td><img src="img/button/open_image_button.gif" title="<?php echo  $BL['be_cnt_openimagebrowser'] ?>" alt="<?php echo  $BL['be_cnt_openimagebrowser'] ?>" width="20" height="15" border="0" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=0&amp;target=nolist" class="modalButton" /></td>
        <td><a href="javascript:;" title="<?php echo  $BL['be_cnt_delimage'] ?>" onclick="document.articlecontent.cimage_name.value='';document.articlecontent.cimage_id.value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>
          <input name="cimage_id" type="hidden" value="<?php echo $content["image_id"] ?>" /></td>
      </tr>
    </table>
  </div>
</div>

<div class="form-group form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['alt_text'] ?></label>
  <div class="col">
    <table class="table-no-border">
        <tr>
          <td valign="top"><textarea name="cimage_caption" cols="30" rows="4" class="form-control" id="cimage_caption"><?php echo html($content["image_caption"]) ?></textarea></td>
          <td valign="top"><?php if($content["image_id"]): ?><img src="<?php echo CMSGO_RESIZE_IMAGE.'/'.$cmsgo["img_list_width"].'x'.$cmsgo["img_list_height"].'/'.$content["image_id"] ?>" border="0" /><?php endif; ?>&nbsp;</td>
        </tr>
    </table>
  </div>
</div>
