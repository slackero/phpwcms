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

// create pagination
if(isset($_GET['c'])) {
	$_SESSION['list_user_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
	$_SESSION['ads_page'] = intval($_GET['page']);
}

// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
	$_SESSION['list_user_count'] = 25;
}

// paginate and search form processing
if(isset($_POST['do_pagination'])) {

	$_SESSION['list_active']	= empty($_POST['showactive']) ? 0 : 1;
	$_SESSION['list_inactive']	= empty($_POST['showinactive']) ? 0 : 1;

	$_SESSION['filter_ad_campaign'] = clean_slweg($_POST['filter']);
	if(empty($_SESSION['filter_ad_campaign'])) {
		unset($_SESSION['filter_ad_campaign']);
	} else {
		$_SESSION['filter_ad_campaign']	= convertStringToArray($_SESSION['filter_ad_campaign'], ' ');
		$_POST['filter'] = $_SESSION['filter_ad_campaign'];
	}

	$_SESSION['ads_page'] = intval($_POST['page']);

}

if(empty($_SESSION['ads_page'])) {
	$_SESSION['ads_page'] = 1;
}

$_entry['list_active']		= isset($_SESSION['list_active'])	? $_SESSION['list_active']		: 1;
$_entry['list_inactive']	= isset($_SESSION['list_inactive'])	? $_SESSION['list_inactive']	: 1;


// set correct status query
if($_entry['list_active'] != $_entry['list_inactive']) {

	if(!$_entry['list_active']) {
		$_entry['query'] .= 'adcampaign_status=0';
	}
	if(!$_entry['list_inactive']) {
		$_entry['query'] .= 'adcampaign_status=1';
	}

} else {
	$_entry['query'] .= 'adcampaign_status!=9';
}

if(isset($_SESSION['filter_ad_campaign']) && is_array($_SESSION['filter_ad_campaign']) && count($_SESSION['filter_ad_campaign'])) {

	$_entry['filter_array'] = array();

	foreach($_SESSION['filter_ad_campaign'] as $_entry['filter']) {
		//usr_name, usr_login, usr_email
		$_entry['filter_array'][] = "CONCAT(adcampaign_title, adcampaign_comment) LIKE '%".aporeplace($_entry['filter'])."%'";
	}
	if(count($_entry['filter_array'])) {

		$_SESSION['filter_ad_campaign'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
		$_entry['query'] .= $_SESSION['filter_ad_campaign'];

	}

} elseif(isset($_SESSION['filter_ad_campaign']) && is_string($_SESSION['filter_ad_campaign'])) {

	$_entry['query'] .= $_SESSION['filter_ad_campaign'];

}


// paginating values
$_entry['count_total'] = _dbQuery('SELECT * FROM '.DB_PREPEND.'phpwcms_ads_campaign WHERE '.$_entry['query'], 'COUNT');
$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['ads_page'] > $_entry['pages_total']) {
	$_SESSION['ads_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
}



?>
<form action="<?php echo MODULE_HREF ?>&amp;listcampaign=1" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="paginate" summary="">
	<tr>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>

				<td><input type="checkbox" name="showactive" id="showactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_active'], 1) ?> /></td>
				<td><label for="showactive"><img src="img/button/aktiv_12x13_1.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>
				<td><input type="checkbox" name="showinactive" id="showinactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_inactive'], 1) ?> /></td>
				<td><label for="showinactive"><img src="img/button/aktiv_12x13_0.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>

<?php
if($_entry['pages_total'] > 1) {

	echo '<td class="chatlist">|&nbsp;</td>';
	echo '<td>';
	if($_SESSION['ads_page'] > 1) {
		echo '<a href="'.MODULE_HREF.'&amp;listcampaign=1&amp;page='.($_SESSION['ads_page']-1).'">';
		echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td>';
	echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['ads_page'];
	echo '"  class="textinput" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
	echo '<td class="chatlist">/'.$_entry['pages_total'].'&nbsp;</td>';
	echo '<td>';
	if($_SESSION['ads_page'] < $_entry['pages_total']) {
		echo '<a href="'.MODULE_HREF.'&amp;listcampaign=1&amp;page='.($_SESSION['ads_page']+1).'">';
		echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="img/famfamfam/action_forward.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td><td class="chatlist">&nbsp;|&nbsp;</td>';

} else {

	echo '<td class="chatlist">|&nbsp;<input type="hidden" name="page" id="page" value="1" /></td>';

}
?>
				<td><input type="search" name="filter" id="filter" size="10" value="<?php

				if(isset($_POST['filter']) && is_array($_POST['filter']) ) {
					echo html(implode(' ', $_POST['filter']));
				}

				?>" class="textinput" style="margin:0 2px 0 0;width:110px;text-align:left;" title="filter results by username, name or email" /></td>
				<td><input type="image" name="gofilter" src="img/famfamfam/action_go.gif" style="margin-right:3px;" /></td>

			</tr>
		</table></td>

	<td class="chatlist" align="right">
		<a href="<?php echo MODULE_HREF ?>&amp;listcampaign=1&amp;c=10">10</a>
		<a href="<?php echo MODULE_HREF ?>&amp;listcampaign=1&amp;c=25">25</a>
		<a href="<?php echo MODULE_HREF ?>&amp;listcampaign=1&amp;c=50">50</a>
		<a href="<?php echo MODULE_HREF ?>&amp;listcampaign=1&amp;c=100">100</a>
		<a href="<?php echo MODULE_HREF ?>&amp;listcampaign=1&amp;c=250">250</a>
		<a href="<?php echo MODULE_HREF ?>&amp;listcampaign=1&amp;c=all"><?php echo $BL['be_ftptakeover_all'] ?></a>
	</td>

	</tr>
</table>
</form>

<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="" class="ads">

	<tr>
		<th width="25">&nbsp;</th>
		<th><?php echo $BLM['campaign_entry'] ?></th>
		<th class="listFormat nowrap" nowrap="nowrap"><?php echo $BLM['ad_from-to'] ?></th>
		<th class="listFormat"><?php echo $BLM['adplace'] ?></th>
		<th>&nbsp;</th>
	</tr>

<?php
// loop listing available newsletters
$row_count = 0;


$sql  = 'SELECT *, UNIX_TIMESTAMP(ac.adcampaign_datestart) AS adcampaign_start, ';
$sql .= 'UNIX_TIMESTAMP(ac.adcampaign_dateend) AS adcampaign_end ';
$sql .= 'FROM '.DB_PREPEND.'phpwcms_ads_campaign ac ';
$sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_ads_place ap ON ';
$sql .=	'ac.adcampaign_place=ap.adplace_id  ';
$sql .= 'WHERE '.$_entry['query'];
if($_SESSION['ads_page'] > 0 && $_SESSION['list_user_count']) {
	$sql .= ' LIMIT '.(($_SESSION['ads_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
}
$data = _dbQuery($sql);

if($data) {

	foreach($data as $row) {

		echo '<tr'.( ($row_count % 2) ? ' class="adsAltRow"' : '' ).'>'.LF;
		echo '	<td width="25" style="padding:2px 3px 2px 4px;"><img src="img/famfamfam/transmit.gif" alt="'.$BLM['campaign_entry'].'" /></td>'.LF;
		echo '	<td width="50%">'.html($row["adcampaign_title"])."</td>\n";

		echo '	<td class="listFormat nowrap">&nbsp;'.html(date($BLM['list_date_format'], $row["adcampaign_start"]).'&#8211;'.date($BLM['list_date_format'], $row["adcampaign_end"]))."&nbsp;</td>\n";

		echo '	<td class="listFormat nowrap">'.$row["adplace_width"].'x'.$row["adplace_height"].' {ADS_'.$row["adplace_id"]."}&nbsp;</td>\n";

		echo '	<td align="right" class="button_td nowrap">';

		echo '<a href="'.MODULE_HREF.'&amp;campaign=1&amp;edit='.$row["adcampaign_id"].'">';
		echo '<img src="img/button/edit_22x13.gif" border="0" alt="" /></a>';

		echo '<a href="'.MODULE_HREF.'&amp;campaign=1&amp;duplicate='.$row["adcampaign_id"].'" ';
		echo 'title="'.$BLM['duplicate_title'].'" onclick="return confirm(\''.js_singlequote($BLM['duplicate_campaign']).' \n'.js_singlequote($BLM['campaign_title'].': '.html('"'.$row["adcampaign_title"].'"')).'\');"';
		echo '><img src="img/button/copy_13x13.gif" border="0" alt="" /></a>';

		echo '<a href="'.MODULE_HREF.'&amp;campaign=1&amp;editid='.$row["adcampaign_id"].'&amp;verify=';
		echo (($row["adcampaign_status"]) ? '0' : '1').'">';
		echo '<img src="img/button/aktiv_12x13_'.$row["adcampaign_status"].'.gif" border="0" alt="" /></a>';

		echo '<a href="'.MODULE_HREF.'&amp;campaign=1&amp;delete='.$row["adcampaign_id"];
		echo '" title="delete: '.html($row["adcampaign_title"]).'"';
		echo ' onclick="return confirm(\''.$BLM['delete_entry'].js_singlequote($row["adcampaign_title"]).'\');">';
		echo '<img src="img/button/trash_13x13_1.gif" border="0" alt=""></a>';

		echo "</td>\n</tr>\n";

		$row_count++;
	}

	echo '<tr><td colspan="5" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>';

} else {

	echo '<tr><td colspan="5" class="tdtop5">'.$BL['be_empty_search_result'].'</td></tr>';

}

?>
</table>