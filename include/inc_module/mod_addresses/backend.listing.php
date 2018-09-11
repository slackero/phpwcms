<?php

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


$_entry['query']            = '';

// create pagination
if(isset($_GET['c'])) {
    $_SESSION['list_user_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
    $_SESSION['userdetail_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
    $_SESSION['list_user_count'] = 25;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

    $_SESSION['list_active']    = empty($_POST['showactive']) ? 0 : 1;
    $_SESSION['list_inactive']  = empty($_POST['showinactive']) ? 0 : 1;

    $_SESSION['filter']         = clean_slweg($_POST['filter']);
    if(empty($_SESSION['filter'])) {
        unset($_SESSION['filter']);
    } else {
        $_SESSION['filter'] = convertStringToArray($_SESSION['filter'], ' ');
        $_POST['filter']    = $_SESSION['filter'];
    }

    $_SESSION['userdetail_page'] = intval($_POST['page']);

    $_SESSION['filter_country'] = $_POST['filter_country'];

}

if(empty($_SESSION['userdetail_page'])) {
    $_SESSION['userdetail_page'] = 1;
}

$_entry['list_active']      = isset($_SESSION['list_active'])   ? $_SESSION['list_active']      : 1;
$_entry['list_inactive']    = isset($_SESSION['list_inactive']) ? $_SESSION['list_inactive']    : 1;


// set correct status query
if($_entry['list_active'] != $_entry['list_inactive']) {

    if(!$_entry['list_active']) {
        $_entry['query'] .= 'detail_aktiv=0';
    }
    if(!$_entry['list_inactive']) {
        $_entry['query'] .= 'detail_aktiv=1';
    }

} else {
    $_entry['query'] .= 'detail_aktiv!=9';
}
$_entry['query'] .= ' AND detail_pid=0';
$_entry['query'] .= " AND detail_regkey="._dbEscape(MODULE_KEY);


if(isset($_SESSION['filter']) && is_array($_SESSION['filter']) && count($_SESSION['filter'])) {

    $_entry['filter_array'] = array();

    foreach($_SESSION['filter'] as $_entry['filter']) {
        //usr_name, usr_login, usr_email
        $_entry['filter_array'][] = "CONCAT(    detail_firstname, detail_lastname,
                                                detail_company, detail_city, detail_zip,
                                                detail_street, detail_add, detail_email
                                            ) LIKE '%".aporeplace($_entry['filter'])."%'";
    }
    if(count($_entry['filter_array'])) {

        $_SESSION['filter'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
        $_entry['query'] .= $_SESSION['filter'];

    }

} elseif(isset($_SESSION['filter']) && is_string($_SESSION['filter'])) {

    $_entry['query'] .= $_SESSION['filter'];

}

if(isset($_SESSION['filter_country']) && $_SESSION['filter_country'] != '-') {
    $_entry['query'] .= " AND detail_country='".aporeplace($_SESSION['filter_country'])."'";
}

// paginating values
$sql = 'SELECT * FROM '.DB_PREPEND.'cmsgo_userdetail WHERE '.$_entry['query'];
$_entry['count_total'] = _dbQuery($sql, 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['userdetail_page'] > $_entry['pages_total']) {
    $_SESSION['userdetail_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}



?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['listing_title'] ?></h1>

<div class="navBarLeft imgButton chatlist">
    <a href="<?php echo MODULE_HREF ?>&amp;edit=0" title="<?php echo $BLM['create_new'] ?>"><img src="<?php echo MODULE_BASEPATH ?>template/image/vcard_add.gif" alt="Add" border="0" /><span><?php echo $BLM['create_new'] ?></span></a>
</div>


<form action="<?php echo MODULE_HREF ?>" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />
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
    if($_SESSION['userdetail_page'] > 1) {
        echo '<a href="'.MODULE_HREF.'&amp;page='.($_SESSION['userdetail_page']-1).'">';
        echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" /></a>';
    } else {
        echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" class="inactive" />';
    }
    echo '</td>';
    echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['userdetail_page'];
    echo '"  class="textinput" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
    echo '<td class="chatlist">/'.$_entry['pages_total'].'&nbsp;</td>';
    echo '<td>';
    if($_SESSION['userdetail_page'] < $_entry['pages_total']) {
        echo '<a href="'.MODULE_HREF.'&amp;page='.($_SESSION['userdetail_page']+1).'">';
        echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" /></a>';
    } else {
        echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" class="inactive" />';
    }
    echo '</td><td class="chatlist">&nbsp;|&nbsp;</td>';

} else {

    echo '<td class="chatlist">|&nbsp;<input type="hidden" name="page" id="page" value="1" /></td>';

}
?>
                <td><input type="text" name="filter" id="filter" size="10" value="<?php

                if(isset($_POST['filter']) && is_array($_POST['filter']) ) {
                    echo html_specialchars(implode(' ', $_POST['filter']));
                }

                ?>" class="textinput width100" style="margin:0 2px 0 0;text-align:left;" title="filter results by username, name or email" /></td>
                <td>&nbsp;</td>

                <td><select name="filter_country" id="filter_country" class="drop width100" onchange="this.form.submit();">
<option value="-">- <?php echo $BLM['detail_country'] ?> -</option>
<?php
    echo list_country( isset($_SESSION['filter_country']) ? $_SESSION['filter_country'] : '-'  );
?>
                </select></td>
                <td>&nbsp;</td>
                <td><input type="image" name="gofilter" src="img/famfamfam/action_go.gif" style="margin-right:3px;" /></td>


            </tr>
        </table></td>

    <td class="chatlist" align="right">
        <a href="<?php echo MODULE_HREF ?>&amp;c=10">10</a>
        <a href="<?php echo MODULE_HREF ?>&amp;c=25">25</a>
        <a href="<?php echo MODULE_HREF ?>&amp;c=50">50</a>
        <a href="<?php echo MODULE_HREF ?>&amp;c=100">100</a>
        <a href="<?php echo MODULE_HREF ?>&amp;c=all"><?php echo $BL['be_ftptakeover_all'] ?></a>
    </td>

    </tr>
</table>
</form>

<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">

    <tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>

    <tr>
        <th>&nbsp;</th>
        <th class="navtext">&nbsp;<?php echo $BLM['detail_company'].'/'.$BLM['detail_lastname'] ?></th>
        <th class="navtext"><?php echo $BLM['detail_city'] ?></th>
        <th class="navtext">C&nbsp;</th>
        <th class="navtext">S&nbsp;</th>
        <th>&nbsp;</th>
    </tr>


    <tr><td colspan="6" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>


<?php
// loop listing available newsletters
$row_count = 0;

$sql  = 'SELECT detail_id, detail_company, detail_firstname, detail_lastname, detail_city, detail_zip, detail_country, detail_int2, detail_aktiv FROM '.DB_PREPEND.'cmsgo_userdetail WHERE '.$_entry['query'].' ';
$sql .= 'ORDER BY detail_company, detail_city, detail_country ';
$sql .= 'LIMIT '.(($_SESSION['userdetail_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
$data = _dbQuery($sql);

foreach($data as $row) {

    $row['listname'] = trim($row["detail_company"] . ', ' . trim($row["detail_firstname"] . ' ' . $row["detail_lastname"]), ', ');

    echo '<tr style="cursor:pointer"'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' );
    echo ' onclick="document.location=\''.MODULE_HREF.'&amp;edit='.$row["detail_id"].'\';">';
    echo '<td width="25" style="padding:2px 3px 2px 4px;">';
    echo '<img src="' . MODULE_BASEPATH . 'template/image/vcard.gif" alt="'.$BLM['dealer_entry'].'" /></td>';
    echo '<td class="dir" width="50%" style="padding-left:3px">'.html($row['listname'])."</td>";
    echo '<td class="dir" width="40%">'.html($row["detail_city"].($row["detail_zip"]?', '.$row["detail_zip"]:''))."&nbsp;</td>";
    echo '<td class="dir" width="4%">'.html($row["detail_country"])."&nbsp;</td>";
    echo '<td class="dir" width="1%">'.(intval($row["detail_int2"]) ? $row["detail_int2"] : '')."&nbsp;</td>";
    echo '<td width="10%" align="right" nowrap="nowrap" class="button_td">';
    echo '<a href="'.MODULE_HREF.'&amp;edit='.$row["detail_id"].'">';
    echo '<img src="img/button/edit_22x13.gif" border="0" alt="" /></a>';
    echo '<a href="'.MODULE_HREF.'&amp;editid='.$row["detail_id"].'&amp;verify=';
    echo (($row["detail_aktiv"]) ? '0' : '1').'">';
    echo '<img src="img/button/aktiv_12x13_'.$row["detail_aktiv"].'.gif" border="0" alt="" /></a>';
    echo '<a href="'.MODULE_HREF.'&amp;delete='.$row["detail_id"];
    echo '" title="delete: '.html($row['listname']).'"';
    echo ' onclick="return confirm(\''.$BLM['delete_entry'].' '.js_singlequote($row["detail_company"]).'\');">';
    echo '<img src="img/button/trash_13x13_1.gif" border="0" alt="" /></a>';
    echo "</td></tr>";

    $row_count++;
}

if($row_count) {
    echo '<tr><td colspan="6" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>';
}

?>

    <tr><td colspan="6"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>
</table>