<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2025, Oliver Georgi
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
    'cc_v3' => array(
        'enable' => 0,
        'title' => '',
        'description' => '',
        'accept_all' => '',
        'accept_necessary' => '',
        'accept_selected' => '',
        'reject_all' => '',
        'customize' => '',
        'link' => '',
        'more' => '',
        'theme' => '',
        'sections' => [
            'general' => [
                'active' => 1,
                'title' => '',
                'description' => '',
            ],
            'necessary' => [
                'active' => 1,
                'title' => '',
                'description' => '',
            ],
            'functionality' => [
                'active' => 1,
                'title' => '',
                'description' => '',
            ],
            'analytics' => [
                'active' => 1,
                'title' => '',
                'description' => '',
            ],
            'marketing' => [
                'active' => 1,
                'title' => '',
                'description' => '',
            ],
            'social' => [
                'active' => 1,
                'title' => '',
                'description' => '',
            ],
            'more' => [
                'active' => 1,
                'title' => '',
                'description' => '',
            ],
        ],
        'gui' => [
            'consent' => [
                'layout' => 'box',
                'position' => 'bottom right',
                'btn_flip' => 0,
                'btn_equal' => 0,
            ],
            'preferences' => [
                'layout' => 'bar',
                'position' => 'right',
                'btn_flip' => 0,
                'btn_equal' => 0,
            ],
        ],
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

        // cookie consent v2
        $template['cookie_consent']['enable'] = empty($_POST['template_cookie_consent']) ? 0 : 1;
        if(!empty($_POST['cookie_consent_message'])) {
            $template['cookie_consent']['message'] = slweg($_POST['cookie_consent_message']);
        }
        if(!empty($_POST['cookie_consent_dismiss'])) {
            $template['cookie_consent']['dismiss'] = slweg($_POST['cookie_consent_dismiss']);
        }
        if(!empty($_POST['cookie_consent_more'])) {
            $template['cookie_consent']['more'] = slweg($_POST['cookie_consent_more']);
        }
        if(!empty($_POST['cookie_consent_link'])) {
            $template['cookie_consent']['link'] = slweg($_POST['cookie_consent_link']);
        }
        if(isset($_POST['cookie_consent_theme'])) {
            $template['cookie_consent']['theme'] = clean_slweg($_POST['cookie_consent_theme']);
        }

        // cookie consent v3
        $template['cc_v3']['enable'] = empty($_POST['template_cc_v3']) ? 0 : 1;
        if(!empty($_POST['cc_v3_title'])) {
            $template['cc_v3']['title'] = slweg($_POST['cc_v3_title']);
        }
        if(!empty($_POST['cc_v3_description'])) {
            $template['cc_v3']['description'] = slweg($_POST['cc_v3_description']);
        }
        if(!empty($_POST['cc_v3_accept_all'])) {
            $template['cc_v3']['accept_all'] = slweg($_POST['cc_v3_accept_all']);
        }
        if(!empty($_POST['cc_v3_accept_necessary'])) {
            $template['cc_v3']['accept_necessary'] = slweg($_POST['cc_v3_accept_necessary']);
        }
        if(!empty($_POST['cc_v3_accept_selected'])) {
            $template['cc_v3']['accept_selected'] = slweg($_POST['cc_v3_accept_selected']);
        }
        if(!empty($_POST['cc_v3_reject_all'])) {
            $template['cc_v3']['reject_all'] = slweg($_POST['cc_v3_reject_all']);
        }
        if(!empty($_POST['cc_v3_customize'])) {
            $template['cc_v3']['customize'] = slweg($_POST['cc_v3_customize']);
        }
        if(!empty($_POST['cc_v3_link'])) {
            $template['cc_v3']['link'] = slweg($_POST['cc_v3_link']);
        }
        if(!empty($_POST['cc_v3_more'])) {
            $template['cc_v3']['more'] = slweg($_POST['cc_v3_more']);
        }
        if(!empty($_POST['cc_v3_theme'])) {
            $template['cc_v3']['theme'] = slweg($_POST['cc_v3_theme']);
        }
        if(!empty($_POST['cc_v3_consent_layout'])) {
            $template['cc_v3']['gui']['consent']['layout'] = slweg($_POST['cc_v3_consent_layout']);
        }
        if(!empty($_POST['cc_v3_consent_position'])) {
            $template['cc_v3']['gui']['consent']['position'] = slweg($_POST['cc_v3_consent_position']);
        }
        $template['cc_v3']['gui']['consent']['btn_flip'] = empty($_POST['cc_v3_consent_flip']) ? 0 : 1;
        $template['cc_v3']['gui']['consent']['btn_equal'] = empty($_POST['cc_v3_consent_equal']) ? 0 : 1;
        if(!empty($_POST['cc_v3_preferences_layout'])) {
            $template['cc_v3']['gui']['preferences']['layout'] = slweg($_POST['cc_v3_preferences_layout']);
        }
        if(!empty($_POST['cc_v3_preferences_position'])) {
            $template['cc_v3']['gui']['preferences']['position'] = slweg($_POST['cc_v3_preferences_position']);
        }
        $template['cc_v3']['gui']['preferences']['btn_flip'] = empty($_POST['cc_v3_preferences_flip']) ? 0 : 1;
        $template['cc_v3']['gui']['preferences']['btn_equal'] = empty($_POST['cc_v3_preferences_equal']) ? 0 : 1;

        // Consent Sections
        foreach (['general', 'necessary', 'functionality', 'analytics', 'marketing', 'social', 'more' ] as $section) {
            $template['cc_v3']['sections'][$section]['active'] = empty($_POST['cc_v3_'.$section.'_active']) ? 0 : 1;
            if(!empty($_POST['cc_v3_'.$section.'_title'])) {
                $template['cc_v3']['sections'][$section]['title'] = slweg($_POST['cc_v3_'.$section.'_title']);
            }
            if(!empty($_POST['cc_v3_'.$section.'_description'])) {
                $template['cc_v3']['sections'][$section]['description'] = slweg($_POST['cc_v3_'.$section.'_description']);
            }
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
            if(($result[0]["template_var"] = @unserialize($result[0]["template_var"], ['allowed_classes' => false]))) {
                $template = array_replace_recursive($template, $result[0]["template_var"]);
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
            $custom_blocks = unserialize($row['pagelayout_var'], ['allowed_classes' => false]);
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

        if(!str_starts_with($css_file, '.') && is_file(PHPWCMS_TEMPLATE."inc_css/".$css_file) && preg_match('/^[a-z0-9\. \-_]+\.css$/i', $css_file) ) {

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
$jslib_optgroup = false;
$jslib_current_optgroup = '';
$jslib_selected = '';
foreach($phpwcms['js_lib'] as $key => $value) {
    if (substr($value, 0, 1) === '-' && $key !== $jslib_current_optgroup) {
        if ($jslib_optgroup) {
            echo '</optgroup>';
        }
        $jslib_optgroup = true;
        $jslib_current_optgroup = $key;
        echo '<optgroup label="' . html($jslib_current_optgroup) . '">';
        continue;
    }
    echo '<option value="' . $key . '"';
    if ($template['jslib'] == $key) {
        $jslib_selected = $key;
        is_selected($template['jslib'], $key);
    }
    echo '>' . html($value) . '</option>';
}
if ($jslib_optgroup) {
    echo '</optgroup>';
}
if ($template['jslib'] && !$jslib_selected) {
    echo '<optgroup label="' . html($BL['be_deprecated']) . '">';
    echo '<option value="' . $template['jslib'] . '" selected="selected">';
    echo html($template['jslib'] . ' (' . $BL['be_deprecated'] . ')');
    echo '</option>';
    echo '</optgroup>';
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
                        <td><input type="checkbox" name="template_ga_optout" id="template_ga_optout" value="1"<?php is_checked($template['tracking_ga']['optout'] ?? 0, 1); ?> /></td>
                        <td class="chatlist nowrap"><label for="template_ga_optout">&nbsp;<?php echo $BL['be_tracking_optout']; ?></label></td>
                    </tr>
                    <tr>
                        <td class="chatlist">&nbsp;</td>
                        <td><input type="checkbox" name="template_ga_cookie_flags" id="template_ga_cookie_flags" value="1"<?php is_checked($template['tracking_ga']['cookie_flags'] ?? 0, 1); ?> /></td>
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

        <!-- Cookie Consent v2 -->
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

        <!-- Cookie Consent v3 -->
        <tr>
            <td>
                <input type="checkbox" name="template_cc_v3" id="template_cc_v3" value="1"<?php is_checked($template['cc_v3']['enable'], 1); ?> />
            </td>
            <td class="v10"><label for="template_cc_v3"><?php echo $BL['be_cc_v3_enable'] ?></label></td>
        </tr>
        <tr id="template-cc_v3-form"<?php if(!$template['cc_v3']['enable']): ?> style="display:none;"<?php endif; ?>>
            <td>&nbsp;</td>
            <td class="tdbottom5">
                <?php if (count($phpwcms['allowed_lang'])): ?>
                <div class="chatlist wrap tdbottom3 tdright10">
                    <?php echo $BL['be_cookie_consent_translatable']; ?>
                </div>
                <?php endif; ?>
                <table cellpadding="0" cellspacing="0" border="0" class="tdtop3">
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap">
                            <label for="cc_v3_title"><?php echo $BL['be_cc_v3_title']; ?></label>:&nbsp;
                        </td>
                        <td class="tdbottom3">
                            <input type="text"
                                   name="cc_v3_title"
                                   id="cc_v3_title"
                                   class="width400"
                                   placeholder="<?php echo $BL['cc_v3_title_placeholder']; ?>"
                                   value="<?php echo html($template['cc_v3']['title']); ?>"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop3 nowrap">
                            <label for="cc_v3_description"><?php echo $BL['be_cc_v3_description']; ?></label>:&nbsp;
                        </td>
                        <td class="tdbottom3">
                            <textarea name="cc_v3_description"
                                      rows="3"
                                      id="cc_v3_description"
                                      class="width400 autosize"
                                      placeholder="<?php echo $BL['cc_v3_description_placeholder']; ?>"><?php
                                echo html($template['cc_v3']['description']);
                                ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap">
                            <label for="cc_v3_accept_all"><?php echo $BL['be_cc_v3_accept_all']; ?></label>:&nbsp;
                        </td>
                        <td class="tdbottom3">
                            <input type="text"
                                   name="cc_v3_accept_all"
                                   id="cc_v3_accept_all"
                                   class="width200"
                                   placeholder="<?php echo $BL['cc_v3_accept_all_placeholder']; ?>"
                                   value="<?php echo html($template['cc_v3']['accept_all']); ?>"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap">
                            <label for="cc_v3_accept_necessary"><?php echo $BL['be_cc_v3_accept_necessary']; ?></label>:&nbsp;
                        </td>
                        <td class="tdbottom3">
                            <input type="text"
                                   name="cc_v3_accept_necessary"
                                   id="cc_v3_accept_necessary"
                                   class="width200"
                                   placeholder="<?php echo $BL['cc_v3_accept_necessary_placeholder']; ?>"
                                   value="<?php echo html($template['cc_v3']['accept_necessary']); ?>"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap">
                            <label for="cc_v3_accept_selected"><?php echo $BL['be_cc_v3_accept_selected']; ?></label>:&nbsp;
                        </td>
                        <td class="tdbottom3">
                            <input type="text"
                                   name="cc_v3_accept_selected"
                                   id="cc_v3_accept_selected"
                                   class="width200"
                                   placeholder="<?php echo $BL['cc_v3_accept_selected_placeholder']; ?>"
                                   value="<?php echo html($template['cc_v3']['accept_selected']); ?>"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap">
                            <label for="cc_v3_reject_all"><?php echo $BL['be_cc_v3_reject_all']; ?></label>:&nbsp;
                        </td>
                        <td class="tdbottom3">
                            <input type="text"
                                   name="cc_v3_reject_all"
                                   id="cc_v3_reject_all"
                                   class="width200"
                                   placeholder="<?php echo $BL['cc_v3_reject_all_placeholder']; ?>"
                                   value="<?php echo html($template['cc_v3']['reject_all']); ?>"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap">
                            <label for="cc_v3_customize"><?php echo $BL['be_cc_v3_customize']; ?></label>:&nbsp;
                        </td>
                        <td class="tdbottom3">
                            <input type="text"
                                   name="cc_v3_customize"
                                   id="cc_v3_customize"
                                   class="width200"
                                   placeholder="<?php echo $BL['cc_v3_customize_placeholder']; ?>"
                                   value="<?php echo html($template['cc_v3']['customize']); ?>"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap">
                            <label for="cc_v3_more"><?php echo $BL['be_cc_v3_more']; ?></label>:&nbsp;
                        </td>
                        <td class="tdbottom3">
                            <input type="text"
                                   name="cc_v3_more"
                                   id="cc_v3_more"
                                   class="width400"
                                   placeholder="<?php echo $BL['cc_v3_more_placeholder']; ?>"
                                   value="<?php echo html($template['cc_v3']['more']); ?>"
                            />
                        </td>
                    </tr>
                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap">
                            <label for="cc_v3_link"><?php echo $BL['be_cc_v3_link']; ?></label>:&nbsp;
                        </td>
                        <td class="tdbottom10">
                            <input type="text"
                                   name="cc_v3_link"
                                   id="cc_v3_link"
                                   class="width400"
                                   placeholder="https://example.com/cookie-policy | cookie-policy"
                                   value="<?php echo html($template['cc_v3']['link']); ?>"
                            />
                        </td>
                    </tr>

                    <tr>
                        <td align="right" class="chatlist tdtop2 nowrap">
                            <?php echo $BL['be_cc_v3_sections']; ?>:&nbsp;
                        </td>
                        <td class="tdbottom5">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <!-- General -->
                                <tr>
                                    <td class="nowrap chatlist" align="right">
                                        <strong><?php echo $BL['be_cc_v3_section_general']; ?>&nbsp;</strong>
                                    </td>
                                    <td>
                                        <input name="cc_v3_general_active"
                                               id="cc_v3_general_active"
                                               type="checkbox"
                                               value="1"<?php is_checked($template['cc_v3']['sections']['general']['active'], 1); ?>
                                        />
                                    </td>
                                    <td class="width300">
                                        <label for="cc_v3_general_active">
                                            <?php echo $BL['be_cc_v3_sections_active']; ?>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_general_title"><?php echo $BL['be_cc_v3_sections_title']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom3 tdtop2" colspan="2">
                                        <input type="text"
                                               name="cc_v3_general_title"
                                               id="cc_v3_general_title"
                                               class="width300"
                                               placeholder="<?php echo $BL['be_cc_v3_section_general_title_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['sections']['general']['title']); ?>"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_general_description"><?php echo $BL['be_cc_v3_sections_description']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom5 tdtop2" colspan="2">
                                        <textarea name="cc_v3_general_description"
                                                  rows="3"
                                                  id="cc_v3_general_description"
                                                  class="width300 autosize"
                                                  placeholder="<?php echo $BL['be_cc_v3_section_general_description_placeholder']; ?>"><?php
                                            echo html($template['cc_v3']['sections']['general']['description']);
                                        ?></textarea>
                                    </td>
                                </tr>

                                <!-- Strictly necessary cookies -->
                                <tr>
                                    <td class="nowrap chatlist" align="right">
                                        <strong><?php echo $BL['be_cc_v3_section_necessary']; ?>&nbsp;</strong>
                                    </td>
                                    <td>
                                        <input type="checkbox"
                                               value="1"
                                               checked="checked"
                                               disabled="disabled"
                                        />
                                        <input type="hidden" name="cc_v3_necessary_active" value="1" /><!-- always active -->
                                    </td>
                                    <td class="width300">
                                        <?php echo $BL['be_cc_v3_sections_active']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_necessary_title"><?php echo $BL['be_cc_v3_sections_title']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom3 tdtop2" colspan="2">
                                        <input type="text"
                                               name="cc_v3_necessary_title"
                                               id="cc_v3_necessary_title"
                                               class="width300"
                                               placeholder="<?php echo $BL['be_cc_v3_section_necessary_title_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['sections']['necessary']['title']); ?>"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_necessary_description"><?php echo $BL['be_cc_v3_sections_description']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom5 tdtop2" colspan="2">
                                        <textarea name="cc_v3_necessary_description"
                                                  rows="3"
                                                  id="cc_v3_necessary_description"
                                                  class="width300 autosize"
                                                  placeholder="<?php echo $BL['be_cc_v3_section_necessary_description_placeholder']; ?>"><?php
                                            echo html($template['cc_v3']['sections']['necessary']['description']);
                                        ?></textarea>
                                    </td>
                                </tr>

                                <!-- Functional cookies -->
                                <tr>
                                    <td class="nowrap chatlist" align="right">
                                        <strong><?php echo $BL['be_cc_v3_section_functional']; ?>&nbsp;</strong>
                                    </td>
                                    <td>
                                        <input name="cc_v3_functionality_active"
                                               id="cc_v3_functionality_active"
                                               type="checkbox"
                                               value="1"<?php is_checked($template['cc_v3']['sections']['functionality']['active'], 1); ?>
                                        />
                                    </td>
                                    <td class="width300">
                                        <label for="cc_v3_functionality_active">
                                            <?php echo $BL['be_cc_v3_sections_active']; ?>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_functionality_title"><?php echo $BL['be_cc_v3_sections_title']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom3 tdtop2" colspan="2">
                                        <input type="text"
                                               name="cc_v3_functionality_title"
                                               id="cc_v3_functionality_title"
                                               class="width300"
                                               placeholder="<?php echo $BL['be_cc_v3_section_functional_title_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['sections']['functionality']['title']); ?>"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_functionality_description"><?php echo $BL['be_cc_v3_sections_description']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom5 tdtop2" colspan="2">
                                         <textarea name="cc_v3_functionality_description"
                                                   rows="3"
                                                   id="cc_v3_functionality_description"
                                                   class="width300 autosize"
                                                   placeholder="<?php echo $BL['be_cc_v3_section_functional_description_placeholder']; ?>"><?php
                                             echo html($template['cc_v3']['sections']['functionality']['description']);
                                         ?></textarea>
                                    </td>
                                </tr>

                                <!-- Performance and Analytics cookies -->
                                <tr>
                                    <td class="nowrap chatlist" align="right">
                                        <strong><?php echo $BL['be_cc_v3_section_analytics']; ?>&nbsp;</strong>
                                    </td>
                                    <td>
                                        <input name="cc_v3_analytics_active"
                                               id="cc_v3_analytics_active"
                                               type="checkbox"
                                               value="1"<?php is_checked($template['cc_v3']['sections']['analytics']['active'], 1); ?>
                                        />
                                    </td>
                                    <td class="width300">
                                        <label for="cc_v3_analytics_active">
                                            <?php echo $BL['be_cc_v3_sections_active']; ?>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_analytics_title"><?php echo $BL['be_cc_v3_sections_title']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom3 tdtop2" colspan="2">
                                        <input type="text"
                                               name="cc_v3_analytics_title"
                                               id="cc_v3_analytics_title"
                                               class="width300"
                                               placeholder="<?php echo $BL['be_cc_v3_section_analytics_title_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['sections']['analytics']['title']); ?>"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_analytics_description"><?php echo $BL['be_cc_v3_sections_description']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom5 tdtop2" colspan="2">
                                         <textarea name="cc_v3_analytics_description"
                                                   rows="3"
                                                   id="cc_v3_analytics_description"
                                                   class="width300 autosize"
                                                   placeholder="<?php echo $BL['be_cc_v3_section_analytics_description_placeholder']; ?>"><?php
                                             echo html($template['cc_v3']['sections']['analytics']['description']);
                                         ?></textarea>
                                    </td>
                                </tr>

                                <!-- Advertising and marketing cookies -->
                                <tr>
                                    <td class="nowrap chatlist" align="right">
                                        <strong><?php echo $BL['be_cc_v3_section_marketing']; ?>&nbsp;</strong>
                                    </td>
                                    <td>
                                        <input name="cc_v3_marketing_active"
                                               id="cc_v3_marketing_active"
                                               type="checkbox"
                                               value="1"<?php is_checked($template['cc_v3']['sections']['marketing']['active'], 1); ?>
                                        />
                                    </td>
                                    <td class="width300">
                                        <label for="cc_v3_marketing_active">
                                            <?php echo $BL['be_cc_v3_sections_active']; ?>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_marketing_title"><?php echo $BL['be_cc_v3_sections_title']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom3 tdtop2" colspan="2">
                                        <input type="text"
                                               name="cc_v3_marketing_title"
                                               id="cc_v3_marketing_title"
                                               class="width300"
                                               placeholder="<?php echo $BL['be_cc_v3_section_marketing_title_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['sections']['marketing']['title']); ?>"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_marketing_description"><?php echo $BL['be_cc_v3_sections_description']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom5 tdtop2" colspan="2">
                                         <textarea name="cc_v3_marketing_description"
                                                   rows="3"
                                                   id="cc_v3_marketing_description"
                                                   class="width300 autosize"
                                                   placeholder="<?php echo $BL['be_cc_v3_section_marketing_description_placeholder']; ?>"><?php
                                             echo html($template['cc_v3']['sections']['marketing']['description']);
                                         ?></textarea>
                                    </td>
                                </tr>

                                <!-- Social media cookies -->
                                <tr>
                                    <td class="nowrap chatlist" align="right">
                                        <strong><?php echo $BL['be_cc_v3_section_social']; ?>&nbsp;</strong>
                                    </td>
                                    <td>
                                        <input name="cc_v3_social_active"
                                               id="cc_v3_social_active"
                                               type="checkbox"
                                               value="1"<?php is_checked($template['cc_v3']['sections']['social']['active'], 1); ?>
                                        />
                                    </td>
                                    <td class="width300">
                                        <label for="cc_v3_social_active">
                                            <?php echo $BL['be_cc_v3_sections_active']; ?>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_social_title"><?php echo $BL['be_cc_v3_sections_title']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom3 tdtop2" colspan="2">
                                        <input type="text"
                                               name="cc_v3_social_title"
                                               id="cc_v3_social_title"
                                               class="width300"
                                               placeholder="<?php echo $BL['be_cc_v3_section_social_title_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['sections']['social']['title']); ?>"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_social_description"><?php echo $BL['be_cc_v3_sections_description']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom5 tdtop2" colspan="2">
                                         <textarea name="cc_v3_social_description"
                                                   rows="3"
                                                   id="cc_v3_social_description"
                                                   class="width300 autosize"
                                                   placeholder="<?php echo $BL['be_cc_v3_section_social_description_placeholder']; ?>"><?php
                                             echo html($template['cc_v3']['sections']['social']['description']);
                                         ?></textarea>
                                    </td>
                                </tr>

                                <!-- More information -->
                                <tr>
                                    <td class="nowrap chatlist" align="right">
                                        <strong><?php echo $BL['be_cc_v3_section_more']; ?>&nbsp;</strong>
                                    </td>
                                    <td>
                                        <input name="cc_v3_more_active"
                                               id="cc_v3_more_active"
                                               type="checkbox"
                                               value="1"<?php is_checked($template['cc_v3']['sections']['more']['active'], 1); ?>
                                        />
                                    </td>
                                    <td class="width300">
                                        <label for="cc_v3_more_active">
                                            <?php echo $BL['be_cc_v3_sections_active']; ?>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_more_title"><?php echo $BL['be_cc_v3_sections_title']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom3 tdtop2" colspan="2">
                                        <input type="text"
                                               name="cc_v3_more_title"
                                               id="cc_v3_more_title"
                                               class="width300"
                                               placeholder="<?php echo $BL['be_cc_v3_section_more_title_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['sections']['more']['title']); ?>"
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_more_description"><?php echo $BL['be_cc_v3_sections_description']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom5 tdtop2" colspan="2">
                                         <textarea name="cc_v3_more_description"
                                                   rows="3"
                                                   id="cc_v3_more_description"
                                                   class="width300 autosize"
                                                   placeholder="<?php echo $BL['be_cc_v3_section_more_description_placeholder']; ?>"><?php
                                             echo html($template['cc_v3']['sections']['more']['description']);
                                         ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="right" class="chatlist tdtop4 nowrap">
                            <label for="cc_v3_theme"><?php echo $BL['be_cc_v3_theme']; ?></label>:&nbsp;
                        </td>
                        <td class="tdbottom6">
                            <input type="text"
                                   name="cc_v3_theme"
                                   id="cc_v3_theme"
                                   class="width400"
                                   placeholder="light (<?= $BL['be_cc_v3_default']; ?>) <?= $BL['be_fsearch_or']; ?> dark <?= $BL['be_fsearch_or']; ?> custom&hellip;"
                                   title="<?php echo $BL['be_admin_tmpl_default']; ?>: light (<?= $BL['be_cc_v3_builtin'] . ', ' . $BL['be_cc_v3_default']; ?>), dark (<?= $BL['be_cc_v3_builtin']; ?>), custom"
                                   value="<?php echo html($template['cc_v3']['theme']) ?>"
                            />
                        </td>
                    </tr>

                    <tr>
                        <td align="right" class="chatlist tdtop5 nowrap">
                            <?php echo $BL['be_cc_v3_consent_modal']; ?>:&nbsp;
                        </td>
                        <td class="tdbottom5">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_consent_layout"><?php echo $BL['be_cc_v3_layout']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom3 tdtop2" colspan="4">
                                        <select name="cc_v3_consent_layout" id="cc_v3_consent_layout">
                                            <option value="box"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'box'); ?>>Box</option>
                                            <option value="box inline"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'box inline'); ?>>Box Inline</option>
                                            <option value="box wide"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'box wide'); ?>>Box Wide</option>
                                            <option value="cloud"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'cloud'); ?>>Cloud</option>
                                            <option value="cloud inline"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'cloud inline'); ?>>Cloud Inline</option>
                                            <option value="bar"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'bar'); ?>>Bar</option>
                                            <option value="bar inline"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'bar inline'); ?>>Bar Inline</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_consent_position"><?php echo $BL['be_cc_v3_position']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom3 tdtop2" colspan="4">
                                        <?php
                                        if (in_array($template['cc_v3']['gui']['consent']['layout'], ['bar', 'bar inline'])) {
                                            $cc_v3_consent_position_nobar = ' style="display:none;"';
                                            $cc_v3_consent_position_bar = '';
                                        } else {
                                            $cc_v3_consent_position_nobar = '';
                                            $cc_v3_consent_position_bar = ' style="display:none;"';
                                        }
                                        ?>
                                        <select name="cc_v3_consent_position" id="cc_v3_consent_position">
                                            <option value="top left"<?php is_selected($template['cc_v3']['gui']['consent']['position'], 'top left'); echo $cc_v3_consent_position_nobar; ?> class="v3_consent-no-bar">
                                                <?= $BL['be_cc_v3_top_left']; ?>
                                            </option>
                                            <option value="top center"<?php is_selected($template['cc_v3']['gui']['consent']['position'], 'top center'); echo $cc_v3_consent_position_nobar;  ?> class="v3_consent-no-bar">
                                                <?= $BL['be_cc_v3_top_center']; ?>
                                            </option>
                                            <option value="top right"<?php is_selected($template['cc_v3']['gui']['consent']['position'], 'top right'); echo $cc_v3_consent_position_nobar;  ?> class="v3_consent-no-bar">
                                                <?= $BL['be_cc_v3_top_right']; ?>
                                            </option>
                                            <option value="middle left"<?php is_selected($template['cc_v3']['gui']['consent']['position'], 'middle left'); echo $cc_v3_consent_position_nobar;  ?> class="v3_consent-no-bar">
                                                <?= $BL['be_cc_v3_middle_left']; ?>
                                            </option>
                                            <option value="middle center"<?php is_selected($template['cc_v3']['gui']['consent']['position'], 'middle center'); echo $cc_v3_consent_position_nobar;  ?> class="v3_consent-no-bar">
                                                <?= $BL['be_cc_v3_middle_center']; ?>
                                            </option>
                                            <option value="middle right"<?php is_selected($template['cc_v3']['gui']['consent']['position'], 'middle right'); echo $cc_v3_consent_position_nobar;  ?> class="v3_consent-no-bar">
                                                <?= $BL['be_cc_v3_middle_right']; ?>
                                            </option>
                                            <option value="bottom left"<?php is_selected($template['cc_v3']['gui']['consent']['position'], 'bottom left'); echo $cc_v3_consent_position_nobar;  ?> class="v3_consent-no-bar">
                                                <?= $BL['be_cc_v3_bottom_left']; ?>
                                            </option>
                                            <option value="bottom center"<?php is_selected($template['cc_v3']['gui']['consent']['position'], 'bottom center'); echo $cc_v3_consent_position_nobar;  ?> class="v3_consent-no-bar">
                                                <?= $BL['be_cc_v3_bottom_center']; ?>
                                            </option>
                                            <option value="bottom right"<?php is_selected($template['cc_v3']['gui']['consent']['position'], 'bottom right'); echo $cc_v3_consent_position_nobar;  ?> class="v3_consent-no-bar">
                                                <?= $BL['be_cc_v3_bottom_right']; ?>
                                            </option>
                                            <option value="top"<?php is_selected($template['cc_v3']['gui']['consent']['position'], 'top'); echo $cc_v3_consent_position_bar;  ?> class="v3_consent-bar">
                                                <?= $BL['be_cc_v3_top']; ?>
                                            </option>
                                            <option value="bottom"<?php is_selected($template['cc_v3']['gui']['consent']['position'], 'bottom'); echo $cc_v3_consent_position_bar; ?> class="v3_consent-bar">
                                                <?= $BL['be_cc_v3_bottom']; ?>
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="chatlist tdtop6 nowrap">
                                        &nbsp;
                                    </td>
                                    <td>
                                        <input name="cc_v3_consent_flip"
                                               id="cc_v3_consent_flip"
                                               type="checkbox"
                                               value="1"<?php is_checked($template['cc_v3']['gui']['consent']['btn_flip'], 1); ?>
                                        />
                                    </td>
                                    <td class="width100 tdright10">
                                        <label for="cc_v3_consent_flip"><?php echo $BL['be_cc_v3_btn_flip'] ?></label>
                                    </td>
                                    <td>
                                        <input name="cc_v3_consent_equal"
                                               id="cc_v3_consent_equal"
                                               type="checkbox"
                                               value="1"<?php is_checked($template['cc_v3']['gui']['consent']['btn_equal'], 1); ?>
                                        />
                                    </td>
                                    <td class="width100">
                                        <label for="cc_v3_consent_equal"><?php echo $BL['be_cc_v3_btn_equal'] ?></label>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="right" class="chatlist tdtop5 nowrap">
                            <?php echo $BL['be_cc_v3_preferences_modal']; ?>:&nbsp;
                        </td>
                        <td class="tdbottom10">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_preferences_layout"><?php echo $BL['be_cc_v3_layout']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom3 tdtop2" colspan="4">
                                        <select name="cc_v3_preferences_layout" id="cc_v3_preferences_layout">
                                            <option value="box"<?php is_selected($template['cc_v3']['gui']['preferences']['layout'], 'box'); ?>>
                                                Box
                                            </option>
                                            <option value="bar"<?php is_selected($template['cc_v3']['gui']['preferences']['layout'], 'bar'); ?>>
                                                Bar
                                            </option>
                                            <option value="bar wide"<?php is_selected($template['cc_v3']['gui']['preferences']['layout'], 'bar wide'); ?>>
                                                Bar Wide
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" class="chatlist tdtop6 nowrap">
                                        <label for="cc_v3_preferences_position"><?php echo $BL['be_cc_v3_position']; ?></label>:&nbsp;
                                    </td>
                                    <td class="tdbottom3 tdtop2" colspan="4">
                                        <select name="cc_v3_preferences_position" id="cc_v3_preferences_position"<?php
                                        if ($template['cc_v3']['gui']['preferences']['layout'] === 'box'): ?> disabled="disabled"<?php endif;
                                        ?>>
                                            <option value="left"<?php is_selected($template['cc_v3']['gui']['preferences']['position'], 'left'); ?>>
                                                <?= $BL['be_cc_v3_left']; ?>
                                            </option>
                                            <option value="right"<?php is_selected($template['cc_v3']['gui']['preferences']['position'], 'right'); ?>>
                                                <?= $BL['be_cc_v3_right']; ?>
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="chatlist tdtop6 nowrap">
                                        &nbsp;
                                    </td>
                                    <td>
                                        <input name="cc_v3_preferences_flip"
                                               id="cc_v3_preferences_flip"
                                               type="checkbox"
                                               value="1"<?php is_checked($template['cc_v3']['gui']['preferences']['btn_flip'], 1); ?>
                                        />
                                    </td>
                                    <td class=" width100 tdright10">
                                        <label for="cc_v3_preferences_flip"><?php echo $BL['be_cc_v3_btn_flip'] ?></label>
                                    </td>
                                    <td>
                                        <input name="cc_v3_preferences_equal"
                                               id="cc_v3_preferences_equal"
                                               type="checkbox"
                                               value="1"<?php is_checked($template['cc_v3']['gui']['preferences']['btn_equal'], 1); ?>
                                        />
                                    </td>
                                    <td class="width100">
                                        <label for="cc_v3_preferences_equal"><?php echo $BL['be_cc_v3_btn_equal'] ?></label>
                                    </td>
                                </tr>
                            </table>
                        </td>
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
if(!empty($jsOnChange)) {

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
                // Only Cookie Consent v3 or Cookie Consent v2 can be enabled at the same time
                // Disable Cookie Consent v3 if Cookie Consent v2 is enabled
                $('#template_cc_v3').prop('checked', false);
                $('#template-cc_v3-form').hide();
            } else {
                $('#template-cc-form').hide();
            }
        });
        $('#template_cc_v3').on('change', function(){
            if($(this).is(':checked')) {
                $('#template-cc_v3-form').show();
                // Only Cookie Consent v3 or Cookie Consent v2 can be enabled at the same time
                // Disable Cookie Consent v2 if Cookie Consent v3 is enabled
                $('#template_cookie_consent').prop('checked', false);
                $('#template-cc-form').hide();
            } else {
                $('#template-cc_v3-form').hide();
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
        $('#cc_v3_preferences_layout').on('change', function(){
            const $value = $(this).val();
            if($value === 'box') {
                $('#cc_v3_preferences_position').prop('disabled', 'disabled');
            } else {
                $('#cc_v3_preferences_position').removeAttr('disabled');
            }
        });
        $('#cc_v3_consent_layout').on('change', function(){
            let $value = $(this).val();
            let $consent_position = $('#cc_v3_consent_position');
            if($value === 'bar' || $value === 'bar inline') {
                let $consent_position_bar = $consent_position.children('.v3_consent-bar');
                $consent_position_bar.show();
                $consent_position.children('.v3_consent-no-bar').hide();
                $consent_position_bar.first().prop('selected', true);
                $consent_position_bar.first().attr('selected', 'selected');
            } else {
                let $consent_position_nobar = $consent_position.children('.v3_consent-no-bar');
                $consent_position_nobar.show();
                $consent_position.children('.v3_consent-bar').hide();
                $consent_position_nobar.first().prop('selected', true);
                $consent_position_nobar.first().attr('selected', 'selected');
            }
        });
    });
</script>
<?php

}
