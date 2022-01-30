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

$buttonAction  = '<table cellpadding="0" cellspacing="0" border="0"><tr>'.LF;
// Article List
$buttonAction .= '<td><input type="button" value="'.$BL['be_article_cnt_center'];
$buttonAction .= '" class="button" title="'.$BL['be_article_cnt_center'].'" onclick="';
$buttonAction .= "location.href='phpwcms.php?do=articles';return false;\"></td>\n<td>&nbsp;</td>\n";
// Article Preview (new window)
$buttonActionLink = rel_url(array('phpwcms-preview'=>1), array(), empty($article["article_alias"]) ? 'aid='.$article["article_id"] : $article["article_alias"]);
$buttonAction .= '<td><input type="button" value="'.$BL['be_func_struct_preview'].'" class="button" title="'.$BL['be_func_struct_preview'].'" onclick="';
$buttonAction .= "window.open('".$buttonActionLink."', 'articlePreviewWindows');return false;\"></td>";
$buttonAction .= '</tr></table>';

?>
<form action="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=<?php echo $article["article_id"] ?>" method="post" name="addcontent" id="addcontent">
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr><td colspan="3" class="title"><?php echo $BL['be_article_cnt_ltitle'] ?></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
    <tr bgcolor="#92A1AF"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr bgcolor="#F3F5F8"><td><img src="img/leer.gif" alt="" width="23" height="4" /></td>
        <td><img src="img/leer.gif" alt="" width="453" height="1" /></td>
        <td><img src="img/leer.gif" alt="" width="62" height="1" /></td>
    </tr>
    <tr bgcolor="#F3F5F8">
        <td width="23" align="right"><?php if(count($phpwcms['allowed_lang']) == 0): ?>
            <img src="img/symbole/article_text.gif" alt="" width="9" height="11" border="0" style="margin-right:5px;" />
        <?php else: ?>
            <img src="img/famfamfam/lang/<?php echo ($lang = strtolower(empty($article["article_lang"]) ? $phpwcms['default_lang'] : $article["article_lang"])); ?>.png" title="<?php echo get_language_name($lang) ?>" style="margin-right:4px;" />
        <?php endif; ?></td>
        <td width="453" class="dir"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article["article_id"] ?>"><strong><?php echo html($article["article_title"]) ?></strong></a>&nbsp;[AID:<?php echo $article["article_id"] ?>]</td>
        <td width="62" align="right" class="h13" style="padding-right:1px"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php echo $article["article_id"] ?>"><img src="img/button/edit_22x13.gif" alt="<?php echo $BL['be_article_cnt_ledit'] ?>" width="22" height="13" border="0" /></a><a href="include/inc_act/act_articlecontent.php?do=<?php echo "3,".$article["article_id"].",0,".switch_on_off($article["article_aktiv"]) ?>" title="<?php echo $BL['be_article_cnt_lvisible'] ?>"><img src="img/button/visible_12x13_<?php echo $article["article_aktiv"] ?>.gif" alt="" width="12" height="13" border="0" /></a><a href="include/inc_act/act_articlecontent.php?do=<?php echo "1,".$article["article_id"]; ?>" title="<?php echo $BL['be_article_cnt_ldel'] ?>" onclick="return confirm('<?php echo $BL['be_article_cnt_ldeljs'].'\n'.html($article["article_title"]); ?>  \n ');"><img src="img/button/trash_13x13_1.gif" alt="" width="13" height="13" border="0" /></a></td>
    </tr>
    <tr bgcolor="#F3F5F8"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
    </tr>
        <tr bgcolor="#F3F5F8">
          <td><img src="img/leer.gif" alt="" width="23" height="1" /></td>
          <td><table border="0" cellpadding="0" cellspacing="0" summary="" class="tdMorepace">
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
            <tr>
              <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_article_urlalias'] ?>:&nbsp;</td>
              <td valign="top" class="v10"><strong><?php echo html($article["article_alias"]); ?></strong></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <?php   if($article["article_subtitle"]) { ?>
            <tr>
              <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_article_asubtitle'] ?>:&nbsp;</td>
              <td valign="top" class="v10"><strong><?php echo html($article["article_subtitle"]); ?></strong></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <?php } ?>

            <?php if(!empty($article["article_summary"])) { ?>
            <tr>
              <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_article_asummary'] ?>:&nbsp;</td>
              <td valign="top" class="v10"><?php echo html(getCleanSubString(strip_tags($article["article_summary"]), 250, '&#8230;'), false); ?></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <?php   } ?>
            <tr>
              <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_article_cat'] ?>:&nbsp;</td>
              <td valign="top" class="v10"><?php echo html($article["article_cat"]) ?></td>
            </tr>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <tr>
              <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_article_akeywords'] ?>:&nbsp;</td>
              <td valign="top" class="v10"><?php if($article["article_keyword"]) {echo html($article["article_keyword"]);}else{echo "not defined/completed";} ?></td>
            </tr>
            <?php
            if($article["article_canonical"]) {
            ?>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <tr>
              <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_canonical'] ?>:&nbsp;</td>
              <td valign="top" class="v10"><?php echo html($article["article_canonical"]); ?></td>
            </tr>
            <?php
            }

            if($article["article_redirect"]) {
            ?>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <tr>
              <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_article_cnt_redirect'] ?>:&nbsp;</td>
              <td valign="top" class="v10"><?php echo html($article["article_redirect"]); ?></td>
            </tr>
            <?php
            }

            $thumb_image = false;
            if(!empty($article["image"]["hash"])) {
                $thumb_image = get_cached_image(array(
                    "target_ext"    =>  $article['image']['ext'],
                    "image_name"    =>  $article['image']['hash'] . '.' . $article['image']['ext'],
                    "thumb_name"    =>  md5($article['image']['hash'].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                ));
            }

            $thumb_list_image = false;
            if(!empty($article["image"]["list_hash"])) {
                $thumb_list_image = get_cached_image(array(
                    "target_ext"    =>  $article['image']['list_ext'],
                    "image_name"    =>  $article['image']['list_hash'] . '.' . $article['image']['list_ext'],
                    "thumb_name"    =>  md5($article['image']['list_hash'].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
                ));
            }

            if($thumb_image || $thumb_list_image) {

            ?>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <tr>
              <td valign="top" class="v10" style="color:#727889"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
              <td valign="top" class="v10"><?php

            if($thumb_image) {
                echo '<img src="'. $thumb_image['src'] .'" '.$thumb_image[3].' alt="" style="margin-right:5px;" />';
            }
            if($thumb_list_image) {
                echo '<img src="'. $thumb_list_image['src'] .'" '.$thumb_list_image[3].' alt=""';
                if(!empty($article['image']['list_usesummary'])) {
                    echo ' class="inactive"';
                }
                echo ' />';
            }

            ?></td>
            </tr>
            <?php

            }

            ?>
            <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <tr>
              <td class="v10" style="color:#727889"><?php echo $BL['be_article_username']; ?>:&nbsp;</td>
              <td class="v10"><?php echo $article["article_username"] ?></td>
            </tr>
            <tr>
              <td class="v10" style="color:#727889"><?php echo $BL['be_article_eslastedit'] ?>:&nbsp;</td>
              <td class="v10"><?php echo phpwcms_strtotime($article["article_date"], $BL['be_longdatetime'], '') ?>&nbsp;&nbsp;<span style="color:#727889"><?php echo $BL['be_fprivedit_created'] ?>:</span>&nbsp;<?php echo date($BL['be_longdatetime'], $article["article_created"]) ?></td>
            </tr>
            <tr>
              <td class="v10 nowrap" style="color:#727889" nowrap="nowrap"><?php echo $BL['be_article_cnt_start'] ?>:&nbsp;</td>
              <td class="v10">
                  <?php echo $set_begin ? phpwcms_strtotime($article["article_begin"], $BL['be_longdatetime'], '') : $BL['be_not_set']; ?>&nbsp;&nbsp;<span style="color:#727889"><?php echo $BL['be_article_cnt_end'] ?>:</span>
                  <?php echo $set_end ? phpwcms_strtotime($article["article_end"], $BL['be_longdatetime'], '') : $BL['be_not_set']; ?>
              </td>
            </tr>
            <tr>
              <td class="v10 nowrap" style="color:#727889" nowrap="nowrap"><?php echo $BL['be_cnt_sortvalue'] ?>:&nbsp;</td>
              <td class="v10"><?php echo $article["article_sort"] ?>&nbsp;&nbsp;<span style="color:#727889"><?php echo $BL['be_priorize'] ?>:</span>&nbsp;<?php echo $article["article_priorize"] ?></td>
            </tr>

            <tr>
              <td class="v10 nowrap" style="color:#727889" nowrap="nowrap"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
              <td class="v10"><?php echo ($article["article_nositemap"] == 1 ? '&check;' : '-') . ' ' . $BL['be_ctype_sitemap'] ?></td>
            </tr>
            <tr>
              <td class="v10">&nbsp;</td>
              <td class="v10"><?php echo ($article["article_nosearch"] == 1 ? '-' : '&check;') . ' ' . $BL['be_fsearch_searchlabel'] ?></td>
            </tr>
            <tr>
              <td class="v10">&nbsp;</td>
              <td class="v10"><?php echo ($article["article_norss"] == 1 ? '&check;' : '-') . ' ' . $BL['be_no_rss'] ?></td>
            </tr>
            <tr>
              <td class="v10">&nbsp;</td>
              <td class="v10"><?php echo ($article["article_opengraph"] == 1 ? '&check;' : '-') . ' ' . $BL['be_opengraph_support'] ?></td>
            </tr>
            <tr>
              <td class="v10">&nbsp;</td>
              <td class="v10"><?php echo ($article["article_archive_status"] == 1 ? '&check;' : '-') . ' ' . $BL['be_show_archived']; //&#x2610; ?></td>
            </tr>

          </table></td>
            <td>&nbsp;</td>
        </tr>
        <tr bgcolor="#F3F5F8"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
        <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
        <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

        <tr><td colspan="3"><?php echo $buttonAction; ?></td></tr>

            <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

            <tr bgcolor="#92A1AF"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <tr bgcolor="#D9DEE3"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
            <tr bgcolor="#D9DEE3"><td colspan="3"><table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>
                  <td><img src="img/leer.gif" alt="" width="7" height="1" /></td>
                  <td><img src="img/symbole/add_content.gif" alt="" width="11" height="9" /><img src="img/leer.gif" alt="" width="5" height="1" /></td>
                  <td><select name="ctype" class="v12" id="ctype" onchange="this.form.submit();">
<?php

$temp_count = 0;
$user_selected_cp = isset($_SESSION["wcs_user_cp"]) && count($_SESSION["wcs_user_cp"]);

if(is_array($article["article_cntpart"]) && count($article["article_cntpart"])) {

    // list all content parts usable for this article category
    foreach($article["article_cntpart"] as $value) {

        if($user_selected_cp && !isset($_SESSION["wcs_user_cp"][$value])) {
            continue;
        }

        if(isset($wcs_content_type[$value])) {

            echo getContentPartOptionTag($value, $wcs_content_type[$value], $article['article_cpdefault']);
            $temp_count++;

        }
        $value1 = $value * (-1);
        if(isset($BL['be_admin_optgroup_label'][$value1]) && $value) {
            echo '<optgroup label="[ '.$BL['be_admin_optgroup_label'][$value1].' ]" class="cntOptGroup"></optgroup>'."\n";
        }
    }

}
if(!$temp_count) {
    //list all available content parts
    foreach($wcs_content_type as $key => $value) {

        if($user_selected_cp && !isset($_SESSION["wcs_user_cp"][$key])) {
            continue;
        }

        echo getContentPartOptionTag($key, $value, $article['article_cpdefault']);
    }
}
?>
                    </select></td>
                  <td><img src="img/leer.gif" alt="" width="5" height="1" /></td>
                  <td><input type="submit" name="image" value="<?php echo  $BL['be_article_cnt_add'] ?>" class="v12" title="<?php echo  $BL['be_article_cnt_addtitle'] ?>" /></td>
                </tr>
              </table></td></tr>
            <tr bgcolor="#D9DEE3"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
            <tr bgcolor="#92A1AF"><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <?php

            // Sorting
            $sc = 0;
            $scc = 0;
            $sbutton = array();
            $sbutton_string = array();

            $sql  = "SELECT acontent_id, acontent_sorting, acontent_trash, acontent_block FROM ".DB_PREPEND."phpwcms_articlecontent ";
            $sql .= "WHERE acontent_aid=".$article["article_id"]." ORDER BY acontent_block, acontent_sorting, acontent_id";
            $result = _dbQuery($sql);

            if(isset($result[0]['acontent_id'])) {

                foreach($result as $row) {
                    $scc++;
                    if($row['acontent_trash'] == 0) {
                        $sc++;
                        $sbutton[$sc]["id"]    = $row['acontent_id'];
                        $sbutton[$sc]["sort"]  = $row['acontent_sorting'];
                        $sbutton[$sc]["block"] = $row['acontent_block'];
                    }
                }
            }
            if($sc) {
                //Jetzt aufbauen der Sortieranweisung
                foreach($sbutton as $key => $value) {
                    if($key == 1) {
                        // if 1st content part in list
                        $sbutton[$key]["top"] = '<img src="img/button/sort_top_0.gif" border="0" alt="" />';

                    } elseif(isset($sbutton[$key-1]["block"]) && $sbutton[$key-1]["block"] != $sbutton[$key]["block"]) {
                        // if this content part is selected for different block than previous
                        $sbutton[$key]["top"] = '<img src="img/button/sort_top_0.gif" border="0" alt="" />';

                    } else {
                        $sbutton[$key]["top"] = "<a href=\"include/inc_act/act_articlecontent.php?sort=".
                        $sbutton[$key]["id"].":".$sbutton[$key-1]["sort"]."|".
                        $sbutton[$key-1]["id"].":".$sbutton[$key]["sort"].
                        "\" title=\"".$BL['be_article_cnt_up']."\"><img src=\"img/button/sort_top_1.gif\" border=\"0\" alt=\"\" /></a>";
                    }
                    if($key == $sc) {
                        // if this is the last content part in list
                        $sbutton[$key]["bottom"] = "<img src=\"img/button/sort_bottom_0.gif\" border=\"0\" alt=\"\" />";

                    } elseif(isset($sbutton[$key+1]["block"]) && $sbutton[$key+1]["block"] != $sbutton[$key]["block"]) {
                        // if this is the last content part in current block and next is different
                        $sbutton[$key]["bottom"] = "<img src=\"img/button/sort_bottom_0.gif\" border=\"0\" alt=\"\" />";

                    } else {
                        $sbutton[$key]["bottom"] = "<a href=\"include/inc_act/act_articlecontent.php?sort=".
                        $sbutton[$key]["id"].":".$sbutton[$key+1]["sort"]."|".
                        $sbutton[$key+1]["id"].":".$sbutton[$key]["sort"].
                        "\" title=\"".$BL['be_article_cnt_down']."\"><img src=\"img/button/sort_bottom_1.gif\" border=\"0\" alt=\"\" /></a>";
                    }
                    $sbutton_string[$sbutton[$key]["id"]] = $sbutton[$key]["top"].
                    "<img src=\"img/leer.gif\" width=\"1\" height=\"1\" alt=\"\" />".
                    $sbutton[$key]["bottom"];
                }
                unset($sbutton);
            }

            //Listing zugehöriger Artikel Content Teile
            $sortierwert            = 1;
            $contentpart_block      = ' ';
            $contentpart_block_name = '';
            $contentpart_tab        = '';
            $contentpart_tab_close  = '';

            $sql =  "SELECT *, UNIX_TIMESTAMP(acontent_tstamp) as acontent_date FROM ".DB_PREPEND."phpwcms_articlecontent ".
                    "WHERE acontent_aid=".$article["article_id"]." AND acontent_trash=0 ".
                    "ORDER BY acontent_block, acontent_sorting, acontent_tab, acontent_id";
            $result = _dbQuery($sql);
            if(isset($result[0]['acontent_id'])) {
                foreach($result as $row) {

                    // if type of content part not enabled available
                    if(!isset($wcs_content_type[ $row["acontent_type"] ]) || ($row["acontent_type"] == 30 && !isset($phpwcms['modules'][$row["acontent_module"]]))) {
                        continue;
                    }

                    if($contentpart_tab_close) {
                        echo $contentpart_tab_close;
                        $contentpart_tab_close = '';
                    }

                    // now show current block name
                    if($contentpart_block != $row['acontent_block']) {
                        $contentpart_block = $row['acontent_block'];
                        $contentpart_block_name = html(' {'.$row['acontent_block'].'}');
                        $contentpart_block_color = ' bgcolor="#E0D6EB"';

                        switch($contentpart_block) {
                            case '':
                            case 'CONTENT':
                                $contentpart_block_name = $BL['be_main_content'].$contentpart_block_name;
                                if($article['article_paginate']) {
                                    $contentpart_block_name .= ' / <img src="img/symbole/content_cppaginate.gif" alt="" style="margin-right:2px;" />';
                                    $contentpart_block_name .= $BL['be_cnt_pagination'];
                                }
                                $contentpart_block_color = ' bgcolor="#F5CCCC"';
                                break;

                            case 'LEFT':
                                $contentpart_block_name = $BL['be_cnt_left'].$contentpart_block_name;
                                $contentpart_block_color = ' bgcolor="#E0EBD6"';
                                break;

                            case 'RIGHT':
                                $contentpart_block_name = $BL['be_cnt_right'].$contentpart_block_name;
                                $contentpart_block_color = ' bgcolor="#FFF5CC"';
                                break;

                            case 'HEADER':
                                $contentpart_block_name = $BL['be_admin_page_header'].$contentpart_block_name;
                                $contentpart_block_color = ' bgcolor="#EBEBD6"';
                                break;

                            case 'FOOTER':
                                $contentpart_block_name = $BL['be_admin_page_footer'].$contentpart_block_name;
                                $contentpart_block_color = ' bgcolor="#E1E8F7"';
                                break;

                            case 'CPSET':
                                $contentpart_block_name = $BL['be_settings'].' <span style="font-weight:normal">('.$BL['be_system_container_norender'].')</span>';
                                $contentpart_block_color = ' bgcolor="#cceaf5"';
                                break;

                            case 'SYSTEM':
                                $contentpart_block_name = $BL['be_system_container'].' <span style="font-weight:normal">('.$BL['be_system_container_norender'].')</span>';
                                $contentpart_block_color = ' bgcolor="#ffdc9d"';
                                break;
                        }

            ?>
            <tr<?php echo $contentpart_block_color ?>>
                <td align="right" style="padding-right:5px;"><img src="img/symbole/<?php echo $contentpart_block == 'CPSET' ? 'cpset' : 'block' ?>.gif" alt="" width="9" height="11" border="0" /></td>
                <td style="font-size:9px;font-weight:bold;"><?php echo  $contentpart_block_name ?></td>
                <td><img src="img/leer.gif" alt="" width="1" height="15" /></td>
            </tr>
            <tr><td colspan="3" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <?php
                    }

                    // now check if content part is tabbed
                    if($row['acontent_tab'] && $contentpart_tab != $row['acontent_tab']) {
                        $contentpart_tab        = $row['acontent_tab'];
                        $contentpart_tabbed     = explode('_', $contentpart_tab, 2);
                        $contentpart_tab_title  = isset($contentpart_tabbed[1]) ? trim($contentpart_tabbed[1]) : '';
                        $contentpart_tab_number = explode('|', $contentpart_tabbed[0]);
                        $contentpart_tab_type   = empty($contentpart_tab_number[1]) ? 1 : $contentpart_tab_number[1];
                        $contentpart_tab_number = intval($contentpart_tab_number[0]);

            ?>
            <tr<?php echo $contentpart_block_color ?>>
                <td align="right" style="padding-right:5px;"><img src="img/symbole/tabbed.gif" alt="" width="9" height="11" border="0" /></td>
                <td style="font-size:9px;"><?php

                        if($contentpart_tab_type == 2) {
                            echo $BL['be_ctype_accordion'];
                        } elseif(isset($template_default['attributes']['cpgroup_custom'][$contentpart_tab_type])) {
                            echo html($template_default['attributes']['cpgroup_custom'][$contentpart_tab_type]['title']);
                        } else {
                            echo $BL['be_ctype_tabs'];
                        }
                        echo ' / ' . $BL['be_cnt_paginate_subsection'] . ': ';
                        if($contentpart_tab_title !== '') {
                            echo html($contentpart_tab_title) . ' ';
                        }
                        echo '[' . $contentpart_tab_number . ']';

                 ?>&nbsp;</td>
                <td><img src="img/leer.gif" alt="" width="1" height="15" /></td>
            </tr>
            <tr><td colspan="3" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <?php

                    } elseif($contentpart_tab && empty($row['acontent_tab'])) {

                        // not the same tab but following cp is not tabbed
                        $contentpart_tab = '';

                        $contentpart_tab_close  = '<tr'.$contentpart_block_color.'><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>';
                        $contentpart_tab_close .= '<tr><td colspan="3" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>';

                    }

            ?>
            <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

            <tr>
              <td align="right" style="padding-right:5px;"><img src="img/symbole/content_9x11<?php if($row["acontent_granted"]) { echo $row["acontent_granted"] == 2 ? '_hidden_granted' : '_granted'; } ?>.gif" alt="" width="9" height="11" border="0" /></td>
              <td><table border="0" cellpadding="0" cellspacing="0" summary="" width="100%">
                <tr>
                  <td width="180" style="font-size:9px;font-weight:bold;text-transform:uppercase;"><?php

                $cntpart_title = $wcs_content_type[$row["acontent_type"]];
                if(!empty($row["acontent_module"])) {

                    $cntpart_title .= ': '.$BL['modules'][$row["acontent_module"]]['listing_title'];

                }
                echo $cntpart_title;


                  ?></td>
                  <td width="23" class="nowrap"><?php echo $sbutton_string[$row["acontent_id"]]; ?></td>
                  <td class="v09 nowrap" style="color:#727889;padding:0 4px 0 5px" width="60" nowrap="nowrap">[ID:<?php echo $row["acontent_id"] ?>]</td>
                  <td class="v09 nowrap" nowrap="nowrap"><?php

                  echo date($BL['be_shortdatetime'], $row["acontent_date"]).'&nbsp;';

                  if($contentpart_block != 'CPSET') {

                        //Display cp paginate page number
                        if($article["article_paginate"]) {

                            echo '<img src="img/symbole/content_cppaginate.gif" alt="subsection" title="subsection" />';
                            echo $row["acontent_paginate_page"] == 0 ? 1 : $row["acontent_paginate_page"];

                        }

                        //Anzeigen der Space Before/After Info
                        if(intval($row["acontent_before"])) {
                            echo '<img src="img/symbole/content_space_before.gif" alt="" />'.$row["acontent_before"];
                        }
                        if(intval($row["acontent_after"])) {
                            echo '<img src="img/symbole/content_space_after.gif" alt="" />'.$row["acontent_after"];
                        }
                        if($row["acontent_top"]) {
                            echo '<img src="img/symbole/content_top.gif" alt="TOP" title="TOP" />';
                        }
                        if($row["acontent_anchor"]) {
                            echo '<img src="img/symbole/content_anchor.gif" alt="Anchor" title="Anchor" />';
                        }

                  }

                  ?></td>
                </tr>
              </table></td>
              <td align="right" style="padding-right:1px;"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=<?php
              echo $article["article_id"]."&amp;acid=".$row["acontent_id"];
              ?>" title="<?php echo $BL['be_article_cnt_edit'] ?>"><img src="img/button/edit_22x13.gif" alt="" border="0" /></a><?php
                // duplicate content part
                echo '<a href="include/inc_act/act_structure.php?do=8%7C'.$row["acontent_id"].'%7C'.$article["article_id"].'%7C'.($row["acontent_sorting"]+5).'" ';
                echo 'title="'.$BL['be_func_content_copy'].' [ID:'.$row["acontent_id"].']" ';
                echo 'onclick="return confirm(\''.js_singlequote($BL['be_func_content_copy']).': \n'.js_singlequote($cntpart_title.' [ID:'.$row["acontent_id"].']').'\');">';
                echo '<img src="img/button/copy_13x13.gif" border="0" alt="" width="13" height="13" /></a>';

              ?><a href="include/inc_act/act_articlecontent.php?do=<?php
              echo "2,".$article["article_id"].",".$row["acontent_id"].",".switch_on_off($row["acontent_visible"])
              ?>" title="<?php
              echo $BL['be_article_cnt_lvisible']
              ?>"><img src="img/button/visible_12x13_<?php
              echo $row["acontent_visible"]
              ?>.gif" alt="" width="12" height="13" border="0" /></a><a href="include/inc_act/act_articlecontent.php?do=<?php
              echo "9,".$article["article_id"].",".$row["acontent_id"]
              ?>" title="<?php echo $BL['be_article_cnt_delpart'] ?>" onclick="return confirm('<?php
              echo $BL['be_article_cnt_delpartjs'] ?> \n[ID: <?php echo $row["acontent_id"]
              ?>]\n ');"><img src="img/button/trash_13x13_1.gif" alt="" width="13" height="13" border="0" /></a></td>
            </tr>

<?php   if($row["acontent_block"] === 'SYSTEM'): ?>
            <tr>
                <td class="v09">&nbsp;</td>
                <td colspan="2" class="v09"><?php
                    echo '<span class="greyed">', $BL['be_article_rendering'], ':</span> <span class="tool-title">';

                    if(empty($row["acontent_tid"])) {
                        echo $BL['be_custom_scriptlogic'];
                    } elseif($row["acontent_tid"] == 3) {
                        echo $BL['be_article_forlist'].' + '.$BL['be_article_forfull'];
                    } elseif($row["acontent_tid"] == 2) {
                        echo $BL['be_article_forfull'];
                    } else { // == 1
                        echo $BL['be_article_forlist'];
                    }

                    echo '</span>';

                ?></td>
            </tr>
            <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
<?php   endif;

        $acontent_livedate = $row['acontent_livedate'] === '0000-00-00 00:00:00' ? false : phpwcms_strtotime($row['acontent_livedate'], $BL['be_longdatetime'], '');
        $acontent_killdate = $row['acontent_killdate'] === '0000-00-00 00:00:00' ? false : phpwcms_strtotime($row['acontent_killdate'], $BL['be_longdatetime'], '');

        if($acontent_livedate || $acontent_killdate):
?>
            <tr>
                <td class="v09">&nbsp;</td>
                <td class="v10" colspan="2">
                    <span class="chatlist"><?php echo $BL['be_article_cnt_start'] ?>:</span> <?php echo $acontent_livedate ? $acontent_livedate : $BL['be_not_set']; ?>
                    &nbsp;&nbsp;
                    <span class="chatlist"><?php echo $BL['be_article_cnt_end'] ?>:</span> <?php echo $acontent_killdate ? $acontent_killdate : $BL['be_not_set']; ?>
                </td>
            </tr>
<?php   endif;

    // list content type overview
    $cinfo = NULL;

    // check default content parts (system internals
    if($row['acontent_type'] != 30 && file_exists('include/inc_tmpl/content/cnt'.$row['acontent_type'].'.list.inc.php')) {

        include PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt'.$row['acontent_type'].'.list.inc.php';

    } elseif($row['acontent_type'] == 30 && file_exists($phpwcms['modules'][$row['acontent_module']]['path'].'inc/cnt.list.php')) {

        // custom module
        include $phpwcms['modules'][$row['acontent_module']]['path'].'inc/cnt.list.php';

    } else {

        // default fallback
        include PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt0.list.inc.php';

    }
    // end list

?>
            <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
            <tr><td colspan="3" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<?php
    }
} //Ende Listing Artikel Content Teile
?>
            <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

</table>
<input name="csorting" type="hidden" id="csorting" value="<?php echo ($scc*10); ?>" />
</form>
<?php

echo $buttonAction;
