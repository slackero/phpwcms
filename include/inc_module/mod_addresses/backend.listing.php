<?php

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
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
$sql = 'SELECT * FROM '.DB_PREPEND.'phpwcms_userdetail WHERE '.$_entry['query'];
$_entry['count_total'] = _dbQuery($sql, 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['userdetail_page'] > $_entry['pages_total']) {
    $_SESSION['userdetail_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}



?>
<h1 class="title mb-3"><?php echo $BLM['listing_title'] ?></h1>

<div class="form-group mb-3 text-center text-sm-left">
    <a class="btn btn-sm btn-blue" href="<?php echo MODULE_HREF ?>&amp;edit=0" title="<?php echo $BLM['create_new'] ?>"><i class="fas fa-address-card fa-fw"></i> <span><?php echo $BLM['create_new'] ?></span></a>
</div>

<div class="card">
	<div class="card-body">
		<form action="<?php echo MODULE_HREF ?>" method="post" name="paginate" id="paginate">
			<input type="hidden" name="do_pagination" value="1" />
			<input type="hidden" name="showactive" id="showactive_input" value="<?php echo $_entry['list_active'] ?>" />
			<input type="hidden" name="showinactive" id="showinactive_input" value="<?php echo $_entry['list_inactive'] ?>" />
			<div class="form-row align-items-center mb-3">
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

				<?php if($_entry['pages_total'] > 1): ?>
					<div class="col-auto">
						<div class="input-group input-group-sm">
							<div class="input-group-prepend">
								<?php if($_SESSION['userdetail_page'] > 1): ?>
									<a href="<?php echo decode_entities(MODULE_HREF) ?>&amp;page=<?php echo ($_SESSION['userdetail_page']-1) ?>" class="btn btn-secondary"><i class="fas fa-chevron-left"></i></a>
								<?php else: ?>
									<button class="btn btn-secondary" disabled><i class="fas fa-chevron-left"></i></button>
								<?php endif; ?>
							</div>
							<input type="number" name="page" id="page" value="<?php echo $_SESSION['userdetail_page'] ?>" class="form-control text-center w-25" />
							<div class="input-group-append">
								<span class="input-group-text">/ <?php echo $_entry['pages_total'] ?></span>
								<?php if($_SESSION['userdetail_page'] < $_entry['pages_total']): ?>
									<a href="<?php echo decode_entities(MODULE_HREF) ?>&amp;page=<?php echo ($_SESSION['userdetail_page']+1) ?>" class="btn btn-secondary"><i class="fas fa-chevron-right"></i></a>
								<?php else: ?>
									<button class="btn btn-secondary" disabled><i class="fas fa-chevron-right"></i></button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php else: ?>
					<input type="hidden" name="page" id="page" value="1" />
				<?php endif; ?>

				<div class="col-auto">
					<div class="input-group input-group-sm">
						<input type="search" name="filter" id="filter" size="15" value="<?php
						if(isset($_POST['filter']) && is_array($_POST['filter']) ) {
							echo html_specialchars(implode(' ', $_POST['filter']));
						}
						?>" class="form-control" placeholder="<?php echo html($BL['be_ftab_search']); ?>..." title="<?php echo html($BL['be_filter']); ?>" style="min-width: 250px;" />
						<select name="filter_country" id="filter_country" class="form-control" onchange="this.form.submit();">
							<option value="-">- <?php echo $BLM['detail_country'] ?> -</option>
							<?php echo list_country( isset($_SESSION['filter_country']) ? $_SESSION['filter_country'] : '-'  ); ?>
						</select>
						<div class="input-group-append">
							<button class="btn btn-secondary" type="submit" name="gofilter" title="<?php echo html($BL['be_filter']); ?>"><i class="fas fa-search"></i></button>
						</div>
					</div>
				</div>

				<div class="col text-right">
					<select class="custom-select custom-select-sm" style="width: auto; display: inline-block;" onchange="location.href='<?php echo decode_entities(MODULE_HREF) ?>&amp;c=' + this.value;">
						<?php foreach([10, 25, 50, 100] as $c): ?>
							<option value="<?php echo $c ?>"<?php if($_SESSION['list_user_count'] == $c) echo ' selected'; ?>><?php echo $c ?></option>
						<?php endforeach; ?>
						<option value="all"<?php if($_SESSION['list_user_count'] == 99999) echo ' selected'; ?>><?php echo $BL['be_ftptakeover_all'] ?></option>
					</select>
				</div>
			</div>
		</form>

		<div class="table-responsive">
			<table class="table table-sm table-striped table-hover table-valign-middle mb-0">
				<thead>
					<tr>
						<th style="width: 40px;" class="text-center">&nbsp;</th>
						<th><?php echo $BLM['detail_company'].'/'.$BLM['detail_lastname'] ?></th>
						<th><?php echo $BLM['detail_city'] ?></th>
						<th>C</th>
						<th>S</th>
						<th style="width: 120px;" class="text-right">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$row_count = 0;

				$sql  = 'SELECT detail_id, detail_company, detail_firstname, detail_lastname, detail_city, detail_zip, detail_country, detail_int2, detail_aktiv FROM '.DB_PREPEND.'phpwcms_userdetail WHERE '.$_entry['query'].' ';
				$sql .= 'ORDER BY detail_company, detail_city, detail_country ';
				$sql .= 'LIMIT '.(($_SESSION['userdetail_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
				$data = _dbQuery($sql);

				if($data) {
					foreach($data as $row) {
						$row['listname'] = trim($row["detail_company"] . ', ' . trim($row["detail_firstname"] . ' ' . $row["detail_lastname"]), ', ');
						echo '<tr>';
						echo '<td class="text-center"><i class="fas fa-address-card text-muted"></i></td>';
						echo '<td><a href="' . MODULE_HREF . '&amp;edit=' . $row["detail_id"] . '" class="text-dark font-weight-bold">' . html($row['listname']) . '</a></td>';
						echo '<td>' . html($row["detail_city"] . ($row["detail_zip"] ? ', ' . $row["detail_zip"] : '')) . '</td>';
						echo '<td>' . html($row["detail_country"]) . '</td>';
						echo '<td>' . (intval($row["detail_int2"]) ? $row["detail_int2"] : '') . '</td>';
						echo '<td class="text-right text-nowrap">';
						echo '<div class="btn-group btn-group-sm" role="group" aria-label="address-actions-' . $row["detail_id"] . '">';
						
						echo '<a href="' . MODULE_HREF . '&amp;edit=' . $row["detail_id"] . '" class="btn btn-sm btn-blue" title="' . $BL['be_func_struct_edit'] . '"><i class="fa fa-pencil-alt fa-fw"></i></a>';
						
						echo '<a href="' . MODULE_HREF . '&amp;editid=' . $row["detail_id"] . '&amp;verify=' . (($row["detail_aktiv"]) ? '0' : '1') . '" class="btn btn-sm ' . (($row["detail_aktiv"]) ? 'btn-success' : 'btn-warning') . '" title="Toggle Status">';
						echo '<i class="fas ' . (($row["detail_aktiv"]) ? 'fa-eye' : 'fa-eye-slash') . ' fa-fw"></i></a>';
						echo '</div>';
						
						echo '<a href="' . MODULE_HREF . '&amp;delete=' . $row["detail_id"] . '" class="btn btn-sm btn-danger ml-1" title="' . $BL['be_cnt_delete'] . ': ' . html($row["detail_name"]) . '"';
						echo ' onclick="return confirm(\'' . $BLM['delete_entry'] . ' ' . js_singlequote($row["detail_name"]) . '\');">';
						echo '<i class="far fa-trash-alt"></i></a>';
						echo '</td>';
						echo '</tr>';
						$row_count++;
					}
				} else {
					echo '<tr><td colspan="6" class="text-center text-muted py-3">' . $BL['be_empty_search_result'] . '</td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>