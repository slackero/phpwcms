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

$caption_box    = array();
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

if(!isset($content['image_list']['col'])) {

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
            'limit'     => 0,
            'random'    => 0

        );

}
if(empty($content['image_list']['center_image'])) {
    $content['image_list']['center_image'] = 0;
}

$img_count = isset($content["image_list"]['images']) && is_array($content["image_list"]['images']) ? count($content["image_list"]['images']) : 0;

?>

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</td>
    <td><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
            <td><select name="template" id="template" class="width150">
<?php

    echo '<option value="">'.$BL['be_admin_tmpl_default'].'</option>'.LF;

    // templates for frontend login
    $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/images');
    if(is_array($tmpllist) && count($tmpllist)) {
        foreach($tmpllist as $val) {
            // do not show listmode templates
            if(substr($val, 0, 5) == 'list.') {
                continue;
            }
            $selected_val = (isset($content["image_template"]) && $val == $content["image_template"]) ? ' selected="selected"' : '';
            $val = html($val);
            echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
        }
    }

?>
        </select></td>

        <td class="chatlist">&nbsp;&nbsp;&nbsp;<?php echo $BL['be_image_align'] ?>:&nbsp;</td>

         <td>
            <select name="cimage_center" id="cimage_center" class="v11 width150">

                <option value="0"<?php is_selected(0, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagenocenter'] ?></option>
                <option value="1"<?php is_selected(1, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagecenter'] ?></option>
                <option value="2"<?php is_selected(2, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagecenterh'] ?></option>
                <option value="3"<?php is_selected(3, $content['image_list']['center_image']); ?>><?php echo $BL['be_cnt_imagecenterv'] ?></option>

            </select>
        </td>

        </tr>

    </table></td>

</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>

            <td><input name="cimage_width" type="text" class="f11b" id="cimage_width" style="width: 50px;" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo empty($content['image_list']['width']) ? $template_default['imagegallery_default_width'] : $content['image_list']['width']; ?>" /></td>
            <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp;</td>

            <td><input name="cimage_height" type="text" class="f11b" id="cimage_height" style="width: 50px;" size="4" maxlength="4" onkeyup="setCimageCenterInactive();" value="<?php echo empty($content['image_list']['height']) ? $template_default['imagegallery_default_height'] : $content['image_list']['height']; ?>" /></td>
            <td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;</td>

            <td><input type="checkbox" name="cimage_crop" id="cimage_crop" value="1" <?php is_checked(1, $content['image_list']['crop']); ?> /></td>
            <td class="chatlist"><label for="cimage_crop" class="checkbox"><?php echo $BL['be_image_crop'] ?></label></td>

        </tr>
    </table></td>
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
                  <td><input name="cimage_space" type="text" class="f11b width25" id="cimage_space" size="2" maxlength="3" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['space']) ? $template_default['imagegallery_default_space'] : $content['image_list']['space']; ?>" /></td>
                  <td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;</td>

                  <td><input type="checkbox" name="cimage_random" id="cimage_random" value="1" <?php is_checked(1, empty($content['image_list']['random']) ? 0 : 1); ?> /></td>
                  <td class="chatlist"><label for="cimage_random" class="checkbox"><?php echo $BL['random_image'] ?></label>&nbsp;&nbsp;&nbsp;</td>

                  <td class="chatlist"><label for="cimage_limit" class="checkbox"><?php echo $BL['limit_image_from_list'] ?></label></td>
                  <td><input name="cimage_limit" type="text" class="f11b width25" id="cimage_limit" size="2" maxlength="3" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo empty($content['image_list']['limit']) ? '' : $content['image_list']['limit']; ?>" /></td>


                </tr>
              </table></td>
              </tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /><script type="text/javascript">
    function setCimageCenterInactive() {
        var cih = getObjectById('cimage_width');
        var ciw = getObjectById('cimage_height');
        var cic = getObjectById('cimage_center');
        var ccp = getObjectById('cimage_crop');
        var dis = false;
        if(!parseInt(cih.value,10)) {
            cih.value = '';
            dis = true;
        }
        if(!parseInt(ciw.value,10)) {
            ciw.value = '';
            dis = true;
        }
        if(dis) {
            cic.disabled = true;
            ccp.disabled = true;
        } else {
            cic.disabled = false;
            ccp.disabled = false;
        }
    }
    setCimageCenterInactive();
    </script></td></tr>

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

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
    <tr>
        <td valign="top">
        <select name="cimage_list[]" size="<?php echo $img_count+5 ?>" multiple="multiple" class="width360" id="cimage_list">
<?php
if($img_count) {

    // browse images and list available
    // will be visible only when aceessible
    foreach($content['image_list']['images'] as $key => $value) {

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
            echo $img_name . '</option>'.LF;

            if($imgx == 4) {
                $img_thumbs .= '<br /><img src="img/leer.gif" alt="" border="0" width="1" height="2" /><br />';
                $imgx = 0;
            }
            if($imgx) {
                $img_thumbs .= '<img src="img/leer.gif" alt="" border="0" width="2" height="1" />';
            }
            $img_thumbs .= '<img src="' . $thumb_image['src'] .'" '.$thumb_image[3].' alt="'.$img_name.'" title="'.$img_name.'" />';

            $caption_box[] = html($content['image_list']['images'][$key][6]);

            $imgx++;
        }

    }

}

?>
          </select></td>
                  <td valign="top"><img src="img/leer.gif" alt="" width="5" height="1" /></td>
                  <td valign="top">
                  <a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=1&amp;target=nolist');return false;"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a>
                  <br /><img src="img/leer.gif" alt="" width="1" height="4" /><br /><a href="#" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cimage_list);return false;"><img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0" /></a><a href="#" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cimage_list);return false;"><img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0" /></a>
                  <br /><img src="img/leer.gif" alt="" width="1" height="4" /><br /><a href="#" onclick="removeSelectedOptions(document.articlecontent.cimage_list);return false;" title="<?php echo $BL['be_cnt_delimage'] ?>"><img src="img/button/del_image_button1.gif" alt="" width="20" height="15" border="0" /></a></td>
      </tr>
              </table>
<?php

    if($img_thumbs) {
        echo '
        <table border="0" cellspacing="0" cellpadding="0" summary="">
        <tr>
                <td style="padding:5px 0 5px 0;">'.$img_thumbs.'</td>
            </tr>
        </table>';
    }

?></td>

</tr>

<tr>
    <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_cnt_caption'] ?>:&nbsp;</td>
    <td valign="top">
        <textarea name="cimage_caption" cols="40" rows="3" wrap="off" class="width440 autosize" id="cimage_caption"><?php echo implode(' '.LF, $caption_box) ?></textarea>
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
<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr><td colspan="2" align="center"><?php

$wysiwyg_editor = array(
    'value'     => isset($content["text"]) ? $content["text"] : '',
    'field'     => 'ctext',
    'height'    => '250px',
    'width'     => '100%',
    'rows'      => '15',
    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    'lang'      => 'en'
);

include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

?></td></tr>
