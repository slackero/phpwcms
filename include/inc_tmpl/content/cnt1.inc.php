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


//image with text

$template_default['article']['image_default_width'] = isset($template_default['article']['image_default_width']) ? $template_default['article']['image_default_width'] : '' ;
$template_default['article']['image_default_height']= isset($template_default['article']['image_default_height']) ? $template_default['article']['image_default_height'] : '' ;

if(empty($content['cimage']['cimage_crop'])) {
    $content['cimage']['cimage_crop'] = 0;
}

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
    <td><select name="template" id="template">
<?php

    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/imagetext');
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


<tr><td colspan="2" class="chatlist">&nbsp;<?php echo $BL['be_cnt_htmltext'] ?>:&nbsp;</td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<tr><td colspan="2" align="center"><?php

$wysiwyg_editor = array(
    'value'     => isset($content["text"]) ? $content["text"] : '',
    'field'     => 'ctext',
    'height'    => '650px',
    'width'     => '100%',
    'rows'      => '15',
    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    'lang'      => 'en'
);

include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

?></td></tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
              <td align="right" class="chatlist"><?php echo  $BL['be_cnt_image'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><input name="cimage_name" type="text" id="cimage_name" class="f11b" style="width: 300px; color: #727889;" value="<?php echo  isset($content["image_name"]) ? html($content["image_name"]) : '' ?>" size="40" maxlength="250" onfocus="this.blur()" /></td>
                  <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="javascript:;" title="<?php echo  $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=0&amp;target=nolist')"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="javascript:;" title="<?php echo  $BL['be_cnt_delimage'] ?>" onclick="document.articlecontent.cimage_name.value='';document.articlecontent.cimage_id.value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>
                    <input name="cimage_id" type="hidden" value="<?php echo  isset($content["image_id"]) ? $content["image_id"] : '' ?>" /></td>
                </tr>
              </table></td>
              </tr>
              <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
            <tr>
              <td align="right" class="chatlist"><?php echo  $BL['be_cnt_position'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><select name="cimage_pos" id="cimage_pos" onchange="changeImagePosMenu();">
                <option value="0" <?php
                if(!isset($content["image_pos"])) $content["image_pos"] = 0;

                is_selected(0, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos0'] ?></option>
                <option value="1" <?php is_selected(1, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos1'] ?></option>
                <option value="2" <?php is_selected(2, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos2'] ?></option>
                <option value="3" <?php is_selected(3, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos3'] ?></option>
                <option value="4" <?php is_selected(4, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos4'] ?></option>
                <option value="5" <?php is_selected(5, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos5'] ?></option>
                <option value="6" <?php is_selected(6, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos6'] ?></option>
                <option value="7" <?php is_selected(7, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos7'] ?></option>
                <option value="8" <?php is_selected(8, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos8'] ?></option>
                <option value="9" <?php is_selected(9, $content["image_pos"]) ?>><?php echo  $BL['be_cnt_pos9'] ?></option>
              </select></td>
                  <td><img src="img/leer.gif" alt="" width="3" height="1" /></td>
                  <td><img src="img/symbole/content_selected.gif" alt="" name="imgpos0" width="7" height="10" id="imgpos0" /></td>
                  <td><a href="javascript:" onclick="changeImagePos(0);this.blur();return false;" title="<?php echo $BL['be_cnt_pos0i'] ?>"><img src="img/button/image_pos0.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos1" width="7" height="10" id="imgpos1" /></td>
                  <td><a href="javascript:" onclick="changeImagePos(1);this.blur();return false;" title="<?php echo $BL['be_cnt_pos1i'] ?>"><img src="img/button/image_pos1.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos2" width="7" height="10" id="imgpos2" /></td>
                  <td><a href="javascript:" onclick="changeImagePos(2);this.blur();return false;" title="<?php echo $BL['be_cnt_pos2i'] ?>"><img src="img/button/image_pos2.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos3" width="7" height="10" id="imgpos3" /></td>
                  <td><a href="javascript:" onclick="changeImagePos(3);this.blur();return false;" title="<?php echo $BL['be_cnt_pos3i'] ?>"><img src="img/button/image_pos3.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos4" width="7" height="10" id="imgpos4" /></td>
                  <td><a href="javascript:" onclick="changeImagePos(4);this.blur();return false;" title="<?php echo $BL['be_cnt_pos4i'] ?>"><img src="img/button/image_pos4.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos5" width="7" height="10" id="imgpos5" /></td>
                  <td><a href="javascript:" onclick="changeImagePos(5);this.blur();return false;" title="<?php echo $BL['be_cnt_pos5i'] ?>"><img src="img/button/image_pos5.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos6" width="7" height="10" id="imgpos6" /></td>
                  <td><a href="javascript:" onclick="changeImagePos(6);this.blur();return false;" title="<?php echo $BL['be_cnt_pos6i'] ?>"><img src="img/button/image_pos6.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos7" width="7" height="10" id="imgpos7" /></td>
                  <td><a href="javascript:" onclick="changeImagePos(7);this.blur();return false;" title="<?php echo $BL['be_cnt_pos7i'] ?>"><img src="img/button/image_pos7.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos8" width="7" height="10" id="imgpos8" /></td>
                  <td><a href="javascript:" onclick="changeImagePos(8);this.blur();return false;" title="<?php echo $BL['be_cnt_pos8i'] ?>"><img src="img/button/image_pos8.gif" alt="" width="15" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" name="imgpos9" width="7" height="10" id="imgpos9" /></td>
                  <td><a href="javascript:" onclick="changeImagePos(9);this.blur();return false;" title="<?php echo $BL['be_cnt_pos9i'] ?>"><img src="img/button/image_pos9.gif" alt="" width="15" height="15" border="0" /></a></td>
                </tr>
              </table><script type="text/javascript">
              <!--
                changeImagePos(<?php echo intval($content["image_pos"]); ?>);
              //-->
              </script></td>
              </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><input name="cimage_width" type="text" class="f11b" id="cimage_width" style="width: 50px;" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content["image_width"]) ? $template_default['article']['image_default_width'] : $content["image_width"] ?>" /></td>
                  <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp; </td>

                  <td><input name="cimage_height" type="text" class="f11b" id="cimage_height" style="width: 50px;" size="4" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content["image_height"]) ? $template_default['article']['image_default_height'] : $content["image_height"] ?>" /></td>
                  <td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;</td>

                <td><input type="checkbox" name="cimage_crop" id="cimage_crop" value="1" <?php is_checked(1, $content['cimage']['cimage_crop']); ?> /></td>
                <td class="v10"><label for="cimage_crop" class="checkbox"><?php echo $BL['be_image_crop'] ?></label></td>

                </tr>
              </table></td> </tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_behavior'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>
                <td><input name="cimage_zoom" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, empty($content["image_zoom"]) ? 0 : 1); ?> /></td>
                <td class="v10"><label for="cimage_zoom" class="checkbox"><?php echo $BL['be_cnt_enlarge'] ?></label></td>

                <td>&nbsp;</td>
                <td><input name="cimage_lightbox" type="checkbox" id="cimage_lightbox" value="1" <?php is_checked(1, empty($content['cimage']['cimage_lightbox']) ? 0 : 1); ?> onchange="if(this.checked){getObjectById('cimage_zoom').checked=true;}" /></td>
                <td class="v10"><label for="cimage_lightbox" class="checkbox"><?php echo $BL['be_cnt_lightbox'] ?></label></td>

                <td>&nbsp;</td>
                <td><input name="cimage_nocaption" type="checkbox" id="cimage_nocaption" value="1" <?php is_checked(1, empty($content['cimage']['cimage_nocaption']) ? 0 : 1); ?> /></td>
                <td class="v10"><label for="cimage_nocaption" class="checkbox"><?php echo $BL['be_cnt_imglist_nocaption'] ?></label></td>

            </tr>
        </table>
    </td>
</tr>



            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
            <tr>
              <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13" /><?php echo $BL['be_cnt_caption'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                  <tr>
                    <td valign="top">
                        <textarea name="cimage_caption" cols="30" rows="3" class="width300 autosize" id="cimage_caption"><?php
                        if(isset($content["image_caption"])) {
                            echo html($content["image_caption"]);
                        }
                    ?></textarea>
                        <span class="caption width300">
                            <?php echo $BL['be_cnt_caption']; ?>
                            |
                            <?php echo $BL['be_caption_alt']; ?>
                            |
                            <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
                            |
                            <?php echo $BL['be_caption_title']; ?>
                            |
                            <?php echo $BL['be_copyright']; ?>
                        </span>
                    </td>
                    <td valign="top"><img src="img/leer.gif" alt="" width="15" height="1" /></td>
                    <td valign="top"><?php

if(isset($content["image_hash"])) {
    $thumb_image = get_cached_image(array(
        "target_ext"    =>  $content["image_ext"],
        "image_name"    =>  $content["image_hash"] . '.' . $content["image_ext"],
        "thumb_name"    =>  md5($content["image_hash"].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
    ));

    if($thumb_image != false) {
        echo '<img src="' . $thumb_image['src'] .'" alt="" '.$thumb_image[3].'>';
    }
}

?></td>
                  </tr>
              </table></td>
</tr>
