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

// files
$files_folder = (isset($_GET["f"])) ? intval($_GET["f"]) : 0; //Ermitteln, welcher Unterreiter angezeigt wird

// if cut/paste is active
$add_paste_icon = '<a href="phpwcms.php?do=files&amp;f=0&amp;mkdir=0" title="'.$BL['be_ftab_createnew'].
				  '"><img src="img/button/add_13x13.gif" border="0"></a>';
if(isset($_GET["cut"])) {
	$cutID = intval($_GET["cut"]);
	$add_paste_icon = '<a href="include/inc_act/act_file.php?paste='.$cutID.'|0" title="'.$BL['be_ftab_paste'].
					  '"><img src="img/button/paste_13x13.gif" border="0"></a>';
} else { $cutID=0; }

$change_thumbnail_icon = '<a href="include/inc_act/act_file.php?thumbnail=';
if($_SESSION["wcs_user_thumb"]) {
	$change_thumbnail_icon .= '0" title="'.$BL['be_ftab_disablethumb'].'">';
	$change_thumbnail_icon .= '<img src="img/button/thumbnail_13x13_0.gif" border="0"></a>';
} else {
	$change_thumbnail_icon .= '1" title="'.$BL['be_ftab_enablethumb'].'">';
	$change_thumbnail_icon .= '<img src="img/button/thumbnail_13x13_1.gif" border="0"></a>';
}

?>
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
<tr><td class="title"><?php echo $BL['be_ftab_title'] ?></td></tr>
<tr><td><img src="img/leer.gif" alt="" width="1" height="6" /></td></tr>
<tr><td><table width="538" border="0" cellpadding="2" cellspacing="0" summary="">
	<tr>
		<td width="90" align="center" background="img/background/bg_eckeli.gif" <?php which_folder_active($files_folder, 0) ?>><a href="phpwcms.php?do=files&amp;f=0"><?php echo $BL['be_ftab_private'] ?></a></td>
		<td width="90" align="center" background="img/background/bg_eckeli.gif" <?php which_folder_active($files_folder, 1) ?>><a href="phpwcms.php?do=files&amp;f=1"><?php echo $BL['be_ftab_public'] ?></a></td>
		<td width="90" align="center" background="img/background/bg_eckeli.gif" <?php which_folder_active($files_folder, 3) ?>><a href="phpwcms.php?do=files&amp;f=3"><?php echo $BL['be_ftab_search'] ?></a></td>
		<td width="90" align="center" background="img/background/bg_eckeli.gif" <?php which_folder_active($files_folder, 2) ?>><a href="phpwcms.php?do=files&amp;f=2"><?php echo $BL['be_ftab_trash'] ?></a></td>
		<?php if($files_folder == 0) { ?>
		<td width="162" align="right" background="img/background/bg_ecke_lang.gif" bgcolor="#EBF2F4" class="chatlist"><img src="img/button/root_dir.gif" alt="" width="43" height="13" /><a href="phpwcms.php?do=files&amp;f=0&amp;upload=0" title="<?php echo $BL['be_ftab_upload'] ?>"><img src="img/button/upload_13x13.gif" alt="" width="13" height="13" border="0" /></a><?php echo $add_paste_icon ?><a href="include/inc_help/filehelp.htm" target="_blank" onclick="flevPopupLink(this.href,'filehelp','scrollbars=yes,resizable=yes,width=320,height=300',0);return document.MM_returnValue" style="cursor: help;"><img src="img/button/help_22x13.gif" alt="open file help" width="22" height="13" border="0" /></a><a href="phpwcms.php?do=files&amp;f=0&amp;all=open" title="<?php echo $BL['be_ftab_open'] ?>"><img src="img/button/alle_auf.gif" alt="" width="12" height="13" border="0" /></a><a href="phpwcms.php?do=files&amp;f=0&amp;all=close" title="<?php echo $BL['be_ftab_close'] ?>"><img src="img/button/alle_zu.gif" alt="" width="12" height="13" border="0" /></a><img src="img/leer.gif" alt="" width="5" height="1" /><?php echo $change_thumbnail_icon ?></td>
		<?php } elseif($files_folder == 1) { ?>
		<td width="162" align="right" background="img/background/bg_ecke_lang.gif" bgcolor="#EBF2F4" class="chatlist"><a href="include/inc_help/filehelp.htm" target="_blank" onclick="flevPopupLink(this.href,'filehelp','scrollbars=yes,resizable=yes,width=320,height=300',0);return document.MM_returnValue" style="cursor: help;"><img src="img/button/help_22x13.gif" alt="<?php echo $BL['be_ftab_filehelp'] ?>" width="22" height="13" border="0" /></a><a href="phpwcms.php?do=files&amp;f=1&amp;all=close" title="<?php echo $BL['be_ftab_close'] ?>"><img src="img/button/alle_zu.gif" alt="" width="12" height="13" border="0" /></a><img src="img/leer.gif" alt="" width="5" height="1" /><?php echo $change_thumbnail_icon ?></td>
		<?php } elseif($files_folder == 3) { ?>
		<td width="162" align="right" background="img/background/bg_ecke_lang.gif" bgcolor="#EBF2F4" class="chatlist"><a href="include/inc_help/filehelp.htm" target="_blank" onclick="flevPopupLink(this.href,'filehelp','scrollbars=yes,resizable=yes,width=320,height=300',0);return document.MM_returnValue" style="cursor: help;"><img src="img/button/help_22x13.gif" alt="<?php echo $BL['be_ftab_filehelp'] ?>" width="22" height="13" border="0" /></a><img src="img/leer.gif" alt="" width="5" height="1" /><?php echo $change_thumbnail_icon ?></td>
		<?php } else { ?>
		<td width="162" class="chatlist">&nbsp;</td>
		<?php } ?>
	</tr></table></td></tr>
<tr><td bgcolor="#9BBECA"><img src="img/leer.gif" alt="" width="1" height="4" /></td>
</tr>
</table>
