<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2019, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Reference

if(!isset($content['reference'])) {

    $content['reference']["text"] = '';
    $content["reference"]['tmpl'] = '';
    $content['reference']["select"] = array();
    $content['reference']["list"] = array();
    $content['reference']["caption"] = '';
    $content['reference']["zoom"] = 0;
    $content['reference']["width"] = '';
    $content['reference']["height"] = '';
    $content['reference']["border"] = '';
    $content['reference']["pos"] = 0;
    $content['reference']["blockwidth"] = '';
    $content['reference']["blockheight"] = '';
    $content['reference']["space"] = '';
    $content['reference']["listborder"] = '';
    $content["reference"]["basis"] = 0;

}

$imgx=0;
$img_thumbs = '';

?>


<tr>
    <td align="right"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
    <td><select class="custom-select" name="creference_tmpl" id="creference_tmpl">
<?php

    // templates for Reference
    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;
    $tmpllist = get_tmpl_files(CMSGO_TEMPLATE.'inc_cntpart/reference');
    if(is_array($tmpllist) && count($tmpllist)) {
        foreach($tmpllist as $val) {
            $val = htmlspecialchars($val);
            echo '<option value="' . $val . '"' . ($val == $content["reference"]['tmpl'] ? ' selected="selected"' : '' ).'>' . $val . "</option>\n";
        }
    }

?>
      </select></td></tr>



<tr>
<td align="right" valign="top"><?php echo $BL['be_cnt_plaintext'] ?>:&nbsp;</td>
<td valign="top"><textarea name="creference_text" rows="15" wrap="VIRTUAL" class="width440 autosize" id="creference_text"><?php echo  $content['reference']["text"] ?></textarea></td>
</tr>

<tr>
  <td align="right" valign="top"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
  <td valign="top"><table class="table-no-border">
      <tr>
        <td valign="top"><select name="cimage_list[]" size="<?php echo isset($content['reference']["select"]) && count($content['reference']["select"]) ? count($content['reference']["select"]) + 5 : 5; ?>" multiple="multiple" class="custom-select width300" id="cimage_list">
            <?php
                    if(is_array($content['reference']["list"]) && count($content['reference']["list"])) {

                        foreach($content['reference']["list"] as $key => $value) {

                            $thumb_image = get_cached_image(array(
                                "target_ext"    =>  $content['reference']["list"][$key][3],
                                "image_name"    =>  $content['reference']["list"][$key][2] . '.' . $content['reference']["list"][$key][3],
                                "thumb_name"    =>  md5($content['reference']["list"][$key][2].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
                            ));

                            if($thumb_image != false) {

                                echo "<option value=\"".$content['reference']["list"][$key][0]."\">";
                                $img_name = html($content['reference']["list"][$key][1]);
                                echo $img_name."</option>\n";

                                if($imgx == 4) {
                                    $img_thumbs .= '<br>><br>';
                                    $imgx = 0;
                                }
                                if($imgx) {
                                    $img_thumbs .= '';
                                }
                                $img_thumbs .= '<img src="' . $thumb_image['src'] .'" '.$thumb_image[3].' alt="'.$img_name.'" title="'.$img_name.'">';

                                $imgx++;

                            }

                        }

                    }
                    ?>
          </select></td>
        <td valign="top"></td>
        <td valign="top"><img src="img/button/open_image_button.gif" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" alt="<?php echo $BL['be_cnt_openimagebrowser'] ?>" width="20" height="15" border="0" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=3&amp;target=nolist" class="modalButton" /><br />
          ><br />
          <a href="javascript:;" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cimage_list);"><img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0"></a><a href="javascript:;" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cimage_list);"><img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0"></a><br />
          ><br />
          <a href="javascript:;" onclick="removeSelectedOptions(document.articlecontent.cimage_list);" title="<?php echo $BL['be_cnt_delimage'] ?>"><img src="img/button/del_image_button1.gif" alt="" width="20" height="15" border="0"></a></td>
      </tr>
    </table><?php

if($img_thumbs) {
    echo '<table border="0" cellspacing="0" cellpadding="0">
        <tr><td style="padding-bottom:3px;"><br>'.$img_thumbs.'</td></tr>
        </table>';
}

?></td>
</tr>

<tr>
  <td align="right" valign="top"><?php echo $BL['be_cnt_caption'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="creference_caption" cols="40" rows="5" wrap="off" class="form-control" id="creference_caption"><?php echo html($content['reference']["caption"]) ?></textarea></td>
</tr>

<tr>
  <td align="right"><?php echo $BL['be_cnt_reference_zoom'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
      <tr>
        <td><input name="creference_zoom" type="checkbox" id="creference_zoom" value="1" <?php is_checked(1, $content['reference']["zoom"]); ?>></td>
        <td>&nbsp;<?php echo $BL['be_cnt_enlarge'] ?>&nbsp;&nbsp;</td>
      </tr>
    </table></td>
</tr>

<tr><td></td><td><strong><?php echo $BL['be_cnt_reference_largetext']; ?>:</strong></td></tr>
<tr>
  <td align="right"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
  <td valign="top"><table class="table-no-border">
      <tr>
        <td><input name="creference_width" type="text" class="form-control" id="creference_width" style="width: 50px;" size="5" maxlength="5" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['reference']["width"] ?>"></td>
        <td>&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp;</td>
        <td><input name="creference_height" type="text" class="form-control" id="creference_height" style="width: 50px;" size="5" maxlength="5" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['reference']["height"] ?>"></td>
        <td>&nbsp;px&nbsp;&nbsp;&nbsp;<?php echo $BL['be_cnt_reference_border'] ?>:&nbsp;</td>
        <td><input name="creference_border" type="text" class="form-control" id="creference_border" style="width: 30px;" size="3" maxlength="3" onKeyUp="if(!parseInt(this.value,10)) this.value='0';" value="<?php echo $content['reference']["border"] ?>"></td>
      </tr>
    </table></td>
</tr>



<tr><td></td><td><strong><?php echo $BL['be_cnt_reference_aligntext'] ?>:</strong></td></tr>
<tr>
  <td align="right"><?php echo $BL['be_cnt_reference_basis'] ?>:&nbsp;</td>
  <td valign="top"><table class="table-no-border">
    <tr>
      <td bgcolor="#E7E8EB"><input name="creference_basis" type="radio" value="0" <?php is_checked(0, $content["reference"]["basis"]); ?>></td>
      <td bgcolor="#E7E8EB"><?php echo $BL['be_cnt_reference_horizontal'] ?>&nbsp;</td>
      <td bgcolor="#E7E8EB"><input name="creference_basis" type="radio" value="1" <?php is_checked(1, $content["reference"]["basis"]); ?>></td>
      <td bgcolor="#E7E8EB"><?php echo $BL['be_cnt_reference_vertical'] ?>&nbsp;&nbsp;</td>
      <td>&nbsp;&nbsp;&nbsp;</td>
      <td><select class="custom-select" name="creference_pos" id="creference_pos">
        <option value="0" <?php is_selected(0, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_default'] ?></option>
        <option value="1" <?php is_selected(1, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_left'].', '.$BL['be_admin_page_top'] ?></option>
        <option value="2" <?php is_selected(2, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_left'].', '.$BL['be_cnt_reference_middle'] ?></option>
        <option value="3" <?php is_selected(3, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_left'].', '.$BL['be_admin_page_bottom'] ?></option>
        <option value="4" <?php is_selected(4, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_center'].', '.$BL['be_admin_page_top'] ?></option>
        <option value="5" <?php is_selected(5, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_center'].',. '.$BL['be_cnt_reference_middle'] ?></option>
        <option value="6" <?php is_selected(6, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_center'].', '.$BL['be_admin_page_bottom'] ?></option>
        <option value="7" <?php is_selected(7, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_right'].', '.$BL['be_admin_page_top'] ?></option>
        <option value="8" <?php is_selected(8, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_right'].', '.$BL['be_cnt_reference_middle'] ?></option>
        <option value="9" <?php is_selected(9, $content['reference']["pos"]) ?>><?php echo $BL['be_cnt_right'].', '.$BL['be_admin_page_bottom'] ?></option>
        </select></td>
      </tr>
  </table></td>
</tr>


<tr>
  <td align="right"><?php echo $BL['be_cnt_reference_block'] ?>:&nbsp;</td>
  <td valign="top"><table class="table-no-border">
      <tr>

        <td><input name="creference_blockwidth" type="text" class="form-control" id="creference_blockwidth" style="width: 50px;" size="5" maxlength="5" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['reference']["blockwidth"] ?>"></td>
        <td>&nbsp;x&nbsp;</td>
        <td><input name="creference_blockheight" type="text" class="form-control" id="creference_blockheight" style="width: 50px;" size="5" maxlength="5" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content['reference']["blockheight"] ?>"></td>
        <td>&nbsp;px&nbsp;&nbsp;&nbsp;<?php echo $BL['be_cnt_imagespace'] ?>:&nbsp;</td>
        <td><input name="creference_space" type="text" class="form-control" id="creference_space" style="width: 50px;" size="2" maxlength="2" onKeyUp="if(!parseInt(this.value,10)) this.value='0';" value="<?php echo $content['reference']["space"] ?>"></td>
        <td>&nbsp;px&nbsp;&nbsp;<?php echo $BL['be_cnt_reference_border'] ?>:&nbsp;</td>
        <td><input name="creference_listborder" type="text" class="form-control" id="creference_listborder" style="width: 30px;" size="3" maxlength="3" onKeyUp="if(!parseInt(this.value,10)) this.value='0';" value="<?php echo $content['reference']["listborder"] ?>"></td>
      </tr>
    </table></td>
</tr>
