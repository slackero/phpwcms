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


// Sitemap

if(!isset($content['sitemap'])) {

	$content['sitemap']["before"]			= '';
	$content['sitemap']["after"]			= '';
	$content['sitemap']["catimg"]			= '';
	$content['sitemap']["articleimg"]		= '';
	$content['sitemap']["startid"]			= 0;
	$content['sitemap']["display"]			= 0;
	$content['sitemap']["catclass"]			= '';
	$content['sitemap']["articleclass"]		= '';
	$content['sitemap']["classcount"]		= 0;
	$content['sitemap']["without_parent"]	= 0;

}

?>
<tr><td colspan="2" class="rowspacer0x7"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>

<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_guestbook_before'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="csitemap_before" cols="40" rows="3" class="code width440 autosize" id="csitemap_before"><?php echo html($content["sitemap"]["before"]) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist" valign="top"><img src="img/leer.gif" alt="" width="1" height="15"><?php echo $BL['be_cnt_guestbook_after'] ?>:&nbsp;</td>
  <td valign="top"><textarea name="csitemap_after" cols="40" rows="3" class="code width440 autosize" id="csitemap_after"><?php echo html($content["sitemap"]["after"]) ?></textarea></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_catimage'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
  <tr><td><input name="csitemap_catimg" type="text" id="csitemap_catimg" class="f11" style="width: 350px" value="<?php echo html($content["sitemap"]["catimg"]) ?>" size="40"></td><td>&nbsp;<?php
  if($content["sitemap"]["catimg"]) echo '<img src="'.$content["sitemap"]["catimg"].'" border="0">';
  ?></td></tr></table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_articleimage'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" summary="">
  <tr><td><input name="csitemap_articleimg" type="text" id="csitemap_articleimg" class="f11" style="width: 350px" value="<?php echo html($content["sitemap"]["articleimg"]) ?>" size="40"></td><td>&nbsp;<?php
  if($content["sitemap"]["articleimg"]) echo '<img src="'.$content["sitemap"]["articleimg"].'" border="0">';
  ?></td></tr></table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>

<tr>
	<td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_startid'] ?>:&nbsp;</td>
	<td><select name="csitemap_startid" id="csitemap_startid" class="width325">
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
  	<td><input name="csitemap_display" id="csitemap_display0" type="radio" value="0" <?php is_checked(0, $content["sitemap"]["display"]) ?>></td>
	<td><label for="csitemap_display0"><?php echo $BL['be_cnt_sitemap_structuronly'] ?></label>&nbsp;&nbsp;</td>
	<td><input name="csitemap_display" id="csitemap_display1" type="radio" value="1" <?php is_checked(1, $content["sitemap"]["display"]) ?>></td>
	<td><label for="csitemap_display1"><?php echo $BL['be_cnt_sitemap_structurarticle'] ?></label>&nbsp;&nbsp;</td>
	<td><input name="csitemap_without_parent" id="csitemap_without_parent" type="checkbox" value="1" <?php is_checked(1, $content["sitemap"]["without_parent"]) ?>></td>
	<td><label for="csitemap_without_parent"><?php echo $BL['be_cnt_sitemap_without_parent'] ?></label>&nbsp;&nbsp;</td>
  </tr></table></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="6"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_catclass'] ?>:&nbsp;</td>
  <td valign="top"><input name="csitemap_catclass" type="text" id="csitemap_catclass" class="f11" style="width: 350px" value="<?php echo html($content["sitemap"]["catclass"]) ?>" size="40"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="2"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_articleclass'] ?>:&nbsp;</td>
  <td valign="top"><input name="csitemap_articleclass" type="text" id="csitemap_articleclass" class="f11" style="width: 350px" value="<?php echo html($content["sitemap"]["articleclass"]) ?>" size="40"></td>
</tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="8"></td></tr>
<tr>
  <td align="right" class="chatlist"><?php echo $BL['be_cnt_sitemap_count'] ?>:&nbsp;</td>
  <td valign="top"><table border="0" cellpadding="0" cellspacing="0" bgcolor="#E7E8EB" summary="">
  <tr>
  	<td><input name="csitemap_classcount" id="csitemap_classcount0" type="radio" value="0" <?php is_checked(0, $content["sitemap"]["classcount"]) ?>></td>
	<td><label for="csitemap_classcount0"><?php echo $BL['be_cnt_sitemap_noclasscount'] ?></label>&nbsp;&nbsp;</td>
	<td><input name="csitemap_classcount" id="csitemap_classcount1" type="radio" value="1" <?php is_checked(1, $content["sitemap"]["classcount"]) ?>></td>
	<td><label for="csitemap_classcount1"><?php echo $BL['be_cnt_sitemap_classcount'] ?></label>&nbsp;&nbsp;</td>
  </tr></table></td>
</tr>

