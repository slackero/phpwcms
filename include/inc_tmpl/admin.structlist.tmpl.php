<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2026, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
 *
 **/

// 19-11-2004 Fernando Batista

// ----------------------------------------------------------------
// obligate check for cmsGO! constants
if (!defined('CMSGO_ROOT')) {
	die("You Cannot Access This Script Directly, Have a Nice Day.");
}
// ----------------------------------------------------------------


?><table width="100%" border="0" cellpadding="0" cellspacing="0" summary="">
<tr><td colspan="2" class="title"><?php echo $BL['be_admin_struct_title'] ?></td></tr>
<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="1"></td></tr>
<?php

$listmode				= 1;
$cut_id					= empty($_GET["cut"]) ? 0 : intval($_GET["cut"]);
$cut_article			= empty($_GET["acut"]) ? 0 : intval($_GET["acut"]);
$copy_id				= empty($_GET["cop"]) ? 0 : intval($_GET["cop"]);
$copy_article			= empty($_GET["acopy"]) ? 0 : intval($_GET["acopy"]);
$cut_article_content	= empty($_GET["accut"]) ? 0 : intval($_GET["accut"]);
$copy_article_content	= empty($_GET["accopy"]) ? 0 : intval($_GET["accopy"]);

if(isset($_GET["open"])) {
	list($open_id, $open_value) = explode(":", $_GET["open"]);
	$open_id = intval($open_id);
	if(empty($open_value)) {
		unset($_SESSION["structure"][$open_id]);
	} else {
		$_SESSION["structure"][$open_id] = $open_value;
	}
}

$child_count		= get_root_childcount(0);
$child_sort			= ( $child_count + 1 ) * 10;
$struct_template	= _dbQuery('SELECT template_default, template_name FROM '.DB_PREPEND.'cmsgo_template WHERE template_trash=0 AND template_id='.intval($indexpage['acat_template']));

echo "<tr class=\"hover-warning\">";
echo "<td width=\"450\">";
echo "<table class=\"table-borderless\"><tr>";
echo '<td class="text-nowrap">';
echo ($child_count) ? "<a href=\"cmsgo.php?do=admin&amp;p=6&amp;open=0:".(empty($_SESSION["structure"][0])?1:0)."\">" : "";
echo "<img src=\"img/symbole/plus_".(($child_count) ? (empty($_SESSION["structure"][0]) ? "open" : "close") : "empty");
echo ".gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"\" />".(($child_count) ? "</a>" : "");
echo '<img src="img/symbole/page_1.gif" width="11" height="15" alt="ID:0"';
echo 'onmouseover="Tip(\'ID: <b>0</b><br>', $BL['be_alias'], ': ', html($indexpage["acat_alias"]);
if(isset($struct_template[0]['template_name'])) {
	echo '<br>', $BL['be_admin_struct_template'], ': ';
	echo html($struct_template[0]["template_name"]);
	if($struct_template[0]["template_default"]) {
		echo ' (', $BL['be_admin_tmpl_default'], ')';
	}
}
echo '<br>'.$BL['be_onepage_id'].': '.(empty($indexpage["acat_onepage"]) ? $BL['be_no'] : $BL['be_yes']);
echo '\');" onmouseout="UnTip()">';
echo "</td><td><img src=\"img/leer.gif\" width=\"2\" height=\"15\" alt=\"\" /></td>";
echo '<td class="dir" width="97%"><strong>'.$indexpage['acat_name']."</strong></td></tr></table></td>";
echo '<td class="text-nowrap" style="width: 99px;">';

$struct[0]["acat_id"]     = 0;
$struct[0]["acat_aktiv"]  = 1;
$struct[0]["acat_struct"] = 0;

echo listmode_edits($listmode, $struct, 0, $indexpage['acat_name'], $copy_article_content, $cut_article_content, $copy_article, $copy_id, $cut_article, $cut_id, 0, 0, 0, $child_sort);
echo "</td></tr>";
if(!empty($_SESSION["structure"][0])) {
	struct_list(0, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode);
}

?>
<tr><td colspan="2" class="rowspacer7x7"><img src="img/leer.gif" alt="" width="1" height="1" /></td></tr>
</table>
