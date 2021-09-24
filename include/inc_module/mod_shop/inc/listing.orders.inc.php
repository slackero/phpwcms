<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2021, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
    die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

?>
<div class="table-responsive">
<table class="table table-sm mb-0">
    <tr bgcolor="#f3f3f3">
        <th><?php echo $BLM['th_ordnr'] ?></th>
        <th><?php echo $BLM['th_date'] ?></th>
        <th><?php echo $BLM['th_customer'] ?></th>
        <th><?php echo $BLM['th_net'] ?>&nbsp;</th>
        <th><?php echo $BLM['th_gross'] ?>&nbsp;</th>
        <th><?php echo $BLM['th_payment'] ?></th>
        <th></th>
    </tr>

<?php

// loop listing available orders
$BLM['shopprod_payby_INVOICE'] = $BLM['shopprod_payby_onbill'];
$sql  = "SELECT *, DATE_FORMAT(order_date,'%d.%m.%Y') AS order_fdate FROM ".DB_PREPEND."cmsgo_shop_orders WHERE ";
$sql .= "order_status NOT IN ('ARCHIVED', 'CLOSED') ORDER BY order_date DESC";
$data = _dbQuery($sql);
$_controller_link =  shop_url('controller=order');
if($data) {
    foreach($data as $key => $row) {
        echo '<tr'.( ($key % 2) ? ' class="adsAltRow"' : '' ).'>'.LF;

        echo '<td class="dir nowrap" width="13%">';

        if(SHOP_FELANG_SUPPORT) {
            $row['order_data']		= @unserialize($row['order_data']);
            $row['shopprod_lang']	= empty($row['order_data']['lang']) ? '' : html_specialchars(strtolower($row['order_data']['lang']));
            echo '<span class="mr-2 flag-icon flag-icon-'.($row['shopprod_lang'] ? $row['shopprod_lang'] : ' fa fa-globe').' mt-1" data-toggle="tooltip" title="'.$row['shopprod_lang'].'"></span>';
        }

        echo html_specialchars($row['order_number'])."&nbsp;</td>\n";
        echo '<td class="dir" width="13%">'.html_specialchars($row['order_fdate'])."</td>\n";
        echo '<td class="dir nowrap">';
        echo html_specialchars($row['order_firstname'].' '.$row['order_name'])."</td>\n";

        echo '<td class="dir listNumber" width="10%">'.html_specialchars( number_format( round($row['order_net'], 2) , 2, $BLM['dec_point'], $BLM['thousands_sep'] ) )."&nbsp;</td>\n";
        echo '<td class="dir listNumber" width="10%">'.html_specialchars( number_format( round($row['order_gross'], 2) , 2, $BLM['dec_point'], $BLM['thousands_sep'] ) )."&nbsp;</td>\n";
        echo '<td class="dir" width="10%">'.(empty($row['order_payment']) ? '-' : html_specialchars($BLM[ 'shopprod_payby_'.$row['order_payment'] ]))."&nbsp;&nbsp;</td>\n";

        echo '<td class="text-right text-nowrap" width="15%">';
        echo '<a class="btn btn-sm btn-blue" href="'.$_controller_link.'&amp;show='.$row["order_id"].'" data-toggle="tooltip" title="'.$BLM['order_edit'].'">';
        echo '<i class="fa fa-pencil-alt fa-fw"></i>';
        echo '</a>'.LF;

        echo '<a class="btn btn-sm btn-blue" href="mailto:'.$row['order_email'].'?subject='.rawurlencode($BLM['shopprod_order_subject'].' #'.$row['order_number']).'" data-toggle="tooltip" title="'.$BLM['shopprod_email_customer'].'">';
        echo '<i class="fa fa-envelope fa-fw"></i>';
        echo '</a>'.LF;


        echo '<a class="btn btn-sm btn-danger" href="'.$_controller_link.'&amp;delete='.$row["order_id"].'" data-toggle="tooltip" title="'.$BL['be_cnt_delete'].': '.html($row['order_number']).'"';
        echo ' onclick="return confirm(\''.$BLM['delete_order'].js_singlequote($row['order_number']).'\');">';
        echo '<i class="far fa-trash-alt fa-fw"></i></a>';

        echo '</td>'.LF;
        echo '</tr>'.LF;
    }
}

?>
</table>
</div>
