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

$_userInfo = array();

// delete all duplicate addresses
if(isset($_GET['duplicate']) && $_GET['duplicate'] == 'remove') {
	$data = _dbQuery('SELECT COUNT(*) AS address_count, address_email FROM '.DB_PREPEND.'phpwcms_address GROUP BY address_email');
	if($data) {

		foreach($data as $value) {

			// check for multiple entries
			if($value['address_count'] > 1) {

				$sql  = 'SELECT address_id FROM '.DB_PREPEND.'phpwcms_address ';
				$sql .= "WHERE address_email='".aporeplace($value['address_email'])."' ";
				$sql .= 'ORDER BY address_verified DESC, address_name DESC LIMIT 1';
				$dataID = _dbQuery($sql);

				if(!empty($dataID[0]['address_id'])) {
					$sql  = 'DELETE FROM '.DB_PREPEND.'phpwcms_address ';
					$sql .= "WHERE address_email='".aporeplace($value['address_email'])."' ";
					$sql .= "AND address_id != ".intval($dataID[0]['address_id']);
					@_dbQuery($sql, 'DELETE');
				}

			}
		}
	}
	headerRedirect(PHPWCMS_URL.'phpwcms.php?'.get_token_get_string().'&do=messages&p=4');
}


// delete susbcriber
if(isset($_GET["del"]) && isset($_GET["s"]) && $_GET["del"] == $_GET["s"]) {
	_dbQuery("DELETE FROM ".DB_PREPEND."phpwcms_address WHERE address_id=".intval($_GET["del"])." LIMIT 1", 'DELETE');
}
// change verification
if(isset($_GET["verify"]) && isset($_GET["s"])) {
	$sql  = "UPDATE ".DB_PREPEND."phpwcms_address SET address_verified=";
	$sql .= intval($_GET["verify"]) ? 1 : 0;
	$sql .= " WHERE address_id=".intval($_GET["s"])." LIMIT 1";
	_dbQuery($sql, 'UPDATE');
}

echo '<div class="title" style="margin-bottom:10px">'.$BL['be_subnav_msg_subscribers'].'</div>';
?>

<div class="navBar imgButton chatlist">
	<a href="phpwcms.php?do=messages&amp;p=4&amp;s=0&amp;edit=1"><img src="img/famfamfam/vcard_add.gif" alt="Add" border="0" /><span><?php echo $BL['be_cnt_new_recipient'] ?></span></a>
	&nbsp;
	<a href="phpwcms.php?do=messages&amp;p=4&amp;duplicate=remove" onclick="return confirm('Delete all duplicate subscribers?');"><img src="img/famfamfam/vcard_delete.gif" alt="Delete" border="0" /><span><?php echo $BL['be_cnt_delete_duplicates'] ?></span></a>
	&nbsp;
	<a href="phpwcms.php?do=messages&amp;p=4&amp;import=1"><img src="img/famfamfam/table_add.gif" alt="Import" border="0" /><span><?php echo $BL['be_newsletter_newimport'] ?></span></a>
	&nbsp;
	<a href="include/inc_act/act_export.php?<?php echo CSRF_GET_TOKEN; ?>&amp;action=exportsubscriber" target="_blank" onclick="return confirm('Export all subscribers based on current selection?');"><img src="img/famfamfam/icon_download.gif" alt="Download" border="0" /><span><?php echo $BL['be_cnt_export_selection'] ?></span></a>
</div>


<?php

// recipient edit form
if(isset($_GET["s"]) && isset($_GET["edit"])) {

	$_userInfo['subscriber_id'] = intval($_GET["s"]);

	if($_userInfo['subscriber_id'] === 0) {

		$_userInfo['subscriber_data']['address_email']			= '';
		$_userInfo['subscriber_data']['address_name']			= '';
		$_userInfo['subscriber_data']['address_id']				= 0;
		$_userInfo['subscriber_data']['address_subscription']	= '';
		$_userInfo['subscriber_data']['address_tstamp']			= date('Y-m-d H:i:s');
		$_userInfo['subscriber_data']['address_verified']		= 0;

	} else {
		$_userInfo['subscriber_data']	= _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_address WHERE address_id=".$_userInfo['subscriber_id']." LIMIT 1");
		if($_userInfo['subscriber_data']) {
			$_userInfo['subscriber_data']  = $_userInfo['subscriber_data'][0];
		}
	}

	if(isset($_POST['subscribe_email'])) {
		include_once PHPWCMS_ROOT.'/include/inc_lib/subscriber.form.inc.php';
	}

	if($_userInfo['subscriber_data']) {
		include_once PHPWCMS_ROOT.'/include/inc_tmpl/subscriber.form.tmpl.php';
	}
}


// import form
if(isset($_GET['import']) && $_GET['import'] === '1') {

	$_userInfo['delimeter']			= ';';
	$_userInfo['subscribe_active']	= 1;
	$_userInfo['subscribe_all']		= 1;
	$_userInfo['subscribe_select']	= array();

	if(isset($_POST['delimeter'])) {

		include_once PHPWCMS_ROOT.'/include/inc_lib/subscriberimport.form.inc.php';

		if(isset($_userInfo['csvError'])) {
			include_once PHPWCMS_ROOT.'/include/inc_tmpl/subscriberimport.form.tmpl.php';
		} else {
			include_once PHPWCMS_ROOT.'/include/inc_tmpl/subscriberimport.result.tmpl.php';
		}

	} else {

		include_once PHPWCMS_ROOT.'/include/inc_tmpl/subscriberimport.form.tmpl.php';

	}

}


// create paginating for users
if(isset($_GET['c'])) {
	$_SESSION['list_user_count'] = (trim($_GET['c']) == 'all') ? '99999' : intval($_GET['c']);
}
if(isset($_GET['page'])) {
	$_SESSION['subscriber_page'] = intval($_GET['page']);
}


// set default values for paginating
if(empty($_SESSION['list_user_count'])) {
	$_SESSION['list_user_count'] = 25;
}

// get filter and paginating form values
if(isset($_POST['do_pagination'])) {

	$_SESSION['list_active']	= empty($_POST['showactive']) ? 0 : 1;
	$_SESSION['list_inactive']	= empty($_POST['showinactive']) ? 0 : 1;
	$_SESSION['list_channel']	= empty($_POST['showchannel']) ? 0 : 1;

	$_SESSION['subscriber_page']	= intval($_POST['page']);
	$_SESSION['filter_subscriber']	= clean_slweg($_POST['filter']);
	if(empty($_SESSION['filter_subscriber'])) {
		unset($_SESSION['filter_subscriber']);
	} else {
		$_SESSION['filter_subscriber']	= convertStringToArray($_SESSION['filter_subscriber'], ' ');
	}
}

if(empty($_SESSION['subscriber_page'])) {
	$_SESSION['subscriber_page'] = 1;
}

// default settings for listing selected users
$_userInfo['list_active']		= isset($_SESSION['list_active'])	? $_SESSION['list_active']		: 1;
$_userInfo['list_inactive']		= isset($_SESSION['list_inactive'])	? $_SESSION['list_inactive']	: 1;
$_userInfo['list_channel']		= isset($_SESSION['list_channel'])	? $_SESSION['list_channel']		: 0;

if($_userInfo['list_channel'] && isset($_POST['showchannel'])) {
	$_userInfo['channel'] = empty($_POST['subscribe_select']) ? false : $_POST['subscribe_select'];
	$_SESSION['channel'] = $_userInfo['channel'];
} elseif($_userInfo['list_channel'] && isset($_SESSION['channel'])) {
	$_userInfo['channel'] = $_SESSION['channel'];
} else {
	$_userInfo['channel'] = false;
}

$_userInfo['list']			= array();
// if admin user should be listed
$_userInfo['where_query']	= '';
if($_userInfo['list_active'] != $_userInfo['list_inactive'] && $_userInfo['list_active']) {
	$_userInfo['where_query']	= ' WHERE address_verified=1';
} elseif($_userInfo['list_active'] != $_userInfo['list_inactive'] && $_userInfo['list_inactive']) {
	$_userInfo['where_query']	= ' WHERE address_verified=0';
}

if(isset($_SESSION['filter_subscriber']) && count($_SESSION['filter_subscriber'])) {

	$_userInfo['filter_array'] = array();

	foreach($_SESSION['filter_subscriber'] as $_userInfo['filter']) {
		//usr_name, usr_login, usr_email
		$_userInfo['filter_array'][] = "CONCAT(address_email, address_name) LIKE '%".aporeplace($_userInfo['filter'])."%'";
	}
	if(count($_userInfo['filter_array'])) {

		$_userInfo['where_query'] .= $_userInfo['where_query'] ? ' AND ' : ' WHERE ';
		$_userInfo['where_query'] .= '('.implode(' OR ', $_userInfo['filter_array']).')';

	}

}

// paginating values
$_userInfo['count_total'] = _dbQuery("SELECT COUNT(*) FROM ".DB_PREPEND."phpwcms_address".$_userInfo['where_query'], 'COUNT');
$_userInfo['pages_total'] = ceil($_userInfo['count_total'] / $_SESSION['list_user_count']);
if($_SESSION['subscriber_page'] > $_userInfo['pages_total']) {
	$_SESSION['subscriber_page'] = empty($_userInfo['pages_total']) ? 1 : $_userInfo['pages_total'];
}


?>
<form action="phpwcms.php?do=messages&amp;p=4" method="post" name="paginate" id="paginate"><input type="hidden" name="do_pagination" value="1" />
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="paginate" summary="">
		<tr>
			<td><table border="0" cellpadding="0" cellspacing="0" summary="">
				<tr>

					<td><input type="checkbox" name="showactive" id="showactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_active'], 1) ?> /></td>
					<td><label for="showactive"><img src="img/button/aktiv_12x13_1.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>
					<td><input type="checkbox" name="showinactive" id="showinactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_inactive'], 1) ?> /></td>
					<td><label for="showinactive"><img src="img/button/aktiv_12x13_0.gif" alt="" style="margin:1px 1px 0 1px;" /></label></td>

					<td<?php if($_userInfo['list_channel']) echo ' class="channelSelectTd"' ?>><input type="checkbox" name="showchannel" id="showchannel" value="1" onclick="this.form.submit();"<?php is_checked(1, $_userInfo['list_channel'], 1) ?> /></td>
					<td<?php if($_userInfo['list_channel']) echo ' class="channelSelectTd"' ?>><label for="showchannel"><img src="img/symbole/newsletter_susbcription.gif" alt="Subscription" style="margin:1px 0 0 1px;" /></label></td>



<?php
if($_userInfo['pages_total'] > 1) {

	echo '<td class="chatlist">|&nbsp;</td>';
	echo '<td>';
	if($_SESSION['subscriber_page'] > 1) {
		echo '<a href="phpwcms.php?do=messages&amp;p=4&amp;page='.($_SESSION['subscriber_page']-1).'">';
		echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" /></a>';
	} else {
		echo '<img src="img/famfamfam/action_back.gif" alt="" border="0" class="inactive" />';
	}
	echo '</td>';
	echo '<td><input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['subscriber_page'];
	echo '"  class="textinput" style="margin:0 3px 0 5px;width:30px;font-weight:bold;" /></td>';
	echo '<td class="chatlist">/'.$_userInfo['pages_total'].'&nbsp;</td>';
	echo '<td>';
	if($_SESSION['subscriber_page'] < $_userInfo['pages_total']) {
		echo '<a href="phpwcms.php?do=messages&amp;p=4&amp;page='.($_SESSION['subscriber_page']+1).'">';
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

	if(isset($_SESSION['filter_subscriber']) && count($_SESSION['filter_subscriber']) ) {
		echo html(implode(' ', $_SESSION['filter_subscriber']));
	}

	?>" class="textinput" style="margin:0 2px 0 0;width:110px;text-align:left;" title="filter results by username, name or email" /></td>
	<td><input type="image" name="gofilter" src="img/famfamfam/action_go.gif" style="margin-right:3px;" /></td>

	</tr>
	</table></td>



	<td class="chatlist" align="right">
		<a href="phpwcms.php?do=messages&amp;p=4&amp;c=10">10</a>
		<a href="phpwcms.php?do=messages&amp;p=4&amp;c=25">25</a>
		<a href="phpwcms.php?do=messages&amp;p=4&amp;c=50">50</a>
		<a href="phpwcms.php?do=messages&amp;p=4&amp;c=100">100</a>
		<a href="phpwcms.php?do=messages&amp;p=4&amp;c=250">250</a>
		<a href="phpwcms.php?do=messages&amp;p=4&amp;c=all"><?php echo $BL['be_ftptakeover_all'] ?></a>
	</td>

	</tr>
</table>
<?php

// set filter select by channel
if($_userInfo['list_channel']) {



	$_userInfo['subscriptions'] = _dbQuery("SELECT * FROM ".DB_PREPEND."phpwcms_subscription ORDER BY subscription_name");

	if($_userInfo['subscriptions']) {

		$_userInfo['select_subscr'] = '';

		foreach($_userInfo['subscriptions'] as $value) {

			$_userInfo['select_subscr'] .= '		<tr>
				<td><input type="checkbox" name="subscribe_select['.$value['subscription_id'].
				']" id="subscribe_select'.$value['subscription_id'].'" value="'.$value['subscription_id'].'"';

			if(!empty($_userInfo['channel'][$value['subscription_id']]) && $_userInfo['channel'][$value['subscription_id']]==$value['subscription_id']) {
				$_userInfo['select_subscr'] .= ' checked="checked"';
			}

			$_userInfo['select_subscr'] .= ' /></td>
				<td><label for="subscribe_select'.$value['subscription_id'].'">'.
				html($value['subscription_name']).
				'</label></td>
			</tr>
			';
		}

		if($_userInfo['select_subscr']) {

			echo '<div id="channelSelect">'.LF;
			echo '<table cellpadding="0" cellspacing="0" border="0">'.LF;
			echo $_userInfo['select_subscr'];
			echo '</table>'.LF;
			//echo '<input type="image" name="gofilter" src="img/famfamfam/action_go.gif" class="channelSelectSubmit" />';
			echo '</div>';

		}

	}




}

?>
</form>
<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">

	<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
	<tr><td colspan="4" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<?php
// loop listing available newsletters
$row_count = 0;

$sql  = "SELECT * FROM ".DB_PREPEND."phpwcms_address".$_userInfo['where_query']." ";
$sql .= "LIMIT ".(($_SESSION['subscriber_page']-1) * $_SESSION['list_user_count']).','.$_SESSION['list_user_count'];
$data = _dbQuery($sql);

foreach($data as $row) {

	// mark selected channel
	if($_userInfo['channel'] !== false) {

		$_userInfo['channel_select'] = ' class="inactive"';

		$row['channel'] = unserialize($row['address_subscription']);
		if(is_array($row['channel']) && count($row['channel'])) {

			foreach($row['channel'] as $channel) {

				if(isset($_userInfo['channel'][$channel])) {
					$_userInfo['channel_select'] = '';
					break;
				}

			}

		}

	} else {

		$_userInfo['channel_select'] = '';

	}

	$row["address_email"] = html($row["address_email"]);
	echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).$_userInfo['channel_select'].">\n<td width=\"25\" style=\"padding:1px 3px 3px 4px;\">";
	echo '<img src="img/famfamfam/vcard.gif" alt="Recipient"></td>'."\n";
	echo '<td width="1%" class="dir">&nbsp;<strong>'.$row["address_email"]."</strong></td>\n";
	echo '<td class="dir" width="95%">&nbsp;'.html($row["address_name"])."</td>\n";
	echo '<td align="right" nowrap="nowrap" class="button_td nowrap">';

	echo '<a href="phpwcms.php?do=messages&amp;p=4&amp;s='.$row["address_id"].'&amp;edit=1">';
	echo '<img src="img/button/edit_22x13.gif" border="0" alt=""></a>';

	echo '<a href="phpwcms.php?do=messages&amp;p=4&amp;s='.$row["address_id"].'&amp;verify=';
	echo ($row["address_verified"]) ? '0' : '1';
	echo '" title="set '.$row["address_email"].' verified/not verified">';
	echo '<img src="img/button/aktiv_12x13_'.$row["address_verified"].'.gif" border="0" alt=""></a>';

	echo '<a href="phpwcms.php?do=messages&amp;p=4&amp;s='.$row["address_id"].'&amp;del='.$row["address_id"];
	echo '" title="delete: '.$row["address_email"].'"';
	echo ' onclick="return confirm(\'Delete subscriber '.js_singlequote($row["address_email"]).'\');">';
	echo '<img src="img/button/trash_13x13_1.gif" border="0" alt=""></a>';

	echo "</td>\n</tr>\n";

	$row_count++;
}

if($row_count) {
	echo '<tr><td colspan="4" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>';
}

?>
	<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="15"></td></tr>
</table>