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


?>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-sm table-striped table-hover mb-0">
				<thead>
					<tr>
						<th style="width: 40px;" class="text-center">&nbsp;</th>
						<th><?php echo $BLM['adplace'] ?></th>
						<th><?php echo $BLM['ad_format'] ?></th>
						<th>RT</th>
						<th><?php echo $BLM['ad_wxh'] ?></th>
						<th style="width: 120px;" class="text-right">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$row_count = 0;

				$sql  = 'SELECT * FROM '.DB_PREPEND.'cmsgo_ads_place ap ';
				$sql .= 'LEFT JOIN '.DB_PREPEND.'cmsgo_ads_formats af ON ';
				$sql .=	'ap.adplace_format=af.adformat_id ';
				$sql .= 'WHERE adplace_status!=9';
				$data = _dbQuery($sql);

				$sql  = 'SELECT COUNT(*) FROM '.DB_PREPEND.'cmsgo_ads_campaign WHERE adcampaign_status!=9 AND adcampaign_place=';

				foreach($data as $row) {
					echo '<tr>';
					echo '<td class="text-center"><i class="fas fa-th-large text-muted"></i></td>';
					echo '<td>' . html($row["adplace_title"]) . '</td>';
					$_format_key = 'format_' . strtolower(str_replace(array(' ', '-'), '_', $row["adformat_title"]));
					$_format_title = isset($BLM[$_format_key]) ? $BLM[$_format_key] : $row["adformat_title"];
					echo '<td>' . html($_format_title) . '</td>';
					echo '<td>{ADS_' . $row["adplace_id"] . '}</td>';
					echo '<td>' . $row["adplace_width"] . 'x' . $row["adplace_height"] . '</td>';
					echo '<td class="text-right">';
					
					echo '<a href="' . MODULE_HREF . '&amp;adplace=1&amp;edit=' . $row["adplace_id"] . '" class="btn btn-sm btn-blue mr-1" title="Edit"><i class="fas fa-edit fa-fw"></i></a>';
					
					echo '<a href="' . MODULE_HREF . '&amp;adplace=1&amp;editid=' . $row["adplace_id"] . '&amp;verify=' . (($row["adplace_status"]) ? '0' : '1') . '" class="btn btn-sm ' . (($row["adplace_status"]) ? 'btn-success' : 'btn-secondary') . ' mr-1" title="Toggle Status">';
					echo '<i class="fas ' . (($row["adplace_status"]) ? 'fa-eye' : 'fa-eye-slash') . ' fa-fw"></i></a>';
					
					if(_dbQuery($sql.$row['adplace_id'], 'COUNT')) {
						echo '<button class="btn btn-sm btn-danger" disabled title="Delete"><i class="fas fa-trash fa-fw"></i></button>';
					} else {
						echo '<a href="' . MODULE_HREF . '&amp;adplace=1&amp;delete=' . $row["adplace_id"] . '" class="btn btn-sm btn-danger" title="Delete"';
						echo ' onclick="return confirm(\'' . $BLM['delete_adplace'] . js_singlequote($row["adplace_title"]) . '\');">';
						echo '<i class="fas fa-trash fa-fw"></i></a>';
					}
					
					echo '</td>';
					echo '</tr>';
					$row_count++;
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
