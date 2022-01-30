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


// set backend listing values
$_phpwcms_home['homeMaxArticles'] = empty($_COOKIE['homeMaxArticles']) ? 10 : intval($_COOKIE['homeMaxArticles']);
$_phpwcms_home['homeMaxCntParts'] = empty($_COOKIE['homeMaxCntParts']) ? 10 : intval($_COOKIE['homeMaxCntParts']);
$_phpwcms_home['homeCntType'] = empty($_COOKIE['homeCntType']) ? '' : $_COOKIE['homeCntType'];

if(isset($_POST['homeMaxArticles'])) {
	if($_phpwcms_home['homeMaxArticles'] = intval($_POST['homeMaxArticles'])) {
		@setcookie('homeMaxArticles', strval($_phpwcms_home['homeMaxArticles']) , time()+31536000, '/', getCookieDomain(), PHPWCMS_SSL, true); // store cookie for 1 year
	}
}
if(isset($_POST['homeMaxCntParts'])) {
	if($_phpwcms_home['homeMaxCntParts'] = intval($_POST['homeMaxCntParts'])) {
		@setcookie('homeMaxCntParts', strval($_phpwcms_home['homeMaxCntParts']) , time()+31536000, '/', getCookieDomain(), PHPWCMS_SSL, true); // store cookie for 1 year
	}
	$_phpwcms_home['homeCntType'] = clean_slweg($_POST['homeCntType']);
	@setcookie('homeCntType', $_phpwcms_home['homeCntType'], time()+31536000, '/', getCookieDomain(), PHPWCMS_SSL, true); // store cookie for 1 year
	$_SESSION['phpwcms_backend_search'] = '';
}

// set if user has admin rights
$_usql = $_SESSION["wcs_user_admin"] ? '' : 'AND article_uid='.intval($_SESSION["wcs_user_id"]).' ';

// first list last edited articles
$_asql_1  = "SELECT *, DATE_FORMAT(acontent_tstamp, '%d/%m/%Y %H:%i') AS acontent_changed FROM ".DB_PREPEND."phpwcms_articlecontent t1 ";
$_asql_1 .= "LEFT JOIN ".DB_PREPEND."phpwcms_article t2 ON ";
$_asql_1 .= "t1.acontent_aid = t2.article_id ";
$_asql_1 .= 'WHERE t1.acontent_trash=0 AND t2.article_deleted=0 ';
$_asql_1 .= $_usql;
if(is_intval($_phpwcms_home['homeCntType'])) {
	$_asql_1 .= ' AND t1.acontent_type=' . _dbEscape($_phpwcms_home['homeCntType']);
}
if(!empty($_SESSION['phpwcms_backend_search'])) {
	$_asql_1 .= " AND (";
	$_asql_1 .= "	CONCAT(t1.acontent_title,t1.acontent_subtitle,t1.acontent_text,t1.acontent_html) LIKE '%"._dbEscape($_SESSION['phpwcms_backend_search'], FALSE)."%'";
	$_asql_1 .= " OR ";
	$_asql_1 .= "	CONCAT(t2.article_title,t2.article_subtitle,t2.article_summary) LIKE '%"._dbEscape($_SESSION['phpwcms_backend_search'], FALSE)."%'";
	$_asql_1 .= " ) ";

	$_be_search = $BL['be_ctype_search'].': ' . html($_SESSION['phpwcms_backend_search']) ;

} else {
	$_be_search = $BL['be_last_edited'];
}
$_asql_1 .= ' ORDER BY acontent_tstamp DESC LIMIT '.$_phpwcms_home['homeMaxCntParts'];
$_last10_articlecontent = _dbQuery($_asql_1);

$_asql_1  = "SELECT article_id, article_cid, article_title, article_subtitle, article_aktiv, article_uid, ";
$_asql_1 .= "date_format(article_tstamp, '%d/%m/%Y %H:%i') AS article_date ";
$_asql_1 .= 'FROM '.DB_PREPEND.'phpwcms_article WHERE article_deleted=0 ';
$_asql_1 .= $_usql;
if(!empty($_SESSION['phpwcms_backend_search'])) {
	$_asql_1 .= " AND CONCAT(article_title,article_subtitle,article_summary) LIKE '%"._dbEscape($_SESSION['phpwcms_backend_search'], FALSE)."%' ";
}
$_asql_1 .= 'ORDER BY article_tstamp DESC LIMIT '.$_phpwcms_home['homeMaxArticles'];
$_last10_article = _dbQuery($_asql_1);

?>
<div style="margin:0 0 10px 0;padding:0;">
	<form class="formRightInput" action="phpwcms.php?do=home" id="setHomeMaxArticles" name="setHomeMaxArticles" method="post">
		<select name="homeMaxArticles" onchange="this.form.submit();">
	<?php foreach(array(5,10,15,25,50,75,100,150) as $x): ?>
			<option value="<?php echo $x ?>"<?php is_selected($_phpwcms_home['homeMaxArticles'], $x) ?>><?php echo $x ?></option>
	<?php endforeach; ?>
			<option value="99999"<?php is_selected(99999, $_phpwcms_home['homeMaxArticles']) ?>><?php echo $BL['be_ftptakeover_all'] ?></option>
		</select>
	</form>
	<h1 class="title" style="margin-top:5px;"><?php echo $BL['be_cnt_articles'] .' <span class="v10">('. $_be_search . ')</span>' ?></h1>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">

	<tr class="tableHeadRow">
		<th>&nbsp;</th>
		<th style="text-align:left"><?php echo $BL['be_article_atitle'] ?></th>
		<th><?php echo $BL['be_cnt_last_edited'] ?></th>
		<th>&nbsp;</th>
	</tr>

	<tr><td colspan="4" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>

<?php

	if(count($_last10_article)) {

		$row_count = 0;

		foreach($_last10_article as $value) {

			if($row_count) {
				echo '<tr><td colspan="4" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>'.LF;
			}

			echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).' class="listrow" style="cursor:pointer" ';
			echo 'onclick="document.location.href=\'phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id='.$value['article_id'].'\'" title="'.$BL['be_func_struct_edit'].'">'.LF;
			echo '	<td style="width:11px;padding:1px 4px 1px 2px;"><img src="img/symbole/text_1.gif" alt="" /></td>'.LF;
			echo '	<td class="overflow-ellipsis home-article">'.html($value['article_title']);
			if($value['article_subtitle']) {
				echo ' / ' . html($value['article_subtitle']);
			}
			echo '</td>'.LF;
			echo '	<td align="center" class="nowrap" style="width:115px">&nbsp;'.$value['article_date'].'&nbsp;</td>'.LF;
			echo '	<td style="padding:3px;width:42px;" class="nowrap">';
			echo '<img src="img/button/visible_12x13_'.$value["article_aktiv"].'.gif" alt="" border="0" style="margin-right:2px;" />';
			echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id='.$value['article_id'];
			echo '"><img src="img/button/edit_22x13.gif" alt="Edit" border="0" /></a>';
			echo '</td>'.LF;
			echo '</tr>'.LF;

			$row_count++;

		}

		echo '<tr><td colspan="4" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>'.LF;
	}

?>
	<tr>
		<td colspan="4" style="padding: 6px 0 0 3px;">
			<input type="button" value="<?php echo $BL['be_subnav_article_center'] ?>" class="button" onclick="document.location.href='phpwcms.php?do=articles'" />
			<input type="button" value="<?php echo $BL['be_subnav_article_new'] ?>" class="button" onclick="document.location.href='phpwcms.php?do=articles&amp;p=1&amp;struct=0'" />
		</td>
	</tr>

</table>

<div style="margin:25px 0 10px 0;padding:0;">
	<form class="formRightInput" action="phpwcms.php?do=home" id="setHomeMaxCntParts" name="setHomeMaxCntParts" method="post">
		<select name="homeCntType" onChange="this.form.submit();" class="width150">
			<option value="">&#8211;</option>
	<?php foreach($wcs_content_type as $key => $value): ?>
			<option value="<?php echo $key ?>"<?php is_selected($_phpwcms_home['homeCntType'], $key) ?>><?php echo $value ?></option>
	<?php endforeach; ?>
		</select><select name="homeMaxCntParts" onchange="this.form.submit();">
	<?php foreach(array(5,10,15,25,50,75,100,150,200,250) as $x): ?>
			<option value="<?php echo $x ?>"<?php is_selected($_phpwcms_home['homeMaxCntParts'], $x) ?>><?php echo $x ?></option>
	<?php endforeach; ?>
			<option value="99999"<?php is_selected(99999, $_phpwcms_home['homeMaxCntParts']) ?>><?php echo $BL['be_ftptakeover_all'] ?></option>
		</select>
	</form>
	<h1 class="title" style="margin:0;"><?php echo $BL['be_ctype'] .' <span class="v10">('. $_be_search .')</span>' ?></h1>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">

	<tr class="tableHeadRow">
		<th width="20">&nbsp;</th>
		<th style="text-align:left"><?php echo $BL['be_cnt_type'] ?>&nbsp;</th>
		<th style="text-align:left"><?php echo $BL['be_article_atitle'].'/'.$BL['be_profile_label_notes'] ?></th>
		<th><?php echo $BL['be_cnt_last_edited'] ?>&nbsp;</th>
		<th>&nbsp;</th>
	</tr>

	<tr><td colspan="5" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<?php

	if(count($_last10_articlecontent)) {

		$row_count = 0;

		foreach($_last10_articlecontent as $value) {

			if(($value["acontent_type"] == 30 && !isset($phpwcms['modules'][$value["acontent_module"] ])) || !isset($wcs_content_type[$value["acontent_type"]])) {
				continue;
			}

			if($row_count) {
				echo '<tr><td colspan="5" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>'.LF;
			}

			echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).' class="listrow" style="cursor:pointer" ';
			echo 'onclick="document.location.href=\'phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;';
			echo 'id='.$value['acontent_aid'].'&amp;acid='.$value['acontent_id'].'\'" title="'.$BL['be_func_content_edit'].'">'.LF;

			echo '	<td style="padding:1px 4px 1px 2px;width:11px;"><img src="img/symbole/add_content.gif" alt="" /></td>'.LF;

			echo '	<td class="overflow-ellipsis home-type">'.$wcs_content_type[$value["acontent_type"]];
			if($value["acontent_type"] == 30) {
				echo ': '.$BL['modules'][$value["acontent_module"]]['listing_title'];
			}
			echo '&nbsp;</td>'.LF;

			$value['notice'] = str_replace('###', ', ', trim($value['acontent_title'].'###'.$value['acontent_subtitle'].'###'.$value['acontent_comment'], '#'));
			if($value['notice']) {
				$value['notice_long'] = $value['article_title'] . ' > ' . $value['notice'];
				$value['notice'] = getCleanSubString($value['article_title'], 15, '.') . ' > ' . $value['notice'];
			} else {
				$value['notice_long'] = $value['notice'] = $value['article_title'];
			}

			$value['notice'] = html(preg_replace('/\s+/', ' ', $value['notice'], false));

			echo '	<td class="overflow-ellipsis home-cp" title="'.$BL['be_func_content_edit'].': '.html($value['notice_long'], false).'" style="font-weight:normal">'.$value['notice'].'</td>'.LF;
			echo '	<td align="center" class="nowrap" style="width:115px">&nbsp;'.$value['acontent_changed'].'&nbsp;</td>'.LF;

			echo '	<td style="padding:3px;width:42px;" class="nowrap">';
			echo '<img src="img/button/visible_12x13_'.$value["acontent_visible"].'.gif" alt="" border="0" style="margin-right:2px;" />';
			echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;';
			echo 'id='.$value['acontent_aid'].'&amp;acid='.$value['acontent_id'];
			echo '"><img src="img/button/edit_22x13.gif" alt="Edit" border="0" /></a>';
			echo '</td>'.LF;
			echo '</tr>'.LF;

			$row_count++;

		}

		echo '<tr><td colspan="5" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>'.LF;
	}

?>
	<tr><td colspan="5"><img src="img/leer.gif" alt="" width="1" height="25" /></td></tr>
</table>