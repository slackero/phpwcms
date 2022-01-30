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

initJsCalendar();
initMootoolsAutocompleter();

?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['listing_title'] ?></h1>

<form action="<?php echo MODULE_HREF ?>&amp;edit=<?php echo $plugin['data']['calendar_id'] ?>" method="post" id="calendar_form">
<input type="hidden" name="calendar_id" value="<?php echo $plugin['data']['calendar_id'] ?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">

    <tr>
        <td align="right" class="chatlist nowrap" nowrap="nowrap"><?php echo $BL['be_cnt_last_edited']  ?>:&nbsp;</td>
        <td class="v10"><?php echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['calendar_changed']))) ?></td>
    </tr>

    <?php if(!empty($plugin['data']['calendar_created'])) { ?>

    <tr>
        <td align="right" class="chatlist nowrap" nowrap="nowrap"><?php echo $BL['be_fprivedit_created']  ?>:&nbsp;</td>
        <td class="v10"><?php echo html(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['calendar_created']))) ?></td>
    </tr>

    <?php } ?>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['calendar_title'] ?>:&nbsp;</td>
        <td><input name="calendar_title" type="text" id="calendar_title" class="v12 width375<?php

        //error class
        if(!empty($plugin['error']['calendar_title'])) echo ' errorInputText';

        ?>" value="<?php echo html($plugin['data']['calendar_title']) ?>" size="30" maxlength="250" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>


    <tr>
        <td align="right" class="chatlist" valign="top" style="padding-top:17px"><?php echo $BLM['calendar_start'] ?>:&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">

            <tr>
                <td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['date_format'] ?></td>
                <td class="chatlist" id="endDate0">&nbsp;</td>
                <td class="chatlist" id="endDate1" style="padding-bottom:2px"><?php echo $BLM['time_format'] ?></td>
                <td colspan="2">&nbsp;</td>
            </tr>

            <tr>
                <td><input name="calendar_start_date" type="text" id="calendar_start_date" class="v12<?php

        //error class
        if(!empty($plugin['error']['calendar_start'])) echo ' errorInputText';

        ?>" style="width:100px;" value="<?php echo html($plugin['data']['calendar_start_date']) ?>" size="30" /></td>
        <td><script type="text/javascript">
        function aStart(date, month, year) {
            getFieldById('calendar_start_date').value = subrstr('00' + date, 2) + '<?php echo $BLM['date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BLM['date_delimiter'] ?>' + year;
        }
        calStart = new dynCalendar('calStart', 'aStart', 'img/dynCal/');
        calStart.setMonthCombo(false);
        calStart.setYearCombo(false);
        </script>&nbsp;</td>
        <td id="endDate2"><input name="calendar_start_time" type="text" id="calendar_start_time" class="v12" style="width:80px;" value="<?php echo html($plugin['data']['calendar_start_time']) ?>" size="30" /></td>

        <td id="endDate3">&nbsp;</td>
        <td><input type="checkbox" name="calendar_allday" id="calendar_allday" value="1"<?php is_checked(1, $plugin['data']['calendar_allday']) ?> onchange="setCalendarAllDay();" /></td>
        <td><label for="calendar_allday" onclick="setCalendarAllDay()"><?php echo $BLM['all_day'] ?></label></td>

            </tr>
        </table></td>

    </tr>

    <tr id="endDate4"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

    <tr id="endDate5">
        <td align="right" class="chatlist" valign="top" style="padding-top:17px"><?php echo $BLM['calendar_end'] ?>:&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">

            <tr>
                <td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['date_format'] ?></td>
                <td class="chatlist">&nbsp;</td>
                <td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['time_format'] ?></td>
            </tr>

            <tr>
                <td><input name="calendar_end_date" type="text" id="calendar_end_date" class="v12<?php

        //error class
        if(!empty($plugin['error']['calendar_end'])) echo ' errorInputText';

        ?>" style="width:100px;" value="<?php echo html($plugin['data']['calendar_end_date']) ?>" size="30" /></td>
        <td><script type="text/javascript">
        function aEnd(date, month, year) {
            getFieldById('calendar_end_date').value = subrstr('00' + date, 2) + '<?php echo $BLM['date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BLM['date_delimiter'] ?>' + year;
        }
        calEnd = new dynCalendar('calEnd', 'aEnd', 'img/dynCal/');
        calEnd.setMonthCombo(false);
        calEnd.setYearCombo(false);
        </script>&nbsp;</td>
        <td><input name="calendar_end_time" type="text" id="calendar_end_time" class="v12" style="width:80px;" value="<?php echo html($plugin['data']['calendar_end_time']) ?>" size="30" /></td>

            </tr>
        </table></td>

    </tr>

    <tr id="endDate0"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td></tr>


    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['repeat'] ?>:&nbsp;</td>
        <td><select name="calendar_range" id="calendar_range" class="v12" onchange="setRangeDates(this.options[this.selectedIndex].value)">

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

        </select></td>
    </tr>

    <tr id="rDate0"><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

    <tr id="rDate1">
        <td align="right" class="chatlist" valign="top" style="padding-top:17px"><?php echo $BLM['repeat_till'] ?>:&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">

            <tr>
                <td class="chatlist" style="padding-bottom:2px"><?php echo $BLM['date_format'] ?></td>
                <td class="chatlist" colspan="2">&nbsp;</td>
                <td class="chatlist" colspan="2" style="padding-bottom:2px"><?php echo $BLM['date_format'] ?></td>
            </tr>

            <tr>
                <td><input name="calendar_range_start" type="text" id="calendar_range_start" class="v12" style="width:100px;" value="<?php echo html($plugin['data']['calendar_rangestart']) ?>" size="30" /></td>
                <td><script type="text/javascript">
                    function rStart(date, month, year) {
                        getFieldById('calendar_range_start').value = subrstr('00' + date, 2) + '<?php echo $BLM['date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BLM['date_delimiter'] ?>' + year;
                    }
                    rangeStart = new dynCalendar('rangeStart', 'rStart', 'img/dynCal/');
                    rangeStart.setMonthCombo(false);
                    rangeStart.setYearCombo(false);
                    </script>&nbsp;</td>
                <td class="chatlist">&nbsp;<?php echo $BLM['till'] ?>:&nbsp;</td>
                <td><input name="calendar_range_end" type="text" id="calendar_range_end" class="v12" style="width:100px;" value="<?php echo html($plugin['data']['calendar_rangeend']) ?>" size="30" /></td>
                <td><script type="text/javascript">
                    function rEnd(date, month, year) {
                        getFieldById('calendar_range_end').value = subrstr('00' + date, 2) + '<?php echo $BLM['date_delimiter'] ?>' + subrstr('00' + month, 2) + '<?php echo $BLM['date_delimiter'] ?>' + year;
                    }
                    rangeEnd = new dynCalendar('rangeEnd', 'rEnd', 'img/dynCal/');
                    rangeEnd.setMonthCombo(false);
                    rangeEnd.setYearCombo(false);
                    </script>&nbsp;</td>

            </tr>

        </table></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['where'] ?>:&nbsp;</td>
        <td><input name="calendar_where" type="text" id="calendar_where" class="v12" style="width:375px;" value="<?php echo html($plugin['data']['calendar_where']) ?>" size="30" maxlength="220" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['calendar_token'] ?>:&nbsp;</td>
        <td><input name="calendar_tag" type="text" id="calendar_tag" class="v12" style="width:375px;" value="<?php echo html(trim($plugin['data']['calendar_tag'])) ?>" size="30" maxlength="255" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_profile_label_lang'] ?>:&nbsp;</td>
        <td><input name="calendar_lang" type="text" id="calendar_lang" class="v12" style="width:100px;" value="<?php echo html(trim($plugin['data']['calendar_lang'])) ?>" size="30" maxlength="50" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['article_link'] ?>:&nbsp;</td>
        <td class="chatlist"><?php echo $BLM['more_info'] ?></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><input name="calendar_refid" type="text" id="calendar_refid" class="v12" style="width:375px;margin-top:3px;" value="<?php echo empty($plugin['data']['calendar_refid']) ? '' : html($plugin['data']['calendar_refid']) ?>" size="30" maxlength="500" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

    <tr>
        <td align="right" class="chatlist tdtop4"><?php echo $BLM['calendar_teasertext'] ?>:&nbsp;</td>
        <td><textarea name="calendar_teaser" id="calendar_teaser" class="width375" rows="5"><?php echo html($plugin['data']['calendar_teaser']) ?></textarea></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>

    <tr>
        <td class="chatlist" colspan="2" style="padding-bottom:4px"><?php echo $BLM['calendar_text'] ?>:&nbsp;</td>
    </tr>

    <tr>
        <td colspan="2" align="center"><?php

        $wysiwyg_editor = array(
            'value'     => $plugin['data']['calendar_text'],
            'field'     => 'calendar_text',
            'height'    => '400px',
            'width'     => '100%',
            'rows'      => '15',
            'editor'    => $_SESSION["WYSIWYG_EDITOR"],
            'lang'      => 'en'
        );

        include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

        ?></td>
    </tr>

    <tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>


    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
        <td>
        <table cellpadding="0" cellspacing="0" border="0" summary="">
            <tr>
                <td><input type="text" name="cnt_image_name" id="cnt_image_name" value="<?php echo html($plugin['data']['calendar_image']['name']) ?>" class="v12 width300" maxlength="250" /></td>
                <td style="padding:2px 0 0 5px" width="100">
                    <a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=7');return false;"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a>
                    <a href="#" title="<?php echo $BL['be_cnt_delimage'] ?>" onclick="setImgIdName();return false;"><img src="img/button/del_image_button.gif" alt="" width="15" height="15" border="0" /></a>
                    <input name="cnt_image_id" id="cnt_image_id" type="hidden" value="<?php echo $plugin['data']['calendar_image']['id'] ?>" />
                </td>
            </tr>
        </table>
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
        <td class="tdtop5 tdbottom5">
        <table border="0" cellpadding="0" cellspacing="0" summary="">
        <tr>
      <td><input name="cnt_image_zoom" type="checkbox" id="cnt_image_zoom" value="1" <?php is_checked(1, $plugin['data']['calendar_image']['zoom']); ?> /></td>
          <td><label for="cnt_image_zoom" class="checkbox"><?php echo $BL['be_cnt_enlarge'] ?></label></td>

          <td><input name="cnt_image_lightbox" type="checkbox" id="cnt_image_lightbox" value="1" <?php is_checked(1, $plugin['data']['calendar_image']['lightbox']); ?> onchange="if(this.checked){getObjectById('cnt_image_zoom').checked=true;}" /></td>
          <td><label for="cnt_image_lightbox" class="checkbox"><?php echo $BL['be_cnt_lightbox'] ?></label></td>
        </tr>
        </table>

        <div id="cnt_image" style="padding-top:3px;"></div>

        </td>
    </tr>


    <tr>
        <td align="right" class="chatlist tdtop4"><?php echo $BL['be_cnt_caption'] ?>:&nbsp;</td>
        <td class="tdbottom4">
        <textarea name="cnt_image_caption" id="cnt_image_caption" class="width350" rows="2"><?php echo html($plugin['data']['calendar_image']['caption']) ?></textarea>
        </td>
    </tr>


    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_profile_label_website'] ?>:&nbsp;</td>
        <td><input type="text" name="cnt_image_link" id="cnt_image_link" class="v12 width350" maxlength="500" value="<?php echo html($plugin['data']['calendar_image']['link']) ?>" /></td>
    </tr>

    <tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>



    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>
                <td><input type="checkbox" name="calendar_status" id="calendar_status" value="1"<?php is_checked($plugin['data']['calendar_status'], 1) ?> /></td>
                <td><label for="calendar_status"><?php echo $BL['be_cnt_activated'] ?></label></td>
            </tr>
        </table></td>
    </tr>
    <tr>
        <td align="right" class="chatlist">&nbsp;</td>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>
                <td><input type="checkbox" name="calendar_duplicate" id="calendar_duplicate" value="1"<?php is_checked(empty($plugin['data']['calendar_duplicate'])?0:1, 1) ?> /></td>
                <td><label for="calendar_duplicate"><?php echo $BLM['save_copy'] ?></label></td>
            </tr>
        </table></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>
            <input name="submit" type="submit" class="button" value="<?php echo empty($plugin['data']['calendar_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
            <input name="save" type="submit" class="button" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input name="new" type="button" class="button" value="<?php echo ucfirst($BL['be_msg_new']) ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>&edit=0';return false;" />
            <input name="close" type="button" class="button" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='<?php echo decode_entities(MODULE_HREF) ?>';return false;" />
        </td>
    </tr>

</table>
</form>
<script type="text/javascript">
window.addEvent('domready', function(){

    /* Autocompleter for categories/tags */
    var searchCategory = $('calendar_tag');
    var indicator2 = new Element('span', {'class': 'autocompleter-loading', 'styles': {'display': 'none'}}).setHTML('').injectAfter(searchCategory);
    var completer2 = new Autocompleter.Ajax.Json(searchCategory, 'include/inc_act/ajax_connector.php', {
        multi: true,
        maxChoices: 30,
        autotrim: true,
        minLength: 0,
        allowDupes: false,
        postData: {action: 'category', method: 'json'},
        onRequest: function(el) {
            indicator2.setStyle('display', '');
        },
        onComplete: function(el) {
            indicator2.setStyle('display', 'none');
        }
    });

    var selectLang = $('calendar_lang');
    var indicator1 = new Element('span', {'class': 'autocompleter-loading', 'styles': {'display': 'none'}}).setHTML('').injectAfter(selectLang);
    var completer1 = new Autocompleter.Ajax.Json(selectLang, 'include/inc_act/ajax_connector.php', {
        multi: false,
        allowDupes: false,
        autotrim: true,
        minLength: 0,
        maxChoices: 20,
        postData: {action: 'lang', method: 'json'},
        onRequest: function(el) {
            indicator1.setStyle('display', '');
        },
        onComplete: function(el) {
            indicator1.setStyle('display', 'none');
        }
    });

    selectLang.addEvent('keyup', function(){
        this.value = this.value.replace(/[^a-z\-]/g, '');
    });

    setCalendarAllDay();
    setRangeDates(<?php echo $plugin['data']['calendar_range'] ?>);

    $('calendar_form').addEvent('submit', function(r) {
        var calendar_title = $('calendar_title');
        calendar_title.value = calendar_title.value.clean();
        if( calendar_title.value === '' ) {
            var r = new Event(r).stop();
            alert('<?php echo $BLM['alert_empty_title'] ?>');
        }
    });


    showImage();
});

function setCalendarAllDay() {

    var calendarAllDay = $('calendar_allday');
    if(calendarAllDay.checked) {
        toggleDisplayById('endDate0', 'none');
        toggleDisplayById('endDate1', 'none');
        toggleDisplayById('endDate2', 'none');
        toggleDisplayById('endDate3', 'none');
        toggleDisplayById('endDate4', 'none');
        toggleDisplayById('endDate5', 'none');
    } else {
        toggleDisplayById('endDate0', '');
        toggleDisplayById('endDate1', '');
        toggleDisplayById('endDate2', '');
        toggleDisplayById('endDate3', '');
        toggleDisplayById('endDate4', '');
        toggleDisplayById('endDate5', '');
    }

}

function setRangeDates(value) {

    value = parseInt(value,10);
    if(!value) {
        toggleDisplayById('rDate0', 'none');
        toggleDisplayById('rDate1', 'none');
    } else {
        toggleDisplayById('rDate0', '');
        toggleDisplayById('rDate1', '');
    }

}

function setImgIdName(file_id, file_name) {
    if(file_id == null) {
        file_id=0;
    }
    if(file_name == null) {
        file_name='';
    }
    getObjectById('cnt_image_id').value = file_id;
    getObjectById('cnt_image_name').value = file_name;

    showImage();
}

function showImage() {
    var id  = parseInt(getObjectById('cnt_image_id').value,10);
    var img = getObjectById('cnt_image');
    if(id > 0) {
        img.innerHTML = '<img src="<?php echo PHPWCMS_URL.PHPWCMS_RESIZE_IMAGE.'/'.$phpwcms['img_list_width'].'x'.$phpwcms['img_list_height'] ?>/'+id+'" alt="" border="0" />';
        img.style.display = '';
    } else {
        img.style.display = 'none';
    }
}

</script>