<?php
// ----------------------------------------------------------------
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


?>
<h1 class="title" style="margin-bottom:10px"><?php echo $BLM['listing_title'] ?></h1>

<div class="navBarLeft imgButton chatlist">
	&nbsp;&nbsp;
	<a href="<?php echo MODULE_HREF ?>&amp;edit=0" title="<?php echo $BLM['create_new'] ?>"><img src="img/famfamfam/rss_add.png" alt="Add" border="0" /><span><?php echo $BLM['create_new'] ?></span></a>
</div>

<!-- No Pagination or filter -->

<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">

	<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="3" /></td></tr>
	<tr><td colspan="4" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<?php

// loop listing available rates
$row_count = 0;

$data = _dbGet('phpwcms_content', '*', 'cnt_status!=9 AND cnt_module='._dbEscape(MODULE_KEY));

foreach($data as $row) {

	$url = parse_url($row['cnt_text'], PHP_URL_HOST);

	echo '<tr style="cursor:pointer"'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' );
	echo ' onclick="document.location=\''.MODULE_HREF.'&amp;edit='.$row["cnt_id"].'\';">'.LF;
	echo '<td width="25" style="padding:2px 3px 2px 4px;">';
	echo '<img src="img/famfamfam/rss.png" alt="'.$BLM['backend_menu'].'" /></td>'.LF;
	echo '<td class="dir nowrap" width="55%" style="padding-left:3px" nowrap="nowrap">'.html($row['cnt_name'])."</td>\n";

	echo '<td class="dir nowrap" width="35%" nowrap="nowrap">&nbsp;'.$url."&nbsp;&nbsp;</td>\n";

	echo '<td width="10%" align="right" nowrap="nowrap" class="button_td nowrap">';

	echo '<a href="'.MODULE_HREF.'&amp;edit='.$row["cnt_id"].'">';
	echo '<img src="img/button/edit_22x13.gif" border="0" alt="" /></a>';

	echo '<a href="'.MODULE_HREF.'&amp;editid='.$row["cnt_id"].'&amp;active=';
	echo (($row["cnt_status"]) ? '0' : '1').'">';
	echo '<img src="img/button/aktiv_12x13_'.$row["cnt_status"].'.gif" border="0" alt="" /></a>';

	echo '<a href="'.MODULE_HREF.'&amp;delete='.$row["cnt_id"];
	echo '" title="' . $BL['be_cnt_delete'] .': '.html($row['cnt_name']).'"';
	echo ' onclick="return confirm(\''.js_singlequote($BLM['delete_entry'] . ' ' . $row['cnt_name']).'\');">';
	echo '<img src="img/button/trash_13x13_1.gif" border="0" alt="" /></a>';

	echo "</td>\n</tr>\n";

	$row_count++;
}

if($row_count) {
	echo '<tr><td colspan="4" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>';
}

?>

	<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="15" /></td></tr>
</table>