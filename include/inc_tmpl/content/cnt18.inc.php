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


//guestbook/comments

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
    <td><select name="cguestbook_template" id="cguestbook_template">
<?php
// templates for article listing
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/guestbook');
if(is_array($tmpllist) && count($tmpllist)) {
    foreach($tmpllist as $val) {
        $vals = '';
        if(isset($content["guestbook"]['template']) && $val == $content["guestbook"]['template']) $vals= ' selected="selected"';
        $val = htmlspecialchars($val);
        echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
    }
}

?>
        </select></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_alias_ID'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" summary="">

    <tr>
        <td><input name="cguestbook_aliasID" type="text" class="f11b" id="cguestbook_aliasID" style="width: 50px;" size="10"
            maxlength="10" onkeyup="if(!parseInt(this.value,10))this.value='';"
            value="<?php echo  isset($content["guestbook"]["aliasID"]) ? $content["guestbook"]["aliasID"] : '' ?>" /></td>

        <td>&nbsp;</td>
<?php

    $_aliasID_Query  = 'SELECT acontent_id,  acontent_visible, article_title, acontent_form FROM '.DB_PREPEND.'phpwcms_articlecontent';
    $_aliasID_Query .= ' LEFT JOIN '.DB_PREPEND.'phpwcms_article ON ';
    $_aliasID_Query .= ' ('.DB_PREPEND.'phpwcms_articlecontent.acontent_aid = '.DB_PREPEND.'phpwcms_article.article_id)';
    $_aliasID_Query .= ' WHERE '.DB_PREPEND.'phpwcms_articlecontent.acontent_id != '.$content["id"];
    $_aliasID_Query .= ' AND '.DB_PREPEND.'phpwcms_articlecontent.acontent_type=18';
    $_aliasID_Query .= ' AND '.DB_PREPEND.'phpwcms_articlecontent.acontent_trash=0';

    $_available_aliasID = _dbQuery($_aliasID_Query);

    if(count($_available_aliasID)) {
        echo '<td><select name="cguestbook_aliasID_select" id="cguestbook_aliasID_select" class="v10">'.LF;

        foreach($_available_aliasID as $_aliasValue) {

            $_temp_gb_data = unserialize($_aliasValue['acontent_form']);

            if(empty($_temp_gb_data['aliasID'])) {
                echo '  <option value="'.$_aliasValue['acontent_id'].'">['.$_aliasValue['acontent_id'].'] ';
                echo html(getCleanSubString($_aliasValue['article_title'], 6, '&#8230;', 'word'));
                echo '</option>'.LF;
            }

        }
        echo LF.'</select></td>';
    }
?>
    </tr>
  </table></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_guestbook_listing'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
      <?php

      if(!isset($content["guestbook"]["listing"])) {
        $content["guestbook"]["listing"] = 0;
      }

      ?>
        <td bgcolor="#E7E8EB"><input name="cguestbook_listing" id="cguestbook_listing0" type="radio" value="0" <?php is_checked(0, $content["guestbook"]["listing"]); ?> /></td>
        <td class="v10" bgcolor="#E7E8EB"><label for="cguestbook_listing0"><?php echo $BL['be_cnt_guestbook_listing_all'] ?></label>&nbsp;&nbsp;</td>
        <td bgcolor="#E7E8EB"><input name="cguestbook_listing" id="cguestbook_listing1" type="radio" value="1" <?php is_checked(1, $content["guestbook"]["listing"]); ?> /></td>
        <td class="v10" bgcolor="#E7E8EB"><label for="cguestbook_listing1"><?php echo $BL['be_cnt_guestbook_list'] ?>&nbsp;</label></td>
        <td bgcolor="#E7E8EB"><input name="cguestbook_listcount" type="text" class="f11b" id="cguestbook_listcount" style="width: 40px;" size="10" maxlength="10" onkeyup="if(!parseInt(this.value,10))this.value='';" value="<?php echo  isset($content["guestbook"]["listcount"]) ? $content["guestbook"]["listcount"] : '' ?>" /></td>
        <td class="v10" bgcolor="#E7E8EB">&nbsp;<?php echo $BL['be_cnt_guestbook_perpage'] ?>&nbsp;</td>
        <td><img src="img/leer.gif" alt="" width="1" height="22" /></td>
      </tr>
    </table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
  <td align="right" class="chatlist">&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr bgcolor="#E7E8EB">
      <?php

      if(empty($content["guestbook"]["gb_login_post"])) {
        $content["guestbook"]["gb_login_post"] = 0;
      }

      if(empty($content["guestbook"]["gb_login_show"])) {
        $content["guestbook"]["gb_login_show"] = 0;
      }

      ?>
       <td><input name="cguestbook_login_show" id="cguestbook_login_show" type="checkbox" value="1" <?php is_checked(1, $content["guestbook"]["gb_login_show"]); ?> /></td>
       <td class="v10"><label for="cguestbook_login_show">&nbsp;<?php echo $BL['be_gb_show_login'] ?>&nbsp;&nbsp;</label></td>
     </tr>

     <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>

     <tr bgcolor="#E7E8EB">
       <td><input name="cguestbook_login_post" id="cguestbook_login_post" type="checkbox" value="1" <?php is_checked(1, $content["guestbook"]["gb_login_post"]); ?> /></td>
       <td class="v10"><label for="cguestbook_login_post">&nbsp;<?php echo $BL['be_gb_post_login'] ?>&nbsp;&nbsp;</label></td>
      </tr>
      </table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_imgupload'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
      <?php

      if(!isset($content["guestbook"]["image_upload"])) {
        $content["guestbook"]["image_upload"] = 0;
      }

      if(empty($content["guestbook"]["max_image_filesize"])) {
        $content["guestbook"]["max_image_filesize"] = $phpwcms['file_maxsize'];
      }

      $content["guestbook"]["max_image_filesize"] = return_bytes_shorten($content["guestbook"]["max_image_filesize"]);

      if(return_bytes($content["guestbook"]["max_image_filesize"]) > $phpwcms['file_maxsize']) {
        $content["guestbook"]["max_image_filesize"] = return_bytes_shorten($phpwcms['file_maxsize']);
      }

      ?>
       <td bgcolor="#E7E8EB"><input name="cguestbook_imgupload" id="cguestbook_imgupload" type="checkbox" value="1" <?php is_checked(1, $content["guestbook"]["image_upload"]); ?> /></td>
       <td class="v10" bgcolor="#E7E8EB"><label for="cguestbook_imgupload">&nbsp;<?php echo $BL['be_on'] ?></label>&nbsp;&nbsp;</td>
       <td>&nbsp;&nbsp;</td>
       <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_filesize'] ?>:&nbsp;</td>
       <td ><input name="cguestbook_maximgsize" type="text" class="f11b" id="cguestbook_maximgsize" style="width: 100px;" size="20" maxlength="20" value="<?php echo $content["guestbook"]["max_image_filesize"] ?>" /></td>
       <td class="chatlist">&nbsp;(<?php echo return_bytes($content["guestbook"]["max_image_filesize"]) ?> Byte)</td>
       <td><img src="img/leer.gif" alt="" width="1" height="22" /></td>
      </tr>
      </table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sorting'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
      <tr>
      <?php

      if(!isset($content["guestbook"]["sorting"])) {
        $content["guestbook"]["sorting"] = 0;
      }

      ?>
        <td><input name="cguestbook_sorting" id="cguestbook_sorting0" type="radio" value="0" <?php is_checked(0, $content["guestbook"]["sorting"]); ?> /></td>
        <td class="v10"><label for="cguestbook_sorting0"><?php echo $BL['be_msg_date'].' '.$BL['be_admin_struct_orderdesc'] ?></label>&nbsp;</td>
        <td><input name="cguestbook_sorting" id="cguestbook_sorting1" type="radio" value="1" <?php is_checked(1, $content["guestbook"]["sorting"]); ?> /></td>
        <td class="v10"><label for="cguestbook_sorting1"><?php echo $BL['be_msg_date'].' '.$BL['be_admin_struct_orderasc'] ?></label>&nbsp;</td>
        <td><img src="img/leer.gif" alt="" width="1" height="22" /></td>
      </tr>
    </table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
  <td align="right" class="chatlist"><?php echo 'Captcha' ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
      <?php

      if(!isset($content["guestbook"]["captcha"])) {
        $content["guestbook"]["captcha"] = 1;
      }

      if(empty($content["guestbook"]["captcha_maxchar"])) {
        $content["guestbook"]["captcha_maxchar"] = 5;
      }

      ?>
       <td bgcolor="#E7E8EB"><input name="cguestbook_captcha" id="cguestbook_captcha" type="checkbox" value="1" <?php is_checked(1, $content["guestbook"]["captcha"]); ?> /></td>
       <td class="v10" bgcolor="#E7E8EB"><label for="cguestbook_captcha">&nbsp;<?php echo $BL['be_admin_usr_verify'] ?></label>&nbsp;&nbsp;</td>
       <td>&nbsp;&nbsp;</td>
       <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_captchalength'] ?>:&nbsp;</td>
       <td><input name="cguestbook_captchamaxchar" type="text" class="f11b" id="cguestbook_captchamaxchar" style="width: 25px;" size="3" maxlength="2" value="<?php echo $content["guestbook"]["captcha_maxchar"] ?>" /></td>
       <td class="chatlist">&nbsp;<?php echo $BL['be_cnt_chars'] ?></td>
       <td><img src="img/leer.gif" alt="" width="1" height="22" /></td>
      </tr>
      </table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_profile_label_website'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
      <tr>
      <?php

      if(empty($content["guestbook"]["gb_urlcheck"])) {
        $content["guestbook"]["gb_urlcheck"] = 0;
      }

      ?>
       <td bgcolor="#E7E8EB"><input name="cguestbook_urlcheck" id="cguestbook_urlcheck" type="checkbox" value="1" <?php is_checked(1, $content["guestbook"]["gb_urlcheck"]); ?> /></td>
       <td class="v10" bgcolor="#E7E8EB"><label for="cguestbook_urlcheck">&nbsp;<?php echo $BL['be_gb_urlcheck'] ?>&nbsp;&nbsp;</label></td>
      </tr>
      </table></td>
</tr>

<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13" /><?php echo $BL['be_cnt_guestbook_banned'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cguestbook_banned" cols="40" rows="3" class="width440 autosize"><?php echo  isset($content["guestbook"]["banned"]) ? html($content["guestbook"]["banned"]) : '' ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td>
</tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_guestbook_flooding'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
      <tr>
        <td><input name="cguestbook_cookie" type="checkbox" value="1"<?php

        if(!isset($content["guestbook"]["cookie"])) $content["guestbook"]["cookie"] = 1;
        is_checked(1, intval($content["guestbook"]["cookie"]))

        ?> /></td>
        <td class="v10"><?php echo $BL['be_cnt_guestbook_setcookie'] ?>&nbsp;&nbsp;&nbsp;</td>
        <td class="v10"><?php echo$BL['be_cnt_guestbook_allowed'] ?>&nbsp;</td>
        <td><input name="cguestbook_time" type="text" class="f11" id="cguestbook_time" style="width: 65px;" size="10" maxlength="10" value="<?php

        if(!isset($content["guestbook"]["time"])) {
            $content["guestbook"]["time"] = 86400;
        }
        echo intval($content["guestbook"]["time"]);

        ?>" /></td>
        <td class="v10">&nbsp;<?php echo $BL['be_cnt_guestbook_seconds'] ?></td>
        <td><img src="img/leer.gif" alt="" width="10" height="22" /></td>
      </tr>
    </table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
<?php

if(!isset($content["guestbook"]["notify"])) {
    $content["guestbook"]["notify"] = 0;
}
if(!isset($content["guestbook"]["notify_email"])) {
    $content["guestbook"]["notify_email"] = '';
}

?>
<tr>
     <td align="right" class="chatlist"><?php echo $BL['be_cnt_email_notify'] ?>:&nbsp;</td>
     <td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">

     <tr>
        <td><input name="cguestbook_notify" id="cguestbook_notify" type="checkbox" value="1" <?php is_checked(1, $content["guestbook"]["notify"]); ?> /></td>
        <td class="v10"><label for="cguestbook_notify"><?php echo $BL['be_cnt_notify_by_email'] ?></label>&nbsp;</td>
        <td><input name="cguestbook_notify_email" type="text" class="f11" id="cguestbook_notify_email" style="width: 200px;" size="10" value="<?php echo html($content["guestbook"]["notify_email"]) ?>" /></td>
     </tr>

     </table></td>
</tr>

<?php
// show possibility to edit guestbook entries
// if content part is created
if($content["id"]) {
?>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="15" /><?php echo $BL['be_cnt_guestbook_edit'] ?>:&nbsp;</td>
  <td><iframe height="350" width="440" frameborder="0" scrolling="Auto" src="include/inc_act/act_guestbook.php?<?php echo CSRF_GET_TOKEN; ?>&amp;cid=<?php echo empty($content["guestbook"]["aliasID"]) ? $content["id"] : $content["guestbook"]["aliasID"]; ?>"></iframe></td>
</tr>


<?php
}
?>