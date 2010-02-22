<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2010 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


// set backend listing values 
$_phpwcms_home['homeMaxArticles'] = empty($_COOKIE['homeMaxArticles']) ? 5 : intval($_COOKIE['homeMaxArticles']);
$_phpwcms_home['homeMaxCntParts'] = empty($_COOKIE['homeMaxCntParts']) ? 5 : intval($_COOKIE['homeMaxCntParts']);

if(isset($_POST['homeMaxArticles'])) {
	if($_phpwcms_home['homeMaxArticles'] = intval($_POST['homeMaxArticles'])) {
		@setcookie('homeMaxArticles', strval($_phpwcms_home['homeMaxArticles']) , time()+31536000); // store cookie for 1 year
	}
}
if(isset($_POST['homeMaxCntParts'])) {
	if($_phpwcms_home['homeMaxCntParts'] = intval($_POST['homeMaxCntParts'])) {
		@setcookie('homeMaxCntParts', strval($_phpwcms_home['homeMaxCntParts']) , time()+31536000); // store cookie for 1 year
	}
}
// set default if necessary
if(!$_phpwcms_home['homeMaxArticles']) $_phpwcms_home['homeMaxArticles'] = 5;
if(!$_phpwcms_home['homeMaxCntParts']) $_phpwcms_home['homeMaxCntParts'] = 5;

// set if user has admin rights
$_usql = $_SESSION["wcs_user_admin"] ? '' : 'AND article_uid='.intval($_SESSION["wcs_user_id"]).' ';

// first list last edited articles
$_asql_1  = "SELECT *, DATE_FORMAT(acontent_tstamp, '%d/%m/%Y %H:%i') AS acontent_changed FROM ".DB_PREPEND."phpwcms_articlecontent ";
$_asql_1 .= "LEFT JOIN ".DB_PREPEND."phpwcms_article ON ";
$_asql_1 .= DB_PREPEND."phpwcms_articlecontent.acontent_aid = ".DB_PREPEND."phpwcms_article.article_id "; 
$_asql_1 .= 'WHERE acontent_trash=0 AND article_deleted=0 ';
$_asql_1 .= $_usql;
$_asql_1 .= 'ORDER BY acontent_tstamp DESC LIMIT '.$_phpwcms_home['homeMaxCntParts'];
$_last10_articlecontent = _dbQuery($_asql_1);

$_asql_1  = "SELECT article_id, article_cid, article_title, article_public, article_aktiv, article_uid, ";
$_asql_1 .= "date_format(article_tstamp, '%d/%m/%Y %H:%i') AS article_date ";
$_asql_1 .= "FROM ".DB_PREPEND."phpwcms_article ";
$_asql_1 .= 'WHERE article_deleted=0 ';
$_asql_1 .= $_usql;
$_asql_1 .= 'ORDER BY article_tstamp DESC LIMIT '.$_phpwcms_home['homeMaxArticles'];
$_last10_article = _dbQuery($_asql_1);


?>
<div style="margin:0 0 10px 0;padding:0;">
<form class="formRightInput" action="phpwcms.php" id="setHomeMaxArticles" name="setHomeMaxArticles" method="post">
<input type="text" name="homeMaxArticles" id="homeMaxArticles" value="<?php echo $_phpwcms_home['homeMaxArticles'] ?>" class="smallInputField" onblur="this.form.submit();" />
</form>
<h1 class="title" style="margin-top:5px;"><?php echo $BL['be_cnt_articles'] .' <span class="v10">('.$BL['be_last_edited'].')</span>' ?></h1>
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
	$row_count = 0;
	
	foreach($_last10_article as $value) {
	
		if($row_count) {
			echo '<tr><td colspan="4" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>'.LF;
		}
	
		echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).' class="listrow" style="cursor:pointer" ';
		echo 'onclick="document.location.href=\'phpwcms.php?do=articles&p=2&s=1&id='.$value['article_id'].'\'" title="'.$BL['be_func_struct_edit'].'">'.LF;
		echo '	<td style="padding:1px 4px 1px 2px;"><img src="img/symbole/text_1.gif" alt="" /></td>'.LF;
		echo '	<td width="80%"><strong>'.html_specialchars($value['article_title']).'</strong></td>'.LF;
		echo '	<td align="center" nowrap="nowrap">&nbsp;'.$value['article_date'].'&nbsp;</td>'.LF;
		echo '	<td style="padding:3px;" nowrap="nowrap">';
		echo '<img src="img/button/visible_12x13_'.$value["article_aktiv"].'.gif" alt="" border="0" style="margin-right:2px;" />';
		echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;id='.$value['article_id'];
		echo '"><img src="img/button/edit_22x13.gif" alt="Edit" border="0" /></a>';
		echo '</td>'.LF;
		echo '</tr>'.LF;
	
		$row_count++;

	}
	
	if($row_count) {
		echo '<tr><td colspan="4" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>'.LF;
	}


?>	
	<tr>
		<td colspan="4" style="padding: 6px 0 0 3px;">
			<input type="button" value="<?php echo $BL['be_subnav_article_center'] ?>" class="button10" onclick="document.location.href='phpwcms.php?do=articles'" />
			<input type="button" value="<?php echo $BL['be_subnav_article_new'] ?>" class="button10" onclick="document.location.href='phpwcms.php?do=articles&p=1&struct=0'" />
		</td>
	</tr>

</table>

<div style="margin:25px 0 10px 0;padding:0;">
<form class="formRightInput" action="phpwcms.php" id="setHomeMaxCntParts" name="setHomeMaxCntParts" method="post">
<input type="text" name="homeMaxCntParts" id="homeMaxCntParts" value="<?php echo $_phpwcms_home['homeMaxCntParts'] ?>" class="smallInputField" onblur="this.form.submit();" />
</form>
<h1 class="title" style="margin:0;"><?php echo $BL['be_ctype'] .' <span class="v10">('.$BL['be_last_edited'].')</span>' ?></h1>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">
	
	<tr class="tableHeadRow">
		<th width="20">&nbsp;</th>
		<th style="text-align:left"><?php echo $BL['be_cnt_type'] ?>&nbsp;</th>
		<th style="text-align:left"><?php echo $BL['be_article_cnt_ctitle'] ?></th>
		<th><?php echo $BL['be_cnt_last_edited'] ?>&nbsp;</th>
		<th>&nbsp;</th>
	</tr>
	
	<tr><td colspan="5" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
	

<?php
	$row_count = 0;
	
	foreach($_last10_articlecontent as $value) {
	
		if(($value["acontent_type"] == 30 && !isset($phpwcms['modules'][$value["acontent_module"] ])) || !isset($wcs_content_type[$value["acontent_type"]])) {
			continue;
		}
	
		if($row_count) {
			echo '<tr><td colspan="5" bgcolor="#D9DEE3"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>'.LF;
		}
	
		echo '<tr'.( ($row_count % 2) ? ' bgcolor="#F3F5F8"' : '' ).' class="listrow" style="cursor:pointer" ';
		echo 'onclick="document.location.href=\'phpwcms.php?do=articles&p=2&s=1&aktion=2&';
		echo 'id='.$value['acontent_aid'].'&acid='.$value['acontent_id'].'\'" title="'.$BL['be_func_content_edit'].'">'.LF;
		
		echo '	<td style="padding:1px 4px 1px 2px;"><img src="img/symbole/add_content.gif" alt="" /></td>'.LF;
		
		echo '	<td nowrap="nowrap">'.$wcs_content_type[$value["acontent_type"]];
		if($value["acontent_type"] == 30) {
			echo ': '.$BL['modules'][$value["acontent_module"]]['listing_title'];
		}
		echo '&nbsp;</td>'.LF;
		
		$trenner = ($value['acontent_title'] && $value['acontent_subtitle']) ? '/' : '';
		
		echo '	<td width="80%"><strong>'.html_specialchars(getCleanSubString($value['acontent_title'].$trenner.$value['acontent_subtitle'], 27, '&#8230;')).'</strong></td>'.LF;
		echo '	<td align="center" nowrap="nowrap">&nbsp;'.$value['acontent_changed'].'&nbsp;</td>'.LF;
		
		echo '	<td style="padding:3px;" nowrap="nowrap">';
		echo '<img src="img/button/visible_12x13_'.$value["acontent_visible"].'.gif" alt="" border="0" style="margin-right:2px;" />';
		echo '<a href="phpwcms.php?do=articles&amp;p=2&amp;s=1&amp;aktion=2&amp;';
		echo 'id='.$value['acontent_aid'].'&amp;acid='.$value['acontent_id'];
		echo '"><img src="img/button/edit_22x13.gif" alt="Edit" border="0" /></a>';
		echo '</td>'.LF;
		echo '</tr>'.LF;

		$row_count++;

	}
	
	if($row_count) {
		echo '<tr><td colspan="5" bgcolor="#92A1AF"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>'.LF;
	}


?>
	<tr><td colspan="5"><img src="img/leer.gif" alt="" width="1" height="25" /></td></tr>
</table>
<?php echo phpwcmsversionCheck(); ?>