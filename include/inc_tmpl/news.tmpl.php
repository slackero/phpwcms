<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2023, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// News
$news = new cmsgoNews();

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
  <div class="col text-center text-sm-left">
    <h1><?php echo $BL['be_news'] ?></h1>
  </div>
  <div class="col text-center text-sm-right mb-3">
    <a class="btn btn-sm btn-blue" href="<?php echo $news->base_url ?>&amp;cntid=0&amp;action=edit" title="<?php echo $BL['be_news_create'] ?>"><i class="fa fa-plus"></i> <?php echo $BL['be_news_create'] ?></a>
  </div>
</div>

<div class="card">
    <div class="card-header"><h2><?php echo $BL['be_news_list'] ?></h2></div>
    <div class="card-body">

    <form action="<?php echo $news->base_url ?>" method="post" id="paginate">
    <div class="form-group mb-2">
        <input type="hidden" name="filter" value="1" />
      <div class="form-row align-items-center">
        <div class="col-12 col-sm">
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text bg-success border-0">
                <input name="showactive" id="showactive" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, ( $news->filter_status == 0 || $news->filter_status == 1 ) ? 1 : 0 ) ?> />
              </div>
              <div class="input-group-text bg-danger border-0">
                  <input name="showinactive" id="showinactive" type="checkbox" onclick="this.form.submit();"<?php  is_checked(1, ( $news->filter_status == 0 || $news->filter_status == 2 ) ? 1 : 0 ) ?> />
              </div>
            </div>
            <div class="input-group-append">
              <span class="input-group-text border-0" id="basic-addon2"><i class="fas fa-eye"></i></span>
            </div>
          </div>
        </div>
      <div class="col-sm-auto my-2 my-sm-0">
        <select name="sort" class="custom-select form-control form-control-sm" onchange="this.form.submit();" >
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
            <select name="keyword" data-toggle="tooltip" title="<?php echo $BL['be_tooltip_filter_for'] ?> <?php echo $BL['be_tags'] ?>" class="custom-select form-control form-control-sm" onchange="this.form.submit();">
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

        <div class="col-sm-auto my-2 my-sm-0">
            <div class="input-group">
                <input name="filter" id="filter" size="15" data-toggle="tooltip" title="<?php echo $BL['be_tooltip_filter_for'] ?> <?php echo $BL['be_text_full'] ?>" class="form-control form-control-sm" value="<?php echo html($news->filter) ?>" type="search">
                <span class="input-group-append">
                    <input class="btn btn-sm btn-secondary" name="gofilter" value="Filter" type="submit">
                </span>
            </div>
        </div>

        <div class="col-sm-auto text-sm-right">
            <?php echo getItemsPerPageMenu(); ?>
            <script>
                $(function(){
                    $('#news-paginate').on('change', function() {
                        window.location = '<?php echo $news->base_url_decoded; ?>&showipp=' + $(this).val();
                    });
                });
            </script>
        </div>

      </div>
  </div>
</form>

<?php
    echo $news->listBackend();
    $cmsgo['be_parse_lang_process'] = true;
?>
</div>
</div>
<div class="form-group text-center text-sm-right mt-4">
  <a class="btn btn-sm btn-blue" href="<?php echo $news->base_url ?>&amp;cntid=0&amp;action=edit" title="<?php echo $BL['be_news_create'] ?>"><i class="fa fa-plus"></i> <?php echo $BL['be_news_create'] ?></a>
</div>

<?php
  }
  // Begin news form
  if(count($news->data)) {
    // some JavaScripts wee need
    initJsCalendar();
    initJsOptionSelect();
    initJsAutocompleter();
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
    img.html('<img src="<?php echo CMSGO_URL.CMSGO_RESIZE_IMAGE.'/'.$cmsgo['img_list_width'].'x'.$cmsgo['img_list_height'] ?>/'+id+'" alt="" border="0" />');
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

function closeForm() {
  document.location.href='<?php echo $news->base_url_decoded ?>';
  return false;
}

$(function(){

  /* Autocompleter for categories/tags */
  $("#news_keyword_autosuggest").autoSuggest('<?php echo CMSGO_URL ?>include/inc_act/ajax_connector.php', {
    selectedItemProp: "cat_name",
    selectedValuesProp: 'cat_name',
    searchObjProps: "cat_name",
    queryParam: 'value',
    extraParams: '&method=json&action=newstags&<?php echo get_token_get_string(); ?>',
    startText: '',
    preFill: $("#cnt_category").val(),
    neverSubmit: true,
    asHtmlID: 'keyword-autosuggest',
    emptyText: '<?php echo $BL['be_cnt_noresult']; ?>'
  });

  $('#newsform').submit(function(event){

    $("#cnt_category").val($('#as-values-keyword-autosuggest').val());
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

});

</script>

<form action="<?php echo $news->formAction() ?>" method="post" class="free" id="newsform" name="newsform" required>
  <div class="row">
    <div class="col-sm-auto text-center text-sm-left">
      <h1><?php echo $BL['be_news'] ?></h1>
    </div>
    <div class="col-sm text-center text-sm-right mb-3">
      <?php if($news->data['cnt_id']) { ?>
      <input name="submit" type="submit" class="btn btn-sm btn-blue mb-1 mb-sm-0" value="<?php echo $BL['be_article_cnt_button1'] ?>" />
      <input name="save" type="submit" class="btn btn-sm btn-blue mb-1 mb-sm-0" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
    <?php } else { ?>
      <input name="submit" type="submit" class="btn btn-sm btn-blue mb-1 mb-sm-0" value="<?php echo $BL['be_admin_fcat_button2'] ?>" />
      <input name="save" type="submit" class="btn btn-sm btn-blue mb-1 mb-sm-0" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
     <?php } ?>
      <input name="new" type="button" class="btn btn-sm btn-blue mx-sm-3 mb-1 mb-sm-0" value="<?php echo ($BL['be_news_create']) ?>" onclick="emptyNews();" />
      <input name="close" type="button" class="btn btn-sm btn-blue mb-1 mb-sm-0" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="closeForm();" />
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
  <div class="form-group align-items-center form-row">
    <label for="be_article_cnt_ctitle" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_cnt_ctitle'] ?></label>
    <div class="col">
      <input name="cnt_title" class="form-control form-control-sm" id="cnt_title" value="<?php echo html($news->data['cnt_title']) ?>" maxlength="250" type="text" required >
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="be_article_asubtitle" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_asubtitle'] ?></label>
    <div class="col">
      <input name="cnt_subtitle" class="form-control form-control-sm" id="cnt_subtitle" value="<?php echo html($news->data['cnt_subtitle']) ?>" maxlength="250" type="text">
    </div>
  </div>

  <hr />

  <div class="form-group align-items-center form-row">
      <label for="be_teasertext" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_teasertext'] ?></label>
    <div class="form-check form-check-inline col-sm-auto">
      <input class="form-check-input" type="radio" id="text_format0" name="cnt_textformat" value="plain"<?php is_checked('plain', $news->data['cnt_textformat']); ?> />
      <label class="form-check-label" for="text_format0"><?php echo $BL['be_ctype_plaintext'] ?></label>
    </div>
    <div class="form-check form-check-inline col-sm-auto">
      <input class="form-check-input" type="radio" id="text_format1" name="cnt_textformat" value="markdown"<?php is_checked('markdown', $news->data['cnt_textformat']); ?> />
      <label class="form-check-label" for="text_format1">MarkDown <a href="http://en.wikipedia.org/wiki/Markdown" target="_blank" data-toggle="tooltip" title="Wikipedia: Markdown"><i class="fas fa-info-circle text-blue"></i></a></label>
    </div>
    <div class="form-check form-check-inline col-sm-auto">
            <input class="form-check-input" type="radio" id="text_format2" name="cnt_textformat" value="textile" <?php is_checked('textile', $news->data['cnt_textformat']); ?> />
            <label class="form-check-label" for="text_format2">Textile <a href="http://en.wikipedia.org/wiki/Textile_%28markup_language%29" target="_blank" data-toggle="tooltip" title="Wikipedia: Textile"><i class="fas fa-info-circle text-blue"></i></a></label>
    </div>
    <div class="form-check form-check-inline col">
      <input class="form-check-input" type="radio" id="text_format3" name="cnt_textformat" value="br" <?php is_checked('br', $news->data['cnt_textformat']); ?> />
      <label class="form-check-label" for="text_format3">BR</label>
    </div>
  </div>

  <div class="form-group form-row">
      <label class="col-form-label col-sm-2"></label>
      <div class="col">
      <textarea name="cnt_teasertext" id="cnt_teasertext" class="form-control form-control-sm" rows="5"><?php echo html($news->data['cnt_teasertext']) ?></textarea>
    </div>
  </div>

  <hr />

    <div class="form-group form-row align-items-center">
      <label for="start_date" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_cnt_start'] ?></label>
      <div class="col-sm-auto">
        <div class="date input-group mb-2 mb-sm-0" id="datetimepickerstartdate">
          <input type="text" class="form-control form-control-sm datetimepicker" name="calendar_start_date" id="start_date" value="<?php echo $news->data['cnt_date_start']; ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" />
          <div class="input-group-append">
            <span class="datepickerbutton btn-blue input-group-text form-control form-control-sm"><i class="far fa-calendar-alt fa-fw"></i></span>
          </div>
        </div>
      </div>
      <div class="col-sm-auto">
        <div class="input-group" id="datetimepickerstarttime">
          <input type="text" class="form-control form-control-sm datetimepicker" name="calendar_start_time" id="start_time" value="<?php echo $news->data['cnt_time_start']; ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" />
          <div class="input-group-append">
            <span class="datepickerbutton btn-blue input-group-text form-control form-control-sm"><i class="far fa-clock"></i></span>
          </div>
        </div>
      </div>
    </div>
    <script>
      $(function () {
          $('#datetimepickerstartdate').datetimepicker({
            locale: 'de-ch',
            format: "DD.MM.YYYY",
            showClose: true
          });

          $('#datetimepickerstarttime').datetimepicker({
            locale: 'de-ch',
            format: "H:mm",
            showClose: true
          });
      });
    </script>

    <div class="form-group form-row align-items-center">
      <label for="end_date" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_cnt_end'] ?></label>
      <div class="col-sm-auto">
        <div class="input-group mb-2 mb-sm-0" id="datetimepickerenddate">
          <input type="text" class="form-control form-control-sm datetimepicker" name="calendar_end_date" id="end_date" value="<?php echo $news->data['cnt_date_end']; ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" />
          <div class="input-group-append">
            <span class="datepickerbutton btn-blue input-group-text form-control form-control-sm"><i class="far fa-calendar-alt fa-fw"></i></span>
          </div>
        </div>
      </div>
      <div class="col-sm-auto">
        <div class="input-group" id="datetimepickerendtime">
          <input type="text" class="form-control form-control-sm datetimepicker" name="calendar_end_time" id="end_time" value="<?php echo $news->data['cnt_time_end']; ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" />
          <div class="input-group-append">
            <span class="datepickerbutton btn-blue input-group-text form-control form-control-sm"><i class="far fa-clock"></i></span>
            </div>
        </div>
      </div>
    </div>
    <script>
      $(function () {
          $('#datetimepickerenddate').datetimepicker({
            locale: 'de-ch',
            format: "DD.MM.YYYY",
            showClose: true
          });

          $('#datetimepickerendtime').datetimepicker({
            locale: 'de-ch',
            format: "H:mm",
            showClose: true
          });
      });
    </script>

    <div class="form-group form-row align-items-center">
      <label for="sort_date" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_sort_date'] ?></label>
      <div class="col-sm-auto">
        <div class="input-group mb-2 mb-sm-0" id="datetimepickersortdate">
          <input type="text" class="form-control form-control-sm datetimepicker" name="sort_date" id="sort_date" value="<?php echo $news->data['cnt_sort_date']; ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" />
          <div class="input-group-append">
            <span class="datepickerbutton btn-blue input-group-text form-control form-control-sm"><i class="far fa-calendar-alt fa-fw"></i></span>
            </div>
        </div>
      </div>
      <div class="col-sm-auto">
        <div class="input-group" id="datetimepickersorttime">
          <input type="text" class="form-control form-control-sm datetimepicker" name="sort_time" id="sort_time" value="<?php echo $news->data['cnt_sort_time']; ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" />
          <div class="input-group-append">
            <span class="datepickerbutton btn-blue input-group-text form-control form-control-sm"><i class="far fa-clock"></i></span>
            </div>
        </div>
      </div>
    </div>
    <script>
      $(function () {
          $('#datetimepickersortdate').datetimepicker({
            locale: 'de-ch',
            format: "DD.MM.YYYY",
            showClose: true
          });

          $('#datetimepickersorttime').datetimepicker({
            locale: 'de-ch',
            format: "H:mm",
            showClose: true
          });
      });
    </script>

  <hr />

  <div class="form-group align-items-center form-row">
    <label for="be_article_cnt_ctitle" class="col-sm-2 col-form-label text-right"><a id="cnt_name_click" class="underline text-blue"><?php echo $BL['be_title'] ?></a></label>
    <div class="col">
      <input name="cnt_name" class="form-control form-control-sm" id="cnt_name" value="<?php echo html($news->data['cnt_name']) ?>" placeholder="<?php echo $BL['be_title'] ?>" maxlength="200" type="text" required>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="be_alias" class="col-sm-2 col-form-label text-right"><a id="cnt_alias_click" class="underline text-blue"><?php echo $BL['be_alias'] ?></a></label>
    <div class="col">
      <input name="cnt_alias" class="form-control form-control-sm" id="cnt_alias" value="<?php echo html($news->data['cnt_alias']) ?>" placeholder="<?php echo $BL['be_alias'] ?>" maxlength="200" type="text" required>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label for="be_alias" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_tags'] ?> <i class="fas fa-info-circle text-blue" data-toggle="tooltip" title="<?php echo $BL['be_input_text_tab'] ?>"></i></label>
    <div class="col">
      <input type="text" id="news_keyword_autosuggest" class="form-control form-control-sm"  /><input type="hidden" name="cnt_category" id="cnt_category" value="<?php echo html($news->data['cnt_category']) ?>" />
    </div>
  </div>

  <?php if(count($cmsgo['allowed_lang']) > 1):  ?>
    <div class="form-group form-row align-items-center">
        <label for="cnt_lang" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_lang'] ?></label>
        <div class="col-sm-4">
           <select name="cnt_lang" id="cnt_lang" class="custom-select form-control form-control-sm">
            <?php
              echo '  <option value=""';
              is_selected('', $news->data['cnt_lang']);
              echo '>'. $BL['be_admin_tmpl_default'].'</option>';
              foreach($cmsgo['allowed_lang'] as $key => $lang):
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

  <div class="form-group align-items-center form-row">
      <label for="be_priorize" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_priorize'] ?></label>
      <div class="col-sm-4">
        <select name="cnt_prio" id="cnt_prio"  class="custom-select form-control form-control-sm" data-toggle="tooltip" title="<?php echo $BL['be_priorize'] ?>">
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

  <div class="form-group form-row mt-3">
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

    include CMSGO_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

  ?>
    </div>
  </div>
  <hr />

    <div class="form-group align-items-center form-row">
        <label for="be_cnt_image" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_image'] ?></label>
        <div class="col-sm-4">
            <div class="input-group">
                <span class="input-group-prepend">
                    <button class="modalButton btn btn-sm btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=7&amp;target=summary" ></button>
                </span>
                <input name="cnt_image_name" type="text" id="cnt_image_name" class="form-control form-control-sm" value="<?php echo html($news->data['cnt_image']['name']) ?>" maxlength="250" onfocus="this.blur()" />
                <span class="input-group-append">
                    <a href="#" class="btn btn-sm btn-danger trash" type="button" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="setImgIdName();return false;"></a>
                </span>
            </div>
            <input name="cnt_image_id" id="cnt_image_id" type="hidden" value="<?php echo $news->data['cnt_image']['id'] ?>" />
        </div>
    </div>

  <div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right"></label>
    <div class="form-check form-check-inline col-sm-auto">
            <input class="form-check-input" type="checkbox" id="cnt_image_zoom" name="cnt_image_zoom" value="1"<?php is_checked(1, $news->data['cnt_image']['zoom']); ?> />
            <label class="form-check-label" for="cnt_image_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>
    </div>
    <div class="form-check form-check-inline col-sm-auto">
            <input class="form-check-input" type="checkbox" id="cnt_image_lightbox" name="cnt_image_lightbox" value="1"<?php is_checked(1, $news->data['cnt_image']['lightbox']); ?> />
            <label class="form-check-label" for="cnt_image_lightbox"><?php echo $BL['be_cnt_lightbox'] ?></label>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label class="col-sm-2"></label>
    <div id="cnt_image" class="col-sm-auto"></div>
  </div>

  <div class="form-group form-row">
      <label class="col-form-label col-sm-2 text-right"><?php echo $BL['be_cnt_caption'] ?></label></label>
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

  <div class="form-group align-items-center form-row">
        <label for="be_profile_label_website" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_website'] ?></label>
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
  <div class="form-group form-row" >
      <label for="be_selection" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_files'] ?></label>
      <div class="col">
        <select name="cnt_files[]" size="<?php echo $news->fileRows ?>" multiple="multiple" id="cfile_list" class="custom-select form-control form-control-sm h-100">
      <?php if($news->fileCount) {
            foreach($news->files as $f_id => $item) {
              echo '<option value="' . $item['f_id'] . '">' . (empty($item['f_name']) ? '-- ' . $BL['be_msg_del'] . ' --' : html($item['f_name'])) . '</option>' . LF;
            }
          }
      ?>
        </select>
      </div>
      <div class="col-sm-auto">
        <button type="button" class="modalButton btn btn-sm btn-blue mb-1" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=9&amp;target=summary" ><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i></button><br />
        <button type="button" class="btn btn-sm btn-secondary mb-1" data-toggle="tooltip" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(getObjectById('cfile_list'));return false;"><i class="fa fa-angle-up fa-fw" aria-hidden="true"></i></button><br />
        <button type="button" class="btn btn-sm btn-secondary mb-1" data-toggle="tooltip" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(getObjectById('cfile_list'));return false;"><i class="fa fa-angle-down fa-fw" aria-hidden="true"></i></button><br />
        <button type="button" class="btn btn-sm btn-danger mb-1" onclick="removeSelectedOptions(getObjectById('cfile_list'));return false;" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delfile'] ?>"><i class="far fa-trash-alt fa-fw" aria-hidden="true"></i></button>
      </div>
  </div>

  <div class="form-group form-row">
      <label class="col-form-label col-sm-2 text-right"><?php echo $BL['be_cnt_description'] ?></label>
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

  <div class="form-group align-items-center form-row mb-3">
    <label class="col-sm-2 col-form-label text-right"></label>
    <div class="form-check form-check-inline col-sm-auto">
            <input class="form-check-input" type="checkbox" id="cnt_file_gallery" name="cnt_file_gallery" value="1"<?php is_checked(1, $news->data['cnt_files']['gallery']); ?> />
            <label class="form-check-label" for="be_imagefiles_as_gallery"><?php echo $BL['be_imagefiles_as_gallery'] ?></label>
    </div>
    <div class="form-check form-check-inline col-sm-auto">
            <input class="form-check-input" type="checkbox" id="cnt_file_gallery_download" name="cnt_file_gallery_download" value="1"<?php is_checked(1, $news->data['cnt_files']['gallery_download']); ?> />
            <label class="form-check-label" for="be_gallerydownload"><?php echo $BL['be_gallerydownload'] ?></label>
    </div>
  </div>

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_read_more_link'] ?></label>
    <div class="col-sm-4">
        <div class="input-group">
          <span class="input-group-prepend">
            <button class="modalButton btn btn-sm btn-blue sitemap-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="articlebrowser.php?opt=1" ></button>
          </span>
          <input type="text" name="cnt_link" id="cnt_link" value="<?php echo html_entities($news->data['cnt_link']) ?>" class="form-control form-control-sm" maxlength="250" data-toggle="tooltip" title="<?php echo $BL['be_read_more_link'] ?>" />
        </div><?php
          if (intval($news->data['cnt_link'])> 0) {
            $adata = get_article_data($news->data['cnt_link']);
            echo '<small><label class="mt-1">' . $BL['be_cnt_target'] .':&nbsp;</label>';
            if (is_array($adata)) {
              echo '<a href="cmsgo.php?&do=articles&p=2&s=1&id=' . $adata['article_id'] . '" target="_blank" data-toggle="tooltip" title="' . $adata['article_title'] . '">' . $adata['article_alias'] . $cmsgo['rewrite_ext'] .'</a>';
            } else {
              echo $BL['be_admin_usr_err'];
            }
            echo '</small>';
          }
          ?>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
      <label for="be_admin_page_text" class="col-sm-2 col-form-label text-right">URL <?php echo $BL['be_admin_page_text'] ?></label>
        <div class="col">
            <input name="cnt_linktext" class="form-control form-control-sm" id="cnt_linktext" value="<?php echo html_entities($news->data['cnt_linktext']) ?>" maxlength="250" type="text" data-toggle="tooltip" title="URL <?php echo $BL['be_admin_page_text'] ?>">
        </div>
  </div>

  <div class="form-group align-items-center form-row">
      <label for="be_article_username" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_article_username'] ?></label>
        <div class="col">
            <input name="cnt_editor" class="form-control form-control-sm" id="cnt_editor" value="<?php echo html($news->data['cnt_editor']) ?>" maxlength="250" type="text" data-toggle="tooltip" title="<?php echo $BL['be_article_username'] ?>">
        </div>
       <label for="be_place" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_place'] ?></label>
        <div class="col">
            <input name="cnt_place" class="form-control form-control-sm" id="cnt_place" value="<?php echo html($news->data['cnt_place']) ?>" maxlength="250" type="text" data-toggle="tooltip" title="<?php echo $BL['be_place'] ?>">
        </div>
  </div>

 <hr />

    <div class="form-group form-row bg-grey py-2 mb-0">
    <label class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_ftptakeover_status'] ?></label>
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

  <div class="row mt-4 text-right">
    <div class="col">
      <?php if($news->data['cnt_id']) { ?>
      <input name="submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button1'] ?>" />
      <input name="save" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
    <?php } else { ?>
      <input name="submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_admin_fcat_button2'] ?>" />
      <input name="save" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
     <?php } ?>
      <input name="new" type="button" class="btn btn-sm btn-blue mx-sm-3" value="<?php echo ($BL['be_news_create']) ?>" onclick="emptyNews();" />
      <input name="close" type="button" class="btn btn-sm btn-blue" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="closeForm();" />
    </div>
  </div>

</form>

<script type="text/javascript">
  showImage();
</script>
<?php

  }
  // Stop news form
