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

initAceEditor();

reset($phpwcms['js_lib']); // reset $phpwcms['js_lib'] to get first element as default

$template = [
    "name" => '',
    "default" => 0,
    "layout" => '',
    "css" => [],
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
    'cookie_consent' => [
        'enable' => 0,
        'message' => $BL['cookie_consent_message'],
        'dismiss' => $BL['cookie_consent_dismiss'],
        'more' => $BL['cookie_consent_more'],
        'link' => '',
        'theme' => 'light-bottom',
    ],
    'cc_v3' => [
        'enable' => 0,
        'reload_on_change' => 0,
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
    ],
    'tracking_ga' => [
        'enable' => 0,
        'id' => '',
        'anonymize' => PHPWCMS_GDPR_MODE  ? 1 : 0,
        'optout' => PHPWCMS_GDPR_MODE  ? 1 : 0,
        'cookie_flags' => PHPWCMS_GDPR_MODE  ? 1 : 0,
        'custom_properties' => ''
    ],
    'tracking_gtm' => [
        'enable' => 0,
        'id' => '',
    ],
    'tracking_piwik' => [
        'enable' => 0,
        'id' => '',
        'url' => ''
    ],
    'donottrack' => 0,
    'require_consent' => [
        'enable' => 0,
        'cookie_name' => 'cookieconsent_dismissed',
        'cookie_value' => 'yes'
    ],
];

initJQuery();

if(!isset($_GET["s"])) {

    ?>
    <h1 class="text-center text-sm-start"><?php echo $BL['be_subnav_admin_templates'] ?></h1>
    <div class="card">
        <div class="card-header"><h2><i class="fa-solid fa-list"></i> <?php echo $BL['be_admin_tmpl_title'] ?></h2></div>
        <div class="card-body">
            <table class="table table-striped table-sm table-valign-middle mb-4">
                <?php
                // loop listing available templates
                $sql = "SELECT * FROM " . DB_PREPEND . "phpwcms_template WHERE template_trash=0 ORDER BY template_default DESC, template_name";
                $result = _dbQuery($sql);
                $row_count = 0;
                if (isset($result[0]['template_id'])) {
                    foreach ($result as $row) {

                        $edit_link = 'do=admin&amp;p=11&amp;s=' . $row["template_id"] . '&amp;t=' . $row["template_type"];

                        echo "<tr>\n";
                        echo '<td><a href="phpwcms.php?' . $edit_link;
                        echo '"><strong>' . html($row["template_name"]) . "</strong>";
                        if($row["template_default"]) {
                            echo " (" . $BL['be_admin_tmpl_default'] . ")";
                        }
                        echo "</a></td>" . '<td class="text-end text-nowrap">';
                        echo '<div class="btn-group btn-group-sm" role="group" aria-label="tmpl-actions-' . $row["template_id"] . '">';
                        echo '<a class="btn btn-blue btn-sm" role="button" data-bs-toggle="tooltip" title="' . $BL['be_tt_edit'] . '" href="phpwcms.php?' . $edit_link;
                        echo '"><i class="fa-solid fa-pencil-alt"></i></a>';

                        echo '<a class="btn btn-blue btn-sm" role="button" data-bs-toggle="tooltip" title="' . $BL['be_tt_duplicate'] . '" href="phpwcms.php?' . $edit_link . '&amp;c=1'; // c=1 -> do copy
                        echo '"><i class="fa-solid fa-copy"></i></a>';
                        echo '</div>';

                        echo '<a class="btn btn-danger btn-sm ms-1" role="button" data-bs-toggle="tooltip" href="include/inc_act/act_frontendsetup.php?do=2|' . $row["template_id"] . '" ';
                        echo 'title="' . $BL['be_cnt_delete'] . ': ' . html($row["template_name"]) . '" ';
                        echo 'data-confirm-danger="' . html($BL['be_cnt_delete'] . ":\n[" . $row["template_name"] . ']') . '">';
                        echo '<i class="fa-regular fa-trash-alt" aria-hidden="true"></i></a>';
                        echo "</td>\n</tr>\n";

                        $row_count++;
                    }
                } // end listing

                ?>
            </table>
            <a href="phpwcms.php?do=admin&amp;p=11&amp;s=0" class="btn btn-blue btn-sm" title="<?php echo $BL['be_admin_tmpl_add'] ?>"><i class="fa-solid fa-plus me-1"></i> <?php echo $BL['be_admin_tmpl_add'] ?></a>
        </div>
    </div>
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
        $template["css"] = isset($_POST["template_css"]) && is_array($_POST["template_css"]) ? $_POST["template_css"] : [];
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
        $template['cc_v3']['reload_on_change'] = empty($_POST['cc_v3_reload_on_change']) ? 0 : 1;

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
        $template['tracking_ga']['id'] = clean_slweg($_POST['template_ga_id']);
        $template['tracking_ga']['custom_properties'] = trim(clean_slweg($_POST['template_ga_custom_properties']), " \t\n\r\0\x0B{},");
        $template['tracking_ga']['anonymize'] = empty($_POST['template_ga_anonymize']) ? 0 : 1;
        $template['tracking_ga']['optout'] = empty($_POST['template_ga_optout']) ? 0 : 1;
        $template['tracking_ga']['cookie_flags'] = empty($_POST['template_ga_cookie_flags']) ? 0 : 1;
        if(empty($template['tracking_ga']['id'])) {
            $template['tracking_ga']['enable'] = 0;
        }
        $template['tracking_gtm']['enable'] = empty($_POST['template_gtm']) ? 0 : 1;
        $template['tracking_gtm']['id'] = clean_slweg($_POST['template_gtm_id']);
        if(empty($template['tracking_gtm']['id'])) {
            $template['tracking_gtm']['enable'] = 0;
        }
        $template['tracking_piwik']['enable'] = empty($_POST['template_piwik']) ? 0 : 1;
        $template['tracking_piwik']['id'] = intval($_POST['template_piwik_id']);
        $template['tracking_piwik']['url'] = clean_slweg($_POST['template_piwik_url']);
        if(!empty($template['tracking_piwik']['url'])) {
            $template['tracking_piwik']['url'] = trim(preg_replace('/.*?:\/\//i', '', trim($template['tracking_piwik']['url'], '/')));
        }
        if(empty($template['tracking_piwik']['id']) || empty($template['tracking_piwik']['url'])) {
            $template['tracking_piwik']['enable'] = 0;
        }
        $template['donottrack'] = empty($_POST['template_donottrack']) ? 0 : 1;
        $template['require_consent'] = [
            'enable' => empty($_POST['template_require_consent']) ? 0 : 1,
            'cookie_name' => clean_slweg($_POST['template_require_cookie_name']),
            'cookie_value' => clean_slweg($_POST['template_require_cookie_value'])
        ];

        // now browse custom blocks if available
        if(!empty($_POST['customblock'])) {
            $template['customblock'] = clean_slweg($_POST['customblock']);
            $temp_customblock = explode(',', $template['customblock']);
            foreach($temp_customblock as $value) {
                $template['customblock_'.$value] = slweg($_POST['template_customblock_'.$value]);
                $template['customblock_'.$value.'_file'] = slweg($_POST['template_customblock_'.$value.'_file']);
            }
        }

        if($template["id"] && empty($createcopy)) {
            // if ID <> 0 then get template info from database
            $query_mode = 'UPDATE';
            $sql =  'UPDATE ' .DB_PREPEND. 'phpwcms_template SET ' .
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
                $template['css'] = [$template['css']];
            }
        }
    }

    // show form
    ?>
    <script type="text/javascript">
        function doPageLayoutChange() {
        if(confirm('<?php echo correct_charset($BL['be_admin_template_jswarning'], true); ?>')) {
                document.blocks.submit();
                return true;
            }
            return false;
        }
    </script>
    <form action="phpwcms.php?do=admin&amp;p=11&amp;s=<?php echo $template["id"] ?>" method="post" name="blocks" target="_self" id="blocks">
        <div class="row align-items-center">
            <div class="col col-sm-auto text-center text-sm-start">
                <h1><?php echo $BL['be_subnav_admin_templates'] ?></h1>
            </div>
            <div class="col-12 col-sm text-center text-sm-end mb-3">
                <div class="form-group">
                    <input name="template_id" type="hidden" value="<?php echo $template["id"] ?>"/>
                    <button name="Submit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa-solid fa-rotate"></i> <?php echo $BL['be_admin_tmpl_button'] ?></button>
                    <a href="phpwcms.php?do=admin&amp;p=11" class="btn btn-sm btn-danger ms-3"><i class="fa-solid fa-times"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h2>
                    <i class="fa-solid fa-list"></i>
                    <?php echo(empty($createcopy) ? $BL['be_admin_tmpl_edit'] : $BL['be_admin_tmpl_copy']) ?>
                    : <?php echo ($template["id"]) ? html($template["name"]) : $BL['be_admin_tmpl_new']; ?>
                </h2>
                <input type="hidden" name="c" value="<?php echo $createcopy; ?>"/>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-3" id="templateTabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="tmpl-layout-tab" data-bs-toggle="tab" href="#tmpl-layout-sect" role="tab" aria-controls="tmpl-layout-sect" aria-selected="true"><i class="fa-solid fa-th-large me-1"></i> <?php echo $BL['be_admin_tmpl_layout'] ?></a></li>
                    <li class="nav-item"><a class="nav-link" id="tmpl-blocks-tab" data-bs-toggle="tab" href="#tmpl-blocks-sect" role="tab" aria-controls="tmpl-blocks-sect" aria-selected="false"><i class="fa-solid fa-cubes me-1"></i> <?php echo $BL['be_admin_page_blocks'] ?></a></li>
                    <li class="nav-item"><a class="nav-link" id="tmpl-head-tab" data-bs-toggle="tab" href="#tmpl-head-sect" role="tab" aria-controls="tmpl-head-sect" aria-selected="false"><i class="fa-solid fa-code me-1"></i> <?php echo $BL['be_admin_tmpl_head'] ?></a></li>
                    <li class="nav-item"><a class="nav-link" id="tmpl-consent-tab" data-bs-toggle="tab" href="#tmpl-consent-sect" role="tab" aria-controls="tmpl-consent-sect" aria-selected="false"><i class="fa-solid fa-shield-alt me-1"></i> Tracking &amp; Cookie Consent</a></li>
                </ul>

                <div class="tab-content" id="templateTabsContent">
                    <!-- TAB 1: LAYOUT -->
                    <div class="tab-pane fade show active" id="tmpl-layout-sect" role="tabpanel" aria-labelledby="tmpl-layout-tab">
                        <div class="form-group row g-2 align-items-center">
                            <label for="template_name" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_tmpl_name'] ?></label>
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
                        <div class="form-group row g-2 align-items-center">
                            <label for="template_layout"
                                   class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_tmpl_layout'] ?></label>
                            <div class="col-sm-5">
                                <?php
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
                                        $opt .= '>' . html($row['pagelayout_name']) . '</option>';
                                    }
                                }
                                if ($opt) {
                                    echo '<select name="template_layout" class="form-select form-select-sm" id="template_layout"' . $jsOnChange . '>';
                                    echo $opt;
                                    echo '</select>';
                                } else {
                                    echo $BL['be_admin_tmpl_nolayout'] . ' (<a href="phpwcms.php?do=admin&p=8&s=0">' . $BL['be_admin_page_add'] . '</a>)';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="form-group row g-2 align-items-center">
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

                        <hr/>

                        <div class="form-group row g-2">
                            <div class="col-sm-2"></div>
                            <div class="col">
                                <?php echo $BL['be_overwrite_default'] ?><br/>
                                <strong><code>/include/config/conf.template_default.inc.php</code></strong>
                            </div>
                        </div>

                        <div class="form-group row g-2 align-items-center">
                            <label for="template_overwrite" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_settings'] ?></label>
                            <div class="col-sm-5">
                                <select name="template_overwrite" type="text" class="form-select form-select-sm" id="template_overwrite">
                                    <option value="" style="font-weight:normal;font-style:italic;"><?php echo $BL['be_admin_tmpl_default']; ?></option>
                                    <?php
                                    // templates for frontend login
                                    $tmpllist = get_tmpl_files(PHPWCMS_TEMPLATE . 'inc_settings/template_default', 'php');
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

                        <hr/>

                        <div class="form-group row g-2">
                            <label for="template_css" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_tmpl_css'] ?></label>
                            <div class="col">
                                <select name="template_css[]" multiple class="form-select form-select-sm" id="template_css">
                                    <?php
                                    $unselected_css = [];
                                    // get css file list
                                    if (is_dir(PHPWCMS_TEMPLATE . "inc_css")) {
                                        $css_handle = opendir(PHPWCMS_TEMPLATE . "inc_css");
                                        // browse template CSS diretory and list all available CSS files
                                        while ($css_file = readdir($css_handle)) {
                                            if (substr($css_file, 0, 1) !== '.' && is_file(PHPWCMS_TEMPLATE . "inc_css/" . $css_file) && preg_match('/^[a-z0-9\. \-_]+\.css$/i', $css_file)) {
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
                                <button type="button" class="btn btn-sm btn-blue" onclick="moveOptionUp(document.blocks.template_css);">
                                    <i class="fa-solid fa-angle-up fa-fw" aria-hidden="true"></i>
                                </button>
                                <br/>
                                <button type="button" class="btn btn-sm btn-blue mt-1" onclick="moveOptionDown(document.blocks.template_css);">
                                    <i class="fa-solid fa-angle-down fa-fw" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        <hr/>

                        <div class="form-group row g-2 align-items-center mb-0">
                            <label for="template_felogin_url" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_fe_login_url'] ?></label>
                            <div class="col">
                                <input type="text" class="form-control form-control-sm" name="template_felogin_url" id="template_felogin_url" value="<?php echo empty($template["feloginurl"]) ? '' : html_entities($template["feloginurl"]) ?>">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: BLOCKS -->
                    <div class="tab-pane fade" id="tmpl-blocks-sect" role="tabpanel" aria-labelledby="tmpl-blocks-tab">
                        <div class="form-group row g-2">
                            <label for="template_block_header" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_page_header'] ?></label>
                            <div class="col">
                                <?php
                                if (!isset($template["headertext_file"])) {
                                    $template["headertext_file"] = '';
                                }
                                echo get_template_file_select('header', 'template_block_header_file', $template["headertext_file"]);
                                ?>
                                <textarea name="template_block_header" rows="6" class="form-control form-control-sm autosize font-monospace code-editor" data-mode="html" id="template_block_header"><?php echo html_entities($template["headertext"]); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row g-2">
                            <label for="template_block_main" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_page_main'] ?></label>
                            <div class="col">
                                <?php
                                if(!isset($template["maintext_file"])) {
                                    $template["maintext_file"] = '';
                                }
                                echo get_template_file_select('main', 'template_block_main_file', $template["maintext_file"]);
                                ?>
                                <textarea name="template_block_main" rows="10" class="form-control form-control-sm autosize font-monospace code-editor" data-mode="html" id="template_block_main"><?php echo html_entities($template["maintext"]); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row g-2">
                            <label for="template_block_footer" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_page_footer'] ?></label>
                            <div class="col">
                                <?php
                                if(!isset($template["footertext_file"])) {
                                    $template["footertext_file"] = '';
                                }
                                echo get_template_file_select('footer', 'template_block_footer_file', $template["footertext_file"]);
                                ?>
                                <textarea name="template_block_footer" rows="6" class="form-control form-control-sm autosize font-monospace code-editor" data-mode="html" id="template_block_footer"><?php echo html_entities($template["footertext"]); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row g-2">
                            <label for="template_block_left" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_page_left'] ?></label>
                            <div class="col">
                                <?php
                                if(!isset($template["lefttext_file"])) {
                                    $template["lefttext_file"] = '';
                                }
                                echo get_template_file_select('left', 'template_block_left_file', $template["lefttext_file"]);
                                ?>
                                <textarea name="template_block_left" rows="6" class="form-control form-control-sm autosize font-monospace code-editor" data-mode="html" id="template_block_left"><?php echo html_entities($template["lefttext"]); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row g-2">
                            <label for="template_block_right" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_page_right'] ?></label>
                            <div class="col">
                                <?php
                                if(!isset($template["righttext_file"])) {
                                    $template["righttext_file"] = '';
                                }
                                echo get_template_file_select('right', 'template_block_right_file', $template["righttext_file"]);
                                ?>
                                <textarea name="template_block_right" rows="6" class="form-control form-control-sm autosize font-monospace code-editor" data-mode="html" id="template_block_right"><?php echo html_entities($template["righttext"]); ?></textarea>
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
                                echo '<div class="form-group row g-2">';
                                echo '  <label for="be_admin_tmpl_error" class="col-sm-2 col-form-label text-end">';
                                echo $custom_block . " <br />{" . $custom_block . "}";
                                echo '</label>';
                                echo '<div class="col">';
                                echo get_template_file_select(strtolower($value), 'template_customblock_'.$custom_block.'_file', $template['customblock_'.$value.'_file']);
                                echo '<textarea name="template_customblock_' . $custom_block . '" id="template_customblock_' . $custom_block . '" ';
                                echo 'rows="6" class="form-control form-control-sm autosize font-monospace code-editor" data-mode="html">';
                                echo isset($template['customblock_' . $value]) ? html_entities($template['customblock_' . $value]) : '';
                                echo "</textarea>";
                                echo '  </div>';
                                echo '</div>';
                            }
                        }
                        ?>

                        <div class="form-group row g-2 mb-0">
                            <label for="template_block_error" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_tmpl_error'] ?></label>
                            <div class="col">
                                <?php
                                if(!isset($template["errortext_file"])) {
                                    $template["errortext_file"] = '';
                                }
                                echo get_template_file_select('error', 'template_block_error_file', $template["errortext_file"]);
                                ?>
                                <textarea name="template_block_error" rows="6" class="form-control form-control-sm autosize font-monospace code-editor" data-mode="html" id="template_block_error"><?php echo html_entities($template["errortext"]); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: HEAD & SCRIPTS -->
                    <div class="tab-pane fade" id="tmpl-head-sect" role="tabpanel" aria-labelledby="tmpl-head-tab">
                        <div class="form-group row g-2">
                            <label for="template_htmlhead" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_tmpl_head'] ?></label>
                            <div class="col">
                                <?php
                                if (!isset($template["htmlhead_file"])) {
                                    $template["htmlhead_file"] = '';
                                }
                                echo get_template_file_select('head', 'template_htmlhead_file', $template["htmlhead_file"]);
                                ?>
                                <textarea name="template_htmlhead" rows="6" class="form-control form-control-sm autosize font-monospace code-editor" data-mode="html" id="template_htmlhead"><?php echo html_entities($template["htmlhead"]); ?></textarea>
                            </div>
                        </div>

                        <hr/>

                        <div class="form-group row g-2 align-items-center">
                            <label for="template_jslib" class="col-sm-2 col-form-label text-end"><?php echo $BL['js_lib'] ?></label>
                            <div class="col">
                                <div class="input-group input-group-sm">
                                    <select class="form-select" name="template_jslib" id="template_jslib">
                                        <?php
                                        $jslib_optgroup = false;
                                        $jslib_current_optgroup = '';
                                        foreach ($phpwcms['js_lib'] as $key => $value) {
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
                                            is_selected($template['jslib'], $key);
                                            echo '>' . html($value) . '</option>';
                                        }
                                        if ($jslib_optgroup) {
                                            echo '</optgroup>';
                                        }
                                        ?>
                                    </select>
                                    
                                        <div class="input-group-text">
                                            <input class="me-1" type="checkbox" name="template_jslibload" id="template_jslibload" value="1" <?php is_checked($template['jslibload'], 1); ?> />
                                            <label for="template_jslibload" class="form-check-label mb-0"><?php echo $BL['js_lib_alwaysload'] ?></label>
                                        </div>
                                        <div class="input-group-text">
                                            <input class="me-1" type="checkbox" name="template_googleapi" id="template_googleapi" value="1" <?php is_checked($template['googleapi'], 1); ?> />
                                            <label for="template_googleapi" class="form-check-label mb-0"><?php echo $BL['googleapi_load'] ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row g-2 align-items-center">
                            <label for="template_jsonload" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_tmpl_js'] ?></label>
                            <div class="col">
                                <input type="text" class="form-control form-control-sm" name="template_jsonload" id="template_jsonload" value="<?php echo html_entities($template["jsonload"]) ?>">
                            </div>
                        </div>

                        <div class="form-group row g-2 align-items-center mb-0">
                            <div class="col-sm-2"></div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" name="template_frontendjs" id="template_frontendjs" type="checkbox" value="1"<?php is_checked($template['frontendjs'], 1); ?>>
                                    <label class="form-check-label" for="template_frontendjs"><?php echo $BL['frontendjs_load'] ?></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: TRACKING & COOKIE CONSENT -->
                    <div class="tab-pane fade" id="tmpl-consent-sect" role="tabpanel" aria-labelledby="tmpl-consent-tab">
                        <!-- Tracking -->
                        <div class="form-group mb-4">
                            <label class="col-form-label fw-bold">Tracking</label>
<?php if (!empty($template['ie8ignore'])): ?>
                            <div class="form-check">
                                <input class="form-check-input" name="template_ie8ignore" id="template_ie8ignore" type="checkbox" value="1" checked disabled readonly>
                                <label class="form-check-label text-muted" for="template_ie8ignore"><?php echo $BL['be_ie8ignore'] ?></label>
                            </div>
<?php endif; ?>
                            <div class="form-check">
                                <label class="form-check-label" for="template_ga">
                                    <input class="form-check-input" name="template_ga" id="template_ga" type="checkbox" value="1"<?php is_checked($template['tracking_ga']['enable'], 1); ?>>
                                    <?php echo $BL['be_google_analytics_enable']; ?>
                                </label>

                                <div id="ga-tracking" class="form-group row g-2 align-items-center mt-1"<?php if (!$template['tracking_ga']['enable']): ?> style="display:none;"<?php endif; ?>>
                                    <label class="col-sm-2 col-form-label text-end" for="template_ga_id"><?php echo $BL['be_tracking_id']; ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="template_ga_id" id="template_ga_id" class="form-control form-control-sm" placeholder="UA-XXXXX-Y" value="<?php echo html($template['tracking_ga']['id']) ?>"/>
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="template_ga_anonymize" id="template_ga_anonymize" value="1"<?php is_checked($template['tracking_ga']['anonymize'], 1); ?> />
                                            <label for="template_ga_anonymize" class="form-check-label"><?php echo $BL['be_tracking_anonymize']; ?></label>
                                        </div>
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="template_ga_optout" id="template_ga_optout" value="1"<?php is_checked($template['tracking_ga']['optout'] ?? 0, 1); ?> />
                                            <label for="template_ga_optout" class="form-check-label"><?php echo $BL['be_tracking_optout']; ?></label>
                                        </div>
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="template_ga_cookie_flags" id="template_ga_cookie_flags" value="1"<?php is_checked($template['tracking_ga']['cookie_flags'] ?? 0, 1); ?> />
                                            <label for="template_ga_cookie_flags" class="form-check-label"><?php echo $BL['be_tracking_cookie_flags']; ?></label>
                                        </div>
                                    </div>

                                    <div class="col-sm-10 offset-sm-2 my-1">
                                        <label class="col-form-label fw-normal pb-1" for="template_ga_custom_properties"><?php echo $BL['be_tracking_custom_properties']; ?></label>
                                        <textarea name="template_ga_custom_properties" id="template_ga_custom_properties" class="form-control font-monospace autosize code-editor" data-mode="javascript" data-min-lines="4" placeholder="prop1: 'val1', prop2: true"><?php echo html($template['tracking_ga']['custom_properties']) ?></textarea>
                                    </div>

                                </div>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label" for="template_gtm">
                                    <input class="form-check-input" name="template_gtm" id="template_gtm" type="checkbox" value="1"<?php is_checked($template['tracking_gtm']['enable'], 1); ?>>
                                    <?php echo $BL['be_google_tag_manager_enable']; ?>
                                </label>

                                <div id="gtm-tracking" class="form-group row g-2 align-items-center mt-1"<?php if (!$template['tracking_gtm']['enable']): ?> style="display:none;"<?php endif; ?>>
                                    <label class="col-sm-2 col-form-label text-end" for="template_gtm_id"><?php echo $BL['be_tracking_id']; ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" name="template_gtm_id" id="template_gtm_id" class="form-control form-control-sm" placeholder="GTM-XXXXXXX" value="<?php echo html($template['tracking_gtm']['id']) ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label" for="template_piwik">
                                    <input class="form-check-input" name="template_piwik" id="template_piwik" type="checkbox" value="1"<?php is_checked($template['tracking_piwik']['enable'], 1); ?>>
                                    <?php echo $BL['be_piwik_enable']; ?>
                                </label>

                                <div id="piwik-tracking" class="form-group mt-1"<?php if (!$template['tracking_piwik']['enable']): ?> style="display:none;"<?php endif; ?>>
                                    <div class="row g-2 align-items-center">
                                        <label class="col-sm-2 col-form-label text-end" for="template_piwik_id"><?php echo $BL['be_site_id']; ?></label>
                                        <div class="col-sm-2">
                                            <input type="text" name="template_piwik_id" class="form-control form-control-sm" placeholder="1" id="template_piwik_id" value="<?php echo empty($template['tracking_piwik']['id']) ? '' : $template['tracking_piwik']['id']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="row g-2 align-items-center mt-1">
                                        <label class="col-sm-2 col-form-label text-end" for="template_piwik_url"><?php echo $BL['be_piwik_url']; ?></label>
                                        <div class="col-sm-4">
                                            <input type="text" name="template_piwik_url" class="form-control form-control-sm" placeholder="piwik.example.com" id="template_piwik_url" value="<?php echo html($template['tracking_piwik']['url']) ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr/>

                        <!-- Cookie Consent -->
                        <div class="form-group mb-0">
                            <label class="col-form-label fw-bold">Cookie Consent</label>

                            <!-- Cookie Consent v2 -->
                            <div class="form-check">
                            <label class="form-check-label" for="template_cookie_consent">
                                <input class="form-check-input" name="template_cookie_consent" id="template_cookie_consent" type="checkbox" value="1"<?php is_checked($template['cookie_consent']['enable'], 1); ?>>
                                <?php echo $BL['be_cookie_consent_enable'] ?>
                            </label>

                            <div id="template-cc-form"<?php if (!$template['cookie_consent']['enable']): ?> style="display:none;"<?php endif; ?>>
                                <?php if (count($phpwcms['allowed_lang'])): ?>
                                    <em class="mt-2"><small><?php echo $BL['be_cookie_consent_translatable']; ?></small></em>
                                <?php endif; ?>
                                <div class="form-group row g-2 my-2">
                                    <label class="col-sm-3 col-form-label text-end" for="be_cookie_consent_message"><?php echo $BL['be_cookie_consent_message']; ?></label>
                                    <div class="col">
                                        <textarea name="cookie_consent_message" rows="3" id="be_cookie_consent_message" class="form-control form-control-sm autosize" placeholder="<?php echo $BL['cookie_consent_message']; ?>"><?php echo html($template['cookie_consent']['message']) ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row g-2 mt-2 mb-0">
                                    <label class="col-sm-3 col-form-label text-end" for="be_cookie_consent_dismiss"><?php echo $BL['be_cookie_consent_dismiss']; ?></label>
                                    <div class="col"><input type="text" name="cookie_consent_dismiss" id="be_cookie_consent_dismiss" class="form-control form-control-sm" placeholder="<?php echo $BL['cookie_consent_dismiss']; ?>" value="<?php echo html($template['cookie_consent']['dismiss']) ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row g-2 my-0">
                                    <label class="col-sm-3 col-form-label text-end" for="be_cookie_consent_more"><?php echo $BL['be_cookie_consent_more']; ?></label>
                                    <div class="col">
                                        <input type="text" name="cookie_consent_more" id="be_cookie_consent_more" class="form-control form-control-sm" placeholder="<?php echo $BL['cookie_consent_more']; ?>" value="<?php echo html($template['cookie_consent']['more']) ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row g-2 my-0">
                                    <label class="col-sm-3 col-form-label text-end" for="be_cookie_consent_link"><?php echo $BL['be_cookie_consent_link']; ?></label>
                                    <div class="col">
                                        <input type="text" name="cookie_consent_link" id="be_cookie_consent_link" class="form-control form-control-sm" placeholder="https://example.com/cookie-policy | cookie-policy" value="<?php echo html($template['cookie_consent']['link']) ?>"/>
                                    </div>
                                </div>
                                <div class="form-group row g-2 mt-0">
                                    <label class="col-sm-3 col-form-label text-end" for="be_cookie_consent_theme"><?php echo $BL['be_cookie_consent_theme']; ?></label>
                                    <div class="col">
                                        <input type="text" name="cookie_consent_theme" id="be_cookie_consent_theme" class="form-control form-control-sm" placeholder="light-top, light-bottom, light-floating, dark-top&hellip;" title="<?php echo $BL['be_admin_tmpl_default']; ?>: light-top, light-bottom, light-floating, dark-top, dark-bottom, dark-floating, dark-inline, dark-floating-tada" value="<?php echo html($template['cookie_consent']['theme']) ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cookie Consent v3 -->
                        <div class="form-check">
                            <label class="form-check-label" for="template_cc_v3">
                                <input class="form-check-input"
                                       name="template_cc_v3"
                                       id="template_cc_v3"
                                       type="checkbox"
                                       value="1"<?php is_checked($template['cc_v3']['enable'], 1); ?>
                                />
                                <?php echo $BL['be_cc_v3_enable'] ?>
                            </label>

                            <div id="template-cc_v3-form"<?php if (!$template['cc_v3']['enable']): ?> style="display:none;"<?php endif; ?> class="mb-2">
                                <?php if (count($phpwcms['allowed_lang'])): ?>
                                    <em class="mt-2"><small><?php echo $BL['be_cookie_consent_translatable']; ?></small></em>
                                <?php endif; ?>

                                <div class="form-group row g-2 mb-0">
                                    <label class="col-sm-3 col-form-label text-end" for="cc_v3_reload_on_change">
                                        <?php echo $BL['be_cc_v3_on_change']; ?>
                                    </label>
                                    <div class="col mb-1 mt-2 ps-4">
                                        <label class="form-check-label" for="cc_v3_reload_on_change">
                                            <input class="form-check-input"
                                                   name="cc_v3_reload_on_change"
                                                   id="cc_v3_reload_on_change"
                                                   type="checkbox"
                                                   value="1"<?php is_checked($template['cc_v3']['reload_on_change'], 1); ?>
                                            />
                                            <?php echo $BL['be_cc_v3_reload_on_change'] ?>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row g-2 mt-1 mb-0">
                                    <label class="col-sm-3 col-form-label text-end" for="cc_v3_title">
                                        <?php echo $BL['be_cc_v3_title']; ?>
                                    </label>
                                    <div class="col">
                                        <input type="text"
                                               name="cc_v3_title"
                                               id="cc_v3_title"
                                               class="form-control form-control-sm"
                                               placeholder="<?php echo $BL['cc_v3_title_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['title']); ?>"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row g-2 my-0">
                                    <label class="col-sm-3 col-form-label text-end" for="cc_v3_description">
                                        <?php echo $BL['be_cc_v3_description']; ?>
                                    </label>
                                    <div class="col">
                                        <textarea name="cc_v3_description"
                                                  rows="3"
                                                  id="cc_v3_description"
                                                  class="form-control form-control-sm autosize"
                                                  placeholder="<?php echo $BL['cc_v3_description_placeholder']; ?>"><?php
                                            echo html($template['cc_v3']['description']);
                                            ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group row g-2 mt-2 mb-0">
                                    <label class="col-sm-3 col-form-label text-end" for="cc_v3_accept_all">
                                        <?php echo $BL['be_cc_v3_accept_all']; ?>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text"
                                               name="cc_v3_accept_all"
                                               id="cc_v3_accept_all"
                                               class="form-control form-control-sm"
                                               placeholder="<?php echo $BL['cc_v3_accept_all_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['accept_all']); ?>"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row g-2 my-0">
                                    <label class="col-sm-3 col-form-label text-end" for="cc_v3_accept_necessary">
                                        <?php echo $BL['be_cc_v3_accept_necessary']; ?>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text"
                                               name="cc_v3_accept_necessary"
                                               id="cc_v3_accept_necessary"
                                               class="form-control form-control-sm"
                                               placeholder="<?php echo $BL['cc_v3_accept_necessary_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['accept_necessary']); ?>"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row g-2 my-0">
                                    <label class="col-sm-3 col-form-label text-end" for="cc_v3_accept_selected">
                                        <?php echo $BL['be_cc_v3_accept_selected']; ?>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text"
                                               name="cc_v3_accept_selected"
                                               id="cc_v3_accept_selected"
                                               class="form-control form-control-sm"
                                               placeholder="<?php echo $BL['cc_v3_accept_selected_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['accept_selected']); ?>"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row g-2 my-0">
                                    <label class="col-sm-3 col-form-label text-end" for="cc_v3_reject_all">
                                        <?php echo $BL['be_cc_v3_reject_all']; ?>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text"
                                               name="cc_v3_reject_all"
                                               id="cc_v3_reject_all"
                                               class="form-control form-control-sm"
                                               placeholder="<?php echo $BL['cc_v3_reject_all_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['reject_all']); ?>"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row g-2 my-0">
                                    <label class="col-sm-3 col-form-label text-end" for="cc_v3_customize">
                                        <?php echo $BL['be_cc_v3_customize']; ?>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text"
                                               name="cc_v3_customize"
                                               id="cc_v3_customize"
                                               class="form-control form-control-sm"
                                               placeholder="<?php echo $BL['cc_v3_customize_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['customize']); ?>"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row g-2 my-0">
                                    <label class="col-sm-3 col-form-label text-end" for="cc_v3_more">
                                        <?php echo $BL['be_cc_v3_more']; ?>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text"
                                               name="cc_v3_more"
                                               id="cc_v3_more"
                                               class="form-control form-control-sm"
                                               placeholder="<?php echo $BL['cc_v3_more_placeholder']; ?>"
                                               value="<?php echo html($template['cc_v3']['more']); ?>"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row g-2 my-0">
                                    <label class="col-sm-3 col-form-label text-end" for="cc_v3_link">
                                        <?php echo $BL['be_cc_v3_link']; ?>
                                    </label>
                                    <div class="col">
                                        <input type="text"
                                               name="cc_v3_link"
                                               id="cc_v3_link"
                                               class="form-control form-control-sm"
                                               placeholder="https://example.com/cookie-policy | cookie-policy"
                                               value="<?php echo html($template['cc_v3']['link']); ?>"
                                        />
                                    </div>
                                </div>

                                <div class="form-group row g-2 mt-0 mb-2">
                                    <div class="col-sm-3 col-form-label text-end">
                                        <?php echo $BL['be_cc_v3_sections']; ?>
                                    </div>
                                    <div class="col">
                                        <div class="border rounded p-2 mt-1">
                                            <!-- General -->
                                            <div class="form-group row g-2 my-0 pb-1">
                                                <strong class="col-4 col-md-3 col-lg-2 text-end text-info">
                                                    <?php echo $BL['be_cc_v3_section_general']; ?>
                                                </strong>
                                                <div class="col">
                                                    <label class="form-check-label ms-4" for="cc_v3_general_active">
                                                        <input class="form-check-input"
                                                               name="cc_v3_general_active"
                                                               id="cc_v3_general_active"
                                                               type="checkbox"
                                                               value="1"<?php is_checked($template['cc_v3']['sections']['general']['active'], 1); ?>
                                                        />
                                                        <?php echo $BL['be_cc_v3_sections_active']; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_general_title">
                                                    <?php echo $BL['be_cc_v3_sections_title']; ?>
                                                </label>
                                                <div class="col">
                                                    <input type="text"
                                                           name="cc_v3_general_title"
                                                           id="cc_v3_general_title"
                                                           class="form-control form-control-sm"
                                                           placeholder="<?php echo $BL['be_cc_v3_section_general_title_placeholder']; ?>"
                                                           value="<?php echo html($template['cc_v3']['sections']['general']['title']); ?>"
                                                    />
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_general_description">
                                                    <?php echo $BL['be_cc_v3_sections_description']; ?>
                                                </label>
                                                <div class="col">
                                                    <textarea name="cc_v3_general_description"
                                                              rows="3"
                                                              id="cc_v3_general_description"
                                                              class="form-control form-control-sm autosize"
                                                              placeholder="<?php echo $BL['be_cc_v3_section_general_description_placeholder']; ?>"><?php
                                                        echo html($template['cc_v3']['sections']['general']['description']);
                                                    ?></textarea>
                                                </div>
                                            </div>

                                            <hr class="my-2">

                                            <!-- Strictly necessary cookies -->
                                            <div class="form-group row g-2 my-0 pb-1">
                                                <strong class="col-4 col-md-3 col-lg-2 text-end text-info">
                                                    <?php echo $BL['be_cc_v3_section_necessary']; ?>
                                                </strong>
                                                <div class="col">
                                                    <label class="form-check-label ms-4">
                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               value="1"
                                                               checked="checked"
                                                               disabled="disabled"
                                                               id="cc_v3_section_necessary_active"
                                                        />
                                                        <?php echo $BL['be_cc_v3_sections_active']; ?>
                                                        <input type="hidden" name="cc_v3_necessary_active" id="cc_v3_necessary_active" value="1" /><!-- always active -->
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_necessary_title">
                                                    <?php echo $BL['be_cc_v3_sections_title']; ?>
                                                </label>
                                                <div class="col">
                                                    <input type="text"
                                                           name="cc_v3_necessary_title"
                                                           id="cc_v3_necessary_title"
                                                           class="form-control form-control-sm"
                                                           placeholder="<?php echo $BL['be_cc_v3_section_necessary_title_placeholder']; ?>"
                                                           value="<?php echo html($template['cc_v3']['sections']['necessary']['title']); ?>"
                                                    />
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_necessary_description">
                                                    <?php echo $BL['be_cc_v3_sections_description']; ?>
                                                </label>
                                                <div class="col">
                                                    <textarea name="cc_v3_necessary_description"
                                                              rows="3"
                                                              id="cc_v3_necessary_description"
                                                              class="form-control form-control-sm autosize"
                                                              placeholder="<?php echo $BL['be_cc_v3_section_necessary_description_placeholder']; ?>"><?php
                                                        echo html($template['cc_v3']['sections']['necessary']['description']);
                                                    ?></textarea>
                                                </div>
                                            </div>

                                            <hr class="my-2">

                                            <!-- Functional cookies -->
                                            <div class="form-group row g-2 my-0 pb-1">
                                                <strong class="col-4 col-md-3 col-lg-2 text-end text-info">
                                                    <?php echo $BL['be_cc_v3_section_functional']; ?>
                                                </strong>
                                                <div class="col">
                                                    <label class="form-check-label ms-4" for="cc_v3_functionality_active">
                                                        <input class="form-check-input"
                                                               name="cc_v3_functionality_active"
                                                               id="cc_v3_functionality_active"
                                                               type="checkbox"
                                                               value="1"<?php is_checked($template['cc_v3']['sections']['functionality']['active'], 1); ?>
                                                        />
                                                        <?php echo $BL['be_cc_v3_sections_active']; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_functionality_title">
                                                    <?php echo $BL['be_cc_v3_sections_title']; ?>
                                                </label>
                                                <div class="col">
                                                    <input type="text"
                                                           name="cc_v3_functionality_title"
                                                           id="cc_v3_functionality_title"
                                                           class="form-control form-control-sm"
                                                           placeholder="<?php echo $BL['be_cc_v3_section_functional_title_placeholder']; ?>"
                                                           value="<?php echo html($template['cc_v3']['sections']['functionality']['title']); ?>"
                                                    />
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_functionality_description">
                                                    <?php echo $BL['be_cc_v3_sections_description']; ?>
                                                </label>
                                                <div class="col">
                                                    <textarea name="cc_v3_functionality_description"
                                                              rows="3"
                                                              id="cc_v3_functionality_description"
                                                              class="form-control form-control-sm autosize"
                                                              placeholder="<?php echo $BL['be_cc_v3_section_functional_description_placeholder']; ?>"><?php
                                                        echo html($template['cc_v3']['sections']['functionality']['description']);
                                                    ?></textarea>
                                                </div>
                                            </div>

                                            <hr class="my-2">

                                            <!-- Performance and Analytics cookies -->
                                            <div class="form-group row g-2 my-0 pb-1">
                                                <strong class="col-4 col-md-3 col-lg-2 text-end text-info">
                                                    <?php echo $BL['be_cc_v3_section_analytics']; ?>
                                                </strong>
                                                <div class="col">
                                                    <label class="form-check-label ms-4" for="cc_v3_analytics_active">
                                                        <input class="form-check-input"
                                                               name="cc_v3_analytics_active"
                                                               id="cc_v3_analytics_active"
                                                               type="checkbox"
                                                               value="1"<?php is_checked($template['cc_v3']['sections']['analytics']['active'], 1); ?>
                                                        />
                                                        <?php echo $BL['be_cc_v3_sections_active']; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_analytics_title">
                                                    <?php echo $BL['be_cc_v3_sections_title']; ?>
                                                </label>
                                                <div class="col">
                                                    <input type="text"
                                                           name="cc_v3_analytics_title"
                                                           id="cc_v3_analytics_title"
                                                           class="form-control form-control-sm"
                                                           placeholder="<?php echo $BL['be_cc_v3_section_analytics_title_placeholder']; ?>"
                                                           value="<?php echo html($template['cc_v3']['sections']['analytics']['title']); ?>"
                                                    />
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_analytics_description">
                                                    <?php echo $BL['be_cc_v3_sections_description']; ?>
                                                </label>
                                                <div class="col">
                                                    <textarea name="cc_v3_analytics_description"
                                                              rows="3"
                                                              id="cc_v3_analytics_description"
                                                              class="form-control form-control-sm autosize"
                                                              placeholder="<?php echo $BL['be_cc_v3_section_analytics_description_placeholder']; ?>"><?php
                                                        echo html($template['cc_v3']['sections']['analytics']['description']);
                                                    ?></textarea>
                                                </div>
                                            </div>

                                            <hr class="my-2">

                                            <!-- Advertising and marketing cookies -->
                                            <div class="form-group row g-2 my-0 pb-1">
                                                <strong class="col-4 col-md-3 col-lg-2 text-end text-info">
                                                    <?php echo $BL['be_cc_v3_section_marketing']; ?>
                                                </strong>
                                                <div class="col">
                                                    <label class="form-check-label ms-4" for="cc_v3_marketing_active">
                                                        <input class="form-check-input"
                                                               name="cc_v3_marketing_active"
                                                               id="cc_v3_marketing_active"
                                                               type="checkbox"
                                                               value="1"<?php is_checked($template['cc_v3']['sections']['marketing']['active'], 1); ?>
                                                        />
                                                        <?php echo $BL['be_cc_v3_sections_active']; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_marketing_title">
                                                    <?php echo $BL['be_cc_v3_sections_title']; ?>
                                                </label>
                                                <div class="col">
                                                    <input type="text"
                                                           name="cc_v3_marketing_title"
                                                           id="cc_v3_marketing_title"
                                                           class="form-control form-control-sm"
                                                           placeholder="<?php echo $BL['be_cc_v3_section_marketing_title_placeholder']; ?>"
                                                           value="<?php echo html($template['cc_v3']['sections']['marketing']['title']); ?>"
                                                    />
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_marketing_description">
                                                    <?php echo $BL['be_cc_v3_sections_description']; ?>
                                                </label>
                                                <div class="col">
                                                    <textarea name="cc_v3_marketing_description"
                                                              rows="3"
                                                              id="cc_v3_marketing_description"
                                                              class="form-control form-control-sm autosize"
                                                              placeholder="<?php echo $BL['be_cc_v3_section_marketing_description_placeholder']; ?>"><?php
                                                        echo html($template['cc_v3']['sections']['marketing']['description']);
                                                    ?></textarea>
                                                </div>
                                            </div>

                                            <hr class="my-2">

                                            <!-- Social media cookies -->
                                            <div class="form-group row g-2 my-0 pb-1">
                                                <strong class="col-4 col-md-3 col-lg-2 text-end text-info">
                                                    <?php echo $BL['be_cc_v3_section_social']; ?>
                                                </strong>
                                                <div class="col">
                                                    <label class="form-check-label ms-4" for="cc_v3_social_active">
                                                        <input class="form-check-input"
                                                               name="cc_v3_social_active"
                                                               id="cc_v3_social_active"
                                                               type="checkbox"
                                                               value="1"<?php is_checked($template['cc_v3']['sections']['social']['active'], 1); ?>
                                                        />
                                                        <?php echo $BL['be_cc_v3_sections_active']; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_social_title">
                                                    <?php echo $BL['be_cc_v3_sections_title']; ?>
                                                </label>
                                                <div class="col">
                                                    <input type="text"
                                                           name="cc_v3_social_title"
                                                           id="cc_v3_social_title"
                                                           class="form-control form-control-sm"
                                                           placeholder="<?php echo $BL['be_cc_v3_section_social_title_placeholder']; ?>"
                                                           value="<?php echo html($template['cc_v3']['sections']['social']['title']); ?>"
                                                    />
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_social_description">
                                                    <?php echo $BL['be_cc_v3_sections_description']; ?>
                                                </label>
                                                <div class="col">
                                                    <textarea name="cc_v3_social_description"
                                                              rows="3"
                                                              id="cc_v3_social_description"
                                                              class="form-control form-control-sm autosize"
                                                              placeholder="<?php echo $BL['be_cc_v3_section_social_description_placeholder']; ?>"><?php
                                                        echo html($template['cc_v3']['sections']['social']['description']);
                                                    ?></textarea>
                                                </div>
                                            </div>

                                            <hr class="my-2">

                                            <!-- More information -->
                                            <div class="form-group row g-2 my-0 pb-1">
                                                <strong class="col-4 col-md-3 col-lg-2 text-end text-info">
                                                    <?php echo $BL['be_cc_v3_section_more']; ?>
                                                </strong>
                                                <div class="col">
                                                    <label class="form-check-label ms-4" for="cc_v3_more_active">
                                                        <input class="form-check-input"
                                                               name="cc_v3_more_active"
                                                               id="cc_v3_more_active"
                                                               type="checkbox"
                                                               value="1"<?php is_checked($template['cc_v3']['sections']['more']['active'], 1); ?>
                                                        />
                                                        <?php echo $BL['be_cc_v3_sections_active']; ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_more_title">
                                                    <?php echo $BL['be_cc_v3_sections_title']; ?>
                                                </label>
                                                <div class="col">
                                                    <input type="text"
                                                           name="cc_v3_more_title"
                                                           id="cc_v3_more_title"
                                                           class="form-control form-control-sm"
                                                           placeholder="<?php echo $BL['be_cc_v3_section_more_title_placeholder']; ?>"
                                                           value="<?php echo html($template['cc_v3']['sections']['more']['title']); ?>"
                                                    />
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end fw-normal" for="cc_v3_more_description">
                                                    <?php echo $BL['be_cc_v3_sections_description']; ?>
                                                </label>
                                                <div class="col">
                                                    <textarea name="cc_v3_more_description"
                                                              rows="3"
                                                              id="cc_v3_more_description"
                                                              class="form-control form-control-sm autosize"
                                                              placeholder="<?php echo $BL['be_cc_v3_section_more_description_placeholder']; ?>"><?php
                                                        echo html($template['cc_v3']['sections']['more']['description']);
                                                    ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-group row g-2 my-0">
                                    <label class="col-sm-3 col-form-label text-end" for="be_cc_v3_theme">
                                        <?php echo $BL['be_cc_v3_theme']; ?>
                                    </label>
                                    <div class="col">
                                        <input type="text"
                                               name="cc_v3_theme"
                                               id="be_cc_v3_theme"
                                               class="form-control form-control-sm"
                                               placeholder="light (<?= $BL['be_cc_v3_default']; ?>) <?= $BL['be_fsearch_or']; ?> dark <?= $BL['be_fsearch_or']; ?> custom&hellip;"
                                               title="<?php echo $BL['be_admin_tmpl_default']; ?>: light (<?= $BL['be_cc_v3_builtin'] . ', ' . $BL['be_cc_v3_default']; ?>), dark (<?= $BL['be_cc_v3_builtin']; ?>), custom"
                                               value="<?php echo html($template['cc_v3']['theme']) ?>"
                                        />
                                    </div>
                                </div>

                                <div class="form-group row g-2 my-0">
                                    <div class="col-sm-3 col-form-label text-end">
                                        <?php echo $BL['be_cc_v3_consent_modal']; ?>
                                    </div>
                                    <div class="col">
                                        <div class="border rounded p-2 mt-1">
                                            <div class="form-group row g-2 my-0 pb-1">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end" for="cc_v3_consent_layout">
                                                    <?php echo $BL['be_cc_v3_layout']; ?>
                                                </label>
                                                <div class="col">
                                                    <select class="form-select" name="cc_v3_consent_layout" id="cc_v3_consent_layout">
                                                        <option value="box"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'box'); ?>>Box</option>
                                                        <option value="box inline"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'box inline'); ?>>Box Inline</option>
                                                        <option value="box wide"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'box wide'); ?>>Box Wide</option>
                                                        <option value="cloud"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'cloud'); ?>>Cloud</option>
                                                        <option value="cloud inline"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'cloud inline'); ?>>Cloud Inline</option>
                                                        <option value="bar"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'bar'); ?>>Bar</option>
                                                        <option value="bar inline"<?php is_selected($template['cc_v3']['gui']['consent']['layout'], 'bar inline'); ?>>Bar Inline</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0 pb-1">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end" for="cc_v3_consent_position">
                                                    <?php echo $BL['be_cc_v3_position']; ?>
                                                </label>
                                                <div class="col">
                                                    <?php
                                                    if (in_array($template['cc_v3']['gui']['consent']['layout'], ['bar', 'bar inline'])) {
                                                        $cc_v3_consent_position_nobar = ' style="display:none;"';
                                                        $cc_v3_consent_position_bar = '';
                                                    } else {
                                                        $cc_v3_consent_position_nobar = '';
                                                        $cc_v3_consent_position_bar = ' style="display:none;"';
                                                    }

                                                    ?>
                                                    <select class="form-select" name="cc_v3_consent_position" id="cc_v3_consent_position">
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
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0 py-1">
                                                <strong class="col-4 col-md-3 col-lg-2">&nbsp;</strong>
                                                <div class="col">
                                                    <label class="form-check-label mx-4" for="cc_v3_consent_flip">
                                                        <input class="form-check-input"
                                                               name="cc_v3_consent_flip"
                                                               id="cc_v3_consent_flip"
                                                               type="checkbox"
                                                               value="1"<?php is_checked($template['cc_v3']['gui']['consent']['btn_flip'], 1); ?>
                                                        />
                                                        <?php echo $BL['be_cc_v3_btn_flip'] ?>
                                                    </label>
                                                    <label class="form-check-label ms-4" for="cc_v3_consent_equal">
                                                        <input class="form-check-input"
                                                               name="cc_v3_consent_equal"
                                                               id="cc_v3_consent_equal"
                                                               type="checkbox"
                                                               value="1"<?php is_checked($template['cc_v3']['gui']['consent']['btn_equal'], 1); ?>
                                                        />
                                                        <?php echo $BL['be_cc_v3_btn_equal'] ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row g-2 my-0">
                                    <div class="col-sm-3 col-form-label text-end">
                                        <?php echo $BL['be_cc_v3_preferences_modal']; ?>
                                    </div>
                                    <div class="col">
                                        <div class="border rounded p-2 mt-1">
                                            <div class="form-group row g-2 my-0 pb-1">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end" for="cc_v3_preferences_layout">
                                                    <?php echo $BL['be_cc_v3_layout']; ?>
                                                </label>
                                                <div class="col">
                                                    <select class="form-select" name="cc_v3_preferences_layout" id="cc_v3_preferences_layout">
                                                        <option value="box"<?php is_selected($template['cc_v3']['gui']['preferences']['layout'], 'box'); ?>>Box</option>
                                                        <option value="bar"<?php is_selected($template['cc_v3']['gui']['preferences']['layout'], 'bar'); ?>>Bar</option>
                                                        <option value="bar wide"<?php is_selected($template['cc_v3']['gui']['preferences']['layout'], 'bar wide'); ?>>Bar Wide</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0 pb-1">
                                                <label class="col-4 col-md-3 col-lg-2 col-form-label text-end" for="cc_v3_preferences_position">
                                                    <?php echo $BL['be_cc_v3_position']; ?>
                                                </label>
                                                <div class="col">
                                                    <select class="form-select" name="cc_v3_preferences_position" id="cc_v3_preferences_position"<?php
                                                    if ($template['cc_v3']['gui']['preferences']['layout'] === 'box'): ?> disabled="disabled"<?php endif;
                                                    ?>>
                                                        <option value="left"<?php is_selected($template['cc_v3']['gui']['preferences']['position'], 'left'); ?>>
                                                            <?= $BL['be_cc_v3_left']; ?>
                                                        </option>
                                                        <option value="right"<?php is_selected($template['cc_v3']['gui']['preferences']['position'], 'right'); ?>>
                                                            <?= $BL['be_cc_v3_right']; ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row g-2 my-0 py-1">
                                                <strong class="col-4 col-md-3 col-lg-2">&nbsp;</strong>
                                                <div class="col">
                                                    <label class="form-check-label mx-4" for="cc_v3_preferences_flip">
                                                        <input class="form-check-input"
                                                               name="cc_v3_preferences_flip"
                                                               id="cc_v3_preferences_flip"
                                                               type="checkbox"
                                                               value="1"<?php is_checked($template['cc_v3']['gui']['preferences']['btn_flip'], 1); ?>
                                                        />
                                                        <?php echo $BL['be_cc_v3_btn_flip'] ?>
                                                    </label>
                                                    <label class="form-check-label ms-4" for="cc_v3_preferences_equal">
                                                        <input class="form-check-input"
                                                               name="cc_v3_preferences_equal"
                                                               id="cc_v3_preferences_equal"
                                                               type="checkbox"
                                                               value="1"<?php is_checked($template['cc_v3']['gui']['preferences']['btn_equal'], 1); ?>
                                                        />
                                                        <?php echo $BL['be_cc_v3_btn_equal'] ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label" for="template_require_consent">
                                <input class="form-check-input" name="template_require_consent" id="template_require_consent" type="checkbox" value="1"<?php is_checked($template['require_consent']['enable'], 1); ?>>
                                <?php echo $BL['be_require_consent']; ?>
                            </label>

                            <div id="template-cr-form"<?php if (!$template['require_consent']['enable']): ?> style="display:none;"<?php endif; ?>>

                                <div class="form-group row g-2 mt-2 my-0">
                                    <label class="col-sm-3 col-form-label text-end" for="template_require_cookie_name">
                                        <?php echo $BL['be_consent_cookie_name']; ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               name="template_require_cookie_name"
                                               id="template_require_cookie_name"
                                               class="form-control form-control-sm"
                                               placeholder="<?php echo empty($template['cookie_consent']['enable']) ? 'cc_cookie' : $BL['placeholder_require_cookie_name']; ?>"
                                               value="<?php echo html($template['require_consent']['cookie_name']) ?>"
                                        >
                                    </div>
                                </div>

                                <div class="form-group row g-2 mt-0">
                                    <label class="col-sm-3 col-form-label text-end" for="template_require_cookie_value">
                                        <?php echo $BL['be_consent_cookie_value']; ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" name="template_require_cookie_value" id="template_require_cookie_value" class="form-control form-control-sm" placeholder="<?php echo $BL['placeholder_require_cookie_value']; ?>" value="<?php echo html($template['require_consent']['cookie_value']) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="form-group align-items-center mt-4 mb-0">
            <input name="template_id" type="hidden" value="<?php echo $template["id"] ?>"/>
            <button name="Submit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa-solid fa-rotate"></i> <?php echo $BL['be_admin_tmpl_button'] ?></button>
            <a href="phpwcms.php?do=admin&amp;p=11" class="btn btn-sm btn-danger ms-3"><i class="fa-solid fa-times"></i> <?php echo $BL['be_admin_struct_close'] ?></a>
        </div>
    </form>
    <script type="text/javascript">
    $(function(){
        // Tab persistence via localStorage
        const tmplTabKey = 'phpwcms_active_template_tab';
        $('#templateTabs a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            localStorage.setItem(tmplTabKey, $(e.target).attr('href'));
        });
        const activeTmplTab = localStorage.getItem(tmplTabKey);
        if (activeTmplTab && $('#templateTabs a[href="' + activeTmplTab + '"]').length) {
            $('#templateTabs a[href="' + activeTmplTab + '"]').tab('show');
        }

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
            if ($(this).is(':checked')) {
                $('#piwik-tracking').show();
            } else {
                $('#piwik-tracking').hide();
            }
        });
        $('#cc_v3_preferences_layout').on('change', function(){
            let $preferences_position = $('#cc_v3_preferences_position');
            if($(this).val() === 'box') {
                $preferences_position.prop('disabled', true).attr('disabled', 'disabled');
            } else {
                $preferences_position.prop('disabled', false).removeAttr('disabled');
            }
        });
        $('#cc_v3_consent_layout').on('change', function(){
            let $value = $(this).val();
            let $consent_position = $('#cc_v3_consent_position');
            if($value === 'bar' || $value === 'bar inline') {
                let $consent_position_bar = $consent_position.children('.v3_consent-bar');
                $consent_position_bar.show();
                $consent_position.children('.v3_consent-no-bar').hide();
                $consent_position_bar.first().prop('selected', true).attr('selected', 'selected');
            } else {
                let $consent_position_nobar = $consent_position.children('.v3_consent-no-bar');
                $consent_position_nobar.show();
                $consent_position.children('.v3_consent-bar').hide();
                $consent_position_nobar.first().prop('selected', true).attr('selected', 'selected');
            }
        });
    });
    </script>
    <?php
}
