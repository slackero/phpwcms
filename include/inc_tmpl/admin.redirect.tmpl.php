<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <oliver@phpwcms.de>
 * @copyright Copyright (c) 2002-2013, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.de
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
if(empty($_GET['rid']) || isset($_GET['active'])) {

	echo '<h1 class="title">' . $BL['be_links'] . ' &amp; ' . $BL['be_redirects'] . '</h1>'.LF;
	
	// now retrieve all articles
	$result = _dbGet('phpwcms_redirect', '*, UNIX_TIMESTAMP(changed) AS timestamp', '', '', 'changed DESC, views DESC');
	
	?>
	
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
			$data['source'][] = $BL['be_alias'].':&nbsp;'.html_specialchars($data['alias']);
		}
		if($data['aid']) {
			$data['source'][] = $BL['be_func_struct_articleID'].':&nbsp;'.html_specialchars($data['aid']);
		}
		if($data['id']) {
			$data['source'][] = $BL['be_structure_id'].':&nbsp;'.html_specialchars($data['id']);
		}
		
		$data['source'] = implode(', ', $data['source']);
		if(!$data['source']) {
			$data['source'] = '&#8212;';
		}
		
		//if($data["target"]) {
			$data["target"] = $data["type"] ? ($target_types[$data["type"]] . ': '.html_specialchars($data["target"])) : $BL['be_admin_struct_index'];
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

<h1 class="title"><?php $BL['be_cnt_guestbook_edit'] . ': ' . $BL['be_link'] . ' &amp; ' . $BL['be_redirect'] ?></h1>

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
		<td colspan="3"><input name="alias" type="text" class="width440" value="<?php echo html_specialchars($data['alias']) ?>" size="50" /></td>
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
		<td colspan="3"><input name="target" type="text" class="width440" value="<?php echo html_specialchars($data['target']) ?>" size="50" /></td>
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
		<td colspan="3">
			<input type="submit" class="button10" value="<?php echo $rid ? $BL['be_article_cnt_button3'] : $BL['be_article_cnt_button2'] ?>" />
			<input type="reset" class="button10" value="<?php echo $BL['be_cnt_field']['reset'] ?>" />
			&nbsp;&nbsp;
			<input name="donotsubmit" type="button" class="button10" value="<?php echo  $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=admin&p=14'" />
			
			<input type="hidden" name="rid" value="<?php echo $data['rid'] ?>" />
		</td>
	</tr>
	
	<tr class="formrowdark"><td colspan="4" class="rowspacer7x0"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

</table>
</form>
<?php

}

?>
