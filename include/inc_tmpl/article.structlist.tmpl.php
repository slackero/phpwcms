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


//19-11-2004 Fernando Batista -> Copy article, Copy strutures http://fernandobatista.net
//31-03-2005 Fernando Batista -> Copy/Cut Article Content http://fernandobatista.net

?>
<table width="538" border="0" cellpadding="0" cellspacing="0" summary="">
<tr><td colspan="2" class="title"><?php echo $BL['be_article_title'] ?></td></tr>
<tr><td colspan="2" class="title"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
<?php

$listmode = 0;
$cut_id = (isset($_GET["cut"])) ? intval($_GET["cut"]) : 0;
$cut_article = (isset($_GET["acut"])) ? intval($_GET["acut"]) : 0;

$copy_id = (isset($_GET["cop"])) ? intval($_GET["cop"]) : 0;
$copy_article = (isset($_GET["acopy"])) ? intval($_GET["acopy"]) : 0;

if(isset($_GET["open"])) {
	list($open_id, $open_value) = explode(":", $_GET["open"]);
	$open_id = intval($open_id);
	if(empty($open_value)) {
		unset($_SESSION["structure"][$open_id]);
	}
	$_SESSION["structure"][$open_id] = $open_value;
	mysql_query("UPDATE ".DB_PREPEND."phpwcms_user SET usr_var_structure="._dbEscape(serialize($_SESSION["structure"]))." WHERE usr_id=".aporeplace($_SESSION["wcs_user_id"]), $db);
}

//31-03-2005 Fernando Batista  start---------------------------------------------------------------------------
$cut_article_content = (isset($_GET["accut"])) ? intval($_GET["accut"]) : 0;
$copy_article_content = (isset($_GET["accopy"])) ? intval($_GET["accopy"]) : 0;
if(isset($_GET["opena"])) {
	list($open_id, $open_value) = explode(":", $_GET["opena"]);
	$open_id = intval($open_id);
	if(empty($open_value)) {
		unset($_SESSION["structure"]["article"][$open_id]);
	} else {
		$_SESSION["structure"]["article"][$open_id] = $open_value;
	}
	mysql_query("UPDATE ".DB_PREPEND."phpwcms_user SET usr_var_structure="._dbEscape(serialize($_SESSION["structure"]))." WHERE usr_id=".aporeplace($_SESSION["wcs_user_id"]), $db);
}
//31-03-2005 Fernando Batista  end-------------------

$child_count = get_root_childcount(0, $db);
//$an = $BL['be_admin_struct_index'];
$an = $indexpage['acat_name'];

$a  = "<tr onMouseOver=\"this.bgColor='#CCFF00';\" onMouseOut=\"this.bgColor='#FFFFFF';\">\n";
$a .= "<td width=\"450\">";
$a .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" summary=\"\">\n<tr>\n";
$a .= '<td nowrap="nowrap">';
$a .= ($child_count) ? "<a href=\"phpwcms.php?do=articles&amp;open=0:".(($_SESSION["structure"][0])?0:1)."\">" : "";
$a .= "<img src=\"img/symbole/plus_".(($child_count) ? (($_SESSION["structure"][0]) ? "close" : "open") : "empty");
$a .= ".gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"\">".(($child_count) ? "</a>" : "");

$a .= "<img src=\"img/symbole/page_1.gif\" width=\"11\" height=\"15\" ";

$info  = 'ID: <b>0</b><br />';
$info .= 'ALIAS: '.html_specialchars($indexpage["acat_alias"]);
	
$a .= 'onmouseover="Tip(\''.$info.'\');" onmouseout="UnTip()" alt="" />';	

$a .= "</td>\n";
$a .= "<td><img src=\"img/leer.gif\" width=\"2\" height=\"15\" alt=\"\" /></td>\n";
$a .= '<td class="dir" width="95%"><strong>'.$an."</strong></td>\n</tr>\n</table></td>\n";

echo $a;
echo '<td width="88" nowrap="nowrap">';

$struct[0]["acat_id"]		= 0;
$struct[0]["acat_aktiv"]	= 1;
$struct[0]["acat_public"]	= 1;
$struct[0]["acat_struct"]	= 0;

echo listmode_edits ($listmode, $struct, 0, $an, $copy_article_content, $cut_article_content, $copy_article, $copy_id, $cut_article, $cut_id, 0, 0, 0, 0);

echo "</td>\n</tr>\n";

if($_SESSION["structure"][0]) {
       struct_articlelist(0, 0, $copy_article_content, $cut_article_content, $copy_article, $cut_article, $indexpage['acat_order']);//$template_default["article_order"]
       struct_list(0, $db, $copy_article_content, $cut_article_content, $copy_id, $copy_article, $cut_id, $cut_article, $listmode);
}
?>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="5" /></td></tr>
<tr><td colspan="2"><img src="img/lines/l538_70.gif" alt="" width="538" height="1" /></td></tr>
<tr><td colspan="2"><img src="img/leer.gif" alt="" width="1" height="4" /></td></tr>
</table>