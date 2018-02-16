<?php
/**
 * cmsGo!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2017, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGo! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
  die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

$_entry['query']      = '';

// create pagination
if(isset($_GET['c'])) {
  $_SESSION['list_product_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
  $_SESSION['detail_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_product_count'])) {
  $_SESSION['list_product_count'] = 25;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

  $_SESSION['list_active']  = empty($_POST['showactive']) ? 0 : 1;
  $_SESSION['list_inactive']  = empty($_POST['showinactive']) ? 0 : 1;

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

$_entry['list_active']    = isset($_SESSION['list_active']) ? $_SESSION['list_active']    : 1;
$_entry['list_inactive']  = isset($_SESSION['list_inactive']) ? $_SESSION['list_inactive']  : 1;


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
    $_entry['filter_array'][] = "CONCAT(  shopprod_ordernumber,   shopprod_model,     shopprod_name1,
                        shopprod_name2,     shopprod_tag,     (shopprod_price+' '),
                        shopprod_description1,  shopprod_description2,  shopprod_description3
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
$_entry['count_total'] = _dbCount('SELECT COUNT(shopprod_id) FROM '.DB_PREPEND.'cmsgo_shop_products WHERE '.$_entry['query']);
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_product_count']);
if($_SESSION['detail_page'] > $_entry['pages_total']) {
  $_SESSION['detail_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}
?>

<div class="form-group mb-3 text-center text-sm-left">
  <a class="btn btn-sm btn-blue" href="<?php echo shop_url(array('controller=prod', 'edit=0')) ?>" title="<?php echo $BLM['create_new_prod'] ?>"><i class="fa fa-plus"></i> <span><?php echo $BLM['create_new_prod'] ?></span></a>
</div>

<form action="<?php echo shop_url('controller=prod') ?>" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />
  <div class="form-row align-items-center my-2">
		<div class="form-inline col-12 col-sm">
			<div class="input-group">
				<div class="input-group-prepend">
					<div class="input-group-text bg-success border-0">
						<input name="showactive" id="showactive" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_active'], 1) ?> />
					</div>
					<div class="input-group-text bg-danger border-0">
						 <input name="showinactive" id="showinactive" type="checkbox" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_inactive'], 1) ?> />
					</div>
				</div>
				<div class="input-group-append">
					<span class="input-group-text border-0" id="basic-addon2"><i class="fas fa-eye"></i></span>
				</div>
			</div>
		</div>

		<div class="col-12 col-sm-auto">
			<div class="input-group my-3 my-sm-0">
				<input name="filter" id="filter" size="15" data-toggle="tooltip" title="Filtern" class="form-control form-control-sm" value="<?php echo html($news->filter) ?>" type="search">
				<span class="input-group-append">
					<input class="btn btn-sm btn-secondary" name="gofilter" value="Filter" type="submit">
				</span>
			</div>
		</div>

		<div class="col-12 col-sm-auto text-right">
			<select class="form-control form-control-sm custom-select">
				<option <?php echo ($_SESSION['list_product_count'] == '') ? 'selected ' : ''; ?>><?php echo $BL['be_article_rendering'] ?></option>
				<option <?php echo ($_SESSION['list_product_count'] == '10') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=prod&amp;c=10'">10</option>
				<option <?php echo ($_SESSION['list_product_count'] == '25') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=prod&amp;c=25'">25</option>
				<option <?php echo ($_SESSION['list_product_count'] == '50') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=prod&amp;c=50'">50</option>
				<option <?php echo ($_SESSION['list_product_count'] == '100') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=prod&amp;c=100'">100</option>
				<option <?php echo ($_SESSION['list_product_count'] == '250') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=prod&amp;c=250'">250</option>
				<option <?php echo ($_SESSION['list_product_count'] == '99999') ? 'selected ' : ''; ?>onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=prod&amp;c=all'"><?php echo $BL['be_ftptakeover_all'].' '.$_entry['count_total'] ?></option>
			</select>
		</div>
  </div>
</form>
<div class ="table-responsive">
<table class="table table-sm mb-0 mt-2" border="0" >

  <tr bgcolor="#f3f3f3">
    <th>&nbsp;</th>
    <th>&nbsp;<?php echo $BLM['th_ordnr'] ?></th>
    <th>&nbsp;<?php echo $BLM['th_modnr'] ?></th>
    <th>&nbsp;<?php echo $BLM['th_product'] ?></th>
    <th style="text-align:right;padding-right:5px;">&nbsp;<?php echo $BLM['th_price'] ?>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>


<?php
// loop listing available newsletters
$row_count = 0;

$sql  = 'SELECT * FROM '.DB_PREPEND.'cmsgo_shop_products WHERE '.$_entry['query'].' ';
$sql .= 'LIMIT '.(($_SESSION['detail_page']-1) * $_SESSION['list_product_count']).','.$_SESSION['list_product_count'];

$data = _dbQuery($sql);

if($data) {

  $_controller_link =  shop_url('controller=prod');

  foreach($data as $row) {

    echo '<tr'.( ($row_count % 2) ? ' class="adsAltRow"' : '' ).'>'.LF;

    echo '<td width="25" style="padding:2px 3px 2px 4px;">';
    echo '<i class="fas fa-gift fa-fw text-blue" aria-hidden="true"></i></td>'.LF;

    echo '<td class="dir">';
    if(SHOP_FELANG_SUPPORT) {
      $row['shopprod_lang'] = html_specialchars(strtolower($row['shopprod_lang']));
      echo '<span class="flag-icon flag-icon-'.($row['shopprod_lang'] ? $row['shopprod_lang'] : ' fas fa-globe').' mt-1" data-toggle="tooltip" title="'.$row['shopprod_lang'].'"></span>';
    }
    echo '&nbsp;' . html_specialchars($row['shopprod_ordernumber']) . "</td>\n";
    echo '<td class="dir">&nbsp;'.html_specialchars($row['shopprod_model'])."</td>\n";
    echo '<td class="dir">&nbsp;'.html_specialchars($row['shopprod_name1'])."</td>\n";
    echo '<td class="dir listNumber text-right">&nbsp;'.html_specialchars( number_format( round($row['shopprod_price'], 2) , 2, $BLM['dec_point'], $BLM['thousands_sep'] ) )."&nbsp;</td>\n";

    echo '<td class="text-right text-nowrap">';

      echo '<a class="btn btn-sm btn-blue mr-1" href="'.$_controller_link.'&amp;edit='.$row["shopprod_id"].'">';
      echo '<i class="fa fa-pencil"></i></a>';

      echo '<button id="abtnshop'.$row['shopprod_id'].'" class="btn fa btn-sm visible '.($row["shopprod_status"]==0 ? "btn-danger" : "btn-success").' mr-1" data-id="'.$row['shopprod_id'].'" data-type="shop" data-table="shop_products" data-field="shopprod_status" data-fieldid="shopprod_id" aria-disabled="true" data-toggle="tooltip" title="'.$BL['be_tooltip_visibility'].'"></button>';

      echo '<a class="btn btn-sm btn-danger mr-1" href="'.$_controller_link.'&amp;delete='.$row["shopprod_id"];
      echo '" title="delete: '.html_specialchars($row['shopprod_ordernumber'].' / '.$row['shopprod_name1']).'"';
      echo ' onclick="return confirm(\''.$BLM['delete_product'].js_singlequote($row['shopprod_ordernumber'].' / '.$row['shopprod_name1']).'\');">';
      echo '<i class="fa fa-trash"></i></a>';

    echo '</td>'.LF;

    echo '</tr>'.LF;

    $row_count++;
  }

} else {
  echo '<tr><td colspan="6" class="tdtop5">'.$BL['be_empty_search_result'].'</td></tr>';
}
?>

</table>
</div>
