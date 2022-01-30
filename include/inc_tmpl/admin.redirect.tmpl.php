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


if(isset($_POST['rid']) && !isset($_POST['donotsubmit'])) {

	$data_result = update_404redirect();

} elseif(isset($_GET['rid']) && intval($_GET['rid']) && isset($_GET['active'])) {

	_dbUpdate('phpwcms_redirect', array('active'=>empty($_GET['active']) ? 1 : 0), 'rid='.intval($_GET['rid']));

} else {

	$data_result = array(
		'error' => NULL,
		'data'	=> array()
	);
}

// List Redirects
if(!isset($_GET['rid']) || isset($_GET['active'])) {

	$_entry = array('query' => '');

	// Pagination
	if(isset($_GET['c'])) {
		$_SESSION['redirect_list_count'] = $_GET['c'] == 'all' ? '99999' : intval($_GET['c']);
	}
	if(isset($_GET['page'])) {
		$_SESSION['redirect_detail_page'] = intval($_GET['page']);
	}

	// set default values for paginating
	if(empty($_SESSION['redirect_list_count'])) {
		$_SESSION['redirect_list_count'] = 25;
	}

	// paginate and search form processing
	if(isset($_POST['do_pagination'])) {

		$_SESSION['redirect_list_active']	= empty($_POST['showactive']) ? 0 : 1;
		$_SESSION['redirect_list_inactive']	= empty($_POST['showinactive']) ? 0 : 1;

		$_SESSION['redirect_filter']		= clean_slweg($_POST['filter']);
		if(empty($_SESSION['redirect_filter'])) {
			unset($_SESSION['redirect_filter']);
		} else {
			$_SESSION['redirect_filter']	= convertStringToArray($_SESSION['redirect_filter'], ' ');
			$_POST['filter']				= $_SESSION['redirect_filter'];
		}

		$_SESSION['redirect_detail_page'] = intval($_POST['page']);
	}

	if(empty($_SESSION['redirect_detail_page'])) {
		$_SESSION['redirect_detail_page'] = 1;
	}

	$_entry['list_active']		= isset($_SESSION['redirect_list_active']) ? $_SESSION['redirect_list_active'] : 1;
	$_entry['list_inactive']	= isset($_SESSION['redirect_list_inactive']) ? $_SESSION['redirect_list_inactive'] : 1;

	// set correct status query
	if($_entry['list_active'] != $_entry['list_inactive']) {

		if(!$_entry['list_active']) {
			$_entry['query'] .= 'active=0';
		}
		if(!$_entry['list_inactive']) {
			$_entry['query'] .= 'active=1';
		}

	} else {
		$_entry['query'] .= 'active!=9';
	}

	if(isset($_SESSION['redirect_filter']) && is_array($_SESSION['redirect_filter']) && count($_SESSION['redirect_filter'])) {

		$_entry['filter_array'] = array();

		foreach($_SESSION['redirect_filter'] as $_entry['filter']) {
			// search in alias/target fields
			$_entry['filter_array'][] = "CONCAT(alias, target) LIKE '%"._dbEscape($_entry['filter'], false)."%'";
		}
		if(count($_entry['filter_array'])) {
			$_SESSION['redirect_filter'] = ' AND ('.implode(' OR ', $_entry['filter_array']).')';
			$_entry['query'] .= $_SESSION['redirect_filter'];
		}

	} elseif(isset($_SESSION['redirect_filter']) && is_string($_SESSION['redirect_filter'])) {

		$_entry['query'] .= $_SESSION['redirect_filter'];

	}

	// paginating values
	$_entry['count_total'] = _dbCount('SELECT COUNT(rid) FROM '.DB_PREPEND.'phpwcms_redirect WHERE '.$_entry['query']);
	$_entry['pages_total'] = ceil($_entry['count_total'] / $_SESSION['redirect_list_count']);
	if($_SESSION['redirect_detail_page'] > $_entry['pages_total']) {
		$_SESSION['redirect_detail_page'] = empty($_entry['pages_total']) ? 1 : $_entry['pages_total'];
	}

	$_entry['limit'] = $_entry['pages_total'] > 1 ? (($_SESSION['redirect_detail_page']-1) * $_SESSION['redirect_list_count']).','.$_SESSION['redirect_list_count'] : '';

	// now retrieve all articles
	$result = _dbGet('phpwcms_redirect', '*, UNIX_TIMESTAMP(changed) AS timestamp', $_entry['query'], '', 'changed DESC, views DESC', $_entry['limit']);

?>
	<h1 class="title"><?php echo $BL['be_links'] . ' &amp; ' . $BL['be_redirects']; ?></h1>

	<form action="phpwcms.php?do=admin&amp;p=14" method="post" style="margin-bottom:1em;">
		<input type="hidden" name="do_pagination" value="1" /><?php if($_entry['pages_total'] <= 1): ?><input type="hidden" name="page" id="page" value="1" /><?php endif; ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">
			<tr>
				<td width="30%" class="nowrap chatlist">
					<a href="phpwcms.php?do=admin&amp;p=14&amp;rid=0" title="<?php echo $BL['be_new_linkredirect'] ?>">
						<img src="img/famfamfam/arrow_forward.png" alt="Add" border="0" style="vertical-align:middle;" /><?php echo $BL['be_new_linkredirect'] ?>
					</a>
				</td>
				<td style="padding:0 10px 0 20px;" class="nowrap">
					<label>
						<input type="checkbox" name="showactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_active'], 1) ?> />
						<img src="img/button/aktiv_12x13_1.gif" alt="" style="vertical-align:middle;" />
					</label>
					<label>
						<input type="checkbox" name="showinactive" value="1" onclick="this.form.submit();"<?php is_checked(1, $_entry['list_inactive'], 1) ?> />
						<img src="img/button/aktiv_12x13_0.gif" alt="" style="vertical-align:middle;" />
					</label>
				</td>
<?php
		if($_entry['pages_total'] > 1) {
			echo '<td class="chatlist nowrap" style="padding:0 10px 0 0;">';
			if($_SESSION['redirect_detail_page'] > 1) {
				echo '<a href="phpwcms.php?do=admin&amp;p=14&amp;page='.($_SESSION['redirect_detail_page']-1).'"><img src="img/famfamfam/action_back.gif" alt="" style="vertical-align:middle;margin:0 3px;" /></a>';
			} else {
				echo '<img src="img/famfamfam/action_back.gif" alt="" class="inactive" style="vertical-align:middle;margin:0 3px;" />';
			}

			echo '<input type="text" name="page" id="page" maxlength="4" size="4" value="'.$_SESSION['redirect_detail_page'].'" class="width20 f11b center-text" />/'.$_entry['pages_total'];
			if($_SESSION['redirect_detail_page'] < $_entry['pages_total']) {
				echo '<a href="phpwcms.php?do=admin&amp;p=14&amp;page='.($_SESSION['redirect_detail_page']+1).'"><img src="img/famfamfam/action_forward.gif" alt="" style="vertical-align:middle;margin:0 3px;" /></a>';
			} else {
				echo '<img src="img/famfamfam/action_forward.gif" alt="" class="inactive" style="vertical-align:middle;margin:0 3px;" />';
			}
			echo '</td>';
		}
?>
				<td style="padding:0 10px 0 0;" class="nowrap">
					<input type="search" name="filter" size="10" value="<?php	if(isset($_POST['filter']) && is_array($_POST['filter']) ) echo html(implode(' ', $_POST['filter'])); ?>" class="width100" />
					<input type="image" name="gofilter" src="img/famfamfam/action_go.gif" style="vertical-align:middle;" />
				</td>

				<td class="chatlist nowrap" align="right" width="30%">
					<a href="phpwcms.php?do=admin&amp;p=14&amp;c=10">10</a>
					<a href="phpwcms.php?do=admin&amp;p=14&amp;c=25">25</a>
					<a href="phpwcms.php?do=admin&amp;p=14&amp;c=50">50</a>
					<a href="phpwcms.php?do=admin&amp;p=14&amp;c=100">100</a>
					<a href="phpwcms.php?do=admin&amp;p=14&amp;c=250">250</a>
					<a href="phpwcms.php?do=admin&amp;p=14&amp;c=all"><?php echo $BL['be_ftptakeover_all'] ?></a>
				</td>
			</tr>
		</table>
	</form>

	<table width="538" border="0" cellpadding="0" cellspacing="0" class="listing" summary="">

		<tr class="header">
			<th class="column news"><?php echo $BL['be_cnt_source'] ?></th>
			<th class="column"><?php echo $BL['be_cnt_target'] ?></th>
			<th class="column"><?php echo $BL['be_views'] ?></th>
			<th class="column"><?php echo $BL['be_newsletter_changed'] ?></th>
			<th class="column collast"> </th>
		</tr>

<?php

	$x = 0;

	$target_types = array(
		'alias' => $BL['be_alias'],
		'id' => $BL['be_structure_id'],
		'aid' => $BL['be_func_struct_articleID'],
		'link' => $BL['be_profile_label_website'].'/'.$BL['be_link']
	);

	foreach($result as $data) {

		$data['source'] = array();
		if($data['alias']) {
			$data['source'][] = $BL['be_alias'].':&nbsp;'.html($data['alias']);
		}
		if($data['aid']) {
			$data['source'][] = $BL['be_func_struct_articleID'].':&nbsp;'.html($data['aid']);
		}
		if($data['id']) {
			$data['source'][] = $BL['be_structure_id'].':&nbsp;'.html($data['id']);
		}

		$data['source'] = implode(', ', $data['source']);
		if(!$data['source']) {
			$data['source'] = '&#8212;';
		}

		//if($data["target"]) {
			$data["target"] = $data["type"] ? ($target_types[$data["type"]] . ': '.html($data["target"])) : $BL['be_admin_struct_index'];
			$data['enable_switch_prefix']  = '<a href="phpwcms.php?do=admin&amp;p=14&amp;rid='.$data["rid"];
			$data['enable_switch_prefix'] .= '&amp;active='.$data['active'].'" title="' . $BL['be_fprivfunc_cactive'];
			$data['enable_switch_prefix'] .= ': '.$data['source'] . ' &gt; ' . $data["target"].'">';
			$data['enable_switch_suffix']  = '</a>';
		/*} else {
			$data['enable_switch_prefix']  = '<span style="padding:0 1px">';
			$data['enable_switch_suffix']  = '</span>';
			$data["target"] = $BL['be_admin_struct_index'];
		}*/

		// now add article URL
		echo '	<tr class="row'.($x%2?' alt': '').'" title="' . $data['source'] . ' &gt; ' . $data["target"].'">';
		echo '		<td style="width:40%">' . $data["source"] . "</td>" . LF;
		echo '		<td style="width:40%">' . $data["target"] . "</td>" . LF;
		echo '		<td style="width:5%">' . $data["views"] . "</td>" . LF;
		echo '		<td class="nowrap">'.date($BL['default_date'], $data["timestamp"])."</td>" . LF;
		echo '		<td class="nowrap">';
		echo '<a href="phpwcms.php?do=admin&amp;p=14&amp;rid='.$data["rid"].'"><img src="img/button/edit_22x13.gif" alt="" border="0" height="13" width="22" /></a>';
		echo $data['enable_switch_prefix'];
		echo '<img src="img/button/aktiv_12x13_'.$data['active'].'.gif" alt="" border="0" height="13" width="12" />';
		echo $data['enable_switch_suffix'];
		echo '</td>' . LF . '	</tr>' . LF;

		$x++;
	}

?>

	</table>

<?php

// Edit Redirects
} else {

	$rid = empty($_GET['rid']) ? 0 : intval($_GET['rid']);

	// now retrieve selected item
	if($rid) {
		$data = _dbGet('phpwcms_redirect', '*, UNIX_TIMESTAMP(changed) AS timestamp', 'rid='.$rid, '', 'changed DESC, views DESC');
	}

	if(isset($data[0])) {

		$data = $data[0];

	} else {

		$data = array(
			'rid'		=> 0,
			'alias'		=> '',
			'id'		=> '',
			'aid'		=> '',
			'type'		=> '',
			'active'	=> 0,
			'shortcut'	=> 0,
			'views'		=> 0,
			'timestamp'	=> now(),
			'target'	=> '',
			'code'		=> ''
		);
	}

	if(count($data_result['data'])) {

		$data = array_merge($data, $data_result['data']);

	}


?>
<h1 class="title"><?php echo ($data['rid'] ? $BL['be_cnt_guestbook_edit'] : $BL['be_article_cnt_button2']) . ': ' . $BL['be_link'] . ' &amp; ' . $BL['be_redirect'] ?></h1>
<form action="phpwcms.php?do=admin&amp;p=14&amp;rid=<?php echo $data['rid'] ?>" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">

	<tr><td colspan="4" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_cnt_type'] ?>:&nbsp;</td>
		<td>
			<select name="shortcut">
				<option value="0"<?php if($data['shortcut'] != 1): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_redirect'].'/'.$BL['be_article_cnt_redirect'] ?></option>
				<option value="1"<?php echo is_selected(1, $data['shortcut']) ?>><?php echo $BL['be_shortcut'] ?></option>
			</select>
		</td>

		<td align="right" class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_views'] ?>:&nbsp;</td>
		<td><strong><?php echo $data['views'] ?></strong></td>
	</tr>

	<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="7" /></td></tr>

	<tr class="formrowdark"><td colspan="4" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

	<tr class="formrowdark">
		<td align="right" class="chatlist">&nbsp;<?php echo $BL['be_alias'].'/'.$BL['be_shortcut'] ?>:&nbsp;</td>
		<td colspan="3"><input name="alias" type="text" class="width440" value="<?php echo html($data['alias']) ?>" size="50" /></td>
	</tr>

	<tr class="formrowdark">
		<td align="right" class="chatlist"><?php echo $BL['be_func_struct_articleID'] ?>:&nbsp;</td>
		<td><input name="aid" type="text" class="code width175" value="<?php echo empty($data['aid']) ? '' : $data['aid'] ?>" size="15" /></td>

		<td align="right" class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_structure_id'] ?>:&nbsp;</td>
		<td><input name="id" type="text" class="code width175" value="<?php echo empty($data['id']) ? '' : $data['id'] ?>" size="15" /></td>
	</tr>

	<tr class="formrowdark"><td colspan="4" class="rowspacer7x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

	<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="7" /></td></tr>

	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_target_type'] ?>:&nbsp;</td>
		<td>
			<select name="type">
				<option value=""<?php if(empty($data['type'])): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_admin_struct_index'] ?></option>
				<option value="alias"<?php echo is_selected('alias', $data['type']) ?>><?php echo $BL['be_alias'] ?></option>
				<option value="id"<?php echo is_selected('id', $data['type']) ?>><?php echo $BL['be_structure_id'] ?></option>
				<option value="aid"<?php echo is_selected('aid', $data['type']) ?>><?php echo $BL['be_func_struct_articleID'] ?></option>
				<option value="link"<?php echo is_selected('link', $data['type']) ?>><?php echo $BL['be_profile_label_website'].'/'.$BL['be_link'] ?></option>
			</select>
		</td>

		<td align="right" class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_http_status'] ?>:&nbsp;</td>
		<td>
			<select name="code">
				<option value=""<?php if(empty($data['code'])): ?> selected="selected"<?php endif; ?>><?php echo $BL['be_admin_tmpl_default'] ?> (302)</option>
				<option value="301"<?php echo is_selected('301', $data['code']) ?>><?php echo $BL['be_http_status301'] ?> (301)</option>
				<option value="307"<?php echo is_selected('307', $data['code']) ?>><?php echo $BL['be_http_status307'] ?> (301)</option>
				<option value="404"<?php echo is_selected('404', $data['code']) ?>><?php echo $BL['be_http_status404'] ?> (404)</option>
				<option value="401"<?php echo is_selected('401', $data['code']) ?>><?php echo $BL['be_http_status401'] ?> (401)</option>
				<option value="503"<?php echo is_selected('503', $data['code']) ?>><?php echo $BL['be_http_status503'] ?> (503)</option>
			</select>
		</td>
	</tr>

	<tr><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>

	<tr>
		<td align="right" class="chatlist">&nbsp;<?php echo $BL['be_cnt_target'] ?>:&nbsp;</td>
		<td colspan="3"><input name="target" type="text" class="width440" value="<?php echo html($data['target']) ?>" size="50" /></td>
	</tr>

	<tr><td colspan="4" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

	<tr>
		<td>&nbsp;</td>
		<td>
			<label>
				<input type="checkbox" name="active" value="1"<?php is_checked(1, $data['active']) ?> />
				<?php echo $BL['be_ftptakeover_active'] ?>
			</label>
		</td>

		<td align="right" class="chatlist">&nbsp;&nbsp;<?php echo $BL['be_newsletter_changed'] ?>:&nbsp;</td>
		<td><?php echo date($BL['be_longdatetime'], $data["timestamp"]) ?></td>
	</tr>

	<tr><td colspan="4" class="rowspacer7x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

	<tr class="formrowdark"><td colspan="4"><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
	<tr class="formrowdark">
		<td>&nbsp;</td>
		<td colspan="2">
			<input type="submit" class="button" value="<?php echo $rid ? $BL['be_article_cnt_button3'] : $BL['be_article_cnt_button2'] ?>" />
			<input type="reset" class="button" value="<?php echo $BL['be_cnt_field']['reset'] ?>" />
			&nbsp;&nbsp;
			<input name="donotsubmit" type="button" class="button" value="<?php echo  $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=admin&p=14'" />
			<input type="hidden" name="rid" value="<?php echo $data['rid'] ?>" />
		</td>
		<td align="right">
			<?php if($rid): ?><input type="submit" class="button" name="delete_<?php echo md5($rid) ?>" value="<?php echo $BL['be_cnt_delete'] ?>" onclick="return confirm('<?php echo $BL['be_delete_dataset'].' [ID:'.$rid.']' ?>');" /><?php endif; ?>&nbsp;&nbsp;
		</td>
	</tr>

	<tr class="formrowdark"><td colspan="4" class="rowspacer7x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

</table>
</form>
<?php

}

?>