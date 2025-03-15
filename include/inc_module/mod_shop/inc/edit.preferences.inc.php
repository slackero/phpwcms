<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2025, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

?>

<form action="<?php echo shop_url('controller=pref'); ?>" method="post">
    <div class="form-group text-center text-sm-right my-3 my-sm-0">
        <input name="save" type="submit" class="btn btn-sm btn-blue" id="save_button" value="<?php echo $BL['be_article_cnt_button3'] ?>" disabled />
        <input name="reset" type="reset" class="btn btn-sm btn-blue" value="<?php echo $BL['be_cnt_field']['reset'] ?>" onclick="disableSubmit();" />
    </div>

    <div class="form-group form-row align-items-center">
        <label class="col-sm-2 col-form-label text-right"><?php echo $BL['be_profile_label_lang'] ?></label>
        <div class="col-sm-auto">
            <div class="form-check form-check-inline">
                <input class="form-check-input" name="pref_felang" id="pref_felang" type="checkbox" value="1"<?php is_checked('1', $plugin['data']['shop_pref_felang']) ?> onchange="enableSubmit();" />
                <label class="form-check-label"><?php echo $BLM['shopprod_lang_support'] . ' (' . strtoupper(implode('/', $cmsgo['allowed_lang'])) . ')' ?></label>
            </div>
        </div>
    </div>

    <div class="form-group form-row align-items-center">
        <label for="shopprod_email_from" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_currency'] ?></label>
        <div class="col col-sm-4">
            <input type="text" class="form-control form-control-sm" name="pref_currency" id="pref_currency" value="<?php echo html_specialchars($plugin['data']['shop_pref_currency']) ?>" size="10" maxlength="10" onchange="enableSubmit();" />
        </div>
        <div class="col-sm-auto align-items-center text-left">&nbsp;CHF, EUR, USD, &#8364;, $, &pound;, &yen;</div>
    </div>

    <div class="form-group form-row align-items-center">
        <label for="shopprod_email_from" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_unit'] . ' - ' . $BLM['shopprod_weight'] ?></label>
        <div class="col col-sm-4">
            <input type="text" class="form-control form-control-sm" name="pref_unit_weight" id="pref_unit_weight" value="<?php echo html_specialchars($plugin['data']['shop_pref_unit_weight']) ?>" size="10" maxlength="10" onchange="enableSubmit();" />
        </div>
        <div class="col-sm-auto align-items-center text-left">&nbsp;<?php echo $BLM['shopprod_units_weight'] ?></div>
    </div>

    <div class="form-group form-row align-items-top">
        <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_vat_rates'] ?></label>
        <div class="col col-sm-4 align-items-top">
            <textarea class="form-control form-control-sm text-right" name="pref_vat" id="pref_vat" rows="3" onchange="enableSubmit();" />
            <?php
            foreach( $plugin['data']['shop_pref_vat'] as $value ) {
                echo number_format((float) $value, 2, $BLM['dec_point'], $BLM['thousands_sep']) . LF;
            }
            ?>
            </textarea>
        </div>
        <div class="col-sm-auto align-items-top text-left">&nbsp;%</div>
    </div>

    <div class="form-group form-row align-items-center">
        <label for="shopprod_email_to" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_email_to'] ?></label>
        <div class="col-sm-4">
            <input type="text" class="form-control form-control-sm" name="pref_email_to" id="pref_email_to" value="<?php echo html_specialchars(str_replace(';', '; ', $plugin['data']['shop_pref_email_to'])) ?>" size="30" maxlength="200" onchange="enableSubmit();" />
        </div>
    </div>

    <div class="form-group form-row align-items-center">
        <label for="shopprod_email_from" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_email_from'] ?></label>
        <div class="col-sm-4">
            <input type="text" class="form-control form-control-sm" name="pref_email_from" id="pref_email_from" value="<?php echo html_specialchars($plugin['data']['shop_pref_email_from']) ?>" size="30" maxlength="200" onchange="enableSubmit();" />
        </div>
    </div>

    <div class="form-group form-row align-items-center">
        <label for="shopprod_id_shop" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_id_shop'] ?></label>
        <div class="col-sm-4">
            <input type="text" class="form-control form-control-sm" name="pref_shop_id" id="pref_shop_id" value="<?php echo html_specialchars($plugin['data']['shop_pref_id_shop']) ?>" size="30" maxlength="200" onchange="enableSubmit();" />
        </div>
    </div>

    <div class="form-group form-row align-items-center">
        <label for="shopprod_id_cart" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_id_cart'] ?></label>
        <div class="col-sm-4">
            <input type="text" class="form-control form-control-sm" name="pref_cart_id" id="pref_cart_id" value="<?php echo html_specialchars($plugin['data']['shop_pref_id_cart']) ?>" size="30" maxlength="200" onchange="enableSubmit();" />
        </div>
    </div>

    <hr />

    <div class="form-group form-row align-items-center">
        <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_shipping'] ?></label>
        <div class="col-sm-2">
            <div class="form-check form-check-inline">
                <input class="form-check-input" name="pref_shipping_calc" type="radio" value="0"<?php is_checked(0, $plugin['data']['shop_pref_shipping_calc']) ?> onchange="enableSubmit();" />
                <label class="form-check-label"><strong><?php echo $BLM['shopprod_weight'].', '.$BLM['shopprod_weight_max'] ?></strong></label>
            </div>
        </div>
        <label class="col-sm-2 mb-0">
            <strong><?php echo $BLM['shopprod_net'] ?></strong>
        </label>
        <label class="col-sm-2 mb-0">
            <strong><?php echo $BLM['shopprod_vat'] ?> %</strong>
        </label>
    </div>

    <?php
    for( $x = 0; $x <= 4; $x++ ) {
        // Be sure to have set all default values
        $plugin['data']['shop_pref_shipping'][$x] = array_merge($_checkPref['shop_pref_shipping'][$x], $plugin['data']['shop_pref_shipping'][$x]);

        echo '
            <div class="form-group form-row align-items-center">
            <label class="col-sm-2 col-form-label text-right"></label>
                <div class="col-sm-2"><input name="pref_shipping_weight['.$x.']" type="text" class="form-control form-control-sm" value="' .
                html_specialchars( @number_format((float) $plugin['data']['shop_pref_shipping'][$x]['weight'], 3, $BLM['dec_point'], $BLM['thousands_sep'] ) ) .
                '" size="10" maxlength="10" onchange="enableSubmit();" /></div>
                <div class="col-sm-2"><input name="pref_shipping_net['.$x.']" type="text" class="form-control form-control-sm" value="' .
                html_specialchars( @number_format((float) $plugin['data']['shop_pref_shipping'][$x]['net'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) .
                '" size="10" maxlength="10" onchange="enableSubmit();" /></div>
                <div class="col-sm-2"><input name="pref_shipping_vat['.$x.']" type="text" class="form-control form-control-sm" value="' .
                html_specialchars( @number_format((float) $plugin['data']['shop_pref_shipping'][$x]['vat'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) .
                '" size="10" maxlength="10" onchange="enableSubmit();" /></div>
            </div>
            ';
        }
    ?>

    <div class="form-group form-row align-items-center mt-3">
      <label class="col-sm-2 col-form-label text-right pt-0"></label>
        <div class="col-sm-2">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                <input class="form-check-input" name="pref_shipping_calc" type="radio" value="1"<?php is_checked(1, $plugin['data']['shop_pref_shipping_calc']) ?> onchange="enableSubmit();" />
                <strong><?php echo $BLM['shopprod_price'].', '.$BLM['shopprod_net'] ?></strong>
            </label>
        </div>
        </div>
        <label class="col-sm-2 mb-0 ">
            <strong><?php echo $BLM['shopprod_net'] ?></strong>
        </label>
        <label class="col-sm-2 mb-0 ">
            <strong><?php echo $BLM['shopprod_vat'] ?> %</strong>
        </label>
    </div>

    <?php
    for( $x = 0; $x <= 4; $x++ ) {

        echo '
            <div class="form-group form-row align-items-center">
            <label class="col-sm-2 col-form-label text-right"></label>
                <div class="col-sm-2"><input name="pref_shipping_price['.$x.']" type="text" class="form-control form-control-sm" value="' .
                html_specialchars( @number_format((float) $plugin['data']['shop_pref_shipping'][$x]['price'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) .
                '" size="10" maxlength="10" onchange="enableSubmit();" /></div>
                <div class="col-sm-2"><input name="pref_shipping_price_net['.$x.']" type="text" class="form-control form-control-sm" value="' .
                html_specialchars( @number_format((float) $plugin['data']['shop_pref_shipping'][$x]['price_net'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) .
                '" size="10" maxlength="10" onchange="enableSubmit();" /></div>
                <div class="col-sm-2"><input name="pref_shipping_price_vat['.$x.']" type="text" class="form-control form-control-sm" value="' .
                html_specialchars( @number_format((float) $plugin['data']['shop_pref_shipping'][$x]['price_vat'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) .
                '" size="10" maxlength="10" onchange="enableSubmit();" /></div>
            </div>
            ';
    }
    ?>

    <?php if(!ini_get('allow_url_fopen')): ?>
        <div class="alert alert-danger" role="alert">
            <strong>Set PHP INI Value 'allow_url_fopen = 1'</strong>
        </div>
    <?php endif; ?>

    <div class="form-group form-row align-items-center mt-3">
        <label class="col-sm-2 col-form-label text-right pt-0"></label>
        <div class="col-sm-2">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" name="pref_shipping_calc" type="radio" value="2"<?php is_checked(2, $plugin['data']['shop_pref_shipping_calc']) ?> onchange="enableSubmit();"<?php if(!ini_get('allow_url_fopen')): ?> disabled="disabled"<?php endif; ?> />
                    <strong><?php echo $BLM['shopprod_distance'] ?></strong>
                </label>
            </div>
        </div>
        <label class="col-sm-2 mb-0">
            <strong><?php echo $BLM['shopprod_net'] ?></strong>
        </label>
        <label class="col-sm-2 mb-0">
            <strong><?php echo $BLM['shopprod_vat'] ?> %</strong>
        </label>
        <label class="col-sm-2 mb-0">
            <strong><?php echo $BL['be_title'] ?></strong>
        </label>
    </div>

<?php
    for( $x = 0; $x <= 4; $x++ ) {

        echo '
            <div class="form-group form-row align-items-center">
            <label class="col-sm-2 col-form-label text-right"></label>
                <div class="col-sm-2"><input name="pref_shipping_zone['.$x.']" type="text" class="form-control form-control-sm" value="' .
                $plugin['data']['shop_pref_shipping'][$x]['zone'] .
                '" size="10" maxlength="10" onchange="enableSubmit();" /></div>
                <div class="col-sm-2"><input name="pref_shipping_zone_net['.$x.']" type="text" class="form-control form-control-sm" value="' .
                html_specialchars( @number_format((float) $plugin['data']['shop_pref_shipping'][$x]['zone_net'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) .
                '" size="10" maxlength="10" onchange="enableSubmit();" /></div>
                <div class="col-sm-2"><input name="pref_shipping_zone_vat['.$x.']" type="text" class="form-control form-control-sm" value="' .
                html_specialchars( @number_format((float) $plugin['data']['shop_pref_shipping'][$x]['zone_vat'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) .
                '" size="10" maxlength="10" onchange="enableSubmit();" /></div>
                <div class="col-sm-2"><input name="pref_shipping_zone_label['.$x.']" type="text" class="form-control form-control-sm" value="' .
                html_specialchars($plugin['data']['shop_pref_shipping'][$x]['zone_label']) .
                '" size="10" maxlength="100" onchange="enableSubmit();" /></div>
            </div>
            ';
    }
?>

    <hr />

    <div class="form-group form-row align-items-center">
        <label for="pref_shipping_selfpickup" class="col-sm-2 col-form-label text-right">
            <?php echo $BLM['shopprod_selfpickup'] ?>
        </label>
        <div class="col-sm-auto">
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="pref_shipping_selfpickup" id="pref_shipping_selfpickup" value="1"<?php is_checked('1', @$plugin['data']['shop_pref_shipping_selfpickup']) ?> onchange="enableSubmit();" />
                <label for="pref_shipping_selfpickup" class="form-check-label">
                    <?php echo $BLM['shopprod_allowed'] ?>
                </label>
            </div>
        </div>
        <div class="col-sm-auto">
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input" name="pref_freeshipping_pickup" id="pref_freeshipping_pickup" value="1"<?php is_checked('1', @$plugin['data']['shop_pref_discount']['freeshipping_pickup']) ?> onchange="enableSubmit();" />
                <label for="pref_freeshipping_pickup" class="form-check-label">
                    <?php echo $BLM['shopprod_freeshipping'] ?>
                </label>
            </div>
        </div>
    </div>

    <hr />

    <div class="form-group form-row align-items-center">
        <label for="shopprod_id_cart" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_distance_base'] ?></label>
        <div class="col-sm-4">
            <input type="text" class="form-control form-control-sm" name="pref_zone_base" id="pref_zone_base" value="<?php echo html_specialchars($plugin['data']['shop_pref_zone_base']) ?>" size="30" maxlength="200" onchange="enableSubmit();" />
        </div>
    </div>

    <!-- Low order surcharge -->
    <div class="form-group form-row align-items-center">
        <label for="shopprod_id_cart" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_loworder'] ?></label>
            <div class="col-sm-auto">
                <div class="form-check-inline">
                    <input class="form-check-input mr-sm-3" type="checkbox" name="pref_loworder" id="pref_loworder" value="1"<?php is_checked('1', $plugin['data']['shop_pref_loworder']['loworder']) ?> onchange="enableSubmit();" />
                    <label class="form-check-label"><?php echo trim($BLM['shopprod_loworder_under'].' '.html_specialchars($plugin['data']['shop_pref_currency'])) ?></label>
                </div>
            </div>
            <div class="col-sm-auto">
                <input name="pref_loworder_under" type="text" id="pref_loworder_under" class="form-control form-control-sm" value="<?php echo html_specialchars( @number_format((float) $plugin['data']['shop_pref_loworder']['under'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) ?>" size="10" maxlength="10" onchange="enableSubmit();" />
            </div>
            <div class="col-sm-auto py-2 py-sm-0">
                <?php echo $BLM['shopprod_loworder_charge'] ?>
            </div>
            <div class="col-sm-auto">
                <input name="pref_loworder_charge" type="text" id="pref_loworder_charge" class="form-control form-control-sm" value="<?php echo html_specialchars( @number_format((float) $plugin['data']['shop_pref_loworder']['charge'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) ?>" size="10" maxlength="10" onchange="enableSubmit();" />
            </div>
            <div class="col-sm-auto py-2 py-sm-0">
                <?php echo $BLM['shopprod_vat'] ?>
            </div>
            <div class="col-sm-auto">
                <input name="pref_loworder_vat" type="text" id="pref_loworder_vat" class="form-control form-control-sm" value="<?php echo html_specialchars( @number_format((float) $plugin['data']['shop_pref_loworder']['vat'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) ?>" size="10" maxlength="10" onchange="enableSubmit();" />
            </div>
            &nbsp;%
    </div>

    <div class="form-group form-row align-items-center">
        <label class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_inventory'] ?></label>
        <div class="col-sm-auto">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="pref_autosubtract_off" id="pref_autosubtract_off" value="1"<?php is_checked('1', $plugin['data']['shop_pref_autosubtract_off']) ?> onchange="enableSubmit();" />
                <label class="form-check-label"><?php echo $BLM['shopprod_autosubtract_off'] ?></label>
            </div>
        </div>
    </div>

    <!-- Discount -->
    <div class="form-group form-row align-items-center">
        <label for="shopprod_discount" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_discount'] ?></label>
            <div class="col-sm-auto">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="pref_discount" id="pref_discount" value="1"<?php is_checked('1', $plugin['data']['shop_pref_discount']['discount']) ?> onchange="enableSubmit();" />
                    </label>
                </div>
            </div>
            <div class="col-sm-auto">
                <input name="pref_discount_percent" type="text" id="pref_discount_percent" class="form-control form-control-sm" value="<?php echo html_specialchars( @number_format((float) $plugin['data']['shop_pref_discount']['percent'], 1, $BLM['dec_point'], $BLM['thousands_sep'] ) ) ?>" size="10" maxlength="10" onchange="enableSubmit();" />
            </div>
            <div class="col-sm-auto py-2 py-sm-0">
                %, <?php echo $BLM['shopprod_discount_from'] ?>
            </div>
            <div class="col-sm-auto">
                <input name="pref_discount_amount" type="text" id="pref_discount_amount" class="form-control form-control-sm" value="<?php echo html_specialchars( @number_format((float) $plugin['data']['shop_pref_discount']['amount'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) ?>" size="10" maxlength="10" onchange="enableSubmit();" />
            </div>
            <div class="col-sm-auto py-2 py-sm-0">
                <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="pref_discount_freeshipping" id="pref_discount_freeshipping" value="1"<?php is_checked('1', @$plugin['data']['shop_pref_discount']['freeshipping']) ?> onchange="enableSubmit();" />
                     <?php echo $BLM['shopprod_freeshipping'] ?>
                </label>
                 </div>
            </div>
    </div>
    <div class="form-group form-row align-items-center">
        <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-auto">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="pref_discount_1" id="pref_discount_1" value="1"<?php is_checked('1', @$plugin['data']['shop_pref_discount']['discount_1']) ?> onchange="enableSubmit();" />
                    </label>
                </div>
            </div>
            <div class="col-sm-auto">
                <input name="pref_discount_percent_1" type="text" id="pref_discount_percent_1" class="form-control form-control-sm" value="<?php echo html_specialchars( @number_format((float) $plugin['data']['shop_pref_discount']['percent_1'], 1, $BLM['dec_point'], $BLM['thousands_sep'] ) ) ?>" size="10" maxlength="10" onchange="enableSubmit();" />
            </div>
            <div class="col-sm-auto py-2 py-sm-0">
                %, <?php echo $BLM['shopprod_discount_from'] ?>
            </div>
            <div class="col-sm-auto">
                <input name="pref_discount_amount_1" type="text" id="pref_discount_amount_1" class="form-control form-control-sm" value="<?php echo html_specialchars( @number_format((float) $plugin['data']['shop_pref_discount']['amount_1'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) ?>" size="10" maxlength="10" onchange="enableSubmit();" />
            </div>
            <div class="col-sm-auto py-2 py-sm-0">
                <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="pref_discount_freeshipping_1" id="pref_discount_freeshipping_1" value="1"<?php is_checked('1', @$plugin['data']['shop_pref_discount']['freeshipping_1']) ?> onchange="enableSubmit();" />
                     <?php echo $BLM['shopprod_freeshipping'] ?>
                </label>
                 </div>
            </div>
    </div>
    <div class="form-group form-row align-items-center">
        <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-auto">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="pref_discount_2" id="pref_discount_2" value="1"<?php is_checked('1', @$plugin['data']['shop_pref_discount']['discount_2']) ?> onchange="enableSubmit();" />
                    </label>
                </div>
            </div>
            <div class="col-sm-auto">
                <input name="pref_discount_percent_2" type="text" id="pref_discount_percent_2" class="form-control form-control-sm" value="<?php echo html_specialchars( @number_format((float) $plugin['data']['shop_pref_discount']['percent_2'], 1, $BLM['dec_point'], $BLM['thousands_sep'] ) ) ?>" size="10" maxlength="10" onchange="enableSubmit();" />
            </div>
            <div class="col-sm-auto py-2 py-sm-0">
                %, <?php echo $BLM['shopprod_discount_from'] ?>
            </div>
            <div class="col-sm-auto">
                <input name="pref_discount_amount_2" type="text" id="pref_discount_amount_2" class="form-control form-control-sm" value="<?php echo html_specialchars( @number_format((float) $plugin['data']['shop_pref_discount']['amount_2'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ) ?>" size="10" maxlength="10" onchange="enableSubmit();" />
            </div>
            <div class="col-sm-auto py-2 py-sm-0">
                <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="pref_discount_freeshipping_2" id="pref_discount_freeshipping_2" value="1"<?php is_checked('1', @$plugin['data']['shop_pref_discount']['freeshipping_2']) ?> onchange="enableSubmit();" />
                     <?php echo $BLM['shopprod_freeshipping'] ?>
                </label>
                 </div>
            </div>
    </div>

    <hr />

    <!-- Payment methods -->
    <div class="form-group form-row align-items-center">
        <label for="shopprod_payment_method" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_payment_method'] ?></label>
        <div class="col-sm-2">
            <div class="form-check-inline">
                <input class="form-check-input" name="pref_payment_paypal" id="pref_payment_paypal" type="checkbox" value="1"<?php is_checked(1, $plugin['data']['shop_pref_payment']['paypal']) ?> onchange="enableSubmit();" />
                <label for="pref_payment_paypal" class="form-check-label"><?php echo $BLM['shopprod_payby_paypal'] ?></label>
            </div>
        </div>
        <div class="col-sm-2 text-sm-right py-2 py-sm-0">
            <?php echo $BLM['shopprod_email_paypal'] ?>
        </div>
        <div class="col-sm-auto">
            <input name="pref_email_paypal" type="text" id="pref_email_paypal" class="form-control form-control-sm" value="<?php echo html_specialchars($plugin['data']['shop_pref_email_paypal']) ?>" size="30" maxlength="200" onchange="enableSubmit();" />
        </div>
    </div>

    <div class="form-group form-row align-items-center">
        <label for="shopprod_payment_method" class="col-sm-2 col-form-label text-sm-right"></label>
        <div class="col-sm-2">
            <div class="form-check-inline">
                <input class="form-check-input" name="pref_payment_ccard" id="pref_payment_ccard" type="checkbox" value="1"<?php is_checked(1, $plugin['data']['shop_pref_payment']['ccard']) ?> onchange="enableSubmit();" />
                <label class="form-check-label" for="pref_payment_ccard"><?php echo $BLM['shopprod_payby_ccard'] ?></label>
      </div>
        </div>
        <div class="col-sm-2 text-sm-right py-2 py-sm-0">
            <?php echo $BLM['shopprod_supported_ccard'] ?>
        </div>
        <div class="col-sm-auto">
            <select name="pref_supported_ccard[]" id="pref_supported_ccard" size="4" class="custom-select form-control form-control-sm" multiple="multiple" onchange="enableSubmit();" >
                <option value="americanexpress"<?php if(in_array('americanexpress', $plugin['data']['shop_pref_payment']['accepted_ccard'])) echo ' selected="selected"'; ?> style="margin-bottom:1px">American Express</option>
                <option value="mastercard"<?php if(in_array('mastercard', $plugin['data']['shop_pref_payment']['accepted_ccard'])) echo ' selected="selected"'; ?> style="margin-bottom:1px">MasterCard/EuroCard</option>
                <option value="visa"<?php if(in_array('visa', $plugin['data']['shop_pref_payment']['accepted_ccard'])) echo ' selected="selected"'; ?> style="margin-bottom:1px">Visa</option>
            </select>
        </div>
    </div>

    <div class="form-group form-row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <div class="form-check">
                <input class="form-check-input" name="pref_payment_prepay" id="pref_payment_prepay" value="1" type="checkbox" value="1"<?php is_checked(1, $plugin['data']['shop_pref_payment']['prepay']) ?> onchange="enableSubmit();" />
                <label class="form-check-label" for="pref_payment_prepay"><?php echo $BLM['shopprod_payby_prepay'] ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" name="pref_payment_pod" id="pref_payment_pod" value="1" type="checkbox" value="1"<?php is_checked(1, $plugin['data']['shop_pref_payment']['pod']) ?> onchange="enableSubmit();" />
                <label class="form-check-label" for="pref_payment_pod"><?php echo $BLM['shopprod_payby_pod'] ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" name="pref_payment_onbill" id="pref_payment_onbill" value="1" type="checkbox" value="1"<?php is_checked(1, $plugin['data']['shop_pref_payment']['onbill']) ?> onchange="enableSubmit();" />
                <label class="form-check-label" for="pref_payment_onbill"><?php echo $BLM['shopprod_payby_onbill'] ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" name="pref_payment_cash" id="pref_payment_cash" value="1" type="checkbox" value="1"<?php is_checked(1, $plugin['data']['shop_pref_payment']['cash']) ?> onchange="enableSubmit();" />
                <label class="form-check-label" for="pref_payment_cash"><?php echo $BLM['shopprod_payby_cash'] ?></label>
            </div>
        </div>
    </div>

    <hr />

    <div class="form-group form-row align-items-center">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col">
            <div class="form-check form-check-inline">
                <input class="form-check-input" name="pref_terms_format" id="pref_terms_format" value="0" type="radio" value="0"<?php is_checked('0', $plugin['data']['shop_pref_terms_format']) ?> onchange="enableSubmit();" />
                <label class="form-check-label" for="pref_terms_format">TEXT</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" name="pref_terms_format" id="pref_terms_html" value="1" type="radio" value="1"<?php is_checked('1', $plugin['data']['shop_pref_terms_format']) ?> onchange="enableSubmit();" />
                <label class="form-check-label" for="pref_terms_html">HTML</label>
            </div>
        </div>
    </div>

    <div class="form-group form-row">
        <label for="shopprod_terms" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_terms'] ?></label>
        <div class="col">
            <textarea name="pref_terms" rows="5" class="form-control form-control-sm" id="pref_terms" onchange="enableSubmit();">
                <?php echo $plugin['data']['shop_pref_terms_format'] ? html_entities($plugin['data']['shop_pref_terms']) : html_specialchars($plugin['data']['shop_pref_terms']); ?>
            </textarea>
         </div>
    </div>

  <div class="form-group form-row align-items-center mt-sm-3">
        <label for="shopprod_payment_method" class="col-sm-2 col-form-label text-right"><?php echo $BLM['shopprod_api'] ?></label>
        <div class="col-sm-auto">
            <div class="form-check-inline">
        <input class="form-check-input" name="pref_api_access" id="pref_api_access" type="checkbox" value="1"<?php is_checked('1', $plugin['data']['shop_pref_api_access']); ?> onchange="enableSubmit();" />
        <label class="form-check-label" for="pref_api_access"><?php echo $BLM['shopprod_api_access']; ?></label>
      </div>
        </div>
        <div class="col-sm-auto text-sm-right pt-2 pt-sm-0">
            <?php echo trim($BLM['shopprod_api_key']) ?>
        </div>
        <div class="col-sm-auto">
            <input name="pref_api_key" type="text" id="pref_api_key" class="form-control form-control-sm" value="<?php echo html_specialchars($plugin['data']['shop_pref_api_key']) ?>" size="20" maxlength="50" onchange="enableSubmit();" />
        </div>
    </div>
</form>

<script type="text/javascript">
    function enableSubmit() {
        var submit_prefs = getObjectById('save_button');
        submit_prefs.disabled=false;
    }
    function disableSubmit() {
        var submit_prefs = getObjectById('save_button');
        submit_prefs.disabled=true;
    }
</script>
