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


// OK check article and category information
$sql  = 'SELECT DISTINCT * FROM '.DB_PREPEND.'phpwcms_article ar LEFT JOIN '.DB_PREPEND.'phpwcms_articlecat ac ON ';
$sql .= "ar.article_cid=ac.acat_id WHERE ar.article_id='".$content["aid"]."' LIMIT 1";
$content['article'] = _dbQuery($sql);
$content['article'] = isset($content['article'][0]) ? $content['article'][0] : array('article_title' => '', 'acat_name' => '', 'acat_template'=>0);
$content['cp_setting_mode'] = false;

if(empty($content['article']['acat_id'])) { // Root structure
    $content['article']['acat_name']        = $indexpage['acat_name'];
    $content['article']['acat_id']          = 0;
    $content['article']['acat_template']    = $indexpage['acat_template'];
}

// Livedate / killdate fallback
if(empty($content["livedate"]) || $content["livedate"] === '0000-00-00 00:00:00') {
    $content["livedate"] = '';
    $set_livedate = 0;
} else {
    $set_livedate = 1;
}
if(empty($content["killdate"]) || $content["killdate"] === '0000-00-00 00:00:00') {
    $content["killdate"] = '';
    $set_killdate = 0;
} else {
    $set_killdate = 1;
}

initJsCalendar();

?>
<script type="text/javascript">
    function validate_before_after(elem, checkElem) {
        if(elem.value.length === 1 && (elem.value === '-' || elem.value === '+')) {
            return true;
        }
        var checkbox_element = document.getElementById(checkElem);
        var elem_int = parseInt(elem.value, 10);
        if(elem_int) {
            elem.value = elem_int;
            checkbox_element.checked = true;
        } else {
            checkbox_element.checked = false;
            elem.value = '';
        }
    }
</script>
<form action="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=<?php echo $content["aid"]."&amp;acid=".$content["id"] ?>" method="post" name="articlecontent" id="articlecontent" <?php

    // Some javascript actions neccessary on submit
    switch($content["type"]) {

        case 2:
        case 29:
        case 16:
        case 50:
        case 89:
            echo 'onsubmit="selectAllOptions(this.cimage_list);return checkCp();"';
            break;

        //case 25:
        case 7:
            echo 'onsubmit="selectAllOptions(this.cfile_list);return checkCp();"';
            break;

        case 8:
            echo 'onsubmit="selectAllOptions(this.calink);return checkCp();"';
            break;

        case 53:
            echo 'onsubmit="selectAllOptions(this.cforum_selection);return checkCp();"';
            break;

        default:
            echo 'onsubmit="var ct=document.getElementById(\'target_ctype\'); if(ct.disabled){ct.disabled=false;} return checkCp();"';

    }

    if(empty($content["id"]) && empty($content['block'])) {

        $sendbutton = $BL['be_article_cnt_button2'];

        $content["block"]           = 'CONTENT';
        $content["before"]          = '';
        $content["after"]           = '';
        $content["title"]           = '';
        $content["subtitle"]        = '';
        $content["top"]             = 0;
        $content["visible"]         = 0;
        $content["anchor"]          = 0;
        $content['comment']         = '';
        $content['paginate_title']  = '';
        $content['paginate_page']   = '';
        $content["granted"]         = 0;

    } else {

        $sendbutton = $BL['be_article_cnt_button1'];

    }

    ?>>
<input type="hidden" name="ctype_module" value="<?php echo html($content["module"]) ?>" />
<table summary="" width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:13px;">

    <tr><td colspan="2" class="title"><?php

    echo $BL['be_article_cnt_title'].' &#8212; <span style="text-transform: uppercase;">';
    echo $wcs_content_type[$content["type"]];
    if(!empty($content["module"])) {

        echo ': '.$BL['modules'][$content["module"]]['listing_title'];

        // check if Module is in setting mode
        if(!empty($phpwcms['modules'][$content["module"]]['setting'])) {
            $content['cp_setting_mode'] = true;
        }

    }
    echo '</span>';

    ?></td></tr>
    <tr><td colspan="2" class="rowspacer5x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr bgcolor="#D9DEE3"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>

    <tr bgcolor="#D9DEE3">
        <td align="right" class="chatlist nowrap" nowrap="nowrap"><a href="phpwcms.php?do=admin&amp;p=6&amp;struct=<?php

        if(empty($content['article']['acat_id'])) {
            echo 'index';
        } else {
            echo $content['article']['acat_struct'], '&amp;cat=', $content['article']['acat_id'];
        }

        ?>" onclick="return confirm('<?php echo $BL['be_dialog_warn_nosave'];
        ?>');"><?php echo $BL['be_article_cat'];

        ?><img src="img/symbole/redirect.gif" alt="" border="0" style="margin:0 0 0 2px;position:relative;top:1px;" /></a>:&nbsp;</td>
        <td><strong><?php echo html($content["article"]['acat_name']).' [ID:'.$content['article']['acat_id'].']' ?></strong></td>
    </tr>
    <tr bgcolor="#D9DEE3"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td>
    </tr>
    <tr bgcolor="#D9DEE3">
        <td align="right" class="chatlist nowrap" nowrap="nowrap"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id=<?php

        echo $content["aid"];
        ?>" onclick="return confirm('<?php echo $BL['be_dialog_warn_nosave'];
        ?>');"><?php echo $BL['be_article_atitle'];

        ?><img src="img/symbole/redirect.gif" alt="" border="0" style="margin:0 0 0 2px;position:relative;top:1px;" /></a>:&nbsp;</td>
        <td onclick="showEditArticleID(this);" onmouseover="this.ttOffsetY=0;Tip('<?php echo $BL['be_change_articleID'].'<br />'.$BL['be_cnt_default'].': '.$content["aid"] ?>')" class="linkcursor"><strong><?php echo html($content["article"]['article_title']) ?></strong></td>
    </tr>


    <tr bgcolor="#D9DEE3"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /><script type="text/javascript">

    var istuff_done = false;
    function showEditArticleID(istuff) {
        if(istuff_done) return false;
        istuff.innerHTML += '&nbsp; <'+'span class="chatlist"'+'><?php echo $BL['be_func_struct_articleID'] ?>:&nbsp;<'+'/span><'+'input type="text" name="ctype_change_aid" value="<?php echo $content["aid"] ?>" class="v11 width35" /'+'>';
        istuff_done = true;
    }

    </script></td></tr>
    <tr><td colspan="2" class="rowspacer"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

    <tr bgcolor="#F3F5F8">
      <td align="right" class="chatlist"><?php

        echo $BL['be_article_cnt_type'];
        $enable_disable = '';

    ?>:&nbsp;</td>
      <td><table summary="" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><select name="target_ctype" id="target_ctype" onchange="if(confirm('<?php

    // echo Message for JS dialog
    echo $BL['be_func_switch_contentpart'];

    // Menü mit Content Typen erstellen
    // build select box options and remember the "old" value for javascript
    $temp_select                = '';
    $temp_count                 = 0;
    $contentpart_temp_selected  = 0;
    $user_selected_cp           = isset($_SESSION["wcs_user_cp"]) && count($_SESSION["wcs_user_cp"]);

    if(is_array($article["article_cntpart"]) && count($article["article_cntpart"])) {

        if(!in_array($content['type'], $article["article_cntpart"])) {
            $article["article_cntpart"][] = $content['type'];
        }

        // list all content parts usable for this article category
        foreach($article["article_cntpart"] as $value) {

            if($user_selected_cp && !isset($_SESSION["wcs_user_cp"][$value]) && $value != $content['type']) {
                continue;
            }

            if(isset($wcs_content_type[$value])) {

                $temp_select .= getContentPartOptionTag($value, $wcs_content_type[$value], $content['type'], $content['module']);
                $temp_count++;
            }
            $value1 = $value * (-1);
            if(isset($BL['be_admin_optgroup_label'][$value1]) && $value) {
                $temp_select .= '<optgroup label="[ '.$BL['be_admin_optgroup_label'][$value1].' ]" class="cntOptGroup"></optgroup>'."\n";
            }
        }

    }
    if(!$temp_count) {
        //list all available content parts
        foreach($wcs_content_type as $key => $value) {

            if($user_selected_cp && !isset($_SESSION["wcs_user_cp"][$key]) && $key != $content['type']) {
                continue;
            }

            $temp_select .= getContentPartOptionTag($key, $value, $content['type'], $content['module']);
            $temp_count++;
        }
    }

?>')){ this.form.submit(); } else { this.form.target_ctype.selectedIndex = <?php echo $contentpart_temp_selected; ?>; return false; }">
<?php

    //Menü mit Content Typen erstellen
    echo $temp_select

?>
                      </select>
                    </td>
            <?php echo $enable_disable; ?>

                  </tr>
                </table></td>
     </tr>

    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /><?php

// Content Part Setting Mode — hide all settings related to article and content part rendering
if($content['cp_setting_mode']):
        // some hidden fields with default content

?>
        <input type="hidden" name="cblock" value="CPSET" />
        <input type="hidden" name="csorting" value="0" />
        <input type="hidden" name="cbefore" value="" />
        <input type="hidden" name="ctab_title" value="" />
        <input type="hidden" name="ctab_number" value="" />
        <input type="hidden" name="ctitle" value="" />
        <input type="hidden" name="csubtitle" value="" />
        <input type="hidden" name="cpaginate_title" value="" />
        <input type="hidden" name="cpaginate_page" value="" />

<?php

    // normal contentpart edit mode
    else:

        // Detect Template
        if(!empty($content['article']['acat_template'])) {
            $content['current_template'] = _dbGet('phpwcms_template', '*', 'template_trash=0 AND template_id='._dbEscape($content['article']['acat_template']), '', '', 1);
        }
        if(!isset($content['current_template'][0])) {
            $content['current_template'] = _dbGet('phpwcms_template', '*', 'template_trash=0 AND template_default=1', '', '', 1);
        }
        if(!isset($content['current_template'][0])) {
            $content['current_template'] = _dbGet('phpwcms_template', '*', 'template_trash=0', '', 'template_default DESC', 1);
        }

        $content['blocks'] = array();

        if(isset($content['current_template'][0]['template_var'])) {
            $content['template_name'] = html($content['current_template'][0]['template_name']);
            if($content['current_template'][0]['template_default']) {
                $content['template_name'] .= ' ('.$BL['be_admin_tmpl_default'].')';
            }
            $content['current_template'] = unserialize($content['current_template'][0]['template_var']);
            if(!empty($content['current_template']['customblock'])) {
                $content['current_template'] = explode(',', $content['current_template']['customblock']);
                if(count($content['current_template'])) {
                    $content['blocks'][] = '<optgroup label="'.$BL['be_admin_page_blocks'].', '.$BL['be_admin_page_customblocks'].'">';
                    foreach($content['current_template'] as $value) {
                        $value = trim($value);
                        if($value !== '') {
                            $valhtml = html($value);
                            $content['blocks'][] = '    <option value="'.$valhtml.'"'.is_selected($value, $content["block"], 0, 0).'>'.$valhtml.'</option>';
                        }
                    }
                    $content['blocks'][] = '</optgroup>';
                }
            }
        } else {
            $content['template_name'] = $BL['be_admin_tmpl_default'];
        }

        $content['blocks'] = implode(LF.'                       ', $content['blocks']);

    ?></td></tr>

    <tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<?php

    $anchor_title = empty($content["id"]) ? '' : ' title="cpid'.$content["id"].'"';

    // handle tab settings
    $content["tab_style"] = ' style="display:none"';

    if(empty($content["tab"])) {

        $content["tab"]         = '';
        $content["tab_number"]  = '';
        $content["tab_title"]   = '';
        $content["tab_type"]    = 0;

    } else {

        $content["tab"]             = explode('_', $content["tab"], 2);
        $content["tab_title"]       = empty($content["tab"][1]) ? '' : $content["tab"][1];
        $content["tab_number"]      = explode('|', $content["tab"][0]);
        $content["tab_type"]        = empty($content["tab_number"][1]) ? 1 : $content["tab_number"][1];
        $content["tab_number"]      = intval($content["tab_number"][0]);

        if($content["tab_number"].$content["tab_title"]) {
            $content["tab"]         = 1;
            $content["tab_style"]   = '';
        }

    }

?>

    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_show_content'] ?>:&nbsp;</td>
        <td><table summary="" border="0" cellspacing="0" cellpadding="0" width="440">
            <tr>
                <td>
                    <select name="cblock" id="cblock" onchange="checkCntBlockPaginate(this);">
                        <optgroup label="<?php echo $BL['be_admin_struct_template'].': '.$content['template_name'] ?>">
                            <option value="CONTENT"<?php echo is_selected('CONTENT', $content["block"]) ?>><?php echo $BL['be_main_content'] ?> (CONTENT)</option>
                            <option value="LEFT"<?php echo is_selected('LEFT', $content["block"]) ?>><?php echo $BL['be_cnt_left'] ?> (LEFT)</option>
                            <option value="RIGHT"<?php echo is_selected('RIGHT', $content["block"]) ?>><?php echo $BL['be_cnt_right'] ?> (RIGHT)</option>
                            <option value="HEADER"<?php echo is_selected('HEADER', $content["block"]) ?>><?php echo $BL['be_admin_page_header'] ?> (HEADER)</option>
                            <option value="FOOTER"<?php echo is_selected('FOOTER', $content["block"]) ?>><?php echo $BL['be_admin_page_footer'] ?> (FOOTER)</option>
                            <option value="SYSTEM"<?php echo is_selected('SYSTEM', $content["block"]) ?>><?php echo $BL['be_system_container'] ?> (SYSTEM)</option>
                        </optgroup>
                        <?php echo $content['blocks'] ?>

                    </select>
                </td>

                <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_paginate_subsection']; ?>:&nbsp;</td>
                <td>
                    <select name="ctab" id="ctab" class="v11 width150" onchange="checkTabStatus(this);"<?php if($content["block"] == 'SYSTEM'): ?> disabled="disabled"<?php endif; ?> >
                        <option value="0"<?php is_selected(0, $content["tab_type"]); ?>><?php echo $BL['be_off'] ?></option>
                        <option value="1"<?php is_selected(1, $content["tab_type"]); ?>><?php echo $BL['be_ctype_tabs'] ?></option>
                        <option value="2"<?php is_selected(2, $content["tab_type"]); ?>><?php echo $BL['be_ctype_accordion'] ?></option>
<?php
    if(isset($template_default['attributes']['cpgroup_custom']) && is_array($template_default['attributes']['cpgroup_custom']) && count($template_default['attributes']['cpgroup_custom'])):
        foreach($template_default['attributes']['cpgroup_custom'] as $tab_type_value => $value):
?>
            <option value="<?php echo $tab_type_value ?>"<?php is_selected($tab_type_value, $content["tab_type"]); ?>><?php echo html($value['title']) ?></option>
<?php
        endforeach;
    endif;
?>
                    </select>
                    <script>

                        var cTabStatus = <?php echo $content["tab_type"] ? 'true' : 'false' ?>,
                            loadblock = true;

                        function checkTabStatus(tabVal) {

                            cTabStatus = tabVal.options[tabVal.selectedIndex].value !== '0';

                            if(!cTabStatus) {
                                document.getElementById('ctab1').style.display = 'none';
                                document.getElementById('ctab2').style.display = 'none';
                            } else {
                                document.getElementById('ctab1').style.display = '';
                                document.getElementById('ctab2').style.display = '';
                            }

                            tabVal.blur();
                        }

                        function setTabStatus(enabled) {
                            document.getElementById('ctab').disabled = !enabled;
                        }

                        function checkCntBlockPaginate(obj) {
                            var paginate = document.getElementById("cpaginate_page"),
                                block = obj.options[obj.selectedIndex].value,
                                system1 = document.getElementById('system1'),
                                ctab = document.getElementById('ctab');

                            if(block === 'SYSTEM') {
                                system1.style.display = 'table-row';
                                ctab.value = '0';
                                ctab.disabled = true;
                                cTabStatus = false;

                                document.getElementById('ctab1').style.display = 'none';
                                document.getElementById('ctab2').style.display = 'none';

                            } else {

                                system1.style.display = 'none';
                                ctab.disabled = false;

                            }

                            if(block !== 'CONTENT') {
                                if(paginate.value !== "0" && !loadblock) {
                                    if(!confirm("<?php echo $BL['be_cnt_subsection_warning'] ?>")) {
                                        obj.selectedIndex = 0;
                                        return false;
                                    }
                                }
                                paginate.disabled = true;
                            } else {
                                paginate.disabled = false;
                            }
                        }

                        function checkCp() {

                            var ctab = document.getElementById('ctab'),
                                ctab_title = document.getElementById('ctab_title'),
                                ctab_number = document.getElementById('ctab_number');

                            if(ctab.selectedIndex > 0 && ctab_title.value === '' && ctab_number.value === '') {
                                return confirm('<?php echo PHPWCMS_CHARSET === 'utf-8' ? $BL['confirm_cp_tab_warning'] : utf8_decode($BL['confirm_cp_tab_warning']); ?>');
                            }

                            return true;
                        }

                    </script>
                </td>

            </tr>
        </table></td>
    </tr>

    <tr id="system1"<?php if($content["block"] !== 'SYSTEM'): ?> style="display:none;"<?php endif; ?>>
        <td align="right" class="chatlist"><?php echo $BL['be_article_rendering'] ?>:&nbsp;</td>
        <td class="tdtop5 tdbottom5">
            <select name="ctid" id="ctid">
                <option value="0"<?php echo is_selected(0, $content['tid']) ?>><?php echo $BL['be_custom_scriptlogic'] ?></option>
                <option value="1"<?php echo is_selected(1, $content['tid']) ?>><?php echo $BL['be_article_forlist'] ?></option>
                <option value="2"<?php echo is_selected(2, $content['tid']) ?>><?php echo $BL['be_article_forfull'] ?></option>
                <option value="3"<?php echo is_selected(3, $content['tid']) ?>><?php echo $BL['be_article_forlist'].' + '.$BL['be_article_forfull'] ?></option>
            </select>
        </td>
    </tr>

    <!-- ctab section -->
    <tr id="ctab1"<?php echo $content["tab_style"] ?>><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
    <tr id="ctab2"<?php echo $content["tab_style"] ?>>
      <td align="right" class="chatlist"><?php echo $BL['be_cnt_paginate_subsection'] ?>:&nbsp;</td>
      <td><table summary="" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><input name="ctab_title" type="text" id="ctab_title" class="f11b width325" value="<?php echo html($content["tab_title"]) ?>" size="40" maxlength="1500" /></td>
            <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_ctype_number'] ?>:&nbsp;</td>
            <td><input name="ctab_number" type="text" id="ctab_number" class="v11 width25" value="<?php echo $content["tab_number"] ?>" size="3" maxlength="4" onkeyup="if(!parseInt(this.value,10))this.value='';" /></td>
          </tr>
        </table></td>
    </tr>
    <!-- ctab section end -->

    <tr><td colspan="2" class="rowspacer7x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
        <?php
          if(isset($content["error"])) {
        ?>
    <tr bgcolor="#F3F5F8">
        <td align="right" valign="top" bgcolor="#FFE9D2"><strong style="color:#FF6600"><?php echo $BL['be_admin_usr_err'] ?>:&nbsp;</strong></td>
        <td valign="top" bgcolor="#FFE9D2"><strong style="color:#FF6600"><?php
            //Fehlerdarstellung
            $content["error_result"]="";
            foreach($content["error"] as $value) {
                $content["error_result"] .= "> ".$value."\n";
            }
            echo nl2br(html(chop($content["error_result"])));
            unset($content["error_result"]);

        ?></strong></td>
    </tr>
    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
          <?php
          }
          ?>
    <tr bgcolor="#F3F5F8">
      <td align="right" class="chatlist"><?php echo $BL['be_article_cnt_ctitle'] ?>:&nbsp;</td>
      <td><input name="ctitle" type="text" id="ctitle" class="width440p5" value="<?php echo html($content["title"]) ?>" size="40" maxlength="2000" /></td>
    </tr>
    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
    <tr bgcolor="#F3F5F8">
      <td align="right" class="chatlist"><?php echo $BL['be_article_asubtitle'] ?>:&nbsp;</td>
      <td><input name="csubtitle" type="text" id="csubtitle" class="width440p5" value="<?php echo html($content["subtitle"]) ?>" size="40" maxlength="2000" /></td>
    </tr>

<?php

    // check if it is necessary to display paginate stuff
    // in case no content part pagination isset for current article

    $content["paginate_page"] = empty($content["paginate_page"]) ? 0 : intval($content["paginate_page"]);

    if(empty($content['article']['article_paginate']) || $content["block"] === 'SYSTEM') {

        echo '<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="7" />';
        echo '<input name="cpaginate_title" type="hidden" id="cpaginate_title" value="'.html($content["paginate_title"]).'" />';
        echo '<input name="cpaginate_page" type="hidden" id="cpaginate_page" value="'.$content["paginate_page"].'" />';
        echo '</td></tr>';

    } else {

?>

    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
    <tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

    <tr>
      <td align="right" class="chatlist"><?php echo $BL['be_cnt_paginate_subsection'] ?>:&nbsp;</td>
      <td><table summary="" border="0" cellspacing="0" cellpadding="0" width="444">
          <tr>
            <td><input name="cpaginate_page" type="text" id="cpaginate_page" class="v11 width25" value="<?php echo $content["paginate_page"] ?>" size="3" maxlength="3" onkeyup="if(!parseInt(this.value,10))this.value='0';" /></td>
            <td align="right" class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_subsection_tite'].' ('.$BL['be_pagination'].')' ?>:&nbsp;</td>
            <td width="200"><input name="cpaginate_title" type="text" id="cpaginate_title" class="f11b width225" value="<?php echo html($content["paginate_title"]) ?>" size="40" maxlength="2000" /></td>
          </tr>
        </table><script type="text/javascript">

            checkCntBlockPaginate(getObjectById("cblock"));
            loadblock = false;

        </script></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="7" /></td></tr>

<?php

    }
    // end paginate check


// end non content part setting mode
endif;

?>

    <tr><td colspan="2" class="rowspacer"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

    <tr bgcolor="#F3F5F8">
        <td>&nbsp;</td>
        <td style="padding:7px 0 7px 0;">
<?php

// render buttons only once and save the buffer
if(!empty($content["id"])) {
    $buttonActionLink = rel_url(array('phpwcms-preview'=>1), array(), empty($content['article']["article_alias"]) ? (empty($content["aid"]) ? 'id='.$content["id"] : 'aid='.$content["aid"]) : $content['article']["article_alias"]);
    $buttonAction  = '<button type="button" value="'.$BL['be_func_struct_preview'].'" class="button" title="'.$BL['be_func_struct_preview'].'" style="margin-left:50px;" ';
    $buttonAction .= 'onclick="window.open(\''.$buttonActionLink."', 'articlePreviewWindows');return false;\">";
    $buttonAction .= $BL['be_func_struct_preview']."</button>" . LF;
} else {
    $buttonAction  = '';
}


ob_start();

?>
    <input name="Submit" type="submit" class="button" value="<?php echo  $sendbutton ?>" />
    <input name="SubmitClose" type="submit" class="button" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
    &nbsp; &nbsp;
    <input name="donotsubmit" type="button" class="button" value="<?php echo  $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id=<?php echo $content["aid"] ?>'" />
    &nbsp; &nbsp;
    <?php echo $buttonAction; ?>
<?php

$_save_close_buttons = ob_get_clean();

echo $_save_close_buttons;

?>
        </td>
    </tr>

<?php

    // show content part specific form elements
    if($content['type'] != 30 && file_exists(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt'.$content['type'].'.inc.php')) {

        include_once PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt'.$content['type'].'.inc.php';

    } elseif($content['type'] == 30 && file_exists($phpwcms['modules'][$content["module"]]['path'].'inc/cnt.form.php')) {

        include_once $phpwcms['modules'][$content["module"]]['path'].'inc/cnt.form.php';

    } else {

        include_once PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt0.inc.php';

    }

    if(in_array($content['type'], array(32))):
?>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
<?php
    else:
?>
    <tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<?php
    endif;
?>

    <tr>
      <td align="right" class="chatlist"><?php echo $BL['be_article_cnt_space'] ?>:&nbsp;</td>
      <td>
        <table summary="" border="0" cellspacing="0" cellpadding="0" bgcolor="#E7E8EB">
            <tr>
                <td class="v10"><label for="ccb">&nbsp;<?php echo $BL['be_article_cnt_before'] ?></label></td>
                <td><input name="ccb" type="checkbox" id="ccb" value="1" <?php if($content["before"] !== '') echo "checked"; ?> onclick="if(!this.checked){this.form.cbefore.value='';}else if(this.form.cbefore.value===''){this.checked=false;}" /></td>
                <td><input name="cbefore" type="text" id="cbefore" class="width40" value="<?php echo $content["before"] ?>" size="5" maxlength="5" onkeyup="validate_before_after(this, 'ccb');" /></td>
                <td class="chatlist">&nbsp;<?php echo empty($template_default['article']['div_spacer_unit']) ? 'px' : $template_default['article']['div_spacer_unit']; ?>&nbsp;&nbsp;</td>
                <td class="v10"><label for="cca">&nbsp;<?php echo $BL['be_article_cnt_after'] ?></label></td>
                <td><input name="cca" type="checkbox" id="cca" value="1" <?php if($content["after"] !== '') echo "checked"; ?> onclick="if(!this.checked){this.form.cafter.value='';}else if(this.form.cafter.value===''){this.checked=false;}" /></td>
                <td class="tdtop1 tdbottom1"><input name="cafter" type="text" id="cafter" class="width40" value="<?php echo $content["after"] ?>" size="5" maxlength="5" onkeyup="validate_before_after(this, 'cca');" /></td>
                <td class="chatlist">&nbsp;<?php echo empty($template_default['article']['div_spacer_unit']) ? 'px' : $template_default['article']['div_spacer_unit']; ?>&nbsp;</td>
                <td bgcolor="#ffffff">&nbsp;</td>
                <td><label for="ctop">&nbsp;&nbsp;<?php echo $BL['be_article_cnt_toplink'] ?></label></td>
                <td><input name="ctop" type="checkbox" id="ctop" value="1"<?php is_checked(1, $content["top"]); ?> /></td>
                <td>&nbsp;</td>
                <td bgcolor="#ffffff">&nbsp;</td>
                <td><label for="canchor"<?php echo $anchor_title ?>>&nbsp;&nbsp;<?php echo $BL['be_article_cnt_anchor'] ?></label></td>
                <td><input name="canchor" type="checkbox" id="canchor" value="1"<?php is_checked(1, $content["anchor"]); echo $anchor_title ?> /></td>
                <td>&nbsp;&nbsp;</td>
            </tr>
        </table></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

    <tr>
      <td align="right" class="chatlist"><?php echo $BL['be_cnt_attribute_class']; ?>:&nbsp;</td>
      <td>
        <table summary="" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><input name="cattr_class" type="text" value="<?php echo html($content["attr_class"]); ?>" class="width175" maxlength="255" /></td>
                <td class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_cnt_attribute_id']; ?>:&nbsp;</td>
                <td><input name="cattr_id" type="text" value="<?php echo html($content["attr_id"]); ?>" class="width175" maxlength="255" /></td>
            </tr>
        </table></td>
    </tr>

    <tr><td colspan="2" class="rowspacer7x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="7" /></td></tr>

    <tr bgcolor="#F3F5F8">
        <td align="right" class="chatlist">&nbsp;<br /><?php echo $BL['be_article_abegin'] ?>:&nbsp;</td>
        <td>
            <table border="0" cellpadding="2" cellspacing="0" summary="" bgcolor="#E7E8EB">
                <tr>
                    <td class="chatlist">&nbsp;<br />
                        <input name="set_livedate" type="checkbox" id="set_livedate" value="1"<?php is_checked(1, $set_livedate) ?> onclick="document.articlecontent.clivedate.value = this.checked ? '<?php echo $content["livedate"]; ?>' : '';" />
                    </td>
                    <td class="chatlist tdbottom3 nowrap" nowrap="nowrap">YYYY-MM-DD HH:MM:SS<br />
                        <input name="clivedate" type="text" id="clivedate" class="width150" value="<?php echo $content["livedate"]; ?>" />
                    </td>
                    <td class="chatlist tdbottom3">&nbsp;<br />
                        <script type="text/javascript">
                            var currentDateBegin = 0,
                                currentDateEnd = 0;

                            function aBegin(day, month, year) {

                                month = subrstr('00' + month, 2);
                                day = subrstr('00' + day, 2);

                                currentDateBegin = parseInt(year + month + day, 10);

                                if(currentDateEnd > 0 && currentDateBegin > currentDateEnd) {
                                    document.articlecontent.ckilldate.value = '';
                                    document.articlecontent.set_killdate.checked = false;
                                }

                                document.articlecontent.clivedate.value = year + '-' + subrstr('00' + month, 2) + '-' + subrstr('00' + day, 2) + ' 00:00:00';
                                document.articlecontent.set_livedate.checked = true;
                            }

                            calBegin = new dynCalendar('calBegin', 'aBegin', 'img/dynCal/');
                            calBegin.setMonthCombo(false);
                            calBegin.setYearCombo(false);

                        </script>&nbsp;
                    </td>
                    <td align="right" class="chatlist" bgcolor="#F3F5F8">&nbsp;<br />&nbsp;&nbsp;<?php echo $BL['be_article_aend'] ?>:</td>
                    <td class="chatlist">&nbsp;<br />
                        <input name="set_killdate" type="checkbox" id="set_killdate" value="1"<?php is_checked(1, $set_killdate) ?> onclick="document.articlecontent.ckilldate.value = this.checked ? '<?php echo $content["killdate"] ?>' : '';" />
                    </td>
                    <td class="chatlist tdbottom3 nowrap" nowrap="nowrap">YYYY-MM-DD HH:MM:SS<br />
                        <input name="ckilldate" type="text" id="ckilldate" class="width150" value="<?php echo $content["killdate"]; ?>" />
                    </td>
                    <td class="chatlist tdbottom3">&nbsp;<br />
                        <script type="text/javascript">

                            function aEnd(day, month, year) {

                                month = subrstr('00' + month, 2);
                                day = subrstr('00' + day, 2);

                                currentDateEnd = parseInt(year + month + day, 10);

                                if(currentDateBegin > 0 && currentDateBegin > currentDateEnd) {
                                    document.articlecontent.clivedate.value = '';
                                    document.articlecontent.set_livedate.checked = false;
                                }

                                document.articlecontent.ckilldate.value = year + '-' + month + '-' + day + ' 23:59:59';
                                document.articlecontent.set_killdate.checked = true;
                            }

                            calEnd = new dynCalendar('calEnd', 'aEnd', 'img/dynCal/');
                            calEnd.setMonthCombo(false);
                            calEnd.setYearCombo(false);

                        </script>&nbsp;
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

    <tr bgcolor="#F3F5F8">
        <td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
        <td><table summary="" border="0" cellpadding="1" cellspacing="0" bgcolor="#E7E8EB">
            <tr>

                <td><input name="cvisible" type="checkbox" id="cvisible" value="1"<?php is_checked(1, $content["visible"]); ?> /></td>
                <td><label for="cvisible"><?php echo $BL['be_admin_struct_visible'] ?>&nbsp;&nbsp;</label>&nbsp;</td>

                <td>
                    <select name="cgranted" id="cgranted" class="v11">
                        <option value="0"<?php is_selected(0, $content["granted"]); ?>><?php echo $BL['be_visible_for_everybody'] ?></option>
                        <option value="1"<?php is_selected(1, $content["granted"]); ?>><?php echo $BL['be_granted_feuser'] ?></option>
                        <option value="2"<?php is_selected(2, $content["granted"]); ?>><?php echo $BL['be_hidden_for_feuser'] ?></option>
                    </select>
                </td>

                <td class="chatlist" align="right">&nbsp;&nbsp;<?php echo $BL['be_cnt_sortvalue'] ?>:&nbsp;</td>
                <td><input name="csorting" type="text" id="csorting" value="<?php echo $content["sorting"] ?>" class="width40" maxlength="10" onkeyup="if(!parseInt(this.value,10))this.value='0';" /></td>

            </tr>
        </table></td>
    </tr>

    <tr bgcolor="#F3F5F8">
        <td>&nbsp;</td>
        <td class="tdtop10 tdbottom10">
            <input name="caktion" type="hidden" id="caktion" value="1" />
            <input name="caid" type="hidden" id="caid" value="<?php echo $article["article_id"] ?>" />
            <input name="cid" type="hidden" id="cid" value="<?php echo  $content["id"] ?>" />
            <input name="ctype" type="hidden" id="ctype" value="<?php echo  $content["type"] ?>" />
            <?php echo $_save_close_buttons ?>
        </td>
    </tr>

    <tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

    <tr>
      <td align="right" class="chatlist tdtop3"><?php echo $BL['be_profile_label_notes'] ?>:&nbsp;</td>
      <td><textarea name="ccomment" id="ccomment" class="width440 autosize" rows="5"><?php echo html($content["comment"]) ?></textarea></td>
    </tr>

</table>
</form>