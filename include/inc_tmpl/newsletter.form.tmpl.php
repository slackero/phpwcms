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


// show newsletter form

?>
<div class="title" style="margin-bottom:10px"><?php echo $BL['be_newsletter_titleeditnl'] ?></div>
<form action="phpwcms.php?do=messages&amp;p=3&amp;s=<?php echo $newsletter["newsletter_id"] ?>&amp;edit=1" method="post" name="newsletter" target="_self" id="newsletter" onsubmit="hideLayer('newsletterButtons');enableStatusMessage('statusMessage', true, false);">
<table border="0" cellpadding="0" cellspacing="0" summary="" width="100%">
    <tr><td colspan="2" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="9" /></td></tr>
    <tr bgcolor="#F3F5F8">
        <td align="right" class="chatlist"><?php

        echo $BL['be_msg_subject'];
        if(!empty($newsletter['error']['subject'])) {
            echo '<img src="img/symbole/error_9x9.gif" width="9" height="9" alt="" />';
        }


    ?>:&nbsp;</td>
        <td><input name="newsletter_subject" type="text" class="f11b" id="newsletter_subject" style="width:400px" value="<?php echo html($newsletter["newsletter_subject"]) ?>" size="50" maxlength="250" onchange="hideLayer('messagesend');" /></td>
    </tr>
    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

<?php
    if(!empty($newsletter["newsletter_created"])) {
        echo '<tr bgcolor="#F3F5F8"><td align="right" class="chatlist">' .$BL['be_fprivedit_created']. ':&nbsp;</td><td><strong>';
        echo @date($BL['be_fprivedit_dateformat'], strtotime($newsletter["newsletter_created"]));
        echo '</strong></td></tr>';
        echo '<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>';
    }


?>
    <tr bgcolor="#F3F5F8">
        <td align="right" class="chatlist"><?php echo $BL['be_newsletter_changed'] ?>:&nbsp;</td>
        <td><strong><?php

        if(isset($newsletter['error'])) $newsletter["newsletter_date"] = time();
        echo @date($BL['be_fprivedit_dateformat'], $newsletter["newsletter_date"]);

        ?></strong></td>
    </tr>
    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
    <tr><td colspan="2" bgcolor="#d9dee3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_newsletter_fromname'] ?>:&nbsp;</td>
        <td><input name="newsletter_fromname" type="text" class="f11" id="newsletter_fromname" style="width:400px" value="<?php echo html($newsletter["newsletter_vars"]['from_name']) ?>" size="50" maxlength="250" /></td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
    <tr>
        <td align="right" class="chatlist"><?php

        echo $BL['be_newsletter_fromemail'];
        if(isset($newsletter['error']) && isset($newsletter['error']['from_email'])) {
            echo '<img src="img/symbole/error_9x9.gif" width="9" height="9" alt="" />';
        }

        ?>:&nbsp;</td>
        <td><input name="newsletter_fromemail" type="text" class="f11" id="newsletter_fromemail" style="width:400px" value="<?php echo html($newsletter["newsletter_vars"]['from_email']) ?>" size="50" maxlength="250" /></td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
    <tr>
        <td align="right" class="chatlist"><?php

        echo $BL['be_newsletter_replyto'];
        if(isset($newsletter['error']) && isset($newsletter['error']['replyto'])) {
            echo '<img src="img/symbole/error_9x9.gif" width="9" height="9" alt="" />';
        }

        ?>:&nbsp;</td>
        <td><input name="newsletter_replyto" type="text" class="f11" id="newsletter_replyto" style="width:400px" value="<?php echo html($newsletter["newsletter_vars"]['replyto']) ?>" size="50" maxlength="250" /></td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
    <tr>
        <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="14" /><?php echo $BL['be_cnt_subscription'] ?>:&nbsp;</td>
        <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">

        <tr>
            <td><input name="newsletter_subscription[0]" id="nls0" type="checkbox" value="0" <?php if(isset($newsletter["newsletter_vars"]["subscription"][0]) && $newsletter["newsletter_vars"]["subscription"][0] === 0) echo ' checked="checked"'; ?> /></td>
            <td><label for="nls0"><?php echo $BL['be_newsletter_allsubscriptions']; ?></label></td>
        </tr>

<?php
    //retrieve available subscription lists/channels

    $sql = "SELECT subscription_id,subscription_name FROM ".DB_PREPEND."phpwcms_subscription ORDER BY subscription_name";
    $result = _dbQuery($sql);

    if(isset($result[0]['subscription_id'])) {
        foreach($result as $row) {
?>
            <tr>
                <td>
                    <input type="checkbox"
                        name="newsletter_subscription[<?php echo $row['subscription_id']; ?>]"
                        id="nls<?php echo $row['subscription_id']; ?>"
                        value="<?php echo $row['subscription_id']; ?>"<?php
                        if(!empty($newsletter["newsletter_vars"]["subscription"]) && count($newsletter["newsletter_vars"]["subscription"])) {
                            foreach($newsletter["newsletter_vars"]["subscription"] as $value) {
                                if($value == $row['subscription_id']) {
                                    echo ' checked="checked"';
                                    break;
                                }
                            }
                        }
                        ?> />
                </td>
                <td><label for="nls<?php echo $row['subscription_id']; ?>"><?php echo html($row['subscription_name']); ?></label></td>
            </tr>

<?php
        }
    }
?>
        </table></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

<?php

    // newsletter templates

    $BE['HEADER']['newsletter.form.js'] = getJavaScriptSourceLink('include/inc_js/newsletter.form.js');

    $tmpllist = returnSubdirListAsArray(PHPWCMS_TEMPLATE.'inc_newsletter');
    $tmpldata = array('options' => array(), 'js' => array(), 'files' => array() );
    $value3   = '';
    if(is_array($tmpllist) && count($tmpllist)) {
        if(empty($newsletter["newsletter_vars"]['template'])) {
            $newsletter["newsletter_vars"]['template'] = '';
        }
        $i = 0;
        foreach($tmpllist as $value) {
            $value1 = html($value);
            $tmpldata['options'][$i]  = '<option value="'.$value1.'"';
            if($value == $newsletter["newsletter_vars"]['template']) {
                $tmpldata['options'][$i] .= ' selected="selected"';
                $value3 = $value;
            }
            $tmpldata['options'][$i] .= '>'.$value1.'</option>'.LF;
            $tmpldata['files'][$i] = returnFileListAsArray(PHPWCMS_TEMPLATE.'inc_newsletter/'.$value);

            // chech against tmpl file
            if(isset($tmpldata['files'][$i]['newsletter.tmpl'])) {
                $value2 = @file_get_contents(PHPWCMS_TEMPLATE.'inc_newsletter/'.$value.'/newsletter.tmpl');
                $value2 = get_tmpl_section('NEWSLETTER_SETTINGS', $value2);
                $value2 = parse_ini_str($value2, false);
                if(empty($value2['title']))         $value2['title'] = '';
                if(empty($value2['description']))   $value2['description'] = '';
            } else {
                $value2 = array('title'=>'', 'description'=>'');
            }

            $tmpldata['js'][] = '  nltemplate["'.$value.'"] = new Array();';
            $tmpldata['js'][] = '  nltemplate["'.$value.'"]["title"] = "'.js_singlequote($value2['title']).'";';
            $tmpldata['js'][] = '  nltemplate["'.$value.'"]["description"] = "'.js_singlequote($value2['description']).'";';

            $value2 = '';
            // set preview image
            if(isset($tmpldata['files'][$i]['preview.gif'])) {
                $value2 = 'preview.gif';
            } elseif(isset($tmpldata['files'][$i]['preview.jpg'])) {
                $value2 = 'preview.jpg';
            } elseif(isset($tmpldata['files'][$i]['preview.png'])) {
                $value2 = 'preview.png';
            }
            if($value2) {
                $tmpldata['js'][] = '  nltemplate["'.$value.'"]["imgsrc"] = "'.js_singlequote(TEMPLATE_PATH.'inc_newsletter/'.$value.'/'.$value2).'";';
            }

            $i++;
        }
    }

?>
    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
        <td><select name="newsletter_template" id="newsletter_template" onchange="showNewsletterTemplateData(this.options[this.selectedIndex].value);">
            <option value=""<?php if(empty($newsletter["newsletter_vars"]['template'])) echo ' selected="selected"' ?>><?php echo $BL['be_admin_tmpl_default'].' ('.$BL['be_func_struct_empty'].')' ?></option>
            <?php echo implode($tmpldata['options']) ?>
        </select></td>
    </tr>

    <tr>
        <td><img src="img/leer.gif" alt="" width="1" height="3" /></td>
        <td id="newsletterTemplateInfo" style="padding:4px 0 4px 0"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
    <tr><td colspan="2" bgcolor="#d9dee3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

    <tr><td colspan="2" style="padding-left:2px">
<script type="text/javascript">
  var nltemplate = [];
<?php
    echo implode(LF, $tmpldata['js']).LF;
    echo '  showNewsletterTemplateData("'.$value3.'");';
?>
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr><td class="chatlist"><?php echo $BL['be_newsletter_htmlpart'] ?>:</td></tr>
    <tr><td><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr>
        <td><?php

$wysiwyg_editor = array(
    'value'     => $newsletter["newsletter_vars"]['html'],
    'field'     => 'newsletter_html',
    'height'    => '350px',
    'width'     => '100%',
    'rows'      => '20',
    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    'lang'      => 'en'
);
include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

?></td></tr>

    <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td>
    </tr>
    <tr><td class="chatlist"><?php echo $BL['be_newsletter_textpart'] ?>:</td></tr>
    <tr><td><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr><td><textarea name="newsletter_text" rows="25" wrap="off" class="code width540 autosize"><?php echo html($newsletter["newsletter_vars"]['text']) ?></textarea></td></tr>

    </table>
    </td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
    <tr>
        <td colspan="2" class="v10"><span class="chatlist"><?php echo $BL['be_newsletter_placeholder'] ?>:</span>
            ###RECIPIENT_NAME###,
            ###RECIPIENT_EMAIL###,
            ###VERIFY_LINK###,
            ###DELETE_LINK###,
            ###SITE_URL###
        </td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
    <tr><td colspan="2" bgcolor="#d9dee3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

    <tr>
        <td align="right" class="chatlist" valign="top" style="padding-top: 3px;"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
        <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">

        <tr>
            <td valign="top"><input name="newsletter_active" id="newsletter_active" type="checkbox" value="1"<?php is_checked(1, $newsletter["newsletter_active"]); ?> /></td>
            <td style="padding-top: 2px;"><label for="newsletter_active"><strong><?php echo $BL['be_cnt_newsletter_prepare'] ?></strong><br /><span class="v10"><?php echo $BL['be_cnt_newsletter_prepare1'] ?></span></label></td>
        </tr>

        </table></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>
    <tr>
        <td>&nbsp;</td>
        <td>
        <input name="newsletter_id" type="hidden" value="<?php echo $newsletter["newsletter_id"] ?>" />
        <div id="newsletterButtons">
        <input name="submit" type="submit" class="button" value="<?php echo empty($newsletter["newsletter_id"]) ? $BL['be_article_cnt_button2'] : $BL['be_article_cnt_button1'] ?>" />
        &nbsp;
        <input name="close" type="submit" class="button" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" class="button" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=messages&amp;p=3';" />
        </div>
        <div id="statusMessage"><img src="img/indicator/indicator_arrows_green.gif" alt="Indicator" width="16" height="16" class="icon" /><p><?php echo $BL['be_cnt_newsletter_prepare2'] ?></p></div></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
    </tr>

</table>
</form>