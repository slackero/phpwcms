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


// Sitemap

if(!isset($content['sitemap'])) {

	$content['sitemap']["before"]		= '';
	$content['sitemap']["after"]		= '';
	$content['sitemap']["catimg"]		= '';
	$content['sitemap']["articleimg"]	= '';
	$content['sitemap']["startid"]		= 0;
	$content['sitemap']["display"]		= 0;
	$content['sitemap']["catclass"]		= '';
	$content['sitemap']["articleclass"]	= '';
	$content['sitemap']["classcount"]	= 0;

}

?>
<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_guestbook_before'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="csitemap_before" cols="40" rows="3" class="code" id="csitemap_before" style="width: 440px"><?php echo html_specialchars($content["sitemap"]["before"]) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_guestbook_after'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="csitemap_after" cols="40" rows="3" class="code" id="csitemap_after" style="width: 440px"><?php echo html_specialchars($content["sitemap"]["after"]) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_catimage'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
  <tr><td><input name="csitemap_catimg" type="text" id="csitemap_catimg" class="f11" style="width: 350px" value="<?php echo html_specialchars($content["sitemap"]["catimg"]) ?>" size="40"></td><td>&nbsp;<?php
  if($content["sitemap"]["catimg"]) echo '<img src="'.$content["sitemap"]["catimg"].'" border="0">';
  ?></td></tr></table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_articleimage'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
  <tr><td><input name="csitemap_articleimg" type="text" id="csitemap_articleimg" class="f11" style="width: 350px" value="<?php echo html_specialchars($content["sitemap"]["articleimg"]) ?>" size="40"></td><td>&nbsp;<?php
  if($content["sitemap"]["articleimg"]) echo '<img src="'.$content["sitemap"]["articleimg"].'" border="0">';
  ?></td></tr></table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_startid'] ?>:&nbsp;</td>
	<td><select name="csitemap_startid" id="csitemap_startid" style="width: 325px" class="f11b">
<?php
	echo "<option value='0'".((!$content["sitemap"]["startid"])?" selected":"").">".$BL['be_admin_struct_index']."</option>\n";
	struct_select_menu(0, 0, $content["sitemap"]["startid"]);
?></select></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>

<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_display'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
  <tr>
  	<td><input name="csitemap_display" type="radio" value="0" <?php is_checked(0, $content["sitemap"]["display"]) ?>></td>
	<td><?php echo $BL['be_cnt_sitemap_structuronly'] ?>&nbsp;&nbsp;</td>
	<td><input name="csitemap_display" type="radio" value="1" <?php is_checked(1, $content["sitemap"]["display"]) ?>></td>
	<td><?php echo $BL['be_cnt_sitemap_structurarticle'] ?>&nbsp;&nbsp;&nbsp;</td>
  </tr></table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_catclass'] ?>:&nbsp;</td>
  <td valign="top"><input name="csitemap_catclass" type="text" id="csitemap_catclass" class="f11" style="width: 350px" value="<?php echo html_specialchars($content["sitemap"]["catclass"]) ?>" size="40"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_articleclass'] ?>:&nbsp;</td>
  <td valign="top"><input name="csitemap_articleclass" type="text" id="csitemap_articleclass" class="f11" style="width: 350px" value="<?php echo html_specialchars($content["sitemap"]["articleclass"]) ?>" size="40"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_count'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
  <tr>
  	<td><input name="csitemap_classcount" type="radio" value="0" <?php is_checked(0, $content["sitemap"]["classcount"]) ?>></td>
	<td><?php echo $BL['be_cnt_sitemap_noclasscount'] ?>&nbsp;&nbsp;</td>
	<td><input name="csitemap_classcount" type="radio" value="1" <?php is_checked(1, $content["sitemap"]["classcount"]) ?>></td>
	<td><?php echo $BL['be_cnt_sitemap_classcount'] ?>&nbsp;&nbsp;&nbsp;</td>
  </tr></table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5"></td></tr>