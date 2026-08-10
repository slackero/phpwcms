<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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

<div class="form-group align-items-center form-row">
  <label for="template" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_struct_template']; ?></label>
  <div class="col-sm-4">
    <select name="template" id="template" class="custom-select form-control form-control-sm">
<?php
echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/multimedia');
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

<div class="form-group align-items-center form-row">
  <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_mediatype'] ?></label>
  <div class="col-sm-10">
    <div class="card card-body bg-light p-2">
      <div class="form-row align-items-center">
        <div class="col-auto">
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="cmedia_type_0" name="cmedia_type" value="0" class="custom-control-input" <?php is_checked(0, $content["media_type"]); ?> onchange="if(document.getElementById('cmedia_player_3').checked) document.getElementById('cmedia_player_0').click();">
            <label class="custom-control-label" for="cmedia_type_0">Video</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="cmedia_type_1" name="cmedia_type" value="1" class="custom-control-input" <?php is_checked(1, $content["media_type"]); ?> onchange="if(document.getElementById('cmedia_player_3').checked) document.getElementById('cmedia_player_0').click();">
            <label class="custom-control-label" for="cmedia_type_1">Audio</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="cmedia_type_2" name="cmedia_type" value="2" class="custom-control-input" <?php is_checked(2, $content["media_type"]); ?> onchange="document.getElementById('cmedia_player_3').click();">
            <label class="custom-control-label" for="cmedia_type_2">Flash</label>
          </div>
        </div>
      </div>
      <hr class="my-2">
      <div class="form-row align-items-center">
        <div class="col-auto">
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="cmedia_player_0" name="cmedia_player" value="0" class="custom-control-input" <?php is_checked(0, $content["media_player"]); ?> onchange="if(document.getElementById('cmedia_type_2').checked) document.getElementById('cmedia_type_0').click();">
            <label class="custom-control-label" for="cmedia_player_0">Quicktime</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="cmedia_player_1" name="cmedia_player" value="1" class="custom-control-input" <?php is_checked(1, $content["media_player"]); ?> onchange="if(document.getElementById('cmedia_type_2').checked) document.getElementById('cmedia_type_0').click();">
            <label class="custom-control-label" for="cmedia_player_1">RealPlayer</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="cmedia_player_2" name="cmedia_player" value="2" class="custom-control-input" <?php is_checked(2, $content["media_player"]); ?> onchange="if(document.getElementById('cmedia_type_2').checked) document.getElementById('cmedia_type_0').click();">
            <label class="custom-control-label" for="cmedia_player_2">MediaPlayer</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="cmedia_player_3" name="cmedia_player" value="3" class="custom-control-input" <?php is_checked(3, $content["media_player"]); ?> onchange="document.getElementById('cmedia_type_2').click();">
            <label class="custom-control-label" for="cmedia_player_3">Flash Plugin</label>
          </div>
        </div>
      </div>
      <hr class="my-2">
      <div class="form-row align-items-center">
        <div class="col-auto">
          <span class="font-weight-bold small mr-2"><?php echo $BL['be_cnt_control'] ?>:</span>
          <div class="custom-control custom-checkbox custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="cmedia_control" id="cmedia_control" value="1" <?php is_checked(1, $content["media_control"]); ?>>
            <label class="custom-control-label" for="cmedia_control"><?php echo $BL['be_cnt_showcontrol'] ?></label>
          </div>
          <div class="custom-control custom-checkbox custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="cmedia_auto" id="cmedia_auto" value="1" <?php is_checked(1, $content["media_auto"]); ?>>
            <label class="custom-control-label" for="cmedia_auto"><?php echo $BL['be_cnt_autoplay'] ?></label>
          </div>
          <div class="custom-control custom-checkbox custom-control-inline">
            <input type="checkbox" class="custom-control-input" name="cmedia_transparent" id="cmedia_transparent" value="1" <?php is_checked(1, $content["media_transparent"]); ?>>
            <label class="custom-control-label" for="cmedia_transparent"><?php echo $BL['be_cnt_transparent'] ?></label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$cmedia_src_minw = max(mb_strlen(strip_tags($BL['be_cnt_internal'])), mb_strlen(strip_tags($BL['be_cnt_external']))) + 4;
?>
<div class="form-group align-items-center form-row">
  <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_source'] ?></label>
  <div class="col-sm-10">
    <div class="input-group input-group-sm mb-2">
      <div class="input-group-prepend">
        <div class="input-group-text py-0" style="min-width: <?php echo $cmedia_src_minw; ?>ch;">
          <div class="custom-control custom-radio">
            <input type="radio" id="cmedia_src_0" name="cmedia_src" value="0" class="custom-control-input" <?php is_checked(0, $content["media_src"]); ?>>
            <label class="custom-control-label" for="cmedia_src_0"><?php echo $BL['be_cnt_internal'] ?></label>
          </div>
        </div>
      </div>
      <input name="cmedia_name" type="text" id="cmedia_name" class="form-control form-control-sm" value="<?php echo isset($content["media_name"]) ? html($content["media_name"]) : '' ?>" readonly>
      <div class="input-group-append">
        <button type="button" class="modalButton btn btn-sm btn-blue folder-open" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=2&amp;target=nolist" title="<?php echo $BL['be_cnt_openmediabrowser'] ?>"></button>
        <button type="button" class="btn btn-sm btn-danger trash" title="<?php echo $BL['be_cnt_delmedia'] ?>" onclick="document.articlecontent.cmedia_name.value='';document.articlecontent.cmedia_id.value='0';return false;"></button>
      </div>
    </div>
    <input name="cmedia_id" type="hidden" id="cmedia_id2" value="<?php echo isset($content["media_id"]) ? $content["media_id"] : '' ?>">

    <div class="input-group input-group-sm">
      <div class="input-group-prepend">
        <div class="input-group-text py-0" style="min-width: <?php echo $cmedia_src_minw; ?>ch;">
          <div class="custom-control custom-radio">
            <input type="radio" id="cmedia_src_1" name="cmedia_src" value="1" class="custom-control-input" <?php is_checked(1, $content["media_src"]); ?>>
            <label class="custom-control-label" for="cmedia_src_1"><?php echo $BL['be_cnt_external'] ?></label>
          </div>
        </div>
      </div>
      <input name="cmedia_extern" type="text" id="cmedia_extern" class="form-control form-control-sm" value="<?php echo isset($content["media_extern"]) ? html($content["media_extern"]) : '' ?>" placeholder="https://...">
    </div>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cimage_pos" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_position'] ?></label>
  <div class="col-sm-auto">
    <select name="cimage_pos" id="cimage_pos" class="custom-select form-control form-control-sm">
      <option value="0" <?php is_selected(0, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos0'] ?></option>
      <option value="1" <?php is_selected(1, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos1'] ?></option>
      <option value="2" <?php is_selected(2, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos2'] ?></option>
      <option value="3" <?php is_selected(3, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos3'] ?></option>
      <option value="4" <?php is_selected(4, $content["media_pos"]) ?>><?php echo $BL['be_cnt_mediapos4'] ?></option>
    </select>
  </div>
  <div class="col-sm-auto mt-2 mt-sm-0">
    <div id="imgpos0" class="btn btn-sm <?php echo ($content["image_pos"]==0 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos0.svg" alt="" width="15" height="15" border="0" data-toggle="tooltip" title="<?php echo $BL['be_cnt_mediapos0i'] ?>"></div>
    <div id="imgpos1" class="btn btn-sm <?php echo ($content["image_pos"]==1 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos1.svg" alt="" width="15" height="15" border="0" data-toggle="tooltip" title="<?php echo $BL['be_cnt_mediapos1i'] ?>"></div>
    <div id="imgpos2" class="btn btn-sm <?php echo ($content["image_pos"]==2 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos2.svg" alt="" width="15" height="15" border="0" data-toggle="tooltip" title="<?php echo $BL['be_cnt_mediapos2i'] ?>"></div>
    <div id="imgpos3" class="btn btn-sm <?php echo ($content["image_pos"]==3 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos6.svg" alt="" width="15" height="15" border="0" data-toggle="tooltip" title="<?php echo $BL['be_cnt_mediapos3i'] ?>"></div>
    <div id="imgpos4" class="btn btn-sm <?php echo ($content["image_pos"]==4 ? "btn-success" : "btn-blue");?>"><img src="img/button/image_pos7.svg" alt="" width="15" height="15" border="0" data-toggle="tooltip" title="<?php echo $BL['be_cnt_mediapos4i'] ?>"></div>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cmedia_width" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ftptakeover_size'] ?></label>
  <div class="col-sm-auto my-1 my-sm-0">
    <div class="input-group input-group-sm">
      <div class="input-group-prepend">
        <span class="input-group-text"><?php echo $BL['be_admin_page_width'] ?></span>
      </div>
      <input name="cmedia_width" type="text" class="form-control form-control-sm" id="cmedia_width" style="width: 50px;" maxlength="5" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo isset($content["media_width"]) ? $content["media_width"] : '' ?>">
      <div class="input-group-append">
        <span class="input-group-text">px</span>
      </div>
    </div>
  </div>

  <div class="col-sm-auto my-1 my-sm-0 ml-sm-2">
    <div class="input-group input-group-sm">
      <div class="input-group-prepend">
        <span class="input-group-text"><?php echo $BL['be_admin_page_height'] ?></span>
      </div>
      <input name="cmedia_height" type="text" class="form-control form-control-sm" id="cmedia_height" style="width: 50px;" maxlength="5" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo isset($content["media_height"]) ? $content["media_height"] : '' ?>">
      <div class="input-group-append">
        <span class="input-group-text">px</span>
      </div>
    </div>
  </div>

  <div class="col-sm-auto my-1 my-sm-0 ml-sm-3">
    <span class="font-weight-bold small mr-1"><?php echo $BL['be_cnt_setsize'] ?>:</span>
    <div class="btn-group btn-group-sm" role="group">
      <button type="button" class="btn btn-outline-secondary" title="<?php echo $BL['be_cnt_set1'] ?>" onclick="document.articlecontent.cmedia_width.value='160';document.articlecontent.cmedia_height.value='120';">160x120</button>
      <button type="button" class="btn btn-outline-secondary" title="<?php echo $BL['be_cnt_set2'] ?>" onclick="document.articlecontent.cmedia_width.value='240';document.articlecontent.cmedia_height.value='180';">240x180</button>
      <button type="button" class="btn btn-outline-secondary" title="<?php echo $BL['be_cnt_set3'] ?>" onclick="document.articlecontent.cmedia_width.value='320';document.articlecontent.cmedia_height.value='240';">320x240</button>
      <button type="button" class="btn btn-outline-secondary" title="<?php echo $BL['be_cnt_set4'] ?>" onclick="document.articlecontent.cmedia_width.value='480';document.articlecontent.cmedia_height.value='360';">480x360</button>
      <button type="button" class="btn btn-outline-danger" title="<?php echo $BL['be_cnt_set5'] ?>" onclick="document.articlecontent.cmedia_width.value='';document.articlecontent.cmedia_height.value='';"><i class="fas fa-times"></i></button>
    </div>
  </div>
</div>

<div class="form-group align-items-center form-row">
  <label for="cimage_name" class="col-sm-2 col-form-label text-right"><?php echo $BL['alt_image'] ?></label>
  <div class="col-sm-8">
    <div class="input-group input-group-sm">
      <input name="cimage_name" type="text" id="cimage_name" class="form-control form-control-sm" value="<?php echo html($content["image_name"]) ?>" maxlength="250" readonly>
      <div class="input-group-append">
        <button type="button" class="modalButton btn btn-sm btn-blue folder-open" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=0&amp;target=nolist" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>"></button>
        <button type="button" class="btn btn-sm btn-danger trash" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="document.articlecontent.cimage_name.value='';document.articlecontent.cimage_id.value='0';return false;"></button>
      </div>
    </div>
    <input name="cimage_id" type="hidden" value="<?php echo $content["image_id"] ?>">
  </div>
</div>

<div class="form-group form-row">
  <label for="cimage_caption" class="col-sm-2 col-form-label text-right"><?php echo $BL['alt_text'] ?></label>
  <div class="col-sm-8">
    <textarea name="cimage_caption" cols="30" rows="3" class="form-control form-control-sm" id="cimage_caption"><?php echo html($content["image_caption"]) ?></textarea>
  </div>
  <?php if($content["image_id"]): ?>
  <div class="col-sm-2">
    <img src="<?php echo PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms["img_list_width"].'x'.$phpwcms["img_list_height"].'/'.$content["image_id"] ?>" class="img-thumbnail" alt="">
  </div>
  <?php endif; ?>
</div>
