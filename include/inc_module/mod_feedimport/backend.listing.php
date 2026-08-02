<?php
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


?>
<h1 class="title mb-3"><?php echo $BLM['listing_title'] ?></h1>

<div class="form-group mb-3 text-center text-sm-left">
	<a class="btn btn-sm btn-blue" href="<?php echo MODULE_HREF ?>&amp;edit=0" title="<?php echo $BLM['create_new'] ?>"><i class="fas fa-rss fa-fw"></i> <span><?php echo $BLM['create_new'] ?></span></a>
</div>

<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-sm table-striped table-hover mb-0">
				<thead>
					<tr>
						<th style="width: 40px;" class="text-center">&nbsp;</th>
						<th>Name</th>
						<th>Source Host</th>
						<th style="width: 120px;" class="text-right">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$row_count = 0;
				$data = _dbGet('phpwcms_content', '*', 'cnt_status!=9 AND cnt_module='._dbEscape(MODULE_KEY));

				if ($data) {
					foreach($data as $row) {
						$url = parse_url($row['cnt_text'], PHP_URL_HOST);
						echo '<tr>';
						echo '<td class="text-center"><i class="fas fa-rss text-muted"></i></td>';
						echo '<td><a href="' . MODULE_HREF . '&amp;edit=' . $row["cnt_id"] . '" class="text-dark font-weight-bold">' . html($row['cnt_name']) . '</a></td>';
						echo '<td>' . html($url) . '</td>';
						echo '<td class="text-right text-nowrap">';
						echo '<div class="btn-group btn-group-sm" role="group" aria-label="feed-actions-' . $row["cnt_id"] . '">';
						
						echo '<a href="' . MODULE_HREF . '&amp;edit=' . $row["cnt_id"] . '" class="btn btn-sm btn-blue" title="' . $BL['be_func_struct_edit'] . '"><i class="fa fa-pencil-alt fa-fw"></i></a>';
						
						echo '<a href="' . MODULE_HREF . '&amp;editid=' . $row["cnt_id"] . '&amp;active=' . (($row["cnt_status"]) ? '0' : '1') . '" class="btn btn-sm ' . (($row["cnt_status"]) ? 'btn-success' : 'btn-warning') . '" title="Toggle Status">';
						echo '<i class="fas ' . (($row["cnt_status"]) ? 'fa-eye' : 'fa-eye-slash') . ' fa-fw"></i></a>';
						echo '</div>';
						
						echo '<a href="' . MODULE_HREF . '&amp;delete=' . $row["cnt_id"] . '" class="btn btn-sm btn-danger ml-1" title="' . $BL['be_cnt_delete'] . ': ' . html($row['cnt_name']) . '"';
						echo ' onclick="event.stopPropagation(); return confirm(\'' . js_singlequote($BLM['delete_entry'] . ' ' . $row['cnt_name']) . '\');">';
						echo '<i class="far fa-trash-alt"></i></a>';
						echo '</td>';
						echo '</tr>';
						$row_count++;
					}
				} else {
					echo '<tr><td colspan="4" class="text-center text-muted py-3">' . $BL['be_empty_search_result'] . '</td></tr>';
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>