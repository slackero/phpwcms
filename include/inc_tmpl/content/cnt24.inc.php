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


// Alias Content
$content['alias_link'] = '';

if(empty($content["alias"]['alias_ID'])) {
    $content["alias"]['alias_ID'] = '';
} else {
    $content["alias"]['alias_ID'] = intval($content["alias"]['alias_ID']);
    $sql_cnt  = "SELECT * FROM ".DB_PREPEND."phpwcms_articlecontent WHERE acontent_id=".$content["alias"]['alias_ID']." AND acontent_trash=0";
    $cntresult = _dbQuery($sql_cnt);
    if(isset($cntresult[0]['acontent_id'])) {
        $content['alias_link']  = '<td class="chatlist">&nbsp;&nbsp;&nbsp;'.$BL['be_article_cnt_edit'].':&nbsp;</td>';
        if($cntresult[0]['acontent_type'] == 30 && (!$cntresult[0]['acontent_module'] || !isset($phpwcms['modules'][$cntresult[0]['acontent_module']]))) {
            $content['alias_link'] .= '<td class="f10b error">'.$BL['be_cnt_plugin_n.a.'];
        } else {
            $content['alias_link'] .= '<td class="f10"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=';
            $content['alias_link'] .= $cntresult[0]['acontent_aid'].'&amp;acid='.$content["alias"]['alias_ID'];
            $content['alias_link'] .= '" target="_blank" class="btn">'.$wcs_content_type[$cntresult[0]['acontent_type']].'</a>';
        }
        $content['alias_link'] .= '</td>';
    }
}

$content["alias"]['alias_block']    = empty($content["alias"]['alias_block']) ? 0 : 1;
$content["alias"]['alias_spaces']   = empty($content["alias"]['alias_spaces']) ? 0 : 1;
$content["alias"]['alias_title']    = empty($content["alias"]['alias_title']) ? 0 : 1;
$content["alias"]['alias_toplink']  = empty($content["alias"]['alias_toplink']) ? 0 : 1;
$content["alias"]['alias_status']   = empty($content["alias"]['alias_status']) ? 0 : 1;

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_alias_ID'] ?>:&nbsp;</td>
    <td><table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
            <td><input name="calias" type="text" class="f11b" id="calias" style="width: 50px" value="<?php echo $content["alias"]['alias_ID'] ?>"></td>
            <?php echo $content['alias_link']; ?>
        </tr>
    </table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" width="1" height="5" alt=""></td></tr>
<tr>
    <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_setting'] ?>:&nbsp;</td>
    <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
<tr>
    <td><input type="checkbox" name="cablock" id="cablock" value="1" <?php is_checked(1, $content["alias"]['alias_block']); ?>></td>
    <td><label for="cablock">&nbsp;<?php echo $BL['be_cnt_block'] ?></label></td>
</tr>
<tr>
    <td><input type="checkbox" name="caspaces" id="caspaces" value="1" <?php is_checked(1, $content["alias"]['alias_spaces']); ?>></td>
    <td><label for="caspaces">&nbsp;<?php echo $BL['be_cnt_spaces'] ?></label></td>
</tr>
<tr>
    <td><input type="checkbox" name="catitle" id="catitle" value="1" <?php is_checked(1, $content["alias"]['alias_title']); ?>></td>
    <td><label for="catitle">&nbsp;<?php echo $BL['be_cnt_title'] ?></label></td>
</tr>
<tr>
    <td><input type="checkbox" name="catop" id="catop" value="1" <?php is_checked(1, $content["alias"]['alias_toplink']); ?>></td>
    <td><label for="catop">&nbsp;<?php echo $BL['be_cnt_toplink'] ?></label></td>
</tr>
<tr>
    <td><input type="checkbox" name="castatus" id="castatus" value="1" <?php is_checked(1, $content["alias"]['alias_status']); ?>></td>
    <td><label for="castatus">&nbsp;<?php echo $BL['be_cnt_status'] ?></label></td>
</tr>
</table></td>
