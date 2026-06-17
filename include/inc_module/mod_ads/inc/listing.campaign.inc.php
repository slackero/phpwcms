<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------

// create pagination
if(isset($_GET['c'])) {
	$_SESSION['list_user_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
	$_SESSION['ads_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
	$_SESSION['list_user_count'] = 25;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

	$_SESSION['list_active']	= empty($_POST['showactive']) ? 0 : 1;
	$_SESSION['list_inactive']	= empty($_POST['showinactive']) ? 0 : 1;

	$_SESSION['filter_ad_campaign'] = clean_slweg($_POST['filter']);
	if(empty($_SESSION['filter_ad_campaign'])) {
		unset($_SESSION['filter_ad_campaign']);
	} else {
		$_SESSION['filter_ad_campaign']	= convertStringToArray($_SESSION['filter_ad_campaign'], ' ');
		$_POST['filter'] = $_SESSION['filter_ad_campaign'];
	}

	$_SESSION['ads_page'] = intval($_POST['page']);

}

if(empty($_SESSION['ads_page'])) {
	$_SESSION['ads_page'] = 1;
}

$_entry['list_active']		= isset($_SESSION['list_active'])	? $_SESSION['list_active']		: 1;
$_entry['list_inactive']	= isset($_SESSION['list_inactive'])	? $_SESSION['list_inactive']	: 1;


// set correct status query
if($_entry['list_active'] != $_entry['list_inactive']) {

	if(!$_entry['list_active']) {
		$_entry['query'] .= 'adcampaign_status=0';
	}
	if(!$_entry['list_inactive']) {
		$_entry['query'] .= 'adcampaign_status=1';
	}

} else {
	$_entry['query'] .= 'adcampaign_status!=9';
}

if(isset($_SESSION['filter_ad_campaign']) && is_array($_SESSION['filter_ad_campaign']) && count($_SESSION['filter_ad_campaign'])) {

	$_entry['filter_array'] = array();

	foreach($_SESSION['filter_ad_campaign'] as $_entry['filter']) {
		//usr_name, usr_login, usr_email
		$_entry['filter_array'][] = "CONCAT(adcampaign_title, adcampaign_comment) LIKE '%".aporeplace($_entry['filter'])."%'";
	}
	if(count($_entry['filter_array'])) {

		$_SESSION['filter_ad_campaign'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
		$_entry['query'] .= $_SESSION['filter_ad_campaign'];

	}

} elseif(isset($_SESSION['filter_ad_campaign']) && is_string($_SESSION['filter_ad_campaign'])) {

	$_entry['query'] .= $_SESSION['filter_ad_campaign'];

}


// paginating values
$_entry['count_total'] = _dbQuery('SELECT * FROM '.DB_PREPEND.'cmsgo_ads_campaign WHERE '.$_entry['query'], 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['ads_page'] > $_entry['pages_total']) {
	$_SESSION['ads_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}



?>
	<div class="card-body">
		<form action="<?php echo MODULE_HREF ?>&amp;listcampaign=1" method="post" name="paginate" id="paginate">
			<input type="hidden" name="do_pagination" value="1" />
			<input type="hidden" name="showactive" id="showactive_input" value="<?php echo $_entry['list_active'] ?>" />
			<input type="hidden" name="showinactive" id="showinactive_input" value="<?php echo $_entry['list_inactive'] ?>" />
			<div class="form-row align-items-center mb-3">
				<div class="col-auto">
					<div class="btn-group btn-group-sm">
						<button type="button" class="btn btn-sm <?php echo $_entry['list_active'] ? 'btn-success' : 'btn-outline-secondary' ?>" onclick="document.getElementById('showactive_input').value = (document.getElementById('showactive_input').value == '1' ? '0' : '1'); this.form.submit();" title="Active">
							<i class="fas fa-eye"></i>
						</button>
						<button type="button" class="btn btn-sm <?php echo $_entry['list_inactive'] ? 'btn-danger' : 'btn-outline-secondary' ?>" onclick="document.getElementById('showinactive_input').value = (document.getElementById('showinactive_input').value == '1' ? '0' : '1'); this.form.submit();" title="Inactive">
							<i class="fas fa-eye-slash"></i>
						</button>
					</div>
				</div>

				<?php if($_entry['pages_total'] > 1): ?>
					<div class="col-auto">
						<div class="input-group input-group-sm">
							<div class="input-group-prepend">
								<?php if($_SESSION['ads_page'] > 1): ?>
									<a href="<?php echo MODULE_HREF ?>&amp;listcampaign=1&amp;page=<?php echo ($_SESSION['ads_page']-1) ?>" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-left"></i></a>
								<?php else: ?>
									<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-chevron-left"></i></button>
								<?php endif; ?>
							</div>
							<input type="text" name="page" id="page" value="<?php echo $_SESSION['ads_page'] ?>" class="form-control form-control-sm text-center" style="width: 50px;" />
							<div class="input-group-append">
								<span class="input-group-text">/ <?php echo $_entry['pages_total'] ?></span>
								<?php if($_SESSION['ads_page'] < $_entry['pages_total']): ?>
									<a href="<?php echo MODULE_HREF ?>&amp;listcampaign=1&amp;page=<?php echo ($_SESSION['ads_page']+1) ?>" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-right"></i></a>
								<?php else: ?>
									<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-chevron-right"></i></button>
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
							echo html(implode(' ', $_POST['filter']));
						}
						?>" class="form-control" placeholder="<?php echo html($BL['be_ftab_search']); ?>..." title="<?php echo html($BL['be_filter']); ?>" style="min-width: 250px;" />
						<div class="input-group-append">
							<button class="btn btn-secondary" type="submit" name="gofilter" title="<?php echo html($BL['be_filter']); ?>"><i class="fas fa-search"></i></button>
						</div>
					</div>
				</div>

				<div class="col text-right">
					<select class="custom-select custom-select-sm" style="width: auto; display: inline-block;" onchange="location.href='<?php echo decode_entities(MODULE_HREF) ?>&amp;listcampaign=1&amp;c=' + this.value;">
						<?php foreach([10, 25, 50, 100, 250] as $c): ?>
							<option value="<?php echo $c ?>"<?php if($_SESSION['list_user_count'] == $c) echo ' selected'; ?>><?php echo $c ?></option>
						<?php endforeach; ?>
						<option value="all"<?php if($_SESSION['list_user_count'] == 99999) echo ' selected'; ?>><?php echo $BL['be_ftptakeover_all'] ?></option>
					</select>
				</div>
			</div>
		</form>

		<div class="table-responsive">
			<table class="table table-sm table-striped table-hover mb-0">
				<thead>
					<tr>
						<th style="width: 40px;" class="text-center">&nbsp;</th>
						<th><?php echo $BLM['campaign_entry'] ?></th>
						<th><?php echo $BLM['ad_from-to'] ?></th>
						<th><?php echo $BLM['adplace'] ?></th>
						<th style="width: 160px;" class="text-right">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$row_count = 0;

				$sql  = 'SELECT *, UNIX_TIMESTAMP(ac.adcampaign_datestart) AS adcampaign_start, ';
				$sql .= 'UNIX_TIMESTAMP(ac.adcampaign_dateend) AS adcampaign_end ';
				$sql .= 'FROM '.DB_PREPEND.'cmsgo_ads_campaign ac ';
				$sql .= 'LEFT JOIN '.DB_PREPEND.'cmsgo_ads_place ap ON ';
				$sql .=	'ac.adcampaign_place=ap.adplace_id  ';
				$sql .= 'WHERE '.$_entry['query'];
				if($_SESSION['ads_page'] > 0 && $_SESSION['list_user_count']) {
					$sql .= ' LIMIT '.(($_SESSION['ads_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
				}
				$data = _dbQuery($sql);

				if($data) {
					foreach($data as $row) {
						echo '<tr>';
						echo '<td class="text-center"><i class="fas fa-bullhorn text-muted"></i></td>';
						echo '<td>' . html($row["adcampaign_title"]) . '</td>';
						echo '<td>' . html(date($BLM['list_date_format'], $row["adcampaign_start"]) . ' – ' . date($BLM['list_date_format'], $row["adcampaign_end"])) . '</td>';
						echo '<td>' . $row["adplace_width"] . 'x' . $row["adplace_height"] . ' {ADS_' . $row["adplace_id"] . '}</td>';
						echo '<td class="text-right">';
						
						echo '<a href="' . MODULE_HREF . '&amp;campaign=1&amp;edit=' . $row["adcampaign_id"] . '" class="btn btn-sm btn-blue mr-1" title="Edit"><i class="fas fa-edit fa-fw"></i></a>';
						
						echo '<a href="' . MODULE_HREF . '&amp;campaign=1&amp;duplicate=' . $row["adcampaign_id"] . '" class="btn btn-sm btn-secondary mr-1" title="' . $BLM['duplicate_title'] . '"';
						echo ' onclick="return confirm(\'' . js_singlequote($BLM['duplicate_campaign']) . ' \n' . js_singlequote($BLM['campaign_title'] . ': ' . html('"' . $row["adcampaign_title"] . '"')) . '\');">';
						echo '<i class="fas fa-copy fa-fw"></i></a>';
						
						echo '<a href="' . MODULE_HREF . '&amp;campaign=1&amp;editid=' . $row["adcampaign_id"] . '&amp;verify=' . (($row["adcampaign_status"]) ? '0' : '1') . '" class="btn btn-sm ' . (($row["adcampaign_status"]) ? 'btn-success' : 'btn-secondary') . ' mr-1" title="Toggle Status">';
						echo '<i class="fas ' . (($row["adcampaign_status"]) ? 'fa-eye' : 'fa-eye-slash') . ' fa-fw"></i></a>';
						
						echo '<a href="' . MODULE_HREF . '&amp;campaign=1&amp;delete=' . $row["adcampaign_id"] . '" class="btn btn-sm btn-danger" title="Delete"';
						echo ' onclick="return confirm(\'' . $BLM['delete_entry'] . js_singlequote($row["adcampaign_title"]) . '\');">';
						echo '<i class="fas fa-trash fa-fw"></i></a>';
						
						echo '</td>';
						echo '</tr>';
						$row_count++;
					}
				} else {
					echo '<tr><td colspan="5" class="text-center text-muted py-3">' . $BL['be_empty_search_result'] . '</td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
