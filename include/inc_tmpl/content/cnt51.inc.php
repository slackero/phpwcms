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

//map

if(!isset($content["map"])) {
    $content["map"]['template'] = '';
    $content["map"]['image'] = '';
    $content["map"]["text"] = '';
}

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
    <td align="right" class="chatlist"><?php echo $BL['be_admin_struct_template'] ?>:&nbsp;</td>
    <td><select name="cmap_template" id="cmap_template">
<?php
// templates for article listing
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/map');
if(is_array($tmpllist) && count($tmpllist)) {
    foreach($tmpllist as $val) {
        $vals = '';
        if($val == $content["map"]['template']) $vals= ' selected="selected"';
        $val = htmlspecialchars($val);
        echo '<option value="'.$val.'"'.$vals.'>'.$val."</option>\n";
    }
}

?>
        </select></td>
</tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
    <td valign="top" align="right" class="chatlist"><img src="img/leer.gif" width="1" height="14" alt=""><?php echo $BL['be_ctype_map'] ?>:&nbsp;</td>
    <td valign="top"><?php

// select the map image
$g = '';
$map_selected = 0;
$map_name = '';
$imglist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_cntpart/map/map_img', 'jpg,gif,png,jpeg');
if(is_array($imglist) && count($imglist)) {
    foreach($imglist as $val) {
        $vals = '';
        if($val == $content["map"]['image']) {
            $vals= ' checked="checked"';
            $map_selected = 1;
            $map_name = $content["map"]['image'];
            $map_info = getimagesize(PHPWCMS_TEMPLATE.'inc_cntpart/map/map_img/'.$map_name);
        }
        $val = html($val);
        $g .= '<tr><td>';
        $g .= '<input type="radio" name="cmap_image" value="'.$val.'"'.$vals.' />&nbsp;';
        $g .= '</td><td class="f11b">'.$val.'&nbsp;&nbsp;</td><td>';
        $g .= '<a href="javascript:void(0);" onmouseover="this.T_WIDTH=150;this.T_DELAY=100;this.T_PADDING=6;this.T_BGCOLOR=\'#ffffff\';';
        $gp = trim($phpwcms["templates"], '/').'/inc_cntpart/map/map_img/'.$val;
        $g .= 'Tip(\'<img style=\\\'min-width:100px;max-width:225px\\\' src=\\\''.$gp.'\\\'>\');">';
        $g .= '<img src="img/button/button_img_mouseover.gif" alt="" width="11" height="11" border="0">';
        $g .= "</a></td></tr>\n";
    }
}
if($g) {
    echo '<table cellspacing="0" cellpadding="0" border="0">'."\n".$g.'</table>';
} else {
    echo '<img src="img/leer.gif" width="1" height="13" alt=""><span class="error">no map available. upload one first.</span>';
}

?></td>
</tr>
<?php
// if a map is selected show possible add/edit new point
if($map_selected) {

    if(isset($_GET['dellocid']) && intval($_GET['dellocid'])) {

        _dbQuery("UPDATE ".DB_PREPEND."phpwcms_map SET map_deleted=9 WHERE map_cid=".intval($content["id"])." AND map_id=".intval($_GET['dellocid']), 'UPDATE');

    }

    $map_current = (isset($_GET['locid'])) ? intval($_GET['locid']) : 0;
    $map_sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_map WHERE map_deleted=0 ";
    $map_sql .= "AND map_cid=".intval($content["id"])." ORDER BY map_zip ASC, map_city ASC";
    $map_result = _dbQuery($map_sql);

    $map_list = '';
    $map_xy = array();
    $ck = 0;

    if(isset($map_result[0]['map_id'])) {
        foreach($map_result as $map_row) {
            if($map_row['map_id'] == $map_current) {

                $content["location"]['id']      = $map_row['map_id'];
                $content["location"]['x']       = $map_row['map_x'];
                $content["location"]['y']       = $map_row['map_y'];
                $content["location"]['title']   = $map_row['map_title'];
                $content["location"]['zip']     = $map_row['map_zip'];
                $content["location"]['city']    = $map_row['map_city'];
                $content["location"]['entry']   = $map_row['map_entry'];

            }
            $map_row['map_x'] = html($map_row['map_x']);
            $map_list .= '<tr'.(($ck % 2) ? ' bgcolor="#FBFCFC"' : '').">\n";
            $map_list .= '<td class="v09">'.$map_row['map_x'].'x'.$map_row['map_y']."</td>\n";
            $map_list .= '<td class="v09" width="90%"><strong>'.$map_row['map_title']."</strong></td>\n";
            $map_list .= '<td align="right" class="v09"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;';
            $map_list .= 'id='.$content["aid"].'&amp;acid='.$content["id"].'&amp;locid='.$map_row['map_id'].'">';
			$map_list .= '<img src="img/button/edit_22x13.gif" width="22" height="13" border="0" alt=""></a>';
            $map_list .= '<img src="img/leer.gif" width="1" height="1" alt="" border="0">';
            $map_list .= '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;';
            $map_list .= 'id='.$content["aid"].'&amp;acid='.$content["id"].'&amp;dellocid='.$map_row['map_id'];
            $map_list .= '" onclick="return confirm(\''.$BL['be_btn_delete'].' \n';
            $map_list .= $map_row['map_title'].'\');">';
            $map_list .= '<img src="img/button/del_11x11.gif" width="11" height="11" alt="" border="0">';
            $map_list .= "</a></td>\n</tr>\n";
            $map_xy[] = $map_row['map_x'].':::'.$map_row['map_y'].':::'.$map_row['map_title'];

            $ck++;
        }
    }

?>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo ($map_current) ? $BL['be_cnt_map_edit'] : $BL['be_cnt_map_add']; ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
                <tr>
                  <td class="v10">&nbsp;&nbsp;X:&nbsp; </td>
                  <td><input name="cmap_location_x" type="text" class="f10" id="cmap_location_x" style="width: 50px;" size="4" maxlength="4" value="<?php echo  empty($content['location']["x"]) ? '' : intval($content['location']["x"]) ?>" onChange="doMapChange();"></td>
                  <td class="v10">&nbsp;&nbsp;Y:&nbsp; </td>
                  <td><input name="cmap_location_y" type="text" class="f10" id="cmap_location_y" style="width: 50px;" size="4" maxlength="4" value="<?php echo  empty($content['location']["y"]) ? '' : intval($content['location']["y"]) ?>" onChange="doMapChange();"></td>
                  <td class="v10">&nbsp;px&nbsp;&nbsp;&nbsp;</td>
                  <td class="v10"><input name="open_map" type="button" value="open map" class="v09"
                  onclick="flevPopupLink('include/inc_tmpl/content/cnt51.open.php?cid=<?php
                  echo $content["id"] ?>&map=<?php
                  echo rawurlencode($map_name);
                  echo '&points='.rawurlencode(implode(':|:', $map_xy));
                  ?>','map','<?php
                  echo 'scrollbars=yes,resizable=yes,width='.($map_info[0]+12).',height='.($map_info[1]+15)
                  ?>',1);return document.MM_returnValue;"></td>
                  <td><img src="img/leer.gif" alt="" width="5" height="22"></td>
                </tr>
              </table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_map_title'] ?>:&nbsp;</td>
  <td><input name="cmap_location_title" type="text" class="f11" style="width: 440px" value="<?php echo  empty($content['location']["title"]) ? '' : html($content['location']["title"]) ?>" size="40" onChange="doMapChange();"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_profile_label_zip'] ?>:&nbsp;</td>
  <td><table border="0" cellpadding="0" cellspacing="0" summary="">
  <tr>
  <td style="width:55px;"><input name="cmap_location_zip" type="text" class="f11" style="width: 55px" value="<?php echo  empty($content['location']["zip"]) ? '' : html($content['location']["zip"]) ?>" size="8" onChange="doMapChange();"></td>
  <td class="chatlist" align="right">&nbsp;&nbsp;<?php echo $BL['be_profile_label_city'] ?>:&nbsp;</td>
  <td style="width:300px;"><input name="cmap_location_city" type="text" class="f11" style="width: 300px" value="<?php echo  empty($content['location']["city"]) ? '' : html($content['location']["city"]) ?>" size="30" onChange="doMapChange();"></td>
  </tr>
  </table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_cnt_map_info'] ?>:&nbsp;</td>
  <td valign="top"><?php

$wysiwyg_editor = array(
    'value'     => empty($content['location']["entry"]) ? '' : $content['location']["entry"],
    'field'     => 'cmap_location_entry',
    'height'    => '250px',
    'width'     => '100%',
    'rows'      => '7',
    'editor'    => $_SESSION["WYSIWYG_EDITOR"],
    'lang'      => 'en'
);
include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

  ?></td>
</tr>
<tr>
  <td valign="bottom"><img src="img/leer.gif" alt="" width="1" height="22"><input type="hidden" name="cmap_location_id" value="<?php echo  empty($content['location']["id"]) ? 0 : intval($content['location']["id"]) ?>"><input type="hidden" id="cmap_location_edited" name="cmap_location_edited" value="1"></td>
  <td valign="bottom"><input name="Submit" type="submit" class="v09" value="<?php echo $BL['be_save_btn'] ?>"></td>
</tr>
<?php

    if($map_list) {
        $ck = ($ck > 10) ? 'height:200px;' : '';
?>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>
<tr>
  <td valign="top" align="right" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="14"><?php echo $BL['be_cnt_map_list'] ?>:&nbsp;</td>
  <td><div style="overflow:auto;border:1px solid #7F9DB9;padding:0;width:440px;<?php echo $ck ?>margin:0;background-color:#F3F3F5;">
  <table width="100%" border="0" cellpadding="3" cellspacing="0" summary="">
  <?php echo $map_list; ?>
  </table>
  </div></td>
</tr>
<?php
    }

}
?>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
  <td align="right" valign="top" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="13"><?php echo $BL['be_admin_page_text'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="cmap_text" cols="40" rows="8" class="width440 autosize"><?php echo empty($content["map"]["text"]) ? '' : html($content["map"]["text"]) ?></textarea></td>
</tr>
