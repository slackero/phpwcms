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
	$_SESSION['glossary_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
	$_SESSION['list_user_count'] = 25;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

	$_SESSION['list_active']	= empty($_POST['showactive']) ? 0 : 1;
	$_SESSION['list_inactive']	= empty($_POST['showinactive']) ? 0 : 1;

	$_SESSION['filter_glossary']			= clean_slweg($_POST['filter']);
	if(empty($_SESSION['filter_glossary'])) {
		unset($_SESSION['filter_glossary']);
	} else {
		$_SESSION['filter_glossary']	= convertStringToArray($_SESSION['filter_glossary'], ' ');
		$_POST['filter']	= $_SESSION['filter_glossary'];
	}

	$_SESSION['glossary_page'] = intval($_POST['page']);

}

if(empty($_SESSION['glossary_page'])) {
	$_SESSION['glossary_page'] = 1;
}

$_entry['list_active']		= isset($_SESSION['list_active'])	? $_SESSION['list_active']		: 1;
$_entry['list_inactive']	= isset($_SESSION['list_inactive'])	? $_SESSION['list_inactive']	: 1;


// set correct status query
if($_entry['list_active'] != $_entry['list_inactive']) {

	if(!$_entry['list_active']) {
		$_entry['query'] .= 'glossary_status=0';
	}
	if(!$_entry['list_inactive']) {
		$_entry['query'] .= 'glossary_status=1';
	}

} else {
	$_entry['query'] .= 'glossary_status!=9';
}

if(isset($_SESSION['filter_glossary']) && is_array($_SESSION['filter_glossary']) && count($_SESSION['filter_glossary'])) {

	$_entry['filter_array'] = array();

	foreach($_SESSION['filter_glossary'] as $_entry['filter']) {
		//usr_name, usr_login, usr_email
		$_entry['filter_array'][] = 'CONCAT(glossary_title, glossary_tag, glossary_keyword, glossary_text) LIKE ' . _dbEscapeLike($_entry['filter']);
	}
	if(count($_entry['filter_array'])) {

		$_SESSION['filter_glossary'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
		$_entry['query'] .= $_SESSION['filter_glossary'];

	}

} elseif(isset($_SESSION['filter_glossary']) && is_string($_SESSION['filter_glossary'])) {

	$_entry['query'] .= $_SESSION['filter_glossary'];

}


// paginating values
$_entry['count_total'] = _dbQuery('SELECT * FROM '.DB_PREPEND.'phpwcms_glossary WHERE '.$_entry['query'], 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['glossary_page'] > $_entry['pages_total']) {
	$_SESSION['glossary_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}



?>
<h1 class="title mb-3"><?php echo $BLM['listing_title'] ?></h1>

<div class="form-group mb-3 text-center text-sm-start">
	<a class="btn btn-sm btn-blue" href="<?php echo GLOSSARY_HREF ?>&amp;edit=0" title="<?php echo $BLM['create_new'] ?>"><i class="fa fa-plus me-1"></i> <span><?php echo $BLM['create_new'] ?></span></a>
</div>

<div class="card">
	<div class="card-body">
		<form action="<?php echo GLOSSARY_HREF ?>" method="post" name="paginate" id="paginate">
			<input type="hidden" name="do_pagination" value="1" />
			<input type="hidden" name="showactive" id="showactive_input" value="<?php echo $_entry['list_active'] ?>" />
			<input type="hidden" name="showinactive" id="showinactive_input" value="<?php echo $_entry['list_inactive'] ?>" />
			<div class="row g-2 align-items-center mb-3">
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
							
								<?php if($_SESSION['glossary_page'] > 1): ?>
									<a href="<?php echo GLOSSARY_HREF ?>&amp;page=<?php echo ($_SESSION['glossary_page']-1) ?>" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-left"></i></a>
								<?php else: ?>
									<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-chevron-left"></i></button>
								<?php endif; ?>
							
							<input type="number" name="page" id="page" value="<?php echo $_SESSION['glossary_page'] ?>" class="form-control form-control-sm text-center w-25" />
							
								<span class="input-group-text">/ <?php echo $_entry['pages_total'] ?></span>
								<?php if($_SESSION['glossary_page'] < $_entry['pages_total']): ?>
									<a href="<?php echo GLOSSARY_HREF ?>&amp;page=<?php echo ($_SESSION['glossary_page']+1) ?>" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-right"></i></a>
								<?php else: ?>
									<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-chevron-right"></i></button>
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
						
							<button class="btn btn-secondary" type="submit" name="gofilter" title="<?php echo html($BL['be_filter']); ?>"><i class="fas fa-search"></i></button>
						
					</div>
				</div>

				<div class="col text-end">
					<select class="form-select form-select-sm" style="width: auto; display: inline-block;" onchange="location.href='<?php echo decode_entities(GLOSSARY_HREF) ?>&amp;c=' + this.value;">
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
						<th style="width: 40px;" class="text-center">&nbsp;</th>
						<th>Title</th>
						<th>Keyword</th>
						<th>Tag</th>
						<th style="width: 120px;" class="text-end">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$row_count = 0;

				$sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_glossary WHERE '.$_entry['query'].' ';
				$sql .= 'LIMIT '.(($_SESSION['glossary_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
				$data = _dbQuery($sql);

				if($data) {
					foreach($data as $row) {
						echo '<tr>';
						echo '<td class="text-center">';
						if ($row["glossary_highlight"]) {
							echo '<i class="fas fa-key text-warning" title="' . $BLM['glossary_entry'] . '"></i>';
						} else {
							echo '<i class="fas fa-tag text-muted" title="' . $BLM['glossary_entry'] . '"></i>';
						}
						echo '</td>';
						echo '<td>' . html($row["glossary_title"]) . '</td>';
						echo '<td>' . html($row["glossary_keyword"]) . '</td>';
						echo '<td>' . html($row["glossary_tag"]) . '</td>';
						echo '<td class="text-end text-nowrap">';
						echo '<div class="btn-group btn-group-sm" role="group" aria-label="glossary-actions-' . $row["glossary_id"] . '">';
						
						echo '<a href="' . GLOSSARY_HREF . '&amp;edit=' . $row["glossary_id"] . '" class="btn btn-sm btn-blue" title="' . $BL['be_func_struct_edit'] . '"><i class="fa fa-pencil-alt fa-fw"></i></a>';
						
						echo '<a href="' . GLOSSARY_HREF . '&amp;editid=' . $row["glossary_id"] . '&amp;verify=' . (($row["glossary_status"]) ? '0' : '1') . '" class="btn btn-sm ' . (($row["glossary_status"]) ? 'btn-success' : 'btn-warning') . '" title="Toggle Status">';
						echo '<i class="fas ' . (($row["glossary_status"]) ? 'fa-eye' : 'fa-eye-slash') . ' fa-fw"></i></a>';
						echo '</div>';
						
						echo '<a href="' . GLOSSARY_HREF . '&amp;delete=' . $row["glossary_id"] . '" class="btn btn-sm btn-danger ms-1" title="Delete"';
						echo ' onclick="return confirm(\'' . $BLM['delete_entry'] . ' ' . js_singlequote($row["glossary_title"]) . '\');">';
						echo '<i class="far fa-trash-alt"></i></a>';
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
