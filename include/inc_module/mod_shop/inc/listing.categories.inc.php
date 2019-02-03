<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2018, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsgo constants
if (!defined('CMSGO_ROOT')) {
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

	$_SESSION['filter_shop_category'] = clean_slweg($_POST['filter']);
	if(empty($_SESSION['filter_shop_category'])) {
		unset($_SESSION['filter_shop_category']);
	} else {
		$_SESSION['filter_shop_category'] = convertStringToArray($_SESSION['filter_shop_category'], ' ');
		$_POST['filter'] = $_SESSION['filter_shop_category'];
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
		$_entry['query'] .= 'cat_status=0';
	}
	if(!$_entry['list_inactive']) {
		$_entry['query'] .= 'cat_status=1';
	}

} else {
	$_entry['query'] .= 'cat_status!=9';
}
$_entry['query'] .= " AND cat_type='module_shop'";

if(isset($_SESSION['filter_shop_category']) && is_array($_SESSION['filter_shop_category']) && count($_SESSION['filter_shop_category'])) {

	$_entry['filter_array'] = array();

	foreach($_SESSION['filter_shop_category'] as $_entry['filter']) {
		//usr_name, usr_login, usr_email
		$_entry['filter_array'][] = "cat_name LIKE '%".aporeplace($_entry['filter'])."%'";
		$_entry['filter_array'][] = "cat_info LIKE '%".aporeplace($_entry['filter'])."%'";
	}
	if(count($_entry['filter_array'])) {

		$_SESSION['filter_shop_category'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
		$_entry['query'] .= $_SESSION['filter_shop_category'];

	}

} elseif(isset($_SESSION['filter_shop_category']) && is_string($_SESSION['filter_shop_category'])) {

	$_entry['query'] .= $_SESSION['filter_shop_category'];

}

// paginating values
$_entry['count_total'] = _dbQuery('SELECT COUNT(cat_id) FROM '.DB_PREPEND.'cmsgo_categories WHERE '.$_entry['query'], 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_count']);
if($_SESSION['detail_page'] > $_entry['pages_total']) {
	$_SESSION['detail_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}
?>

<div class="form-group mb-3 text-center text-sm-left">
  <a class="btn btn-sm btn-blue" href="<?php echo shop_url(array('controller=cat', 'edit=0')) ?>" title="<?php echo $BLM['create_new'] ?>"><i class="fa fa-plus"></i> <span><?php echo $BLM['create_new'] ?></span></a>
</div>

<form action="<?php echo shop_url('controller=cat') ?>" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />
  <div class="form-row align-items-center">
		<div class="col-12 col-sm">
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
			<div class="input-group input-group-sm my-3 my-sm-0">
				<input name="filter" id="filter" size="15" data-toggle="tooltip" title="Filtern" class="form-control form-control-sm" value="<?php echo html($news->filter) ?>" type="search">
					<span class="input-group-append">
						<input class="btn btn-sm btn-secondary" name="gofilter" value="Filter" type="submit">
					</span>
			</div>
		</div>

		<div class="col-12 col-sm-auto text-right">
			<select class="form-control form-control-sm custom-select">
				<option selected><?php echo $BL['be_article_rendering'] ?></option>
				<option onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=cat&amp;c=5'">5</option>
				<option onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=cat&amp;c=10'">10</option>
				<option onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=cat&amp;c=25'">25</option>
				<option onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=cat&amp;c=50'">50</option>
				<option onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=cat&amp;c=100'">100</option>
				<option onClick="window.location = 'cmsgo.php?do=modules&amp;module=shop&amp;controller=cat&amp;c=all'"><?php echo $BL['be_ftptakeover_all'] ?></option>
			</select>
		</div>
  </div>
</form>
<div class="table-responsive">
	<table class="table table-sm mb-0 mt-2">
		<?php
		// loop listing available newsletters
		$row_count = 0;

		$sql  = "SELECT C1.*, ";
		$sql .= "IFNULL(CONCAT(C2.cat_name, ' / ', C1.cat_name), C1.cat_name) AS category FROM ";
		$sql .= DB_PREPEND.'cmsgo_categories C1 ';
		$sql .= 'LEFT JOIN '.DB_PREPEND.'cmsgo_categories C2 ';
		$sql .= 'ON C1.cat_pid=C2.cat_id ';
		$sql .= 'WHERE '.str_replace('cat_', 'C1.cat_', $_entry['query']).' ';
		$sql .= 'ORDER BY C1.cat_sort DESC, C2.cat_sort DESC, category ASC ';
		$sql .= 'LIMIT '.(($_SESSION['detail_page']-1) * $_SESSION['list_count']).','.$_SESSION['list_count'];

		$data = _dbQuery($sql);

		$_controller_link =  shop_url('controller=cat');

		if(isset($data[0]['cat_id'])) {

			foreach($data as $row) {

				echo '<tr';
				if(!$row['cat_pid']) echo ' data-toggle="tooltip" data-html="true" title="' . $BL['be_admin_page_category'] . ' ID: <b>' .$row["cat_id"]. '</b><br />'.$BL['be_cnt_sorting'].': <b>'.$row["cat_sort"].'</b>)"';
				echo '>'.LF;

				echo '<td width="25" style="padding:2px 3px 2px 4px;">';
				echo '<i class="fa fa-tag fa-fw text-';
				echo $row['cat_pid'] ? 'muted' : 'blue';
				echo '"></i></td>'.LF;

				echo '<td class="dir" width="85%">';
				echo $row['cat_pid'] ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '&nbsp;';
				echo html_specialchars($row['category'])."</td>\n";

				echo '<td class="dir" width="3%" align="center">&nbsp;' . $row['cat_sort'] . '&nbsp;</td>';


				echo '<td width="10%" class="text-right text-nowrap">';

					echo '<a class="btn btn-sm btn-blue mr-1" href="'.$_controller_link.'&amp;edit='.$row["cat_id"].'">';
					echo '<i class="fa fa-pencil"></i></a>';

							echo '<button id="abtnshop'.$row['cat_id'].'" class="btn fa btn-sm visible '.($row["cat_status"]==0 ? "btn-danger" : "btn-success").' mr-1" data-id="'.$row['cat_id'].'" data-type="shop" data-table="categories" data-field="cat_status" data-fieldid="cat_id" aria-disabled="true" data-toggle="tooltip" title="'.$BL['be_tooltip_visibility'].'"></button>';

					echo '<a class="btn btn-sm btn-danger mr-1" href="'.$_controller_link.'&amp;delete='.$row["cat_id"];
					echo '" title="delete: '.html_specialchars($row['cat_name']).'"';
					echo ' onclick="return confirm(\''.$BLM['delete_entry'].js_singlequote($row['cat_name']).'\');">';
					echo '<i class="fa fa-trash"></i></a>';

				echo '</td>'.LF;

				echo '</tr>'.LF;

				$row_count++;
			}
		} else {
			echo '<tr><td colspan="4" class="tdtop5">'.$BL['be_empty_search_result'].'</td></tr>';
		}
		?>
	</table>
</div>
