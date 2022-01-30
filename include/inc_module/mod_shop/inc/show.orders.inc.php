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

?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['shopprod_order'] ?></h1>

<div class="show">

<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="">

    <tr>
        <td class="chatlist"><?php echo $BLM['shopprod_order_date'] ?>:&nbsp;</td>
        <td width="410" class="v12"><?php

            echo html(date($BLM['shopprod_date_long'], $plugin['data']['order_date_unix']));

            if(SHOP_FELANG_SUPPORT) {
                $plugin['data']['order_data']['lang'] = empty($plugin['data']['order_data']['lang']) ? '' : html_specialchars(strtolower($plugin['data']['order_data']['lang']));
                echo '&nbsp;&nbsp;&nbsp;<span class="chatlist">'.$BL['be_profile_label_lang'].':</span> ';
                echo '<img src="img/famfamfam/lang/';
                echo $plugin['data']['order_data']['lang'] ? $plugin['data']['order_data']['lang'] : 'all';
                echo '.png" alt="'.$plugin['data']['order_data']['lang'].'" style="position:relative;top:1px;" />';
            }

        ?></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

    <tr>
        <td class="chatlist"><?php echo $BLM['shopprod_ordernumber'] ?>:&nbsp;</td>
        <td class="v12b tdbottom5"><strong><?php echo html($plugin['data']['order_number']) ?></strong></td>
    </tr>
    <tr>
        <td class="chatlist"><?php echo $BLM['th_payment'] ?>:&nbsp;</td>
        <td class="v12b tdbottom5"><strong><?php echo html($BLM[ 'shopprod_payby_'.$plugin['data']['order_payment'] ]) ?></strong></td>
    </tr>
    <tr>
        <td class="chatlist" style="padding-top:3px;"><?php echo $BL['be_ftptakeover_status'] ?>:&nbsp;</td>
        <td class="v12b">
        <form action="<?php echo shop_url('controller=order').'&amp;show='.$plugin['data']['order_id'] ?>" method="post">
            <input type="hidden" name="order_status" value="<?php echo $plugin['data']['order_id'] ?>" />
            <table cellpadding="0" cellspacing="0" border="0" summary="">
                <tr class="row">
                    <td><input type="checkbox" name="status_payment" value="PAYED" id="status_payment"<?php echo order_status('PAYED', $plugin['data']['order_status']) ?> onchange="this.form.submit();" /></td>
                    <td><label for="status_payment"><?php echo $BLM['shopprod_status_paid']?>&nbsp;</label></td>
                    <td><input type="checkbox" name="status_send" value="SENT" id="status_send"<?php echo order_status('SENT', $plugin['data']['order_status']) ?> onchange="this.form.submit();" /></td>
                    <td><label for="status_send"><?php echo $BLM['shopprod_status_sent'] ?>&nbsp;</label></td>
                    <td><input type="checkbox" name="status_back" value="RETURN" id="status_back"<?php echo order_status('RETURN', $plugin['data']['order_status']) ?> onchange="this.form.submit();" /></td>
                    <td><label for="status_back"><?php echo $BLM['shopprod_status_back'] ?>&nbsp;</label></td>
                    <td><input type="checkbox" name="status_done" value="COMPLETED" id="status_done"<?php echo order_status('COMPLETED', $plugin['data']['order_status']) ?> onchange="this.form.submit();" /></td>
                    <td><label for="status_done"><?php echo $BLM['shopprod_status_done'] ?></label></td>
                </tr>
            </table>
        </form>
        </td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>

    <tr>
        <td class="chatlist"><?php echo $BLM['shopprod_selfpickup'] ?>:&nbsp;</td>
        <td class="v12 tdbottom3">
            <strong><?php echo empty($plugin['data']['order_data']['shipping']['selfpickup']) ? $BLM['shopprod_isnot_selfpickup'] : $BLM['shopprod_is_selfpickup']; ?></strong>
        </td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>

    <tr>
        <td class="chatlist"><?php echo $BL['be_profile_label_firstname'] ?>:&nbsp;</td>
        <td class="v12 tdbottom3"><?php echo html($plugin['data']['order_firstname']) ?></td>
    </tr>
    <tr>
        <td class="chatlist"><?php echo $BL['be_profile_label_name'] ?>:&nbsp;</td>
        <td class="v12 tdbottom3"><strong><?php echo html($plugin['data']['order_name']) ?></strong></td>
    </tr>
    <tr>
        <td class="chatlist"><?php echo $BLM['shopprod_order_address'] ?>:&nbsp;</td>
        <td class="v12 tdbottom3"><?php echo nl2br( html($plugin['data']['order_data']['address']['INV_ADDRESS'])) ?></td>
    </tr>
    <tr>
        <td class="chatlist"><?php echo $BL['be_profile_label_zip'] ?>:&nbsp;</td>
        <td class="v12 tdbottom3"><?php echo html($plugin['data']['order_data']['address']['INV_ZIP']) ?></td>
    </tr>
    <tr>
        <td class="chatlist"><?php echo $BL['be_profile_label_city'] ?>:&nbsp;</td>
        <td class="v12 tdbottom3"><?php echo html($plugin['data']['order_data']['address']['INV_CITY']) ?></td>
    </tr>
    <tr>
        <td class="chatlist"><?php echo $BLM['shopprod_order_region'] ?>:&nbsp;</td>
        <td class="v12 tdbottom3"><?php echo html($plugin['data']['order_data']['address']['INV_REGION']) ?>&nbsp;</td>
    </tr>
    <tr>
        <td class="chatlist"><?php echo $BL['be_profile_label_country'] ?>:&nbsp;</td>
        <td class="v12 tdbottom3"><?php echo html($plugin['data']['order_data']['address']['INV_COUNTRY']) ?>&nbsp;</td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

    <tr>
        <td class="chatlist"><?php echo $BL['be_profile_label_email'] ?>:&nbsp;</td>
        <td class="v12 tdbottom3"><?php

        if(is_valid_email($plugin['data']['order_data']['address']['EMAIL'])) {
            echo '<a href="mailto:'.html($plugin['data']['order_data']['address']['EMAIL']);
            echo '?subject='.rawurlencode($BLM['th_ordnr'].': '.$plugin['data']['order_number']).'"><u>';
            echo html($plugin['data']['order_data']['address']['EMAIL']).'</u></a>';
        } else {
            echo '&nbsp;';
        }
        ?></td>
    </tr>
    <tr>
        <td class="chatlist"><?php echo $BL['be_profile_label_phone'] ?>:&nbsp;</td>
        <td class="v12 tdbottom3"><?php echo html($plugin['data']['order_data']['address']['PHONE']) ?>&nbsp;</td>
    </tr>

<?php

    $plugin['custom'] = array();

    foreach($plugin['data']['order_data']['address'] as $custom_key => $custom_field) {

        if(strpos($custom_key, 'shop_field') === FALSE) {
            continue;
        }

        $plugin['custom'][$custom_key] = $custom_field;

    }

    if(count($plugin['custom'])) {

?>	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr><?php

        foreach($plugin['custom'] as $custom_key => $custom_field) {

?>
    <tr>
        <td class="chatlist"><?php echo $BLM['shopprod_custom_field'].str_replace('shop_field_', ' ', $custom_key) ?>:&nbsp;</td>
        <td class="v12 tdbottom3"><?php echo nl2br( html($custom_field) ) ?>&nbsp;</td>
    </tr>

<?php
        }
    }

    $plugin['data']['currency'] = ' '.html( _getConfig( 'shop_pref_currency' ) );
    $plugin['data']['weight_unit'] = ' '.html( _getConfig( 'shop_pref_unit_weight' ) );

?>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td></tr>

    <tr>
        <td class="chatlist"><?php echo $BLM['shopprod_ordered'] ?>:&nbsp;</td>
        <td><table cellpadding="0" cellspacing="0" border="0" summary="" width="100%">

        <tr>
            <th><?php echo $BLM['shopprod_quantity'] ?></th>
            <th><?php echo $BLM['th_ordnr'] ?></th>
            <th><?php echo $BLM['shopprod_name1'] ?></th>
            <th class="right"><?php echo $BLM['shopprod_net'].' '.$plugin['data']['currency'] ?></th>
            <th class="right"><?php echo $BLM['shopprod_vat'].'%' ?></th>
            <th class="right"><?php echo $BLM['shopprod_total'].' '.$plugin['data']['currency'] ?></th>
        </tr>

<?php

    $_controller_link =  shop_url('controller=prod');

    foreach($plugin['data']['order_data']['cart'] as $plugin['product']) {

        $plugin['vat_factor'] = 1 + ( $plugin['product']['shopprod_vat'] / 100 );

        if($plugin['product']['shopprod_size'] && ($_cart_opt_1 = explode(LF, $plugin['product']['shopprod_size']))) {
            foreach($_cart_opt_1 as $key => $value){
                //title
                if(!$key) {
                    unset($_cart_opt_1[$key]);
                    continue;
                }
                $_cart_opt_1[$key] = get_shop_option_value($value);
            }
        } else {
            $_cart_opt_1 = null;
        }
        if($plugin['product']['shopprod_color'] && ($_cart_opt_2 = explode(LF, $plugin['product']['shopprod_color']))) {
            foreach($_cart_opt_2 as $key => $value){
                //title
                if(!$key) {
                    unset($_cart_opt_2[$key]);
                    continue;
                }
                $_cart_opt_2[$key] = get_shop_option_value($value);
            }
        } else {
            $_cart_opt_2 = null;
        }

        //loop all opt_1
        if(!isset($plugin['product']['shopprod_quantity'])) {
            $plugin['product']['shopprod_quantity'] = 1;
        }
        if(!is_array($plugin['product']['shopprod_quantity'])) {
            $plugin['product']['shopprod_quantity'] = array(array($plugin['product']['shopprod_quantity']));
        }

        if($plugin['product']['shopprod_quantity']) {
            foreach($plugin['product']['shopprod_quantity'] as $key => $idval) {

                //loop all opt_2
                if(!$plugin['product']['shopprod_quantity'][$key]) {
                    continue;
                }

                foreach($plugin['product']['shopprod_quantity'][$key] as $k => $v) {

                    //opt_1
                    if(isset($_cart_opt_1[$key][1])) {
                        if ($_cart_opt_1[$key]['type'] === '=') {
                            $plugin['product']['shopprod_price'] = $_cart_opt_1[$key][1];
                            $value_opt1_float = 0;
                        } else {
                            $value_opt1_float = $_cart_opt_1[$key][1];
                        }
                        $opt1_txt = $_cart_opt_1[$key]['option'];
                        $opt1_numbr = $_cart_opt_1[$key][2];
                    } else {
                        $opt1_txt = '';
                        $opt1_numbr = '';
                        $value_opt1_float = 0;
                    }

                    //opt_2
                    if(isset($_cart_opt_2[$key][1])) {
                        if ($_cart_opt_2[$key]['type'] === '=') {
                            $plugin['product']['shopprod_price'] = $_cart_opt_2[$key][1];
                            $value_opt2_float = 0;
                        } else {
                            $value_opt2_float = $_cart_opt_2[$key][1];
                        }
                        $opt2_txt = $_cart_opt_2[$key]['option'];
                        $opt2_numbr = $_cart_opt_2[$key][2];
                    } else {
                        $opt2_txt = '';
                        $opt2_numbr = '';
                        $value_opt2_float = 0;
                    }

                    $pluginshopprod_price = $plugin['product']['shopprod_price'] + $value_opt1_float + $value_opt2_float;

                    if($plugin['product']['shopprod_netgross'] == 1) {
                        $plugin['price_net']	= $pluginshopprod_price / $plugin['vat_factor'];
                        $plugin['price_gross']	= $pluginshopprod_price;
                    } else {
                        $plugin['price_net']	= $pluginshopprod_price;
                        $plugin['price_gross']	= $pluginshopprod_price * $plugin['vat_factor'];
                    }
                    $plugin['price_vat']		= $plugin['price_gross'] - $plugin['price_net'];

                    $pluginshopprod_numbr = $plugin['product']['shopprod_ordernumber'].$opt1_numbr.$opt2_numbr;

                    ?>
                    <tr class="product">
                        <td><?php echo $v ?></td>
                        <td><?php echo html_specialchars($pluginshopprod_numbr) ?></td>
                        <td>
                            <a href="<?php echo $_controller_link.'&amp;edit='.$plugin['product']["shopprod_id"] ?>" target="_blank"><?php echo html($plugin['product']['shopprod_name1']); ?></a>
                            <?php if(($opt1_txt.$opt2_txt)) { echo '<br />' . html(trim($opt1_txt.' '.$opt2_txt)); } ?>
                        </td>
                        <td class="number"><?php echo number_format($plugin['price_net'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
                        <td class="number"><?php echo number_format($plugin['product']['shopprod_vat'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
                        <td class="number"><?php echo number_format($v * $plugin['price_net'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
                    </tr>
                    <?php
                }
            }
        }
    }

    if(isset($plugin['data']['order_data']['subtotal'])) {

        $plugin['data']['order_data']['subtotal']['vat'] = $plugin['data']['order_data']['subtotal']['subtotal_gross'] - $plugin['data']['order_data']['subtotal']['subtotal_net'];

?>
        <tr class="product linetop">
            <td colspan="3" class="chatlist"><?php echo $BLM['shopprod_subtotal'].' '.$plugin['data']['currency'] ?>:</td>
            <td class="number"><?php echo number_format($plugin['data']['order_data']['subtotal']['subtotal_net'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
            <td class="number"><?php echo number_format($plugin['data']['order_data']['subtotal']['vat'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
            <td class="number"><?php echo number_format($plugin['data']['order_data']['subtotal']['subtotal_gross'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
        </tr>

        <tr class="product linebottom">
            <td colspan="3" class="chatlist"><?php

            $_shipping_info = array();

            if(isset($plugin['data']['order_data']['shipping']['shipping_distance'])) {
                if(isset($plugin['data']['order_data']['distance']['label']) && $plugin['data']['order_data']['distance']['label'] !== '') {
                    $_shipping_info['zone'] = html($plugin['data']['order_data']['distance']['label']);
                }
                $_shipping_info['distance'] = number_format(($plugin['data']['order_data']['shipping']['shipping_distance']/1000), 1, $BLM['dec_point'], $BLM['thousands_sep']).' km';
            }
            if(!empty($plugin['data']['order_data']['weight'])) {
                $_shipping_info['weight']  = $BLM['shopprod_weight'].' ';
                $_shipping_info['weight'] .= number_format($plugin['data']['order_data']['weight'], 0, $BLM['dec_point'], $BLM['thousands_sep']);
                $_shipping_info['weight'] .= ' '.$plugin['data']['weight_unit'];
            }

            echo $BLM['shopprod_shipping'].' ';

            if($_shipping_info) {
                echo '(' . implode(' / ', $_shipping_info) . ') ';
            }

            echo $plugin['data']['currency'];

            $plugin['data']['order_data']['shipping']['vat'] = $plugin['data']['order_data']['shipping']['shipping_gross'] - $plugin['data']['order_data']['shipping']['shipping_net'];

            ?>:</td>
            <td class="number"><?php echo number_format($plugin['data']['order_data']['shipping']['shipping_net'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
            <td class="number"><?php echo number_format($plugin['data']['order_data']['shipping']['vat'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
            <td class="number"><?php echo number_format($plugin['data']['order_data']['shipping']['shipping_gross'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
        </tr>

<?php	if(isset($plugin['data']['order_data']['discount'])):

            $plugin['data']['order_data']['discount']['vat'] = $plugin['data']['order_data']['discount']['discount_gross'] - $plugin['data']['order_data']['discount']['discount_net'];
            $plugin['data']['shop_pref_discount'] = _getConfig('shop_pref_discount');
?>
        <tr class="product linebottom">
            <td colspan="3" class="chatlist"><?php echo $BLM['shopprod_discount'].' '.number_format($plugin['data']['shop_pref_discount']['percent'], 2, $BLM['dec_point'], $BLM['thousands_sep'] ) ?>%:</td>
            <td class="number">-<?php echo number_format($plugin['data']['order_data']['discount']['discount_net'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
            <td class="number">-<?php echo number_format($plugin['data']['order_data']['discount']['vat'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
            <td class="number">-<?php echo number_format($plugin['data']['order_data']['discount']['discount_gross'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
        </tr>

<?php
        endif;
        if(isset($plugin['data']['order_data']['loworder'])):

            $plugin['data']['order_data']['loworder']['vat'] = $plugin['data']['order_data']['loworder']['loworder_gross'] - $plugin['data']['order_data']['loworder']['loworder_net'];
?>
        <tr class="product linebottom">
            <td colspan="3" class="chatlist"><?php echo $BLM['shopprod_loworder'].' '.$plugin['data']['currency'] ?>:</td>
            <td class="number"><?php echo number_format($plugin['data']['order_data']['loworder']['loworder_net'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
            <td class="number"><?php echo number_format($plugin['data']['order_data']['loworder']['vat'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
            <td class="number"><?php echo number_format($plugin['data']['order_data']['loworder']['loworder_gross'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
        </tr>
<?php
        endif;

    }

?>
        <tr class="product total">
            <td colspan="3" class="chatlist"><?php echo $BLM['shopprod_total_net'].' '.$plugin['data']['currency'] ?>:&nbsp;</td>
            <td colspan="3" class="v12 number"><?php echo number_format($plugin['data']['order_net'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
        </tr>
        <tr class="product total">
            <td colspan="3" class="chatlist"><?php echo $BLM['shopprod_total_vat'].' '.$plugin['data']['currency'] ?>:&nbsp;</td>
            <td colspan="3" class="v12 number"><?php echo number_format($plugin['data']['order_gross'] - $plugin['data']['order_net'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></td>
        </tr>
        <tr class="product total end">
            <td colspan="3" class="chatlist"><?php echo $BLM['shopprod_total_gross'].' '.$plugin['data']['currency'] ?>:&nbsp;</td>
            <td colspan="3" class="v12 number"><b><?php echo number_format($plugin['data']['order_gross'], 2, $BLM['dec_point'], $BLM['thousands_sep']); ?></b></td>
        </tr>

        </table></td>
    </tr>

    <tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="20" /></td></tr>

    <tr>
        <td class="chatlist" style="padding-top:7px;"><?php echo $BLM['shopprod_email_customer'] ?>:&nbsp;</td>
        <td class="tdbottom5 email"><pre><?php

            // only a slight fix
            if(PHPWCMS_CHARSET !== 'utf-8' && strpos($plugin['data']['order_data']['mail_customer'], '�') !== false) {
                $plugin['data']['order_data']['mail_customer'] = mb_convert_encoding($plugin['data']['order_data']['mail_customer'], PHPWCMS_CHARSET, 'utf-8');
            }

            echo html($plugin['data']['order_data']['mail_customer']);
        ?></pre></td>
    </tr>

<?php if(!empty($plugin['data']['order_data']['mail_self'])) { ?>

    <tr>
        <td class="chatlist" style="padding-top:7px;"><?php echo $BLM['shopprod_email_shop'] ?>:&nbsp;</td>
        <td class="email"><pre><?php

            // only a slight fix
            if(PHPWCMS_CHARSET !== 'utf-8' && strpos($plugin['data']['order_data']['mail_self'], '�') !== false) {
                $plugin['data']['order_data']['mail_self'] = mb_convert_encoding($plugin['data']['order_data']['mail_self'], PHPWCMS_CHARSET, 'utf-8');
            }

            echo html($plugin['data']['order_data']['mail_self']);

        ?></pre></td>
    </tr>

<?php } ?>

</table>

</div>

<input type="button" class="button" style="margin-top:5px;" value="<?php echo $BL['be_func_struct_close'] ?>" onclick="document.location.href='<?php echo shop_url('controller=order') ?>'" />
