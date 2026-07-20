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


// Module/Plug-in Ads/Banner Management

$_entry['query']			= '';



?>
<h1 class="title mb-3"><?php echo $BLM['listing_title'] ?></h1>

<div class="form-group mb-3 text-center text-sm-left">
	<a class="btn btn-sm btn-blue mr-2" href="<?php echo MODULE_HREF ?>&amp;campaign=1&amp;edit=0" title="<?php echo $BLM['create_new'] ?>"><i class="fas fa-bullhorn fa-fw"></i> <span><?php echo $BLM['create_new'] ?></span></a>
	<a class="btn btn-sm btn-secondary" href="<?php echo MODULE_HREF ?>&amp;adplace=1&amp;edit=0" title="<?php echo $BLM['new_adplace'] ?>"><i class="fas fa-th-large fa-fw"></i> <span><?php echo $BLM['new_adplace'] ?></span></a>
</div>

<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs">
			<li class="nav-item">
				<a class="nav-link <?php if(isset($_GET['listcampaign']) || empty($_GET['listadplace'])) echo 'active'; ?>" href="<?php echo MODULE_HREF ?>&amp;listcampaign=1"><?php echo $BLM['campaign_entry'] ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?php if(isset($_GET['listadplace'])) echo 'active'; ?>" href="<?php echo MODULE_HREF ?>&amp;listadplace=1"><?php echo $BLM['ad_format'] ?></a>
			</li>
		</ul>
	</div>
