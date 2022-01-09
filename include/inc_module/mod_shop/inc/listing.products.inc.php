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

$_entry['query']			= '';

// create pagination
if(isset($_GET['c'])) {
    $_SESSION['list_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
    $_SESSION['detail_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_count'])) {
    $_SESSION['list_count'] = 25;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

    $_SESSION['list_active']	= empty($_POST['showactive']) ? 0 : 1;
    $_SESSION['list_inactive']	= empty($_POST['showinactive']) ? 0 : 1;

    $_SESSION['filter_shop_products'] = clean_slweg($_POST['filter']);
    if(empty($_SESSION['filter_shop_products'])) {
        unset($_SESSION['filter_shop_products']);
    } else {
        $_SESSION['filter_shop_products'] = convertStringToArray($_SESSION['filter_shop_products'], ' ');
        $_POST['filter'] = $_SESSION['filter_shop_products'];
    }

    $_SESSION['detail_page'] = intval($_POST['page']);

}

if(empty($_SESSION['detail_page'])) {
    $_SESSION['detail_page'] = 1;
}

$_entry['list_active']		= isset($_SESSION['list_active'])	? $_SESSION['list_active']		: 1;
$_entry['list_inactive']	= isset($_SESSION['list_inactive'])	? $_SESSION['list_inactive']	: 1;


// set correct status query
if($_entry['list_active'] != $_entry['list_inactive']) {

    if(!$_entry['list_active']) {
        $_entry['query'] .= 'shopprod_status=0';
    }
    if(!$_entry['list_inactive']) {
        $_entry['query'] .= 'shopprod_status=1';
    }

} else {
    $_entry['query'] .= 'shopprod_status!=9';
}

if(isset($_SESSION['filter_shop_products']) && is_array($_SESSION['filter_shop_products']) && count($_SESSION['filter_shop_products'])) {

    $_entry['filter_array'] = array();

    foreach($_SESSION['filter_shop_products'] as $_entry['filter']) {
        //usr_name, usr_login, usr_email
        $_entry['filter_array'][] = "CONCAT(
            shopprod_ordernumber, 	shopprod_model,			shopprod_name1,
            shopprod_name2,			shopprod_tag,			(shopprod_price+' '),
            shopprod_description1,	shopprod_description2,	shopprod_description3
        ) LIKE '%".aporeplace($_entry['filter'])."%'";
    }
    if(count($_entry['filter_array'])) {

        $_SESSION['filter_shop_products'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
        $_entry['query'] .= $_SESSION['filter_shop_products'];

    }

} elseif(isset($_SESSION['filter_shop_products']) && is_string($_SESSION['filter_shop_products'])) {

    $_entry['query'] .= $_SESSION['filter_shop_products'];

}

// paginating values
$_entry['count_total'] = _dbCount('SELECT COUNT(shopprod_id) FROM '.DB_PREPEND.'phpwcms_shop_products WHERE '.$_entry['query']);
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_count']);
if($_SESSION['detail_page'] > $_entry['pages_total']) {
    $_SESSION['detail_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}

?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['listing_title'] ?></h1>

<div class="navBarLeft imgButton chatlist">
    &nbsp;&nbsp;
    <a href="<?php echo shop_url(array('controller=prod', 'edit=0')) ?>" title="<?php echo $BLM['create_new_prod'] ?>"><img src="img/famfamfam/package_add.gif" alt="Add" border="0" /><span><?php echo $BLM['create_new_prod'] ?></span></a>
</div>


<form action="<?php echo shop_url('controller=prod') ?>" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="paginate" summary="">
    <tr>
        <td><table border="0" cellpadding="0" cellspacing="0" summary="">
            <tr>

                <td><input type="checkbox" name="showactive" id="showactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_active'], 1) ?> /></td>
                <td><label for="showactive"><img src="img/button/aktiv_12x13_1.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>
                <td><input type="checkbox" name="showinactive" id="showinactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_inactive'], 1) ?> /></td>
                <td><label for="showinactive"><img src="img/button/aktiv_12x13_0.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>

<?php
if($_entry['pages_total'] > 1) {

    echo '<td class="chatlist">|&nbsp;</td>';
    echo '<td>';
    if($_SESSION['detail_page'] > 1) {
        echo '<a href="'. shop_url( array('controller=prod', 'page='.($_SESSION['detail_page']-1)) ) . '">';
        echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" /></a>';
    } else {
        echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" class="inactive" />';
    }
    echo '</td>';
    echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['detail_page'];
    echo '"  class="textinput" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
    echo '<td class="chatlist">/'.$_entry['pages_total'].'&nbsp;</td>';
    echo '<td>';
    if($_SESSION['detail_page'] < $_entry['pages_total']) {
        echo '<a href="'.shop_url( array('controller=prod', 'page='.($_SESSION['detail_page']+1)) ) .'">';
        echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" /></a>';
    } else {
        echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" class="inactive" />';
    }
    echo '</td><td class="chatlist">&nbsp;|&nbsp;</td>';

} else {

    echo '<td class="chatlist">|&nbsp;<input type="hidden" name="page" id="page" value="1" /></td>';

}
?>
                <td><input type="search" name="filter" id="filter" size="10" value="<?php

                if(isset($_POST['filter']) && is_array($_POST['filter']) ) {
                    echo html_specialchars(implode(' ', $_POST['filter']));
                }

                ?>" class="textinput" style="margin:0 2px 0 0;width:110px;text-align:left;" title="filter results by username, name or email" /></td>
                <td><input type="image" name="gofilter" src="img/famfamfam/action_go.gif" style="margin-right:3px;" /></td>

            </tr>
        </table></td>

    <td class="chatlist" align="right">
        <a href="<?php echo shop_url(array('controller=prod', 'c=10')) ?>">10</a>
        <a href="<?php echo shop_url(array('controller=prod', 'c=25')) ?>">25</a>
        <a href="<?php echo shop_url(array('controller=prod', 'c=50')) ?>">50</a>
        <a href="<?php echo shop_url(array('controller=prod', 'c=100')) ?>">100</a>
        <a href="<?php echo shop_url(array('controller=prod', 'c=250')) ?>">250</a>
        <a href="<?php echo shop_url(array('controller=prod', 'c=all')) ?>"><?php echo $BL['be_ftptakeover_all'] ?></a>
    </td>

    </tr>
</table>
</form>

<table width="700" border="0" cellpadding="0" cellspacing="0" summary="" class="shop">

    <tr>
        <th>&nbsp;</th>
        <th>&nbsp;<?php echo $BLM['th_ordnr'] ?></th>
        <th>&nbsp;<?php echo $BLM['th_modnr'] ?></th>
        <th>&nbsp;<?php echo $BLM['th_product'] ?></th>
        <th style="text-align:right;padding-right:5px;">&nbsp;<?php echo $BLM['th_price'] ?>&nbsp;</th>
        <th style="text-align:right;padding-right:5px;">&nbsp;<?php echo $BLM['shopprod_inventory'] ?>&nbsp;</th>
        <th>&nbsp;</th>
    </tr>


<?php
// loop listing available newsletters
$row_count = 0;

$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_shop_products WHERE '.$_entry['query'].' ';
$sql .= 'LIMIT '.(($_SESSION['detail_page']-1) * $_SESSION['list_count']).','.$_SESSION['list_count'];

$data = _dbQuery($sql);

if($data) {

    $_controller_link =  shop_url('controller=prod');

    foreach($data as $row) {

        echo '<tr'.( ($row_count % 2) ? ' class="adsAltRow"' : '' ).'>'.LF;

        echo '<td width="25" style="padding:2px 3px 2px 4px;">';
        echo '<img src="img/famfamfam/package.gif" alt="'.$BLM['shop_product'].'" /></td>'.LF;

        echo '<td class="dir">';
        if(SHOP_FELANG_SUPPORT) {
            $row['shopprod_lang'] = html_specialchars(strtolower($row['shopprod_lang']));
            echo '<img src="img/famfamfam/lang/'.($row['shopprod_lang'] ? $row['shopprod_lang'] : 'all').'.png" alt="'.$row['shopprod_lang'].'" />';
        }
        echo '&nbsp;' . html_specialchars($row['shopprod_ordernumber']) . "</td>\n";
        echo '<td class="dir">&nbsp;'.html_specialchars($row['shopprod_model'])."</td>\n";
        echo '<td class="dir">&nbsp;'.html_specialchars($row['shopprod_name1'])."</td>\n";
        echo '<td class="dir listNumber">&nbsp;'.html_specialchars( number_format( round($row['shopprod_price'], 2) , 2, $BLM['dec_point'], $BLM['thousands_sep'] ) )."&nbsp;</td>\n";
        echo '<td class="dir listNumber">&nbsp;'.$row['shopprod_inventory']."&nbsp;</td>\n";

        echo '<td align="right" nowrap="nowrap" class="nowrap button_td">';

            echo '<a href="'.$_controller_link.'&amp;edit='.$row["shopprod_id"].'">';
            echo '<img src="img/button/edit_22x13.gif" border="0" alt="" /></a>';

            echo '<a href="'.$_controller_link.'&amp;status=' . $row["shopprod_id"] . '-' . $row["shopprod_status"] .'">';
            echo '<img src="img/button/aktiv_12x13_'.$row["shopprod_status"].'.gif" border="0" alt="" /></a>';

            echo '<a href="'.$_controller_link.'&amp;delete='.$row["shopprod_id"];
            echo '" title="delete: '.html_specialchars($row['shopprod_ordernumber'].' / '.$row['shopprod_name1']).'"';
            echo ' onclick="return confirm(\''.$BLM['delete_product'].js_singlequote($row['shopprod_ordernumber'].' / '.$row['shopprod_name1']).'\');">';
            echo '<img src="img/button/trash_13x13_1.gif" border="0" alt="" /></a>';

        echo '</td>'.LF;

        echo '</tr>'.LF;

        $row_count++;
    }


    echo '<tr><td colspan="7" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>';

} else {

    echo '<tr><td colspan="7" class="tdtop5">'.$BL['be_empty_search_result'].'</td></tr>';

}

?>

</table>