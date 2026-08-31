<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
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

<div class="form-group align-items-center row g-2">
  <label for="cmap_template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template']; ?>:&nbsp;</label>
  <div class="col">
    <select name="cmap_template" id="cmap_template" class="form-select form-select-sm">
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
    </select>
  </div>
</div>

<div class="form-group row g-2">
  <label for="cmap_image" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_ctype_map'] ?></label>
  <div class="col"><?php

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
    echo '<table class="table-borderless">'."\n".$g.'</table>';
} else {
    echo '<span class="error">no map available. upload one first.</span>';
}

?>  </div>
</div>

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
            $map_list .= '';
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

<div class="form-group row g-2">
  <label for="cmap_location_x" class="col-sm-2 col-form-label text-end"><?php echo ($map_current) ? $BL['be_cnt_map_edit'] : $BL['be_cnt_map_add']; ?></label>
  <div class="col">
    <table class="table-borderless" bgcolor="#E7E8EB">
        <tr>
          <td>&nbsp;&nbsp;X:&nbsp; </td>
          <td><input name="cmap_location_x" type="text" class="form-control" id="cmap_location_x" maxlength="4" value="<?php echo  empty($content['location']["x"]) ? '' : intval($content['location']["x"]) ?>" onChange="doMapChange();"></td>
          <td>&nbsp;&nbsp;Y:&nbsp; </td>
          <td><input name="cmap_location_y" type="text" class="form-control" id="cmap_location_y"  maxlength="4" value="<?php echo  empty($content['location']["y"]) ? '' : intval($content['location']["y"]) ?>" onChange="doMapChange();"></td>
          <td>&nbsp;px&nbsp;&nbsp;&nbsp;</td>
          <td><button name="open_map" type="button" class="btn btn-blue btn-sm"
          onclick="flevPopupLink('include/inc_tmpl/content/cnt51.open.php?cid=<?php
          echo $content["id"] ?>&map=<?php
          echo rawurlencode($map_name);
          echo '&points='.rawurlencode(implode(':|:', $map_xy));
          ?>','map','<?php
          echo 'scrollbars=yes,resizable=yes,width='.($map_info[0]+12).',height='.($map_info[1]+15)
          ?>',1);return document.MM_returnValue;"><i class="fa-solid fa-map-marker-alt me-1"></i> open map</button></td>
          <td></td>
        </tr>
      </table>
  </div>
</div>

<div class="form-group row g-2">
  <label for="cmap_title" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_map_title'] ?></label>
  <div class="col">
    <input name="cmap_location_title" type="text" class="form-control" value="<?php echo  empty($content['location']["title"]) ? '' : html($content['location']["title"]) ?>" onChange="doMapChange();">
  </div>
</div>


<div class="form-group row g-2">
  <label for="cmap_zip" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_profile_label_zip'] ?></label>
  <div class="col">
    <table class="table-borderless">
      <tr>
      <td style="width:55px;"><input name="cmap_location_zip" id="cmap_zip" type="text" class="form-control" value="<?php echo  empty($content['location']["zip"]) ? '' : html($content['location']["zip"]) ?>" onChange="doMapChange();"></td>
      <td align="right">&nbsp;&nbsp;<?php echo $BL['be_profile_label_city'] ?>:&nbsp;</td>
      <td style="width:300px;"><input name="cmap_location_city" type="text" class="form-control" value="<?php echo  empty($content['location']["city"]) ? '' : html($content['location']["city"]) ?>" onChange="doMapChange();"></td>
      </tr>
    </table>
  </div>
</div>

<div class="form-group row g-2">
  <label for="cmap_location_entry" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_map_info'] ?></label>
  <div class="col"><?php

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

  ?></div>
</div>

<input type="hidden" name="cmap_location_id" value="<?php echo  empty($content['location']["id"]) ? 0 : intval($content['location']["id"]) ?>">
<input type="hidden" id="cmap_location_edited" name="cmap_location_edited" value="1">

<button name="Submit" type="submit" class="btn btn-blue btn-sm" value="1"><i class="fa-solid fa-check"></i> <?php echo $BL['be_save_btn'] ?></button>

<?php
    if($map_list) {
        $ck_style = ($ck > 10) ? ' style="height:200px;"' : '';
?>

<div class="form-group row g-2">
  <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_map_list'] ?></label>
  <div class="col">
    <div class="map-list-container"<?php echo $ck_style ?>>
      <table class="table-borderless w-100">
      <?php echo $map_list; ?>
      </table>
    </div>
  </div>
</div>
<?php
    }

}
?>

<div class="form-group row g-2">
  <label for="cmap_text" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_page_text'] ?></label>
  <div class="col">
    <textarea name="cmap_text" id="cmap_text" cols="40" rows="8" class="form-control"><?php echo empty($content["map"]["text"]) ? '' : html($content["map"]["text"]) ?></textarea>
    </div>
</div>
