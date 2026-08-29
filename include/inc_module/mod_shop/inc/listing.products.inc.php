<?php
/**
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
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
  $_SESSION['list_product_count'] = 250;
}

$_entry['post_filter'] = '';

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

  $_SESSION['list_active']  = empty($_POST['showactive']) ? 0 : 1;
  $_SESSION['list_inactive']  = empty($_POST['showinactive']) ? 0 : 1;

  $_entry['post_filter'] = clean_slweg($_POST['filter']);

  if(empty($_entry['post_filter'])) {
    unset($_SESSION['filter_shop_products']);
  } else {
    $_SESSION['filter_shop_products'] = convertStringToArray($_entry['post_filter'], ' ');
  }

  $_SESSION['detail_page'] = empty($_POST['page']) ? 0 : intval($_POST['page']);

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
$_entry['count_total'] = _dbCount('SELECT COUNT(shopprod_id) FROM '.DB_PREPEND.'phpwcms_shop_products WHERE '.$_entry['query']);
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_product_count']);
if($_SESSION['detail_page'] > $_entry['pages_total']) {
  $_SESSION['detail_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}
?>

<div class="form-group mb-3 text-center text-sm-start">
  <a class="btn btn-sm btn-blue" href="<?php echo shop_url(array('controller=prod', 'edit=0')) ?>" title="<?php echo $BLM['create_new_prod'] ?>"><i class="fa fa-plus me-1"></i> <span><?php echo $BLM['create_new_prod'] ?></span></a>
</div>

<form action="<?php echo shop_url('controller=prod') ?>" method="post" name="paginate" id="paginate">
	<input type="hidden" name="do_pagination" value="1" />
	<input type="hidden" name="showactive" id="showactive_input" value="<?php echo $_entry['list_active'] ?>" />
	<input type="hidden" name="showinactive" id="showinactive_input" value="<?php echo $_entry['list_inactive'] ?>" />
	<div class="row g-2 align-items-center my-2">
		<div class="col-auto">
			<div class="btn-group btn-group-sm">
				<button type="button" class="btn btn-sm <?php echo $_entry['list_active'] ? 'btn-success' : 'btn-outline-secondary' ?>" onclick="document.getElementById('showactive_input').value = (document.getElementById('showactive_input').value == '1' ? '0' : '1'); this.form.submit();" title="Active">
					<i class="fas fa-eye"></i>
				</button>
				<button type="button" class="btn btn-sm <?php echo $_entry['list_inactive'] ? 'btn-warning' : 'btn-outline-secondary' ?>" onclick="document.getElementById('showinactive_input').value = (document.getElementById('showinactive_input').value == '1' ? '0' : '1'); this.form.submit();" title="Inactive">
					<i class="fas fa-eye-slash"></i>
				</button>
			</div>
		</div>

		<div class="col-auto">
			<div class="input-group input-group-sm">
				<input name="filter" id="filter" size="15" data-bs-toggle="tooltip" title="<?php echo html($BL['be_filter']); ?>" class="form-control" value="<?php echo html($_entry['post_filter']); ?>" type="search" style="min-width: 250px;" placeholder="<?php echo html($BL['be_ftab_search']); ?>..." />
				
					<button class="btn btn-secondary" type="submit" name="gofilter" title="<?php echo html($BL['be_filter']); ?>"><i class="fas fa-search"></i></button>
				
			</div>
		</div>

		<div class="col text-end">
			<select class="form-select form-select-sm" style="width: auto; display: inline-block;" onchange="location.href='phpwcms.php?do=modules&amp;module=shop&amp;controller=prod&amp;c=' + this.value;">
				<option value="10"<?php if($_SESSION['list_product_count'] == '10') echo ' selected'; ?>>10</option>
				<option value="25"<?php if($_SESSION['list_product_count'] == '25') echo ' selected'; ?>>25</option>
				<option value="50"<?php if($_SESSION['list_product_count'] == '50') echo ' selected'; ?>>50</option>
				<option value="100"<?php if($_SESSION['list_product_count'] == '100') echo ' selected'; ?>>100</option>
				<option value="250"<?php if($_SESSION['list_product_count'] == '250') echo ' selected'; ?>>250</option>
				<option value="all"<?php if($_SESSION['list_product_count'] == 99999) echo ' selected'; ?>><?php echo $BL['be_ftptakeover_all'].' '.$_entry['count_total'] ?></option>
			</select>
		</div>
	</div>
</form>
<div class="table-responsive">
<table class="table table-sm table-valign-middle mb-0 mt-2">

  <tr bgcolor="#f3f3f3">
    <th>&nbsp;</th>
    <th>&nbsp;<?php echo $BLM['th_ordnr'] ?></th>
    <th>&nbsp;<?php echo $BLM['th_modnr'] ?></th>
    <th>&nbsp;<?php echo $BLM['th_product'] ?></th>
    <th style="text-align:right;padding-right:5px;">&nbsp;<?php echo $BLM['th_price'] ?>&nbsp;</th>
    <th style="text-align:right;padding-right:5px;">&nbsp;<?php echo $BLM['shopprod_inventory'] ?>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>


<?php
// loop listing available products
$row_count = 0;

$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_shop_products WHERE '.$_entry['query'].' ';
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
      echo '<span class="flag-icon flag-icon-'.($row['shopprod_lang'] ? $row['shopprod_lang'] : ' fas fa-globe').' mt-1" data-bs-toggle="tooltip" title="'.$row['shopprod_lang'].'"></span>';
    }
    echo '&nbsp;' . html_specialchars($row['shopprod_ordernumber']) . "</td>\n";
    echo '<td class="dir">&nbsp;'.html_specialchars($row['shopprod_model'])."</td>\n";
    echo '<td class="dir">&nbsp;'.html_specialchars($row['shopprod_name1'])."</td>\n";
    echo '<td class="dir listNumber text-end">&nbsp;'.html_specialchars( number_format( round($row['shopprod_price'], 2) , 2, $BLM['dec_point'], $BLM['thousands_sep'] ) )."&nbsp;</td>\n";
    echo '<td class="dir listNumber">&nbsp;'.$row['shopprod_inventory']."&nbsp;</td>\n";

    echo '<td class="text-end text-nowrap">';

      echo '<div class="btn-group btn-group-sm" role="group" aria-label="shop-prod-actions-'.$row['shopprod_id'].'">';
      echo '<a class="btn btn-sm btn-blue" href="'.$_controller_link.'&amp;edit='.$row["shopprod_id"].'">';
      echo '<i class="fa fa-pencil-alt"></i></a>';

      $row["shopprod_var"] = @unserialize($row["shopprod_var"], ['allowed_classes' => false]);

      echo '<button id="abtnshop'.$row['shopprod_id'].'" class="btn fa btn-sm visible ';

      if (empty($row["shopprod_status"])) {
          echo "btn-warning";
      } elseif (!empty($row["shopprod_var"]['request']) && !empty($row["shopprod_var"]['request_url'])) {
          echo "btn-warning";
      } else {
          echo "btn-success";
      }

      echo '" data-id="'.$row['shopprod_id'].'" data-type="shop" data-table="shop_products" data-field="shopprod_status" data-fieldid="shopprod_id" aria-disabled="true" data-bs-toggle="tooltip" title="'.$BL['be_tooltip_visibility'].'"></button>';
      echo '</div>';

      echo '<a class="btn btn-sm btn-danger ms-1" href="'.$_controller_link.'&amp;delete='.$row["shopprod_id"];
      echo '" title="delete: '.html_specialchars($row['shopprod_ordernumber'].' / '.$row['shopprod_name1']).'"';
      echo ' onclick="return confirm(\''.$BLM['delete_product'].js_singlequote($row['shopprod_ordernumber'].' / '.$row['shopprod_name1']).'\');">';
      echo '<i class="far fa-trash-alt"></i></a>';

    echo '</td>'.LF;

    echo '</tr>'.LF;

    $row_count++;
  }

} else {
  echo '<tr><td colspan="7" class="tdtop5">'.$BL['be_empty_search_result'].'</td></tr>';
}
?>

</table>
</div>
