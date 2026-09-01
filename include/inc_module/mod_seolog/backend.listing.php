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


$_entry['query']			= '';

// create pagination
if(isset($_GET['c'])) {
	$_SESSION['list_user_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
	$_SESSION['seolog_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
	$_SESSION['list_user_count'] = 25;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

	$_SESSION['list_active']	= empty($_POST['showactive']) ? 0 : 1;
	$_SESSION['list_inactive']	= empty($_POST['showinactive']) ? 0 : 1;

	$_SESSION['filter_seo']			= clean_slweg($_POST['filter']);
	if(empty($_SESSION['filter_seo'])) {
		unset($_SESSION['filter_seo']);
	} else {
		$_SESSION['filter_seo']	= convertStringToArray($_SESSION['filter_seo'], ' ');
		$_POST['filter']	= $_SESSION['filter_seo'];
	}

	$_SESSION['seolog_page'] = intval($_POST['page']);

}

if(empty($_SESSION['seolog_page'])) {
	$_SESSION['seolog_page'] = 1;
}

$_entry['list_active']		= isset($_SESSION['list_active'])	? $_SESSION['list_active']		: 1;
$_entry['list_inactive']	= isset($_SESSION['list_inactive'])	? $_SESSION['list_inactive']	: 1;


$_entry['query'] = '1=1';

if(isset($_SESSION['filter_seo']) && is_array($_SESSION['filter_seo']) && count($_SESSION['filter_seo'])) {

	$_entry['filter_array'] = array();

	foreach($_SESSION['filter_seo'] as $_entry['filter']) {
		//usr_name, usr_login, usr_email
		$_entry['filter_array'][] = 'CONCAT(domain,query) LIKE ' . _dbEscapeLike($_entry['filter']);
	}
	if(count($_entry['filter_array'])) {

		$_SESSION['filter_seo'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
		$_entry['query'] .= $_SESSION['filter_seo'];

	}

} elseif(isset($_SESSION['filter_seo']) && is_string($_SESSION['filter_seo'])) {

	$_entry['query'] .= $_SESSION['filter_seo'];

}


$sql  = 'SELECT COUNT(DISTINCT hash) FROM '.DB_PREPEND.'phpwcms_log_seo ';
if($_entry['query'] && $_entry['query'] != '1=1') {
	$sql .= 'WHERE '.$_entry['query'].' ';
}

// paginating values
$_entry['count_total'] = _dbQuery($sql, 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['seolog_page'] > $_entry['pages_total']) {
	$_SESSION['seolog_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}



?>
<h1 class="title mb-3"><?php echo $BLM['listing_title'] ?></h1>

<div class="card">
	<div class="card-body">
		<form action="<?php echo MODULE_HREF ?>" method="post" name="paginate" id="paginate">
			<input type="hidden" name="do_pagination" value="1" />
			<div class="row g-2 align-items-center mb-3">
				<?php if($_entry['pages_total'] > 1): ?>
					<div class="col-auto">
						<div class="input-group input-group-sm">
							
								<?php if($_SESSION['seolog_page'] > 1): ?>
									<a href="<?php echo MODULE_HREF ?>&amp;page=<?php echo ($_SESSION['seolog_page']-1) ?>" class="btn btn-secondary btn-sm"><i class="fa-solid fa-chevron-left"></i></a>
								<?php else: ?>
									<button class="btn btn-secondary btn-sm" disabled><i class="fa-solid fa-chevron-left"></i></button>
								<?php endif; ?>
							
							<input type="number" name="page" id="page" value="<?php echo $_SESSION['seolog_page'] ?>" class="form-control form-control-sm text-center w-25" />
							
								<span class="input-group-text">/ <?php echo $_entry['pages_total'] ?></span>
								<?php if($_SESSION['seolog_page'] < $_entry['pages_total']): ?>
									<a href="<?php echo MODULE_HREF ?>&amp;page=<?php echo ($_SESSION['seolog_page']+1) ?>" class="btn btn-secondary btn-sm"><i class="fa-solid fa-chevron-right"></i></a>
								<?php else: ?>
									<button class="btn btn-secondary btn-sm" disabled><i class="fa-solid fa-chevron-right"></i></button>
								<?php endif; ?>
							
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
						
							<button class="btn btn-secondary" type="submit" name="gofilter" title="<?php echo html($BL['be_filter']); ?>"><i class="fa-solid fa-search"></i></button>
						
					</div>
				</div>

				<div class="col text-end">
					<select class="form-select form-select-sm" style="width: auto; display: inline-block;" onchange="location.href='<?php echo decode_entities(MODULE_HREF) ?>&amp;c=' + this.value;">
						<?php foreach([10, 25, 50, 100, 250] as $c): ?>
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
						<th style="width: 80px;" class="text-center">Count</th>
						<th>Domain / Referrer</th>
						<th>Query</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$row_count = 0;

				$sql  = 'SELECT domain, referrer, query, hash, COUNT(*) AS occurance FROM '.DB_PREPEND.'phpwcms_log_seo ';
				if($_entry['query'] && $_entry['query'] != '1=1') {
					$sql .= 'WHERE '.$_entry['query'].' ';
				}
				$sql .= 'GROUP BY hash, domain, referrer, query ORDER BY occurance DESC ';
				$sql .= 'LIMIT '.(($_SESSION['seolog_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
				$data = _dbQuery($sql);

				if($data) {
					foreach($data as $row) {
						echo '<tr>';
						echo '<td class="text-center">' . $row['occurance'] . '</td>';
						echo '<td><a href="' . html($row['referrer']) . '" target="_blank">' . html($row['domain']) . '</a></td>';
						echo '<td>' . html(PHPWCMS_CHARSET != 'utf-8' && phpwcms_seems_utf8($row['query']) ? makeCharsetConversion($row['query'], 'utf-8', PHPWCMS_CHARSET, false) : $row['query']) . '</td>';
						echo '</tr>';
						$row_count++;
					}
				} else {
					echo '<tr><td colspan="3" class="text-center text-muted py-3">' . $BL['be_empty_search_result'] . '</td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
