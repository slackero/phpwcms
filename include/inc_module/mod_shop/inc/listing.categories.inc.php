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
    die('You Cannot Access This Script Directly, Have a Nice Day.');
}
// ----------------------------------------------------------------

$_entry['query'] = '';
$_entry['post_filter'] = $_SESSION['filter_shop_category_raw'] ?? '';

// create pagination
if (isset($_GET['c'])) {
    $_SESSION['list_count'] = $_GET['c'] === 'all' ? '99999' : (int)$_GET['c'];
}
if (isset($_GET['page'])) {
    $_SESSION['detail_page'] = (int)$_GET['page'];
}

// set default values for paginating
if (empty($_SESSION['list_count'])) {
    $_SESSION['list_count'] = 25;
}

// paginate and search form processing
if (isset($_POST['do_pagination'])) {

    $_SESSION['list_active'] = empty($_POST['showactive']) ? 0 : 1;
    $_SESSION['list_inactive'] = empty($_POST['showinactive']) ? 0 : 1;

    $_entry['post_filter'] = clean_slweg($_POST['filter']);
    $_SESSION['filter_shop_category_raw'] = $_entry['post_filter'];
    $_SESSION['filter_shop_category'] = $_entry['post_filter'];
    if (empty($_SESSION['filter_shop_category'])) {
        unset($_SESSION['filter_shop_category'], $_SESSION['filter_shop_category_raw']);
    } else {
        $_SESSION['filter_shop_category'] = convertStringToArray($_SESSION['filter_shop_category'], ' ');
        $_POST['filter'] = $_SESSION['filter_shop_category'];
    }

    $_SESSION['detail_page'] = (int)($_POST['page'] ?? 1);
}

if (empty($_SESSION['detail_page'])) {
    $_SESSION['detail_page'] = 1;
}

$_entry['list_active'] = $_SESSION['list_active'] ?? 1;
$_entry['list_inactive'] = (int)($_SESSION['list_inactive'] ?? 1);

// set correct status query
if ($_entry['list_active'] !== $_entry['list_inactive']) {
    if (!$_entry['list_active']) {
        $_entry['query'] .= 'cat_status=0';
    }
    if (!$_entry['list_inactive']) {
        $_entry['query'] .= 'cat_status=1';
    }
} else {
    $_entry['query'] .= 'cat_status!=9';
}
$_entry['query'] .= " AND cat_type='module_shop'";

if (isset($_SESSION['filter_shop_category']) && is_array($_SESSION['filter_shop_category']) && count($_SESSION['filter_shop_category'])) {

    $_entry['filter_array'] = array();

    foreach ($_SESSION['filter_shop_category'] as $_entry['filter']) {
        //usr_name, usr_login, usr_email
        $_entry['filter_array'][] = 'cat_name LIKE ' . _dbEscapeLike($_entry['filter']);
        $_entry['filter_array'][] = 'cat_info LIKE ' . _dbEscapeLike($_entry['filter']);
    }
    if (count($_entry['filter_array'])) {

        $_SESSION['filter_shop_category'] = ' AND (' . implode(' OR ', $_entry['filter_array']) . ')';
        $_entry['query'] .= $_SESSION['filter_shop_category'];

    }

} elseif (isset($_SESSION['filter_shop_category']) && is_string($_SESSION['filter_shop_category'])) {

    $_entry['query'] .= $_SESSION['filter_shop_category'];

}

// paginating values
$_entry['count_total'] = _dbQuery('SELECT COUNT(cat_id) FROM ' . DB_PREPEND . 'phpwcms_categories WHERE ' . $_entry['query'], 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_count']);
if ($_SESSION['detail_page'] > $_entry['pages_total']) {
    $_SESSION['detail_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}
?>

<div class="form-group mb-3 text-center text-sm-start">
    <a class="btn btn-sm btn-blue me-2" href="<?php echo shop_url(array('controller=cat', 'edit=0')) ?>"
        title="<?php echo $BLM['create_new'] ?>"><i class="fa-solid fa-plus me-1"></i>
        <span><?php echo $BLM['create_new'] ?></span></a>
</div>

<form action="<?php echo shop_url('controller=cat') ?>" method="post" name="paginate" id="paginate">
    <input type="hidden" name="do_pagination" value="1" />
	<input type="hidden" name="showactive" id="showactive_input" value="<?php echo $_entry['list_active'] ?>" />
	<input type="hidden" name="showinactive" id="showinactive_input" value="<?php echo $_entry['list_inactive'] ?>" />
    <div class="row g-2 align-items-center">
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
            <select class="form-select form-select-sm" style="width: auto; display: inline-block;" onchange="location.href='phpwcms.php?do=modules&amp;module=shop&amp;controller=cat&amp;c=' + this.value;">
                <option value="5"<?php if($_SESSION['list_count'] == '5') echo ' selected'; ?>>5</option>
                <option value="10"<?php if($_SESSION['list_count'] == '10') echo ' selected'; ?>>10</option>
                <option value="25"<?php if($_SESSION['list_count'] == '25') echo ' selected'; ?>>25</option>
                <option value="50"<?php if($_SESSION['list_count'] == '50') echo ' selected'; ?>>50</option>
                <option value="100"<?php if($_SESSION['list_count'] == '100') echo ' selected'; ?>>100</option>
                <option value="all"<?php if($_SESSION['list_count'] == 99999) echo ' selected'; ?>><?php echo $BL['be_ftptakeover_all'] ?></option>
            </select>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-sm table-valign-middle mb-0 mt-2">
        <?php
        // loop listing available newsletters
        $row_count = 0;

        $sql = 'SELECT C1.*, ';
        $sql .= "IFNULL(CONCAT(C2.cat_name, ' / ', C1.cat_name), C1.cat_name) AS category FROM ";
        $sql .= DB_PREPEND . 'phpwcms_categories C1 ';
        $sql .= 'LEFT JOIN ' . DB_PREPEND . 'phpwcms_categories C2 ';
        $sql .= 'ON C1.cat_pid=C2.cat_id ';
        $sql .= 'WHERE ' . str_replace('cat_', 'C1.cat_', $_entry['query']) . ' ';
        $sql .= 'ORDER BY C1.cat_sort DESC, C2.cat_sort DESC, category ASC ';
        $sql .= 'LIMIT ' . (($_SESSION['detail_page'] - 1) * $_SESSION['list_count']) . ',' . $_SESSION['list_count'];

        $data = _dbQuery($sql);

        $_controller_link = shop_url('controller=cat');

        if (isset($data[0]['cat_id'])) {

            foreach ($data as $row) {

                echo '<tr';
                if (!$row['cat_pid']) {
                    echo ' data-bs-toggle="tooltip" data-bs-html="true" title="' . $BL['be_admin_page_category'] . ' ID: <b>' . $row['cat_id'] . '</b><br />' . $BL['be_cnt_sorting'] . ': <b>' . $row['cat_sort'] . '</b>)"';
                }
                echo '>' . LF;

                echo '<td width="25" style="padding:2px 3px 2px 4px;">';
                echo '<i class="fa-solid fa-tag fa-fw text-';
                echo $row['cat_pid'] ? 'muted' : 'blue';
                echo '"></i></td>' . LF;

                echo '<td class="dir" width="85%">';
                echo $row['cat_pid'] ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '&nbsp;';
                echo html_specialchars($row['category']) . "</td>\n";
                echo '<td class="dir" width="3%" align="center">&nbsp;' . $row['cat_sort'] . '&nbsp;</td>';
                echo '<td width="10%" class="text-end text-nowrap">';
                echo '<div class="btn-group btn-group-sm" role="group" aria-label="shop-cat-actions-' . $row['cat_id'] . '">';
                echo '<a class="btn btn-sm btn-blue" href="' . $_controller_link . '&amp;edit=' . $row['cat_id'] . '">';
                echo '<i class="fa-solid fa-pencil-alt"></i></a>';
                echo '<button id="abtnshop' . $row['cat_id'] . '" class="btn fa btn-sm visible ';
                echo ((int)$row['cat_status'] === 0 ? 'btn-warning' : 'btn-success') . '" data-id="' . $row['cat_id'];
                echo '" data-type="shop" data-table="categories" data-field="cat_status" data-fieldid="cat_id" aria-disabled="true" data-bs-toggle="tooltip" title="';
                echo $BL['be_tooltip_visibility'] . '"></button>';
                echo '</div>';
                echo '<a class="btn btn-sm btn-danger ms-1" href="' . $_controller_link . '&amp;delete=' . $row['cat_id'];
                echo '" title="delete: ' . html_specialchars($row['cat_name']) . '"';
                echo ' onclick="return confirm(\'' . $BLM['delete_entry'] . js_singlequote($row['cat_name']) . '\');">';
                echo '<i class="far fa-trash-alt"></i></a>';

                echo '</td>' . LF;
                echo '</tr>' . LF;

                $row_count++;
            }
        } else {
            echo '<tr><td colspan="4" class="tdtop5">' . $BL['be_empty_search_result'] . '</td></tr>';
        }
        ?>
    </table>
</div>
