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

$acat_struct_mode   = 'STRUCT';
$acat_lang_mode     = $_GET['struct'] !== 'index' && count($phpwcms['allowed_lang']) > 1;

if($_GET['struct'] === 'index') {

    $acat_title         = $indexpage['acat_name'];
    $acat_title_alt     = $indexpage['acat_title'];
    $acat_info          = $indexpage['acat_info'];
    $acat_id            = 'index';
    $acat_new           = 0;
    $acat_aktiv         = $indexpage['acat_aktiv'];
    $acat_sort          = isset($acat_sort) ? $acat_sort : '';
    $acat_alias         = $indexpage['acat_alias'];
    $acat_hidden        = $indexpage['acat_hidden'];
    $acat_template      = $indexpage['acat_template'];
    $acat_ssl           = $indexpage['acat_ssl'];
    $acat_regonly       = $indexpage['acat_regonly'];
    $acat_topcount      = $indexpage['acat_topcount'];
    $acat_maxlist       = $indexpage['acat_maxlist'];
    $acat_redirect      = $indexpage['acat_redirect'];
    $acat_timeout       = strval($indexpage['acat_timeout']);
    $acat_nosearch      = strval($indexpage['acat_nosearch']);
    $acat_nositemap     = strval($indexpage['acat_nositemap']);
    $acat_order         = get_order_sort($indexpage['acat_order']);
    $acat_permit        = empty($indexpage['acat_permit']) ? array() : explode(',', $indexpage['acat_permit']) ;
    $acat_cntpart       = (isset($indexpage['acat_cntpart']) && $indexpage['acat_cntpart'] != '') ? explode(',', $indexpage['acat_cntpart']) : array();
    $acat_pagetitle     = empty($indexpage['acat_pagetitle']) ? '' : $indexpage['acat_pagetitle'];
    $acat_paginate      = empty($indexpage['acat_paginate']) ? 0 : 1;
    $acat_overwrite     = empty($indexpage['acat_overwrite']) ? '' : $indexpage['acat_overwrite'];
    $acat_archive       = empty($indexpage['acat_archive']) ? 0 : $indexpage['acat_archive'];
    $acat_class         = empty($indexpage['acat_class']) ? '' : $indexpage['acat_class'];
    $acat_keywords      = empty($indexpage['acat_keywords']) ? '' : $indexpage['acat_keywords'];
    $acat_cpdefault     = empty($indexpage['acat_cpdefault']) ? (empty($phpwcms['cp_default']) ? 0 : intval($phpwcms['cp_default'])) : intval($indexpage['acat_cpdefault']);
    $acat_lang          = '';
    $acat_lang_type     = '';
    $acat_lang_id       = 0;
    $acat_disable301    = empty($indexpage['acat_disable301']) ? 0 : 1;
    $acat_opengraph     = isset($indexpage['acat_opengraph']) ? $indexpage['acat_opengraph'] : 1;
    $acat_canonical     = empty($indexpage['acat_canonical']) ? '' : $indexpage['acat_canonical'];
    $acat_breadcrumb    = empty($indexpage['acat_breadcrumb']) ? 0 : intval($indexpage['acat_breadcrumb']);
    $acat_onepage       = empty($indexpage['acat_onepage']) ? 0 : 1;

    $acat_struct_mode = 'INDEX';

} elseif(!isset($acat_title)) {

    $parentStructData   = getParentStructArray($_GET["struct"]);

    $acat_title         = '';
    $acat_title_alt     = '';
    $acat_info          = '';
    $acat_aktiv         = $phpwcms['set_category_active'];
    $acat_sort          = isset($acat_sort) ? $acat_sort : '';
    $acat_alias         = '';
    $acat_hidden        = 0;
    $acat_hiddenactive  = 0;
    $acat_template      = $parentStructData['acat_template'];
    $acat_ssl           = 0;
    $acat_regonly       = 0;
    $acat_redirect      = '';
    $acat_nositemap     = 1;
    $acat_maxlist       = 0;
    $acat_permit        = array();
    $acat_cntpart       = array();
    $acat_pagetitle     = '';
    $acat_paginate      = 0;
    $acat_overwrite     = '';
    $acat_archive       = 0;
    $acat_class         = '';
    $acat_keywords      = '';
    $acat_cpdefault     = empty($phpwcms['cp_default']) ? 0 : intval($phpwcms['cp_default']);
    $acat_lang          = '';
    $acat_lang_type     = '';
    $acat_lang_id       = 0;
    $acat_disable301    = 0;
    $acat_opengraph     = empty($phpwcms['set_sociallink']['articlecat']) ? 0 : 1;
    $acat_canonical     = '';
    $acat_breadcrumb    = 0;
    $acat_onepage       = 0;

}

switch($acat_hidden) {

    case 1:     $acat_hidden        = 1;
                $acat_hiddenactive  = 0;
                break;

    case 2:     $acat_hidden        = 1;
                $acat_hiddenactive  = 1;
                break;

    default:    $acat_hidden        = 0;
                $acat_hiddenactive  = 0;

}

?>
<form action="include/inc_act/act_structure.php" method="post" name="editsitestructure" id="editsitestructure" onsubmit="selectAllOptions(this.acat_access);selectAllOptions(this.acat_cp);var x = wordcount(this.acat_name.value);if(x&lt;1) {alert('Fill in a category title! \n\n('+x+' words total)');this.acat_name.focus();return false;}">
    <table width="538" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" summary="">

          <tr><td width="538" class="title"><?php echo $BL['be_admin_struct_title'] ?> <span style="font-weight: normal;"><?php echo $BL['be_admin_struct_child'] ?></span>: <strong style="color: #FF3300"><?php
            //Anzeigen des Kategorienamens (Men�punkt)
            $acat_struct = intval($_GET["struct"]);
            if($acat_struct) {

                $parentStructData = getParentStructArray($acat_struct);
                echo html($parentStructData["acat_name"]);

            } else {
                echo $BL['be_admin_struct_index'];
                $parentStructData = array("acat_name" => $BL['be_admin_struct_index']);
            }

            $acat_struct_alias = get_struct_alias($acat_struct);
            $acat_parent_alias = get_struct_alias($acat_struct, true);
            if(empty($acat_struct_alias) && $acat_id != 'index') {
                $acat_struct_alias = $parentStructData["acat_name"];
            }

          ?></strong></td>
          </tr>
          <tr><td><img src="img/leer.gif" width="1" height="4" alt="" /></td></tr>
          <tr><td><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

          <tr><td class="v09"><?php echo $BL['be_admin_struct_cat'] ?>:</td></tr>
          <tr><td><input name="acat_name" type="text" id="acat_name" class="bold width540" onchange="this.value=Trim(this.value);" value="<?php echo html($acat_title) ?>" size="50" maxlength="2000" /></td></tr>

          <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

          <tr><td class="v09"><?php echo $BL['be_admin_struct_alt'] ?>:</td></tr>
          <tr><td><input name="acat_title" type="text" id="acat_title" class="bold width540" value="<?php echo html($acat_title_alt) ?>" size="50" maxlength="2000" /></td></tr>

          <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

          <tr>
               <td class="v09">
                <a href="#" onclick="return set_article_alias(false, 'struct');"><?php echo $BL['be_admin_struct_alias']; ?></a>,
                +<a href="#" onclick="return set_article_alias(false, 'struct', '<?php echo $acat_parent_alias ?>');" title="<?php echo $acat_parent_alias ?>"><?php echo $BL['be_parental_alias']; ?></a>,
                +<a href="#" onclick="return set_article_alias(false, 'struct', '<?php echo $acat_struct_alias ?>');" title="<?php echo $acat_struct_alias ?>"><?php echo $BL['be_admin_struct_title']; ?></a>:
            </td></tr>
          <tr><td>
              <input name="acat_alias" type="text" id="acat_alias" class="bold width540" value="<?php echo html($acat_alias) ?>" size="50" maxlength="1000"<?php
                if(empty($phpwcms['allow_empty_alias'])): ?> onfocus="set_article_alias(true, 'struct');"<?php endif; ?> onchange="this.value=create_alias(this.value);" />
          </td></tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
          <tr><td><table border="0" cellpadding="0" cellspacing="0" summary="">
              <tr>
                <td bgcolor="#D9DEE3"><input name="acat_onepage" type="checkbox" id="acat_onepage" value="1"<?php if(!empty($acat_onepage)) { echo ' checked="checked"';} ?> /></td>
                <td bgcolor="#D9DEE3"><label for="acat_onepage">&nbsp;<?php echo $BL['be_onepage_id']; ?>&nbsp;</label></td>
              </tr>
          </table></td></tr>

<?php   if($acat_lang_mode):

            $lang_default   = ' ('.$BL['be_admin_tmpl_default'].')';

?>

        <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

        <tr><td class="v09"><?php echo $BL['be_profile_label_lang'] ?>:</td></tr>
          <tr><td class="tdtop2">

            <div style="margin:0;border:1px solid #D9DEE3;padding:5px;float:left;" class="lang-select">
            <table border="0" cellpadding="0" cellspacing="0" summary="">
                <tr>

                <td>
                    <label><input type="radio" name="acat_lang" class="lang-default" value=""<?php is_checked('', $acat_lang); ?> />
                        <img src="img/famfamfam/lang/<?php echo $phpwcms['default_lang'] ?>.png" title="<?php echo get_language_name($phpwcms['default_lang']) . $lang_default ?>" /><?php echo $lang_default ?>
                        &nbsp;
                    </label>
                </td>


<?php       foreach($phpwcms['allowed_lang'] as $key => $lang):

                $lang = strtolower($lang);

                if($lang == $phpwcms['default_lang']) {
                    continue;
                }

?>
                    <td><label><input type="radio" name="acat_lang" class="lang-opt" value="<?php echo $lang ?>"<?php is_checked($lang, $acat_lang); ?> />
                            <img src="img/famfamfam/lang/<?php echo $lang ?>.png" title="<?php echo get_language_name($lang) ?>" />
                            &nbsp;
                        </label>
                    </td>

<?php       endforeach; ?>

                </tr>

            </table>
                <div style="margin:5px 0 0 0;border-top:1px solid #D9DEE3;padding-top:5px;<?php if($acat_lang == ''): ?>display:none;<?php endif; ?>" id="lang-id-select">
                    <label><input type="radio" name="acat_lang_type" value="category"<?php is_checked('category', $acat_lang_type); ?> /> <?php echo $BL['be_article_cat'] ?> ID</label>
                    &nbsp;
                    <label><input type="radio" name="acat_lang_type" value="article"<?php is_checked('article', $acat_lang_type); ?> /><?php echo $BL['be_cnt_articles'] ?> ID</label>
                    &nbsp;
                    <img src="img/famfamfam/lang/<?php echo $phpwcms['default_lang'] ?>.png" title="<?php echo get_language_name($phpwcms['default_lang']) . $lang_default ?>" />&nbsp;
                    <input name="acat_lang_id" type="text" class="bold width75" value="<?php echo $acat_lang_id ? $acat_lang_id : ''; ?>" size="11" maxlength="11" />
                </div>

            </div>

          </td></tr>


<?php   endif;  ?>

          <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

          <tr><td class="v09"><?php echo $BL['be_admin_page_pagetitle'] ?>:</td></tr>
          <tr><td><input name="acat_pagetitle" type="text" id="acat_pagetitle" class="width540" value="<?php echo html($acat_pagetitle) ?>" size="50" maxlength="2000" /></td></tr>

            <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

          <tr><td class="v09"><?php echo $BL['be_cnt_css_class'] ?>:</td></tr>
         <tr>
            <td><input name="acat_class" type="text" id="acat_class" class="width540" value="<?php echo html($acat_class) ?>" size="50" maxlength="255" /></td>
        </tr>

          <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

          <tr><td class="v09"><?php echo $BL['be_article_aredirect'] ?>:</td></tr>
          <tr><td><input name="acat_redirect" type="text" id="acat_redirect" class="width540" value="<?php echo html($acat_redirect) ?>" size="50" maxlength="255" /></td></tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

          <tr><td class="v09"><?php echo $BL['be_canonical'] ?>:</td></tr>
          <tr><td><input name="acat_canonical" type="text" id="acat_canonical" class="width540" value="<?php echo html($acat_canonical) ?>" size="50" maxlength="2000" /></td></tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

        <tr><td class="v09"><?php echo $BL['be_article_akeywords'] ?>:</td></tr>
          <tr><td><textarea name="acat_keywords" cols="50" rows="3" id="acat_keywords" class="width540 autosize"><?php echo html($acat_keywords) ?></textarea></td></tr>

        <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

          <tr><td class="v09"><?php echo $BL['be_admin_struct_info'] ?>:</td></tr>
          <tr><td><textarea name="acat_info" cols="50" rows="4" id="acat_info" class="width540 autosize"><?php echo html($acat_info) ?></textarea></td></tr>

          <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
          <tr><td class="v09"><?php echo $BL['be_admin_struct_template'] ?>:</td></tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
          <tr>
            <td><select name="acat_template" id="acat_template" class="width300">
<?php

$_temp_cat = '';

// list available
$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_template WHERE template_trash=0 ORDER BY template_default DESC";
$result = _dbQuery($sql);
if(isset($result[0]['template_id'])) {
    foreach($result as $row) {
        echo "<option value=\"".$row["template_id"]."\"";
        if($row["template_id"] == $acat_template) {
            echo " selected";
            $_temp_cat = @unserialize($row['template_var']);
            $_temp_cat = empty($_temp_cat['overwrite']) ? '' : $_temp_cat['overwrite'];
        }
        echo ">".html($row["template_name"]);
        if($row["template_default"]) {
            echo ' (', $BL['be_admin_tmpl_default'], ')';
        }
        echo "</option>\n";
    }
}

?>
            </select></td>
          </tr>



          <tr><td><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
          <tr><td class="v09"><?php echo $BL['be_settings'] ?>:&nbsp;<i><?php echo $BL['be_overwrite_default'] ?></i></td></tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
          <tr>
            <td><select name="acat_overwrite" id="acat_overwrite" class="width300">
            <option value="" style="font-weight:normal;font-style:italic;"><?php echo $BL['be_admin_tmpl_default']; ?></option>
<?php

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_settings/template_default', 'php');
if(is_array($tmpllist) && count($tmpllist)) {
    foreach($tmpllist as $val) {
        $selected_val = (isset($acat_overwrite) && $val == $acat_overwrite) ? ' selected="selected"' : '';
        $val = html($val);
        echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . ($_temp_cat==$val ? ' ('.$BL['be_admin_struct_template'].')' : '') . '</option>' . LF;
    }
}

?>
        </select></td>
          </tr>

          <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

    <tr>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
         <tr>
            <td class="v09"><?php echo  $BL['be_admin_struct_topcount'] ?>:</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td class="v09" colspan="2"><?php echo  $BL['be_pagination'] ?>:</td>

            <td>&nbsp;&nbsp;</td>
            <td class="v09"><?php echo $BL['be_article_per_page'] ?>:</td>

<?php if($acat_struct_mode != 'INDEX'): ?>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td class="v09"><?php echo $BL['be_cnt_sortvalue'] ?>:</td>
<?php endif; ?>

          </tr>

          <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

          <tr>
            <td><input name="acat_topcount" type="text" id="acat_topcount" class="width125" value="<?php echo  intval($acat_topcount) ?>" size="10" maxlength="10" /></td>
            <td>&nbsp;</td>

            <td bgcolor="#D9DEE3"><input name="acat_paginate" type="checkbox" id="acat_paginate" value="1" <?php if($acat_paginate == 1) echo "checked"; ?> /></td>
            <td bgcolor="#D9DEE3">&nbsp;<label for="acat_paginate"><?php echo $BL['be_article_pagination'] ?></label>&nbsp;&nbsp;</td>

            <td>&nbsp;</td>
            <td><input name="acat_maxlist" type="text" id="acat_maxlist" class="width75" value="<?php echo empty($acat_maxlist) ? '' : intval($acat_maxlist); ?>" size="10" maxlength="10" /></td>

<?php if($acat_struct_mode != 'INDEX'): ?>
            <td>&nbsp;</td>
            <td><input name="acat_sort" type="text" id="acat_sort" class="width75" value="<?php echo $acat_sort; ?>" size="11" maxlength="11" /></td>
<?php endif; ?>

          </tr>
          </table></td>
    </tr>

          <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

          <tr><td class="v09"><?php echo  $BL['be_admin_struct_orderarticle'] ?>:</td></tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="2" /></td></tr>
          <tr>
            <td valign="top">
            <div style="margin:0;border:1px solid #D9DEE3;padding:5px;float:left;">
            <table border="0" cellpadding="0" cellspacing="0" summary=""><!-- seems not neccessary anymore: onclick="noDESC()" -->
                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order0" value="0"<?php is_checked(0, intval($acat_order[0])) ?> /></td>
                  <td>&nbsp;<label for="acat_order0"><?php echo  $BL['be_admin_struct_ordermanual'] ?></label>&nbsp;&nbsp;</td>
                  <td rowspan="6">&nbsp;</td>
                  <td><input type="radio" name="acat_ordersort" id="acat_ordersort0" value="0"<?php is_checked(0, intval($acat_order[1])) ?> /></td>
                  <td>&nbsp;<label for="acat_ordersort0"><?php echo  $BL['be_admin_struct_orderasc'] ?></label>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order1" value="2"<?php is_checked(2, $acat_order[0]) ?> /></td>
                  <td>&nbsp;<label for="acat_order1"><?php echo  $BL['be_admin_struct_orderdate'] ?></label>&nbsp;&nbsp;</td>
                  <td><input type="radio" name="acat_ordersort" id="acat_ordersort1" value="1"<?php is_checked(1, $acat_order[1]) ?> /></td>
                  <td>&nbsp;<label for="acat_ordersort1"><?php echo  $BL['be_admin_struct_orderdesc'] ?></label>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order2" value="4"<?php is_checked(4, $acat_order[0]) ?> /></td>
                  <td>&nbsp;<label for="acat_order2"><?php echo  $BL['be_admin_struct_orderchangedate'] ?></label>&nbsp;&nbsp;</td>
                  <td colspan="2" rowspan="4">&nbsp;</td>
                </tr>
                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order3" value="6"<?php is_checked(6, $acat_order[0]) ?> /></td>
                  <td>&nbsp;<label for="acat_order3"><?php echo  $BL['be_admin_struct_orderstartdate'] ?></label>&nbsp;&nbsp;</td>
                </tr>

                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order5" value="10"<?php is_checked(10, $acat_order[0]) ?> /></td>
                  <td>&nbsp;<label for="acat_order5"><?php echo  $BL['be_admin_struct_orderkilldate'] ?></label>&nbsp;&nbsp;</td>
                </tr>

                <tr>
                  <td><input type="radio" name="acat_order" id="acat_order4" value="8"<?php is_checked(8, $acat_order[0]) ?> /></td>
                  <td>&nbsp;<label for="acat_order4"><?php echo  $BL['be_article_atitle'] ?></label>&nbsp;&nbsp;</td>
                </tr>
            </table>
            </div>
            </td>
          </tr>


          <tr><td><img src="img/leer.gif" alt="" width="1" height="7" /></td></tr>

<?php   if(!empty($phpwcms['usergroup_support'])): ?>

        <!-- enym group selector -->
        <tr><td><img src="img/leer.gif" width="1" height="10"></td></tr>
        <tr><td class="v09"><?php echo $BL['be_cnt_access']; ?> (<?php echo $BL['be_subnav_admin_groups']; ?>):</td></tr>
        <tr><td><img src="img/leer.gif" width="1" height="2"></td></tr>
        <tr>
            <td valign="top"><?php

        // list all available groups and put into temp array
        $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_usergroup WHERE group_active != 9 ORDER BY group_id DESC";
        $result = _dbQuery($sql);
        $_temp_group = array();
        if(isset($result[0]['group_name'])) {
            foreach($result as $row) {
                $_temp_group[$row['group_id']]['name']   = html($row['group_name']);
                $_temp_group[$row['group_id']]['active'] = $row['group_active'];
            }
        }

        ?><table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><select name="acat_access[]" id="acat_access" size="7"
                        onDblClick="moveSelectedOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers,true);"
                        multiple="multiple" style="width: 255px" class="f10">
        <?php

            if(count($_temp_group)) {
                // list groups that have rights in here! acat_permit used for groups too
                foreach($_temp_group as $key => $value) {
                    if(in_array($key, $acat_permit)) {
                        echo '<option value="'.$key.'"';
                        if(empty($_temp_group[$key]['active'])) {
                            echo ' style="color:#999999;"';
                        }
                        echo '>'.html($_temp_group[$key]['name'])."</option>";
                        unset($_temp_group[$key]);
                    }
                }
            }

        ?></select></td>
                <td valign="top" style="padding-left:5px;padding-right:5px;">
<img src="img/button/put_left.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_adduser_all']?>" onClick="moveAllOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access);selectAllOptions(document.editsitestructure.acat_access);"><br />
<img src="img/leer.gif" width="1" height="3" /><br />
<img src="img/button/put_left_a.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_adduser_this']?>" onClick="moveSelectedOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access,true);selectAllOptions(document.editsitestructure.acat_access);"><br />
<img src="img/leer.gif" width="1" height="6" /><br />
<img src="img/button/put_right_a.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_remove_this']?>" onClick="moveSelectedOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers,true);" /><br />
<img src="img/leer.gif" width="1" height="3"><br />
<img src="img/button/put_right.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_remove_all']?>" onClick="moveAllOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers);" />
                </td>
                <td><select name="acat_feusers" size="7" id="acat_feusers"
                            onDblClick="moveSelectedOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access,true);selectAllOptions(document.editsitestructure.acat_access);"
                            style="width: 255px" class="f10" multiple="multiple">
        <?php

            if(count($_temp_group)) {
                // list all available groups
                foreach($_temp_group as $key => $value) {
                    echo '<option value="'.$key.'"';
                    if(empty($_temp_group[$key]['active'])) {
                        echo ' style="color:#999999;"';
                    }
                    echo '>'.html($_temp_group[$key]['name'])."</option>\n";
                }
            }
        ?>
                    </select></td>
                </tr>
            </table></td>
            </tr>

            <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
            <!-- enym end new add group selector-->

<?php   endif; ?>


<!-- Content Part Selection -->

          <tr><td><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>
                <td class="v09" style="width:280px;"><?php echo $BL['be_structform_selected_cp'] ?>:</td>
                <td class="v09"><?php echo $BL['be_admin_tmpl_default'] ?>:&nbsp;</td>
                <td style="padding:2px 0;"><select name="acat_cpdefault">
<?php
foreach($wcs_content_type as $key => $value) {
    echo '<option value="'.$key.'"'.is_selected($acat_cpdefault, $key,1, 0).'>'.$value."</option>\n";
}

?>
                </select></td>
            </tr>
            </table></td></tr>
          <tr><td><img src="img/leer.gif" width="1" height="2" alt="" /></td></tr>
          <tr>
            <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
              <tr>
                <td><select name="acat_cp[]" id="acat_cp" size="9" multiple="multiple" class="width250" ondblclick="moveSelectedOptions(document.editsitestructure.acat_cp,document.editsitestructure.acat_cpa,false);">

<?php

// check which content part is available
$temp_count = 0;
foreach($acat_cntpart as $value) {
    if(isset($wcs_content_type[$value])) {
        echo '<option value="'.$value.'">'.$wcs_content_type[$value]."</option>\n";
        unset($wcs_content_type[$value]);
    }
    $value1 = $value * (-1);
    if(isset($BL['be_admin_optgroup_label'][$value1])) {
        echo '<option value="'.$value.'">[optgroup] '.$BL['be_admin_optgroup_label'][$value1]."</option>\n";
        unset($BL['be_admin_optgroup_label'][$value1]);
    }
}

?>
                        </select></td>
<td valign="top" style="padding-left:5px;padding-right:5px;">
<img src="img/button/put_left.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_adduser_all']?>" onclick="moveAllOptions(document.editsitestructure.acat_cpa,document.editsitestructure.acat_cp);" alt="" /><br />
<img src="img/leer.gif" width="1" height="3" alt="" /><br />
<img src="img/button/put_left_a.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_adduser_this']?>" onclick="moveSelectedOptions(document.editsitestructure.acat_cpa,document.editsitestructure.acat_cp,false);" alt="" /><br />
<img src="img/leer.gif" width="1" height="6" alt="" /><br />
<img src="img/button/put_right_a.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_remove_this']?>" onclick="moveSelectedOptions(document.editsitestructure.acat_cp,document.editsitestructure.acat_cpa,false);" alt="" /><br />
<img src="img/leer.gif" width="1" height="3" alt="" /><br />
<img src="img/button/put_right.gif" width="15" height="15" title="<?php echo $BL['be_admin_struct_remove_all']?>" alt="" onclick="moveAllOptions(document.editsitestructure.acat_cp,document.editsitestructure.acat_cpa);" /><br />
<img src="img/leer.gif" alt="" width="1" height="6" /><br />
<img src="img/button/list_pos_up.gif" alt="" width="15" height="15" border="0" onclick="moveOptionUp(document.editsitestructure.acat_cp);" /><br />
<img src="img/leer.gif" width="1" height="3" alt="" /><br />
<img src="img/button/list_pos_down.gif" alt="" width="15" height="15" border="0" onclick="moveOptionDown(document.editsitestructure.acat_cp);" /></td>
<td><select name="acat_cpa" size="9" multiple="multiple" id="acat_cpa" class="width225" ondblclick="moveSelectedOptions(document.editsitestructure.acat_cpa,document.editsitestructure.acat_cp,false);">

<?php
//Men� mit Content Typen erstellen
foreach($wcs_content_type as $key => $value) {
    //echo getContentPartOptionTag($key, $value);
    echo '<option value="'.$key.'">'.$value."</option>\n";
}
foreach($BL['be_admin_optgroup_label'] as $key => $value) {
    echo '<option value="-'.$key.'">[optgroup] '.$value."</option>\n";
}
?>

                        </select></td>
              </tr>
            </table></td>
          </tr>


          <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
          <tr><td><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr><td colspan="7" class="v09"><?php echo $BL['be_admin_struct_status'] ?>:</td></tr>

            <tr><td colspan="7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

            <tr>
              <td bgcolor="#D9DEE3"><input name="acat_hidden" type="checkbox" id="acat_hidden" value="1" <?php is_checked($acat_hidden, 1); ?> /></td>
              <td bgcolor="#D9DEE3"><label for="acat_hidden">&nbsp;<?php echo $BL['be_admin_struct_hide1'] ?></label>&nbsp;&nbsp;</td>

              <td bgcolor="#D9DEE3"><input name="acat_hiddenactive" type="checkbox" id="acat_hiddenactive" value="1" <?php is_checked($acat_hiddenactive, 1); ?> /></td>
              <td bgcolor="#D9DEE3"><label for="acat_hiddenactive">&nbsp;<?php echo $BL['be_admin_struct_acat_hiddenactive'] ?></label>&nbsp;&nbsp;</td>

              <td>&nbsp;&nbsp;</td>

              <td bgcolor="#D9DEE3"><input name="acat_regonly" type="checkbox" id="acat_regonly" value="1" <?php is_checked($acat_regonly, 1); ?> /></td>
              <td bgcolor="#D9DEE3" colspan="3"><label for="acat_regonly">&nbsp;<?php echo $BL['be_admin_struct_regonly'] ?></label>&nbsp;&nbsp;</td>
            </tr>

            <tr><td colspan="7"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
          </table>

<?php
    // Breadcrumb options
    $acat_breadcrumb_default = 0;
    $acat_breadcrumb_default_checked = 1;
    $acat_breadcrumb_nothidden = 1;
    $acat_breadcrumb_nothidden_checked = 0;
    $acat_breadcrumb_nolink = 2;
    $acat_breadcrumb_nolink_checked = 0;

    if($acat_breadcrumb) {
        $acat_breadcrumb = intval($acat_breadcrumb);
        $acat_breadcrumb_default_checked = 0;
        if($acat_breadcrumb === 1) {
            $acat_breadcrumb_nothidden_checked = 1;
        } elseif($acat_breadcrumb === 2) {
            $acat_breadcrumb_nolink_checked = 1;
        } else {
            $acat_breadcrumb_nothidden_checked = 1;
            $acat_breadcrumb_nolink_checked = 1;
        }
    }

?>
        <table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr><td colspan="6" class="v09"><?php echo $BL['be_breadcrumb'] ?>:</td></tr>
            <tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
            <tr>
              <td bgcolor="#D9DEE3"><input name="acat_breadcrumb_default" type="checkbox" id="acat_breadcrumb_default" value="0" <?php is_checked($acat_breadcrumb_default_checked, 1); ?> /></td>
              <td bgcolor="#D9DEE3"><label for="acat_breadcrumb_default">&nbsp;<?php echo $BL['be_admin_tmpl_default'] ?></label>&nbsp;&nbsp;</td>

              <td bgcolor="#D9DEE3"><input name="acat_breadcrumb_nothidden" type="checkbox" id="acat_breadcrumb_nothidden" value="1" <?php is_checked($acat_breadcrumb_nothidden_checked, 1); ?> /></td>
              <td bgcolor="#D9DEE3"><label for="acat_breadcrumb_nothidden">&nbsp;<?php echo $BL['be_breadcrumb_nothidden'] ?></label>&nbsp;&nbsp;</td>

              <td bgcolor="#D9DEE3"><input name="acat_breadcrumb_nolink" type="checkbox" id="acat_breadcrumb_nolink" value="2" <?php is_checked($acat_breadcrumb_nolink_checked, 1); ?> /></td>
              <td bgcolor="#D9DEE3" colspan="3"><label for="acat_breadcrumb_nolink">&nbsp;<?php echo $BL['be_breadcrumb_nolink'] ?></label>&nbsp;&nbsp;</td>
            </tr>
          </table></td></tr>

         <tr><td><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

          <tr>
            <td><table border="0" cellpadding="0" cellspacing="0" summary="">

                <tr>
                  <td class="v09 inactive" colspan="5"><?php echo  $BL['be_cache'] ?>:</td>
                  <td class="v09" colspan="3"><?php echo  $BL['be_ctype_search'] ?>:</td>
                  <?php if(!empty($phpwcms['force301_2struct'])): ?><td class="v09" colspan="3"><?php echo  $BL['be_acat_disable301'] ?>:</td><?php endif; ?>
                </tr>
                <tr><td colspan="7"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
                </tr>


                <tr bgcolor="#D9DEE3">
                  <td class="inactive"><input name="acat_cacheoff" type="checkbox" id="acat_cacheoff" value="1"<?php if($acat_timeout === '0') echo "checked"; ?> /></td>
                  <td class="inactive"><label for="acat_cacheoff">&nbsp;<?php echo $BL['be_off'] ?></label>&nbsp;</td>
                  <td class="inactive"><select name="acat_timeout" class="width85" style="margin:2px;" onchange="document.editsitestructure.acat_cacheoff.checked=false;">
<?php
echo '<option value=" ">'.$BL['be_admin_tmpl_default']."</option>\n";
echo '<option value="60"'.is_selected($acat_timeout, '60', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_minute']."</option>\n";
echo '<option value="300"'.is_selected($acat_timeout, '300', 0, 0).'>&nbsp;&nbsp;5 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="900"'.is_selected($acat_timeout, '900', 0, 0).'>15 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="1800"'.is_selected($acat_timeout, '1800', 0, 0).'>30 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="3600"'.is_selected($acat_timeout, '3600', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_hour']."</option>\n";
echo '<option value="14400"'.is_selected($acat_timeout, '14400', 0, 0).'>&nbsp;&nbsp;4 '.$BL['be_date_hours']."</option>\n";
echo '<option value="43200"'.is_selected($acat_timeout, '43200', 0, 0).'>12 '.$BL['be_date_hours']."</option>\n";
echo '<option value="86400"'.is_selected($acat_timeout, '86400', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_day']."</option>\n";
echo '<option value="172800"'.is_selected($acat_timeout, '172800', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_days']."</option>\n";
echo '<option value="604800"'.is_selected($acat_timeout, '604800', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_week']."</option>\n";
echo '<option value="1209600"'.is_selected($acat_timeout, '1209600', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_weeks']."</option>\n";
echo '<option value="2592000"'.is_selected($acat_timeout, '2592000', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_month']."</option>\n";
?>
                  </select></td>
                  <td class="inactive">&nbsp;<?php echo $BL['be_cache_timeout'] ?>&nbsp;&nbsp;</td>
                  <td bgcolor="#FFFFFF">&nbsp;&nbsp;</td>
                  <td><input name="acat_nosearch" type="checkbox" id="acat_nosearch" value="1" <?php if($acat_nosearch === '1') echo 'checked="checked"'; ?> /></td>
                  <td><label for="acat_nosearch">&nbsp;<?php echo $BL['be_off'] ?></label>&nbsp;&nbsp;</td>
                  <td bgcolor="#FFFFFF">&nbsp;&nbsp;<?php if(empty($phpwcms['force301_2struct'])): ?><input type="hidden" name="acat_disable301" value="<?php echo $acat_disable301 ?>" /><?php endif; ?></td>
                <?php if(!empty($phpwcms['force301_2struct'])): ?>
                  <td><input name="acat_disable301" type="checkbox" id="acat_disable301" value="1" <?php if($acat_disable301) echo 'checked="checked"'; ?> /></td>
                  <td><label for="acat_disable301">&nbsp;<?php echo $BL['be_off'] ?></label>&nbsp;&nbsp;</td>
                  <td bgcolor="#FFFFFF" width="90%">&nbsp;&nbsp;</td>
                 <?php endif; ?>
                </tr>
              </table></td>
          </tr>

          <tr><td class="v09 tdbottom1 tdtop10"><?php echo  $BL['be_ftptakeover_status'] ?>:</td></tr>

          <tr>
              <td>
                <span class="nowrap" style="display:inline-block;background:#D9DEE3;padding:2px 7px 2px 2px;">
                    <label><input name="acat_aktiv" type="checkbox" id="acat_aktiv" value="1" <?php if($acat_aktiv == 1) echo 'checked="checked"'; ?> />&nbsp;<?php echo $BL['be_admin_struct_visible'] ?></label>
                    &nbsp;
                    <label><input name="acat_ssl" type="checkbox" id="acat_ssl" value="1"<?php is_checked(1, $acat_ssl); ?> />&nbsp;SSL</label>
                    &nbsp;
                    <label><input name="acat_nositemap" type="checkbox" id="acat_nositemap" value="1"<?php is_checked(1, $acat_nositemap); ?> />&nbsp;<?php echo $BL['be_ctype_sitemap'] ?></label>
                    &nbsp;
                    <label><input name="acat_archive" type="checkbox" id="acat_archive" value="1"<?php is_checked(1, $acat_archive); ?> />&nbsp;<?php echo $BL['be_archive'] ?></label>
                    &nbsp;
                    <label><input name="acat_opengraph" type="checkbox" id="acat_opengraph" value="1" <?php if($acat_opengraph == 1) echo 'checked="checked"'; ?> />&nbsp;<?php echo $BL['be_opengraph_support'] ?></label>
                </span>
              </td>
          </tr>
          <tr><td><img src="img/leer.gif" alt="" width="1" height="20" /></td></tr>
          <tr><td class="tdbottom5">
                <input name="acat_sort_temp" type="hidden" value="<?php echo $acat_sort; ?>" />
                <input name="acat_struct" type="hidden" id="acat_struct" value="<?php echo $acat_struct; ?>" />
                <input name="acat_new" type="hidden" id="acat_new" value="<?php echo $acat_new; ?>" />
                <input name="acat_id" type="hidden" id="acat_id" value="<?php echo $acat_id; ?>" />
                <input name="submit" type="submit" class="button" value="<?php echo empty($acat_id) ? $BL['be_article_cnt_button2'] : $BL['be_article_cnt_button1'] ?>" />
                <input name="SubmitClose" type="submit" class="button" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input name="donotsubmit" type="button" class="button" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=admin&amp;p=6';" />
            </td></tr>
</table>
</form>
<?php

if($acat_lang_mode):

    // Be more modern here � we start switch to jQuery and overwrite non-used MooTools with jQuery call
    $GLOBALS['BE']['HEADER']['mootools.js'] = getJavaScriptSourceLink('include/inc_js/jquery/jquery.min.js');

?>
<script type="text/javascript">
// Handle language switch click
$(function() {

    var langIdSelect = $('#lang-id-select');

    $('input.lang-opt').change(function(){
        langIdSelect.show();
    });

    $('input.lang-default').change(function(){
        langIdSelect.hide();
    });

});
</script>
<?php
endif;
