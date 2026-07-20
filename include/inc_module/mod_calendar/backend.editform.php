<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

initJsCalendar();
initJsAutocompleter();

?>
<form action="<?php echo MODULE_HREF ?>&amp;edit=<?php echo $plugin['data']['calendar_id'] ?>" method="post" id="calendar_form">
<div class="row">
    <div class="col-sm">
      <h1><?php echo $BLM['listing_title'] ?></h1>
    </div>
    <div class="col-sm">
      <div class="form-group text-right">
        <input name="submit" type="submit" class="btn btn-blue btn-sm" value="<?php echo empty($plugin['data']['calendar_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
        <input name="save" type="submit" class="btn btn-blue btn-sm mr-sm-3" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
        <input name="new" type="button" class="btn btn-blue btn-sm" value="<?php echo ucfirst($BL['be_msg_new']) ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>&edit=0';return false;" />
        <input name="close" type="button" class="btn btn-blue btn-sm" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>';return false;" />
       </div>
     </div>
  </div>

<div class="card">
<div class="card-header"><?php echo $BLM['add_event'] ?></div>
<div class="card-body">


	<input type="hidden" name="calendar_id" value="<?php echo $plugin['data']['calendar_id'] ?>" />

	<div class="form-group form-row align-items-center">
  		<div class="col-sm-2 text-right"><?php echo $BL['be_cnt_last_edited']  ?>:</div>
  		<div class="col"><?php echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['calendar_changed']))) ?></div>
	</div>

    <?php if (!empty($plugin['data']['calendar_created'])) {
    ?>

	<div class="form-group form-row align-items-center">
  		<div class="col-sm-2 text-right"><?php echo $BL['be_fprivedit_created']  ?>:</div>
  		<div class="col"><?php echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['calendar_created']))) ?></div>
	</div>

    <?php
} ?>

	<div class="form-group form-row align-items-center">
    	<label for="be_admin_tmpl_js" class="col-sm-2 col-form-label text-right"><?php echo $BLM['calendar_title'] ?></label>
    	<div class="col">
    		<input class="form-control form-control-sm<?php
        //error class
        if (!empty($plugin['error']['calendar_title'])) {
            echo ' errorInputText';
        }
        ?>" value="<?php echo html($plugin['data']['calendar_title']) ?>" name="calendar_title" id="calendar_title" type="text" required >
    	</div>
    </div>

<hr />

    <div class="form-group form-row align-items-center">
    	<label class="col-sm-2 col-form-label text-right"><?php echo $BLM['calendar_start'] ?></label>
        <div class="col-sm-10">
          <div class="d-flex flex-wrap align-items-center">
            <div class="my-1 mr-sm-3 mb-2 mb-sm-0">
              <div class="input-group input-group-sm datetime-picker-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><?php echo $BL['be_msg_from'] ?></span>
                </div>
                <input type="text" class="form-control datetimepicker-input" name="calendar_start_date" id="calendar_start_date" value="<?php echo html($plugin['data']['calendar_start_date']) ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" data-target="#calendar_start_date" autocomplete="off" />
                <div class="input-group-append" data-target="#calendar_start_date" data-toggle="datetimepicker">
                  <span class="input-group-text btn-blue"><i class="far fa-calendar-alt fa-fw"></i></span>
                </div>
                <input type="text" class="form-control datetimepicker-input" name="calendar_start_time" id="calendar_start_time" value="<?php echo html($plugin['data']['calendar_start_time']) ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" data-target="#calendar_start_time" autocomplete="off" />
                <div class="input-group-append" data-target="#calendar_start_time" data-toggle="datetimepicker">
                  <span class="input-group-text btn-blue"><i class="far fa-clock fa-fw"></i></span>
                </div>
              </div>
            </div>
            <div class="my-1 mr-sm-3 mb-2 mb-sm-0">
              <div class="input-group input-group-sm datetime-picker-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><?php echo $BL['be_article_aend'] ?></span>
                </div>
                <input type="text" class="form-control datetimepicker-input" name="calendar_end_date" id="calendar_end_date" value="<?php echo html($plugin['data']['calendar_end_date']) ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" data-target="#calendar_end_date" autocomplete="off" />
                <div class="input-group-append" data-target="#calendar_end_date" data-toggle="datetimepicker">
                  <span class="input-group-text btn-blue"><i class="far fa-calendar-alt fa-fw"></i></span>
                </div>
                <input type="text" class="form-control datetimepicker-input" name="calendar_end_time" id="calendar_end_time" value="<?php echo html($plugin['data']['calendar_end_time']) ?>" maxlength="5" placeholder="<?php echo $BL['default_time_format'] ?>" data-target="#calendar_end_time" autocomplete="off" />
                <div class="input-group-append" data-target="#calendar_end_time" data-toggle="datetimepicker">
                  <span class="input-group-text btn-blue"><i class="far fa-clock fa-fw"></i></span>
                </div>
              </div>
            </div>
            <div class="my-1 form-check form-check-inline align-self-center">
              <input type="checkbox" name="calendar_allday" id="calendar_allday" class="form-check-input" value="1"<?php is_checked(1, $plugin['data']['calendar_allday']) ?> onchange="setCalendarAllDay();" />
              <label for="calendar_allday" class="form-check-label mb-0" onclick="setCalendarAllDay()">
                <?php echo $BLM['all_day'] ?>
              </label>
            </div>
          </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $('#calendar_start_date').datetimepicker({
              locale: '<?php echo $_SESSION['wcs_user_lang'] ?>',
              format: "DD.MM.YYYY",
              buttons: {
                showClose: true
              }
            });

            $('#calendar_start_time').datetimepicker({
              locale: '<?php echo $_SESSION['wcs_user_lang'] ?>',
              format: "HH:mm",
              buttons: {
                showClose: true
              }
            });

            $('#calendar_end_date').datetimepicker({
              locale: '<?php echo $_SESSION['wcs_user_lang'] ?>',
              format: "DD.MM.YYYY",
              buttons: {
                showClose: true
              }
            });

            $('#calendar_end_time').datetimepicker({
              locale: '<?php echo $_SESSION['wcs_user_lang'] ?>',
              format: "HH:mm",
              buttons: {
                showClose: true
              }
            });
        });
    </script>

  <div class="form-group form-row align-items-center">
    <label for="be_admin_tmpl_js" class="col-sm-2 col-form-label text-right"><?php echo $BLM['repeat'] ?></label>
    <div class="col-sm-4">
      <select name="calendar_range" id="calendar_range" class="custom-select form-control form-control-sm" onchange="setRangeDates(this.options[this.selectedIndex].value)">
        <option value="0"<?php is_selected(0, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_0'] ?></option>
        <option value="1"<?php is_selected(1, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_1'] ?></option>
        <option value="2"<?php is_selected(2, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_2'] ?></option>
        <option value="3"<?php is_selected(3, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_3'] ?></option>
        <option value="4"<?php is_selected(4, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_4'] ?></option>
        <option value="15"<?php is_selected(15, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_15'] ?></option>
        <option value="16"<?php is_selected(16, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_16'] ?></option>
        <option value="5"<?php is_selected(5, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_5'] ?></option>
        <option value="6"<?php is_selected(6, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_6'] ?></option>
        <option value="7"<?php is_selected(7, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_7'] ?></option>
        <option value="8"<?php is_selected(8, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_8'] ?></option>
        <option value="9"<?php is_selected(9, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_9'] ?></option>
        <option value="10"<?php is_selected(10, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_10'] ?></option>
        <option value="11"<?php is_selected(11, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_11'] ?></option>
        <option value="12"<?php is_selected(12, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_12'] ?></option>
        <option value="13"<?php is_selected(13, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_13'] ?></option>
        <option value="14"<?php is_selected(14, $plugin['data']['calendar_range']) ?>><?php echo $BLM['repeat_14'] ?></option>
      </select>
    </div>
  </div>

    <div id="rDate0" class="form-group form-row align-items-center">
      <label for="calendar_end_date" class="col-sm-2 col-form-label text-right"><?php echo $BLM['repeat_till'] ?></label>
      <div class="col-sm-auto">
        <div class="date input-group input-group-sm" data-target-input="#calendar_range_start">
          <input type="text" class="form-control datetimepicker-input" name="calendar_range_start" id="calendar_range_start" value="<?php echo html($plugin['data']['calendar_rangestart']) ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" data-target="#calendar_range_start" autocomplete="off" />
          <div class="input-group-append" data-target="#calendar_range_start" data-toggle="datetimepicker">
            <span class="input-group-text btn-blue"><i class="far fa-calendar-alt fa-fw"></i></span>
          </div>
        </div>
      </div>
      <div class="col-sm-auto mt-3 mt-sm-0">
        <div class="date input-group input-group-sm" data-target-input="#calendar_range_end">
          <input type="text" class="form-control datetimepicker-input" name="calendar_range_end" id="calendar_range_end" value="<?php echo html($plugin['data']['calendar_rangeend']) ?>" maxlength="10" placeholder="<?php echo $BL['default_date_format'] ?>" data-target="#calendar_range_end" autocomplete="off" />
          <div class="input-group-append" data-target="#calendar_range_end" data-toggle="datetimepicker">
            <span class="input-group-text btn-blue"><i class="far fa-calendar-alt fa-fw"></i></span>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $('#calendar_range_start').datetimepicker({
              locale: '<?php echo $_SESSION['wcs_user_lang'] ?>',
              format: "DD.MM.YYYY",
              buttons: {
                showClose: true
              }
            });

            $('#calendar_range_end').datetimepicker({
              locale: '<?php echo $_SESSION['wcs_user_lang'] ?>',
              format: "DD.MM.YYYY",
              buttons: {
                showClose: true
              }
            });
        });
    </script>

	<div class="form-group form-row align-items-center">
    	<label for="where" class="col-sm-2 col-form-label text-right"><?php echo $BLM['where'] ?></label>
    	<div class="col">
    		<input name="calendar_where" type="text" id="calendar_where" class="form-control form-control-sm" value="<?php echo html($plugin['data']['calendar_where']) ?>" maxlength="220" />
    	</div>
    </div>

	<div class="form-group form-row align-items-center">
    	<span class="col-sm-2 col-form-label text-right"><?php echo $BLM['calendar_token'] ?></span>
    	<div class="col">
    		<input type="text" id="calendar_tag_autosuggest" class="form-control form-control-sm" aria-label="<?php echo html_specialchars($BLM['calendar_token']) ?>" />
    		<input name="calendar_tag" type="hidden" id="calendar_tag" class="form-control form-control-sm" value="<?php echo html(trim($plugin['data']['calendar_tag'])) ?>" maxlength="255" />
    	</div>
    </div>

	<div class="form-group form-row align-items-center">
    	<span class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_lang'] ?></span>
    	<div class="col">
    		<input type="text" id="calendar_lang_autosuggest" class="form-control form-control-sm" aria-label="<?php echo html_specialchars($BL['be_profile_label_lang']) ?>" />
    		<input name="calendar_lang" type="hidden" id="calendar_lang" class="form-control form-control-sm" value="<?php echo html(trim($plugin['data']['calendar_lang'])) ?>" maxlength="50" />
    	</div>
    </div>

	<div class="form-group form-row align-items-center">
    	<label class="col-sm-2 col-form-label text-right"><?php echo $BLM['article_link'] ?></label>
    	<div class="col-sm-4">
			<input name="calendar_refid" type="text" id="calendar_refid" class="form-control form-control-sm" value="<?php echo empty($plugin['data']['calendar_refid']) ? '' : html($plugin['data']['calendar_refid']) ?>"  maxlength="500" />
    	</div>
    	<div class="col">
    		<?php echo $BLM['more_info'] ?>
    	</div>
    </div>

<hr />

	<div class="form-group form-row">
    	<label for="where" class="col-sm-2 col-form-label text-right"><?php echo $BLM['calendar_teasertext'] ?></label>
    	<div class="col">
    		<textarea name="calendar_teaser" id="calendar_teaser" class="form-control form-control-sm" rows="5"><?php echo html($plugin['data']['calendar_teaser']) ?></textarea>
    	</div>
    </div>

	<div class="form-group form-row">
    	<label for="where" class="col-sm-2 col-form-label text-right"><?php echo $BLM['calendar_text'] ?></label>
    	<div class="col">
    		<?php
        $wysiwyg_editor = array(
            'value'     => $plugin['data']['calendar_text'],
            'field'     => 'calendar_text',
            'height'    => '400px',
            'width'     => '100%',
            'rows'      => '15',
            'editor'    => $_SESSION["WYSIWYG_EDITOR"],
            'lang'      => 'en'
        );
        include CMSGO_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';
        ?>
    	</div>
    </div>

<hr />

  <div class="form-group align-items-center form-row">
    <label for="be_cnt_image" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_image'] ?></label>
    <div class="col-sm-4">
      <div class="input-group input-group-sm">
        <div class="input-group-prepend">
          <button class="modalButton btn btn-blue folder-open" type="button" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=7" ></button>
        </div>
        <input name="cnt_image_name" type="text" id="cnt_image_name" class="form-control" value="<?php echo html($plugin['data']['calendar_image']['name']) ?>" maxlength="250" onfocus="this.blur()" />
        <div class="input-group-append">
          <a href="#" class="btn btn-danger trash" type="button" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="setImgIdName();return false;"></a>
        </div>
      </div>
      <input name="cnt_image_id" id="cnt_image_id" type="hidden" value="<?php echo $plugin['data']['calendar_image']['id'] ?>" />
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label class="col-sm-2 col-form-label text-right p-0"></label>
    <div class="form-check form-check-inline col-sm-auto">
			<input class="form-check-input" type="checkbox" id="cnt_image_zoom" name="cnt_image_zoom" value="1"<?php is_checked(1, $plugin['data']['calendar_image']['zoom']); ?> />
			<label class="form-check-label" for="cnt_image_zoom"><?php echo $BL['be_cnt_enlarge'] ?></label>
    </div>
    <div class="form-check form-check-inline col-sm-auto">
			<input class="form-check-input" type="checkbox" id="cnt_image_lightbox" name="cnt_image_lightbox" value="1"<?php is_checked(1, $plugin['data']['calendar_image']['lightbox']); ?> />
			<label class="form-check-label" for="cnt_image_lightbox"><?php echo $BL['be_cnt_lightbox'] ?></label>
    </div>
  </div>

  <div class="form-group align-items-center form-row">
    <label class="col-sm-2"></label>
    <div id="cnt_image" class="col-sm-auto"></div>
  </div>

	<div class="form-group form-row">
    	<label for="where" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_caption'] ?></label>
    	<div class="col-sm-4">
    		 <textarea name="cnt_image_caption" id="cnt_image_caption" class="form-control form-control-sm" rows="2"><?php echo html($plugin['data']['calendar_image']['caption']) ?></textarea>
    	</div>
    </div>

    <div class="form-group form-row">
    	<label for="where" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_website'] ?></label>
    	<div class="col-sm-4">
    		 <input type="text" name="cnt_image_link" id="cnt_image_link" class="form-control form-control-sm" maxlength="500" value="<?php echo html($plugin['data']['calendar_image']['link']) ?>" />
    	</div>
    </div>

    <div class="form-group form-row">
    	<label for="be_ftptakeover_status" class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_ftptakeover_status'] ?></label>
			<div class="form-check form-check-inline col-sm-auto">
				<input class="form-check-input" type="checkbox" name="calendar_status" id="calendar_status" value="1"<?php is_checked($plugin['data']['calendar_status'], 1) ?> />
				<label class="form-check-label" for="calendar_status"><?php echo $BL['be_cnt_activated'] ?></label>
			</div>
			<div class="form-check form-check-inline col-sm-auto">
				<input class="form-check-input" type="checkbox" name="calendar_duplicate" id="calendar_duplicate" value="1"<?php is_checked(empty($plugin['data']['calendar_duplicate'])?0:1, 1) ?> />
				<label class="form-check-label" for="calendar_duplicate"><?php echo $BLM['save_copy'] ?></label>
			</div>
    </div>

	</div>
</div>

	<div class="form-group text-right mt-4">
        <input name="submit" type="submit" class="bnt btn-blue btn-sm" value="<?php echo empty($plugin['data']['calendar_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
        <input name="save" type="submit" class="bnt btn-blue btn-sm mr-sm-3" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
        <input name="new" type="button" class="bnt btn-blue btn-sm" value="<?php echo ucfirst($BL['be_msg_new']) ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>&edit=0';return false;" />
        <input name="close" type="button" class="bnt btn-blue btn-sm" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>';return false;" />
     </div>

</form>

<script type="text/javascript">

$(function(){

    $("#calendar_tag_autosuggest").autoSuggest('<?php echo CMSGO_URL ?>include/inc_act/ajax_connector.php', {
        selectedItemProp: "calendar_tag",
        selectedValuesProp: 'calendar_tag',
        searchObjProps: "cat_name",
        queryParam: 'value',
        extraParams: '&method=json&action=category&<?php echo get_token_get_string(); ?>',
        startText: '',
        preFill: $("#calendar_tag").val(),
        neverSubmit: true,
        asHtmlID: 'keyword-autosuggest1'
    });

    $("#calendar_lang_autosuggest").autoSuggest('<?php echo CMSGO_URL ?>include/inc_act/ajax_connector.php', {
        selectedItemProp: "allowed_lang",
        selectedValuesProp: 'allowed_lang',
        searchObjProps: "allowed_lang",
        queryParam: 'value',
        extraParams: '&method=json&action=lang&<?php echo get_token_get_string(); ?>',
        startText: '',
        preFill: $("#calendar_lang").val(),
        neverSubmit: true,
        asHtmlID: 'keyword-autosuggest2'
    });

    $('#calendar_form').submit(function(event){
        $("#calendar_tag").val($('#as-values-keyword-autosuggest1').val());
        $("#calendar_lang").val($('#as-values-keyword-autosuggest2').val());
    });

    $("#calendar_lang").keyup(function(){
      this.value = this.value.replace(/[^a-z\-]/g, '');
      alert(this.value);
    });

    setCalendarAllDay();
    setRangeDates(<?php echo $plugin['data']['calendar_range'] ?>);

    showImage();
});

function setCalendarAllDay() {

    if($('#calendar_allday').is(':checked')) {
      $('#calendar_start_time, #calendar_start_time + .input-group-append').hide();
      $('#calendar_end_time, #calendar_end_time + .input-group-append').hide();
      $('.datetime-picker-group').addClass('all-day-active');
    } else {
      $('#calendar_start_time, #calendar_start_time + .input-group-append').show();
      $('#calendar_end_time, #calendar_end_time + .input-group-append').show();
      $('.datetime-picker-group').removeClass('all-day-active');
    }

}

function setRangeDates(value) {

    value = parseInt(value,10);
    if(!value) {
        $('#rDate0').hide();
    } else {
        $('#rDate0').show();
    }

}

function setImgIdName(file_id, file_name) {
    if(file_id == null) {
        file_id=0;
    }
    if(file_name == null) {
        file_name='';
    }
    $('#cnt_image_id').val(file_id);
    $('#cnt_image_name').val(file_name);

    showImage();
}

function showImage() {
  var id  = parseInt($('#cnt_image_id').val(),10);
  var img = $('#cnt_image');

  if(id > 0) {
		$('#cnt_image').html('<img src="<?php echo CMSGO_URL.CMSGO_RESIZE_IMAGE.'/'.$cmsgo['img_list_width'].'x'.$cmsgo['img_list_height'] ?>/'+id+'" alt="" border="0" />').show();
  } else {
    $('#cnt_image').hide();
  }
}

</script>
