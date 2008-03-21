<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2008 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
   This script is part of PHPWCMS. The PHPWCMS web content management system is
   free software; you can redistribute it and/or modify it under the terms of
   the GNU General Public License as published by the Free Software Foundation;
   either version 2 of the License, or (at your option) any later version.
  
   The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html
   A copy is found in the textfile GPL.txt and important notices to the license 
   from the author is found in LICENSE.txt distributed with these scripts.
  
   This script is distributed in the hope that it will be useful, but WITHOUT ANY 
   WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
   PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 
   This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************************/

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
		<li<?php if(empty($_GET['listcampaign']) && empty($_GET['listadplace'])) echo ' class="activeTab"'; ?>><a href="<?php echo MODULE_HREF ?>"><span><?php echo $BLM['ad_summary'] ?></span></a></li>
		<li<?php if(isset($_GET['listcampaign'])) echo ' class="activeTab"'; ?>><a href="<?php echo MODULE_HREF ?>&amp;listcampaign=1"><span><?php echo $BLM['campaign_entry'] ?></span></a></li>
		<li<?php if(isset($_GET['listadplace'])) echo ' class="activeTab"'; ?>><a href="<?php echo MODULE_HREF ?>&amp;listadplace=1"><span><?php echo $BLM['ad_format'] ?></span></a></li>
	</ul>
	<br class="clear" />
</div>
