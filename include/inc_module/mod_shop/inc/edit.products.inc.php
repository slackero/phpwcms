<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
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

<form action="<?php
    echo shop_url(array('controller=prod', 'edit='.$plugin['data']['shopprod_id']))
?>" method="post" onsubmit="selectAllOptions(this.shopprod_images);selectAllOptions(this.shopprod_files);">

  <div class="row align-items-center">
    <div class="col-sm">
      <h1 class="mb-2 mb-sm-0 text-center text-sm-left"><?php echo $BLM['prod_edit'] ?></h1>
    </div>
    <div class="col-sm">
      <div class="form-group text-center text-sm-right mb-0">
          <input name="submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo empty($plugin['data']['shopprod_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
          <input name="save" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
          <input name="close" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_admin_struct_close'] ?>" />
       </div>
     </div>
  </div>
<hr />

  <div class="form-group align-items-center form-row">
    <input type="hidden" name="shopprod_id" value="<?php echo $plugin['data']['shopprod_id'] ?>" /><?php if (SHOP_FELANG_SUPPORT === false): ?><input type="hidden" name="shopprod_lang" value="<?php echo $plugin['data']['shopprod_lang'] ?>" /><?php endif; ?>
            <label class="col-sm-2 col-form-label text-right"></label>
            <div class="col">
            <?php echo $BL['be_cnt_last_edited']  ?>: <?php echo html_specialchars(date($BL['be_fprivedit_dateformat'], $plugin['data']['shopprod_changedate'])) ;
                if (!empty($plugin['data']['shopprod_createdate'])) {
            ?>
            <br /><span class="chatlist"><?php echo $BL['be_fprivedit_created']  ?>:</span>
            <?php
                    echo html_specialchars(date($BL['be_fprivedit_dateformat'], strtotime($plugin['data']['shopprod_createdate'])));
             }
            ?>
        </div>
  </div>

  <div class="form-group form-row align-items-center">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_ordernumber'] ?></label>
    <div class="col-sm-4">
      <input name="shopprod_ordernumber" type="text" id="shopprod_ordernumber" class="form-control form-control-sm<?php
          //error class
          if (!empty($plugin['error']['shopprod_ordernumber'])) {
              echo ' errorInputText';
          }
      ?>" value="<?php echo html_specialchars($plugin['data']['shopprod_ordernumber']) ?>" size="30" maxlength="20" />
    </div>
    <div class="col-sm-6">
      <div class="row align-items-center">
        <label class="col-sm-auto col-form-label text-right ml-sm-5"><?php echo $BLM['shopprod_model'] ?></label>
          <div class="col">
           <input name="shopprod_model" type="text" id="shopprod_model" class="form-control form-control-sm" value="<?php echo html_specialchars($plugin['data']['shopprod_model']) ?>" size="30" maxlength="200" />
          </div>
      </div>
    </div>
  </div>

  <?php if (SHOP_FELANG_SUPPORT): ?>
  <div class="form-group form-row align-items-center">
      <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_lang']  ?></label>
      <div class="col">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="shopprod_lang" id="shopprod_lang_0" value=""<?php is_checked($plugin['data']['shopprod_lang'], '') ?> />
          <label class="form-check-label" for="shopprod_lang_0"><?php echo $BL['be_ftptakeover_all'] ?></label>
        </div>
    <?php foreach ($cmsgo['allowed_lang'] as $lang):
          $lang = strtolower($lang);
    ?><div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="shopprod_lang" id="shopprod_lang_<?php echo $lang ?>" value="<?php echo $lang ?>"<?php is_checked(strtolower($plugin['data']['shopprod_lang']), $lang) ?> />
        <label class="form-check-label" for="shopprod_lang_<?php echo $lang ?>"><span class="flag-icon flag-icon-<?php echo $lang; $lang = strtoupper($lang); ?> mt-1" data-toggle="tooltip" title="<?php echo $lang ?>"></span></label>
      </div>
  <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="form-group form-row align-items-center">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_name1'] ?></label>
    <div class="col">
      <input name="shopprod_name1" type="text" id="shopprod_name1" class="form-control form-control-sm<?php
          //error class
          if (!empty($plugin['error']['shopprod_name1'])) {
              echo ' errorInputText';
          }
      ?>" value="<?php echo html_specialchars($plugin['data']['shopprod_name1']) ?>" size="30" maxlength="200" />
    </div>
  </div>

  <div class="form-group form-row align-items-center">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_name2'] ?></label>
    <div class="col">
      <input name="shopprod_name2" type="text" id="shopprod_name2" class="form-control form-control-sm" value="<?php echo html_specialchars($plugin['data']['shopprod_name2']) ?>" size="30" maxlength="200" />
    </div>
  </div>

  <div class="form-group form-row align-items-center">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_weight'] ?></label>
    <div class="col-sm-4">
        <div class="input-group input-group-sm">
      <input name="shopprod_weight" type="text" id="shopprod_weight" class="form-control" value="<?php echo number_format($plugin['data']['shopprod_weight'], 3, $BLM['dec_point'], $BLM['thousands_sep']); ?>" size="30" maxlength="200" />
                <div class="input-group-append">
                    <div class="input-group-text">
                        <?php
                        if (! ($plugin['data']['shop_pref_unit_weight'] = _getConfig('shop_pref_unit_weight'))) {
                                $plugin['data']['shop_pref_unit_weight'] = 'kg';
                                _setConfig('shop_pref_unit_weight', $plugin['data']['shop_pref_unit_weight'], 'module_shop');
                        }
                        echo html_specialchars($plugin['data']['shop_pref_unit_weight']);
                        ?>
                    </div>
                </div>
            </div>
    </div>
  </div>

    <div class="form-group form-row align-items-center">
        <label class="col-sm-2 col-form-label text-right" for="shopprod_inventory"><?php echo $BLM['shopprod_inventory'] ?></label>
        <div class="col-sm-4">
            <input name="shopprod_inventory" type="text" id="shopprod_inventory" class="form-control form-control-sm" value="<?php echo $plugin['data']['shopprod_inventory'] ?>" size="30" maxlength="11" />
        </div>
    </div>

  <div class="form-group form-row align-items-center">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_unit'] ?></label>
    <div class="col-sm-4">
      <input name="shopprod_unit" type="text" id="shopprod_unit" class="form-control form-control-sm" value="<?php echo html($plugin['data']['shopprod_unit']) ?>" size="30" maxlength="100" />
    </div>
  </div>

  <div class="form-group form-row align-items-center">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_price'] ?></label>
    <div class="col-sm-4">
        <div class="input-group input-group-sm">
        <input name="shopprod_price" type="text" id="shopprod_price" class="form-control<?php if (!empty($plugin['error']['shopprod_price'])) {
                            echo ' errorInputText';
                    } ?>" value="<?php $dec_lenght = strlen(strrchr($plugin['data']['shopprod_price'], '.')) - 1; if ($dec_lenght < 2) {
                            $dec_lenght = 2;
                    } echo number_format($plugin['data']['shopprod_price'], $dec_lenght, $BLM['dec_point'], $BLM['thousands_sep']); ?>" size="30" maxlength="200" />
            <div class="input-group-append">
                <div class="input-group-text">
                    <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="shopprod_netgross" id="shopprod_netgross" value="1"<?php is_checked(1, $plugin['data']['shopprod_netgross']) ?>  title="<?php echo $BLM['shopprod_netgross_info'] ?>" />
                            <label  class="form-check-label" for="shopprod_netgross" title="<?php echo $BLM['shopprod_netgross_info'] ?>"><?php echo $BLM['shopprod_netgross'] ?></label>
                        </div>
            </div>
            </div>
        </div>
    </div>

    <div class="col-sm-auto px-3 py-3 py-sm-0">
        <div class="input-group input-group-sm align-items-center">
                <label class="col-form-label mr-2"><?php echo $BLM['shopprod_vat'] ?></label>
                    <select name="shopprod_vat" id="shopprod_id" class="form-control-sm custom-select">
                        <?php
                        if (! $plugin['data']['shop_pref_vat'] = _getConfig('shop_pref_vat')) {
                                $plugin['data']['shop_pref_vat'] = array('0.00');
                                _setConfig('shop_pref_vat', $plugin['data']['shop_pref_vat'], 'module_shop');
                        }
                        $add_option = '';
                        $add_vat    = array();
                        foreach ($plugin['data']['shop_pref_vat'] as $value) {
                                echo '<option value="'.$value.'"';
                                if ($plugin['data']['shopprod_vat'] == $value) {
                                        echo ' selected="selected"';
                                } elseif (! empty($plugin['data']['shopprod_vat']) && ! in_array($plugin['data']['shopprod_vat'], $plugin['data']['shop_pref_vat'])) {
                                        $plugin['data']['shop_pref_vat'][] = $plugin['data']['shopprod_vat'];
                                        natsort($plugin['data']['shop_pref_vat']);
                                        _setConfig('shop_pref_vat', $plugin['data']['shop_pref_vat'], 'module_shop');

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
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text">%</div>
                </div>
            </div>
    </div>
  </div>

  <hr />

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_size'] ?></label>
    <div class="col-sm-4">
      <textarea name="shopprod_size" id="shopprod_size" class="form-control form-control-sm" rows="5" cols="15"><?php echo html_specialchars($plugin['data']['shopprod_size']) ?></textarea>
    </div>
    <div class="col-sm-6">
    <div class="form-row">
    <label class="col-sm-3 col-form-label text-right"><?php echo $BLM['shopprod_color'] ?></label>
            <div class="col">
              <textarea name="shopprod_color" id="shopprod_color" class="form-control form-control-sm" rows="5" cols="15"><?php echo html_specialchars($plugin['data']['shopprod_color']) ?></textarea>
      </div>
      </div>
    </div>
  </div>

  <hr />

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_description0'] ?></label>
    <div class="col">
      <?php
        $wysiwyg_editor = array(
            'value'     => $plugin['data']['shopprod_description0'],
            'field'     => 'shopprod_description0',
            'height'    => '150px',
            'width'     => '100%',
            'rows'      => '10',
            'editor'    => $_SESSION["WYSIWYG_EDITOR"],
            'lang'      => 'en'
        );
        include CMSGO_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';
        ?>
    </div>
  </div>

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
    <div class="col">
      <?php

        $wysiwyg_editor = array(
            'value'     => $plugin['data']['shopprod_description1'],
            'field'     => 'shopprod_description1',
            'height'    => '250px',
            'width'     => '100%',
            'rows'      => '10',
            'editor'    => $_SESSION["WYSIWYG_EDITOR"],
            'lang'      => 'en'
        );

        include CMSGO_ROOT.'/include/inc_lib/wysiwyg.editor.inc.php';

        ?>
    </div>
  </div>

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_description1'] ?></label>
    <div class="col">
      <textarea name="shopprod_description2" id="shopprod_description2" rows="5" class="form-control form-control-sm"><?php echo html_specialchars($plugin['data']['shopprod_description2']) ?></textarea>
    </div>
  </div>

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_description2'] ?></label>
    <div class="col">
      <textarea name="shopprod_description3" id="shopprod_description3" rows="5" class="form-control form-control-sm"><?php echo html_specialchars($plugin['data']['shopprod_description3']) ?></textarea>
    </div>
  </div>

<hr />

  <div class="form-group form-row">
    <label for="cimage_list" class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_image'] ?></label>
    <div class="col">
      <div class="row">
        <div class="col"><select name="shopprod_images[]" size="<?php

    $img_count = isset($plugin['data']['shopprod_images']) && is_array($plugin['data']['shopprod_images']) ? count($plugin['data']['shopprod_images']) : 0;

    echo $img_count+5

        ?>" multiple="multiple" class="custom-select form-control form-control-sm h-100" id="shopprod_images">
<?php

$img_thumbs = '';
$imgx = 0;

if ($img_count) {

    // browse images and list available
    // will be visible only when aceessible
    foreach ($plugin['data']['shopprod_images'] as $key => $value) {

        // 0   :1       :2   :3        :4    :5     :6      :7       :8
        // dbid:filename:hash:extension:width:height:caption:position:zoom
        $thumb_image = get_cached_image(array(
            "target_ext"    =>  $plugin['data']['shopprod_images'][$key]['f_ext'],
            "image_name"    =>  $plugin['data']['shopprod_images'][$key]['f_hash'] . '.' . $plugin['data']['shopprod_images'][$key]['f_ext'],
            "thumb_name"    =>  md5($plugin['data']['shopprod_images'][$key]['f_hash'].$cmsgo["img_list_width"].$cmsgo["img_list_height"].$cmsgo["sharpen_level"].$cmsgo['colorspace'])
        ));

        if ($thumb_image != false) {

            // image found
            echo '<option value="' . $plugin['data']['shopprod_images'][$key]['f_id'] . '">';
            $img_name = html_specialchars($plugin['data']['shopprod_images'][$key]['f_name']);
            echo $img_name . '</option>'.LF;


            $img_thumbs .= '<img class="my-1 mr-1" src="' . $thumb_image['src'] .'" '.$thumb_image[3].' alt="'.$img_name.'" title="'.$img_name.'" />';

            $plugin['data']['shopprod_caption'][] = html_specialchars($plugin['data']['shopprod_images'][$key]['caption']);

            $imgx++;
        }
    }
}

?>
          </select>
        </div>
        <div class="col-sm-auto pl-0">
          <button type="button" class="modalButton btn btn-sm btn-blue mb-1" title="<?php echo $BL['be_cnt_openimagebrowser'] ?>" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=5&amp;target=nolist"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i></button><br>
          <button type="button" class="btn btn-sm btn-secondary mb-1" data-toggle="tooltip" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(img_field);return false;"><i class="fa fa-angle-up fa-fw" aria-hidden="true"></i></button><br>
          <button type="button" class="btn btn-sm btn-secondary mb-1" data-toggle="tooltip" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(img_field);return false;"><i class="fa fa-angle-down fa-fw" aria-hidden="true"></i></button><br>
          <button type="button" class="btn btn-sm btn-danger mb-1" onclick="removeSelectedOptions(img_field);return false;" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delimage'] ?>"><i class="far fa-trash-alt fa-fw" aria-hidden="true"></i></button>
        </div>
      </div>
      <div class="row">
        <div class="col mt-1">
  <?php

      if ($img_thumbs) {
          echo $img_thumbs;
      }

  ?>
        </div>
      </div>
    </div>
  </div>

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_caption'] ?></label>
    <div class="col">
      <textarea name="shopprod_caption" cols="40" rows="<?php echo $img_count+5 ?>" wrap="off" class="form-control form-control-sm" id="shopprod_caption"><?php echo implode(' '.LF, $plugin['data']['shopprod_caption']) ?></textarea>
    </div>
  </div>

<hr />

  <!-- Attachments -->
  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_files'] ?></label>
    <div class="col">
      <div class="row">
        <div class="col"><select name="shopprod_files[]" size="<?php
    $files_count = isset($plugin['data']['shopprod_files']) && is_array($plugin['data']['shopprod_files']) ? count($plugin['data']['shopprod_files']) : 0;
    echo $files_count+5
        ?>" multiple="multiple" class="custom-select form-control form-control-sm h-100" id="shopprod_files">
<?php

if (count($plugin['data']['shopprod_files'])) {
    // browse images and list available
    // will be visible only when accessible
    foreach ($plugin['data']['shopprod_files'] as $key => $value) {
        echo '<option value="' . $plugin['data']['shopprod_files'][$key]['f_id'] . '">';
        echo html_specialchars($plugin['data']['shopprod_files'][$key]['f_name']);
        echo '</option>'.LF;

        $plugin['data']['shopprod_filecaption'][] = html_specialchars($plugin['data']['shopprod_files'][$key]['caption']);
    }
}
?>
          </select>
        </div>
        <div class="col-sm-auto pl-0">
            <button type="button" class="modalButton btn btn-sm btn-blue mb-1" title="<?php echo $BL['be_cnt_openfilebrowser'] ?>" data-toggle="modal" data-target="#browserModal" data-src="filebrowser.php?opt=9&amp;target=nolist"><i class="fa fa-folder-open fa-fw" aria-hidden="true"></i></button><br>
            <button type="button" class="btn btn-sm btn-secondary mb-1" data-toggle="tooltip" title="<?php echo $BL['be_cnt_sortup'] ?>" onclick="moveOptionUp(files_field);return false;"><i class="fa fa-angle-up fa-fw" aria-hidden="true"></i></button><br>
            <button type="button" class="btn btn-sm btn-secondary mb-1" data-toggle="tooltip" title="<?php echo $BL['be_cnt_sortdown'] ?>" onclick="moveOptionDown(files_field);return false;"><i class="fa fa-angle-down fa-fw" aria-hidden="true"></i></button><br>
            <button type="button" class="btn btn-sm btn-danger mb-1" onclick="removeSelectedOptions(files_field);return false;" data-toggle="tooltip" title="<?php echo $BL['be_cnt_delfile'] ?>"><i class="far fa-trash-alt fa-fw" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
  </div>

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_cnt_description'] ?></label>
    <div class="col">
      <textarea name="shopprod_filecaption" cols="40" rows="<?php echo $img_count+5 ?>" wrap="off" class="form-control form-control-sm" id="shopprod_filecaption"><?php echo implode(' '.LF, $plugin['data']['shopprod_filecaption']) ?></textarea>
    </div>
  </div>
<!-- End Attachments -->

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_url'] ?></label>
    <div class="col">
      <input name="shopprod_url" type="text" id="shopprod_url" class="form-control form-control-sm" value="<?php echo html_specialchars($plugin['data']['shopprod_url']) ?>" size="30" maxlength="250" />
    </div>
  </div>

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['prod_cat'] ?></label>
    <div class="col-sm-4">
        <select name="shopprod_category[]" size="7" multiple="multiple" class="custom-select form-control form-control-sm" id="shopprod_category">
        <?php
        $t = array();
        foreach ($plugin['data']['categories'] as $value) {
            echo '<option value="'.$value['cat_id'].'"';
            if (in_array($value['cat_id'], $plugin['data']['shopprod_category'])) {
                echo ' selected="selected"';
                $t[] = $value['category'] . "\n";
            }
            if ($value['cat_status'] == 0) {
                echo ' style="font-style:italic;"';
            }
            echo '>';
            if ($value['cat_pid']) {
                echo '&nbsp;&nbsp;&nbsp;';
            }
            echo html_specialchars($value['cat_name']).'</option>'.LF;
        }
        ?>
        </select>
    </div>
        <?php if (count($t)) {
            ?>
             <div class="col"><?php echo nl2br(html_specialchars(implode(' ', $t))) ?></div>
        <?php
        }   ?>
  </div>

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_tag'] ?></label>
    <div class="col">
      <input name="shopprod_tag" type="text" id="shopprod_tag" class="form-control form-control-sm" value="<?php echo html_specialchars(trim($plugin['data']['shopprod_tag'], ',')) ?>" size="30" maxlength="250" />
    </div>
  </div>

    <div class="form-group form-row">
        <label class="col-2 col-form-label text-right"><?php echo $BLM['shopprod_on_request'] ?></label>
        <div class="col-10 col-sm-1">
            <div class="form-check pt-2">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="shopprod_on_request" id="shopprod_on_request" value="1"<?php is_checked($plugin['data']['shopprod_on_request'], 1) ?> />
                    <?php echo $BL['be_cnt_activated'] ?>
                </label>
            </div>
        </div>
        <div class="col-3 col-sm-2 text-right">
            <label class="col-form-label"><?php echo $BLM['shopprod_on_request_button'] ?></label>
        </div>
        <div class="col-9 col-sm-7">
            <input name="shopprod_on_request_url" type="text" id="shopprod_on_request_url" class="form-control form-control-sm" value="<?php echo html($plugin['data']['shopprod_on_request_url']) ?>" size="30" maxlength="250" title="<?php echo $BLM['shopprod_on_request_url'] ?>" placeholder="<?php echo $BLM['shopprod_on_request_url'] ?>" />
        </div>
    </div>

    <hr />

  <div class="form-group form-row">
    <label class="col-sm-2 col-form-label text-right pt-0"><?php echo $BL['be_ftptakeover_status'] ?></label>
    <div class="col">
        <div class="form-check">
        <label class="form-check-label">
            <input class="form-check-input" type="checkbox" name="shopprod_status" id="shopprod_status" value="1"<?php is_checked($plugin['data']['shopprod_status'], 1) ?> />
            <strong><?php echo $BL['be_cnt_activated'] ?></strong>
        </label>
        </div>
        <div class="form-check">
        <label class="form-check-label">
            <input class="form-check-input" type="checkbox" name="shopprod_listall" id="shopprod_listall" value="1"<?php is_checked($plugin['data']['shopprod_listall'], 1) ?> />
            <?php echo $BLM['shopprod_listall'] ?>
        </label>
        </div>
        <div class="form-check">
        <label class="form-check-label">
            <input class="form-check-input" type="checkbox" name="shopprod_overwrite_meta" id="shopprod_overwrite_meta" value="1"<?php is_checked($plugin['data']['shopprod_overwrite_meta'], 1) ?> />
            <?php echo $BLM['shopprod_overwrite_meta'] ?>
        </label>
        </div>
        <div class="form-check">
        <label class="form-check-label">
            <input class="form-check-input" type="checkbox" name="shopprod_opengraph" id="shopprod_opengraph" value="1"<?php is_checked($plugin['data']['shopprod_opengraph'], 1) ?> />
            <?php echo $BL['be_opengraph_support'] ?>
        </label>
        </div>

<!-- save as duplicate -->
<?php   if ($plugin['data']['shopprod_id']): ?>
                <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="shopprod_duplicate" id="shopprod_duplicate" value="1"<?php is_checked($plugin['data']['shopprod_duplicate'], 1) ?> />
                    <?php echo $BL['be_save_copy'] ?>
                </label>
                </div>
<?php   endif;  ?>
    </div>
  </div>

      <div class="form-group text-center text-sm-right mb-0">
          <input name="submit" type="submit" class="btn btn-sm btn-blue" value="<?php echo empty($plugin['data']['shopprod_id']) ? $BL['be_admin_fcat_button2'] : $BL['be_article_cnt_button1'] ?>" />
          <input name="save" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
          <input name="close" type="submit" class="btn btn-sm btn-blue" value="<?php echo $BL['be_admin_struct_close'] ?>" />
       </div>

</form>
<script type="text/javascript">

var img_field = getObjectByIdShop('shopprod_images');
var files_field = getObjectByIdShop('shopprod_files');

function addFile(value,text) {
    if(files_field!=null && files_field.options!=null) {
        newOpt = new Option(text, value);
        files_field.options.length++;
        files_field.options[files_field.length-1].text  = newOpt.text;
        files_field.options[files_field.length-1].value = newOpt.value;
        files_field.options[files_field.length-1].selected = false;
    }
}
function getObjectByIdShop(fld) {
    if (document.getElementById && document.getElementById(fld) != null) {
        return document.getElementById(fld);
    } else if (document.layers && document.layers[fld] != null) {
        return document.layers[fld];
    } else if (document.all) {
        return document.all(fld);
    } else {
        return false;
    }
}
</script>
