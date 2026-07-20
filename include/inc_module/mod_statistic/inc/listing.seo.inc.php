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
$_controller_link =  statistic_url('controller=seo');

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

    $_SESSION['list_active']  = empty($_POST['showactive']) ? 0 : 1;
    $_SESSION['list_inactive']  = empty($_POST['showinactive']) ? 0 : 1;

    $_SESSION['seo_filter']     = clean_slweg($_POST['filter']);
    if(empty($_SESSION['seo_filter'])) {
        unset($_SESSION['seo_filter']);
    } else {
        $_SESSION['seo_filter'] = convertStringToArray($_SESSION['seo_filter'], ' ');
        $_POST['filter']  = $_SESSION['seo_filter'];
    }

    $_SESSION['seolog_page'] = intval($_POST['page']);

}

if(empty($_SESSION['seolog_page'])) {
    $_SESSION['seolog_page'] = 1;
}

$_entry['list_active']    = isset($_SESSION['list_active']) ? $_SESSION['list_active']    : 1;
$_entry['list_inactive']  = isset($_SESSION['list_inactive']) ? $_SESSION['list_inactive']  : 1;


$_entry['query'] = '1=1';

if(isset($_SESSION['seo_filter']) && is_array($_SESSION['seo_filter']) && count($_SESSION['seo_filter'])) {

    $_entry['filter_array'] = array();

    foreach($_SESSION['seo_filter'] as $_entry['filter']) {
        //usr_name, usr_login, usr_email
        $_entry['filter_array'][] = "CONCAT(domain,query) LIKE '%".aporeplace($_entry['filter'])."%'";
    }
    if(count($_entry['filter_array'])) {

        $_SESSION['seo_filter'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
        $_entry['query'] .= $_SESSION['seo_filter'];

    }

} elseif(isset($_SESSION['seo_filter']) && is_string($_SESSION['seo_filter'])) {

    $_entry['query'] .= $_SESSION['seo_filter'];

}


// paginating values
$_entry['count_total'] = _dbQuery('SELECT * FROM '.DB_PREPEND.'phpwcms_log_seo WHERE '.$_entry['query'], 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['seolog_page'] > $_entry['pages_total']) {
    $_SESSION['seolog_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}



?>
<h2 class="mb-3"><?php echo $BLM['listing_seo'] ?></h2>

<form action="<?php echo $_controller_link ?>" method="post" name="paginate" id="paginate">
	<input type="hidden" name="do_pagination" value="1" />
	<div class="form-row align-items-center mb-3">
		<?php if($_entry['pages_total'] > 1): ?>
			<div class="col-auto">
				<div class="input-group input-group-sm">
					<div class="input-group-prepend">
						<?php if($_SESSION['seolog_page'] > 1): ?>
							<a href="<?php echo $_controller_link ?>&amp;page=<?php echo ($_SESSION['seolog_page']-1) ?>" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-left"></i></a>
						<?php else: ?>
							<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-chevron-left"></i></button>
						<?php endif; ?>
					</div>
					<input type="number" name="page" id="page" value="<?php echo $_SESSION['seolog_page'] ?>" class="form-control form-control-sm text-center w-25" />
					<div class="input-group-append">
						<span class="input-group-text">/ <?php echo $_entry['pages_total'] ?></span>
						<?php if($_SESSION['seolog_page'] < $_entry['pages_total']): ?>
							<a href="<?php echo $_controller_link ?>&amp;page=<?php echo ($_SESSION['seolog_page']+1) ?>" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-right"></i></a>
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
					echo html_specialchars(implode(' ', $_POST['filter']));
				}
				?>" class="form-control" placeholder="<?php echo html($BL['be_ftab_search']); ?>..." title="<?php echo html($BL['be_filter']); ?>" style="min-width: 250px;" />
				<div class="input-group-append">
					<button class="btn btn-secondary" type="submit" name="gofilter" title="<?php echo html($BL['be_filter']); ?>"><i class="fas fa-search"></i></button>
				</div>
			</div>
		</div>

		<div class="col text-right">
			<select class="custom-select custom-select-sm" style="width: auto; display: inline-block;" onchange="location.href='<?php echo statistic_url('controller=seo') ?>&amp;c=' + this.value;">
				<?php foreach([10, 25, 50, 100, 250] as $c): ?>
					<option value="<?php echo $c ?>"<?php if($_SESSION['list_user_count'] == $c) echo ' selected'; ?>><?php echo $c ?></option>
				<?php endforeach; ?>
				<option value="all"<?php if($_SESSION['list_user_count'] == 99999) echo ' selected'; ?>><?php echo $BL['be_ftptakeover_all'].' '.$_entry['count_total'] ?></option>
			</select>
		</div>
	</div>
</form>

<div class="table-responsive mb-4">
	<table class="table table-sm table-striped table-hover mb-0">
		<thead>
			<tr>
				<th style="width: 150px;">Datum</th>
				<th>Domain / Referrer</th>
				<th class="text-center" style="width: 80px;">Pos</th>
				<th>Suchbegriff</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$row_count = 0;
		$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_log_seo WHERE '.$_entry['query'].' ORDER BY create_date DESC ';
		$sql .= 'LIMIT '.(($_SESSION['seolog_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
		$data = _dbQuery($sql);

		foreach($data as $row) {
			echo '<tr>';
			echo '<td class="align-middle text-nowrap">'.$row['create_date'].'</td>';
			echo '<td class="align-middle"><a href="'.html_specialchars($row['referrer']).'" target="_blank">'.html_specialchars($row['domain']).'</a></td>';
			echo '<td class="align-middle text-center">'.$row['pos'].'</td>';
			echo '<td class="align-middle">';
			echo html_specialchars(PHPWCMS_CHARSET != 'utf-8' && phpwcms_seems_utf8($row['query']) ? makeCharsetConversion($row['query'], 'utf-8', PHPWCMS_CHARSET, false) : $row['query']);
			echo '</td>';
			echo "</tr>\n";
			$row_count++;
		}
		?>
		</tbody>
	</table>
</div>

<div class="card mt-4 mb-0">
	<div class="card-header"><h5 class="mb-0"><?php echo $BLM['listing_seo_top'] ?></h5></div>
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-sm table-striped table-hover mb-0">
				<thead>
					<tr>
						<th style="width: 80px;"><?php echo $BLM['pollcounts'] ?></th>
						<th>Suchbegriff</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$sql  = 'SELECT Count(query) AS Anzahl, query FROM '.DB_PREPEND.'phpwcms_log_seo GROUP BY query ORDER BY Anzahl DESC LIMIT 0,20';
				$data = _dbQuery($sql);

				$row_count = 0;
				foreach($data as $row) {
					echo '<tr>';
					echo '<td>'.$row['Anzahl'].'</td>';
					echo '<td>';
					echo html_specialchars(PHPWCMS_CHARSET !== 'utf-8' && phpwcms_seems_utf8($row['query']) ? makeCharsetConversion($row['query'], 'utf-8', PHPWCMS_CHARSET, false) : $row['query']);
					echo '</td>';
					echo "</tr>\n";
					$row_count++;
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

</div>
</div>
