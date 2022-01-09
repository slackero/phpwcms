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
<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="" class="shop">

    <tr>
        <th>&nbsp;</th>
        <th>&nbsp;<?php echo $BLM['th_ordnr'] ?></th>
        <th>&nbsp;&nbsp;<?php echo $BLM['th_date'] ?></th>
        <th>&nbsp;&nbsp;<?php echo $BLM['th_customer'] ?></th>
        <th>&nbsp;&nbsp;<?php echo $BLM['th_net'] ?>&nbsp;</th>
        <th>&nbsp;&nbsp;<?php echo $BLM['th_gross'] ?>&nbsp;</th>
        <th>&nbsp;<?php echo $BLM['th_payment'] ?></th>
        <th>&nbsp;&nbsp;&nbsp;</th>
    </tr>


<?php

// loop listing available orders
$BLM['shopprod_payby_INVOICE'] = $BLM['shopprod_payby_onbill'];

$sql  = "SELECT *, DATE_FORMAT(order_date,'%d.%m.%Y') AS order_fdate FROM ".DB_PREPEND."phpwcms_shop_orders WHERE ";
$sql .= "order_status NOT IN ('ARCHIVED', 'CLOSED') ORDER BY order_date DESC";

$data = _dbQuery($sql);

$_controller_link =  shop_url('controller=order');

if($data) {

    foreach($data as $key => $row) {

        echo '<tr'.( ($key % 2) ? ' class="adsAltRow"' : '' ).'>'.LF;

        echo '<td width="25" style="padding:2px 3px 2px 4px;">';

        echo '<a href="'.$_controller_link.'&amp;show='.$row["order_id"].'">';
        echo '<img src="img/famfamfam/cart_go.gif" alt="'.$BLM['shop_order'].'" border="0" />';
        echo '</a></td>'.LF;

        echo '<td class="dir nowrap" width="13%">';

        if(SHOP_FELANG_SUPPORT) {
            $row['order_data']		= @unserialize($row['order_data']);
            $row['shopprod_lang']	= empty($row['order_data']['lang']) ? '' : html_specialchars(strtolower($row['order_data']['lang']));
            echo '<img src="img/famfamfam/lang/'.($row['shopprod_lang'] ? $row['shopprod_lang'] : 'all').'.png" alt="'.$row['shopprod_lang'].'" style="position:relative;top:1px;margin:0 3px 0 3px;" />';
        }

        echo html_specialchars($row['order_number'])."&nbsp;</td>\n";
        echo '<td class="dir" align="right" width="13%">&nbsp;'.html_specialchars($row['order_fdate'])."&nbsp;</td>\n";
        echo '<td class="dir nowrap" width="50%">&nbsp;<a href="mailto:'.$row['order_email'].'?subject='.rawurlencode($BLM['shopprod_order_subject'].' #'.$row['order_number']).'">';
        echo html_specialchars($row['order_firstname'].' '.$row['order_name'])."</a>&nbsp;</td>\n";

        echo '<td class="dir listNumber" width="10%">'.html_specialchars( number_format( round($row['order_net'], 2) , 2, $BLM['dec_point'], $BLM['thousands_sep'] ) )."&nbsp;</td>\n";
        echo '<td class="dir listNumber" width="10%">'.html_specialchars( number_format( round($row['order_gross'], 2) , 2, $BLM['dec_point'], $BLM['thousands_sep'] ) )."&nbsp;</td>\n";
        echo '<td class="dir" width="10%">'.(empty($row['order_payment']) ? '-' : html_specialchars($BLM[ 'shopprod_payby_'.$row['order_payment'] ]))."&nbsp;&nbsp;</td>\n";

        echo '<td width="5%" align="right" class="button_td nowrap">';

        echo '<a href="'.$_controller_link.'&amp;delete='.$row["order_id"].'" title="'.$BL['be_cnt_delete'].': '.html($row['order_number']).'"';
        echo ' onclick="return confirm(\''.$BLM['delete_order'].js_singlequote($row['order_number']).'\');">';
        echo '<img src="img/button/trash_13x13_1.gif" border="0" alt="" /></a>';

        echo '</td>'.LF;
        echo '</tr>'.LF;

    }

} else {
    echo '<tr><td colspan="8" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>';
}

?>
</table>