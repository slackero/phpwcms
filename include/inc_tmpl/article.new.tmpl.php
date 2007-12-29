<?php
/*************************************************************************************
   Copyright notice
   
   (c) 2002-2007 Oliver Georgi (oliver@phpwcms.de) // All rights reserved.
 
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


//Auswerten der neuen Artikeldaten
$article_cid = isset($_GET["struct"]) ? intval($_GET["struct"]) : 0;

$article_aktiv  			= 1;
$article_alias				= '';
$article_public 			= 1;
$article_timeout 			= '';
$article_nosearch 			= '';
$article_title 				= '';
$article_subtitle 			= '';
$article_notitle 			= 0;
$set_begin 					= 0;
$set_end 					= 0;
$article_begin 				= '';
$article_end 				= '';
$article_redirect 			= '';
$article_keyword			= '';
$article_nositemap 			= 1;
$article_aliasid 			= 0;
$article_headerdata 		= 0;
$article_morelink 			= 1;
$article_pagetitle 			= '';
$article_norss				= 1;

			
if(isset($_POST["article_aktion"]) && intval($_POST["article_aktion"])) {
	$article_cid		= intval($_POST["article_cid"]);
	$article_title		= clean_slweg($_POST["article_title"]);
	$article_alias		= get_alnum_dashes(clean_slweg($_POST["article_alias"]));
	$article_keyword	= clean_slweg($_POST["article_keyword"]);
	$article_aktiv		= isset($_POST["article_aktiv"]) ? 1 : 0;
	$article_notitle	= isset($_POST["article_notitle"]) ? 1 : 0;
	$article_public		= isset($_POST["article_public"]) ? 1 : 0;
	$article_hidesummary= isset($_POST["article_hidesummary"]) ? 1 : 0;
	$article_begin		= clean_slweg($_POST["article_begin"]);
	$article_end		= clean_slweg($_POST["article_end"]);
	$article_subtitle	= clean_slweg($_POST["article_subtitle"]);
	$article_redirect	= clean_slweg($_POST["article_redirect"]);
	$article_nosearch	= isset($_POST['article_nosearch']) ? '1' : '';
	$article_nositemap	= isset($_POST['article_nositemap']) ? 1 : 0;
	$set_begin			= isset($_POST["set_begin"]) ? 1 : 0;
	$set_end			= isset($_POST["set_end"]) ? 1 : 0;
	$article_aliasid	= intval($_POST["article_aliasid"]);
	$article_headerdata	= isset($_POST["article_headerdata"]) ? 1 : 0;
	$article_morelink	= isset($_POST["article_morelink"]) ? 1 : 0;
	$article_pagetitle	= clean_slweg($_POST["article_pagetitle"]);
	$article_norss		= empty($_POST["article_norss"]) ? 0 : 1;
	
	if(isEmpty($article_title)) $article_err = "> ".$BL['be_article_err1']."\n";
	if($article_begin) { //Check date
		$article_begin = strtotime($article_begin);
		if($article_begin == -1) {
			$article_begin = date("Y-m-d H:i:s");
			$set_begin = 1;
			$article_err  .= "> ".$BL['be_article_err2']."\n"; 
		} else {
			$article_begin = date("Y-m-d H:i:s", $article_begin);
			$set_begin = 1;
		}
	} else {
		$article_begin=date("Y-m-d H:i:s");
		$set_begin = 0;
	}
	if($article_end) { //Check date
		$article_end = strtotime($article_end);
		if($article_end == -1) {
			$article_end = date("Y-m-d H:i:s");
			$set_end = 1;
			$article_err  .= "> ".$BL['be_article_err3']."\n"; 
		} else {
			$article_end = date("Y-m-d H:i:s", $article_end);
			$set_end = 1;
		}
	} else {
		$article_end=date("Y-m-d", time()+(60*60*24*365*10)).' 23:59:59';
		$set_end = 0;
	}	//Ende Check Date
	
	$article_timeout = clean_slweg($_POST["article_timeout"]);
	if(isset($_POST['article_cacheoff']) && intval($_POST['article_cacheoff'])) $article_timeout = '0'; //check if cache = Off
	
	/*
	 *	Get sort value for article based on article structure ID
	 */
	$article_sort = getArticleSortValue($article_cid);
	
	if(isEmpty($article_err)) {
		$sql =	"INSERT INTO ".DB_PREPEND."phpwcms_article (".
				"article_cid, article_uid, article_username, article_title, article_alias, ".
				"article_keyword, article_public, article_aktiv, article_begin, ".
				"article_end, article_subtitle, article_redirect, ".
				"article_sort, article_notitle, article_created, article_cache, ".
				"article_nosearch, article_nositemap, article_aliasid, ".
				"article_headerdata, article_morelink, article_pagetitle, article_norss) VALUES ('".
				$article_cid."','".
				aporeplace($_SESSION["wcs_user_id"])."','".
				aporeplace($_SESSION["wcs_user_name"])."','".
				aporeplace($article_title)."','".
				aporeplace($article_alias)."','".
				aporeplace($article_keyword)."','".
				$article_public."','".
				aporeplace($article_aktiv)."','".
				aporeplace($article_begin)."','".
				aporeplace($article_end)."','".
				aporeplace($article_subtitle)."','".
				aporeplace($article_redirect)."', ".
				$article_sort.",".$article_notitle.", '".time()."', '".aporeplace($article_timeout)."', '".
				aporeplace($article_nosearch)."', ".$article_nositemap.", ".$article_aliasid.", ".
				$article_headerdata.", ".$article_morelink.", '".aporeplace($article_pagetitle)."', ".$article_norss.")";
		if($result = mysql_query($sql, $db) or die("error while connecting to database: <br><pre>".$sql.LF.mysql_error()."</pre>")) {
			$article_id = mysql_insert_id($db);
			if(isset($_POST['submitclose'])) {
				headerRedirect(PHPWCMS_URL.'phpwcms.php?do=articles');
			} else {
				headerRedirect(PHPWCMS_URL."phpwcms.php?do=articles&p=2&s=1&aktion=1&id=".$article_id);
			}
		}
	}			
}	
		
?>
<form action="phpwcms.php?do=articles&amp;p=1" method="post" name="article" id="article">
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
	<tr><td colspan="2" class="title"><?php echo $BL['be_article_title1'] ?></td>
	</tr>
	<tr>
		<td width="88"><img src="img/leer.gif" alt="" width="88" height="4" /></td>
		<td width="450"><img src="img/leer.gif" alt="" width="450" height="1" /></td>
	</tr>
	<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8" /></td>
	</tr>
  <?php
  if(!empty($article_err)) {
  ?>
	<tr valign="top" bgcolor="#FFE9D2">
		<td align="right"><strong style="color:#FF6600"><?php echo $BL['be_admin_tmpl_error'] ?>:&nbsp;</strong></td>
		<td><strong style="color:#FF6600"><?php echo nl2br(html_specialchars(chop($article_err))); ?></strong></td>
	</tr>
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
	</tr>
  <?php
  }
  ?>
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_article_cat'] ?>:&nbsp;</td>
		<td><select name="article_cid" id="article_cid" style="width: 325px" class="f11b">
	<?php
		//keine definierte Kategorie = allgemeine Artikelkategorie
		echo "<option value='0'".((!$article_cid)?" selected":"").">".$BL['be_admin_struct_index']."</option>\n";
		struct_select_menu($db, 0, 0, $article_cid);
		?>
		</select></td>
	</tr>
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
	</tr>
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_alias_articleID'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		 <tr>
			<td><input name="article_aliasid" type="text" class="f11b" id="article_aliasid" style="width: 65px" value="<?php echo $article_aliasid ? $article_aliasid : ''; ?>" size="11" maxlength="11" /></td>
			<td>&nbsp;&nbsp;</td>
			<td><input name="article_headerdata" id="article_headerdata" type="checkbox" value="1" <?php is_checked($article_headerdata,1) ?> /></td>
			<td class="v10"><label for="article_headerdata"><?php echo $BL['be_alias_useAll'] ?></label></td>
		 </tr>
	  </table></td>
	</tr>

	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
	</tr>
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_article_atitle'] ?>:&nbsp;</td>
		<td><table border="0" cellpadding="0" cellspacing="0" summary="">
		 <tr>
			<td><input name="article_title" type="text" class="f11b" id="article_title" style="width: 325px" value="<?php echo html_specialchars($article_title) ?>" size="40" maxlength="1000" /></td>
			<td>&nbsp;&nbsp;</td>
			<td><input name="article_notitle" id="article_notitle" type="checkbox" value="1" <?php is_checked($article_notitle,1) ?> /></td>
			<td class="v10"><label for="article_notitle"><?php echo $BL['be_admin_struct_hide1'] ?></label></td>
		 </tr>
	  </table></td>
	</tr>			
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
	</tr>
	<tr>
		<td align="right" class="chatlist"><?php echo $BL['be_article_asubtitle'] ?>:&nbsp;</td>
		<td><input name="article_subtitle" type="text" class="f11b" id="article_subtitle" style="width: 440px" value="<?php echo html_specialchars($article_subtitle) ?>" size="40" maxlength="1000" /></td>
	</tr>
	
	
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6" /></td>
	</tr>
	<tr>
		<td colspan="2"><table border="0" cellpadding="2" cellspacing="0" summary="">
			<tr>
				<td width="84"><img src="img/leer.gif" alt="" width="84" height="1" /></td>
				<td colspan="7"><img src="img/leer.gif" alt="" width="1" height="1" /></td>
			</tr>
					<tr bgcolor="#E7E8EB">
					  <td width="84" align="right" bgcolor="#FFFFFF" class="chatlist">&nbsp;<br /><?php echo $BL['be_article_abegin'] ?>:<img src="img/leer.gif" alt="" width="2" height="1" /></td>
						<td class="chatlist">&nbsp;<br /><input name="set_begin" type="checkbox" id="set_begin" value="1"<?php is_checked(1, $set_begin) ?> onclick="if(!this.checked) {document.article.article_begin.value='';}else{document.article.article_begin.value=formatDate(new Date(),'yyyy-MM-dd HH:mm:ss');}" /></td>
						<td class="chatlist">YYYY-MM-DD HH:MM:SS<br />
						  <input name="article_begin" type="text" id="article_begin" style="width:140px" class="f11" value="<?php echo $article_begin ?>" /></td>
					  <td class="chatlist" valign="bottom"><script language="JavaScript" type="text/javascript">
<!--
function aBegin(date, month, year) {
	document.article.article_begin.value = year + '-' + subrstr('00' + month, 2) + '-' + subrstr('00' + date, 2) + ' 00:00:00';
	document.article.set_begin.checked = true;
}
calBegin = new dynCalendar('calBegin', 'aBegin', 'img/dynCal/');
calBegin.setMonthCombo(false);
calBegin.setYearCombo(false);
//-->
</script><img src="img/leer.gif" alt="" width="3" height="1" /></td>
						<td align="right" bgcolor="#FFFFFF" class="chatlist">&nbsp;<br />&nbsp;&nbsp;<?php echo $BL['be_article_aend'] ?>:</td>
						<td class="chatlist">&nbsp;<br /><input name="set_end" type="checkbox" id="set_end" value="1"<?php is_checked(1, $set_end) ?>  onclick="if(!this.checked) {document.article.article_end.value='';}else{document.article.article_end.value=formatDate(new Date(),'yyyy-MM-dd HH:mm:ss');}" /></td>
						<td class="chatlist">YYYY-MM-DD HH:MM:SS<br />
						  <input name="article_end" type="text" id="article_end" style="width:140px" class="f11" value="<?php echo $article_end ?>" /></td>
					  <td class="chatlist" valign="bottom"><script language="JavaScript" type="text/javascript">
<!--
function aEnd(date, month, year) {
	document.article.article_end.value = year + '-' + subrstr('00' + month, 2) + '-' + subrstr('00' + date, 2) + ' 23:59:59';
	document.article.set_end.checked = true;
}
calEnd = new dynCalendar('calEnd', 'aEnd', 'img/dynCal/');
calEnd.setMonthCombo(false);
calEnd.setYearCombo(false);
//-->
</script><img src="img/leer.gif" alt="" width="3" height="1" /></td>
					</tr>
				</table></td>
			</tr>
			
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
			</tr>
			<tr>
              <td align="right" class="chatlist"><?php echo $BL['be_article_aredirect'] ?>:&nbsp;</td>
              <td><input name="article_redirect" type="text" id="article_redirect" class="f11" style="width: 440px" value="<?php echo html_specialchars($article_redirect) ?>" size="40" /></td>
			</tr>
			
	<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3"></td></tr>
	<tr>
		<td align="right" class="chatlist"><a href="#" onclick="return set_article_alias();"><?php echo $BL['be_article_urlalias'] ?></a>:&nbsp;</td>
		<td><input name="article_alias" type="text" class="f11b" id="article_alias" style="width: 440px" value="<?php echo html_specialchars($article_alias) ?>" size="40" maxlength="200" onfocus="set_article_alias(true);" onchange="this.value=create_alias(this.value);" /></td>
	</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="3" /></td>
			</tr>
			<tr>
              <td align="right" class="chatlist"><?php echo $BL['be_admin_page_pagetitle'] ?>:&nbsp;</td>
              <td><input name="article_pagetitle" type="text" id="article_pagetitle" class="f11" style="width: 440px" value="<?php echo html_specialchars($article_pagetitle) ?>" size="40" maxlength="150" /></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
			</tr>
			<tr valign="top">
				<td align="right" class="chatlist"><img src="img/leer.gif" alt="" width="1" height="3" /><br />
			  <?php echo $BL['be_article_akeywords'] ?>:&nbsp;</td>
				<td><textarea name="article_keyword" rows="5" class="f10" id="article_keyword" style="width: 440px" onkeyup="if(this.value.length &gt; 250) {this.value=this.value.substr(0,250);}"><?php echo html_specialchars($article_keyword) ?></textarea></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
			</tr>
			<tr>
				<td align="right" class="chatlist inactive"><?php echo $BL['be_cache'] ?>:&nbsp;</td>
				<td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
					<tr>
						<td class="inactive"><input name="article_cacheoff" type="checkbox" id="article_cacheoff" value="1" <?php if($article_timeout === '0') echo "checked"; ?> /></td>
						<td class="inactive">&nbsp;<label for="article_cacheoff"><?php echo $BL['be_off'] ?></label>&nbsp;&nbsp;</td>
						<td class="inactive">&nbsp;</td>
						<td class="inactive"><select name="article_timeout" class="f11" style="margin:2px;" onchange="document.article.article_cacheoff.checked=false;">
<?php
echo '<option value=" ">'.$BL['be_admin_tmpl_default']."</option>\n";
echo '<option value="60"'.is_selected($article_timeout, '60', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_minute']."</option>\n";
echo '<option value="300"'.is_selected($article_timeout, '300', 0, 0).'>&nbsp;&nbsp;5 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="900"'.is_selected($article_timeout, '900', 0, 0).'>15 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="1800"'.is_selected($article_timeout, '1800', 0, 0).'>30 '.$BL['be_date_minutes']."</option>\n";
echo '<option value="3600"'.is_selected($article_timeout, '3600', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_hour']."</option>\n";
echo '<option value="14400"'.is_selected($article_timeout, '14400', 0, 0).'>&nbsp;&nbsp;4 '.$BL['be_date_hours']."</option>\n";
echo '<option value="43200"'.is_selected($article_timeout, '43200', 0, 0).'>12 '.$BL['be_date_hours']."</option>\n";
echo '<option value="86400"'.is_selected($article_timeout, '86400', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_day']."</option>\n";
echo '<option value="172800"'.is_selected($article_timeout, '172800', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_days']."</option>\n";
echo '<option value="604800"'.is_selected($article_timeout, '604800', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_week']."</option>\n";
echo '<option value="1209600"'.is_selected($article_timeout, '1209600', 0, 0).'>&nbsp;&nbsp;2 '.$BL['be_date_weeks']."</option>\n";
echo '<option value="2592000"'.is_selected($article_timeout, '2592000', 0, 0).'>&nbsp;&nbsp;1 '.$BL['be_date_month']."</option>\n";
?>
				              </select></td>
				  <td class="inactive">&nbsp;<?php echo $BL['be_cache_timeout'] ?>&nbsp;&nbsp;</td>
				  <td class="chatlist" bgcolor="#FFFFFF">&nbsp;&nbsp;&nbsp;<?php echo $BL['be_ctype_search'] ?>:&nbsp;</td>
				  <td><input name="article_nosearch" type="checkbox" id="article_nosearch" value="1" <?php is_checked(1, $article_nosearch); ?> /></td>
				  <td>&nbsp;<?php echo $BL['be_off'] ?></label>&nbsp;</td>
				  
				  <td class="chatlist" bgcolor="#FFFFFF"><label for="article_norss">&nbsp;&nbsp;<?php echo $BL['be_no_rss'] ?>:&nbsp;</label></td>
				  <td><input name="article_norss" type="checkbox" id="article_norss" value="1" <?php is_checked(1, $article_norss); ?> /></td>
				  
				</tr>
				</table></td>
			</tr>
			
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
			</tr>
			<tr>
				<td align="right" class="chatlist"><?php echo $BL['be_fpriv_status'] ?>:&nbsp;</td>
				<td><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
					<tr>
						<td><input name="article_aktiv" type="checkbox" id="article_aktiv" value="1"<?php is_checked(1, $article_aktiv); ?> /></td>
						<td>&nbsp;<label for="article_aktiv"><?php echo $BL['be_admin_struct_visible'] ?></label>&nbsp;&nbsp;</td>
						<td><input name="article_public" type="checkbox" id="article_public" value="1"<?php is_checked(1, $article_public); ?> /></td>
						<td>&nbsp;<label for="article_public"><?php echo $BL['be_ftptakeover_public'] ?></label>&nbsp;&nbsp;</td>
						<td><input name="article_nositemap" type="checkbox" id="article_nositemap" value="1"<?php is_checked(1, $article_nositemap); ?> /></td>
						<td>&nbsp;<label for="article_nositemap"><?php echo $BL['be_ctype_sitemap'] ?></label>&nbsp;&nbsp;</td>
						<td><input name="article_morelink" type="checkbox" id="article_morelink" value="1"<?php is_checked(1, $article_morelink); ?> /></td>
						<td>&nbsp;<label for="article_morelink"><?php echo $BL['be_article_morelink'] ?></label>&nbsp;&nbsp;</td>
						<td><img src="img/leer.gif" alt="" width="1" height="23" /></td>
					</tr>
				</table></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="15" /></td>
			</tr>
			<tr>
				<td><input name="article_aktion" type="hidden" id="article_aktion" value="1" /></td>
				<td><input name="Submit" type="submit" class="button10" value="<?php echo $BL['be_article_abutton'] ?>" />
				&nbsp;
				  <input name="submitclose" type="submit" class="button10" value="<?php echo $BL['be_article_cnt_button3'] ?>" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="donotsubmit" type="button" class="button10" value="<?php echo $BL['be_newsletter_button_cancel'] ?>" onclick="location.href='phpwcms.php?do=articles'" /></td>
			</tr>
			<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="10" /></td>
			</tr>
			<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td>
			</tr>
</table>
</form>