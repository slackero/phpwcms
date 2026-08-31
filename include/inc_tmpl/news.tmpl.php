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

// News
$news = new phpwcmsNews();

?>

<?php
  if(isset($_GET['cntid'])) {
    $news->edit();
  } else {
    $news->filter();
    $news->countAll();
    $news_categories = $news->getNewsCategories();
?>

<div class="row">
  <div class="col text-center text-sm-start">
    <h1><?php echo $BL['be_news'] ?></h1>
  </div>
  <div class="col text-center text-sm-end mb-3">
    <a class="btn btn-sm btn-blue" href="<?php echo $news->base_url ?>&amp;cntid=0&amp;action=edit" title="<?php echo $BL['be_news_create'] ?>"><i class="fa-solid fa-plus"></i> <?php echo $BL['be_news_create'] ?></a>
  </div>
</div>

<div class="card">
    <div class="card-header"><h2><?php echo $BL['be_news_list'] ?></h2></div>
    <div class="card-body">

    <form action="<?php echo $news->base_url ?>" method="post" id="paginate">
    <div class="form-group mb-2">
      <div class="row g-2 align-items-center">
        <input type="hidden" name="showactive" id="showactive_input" value="<?php echo ($news->filter_status == 0 || $news->filter_status == 1) ? 1 : 0 ?>" />
        <input type="hidden" name="showinactive" id="showinactive_input" value="<?php echo ($news->filter_status == 0 || $news->filter_status == 2) ? 1 : 0 ?>" />
        <div class="col-12 col-sm-auto">
          <div class="btn-group btn-group-sm" role="group" aria-label="news-filter">
            <button type="button" class="btn btn-sm <?php echo ($news->filter_status == 0 || $news->filter_status == 1) ? 'btn-success' : 'btn-outline-secondary' ?>" onclick="document.getElementById('showactive_input').value = (document.getElementById('showactive_input').value == '1' ? '0' : '1'); this.form.submit();" title="Active">
              <i class="fas fa-eye"></i>
            </button>
            <button type="button" class="btn btn-sm <?php echo ($news->filter_status == 0 || $news->filter_status == 2) ? 'btn-warning' : 'btn-outline-secondary' ?>" onclick="document.getElementById('showinactive_input').value = (document.getElementById('showinactive_input').value == '1' ? '0' : '1'); this.form.submit();" title="Inactive">
              <i class="fas fa-eye-slash"></i>
            </button>
          </div>
        </div>
      <div class="col-sm-auto">
        <select name="sort" class="form-select form-select-sm" onchange="this.form.submit();" >
            <option value="prio_asc"<?php is_selected('prio_asc', $news->filter_sort) ?>><?php echo $BL['be_priorize'], ', ', $BL['be_admin_struct_orderasc'] ?></option>
            <option value="prio_desc"<?php is_selected('prio_desc', $news->filter_sort) ?>><?php echo $BL['be_priorize'], ', ', $BL['be_admin_struct_orderdesc'] ?></option>
            <option value="name_asc"<?php is_selected('name_asc', $news->filter_sort) ?>><?php echo $BL['be_title'], ', ', $BL['be_admin_struct_orderasc'] ?></option>
            <option value="name_desc"<?php is_selected('name_desc', $news->filter_sort) ?>><?php echo $BL['be_title'], ', ', $BL['be_admin_struct_orderdesc'] ?></option>
            <option value="start_asc"<?php is_selected('start_asc', $news->filter_sort) ?>><?php echo $BL['be_article_cnt_start'], ', ', $BL['be_admin_struct_orderasc'] ?></option>
            <option value="start_desc"<?php is_selected('start_desc', $news->filter_sort) ?>><?php echo $BL['be_article_cnt_start'], ', ', $BL['be_admin_struct_orderdesc'] ?></option>
            <option value="end_asc"<?php is_selected('end_asc', $news->filter_sort) ?>><?php echo $BL['be_article_cnt_end'], ', ', $BL['be_admin_struct_orderasc'] ?></option>
            <option value="end_desc"<?php is_selected('end_desc', $news->filter_sort) ?>><?php echo $BL['be_article_cnt_end'], ', ', $BL['be_admin_struct_orderdesc'] ?></option>
            <option value="sort_asc"<?php is_selected('sort_asc', $news->filter_sort) ?>><?php echo $BL['be_sort_date'], ', ', $BL['be_admin_struct_orderasc'] ?></option>
            <option value="sort_desc"<?php is_selected('sort_desc', $news->filter_sort) ?>><?php echo $BL['be_sort_date'], ', ', $BL['be_admin_struct_orderdesc'] ?></option>
        </select>
      </div>

      <div class="col-sm-auto">
            <select name="keyword" data-bs-toggle="tooltip" title="<?php echo $BL['be_tooltip_filter_for'] ?> <?php echo $BL['be_tags'] ?>" class="form-select form-control form-control-sm" onchange="this.form.submit();">
                <option value=""<?php is_selected('', $news->filter_keyword) ?>><?php echo $BL['be_ftptakeover_all'] ?></option>
                    <?php if(count($news_categories)):
                        foreach($news_categories as $item):
                    ?>
                <option value="<?php echo html($item) ?>"<?php is_selected($item, $news->filter_keyword) ?>><?php echo html(ucfirst($item)) ?></option>
                    <?php
                        endforeach;
                        endif;
                    ?>
            </select>
      </div>

        <div class="col-sm-auto">
            <div class="input-group">
                <input name="filter" id="filter" size="15" data-bs-toggle="tooltip" title="<?php echo $BL['be_tooltip_filter_for'] ?> <?php echo $BL['be_text_full'] ?>" class="form-control form-control-sm" value="<?php echo html($news->filter) ?>" type="search">
                
                    <button class="btn btn-sm btn-secondary" name="gofilter" type="submit"><i class="fa-solid fa-filter me-1"></i> <?php echo $BL['be_filter'] ?></button>
                
            </div>
        </div>

        <div class="col-sm-auto text-sm-end">
            <?php echo getItemsPerPageMenu(); ?>
            <script>
                $(function(){
                    $('#news-paginate').on('change', function() {
                        window.location = <?php echo json_encode($news->base_url_decoded . '&showipp='); ?> + $(this).val();
                    });
                });
            </script>
        </div>

      </div>
  </div>
</form>

<?php
    echo $news->listBackend();
    $phpwcms['be_parse_lang_process'] = true;
?>
</div>
</div>
<div class="form-group text-center text-sm-end mt-4">
  <a class="btn btn-sm btn-blue" href="<?php echo $news->base_url ?>&amp;cntid=0&amp;action=edit" title="<?php echo $BL['be_news_create'] ?>"><i class="fa-solid fa-plus"></i> <?php echo $BL['be_news_create'] ?></a>
</div>

<?php
  }
  // Begin news form
  if(count($news->data)) {
    // some JavaScripts wee need
    initJsCalendar();
    initJsAutocompleter();
    initAceEditor();
?>
<!-- NEWSDETAIL START -->
<script>

function setImgIdName(file_id, file_name) {
    if(typeof file_id === 'undefined' || file_id === null) {
        file_id = 0;
    }
    if(typeof file_name === 'undefined' || file_name === null) {
        file_name = '';
    }
  $('#cnt_image_id').val(file_id);
  $('#cnt_image_name').val(file_name);

  showImage();
}

function showImage() {
  var id  = parseInt($('#cnt_image_id').val(), 10);
  var img = $('#cnt_image');
  if(id) {
    img.html('<img src="' + <?php echo json_encode(PHPWCMS_URL.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms['img_list_width'].'x'.$phpwcms['img_list_height'].'/'); ?> + id + '" alt="" />');
    img.show();
  } else {
    img.hide();
  }
}

function addFile(file_id, file_name) {
  var obj = document.getElementById('cfile_list');
  if(obj!=null && obj.options!=null) {
    var newOpt = new Option(file_name, file_id);
    obj.options.length++;
    obj.options[obj.length-1].text    = newOpt.text;
    obj.options[obj.length-1].value   = newOpt.value;
    obj.options[obj.length-1].selected  = false;
    if(obj.options.length > 5) {
      obj.size = obj.options.length;
      $('#cnt_file_caption').attr('rows', obj.size+1);
    }
  }
}

function emptyNews() {
  document.location.href='<?php echo $news->base_url_decoded ?>&cntid=0&action=edit';
  return false;
}

var initialNewsFormData = '';
function closeForm() {
  if ($('#newsform').serialize() !== initialNewsFormData) {
    bsConfirmWarning('<?php echo js_singlequote($BL["be_dialog_warn_nosave"]); ?>', function() {
      document.location.href='<?php echo $news->base_url_decoded ?>';
    }, '<?php echo js_singlequote($BL["be_yes"]); ?>', '<?php echo js_singlequote($BL["be_no"]); ?>');
  } else {
    document.location.href='<?php echo $news->base_url_decoded ?>';
  }
  return false;
}

$(function(){

  /* Autocompleter for categories/tags */
  initTomSelectTagAutosuggest('#news_keyword_autosuggest', '#cnt_category', 'newstags');

  $('#newsform').submit(function(event){
    $('#cfile_list option').prop('selected', true);
  });


  var cnt_title = $('#cnt_title'),
      change_name_value = '-',
      change_alias_value  = '-';

  // set name field
  $('#cnt_name_click').on('click', function(){
    var cnt_name = cnt_title.val().trim();
    if(cnt_name === '') {
      cnt_title.val( $('#cnt_name').val().trim() );
    } else {
      $('#cnt_name').val(cnt_name);
    }
  });

  $('#cnt_alias_click').on('click', function(){
    var cnt_alias = $('#cnt_name').val().trim();
    if(cnt_alias === '') {
      cnt_alias = cnt_title.val().trim();
      $('#cnt_name').val(cnt_alias);
    } else {
      $('#cnt_alias').val( create_alias(cnt_alias) );
    }
  });

  cnt_title.on({
    focus: function(){
      change_name_value   = $('#cnt_name').val().trim();
      change_alias_value  = $('#cnt_alias').val().trim();
    },
    keyup: function() {
      if(change_name_value === ''){
        $('#cnt_name').val(cnt_title.val());
      }
      if(change_alias_value === '') {
        $('#cnt_alias').val(create_alias( $('#cnt_name').val() ));
      }
    }
  });

  $('#cnt_image_lightbox').on('click', function(){
    if($(this).is(':checked')) {
      $('#cnt_image_zoom').attr('checked', true);
    }
  });

  initialNewsFormData = $('#newsform').serialize();
});

</script>

<form action="<?php echo $news->formAction() ?>" method="post" class="free" id="newsform" name="newsform" required>
  <div class="row">
    <div class="col-sm-auto text-center text-sm-start">
      <h1><?php echo $BL['be_news'] ?></h1>
    </div>
    <div class="col-sm text-center text-sm-end mb-3">
      <button name="new" type="button" class="btn btn-sm btn-blue me-sm-3 mb-1 mb-sm-0" onclick="emptyNews();"><i class="fa-solid fa-plus"></i> <?php echo ($BL['be_news_create']) ?></button>
      <?php if($news->data['cnt_id']) { ?>
      <button name="submit" type="submit" class="btn btn-sm btn-blue mb-1 mb-sm-0" value="1"><i class="fa-solid fa-rotate"></i> <?php echo $BL['be_article_cnt_button1'] ?></button>
      <button name="save" type="submit" class="btn btn-sm btn-blue ms-1 mb-1 mb-sm-0" value="<?php echo $BL['be_article_cnt_button3'] ?>"><i class="fa-solid fa-check"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
    <?php } else { ?>
      <button name="submit" type="submit" class="btn btn-sm btn-blue mb-1 mb-sm-0" value="1"><i class="fa-solid fa-rotate"></i> <?php echo $BL['be_admin_fcat_button2'] ?></button>
      <button name="save" type="submit" class="btn btn-sm btn-blue ms-1 mb-1 mb-sm-0" value="<?php echo $BL['be_article_cnt_button3'] ?>"><i class="fa-solid fa-check"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
     <?php } ?>
      <button name="close" type="button" class="btn btn-sm btn-danger ms-sm-3 mb-1 mb-sm-0" onclick="closeForm();"><i class="fa-solid fa-times"></i> <?php echo $BL['be_admin_struct_close'] ?></button>
    </div>
  </div>


<div class="card">
<div class="card-header"><h2><?php
if($news->data['cnt_id']) {
  echo $BL['be_news_edit'];
} else if (isset($_GET["button"]) && $_GET["button"] === 'copy') {
  echo $BL['be_news_copy'];
} else {
  echo $BL['be_news_add'];
}
?></h2></div>

<div class="card-body">
  <div class="form-group align-items-center row g-2">
    <label for="cnt_title" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_article_cnt_ctitle'] ?></label>
    <div class="col">
      <input name="cnt_title" class="form-control form-control-sm" id="cnt_title" value="<?php echo html($news->data['cnt_title']) ?>" maxlength="250" type="text" required >
    </div>
  </div>

  <div class="form-group align-items-center row g-2">
    <label for="cnt_subtitle" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_article_asubtitle'] ?></label>
    <div class="col">
      <input name="cnt_subtitle" class="form-control form-control-sm" id="cnt_subtitle" value="<?php echo html($news->data['cnt_subtitle']) ?>" maxlength="250" type="text">
    </div>
  </div>

  <hr />

  <div class="form-group align-items-center row g-2">
    <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_media_format'] ?></label>
    <div class="col-sm-10">
      <div class="btn-group btn-group-xs" role="group" aria-label="cnt_textformat">
        <input class="btn-check" type="radio" id="text_format0" name="cnt_textformat" value="plain" autocomplete="off"<?php is_checked('plain', $news->data['cnt_textformat']); ?> />
        <label class="btn btn-outline-blue" for="text_format0"><?php echo $BL['be_ctype_plaintext'] ?></label>

        <input class="btn-check" type="radio" id="text_format1" name="cnt_textformat" value="markdown" autocomplete="off"<?php is_checked('markdown', $news->data['cnt_textformat']); ?> />
        <label class="btn btn-outline-blue" for="text_format1">MarkDown</label>

        <input class="btn-check" type="radio" id="text_format2" name="cnt_textformat" value="textile" autocomplete="off"<?php is_checked('textile', $news->data['cnt_textformat']); ?> />
        <label class="btn btn-outline-blue" for="text_format2">Textile</label>

        <input class="btn-check" type="radio" id="text_format3" name="cnt_textformat" value="br" autocomplete="off"<?php is_checked('br', $news->data['cnt_textformat']); ?> />
        <label class="btn btn-outline-blue" for="text_format3">BR</label>
      </div>
    </div>
  </div>

  <div class="form-group row g-2">
      <label for="cnt_teasertext" class="col-form-label col-sm-2 text-end"><?php echo $BL['be_teasertext'] ?></label>
      <div class="col">
      <textarea name="cnt_teasertext" id="cnt_teasertext" rows="5" class="form-control form-control-sm field-sizing-content field-sizing-content-5 code-editor" data-mode="<?php echo ($news->data['cnt_textformat'] === 'markdown') ? 'markdown' : (($news->data['cnt_textformat'] === 'textile') ? 'textile' : 'text'); ?>"><?php echo html($news->data['cnt_teasertext']); ?></textarea>
    </div>
  </div>

  <hr />

    <div class="form-group row g-2 align-items-center">
      <label class="col-sm-2 col-form-label text-end"><?php echo $BL['be_article_cnt_start'] ?></label>
      <div class="col-sm-10">
        <div class="d-flex flex-wrap flex-lg-nowrap align-items-center gap-2">
          <div class="input-group input-group-sm datetime-picker-group">
            <span class="input-group-text"><?php echo $BL['be_msg_from'] ?></span>
            <input type="text" class="form-control form-control-sm datetimepicker-input" name="calendar_start_date" id="start_date" value="<?php echo $news->data['cnt_date_start']; ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" autocomplete="off" />
            <span class="btn-blue input-group-text" style="cursor:pointer;" onclick="document.getElementById('start_date')._flatpickr&&document.getElementById('start_date')._flatpickr.open();"><i class="far fa-calendar-alt fa-fw"></i></span>
            <input type="text" class="form-control form-control-sm datetimepicker-input" name="calendar_start_time" id="start_time" value="<?php echo $news->data['cnt_time_start']; ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" autocomplete="off" />
            <span class="btn-blue input-group-text" style="cursor:pointer;" onclick="document.getElementById('start_time')._flatpickr&&document.getElementById('start_time')._flatpickr.open();"><i class="far fa-clock"></i></span>
          </div>
          <div class="input-group input-group-sm datetime-picker-group">
            <span class="input-group-text"><?php echo $BL['be_article_aend'] ?></span>
            <input type="text" class="form-control form-control-sm datetimepicker-input" name="calendar_end_date" id="end_date" value="<?php echo $news->data['cnt_date_end']; ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" autocomplete="off" />
            <span class="btn-blue input-group-text" style="cursor:pointer;" onclick="document.getElementById('end_date')._flatpickr&&document.getElementById('end_date')._flatpickr.open();"><i class="far fa-calendar-alt fa-fw"></i></span>
            <input type="text" class="form-control form-control-sm datetimepicker-input" name="calendar_end_time" id="end_time" value="<?php echo $news->data['cnt_time_end']; ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" autocomplete="off" />
            <span class="btn-blue input-group-text" style="cursor:pointer;" onclick="document.getElementById('end_time')._flatpickr&&document.getElementById('end_time')._flatpickr.open();"><i class="far fa-clock"></i></span>
          </div>
        </div>
      </div>
    </div>
    <script>
        $(function () {
            var fpStart = flatpickr('#start_date', {
                dateFormat: 'd.m.Y',
                allowInput: true
            });
            var fpStartTime = flatpickr('#start_time', {
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: true,
                allowInput: true
            });
            var fpEnd = flatpickr('#end_date', {
                dateFormat: 'd.m.Y',
                allowInput: true
            });
            var fpEndTime = flatpickr('#end_time', {
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: true,
                allowInput: true
            });
        });
    </script>
    <div class="form-group align-items-center row g-2">
      <label for="sort_date" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_sorting'] ?></label>
      <div class="col-sm-auto">
        <div class="input-group input-group-sm datetime-picker-group">
          <input type="text" class="form-control form-control-sm datetimepicker-input" name="calendar_sort_date" id="sort_date" value="<?php echo $news->data['cnt_date_sort']; ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" autocomplete="off" />
          <span class="btn-blue input-group-text" style="cursor:pointer;" onclick="document.getElementById('sort_date')._flatpickr&&document.getElementById('sort_date')._flatpickr.open();"><i class="far fa-calendar-alt fa-fw"></i></span>
          <input type="text" class="form-control form-control-sm datetimepicker-input" name="calendar_sort_time" id="sort_time" value="<?php echo $news->data['cnt_time_sort']; ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" autocomplete="off" />
          <span class="btn-blue input-group-text" style="cursor:pointer;" onclick="document.getElementById('sort_time')._flatpickr&&document.getElementById('sort_time')._flatpickr.open();"><i class="far fa-clock"></i></span>
        </div>
      </div>
    </div>
    <script>
      $(function () {
          flatpickr('#sort_date', { dateFormat: 'd.m.Y', allowInput: true });
          flatpickr('#sort_time', { enableTime: true, noCalendar: true, dateFormat: 'H:i', time_24hr: true, allowInput: true });
      });
    </script>

  <hr />

  <div class="form-group align-items-center row g-2">
    <label for="cnt_name" class="col-sm-2 col-form-label text-end"><a id="cnt_name_click" class="underline text-blue"><?php echo $BL['be_title'] ?></a></label>
    <div class="col">
      <input name="cnt_name" class="form-control form-control-sm" id="cnt_name" value="<?php echo html($news->data['cnt_name']) ?>" placeholder="<?php echo $BL['be_title'] ?>" maxlength="200" type="text" required>
    </div>
  </div>

  <div class="form-group align-items-center row g-2">
    <label for="cnt_alias" class="col-sm-2 col-form-label text-end"><a id="cnt_alias_click" class="underline text-blue"><?php echo $BL['be_alias'] ?></a></label>
    <div class="col">
      <input name="cnt_alias" class="form-control form-control-sm" id="cnt_alias" value="<?php echo html($news->data['cnt_alias']) ?>" placeholder="<?php echo $BL['be_alias'] ?>" maxlength="200" type="text" required>
    </div>
  </div>

  <div class="form-group align-items-center row g-2">
    <span class="col-sm-2 col-form-label text-end"><?php echo $BL['be_tags'] ?> <i class="fas fa-info-circle text-blue" data-bs-toggle="tooltip" title="<?php echo $BL['be_input_text_tab'] ?>"></i></span>
    <div class="col">
      <input type="text" id="news_keyword_autosuggest" class="form-control form-control-sm" aria-label="<?php echo html_specialchars($BL['be_tags']) ?>" /><input type="hidden" name="cnt_category" id="cnt_category" value="<?php echo html($news->data['cnt_category']) ?>" />
    </div>
  </div>

  <?php if(count($phpwcms['allowed_lang']) > 1):  ?>
    <div class="form-group row g-2 align-items-center">
        <label for="cnt_lang" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_profile_label_lang'] ?></label>
        <div class="col-sm-4">
           <select name="cnt_lang" id="cnt_lang" class="form-select form-select-sm">
            <?php
              echo '  <option value=""';
              is_selected('', $news->data['cnt_lang']);
              echo '>'. $BL['be_admin_tmpl_default'].'</option>';
              foreach($phpwcms['allowed_lang'] as $key => $lang):
                $lang = strtolower($lang);
                echo '  <option value="'.$lang.'"';
                is_selected($lang, $news->data['cnt_lang']);
                echo '>'. get_language_name($lang) .'</option>';
              endforeach;
            ?>
          </select>
        </div>
    </div>
  <?php else: ?>
    <input type="hidden" name="cnt_lang" value="<?php echo html($news->data['cnt_lang']) ?>" />
  <?php endif;  ?>

  <div class="form-group align-items-center row g-2">
      <label for="cnt_prio" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_priorize'] ?></label>
      <div class="col-sm-4">
        <select name="cnt_prio" id="cnt_prio"  class="form-select form-select-sm" data-bs-toggle="tooltip" title="<?php echo $BL['be_priorize'] ?>">
          <?php
              for($x=30; $x>=-30; $x--) {
              echo '  <option value="'.$x.'"';
              is_selected($x, $news->data['cnt_prio']);
              echo '>'.( $x==0 ? $BL['be_cnt_default'] : $x ).'</option>'.LF;
              }
            ?>
      </select>
    </div>
  </div>

  <div class="form-group row g-2 mt-3">
      <div class="col">
<?php

    $wysiwyg_editor = array(
      'value'   => $news->data['cnt_text'],
      'field'   => 'cnt_text',
      'height'  => '250px',
      'rows'    => '10',
      'editor'  => $_SESSION["WYSIWYG_EDITOR"],
      'lang'    => 'en'
    );

    include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

  ?>
    </div>
  </div>
  <hr />

    <div class="form-group align-items-center row g-2">
        <label for="cnt_image_name" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_image'] ?></label>
        <div class="col-sm-4">
            <div class="input-group">
                <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="filebrowser.php?opt=7&amp;target=summary" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>"><i class="fa-solid fa-folder-open fa-fw" aria-hidden="true"></i></button>
                <input name="cnt_image_name" type="text" id="cnt_image_name" class="form-control form-control-sm" value="<?php echo html($news->data['cnt_image']['name']) ?>" maxlength="250" onfocus="this.blur()" />
                <a href="#" class="btn btn-sm btn-danger trash" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="setImgIdName();return false;"><i class="fa-solid fa-trash-alt fa-fw" aria-hidden="true"></i></a>
            </div>
            <input name="cnt_image_id" id="cnt_image_id" type="hidden" value="<?php echo $news->data['cnt_image']['id'] ?>" />
        </div>
    </div>

  <div class="form-group align-items-center row g-2">
    <span class="col-sm-2"></span>
    <div class="col-sm-10">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="cnt_image_zoom" name="cnt_image_zoom" value="1"<?php is_checked(1, $news->data['cnt_image']['zoom']); ?> />
        <label class="form-check-label" for="cnt_image_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="cnt_image_lightbox" name="cnt_image_lightbox" value="1"<?php is_checked(1, $news->data['cnt_image']['lightbox']); ?> />
        <label class="form-check-label" for="cnt_image_lightbox"><?php echo $BL['be_cnt_lightbox'] ?></label>
      </div>
    </div>
  </div>

  <div class="form-group align-items-center row g-2">
    <label class="col-sm-2"></label>
    <div id="cnt_image" class="col-sm-auto"></div>
  </div>

  <div class="form-group row g-2">
      <label for="cnt_image_caption" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_caption'] ?></label>
      <div class="col">
        <textarea name="cnt_image_caption" id="cnt_image_caption" class="form-control form-control-sm" rows="3"><?php echo html($news->data['cnt_image']['caption']) ?></textarea>
           <div class="pt-2">
            <?php echo $BL['be_cnt_caption']; ?>
            |
            <?php echo $BL['be_caption_alt']; ?>
            |
            <?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
            |
            <?php echo $BL['be_caption_title']; ?>
            |
            <?php echo $BL['be_copyright']; ?>
          </div>
      </div>
  </div>

  <div class="form-group align-items-center row g-2">
        <label for="cnt_image_link" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_profile_label_website'] ?></label>
        <div class="col">
            <input name="cnt_image_link" class="form-control form-control-sm" id="cnt_image_link" value="<?php echo html($news->data['cnt_image']['link']) ?>" maxlength="500" type="text">
    </div>
  </div>

  <hr />

<?php
  $news->files = $news->getFiles();
  $news->fileCount = count($news->files);
  $news->fileRows = $news->fileCount ? $news->fileCount+1 : 6;
?>
  <div class="form-group row g-2" >
      <label for="cfile_list" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_files'] ?></label>
      <div class="col">
        <select name="cnt_files[]" size="<?php echo $news->fileRows ?>" multiple="multiple" id="cfile_list" class="form-select form-control form-control-sm h-100">
      <?php if($news->fileCount) {
            foreach($news->files as $f_id => $item) {
              echo '<option value="' . $item['f_id'] . '">' . (empty($item['f_name']) ? '-- ' . $BL['be_msg_del'] . ' --' : html($item['f_name'])) . '</option>' . LF;
            }
          }
      ?>
        </select>
      </div>
      <div class="col-sm-auto">
        <button type="button" class="modalButton btn btn-sm btn-blue mb-1" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="filebrowser.php?opt=9&amp;target=summary" ><i class="fa-solid fa-folder-open fa-fw" aria-hidden="true"></i></button><br />
        <button type="button" class="btn btn-sm btn-secondary mb-1" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.getElementById('cfile_list'));return false;"><i class="fa-solid fa-angle-up fa-fw" aria-hidden="true"></i></button><br />
        <button type="button" class="btn btn-sm btn-secondary mb-1" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.getElementById('cfile_list'));return false;"><i class="fa-solid fa-angle-down fa-fw" aria-hidden="true"></i></button><br />
        <button type="button" class="btn btn-sm btn-danger mb-1" onclick="removeSelectedOptions(document.getElementById('cfile_list'));return false;" data-bs-toggle="tooltip" title="<?php echo $BL['be_cnt_delfile'] ?>"><i class="far fa-trash-alt fa-fw" aria-hidden="true"></i></button>
      </div>
  </div>

  <div class="form-group row g-2">
      <label for="cnt_file_caption" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_cnt_description'] ?></label>
      <div class="col">
        <textarea name="cnt_file_caption" id="cnt_file_caption" class="form-control form-control-sm" rows="<?php echo $news->fileRows ?>"><?php echo html($news->data['cnt_files']['caption']) ?></textarea>
           <div class="pt-2">
          <?php echo $BL['be_caption_descr.']; ?>
          |
          <?php echo $BL['be_fprivedit_filename']; ?>
          |
          <?php echo $BL['be_caption_file_title']; ?>
          |
          <?php echo $BL['be_cnt_target']; ?>
          |
          <?php echo $BL['be_caption_file_imagesize']; ?>
          |
          <?php echo $BL['be_copyright']; ?>&nbsp;&crarr;&nbsp;&hellip;
          </div>
      </div>
  </div>

  <div class="form-group align-items-center row g-2">
    <span class="col-sm-2"></span>
    <div class="col-sm-10">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="cnt_file_gallery" name="cnt_file_gallery" value="1"<?php is_checked(1, $news->data['cnt_files']['gallery']); ?> />
        <label class="form-check-label" for="cnt_file_gallery"><?php echo $BL['be_imagefiles_as_gallery'] ?></label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="cnt_file_gallery_download" name="cnt_file_gallery_download" value="1"<?php is_checked(1, $news->data['cnt_files']['gallery_download']); ?> />
        <label class="form-check-label" for="cnt_file_gallery_download"><?php echo $BL['be_gallerydownload'] ?></label>
      </div>
    </div>
  </div>

  <div class="form-group row g-2">
    <label for="cnt_link" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_read_more_link'] ?></label>
    <div class="col-sm-4">
        <div class="input-group">
            <button class="modalButton btn btn-sm btn-blue sitemap-open" type="button" data-bs-toggle="modal" data-bs-target="#browserModal" data-src="articlebrowser.php?opt=1" title="<?php echo $BL['be_cnt_openarticlebrowser'] ?>"><i class="fa-solid fa-sitemap fa-fw" aria-hidden="true"></i></button>
            <input type="text" name="cnt_link" id="cnt_link" value="<?php echo html_entities($news->data['cnt_link']) ?>" class="form-control form-control-sm" maxlength="250" data-bs-toggle="tooltip" title="<?php echo $BL['be_read_more_link'] ?>" />
        </div><?php
          if (intval($news->data['cnt_link'])> 0) {
            $adata = get_article_data($news->data['cnt_link']);
            echo '<small><label class="mt-1">' . $BL['be_cnt_target'] .':&nbsp;</label>';
            if (is_array($adata)) {
              echo '<a href="phpwcms.php?&do=articles&p=2&s=1&id=' . $adata['article_id'] . '" target="_blank" data-bs-toggle="tooltip" title="' . $adata['article_title'] . '">' . $adata['article_alias'] . $phpwcms['rewrite_ext'] .'</a>';
            } else {
              echo $BL['be_admin_usr_err'];
            }
            echo '</small>';
          }
          ?>
    </div>
  </div>

  <div class="form-group align-items-center row g-2">
      <label for="cnt_linktext" class="col-sm-2 col-form-label text-end">URL <?php echo $BL['be_admin_page_text'] ?></label>
        <div class="col">
            <input name="cnt_linktext" class="form-control form-control-sm" id="cnt_linktext" value="<?php echo html_entities($news->data['cnt_linktext']) ?>" maxlength="250" type="text" data-bs-toggle="tooltip" title="URL <?php echo $BL['be_admin_page_text'] ?>">
        </div>
  </div>

  <div class="form-group align-items-center row g-2">
      <label for="cnt_editor" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_article_username'] ?></label>
        <div class="col">
            <input name="cnt_editor" class="form-control form-control-sm" id="cnt_editor" value="<?php echo html($news->data['cnt_editor']) ?>" maxlength="250" type="text" data-bs-toggle="tooltip" title="<?php echo $BL['be_article_username'] ?>">
        </div>
       <label for="cnt_place" class="col-sm-2 col-form-label text-end"><?php echo $BL['be_place'] ?></label>
        <div class="col">
            <input name="cnt_place" class="form-control form-control-sm" id="cnt_place" value="<?php echo html($news->data['cnt_place']) ?>" maxlength="250" type="text" data-bs-toggle="tooltip" title="<?php echo $BL['be_place'] ?>">
        </div>
  </div>

 <hr />

    <div class="form-group row g-2 bg-grey py-2 mb-0">
    <label class="col-sm-2 col-form-label text-end pt-0"><?php echo $BL['be_ftptakeover_status'] ?></label>
    <div class="col-sm-10">
      <div class="form-check">
                <input class="form-check-input" name="cnt_readmore" type="checkbox" id="cnt_readmore" value="1"<?php is_checked(1, $news->data['cnt_readmore']); ?> />
                <label class="form-check-label" for="cnt_readmore"><?php echo $BL['be_article_morelink'] ?></label>
      </div>
      <div class="form-check">
                <input class="form-check-input"  name="cnt_searchoff" type="checkbox" id="cnt_searchoff" value="1"<?php is_checked(1, $news->data['cnt_searchoff']); ?> />
                <label class="form-check-label" for="cnt_searchoff"><?php echo $BL['be_no_search'] ?></label>
      </div>
      <div class="form-check">
                <input class="form-check-input"  name="cnt_opengraph" type="checkbox" id="cnt_opengraph" value="1"<?php is_checked(1, $news->data['cnt_opengraph']); ?> />
                <label class="form-check-label" for="cnt_opengraph"><?php echo $BL['be_opengraph_support'] ?></label>
      </div>
      <div class="form-check">
                <input class="form-check-input" name="cnt_archive_status" type="checkbox" id="cnt_archive_status" value="1"<?php is_checked(1, $news->data['cnt_archive_status']); ?> />
                <label class="form-check-label" for="cnt_archive_status"><?php echo $BL['be_show_archived'] ?></label>
      </div>
      <div class="form-check">
                <input class="form-check-input" name="cnt_duplicate" type="checkbox" id="cnt_duplicate" value="1"<?php is_checked(1, $news->data['cnt_duplicate']); ?> />
                <label class="form-check-label" for="cnt_duplicate"><?php echo $BL['be_save_copy'] ?></label>
      </div>
      <div class="form-check">
                <input class="form-check-input" name="cnt_status" type="checkbox" id="cnt_status" value="1"<?php is_checked(1, $news->data['cnt_status']); ?> />
                <label class="form-check-label" for="cnt_status"><strong><?php echo $BL['be_published'] ?></strong></label>
      </div>
    </div>
  </div>

</div> <!-- END CARD BODY -->
</div> <!-- END CARD -->

  <div class="row mt-4 text-end">
    <div class="col">
      <button name="new" type="button" class="btn btn-sm btn-blue me-sm-3 mb-1 mb-sm-0" onclick="emptyNews();"><i class="fa-solid fa-plus"></i> <?php echo ($BL['be_news_create']) ?></button>
      <?php if($news->data['cnt_id']) { ?>
      <button name="submit" type="submit" class="btn btn-sm btn-blue mb-1 mb-sm-0" value="1"><i class="fa-solid fa-rotate"></i> <?php echo $BL['be_article_cnt_button1'] ?></button>
      <button name="save" type="submit" class="btn btn-sm btn-blue ms-1 mb-1 mb-sm-0" value="<?php echo $BL['be_article_cnt_button3'] ?>"><i class="fa-solid fa-check"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
    <?php } else { ?>
      <button name="submit" type="submit" class="btn btn-sm btn-blue mb-1 mb-sm-0" value="1"><i class="fa-solid fa-rotate"></i> <?php echo $BL['be_admin_fcat_button2'] ?></button>
      <button name="save" type="submit" class="btn btn-sm btn-blue ms-1 mb-1 mb-sm-0" value="<?php echo $BL['be_article_cnt_button3'] ?>"><i class="fa-solid fa-check"></i> <?php echo $BL['be_article_cnt_button3'] ?></button>
     <?php } ?>
      <button name="close" type="button" class="btn btn-sm btn-danger ms-sm-3 mb-1 mb-sm-0" onclick="closeForm();"><i class="fa-solid fa-times"></i> <?php echo $BL['be_admin_struct_close'] ?></button>
    </div>
  </div>

</form>

<script type="text/javascript">
  showImage();
</script>
<?php

  }
  // Stop news form
