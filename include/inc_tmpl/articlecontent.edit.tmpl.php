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

initJsCalendar();

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
?>
<script type="text/javascript">
    var initialContentFormData = '';
    $(function() {
        initialContentFormData = $('#articlecontent').serialize();
    });

    function cancelContentEdit() {
        if ($('#articlecontent').serialize() !== initialContentFormData) {
            bsConfirmWarning('<?php echo js_singlequote($BL["be_dialog_warn_nosave"]); ?>', function() {
                location.href='phpwcms.php?do=articles&p=2&s=1&id=<?php echo $content["aid"] ?>';
            }, '<?php echo js_singlequote($BL["be_yes"]); ?>', '<?php echo js_singlequote($BL["be_no"]); ?>');
        } else {
            location.href='phpwcms.php?do=articles&p=2&s=1&id=<?php echo $content["aid"] ?>';
        }
        return false;
    }

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

    function onImageSelected(target, id, name) {
        var hasImage = (id && parseInt(id, 10) > 0);
        if (target === '_') {
            var container = $('#cimage_preview_container');
            if (container.length) {
                if (hasImage) {
                    container.html('<img src="img/cmsimage.php/200x200/' + id + '" alt="" />');
                } else {
                    container.html('&nbsp;');
                }
            }
            var btn = $('#cimage_delete_button');
            if (btn.length) {
                if (hasImage) {
                    btn.removeClass('disabled').css({'opacity': '', 'pointer-events': ''});
                } else {
                    btn.addClass('disabled').css({'opacity': '0.5', 'pointer-events': 'none'});
                }
            }
        }
    }
</script>
<form action="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;id=<?php echo $content["aid"]."&amp;acid=".$content["id"] ?>" method="post" name="articlecontent" id="articlecontent" class="form-horizontal" <?php

    // Some javascript actions neccessary on submit
    switch ($content["type"]) {

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

    if (empty($content["id"]) && empty($content['block'])) {
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
<div class="card">
  <div class="card-header"><h2>
    <?php

    echo $BL['be_article_cnt_title'].' &#8212; <span style="text-transform: uppercase;">';
    echo $wcs_content_type[$content["type"]];
    if (!empty($content["module"])) {
        echo ': '.$BL['modules'][$content["module"]]['listing_title'];

        // check if Module is in setting mode
        if (!empty($phpwcms['modules'][$content["module"]]['setting'])) {
            $content['cp_setting_mode'] = true;
        }
    }
    echo '</span>';

    ?></h2>
  </div>

  <div class="card-body">
    <div class="form-group align-items-center form-row">
      <div class="col-sm-2 text-right">
        <span data-toggle="tooltip" title="<?php echo html($BL['be_article_cat'].' - '.$BL['be_article_atitle']); ?>"><i class="fas fa-info-circle text-blue"></i></span>
      </div>
      <div class="col-sm-auto">
        <strong><?php echo html($content["article"]['acat_name'].' [ID:'.$content['article']['acat_id'].']'.' - '.$content["article"]['article_title']) ?> </strong>
      </div>
    </div>

    <div class="form-group align-items-center form-row">
      <label for="target_ctype" class="col-sm-2 col-form-label text-right"><?php
        echo $BL['be_article_cnt_type'];
        $enable_disable = '';
    ?></label>
      <div class="col-sm-4">
<?php
    // Menü mit Content Typen erstellen
    // build select box options and remember the "old" value for javascript
    $temp_select                = '';
    $temp_count                 = 0;
    $contentpart_temp_selected  = 0;
    $user_selected_cp           = isset($_SESSION["wcs_user_cp"]) && count($_SESSION["wcs_user_cp"]);

    if (is_array($article["article_cntpart"]) && count($article["article_cntpart"])) {
        if (!in_array($content['type'], $article["article_cntpart"])) {
            $article["article_cntpart"][] = $content['type'];
        }

        // list all content parts usable for this article category
        foreach ($article["article_cntpart"] as $value) {
            if ($user_selected_cp && !isset($_SESSION["wcs_user_cp"][$value]) && $value != $content['type']) {
                continue;
            }

            if (isset($wcs_content_type[$value])) {
                $temp_select .= getContentPartOptionTag($value, $wcs_content_type[$value], $content['type'], $content['module']);
                $temp_count++;
            }
            $value1 = $value * (-1);
            if (isset($BL['be_admin_optgroup_label'][$value1]) && $value) {
                $temp_select .= '<optgroup label="[ '.$BL['be_admin_optgroup_label'][$value1].' ]" class="cntOptGroup"></optgroup>'."\n";
            }
        }
    }
    if (!$temp_count) {
        //list all available content parts
        foreach ($wcs_content_type as $key => $value) {
            if ($user_selected_cp && !isset($_SESSION["wcs_user_cp"][$key]) && $key != $content['type']) {
                continue;
            }

            $temp_select .= getContentPartOptionTag($key, $value, $content['type'], $content['module']);
            $temp_count++;
        }
    }

?>
        <select name="target_ctype" id="target_ctype" class="custom-select form-control form-control-sm" data-prev-index="<?php echo $contentpart_temp_selected; ?>">
<?php
    echo $temp_select;
?>
        </select>
<?php
$BE['BODY_CLOSE'][] = '
<script type="text/javascript">
$(function() {
	var $select = $("#target_ctype");
	var prevIndex = $select.attr("data-prev-index");
	$select.on("change", function(e) {
		var $this = $(this);
		bsConfirmWarning("' . js_singlequote($BL['be_func_switch_contentpart']) . '", function() {
			$this.closest("form").submit();
		}, "' . js_singlequote($BL['be_yes']) . '", "' . js_singlequote($BL['be_no']) . '");
		
		$("#bootstrapConfirmModal").one("hidden.bs.modal", function() {
			setTimeout(function() {
				$select.prop("selectedIndex", prevIndex);
			}, 100);
		});
	});
});
</script>
';
?>
      </div>
    </div>

<?php

// Content Part Setting Mode — hide all settings related to article and content part rendering
if ($content['cp_setting_mode']):
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
        if (!empty($content['article']['acat_template'])) {
            $content['current_template'] = _dbGet('phpwcms_template', '*', 'template_trash=0 AND template_id='._dbEscape($content['article']['acat_template']), '', '', 1);
        }
        if (!isset($content['current_template'][0])) {
            $content['current_template'] = _dbGet('phpwcms_template', '*', 'template_trash=0 AND template_default=1', '', '', 1);
        }
        if (!isset($content['current_template'][0])) {
            $content['current_template'] = _dbGet('phpwcms_template', '*', 'template_trash=0', '', 'template_default DESC', 1);
        }

        $content['blocks'] = array();

        if (isset($content['current_template'][0]['template_var'])) {
            $content['template_name'] = html($content['current_template'][0]['template_name']);
            if ($content['current_template'][0]['template_default']) {
                $content['template_name'] .= ' ('.$BL['be_admin_tmpl_default'].')';
            }
            $content['current_template'] = unserialize($content['current_template'][0]['template_var'], ['allowed_classes' => false]);
            if (!empty($content['current_template']['customblock'])) {
                $content['current_template'] = explode(',', $content['current_template']['customblock']);
                if (count($content['current_template'])) {
                    $content['blocks'][] = '<optgroup label="'.$BL['be_admin_page_blocks'].', '.$BL['be_admin_page_customblocks'].'">';
                    foreach ($content['current_template'] as $value) {
                        $value = trim($value);
                        if ($value !== '') {
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

    $anchor_title = empty($content["id"]) ? '' : ' title="cpid'.$content["id"].'"';

    // handle tab settings
    $content["tab_style"] = ' style="display:none"';

    if (empty($content["tab"])) {
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

        if ($content["tab_number"].$content["tab_title"]) {
            $content["tab"]         = 1;
            $content["tab_style"]   = '';
        }
    }
?>

    <div class="form-group align-items-center form-row">
      <label for="ctitle" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_cnt_ctitle'] ?></label>
      <div class="col-sm-10">
        <input name="ctitle" type="text" id="ctitle" class="form-control form-control-sm" value="<?php echo html($content["title"]) ?>" maxlength="2000" />
      </div>
    </div>

    <div class="form-group align-items-center form-row">
      <label for="csubtitle" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_asubtitle'] ?></label>
      <div class="col-sm-10">
        <input name="csubtitle" type="text" id="csubtitle" class="form-control form-control-sm" value="<?php echo html($content["subtitle"]) ?>" maxlength="2000" />
      </div>
    </div>

    <div class="form-group align-items-center form-row">
      <label for="cblock" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_show_content'] ?></label>
      <div class="col-sm-4">
        <select name="cblock" id="cblock" class="custom-select form-control form-control-sm" onchange="checkCntBlockPaginate(this);">
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
      </div>
      <label for="ctab" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_paginate_subsection']; ?></label>
      <div class="col">
        <select name="ctab" id="ctab" class="custom-select form-control form-control-sm" onchange="checkTabStatus(this);"<?php if ($content["block"] == 'SYSTEM'): ?> disabled="disabled"<?php endif; ?> >
					<option value="0"<?php is_selected(0, $content["tab_type"]); ?>><?php echo $BL['be_off'] ?></option>
					<option value="1"<?php is_selected(1, $content["tab_type"]); ?>><?php echo $BL['be_ctype_tabs'] ?></option>
					<option value="2"<?php is_selected(2, $content["tab_type"]); ?>><?php echo $BL['be_ctype_accordion'] ?></option>
  <?php
      if (isset($template_default['attributes']['cpgroup_custom']) && is_array($template_default['attributes']['cpgroup_custom']) && count($template_default['attributes']['cpgroup_custom'])):
          foreach ($template_default['attributes']['cpgroup_custom'] as $tab_type_value => $value):
  ?>
                          <option value="<?php echo $tab_type_value ?>"<?php is_selected($tab_type_value, $content["tab_type"]); ?>><?php echo html($value['title']) ?></option>
  <?php
          endforeach;
      endif;
  ?>
        </select>
        <script>

            var cTabStatus = <?php echo $content["tab_type"] ? 'true' : 'false' ?>, loadblock = true;

            function checkTabStatus(tabVal) {

                var tabValue = tabVal.options[tabVal.selectedIndex].value;

                cTabStatus = tabValue !== '0';

                if(cTabStatus == false) {
                    //document.getElementById('ctab1').style.display = 'none';
                    document.getElementById('ctab2').style.display = 'none';
                } else {
                    //document.getElementById('ctab1').style.display = '';
                    document.getElementById('ctab2').style.display = '';
                }

                tabVal.blur();
            }

            function setTabStatus(enabled) {
                document.getElementById('ctab').disabled = !enabled;
            }

            function checkCntBlockPaginate(obj) {
                var paginate = document.getElementById("cpaginate_page");
                var block = obj.options[obj.selectedIndex].value;
                var system1 = document.getElementById('system1');
                var ctab = document.getElementById('ctab');

                if(block == 'SYSTEM') {
                    //system1.style.display = 'table-row';
                    system1.style.display = '';
                    ctab.value = '0';
                    ctab.disabled = true;
                    cTabStatus = false;

                    //document.getElementById('ctab1').style.display = 'none';
                    document.getElementById('ctab2').style.display = 'none';

                } else {

                    system1.style.display = 'none';
                    ctab.disabled = false;

                }

                if(block != "CONTENT") {
                    if(paginate.value != "0" && loadblock == false) {
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

                var ctab = document.getElementById('ctab');
                var ctab_title = document.getElementById('ctab_title');
                var ctab_number = document.getElementById('ctab_number');

                if(ctab.selectedIndex > 0 && ctab_title.value === '' && ctab_number.value === '') {
                    return confirm('<?php echo PHPWCMS_CHARSET === 'utf-8' ? $BL['confirm_cp_tab_warning'] : mb_convert_encoding($BL['confirm_cp_tab_warning'], PHPWCMS_CHARSET); ?>');
                }

                return true;
            }

        </script>
      </div>
    </div>

    <div class="form-group align-items-center form-row" id="system1"<?php if ($content["block"] !== 'SYSTEM'): ?> style="display:none;"<?php endif; ?>>
      <label for="ctid" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_rendering'] ?></label>
      <div class="col-sm-4">
				<select name="ctid" id="ctid" class="custom-select form-control form-control-sm">
					<option value="0"<?php echo is_selected(0, $content['tid']) ?>><?php echo $BL['be_custom_scriptlogic'] ?></option>
					<option value="1"<?php echo is_selected(1, $content['tid']) ?>><?php echo $BL['be_article_forlist'] ?></option>
					<option value="2"<?php echo is_selected(2, $content['tid']) ?>><?php echo $BL['be_article_forfull'] ?></option>
					<option value="3"<?php echo is_selected(3, $content['tid']) ?>><?php echo $BL['be_article_forlist'].' + '.$BL['be_article_forfull'] ?></option>
				</select>
      </div>
    </div>

    <!-- ctab section -->
    <div class="form-group align-items-center form-row" id="ctab2"<?php echo $content["tab_style"] ?>>
      <label for="ctab_title" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_paginate_subsection'] ?></label>
      <div class="col-sm-4">
        <input name="ctab_title" type="text" id="ctab_title" class="form-control form-control-sm" value="<?php echo html($content["tab_title"]) ?>" maxlength="1500" />
      </div>
      <label for="ctab_number" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_ctype_number'] ?></label>
      <div class="col-sm-4">
        <input name="ctab_number" type="text" id="ctab_number" class="form-control form-control-sm" value="<?php echo $content["tab_number"] ?>" maxlength="4" onkeyup="if(!parseInt(this.value,10))this.value='';" />
      </div>
    </div>
    <!-- ctab section end -->


<?php
  if (isset($content["error"])) {
      ?>
    <div class="alert alert-danger">
        <h2><?php echo $BL['be_admin_usr_err'] ?>:&nbsp;</h2>
        <?php
            //Fehlerdarstellung
            $content["error_result"]="";
      foreach ($content["error"] as $value) {
          $content["error_result"] .= "> ".$value."\n";
      }
      echo nl2br(html(chop($content["error_result"])));
      unset($content["error_result"]); ?>
    </div>
<?php
  }
?>

<?php

    // check if it is necessary to display paginate stuff
    // in case no content part pagination isset for current article

    $content["paginate_page"] = empty($content["paginate_page"]) ? 0 : intval($content["paginate_page"]);

    if (empty($content['article']['article_paginate']) || $content["block"] === 'SYSTEM') {
        echo '<input name="cpaginate_title" type="hidden" id="cpaginate_title" value="'.html($content["paginate_title"]).'" />';
        echo '<input name="cpaginate_page" type="hidden" id="cpaginate_page" value="'.$content["paginate_page"].'" />';
    } else {
        ?>

    <div class="form-group align-items-center form-row">
      <label for="cpaginate_page" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_paginate_subsection'] ?></label>
      <div class="col-sm-auto">
        <input name="cpaginate_page" type="text" id="cpaginate_page" class="form-control form-control-sm" value="<?php echo $content["paginate_page"] ?>" maxlength="3" onkeyup="if(!parseInt(this.value,10))this.value='0';" />
      </div>
      <label for="cpaginate_title" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_subsection_tite'].' ('.$BL['be_pagination'].')' ?></label>
      <div class="col-sm-auto">
        <input name="cpaginate_title" type="text" id="cpaginate_title" class="form-control form-control-sm" value="<?php echo html($content["paginate_title"]) ?>" maxlength="2000" />
      </div>
    </div>

    <script type="text/javascript">

        checkCntBlockPaginate(document.getElementById("cblock"));
        loadblock = false;

    </script>

<?php
    }
    // end paginate check


// end non content part setting mode
endif;

?>

    <hr />

    <div class="form-group row">
      <div class="col-sm-2"></div>
      <div class="col-sm-10 text-center text-sm-left">
<?php

// render buttons only once and save the buffer
if (!empty($content["id"])) {
    $buttonActionLink = rel_url(array('phpwcms-preview'=>1), array(), empty($content['article']["article_alias"]) ? (empty($content["aid"]) ? 'id='.$content["id"] : 'aid='.$content["aid"]) : $content['article']["article_alias"]);
    $buttonAction  = '<button type="button" value="'.$BL['be_func_struct_preview'].'" class="btn btn-sm btn-info float-sm-right" title="'.$BL['be_func_struct_preview'].'" ';
    $buttonAction .= 'onclick="window.open(\''.$buttonActionLink."', 'articlePreviewWindows');return false;\">";
    $buttonAction .= $BL['be_func_struct_preview']."</button>" . LF;
} else {
    $buttonAction  = '';
}

ob_start();

?>
    <input name="Submit" type="submit" class="btn btn-sm btn-blue" id="submit-button" value="<?php echo $sendbutton ?>" />
    <input name="SubmitClose" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
    <input name="donotsubmit" type="button" class="btn btn-sm btn-danger ml-3" value="<?php echo  $BL['be_newsletter_button_cancel'] ?>" onclick="return cancelContentEdit();" />
    <?php echo $buttonAction; ?>
<?php

$_save_close_buttons = ob_get_clean();

echo $_save_close_buttons;

?>
      </div>
    </div>

    <hr />

<?php

    // show content part specific form elements
    if ($content['type'] != 30 && file_exists(PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt'.$content['type'].'.inc.php')) {
        include_once PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt'.$content['type'].'.inc.php';
    } elseif ($content['type'] == 30 && file_exists($phpwcms['modules'][$content["module"]]['path'].'inc/cnt.form.php')) {
        include_once $phpwcms['modules'][$content["module"]]['path'].'inc/cnt.form.php';
    } else {
        include_once PHPWCMS_ROOT.'/include/inc_tmpl/content/cnt0.inc.php';
    }

    if (in_array($content['type'], array(32))):
?>
<?php
    else:
?>
<?php
    endif;
?>
    <hr />


    <div class="form-group align-items-center form-row">
      <span class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_cnt_space'] ?></span>
      <div class="col-sm-10">
        <div class="d-flex flex-wrap align-items-center">
          <div class="my-1 mr-3">
            <div class="input-group input-group-sm">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <input name="ccb" type="checkbox" id="ccb" value="1" <?php if ($content["before"] !== '') {echo "checked";} ?> onclick="if(!this.checked){this.form.cbefore.value='';}else{ if(this.form.cbefore.value=='') this.checked=false;}" />
                </div>
                <label class="input-group-text" for="cbefore"><?php echo $BL['be_article_cnt_before'] ?></label>
              </div>
              <input name="cbefore" type="number" id="cbefore" class="form-control form-control-sm" value="<?php echo $content["before"] ?>" size="5" maxlength="5" onkeyup="validate_before_after(this, 'ccb');" />
              <div class="input-group-append">
                <span class="input-group-text"><?php echo empty($template_default['article']['div_spacer_unit']) ? 'px' : $template_default['article']['div_spacer_unit']; ?></span>
              </div>
            </div>
          </div>
          <div class="my-1">
            <div class="input-group input-group-sm">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <input name="cca" type="checkbox" id="cca" value="1" <?php if ($content["after"] !== '') {echo 'checked';} ?> onclick="if(!this.checked){this.form.cafter.value='';}else{ if(this.form.cafter.value=='') this.checked=false;}" />
                </div>
                <label class="input-group-text" for="cafter"><?php echo $BL['be_article_cnt_after'] ?></label>
              </div>
              <input name="cafter" type="number" id="cafter" class="form-control form-control-sm" value="<?php echo $content["after"] ?>" size="5" maxlength="5" onkeyup="validate_before_after(this, 'cca');" />
              <div class="input-group-append">
                <span class="input-group-text"><?php echo empty($template_default['article']['div_spacer_unit']) ? 'px' : $template_default['article']['div_spacer_unit']; ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

      <div class="form-group align-items-center form-row">
          <label for="cattr_class" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_attribute_class'] ?></label>
          <div class="col-sm-4">
              <input name="cattr_class" id="cattr_class" type="text" value="<?php echo html($content["attr_class"]); ?>" class="form-control form-control-sm" maxlength="255" />
          </div>
          <label for="cattr_id" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_attribute_id']; ?></label>
          <div class="col-sm-4">
              <input name="cattr_id" id="cattr_id" type="text" value="<?php echo html($content["attr_id"]); ?>" class="form-control form-control-sm" maxlength="255" />
          </div>
      </div>

    <hr />

    <div class="form-group align-items-center form-row">
       <span class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_rendering'] ?></span>
       <div class="col-sm-10">
         <div class="d-flex flex-wrap align-items-center">
           <div class="my-1 mr-3">
             <div class="date input-group input-group-sm" id="datetimepicker1" data-target-input="#clivedate">
               <div class="input-group-prepend">
                 <div class="input-group-text">
                   <input name="set_livedate" type="checkbox" id="set_livedate" value="1"<?php is_checked(1, $set_livedate) ?> onclick="if (this.checked) { var d = '<?php echo phpwcms_strtotime($content['livedate'], $BL['be_longdatetime'], '') ?>'; $('#datetimepicker1').datetimepicker('date', d || moment()); } else { $('#datetimepicker1').datetimepicker('clear'); }" />
                 </div>
                 <label class="input-group-text" for="clivedate"><?php echo $BL['be_msg_from'] ?></label>
               </div>
               <input name="clivedate" type="text" id="clivedate" class="form-control form-control-sm datetimepicker datetimepicker-input" placeholder="<?php echo $BL['default_date_format'] . ' ' . $BL['default_time_format'] . ':SS'; ?>" value="<?php echo phpwcms_strtotime($content["livedate"], $BL['be_longdatetime'], ''); ?>" data-target="#datetimepicker1" autocomplete="off" />
               <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                 <span class="datepickerbutton input-group-text form-control form-control-sm btn-blue"><i class="far fa-calendar-alt fa-fw"></i></span>
               </div>
             </div>
           </div>
           <div class="my-1 mr-3">
              <div class="date input-group input-group-sm" id="datetimepicker2" data-target-input="#ckilldate">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <input name="set_killdate" type="checkbox" id="set_killdate" value="1"<?php is_checked(1, $set_killdate) ?> onclick="if (this.checked) { var d = '<?php echo phpwcms_strtotime($content['killdate'], $BL['be_longdatetime'], '') ?>'; $('#datetimepicker2').datetimepicker('date', d || moment()); } else { $('#datetimepicker2').datetimepicker('clear'); }" />
                  </div>
                  <label class="input-group-text" for="ckilldate"><?php echo $BL['be_article_aend'] ?></label>
                </div>
                <input name="ckilldate" type="text" id="ckilldate" class="form-control form-control-sm datetimepicker datetimepicker-input" placeholder="<?php echo $BL['default_date_format'] . ' ' . $BL['default_time_format'] . ':SS'; ?>" value="<?php echo phpwcms_strtotime($content["killdate"], $BL['be_longdatetime'], ''); ?>" data-target="#datetimepicker2" autocomplete="off" />
                <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                  <span class="datepickerbutton input-group-text form-control form-control-sm btn-blue"><i class="far fa-calendar-alt fa-fw"></i></span>
                </div>
              </div>
            </div>
            <div class="my-1">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <label class="input-group-text" for="csorting"><?php echo $BL['be_cnt_sortvalue'] ?></label>
                </div>
                <input class="form-control form-control-sm" name="csorting" type="number" id="csorting" value="<?php echo $content["sorting"] ?>" maxlength="10" onkeyup="if(!parseInt(this.value,10))this.value='0';" />
              </div>
            </div>
         </div>
       </div>
    </div>

    <script type="text/javascript">
        $(function () {
            $('#datetimepicker1').datetimepicker({
              locale: '<?php echo $_SESSION['wcs_user_lang'] ?>',
              format: "DD.MM.YYYY HH:mm:ss",
              useCurrent: false,
              buttons: {
                showClose: true
              }
            });
            $("#datetimepicker1").on("change.datetimepicker", function (e) {
              if (e.date !== undefined) {
                document.articlecontent.set_livedate.checked = !!e.date;
              }
            });

            $('#datetimepicker2').datetimepicker({
              locale: '<?php echo $_SESSION['wcs_user_lang'] ?>',
              format: "DD.MM.YYYY HH:mm:ss",
              useCurrent: false,
              buttons: {
                showClose: true
              }
            });
            $("#datetimepicker2").on("change.datetimepicker", function (e) {
              if (e.date !== undefined) {
                document.articlecontent.set_killdate.checked = !!e.date;
              }
            });
        });
    </script>
    <div class="form-inline form-group align-items-center form-row">
      <span class="col-sm-2 col-form-label text-right d-block"><?php echo $BL['be_ftptakeover_status'] ?></span>
        <div class="col-sm-auto">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="cvisible" name="cvisible" value="1"<?php is_checked(1, $content["visible"]); ?>/>
            <label class="form-check-label" for="cvisible"><?php echo $BL['be_admin_struct_visible'] ?></label>
          </div>
        </div>
        <div class="col-sm-auto">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="cgranted" name="cgranted" value="1"<?php is_checked(1, $content["granted"]); ?> />
            <label class="form-check-label" for="cgranted"><?php echo $BL['be_granted_feuser'] ?></label>
          </div>
        </div>
        <div class="col-sm-auto">
          <div class="form-check form-check-inline">
            <input class="form-check-input" name="ctop" type="checkbox" id="ctop" value="1"<?php is_checked(1, $content["top"]); ?> />
            <label class="form-check-label" for="ctop"><?php echo $BL['be_article_cnt_toplink'] ?></label>
          </div>
        </div>
        <div class="col-sm-auto">
          <div class="form-check form-check-inline">
            <input class="form-check-input" name="canchor" type="checkbox" id="canchor" value="1"<?php is_checked(1, $content["anchor"]); echo $anchor_title ?> />
            <label class="form-check-label" for="canchor"><?php echo $BL['be_article_cnt_anchor'] ?></label>
          </div>
        </div>
    </div>


    <hr />

    <div class="form-group row">
      <div class="col-sm-2"></div>
      <div class="col-sm-10 text-center text-sm-left">
        <input name="caktion" type="hidden" id="caktion" value="1" />
        <input name="caid" type="hidden" id="caid" value="<?php echo $article["article_id"] ?>" />
        <input name="cid" type="hidden" id="cid" value="<?php echo  $content["id"] ?>" />
        <input name="ctype" type="hidden" id="ctype" value="<?php echo  $content["type"] ?>" />
        <?php echo str_replace('"submit-button', '"submit-button-bottom', $_save_close_buttons); ?>
      </div>
    </div>

    <hr />

    <div class="form-group form-row">
      <label class="col-sm-2 col-form-label text-right" for="ccomment"><?php echo $BL['be_profile_label_notes'] ?></label>
      <div class="col-sm-10">
        <textarea name="ccomment" id="ccomment" class="form-control autosize" rows="5"><?php echo html($content["comment"]) ?></textarea>
      </div>
    </div>

  </div>
</div>

</form>
