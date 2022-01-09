<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.org>
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


$BE['HEADER']['optionselect.js'] = getJavaScriptSourceLink('include/inc_js/optionselect.js');

if(!isset($plugin['data']['shopprod_duplicate'])) {
    $plugin['data']['shopprod_duplicate'] = 0;
}
if(!isset($plugin['data']['shopprod_overwrite_meta'])) {
    $plugin['data']['shopprod_overwrite_meta'] = 1;
}

?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['prod_edit'] ?></h1>

<form action="<?php

    echo shop_url( array('controller=prod', 'edit='.$plugin['data']['shopprod_id']) )

?>" method="post" class="editform" onsubmit="selectAllOptions(this.shopprod_images);selectAllOptions(this.shopprod_files);">

<input type="hidden" name="shopprod_id" value="<?php echo $plugin['data']['shopprod_id'] ?>" /><?php if(SHOP_FELANG_SUPPORT === false): ?><input type="hidden" name="shopprod_lang" value="<?php echo $plugin['data']['shopprod_lang'] ?>" /><?php endif; ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">

    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_cnt_last_edited']  ?>:&nbsp;</td>
        <td class="v10" width="410"><?php

        echo html_specialchars(date($BL['be_fprivedit_dateformat'], $plugin['data']['shopprod_changedate'])) ;

        if(!empty($plugin['data']['shopprod_createdate'])) {
        ?>
        &nbsp;&nbsp;&nbsp;<span class="chatlist"><?php echo $BL['be_fprivedit_created']  ?>:</span>
        <?php

            echo html_specialchars(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['shopprod_createdate'])));
        }

        ?></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['shopprod_ordernumber'] ?>:&nbsp;</td>
        <td><table cellpadding="0" cellspacing="0" border="0" summary="">

            <tr>
                <td><input name="shopprod_ordernumber" type="text" id="shopprod_ordernumber" class="v12 width125 bold<?php

        //error class
        if(!empty($plugin['error']['shopprod_ordernumber'])) echo ' errorInputText';

        ?>" value="<?php echo html_specialchars($plugin['data']['shopprod_ordernumber']) ?>" size="30" maxlength="20" /></td>

                <td align="right" class="chatlist width100"><?php echo $BLM['shopprod_model'] ?>:&nbsp;</td>
                <td><input name="shopprod_model" type="text" id="shopprod_model" class="v12 width170" value="<?php echo html_specialchars($plugin['data']['shopprod_model']) ?>" size="30" maxlength="200" /></td>
            </tr>

            </table></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

<?php if(SHOP_FELANG_SUPPORT): ?>

    <tr>
        <td align="right" class="chatlist"><?php echo $BL['be_profile_label_lang']  ?>:&nbsp;</td>
        <td class="v10" width="410"><table cellpadding="0" cellspacing="0" border="0" summary="">
            <tr>
                <td><input type="radio" name="shopprod_lang" id="shopprod_lang_0" value=""<?php is_checked($plugin['data']['shopprod_lang'], '') ?> /></td>
                <td><label for="shopprod_lang_0"><?php echo $BL['be_ftptakeover_all'] ?></label>&nbsp;&nbsp;</td>
<?php   foreach($phpwcms['allowed_lang'] as $lang):

            $lang = strtolower($lang);
?>
        <td><input type="radio" name="shopprod_lang" id="shopprod_lang_<?php echo $lang ?>" value="<?php echo $lang ?>"<?php is_checked(strtolower($plugin['data']['shopprod_lang']), $lang) ?> /></td>
        <td><label for="shopprod_lang_<?php echo $lang ?>"><img src="img/famfamfam/lang/<?php echo $lang; $lang = strtoupper($lang); ?>.png" alt="<?php echo $lang ?>" title="<?php echo $lang ?>" /></label>&nbsp;&nbsp;</td>

<?php   endforeach; ?>
            </tr>
        </table></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

<?php endif; ?>


    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['shopprod_name1'] ?>:&nbsp;</td>
        <td><input name="shopprod_name1" type="text" id="shopprod_name1" class="v12 width400 bold<?php

        //error class
        if(!empty($plugin['error']['shopprod_name1'])) echo ' errorInputText';

        ?>" value="<?php echo html_specialchars($plugin['data']['shopprod_name1']) ?>" size="30" maxlength="200" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['shopprod_name2'] ?>:&nbsp;</td>
        <td><input name="shopprod_name2" type="text" id="shopprod_name2" class="v12 width400" value="<?php echo html_specialchars($plugin['data']['shopprod_name2']) ?>" size="30" maxlength="200" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['shopprod_weight'] ?>:&nbsp;</td>
        <td><table cellpadding="0" cellspacing="0" border="0" summary="">

            <tr>

                <td><input name="shopprod_weight" type="text" id="shopprod_weight" class="v12 width125 right" value="<?php

                    echo number_format($plugin['data']['shopprod_weight'], 3, $BLM['dec_point'], $BLM['thousands_sep']);

                ?>" size="30" maxlength="200" /></td>

                <td class="chatlist" width="25">&nbsp;<?php

                    if( ! ($plugin['data']['shop_pref_unit_weight'] = _getConfig('shop_pref_unit_weight')) ) {
                        $plugin['data']['shop_pref_unit_weight'] = 'kg';
                        _setConfig('shop_pref_unit_weight',     $plugin['data']['shop_pref_unit_weight'],   'module_shop');
                    }
                    echo html_specialchars($plugin['data']['shop_pref_unit_weight']);

                ?></td>

            </tr>
            </table></td>

    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['shopprod_inventory'] ?>:&nbsp;</td>
        <td><table cellpadding="0" cellspacing="0" border="0" summary="">
                <tr>
                    <td><input name="shopprod_inventory" type="text" id="shopprod_inventory" class="v12 width125 right" value="<?php echo $plugin['data']['shopprod_inventory'] ?>" size="30" maxlength="11" /></td>
                    <td align="right" class="chatlist width100"><?php echo $BLM['shopprod_unit'] ?>:&nbsp;</td>
                    <td><input name="shopprod_unit" type="text" id="shopprod_unit" class="v12 width170" value="<?php echo html($plugin['data']['shopprod_unit']) ?>" size="30" maxlength="100" /></td>
                </tr>
        </table></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['shopprod_price'] ?>:&nbsp;</td>
        <td><table cellpadding="0" cellspacing="0" border="0" summary="">

            <tr>

                <td><input name="shopprod_price" type="text" id="shopprod_price" class="v12 width125 bold right<?php

            if(!empty($plugin['error']['shopprod_price'])) echo ' errorInputText';

            ?>" value="<?php

            $dec_lenght = strlen(strrchr($plugin['data']['shopprod_price'],'.')) - 1;
            if($dec_lenght < 2) $dec_lenght = 2;
            echo number_format($plugin['data']['shopprod_price'], $dec_lenght, $BLM['dec_point'], $BLM['thousands_sep']);

        ?>" size="30" maxlength="200" /></td>

            <td>&nbsp;</td>
            <td width="25" align="right"><input type="checkbox" name="shopprod_netgross" id="shopprod_netgross" value="1"<?php is_checked(1, $plugin['data']['shopprod_netgross']) ?>  title="<?php echo $BLM['shopprod_netgross_info'] ?>" /></td>
            <td width="75"><label for="shopprod_netgross" title="<?php echo $BLM['shopprod_netgross_info'] ?>"><?php echo $BLM['shopprod_netgross'] ?></label>&nbsp;&nbsp;&nbsp;</td>

            <td class="chatlist"><?php echo $BLM['shopprod_vat'] ?>:&nbsp;</td>
            <td><select name="shopprod_vat" id="shopprod_id" class="v12">

    <?php

    if( ! $plugin['data']['shop_pref_vat'] = _getConfig('shop_pref_vat') ) {
        $plugin['data']['shop_pref_vat'] = array('0.00');
        _setConfig('shop_pref_vat',     $plugin['data']['shop_pref_vat'],   'module_shop');
    }

    $add_option = '';
    $add_vat    = array();

    foreach( $plugin['data']['shop_pref_vat'] as $value ) {
        echo '<option value="'.$value.'"';
        if($plugin['data']['shopprod_vat'] == $value) {
            echo ' selected="selected"';
        } elseif( ! empty($plugin['data']['shopprod_vat']) && ! in_array($plugin['data']['shopprod_vat'], $plugin['data']['shop_pref_vat']) ) {
            $plugin['data']['shop_pref_vat'][] = $plugin['data']['shopprod_vat'];
            natsort($plugin['data']['shop_pref_vat']);
            _setConfig('shop_pref_vat',     $plugin['data']['shop_pref_vat'],   'module_shop');

            $add_option .= LF . '<option value="'.$plugin['data']['shopprod_vat'].'" selected="selected">';
            $add_option .= number_format($plugin['data']['shopprod_vat'], 2, $BLM['dec_point'], $BLM['thousands_sep']);
            $add_option .= '</option>';
        }
        echo '>';
        echo number_format($value, 2, $BLM['dec_point'], $BLM['thousands_sep']);
        echo '</option>' . LF;
    }
    echo $add_option;

    ?>

            </select></td>

            <td>&nbsp;%</td>


        </tr>

            </table></td>

    </tr>


    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

    <tr>
        <td align="right" class="chatlist tdtop4"><?php echo $BLM['shopprod_size'] ?>:&nbsp;</td>
        <td><table cellpadding="0" cellspacing="0" border="0" summary="">

            <tr>
                <td><textarea name="shopprod_size" id="shopprod_size" class="v12 width125" rows="5" cols="15"><?php echo html_specialchars($plugin['data']['shopprod_size']) ?></textarea></td>

                <td align="right" class="chatlist width100 tdtop4"><?php echo $BLM['shopprod_color'] ?>:&nbsp;</td>
                <td><textarea name="shopprod_color" id="shopprod_color" class="v12 width170" rows="5" cols="15"><?php echo html_specialchars($plugin['data']['shopprod_color']) ?></textarea></td>

                </tr>

            </table></td>
    </tr>

<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>


    <tr>
        <td align="right" class="chatlist tdbottom3"><?php echo $BLM['shopprod_description0'] ?>:&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="tdbottom4"><?php

        $wysiwyg_editor = array(
            'value'     => $plugin['data']['shopprod_description0'],
            'field'     => 'shopprod_description0',
            'height'    => '150px',
            'width'     => '100%',
            'rows'      => '10',
            'editor'    => $_SESSION["WYSIWYG_EDITOR"],
            'lang'      => 'en'
        );

        include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

        ?></td>
    </tr>

    <tr>
        <td align="right" class="chatlist tdbottom3"><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="tdbottom4"><?php

        $wysiwyg_editor = array(
            'value'     => $plugin['data']['shopprod_description1'],
            'field'     => 'shopprod_description1',
            'height'    => '250px',
            'width'     => '100%',
            'rows'      => '10',
            'editor'    => $_SESSION["WYSIWYG_EDITOR"],
            'lang'      => 'en'
        );

        include PHPWCMS_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

        ?></td>
    </tr>

    <tr>
        <td align="right" class="chatlist tdtop4"><?php echo $BLM['shopprod_description1'] ?>:&nbsp;</td>
        <td colspan="2" class="tdbottom3"><textarea name="shopprod_description2" id="shopprod_description2" rows="5" class="v12 width400"><?php echo html_specialchars($plugin['data']['shopprod_description2']) ?></textarea></td>
    </tr>

    <tr>
        <td align="right" class="chatlist tdtop4"><?php echo $BLM['shopprod_description2'] ?>:&nbsp;</td>
        <td colspan="2"><textarea name="shopprod_description3" id="shopprod_description3" rows="5" class="v12 width400"><?php echo html_specialchars($plugin['data']['shopprod_description3']) ?></textarea></td>
    </tr>


<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<tr>
    <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_cnt_image'] ?>:&nbsp;</td>
    <td valign="top" class="tdbottom3"><table border="0" cellpadding="0" cellspacing="0" summary="">
    <tr>
        <td valign="top"><select name="shopprod_images[]" size="<?php

    $img_count = isset($plugin['data']['shopprod_images']) && is_array($plugin['data']['shopprod_images']) ? count($plugin['data']['shopprod_images']) : 0;

    echo $img_count+5

        ?>" multiple="multiple" class="f11 width360" id="shopprod_images">
<?php

$img_thumbs = '';
$imgx = 0;

if($img_count) {

    // browse images and list available
    // will be visible only when aceessible
    foreach($plugin['data']['shopprod_images'] as $key => $value) {

        // 0   :1       :2   :3        :4    :5     :6      :7       :8
        // dbid:filename:hash:extension:width:height:caption:position:zoom
        $thumb_image = get_cached_image(array(
            "target_ext"    =>  $plugin['data']['shopprod_images'][$key]['f_ext'],
            "image_name"    =>  $plugin['data']['shopprod_images'][$key]['f_hash'] . '.' . $plugin['data']['shopprod_images'][$key]['f_ext'],
            "thumb_name"    =>  md5($plugin['data']['shopprod_images'][$key]['f_hash'].$phpwcms["img_list_width"].$phpwcms["img_list_height"].$phpwcms["sharpen_level"].$phpwcms['colorspace'])
        ));

        if($thumb_image != false) {

            // image found
            echo '<option value="' . $plugin['data']['shopprod_images'][$key]['f_id'] . '">';
            $img_name = html_specialchars($plugin['data']['shopprod_images'][$key]['f_name']);
            echo $img_name . '</option>'.LF;

            if($imgx == 4) {
                $img_thumbs .= '<br /><img src="img/leer.gif" alt="" width="1" height="2" /><br />';
                $imgx = 0;
            }
            if($imgx) {
                $img_thumbs .= '<img src="img/leer.gif" alt="" width="2" height="1" />';
            }
            $img_thumbs .= '<img src="' . $thumb_image['src'] .'" '.$thumb_image[3].' alt="'.$img_name.'" title="'.$img_name.'" />';

            $plugin['data']['shopprod_caption'][] = html_specialchars($plugin['data']['shopprod_images'][$key]['caption']);

            $imgx++;
        }

    }

}

?>
          </select></td>
                  <td valign="top"><img src="img/leer.gif" alt="" width="5" height="1" /></td>
                  <td valign="top">
                  <a href="#" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=5&amp;target=nolist');return false;"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a>
                  <br /><img src="img/leer.gif" alt="" width="1" height="4" /><br /><a href="#" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(img_field);return false;"><img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0" /></a><a href="#" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(img_field);return false;"><img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0" /></a>
                  <br /><img src="img/leer.gif" alt="" width="1" height="4" /><br /><a href="#" onclick="removeSelectedOptions(img_field);return false;" title="<?php echo $BL['be_cnt_delimage'] ?>"><img src="img/button/del_image_button1.gif" alt="" width="20" height="15" border="0" /></a></td>
      </tr>
              </table>
<?php

    if($img_thumbs) {
        echo '
        <table border="0" cellspacing="0" cellpadding="0" summary="">
        <tr>
                <td style="padding:5px 0 5px 0;">'.$img_thumbs.'</td>
            </tr>
        </table>';
    }

?></td>
    </tr>

    <tr>
        <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_cnt_caption'] ?>:&nbsp;</td>
        <td valign="top"><textarea name="shopprod_caption" cols="40" rows="<?php echo $img_count+5 ?>" wrap="off" class="f11 width400" id="shopprod_caption"><?php echo implode(' '.LF, $plugin['data']['shopprod_caption']) ?></textarea></td>
    </tr>

    <tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>


    <!-- Attachments -->
    <tr>
    <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_cnt_files'] ?>:&nbsp;</td>
    <td valign="top" class="tdbottom3"><table border="0" cellpadding="0" cellspacing="0" summary="">
    <tr>
        <td valign="top"><select name="shopprod_files[]" size="<?php

    $files_count = isset($plugin['data']['shopprod_files']) && is_array($plugin['data']['shopprod_files']) ? count($plugin['data']['shopprod_files']) : 0;

    echo $files_count+4

        ?>" multiple="multiple" class="f11 width360" id="shopprod_files">
<?php

if(count($plugin['data']['shopprod_files'])) {

    // browse images and list available
    // will be visible only when aceessible
    foreach($plugin['data']['shopprod_files'] as $key => $value) {

        echo '<option value="' . $plugin['data']['shopprod_files'][$key]['f_id'] . '">';
        echo html_specialchars($plugin['data']['shopprod_files'][$key]['f_name']);
        echo '</option>'.LF;

        $plugin['data']['shopprod_filecaption'][] = html_specialchars($plugin['data']['shopprod_files'][$key]['caption']);

    }

}

?>
        </select></td>

        <td valign="top"><img src="img/leer.gif" alt="" width="5" height="1" /></td>
        <td valign="top">
        <a href="#" title="<?php echo $BL['be_cnt_openfilebrowser'] ?>" onclick="openFileBrowser('filebrowser.php?opt=9&amp;target=nolist');return false;"><img src="img/button/open_image_button.gif" alt="" width="20" height="15" border="0" /></a>
        <br /><img src="img/leer.gif" alt="" width="1" height="4" /><br /><a href="#" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(files_field);return false;"><img src="img/button/image_pos_up.gif" alt="" width="10" height="9" border="0" /></a><a href="#" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(files_field);return false;"><img src="img/button/image_pos_down.gif" alt="" width="10" height="9" border="0" /></a>
        <br /><img src="img/leer.gif" alt="" width="1" height="4" /><br /><a href="#" onclick="removeSelectedOptions(files_field);return false;" title="<?php echo $BL['be_cnt_delfile'] ?>"><img src="img/button/del_image_button1.gif" alt="" width="20" height="15" border="0" /></a></td>
      </tr>
    </table>
        </td>
    </tr>

    <tr>
        <td align="right" valign="top" class="chatlist tdtop4"><?php echo $BL['be_cnt_description'] ?>:&nbsp;</td>
        <td valign="top"><textarea name="shopprod_filecaption" cols="40" rows="<?php echo $img_count+5 ?>" wrap="off" class="f11 width400" id="shopprod_filecaption"><?php echo implode(' '.LF, $plugin['data']['shopprod_filecaption']) ?></textarea></td>
    </tr>
    <!-- End Attachments -->

    <tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['shopprod_url'] ?>:&nbsp;</td>
        <td><input name="shopprod_url" type="text" id="shopprod_url" class="v12 width400" value="<?php echo html_specialchars($plugin['data']['shopprod_url']) ?>" size="30" maxlength="250" /></td>
    </tr>

    <tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

    <tr>
        <td align="right" class="chatlist tdtop3"><?php echo $BLM['prod_cat'] ?>:&nbsp;</td>
        <td><table cellpadding="0" cellspacing="0" border="0" summary="">

            <tr>
                <td><select name="shopprod_category[]" size="7" multiple="multiple" class="v12 width400" id="shopprod_category">
        <?php
        $t = array();
        foreach($plugin['data']['categories'] as $value) {

            echo '<option value="'.$value['cat_id'].'"';
            if(in_array($value['cat_id'], $plugin['data']['shopprod_category'])) {
                echo ' selected="selected"';
                $t[] = $value['category'];
            }
            if($value['cat_status'] == 0) {
                echo ' style="font-style:italic;"';
            }
            echo '>';
            if($value['cat_pid']) {
                echo '&nbsp;&nbsp;&nbsp;';
            }
            echo html_specialchars($value['cat_name']).'</option>'.LF;

        }

        ?>
            </select></td>
            </tr>
        <?php   if(count($t)) {     ?>
            <tr>
                <td class="tdtop3 v10"><?php echo nl2br(html_specialchars(implode(', ', $t))) ?></td>
            </tr>
        <?php   }   ?>

        </table></td>

    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['shopprod_tag'] ?>:&nbsp;</td>
        <td><input name="shopprod_tag" type="text" id="shopprod_tag" class="v12 width400" value="<?php echo html_specialchars( trim($plugin['data']['shopprod_tag'], ',') ) ?>" size="30" maxlength="250" /></td>
    </tr>

    <tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['shopprod_on_request'] ?>:&nbsp;</td>
        <td class="tdtop1">
            <div>
                <label>
                    <input type="checkbox" name="shopprod_on_request" id="shopprod_on_request" value="1"<?php is_checked($plugin['data']['shopprod_on_request'], 1) ?> />
                    <strong><?php echo $BL['be_cnt_activated'] ?></strong>
                </label>
            </div>
        </td>
    </tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
    <tr>
        <td align="right" class="chatlist"><?php echo $BLM['shopprod_on_request_button'] ?>:&nbsp;</td>
        <td><input name="shopprod_on_request_url" type="text" id="shopprod_on_request_url" class="v12 width400" value="<?php echo html($plugin['data']['shopprod_on_request_url']) ?>" size="30" maxlength="250" title="<?php echo $BLM['shopprod_on_request_url'] ?>" placeholder="<?php echo $BLM['shopprod_on_request_url'] ?>" /></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
    <tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

    <tr>
        <td align="right" class="chatlist tdtop4"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
        <td class="tdtop1">
            <div>
                <label>
                    <input type="checkbox" name="shopprod_status" id="shopprod_status" value="1"<?php is_checked($plugin['data']['shopprod_status'], 1) ?> />
                    <strong><?php echo $BL['be_cnt_activated'] ?></strong>
                </label>
                &nbsp;
                <label>
                    <input type="checkbox" name="shopprod_listall" id="shopprod_listall" value="1"<?php is_checked($plugin['data']['shopprod_listall'], 1) ?> />
                    <?php echo $BLM['shopprod_listall'] ?>
                </label>
            </div>
            <div>
                <label>
                    <input type="checkbox" name="shopprod_overwrite_meta" id="shopprod_overwrite_meta" value="1"<?php is_checked($plugin['data']['shopprod_overwrite_meta'], 1) ?> />
                    <?php echo $BLM['shopprod_overwrite_meta'] ?>
                </label>
            </div>
            <div>
                <label>
                    <input type="checkbox" name="shopprod_opengraph" id="shopprod_opengraph" value="1"<?php is_checked($plugin['data']['shopprod_opengraph'], 1) ?> />
                    <?php echo $BL['be_opengraph_support'] ?>
                </label>
            </div>

<!-- save as duplicate -->
<?php   if($plugin['data']['shopprod_id']): ?>
            <div>
                <label>
                    <input type="checkbox" name="shopprod_duplicate" id="shopprod_duplicate" value="1"<?php is_checked($plugin['data']['shopprod_duplicate'], 1) ?> />
                    <?php echo $BL['be_save_copy'] ?>
                </label>
            </div>
<?php   endif;  ?>

        </td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>
    <tr>
        <td>&nbsp;</td>
        <td>
            <input name="submit" type="submit" class="button10" value="<?php echo empty($plugin['data']['shopprod_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
            <input name="save" type="submit" class="button10" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input name="close" type="submit" class="button10" value="<?php echo $BL['be_admin_struct_close'] ?>" />
        </td>
    </tr>
</table>
</form>
<script type="text/javascript">

var img_field = getObjectById('shopprod_images');
var files_field = getObjectById('shopprod_files');
function addFile(value,text) {
    if(files_field!=null && files_field.options!=null) {
        newOpt = new Option(text, value);
        files_field.options.length++;
        files_field.options[files_field.length-1].text  = newOpt.text;
        files_field.options[files_field.length-1].value = newOpt.value;
        files_field.options[files_field.length-1].selected = false;
    }
}

</script>