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


//ecard

$imgx = 0;
$img_thumbs = '';
$caption_box = '';

if(!isset($content["ecard"])) {

    $content["ecard"] = array(

        "subject"   => '',
        "selector"  => 0,
        "onover"    => '',
        "onclick"   => '',
        "onout"     => '',
        "select"    => array(),
        'images'    => array(),
        "pos"       => 0,
        "width"     => '',
        "height"    => '',
        "form"      => false,
        "send"      => '',
        "mail"      => '',
        'col'       => 1,
        'space'     => '',
        'zoom'  => 0

    );

}

?>

<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_subject'] ?>:&nbsp;</td>
    <td><input name="cecard_subject" type="text" id="cecard_subject" class="f11b" style="width: 440px" value="<?php echo html($content["ecard"]["subject"]) ?>" size="40" maxlength="250"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_ecardform_selector'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
      <tr>
        <td><input name="cecard_selector" type="radio" value="0" <?php is_checked(0, $content["ecard"]["selector"]); ?>></td>
        <td class="v10"><?php echo $BL['be_cnt_ecardform_radiobutton'] ?>&nbsp;</td>
        <td><input name="cecard_selector" type="radio" value="1" <?php is_checked(1, $content["ecard"]["selector"]); ?>></td>
        <td class="v10"><?php echo $BL['be_cnt_ecardform_javascript'] ?>&nbsp;&nbsp;&nbsp;</td>
      </tr>
    </table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_ecardform_over'] ?>:&nbsp;</td>
    <td><input name="cecard_onover" type="text" id="cecard_over" class="f11" style="width: 440px" value="<?php echo html($content["ecard"]["onover"]) ?>" size="40" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_ecardform_click'] ?>:&nbsp;</td>
    <td><input name="cecard_onclick" type="text" id="cecard_onclick" class="f11" style="width: 440px" value="<?php echo html($content["ecard"]["onclick"]) ?>" size="40" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_cnt_ecardform_out'] ?>:&nbsp;</td>
    <td><input name="cecard_onout" type="text" id="cecard_onout" class="f11" style="width: 440px" value="<?php echo html($content["ecard"]["onout"]) ?>" size="40" /></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td valign="top"><select name="cimage_list[]" size="<?php echo isset($content["ecard"]["select"]) && count($content["ecard"]["select"]) ? count($content["ecard"]["select"])+5 : 5 ?>" multiple="multiple" class="width200" id="cimage_list">
<?php
if(is_array($content['ecard']['images']) && count($content['ecard']['images'])) {

    // browse images and list available
    // will be visible only when aceessible
    foreach($content['ecard']['images'] as $key => $value) {

        $caption_box .= html($content['ecard']['images'][$key][6])."\n";

        $thumb_image = get_cached_image(array(
            "target_ext"    =>  $content['ecard']['images'][$key][3],
            "image_name"    =>  $content['ecard']['images'][$key][2] . '.' . $content['ecard']['images'][$key][3],
            "thumb_name"    =>  md5($content['ecard']['images'][$key][2].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
        ));

        if($thumb_image != false) {

            // image found
            echo '<option value="' . $content['ecard']['images'][$key][0] . '">';
            $img_name = html($content['ecard']['images'][$key][1]);
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
        <td valign="top"><img src="img/leer.gif" alt="" width="5" height="1"></td>                                           <!-- browser_image.php //-->
        <td valign="top"><a href="javascript:" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=3&amp;target=nolist')"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0"></a><br />
          <img src="img/leer.gif" alt="" width="1" height="4"><br />
          <a href="javascript:" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cimage_list);"><img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0"></a><a href="javascript:;" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cimage_list);"><img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0"></a><br />
          <img src="img/leer.gif" alt="" width="1" height="4"><br />
          <a href="javascript:" onclick="removeSelectedOptions(document.articlecontent.cimage_list);" title="<?php echo $BL['be_cnt_delimage'] ?>"><img src="img/button/del_image_button1.gif" alt="" width="20" height="15" border="0"></a></td>
      </tr>
    </table><?php

if(isset($img_thumbs) && $img_thumbs) {
    echo '<table border="0" cellspacing="0" cellpadding="0">
        <tr><td style="padding-bottom:3px;"><img src="img/leer.gif" width="1" height="5"><br>'.$img_thumbs.'</td></tr>
        </table>';
}

?></td>
</tr>
<tr>
  <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td>
</tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_alignment'] ?>:&nbsp;</td>
  <td><select name="cecard_pos" id="cecard_pos">
                <option value="0" <?php is_selected(0, $content["ecard"]["pos"]) ?>><?php echo $BL['be_cnt_left'].' ('.$BL['be_cnt_default'].')' ?></option>
                <option value="1" <?php is_selected(1, $content["ecard"]["pos"]) ?>><?php echo $BL['be_cnt_center'] ?></option>
                <option value="2" <?php is_selected(2, $content["ecard"]["pos"]) ?>><?php echo $BL['be_cnt_right'] ?></option>
              </select></td>
</tr>
<tr>
  <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td>
</tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_maxw'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td><input name="cecard_width" type="text" class="f11b" id="cecard_width" style="width: 50px;" size="3" maxlength="4" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content["ecard"]["width"] ?>"></td>
        <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_maxh'] ?>:&nbsp;</td>
        <td><input name="cecard_height" type="text" class="f11b" id="cecard_height" style="width: 50px;" size="3" maxlength="4" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content["ecard"]["height"] ?>"></td>
        <td class="chatlist">&nbsp;&nbsp;px</td>
      </tr>
    </table></td>
</tr>
<tr>
  <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td>
</tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_column'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
        <td><select name="cecard_col" id="cecard_col">
<?php
// list select menu for max image columns
for($max_image_col = 1; $max_image_col <= 25; $max_image_col++) {

    echo '<option value="'.$max_image_col.'" ';
    is_selected($max_image_col, $content['ecard']['col']);
    echo '>'.$max_image_col."</option>\n";

}

?>
          </select></td>
        <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_imagespace'] ?>:&nbsp;</td>
        <td><input name="cecard_space" type="text" class="f11b" id="cecard_space" style="width: 40px;" size="2" maxlength="2" onKeyUp="if(!parseInt(this.value,10)) this.value='';" value="<?php echo $content["ecard"]["space"] ?>"></td>
        <td class="chatlist">&nbsp;&nbsp;px&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td bgcolor="#E7E8EB">&nbsp;</td>
        <td bgcolor="#E7E8EB"><input name="cecard_zoom" type="checkbox" id="cecard_zoom" value="1" <?php is_checked(1, $content["ecard"]["zoom"]); ?>></td>
        <td bgcolor="#E7E8EB" class="v10">&nbsp;<?php echo $BL['be_cnt_enlarge'] ?>&nbsp;</td>
        <td bgcolor="#E7E8EB"><img src="img/leer.gif" alt="" width="6" height="15"></td>
      </tr>
    </table></td>
</tr>
<tr>
  <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td>
</tr>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_ecardtext'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cecard_caption" cols="40" rows="3" wrap="off" class="width440" id="cecard_caption"><?php echo isset($caption_box) ? $caption_box : '' ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
<tr>
  <td align="right" valign="top" class="chatlist">&nbsp;</td>
  <td valign="top" class="chatlist">
    HTML: ###ECARD_SUBJECT###, ###SENDER_NAME###,  ###SENDER_EMAIL###,<br>
    ###RECIPIENT_NAME###, ###RECIPIENT_EMAIL###, ###SENDER_MESSAGE###, <br />###ECARD_CHOOSER###,
    &lt;!--FORM_ERROR_START--&gt; &lt;!--FORM_ERROR_END--&gt;
  </td>
</tr>
<tr>
  <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td>
</tr>
<?php

// fill in the default template if form value is empty

if(!$content["ecard"]["form"]) {
    $content["ecard"]["form"]  = '<div align="center" style="margin:0 0 0 0;padding:10px 0 10px 0;">###ECARD_CHOOSER###</div>'."\n";
    $content["ecard"]["form"] .= '<table border="0" align="center" cellpadding="4" cellspacing="0" style="font-size:11px;font-family:Verdana,Arial,sans-serif;">'."\n";
    $content["ecard"]["form"] .= "<!--FORM_ERROR_START-->\n";
    $content["ecard"]["form"] .= '<tr><td colspan="5" style="margin:0 0 0 0;padding:0 0 0 0;"><table border="0" cellspacing="0" cellpadding="0">'."\n";
    $content["ecard"]["form"] .= '<tr><td valign="top"><img src="img/symbole/achtung.gif" width="13" height="11" />&nbsp;</td>'."\n";
    $content["ecard"]["form"] .= '<td valign="top"><strong style="color:#CC3300;">'.$BL['be_cnt_ecardform_err'].'</strong></td>'."\n";
    $content["ecard"]["form"] .= "</tr></table></td></tr>\n";
    $content["ecard"]["form"] .= '<tr><td colspan="5"><img src="img/leer.gif" width="1" height="2" /></td></tr>'."\n";
    $content["ecard"]["form"] .= "<!--FORM_ERROR_END-->\n";
    $content["ecard"]["form"] .= "<tr>\n";
    $content["ecard"]["form"] .= '<td colspan="2" bgcolor="#99CC00"><strong style="color:#FFFFFF;">&nbsp;'.$BL['be_cnt_ecardform_sender'].'</strong></td>'."\n";
    $content["ecard"]["form"] .= '<td rowspan="5"><img src="img/leer.gif" width="3" height="1" /></td>'."\n";
    $content["ecard"]["form"] .= '<td colspan="2" bgcolor="#99CC00"><strong style="color:#FFFFFF;">&nbsp;'.$BL['be_cnt_ecardform_recipient'].'</strong></td>'."\n";
    $content["ecard"]["form"] .= "</tr><tr>\n";
    $content["ecard"]["form"] .= '<td colspan="2" bgcolor="#F8FFDF" style="margin:0 0 0 0;padding:2px 0 0 0;"><img src="img/leer.gif" width="1" height="1" /></td>'."\n";
    $content["ecard"]["form"] .= '<td colspan="2" bgcolor="#F8FFDF" style="margin:0 0 0 0;padding:2px 0 0 0;"><img src="img/leer.gif" width="1" height="1" /></td>'."\n";
    $content["ecard"]["form"] .= "</tr>\n";
    $content["ecard"]["form"] .= "<tr>\n";
    $content["ecard"]["form"] .= '<td bgcolor="#F8FFDF">&nbsp;'.$BL['be_cnt_ecardform_name'].':</td>'."\n";
    $content["ecard"]["form"] .= '<td bgcolor="#F8FFDF"><input name="###SENDER_NAME###" type="text" style="font-size:12px; width:150px;margin-right:3px;" value="###SENDER_NAME###" size="25" /></td>'."\n";
    $content["ecard"]["form"] .= '<td bgcolor="#F8FFDF">&nbsp;'.$BL['be_cnt_ecardform_name'].':</td>'."\n";
    $content["ecard"]["form"] .= '<td bgcolor="#F8FFDF"><input name="###RECIPIENT_NAME###" type="text" style="font-size:12px; width:150px;margin-right:3px" value="###RECIPIENT_NAME###" size="25" /></td>'."\n";
    $content["ecard"]["form"] .= "</tr>\n";
    $content["ecard"]["form"] .= "<tr>\n";
    $content["ecard"]["form"] .= '<td bgcolor="#F8FFDF">&nbsp;'.$BL['be_profile_label_email'].'<span style="color:#CC3300;">*</span>:</td>'."\n";
    $content["ecard"]["form"] .= '<td bgcolor="#F8FFDF"><input name="###SENDER_EMAIL###" type="text" style="font-size:12px; width:150px;" value="###SENDER_EMAIL###" size="25" /></td>'."\n";
    $content["ecard"]["form"] .= '<td bgcolor="#F8FFDF">&nbsp;'.$BL['be_profile_label_email'].'<span style="color:#CC3300;">*</span>:</td>'."\n";
    $content["ecard"]["form"] .= '<td bgcolor="#F8FFDF"><input name="###RECIPIENT_EMAIL###" type="text" style="font-size:12px; width:150px;" value="###RECIPIENT_EMAIL###" size="25" /></td>'."\n";
    $content["ecard"]["form"] .= "</tr>\n";
    $content["ecard"]["form"] .= "<tr>\n";
    $content["ecard"]["form"] .= '<td colspan="2" bgcolor="#F8FFDF" style="margin:0 0 0 0;padding:2px 0 0 0;"><img src="img/leer.gif" width="1" height="1" /></td>'."\n";
    $content["ecard"]["form"] .= '<td colspan="2" bgcolor="#F8FFDF" style="margin:0 0 0 0;padding:2px 0 0 0;"><img src="img/leer.gif" width="1" height="1" /></td>'."\n";
    $content["ecard"]["form"] .= "</tr>\n";
    $content["ecard"]["form"] .= '<tr><td colspan="5"><img src="img/leer.gif" width="1" height="3" /></td></tr>'."\n";
    $content["ecard"]["form"] .= '<tr bgcolor="#99CC00"><td colspan="5"><strong style="color:#FFFFFF;">&nbsp;'.$BL['be_cnt_ecardform_msgtext'].'</strong></td></tr>'."\n";
    $content["ecard"]["form"] .= '<tr bgcolor="#F8FFDF"><td colspan="5" style="margin:0 0 0 0;padding:2px 0 0 0;"><img src="img/leer.gif" width="1" height="1" /></td></tr>'."\n";
    $content["ecard"]["form"] .= '<tr align="center" bgcolor="#F8FFDF">'."\n";
    $content["ecard"]["form"] .= '<td colspan="5"><textarea name="###SENDER_MESSAGE###" cols="50" rows="6" id="ecard_sender_msg" class="width440 autosize">###SENDER_MESSAGE###</textarea></td></tr>'."\n";
    $content["ecard"]["form"] .= '<tr bgcolor="#F8FFDF"><td colspan="5" style="margin:0 0 0 0;padding:4px 0 0 0;"><img src="img/leer.gif" width="1" height="1" /></td></tr>'."\n";
    $content["ecard"]["form"] .= '<tr align="center" bgcolor="#F8FFDF"><td colspan="5"><input name="###BUTTON###" type="submit" value="'.$BL['be_cnt_ecardform_button'].'" style="font-size:12px;" /></td></tr>'."\n";
    $content["ecard"]["form"] .= '<tr bgcolor="#F8FFDF"><td colspan="5"><img src="img/leer.gif" width="1" height="1" /></td></tr>'."\n";
    $content["ecard"]["form"] .= '</table>';

}

?>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_ecardform'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cecard_form" rows="15" wrap="VIRTUAL" class="code width440 autosize" id="cecard_form"><?php echo html($content["ecard"]["form"]); ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
<tr>
  <td align="right" valign="top" class="chatlist">&nbsp;</td>
  <td valign="top" class="chatlist">HTML: ###ECARD_SUBJECT###, ###RECIPIENT_NAME###,
        ###RECIPIENT_EMAIL###,<br /> ###SENDER_MESSAGE###,
        ###ECARD_TITLE###, ###ECARD_IMAGE###</td>
</tr>
<tr>
  <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td>
</tr>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_ecardsend'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cecard_send" rows="5" wrap="VIRTUAL" class="code width440 autosize" id="cecard_send"><?php echo html($content["ecard"]["send"]); ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4"></td></tr>
<tr>
  <td align="right" valign="top" class="chatlist">&nbsp;</td>
  <td valign="top" class="chatlist">HTML: ###ECARD_SUBJECT###, ###SENDER_NAME###,
    ###SENDER_EMAIL###, <br />###RECIPIENT_NAME###,
    ###RECIPIENT_EMAIL###, ###SENDER_MESSAGE###, <br />
    ###ECARD_IMAGE###, ###ECARD_TITLE###</td>
</tr>
<tr>
  <td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td>
</tr>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_ecardtmpl'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cecard_mail" rows="15" wrap="VIRTUAL" class="code width440 autosize" id="cecard_mail"><?php echo  html($content["ecard"]["mail"]) ?></textarea></td>
</tr>
