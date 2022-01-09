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


//images

$caption_box    = '';
$img_thumbs     = '';
$imgx           = 0;

if(empty($template_default['imagegallery_default_column'])) {
    $template_default['imagegallery_default_column'] = 1;
} else {
    $template_default['imagegallery_default_column'] = intval($template_default['imagegallery_default_column']);
    if(empty($template_default['imagegallery_default_column'])) {
        $template_default['imagegallery_default_column'] = 1;
    }
}

$template_default['imagegallery_default_width']  = isset($template_default['imagegallery_default_width']) ? $template_default['imagegallery_default_width'] : '' ;
$template_default['imagegallery_default_height'] = isset($template_default['imagegallery_default_height']) ? $template_default['imagegallery_default_height'] : '' ;
$template_default['imagegallery_default_space']  = isset($template_default['imagegallery_default_space']) ? $template_default['imagegallery_default_space'] : '' ;

if(!isset($content['image_list'])) {

    $content['image_list'] = array(

        'pos'       => 0,
        'width'     => $template_default['imagegallery_default_width'],
        'height'    => $template_default['imagegallery_default_height'],
        'col'       => $template_default['imagegallery_default_column'],
        'space'     => $template_default['imagegallery_default_space'],
        'zoom'      => 0,
        'caption'   => '',
        'lightbox'  => 0,
        'nocaption' => 0,
        'crop'      => 0,
        'random'    => 0,
        'limit'     => 0

    );

}

if(!isset($content['image_list']['limit'])) {
    $content['image_list']['limit'] = 0;
}

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
    <td><select name="template" id="template">
<?php

    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/imagetable');
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
    <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
    <tr>
        <td valign="top"><select name="cimage_list[]" size="<?php echo isset($content["image_list"]) && count($content["image_list"]) ? count($content["image_list"])+5 : 5 ?>" multiple="multiple" class="width300" id="cimage_list">
<?php
if(isset($content['image_list']['images']) && is_array($content['image_list']['images']) && count($content['image_list']['images'])) {

    // browse images and list available
    // will be visible only when aceessible
    foreach($content['image_list']['images'] as $key => $value) {

        $caption_box .= html($content['image_list']['images'][$key][6])."\n";

        // 0   :1       :2   :3        :4    :5     :6      :7       :8
        // dbid:filename:hash:extension:width:height:caption:position:zoom
        $thumb_image = get_cached_image(array(
            "target_ext"    =>  $content['image_list']['images'][$key][3],
            "image_name"    =>  $content['image_list']['images'][$key][2] . '.' . $content['image_list']['images'][$key][3],
            "thumb_name"    =>  md5($content['image_list']['images'][$key][2].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
        ));

        if($thumb_image != false) {

            // image found
            echo '<option value="' . $content['image_list']['images'][$key][0] . '">';
            $img_name = html($content['image_list']['images'][$key][1]);
            echo $img_name . "</option>\n";

            if($imgx == 4) {
                $img_thumbs .= '<br><img src="img/leer.gif" alt="" width="1" height="2"><br>';
                $imgx = 0;
            }
            if($imgx) {
                $img_thumbs .= '<img src="img/leer.gif" alt="" width="2" height="1">';
            }
            $img_thumbs .= '<img src="' . $thumb_image['src'] .'" '.$thumb_image[3].' alt="'.$img_name.'" title="'.$img_name.'">';

            $imgx++;
        }

    }

}

?>
                  </select></td>
                  <td valign="top"><img src="img/leer.gif" alt="" width="5" height="1"></td>
                  <td valign="top"><a href="javascript:;" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>"
                  onclick="openFileBrowser('filebrowser.php?opt=1&amp;target=nolist');"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0"></a><br /><img src="img/leer.gif" alt="" width="1" height="4"><br /><a href="javascript:;"
                  title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cimage_list);"><img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0"></a><a href="javascript:;"
                  title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cimage_list);"><img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0"></a><br /><img src="img/leer.gif" alt="" width="1" height="4"><br /><a href="javascript:;"
                  onclick="removeSelectedOptions(document.articlecontent.cimage_list);" title="<?php echo $BL['be_cnt_delimage'] ?>"><img src="img/button/del_image_button1.gif" alt="" width="20" height="15" border="0"></a></td>
                </tr>
              </table><?php

if($img_thumbs) {
    echo '<table border="0" cellspacing="0" cellpadding="0">
        <tr><td style="padding-bottom:3px;"><img src="img/leer.gif" width="1" height="5"><br>'.$img_thumbs.'</td></tr>
        </table>';
}

?></td>
              </tr>
              <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_cnt_position'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><select name="cimage_pos" id="cimage_pos" onChange="changeImagePosMenu();">
                <option value="0" <?php is_selected(0, $content['image_list']['pos']) ?>><?php echo $BL['be_cnt_pos0'] ?></option>
                <option value="1" <?php is_selected(1, $content['image_list']['pos']) ?>><?php echo $BL['be_cnt_pos1'] ?></option>
                <option value="2" <?php is_selected(2, $content['image_list']['pos']) ?>><?php echo $BL['be_cnt_pos2'] ?></option>
                <option value="3" <?php is_selected(3, $content['image_list']['pos']) ?>><?php echo $BL['be_cnt_pos3'] ?></option>
                <option value="4" <?php is_selected(4, $content['image_list']['pos']) ?>><?php echo $BL['be_cnt_pos4'] ?></option>
                <option value="5" <?php is_selected(5, $content['image_list']['pos']) ?>><?php echo $BL['be_cnt_pos5'] ?></option>
                <option value="6" <?php is_selected(6, $content['image_list']['pos']) ?>><?php echo $BL['be_cnt_pos6'] ?></option>
                <option value="7" <?php is_selected(7, $content['image_list']['pos']) ?>><?php echo $BL['be_cnt_pos7'] ?></option>
              </select></td>
                  <td><img src="img/leer.gif" alt="" width="3" height="1"></td>
                  <td><img src="img/symbole/content_selected.gif" alt="" name="imgpos0" width="7" height="10" id="imgpos0"></td>
                  <td><a href="javascript:;" onclick="changeImagePos(0);this.blur();return false;" title="<?php echo $BL['be_cnt_pos0i'] ?>"><img src="img/button/image_pos0.gif" alt="" width="15" height="15" border="0"></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos1" width="7" height="10" id="imgpos1"></td>
                  <td><a href="javascript:;" onclick="changeImagePos(1);this.blur();return false;" title="<?php echo $BL['be_cnt_pos1i'] ?>"><img src="img/button/image_pos1.gif" alt="" width="15" height="15" border="0"></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos2" width="7" height="10" id="imgpos2"></td>
                  <td><a href="javascript:;" onclick="changeImagePos(2);this.blur();return false;" title="<?php echo $BL['be_cnt_pos2i'] ?>"><img src="img/button/image_pos2.gif" alt="" width="15" height="15" border="0"></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos3" width="7" height="10" id="imgpos3"></td>
                  <td><a href="javascript:;" onclick="changeImagePos(3);this.blur();return false;" title="<?php echo $BL['be_cnt_pos3i'] ?>"><img src="img/button/image_pos3.gif" alt="" width="15" height="15" border="0"></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos4" width="7" height="10" id="imgpos4"></td>
                  <td><a href="javascript:;" onclick="changeImagePos(4);this.blur();return false;" title="<?php echo $BL['be_cnt_pos4i'] ?>"><img src="img/button/image_pos4.gif" alt="" width="15" height="15" border="0"></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos5" width="7" height="10" id="imgpos5"></td>
                  <td><a href="javascript:;" onclick="changeImagePos(5);this.blur();return false;" title="<?php echo $BL['be_cnt_pos5i'] ?>"><img src="img/button/image_pos5.gif" alt="" width="15" height="15" border="0"></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos6" width="7" height="10" id="imgpos6"></td>
                  <td><a href="javascript:;" onclick="changeImagePos(6);this.blur();return false;" title="<?php echo $BL['be_cnt_pos6i'] ?>"><img src="img/button/image_pos6.gif" alt="" width="15" height="15" border="0"></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos7" width="7" height="10" id="imgpos7"></td>
                  <td><a href="javascript:;" onclick="changeImagePos(7);this.blur();return false;" title="<?php echo $BL['be_cnt_pos7i'] ?>"><img src="img/button/image_pos7.gif" alt="" width="15" height="15" border="0"></a></td>

                  <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td><input type="checkbox" name="cimage_usetable" id="cimage_usetable" value="1" <?php is_checked(1, empty($content['image_list']['usetable']) ? 0 : 1); ?> /></td>
                  <td class="v10"><label for="cimage_usetable" class="checkbox"><?php echo $BL['be_admin_page_table'] ?></label></td>

                </tr>
              </table><script type="text/javascript">
              <!--
              changeImagePos(<?php echo intval($content['image_list']['pos']); ?>);
              //-->
              </script></td>
              </tr>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><input name="cimage_width" type="text" class="f11b" id="cimage_width" style="width: 50px;" size="4" maxlength="4" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['width']) ? $template_default['imagegallery_default_width'] : $content['image_list']['width']; ?>"></td>
                  <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp;</td>
                  <td><input name="cimage_height" type="text" class="f11b" id="cimage_height" style="width: 50px;" size="4" maxlength="4" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['height']) ? $template_default['imagegallery_default_height'] : $content['image_list']['height']; ?>"></td>
                  <td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;</td>

                <td><input type="checkbox" name="cimage_crop" id="cimage_crop" value="1" <?php is_checked(1, $content['image_list']['crop']); ?> /></td>
                <td class="v10"><label for="cimage_crop" class="checkbox"><?php echo $BL['be_image_crop'] ?></label></td>

                </tr>
              </table></td>
              </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

<tr>
   <td align="right" class="chatlist"><?php echo $BL['limit_image_from_list'] ?>:&nbsp;</td>
   <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
         <tr>
            <td><select name="cimage_limit" id="cimage_limit">
        <option value="0"<?php is_selected(0, $content['image_list']['limit']); ?>><?php echo $BL['be_ftptakeover_all'] ?></option>
<?php
// take max images from list
if($imgx) {
    $max_limit = $imgx + 10;
} else {
    $max_limit = 10;
}
for($max_image_limit = 1; $max_image_limit <= $max_limit; $max_image_limit++) {

    echo '<option value="'.$max_image_limit.'" ';
    is_selected($max_image_limit, $content['image_list']['limit']);
    echo '>'.$max_image_limit."</option>\n";
}
?>            </select></td>
            <td>&nbsp;&nbsp;</td>
            <td><input name="cimage_random" type="checkbox" id="cimage_random" value="1" <?php is_checked(1, $content['image_list']['random']); ?> /></td>
            <td class="v10"><label for="cimage_random" class="checkbox"><?php echo $BL['random_image'] ?></label></td>
         </tr>
      </table>
   </td>
</tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_cnt_column'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><select name="cimage_col" id="cimage_col">
<?php

// list select menu for max image columns
for($max_image_col = 1; $max_image_col <= 25; $max_image_col++) {

    echo '<option value="'.$max_image_col.'" ';
    is_selected($max_image_col, $content['image_list']['col']);
    echo '>'.$max_image_col."</option>\n";

}

?>
                  </select></td>
                  <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_imagespace'] ?>:&nbsp;</td>
                  <td><input name="cimage_space" type="text" class="f11b" id="cimage_space" style="width: 50px;" size="2" maxlength="3" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['space']) ? $template_default['imagegallery_default_space'] : $content['image_list']['space']; ?>" /></td>
                  <td class="chatlist">&nbsp;px</td>
                </tr>
              </table></td>
              </tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_behavior'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>
                <td><input name="cimage_zoom" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, $content['image_list']['zoom']); ?> /></td>
                <td class="v10"><label for="cimage_zoom" class="checkbox"><?php echo $BL['be_cnt_enlarge'] ?></label></td>

                <td>&nbsp;</td>
                <td><input name="cimage_lightbox" type="checkbox" id="cimage_lightbox" value="1" <?php is_checked(1, $content['image_list']['lightbox']); ?> onchange="if(this.checked){getObjectById('cimage_zoom').checked=true;}" /></td>
                <td class="v10"><label for="cimage_lightbox" class="checkbox"><?php echo $BL['be_cnt_lightbox'] ?></label></td>

                <td>&nbsp;</td>
                <td><input name="cimage_nocaption" type="checkbox" id="cimage_nocaption" value="1" <?php is_checked(1, $content['image_list']['nocaption']); ?> /></td>
                <td class="v10"><label for="cimage_nocaption" class="checkbox"><?php echo $BL['be_cnt_imglist_nocaption'] ?></label></td>

            </tr>
        </table>
    </td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
<tr>
    <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_caption'] ?>:&nbsp;</td>
    <td valign="top">
        <textarea name="cimage_caption" cols="40" rows="3" wrap="off" class="width440 autosize" id="cimage_caption"><?php echo $caption_box; ?></textarea>
        <span class="caption width440">
            <?php echo $BL['be_cnt_caption']; ?>
            |
            <?php echo $BL['be_caption_alt']; ?>
            |
            <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
            |
            <?php echo $BL['be_caption_title']; ?>
            |
            <?php echo $BL['be_copyright']; ?>&nbsp;&crarr;&nbsp;&hellip;
        </span>
    </td>
</tr>
