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

//FAQ

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
    <td><select name="faq_template" id="faq_template">
<?php

// templates for recipes
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/faq');
if(is_array($tmpllist) && count($tmpllist)) {
    foreach($tmpllist as $val) {
        if(isset($content['faq']['faq_template']) && $val == $content['faq']['faq_template']) {
            $selected_val = ' selected="selected"';
        } else {
            $selected_val = '';
        }
        $val = html($val);
        echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
    }
}

?>
    </select></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
    <td class="chatlist tdtop3" align="right">&nbsp;<?php echo $BL['be_cnt_question'] ?>:&nbsp;</td>
    <td><textarea name="faq_question" rows="4" class="width440 autosize" id="faq_question"><?php

    echo empty($content["faq_question"]) ? '' : $content["faq_question"];

?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
</tr>
<tr><td colspan="2" class="chatlist">&nbsp;<?php echo $BL['be_cnt_answer'] ?>:&nbsp;</td></tr>
<tr><td colspan="2"><?php

$wysiwyg_editor = array(
    'value'     => isset($content["faq_answer"]) ? $content["faq_answer"] : '',
    'field'     => 'faq_answer',
    'height'    => '300px',
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
                  <td><input name="cimage_name" type="text" id="cimage_name" class="f11b" style="width: 200px; color: #727889;" value="<?php echo  isset($content["image_name"]) ? html($content["image_name"]) : '' ?>" size="40" maxlength="250" onfocus="this.blur()" /></td>
                  <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="javascript:;" title="<?php echo  $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=0&amp;target=nolist')"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a></td>
                  <td><img src="img/leer.gif" alt="" width="3" height="1" /><a href="javascript:;" title="<?php echo  $BL['be_cnt_delimage'] ?>" onclick="document.articlecontent.cimage_name.value='';document.articlecontent.cimage_id.value='0';this.blur();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>
                    <input name="cimage_id" type="hidden" value="<?php echo  isset($content["image_id"]) ? $content["image_id"] : '' ?>" /></td>
                </tr>
              </table></td>
              </tr>
          <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>
            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><input name="cimage_width" type="text" class="f11b" id="cimage_width" style="width: 50px;" size="3" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo  isset($content["image_width"]) ? $content["image_width"] : '' ?>" /></td>
                  <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp; </td>
                  <td><input name="cimage_height" type="text" class="f11b" id="cimage_height" style="width: 50px;" size="3" maxlength="4" onkeyup="if(!parseInt(this.value,10)) this.value='';" value="<?php echo  isset($content["image_height"]) ? $content["image_height"] : '' ?>" /></td>
                  <td class="chatlist">&nbsp;px&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td bgcolor="#E7E8EB">&nbsp;</td>
                  <td bgcolor="#E7E8EB"><input name="cimage_zoom" type="checkbox" id="cimage_zoom" value="1" <?php is_checked(1, isset($content["image_zoom"]) ? $content["image_zoom"] : 0); ?> /></td>
                  <td bgcolor="#E7E8EB" class="v10">&nbsp;<label for="cimage_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>&nbsp;</td>
                  <td bgcolor="#E7E8EB"><img src="img/leer.gif" alt="" width="6" height="15" /></td>
                </tr>
              </table></td>
              </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
</tr>
            <tr>
              <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13" /><?php echo $BL['be_cnt_caption'] ?>:&nbsp;</td>
              <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
                  <tr>
                    <td valign="top"><textarea name="cimage_caption" cols="30" rows="4" class="width300 autosize" id="cimage_caption"><?php echo  isset($content["image_caption"]) ? html($content["image_caption"]) : '' ?></textarea></td>
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
