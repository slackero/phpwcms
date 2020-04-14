<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2019, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

reset($cmsgo['js_lib']); // reset $cmsgo['js_lib'] to get first element as default

$template = array("name" => '', "default" => 0, "layout" => '', "css" => array(), "htmlhead" => '', "jsonload" => '', "headertext" => '', "maintext" => '', "footertext" => '', "lefttext" => '', "righttext" => '', "errortext" => '', "htmlhead_file" => '', "headertext_file" => '', "maintext_file" => '', "footertext_file" => '', "lefttext_file" => '', "righttext_file" => '', "errortext_file" => '', 'feloginurl' => '', 'jslib' => key($cmsgo['js_lib']), // take the most current
    'jslibload' => 0, 'frontendjs' => 0, 'googleapi' => 1, 'onepage' => 0, 'ie8ignore' => 0, 'cookie_consent' => array('enable' => 0, 'message' => $BL['cookie_consent_message'], 'dismiss' => $BL['cookie_consent_dismiss'], 'more' => $BL['cookie_consent_more'], 'link' => '', 'theme' => 'light-bottom',), 'tracking_ga' => array('enable' => 0, 'id' => '', 'anonymize' => CMSGO_GDPR_MODE ? 1 : 0, 'optout' => CMSGO_GDPR_MODE ? 1 : 0,), 'tracking_piwik' => array('enable' => 0, 'id' => '', 'url' => ''),);

initJQuery();

if (!isset($_GET["s"])) {

    ?>
    <h1 class="text-center text-sm-left"><?php echo $BL['be_subnav_admin_templates'] ?></h1>
    <div class="card">
        <div class="card-header"><h2><i class="fa fa-list"></i> <?php echo $BL['be_admin_tmpl_title'] ?></h2></div>
        <div class="card-body">
            <table class="table table-striped table-sm mb-4" border="0" cellpadding="0" cellspacing="0" summary="">
                <?php
                // loop listing available templates
                $sql = "SELECT * FROM " . DB_PREPEND . "cmsgo_template WHERE template_trash=0 ORDER BY template_default DESC, template_name";
                $result = _dbQuery($sql);
                $row_count = 0;
                if (isset($result[0]['template_id'])) {
                    foreach ($result as $row) {

                        $edit_link = 'do=admin&amp;p=11&amp;s=' . $row["template_id"] . '&amp;t=' . $row["template_type"];

                        echo "<tr>\n";
                        echo '<td><a href="cmsgo.php?' . $edit_link;
                        echo '"><strong>' . html($row["template_name"]) . "</strong>";
                        if($row["template_default"]) {
                            echo " (" . $BL['be_admin_tmpl_default'] . ")";
                        }
                        echo "</a></td>" . '<td class="text-right text-nowrap">';
                        echo '<a class="btn btn-blue btn-sm mr-1" role="button" data-toggle="tooltip" title="' . $BL['be_tt_edit'] . '" href="cmsgo.php?' . $edit_link;
                        echo '"><i class="fa fa-pencil-alt"></i></a>';

                        echo '<a class="btn btn-blue btn-sm mr-1" role="button" data-toggle="tooltip" title="' . $BL['be_tt_duplicate'] . '" href="cmsgo.php?' . $edit_link . '&amp;c=1'; // c=1 -> do copy
                        echo '"><i class="fa fa-copy"></i></a>';

                        echo '<a class="btn btn-danger btn-sm" role="button" data-toggle="tooltip" href="include/inc_act/act_frontendsetup.php?do=2|' . $row["template_id"] . '" ';
                        echo 'title="' . $BL['be_cnt_delete'] . ': ' . html($row["template_name"]) . '" ';
                        echo 'onclick="return confirm(\'' . js_singlequote($BL['be_cnt_delete'] . ': ' . html($row["template_name"])) . '\');">';
                        echo '<i class="far fa-trash-alt" aria-hidden="true"></i></a>';
                        echo "</td>\n</tr>\n";

                        $row_count++;
                    }
                } // end listing

                ?>
            </table>
            <form action="cmsgo.php?do=admin&amp;p=11&amp;s=0" method="post">
                <input type="submit" value="<?php echo $BL['be_admin_tmpl_add'] ?>" class="btn btn-blue btn-sm"
                       title="<?php echo $BL['be_admin_tmpl_add'] ?>"/>
            </form>
        </div>
    </div>
    <?php
} else {
    // edit template dialog
    $template["id"] = intval($_GET["s"]);

    $createcopy = isset($_GET["c"]) ? intval($_GET["c"]) : 0;

    if (isset($_POST["template_id"])) {

        $createcopy = empty($_POST["c"]) ? 0 : intval($_POST["c"]); // ERICH COPY TEMPLATE 08.06.2005

        // read the create or edit template form data
        $template["id"] = intval($_POST["template_id"]);
        $template["default"] = empty($_POST["template_setdefault"]) ? 0 : 1;
        $template["layout"] = intval($_POST["template_layout"]);
        $template["name"] = clean_slweg($_POST["template_name"], 150);
        if (empty($template["name"])) {
            $template["name"] = "template_" . generic_string(3);
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
        if (!empty($_POST['template_cc_message'])) {
            $template['cookie_consent']['message'] = slweg($_POST['template_cc_message']);
        }
        if (!empty($_POST['template_cc_dismiss'])) {
            $template['cookie_consent']['dismiss'] = slweg($_POST['template_cc_dismiss']);
        }
        if (!empty($_POST['template_cc_more'])) {
            $template['cookie_consent']['more'] = slweg($_POST['template_cc_more']);
        }
        if (!empty($_POST['template_cc_link'])) {
            $template['cookie_consent']['link'] = slweg($_POST['template_cc_link']);
        }
        if (isset($_POST['template_cc_theme'])) {
            $template['cookie_consent']['theme'] = clean_slweg($_POST['template_cc_theme']);
        }
        $template['tracking_ga']['enable'] = empty($_POST['template_ga']) ? 0 : 1;
        $template['tracking_ga']['id'] = clean_slweg($_POST["template_ga_id"]);
        $template['tracking_ga']['anonymize'] = empty($_POST['template_ga_anonymize']) ? 0 : 1;
        $template['tracking_ga']['optout'] = empty($_POST['template_ga_optout']) ? 0 : 1;
        if (empty($template['tracking_ga']['id'])) {
            $template['tracking_ga']['enable'] = 0;
        }
        $template['tracking_piwik']['enable'] = empty($_POST['template_piwik']) ? 0 : 1;
        $template['tracking_piwik']['id'] = intval($_POST["template_piwik_id"]);
        $template['tracking_piwik']['url'] = clean_slweg($_POST["template_piwik_url"]);
        if (!empty($template['tracking_piwik']['url'])) {
            $template['tracking_piwik']['url'] = trim(preg_replace('/.*?:\/\//i', '', trim($template['tracking_piwik']['url'], '/')));
        }
        if (empty($template['tracking_piwik']['id']) || empty($template['tracking_piwik']['url'])) {
            $template['tracking_piwik']['enable'] = 0;
        }

        // now browse custom blocks if available
        if (!empty($_POST['customblock'])) {

            $template['customblock'] = clean_slweg($_POST["customblock"]);
            $temp_customblock = explode(',', $template['customblock']);
            foreach ($temp_customblock as $value) {

                $template['customblock_' . $value] = slweg($_POST['template_customblock_' . $value]);
                $template['customblock_' . $value . '_file'] = slweg($_POST['template_customblock_' . $value . '_file']);
            }
        }

        if ($template["id"] && empty($createcopy)) {
            // if ID <> 0 then get template info from database
            $query_mode = 'UPDATE';
            $sql = "UPDATE " . DB_PREPEND . "cmsgo_template SET " . "template_name='" . aporeplace($template["name"]) . "', " . "template_default=" . $template["default"] . ", " . "template_var='" . aporeplace(serialize($template)) . "' " . "WHERE template_id=" . $template["id"];
        } else {
            // if ID = 0 then show create new template form
            $query_mode = 'INSERT';
            $sql = "INSERT INTO " . DB_PREPEND . "cmsgo_template (" . "template_name, template_default, template_var) VALUES ('" . aporeplace($template["name"]) . "', " . $template["default"] . ", '" . aporeplace(serialize($template)) . "')";
        }
        // update or insert data entry
        $result = _dbQuery($sql, $query_mode);

        if ($query_mode === 'INSERT' && !empty($result['INSERT_ID'])) {
            $template["id"] = $result['INSERT_ID'];
        }

        //now proof for default template definition
        if ($template["default"]) {
            _dbQuery("UPDATE " . DB_PREPEND . "cmsgo_template SET template_default=0 WHERE template_id != " . $template["id"], 'UPDATE');
        }
        update_cache();
        headerRedirect(CMSGO_URL . 'cmsgo.php?' . get_token_get_string('csrftoken') . '&do=admin&p=11&s=' . $template["id"]);
    }

    if ($template["id"]) {
        // read the given template datas from db
        $sql = "SELECT * FROM " . DB_PREPEND . "cmsgo_template WHERE template_id=" . $template["id"] . " LIMIT 1";
        $result = _dbQuery($sql);
        if (isset($result[0]['template_id'])) {
            if (($result[0]["template_var"] = @unserialize($result[0]["template_var"]))) {
                $template = array_merge($template, $result[0]["template_var"]);
            };
            $template["id"] = intval($result[0]["template_id"]);
            $template["default"] = $result[0]["template_default"];

            // compatibility for older releases where only 1 css file could be stored per template
            if (is_string($template['css'])) {
                $template['css'] = array($template['css']);
            }
        }
    }

    // show form
    ?>
    <script type="text/javascript">
        function doPageLayoutChange() {
            if (confirm('<?php echo $BL['be_admin_template_jswarning'] ?>')) {
                document.blocks.submit();
                return true;
            }
            return false;
        }
    </script>
    <form action="cmsgo.php?do=admin&amp;p=11&amp;s=<?php echo $template["id"] ?>" method="post" name="blocks"
          target="_self" id="blocks">
        <div class="row align-items-center">
            <div class="col col-sm-auto text-center text-sm-left">
                <h1><?php echo $BL['be_subnav_admin_templates'] ?></h1>
            </div>
            <div class="col-12 col-sm text-center text-sm-right mb-3">
                <div class="form-group">
                    <input name="template_id" type="hidden" value="<?php echo $template["id"] ?>"/>
                    <input name="Submit" type="submit" class="btn btn-sm btn-blue"
                           value="<?php echo $BL['be_admin_tmpl_button'] ?>"/>
                    <input type="button" class="btn btn-sm btn-blue" value="<?php echo $BL['be_admin_struct_close'] ?>"
                           onclick="location.href='cmsgo.php?do=admin&amp;p=11';"/>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h2>
                    <i class="fa fa-list"></i>
                    <?php echo(empty($createcopy) ? $BL['be_admin_tmpl_edit'] : $BL['be_admin_tmpl_copy']) ?>
                    : <?php echo ($template["id"]) ? html($template["name"]) : $BL['be_admin_tmpl_new']; ?>
                </h2>
                <input type="hidden" name="c" value="<?php echo $createcopy; ?>"/>
            </div>
            <div class="card-body">
                <div class="form-group form-row align-items-center">
                    <label for="layout_name"
                           class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_tmpl_name'] ?></label>
                    <div class="col-sm-7">
                        <?php
                        if (empty($createcopy)) {
                            echo '<input name="template_name" type="text" class="form-control form-control-sm" id="template_name" value="' . html($template["name"]) . '" >';
                        } else {
                            echo '<input name="template_name" type="text" class="form-control form-control-sm is-invalid" id="template_name" value="' . html($template["name"]) . '_' . generic_string(2) . '" size="50" maxlength="150">';
                        }
                        ?>
                    </div>
                    <div class="col-sm-3 mt-2 mt-sm-0">
                        <div class="form-check">
                            <input class="form-check-input" name="template_setdefault" type="checkbox"
                                   id="template_setdefault"
                                   value="1" <?php is_checked(empty($createcopy) ? $template["default"] : 0, 1) ?> />
                            <label class="form-check-label"
                                   for="template_setdefault"><?php echo $BL['be_admin_tmpl_default'] ?></label>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group form-row align-items-center">
                    <label for="be_admin_tmpl_layout"
                           class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_tmpl_layout'] ?></label>
                    <div class="col-sm-5">
                        <?php
                        // get available page layout list
                        $jsOnChange = '';
                        $opt = "";
                        $sql = "SELECT * FROM " . DB_PREPEND . "cmsgo_pagelayout WHERE pagelayout_trash=0 ORDER BY pagelayout_default DESC";
                        $result = _dbQuery($sql);
                        if (isset($result[0]['pagelayout_id'])) {
                            foreach ($result as $row) {
                                $opt .= '<option value="' . $row['pagelayout_id'] . '"';
                                if ($row['pagelayout_id'] == $template["layout"]) {
                                    $opt .= ' selected="selected"';
                                    // try to get additional custom blocks from selected page layout
                                    $custom_blocks = unserialize($row['pagelayout_var']);
                                    $custom_blocks = explode(', ', trim($custom_blocks['layout_customblocks']));

                                    if (is_array($custom_blocks) && count($custom_blocks) && $custom_blocks[0] != '') {
                                        $jsOnChange = ' onChange="doPageLayoutChange();"';
                                    } else {
                                        $jsOnChange = '';
                                    }
                                }
                                $opt .= '>' . html($row['pagelayout_name']) . '</option>';
                            }
                        }
                        if ($opt) {
                            echo '<select name="template_layout" class="custom-select form-control form-control-sm" id="template_layout"' . $jsOnChange . '>';
                            echo $opt;
                            echo '</select>';
                        } else {
                            echo $BL['be_admin_tmpl_nolayout'] . ' (<a href="cmsgo.php?do=admin&p=8&s=0">' . $BL['be_admin_page_add'] . '</a>)';
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group form-row align-items-center">
                    <div class="col-sm-2"></div>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" name="template_onepage" type="checkbox"
                                   id="template_onepage"
                                   value="1" <?php is_checked((!empty($template["onepage"]) ? 1 : 0), 1) ?> />
                            <label class="form-check-label"
                                   for="template_onepage"><?php echo $BL['be_onepage_template'] ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group form-row">
                    <div class="col-sm-2"></div>
                    <div class="col">
                        <?php echo $BL['be_overwrite_default'] ?><br/><strong>include/config/conf.template_default.inc.php</strong>
                    </div>
                </div>

                <hr/>

                <div class="form-group form-row align-items-center">
                    <label for="be_settings"
                           class="col-sm-2 col-form-label text-right"><?php echo $BL['be_settings'] ?></label>
                    <div class="col-sm-5">
                        <select name="template_overwrite" type="text" class="custom-select form-control form-control-sm"
                                id="template_overwrite">
                            <option value=""
                                    style="font-weight:normal;font-style:italic;"><?php echo $BL['be_admin_tmpl_default']; ?></option>
                            <?php
                            // templates for frontend login
                            $tmpllist = get_tmpl_files(CMSGO_TEMPLATE . 'inc_settings/template_default', 'php');
                            if (is_array($tmpllist) && count($tmpllist)) {
                                foreach ($tmpllist as $val) {
                                    $selected_val = (isset($template["overwrite"]) && $val == $template["overwrite"]) ? ' selected="selected"' : '';
                                    $val = html($val);
                                    echo '  <option value="' . $val . '"' . $selected_val . '>' . $val . '</option>' . LF;
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group form-row">
                    <label for="be_admin_tmpl_css"
                           class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_tmpl_css'] ?></label>
                    <div class="col">
                        <select name="template_css[]" multiple class="custom-select form-control form-control-sm"
                                id="template_css">
                            <?php
                            $unselected_css = array();
                            // get css file list
                            if (is_dir(CMSGO_TEMPLATE . "inc_css")) {
                                $css_handle = opendir(CMSGO_TEMPLATE . "inc_css");
                                // browse template CSS diretory and list all available CSS files
                                while ($css_file = readdir($css_handle)) {
                                    if (substr($css_file, 0, 1) !== '.' && is_file(CMSGO_TEMPLATE . "inc_css/" . $css_file) && preg_match('/^[a-z0-9\. \-_]+\.css$/i', $css_file)) {
                                        $unselected_css[$css_file] = $css_file;
                                    }
                                }
                                closedir($css_handle);
                            }
                            // now run the css information
                            foreach ($template["css"] as $value) {
                                if (isset($unselected_css[$value])) {
                                    $css_file = html($value);
                                    echo '      <option value="' . $css_file . '" selected="selected" style="font-weight: bold;">' . $css_file . '&nbsp;&nbsp;</option>' . LF;
                                    unset($unselected_css[$value]);
                                }
                            }
                            foreach ($unselected_css as $value) {
                                $css_file = html($value);
                                echo '      <option value="' . $css_file . '">' . $css_file . '&nbsp;&nbsp;</option>' . LF;
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-auto">
                        <button type="button" class="btn btn-sm btn-blue"
                                onclick="moveOptionUp(document.blocks.template_css);"><i class="fa fa-angle-up fa-fw"
                                                                                         aria-hidden="true"></i>
                        </button>
                        <br/>
                        <button type="button" class="btn btn-sm btn-blue mt-1"
                                onclick="moveOptionDown(document.blocks.template_css);"><i
                                    class="fa fa-angle-down fa-fw" aria-hidden="true"></i></button>
                    </div>
                </div>

                <div class="form-group form-row">
                    <label for="be_admin_tmpl_head"
                           class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_tmpl_head'] ?></label>
                    <div class="col">
                        <?php
                        if (!isset($template["htmlhead_file"])) {
                            $template["htmlhead_file"] = '';
                        }
                        echo get_template_file_select('head', 'template_htmlhead_file', $template["htmlhead_file"]);
                        ?>
                        <textarea name="template_htmlhead" rows="3" class="form-control form-control-sm autosize"
                                  id="template_htmlhead"><?php echo html_entities($template["htmlhead"]); ?></textarea>
                    </div>
                </div>

                <div class="form-group form-row align-items-center">
                    <label for="js_lib" class="col-sm-2 col-form-label text-right"><?php echo $BL['js_lib'] ?></label>
                    <div class="col-sm-5">
                        <select class="custom-select form-control form-control-sm" name="template_jslib"
                                id="template_jslib">
                            <?php
                            foreach ($cmsgo['js_lib'] as $key => $value) {
                                echo '<option value="' . $key . '"';
                                is_selected($template['jslib'], $key);
                                echo '>' . html($value) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-5 mt-2 mt-sm-0">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="template_jslibload"
                                   id="template_jslibload" value="1" <?php is_checked($template['jslibload'], 1); ?> />
                            <label for="template_jslibload"
                                   class="form-check-label"><?php echo $BL['js_lib_alwaysload'] ?></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="template_googleapi"
                                   id="template_googleapi" value="1" <?php is_checked($template['googleapi'], 1); ?> />
                            <label for="template_googleapi"
                                   class="form-check-label"><?php echo $BL['googleapi_load'] ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group form-row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input class="form-check-input" name="template_ie8ignore" id="template_ie8ignore"
                                   type="checkbox" value="1"<?php is_checked($template['ie8ignore'], 1); ?>>
                            <label class="form-check-label"
                                   for="template_ie8ignore"><?php echo $BL['be_ie8ignore'] ?></label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" for="template_ga">
                                <input class="form-check-input" name="template_ga" id="template_ga" type="checkbox"
                                       value="1"<?php is_checked($template['tracking_ga']['enable'], 1); ?>>
                                <?php echo $BL['be_google_analytics_enable']; ?>
                            </label>

                            <div id="ga-tracking"
                                 class="form-group form-row align-items-center mt-3"<?php if (!$template['tracking_ga']['enable']): ?> style="display:none;"<?php endif; ?>>
                                <label class="col-sm-2 col-form-label text-right"
                                       for="be_tracking_id"><?php echo $BL['be_tracking_id']; ?></label>
                                <div class="col-sm-3"><input type="text" name="template_ga_id"
                                                             class="form-control form-control-sm"
                                                             placeholder="UA-XXXXX-Y"
                                                             value="<?php echo html($template['tracking_ga']['id']) ?>"/>
                                </div>

                                <div class="form-check col-sm-5 form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="template_ga_anonymize"
                                           id="template_ga_anonymize"
                                           value="1"<?php is_checked($template['tracking_ga']['anonymize'], 1); ?> />
                                    <label for="be_tracking_anonymize"
                                           class="form-check-label"><?php echo $BL['be_tracking_anonymize']; ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label" for="template_piwik">
                                <input class="form-check-input" name="template_piwik" id="template_piwik"
                                       type="checkbox"
                                       value="1"<?php is_checked($template['tracking_piwik']['enable'], 1); ?>>
                                <?php echo $BL['be_piwik_enable']; ?>
                            </label>

                            <div id="piwik-tracking"
                                 class="form-group form-row align-items-center mt-3"<?php if (!$template['tracking_piwik']['enable']): ?> style="display:none;"<?php endif; ?>>
                                <label class="col-sm-2 col-form-label text-right"
                                       for="be_site_id"><?php echo $BL['be_site_id']; ?></label>
                                <input type="text" name="template_piwik_id" class="form-control col-sm-2"
                                       placeholder="1"
                                       value="<?php echo empty($template['tracking_piwik']['id']) ? '' : $template['tracking_piwik']['id']; ?>"/>
                                <label class="col-sm-2 col-form-label text-right"
                                       for="be_piwik_url"><?php echo $BL['be_piwik_url']; ?></label>
                                <input type="text" name="template_piwik_url" class="form-control col-sm-4"
                                       placeholder="piwik.example.com"
                                       value="<?php echo html($template['tracking_piwik']['url']) ?>"/>
                            </div>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label" for="template_cookie_consent">
                                <input class="form-check-input" name="template_cookie_consent"
                                       id="template_cookie_consent" type="checkbox"
                                       value="1"<?php is_checked($template['cookie_consent']['enable'], 1); ?>>
                                <?php echo $BL['be_cookie_consent_enable'] ?>
                            </label>

                            <div id="cookie-consent"<?php if (!$template['cookie_consent']['enable']): ?> style="display:none;"<?php endif; ?>>
                                <?php if (count($cmsgo['allowed_lang'])): ?>
                                    <em class="mt-2"><small><?php echo $BL['be_cookie_consent_translatable']; ?></em></small>
                                <?php endif; ?>
                                <div class="form-group form-row my-2">
                                    <label class="col-sm-3 col-form-label text-right"
                                           for="be_cookie_consent_message"><?php echo $BL['be_cookie_consent_message']; ?></label>
                                    <div class="col"><textarea name="template_cc_message" rows="3"
                                                               class="form-control form-control-sm autosize"
                                                               placeholder="<?php echo $BL['cookie_consent_message']; ?>"><?php echo html($template['cookie_consent']['message']) ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group form-row mt-2 mb-0">
                                    <label class="col-sm-3 col-form-label text-right"
                                           for="be_cookie_consent_dismiss"><?php echo $BL['be_cookie_consent_dismiss']; ?></label>
                                    <div class="col"><input type="text" name="cookie_consent_dismiss"
                                                            class="form-control form-control-sm"
                                                            placeholder="<?php echo $BL['cookie_consent_dismiss']; ?>"
                                                            value="<?php echo html($template['cookie_consent']['dismiss']) ?>"/>
                                    </div>
                                </div>
                                <div class="form-group form-row my-0">
                                    <label class="col-sm-3 col-form-label text-right"
                                           for="be_cookie_consent_link"><?php echo $BL['be_cookie_consent_more']; ?></label>
                                    <div class="col"><input type="text" name="be_cookie_consent_more"
                                                            class="form-control form-control-sm"
                                                            placeholder="<?php echo $BL['cookie_consent_more']; ?>"
                                                            value="<?php echo html($template['cookie_consent']['more']) ?>"/>
                                    </div>
                                </div>
                                <div class="form-group form-row my-0">
                                    <label class="col-sm-3 col-form-label text-right"
                                           for="be_cookie_consent_more"><?php echo $BL['be_cookie_consent_link']; ?></label>
                                    <div class="col"><input type="text" name="be_cookie_consent_link"
                                                            class="form-control form-control-sm"
                                                            placeholder="http://example.com/cookie-policy | cookie-policy"
                                                            value="<?php echo html($template['cookie_consent']['link']) ?>"/>
                                    </div>
                                </div>
                                <div class="form-group form-row mt-0">
                                    <label class="col-sm-3 col-form-label text-right"
                                           for="be_cookie_consent_theme"><?php echo $BL['be_cookie_consent_theme']; ?></label>
                                    <div class="col">
                                        <input type="text" name="be_cookie_consent_theme"
                                               class="form-control form-control-sm"
                                               placeholder="light-top, light-bottom, light-floating, dark-top&hellip;"
                                               title="<?php echo $BL['be_admin_tmpl_default']; ?>: light-top, light-bottom, light-floating, dark-top, dark-bottom, dark-floating, dark-inline, dark-floating-tada"
                                               value="<?php echo html($template['cookie_consent']['theme']) ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" name="template_frontendjs" id="template_frontendjs"
                                   type="checkbox" value="1"<?php is_checked($template['frontendjs'], 1); ?>>
                            <label class="form-check-label"
                                   for="template_frontendjs"><?php echo $BL['frontendjs_load'] ?></label>
                        </div>
                    </div>
                </div>

            <div class="form-group form-row align-items-center">
                <label for="be_admin_tmpl_js"
                       class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_tmpl_js'] ?></label>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" name="template_jsonload"
                           id="template_jsonload" value="<?php echo html_entities($template["jsonload"]) ?>">
                </div>
            </div>
            <div class="form-group form-row align-items-center">
                <label for="be_fe_login_url"
                       class="col-sm-2 col-form-label text-right"><?php echo $BL['be_fe_login_url'] ?></label>
                <div class="col">
                    <input type="text" class="form-control form-control-sm" name="template_felogin_url"
                           id="template_felogin_url"
                           value="<?php echo empty($template["feloginurl"]) ? '' : html_entities($template["feloginurl"]) ?>">
                </div>
            </div>

            <hr/>

            <div class="form-group form-row">
                <label for="be_admin_page_header"
                       class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_header'] ?></label>
                <div class="col">
                    <?php
                    if (!isset($template["headertext_file"])) {
                        $template["headertext_file"] = '';
                    }
                    echo get_template_file_select('header', 'template_block_header_file', $template["headertext_file"]);
                    ?>
                    <textarea name="template_block_header" rows="3" class="form-control form-control-sm autosize"
                              id="template_block_header"><?php echo html_entities($template["headertext"]); ?></textarea>
                </div>
            </div>
            <div class="form-group form-row">
                <label for="be_admin_page_main"
                       class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_main'] ?></label>
                <div class="col">
                    <?php
                    if(!isset($template["maintext_file"])) {
                        $template["maintext_file"] = '';
                    }
                    echo get_template_file_select('main', 'template_block_main_file', $template["maintext_file"]);
                    ?>
                    <textarea name="template_block_main" rows="3" class="form-control form-control-sm autosize"
                              id="template_block_main"><?php echo html_entities($template["maintext"]); ?></textarea>
                </div>
            </div>
            <div class="form-group form-row">
                <label for="be_admin_page_footer"
                       class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_footer'] ?></label>
                <div class="col">
                    <?php
                    if(!isset($template["footertext_file"])) {
                        $template["footertext_file"] = '';
                    }
                    echo get_template_file_select('footer', 'template_block_footer_file', $template["footertext_file"]);
                    ?>
                    <textarea name="template_block_footer" rows="3" class="form-control form-control-sm autosize"
                              id="template_block_footer"><?php echo html_entities($template["footertext"]); ?></textarea>
                </div>
            </div>
            <div class="form-group form-row">
                <label for="be_admin_page_left"
                       class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_left'] ?></label>
                <div class="col">
                    <?php
                    if(!isset($template["lefttext_file"])) {
                        $template["lefttext_file"] = '';
                    }
                    echo get_template_file_select('left', 'template_block_left_file', $template["lefttext_file"]);
                    ?>
                    <textarea name="template_block_left" rows="3" class="form-control form-control-sm autosize"
                              id="template_block_left"><?php echo html_entities($template["lefttext"]); ?></textarea>
                </div>
            </div>
            <div class="form-group form-row">
                <label for="be_admin_page_right"
                       class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_page_right'] ?></label>
                <div class="col">
                    <?php
                    if(!isset($template["righttext_file"])) {
                        $template["righttext_file"] = '';
                    }
                    echo get_template_file_select('right', 'template_block_right_file', $template["righttext_file"]);
                    ?>
                    <textarea name="template_block_right" rows="3" class="form-control form-control-sm autosize"
                              id="template_block_right"><?php echo html_entities($template["righttext"]); ?></textarea>
                </div>
            </div>

            <?php
            if (!empty($jsOnChange)) {
                echo '<input type="hidden" name="customblock" value="' . html(implode(',', $custom_blocks)) . '" />';
                // list custom blocks
                foreach ($custom_blocks as $value) {
                    $custom_block = html($value);
                    if(!isset($template['customblock_'.$value.'_file'])) {
                        $template['customblock_'.$value.'_file'] = '';
                    }
                    echo '<div class="form-group form-row">';
                    echo '  <label for="be_admin_tmpl_error" class="col-sm-2 col-form-label text-right">';
                    echo '' . $custom_block . " <br /> {" . $custom_block . "}";
                    echo '</label>';
                    echo '<div class="col">';
                    echo get_template_file_select(strtolower($value), 'template_customblock_'.$custom_block.'_file', $template['customblock_'.$value.'_file']);
                    echo '<textarea name="template_customblock_' . $custom_block;
                    echo '" rows="3" class="form-control form-control-sm autosize">';
                    echo isset($template['customblock_' . $value]) ? html_entities($template['customblock_' . $value]) : '';
                    echo "</textarea>\n";
                    echo '  </div>';
                    echo '</div>';
                }
            }
            ?>

            <div class="form-group form-row">
                <label for="be_admin_tmpl_error"
                       class="col-sm-2 col-form-label text-right"><?php echo $BL['be_admin_tmpl_error'] ?></label>
                <div class="col">
                    <?php
                    if(!isset($template["errortext_file"])) {
                        $template["errortext_file"] = '';
                    }
                    echo get_template_file_select('error', 'template_block_error_file', $template["errortext_file"]);
                    ?>
                    <textarea name="template_block_error" rows="3" class="form-control form-control-sm autosize"
                              id="template_block_error"><?php echo html_entities($template["errortext"]); ?></textarea>
                </div>
            </div>
        
        <div class="form-group align-items-center text-center text-sm-right mt-3 mb-2">
            <input name="template_id" type="hidden" value="<?php echo $template["id"] ?>"/>
            <input name="Submit" type="submit" class="btn btn-sm btn-blue"
                   value="<?php echo $BL['be_admin_tmpl_button'] ?>"/>
            <input type="button" class="btn btn-sm btn-blue" value="<?php echo $BL['be_admin_struct_close'] ?>"
                   onclick="location.href='cmsgo.php?do=admin&amp;p=11';"/>
        </div>
        </form>

    <script type="text/javascript">
        $(function () {
            $('#template_cookie_consent').change(function () {
                if ($(this).is(':checked')) {
                    $('#cookie-consent').show();
                } else {
                    $('#cookie-consent').hide();
                }
            });
            $('#template_ga').change(function () {
                if ($(this).is(':checked')) {
                    $('#ga-tracking').show();
                } else {
                    $('#ga-tracking').hide();
                }
            });
            $('#template_piwik').change(function () {
                if ($(this).is(':checked')) {
                    $('#piwik-tracking').show();
                } else {
                    $('#piwik-tracking').hide();
                }
            });
        });
    </script>
    <?php

}
