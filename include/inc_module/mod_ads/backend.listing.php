<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/

// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


// Module/Plug-in Ads/Banner Management

$_entry['query']			= '';



?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['listing_title'] ?></h1>

<div class="navBarLeft imgButton chatlist">
	&nbsp;&nbsp;
	<a href="<?php echo MODULE_HREF ?>&amp;campaign=1&amp;edit=0" title="<?php echo $BLM['create_new'] ?>"><img src="img/famfamfam/transmit_add.gif" alt="New" border="0" /><span><?php echo $BLM['create_new'] ?></span></a>
	&nbsp;
	<a href="<?php echo MODULE_HREF ?>&amp;adplace=1&amp;edit=0" title="<?php echo $BLM['new_adplace']	 ?>"><img src="img/famfamfam/layout_add.gif" alt="New" border="0" /><span><?php echo $BLM['new_adplace'] ?></span></a>
</div>

<div id="tabsG">
	<ul>
<?php /*		<li<?php if(empty($_GET['listcampaign']) && empty($_GET['listadplace'])) echo ' class="activeTab"'; ?>><a href="<?php echo MODULE_HREF ?>"><span><?php echo $BLM['ad_summary'] ?></span></a></li> */ ?>
		<li<?php if(isset($_GET['listcampaign']) || empty($_GET['listadplace'])) echo ' class="activeTab"'; ?>><a href="<?php echo MODULE_HREF ?>&amp;listcampaign=1"><span><?php echo $BLM['campaign_entry'] ?></span></a></li>
		<li<?php if(isset($_GET['listadplace'])) echo ' class="activeTab"'; ?>><a href="<?php echo MODULE_HREF ?>&amp;listadplace=1"><span><?php echo $BLM['ad_format'] ?></span></a></li>
	</ul>
	<br class="clear" />
</div>
