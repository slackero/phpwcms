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
    $acat_struct        = 0;

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

if (!isset($acat_struct)) {
    $acat_struct = intval($_GET['struct']);
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

$cancel_url = 'phpwcms.php?' . get_token_get_string() . '&amp;do=' . (($do === 'admin') ? 'admin&amp;p=6' : 'articles');
if ($acat_id === 'index' || $acat_id === 0) {
    $cancel_url .= '#struct_0';
} elseif (!empty($acat_id)) {
    $cancel_url .= '#struct_' . intval($acat_id);
} elseif (!empty($acat_struct)) {
    $cancel_url .= '#struct_' . intval($acat_struct);
}

?>
<form action="include/inc_act/act_structure.php" method="post" name="editsitestructure" id="editsitestructure" onsubmit="selectAllOptions(this.acat_access);selectAllOptions(this.acat_cp);var x = wordcount(this.acat_name.value);if(x&lt;1) {alert('Fill in a category title! \n\n('+x+' words total)');this.acat_name.focus();return false;}">

<input name="acat_sort_temp" type="hidden" value="<?php echo $acat_sort; ?>" />
<input name="acat_struct" type="hidden" id="acat_struct" value="<?php echo $acat_struct; ?>" />
<input name="acat_new" type="hidden" id="acat_new" value="<?php echo $acat_new; ?>" />
<input name="acat_id" type="hidden" id="acat_id" value="<?php echo $acat_id; ?>" />

<div class="row align-items-center">
  <div class="col-12 col-sm-auto text-center text-sm-start">
    <h1><?php echo $BL['be_admin_struct_title'] ?></h1>
  </div>
  <div class="col text-center text-sm-end mb-3">
    <div class="form-group align-items-center">
			<button name="submit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa-solid fa-rotate"></i> <?php echo empty($acat_id) ? $BL['be_article_cnt_button2'] : $BL['be_article_cnt_button1'] ?></button>
			<button name="SubmitClose" type="submit" class="btn btn-sm btn-blue ms-1" value="1"><i class="fa-solid fa-check"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
			<a href="<?php echo $cancel_url; ?>" class="btn btn-sm btn-danger ms-3"><i class="fa-solid fa-times"></i> <?php echo $BL['be_newsletter_button_cancel'] ?></a>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header"><h2>
  <?php echo $BL['be_admin_struct_title'] ?> <span class="fw-normal"><?php echo $BL['be_admin_struct_child'] ?></span>: <strong class="text-danger"><?php
              //Anzeigen des Kategorienamens (Menuepunkt)
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

            ?></strong>
  </h2></div>

<div class="card-body">

  <div class="form-group align-items-center row g-2">
      <label for="be_admin_struct_cat" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_cat'] ?></label>
      <div class="col">
          <input name="acat_name" class="form-control form-control-sm" id="acat_name" onchange="this.value=this.value.trim();" value="<?php echo html($acat_title) ?>" size="50" maxlength="2000" type="text">
      </div>
  </div>

  <div class="form-group align-items-center row g-2">
      <label for="be_admin_struct_cat" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_alt'] ?></label>
      <div class="col">
          <input name="acat_title" class="form-control form-control-sm" id="acat_title" onchange="this.value=this.value.trim();" value="<?php echo html($acat_title_alt) ?>" size="50" maxlength="2000" type="text">
      </div>
  </div>

  <div class="form-group align-items-center row g-2">
      <label for="" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_alias']; ?></label>
      <div class="col-sm-4">
          <input name="acat_alias" class="form-control form-control-sm" id="acat_alias" value="<?php echo html($acat_alias) ?>" size="50" maxlength="1000" type="text" <?php
                if(empty($phpwcms['allow_empty_alias'])): ?> onfocus="set_article_alias(true, 'struct');"<?php endif; ?> onchange="this.value=create_alias(this.value);" />
      </div>
      <div class="col">
          <a class="underline" href="#" onclick="return set_article_alias(false, 'struct');"><?php echo $BL['be_admin_struct_cat']; ?></a>
            <span class="mx-2">+</span><a class="underline" href="#" onclick="return set_article_alias(false, 'struct', '<?php echo $acat_parent_alias ?>');" title="<?php echo $acat_parent_alias ?>"><?php echo $BL['be_parental_alias']; ?></a>
            <span class="mx-2">+</span><a class="underline" href="#" onclick="return set_article_alias(false, 'struct', '<?php echo $acat_struct_alias ?>');" title="<?php echo $acat_struct_alias ?>"><?php echo $BL['be_admin_struct_title']; ?></a>
      </div>
  </div>

  <div class="form-group align-items-center row g-2">
      <label class="col-sm-2 col-form-label text-end"></label>
      <div class="col">
        <div class="form-check form-switch">
          <input class="form-check-input" name="acat_onepage" type="checkbox" role="switch" id="acat_onepage" value="1"<?php if(!empty($acat_onepage)) { echo ' checked="checked"';} ?> />
          <label class="form-check-label" for="acat_onepage"><?php echo $BL['be_onepage_id']; ?></label>
        </div>
      </div>
  </div>

  <?php if($acat_lang_mode):
            $lang_default   = ' ('.$BL['be_admin_tmpl_default'].')';
  ?>
  <div class="form-group row g-2">
      <label class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_profile_label_lang'] ?></label>
      <div class="col">
        <div class="form-check form-check-inline">
					<input class="form-check-input lang-default" type="radio" name="acat_lang" id="acat_lang" value="" <?php is_checked('', $acat_lang); ?> />
					<label class="form-check-label"><span class="flag-icon flag-icon-<?php echo $phpwcms['default_lang'] ?> mt-1" data-bs-toggle="tooltip" title="<?php echo get_language_name($phpwcms['default_lang']) ?>"></span><?php echo $lang_default ?></label>
        </div>

        <?php foreach($phpwcms['allowed_lang'] as $key => $lang):
					$lang = strtolower($lang);
					if($lang == $phpwcms['default_lang']) {
							continue;
					}
        ?>

        <div class="form-check form-check-inline">
					<input class="form-check-input lang-opt" type="radio" name="acat_lang" id="acat_lang2" value="<?php echo $lang ?>"<?php is_checked($lang, $acat_lang); ?> />
					<label class="form-check-label"><span class="flag-icon flag-icon-<?php echo $lang ?>" data-bs-toggle="tooltip" title="<?php echo get_language_name($lang) ?>"></span></label>
        </div>

        <?php endforeach; ?>

        <div class="row align-items-center" style="margin:5px 0;border-top:1px solid #D9DEE3;border-bottom:1px solid #D9DEE3;padding:10px 0;<?php if($acat_lang == ''): ?>display:none;<?php endif; ?>" id="lang-id-select">
          <div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="acat_lang_type" value="category"<?php is_checked('category', $acat_lang_type); ?> />
						<label class="form-check-label"><?php echo $BL['be_article_cat'] ?> ID</label>
          </div>
          <div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="acat_lang_type" value="article"<?php is_checked('article', $acat_lang_type); ?> />
						<label class="form-check-label"><?php echo $BL['be_cnt_articles'] ?> ID &nbsp;<span class="flag-icon flag-icon-<?php echo $phpwcms['default_lang'] ?> mt-1" data-bs-toggle="tooltip" title="<?php echo get_language_name($phpwcms['default_lang']) . ' ('.$BL['be_admin_tmpl_default'].')' ?>"></span></label>
          </div>
          <div class="form-check form-check-inline py-2 py-sm-0 px-sm-4">
						<div class="input-group">
							<input name="acat_lang_id" type="text" id="acat_lang_id" class="form-control form-control-sm" value="<?php echo $acat_lang_id ? $acat_lang_id : ''; ?>" maxlength="10" onfocus="this.blur()" />
							<script>
							var $acatIdInput = $('#acat_lang_id');
							$acatIdInput.data('lang-type', $('input:radio[name="acat_lang_type"]:checked').val() || '');
							$('input:radio[name="acat_lang_type"]').on('change', function() {
								$('#acat_lang_browser').attr('data-src', 'articlebrowser.php?opt=3&idtype=' + this.value);
								// stash the ID of the previous type, restore the new type's ID if known
								var prevType = $acatIdInput.data('lang-type');
								if (prevType && prevType !== this.value && $acatIdInput.val() !== '') {
									$acatIdInput.data('stash-' + prevType, $acatIdInput.val());
								}
								$acatIdInput.val($acatIdInput.data('stash-' + this.value) || '');
								$acatIdInput.data('lang-type', this.value);
							});
							</script>
								<button class="modalButton btn btn-sm btn-blue sitemap-open" type="button" id="acat_lang_browser" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="articlebrowser.php?opt=3<?php echo $acat_lang_type === 'article' ? '&amp;idtype=article' : ($acat_lang_type === 'category' ? '&amp;idtype=category' : '') ?>" title="<?php echo $BL['be_func_open_articlebrowser'] ?>"><i class="fa-solid fa-sitemap fa-fw" aria-hidden="true"></i></button>
							
						</div>
          </div>
        </div>
      </div>
  </div>
  <?php endif; ?>

  <div class="form-group align-items-center row g-2">
      <label for="be_admin_page_pagetitle" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_page_pagetitle'] ?></label>
      <div class="col">
          <input name="acat_pagetitle" class="form-control form-control-sm" id="acat_pagetitle" value="<?php echo html($acat_pagetitle) ?>" maxlength="2000" type="text">
      </div>
  </div>

  <div class="form-group align-items-center row g-2">
      <label for="be_cnt_css_class" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_css_class'] ?></label>
      <div class="col">
          <input name="acat_class" class="form-control form-control-sm" id="acat_class" value="<?php echo html($acat_class) ?>" maxlength="255" type="text">
      </div>
  </div>

  <div class="form-group align-items-center row g-2">
      <label for="be_article_aredirect" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_article_aredirect'] ?></label>
      <div class="col">
          <input name="acat_redirect" class="form-control form-control-sm" id="acat_redirect" value="<?php echo html($acat_redirect) ?>" maxlength="255" type="text">
      </div>
  </div>

  <div class="form-group align-items-center row g-2">
      <label for="be_canonical" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_canonical'] ?></label>
      <div class="col">
          <input name="acat_canonical" class="form-control form-control-sm" id="acat_canonical" value="<?php echo html($acat_canonical) ?>" size="50" maxlength="2000" type="text">
      </div>
  </div>

  <div class="form-group row g-2">
      <label for="be_article_akeywords" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_article_akeywords'] ?></label>
      <div class="col">
          <textarea name="acat_keywords" class="form-control form-control-sm" id="acat_keywords"><?php echo html($acat_keywords) ?></textarea>
      </div>
  </div>

  <div class="form-group row g-2">
      <label for="be_admin_struct_info" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_info'] ?></label>
      <div class="col">
          <textarea name="acat_info" class="form-control form-control-sm" id="acat_info"><?php echo html($acat_info) ?></textarea>
      </div>
  </div>

  <hr />

  <div class="form-group align-items-center row g-2">
    <label for="be_admin_struct_template" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_admin_struct_template'] ?></label>
      <div class="col-sm-4">
      <select name="acat_template" id="acat_template" class="form-select form-select-sm">
        <?php
        $_temp_cat = '';

        // list available
        $sql = "SELECT * FROM ".DB_PREPEND."template WHERE template_trash=0 ORDER BY template_default DESC";
        $result = _dbQuery($sql);
        if(isset($result[0]['template_id'])) {
            foreach($result as $row) {
                echo "<option value=\"".$row["template_id"]."\"";
                if($row["template_id"] == $acat_template) {
                    echo " selected";
                    $_temp_cat = @unserialize($row['template_var'], ['allowed_classes' => false]);
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
      </select>
    </div>
  </div>

  <div class="form-group row g-2 align-items-center">
    <label for="be_settings" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_settings'] ?></label>
      <div class="col-sm-4">
      <select name="acat_overwrite" id="acat_overwrite" class="form-select form-select-sm">
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
      </select>
    </div>
    <div class="col small">
      <i><?php echo $BL['be_overwrite_default'] ?></i>
    </div>
    </div>

  <div class="form-group row g-2 align-items-center">
    <label class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_pagination'] ?></label>
    <div class="col-sm-auto">
      <div class="form-check form-switch">
        <input class="form-check-input" name="acat_paginate" type="checkbox" role="switch" id="acat_paginate" value="1" <?php if($acat_paginate == 1) echo 'checked="checked"'; ?> />
        <label for="acat_paginate" class="form-check-label"><?php echo $BL['be_article_pagination'] ?></label>
      </div>
    </div>
    <div class="form-group col-sm-auto">
      <label for="acat_topcount"><strong><?php echo $BL['be_admin_struct_topcount'] ?></strong></label>
      <input name="acat_topcount" type="number" id="acat_topcount" class="form-control form-control-sm" value="<?php echo intval($acat_topcount) ?>" size="10" maxlength="10" />
    </div>
    <div class="form-group col-sm-auto">
      <label for="acat_maxlist"><strong><?php echo $BL['be_article_per_page'] ?></strong></label>
      <input name="acat_maxlist" type="number" id="acat_maxlist" class="form-control form-control-sm" value="<?php echo empty($acat_maxlist) ? '' : intval($acat_maxlist); ?>" size="10" maxlength="10" />
    </div>
  <?php if($acat_struct_mode != 'INDEX'): ?>
    <div class="form-group col-sm-auto">
      <label for="acat_sort"><strong><?php echo $BL['be_cnt_sortvalue'] ?></strong></label>
      <input name="acat_sort" type="number" id="acat_sort" class="form-control form-control-sm" value="<?php echo $acat_sort; ?>" size="11" maxlength="11" />
    </div>
  <?php endif; ?>
  </div>

<hr />

  <fieldset class="form-group row g-2">
    <label for="be_admin_struct_template" class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_admin_struct_orderarticle'] ?></label>

			<div class="col-sm-auto">
				<div class="form-check">
					<label for="acat_order0" class="form-check-label">
						<input class="form-check-input" type="radio" name="acat_order" id="acat_order" value="0"<?php is_checked(0, intval($acat_order[0])) ?> />
						<?php echo  $BL['be_admin_struct_ordermanual'] ?>
					</label>
				</div>
				<div class="form-check">
					<label for="acat_order1" class="form-check-label">
						<input class="form-check-input" type="radio" name="acat_order" id="acat_order1" value="2"<?php is_checked(2, $acat_order[0]) ?> />
						<?php echo  $BL['be_admin_struct_orderdate'] ?>
					</label>
				</div>
				<div class="form-check">
					<label for="acat_order2" class="form-check-label">
						<input class="form-check-input" type="radio" name="acat_order" id="acat_order2" value="4"<?php is_checked(4, $acat_order[0]) ?> />
						<?php echo  $BL['be_admin_struct_orderchangedate'] ?>
					</label>
				</div>
				<div class="form-check">
					<label for="acat_order3" class="form-check-label">
						<input class="form-check-input" type="radio" name="acat_order" id="acat_order3" value="6"<?php is_checked(6, $acat_order[0]) ?> />
						<?php echo  $BL['be_admin_struct_orderstartdate'] ?>
					</label>
				</div>
				<div class="form-check">
					<label for="acat_order5" class="form-check-label">
						<input class="form-check-input" type="radio" name="acat_order" id="acat_order5" value="10"<?php is_checked(10, $acat_order[0]) ?> />
						<?php echo  $BL['be_admin_struct_orderkilldate'] ?>
					</label>
				</div>
				<div class="form-check">
					<label for="acat_order4" class="form-check-label">
						<input class="form-check-input" type="radio" name="acat_order" id="acat_order4" value="8"<?php is_checked(8, $acat_order[0]) ?> />
						<?php echo  $BL['be_article_atitle'] ?>
					</label>
				</div>
			</div>

			<div class="col-sm-auto">
				<div class="form-check">
					<label for="acat_ordersort0" class="form-check-label">
						<input class="form-check-input" type="radio" name="acat_ordersort" id="acat_ordersort" value="0"<?php is_checked(0, intval($acat_order[1])) ?> />
						<?php echo  $BL['be_admin_struct_orderasc'] ?>
					</label>
				</div>
				<div class="form-check">
					<label for="acat_ordersort1" class="form-check-label">
						<input class="form-check-input" type="radio" name="acat_ordersort" id="acat_ordersort1" value="1"<?php is_checked(1, $acat_order[1]) ?> />
						<?php echo  $BL['be_admin_struct_orderdesc'] ?>
					</label>
				</div>
			</div>

  </fieldset>

  <hr />

<?php   if(!empty($phpwcms['usergroup_support'])): ?>

  <!-- enym group selector -->
  <div class="form-group row g-2">
    <label for="acat_access" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_access']; ?> (<?php echo $BL['be_subnav_admin_groups']; ?>):</label>
    <div class="col"><?php
        // list all available groups and put into temp array
        $sql = "SELECT * FROM ".DB_PREPEND."usergroup WHERE group_active != 9 ORDER BY group_id DESC";
        $result = _dbQuery($sql);
        $_temp_group = array();
        if(isset($result[0]['group_name'])) {
            foreach($result as $row) {
                $_temp_group[$row['group_id']]['name'] = html(($row['group_syskey']) ? ($groupnames[$row["group_syskey"]] ?? $row["group_syskey"]) : $row["group_name"]);
                $_temp_group[$row['group_id']]['active'] = $row['group_active'];
            }
        }
    ?><select class="form-select" name="acat_access[]" id="acat_access" size="7"
              ondblclick="moveSelectedOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers,true);"
              multiple="multiple" class="form-control form-control-sm">
    <?php

        if(count($_temp_group)) {
            // list groups that have rights in here! acat_permit used for groups too
            foreach($_temp_group as $key => $value) {
                if(in_array($key, $acat_permit)) {
                    echo '<option value="'.$key.'"';
                    if(empty($_temp_group[$key]['active'])) {
                        echo ' class="text-muted"';
                    }
                    echo '>'.html($_temp_group[$key]['name'])."</option>";
                    unset($_temp_group[$key]);
                }
            }
        }

    ?></select>
    </div>

    <div class="col-sm-auto btn-col">
      <button class="btn btn-sm btn-blue" data-bs-toggle="tooltip" title="<?php echo $BL['be_admin_struct_adduser_all']?>" onclick="moveAllOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access);selectAllOptions(document.editsitestructure.acat_access);"><i class="fa-solid fa-angle-double-left fa-fw" aria-hidden="true"></i></button>
      <button class="btn btn-sm btn-blue" data-bs-toggle="tooltip" title="<?php echo $BL['be_admin_struct_adduser_this']?>" onclick="moveSelectedOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access,true);selectAllOptions(document.editsitestructure.acat_access);"><i class="fa-solid fa-angle-left fa-fw" aria-hidden="true"></i></button>
      <button class="btn btn-sm btn-blue" data-bs-toggle="tooltip" title="<?php echo $BL['be_admin_struct_remove_this']?>" onclick="moveSelectedOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers,true);"><i class="fa-solid fa-angle-right fa-fw" aria-hidden="true"></i></button>
      <button class="btn btn-sm btn-blue" data-bs-toggle="tooltip" title="<?php echo $BL['be_admin_struct_remove_all']?>" onclick="moveAllOptions(document.editsitestructure.acat_access,document.editsitestructure.acat_feusers);"><i class="fa-solid fa-angle-double-right fa-fw" aria-hidden="true"></i></button>
    </div>

    <div class="col">
      <select class="form-select" name="acat_feusers" size="7" id="acat_feusers"
              ondblclick="moveSelectedOptions(document.editsitestructure.acat_feusers,document.editsitestructure.acat_access,true);selectAllOptions(document.editsitestructure.acat_access);"
              class="form-control form-control-sm" multiple="multiple">
        <?php
            if(count($_temp_group)) {
                // list all available groups
                foreach($_temp_group as $key => $value) {
                    echo '<option value="'.$key.'"';
                    if(empty($_temp_group[$key]['active'])) {
                        echo ' class="text-muted"';
                    }
                    echo '>'.html($_temp_group[$key]['name'])."</option>\n";
                }
            }
        ?>
        </select>
    </div>
  </div>

  <!-- enym end new add group selector-->

<?php   endif; ?>

<hr />

  <!-- Content Part Selection -->
  <div class="form-group row g-2">
    <label for="be_structform_selected_cp" class="col-form-label col-sm-2 text-end"><?php echo $BL['be_structform_selected_cp'] ?></label>
    <div class="col">
      <select name="acat_cp[]" size="11" id="acat_cp" multiple class="form-select form-select-sm" ondblclick="moveSelectedOptions(document.editsitestructure.acat_cp,document.editsitestructure.acat_cpa,false);">
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
      </select>
    </div>

    <div class="col-sm-auto btn-col">
        <button class="btn btn-sm btn-blue" data-bs-toggle="tooltip" title="<?php echo $BL['be_admin_struct_adduser_all']?>" onclick="moveAllOptions(document.editsitestructure.acat_cpa,document.editsitestructure.acat_cp);"><i class="fa-solid fa-angle-double-left fa-fw" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-blue" data-bs-toggle="tooltip" title="<?php echo $BL['be_admin_struct_adduser_this']?>" onclick="moveSelectedOptions(document.editsitestructure.acat_cpa,document.editsitestructure.acat_cp,false);"><i class="fa-solid fa-angle-left fa-fw" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-blue" data-bs-toggle="tooltip" title="<?php echo $BL['be_admin_struct_remove_this']?>" onclick="moveSelectedOptions(document.editsitestructure.acat_cp,document.editsitestructure.acat_cpa,false);"><i class="fa-solid fa-angle-right fa-fw" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-blue" data-bs-toggle="tooltip" title="<?php echo $BL['be_admin_struct_remove_all']?>" onclick="moveAllOptions(document.editsitestructure.acat_cp,document.editsitestructure.acat_cpa);"><i class="fa-solid fa-angle-double-right fa-fw" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-blue" onclick="moveOptionUp(document.editsitestructure.acat_cp);"><i class="fa-solid fa-angle-up fa-fw" aria-hidden="true"></i></button>
        <button class="btn btn-sm btn-blue" onclick="moveOptionDown(document.editsitestructure.acat_cp);"><i class="fa-solid fa-angle-down fa-fw" aria-hidden="true"></i></button>
    </div>

    <div class="col">
      <select name="acat_cpa" size="11" multiple id="acat_cpa" class="form-select form-select-sm" ondblclick="moveSelectedOptions(document.editsitestructure.acat_cpa,document.editsitestructure.acat_cp,false);">
        <?php
        //Menue mit Content Typen erstellen
        foreach($wcs_content_type as $key => $value) {
            //echo getContentPartOptionTag($key, $value);
            echo '<option value="'.$key.'">'.$value."</option>\n";
        }
        foreach($BL['be_admin_optgroup_label'] as $key => $value) {
           echo '<option value="-'.$key.'">[optgroup] '.$value."</option>\n";
        }
        ?>
       </select>
    </div>
  </div>

   <div class="form-group align-items-center row g-2">
      <label for="be_structform_selected_cp" class="col-form-label col-sm-2 text-end"><?php echo $BL['be_admin_tmpl_default'] ?></label>
      <div class="col-sm-auto">
        <select name="acat_cpdefault" class="form-select form-select-sm">
          <?php
            foreach($wcs_content_type as $key => $value) {
              echo '<option value="'.$key.'"'.is_selected($acat_cpdefault, $key,1, 0).'>'.$value."</option>\n";
            }
          ?>
        </select>
    </div>
  </div>

  <hr />

  <div class="form-group align-items-center row g-2">
    <label class="col-form-label col-sm-2 text-end"><?php echo  $BL['be_cache'] ?></label>
    <div class="col-sm-auto me-sm-5">
      <div class="form-check form-switch">
        <input class="form-check-input" name="acat_cacheoff" type="checkbox" role="switch" id="acat_cacheoff" value="1"<?php if($acat_timeout === '0') echo "checked"; ?> />
        <label for="acat_cacheoff" class="form-check-label"> <?php echo $BL['be_off'] ?></label>
      </div>
    </div>

    <div class="col-sm-auto">
      <div class="input-group">
        <select name="acat_timeout" class="form-select form-select-sm" onchange="document.editsitestructure.acat_cacheoff.checked=false;">
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
        </select>

        
          <span class="input-group-text form-control-sm py-1"><?php echo $BL['be_cache_timeout'] ?></span>
        
    </div>
  </div>
  </div>

  <div class="form-group align-items-center row g-2">
    <label class="col-form-label col-sm-2 text-end"><?php echo  $BL['be_ctype_search'] ?></label>
    <div class="col">
      <div class="form-check form-switch">
        <input class="form-check-input" name="acat_nosearch" type="checkbox" role="switch" id="acat_nosearch" value="1" <?php if($acat_nosearch === '1') echo 'checked="checked"'; ?> />
        <?php if(empty($phpwcms['force301_2struct'])): ?><input type="hidden" name="acat_disable301" value="<?php echo $acat_disable301 ?>" /><?php endif; ?>
        <label for="acat_nosearch" class="form-check-label">
          <?php echo $BL['be_off']; ?>
          <?php if(!empty($phpwcms['force301_2struct'])): ?> <strong><?php echo $BL['be_acat_disable301'] ?></strong><?php endif; ?>
        </label>
      </div>
    </div>
  </div>

  <div class="form-group row g-2 align-items-center">
    <label for="be_admin_struct_status" class="col-form-label col-sm-2 text-end"><?php echo $BL['be_admin_struct_status'] ?></label>
    <div class="col">
      <div class="form-check form-switch form-check-inline">
				<input class="form-check-input" type="checkbox" role="switch" name="acat_hidden" id="acat_hidden" value="1"<?php is_checked($acat_hidden, 1); ?> />
				<label class="form-check-label" for="acat_hidden"><?php echo $BL['be_admin_struct_hide1'] ?></label>
      </div>
      <div class="form-check form-switch form-check-inline">
				<input class="form-check-input" type="checkbox" role="switch" name="acat_hiddenactive" id="acat_hiddenactive" value="1"<?php is_checked($acat_hiddenactive, 1); ?> />
				<label class="form-check-label" for="acat_hiddenactive"><?php echo $BL['be_admin_struct_acat_hiddenactive'] ?></label>
      </div>
      <div class="form-check form-switch form-check-inline">
				<input class="form-check-input" type="checkbox" role="switch" name="acat_regonly" id="acat_regonly" value="1"<?php is_checked($acat_regonly, 1); ?> />
				<label class="form-check-label" for="acat_regonly"><?php echo $BL['be_admin_struct_regonly'] ?></label>
      </div>
    </div>
  </div>

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

  <div class="form-group row g-2 align-items-center">
    <label for="be_breadcrumb" class="col-form-label col-sm-2 text-end"><?php echo $BL['be_breadcrumb'] ?></label>
    <div class="col">
      <div class="form-check form-switch form-check-inline">
				<input class="form-check-input" type="checkbox" role="switch" name="acat_breadcrumb_default" id="acat_breadcrumb_default" value="0"<?php is_checked($acat_breadcrumb_default_checked, 1); ?> />
				<label class="form-check-label" for="acat_breadcrumb_default"><?php echo $BL['be_admin_tmpl_default'] ?></label>
      </div>
      <div class="form-check form-switch form-check-inline">
				<input class="form-check-input" type="checkbox" role="switch" name="acat_breadcrumb_nothidden" id="acat_breadcrumb_nothidden" value="1"<?php is_checked($acat_breadcrumb_nothidden_checked, 1); ?> />
				<label class="form-check-label" for="acat_breadcrumb_nothidden"><?php echo $BL['be_breadcrumb_nothidden'] ?></label>
      </div>
      <div class="form-check form-switch form-check-inline">
				<input class="form-check-input" type="checkbox" role="switch" name="acat_breadcrumb_nolink" id="acat_breadcrumb_nolink" value="2"<?php is_checked($acat_breadcrumb_nolink_checked, 1); ?> />
				<label class="form-check-label" for="acat_breadcrumb_nolink"><?php echo $BL['be_breadcrumb_nolink'] ?></label>
      </div>
    </div>
  </div>

  <div class="form-group row g-2 align-items-center">
    <label for="be_ftptakeover_status" class="col-form-label col-sm-2 text-end"><?php echo  $BL['be_ftptakeover_status'] ?></label>
    <div class="col">
      <div class="form-check form-switch form-check-inline">
				<input class="form-check-input" type="checkbox" role="switch" name="acat_aktiv" id="acat_aktiv" value="1"<?php if($acat_aktiv == 1) echo 'checked="checked"'; ?> />
				<label class="form-check-label" for="acat_aktiv"><?php echo $BL['be_admin_struct_visible'] ?></label>
      </div>
      <div class="form-check form-switch form-check-inline">
				<input class="form-check-input" type="checkbox" role="switch" name="acat_ssl" id="acat_ssl" value="1"<?php is_checked(1, $acat_ssl); ?> />
				<label class="form-check-label" for="acat_ssl">SSL</label>
      </div>
      <div class="form-check form-switch form-check-inline">
				<input class="form-check-input" type="checkbox" role="switch" name="acat_nositemap" id="acat_nositemap" value="1"<?php is_checked(1, $acat_nositemap); ?> />
				<label class="form-check-label" for="acat_nositemap"><?php echo $BL['be_ctype_sitemap'] ?></label>
      </div>
      <div class="form-check form-switch form-check-inline">
				<input class="form-check-input" type="checkbox" role="switch" name="acat_archive" id="acat_archive" value="1"<?php is_checked(1, $acat_archive); ?> />
				<label class="form-check-label" for="acat_archive"><?php echo $BL['be_archive'] ?></label>
      </div>
      <div class="form-check form-switch form-check-inline">
				<input class="form-check-input" type="checkbox" role="switch" name="acat_opengraph" id="acat_opengraph" value="1"<?php if($acat_opengraph == 1) echo 'checked="checked"'; ?> />
				<label class="form-check-label" for="acat_opengraph"><?php echo $BL['be_opengraph_support'] ?></label>
      </div>
    </div>
  </div>
</div>
</div>

  <div class="mt-4">
    <div class="form-group align-items-center">
			<button name="submit" type="submit" class="btn btn-sm btn-blue" value="1"><i class="fa-solid fa-rotate"></i> <?php echo empty($acat_id) ? $BL['be_article_cnt_button2'] : $BL['be_article_cnt_button1'] ?></button>
			<button name="SubmitClose" type="submit" class="btn btn-sm btn-blue ms-1" value="1"><i class="fa-solid fa-check"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
			<a href="<?php echo $cancel_url; ?>" class="btn btn-sm btn-danger ms-3"><i class="fa-solid fa-times"></i> <?php echo $BL['be_newsletter_button_cancel'] ?></a>
    </div>
  </div>

</form>

<?php

if($acat_lang_mode):
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
