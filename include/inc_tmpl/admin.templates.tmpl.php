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

reset($phpwcms['js_lib']); // reset $phpwcms['js_lib'] to get first element as default

$template = array(
    "name" => '',
    "default" => 0,
    "layout" => '',
    "css" => array(),
    "htmlhead" => '',
    "jsonload" => '',
    "headertext" => '',
    "maintext" => '',
    "footertext" => '',
    "lefttext" => '',
    "righttext" => '',
    "errortext" => '',
    "htmlhead_file" => '',
    "headertext_file" => '',
    "maintext_file" => '',
    "footertext_file" => '',
    "lefttext_file" => '',
    "righttext_file" => '',
    "errortext_file" => '',
    'feloginurl' => '',
    'jslib' => key($phpwcms['js_lib']), // take the most current
    'jslibload' => 0,
    'frontendjs' => 0,
    'googleapi' => 1,
    'onepage' => 0,
    'ie8ignore' => 0,
    'cookie_consent' => array(
        'enable' => 0,
        'message' => $BL['cookie_consent_message'],
        'dismiss' => $BL['cookie_consent_dismiss'],
        'more' => $BL['cookie_consent_more'],
        'link' => '',
        'theme' => 'light-bottom',
    ),
    'tracking_ga' => array(
        'enable' => 0,
        'id' => '',
        'anonymize' => PHPWCMS_GDPR_MODE  ? 1 : 0,
        'optout' => PHPWCMS_GDPR_MODE  ? 1 : 0,
        'cookie_flags' => PHPWCMS_GDPR_MODE  ? 1 : 0,
        'custom_properties' => ''
    ),
    'tracking_gtm' => array(
        'enable' => 0,
        'id' => '',
    ),
    'tracking_piwik' => array(
        'enable' => 0,
        'id' => '',
        'url' => ''
    ),
    'donottrack' => 0,
    'require_consent' => array(
        'enable' => 0,
        'cookie_name' => 'cookieconsent_dismissed',
        'cookie_value' => 'yes'
    ),
);

initJQuery();

if(!isset($_GET["s"])) {

?>
<h1 class="title"><?php echo $BL['be_admin_tmpl_title'] ?></h1>
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
    <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<?php
// loop listing available templates
$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_template WHERE template_trash=0 ORDER BY template_default DESC, template_name";
$result = _dbQuery($sql);
$row_count = 0;
if(isset($result[0]['template_id'])) {
    foreach($result as $row) {

        $edit_link = 'do=admin&amp;p=11&amp;s='.$row["template_id"].'&amp;t='.$row["template_type"];

        echo "<tr".( ($row_count % 2) ? " bgcolor=\"#F3F5F8\"" : "" ).">\n<td width=\"28\">"; //#F9FAFB
        echo '<img src="img/symbole/template_list_icon.gif" width="28" height="18"></td>'."\n";
        echo '<td width="470" class="dir"><a href="phpwcms.php?'.$edit_link;
        echo '"><strong>'.html($row["template_name"])."</strong>";
        echo ($row["template_default"]) ? " (".$BL['be_admin_tmpl_default'].")" : "";
        echo "</a></td>\n".'<td width="60" align="right">';
        echo '<a href="phpwcms.php?'.$edit_link;
        echo '"><img src="img/button/edit_22x11.gif" width="22" height="11" border="0"></a>';
        echo '<img src="img/leer.gif" width="2" height="1">';

        echo '<a href="phpwcms.php?'.$edit_link.'&amp;c=1'; // c=1 -> do copy
        echo '" title="copy template"><img src="img/button/copy_11x11_0.gif" width="11" height="11" border="0"></a>';
        echo '<img src="img/leer.gif" width="2" height="1">';

        echo '<a href="include/inc_act/act_frontendsetup.php?do=2|'.$row["template_id"].'" ';
        echo 'title="'.$BL['be_cnt_delete'].': '.html($row["template_name"]).'" ';
        echo 'onclick="return confirm(\''.js_singlequote($BL['be_cnt_delete'].': '.html($row["template_name"])).'\');">';
        echo '<img src="img/button/del_11x11.gif" width="11" height="11" border="0"></a>';
        echo '<img src="img/leer.gif" width="2" height="1">'."</td>\n</tr>\n";

        $row_count++;
    }

} // end listing

?>
    <tr><td colspan="3" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr><td colspan="3"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
    <tr><td colspan="3"><form action="phpwcms.php?do=admin&amp;p=11&amp;s=0" method="post">
        <input type="submit" value="<?php echo $BL['be_admin_tmpl_add'] ?>" class="button" title="<?php echo $BL['be_admin_tmpl_add'] ?>" />
    </form></td>
    </tr>
</table>
<?php

} else {

    // edit template dialog
    $template["id"] = intval($_GET["s"]);

    $createcopy = isset($_GET["c"]) ? intval($_GET["c"]) : 0;

    if(isset($_POST["template_id"])) {

        $createcopy = empty($_POST["c"]) ? 0 : intval($_POST["c"]); // ERICH COPY TEMPLATE 08.06.2005

        // read the create or edit template form data
        $template["id"] = intval($_POST["template_id"]);
        $template["default"] = empty($_POST["template_setdefault"]) ? 0 : 1;
        $template["layout"] = intval($_POST["template_layout"]);
        $template["name"] = clean_slweg($_POST["template_name"], 150);
        if(empty($template["name"])) {
            $template["name"] = "template_".generic_string(3);
        }
        $template["css"] = isset($_POST["template_css"]) && is_array($_POST["template_css"]) ? $_POST["template_css"] : array();
        $template["htmlhead"] = slweg($_POST["template_htmlhead"]);
        $template["htmlhead_file"] = clean_slweg($_POST["template_htmlhead_file"]);
        $template["jsonload"] = slweg($_POST["template_jsonload"]);
        $template["headertext"] = slweg($_POST["template_block_header"]);
        $template["headertext_file"] = clean_slweg($_POST["template_block_header_file"]);
        $template["maintext"] = slweg($_POST["template_block_main"]);
        $template["maintext_file"] = clean_slweg($_POST["template_block_main_file"]);
        $template["footertext"] = slweg($_POST["template_block_footer"]);
        $template["footertext_file"] = clean_slweg($_POST["template_block_footer_file"]);
        $template["lefttext"] = slweg($_POST["template_block_left"]);
        $template["lefttext_file"] = clean_slweg($_POST["template_block_left_file"]);
        $template["righttext"] = slweg($_POST["template_block_right"]);
        $template["righttext_file"] = clean_slweg($_POST["template_block_right_file"]);
        $template["errortext"] = slweg($_POST["template_block_error"]);
        $template["errortext_file"] = clean_slweg($_POST["template_block_error_file"]);
        $template["feloginurl"] = slweg($_POST["template_felogin_url"]);
        $template["overwrite"] = clean_slweg($_POST["template_overwrite"]);
        $template['jslib'] = clean_slweg($_POST["template_jslib"]);
        $template['jslibload'] = empty($_POST["template_jslibload"]) ? 0 : 1;
        $template['frontendjs'] = empty($_POST["template_frontendjs"]) ? 0 : 1;
        $template['googleapi'] = empty($_POST["template_googleapi"]) ? 0 : 1;
        $template['onepage'] = empty($_POST["template_onepage"]) ? 0 : 1;
        $template['ie8ignore'] = empty($_POST["template_ie8ignore"]) ? 0 : 1;
        $template['cookie_consent']['enable'] = empty($_POST['template_cookie_consent']) ? 0 : 1;
        if(!empty($_POST['template_cc_message'])) {
            $template['cookie_consent']['message'] = slweg($_POST['template_cc_message']);
        }
        if(!empty($_POST['template_cc_dismiss'])) {
            $template['cookie_consent']['dismiss'] = slweg($_POST['template_cc_dismiss']);
        }
        if(!empty($_POST['template_cc_more'])) {
            $template['cookie_consent']['more'] = slweg($_POST['template_cc_more']);
        }
        if(!empty($_POST['template_cc_link'])) {
            $template['cookie_consent']['link'] = slweg($_POST['template_cc_link']);
        }
        if(isset($_POST['template_cc_theme'])) {
            $template['cookie_consent']['theme'] = clean_slweg($_POST['template_cc_theme']);
        }
        $template['tracking_ga']['enable'] = empty($_POST['template_ga']) ? 0 : 1;
        $template['tracking_ga']['id'] = clean_slweg($_POST["template_ga_id"]);
        $template['tracking_ga']['custom_properties'] = trim(clean_slweg($_POST["template_ga_custom_properties"]), " \t\n\r\0\x0B{},");
        $template['tracking_ga']['anonymize'] = empty($_POST['template_ga_anonymize']) ? 0 : 1;
        $template['tracking_ga']['optout'] = empty($_POST['template_ga_optout']) ? 0 : 1;
        $template['tracking_ga']['cookie_flags'] = empty($_POST['template_ga_cookie_flags']) ? 0 : 1;
        if(empty($template['tracking_ga']['id'])) {
            $template['tracking_ga']['enable'] = 0;
        }
        $template['tracking_gtm']['enable'] = empty($_POST['template_gtm']) ? 0 : 1;
        $template['tracking_gtm']['id'] = clean_slweg($_POST["template_gtm_id"]);
        if(empty($template['tracking_gtm']['id'])) {
            $template['tracking_gtm']['enable'] = 0;
        }
        $template['tracking_piwik']['enable'] = empty($_POST['template_piwik']) ? 0 : 1;
        $template['tracking_piwik']['id'] = intval($_POST["template_piwik_id"]);
        $template['tracking_piwik']['url'] = clean_slweg($_POST["template_piwik_url"]);
        if(!empty($template['tracking_piwik']['url'])) {
            $template['tracking_piwik']['url'] = trim(preg_replace('/.*?:\/\//i', '', trim($template['tracking_piwik']['url'], '/')));
        }
        if(empty($template['tracking_piwik']['id']) || empty($template['tracking_piwik']['url'])) {
            $template['tracking_piwik']['enable'] = 0;
        }
        $template['donottrack'] = empty($_POST["template_donottrack"]) ? 0 : 1;
        $template['require_consent'] = array(
            'enable' => empty($_POST["template_require_consent"]) ? 0 : 1,
            'cookie_name' => clean_slweg($_POST['template_require_cookie_name']),
            'cookie_value' => clean_slweg($_POST['template_require_cookie_value'])
        );

        // now browse custom blocks if available
        if(!empty($_POST['customblock'])) {

            $template['customblock'] = clean_slweg($_POST["customblock"]);
            $temp_customblock = explode(',', $template['customblock']);
            foreach($temp_customblock as $value) {

                $template['customblock_'.$value] = slweg($_POST['template_customblock_'.$value]);
                $template['customblock_'.$value.'_file'] = slweg($_POST['template_customblock_'.$value.'_file']);

            }
        }

        if($template["id"] && empty($createcopy)) {
            // if ID <> 0 then get template info from database
            $query_mode = 'UPDATE';
            $sql =  "UPDATE ".DB_PREPEND."phpwcms_template SET ".
                    "template_name='".aporeplace($template["name"])."', ".
                    "template_default=".$template["default"].", ".
                    "template_var='".aporeplace(serialize($template))."' ".
                    "WHERE template_id=".$template["id"];
        } else {
            // if ID = 0 then show create new template form
            $query_mode = 'INSERT';
            $sql =  "INSERT INTO ".DB_PREPEND."phpwcms_template (".
                    "template_name, template_default, template_var) VALUES ('".
                    aporeplace($template["name"])."', ".$template["default"].", '".
                    aporeplace(serialize($template))."')";
        }
        // update or insert data entry
        $result = _dbQuery($sql, $query_mode);

        if($query_mode === 'INSERT' && !empty($result['INSERT_ID'])) {
            $template["id"] = $result['INSERT_ID'];
        }

        //now proof for default template definition
        if($template["default"]) {
            _dbQuery("UPDATE ".DB_PREPEND."phpwcms_template SET template_default=0 WHERE template_id != ".$template["id"], 'UPDATE');
        }
        update_cache();
        headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=admin&p=11&s='.$template["id"]);
    }

    if($template["id"]) {
        // read the given template datas from db
        $sql = "SELECT * FROM ".DB_PREPEND."phpwcms_template WHERE template_id=".$template["id"]." LIMIT 1";
        $result = _dbQuery($sql);
        if(isset($result[0]['template_id'])) {
            if(($result[0]["template_var"] = @unserialize($result[0]["template_var"]))) {
                $template = array_merge($template, $result[0]["template_var"]);
            }
            $template["id"] = intval($result[0]["template_id"]);
            $template["default"] = $result[0]["template_default"];

            // compatibility for older releases where only 1 css file could be stored per template
            if(is_string($template['css'])) {
                $template['css'] = array($template['css']);
            }
         }
    }

    // show form
?>
<script type="text/javascript">
    function doPageLayoutChange() {
        if(confirm('<?php echo $BL['be_admin_template_jswarning'] ?>')) {
            document.blocks.submit();
            return true;
        }
        return false;
    }
</script>
<form action="phpwcms.php?do=admin&amp;p=11&amp;s=<?php echo $template["id"] ?>" method="post" name="blocks" target="_self" id="blocks">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">

    <tr><td colspan="2" class="title"><?php echo (empty($createcopy) ? $BL['be_admin_tmpl_edit'] : $BL['be_admin_tmpl_copy']) ?>: <?php echo ($template["id"]) ? html($template["name"]) : $BL['be_admin_tmpl_new']; ?>
        <input type="hidden" name="c" value="<?php echo $createcopy; ?>" /></td></tr>
    <tr><td colspan="2" class="rowspacer7x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr bgcolor="#E6EAED"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
    </tr>
    <tr bgcolor="#E6EAED">
        <td align="right" class="chatlist"><?php echo $BL['be_admin_tmpl_name'] ?>:&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
          <tr>
            <td><?php
            // ERICH COPY TEMPLATE 08.06.2005
            if(empty($createcopy)) {
                echo '<input name="template_name" type="text" class="f11b width350" id="template_name" value="'.html($template["name"]).'" size="50" maxlength="150">';
            } else {
                echo '<img src="img/symbole/achtung.gif" width="13" height="11" alt="" border="0" style="margin-right:2px;" /><input name="template_name" type="text" class="f11b width350" id="template_name" style="color:FF3300" value="'.html($template["name"]).'_'.generic_string(2).'" size="50" maxlength="150">';
            }
            ?></td>
            <td>&nbsp;</td>
            <td><input name="template_setdefault" id="template_setdefault" type="checkbox" value="1" <?php is_checked(empty($createcopy) ? $template["default"] : 0, 1) ?> /></td>
            <td class="v10"><label for="template_setdefault"><?php echo $BL['be_admin_tmpl_default'] ?></label></td>
          </tr>
          </table></td>
    </tr>
    <tr bgcolor="#E6EAED"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
    <tr bgcolor="#E6EAED">
        <td align="right" class="chatlist"><?php echo $BL['be_admin_tmpl_layout'] ?>:&nbsp;</td>
        <td><?php
// get available page layout list
$jsOnChange = '';
$opt = "";
$sql = "SELECT * FROM ".DB_PREPEND."phpwcms_pagelayout WHERE pagelayout_trash=0 ORDER BY pagelayout_default DESC";
$result = _dbQuery($sql);
if(isset($result[0]['pagelayout_id'])) {
    foreach($result as $row) {
        $opt .= '<option value="'.$row['pagelayout_id'].'"';
        if($row['pagelayout_id'] == $template["layout"]) {
            $opt .= ' selected="selected"';
            // try to get additional custom blocks from selected page layout
            $custom_blocks = unserialize($row['pagelayout_var']);
            $custom_blocks = explode(', ', trim($custom_blocks['layout_customblocks']));

            if(is_array($custom_blocks) && count($custom_blocks) && $custom_blocks[0] != '') {
                $jsOnChange = ' onChange="doPageLayoutChange();"';
            } else {
                $jsOnChange = '';
            }
        }
        $opt .= '>'.html($row['pagelayout_name']).'</option>';
    }
}

if($opt) {
    echo '<select name="template_layout" class="width350" id="template_layout"'.$jsOnChange.'>';
    echo $opt;
    echo '</select>';
} else {
    echo $BL['be_admin_tmpl_nolayout'].' (<a href="phpwcms.php?do=admin&p=8&s=0">'.$BL['be_admin_page_add'].'</a>)';
}

?></td>
    </tr>
    <tr bgcolor="#E6EAED"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
    <tr bgcolor="#E6EAED">
        <td>&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
          <tr>
            <td><input name="template_onepage" id="template_onepage" type="checkbox" value="1" <?php is_checked((!empty($template["onepage"]) ? 1 : 0), 1) ?> /></td>
            <td class="v10"><label for="template_onepage"><?php echo $BL['be_onepage_template'] ?></label></td>
          </tr>
          </table></td>
    </tr>

    <tr bgcolor="#E6EAED"><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

    <tr bgcolor="#E6EAED">
        <td>&nbsp;</td>
        <td class="chatlist tdbottom3"><?php echo $BL['be_overwrite_default'] ?><br/><strong>include/config/conf.template_default.inc.php</strong></td>
    </tr>

    <tr bgcolor="#E6EAED">
        <td align="right" class="chatlist" style="padding-left:2px"><?php echo $BL['be_settings'] ?>:&nbsp;</td>
        <td><select name="template_overwrite" id="template_overwrite">
            <option value="" style="font-weight:normal;font-style:italic;"><?php echo $BL['be_admin_tmpl_default']; ?></option>
<?php

// templates for frontend login
$tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE.'inc_settings/template_default', 'php');
if(is_array($tmpllist) && count($tmpllist)) {
    foreach($tmpllist as $val) {
        $selected_val = (isset($template["overwrite"]) && $val == $template["overwrite"]) ? ' selected="selected"' : '';
        $val = html($val);
        echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
    }
}

?>
        </select></td>
    </tr>

    <tr bgcolor="#E6EAED"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
    <tr><td colspan="2" class="rowspacer1x0" bgcolor="#F3F5F8"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>

    <tr bgcolor="#F3F5F8">
        <td align="right" class="chatlist" valign="top"><?php echo $BL['be_admin_tmpl_css'] ?>:<img src="img/leer.gif" alt="" width="4" height="14" /></td>
        <td class="tdbottom5"><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>
            <td valign="top"><select name="template_css[]" size="6" multiple="multiple" class="code" id="template_css">
<?php

$unselected_css = array();

// get css file list
if(is_dir(PHPWCMS_TEMPLATE."inc_css")) {

    $css_handle = opendir(PHPWCMS_TEMPLATE."inc_css" );

    // browse template CSS diretory and list all available CSS files
    while($css_file = readdir($css_handle)) {

        if(substr($css_file, 0, 1) !== '.' && is_file(PHPWCMS_TEMPLATE."inc_css/".$css_file) && preg_match('/^[a-z0-9\. \-_]+\.css$/i', $css_file) ) {

            $unselected_css[$css_file] = $css_file;

        }
    }
    closedir( $css_handle );
}

// now run the css information
foreach($template["css"] as $value) {
    if(isset($unselected_css[$value])) {
        $css_file = html($value);
        echo '      <option value="'.$css_file.'" selected="selected" style="font-weight: bold;">'.$css_file.'&nbsp;&nbsp;</option>'.LF;
        unset($unselected_css[$value]);
    }
}
foreach($unselected_css as $value) {
    $css_file = html($value);
    echo '      <option value="'.$css_file.'">'.$css_file.'&nbsp;&nbsp;</option>'.LF;
}

?>
            </select></td>

          <td valign="top" align="center">
        <img src="img/button/list_pos_up.gif" alt="" width="15" height="15" border="0" onclick="moveOptionUp(document.blocks.template_css);" /><br />
        <img src="img/leer.gif" width="23" height="3" alt="" /><br />
        <img src="img/button/list_pos_down.gif" alt="" width="15" height="15" border="0" onclick="moveOptionDown(document.blocks.template_css);" /></td>
          <td valign="top">&nbsp;</td>

          </tr>
          </table></td>
    </tr>

    <tr bgcolor="#F3F5F8">
        <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_tmpl_head'] ?>:&nbsp;<br />&lt;head&gt; &nbsp;</td>
        <td>
            <?php
            if(!isset($template["htmlhead_file"])) {
                $template["htmlhead_file"] = '';
            }
            echo get_template_file_select('head', 'template_htmlhead_file', $template["htmlhead_file"]);
            ?>
            <textarea name="template_htmlhead" cols="35" rows="3" class="code width600 autosize mb-5"><?php echo html_entities($template["htmlhead"]); ?></textarea>
        </td>
    </tr>

    <tr bgcolor="#F3F5F8">
      <td align="right" class="chatlist"><?php echo $BL['js_lib'] ?>:&nbsp;</td>
      <td><table cellpadding="0" cellspacing="0" border="0" summary="">

        <tr>
            <td><select name="template_jslib" id="template_jslib">
<?php
foreach($phpwcms['js_lib'] as $key => $value) {

    echo '<option value="' . $key . '"';
    is_selected($template['jslib'], $key);
    echo '>' . html($value) . '</option>';

}

?>
            </select></td>
            <td>&nbsp;</td>
            <td><input type="checkbox" name="template_jslibload" id="template_jslibload" value="1"<?php is_checked($template['jslibload'], 1); ?> /></td>
            <td class="v10"><label for="template_jslibload"><?php echo $BL['js_lib_alwaysload'] ?></label></td>
            <td>&nbsp;&nbsp;</td>
            <td><input type="checkbox" name="template_googleapi" id="template_googleapi" value="1"<?php is_checked($template['googleapi'], 1); ?> /></td>
            <td class="v10"><label for="template_googleapi"><?php echo $BL['googleapi_load'] ?></label></td>
        </tr>
    </table></td>
    </tr>

    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

    <tr bgcolor="#F3F5F8">
      <td align="right" class="chatlist">&nbsp;</td>
      <td><table cellpadding="0" cellspacing="0" border="0" summary="">

        <tr>
            <td><input type="checkbox" name="template_ie8ignore" id="template_ie8ignore" value="1"<?php is_checked($template['ie8ignore'], 1); ?> /></td>
            <td class="v10"><label for="template_ie8ignore"><?php echo $BL['be_ie8ignore'] ?></label></td>
        </tr>

        <tr>
          <td><input type="checkbox" name="template_donottrack" id="template_donottrack" value="1"<?php is_checked($template['donottrack'], 1); ?> /></td>
          <td class="v10"><label for="template_donottrack"><?php echo $BL['be_respect_donottrack']; ?></label></td>
        </tr>

        <tr>
            <td><input type="checkbox" name="template_ga" id="template_ga" value="1"<?php is_checked($template['tracking_ga']['enable'], 1); ?> /></td>
            <td class="v10"><label for="template_ga"><?php echo $BL['be_google_analytics_enable']; ?></label></td>
        </tr>
        <tr id="ga-tracking"<?php if(empty($template['tracking_ga']['enable'])): ?> style="display:none;"<?php endif; ?>>
            <td>&nbsp;</td>
            <td class="tdtop3 tdbottom5">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="right" class="chatlist tdtop3 nowrap"><?php echo $BL['be_tracking_id']; ?>:&nbsp;</td>
                        <td class="tdbottom3" colspan="2"><input type="text" name="template_ga_id" maxlength="20" class="width150" placeholder="UA-XXXXX-Y" value="<?php echo html($template['tracking_ga']['id']) ?>" /></td>
                    </tr>
                    <tr>
                        <td class="chatlist">&nbsp;</td>
                        <td class="tdtop3"><input type="checkbox" name="template_ga_anonymize" id="template_ga_anonymize" value="1"<?php is_checked($template['tracking_ga']['anonymize'], 1); ?> /></td>
                        <td class="chatlist tdtop3 nowrap"><label for="template_ga_anonymize">&nbsp;<?php echo $BL['be_tracking_anonymize']; ?></label></td>
                    </tr>
                    <tr>
                        <td class="chatlist">&nbsp;</td>
                        <td><input type="checkbox" name="template_ga_optout" id="template_ga_optout" value="1"<?php is_checked(isset($template['tracking_ga']['optout']) ? $template['tracking_ga']['optout'] : 0, 1); ?> /></td>
                        <td class="chatlist nowrap"><label for="template_ga_optout">&nbsp;<?php echo $BL['be_tracking_optout']; ?></label></td>
                    </tr>
                    <tr>
                        <td class="chatlist">&nbsp;</td>
                        <td><input type="checkbox" name="template_ga_cookie_flags" id="template_ga_cookie_flags" value="1"<?php is_checked(isset($template['tracking_ga']['cookie_flags']) ? $template['tracking_ga']['cookie_flags'] : 0, 1); ?> /></td>
                        <td class="chatlist nowrap"><label for="template_ga_cookie_flags">&nbsp;<?php echo $BL['be_tracking_cookie_flags']; ?></label></td>
                    </tr>
                    <tr>
                        <td class="chatlist">&nbsp;</td>
                        <td class="chatlist tdtop5 tdbottom3" colspan="2"><?php echo $BL['be_tracking_custom_properties']; ?>:</td>
                    </tr>
                    <tr>
                        <td class="chatlist">&nbsp;</td>
                        <td class="tdbottom3" colspan="2"><textarea name="template_ga_custom_properties" class="width400 autosize" placeholder="prop1: 'val1', prop2: true"><?php echo html($template['tracking_ga']['custom_properties']) ?></textarea></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td><input type="checkbox" name="template_gtm" id="template_gtm" value="1"<?php is_checked($template['tracking_gtm']['enable'], 1); ?> /></td>
            <td class="v10"><label for="template_gtm"><?php echo $BL['be_google_tag_manager_enable']; ?></label></td>
        </tr>
        <tr id="gtm-tracking"<?php if(empty($template['tracking_gtm']['enable'])): ?> style="display:none;"<?php endif; ?>>
            <td>&nbsp;</td>
            <td class="tdtop3 tdbottom5">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="right" class="chatlist tdtop3 nowrap"><?php echo $BL['be_tracking_id']; ?>:&nbsp;</td>
                        <td class="tdbottom3" colspan="2"><input type="text" name="template_gtm_id" maxlength="15" class="width150" placeholder="GTM-XXXXXXX" value="<?php echo html($template['tracking_gtm']['id']) ?>" /></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td><input type="checkbox" name="template_piwik" id="template_piwik" value="1"<?php is_checked($template['tracking_piwik']['enable'], 1); ?> /></td>
            <td class="v10"><label for="template_piwik"><?php echo $BL['be_piwik_enable']; ?></label></td>
        </tr>
        <tr id="piwik-tracking"<?php if(empty($template['tracking_piwik']['enable'])): ?> style="display:none;"<?php endif; ?>>
            <td>&nbsp;</td>
            <td class="tdtop3 tdbottom5">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="right" class="chatlist tdtop3 nowrap"><?php echo $BL['be_site_id']; ?>:&nbsp;</td>
                        <td class="tdbottom3"><input type="text" name="template_piwik_id" maxlength="11" class="width150" placeholder="1" value="<?php echo empty($template['tracking_piwik']['id']) ? '' : $template['tracking_piwik']['id']; ?>" /></td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop3 nowrap"><?php echo $BL['be_piwik_url']; ?>:&nbsp;</td>
                        <td class="tdbottom3"><input type="text" name="template_piwik_url" maxlength="200" class="width400" placeholder="piwik.example.com" value="<?php echo html($template['tracking_piwik']['url']) ?>" /></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td><input type="checkbox" name="template_cookie_consent" id="template_cookie_consent" value="1"<?php is_checked($template['cookie_consent']['enable'], 1); ?> /></td>
            <td class="v10"><label for="template_cookie_consent"><?php echo $BL['be_cookie_consent_enable'] ?></label></td>
        </tr>
        <tr id="template-cc-form"<?php if(!$template['cookie_consent']['enable']): ?> style="display:none;"<?php endif; ?>>
            <td>&nbsp;</td>
            <td class="tdbottom5">
                <?php if(count($phpwcms['allowed_lang'])): ?><div class="chatlist wrap tdbottom3 tdright10"><?php echo $BL['be_cookie_consent_translatable']; ?></div><?php endif; ?>
                <table cellpadding="0" cellspacing="0" border="0" class="tdtop3">
                    <tr>
                        <td align="right" class="chatlist tdtop3 nowrap"><?php echo $BL['be_cookie_consent_message']; ?>:&nbsp;</td>
                        <td class="tdbottom3"><textarea name="template_cc_message" rows="3" class="width400 autosize" placeholder="<?php echo $BL['cookie_consent_message']; ?>"><?php echo html_entities($template['cookie_consent']['message']) ?></textarea></td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap"><?php echo $BL['be_cookie_consent_dismiss']; ?>:&nbsp;</td>
                        <td class="tdbottom3"><input type="text" name="template_cc_dismiss" maxlength="100" class="width400" placeholder="<?php echo $BL['cookie_consent_dismiss']; ?>" value="<?php echo html_entities($template['cookie_consent']['dismiss']) ?>" /></td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap"><?php echo $BL['be_cookie_consent_more']; ?>:&nbsp;</td>
                        <td class="tdbottom3"><input type="text" name="template_cc_more" maxlength="100" class="width400" placeholder="<?php echo $BL['cookie_consent_more']; ?>" value="<?php echo html_entities($template['cookie_consent']['more']) ?>" /></td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap"><?php echo $BL['be_cookie_consent_link']; ?>:&nbsp;</td>
                        <td class="tdbottom3"><input type="text" name="template_cc_link" class="width400" placeholder="http://example.com/cookie-policy | cookie-policy" value="<?php echo html($template['cookie_consent']['link']) ?>" /></td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap"><?php echo $BL['be_cookie_consent_theme']; ?>:&nbsp;</td>
                        <td class="tdbottom3"><input type="text" name="template_cc_theme" maxlength="200" class="width400"
                            placeholder="light-top, light-bottom, light-floating, dark-top&hellip;"
                            title="<?php echo $BL['be_admin_tmpl_default']; ?>: light-top, light-bottom, light-floating, dark-top, dark-bottom, dark-floating, dark-inline, dark-floating-tada"
                            value="<?php echo html($template['cookie_consent']['theme']) ?>" /></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
          <td><input type="checkbox" name="template_require_consent" id="template_require_consent" value="1"<?php is_checked($template['require_consent']['enable'], 1); ?> /></td>
          <td class="v10"><label for="template_require_consent"><?php echo $BL['be_require_consent']; ?></label></td>
        </tr>
        <tr id="template-cr-form">
          <td>&nbsp;</td>
          <td class="tdbottom5">
              <table cellpadding="0" cellspacing="0" border="0" class="tdtop3">
                  <tr>
                      <td align="right" class="chatlist tdtop3 nowrap"><?php echo $BL['be_consent_cookie_name']; ?>:&nbsp;</td>
                      <td class="tdbottom3"><input type="text" name="template_require_cookie_name" maxlength="255"  class="width400" placeholder="<?php echo $BL['placeholder_require_cookie_name']; ?>" value="<?php echo html($template['require_consent']['cookie_name']) ?>" /></td>
                  </tr>
                  <tr>
                      <td align="right" class="chatlist tdtop4 nowrap"><?php echo $BL['be_consent_cookie_value']; ?>:&nbsp;</td>
                      <td class="tdbottom3"><input type="text" name="template_require_cookie_value" maxlength="255" class="width400" placeholder="<?php echo $BL['placeholder_require_cookie_value']; ?>" value="<?php echo html($template['require_consent']['cookie_value']) ?>" /></td>
                  </tr>
              </table>
          </td>
        </tr>

        <tr>
            <td><input type="checkbox" name="template_frontendjs" id="template_frontendjs" value="1"<?php is_checked($template['frontendjs'], 1); ?> /></td>
            <td class="v10"><label for="template_frontendjs"><?php echo $BL['frontendjs_load'] ?></label></td>
        </tr>

      </table></td>
    </tr>

    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

    <tr bgcolor="#F3F5F8">
      <td align="right" class="chatlist"><?php echo $BL['be_admin_tmpl_js'] ?>:&nbsp;</td>
      <td><input name="template_jsonload" type="text" class="code width600" id="template_jsonload" value="<?php echo html_entities($template["jsonload"]) ?>" size="50" /></td>
    </tr>

    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>


    <tr bgcolor="#F3F5F8">
      <td align="right" class="chatlist nowrap" nowrap="nowrap">&nbsp;<?php echo $BL['be_fe_login_url'] ?>:&nbsp;</td>
      <td><input name="template_felogin_url" type="text" class="code width600" id="template_felogin_url" value="<?php echo empty($template["feloginurl"]) ? '' : html_entities($template["feloginurl"]) ?>" size="50" /></td>
    </tr>
    <tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>
    <tr><td colspan="2" class="rowspacer1x0" bgcolor="#F3F5F8"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

    <tr>
        <td>&nbsp;</td>
        <td style="padding:7px 0">
            <input name="Submit" type="submit" class="button" value="<?php echo $BL['be_admin_tmpl_button'] ?>" />
            &nbsp;&nbsp;
            <input type="button" class="button" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='phpwcms.php?do=admin&amp;p=11';" />
        </td>
    </tr>
    <tr><td colspan="2" class="rowspacer1x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
    <tr>
        <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_page_header'] ?>:&nbsp;</td>
        <td>
            <?php
            if(!isset($template["headertext_file"])) {
                $template["headertext_file"] = '';
            }
            echo get_template_file_select('header', 'template_block_header_file', $template["headertext_file"]);
            ?>
            <textarea name="template_block_header" cols="35" rows="3" class="code width600 autosize"><?php echo html_entities($template["headertext"]); ?></textarea>
        </td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
    </tr>
    <tr>
        <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_page_main'] ?>:&nbsp;</td>
        <td>
            <?php
            if(!isset($template["maintext_file"])) {
                $template["maintext_file"] = '';
            }
            echo get_template_file_select('main', 'template_block_main_file', $template["maintext_file"]);
            ?>
            <textarea name="template_block_main" cols="35" rows="3" class="code width600 autosize"><?php echo html_entities($template["maintext"]); ?></textarea>
        </td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
    </tr>
    <tr>
        <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_page_footer'] ?>:&nbsp;</td>
        <td>
            <?php
            if(!isset($template["footertext_file"])) {
                $template["footertext_file"] = '';
            }
            echo get_template_file_select('footer', 'template_block_footer_file', $template["footertext_file"]);
            ?>
            <textarea name="template_block_footer" cols="35" rows="3" class="code width600 autosize"><?php echo html_entities($template["footertext"]); ?></textarea>
        </td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
    </tr>
    <tr>
        <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_page_left'] ?>:&nbsp;</td>
        <td>
            <?php
            if(!isset($template["lefttext_file"])) {
                $template["lefttext_file"] = '';
            }
            echo get_template_file_select('left', 'template_block_left_file', $template["lefttext_file"]);
            ?>
            <textarea name="template_block_left" cols="35" rows="3" class="code width600 autosize"><?php echo html_entities($template["lefttext"]); ?></textarea>
        </td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
    </tr>
    <tr>
        <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_page_right'] ?>:&nbsp;</td>
        <td>
            <?php
            if(!isset($template["righttext_file"])) {
                $template["righttext_file"] = '';
            }
            echo get_template_file_select('right', 'template_block_right_file', $template["righttext_file"]);
            ?>
            <textarea name="template_block_right" cols="35" rows="3" class="code width600 autosize"><?php echo html_entities($template["righttext"]); ?></textarea>
        </td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

<?php
if(!empty($jsOnChange))  {

    echo '<tr><td colspan="2"><img src="img/leer.gif" width="1" height="5" alt="" /></td></tr>';
    echo '<tr><td colspan="2" class="rowspacer1x0" bgcolor="#F3F5F8"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>';
    echo '<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" width="1" height="8" alt="" />';
    echo '<input type="hidden" name="customblock" value="'.html(implode(',', $custom_blocks)).'" />';
    echo "</td></tr>\n";
    // list custom blocks
    foreach($custom_blocks as $value) {

        $custom_block = html($value);

        if(!isset($template['customblock_'.$value.'_file'])) {
            $template['customblock_'.$value.'_file'] = '';
        }

        echo '<tr bgcolor="#F3F5F8"><td><img src="img/leer.gif" width="1" height="14" alt="" /></td>';
        echo '<td class="chatlist" valign="top">'.$custom_block." {".$custom_block."}</td>\n</tr>\n";
        echo '<tr bgcolor="#F3F5F8"><td>&nbsp;</td>';
        echo '<td>';
        echo get_template_file_select(strtolower($value), 'template_customblock_'.$custom_block.'_file', $template['customblock_'.$value.'_file']);
        echo '<textarea name="template_customblock_'.$custom_block;
        echo '" cols="35" rows="3" class="code width600 autosize">';
        echo isset($template['customblock_'.$value]) ? html_entities($template['customblock_'.$value]) : '';
        echo "</textarea></td></tr>";
        echo '<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" width="1" height="3" alt="" /></td></tr>'."\n";

    }

    echo '<tr bgcolor="#F3F5F8"><td colspan="2"><img src="img/leer.gif" width="1" height="5" alt="" /></td></tr>
    <tr><td colspan="2" class="rowspacer1x0" bgcolor="#F3F5F8"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" width="1" height="8" alt="" /></td></tr>';

}
?>
    <tr>
      <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_admin_tmpl_error'] ?>:&nbsp;</td>
      <td>
          <?php
          if(!isset($template["errortext_file"])) {
              $template["errortext_file"] = '';
          }
          echo get_template_file_select('error', 'template_block_error_file', $template["errortext_file"]);
          ?>
          <textarea name="template_block_error" cols="35" rows="3" class="code width600 autosize"><?php echo html_entities($template["errortext"]); ?></textarea>
      </td>
    </tr>

    <tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

    <tr>
        <td>&nbsp;<input name="template_id" type="hidden" value="<?php echo $template["id"] ?>" /></td>
        <td style="padding-bottom:10px;">
        <input name="Submit" type="submit" class="button" value="<?php echo $BL['be_admin_tmpl_button'] ?>" />
        &nbsp;&nbsp;
        <input type="button" class="button" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='phpwcms.php?do=admin&amp;p=11';" /></td>
    </tr>

</table>
</form>
<script type="text/javascript">

    $(function(){
        $('#template_cookie_consent').on('change', function(){
            if($(this).is(':checked')) {
                $('#template-cc-form').show();
            } else {
                $('#template-cc-form').hide();
            }
        });
        $('#template_require_consent').on('change', function(){
            if($(this).is(':checked')) {
                $('#template-cr-form').show();
            } else {
                $('#template-cr-form').hide();
            }
        });
        $('#template_ga').on('change', function(){
            if($(this).is(':checked')) {
                $('#ga-tracking').show();
            } else {
                $('#ga-tracking').hide();
            }
        });
        $('#template_gtm').on('change', function(){
            if($(this).is(':checked')) {
                $('#gtm-tracking').show();
            } else {
                $('#gtm-tracking').hide();
            }
        });
        $('#template_piwik').on('change', function(){
            if($(this).is(':checked')) {
                $('#piwik-tracking').show();
            } else {
                $('#piwik-tracking').hide();
            }
        });
    });

</script>
<?php

}
