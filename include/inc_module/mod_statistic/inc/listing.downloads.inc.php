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

// create pagination
if(isset($_GET['c'])) {
    $_SESSION['list_user_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
    $_SESSION['downloads_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
    $_SESSION['list_user_count'] = 25;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

    $_SESSION['download_filter']      = clean_slweg($_POST['filter']);
    if(empty($_SESSION['download_filter'])) {
        unset($_SESSION['download_filter']);
    } else {
        $_SESSION['download_filter']  = convertStringToArray($_SESSION['download_filter'], ' ');
        $_POST['filter']  = $_SESSION['filter'];
    }
    $_SESSION['list_search']      = clean_slweg($_POST['list_search']);
    if(empty($_SESSION['list_search'])) {
        unset($_SESSION['list_search']);
    }

    $_SESSION['downloads_page'] = intval($_POST['page']);
}

if(empty($_SESSION['downloads_page'])) {
    $_SESSION['downloads_page'] = 1;
}

$_entry['query'] = 'f_dlstart > 0 AND f_trash = 0';

if(isset($_SESSION['download_filter']) && is_array($_SESSION['download_filter']) && count($_SESSION['download_filter'])) {

    $_entry['filter_array'] = array();

    foreach($_SESSION['download_filter'] as $_entry['filter']) {
        //usr_name, usr_login, usr_email
        $_entry['filter_array'][] = 'CONCAT(f_name) LIKE ' . _dbEscapeLike($_entry['filter']);
    }
    if(count($_entry['filter_array'])) {
        $_SESSION['download_filter'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
        $_entry['query'] .= $_SESSION['download_filter'];
    }

} elseif(isset($_SESSION['download_filter']) && is_string($_SESSION['download_filter'])) {

    $_entry['query'] .= $_SESSION['download_filter'];

}
if(isset($_SESSION['list_search'])) {
    $_entry['sort'] = empty($_SESSION['list_search']) ? 'f_dlstart' : $_SESSION['list_search'];
} else {
    $_SESSION['list_search'] = 'f_dlstart';
    $_entry['sort'] = 'f_dlstart';
}

// paginating values
$_entry['count_total'] = _dbCount('SELECT COUNT(*) FROM '.DB_PREPEND.'phpwcms_file WHERE '.$_entry['query']);
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['downloads_page'] > $_entry['pages_total']) {
    $_SESSION['downloads_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}

// now retrieve all downloads
$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_file WHERE ".$_entry['query']." ORDER BY ".$_entry['sort']." DESC";
$sql .= ' LIMIT '.(($_SESSION['downloads_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
$result = _dbQuery($sql);
?>

<h2 class="mb-3"><?php echo $BLM['listing_title'] ?></h2>

<form action="<?php echo statistic_url('controller=downloads') ?>" method="post" name="paginate" id="paginate">
	<input type="hidden" name="do_pagination" value="1" />
	<div class="row g-2 align-items-center mb-3">
		<?php if($_entry['pages_total'] > 1): ?>
			<div class="col-auto">
				<div class="input-group input-group-sm">
					
						<?php if($_SESSION['downloads_page'] > 1): ?>
							<a href="<?php echo statistic_url('controller=downloads') ?>&amp;page=<?php echo ($_SESSION['downloads_page']-1) ?>" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-left"></i></a>
						<?php else: ?>
							<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-chevron-left"></i></button>
						<?php endif; ?>
					
					<input type="number" name="page" id="page" value="<?php echo $_SESSION['downloads_page'] ?>" class="form-control form-control-sm text-center w-25" />
					
						<span class="input-group-text">/ <?php echo $_entry['pages_total'] ?></span>
						<?php if($_SESSION['downloads_page'] < $_entry['pages_total']): ?>
							<a href="<?php echo statistic_url('controller=downloads') ?>&amp;page=<?php echo ($_SESSION['downloads_page']+1) ?>" class="btn btn-secondary btn-sm"><i class="fas fa-chevron-right"></i></a>
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
					echo html_specialchars(implode(' ', $_POST['filter']));
				}
				?>" class="form-control" placeholder="<?php echo html($BL['be_ftab_search']); ?>..." title="<?php echo html($BL['be_filter']); ?>" style="min-width: 250px;" />
				<select name="list_search" class="form-control" id="list_search">
					<option value="">-- Sortierung --</option>
					<option value="f_name" <?php echo ($_SESSION['list_search'] == 'f_name' ? ' selected' : '') ?>><?php echo $BLM['filename'] ?></option>
					<option value="f_dlstart" <?php echo ($_SESSION['list_search'] == 'f_dlstart' ? ' selected' : '') ?>><?php echo $BLM['downloads_start'] ?></option>
					<option value="f_dlfinal" <?php echo ($_SESSION['list_search'] == 'f_dlfinal' ? ' selected' : '') ?>><?php echo $BLM['downloads_end'] ?></option>
					<option value="f_created" <?php echo ($_SESSION['list_search'] == 'f_created' ? ' selected' : '') ?>><?php echo $BLM['erstellt'] ?></option>
				</select>
				
					<button class="btn btn-secondary" type="submit" name="gofilter" title="<?php echo html($BL['be_filter']); ?>"><i class="fas fa-search"></i></button>
				
			</div>
		</div>

		<div class="col text-end">
			<select class="form-select form-select-sm" style="width: auto; display: inline-block;" onchange="location.href='<?php echo statistic_url('controller=downloads') ?>&amp;c=' + this.value;">
				<?php foreach([10, 25, 50, 100, 250] as $c): ?>
					<option value="<?php echo $c ?>"<?php if($_SESSION['list_user_count'] == $c) echo ' selected'; ?>><?php echo $c ?></option>
				<?php endforeach; ?>
				<option value="all"<?php if($_SESSION['list_user_count'] == 99999) echo ' selected'; ?>><?php echo $BL['be_ftptakeover_all'].' '.$_entry['count_total'] ?></option>
			</select>
		</div>
	</div>
</form>

<div class="table-responsive">
	<table class="table table-sm table-striped table-hover table-valign-middle mb-0">
		<thead>
			<tr>
				<th><?php echo $BLM['filename'] ?></th>
				<th><?php echo $BLM['downloads_start'] ?></th>
				<th><?php echo $BLM['downloads_end'] ?></th>
				<th><?php echo $BLM['erstellt'] ?></th>
			</tr>
		</thead>
		<tbody>
        <?php
		$x = 0;
		if(isset($result[0]['f_name'])) {
			foreach($result as $data) {
				echo '  <tr title="'.html_specialchars($data["f_name"]).'">';
				echo '    <td><a href="fileinfo.php?public&fid='.$data["f_id"].'" target="_blank">' . (empty($data["f_name"]) ? '-' : html_specialchars($data["f_name"]) ) . "</a>&nbsp;</td>" . LF;
				echo '    <td>'.$data["f_dlstart"]."&nbsp;</td>" . LF;
				echo '    <td>'.$data["f_dlfinal"]."&nbsp;</td>" . LF;
				echo '    <td nowrap>&nbsp;'.@date($BL['be_fprivedit_dateformat'], $data["f_created"])."</td>" . LF;
				echo '  </tr>' . LF;
				$x++;
			}
		}
		?>
		</tbody>
	</table>
</div>
</div>
</div>

  </div>
</div>
