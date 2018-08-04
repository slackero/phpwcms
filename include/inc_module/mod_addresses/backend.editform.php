<?php
// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Init Google Maps
$GLOBALS['BE']['HEADER']['optionselect.js'] = getJavaScriptSourceLink('include/inc_js/optionselect.js');
$GLOBALS['BE']['HEADER']['google_maps']     = ' <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.MAPS_API_KEY.'" type="text/javascript"></script>';
$GLOBALS['BE']['HEADER']['multicheck.css']  = ' <style type="text/css">
    ul.multicheck {
        height: 21em;
        overflow: auto;
        list-style: none;
        padding: 3px 0 3px 2px;
        width: 402px;
        margin: 0 0 3px 0;
        background: #ffffff;
        border-top: 1px solid #877F80;
        border-left: 1px solid #BCBABB;
        border-bottom: 1px solid #E1DEDF;
        border-right: 1px solid #BCBABB;
    }
    ul.multicheck li {
        font-size: 12px;
    }
    h1.address-title {
        margin-bottom: 10px;
        padding-left: 21px;
        background: url('.MODULE_BASEPATH.'template/image/vcard.gif) no-repeat left center;
    }
    form.cmsgo-address {
        background: #F3F5F8;
        border-top: 1px solid #92A1AF;
        border-bottom: 1px solid #92A1AF;
        margin: 0 0 5px 0;
        padding: 10px 8px 15px 8px;
    }
    .address-link-action {
        font-weight: bold;
        margin-left: 5px;
        padding-left: 18px;
        background: url('.MODULE_BASEPATH.'template/image/magnifier.gif) no-repeat left center;
        line-height: 16px;
        height: 16px;
        display: inline-block;
    }
    .address-link-action.get-coordinates {
        background-image: url('.MODULE_BASEPATH.'template/image/world_link.gif);
    }
    a.click-icon-link {
        display: inline-block;
        margin-bottom: 4px;
    }
    span.caption {
        display: block;
        padding-bottom: 7px;
    }
  </style>';
?>
<h1 class="title address-title"><?php echo $BLM['listing_title'] ?></h1>
<form action="<?php echo MODULE_HREF ?>&amp;edit=<?php echo $plugin['data']['detail_id'] ?>" method="post" id="address_form" class="cmsgo-address" name="articlecontent" onsubmit="selectAllOptions(this.cfile_list);">
<input type="hidden" name="detail_id" value="<?php echo $plugin['data']['detail_id'] ?>" />
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">
    <?php   if(isset($plugin['error'])):    ?>
    <tr>
        <td>&nbsp;</td>
        <td class="v12 error"><?php echo implode('<br />', $plugin['error']) ;?></td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
    <?php   endif;  ?>
    <tr>
        <td>&nbsp;</td>
        <td class="v12"><?php echo $BLM['forminfo'] ?></td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
<?php

foreach($plugin['fields'] as $key => $value) {

    switch($value) {

        case 'STRING':
            echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>';
            echo '<tr>';
            echo '<td align="right" class="chatlist">'.$BLM[$key].':&nbsp;</td>';
            echo '<td><input name="'.$key.'" type="text" id="'.$key.'" class="v12 width400;" value="'.html_specialchars($plugin['data'][$key]).'" size="30" maxlength="200" /></td>';
            echo '</tr>';
            break;

        case 'TEXTAREA':
            echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>';
            echo '<tr>';
            echo '<td align="right" class="chatlist tdtop4">'.$BLM[$key].':&nbsp;</td>';
            echo '<td><textarea name="'.$key.'" id="'.$key.'" class="v12 width400" rows="4">'.html_specialchars($plugin['data'][$key]).'</textarea></td>';
            echo '</tr>';
            break;

        case 'FLOAT':
            echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>';
            echo '<tr>';
            echo '<td align="right" class="chatlist">'.$BLM[$key].':&nbsp;</td>';
            echo '<td><input name="'.$key.'" type="text" id="'.$key.'" class="v12 width150" value="'.html_specialchars($plugin['data'][$key]).'" size="30" maxlength="200" /></td>';
            echo '</tr>';
            break;

        case 'INT':
            echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>';
            echo '<tr>';
            echo '<td align="right" class="chatlist">'.$BLM[$key].':&nbsp;</td>';
            echo '<td><input name="'.$key.'" type="text" id="'.$key.'" class="v12 width150" value="'.html_specialchars($plugin['data'][$key]).'" size="30" maxlength="10" /></td>';
            echo '</tr>';
            break;

        case 'CHECK':
            echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>';
            echo '<tr>';
            echo '<td>&nbsp;</td>';
            echo '<td><table border="0" cellpadding="0" cellspacing="0" summary=""><tr><td><input type="checkbox" name="'.$key.'" id="'.$key.'" value="1"';
            is_checked($plugin['data'][$key], 1);
            echo ' /></td><td><label for="'.$key.'">'.$BLM[$key].'</label></td></tr></table></td>';
            echo '</tr>';
            break;

        case 'SELECT':
        case 'MULTISELECT':
            $plugin['select'] = array();

            if(isset( $plugin['fields_' . $key ] )) {

                if(is_array($plugin['fields_' . $key ])) {

                    $plugin['select'] = $plugin['fields_' . $key ];

                // check if the string is a valid function to retrieve field options/values
                } elseif(is_string($plugin['fields_' . $key ])) {

                    if(function_exists($plugin['fields_' . $key ])) {

                        $plugin_function = $plugin['fields_' . $key ];

                        $plugin['select'] = $plugin_function();

                    }
                    // maybe elseif here could be used to check string against imploded
                    // array members separated by "," or any other delimeter
                }
            }

            echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>';
            echo '<tr>';
            echo '<td align="right" class="chatlist tdtop4">'.$BLM[$key].':&nbsp;</td>';
            echo '<td><select id="'.$key.'" class="v12" style="min-width:75px;max-width:400px;" name="'.$key;
            echo $value == 'MULTISELECT' ? '[]" multiple="multiple" size="6"' : '"';
            echo '>';

            $_options_pre = array();
            $_options_end = array();
            $_option_remember = array();

            foreach($plugin['select'] as $item => $row) {

                $_selected = false;
                $_option  = '   <option value="' . html_specialchars($item) .'"';
                if($value == 'MULTISELECT') {
                    if( in_array($item, $plugin['data'][$key]) ) {
                        $_option .= ' selected="selected"';
                        $_option_remember[] = $row;
                        $_selected = true;
                    }
                } else {
                    if( $plugin['data'][$key] == $item ) {
                        $_option .= ' selected="selected"';
                        $_selected = true;
                    }
                }
                $_option .= '>' . html_specialchars(trim($row)) . '</option>';

                if($_selected) {
                    $_options_pre[] = $_option;
                } else {
                    $_options_end[] = $_option;
                }

            }

            echo implode(LF, $_options_pre) . implode(LF, $_options_end);

            echo '</select></td></tr>';

            if(count($_option_remember)) {
                echo '<tr>';
                echo '<td align="right">&nbsp;</td>';
                echo '<td class="tdtop3"><em>'.html_specialchars(implode(', ', $_option_remember)).'</em></td></tr>';
            }

            break;

        case 'MULTICHECK':
            $plugin['multicheck'] = array();

            if(isset( $plugin['fields_' . $key ] )) {

                if(is_array($plugin['fields_' . $key ])) {

                    $plugin['multicheck'] = $plugin['fields_' . $key ];

                // check if the string is a valid function to retrieve field options/values
                } elseif(is_string($plugin['fields_' . $key ])) {

                    if(function_exists($plugin['fields_' . $key ])) {

                        $plugin_function = $plugin['fields_' . $key ];

                        $plugin['multicheck'] = $plugin_function();

                    }
                    // maybe elseif here could be used to check string against imploded
                    // array members separated by "," or any other delimeter

                }

            }

            echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>';
            echo '<tr>';
            echo '<td align="right" class="chatlist tdtop4">'.$BLM[$key].':&nbsp;</td>';
            echo '<td><ul class="multicheck">';

            $_options_pre = array();
            $_options_end = array();

            foreach($plugin['multicheck'] as $item => $row) {

                $_selected = false;
                $_option  = '   <li><label><input type="checkbox" name="'.$key.'[]" value="' . html_specialchars($item) .'"';
                if( in_array($item, $plugin['data'][$key]) ) {
                        $_option .= ' checked="checked"';
                        $_selected = true;
                }
                $_option .= ' />' . html_specialchars(trim($row)) . '</label></li>';

                if($_selected) {
                    $_options_pre[] = $_option;
                } else {
                    $_options_end[] = $_option;
                }

            }

            echo implode(LF, $_options_pre) . implode(LF, $_options_end);
            echo '</ul></td></tr>';

            break;

        case 'FILES':
            $plugin['count_file_items'] = (isset($plugin['data'][$key]['files']) && is_array($plugin['data'][$key]['files'])) ? count($plugin['data'][$key]['files']) : 0;
            echo '<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>';
            echo '<tr>';
            echo '<td align="right" class="chatlist tdtop4">'.$BLM[$key].':&nbsp;</td>';
            echo '<td><table border="0" cellpadding="0" cellspacing="0"><tr><td style="padding-right:5px;padding-bottom:5px;">';
            echo '<select name="'.$key.'[]" size="'.max(5, 3 + $plugin['count_file_items']).'" multiple class="width375" id="cfile_list">';
            if($plugin['count_file_items']) {
                $file_sql = "SELECT f_id, f_name FROM ".DB_PREPEND.'cmsgo_file WHERE f_public=1 AND f_aktiv=1 AND f_kid=1 AND f_trash=0 AND f_id IN (' . implode(',', $plugin['data'][$key]['files']) . ')';
                $file_result = _dbQuery($file_sql);
                if(isset($file_result[0]['f_id'])) {
                    foreach($plugin['data'][$key]['files'] as $file_id) {
                        foreach($file_result as $file_row) {
                            if(intval($file_id) === intval($file_row['f_id'])) {
                                echo '<option value="'.$file_row['f_id'].'">'.html($file_row['f_name']).'</option>';
                                break;
                            }
                        }
                    }
                }
            }
            echo '</select></td>'; ?>
            <td valign="top">
                <a href="#" title="<?php echo $BL['be_cnt_openfilebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=4&amp;target=nolist');return false;" class="click-icon-link">
                    <img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0">
                </a><br><a href="#" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(document.articlecontent.cfile_list);return false;" class="click-icon-link">
                    <img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0">
                </a><a href="#" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(document.articlecontent.cfile_list);return false;" class="click-icon-link">
                    <img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0">
                </a><br><a href="#" onclick="removeSelectedOptions(document.articlecontent.cfile_list);" title="<?php echo $BL['be_cnt_delfile'] ?>" class="click-icon-link">
                    <img src="img/button/del_image_button1.gif" alt="" width="20" height="15" border="0">
                </a>
            </td><?php
            echo '</tr></table></td></tr>';
            echo '<tr>';
            echo '<td align="right" class="chatlist tdtop4">'.$BL['be_cnt_description'].':&nbsp;</td>';
            echo '<td><textarea name="'.$key.'_description" id="'.$key.'_description" class="v12 width400 autosize" rows="'.max(5, 3 + $plugin['count_file_items']).'" cols="40" >';
            if(isset($plugin['data'][$key]['descriptions']) && is_array($plugin['data'][$key]['descriptions']) && count($plugin['data'][$key]['descriptions'])) {
                foreach($plugin['data'][$key]['descriptions'] as $file_description) {
                    echo html(implode(' | ', $file_description)) . LF;
                }
            }
            echo '</textarea>'; ?>
            <span class="caption width440">
        		<?php echo $BL['be_cnt_description']; ?>
        		|
        		<?php echo $BL['be_fprivedit_filename']; ?>
        		|
        		<?php echo $BL['be_caption_file_title']; ?>
        		|
        		<?php echo $BL['be_admin_page_link']; ?> <em><?php echo $BL['be_cnt_target']; ?></em>
        		|
        		<?php echo $BL['be_caption_file_imagesize']; ?>
        		|
        		<?php echo $BL['be_copyright']; ?>&nbsp;&crarr;&nbsp;&hellip;
        	</span><?php
            echo '</td></tr>';
            break;
    }
}
?>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

    <tr>
        <td>&nbsp;</td>
        <td>
            <input name="submit" type="submit" class="button10" value="<?php echo empty($plugin['data']['detail_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
            <input name="save" type="submit" class="button10" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input name="new" type="button" class="button10" value="<?php echo ucfirst($BL['be_msg_new']) ?>" onclick="location.href='<?php echo MODULE_HREF ?>&amp;edit=0';return false;" />
            <input name="close" type="button" class="button10" value="<?php echo $BL['be_admin_struct_close'] ?>" onclick="location.href='<?php echo MODULE_HREF ?>';return false;" />
            <input type="reset" class="button10" value="<?php echo $BL['be_cnt_field']['reset'] ?>" />
        </td>
    </tr>

</table>

</form>

<script>
var geocoder,
    geocoder_inited = false;

// detail_float1 = Latitude
// detail_float2 = Longitude
document.addEventListener('DOMContentLoaded', function() {
    var searchMap = document.createElement('a');
    searchMap.innerText = '<?php echo $BLM['get_coordinates']; ?>';
    searchMap.href = '#';
    searchMap.setAttribute('class', 'address-link-action');
    searchMap.setAttribute('onclick', 'getLocation(true);return false;');

    var showMap = document.createElement('a');
    showMap.innerText = '<?php echo $BLM['show_map']; ?>';
    showMap.href = '#';
    showMap.setAttribute('class', 'address-link-action get-coordinates');
    showMap.setAttribute('onclick', 'showMap();return false;');

    var detailFloat1 = document.getElementById('detail_float1');

    detailFloat1.parentNode.insertBefore(searchMap, detailFloat1.nextSibling);
    searchMap.parentNode.insertBefore(showMap, searchMap.nextSibling);

    tryToSetLocation();
});

// Google Maps Sepcific Functions
function tryToSetLocation() {
    if(document.getElementById('detail_float1')) {
        var google_lat  = parseFloat( document.getElementById('detail_float1').value.trim(), 10 ),
            google_long = parseFloat( document.getElementById('detail_float2').value.trim(), 10 );
        if(google_lat == 0 || google_long == 0 ) {
            getLocation();
        }
    }
}

function setLatLongInformation(response) {

    // Domkloster 3, 50667 Köln, Deutschland

    if (!response || response.Status.code != 200) {

        alert(('<?php echo $BLM['proof_address_alert1']; ?>').replace('%s', getAddress()));

    } else {

        place = response.Placemark[0];

        document.getElementById('detail_float1').value = place.Point.coordinates[1];
        document.getElementById('detail_float2').value = place.Point.coordinates[0];

    }
}

function getLocation(btn) {

    if(!geocoder_inited) {
        geocoder_inited = true;
        geocoder = new GClientGeocoder();
        unloadGoogleMaps();
    }

    var address_line = getAddress();

    if( address_line == '' ) {
        if(btn != undefined || btn != null) {
            alert('<?php echo $BLM['proof_address_alert2']; ?>');
        }
        return;
    }

    geocoder.getLocations( address_line , setLatLongInformation );
}

function getAddress() {
    var address  = '';
    var street1  = document.getElementById('detail_street').value.trim();
    var street2  = document.getElementById('detail_add').value.trim();
    var zipcode  = document.getElementById('detail_zip').value.trim();
    var cityname = document.getElementById('detail_city').value.trim();

    if( street1 == '' || zipcode == '' || cityname == '' ) {
        return '';
    }

    if(street1 != '') {
        address += street1 + ', ';
    }
    if(street2 != '') {
        address += street2 + ', ';
    }
    if(zipcode != '') {
        address += zipcode + ' ';
    }
    if(cityname != '') {
        address += cityname;
    }
    address += ', ' + document.getElementById('detail_country').options[ document.getElementById('detail_country').selectedIndex ].value;

    return address;
}

function showMap() {

    if(!geocoder_inited) {
        geocoder_inited = true;
        geocoder = new GClientGeocoder();
        unloadGoogleMaps();
    }

    var lat = document.getElementById('detail_float1').value;
    var lng = document.getElementById('detail_float2').value;
    var adr = getAddress();

    if(lat != '' && lng != '' && adr != '') {
        point = new GLatLng(lat, lng);
        ssl = point.toUrlValue();

        mapurl  = 'http://maps.google.de/?hl=de&cd=1&q=';
        mapurl += encodeURI( adr );
        mapurl += '&sll=' + ssl + '&z=16&iwloc=addr&om=1';

        window.open(mapurl, 'GoogleMap');

    } else {
        alert('<?php echo $BLM['proof_address']; ?>');
    }
}

function unloadGoogleMaps() {
    document.addEventListener('unload', function() {
        GUnload();
    });
}

</script>