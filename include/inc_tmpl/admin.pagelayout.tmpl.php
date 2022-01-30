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

if(!isset($_GET["s"])) {
// check if pagelayout should be edited or list should be shown
?><table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr><td colspan="3" class="title"><?php echo $BL['be_admin_page_title'] ?></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
    <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<?php
    // loop listing available pagelayouts
    $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_pagelayout WHERE pagelayout_trash=0 ORDER BY pagelayout_default DESC";
    $result = _dbQuery($sql);
    $row_count = 0;
    if(isset($result[0]['pagelayout_id'])) {
        foreach($result as $row) {

            echo "<tr".( ($row_count % 2) ? " bgcolor=\"#F3F5F8\"" : "" ).">\n<td width=\"1%\" style=\"padding:2px 5px 2px 3px\">";

            echo '<img src="img/famfamfam/layout.gif" alt="" border="0" /></td>'."\n";

            echo '<td class="dir"><a href="phpwcms.php?do=admin&amp;p=8&amp;s='.$row["pagelayout_id"];
            echo '"><strong>'.html($row["pagelayout_name"])."</strong>";

            echo ($row["pagelayout_default"]) ? " (".$BL['be_admin_tmpl_default'].")" : '';

            echo "</a></td>\n".'<td align="right" class="nowrap" style="padding:2px 3px 0 5px">';

            echo '<a href="phpwcms.php?do=admin&amp;p=8&amp;s='.$row["pagelayout_id"].'" title="'.$BL['be_admin_page_edit'].'">';
            echo '<img src="img/button/edit_22x13.gif" alt="" border="0" /></a>';

            echo '<a href="include/inc_act/act_frontendsetup.php?do=1|'.$row["pagelayout_id"].'" ';
            echo 'title="delete pagelayout: '.html($row["pagelayout_name"]);
            echo '" style="margin-left:3px" onclick="return confirm(\''.$BL['be_cnt_delete'].': '.js_singlequote(html($row["pagelayout_name"])).'?  \')">';
            echo '<img src="img/button/trash_13x13_1.gif" border="0" alt="" /></a>';

            echo "</td>\n</tr>\n";

            $row_count++;
        }
    } // end listing

?>
    <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="8"></td>
    </tr>
    <tr><td colspan="3"><form action="phpwcms.php?do=admin&p=8&s=0" method="post"><input type="submit" value="<?php echo $BL['be_admin_page_add'] ?>" class="button" title="<?php echo $BL['be_admin_page_add'] ?>"></form></td>
    </tr>
</table>
<?php

} else {

    $pagelayout["id"] = intval($_GET["s"]);

    if(isset($_POST["layout_id"])) {

        // read the full pagelayout values
        $pagelayout["id"]                         = intval($_POST["layout_id"]);
        $pagelayout["layout_name"]                = empty($_POST["layout_name"]) ? $BL['be_subnav_admin_pagelayout'].' ('.date('Ymd-His', now()).')' : clean_slweg($_POST["layout_name"], 200);
        $pagelayout["layout_default"]             = isset($_POST["layout_default"]) ? intval($_POST["layout_default"]) : 0;

        $pagelayout["layout_align"]               = intval($_POST["layout_align"]);
        $pagelayout["layout_type"]                = intval($_POST["layout_type"]);

        $pagelayout["layout_border_top"]          = intval($_POST["layout_border_top"]);
        $pagelayout["layout_border_bottom"]       = intval($_POST["layout_border_bottom"]);
        $pagelayout["layout_border_left"]         = intval($_POST["layout_border_left"]);
        $pagelayout["layout_border_right"]        = intval($_POST["layout_border_right"]);
        $pagelayout["layout_noborder"]            = isset($_POST["layout_noborder"]) ? 1 : 0;

        $pagelayout["layout_border_top"]          = ($pagelayout["layout_noborder"]) ? "" : strval($_POST["layout_border_top"]);
        $pagelayout["layout_border_bottom"]       = ($pagelayout["layout_noborder"]) ? "" : strval($_POST["layout_border_bottom"]);
        $pagelayout["layout_border_left"]         = ($pagelayout["layout_noborder"]) ? "" : strval($_POST["layout_border_left"]);
        $pagelayout["layout_border_right"]        = ($pagelayout["layout_noborder"]) ? "" : strval($_POST["layout_border_right"]);

        $pagelayout["layout_title"]               = clean_slweg($_POST["layout_title"]);
        $pagelayout["layout_title_order"]         = intval($_POST["layout_title_order"]);
        $pagelayout["layout_title_spacer"]        = slweg($_POST["layout_title_spacer"], 0, false);

        $pagelayout["layout_bgcolor"]             = clean_slweg($_POST["layout_bgcolor"],7);
        $pagelayout["layout_bgimage"]             = clean_slweg($_POST["layout_bgimage"]);

        $pagelayout["layout_jsonload"]            = slweg($_POST["layout_jsonload"]);

        $pagelayout["layout_textcolor"]           = clean_slweg($_POST["layout_textcolor"],7);
        $pagelayout["layout_linkcolor"]           = clean_slweg($_POST["layout_linkcolor"],7);
        $pagelayout["layout_vcolor"]              = clean_slweg($_POST["layout_vcolor"],7);
        $pagelayout["layout_acolor"]              = clean_slweg($_POST["layout_acolor"],7);

        $pagelayout["layout_all_width"]           = get_pix_or_percent($_POST["layout_all_width"]);
        $pagelayout["layout_all_bgcolor"]         = clean_slweg($_POST["layout_all_bgcolor"],7);
        $pagelayout["layout_all_bgimage"]         = clean_slweg($_POST["layout_all_bgimage"]);
        $pagelayout["layout_all_class"]           = clean_slweg($_POST["layout_all_class"]);

        $pagelayout["layout_content_width"]       = get_pix_or_percent($_POST["layout_content_width"]);
        $pagelayout["layout_content_bgcolor"]     = clean_slweg($_POST["layout_content_bgcolor"],7);
        $pagelayout["layout_content_bgimage"]     = clean_slweg($_POST["layout_content_bgimage"]);
        $pagelayout["layout_content_class"]       = clean_slweg($_POST["layout_content_class"]);

        $pagelayout["layout_left_width"]          = get_pix_or_percent($_POST["layout_left_width"]);
        $pagelayout["layout_left_bgcolor"]        = clean_slweg($_POST["layout_left_bgcolor"],7);
        $pagelayout["layout_left_bgimage"]        = clean_slweg($_POST["layout_left_bgimage"]);
        $pagelayout["layout_left_class"]          = clean_slweg($_POST["layout_left_class"]);

        $pagelayout["layout_right_width"]         = get_pix_or_percent($_POST["layout_right_width"]);
        $pagelayout["layout_right_bgcolor"]       = clean_slweg($_POST["layout_right_bgcolor"],7);
        $pagelayout["layout_right_bgimage"]       = clean_slweg($_POST["layout_right_bgimage"]);
        $pagelayout["layout_right_class"]         = clean_slweg($_POST["layout_right_class"]);

        $pagelayout["layout_leftspace_width"]     = get_pix_or_percent($_POST["layout_leftspace_width"]);
        $pagelayout["layout_leftspace_bgcolor"]   = clean_slweg($_POST["layout_leftspace_bgcolor"],7);
        $pagelayout["layout_leftspace_bgimage"]   = clean_slweg($_POST["layout_leftspace_bgimage"]);
        $pagelayout["layout_leftspace_class"]     = clean_slweg($_POST["layout_leftspace_class"]);

        $pagelayout["layout_rightspace_width"]    = get_pix_or_percent($_POST["layout_rightspace_width"]);
        $pagelayout["layout_rightspace_bgcolor"]  = clean_slweg($_POST["layout_rightspace_bgcolor"],7);
        $pagelayout["layout_rightspace_bgimage"]  = clean_slweg($_POST["layout_rightspace_bgimage"]);
        $pagelayout["layout_rightspace_class"]    = clean_slweg($_POST["layout_rightspace_class"]);

        $pagelayout["layout_header_height"]       = get_pix_or_percent($_POST["layout_header_height"]);
        $pagelayout["layout_header_bgcolor"]      = clean_slweg($_POST["layout_header_bgcolor"],7);
        $pagelayout["layout_header_bgimage"]      = clean_slweg($_POST["layout_header_bgimage"]);
        $pagelayout["layout_header_class"]        = clean_slweg($_POST["layout_header_class"]);

        $pagelayout["layout_topspace_height"]     = get_pix_or_percent($_POST["layout_topspace_height"]);
        $pagelayout["layout_topspace_bgcolor"]    = clean_slweg($_POST["layout_topspace_bgcolor"],7);
        $pagelayout["layout_topspace_bgimage"]    = clean_slweg($_POST["layout_topspace_bgimage"]);
        $pagelayout["layout_topspace_class"]      = clean_slweg($_POST["layout_topspace_class"]);

        $pagelayout["layout_bottomspace_height"]  = get_pix_or_percent($_POST["layout_bottomspace_height"]);
        $pagelayout["layout_bottomspace_bgcolor"] = clean_slweg($_POST["layout_bottomspace_bgcolor"],7);
        $pagelayout["layout_bottomspace_bgimage"] = clean_slweg($_POST["layout_bottomspace_bgimage"]);
        $pagelayout["layout_bottomspace_class"]   = clean_slweg($_POST["layout_bottomspace_class"]);

        $pagelayout["layout_footer_height"]       = get_pix_or_percent($_POST["layout_footer_height"]);
        $pagelayout["layout_footer_bgcolor"]      = clean_slweg($_POST["layout_footer_bgcolor"],7);
        $pagelayout["layout_footer_bgimage"]      = clean_slweg($_POST["layout_footer_bgimage"]);
        $pagelayout["layout_footer_class"]        = clean_slweg($_POST["layout_footer_class"]);

        $pagelayout["layout_render"]              = intval($_POST["layout_render"]);

        $pagelayout["layout_customblocks"]      = phpwcms_remove_accents(str_replace(' ', ',', strtoupper(clean_slweg($_POST['layout_customblocks']))));
        $pagelayout["layout_customblocks"]      = convertStringToArray($pagelayout["layout_customblocks"]);
        if(is_array($pagelayout["layout_customblocks"]) && count($pagelayout["layout_customblocks"])) {

            // now remove the default pre-defined block name CONTENT and cut to max length of 50
            if(is_array($pagelayout["layout_customblocks"]) && count($pagelayout["layout_customblocks"])) {
                foreach($pagelayout["layout_customblocks"] as $key => $value) {
                    $value = substr($value, 0, 20);
                    $pagelayout["layout_customblocks"][$key] = $value;
                    if(in_array($value, array('CONTENT', 'LEFT', 'RIGHT', 'HEADER', 'FOOTER', 'CPSET', 'SYSTEM'))) {
                        unset($pagelayout["layout_customblocks"][$key]);
                    }
                }
            }

            $pagelayout["layout_customblocks"] = implode(', ', $pagelayout["layout_customblocks"]);

        } else {

            $pagelayout["layout_customblocks"] = '';

        }

        if($pagelayout["id"]) {
            // if ID <> 0 then update pagelayout
            $query_mode = 'UPDATE';
            $sql =  "UPDATE ".DB_PREPEND."phpwcms_pagelayout SET ".
                    "pagelayout_name='".aporeplace($pagelayout["layout_name"])."', ".
                    "pagelayout_default=".$pagelayout["layout_default"].", ".
                    "pagelayout_var='".aporeplace(serialize($pagelayout))."' ".
                    "WHERE pagelayout_id=".$pagelayout["id"];
        } else {
            // if ID = 0 then create new pagelayout
            $query_mode = 'INSERT';
            $sql =  "INSERT INTO ".DB_PREPEND."phpwcms_pagelayout (".
                    "pagelayout_name, pagelayout_default, pagelayout_var) VALUES ('".
                    aporeplace($pagelayout["layout_name"])."', ".$pagelayout["layout_default"].", '".
                    aporeplace(serialize($pagelayout))."')";
        }

        // update or insert data entry
        $result = _dbQuery($sql, $query_mode);
        if($query_mode === 'INSERT' && !empty($result['INSERT_ID'])) {
            $pagelayout["id"] = $result['INSERT_ID'];
        }

        //now proof for default pagelayout and set
        if($pagelayout["layout_default"]) {
            _dbQuery("UPDATE ".DB_PREPEND."phpwcms_pagelayout SET pagelayout_default=0 WHERE pagelayout_id != ".$pagelayout["id"], 'UPDATE');
        }

        update_cache();

        if($pagelayout["id"]) {
            headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=admin&p=8&s='.$pagelayout["id"]);
        }

    }

    if($pagelayout["id"]) {

        // read the given pagelayout from db
        $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_pagelayout WHERE pagelayout_id=".$pagelayout["id"]." LIMIT 1";
        $result = _dbQuery($sql);
        if(isset($result[0]['pagelayout_id'])) {
            $pagelayout = unserialize($result[0]["pagelayout_var"]);
            $pagelayout["id"] = $result[0]["pagelayout_id"];
            $pagelayout["layout_default"] = $result[0]["pagelayout_default"];
        }

    } else {

        // set default pagelayout information
        $pagelayout = array();

        $pagelayout["id"]                         = 0;
        $pagelayout["layout_align"]               = 0;
        $pagelayout["layout_type"]                = 0;
        $pagelayout["layout_border_top"]          = '';
        $pagelayout["layout_border_bottom"]       = '';
        $pagelayout["layout_border_left"]         = '';
        $pagelayout["layout_border_right"]        = '';
        $pagelayout["layout_title"]               = "Pagetitle";
        $pagelayout["layout_title_cat"]           = 1;
        $pagelayout["layout_title_article"]       = 1;
        $pagelayout["layout_bgcolor"]             = '';
        $pagelayout["layout_bgimage"]             = '';
        $pagelayout["layout_jsonload"]            = '';
        $pagelayout["layout_textcolor"]           = '';
        $pagelayout["layout_linkcolor"]           = '';
        $pagelayout["layout_vcolor"]              = '';
        $pagelayout["layout_acolor"]              = '';
        $pagelayout["layout_all_width"]           = '';
        $pagelayout["layout_all_bgcolor"]         = '';
        $pagelayout["layout_all_bgimage"]         = '';
        $pagelayout["layout_all_class"]           = '';
        $pagelayout["layout_content_width"]       = '';
        $pagelayout["layout_content_bgcolor"]     = '';
        $pagelayout["layout_content_bgimage"]     = '';
        $pagelayout["layout_content_class"]       = '';
        $pagelayout["layout_left_width"]          = '';
        $pagelayout["layout_left_bgcolor"]        = '';
        $pagelayout["layout_left_bgimage"]        = '';
        $pagelayout["layout_left_class"]          = '';
        $pagelayout["layout_right_width"]         = '';
        $pagelayout["layout_right_bgcolor"]       = '';
        $pagelayout["layout_right_bgimage"]       = '';
        $pagelayout["layout_right_class"]         = '';
        $pagelayout["layout_leftspace_width"]     = '';
        $pagelayout["layout_leftspace_bgcolor"]   = '';
        $pagelayout["layout_leftspace_bgimage"]   = '';
        $pagelayout["layout_leftspace_class"]     = '';
        $pagelayout["layout_rightspace_width"]    = '';
        $pagelayout["layout_rightspace_bgcolor"]  = '';
        $pagelayout["layout_rightspace_bgimage"]  = '';
        $pagelayout["layout_rightspace_class"]    = '';
        $pagelayout["layout_header_height"]       = '';
        $pagelayout["layout_header_bgcolor"]      = '';
        $pagelayout["layout_header_bgimage"]      = '';
        $pagelayout["layout_header_class"]        = '';
        $pagelayout["layout_topspace_height"]     = '';
        $pagelayout["layout_topspace_bgcolor"]    = '';
        $pagelayout["layout_topspace_bgimage"]    = '';
        $pagelayout["layout_topspace_class"]      = '';
        $pagelayout["layout_bottomspace_height"]  = '';
        $pagelayout["layout_bottomspace_bgcolor"] = '';
        $pagelayout["layout_bottomspace_bgimage"] = '';
        $pagelayout["layout_bottomspace_class"]   = '';
        $pagelayout["layout_footer_height"]       = '';
        $pagelayout["layout_footer_bgcolor"]      = '';
        $pagelayout["layout_footer_bgimage"]      = '';
        $pagelayout["layout_footer_class"]        = '';
        $pagelayout["layout_render"]                = 2;
        $pagelayout["layout_title_order"]           = 4;
        $pagelayout["layout_title_spacer"]          = ' | ';
        $pagelayout["layout_noborder"]              = 1;

    }

    initJQuery(); // switch to jQuery
    $pagelayout['editable_hidden'] = $pagelayout["layout_render"] === 2 ? ' style="display:none"' : '';

?>
<form action="phpwcms.php?do=admin&p=8&s=<?php echo $pagelayout["id"] ?>" method="post" name="pagelayout" target="_self">
    <table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
        <tr><td colspan="2" class="title"><?php echo $BL['be_admin_page_title'] ?></td></tr>
            <tr>
                <td width="90"><img src="img/leer.gif" alt="" width="35" height="6"></td>
                <td width="448"><img src="img/leer.gif" alt="" width="1" height="1"></td>
            </tr>
            <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
            <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
            <tr bgcolor="#F3F5F8">
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_name'] ?>:&nbsp;</td>
              <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                 <tr>
                    <td><input name="layout_name" type="text" class="f11b" id="layout_name" style="width: 350px;" value="<?php echo  isset($pagelayout["layout_name"]) ? html($pagelayout["layout_name"]) : '' ?>" size="20" maxlength="150"></td>
                    <td>&nbsp;&nbsp;</td>
                    <td><input name="layout_default" id="layout_default" type="checkbox" value="1" <?php is_checked(isset($pagelayout["layout_default"]) ? $pagelayout["layout_default"] : 0, 1) ?>></td>
                    <td class="v10"><label for="layout_default"><?php echo $BL['be_admin_tmpl_default'] ?></label></td>
                 </tr>
              </table></td>
            </tr>
            <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

            <tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1"></td></tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>

            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_render'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED" class="tdtop3 tdbottom3"><table border="0" cellpadding="0" cellspacing="0" summary="">
                    <tr id="radio-group-layout-render">
                        <td>&nbsp;</td>
                        <td align="center"><input name="layout_render" type="radio" id="layout_render_2" value="2" <?php is_checked(2, $pagelayout["layout_render"]); ?>></td>
                        <td><label for="layout_render_2">&nbsp;<strong><?php echo $BL['be_admin_page_custom'].'</strong> <span class="v09">('.$BL['be_admin_page_custominfo'].')</span>' ?></label>&nbsp;&nbsp;</td>
                        <td align="center"><input name="layout_render" type="radio" id="layout_render_0" value="0" <?php is_checked(0, $pagelayout["layout_render"]); ?>></td>
                        <td><label for="layout_render_0">&nbsp;<?php echo $BL['be_admin_page_table'] ?></label>&nbsp;&nbsp;</td>
                        <td align="center"><input name="layout_render" type="radio" id="layout_render_1" value="1" <?php is_checked(1, $pagelayout["layout_render"]); ?>></td>
                        <td><label for="layout_render_1">&nbsp;<?php echo $BL['be_admin_page_div'] ?></label></td>
                    </tr>
                </table></td>
            </tr>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>

            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_blocks'].', '.$BL['be_admin_page_customblocks'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED" class="tdtop3 tdbottom3">
                &nbsp;<input name="layout_customblocks" type="text" class="f10" id="layout_customblocks" style="width: 400px;" value="<?php echo  isset($pagelayout["layout_customblocks"]) ? html($pagelayout["layout_customblocks"]) : '' ?>" size="20">
                </td>
            </tr>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>

        <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_pagetitle'] ?>:&nbsp;</td>
              <td><input name="layout_title" type="text" class="f11b" id="layout_title" style="width: 400px;" value="<?php echo html($pagelayout["layout_title"]); ?>" size="20" maxlength="100"></td>
            </tr>
            <tr><td colspan="2" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
            <tr>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_addtotitle'] ?>:&nbsp;</td>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><select name="layout_title_order" id="layout_title_order" class="v11">

    <?php

    if(empty($pagelayout["layout_title_order"])) {
        $pagelayout["layout_title_order"] = 0;
    }
    if(empty($pagelayout["layout_title_spacer"])) {
        $pagelayout["layout_title_spacer"] = ' | ';
    }

    ?>
    <option value="0"<?php is_selected(0, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_category'].', '.$BL['be_admin_page_articlename'] ?></option>
    <option value="1"<?php is_selected(1, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_articlename'].', '.$BL['be_admin_page_category'] ?></option>
    <option value="2"<?php is_selected(2, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_category'].', '.$BL['be_admin_page_articlename'].', '.$BL['be_admin_page_pagetitle'] ?></option>
    <option value="3"<?php is_selected(3, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_category'].', '.$BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_articlename'] ?></option>
    <option value="4"<?php is_selected(4, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_articlename'].', '.$BL['be_admin_page_category'].', '.$BL['be_admin_page_pagetitle'] ?></option>
    <option value="5"<?php is_selected(5, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_articlename'].', '.$BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_category'] ?></option>

    <option value="6"<?php is_selected(6, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_category'] ?></option>
    <option value="7"<?php is_selected(7, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_pagetitle'].', '.$BL['be_admin_page_articlename'] ?></option>
    <option value="8"<?php is_selected(8, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_category'].', '.$BL['be_admin_page_articlename'] ?></option>
    <option value="9"<?php is_selected(9, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_category'].', '.$BL['be_admin_page_pagetitle'] ?></option>
    <option value="10"<?php is_selected(10, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_articlename'].', '.$BL['be_admin_page_category'] ?></option>
    <option value="11"<?php is_selected(11, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_articlename'].', '.$BL['be_admin_page_pagetitle'] ?></option>

    <option value="12"<?php is_selected(12, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_pagetitle'] ?></option>
    <option value="13"<?php is_selected(13, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_category'] ?></option>
    <option value="14"<?php is_selected(14, $pagelayout["layout_title_order"]) ?>><?php echo $BL['be_admin_page_articlename'] ?></option>

                  </select></td>

                  <td align="right" class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_field']['break'] ?>:&nbsp;</td>
                  <td><input name="layout_title_spacer" type="text" class="v11 width40" id="layout_title_spacer" value="<?php echo html($pagelayout["layout_title_spacer"]); ?>" size="20" maxlength="100"></td>

                </tr>
              </table></td>
            </tr>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>

            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" valign="top" class="chatlist tdtop3"><?php echo $BL['be_admin_page_align']  ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table border="0" cellpadding="0" cellspacing="0" summary="">
                  <tr><td colspan="9"><img src="img/leer.gif" alt="" width="1" height="7"></td></tr>
                  <tr>
                    <td rowspan="2"><img src="img/leer.gif" alt="" width="7" height="50"></td>
                    <td valign="top"><img src="img/leer.gif" alt="" width="25" height="1"></td>
                    <td rowspan="2" valign="top"><label for="layout_align_0"><img src="img/symbole/layout_left.gif" alt="<?php echo $BL['be_admin_page_align_left'] ?>" width="56" height="44" border="0"></label></td>
                    <td rowspan="2"><img src="img/leer.gif" alt="" width="15" height="1"></td>
                    <td valign="top"><img src="img/leer.gif" alt="" width="25" height="1"></td>
                    <td rowspan="2" valign="top"><label for="layout_align_1"><img src="img/symbole/layout_center.gif" alt="<?php echo $BL['be_admin_page_align_center'] ?>" width="56" height="44" border="0"></label></td>
                    <td rowspan="2"><img src="img/leer.gif" alt="" width="15" height="1"></td>
                    <td><img src="img/leer.gif" alt="" width="25" height="1"></td>
                    <td rowspan="2" valign="top"><label for="layout_align_2"><img src="img/symbole/layout_right.gif" alt="<?php echo $BL['be_admin_page_align_right'] ?>" width="56" height="44" border="0"></label></td>
                  </tr>
                  <tr>
                    <td align="center" valign="top"><input name="layout_align" type="radio" id="layout_align_0" value="0" <?php is_checked(0, $pagelayout["layout_align"]); ?>></td>
                    <td align="center" valign="top"><input type="radio" name="layout_align" id="layout_align_1" value="1" <?php is_checked(1, $pagelayout["layout_align"]); ?>></td>
                    <td align="center" valign="top"><input type="radio" name="layout_align" id="layout_align_2" value="2" <?php is_checked(2, $pagelayout["layout_align"]); ?>></td>
                  </tr>
                </table></td>
              </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10"></td></tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>><td><img src="img/leer.gif" alt="" width="1" height="1"></td><td bgcolor="#E6EAED"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_margin']  ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_top'] ?>:&nbsp;</td>
                  <td><input name="layout_border_top" type="text" class="f10" id="layout_border_top" style="width: 30px;" value="<?php echo $pagelayout["layout_border_top"] ?>" size="3" maxlength="3"></td>
                  <td class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_bottom'] ?>:&nbsp;</td>
                  <td><input name="layout_border_bottom" type="text" class="f10" id="layout_border_bottom" style="width: 30px;" value="<?php echo $pagelayout["layout_border_bottom"] ?>" size="3" maxlength="3"></td>
                  <td class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_left'] ?>:&nbsp;</td>
                  <td><input name="layout_border_left" type="text" class="f10" id="layout_border_left" style="width: 30px;" value="<?php echo $pagelayout["layout_border_left"] ?>" size="3" maxlength="3"></td>
                  <td class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_right'] ?>:&nbsp;</td>
                  <td><input name="layout_border_right" type="text" class="f10" id="layout_border_right" style="width: 30px;" value="<?php echo $pagelayout["layout_border_right"] ?>" size="3" maxlength="3"></td>
                  <td class="v10">&nbsp;px&nbsp;&nbsp;</td>
                  <td><input name="layout_noborder" id="layout_noborder" type="checkbox" value="1" <?php is_checked(1, isset($pagelayout["layout_noborder"]) ? $pagelayout["layout_noborder"] : 0) ?>></td>
                  <td class="v10"><label for="layout_noborder"><?php echo $BL['be_admin_page_disable'] ?></label></td>
                </tr>
              </table></td>
              </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_bg'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
                  <td><input name="layout_bgcolor" type="text" class="f10" id="layout_bgcolor2" style="width: 55px;" value="<?php echo html($pagelayout["layout_bgcolor"]); ?>" size="7" maxlength="7"></td>
                  <td class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_image'] ?>:&nbsp;</td>
                  <td><input name="layout_bgimage" type="text" class="f10" id="layout_bgimage" style="width: 270px;" value="<?php echo html($pagelayout["layout_bgimage"]); ?>" size="20"></td>
                  <td><img src="img/leer.gif" alt="" width="1" height="20"></td>
                </tr>
              </table></td>
            </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_text'] ?>:&nbsp;</td>
                  <td><input name="layout_textcolor" type="text" class="f10" id="layout_textcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_textcolor"]); ?>" size="7" maxlength="7"></td>
                  <td class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_link'] ?>:&nbsp;</td>
                  <td><input name="layout_linkcolor" type="text" class="f10" id="layout_linkcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_linkcolor"]); ?>" size="7" maxlength="7"></td>
                  <td class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_visited'] ?>:&nbsp;</td>
                  <td><input name="layout_vcolor" type="text" class="f10" id="layout_vcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_vcolor"]); ?>" size="7" maxlength="7"></td>
                  <td class="v10">&nbsp;&nbsp;<?php echo $BL['be_ftptakeover_active'] ?>:&nbsp;</td>
                  <td><input name="layout_acolor" type="text" class="f10" id="layout_acolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_acolor"]); ?>" size="7" maxlength="7"></td>
                  <td><img src="img/leer.gif" alt="" width="1" height="20"></td>
                </tr>
              </table></td>
             </tr>
             <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_js'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                    <td class="v10">&nbsp;&nbsp;onload:&nbsp;</td>
                    <td><input name="layout_jsonload" type="text" class="f10" id="layout_jsonload" style="width: 382px;" value="<?php echo html($pagelayout["layout_jsonload"]); ?>" size="20"></td>
                    <td><img src="img/leer.gif" alt="" width="1" height="20"></td>
                </tr>
               </table></td>
             </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>><td><img src="img/leer.gif" alt="" width="1" height="1"></td><td bgcolor="#E6EAED"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>><td colspan="2" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>

            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" valign="top" class="chatlist tdtop3"><?php echo $BL['be_admin_page_blocks'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table border="0" cellpadding="0" cellspacing="0" summary="">
                  <tr><td colspan="12"><img src="img/leer.gif" alt="" width="1" height="7"></td></tr>
                  <tr>
                    <td rowspan="2"><img src="img/leer.gif" alt="" width="7" height="50"></td>
                    <td valign="top"><img src="img/leer.gif" alt="" width="25" height="1"></td>
                    <td rowspan="2" valign="top"><label for="layout_type_0"><img src="img/symbole/3_column_layout.gif" alt="<?php echo $BL['be_admin_page_col1'] ?>" width="39" height="44" border="0"></label></td>
                    <td rowspan="2"><img src="img/leer.gif" alt="" width="32" height="1"></td>
                    <td valign="top"><img src="img/leer.gif" alt="" width="25" height="1"></td>
                    <td rowspan="2" valign="top"><label for="layout_type_1"><img src="img/symbole/2_column_layout.gif" alt="<?php echo $BL['be_admin_page_col2'] ?>" width="39" height="44" border="0"></label></td>
                    <td rowspan="2"><img src="img/leer.gif" alt="" width="32" height="1"></td>
                    <td><img src="img/leer.gif" alt="" width="25" height="1"></td>
                    <td rowspan="2" valign="top"><label for="layout_type_2"><img src="img/symbole/4_column_layout.gif" alt="<?php echo $BL['be_admin_page_col3'] ?>" width="39" height="44" border="0"></label></td>
                    <td rowspan="2"><img src="img/leer.gif" alt="" width="32" height="1"></td>
                    <td valign="top"><img src="img/leer.gif" alt="" width="25" height="1"></td>
                    <td rowspan="2" valign="top"><label for="layout_type_3"><img src="img/symbole/1_column_layout.gif" alt="<?php echo $BL['be_admin_page_col4'] ?>" width="39" height="44" border="0"></label></td>
                  </tr>
                  <tr>
                    <td align="center" valign="top"><input name="layout_type" type="radio" id="layout_type_0" value="0" <?php is_checked(0, $pagelayout["layout_type"]); ?>></td>
                    <td align="center" valign="top"><input type="radio" name="layout_type" id="layout_type_1" value="1" <?php is_checked(1, $pagelayout["layout_type"]); ?>></td>
                    <td align="center" valign="top"><input type="radio" name="layout_type" id="layout_type_2" value="2" <?php is_checked(2, $pagelayout["layout_type"]); ?>></td>
                    <td align="center" valign="top"><input type="radio" name="layout_type" id="layout_type_3" value="3" <?php is_checked(3, $pagelayout["layout_type"]); ?>></td>
                  </tr>
                </table></td>
            </tr>

            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>

            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>><td><img src="img/leer.gif" alt="" width="1" height="1"></td><td bgcolor="#E6EAED"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_allblocks'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
                  <tr>
                    <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_width'] ?>:&nbsp;</td>
                    <td width="35"><input name="layout_all_width" type="text" class="f10" id="layout_all_width" style="width: 35px;" value="<?php echo $pagelayout["layout_all_width"] ?>" size="4" maxlength="4"></td>
                    <td width="43" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
                    <td width="55"><input name="layout_all_bgcolor" type="text" class="f10" id="layout_all_bgcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_all_bgcolor"]); ?>" size="7" maxlength="7"></td>
                    <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_image'] ?>:&nbsp;</td>
                    <td width="100"><input name="layout_all_bgimage" type="text" class="f10" id="layout_all_bgimage" style="width: 100px;" value="<?php echo html($pagelayout["layout_all_bgimage"]); ?>" size="7"></td>
                    <td width="45" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_class'] ?>:&nbsp;</td>
                    <td width="55"><input name="layout_all_class" type="text" class="f10" id="layout_all_class" style="width: 55px;" value="<?php echo html($pagelayout["layout_all_class"]); ?>" size="7"></td>
                    <td width="1"><img src="img/leer.gif" alt="" width="1" height="20"></td>
                  </tr>
                </table></td>
            </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_left'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_width'] ?>:&nbsp;</td>
                  <td width="35"><input name="layout_left_width" type="text" class="f10" id="layout_left_width" style="width: 35px;" value="<?php echo $pagelayout["layout_left_width"] ?>" size="4" maxlength="4"></td>
                  <td width="43" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
                  <td width="55"><input name="layout_left_bgcolor" type="text" class="f10" id="layout_left_bgcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_left_bgcolor"]); ?>" size="7" maxlength="7"></td>
                  <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_image'] ?>:&nbsp;</td>
                  <td width="100"><input name="layout_left_bgimage" type="text" class="f10" id="layout_left_bgimage" style="width: 100px;" value="<?php echo html($pagelayout["layout_left_bgimage"]); ?>" size="7"></td>
                  <td width="45" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_class'] ?>:&nbsp;</td>
                  <td width="55"><input name="layout_left_class" type="text" class="f10" id="layout_left_class" style="width: 55px;" value="<?php echo html($pagelayout["layout_left_class"]); ?>" size="7"></td>
                  <td width="1"><img src="img/leer.gif" alt="" width="1" height="20"></td>
                </tr>
              </table></td>
            </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_leftspace'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_width'] ?>:&nbsp;</td>
                  <td width="35"><input name="layout_leftspace_width" type="text" class="f10" id="layout_leftspace_width" style="width: 35px;" value="<?php echo $pagelayout["layout_leftspace_width"] ?>" size="4" maxlength="4"></td>
                  <td width="43" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
                  <td width="55"><input name="layout_leftspace_bgcolor" type="text" class="f10" id="layout_leftspace_bgcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_leftspace_bgcolor"]); ?>" size="7" maxlength="7"></td>
                  <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_image'] ?>:&nbsp;</td>
                  <td width="100"><input name="layout_leftspace_bgimage" type="text" class="f10" id="layout_leftspace_bgimage" style="width: 100px;" value="<?php echo html($pagelayout["layout_leftspace_bgimage"]); ?>" size="7"></td>
                  <td width="45" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_class'] ?>:&nbsp;</td>
                  <td width="55"><input name="layout_leftspace_class" type="text" class="f10" id="layout_leftspace_class" style="width: 55px;" value="<?php echo html($pagelayout["layout_leftspace_class"]); ?>" size="7"></td>
                  <td width="1"><img src="img/leer.gif" alt="" width="1" height="20"></td>
                </tr>
              </table></td>
            </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_main']."&nbsp;[".$phpwcms["content_width"]?>]:&nbsp;</td>
              <td bgcolor="#E6EAED"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_width'] ?>:&nbsp;</td>
                  <td width="35"><input name="layout_content_width" type="text" class="f10" id="layout_content_width" style="width: 35px;" value="<?php echo $pagelayout["layout_content_width"] ?>" size="4" maxlength="4"></td>
                  <td width="43" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
                  <td width="55"><input name="layout_content_bgcolor" type="text" class="f10" id="layout_content_bgcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_content_bgcolor"]); ?>" size="7" maxlength="7"></td>
                  <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_image'] ?>:&nbsp;</td>
                  <td width="100"><input name="layout_content_bgimage" type="text" class="f10" id="layout_content_bgimage" style="width: 100px;" value="<?php echo html($pagelayout["layout_content_bgimage"]); ?>" size="7"></td>
                  <td width="45" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_class'] ?>:&nbsp;</td>
                  <td width="55"><input name="layout_content_class" type="text" class="f10" id="layout_content_class" style="width: 55px;" value="<?php echo html($pagelayout["layout_content_class"]); ?>" size="7"></td>
                  <td width="1"><img src="img/leer.gif" alt="" width="1" height="20"></td>
                </tr>
              </table></td>
              </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_rightspace'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_width'] ?>:&nbsp;</td>
                  <td width="35"><input name="layout_rightspace_width" type="text" class="f10" id="layout_rightspace_width" style="width: 35px;" value="<?php echo $pagelayout["layout_rightspace_width"] ?>" size="4" maxlength="4"></td>
                  <td width="43" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
                  <td width="55"><input name="layout_rightspace_bgcolor" type="text" class="f10" id="layout_rightspace_bgcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_rightspace_bgcolor"]); ?>" size="7" maxlength="7"></td>
                  <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_image'] ?>:&nbsp;</td>
                  <td width="100"><input name="layout_rightspace_bgimage" type="text" class="f10" id="layout_rightspace_bgimage" style="width: 100px;" value="<?php echo html($pagelayout["layout_rightspace_bgimage"]); ?>" size="7"></td>
                  <td width="45" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_class'] ?>:&nbsp;</td>
                  <td width="55"><input name="layout_rightspace_class" type="text" class="f10" id="layout_rightspace_class" style="width: 55px;" value="<?php echo html($pagelayout["layout_rightspace_class"]); ?>" size="7"></td>
                  <td width="1"><img src="img/leer.gif" alt="" width="1" height="20"></td>
                </tr>
              </table></td>
              </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_right'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_width'] ?>:&nbsp;</td>
                  <td width="35"><input name="layout_right_width" type="text" class="f10" id="layout_right_width" style="width: 35px;" value="<?php echo $pagelayout["layout_right_width"] ?>" size="4" maxlength="4"></td>
                  <td width="43" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
                  <td width="55"><input name="layout_right_bgcolor" type="text" class="f10" id="layout_right_bgcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_right_bgcolor"]); ?>" size="7" maxlength="7"></td>
                  <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_image'] ?>:&nbsp;</td>
                  <td width="100"><input name="layout_right_bgimage" type="text" class="f10" id="layout_right_bgimage" style="width: 100px;" value="<?php echo html($pagelayout["layout_right_bgimage"]); ?>" size="7"></td>
                  <td width="45" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_class'] ?>:&nbsp;</td>
                  <td width="55"><input name="layout_right_class" type="text" class="f10" id="layout_right_class" style="width: 55px;" value="<?php echo html($pagelayout["layout_right_class"]); ?>" size="7"></td>
                  <td width="1"><img src="img/leer.gif" alt="" width="1" height="20"></td>
                </tr>
              </table></td>
              </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>><td><img src="img/leer.gif" alt="" width="1" height="1"></td><td bgcolor="#E6EAED"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>><td><img src="img/leer.gif" alt="" width="1" height="1"></td><td bgcolor="#E6EAED"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_header'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
                  <tr>
                    <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_height'] ?>:&nbsp;</td>
                    <td width="35"><input name="layout_header_height" type="text" class="f10" id="layout_header_height" style="width: 35px;" value="<?php echo $pagelayout["layout_header_height"] ?>" size="4" maxlength="4"></td>
                    <td width="43" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
                    <td width="55"><input name="layout_header_bgcolor" type="text" class="f10" id="layout_header_bgcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_header_bgcolor"]); ?>" size="7" maxlength="7"></td>
                    <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_image'] ?>:&nbsp;</td>
                    <td width="100"><input name="layout_header_bgimage" type="text" class="f10" id="layout_header_bgimage" style="width: 100px;" value="<?php echo html($pagelayout["layout_header_bgimage"]); ?>" size="7"></td>
                    <td width="45" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_class'] ?>:&nbsp;</td>
                    <td width="55"><input name="layout_header_class" type="text" class="f10" id="layout_header_class" style="width: 55px;" value="<?php echo html($pagelayout["layout_header_class"]); ?>" size="7"></td>
                    <td width="1"><img src="img/leer.gif" alt="" width="1" height="20"></td>
                  </tr>
                </table></td>
            </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_topspace'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
                  <tr>
                    <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_height'] ?>:&nbsp;</td>
                    <td width="35"><input name="layout_topspace_height" type="text" class="f10" id="layout_topspace_height" style="width: 35px;" value="<?php echo $pagelayout["layout_topspace_height"] ?>" size="4" maxlength="4"></td>
                    <td width="43" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
                    <td width="55"><input name="layout_topspace_bgcolor" type="text" class="f10" id="layout_topspace_bgcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_topspace_bgcolor"]); ?>" size="7" maxlength="7"></td>
                    <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_image'] ?>:&nbsp;</td>
                    <td width="100"><input name="layout_topspace_bgimage" type="text" class="f10" id="layout_topspace_bgimage" style="width: 100px;" value="<?php echo html($pagelayout["layout_topspace_bgimage"]); ?>" size="7"></td>
                    <td width="45" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_class'] ?>:&nbsp;</td>
                    <td width="55"><input name="layout_topspace_class" type="text" class="f10" id="layout_topspace_class" style="width: 55px;" value="<?php echo html($pagelayout["layout_topspace_class"]); ?>" size="7"></td>
                    <td width="1"><img src="img/leer.gif" alt="" width="1" height="20"></td>
                  </tr>
                </table></td>
              </tr>
              <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
                <td align="right" class="chatlist"><?php echo $BL['be_admin_page_bottomspace'] ?>:&nbsp;</td>
                <td bgcolor="#E6EAED"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
                  <tr>
                    <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_height'] ?>:&nbsp;</td>
                    <td width="35"><input name="layout_bottomspace_height" type="text" class="f10" id="layout_bottomspace_height" style="width: 35px;" value="<?php echo $pagelayout["layout_bottomspace_height"] ?>" size="4" maxlength="4"></td>
                    <td width="43" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
                    <td width="55"><input name="layout_bottomspace_bgcolor" type="text" class="f10" id="layout_bottomspace_bgcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_bottomspace_bgcolor"]); ?>" size="7" maxlength="7"></td>
                    <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_image'] ?>:&nbsp;</td>
                    <td width="100"><input name="layout_bottomspace_bgimage" type="text" class="f10" id="layout_bottomspace_bgimage" style="width: 100px;" value="<?php echo html($pagelayout["layout_bottomspace_bgimage"]); ?>" size="7"></td>
                    <td width="45" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_class'] ?>:&nbsp;</td>
                    <td width="55"><input name="layout_bottomspace_class" type="text" class="f10" id="layout_bottomspace_class" style="width: 55px;" value="<?php echo html($pagelayout["layout_bottomspace_class"]); ?>" size="7"></td>
                    <td width="1"><img src="img/leer.gif" alt="" width="1" height="20"></td>
                  </tr>
                </table></td>
            </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_footer'] ?>:&nbsp;</td>
              <td bgcolor="#E6EAED"><table width="440" border="0" cellpadding="0" cellspacing="0" summary="">
                  <tr>
                    <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_height'] ?>:&nbsp;</td>
                    <td width="35"><input name="layout_footer_height" type="text" class="f10" id="layout_footer_height" style="width: 35px;" value="<?php echo $pagelayout["layout_footer_height"] ?>" size="4" maxlength="4"></td>
                    <td width="43" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_color'] ?>:&nbsp;</td>
                    <td width="55"><input name="layout_footer_bgcolor" type="text" class="f10" id="layout_footer_bgcolor" style="width: 55px;" value="<?php echo html($pagelayout["layout_footer_bgcolor"]); ?>" size="7" maxlength="7"></td>
                    <td width="53" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_image'] ?>:&nbsp;</td>
                    <td width="100"><input name="layout_footer_bgimage" type="text" class="f10" id="layout_footer_bgimage" style="width: 100px;" value="<?php echo html($pagelayout["layout_footer_bgimage"]); ?>" size="7"></td>
                    <td width="45" class="v10">&nbsp;&nbsp;<?php echo $BL['be_admin_page_class'] ?>:&nbsp;</td>
                    <td width="55"><input name="layout_footer_class" type="text" class="f10" id="layout_footer_class" style="width: 55px;" value="<?php echo html($pagelayout["layout_footer_class"]); ?>" size="7"></td>
                    <td width="1"><img src="img/leer.gif" alt="" width="1" height="20"></td>
                  </tr>
                </table></td>
            </tr>
            <tr class="pagelayout-editable"<?php echo  $pagelayout['editable_hidden']; ?>><td><img src="img/leer.gif" alt="" width="1" height="1"></td><td bgcolor="#E6EAED"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>

            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="20"></td></tr>
            <tr>
                <td><img src="img/leer.gif" alt="" width="90" height="1"><input name="layout_id" type="hidden" value="<?php echo $pagelayout["id"] ?>"></td>
                <td><input name="Submit" type="submit" class="button" value="<?php echo $BL['be_admin_page_button'] ?>">&nbsp;&nbsp;<input type="button" class="button" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='phpwcms.php?do=admin&p=8';"></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
    </table>
</form>
<script type="text/javascript">
$(function(){
    var $pagelayout_editable_items = $('tr.pagelayout-editable'),
        $pagelayout_radio_group = $('#radio-group-layout-render'),
        $pagelayout_radio_group_items = $("input[name='layout_render']"),
        $pagelayout_layout_render_value = <?php echo $pagelayout["layout_render"]; ?>,
        togglePagelayoutEditableItems = function() {
            var layout_render_value = parseInt($pagelayout_radio_group_items.filter(':checked').val(), 10);

            if(layout_render_value !== $pagelayout_layout_render_value) {
                $pagelayout_layout_render_value = layout_render_value;
                if($pagelayout_layout_render_value === 2) {
                    $pagelayout_editable_items.hide();
                } else {
                    $pagelayout_editable_items.show();
                }
            }
        };

    $pagelayout_radio_group_items.on({
        change: togglePagelayoutEditableItems,
        click: togglePagelayoutEditableItems
    });

});
</script>
<?php

}
