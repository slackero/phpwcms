<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2012 Oliver Georgi <oliver@phpwcms.de> // All rights reserved.
 
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


echo '<h1 class="title">'.$BL['be_article_urlalias'].' ('.$BL['be_ftptakeover_active'].')</h1>'.LF;

// now retrieve all articles
$sql  =	"SELECT *, DATE_FORMAT(article_tstamp, '%Y-%m-%d') AS article_timestamp ";
$sql .= "FROM ".DB_PREPEND."phpwcms_article WHERE ";
$sql .= "article_public=1 AND article_aktiv=1 AND article_deleted=0 AND article_nosearch!='1' AND ";
$sql .= "article_nositemap=1 AND article_begin < NOW() AND article_end > NOW() ";
$sql .= "ORDER BY article_alias";

$result = _dbQuery($sql);

?>

<table width="538" border="0" cellpadding="0" cellspacing="0" class="listing" summary="">

	<tr class="header">
		<th class="column colfirst news"><?php echo $BL['be_article_urlalias'] ?></th>
		<th class="column">&nbsp;&nbsp;&nbsp;ID&nbsp;</th>
		<th class="column">&nbsp;</th>
		<th class="column collast"> </th>
	</tr>

<?php

$x = 0;

foreach($result as $data) {

	// now add article URL
	echo '	<tr class="row'.($x%2?' alt': '').'" title="'.html_specialchars('[ID:'.$data["article_id"].'] '.$data["article_title"]).'">';
   	echo '		<td width="80%">' . (empty($data["article_alias"]) ? '-' : html_specialchars($data["article_alias"]) ) . "&nbsp;</td>" . LF;
	echo '		<td align="right">'.$data["article_id"]."&nbsp;</td>" . LF;
	echo '		<td>&nbsp;'.$data["article_timestamp"]."</td>" . LF;
	echo '		<td align="right" width="30"><a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=1&amp;id='.$data["article_id"].'">';
	echo '<img src="img/button/edit_22x13.gif" alt="" border="0" height="13" width="22" /></a>';
	echo '</td>' . LF . '	</tr>' . LF;  
	
	$x++;
}

?>

</table>
